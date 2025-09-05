# SpoqPlus 사업장 분리 마이그레이션 가이드

## 1. 마이그레이션 개요
기존 데이터베이스를 사용하기로 하여 이문서보다는 migration_guide_v2를 참고

### 1.1 목적
기존 하드코딩된 사업장 정보를 DB 기반으로 전환하여 새로운 사업장 추가 시 코드 수정 없이 운영 가능하도록 마이그레이션합니다. 

### 1.2 영향 범위
- PG 결제 설정
- 은행 계좌 정보
- 결제 프로세스
- 관리자 설정 화면

## 2. 사전 준비 사항

### 2.1 백업
```bash
# 데이터베이스 전체 백업
mysqldump -u [username] -p [database_name] > backup_$(date +%Y%m%d_%H%M%S).sql

# 주요 테이블 개별 백업
mysqldump -u [username] -p [database_name] bcoff_mgmt_tbl > bcoff_mgmt_tbl_backup.sql
mysqldump -u [username] -p [database_name] paymt_van_tbl > paymt_van_tbl_backup.sql
```

### 2.2 현재 설정 확인
```sql
-- 현재 사업장 목록 확인
SELECT COMP_CD, BCOFF_CD, BCOFF_NM, USE_YN 
FROM bcoff_mgmt_tbl 
WHERE USE_YN = 'Y' 
ORDER BY COMP_CD, BCOFF_CD;

-- API 키 설정 확인
SELECT b.COMP_CD, b.BCOFF_CD, b.BCOFF_NM, b.api_key
FROM bcoff_mgmt_tbl b
WHERE b.USE_YN = 'Y';
```

## 3. 단계별 마이그레이션

### 3.1 Phase 1: DB 스키마 생성 (30분)

#### Step 1: 신규 테이블 생성
```sql
-- 1. PG 설정 테이블 생성
CREATE TABLE pg_config_tbl (
    config_sno INT AUTO_INCREMENT PRIMARY KEY COMMENT '설정일련번호',
    comp_cd VARCHAR(10) NOT NULL COMMENT '회사코드',
    bcoff_cd VARCHAR(10) NOT NULL COMMENT '지점코드',
    pg_type VARCHAR(20) NOT NULL COMMENT 'PG유형(card/mobile/kakao)',
    pg_mid VARCHAR(50) NOT NULL COMMENT 'PG상점ID',
    pg_signkey VARCHAR(200) NOT NULL COMMENT 'PG서명키',
    pg_mode ENUM('DEV', 'PROD') DEFAULT 'PROD' COMMENT '운영모드',
    pg_provider VARCHAR(20) DEFAULT 'inicis' COMMENT 'PG사',
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

-- 2. 계좌 정보 테이블 생성
CREATE TABLE bank_account_tbl (
    account_sno INT AUTO_INCREMENT PRIMARY KEY COMMENT '계좌일련번호',
    comp_cd VARCHAR(10) NOT NULL COMMENT '회사코드',
    bcoff_cd VARCHAR(10) NOT NULL COMMENT '지점코드',
    bank_cd VARCHAR(10) COMMENT '은행코드',
    bank_nm VARCHAR(50) NOT NULL COMMENT '은행명',
    account_no VARCHAR(50) NOT NULL COMMENT '계좌번호',
    account_holder VARCHAR(50) COMMENT '예금주',
    account_type VARCHAR(20) DEFAULT 'NORMAL' COMMENT '계좌유형',
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

-- 3. PG 거래 로그 테이블 생성
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

#### Step 2: 기존 테이블 수정
```sql
-- bcoff_mgmt_tbl 수정
ALTER TABLE bcoff_mgmt_tbl 
ADD COLUMN IF NOT EXISTS default_pg_config_sno INT COMMENT '기본PG설정번호' AFTER api_key,
ADD COLUMN IF NOT EXISTS default_account_sno INT COMMENT '기본계좌번호' AFTER default_pg_config_sno;
```

### 3.2 Phase 2: 데이터 마이그레이션 (20분)

#### Step 1: PG 설정 데이터 입력
```sql
-- 운영 PG 설정
INSERT INTO pg_config_tbl 
(comp_cd, bcoff_cd, pg_type, pg_mid, pg_signkey, pg_mode, pg_provider, cre_id)
VALUES 
-- 잠실점
('C00001', 'B00001', 'mobile', 'wpgymjs200', 'NkpsY1B4NXM5c0dNN2xYTmpYMUx6UT09', 'PROD', 'inicis', 'MIGRATION'),
-- 역삼점
('C00001', 'B00002', 'mobile', 'wpgym200ys', 'clNSQTgxa2lLZHdMTG1DOEVaME5QZz09', 'PROD', 'inicis', 'MIGRATION');

