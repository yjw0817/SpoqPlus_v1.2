<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>체크인 얼굴 인식 API 테스트</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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
            text-align: center;
            margin-bottom: 30px;
        }
        .test-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fafafa;
        }
        .test-section h3 {
            margin-top: 0;
            color: #555;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
        }
        button:hover {
            background: #0056b3;
        }
        .result {
            margin-top: 15px;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        #video {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            margin: 10px 0;
        }
        #canvas {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🤖 체크인 얼굴 인식 API 테스트</h1>
        
        <!-- API 헬스체크 -->
        <div class="test-section">
            <h3>1. API 서버 상태 확인</h3>
            <button onclick="testHealthCheck()">헬스체크 실행</button>
            <div id="healthResult" class="result"></div>
        </div>

        <!-- 얼굴 인식 서버 상태 확인 -->
        <div class="test-section">
            <h3>2. Python 얼굴 인식 서버 상태</h3>
            <button onclick="testPythonServer()">Python 서버 상태 확인</button>
            <div id="pythonResult" class="result"></div>
        </div>

        <!-- 카메라 테스트 -->
        <div class="test-section">
            <h3>3. 카메라 및 얼굴 인식 테스트</h3>
            <button onclick="startCamera()">카메라 시작</button>
            <button onclick="captureAndRecognize()">얼굴 인식 실행</button>
            <button onclick="stopCamera()">카메라 정지</button>
            <br>
            <video id="video" autoplay></video>
            <canvas id="canvas"></canvas>
            <div id="cameraResult" class="result"></div>
        </div>

        <!-- API 직접 테스트 -->
        <div class="test-section">
            <h3>4. API 직접 호출 테스트</h3>
            <button onclick="testRecognizeAPI()">더미 데이터로 API 테스트</button>
            <div id="apiResult" class="result"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let stream = null;
        let video = document.getElementById('video');
        let canvas = document.getElementById('canvas');
        let ctx = canvas.getContext('2d');

        // 1. API 헬스체크
        function testHealthCheck() {
            showResult('healthResult', '헬스체크 실행 중...', 'info');
            
            $.ajax({
                url: '/api/face/health',
                type: 'GET',
                success: function(result) {
                    showResult('healthResult', `✅ 헬스체크 성공\n${JSON.stringify(result, null, 2)}`, 'success');
                },
                error: function(xhr, status, error) {
                    showResult('healthResult', `❌ 헬스체크 실패\nStatus: ${xhr.status}\nError: ${error}\nResponse: ${xhr.responseText}`, 'error');
                }
            });
        }

        // 2. Python 서버 상태 확인
        function testPythonServer() {
            showResult('pythonResult', 'Python 서버 상태 확인 중...', 'info');
            
            // Python 서버에 직접 요청
            $.ajax({
                url: 'http://localhost:5001',
                type: 'GET',
                success: function(result) {
                    showResult('pythonResult', `✅ Python 서버 연결 성공\n${JSON.stringify(result, null, 2)}`, 'success');
                },
                error: function(xhr, status, error) {
                    showResult('pythonResult', `❌ Python 서버 연결 실패\n포트 5001에서 face_recognition 서버가 실행되고 있는지 확인해주세요.\nError: ${error}`, 'error');
                }
            });
        }

        // 3. 카메라 시작
        function startCamera() {
            navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: 'user',
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                } 
            })
            .then(function(mediaStream) {
                stream = mediaStream;
                video.srcObject = stream;
                showResult('cameraResult', '✅ 카메라 시작됨', 'success');
            })
            .catch(function(err) {
                showResult('cameraResult', `❌ 카메라 접근 실패: ${err.message}`, 'error');
            });
        }

        // 4. 카메라 정지
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                video.srcObject = null;
                stream = null;
                showResult('cameraResult', '⏹️ 카메라 정지됨', 'info');
            }
        }

        // 5. 얼굴 촬영 및 인식
        function captureAndRecognize() {
            if (!stream) {
                showResult('cameraResult', '❌ 먼저 카메라를 시작해주세요', 'error');
                return;
            }

            showResult('cameraResult', '📸 얼굴 촬영 및 인식 중...', 'info');

            // 캔버스에 현재 프레임 그리기
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0);

            // 이미지를 Blob으로 변환
            canvas.toBlob(function(blob) {
                const formData = new FormData();
                formData.append('face_image', blob, 'test_capture.jpg');

                $.ajax({
                    url: '/api/face/recognize',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        if (typeof result === 'string' && result.trim().startsWith('<')) {
                            showResult('cameraResult', `❌ HTML 응답 수신 (로그인 문제)\n${result.substring(0, 200)}...`, 'error');
                        } else {
                            showResult('cameraResult', `✅ 얼굴 인식 응답\n${JSON.stringify(result, null, 2)}`, 'success');
                        }
                    },
                    error: function(xhr, status, error) {
                        showResult('cameraResult', `❌ 얼굴 인식 API 오류\nStatus: ${xhr.status}\nError: ${error}\nResponse: ${xhr.responseText}`, 'error');
                    }
                });
            }, 'image/jpeg', 0.8);
        }

        // 6. API 직접 테스트
        function testRecognizeAPI() {
            showResult('apiResult', 'API 직접 테스트 중...', 'info');

            // 1x1 픽셀 더미 이미지 생성
            const dummyCanvas = document.createElement('canvas');
            dummyCanvas.width = 1;
            dummyCanvas.height = 1;
            const dummyCtx = dummyCanvas.getContext('2d');
            dummyCtx.fillStyle = '#000000';
            dummyCtx.fillRect(0, 0, 1, 1);

            dummyCanvas.toBlob(function(blob) {
                const formData = new FormData();
                formData.append('face_image', blob, 'dummy.jpg');

                $.ajax({
                    url: '/api/face/recognize',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        if (typeof result === 'string' && result.trim().startsWith('<')) {
                            showResult('apiResult', `❌ HTML 응답 수신 (인증/라우팅 문제)\n응답 시작: ${result.substring(0, 100)}...`, 'error');
                        } else {
                            showResult('apiResult', `✅ API 정상 응답 (더미 데이터)\n${JSON.stringify(result, null, 2)}`, 'success');
                        }
                    },
                    error: function(xhr, status, error) {
                        showResult('apiResult', `❌ API 호출 실패\nStatus: ${xhr.status}\nError: ${error}\nResponse: ${xhr.responseText}`, 'error');
                    }
                });
            }, 'image/jpeg');
        }

        // 결과 표시 함수
        function showResult(elementId, message, type) {
            const element = document.getElementById(elementId);
            element.className = `result ${type}`;
            element.textContent = message;
        }

        // 페이지 로드 시 자동으로 헬스체크 실행
        $(document).ready(function() {
            testHealthCheck();
        });
    </script>
</body>
</html> 