# SpoqPlus 얼굴 인식 구현 가이드

## 📌 구현 개요

이 문서는 SpoqPlus 시스템에 얼굴 인식 기능을 실제로 구현하는 방법을 설명합니다.

## 🎯 주요 구현 시나리오

### 1. 회원 등록 페이지 구현 (info_mem.php)
### 2. 체크인 페이지 구현 (checkin.php)
### 3. 키오스크 API 구현
### 4. 관리자 모니터링 구현

---

## 1️⃣ 회원 등록 페이지 구현

### HTML 구조
```html
<!-- info_mem.php -->
<div id="face-registration-container">
    <video id="video" autoplay playsinline></video>
    <canvas id="canvas" style="display:none;"></canvas>
    
    <div id="face-detection-status">
        <span id="status-message">카메라를 준비중입니다...</span>
        <div id="quality-indicators">
            <span class="indicator" id="face-detected">❌ 얼굴 감지</span>
            <span class="indicator" id="quality-check">❌ 품질 확인</span>
            <span class="indicator" id="liveness-check">❌ 실제 얼굴</span>
        </div>
    </div>
    
    <button id="register-face-btn" class="btn btn-primary" disabled>
        얼굴 등록하기
    </button>
</div>
```

### JavaScript 구현
```javascript
// face-registration.js
class FaceRegistration {
    constructor() {
        this.video = document.getElementById('video');
        this.canvas = document.getElementById('canvas');
        this.ctx = this.canvas.getContext('2d');
        this.stream = null;
        this.detectionInterval = null;
        this.autoCapture = true;  // 자동 캡처 활성화
    }
    
    async init() {
        try {
            // 카메라 권한 요청
            this.stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 640 },
                    height: { ideal: 480 },
                    facingMode: 'user'
                }
            });
            
            this.video.srcObject = this.stream;
            this.startDetection();
        } catch (error) {
            this.showError('카메라를 사용할 수 없습니다');
        }
    }
    
    startDetection() {
        this.detectionInterval = setInterval(() => {
            this.captureFrame();
        }, 1000);  // 1초마다 감지
    }
    
    captureFrame() {
        // 캔버스에 현재 프레임 그리기
        this.canvas.width = this.video.videoWidth;
        this.canvas.height = this.video.videoHeight;
        this.ctx.drawImage(this.video, 0, 0);
        
        // Base64로 변환
        const imageData = this.canvas.toDataURL('image/jpeg', 0.95);
        
        // 얼굴 감지 API 호출
        this.detectFace(imageData);
    }
    
    async detectFace(imageData) {
        try {
            // PHP 프록시를 통한 호출 (실제 구현)
            const response = await fetch('/FaceTest/recognize_for_registration', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    image: imageData,  // 주의: 'image'로 전송
                    param1: comp_cd,   // 회사 코드
                    param2: bcoff_cd   // 지점 코드
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.updateStatus(result.data);
                
                // 자동 캡처 조건 확인
                if (this.autoCapture && this.isReadyForCapture(result.data)) {
                    this.registerFace(imageData);
                }
            }
        } catch (error) {
            console.error('얼굴 감지 오류:', error);
        }
    }
    
    isReadyForCapture(data) {
        return data.faces_count === 1 &&
               data.face_info.quality_score > 60 &&
               (!data.liveness_score || data.liveness_score > 0.3);
    }
    
    async registerFace(imageData) {
        // 중복 등록 방지
        if (this.isRegistering) return;
        this.isRegistering = true;
        
        try {
            const memberNumber = document.getElementById('member_number').value;
            const memberName = document.getElementById('member_name').value;
            
            const response = await fetch('http://localhost:5001/api/face/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    image: imageData,
                    member_number: memberNumber,
                    name: memberName,
                    param1: getCompanyCode(),
                    param2: getBranchCode(),
                    update_if_exists: true
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess('얼굴이 등록되었습니다');
                
                // 경고 메시지 처리
                if (result.data.warnings) {
                    result.data.warnings.forEach(warning => {
                        this.showWarning(warning);
                    });
                }
                
                // 카메라 정리
                this.cleanup();
            } else {
                this.showError(result.message);
            }
        } catch (error) {
            this.showError('등록 중 오류가 발생했습니다');
        } finally {
            this.isRegistering = false;
        }
    }
    
    updateStatus(data) {
        // 상태 표시 업데이트
        document.getElementById('face-detected').className = 
            data.faces_count === 1 ? 'indicator success' : 'indicator';
        
        document.getElementById('quality-check').className = 
            data.face_info?.quality_score > 60 ? 'indicator success' : 'indicator';
        
        document.getElementById('liveness-check').className = 
            !data.liveness_score || data.liveness_score > 0.4 ? 'indicator success' : 'indicator warning';
    }
    
    showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: '성공',
            text: message,
            timer: 3000
        });
    }
    
    showWarning(message) {
        toastr.warning(message, '주의', {
            timeOut: 5000,
            positionClass: 'toast-top-right'
        });
    }
    
    showError(message) {
        Swal.fire({
            icon: 'error',
            title: '오류',
            text: message
        });
    }
    
    cleanup() {
        if (this.detectionInterval) {
            clearInterval(this.detectionInterval);
        }
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
        }
    }
}

// 페이지 로드 시 초기화
document.addEventListener('DOMContentLoaded', () => {
    const faceReg = new FaceRegistration();
    
    // 얼굴 등록 버튼 클릭 시
    document.getElementById('open-face-registration').addEventListener('click', () => {
        faceReg.init();
    });
});
```

