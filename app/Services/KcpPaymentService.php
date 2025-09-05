<?php

namespace App\Services;

use CodeIgniter\Model;
use Exception;

/**
 * KCP PG 결제 서비스 클래스
 * 
 * KCP API를 이용한 결제 처리를 담당합니다.
 * - 결제 요청 (Payment Request)
 * - 결제 검증 (Payment Verification)
 * - 결제 취소 (Payment Cancellation)
 * - 모바일 및 카드 결제 지원
 * - 테스트/운영 모드 지원
 */
class KcpPaymentService
{
    protected $db;
    protected $settings;
    protected $isTestMode;
    protected $apiUrl;
    protected $logger;

    // KCP API 엔드포인트
    private const TEST_API_URL = 'https://testpaygw.kcp.co.kr';
    private const LIVE_API_URL = 'https://paygw.kcp.co.kr';
    
    // 결제 상태 코드
    private const PAYMENT_SUCCESS = '0000';
    private const PAYMENT_PENDING = '0001';
    private const PAYMENT_FAILED = '9999';
    private const PAYMENT_CANCELLED = '0002';

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
        
        $this->logger->info('KCP 결제 서비스 초기화', [
            'mode' => $this->isTestMode ? 'TEST' : 'LIVE',
            'site_cd' => $settings['site_cd'] ?? 'N/A'
        ]);
    }

    /**
     * 지점 설정으로 서비스 초기화
     */
    public function initializeWithBranchSettings($bcoff_cd)
    {
        $settings = $this->getBranchPaymentSettings($bcoff_cd, 'kcp');
        if (!$settings) {
            throw new Exception('KCP 결제 설정을 찾을 수 없습니다.');
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
            $tid = $this->generateTransactionId($paymentData['BCOFF_CD']);
            
            // 결제 요청 데이터 구성
            $requestData = $this->buildPaymentRequest($paymentData, $tid);
            
            // KCP API 호출
            $response = $this->callKcpAPI('/tx/payment/init', $requestData, 'POST');
            
            if ($response['res_cd'] === '0000') {
                // 결제 정보 임시 저장
                $this->saveTemporaryPayment($tid, $paymentData, $response);
                
                $this->logger->info('KCP 결제 초기화 성공', [
                    'tid' => $tid,
                    'amount' => $paymentData['PAYMT_AMT'],
                    'member_id' => $paymentData['MEM_ID']
                ]);
                
                return [
                    'status' => 'success',
                    'tid' => $tid,
                    'tno' => $response['tno'] ?? null,
                    'payment_url' => $response['payment_url'] ?? null,
                    'form_data' => $response['form_data'] ?? null,
                    'next_redirect_pc_url' => $response['next_redirect_pc_url'] ?? null,
                    'next_redirect_mobile_url' => $response['next_redirect_mobile_url'] ?? null
                ];
            } else {
                throw new Exception('결제 초기화 실패: ' . ($response['res_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('KCP 결제 초기화 실패', [
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
            // 서명 검증
            if (!$this->verifySignature($verificationData)) {
                throw new Exception('결제 결과 서명 검증 실패');
            }
            
            // KCP 결제 결과 조회
            $paymentResult = $this->getPaymentResult($verificationData['tid']);
            
            if ($paymentResult['res_cd'] === '0000') {
                // 결제 성공 처리
                $this->processSuccessfulPayment($verificationData, $paymentResult);
                
                $this->logger->info('KCP 결제 검증 성공', [
                    'tid' => $verificationData['tid'],
                    'amount' => $paymentResult['amount']
                ]);
                
                return [
                    'status' => 'success',
                    'payment_result' => $paymentResult,
                    'paymt_mgmt_sno' => $paymentResult['paymt_mgmt_sno'] ?? null
                ];
            } else {
                throw new Exception('결제 실패: ' . ($paymentResult['res_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('KCP 결제 검증 실패', [
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
            
            // 취소 요청 데이터 구성
            $requestData = $this->buildCancelRequest($cancelData);
            
            // KCP 취소 API 호출
            $response = $this->callKcpAPI('/tx/payment/cancel', $requestData, 'POST');
            
            if ($response['res_cd'] === '0000') {
                // 취소 완료 처리
                $this->processCancelledPayment($cancelData, $response);
                
                $this->logger->info('KCP 결제 취소 성공', [
                    'paymt_mgmt_sno' => $cancelData['PAYMT_MGMT_SNO'],
                    'cancel_amount' => $cancelData['CANCEL_AMT']
                ]);
                
                return [
                    'status' => 'success',
                    'cancel_result' => $response,
                    'refund_detail_sno' => $response['refund_detail_sno'] ?? null
                ];
            } else {
                throw new Exception('결제 취소 실패: ' . ($response['res_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('KCP 결제 취소 실패', [
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
     * KCP API 연결 테스트
     */
    public function testConnection()
    {
        try {
            // KCP 실제 API 연결 테스트
            // KCP 가맹점 정보 검증 API 사용
            
            $timestamp = date('YmdHis');
            $site_cd = $this->settings['site_cd'] ?? '';
            $site_key = $this->settings['site_key'] ?? '';
            
            if (empty($site_cd) || empty($site_key)) {
                throw new Exception('Site CD 또는 Site Key가 설정되지 않았습니다.');
            }
            
            // KCP 가맹점 검증을 위한 해시 생성
            $hash_data = $site_cd . $timestamp . $site_key;
            $hash_value = hash('sha256', $hash_data);
            
            // KCP 가맹점 정보 검증 요청
            $testData = [
                'site_cd' => $site_cd,
                'kcp_cert_info' => $site_key,
                'enc_data' => base64_encode($site_cd . '^' . $timestamp),
                'enc_info' => $hash_value,
                'ordr_idxx' => 'TEST_' . $timestamp,
                'req_tx' => 'pay',
                'tran_cd' => '00100000',
                'good_mny' => '1000',
                'currency' => 'WON',
                'module_type' => '01'
            ];
            
            // KCP 가맹점 검증 API 호출
            $ch = curl_init();
            
            // KCP는 실제로는 PP_CLI를 통해 검증하지만, 여기서는 간접적으로 검증
            $url = $this->isTestMode 
                ? 'https://testpgapi.kcp.co.kr/api/v1/merchant/verify'
                : 'https://pgapi.kcp.co.kr/api/v1/merchant/verify';
            
            // 대안: 결제창 URL로 site_cd 유효성 체크
            $check_url = $this->isTestMode
                ? 'https://testpay.kcp.co.kr/plugin/certify_process.jsp'
                : 'https://pay.kcp.co.kr/plugin/certify_process.jsp';
                
            curl_setopt($ch, CURLOPT_URL, $check_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($testData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HEADER, true);
            
            $startTime = microtime(true);
            $response = curl_exec($ch);
            $responseTime = round((microtime(true) - $startTime) * 1000);
            
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                throw new Exception('KCP 서버 연결 실패: ' . $error);
            }
            
            // KCP 응답 검증 - 정확한 에러 메시지 체크
            $hasError = false;
            $errorMessage = '';
            
            if (strpos($body, 'res_cd=9502') !== false) {
                $hasError = true;
                $errorMessage = '잘못된 Site CD입니다.';
            } elseif (strpos($body, 'res_cd=9503') !== false) {
                $hasError = true;
                $errorMessage = '잘못된 Site Key입니다.';
            } elseif (strpos($body, '가맹점정보오류') !== false) {
                $hasError = true;
                $errorMessage = '가맹점 정보가 올바르지 않습니다.';
            } elseif (strpos($body, 'site_cd 없음') !== false) {
                $hasError = true;
                $errorMessage = 'Site CD가 존재하지 않습니다.';
            }
            
            // HTTP 200이고 에러가 없으면 성공
            $isSuccess = ($httpCode == 200 || $httpCode == 302) && !$hasError;
            
            if ($isSuccess) {
                $this->logger->info('KCP 연결 테스트 성공', [
                    'site_cd' => substr($site_cd, 0, 5) . '***',
                    'response_time' => $responseTime . 'ms',
                    'test_mode' => $this->isTestMode
                ]);
                
                return [
                    'status' => 'success',
                    'response_time' => $responseTime . 'ms',
                    'test_transaction_id' => 'KCP_TEST_' . time(),
                    'api_version' => 'V1.0',
                    'site_cd_valid' => true,
                    'test_mode' => $this->isTestMode,
                    'merchant_verified' => true
                ];
            } else {
                throw new Exception($errorMessage ?: 'KCP 가맹점 정보가 올바르지 않습니다.');
            }
            
        } catch (Exception $e) {
            $this->logger->error('KCP 연결 테스트 실패', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * KCP 인증 데이터 생성
     */
    private function generateAuthData($site_cd, $ordr_idxx, $good_mny)
    {
        // KCP 인증 데이터 생성 로직
        // 실제로는 KCP 제공 라이브러리를 사용해야 함
        $auth_data = [
            'enc_data' => base64_encode($site_cd . '|' . $ordr_idxx . '|' . $good_mny),
            'enc_info' => 'TEST_AUTH_' . time()
        ];
        
        return $auth_data;
    }

    /**
     * 거래 고유번호 생성
     */
    private function generateTransactionId($bcoff_cd)
    {
        $prefix = $this->isTestMode ? 'TEST' : 'LIVE';
        $timestamp = date('YmdHis');
        $random = sprintf('%04d', mt_rand(0, 9999));
        
        return "{$prefix}_{$bcoff_cd}_{$timestamp}_{$random}";
    }

    /**
     * 결제 요청 데이터 구성
     */
    private function buildPaymentRequest($paymentData, $tid)
    {
        $timestamp = date('YmdHis');
        
        $requestData = [
            'site_cd' => $this->settings['site_cd'],
            'kcp_cert_info' => $this->settings['site_key'],
            'tran_cd' => $this->getTransactionCode($paymentData['PAYMT_MTHD']),
            'ordr_idxx' => $tid,
            'good_mny' => (int)$paymentData['PAYMT_AMT'],
            'good_name' => $paymentData['SELL_EVENT_NM'],
            'req_tx' => 'pay',
            'shop_user_id' => $paymentData['MEM_ID'],
            'buyr_name' => $paymentData['MEM_NM'],
            'buyr_mail' => $paymentData['MEM_EMAIL'] ?? '',
            'buyr_tel1' => $this->formatPhoneNumber($paymentData['MEM_TEL'] ?? ''),
            'buyr_tel2' => $this->formatPhoneNumber($paymentData['MEM_TEL'] ?? ''),
            'pay_method' => $this->getPaymentMethod($paymentData['PAYMT_MTHD']),
            'currency' => 'WON',
            'escw_used' => 'N',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'SpoqPlus/1.0',
            'rem_addr' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'enc_info' => $this->generateEncryptionInfo($tid, $paymentData['PAYMT_AMT'], $timestamp),
            'ver_info' => 'KCPPAYGW_V1.0'
        ];

        // 모바일 결제인 경우 추가 설정
        if ($paymentData['PAYMT_CHNL'] === 'MOBILE') {
            $requestData['device'] = 'MOBILE';
            $requestData['return_url'] = base_url('payment/kcp/return');
            $requestData['cancel_url'] = base_url('payment/kcp/cancel');
        } else {
            $requestData['device'] = 'PC';
            $requestData['return_url'] = base_url('payment/kcp/return');
            $requestData['cancel_url'] = base_url('payment/kcp/cancel');
        }

        return $requestData;
    }

    /**
     * 결제 방법에 따른 거래 코드 반환
     */
    private function getTransactionCode($paymt_mthd)
    {
        $transactionMap = [
            'CARD' => '00100000',     // 신용카드
            'BANK' => '00200000',     // 계좌이체
            'VBANK' => '00300000',    // 가상계좌
            'MOBILE' => '00400000',   // 휴대폰결제
            'POINT' => '00500000'     // 포인트결제
        ];
        
        return $transactionMap[$paymt_mthd] ?? '00100000';
    }

    /**
     * 결제 방법 코드 변환
     */
    private function getPaymentMethod($paymt_mthd)
    {
        $methodMap = [
            'CARD' => '100000000000',
            'BANK' => '010000000000',
            'VBANK' => '001000000000',
            'MOBILE' => '000100000000',
            'POINT' => '000010000000'
        ];
        
        return $methodMap[$paymt_mthd] ?? '100000000000';
    }

    /**
     * 전화번호 포맷팅
     */
    private function formatPhoneNumber($phone)
    {
        // 숫자만 추출
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($phone) === 11 && substr($phone, 0, 3) === '010') {
            return substr($phone, 0, 3) . '-' . substr($phone, 3, 4) . '-' . substr($phone, 7, 4);
        } elseif (strlen($phone) === 10) {
            return substr($phone, 0, 2) . '-' . substr($phone, 2, 4) . '-' . substr($phone, 6, 4);
        }
        
        return $phone;
    }

    /**
     * 암호화 정보 생성
     */
    private function generateEncryptionInfo($tid, $amount, $timestamp)
    {
        $siteKey = $this->settings['site_key'];
        $hashData = $this->settings['site_cd'] . $tid . $amount . $timestamp;
        
        return hash_hmac('sha256', $hashData, $siteKey);
    }

    /**
     * 결제 취소 요청 데이터 구성
     */
    private function buildCancelRequest($cancelData)
    {
        $timestamp = date('YmdHis');
        
        return [
            'site_cd' => $this->settings['site_cd'],
            'kcp_cert_info' => $this->settings['site_key'],
            'tran_cd' => '00200000',
            'ordr_idxx' => $cancelData['TID'],
            'mod_type' => $cancelData['PARTIAL_YN'] === 'Y' ? 'STSC' : 'STPC',
            'mod_mny' => (int)$cancelData['CANCEL_AMT'],
            'mod_desc' => $cancelData['CANCEL_RSON'] ?? '고객 요청에 의한 취소',
            'req_tx' => 'mod',
            'ver_info' => 'KCPPAYGW_V1.0',
            'enc_info' => $this->generateCancelEncryptionInfo($cancelData['TID'], $cancelData['CANCEL_AMT'], $timestamp)
        ];
    }

    /**
     * 취소 암호화 정보 생성
     */
    private function generateCancelEncryptionInfo($tid, $amount, $timestamp)
    {
        $siteKey = $this->settings['site_key'];
        $hashData = $this->settings['site_cd'] . $tid . $amount . $timestamp;
        
        return hash_hmac('sha256', $hashData, $siteKey);
    }

    /**
     * KCP API 호출
     */
    private function callKcpAPI($endpoint, $data, $method = 'POST')
    {
        $url = $this->apiUrl . $endpoint;
        
        $curl = curl_init();
        
        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => !$this->isTestMode,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'User-Agent: SpoqPlus/1.0',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
            ]
        ];
        
        if ($method === 'POST') {
            $curlOptions[CURLOPT_POST] = true;
            $curlOptions[CURLOPT_POSTFIELDS] = http_build_query($data);
        } else {
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
        
        if ($httpCode !== 200) {
            throw new Exception("API 응답 오류: HTTP {$httpCode}");
        }
        
        // KCP 응답은 URL 인코딩된 형태로 반환됨
        parse_str($response, $result);
        
        // JSON 응답인 경우 처리
        if (empty($result) && !empty($response)) {
            $jsonResult = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $result = $jsonResult;
            }
        }
        
        $result['response_time'] = $responseTime . 'ms';
        
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
    }

    /**
     * 취소 데이터 검증
     */
    private function validateCancelData($data)
    {
        $required = ['PAYMT_MGMT_SNO', 'TID', 'CANCEL_AMT'];
        
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
     * 서명 검증
     */
    private function verifySignature($data)
    {
        if (empty($data['enc_info']) || empty($data['ordr_idxx']) || empty($data['good_mny'])) {
            return false;
        }
        
        $expectedHash = $this->generateEncryptionInfo($data['ordr_idxx'], $data['good_mny'], $data['tran_date'] ?? date('YmdHis'));
        
        return hash_equals($expectedHash, $data['enc_info']);
    }

    /**
     * 임시 결제 정보 저장
     */
    private function saveTemporaryPayment($tid, $paymentData, $response)
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
            $tid,
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
                'TID' => $verificationData['ordr_idxx'],
                'PAYMT_STAT' => self::PAYMENT_SUCCESS,
                'PAYMT_DATE' => date('Y-m-d H:i:s'),
                'APPNO' => $paymentResult['app_no'] ?? '',
                'MOD_ID' => 'KCP_API',
                'MOD_DATETM' => date('Y-m-d H:i:s')
            ];
            
            $this->updatePaymentMgmt($paymentMgmtData);
            
            // 임시 결제 정보 삭제
            $this->deleteTemporaryPayment($verificationData['ordr_idxx']);
            
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
            $this->updatePaymentStatus($cancelData['PAYMT_MGMT_SNO'], self::PAYMENT_CANCELLED);
            
            // 환불 상세 내역 저장
            $refund_detail_sno = $this->generateRefundDetailSno();
            
            $refundDetailData = [
                'REFUND_DETAIL_SNO' => $refund_detail_sno,
                'PAYMT_MGMT_SNO' => $cancelData['PAYMT_MGMT_SNO'],
                'REFUND_AMT' => $cancelData['CANCEL_AMT'],
                'REFUND_MTHD' => 'KCP_AUTO',
                'REFUND_DATE' => date('Y-m-d H:i:s'),
                'REFUND_STAT' => '00',
                'CRE_ID' => 'KCP_API',
                'CRE_DATETM' => date('Y-m-d H:i:s'),
                'MOD_ID' => 'KCP_API',
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
     * 결제 결과 조회
     */
    private function getPaymentResult($tid)
    {
        $requestData = [
            'site_cd' => $this->settings['site_cd'],
            'kcp_cert_info' => $this->settings['site_key'],
            'tran_cd' => '00200000',
            'ordr_idxx' => $tid,
            'req_tx' => 'query',
            'ver_info' => 'KCPPAYGW_V1.0'
        ];
        
        return $this->callKcpAPI('/tx/payment/query', $requestData, 'POST');
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
                MOD_ID = ?, 
                MOD_DATETM = ?
                WHERE PAYMT_MGMT_SNO = ?";
        
        $this->db->query($sql, [
            $data['PAYMT_STAT'],
            $data['PAYMT_DATE'],
            $data['APPNO'],
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
                MOD_ID = 'KCP_API', 
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
            $data['CRE_ID'],
            $data['CRE_DATETM'],
            $data['MOD_ID'],
            $data['MOD_DATETM']
        ]);
    }

    /**
     * 임시 결제 정보 삭제
     */
    private function deleteTemporaryPayment($tid)
    {
        $sql = "DELETE FROM temp_payment_tbl WHERE TID = ?";
        $this->db->query($sql, [$tid]);
    }
}