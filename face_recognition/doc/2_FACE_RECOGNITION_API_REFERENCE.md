# SpoqPlus 얼굴 인식 API 레퍼런스

## 📌 API 개요

### 기본 정보
- **Python Service URL**: `http://localhost:5002` (실제 서비스)
- **PHP Proxy URL**: `/FaceTest/*` (Frontend 접근점)
- **Protocol**: HTTP/HTTPS
- **Format**: JSON
- **Encoding**: UTF-8
- **Authentication**: Session-based (Cookie)

### 응답 형식
```json
{
    "success": true|false,
    "data": {},
    "message": "string",
    "error": "string (optional)",
    "timestamp": "ISO 8601"
}
```

## 🏗️ API 아키텍처

### 호출 경로
```
Frontend (JavaScript/Ajax)
    ↓
PHP Controller (프록시/보안/세션)
    ↓
Python Service (InsightFace 처리)
    ↓
Database (MariaDB/MSSQL)
```

### 실제 사용 경로
| 기능 | Frontend | PHP Controller | Python API |
|------|----------|----------------|------------|
| 헬스체크 | - | `/FaceTest/health` | `/api/face/health` |
| 회원 얼굴 검출 | `jsinc.php` | `/FaceTest/recognize_for_registration` | `/api/face/detect_for_registration` |
| 회원 얼굴 등록 | `info_mem.php` | `/Ttotalmain/ajax_mgr_modify_proc` | `/api/face/register` |
| 체크인 인식 | `checkin.php` | `/FaceTest/recognize_for_checkin` | `/api/face/recognize_for_checkin` |

## 🔌 API 엔드포인트

### 1. 헬스 체크
```http
GET /api/face/health
```

#### 응답 예시
```json
{
    "status": "healthy",
    "service": "InsightFace Recognition Service",
    "version": "2.0",
    "model_loaded": true,
    "database_connected": true,
    "uptime_seconds": 3600
}
```

---

### 2. 얼굴 등록
```http
POST /api/face/register
```
**PHP Proxy**: `POST /Ttotalmain/ajax_mgr_modify_proc` (회원 정보 수정 시)

#### 요청 파라미터
| 필드 | 타입 | 필수 | 설명 |
|------|------|------|------|
| image | string | ✅ | Base64 인코딩된 이미지 |
| member_id | string | ✅ | 회원번호 (실제 구현) |
| param1 | string | ❌ | 회사 코드 |
| param2 | string | ❌ | 지점 코드 |
| name | string | ❌ | 회원 이름 |
| update_if_exists | boolean | ❌ | 기존 데이터 업데이트 (기본: true) |

#### 요청 예시
```json
{
    "image": "data:image/jpeg;base64,/9j/4AAQSkZJRg...",
    "member_id": "M2024001",
    "param1": "SPOQ",
    "param2": "GANGNAM",
    "name": "홍길동",
    "update_if_exists": true
}
```

#### 응답 예시 (성공)
```json
{
    "success": true,
    "message": "얼굴이 성공적으로 등록되었습니다",
    "data": {
        "member_number": "M2024001",
        "face_id": 123,
        "quality_score": 85.5,
        "liveness_score": 0.92,
        "encoding_size": 512,
        "updated": false
    }
}
```

#### 응답 예시 (경고 포함)
```json
{
    "success": true,
    "message": "얼굴이 등록되었지만 주의사항이 있습니다",
    "data": {
        "member_number": "M2024001",
        "warnings": [
            "Liveness 검사 실패 (점수: 0.35)",
            "조명이 어두움"
        ]
    }
}
```

#### 에러 코드
| 코드 | 설명 | 해결 방법 |
|------|------|----------|
| FACE_NOT_FOUND | 얼굴을 찾을 수 없음 | 얼굴이 잘 보이도록 조정 |
| MULTIPLE_FACES | 여러 얼굴 감지됨 | 한 명만 촬영 |
| LOW_QUALITY | 이미지 품질 낮음 | 밝은 곳에서 재촬영 |
| SCREEN_DETECTED | PC 화면으로 감지됨 | 실제 얼굴로 시도 |
| DB_ERROR | 데이터베이스 오류 | IT 지원 문의 |

---

### 3. 얼굴 인식 (일반)
```http
POST /api/face/recognize
```

#### 요청 파라미터
| 필드 | 타입 | 필수 | 설명 |
|------|------|------|------|
| image | string | ✅ | Base64 인코딩된 이미지 |
| param1 | string | ❌ | 회사 코드 (필터링용) |
| param2 | string | ❌ | 지점 코드 (필터링용) |
| threshold | float | ❌ | 인식 임계값 (기본: 0.7) |

