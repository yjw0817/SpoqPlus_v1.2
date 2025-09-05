# IP 접근 제어 & 디버그 설정 가이드

## 개요
Face Recognition 서비스는 유연한 IP 접근 제어 시스템과 디버그 파일 관리 기능을 제공합니다.
- **IP 접근 제어**: 기본적으로 모든 IP에서 접근 가능하며, 필요시 특정 IP를 차단할 수 있습니다.
- **디버그 관리**: API 요청/응답 디버그 파일 생성을 제어할 수 있습니다.

## 1. IP 접근 제어

### 접근 제어 모드

#### Open 모드 (기본값)
- 모든 IP에서 접근 가능
- 별도 설정 없이 사용 가능

#### Blacklist 모드
- 특정 IP를 차단
- 내부망 IP는 항상 허용 (192.168.x.x, 172.16.x.x, 10.x.x.x, 127.0.0.1)

### IP 접근 제어 설정 방법

#### 방법 1: 환경 변수 (.env 파일)

```bash
# IP 접근 제어 모드 설정
# 'open': 모든 IP 허용 (기본값)
# 'blacklist': 특정 IP 차단
FACE_ACCESS_MODE=open

# 블랙리스트 모드일 때 차단할 IP 목록 (쉼표로 구분)
FACE_BLACKLIST_IPS=123.45.67.89,98.76.54.32

# 예시 1: 모두 허용
FACE_ACCESS_MODE=open

# 예시 2: 특정 IP 차단
FACE_ACCESS_MODE=blacklist
FACE_BLACKLIST_IPS=123.45.67.89,98.76.54.32,192.168.100.50
```

#### 방법 2: config.py 직접 수정

```python
# IP 접근 제어 설정
IP_ACCESS_CONTROL = {
    'mode': 'open',  # 'open' 또는 'blacklist'
    'blacklist_ips': [],  # 차단할 IP 목록
    'internal_ranges': [  # 내부망 IP 대역 (항상 허용)
        '192.168.0.0/16',
        '172.16.0.0/12',
        '10.0.0.0/8',
        '127.0.0.1'
    ]
}

# 예시 1: 모두 허용
IP_ACCESS_CONTROL = {
    'mode': 'open',
    'blacklist_ips': [],
    'internal_ranges': ['192.168.0.0/16', '172.16.0.0/12', '10.0.0.0/8', '127.0.0.1']
}

# 예시 2: 특정 IP 차단
IP_ACCESS_CONTROL = {
    'mode': 'blacklist',
    'blacklist_ips': ['123.45.67.89', '98.76.54.32'],
    'internal_ranges': ['192.168.0.0/16', '172.16.0.0/12', '10.0.0.0/8', '127.0.0.1']
}
```

## 2. 디버그 파일 설정

### 디버그 파일 기능
- API 요청 시 상세 정보를 파일로 저장
- 요청 헤더, 본문, 응답 등을 기록
- 문제 해결 시 유용한 정보 제공

### 디버그 설정 방법

#### 방법 1: 환경 변수 (.env 파일)

```bash
# 디버그 파일 생성 여부 (true/false)
# true: 디버그 파일 생성
# false: 디버그 파일 생성 안함 (기본값)
REQUEST_DEBUG_ENABLED=false

# 디버그 파일 저장 폴더
REQUEST_DEBUG_FOLDER=./debug

# 디버그 파일 보관 일수
DEBUG_FILE_RETENTION_DAYS=7

# 예시 1: 디버그 비활성화 (운영 환경)
REQUEST_DEBUG_ENABLED=false

# 예시 2: 디버그 활성화 (개발/테스트 환경)
REQUEST_DEBUG_ENABLED=true
REQUEST_DEBUG_FOLDER=./debug
DEBUG_FILE_RETENTION_DAYS=3
```

#### 방법 2: config.py 직접 수정

```python
# 디버그 파일 설정
DEBUG_OPTIONS = {
    'request_debug_enabled': False,  # True로 변경하면 디버그 파일 생성
    'debug_folder': './debug',       # 디버그 파일 저장 경로
    'retention_days': 7               # 파일 보관 일수
}

# 예시 1: 디버그 비활성화
DEBUG_OPTIONS = {
    'request_debug_enabled': False,
    'debug_folder': './debug',
    'retention_days': 7
}

# 예시 2: 디버그 활성화
DEBUG_OPTIONS = {
    'request_debug_enabled': True,
    'debug_folder': './debug_logs',
    'retention_days': 30
}
```

### 디버그 파일 내용
디버그 파일에는 다음 정보가 포함됩니다:
- 타임스탬프
- HTTP 메소드 및 URL
- 요청 헤더
- 요청 본문 (JSON/FormData)
- 클라이언트 IP 주소
- User-Agent 정보

파일명 형식: `request_debug_YYYYMMDD_HHMMSS_ffffff.txt`

