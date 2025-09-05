<?php
// 간단한 API 키 검증 테스트

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// API 키 받기
$apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';

$response = [
    'test_info' => 'API 키 검증 테스트',
    'received_api_key' => $apiKey,
    'headers' => getallheaders(),
    'server_info' => [
        'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'],
        'REQUEST_URI' => $_SERVER['REQUEST_URI']
    ]
];

// 데이터베이스 연결 테스트
try {
    $pdo = new PDO('mysql:host=localhost;dbname=spoq_db;charset=utf8mb4', 'root', '');
    
    // API 키 테이블 확인
    $stmt = $pdo->query("SHOW TABLES LIKE 'api_keys'");
    $response['api_keys_table_exists'] = $stmt->rowCount() > 0;
    
    if ($response['api_keys_table_exists']) {
        // API 키 확인
        $stmt = $pdo->prepare("SELECT * FROM api_keys WHERE api_key = ? AND is_active = 1");
        $stmt->execute([$apiKey]);
        $keyInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $response['api_key_found'] = !empty($keyInfo);
        $response['api_key_info'] = $keyInfo ? [
            'name' => $keyInfo['name'],
            'comp_cd' => $keyInfo['comp_cd'],
            'bcoff_cd' => $keyInfo['bcoff_cd']
        ] : null;
        
        // 등록된 모든 API 키 개수
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM api_keys");
        $response['total_api_keys'] = $stmt->fetch()['count'];
    }
    
} catch (Exception $e) {
    $response['db_error'] = $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>