---

## 2️⃣ 체크인 페이지 구현

### HTML 구조
```html
<!-- checkin.php -->
<div id="checkin-container">
    <div class="camera-section">
        <video id="checkin-video" autoplay playsinline></video>
        <canvas id="checkin-canvas" style="display:none;"></canvas>
        
        <div class="checkin-status">
            <h3 id="welcome-message"></h3>
            <div id="member-info" style="display:none;">
                <p>회원번호: <span id="member-number"></span></p>
                <p>이름: <span id="member-name"></span></p>
            </div>
        </div>
    </div>
    
    <div id="pass-selection" style="display:none;">
        <h4>이용권을 선택해주세요</h4>
        <div id="pass-list"></div>
    </div>
</div>
```

### JavaScript 구현
```javascript
// face-recognition-checkin.js
class FaceCheckin {
    constructor() {
        this.video = document.getElementById('checkin-video');
        this.canvas = document.getElementById('checkin-canvas');
        this.isProcessing = false;
        this.recognitionInterval = null;
        this.lastRecognitionTime = 0;
        this.RECOGNITION_COOLDOWN = 3000; // 3초 쿨다운
    }
    
    async init() {
        try {
            // 카메라 자동 시작
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    facingMode: 'user'
                }
            });
            
            this.video.srcObject = stream;
            
            // 비디오 준비되면 자동 인식 시작
            this.video.onloadedmetadata = () => {
                this.startAutoRecognition();
            };
        } catch (error) {
            this.showError('카메라를 시작할 수 없습니다');
        }
    }
    
    startAutoRecognition() {
        this.recognitionInterval = setInterval(() => {
            const now = Date.now();
            
            // 쿨다운 체크
            if (now - this.lastRecognitionTime < this.RECOGNITION_COOLDOWN) {
                return;
            }
            
            if (!this.isProcessing) {
                this.recognizeFace();
            }
        }, 500); // 0.5초마다 체크
    }
    
    async recognizeFace() {
        this.isProcessing = true;
        this.lastRecognitionTime = Date.now();
        
        try {
            // 현재 프레임 캡처
            const ctx = this.canvas.getContext('2d');
            this.canvas.width = this.video.videoWidth;
            this.canvas.height = this.video.videoHeight;
            ctx.drawImage(this.video, 0, 0);
            
            const imageData = this.canvas.toDataURL('image/jpeg', 0.95);
            
            // 체크인용 인식 API 호출
            const response = await fetch('http://localhost:5001/api/face/recognize_for_checkin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Cookie': document.cookie  // 세션 정보 포함
                },
                body: JSON.stringify({
                    image: imageData,
                    param1: getCompanyCode(),
                    param2: getBranchCode(),
                    require_liveness: true
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.handleRecognitionSuccess(result.data);
            } else {
                // 친근한 메시지로 변환
                this.showUserFriendlyError(result.error, result.message);
            }
        } catch (error) {
            console.error('인식 오류:', error);
        } finally {
            this.isProcessing = false;
        }
    }
    
    handleRecognitionSuccess(data) {
        // 환영 메시지 표시
        const welcomeMsg = `${data.name}님 안녕하세요!`;
        document.getElementById('welcome-message').textContent = welcomeMsg;
        
        // 회원 정보 표시
        document.getElementById('member-number').textContent = data.member_number;
        document.getElementById('member-name').textContent = data.name;
        document.getElementById('member-info').style.display = 'block';
        
        // 이용권 처리
        if (data.available_passes.length === 0) {
            this.showError('사용 가능한 이용권이 없습니다');
        } else if (data.available_passes.length === 1) {
            // 이용권 1개: 즉시 체크인
            const pass = data.available_passes[0];
            const confirmMsg = `${welcomeMsg}\n${pass.pass_name}으로 입장하시겠습니까?`;
            
            if (confirm(confirmMsg)) {
                this.processCheckin(data.member_number, pass.pass_id);
            }
        } else {
            // 이용권 여러개: 선택 화면
            this.showPassSelection(data.name, data.available_passes);
        }
    }
    
    showPassSelection(memberName, passes) {
        const passListDiv = document.getElementById('pass-list');
        passListDiv.innerHTML = '';
        
        // 환영 메시지 유지
        document.getElementById('welcome-message').textContent = `${memberName}님 안녕하세요!`;
        
        passes.forEach(pass => {
            const passBtn = document.createElement('button');
            passBtn.className = 'btn btn-outline-primary m-2';
            passBtn.innerHTML = `
                <strong>${pass.pass_name}</strong><br>
                <small>잔여: ${pass.remaining_days}일</small>
            `;
            passBtn.onclick = () => this.processCheckin(memberNumber, pass.pass_id);
            passListDiv.appendChild(passBtn);
        });
        
        document.getElementById('pass-selection').style.display = 'block';
    }
    
    async processCheckin(memberNumber, passId) {
        try {
            // PHP 체크인 처리 호출
            const response = await fetch('/teventmem/process_checkin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    member_number: memberNumber,
                    pass_id: passId
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showCheckinSuccess();
            } else {
                this.showError(result.message);
            }
        } catch (error) {
            this.showError('체크인 처리 중 오류가 발생했습니다');
        }
    }
    
    showCheckinSuccess() {
        Swal.fire({
            icon: 'success',
            title: '입장 완료',
            text: '즐거운 운동 되세요!',
            timer: 10000,  // 10초 후 자동 닫기
            showConfirmButton: true,
            confirmButtonText: '확인'
        }).then(() => {
            // 페이지 새로고침
            location.reload();
        });
    }
    
    showUserFriendlyError(errorCode, message) {
        let userMessage = message;
        
        // 에러 코드별 친근한 메시지
        switch(errorCode) {
            case 'FACE_NOT_FOUND':
                userMessage = '얼굴을 찾을 수 없습니다. 카메라를 정면으로 바라봐 주세요.';
                break;
            case 'LIVENESS_FAILED':
                userMessage = '조명이 밝은 곳에서 다시 시도해 주세요.';
                break;
            case 'NO_MATCH':
                userMessage = '등록되지 않은 회원입니다. 회원 등록을 먼저 해주세요.';
                break;
            case 'SCREEN_DETECTED':
                userMessage = '화면이 감지되었습니다. 직접 카메라를 보고 시도해 주세요.';
                break;
        }
        
        // 토스트 메시지로 표시 (덜 방해적)
        toastr.info(userMessage, '', {
            timeOut: 3000,
            positionClass: 'toast-top-center'
        });
    }
    
    showError(message) {
        toastr.error(message, '오류', {
            timeOut: 5000,
            positionClass: 'toast-top-center'
        });
    }
}

// 페이지 로드 시 자동 시작
document.addEventListener('DOMContentLoaded', () => {
    const checkin = new FaceCheckin();
    checkin.init();
});
```

