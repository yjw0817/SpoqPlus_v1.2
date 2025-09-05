# POS 로컬 에이전트

웹 브라우저와 POS 단말기 간 통신을 중계하는 로컬 서버입니다.

## 설치 방법

1. Python 3.x 설치
2. 필요한 패키지 설치:
```bash
pip install flask flask-cors pyserial
```

3. 에이전트 실행:
```bash
python pos_agent.py
```

## 설정

1. POS 연결 방식에 따라 설정:
   - **시리얼**: COM 포트 번호 확인 (장치관리자)
   - **네트워크**: POS IP 주소 확인

2. 웹 시스템에서 `http://localhost:9090`으로 통신

## API 엔드포인트

- `GET /health` - 에이전트 상태 확인
- `POST /connect` - POS 연결
- `POST /payment` - 결제 요청
- `POST /cancel` - 결제 취소

## 보안 주의사항

- 로컬(127.0.0.1)에서만 접근 가능
- HTTPS 사용 시 인증서 설정 필요
- 방화벽에서 9090 포트 허용