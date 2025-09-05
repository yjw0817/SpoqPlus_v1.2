<?php

namespace App\Services;

use CodeIgniter\Model;
use Exception;

/**
 * 토스페이먼츠 PG 결제 서비스 클래스
 * 
 * 토스페이먼츠 API를 이용한 결제 처리를 담당합니다.
 * - 결제 요청 (Payment Request)
 * - 결제 검증 (Payment Verification)
 * - 결제 취소 (Payment Cancellation)
 * - 현대적 결제 방식 지원 (카드, 계좌이체, 가상계좌, 간편결제 등)
 * - 테스트/운영 모드 지원
 * - Payment Widget 및 Checkout 지원
 */
class TossPaymentService
{
    protected $db;
    protected $settings;
    protected $isTestMode;
    protected $apiUrl;
    protected $logger;

    // 토스페이먼츠 API 엔드포인트
    private const TEST_API_URL = 'https://api.tosspayments.com';
    private const LIVE_API_URL = 'https://api.tosspayments.com';
    
    // 토스페이먼츠 위젯 및 체크아웃 URL
    private const TEST_WIDGET_URL = 'https://js.tosspayments.com/v1/payment-widget';
    private const LIVE_WIDGET_URL = 'https://js.tosspayments.com/v1/payment-widget';
    
    // 결제 상태 코드
    private const PAYMENT_SUCCESS = 'DONE';
    private const PAYMENT_PENDING = 'WAITING_FOR_DEPOSIT';
    private const PAYMENT_FAILED = 'FAILED';
    private const PAYMENT_CANCELLED = 'CANCELED';
    private const PAYMENT_PARTIAL_CANCELLED = 'PARTIAL_CANCELED';

    // 지원하는 결제 방법
    private const PAYMENT_METHODS = [
        'CARD' => '카드',
        'TRANSFER' => '계좌이체',
        'VIRTUAL_ACCOUNT' => '가상계좌',
        'MOBILE_PHONE' => '휴대폰결제',
        'GIFT_CERTIFICATE' => '상품권결제',
        'EASY_PAY' => '간편결제',
        'FOREIGN_CARD' => '해외카드'
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
        
        $this->logger->info('토스페이먼츠 결제 서비스 초기화', [
            'mode' => $this->isTestMode ? 'TEST' : 'LIVE',
            'client_key' => substr($settings['client_key'] ?? 'N/A', 0, 10) . '***'
        ]);
    }

    /**
     * 지점 설정으로 서비스 초기화
     */
    public function initializeWithBranchSettings($bcoff_cd)
    {
        $settings = $this->getBranchPaymentSettings($bcoff_cd, 'toss');
        if (!$settings) {
            throw new Exception('토스페이먼츠 결제 설정을 찾을 수 없습니다.');
        }
        
        $this->initializeWithSettings($settings);
        return $this;
    }

