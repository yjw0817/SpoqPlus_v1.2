<?php
// FaceRecognitionModel 테스트 스크립트

require_once '../vendor/autoload.php';

// CodeIgniter 설정
$app = \Config\Services::codeigniter();
$app->initialize();

try {
    // FaceRecognitionModel 인스턴스 생성 테스트
    $faceModel = new \App\Models\FaceRecognitionModel();
    
    echo "<h2>🔍 FaceRecognitionModel 테스트</h2>";
    
    // 1. 기본 메서드 확인
    echo "<h3>1. 기본 메서드 존재 확인</h3>";
    
    $methods = get_class_methods($faceModel);
    $requiredMethods = [
        'registerFace',
        'deactivateFace', 
        'saveFaceRecognitionLog',
        'registerMemberFace',     // 별칭
        'deactivateMemberFaces',  // 별칭
        'logRecognition'          // 별칭
    ];
    
    foreach ($requiredMethods as $method) {
        if (method_exists($faceModel, $method)) {
            echo "✅ {$method} - 존재<br>";
        } else {
            echo "❌ {$method} - 없음<br>";
        }
    }
    
    // 2. 데이터베이스 연결 테스트
    echo "<h3>2. 데이터베이스 연결 테스트</h3>";
    
    try {
        $faces = $faceModel->getAllActiveFaces();
        echo "✅ 데이터베이스 연결 성공 - 활성 얼굴 데이터: " . count($faces) . "개<br>";
    } catch (\Exception $e) {
        echo "❌ 데이터베이스 연결 실패: " . $e->getMessage() . "<br>";
    }
    
    // 3. 별칭 메서드 호출 테스트
    echo "<h3>3. 별칭 메서드 호출 테스트</h3>";
    
    try {
        // deactivateMemberFaces 테스트 (실제로는 실행하지 않음)
        if (method_exists($faceModel, 'deactivateMemberFaces')) {
            echo "✅ deactivateMemberFaces 메서드 호출 가능<br>";
        } else {
            echo "❌ deactivateMemberFaces 메서드 없음<br>";
        }
        
        // registerMemberFace 테스트
        if (method_exists($faceModel, 'registerMemberFace')) {
            echo "✅ registerMemberFace 메서드 호출 가능<br>";
        } else {
            echo "❌ registerMemberFace 메서드 없음<br>";
        }
        
        // logRecognition 테스트
        if (method_exists($faceModel, 'logRecognition')) {
            echo "✅ logRecognition 메서드 호출 가능<br>";
        } else {
            echo "❌ logRecognition 메서드 없음<br>";
        }
        
    } catch (\Exception $e) {
        echo "❌ 별칭 메서드 테스트 실패: " . $e->getMessage() . "<br>";
    }
    
    echo "<h3>4. 클래스 정보</h3>";
    echo "클래스명: " . get_class($faceModel) . "<br>";
    echo "부모 클래스: " . get_parent_class($faceModel) . "<br>";
    echo "총 메서드 수: " . count($methods) . "<br>";
    
    echo "<h3>5. 모든 메서드 목록</h3>";
    echo "<pre>";
    foreach ($methods as $method) {
        echo "- {$method}\n";
    }
    echo "</pre>";
    
} catch (\Exception $e) {
    echo "<h2>❌ 테스트 실패</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<br><p><a href='/FaceTest/health'>Python 서버 상태 확인</a></p>";
?> 