<style>
/* 모바일 최적화 스타일 - bank_account_management 스타일 참고 */
* {
    box-sizing: border-box;
    -webkit-tap-highlight-color: transparent;
}

body {
    background: #f5f7fa;
    margin: 0;
    padding: 0;
}

.register-container {
    background: #f8f9fa;
    min-height: 100vh;
}

.register-header {
    background: #fff;
    padding: 30px 20px 20px;
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 20px;
    text-align: center;
}

.register-header h2 {
    font-size: 24px;
    margin: 0 0 5px 0;
    font-weight: 600;
    color: #343a40;
}

.register-header p {
    margin: 0;
    color: #6c757d;
    font-size: 14px;
}

.form-section {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin: 0 15px 20px;
    transition: all 0.3s ease;
}

.form-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.form-section-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #343a40;
    display: flex;
    align-items: center;
}

.form-section-title i {
    font-size: 18px;
    color: #007bff;
    margin-right: 10px;
}

.form-group {
    margin-bottom: 20px;
    position: relative;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #495057;
    font-size: 14px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #fff;
    -webkit-appearance: none;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.form-group input[readonly] {
    background-color: #f8f9fa;
    color: #6c757d;
    cursor: not-allowed;
}

.form-text {
    font-size: 12px;
    margin-top: 4px;
    color: #6c757d;
}

/* 라디오 버튼 스타일 개선 */
.radio-group {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.radio-label {
    flex: 1;
    position: relative;
}

.radio-label input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.radio-label span {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px;
    text-align: center;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    background: #fff;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
    height: 100%;
    min-height: 45px;
}

.radio-label input[type="radio"]:checked + span {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

/* 사진 섹션 개선 */
.photo-section {
    text-align: center;
    padding: 25px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.photo-preview {
    width: 120px;
    height: 120px;
    margin: 0 auto 20px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    overflow: hidden;
    position: relative;
    transition: all 0.3s ease;
}

.photo-preview:hover {
    border-color: #007bff;
}

.photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-placeholder {
    color: #adb5bd;
    font-size: 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.photo-placeholder i {
    font-size: 48px;
    display: block;
    margin-bottom: 10px;
    color: #dee2e6;
}

.btn-take-photo {
    background: #28a745;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-take-photo:hover {
    background: #218838;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40,167,69,0.35);
}

.btn-take-photo:active {
    transform: translateY(0);
}

.register-actions {
    padding: 20px 15px;
    background: #fff;
    border-top: 1px solid #e9ecef;
    position: sticky;
    bottom: 0;
    z-index: 100;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
}

.btn-register {
    width: 100%;
    padding: 16px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-register:hover {
    background: #0056b3;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.35);
}

.btn-register:active {
    transform: translateY(0);
}

.btn-register:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* 카메라 모달 개선 */
.camera-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #000;
    z-index: 9999;
}

/* 웹(PC) 전용 카메라 모달 스타일 */
@media (min-width: 769px) {
    .camera-modal.web-modal {
        background: rgba(0, 0, 0, 0.8);
        display: none;
        align-items: center;
        justify-content: center;
    }
    
    .camera-modal.web-modal .camera-container {
        position: relative;
        width: 90%;
        max-width: 800px;
        height: auto;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
    
    .camera-modal.web-modal .camera-header {
        background: #4a90e2;
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .camera-modal.web-modal .camera-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 500;
    }
    
    .camera-modal.web-modal .camera-header .close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.3s;
    }
    
    .camera-modal.web-modal .camera-header .close-btn:hover {
        opacity: 0.8;
    }
    
    .camera-modal.web-modal #camera-video {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .camera-modal.web-modal .video-wrapper {
        position: relative;
        background: #000;
    }
    
    /* 얼굴 실루엣 가이드 (웹 전용) */
    .face-silhouette {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 200px;
        height: 260px;
        border: 3px dashed #4a90e2;
        border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
        pointer-events: none;
        opacity: 0.7;
        display: none;
    }
    
    .camera-modal.web-modal .face-silhouette {
        display: block;
    }
    
    .face-silhouette::before {
        content: '얼굴을 이 영역에 맞춰주세요';
        position: absolute;
        top: -35px;
        left: 50%;
        transform: translateX(-50%);
        color: #4a90e2;
        font-weight: bold;
        white-space: nowrap;
        background-color: rgba(255, 255, 255, 0.95);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .camera-modal.web-modal .camera-controls {
        position: relative;
        background: #f8f9fa;
        padding: 20px;
        display: flex;
        justify-content: center;
        gap: 15px;
        border-top: 1px solid #e0e0e0;
    }
    
    .camera-modal.web-modal .camera-btn {
        width: auto;
        height: auto;
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    
    .camera-modal.web-modal .camera-btn.capture {
        background: #4a90e2;
        color: white;
        border: none;
        font-weight: 500;
    }
    
    .camera-modal.web-modal .camera-btn.capture:hover {
        background: #357abd;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
    }
    
    .camera-modal.web-modal .camera-btn.cancel {
        background: #e0e0e0;
        color: #666;
        border: none;
    }
    
    .camera-modal.web-modal .camera-btn.cancel:hover {
        background: #d0d0d0;
    }
    
    .camera-modal.web-modal .camera-btn.switch {
        background: white;
        color: #4a90e2;
        border: 2px solid #4a90e2;
    }
    
    .camera-modal.web-modal .camera-btn.switch:hover {
        background: #f0f7ff;
    }
}

.camera-container {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
}

#camera-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.camera-controls {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 30px 20px;
    background: linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0.4), transparent);
    display: flex;
    justify-content: center;
    gap: 20px;
}

.camera-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: none;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    margin: 0 10px;
}

.camera-btn.capture {
    background: white;
    color: #333;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.camera-btn.capture:active {
    transform: scale(0.9);
}

.camera-btn.cancel {
    background: rgba(255,255,255,0.2);
    color: white;
    backdrop-filter: blur(10px);
}

.camera-btn.switch {
    background: rgba(255,255,255,0.2);
    color: white;
    backdrop-filter: blur(10px);
    width: 60px;
    height: 60px;
    font-size: 20px;
}

/* 입력 필드 포커스 시 확대 방지 */
@media (max-width: 768px) {
    input, select, textarea {
        font-size: 16px !important;
    }
}

/* 터치 피드백 개선 */
button, .btn-take-photo, .btn-register, .radio-label {
    -webkit-tap-highlight-color: rgba(0, 123, 255, 0.2);
}

/* 스크롤 성능 개선 */
.register-container {
    -webkit-overflow-scrolling: touch;
}

.required {
    color: #dc3545;
    font-size: 12px;
    margin-left: 2px;
}

/* 셀렉트 박스 화살표 커스텀 */
select {
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 20px;
    padding-right: 45px;
}

/* 로딩 상태 */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* 애니메이션 */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
    100% {
        opacity: 1;
    }
}

.form-section {
    animation: fadeIn 0.5s ease-out;
}

/* 로딩 애니메이션 */
.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(0, 123, 255, 0.2);
    border-radius: 50%;
    border-top-color: #007bff;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* 입력 오류 스타일 */
.form-group.error input,
.form-group.error select {
    border-color: #dc3545;
    background: #fff5f5;
}

.error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
    display: none;
    animation: shake 0.3s ease;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.form-group.error .error-message {
    display: block;
}

/* 성공 피드백 */
.success-check {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #28a745;
    font-size: 18px;
    display: none;
}

.form-group.success .success-check {
    display: block;
    animation: checkmark 0.3s ease;
}

@keyframes checkmark {
    0% {
        transform: translateY(-50%) scale(0);
    }
    50% {
        transform: translateY(-50%) scale(1.2);
    }
    100% {
        transform: translateY(-50%) scale(1);
    }
}
</style>

<!-- jQuery and Required Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- 디버그 정보 표시 영역 (개발시에만 사용) -->
<div id="debug-info" style="position: fixed; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.8); color: white; padding: 10px; font-size: 12px; max-height: 200px; overflow-y: auto; display: none; z-index: 9999;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <strong>Debug Info</strong>
        <button onclick="document.getElementById('debug-info').style.display='none'" style="background: red; color: white; border: none; padding: 5px 10px;">Close</button>
    </div>
    <div id="debug-content"></div>
</div>

<!-- Main content -->
<section class="content">
    <div class="register-container">
        <div class="register-header">
            <h2>회원가입</h2>
            <p>ARGOS SpoQ 회원가입을 환영합니다</p>
        </div>

        <form id="registerForm" method="post">
            <!-- 회사/지점 선택 섹션 -->
            <div class="form-section">
                <div class="form-section-title"><i class="fas fa-building"></i> 회사/지점 선택</div>
                <div class="form-group">
                    <label for="comp_select">회사 선택 <span class="required">*</span></label>
                    <select id="comp_select" name="comp_cd" required>
                        <option value="">회사를 선택해주세요</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="bcoff_select">지점 선택 <span class="required">*</span></label>
                    <select id="bcoff_select" name="bcoff_cd" required disabled>
                        <option value="">먼저 회사를 선택해주세요</option>
                    </select>
                </div>
            </div>

            <!-- 기본 정보 섹션 -->
            <div class="form-section">
                <div class="form-section-title"><i class="fas fa-user"></i> 기본 정보</div>
                
                <div class="form-group">
                    <label for="mem_nm">이름 <span class="required">*</span></label>
                    <input type="text" id="mem_nm" name="mem_nm" required>
                    <i class="fas fa-check success-check"></i>
                </div>

                <div class="form-group">
                    <label for="mem_telno">전화번호 <span class="required">*</span></label>
                    <input type="tel" id="mem_telno" name="mem_telno" value="<?= $_GET['phone'] ?? '' ?>" readonly>
                    <small class="form-text text-muted">* 인증된 전화번호는 변경할 수 없습니다</small>
                </div>

                <div class="form-group">
                    <label for="bthday">생년월일</label>
                    <input type="text" id="bthday" name="bthday" placeholder="YYYY-MM-DD" maxlength="10">
                </div>

                <div class="form-group">
                    <label>성별 <span class="required">*</span></label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="mem_gendr" value="M" required>
                            <span>남성</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="mem_gendr" value="F" required>
                            <span>여성</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="mem_email">이메일</label>
                    <input type="email" id="mem_email" name="mem_email">
                </div>

                <div class="form-group">
                    <label for="mem_addr">주소</label>
                    <input type="text" id="mem_addr" name="mem_addr">
                </div>
            </div>

            <!-- 사진 등록 섹션 -->
            <div class="form-section">
                <div class="form-section-title"><i class="fas fa-camera"></i> 사진 등록</div>
                <div class="photo-section">
                    <div class="photo-preview">
                        <img id="photo_preview" style="display:none;">
                        <div id="photo_placeholder" class="photo-placeholder">
                            <i class="fas fa-user-circle"></i>
                            <span>사진을 등록해주세요</span>
                        </div>
                    </div>
                    <button type="button" class="btn-take-photo" onclick="openCamera()">
                        <i class="fas fa-camera"></i> 사진 촬영
                    </button>
                    <div id="face_recognition_status" style="display:none; margin-top: 15px; padding: 10px; border-radius: 6px; font-size: 13px; line-height: 1.5;"></div>
                    <input type="hidden" id="mem_photo" name="mem_photo">
                    <input type="hidden" id="face_data" name="face_data">
                </div>
            </div>

        </form>
    </div>
    
    <div class="register-actions">
        <button type="submit" form="registerForm" class="btn-register" id="submitBtn">
            <i class="fas fa-check-circle"></i> 회원가입 완료
        </button>
    </div>
</section>

<!-- 카메라 모달 -->
<div class="camera-modal" id="cameraModal">
    <div class="camera-container">
        <video id="camera-video" autoplay></video>
        <canvas id="camera-canvas" style="display:none;"></canvas>
        <div class="camera-controls">
            <button class="camera-btn cancel" onclick="closeCamera()">
                <i class="fas fa-times"></i>
            </button>
            <button class="camera-btn capture" onclick="capturePhoto()">
                <i class="fas fa-camera"></i>
            </button>
            <button class="camera-btn switch" onclick="switchCamera()">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>
</div>

<script>
// 모바일 디버깅 팁: 
// 1. Chrome 모바일에서 chrome://inspect 사용
// 2. Safari 모바일에서 Mac Safari의 개발자 도구 사용
// 3. alert() 대신 console.log()를 사용하고 원격 디버깅으로 확인
// 4. 또는 화면에 디버그 메시지를 표시하는 방법:
function debugLog(msg) {
    console.log(msg);
    // 디버그 정보를 화면에 표시 (개발시에만 사용)
    const debugInfo = document.getElementById('debug-info');
    const debugContent = document.getElementById('debug-content');
    if (debugInfo && debugContent) {
        debugInfo.style.display = 'block';
        const time = new Date().toLocaleTimeString();
        debugContent.innerHTML += `[${time}] ${msg}<br>`;
        debugContent.scrollTop = debugContent.scrollHeight;
    }
}

// DOM이 로드된 후 실행
document.addEventListener('DOMContentLoaded', function() {
    debugLog('DOM loaded, initializing registration...');
    initializeRegistration();
});

// 얼굴 인식 서버 상태 확인
function checkFaceServerStatus() {
    debugLog('얼굴 인식 서버 상태 확인 중...');
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/FaceTest/health', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const result = JSON.parse(xhr.responseText);
                if (result.success) {
                    debugLog('✅ 얼굴 인식 서버 정상 작동');
                } else {
                    debugLog('❌ 얼굴 인식 서버 연결 실패: ' + (result.error || 'Unknown error'));
                }
            } catch (e) {
                debugLog('❌ 얼굴 인식 서버 응답 파싱 오류');
            }
        } else {
            debugLog('❌ 얼굴 인식 서버 연결 실패: HTTP ' + xhr.status);
        }
    };
    xhr.onerror = function() {
        debugLog('❌ 얼굴 인식 서버 네트워크 오류');
    };
    xhr.send();
}