## 3. 설정 우선순위

1. **환경 변수 (.env 파일)가 있으면 우선 적용**
2. **환경 변수가 없으면 config.py의 기본값 사용**
3. **config.py도 없으면 시스템 기본값 사용**
   - IP 접근 제어: 모두 허용 (open 모드)
   - 디버그: 비활성화

## 4. 로그 확인

### IP 접근 제어 로그
`insightface_service.log`에서 확인:

```bash
# 로그 예시
[IP_CHECK] 접속 IP: 123.45.67.89 (모드: blacklist)
[IP_CHECK] 접속 차단: 123.45.67.89 (블랙리스트)
[IP_CHECK] 접속 허용: 192.168.1.100
[IP_CHECK] 내부망 접속 허용: 192.168.1.100 (대역: 192.168.0.0/16)
```

### 디버그 파일 위치
- 기본 경로: `./debug/`
- 파일명: `request_debug_20250827_153045_123456.txt`

## 5. 주의사항

### IP 접근 제어 관련
1. **내부망 IP는 항상 허용됩니다**
   - 192.168.0.0/16
   - 172.16.0.0/12
   - 10.0.0.0/8
   - 127.0.0.1 (localhost)

2. **블랙리스트에 내부망 IP를 추가해도 차단되지 않습니다**
   - 안전을 위해 내부망은 항상 접근 가능하도록 설계

3. **X-Forwarded-For 헤더 지원**
   - 프록시나 로드밸런서 뒤에서도 실제 클라이언트 IP를 감지

### 디버그 파일 관련
1. **운영 환경에서는 디버그를 비활성화하세요**
   - 성능 영향 및 디스크 사용량 증가 가능

2. **디버그 파일에는 민감한 정보가 포함될 수 있습니다**
   - 적절한 접근 권한 설정 필요
   - 주기적인 파일 삭제 권장

3. **디스크 공간 관리**
   - `retention_days` 설정으로 오래된 파일 자동 삭제
   - 수동으로 디버그 폴더 정리 가능

## 6. 문제 해결

### IP 접근 제어 문제

#### Q: 특정 IP가 차단되지 않음
A: 다음 사항을 확인하세요:
- `FACE_ACCESS_MODE`가 `blacklist`로 설정되어 있는지
- 차단하려는 IP가 `FACE_BLACKLIST_IPS`에 정확히 입력되어 있는지
- 해당 IP가 내부망 대역에 속하지 않는지

#### Q: 모든 접속이 차단됨
A: `FACE_ACCESS_MODE`를 `open`으로 설정하거나, 설정을 제거하여 기본값(open)을 사용하세요.

### 디버그 관련 문제

#### Q: 디버그 파일이 계속 생성됨
A: `REQUEST_DEBUG_ENABLED`를 `false`로 설정하세요.

#### Q: 디버그 파일이 생성되지 않음
A: 다음을 확인하세요:
- `REQUEST_DEBUG_ENABLED`가 `true`로 설정되어 있는지
- 디버그 폴더에 쓰기 권한이 있는지
- 서비스를 재시작했는지

#### Q: 디버그 파일이 너무 많이 쌓임
A: `DEBUG_FILE_RETENTION_DAYS`를 더 작은 값으로 설정하거나, 주기적으로 수동 삭제

## 7. 권장 설정

### 개발 환경
```bash
# .env 파일
FACE_ACCESS_MODE=open
REQUEST_DEBUG_ENABLED=true
REQUEST_DEBUG_FOLDER=./debug
DEBUG_FILE_RETENTION_DAYS=3
```

### 테스트 환경
```bash
# .env 파일
FACE_ACCESS_MODE=open
REQUEST_DEBUG_ENABLED=true
REQUEST_DEBUG_FOLDER=./debug
DEBUG_FILE_RETENTION_DAYS=7
```

### 운영 환경
```bash
# .env 파일
FACE_ACCESS_MODE=blacklist
FACE_BLACKLIST_IPS=악성IP목록
REQUEST_DEBUG_ENABLED=false
```

## 8. 서비스 재시작

설정 변경 후 서비스를 재시작해야 적용됩니다:

```bash
# Python 직접 실행의 경우
# Ctrl+C로 종료 후
python insightface_service_r1.py

# 또는
python insightface_service.py

# systemd 서비스의 경우
sudo systemctl restart face-recognition

# Docker의 경우
docker restart face-recognition-container

# Windows 배치 파일 사용
run_insightface_service.bat
```

## 9. 설정 확인

서비스 시작 시 로그에서 현재 설정을 확인할 수 있습니다:

```
IP 접근 제어 모드: open
디버그 파일 생성: 비활성화
```

또는

```
IP 접근 제어 모드: blacklist
차단 IP 목록: ['123.45.67.89', '98.76.54.32']
디버그 파일 생성: 활성화
디버그 폴더: ./debug
```