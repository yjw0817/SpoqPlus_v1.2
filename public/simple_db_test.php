<?php
$host = '192.168.0.48';
$username = 'root';
$password = 'spoqdb11';
$database = 'spoqplusdb';

try {
    echo "=== 얼굴 인식 로그 저장 테스트 ===\n\n";
    
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ 데이터베이스 연결 성공\n";
    
    // 테이블 구조 확인
    echo "\n=== face_recognition_logs 테이블 구조 ===\n";
    $stmt = $pdo->query("DESCRIBE face_recognition_logs");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']} | {$row['Type']} | {$row['Null']} | {$row['Default']}\n";
    }
    
    // 테스트 데이터 삽입
    echo "\n=== 테스트 데이터 삽입 ===\n";
    
    $testData = [
        'mem_sno' => 'MM202505290000000002',
        'confidence_score' => 0.95,
        'processing_time_ms' => 150,
        'glasses_detected' => 1,
        'match_category' => 'match_found',
        'security_checks_passed' => json_encode([
            'basic_security' => true,
            'liveness_check' => true
        ]),
        'success' => 1,
        'error_message' => null,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test Agent',
        'session_id' => 'test_session',
        'similarity_score' => 0.93,
        'quality_score' => 0.88
    ];
    
    $sql = "INSERT INTO face_recognition_logs 
            (mem_sno, confidence_score, processing_time_ms, glasses_detected, 
             match_category, security_checks_passed, success, error_message, 
             ip_address, user_agent, session_id, similarity_score, quality_score) 
            VALUES 
            (:mem_sno, :confidence_score, :processing_time_ms, :glasses_detected, 
             :match_category, :security_checks_passed, :success, :error_message, 
             :ip_address, :user_agent, :session_id, :similarity_score, :quality_score)";
    
    $stmt = $pdo->prepare($sql);
    
    echo "SQL: $sql\n";
    echo "데이터: " . json_encode($testData) . "\n";
    
    $result = $stmt->execute($testData);
    
    if ($result) {
        echo "✅ 데이터 삽입 성공! ID: " . $pdo->lastInsertId() . "\n";
    } else {
        echo "❌ 데이터 삽입 실패\n";
    }
    
    // 최근 로그 확인
    echo "\n=== 최근 로그 3개 ===\n";
    $stmt = $pdo->query("SELECT id, mem_sno, success, match_category, created_at FROM face_recognition_logs ORDER BY created_at DESC LIMIT 3");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['id']}, 회원: {$row['mem_sno']}, 성공: {$row['success']}, 카테고리: {$row['match_category']}, 시간: {$row['created_at']}\n";
    }
    
} catch (PDOException $e) {
    echo "❌ 데이터베이스 오류: " . $e->getMessage() . "\n";
    echo "오류 코드: " . $e->getCode() . "\n";
} catch (Exception $e) {
    echo "❌ 일반 오류: " . $e->getMessage() . "\n";
}
?> 