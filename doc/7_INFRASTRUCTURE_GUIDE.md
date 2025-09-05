# 인프라 및 클라우드 가이드

이 문서는 시스템 인프라와 클라우드 배포 관련 문서를 통합합니다.

## 📚 인프라 문서

### 1. 클라우드 배포
- [클라우드 배포 규칙](../CLOUD_DEPLOYMENT_RULES.md)
- [백업 전략](../cloud-infrastructure/backup/backup-strategy.md)

### 2. 통합 가이드
- [통합 가이드](../INTEGRATION_GUIDE.md)

## 🏗️ 시스템 아키텍처

### 서버 구성
```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│   Web Server    │────▶│   App Server    │────▶│   DB Server     │
│  (Nginx/Apache) │     │ (PHP/CodeIgniter)│     │    (MySQL)      │
└─────────────────┘     └─────────────────┘     └─────────────────┘
         │                       │
         │                       │
         ▼                       ▼
┌─────────────────┐     ┌─────────────────┐
│  Static Assets  │     │ Face Recognition │
│   (CDN/S3)      │     │ Server (Python)  │
└─────────────────┘     └─────────────────┘
```

### 권장 사양

#### 웹/앱 서버
- **CPU**: 4 vCPU
- **RAM**: 8GB
- **Storage**: 50GB SSD
- **OS**: Ubuntu 20.04 LTS

#### 데이터베이스 서버
- **CPU**: 4 vCPU
- **RAM**: 16GB
- **Storage**: 100GB SSD (확장 가능)
- **백업**: 일일 자동 백업

#### 얼굴 인식 서버
- **CPU**: 8 vCPU (AI 연산용)
- **RAM**: 16GB
- **GPU**: 선택사항 (성능 향상)
- **Storage**: 50GB SSD
- **서비스**: InsightFace
- **API**: /api/face/register
- **세션 키**: comp_cd, bcoff_cd
- **최소 밝기**: 100
- **필터링**: param1/param2 시스템

## ☁️ 클라우드 플랫폼

### AWS 구성
- **EC2**: 애플리케이션 서버
- **RDS**: MySQL 데이터베이스
- **S3**: 정적 파일 저장소
- **CloudFront**: CDN
- **Route 53**: DNS 관리

### 보안 그룹
```
Web Server: 80, 443 (public)
App Server: 8080 (private)
DB Server: 3306 (private)
Face Recognition: 5002 (private, .env에서 FACE_PORT=5002로 설정)
```

## 🔒 보안 설정

### SSL/TLS
- Let's Encrypt 무료 인증서 사용
- 자동 갱신 설정
- TLS 1.2 이상만 허용

### 방화벽
- UFW 또는 iptables 사용
- 필요한 포트만 오픈
- fail2ban 설치

### 백업
- **데이터베이스**: 일일 백업, 30일 보관
- **파일 시스템**: 주간 백업, 4주 보관
- **얼굴 데이터**: 실시간 동기화

## 🚀 배포 프로세스

### CI/CD 파이프라인
1. Git push to main branch
2. GitHub Actions 실행
3. 테스트 실행
4. Docker 이미지 빌드
5. 스테이징 서버 배포
6. 프로덕션 승인 후 배포

### 무중단 배포
- Blue-Green 배포 전략
- 로드 밸런서 활용
- 헬스 체크 설정

## 📊 모니터링

### 시스템 모니터링
- **CPU/Memory**: CloudWatch 또는 Prometheus
- **디스크**: 80% 임계값 알림
- **네트워크**: 트래픽 모니터링

### 애플리케이션 모니터링
- **에러 로그**: Sentry 또는 ELK Stack
- **성능**: New Relic 또는 DataDog
- **가동시간**: Uptime Robot

## 🔧 유지보수

### 정기 작업
- 주간: 보안 패치 확인
- 월간: 성능 리뷰
- 분기: 용량 계획 검토

### 비상 대응
- 24/7 모니터링
- 자동 알림 시스템
- 복구 절차 문서화