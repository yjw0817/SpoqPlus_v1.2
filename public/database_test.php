<?php
// 데이터베이스 연결 진단 도구
// MySQL 8.0 인증 문제 해결을 위한 테스트 도구

header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html>
<head>
    <title>🔧 데이터베이스 연결 진단</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px; margin: 10px 0; border-radius: 4px; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 4px; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 10px; margin: 10px 0; border-radius: 4px; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 10px; margin: 10px 0; border-radius: 4px; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .nav-links { text-align: center; margin: 20px 0; }
        .nav-links a { margin: 0 10px; padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .solution { background: #e7f3ff; border-left: 4px solid #2196F3; padding: 15px; margin: 10px 0; }
        h1 { color: #2c3e50; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 데이터베이스 연결 진단 도구</h1>
        
        <div class="nav-links">
            <a href="analyze_face_logs.php">📊 로그 분석</a>
            <a href="check_face_logs.php">📋 로그 확인</a>
            <a href="test_face_registration.php">🧪 등록 테스트</a>
        </div>

        <?php
        // 1. 기본 PHP 정보 확인
        echo "<div class='info'>";
        echo "<h2>🔍 1. 기본 환경 확인</h2>";
        echo "<p><strong>PHP 버전:</strong> " . phpversion() . "</p>";
        echo "<p><strong>MySQLi 확장:</strong> " . (extension_loaded('mysqli') ? '✅ 사용 가능' : '❌ 사용 불가') . "</p>";
        echo "<p><strong>PDO 확장:</strong> " . (extension_loaded('pdo') ? '✅ 사용 가능' : '❌ 사용 불가') . "</p>";
        echo "<p><strong>PDO MySQL 확장:</strong> " . (extension_loaded('pdo_mysql') ? '✅ 사용 가능' : '❌ 사용 불가') . "</p>";
        echo "</div>";

        // 2. 직접 데이터베이스 연결 테스트
        echo "<div class='info'>";
        echo "<h2>🔌 2. 직접 데이터베이스 연결 테스트</h2>";
        
        // 기본 설정값 (일반적인 XAMPP 설정)
        $config = [
            'hostname' => 'localhost',
            'port' => 3306,
            'database' => 'spoqplusteam',
            'username' => 'root',
            'password' => ''
        ];
        
        // MySQLi 테스트
        echo "<h3>🧪 MySQLi 연결 테스트</h3>";
        try {
            $mysqli = new mysqli(
                $config['hostname'], 
                $config['username'], 
                $config['password'], 
                $config['database'], 
                $config['port']
            );
            
            if ($mysqli->connect_error) {
                throw new Exception("MySQLi 연결 실패: " . $mysqli->connect_error);
            }
            
            echo "<div class='success'>✅ MySQLi 연결 성공!</div>";
            echo "<p>서버 버전: " . $mysqli->server_info . "</p>";
            
            // 테이블 존재 확인
            $result = $mysqli->query("SHOW TABLES LIKE 'face_recognition_logs'");
            if ($result && $result->num_rows > 0) {
                echo "<p>✅ face_recognition_logs 테이블 존재</p>";
            } else {
                echo "<p class='warning'>⚠️ face_recognition_logs 테이블 없음</p>";
            }
            
            $mysqli->close();
            
        } catch (Exception $e) {
            echo "<div class='error'>❌ MySQLi 연결 실패: " . $e->getMessage() . "</div>";
        }
        
        // PDO 테스트
        echo "<h3>🧪 PDO 연결 테스트</h3>";
        try {
            $dsn = "mysql:host={$config['hostname']};port={$config['port']};dbname={$config['database']};charset=utf8mb4";
            $pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]);
            
            echo "<div class='success'>✅ PDO 연결 성공!</div>";
            
            // 버전 확인
            $version = $pdo->query('SELECT VERSION()')->fetchColumn();
            echo "<p>MySQL 버전: {$version}</p>";
            
            // 테이블 존재 확인
            $stmt = $pdo->prepare("SHOW TABLES LIKE 'face_recognition_logs'");
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                echo "<p>✅ face_recognition_logs 테이블 존재</p>";
            } else {
                echo "<p class='warning'>⚠️ face_recognition_logs 테이블 없음</p>";
            }
            
            $pdo = null;
            
        } catch (Exception $e) {
            echo "<div class='error'>❌ PDO 연결 실패: " . $e->getMessage() . "</div>";
            
            // MySQL 8.0 인증 문제 해결 방법 제시
            if (strpos($e->getMessage(), '2054') !== false || strpos($e->getMessage(), 'authentication method') !== false) {
                echo "<div class='solution'>";
                echo "<h4>💡 MySQL 8.0 인증 문제 해결 방법</h4>";
                echo "<p>MySQL 8.0에서 새로운 인증 방법으로 인해 발생하는 문제입니다.</p>";
                echo "<p><strong>해결 방법:</strong></p>";
                echo "<ol>";
                echo "<li>XAMPP Control Panel에서 MySQL의 'Admin' 버튼 클릭</li>";
                echo "<li>phpMyAdmin에서 다음 SQL 실행:</li>";
                echo "</ol>";
                echo "<pre>ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';</pre>";
                echo "<p>또는 새로운 사용자 생성:</p>";
                echo "<pre>CREATE USER 'spoqplus'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password123';\nGRANT ALL PRIVILEGES ON spoqplusteam.* TO 'spoqplus'@'localhost';\nFLUSH PRIVILEGES;</pre>";
                echo "</div>";
            }
        }
        echo "</div>";

        // 3. CodeIgniter 데이터베이스 연결 테스트
        echo "<div class='info'>";
        echo "<h2>🏗️ 3. CodeIgniter 데이터베이스 연결 테스트</h2>";
        
        try {
            // FCPATH 정의
            if (!defined('FCPATH')) {
                define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
            }
            
            // 환경 설정
            if (!defined('ENVIRONMENT')) {
                define('ENVIRONMENT', 'development');
            }
            
            // 경로 설정
            $pathsPath = realpath(__DIR__ . '/../app/Config/Paths.php');
            if (!$pathsPath || !file_exists($pathsPath)) {
                throw new Exception('Paths.php 파일을 찾을 수 없습니다');
            }
            
            require_once $pathsPath;
            $paths = new Config\Paths();
            
            $bootstrap = rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
            if (!file_exists($bootstrap)) {
                throw new Exception('Bootstrap 파일을 찾을 수 없습니다');
            }
            
            $app = require realpath($bootstrap);
            $app->initialize();
            
            // 데이터베이스 연결
            $db = \Config\Database::connect();
            
            echo "<div class='success'>✅ CodeIgniter 초기화 성공!</div>";
            
            // 연결 테스트
            if ($db->connID) {
                echo "<div class='success'>✅ CodeIgniter 데이터베이스 연결 성공!</div>";
                
                // 테이블 존재 확인
                if ($db->tableExists('face_recognition_logs')) {
                    echo "<p>✅ face_recognition_logs 테이블 존재</p>";
                    
                    // 로그 수 확인
                    $logCount = $db->table('face_recognition_logs')->countAllResults();
                    echo "<p>📊 저장된 로그 수: {$logCount}개</p>";
                } else {
                    echo "<p class='warning'>⚠️ face_recognition_logs 테이블 없음</p>";
                }
                
                if ($db->tableExists('member_faces')) {
                    echo "<p>✅ member_faces 테이블 존재</p>";
                    
                    // 얼굴 수 확인
                    $faceCount = $db->table('member_faces')->countAllResults();
                    echo "<p>👥 등록된 얼굴 수: {$faceCount}개</p>";
                } else {
                    echo "<p class='warning'>⚠️ member_faces 테이블 없음</p>";
                }
                
            } else {
                echo "<div class='error'>❌ CodeIgniter 데이터베이스 연결 실패</div>";
            }
            
        } catch (Exception $e) {
            echo "<div class='error'>❌ CodeIgniter 연결 실패: " . $e->getMessage() . "</div>";
        }
        echo "</div>";

        // 4. 종합 진단 결과
        echo "<div class='solution'>";
        echo "<h2>📋 4. 종합 진단 결과 및 권장사항</h2>";
        echo "<h3>💡 문제 해결 순서:</h3>";
        echo "<ol>";
        echo "<li><strong>XAMPP 상태 확인:</strong> Apache와 MySQL이 실행 중인지 확인</li>";
        echo "<li><strong>MySQL 8.0 인증 문제:</strong> 위에서 제시한 SQL 명령어 실행</li>";
        echo "<li><strong>데이터베이스 존재 확인:</strong> spoqplusteam 데이터베이스가 존재하는지 확인</li>";
        echo "<li><strong>테이블 생성:</strong> 필요한 테이블들이 생성되었는지 확인</li>";
        echo "<li><strong>포트 확인:</strong> MySQL이 3306 포트에서 실행 중인지 확인</li>";
        echo "</ol>";
        echo "</div>";
        ?>
        
        <div class="nav-links">
            <button onclick="location.reload()" style="padding: 8px 16px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">🔄 다시 테스트</button>
        </div>
    </div>
</body>
</html> 