<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? '토스페이먼츠 결제 완료' ?> - SpoqPlus</title>
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
            background: linear-gradient(135deg, #3182f6 0%, #1b64da 100%);
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
        .success-icon {
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
        .payment-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #3182f6;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
        }
        .info-value {
            font-weight: 500;
            text-align: right;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #3182f6;
        }
        .payment-method {
            background: #e8f5e8;
            color: #28a745;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .toss-badge {
            background: #3182f6;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .receipt-section {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .receipt-header {
            font-weight: 600;
            margin-bottom: 15px;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .receipt-link {
            display: inline-block;
            background: #3182f6;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .receipt-link:hover {
            background: #1b64da;
            text-decoration: none;
            color: white;
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
        .footer {
            background: #f8f9fa;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #6c757d;
        }
        .security-info {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .security-info h4 {
            margin: 0 0 8px 0;
            color: #1976d2;
            font-size: 14px;
        }
        .security-info p {
            margin: 0;
            font-size: 12px;
            color: #666;
            line-height: 1.4;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="toss-logo">토스페이먼츠</div>
            <div class="success-icon">✅</div>
            <h1>결제가 완료되었습니다</h1>
            <p>토스페이먼츠를 통한 결제가 정상적으로 완료되었습니다.</p>
        </div>
        
        <div class="content">
            <div class="payment-info">
                <div class="info-row">
                    <span class="info-label">주문번호</span>
                    <span class="info-value"><?= esc($payment_result['orderId'] ?? 'N/A') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">결제키</span>
                    <span class="info-value"><?= esc(substr($payment_result['paymentKey'] ?? 'N/A', 0, 20) . '...') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">상품명</span>
                    <span class="info-value"><?= esc($payment_result['orderName'] ?? $payment_result['goodname'] ?? 'N/A') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">결제방법</span>
                    <span class="info-value">
                        <span class="payment-method">
                            <?php
                            $method = $payment_result['method'] ?? 'N/A';
                            switch($method) {
                                case 'CARD': echo '신용카드'; break;
                                case '카드': echo '신용카드'; break;
                                case 'TRANSFER': echo '계좌이체'; break;
                                case 'VIRTUAL_ACCOUNT': echo '가상계좌'; break;
                                case 'MOBILE_PHONE': echo '휴대폰결제'; break;
                                case 'GIFT_CERTIFICATE': echo '상품권'; break;
                                case 'EASY_PAY': echo '간편결제'; break;
                                default: echo $method;
                            }
                            ?>
                        </span>
                        <span class="toss-badge">TOSS</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">결제금액</span>
                    <span class="info-value amount"><?= number_format($payment_result['totalAmount'] ?? $payment_result['amount'] ?? 0) ?>원</span>
                </div>
                <div class="info-row">
                    <span class="info-label">결제일시</span>
                    <span class="info-value">
                        <?php
                        $approvedAt = $payment_result['approvedAt'] ?? $payment_result['payment_date'] ?? 'now';
                        echo date('Y-m-d H:i:s', strtotime($approvedAt));
                        ?>
                    </span>
                </div>
                <?php if (isset($payment_result['card']) && is_array($payment_result['card'])): ?>
                <div class="info-row">
                    <span class="info-label">카드정보</span>
                    <span class="info-value">
                        <?= esc($payment_result['card']['company'] ?? '') ?> 
                        (<?= esc($payment_result['card']['number'] ?? '') ?>)
                    </span>
                </div>
                <?php endif; ?>
                <?php if (isset($payment_result['virtualAccount']) && is_array($payment_result['virtualAccount'])): ?>
                <div class="info-row">
                    <span class="info-label">가상계좌</span>
                    <span class="info-value">
                        <?= esc($payment_result['virtualAccount']['bankCode'] ?? '') ?> 
                        <?= esc($payment_result['virtualAccount']['accountNumber'] ?? '') ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">입금기한</span>
                    <span class="info-value"><?= esc($payment_result['virtualAccount']['dueDate'] ?? '') ?></span>
                </div>
                <?php endif; ?>
            </div>

            <?php if (isset($payment_result['receipt']) && !empty($payment_result['receipt']['url'])): ?>
            <div class="receipt-section">
                <div class="receipt-header">
                    📄 결제 영수증
                </div>
                <p style="margin-bottom: 15px; font-size: 14px; color: #666;">
                    결제 영수증을 확인하실 수 있습니다.
                </p>
                <a href="<?= esc($payment_result['receipt']['url']) ?>" target="_blank" class="receipt-link">
                    영수증 보기
                </a>
            </div>
            <?php endif; ?>

            <div class="security-info">
                <h4>🔒 보안 결제 완료</h4>
                <p>
                    이 결제는 토스페이먼츠의 보안 시스템을 통해 안전하게 처리되었습니다.<br>
                    결제 정보는 PCI DSS 표준에 따라 암호화되어 보호됩니다.
                </p>
            </div>
            
            <div class="button-group">
                <a href="/" class="btn btn-primary">홈으로 가기</a>
                <a href="/my/orders" class="btn btn-secondary">주문내역 보기</a>
            </div>
        </div>
        
        <div class="footer">
            <p>결제 관련 문의사항이 있으시면 고객센터로 연락해 주세요.</p>
            <p>SpoqPlus 고객센터: 1588-0000 | 토스페이먼츠 고객센터: 1544-7772</p>
        </div>
    </div>

    <script>
        // 결제 완료 후 브라우저 뒤로가기 방지
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
        
        // 결제 완료 이벤트 추적 (Google Analytics 등)
        if (typeof gtag !== 'undefined') {
            gtag('event', 'purchase', {
                'transaction_id': '<?= esc($payment_result['orderId'] ?? '') ?>',
                'value': <?= (int)($payment_result['totalAmount'] ?? $payment_result['amount'] ?? 0) ?>,
                'currency': 'KRW',
                'payment_method': 'toss_payments'
            });
        }
        
        // 결제 성공 알림 (선택사항)
        // setTimeout(function() {
        //     if (confirm('결제가 완료되었습니다. 주문내역을 확인하시겠습니까?')) {
        //         window.location.href = '/my/orders';
        //     }
        // }, 3000);
    </script>
</body>
</html>