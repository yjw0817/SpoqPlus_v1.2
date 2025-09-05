<?php

namespace App\Services;

use CodeIgniter\Model;
use Exception;

/**
 * Nice VAN 결제 서비스 클래스
 * 
 * Nice VAN API를 이용한 터미널 기반 결제 처리를 담당합니다.
 * - 터미널 기반 결제 승인 (Terminal Payment Approval)
 * - 오프라인/온라인 거래 처리 (Offline/Online Transaction Processing)
 * - 거래 조회 및 상태 확인 (Transaction Inquiry & Status Check)
 * - 부분취소 및 전체취소 (Partial/Full Cancellation)
 * - 정산 및 배치 처리 (Settlement & Batch Processing)
 * - Nice VAN 전용 암호화 (Nice Encryption)
 */
class NiceVanService
{
    protected $db;
    protected $settings;
    protected $isTestMode;
    protected $apiUrl;
    protected $logger;
    protected $terminalId;
    protected $merchantId;
    protected $licenseKey;
    protected $cancelPwd;
    protected $encryptKey;

    // Nice VAN API 엔드포인트
    private const TEST_API_URL = 'https://webtest.nicepay.co.kr';
    private const LIVE_API_URL = 'https://web.nicepay.co.kr';
    
    // VAN 거래 타입
    private const TXN_TYPE_AUTH = 'CARD_AUTH';        // 승인
    private const TXN_TYPE_SALE = 'CARD_SALE';        // 매출
    private const TXN_TYPE_CANCEL = 'CARD_CANCEL';    // 취소
    private const TXN_TYPE_INQUIRY = 'CARD_INQUIRY';  // 조회
    private const TXN_TYPE_SETTLE = 'SETTLEMENT';     // 정산
    
    // 결과 코드
    private const RESULT_SUCCESS = '3001';
    private const RESULT_CANCEL_SUCCESS = '2001';
    private const RESULT_PENDING = '0000';
    private const RESULT_FAILED = '4000';
    
    // Nice VAN 응답 코드
    private const NICE_APPROVED = '0000';
    private const NICE_CANCELLED = '2001';
    private const NICE_DECLINED = '4001';
    private const NICE_ERROR = '9999';

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
        
        // Nice VAN 필수 설정
        $this->terminalId = $settings['terminal_id'] ?? '';
        $this->merchantId = $settings['merchant_id'] ?? '';
        $this->licenseKey = $settings['license_key'] ?? '';
        $this->cancelPwd = $settings['cancel_pwd'] ?? '';
        $this->encryptKey = $settings['encrypt_key'] ?? '';
        
        if (empty($this->terminalId) || empty($this->merchantId) || empty($this->licenseKey)) {
            throw new Exception('Nice VAN 필수 설정이 누락되었습니다.');
        }
        
