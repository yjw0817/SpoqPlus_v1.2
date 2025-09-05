# 얼굴 인식 품질 검증 테스트 가이드

## 개요
이 문서는 얼굴 인식 시스템의 품질 검증을 위한 테스트 스크립트 사용법을 설명합니다.

## 테스트 스크립트

### 1. test_face_quality.py (기본 테스트)
기본적인 얼굴 품질 검증 테스트를 수행합니다.

```bash
# 기본 실행 (test_images 폴더의 이미지 자동 검색)
python test_face_quality.py

# 특정 이미지 테스트
python test_face_quality.py path/to/image.jpg
```

**기능:**
- 이미지 품질 사전 분석 (밝기, 대비, 선명도)
- 얼굴 검출 및 품질 평가
- 등록 적합성 판단
- 상세한 권장사항 제공
- 웹캠을 통한 테스트 이미지 캡처

### 2. test_face_quality_advanced.py (고급 테스트)
다양한 모드의 고급 테스트를 지원합니다.

#### 단일 이미지 테스트
```bash
python test_face_quality_advanced.py --mode single --image path/to/image.jpg
```

#### 배치 테스트 (폴더 내 모든 이미지)
```bash
python test_face_quality_advanced.py --mode batch --folder test_images
```

#### 실시간 웹캠 테스트
```bash
python test_face_quality_advanced.py --mode realtime
```
- `q`: 종료
- `s`: 현재 프레임 저장 및 테스트
- `c`: 연속 테스트 모드 토글

#### 성능 벤치마크
```bash
python test_face_quality_advanced.py --mode benchmark --image test.jpg --iterations 100
```

## API 응답 형식

### 성공 응답
```json
{
    "success": true,
    "face_detected": true,
    "suitable_for_registration": true,
    "face_encoding": [...],
    "quality_score": 0.85,
    "liveness_score": 0.92,
    "face_pose": {
        "yaw": -5.2,
        "pitch": 2.1,
        "roll": 0.8,
        "is_frontal": true
    },
    "quality_details": {
        "face_size_ratio": 0.35,
        "face_centered": true,
        "detection_confidence": 0.95,
        "overall_quality_score": 0.88
    },
    "recommendations": [],
    "processing_time_ms": 125
}
```

### 실패 응답 (얼굴 미검출)
```json
{
    "success": false,
    "face_detected": false,
    "suitable_for_registration": false,
    "recommendations": ["얼굴이 감지되지 않았습니다. 카메라를 정면으로 바라봐주세요."],
    "error": "Face detection failed"
}
```

## 품질 평가 기준

### 1. 얼굴 검출 신뢰도
- **기준**: 0.8 이상
- **미달 시**: "얼굴이 명확하게 보이지 않습니다. 조명을 밝게 해주세요."

### 2. 얼굴 크기
- **적정 범위**: 화면의 15% ~ 70% (범위 확대됨)
- **너무 작을 때** (15% 미만): "카메라에 더 가까이 와주세요."
- **너무 클 때** (70% 초과): "조금 뒤로 물러나주세요."

### 3. 얼굴 위치
- **기준**: 화면 중앙 배치
- **미달 시**: "얼굴을 화면 중앙에 맞춰주세요."

### 4. 얼굴 각도
- **Yaw (좌우)**: ±15도 이내
- **Pitch (상하)**: ±15도 이내
- **Roll (기울기)**: ±10도 이내
- **미달 시**: 구체적인 각도 조정 권장사항 제공

### 5. 전체 품질 점수
- **기준**: 0.7 이상
- **가중치**:
  - 기본 품질: 20%
  - 검출 신뢰도: 20%
  - 위치 점수: 15%
  - 크기 점수: 15%
  - 각도 점수: 30%

## 테스트 이미지 준비

### 권장 테스트 시나리오
1. **frontal_face.jpg**: 정면 얼굴 (적합)
2. **side_face.jpg**: 측면 얼굴 (부적합)
3. **small_face.jpg**: 작은 얼굴 (부적합)
4. **large_face.jpg**: 큰 얼굴 (부적합)
5. **tilted_face.jpg**: 기울어진 얼굴 (부적합)
6. **dark_image.jpg**: 어두운 조명 (부적합)
7. **bright_image.jpg**: 밝은 조명 (경우에 따라)
8. **blurry_image.jpg**: 흐릿한 이미지 (부적합)

### 웹캠으로 테스트 이미지 생성
```bash
# 기본 스크립트로 웹캠 캡처
python test_face_quality.py
# 프롬프트에서 'y' 선택

# 고급 스크립트로 실시간 캡처
python test_face_quality_advanced.py --mode realtime
# 's' 키로 저장
```

## 문제 해결

### API 연결 오류
```bash
# API 서버 실행 확인
ps aux | grep insightface_service

# 다른 포트 사용 시
python test_face_quality_advanced.py --api-url http://localhost:5003
```

### 웹캠 오류
- 웹캠 권한 확인
- 다른 프로그램에서 웹캠 사용 중인지 확인
- USB 웹캠의 경우 연결 상태 확인

### 성능 이슈
- 이미지 크기 최적화 (1920x1080 이하 권장)
- API 서버 리소스 확인
- 네트워크 지연 확인

## 결과 해석

### 적합한 이미지
- suitable_for_registration: true
- quality_score: 0.7 이상
- 모든 각도가 허용 범위 내
- recommendations 비어있음

### 부적합한 이미지
- suitable_for_registration: false
- recommendations에 구체적인 개선사항 포함
- 각 문제점에 대한 명확한 가이드 제공

## 추가 기능

### 배치 테스트 결과 분석
- 평균 품질 점수
- 처리 시간 통계
- 주요 실패 원인 분석
- 개선 권장사항 빈도

### 성능 벤치마크
- 처리량 (req/s)
- 응답 시간 분포
- 95/99 백분위 응답 시간
- 히스토그램 자동 생성