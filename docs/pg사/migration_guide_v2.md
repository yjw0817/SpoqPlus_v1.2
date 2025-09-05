# SpoqPlus 사업장 분리 마이그레이션 가이드 (개정판)

## 1. 개요

기존 테이블 구조를 유지하면서 최소한의 변경으로 사업장별 PG 설정을 분리하는 마이그레이션 가이드입니다.

## 2. 사전 준비

### 2.1 현재 상태 확인

```sql
-- 현재 지점 목록 확인
SELECT COMP_CD, BCOFF_CD, BCOFF_NM, USE_YN 
FROM bcoff_mgmt_tbl 
WHERE USE_YN = 'Y' 
ORDER BY COMP_CD, BCOFF_CD;

-- 최근 결제 건수 확인 (영향도 파악)
SELECT COUNT(*) as cnt, BCOFF_CD 
FROM paymt_mgmt_tbl 
WHERE PAYMT_DATE >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY BCOFF_CD;
```

### 2.2 백업

```bash
# 전체 DB 백업
mysqldump -u [username] -p [database_name] > backup_$(date +%Y%m%d_%H%M%S).sql

# 핵심 테이블만 백업
mysqldump -u [username] -p [database_name] bcoff_mgmt_tbl > bcoff_mgmt_tbl_$(date +%Y%m%d).sql
```

## 3. 단계별 마이그레이션

### Phase 1: DB 스키마 변경 (10분)

#### Step 1: 컬럼 추가
```sql
-- bcoff_mgmt_tbl에 JSON 컬럼 추가
ALTER TABLE bcoff_mgmt_tbl
ADD COLUMN pg_settings JSON COMMENT 'PG설정정보' AFTER api_key,
ADD COLUMN bank_accounts JSON COMMENT '계좌정보' AFTER pg_settings;

-- 확인
DESCRIBE bcoff_mgmt_tbl;
```

#### Step 2: 기존 데이터 마이그레이션
```sql
-- 잠실점 (B00001) PG 설정
UPDATE bcoff_mgmt_tbl 
SET pg_settings = JSON_OBJECT(
    'inicis', JSON_OBJECT(
        'mobile', JSON_OBJECT(
            'mid', 'wpgymjs200',
            'signkey', 'NkpsY1B4NXM5c0dNN2xYTmpYMUx6UT09',
            'mode', 'prod'
        )
    ),
    'dev', JSON_OBJECT(
        'mobile', JSON_OBJECT(
            'mid', 'welcometst',
            'signkey', 'QjZXWDZDRmxYUXJPYnMvelEvSjJ5QT09',
            'mode', 'dev'
        )
    )
),
MOD_ID = 'MIGRATION',
MOD_DATETM = NOW()
WHERE COMP_CD = 'C00001' AND BCOFF_CD = 'B00001';

-- 역삼점 (B00002) PG 설정
UPDATE bcoff_mgmt_tbl 
SET pg_settings = JSON_OBJECT(
    'inicis', JSON_OBJECT(
        'mobile', JSON_OBJECT(
            'mid', 'wpgym200ys',
            'signkey', 'clNSQTgxa2lLZHdMTG1DOEVaME5QZz09',
            'mode', 'prod'
        )
    ),
    'dev', JSON_OBJECT(
        'mobile', JSON_OBJECT(
            'mid', 'welcometst',
            'signkey', 'QjZXWDZHRmxYUXJPYnMvelEvSjJ5QT09',
            'mode', 'dev'
        )
    )
),
MOD_ID = 'MIGRATION',
MOD_DATETM = NOW()
WHERE COMP_CD = 'C00001' AND BCOFF_CD = 'B00002';

-- 계좌 정보 마이그레이션 (모든 지점 동일)
UPDATE bcoff_mgmt_tbl 
SET bank_accounts = JSON_ARRAY(
    JSON_OBJECT(
        'bank_nm', '국민은행',
        'bank_cd', '004',
        'account_no', '12321-13547-125-25',
        'account_holder', '(주)스포크플러스',
        'is_default', true,
        'display_order', 1
    ),
    JSON_OBJECT(
        'bank_nm', '하나은행',
        'bank_cd', '081',
        'account_no', '8514-254-45387-11',
        'account_holder', '(주)스포크플러스',
        'is_default', false,
        'display_order', 2
    )
)
WHERE COMP_CD = 'C00001' AND USE_YN = 'Y';
```

#### Step 3: 데이터 검증
```sql
-- PG 설정 확인
SELECT 
    BCOFF_CD,
    BCOFF_NM,
    JSON_UNQUOTE(JSON_EXTRACT(pg_settings, '$.inicis.mobile.mid')) as pg_mid,
    JSON_UNQUOTE(JSON_EXTRACT(pg_settings, '$.inicis.mobile.mode')) as pg_mode
FROM bcoff_mgmt_tbl
WHERE COMP_CD = 'C00001' AND USE_YN = 'Y';

-- 계좌 정보 확인
SELECT 
    BCOFF_CD,
    BCOFF_NM,
    JSON_EXTRACT(bank_accounts, '$[0].bank_nm') as bank1,
    JSON_EXTRACT(bank_accounts, '$[0].account_no') as account1
FROM bcoff_mgmt_tbl
WHERE COMP_CD = 'C00001' AND USE_YN = 'Y';
```

### Phase 2: 코드 배포 (20분)

#### Step 1: Model 파일 배포
```bash
# BcoffModel.php 백업
cp app/Models/BcoffModel.php app/Models/BcoffModel.php.backup

# 수정된 Model 업로드
# app/Models/BcoffModel.php (getPgSettings, getBankAccounts 메소드 추가)
```

