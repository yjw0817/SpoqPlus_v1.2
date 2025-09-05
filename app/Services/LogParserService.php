<?php

namespace App\Services;

class LogParserService
{
    /**
     * 지원하는 로그 포맷 패턴
     */
    private $patterns = [
        'codeigniter4' => [
            'pattern' => '/^(CRITICAL|ERROR|WARNING|INFO|DEBUG) - (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}) --> (.+?)(?=^(?:CRITICAL|ERROR|WARNING|INFO|DEBUG) - \d{4}|\z)/ms',
            'groups' => ['level', 'date', 'message']
        ],
        'apache_error' => [
            'pattern' => '/^\[([^\]]+)\] \[([^\]]+)\] \[pid (\d+)\] \[client ([^\]]+)\] (.+)$/m',
            'groups' => ['date', 'level', 'pid', 'client', 'message']
        ],
        'nginx_error' => [
            'pattern' => '/^(\d{4}\/\d{2}\/\d{2} \d{2}:\d{2}:\d{2}) \[([^\]]+)\] (\d+)#(\d+): (.+)$/m',
            'groups' => ['date', 'level', 'pid', 'tid', 'message']
        ],
        'php_error' => [
            'pattern' => '/^\[(\d{2}-\w{3}-\d{4} \d{2}:\d{2}:\d{2} \w+)\] (.+)$/m',
            'groups' => ['date', 'message']
        ]
    ];

    /**
     * 로그 파일을 파싱하여 구조화된 데이터로 변환
     */
    public function parseLogFile($filePath, $format = 'codeigniter4')
    {
        if (!file_exists($filePath)) {
            throw new \Exception("Log file not found: {$filePath}");
        }

        $content = file_get_contents($filePath);
        return $this->parseLogContent($content, $format);
    }

    /**
     * 로그 내용을 파싱
     */
    public function parseLogContent($content, $format = 'codeigniter4')
    {
        if (!isset($this->patterns[$format])) {
            throw new \Exception("Unsupported log format: {$format}");
        }

        $pattern = $this->patterns[$format]['pattern'];
        $groups = $this->patterns[$format]['groups'];
        $entries = [];

        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $entry = [];
            
            // 매칭된 그룹을 명명된 필드로 매핑
            foreach ($groups as $index => $fieldName) {
                $entry[$fieldName] = $match[$index + 1] ?? null;
            }

            // 추가 정보 추출
            $entry = $this->enrichLogEntry($entry, $format);
            
            $entries[] = $entry;
        }

