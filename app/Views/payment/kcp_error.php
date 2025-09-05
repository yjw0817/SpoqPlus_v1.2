<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'KCP 결제 오류' ?> - SpoqPlus</title>
    <style>
        body {
            font-family: 'Noto Sans KR', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            text-align: center;
            padding: 40px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .error-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        .pg-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }
        .content {
            padding: 40px;
        }
        .error-info {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            color: #721c24;
        }
        .error-message {
            font-size: 16px;
            line-height: 1.5;
            margin: 0;
        }
        .kcp-error-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            color: #856404;
        }
        .help-section {
            background: #e2e3e5;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .help-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: #495057;
        }
        .help-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .help-list li {
            padding: 8px 0;
            border-bottom: 1px solid #d6d8db;
        }
        .help-list li:last-child {
            border-bottom: none;
        }
        .help-list li:before {
            content: "• ";
            color: #6c757d;
            font-weight: bold;
        }
        .kcp-specific-help {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .kcp-specific-help .help-title {
            color: #0c5460;
            margin-bottom: 10px;
        }
        .button-group {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #545b62;
            transform: translateY(-2px);
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
        .btn-info {
            background: #17a2b8;
            color: white;
        }
        .btn-info:hover {
            background: #138496;
            transform: translateY(-2px);
        }
        .footer {
            background: #f8f9fa;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #6c757d;
        }
        .contact-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .contact-info strong {
            color: #0c5460;
        }
        @media (max-width: 600px) {
            .container {
                margin: 20px;
                border-radius: 0;
            }
            .content {
                padding: 20px;
            }
            .btn {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="error-icon">❌</div>
            <h1>KCP 결제 처리 중 오류가 발생했습니다</h1>
            <p>KCP를 통한 결제가 정상적으로 완료되지 않았습니다.</p>
            <div class="pg-badge">KCP 안전결제</div>
        </div>
        
        <div class="content">
            <div class="error-info">
                <p class="error-message">
                    <strong>오류 내용:</strong><br>
                    <?= esc($error_message ?? 'KCP 결제 처리 중 알 수 없는 오류가 발생했습니다.') ?>
                </p>
            </div>
            
            <div class="kcp-error-section">
                <strong>KCP 결제 시스템 관련 안내</strong><br>
                본 오류는 KCP(한국사이버결제) 결제 시스템과의 통신 중 발생한 문제일 수 있습니다.<br>
                잠시 후 다시 시도해 주시거나, 다른 결제 수단을 이용해 주세요.
            </div>
            
            <div class="help-section">
                <div class="help-title">일반적인 해결방법</div>
                <ul class="help-list">
                    <li>결제 정보를 다시 확인해 주세요.</li>
                    <li>신용카드의 한도나 유효기간을 확인해 주세요.</li>
                    <li>인터넷 연결 상태를 확인해 주세요.</li>
                    <li>잠시 후 다시 시도해 주세요.</li>
                    <li>브라우저를 새로고침하고 다시 시도해 주세요.</li>
                </ul>
            </div>
            
            <div class="kcp-specific-help">
                <div class="help-title">KCP 결제 관련 특별 확인사항</div>
                <ul class="help-list">
                    <li>KCP 안전결제 서비스가 활성화되어 있는지 확인해 주세요.</li>
                    <li>팝업 차단 프로그램이 KCP 결제창을 차단했는지 확인해 주세요.</li>
                    <li>KCP 결제 앱이 최신 버전인지 확인해 주세요. (모바일의 경우)</li>
                    <li>카드사 홈페이지에서 온라인 결제 한도를 확인해 주세요.</li>
                    <li>해외 발급 카드의 경우 국내 온라인 결제가 가능한지 확인해 주세요.</li>
                </ul>
            </div>
            
            <div class="contact-info">
                <strong>고객센터 연락처</strong><br>
                SpoqPlus 고객센터: 1588-0000<br>
                KCP 고객센터: 1588-8661<br>
                운영시간: 평일 09:00 ~ 18:00
            </div>
            
            <div class="button-group">
                <a href="javascript:history.back();" class="btn btn-danger">다시 시도</a>
                <a href="/" class="btn btn-primary">홈으로 가기</a>
                <a href="/customer/support" class="btn btn-secondary">고객센터</a>
                <a href="https://www.kcp.co.kr/customer/" target="_blank" class="btn btn-info">KCP 고객센터</a>
            </div>
        </div>
        
        <div class="footer">
            <p>KCP 결제 중 문제가 발생하신 경우 언제든지 고객센터로 연락해 주세요.</p>
            <p>빠른 해결을 위해 오류 메시지와 결제 시도 시간을 함께 알려주시면 도움이 됩니다.</p>
        </div>
    </div>

    <script>
        // 페이지 로드 시 KCP 에러 로깅
        document.addEventListener('DOMContentLoaded', function() {
            console.error('KCP Payment Error:', <?= json_encode($error_message ?? 'Unknown KCP error') ?>);
            
            // 에러 발생 시간 표시
            const now = new Date();
            const errorTime = now.toLocaleString('ko-KR');
            
            // KCP 에러 정보를 로컬 스토리지에 저장 (디버깅 목적)
            const kcpErrorInfo = {
                message: <?= json_encode($error_message ?? 'Unknown KCP error') ?>,
                timestamp: errorTime,
                url: window.location.href,
                userAgent: navigator.userAgent,
                paymentGateway: 'KCP'
            };
            
            localStorage.setItem('lastKcpPaymentError', JSON.stringify(kcpErrorInfo));
            
            // Google Analytics 이벤트 (있는 경우)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'payment_error', {
                    'payment_method': 'KCP',
                    'error_message': <?= json_encode($error_message ?? 'Unknown KCP error') ?>
                });
            }
        });
        
        // 새로고침 방지 경고
        window.addEventListener('beforeunload', function(e) {
            e.preventDefault();
            e.returnValue = '페이지를 새로고침하시겠습니까? KCP 결제 정보가 손실될 수 있습니다.';
        });
        
        // KCP 고객센터 바로가기 함수
        function openKcpSupport() {
            window.open('https://www.kcp.co.kr/customer/', '_blank');
        }
        
        // 에러 상세 정보 토글 (선택사항)
        function toggleErrorDetails() {
            const details = document.getElementById('error-details');
            if (details) {
                details.style.display = details.style.display === 'none' ? 'block' : 'none';
            }
        }
    </script>
</body>
</html>