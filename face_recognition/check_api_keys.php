<?php
/**
 * API 키 시스템 상태 확인 스크립트
 */

// 데이터베이스 설정
$host = 'localhost';
$user = 'root';
$pass = ''; // 비밀번호 입력 필요
$dbname = 'spoq_db';

try {
    // 데이터베이스 연결
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== API 키 시스템 상태 확인 ===\n\n";
    
    // 1. 테이블 존재 확인
    echo "1. 테이블 확인:\n";
    $tables = $pdo->query("SHOW TABLES LIKE '%api_key%'")->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "   ❌ API 키 테이블이 없습니다!\n";
        echo "   setup_api_keys.bat을 실행하여 테이블을 생성하세요.\n";
        exit(1);
    }
    
    foreach ($tables as $table) {
        echo "   ✅ $table\n";
    }
    
    // 2. API 키 목록
    echo "\n2. 등록된 API 키:\n";
    $stmt = $pdo->query("SELECT api_key, comp_cd, bcoff_cd, name, is_active FROM api_keys ORDER BY created_at DESC LIMIT 10");
    $keys = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($keys)) {
        echo "   등록된 API 키가 없습니다.\n";
    } else {
        foreach ($keys as $key) {
            $status = $key['is_active'] ? '✅' : '❌';
            echo sprintf("   %s [%s-%s] %s: %s\n", 
                $status, 
                $key['comp_cd'], 
                $key['bcoff_cd'], 
                $key['name'], 
                substr($key['api_key'], 0, 30) . '...'
            );
        }
    }
    
    // 3. 지점별 API 키 상태
    echo "\n3. 지점별 API 키 상태:\n";
    $stmt = $pdo->query("
        SELECT 
            b.BCOFF_CD,
            b.BCOFF_NM,
            b.api_key,
            k.api_key as registered_key
        FROM bcoff_mgmt_tbl b
        LEFT JOIN api_keys k ON b.COMP_CD = k.comp_cd AND b.BCOFF_CD = k.bcoff_cd
        WHERE b.USE_YN = 'Y'
        ORDER BY b.BCOFF_CD
    ");
    $branches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $withKey = 0;
    $withoutKey = 0;
    
    foreach ($branches as $branch) {
        if ($branch['registered_key']) {
            $withKey++;
            echo sprintf("   ✅ [%s] %s\n", $branch['BCOFF_CD'], $branch['BCOFF_NM']);
        } else {
            $withoutKey++;
            echo sprintf("   ❌ [%s] %s - API 키 없음\n", $branch['BCOFF_CD'], $branch['BCOFF_NM']);
        }
    }
    
    echo "\n   총 지점: " . count($branches) . "개\n";
    echo "   API 키 있음: $withKey개\n";
    echo "   API 키 없음: $withoutKey개\n";
    
    if ($withoutKey > 0) {
        echo "\n   💡 generate_branch_api_keys.php를 실행하여 API 키를 생성하세요.\n";
    }
    
} catch (PDOException $e) {
    echo "❌ 데이터베이스 연결 실패: " . $e->getMessage() . "\n";
    echo "\n다음을 확인하세요:\n";
    echo "1. MySQL 서버가 실행 중인가?\n";
    echo "2. 데이터베이스 이름이 맞는가? (현재: $dbname)\n";
    echo "3. 사용자명과 비밀번호가 맞는가?\n";
}