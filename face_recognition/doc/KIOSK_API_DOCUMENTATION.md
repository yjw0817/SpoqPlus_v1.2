# KIOSK API Documentation

## KIOSK 시스템 API 문서

### 개요
KIOSK 시스템은 무인 체크인/체크아웃을 위한 독립 실행형 터미널 시스템입니다.

### API 엔드포인트

#### 1. 회원 조회
```http
GET /api/kiosk/members/search
```
**Parameters:**
- `phone`: 전화번호
- `member_number`: 회원번호

**Response:**
```json
{
  "success": true,
  "data": {
    "member_number": "M2024001",
    "name": "홍길동",
    "passes": [...]
  }
}
```

#### 2. 체크인 처리
```http
POST /api/kiosk/checkin
```
**Body:**
```json
{
  "member_number": "M2024001",
  "pass_id": "P001",
  "branch_code": "GANGNAM"
}
```

#### 3. 이용권 조회
```http
GET /api/kiosk/passes/{member_number}
```
**Response:**
```json
{
  "passes": [
    {
      "pass_id": "P001",
      "pass_name": "1개월 이용권",
      "valid_until": "2024-12-31",
      "remaining": 15
    }
  ]
}
```

### 지점별 설정

#### 지점 코드
- `GANGNAM`: 강남점
- `SEOCHO`: 서초점
- `JAMSIL`: 잠실점

#### 지점별 필터링
- 각 KIOSK는 설정된 지점의 회원만 조회
- `branch_code` 파라미터로 필터링

### 보안
- API Key 인증
- HTTPS 필수
- IP Whitelist

### 에러 코드
| 코드 | 설명 |
|------|------|
| 401 | 인증 실패 |
| 404 | 회원 없음 |
| 409 | 중복 체크인 |
| 500 | 서버 오류 |

### 테스트 환경
- URL: `http://localhost:8080/api/kiosk`
- API Key: `test_key_123`