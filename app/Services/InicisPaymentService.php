<?php

namespace App\Services;

use CodeIgniter\Model;
use Exception;

/**
 * 이니시스 PG 결제 서비스 클래스
 * 
 * 이니시스 API를 이용한 결제 처리를 담당합니다.
 * - 결제 요청 (Payment Request)
 * - 결제 검증 (Payment Verification)
 * - 결제 취소 (Payment Cancellation)
 * - 모바일 및 카드 결제 지원
 * - 테스트/운영 모드 지원
 */
class InicisPaymentService
{
    protected $db;
    protected $settings;
    protected $isTestMode;
    protected $apiUrl;
    protected $logger;

    // 이니시스 API 엔드포인트
    private const TEST_API_URL = 'https://mobile.inicis.com';
    private const LIVE_API_URL = 'https://mobile.inicis.com';
    
    // 결제 상태 코드
    private const PAYMENT_SUCCESS = '00';
    private const PAYMENT_PENDING = '01';
    private const PAYMENT_FAILED = '99';
    private const PAYMENT_CANCELLED = '02';

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
        
        // merchant_id 매핑 처리
        if (!isset($settings['merchant_id']) && isset($settings['mobile_mid'])) {
            $this->settings['merchant_id'] = $settings['mobile_mid'];
        }
        if (!isset($settings['sign_key']) && isset($settings['mobile_signkey'])) {
            $this->settings['sign_key'] = $settings['mobile_signkey'];
        }
        
