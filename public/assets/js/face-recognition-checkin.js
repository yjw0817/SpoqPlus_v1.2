/**
 * 체크인 페이지용 안면인식 기능
 * SpoqPlus Face Recognition for Checkin
 */

// 안면 인식 관련 전역 변수
let faceStream = null;
let faceCanvas = null;
let faceContext = null;
let faceRecognitionInProgress = false;

/**
 * 안면 인식 시작
 */
function startFaceRecognition() {
    // 안면 인식 모달이 없으면 기본 알림 표시
    if (!document.getElementById('faceModal')) {
        Swal.fire({
            icon: 'info',
            title: '안면 인식 준비',
            text: '안면 인식을 시작합니다. 카메라에 얼굴을 맞춰주세요.',
            confirmButtonColor: '#17a2b8',
            confirmButtonText: '시작',
            showCancelButton: true,
            cancelButtonText: '취소'
        }).then((result) => {
            if (result.isConfirmed) {
                startFaceRecognitionWithoutModal();
            }
        });
        return;
    }

    const modal = document.getElementById('faceModal');
    const video = document.getElementById('faceVideo');
    faceCanvas = document.getElementById('faceCanvas');
    faceContext = faceCanvas.getContext('2d', { willReadFrequently: true });

    modal.style.display = 'block';
    updateFaceStatus('카메라를 준비 중입니다...', 'detecting');

    // 카메라 접근
    navigator.mediaDevices.getUserMedia({ 
        video: { 
            facingMode: 'user', // 전면 카메라
            width: { ideal: 640 },
            height: { ideal: 480 }
        } 
    })
    .then(function(stream) {
        faceStream = stream;
        video.srcObject = stream;
        video.play();
        
        // 비디오 메타데이터 로드 후 준비
        video.addEventListener('loadedmetadata', function() {
            faceCanvas.width = video.videoWidth;
            faceCanvas.height = video.videoHeight;
            updateFaceStatus('얼굴을 카메라에 맞춰주세요', 'detecting');
            
            // 자동 촬영 시작 (3초 후)
            setTimeout(function() {
                if (faceStream && !faceRecognitionInProgress) {
                    captureFace();
                }
            }, 3000);
        });
    })
    .catch(function(err) {
        console.error('카메라 접근 실패:', err);
        updateFaceStatus('카메라 접근 실패', 'error');
        setTimeout(() => {
            Swal.fire({
                icon: 'error',
                title: '카메라 접근 실패',
                text: '카메라에 접근할 수 없습니다. 권한을 확인해주세요.',
                confirmButtonColor: '#17a2b8'
            });
            closeFaceRecognition();
        }, 1000);
    });
}

/**
 * 모달 없이 안면 인식 시작 (Swal 사용)
 */
