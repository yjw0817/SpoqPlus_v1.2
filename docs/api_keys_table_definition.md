# API Keys 테이블 정의서

## 1. api_keys 테이블

### 테이블 목적
- PG/VAN 업체별 API 키 및 인증 정보 관리
- 지점별 독립적인 API 키 설정 지원
- 암호화된 민감 정보 저장

### 테이블 구조

```sql
CREATE TABLE api_keys (
    API_KEY_ID VARCHAR(20) NOT NULL COMMENT 'API키ID (Primary Key)',
    COMP_CD VARCHAR(10) NOT NULL COMMENT '회사코드',
    BCOFF_CD VARCHAR(10) NOT NULL COMMENT '지점코드',
    PROVIDER_TYPE ENUM('PG', 'VAN') NOT NULL COMMENT '제공업체 유형',
    PROVIDER_NAME VARCHAR(50) NOT NULL COMMENT '제공업체명 (inicis, kcp, toss, kicc, nice, ksnet)',
    SERVICE_TYPE VARCHAR(20) NOT NULL COMMENT '서비스 타입 (mobile, card, terminal)',
    
    -- API 키 정보
    API_KEY_NAME VARCHAR(100) NOT NULL COMMENT 'API키명칭',
    API_KEY_VALUE TEXT NOT NULL COMMENT 'API키값 (암호화 저장)',
    SECRET_KEY TEXT COMMENT '시크릿키 (암호화 저장)',
    MERCHANT_ID VARCHAR(100) COMMENT '가맹점ID',
    TERMINAL_ID VARCHAR(50) COMMENT '터미널ID (VAN용)',
    
    -- 설정 정보
    IS_ACTIVE ENUM('Y', 'N') DEFAULT 'Y' COMMENT '활성화 여부',
    IS_DEFAULT ENUM('Y', 'N') DEFAULT 'N' COMMENT '기본 설정 여부',
    ENVIRONMENT ENUM('DEV', 'PROD') DEFAULT 'PROD' COMMENT '환경 구분',
    
    -- 만료 및 관리 정보
    EXPIRE_DATE DATE COMMENT '만료일',
    LAST_USED_DATE DATETIME COMMENT '최종 사용일시',
    USAGE_COUNT INT DEFAULT 0 COMMENT '사용 횟수',
    
    -- 메타 정보
    CREATED_BY VARCHAR(50) COMMENT '생성자',
    CREATED_DATE DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
    UPDATED_BY VARCHAR(50) COMMENT '수정자',
    UPDATED_DATE DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
    
    -- 추가 설정 (JSON)
    ADDITIONAL_CONFIG JSON COMMENT '추가 설정 정보',
    
    PRIMARY KEY (API_KEY_ID),
    UNIQUE KEY uk_api_keys_provider (COMP_CD, BCOFF_CD, PROVIDER_TYPE, PROVIDER_NAME, SERVICE_TYPE),
    INDEX idx_api_keys_comp_bcoff (COMP_CD, BCOFF_CD),
    INDEX idx_api_keys_provider (PROVIDER_TYPE, PROVIDER_NAME),
    INDEX idx_api_keys_active (IS_ACTIVE),
    INDEX idx_api_keys_expire (EXPIRE_DATE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='API키 관리 테이블';
```

### 컬럼 상세 설명

