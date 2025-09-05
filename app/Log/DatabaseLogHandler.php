<?php

namespace App\Log;

use CodeIgniter\Log\Handlers\HandlerInterface;
use Config\Database;

/**
 * 실시간으로 로그를 데이터베이스에 저장하는 핸들러
 */
class DatabaseLogHandler implements HandlerInterface
{
    protected $db;
    protected $handles;

    public function __construct(array $config)
    {
        $this->handles = $config['handles'] ?? [];
        $this->db = Database::connect();
    }

    /**
     * 로그를 처리할지 결정
     */
    public function canHandle(string $level): bool
    {
        return in_array($level, $this->handles, true);
    }

    /**
     * 로그를 데이터베이스에 저장
     */
    public function handle($level, $message): bool
    {
        // 테이블이 존재하는지 확인
        if (!$this->db->tableExists('log_analysis')) {
            return false;
        }

        // 로그 메시지 파싱
        $logData = $this->parseLogMessage($level, $message);
        
        // 데이터베이스에 저장
        try {
            $this->db->table('log_analysis')->insert($logData);
            
            // 동일한 오류가 이미 있는지 확인하고 카운트 증가
            $existing = $this->db->table('log_analysis')
                ->where('error_message', $logData['error_message'])
                ->where('file_path', $logData['file_path'])
                ->where('line_number', $logData['line_number'])
                ->where('DATE(log_date)', date('Y-m-d'))
                ->get()
                ->getRow();
                
            if ($existing) {
                $this->db->table('log_analysis')
                    ->where('id', $existing->id)
                    ->update([
                        'occurrence_count' => $existing->occurrence_count + 1,
                        'last_occurred' => date('Y-m-d H:i:s')
                    ]);
            }
            
            return true;
        } catch (\Exception $e) {
            // 로그 저장 실패 시 파일로 폴백
            return false;
        }
    }

    /**
     * 로그 메시지 파싱
     */
    protected function parseLogMessage($level, $message): array
    {
        $data = [
            'log_date' => date('Y-m-d H:i:s'),
            'error_level' => strtoupper($level),
            'error_message' => $message,
            'file_path' => null,
            'line_number' => null,
            'user_id' => session()->get('user_id'),
            'user_name' => session()->get('user_name'),
            'ip_address' => service('request')->getIPAddress(),
            'request_url' => current_url(),
            'stack_trace' => null,
            'is_resolved' => 0,
            'occurrence_count' => 1,
            'first_occurred' => date('Y-m-d H:i:s'),
            'last_occurred' => date('Y-m-d H:i:s')
        ];

        // 스택 트레이스에서 파일 정보 추출
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        foreach ($backtrace as $trace) {
            if (isset($trace['file']) && strpos($trace['file'], 'app') !== false) {
                $data['file_path'] = $trace['file'];
                $data['line_number'] = $trace['line'] ?? null;
                break;
            }
        }

        // 전체 스택 트레이스 저장
        ob_start();
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $data['stack_trace'] = ob_get_clean();

        return $data;
    }

    /**
     * 배치 모드는 지원하지 않음
     */
    public function setDateFormat(string $format): HandlerInterface
    {
        return $this;
    }
}