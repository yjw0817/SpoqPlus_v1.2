# 🚨 보안 경고: InsightFace 서버 외부 노출

## 현재 상황
InsightFace 서버(포트 5002)가 인터넷에 직접 노출되어 있으며, 외부에서 스캔/공격 시도가 발생하고 있습니다.

## 즉시 조치 사항

### 1. 방화벽 설정 (최우선)
```bash
# iptables로 외부 접근 차단 (로컬과 내부 네트워크만 허용)
sudo iptables -A INPUT -p tcp --dport 5002 -s 127.0.0.1 -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 5002 -s 192.168.0.0/24 -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 5002 -j DROP

# 설정 저장
sudo iptables-save > /etc/iptables/rules.v4
```

### 2. InsightFace 서버 설정 변경
`insightface_service.py` 수정:
```python
# 현재 (위험)
self.app.run(host='0.0.0.0', port=5002)

# 변경 후 (안전)
self.app.run(host='127.0.0.1', port=5002)  # 로컬만 허용
# 또는
self.app.run(host='192.168.0.X', port=5002)  # 특정 내부 IP만
```

### 3. Nginx 리버스 프록시 설정
```nginx
# /etc/nginx/sites-available/face-recognition
server {
    listen 80;
    server_name internal-face.company.local;
    
    # 내부 네트워크만 허용
    allow 192.168.0.0/24;
    deny all;
    
    location /api/face/ {
        proxy_pass http://127.0.0.1:5002/;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    }
}
```

### 4. 보안 모니터링 추가
```python
# insightface_service.py에 추가
import logging
from datetime import datetime

def log_suspicious_activity(request):
    """의심스러운 활동 로깅"""
    with open('/var/log/face_recognition_security.log', 'a') as f:
        f.write(f"{datetime.now()} - Suspicious: {request.remote_addr} - {request.url}\n")
    
    # IP 차단 리스트에 추가
    if request.remote_addr not in ['127.0.0.1', '192.168.0.0/24']:
        # 자동 차단 로직
        os.system(f"sudo iptables -A INPUT -s {request.remote_addr} -j DROP")
```

### 5. 접근 제어 미들웨어
```python
from functools import wraps
from flask import request, jsonify

ALLOWED_IPS = ['127.0.0.1', '192.168.0.214']  # 허용된 IP 목록

def ip_restriction(f):
    @wraps(f)
    def decorated_function(*args, **kwargs):
        client_ip = request.remote_addr
        if client_ip not in ALLOWED_IPS:
            logger.warning(f"Unauthorized access attempt from {client_ip}")
            return jsonify({'error': 'Unauthorized'}), 403
        return f(*args, **kwargs)
    return decorated_function

# API 엔드포인트에 적용
@self.app.route('/api/face/detect_for_registration', methods=['POST'])
@ip_restriction
def detect_for_registration():
    # 기존 코드
```

## 공격자 정보
- **65.49.1.66**: Linode 클라우드 (자동화 봇 의심)
- **3.132.23.201**: AWS EC2 us-east-2 (스캔 도구 의심)

## 장기 보안 강화 방안

1. **VPN 구성**: 얼굴 인식 서버는 VPN을 통해서만 접근
2. **API 키 인증**: 모든 요청에 API 키 필수
3. **Rate Limiting**: 과도한 요청 차단
4. **SSL/TLS**: 내부 통신도 암호화
5. **로그 분석**: fail2ban 등으로 자동 차단

## 확인 명령어
```bash
# 현재 연결 상태 확인
netstat -tuln | grep 5002

# 접근 시도 로그 확인
tail -f /var/log/face_recognition.log

# 방화벽 규칙 확인
sudo iptables -L -n -v
```

## 결론
현재 InsightFace 서버가 인터넷에 직접 노출되어 있어 매우 위험한 상태입니다.
**즉시 방화벽 설정과 서버 바인딩 주소를 변경**해야 합니다.

이 서버에는 민감한 생체 정보(얼굴 벡터)가 처리되므로, 외부 접근은 완전히 차단되어야 합니다.