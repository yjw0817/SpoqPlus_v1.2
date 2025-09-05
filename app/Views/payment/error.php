<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? '결제 오류' ?> - SpoqPlus</title>
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
            <h1>결제 처리 중 오류가 발생했습니다</h1>
            <p>결제가 정상적으로 완료되지 않았습니다.</p>
        </div>
        
        <div class="content">
            <div class="error-info">
                <p class="error-message">
                    <strong>오류 내용:</strong><br>
                    <?= esc($error_message ?? '알 수 없는 오류가 발생했습니다.') ?>
                </p>
            </div>
            
            <div class="help-section">
                <div class="help-title">가능한 해결방법</div>
                <ul class="help-list">
                    <li>결제 정보를 다시 확인해 주세요.</li>
                    <li>신용카드의 한도나 유효기간을 확인해 주세요.</li>
                    <li>인터넷 연결 상태를 확인해 주세요.</li>
                    <li>잠시 후 다시 시도해 주세요.</li>
                    <li>문제가 지속되면 고객센터로 연락해 주세요.</li>
                </ul>
            </div>
            
            <div class="contact-info">
                <strong>고객센터 연락처</strong><br>
                전화: 1588-0000<br>
                운영시간: 평일 09:00 ~ 18:00
            </div>
            
            <div class="button-group">
                <a href="javascript:history.back();" class="btn btn-danger">다시 시도</a>
                <a href="/" class="btn btn-primary">홈으로 가기</a>
                <a href="/customer/support" class="btn btn-secondary">고객센터</a>
            </div>
        </div>
        
        <div class="footer">
            <p>결제 중 문제가 발생하신 경우 언제든지 고객센터로 연락해 주세요.</p>
            <p>빠른 해결을 위해 오류 메시지를 함께 알려주시면 도움이 됩니다.</p>
        </div>
    </div>

    <script>
        // 페이지 로드 시 에러 로깅
        document.addEventListener('DOMContentLoaded', function() {
            console.error('Payment Error:', <?= json_encode($error_message ?? 'Unknown error') ?>);
            
            // 에러 발생 시간 표시
            const now = new Date();
            const errorTime = now.toLocaleString('ko-KR');
            
            // 에러 정보를 로컬 스토리지에 저장 (디버깅 목적)
            const errorInfo = {
                message: <?= json_encode($error_message ?? 'Unknown error') ?>,
                timestamp: errorTime,
                url: window.location.href,
                userAgent: navigator.userAgent
            };
            
            localStorage.setItem('lastPaymentError', JSON.stringify(errorInfo));
        });
        
        // 새로고침 방지 경고
        window.addEventListener('beforeunload', function(e) {
            e.preventDefault();
            e.returnValue = '페이지를 새로고침하시겠습니까? 결제 정보가 손실될 수 있습니다.';
        });
    </script>
</body>
</html>