<?php

namespace App\Services;

use CodeIgniter\Model;
use Exception;

/**
 * KSNET VAN 결제 서비스 클래스
 * 
 * KSNET VAN API를 이용한 터미널 기반 결제 처리를 담당합니다.
 * - 실시간 결제 승인/취소 (Real-time Payment Approval/Cancellation)
 * - 터미널 ID 기반 거래 관리 (Terminal ID Based Transaction Management)
 * - 매출전표 및 취소전표 발행 (Sales/Cancel Receipt Issuance)
 * - 일괄 정산 및 거래 내역 조회 (Batch Settlement & Transaction History)
 * - KSNET 전용 암호화 및 전문 통신 (KSNET Encryption & Protocol Communication)
 * - 오프라인 승인 후 온라인 매출 (Offline Auth + Online Sale)
 */
class KsnetVanService
{
    protected $db;
    protected $settings;
    protected $isTestMode;
    protected $apiUrl;
    protected $logger;
    protected $terminalId;
    protected $storeId;
    protected $apiKey;
    protected $encryptKey;
    protected $vanId;

    // KSNET VAN API 엔드포인트
    private const TEST_API_URL = 'https://testpgapi.ksnet.to';
    private const LIVE_API_URL = 'https://pgapi.ksnet.to';
    
    // VAN 거래 타입
    private const TXN_TYPE_AUTH = '1001';         // 승인
    private const TXN_TYPE_SALE = '1002';         // 매출
    private const TXN_TYPE_CANCEL = '1003';       // 취소
    private const TXN_TYPE_AUTH_CANCEL = '1004';  // 승인취소
    private const TXN_TYPE_INQUIRY = '2001';      // 조회
    private const TXN_TYPE_SETTLE = '3001';       // 정산
    
    // 결과 코드
    private const RESULT_SUCCESS = '0000';
    private const RESULT_APPROVAL = '0000';
    private const RESULT_CANCEL_SUCCESS = '0000';
    private const RESULT_PENDING = '9999';
    private const RESULT_FAILED = 'E001';
    
    // 메시지 타입
    private const MSG_TYPE_REQUEST = '0200';
    private const MSG_TYPE_RESPONSE = '0210';
    private const MSG_TYPE_REVERSAL = '0400';
    
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
        
        $this->terminalId = $settings['terminal_id'] ?? '';
        $this->storeId = $settings['store_id'] ?? '';
        $this->vanId = $settings['van_id'] ?? 'KSNET';
        $this->apiKey = $settings['api_key'] ?? '';
        $this->encryptKey = $settings['encrypt_key'] ?? $settings['api_key'] ?? '';
        
