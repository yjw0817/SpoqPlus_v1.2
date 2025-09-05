<?php

namespace App\Models;

use CodeIgniter\Model;

class LogAnalysisModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'log_analysis';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'log_date', 'error_level', 'error_message', 'file_path', 'line_number',
        'stack_trace', 'user_id', 'user_name', 'company_cd', 'branch_cd',
        'request_url', 'request_method', 'request_params', 'session_data',
        'user_agent', 'ip_address', 'error_hash', 'occurrence_count',
        'first_occurred', 'last_occurred', 'is_resolved', 'resolved_by',
        'resolved_at', 'fix_applied'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * 로그 파일을 파싱하여 구조화된 데이터로 변환
     */
    public function parseLogFile($logFilePath)
    {
        if (!file_exists($logFilePath)) {
            return false;
        }

        $logContent = file_get_contents($logFilePath);
        $logEntries = [];
        
        // 로그 엔트리를 분리 (날짜 패턴으로 분리)
        $pattern = '/^(CRITICAL|ERROR|WARNING|INFO|DEBUG) - (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}) --> (.+?)(?=^(?:CRITICAL|ERROR|WARNING|INFO|DEBUG) - \d{4}|\z)/ms';
        preg_match_all($pattern, $logContent, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $entry = [
                'error_level' => $match[1],
                'log_date' => $match[2],
                'error_message' => trim($match[3])
            ];

            // 스택 트레이스 파싱
            if (strpos($entry['error_message'], '#0') !== false) {
                $lines = explode("\n", $entry['error_message']);
                $entry['error_message'] = $lines[0];
                
                // 파일 경로와 라인 번호 추출
                if (preg_match('/(.+?)\((\d+)\):/', $lines[1], $fileMatch)) {
                    $entry['file_path'] = trim($fileMatch[1]);
                    $entry['line_number'] = (int)$fileMatch[2];
                }
                
                // 스택 트레이스 저장
                $entry['stack_trace'] = implode("\n", array_slice($lines, 1));
            }

            // 오류 해시 생성 (중복 체크용)
            $entry['error_hash'] = md5($entry['error_level'] . $entry['error_message'] . ($entry['file_path'] ?? '') . ($entry['line_number'] ?? ''));
            
            $logEntries[] = $entry;
        }

        return $logEntries;
    }

    /**
     * 로그 엔트리 저장 또는 업데이트
     */
    public function saveLogEntry($logData)
    {
        // 중복 체크
        $existing = $this->where('error_hash', $logData['error_hash'])
                         ->first();

        if ($existing) {
            // 기존 오류면 카운트 증가
            $updateData = [
                'occurrence_count' => $existing['occurrence_count'] + 1,
                'last_occurred' => $logData['log_date']
            ];
            
            return $this->update($existing['id'], $updateData);
        } else {
            // 새로운 오류
            $logData['first_occurred'] = $logData['log_date'];
            $logData['last_occurred'] = $logData['log_date'];
            $logData['occurrence_count'] = 1;
            
            return $this->insert($logData);
        }
    }

    /**
     * 대시보드용 통계 데이터 조회
     */
    public function getDashboardStats($companyCode = null, $branchCode = null)
    {
        // 테이블 존재 여부 확인
        if (!$this->db->tableExists($this->table)) {
            return [
                'today_stats' => [],
                'trend_data' => [],
                'top_errors' => [],
                'affected_users' => 0
            ];
        }
        
        $builder = $this->db->table($this->table);
        
        if ($companyCode) {
            $builder->where('company_cd', $companyCode);
        }
        if ($branchCode) {
            $builder->where('branch_cd', $branchCode);
        }

        // 오늘의 오류 통계
        $today = date('Y-m-d');
        $todayStats = $builder->select('error_level, COUNT(*) as count')
                              ->where('DATE(log_date)', $today)
                              ->groupBy('error_level')
                              ->get()
                              ->getResultArray();

        // 최근 7일 트렌드
        $weekAgo = date('Y-m-d', strtotime('-7 days'));
        $trendData = $builder->select('DATE(log_date) as date, error_level, COUNT(*) as count')
                             ->where('log_date >=', $weekAgo)
                             ->groupBy(['DATE(log_date)', 'error_level'])
                             ->orderBy('date')
                             ->get()
                             ->getResultArray();

        // TOP 10 빈번한 오류
        $topErrors = $builder->select('id, error_level, error_message, file_path, line_number, last_occurred, SUM(occurrence_count) as total_count')
                             ->where('is_resolved', false)
                             ->groupBy(['error_message', 'file_path', 'line_number'])
                             ->orderBy('total_count', 'DESC')
                             ->limit(10)
                             ->get()
                             ->getResultArray();

        // 영향받은 사용자 수
        $affectedUsers = $builder->select('COUNT(DISTINCT user_id) as affected_users')
                                 ->where('DATE(log_date)', $today)
                                 ->where('user_id IS NOT NULL')
                                 ->get()
                                 ->getRowArray();

        return [
            'today_stats' => $todayStats,
            'trend_data' => $trendData,
            'top_errors' => $topErrors,
            'affected_users' => $affectedUsers['affected_users'] ?? 0
        ];
    }

    /**
     * 로그 검색
     */
    public function searchLogs($filters = [])
    {
        // 테이블 존재 여부 확인
        if (!$this->db->tableExists($this->table)) {
            return [
                'data' => [],
                'total' => 0,
                'page' => 1,
                'per_page' => 50,
                'total_pages' => 0
            ];
        }
        
        $builder = $this->db->table($this->table);

        // 날짜 필터
        if (!empty($filters['start_date'])) {
            $builder->where('log_date >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('log_date <=', $filters['end_date']);
        }

        // 오류 레벨 필터
        if (!empty($filters['error_level'])) {
            $builder->where('error_level', $filters['error_level']);
        }

        // 사용자 필터
        if (!empty($filters['user_id'])) {
            $builder->where('user_id', $filters['user_id']);
        }

        // 키워드 검색
        if (!empty($filters['keyword'])) {
            $builder->groupStart()
                    ->like('error_message', $filters['keyword'])
                    ->orLike('file_path', $filters['keyword'])
                    ->orLike('request_url', $filters['keyword'])
                    ->groupEnd();
        }

        // 해결 상태
        if (isset($filters['is_resolved'])) {
            $builder->where('is_resolved', $filters['is_resolved']);
        }

        // 회사/지점 필터
        if (!empty($filters['company_cd'])) {
            $builder->where('company_cd', $filters['company_cd']);
        }
        if (!empty($filters['branch_cd'])) {
            $builder->where('branch_cd', $filters['branch_cd']);
        }

        // 정렬
        $orderBy = $filters['order_by'] ?? 'log_date';
        $orderDir = $filters['order_dir'] ?? 'DESC';
        $builder->orderBy($orderBy, $orderDir);

        // 페이징
        $page = $filters['page'] ?? 1;
        $perPage = $filters['per_page'] ?? 50;
        $offset = ($page - 1) * $perPage;

        $totalCount = $builder->countAllResults(false);
        $results = $builder->limit($perPage, $offset)->get()->getResultArray();

        return [
            'data' => $results,
            'total' => $totalCount,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($totalCount / $perPage)
        ];
    }

    /**
     * 오류 상세 정보 조회
     */
    public function getErrorDetail($errorId)
    {
        // 테이블 존재 여부 확인
        if (!$this->db->tableExists($this->table)) {
            return null;
        }
        
        $error = $this->find($errorId);
        
        if (!$error) {
            return null;
        }

        // 파일 내용 읽기 (오류 발생 전후 코드)
        if ($error['file_path'] && file_exists($error['file_path']) && $error['line_number']) {
            $lines = file($error['file_path']);
            $startLine = max(0, $error['line_number'] - 10);
            $endLine = min(count($lines), $error['line_number'] + 10);
            
            $codeContext = [];
            for ($i = $startLine; $i < $endLine; $i++) {
                $codeContext[] = [
                    'line_number' => $i + 1,
                    'code' => $lines[$i] ?? '',
                    'is_error_line' => ($i + 1) == $error['line_number']
                ];
            }
            
            $error['code_context'] = $codeContext;
        }

        // 수정 이력 조회
        $fixHistory = $this->db->table('error_fix_history')
                               ->where('error_id', $errorId)
                               ->orderBy('fixed_at', 'DESC')
                               ->get()
                               ->getResultArray();
        
        $error['fix_history'] = $fixHistory;

        // 비슷한 오류 조회
        $similarErrors = $this->where('error_hash', $error['error_hash'])
                              ->where('id !=', $errorId)
                              ->orderBy('log_date', 'DESC')
                              ->limit(10)
                              ->findAll();
        
        $error['similar_errors'] = $similarErrors;

        return $error;
    }

    /**
     * 오류 해결 처리
     */
    public function resolveError($errorId, $fixData)
    {
        $this->db->transStart();

        // 오류 상태 업데이트
        $this->update($errorId, [
            'is_resolved' => true,
            'resolved_by' => $fixData['resolved_by'],
            'resolved_at' => date('Y-m-d H:i:s'),
            'fix_applied' => $fixData['fix_description'] ?? ''
        ]);

        // 수정 이력 저장
        if (!empty($fixData['fixed_code'])) {
            $this->db->table('error_fix_history')->insert([
                'error_id' => $errorId,
                'fixed_by' => $fixData['resolved_by'],
                'fixed_at' => date('Y-m-d H:i:s'),
                'file_path' => $fixData['file_path'],
                'original_code' => $fixData['original_code'] ?? '',
                'fixed_code' => $fixData['fixed_code'],
                'fix_description' => $fixData['fix_description'] ?? '',
                'is_auto_fix' => $fixData['is_auto_fix'] ?? false
            ]);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * 통계 요약 데이터 생성 (크론잡용)
     */
    public function generateStatisticsSummary($date = null)
    {
        $date = $date ?? date('Y-m-d');
        
        $sql = "
            INSERT INTO log_statistics_summary 
                (stat_date, error_level, company_cd, branch_cd, error_count, 
                 unique_errors, affected_users, resolved_count, avg_resolution_time)
            SELECT 
                ? as stat_date,
                error_level,
                IFNULL(company_cd, '') as company_cd,
                IFNULL(branch_cd, '') as branch_cd,
                COUNT(*) as error_count,
                COUNT(DISTINCT error_hash) as unique_errors,
                COUNT(DISTINCT user_id) as affected_users,
                SUM(CASE WHEN is_resolved = 1 THEN 1 ELSE 0 END) as resolved_count,
                AVG(CASE 
                    WHEN is_resolved = 1 AND resolved_at IS NOT NULL 
                    THEN TIMESTAMPDIFF(MINUTE, first_occurred, resolved_at) 
                    ELSE NULL 
                END) as avg_resolution_time
            FROM log_analysis
            WHERE DATE(log_date) = ?
            GROUP BY error_level, company_cd, branch_cd
            ON DUPLICATE KEY UPDATE
                error_count = VALUES(error_count),
                unique_errors = VALUES(unique_errors),
                affected_users = VALUES(affected_users),
                resolved_count = VALUES(resolved_count),
                avg_resolution_time = VALUES(avg_resolution_time),
                updated_at = CURRENT_TIMESTAMP
        ";

        return $this->db->query($sql, [$date, $date]);
    }
}