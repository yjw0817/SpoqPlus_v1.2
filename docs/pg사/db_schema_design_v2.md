# SpoqPlus DB 스키마 설계 문서 (개정판)

## 1. 개요
기존 테이블 구조를 최대한 활용하여 사업장별 독립적인 운영을 위한 데이터베이스 스키마 설계 문서입니다.

## 2. 기존 테이블 분석

### 2.1 결제 관련 테이블 구조
```
paymt_mobile_tbl (결제 요청)
    ↓ PAY_APPNO_SNO
paymt_mobile_result_tbl (PG 응답)
    ↓ 
van_direct_hist (VAN 이력)
    ↓ PAYMT_VAN_SNO
paymt_mgmt_tbl (결제 관리)
    ↓ PAYMT_MGMT_SNO
sales_mgmt_tbl (매출 관리)
```

### 2.2 핵심 테이블 특징
- **paymt_mgmt_tbl**: 모든 결제의 최종 정보 관리
- **paymt_mobile_tbl**: 모바일 결제 시작 시 임시 정보 저장
- **COMP_CD, BCOFF_CD**: 모든 테이블에 사업장 구분 존재
- **PAYMT_VAN_KND**: PG사 구분 가능

## 3. 스키마 수정 설계 (최소 변경)

### 3.1 bcoff_mgmt_tbl 수정

#### 컬럼 추가
```sql
ALTER TABLE bcoff_mgmt_tbl
ADD COLUMN pg_settings JSON COMMENT 'PG설정정보' AFTER api_key,
ADD COLUMN bank_accounts JSON COMMENT '계좌정보' AFTER pg_settings;
```

#### JSON 구조 정의

**pg_settings 구조**:
```json
{
  "inicis": {
    "mobile": {
      "mid": "wpgymjs200",
      "signkey": "NkpsY1B4NXM5c0dNN2xYTmpYMUx6UT09",
      "mode": "prod"
    },
    "card": {
      "mid": "wpgymjs200c",
      "signkey": "card_signkey_here",
      "mode": "prod"
    }
  },
  "dev": {
    "mobile": {
      "mid": "welcometst",
      "signkey": "QjZXWDZDRmxYUXJPYnMvelEvSjJ5QT09",
      "mode": "dev"
    }
  }
}
```

**bank_accounts 구조**:
```json
[
  {
    "bank_nm": "국민은행",
    "bank_cd": "004",
    "account_no": "12321-13547-125-25",
    "account_holder": "(주)스포크플러스",
    "is_default": true,
    "display_order": 1
  },
  {
    "bank_nm": "하나은행",
    "bank_cd": "081",
    "account_no": "8514-254-45387-11",
    "account_holder": "(주)스포크플러스",
    "is_default": false,
    "display_order": 2
  }
]
```

### 3.2 paymt_mobile_tbl 수정 (선택사항)

PG 정보 추적을 위한 컬럼 추가:
```sql
ALTER TABLE paymt_mobile_tbl
ADD COLUMN PAY_PG_MID VARCHAR(50) COMMENT '사용PG상점ID' AFTER PAY_ISSUE,
ADD COLUMN PAY_PG_PROVIDER VARCHAR(20) COMMENT 'PG제공사' AFTER PAY_PG_MID;
```

## 4. 데이터 마이그레이션

### 4.1 기존 데이터 백업
```sql
-- bcoff_mgmt_tbl 백업
CREATE TABLE bcoff_mgmt_tbl_backup AS SELECT * FROM bcoff_mgmt_tbl;
```

### 4.2 PG 설정 데이터 입력
```sql
-- 잠실점 (B00001)
UPDATE bcoff_mgmt_tbl 
SET pg_settings = '{
  "inicis": {
    "mobile": {"mid": "wpgymjs200", "signkey": "NkpsY1B4NXM5c0dNN2xYTmpYMUx6UT09", "mode": "prod"}
  },
  "dev": {
    "mobile": {"mid": "welcometst", "signkey": "QjZXWDZDRmxYUXJPYnMvelEvSjJ5QT09", "mode": "dev"}
  }
}'
WHERE COMP_CD = 'C00001' AND BCOFF_CD = 'B00001';

-- 역삼점 (B00002)
UPDATE bcoff_mgmt_tbl 
SET pg_settings = '{
  "inicis": {
    "mobile": {"mid": "wpgym200ys", "signkey": "clNSQTgxa2lLZHdMTG1DOEVaME5QZz09", "mode": "prod"}
  },
  "dev": {
    "mobile": {"mid": "welcometst", "signkey": "QjZXWDZDRmxYUXJPYnMvelEvSjJ5QT09", "mode": "dev"}
  }
}'
WHERE COMP_CD = 'C00001' AND BCOFF_CD = 'B00002';
```

### 4.3 계좌 정보 데이터 입력
```sql
-- 모든 지점 공통 계좌 (예시)
UPDATE bcoff_mgmt_tbl 
SET bank_accounts = '[
  {"bank_nm": "국민은행", "bank_cd": "004", "account_no": "12321-13547-125-25", "account_holder": "(주)스포크플러스", "is_default": true, "display_order": 1},
  {"bank_nm": "하나은행", "bank_cd": "081", "account_no": "8514-254-45387-11", "account_holder": "(주)스포크플러스", "is_default": false, "display_order": 2}
]'
WHERE COMP_CD = 'C00001';
```

## 5. 인덱스 및 제약조건

기존 인덱스는 그대로 유지하며, JSON 컬럼은 MySQL 5.7 이상에서 가상 컬럼으로 인덱싱 가능:

```sql
-- JSON 내 특정 값에 대한 가상 컬럼 및 인덱스 (선택사항)
ALTER TABLE bcoff_mgmt_tbl
ADD COLUMN pg_mid_mobile VARCHAR(50) AS (JSON_UNQUOTE(JSON_EXTRACT(pg_settings, '$.inicis.mobile.mid'))) VIRTUAL,
ADD INDEX idx_pg_mid (pg_mid_mobile);
```

## 6. 보안 고려사항

### 6.1 PG 서명키 암호화
- Application 레벨에서 암/복호화 처리
- DB에는 암호화된 값 저장

### 6.2 접근 권한
- pg_settings, bank_accounts 컬럼은 관리자만 수정 가능
- 일반 사용자는 읽기 전용

## 7. 장점

1. **최소한의 변경**: 기존 테이블 구조 100% 유지
2. **유연성**: JSON으로 다양한 PG사 및 설정 지원
3. **확장성**: 새로운 PG사 추가 시 JSON 구조만 확장
4. **호환성**: 기존 시스템에 영향 없음

## 8. 주의사항

1. **JSON 지원**: MySQL 5.7 이상 필요
2. **백업**: 수정 전 반드시 백업 수행
3. **테스트**: 개발 환경에서 충분한 테스트 후 적용