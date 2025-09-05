<!DOCTYPE html>
<html>
<head>
    <title>👓 안경 검출 데이터베이스 현황</title>
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
        .btn { padding: 8px 16px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h1>👓 안경 검출 데이터베이스 현황</h1>
        
        <div class="nav-links">
            <a href="check_face_logs.php">📋 로그 상세 보기</a>
            <a href="analyze_face_logs.php">📊 로그 분석</a>
            <a href="test_face_registration.php">🧪 등록 테스트</a>
        </div>

        <?php
        // CodeIgniter 초기화 (수정됨)
        define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

        $pathsPath = realpath(__DIR__ . '/../app/Config/Paths.php');
        if (!$pathsPath) {
            echo "<div class='stat-card error'>";
            echo "<h2>❌ 설정 파일 오류</h2>";
            echo "<p>Paths.php 파일을 찾을 수 없습니다.</p>";
            echo "</div>";
            exit;
        }

        require $pathsPath;

        $paths = new Config\Paths();
        $bootstrap = rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';

        if (!file_exists($bootstrap)) {
            echo "<div class='stat-card error'>";
            echo "<h2>❌ Bootstrap 파일 오류</h2>";
            echo "<p>Bootstrap 파일을 찾을 수 없습니다: {$bootstrap}</p>";
            echo "</div>";
            exit;
        }

        try {
            $app = require realpath($bootstrap);
            $app->initialize();
            $db = \Config\Database::connect();
            
            echo "<div class='stat-card success'>";
            echo "<h2>✅ 데이터베이스 연결 성공</h2>";
            echo "<p>CodeIgniter 데이터베이스 연결이 정상적으로 설정되었습니다.</p>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='stat-card error'>";
            echo "<h2>❌ 데이터베이스 연결 실패</h2>";
            echo "<p>Error: " . $e->getMessage() . "</p>";
            echo "<p><strong>해결방법:</strong></p>";
            echo "<ul>";
            echo "<li>XAMPP MySQL 서비스가 실행 중인지 확인</li>";
            echo "<li>app/Config/Database.php 설정 확인</li>";
            echo "<li>MySQL 8.0 인증 방법 문제일 수 있음</li>";
            echo "</ul>";
            echo "</div>";
            exit;
        }

        try {
            // 1. member_faces 테이블 현황
            echo "<div class='stat-card info'>";
            echo "<h2>👥 얼굴 등록 현황 (member_faces)</h2>";
            
            $totalFaces = $db->table('member_faces')->countAllResults();
            $glassesCount = $db->table('member_faces')->where('glasses_detected', 1)->countAllResults();
            $noGlassesCount = $totalFaces - $glassesCount;
            
            echo "<p>📊 총 등록된 얼굴: <strong>{$totalFaces}</strong>개</p>";
            echo "<p>👓 안경 착용: <strong>{$glassesCount}</strong>개</p>";
            echo "<p>👁️ 안경 미착용: <strong>{$noGlassesCount}</strong>개</p>";
            
            if ($totalFaces > 0) {
                $glassesPercent = round(($glassesCount / $totalFaces) * 100, 1);
                echo "<p>📈 안경 착용 비율: <strong>{$glassesPercent}%</strong></p>";
            }
            echo "</div>";
            
            // 2. 최근 등록 얼굴 정보
            echo "<div class='stat-card'>";
            echo "<h2>📝 최근 등록 얼굴 (최대 10개)</h2>";
            
            $recentFaces = $db->table('member_faces')
                ->select('face_id, mem_sno, glasses_detected, quality_score, created_at')
                ->orderBy('created_at', 'DESC')
                ->limit(10)
                ->get()
                ->getResultArray();
            
            if (!empty($recentFaces)) {
                echo "<table>";
                echo "<tr><th>Face ID</th><th>회원번호</th><th>안경 착용</th><th>Quality Score</th><th>등록일시</th></tr>";
                
                foreach ($recentFaces as $face) {
                    $glassesLabel = $face['glasses_detected'] ? '👓 착용' : '👁️ 미착용';
                    $qualityScore = $face['quality_score'] ? number_format($face['quality_score'], 4) : 'N/A';
                    $createdAt = $face['created_at'] ? date('Y-m-d H:i', strtotime($face['created_at'])) : 'N/A';
                    
                    echo "<tr>";
                    echo "<td>{$face['face_id']}</td>";
                    echo "<td>{$face['mem_sno']}</td>";
                    echo "<td>{$glassesLabel}</td>";
                    echo "<td class='number'>{$qualityScore}</td>";
                    echo "<td>{$createdAt}</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>등록된 얼굴 데이터가 없습니다.</p>";
            }
            echo "</div>";
            
            // 3. Quality Score 분포
            echo "<div class='stat-card'>";
            echo "<h2>📊 Quality Score 분포</h2>";
            
            $qualityDistribution = $db->query("
                SELECT 
                    CASE 
                        WHEN quality_score >= 0.9 THEN '0.9-1.0 (매우 좋음)'
                        WHEN quality_score >= 0.8 THEN '0.8-0.9 (좋음)'
                        WHEN quality_score >= 0.7 THEN '0.7-0.8 (보통)'
                        WHEN quality_score >= 0.6 THEN '0.6-0.7 (낮음)'
                        ELSE '0.0-0.6 (매우 낮음)'
                    END as quality_range,
                    COUNT(*) as count,
                    SUM(glasses_detected) as glasses_count
                FROM member_faces 
                WHERE quality_score IS NOT NULL
                GROUP BY quality_range
                ORDER BY quality_range DESC
            ")->getResultArray();
            
            if (!empty($qualityDistribution)) {
                echo "<table>";
                echo "<tr><th>Quality 범위</th><th>개수</th><th>안경 착용</th><th>비율</th></tr>";
                
                foreach ($qualityDistribution as $dist) {
                    $percent = $totalFaces > 0 ? round(($dist['count'] / $totalFaces) * 100, 1) : 0;
                    echo "<tr>";
                    echo "<td>{$dist['quality_range']}</td>";
                    echo "<td class='number'>{$dist['count']}</td>";
                    echo "<td class='number'>{$dist['glasses_count']}</td>";
                    echo "<td class='number'>{$percent}%</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Quality Score 데이터가 없습니다.</p>";
            }
            echo "</div>";
            
            // 4. 로그 테이블 현황
            echo "<div class='stat-card'>";
            echo "<h2>📋 로그 현황 (face_recognition_logs)</h2>";
            
            $totalLogs = $db->table('face_recognition_logs')->countAllResults();
            $successLogs = $db->table('face_recognition_logs')->where('success', 1)->countAllResults();
            $registrationLogs = $db->table('face_recognition_logs')->where('match_category', 'registration')->countAllResults();
            $recognitionLogs = $db->table('face_recognition_logs')->where('match_category', 'recognition')->countAllResults();
            
            echo "<p>📊 총 로그 수: <strong>{$totalLogs}</strong>개</p>";
            echo "<p>✅ 성공: <strong>{$successLogs}</strong>개</p>";
            echo "<p>📝 등록 로그: <strong>{$registrationLogs}</strong>개</p>";
            echo "<p>🔍 인식 로그: <strong>{$recognitionLogs}</strong>개</p>";
            echo "</div>";
            
            // 5. 시스템 권장사항
            echo "<div class='stat-card warning'>";
            echo "<h2>💡 시스템 권장사항</h2>";
            
            $recommendations = [];
            
            if ($totalFaces == 0) {
                $recommendations[] = "📭 등록된 얼굴이 없습니다. 얼굴 등록을 진행하세요.";
            } else {
                $lowQualityFaces = $db->table('member_faces')
                    ->where('quality_score <', 0.7)
                    ->countAllResults();
                
                if ($lowQualityFaces > 0) {
                    $recommendations[] = "📷 품질이 낮은 얼굴이 {$lowQualityFaces}개 있습니다. 재등록을 고려하세요.";
                }
                
                if ($glassesCount > 0 && $recognitionLogs == 0) {
                    $recommendations[] = "👓 안경 착용자가 {$glassesCount}명 등록되어 있습니다. 인식 테스트를 진행하세요.";
                }
                
                if (count($recommendations) === 0) {
                    $recommendations[] = "🎉 데이터베이스 상태가 양호합니다!";
                }
            }
            
            foreach ($recommendations as $rec) {
                echo "<p>{$rec}</p>";
            }
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='stat-card error'>";
            echo "<h2>❌ 데이터 조회 실패</h2>";
            echo "<p>오류: " . $e->getMessage() . "</p>";
            echo "</div>";
        }
        ?>
        
        <div class="stat-card info">
            <h2>🔧 테스트 도구</h2>
            <p>다음 버튼을 클릭하여 테스트를 진행하세요:</p>
            <button class="btn" onclick="window.location.href='test_face_registration.php'">🧪 얼굴 등록 테스트</button>
            <button class="btn" onclick="window.location.href='check_face_logs.php'">📋 로그 상세 확인</button>
        </div>
    </div>
</body>
</html> 
<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "spoqplus";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h2>🔍 얼굴 데이터베이스 현황 분석</h2>";
    echo "<hr>";

    // 1. 전체 통계
    $stmt = $pdo->query("
        SELECT 
            COUNT(*) as total_faces,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_faces,
            SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive_faces
        FROM member_faces
    ");
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<h3>📊 전체 통계</h3>";
    echo "<ul>";
    echo "<li><strong>총 얼굴 데이터:</strong> {$stats['total_faces']}개</li>";
    echo "<li><strong>활성 데이터:</strong> {$stats['active_faces']}개</li>";
    echo "<li><strong>비활성 데이터:</strong> {$stats['inactive_faces']}개</li>";
    echo "</ul>";

    // 2. 안경 착용 통계 (활성 데이터만)
    $stmt = $pdo->query("
        SELECT 
            glasses_detected,
            COUNT(*) as count
        FROM member_faces 
        WHERE is_active = 1 
        GROUP BY glasses_detected
    ");

    echo "<h3>👓 안경 착용 통계 (활성 데이터)</h3>";
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $label = $row['glasses_detected'] ? '안경 착용' : '안경 없음';
        echo "<li><strong>{$label}:</strong> {$row['count']}명</li>";
    }
    echo "</ul>";

    // 3. 상세 데이터 조회
    $stmt = $pdo->query("
        SELECT 
            face_id,
            mem_sno,
            glasses_detected,
            quality_score,
            security_level,
            liveness_score,
            is_active,
            registered_date,
            last_updated,
            CASE WHEN face_encoding = '[]' OR face_encoding = '' THEN '빈 데이터'
                 ELSE CONCAT(LENGTH(face_encoding), '바이트') END as encoding_status
        FROM member_faces 
        ORDER BY registered_date DESC
        LIMIT 10
    ");

    echo "<h3>📋 최근 등록된 얼굴 데이터 (최대 10개)</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background-color: #f0f0f0;'>";
    echo "<th>ID</th><th>회원번호</th><th>안경</th><th>품질</th><th>보안</th><th>Liveness</th><th>활성</th><th>등록일</th><th>수정일</th><th>인코딩</th>";
    echo "</tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $glasses_icon = $row['glasses_detected'] ? '👓' : '👤';
        $active_status = $row['is_active'] ? '✅' : '❌';

        echo "<tr>";
        echo "<td>{$row['face_id']}</td>";
        echo "<td>{$row['mem_sno']}</td>";
        echo "<td>{$glasses_icon} " . ($row['glasses_detected'] ? 'Yes' : 'No') . "</td>";
        echo "<td>{$row['quality_score']}</td>";
        echo "<td>{$row['security_level']}</td>";
        echo "<td>{$row['liveness_score']}</td>";
        echo "<td>{$active_status}</td>";
        echo "<td>{$row['registered_date']}</td>";
        echo "<td>{$row['last_updated']}</td>";
        echo "<td>{$row['encoding_status']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    // 4. 테이블 구조 확인
    echo "<h3>🔧 테이블 구조</h3>";
    $stmt = $pdo->query("DESCRIBE member_faces");
    echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
    echo "<tr style='background-color: #f0f0f0;'><th>필드</th><th>타입</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['Field']}</td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "<td>{$row['Default']}</td>";
        echo "<td>{$row['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch(PDOException $e) {
    echo "<div style='background-color: #ffe8e8; padding: 10px; border-radius: 5px;'>";
    echo "<h3>❌ 데이터베이스 연결 실패</h3>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "</div>";
}
?> 