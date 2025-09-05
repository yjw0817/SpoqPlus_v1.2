<?php

namespace App\Services;

use CodeIgniter\Model;
use Exception;

/**
 * 웰컴페이먼츠 PG 결제 서비스 클래스
 * 
 * 웰컴페이먼츠 API를 이용한 결제 처리를 담당합니다.
 * - 결제 요청 (Payment Request)
 * - 결제 검증 (Payment Verification)
 * - 결제 취소 (Payment Cancellation)
 * - 통합 결제 솔루션 (카드, 계좌이체, 가상계좌, 간편결제 등)
 * - 테스트/운영 모드 지원
 * - REST API 기반 통신
 */
class WelcomePaymentService
{
    protected $db;
    protected $settings;
    protected $isTestMode;
    protected $apiUrl;
    protected $logger;

    // 웰컴페이먼츠 API 엔드포인트
    private const TEST_API_URL = 'https://testpgapi.welcomepayments.co.kr';
    private const LIVE_API_URL = 'https://pgapi.welcomepayments.co.kr';
    
    // 결제 상태 코드
    private const PAYMENT_SUCCESS = 'SUCCESS';
    private const PAYMENT_PENDING = 'PENDING';
    private const PAYMENT_FAILED = 'FAILED';
    private const PAYMENT_CANCELLED = 'CANCELLED';
    private const PAYMENT_PARTIAL_CANCELLED = 'PARTIAL_CANCELLED';

    // 지원하는 결제 방법
    private const PAYMENT_METHODS = [
        'CARD' => '신용카드',
        'BANK' => '계좌이체',
        'VBANK' => '가상계좌',
        'PHONE' => '휴대폰결제',
        'EASY_PAY' => '간편결제',
        'GIFT_CARD' => '상품권',
        'CULTURE' => '문화상품권'
    ];

    public function __construct($settings = null)
    {
        $this->db = \Config\Database::connect();
        $this->logger = service('logger');
        
        if ($settings) {
            $this->initializeWithSettings($settings);
        }
    }

    /**
     * 설정값으로 서비스 초기화
     */
    public function initializeWithSettings($settings)
    {
        $this->settings = $settings;
        $this->isTestMode = ($settings['test_mode'] ?? 'Y') === 'Y';
        $this->apiUrl = $this->isTestMode ? self::TEST_API_URL : self::LIVE_API_URL;
        
        $this->logger->info('웰컴페이먼츠 결제 서비스 초기화', [
            'mode' => $this->isTestMode ? 'TEST' : 'LIVE',
            'mid' => substr($settings['mid'] ?? 'N/A', 0, 10) . '***'
        ]);
    }

    /**
     * 지점 설정으로 서비스 초기화
     */
    public function initializeWithBranchSettings($bcoff_cd)
    {
        $settings = $this->getBranchPaymentSettings($bcoff_cd, 'welcome');
        if (!$settings) {
            throw new Exception('웰컴페이먼츠 결제 설정을 찾을 수 없습니다.');
        }
        
        $this->initializeWithSettings($settings);
        return $this;
    }

    /**
     * 지점별 결제 설정 조회
     */
    private function getBranchPaymentSettings($bcoff_cd, $provider)
    {
        $sql = "SELECT pg_settings, van_settings 
                FROM bcoff_mgmt_tbl 
                WHERE BCOFF_CD = ? AND USE_YN = 'Y'";
        
        $query = $this->db->query($sql, [$bcoff_cd]);
        $result = $query->getRowArray();
        
        if (!$result) {
            return null;
        }

        // JSON 설정 파싱
        $pgSettings = json_decode($result['pg_settings'] ?? '{}', true);
        
        return $pgSettings[$provider] ?? null;
    }

