# PHP 파일 param1/param2 수정 완료 요약

## ✅ 수정된 파일들

### 1. **FaceTest.php** (`app/Controllers/FaceTest.php`)
- `recognize_for_checkin()` - comp_cd, bcoff_cd 추가
- `test_register()` - param1, param2 추가

### 2. **FaceApi.php** (`app/Controllers/FaceApi.php`)
- `register()` - param1, param2 추가
- `recognize()` - param1, param2 추가

### 3. **Ttotalmain.php** (`app/Controllers/Ttotalmain.php`)
- Python API 호출 시 param1, param2 추가
- member_id도 함께 전달

### 4. **checkin.php** (`face_recognition/examples/checkin.php`)
- 세션 키 이름을 'comp_cd', 'bcoff_cd'로 통일

## 📌 주요 변경 내용

### 세션에서 회사/지점 정보 가져오기
```php
$session = session();
$param1 = $session->get('comp_cd') ?? 'DEFAULT';
$param2 = $session->get('bcoff_cd') ?? 'DEFAULT';
```

### API 호출 시 파라미터 추가
```php
// 얼굴 등록
$requestData = [
    'member_id' => $postVar['mem_sno'],
    'image' => $imageData,
    'param1' => $param1,  // 회사 코드
    'param2' => $param2   // 지점 코드
];

// 체크인용 인식
$apiData = [
    'image' => $requestData['image'],
    'comp_cd' => $comp_cd,      // Python에서 param1으로 자동 변환
    'bcoff_cd' => $bcoff_cd,    // Python에서 param2로 자동 변환
    'security_level' => 3
];
```

### 로그 추가
```php
log_message('info', '[FaceTest] comp_cd: ' . $comp_cd . ', bcoff_cd: ' . $bcoff_cd);
log_message('info', '[FaceApi] register - param1: ' . $param1 . ', param2: ' . $param2);
log_message('info', '[FACE] param1(회사): ' . $param1 . ', param2(지점): ' . $param2);
```

## 🔧 필요한 추가 작업

### 1. 로그인 시 세션 설정
로그인 컨트롤러에서 다음과 같이 설정 필요:
```php
// 로그인 성공 시
$session = session();
$session->set([
    'logged_in' => true,
    'user_id' => $user['mem_sno'],
    'comp_cd' => $user['comp_cd'],    // 회사 코드
    'bcoff_cd' => $user['bcoff_cd']   // 지점 코드
]);
```

### 2. 테스트 순서
1. 로그인하여 세션에 회사/지점 정보 저장 확인
2. 얼굴 등록 시 param1, param2가 전달되는지 로그 확인
3. 얼굴 인식 시 해당 지점 회원만 조회되는지 확인

### 3. 로그 확인 위치
- CodeIgniter 로그: `writable/logs/log-YYYY-MM-DD.php`
- Python 로그: `face_recognition/insightface_service.log`

## ✅ 완료 상태
- 모든 face recognition 관련 PHP 파일 수정 완료
- param1/param2 전달 구현 완료
- 로그 기능 추가 완료
- 하위 호환성 유지 (comp_cd/bcoff_cd도 계속 작동)