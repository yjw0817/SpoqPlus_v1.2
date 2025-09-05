# InsightFace 프로덕션 배포 가이드

## 📋 목차
1. [개요](#개요)
2. [사전 준비](#사전-준비)
3. [설치](#설치)
4. [실행 방법](#실행-방법)
5. [시스템 서비스 등록](#시스템-서비스-등록)
6. [모니터링](#모니터링)
7. [트러블슈팅](#트러블슈팅)

## 개요

Flask 개발 서버를 프로덕션용 WSGI 서버로 전환하여 안정성과 성능을 향상시킵니다.

### 주요 개선사항
- ✅ **성능**: 멀티 워커로 동시 처리 (4-8배 향상)
- ✅ **안정성**: 자동 재시작, 오류 복구
- ✅ **보안**: 디버그 모드 비활성화
- ✅ **관리**: systemd 서비스 관리

## 사전 준비

### 필수 패키지 확인
```bash
# Python 버전 확인 (3.8 이상)
python --version

# 필수 패키지 설치 확인
pip list | grep -E "gunicorn|waitress"
```

### 필수 패키지 설치
```bash
# requirements.txt에 이미 포함되어 있음
pip install gunicorn==23.0.0  # Linux/Mac
pip install waitress==3.0.2   # Windows
```

## 설치

### 1. 파일 구조 확인
```
face_recognition/
├── wsgi.py                    # WSGI 엔트리 포인트
├── start_production.py         # 프로덕션 시작 스크립트
├── start_production.sh         # Linux용 시작 스크립트
├── insightface.service         # systemd 서비스 파일
├── insightface_service_r1.py  # 메인 애플리케이션
└── config.py                   # 설정 파일
```

### 2. 환경 변수 설정 (.env)
```bash
# 프로덕션 모드 활성화
FACE_PRODUCTION=True
FACE_DEBUG=False

# 서버 설정
FACE_HOST=0.0.0.0
FACE_PORT=5002

# 성능 설정
FACE_WORKERS=4      # Gunicorn 워커 수
FACE_THREADS=8      # Waitress 스레드 수
FACE_TIMEOUT=120    # 요청 타임아웃 (초)
```

## 실행 방법

### 방법 1: 자동 실행 스크립트 (권장)
```bash
# OS를 자동 감지하여 적절한 서버 실행
python start_production.py
```

### 방법 2: Linux/Mac - Gunicorn 직접 실행
```bash
# 셸 스크립트 사용
./start_production.sh

# 또는 직접 실행
gunicorn wsgi:application --workers 4 --bind 0.0.0.0:5002
```

### 방법 3: Windows - Waitress 실행
```bash
# Python 스크립트로 실행
python start_production.py

# 또는 직접 실행
waitress-serve --host=0.0.0.0 --port=5002 wsgi:application
```

## 시스템 서비스 등록 (Linux)

### 1. 서비스 파일 복사
```bash
sudo cp insightface.service /etc/systemd/system/
```

### 2. 서비스 파일 수정
```bash
sudo nano /etc/systemd/system/insightface.service

# 다음 항목들을 실제 경로로 수정:
# - WorkingDirectory=/your/actual/path/face_recognition
# - ExecStart=/usr/local/bin/gunicorn (gunicorn 실제 경로)
# - User=your-username (실제 사용자명)
```

### 3. 로그 디렉토리 생성
```bash
sudo mkdir -p /var/log/insightface
sudo chown www-data:www-data /var/log/insightface
```

### 4. 서비스 활성화 및 시작
```bash
# 서비스 리로드
sudo systemctl daemon-reload

# 서비스 활성화 (부팅 시 자동 시작)
sudo systemctl enable insightface.service

# 서비스 시작
sudo systemctl start insightface.service

# 상태 확인
sudo systemctl status insightface.service
```

### 5. 서비스 관리 명령어
```bash
# 시작
sudo systemctl start insightface

# 중지
sudo systemctl stop insightface

# 재시작
sudo systemctl restart insightface

# 상태 확인
sudo systemctl status insightface

# 로그 확인
sudo journalctl -u insightface -f
```

## 모니터링

### 1. 프로세스 확인
```bash
# Gunicorn 프로세스 확인
ps aux | grep gunicorn

# 포트 확인
netstat -tlnp | grep 5002
```

### 2. 로그 확인
```bash
# systemd 로그
sudo journalctl -u insightface -f

# 애플리케이션 로그
tail -f /var/log/insightface/access.log
tail -f /var/log/insightface/error.log
```

### 3. 성능 모니터링
```bash
# CPU 및 메모리 사용량
htop

# 네트워크 연결 상태
ss -tnp | grep 5002
```

### 4. 헬스 체크
```bash
# API 상태 확인
curl http://localhost:5002/api/face/health
```

## 트러블슈팅

### 문제 1: 서버가 시작되지 않음
```bash
# 포트 사용 확인
sudo lsof -i :5002

# 다른 프로세스가 사용 중이면 종료
sudo kill -9 [PID]
```

### 문제 2: 권한 오류
```bash
# 실행 권한 부여
chmod +x start_production.sh
chmod +x start_production.py

# 로그 디렉토리 권한
sudo chown -R $USER:$USER /var/log/insightface
```

### 문제 3: 모듈을 찾을 수 없음
```bash
# 가상환경 활성화 확인
source face_env/bin/activate

# 패키지 재설치
pip install -r requirements.txt
```

### 문제 4: 메모리 부족
```bash
# 워커 수 줄이기 (.env 파일)
FACE_WORKERS=2

# 또는 config.py에서 직접 수정
WORKERS = 2
```

### 문제 5: 타임아웃 오류
```bash
# 타임아웃 늘리기 (.env 파일)
FACE_TIMEOUT=300  # 5분으로 증가
```

## 성능 최적화

### 1. 워커 수 조정
```python
# config.py 또는 .env
# 권장: CPU 코어 수 * 2 + 1
# 2코어: 5 워커
# 4코어: 9 워커 (최대 8로 제한)
FACE_WORKERS=4
```

### 2. 리버스 프록시 설정 (Nginx)
```nginx
server {
    listen 80;
    server_name your-domain.com;

    location / {
        proxy_pass http://127.0.0.1:5002;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        
        # 타임아웃 설정
        proxy_connect_timeout 120s;
        proxy_send_timeout 120s;
        proxy_read_timeout 120s;
    }
}
```

### 3. 프로세스 관리자 사용 (Supervisor)
```ini
[program:insightface]
command=/usr/local/bin/gunicorn wsgi:application --workers 4 --bind 0.0.0.0:5002
directory=/path/to/face_recognition
user=www-data
autostart=true
autorestart=true
redirect_stderr=true
stdout_logfile=/var/log/supervisor/insightface.log
```

## 보안 권장사항

1. **방화벽 설정**
```bash
# 특정 IP만 허용
sudo ufw allow from 192.168.0.0/24 to any port 5002
```

2. **HTTPS 설정**
- Nginx를 통한 SSL/TLS 터미네이션
- Let's Encrypt 인증서 사용

3. **환경 변수 보호**
```bash
# .env 파일 권한 제한
chmod 600 .env
```

4. **로그 관리**
- 로그 로테이션 설정
- 민감한 정보 로깅 방지

## 개발 서버로 되돌리기

프로덕션 서버를 중지하고 개발 서버로 되돌리려면:

```bash
# systemd 서비스 중지
sudo systemctl stop insightface

# 개발 서버 실행
python insightface_service_r1.py
```

## 지원 및 문의

문제가 발생하거나 도움이 필요한 경우:
- 로그 파일 확인: `/var/log/insightface/`
- 설정 파일 확인: `config.py`, `.env`
- 서비스 상태 확인: `systemctl status insightface`