---

## 3️⃣ 키오스크 API 구현

### PHP 서비스 래퍼
```php
// app/Libraries/FaceRecognitionService.php
<?php
namespace App\Libraries;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class FaceRecognitionService {
    private $client;
    private $baseUrl;
    private $timeout = 30;
    
    public function __construct() {
        $faceHost = env('FACE_RECOGNITION_HOST', 'localhost');
        $facePort = env('FACE_RECOGNITION_PORT', '5001');
        
        $this->baseUrl = "http://{$faceHost}:{$facePort}";
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => $this->timeout,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
    }
    
    /**
     * 키오스크용 얼굴 인식 (지점 필터링)
     */
    public function recognizeForKiosk($imageBase64, $branchCode) {
        try {
            $response = $this->client->post('/api/face/recognize_for_checkin', [
                'json' => [
                    'image' => $imageBase64,
                    'param1' => 'KIOSK',  // 키오스크 식별자
                    'param2' => $branchCode,
                    'require_liveness' => true
                ]
            ]);
            
            $result = json_decode($response->getBody(), true);
            
            // 추가 비즈니스 로직
            if ($result['success']) {
                // 이용권 유효성 검증
                $result['data']['valid_passes'] = $this->validatePasses(
                    $result['data']['available_passes']
                );
                
                // 로그 기록
                $this->logKioskAccess($result['data']['member_number'], $branchCode);
            }
            
            return $result;
            
        } catch (RequestException $e) {
            return [
                'success' => false,
                'error' => 'API_ERROR',
                'message' => '얼굴 인식 서비스에 연결할 수 없습니다'
            ];
        }
    }
    
    /**
     * 회원 얼굴 일괄 등록
     */
    public function batchRegister($members) {
        $results = [];
        
        foreach ($members as $member) {
            try {
                $response = $this->client->post('/api/face/register', [
                    'json' => [
                        'image' => $member['image'],
                        'member_number' => $member['number'],
                        'name' => $member['name'],
                        'param1' => $member['company'],
                        'param2' => $member['branch'],
                        'update_if_exists' => true
                    ]
                ]);
                
                $results[] = [
                    'member_number' => $member['number'],
                    'success' => true
                ];
                
            } catch (\Exception $e) {
                $results[] = [
                    'member_number' => $member['number'],
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $results;
    }
    
    /**
     * 얼굴 데이터 마이그레이션
     */
    public function migrateFaceData($oldSystem = 'face_id', $newSystem = 'insightface') {
        // 기존 데이터 조회
        $oldData = $this->db->table('member_faces_old')->get();
        
        $migrated = 0;
        $failed = 0;
        
        foreach ($oldData as $record) {
            try {
                // 이미지 재처리
                $newEncoding = $this->reprocessImage($record->face_image);
                
                // 새 시스템에 저장
                $this->db->table('member_faces')->insert([
                    'member_number' => $record->member_number,
                    'face_encoding' => $newEncoding,
                    'quality_score' => 0,
                    'param1' => $record->company_code,
                    'param2' => $record->branch_code,
                    'migrated_from' => $oldSystem,
                    'created_at' => now()
                ]);
                
                $migrated++;
                
            } catch (\Exception $e) {
                log_message('error', "Migration failed for {$record->member_number}: " . $e->getMessage());
                $failed++;
            }
        }
        
        return [
            'total' => count($oldData),
            'migrated' => $migrated,
            'failed' => $failed
        ];
    }
    
    private function validatePasses($passes) {
        $validPasses = [];
        
        foreach ($passes as $pass) {
            // 유효기간 체크
            if ($pass['remaining_days'] > 0) {
                // 시간대별 제한 체크
                if ($this->isPassValidForCurrentTime($pass)) {
                    $validPasses[] = $pass;
                }
            }
        }
        
        return $validPasses;
    }
    
    private function isPassValidForCurrentTime($pass) {
        $currentHour = date('H');
        
        // 예: 새벽 이용권은 04:00-08:00만 가능
        if (strpos($pass['pass_name'], '새벽') !== false) {
            return $currentHour >= 4 && $currentHour < 8;
        }
        
        return true;
    }
    
    private function logKioskAccess($memberNumber, $branchCode) {
        $this->db->table('kiosk_access_logs')->insert([
            'member_number' => $memberNumber,
            'branch_code' => $branchCode,
            'access_time' => now(),
            'device_type' => 'KIOSK'
        ]);
    }
}
```