function initializeRegistration() {
    // 얼굴 인식 서버 상태 확인
    checkFaceServerStatus();
    
    // 회사 목록 로드
    loadCompanyList();
    
    // 전화번호 확인
    checkPhoneVerification();
    
    // 회사 선택 시 지점 목록 로드
    const compSelect = document.getElementById('comp_select');
    if (compSelect) {
        compSelect.addEventListener('change', function() {
            const comp_cd = this.value;
            if (comp_cd) {
                loadBranchList(comp_cd);
            } else {
                const bcoffSelect = document.getElementById('bcoff_select');
                bcoffSelect.disabled = true;
                bcoffSelect.innerHTML = '<option value="">먼저 회사를 선택해주세요</option>';
            }
        });
    }
    
    // 폼 제출 처리
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitRegistration();
        });
    }
}

// 전화번호 인증 확인
function checkPhoneVerification() {
    const phoneInput = document.getElementById('mem_telno');
    const phone = phoneInput ? phoneInput.value : '';
    if (!phone) {
        alert('전화번호 인증이 필요합니다.');
        window.location.href = '/login';
        return;
    }
    
    // 세션에서 인증 여부 확인
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/api/checkPhoneVerification', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const result = JSON.parse(xhr.responseText);
                if (result.result !== 'true') {
                    alert('전화번호 인증이 만료되었습니다. 다시 인증해주세요.');
                    window.location.href = '/login';
                }
            } catch (e) {
                console.error('JSON parse error:', e);
            }
        }
    };
    xhr.send('phone_number=' + encodeURIComponent(phone));
}

