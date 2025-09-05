<?php
// 얼굴 인식 로그 확인 스크립트

$host = '192.168.0.48';
$username = 'root';
$password = 'spoqdb11';
$database = 'spoqplusdb';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== 최근 얼굴 인식 로그 5개 ===\n\n";
    
    $stmt = $pdo->prepare("
        SELECT 
            id,
            mem_sno,
            confidence_score,
            similarity_score,
            quality_score,
            processing_time_ms,
            glasses_detected,
            match_category,
            security_checks_passed,
            success,
            error_message,
            created_at
        FROM face_recognition_logs 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($logs)) {
        echo "⚠️ 로그 데이터가 없습니다.\n";
    } else {
        foreach ($logs as $log) {
            echo "로그 ID: {$log['id']}\n";
            echo "회원번호: " . ($log['mem_sno'] ?? 'NULL') . "\n";
            echo "신뢰도 점수: {$log['confidence_score']}\n";
            echo "유사도 점수: {$log['similarity_score']}\n";
            echo "품질 점수: {$log['quality_score']}\n";
            echo "처리 시간: {$log['processing_time_ms']}ms\n";
            echo "안경 착용: " . ($log['glasses_detected'] ? 'Yes' : 'No') . "\n";
            echo "매칭 카테고리: {$log['match_category']}\n";
            echo "보안 검사: {$log['security_checks_passed']}\n";
            echo "성공 여부: " . ($log['success'] ? 'Yes' : 'No') . "\n";
            echo "오류 메시지: " . ($log['error_message'] ?? 'None') . "\n";
            echo "생성 시간: {$log['created_at']}\n";
            echo "-----------------------------------\n\n";
        }
    }
    
    // 통계 정보
    echo "=== 통계 정보 ===\n";
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM face_recognition_logs");
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "총 로그 수: $total\n";
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as success_count FROM face_recognition_logs WHERE success = 1");
    $stmt->execute();
    $success = $stmt->fetch(PDO::FETCH_ASSOC)['success_count'];
    echo "성공한 인식: $success\n";
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as glasses_count FROM face_recognition_logs WHERE glasses_detected = 1");
    $stmt->execute();
    $glasses = $stmt->fetch(PDO::FETCH_ASSOC)['glasses_count'];
    echo "안경 착용 감지: $glasses\n";
    
    if ($total > 0) {
        $success_rate = round(($success / $total) * 100, 2);
        echo "성공률: {$success_rate}%\n";
    }
    
} catch (PDOException $e) {
    echo "데이터베이스 연결 오류: " . $e->getMessage() . "\n";
}
?> 