        $this->logger->info('Nice VAN 서비스 초기화', [
            'mode' => $this->isTestMode ? 'TEST' : 'LIVE',
            'terminal_id' => $this->terminalId,
            'merchant_id' => $this->merchantId
        ]);
    }

    /**
     * 지점 설정으로 서비스 초기화
     */
    public function initializeWithBranchSettings($bcoff_cd)
    {
        $settings = $this->getBranchVanSettings($bcoff_cd, 'nice');
        if (!$settings) {
            throw new Exception('Nice VAN 설정을 찾을 수 없습니다.');
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
            $orderId = $this->generateOrderId($paymentData['BCOFF_CD']);
            
            $requestData = [
                'PayMethod' => $this->convertPaymentMethod($paymentData['PAYMT_MTHD']),
                'MID' => $this->merchantId,
                'Moid' => $orderId,
                'Amt' => (int)$paymentData['PAYMT_AMT'],
                'GoodsName' => $paymentData['SELL_EVENT_NM'],
                'BuyerName' => $paymentData['MEM_NM'],
                'BuyerEmail' => $paymentData['MEM_EMAIL'] ?? '',
                'BuyerTel' => $paymentData['MEM_TEL'] ?? '',
                'ReturnURL' => base_url('/payment/van/nice/return'),
                'CancelURL' => base_url('/payment/van/nice/cancel'),
                'Currency' => 'KRW',
                'ReqType' => 'JSON',
                'CharSet' => 'utf-8',
                'TerminalID' => $this->terminalId,
                'TxnType' => self::TXN_TYPE_SALE,
                'TimeStamp' => date('YmdHis'),
                'VbankExpDate' => date('Ymd', strtotime('+3 days')), // 가상계좌 만료일
                'MallIP' => $_SERVER['SERVER_ADDR'] ?? '127.0.0.1'
            ];

            // Nice VAN 전용 서명 생성
            $requestData['SignData'] = $this->generateNiceSignature($requestData);
            
            // 결제 정보를 DB에 임시 저장
            $this->saveTemporaryPayment($orderId, $paymentData, $requestData);
            
            // VAN 터미널 결제 요청
            $response = $this->sendVanPaymentRequest($requestData);
            
            if ($response['ResultCode'] === self::RESULT_SUCCESS || $response['ResultCode'] === self::RESULT_PENDING) {
                return [
                    'status' => 'success',
                    'van_order_id' => $orderId,
                    'nice_txn_id' => $response['TxnId'] ?? '',
                    'auth_token' => $response['AuthToken'] ?? '',
                    'next_redirect_url' => $response['NextRedirectURL'] ?? '',
                    'payment_form_data' => $this->buildPaymentFormData($requestData),
                    'van_response_code' => $response['ResultCode']
                ];
            } else {
                throw new Exception('Nice VAN 결제 요청 실패: ' . ($response['ResultMsg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('Nice VAN 결제 초기화 실패', [
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
            $orderId = $verificationData['Moid'] ?? '';
            $txnId = $verificationData['TxnId'] ?? '';
            $amount = $verificationData['Amt'] ?? 0;
            $authToken = $verificationData['AuthToken'] ?? '';
            
            if (empty($orderId) || empty($txnId)) {
                throw new Exception('필수 검증 데이터가 누락되었습니다.');
            }
            
            // Nice VAN 서명 검증
            if (!$this->verifyNiceSignature($verificationData)) {
                throw new Exception('Nice VAN 서명 검증 실패');
            }
            
            // 거래 조회로 실제 승인 상태 확인
            $inquiryResult = $this->inquireTransaction($orderId);
            
            if ($inquiryResult['status'] === 'success' && $inquiryResult['ResultCode'] === self::NICE_APPROVED) {
                // 결제 완료 정보 생성
                $paymentResult = [
                    'paymt_mgmt_sno' => $this->generatePaymentMgmtSno(),
                    'van_order_id' => $orderId,
                    'nice_txn_id' => $txnId,
                    'auth_token' => $authToken,
                    'amount' => $amount,
                    'payment_date' => date('Y-m-d H:i:s'),
                    'terminal_id' => $this->terminalId,
                    'merchant_id' => $this->merchantId,
                    'van_provider' => 'NICE',
                    'card_code' => $inquiryResult['CardCode'] ?? '',
                    'card_name' => $inquiryResult['CardName'] ?? '',
                    'card_no' => $inquiryResult['CardNo'] ?? '',
                    'install_month' => $inquiryResult['InstallPeriod'] ?? '00',
                    'auth_date' => $inquiryResult['AuthDate'] ?? '',
                    'auth_code' => $inquiryResult['AuthCode'] ?? ''
                ];
                
                // 결제 정보를 정식 테이블에 저장
                $this->saveCompletedPayment($paymentResult);
                
                return [
                    'status' => 'success',
                    'payment_result' => $paymentResult
                ];
            } else {
                throw new Exception('Nice VAN 거래 조회 실패 또는 미승인 상태');
            }
            
        } catch (Exception $e) {
            $this->logger->error('Nice VAN 결제 검증 실패', [
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
                'TxnType' => self::TXN_TYPE_CANCEL,
                'MID' => $this->merchantId,
                'CancelTxnId' => $cancelTxnId,
                'OriginalTxnId' => $originalPayment['NICE_TXN_ID'],
                'CancelAmt' => $cancel_amount,
                'PartialCancelCode' => $partial_yn === 'Y' ? '1' : '0',
                'CancelMsg' => $cancel_reason,
                'CancelPwd' => $this->cancelPwd,
                'TerminalID' => $this->terminalId,
                'TimeStamp' => date('YmdHis'),
                'CharSet' => 'utf-8'
            ];
            
            // Nice VAN 취소 서명 생성
            $requestData['SignData'] = $this->generateNiceCancelSignature($requestData);
            
            // VAN 취소 요청
            $response = $this->sendVanCancelRequest($requestData);
            
            if ($response['ResultCode'] === self::RESULT_CANCEL_SUCCESS) {
                $cancelResult = [
                    'cancel_txn_id' => $cancelTxnId,
                    'cancel_amount' => $cancel_amount,
                    'cancel_date' => date('Y-m-d H:i:s'),
                    'cancel_reason' => $cancel_reason,
                    'van_provider' => 'NICE',
                    'cancel_auth_code' => $response['CancelAuthCode'] ?? '',
                    'cancel_auth_date' => $response['CancelAuthDate'] ?? ''
                ];
                
                // 취소 정보 저장
                $refundDetailSno = $this->saveCancelPayment($originalPayment, $cancelResult);
                
                return [
                    'status' => 'success',
                    'cancel_result' => $cancelResult,
                    'refund_detail_sno' => $refundDetailSno
                ];
            } else {
                throw new Exception('Nice VAN 취소 요청 실패: ' . ($response['ResultMsg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('Nice VAN 결제 취소 실패', [
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
    public function inquireTransaction($orderId)
    {
        try {
            $requestData = [
                'TxnType' => self::TXN_TYPE_INQUIRY,
                'MID' => $this->merchantId,
                'Moid' => $orderId,
                'TerminalID' => $this->terminalId,
                'TimeStamp' => date('YmdHis'),
                'CharSet' => 'utf-8'
            ];
            
            $requestData['SignData'] = $this->generateNiceSignature($requestData);
            
            $response = $this->sendVanInquiryRequest($requestData);
            
            if ($response['ResultCode'] === self::NICE_APPROVED) {
                return [
                    'status' => 'success',
                    'ResultCode' => $response['ResultCode'],
                    'TxnId' => $response['TxnId'] ?? '',
                    'Amount' => $response['Amt'] ?? 0,
                    'CardCode' => $response['CardCode'] ?? '',
                    'CardName' => $response['CardName'] ?? '',
                    'CardNo' => $response['CardNo'] ?? '',
                    'InstallPeriod' => $response['InstallPeriod'] ?? '00',
                    'AuthDate' => $response['AuthDate'] ?? '',
                    'AuthCode' => $response['AuthCode'] ?? '',
                    'transaction_status' => $this->getTransactionStatus($response['ResultCode'])
                ];
            } else {
                return [
                    'status' => 'success',
                    'ResultCode' => $response['ResultCode'],
                    'ResultMsg' => $response['ResultMsg'] ?? '',
                    'transaction_status' => 'failed_or_not_found'
                ];
            }
            
        } catch (Exception $e) {
            $this->logger->error('Nice VAN 거래조회 실패', [
                'error' => $e->getMessage(),
                'order_id' => $orderId
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
                'TxnType' => self::TXN_TYPE_SETTLE,
                'MID' => $this->merchantId,
                'SettleDate' => $date, // YYYYMMDD 형식
                'TerminalID' => $this->terminalId,
                'TimeStamp' => date('YmdHis'),
                'CharSet' => 'utf-8'
            ];
            
            $requestData['SignData'] = $this->generateNiceSignature($requestData);
            
            $response = $this->sendVanSettleRequest($requestData);
            
            if ($response['ResultCode'] === self::RESULT_SUCCESS) {
                return [
                    'status' => 'success',
                    'settle_date' => $date,
                    'total_count' => $response['TotalCount'] ?? 0,
                    'total_amount' => $response['TotalAmount'] ?? 0,
                    'sale_count' => $response['SaleCount'] ?? 0,
                    'sale_amount' => $response['SaleAmount'] ?? 0,
                    'cancel_count' => $response['CancelCount'] ?? 0,
                    'cancel_amount' => $response['CancelAmount'] ?? 0,
                    'settlement_list' => $response['SettlementList'] ?? []
                ];
            } else {
                throw new Exception('Nice VAN 정산조회 실패: ' . ($response['ResultMsg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('Nice VAN 정산조회 실패', [
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
     * 배치 결제 처리 (오프라인 승인)
     */
    public function processBatchPayments($batchData)
    {
        try {
            $results = [];
            
            foreach ($batchData as $payment) {
                $batchResult = $this->processOfflinePayment($payment);
                $results[] = $batchResult;
            }
            
            return [
                'status' => 'success',
                'batch_count' => count($batchData),
                'success_count' => count(array_filter($results, fn($r) => $r['status'] === 'success')),
                'failed_count' => count(array_filter($results, fn($r) => $r['status'] === 'error')),
                'results' => $results
            ];
            
        } catch (Exception $e) {
            $this->logger->error('Nice VAN 배치처리 실패', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * 오프라인 결제 처리
     */
    private function processOfflinePayment($paymentData)
    {
        try {
            $orderId = $this->generateOrderId($paymentData['BCOFF_CD'], 'O');
            
            $requestData = [
                'TxnType' => 'OFFLINE_SALE',
                'MID' => $this->merchantId,
                'Moid' => $orderId,
                'Amt' => (int)$paymentData['amount'],
                'CardNo' => $paymentData['card_no'],
                'ExpYear' => $paymentData['exp_year'],
                'ExpMonth' => $paymentData['exp_month'],
                'CVC' => $paymentData['cvc'] ?? '',
                'InstallPeriod' => $paymentData['install_period'] ?? '00',
                'TerminalID' => $this->terminalId,
                'TimeStamp' => date('YmdHis')
            ];
            
            $requestData['SignData'] = $this->generateNiceSignature($requestData);
            
            $response = $this->sendVanRequest('/offline/payment', $requestData);
            
            if ($response['ResultCode'] === self::NICE_APPROVED) {
                return [
                    'status' => 'success',
                    'order_id' => $orderId,
                    'txn_id' => $response['TxnId'],
                    'auth_code' => $response['AuthCode'],
                    'amount' => $paymentData['amount']
                ];
            } else {
                return [
                    'status' => 'error',
                    'order_id' => $orderId,
                    'message' => $response['ResultMsg'] ?? '오프라인 결제 실패'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'order_id' => $orderId ?? 'N/A',
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
            // Nice VAN 실제 API 연결 테스트
            // 가맹점 정보 조회를 통한 연결 테스트
            $timestamp = date('YmdHis');
            
            $testData = [
                'TxnType' => 'MERCHANT_INFO',  // 가맹점 정보 조회
                'MID' => $this->merchantId,
                'TerminalID' => $this->terminalId,
                'TimeStamp' => $timestamp,
                'CharSet' => 'utf-8',
                'ServiceType' => 'VAN',
                'Version' => '2.0'
            ];
            
            // Nice VAN 서명 생성
            $testData['SignData'] = $this->generateNiceSignature($testData);
            
            // Nice VAN API 호출
            $ch = curl_init();
            
            $url = $this->isTestMode 
                ? 'https://test-van.nicepay.co.kr/api/merchant/info'
                : 'https://van.nicepay.co.kr/api/merchant/info';
                
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($testData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json',
                'X-NICEPAY-TerminalID: ' . $this->terminalId
            ]);
            
            $startTime = microtime(true);
            $response = curl_exec($ch);
            $responseTime = round((microtime(true) - $startTime) * 1000);
            
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                throw new Exception('Nice VAN 서버 연결 실패: ' . $error);
            }
            
            // JSON 응답 파싱
            $responseData = json_decode($response, true);
            if (!$responseData) {
                // XML 응답 파싱 시도
                $xml = simplexml_load_string($response);
                if ($xml) {
                    $responseData = json_decode(json_encode($xml), true);
                }
            }
            
            // 응답 코드 확인
            $resultCode = $responseData['ResultCode'] ?? $responseData['resultCode'] ?? '';
            $isSuccess = ($httpCode == 200) && ($resultCode === '0000' || $resultCode === '00');
            
            if ($isSuccess) {
                $this->logger->info('Nice VAN 연결 테스트 성공', [
                    'merchant_id' => $this->merchantId,
                    'terminal_id' => $this->terminalId,
                    'response_time' => $responseTime . 'ms'
                ]);
                
                return [
                    'status' => 'success',
                    'response_time' => $responseTime . 'ms',
                    'terminal_id' => $this->terminalId,
                    'merchant_id' => $this->merchantId,
                    'api_version' => $responseData['Version'] ?? 'V2.0',
                    'test_mode' => $this->isTestMode,
                    'van_status' => $responseData['Status'] ?? 'ACTIVE',
                    'merchant_name' => $responseData['MerchantName'] ?? '',
                    'terminal_status' => $responseData['TerminalStatus'] ?? 'ACTIVE'
                ];
            } else {
                $errorMessage = $responseData['ResultMsg'] ?? 
                               $responseData['resultMsg'] ?? 
                               'Nice VAN 가맹점 정보가 올바르지 않습니다.';
                throw new Exception($errorMessage);
            }
            
        } catch (Exception $e) {
            $this->logger->error('Nice VAN 연결 테스트 실패', [
                'error' => $e->getMessage(),
                'merchant_id' => $this->merchantId
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
     * Nice VAN 전용 서명 생성
     */
    private function generateNiceSignature($data)
    {
        // Nice VAN 서명 규칙에 따른 문자열 생성
        $signString = '';
        $signKeys = ['MID', 'Moid', 'Amt', 'TerminalID', 'TimeStamp'];
        
        foreach ($signKeys as $key) {
            if (isset($data[$key])) {
                $signString .= $data[$key];
            }
        }
        
        $signString .= $this->licenseKey;
        
        return hash('sha256', $signString);
    }

    /**
     * Nice VAN 취소 서명 생성
     */
    private function generateNiceCancelSignature($data)
    {
        $signString = $data['MID'] . $data['CancelTxnId'] . $data['OriginalTxnId'] . 
                     $data['CancelAmt'] . $data['TimeStamp'] . $this->licenseKey;
        
        return hash('sha256', $signString);
    }

    /**
     * Nice VAN 서명 검증
     */
    private function verifyNiceSignature($data)
    {
        $receivedSignature = $data['SignData'] ?? '';
        $expectedSignature = $this->generateNiceSignature($data);
        
        return hash_equals($expectedSignature, $receivedSignature);
    }

    /**
     * VAN API 요청 전송 메서드들
     */
    private function sendVanPaymentRequest($data)
    {
        return $this->sendVanRequest('/van/payment', $data);
    }

    private function sendVanCancelRequest($data)
    {
        return $this->sendVanRequest('/van/cancel', $data);
    }

    private function sendVanInquiryRequest($data)
    {
        return $this->sendVanRequest('/van/inquiry', $data);
    }

    private function sendVanSettleRequest($data)
    {
        return $this->sendVanRequest('/van/settlement', $data);
    }

    private function sendVanTestRequest($data)
    {
        return $this->sendVanRequest('/van/test', $data);
    }

    /**
     * VAN API 요청 전송 (공통)
     */
    private function sendVanRequest($endpoint, $data)
    {
        $client = \Config\Services::curlrequest();
        
        $options = [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8',
                'User-Agent' => 'SpoqPlus-Nice-VAN/2.0',
                'MID' => $this->merchantId,
                'TerminalID' => $this->terminalId,
                'Charset' => 'utf-8'
            ],
            'timeout' => 30,
            'json' => $data
        ];
        
        $response = $client->post($this->apiUrl . $endpoint, $options);
        
        if ($response->getStatusCode() !== 200) {
            throw new Exception('Nice VAN API 통신 오류: ' . $response->getStatusCode());
        }
        
        $responseData = json_decode($response->getBody(), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Nice VAN 응답 파싱 오류: ' . json_last_error_msg());
        }
        
        return $responseData;
    }

    /**
     * 결제수단 변환
     */
    private function convertPaymentMethod($paymentMethod)
    {
        $methodMap = [
            'CARD' => 'Card',        // 신용카드
            'VBANK' => 'VBank',      // 가상계좌
            'BANK' => 'DirectBank',  // 계좌이체
            'MOBILE' => 'HPP'        // 휴대폰
        ];
        
        return $methodMap[$paymentMethod] ?? 'Card';
    }

    /**
     * 주문번호 생성
     */
    private function generateOrderId($bcoff_cd, $type = 'P')
    {
        // Nice VAN 주문번호 형식: NICE + 지점코드 + 타입 + YYYYMMDDHHMMSS + 랜덤4자리
        return 'NICE' . $bcoff_cd . $type . date('YmdHis') . sprintf('%04d', mt_rand(1, 9999));
    }

    /**
     * 결제관리 일련번호 생성
     */
    private function generatePaymentMgmtSno()
    {
        return 'NICE' . date('YmdHis') . sprintf('%06d', mt_rand(1, 999999));
    }

    /**
     * 거래 상태 변환
     */
    private function getTransactionStatus($resultCode)
    {
        $statusMap = [
            self::NICE_APPROVED => 'approved',
            self::NICE_CANCELLED => 'cancelled',
            self::NICE_DECLINED => 'declined',
            self::NICE_ERROR => 'error'
        ];
        
        return $statusMap[$resultCode] ?? 'unknown';
    }

    /**
     * 결제 폼 데이터 생성
     */
    private function buildPaymentFormData($requestData)
    {
        return [
            'MID' => $requestData['MID'],
            'Moid' => $requestData['Moid'],
            'Amt' => $requestData['Amt'],
            'GoodsName' => $requestData['GoodsName'],
            'BuyerName' => $requestData['BuyerName'],
            'BuyerEmail' => $requestData['BuyerEmail'],
            'SignData' => $requestData['SignData'],
            'TerminalID' => $requestData['TerminalID'],
            'ReturnURL' => $requestData['ReturnURL']
        ];
    }

    /**
     * 임시 결제 정보 저장
     */
    private function saveTemporaryPayment($orderId, $paymentData, $requestData)
    {
        $sql = "INSERT INTO tmp_paymt_tbl (
                    TXN_ID, BCOFF_CD, MEM_SNO, MEM_ID, MEM_NM, SELL_EVENT_SNO, SELL_EVENT_NM,
                    PAYMT_AMT, PAYMT_MTHD, PAYMT_CHNL, VAN_PROVIDER, TERMINAL_ID, MERCHANT_ID,
                    CRE_DATETM, MOD_DATETM
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $this->db->query($sql, [
            $orderId,
            $paymentData['BCOFF_CD'],
            $paymentData['MEM_SNO'],
            $paymentData['MEM_ID'],
            $paymentData['MEM_NM'],
            $paymentData['SELL_EVENT_SNO'] ?? '',
            $paymentData['SELL_EVENT_NM'],
            $paymentData['PAYMT_AMT'],
            $paymentData['PAYMT_MTHD'],
            $paymentData['PAYMT_CHNL'] ?? 'VAN',
            'NICE',
            $this->terminalId,
            $this->merchantId
        ]);
    }

    /**
     * 완료된 결제 정보 저장
     */
    private function saveCompletedPayment($paymentResult)
    {
        $sql = "INSERT INTO paymt_mgmt_tbl (
                    PAYMT_MGMT_SNO, VAN_ORDER_ID, NICE_TXN_ID, AUTH_TOKEN, PAYMT_AMT, 
                    PAYMT_STAT, PAYMT_DATETM, TERMINAL_ID, MERCHANT_ID, VAN_PROVIDER,
                    CARD_CODE, CARD_NAME, CARD_NO, INSTALL_MONTH, AUTH_DATE, AUTH_CODE,
                    CRE_DATETM, MOD_DATETM
                ) VALUES (?, ?, ?, ?, ?, '00', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $this->db->query($sql, [
            $paymentResult['paymt_mgmt_sno'],
            $paymentResult['van_order_id'],
            $paymentResult['nice_txn_id'],
            $paymentResult['auth_token'],
            $paymentResult['amount'],
            $paymentResult['payment_date'],
            $paymentResult['terminal_id'],
            $paymentResult['merchant_id'],
            $paymentResult['van_provider'],
            $paymentResult['card_code'],
            $paymentResult['card_name'],
            $paymentResult['card_no'],
            $paymentResult['install_month'],
            $paymentResult['auth_date'],
            $paymentResult['auth_code']
        ]);
    }

    /**
     * 취소 결제 정보 저장
     */
    private function saveCancelPayment($originalPayment, $cancelResult)
    {
        $refundDetailSno = 'RF' . date('YmdHis') . sprintf('%06d', mt_rand(1, 999999));
        
        $sql = "INSERT INTO refund_detail_tbl (
                    REFUND_DETAIL_SNO, PAYMT_MGMT_SNO, CANCEL_TXN_ID, CANCEL_AUTH_CODE,
                    CANCEL_AMT, CANCEL_RSON, CANCEL_DATETM, VAN_PROVIDER, CANCEL_AUTH_DATE,
                    CRE_DATETM, MOD_DATETM
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $this->db->query($sql, [
            $refundDetailSno,
            $originalPayment['PAYMT_MGMT_SNO'],
            $cancelResult['cancel_txn_id'],
            $cancelResult['cancel_auth_code'],
            $cancelResult['cancel_amount'],
            $cancelResult['cancel_reason'],
            $cancelResult['cancel_date'],
            $cancelResult['van_provider'],
            $cancelResult['cancel_auth_date']
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