function startFaceRecognitionWithoutModal() {
    Swal.fire({
        title: '안면 인식 중...',
        text: '카메라를 준비하고 있습니다.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // 카메라 접근
    navigator.mediaDevices.getUserMedia({ 
        video: { 
            facingMode: 'user',
            width: { ideal: 640 },
            height: { ideal: 480 }
        } 
    })
    .then(function(stream) {
        // 임시 비디오 엘리먼트 생성
        const video = document.createElement('video');
        video.srcObject = stream;
        video.autoplay = true;
        video.muted = true;
        video.style.display = 'none';
        document.body.appendChild(video);

        video.addEventListener('loadedmetadata', function() {
            // 카메라 준비 완료
            Swal.update({
                title: '안면 인식 중...',
                text: '얼굴을 인식하고 있습니다. 잠시만 기다려주세요.'
            });

            // 3초 후 자동 촬영
            setTimeout(() => {
                captureAndRecognize(video, stream);
            }, 3000);
        });
    })
    .catch(function(err) {
        console.error('카메라 접근 실패:', err);
        Swal.fire({
            icon: 'error',
            title: '카메라 접근 실패',
            text: '카메라에 접근할 수 없습니다. 권한을 확인해주세요.',
            confirmButtonColor: '#17a2b8'
        });
    });
}

/**
 * 얼굴 상태 업데이트
 */
function updateFaceStatus(message, type) {
    const statusDiv = document.getElementById('faceStatus');
    if (statusDiv) {
        statusDiv.textContent = message;
        statusDiv.className = `face-status ${type}`;
    }
}

/**
 * 얼굴 촬영 및 인식
 */
function captureFace() {
    if (faceRecognitionInProgress) return;
    
    faceRecognitionInProgress = true;
    updateFaceStatus('얼굴을 촬영 중입니다...', 'detecting');
    
    const video = document.getElementById('faceVideo');
    const canvas = faceCanvas;
    const ctx = faceContext;
    
    // 캔버스에 현재 비디오 프레임 그리기
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // 이미지를 Base64로 변환 (Python 서버 포맷에 맞춤)
    const imageDataUrl = canvas.toDataURL('image/jpeg', 0.8);
    const base64Data = imageDataUrl.split(',')[1]; // data:image/jpeg;base64, 부분 제거
    
    console.log('Captured image size:', base64Data.length);
    
    // JSON 형태로 데이터 준비 (Python 서버 포맷에 맞춤)
    const requestData = {
        image_base64: base64Data
    };
    
    updateFaceStatus('얼굴을 인식 중입니다...', 'detecting');
    
    // 얼굴 인식 API 호출 (Python 서버로 직접 호출)
    jQuery.ajax({
        url: 'http://localhost:5001/api/face/recognize',
        type: 'POST',
        data: JSON.stringify(requestData),
        contentType: 'application/json',
        dataType: 'json',
        timeout: 10000, // 10초 타임아웃
        success: function(result) {
            console.log('Face Recognition Response:', result);
            
            try {
                if (result.success && result.face_matching && result.face_matching.match_found) {
                    // 얼굴 인식 성공
                    const memberSno = result.face_matching.member.mem_sno;
                    const confidence = result.face_matching.similarity_score;
                    
                    updateFaceStatus(`인식 성공! 회원번호: ${memberSno}`, 'success');
                    
                    // 2초 후 체크인 처리
                    setTimeout(() => {
                        closeFaceRecognition();
                        processFaceCheckin(memberSno, confidence);
                    }, 2000);
                    
                } else {
                    // 얼굴 인식 실패
                    updateFaceStatus('얼굴 인식 실패', 'error');
                    
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'warning',
                            title: '얼굴 인식 실패',
                            text: result.error || '등록된 얼굴을 찾을 수 없습니다.',
                            confirmButtonColor: '#17a2b8'
                        });
                        closeFaceRecognition();
                    }, 1500);
                }
            } catch (e) {
                console.error('Response processing error:', e);
                console.error('Raw response:', result);
                updateFaceStatus('응답 처리 오류', 'error');
                
                setTimeout(() => {
                    Swal.fire({
                        icon: 'error',
                        title: '시스템 오류',
                        text: '서버 응답을 처리하는 중 오류가 발생했습니다.',
                        confirmButtonColor: '#17a2b8'
                    });
                    closeFaceRecognition();
                }, 1500);
            }
        },
        error: function(xhr, status, error) {
            console.error('Face Recognition API Error:', xhr, status, error);
            console.error('Response Text:', xhr.responseText);
            updateFaceStatus('API 호출 오류', 'error');
            
            let errorMessage = '얼굴 인식 서버와 통신할 수 없습니다.';
            
            if (xhr.status === 404) {
                errorMessage = 'Python 서버 또는 API 엔드포인트를 찾을 수 없습니다.';
            } else if (xhr.status === 500) {
                errorMessage = '얼굴 인식 서버 내부 오류가 발생했습니다.';
            } else if (xhr.status === 0) {
                errorMessage = 'Python 서버(localhost:5001)에 연결할 수 없습니다.';
            } else if (status === 'timeout') {
                errorMessage = '얼굴 인식 처리 시간이 초과되었습니다.';
            }
            
            setTimeout(() => {
                Swal.fire({
                    icon: 'error',
                    title: '네트워크 오류',
                    text: errorMessage,
                    confirmButtonColor: '#17a2b8'
                });
                closeFaceRecognition();
            }, 1500);
        },
        complete: function() {
            faceRecognitionInProgress = false;
        }
    });
}

