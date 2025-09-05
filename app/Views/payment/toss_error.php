<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? '토스페이먼츠 결제 오류' ?> - SpoqPlus</title>
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
            position: relative;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="30" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="60" cy="70" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="30" cy="80" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>');
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        .error-icon {
            font-size: 64px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        .toss-logo {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.2);
            padding: 8px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            z-index: 1;
        }
        .content {
            padding: 40px;
        }
        .error-info {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .error-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
            min-width: 100px;
        }
        .info-value {
            font-weight: 500;
            text-align: right;
            flex: 1;
            word-break: break-word;
        }
        .error-code {
            background: #dc3545;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .toss-badge {
            background: #3182f6;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .help-section {
            background: #e8f4fd;
            border-left: 4px solid #3182f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .help-section h4 {
            margin: 0 0 15px 0;
            color: #1976d2;
            font-size: 16px;
        }
        .help-list {
            margin: 0;
            padding-left: 20px;
        }
        .help-list li {
            margin-bottom: 8px;
            line-height: 1.5;
            color: #495057;
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
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        .btn-primary {
            background: #3182f6;
            color: white;
        }
        .btn-primary:hover {
            background: #1b64da;
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #545b62;
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }
        .btn-retry {
            background: #28a745;
            color: white;
        }
        .btn-retry:hover {
            background: #218838;
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }
        .footer {
            background: #f8f9fa;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #6c757d;
        }
        .contact-info {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .contact-info strong {
            color: #533f03;
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
            .toss-logo {
                position: static;
                display: inline-block;
                margin-top: 10px;
            }
            .info-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .info-value {
                text-align: left;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="toss-logo">토스페이먼츠</div>
            <div class="error-icon">❌</div>
            <h1>결제 처리 중 오류가 발생했습니다</h1>
            <p>토스페이먼츠 결제 처리 과정에서 문제가 발생했습니다.</p>
        </div>
        
        <div class="content">
            <div class="error-info">
                <h3 style="margin-top: 0; color: #dc3545;">오류 정보</h3>
                <p style="margin-bottom: 0; line-height: 1.6;">
                    <strong><?= esc($error_message) ?></strong>
                </p>
                <?php if (isset($error_code) && !empty($error_code)): ?>
                <p style="margin-top: 15px; margin-bottom: 0;">
                    <span class="error-code"><?= esc($error_code) ?></span>
                    <span class="toss-badge">TOSS</span>
                </p>
                <?php endif; ?>
            </div>

            <?php if (isset($error_code) && !empty($error_code)): ?>
            <div class="error-details">
                <div class="info-row">
                    <span class="info-label">오류 코드</span>
                    <span class="info-value"><?= esc($error_code) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">오류 메시지</span>
                    <span class="info-value"><?= esc($error_message) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">발생 시간</span>
                    <span class="info-value"><?= date('Y-m-d H:i:s') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">결제 서비스</span>
                    <span class="info-value">토스페이먼츠</span>
                </div>
            </div>
            <?php endif; ?>

            <div class="help-section">
                <h4>💡 해결 방법</h4>
                <ul class="help-list">
                    <?php
                    $errorCode = $error_code ?? '';
                    switch($errorCode) {
                        case 'PAY_PROCESS_CANCELED':
                            echo '<li>결제 과정에서 취소되었습니다. 다시 시도해 주세요.</li>';
                            echo '<li>결제 정보를 다시 확인하고 재시도해 주세요.</li>';
                            break;
                        case 'PAY_PROCESS_ABORTED':
                            echo '<li>결제가 중단되었습니다. 네트워크 상태를 확인해 주세요.</li>';
                            echo '<li>브라우저를 새로고침 후 다시 시도해 주세요.</li>';
                            break;
                        case 'REJECT_CARD_COMPANY':
                            echo '<li>카드사에서 결제를 거부했습니다.</li>';
                            echo '<li>다른 카드로 시도하거나 카드사에 문의해 주세요.</li>';
                            break;
                        case 'INSUFFICIENT_FUNDS':
                            echo '<li>잔액이 부족합니다. 계좌 잔액을 확인해 주세요.</li>';
                            echo '<li>다른 결제수단을 이용해 주세요.</li>';
                            break;
                        case 'INVALID_CARD':
                            echo '<li>유효하지 않은 카드입니다.</li>';
                            echo '<li>카드 정보를 다시 확인하고 입력해 주세요.</li>';
                            break;
                        case 'EXCEED_MAX_DAILY_PAYMENT_COUNT':
                            echo '<li>일일 결제 한도를 초과했습니다.</li>';
                            echo '<li>내일 다시 시도하거나 다른 결제수단을 이용해 주세요.</li>';
                            break;
                        default:
                            echo '<li>브라우저를 새로고침 후 다시 시도해 주세요.</li>';
                            echo '<li>다른 결제수단을 선택해 보세요.</li>';
                            echo '<li>결제 정보를 다시 확인해 주세요.</li>';
                            echo '<li>문제가 지속되면 고객센터로 문의해 주세요.</li>';
                    }
                    ?>
                </ul>
            </div>

            <div class="contact-info">
                <strong>📞 고객 지원</strong><br>
                결제 관련 문의: SpoqPlus 고객센터 1588-0000<br>
                토스페이먼츠 관련 문의: 토스페이먼츠 고객센터 1544-7772
            </div>
            
            <div class="button-group">
                <a href="javascript:history.back()" class="btn btn-retry">다시 시도</a>
                <a href="/" class="btn btn-primary">홈으로 가기</a>
                <a href="/contact" class="btn btn-secondary">고객센터 문의</a>
            </div>
        </div>
        
        <div class="footer">
            <p>결제 처리 중 발생한 문제에 대해 사과드립니다.</p>
            <p>추가 도움이 필요하시면 언제든지 고객센터로 연락해 주세요.</p>
        </div>
    </div>

    <script>
        // 오류 발생 이벤트 추적 (Google Analytics 등)
        if (typeof gtag !== 'undefined') {
            gtag('event', 'payment_error', {
                'error_code': '<?= esc($error_code ?? 'UNKNOWN') ?>',
                'error_message': '<?= esc(substr($error_message, 0, 100)) ?>',
                'payment_method': 'toss_payments'
            });
        }
        
        // 페이지 로드 시 오류 정보 로깅
        console.log('토스페이먼츠 결제 오류:', {
            code: '<?= esc($error_code ?? 'UNKNOWN') ?>',
            message: '<?= esc($error_message) ?>',
            timestamp: '<?= date('Y-m-d H:i:s') ?>'
        });
        
        // 사용자 행동 추적
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-retry')) {
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'payment_retry_attempt', {
                        'payment_method': 'toss_payments'
                    });
                }
            }
        });
        
        // 자동 새로고침 방지 (사용자가 의도적으로 새로고침할 수 있도록)
        let refreshWarningShown = false;
        window.addEventListener('beforeunload', function(e) {
            if (!refreshWarningShown) {
                refreshWarningShown = true;
                e.preventDefault();
                e.returnValue = '페이지를 새로고침하면 결제를 다시 시도할 수 있습니다.';
            }
        });
    </script>
</body>
</html>