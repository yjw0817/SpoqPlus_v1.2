# Payment API Keys 테이블 정의서

## 1. 개요

### 테이블 목적
- 기존 `api_keys` 테이블과 구분하여 결제 시스템 전용 API 키 관리
- PG/VAN 업체별 API 키 및 인증 정보 관리
- 지점별 독립적인 API 키 설정 지원
- 암호화된 민감 정보 저장 및 상세 로그 추적

### 기존 테이블과 구분
- **기존 `api_keys`**: 얼굴인식 키오스크 시스템용 (단순 구조)
- **새로운 `payment_api_keys`**: PG/VAN 결제 시스템용 (복합 구조)

## 2. payment_api_keys 테이블

### 테이블 목적
- PG/VAN 업체별 API 키 및 인증 정보 관리
- 지점별 독립적인 API 키 설정 지원
- 암호화된 민감 정보 저장

### 테이블 구조

```sql
CREATE TABLE payment_api_keys (
    PAYMENT_API_KEY_ID VARCHAR(20) NOT NULL COMMENT '결제API키ID (Primary Key)',
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
    
    PRIMARY KEY (PAYMENT_API_KEY_ID),
    UNIQUE KEY uk_payment_api_keys_provider (COMP_CD, BCOFF_CD, PROVIDER_TYPE, PROVIDER_NAME, SERVICE_TYPE),
    INDEX idx_payment_api_keys_comp_bcoff (COMP_CD, BCOFF_CD),
    INDEX idx_payment_api_keys_provider (PROVIDER_TYPE, PROVIDER_NAME),
    INDEX idx_payment_api_keys_active (IS_ACTIVE),
    INDEX idx_payment_api_keys_expire (EXPIRE_DATE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='결제 API키 관리 테이블';
```

### 컬럼 상세 설명

| 컬럼명 | 타입 | 제약조건 | 설명 |
|--------|------|----------|------|
| PAYMENT_API_KEY_ID | VARCHAR(20) | PK, NOT NULL | 결제API키 고유 식별자 (PAK + 17자리) |
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
-- 잠실점 이니시스 모바일 결제 API 키
INSERT INTO payment_api_keys (
    PAYMENT_API_KEY_ID, COMP_CD, BCOFF_CD, PROVIDER_TYPE, PROVIDER_NAME, SERVICE_TYPE,
    API_KEY_NAME, API_KEY_VALUE, SECRET_KEY, MERCHANT_ID,
    IS_ACTIVE, IS_DEFAULT, ENVIRONMENT, EXPIRE_DATE,
    ADDITIONAL_CONFIG, CREATED_BY
) VALUES (
    'PAK202501150000000001', 'C00001', 'B00001', 'PG', 'inicis', 'mobile',
    '이니시스 모바일 결제 (잠실점)', 
    AES_ENCRYPT('wpgymjs200', 'spoqplus_payment_key_2025'),
    AES_ENCRYPT('NkpsY1B4NXM5c0dNN2xYTmpYMUx6UT09', 'spoqplus_payment_key_2025'),
    'wpgymjs200',
    'Y', 'Y', 'PROD', '2025-12-31',
    '{"timeout": 30, "retry_count": 3}',
    'system'
);
```

---

## 3. payment_api_logs 테이블

### 테이블 목적
- 결제 API 키 사용 이력 추적
- 보안 감사 및 모니터링
- 결제 오류 발생 시 추적 가능

### 테이블 구조

```sql
CREATE TABLE payment_api_logs (
    LOG_ID BIGINT AUTO_INCREMENT NOT NULL COMMENT '로그ID (Primary Key)',
    PAYMENT_API_KEY_ID VARCHAR(20) NOT NULL COMMENT '결제API키ID',
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
    INDEX idx_payment_api_logs_api_key (PAYMENT_API_KEY_ID),
    INDEX idx_payment_api_logs_comp_bcoff (COMP_CD, BCOFF_CD),
    INDEX idx_payment_api_logs_date (CREATED_DATE),
    INDEX idx_payment_api_logs_transaction (TRANSACTION_ID),
    INDEX idx_payment_api_logs_success (IS_SUCCESS),
    INDEX idx_payment_api_logs_type (REQUEST_TYPE),
    
    FOREIGN KEY (PAYMENT_API_KEY_ID) REFERENCES payment_api_keys(PAYMENT_API_KEY_ID) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='결제 API키 사용 로그 테이블';
```

---

## 4. 기존 테이블과 비교

### 기존 `api_keys` 테이블 (얼굴인식 시스템용)
```sql
CREATE TABLE api_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    api_key VARCHAR(64) UNIQUE NOT NULL,
    comp_cd VARCHAR(10) NOT NULL,
    bcoff_cd VARCHAR(10) NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    -- 기타 기본 컬럼들...
);
```

### 새로운 `payment_api_keys` 테이블 (결제 시스템용)
```sql
CREATE TABLE payment_api_keys (
    PAYMENT_API_KEY_ID VARCHAR(20) NOT NULL PRIMARY KEY,
    COMP_CD VARCHAR(10) NOT NULL,
    BCOFF_CD VARCHAR(10) NOT NULL,
    PROVIDER_TYPE ENUM('PG', 'VAN') NOT NULL,
    PROVIDER_NAME VARCHAR(50) NOT NULL,
    SERVICE_TYPE VARCHAR(20) NOT NULL,
    -- 암호화된 API 키 정보...
    -- 추가 설정 정보...
);
```

### 주요 차이점

| 항목 | 기존 api_keys | 새로운 payment_api_keys |
|------|---------------|-------------------------|
| **용도** | 얼굴인식 키오스크 | PG/VAN 결제 시스템 |
| **구조** | 단순 (지점별 1개 키) | 복합 (업체별 다중 키) |
| **암호화** | 없음 | AES 암호화 |
| **제공업체** | 없음 | PG/VAN 업체별 구분 |
| **서비스타입** | 없음 | mobile/card/terminal |
| **로그** | 기본적 | 상세한 결제 로그 |

---

## 5. 마이그레이션 전략

### 1단계: 새 테이블 생성
```bash
mysql -u username -p database_name < create_payment_api_tables.sql
```

### 2단계: 기존 하드코딩 데이터 이전
```sql
-- 기존 Api.php의 하드코딩 데이터를 새 테이블로 이전
-- 잠실점: wpgymjs200 / NkpsY1B4NXM5c0dNN2xYTmpYMUx6UT09
-- 역삼점: wpgym200ys / clNSQTgxa2lLZHdMTG1DOEVaME5QZz09
-- 개발용: welcometst / QjZXWDZDRmxYUXJPYnMvelEvSjJ5QT09
```

### 3단계: 코드 수정
- Api.php 컨트롤러에서 DB 조회로 변경
- 암호화/복호화 로직 추가
- 로그 기록 로직 추가

### 4단계: 테스트 및 검증
- 기존 결제 프로세스 정상 동작 확인
- 새로운 API 키 관리 기능 테스트
- 로그 수집 및 모니터링 확인

---

## 6. 사용 방법

### API 키 조회 (복호화)
```sql
SELECT 
    PAYMENT_API_KEY_ID, 
    PROVIDER_NAME, 
    SERVICE_TYPE,
    AES_DECRYPT(API_KEY_VALUE, 'spoqplus_payment_key_2025') as decrypted_key,
    AES_DECRYPT(SECRET_KEY, 'spoqplus_payment_key_2025') as decrypted_secret