#### 요청 예시
```json
{
    "image": "data:image/jpeg;base64,/9j/4AAQSkZJRg...",
    "param1": "SPOQ",
    "param2": "GANGNAM",
    "threshold": 0.7
}
```

#### 응답 예시 (성공)
```json
{
    "success": true,
    "message": "회원을 인식했습니다",
    "data": {
        "member_number": "M2024001",
        "name": "홍길동",
        "confidence": 0.85,
        "quality_score": 82.3,
        "liveness_score": 0.88,
        "last_seen": "2025-01-17T10:30:00Z"
    }
}
```

#### 응답 예시 (실패)
```json
{
    "success": false,
    "message": "등록된 회원을 찾을 수 없습니다",
    "error": "NO_MATCH",
    "data": {
        "faces_detected": 1,
        "faces_checked": 150,
        "best_match_score": 0.45
    }
}
```

---

### 4. 회원 등록용 얼굴 검출
```http
POST /api/face/detect_for_registration
```
**PHP Proxy**: `POST /FaceTest/recognize_for_registration`

#### 요청 파라미터
| 필드 | 타입 | 필수 | 설명 |
|------|------|------|------|
| image | string | ✅ | Base64 인코딩된 이미지 |
| param1 | string | ❌ | 회사 코드 |
| param2 | string | ❌ | 지점 코드 |

#### 용도
회원 등록 시 얼굴 검출만 수행 (매칭하지 않음). 얼굴 품질, 안경 착용, Liveness 등을 체크하여 등록 가능 여부를 판단합니다.

#### 응답 예시 (성공)
```json
{
    "face_detected": true,
    "face_encoding": [0.123, -0.456, ...],  // 512차원 벡터
    "glasses_detected": false,
    "glasses_confidence": 0.15,
    "quality_score": 0.85,
    "suitable_for_registration": true,
    "liveness_check": {
        "is_live": true,
        "confidence": 0.92
    },
    "security_check": {
        "is_screen": false,
        "confidence": 0.05
    },
    "recommendations": []
}
```

---

### 5. 체크인용 얼굴 인식 (엄격)
```http
POST /api/face/recognize_for_checkin
```
**PHP Proxy**: `POST /FaceTest/recognize_for_checkin`

#### 요청 파라미터
| 필드 | 타입 | 필수 | 설명 |
|------|------|------|------|
| image | string | ✅ | Base64 인코딩된 이미지 |
| comp_cd | string | ❌ | 회사 코드 (Python에서 param1으로 변환) |
| bcoff_cd | string | ❌ | 지점 코드 (Python에서 param2으로 변환) |
| check_liveness | boolean | ❌ | Liveness 검사 (기본: true) |
| check_blink | boolean | ❌ | 눈 깜빡임 검사 (기본: false) |
| security_level | int | ❌ | 보안 레벨 (기본: 3) |

#### 요청 예시
```json
{
    "image": "data:image/jpeg;base64,/9j/4AAQSkZJRg...",
    "param1": "SPOQ",
    "param2": "GANGNAM",
    "require_liveness": true
}
```

#### 응답 예시 (성공)
```json
{
    "success": true,
    "message": "체크인 가능한 회원입니다",
    "data": {
        "member_number": "M2024001",
        "name": "홍길동",
        "confidence": 0.92,
        "security_checks": {
            "liveness_passed": true,
            "screen_detection_passed": true,
            "quality_passed": true
        },
        "available_passes": [
            {
                "pass_id": "P001",
                "pass_name": "1개월 이용권",
                "remaining_days": 15
            }
        ]
    }
}
```

#### 보안 검증 실패 응답
```json
{
    "success": false,
    "message": "카메라를 정면으로 바라봐 주세요",
    "error": "LIVENESS_FAILED",
    "data": {
        "liveness_score": 0.35,
        "required_score": 0.4,
        "recommendation": "조명이 밝은 곳에서 다시 시도해 주세요"
    }
}
```

---

### 5. 등록용 얼굴 검출
```http
POST /api/face/detect_for_registration
```

#### 요청 파라미터
| 필드 | 타입 | 필수 | 설명 |
|------|------|------|------|
| image | string | ✅ | Base64 인코딩된 이미지 |
| check_quality | boolean | ❌ | 품질 검사 (기본: true) |
| check_liveness | boolean | ❌ | Liveness 검사 (기본: false) |

