# 얼굴 인식 시스템 구현 요약

## 개요
회원 등록 모달(jsinc.php)에 사진 촬영 및 얼굴 인식 기능을 추가하여 info_mem2.php와 동일한 방식으로 작동하도록 구현했습니다.

## 주요 구현 사항

### 1. UI/UX 개선
- **카메라 모달 팝업**: 사진 촬영 시 별도의 모달을 사용하여 화면 길이 문제 해결
- **z-index 관리**: 모달 위에 모달을 띄우기 위한 z-index 조정 (9060)
- **상태 표시**: 얼굴 인식 진행 상태를 실시간으로 표시

### 2. 클라이언트 구현 (jsinc.php)

#### HTML 구조
```html
<!-- 카메라 모달 -->
<div class="modal fade" id="modal_new_member_camera" style="z-index: 9060;">
    <!-- 카메라 스트림 및 캡처 UI -->
</div>

<!-- Hidden 필드들 -->
<input type="hidden" id="new_member_face_encoding_data" name="face_encoding_data" />
<input type="hidden" id="new_member_glasses_detected" name="glasses_detected" value="0" />
<input type="hidden" id="new_member_quality_score" name="quality_score" value="0" />
```

#### JavaScript 함수
```javascript
// 얼굴 인식 처리 (info_mem2와 동일한 방식)
async function processNewMemberFaceRecognition(imageBase64) {
    // 세션에서 comp_cd, bcoff_cd 가져오기
    const comp_cd = '<?= session()->get('comp_cd') ?>';
    const bcoff_cd = '<?= session()->get('bcoff_cd') ?>';
    
    const response = await fetch('/FaceTest/recognize_for_registration', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            image: base64Data,      // 'image' 필드 사용
            param1: comp_cd,        // 회사 코드
            param2: bcoff_cd        // 지점 코드
        })
    });
    
    // 응답 처리 및 hidden 필드에 데이터 저장
    document.getElementById('new_member_face_encoding_data').value = JSON.stringify(result.face_data);
}
```

### 3. 서버 구현

#### FaceTest Controller (`/FaceTest/recognize_for_registration`)
- JSON과 POST 요청 모두 지원
- 'image' 필드로 Base64 이미지 데이터 수신
- param1 (comp_cd), param2 (bcoff_cd) 파라미터 지원
- InsightFace API(`/api/face/detect_for_registration`) 호출
- 얼굴 검출 성공 시 face_encoding, glasses_detected, quality_score 반환

#### Tmemmain Controller (`ajax_mem_insert_proc`)
```php
// 얼굴 인식 데이터 수신
$mdata['face_encoding_data'] = $postVar['face_encoding_data'] ?? '';
$mdata['glasses_detected'] = $postVar['glasses_detected'] ?? '0';
$mdata['quality_score'] = $postVar['quality_score'] ?? '0';

// FaceRecognitionModel을 통한 데이터 저장
if (!empty($postVar['face_encoding_data'])) {
    $faceModel = new \App\Models\FaceRecognitionModel();
    $faceData = json_decode($postVar['face_encoding_data'], true);
    
    // Python 서버에 얼굴 등록 (param1, param2 포함)
    if (!empty($postVar['captured_photo'])) {
        $faceTest = new \App\Controllers\FaceTest();
        $pythonData = [
            'member_id' => $mem_sno['mem_sno'],
            'image' => $imageBase64,
            'param1' => $comp_cd,    // 회사 코드
            'param2' => $bcoff_cd    // 지점 코드
        ];
        $pythonResult = $faceTest->callPythonAPI('/api/face/register', 'POST', $pythonData);
    }
    
    // 로컬 DB에 얼굴 데이터 저장
    $dbData = [
        'mem_sno' => $mem_sno['mem_sno'],
        'face_encoding' => json_encode($faceData['face_encoding']),
        'glasses_detected' => $postVar['glasses_detected'],
        'quality_score' => $postVar['quality_score']
    ];
    
    $faceModel->registerFace($dbData);
}
```

### 4. 데이터 흐름

1. **사진 촬영**: 웹캠으로 사진 촬영
2. **Base64 변환**: Canvas를 통해 이미지를 Base64로 변환
3. **얼굴 인식 API 호출**: `/FaceTest/recognize_for_registration`로 전송
4. **Python 서버 처리**: InsightFace를 통한 얼굴 검출 및 인코딩
5. **클라이언트 응답 처리**: 얼굴 데이터를 hidden 필드에 저장
6. **회원 등록**: 폼 제출 시 얼굴 데이터도 함께 전송
7. **데이터베이스 저장**: `member_faces` 테이블에 얼굴 인코딩 저장

### 5. 주요 수정 사항

1. **API 필드명 수정**: `image_base64` → `image` (FaceTest controller 요구사항에 맞춤)
2. **고유 ID 사용**: 모든 요소에 `new_member_` 접두사 사용 (jsinc.php 공유 환경)
3. **모달 팝업 구현**: 화면 길이 문제 해결
4. **info_mem2 방식 준수**: 동일한 API 엔드포인트와 데이터 구조 사용

### 6. 테스트 및 검증

- 테스트 스크립트: `/test_face_recognition_flow.php`
- 얼굴 데이터 저장 확인
- 데이터 무결성 검사
- 로그 기록 확인

## 결론
info_mem2.php의 얼굴 인식 구현 방식을 정확히 따라 회원 등록 모달에 성공적으로 통합했습니다. 클라이언트와 서버 간의 데이터 흐름이 원활하게 작동하며, 얼굴 인식 데이터가 데이터베이스에 올바르게 저장됩니다.