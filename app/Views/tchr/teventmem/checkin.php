<!DOCTYPE html>
<html lang="ko" class="hydrated"><head>
	<meta charset="utf-8"><style data-styles="">ion-icon{visibility:hidden}.hydrated{visibility:inherit}</style>
	<title>ARGOS SpoQ | 체크인</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
	<meta content="" name="description">
	<meta content="" name="author">
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="/dist/css/vendor.min.css" rel="stylesheet">
	<link href="/dist/css/apple/app.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- ================== END core-css ================== -->

	<style>
		/* 키패드 스타일 */
		.keypad-container {
			background: white;
			border-radius: 12px;
			padding: 15px 20px 20px 20px;
			box-shadow: 0 5px 20px rgba(0,0,0,0.1);
			margin-top: 30px;
		}

		.keypad-title {
			text-align: center;
			font-size: 1.3rem;
			color: #2d3436;
			margin-bottom: 20px;
			font-weight: 600;
		}

		.member-display {
			background: #f8f9fa;
			border: 2px solid #e9ecef;
			border-radius: 10px;
			padding: 15px;
			text-align: center;
			font-size: 1.8rem;
			font-weight: 600;
			color: #2d3436;
			margin-bottom: 20px;
			height: 50px;
			display: flex;
			align-items: center;
			justify-content: center;
			letter-spacing: 2px;
		}

		.keypad-grid {
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			gap: 10px;
			margin-bottom: 15px;
		}

		.keypad-btn {
			background: #f8f9fa;
			border: 2px solid #e9ecef;
			border-radius: 10px;
			padding: 15px;
			font-size: 1.5rem;
			font-weight: 600;
			color: #2d3436;
			cursor: pointer;
			transition: all 0.3s ease;
			min-height: 50px;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.keypad-btn:hover {
			background: #e9ecef;
			transform: translateY(-1px);
			box-shadow: 0 3px 10px rgba(0,0,0,0.1);
		}

		.keypad-btn:active {
			transform: translateY(0);
			box-shadow: 0 1px 3px rgba(0,0,0,0.1);
		}

		.action-buttons {
			display: grid;
			grid-template-columns: 1fr;
			gap: 10px;
			margin-top: 20px;
		}

		.btn-checkin {
			background: #007bff;
			color: white;
			border: none;
			border-radius: 10px;
			padding: 15px;
			font-size: 1.1rem;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.3s ease;
		}

		.btn-checkin:hover {
			background: #0056b3;
			transform: translateY(-1px);
			box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
		}

		.bottom-row {
			display: grid;
			grid-template-columns: 1fr 1fr 1fr;
			gap: 10px;
			margin-top: 10px;
		}

		.icon-btn {
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 1.2rem;
		}

		.register-header {
			margin-bottom: 10px !important;
		}

		.scan-section {
			margin: 15px 0;
		}

		.scan-buttons {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 10px;
		}

		.btn-qr-scan {
			background: #28a745;
			color: white;
			border: none;
			border-radius: 10px;
			padding: 12px 20px;
			font-size: 1rem;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.3s ease;
		}

		.btn-qr-scan:hover {
			background: #218838;
			transform: translateY(-1px);
			box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
		}

		.btn-face-scan {
			background: #17a2b8;
			color: white;
			border: none;
			border-radius: 10px;
			padding: 12px 20px;
			font-size: 1rem;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.3s ease;
		}

		.btn-face-scan:hover {
			background: #138496;
			transform: translateY(-1px);
			box-shadow: 0 5px 15px rgba(23, 162, 184, 0.3);
		}

		.qr-modal {
			display: none;
			position: fixed;
			z-index: 9999;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0,0,0,0.8);
		}

		.qr-modal-content {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			background: white;
			border-radius: 15px;
			padding: 20px;
			max-width: 400px;
			width: 90%;
		}

		.qr-video {
			width: 100%;
			border-radius: 10px;
			margin: 15px 0;
		}

		.qr-close {
			position: absolute;
			top: 10px;
			right: 15px;
			font-size: 28px;
			font-weight: bold;
			cursor: pointer;
			color: #999;
		}

		.qr-close:hover {
			color: #000;
		}

		/* 이용권 선택 모달 */
		.ticket-modal {
			display: none;
			position: fixed;
			z-index: 9999;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0,0,0,0.8);
		}

		.ticket-modal-content {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			background: white;
			border-radius: 15px;
			padding: 25px;
			max-width: 500px;
			width: 90%;
			max-height: 70vh;
			overflow-y: auto;
		}

		.ticket-item {
			border: 2px solid #e9ecef;
			border-radius: 10px;
			padding: 15px;
			margin-bottom: 10px;
			cursor: pointer;
			transition: all 0.3s ease;
		}

		.ticket-item:hover {
			border-color: #007bff;
			background-color: #f8f9ff;
		}

		.ticket-item.selected {
			border-color: #007bff;
			background-color: #e7f3ff;
		}

		.ticket-item.ticket-disabled {
			opacity: 0.5;
			cursor: not-allowed;
			background-color: #f8f9fa;
		}

		.ticket-item.ticket-disabled:hover {
			border-color: #e9ecef;
			background-color: #f8f9fa;
		}

		.ticket-header {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
			margin-bottom: 8px;
			gap: 10px;
		}

		.ticket-name {
			font-weight: 600;
			font-size: 1.1rem;
			color: #2d3436;
			flex-shrink: 0;
			min-width: fit-content;
		}

		.ticket-detail {
			font-size: 0.75rem;
			color: #6c757d;
			text-align: right;
			padding: 6px 12px;
			background: linear-gradient(135deg, #f8f9fc 0%, #f1f3f8 100%);
			border-radius: 8px;
			border: 1px solid #e9ecef;
			box-shadow: 0 2px 4px rgba(0,0,0,0.04);
			line-height: 1.4;
			flex-grow: 1;
			font-weight: 500;
			letter-spacing: -0.02em;
			transition: all 0.2s ease;
		}
		
		.ticket-detail:hover {
			background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 100%);
			border-color: #007bff;
			box-shadow: 0 4px 8px rgba(0,123,255,0.1);
		}

		.ticket-info {
			font-size: 0.9rem;
			color: #6c757d;
		}

		.ticket-bottom {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-top: 10px;
			gap: 10px;
		}

		.info-container {
			flex-grow: 1;
		}

		.gx-info {
			font-size: 0.85rem;
			color: #28a745;
			background-color: #f8fff9;
			border: 1px solid #d4edda;
			border-radius: 6px;
			padding: 6px 10px;
			margin-bottom: 0;
		}

		.gx-info strong {
			color: #155724;
		}

		.usage-info {
			font-size: 0.85rem;
			color: #0066cc;
			background-color: #f0f8ff;
			border: 1px solid #b8d4f1;
			border-radius: 6px;
			padding: 6px 10px;
			margin-bottom: 0;
		}

		.ticket-status {
			display: inline-block;
			padding: 4px 12px;
			border-radius: 12px;
			font-size: 0.75rem;
			font-weight: 600;
			flex-shrink: 0;
		}

		.status-active {
			background: #d4edda;
			color: #155724;
		}

		.status-inactive {
			background: #f8d7da;
			color: #721c24;
		}

		/* 안면 인식 관련 스타일 */
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

		/* Camera Active Mode Styles */
		#cameraActiveMode {
			margin-top: 10px;
		}

		.camera-preview-container {
			position: relative;
			width: 100%;
			max-width: 400px;
			margin: 0 auto;
			border-radius: 15px;
			overflow: hidden;
			box-shadow: 0 5px 20px rgba(0,0,0,0.2);
			background: #000;
		}

		.auto-mode-video {
			width: 100%;
			height: auto;
			display: block;
		}

	</style>
