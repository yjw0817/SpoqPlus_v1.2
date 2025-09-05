<?php
/**
 * 지점 확인 및 API 키 SQL 생성 (실행 없이 SQL만 출력)
 */

// 일반적인 지점 코드 예시 (실제 데이터가 없을 경우 사용)
$sampleBranches = [
    ['COMP_CD' => 'C00001', 'BCOFF_CD' => 'B00001', 'BCOFF_NM' => '강남점'],
    ['COMP_CD' => 'C00001', 'BCOFF_CD' => 'B00002', 'BCOFF_NM' => '서초점'],
    ['COMP_CD' => 'C00001', 'BCOFF_CD' => 'B00003', 'BCOFF_NM' => '판교점'],
    ['COMP_CD' => 'C00001', 'BCOFF_CD' => 'B00004', 'BCOFF_NM' => '분당점'],
    ['COMP_CD' => 'C00001', 'BCOFF_CD' => 'B00005', 'BCOFF_NM' => '수원점'],
];

echo "-- 실제 지점 기반 API 키 생성 SQL\n";
echo "-- 생성일: " . date('Y-m-d H:i:s') . "\n\n";

echo "-- 먼저 현재 등록된 지점 확인\n";
echo "SELECT COMP_CD, BCOFF_CD, BCOFF_NM FROM bcoff_mgmt_tbl WHERE USE_YN = 'Y' ORDER BY COMP_CD, BCOFF_CD;\n\n";

echo "-- API 키 테이블이 없다면 생성\n";
echo "CREATE TABLE IF NOT EXISTS api_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    api_key VARCHAR(64) UNIQUE NOT NULL COMMENT 'API 키',
    comp_cd VARCHAR(10) NOT NULL COMMENT '회사 코드',
    bcoff_cd VARCHAR(10) NOT NULL COMMENT '지점 코드',
    name VARCHAR(100) NOT NULL COMMENT 'API 키 이름',
    description TEXT COMMENT '설명',
    is_active BOOLEAN DEFAULT TRUE COMMENT '활성화 여부',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
    last_used_at TIMESTAMP NULL COMMENT '마지막 사용일시',
    usage_count INT DEFAULT 0 COMMENT '사용 횟수',
    created_by VARCHAR(50) COMMENT '생성자',
    INDEX idx_api_key (api_key),
    INDEX idx_comp_bcoff (comp_cd, bcoff_cd),
    UNIQUE KEY uk_comp_bcoff (comp_cd, bcoff_cd) COMMENT '회사-지점별 유니크'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='API 키 관리';\n\n";

echo "-- bcoff_mgmt_tbl에 api_key 컬럼 추가\n";
echo "ALTER TABLE bcoff_mgmt_tbl ADD COLUMN IF NOT EXISTS api_key VARCHAR(64) COMMENT 'API 키' AFTER BCOFF_NM;\n\n";

echo "-- 실제 지점들의 API 키 생성\n";
echo "-- 아래 쿼리로 현재 지점을 확인한 후, 해당 지점에 맞게 수정하세요\n\n";

// 샘플 지점들에 대한 API 키 생성
foreach ($sampleBranches as $branch) {
    $timestamp = dechex(time());
    $random = bin2hex(random_bytes(16));
    $apiKey = 'dy-bcoff-' . strtolower($branch['BCOFF_CD']) . '-' . $timestamp . $random;
    
    echo "-- {$branch['BCOFF_NM']} ({$branch['BCOFF_CD']})\n";
    echo "INSERT INTO api_keys (api_key, comp_cd, bcoff_cd, name, description, created_by)\n";
    echo "VALUES ('{$apiKey}', '{$branch['COMP_CD']}', '{$branch['BCOFF_CD']}', '{$branch['BCOFF_NM']} API 키', '{$branch['BCOFF_NM']} 키오스크 시스템용', 'SYSTEM')\n";
    echo "ON DUPLICATE KEY UPDATE api_key = VALUES(api_key), updated_at = NOW();\n";
    echo "UPDATE bcoff_mgmt_tbl SET api_key = '{$apiKey}' WHERE COMP_CD = '{$branch['COMP_CD']}' AND BCOFF_CD = '{$branch['BCOFF_CD']}';\n\n";
}

// 테스트용 키
$testKey = 'test-api-key-dy-' . bin2hex(random_bytes(12));
echo "-- 개발/테스트용 API 키\n";
echo "INSERT INTO api_keys (api_key, comp_cd, bcoff_cd, name, description, created_by)\n";
echo "VALUES ('{$testKey}', 'C00001', 'B00001', '개발 테스트 API 키', '로컬 개발 및 테스트용', 'SYSTEM')\n";
echo "ON DUPLICATE KEY UPDATE api_key = VALUES(api_key), updated_at = NOW();\n\n";

echo "-- 생성된 API 키 확인\n";
echo "SELECT k.api_key, k.comp_cd, k.bcoff_cd, b.BCOFF_NM, k.name, k.created_at\n";
echo "FROM api_keys k\n";
echo "LEFT JOIN bcoff_mgmt_tbl b ON k.comp_cd = b.COMP_CD AND k.bcoff_cd = b.BCOFF_CD\n";
echo "ORDER BY k.comp_cd, k.bcoff_cd;\n";

// 생성된 키 목록 출력
echo "\n\n-- 생성된 API 키 목록 (참고용)\n";
echo "/*\n";
foreach ($sampleBranches as $i => $branch) {
    echo "{$branch['BCOFF_NM']}: 위에서 생성된 API 키 참조\n";
}
echo "테스트용: {$testKey}\n";
echo "*/\n";
?>