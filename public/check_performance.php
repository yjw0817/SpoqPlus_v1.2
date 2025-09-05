<?php
$host = '192.168.0.48';
$username = 'root';
$password = 'spoqdb11';
$database = 'spoqplusdb';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    
    echo "=== 얼굴 인식 시스템 성능 분석 ===\n\n";
    
    // 1. 총 로그 수 및 성공률
    $stmt = $pdo->query("SELECT 
        COUNT(*) as total_logs,
        SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as success_count,
        ROUND(SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) as success_rate
        FROM face_recognition_logs");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "📊 전체 성능 지표:\n";
    echo "- 총 로그 수: {$result['total_logs']}\n";
    echo "- 성공 횟수: {$result['success_count']}\n";
    echo "- 성공률: {$result['success_rate']}%\n\n";
    
    // 2. 품질 점수 분포
    echo "📈 품질 점수 분포:\n";
    $stmt = $pdo->query("SELECT 
        CASE 
            WHEN quality_score >= 0.9 THEN '최고 품질 (0.9+)'
            WHEN quality_score >= 0.8 THEN '우수 품질 (0.8-0.89)'
            WHEN quality_score >= 0.7 THEN '양호 품질 (0.7-0.79)'
            WHEN quality_score >= 0.6 THEN '보통 품질 (0.6-0.69)'
            ELSE '낮은 품질 (0.6 미만)'
        END as quality_range,
        COUNT(*) as count,
        ROUND(AVG(quality_score), 3) as avg_quality
        FROM face_recognition_logs
        WHERE quality_score IS NOT NULL
        GROUP BY quality_range
        ORDER BY avg_quality DESC");
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['quality_range']}: {$row['count']}건 (평균: {$row['avg_quality']})\n";
    }
    
    // 3. 신뢰도 분포
    echo "\n🎯 신뢰도 분포:\n";
    $stmt = $pdo->query("SELECT 
        CASE 
            WHEN confidence_score >= 0.95 THEN '매우 높음 (0.95+)'
            WHEN confidence_score >= 0.9 THEN '높음 (0.9-0.94)'
            WHEN confidence_score >= 0.85 THEN '양호 (0.85-0.89)'
            WHEN confidence_score >= 0.8 THEN '보통 (0.8-0.84)'
            ELSE '낮음 (0.8 미만)'
        END as confidence_range,
        COUNT(*) as count,
        ROUND(AVG(confidence_score), 3) as avg_confidence
        FROM face_recognition_logs
        WHERE confidence_score IS NOT NULL
        GROUP BY confidence_range
        ORDER BY avg_confidence DESC");
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['confidence_range']}: {$row['count']}건 (평균: {$row['avg_confidence']})\n";
    }
    
    // 4. 안경 검출 통계
    echo "\n👓 안경 검출 통계:\n";
    $stmt = $pdo->query("SELECT 
        glasses_detected,
        COUNT(*) as count,
        ROUND(COUNT(*) / (SELECT COUNT(*) FROM face_recognition_logs) * 100, 2) as percentage
        FROM face_recognition_logs
        WHERE glasses_detected IS NOT NULL
        GROUP BY glasses_detected");
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $glasses_status = $row['glasses_detected'] ? '안경 착용' : '안경 미착용';
        echo "- {$glasses_status}: {$row['count']}건 ({$row['percentage']}%)\n";
    }
    
    // 5. 보안 검사 통과율
    echo "\n🔒 보안 검사 상세 분석:\n";
    $stmt = $pdo->query("SELECT 
        security_checks_passed,
        COUNT(*) as count
        FROM face_recognition_logs
        WHERE security_checks_passed IS NOT NULL
        LIMIT 10");
    
    $security_stats = [
        'liveness_passed' => 0,
        'high_quality' => 0,
        'low_warnings' => 0,
        'total_with_security' => 0
    ];
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $security_data = json_decode($row['security_checks_passed'], true);
        if ($security_data) {
            $security_stats['total_with_security'] += $row['count'];
            
            if (isset($security_data['liveness_passed']) && $security_data['liveness_passed']) {
                $security_stats['liveness_passed'] += $row['count'];
            }
            
            if (isset($security_data['quality_score']) && $security_data['quality_score'] >= 0.8) {
                $security_stats['high_quality'] += $row['count'];
            }
            
            if (isset($security_data['security_warnings']) && count($security_data['security_warnings']) <= 1) {
                $security_stats['low_warnings'] += $row['count'];
            }
        }
    }
    
    if ($security_stats['total_with_security'] > 0) {
        $liveness_rate = round($security_stats['liveness_passed'] / $security_stats['total_with_security'] * 100, 2);
        $quality_rate = round($security_stats['high_quality'] / $security_stats['total_with_security'] * 100, 2);
        $warning_rate = round($security_stats['low_warnings'] / $security_stats['total_with_security'] * 100, 2);
        
        echo "- Liveness 검사 통과율: {$liveness_rate}%\n";
        echo "- 고품질 데이터 비율: {$quality_rate}%\n";
        echo "- 보안 경고 낮음 비율: {$warning_rate}%\n";
    }
    
    // 6. 최근 처리 시간 분석
    echo "\n⏱️ 처리 시간 분석:\n";
    $stmt = $pdo->query("SELECT 
        AVG(processing_time_ms) as avg_time,
        MIN(processing_time_ms) as min_time,
        MAX(processing_time_ms) as max_time,
        COUNT(*) as count
        FROM face_recognition_logs
        WHERE processing_time_ms IS NOT NULL
        AND recognition_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    
    $time_stats = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($time_stats['count'] > 0) {
        echo "- 평균 처리 시간: " . round($time_stats['avg_time'], 2) . "ms\n";
        echo "- 최소 처리 시간: " . round($time_stats['min_time'], 2) . "ms\n";
        echo "- 최대 처리 시간: " . round($time_stats['max_time'], 2) . "ms\n";
        echo "- 분석 대상: {$time_stats['count']}건 (최근 7일)\n";
    }
    
    // 7. 등록된 얼굴 데이터 품질
    echo "\n📋 등록된 얼굴 데이터 품질:\n";
    $stmt = $pdo->query("SELECT 
        COUNT(*) as total_faces,
        AVG(quality_score) as avg_quality,
        SUM(CASE WHEN glasses_detected = 1 THEN 1 ELSE 0 END) as glasses_count,
        SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count
        FROM member_faces");
    
    $face_stats = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "- 총 등록 얼굴: {$face_stats['total_faces']}개\n";
    echo "- 평균 품질 점수: " . round($face_stats['avg_quality'], 3) . "\n";
    echo "- 안경 착용자: {$face_stats['glasses_count']}명\n";
    echo "- 활성화된 얼굴: {$face_stats['active_count']}개\n";
    
    echo "\n✅ 성능 분석 완료!\n";
    
} catch (PDOException $e) {
    echo "DB 오류: " . $e->getMessage() . "\n";
}
?> 