<?php
$host = '192.168.0.48';
$username = 'root';
$password = 'spoqdb11';
$database = 'spoqplusdb';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    
    echo "=== 데이터베이스 로그 확인 ===\n\n";
    
    // 1. face_recognition_logs 테이블 확인
    echo "1. face_recognition_logs 테이블:\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM face_recognition_logs");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "- 총 로그 수: {$result['count']}\n";
    
    if ($result['count'] > 0) {
        // 최근 로그 5개 확인
        echo "\n- 최근 로그 5개:\n";
        $stmt = $pdo->query("SELECT log_id, mem_sno, recognition_time, success, confidence_score, quality_score 
                            FROM face_recognition_logs 
                            ORDER BY recognition_time DESC 
                            LIMIT 5");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  ID: {$row['log_id']}, 회원: {$row['mem_sno']}, 시간: {$row['recognition_time']}, 성공: {$row['success']}, 신뢰도: {$row['confidence_score']}, 품질: {$row['quality_score']}\n";
        }
    }
    
    // 2. member_faces 테이블 확인
    echo "\n2. member_faces 테이블:\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM member_faces");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "- 총 등록 얼굴: {$result['count']}\n";
    
    if ($result['count'] > 0) {
        // 최근 등록 5개 확인
        echo "\n- 최근 등록 5개:\n";
        $stmt = $pdo->query("SELECT face_id, mem_sno, registered_date, quality_score, glasses_detected, is_active 
                            FROM member_faces 
                            ORDER BY registered_date DESC 
                            LIMIT 5");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $glasses = $row['glasses_detected'] ? '안경' : '일반';
            $active = $row['is_active'] ? '활성' : '비활성';
            echo "  ID: {$row['face_id']}, 회원: {$row['mem_sno']}, 등록일: {$row['registered_date']}, 품질: {$row['quality_score']}, 분류: {$glasses}, 상태: {$active}\n";
        }
    }
    
    // 3. 테이블 구조 확인
    echo "\n3. 테이블 존재 여부:\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'face_recognition_logs'");
    $exists = $stmt->fetch() ? '존재' : '없음';
    echo "- face_recognition_logs: {$exists}\n";
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'member_faces'");
    $exists = $stmt->fetch() ? '존재' : '없음';
    echo "- member_faces: {$exists}\n";
    
} catch (PDOException $e) {
    echo "DB 오류: " . $e->getMessage() . "\n";
}
?> 