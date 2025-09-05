<?php
// 데이터베이스 테이블 확인 스크립트

require_once '../vendor/autoload.php';

// CodeIgniter 설정
$app = \Config\Services::codeigniter();
$app->initialize();

try {
    $db = \Config\Database::connect();
    
    echo "<h2>📊 데이터베이스 테이블 확인</h2>";
    
    // 필요한 테이블들
    $requiredTables = [
        'member_faces',
        'face_recognition_logs', 
        'face_system_config'
    ];
    
    echo "<h3>1. 테이블 존재 확인</h3>";
    
    foreach ($requiredTables as $table) {
        if ($db->tableExists($table)) {
            echo "✅ {$table} - 존재<br>";
            
            // 테이블 구조 확인
            $fields = $db->getFieldNames($table);
            echo "&nbsp;&nbsp;&nbsp;필드 수: " . count($fields) . "<br>";
            
            // 레코드 수 확인
            $count = $db->table($table)->countAllResults();
            echo "&nbsp;&nbsp;&nbsp;레코드 수: {$count}<br>";
            
        } else {
            echo "❌ {$table} - 없음<br>";
        }
    }
    
    echo "<h3>2. member_faces 테이블 상세 정보</h3>";
    
    if ($db->tableExists('member_faces')) {
        $fields = $db->getFieldData('member_faces');
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>필드명</th><th>타입</th><th>Null</th><th>키</th><th>기본값</th></tr>";
        
        foreach ($fields as $field) {
            echo "<tr>";
            echo "<td>{$field->name}</td>";
            echo "<td>{$field->type}</td>";
            echo "<td>" . ($field->nullable ? 'YES' : 'NO') . "</td>";
            echo "<td>{$field->primary_key}</td>";
            echo "<td>{$field->default}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // 샘플 데이터 확인
        echo "<h4>샘플 데이터 (최대 5개)</h4>";
        $sample = $db->table('member_faces')->limit(5)->get()->getResultArray();
        
        if (!empty($sample)) {
            echo "<pre>";
            foreach ($sample as $row) {
                echo "ID: {$row['face_id']}, 회원: {$row['mem_sno']}, 활성: {$row['is_active']}, 등록일: {$row['registered_date']}\n";
            }
            echo "</pre>";
        } else {
            echo "데이터 없음<br>";
        }
        
    } else {
        echo "❌ member_faces 테이블이 존재하지 않습니다. 테이블을 생성해야 합니다.<br>";
        
        echo "<h4>테이블 생성 SQL</h4>";
        echo "<textarea rows='20' cols='80'>";
        echo "CREATE TABLE member_faces (
    face_id INT AUTO_INCREMENT PRIMARY KEY,
    mem_sno VARCHAR(50) NOT NULL,
    face_encoding JSON NOT NULL,
    face_image_path VARCHAR(255),
    glasses_detected BOOLEAN DEFAULT FALSE,
    quality_score DECIMAL(3,2) DEFAULT 0.85,
    security_level VARCHAR(20) DEFAULT 'medium',
    liveness_score DECIMAL(3,2) DEFAULT 0.95,
    is_active BOOLEAN DEFAULT TRUE,
    notes TEXT,
    registered_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_mem_sno (mem_sno),
    INDEX idx_active (is_active),
    INDEX idx_glasses (glasses_detected)
);

CREATE TABLE face_recognition_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    mem_sno VARCHAR(50),
    confidence_score DECIMAL(5,4) DEFAULT 0,
    processing_time_ms INT DEFAULT 0,
    glasses_detected BOOLEAN DEFAULT FALSE,
    match_category VARCHAR(50) DEFAULT 'unknown',
    security_checks_passed JSON,
    success BOOLEAN DEFAULT FALSE,
    error_message TEXT,
    recognition_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    session_id VARCHAR(255),
    INDEX idx_mem_sno (mem_sno),
    INDEX idx_success (success),
    INDEX idx_recognition_time (recognition_time)
);

CREATE TABLE face_system_config (
    config_id INT AUTO_INCREMENT PRIMARY KEY,
    config_key VARCHAR(100) UNIQUE NOT NULL,
    config_value TEXT NOT NULL,
    description TEXT,
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);";
        echo "</textarea>";
    }
    
    echo "<h3>3. 데이터베이스 연결 정보</h3>";
    echo "데이터베이스: " . $db->getDatabase() . "<br>";
    echo "호스트: " . $db->hostname . "<br>";
    
} catch (\Exception $e) {
    echo "<h2>❌ 데이터베이스 확인 실패</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}

echo "<br><p><a href='test_face_model.php'>FaceRecognitionModel 테스트</a></p>";
?> 