| 컬럼명 | 타입 | 제약조건 | 설명 |
|--------|------|----------|------|
| API_KEY_ID | VARCHAR(20) | PK, NOT NULL | API키 고유 식별자 (AK + 18자리) |
| COMP_CD | VARCHAR(10) | NOT NULL | 회사코드 (C00001) |
| BCOFF_CD | VARCHAR(10) | NOT NULL | 지점코드 (B00001) |
| PROVIDER_TYPE | ENUM | NOT NULL | 제공업체 유형 (PG/VAN) |
| PROVIDER_NAME | VARCHAR(50) | NOT NULL | 제공업체명 |
| SERVICE_TYPE | VARCHAR(20) | NOT NULL | 서비스 타입 |
| API_KEY_NAME | VARCHAR(100) | NOT NULL | API키 명칭 |
| API_KEY_VALUE | TEXT | NOT NULL | 암호화된 API키 값 |
| SECRET_KEY | TEXT | NULL | 암호화된 시크릿키 |
| MERCHANT_ID | VARCHAR(100) | NULL | 가맹점 ID |
| TERMINAL_ID | VARCHAR(50) | NULL | 터미널 ID (VAN용) |
| IS_ACTIVE | ENUM | DEFAULT 'Y' | 활성화 여부 |
| IS_DEFAULT | ENUM | DEFAULT 'N' | 기본 설정 여부 |
| ENVIRONMENT | ENUM | DEFAULT 'PROD' | 환경 구분 |
| EXPIRE_DATE | DATE | NULL | 만료일 |
| LAST_USED_DATE | DATETIME | NULL | 최종 사용일시 |
| USAGE_COUNT | INT | DEFAULT 0 | 사용 횟수 |

### 데이터 예시

```sql
-- 이니시스 PG 모바일 결제 API 키
INSERT INTO api_keys (
    API_KEY_ID, COMP_CD, BCOFF_CD, PROVIDER_TYPE, PROVIDER_NAME, SERVICE_TYPE,
    API_KEY_NAME, API_KEY_VALUE, SECRET_KEY, MERCHANT_ID,
    IS_ACTIVE, IS_DEFAULT, ENVIRONMENT, EXPIRE_DATE,
    ADDITIONAL_CONFIG, CREATED_BY
) VALUES (
    'AK' + LPAD(FLOOR(RAND() * 1000000000000000000), 18, '0'),
    'C00001', 'B00001', 'PG', 'inicis', 'mobile',
    '이니시스 모바일 결제', 
    AES_ENCRYPT('wpgymjs200', 'encryption_key'),
    AES_ENCRYPT('NkpsY1B4NXM5c0dNN2xYTmpYMUx6UT09', 'encryption_key'),
    'wpgymjs200',
    'Y', 'Y', 'PROD', '2025-12-31',
    '{"timeout": 30, "retry_count": 3}',
    'admin'
);

-- KICC VAN 터미널 API 키
INSERT INTO api_keys (
    API_KEY_ID, COMP_CD, BCOFF_CD, PROVIDER_TYPE, PROVIDER_NAME, SERVICE_TYPE,
    API_KEY_NAME, API_KEY_VALUE, TERMINAL_ID,
    IS_ACTIVE, IS_DEFAULT, ENVIRONMENT, EXPIRE_DATE,
    CREATED_BY
) VALUES (
    'AK' + LPAD(FLOOR(RAND() * 1000000000000000000), 18, '0'),
    'C00001', 'B00001', 'VAN', 'kicc', 'terminal',
    'KICC VAN 터미널',
    AES_ENCRYPT('kicc_api_key_value', 'encryption_key'),
    'TERM001',
    'Y', 'Y', 'PROD', '2025-12-31',
    'admin'
);
```

---

## 2. api_key_logs 테이블

### 테이블 목적
- API 키 사용 이력 추적
- 보안 감사 및 모니터링
- 오류 발생 시 추적 가능

### 테이블 구조