        $this->logger->info('KSNET VAN 서비스 초기화', [
            'mode' => $this->isTestMode ? 'TEST' : 'LIVE',
            'terminal_id' => $this->terminalId,
            'store_id' => $this->storeId
        ]);
    }

    /**
     * 지점 설정으로 서비스 초기화
     */
    public function initializeWithBranchSettings($bcoff_cd)
    {
        $settings = $this->getBranchVanSettings($bcoff_cd, 'ksnet');
        if (!$settings) {
            throw new Exception('KSNET VAN 설정을 찾을 수 없습니다.');
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

        $vanSettings = json_decode($result['PAYMT_VAN_SET'] ?? '{}', true);
        
        return $vanSettings[$provider] ?? null;
    }

    /**
     * 결제 승인 요청
     */
    public function approvePayment($paymentData)
    {
        try {
            $orderId = $this->generateOrderId($paymentData['BCOFF_CD']);
            $amount = (int)$paymentData['PAYMT_AMT'];
            $installment = (int)($paymentData['INSTALL_MONTH'] ?? 0);
            
            $encryptedData = $this->encryptKsnetData([
                'card_no' => $paymentData['CARD_NO'],
                'expiry_date' => $paymentData['EXPIRY_DATE'] ?? '',
                'card_pwd' => $paymentData['CARD_PWD'] ?? '',
                'birth_date' => $paymentData['BIRTH_DATE'] ?? ''
            ]);
            
            $requestData = [
                'van_txn_type' => self::TXN_TYPE_AUTH,
                'terminal_id' => $this->terminalId,
                'store_id' => $this->storeId,
                'van_id' => $this->vanId,
                'transaction_id' => $orderId,
                'amount' => $amount,
                'installment' => $installment,
                'encrypted_data' => $encryptedData,
                'order_name' => $paymentData['SELL_EVENT_NM'] ?? '상품구매',
                'buyer_name' => $paymentData['MEM_NM'] ?? '',
                'buyer_tel' => $paymentData['MEM_TEL'] ?? '',
                'txn_date' => date('Ymd'),
                'txn_time' => date('His'),
                'message_type' => self::MSG_TYPE_REQUEST,
                'processing_code' => '000000' // 승인 처리코드
            ];
            
            // KSNET VAN 데이터 해시 생성
            $requestData['hash_data'] = $this->generateKsnetHash($requestData);
            
            // VAN 승인 요청
            $response = $this->sendVanAuthRequest($requestData);
            
            if ($response['resp_code'] === self::RESULT_APPROVAL) {
                $approvalResult = [
                    'van_txn_id' => $response['van_txn_id'] ?? $orderId,
                    'approval_no' => $response['approval_no'],
                    'approval_date' => $response['approval_date'] ?? date('Y-m-d'),
                    'approval_time' => $response['approval_time'] ?? date('H:i:s'),
                    'card_company' => $response['card_company'] ?? '',
                    'card_no_masked' => $response['card_no_masked'] ?? '',
                    'merchant_no' => $response['merchant_no'] ?? '',
                    'terminal_no' => $this->terminalId,
                    'van_provider' => 'KSNET',
                    'installment' => $installment,
                    'amount' => $amount
                ];
                
                // 승인 정보 저장
                $paymt_mgmt_sno = $this->savePaymentApproval($paymentData, $approvalResult);
                
                return [
                    'status' => 'success',
                    'approval_result' => $approvalResult,
                    'paymt_mgmt_sno' => $paymt_mgmt_sno
                ];
            } else {
                throw new Exception('KSNET VAN 승인 요청 실패: ' . ($response['resp_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('KSNET VAN 결제 승인 실패', [
                'error' => $e->getMessage(),
                'order_id' => $orderId ?? 'N/A'
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * 결제 검증 (조회)
     */
    public function verifyPayment($van_txn_id)
    {
        try {
            $requestData = [
                'van_txn_type' => self::TXN_TYPE_INQUIRY,
                'terminal_id' => $this->terminalId,
                'store_id' => $this->storeId,
                'van_id' => $this->vanId,
                'transaction_id' => $van_txn_id,
                'txn_date' => date('Ymd'),
                'txn_time' => date('His'),
                'message_type' => self::MSG_TYPE_REQUEST,
                'processing_code' => '310000' // 조회 처리코드
            ];
            
            $requestData['hash_data'] = $this->generateKsnetHash($requestData);
            
            $response = $this->sendVanInquiryRequest($requestData);
            
            if ($response['resp_code'] === self::RESULT_SUCCESS) {
                return [
                    'status' => 'success',
                    'transaction_info' => [
                        'van_txn_id' => $response['van_txn_id'] ?? $van_txn_id,
                        'approval_no' => $response['approval_no'] ?? '',
                        'amount' => $response['amount'] ?? 0,
                        'txn_status' => $response['txn_status'] ?? '',
                        'txn_date' => $response['txn_date'] ?? '',
                        'card_company' => $response['card_company'] ?? '',
                        'card_no_masked' => $response['card_no_masked'] ?? ''
                    ]
                ];
            } else {
                throw new Exception('KSNET VAN 거래 조회 실패: ' . ($response['resp_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('KSNET VAN 결제 검증 실패', [
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
            
            $cancelTxnId = $this->generateOrderId($originalPayment['BCOFF_CD'], 'C');
            
            $requestData = [
                'van_txn_type' => $partial_yn === 'Y' ? self::TXN_TYPE_CANCEL : self::TXN_TYPE_AUTH_CANCEL,
                'terminal_id' => $this->terminalId,
                'store_id' => $this->storeId,
                'van_id' => $this->vanId,
                'transaction_id' => $cancelTxnId,
                'original_van_txn_id' => $originalPayment['VAN_TXN_ID'],
                'original_approval_no' => $originalPayment['APPROVAL_NO'],
                'cancel_amount' => $cancel_amount,
                'cancel_reason' => $cancel_reason,
                'txn_date' => date('Ymd'),
                'txn_time' => date('His'),
                'message_type' => self::MSG_TYPE_REQUEST,
                'processing_code' => '200000' // 취소 처리코드
            ];
            
            // KSNET VAN 취소 해시 생성
            $requestData['hash_data'] = $this->generateKsnetHash($requestData);
            
            // VAN 취소 요청
            $response = $this->sendVanCancelRequest($requestData);
            
            if ($response['resp_code'] === self::RESULT_CANCEL_SUCCESS) {
                $cancelResult = [
                    'cancel_txn_id' => $cancelTxnId,
                    'cancel_approval_no' => $response['cancel_approval_no'] ?? '',
                    'cancel_amount' => $cancel_amount,
                    'cancel_date' => date('Y-m-d'),
                    'cancel_time' => date('H:i:s'),
                    'cancel_reason' => $cancel_reason,
                    'van_provider' => 'KSNET'
                ];
                
                // 취소 정보 저장
                $refundDetailSno = $this->saveCancelPayment($originalPayment, $cancelResult);
                
                return [
                    'status' => 'success',
                    'cancel_result' => $cancelResult,
                    'refund_detail_sno' => $refundDetailSno
                ];
            } else {
                throw new Exception('KSNET VAN 취소 요청 실패: ' . ($response['resp_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('KSNET VAN 결제 취소 실패', [
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
     * 거래 내역 조회
     */
    public function getTransactionHistory($date_from, $date_to)
    {
        try {
            $requestData = [
                'van_txn_type' => self::TXN_TYPE_INQUIRY,
                'terminal_id' => $this->terminalId,
                'store_id' => $this->storeId,
                'van_id' => $this->vanId,
                'date_from' => str_replace('-', '', $date_from),
                'date_to' => str_replace('-', '', $date_to),
                'page_size' => 100,
                'page_no' => 1,
                'txn_date' => date('Ymd'),
                'txn_time' => date('His')
            ];
            
            $requestData['hash_data'] = $this->generateKsnetHash($requestData);
            
            $response = $this->sendVanHistoryRequest($requestData);
            
            if ($response['resp_code'] === self::RESULT_SUCCESS) {
                return [
                    'status' => 'success',
                    'total_count' => $response['total_count'] ?? 0,
                    'total_amount' => $response['total_amount'] ?? 0,
                    'transactions' => $response['transaction_list'] ?? []
                ];
            } else {
                throw new Exception('KSNET VAN 거래내역 조회 실패: ' . ($response['resp_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('KSNET VAN 거래내역 조회 실패', [
                'error' => $e->getMessage(),
                'period' => $date_from . ' ~ ' . $date_to
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * 정산 데이터 조회
     */
    public function getSettlementData($settle_date)
    {
        try {
            $requestData = [
                'van_txn_type' => self::TXN_TYPE_SETTLE,
                'terminal_id' => $this->terminalId,
                'store_id' => $this->storeId,
                'van_id' => $this->vanId,
                'settle_date' => str_replace('-', '', $settle_date),
                'txn_date' => date('Ymd'),
                'txn_time' => date('His'),
                'message_type' => self::MSG_TYPE_REQUEST,
                'processing_code' => '920000' // 정산 조회 처리코드
            ];
            
            $requestData['hash_data'] = $this->generateKsnetHash($requestData);
            
            $response = $this->sendVanSettleRequest($requestData);
            
            if ($response['resp_code'] === self::RESULT_SUCCESS) {
                return [
                    'status' => 'success',
                    'settle_date' => $settle_date,
                    'total_sale_count' => $response['total_sale_count'] ?? 0,
                    'total_sale_amount' => $response['total_sale_amount'] ?? 0,
                    'total_cancel_count' => $response['total_cancel_count'] ?? 0,
                    'total_cancel_amount' => $response['total_cancel_amount'] ?? 0,
                    'net_amount' => $response['net_amount'] ?? 0,
                    'commission' => $response['commission'] ?? 0,
                    'settle_amount' => $response['settle_amount'] ?? 0,
                    'settle_details' => $response['settle_details'] ?? []
                ];
            } else {
                throw new Exception('KSNET VAN 정산조회 실패: ' . ($response['resp_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('KSNET VAN 정산조회 실패', [
                'error' => $e->getMessage(),
                'settle_date' => $settle_date
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
            // KSNET VAN 실제 API 연결 테스트
            // 터미널 상태 조회를 통한 연결 테스트
            $timestamp = date('YmdHis');
            
            $testData = [
                'TrNo' => '9999',  // 터미널 상태 조회 거래번호
                'TrCode' => 'SHC',  // Status Health Check
                'TerminalId' => $this->terminalId,
                'StoreId' => $this->storeId,
                'VanId' => $this->vanId,
                'TrDateTime' => $timestamp,
                'Version' => '3.0',
                'Charset' => 'UTF-8'
            ];
            
            // KSNET VAN 서명 생성
            $testData['HashData'] = $this->generateKsnetHash($testData);
            
            // KSNET VAN API 호출
            $ch = curl_init();
            
            $url = $this->isTestMode 
                ? 'https://test-van.ksnet.co.kr/vanapi/terminal/status'
                : 'https://van.ksnet.co.kr/vanapi/terminal/status';
                
            // KSNET은 POST 데이터를 특정 형식으로 전송
            $postData = $this->buildKsnetPostData($testData);
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
                'Accept: application/json',
                'X-KSNET-TerminalId: ' . $this->terminalId,
                'X-KSNET-ApiKey: ' . ($this->apiKey ?? '')
            ]);
            
            $startTime = microtime(true);
            $response = curl_exec($ch);
            $responseTime = round((microtime(true) - $startTime) * 1000);
            
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                throw new Exception('KSNET VAN 서버 연결 실패: ' . $error);
            }
            
            // 응답 파싱
            $responseData = $this->parseKsnetResponse($response);
            
            // 응답 코드 확인
            $respCode = $responseData['RespCode'] ?? '';
            $isSuccess = ($httpCode == 200) && ($respCode === '0000' || $respCode === 'O');
            
            if ($isSuccess) {
                $this->logger->info('KSNET VAN 연결 테스트 성공', [
                    'terminal_id' => $this->terminalId,
                    'store_id' => $this->storeId,
                    'response_time' => $responseTime . 'ms'
                ]);
                
                return [
                    'status' => 'success',
                    'response_time' => $responseTime . 'ms',
                    'terminal_id' => $this->terminalId,
                    'store_id' => $this->storeId,
                    'van_id' => $this->vanId,
                    'api_version' => $responseData['Version'] ?? 'V3.0',
                    'test_mode' => $this->isTestMode,
                    'van_status' => $responseData['VanStatus'] ?? 'ACTIVE',
                    'terminal_status' => $responseData['TerminalStatus'] ?? 'ONLINE',
                    'store_name' => $responseData['StoreName'] ?? '',
                    'last_transaction' => $responseData['LastTrDateTime'] ?? ''
                ];
            } else {
                $errorMessage = $responseData['RespMsg'] ?? 
                               'KSNET VAN 터미널 정보가 올바르지 않습니다.';
                throw new Exception($errorMessage);
            }
            
        } catch (Exception $e) {
            $this->logger->error('KSNET VAN 연결 테스트 실패', [
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
    
    /**
     * KSNET POST 데이터 구성
     */
    private function buildKsnetPostData($data)
    {
        // KSNET은 key=value&key=value 형식을 사용
        $postData = [];
        foreach ($data as $key => $value) {
            $postData[] = $key . '=' . urlencode($value);
        }
        return implode('&', $postData);
    }
    
    /**
     * KSNET 응답 파싱
     */
    private function parseKsnetResponse($response)
    {
        // JSON 응답 시도
        $data = json_decode($response, true);
        if ($data) {
            return $data;
        }
        
        // key=value 형식 파싱
        $result = [];
        parse_str($response, $result);
        
        return $result;
    }

    // =========================================================================
    // Private Helper Methods
    // =========================================================================

    /**
     * KSNET VAN 전용 데이터 암호화
     */
    private function encryptKsnetData($data)
    {
        $dataString = json_encode($data, JSON_UNESCAPED_UNICODE);
        $key = substr(hash('sha256', $this->encryptKey, true), 0, 16);
        $iv = openssl_random_pseudo_bytes(16);
        
        $encrypted = openssl_encrypt(
            $dataString,
            'aes-128-cbc',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        
        return base64_encode($iv . $encrypted);
    }

    /**
     * KSNET VAN 전용 해시 생성
     */
    private function generateKsnetHash($data)
    {
        // KSNET 해시 규칙: StoreId + TerminalId + TransactionId + Amount + ApiKey
        $hashString = $this->storeId . $this->terminalId . 
                     ($data['transaction_id'] ?? '') . 
                     ($data['amount'] ?? '') . 
                     $this->apiKey;
        
        return strtoupper(hash('sha256', $hashString));
    }

    /**
     * 주문번호 생성
     */
    private function generateOrderId($bcoff_cd, $prefix = 'T')
    {
        return 'KSNET_' . $prefix . '_' . $bcoff_cd . '_' . date('YmdHis') . '_' . mt_rand(1000, 9999);
    }

    /**
     * VAN 승인 요청 전송
     */
    private function sendVanAuthRequest($data)
    {
        $url = $this->apiUrl . '/van/auth';
        return $this->sendKsnetRequest($url, $data);
    }

    /**
     * VAN 조회 요청 전송
     */
    private function sendVanInquiryRequest($data)
    {
        $url = $this->apiUrl . '/van/inquiry';
        return $this->sendKsnetRequest($url, $data);
    }

    /**
     * VAN 취소 요청 전송
     */
    private function sendVanCancelRequest($data)
    {
        $url = $this->apiUrl . '/van/cancel';
        return $this->sendKsnetRequest($url, $data);
    }

    /**
     * VAN 정산 요청 전송
     */
    private function sendVanSettleRequest($data)
    {
        $url = $this->apiUrl . '/van/settle';
        return $this->sendKsnetRequest($url, $data);
    }

    /**
     * VAN 거래내역 요청 전송
     */
    private function sendVanHistoryRequest($data)
    {
        $url = $this->apiUrl . '/van/history';
        return $this->sendKsnetRequest($url, $data);
    }

    /**
     * VAN 테스트 요청 전송
     */
    private function sendVanTestRequest($data)
    {
        $url = $this->apiUrl . '/van/test';
        return $this->sendKsnetRequest($url, $data);
    }

    /**
     * KSNET API 요청 전송
     */
    private function sendKsnetRequest($url, $data)
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-KSNET-VAN-ID: ' . $this->vanId,
            'X-KSNET-Terminal-ID: ' . $this->terminalId,
            'X-KSNET-Store-ID: ' . $this->storeId,
            'X-KSNET-API-Key: ' . $this->apiKey,
            'X-KSNET-Timestamp: ' . date('YmdHis')
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($response === false) {
            throw new Exception('KSNET VAN API 통신 오류');
        }
        
        $result = json_decode($response, true);
        if (!$result) {
            throw new Exception('KSNET VAN API 응답 파싱 오류');
        }
        
        return $result;
    }

    /**
     * 결제 승인 정보 저장
     */
    private function savePaymentApproval($paymentData, $approvalResult)
    {
        $paymt_mgmt_sno = $this->generatePaymentMgmtSno();
        
        $sql = "INSERT INTO paymt_mgmt_tbl SET
                PAYMT_MGMT_SNO = ?,
                COMP_CD = ?,
                BCOFF_CD = ?,
                MEM_SNO = ?,
                SELL_EVENT_SNO = ?,
                PAYMT_AMT = ?,
                PAYMT_MTHD = 'VAN',
                PAYMT_STAT = '00',
                PAYMT_DATE = NOW(),
                VAN_TXN_ID = ?,
                APPROVAL_NO = ?,
                CARD_COMPANY = ?,
                CARD_NO = ?,
                VAN_PROVIDER = ?,
                TERMINAL_ID = ?,
                CRE_ID = 'KSNET_VAN',
                CRE_DATETM = NOW()";
        
        $this->db->query($sql, [
            $paymt_mgmt_sno,
            $paymentData['COMP_CD'],
            $paymentData['BCOFF_CD'],
            $paymentData['MEM_SNO'],
            $paymentData['SELL_EVENT_SNO'] ?? '',
            $paymentData['PAYMT_AMT'],
            $approvalResult['van_txn_id'],
            $approvalResult['approval_no'],
            $approvalResult['card_company'],
            $approvalResult['card_no_masked'],
            'KSNET',
            $this->terminalId
        ]);
        
        return $paymt_mgmt_sno;
    }

    /**
     * 결제 취소 정보 저장
     */
    private function saveCancelPayment($originalPayment, $cancelResult)
    {
        $refund_detail_sno = $this->generateRefundDetailSno();
        
        $sql = "INSERT INTO refund_detail_tbl SET
                REFUND_DETAIL_SNO = ?,
                PAYMT_MGMT_SNO = ?,
                REFUND_AMT = ?,
                REFUND_MTHD = 'VAN_CANCEL',
                REFUND_DATE = NOW(),
                REFUND_STAT = '00',
                VAN_CANCEL_NO = ?,
                CANCEL_REASON = ?,
                CRE_ID = 'KSNET_VAN',
                CRE_DATETM = NOW()";
        
        $this->db->query($sql, [
            $refund_detail_sno,
            $originalPayment['PAYMT_MGMT_SNO'],
            $cancelResult['cancel_amount'],
            $cancelResult['cancel_approval_no'],
            $cancelResult['cancel_reason']
        ]);
        
        // 원거래 상태 업데이트
        $this->updatePaymentStatus($originalPayment['PAYMT_MGMT_SNO'], '02'); // 취소
        
        return $refund_detail_sno;
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
     * 결제 상태 업데이트
     */
    private function updatePaymentStatus($paymt_mgmt_sno, $status)
    {
        $sql = "UPDATE paymt_mgmt_tbl SET 
                PAYMT_STAT = ?, 
                MOD_ID = 'KSNET_VAN', 
                MOD_DATETM = NOW()
                WHERE PAYMT_MGMT_SNO = ?";
        
        $this->db->query($sql, [$status, $paymt_mgmt_sno]);
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
}