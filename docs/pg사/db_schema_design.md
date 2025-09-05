# SpoqPlus DB 스키마 설계 문서

## 1. 개요
사업장별 독립적인 운영을 위한 데이터베이스 스키마 설계 문서입니다. 하지만 기존 데이터 베이스 사용으로 이부분은 제외되었고 db_schema_design_v2를 참조

## 2. 신규 테이블 설계

### 2.1 pg_config_tbl (PG 설정 테이블)

#### 테이블 정의
```sql
CREATE TABLE pg_config_tbl (
    config_sno INT AUTO_INCREMENT PRIMARY KEY COMMENT '설정일련번호',
    comp_cd VARCHAR(10) NOT NULL COMMENT '회사코드',
    bcoff_cd VARCHAR(10) NOT NULL COMMENT '지점코드',
    pg_type VARCHAR(20) NOT NULL COMMENT 'PG유형(card/mobile/kakao)',
    pg_mid VARCHAR(50) NOT NULL COMMENT 'PG상점ID',
    pg_signkey VARCHAR(200) NOT NULL COMMENT 'PG서명키',
    pg_mode ENUM('DEV', 'PROD') DEFAULT 'PROD' COMMENT '운영모드',
    pg_provider VARCHAR(20) DEFAULT 'inicis' COMMENT 'PG사(inicis/nice/kcp)',
    pg_url VARCHAR(200) COMMENT 'PG URL',
    pg_return_url VARCHAR(200) COMMENT '결제완료 URL',
    pg_cancel_url VARCHAR(200) COMMENT '결제취소 URL',
    pg_noti_url VARCHAR(200) COMMENT '결제통지 URL',
    is_active CHAR(1) DEFAULT 'Y' COMMENT '사용여부',
    cre_id VARCHAR(50) COMMENT '생성자',
    cre_datetm DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
    mod_id VARCHAR(50) COMMENT '수정자',
    mod_datetm DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
    UNIQUE KEY uk_bcoff_pg (comp_cd, bcoff_cd, pg_type),
    INDEX idx_bcoff (comp_cd, bcoff_cd),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='PG설정정보';
```

#### 컬럼 설명
- **config_sno**: 자동증가 기본키
- **comp_cd/bcoff_cd**: 회사/지점 식별자
- **pg_type**: 결제 유형 (card, mobile, kakao 등)
- **pg_mid/pg_signkey**: PG사 인증 정보
- **pg_mode**: 개발/운영 모드 구분
- **pg_provider**: PG사 구분 (inicis, nice, kcp 등)
- **pg_url 시리즈**: PG사별 URL 정보
- **is_active**: 활성화 여부

### 2.2 bank_account_tbl (은행계좌 테이블)

#### 테이블 정의
```sql
CREATE TABLE bank_account_tbl (
    account_sno INT AUTO_INCREMENT PRIMARY KEY COMMENT '계좌일련번호',
    comp_cd VARCHAR(10) NOT NULL COMMENT '회사코드',
    bcoff_cd VARCHAR(10) NOT NULL COMMENT '지점코드',
    bank_cd VARCHAR(10) COMMENT '은행코드',
    bank_nm VARCHAR(50) NOT NULL COMMENT '은행명',
    account_no VARCHAR(50) NOT NULL COMMENT '계좌번호',
    account_holder VARCHAR(50) COMMENT '예금주',
    account_type VARCHAR(20) DEFAULT 'NORMAL' COMMENT '계좌유형(NORMAL/VIRTUAL)',
    account_desc VARCHAR(200) COMMENT '계좌설명',
    display_order INT DEFAULT 0 COMMENT '표시순서',
    is_default CHAR(1) DEFAULT 'N' COMMENT '기본계좌여부',
    is_active CHAR(1) DEFAULT 'Y' COMMENT '사용여부',
    cre_id VARCHAR(50) COMMENT '생성자',
    cre_datetm DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
    mod_id VARCHAR(50) COMMENT '수정자',
    mod_datetm DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
    INDEX idx_bcoff (comp_cd, bcoff_cd),
    INDEX idx_active (is_active),
    INDEX idx_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='계좌정보';
```

#### 컬럼 설명
- **account_sno**: 자동증가 기본키
- **bank_cd**: 금융결제원 은행코드
- **account_type**: 일반계좌/가상계좌 구분
- **display_order**: UI 표시 순서
- **is_default**: 기본 선택 계좌 여부

### 2.3 pg_transaction_log (PG 거래 로그)

#### 테이블 정의
```sql
CREATE TABLE pg_transaction_log (
    log_sno BIGINT AUTO_INCREMENT PRIMARY KEY COMMENT '로그일련번호',
    comp_cd VARCHAR(10) NOT NULL COMMENT '회사코드',
    bcoff_cd VARCHAR(10) NOT NULL COMMENT '지점코드',
    pg_type VARCHAR(20) NOT NULL COMMENT 'PG유형',
    transaction_id VARCHAR(100) COMMENT 'PG거래번호',
    order_id VARCHAR(50) COMMENT '주문번호',
    amount DECIMAL(12,2) COMMENT '금액',
    status VARCHAR(20) COMMENT '상태',
    request_data JSON COMMENT '요청데이터',
    response_data JSON COMMENT '응답데이터',
    error_message TEXT COMMENT '에러메시지',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
    INDEX idx_bcoff (comp_cd, bcoff_cd),
    INDEX idx_transaction (transaction_id),
    INDEX idx_order (order_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='PG거래로그';
```

