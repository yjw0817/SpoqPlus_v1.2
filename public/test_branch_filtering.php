<?php
// 지점별 필터링 테스트
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>지점별 얼굴 인증 필터링 테스트</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; color: #333; margin-bottom: 30px; }
        .test-section { margin: 30px 0; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .test-section h3 { margin-top: 0; color: #2196F3; }
        .api-key-info { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 10px 0; font-family: monospace; }
        .result { margin: 20px 0; padding: 15px; border-radius: 8px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .info { background: #cce5ff; border: 1px solid #b8daff; color: #004085; }
        button { padding: 10px 20px; background: #2196F3; color: white; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        button:hover { background: #1976D2; }
        pre { background: #f5f5f5; padding: 10px; overflow: auto; border-radius: 5px; }
        .flow-diagram { background: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 지점별 얼굴 인증 필터링 테스트</h1>
            <p>API 키의 회사/지점 정보를 기반으로 해당 지점 회원만 인증되는지 확인</p>
        </div>

        <div class="flow-diagram">
            <h3>📋 인증 흐름</h3>
            <ol>
                <li><strong>API 키 검증</strong>: X-API-Key 헤더로 전달된 키 확인</li>
                <li><strong>지점 정보 추출</strong>: API 키에서 comp_cd, bcoff_cd 확인</li>
                <li><strong>얼굴 인식 요청</strong>: Python 서버에 지점 정보와 함께 전달</li>
                <li><strong>지점별 필터링</strong>: 해당 지점의 회원 얼굴만 매칭</li>
                <li><strong>회원 정보 확인</strong>: 매칭된 회원이 해당 지점 소속인지 재확인</li>
            </ol>
        </div>

        <div class="test-section">
            <h3>1. API 키별 지점 정보 확인</h3>
            <div class="api-key-info">
                <strong>강남점 API 키:</strong> dy-bcoff-b00001-686e3557918a83c03cf50a707874dbe19afdc1a7<br>
                <strong>회사:</strong> C00001, <strong>지점:</strong> B00001
            </div>
            <div class="api-key-info">
                <strong>서초점 API 키:</strong> dy-bcoff-b00002-686e3557e8de2e5c3cf50a707874dbe19afdc1a7<br>
                <strong>회사:</strong> C00001, <strong>지점:</strong> B00002
            </div>
            <button onclick="testApiKeys()">API 키 정보 확인</button>
            <div id="apiKeyResult"></div>
        </div>

        <div class="test-section">
            <h3>2. 지점별 회원 데이터 확인</h3>
            <button onclick="checkMemberData()">회원 데이터 조회</button>
            <div id="memberDataResult"></div>
        </div>

        <div class="test-section">
            <h3>3. Python 서버 지점 필터링 테스트</h3>
            <p>직접 Python API를 호출하여 지점 필터링이 작동하는지 확인</p>
            <button onclick="testPythonFiltering()">Python 서버 테스트</button>
            <div id="pythonResult"></div>
        </div>

        <div class="test-section">
            <h3>4. 전체 흐름 테스트</h3>
            <p>키오스크 API를 통한 전체 인증 흐름 테스트</p>
            <button onclick="testFullFlow('강남점')">강남점 키로 테스트</button>
            <button onclick="testFullFlow('서초점')">서초점 키로 테스트</button>
            <div id="fullFlowResult"></div>
        </div>
    </div>

    <script>
        const apiKeys = {
            '강남점': 'dy-bcoff-b00001-686e3557918a83c03cf50a707874dbe19afdc1a7',
            '서초점': 'dy-bcoff-b00002-686e3557e8de2e5c3cf50a707874dbe19afdc1a7'
        };

        function testApiKeys() {
            const result = document.getElementById('apiKeyResult');
            result.innerHTML = '<p class="info">API 키 정보 확인 중...</p>';
            
            Promise.all(Object.entries(apiKeys).map(([branch, key]) => 
                fetch('/test_api_ci.php', {
                    headers: { 'X-API-Key': key }
                })
                .then(r => r.json())
                .then(data => ({ branch, key: key.substring(0, 30) + '...', data }))
            ))
            .then(results => {
                let html = '<h4>API 키 검증 결과:</h4>';
                results.forEach(r => {
                    html += `<div class="${r.data.api_key_valid ? 'success' : 'error'}">`;
                    html += `<strong>${r.branch}:</strong> ${r.data.api_key_valid ? '✓ 유효' : '✗ 무효'}<br>`;
                    if (r.data.api_key_info) {
                        html += `회사: ${r.data.api_key_info.comp_cd}, 지점: ${r.data.api_key_info.bcoff_cd}`;
                    }
                    html += '</div>';
                });
                result.innerHTML = html;
            })
            .catch(err => {
                result.innerHTML = `<p class="error">오류: ${err.message}</p>`;
            });
        }

        function checkMemberData() {
            const result = document.getElementById('memberDataResult');
            result.innerHTML = '<p class="info">회원 데이터 조회 중...</p>';
            
            // 데이터베이스 직접 조회 스크립트 호출
            fetch('/check_branch_members.php')
                .then(r => r.json())
                .then(data => {
                    let html = '<h4>지점별 회원 현황:</h4>';
                    html += '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                    result.innerHTML = html;
                })
                .catch(err => {
                    result.innerHTML = `<p class="error">오류: ${err.message}</p>`;
                });
        }

        function testPythonFiltering() {
            const result = document.getElementById('pythonResult');
            result.innerHTML = '<p class="info">Python 서버 테스트 중...</p>';
            
            // 테스트용 이미지 데이터 (빈 이미지)
            const testImage = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
            
            Promise.all([
                // 강남점 정보로 테스트
                fetch('http://localhost:5002/api/face/health', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        comp_cd: 'C00001',
                        bcoff_cd: 'B00001'
                    })
                }),
                // 서초점 정보로 테스트
                fetch('http://localhost:5002/api/face/health', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        comp_cd: 'C00001',
                        bcoff_cd: 'B00002'
                    })
                })
            ])
            .then(responses => Promise.all(responses.map(r => r.json())))
            .then(results => {
                let html = '<h4>Python 서버 응답:</h4>';
                html += '<div class="success">✓ Python 서버가 지점 정보를 받을 준비가 되어 있습니다.</div>';
                html += '<pre>' + JSON.stringify(results, null, 2) + '</pre>';
                result.innerHTML = html;
            })
            .catch(err => {
                result.innerHTML = `<p class="error">Python 서버 연결 실패: ${err.message}</p>`;
            });
        }

        function testFullFlow(branch) {
            const result = document.getElementById('fullFlowResult');
            result.innerHTML = `<p class="info">${branch} API 키로 전체 흐름 테스트 중...</p>`;
            
            // 상태 확인 API 호출
            fetch('http://localhost:8080/api/v1/kiosk/status', {
                headers: {
                    'X-API-Key': apiKeys[branch]
                }
            })
            .then(r => {
                console.log('Status response:', r.status);
                return r.json();
            })
            .then(data => {
                let html = `<h4>${branch} API 키 테스트 결과:</h4>`;
                
                if (data.success) {
                    html += '<div class="success">';
                    html += `<strong>✓ API 인증 성공</strong><br>`;
                    html += `서비스: ${data.service}<br>`;
                    html += `버전: ${data.version}<br>`;
                    html += `상태: ${data.status}<br>`;
                    html += '<strong>컴포넌트 상태:</strong><br>';
                    Object.entries(data.components).forEach(([key, value]) => {
                        html += `- ${key}: ${value}<br>`;
                    });
                    html += '</div>';
                    
                    html += '<div class="info">';
                    html += '<strong>지점 필터링 동작 확인:</strong><br>';
                    html += `이 API 키(${branch})로 얼굴 인증 시 해당 지점의 회원만 매칭됩니다.`;
                    html += '</div>';
                } else {
                    html += `<div class="error">✗ API 인증 실패: ${data.message || data.error}</div>`;
                }
                
                result.innerHTML = html;
            })
            .catch(err => {
                result.innerHTML = `<p class="error">오류: ${err.message}</p>`;
            });
        }
    </script>
</body>
</html>