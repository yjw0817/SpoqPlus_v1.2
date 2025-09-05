<?php
// 얼굴 인식 로그 분석 도구
// 생성일: 2024

header('Content-Type: text/html; charset=utf-8');

// CodeIgniter 초기화 (수정됨)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

$pathsPath = realpath(__DIR__ . '/../app/Config/Paths.php');
if (!$pathsPath) {
    die('Paths.php 파일을 찾을 수 없습니다.');
}

require $pathsPath;

$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';

if (!file_exists($bootstrap)) {
    die('Bootstrap 파일을 찾을 수 없습니다: ' . $bootstrap);
}

$app = require realpath($bootstrap);
$app->initialize();

try {
    $db = \Config\Database::connect();
} catch (Exception $e) {
    die('데이터베이스 연결 실패: ' . $e->getMessage());
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>📊 얼굴 인식 로그 분석</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .stat-card { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { border-left: 4px solid #28a745; }
        .warning { border-left: 4px solid #ffc107; }
        .error { border-left: 4px solid #dc3545; }
        .info { border-left: 4px solid #007bff; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .number { text-align: right; font-family: monospace; }
        h1 { color: #333; text-align: center; }
        h2 { color: #555; margin-top: 0; }
        .nav-links { text-align: center; margin: 20px 0; }
        .nav-links a { margin: 0 10px; padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .nav-links a:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 얼굴 인식 로그 분석 대시보드</h1>
        
        <div class="nav-links">
            <a href="check_face_logs.php">📋 로그 상세 보기</a>
            <a href="test_face_registration.php">🧪 등록 테스트</a>
            <a href="check_glasses_database.php">👓 안경 DB 확인</a>
        </div>
        
        <?php
        try {
            // 1. 전체 통계
            echo "<div class='stat-card info'>";
            echo "<h2>🔍 전체 통계</h2>";
            
            $totalLogs = $db->table('face_recognition_logs')->countAllResults();
            $successLogs = $db->table('face_recognition_logs')->where('success', 1)->countAllResults();
            $failureLogs = $totalLogs - $successLogs;
            $successRate = $totalLogs > 0 ? round(($successLogs / $totalLogs) * 100, 2) : 0;
            
            echo "<p>📈 총 로그 수: <strong>{$totalLogs}</strong></p>";
            echo "<p>✅ 성공: <strong>{$successLogs}</strong> ({$successRate}%)</p>";
            echo "<p>❌ 실패: <strong>{$failureLogs}</strong></p>";
            echo "</div>";
            
            // 2. 등록 vs 인식 분석
            echo "<div class='stat-card'>";
            echo "<h2>📋 카테고리별 분석</h2>";
            
            $categoryStats = $db->query("
                SELECT 
                    match_category,
                    COUNT(*) as total,
                    SUM(success) as successes,
                    ROUND(AVG(confidence_score), 4) as avg_confidence,
                    ROUND(AVG(similarity_score), 4) as avg_similarity,
                    ROUND(AVG(quality_score), 4) as avg_quality,
                    SUM(glasses_detected) as glasses_count
                FROM face_recognition_logs 
                GROUP BY match_category
            ")->getResultArray();
            
            echo "<table>";
            echo "<tr><th>카테고리</th><th>총 수</th><th>성공률</th><th>평균 Confidence</th><th>평균 Similarity</th><th>평균 Quality</th><th>안경 착용</th></tr>";
            
            foreach ($categoryStats as $stat) {
                $successRate = $stat['total'] > 0 ? round(($stat['successes'] / $stat['total']) * 100, 2) : 0;
                echo "<tr>";
                echo "<td>{$stat['match_category']}</td>";
                echo "<td class='number'>{$stat['total']}</td>";
                echo "<td class='number'>{$successRate}%</td>";
                echo "<td class='number'>" . ($stat['avg_confidence'] ?? 'N/A') . "</td>";
                echo "<td class='number'>" . ($stat['avg_similarity'] ?? 'N/A') . "</td>";
                echo "<td class='number'>" . ($stat['avg_quality'] ?? 'N/A') . "</td>";
                echo "<td class='number'>{$stat['glasses_count']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            
            // 3. 점수 분포 분석
            echo "<div class='stat-card'>";
            echo "<h2>📊 Confidence Score 분포</h2>";
            
            $scoreDistribution = $db->query("
                SELECT 
                    CASE 
                        WHEN confidence_score = 1.0 THEN '1.0 (등록)'
                        WHEN confidence_score >= 0.9 THEN '0.9-1.0'
                        WHEN confidence_score >= 0.8 THEN '0.8-0.9'
                        WHEN confidence_score >= 0.7 THEN '0.7-0.8'
                        WHEN confidence_score >= 0.6 THEN '0.6-0.7'
                        ELSE '0.0-0.6'
                    END as score_range,
                    COUNT(*) as count,
                    SUM(success) as successes,
                    ROUND(AVG(processing_time_ms), 2) as avg_processing_time
                FROM face_recognition_logs 
                GROUP BY score_range
                ORDER BY score_range DESC
            ")->getResultArray();
            
            echo "<table>";
            echo "<tr><th>Confidence 범위</th><th>개수</th><th>성공 수</th><th>성공률</th><th>평균 처리시간(ms)</th></tr>";
            
            foreach ($scoreDistribution as $dist) {
                $successRate = $dist['count'] > 0 ? round(($dist['successes'] / $dist['count']) * 100, 2) : 0;
                echo "<tr>";
                echo "<td>{$dist['score_range']}</td>";
                echo "<td class='number'>{$dist['count']}</td>";
                echo "<td class='number'>{$dist['successes']}</td>";
                echo "<td class='number'>{$successRate}%</td>";
                echo "<td class='number'>{$dist['avg_processing_time']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            
            // 4. 안경 착용 분석
            echo "<div class='stat-card'>";
            echo "<h2>👓 안경 착용 분석</h2>";
            
            $glassesStats = $db->query("
                SELECT 
                    glasses_detected,
                    COUNT(*) as total,
                    SUM(success) as successes,
                    ROUND(AVG(confidence_score), 4) as avg_confidence,
                    ROUND(AVG(similarity_score), 4) as avg_similarity,
                    ROUND(AVG(quality_score), 4) as avg_quality
                FROM face_recognition_logs 
                GROUP BY glasses_detected
            ")->getResultArray();
            
            echo "<table>";
            echo "<tr><th>안경 착용</th><th>총 수</th><th>성공률</th><th>평균 Confidence</th><th>평균 Similarity</th><th>평균 Quality</th></tr>";
            
            foreach ($glassesStats as $stat) {
                $successRate = $stat['total'] > 0 ? round(($stat['successes'] / $stat['total']) * 100, 2) : 0;
                $glassesLabel = $stat['glasses_detected'] ? '👓 착용' : '👁️ 미착용';
                echo "<tr>";
                echo "<td>{$glassesLabel}</td>";
                echo "<td class='number'>{$stat['total']}</td>";
                echo "<td class='number'>{$successRate}%</td>";
                echo "<td class='number'>" . ($stat['avg_confidence'] ?? 'N/A') . "</td>";
                echo "<td class='number'>" . ($stat['avg_similarity'] ?? 'N/A') . "</td>";
                echo "<td class='number'>" . ($stat['avg_quality'] ?? 'N/A') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            
            // 5. 최근 로그 (최대 20개)
            echo "<div class='stat-card'>";
            echo "<h2>📝 최근 로그 (최대 20개)</h2>";
            
            $recentLogs = $db->table('face_recognition_logs')
                ->orderBy('recognition_time', 'DESC')
                ->limit(20)
                ->get()
                ->getResultArray();
            
            if (!empty($recentLogs)) {
                echo "<table>";
                echo "<tr><th>시간</th><th>회원번호</th><th>카테고리</th><th>Confidence</th><th>Similarity</th><th>Quality</th><th>안경</th><th>성공</th></tr>";
                
                foreach ($recentLogs as $log) {
                    $glassesLabel = $log['glasses_detected'] ? '👓' : '👁️';
                    $successLabel = $log['success'] ? '✅' : '❌';
                    $time = date('m-d H:i', strtotime($log['recognition_time']));
                    
                    echo "<tr>";
                    echo "<td>{$time}</td>";
                    echo "<td>{$log['mem_sno']}</td>";
                    echo "<td>{$log['match_category']}</td>";
                    echo "<td class='number'>" . number_format($log['confidence_score'], 4) . "</td>";
                    echo "<td class='number'>" . ($log['similarity_score'] ? number_format($log['similarity_score'], 4) : 'N/A') . "</td>";
                    echo "<td class='number'>" . ($log['quality_score'] ? number_format($log['quality_score'], 4) : 'N/A') . "</td>";
                    echo "<td>{$glassesLabel}</td>";
                    echo "<td>{$successLabel}</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>로그 데이터가 없습니다.</p>";
            }
            echo "</div>";
            
            // 6. 시스템 권장사항
            echo "<div class='stat-card warning'>";
            echo "<h2>💡 시스템 분석 결과 및 권장사항</h2>";
            
            // 분석 로직
            $recommendations = [];
            
            if ($totalLogs == 0) {
                $recommendations[] = "📭 로그 데이터가 없습니다. 얼굴 등록 테스트를 진행하세요.";
            } else {
                if ($successRate < 90) {
                    $recommendations[] = "📈 전체 성공률({$successRate}%)이 낮습니다. 임계값 조정을 고려하세요.";
                }
                
                $lowQualityLogs = $db->table('face_recognition_logs')
                    ->where('quality_score <', 0.7)
                    ->where('success', 0)
                    ->countAllResults();
                
                if ($lowQualityLogs > 0) {
                    $recommendations[] = "📷 품질 점수가 낮은 실패 로그가 {$lowQualityLogs}개 있습니다. 카메라 설정을 확인하세요.";
                }
                
                $registrationLogs = $db->table('face_recognition_logs')
                    ->where('match_category', 'registration')
                    ->countAllResults();
                
                if ($registrationLogs > 0) {
                    $recommendations[] = "✅ 등록 로그가 {$registrationLogs}개 있습니다. confidence_score가 1.0으로 저장되었는지 확인하세요.";
                }
                
                if (count($recommendations) === 0) {
                    $recommendations[] = "🎉 시스템이 정상적으로 작동하고 있습니다!";
                }
            }
            
            foreach ($recommendations as $rec) {
                echo "<p>{$rec}</p>";
            }
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='stat-card error'>";
            echo "<h2>❌ 분석 실패</h2>";
            echo "<p>오류: " . $e->getMessage() . "</p>";
            echo "</div>";
        }
        ?>
        
        <div class="stat-card info">
            <h2>📚 다음 단계</h2>
            <ul>
                <li>✅ <strong>confidence_score = 1.0</strong> 확인 (등록 시)</li>
                <li>✅ <strong>similarity_score = NULL</strong> 확인 (등록 시)</li>
                <li>✅ <strong>quality_score = 실제값</strong> 확인 (등록 시)</li>
                <li>🔄 인식 시 similarity_score 구현 (향후)</li>
                <li>📈 임계값 최적화 (데이터 누적 후)</li>
            </ul>
        </div>
    </div>
</body>
</html> 