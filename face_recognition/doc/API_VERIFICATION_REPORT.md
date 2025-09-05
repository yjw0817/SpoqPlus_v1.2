# 얼굴 인식 API 검증 보고서

## 📋 분석 일자: 2025-01-21

## 🔍 분석 범위
1. Frontend: `Views/inc/jsinc.php` - captureNewMemberPhoto() 함수
2. Frontend: `Views/tchr/teventmem/checkin.php` - 얼굴 체크인 기능
3. Backend: `Controllers/FaceTest.php` - API 프록시 컨트롤러
4. Backend: `Controllers/Ttotalmain.php` - 회원 정보 수정 시 얼굴 등록
5. Python: `face_recognition/insightface_service.py` - 실제 얼굴 인식 서비스

## ✅ 실제 API 호출 흐름

### 1. 회원 등록 시 얼굴 인식
```
[Frontend] jsinc.php
    ↓ POST /FaceTest/recognize_for_registration
[PHP Controller] FaceTest.php
    ↓ POST http://localhost:5002/api/face/detect_for_registration
[Python Service] insightface_service.py
```

**실제 데이터 구조:**
```javascript
// Frontend (jsinc.php)
{
    image: base64Data,     // 주의: 'image'로 전송 (문서는 'image_base64')
    param1: comp_cd,       // 회사 코드
    param2: bcoff_cd       // 지점 코드
}
```

### 2. 회원 정보 수정 시 얼굴 등록
```
[Frontend] info_mem.php
    ↓ POST /Ttotalmain/ajax_mgr_modify_proc
[PHP Controller] Ttotalmain.php
    ↓ POST http://localhost:5002/api/face/register
[Python Service] insightface_service.py
```

**실제 데이터 구조:**
```php
// Backend (Ttotalmain.php)
{
    'image': $imageData,
    'member_id': $postVar['mem_sno'],  // 주의: 'member_id' (문서는 'member_number')
    'param1': $param1,    // 회사 코드
    'param2': $param2     // 지점 코드
}
```

### 3. 체크인 시 얼굴 인식
```
[Frontend] checkin.php
    ↓ POST /FaceTest/recognize_for_checkin
[PHP Controller] FaceTest.php
    ↓ POST http://localhost:5002/api/face/recognize_for_checkin
[Python Service] insightface_service.py
```

**실제 데이터 구조:**
```javascript
// Frontend (checkin.php) - Ajax로 전송
{
    image_base64: imageBase64,  // 주의: 'image_base64' (일관성 없음)
    action: 'recognize',
    comp_cd: '<?php echo $comp_cd; ?>',
    bcoff_cd: '<?php echo $bcoff_cd; ?>'
}

// PHP Controller에서 Python으로 변환
{
    'image': $requestData['image'],
    'comp_cd': $comp_cd,     // Python에서 param1으로 변환
    'bcoff_cd': $bcoff_cd,   // Python에서 param2으로 변환
    'check_liveness': true,
    'check_blink': false,
    'security_level': 3
}
```

## ❌ 문서와 실제 구현의 차이점

### 1. 포트 번호 불일치
- **문서**: 5001
- **실제**: 5002 (FaceTest.php에서 확인)

### 2. 파라미터 이름 불일치

| 기능 | 문서 | 실제 구현 |
|------|------|----------|
| 회원 등록 이미지 | `image_base64` | `image` |
| 회원 번호 | `member_number` | `member_id` |
| 체크인 이미지 | `image` | Frontend는 `image_base64`, Backend는 `image`로 변환 |

### 3. API 엔드포인트 차이

| 기능 | 문서 | 실제 경로 |
|------|------|----------|
| 회원 등록 시 얼굴 검출 | `/api/face/detect_for_registration` | `/FaceTest/recognize_for_registration` → `/api/face/detect_for_registration` |
| 체크인 | `/api/face/recognize_for_checkin` | `/FaceTest/recognize_for_checkin` → `/api/face/recognize_for_checkin` |

### 4. 추가 파라미터
실제 구현에는 문서에 없는 파라미터들이 포함됨:
- `check_liveness` (체크인)
- `check_blink` (체크인)
- `security_level` (체크인)
- `quality_score` (등록)
- `glasses_detected` (등록)

## 🔧 개선 권장사항

### 1. 포트 설정 통일
- 환경 변수로 포트 관리: `FACE_PORT=5002`
- 모든 컨트롤러에서 동일한 포트 사용

### 2. 파라미터 이름 표준화
```javascript
// 권장 표준
{
    image: "base64...",        // 모든 API에서 'image' 사용
    member_id: "M2024001",     // 'member_id'로 통일
    company_code: "SPOQ",      // 명확한 이름 사용
    branch_code: "GANGNAM"     // param1, param2 대신 명확한 이름
}
```

### 3. API 경로 정리
- Frontend는 PHP 컨트롤러 호출 유지 (보안상 좋음)
- PHP 컨트롤러는 Python API의 프록시 역할
- 문서에 실제 호출 경로 명시

### 4. 응답 형식 통일
```json
{
    "success": true|false,
    "message": "설명 메시지",
    "data": {
        // 실제 데이터
    },
    "error": "에러 코드 (실패 시)",
    "timestamp": "2025-01-21T10:00:00Z"
}
```

## 📊 API 호출 매트릭스

| Frontend 파일 | PHP Controller | Python API | 용도 |
|--------------|----------------|------------|------|
| jsinc.php | FaceTest::recognize_for_registration | /api/face/detect_for_registration | 회원 등록 시 얼굴 검출 |
| info_mem.php | Ttotalmain::ajax_mgr_modify_proc | /api/face/register | 회원 정보 수정 시 얼굴 등록 |
| checkin.php | FaceTest::recognize_for_checkin | /api/face/recognize_for_checkin | 체크인 시 얼굴 인식 |

## 🎯 결론

현재 얼굴 인식 시스템은 작동하고 있지만, 문서와 실제 구현 간에 여러 불일치가 있습니다. 특히:

1. **포트 번호** (5001 vs 5002)
2. **파라미터 이름** (image vs image_base64, member_number vs member_id)
3. **API 경로** (직접 호출 vs PHP 프록시 경유)

이러한 불일치는 유지보수와 새로운 개발자의 이해를 어렵게 만들 수 있으므로, 문서 업데이트 또는 코드 표준화가 필요합니다.