<?php
// CodeIgniter 부트스트랩
require_once dirname(__DIR__) . '/app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/bootstrap.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// API 키 받기
$apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';

$response = [
    'test_info' => 'CodeIgniter API 키 검증 테스트',
    'received_api_key' => $apiKey,
    'api_key_length' => strlen($apiKey)
];

try {
    // CodeIgniter 데이터베이스 연결
    $db = \Config\Database::connect();
    
    // api_keys 테이블 존재 확인
    $tables = $db->query("SHOW TABLES LIKE 'api_keys'")->getResult();
    $response['api_keys_table_exists'] = count($tables) > 0;
    
    if ($response['api_keys_table_exists']) {
        // 받은 API 키로 검색
        if (!empty($apiKey)) {
            $query = $db->query("SELECT * FROM api_keys WHERE api_key = ? AND is_active = 1", [$apiKey]);
            $keyInfo = $query->getRow();
            
            $response['api_key_valid'] = !empty($keyInfo);
            if ($keyInfo) {
                $response['api_key_info'] = [
                    'name' => $keyInfo->name,
                    'comp_cd' => $keyInfo->comp_cd,
                    'bcoff_cd' => $keyInfo->bcoff_cd
                ];
            }
        } else {
            $response['api_key_valid'] = false;
            $response['message'] = 'API 키가 제공되지 않았습니다';
        }
        
        // 전체 API 키 개수
        $count = $db->query("SELECT COUNT(*) as total FROM api_keys")->getRow()->total;
        $response['total_api_keys_in_db'] = $count;
        
        // 테스트 키 존재 확인
        $testKeys = $db->query("SELECT api_key, name FROM api_keys WHERE api_key LIKE '%test%'")->getResult();
        $response['test_keys_found'] = [];
        foreach ($testKeys as $tk) {
            $response['test_keys_found'][] = [
                'key' => substr($tk->api_key, 0, 30) . '...',
                'name' => $tk->name
            ];
        }
    }
    
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);