// 회사 목록 로드
function loadCompanyList() {
    debugLog('회사 목록 로드 시작...');
    const select = document.getElementById('comp_select');
    if (!select) {
        debugLog('comp_select 요소를 찾을 수 없음');
        return;
    }
    
    select.innerHTML = '<option value="">로딩중...</option>';
    select.classList.add('loading');
    
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/api/getCompanyList', true);
    xhr.onload = function() {
        debugLog('회사 목록 응답: ' + xhr.status);
        select.classList.remove('loading');
        if (xhr.status === 200) {
            try {
                const result = JSON.parse(xhr.responseText);
                debugLog('회사 목록 결과: ' + JSON.stringify(result));
                
                if (result.result === 'true' && result.companies && result.companies.length > 0) {
                    select.innerHTML = '<option value="">회사를 선택해주세요</option>';
                    result.companies.forEach(function(company) {
                        const option = document.createElement('option');
                        option.value = company.COMP_CD;
                        option.textContent = company.COMP_NM;
                        select.appendChild(option);
                    });
                    debugLog('회사 목록 로드 완료: ' + result.companies.length + '개');
                } else {
                    select.innerHTML = '<option value="">회사 목록이 없습니다</option>';
                    console.error('회사 목록 없음:', result);
                }
            } catch (e) {
                console.error('회사 목록 파싱 오류:', e);
                console.log('응답 내용:', xhr.responseText);
                select.innerHTML = '<option value="">회사 목록 로드 실패</option>';
            }
        } else {
            console.error('회사 목록 서버 오류:', xhr.status);
            select.innerHTML = '<option value="">서버 오류</option>';
        }
    };
    xhr.onerror = function() {
        debugLog('회사 목록 네트워크 오류');
        select.classList.remove('loading');
        select.innerHTML = '<option value="">네트워크 오류</option>';
        setTimeout(function() {
            if (confirm('회사 목록을 다시 불러오시겠습니까?')) {
                loadCompanyList();
            }
        }, 500);
    };
    xhr.send();
}

