<?php
/**
 * 기존 지점들에 대한 API 키 생성 스크립트
 * 
 * 사용법:
 * 1. 프로젝트 루트에서 실행: php face_recognition/generate_branch_api_keys.php
 * 2. 또는 웹 브라우저에서 실행 (개발 환경에서만)
 */

// CodeIgniter 환경 설정
define('APPPATH', dirname(__DIR__) . '/app/');
define('SYSTEMPATH', dirname(__DIR__) . '/vendor/codeigniter4/framework/system/');
define('FCPATH', dirname(__DIR__) . '/public/');

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once APPPATH . 'Config/Database.php';

use Config\Database;
use App\Models\ApiKeyModel;

// 데이터베이스 연결
$db = Database::connect();

// API 키 모델
require_once APPPATH . 'Models/ApiKeyModel.php';
$apiKeyModel = new ApiKeyModel();

echo "=== 기존 지점 API 키 생성 스크립트 ===\n\n";

// 1. API 키가 없는 활성 지점 조회
$sql = "SELECT 
            COMP_CD,
            BCOFF_CD,
            BCOFF_NM
        FROM bcoff_mgmt_tbl
        WHERE USE_YN = 'Y'
        AND (api_key IS NULL OR api_key = '')
        ORDER BY COMP_CD, BCOFF_CD";

$query = $db->query($sql);
$branches = $query->getResultArray();

if (empty($branches)) {
    echo "API 키를 생성할 지점이 없습니다.\n";
    exit;
}

echo "API 키를 생성할 지점 수: " . count($branches) . "\n\n";

// 2. 각 지점에 대해 API 키 생성
$successCount = 0;
$failCount = 0;

foreach ($branches as $branch) {
    try {
        echo sprintf("[%s] %s - ", $branch['BCOFF_CD'], $branch['BCOFF_NM']);
        
        // API 키 생성
        $apiKey = $apiKeyModel->generateBranchApiKey(
            $branch['COMP_CD'],
            $branch['BCOFF_CD'],
            $branch['BCOFF_NM'],
            'SYSTEM_BATCH'
        );
        
        echo "성공: " . $apiKey . "\n";
        $successCount++;
        
    } catch (Exception $e) {
        echo "실패: " . $e->getMessage() . "\n";
        $failCount++;
    }
}

echo "\n=== 처리 결과 ===\n";
echo "성공: " . $successCount . "건\n";
echo "실패: " . $failCount . "건\n\n";

// 3. 생성된 API 키 목록 출력
if ($successCount > 0) {
    echo "=== 생성된 API 키 목록 ===\n";
    
    $sql = "SELECT 
                k.api_key,
                k.comp_cd,
                k.bcoff_cd,
                b.BCOFF_NM,
                k.created_at
            FROM api_keys k
            JOIN bcoff_mgmt_tbl b ON k.comp_cd = b.COMP_CD AND k.bcoff_cd = b.BCOFF_CD
            WHERE k.created_by = 'SYSTEM_BATCH'
            ORDER BY k.created_at DESC
            LIMIT 20";
    
    $query = $db->query($sql);
    $apiKeys = $query->getResultArray();
    
    foreach ($apiKeys as $key) {
        echo sprintf(
            "[%s-%s] %s\n  API Key: %s\n  생성일: %s\n\n",
            $key['comp_cd'],
            $key['bcoff_cd'],
            $key['BCOFF_NM'],
            $key['api_key'],
            $key['created_at']
        );
    }
}

// 4. 샘플 사용 예제
echo "=== API 키 사용 예제 ===\n";
echo "curl -X POST http://localhost:8080/api/v1/kiosk/face-auth \\\n";
echo "  -H \"Content-Type: application/json\" \\\n";
echo "  -H \"X-API-Key: dy-bcoff-b00001-xxxxxxxxxxxxxxxx\" \\\n";
echo "  -d '{\"image\": \"...\", \"device_id\": \"KIOSK_001\"}'\n";

echo "\n완료!\n";