# SpoqPlus 프로젝트 문서

## 📊 문서 현황
- **전체 MD 파일**: 364개 (프로젝트 34개 + 외부 라이브러리 330개)
- **프로젝트 문서**: 34개 (통합 문서 8개 포함)
- **최종 정리 보고서**: [MD_FILES_FINAL_SUMMARY.md](./MD_FILES_FINAL_SUMMARY.md)

## 📚 문서 목록

### 1. [프로젝트 개요](./3_PROJECT_README.md)
프로젝트 소개, 기술 스택, 구조 설명

### 2. 얼굴 인식 시스템
- [종합 README](../face_recognition/doc/README.md) - 전체 가이드
- [기술 가이드](../face_recognition/doc/1_FACE_RECOGNITION_GUIDE.md) - 설치 및 사용법
- [기술 상세](../face_recognition/doc/4_FACE_RECOGNITION_TECHNICAL.md) - 구현 세부사항
- [API 레퍼런스](../face_recognition/doc/API_REFERENCE.md) - API 명세

### 3. [키오스크 API 문서](./5_KIOSK_API_DOCUMENTATION.md)
키오스크 관련 모든 API 통합 문서

### 4. [배포 가이드](./2_DEPLOYMENT_GUIDE.md)
시스템 배포 방법과 설정 가이드

### 5. [인프라 가이드](./7_INFRASTRUCTURE_GUIDE.md)
클라우드 및 인프라 구성 가이드

### 6. [개발 로그](./6_DEVELOPMENT_LOGS.md)
개발 이력과 체크포인트 기록

## 🔍 빠른 참조

### 최신 업데이트
- 2025-01-10: [카메라 문제 수정](../face_recognition/doc/FACE_RECOGNITION_FIXES_20250110.md)
- MD 파일 통합 정리 완료

### 자주 찾는 문서
- [InsightFace 설치](../INSIGHTFACE_INSTALLATION_GUIDE.md)
- [현재 시스템 상태](../face_recognition/doc/FACE_RECOGNITION_CURRENT_STATUS.md)

### 얼굴 인식 시스템 현황 (2025년 1월 17일 기준)
- **포트**: 5002 (.env 파일에서 FACE_PORT=5002)
- **서비스**: InsightFace
- **API 엔드포인트**: /api/face/register
- **세션 키**: comp_cd, bcoff_cd (기존 logged_comp_cd 대신)
- **최소 밝기**: 100
- **필터링**: param1/param2 시스템
- **성능**: 인식률 92%, 안경 감지 95%
- **업데이트**: [최신 업데이트 요약](../face_recognition/doc/UPDATE_SUMMARY_2025-01-17.md)

### 주요 문서
- [얼굴 인식 종합 README](../face_recognition/doc/README.md)
- [API 레퍼런스](../face_recognition/doc/API_REFERENCE.md)
- [트러블슈팅 가이드](../face_recognition/doc/TROUBLESHOOTING_GUIDE.md)
- [배포 가이드](../face_recognition/doc/DEPLOYMENT_GUIDE.md)
- [개발 이력](../face_recognition/doc/DEVELOPMENT_HISTORY.md)
- [체크포인트 로그](../CHECKPOINT_LOG.md)

## 📂 문서 구조
```
doc/
├── README.md                         # 이 파일
├── README.md                        # 메인 문서 인덱스
├── 2_DEPLOYMENT_GUIDE.md            # 배포 가이드
├── 3_PROJECT_README.md              # 프로젝트 개요
└── 7_INFRASTRUCTURE_GUIDE.md        # 인프라 가이드
├── 5_KIOSK_API_DOCUMENTATION.md     # 키오스크 API
├── 6_DEVELOPMENT_LOGS.md            # 개발 로그
├── 7_INFRASTRUCTURE_GUIDE.md        # 인프라 가이드
└── MD_FILES_CLEANUP_SUMMARY.md      # 정리 요약
```

## 🏷️ 태그별 문서

### #얼굴인식
- 가이드: ../face_recognition/doc/1_FACE_RECOGNITION_GUIDE.md
- 기술: ../face_recognition/doc/4_FACE_RECOGNITION_TECHNICAL.md
- 설치: ../face_recognition/doc/INSIGHTFACE_INSTALLATION_GUIDE.md

### #API
- 키오스크: ../face_recognition/doc/KIOSK_API_DOCUMENTATION.md
- API 레퍼런스: ../face_recognition/doc/API_REFERENCE.md

### #배포
- 가이드: 2, 7
- 클라우드: CLOUD_DEPLOYMENT_RULES.md

### #개발
- 로그: 6
- 체크포인트: CHECKPOINT_LOG.md

## 📝 문서 관리 가이드
1. 새 문서는 적절한 번호와 함께 doc 폴더에 추가
2. 기존 문서 수정 시 날짜와 함께 변경 이력 기록
3. 외부 라이브러리 문서는 포함하지 않음
4. 영문 작성을 기본으로 함