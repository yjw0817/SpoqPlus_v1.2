<?php
namespace App\Libraries;

use App\Models\SmsModel;

class SmsService
{
    protected $smsModel;
    protected $provider;
    protected $config;
    
    public function __construct()
    {
        $this->smsModel = new SmsModel();
        $this->loadConfig();
    }
    
    /**
     * SMS 설정 로드
     */
    private function loadConfig()
    {
        // .env 파일에서 SMS 설정 로드
        $this->config = [
            'provider' => getenv('SMS_PROVIDER') ?: 'aligo', // aligo, twilio, etc.
            'api_key' => getenv('SMS_API_KEY'),
            'api_secret' => getenv('SMS_API_SECRET'),
            'sender' => getenv('SMS_SENDER') ?: '1588-0000',
            'daily_limit' => getenv('SMS_DAILY_LIMIT') ?: 5,
            'test_mode' => getenv('SMS_TEST_MODE') === 'true'
        ];
    }
    
    /**
     * 인증 SMS 발송
     */
    public function sendVerificationSMS($phoneNumber, $purpose = 'login')
    {
        try {
            // 전화번호 정규화
            $phoneNumber = $this->normalizePhoneNumber($phoneNumber);
            
            // 일일 발송 제한 확인
            try {
                if (!$this->smsModel->checkDailyLimit($phoneNumber, $this->config['daily_limit'])) {
                    return [
                        'success' => false,
                        'message' => '일일 SMS 발송 한도를 초과했습니다.'
                    ];
                }
            } catch (\Exception $e) {
                // 데이터베이스 오류 시 테이블이 없을 가능성
                log_message('error', 'SMS 일일 제한 확인 오류: ' . $e->getMessage());
                // 테이블이 없어도 계속 진행 (개발 환경을 위해)
            }
            
            // 인증 코드 생성
            $verification = $this->smsModel->createVerification([
                'phone_number' => $phoneNumber,
                'purpose' => $purpose,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
            
            // SMS 메시지 생성
            $message = $this->createVerificationMessage($verification['verification_code']);
            
            // SMS 발송
            if ($this->config['test_mode']) {
                // 테스트 모드에서는 실제 발송하지 않음
                $sendResult = [
                    'success' => true,
                    'message_id' => 'TEST_' . time(),
                    'test_code' => $verification['verification_code'] // 테스트용
                ];
            } else {
                $sendResult = $this->sendSMS($phoneNumber, $message);
            }
            
            // 발송 로그 저장
            $this->smsModel->saveSendLog([
                'phone_number' => $phoneNumber,
                'message_type' => 'verification',
                'message_content' => $message,
                'send_status' => $sendResult['success'] ? 'success' : 'failed',
                'provider' => $this->config['provider'],
                'provider_message_id' => $sendResult['message_id'] ?? null,
                'error_message' => $sendResult['error'] ?? null,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
            ]);
            
            if ($sendResult['success']) {
                return [
                    'success' => true,
                    'message' => '인증번호가 발송되었습니다.',
                    'verification_id' => $verification['verification_id'],
                    'test_code' => $this->config['test_mode'] ? $verification['verification_code'] : null
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'SMS 발송에 실패했습니다.'
                ];
            }
            
        } catch (\Exception $e) {
            log_message('error', 'SMS 발송 오류: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'SMS 발송 중 오류가 발생했습니다: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * 인증 코드 확인
     */
    public function verifyCode($phoneNumber, $code)
    {
        try {
            $phoneNumber = $this->normalizePhoneNumber($phoneNumber);
            return $this->smsModel->verifyCode($phoneNumber, $code);
        } catch (\Exception $e) {
            log_message('error', 'SMS 인증 코드 확인 오류: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * 인증 메시지 생성
     */
    private function createVerificationMessage($code)
    {
        return "[ARGOS SpoQ] 인증번호는 [{$code}]입니다. 5분 이내에 입력해주세요.";
    }
    
    /**
     * 전화번호 정규화
     */
    private function normalizePhoneNumber($phoneNumber)
    {
        // 숫자만 추출
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // 국가번호 처리
        if (substr($phoneNumber, 0, 2) === '82') {
            $phoneNumber = '0' . substr($phoneNumber, 2);
        }
        
        return $phoneNumber;
    }
    
    /**
     * SMS 발송 (Provider별 구현)
     */
    private function sendSMS($phoneNumber, $message)
    {
        switch ($this->config['provider']) {
            case 'aligo':
                return $this->sendViaAligo($phoneNumber, $message);
            case 'twilio':
                return $this->sendViaTwilio($phoneNumber, $message);
            default:
                return $this->sendViaAligo($phoneNumber, $message);
        }
    }
    
    /**
     * 알리고 SMS 발송
     */
    private function sendViaAligo($phoneNumber, $message)
    {
        $url = 'https://apis.aligo.in/send/';
        
        $data = [
            'key' => $this->config['api_key'],
            'user_id' => $this->config['api_secret'],
            'sender' => $this->config['sender'],
            'receiver' => $phoneNumber,
            'msg' => $message,
            'testmode_yn' => $this->config['test_mode'] ? 'Y' : 'N'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            $result = json_decode($response, true);
            
            if ($result && $result['result_code'] == '1') {
                return [
                    'success' => true,
                    'message_id' => $result['msg_id'] ?? null
                ];
            }
        }
        
        return [
            'success' => false,
            'error' => $response
        ];
    }
    
    /**
     * Twilio SMS 발송 (예시)
     */
    private function sendViaTwilio($phoneNumber, $message)
    {
        // Twilio 구현
        // 필요시 구현
        return [
            'success' => false,
            'error' => 'Twilio provider not implemented'
        ];
    }
}