-- 개발 PG 설정 (공통)
INSERT INTO pg_config_tbl 
(comp_cd, bcoff_cd, pg_type, pg_mid, pg_signkey, pg_mode, pg_provider, cre_id)
VALUES 
('C00001', 'B00000', 'mobile', 'welcometst', 'QjZXWDZDRmxYUXJPYnMvelEvSjJ5QT09', 'DEV', 'inicis', 'MIGRATION');

-- 확인
SELECT * FROM pg_config_tbl ORDER BY comp_cd, bcoff_cd;
```

#### Step 2: 계좌 정보 데이터 입력
```sql
-- 각 지점별 계좌 정보 입력
INSERT INTO bank_account_tbl 
(comp_cd, bcoff_cd, bank_cd, bank_nm, account_no, account_holder, display_order, is_default, cre_id)
VALUES 
-- 잠실점
('C00001', 'B00001', '004', '국민은행', '12321-13547-125-25', '(주)스포크플러스', 1, 'Y', 'MIGRATION'),
('C00001', 'B00001', '081', '하나은행', '8514-254-45387-11', '(주)스포크플러스', 2, 'N', 'MIGRATION'),
-- 역삼점
('C00001', 'B00002', '004', '국민은행', '12321-13547-125-25', '(주)스포크플러스', 1, 'Y', 'MIGRATION'),
('C00001', 'B00002', '081', '하나은행', '8514-254-45387-11', '(주)스포크플러스', 2, 'N', 'MIGRATION');

-- 확인
SELECT * FROM bank_account_tbl ORDER BY comp_cd, bcoff_cd, display_order;
```

#### Step 3: 기본 설정 연결
```sql
-- 각 지점의 기본 PG 및 계좌 설정
UPDATE bcoff_mgmt_tbl b
SET 
    default_pg_config_sno = (
        SELECT config_sno FROM pg_config_tbl p 
        WHERE p.comp_cd = b.COMP_CD 
        AND p.bcoff_cd = b.BCOFF_CD 
        AND p.pg_type = 'mobile' 
        AND p.is_active = 'Y'
        LIMIT 1
    ),
    default_account_sno = (
        SELECT account_sno FROM bank_account_tbl a 
        WHERE a.comp_cd = b.COMP_CD 
        AND a.bcoff_cd = b.BCOFF_CD 
        AND a.is_default = 'Y'
        LIMIT 1
    )
WHERE b.USE_YN = 'Y';

-- 확인
SELECT COMP_CD, BCOFF_CD, BCOFF_NM, default_pg_config_sno, default_account_sno
FROM bcoff_mgmt_tbl
WHERE USE_YN = 'Y';
```

### 3.3 Phase 3: 코드 배포 (1시간)

#### Step 1: Model 파일 배포
```bash
# Model 파일 업로드
app/Models/PgConfigModel.php
app/Models/BankAccountModel.php
```

#### Step 2: Controller 파일 배포
```bash
# 백업
cp app/Controllers/Api.php app/Controllers/Api.php.backup

# 신규 파일 배포
app/Controllers/Api.php (수정된 버전)
```

#### Step 3: View 파일 배포
```bash
# 백업
cp app/Views/mobile/new_event_buy_info.php app/Views/mobile/new_event_buy_info.php.backup
cp app/Views/mobile_p/mobile_pay.php app/Views/mobile_p/mobile_pay.php.backup

# 신규 파일 배포
app/Views/mobile/new_event_buy_info.php (수정된 버전)
app/Views/mobile_p/mobile_pay.php (수정된 버전)
```

### 3.4 Phase 4: 검증 (30분)

#### Step 1: 기능 테스트
1. 각 지점별 로그인 테스트
2. 상품 구매 프로세스 테스트
3. 결제 진행 테스트 (테스트 모드)
4. 계좌 정보 표시 확인

#### Step 2: 로그 확인
```sql
-- PG 거래 로그 확인
SELECT * FROM pg_transaction_log 
WHERE created_at >= NOW() - INTERVAL 1 HOUR
ORDER BY created_at DESC;

-- 오류 확인
SELECT * FROM pg_transaction_log 
WHERE status = 'ERROR' 
AND created_at >= NOW() - INTERVAL 1 DAY;
```

## 4. 롤백 절차

### 4.1 긴급 롤백
```bash
# 코드 롤백
cp app/Controllers/Api.php.backup app/Controllers/Api.php
cp app/Views/mobile/new_event_buy_info.php.backup app/Views/mobile/new_event_buy_info.php
cp app/Views/mobile_p/mobile_pay.php.backup app/Views/mobile_p/mobile_pay.php