    /**
     * 결제 요청 초기화
     */
    public function initializePayment($paymentData)
    {
        try {
            $this->validatePaymentData($paymentData);
            
            // 거래 고유번호 생성
            $orderId = $this->generateOrderId($paymentData['BCOFF_CD']);
            
            // 결제 요청 데이터 구성
            $requestData = $this->buildPaymentRequest($paymentData, $orderId);
            
            // 웰컴페이먼츠 결제 준비 API 호출
            $response = $this->callWelcomeAPI('/api/v1/payment/ready', $requestData, 'POST');
            
            if ($response['resultCode'] === '0000') {
                // 결제 정보 임시 저장
                $this->saveTemporaryPayment($orderId, $paymentData, $response);
                
                $this->logger->info('웰컴페이먼츠 결제 초기화 성공', [
                    'orderId' => $orderId,
                    'amount' => $paymentData['PAYMT_AMT'],
                    'member_id' => $paymentData['MEM_ID']
                ]);
                
                return [
                    'status' => 'success',
                    'orderId' => $orderId,
                    'paymentId' => $response['paymentId'] ?? null,
                    'paymentUrl' => $response['paymentUrl'] ?? null,
                    'successUrl' => base_url('payment/welcome/return'),
                    'failUrl' => base_url('payment/welcome/fail'),
                    'cancelUrl' => base_url('payment/welcome/cancel'),
                    'amount' => $paymentData['PAYMT_AMT'],
                    'orderName' => $paymentData['SELL_EVENT_NM'],
                    'customerEmail' => $paymentData['MEM_EMAIL'] ?? null,
                    'customerName' => $paymentData['MEM_NM'],
                    'customerPhone' => $this->formatPhoneNumber($paymentData['MEM_TEL'] ?? '')
                ];
            } else {
                throw new Exception('결제 초기화 실패: ' . ($response['resultMessage'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('웰컴페이먼츠 결제 초기화 실패', [
                'error' => $e->getMessage(),
                'payment_data' => $paymentData
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * 결제 완료 검증
     */
    public function verifyPayment($verificationData)
    {
        try {
            $orderId = $verificationData['orderId'] ?? '';
            $paymentId = $verificationData['paymentId'] ?? '';
            $amount = $verificationData['amount'] ?? 0;
            
            if (empty($orderId) || empty($paymentId)) {
                throw new Exception('필수 검증 파라미터가 누락되었습니다.');
            }
            
            // 웰컴페이먼츠 결제 승인
            $approvalData = [
                'paymentId' => $paymentId,
                'orderId' => $orderId,
                'amount' => $amount
            ];
            
            $paymentResult = $this->confirmPayment($approvalData);
            
            if ($paymentResult['resultCode'] === '0000') {
                // 결제 성공 처리
                $this->processSuccessfulPayment($verificationData, $paymentResult);
                
                $this->logger->info('웰컴페이먼츠 결제 검증 성공', [
                    'orderId' => $orderId,
                    'paymentId' => $paymentId,
                    'amount' => $amount
                ]);
                
                return [
                    'status' => 'success',
                    'payment_result' => $paymentResult,
                    'paymt_mgmt_sno' => $paymentResult['paymt_mgmt_sno'] ?? null
                ];
            } else {
                throw new Exception('결제 실패: ' . ($paymentResult['resultMessage'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('웰컴페이먼츠 결제 검증 실패', [
                'error' => $e->getMessage(),
                'verification_data' => $verificationData
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * 결제 취소
     */
    public function cancelPayment($cancelData)
    {
        try {
            $this->validateCancelData($cancelData);
            
            // 기존 결제 정보 조회
            $paymentInfo = $this->getPaymentInfo($cancelData['PAYMT_MGMT_SNO']);
            if (!$paymentInfo || empty($paymentInfo['PAYMENT_ID'])) {
                throw new Exception('취소할 결제 정보를 찾을 수 없습니다.');
            }
            
            // 취소 요청 데이터 구성
            $requestData = $this->buildCancelRequest($cancelData, $paymentInfo);
            
            // 웰컴페이먼츠 취소 API 호출
            $response = $this->callWelcomeAPI('/api/v1/payment/cancel', $requestData, 'POST');
            
            if ($response['resultCode'] === '0000') {
                // 취소 완료 처리
                $this->processCancelledPayment($cancelData, $response);
                
                $this->logger->info('웰컴페이먼츠 결제 취소 성공', [
                    'paymt_mgmt_sno' => $cancelData['PAYMT_MGMT_SNO'],
                    'cancel_amount' => $cancelData['CANCEL_AMT'],
                    'paymentId' => $paymentInfo['PAYMENT_ID']
                ]);
                
                return [
                    'status' => 'success',
                    'cancel_result' => $response,
                    'refund_detail_sno' => $response['refund_detail_sno'] ?? null
                ];
            } else {
                throw new Exception('결제 취소 실패: ' . ($response['resultMessage'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('웰컴페이먼츠 결제 취소 실패', [
                'error' => $e->getMessage(),
                'cancel_data' => $cancelData
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * 웰컴페이먼츠 API 연결 테스트
     */
    public function testConnection()
    {
        try {
            // 웰컴페이먼츠 인증 토큰 요청을 통한 연결 테스트
            $authData = [
                'mid' => $this->settings['mid'],
                'merchantKey' => $this->settings['merchant_key']
            ];
            
            $response = $this->callWelcomeAPI('/api/v1/auth/token', $authData, 'POST');
            
            $isSuccess = isset($response['resultCode']) && $response['resultCode'] === '0000';
            
            if ($isSuccess) {
                $this->logger->info('웰컴페이먼츠 연결 테스트 완료', [
                    'response_time' => $response['response_time'] ?? 'N/A',
                    'test_mode' => $this->isTestMode ? 'TEST' : 'LIVE'
                ]);
                
                return [
                    'status' => 'success',
                    'response_time' => $response['response_time'] ?? '200ms',
                    'test_transaction_id' => 'WELCOME_TEST_' . time(),
                    'api_version' => $response['apiVersion'] ?? 'v1',
                    'test_mode' => $this->isTestMode,
                    'auth_token' => $response['accessToken'] ?? null,
                    'supported_methods' => array_keys(self::PAYMENT_METHODS)
                ];
            } else {
                throw new Exception('API 인증 실패: ' . ($response['resultMessage'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('웰컴페이먼츠 연결 테스트 실패', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * 주문 ID 생성
     */
    private function generateOrderId($bcoff_cd)
    {
        $prefix = $this->isTestMode ? 'TEST' : 'LIVE';
        $timestamp = date('YmdHis');
        $random = sprintf('%04d', mt_rand(0, 9999));
        
        return "{$prefix}_{$bcoff_cd}_{$timestamp}_{$random}";
    }

    /**
     * 결제 요청 데이터 구성
     */
    private function buildPaymentRequest($paymentData, $orderId)
    {
        return [
            'mid' => $this->settings['mid'],
            'orderId' => $orderId,
            'orderName' => $paymentData['SELL_EVENT_NM'],
            'amount' => (int)$paymentData['PAYMT_AMT'],
            'currency' => 'KRW',
            'customerEmail' => $paymentData['MEM_EMAIL'] ?? null,
            'customerName' => $paymentData['MEM_NM'],
            'customerPhone' => $this->formatPhoneNumber($paymentData['MEM_TEL'] ?? ''),
            'returnUrl' => base_url('payment/welcome/return'),
            'failUrl' => base_url('payment/welcome/fail'),
            'cancelUrl' => base_url('payment/welcome/cancel'),
            'payMethod' => $this->getPaymentMethod($paymentData['PAYMT_MTHD']),
            'userIp' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?? 'SpoqPlus/1.0',
            'metadata' => [
                'bcoff_cd' => $paymentData['BCOFF_CD'],
                'mem_sno' => $paymentData['MEM_SNO'],
                'sell_event_sno' => $paymentData['SELL_EVENT_SNO'] ?? '',
                'paymt_chnl' => $paymentData['PAYMT_CHNL'] ?? 'PC'
            ]
        ];
    }

    /**
     * 결제 방법 코드 변환
     */
    private function getPaymentMethod($paymt_mthd)
    {
        $methodMap = [
            'CARD' => 'CARD',
            'BANK' => 'BANK',
            'VBANK' => 'VBANK',
            'MOBILE' => 'PHONE',
            'EASY_PAY' => 'EASY_PAY'
        ];
        
        return $methodMap[$paymt_mthd] ?? 'CARD';
    }

    /**
     * 전화번호 포맷팅
     */
    private function formatPhoneNumber($phone)
    {
        // 숫자만 추출
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (empty($phone)) {
            return null;
        }
        
        if (strlen($phone) === 11 && substr($phone, 0, 3) === '010') {
            return '010-' . substr($phone, 3, 4) . '-' . substr($phone, 7, 4);
        } elseif (strlen($phone) === 10) {
            return substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4);
        }
        
        return $phone;
    }

    /**
     * 결제 승인
     */
    private function confirmPayment($confirmData)
    {
        $endpoint = '/api/v1/payment/approval';
        
        $requestData = [
            'mid' => $this->settings['mid'],
            'paymentId' => $confirmData['paymentId'],
            'orderId' => $confirmData['orderId'],
            'amount' => $confirmData['amount']
        ];
        
        return $this->callWelcomeAPI($endpoint, $requestData, 'POST');
    }

    /**
     * 결제 취소 요청 데이터 구성
     */
    private function buildCancelRequest($cancelData, $paymentInfo)
    {
        $requestData = [
            'mid' => $this->settings['mid'],
            'paymentId' => $paymentInfo['PAYMENT_ID'],
            'cancelReason' => $cancelData['CANCEL_RSON'] ?? '고객 요청에 의한 취소'
        ];
        
        // 부분 취소인 경우
        if ($cancelData['PARTIAL_YN'] === 'Y') {
            $requestData['cancelAmount'] = (int)$cancelData['CANCEL_AMT'];
        }
        
        return $requestData;
    }

    /**
     * 웰컴페이먼츠 API 호출
     */
    private function callWelcomeAPI($endpoint, $data, $method = 'POST')
    {
        $url = $this->apiUrl . $endpoint;
        
        $curl = curl_init();
        
        $headers = [
            'Content-Type: application/json',
            'User-Agent: SpoqPlus/1.0'
        ];
        
        // API 키 인증
        if (isset($this->settings['api_key'])) {
            $headers[] = 'Authorization: Bearer ' . $this->settings['api_key'];
        }
        
        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => !$this->isTestMode,
            CURLOPT_HTTPHEADER => $headers
        ];
        
        if ($method === 'POST') {
            $curlOptions[CURLOPT_POST] = true;
            if (!empty($data)) {
                $curlOptions[CURLOPT_POSTFIELDS] = json_encode($data);
            }
        } elseif ($method === 'GET' && !empty($data)) {
            $curlOptions[CURLOPT_URL] .= '?' . http_build_query($data);
        }
        
        curl_setopt_array($curl, $curlOptions);
        
        $startTime = microtime(true);
        $response = curl_exec($curl);
        $responseTime = round((microtime(true) - $startTime) * 1000);
        
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        if ($error) {
            throw new Exception('API 호출 실패: ' . $error);
        }
        
        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('API 응답 파싱 실패: ' . json_last_error_msg());
        }
        
        $result['response_time'] = $responseTime . 'ms';
        $result['http_code'] = $httpCode;
        
        // 에러 응답 처리
        if ($httpCode >= 400) {
            $result['error'] = true;
            if (!isset($result['resultCode'])) {
                $result['resultCode'] = '9999';
                $result['resultMessage'] = 'HTTP 에러 ' . $httpCode;
            }
        }
        
        return $result;
    }

    /**
     * 결제 데이터 검증
     */
    private function validatePaymentData($data)
    {
        $required = ['BCOFF_CD', 'MEM_SNO', 'MEM_ID', 'MEM_NM', 'PAYMT_AMT', 'SELL_EVENT_NM', 'PAYMT_MTHD'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("필수 항목이 누락되었습니다: {$field}");
            }
        }
        
        if ((int)$data['PAYMT_AMT'] <= 0) {
            throw new Exception('결제 금액이 올바르지 않습니다.');
        }
        
        if ((int)$data['PAYMT_AMT'] < 100) {
            throw new Exception('최소 결제 금액은 100원입니다.');
        }
    }

    /**
     * 취소 데이터 검증
     */
    private function validateCancelData($data)
    {
        $required = ['PAYMT_MGMT_SNO', 'CANCEL_AMT'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("필수 항목이 누락되었습니다: {$field}");
            }
        }
        
        if ((int)$data['CANCEL_AMT'] <= 0) {
            throw new Exception('취소 금액이 올바르지 않습니다.');
        }
    }

    /**
     * 결제 정보 조회
     */
    private function getPaymentInfo($paymt_mgmt_sno)
    {
        $sql = "SELECT * FROM paymt_mgmt_tbl WHERE PAYMT_MGMT_SNO = ?";
        $query = $this->db->query($sql, [$paymt_mgmt_sno]);
        
        return $query->getRowArray();
    }

    /**
     * 임시 결제 정보 저장
     */
    private function saveTemporaryPayment($orderId, $paymentData, $response)
    {
        $sql = "INSERT INTO temp_payment_tbl SET
                TID = ?,
                BCOFF_CD = ?,
                MEM_SNO = ?,
                PAYMT_AMT = ?,
                PAYMT_MTHD = ?,
                PAYMT_STAT = ?,
                API_RESPONSE = ?,
                CRE_DATETM = NOW()";
        
        $this->db->query($sql, [
            $orderId,
            $paymentData['BCOFF_CD'],
            $paymentData['MEM_SNO'],
            $paymentData['PAYMT_AMT'],
            $paymentData['PAYMT_MTHD'],
            self::PAYMENT_PENDING,
            json_encode($response)
        ]);
    }

    /**
     * 결제 성공 처리
     */
    private function processSuccessfulPayment($verificationData, $paymentResult)
    {
        $this->db->transStart();
        
        try {
            // 결제 관리 테이블 업데이트
            $paymt_mgmt_sno = $this->generatePaymentMgmtSno();
            
            $paymentMgmtData = [
                'PAYMT_MGMT_SNO' => $paymt_mgmt_sno,
                'TID' => $verificationData['orderId'],
                'PAYMENT_ID' => $verificationData['paymentId'] ?? '',
                'PAYMT_STAT' => self::PAYMENT_SUCCESS,
                'PAYMT_DATE' => date('Y-m-d H:i:s'),
                'APPNO' => $paymentResult['approvalNo'] ?? '',
                'PAYMT_MTHD' => $paymentResult['payMethod'] ?? '',
                'CARD_COMPANY' => $paymentResult['cardCompany'] ?? '',
                'CARD_NUMBER' => $paymentResult['cardNumber'] ?? '',
                'RECEIPT_URL' => $paymentResult['receiptUrl'] ?? '',
                'MOD_ID' => 'WELCOME_API',
                'MOD_DATETM' => date('Y-m-d H:i:s')
            ];
            
            $this->updatePaymentMgmt($paymentMgmtData);
            
            // 임시 결제 정보 삭제
            $this->deleteTemporaryPayment($verificationData['orderId']);
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                throw new Exception('결제 정보 저장 실패');
            }
            
            $paymentResult['paymt_mgmt_sno'] = $paymt_mgmt_sno;
            
        } catch (Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * 결제 취소 처리
     */
    private function processCancelledPayment($cancelData, $response)
    {
        $this->db->transStart();
        
        try {
            // 결제 관리 테이블 업데이트
            $newStatus = isset($response['cancelAmount']) && $response['cancelAmount'] < $response['totalAmount']
                ? self::PAYMENT_PARTIAL_CANCELLED
                : self::PAYMENT_CANCELLED;
                
            $this->updatePaymentStatus($cancelData['PAYMT_MGMT_SNO'], $newStatus);
            
            // 환불 상세 내역 저장
            $refund_detail_sno = $this->generateRefundDetailSno();
            
            $refundDetailData = [
                'REFUND_DETAIL_SNO' => $refund_detail_sno,
                'PAYMT_MGMT_SNO' => $cancelData['PAYMT_MGMT_SNO'],
                'REFUND_AMT' => $cancelData['CANCEL_AMT'],
                'REFUND_MTHD' => 'WELCOME_AUTO',
                'REFUND_DATE' => date('Y-m-d H:i:s'),
                'REFUND_STAT' => '00',
                'CANCEL_REASON' => $cancelData['CANCEL_RSON'] ?? '',
                'CRE_ID' => 'WELCOME_API',
                'CRE_DATETM' => date('Y-m-d H:i:s'),
                'MOD_ID' => 'WELCOME_API',
                'MOD_DATETM' => date('Y-m-d H:i:s')
            ];
            
            $this->insertRefundDetail($refundDetailData);
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                throw new Exception('취소 정보 저장 실패');
            }
            
            $response['refund_detail_sno'] = $refund_detail_sno;
            
        } catch (Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * 결제 관리 일련번호 생성
     */
    private function generatePaymentMgmtSno()
    {
        $sql = "SELECT IFNULL(MAX(CAST(SUBSTRING(PAYMT_MGMT_SNO, -10) AS UNSIGNED)), 0) + 1 as next_sno 
                FROM paymt_mgmt_tbl 
                WHERE PAYMT_MGMT_SNO LIKE CONCAT(DATE_FORMAT(NOW(), '%Y%m%d'), '%')";
        
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        
        return date('Ymd') . sprintf('%010d', $result['next_sno']);
    }

    /**
     * 환불 상세 일련번호 생성
     */
    private function generateRefundDetailSno()
    {
        $sql = "SELECT IFNULL(MAX(CAST(SUBSTRING(REFUND_DETAIL_SNO, -10) AS UNSIGNED)), 0) + 1 as next_sno 
                FROM refund_detail_tbl 
                WHERE REFUND_DETAIL_SNO LIKE CONCAT(DATE_FORMAT(NOW(), '%Y%m%d'), '%')";
        
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        
        return date('Ymd') . sprintf('%010d', $result['next_sno']);
    }

    /**
     * 결제 관리 정보 업데이트
     */
    private function updatePaymentMgmt($data)
    {
        $sql = "UPDATE paymt_mgmt_tbl SET 
                PAYMT_STAT = ?, 
                PAYMT_DATE = ?, 
                APPNO = ?, 
                PAYMT_MTHD = ?,
                CARD_COMPANY = ?,
                CARD_NUMBER = ?,
                RECEIPT_URL = ?,
                PAYMENT_ID = ?,
                MOD_ID = ?, 
                MOD_DATETM = ?
                WHERE PAYMT_MGMT_SNO = ?";
        
        $this->db->query($sql, [
            $data['PAYMT_STAT'],
            $data['PAYMT_DATE'],
            $data['APPNO'],
            $data['PAYMT_MTHD'],
            $data['CARD_COMPANY'],
            $data['CARD_NUMBER'],
            $data['RECEIPT_URL'],
            $data['PAYMENT_ID'],
            $data['MOD_ID'],
            $data['MOD_DATETM'],
            $data['PAYMT_MGMT_SNO']
        ]);
    }

    /**
     * 결제 상태 업데이트
     */
    private function updatePaymentStatus($paymt_mgmt_sno, $status)
    {
        $sql = "UPDATE paymt_mgmt_tbl SET 
                PAYMT_STAT = ?, 
                MOD_ID = 'WELCOME_API', 
                MOD_DATETM = NOW()
                WHERE PAYMT_MGMT_SNO = ?";
        
        $this->db->query($sql, [$status, $paymt_mgmt_sno]);
    }

    /**
     * 환불 상세 내역 삽입
     */
    private function insertRefundDetail($data)
    {
        $sql = "INSERT INTO refund_detail_tbl SET
                REFUND_DETAIL_SNO = ?,
                PAYMT_MGMT_SNO = ?,
                REFUND_AMT = ?,
                REFUND_MTHD = ?,
                REFUND_DATE = ?,
                REFUND_STAT = ?,
                CANCEL_REASON = ?,
                CRE_ID = ?,
                CRE_DATETM = ?,
                MOD_ID = ?,
                MOD_DATETM = ?";
        
        $this->db->query($sql, [
            $data['REFUND_DETAIL_SNO'],
            $data['PAYMT_MGMT_SNO'],
            $data['REFUND_AMT'],
            $data['REFUND_MTHD'],
            $data['REFUND_DATE'],
            $data['REFUND_STAT'],
            $data['CANCEL_REASON'],
            $data['CRE_ID'],
            $data['CRE_DATETM'],
            $data['MOD_ID'],
            $data['MOD_DATETM']
        ]);
    }

    /**
     * 임시 결제 정보 삭제
     */
    private function deleteTemporaryPayment($orderId)
    {
        $sql = "DELETE FROM temp_payment_tbl WHERE TID = ?";
        $this->db->query($sql, [$orderId]);
    }

    /**
     * 웹훅 서명 검증
     */
    public function verifyWebhookSignature($payload, $signature)
    {
        $expectedSignature = hash_hmac('sha256', $payload, $this->settings['webhook_secret'] ?? '', true);
        $expectedSignature = base64_encode($expectedSignature);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * 결제 상태 조회
     */
    public function getPaymentStatus($orderId)
    {
        $requestData = [
            'mid' => $this->settings['mid'],
            'orderId' => $orderId
        ];
        
        return $this->callWelcomeAPI('/api/v1/payment/status', $requestData, 'GET');
    }
}