```sql
CREATE TABLE api_key_logs (
    LOG_ID BIGINT AUTO_INCREMENT NOT NULL COMMENT '로그ID (Primary Key)',
    API_KEY_ID VARCHAR(20) NOT NULL COMMENT 'API키ID',
    COMP_CD VARCHAR(10) NOT NULL COMMENT '회사코드',
    BCOFF_CD VARCHAR(10) NOT NULL COMMENT '지점코드',
    
    -- 요청 정보
    REQUEST_TYPE ENUM('AUTH', 'PAYMENT', 'CANCEL', 'REFUND', 'INQUIRY') NOT NULL COMMENT '요청 타입',
    REQUEST_METHOD VARCHAR(10) NOT NULL COMMENT 'HTTP 메소드',
    REQUEST_URL VARCHAR(500) NOT NULL COMMENT '요청 URL',
    REQUEST_HEADERS JSON COMMENT '요청 헤더',
    REQUEST_BODY JSON COMMENT '요청 바디',
    
    -- 응답 정보
    RESPONSE_STATUS INT COMMENT '응답 상태코드',
    RESPONSE_HEADERS JSON COMMENT '응답 헤더',
    RESPONSE_BODY JSON COMMENT '응답 바디',
    RESPONSE_TIME_MS INT COMMENT '응답시간(밀리초)',
    
    -- 결과 정보
    IS_SUCCESS ENUM('Y', 'N') NOT NULL COMMENT '성공 여부',
    ERROR_CODE VARCHAR(20) COMMENT '오류 코드',
    ERROR_MESSAGE TEXT COMMENT '오류 메시지',
    
    -- 거래 정보
    TRANSACTION_ID VARCHAR(100) COMMENT '거래ID',
    ORDER_NO VARCHAR(100) COMMENT '주문번호',
    AMOUNT DECIMAL(15,2) COMMENT '금액',
    
    -- 클라이언트 정보
    CLIENT_IP VARCHAR(45) COMMENT '클라이언트 IP',
    USER_AGENT TEXT COMMENT 'User Agent',
    
    -- 시스템 정보
    SERVER_NAME VARCHAR(100) COMMENT '서버명',
    PROCESS_ID VARCHAR(50) COMMENT '프로세스ID',
    
    -- 메타 정보
    CREATED_DATE DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
    
    PRIMARY KEY (LOG_ID),
    INDEX idx_api_key_logs_api_key (API_KEY_ID),
    INDEX idx_api_key_logs_comp_bcoff (COMP_CD, BCOFF_CD),
    INDEX idx_api_key_logs_date (CREATED_DATE),
    INDEX idx_api_key_logs_transaction (TRANSACTION_ID),
    INDEX idx_api_key_logs_success (IS_SUCCESS),
    INDEX idx_api_key_logs_type (REQUEST_TYPE),
    
    FOREIGN KEY (API_KEY_ID) REFERENCES api_keys(API_KEY_ID) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='API키 사용 로그 테이블';
```

### 컬럼 상세 설명

| 컬럼명 | 타입 | 제약조건 | 설명 |
|--------|------|----------|------|
| LOG_ID | BIGINT | PK, AUTO_INCREMENT | 로그 고유 식별자 |
| API_KEY_ID | VARCHAR(20) | NOT NULL, FK | API키 참조 |
| COMP_CD | VARCHAR(10) | NOT NULL | 회사코드 |
| BCOFF_CD | VARCHAR(10) | NOT NULL | 지점코드 |
| REQUEST_TYPE | ENUM | NOT NULL | 요청 타입 |
| REQUEST_METHOD | VARCHAR(10) | NOT NULL | HTTP 메소드 |
| REQUEST_URL | VARCHAR(500) | NOT NULL | 요청 URL |
| REQUEST_HEADERS | JSON | NULL | 요청 헤더 정보 |
| REQUEST_BODY | JSON | NULL | 요청 바디 정보 |
| RESPONSE_STATUS | INT | NULL | HTTP 응답 상태코드 |
| RESPONSE_HEADERS | JSON | NULL | 응답 헤더 정보 |
| RESPONSE_BODY | JSON | NULL | 응답 바디 정보 |
| RESPONSE_TIME_MS | INT | NULL | 응답시간(밀리초) |
| IS_SUCCESS | ENUM | NOT NULL | 성공 여부 |
| ERROR_CODE | VARCHAR(20) | NULL | 오류 코드 |
| ERROR_MESSAGE | TEXT | NULL | 오류 메시지 |
| TRANSACTION_ID | VARCHAR(100) | NULL | 거래 고유 식별자 |
| ORDER_NO | VARCHAR(100) | NULL | 주문번호 |
| AMOUNT | DECIMAL(15,2) | NULL | 거래 금액 |
| CLIENT_IP | VARCHAR(45) | NULL | 클라이언트 IP 주소 |
| USER_AGENT | TEXT | NULL | 사용자 에이전트 |

