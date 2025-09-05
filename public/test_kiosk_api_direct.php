<?php
// 정식 API 직접 테스트

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>API 키 검증 직접 테스트</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .test { margin: 20px 0; padding: 10px; border: 1px solid #ddd; }
        .success { background: #d4edda; }
        .error { background: #f8d7da; }
        pre { background: #f5f5f5; padding: 10px; overflow: auto; }
    </style>
</head>
<body>
    <h1>API 키 검증 테스트</h1>
    
    <div class="test">
        <h2>1. 올바른 API 키로 테스트</h2>
        <button onclick="testValidKey()">테스트 실행</button>
        <div id="result1"></div>
    </div>
    
    <div class="test">
        <h2>2. 잘못된 API 키로 테스트</h2>
        <button onclick="testInvalidKey()">테스트 실행</button>
        <div id="result2"></div>
    </div>
    
    <div class="test">
        <h2>3. API 키 없이 테스트</h2>
        <button onclick="testNoKey()">테스트 실행</button>
        <div id="result3"></div>
    </div>

    <script>
        function testValidKey() {
            fetch('http://localhost:8080/api/v1/kiosk/status', {
                headers: {
                    'X-API-Key': 'test-api-key-dy-cf965b66227831234b86e45f'
                }
            })
            .then(r => {
                document.getElementById('result1').innerHTML = 
                    `<p>HTTP 상태: ${r.status} ${r.statusText}</p>`;
                return r.json();
            })
            .then(data => {
                document.getElementById('result1').innerHTML += 
                    `<pre>${JSON.stringify(data, null, 2)}</pre>`;
                document.getElementById('result1').className = 
                    data.success ? 'success' : 'error';
            })
            .catch(err => {
                document.getElementById('result1').innerHTML = 
                    `<p class="error">에러: ${err.message}</p>`;
            });
        }
        
        function testInvalidKey() {
            fetch('http://localhost:8080/api/v1/kiosk/status', {
                headers: {
                    'X-API-Key': 'invalid-wrong-key-12345'
                }
            })
            .then(r => {
                document.getElementById('result2').innerHTML = 
                    `<p>HTTP 상태: ${r.status} ${r.statusText}</p>`;
                return r.json();
            })
            .then(data => {
                document.getElementById('result2').innerHTML += 
                    `<pre>${JSON.stringify(data, null, 2)}</pre>`;
                document.getElementById('result2').className = 
                    data.success ? 'success' : 'error';
            })
            .catch(err => {
                document.getElementById('result2').innerHTML = 
                    `<p class="error">에러: ${err.message}</p>`;
            });
        }
        
        function testNoKey() {
            fetch('http://localhost:8080/api/v1/kiosk/status')
            .then(r => {
                document.getElementById('result3').innerHTML = 
                    `<p>HTTP 상태: ${r.status} ${r.statusText}</p>`;
                return r.json();
            })
            .then(data => {
                document.getElementById('result3').innerHTML += 
                    `<pre>${JSON.stringify(data, null, 2)}</pre>`;
                document.getElementById('result3').className = 
                    data.success ? 'success' : 'error';
            })
            .catch(err => {
                document.getElementById('result3').innerHTML = 
                    `<p class="error">에러: ${err.message}</p>`;
            });
        }
    </script>
</body>
</html>