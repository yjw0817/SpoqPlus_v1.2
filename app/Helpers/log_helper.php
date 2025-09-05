<?php

/**
 * 향상된 로그 헬퍼 함수들
 * 
 * 상용화 환경에 적합한 구조화된 로깅 시스템
 */

if (!function_exists('log_error_structured')) {
    /**
     * 구조화된 에러 로그
     */
    function log_error_structured($message, $context = [], $exception = null)
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => 'ERROR',
            'message' => $message,
            'request_id' => $_SESSION['request_id'] ?? uniqid('req_'),
            'user_id' => $_SESSION['user_id'] ?? 'anonymous',
            'user_name' => $_SESSION['user_name'] ?? 'anonymous',
            'company' => $_SESSION['comp_cd'] ?? 'unknown',
            'branch' => $_SESSION['bcoff_cd'] ?? 'unknown',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'url' => $_SERVER['REQUEST_URI'] ?? 'cli',
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'context' => $context
        ];

        if ($exception instanceof \Exception) {
            $logData['exception'] = [
                'class' => get_class($exception),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => array_slice($exception->getTrace(), 0, 5) // 상위 5개만
            ];
        }

        // JSON 형식으로 로그 저장
        $logMessage = json_encode($logData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        log_message('error', $logMessage);

        // 심각한 오류는 별도 알림
        if (in_array($logData['level'], ['CRITICAL', 'EMERGENCY'])) {
            send_error_notification($logData);
        }

        return $logData['request_id'];
    }
}

if (!function_exists('log_performance')) {
    /**
     * 성능 모니터링 로그
     */
    function log_performance($operation, $duration, $details = [])
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => 'PERFORMANCE',
            'operation' => $operation,
            'duration_ms' => round($duration * 1000, 2),
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true),
            'request_id' => $_SESSION['request_id'] ?? uniqid('req_'),
            'user_id' => $_SESSION['user_id'] ?? 'anonymous',
            'details' => $details
        ];

        // 느린 쿼리 알림 (1초 이상)
        if ($duration > 1.0) {
            $logData['alert'] = 'SLOW_OPERATION';
            send_performance_alert($logData);
        }

        $logMessage = json_encode($logData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        log_message('info', $logMessage);
    }
}

if (!function_exists('log_security_event')) {
    /**
     * 보안 이벤트 로그
     */
    function log_security_event($event_type, $details = [], $severity = 'warning')
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => 'SECURITY',
            'severity' => strtoupper($severity),
            'event_type' => $event_type,
            'user_id' => $_SESSION['user_id'] ?? 'anonymous',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'details' => $details
        ];

        // 보안 이벤트 유형별 처리
        switch ($event_type) {
            case 'LOGIN_FAILED':
                $logData['alert_after'] = 5; // 5회 실패 시 알림
                break;
            case 'UNAUTHORIZED_ACCESS':
            case 'SQL_INJECTION_ATTEMPT':
            case 'XSS_ATTEMPT':
                send_security_alert($logData);
                break;
        }

        $logMessage = json_encode($logData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        log_message($severity, $logMessage);
    }
}

if (!function_exists('log_business_event')) {
    /**
     * 비즈니스 이벤트 로그 (결제, 회원가입 등)
     */
    function log_business_event($event_type, $details = [])
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => 'BUSINESS',
            'event_type' => $event_type,
            'user_id' => $_SESSION['user_id'] ?? 'anonymous',
            'company' => $_SESSION['comp_cd'] ?? 'unknown',
            'branch' => $_SESSION['bcoff_cd'] ?? 'unknown',
            'details' => $details
        ];

        $logMessage = json_encode($logData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        log_message('info', $logMessage);
    }
}

if (!function_exists('send_error_notification')) {
    /**
     * 에러 알림 발송 (Slack, Email 등)
     */
    function send_error_notification($errorData)
    {
        // Slack 웹훅 또는 이메일 발송 로직
        // 예시: Slack 웹훅
        $webhookUrl = getenv('SLACK_ERROR_WEBHOOK');
        if ($webhookUrl) {
            $message = [
                'text' => "🚨 *심각한 오류 발생*",
                'attachments' => [[
                    'color' => 'danger',
                    'fields' => [
                        ['title' => '오류 메시지', 'value' => $errorData['message'], 'short' => false],
                        ['title' => '사용자', 'value' => $errorData['user_id'], 'short' => true],
                        ['title' => '시간', 'value' => $errorData['timestamp'], 'short' => true],
                        ['title' => 'URL', 'value' => $errorData['url'], 'short' => false],
                        ['title' => 'Request ID', 'value' => $errorData['request_id'], 'short' => true],
                    ]
                ]]
            ];

            // 비동기로 전송
            // curl_post_async($webhookUrl, json_encode($message));
        }
    }
}

if (!function_exists('send_performance_alert')) {
    /**
     * 성능 알림 발송
     */
    function send_performance_alert($perfData)
    {
        // 성능 모니터링 시스템으로 전송
        // 예: Datadog, New Relic API 호출
    }
}

if (!function_exists('send_security_alert')) {
    /**
     * 보안 알림 발송
     */
    function send_security_alert($securityData)
    {
        // 보안팀 알림
        // 높은 우선순위로 처리
    }
}