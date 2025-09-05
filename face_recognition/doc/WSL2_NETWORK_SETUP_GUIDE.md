# WSL2 Face Recognition 서버 네트워크 설정 가이드

## 🎯 목표
- ✅ 내부망의 모든 컴퓨터에서 Face Recognition 서버 접근 가능
- ✅ 외부 인터넷에서는 완전 차단
- ✅ WSL2 환경의 네트워크 제약 해결

## 📊 현재 환경 분석
```
WSL2 (Ubuntu)             Windows Host            내부망 PC
[Python Server]  ------>  [Port Proxy]  <------  [Web Browser]
172.21.228.59:5002        192.168.x.x:5002       192.168.x.x
```

## 🔧 설정 방법

### 1단계: .env 파일 확인
```bash
# /mnt/d/Projects/html/SpoqPlus_Color_Admin_Except_Mobile_claude2/.env
FACE_HOST=0.0.0.0  # WSL 내에서 모든 인터페이스 수신
FACE_PORT=5002
```

### 2단계: WSL에서 서버 실행
```bash
cd /mnt/d/Projects/html/SpoqPlus_Color_Admin_Except_Mobile_claude2/face_recognition
python insightface_service.py

# 다음과 같이 표시되어야 함:
# Running on http://0.0.0.0:5002
```

### 3단계: Windows PowerShell 설정 (관리자 권한)

#### 자동 설정 (권장)
```powershell
cd D:\Projects\html\SpoqPlus_Color_Admin_Except_Mobile_claude2\face_Recognition
.\setup_wsl_network.ps1
```

#### 수동 설정
```powershell
# 1. WSL IP 확인
$wslIP = wsl hostname -I
# 예: 172.21.228.59

# 2. Windows IP 확인  
ipconfig
# 예: 192.168.0.100

# 3. 포트 프록시 설정
netsh interface portproxy delete v4tov4 listenport=5002
netsh interface portproxy add v4tov4 listenport=5002 listenaddress=0.0.0.0 connectport=5002 connectaddress=172.21.228.59

# 4. 확인
netsh interface portproxy show v4tov4
```

### 4단계: Windows 방화벽 설정

#### PowerShell (관리자)
```powershell
# 내부망만 허용
New-NetFirewallRule -DisplayName "Face Recognition Internal" `
    -Direction Inbound `
    -LocalPort 5002 `
    -Protocol TCP `
    -RemoteAddress 192.168.0.0/16,172.16.0.0/12,10.0.0.0/8 `
    -Action Allow
```

#### 또는 Windows Defender 방화벽 GUI
1. Windows Defender 방화벽 > 고급 설정
2. 인바운드 규칙 > 새 규칙
3. 포트 > TCP > 5002
4. 연결 허용
5. 원격 IP 주소: 192.168.0.0/16, 172.16.0.0/12, 10.0.0.0/8

## 🧪 테스트

### 내부망 다른 PC에서
```bash
# 브라우저에서 접속
http://192.168.0.100:5002/
http://192.168.0.100:5002/api/face/health

# 또는 curl
curl http://192.168.0.100:5002/api/face/health
```

### 외부에서 (차단 확인)
```bash
# 공인 IP로 접속 시도 (실패해야 정상)
curl http://[공인IP]:5002/
# Connection refused
```

## ⚠️ 주의사항

### WSL IP 변경 문제
WSL2는 재부팅 시 IP가 변경될 수 있습니다.

**해결책 1: 자동화 스크립트**
Windows 시작 시 자동 실행되도록 작업 스케줄러에 등록:
```powershell
schtasks /create /tn "WSL Port Forward" /tr "powershell.exe -File D:\...\setup_wsl_network.ps1" /sc onstart /ru SYSTEM
```

**해결책 2: 고정 IP 설정**
`.wslconfig` 파일에서 설정 (고급)

### 보안 모니터링
```bash
# WSL에서 접근 로그 확인
tail -f /var/log/face_recognition.log

# Windows에서 연결 확인
netstat -an | findstr 5002
```

## 🔒 추가 보안 강화

### 1. API 키 인증 추가
```python
# insightface_service.py
API_KEY = os.getenv('FACE_API_KEY')

def require_api_key(f):
    @wraps(f)
    def decorated_function(*args, **kwargs):
        if request.headers.get('X-API-Key') != API_KEY:
            return jsonify({'error': 'Invalid API key'}), 401
        return f(*args, **kwargs)
    return decorated_function
```

### 2. Rate Limiting
```python
from flask_limiter import Limiter
limiter = Limiter(app, key_func=lambda: request.remote_addr)

@limiter.limit("100 per minute")
@app.route('/api/face/detect_for_registration', methods=['POST'])
```

### 3. HTTPS 설정 (선택사항)
내부망이라도 HTTPS 사용 권장

## 📋 체크리스트
- [ ] .env 파일에서 FACE_HOST=0.0.0.0 설정
- [ ] WSL에서 Python 서버 실행
- [ ] Windows 포트 프록시 설정
- [ ] Windows 방화벽 규칙 설정
- [ ] 내부망 다른 PC에서 접근 테스트
- [ ] 외부 인터넷에서 차단 확인
- [ ] 로그 모니터링 설정

## 🆘 문제 해결

### "요청한 주소는 해당 컨텍스트에서 유효하지 않습니다"
→ 특정 IP 대신 0.0.0.0 사용

### 내부망에서도 접근 안 됨
→ Windows 방화벽 확인, 포트 프록시 설정 확인

### WSL IP 찾기 실패
→ `wsl --shutdown` 후 다시 시작

### 포트 이미 사용 중
→ `netstat -ano | findstr :5002`로 프로세스 확인