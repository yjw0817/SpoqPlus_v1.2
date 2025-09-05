<?php
// 지점별 회원 데이터 확인
header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=spoq_db;charset=utf8mb4', 'root', '');
    
    $response = [];
    
    // 1. 전체 회원 수
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM mem_main_info_tbl WHERE USE_YN = 'Y'");
    $response['total_active_members'] = $stmt->fetch()['total'];
    
    // 2. 지점별 회원 수
    $stmt = $pdo->query("
        SELECT SET_COMP_CD, SET_BCOFF_CD, COUNT(*) as member_count 
        FROM mem_main_info_tbl 
        WHERE USE_YN = 'Y' 
        GROUP BY SET_COMP_CD, SET_BCOFF_CD
    ");
    $response['members_by_branch'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 3. 얼굴 등록된 회원 수 (전체)
    $stmt = $pdo->query("SELECT COUNT(DISTINCT mem_sno) as total FROM member_faces WHERE is_active = 1");
    $response['total_face_registered'] = $stmt->fetch()['total'];
    
    // 4. 지점별 얼굴 등록된 회원 수
    $stmt = $pdo->query("
        SELECT m.SET_COMP_CD, m.SET_BCOFF_CD, COUNT(DISTINCT f.mem_sno) as face_count
        FROM member_faces f
        JOIN mem_main_info_tbl m ON f.mem_sno = m.MEM_SNO
        WHERE f.is_active = 1 AND m.USE_YN = 'Y'
        GROUP BY m.SET_COMP_CD, m.SET_BCOFF_CD
    ");
    $response['faces_by_branch'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 5. 샘플 회원 정보 (각 지점별 1명씩)
    $response['sample_members'] = [];
    $branches = [
        ['C00001', 'B00001', '강남점'],
        ['C00001', 'B00002', '서초점']
    ];
    
    foreach ($branches as $branch) {
        $stmt = $pdo->prepare("
            SELECT m.MEM_SNO, m.MEM_NM, m.SET_COMP_CD, m.SET_BCOFF_CD,
                   EXISTS(SELECT 1 FROM member_faces f WHERE f.mem_sno = m.MEM_SNO AND f.is_active = 1) as has_face
            FROM mem_main_info_tbl m
            WHERE m.SET_COMP_CD = ? AND m.SET_BCOFF_CD = ? AND m.USE_YN = 'Y'
            LIMIT 3
        ");
        $stmt->execute([$branch[0], $branch[1]]);
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($members) {
            $response['sample_members'][$branch[2]] = $members;
        }
    }
    
    // 6. API 키 확인
    $stmt = $pdo->query("SELECT name, comp_cd, bcoff_cd, LEFT(api_key, 30) as api_key_prefix FROM api_keys WHERE is_active = 1");
    $response['active_api_keys'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>