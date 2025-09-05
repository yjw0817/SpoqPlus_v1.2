# SpoqPlus 얼굴 인식 시스템 마스터 가이드

## 📌 시스템 개요

SpoqPlus 피트니스 센터 관리 시스템의 InsightFace 기반 얼굴 인식 시스템입니다.

### 핵심 기능
- **회원 얼굴 등록**: 회원 가입/수정 시 얼굴 데이터 저장
- **체크인 자동화**: 얼굴 인식으로 자동 체크인 처리
- **보안 검증**: Liveness Detection, PC 화면 감지
- **지점별 회원 분리**: param1(회사), param2(지점) 기반 격리

### 시스템 구성
```
┌─────────────────┐     ┌──────────────────┐     ┌──────────────┐
│  웹 브라우저     │────▶│  PHP 서버 (CI4)  │────▶│ Python API   │
│  (JavaScript)   │◀────│  포트: 80/443    │◀────│ 포트: 5002   │
└─────────────────┘     └──────────────────┘     └──────────────┘
                                │                        │
                                ▼                        ▼
                        ┌──────────────┐         ┌──────────────┐
                        │   MariaDB    │         │  InsightFace │
                        │   or MSSQL   │         │   Models     │
                        └──────────────┘         └──────────────┘
```

## 🚀 빠른 시작 가이드

### 1. 설치 요구사항
```bash
# Python 3.8+ 필수
# CUDA 11.8 (GPU 사용 시)
# MariaDB 10.3+ 또는 MSSQL 2016+
```

### 2. Python 환경 설정
```bash
# 가상환경 생성
python -m venv venv
source venv/bin/activate  # Linux/Mac
# 또는
venv\Scripts\activate  # Windows

# 패키지 설치
pip install -r requirements.txt
```

### 3. 설정 파일 수정
```python
# face_recognition/config.py
DATABASE_CONFIG = {
    'host': 'your_db_host',
    'user': 'your_db_user',
    'password': 'your_db_password',
    'database': 'your_database',
    'port': 3306
}

# 서비스 포트 설정
SERVICE_PORT = 5002  # 실제 사용 포트
```

### 4. 서비스 실행
```bash
# Windows
cd face_recognition
run_insightface_windows.bat

# Linux/Mac
python insightface_service.py
```

### 5. 서비스 확인
```bash
# 헬스체크
curl http://localhost:5002/api/face/health

# 웹 테스트 인터페이스
http://localhost:5002/
```

## 📁 프로젝트 구조

```
SpoqPlus_Color_Admin_Except_Mobile_claude2/
├── face_recognition/               # Python 얼굴 인식 서비스
│   ├── insightface_service.py     # 메인 서비스
│   ├── config.py                  # 설정 파일
│   ├── requirements.txt           # Python 패키지
│   └── run_insightface_windows.bat # 실행 스크립트
│
├── app/                           # CodeIgniter 4 애플리케이션
│   ├── Controllers/
│   │   ├── Ttotalmain.php        # 회원 관리 컨트롤러
│   │   └── Teventmem.php         # 체크인 컨트롤러
│   │
│   ├── Views/tchr/
│   │   ├── ttotalmain/info_mem.php     # 회원 등록 화면
│   │   └── teventmem/checkin.php       # 체크인 화면
│   │
│   └── Libraries/
│       └── FaceRecognitionService.php  # PHP 클라이언트
│
├── public/js/
│   └── face-recognition-checkin.js     # JavaScript 클라이언트
│
└── face_Recognition/doc/                # 문서
    ├── 1_FACE_RECOGNITION_MASTER_GUIDE.md    # 이 문서
    ├── 2_FACE_RECOGNITION_API_REFERENCE.md   # API 문서
    └── 3_FACE_RECOGNITION_IMPLEMENTATION.md  # 구현 가이드
```

## 🔧 주요 설정값

