<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

/**
 * API 키 검증 디버깅용 컨트롤러
 */
class KioskAuthApiDebug extends ResourceController
{
    use ResponseTrait;
    
    public function __construct()
    {
        helper(['url', 'form']);
        
        // CORS 설정
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, X-API-Key, Authorization');
    }
    
    /**
     * API 키 검증 테스트
     */
    public function testApiKey()
    {
        $apiKey = $this->request->getHeaderLine('X-API-Key');
        
        // 디버깅 정보
        $debug = [
            'received_api_key' => $apiKey,
            'api_key_length' => strlen($apiKey),
            'headers' => $this->request->getHeaders(),
            'method' => $this->request->getMethod()
        ];
        
        // API 키 검증
        $apiKeyModel = new \App\Models\ApiKeyModel();
        $keyInfo = null;
        
        if (!empty($apiKey)) {
            $keyInfo = $apiKeyModel->validateApiKey($apiKey);
        }
        
        // 데이터베이스에서 모든 API 키 조회 (디버깅용)
        $db = \Config\Database::connect();
        $allKeys = $db->query("SELECT api_key, name, is_active FROM api_keys")->getResultArray();
        
        return $this->respond([
            'api_key_provided' => !empty($apiKey),
            'api_key_valid' => !empty($keyInfo),
            'api_key_info' => $keyInfo,
            'debug' => $debug,
            'registered_keys_count' => count($allKeys),
            'registered_keys' => array_map(function($k) {
                return [
                    'name' => $k['name'],
                    'is_active' => $k['is_active'],
                    'key_preview' => substr($k['api_key'], 0, 20) . '...'
                ];
            }, $allKeys)
        ]);
    }
}