/**
 * 모달 없이 촬영 및 인식
 */
function captureAndRecognize(video, stream) {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    // 캔버스에 현재 비디오 프레임 그리기
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // 스트림 정리
    stream.getTracks().forEach(track => track.stop());
    document.body.removeChild(video);
    
    // 이미지를 Base64로 변환 (Python 서버 포맷에 맞춤)
    const imageDataUrl = canvas.toDataURL('image/jpeg', 0.8);
    const base64Data = imageDataUrl.split(',')[1]; // data:image/jpeg;base64, 부분 제거
    
    // JSON 형태로 데이터 준비 (Python 서버 포맷에 맞춤)
    const requestData = {
        image_base64: base64Data
    };
    
    // 얼굴 인식 API 호출 (Python 서버로 직접 호출)
    jQuery.ajax({
        url: 'http://localhost:5001/api/face/recognize',
        type: 'POST',
        data: JSON.stringify(requestData),
        contentType: 'application/json',
        dataType: 'json',
        timeout: 10000, // 10초 타임아웃
        success: function(result) {
            console.log('Face Recognition Response:', result);
            
            try {
                if (result.success && result.face_matching && result.face_matching.match_found) {
                    // 얼굴 인식 성공
                    const memberSno = result.face_matching.member.mem_sno;
                    const confidence = result.face_matching.similarity_score;
                    
                    Swal.fire({
                        icon: 'success',
                        title: '얼굴 인식 성공!',
                        text: `회원번호: ${memberSno} (신뢰도: ${Math.round(confidence * 100)}%)`,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        processFaceCheckin(memberSno, confidence);
                    });
                    
                } else {
                    // 얼굴 인식 실패
                    Swal.fire({
                        icon: 'warning',
                        title: '얼굴 인식 실패',
                        text: result.error || '등록된 얼굴을 찾을 수 없습니다.',
                        confirmButtonColor: '#17a2b8'
                    });
                }
            } catch (e) {
                console.error('Response processing error:', e);
                console.error('Raw response:', result);
                Swal.fire({
                    icon: 'error',
                    title: '시스템 오류',
                    text: '서버 응답을 처리하는 중 오류가 발생했습니다.',
                    confirmButtonColor: '#17a2b8'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Face Recognition API Error:', xhr, status, error);
            console.error('Response Text:', xhr.responseText);
            
            let errorMessage = '얼굴 인식 서버와 통신할 수 없습니다.';
            
            if (xhr.status === 404) {
                errorMessage = 'Python 서버 또는 API 엔드포인트를 찾을 수 없습니다.';
            } else if (xhr.status === 500) {
                errorMessage = '얼굴 인식 서버 내부 오류가 발생했습니다.';
            } else if (xhr.status === 0) {
                errorMessage = 'Python 서버(localhost:5001)에 연결할 수 없습니다.';
            } else if (status === 'timeout') {
                errorMessage = '얼굴 인식 처리 시간이 초과되었습니다.';
            }
            
            Swal.fire({
                icon: 'error',
                title: '네트워크 오류',
                text: errorMessage,
                confirmButtonColor: '#17a2b8'
            });
        }
    });
}

/**
 * 얼굴 인식 결과로 체크인 처리
 */
function processFaceCheckin(memberSno, confidence) {
    // 회원번호 설정
    if (typeof memberNumber !== 'undefined') {
        memberNumber = memberSno;
        if (typeof updateDisplay === 'function') {
            updateDisplay();
        }
    }
    
    // 이용권 조회 및 체크인 처리
    if (typeof getMemberTickets === 'function') {
        currentMemberNumber = memberSno;
        currentQRData = null;
        getMemberTickets(memberSno);
    } else {
        // 직접 체크인 처리
        Swal.fire({
            icon: 'success',
            title: '얼굴 인식 완료!',
            text: `회원번호 ${memberSno}로 인식되었습니다.`,
            confirmButtonColor: '#17a2b8',
            confirmButtonText: '확인'
        });
    }
}

/**
 * 안면 인식 모달 닫기
 */
function closeFaceRecognition() {
    const modal = document.getElementById('faceModal');
    if (modal) {
        modal.style.display = 'none';
    }
    
    // 카메라 스트림 종료
    if (faceStream) {
        faceStream.getTracks().forEach(track => track.stop());
        faceStream = null;
    }
    
    // 비디오 초기화
    const video = document.getElementById('faceVideo');
    if (video) {
        video.srcObject = null;
    }
    
    // 상태 초기화
    faceRecognitionInProgress = false;
}

/**
 * 안면 인식 모달 HTML 생성 및 삽입
 */
function createFaceRecognitionModal() {
    // 모달이 이미 존재하는지 확인
    if (document.getElementById('faceModal')) {
        return;
    }
    
    const modalHTML = `
        <!-- Face Recognition Modal -->
        <div id="faceModal" class="face-modal">
            <div class="face-modal-content">
                <span class="qr-close" onclick="closeFaceRecognition()">&times;</span>
                <h3 style="text-align: center; margin-top: 0;">안면 인식</h3>
                <div id="faceStatus" class="face-status detecting">
                    얼굴을 카메라에 맞춰주세요
                </div>
                <video id="faceVideo" class="face-video" autoplay></video>
                <canvas id="faceCanvas" style="display: none;"></canvas>
                <div class="face-controls">
                    <button id="captureBtn" class="btn-capture" onclick="captureFace()">
                        촬영하기
                    </button>
                    <button class="btn-capture" onclick="closeFaceRecognition()" style="background: #6c757d;">
                        취소
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // 모달을 body 끝에 추가
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

/**
 * 안면 인식 모달 CSS 생성 및 삽입
 */
function createFaceRecognitionStyles() {
    // 스타일이 이미 존재하는지 확인
    if (document.getElementById('faceRecognitionStyles')) {
        return;
    }
    
    const styles = `
        <style id="faceRecognitionStyles">
            /* 안면 인식 모달 스타일 */
            .face-modal {
                display: none;
                position: fixed;
                z-index: 9999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.8);
            }

            .face-modal-content {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                border-radius: 15px;
                padding: 20px;
                max-width: 500px;
                width: 90%;
                text-align: center;
            }

            .face-video {
                width: 100%;
                max-width: 400px;
                border-radius: 10px;
                margin: 15px 0;
            }

            .face-status {
                margin: 10px 0;
                padding: 10px;
                border-radius: 8px;
                font-weight: 600;
            }

            .face-status.detecting {
                background: #e3f2fd;
                color: #1976d2;
            }

            .face-status.success {
                background: #e8f5e8;
                color: #2e7d32;
            }

            .face-status.error {
                background: #ffeaea;
                color: #c62828;
            }

            .face-controls {
                margin-top: 15px;
            }

            .btn-capture {
                background: #17a2b8;
                color: white;
                border: none;
                border-radius: 10px;
                padding: 10px 20px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                margin: 0 10px;
                transition: all 0.3s ease;
            }

            .btn-capture:hover {
                background: #138496;
                transform: translateY(-1px);
                box-shadow: 0 5px 15px rgba(23, 162, 184, 0.3);
            }

            .btn-capture:disabled {
                background: #6c757d;
                cursor: not-allowed;
            }
        </style>
    `;
    
    // 스타일을 head에 추가
    document.head.insertAdjacentHTML('beforeend', styles);
}

/**
 * 페이지 로드 시 초기화
 */
document.addEventListener('DOMContentLoaded', function() {
    // 안면 인식 스타일 및 모달 생성
    createFaceRecognitionStyles();
    createFaceRecognitionModal();
    
    // 모달 외부 클릭 시 닫기 이벤트 추가
    window.addEventListener('click', function(event) {
        const faceModal = document.getElementById('faceModal');
        if (event.target === faceModal) {
            closeFaceRecognition();
        }
    });
}); 