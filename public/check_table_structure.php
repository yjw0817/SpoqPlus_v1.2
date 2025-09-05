<?php
$host = '192.168.0.48';
$username = 'root';
$password = 'spoqdb11';
$database = 'spoqplusdb';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== face_recognition_logs 테이블 구조 ===\n";
    $stmt = $pdo->query("DESCRIBE face_recognition_logs");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        echo sprintf("%-20s | %-15s | %-5s | %s\n", 
            $column['Field'], 
            $column['Type'], 
            $column['Null'], 
            $column['Default'] ?? 'NULL'
        );
    }
    
    echo "\n=== 테이블 존재 여부 확인 ===\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'face_recognition_logs'");
    $exists = $stmt->fetch();
    echo $exists ? "✅ face_recognition_logs 테이블 존재" : "❌ face_recognition_logs 테이블 없음";
    echo "\n";
    
} catch (PDOException $e) {
    echo "❌ 데이터베이스 오류: " . $e->getMessage() . "\n";
}
?> 