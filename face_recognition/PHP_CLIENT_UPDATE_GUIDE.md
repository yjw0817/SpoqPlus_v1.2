# PHP 클라이언트 param1/param2 적용 가이드

## 🔄 업데이트 완료 사항 (2025-07-17)

### ✅ 수정된 파일들
1. **Ttotalmain.php** - `/api/face/register` 사용, param1/param2 전달
2. **FaceApi.php** - `.env`의 `FACE_PORT` 사용
3. **FaceRecognitionService.php** - `.env`의 `FACE_PORT` 사용
4. **Admin.php** - `.env`의 `FACE_PORT` 사용
5. **FaceTest.php** - `recognize_for_checkin`에 param1/param2 추가

### ✅ 환경 설정
- `.env` 파일에 `FACE_HOST=localhost`, `FACE_PORT=5002` 추가
- PHP 서버 재시작으로 환경변수 적용

## 1. Ttotalmain.php 수정 위치

### 현재 코드 (얼굴 등록 시)
```php
// 이미지 데이터가 있는 경우에만 Python API 호출
if ($imageData) {
    $pythonData = json_encode(['image' => $imageData]);
    
    log_message('info', '[FACE] Python API 호출: ' . $pythonUrl);
    log_message('info', '[FACE] 전송 데이터 크기: ' . strlen($pythonData) . ' bytes');
    
    $pythonResponse = $this->callPythonAPI($pythonUrl, $pythonData);
```

### 수정된 코드 (param1, param2 추가)
```php
// 이미지 데이터가 있는 경우에만 Python API 호출
if ($imageData) {
    // 세션에서 회사/지점 정보 가져오기
    $session = session();
    $comp_cd = $session->get('comp_cd') ?? 'DEFAULT';
    $bcoff_cd = $session->get('bcoff_cd') ?? 'DEFAULT';
    
    // Python API 요청 데이터 구성
    $pythonData = json_encode([
        'image' => $imageData,
        'param1' => $comp_cd,   // 회사 코드
        'param2' => $bcoff_cd   // 지점 코드
    ]);
    
    log_message('info', '[FACE] Python API 호출: ' . $pythonUrl);
    log_message('info', '[FACE] 전송 데이터 크기: ' . strlen($pythonData) . ' bytes');
    log_message('info', '[FACE] param1(회사): ' . $comp_cd . ', param2(지점): ' . $bcoff_cd);
    
    $pythonResponse = $this->callPythonAPI($pythonUrl, $pythonData);
```

## 2. 얼굴 인식 (체크인) 시 수정

### InsightFaceClient 사용 시
```php
// 기존 코드
$result = $insightFaceClient->recognizeFaceForCheckin(
    $imageData,
    'COMP01',
    'BRANCH01'
);

// 수정된 코드 (세션에서 자동으로 가져오기)
$session = session();
$comp_cd = $session->get('comp_cd') ?? 'DEFAULT';
$bcoff_cd = $session->get('bcoff_cd') ?? 'DEFAULT';

$result = $insightFaceClient->recognizeFaceForCheckin(
    $imageData,
    $comp_cd,   // 자동으로 param1으로 변환됨
    $bcoff_cd   // 자동으로 param2로 변환됨
);
```

## 3. 직접 API 호출 시
```php
// 얼굴 인식 API 호출
$recognizeData = [
    'image' => $imageData,
    'param1' => $comp_cd,   // 특정 회사만
    'param2' => $bcoff_cd   // 특정 지점만
];

$pythonUrl = 'http://localhost:5001/api/face/recognize';
$pythonResponse = $this->callPythonAPI($pythonUrl, json_encode($recognizeData));
```

## 4. 세션 정보 설정 (로그인 시)

로그인 컨트롤러에서 세션에 회사/지점 정보 저장:
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

## 5. 데이터베이스 조회 시 param1/param2 확인

```php
// 등록된 얼굴 확인
$sql = "SELECT * FROM member_faces 
        WHERE param1 = ? AND param2 = ? 
        ORDER BY created_at DESC";
$query = $db->query($sql, [$comp_cd, $bcoff_cd]);
$faces = $query->getResultArray();

// 인식 로그 확인
$sql = "SELECT * FROM face_recognition_logs 
        WHERE param1 = ? AND param2 = ? 
        AND DATE(recognition_time) = CURDATE()
        ORDER BY recognition_time DESC";
$query = $db->query($sql, [$comp_cd, $bcoff_cd]);
$logs = $query->getResultArray();
```

## 6. 실제 적용 예시

### 회원 등록 페이지
```php
public function registerMemberFace() {
    $postVar = $this->request->getPost();
    $session = session();
    
    // ... 기존 코드 ...
    
    // Python API 호출 부분 수정
    if ($imageData) {
        $pythonData = json_encode([
            'image' => $imageData,
            'member_id' => $postVar['mem_sno'],
            'param1' => $session->get('comp_cd'),
            'param2' => $session->get('bcoff_cd'),
            'notes' => '회원 등록 - ' . date('Y-m-d H:i:s')
        ]);
        
        $pythonUrl = 'http://localhost:5001/api/face/register';
        $pythonResponse = $this->callPythonAPI($pythonUrl, $pythonData);
    }
}
```

### 체크인 페이지
```php
public function processCheckin() {
    $imageData = $this->request->getPost('captured_photo');
    $session = session();
    
    // 해당 지점의 회원만 인식
    $recognizeData = [
        'image' => $imageData,
        'comp_cd' => $session->get('comp_cd'),
        'bcoff_cd' => $session->get('bcoff_cd'),
        'security_level' => 3
    ];
    
    $pythonUrl = 'http://localhost:5001/api/face/recognize_for_checkin';
    $pythonResponse = $this->callPythonAPI($pythonUrl, json_encode($recognizeData));
    
    if ($pythonResponse['success'] && $pythonResponse['matched']) {
        // 체크인 처리
        $memberId = $pythonResponse['member_id'];
        // ... 체크인 로직 ...
    }
}
```

## 7. 테스트 확인사항

1. **로그인 시 세션 확인**
   ```php
   log_message('info', 'Session comp_cd: ' . $session->get('comp_cd'));
   log_message('info', 'Session bcoff_cd: ' . $session->get('bcoff_cd'));
   ```

2. **API 호출 시 파라미터 확인**
   ```php
   log_message('info', 'API Request: ' . json_encode($pythonData));
   ```

3. **응답 확인**
   ```php
   log_message('info', 'API Response: ' . json_encode($pythonResponse));
   ```

## 8. 주의사항

- 세션에 회사/지점 정보가 없는 경우 기본값 사용
- param1, param2는 NULL 허용이므로 선택적 사용 가능
- 기존 comp_cd, bcoff_cd 사용 코드도 계속 작동 (하위 호환성)