// 지점 목록 로드
function loadBranchList(comp_cd) {
    const select = document.getElementById('bcoff_select');
    if (!select) return;
    
    select.disabled = true;
    select.innerHTML = '<option value="">로딩중...</option>';
    
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/api/getBranchList?comp_cd=' + encodeURIComponent(comp_cd), true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const result = JSON.parse(xhr.responseText);
                if (result.result === 'true' && result.branches) {
                    select.disabled = false;
                    select.innerHTML = '<option value="">지점을 선택해주세요</option>';
                    result.branches.forEach(function(branch) {
                        const option = document.createElement('option');
                        option.value = branch.BCOFF_CD;
                        option.textContent = branch.BCOFF_NM;
                        select.appendChild(option);
                    });
                }
            } catch (e) {
                alert('지점 목록을 불러오는데 실패했습니다.');
                select.disabled = false;
                select.innerHTML = '<option value="">지점 로드 실패</option>';
            }
        }
    };
    xhr.onerror = function() {
        alert('지점 목록을 불러오는데 실패했습니다.');
        select.disabled = false;
        select.innerHTML = '<option value="">지점 로드 실패</option>';
    };
    xhr.send();
}

// 카메라 관련 변수
let stream = null;
let capturedPhotoData = null;
let currentFacingMode = 'user';

// 카메라 열기
function openCamera() {
    const modal = document.getElementById('cameraModal');
    if (modal) {
        modal.style.display = 'block';
        startCamera();
    }
}

// 카메라 시작
function startCamera() {
    const video = document.getElementById('camera-video');
    const constraints = {
        video: {
            facingMode: currentFacingMode,
            width: { ideal: 1280 },
            height: { ideal: 720 }
        }
    };
    
    // 기존 스트림이 있으면 중지
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
    
    navigator.mediaDevices.getUserMedia(constraints)
        .then(function(mediaStream) {
            stream = mediaStream;
            video.srcObject = mediaStream;
        })
        .catch(function(err) {
            console.error('카메라 오류:', err);
            // 전면 카메라가 없으면 후면 카메라로 시도
            if (currentFacingMode === 'user' && err.name === 'OverconstrainedError') {
                currentFacingMode = 'environment';
                startCamera();
            } else {
                alert('카메라 접근 권한이 필요합니다.');
                closeCamera();
            }
        });
}

// 카메라 전환
function switchCamera() {
    currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
    startCamera();
}

// 사진 촬영
function capturePhoto() {
    const video = document.getElementById('camera-video');
    const canvas = document.getElementById('camera-canvas');
    const context = canvas.getContext('2d');
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0);
    
    capturedPhotoData = canvas.toDataURL('image/jpeg', 0.8);
    
    // 미리보기 표시
    const photoPreview = document.getElementById('photo_preview');
    const photoPlaceholder = document.getElementById('photo_placeholder');
    const memPhoto = document.getElementById('mem_photo');
    
    if (photoPreview) {
        photoPreview.src = capturedPhotoData;
        photoPreview.style.display = 'block';
    }
    if (photoPlaceholder) {
        photoPlaceholder.style.display = 'none';
    }
    if (memPhoto) {
        memPhoto.value = capturedPhotoData;
    }
    
    // 얼굴 인식 처리
    processFaceRecognition(capturedPhotoData);
    
    closeCamera();
}

