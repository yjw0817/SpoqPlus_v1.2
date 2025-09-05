<?php

namespace App\Services;

use CodeIgniter\Model;
use Exception;

/**
 * KICC VAN 결제 서비스 클래스
 * 
 * KICC VAN API를 이용한 터미널 기반 결제 처리를 담당합니다.
 * - 터미널 기반 결제 승인 (Terminal Payment Approval)
 * - 결제 취소 및 부분취소 (Payment Cancellation)
 * - 거래 조회 (Transaction Inquiry)
 * - 정산 보고서 (Settlement Report)
 * - 실시간 결제 처리 (Real-time Payment Processing)
 * - KICC VAN 전용 서명 검증 (KICC Signature Verification)
 */
class KiccVanService
{
    protected $db;
    protected $settings;
    protected $isTestMode;
    protected $apiUrl;
    protected $logger;
    protected $terminalId;
    protected $merchantNo;
    protected $apiKey;
    protected $encryptKey;

    // KICC VAN API 엔드포인트
    private const TEST_API_URL = 'https://testpay.kicc.co.kr';
    private const LIVE_API_URL = 'https://pay.kicc.co.kr';
    
    // VAN 거래 타입
    private const TXN_TYPE_SALE = '10';           // 승인
    private const TXN_TYPE_CANCEL = '20';         // 취소
    private const TXN_TYPE_PARTIAL_CANCEL = '21'; // 부분취소
    private const TXN_TYPE_INQUIRY = '30';        // 조회
    private const TXN_TYPE_SETTLE = '40';         // 정산
    
    // 결제 상태 코드
    private const RESULT_SUCCESS = '0000';
    private const RESULT_CANCEL_SUCCESS = '0001';
    private const RESULT_PENDING = '9999';
    private const RESULT_FAILED = 'E001';
    
    // VAN 전용 응답 코드
    private const VAN_APPROVED = 'A';
    private const VAN_DECLINED = 'D';
    private const VAN_ERROR = 'E';

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
        
        // KICC VAN 필수 설정
        $this->terminalId = $settings['terminal_id'] ?? '';
        $this->merchantNo = $settings['merchant_no'] ?? '';
        $this->apiKey = $settings['api_key'] ?? '';
        $this->encryptKey = $settings['encrypt_key'] ?? '';
        
        if (empty($this->terminalId) || empty($this->merchantNo) || empty($this->apiKey)) {
            throw new Exception('KICC VAN 필수 설정이 누락되었습니다.');
        }
        
