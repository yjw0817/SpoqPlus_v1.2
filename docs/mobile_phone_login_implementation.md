# 모바일 전화번호 기반 로그인 구현

## 개요
기존 ID/비밀번호 로그인에서 전화번호 기반 SMS 인증 로그인으로 변경했습니다.

## 주요 변경 사항

### 1. UI 변경 (login.php)
- ID/비밀번호 입력 필드를 숨김 처리 (`display: none`)
- 전화번호 입력 필드를 메인으로 표시
- 인증번호 발송 버튼을 로그인 버튼과 통합
- CSS와 레이아웃은 기존 그대로 유지

### 2. JavaScript 변경
- `processPhoneLogin()`: 전화번호 로그인 프로세스 메인 함수
- `sendPhoneSmsVerification()`: SMS 인증번호 발송
- `verifyPhoneSmsCode()`: 인증번호 확인
- `loginWithPhone()`: 전화번호로 로그인 처리
- `checkAutoLogin()`: 쿠키 기반 자동 로그인
- `resendSmsVerification()`: 인증번호 재발송

### 3. 백엔드 API 추가 (Api.php)

#### loginWithPhone()
- 전화번호 기반 로그인 처리
- SMS 인증 확인 후 회원 정보 조회
- 세션 생성 및 장기 쿠키(30일) 설정

#### verifySmsAutoLogin()
- 쿠키 기반 자동 로그인 확인
- 인증 토큰 검증 및 세션 재생성

#### createSessionForMember() (private)
- 회원 정보로 세션 데이터 생성
- 기존 loginProc의 세션 생성 로직 재사용

#### generateAuthToken() (private)
- SHA256 기반 인증 토큰 생성

### 4. 모델 추가

#### MemModel::getMemberByPhone()
```php
public function getMemberByPhone($phoneNumber, $comp_cd, $bcoff_cd)
```
- 전화번호로 회원 정보 조회
- 활성 회원만 조회 (USE_YN = 'Y', CONN_POSS_YN = 'Y')

#### SmsModel 메서드 추가
- `saveAuthToken()`: 인증 토큰 DB 저장
- `verifyAuthToken()`: 토큰 유효성 검증
- `cleanExpiredAuthTokens()`: 만료 토큰 정리

### 5. 데이터베이스 테이블 추가

#### sms_auth_tokens
```sql
CREATE TABLE `sms_auth_tokens` (
    `token_id` INT(11) NOT NULL AUTO_INCREMENT,
    `member_id` VARCHAR(50) NOT NULL,
    `auth_token` VARCHAR(255) NOT NULL,
    `phone_number` VARCHAR(20) NOT NULL,
    `expired_at` DATETIME NOT NULL,
    `created_at` DATETIME NOT NULL,
    `last_used_at` DATETIME NULL,
    PRIMARY KEY (`token_id`)
);
```

## 로그인 플로우

1. **전화번호 입력**
   - 사용자가 전화번호 입력
   - 자동으로 숫자만 입력 가능 (최대 11자리)

2. **인증번호 발송**
   - 전화번호 유효성 검사
   - SMS 인증번호 발송
   - 5분 타이머 시작

3. **인증번호 확인**
   - 6자리 입력 시 자동 확인
   - 인증 성공 시 로그인 진행

4. **로그인 처리**
   - 전화번호로 회원 정보 조회
   - 세션 생성
   - 30일 유효 쿠키 설정

5. **자동 로그인**
   - 페이지 로드 시 쿠키 확인
   - 유효한 토큰이면 자동 로그인

## 보안 고려사항

1. **SMS 인증**
   - 10분 이내 인증 필요
   - 일일 발송 제한 적용

2. **쿠키 보안**
   - httpOnly, secure, sameSite 설정
   - SHA256 해시 토큰 사용

3. **세션 보안**
   - 기존 로그인과 동일한 세션 데이터
   - 회원 상태 확인 (활성 회원만)

## 기존 기능 호환성

- 기존 loginProc 메서드는 수정하지 않음
- 새로운 메서드 추가로 구현
- 다른 페이지에서 사용 중인 기능 영향 없음

## 테스트 방법

1. SQL 파일 실행
```bash
mysql -u username -p database_name < sql/create_sms_auth_tokens_table.sql
```

2. 모바일 로그인 페이지 접속

3. 전화번호 입력 후 인증번호 발송

4. 인증번호 입력 후 로그인

5. 로그아웃 후 재접속하여 자동 로그인 확인