        return $entries;
    }

    /**
     * 로그 엔트리에 추가 정보 추출 및 보강
     */
    private function enrichLogEntry($entry, $format)
    {
        // 오류 레벨 정규화
        if (isset($entry['level'])) {
            $entry['error_level'] = $this->normalizeErrorLevel($entry['level']);
        }

        // 날짜 형식 정규화
        if (isset($entry['date'])) {
            $entry['log_date'] = $this->normalizeDate($entry['date'], $format);
        }

        // 메시지에서 추가 정보 추출
        if (isset($entry['message'])) {
            $enriched = $this->extractFromMessage($entry['message']);
            $entry = array_merge($entry, $enriched);
        }

        // 오류 해시 생성
        $entry['error_hash'] = $this->generateErrorHash($entry);

        return $entry;
    }

    /**
     * 오류 레벨 정규화
     */
    private function normalizeErrorLevel($level)
    {
        $level = strtoupper(trim($level));
        
        $levelMap = [
            'EMERGENCY' => 'CRITICAL',
            'ALERT' => 'CRITICAL',
            'CRIT' => 'CRITICAL',
            'ERR' => 'ERROR',
            'WARN' => 'WARNING',
            'NOTICE' => 'INFO',
            'INFORMATIONAL' => 'INFO',
            'DBG' => 'DEBUG'
        ];

        return $levelMap[$level] ?? $level;
    }

    /**
     * 날짜 형식 정규화
     */
    private function normalizeDate($date, $format)
    {
        switch ($format) {
            case 'apache_error':
                // Apache: Wed Oct 11 14:32:52.123456 2023
                return date('Y-m-d H:i:s', strtotime($date));
                
            case 'nginx_error':
                // Nginx: 2023/10/11 14:32:52
                return str_replace('/', '-', $date);
                
            case 'php_error':
                // PHP: 11-Oct-2023 14:32:52 UTC
                return date('Y-m-d H:i:s', strtotime($date));
                
            default:
                // CodeIgniter4: 2023-10-11 14:32:52
                return $date;
        }
    }

    /**
     * 메시지에서 추가 정보 추출
     */
    private function extractFromMessage($message)
    {
        $extracted = [
            'error_message' => $message
        ];

        // 파일 경로와 라인 번호 추출
        if (preg_match('/(?:in|at)\s+(.+?)\s+(?:on\s+)?line\s+(\d+)/i', $message, $match)) {
            $extracted['file_path'] = trim($match[1]);
            $extracted['line_number'] = (int)$match[2];
        }

        // 스택 트레이스 감지 및 추출
        if (strpos($message, '#0') !== false || strpos($message, 'Stack trace:') !== false) {
            $lines = explode("\n", $message);
            $messageLines = [];
            $stackLines = [];
            $inStack = false;

            foreach ($lines as $line) {
                if (strpos($line, '#0') !== false || strpos($line, 'Stack trace:') !== false) {
                    $inStack = true;
                }
                
                if ($inStack) {
                    $stackLines[] = $line;
                } else {
                    $messageLines[] = $line;
                }
            }

            $extracted['error_message'] = trim(implode("\n", $messageLines));
            $extracted['stack_trace'] = trim(implode("\n", $stackLines));
        }

        // URL 추출
        if (preg_match('/(?:URL|URI|url):\s*([^\s]+)/i', $message, $match)) {
            $extracted['request_url'] = $match[1];
        }

        // IP 주소 추출
        if (preg_match('/\b(?:\d{1,3}\.){3}\d{1,3}\b/', $message, $match)) {
            $extracted['ip_address'] = $match[0];
        }

        // 사용자 ID 추출 (다양한 패턴)
        if (preg_match('/(?:user_id|userid|user|uid)[\s:=]+([^\s,;]+)/i', $message, $match)) {
            $extracted['user_id'] = $match[1];
        }

        // 클래스명 추출
        if (preg_match('/(?:class\s+)?([A-Z][a-zA-Z0-9_\\\\]+)(?:::|->)/', $message, $match)) {
            $extracted['class_name'] = $match[1];
        }

        // 함수/메서드명 추출
        if (preg_match('/(?:function|method)\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(/i', $message, $match)) {
            $extracted['method_name'] = $match[1];
        }

        return $extracted;
    }

    /**
     * 오류 해시 생성
     */
    private function generateErrorHash($entry)
    {
        $hashParts = [
            $entry['error_level'] ?? '',
            $this->extractMainError($entry['error_message'] ?? ''),
            $entry['file_path'] ?? '',
            $entry['line_number'] ?? ''
        ];

        return md5(implode('|', array_filter($hashParts)));
    }

    /**
     * 메인 오류 메시지 추출 (동적 부분 제거)
     */
    private function extractMainError($message)
    {
        // 숫자, 날짜, ID 등 동적 부분 제거
        $message = preg_replace('/\b\d{4}-\d{2}-\d{2}\b/', 'DATE', $message);
        $message = preg_replace('/\b\d{2}:\d{2}:\d{2}\b/', 'TIME', $message);
        $message = preg_replace('/\b[0-9a-f]{32}\b/i', 'HASH', $message);
        $message = preg_replace('/\b\d+\b/', 'NUM', $message);
        $message = preg_replace('/#\d+/', '#NUM', $message);
        
        // 파일 경로의 동적 부분 제거
        $message = preg_replace('/\/[^\/\s]+\.(php|js|css|html)/', '/FILE', $message);
        
        return trim($message);
    }

    /**
     * 실시간 로그 모니터링을 위한 새 엔트리 파싱
     */
    public function parseNewEntries($filePath, $lastPosition = 0, $format = 'codeigniter4')
    {
        if (!file_exists($filePath)) {
            return ['entries' => [], 'position' => 0];
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return ['entries' => [], 'position' => $lastPosition];
        }

        // 마지막 위치로 이동
        fseek($handle, $lastPosition);
        
        // 새로운 내용 읽기
        $newContent = '';
        while (!feof($handle)) {
            $newContent .= fread($handle, 8192);
        }
        
        $currentPosition = ftell($handle);
        fclose($handle);

        // 새 엔트리 파싱
        $entries = [];
        if (!empty($newContent)) {
            $entries = $this->parseLogContent($newContent, $format);
        }

        return [
            'entries' => $entries,
            'position' => $currentPosition
        ];
    }

    /**
     * 로그 파일 목록 조회
     */
    public function getLogFiles($directory = WRITEPATH . 'logs')
    {
        $files = [];
        
        if (is_dir($directory)) {
            $iterator = new \DirectoryIterator($directory);
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'log') {
                    $files[] = [
                        'name' => $file->getFilename(),
                        'path' => $file->getPathname(),
                        'size' => $file->getSize(),
                        'modified' => $file->getMTime(),
                        'readable' => $file->isReadable()
                    ];
                }
            }
        }

        // 수정 시간 기준 정렬 (최신 순)
        usort($files, function($a, $b) {
            return $b['modified'] - $a['modified'];
        });

        return $files;
    }

    /**
     * 로그 파일 크기 체크 및 로테이션 제안
     */
    public function checkLogRotation($filePath, $maxSize = 10485760) // 10MB
    {
        if (!file_exists($filePath)) {
            return ['needed' => false];
        }

        $size = filesize($filePath);
        
        return [
            'needed' => $size > $maxSize,
            'current_size' => $size,
            'max_size' => $maxSize,
            'percentage' => round(($size / $maxSize) * 100, 2)
        ];
    }
}