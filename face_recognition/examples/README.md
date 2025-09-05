# Face Recognition API Client Examples

이 디렉토리는 Face Recognition API를 다양한 환경에서 사용하기 위한 클라이언트 예제들을 포함합니다.

## 📁 파일 목록

### 1. web-client.html
브라우저 기반의 웹 클라이언트입니다.
- 파일 업로드 및 카메라 캡처 지원
- 실시간 미리보기
- 모든 API 엔드포인트 테스트 가능
- 별도의 서버 설치 없이 바로 실행 가능

**사용법:**
```bash
# 브라우저에서 직접 열기
open web-client.html
# 또는
firefox web-client.html
```

### 2. python_client.py
Python 기반의 클라이언트 라이브러리 및 CLI 도구입니다.
- 클래스 기반 API 클라이언트
- 명령줄 인터페이스
- OpenCV를 이용한 카메라 지원

**설치:**
```bash
pip install requests opencv-python numpy
```

**사용법:**
```bash
# 대화형 모드
python python_client.py

# 서버 상태 확인
python python_client.py --action health

# 얼굴 등록
python python_client.py --action register --member-id MEM001 --image face.jpg

# 얼굴 인식
python python_client.py --action recognize --image test.jpg

# 카메라 데모
python python_client.py --action camera
```

### 3. curl_examples.sh
cURL 명령어를 사용한 API 호출 예제입니다.
- 대화형 셸 스크립트
- Windows PowerShell 예제 포함
- Base64 인코딩 자동 처리

**사용법:**
```bash
# 실행 권한 부여
chmod +x curl_examples.sh

# 실행
./curl_examples.sh
```

### 4. FaceRecognition.postman_collection.json
Postman에서 사용할 수 있는 API 컬렉션입니다.
- 모든 API 엔드포인트 포함
- 환경 변수 설정 가능
- 요청/응답 예제 포함
- 자동 테스트 스크립트

**사용법:**
1. Postman 열기
2. Import → Upload Files
3. FaceRecognition.postman_collection.json 선택
4. 환경 변수에서 `base_url` 설정

### 5. InsightFaceClient.php
PHP 환경을 위한 클라이언트 클래스입니다.
- 객체 지향 설계
- 에러 처리 포함
- 파일/URL/업로드 이미지 지원

**사용법:**
```php
require_once 'InsightFaceClient.php';

$client = new InsightFaceClient('http://localhost:5002');

// 서버 상태 확인
$result = $client->healthCheck();

// 얼굴 등록
$imageData = InsightFaceClient::encodeImageFile('face.jpg');
$result = $client->registerFace('MEM001', $imageData);

// 얼굴 인식
$result = $client->recognizeFace($imageData);
```

## 🔧 공통 요구사항

### API 서버
- Face Recognition API 서버가 실행 중이어야 합니다
- 기본 URL: `http://localhost:5002`
- 다른 URL을 사용하는 경우 각 클라이언트에서 설정 변경 필요

### 이미지 형식
- 지원 형식: JPEG, PNG
- 최대 크기: 5MB (권장)
- Base64 인코딩 필요

## 📝 API 엔드포인트

1. **GET /api/face/health** - 서버 상태 확인
2. **POST /api/face/register** - 얼굴 등록
3. **POST /api/face/recognize** - 얼굴 인식
4. **POST /api/face/recognize_for_checkin** - 체크인용 인식 (엄격한 보안)
5. **POST /api/face/detect_for_registration** - 등록 전 품질 검사
6. **POST /api/face/blink_detection** - 눈 깜빡임 감지

## 🔐 보안 레벨

- **레벨 1**: 낮음 - 빠른 인식, 낮은 보안
- **레벨 2**: 중간 - 균형잡힌 성능과 보안
- **레벨 3**: 높음 - 엄격한 보안, 체크인 권장

## 💡 사용 시나리오

### 1. 회원 등록
1. 품질 검사 (`detect_for_registration`)
2. 품질 통과 시 얼굴 등록 (`register`)

### 2. 일반 인증
- 단순 얼굴 인식 (`recognize`)

### 3. 출입 통제/체크인
- 엄격한 보안 검사 (`recognize_for_checkin`)
- 라이브니스 검증 포함

## 🛠️ 문제 해결

### 연결 오류
- API 서버가 실행 중인지 확인
- 방화벽 설정 확인
- 포트 5002가 열려있는지 확인

### 이미지 오류
- 이미지 형식이 JPEG/PNG인지 확인
- 이미지 크기가 적절한지 확인
- Base64 인코딩이 올바른지 확인

### 인식 실패
- 얼굴이 명확히 보이는지 확인
- 조명이 충분한지 확인
- 안경 착용 시 반사가 없는지 확인

## 📚 추가 자료

- API 문서: `../API_DOCUMENTATION.md`
- 서버 설정: `../config.py`
- 데이터베이스 스키마: `../FaceRecognition_Create_table.sql`