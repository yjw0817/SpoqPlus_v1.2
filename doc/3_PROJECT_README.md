# SpoqPlus Color Admin Project

## 프로젝트 개요
SpoqPlus는 헬스장/피트니스 센터 관리 시스템으로, 회원 관리, 출입 통제, 수업 관리 등의 기능을 제공합니다.

## 주요 기능
- 👤 회원 관리 시스템
- 📸 얼굴 인식 기반 출입 통제
- 📅 수업 및 스케줄 관리
- 💳 결제 및 이용권 관리
- 📊 통계 및 리포트

## 기술 스택
- **Backend**: PHP (CodeIgniter 4)
- **Frontend**: HTML, CSS, JavaScript, jQuery
- **Database**: MariaDB 10.3+ / MSSQL 2016+
- **얼굴 인식**: Python (InsightFace)
  - 포트: 5002 (.env 설정)
  - 성능: 인식률 92%, 안경 감지 95%
  - param1/param2 필터링
- **기타**: Redis (캐싱), WebSocket (실시간 통신)

## 프로젝트 구조
```
SpoqPlus_Color_Admin_Except_Mobile_claude2/
├── app/                    # CodeIgniter 애플리케이션
│   ├── Controllers/       # 컨트롤러
│   ├── Models/           # 모델
│   ├── Views/            # 뷰
│   └── Config/           # 설정 파일
├── public/                # 웹 공개 디렉토리
│   ├── assets/           # CSS, JS, 이미지
│   └── dist/             # 컴파일된 리소스
├── face_recognition/      # 얼굴 인식 시스템
├── doc/                   # 문서
└── vendor/               # Composer 패키지
```

## 빠른 시작
1. 프로젝트 클론
2. 데이터베이스 설정 (MariaDB/MSSQL)
3. `.env` 파일 설정
   ```bash
   # Face Recognition 설정
   FACE_HOST=localhost
   FACE_PORT=5002
   DB_TYPE=mariadb  # 또는 mssql
   ```
4. Composer 패키지 설치: `composer install`
5. 얼굴 인식 서버 실행:
   ```bash
   cd face_recognition
   pip install -r requirements_insightface.txt
   ./start_server.sh  # 또는 python insightface_service.py
   ```
   - InsightFace 서비스
   - API: /api/face/register
   - 세션 키: comp_cd, bcoff_cd
   - 최소 밝기: 100

## 문서
자세한 내용은 다음 문서를 참조하세요:
- [얼굴 인식 종합 README](../face_recognition/doc/README.md)
- [기술 가이드](../face_recognition/doc/1_FACE_RECOGNITION_GUIDE.md)
- [API 레퍼런스](../face_recognition/doc/API_REFERENCE.md)
- [배포 가이드](./2_DEPLOYMENT_GUIDE.md)
- [트러블슈팅](../face_recognition/doc/TROUBLESHOOTING_GUIDE.md)

## 최근 업데이트
- 2025-01-17: 포트 설정 및 환경변수 통합 (.env 파일)
- 2025-01-10: 카메라 접근 문제 해결 (PHP 프록시 패턴)
- 2025-01-09: 키오스크 API param1/param2 필터링 추가
- 2025-01-05: 얼굴 인식 정확도 개선 (안경 감지 26% 향상)
- 상세 내용: [개발 이력](../face_recognition/doc/DEVELOPMENT_HISTORY.md)

## 라이선스
이 프로젝트는 비공개 소프트웨어입니다.