    /**
     * 지점별 결제 설정 조회
     */
    private function getBranchPaymentSettings($bcoff_cd, $provider)
    {
        $sql = "SELECT PAYMT_PG_SET, PAYMT_VAN_SET 
                FROM bcoff_mgmt_tbl 
                WHERE BCOFF_CD = ? AND USE_YN = 'Y'";
        
        $query = $this->db->query($sql, [$bcoff_cd]);
        $result = $query->getRowArray();
        
        if (!$result) {
            return null;
        }

        // JSON 설정 파싱
        $pgSettings = json_decode($result['PAYMT_PG_SET'] ?? '{}', true);
        $vanSettings = json_decode($result['PAYMT_VAN_SET'] ?? '{}', true);
        
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
            
            // 토스페이먼츠 결제 준비
            $response = $this->preparePayment($requestData);
            
            if ($response['status'] === 'success') {
                // 결제 정보 임시 저장
                $this->saveTemporaryPayment($orderId, $paymentData, $response);
                
                $this->logger->info('토스페이먼츠 결제 초기화 성공', [
                    'orderId' => $orderId,
                    'amount' => $paymentData['PAYMT_AMT'],
                    'member_id' => $paymentData['MEM_ID']
                ]);
                
                return [
                    'status' => 'success',
                    'orderId' => $orderId,
                    'paymentKey' => $response['paymentKey'] ?? null,
                    'clientKey' => $this->settings['client_key'],
                    'customerKey' => $this->generateCustomerKey($paymentData['MEM_ID']),
                    'successUrl' => base_url('payment/toss/return'),
                    'failUrl' => base_url('payment/toss/fail'),
                    'amount' => $paymentData['PAYMT_AMT'],
                    'orderName' => $paymentData['SELL_EVENT_NM'],
                    'customerEmail' => $paymentData['MEM_EMAIL'] ?? null,
                    'customerName' => $paymentData['MEM_NM'],
                    'customerMobilePhone' => $this->formatPhoneNumber($paymentData['MEM_TEL'] ?? ''),
                    'paymentMethods' => $this->getAvailablePaymentMethods($paymentData['PAYMT_MTHD']),
                    'widgetUrl' => $this->isTestMode ? self::TEST_WIDGET_URL : self::LIVE_WIDGET_URL
                ];
            } else {
                throw new Exception('결제 초기화 실패: ' . ($response['message'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('토스페이먼츠 결제 초기화 실패', [
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
            $paymentKey = $verificationData['paymentKey'] ?? '';
            $amount = $verificationData['amount'] ?? 0;
            
            if (empty($orderId) || empty($paymentKey) || empty($amount)) {
                throw new Exception('필수 검증 파라미터가 누락되었습니다.');
            }
            
            // 토스페이먼츠 결제 승인
            $confirmData = [
                'paymentKey' => $paymentKey,
                'orderId' => $orderId,
                'amount' => $amount
            ];
            
            $paymentResult = $this->confirmPayment($confirmData);
            
            if ($paymentResult['status'] === self::PAYMENT_SUCCESS) {
                // 결제 성공 처리
                $this->processSuccessfulPayment($verificationData, $paymentResult);
                
                $this->logger->info('토스페이먼츠 결제 검증 성공', [
                    'orderId' => $orderId,
                    'paymentKey' => $paymentKey,
                    'amount' => $amount
                ]);
                
                return [
                    'status' => 'success',
                    'payment_result' => $paymentResult,
                    'paymt_mgmt_sno' => $paymentResult['paymt_mgmt_sno'] ?? null
                ];
            } else {
                throw new Exception('결제 실패: ' . ($paymentResult['failure']['message'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('토스페이먼츠 결제 검증 실패', [
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
            if (!$paymentInfo || empty($paymentInfo['PAYMENT_KEY'])) {
                throw new Exception('취소할 결제 정보를 찾을 수 없습니다.');
            }
            
            // 취소 요청 데이터 구성
            $requestData = $this->buildCancelRequest($cancelData, $paymentInfo);
            
            // 토스페이먼츠 취소 API 호출
            $response = $this->callTossAPI(
                '/v1/payments/' . $paymentInfo['PAYMENT_KEY'] . '/cancel',
                $requestData,
                'POST'
            );
            
            if ($response['status'] === self::PAYMENT_CANCELLED || $response['status'] === self::PAYMENT_PARTIAL_CANCELLED) {
                // 취소 완료 처리
                $this->processCancelledPayment($cancelData, $response);
                
                $this->logger->info('토스페이먼츠 결제 취소 성공', [
                    'paymt_mgmt_sno' => $cancelData['PAYMT_MGMT_SNO'],
                    'cancel_amount' => $cancelData['CANCEL_AMT'],
                    'paymentKey' => $paymentInfo['PAYMENT_KEY']
                ]);
                
                return [
                    'status' => 'success',
                    'cancel_result' => $response,
                    'refund_detail_sno' => $response['refund_detail_sno'] ?? null
                ];
            } else {
                throw new Exception('결제 취소 실패: ' . ($response['failure']['message'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('토스페이먼츠 결제 취소 실패', [
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
     * 토스페이먼츠 API 연결 테스트
     */
    public function testConnection()
    {
        try {
            // 토스페이먼츠는 실제 API 키 검증을 통한 연결 테스트를 진행
            $testPaymentKey = 'test_payment_key_' . time();
            
            // API 인증 테스트
            $response = $this->callTossAPI('/v1/payments/' . $testPaymentKey, [], 'GET', false);
            
            // 404는 정상 (결제키가 존재하지 않음을 의미하므로 API 연결은 성공)
            // 401은 인증 실패를 의미
            $isSuccess = !isset($response['error']) || 
                        (isset($response['error']['code']) && $response['error']['code'] === 'NOT_FOUND_PAYMENT');
            
            if ($isSuccess) {
                $this->logger->info('토스페이먼츠 연결 테스트 완료', [
                    'response_time' => $response['response_time'] ?? 'N/A',
                    'test_mode' => $this->isTestMode ? 'TEST' : 'LIVE'
                ]);
                
                return [
                    'status' => 'success',
                    'response_time' => $response['response_time'] ?? '200ms',
                    'test_transaction_id' => 'TOSS_TEST_' . time(),
                    'api_version' => 'v1',
                    'test_mode' => $this->isTestMode,
                    'widget_available' => true,
                    'checkout_available' => true,
                    'supported_methods' => array_keys(self::PAYMENT_METHODS)
                ];
            } else {
                throw new Exception('API 인증 실패: ' . ($response['error']['message'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('토스페이먼츠 연결 테스트 실패', [
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
     * 고객 키 생성
     */
    private function generateCustomerKey($mem_id)
    {
        return 'CUSTOMER_' . md5($mem_id . '_' . $this->settings['client_key']);
    }

    /**
     * 결제 요청 데이터 구성
     */
    private function buildPaymentRequest($paymentData, $orderId)
    {
        return [
            'orderId' => $orderId,
            'orderName' => $paymentData['SELL_EVENT_NM'],
            'amount' => (int)$paymentData['PAYMT_AMT'],
            'currency' => 'KRW',
            'customerKey' => $this->generateCustomerKey($paymentData['MEM_ID']),
            'customerEmail' => $paymentData['MEM_EMAIL'] ?? null,
            'customerName' => $paymentData['MEM_NM'],
            'customerMobilePhone' => $this->formatPhoneNumber($paymentData['MEM_TEL'] ?? ''),
            'successUrl' => base_url('payment/toss/return'),
            'failUrl' => base_url('payment/toss/fail'),
            'paymentMethods' => $this->getAvailablePaymentMethods($paymentData['PAYMT_MTHD']),
            'metadata' => [
                'bcoff_cd' => $paymentData['BCOFF_CD'],
                'mem_sno' => $paymentData['MEM_SNO'],
                'sell_event_sno' => $paymentData['SELL_EVENT_SNO'] ?? '',
                'paymt_chnl' => $paymentData['PAYMT_CHNL'] ?? 'PC'
            ]
        ];
    }

    /**
     * 사용 가능한 결제 방법 반환
     */
    private function getAvailablePaymentMethods($paymt_mthd = null)
    {
        if ($paymt_mthd) {
            $methodMap = [
                'CARD' => ['CARD'],
                'BANK' => ['TRANSFER'],
                'VBANK' => ['VIRTUAL_ACCOUNT'],
                'MOBILE' => ['MOBILE_PHONE'],
                'EASY_PAY' => ['EASY_PAY']
            ];
            
            return $methodMap[$paymt_mthd] ?? ['CARD'];
        }
        
        // 기본적으로 모든 결제 방법 허용
        return array_keys(self::PAYMENT_METHODS);
    }

    /**
     * 전화번호 포맷팅 (토스페이먼츠 형식)
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
            return substr($phone, 0, 2) . '-' . substr($phone, 2, 4) . '-' . substr($phone, 6, 4);
        }
        
        return $phone;
    }

    /**
     * 결제 준비 (Payment Widget용)
     */
    private function preparePayment($requestData)
    {
        // 토스페이먼츠는 클라이언트 사이드에서 Payment Widget을 통해 결제 준비
        // 서버 사이드에서는 결제 정보만 준비하고 실제 결제는 클라이언트에서 진행
        return [
            'status' => 'success',
            'orderId' => $requestData['orderId'],
            'amount' => $requestData['amount'],
            'orderName' => $requestData['orderName'],
            'customerKey' => $requestData['customerKey']
        ];
    }

    /**
     * 결제 승인
     */
    private function confirmPayment($confirmData)
    {
        $endpoint = '/v1/payments/confirm';
        
        return $this->callTossAPI($endpoint, $confirmData, 'POST');
    }

    /**
     * 결제 취소 요청 데이터 구성
     */
    private function buildCancelRequest($cancelData, $paymentInfo)
    {
        $requestData = [
            'cancelReason' => $cancelData['CANCEL_RSON'] ?? '고객 요청에 의한 취소'
        ];
        
        // 부분 취소인 경우
        if ($cancelData['PARTIAL_YN'] === 'Y') {
            $requestData['cancelAmount'] = (int)$cancelData['CANCEL_AMT'];
        }
        
        return $requestData;
    }

    /**
     * 토스페이먼츠 API 호출
     */
    private function callTossAPI($endpoint, $data, $method = 'POST', $requireAuth = true)
    {
        $url = $this->apiUrl . $endpoint;
        
        $curl = curl_init();
        
        $headers = [
            'Content-Type: application/json',
            'User-Agent: SpoqPlus/1.0'
        ];
        
        if ($requireAuth) {
            $auth = base64_encode($this->settings['secret_key'] . ':');
            $headers[] = 'Authorization: Basic ' . $auth;
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
            $result['error'] = $result;
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
                'PAYMENT_KEY' => $verificationData['paymentKey'] ?? '',
                'PAYMT_STAT' => self::PAYMENT_SUCCESS,
                'PAYMT_DATE' => date('Y-m-d H:i:s'),
                'APPNO' => $paymentResult['approvedAt'] ?? '',
                'PAYMT_MTHD' => $paymentResult['method'] ?? '',
                'CARD_COMPANY' => $paymentResult['card']['company'] ?? '',
                'CARD_NUMBER' => $paymentResult['card']['number'] ?? '',
                'RECEIPT_URL' => $paymentResult['receipt']['url'] ?? '',
                'MOD_ID' => 'TOSS_API',
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
                'REFUND_MTHD' => 'TOSS_AUTO',
                'REFUND_DATE' => date('Y-m-d H:i:s'),
                'REFUND_STAT' => '00',
                'CANCEL_REASON' => $cancelData['CANCEL_RSON'] ?? '',
                'CRE_ID' => 'TOSS_API',
                'CRE_DATETM' => date('Y-m-d H:i:s'),
                'MOD_ID' => 'TOSS_API',
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
                PAYMENT_KEY = ?,
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
            $data['PAYMENT_KEY'],
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
                MOD_ID = 'TOSS_API', 
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
        $expectedSignature = base64_encode(hash_hmac('sha256', $payload, $this->settings['secret_key'], true));
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * 결제 위젯 설정 반환
     */
    public function getWidgetConfig($orderId, $amount, $orderName)
    {
        return [
            'clientKey' => $this->settings['client_key'],
            'customerKey' => $this->generateCustomerKey(session()->get('MEM_ID') ?? 'GUEST'),
            'amount' => $amount,
            'orderId' => $orderId,
            'orderName' => $orderName,
            'successUrl' => base_url('payment/toss/return'),
            'failUrl' => base_url('payment/toss/fail'),
            'widgetUrl' => $this->isTestMode ? self::TEST_WIDGET_URL : self::LIVE_WIDGET_URL
        ];
    }
}