## 3. 기존 테이블 수정

### 3.1 bcoff_mgmt_tbl 수정

```sql
-- PG 및 계좌 정보 참조를 위한 컬럼 추가
ALTER TABLE bcoff_mgmt_tbl 
ADD COLUMN default_pg_config_sno INT COMMENT '기본PG설정번호' AFTER api_key,
ADD COLUMN default_account_sno INT COMMENT '기본계좌번호' AFTER default_pg_config_sno,
ADD FOREIGN KEY fk_pg_config (default_pg_config_sno) 
    REFERENCES pg_config_tbl(config_sno) ON DELETE SET NULL,
ADD FOREIGN KEY fk_bank_account (default_account_sno) 
    REFERENCES bank_account_tbl(account_sno) ON DELETE SET NULL;
```

## 4. 데이터 마이그레이션 SQL

### 4.1 PG 설정 초기 데이터

```sql
-- 잠실점 운영 설정
INSERT INTO pg_config_tbl 
(comp_cd, bcoff_cd, pg_type, pg_mid, pg_signkey, pg_mode, pg_provider, cre_id)
VALUES 
('C00001', 'B00001', 'mobile', 'wpgymjs200', 'NkpsY1B4NXM5c0dNN2xYTmpYMUx6UT09', 'PROD', 'inicis', 'SYSTEM');

-- 역삼점 운영 설정
INSERT INTO pg_config_tbl 
(comp_cd, bcoff_cd, pg_type, pg_mid, pg_signkey, pg_mode, pg_provider, cre_id)
VALUES 
('C00001', 'B00002', 'mobile', 'wpgym200ys', 'clNSQTgxa2lLZHdMTG1DOEVaME5QZz09', 'PROD', 'inicis', 'SYSTEM');

-- 공통 개발 설정
INSERT INTO pg_config_tbl 
(comp_cd, bcoff_cd, pg_type, pg_mid, pg_signkey, pg_mode, pg_provider, cre_id)
VALUES 
('C00001', 'B00000', 'mobile', 'welcometst', 'QjZXWDZDRmxYUXJPYnMvelEvSjJ5QT09', 'DEV', 'inicis', 'SYSTEM');
```

### 4.2 계좌 정보 초기 데이터

```sql
-- 잠실점 계좌
INSERT INTO bank_account_tbl 
(comp_cd, bcoff_cd, bank_cd, bank_nm, account_no, account_holder, display_order, is_default, cre_id)
VALUES 
('C00001', 'B00001', '004', '국민은행', '12321-13547-125-25', '(주)스포크플러스', 1, 'Y', 'SYSTEM'),
('C00001', 'B00001', '081', '하나은행', '8514-254-45387-11', '(주)스포크플러스', 2, 'N', 'SYSTEM');

-- 역삼점 계좌 (동일 계좌 사용 가정)
INSERT INTO bank_account_tbl 
(comp_cd, bcoff_cd, bank_cd, bank_nm, account_no, account_holder, display_order, is_default, cre_id)
VALUES 
('C00001', 'B00002', '004', '국민은행', '12321-13547-125-25', '(주)스포크플러스', 1, 'Y', 'SYSTEM'),
('C00001', 'B00002', '081', '하나은행', '8514-254-45387-11', '(주)스포크플러스', 2, 'N', 'SYSTEM');
```

## 5. ER 다이어그램

```
bcoff_mgmt_tbl
    ├── 1:N → pg_config_tbl (comp_cd, bcoff_cd)
    ├── 1:N → bank_account_tbl (comp_cd, bcoff_cd)
    └── 1:N → pg_transaction_log (comp_cd, bcoff_cd)

pg_config_tbl
    └── 1:N → pg_transaction_log (config_sno)
```

## 6. 인덱스 전략

### 6.1 조회 성능 최적화
- 복합 인덱스: (comp_cd, bcoff_cd) - 사업장별 조회
- 단일 인덱스: is_active - 활성 데이터만 조회
- 정렬 인덱스: display_order - UI 표시 순서

### 6.2 로그 조회 최적화
- 시간 기반 인덱스: created_at - 기간별 조회
- 거래 추적 인덱스: transaction_id, order_id

## 7. 보안 고려사항

### 7.1 암호화
- pg_signkey: 양방향 암호화 적용 필요
- account_no: 마스킹 처리 필요

### 7.2 접근 권한
- PG 설정: 시스템 관리자만 수정 가능
- 계좌 정보: 지점 관리자 이상 수정 가능

## 8. 백업 및 복구

### 8.1 백업 전략
- pg_config_tbl: 설정 변경 시마다 이력 보관
- bank_account_tbl: 변경 이력 테이블 별도 관리
- pg_transaction_log: 월별 아카이빙

### 8.2 복구 시나리오
- 설정 롤백 프로시저 준비
- 거래 로그 기반 재처리 기능