---

## 4️⃣ 관리자 모니터링 구현

### 대시보드 페이지
```php
// admin/face_recognition_dashboard.php
<div class="dashboard-container">
    <h2>얼굴 인식 시스템 모니터링</h2>
    
    <!-- 실시간 상태 -->
    <div class="status-cards">
        <div class="card">
            <h4>서비스 상태</h4>
            <span id="service-status" class="badge">확인중...</span>
        </div>
        <div class="card">
            <h4>오늘 등록</h4>
            <span id="today-registrations">0</span>명
        </div>
        <div class="card">
            <h4>오늘 인식</h4>
            <span id="today-recognitions">0</span>건
        </div>
        <div class="card">
            <h4>성공률</h4>
            <span id="success-rate">0</span>%
        </div>
    </div>
    
    <!-- 실시간 로그 -->
    <div class="log-container">
        <h3>실시간 활동 로그</h3>
        <div id="activity-log"></div>
    </div>
    
    <!-- 통계 차트 -->
    <div class="charts">
        <canvas id="hourly-chart"></canvas>
        <canvas id="success-chart"></canvas>
    </div>
</div>

<script>
// 실시간 모니터링
class FaceRecognitionMonitor {
    constructor() {
        this.updateInterval = 5000; // 5초마다 업데이트
        this.init();
    }
    
    async init() {
        await this.checkServiceStatus();
        await this.loadStatistics();
        this.startRealtimeUpdates();
        this.initCharts();
    }
    
    async checkServiceStatus() {
        try {
            const response = await fetch('http://localhost:5001/api/face/health');
            const data = await response.json();
            
            const statusEl = document.getElementById('service-status');
            if (data.status === 'healthy') {
                statusEl.className = 'badge badge-success';
                statusEl.textContent = '정상 작동중';
            } else {
                statusEl.className = 'badge badge-danger';
                statusEl.textContent = '오류';
            }
        } catch (error) {
            document.getElementById('service-status').className = 'badge badge-danger';
            document.getElementById('service-status').textContent = '연결 실패';
        }
    }
    
    async loadStatistics() {
        const response = await fetch('/admin/api/face_recognition_stats');
        const stats = await response.json();
        
        document.getElementById('today-registrations').textContent = stats.registrations;
        document.getElementById('today-recognitions').textContent = stats.recognitions;
        document.getElementById('success-rate').textContent = stats.successRate;
    }
    
    startRealtimeUpdates() {
        // WebSocket 연결
        const ws = new WebSocket('ws://localhost:5001/ws/monitor');
        
        ws.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.addLogEntry(data);
            this.updateCharts(data);
        };
        
        // 정기 업데이트
        setInterval(() => {
            this.checkServiceStatus();
            this.loadStatistics();
        }, this.updateInterval);
    }
    
    addLogEntry(data) {
        const logDiv = document.getElementById('activity-log');
        const entry = document.createElement('div');
        entry.className = `log-entry ${data.success ? 'success' : 'error'}`;
        entry.innerHTML = `
            <span class="time">${new Date().toLocaleTimeString()}</span>
            <span class="action">${data.action}</span>
            <span class="member">${data.member_number || '-'}</span>
            <span class="result">${data.message}</span>
        `;
        
        logDiv.insertBefore(entry, logDiv.firstChild);
        
        // 최대 50개 로그 유지
        while (logDiv.children.length > 50) {
            logDiv.removeChild(logDiv.lastChild);
        }
    }
    
    initCharts() {
        // 시간별 사용량 차트
        this.hourlyChart = new Chart(document.getElementById('hourly-chart'), {
            type: 'line',
            data: {
                labels: Array.from({length: 24}, (_, i) => `${i}시`),
                datasets: [{
                    label: '인식 횟수',
                    data: new Array(24).fill(0),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });
        
        // 성공률 차트
        this.successChart = new Chart(document.getElementById('success-chart'), {
            type: 'doughnut',
            data: {
                labels: ['성공', '실패'],
                datasets: [{
                    data: [0, 0],
                    backgroundColor: ['#28a745', '#dc3545']
                }]
            }
        });
    }
    
    updateCharts(data) {
        // 차트 데이터 업데이트 로직
    }
}

// 초기화
new FaceRecognitionMonitor();
</script>
```