</head>
<body class="pace-done pace-top"><div class="pace pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>
	<!-- BEGIN #loader -->
	<div id="loader" class="app-loader loaded">
		<span class="spinner"></span>
	</div>
	<!-- END #loader -->
	<!-- BEGIN #app -->
	<div id="app" class="app">
		<!-- BEGIN register -->
		<div class="register register-with-news-feed">
			<!-- BEGIN news-feed -->
			<div class="news-feed">
				<div class="news-image" style="background-image: url(/dist/img/login-bg/login-bg-6.jpg)"></div>
				<div class="news-caption">
					<h4 class="caption-title"><b>ARGOS</b> SpoQ</h4>
					<p>
                        DYCIS AI Powered Fitness Center Management System.
					</p>
				</div>
			</div>
			<!-- END news-feed -->
			    <div class="brand">
                   
                </div>
			<!-- BEGIN register-container -->
			<div class="register-container">
				<!-- BEGIN register-header -->
				<div class="register-header mb-25px h1">
                    <div class="d-flex align-items-center">
                        <span class="logo"><ion-icon name="cloud" role="img" class="md hydrated"></ion-icon></span>
                        
                        <b><?php echo $_SESSION["bcoff_nm"] ?></b>
                    </div>

				</div>
				<!-- END register-header -->
				
				<!-- BEGIN keypad-content -->
				<div class="keypad-container">
					<h3 class="keypad-title">회원번호</h3>
					
					
					<!-- 회원번호 디스플레이 -->
					<div class="member-display" id="memberDisplay">
						<!-- 입력된 번호가 여기에 표시됩니다 -->
					</div>
					
					<!-- 숫자 키패드 -->
					<div class="keypad-grid">
						<button class="keypad-btn" onclick="addNumber('1')">1</button>
						<button class="keypad-btn" onclick="addNumber('2')">2</button>
						<button class="keypad-btn" onclick="addNumber('3')">3</button>
						<button class="keypad-btn" onclick="addNumber('4')">4</button>
						<button class="keypad-btn" onclick="addNumber('5')">5</button>
						<button class="keypad-btn" onclick="addNumber('6')">6</button>
						<button class="keypad-btn" onclick="addNumber('7')">7</button>
						<button class="keypad-btn" onclick="addNumber('8')">8</button>
						<button class="keypad-btn" onclick="addNumber('9')">9</button>
					</div>
					
					<!-- 하단 버튼들 -->
					<div class="bottom-row">
						<button class="keypad-btn icon-btn" onclick="clearAll()">
							<i class="fas fa-redo"></i>
						</button>
						<button class="keypad-btn" onclick="addNumber('0')">0</button>
						<button class="keypad-btn icon-btn" onclick="backspace()">
							⬅
						</button>
					</div>
					
					<!-- 스캔 버튼들 -->
					<div class="scan-section">
						<div class="scan-buttons">
							<button class="btn-qr-scan" onclick="startQRScanner()">
								<i class="fas fa-qrcode"></i> QR 스캔
							</button>
							<button class="btn-face-scan" onclick="startFaceRecognition()">
								<i class="fas fa-user-circle"></i> 안면 인식
							</button>
						</div>
					</div>

					<!-- 입장 버튼 -->
					<div class="action-buttons">
						<button class="btn-checkin" onclick="processCheckin()">
							입장
						</button>
					</div>
				</div>
				<!-- END keypad-content -->
				
				<!-- Camera Active Mode UI -->
				<div id="cameraActiveMode" style="display: none;">
					<div class="camera-preview-container">
						<video id="autoModeVideo" class="auto-mode-video" autoplay muted playsinline></video>
						<canvas id="detectionCanvas" style="display: none;"></canvas>
					</div>
				</div>
			</div>
			<!-- END register-container -->
		</div>
		<!-- END register -->
	</div>
	
	<!-- QR Scanner Modal -->
	<div id="qrModal" class="qr-modal">
		<div class="qr-modal-content">
			<span class="qr-close" onclick="closeQRScanner()">&times;</span>
			<h3 style="text-align: center; margin-top: 0;">QR 코드 스캔</h3>
			<video id="qrVideo" class="qr-video" autoplay></video>
			<canvas id="qrCanvas" style="display: none;"></canvas>
			<p style="text-align: center; color: #666; font-size: 0.9rem;">
				QR 코드를 카메라에 맞춰주세요
			</p>
		</div>
	</div>

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
				<button class="btn-capture" onclick="closeFaceRecognition()" style="background: #6c757d;">
					취소
				</button>
			</div>
		</div>
	</div>

	<!-- Ticket Selection Modal -->
	<div id="ticketModal" class="ticket-modal">
		<div class="ticket-modal-content">
			<span class="qr-close" onclick="closeTicketModal()">&times;</span>
			<h3 id="ticketModalTitle" style="text-align: center; margin-top: 0;">이용권 선택</h3>
			<div id="ticketList">
				<!-- 이용권 목록이 여기에 동적으로 추가됩니다 -->
			</div>
			<div style="text-align: center; margin-top: 20px;">
				<button class="btn-qr-scan" onclick="confirmTicketSelection()" style="background: #007bff; color: white; margin-right: 10px;">
					입장
				</button>
				<button class="btn-qr-scan" onclick="closeTicketModal()" style="background: #6c757d; color: white;">
					취소
				</button>
			</div>
		</div>
	</div>
	
	<!-- ================== BEGIN core-js ================== -->
	<script src="/dist/js/vendor.min.js" type="text/javascript"></script>
	<script src="/dist/js/app.min.js" type="text/javascript"></script>
	<!-- SweetAlert2 -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!-- QR Code Scanner Library -->
	<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
	<!-- ================== END core-js ================== -->

	<script>
		let memberNumber = '';
		// Camera active mode variables
		let autoModeActive = false;      // 카메라 활성 모드 상태
		let continuousStream = null;     // 지속적인 카메라 스트림
		let faceDetectionInterval = null; // 얼굴 감지 인터벌
		let qrDetectionInterval = null;   // QR 감지 인터벌
		let isProcessing = false;        // 현재 처리 중인지 확인
		let faceDetectionActive = false;
		let isPopupOpen = false;         // 팝업창 열림 상태

		// Swal helper function - 자동으로 팝업 상태 관리
		function showSwal(options) {
			isPopupOpen = true;
			const defaultOptions = {
				didOpen: () => {
					isPopupOpen = true;
				},
				didClose: () => {
					isPopupOpen = false;
				}
			};
			
			// 기존 옵션과 병합
			const mergedOptions = Object.assign({}, defaultOptions, options);
			
			// 기존 didOpen/didClose가 있으면 처리
			if (options.didOpen) {
				const originalDidOpen = options.didOpen;
				mergedOptions.didOpen = () => {
					isPopupOpen = true;
					originalDidOpen();
				};
			}
			
			if (options.didClose) {
				const originalDidClose = options.didClose;
				mergedOptions.didClose = () => {
					isPopupOpen = false;
					originalDidClose();
				};
			}
			
			return Swal.fire(mergedOptions).then((result) => {
				isPopupOpen = false;
				return result;
			});
		}

		// 숫자 추가
		function addNumber(num) {
			if (memberNumber.length < 11) { // 최대 11자리까지
				memberNumber += num;
				updateDisplay();
			}
		}

		// 백스페이스
		function backspace() {
			memberNumber = memberNumber.slice(0, -1);
			updateDisplay();
		}

		// 전체 지우기
		function clearAll() {
			memberNumber = '';
			updateDisplay();
		}

		// 디스플레이 업데이트
		function updateDisplay() {
			const display = document.getElementById('memberDisplay');
			
			// 환경변수에서 설정된 키값 확인 (PHP에서 전달)
			const autoModeKey = '<?php echo $_ENV["CHECKIN_AUTO_MODE_KEY"] ?? "1379"; ?>';
			
			if (memberNumber === autoModeKey) {
				// 카메라 활성 모드 토글
				if (autoModeActive) {
					deactivateCameraMode();
				} else {
					activateCameraMode();
				}
				memberNumber = ''; // 입력값 초기화
			}
			
			if (memberNumber === '') {
				display.textContent = '';
			} else {
				display.textContent = memberNumber;
			}
		}

		// 전역 변수
		let selectedTicket = null;
		let currentMemberNumber = null;
		let currentQRData = null;

		// Camera active mode functions
		function activateCameraMode() {
			autoModeActive = true;
			
			// QR 스캔/안면인식 버튼 숨기기
			document.querySelector('.scan-buttons').style.display = 'none';
			
			// 카메라 프리뷰 영역 표시
			document.getElementById('cameraActiveMode').style.display = 'block';
			
			// 상태 표시
			updateAutoModeStatus('카메라 활성 모드 시작 중...');
			
			// 시각적 피드백
			Swal.fire({
				icon: 'success',
				title: '카메라 활성 모드',
				text: '자동 QR/얼굴 인식이 활성화되었습니다',
				timer: 1500,
				showConfirmButton: false
			});
			
			// 카메라 스트림 시작
			startContinuousCamera();
		}

		function deactivateCameraMode() {
			autoModeActive = false;
			
			// 인터벌 정리
			if (qrDetectionInterval) {
				clearInterval(qrDetectionInterval);
				qrDetectionInterval = null;
			}
			if (faceDetectionInterval) {
				clearInterval(faceDetectionInterval);
				faceDetectionInterval = null;
			}
			
			// 스트림 정리
			if (continuousStream) {
				continuousStream.getTracks().forEach(track => track.stop());
				continuousStream = null;
			}
			
			// UI 복원
			document.querySelector('.scan-buttons').style.display = 'grid';
			document.getElementById('cameraActiveMode').style.display = 'none';
			
			// 시각적 피드백
			Swal.fire({
				icon: 'info',
				title: '카메라 활성 모드 해제',
				text: '수동 모드로 전환되었습니다',
				timer: 1500,
				showConfirmButton: false
			});
			
			isProcessing = false;
		}

		function startContinuousCamera() {
			navigator.mediaDevices.getUserMedia({ 
				video: { 
					facingMode: 'user',
					width: { ideal: 640 },
					height: { ideal: 480 }
				} 
			})
			.then(stream => {
				continuousStream = stream;
				const video = document.getElementById('autoModeVideo');
				video.srcObject = stream;
				
				video.addEventListener('loadedmetadata', () => {
					// 캔버스 크기 설정
					const canvas = document.getElementById('detectionCanvas');
					canvas.width = video.videoWidth;
					canvas.height = video.videoHeight;
					
					updateAutoModeStatus('준비 완료 - QR/얼굴 인식 대기 중');
					
					// QR 스캔과 얼굴 감지 동시 실행
					startContinuousQRScan();
					startContinuousFaceDetection();
				});
			})
			.catch(err => {
				console.error('카메라 접근 실패:', err);
				updateAutoModeStatus('카메라 접근 실패 - 권한을 확인해주세요');
				deactivateCameraMode();
			});
		}

		function startContinuousQRScan() {
			const video = document.getElementById('autoModeVideo');
			const canvas = document.getElementById('detectionCanvas');
			const ctx = canvas.getContext('2d');
			
			qrDetectionInterval = setInterval(() => {
				if (!autoModeActive || isProcessing || !video.videoWidth || isPopupOpen) return;
				
				// 캔버스에 비디오 프레임 그리기
				ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
				const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
				
				// QR 코드 감지
				const code = jsQR(imageData.data, imageData.width, imageData.height);
				if (code && code.data) {
					isProcessing = true;
					updateAutoModeStatus('QR 코드 처리 중...');
					
					// QR 처리 (기존 함수 사용 - 오류 메시지 포함)
					processQRResult(code.data);
					
					// 처리 완료 후 대기
					setTimeout(() => {
						isProcessing = false;
						updateAutoModeStatus('준비 완료 - QR/얼굴 인식 대기 중');
					}, 3000);
				}
			}, 200); // 200ms마다 체크
		}

		function startContinuousFaceDetection() {
			faceDetectionInterval = setInterval(() => {
				if (!autoModeActive || isProcessing || faceDetectionActive || isPopupOpen) return;
				
				// 2초마다 얼굴 인식 시도
				faceDetectionActive = true;
				captureFaceForAutoMode();
			}, 2000);
		}

		function captureFaceForAutoMode() {
			const video = document.getElementById('autoModeVideo');
			const canvas = document.getElementById('detectionCanvas');
			const ctx = canvas.getContext('2d');
			
			if (!video.videoWidth) {
				faceDetectionActive = false;
				return;
			}
			
			// 현재 프레임 캡처
			ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
			const imageDataUrl = canvas.toDataURL('image/jpeg', 0.8);
			const base64Data = imageDataUrl.split(',')[1];
			
			// 안면인식 API 호출
			jQuery.ajax({
				url: '/FaceTest/recognize_for_checkin',
				type: 'POST',
				data: JSON.stringify({
					image: base64Data,
					check_liveness: true,
					check_blink: false
				}),
				contentType: 'application/json',
				dataType: 'json',
				timeout: 5000,
				success: function(result) {
					if (result.success && result.face_matching?.match_found) {
						// 성공 시 처리
						isProcessing = true;
						const memberName = result.face_matching.member.mem_nm || '회원';
						updateAutoModeStatus(`${memberName}님 인식 성공!`);
						
						// 체크인 처리
						window.currentMemberName = memberName;
						currentMemberNumber = result.face_matching.member.mem_sno;
						currentQRData = null;
						getMemberTickets(result.face_matching.member.mem_sno);
						
						// 3초 후 다시 대기 모드로
						setTimeout(() => {
							isProcessing = false;
							faceDetectionActive = false;
							updateAutoModeStatus('준비 완료 - QR/얼굴 인식 대기 중');
						}, 3000);
					} else {
						// 실패 시 오류 메시지 없이 바로 재시도 가능
						faceDetectionActive = false;
					}
				},
				error: function() {
					// 오류 시에도 메시지 없이 계속 진행
					faceDetectionActive = false;
				}
			});
		}

		function updateAutoModeStatus(message) {
			// 상태 표시 제거됨 - 화면 공간 절약을 위해
			console.log('Camera mode status:', message);
		}

		// 입장값이 전화번호인지 판단
		function isPhoneNumber(input) {
			// 하이픈 제거
			const cleanInput = input.replace(/-/g, '');
			
			// 010으로 시작하고 11자리 숫자인 경우 전화번호로 판단
			if (cleanInput.length === 11 && cleanInput.startsWith('010')) {
				return true;
			}
			
			// 01로 시작하고 10-11자리인 경우도 전화번호로 판단
			if ((cleanInput.length === 10 || cleanInput.length === 11) && cleanInput.startsWith('01')) {
				return true;
			}
			
			return false;
		}

		// 입장 처리
		function processCheckin() {
			if (memberNumber === '') {
				isPopupOpen = true;
				Swal.fire({
					icon: 'warning',
					title: '회원번호/전화번호 입력',
					text: '회원번호 또는 전화번호를 입력해주세요.',
					confirmButtonColor: '#007bff',
					didOpen: () => {
						isPopupOpen = true;
					},
					didClose: () => {
						isPopupOpen = false;
					}
				});
				return;
			}

			currentMemberNumber = memberNumber;
			currentQRData = null;
			
			getMemberTicketsByTelno(memberNumber);
		}

		// QR 코드로 체크인 처리
		function processQRCheckin(qrData) {
			currentQRData = qrData;
			
			// QR 코드에서 회원 정보 추출
			const qrDiv = qrData.split('|');
			
			if (qrDiv.length === 4) {
				// QR 코드 유효성 검사
				const chkQr = parseInt(qrDiv[3].substr(0, 9)) + 1;
				const chkTime = parseInt(String(Math.floor(Date.now() / 1000)).substr(0, 9));
				
				if (chkQr < chkTime) {
					showSwal({
						icon: 'error',
						title: 'QR 코드 만료',
						text: '만료된 QR코드입니다. 새로운 QR코드를 다시 스캔해주세요.',
						confirmButtonColor: '#007bff'
					});
					return;
				}
				
				// 회원 이용권 목록 조회
				getMemberTicketsForQR(qrDiv[0], qrDiv[1], qrDiv[2]);
			} else {
				Swal.fire({
					icon: 'error',
					title: 'QR 코드 오류',
					text: '올바르지 않은 QR코드 형식입니다. 정확한 QR코드를 다시 스캔해주세요.',
					confirmButtonColor: '#007bff'
				});
			}
		}

		// QR 스캔용 회원 이용권 목록 조회
		function getMemberTicketsForQR(compCd, bcoffCd, memSno) {
			// 불필요한 로딩 팝업 제거 - 바로 조회
			jQuery.ajax({
				url: '/api/get_member_tickets',
				type: 'POST',
				contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
				dataType: 'text',
				data: 'comp_cd=' + encodeURIComponent(compCd) + 
					  '&bcoff_cd=' + encodeURIComponent(bcoffCd) + 
					  '&mem_sno=' + encodeURIComponent(memSno),
				success: function (result) {
					console.log('Get Tickets API Response:', result);
					
					// 로그인 만료 체크
					if (result.substr(0, 8) == '<script>') {
						// Swal.close() 제거
						Swal.fire({
							icon: 'error',
							title: '시스템 오류',
							text: '시스템 오류가 발생했습니다. 다시 시도해주세요.',
							confirmButtonColor: '#007bff'
						});
						return;
					}
					
					try {
						const json_result = JSON.parse(result);
						// Swal.close() 제거
						
						if (json_result.result == 'true') {
							if (json_result.ticket_count === 0) {
								Swal.fire({
									icon: 'warning',
									title: '이용권 없음',
									text: json_result.msg,
									confirmButtonColor: '#007bff'
								});
							} else if (json_result.ticket_count === 1) {
								// 이용권이 1개면 바로 체크인
								selectedTicket = json_result.tickets[0];
								window.currentMemberName = selectedTicket.mem_nm || selectedTicket.MEM_NM || '회원';
								confirmQRCheckin(compCd, bcoffCd, memSno);
							} else {
								// 이용권이 여러개면 선택 모달 표시
								currentMemberNumber = memSno;
								window.currentMemberName = json_result.tickets[0].mem_nm || json_result.tickets[0].MEM_NM || '회원';
								showTicketSelectionModal(json_result.tickets);
							}
						} else {
							Swal.fire({
								icon: 'error',
								title: '이용권 조회 실패',
								text: json_result.msg,
								confirmButtonColor: '#007bff'
							});
						}
					} catch (e) {
						console.error('JSON parse error:', e);
						console.error('Response was:', result);
						
						// Swal.close() 제거
						Swal.fire({
							icon: 'error',
							title: '응답 처리 오류',
							text: '서버 응답을 처리하는 중 오류가 발생했습니다.',
							confirmButtonColor: '#007bff'
						});
					}
				},
				error: function(xhr, status, error) {
					console.error('Get Tickets API Error:', xhr, status, error);
					// Swal.close() 제거
					
					Swal.fire({
						icon: 'error',
						title: '네트워크 오류',
						text: '네트워크 오류가 발생했습니다. 다시 시도해 주세요.',
						confirmButtonColor: '#007bff'
					});
				}
			});
		}

		// QR 스캔으로 체크인 확인 처리
		function confirmQRCheckin(compCd, bcoffCd, memSno) {
			// 불필요한 로딩 팝업 제거 - 바로 처리
			jQuery.ajax({
				url: '/api/checkin_with_ticket',
				type: 'POST',
				contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
				dataType: 'text',
				data: 'comp_cd=' + encodeURIComponent(compCd) + 
					  '&bcoff_cd=' + encodeURIComponent(bcoffCd) + 
					  '&mem_sno=' + encodeURIComponent(memSno) + 
					  '&ticket_id=' + encodeURIComponent(selectedTicket.ticket_id),
				success: function (result) {
					console.log('Checkin API Response:', result);
					
					try {
						const json_result = JSON.parse(result);
						// Swal.close() 제거
						
						if (json_result.result == 'true') {
							// API 응답에서 사용된 이용권 정보 사용
							let successMessage = json_result.msg;
							if (json_result.used_ticket) {
								debugger;
								successMessage = `${json_result.used_ticket.mem_nm}님, ${json_result.used_ticket.ticket_name}이용권으로 입장이 완료되었습니다.`;
							}
							
							showSwal({
								icon: 'success',
								title: '입장 완료!',
								text: successMessage,
								timer: 10000,
								showConfirmButton: true,
								confirmButtonText: '확인',
								confirmButtonColor: '#007bff'
							});
							
							// 초기화
							clearAll();
							selectedTicket = null;
							currentMemberNumber = null;
							currentQRData = null;
						} else {
							Swal.fire({
								icon: 'warning',
								title: '입장 실패',
								text: json_result.msg,
								confirmButtonColor: '#007bff'
							});
						}
					} catch (e) {
						console.error('JSON parse error:', e);
						console.error('Response was:', result);
						
						// Swal.close() 제거
						Swal.fire({
							icon: 'error',
							title: '응답 처리 오류',
							text: '서버 응답을 처리하는 중 오류가 발생했습니다.',
							confirmButtonColor: '#007bff'
						});
					}
				},
				error: function(xhr, status, error) {
					console.error('Checkin API Error:', xhr, status, error);
					// Swal.close() 제거
					
					Swal.fire({
						icon: 'error',
						title: '네트워크 오류',
						text: '네트워크 오류가 발생했습니다. 다시 시도해 주세요.',
						confirmButtonColor: '#007bff'
					});
				}
			});
		}

		// 회원 이용권 목록 조회 (회원번호 입력용)
		function getMemberTickets(memSno) {
			// 불필요한 로딩 팝업 제거 - 바로 조회
			jQuery.ajax({
				url: '/api/get_member_tickets',
				type: 'POST',
				contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
				dataType: 'text',
				data: 'mem_sno=' + encodeURIComponent(memSno) + 
					  '&comp_cd=<?php echo $_SESSION["comp_cd"] ?? ""; ?>' + 
					  '&bcoff_cd=<?php echo $_SESSION["bcoff_cd"] ?? ""; ?>',
				success: function (result) {
					console.log('Get Member Tickets API Response:', result);
					
					// 로그인 만료 체크
					if (result.substr(0, 8) == '<script>') {
						// Swal.close() 제거
						Swal.fire({
							icon: 'error',
							title: '시스템 오류',
							text: '시스템 오류가 발생했습니다. 다시 시도해주세요.',
							confirmButtonColor: '#007bff'
						});
						return;
					}
					
					try {
						const json_result = JSON.parse(result);
						// Swal.close() 제거
						
						if (json_result.result == 'true') {
							if (json_result.ticket_count === 0) {
								Swal.fire({
									icon: 'warning',
									title: '이용권 없음',
									text: json_result.msg,
									confirmButtonColor: '#007bff'
								});
							} else if (json_result.ticket_count === 1) {
								// 이용권이 1개면 확인 후 체크인
								selectedTicket = json_result.tickets[0];
								const memberName =selectedTicket.mem_nm || '회원';
								
								isPopupOpen = true; // 팝업 열림 상태 설정
								Swal.fire({
									icon: 'question',
									title: '입장 확인',
									html: `<strong>${memberName}</strong>님 안녕하세요!<br><br><strong>${selectedTicket.ticket_name}</strong>으로<br>입장하시겠습니까?`,
									showCancelButton: true,
									confirmButtonText: '입장',
									cancelButtonText: '취소',
									confirmButtonColor: '#007bff',
									cancelButtonColor: '#6c757d',
									didOpen: () => {
										isPopupOpen = true;
									},
									didClose: () => {
										isPopupOpen = false;
									}
								}).then((result) => {
									isPopupOpen = false; // 팝업 닫힘
									if (result.isConfirmed) {
										confirmCheckin();
									} else {
										// 취소 시 초기화
										clearAll();
										selectedTicket = null;
										window.currentMemberName = null;
									}
								});
							} else {
								// 이용권이 여러개면 선택 모달 표시
								showTicketSelectionModal(json_result.tickets);
							}
						} else {
							Swal.fire({
								icon: 'error',
								title: '이용권 조회 실패',
								text: json_result.msg,
								confirmButtonColor: '#007bff'
							});
						}
					} catch (e) {
						console.error('JSON parse error:', e);
						console.error('Response was:', result);
						
						// Swal.close() 제거
						Swal.fire({
							icon: 'error',
							title: '응답 처리 오류',
							text: '서버 응답을 처리하는 중 오류가 발생했습니다.',
							confirmButtonColor: '#007bff'
						});
					}
				},
				error: function(xhr, status, error) {
					console.error('Get Member Tickets API Error:', xhr, status, error);
					// Swal.close() 제거
					
					Swal.fire({
						icon: 'error',
						title: '네트워크 오류',
						text: '네트워크 오류가 발생했습니다. 다시 시도해 주세요.',
						confirmButtonColor: '#007bff'
					});
				}
			});
		}

		// 전화번호로 회원 이용권 목록 조회
		function getMemberTicketsByTelno(telno) {
			// 불필요한 로딩 팝업 제거 - 바로 조회
			jQuery.ajax({
				url: '/api/get_member_tickets_by_telno',
				type: 'POST',
				contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
				dataType: 'text',
				data: 'mem_telno=' + encodeURIComponent(telno),
				success: function (result) {
					console.log('Get Member Tickets By Telno API Response:', result);
					
					// 로그인 만료 체크
					if (result.substr(0, 8) == '<script>') {
						// Swal.close() 제거
						Swal.fire({
							icon: 'error',
							title: '시스템 오류',
							text: '시스템 오류가 발생했습니다. 다시 시도해주세요.',
							confirmButtonColor: '#007bff'
						});
						return;
					}
					
					try {
						const json_result = JSON.parse(result);
						// Swal.close() 제거
						
						if (json_result.result == 'true') {
							if (json_result.ticket_count === 0) {
								Swal.fire({
									icon: 'warning',
									title: '이용권 없음',
									text: json_result.msg,
									confirmButtonColor: '#007bff'
								});
							} else if (json_result.ticket_count === 1) {
								// 이용권이 1개면 바로 체크인
								selectedTicket = json_result.tickets[0];
								// 전화번호로 조회한 경우 회원번호와 회원명을 저장
								currentMemberNumber = selectedTicket.mem_sno;
								window.currentMemberName = selectedTicket.mem_nm || selectedTicket.MEM_NM || '회원';
								confirmCheckin();
							} else {
								// 이용권이 여러개면 선택 모달 표시
								// 첫 번째 이용권에서 회원번호와 회원명 가져오기
								currentMemberNumber = json_result.tickets[0].mem_sno;
								window.currentMemberName = json_result.tickets[0].mem_nm || json_result.tickets[0].MEM_NM || '회원';
								showTicketSelectionModal(json_result.tickets);
							}
						} else {
							Swal.fire({
								icon: 'error',
								title: '이용권 조회 실패',
								text: json_result.msg,
								confirmButtonColor: '#007bff'
							});
						}
					} catch (e) {
						console.error('JSON parse error:', e);
						console.error('Response was:', result);
						
						// Swal.close() 제거
						Swal.fire({
							icon: 'error',
							title: '응답 처리 오류',
							text: '서버 응답을 처리하는 중 오류가 발생했습니다.',
							confirmButtonColor: '#007bff'
						});
					}
				},
				error: function(xhr, status, error) {
					console.error('Get Member Tickets By Telno API Error:', xhr, status, error);
					// Swal.close() 제거
					
					Swal.fire({
						icon: 'error',
						title: '네트워크 오류',
						text: '네트워크 오류가 발생했습니다. 다시 시도해 주세요.',
						confirmButtonColor: '#007bff'
					});
				}
			});
		}

		// 이용권 선택 모달 표시
		function showTicketSelectionModal(tickets) {
			isPopupOpen = true; // 팝업 열림 상태 설정
			const modal = document.getElementById('ticketModal');
			const ticketList = document.getElementById('ticketList');
			const ticketModalTitle = document.getElementById('ticketModalTitle');
			
			// 회원명을 여러 방법으로 시도해서 가져오기
			let memberName = window.currentMemberName;
			if (!memberName && tickets && tickets.length > 0) {
				// tickets 배열에서 회원명 찾기 (다양한 필드명 시도)
				const firstTicket = tickets[0];
				memberName = firstTicket.mem_nm || firstTicket.MEM_NM || firstTicket.member_name || firstTicket.memberName || '회원';
			}
			if (!memberName) {
				memberName = '회원';
			}
			
			// 모달 제목 업데이트
			ticketModalTitle.innerHTML = `<strong>${memberName}</strong>님 안녕하세요!<br>이용권을 선택해주세요.`;
			
			console.log('showTicketSelectionModal - memberName:', memberName);
			console.log('showTicketSelectionModal - tickets:', tickets);
			
			// 이용권 목록 생성
			ticketList.innerHTML = '';
			tickets.forEach((ticket, index) => {
				const ticketItem = document.createElement('div');
				ticketItem.className = 'ticket-item';
				// 입장불가인 이용권은 비활성화
				if (ticket.status !== 'active') {
					ticketItem.className += ' ticket-disabled';
				} else {
					ticketItem.onclick = () => selectTicket(ticket, ticketItem);
				}
				
				// API 응답 형식에 맞게 수정
				const remainingInfo = ticket.remaining_info || '이용권 정보 없음';
				const detailInfo = ticket.detail_info || '';
				const expireDate = ticket.expire_date || '만료일 없음';
				
				// GX 수업 정보 또는 출석 정보 생성
				let infoHtml = '';
				if (ticket.gx_info) {
					// GX 수업인 경우
					const gx = ticket.gx_info;
					infoHtml = `
						<div class="gx-info">
							<strong>${gx.gx_clas_title}</strong> | ${gx.gx_room_title} | ${gx.class_start_time} ~ ${gx.class_end_time}
						</div>
					`;
				} else if (ticket.usage_info) {
					// 일반 이용권인 경우 출석 정보 표시
					infoHtml = `
						<div class="usage-info">
							${ticket.usage_info}
						</div>
					`;
				}
				
				ticketItem.innerHTML = `
					<div class="ticket-header">
						<div class="ticket-name">${ticket.ticket_name}</div>
						<div class="ticket-detail">${detailInfo}</div>
					</div>
					<div class="ticket-info">
						${remainingInfo} | 만료일: ${expireDate}
					</div>
					<div class="ticket-bottom">
						<div class="info-container">
							${infoHtml}
						</div>
						<span class="ticket-status ${ticket.status === 'active' ? 'status-active' : (ticket.enter_yn === 'N' ? 'status-inactive' : 'status-active')}">
							${(ticket.status === 'active' ? '입장가능' : (ticket.enter_yn === 'N' ? '입장불가' : '재입장'))}
						</span>
					</div>
				`;
				
				ticketList.appendChild(ticketItem);
			});
			
			modal.style.display = 'block';
		}

		// 이용권 선택
		function selectTicket(ticket, element) {
			// 기존 선택 해제
			document.querySelectorAll('.ticket-item').forEach(item => {
				item.classList.remove('selected');
			});
			
			// 새로운 선택
			element.classList.add('selected');
			selectedTicket = ticket;
		}

		// 이용권 선택 확인
		function confirmTicketSelection() {
			if (!selectedTicket) {
				Swal.fire({
					icon: 'warning',
					title: '이용권 선택',
					text: '이용권을 선택해주세요.',
					confirmButtonColor: '#007bff'
				});
				return;
			}
			
			closeTicketModal();
			
			// 여러 개 중 선택한 경우 바로 처리
			// QR 스캔인지 수동 입력인지 구분하여 처리
			if (currentQRData) {
				// QR 스캔의 경우
				const qrDiv = currentQRData.split('|');
				if (qrDiv.length === 4) {
					confirmQRCheckin(qrDiv[0], qrDiv[1], qrDiv[2]);
				}
			} else {
				// 수동 입력의 경우
				confirmCheckin();
			}
		}

		// 체크인 확인 처리 (회원번호 입력용)
		function confirmCheckin() {
			// 불필요한 로딩 팝업 제거 - 바로 처리
			jQuery.ajax({
				url: '/api/checkin_with_ticket',
				type: 'POST',
				contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
				dataType: 'text',
				data: 'mem_sno=' + encodeURIComponent(currentMemberNumber) + 
					  '&ticket_id=' + encodeURIComponent(selectedTicket.ticket_id),
				success: function (result) {
					console.log('Checkin API Response:', result);
					
					try {
						const json_result = JSON.parse(result);
						// Swal.close() 제거
						
						if (json_result.result == 'true') {
							// API 응답에서 사용된 이용권 정보 사용
							let successMessage = json_result.msg;
							if (json_result.used_ticket) {
								successMessage = `${json_result.used_ticket.mem_nm}님, ${json_result.used_ticket.ticket_name}이용권으로 입장이 완료되었습니다.`;
							}
							
							showSwal({
								icon: 'success',
								title: '입장 완료!',
								text: successMessage,
								timer: 10000,
								showConfirmButton: true,
								confirmButtonText: '확인',
								confirmButtonColor: '#007bff'
							});
							
							// 초기화
							clearAll();
							selectedTicket = null;
							currentMemberNumber = null;
						} else {
							Swal.fire({
								icon: 'warning',
								title: '입장 실패',
								text: json_result.msg,
								confirmButtonColor: '#007bff'
							});
						}
					} catch (e) {
						console.error('JSON parse error:', e);
						console.error('Response was:', result);
						
						// Swal.close() 제거
						Swal.fire({
							icon: 'error',
							title: '응답 처리 오류',
							text: '서버 응답을 처리하는 중 오류가 발생했습니다.',
							confirmButtonColor: '#007bff'
						});
					}
				},
				error: function(xhr, status, error) {
					console.error('Checkin API Error:', xhr, status, error);
					// Swal.close() 제거
					
					Swal.fire({
						icon: 'error',
						title: '네트워크 오류',
						text: '네트워크 오류가 발생했습니다. 다시 시도해 주세요.',
						confirmButtonColor: '#007bff'
					});
				}
			});
		}

		// 이용권 선택 모달 닫기
		function closeTicketModal() {
			// 입력된 번호 삭제
			clearAll();
			document.getElementById('ticketModal').style.display = 'none';
			isPopupOpen = false; // 팝업 닫힘
		}

		// 안면 인식 관련 전역 변수
		let faceStream = null;
		let faceCanvas = null;
		let faceContext = null;
		let faceRecognitionInProgress = false;

		// 안면 인식 시작
		function startFaceRecognition() {
			// 브라우저 호환성 체크
			if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
				console.error('getUserMedia is not supported in this browser/context');
				
				// HTTPS 체크
				if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost') {
					Swal.fire({
						icon: 'error',
						title: '보안 연결 필요',
						html: '카메라를 사용하려면 HTTPS 연결이 필요합니다.<br><br>' +
							  '현재 연결: ' + window.location.protocol + '//' + window.location.hostname + '<br>' +
							  'HTTPS로 접속하거나 시스템 관리자에게 문의하세요.',
						confirmButtonColor: '#17a2b8'
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: '브라우저 지원 오류',
						text: '이 브라우저는 카메라 기능을 지원하지 않습니다. Chrome, Firefox, Edge 등 최신 브라우저를 사용해주세요.',
						confirmButtonColor: '#17a2b8'
					});
				}
				return;
			}

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
					
					// 0.5초 후 자동 촬영 (기존의 절반)
					setTimeout(function() {
						if (faceStream && !faceRecognitionInProgress) {
							captureFace();
						}
					}, 500);
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

		// 모달 없이 안면 인식 시작 (Swal 사용)
		function startFaceRecognitionWithoutModal() {
			// 브라우저 호환성 체크 (안전을 위해 한번 더)
			if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
				Swal.fire({
					icon: 'error',
					title: '카메라 사용 불가',
					text: '이 환경에서는 카메라를 사용할 수 없습니다.',
					confirmButtonColor: '#17a2b8'
				});
				return;
			}

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

					// 0.5초 후 자동 촬영 (기존의 절반)
					setTimeout(() => {
						captureAndRecognize(video, stream);
					}, 500);
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

		// 얼굴 상태 업데이트
		function updateFaceStatus(message, type) {
			const statusDiv = document.getElementById('faceStatus');
			if (statusDiv) {
				statusDiv.textContent = message;
				statusDiv.className = `face-status ${type}`;
			}
		}
		

		// 얼굴 촬영 및 인식 (2회 시도)
		function captureFace(attemptNumber = 1) {
			if (faceRecognitionInProgress && attemptNumber === 1) return;
			
			if (attemptNumber === 1) {
				faceRecognitionInProgress = true;
				updateFaceStatus('얼굴을 인식하고 있습니다...', 'detecting');
			} else {
				updateFaceStatus('다시 인식하고 있습니다...', 'detecting');
			}
			
			const video = document.getElementById('faceVideo');
			const canvas = faceCanvas;
			const ctx = faceContext;
			
			// 캔버스에 현재 비디오 프레임 그리기
			ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
			
			// 이미지를 Base64로 변환
			const imageDataUrl = canvas.toDataURL('image/jpeg', 0.8);
			const base64Data = imageDataUrl.split(',')[1]; // data:image/jpeg;base64, 부분 제거
			
			console.log(`Captured image size (attempt ${attemptNumber}):`, base64Data.length);
			
			// JSON 형태로 데이터 준비 (InsightFace 서버 포맷)
			const requestData = {
				image: base64Data,
				check_liveness: true,  // Liveness 검사 활성화
				check_blink: false     // 눈 깜빡임 검사 비활성화
			};
			
			// 타임아웃 설정: 1차 시도는 5초, 2차 시도는 5초
			const timeoutDuration = 5000;
			
			// 얼굴 인식 API 호출 (PHP 프록시 사용)
			jQuery.ajax({
				url: '/FaceTest/recognize_for_checkin',
				type: 'POST',
				data: JSON.stringify(requestData),
				contentType: 'application/json',
				dataType: 'json',
				timeout: timeoutDuration,
				success: function(result) {
					console.log('Face Recognition Response:', result);
					
					try {
						// 보안 검사 먼저 확인
						if (result.security_failed) {
							if (attemptNumber === 1) {
								// 첫 번째 시도 실패 - 바로 두 번째 시도
								console.log('보안 검사 실패 (1차), 두 번째 시도 시작');
								setTimeout(() => {
									captureFace(2);
								}, 500); // 0.5초 후 재시도
							} else {
								// 두 번째 시도도 실패
								updateFaceStatus('다시 시도해 주세요', 'warning');
								
								let errorMsg = '카메라를 정면으로 바라봐 주세요.';
								if (result.security_details) {
									if (!result.security_details.liveness_passed) {
										errorMsg = '조명이 밝은 곳에서 다시 시도해 주세요.';
									}
								}
								
								setTimeout(() => {
									Swal.fire({
										icon: 'info',
										title: '얼굴 인식 안내',
										text: errorMsg,
										confirmButtonColor: '#17a2b8'
									});
									closeFaceRecognition();
								}, 1500);
							}
							return;
						}
						
						if (result.success && result.face_matching && result.face_matching.match_found) {
							// 얼굴 인식 성공
							const memberSno = result.face_matching.member.mem_sno;
							const memberName = result.face_matching.member.mem_nm || result.face_matching.member.MEM_NM || '회원';
							const confidence = result.face_matching.similarity_score;
							
							updateFaceStatus(`인식 성공! ${memberName}님`, 'success');
							
							// 회원 이름을 전역 변수에 저장
							window.currentMemberName = memberName;
							
							// 바로 이용권 조회 처리
							setTimeout(() => {
								closeFaceRecognition();
								processFaceCheckin(memberSno, confidence);
							}, 500);
							
						} else {
							// 얼굴을 찾을 수 없음
							if (attemptNumber === 1) {
								// 첫 번째 시도 실패 - 바로 두 번째 시도
								console.log('첫 번째 시도 실패, 두 번째 시도 시작');
								setTimeout(() => {
									captureFace(2);
								}, 500); // 0.5초 후 재시도
							} else {
								// 두 번째 시도도 실패
								updateFaceStatus('다시 시도해 주세요', 'warning');
								
								// 더 친근한 메시지 표시
								const errorMsg = result.error || '카메라를 정면으로 바라봐 주시거나, 회원번호를 입력해 주세요.';
								
								setTimeout(() => {
									Swal.fire({
										icon: 'info',
										title: '얼굴을 찾을 수 없습니다',
										text: errorMsg,
										confirmButtonColor: '#17a2b8'
									});
									closeFaceRecognition();
								}, 1500);
							}
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
					console.error(`Face Recognition API Error (attempt ${attemptNumber}):`, xhr, status, error);
					
					if (attemptNumber === 1 && status === 'timeout') {
						// 첫 번째 시도에서 타임아웃 발생 시 재시도
						console.log('첫 번째 시도 타임아웃, 두 번째 시도 시작');
						setTimeout(() => {
							captureFace(2);
						}, 500);
					} else {
						// 두 번째 시도 실패 또는 타임아웃이 아닌 에러
						updateFaceStatus('API 호출 오류', 'error');
						
						let errorMessage = '얼굴 인식 서버와 통신할 수 없습니다.';
						
						if (xhr.status === 404) {
							errorMessage = 'InsightFace 서버 또는 API 엔드포인트를 찾을 수 없습니다.';
						} else if (xhr.status === 500) {
							errorMessage = '얼굴 인식 서버 내부 오류가 발생했습니다.';
						} else if (xhr.status === 0) {
							errorMessage = '얼굴 인식 서버에 연결할 수 없습니다.';
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
					}
				},
				complete: function() {
					if (attemptNumber === 2) {
						faceRecognitionInProgress = false;
					}
				}
			});
		}

		// 모달 없이 촬영 및 인식 (2회 시도)
		function captureAndRecognize(video, stream, attemptNumber = 1) {
			const canvas = document.createElement('canvas');
			const ctx = canvas.getContext('2d');
			
			canvas.width = video.videoWidth;
			canvas.height = video.videoHeight;
			
			// 캔버스에 현재 비디오 프레임 그리기
			ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
			
			// 2차 시도가 아닌 경우에만 스트림 정리
			if (attemptNumber === 2 || !stream) {
				if (stream) {
					stream.getTracks().forEach(track => track.stop());
				}
				if (video.parentNode) {
					document.body.removeChild(video);
				}
			}
			
			// 이미지를 Base64로 변환
			const imageDataUrl = canvas.toDataURL('image/jpeg', 0.8);
			const base64Data = imageDataUrl.split(',')[1]; // data:image/jpeg;base64, 부분 제거
			
			console.log(`Captured image size (attempt ${attemptNumber}):`, base64Data.length);
			
			// JSON 형태로 데이터 준비 (InsightFace 서버 포맷)
			const requestData = {
				image: base64Data,
				check_liveness: true,  // Liveness 검사 활성화
				check_blink: false     // 모달 없이는 눈 깜빡임 체크 불가
			};
			
			// 얼굴 인식 API 호출 (PHP 프록시 사용)
			jQuery.ajax({
				url: '/FaceTest/recognize_for_checkin',
				type: 'POST',
				data: JSON.stringify(requestData),
				contentType: 'application/json',
				dataType: 'json',
				success: function(result) {
					console.log('Face Recognition Response:', result);
					
					try {
						// 보안 검사 먼저 확인
						if (result.security_failed) {
							if (attemptNumber === 1) {
								// 첫 번째 시도 실패 - 재시도
								console.log('보안 검사 실패 (1차), 두 번째 시도 시작');
								Swal.update({
									title: '얼굴 인식 중...',
									text: '다시 시도하고 있습니다.'
								});
								setTimeout(() => {
									captureAndRecognize(video, stream, 2);
								}, 500);
							} else {
								// 두 번째 시도도 실패
								let errorMsg = '카메라를 정면으로 바라봐 주세요.';
								if (result.security_details) {
									if (!result.security_details.liveness_passed) {
										errorMsg = '조명이 밝은 곳에서 다시 시도해 주세요.';
									}
								}
								
								Swal.fire({
									icon: 'info',
									title: '얼굴 인식 안내',
									text: errorMsg,
									confirmButtonColor: '#17a2b8'
								});
							}
							return;
						}
						
						if (result.success && result.face_matching && result.face_matching.match_found) {
							// 얼굴 인식 성공
							const memberSno = result.face_matching.member.mem_sno;
							const memberName = result.face_matching.member.mem_nm || result.face_matching.member.MEM_NM || '회원';
							const confidence = result.face_matching.similarity_score;
							
							// 회원 이름을 전역 변수에 저장
							window.currentMemberName = memberName;
							
							// 바로 이용권 조회 처리
							Swal.close();
							processFaceCheckin(memberSno, confidence);
							
						} else {
							// 얼굴을 찾을 수 없음
							if (attemptNumber === 1) {
								// 첫 번째 시도 실패 - 재시도
								console.log('얼굴 인식 실패 (1차), 두 번째 시도 시작');
								Swal.update({
									title: '얼굴 인식 중...',
									text: '다시 시도하고 있습니다.'
								});
								setTimeout(() => {
									captureAndRecognize(video, stream, 2);
								}, 500);
							} else {
								// 두 번째 시도도 실패
								const errorMsg = result.error || '카메라를 정면으로 바라봐 주시거나, 회원번호를 입력해 주세요.';
								
								Swal.fire({
									icon: 'info',
									title: '얼굴을 찾을 수 없습니다',
									text: errorMsg,
									confirmButtonColor: '#17a2b8'
								});
							}
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
					Swal.fire({
						icon: 'error',
						title: '네트워크 오류',
						text: '얼굴 인식 서버와 통신할 수 없습니다.',
						confirmButtonColor: '#17a2b8'
					});
				}
			});
		}

		// 얼굴 인식 결과로 체크인 처리
		function processFaceCheckin(memberSno, confidence) {
			// 회원번호는 표시하지 않음 (자동 입력 제거)
			// memberNumber = memberSno;
			// updateDisplay();
			
			// 이용권 조회 및 체크인 처리
			currentMemberNumber = memberSno;
			currentQRData = null;
			getMemberTickets(memberSno);
		}

		// 안면 인식 모달 닫기
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

		// 키보드 입력 지원
		document.addEventListener('keydown', function(event) {
			if (event.key >= '0' && event.key <= '9') {
				addNumber(event.key);
			} else if (event.key === 'Backspace') {
				event.preventDefault();
				backspace();
			} else if (event.key === 'Delete' || event.key === 'Escape') {
				clearAll();
			} else if (event.key === 'Enter') {
				processCheckin();
			}
		});

		// QR 스캐너 관련 변수
		let qrStream = null;
		let qrCanvas = null;
		let qrContext = null;
		let qrScanInterval = null;

		// QR 스캐너 시작
		function startQRScanner() {
			// 브라우저 호환성 체크
			if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
				console.error('getUserMedia is not supported in this browser/context');
				
				// HTTPS 체크
				if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost') {
					Swal.fire({
						icon: 'error',
						title: '보안 연결 필요',
						html: '카메라를 사용하려면 HTTPS 연결이 필요합니다.<br><br>' +
							  '현재 연결: ' + window.location.protocol + '//' + window.location.hostname + '<br>' +
							  'HTTPS로 접속하거나 시스템 관리자에게 문의하세요.',
						confirmButtonColor: '#28a745'
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: '브라우저 지원 오류',
						text: '이 브라우저는 카메라 기능을 지원하지 않습니다. Chrome, Firefox, Edge 등 최신 브라우저를 사용해주세요.',
						confirmButtonColor: '#28a745'
					});
				}
				return;
			}

			const modal = document.getElementById('qrModal');
			const video = document.getElementById('qrVideo');
			qrCanvas = document.getElementById('qrCanvas');
			qrContext = qrCanvas.getContext('2d', { willReadFrequently: true });

			modal.style.display = 'block';

			// 카메라 접근
			navigator.mediaDevices.getUserMedia({ 
				video: { 
					facingMode: 'environment' // 후면 카메라 우선
				} 
			})
			.then(function(stream) {
				qrStream = stream;
				video.srcObject = stream;
				video.play();
				
				// 비디오 메타데이터 로드 후 스캔 시작
				video.addEventListener('loadedmetadata', function() {
					qrCanvas.width = video.videoWidth;
					qrCanvas.height = video.videoHeight;
					startQRScan();
				});
			})
			.catch(function(err) {
				console.error('카메라 접근 실패:', err);
				Swal.fire({
					icon: 'error',
					title: '카메라 접근 실패',
					text: '카메라에 접근할 수 없습니다. 권한을 확인해주세요.',
					confirmButtonColor: '#28a745'
				});
				closeQRScanner();
			});
		}

		// QR 스캔 시작
		function startQRScan() {
			const video = document.getElementById('qrVideo');
			
			qrScanInterval = setInterval(function() {
				if (video.readyState === video.HAVE_ENOUGH_DATA) {
					qrContext.drawImage(video, 0, 0, qrCanvas.width, qrCanvas.height);
					const imageData = qrContext.getImageData(0, 0, qrCanvas.width, qrCanvas.height);
					const code = jsQR(imageData.data, imageData.width, imageData.height);
					
					if (code) {
						// QR 코드 감지됨
						const qrData = code.data;
						processQRResult(qrData);
					}
				}
			}, 100); // 100ms마다 스캔
		}

		// QR 결과 처리
		function processQRResult(data) {
			closeQRScanner();
			
			// QR 코드 형태: comp_cd|bcoff_cd|mem_sno|timestamp
			const qrDiv = data.split('|');
			
			if (qrDiv.length === 4) {
				// QR 코드 유효성 검사
				const chkQr = parseInt(qrDiv[3].substr(0, 9)) + 1;
				const chkTime = parseInt(String(Math.floor(Date.now() / 1000)).substr(0, 9));
				
				if (chkQr < chkTime) {
					Swal.fire({
						icon: 'error',
						title: 'QR 코드 만료',
						text: 'QR 코드를 다시 확인해주세요.',
						confirmButtonColor: '#28a745'
					});
					return;
				}
				
				// QR 스캔으로 체크인 처리
				processQRCheckin(data);
			} else {
				// QR이 아닌 일반 숫자인 경우 회원번호로 처리
				const memberNumber = data.replace(/\D/g, '');
				if (memberNumber && memberNumber.length > 0) {
					window.memberNumber = memberNumber;
					updateDisplay();
					
					Swal.fire({
						icon: 'success',
						title: 'QR 스캔 완료!',
						text: `회원번호: ${memberNumber}`,
						timer: 1500,
						showConfirmButton: false,
						confirmButtonColor: '#28a745'
					});
				}
			}
		}

		// QR 스캐너 종료
		function closeQRScanner() {
			const modal = document.getElementById('qrModal');
			const video = document.getElementById('qrVideo');
			
			// 스캔 중지
			if (qrScanInterval) {
				clearInterval(qrScanInterval);
				qrScanInterval = null;
			}
			
			// 카메라 스트림 종료
			if (qrStream) {
				qrStream.getTracks().forEach(track => track.stop());
				qrStream = null;
			}
			
			// 비디오 초기화
			video.srcObject = null;
			
			// 모달 닫기
			modal.style.display = 'none';
		}

		// 모달 외부 클릭시 닫기
		window.onclick = function(event) {
			const qrModal = document.getElementById('qrModal');
			const ticketModal = document.getElementById('ticketModal');
			
			if (event.target === qrModal) {
				closeQRScanner();
			}
			
			if (event.target === ticketModal) {
				closeTicketModal();
			}
		}

		// 초기화
		document.addEventListener('DOMContentLoaded', function() {
			updateDisplay();
		});
	</script>

</body></html>