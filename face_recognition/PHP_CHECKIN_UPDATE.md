# recognize_for_checkin 메서드 param1/param2 추가 가이드

## 현재 코드 (param1, param2 없음)
```php
public function recognize_for_checkin()
{
    try {
        log_message('info', '[FaceTest] ========== recognize_for_checkin 시작 ==========');
        
        $postVar = $this->request->getPost();
        $jsonInput = $this->request->getJSON(true);
        
        // JSON과 POST 둘 다 지원
        $requestData = $jsonInput ?: $postVar;
        
        if (!isset($requestData['image'])) {
            return $this->response->setJSON([
                'success' => false,
                'error' => '이미지 데이터가 없습니다.'
            ]);
        }
        
        // InsightFace 서버로 전달할 데이터
        $apiData = [
            'image' => $requestData['image'],
            'check_liveness' => $requestData['check_liveness'] ?? true,
            'check_blink' => $requestData['check_blink'] ?? false
        ];
        
        // Python API 호출 (InsightFace 서버)
        $result = $this->callPythonAPI('/api/face/recognize_for_checkin', 'POST', $apiData);
```

## 수정된 코드 (param1, param2 추가)

### 방법 1: comp_cd, bcoff_cd 사용 (자동 변환)
```php
public function recognize_for_checkin()
{
    try {
        log_message('info', '[FaceTest] ========== recognize_for_checkin 시작 ==========');
        
        $postVar = $this->request->getPost();
        $jsonInput = $this->request->getJSON(true);
        
        // JSON과 POST 둘 다 지원
        $requestData = $jsonInput ?: $postVar;
        
        if (!isset($requestData['image'])) {
            return $this->response->setJSON([
                'success' => false,
                'error' => '이미지 데이터가 없습니다.'
            ]);
        }
        
        // 세션에서 회사/지점 정보 가져오기
        $session = session();
        $comp_cd = $session->get('comp_cd') ?? 'DEFAULT';
        $bcoff_cd = $session->get('bcoff_cd') ?? 'DEFAULT';
        
        // 로그로 확인
        log_message('info', '[FaceTest] comp_cd: ' . $comp_cd . ', bcoff_cd: ' . $bcoff_cd);
        
        // InsightFace 서버로 전달할 데이터
        $apiData = [
            'image' => $requestData['image'],
            'comp_cd' => $comp_cd,      // Python에서 param1으로 자동 변환
            'bcoff_cd' => $bcoff_cd,    // Python에서 param2로 자동 변환
            'check_liveness' => $requestData['check_liveness'] ?? true,
            'check_blink' => $requestData['check_blink'] ?? false
        ];
        
        // Python API 호출 (InsightFace 서버)
        $result = $this->callPythonAPI('/api/face/recognize_for_checkin', 'POST', $apiData);
```

### 방법 2: 직접 param1, param2 사용
```php
public function recognize_for_checkin()
{
    try {
        log_message('info', '[FaceTest] ========== recognize_for_checkin 시작 ==========');
        
        $postVar = $this->request->getPost();
        $jsonInput = $this->request->getJSON(true);
        
        // JSON과 POST 둘 다 지원
        $requestData = $jsonInput ?: $postVar;
        
        if (!isset($requestData['image'])) {
            return $this->response->setJSON([
                'success' => false,
                'error' => '이미지 데이터가 없습니다.'
            ]);
        }
        
        // 세션에서 회사/지점 정보 가져오기
        $session = session();
        $param1 = $session->get('comp_cd') ?? 'DEFAULT';
        $param2 = $session->get('bcoff_cd') ?? 'DEFAULT';
        
        // 로그로 확인
        log_message('info', '[FaceTest] param1(회사): ' . $param1 . ', param2(지점): ' . $param2);
        
        // InsightFace 서버로 전달할 데이터
        $apiData = [
            'image' => $requestData['image'],
            'param1' => $param1,         // 직접 param1 사용
            'param2' => $param2,         // 직접 param2 사용
            'check_liveness' => $requestData['check_liveness'] ?? true,
            'check_blink' => $requestData['check_blink'] ?? false
        ];
        
        // Python API 호출 (InsightFace 서버)
        $result = $this->callPythonAPI('/api/face/recognize', 'POST', $apiData);  // 일반 recognize API 사용
```

### 방법 3: 요청에서 직접 받기 (API 호출 시)
```php
public function recognize_for_checkin()
{
    try {
        log_message('info', '[FaceTest] ========== recognize_for_checkin 시작 ==========');
        
        $postVar = $this->request->getPost();
        $jsonInput = $this->request->getJSON(true);
        
        // JSON과 POST 둘 다 지원
        $requestData = $jsonInput ?: $postVar;
        
        if (!isset($requestData['image'])) {
            return $this->response->setJSON([
                'success' => false,
                'error' => '이미지 데이터가 없습니다.'
            ]);
        }
        
        // 세션에서 기본값 가져오기
        $session = session();
        $defaultCompCd = $session->get('comp_cd') ?? 'DEFAULT';
        $defaultBcoffCd = $session->get('bcoff_cd') ?? 'DEFAULT';
        
        // 요청에서 받거나 세션 기본값 사용
        $comp_cd = $requestData['comp_cd'] ?? $defaultCompCd;
        $bcoff_cd = $requestData['bcoff_cd'] ?? $defaultBcoffCd;
        
        // 로그로 확인
        log_message('info', '[FaceTest] 최종 comp_cd: ' . $comp_cd . ', bcoff_cd: ' . $bcoff_cd);
        
        // InsightFace 서버로 전달할 데이터
        $apiData = [
            'image' => $requestData['image'],
            'comp_cd' => $comp_cd,      // Python에서 param1으로 자동 변환
            'bcoff_cd' => $bcoff_cd,    // Python에서 param2로 자동 변환
            'check_liveness' => $requestData['check_liveness'] ?? true,
            'check_blink' => $requestData['check_blink'] ?? false,
            'security_level' => $requestData['security_level'] ?? 3
        ];
        
        // Python API 호출 (InsightFace 서버)
        $result = $this->callPythonAPI('/api/face/recognize_for_checkin', 'POST', $apiData);
```

## 중요 포인트

1. **recognize_for_checkin API의 특징**
   - comp_cd → param1으로 자동 변환
   - bcoff_cd → param2으로 자동 변환
   - 엄격한 보안 검사 포함

2. **세션에서 회사/지점 정보 가져오기**
   ```php
   $session = session();
   $comp_cd = $session->get('comp_cd');
   $bcoff_cd = $session->get('bcoff_cd');
   ```

3. **로그 확인**
   ```php
   log_message('info', '[FaceTest] comp_cd: ' . $comp_cd . ', bcoff_cd: ' . $bcoff_cd);
   ```

4. **Python 서버 로그에서 확인**
   - insightface_service.log에서 param1, param2 값 확인
   - SQL 쿼리에서 필터링 조건 확인

## 테스트 방법

1. **세션 값 설정 확인**
   ```php
   // 로그인 시 세션에 저장되는지 확인
   var_dump($session->get('comp_cd'));
   var_dump($session->get('bcoff_cd'));
   ```

2. **API 요청 로그**
   ```php
   log_message('info', '[FaceTest] API 요청 데이터: ' . json_encode($apiData));
   ```

3. **Python 서버 응답 확인**
   ```php
   log_message('info', '[FaceTest] API 응답: ' . json_encode($result));
   ```

## 권장 방법

**방법 1 (comp_cd, bcoff_cd 사용)을 권장**합니다.
- 기존 API와 호환성 유지
- Python에서 자동으로 param1, param2로 변환
- recognize_for_checkin의 보안 기능 활용