---

## 📋 체크리스트

### 구현 전 준비사항
- [ ] Python 서비스 실행 확인
- [ ] 데이터베이스 테이블 생성
- [ ] HTTPS 인증서 설정 (프로덕션)
- [ ] 카메라 권한 설정

### 회원 등록 구현
- [ ] 카메라 UI 구현
- [ ] 자동 캡처 로직
- [ ] Liveness 경고 처리
- [ ] 중복 등록 방지

### 체크인 구현
- [ ] 자동 인식 시작
- [ ] 이용권 조회 연동
- [ ] 친근한 메시지 적용
- [ ] 자동 새로고침

### 보안 검증
- [ ] Liveness Detection 테스트
- [ ] PC 화면 감지 테스트
- [ ] 품질 검사 확인
- [ ] 세션 보안 적용

### 성능 최적화
- [ ] 이미지 압축률 조정
- [ ] API 응답 캐싱
- [ ] 동시 요청 제한
- [ ] 에러 재시도 로직

## 🚀 배포 가이드

### 개발 환경
```bash
# .env.development
FACE_RECOGNITION_HOST=localhost
FACE_RECOGNITION_PORT=5001
FACE_RECOGNITION_DEBUG=true
```

### 프로덕션 환경
```bash
# .env.production
FACE_RECOGNITION_HOST=face-api.spoqplus.com
FACE_RECOGNITION_PORT=443
FACE_RECOGNITION_DEBUG=false
FACE_RECOGNITION_SSL=true
```

### Docker 배포
```dockerfile
# Dockerfile
FROM python:3.8
WORKDIR /app
COPY requirements.txt .
RUN pip install -r requirements.txt
COPY . .
EXPOSE 5001
CMD ["python", "insightface_service.py"]
```

### 시스템 서비스 등록
```bash
# /etc/systemd/system/face-recognition.service
[Unit]
Description=Face Recognition Service
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/face_recognition
ExecStart=/usr/bin/python3 insightface_service.py
Restart=always

[Install]
WantedBy=multi-user.target
```

## 📊 모니터링 및 로그

### 로그 수집
```python
# 로그 설정
import logging
logging.basicConfig(
    filename='face_recognition.log',
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
```

### 메트릭 수집
- 일일 등록 수
- 인식 성공률
- 평균 응답 시간
- 에러 발생률

### 알림 설정
- 서비스 다운 알림
- 성공률 저하 알림
- 디스크 용량 경고