// 카메라 닫기
function closeCamera() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    const modal = document.getElementById('cameraModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// 얼굴 인식 상태 표시 함수
function showFaceRecognitionStatus(type, message) {
    const statusDiv = document.getElementById('face_recognition_status');
    if (!statusDiv) return;
    
    // 타입별 스타일 설정
    if (type === 'processing') {
        statusDiv.style.backgroundColor = '#fff3cd';
        statusDiv.style.color = '#856404';
        statusDiv.style.border = '1px solid #ffeaa7';
        statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + message;
    } else if (type === 'success') {
        statusDiv.style.backgroundColor = '#d4edda';
        statusDiv.style.color = '#155724';
        statusDiv.style.border = '1px solid #c3e6cb';
        statusDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + message;
    } else if (type === 'error') {
        statusDiv.style.backgroundColor = '#f8d7da';
        statusDiv.style.color = '#721c24';
        statusDiv.style.border = '1px solid #f5c6cb';
        statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + message;
    }
    
    statusDiv.style.display = 'block';
    
    // 성공 시 5초 후 자동 숨김
    if (type === 'success') {
        setTimeout(() => {
            statusDiv.style.display = 'none';
        }, 5000);
    }
}

// 얼굴 인식 처리
function processFaceRecognition(photoData) {
    debugLog('얼굴 인식 처리 시작...');
    
    // 처리 중 표시
    showFaceRecognitionStatus('processing', '얼굴 분석 중...');
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/FaceTest/recognize_for_registration', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        debugLog('얼굴 인식 응답: ' + xhr.status);
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                debugLog('얼굴 인식 결과: ' + JSON.stringify(response));
                
                // face_detected, detected, success 중 하나라도 true면 성공
                if (response.suitable_for_registration) {
                    const faceDataInput = document.getElementById('face_data');
                    if (faceDataInput) {
                        faceDataInput.value = JSON.stringify(response);
                        debugLog('얼굴 데이터 저장 완료');
                        showFaceRecognitionStatus('success', '얼굴이 정상적으로 인식되었습니다!');
                    }
                } else {
                    // 얼굴 검출 실패 - 상세한 안내 메시지 제공
                    let userMessage = '얼굴이 감지되지 않았습니다.';
                    let guidance = [];
                    // 품질 검증 실패 시 상세 권장사항 제공
                    if (response.suitable_for_registration === false && response.recommendations) {
                        // API에서 제공하는 권장사항 사용
                        guidance = response.recommendations;
                    } else if (response.face_data && response.face_data.quality_details) {
                        // 품질 상세 정보를 기반으로 안내
                        const details = response.face_data.quality_details;
                        
                        if (details.face_size_ratio < 0.15) {
                            guidance.push('• 얼굴이 너무 작습니다. 카메라에 더 가까이 와주세요.');
                        } else if (details.face_size_ratio > 0.7) {
                            guidance.push('• 얼굴이 너무 큽니다. 조금 뒤로 물러나주세요.');
                        }
                        
                        if (!details.face_centered) {
                            guidance.push('• 얼굴을 화면 중앙에 위치시켜주세요.');
                        }
                        
                        if (response.face_data.face_pose && !response.face_data.face_pose.is_frontal) {
                            const pose = response.face_data.face_pose;
                            if (Math.abs(pose.yaw) > 15) {
                                guidance.push('• 정면을 바라봐주세요. (좌우 각도 조정)');
                            }
                            if (Math.abs(pose.pitch) > 15) {
                                guidance.push('• 고개를 똑바로 들어주세요. (상하 각도 조정)');
                            }
                            if (Math.abs(pose.roll) > 10) {
                                guidance.push('• 머리를 똑바로 세워주세요. (기울기 조정)');
                            }
                        }
                        
                    } else {
                        // 기본 안내 메시지
                        guidance = [
                            '• 카메라를 정면으로 바라봐주세요1.',
                            '• 조명이 밝은 곳에서 촬영해주세요.',
                            '• 얼굴이 화면 중앙에 오도록 조정해주세요.'
                        ];
                    }
                    
                    // 최종 메시지 구성 (모바일용 HTML 포맷)
                    if (guidance.length > 0) {
                        userMessage = '얼굴 인식 품질이 충분하지 않습니다.<br><br>' + 
                                      '<strong>개선 방법:</strong><br>' + 
                                      guidance.map(g => g.replace('•', '▫')).join('<br>');
                    } else {
                        userMessage = response.error || '얼굴이 감지되지 않았습니다. 다시 촬영해주세요.';
                    }
                    
                    showFaceRecognitionStatus('error', userMessage);
                    
                    // 재촬영 버튼 활성화를 위해 이미지 데이터 제거
                    document.getElementById('mem_photo').value = '';
                    document.getElementById('face_data').value = '';
                    document.getElementById('photo_preview').style.display = 'none';
                    const placeholder = document.getElementById('photo_placeholder');
                    if (placeholder) {
                        placeholder.style.display = 'flex';
                    }
                }
            } catch (e) {
                console.error('얼굴 인식 응답 파싱 오류:', e);
                console.log('응답 내용:', xhr.responseText);
                showFaceRecognitionStatus('error', '얼굴 인식 서버 응답 오류입니다. 관리자에게 문의해주세요.');
                // 얼굴 인식 서버에 문제가 있어도 사진은 저장되도록
                const faceDataInput = document.getElementById('face_data');
                if (faceDataInput) {
                    faceDataInput.value = JSON.stringify({face_detected: true, skip_validation: true});
                }
            }
        } else {
            console.error('얼굴 인식 서버 오류:', xhr.status);
            showFaceRecognitionStatus('error', '얼굴 인식 서버에 연결할 수 없습니다. 잠시 후 다시 시도해주세요.');
            // 서버 오류시에도 진행 가능하도록
            const faceDataInput = document.getElementById('face_data');
            if (faceDataInput) {
                faceDataInput.value = JSON.stringify({face_detected: true, skip_validation: true});
            }
        }
    };
    xhr.onerror = function() {
        console.error('얼굴 인식 네트워크 오류');
        showFaceRecognitionStatus('error', '네트워크 연결 오류입니다. 인터넷 연결을 확인해주세요.');
        // 네트워크 오류시에도 진행 가능하도록
        const faceDataInput = document.getElementById('face_data');
        if (faceDataInput) {
            faceDataInput.value = JSON.stringify({face_detected: true, skip_validation: true});
        }
    };
    xhr.send('image=' + encodeURIComponent(photoData.split(',')[1]));
}

