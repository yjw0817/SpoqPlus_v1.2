# 배포 가이드

이 문서는 SpoqPlus 시스템의 배포 관련 가이드를 제공합니다.

## 📚 목차

### 1. 클라우드 배포
- [클라우드 배포 규칙](../CLOUD_DEPLOYMENT_RULES.md)

### 2. 얼굴 인식 시스템 배포
- [얼굴 인식 배포 가이드](../face_recognition/doc/DEPLOYMENT_GUIDE.md)
- [트러블슈팅 가이드](../face_recognition/doc/TROUBLESHOOTING_GUIDE.md)
- [인프라 요구사항](../face_recognition/doc/INFRASTRUCTURE_REQUIREMENTS.md)

### 3. API 키 관리
- [API 레퍼런스](../face_recognition/doc/API_REFERENCE.md)
- [KIOSK API 문서](../face_recognition/doc/KIOSK_API_DOCUMENTATION.md)

## 🚀 배포 체크리스트

### 서버 준비
- [ ] SSL 인증서 설치 (HTTPS 필수)
- [ ] PHP 7.4+ 설치
- [ ] Python 3.8+ 설치
- [ ] MySQL 5.7+ 설치
- [ ] 필요한 PHP 확장 모듈 설치

### 얼굴 인식 서버
- [ ] Python 3.8+ 설치
- [ ] 가상환경 생성: `python -m venv face_env`
- [ ] InsightFace 설치: `pip install -r requirements_insightface.txt`
- [ ] .env 파일 설정:
  - `FACE_HOST=localhost`
  - `FACE_PORT=5002`
  - `DB_TYPE=mariadb` 또는 `mssql`
- [ ] 세션 키 확인 (comp_cd, bcoff_cd 사용)
- [ ] 최소 밝기 요구사항: 100
- [ ] param1/param2 필터링 시스템
- [ ] 시작: `./start_server.sh` 또는 `python insightface_service.py`
- [ ] 자세한 내용: [얼굴 인식 배포 가이드](../face_recognition/doc/DEPLOYMENT_GUIDE.md)

### 보안 설정
- [ ] 방화벽 규칙 설정
- [ ] API 키 발급 및 관리
- [ ] CORS 설정
- [ ] 로그 모니터링 설정

### 백업 전략
- [ ] 데이터베이스 백업 설정
- [ ] 얼굴 데이터 백업 설정
- [ ] 로그 파일 백업 설정

## ⚠️ 주의사항

### HTTPS 필수
- 카메라 API는 HTTPS에서만 작동
- localhost는 예외적으로 HTTP 허용

### 포트 설정
- InsightFace 서버: 5002 포트 (.env 파일에서 FACE_PORT=5002로 설정)
- 외부 접근 시 프록시 설정 필요
- API 엔드포인트: /api/face/register

### 성능 최적화
- 얼굴 인식 서버는 별도 서버 권장
- Redis 캐싱 활용 권장
- 로드 밸런싱 고려
- 목표 성능: 인식률 > 90%, 응답시간 < 500ms

## 📞 문제 해결
배포 중 문제 발생 시 다음 문서를 참조하세요:
- [트러블슈팅 가이드](../face_recognition/doc/TROUBLESHOOTING_GUIDE.md)
- [체크포인트 로그](../CHECKPOINT_LOG.md)
- [세션 요약](../face_recognition/doc/FACE_RECOGNITION_SESSION_SUMMARY.md)
- [개발 이력](../face_recognition/doc/DEVELOPMENT_HISTORY.md)