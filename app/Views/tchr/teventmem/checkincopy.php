<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>체크인 시스템</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
			min-height: 100vh;
			overflow-x: hidden;
		}

		.container {
			max-width: 1400px;
			margin: 0 auto;
			padding: 20px;
			min-height: 100vh;
		}

		/* 헤더 */
		.header {
			text-align: center;
			color: white;
			margin-bottom: 40px;
		}

		.header h1 {
			font-size: 3.5rem;
			font-weight: 300;
			margin-bottom: 10px;
			text-shadow: 0 2px 10px rgba(0,0,0,0.3);
		}

		.current-time {
			font-size: 1.4rem;
			opacity: 0.9;
			font-weight: 300;
		}

		/* 메인 그리드 */
		.main-grid {
			display: grid;
			grid-template-columns: 2fr 1fr;
			gap: 30px;
			margin-bottom: 30px;
		}

		/* 체크인 섹션 */
		.checkin-section {
			background: rgba(255, 255, 255, 0.95);
			border-radius: 20px;
			padding: 40px;
			box-shadow: 0 20px 60px rgba(0,0,0,0.1);
			backdrop-filter: blur(10px);
		}

		.section-title {
			font-size: 1.8rem;
			color: #2d3436;
			margin-bottom: 30px;
			font-weight: 600;
		}

		/* 검색 영역 */
		.search-container {
			background: #f8f9fa;
			border-radius: 15px;
			padding: 30px;
			margin-bottom: 30px;
		}

		.search-input {
			width: 100%;
			padding: 20px;
			font-size: 1.2rem;
			border: 2px solid #e9ecef;
			border-radius: 12px;
			background: white;
			transition: all 0.3s ease;
			margin-bottom: 20px;
		}

		.search-input:focus {
			outline: none;
			border-color: #74b9ff;
			box-shadow: 0 0 0 3px rgba(116, 185, 255, 0.1);
		}

		.btn-primary {
			background: linear-gradient(135deg, #74b9ff, #0984e3);
			color: white;
			border: none;
			padding: 15px 30px;
			border-radius: 12px;
			font-size: 1.1rem;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.3s ease;
			width: 100%;
		}

		.btn-primary:hover {
			transform: translateY(-2px);
			box-shadow: 0 10px 25px rgba(116, 185, 255, 0.3);
		}

		/* QR 스캔 영역 */
		.qr-section {
			text-align: center;
			padding: 40px;
			background: #f8f9fa;
			border-radius: 15px;
			border: 2px dashed #74b9ff;
			margin-bottom: 30px;
		}

		.qr-icon {
			font-size: 4rem;
			color: #74b9ff;
			margin-bottom: 20px;
		}

		/* 통계 섹션 */
		.stats-section {
			background: rgba(255, 255, 255, 0.95);
			border-radius: 20px;
			padding: 30px;
			box-shadow: 0 20px 60px rgba(0,0,0,0.1);
			backdrop-filter: blur(10px);
		}

		.stat-card {
			background: linear-gradient(135deg, #ffffff, #f8f9fa);
			border-radius: 15px;
			padding: 25px;
			margin-bottom: 20px;
			text-align: center;
			border: 1px solid #e9ecef;
			transition: all 0.3s ease;
		}

		.stat-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 15px 35px rgba(0,0,0,0.1);
		}

		.stat-number {
			font-size: 2.5rem;
			font-weight: 700;
			color: #0984e3;
			margin-bottom: 5px;
		}

		.stat-label {
			font-size: 0.9rem;
			color: #636e72;
			font-weight: 500;
		}

		/* 최근 활동 */
		.recent-activity {
			background: rgba(255, 255, 255, 0.95);
			border-radius: 20px;
			padding: 30px;
			box-shadow: 0 20px 60px rgba(0,0,0,0.1);
			backdrop-filter: blur(10px);
		}

		.activity-item {
			display: flex;
			align-items: center;
			padding: 15px 0;
			border-bottom: 1px solid #e9ecef;
			transition: all 0.3s ease;
		}

		.activity-item:hover {
			background: #f8f9fa;
			margin: 0 -15px;
			padding: 15px;
			border-radius: 10px;
		}

		.activity-avatar {
			width: 50px;
			height: 50px;
			border-radius: 50%;
			background: linear-gradient(135deg, #74b9ff, #0984e3);
			display: flex;
			align-items: center;
			justify-content: center;
			color: white;
			font-weight: 600;
			margin-right: 15px;
		}

		.activity-info {
			flex: 1;
		}

		.activity-name {
			font-weight: 600;
			color: #2d3436;
			margin-bottom: 5px;
		}

		.activity-time {
			font-size: 0.85rem;
			color: #636e72;
		}

		.activity-status {
			padding: 5px 12px;
			border-radius: 20px;
			font-size: 0.8rem;
			font-weight: 600;
		}

		.status-checkin {
			background: #00b894;
			color: white;
		}

		.status-checkout {
			background: #e17055;
			color: white;
		}

		/* 검색 결과 */
		.search-results {
			background: white;
			border-radius: 12px;
			border: 1px solid #e9ecef;
			margin-top: 15px;
			display: none;
		}

		.member-item {
			padding: 15px;
			border-bottom: 1px solid #e9ecef;
			cursor: pointer;
			transition: all 0.3s ease;
			display: flex;
			align-items: center;
		}

		.member-item:hover {
			background: #f8f9fa;
		}

		.member-avatar {
			width: 40px;
			height: 40px;
			border-radius: 50%;
			background: linear-gradient(135deg, #74b9ff, #0984e3);
			display: flex;
			align-items: center;
			justify-content: center;
			color: white;
			font-weight: 600;
			margin-right: 15px;
		}

		.member-info {
			flex: 1;
		}

		.member-name {
			font-weight: 600;
			color: #2d3436;
		}

		.member-details {
			font-size: 0.85rem;
			color: #636e72;
		}

		/* 모바일 반응형 */
		@media (max-width: 768px) {
			.main-grid {
				grid-template-columns: 1fr;
				gap: 20px;
			}

			.header h1 {
				font-size: 2.5rem;
			}

			.checkin-section, .stats-section {
				padding: 25px;
			}

			.search-container {
				padding: 20px;
			}
		}

		/* 로딩 애니메이션 */
		.loading {
			display: inline-block;
			width: 20px;
			height: 20px;
			border: 3px solid #f3f3f3;
			border-top: 3px solid #74b9ff;
			border-radius: 50%;
			animation: spin 1s linear infinite;
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}

		/* 성공 애니메이션 */
		.success-animation {
			animation: successPulse 0.6s ease-in-out;
		}

		@keyframes successPulse {
			0% { transform: scale(1); }
			50% { transform: scale(1.05); }
			100% { transform: scale(1); }
		}
	</style>
</head>
<body>
	<div class="container">
		<!-- 헤더 -->
		<div class="header">
			<h1><i class="fas fa-user-check"></i> 체크인 시스템</h1>
			<div class="current-time" id="currentTime"></div>
		</div>

		<!-- 메인 그리드 -->
		<div class="main-grid">
			<!-- 체크인 섹션 -->
			<div class="checkin-section">
				<h2 class="section-title"><i class="fas fa-search"></i> 회원 체크인</h2>
				
				<!-- 검색 영역 -->
				<div class="search-container">
					<input type="text" 
						   class="search-input" 
						   id="memberSearch" 
						   placeholder="회원 이름 또는 번호를 입력하세요"
						   autocomplete="off">
					
					<button class="btn-primary" onclick="processCheckin()">
						<i class="fas fa-sign-in-alt"></i> 체크인
					</button>

					<!-- 검색 결과 -->
					<div class="search-results" id="searchResults">
						<div id="memberList"></div>
					</div>
				</div>

				<!-- QR 스캔 영역 -->
				<div class="qr-section">
					<div class="qr-icon">
						<i class="fas fa-qrcode"></i>
					</div>
					<h3>QR 코드 스캔</h3>
					<p style="color: #636e72; margin: 15px 0;">QR 코드를 스캔하여 빠른 체크인</p>
					<button class="btn-primary" onclick="startQRScanner()">
						<i class="fas fa-camera"></i> 카메라 시작
					</button>
				</div>
			</div>

			<!-- 통계 섹션 -->
			<div class="stats-section">
				<h2 class="section-title"><i class="fas fa-chart-line"></i> 실시간 현황</h2>
				
				<div class="stat-card">
					<div class="stat-number" id="todayCount">-</div>
					<div class="stat-label">오늘 출입자</div>
				</div>

				<div class="stat-card">
					<div class="stat-number" id="currentCount">-</div>
					<div class="stat-label">현재 이용중</div>
				</div>

				<div class="stat-card">
					<div class="stat-number" id="weekCount">-</div>
					<div class="stat-label">이번주 출입자</div>
				</div>

				<div class="stat-card">
					<div class="stat-number" id="monthCount">-</div>
					<div class="stat-label">이번달 출입자</div>
				</div>
			</div>
		</div>

		<!-- 최근 활동 -->
		<div class="recent-activity">
			<h2 class="section-title">
				<i class="fas fa-history"></i> 최근 활동
				<button class="btn-primary" style="float: right; width: auto; padding: 8px 16px; font-size: 0.9rem;" onclick="refreshActivity()">
					<i class="fas fa-sync"></i> 새로고침
				</button>
			</h2>
			
			<div id="activityList">
				<div style="text-align: center; padding: 40px; color: #636e72;">
					<div class="loading"></div>
					<div style="margin-top: 15px;">최근 활동을 불러오는 중...</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		// 현재 시간 업데이트
		function updateCurrentTime() {
			const now = new Date();
			const options = {
				year: 'numeric',
				month: 'long',
				day: 'numeric',
				hour: '2-digit',
				minute: '2-digit',
				second: '2-digit',
				weekday: 'long'
			};
			
			document.getElementById('currentTime').textContent = 
				now.toLocaleDateString('ko-KR', options);
		}

		// 회원 검색 (실시간)
		document.getElementById('memberSearch').addEventListener('input', function() {
			const query = this.value.trim();
			const resultsDiv = document.getElementById('searchResults');
			
			if (query.length >= 2) {
				// 실제로는 AJAX로 회원 검색
				resultsDiv.style.display = 'block';
				document.getElementById('memberList').innerHTML = `
					<div class="member-item" onclick="selectMember('홍길동', 'M001')">
						<div class="member-avatar">홍</div>
						<div class="member-info">
							<div class="member-name">홍길동</div>
							<div class="member-details">회원번호: M001 • 정회원</div>
						</div>
					</div>
					<div class="member-item" onclick="selectMember('김영희', 'M002')">
						<div class="member-avatar">김</div>
						<div class="member-info">
							<div class="member-name">김영희</div>
							<div class="member-details">회원번호: M002 • 정회원</div>
						</div>
					</div>
				`;
			} else {
				resultsDiv.style.display = 'none';
			}
		});

		// 회원 선택
		function selectMember(name, id) {
			document.getElementById('memberSearch').value = `${name} (${id})`;
			document.getElementById('searchResults').style.display = 'none';
		}

		// 체크인 처리
		function processCheckin() {
			const memberSearch = document.getElementById('memberSearch').value.trim();
			
			if (!memberSearch) {
				Swal.fire({
					icon: 'warning',
					title: '입력 필요',
					text: '회원 정보를 입력해주세요.',
					confirmButtonColor: '#74b9ff'
				});
				return;
			}

			// 로딩 표시
			Swal.fire({
				title: '체크인 처리중...',
				html: '<div class="loading" style="margin: 20px auto;"></div>',
				allowOutsideClick: false,
				showConfirmButton: false
			});

			// 실제로는 AJAX로 체크인 처리
			setTimeout(() => {
				Swal.fire({
					icon: 'success',
					title: '체크인 완료!',
					text: `${memberSearch}님이 체크인되었습니다.`,
					timer: 2000,
					showConfirmButton: false,
					confirmButtonColor: '#74b9ff'
				});

				// 입력 필드 초기화
				document.getElementById('memberSearch').value = '';
				document.getElementById('searchResults').style.display = 'none';

				// 데이터 새로고침
				refreshActivity();
				updateStats();
			}, 1500);
		}

		// QR 스캐너 시작
		function startQRScanner() {
			Swal.fire({
				icon: 'info',
				title: 'QR 스캐너',
				text: 'QR 스캐너 기능을 구현 중입니다.',
				confirmButtonColor: '#74b9ff'
			});
		}

		// 최근 활동 새로고침
		function refreshActivity() {
			const container = document.getElementById('activityList');
			
			container.innerHTML = `
				<div style="text-align: center; padding: 40px; color: #636e72;">
					<div class="loading"></div>
					<div style="margin-top: 15px;">최신 정보를 불러오는 중...</div>
				</div>
			`;

			// 실제로는 AJAX로 데이터를 가져와야 함
			setTimeout(() => {
				container.innerHTML = `
					<div class="activity-item">
						<div class="activity-avatar">홍</div>
						<div class="activity-info">
							<div class="activity-name">홍길동 (M001)</div>
							<div class="activity-time">${new Date().toLocaleString('ko-KR')}</div>
						</div>
						<div class="activity-status status-checkin">체크인</div>
					</div>
					<div class="activity-item">
						<div class="activity-avatar">김</div>
						<div class="activity-info">
							<div class="activity-name">김영희 (M002)</div>
							<div class="activity-time">5분 전</div>
						</div>
						<div class="activity-status status-checkout">체크아웃</div>
					</div>
					<div class="activity-item">
						<div class="activity-avatar">박</div>
						<div class="activity-info">
							<div class="activity-name">박철수 (M003)</div>
							<div class="activity-time">15분 전</div>
						</div>
						<div class="activity-status status-checkin">체크인</div>
					</div>
				`;
			}, 1000);
		}

		// 통계 업데이트
		function updateStats() {
			// 실제로는 AJAX로 실시간 통계를 가져와야 함
			document.getElementById('todayCount').textContent = Math.floor(Math.random() * 100) + 50;
			document.getElementById('currentCount').textContent = Math.floor(Math.random() * 30) + 15;
			document.getElementById('weekCount').textContent = Math.floor(Math.random() * 500) + 300;
			document.getElementById('monthCount').textContent = Math.floor(Math.random() * 2000) + 1200;
		}

		// Enter 키로 체크인
		document.getElementById('memberSearch').addEventListener('keypress', function(e) {
			if (e.key === 'Enter') {
				processCheckin();
			}
		});

		// 초기화
		document.addEventListener('DOMContentLoaded', function() {
			updateCurrentTime();
			setInterval(updateCurrentTime, 1000);
			
			refreshActivity();
			updateStats();
			
			// 자동 새로고침 (30초마다)
			setInterval(() => {
				refreshActivity();
				updateStats();
			}, 30000);
			
			// 검색 입력 필드에 포커스
			document.getElementById('memberSearch').focus();
		});
	</script>
</body>
</html>