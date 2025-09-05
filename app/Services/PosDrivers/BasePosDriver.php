<?php

namespace App\Services\PosDrivers;

use Exception;

/**
 * POS 드라이버 기본 클래스
 * 
 * 모든 POS 제조사별 드라이버의 기본이 되는 클래스
 * 추상 클래스가 아닌 일반 클래스로 기본 구현 제공
 */
class BasePosDriver
{
    protected $settings;
    protected $connectionType;
    protected $lastError = '';
    
    public function __construct($settings)
    {
        $this->settings = $settings;
        $this->connectionType = $settings['integration_mode'] ?? '';
    }
    
    /**
     * 결제 처리 (각 제조사별로 구현 필요)
     */
    public function processPayment($paymentData)
    {
        // 기본 구현 - 시뮬레이션
        $transactionId = 'POS_' . date('YmdHis') . '_' . rand(1000, 9999);
        
        return [
            'status' => 'success',
            'message' => '결제가 승인되었습니다.',
            'transaction_id' => $transactionId,
            'approval_no' => 'TEST' . rand(100000, 999999),
            'card_no' => '****-****-****-' . rand(1000, 9999),
            'card_name' => '테스트카드',
            'transaction_date' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * 결제 취소 (각 제조사별로 구현 필요)
     */
    public function cancelPayment($transactionId, $amount)
    {
        // 기본 구현 - 시뮬레이션
        return [
            'status' => 'success',
            'message' => '결제가 취소되었습니다.',
            'cancel_transaction_id' => 'CANCEL_' . $transactionId,
            'cancel_date' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * 연결 상태 확인
     */
    public function checkConnection()
    {
        // 기본 구현 - 항상 성공
        return true;
    }
    
    /**
     * 공통 결제 요청 포맷
     */
    protected function formatPaymentRequest($paymentData)
    {
        return [
            'amount' => $paymentData['amount'],
            'payment_method' => $this->convertPaymentMethod($paymentData['payment_method']),
            'order_id' => $paymentData['order_id'],
            'installment' => $paymentData['installment'] ?? 0,
            'tax_free' => $paymentData['tax_free'] ?? 0,
            'vat' => $paymentData['vat'] ?? null,
            'merchant_data' => $paymentData['merchant_data'] ?? ''
        ];
    }
    
    /**
     * 결제 수단 변환
     */
    protected function convertPaymentMethod($method)
    {
        $methodMap = [
            'CARD' => '01',
            'CASH' => '02',
            'POINT' => '03',
            'GIFT' => '04'
        ];
        
        return $methodMap[$method] ?? '01';
    }
    
    /**
     * 응답 코드 변환
     */
    protected function convertResponseCode($code)
    {
        $codeMap = [
            '0000' => 'success',
            '0001' => 'declined',
            '0002' => 'error',
            '0003' => 'timeout',
            '0004' => 'cancelled'
        ];
        
        return $codeMap[$code] ?? 'error';
    }
    
    /**
     * 오류 메시지 가져오기
     */
    public function getLastError()
    {
        return $this->lastError;
    }
    
    /**
     * API 요청 전송
     */
    protected function sendApiRequest($endpoint, $data, $method = 'POST')
    {
        $url = $this->settings['connection']['api_endpoint'] . $endpoint;
        $apiKey = $this->settings['connection']['api_key'] ?? '';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ]);
        
        if ($method === 'POST' || $method === 'PUT') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception('API 요청 실패: ' . $error);
        }
        
        if ($httpCode >= 400) {
            throw new Exception('API 오류: HTTP ' . $httpCode);
        }
        
        return json_decode($response, true);
    }
    
    /**
     * 시리얼 통신 전송
     */
    protected function sendSerialCommand($command)
    {
        $comPort = $this->settings['connection']['com_port'] ?? 'COM1';
        
        // Windows 시리얼 통신
        if (PHP_OS_FAMILY === 'Windows') {
            $fp = fopen($comPort, 'r+b');
            if (!$fp) {
                throw new Exception('시리얼 포트 열기 실패');
            }
            
            // 명령 전송
            fwrite($fp, $command);
            
            // 응답 대기
            $response = '';
            $timeout = time() + 30; // 30초 타임아웃
            
            while (time() < $timeout) {
                $char = fread($fp, 1);
                if ($char !== false) {
                    $response .= $char;
                    
                    // 응답 종료 문자 확인 (제조사별로 다름)
                    if (strpos($response, "\x03") !== false) {
                        break;
                    }
                }
                
                usleep(10000); // 10ms 대기
            }
            
            fclose($fp);
            
            return $response;
        }
        
        throw new Exception('지원하지 않는 운영체제입니다.');
    }
    
    /**
     * 네트워크 통신 전송
     */
    protected function sendNetworkCommand($command)
    {
        $ipAddress = $this->settings['connection']['ip_address'] ?? '';
        $port = $this->settings['connection']['port'] ?? '9999';
        
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$socket) {
            throw new Exception('소켓 생성 실패');
        }
        
        // 연결
        if (!socket_connect($socket, $ipAddress, $port)) {
            socket_close($socket);
            throw new Exception('네트워크 연결 실패');
        }
        
        // 명령 전송
        socket_write($socket, $command, strlen($command));
        
        // 응답 수신
        $response = '';
        $timeout = time() + 30; // 30초 타임아웃
        
        while (time() < $timeout) {
            $data = socket_read($socket, 1024);
            if ($data === false) {
                break;
            }
            
            $response .= $data;
            
            // 응답 종료 확인
            if (strpos($response, "\x03") !== false) {
                break;
            }
        }
        
        socket_close($socket);
        
        return $response;
    }
    
    /**
     * 로그 기록
     */
    protected function log($message, $level = 'info')
    {
        log_message($level, '[POS Driver] ' . get_class($this) . ': ' . $message);
    }
}