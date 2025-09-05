<?php

namespace App\Models;

use CodeIgniter\Model;

class ApiKeyModel extends Model
{
    protected $table = 'api_keys';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'api_key', 'comp_cd', 'bcoff_cd', 'name', 'description', 
        'is_active', 'last_used_at', 'usage_count', 'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    /**
     * 지점용 API 키 생성
     * 
     * @param string $compCd 회사 코드
     * @param string $bcoffCd 지점 코드
     * @param string $bcoffNm 지점명
     * @param string $createdBy 생성자
     * @return string 생성된 API 키
     */
    public function generateBranchApiKey($compCd, $bcoffCd, $bcoffNm, $createdBy = null)
    {
        // 기존 API 키가 있는지 확인
        $existing = $this->where('comp_cd', $compCd)
                         ->where('bcoff_cd', $bcoffCd)
                         ->first();
        
        if ($existing) {
            return $existing['api_key'];
        }
        
        // API 키 생성
        $apiKey = $this->createSecureApiKey($bcoffCd);
        
        // DB에 저장
        $data = [
            'api_key' => $apiKey,
            'comp_cd' => $compCd,
            'bcoff_cd' => $bcoffCd,
            'name' => $bcoffNm . ' API 키',
            'description' => $bcoffNm . ' 키오스크 시스템용 API 키',
            'created_by' => $createdBy ?? 'SYSTEM'
        ];
        
        $this->insert($data);
        
        // 지점 테이블에도 업데이트
        $db = \Config\Database::connect();
        $db->table('bcoff_mgmt_tbl')
           ->where('COMP_CD', $compCd)
           ->where('BCOFF_CD', $bcoffCd)
           ->update(['api_key' => $apiKey]);
        
        return $apiKey;
    }
    
    /**
     * 안전한 API 키 생성
     * 
     * @param string $bcoffCd 지점 코드
     * @return string
     */
    private function createSecureApiKey($bcoffCd)
    {
        // 형식: dy-bcoff-{지점코드소문자}-{랜덤해시}
        // 예: dy-bcoff-b00001-a1b2c3d4e5f6...
        
        $prefix = 'dy-bcoff-' . strtolower($bcoffCd);
        $random = bin2hex(random_bytes(16)); // 32자리 랜덤 문자열
        $timestamp = dechex(time()); // 시간 정보 포함
        
        return $prefix . '-' . $timestamp . $random;
    }
    
    /**
     * API 키 검증
     * 
     * @param string $apiKey
     * @return array|null 유효한 경우 API 키 정보, 무효한 경우 null
     */
    public function validateApiKey($apiKey)
    {
        $keyInfo = $this->where('api_key', $apiKey)
                        ->where('is_active', 1)
                        ->first();
        
        if (!$keyInfo) {
            return null;
        }
        
        // 사용 기록 업데이트 (비동기 처리 권장)
        $this->update($keyInfo['id'], [
            'last_used_at' => date('Y-m-d H:i:s'),
            'usage_count' => $keyInfo['usage_count'] + 1
        ]);
        
        return $keyInfo;
    }
    
    /**
     * API 키 사용 로그 기록
     * 
     * @param int $apiKeyId
     * @param array $logData
     */
    public function logApiKeyUsage($apiKeyId, $logData)
    {
        $db = \Config\Database::connect();
        $db->table('api_key_logs')->insert([
            'api_key_id' => $apiKeyId,
            'endpoint' => $logData['endpoint'] ?? '',
            'ip_address' => $logData['ip_address'] ?? '',
            'user_agent' => $logData['user_agent'] ?? '',
            'response_code' => $logData['response_code'] ?? null,
            'response_time_ms' => $logData['response_time_ms'] ?? null
        ]);
    }
    
    /**
     * 지점별 API 키 목록 조회
     * 
     * @param string $compCd
     * @return array
     */
    public function getBranchApiKeys($compCd)
    {
        return $this->select('api_keys.*, b.BCOFF_NM')
                    ->join('bcoff_mgmt_tbl b', 'api_keys.bcoff_cd = b.BCOFF_CD AND api_keys.comp_cd = b.COMP_CD')
                    ->where('api_keys.comp_cd', $compCd)
                    ->orderBy('api_keys.created_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * API 키 재생성
     * 
     * @param string $compCd
     * @param string $bcoffCd
     * @param string $updatedBy
     * @return string 새로운 API 키
     */
    public function regenerateApiKey($compCd, $bcoffCd, $updatedBy = null)
    {
        $existing = $this->where('comp_cd', $compCd)
                         ->where('bcoff_cd', $bcoffCd)
                         ->first();
        
        if (!$existing) {
            throw new \Exception('API 키를 찾을 수 없습니다.');
        }
        
        // 새 API 키 생성
        $newApiKey = $this->createSecureApiKey($bcoffCd);
        
        // 업데이트
        $this->update($existing['id'], [
            'api_key' => $newApiKey,
            'created_by' => $updatedBy ?? 'SYSTEM'
        ]);
        
        // 지점 테이블에도 업데이트
        $db = \Config\Database::connect();
        $db->table('bcoff_mgmt_tbl')
           ->where('COMP_CD', $compCd)
           ->where('BCOFF_CD', $bcoffCd)
           ->update(['api_key' => $newApiKey]);
        
        return $newApiKey;
    }
    
    /**
     * API 키 활성화/비활성화
     * 
     * @param int $id
     * @param bool $isActive
     */
    public function toggleApiKey($id, $isActive)
    {
        $this->update($id, ['is_active' => $isActive ? 1 : 0]);
    }
}