### 데이터 예시

```sql
-- 성공적인 결제 요청 로그
INSERT INTO api_key_logs (
    API_KEY_ID, COMP_CD, BCOFF_CD, REQUEST_TYPE, REQUEST_METHOD, REQUEST_URL,
    REQUEST_HEADERS, REQUEST_BODY, RESPONSE_STATUS, RESPONSE_BODY, RESPONSE_TIME_MS,
    IS_SUCCESS, TRANSACTION_ID, ORDER_NO, AMOUNT, CLIENT_IP, USER_AGENT
) VALUES (
    'AK123456789012345678', 'C00001', 'B00001', 'PAYMENT', 'POST', 
    'https://stgstdpay.inicis.com/api/v1/formpay',
    '{"Content-Type": "application/json", "Authorization": "Bearer token"}',
    '{"mid": "wpgymjs200", "orderNumber": "ORD20250115001", "amount": "10000"}',
    200,
    '{"resultCode": "00", "resultMsg": "SUCCESS", "tid": "StdpayCARDwpgymjs2002501150000001"}',
    1250,
    'Y', 'TXN20250115001', 'ORD20250115001', 10000.00, '192.168.1.100',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
);

-- 실패한 결제 요청 로그
INSERT INTO api_key_logs (
    API_KEY_ID, COMP_CD, BCOFF_CD, REQUEST_TYPE, REQUEST_METHOD, REQUEST_URL,
    REQUEST_BODY, RESPONSE_STATUS, RESPONSE_BODY, RESPONSE_TIME_MS,
    IS_SUCCESS, ERROR_CODE, ERROR_MESSAGE, ORDER_NO, AMOUNT, CLIENT_IP
) VALUES (
    'AK123456789012345678', 'C00001', 'B00001', 'PAYMENT', 'POST',
    'https://stgstdpay.inicis.com/api/v1/formpay',
    '{"mid": "wpgymjs200", "orderNumber": "ORD20250115002", "amount": "50000"}',
    400,
    '{"resultCode": "01", "resultMsg": "INVALID_REQUEST", "errorDetail": "Invalid card number"}',
    850,
    'N', '01', 'INVALID_REQUEST: Invalid card number', 'ORD20250115002', 50000.00, '192.168.1.100'
);
```

---

## 3. 테이블 관계도

```
api_keys (1) ----< (N) api_key_logs
    |
    |-- COMP_CD, BCOFF_CD → bcoff_mgmt_tbl
    |-- 암호화된 API 키 정보 저장
    |-- 제공업체별 설정 관리
    
api_key_logs
    |-- API 사용 이력 추적
    |-- 보안 감사 로그
    |-- 성능 모니터링 데이터
```

---

## 4. 보안 고려사항

### 암호화
- `API_KEY_VALUE`, `SECRET_KEY`는 AES 암호화 저장
- 암호화 키는 환경 변수로 별도 관리
- 로그의 민감 정보는 마스킹 처리

### 접근 제어
- API 키 조회/수정은 관리자만 가능
- 로그 조회는 읽기 전용으로 제한
- IP 기반 접근 제한 고려

### 데이터 보존
- 로그 데이터는 3개월 후 자동 삭제 (정책에 따라 조정)
- 중요 로그는 별도 백업 저장
- 개인정보 관련 데이터는 즉시 삭제

---

## 5. 인덱스 최적화

### 자주 사용되는 쿼리 패턴
```sql
-- 지점별 활성 API 키 조회
SELECT * FROM api_keys WHERE COMP_CD = ? AND BCOFF_CD = ? AND IS_ACTIVE = 'Y';

-- 특정 기간 로그 조회
SELECT * FROM api_key_logs WHERE CREATED_DATE BETWEEN ? AND ?;

-- 실패 로그 조회
SELECT * FROM api_key_logs WHERE IS_SUCCESS = 'N' AND CREATED_DATE >= ?;
```

이 테이블 구조는 PG/VAN API 키를 안전하게 관리하고 모든 사용 이력을 추적할 수 있도록 설계되었습니다.