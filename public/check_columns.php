<?php
$host = '192.168.0.48';
$username = 'root';
$password = 'spoqdb11';
$database = 'spoqplusdb';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    
    echo "=== member_faces 테이블 구조 ===\n";
    $stmt = $pdo->query("DESCRIBE member_faces");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']} | {$row['Type']} | {$row['Null']} | {$row['Default']}\n";
    }
    
    echo "\n=== face_recognition_logs 테이블 구조 ===\n";
    $stmt = $pdo->query("DESCRIBE face_recognition_logs");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']} | {$row['Type']} | {$row['Null']} | {$row['Default']}\n";
    }
} catch (PDOException $e) {
    echo "DB 오류: " . $e->getMessage() . "\n";
}
?> 