        $this->logger->info('KICC VAN 서비스 초기화', [
            'mode' => $this->isTestMode ? 'TEST' : 'LIVE',
            'terminal_id' => $this->terminalId,
            'merchant_no' => $this->merchantNo
        ]);
    }

    /**
     * 지점 설정으로 서비스 초기화
     */
    public function initializeWithBranchSettings($bcoff_cd)
    {
        $settings = $this->getBranchVanSettings($bcoff_cd, 'kicc');
        if (!$settings) {
            throw new Exception('KICC VAN 설정을 찾을 수 없습니다.');
        }
        
        $this->initializeWithSettings($settings);
        return $this;
    }

    /**
     * 지점별 VAN 설정 조회
     */
    private function getBranchVanSettings($bcoff_cd, $provider)
    {
        $sql = "SELECT PAYMT_VAN_SET 
                FROM bcoff_mgmt_tbl 
                WHERE BCOFF_CD = ? AND USE_YN = 'Y'";
        
        $query = $this->db->query($sql, [$bcoff_cd]);
        $result = $query->getRowArray();
        
        if (!$result) {
            return null;
        }

        // JSON 설정 파싱
        $vanSettings = json_decode($result['PAYMT_VAN_SET'] ?? '{}', true);
        
        return $vanSettings[$provider] ?? null;
    }

    /**
     * 터미널 기반 결제 승인 요청
     */
    public function initializePayment($paymentData)
    {
        try {
            $transactionId = $this->generateTransactionId($paymentData['BCOFF_CD']);
            
            $requestData = [
                'txn_type' => self::TXN_TYPE_SALE,
                'terminal_id' => $this->terminalId,
                'merchant_no' => $this->merchantNo,
                'transaction_id' => $transactionId,
                'amount' => (int)$paymentData['PAYMT_AMT'],
                'product_name' => $paymentData['SELL_EVENT_NM'],
                'customer_name' => $paymentData['MEM_NM'],
                'customer_email' => $paymentData['MEM_EMAIL'] ?? '',
                'customer_phone' => $paymentData['MEM_TEL'] ?? '',
                'payment_method' => $this->convertPaymentMethod($paymentData['PAYMT_MTHD']),
                'currency' => 'KRW',
                'timestamp' => date('YmdHis'),
                'return_url' => base_url('/payment/van/kicc/return'),
                'noti_url' => base_url('/payment/van/kicc/notification'),
                'cancel_url' => base_url('/payment/van/kicc/cancel')
            ];

            // KICC VAN 전용 서명 생성
            $requestData['signature'] = $this->generateKiccSignature($requestData);
            
            // 결제 정보를 DB에 임시 저장
            $this->saveTemporaryPayment($transactionId, $paymentData, $requestData);
            
            // VAN 터미널 결제 요청
            $response = $this->sendVanPaymentRequest($requestData);
            
            if ($response['result_code'] === self::RESULT_SUCCESS) {
                return [
                    'status' => 'success',
                    'van_txn_id' => $transactionId,
                    'terminal_id' => $this->terminalId,
                    'approval_no' => $response['approval_no'] ?? '',
                    'van_response_code' => $response['van_code'] ?? '',
                    'payment_form_data' => $this->buildPaymentFormData($requestData),
                    'redirect_url' => $response['redirect_url'] ?? ''
                ];
            } else {
                throw new Exception('KICC VAN 결제 요청 실패: ' . ($response['result_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('KICC VAN 결제 초기화 실패', [
                'error' => $e->getMessage(),
                'mem_id' => $paymentData['MEM_ID'] ?? 'N/A',
                'amount' => $paymentData['PAYMT_AMT'] ?? 0
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * 결제 검증 및 승인 완료 처리
     */
    public function verifyPayment($verificationData)
    {
        try {
            $van_txn_id = $verificationData['van_txn_id'] ?? '';
            $approval_no = $verificationData['approval_no'] ?? '';
            $van_code = $verificationData['van_code'] ?? '';
            $amount = $verificationData['amount'] ?? 0;
            
            if (empty($van_txn_id) || empty($approval_no)) {
                throw new Exception('필수 검증 데이터가 누락되었습니다.');
            }
            
            // 서명 검증
            if (!$this->verifyKiccSignature($verificationData)) {
                throw new Exception('KICC VAN 서명 검증 실패');
            }
            
            // 거래 조회로 실제 승인 상태 확인
            $inquiryResult = $this->inquireTransaction($van_txn_id);
            
            if ($inquiryResult['status'] === 'success' && $inquiryResult['van_status'] === self::VAN_APPROVED) {
                // 결제 완료 정보 생성
                $paymentResult = [
                    'paymt_mgmt_sno' => $this->generatePaymentMgmtSno(),
                    'van_txn_id' => $van_txn_id,
                    'approval_no' => $approval_no,
                    'van_code' => $van_code,
                    'amount' => $amount,
                    'payment_date' => date('Y-m-d H:i:s'),
                    'terminal_id' => $this->terminalId,
                    'merchant_no' => $this->merchantNo,
                    'van_provider' => 'KICC',
                    'card_no' => $inquiryResult['card_no'] ?? '',
                    'card_name' => $inquiryResult['card_name'] ?? '',
                    'install_month' => $inquiryResult['install_month'] ?? '00'
                ];
                
                // 결제 정보를 정식 테이블에 저장
                $this->saveCompletedPayment($paymentResult);
                
                return [
                    'status' => 'success',
                    'payment_result' => $paymentResult
                ];
            } else {
                throw new Exception('KICC VAN 거래 조회 실패 또는 미승인 상태');
            }
            
        } catch (Exception $e) {
            $this->logger->error('KICC VAN 결제 검증 실패', [
                'error' => $e->getMessage(),
                'van_txn_id' => $van_txn_id ?? 'N/A'
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * 결제 취소 처리
     */
    public function cancelPayment($cancelData)
    {
        try {
            $paymt_mgmt_sno = $cancelData['PAYMT_MGMT_SNO'];
            $cancel_amount = (int)$cancelData['CANCEL_AMT'];
            $cancel_reason = $cancelData['CANCEL_RSON'] ?? '고객요청';
            $partial_yn = $cancelData['PARTIAL_YN'] ?? 'N';
            
            // 원거래 정보 조회
            $originalPayment = $this->getPaymentInfo($paymt_mgmt_sno);
            if (!$originalPayment) {
                throw new Exception('원거래 정보를 찾을 수 없습니다.');
            }
            
            $cancelTxnId = $this->generateTransactionId($originalPayment['BCOFF_CD'], 'C');
            
            $requestData = [
                'txn_type' => $partial_yn === 'Y' ? self::TXN_TYPE_PARTIAL_CANCEL : self::TXN_TYPE_CANCEL,
                'terminal_id' => $this->terminalId,
                'merchant_no' => $this->merchantNo,
                'transaction_id' => $cancelTxnId,
                'original_van_txn_id' => $originalPayment['VAN_TXN_ID'],
                'original_approval_no' => $originalPayment['APPROVAL_NO'],
                'cancel_amount' => $cancel_amount,
                'cancel_reason' => $cancel_reason,
                'timestamp' => date('YmdHis')
            ];
            
            // KICC VAN 취소 서명 생성
            $requestData['signature'] = $this->generateKiccCancelSignature($requestData);
            
            // VAN 취소 요청
            $response = $this->sendVanCancelRequest($requestData);
            
            if ($response['result_code'] === self::RESULT_CANCEL_SUCCESS) {
                $cancelResult = [
                    'cancel_txn_id' => $cancelTxnId,
                    'cancel_approval_no' => $response['cancel_approval_no'] ?? '',
                    'cancel_amount' => $cancel_amount,
                    'cancel_date' => date('Y-m-d H:i:s'),
                    'cancel_reason' => $cancel_reason,
                    'van_provider' => 'KICC'
                ];
                
                // 취소 정보 저장
                $refundDetailSno = $this->saveCancelPayment($originalPayment, $cancelResult);
                
                return [
                    'status' => 'success',
                    'cancel_result' => $cancelResult,
                    'refund_detail_sno' => $refundDetailSno
                ];
            } else {
                throw new Exception('KICC VAN 취소 요청 실패: ' . ($response['result_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('KICC VAN 결제 취소 실패', [
                'error' => $e->getMessage(),
                'paymt_mgmt_sno' => $paymt_mgmt_sno ?? 'N/A'
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * 거래 조회
     */
    public function inquireTransaction($van_txn_id)
    {
        try {
            $requestData = [
                'txn_type' => self::TXN_TYPE_INQUIRY,
                'terminal_id' => $this->terminalId,
                'merchant_no' => $this->merchantNo,
                'transaction_id' => $van_txn_id,
                'timestamp' => date('YmdHis')
            ];
            
            $requestData['signature'] = $this->generateKiccSignature($requestData);
            
            $response = $this->sendVanInquiryRequest($requestData);
            
            if ($response['result_code'] === self::RESULT_SUCCESS) {
                return [
                    'status' => 'success',
                    'van_status' => $response['van_status'] ?? self::VAN_ERROR,
                    'approval_no' => $response['approval_no'] ?? '',
                    'amount' => $response['amount'] ?? 0,
                    'card_no' => $response['card_no'] ?? '',
                    'card_name' => $response['card_name'] ?? '',
                    'install_month' => $response['install_month'] ?? '00',
                    'transaction_date' => $response['transaction_date'] ?? ''
                ];
            } else {
                throw new Exception('KICC VAN 거래조회 실패: ' . ($response['result_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('KICC VAN 거래조회 실패', [
                'error' => $e->getMessage(),
                'van_txn_id' => $van_txn_id
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * 정산 보고서 조회
     */
    public function getSettlementReport($date)
    {
        try {
            $requestData = [
                'txn_type' => self::TXN_TYPE_SETTLE,
                'terminal_id' => $this->terminalId,
                'merchant_no' => $this->merchantNo,
                'settle_date' => $date, // YYYYMMDD 형식
                'timestamp' => date('YmdHis')
            ];
            
            $requestData['signature'] = $this->generateKiccSignature($requestData);
            
            $response = $this->sendVanSettleRequest($requestData);
            
            if ($response['result_code'] === self::RESULT_SUCCESS) {
                return [
                    'status' => 'success',
                    'settle_date' => $date,
                    'total_count' => $response['total_count'] ?? 0,
                    'total_amount' => $response['total_amount'] ?? 0,
                    'sale_count' => $response['sale_count'] ?? 0,
                    'sale_amount' => $response['sale_amount'] ?? 0,
                    'cancel_count' => $response['cancel_count'] ?? 0,
                    'cancel_amount' => $response['cancel_amount'] ?? 0,
                    'settlement_list' => $response['settlement_list'] ?? []
                ];
            } else {
                throw new Exception('KICC VAN 정산조회 실패: ' . ($response['result_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('KICC VAN 정산조회 실패', [
                'error' => $e->getMessage(),
                'settle_date' => $date
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * VAN 연결 테스트
     */
    public function testConnection()
    {
        try {
            // KICC VAN 실제 API 연결 테스트
            // 터미널 상태 조회를 통한 연결 테스트
            $testData = [
                'txn_type' => 'STATUS_CHECK',  // 터미널 상태 조회
                'terminal_id' => $this->terminalId,
                'merchant_no' => $this->merchantNo,
                'transaction_id' => 'TEST_' . date('YmdHis'),
                'timestamp' => date('YmdHis'),
                'message_type' => '0200',  // 상태 조회 메시지
                'processing_code' => '930000'  // 터미널 상태 조회 코드
            ];
            
            // KICC VAN 서명 생성
            $testData['signature'] = $this->generateKiccSignature($testData);
            
            // KICC VAN API 호출
            $ch = curl_init();
            
            $url = $this->isTestMode 
                ? 'https://test-van.kicc.co.kr/api/v1/terminal/status'
                : 'https://van.kicc.co.kr/api/v1/terminal/status';
                
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'X-API-KEY: ' . ($this->apiKey ?? ''),
                'X-Terminal-ID: ' . $this->terminalId
            ]);
            
            $startTime = microtime(true);
            $response = curl_exec($ch);
            $responseTime = round((microtime(true) - $startTime) * 1000);
            
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                throw new Exception('KICC VAN 서버 연결 실패: ' . $error);
            }
            
            $responseData = json_decode($response, true);
            
            // 응답 코드 확인
            $isSuccess = ($httpCode == 200) && 
                        ($responseData['result_code'] ?? '') === '00' || 
                        ($responseData['response_code'] ?? '') === '00';
            
            if ($isSuccess) {
                $this->logger->info('KICC VAN 연결 테스트 성공', [
                    'terminal_id' => $this->terminalId,
                    'response_time' => $responseTime . 'ms',
                    'terminal_status' => $responseData['terminal_status'] ?? 'ACTIVE'
                ]);
                
                return [
                    'status' => 'success',
                    'response_time' => $responseTime . 'ms',
                    'test_transaction_id' => $testData['transaction_id'],
                    'terminal_id' => $this->terminalId,
                    'merchant_no' => $this->merchantNo,
                    'api_version' => $responseData['api_version'] ?? 'V1.0',
                    'test_mode' => $this->isTestMode,
                    'terminal_status' => $responseData['terminal_status'] ?? 'ACTIVE',
                    'van_network_status' => $responseData['network_status'] ?? 'CONNECTED'
                ];
            } else {
                $errorMessage = $responseData['result_msg'] ?? 
                               $responseData['error_message'] ?? 
                               'KICC VAN 터미널 정보가 올바르지 않습니다.';
                throw new Exception($errorMessage);
            }
            
        } catch (Exception $e) {
            $this->logger->error('KICC VAN 연결 테스트 실패', [
                'error' => $e->getMessage(),
                'terminal_id' => $this->terminalId
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => 'N/A'
            ];
        }
    }

    // =========================================================================
    // Private Helper Methods
    // =========================================================================

    /**
     * KICC VAN 전용 서명 생성
     */
    private function generateKiccSignature($data)
    {
        // KICC VAN 서명 규칙: terminal_id + merchant_no + transaction_id + amount + timestamp + api_key
        $signString = $data['terminal_id'] . $data['merchant_no'] . $data['transaction_id'] . 
                     ($data['amount'] ?? '') . $data['timestamp'] . $this->apiKey;
        
        return hash('sha256', $signString);
    }

    /**
     * KICC VAN 취소 서명 생성
     */
    private function generateKiccCancelSignature($data)
    {
        $signString = $data['terminal_id'] . $data['merchant_no'] . $data['transaction_id'] . 
                     $data['original_van_txn_id'] . $data['cancel_amount'] . $data['timestamp'] . $this->apiKey;
        
        return hash('sha256', $signString);
    }

    /**
     * KICC VAN 서명 검증
     */
    private function verifyKiccSignature($data)
    {
        $receivedSignature = $data['signature'] ?? '';
        $expectedSignature = $this->generateKiccSignature($data);
        
        return hash_equals($expectedSignature, $receivedSignature);
    }

    /**
     * VAN 결제 요청 전송
     */
    private function sendVanPaymentRequest($data)
    {
        $url = $this->apiUrl . '/van/payment';
        return $this->sendVanRequest($url, $data);
    }

    /**
     * VAN 취소 요청 전송
     */
    private function sendVanCancelRequest($data)
    {
        $url = $this->apiUrl . '/van/cancel';
        return $this->sendVanRequest($url, $data);
    }

    /**
     * VAN 조회 요청 전송
     */
    private function sendVanInquiryRequest($data)
    {
        $url = $this->apiUrl . '/van/inquiry';
        return $this->sendVanRequest($url, $data);
    }

    /**
     * VAN 정산 요청 전송
     */
    private function sendVanSettleRequest($data)
    {
        $url = $this->apiUrl . '/van/settlement';
        return $this->sendVanRequest($url, $data);
    }

    /**
     * VAN 테스트 요청 전송
     */
    private function sendVanTestRequest($data)
    {
        $url = $this->apiUrl . '/van/test';
        return $this->sendVanRequest($url, $data);
    }

    /**
     * VAN API 요청 전송 (공통)
     */
    private function sendVanRequest($url, $data)
    {
        $client = \Config\Services::curlrequest();
        
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'User-Agent' => 'SpoqPlus-KICC-VAN/1.0',
                'Terminal-ID' => $this->terminalId,
                'Merchant-No' => $this->merchantNo
            ],
            'timeout' => 30,
            'json' => $data
        ];
        
        $response = $client->post($url, $options);
        
        if ($response->getStatusCode() !== 200) {
            throw new Exception('KICC VAN API 통신 오류: ' . $response->getStatusCode());
        }
        
        $responseData = json_decode($response->getBody(), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('KICC VAN 응답 파싱 오류: ' . json_last_error_msg());
        }
        
        return $responseData;
    }

    /**
     * 결제수단 변환
     */
    private function convertPaymentMethod($paymentMethod)
    {
        $methodMap = [
            'CARD' => 'CC',      // 신용카드
            'VBANK' => 'VB',     // 가상계좌
            'BANK' => 'AC',      // 계좌이체
            'MOBILE' => 'MP'     // 모바일
        ];
        
        return $methodMap[$paymentMethod] ?? 'CC';
    }

    /**
     * 거래 ID 생성
     */
    private function generateTransactionId($bcoff_cd, $type = 'P')
    {
        // KICC VAN 거래번호 형식: KICC + 지점코드 + 타입 + YYYYMMDDHHMMSS + 랜덤4자리
        return 'KICC' . $bcoff_cd . $type . date('YmdHis') . sprintf('%04d', mt_rand(1, 9999));
    }

    /**
     * 결제관리 일련번호 생성
     */
    private function generatePaymentMgmtSno()
    {
        return 'KICC' . date('YmdHis') . sprintf('%06d', mt_rand(1, 999999));
    }

    /**
     * 결제 폼 데이터 생성
     */
    private function buildPaymentFormData($requestData)
    {
        return [
            'terminal_id' => $requestData['terminal_id'],
            'merchant_no' => $requestData['merchant_no'],
            'transaction_id' => $requestData['transaction_id'],
            'amount' => $requestData['amount'],
            'product_name' => $requestData['product_name'],
            'signature' => $requestData['signature']
        ];
    }

    /**
     * 임시 결제 정보 저장
     */
    private function saveTemporaryPayment($transactionId, $paymentData, $requestData)
    {
        $sql = "INSERT INTO tmp_paymt_tbl (
                    TXN_ID, BCOFF_CD, MEM_SNO, MEM_ID, MEM_NM, SELL_EVENT_SNO, SELL_EVENT_NM,
                    PAYMT_AMT, PAYMT_MTHD, PAYMT_CHNL, VAN_PROVIDER, TERMINAL_ID, MERCHANT_NO,
                    CRE_DATETM, MOD_DATETM
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $this->db->query($sql, [
            $transactionId,
            $paymentData['BCOFF_CD'],
            $paymentData['MEM_SNO'],
            $paymentData['MEM_ID'],
            $paymentData['MEM_NM'],
            $paymentData['SELL_EVENT_SNO'] ?? '',
            $paymentData['SELL_EVENT_NM'],
            $paymentData['PAYMT_AMT'],
            $paymentData['PAYMT_MTHD'],
            $paymentData['PAYMT_CHNL'] ?? 'VAN',
            'KICC',
            $this->terminalId,
            $this->merchantNo
        ]);
    }

    /**
     * 완료된 결제 정보 저장
     */
    private function saveCompletedPayment($paymentResult)
    {
        $sql = "INSERT INTO paymt_mgmt_tbl (
                    PAYMT_MGMT_SNO, VAN_TXN_ID, APPROVAL_NO, VAN_CODE, PAYMT_AMT, 
                    PAYMT_STAT, PAYMT_DATETM, TERMINAL_ID, MERCHANT_NO, VAN_PROVIDER,
                    CARD_NO, CARD_NAME, INSTALL_MONTH, CRE_DATETM, MOD_DATETM
                ) VALUES (?, ?, ?, ?, ?, '00', ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $this->db->query($sql, [
            $paymentResult['paymt_mgmt_sno'],
            $paymentResult['van_txn_id'],
            $paymentResult['approval_no'],
            $paymentResult['van_code'],
            $paymentResult['amount'],
            $paymentResult['payment_date'],
            $paymentResult['terminal_id'],
            $paymentResult['merchant_no'],
            $paymentResult['van_provider'],
            $paymentResult['card_no'],
            $paymentResult['card_name'],
            $paymentResult['install_month']
        ]);
    }

    /**
     * 취소 결제 정보 저장
     */
    private function saveCancelPayment($originalPayment, $cancelResult)
    {
        $refundDetailSno = 'RF' . date('YmdHis') . sprintf('%06d', mt_rand(1, 999999));
        
        $sql = "INSERT INTO refund_detail_tbl (
                    REFUND_DETAIL_SNO, PAYMT_MGMT_SNO, CANCEL_TXN_ID, CANCEL_APPROVAL_NO,
                    CANCEL_AMT, CANCEL_RSON, CANCEL_DATETM, VAN_PROVIDER, CRE_DATETM, MOD_DATETM
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $this->db->query($sql, [
            $refundDetailSno,
            $originalPayment['PAYMT_MGMT_SNO'],
            $cancelResult['cancel_txn_id'],
            $cancelResult['cancel_approval_no'],
            $cancelResult['cancel_amount'],
            $cancelResult['cancel_reason'],
            $cancelResult['cancel_date'],
            $cancelResult['van_provider']
        ]);
        
        return $refundDetailSno;
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
}