FROM payment_api_keys 
WHERE COMP_CD = 'C00001' AND BCOFF_CD = 'B00001' 
AND PROVIDER_TYPE = 'PG' AND IS_ACTIVE = 'Y';
```

### 로그 조회
```sql
SELECT * FROM payment_api_logs 
WHERE COMP_CD = 'C00001' AND BCOFF_CD = 'B00001' 
AND CREATED_DATE >= DATE_SUB(NOW(), INTERVAL 7 DAY)
ORDER BY CREATED_DATE DESC;
```

### 성능 모니터링
```sql
-- 응답시간 분석
SELECT 
    PROVIDER_NAME,
    REQUEST_TYPE,
    AVG(RESPONSE_TIME_MS) as avg_response_time,
    COUNT(*) as request_count
FROM payment_api_logs p
JOIN payment_api_keys k ON p.PAYMENT_API_KEY_ID = k.PAYMENT_API_KEY_ID
WHERE p.CREATED_DATE >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
GROUP BY PROVIDER_NAME, REQUEST_TYPE;
```

---

## 7. 보안 고려사항

### 암호화
- `API_KEY_VALUE`, `SECRET_KEY`는 AES 암호화 저장
- 암호화 키는 환경 변수로 별도 관리
- 로그의 민감 정보는 마스킹 처리

### 접근 제어
- 결제 API 키 조회/수정은 관리자만 가능
- 로그 조회는 읽기 전용으로 제한
- IP 기반 접근 제한 고려

### 데이터 보존
- 로그 데이터는 90일 후 자동 삭제
- 중요 로그는 별도 백업 저장
- 개인정보 관련 데이터는 즉시 삭제

이 테이블 구조는 기존 `api_keys` 테이블과 완전히 분리되어 결제 시스템 전용으로 PG/VAN API 키를 안전하게 관리할 수 있습니다.