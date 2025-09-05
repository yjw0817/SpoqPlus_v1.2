# 모바일 로그인 SMS 인증 구현

## 개요
모바일 로그인 페이지에 SMS 인증 기능을 추가하여 보안을 강화했습니다.

## 주요 구현 사항

### 1. 데이터베이스 테이블
- `sms_verification`: SMS 인증 정보 저장
- `sms_send_logs`: SMS 발송 로그
- `sms_daily_limits`: 일일 발송 제한 관리

### 2. 백엔드 구현

#### SmsModel.php
- SMS 인증 코드 생성 및 저장
- 인증 코드 확인
- 일일 발송 제한 확인
- 발송 로그 저장

#### SmsService.php
- SMS 발송 서비스 구현
- 알리고 SMS API 연동
- 테스트 모드 지원

#### Api.php 컨트롤러
```php
// SMS 인증번호 발송
public function sendSmsVerification()

// SMS 인증번호 확인
public function verifySmsCode()

// SMS 인증 로그인 처리
public function loginProcWithSms()
```

### 3. 프론트엔드 구현

#### 로그인 페이지 UI
- SMS 인증 섹션 추가
- 전화번호 입력 필드
- 인증번호 입력 필드
- 5분 타이머 표시
- 재발송 버튼

#### JavaScript 기능
- 전화번호 유효성 검사
- 인증번호 자동 확인 (6자리 입력 시)
- 타이머 카운트다운
- 인증 상태 관리

### 4. 로그인 플로우

1. 사용자가 아이디/비밀번호 입력
2. 로그인 버튼 클릭
3. SMS 인증 섹션 표시
4. 전화번호 입력 후 인증번호 발송
5. 5분 이내 인증번호 입력
6. 인증 성공 시 자동 로그인 진행

### 5. 보안 기능

- 일일 SMS 발송 제한 (기본 5회)
- 1분 이내 재발송 방지
- 5분 인증 시간 제한
- 시도 횟수 기록
- IP 주소 및 User Agent 로깅

### 6. 환경 설정 (.env)

```env
# SMS 제공업체
SMS_PROVIDER=aligo

# SMS API 설정
SMS_API_KEY=your_api_key
SMS_API_SECRET=your_user_id

# 발신번호
SMS_SENDER=1588-0000

# 일일 발송 제한
SMS_DAILY_LIMIT=5

# 테스트 모드
SMS_TEST_MODE=true
```

### 7. 테스트 모드

테스트 모드 활성화 시:
- 실제 SMS 발송하지 않음
- 인증번호를 화면에 표시
- 개발/테스트 환경에서 사용

### 8. 사용 방법

1. SQL 파일 실행하여 테이블 생성
   ```bash
   mysql -u username -p database_name < sql/create_sms_verification_table.sql
   ```

2. .env 파일에 SMS 설정 추가

3. 모바일 로그인 페이지 접속하여 테스트

## 결론

모바일 로그인에 SMS 인증을 추가하여 보안을 강화했습니다. 테스트 모드를 통해 실제 SMS 발송 없이도 개발 및 테스트가 가능합니다.