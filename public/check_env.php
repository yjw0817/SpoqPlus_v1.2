<?php
// 환경 변수 확인 스크립트

// .env 파일 로드 (CodeIgniter의 방식과 동일)
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

echo "<h2>Face Recognition 환경 변수 확인</h2>";
echo "<pre>";
echo "FACE_HOST: " . getenv('FACE_HOST') . " (기본값: localhost)\n";
echo "FACE_PORT: " . getenv('FACE_PORT') . " (기본값: 5002)\n";
echo "\n";
echo "DB_TYPE: " . getenv('DB_TYPE') . "\n";
echo "DB_HOST: " . getenv('DB_HOST') . "\n";
echo "DB_NAME: " . getenv('DB_NAME') . "\n";
echo "</pre>";

// Python 서버 연결 테스트
$faceHost = getenv('FACE_HOST') ?: 'localhost';
$facePort = getenv('FACE_PORT') ?: '5002';
$url = "http://{$faceHost}:{$facePort}/api/face/health";

echo "<h3>Python 서버 연결 테스트</h3>";
echo "<p>URL: $url</p>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($response && $httpCode == 200) {
    echo "<p style='color: green;'>✅ Python 서버 연결 성공!</p>";
    echo "<pre>" . json_encode(json_decode($response), JSON_PRETTY_PRINT) . "</pre>";
} else {
    echo "<p style='color: red;'>❌ Python 서버 연결 실패</p>";
    echo "<p>HTTP Code: $httpCode</p>";
    echo "<p>Error: $error</p>";
}
?>