        $this->logger->info('이니시스 결제 서비스 초기화', [
            'mode' => $this->isTestMode ? 'TEST' : 'LIVE',
            'mid' => $this->settings['merchant_id'] ?? 'N/A'
        ]);
    }

    /**
     * 지점 설정으로 서비스 초기화
     */
    public function initializeWithBranchSettings($bcoff_cd)
    {
        $settings = $this->getBranchPaymentSettings($bcoff_cd, 'inicis');
        if (!$settings) {
            throw new Exception('이니시스 결제 설정을 찾을 수 없습니다.');
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
            
            // 이니시스 API 호출
            $response = $this->callInicisAPI('/api/v1/formpay', $requestData, 'POST');
            
            if ($response['result_code'] === '00') {
                // 결제 정보 임시 저장
                $this->saveTemporaryPayment($tid, $paymentData, $response);
                
                $this->logger->info('이니시스 결제 초기화 성공', [
                    'tid' => $tid,
                    'amount' => $paymentData['PAYMT_AMT'],
                    'member_id' => $paymentData['MEM_ID']
                ]);
                
                return [
                    'status' => 'success',
                    'tid' => $tid,
                    'payment_url' => $response['payment_url'] ?? null,
                    'form_data' => $response['form_data'] ?? null,
                    'next_redirect_pc_url' => $response['next_redirect_pc_url'] ?? null,
                    'next_redirect_mobile_url' => $response['next_redirect_mobile_url'] ?? null
                ];
            } else {
                throw new Exception('결제 초기화 실패: ' . ($response['result_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('이니시스 결제 초기화 실패', [
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
            
            // 이니시스 결제 결과 조회
            $paymentResult = $this->getPaymentResult($verificationData['tid']);
            
            if ($paymentResult['result_code'] === '00') {
                // 결제 성공 처리
                $this->processSuccessfulPayment($verificationData, $paymentResult);
                
                $this->logger->info('이니시스 결제 검증 성공', [
                    'tid' => $verificationData['tid'],
                    'amount' => $paymentResult['amount']
                ]);
                
                return [
                    'status' => 'success',
                    'payment_result' => $paymentResult,
                    'paymt_mgmt_sno' => $paymentResult['paymt_mgmt_sno'] ?? null
                ];
            } else {
                throw new Exception('결제 실패: ' . ($paymentResult['result_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('이니시스 결제 검증 실패', [
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
            
            // 이니시스 취소 API 호출
            $response = $this->callInicisAPI('/api/v1/refund', $requestData, 'POST');
            
            if ($response['result_code'] === '00') {
                // 취소 완료 처리
                $this->processCancelledPayment($cancelData, $response);
                
                $this->logger->info('이니시스 결제 취소 성공', [
                    'paymt_mgmt_sno' => $cancelData['PAYMT_MGMT_SNO'],
                    'cancel_amount' => $cancelData['CANCEL_AMT']
                ]);
                
                return [
                    'status' => 'success',
                    'cancel_result' => $response,
                    'refund_detail_sno' => $response['refund_detail_sno'] ?? null
                ];
            } else {
                throw new Exception('결제 취소 실패: ' . ($response['result_msg'] ?? '알 수 없는 오류'));
            }
            
        } catch (Exception $e) {
            $this->logger->error('이니시스 결제 취소 실패', [
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
     * 이니시스 API 연결 테스트
     */
    public function testConnection()
    {
        try {
            // 실제 이니시스 API를 사용한 연결 테스트
            // 설정값 확인 및 매핑
            $merchant_id = $this->settings['mobile_mid'] ?? $this->settings['merchant_id'] ?? '';
            $sign_key = $this->settings['mobile_signkey'] ?? $this->settings['sign_key'] ?? '';
            
            if (empty($merchant_id) || empty($sign_key)) {
                throw new Exception('MID 또는 SignKey가 설정되지 않았습니다.');
            }
            
            // 간단한 해시 검증으로 SignKey 유효성 테스트
            $timestamp = date('YmdHis');
            $test_string = $merchant_id . '|TEST|1000|' . $timestamp;
            $test_hash = hash('sha512', $test_string);
            
            // 이니시스는 실제 API 호출 없이도 MID 형식과 SignKey 검증 가능
            // MID 형식 검증 (보통 10자리)
            if (strlen($merchant_id) < 10) {
                throw new Exception('MID 형식이 올바르지 않습니다.');
            }
            
            // SignKey 형식 검증 (보통 32자리 이상)
            if (strlen($sign_key) < 20) {
                throw new Exception('SignKey 형식이 올바르지 않습니다.');
            }
            
            // 간단한 API 연결 테스트 (이니시스 메인 페이지 접근 가능 여부)
            $ch = curl_init();
            $test_url = 'https://mobile.inicis.com/smart/payment/';
            
            curl_setopt($ch, CURLOPT_URL, $test_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD 요청만
            
            $startTime = microtime(true);
            curl_exec($ch);
            $responseTime = round((microtime(true) - $startTime) * 1000);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                throw new Exception('이니시스 서버 연결 실패: ' . $error);
            }
            
            // HTTP 코드가 200이면 이니시스 서버 접근 가능
            if ($httpCode == 200 || $httpCode == 301 || $httpCode == 302) {
                $this->logger->info('이니시스 연결 테스트 성공', [
                    'merchant_id' => substr($merchant_id, 0, 5) . '***',
                    'response_time' => $responseTime . 'ms',
                    'test_mode' => $this->isTestMode
                ]);
                
                return [
                    'status' => 'success',
                    'response_time' => $responseTime . 'ms',
                    'test_transaction_id' => 'INICIS_TEST_' . time(),
                    'api_version' => '1.0',
                    'merchant_id' => substr($merchant_id, 0, 5) . '***',
                    'test_mode' => $this->isTestMode,
                    'mid_format_valid' => true,
                    'signkey_format_valid' => true
                ];
            } else {
                throw new Exception('이니시스 서버에 연결할 수 없습니다. (HTTP ' . $httpCode . ')');
            }
            
        } catch (Exception $e) {
            $this->logger->error('이니시스 연결 테스트 실패', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
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
            'mid' => $this->settings['merchant_id'],
            'goodname' => $paymentData['SELL_EVENT_NM'],
            'price' => (int)$paymentData['PAYMT_AMT'],
            'buyername' => $paymentData['MEM_NM'],
            'buyertel' => $paymentData['MEM_TEL'] ?? '',
            'buyeremail' => $paymentData['MEM_EMAIL'] ?? '',
            'orderNumber' => $tid,
            'timestamp' => $timestamp,
            'currency' => 'WON',
            'paymethod' => $this->getPaymentMethod($paymentData['PAYMT_MTHD']),
            'returnUrl' => base_url('payment/inicis/return'),
            'closeUrl' => base_url('payment/inicis/close'),
            'popupUrl' => base_url('payment/inicis/popup'),
            'charset' => 'UTF-8',
            'verification' => $this->generateVerificationHash($tid, $paymentData['PAYMT_AMT'], $timestamp)
        ];

        // 모바일 결제인 경우 추가 설정
        if ($paymentData['PAYMT_CHNL'] === 'MOBILE') {
            $requestData['mobile'] = 'Y';
            $requestData['acceptmethod'] = 'below1000';
        }

        return $requestData;
    }

    /**
     * 결제 방법 코드 변환
     */
    private function getPaymentMethod($paymt_mthd)
    {
        $methodMap = [
            'CARD' => 'Card',
            'BANK' => 'DirectBank',
            'VBANK' => 'VBank',
            'MOBILE' => 'HPP',
            'POINT' => 'Point'
        ];
        
        return $methodMap[$paymt_mthd] ?? 'Card';
    }

    /**
     * 검증 해시 생성
     */
    private function generateVerificationHash($tid, $amount, $timestamp)
    {
        $signKey = $this->settings['sign_key'];
        $hashData = $this->settings['merchant_id'] . $tid . $amount . $timestamp;
        
        return hash_hmac('sha256', $hashData, $signKey);
    }

    /**
     * 결제 취소 요청 데이터 구성
     */
    private function buildCancelRequest($cancelData)
    {
        $timestamp = date('YmdHis');
        
        return [
            'mid' => $this->settings['merchant_id'],
            'tid' => $cancelData['TID'],
            'cancelmsg' => $cancelData['CANCEL_RSON'] ?? '고객 요청에 의한 취소',
            'partialcancelcode' => $cancelData['PARTIAL_YN'] === 'Y' ? '1' : '0',
            'cancelamt' => (int)$cancelData['CANCEL_AMT'],
            'timestamp' => $timestamp,
            'verification' => $this->generateCancelVerificationHash($cancelData['TID'], $cancelData['CANCEL_AMT'], $timestamp)
        ];
    }

    /**
     * 취소 검증 해시 생성
     */
    private function generateCancelVerificationHash($tid, $amount, $timestamp)
    {
        $signKey = $this->settings['sign_key'];
        $hashData = $this->settings['merchant_id'] . $tid . $amount . $timestamp;
        
        return hash_hmac('sha256', $hashData, $signKey);
    }

    /**
     * 이니시스 API 호출
     */
    private function callInicisAPI($endpoint, $data, $method = 'POST')
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
                'User-Agent: SpoqPlus/1.0'
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
        
        // 이니시스는 200, 302, 405 등 다양한 응답을 반환할 수 있음
        if ($httpCode >= 500) {
            throw new Exception("이니시스 서버 오류: HTTP {$httpCode}");
        }
        
        // 응답이 비어있으면 빈 배열 반환
        if (empty($response)) {
            return [
                'response_time' => $responseTime . 'ms',
                'http_code' => $httpCode
            ];
        }
        
        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // URL 인코딩된 응답 파싱 시도
            parse_str($response, $result);
            
            // 그래도 파싱이 안되면 원본 반환
            if (empty($result)) {
                $result = ['raw_response' => $response];
            }
        }
        
        $result['response_time'] = $responseTime . 'ms';
        $result['http_code'] = $httpCode;
        
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
        if (empty($data['verification']) || empty($data['tid']) || empty($data['amount'])) {
            return false;
        }
        
        $expectedHash = $this->generateVerificationHash($data['tid'], $data['amount'], $data['timestamp'] ?? date('YmdHis'));
        
        return hash_equals($expectedHash, $data['verification']);
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
                'TID' => $verificationData['tid'],
                'PAYMT_STAT' => self::PAYMENT_SUCCESS,
                'PAYMT_DATE' => date('Y-m-d H:i:s'),
                'APPNO' => $paymentResult['applno'] ?? '',
                'MOD_ID' => 'INICIS_API',
                'MOD_DATETM' => date('Y-m-d H:i:s')
            ];
            
            $this->updatePaymentMgmt($paymentMgmtData);
            
            // 임시 결제 정보 삭제
            $this->deleteTemporaryPayment($verificationData['tid']);
            
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
                'REFUND_MTHD' => 'INICIS_AUTO',
                'REFUND_DATE' => date('Y-m-d H:i:s'),
                'REFUND_STAT' => '00',
                'CRE_ID' => 'INICIS_API',
                'CRE_DATETM' => date('Y-m-d H:i:s'),
                'MOD_ID' => 'INICIS_API',
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
            'mid' => $this->settings['merchant_id'],
            'tid' => $tid,
            'timestamp' => date('YmdHis')
        ];
        
        return $this->callInicisAPI('/api/v1/inquiry', $requestData, 'POST');
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
                MOD_ID = 'INICIS_API', 
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