```python
# 얼굴 인식 임계값
RECOGNITION_THRESHOLD = 0.7  # 0.6~0.8 권장

# Liveness Detection
LIVENESS_THRESHOLD = 0.4     # 완화된 설정 (0.6→0.4)

# 얼굴 크기 최소값
MIN_FACE_SIZE = 50           # 픽셀 단위

# 이미지 품질
MIN_QUALITY_SCORE = 30       # Laplacian 분산값

# 타임아웃 설정
CAPTURE_TIMEOUT = 30         # 초 단위
```

## 🔍 주요 기능별 동작 흐름

### 회원 얼굴 등록
1. 회원 정보 페이지(info_mem.php)에서 "얼굴 등록" 버튼 클릭
2. 카메라 권한 요청 및 활성화
3. 실시간 얼굴 감지 및 품질 검사
4. 자동 캡처 (수동 버튼 제거됨)
5. Liveness 검사 (실패해도 등록 가능, 경고만)
6. 얼굴 임베딩 생성 및 DB 저장
7. 기존 데이터 있으면 UPDATE 처리

### 체크인 얼굴 인식
1. 체크인 페이지(checkin.php) 접속
2. 카메라 자동 활성화
3. 얼굴 감지 시 자동 인식 시도
4. 회원 확인 및 이용권 조회 (통합 처리)
5. 이용권 1개: 즉시 체크인
6. 이용권 여러개: 선택 화면 표시
7. 체크인 완료 (10초 후 자동 닫기)

## 📊 데이터베이스 구조

### member_faces 테이블
```sql
CREATE TABLE member_faces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_number VARCHAR(50) UNIQUE,
    face_encoding TEXT,           -- 512차원 벡터
    quality_score FLOAT,
    liveness_score FLOAT,
    param1 VARCHAR(50),          -- 회사 코드
    param2 VARCHAR(50),          -- 지점 코드
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### face_recognition_logs 테이블
```sql
CREATE TABLE face_recognition_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_number VARCHAR(50),
    action_type VARCHAR(50),     -- register/recognize/checkin
    success BOOLEAN,
    confidence_score FLOAT,
    security_checks JSON,
    error_message TEXT,
    session_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🔐 보안 기능

### 현재 활성화된 보안 기능
✅ **Liveness Detection**: 실제 얼굴 여부 검증 (임계값: 0.4)
✅ **PC 화면 감지**: 모니터/화면 촬영 방지
✅ **품질 검사**: 최소 얼굴 크기 및 선명도 검증
✅ **이미지 보정**: 어두운 환경 자동 보정 (CLAHE)

### 비활성화된 기능
❌ **안경 감지**: 정확도 문제로 제거
❌ **눈 깜빡임**: 사용 편의를 위해 제거

## 🛠️ 문제 해결

### 서비스가 시작되지 않을 때
```bash
# 포트 충돌 확인
netstat -an | findstr :5002

# Python 버전 확인
python --version  # 3.8 이상 필요

# 패키지 재설치
pip install -r requirements.txt --force-reinstall
```

### 얼굴 인식이 안 될 때
1. 조명 상태 확인 (밝은 곳 권장)
2. 카메라 정면 응시
3. 얼굴 전체가 화면에 나오도록 조정
4. 브라우저 카메라 권한 확인

### 데이터베이스 연결 오류
```python
# config.py 확인
# MariaDB/MySQL 포트: 3306
# MSSQL 포트: 1433
```

## 📝 업데이트 이력

### 2025년 1월 최신 업데이트
- Liveness 임계값 완화 (0.6 → 0.4)
- 체크인 대기시간 단축 (3초 → 1초)
- 친근한 메시지로 변경
- MSSQL 지원 추가
- 수동 촬영 버튼 제거 (자동 촬영)

## 📞 지원

- 시스템 관련 문의: IT 지원팀
- 문서 위치: `/face_Recognition/doc/`
- 로그 위치: `/face_recognition/logs/`