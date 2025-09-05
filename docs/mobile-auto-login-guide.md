# 모바일 자동 로그인 구현 가이드

## 1. 데이터베이스 테이블 생성

먼저 다음 SQL을 실행하여 자동 로그인 토큰을 저장할 테이블을 생성하세요:

```sql
-- /sql/member_auth_tokens.sql 파일 내용을 실행
CREATE TABLE IF NOT EXISTS `member_auth_tokens` (
  `token_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '토큰 ID',
  `mem_id` int(11) NOT NULL COMMENT '회원 ID',
  `auth_token` varchar(255) NOT NULL COMMENT '인증 토큰',
  `phone_number` varchar(20) NOT NULL COMMENT '전화번호',
  `device_info` text DEFAULT NULL COMMENT '디바이스 정보',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP 주소',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성일시',
  `expired_at` datetime NOT NULL COMMENT '만료일시',
  `last_used_at` datetime DEFAULT NULL COMMENT '마지막 사용일시',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '활성화 여부',
  PRIMARY KEY (`token_id`),
  UNIQUE KEY `uk_auth_token` (`auth_token`),
  KEY `idx_mem_id` (`mem_id`),
  KEY `idx_phone_number` (`phone_number`),
  KEY `idx_expired_at` (`expired_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='회원 자동 로그인 토큰';

ALTER TABLE `member_auth_tokens` ADD INDEX `idx_cleanup` (`is_active`, `expired_at`);
```

## 2. 자동 로그인 동작 방식

### 로그인 프로세스:
1. **SMS 인증 후 로그인** 또는 **회원가입 완료** 시
2. 서버에서 자동 로그인 토큰 생성 (1년 유효)
3. 토큰을 브라우저의 localStorage에 저장
4. 다음 방문 시 토큰으로 자동 로그인

### 자동 로그인 특징:
- **로그아웃해도 토큰은 유지**됩니다
- 다시 로그인 페이지 방문 시 자동으로 로그인됩니다
- 토큰 유효기간: **1년**
- 브라우저별로 개별 관리

## 3. 구현된 기능

### SmsModel (/app/Models/SmsModel.php)
- `saveAuthToken()`: 토큰 저장
- `verifyAuthToken()`: 토큰 검증
- `deleteAuthToken()`: 토큰 비활성화 (실제로는 삭제하지 않음)
- `cleanExpiredTokens()`: 만료된 토큰 정리

### Api Controller (/app/Controllers/Api.php)
- `loginWithPhone()`: 로그인 시 토큰 생성
- `checkAutoLogin()`: 자동 로그인 확인
- `generateAuthToken()`: 토큰 생성
- `mobileLogout()`: 로그아웃 (토큰은 유지)

### 프론트엔드
- **login.php**: 페이지 로드 시 자동 로그인 체크
- **register.php**: 회원가입 후 토큰 저장

## 4. 테스트 방법

1. 회원가입 또는 SMS 로그인
2. 브라우저 개발자 도구 > Application > Local Storage 확인
3. `spoq_auth_token` 키 확인
4. 로그아웃 후 다시 로그인 페이지 방문
5. 자동으로 로그인되는지 확인

## 5. 주의사항

- HTTPS 환경에서만 안전하게 작동합니다
- 공용 기기에서는 주의가 필요합니다
- 브라우저 캐시/쿠키 삭제 시 토큰도 삭제됩니다

## 6. 토큰 관리

### 토큰 무효화 (관리자)
```sql
-- 특정 회원의 토큰 무효화
UPDATE member_auth_tokens 
SET is_active = 0 
WHERE mem_id = [회원ID];
```

### 만료된 토큰 정리
```sql
-- 만료된 토큰 비활성화
UPDATE member_auth_tokens 
SET is_active = 0 
WHERE expired_at < NOW() AND is_active = 1;
```