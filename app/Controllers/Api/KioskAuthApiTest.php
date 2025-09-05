<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

/**
 * 키오스크 API 테스트 전용 컨트롤러
 * 로그인 체크 없이 간단한 응답만 제공
 */
class KioskAuthApiTest extends ResourceController
{
    use ResponseTrait;
    
    protected $format = 'json';
    
    /**
     * API 상태 확인 - 테스트용
     */
    public function status()
    {
        // CORS 헤더 설정
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, X-API-Key, Authorization');
        
        return $this->respond([
            'success' => true,
            'service' => 'Kiosk Face Authentication API (Test)',
            'version' => '1.0.0',
            'status' => 'operational',
            'components' => [
                'api_server' => 'healthy',
                'face_recognition_server' => $this->checkFaceServer() ? 'healthy' : 'unhealthy',
                'database' => 'test_mode'
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * 얼굴 인증 API - 테스트용
     */
    public function faceAuth()
    {
        // CORS 헤더 설정
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, X-API-Key, Authorization');
        
        // OPTIONS 요청 처리
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            return $this->respond(['status' => 'ok'], 200);
        }
        
        $data = $this->request->getJSON(true);
        $language = $data['language'] ?? 'ko';
        
        // 테스트용 더미 응답
        return $this->respond([
            'success' => true,
            'error_code' => null,
            'message' => $this->getMessage('auth_success', $language, ['name' => '테스트회원']),
            'data' => [
                'member' => [
                    'mem_sno' => 'TEST001',
                    'mem_nm' => '테스트회원',
                    'mem_telno_mask' => '010-****-1234'
                ],
                'tickets' => [
                    [
                        'ticket_id' => 'T001',
                        'ticket_name' => '헬스 월 이용권',
                        'ticket_type' => 'period',
                        'status' => 'active',
                        'is_available' => true,
                        'remaining_info' => '잔여 30일',
                        'expire_date' => '2024-02-28',
                        'restrictions' => null,
                        'gx_info' => null
                    ],
                    [
                        'ticket_id' => 'T002',
                        'ticket_name' => 'PT 10회권',
                        'ticket_type' => 'count',
                        'status' => 'active',
                        'is_available' => true,
                        'remaining_info' => '잔여 8회',
                        'expire_date' => '2024-03-31',
                        'restrictions' => null,
                        'gx_info' => null
                    ]
                ],
                'ticket_count' => 2,
                'similarity_score' => 0.95
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * 체크인 처리 API - 테스트용
     */
    public function checkin()
    {
        // CORS 헤더 설정
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, X-API-Key, Authorization');
        
        $data = $this->request->getJSON(true);
        $language = $data['language'] ?? 'ko';
        
        return $this->respond([
            'success' => true,
            'error_code' => null,
            'message' => $this->getMessage('checkin_success', $language, ['ticket_name' => '헬스 월 이용권']),
            'data' => [
                'checkin_id' => 'CHK' . date('YmdHis'),
                'ticket_name' => '헬스 월 이용권',
                'remaining_count' => null,
                'expire_date' => '2024-02-28'
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Face 서버 체크
     */
    private function checkFaceServer(): bool
    {
        $ch = curl_init('http://localhost:5002/api/face/health');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode === 200;
    }
    
    /**
     * 다국어 메시지
     */
    private function getMessage(string $key, string $language, array $params = []): string
    {
        $messages = [
            'ko' => [
                'auth_success' => '{name}님 안녕하세요!',
                'checkin_success' => '{ticket_name} 이용권으로 입장이 완료되었습니다.'
            ],
            'en' => [
                'auth_success' => 'Welcome, {name}!',
                'checkin_success' => 'Check-in completed with {ticket_name}.'
            ]
        ];
        
        $message = $messages[$language][$key] ?? $messages['ko'][$key] ?? $key;
        
        foreach ($params as $param => $value) {
            $message = str_replace('{' . $param . '}', $value, $message);
        }
        
        return $message;
    }
}