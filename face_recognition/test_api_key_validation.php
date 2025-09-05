<?php
/**
 * API 키 검증 테스트 스크립트
 */

// CodeIgniter 환경 설정
define('APPPATH', dirname(__DIR__) . '/app/');
define('SYSTEMPATH', dirname(__DIR__) . '/vendor/codeigniter4/framework/system/');

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once APPPATH . 'Config/Database.php';

use Config\Database;

try {
    // 데이터베이스 연결
    $db = Database::connect();
    
    echo "=== API 키 검증 테스트 ===\n\n";
    
    // 1. API 키 테이블 존재 확인
    echo "1. 테이블 확인:\n";
    $tables = $db->query("SHOW TABLES LIKE 'api_keys'")->getResult();
    
    if (empty($tables)) {
        echo "   ❌ api_keys 테이블이 존재하지 않습니다!\n";
        echo "   SQL 스크립트를 실행하여 테이블을 생성하세요.\n";
        exit(1);
    } else {
        echo "   ✅ api_keys 테이블 존재\n";
    }
    
    // 2. 등록된 API 키 확인
    echo "\n2. 등록된 API 키:\n";
    $query = $db->query("SELECT api_key, comp_cd, bcoff_cd, name, is_active FROM api_keys");
    $keys = $query->getResultArray();
    
    if (empty($keys)) {
        echo "   ❌ 등록된 API 키가 없습니다!\n";
        echo "   SQL 스크립트를 실행하여 API 키를 생성하세요.\n";
    } else {
        foreach ($keys as $key) {
            $status = $key['is_active'] ? '✅ 활성' : '❌ 비활성';
            echo sprintf("   %s [%s-%s] %s\n     => %s\n\n", 
                $status,
                $key['comp_cd'],
                $key['bcoff_cd'],
                $key['name'],
                $key['api_key']
            );
        }
    }
    
    // 3. API 키 검증 테스트
    echo "\n3. API 키 검증 테스트:\n";
    
    // ApiKeyModel 로드
    require_once APPPATH . 'Models/ApiKeyModel.php';
    $apiKeyModel = new \App\Models\ApiKeyModel();
    
    // 테스트할 API 키들
    $testKeys = [
        'test-api-key-dy-cf965b66227831234b86e45f' => '등록된 테스트 키',
        'invalid-key-12345' => '잘못된 키',
        'dy-bcoff-b00001-686e3557918a83c03cf50a707874dbe19afdc1a7' => '강남점 키'
    ];
    
    foreach ($testKeys as $testKey => $description) {
        echo "   테스트: $description\n";
        echo "   키: " . substr($testKey, 0, 30) . "...\n";
        
        $result = $apiKeyModel->validateApiKey($testKey);
        
        if ($result) {
            echo "   ✅ 검증 성공 - " . $result['name'] . "\n\n";
        } else {
            echo "   ❌ 검증 실패\n\n";
        }
    }
    
    // 4. KioskAuthApi의 validateApiKey 메서드 테스트
    echo "\n4. KioskAuthApi 검증 로직 확인:\n";
    
    // 실제 API 호출 시뮬레이션
    $testApiKey = 'invalid-test-key-999';
    echo "   잘못된 키로 테스트: $testApiKey\n";
    
    $keyInfo = $apiKeyModel->where('api_key', $testApiKey)
                           ->where('is_active', 1)
                           ->first();
    
    if ($keyInfo) {
        echo "   ❌ 문제 발견! 존재하지 않는 키가 통과됨\n";
        var_dump($keyInfo);
    } else {
        echo "   ✅ 정상 - 잘못된 키가 거부됨\n";
    }
    
} catch (Exception $e) {
    echo "❌ 오류 발생: " . $e->getMessage() . "\n";
    echo "스택 트레이스:\n" . $e->getTraceAsString() . "\n";
}