// 회원가입 제출
function submitRegistration() {
    // 모든 에러 메시지 초기화
    $('.form-group').removeClass('error');
    
    let isValid = true;
    
    // 유효성 검사
    if (!$('#comp_select').val()) {
        showFieldError($('#comp_select'), '회사를 선택해주세요.');
        isValid = false;
    }
    
    if (!$('#bcoff_select').val()) {
        showFieldError($('#bcoff_select'), '지점을 선택해주세요.');
        isValid = false;
    }
    
    if (!$('#mem_nm').val().trim()) {
        showFieldError($('#mem_nm'), '이름을 입력해주세요.');
        isValid = false;
    }
    
    // 생년월일은 선택사항이므로 체크하지 않음
    
    if (!$('input[name="mem_gendr"]:checked').val()) {
        showFieldError($('input[name="mem_gendr"]').first(), '성별을 선택해주세요.');
        isValid = false;
    }
    
    // 사진은 선택사항으로 변경
    /*
    if (!$('#mem_photo').val()) {
        alert('사진을 등록해주세요.');
        // 사진 섹션으로 스크롤
        $('html, body').animate({
            scrollTop: $('.photo-section').offset().top - 100
        }, 500);
        isValid = false;
    }
    */
    
    if (!isValid) {
        // 첫 번째 에러 필드로 포커스
        $('.form-group.error').first().find('input, select').focus();
        return;
    }
    
    // 체크인 번호 생성
    const phone = $('#mem_telno').val();
    const checkinNo = generateCheckinNumber(phone);
    
    // 폼 데이터 준비
    const formData = {
        comp_bcoff: $('#comp_select').val() + '|' + $('#bcoff_select').val(),
        mem_nm: $('#mem_nm').val(),
        mem_telno: phone,
        bthday: $('#bthday').val(),
        mem_gendr: $('input[name="mem_gendr"]:checked').val(),
        mem_email: $('#mem_email').val(),
        mem_addr: $('#mem_addr').val(),
        mem_photo: $('#mem_photo').val(),
        face_data: $('#face_data').val(),
        checkin_no: checkinNo,
        mem_pass: checkinNo + '5898' // 초기 비밀번호
    };
    
    // 버튼 비활성화
    $('#submitBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> 처리중...');
    
    // 회원가입 요청
    $.ajax({
        url: '/api/mobileRegisterProc',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(result) {
            debugLog('회원가입 응답: ' + JSON.stringify(result), 'info');
            
            if (result.result === 'true') {
                const serverCheckinNo = result.checkin_no || checkinNo;
                alert('회원가입이 완료되었습니다.\n체크인번호: ' + serverCheckinNo + '\n초기 비밀번호: ' + serverCheckinNo + '5898');
                
                // 자동 로그인 처리
                loginAfterRegister(phone);
            } else {
                if (result.is_duplicate) {
                    alert(result.msg || '이미 등록된 전화번호입니다.');
                } else {
                    alert(result.msg || '회원가입에 실패했습니다.');
                }
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-check-circle"></i> 회원가입 완료');
            }
        },
        error: function(xhr) {
            console.error('회원가입 오류:', xhr.responseText);
            try {
                const errorResponse = JSON.parse(xhr.responseText);
                alert('회원가입 오류: ' + (errorResponse.msg || '서버 오류가 발생했습니다.'));
            } catch (e) {
                alert('회원가입 처리 중 오류가 발생했습니다. 콘솔을 확인해주세요.');
            }
            $('#submitBtn').prop('disabled', false).html('회원가입 완료');
        }
    });
}

// 체크인 번호 생성
function generateCheckinNumber(phone) {
    if (phone.length === 11) {
        return phone.substring(3);
    }
    return phone;
}

// 회원가입 후 자동 로그인
function loginAfterRegister(phone) {
    debugLog('자동 로그인 시도: ' + phone, 'info');
    
    $.ajax({
        url: '/api/loginWithPhone',
        type: 'POST',
        data: { phone_number: phone },
        dataType: 'json',
        success: function(result) {
            debugLog('자동 로그인 응답: ' + JSON.stringify(result), 'info');
            
            if (result.result === 'true') {
                // 자동 로그인 토큰 저장
                if (result.auth_token) {
                    localStorage.setItem('spoq_auth_token', result.auth_token);
                    debugLog('자동 로그인 토큰 저장됨', 'info');
                }
                window.location.href = result.redirect || '/api/mmmain';
            } else {
                alert('자동 로그인에 실패했습니다. 로그인 페이지로 이동합니다.');
                window.location.href = '/login';
            }
        },
        error: function(xhr) {
            debugLog('자동 로그인 오류: ' + xhr.responseText, 'error');
            window.location.href = '/login';
        }
    });
}

// 필드 에러 표시
function showFieldError(field, message) {
    const formGroup = field.closest('.form-group');
    formGroup.addClass('error');
    
    // 에러 메시지가 없으면 추가
    if (formGroup.find('.error-message').length === 0) {
        formGroup.append('<div class="error-message">' + message + '</div>');
    } else {
        formGroup.find('.error-message').text(message);
    }
}

// 실시간 유효성 검사
$(document).on('blur', 'input[required], select[required]', function() {
    const field = $(this);
    const formGroup = field.closest('.form-group');
    
    if (field.val().trim()) {
        formGroup.removeClass('error').addClass('success');
    } else {
        formGroup.removeClass('success');
    }
});

