<?php
namespace App\Models;

use CodeIgniter\Model;

class SmsModel extends Model
{
    protected $db;
    
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    
    /**
     * SMS 인증 코드 생성 및 저장
     */
    public function createVerification($data)
    {
        // 6자리 랜덤 인증 코드 생성
        $verificationCode = sprintf('%06d', mt_rand(100000, 999999));
        
        // 만료 시간 설정 (5분)
        $expiredAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        
        $insertData = [
            'phone_number' => $data['phone_number'],
            'verification_code' => $verificationCode,
            'purpose' => $data['purpose'] ?? 'login',
            'expired_at' => $expiredAt,
            'ip_address' => $data['ip_address'] ?? null,
            'user_agent' => $data['user_agent'] ?? null
        ];
        
        $this->db->table('sms_verification')->insert($insertData);
        
        return [
            'verification_id' => $this->db->insertID(),
            'verification_code' => $verificationCode,
            'expired_at' => $expiredAt
        ];
    }
    
    /**
     * 인증 코드 확인
     */
    public function verifyCode($phoneNumber, $code)
    {
        $query = $this->db->table('sms_verification')
            ->where('phone_number', $phoneNumber)
            ->where('verification_code', $code)
            ->where('is_verified', 0)
            ->where('expired_at >', date('Y-m-d H:i:s'))
            ->orderBy('created_at', 'DESC')
            ->limit(1);
            
        $result = $query->get()->getRowArray();
        
        if ($result) {
            // 인증 성공 - 상태 업데이트
            $this->db->table('sms_verification')
                ->where('verification_id', $result['verification_id'])
                ->update([
                    'is_verified' => 1,
                    'verified_at' => date('Y-m-d H:i:s')
                ]);
                
            return true;
        }
        
        // 실패 시 시도 횟수 증가
        if ($result) {
            $this->db->table('sms_verification')
                ->where('verification_id', $result['verification_id'])
                ->set('attempt_count', 'attempt_count + 1', false)
                ->update();
        }
        
        return false;
    }
    
    /**
     * 최근 인증 요청 확인 (중복 방지)
     */
    public function getRecentVerification($phoneNumber, $minutes = 1)
    {
        $timeLimit = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));
        
        return $this->db->table('sms_verification')
            ->where('phone_number', $phoneNumber)
            ->where('created_at >', $timeLimit)
            ->countAllResults();
    }
    
    
    /**
     * SMS 발송 로그 저장
     */
    public function saveSendLog($data)
    {
        $insertData = [
            'phone_number' => $data['phone_number'],
            'message_type' => $data['message_type'] ?? 'verification',
            'message_content' => $data['message_content'],
            'send_status' => $data['send_status'] ?? 'pending',
            'provider' => $data['provider'] ?? null,
            'provider_message_id' => $data['provider_message_id'] ?? null,
            'error_message' => $data['error_message'] ?? null,
            'cost' => $data['cost'] ?? 0,
            'ip_address' => $data['ip_address'] ?? null
        ];
        
        $this->db->table('sms_send_logs')->insert($insertData);
        
        return $this->db->insertID();
    }
    
    /**
     * 일일 발송 제한 확인
     */
    public function checkDailyLimit($phoneNumber, $maxLimit = 5)
    {
        $today = date('Y-m-d');
        
        $result = $this->db->table('sms_daily_limits')
            ->where('phone_number', $phoneNumber)
            ->where('limit_date', $today)
            ->get()
            ->getRowArray();
            
        if (!$result) {
            // 첫 발송
            $this->db->table('sms_daily_limits')->insert([
                'phone_number' => $phoneNumber,
                'limit_date' => $today,
                'send_count' => 1,
                'last_sent_at' => date('Y-m-d H:i:s')
            ]);
            return true;
        }
        
        if ($result['send_count'] >= $maxLimit) {
            return false;
        }
        
        // 발송 횟수 증가
        $this->db->table('sms_daily_limits')
            ->where('limit_id', $result['limit_id'])
            ->update([
                'send_count' => $result['send_count'] + 1,
                'last_sent_at' => date('Y-m-d H:i:s')
            ]);
            
        return true;
    }
    
    /**
     * 만료된 인증 코드 정리
     */
    public function cleanExpiredVerifications()
    {
        return $this->db->table('sms_verification')
            ->where('expired_at <', date('Y-m-d H:i:s'))
            ->where('is_verified', 0)
            ->delete();
    }
    
    /**
     * 자동 로그인 토큰 저장
     */
    public function saveAuthToken($mem_id, $token, $phone_number, $device_info = null)
    {
        // 기존 토큰 삭제
        $this->db->table('sms_auth_tokens')
            ->where('member_id', $mem_id)
            ->where('phone_number', $phone_number)
            ->delete();
        
        // 새 토큰 저장
        $data = [
            'member_id' => $mem_id,
            'auth_token' => $token,
            'phone_number' => $phone_number,
            'expired_at' => date('Y-m-d H:i:s', strtotime('+1 year')), // 1년 후 만료
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->table('sms_auth_tokens')->insert($data);
    }
    
    /**
     * 자동 로그인 토큰 검증
     */
    public function verifyAuthToken($token)
    {
        $result = $this->db->table('sms_auth_tokens')
            ->where('auth_token', $token)
            ->where('expired_at >', date('Y-m-d H:i:s'))
            ->get()
            ->getRowArray();
        
        if ($result) {
            // 마지막 사용 시간 업데이트
            $this->db->table('sms_auth_tokens')
                ->where('token_id', $result['token_id'])
                ->update(['last_used_at' => date('Y-m-d H:i:s')]);
            
            // member_id를 mem_id로 변환
            $result['mem_id'] = $result['member_id'];
            
            return $result;
        }
        
        return false;
    }
    
    /**
     * 자동 로그인 토큰 삭제 (실제로는 비활성화만 함)
     */
    public function deleteAuthToken($mem_id)
    {
        // 로그아웃 시에도 토큰은 유지하고 비활성화만 하지 않음
        // 사용자가 다시 로그인하면 재활성화
        return true;
    }
    
    /**
     * 만료된 토큰 정리
     */
    public function cleanExpiredTokens()
    {
        return $this->db->table('sms_auth_tokens')
            ->where('expired_at <', date('Y-m-d H:i:s'))
            ->delete();
    }
    
}