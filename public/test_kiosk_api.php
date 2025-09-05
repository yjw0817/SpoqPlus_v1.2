<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>키오스크 API 테스트</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .test-section {
            margin: 30px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .test-section h2 {
            margin-top: 0;
            color: #007bff;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        button:hover {
            background: #0056b3;
        }
        .result {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            max-height: 400px;
            overflow-y: auto;
        }
        .success {
            border-left: 4px solid #28a745;
        }
        .error {
            border-left: 4px solid #dc3545;
        }
        .warning {
            border-left: 4px solid #ffc107;
        }
        #video {
            width: 100%;
            max-width: 640px;
            height: auto;
            border: 2px solid #ddd;
            border-radius: 5px;
        }
        #canvas {
            display: none;
        }
        .camera-controls {
            margin: 20px 0;
        }
        select, input[type="text"] {
            padding: 8px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .ticket-list {
            margin: 20px 0;
        }
        .ticket-item {
            background: #f8f9fa;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            cursor: pointer;
        }
        .ticket-item:hover {
            background: #e9ecef;
        }
        .ticket-item.selected {
            background: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>키오스크 얼굴 인증 API 테스트</h1>
        
        <!-- 카메라 테스트 섹션 -->
        <div class="test-section">
            <h2>1. 얼굴 인증 테스트</h2>
            
            <div>
                <label>언어 선택:</label>
                <select id="language">
                    <option value="ko">한국어</option>
                    <option value="en">English</option>
                    <option value="ja">日本語</option>
                    <option value="zh">中文</option>
                </select>
                
                <label>장치 ID:</label>
                <input type="text" id="deviceId" value="KIOSK_TEST_001" />
                
                <label>위치:</label>
                <input type="text" id="location" value="TEST_LOCATION" />
            </div>
            
            <div class="camera-controls">
                <button onclick="startCamera()">카메라 시작</button>
                <button onclick="captureAndAuth()">얼굴 촬영 및 인증</button>
                <button onclick="stopCamera()">카메라 중지</button>
            </div>
            
            <video id="video" autoplay></video>
            <canvas id="canvas"></canvas>
            
            <div id="authResult" class="result"></div>
        </div>
        
        <!-- 체크인 테스트 섹션 -->
        <div class="test-section">
            <h2>2. 체크인 처리 테스트</h2>
            
            <div id="ticketList" class="ticket-list"></div>
            
            <button onclick="processCheckin()" disabled id="checkinBtn">선택한 이용권으로 체크인</button>
            
            <div id="checkinResult" class="result"></div>
        </div>
        
        <!-- 시나리오 테스트 -->
        <div class="test-section">
            <h2>3. 시나리오별 테스트</h2>
            
            <button onclick="testNoFace()">얼굴 미검출 시나리오</button>
            <button onclick="testSecurityFail()">보안 검증 실패 시나리오</button>
            <button onclick="testMemberNotFound()">회원 미발견 시나리오</button>
            <button onclick="testNoTickets()">이용권 없음 시나리오</button>
            
            <div id="scenarioResult" class="result"></div>
        </div>
    </div>
    
    <script>
        let stream = null;
        let selectedMember = null;
        let selectedTicket = null;
        
        // API Base URL (개발 환경에 맞게 수정)
        const API_BASE_URL = window.location.origin + '/api/v1/kiosk';
        
        // API 상태 확인
        checkAPIStatus();
        
        // API 상태 확인 함수
        function checkAPIStatus() {
            fetch(API_BASE_URL + '/status', {
                method: 'GET',
                headers: {
                    'X-API-Key': 'test-api-key'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('API Status:', data);
                if (data.success) {
                    const statusMsg = `API 서버: ${data.components.api_server}\n` +
                                    `얼굴 인식 서버: ${data.components.face_recognition_server}\n` +
                                    `데이터베이스: ${data.components.database}`;
                    showResult('authResult', 'API 상태 확인 완료\n' + statusMsg, 'success');
                } else {
                    showResult('authResult', 'API 상태 확인 실패', 'error');
                }
            })
            .catch(error => {
                showResult('authResult', 'API 서버에 연결할 수 없습니다: ' + error.message, 'error');
                console.error('API URL:', API_BASE_URL);
            });
        }
        
        // 카메라 시작
        function startCamera() {
            const video = document.getElementById('video');
            
            navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                } 
            })
            .then(function(s) {
                stream = s;
                video.srcObject = stream;
                showResult('authResult', '카메라가 시작되었습니다.', 'success');
            })
            .catch(function(err) {
                showResult('authResult', '카메라 접근 실패: ' + err.message, 'error');
            });
        }
        
        // 카메라 중지
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
                document.getElementById('video').srcObject = null;
                showResult('authResult', '카메라가 중지되었습니다.', 'success');
            }
        }
        
        // 얼굴 촬영 및 인증
        function captureAndAuth() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0);
            
            // Base64로 변환
            const imageData = canvas.toDataURL('image/jpeg', 0.8).split(',')[1];
            
            // API 호출
            const requestData = {
                image: imageData,
                device_id: document.getElementById('deviceId').value,
                location: document.getElementById('location').value,
                check_liveness: true,
                language: document.getElementById('language').value
            };
            
            showResult('authResult', '인증 요청 중...', 'warning');
            
            fetch(API_BASE_URL + '/face-auth', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-API-Key': 'test-api-key' // 실제 API 키로 교체
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                
                if (data.success) {
                    showResult('authResult', JSON.stringify(data, null, 2), 'success');
                    
                    // 이용권이 있으면 표시
                    if (data.data && data.data.tickets && data.data.tickets.length > 0) {
                        selectedMember = data.data.member;
                        displayTickets(data.data.tickets);
                    }
                } else {
                    showResult('authResult', JSON.stringify(data, null, 2), 'error');
                }
            })
            .catch(error => {
                showResult('authResult', 'API 호출 실패: ' + error.message, 'error');
            });
        }
        
        // 이용권 목록 표시
        function displayTickets(tickets) {
            const ticketList = document.getElementById('ticketList');
            ticketList.innerHTML = '<h3>이용 가능한 이용권:</h3>';
            
            tickets.forEach((ticket, index) => {
                const ticketDiv = document.createElement('div');
                ticketDiv.className = 'ticket-item';
                ticketDiv.innerHTML = `
                    <strong>${ticket.ticket_name}</strong><br>
                    ${ticket.remaining_info} | 만료일: ${ticket.expire_date}<br>
                    상태: ${ticket.is_available ? '입장 가능' : '입장 불가'}
                `;
                
                if (ticket.is_available) {
                    ticketDiv.onclick = () => selectTicket(ticket, ticketDiv);
                } else {
                    ticketDiv.style.opacity = '0.5';
                    ticketDiv.style.cursor = 'not-allowed';
                }
                
                ticketList.appendChild(ticketDiv);
            });
            
            document.getElementById('checkinBtn').disabled = false;
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
        
        // 체크인 처리
        function processCheckin() {
            if (!selectedMember || !selectedTicket) {
                showResult('checkinResult', '회원 정보와 이용권을 선택해주세요.', 'error');
                return;
            }
            
            const requestData = {
                mem_sno: selectedMember.mem_sno,
                ticket_id: selectedTicket.ticket_id,
                device_id: document.getElementById('deviceId').value,
                location: document.getElementById('location').value,
                language: document.getElementById('language').value
            };
            
            showResult('checkinResult', '체크인 처리 중...', 'warning');
            
            fetch(API_BASE_URL + '/checkin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-API-Key': 'test-api-key' // 실제 API 키로 교체
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Checkin Response:', data);
                
                if (data.success) {
                    showResult('checkinResult', JSON.stringify(data, null, 2), 'success');
                } else {
                    showResult('checkinResult', JSON.stringify(data, null, 2), 'error');
                }
            })
            .catch(error => {
                showResult('checkinResult', 'API 호출 실패: ' + error.message, 'error');
            });
        }
        
        // 시나리오 테스트 함수들
        function testNoFace() {
            const requestData = {
                image: "invalid_base64_data", // 잘못된 이미지
                device_id: "KIOSK_TEST",
                language: document.getElementById('language').value
            };
            
            callAPI('/face-auth', requestData, 'scenarioResult');
        }
        
        function testSecurityFail() {
            // 실제 구현 시 보안 검증 실패를 유발하는 이미지 사용
            showResult('scenarioResult', '보안 검증 실패 시나리오는 실제 화면/사진 이미지가 필요합니다.', 'warning');
        }
        
        function testMemberNotFound() {
            // 등록되지 않은 얼굴 이미지 필요
            showResult('scenarioResult', '회원 미발견 시나리오는 등록되지 않은 얼굴 이미지가 필요합니다.', 'warning');
        }
        
        function testNoTickets() {
            // 이용권이 없는 회원의 얼굴 이미지 필요
            showResult('scenarioResult', '이용권 없음 시나리오는 이용권이 없는 회원의 얼굴 이미지가 필요합니다.', 'warning');
        }
        
        // API 호출 헬퍼 함수
        function callAPI(endpoint, data, resultId) {
            fetch(API_BASE_URL + endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-API-Key': 'test-api-key'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                showResult(resultId, JSON.stringify(data, null, 2), data.success ? 'success' : 'error');
            })
            .catch(error => {
                showResult(resultId, 'API 호출 실패: ' + error.message, 'error');
            });
        }
        
        // 결과 표시 함수
        function showResult(elementId, message, type) {
            const element = document.getElementById(elementId);
            element.textContent = message;
            element.className = 'result ' + type;
        }
    </script>
</body>
</html>