// 생년월일 입력 자동 포맷팅
$('#bthday').on('input', function(e) {
    let value = e.target.value.replace(/[^0-9]/g, ''); // 숫자만 추출
    let formattedValue = '';
    let isValid = true;
    
    // 최대 8자리까지만 허용
    if (value.length > 8) {
        value = value.substring(0, 8);
    }
    
    // 연도 체크
    if (value.length >= 4) {
        const year = parseInt(value.substring(0, 4));
        const currentYear = new Date().getFullYear();
        
        // 1900년 미만이거나 현재 연도 초과 시 입력 차단
        if (year < 1900) {
            // 1로 시작하지만 1900 미만인 경우 (예: 1829)
            e.preventDefault();
            e.target.value = e.target.value.substring(0, e.target.value.length - 1);
            return;
        } else if (year > currentYear) {
            // 현재 연도보다 큰 경우
            e.preventDefault();
            e.target.value = e.target.value.substring(0, e.target.value.length - 1);
            return;
        }
        
        formattedValue = value.substring(0, 4) + '-';
        
        // 월 체크
        if (value.length >= 6) {
            const month = parseInt(value.substring(4, 6));
            
            if (month < 1 || month > 12) {
                // 잘못된 월 입력 방지
                value = value.substring(0, 4) + value.substring(6);
                formattedValue = value.substring(0, 4) + '-';
            } else {
                formattedValue += value.substring(4, 6) + '-';
                
                // 일 체크
                if (value.length >= 8) {
                    const day = parseInt(value.substring(6, 8));
                    const daysInMonth = new Date(year, month, 0).getDate();
                    
                    if (day < 1 || day > daysInMonth) {
                        // 잘못된 일 입력 방지
                        value = value.substring(0, 6);
                        formattedValue = value.substring(0, 4) + '-' + value.substring(4, 6) + '-';
                    } else {
                        formattedValue += value.substring(6, 8);
                        
                        // 완전한 날짜가 입력되었을 때 추가 검증
                        const inputDate = new Date(year, month - 1, day);
                        const today = new Date();
                        
                        if (inputDate > today) {
                            // 미래 날짜 입력 방지
                            value = value.substring(0, 6);
                            formattedValue = value.substring(0, 4) + '-' + value.substring(4, 6) + '-';
                            showFieldError($(this), '미래 날짜는 입력할 수 없습니다.');
                        } else {
                            // 에러 메시지 제거
                            $(this).closest('.form-group').removeClass('error').find('.error-message').remove();
                        }
                    }
                } else {
                    formattedValue += value.substring(6);
                }
            }
        } else {
            formattedValue += value.substring(4);
        }
    } else {
        formattedValue = value;
    }
    
    // 커서 위치 계산
    let cursorPosition = e.target.selectionStart;
    let oldValue = e.target.value;
    let oldLength = oldValue.length;
    let newLength = formattedValue.length;
    
    // 값 설정
    e.target.value = formattedValue;
    
    // 커서 위치 조정
    if (oldLength < newLength) {
        cursorPosition += newLength - oldLength;
    }
    e.target.setSelectionRange(cursorPosition, cursorPosition);
});

// 생년월일 키다운 이벤트 (백스페이스 처리 및 입력 제한)
$('#bthday').on('keydown', function(e) {
    const cursorPos = this.selectionStart;
    const value = this.value;
    const numericValue = value.replace(/[^0-9]/g, '');
    
    // 백스페이스 처리
    if (e.key === 'Backspace') {
        // 커서가 하이픈 바로 뒤에 있을 때
        if (cursorPos > 0 && value[cursorPos - 1] === '-') {
            e.preventDefault();
            
            // 하이픈 앞의 숫자도 함께 삭제
            const newValue = value.substring(0, cursorPos - 2) + value.substring(cursorPos);
            this.value = newValue;
            this.setSelectionRange(cursorPos - 2, cursorPos - 2);
            
            // input 이벤트 수동 트리거
            $(this).trigger('input');
        }
        return;
    }
    
    // 숫자가 아닌 키 입력 차단 (특수키 제외)
    if (!/[0-9]/.test(e.key) && e.key.length === 1) {
        e.preventDefault();
        return;
    }
    
    // 최대 길이 체크 (8자리)
    if (numericValue.length >= 8 && /[0-9]/.test(e.key)) {
        e.preventDefault();
        return;
    }
    
    // 연도 첫자리 검증 (1 또는 2만 허용)
    if (numericValue.length === 0 && e.key !== '1' && e.key !== '2') {
        e.preventDefault();
        return;
    }
    
    // 연도 검증을 위한 예상 값 계산
    if (/[0-9]/.test(e.key)) {
        const predictedValue = numericValue + e.key;
        
        // 4자리 연도 검증
        if (predictedValue.length === 4) {
            const year = parseInt(predictedValue);
            const currentYear = new Date().getFullYear();
            
            if (year < 1900 || year > currentYear) {
                e.preventDefault();
                return;
            }
        }
        
        // 월 첫자리 검증 (0 또는 1만 허용)
        if (predictedValue.length === 5 && e.key !== '0' && e.key !== '1') {
            e.preventDefault();
            return;
        }
        
        // 월 검증
        if (predictedValue.length === 6) {
            const month = parseInt(predictedValue.substring(4, 6));
            if (month < 1 || month > 12) {
                e.preventDefault();
                return;
            }
        }
        
        // 일 첫자리 검증 (0, 1, 2, 3만 허용)
        if (predictedValue.length === 7) {
            const dayFirst = e.key;
            if (!['0', '1', '2', '3'].includes(dayFirst)) {
                e.preventDefault();
                return;
            }
        }
    }
});

// 생년월일 유효성 검사
$('#bthday').on('blur', function() {
    const value = $(this).val();
    if (value) {
        const regex = /^\d{4}-\d{2}-\d{2}$/;
        if (!regex.test(value)) {
            showFieldError($(this), '올바른 날짜 형식이 아닙니다. (YYYY-MM-DD)');
        } else {
            const parts = value.split('-');
            const year = parseInt(parts[0]);
            const month = parseInt(parts[1]);
            const day = parseInt(parts[2]);
            
            const date = new Date(year, month - 1, day);
            const today = new Date();
            
            if (date.getFullYear() !== year || date.getMonth() + 1 !== month || date.getDate() !== day) {
                showFieldError($(this), '유효하지 않은 날짜입니다.');
            } else if (date > today) {
                showFieldError($(this), '미래 날짜는 입력할 수 없습니다.');
            } else if (year < 1900) {
                showFieldError($(this), '1900년 이후 날짜를 입력해주세요.');
            } else {
                $(this).closest('.form-group').removeClass('error').addClass('success');
            }
        }
    }
});
</script>

