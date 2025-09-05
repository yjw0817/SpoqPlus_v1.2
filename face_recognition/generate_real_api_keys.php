<?php
/**
 * 실제 지점 데이터 기반 API 키 생성 스크립트
 * 
 * 사용법: php generate_real_api_keys.php
 */

// 데이터베이스 설정
$host = 'localhost';
$user = 'root';
$pass = ''; // MySQL 비밀번호 입력 필요
$dbname = 'spoq_db';

try {
    // 데이터베이스 연결
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== 실제 지점 API 키 생성 스크립트 ===\n\n";
    
    // 1. 현재 등록된 활성 지점 조회
    echo "1. 활성 지점 조회 중...\n";
    $stmt = $pdo->query("
        SELECT COMP_CD, BCOFF_CD, BCOFF_NM 
        FROM bcoff_mgmt_tbl 
        WHERE USE_YN = 'Y' 
        ORDER BY COMP_CD, BCOFF_CD
    ");
    $branches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($branches)) {
        echo "등록된 활성 지점이 없습니다.\n";
        exit;
    }
    
    echo "발견된 지점 수: " . count($branches) . "개\n\n";
    
    // 2. SQL 생성
    $sqlStatements = [];
    $sqlStatements[] = "-- 실제 지점 기반 API 키 생성 SQL";
    $sqlStatements[] = "-- 생성일: " . date('Y-m-d H:i:s');
    $sqlStatements[] = "";
    
    foreach ($branches as $branch) {
        // API 키 생성 (형식: dy-bcoff-{지점코드소문자}-{타임스탬프}{랜덤})
        $apiKey = 'dy-bcoff-' . strtolower($branch['BCOFF_CD']) . '-' . dechex(time()) . bin2hex(random_bytes(16));
        
        echo sprintf("[%s] %s - %s\n", 
            $branch['BCOFF_CD'], 
            $branch['BCOFF_NM'],
            $apiKey
        );
        
        // INSERT SQL 생성
        $sqlStatements[] = sprintf(
            "-- %s (%s)",
            $branch['BCOFF_NM'],
            $branch['BCOFF_CD']
        );
        
        $sqlStatements[] = sprintf(
            "INSERT INTO api_keys (api_key, comp_cd, bcoff_cd, name, description, created_by) 
VALUES ('%s', '%s', '%s', '%s', '%s 키오스크 시스템용', 'SYSTEM')
ON DUPLICATE KEY UPDATE api_key = VALUES(api_key), updated_at = NOW();",
            $apiKey,
            $branch['COMP_CD'],
            $branch['BCOFF_CD'],
            $branch['BCOFF_NM'] . ' API 키',
            $branch['BCOFF_NM']
        );
        
        // UPDATE SQL for bcoff_mgmt_tbl
        $sqlStatements[] = sprintf(
            "UPDATE bcoff_mgmt_tbl SET api_key = '%s' WHERE COMP_CD = '%s' AND BCOFF_CD = '%s';",
            $apiKey,
            $branch['COMP_CD'],
            $branch['BCOFF_CD']
        );
        
        $sqlStatements[] = "";
    }
    
    // 테스트용 키 추가
    $testKey = 'test-api-key-dy-' . bin2hex(random_bytes(12));
    $sqlStatements[] = "-- 개발/테스트용 API 키";
    $sqlStatements[] = sprintf(
        "INSERT INTO api_keys (api_key, comp_cd, bcoff_cd, name, description, created_by) 
VALUES ('%s', '%s', '%s', '개발 테스트 API 키', '로컬 개발 및 테스트용', 'SYSTEM')
ON DUPLICATE KEY UPDATE api_key = VALUES(api_key), updated_at = NOW();",
        $testKey,
        $branches[0]['COMP_CD'], // 첫 번째 회사 코드 사용
        $branches[0]['BCOFF_CD']  // 첫 번째 지점 코드 사용
    );
    
    // 확인 쿼리 추가
    $sqlStatements[] = "";
    $sqlStatements[] = "-- 생성된 API 키 확인";
    $sqlStatements[] = "SELECT 
    k.api_key,
    k.comp_cd,
    k.bcoff_cd,
    b.BCOFF_NM,
    k.name,
    k.created_at
FROM api_keys k
JOIN bcoff_mgmt_tbl b ON k.comp_cd = b.COMP_CD AND k.bcoff_cd = b.BCOFF_CD
ORDER BY k.comp_cd, k.bcoff_cd;";
    
    // 3. SQL 파일 저장
    $sqlContent = implode("\n", $sqlStatements);
    $filename = 'sql/insert_real_api_keys_' . date('YmdHis') . '.sql';
    file_put_contents($filename, $sqlContent);
    
    echo "\n=== SQL 파일 생성 완료 ===\n";
    echo "파일명: $filename\n";
    echo "\n실행 방법:\n";
    echo "mysql -u root -p $dbname < $filename\n";
    
    // 4. 즉시 실행 옵션
    echo "\n바로 실행하시겠습니까? (y/n): ";
    $handle = fopen ("php://stdin","r");
    $line = fgets($handle);
    
    if(trim($line) == 'y' || trim($line) == 'Y'){
        echo "\n실행 중...\n";
        
        // SQL 실행
        $pdo->exec($sqlContent);
        
        echo "✅ API 키가 성공적으로 생성되었습니다!\n\n";
        
        // 결과 확인
        $stmt = $pdo->query("
            SELECT 
                k.api_key,
                k.comp_cd,
                k.bcoff_cd,
                b.BCOFF_NM
            FROM api_keys k
            JOIN bcoff_mgmt_tbl b ON k.comp_cd = b.COMP_CD AND k.bcoff_cd = b.BCOFF_CD
            ORDER BY k.created_at DESC
            LIMIT 10
        ");
        
        echo "=== 생성된 API 키 목록 ===\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo sprintf("[%s-%s] %s\n  => %s\n\n", 
                $row['comp_cd'],
                $row['bcoff_cd'],
                $row['BCOFF_NM'],
                $row['api_key']
            );
        }
        
        echo "\n테스트용 API 키: $testKey\n";
    }
    
    fclose($handle);
    
} catch (PDOException $e) {
    echo "❌ 오류 발생: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n완료!\n";
?>