#### Step 2: Controller 파일 배포
```bash
# Api.php 백업
cp app/Controllers/Api.php app/Controllers/Api.php.backup_$(date +%Y%m%d)

# 수정된 Controller 업로드
# app/Controllers/Api.php (mobile_pay, event_buy_info 메소드 수정)
```

#### Step 3: View 파일 배포
```bash
# View 파일 백업
cp app/Views/mobile_p/new_event_buy_info.php app/Views/mobile_p/new_event_buy_info.php.backup

# 수정된 View 업로드
# app/Views/mobile_p/new_event_buy_info.php (계좌 선택 부분 수정)
```

### Phase 3: 테스트 (30분)

#### Step 1: 기능 테스트
1. **각 지점별 테스트 계정으로 로그인**
2. **상품 구매 프로세스 진행**
   - 상품 선택
   - 결제 정보 입력
   - PG 화면 표시 확인 (MID 확인)
3. **계좌 정보 표시 확인**

#### Step 2: 로그 확인
```sql
-- 최근 paymt_mobile_tbl 확인
SELECT * FROM paymt_mobile_tbl 
ORDER BY CRE_DATETM DESC 
LIMIT 10;

-- 오류 발생 여부 확인
SELECT * FROM paymt_mobile_result_tbl 
WHERE P_STATUS != '00' 
AND CRE_DATETM >= NOW() - INTERVAL 1 HOUR;
```

## 4. 신규 사업장 추가 절차

### 4.1 신규 지점 PG 설정
```sql
-- 신규 지점 (B00003) 추가 예시
UPDATE bcoff_mgmt_tbl 
SET pg_settings = JSON_OBJECT(
    'inicis', JSON_OBJECT(
        'mobile', JSON_OBJECT(
            'mid', '신규지점MID',
            'signkey', '신규지점서명키',
            'mode', 'prod'
        )
    ),
    'dev', JSON_OBJECT(
        'mobile', JSON_OBJECT(
            'mid', 'welcometst',
            'signkey', 'QjZXWDZDRmxYUXJPYnMvelEvSjJ5QT09',
            'mode', 'dev'
        )
    )
),
bank_accounts = JSON_ARRAY(
    JSON_OBJECT(
        'bank_nm', '국민은행',
        'account_no', '신규계좌번호',
        'account_holder', '(주)스포크플러스',
        'is_default', true,
        'display_order', 1
    )
),
MOD_ID = 'ADMIN',
MOD_DATETM = NOW()
WHERE COMP_CD = 'C00001' AND BCOFF_CD = 'B00003';
```

### 4.2 설정 확인
```sql
-- 신규 지점 설정 확인
SELECT 
    BCOFF_NM,
    JSON_PRETTY(pg_settings) as pg_config,
    JSON_PRETTY(bank_accounts) as bank_config
FROM bcoff_mgmt_tbl
WHERE COMP_CD = 'C00001' AND BCOFF_CD = 'B00003';
```

## 5. 롤백 절차

### 5.1 긴급 롤백 (코드)
```bash
# 백업 파일로 복원
cp app/Controllers/Api.php.backup_[날짜] app/Controllers/Api.php
cp app/Models/BcoffModel.php.backup app/Models/BcoffModel.php
cp app/Views/mobile_p/new_event_buy_info.php.backup app/Views/mobile_p/new_event_buy_info.php

# 캐시 클리어
rm -rf writable/cache/*
```

### 5.2 DB 롤백
```sql
-- 컬럼 제거 (주의: 데이터 손실)
ALTER TABLE bcoff_mgmt_tbl 
DROP COLUMN pg_settings,
DROP COLUMN bank_accounts;

-- 또는 백업에서 복원
mysql -u [username] -p [database_name] < bcoff_mgmt_tbl_[날짜].sql
```

## 6. 모니터링 및 운영

### 6.1 일일 점검
```sql
-- 일일 결제 현황
SELECT 
    b.BCOFF_NM,
    COUNT(p.PAYMT_MGMT_SNO) as payment_count,
    SUM(p.PAYMT_AMT) as total_amount,
    p.PAYMT_VAN_KND
FROM paymt_mgmt_tbl p
JOIN bcoff_mgmt_tbl b ON p.COMP_CD = b.COMP_CD AND p.BCOFF_CD = b.BCOFF_CD
WHERE p.PAYMT_DATE = CURDATE()
GROUP BY b.BCOFF_CD, b.BCOFF_NM, p.PAYMT_VAN_KND;
```

### 6.2 오류 대응
```sql
-- PG 설정 오류 시 임시 조치
-- 개발 PG로 전환
UPDATE bcoff_mgmt_tbl 
SET pg_settings = JSON_SET(
    pg_settings,
    '$.inicis.mobile.mid', 'welcometst',
    '$.inicis.mobile.signkey', 'QjZXWDZDRmxYUXJPYnMvelEvSjJ5QT09'
)
WHERE COMP_CD = 'C00001' AND BCOFF_CD = '문제지점코드';
```

## 7. FAQ

### Q1: JSON 컬럼이 지원되지 않는 경우?
A: MySQL 5.7 미만인 경우 TEXT 컬럼으로 대체하고 PHP에서 json_decode/encode 사용

### Q2: 기존 결제가 실패하는 경우?
A: 하드코딩된 값이 남아있는지 확인 (Api.php의 $sc_mid, $sc_signkey 검색)

### Q3: 새로운 PG사 추가 방법?
A: pg_settings JSON에 새로운 키 추가 (예: 'kcp', 'nice' 등)

## 8. 체크리스트

- [ ] DB 백업 완료
- [ ] 스키마 변경 완료
- [ ] 데이터 마이그레이션 완료
- [ ] 코드 배포 완료
- [ ] 각 지점별 테스트 완료
- [ ] 모니터링 설정 완료
- [ ] 롤백 계획 준비 완료