<!-- 디버그 콘솔 HTML -->
<div id="debugConsole">
    <div id="debugHeader">
        <span>디버그 콘솔</span>
        <button id="debugCloseBtn">&times;</button>
        <button id="debugClearBtn">Clear</button>
    </div>
    <div id="debugContent"></div>
</div>

<!-- 디버그 콘솔 열기 버튼 (플로팅) -->
<button id="debugOpenBtn" title="디버그 콘솔 열기">
    <i class="fas fa-bug"></i>
</button>

<style>
/* 디버그 콘솔 스타일 */
#debugConsole {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 250px;
    background: #1a1a1a;
    color: #fff;
    font-family: 'Courier New', monospace;
    font-size: 12px;
    z-index: 10000;
    display: none;
    flex-direction: column;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.3);
}

#debugHeader {
    background: #333;
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #555;
}

#debugHeader span {
    font-weight: bold;
}

#debugCloseBtn, #debugClearBtn {
    background: #555;
    border: none;
    color: white;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 3px;
    margin-left: 10px;
}

#debugCloseBtn:hover, #debugClearBtn:hover {
    background: #777;
}

#debugContent {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
    font-size: 11px;
    line-height: 1.4;
}

.debug-log {
    margin-bottom: 5px;
    padding: 3px 0;
    border-bottom: 1px solid #333;
}

.debug-time {
    color: #888;
    margin-right: 10px;
}

.debug-info { color: #4CAF50; }
.debug-warn { color: #FFC107; }
.debug-error { color: #F44336; }

/* 디버그 콘솔 열기 버튼 (플로팅 버튼) */
#debugOpenBtn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
    z-index: 9999;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

#debugOpenBtn:hover {
    background: #0056b3;
    transform: scale(1.1);
}

#debugOpenBtn:active {
    transform: scale(0.95);
}

/* 모바일에서 디버그 콘솔이 열렸을 때 본문 하단 여백 추가 */
body.debug-open {
    padding-bottom: 250px;
}

// 디버그 콘솔 기능
const debugConsole = {
    isOpen: false,
    logs: [],
    
    init() {
        // 디버그 콘솔 토글
        $('#debugOpenBtn').on('click', () => this.open());
        $('#debugCloseBtn').on('click', () => this.close());
        $('#debugClearBtn').on('click', () => this.clear());
        
        // 페이지 로드시 디버그 버튼 표시
        $('#debugOpenBtn').show();
        
        // 콘솔 로그 오버라이드
        this.overrideConsole();
    },
    
    open() {
        $('#debugConsole').css('display', 'flex');
        $('#debugOpenBtn').hide();
        $('body').addClass('debug-open');
        this.isOpen = true;
        this.render();
    },
    
    close() {
        $('#debugConsole').hide();
        $('#debugOpenBtn').show();
        $('body').removeClass('debug-open');
        this.isOpen = false;
    },
    
    clear() {
        this.logs = [];
        $('#debugContent').empty();
    },
    
    log(message, type = 'info') {
        const time = new Date().toLocaleTimeString();
        this.logs.push({ time, message, type });
        
        if (this.isOpen) {
            this.render();
        }
    },
    
    render() {
        const content = $('#debugContent');
        content.empty();
        
        this.logs.forEach(log => {
            const logDiv = $('<div class="debug-log">');
            logDiv.html(`
                <span class="debug-time">${log.time}</span>
                <span class="debug-${log.type}">${this.escapeHtml(log.message)}</span>
            `);
            content.append(logDiv);
        });
        
        // 스크롤을 맨 아래로
        content.scrollTop(content[0].scrollHeight);
    },
    
    escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    },
    
    overrideConsole() {
        const originalLog = console.log;
        const originalError = console.error;
        const originalWarn = console.warn;
        
        console.log = (...args) => {
            originalLog.apply(console, args);
            this.log(args.join(' '), 'info');
        };
        
        console.error = (...args) => {
            originalError.apply(console, args);
            this.log(args.join(' '), 'error');
        };
        
        console.warn = (...args) => {
            originalWarn.apply(console, args);
            this.log(args.join(' '), 'warn');
        };
    }
};

// 전역 디버그 함수
function debugLog(message, type = 'info') {
    console.log(`[DEBUG] ${message}`);
    debugConsole.log(message, type);
}

// 페이지 로드시 디버그 콘솔 초기화
$(document).ready(function() {
    debugConsole.init();
    debugLog('디버그 콘솔 초기화 완료', 'info');
    debugLog('회원가입 페이지 로드 완료', 'info');
});

// AJAX 요청 디버깅
$(document).ajaxSend(function(event, xhr, settings) {
    debugLog(`AJAX 요청: ${settings.type} ${settings.url}`, 'info');
});

$(document).ajaxComplete(function(event, xhr, settings) {
    debugLog(`AJAX 완료: ${settings.url} - 상태: ${xhr.status}`, 'info');
});

$(document).ajaxError(function(event, xhr, settings, error) {
    debugLog(`AJAX 오류: ${settings.url} - ${error}`, 'error');
    if (xhr.responseText) {
        debugLog(`응답: ${xhr.responseText}`, 'error');
    }
});
</script>