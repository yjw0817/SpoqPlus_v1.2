<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * 기존 로그 파일을 파싱하여 데이터베이스에 저장하는 커맨드
 */
class ParseExistingLogs extends BaseCommand
{
    protected $group       = 'Logs';
    protected $name        = 'logs:parse';
    protected $description = '기존 로그 파일을 파싱하여 데이터베이스에 저장합니다.';
    protected $usage       = 'logs:parse [options]';
    protected $arguments   = [];
    protected $options     = [
        '--days' => '파싱할 로그 파일의 날짜 범위 (기본값: 30)',
        '--clear' => '기존 데이터를 모두 삭제하고 새로 파싱',
    ];

    public function run(array $params)
    {
        $days = $params['days'] ?? 30;
        $clear = array_key_exists('clear', $params);

        CLI::write('로그 파싱을 시작합니다...', 'green');

        // 모델 로드
        $logModel = model('LogAnalysisModel');

        // 기존 데이터 삭제 옵션
        if ($clear) {
            CLI::write('기존 데이터를 삭제합니다...', 'yellow');
            $db = \Config\Database::connect();
            $db->table('log_analysis')->truncate();
            CLI::write('기존 데이터 삭제 완료', 'green');
        }

        // 로그 디렉토리 경로
        $logPath = WRITEPATH . 'logs/';
        
        // 날짜 범위 계산
        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        $endDate = date('Y-m-d');

        CLI::write("날짜 범위: {$startDate} ~ {$endDate}", 'blue');

        // 로그 파일 목록 가져오기
        $files = glob($logPath . '*.log');
        
        if (empty($files)) {
            CLI::write('파싱할 로그 파일이 없습니다.', 'red');
            return;
        }

        $totalFiles = count($files);
        $parsedCount = 0;
        $errorCount = 0;

        // 프로그레스 바 시작
        $progress = CLI::showProgress(0, $totalFiles);

        foreach ($files as $index => $file) {
            // 파일명에서 날짜 추출 (log-2024-01-15.log 형식)
            if (preg_match('/log-(\d{4}-\d{2}-\d{2})\.log/', basename($file), $matches)) {
                $fileDate = $matches[1];
                
                // 날짜 범위 체크
                if ($fileDate >= $startDate && $fileDate <= $endDate) {
                    CLI::write("\n파일 파싱 중: " . basename($file), 'light_gray');
                    
                    // 파일 파싱
                    $result = $logModel->parseLogFile($file);
                    
                    if ($result['success']) {
                        $parsedCount++;
                        CLI::write("- 파싱 성공: {$result['entries_added']}개 항목 추가", 'green');
                    } else {
                        $errorCount++;
                        CLI::write("- 파싱 실패: {$result['error']}", 'red');
                    }
                }
            }

            // 프로그레스 바 업데이트
            CLI::showProgress($index + 1, $totalFiles);
        }

        // 통계 업데이트
        $this->updateStatistics();

        // 결과 표시
        CLI::newLine(2);
        CLI::write('로그 파싱 완료!', 'green');
        CLI::write("- 전체 파일: {$totalFiles}개", 'blue');
        CLI::write("- 파싱 성공: {$parsedCount}개", 'green');
        CLI::write("- 파싱 실패: {$errorCount}개", 'red');

        // 데이터베이스 통계
        $db = \Config\Database::connect();
        $totalErrors = $db->table('log_analysis')->countAllResults();
        CLI::write("- 데이터베이스 총 오류: {$totalErrors}개", 'blue');
    }

    /**
     * 통계 정보 업데이트
     */
    private function updateStatistics()
    {
        CLI::write("\n통계 정보를 업데이트합니다...", 'yellow');

        $db = \Config\Database::connect();

        // 날짜별 통계 생성
        $sql = "INSERT INTO log_statistics_summary (date, error_level, count, created_at)
                SELECT 
                    DATE(log_date) as date,
                    error_level,
                    COUNT(*) as count,
                    NOW() as created_at
                FROM log_analysis
                GROUP BY DATE(log_date), error_level
                ON DUPLICATE KEY UPDATE 
                    count = VALUES(count),
                    created_at = VALUES(created_at)";
        
        $db->query($sql);

        CLI::write("통계 정보 업데이트 완료", 'green');
    }
}