# 캐시 클리어
php spark cache:clear
```

### 4.2 DB 롤백
```sql
-- 신규 테이블 제거 (주의: 데이터 손실)
DROP TABLE IF EXISTS pg_transaction_log;
DROP TABLE IF EXISTS bank_account_tbl;
DROP TABLE IF EXISTS pg_config_tbl;

-- bcoff_mgmt_tbl 컬럼 제거
ALTER TABLE bcoff_mgmt_tbl 
DROP COLUMN IF EXISTS default_pg_config_sno,
DROP COLUMN IF EXISTS default_account_sno;
```

## 5. 신규 사업장 추가 가이드

### 5.1 사업장 정보 등록
```sql
-- 1. 사업장 기본 정보 등록 (이미 존재한다고 가정)
-- bcoff_mgmt_tbl에 신규 지점 추가

-- 2. PG 설정 추가
INSERT INTO pg_config_tbl 
(comp_cd, bcoff_cd, pg_type, pg_mid, pg_signkey, pg_mode, pg_provider, cre_id)
VALUES 
('C00001', 'B00003', 'mobile', '[신규PG상점ID]', '[신규PG서명키]', 'PROD', 'inicis', 'ADMIN');

-- 3. 계좌 정보 추가
INSERT INTO bank_account_tbl 
(comp_cd, bcoff_cd, bank_cd, bank_nm, account_no, account_holder, display_order, is_default, cre_id)
VALUES 
('C00001', 'B00003', '004', '국민은행', '[계좌번호]', '[예금주]', 1, 'Y', 'ADMIN');

-- 4. 기본 설정 연결
UPDATE bcoff_mgmt_tbl 
SET 
    default_pg_config_sno = (SELECT config_sno FROM pg_config_tbl WHERE comp_cd='C00001' AND bcoff_cd='B00003' LIMIT 1),
    default_account_sno = (SELECT account_sno FROM bank_account_tbl WHERE comp_cd='C00001' AND bcoff_cd='B00003' AND is_default='Y' LIMIT 1)
WHERE COMP_CD='C00001' AND BCOFF_CD='B00003';
```

### 5.2 설정 확인
```sql
-- 신규 사업장 설정 확인
SELECT 
    b.BCOFF_NM,
    p.pg_mid,
    p.pg_mode,
    a.bank_nm,
    a.account_no
FROM bcoff_mgmt_tbl b
LEFT JOIN pg_config_tbl p ON b.default_pg_config_sno = p.config_sno
LEFT JOIN bank_account_tbl a ON b.default_account_sno = a.account_sno
WHERE b.COMP_CD='C00001' AND b.BCOFF_CD='B00003';
```

## 6. 문제 해결 가이드

### 6.1 일반적인 문제
1. **PG 설정을 찾을 수 없음**
   - pg_config_tbl에 해당 지점 설정 확인
   - is_active = 'Y' 확인
   
2. **계좌 정보가 표시되지 않음**
   - bank_account_tbl 데이터 확인
   - is_active = 'Y' 확인

3. **결제 실패**
   - pg_transaction_log 확인
   - error_message 컬럼 확인

### 6.2 로그 분석
```sql
-- 최근 오류 로그
SELECT 
    log_sno,
    comp_cd,
    bcoff_cd,
    order_id,
    status,
    error_message,
    created_at
FROM pg_transaction_log
WHERE status IN ('ERROR', 'FAIL')
ORDER BY created_at DESC
LIMIT 20;
```

## 7. 모니터링

### 7.1 일일 점검
```sql
-- 일일 거래 현황
SELECT 
    DATE(created_at) as date,
    comp_cd,
    bcoff_cd,
    COUNT(*) as total_count,
    SUM(CASE WHEN status='SUCCESS' THEN 1 ELSE 0 END) as success_count,
    SUM(CASE WHEN status IN ('ERROR','FAIL') THEN 1 ELSE 0 END) as fail_count
FROM pg_transaction_log
WHERE created_at >= CURDATE()
GROUP BY DATE(created_at), comp_cd, bcoff_cd;
```

### 7.2 주간 보고
```sql
-- 주간 사업장별 거래 통계
SELECT 
    b.BCOFF_NM,
    COUNT(l.log_sno) as transaction_count,
    SUM(l.amount) as total_amount,
    AVG(l.amount) as avg_amount
FROM bcoff_mgmt_tbl b
LEFT JOIN pg_transaction_log l ON b.COMP_CD = l.comp_cd AND b.BCOFF_CD = l.bcoff_cd
WHERE l.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
AND l.status = 'SUCCESS'
GROUP BY b.COMP_CD, b.BCOFF_CD, b.BCOFF_NM;
```