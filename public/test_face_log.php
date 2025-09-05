<?php
require_once '../app/Config/Autoload.php';
require_once '../app/Config/Database.php';

use App\Models\FaceRecognitionModel;

// 테스트 데이터
$testLogData = [
    'mem_sno' => 'MM202505290000000002',
    'confidence_score' => 0.95,
    'processing_time_ms' => 150,
    'glasses_detected' => true,
    'match_category' => 'match_found',
    'security_checks_passed' => json_encode([
        'basic_security' => true,
        'liveness_check' => true
    ]),
    'success' => true,
    'error_message' => null,
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Test Agent',
    'session_id' => 'test_session',
    'similarity_score' => 0.93,
    'quality_score' => 0.88
];

try {
    echo "=== 얼굴 인식 로그 저장 테스트 ===\n\n";
    
    $faceModel = new FaceRecognitionModel();
    echo "모델 생성 성공\n";
    
    echo "테스트 데이터:\n";
    print_r($testLogData);
    echo "\n";
    
    $result = $faceModel->saveFaceRecognitionLog($testLogData);
    
    if ($result) {
        echo "✅ 로그 저장 성공!\n";
    } else {
        echo "❌ 로그 저장 실패\n";
    }
    
    // 최근 로그 확인
    echo "\n=== 최근 로그 3개 ===\n";
    $db = \Config\Database::connect();
    $query = $db->query("SELECT * FROM face_recognition_logs ORDER BY created_at DESC LIMIT 3");
    $logs = $query->getResultArray();
    
    foreach ($logs as $log) {
        echo "ID: {$log['id']}, 회원: {$log['mem_sno']}, 성공: " . ($log['success'] ? 'Y' : 'N') . ", 시간: {$log['created_at']}\n";
    }
    
} catch (Exception $e) {
    echo "❌ 오류 발생: " . $e->getMessage() . "\n";
    echo "파일: " . $e->getFile() . "\n";
    echo "라인: " . $e->getLine() . "\n";
    
    // 스택 트레이스 출력
    echo "\n스택 트레이스:\n";
    echo $e->getTraceAsString() . "\n";
}
?> 