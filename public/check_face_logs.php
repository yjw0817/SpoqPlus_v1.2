<?php
// face_recognition_logs 테이블 상세 확인 도구
// URL: http://localhost:8080/check_face_logs.php

header('Content-Type: text/html; charset=utf-8');

// 더 안전한 CodeIgniter 초기화
try {
    // FCPATH 정의
    if (!defined('FCPATH')) {
        define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
    }
    
    // 경로 설정
    $pathsPath = realpath(__DIR__ . '/../app/Config/Paths.php');
    if (!$pathsPath || !file_exists($pathsPath)) {
        throw new Exception('Paths.php 파일을 찾을 수 없습니다: ' . $pathsPath);
    }
    
    require_once $pathsPath;
    
    // Paths 인스턴스 생성
    $paths = new Config\Paths();
    
    // Bootstrap 파일 경로 설정
    $bootstrap = rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
    if (!file_exists($bootstrap)) {
        throw new Exception('Bootstrap 파일을 찾을 수 없습니다: ' . $bootstrap);
    }
    
    // CodeIgniter 앱 시작
    $app = require realpath($bootstrap);
    
    // 환경 설정
    if (!defined('ENVIRONMENT')) {
        define('ENVIRONMENT', 'development');
    }
    
    $app->initialize();
    
    // 데이터베이스 연결 테스트
    $db = \Config\Database::connect();
    $db->initialize();
    
    // 연결 테스트
    if (!$db->connID) {
        throw new Exception('데이터베이스 연결에 실패했습니다.');
    }
    
    echo "<style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h2 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        h3 { color: #34495e; margin-top: 30px; }
        table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .status-ok { color: green; font-weight: bold; }
        .status-error { color: red; font-weight: bold; }
        .status-warning { color: orange; font-weight: bold; }
        .nav-links { text-align: center; margin: 20px 0; }
        .nav-links a { margin: 0 10px; padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .nav-links a:hover { background: #0056b3; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9; }
        .success-box { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .error-box { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; border-radius: 4px; margin: 10px 0; }
    </style>";
    
    echo "<div class='container'>";
    echo "<h2>🔍 Face Recognition Logs 분석</h2>";
    
    echo "<div class='nav-links'>";
    echo "<a href='analyze_face_logs.php'>📊 로그 분석</a>";
    echo "<a href='check_glasses_database.php'>👓 안경 DB 확인</a>";
    echo "<a href='test_face_registration.php'>🧪 등록 테스트</a>";
    echo "</div>";
    
    echo "<div class='success-box'>";
    echo "✅ CodeIgniter 초기화 및 데이터베이스 연결 성공";
    echo "</div>";
    
    // 테이블 존재 확인
    if ($db->tableExists('face_recognition_logs')) {
        echo "<div class='section'>";
        echo "<h3>📋 face_recognition_logs 테이블 현황</h3>";
        
        // 총 로그 수 확인
        $totalLogs = $db->table('face_recognition_logs')->countAllResults();
        echo "<p>📊 총 로그 수: <strong>{$totalLogs}</strong>개</p>";
        
        if ($totalLogs > 0) {
            // 최근 로그 확인
            $logs = $db->table('face_recognition_logs')
                       ->orderBy('recognition_time', 'DESC')
                       ->limit(10)
                       ->get()
                       ->getResultArray();
            
            if (!empty($logs)) {
                echo "<h4>최근 10개 로그</h4>";
                echo "<table>";
                echo "<tr><th>ID</th><th>회원번호</th><th>시간</th><th>카테고리</th><th>Confidence</th><th>Similarity</th><th>Quality</th><th>안경</th><th>성공</th></tr>";
                
                foreach ($logs as $log) {
                    $glassesStatus = isset($log['glasses_detected']) ? 
                        ($log['glasses_detected'] ? '👓' : '👁️') : 
                        '❓';
                    
                    $time = date('m-d H:i', strtotime($log['recognition_time']));
                    
                    echo "<tr>";
                    echo "<td>{$log['log_id']}</td>";
                    echo "<td>{$log['mem_sno']}</td>";
                    echo "<td>{$time}</td>";
                    echo "<td>{$log['match_category']}</td>";
                    echo "<td>" . number_format($log['confidence_score'], 4) . "</td>";
                    echo "<td>" . ($log['similarity_score'] ? number_format($log['similarity_score'], 4) : 'N/A') . "</td>";
                    echo "<td>" . ($log['quality_score'] ? number_format($log['quality_score'], 4) : 'N/A') . "</td>";
                    echo "<td>{$glassesStatus}</td>";
                    echo "<td>" . ($log['success'] ? '✅' : '❌') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
            // 통계 정보
            echo "<div class='section'>";
            echo "<h4>📊 통계 정보</h4>";
            
            $stats = $db->query("
                SELECT 
                    match_category,
                    COUNT(*) as total,
                    SUM(success) as successes,
                    SUM(glasses_detected) as glasses_count,
                    ROUND(AVG(confidence_score), 4) as avg_confidence,
                    ROUND(AVG(similarity_score), 4) as avg_similarity,
                    ROUND(AVG(quality_score), 4) as avg_quality
                FROM face_recognition_logs 
                GROUP BY match_category
            ")->getResultArray();
            
            echo "<table>";
            echo "<tr><th>카테고리</th><th>총 수</th><th>성공</th><th>성공률</th><th>안경 착용</th><th>평균 Confidence</th><th>평균 Similarity</th><th>평균 Quality</th></tr>";
            
            foreach ($stats as $stat) {
                $successRate = $stat['total'] > 0 ? round(($stat['successes'] / $stat['total']) * 100, 2) : 0;
                echo "<tr>";
                echo "<td>{$stat['match_category']}</td>";
                echo "<td>{$stat['total']}</td>";
                echo "<td>{$stat['successes']}</td>";
                echo "<td>{$successRate}%</td>";
                echo "<td>{$stat['glasses_count']}</td>";
                echo "<td>" . ($stat['avg_confidence'] ?? 'N/A') . "</td>";
                echo "<td>" . ($stat['avg_similarity'] ?? 'N/A') . "</td>";
                echo "<td>" . ($stat['avg_quality'] ?? 'N/A') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            
        } else {
            echo "<div class='error-box'>";
            echo "❌ 저장된 로그가 없습니다. 얼굴 등록 테스트를 먼저 진행하세요.";
            echo "</div>";
        }
        echo "</div>";
        
    } else {
        echo "<div class='error-box'>";
        echo "❌ face_recognition_logs 테이블이 존재하지 않습니다.";
        echo "</div>";
    }
    
    echo "</div>"; // container 닫기
    
} catch (Exception $e) {
    echo "<div class='error-box'>";
    echo "<h3>❌ 오류 발생</h3>";
    echo "<p><strong>오류 메시지:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>해결 방법:</strong></p>";
    echo "<ul>";
    echo "<li>XAMPP가 실행 중인지 확인하세요</li>";
    echo "<li>MySQL 서비스가 시작되었는지 확인하세요</li>";
    echo "<li>app/Config/Database.php 설정을 확인하세요</li>";
    echo "<li>CodeIgniter 버전 호환성을 확인하세요</li>";
    echo "</ul>";
    echo "</div>";
}
?>

<script>
// 자동 새로고침 (30초마다) - 선택사항
// setInterval(() => location.reload(), 30000);
</script> 