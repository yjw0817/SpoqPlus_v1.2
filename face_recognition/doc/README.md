# SpoqPlus 프로젝트 문서

> 최종 업데이트: 2025-01-21

## 📚 문서 구조

### 🎯 얼굴 인식 시스템 (통합 문서)
기존 34개 문서를 3개 핵심 문서로 통합 완료

| 문서명 | 설명 | 주요 내용 |
|--------|------|----------|
| [1_FACE_RECOGNITION_MASTER_GUIDE.md](1_FACE_RECOGNITION_MASTER_GUIDE.md) | **마스터 가이드** | 시스템 개요, 빠른 시작, 설정, 문제해결 |
| [2_FACE_RECOGNITION_API_REFERENCE.md](2_FACE_RECOGNITION_API_REFERENCE.md) | **API 레퍼런스** | 5개 API 엔드포인트, 요청/응답, 에러코드 |
| [3_FACE_RECOGNITION_IMPLEMENTATION.md](3_FACE_RECOGNITION_IMPLEMENTATION.md) | **구현 가이드** | 회원등록, 체크인, 키오스크, 모니터링 구현 |

### 💼 시스템 문서
전체 시스템 운영에 필요한 핵심 문서

| 문서명 | 설명 |
|--------|------|
| [DATABASE_STRUCTURE.md](DATABASE_STRUCTURE.md) | 데이터베이스 테이블 구조 및 관계 |
| [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) | 프로덕션 배포 절차 및 체크리스트 |
| [SYSTEM_ARCHITECTURE.md](SYSTEM_ARCHITECTURE.md) | 전체 시스템 아키텍처 및 구성요소 |
| [TROUBLESHOOTING_GUIDE.md](TROUBLESHOOTING_GUIDE.md) | 일반적인 문제 해결 방법 |
| [MONITORING_TOOLS.md](MONITORING_TOOLS.md) | 시스템 모니터링 도구 및 설정 |

### 🏗️ 인프라 문서
인프라 구축 및 클라우드 배포 관련

| 문서명 | 설명 |
|--------|------|
| [INFRASTRUCTURE_REQUIREMENTS.md](INFRASTRUCTURE_REQUIREMENTS.md) | 하드웨어/소프트웨어 요구사항 |
| [CLOUD_DEPLOYMENT_GUIDE.md](CLOUD_DEPLOYMENT_GUIDE.md) | AWS/Azure/GCP 배포 가이드 |

### 🔗 통합 문서
외부 시스템 연동 및 통합

| 문서명 | 설명 |
|--------|------|
| [INTEGRATION_GUIDE.md](INTEGRATION_GUIDE.md) | 결제/SMS/이메일 등 외부 시스템 연동 |

### 📱 KIOSK 문서
무인 키오스크 시스템 관련

| 문서명 | 설명 |
|--------|------|
| [KIOSK_API_DOCUMENTATION.md](KIOSK_API_DOCUMENTATION.md) | KIOSK API 엔드포인트 및 사용법 |
| [KIOSK_API_BRANCH_FILTERING.md](KIOSK_API_BRANCH_FILTERING.md) | 지점별 회원 필터링 로직 |
| [KIOSK_API_LOCALHOST_GUIDE.md](KIOSK_API_LOCALHOST_GUIDE.md) | 로컬 개발 환경 설정 |

## 📋 빠른 참조

### 얼굴 인식 시스템 핵심 정보
- **Python 서비스 포트**: 5001
- **지원 DB**: MariaDB, MSSQL
- **API 엔드포인트**: 5개 (health, register, recognize, recognize_for_checkin, detect_for_registration)
- **인식 모델**: InsightFace (512차원 임베딩)
- **Liveness 임계값**: 0.4

### 주요 파일 위치
```
/face_recognition/
├── insightface_service.py     # Python 얼굴인식 서비스
├── config.py                   # 설정 파일
└── requirements.txt            # Python 패키지

/app/
├── Controllers/
│   ├── Ttotalmain.php         # 회원 관리
│   └── Teventmem.php          # 체크인
└── Views/tchr/
    ├── ttotalmain/info_mem.php    # 회원 등록 화면
    └── teventmem/checkin.php      # 체크인 화면
```

## 🔄 문서 관리 정책

### 업데이트 주기
- **얼굴 인식 문서**: 기능 변경 시 3개 통합 문서만 업데이트
- **시스템 문서**: 월 1회 검토
- **API 문서**: 변경 즉시 반영

### 문서 작성 규칙
1. 명확하고 간결한 설명
2. 실제 코드 예시 포함
3. 문제 해결 방법 제공
4. 버전 정보 명시

### 이전 문서 정리 내역 (2025-01-21)
- **통합 전**: 34개 개별 문서 (중복 다수)
- **통합 후**: 15개 문서 (Face Recognition 3개 통합 + 시스템 문서 12개)
- **삭제된 중복 문서**: FACE_RECOGNITION_*.md (여러 버전), API_REFERENCE 중복, Excel/PDF 파일 등

## 📞 문의
- 기술 문서 관련: IT 지원팀
- 시스템 운영: 운영팀
- 긴급 지원: (내부 연락망 참조)