#### 응답 예시
```json
{
    "success": true,
    "message": "얼굴이 감지되었습니다",
    "data": {
        "faces_count": 1,
        "face_info": {
            "bbox": [100, 100, 200, 200],
            "quality_score": 78.5,
            "blur_score": 0.15,
            "brightness": "normal",
            "face_angle": {
                "yaw": 5.2,
                "pitch": -2.1,
                "roll": 0.8
            }
        },
        "recommendations": []
    }
}
```

## 🔧 파라미터 필터링

### param1, param2 활용
- **param1**: 회사/그룹 단위 필터링
- **param2**: 지점/부서 단위 필터링
- 미입력 시 전체 회원 대상 검색

```javascript
// JavaScript 예시
const data = {
    image: base64Image,
    param1: 'SPOQ',      // SPOQ 회사 회원만
    param2: 'GANGNAM'    // 강남점 회원만
};
```

## 🔐 보안 검증 상세

### Liveness Detection
- **목적**: 사진/영상 대신 실제 얼굴 확인
- **임계값**: 0.4 (등록), 0.4 (체크인)
- **알고리즘**: LBP + 텍스처 분석

### PC 화면 감지
- **목적**: 모니터 촬영 방지
- **방법**: 격자 패턴, 주파수 분석
- **정확도**: 약 85%

### 품질 검사
- **최소 얼굴 크기**: 50x50 픽셀
- **선명도**: Laplacian 분산 > 30
- **밝기**: 자동 보정 (CLAHE)

## 📡 클라이언트 구현 예시

### JavaScript (Ajax)
```javascript
// 얼굴 등록
function registerFace(imageData, memberNumber) {
    $.ajax({
        url: 'http://localhost:5001/api/face/register',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            image: imageData,
            member_number: memberNumber,
            param1: getCompanyCode(),
            param2: getBranchCode()
        }),
        success: function(response) {
            if (response.success) {
                alert('얼굴 등록 완료');
            } else {
                alert('등록 실패: ' + response.message);
            }
        },
        error: function(xhr) {
            alert('서버 오류가 발생했습니다');
        }
    });
}

// 체크인 인식
function recognizeForCheckin(imageData) {
    return $.ajax({
        url: 'http://localhost:5001/api/face/recognize_for_checkin',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            image: imageData,
            param1: getCompanyCode(),
            param2: getBranchCode()
        })
    });
}
```

### PHP (cURL)
```php
// 얼굴 인식 요청
function recognizeFace($imageBase64) {
    $url = 'http://localhost:5001/api/face/recognize';
    
    $data = [
        'image' => $imageBase64,
        'param1' => $_SESSION['company_code'],
        'param2' => $_SESSION['branch_code']
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}
```

## 🚨 에러 처리

### HTTP 상태 코드
| 코드 | 의미 | 설명 |
|------|------|------|
| 200 | OK | 요청 성공 |
| 400 | Bad Request | 잘못된 요청 파라미터 |
| 404 | Not Found | 회원을 찾을 수 없음 |
| 422 | Unprocessable Entity | 이미지 처리 실패 |
| 500 | Internal Server Error | 서버 내부 오류 |
| 503 | Service Unavailable | 서비스 일시 중단 |

### 에러 응답 형식
```json
{
    "success": false,
    "error": "ERROR_CODE",
    "message": "사용자 친화적 메시지",
    "details": {
        "technical_message": "개발자용 상세 메시지",
        "timestamp": "2025-01-17T10:30:00Z",
        "request_id": "req_123456"
    }
}
```

## 📊 성능 지표

### 응답 시간
- 얼굴 검출: < 100ms
- 얼굴 등록: < 500ms
- 얼굴 인식: < 300ms
- 체크인 인식: < 400ms

### 정확도
- 인식률: 95%+ (양호한 조명)
- 오인식률: < 0.1%
- Liveness 정확도: 85%+

### 제한사항
- 최대 이미지 크기: 10MB
- 최소 얼굴 크기: 50x50 픽셀
- 동시 요청: 100 req/s
- 회원 수 제한: 없음 (DB 용량에 따라)

## 🔄 버전 관리

### 현재 버전: 2.0
- 2025-01-17: Liveness 임계값 조정
- 2025-01-10: MSSQL 지원 추가
- 2024-12-20: 체크인 전용 API 추가

### 하위 호환성
- v1.x API는 2025년 6월까지 지원
- 신규 기능은 v2.0 API만 지원