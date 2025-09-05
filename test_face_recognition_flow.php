<?php
/**
 * 얼굴 인식 데이터 흐름 테스트 스크립트
 * 회원 등록 시 얼굴 인식 데이터가 제대로 저장되는지 확인
 */

require_once '../app/Config/Database.php';
use Config\Database;

// 데이터베이스 연결
$db = Database::connect();

echo "=== 얼굴 인식 데이터 흐름 테스트 ===\n\n";

// 1. 최근 등록된 회원 확인
echo "1. 최근 등록된 회원 정보:\n";
$query = $db->query("
    SELECT m.mem_sno, m.mem_id, m.mem_nm, m.cre_datetm
    FROM member_main_info m
    WHERE m.mem_dv = 'M'
    ORDER BY m.cre_datetm DESC
    LIMIT 5
");
$members = $query->getResultArray();

foreach ($members as $member) {
    echo "   - {$member['mem_nm']} (ID: {$member['mem_id']}, SNO: {$member['mem_sno']}, 등록일: {$member['cre_datetm']})\n";
}

// 2. 얼굴 인식 데이터 확인
echo "\n2. 등록된 얼굴 인식 데이터:\n";
$query = $db->query("
    SELECT f.mem_sno, f.glasses_detected, f.quality_score, f.created_at,
           m.mem_nm, m.mem_id,
           LENGTH(f.face_encoding) as encoding_length
    FROM member_faces f
    JOIN member_main_info m ON f.mem_sno = m.mem_sno
    WHERE f.is_active = 1
    ORDER BY f.created_at DESC
    LIMIT 5
");
$faces = $query->getResultArray();

if (count($faces) > 0) {
    foreach ($faces as $face) {
        echo "   - {$face['mem_nm']} (ID: {$face['mem_id']})\n";
        echo "     SNO: {$face['mem_sno']}\n";
        echo "     안경 착용: " . ($face['glasses_detected'] ? '예' : '아니오') . "\n";
        echo "     품질 점수: {$face['quality_score']}\n";
        echo "     인코딩 크기: {$face['encoding_length']} bytes\n";
        echo "     등록일: {$face['created_at']}\n\n";
    }
} else {
    echo "   ❌ 등록된 얼굴 인식 데이터가 없습니다.\n";
}

// 3. 얼굴 인식 로그 확인
echo "\n3. 최근 얼굴 인식 시도 로그:\n";
$query = $db->query("
    SELECT log_id, mem_sno, success, confidence_score, error_message, created_at
    FROM face_recognition_logs
    ORDER BY created_at DESC
    LIMIT 5
");
$logs = $query->getResultArray();

if (count($logs) > 0) {
    foreach ($logs as $log) {
        $status = $log['success'] ? '✅ 성공' : '❌ 실패';
        echo "   - {$status} (로그 ID: {$log['log_id']}, 회원 SNO: {$log['mem_sno']})\n";
        if ($log['confidence_score'] > 0) {
            echo "     신뢰도: {$log['confidence_score']}\n";
        }
        if ($log['error_message']) {
            echo "     오류: {$log['error_message']}\n";
        }
        echo "     시간: {$log['created_at']}\n\n";
    }
} else {
    echo "   ℹ️ 얼굴 인식 로그가 없습니다.\n";
}

// 4. 데이터 무결성 검사
echo "\n4. 데이터 무결성 검사:\n";

// 얼굴 데이터가 있지만 회원 정보가 없는 경우
$query = $db->query("
    SELECT COUNT(*) as orphan_count
    FROM member_faces f
    LEFT JOIN member_main_info m ON f.mem_sno = m.mem_sno
    WHERE m.mem_sno IS NULL
");
$orphan = $query->getRowArray();
echo "   - 고아 얼굴 데이터: {$orphan['orphan_count']}개\n";

// 활성 회원 중 얼굴 데이터가 없는 경우
$query = $db->query("
    SELECT COUNT(*) as no_face_count
    FROM member_main_info m
    LEFT JOIN member_faces f ON m.mem_sno = f.mem_sno AND f.is_active = 1
    WHERE m.mem_dv = 'M' 
    AND m.mem_stat = '01'
    AND f.mem_sno IS NULL
    AND m.cre_datetm >= DATE_SUB(NOW(), INTERVAL 30 DAY)
");
$noFace = $query->getRowArray();
echo "   - 최근 30일 내 등록된 활성 회원 중 얼굴 데이터 없음: {$noFace['no_face_count']}명\n";

echo "\n=== 테스트 완료 ===\n";