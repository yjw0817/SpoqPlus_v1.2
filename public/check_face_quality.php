<?php
$host = '192.168.0.48';
$username = 'root';
$password = 'spoqdb11';
$database = 'spoqplusdb';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    
    echo "=== 현재 저장된 얼굴 데이터 품질 분석 ===\n\n";
    
    // member_faces 테이블 데이터
    $stmt = $pdo->query("SELECT mem_sno, quality_score, glasses_detected, confidence_score, registered_date FROM member_faces WHERE is_active = 1 ORDER BY registered_date DESC LIMIT 10");
    $faces = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($faces)) {
        echo "⚠️ 등록된 얼굴 데이터가 없습니다.\n";
    } else {
        echo "📊 등록된 얼굴 데이터:\n";
        foreach ($faces as $face) {
            echo "회원: {$face['mem_sno']}, 품질: {$face['quality_score']}, 신뢰도: {$face['confidence_score']}, 안경: " . ($face['glasses_detected'] ? 'Y' : 'N') . ", 등록일: {$face['registered_date']}\n";
        }
        
        // 품질 통계
        $stmt = $pdo->query("SELECT AVG(quality_score) as avg_quality, MIN(quality_score) as min_quality, MAX(quality_score) as max_quality, COUNT(*) as total FROM member_faces WHERE is_active = 1");
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "\n📈 품질 통계:\n";
        echo "평균 품질: " . round($stats['avg_quality'], 4) . "\n";
        echo "최소 품질: {$stats['min_quality']}\n";
        echo "최대 품질: {$stats['max_quality']}\n";
        echo "총 데이터: {$stats['total']}개\n";
        
        // 고품질 데이터 비율 (0.8 이상)
        $stmt = $pdo->query("SELECT COUNT(*) as high_quality FROM member_faces WHERE is_active = 1 AND quality_score >= 0.8");
        $highQuality = $stmt->fetch(PDO::FETCH_ASSOC)['high_quality'];
        $highQualityRate = round(($highQuality / $stats['total']) * 100, 2);
        
        echo "고품질 데이터 (≥0.8): {$highQuality}개 ({$highQualityRate}%)\n";
        
        // 인식 성공률 확인
        echo "\n🎯 최근 인식 성공률:\n";
        $stmt = $pdo->query("SELECT COUNT(*) as total_attempts FROM face_recognition_logs WHERE recognition_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
        $totalAttempts = $stmt->fetch(PDO::FETCH_ASSOC)['total_attempts'];
        
        $stmt = $pdo->query("SELECT COUNT(*) as successful_attempts FROM face_recognition_logs WHERE recognition_time >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND success = 1");
        $successfulAttempts = $stmt->fetch(PDO::FETCH_ASSOC)['successful_attempts'];
        
        if ($totalAttempts > 0) {
            $successRate = round(($successfulAttempts / $totalAttempts) * 100, 2);
            echo "최근 7일 인식 시도: {$totalAttempts}회\n";
            echo "성공한 인식: {$successfulAttempts}회\n";
            echo "성공률: {$successRate}%\n";
        } else {
            echo "최근 7일간 인식 시도가 없습니다.\n";
        }
    }
} catch (PDOException $e) {
    echo "DB 오류: " . $e->getMessage() . "\n";
}
?> 