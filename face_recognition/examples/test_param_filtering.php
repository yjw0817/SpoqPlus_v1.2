<?php
/**
 * param1/param2 필터링 테스트
 * 
 * 데이터베이스에 param1, param2 컬럼 추가 후 테스트
 */

require_once 'InsightFaceClient.php';

// 테스트 설정
$client = new InsightFaceClient('http://localhost:5002');

echo "======================================\n";
echo "param1/param2 필터링 PHP 테스트\n";
echo "======================================\n\n";

// 테스트용 이미지 (실제 이미지로 교체 필요)
$testImage = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAYEBQYFBAYGBQYHBwYIChAKCgkJChQODwwQFxQYGBcUFhYaHSUfGhsjHBYWICwgIyYnKSopGR8tMC0oMCUoKSj/2wBDAQcHBwoIChMKChMoGhYaKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCj/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k=';

// 1. 헬스 체크
echo "1. 헬스 체크\n";
$health = $client->healthCheck();
print_r($health);
echo "\n----------------------------\n\n";

// 2. 회원 등록 테스트 (param1, param2 포함)
echo "2. 회원 등록 테스트\n";

// 회사 A, 지점 1
echo "- 회사 A, 지점 1에 회원 등록\n";
$result1 = $client->registerFace(
    'MEM2025001', 
    $testImage, 
    3, 
    '테스트 등록 - 회사A 지점1',
    'COMP_A',    // param1
    'BRANCH_01'  // param2
);
print_r($result1);

// 회사 A, 지점 2
echo "\n- 회사 A, 지점 2에 회원 등록\n";
$result2 = $client->registerFace(
    'MEM2025002', 
    $testImage, 
    3, 
    '테스트 등록 - 회사A 지점2',
    'COMP_A',    // param1
    'BRANCH_02'  // param2
);
print_r($result2);

// 회사 B, 지점 1
echo "\n- 회사 B, 지점 1에 회원 등록\n";
$result3 = $client->registerFace(
    'MEM2025003', 
    $testImage, 
    3, 
    '테스트 등록 - 회사B 지점1',
    'COMP_B',    // param1
    'BRANCH_01'  // param2
);
print_r($result3);

echo "\n----------------------------\n\n";

// 3. 얼굴 인식 테스트 (다양한 필터링)
echo "3. 얼굴 인식 테스트\n";

// 필터 없이 전체 조회
echo "- 필터 없이 전체 조회\n";
$recogAll = $client->recognizeFace($testImage);
printRecognitionResult($recogAll);

// 회사 A만 필터링
echo "\n- 회사 A만 필터링\n";
$recogCompA = $client->recognizeFace($testImage, 'COMP_A');
printRecognitionResult($recogCompA);

// 회사 A의 지점 1만 필터링
echo "\n- 회사 A의 지점 1만 필터링\n";
$recogCompABranch1 = $client->recognizeFace($testImage, 'COMP_A', 'BRANCH_01');
printRecognitionResult($recogCompABranch1);

// 회사 B의 지점 1만 필터링
echo "\n- 회사 B의 지점 1만 필터링\n";
$recogCompBBranch1 = $client->recognizeFace($testImage, 'COMP_B', 'BRANCH_01');
printRecognitionResult($recogCompBBranch1);

echo "\n----------------------------\n\n";

// 4. 레거시 파라미터 호환성 테스트
echo "4. 레거시 파라미터 호환성 테스트\n";

// 기존 방식으로 호출 (comp_cd, bcoff_cd 사용)
echo "- 기존 방식으로 체크인 인식 (comp_cd, bcoff_cd)\n";
$checkinResult = $client->recognizeFaceForCheckin(
    $testImage,
    'COMP_A',    // comp_cd → param1으로 자동 변환
    'BRANCH_01'  // bcoff_cd → param2로 자동 변환
);
printCheckinResult($checkinResult);

// 레거시 recognizeFace 호출
echo "\n- 레거시 방식으로 일반 인식 (comp_cd, bcoff_cd)\n";
$legacyRecog = $client->recognizeFace($testImage, null, null, 'COMP_A', 'BRANCH_01');
printRecognitionResult($legacyRecog);

echo "\n======================================\n";
echo "테스트 완료!\n";
echo "======================================\n";

// 헬퍼 함수들
function printRecognitionResult($result) {
    if ($result['success'] && $result['matched']) {
        echo "✅ 인식 성공! ";
        echo "member_id: " . $result['member_id'] . ", ";
        echo "유사도: " . $result['similarity'] . "\n";
    } else {
        echo "❌ 인식 실패\n";
    }
}

function printCheckinResult($result) {
    if ($result['success'] && $result['matched'] && $result['checkin_allowed']) {
        echo "✅ 체크인 허용! ";
        echo "member_id: " . $result['member_id'] . ", ";
        echo "유사도: " . $result['similarity'] . "\n";
    } else {
        echo "❌ 체크인 거부\n";
        if (isset($result['security_checks'])) {
            echo "보안 검사 결과: ";
            print_r($result['security_checks']);
        }
    }
}

// 실제 환경에서 사용하는 예제
echo "\n\n--- 실제 사용 예제 ---\n";
echo '
// 세션에서 지점 정보 가져오기
$param1 = $_SESSION["comp_cd"] ?? "DEFAULT_COMP";
$param2 = $_SESSION["bcoff_cd"] ?? "DEFAULT_BRANCH";

// 회원 등록
$result = $client->registerFace(
    $memberId,
    $imageData,
    3,
    "회원 등록",
    $param1,  // 회사 코드
    $param2   // 지점 코드
);

// 얼굴 인식 (해당 지점만)
$result = $client->recognizeFace(
    $imageData,
    $param1,  // 해당 회사만
    $param2   // 해당 지점만
);
';
?>