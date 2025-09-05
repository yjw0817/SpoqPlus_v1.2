# KIOSK API Localhost Guide

## 로컬 개발 환경 설정

### 환경 구성

#### 1. 로컬 서버 설정
```bash
# PHP 내장 서버
php spark serve --port 8080

# 또는 Apache/Nginx 설정
DocumentRoot /path/to/project/public
```

#### 2. 데이터베이스 설정
```env
# .env.development
database.default.hostname = localhost
database.default.database = spoqplus_dev
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port = 3306
```

#### 3. KIOSK 에뮬레이터 설정
```javascript
// 개발용 KIOSK 설정
const KIOSK_CONFIG = {
    api_url: 'http://localhost:8080/api/kiosk',
    branch_code: 'TEST',
    debug_mode: true
};
```

### 테스트 데이터

#### 테스트 회원 생성
```sql
INSERT INTO members (member_number, name, phone, branch_code) VALUES
('TEST001', '테스트회원1', '01012345678', 'TEST'),
('TEST002', '테스트회원2', '01087654321', 'TEST');
```

#### 테스트 이용권
```sql
INSERT INTO member_passes (member_number, pass_name, valid_from, valid_to) VALUES
('TEST001', '테스트 1개월권', NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY)),
('TEST002', '테스트 3개월권', NOW(), DATE_ADD(NOW(), INTERVAL 90 DAY));
```

### API 테스트

#### Postman Collection
```json
{
  "info": {
    "name": "KIOSK API Local",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "회원 조회",
      "request": {
        "method": "GET",
        "url": "http://localhost:8080/api/kiosk/members/search?phone=01012345678"
      }
    },
    {
      "name": "체크인",
      "request": {
        "method": "POST",
        "url": "http://localhost:8080/api/kiosk/checkin",
        "body": {
          "mode": "raw",
          "raw": "{\"member_number\":\"TEST001\",\"pass_id\":\"P001\"}"
        }
      }
    }
  ]
}
```

#### cURL 테스트
```bash
# 회원 조회
curl -X GET "http://localhost:8080/api/kiosk/members/search?phone=01012345678"

# 체크인
curl -X POST "http://localhost:8080/api/kiosk/checkin" \
  -H "Content-Type: application/json" \
  -d '{"member_number":"TEST001","pass_id":"P001"}'
```

### 디버깅

#### 로그 확인
```php
// app/Controllers/KioskController.php
log_message('debug', 'KIOSK API called: ' . json_encode($this->request->getVar()));
```

#### 로그 파일 위치
```
writable/logs/log-{date}.log
```

#### 디버그 모드
```env
# .env.development
CI_ENVIRONMENT = development
app.debug = true
```

### 문제 해결

#### CORS 이슈
```php
// app/Filters/Cors.php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

#### 포트 충돌
```bash
# 포트 확인
netstat -an | grep 8080

# 다른 포트 사용
php spark serve --port 8081
```

### Mock 서버

#### JSON Server 사용
```bash
npm install -g json-server
json-server --watch kiosk-mock.json --port 3000
```

#### Mock 데이터
```json
{
  "members": [
    {
      "id": 1,
      "member_number": "TEST001",
      "name": "테스트회원",
      "phone": "01012345678"
    }
  ],
  "checkins": []
}
```