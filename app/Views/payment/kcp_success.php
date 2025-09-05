<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'KCP 결제 완료' ?> - SpoqPlus</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            text-align: center;
            padding: 40px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .success-icon {
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
        .payment-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
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
            color: #28a745;
        }
        .kcp-info {
            background: #e8f5e8;
            border: 1px solid #c3e6c3;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .kcp-info strong {
            color: #155724;
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
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .footer {
            background: #f8f9fa;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #6c757d;
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
            <div class="success-icon">✅</div>
            <h1>KCP 결제가 완료되었습니다</h1>
            <p>KCP를 통한 결제 처리가 정상적으로 완료되었습니다.</p>
            <div class="pg-badge">KCP 안전결제</div>
        </div>
        
        <div class="content">
            <div class="payment-info">
                <div class="info-row">
                    <span class="info-label">주문번호</span>
                    <span class="info-value"><?= esc($payment_result['ordr_idxx'] ?? $payment_result['tid'] ?? 'N/A') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">상품명</span>
                    <span class="info-value"><?= esc($payment_result['good_name'] ?? $payment_result['product_name'] ?? 'N/A') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">결제방법</span>
                    <span class="info-value">
                        <?php
                        $method = $payment_result['pay_method'] ?? $payment_result['paymethod'] ?? 'N/A';
                        switch($method) {
                            case '100000000000': echo 'KCP 신용카드'; break;
                            case '010000000000': echo 'KCP 계좌이체'; break;
                            case '001000000000': echo 'KCP 가상계좌'; break;
                            case '000100000000': echo 'KCP 휴대폰결제'; break;
                            default: 
                                // 카드명이 있는 경우 표시
                                if (!empty($payment_result['card_name'])) {
                                    echo 'KCP ' . esc($payment_result['card_name']) . ' 카드';
                                } else {
                                    echo 'KCP 결제';
                                }
                        }
                        ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">결제금액</span>
                    <span class="info-value amount"><?= number_format($payment_result['good_mny'] ?? $payment_result['amount'] ?? 0) ?>원</span>
                </div>
                <div class="info-row">
                    <span class="info-label">결제일시</span>
                    <span class="info-value"><?= date('Y-m-d H:i:s', strtotime($payment_result['tran_date'] ?? 'now')) ?></span>
                </div>
                <?php if (isset($payment_result['app_no']) && !empty($payment_result['app_no'])): ?>
                <div class="info-row">
                    <span class="info-label">승인번호</span>
                    <span class="info-value"><?= esc($payment_result['app_no']) ?></span>
                </div>
                <?php endif; ?>
                <?php if (isset($payment_result['card_cd']) && !empty($payment_result['card_cd'])): ?>
                <div class="info-row">
                    <span class="info-label">카드코드</span>
                    <span class="info-value"><?= esc($payment_result['card_cd']) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="kcp-info">
                <strong>KCP 안전결제 서비스</strong><br>
                본 결제는 KCP(한국사이버결제)의 안전결제 시스템을 통해 처리되었습니다.<br>
                결제 정보는 강력한 암호화 기술로 보호됩니다.
            </div>
            
            <div class="button-group">
                <a href="/" class="btn btn-primary">홈으로 가기</a>
                <a href="/my/orders" class="btn btn-secondary">주문내역 보기</a>
                <a href="/payment/receipt/kcp/<?= esc($payment_result['ordr_idxx'] ?? '') ?>" class="btn btn-success">영수증 출력</a>
            </div>
        </div>
        
        <div class="footer">
            <p>KCP 결제 관련 문의사항이 있으시면 고객센터로 연락해 주세요.</p>
            <p>SpoqPlus 고객센터: 1588-0000 | KCP 고객센터: 1588-8661</p>
        </div>
    </div>

    <script>
        // 결제 완료 후 브라우저 뒤로가기 방지
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
        
        // KCP 결제 완료 이벤트 로깅
        if (typeof gtag !== 'undefined') {
            gtag('event', 'purchase', {
                'transaction_id': '<?= esc($payment_result['ordr_idxx'] ?? '') ?>',
                'value': <?= intval($payment_result['good_mny'] ?? 0) ?>,
                'currency': 'KRW',
                'payment_method': 'KCP'
            });
        }
        
        // 결제 완료 확인 메시지 (선택사항)
        document.addEventListener('DOMContentLoaded', function() {
            console.log('KCP 결제 완료:', {
                order_id: '<?= esc($payment_result['ordr_idxx'] ?? '') ?>',
                amount: <?= intval($payment_result['good_mny'] ?? 0) ?>,
                timestamp: '<?= date('Y-m-d H:i:s') ?>'
            });
        });
    </script>
</body>
</html>