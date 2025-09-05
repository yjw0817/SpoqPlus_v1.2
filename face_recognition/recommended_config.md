# 권장 설정 방법

## .env 파일 설정
```bash
FACE_HOST=127.0.0.1  # 로컬호스트 바인딩 (안전)
FACE_PORT=5002
```

## 실행 순서

### 1. WSL에서 서버 실행
```bash
cd /mnt/d/Projects/html/SpoqPlus_Color_Admin_Except_Mobile_claude2/face_recognition
python insightface_service.py
# 출력: Running on http://127.0.0.1:5002
```

### 2. Windows PowerShell (관리자 권한)에서 포트 포워딩
```powershell
# WSL로 포트 포워딩 설정
netsh interface portproxy add v4tov4 listenport=5002 listenaddress=0.0.0.0 connectport=5002 connectaddress=127.0.0.1

# 확인
netsh interface portproxy show v4tov4
```

### 3. 방화벽 설정
```powershell
New-NetFirewallRule -DisplayName "Face Recognition" -Direction Inbound -LocalPort 5002 -Protocol TCP -RemoteAddress 192.168.0.0/16,172.16.0.0/12,10.0.0.0/8 -Action Allow
```

## 접속 방법
- WSL 내부: http://127.0.0.1:5002/
- Windows 로컬: http://localhost:5002/
- 내부망: http://[Windows_IP]:5002/

## 이 방법의 장점
1. WSL과 Windows 간 포트 충돌 없음
2. 보안성 유지 (로컬호스트 바인딩)
3. 포트 프록시로 내부망 접근 허용