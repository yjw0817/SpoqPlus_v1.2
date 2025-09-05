# 이니시스 PG 설정 가이드

## 개요
이 문서는 SpoqPlus 시스템에 이니시스(Inicis) PG API를 통합하는 방법을 설명합니다.

## 설정 절차

### 1. 데이터베이스 설정

#### bcoff_mgmt_tbl의 PAYMT_PG_SET 컬럼 설정 예시
```json
{
  "inicis": {
    "merchant_id": "INIpayTest",
    "sign_key": "SU5JTElURV9UUklQTEVERVNfS0VZLS0tLS0tLS0tLS0tLS0=",
    "test_mode": "Y",
    "use_escrow": "N",
    "currency": "WON",
    "charset": "UTF-8",
    "max_amount": 10000000,
    "min_amount": 100,
    "allowed_methods": ["Card", "DirectBank", "VBank", "HPP"],
    "return_url": "https://yourdomain.com/payment/inicis/return",
    "close_url": "https://yourdomain.com/payment/inicis/close",
    "popup_url": "https://yourdomain.com/payment/inicis/popup",
    "notification_url": "https://yourdomain.com/payment/inicis/notification"
  }
}
```

#### 운영 환경 설정 예시
```json
{
  "inicis": {
    "merchant_id": "your_live_merchant_id",
    "sign_key": "your_live_sign_key_base64_encoded",
    "test_mode": "N",
    "use_escrow": "N",
    "currency": "WON",
    "charset": "UTF-8",
    "max_amount": 50000000,
    "min_amount": 100,
    "allowed_methods": ["Card", "DirectBank", "VBank"],
    "return_url": "https://your-live-domain.com/payment/inicis/return",
    "close_url": "https://your-live-domain.com/payment/inicis/close",
    "popup_url": "https://your-live-domain.com/payment/inicis/popup",
    "notification_url": "https://your-live-domain.com/payment/inicis/notification"
  }
}
```

### 2. 임시 결제 테이블 생성
```sql
-- 마이그레이션 파일 실행
php spark migrate
```

### 3. 사용 방법

#### 결제 초기화
```javascript
// JavaScript에서 결제 요청
$.ajax({
    url: '/payment/inicis/init',
    method: 'POST',
    data: {
        bcoff_cd: 'B001',
        mem_sno: '12345',
        mem_id: 'user@example.com',
        mem_nm: '홍길동',
        mem_tel: '010-1234-5678',
        mem_email: 'user@example.com',
        sell_event_sno: 'SE001',
        sell_event_nm: '헬스 1개월 이용권',
        paymt_amt: 100000,
        paymt_mthd: 'CARD',
        paymt_chnl: 'PC'
    },
    success: function(response) {
        if (response.status === 'success') {
            // 결제창 열기
            window.open(response.data.payment_url, 'payment', 'width=500,height=600');
        } else {
            alert('결제 초기화 실패: ' + response.message);
        }
    }
});
```

#### PHP에서 직접 사용
```php
use App\Services\InicisPaymentService;

// 결제 서비스 초기화
$inicisService = new InicisPaymentService();
$inicisService->initializeWithBranchSettings('B001');

// 결제 요청
$paymentData = [
    'BCOFF_CD' => 'B001',
    'MEM_SNO' => '12345',
    'MEM_ID' => 'user@example.com',
    'MEM_NM' => '홍길동',
    'PAYMT_AMT' => 100000,
    'SELL_EVENT_NM' => '헬스 1개월 이용권',
    'PAYMT_MTHD' => 'CARD'
];

$result = $inicisService->initializePayment($paymentData);

if ($result['status'] === 'success') {
    // 결제창으로 리다이렉트
    redirect($result['payment_url']);
} else {
    // 오류 처리
    echo $result['message'];
}
```

## API 명세

### 결제 초기화 API
- **URL**: `/payment/inicis/init`
- **Method**: `POST`
- **Parameters**:
  - `bcoff_cd`: 지점코드 (필수)
  - `mem_sno`: 회원일련번호 (필수)
  - `mem_id`: 회원ID (필수)
  - `mem_nm`: 회원명 (필수)
  - `paymt_amt`: 결제금액 (필수)
  - `sell_event_nm`: 상품명 (필수)
  - `paymt_mthd`: 결제방법 (CARD, BANK, VBANK, MOBILE)
  - `paymt_chnl`: 결제채널 (PC, MOBILE)

### 결제 취소 API
- **URL**: `/payment/inicis/cancel`
- **Method**: `POST`
- **Parameters**:
  - `paymt_mgmt_sno`: 결제관리일련번호 (필수)
  - `tid`: 거래고유번호 (필수)
  - `cancel_amt`: 취소금액 (필수)
  - `cancel_rson`: 취소사유
  - `partial_yn`: 부분취소여부 (Y/N)

## 테스트 방법

### 연결 테스트
```php
// Smgrmain.php 컨트롤러에서 테스트
$settings = [
    'merchant_id' => 'INIpayTest',
    'sign_key' => 'test_sign_key',
    'test_mode' => 'Y'
];

$testResult = $this->testInicisConnection($settings);
```

### 테스트 카드 정보
- **카드번호**: 4000-0000-0000-0002
- **유효기간**: 12/25
- **CVC**: 123
- **비밀번호**: 00

## 보안 고려사항

1. **Sign Key 보안**: 
   - Sign Key는 Base64로 인코딩하여 저장
   - 운영 환경에서는 환경변수 사용 권장

2. **HTTPS 사용**:
   - 운영 환경에서는 반드시 HTTPS 사용
   - Return URL, Notification URL 모두 HTTPS 적용

3. **IP 화이트리스트**:
   - 이니시스 관리자에서 허용 IP 설정
   - 서버 IP만 허용하도록 제한

4. **서명 검증**:
   - 모든 응답에 대해 서명 검증 수행
   - 위조된 응답 차단

## 로그 및 모니터링

### 로그 파일 위치
- 결제 로그: `writable/logs/payment.log`
- 에러 로그: `writable/logs/error.log`

### 모니터링 항목
- 결제 성공률
- 응답 시간
- 에러 발생률
- 취소/환불 처리량

## 문제 해결

### 자주 발생하는 오류

1. **"서명 검증 실패"**
   - Sign Key 확인
   - 시간 동기화 확인
   - 요청 파라미터 순서 확인

2. **"가맹점 정보 오류"**
   - Merchant ID 확인
   - 계약 상태 확인
   - IP 허용 여부 확인

3. **"결제 금액 오류"**
   - 최소/최대 금액 한도 확인
   - 숫자 형식 확인

### 지원 연락처
- 이니시스 고객센터: 1544-7772
- 기술지원: tech@inicis.com

## 버전 히스토리
- v1.0.0: 초기 구현 (2025-01-29)
  - 결제 초기화, 검증, 취소 기능
  - 모바일/PC 결제 지원
  - 테스트/운영 모드 지원