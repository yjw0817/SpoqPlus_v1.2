<?php

namespace App\Services;

use Exception;

/**
 * POS 통합 서비스
 * 
 * POS 단말기와의 통신 및 결제 처리를 담당하는 서비스 클래스
 * 다양한 POS 제조사 및 연동 방식을 지원
 */
class PosIntegrationService
{
    private $db;
    private $settings;
    private $manufacturer;
    private $connectionType;
    private $isConnected = false;
    private $lastError = '';
    
    // POS 제조사별 드라이버
    private $drivers = [
        'KICC' => 'KiccPosDriver',
        'NICE' => 'NicePosDriver',
        'KSNET' => 'KsnetPosDriver',
        'KIS' => 'KisPosDriver',
        'FDIK' => 'FdikPosDriver'
    ];
    
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    
    /**
     * POS 설정으로 초기화
     */
    public function initialize($bcoff_cd, $comp_cd)
    {
        try {
            // DB에서 POS 설정 로드 - 컬럼 존재 여부 확인
            $sql = "SHOW COLUMNS FROM bcoff_mgmt_tbl LIKE 'PAYMENT_DEFAULT_SETTINGS'";
            $columnExists = $this->db->query($sql)->getRowArray();
            
            if (!$columnExists) {
                // 컬럼이 없으면 빈 결과 반환
                $result = null;
                log_message('error', 'PAYMENT_DEFAULT_SETTINGS 컬럼이 bcoff_mgmt_tbl에 존재하지 않습니다.');
            } else {
                $sql = "SELECT PAYMENT_DEFAULT_SETTINGS 
                        FROM bcoff_mgmt_tbl 
                        WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
                
                $result = $this->db->query($sql, [
                    'comp_cd' => $comp_cd,
                    'bcoff_cd' => $bcoff_cd
                ])->getRowArray();
            }
            
            if ($result && !empty($result['PAYMENT_DEFAULT_SETTINGS'])) {
                $payment_settings = json_decode($result['PAYMENT_DEFAULT_SETTINGS'], true);
                $this->settings = $payment_settings['pos_settings'] ?? [];
                
                if (!empty($this->settings['use_pos']) && $this->settings['use_pos']) {
                    $this->manufacturer = $this->settings['pos_device']['manufacturer'] ?? '';
                    $this->connectionType = $this->settings['integration_mode'] ?? '';
                    return true;
                }
            }
            
            return false;
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            log_message('error', 'POS 초기화 실패: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * 설정 데이터로 직접 초기화 (테스트용)
     */
    public function initializeWithSettings($settings)
    {
        try {
            $this->settings = $settings;
            
            if (!empty($this->settings['use_pos']) && $this->settings['use_pos']) {
                $this->manufacturer = $this->settings['pos_device']['manufacturer'] ?? '';
                $this->connectionType = $this->settings['integration_mode'] ?? '';
                return true;
            }
            
            return false;
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            log_message('error', 'POS 설정 초기화 실패: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * POS 연결 테스트
     */
    public function testConnection()
    {
        try {
            if (empty($this->manufacturer)) {
                throw new Exception('POS 제조사가 설정되지 않았습니다.');
            }
            
            // 연동 방식에 따른 연결 테스트
            switch ($this->connectionType) {
                case 'api':
                    return $this->testApiConnection();
                case 'serial':
                    return $this->testSerialConnection();
                case 'network':
                    return $this->testNetworkConnection();
                default:
                    throw new Exception('지원하지 않는 연동 방식입니다.');
            }
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'device_info' => null
            ];
        }
    }
    
    /**
     * POS 결제 요청
     */
    public function requestPayment($paymentData)
    {
        try {
            if (!$this->isConnected) {
                $this->connect();
            }
            
            // 결제 데이터 검증
            $this->validatePaymentData($paymentData);
            
            // 제조사별 드라이버 로드
            $driverClass = $this->getDriver();
            if (!$driverClass) {
                throw new Exception('POS 드라이버를 찾을 수 없습니다.');
            }
            
            // 결제 요청 실행
            $result = $driverClass->processPayment($paymentData);
            
            // 결제 이력 저장
            $this->savePaymentHistory($paymentData, $result);
            
            return $result;
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            log_message('error', 'POS 결제 요청 실패: ' . $e->getMessage());
            
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'transaction_id' => null
            ];
        }
    }
    
    /**
     * POS 결제 취소
     */
    public function cancelPayment($transactionId, $amount = null)
    {
        try {
            if (!$this->isConnected) {
                $this->connect();
            }
            
            // 원거래 정보 조회
            $originalTransaction = $this->getTransaction($transactionId);
            if (!$originalTransaction) {
                throw new Exception('원거래를 찾을 수 없습니다.');
            }
            
            // 취소 금액 설정
            if ($amount === null) {
                $amount = $originalTransaction['amount'];
            }
            
            // 제조사별 드라이버 로드
            $driverClass = $this->getDriver();
            
            // 취소 요청 실행
            $result = $driverClass->cancelPayment($transactionId, $amount);
            
            // 취소 이력 저장
            $this->saveCancelHistory($transactionId, $amount, $result);
            
            return $result;
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            log_message('error', 'POS 결제 취소 실패: ' . $e->getMessage());
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * POS 연결
     */
    private function connect()
    {
        try {
            switch ($this->connectionType) {
                case 'api':
                    $this->connectApi();
                    break;
                case 'serial':
                    $this->connectSerial();
                    break;
                case 'network':
                    $this->connectNetwork();
                    break;
            }
            
            $this->isConnected = true;
            
        } catch (Exception $e) {
            $this->isConnected = false;
            throw $e;
        }
    }
    
    /**
     * API 연결
     */
    private function connectApi()
    {
        $endpoint = $this->settings['connection']['api_endpoint'] ?? '';
        $apiKey = $this->settings['connection']['api_key'] ?? '';
        
        if (empty($endpoint) || empty($apiKey)) {
            throw new Exception('API 연결 정보가 없습니다.');
        }
        
        // API 연결 테스트 (실제 구현은 제조사별로 다름)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint . '/health');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            throw new Exception('API 연결 실패');
        }
    }
    
    /**
     * 시리얼 연결
     */
    private function connectSerial()
    {
        $comPort = $this->settings['connection']['com_port'] ?? 'COM1';
        $baudRate = $this->settings['connection']['baud_rate'] ?? '9600';
        $dataBits = $this->settings['connection']['data_bits'] ?? '8';
        $parity = $this->settings['connection']['parity'] ?? 'N';
        
        // 시리얼 포트 연결 (실제 구현은 OS별로 다름)
        // Windows의 경우 mode 명령 사용
        if (PHP_OS_FAMILY === 'Windows') {
            $command = "mode $comPort: baud=$baudRate parity=$parity data=$dataBits";
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                throw new Exception('시리얼 포트 설정 실패');
            }
        }
    }
    
    /**
     * 네트워크 연결
     */
    private function connectNetwork()
    {
        $ipAddress = $this->settings['connection']['ip_address'] ?? '';
        $port = $this->settings['connection']['port'] ?? '9999';
        
        if (empty($ipAddress)) {
            throw new Exception('IP 주소가 설정되지 않았습니다.');
        }
        
        // TCP 소켓 연결 테스트
        $socket = @fsockopen($ipAddress, $port, $errno, $errstr, 5);
        
        if (!$socket) {
            throw new Exception("네트워크 연결 실패: $errstr ($errno)");
        }
        
        fclose($socket);
    }
    
    /**
     * API 연결 테스트
     */
    private function testApiConnection()
    {
        try {
            $this->connectApi();
            
            return [
                'status' => 'success',
                'message' => 'API 연결 성공',
                'device_info' => $this->manufacturer . ' (API 모드)'
            ];
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'device_info' => null
            ];
        }
    }
    
    /**
     * 시리얼 연결 테스트
     */
    private function testSerialConnection()
    {
        try {
            $this->connectSerial();
            
            return [
                'status' => 'success',
                'message' => '시리얼 포트 연결 성공',
                'device_info' => $this->manufacturer . ' (' . $this->settings['connection']['com_port'] . ')'
            ];
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'device_info' => null
            ];
        }
    }
    
    /**
     * 네트워크 연결 테스트
     */
    private function testNetworkConnection()
    {
        try {
            $this->connectNetwork();
            
            return [
                'status' => 'success',
                'message' => '네트워크 연결 성공',
                'device_info' => $this->manufacturer . ' (' . $this->settings['connection']['ip_address'] . ':' . $this->settings['connection']['port'] . ')'
            ];
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'device_info' => null
            ];
        }
    }
    
    /**
     * 결제 데이터 검증
     */
    private function validatePaymentData($paymentData)
    {
        $required = ['amount', 'payment_method', 'order_id'];
        
        foreach ($required as $field) {
            if (empty($paymentData[$field])) {
                throw new Exception("필수 항목이 누락되었습니다: $field");
            }
        }
        
        if ($paymentData['amount'] <= 0) {
            throw new Exception('결제 금액이 올바르지 않습니다.');
        }
    }
    
    /**
     * 제조사별 드라이버 가져오기
     */
    private function getDriver()
    {
        if (!isset($this->drivers[$this->manufacturer])) {
            return null;
        }
        
        $driverName = $this->drivers[$this->manufacturer];
        $driverClass = "\\App\\Services\\PosDrivers\\$driverName";
        
        if (!class_exists($driverClass)) {
            // 기본 드라이버 반환
            return new \App\Services\PosDrivers\BasePosDriver($this->settings);
        }
        
        return new $driverClass($this->settings);
    }
    
    /**
     * 결제 이력 저장
     */
    private function savePaymentHistory($paymentData, $result)
    {
        try {
            $historyData = [
                'COMP_CD' => $_SESSION['comp_cd'] ?? '',
                'BCOFF_CD' => $paymentData['bcoff_cd'] ?? '',
                'POS_MANUFACTURER' => $this->manufacturer,
                'TRANSACTION_ID' => $result['transaction_id'] ?? '',
                'ORDER_ID' => $paymentData['order_id'],
                'AMOUNT' => $paymentData['amount'],
                'PAYMENT_METHOD' => $paymentData['payment_method'],
                'STATUS' => $result['status'],
                'RESPONSE_MSG' => $result['message'] ?? '',
                'REQUEST_DATA' => json_encode($paymentData),
                'RESPONSE_DATA' => json_encode($result),
                'REG_DATETM' => date('Y-m-d H:i:s')
            ];
            
            $this->db->table('pos_payment_hist')->insert($historyData);
            
        } catch (Exception $e) {
            log_message('error', 'POS 결제 이력 저장 실패: ' . $e->getMessage());
        }
    }
    
    /**
     * 취소 이력 저장
     */
    private function saveCancelHistory($transactionId, $amount, $result)
    {
        try {
            $historyData = [
                'COMP_CD' => $_SESSION['comp_cd'] ?? '',
                'ORIGINAL_TRANSACTION_ID' => $transactionId,
                'CANCEL_AMOUNT' => $amount,
                'STATUS' => $result['status'],
                'RESPONSE_MSG' => $result['message'] ?? '',
                'RESPONSE_DATA' => json_encode($result),
                'REG_DATETM' => date('Y-m-d H:i:s')
            ];
            
            $this->db->table('pos_cancel_hist')->insert($historyData);
            
        } catch (Exception $e) {
            log_message('error', 'POS 취소 이력 저장 실패: ' . $e->getMessage());
        }
    }
    
    /**
     * 거래 정보 조회
     */
    private function getTransaction($transactionId)
    {
        $sql = "SELECT * FROM pos_payment_hist WHERE TRANSACTION_ID = :transaction_id:";
        $result = $this->db->query($sql, ['transaction_id' => $transactionId])->getRowArray();
        
        if ($result) {
            return [
                'transaction_id' => $result['TRANSACTION_ID'],
                'amount' => $result['AMOUNT'],
                'payment_method' => $result['PAYMENT_METHOD'],
                'status' => $result['STATUS']
            ];
        }
        
        return null;
    }
    
    /**
     * POS 사용 가능 여부 확인
     */
    public function isAvailable()
    {
        return !empty($this->settings['use_pos']) && $this->settings['use_pos'];
    }
    
    /**
     * 마지막 오류 메시지 가져오기
     */
    public function getLastError()
    {
        return $this->lastError;
    }
    
    /**
     * 현재 연결 상태
     */
    public function isConnected()
    {
        return $this->isConnected;
    }
    
    /**
     * 설정 정보 가져오기
     */
    public function getSettings()
    {
        return $this->settings;
    }
}