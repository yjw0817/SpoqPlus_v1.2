<?php
// 얼굴 등록 및 로그 저장 테스트 도구
// URL: http://localhost:8080/test_face_registration.php

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

echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .warning { color: orange; font-weight: bold; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
</style>";

echo "<h2>🧪 얼굴 등록 테스트</h2>";

try {
    $db = \Config\Database::connect();
    
    // 테이블 존재 확인
    if (!$db->tableExists('face_recognition_logs')) {
        echo "<p style='color: red;'>❌ face_recognition_logs 테이블이 없습니다</p>";
        exit;
    }
    
    // 테스트 로그 저장
    $testLogData = [
        'mem_sno' => 'TEST123',
        'confidence_score' => 0.8500,
        'processing_time_ms' => 1500,
        'glasses_detected' => 1,
        'match_category' => 'registration',
        'success' => 1,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test Agent'
    ];
    
    echo "<h3>테스트 로그 데이터:</h3>";
    echo "<pre>";
    echo "mem_sno: " . $testLogData['mem_sno'] . "\n";
    echo "glasses_detected: " . $testLogData['glasses_detected'] . " (타입: " . gettype($testLogData['glasses_detected']) . ")\n";
    echo "confidence_score: " . $testLogData['confidence_score'] . "\n";
    echo "</pre>";
    
    // 직접 삽입 테스트
    $insertResult = $db->table('face_recognition_logs')->insert($testLogData);
    
    if ($insertResult) {
        echo "<p style='color: green;'>✅ 테스트 로그 삽입 성공</p>";
        
        // 저장된 데이터 확인
        $savedData = $db->table('face_recognition_logs')
                       ->where('mem_sno', 'TEST123')
                       ->orderBy('recognition_time', 'DESC')
                       ->limit(1)
                       ->get()
                       ->getRowArray();
        
        if ($savedData) {
            echo "<h3>저장된 데이터 확인:</h3>";
            echo "<pre>";
            echo "log_id: " . $savedData['log_id'] . "\n";
            echo "mem_sno: " . $savedData['mem_sno'] . "\n";
            echo "glasses_detected: " . $savedData['glasses_detected'] . " (타입: " . gettype($savedData['glasses_detected']) . ")\n";
            echo "confidence_score: " . $savedData['confidence_score'] . "\n";
            echo "recognition_time: " . $savedData['recognition_time'] . "\n";
            echo "</pre>";
        }
        
        // 테스트 데이터 삭제
        $db->table('face_recognition_logs')->where('mem_sno', 'TEST123')->delete();
        echo "<p style='color: green;'>✅ 테스트 데이터 삭제 완료</p>";
    } else {
        echo "<p style='color: red;'>❌ 테스트 로그 삽입 실패</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ 오류: " . $e->getMessage() . "</p>";
}

echo "<div style='text-align: center; margin: 20px;'>";
echo "<button onclick='location.reload()' style='padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;'>🔄 다시 테스트</button>";
echo "</div>";
?> 