<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? '결제 완료' ?> - SpoqPlus</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            <h1>결제가 완료되었습니다</h1>
            <p>결제 처리가 정상적으로 완료되었습니다.</p>
        </div>
        
        <div class="content">
            <div class="payment-info">
                <div class="info-row">
                    <span class="info-label">거래번호</span>
                    <span class="info-value"><?= esc($payment_result['tid'] ?? 'N/A') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">상품명</span>
                    <span class="info-value"><?= esc($payment_result['goodname'] ?? $payment_result['product_name'] ?? 'N/A') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">결제방법</span>
                    <span class="info-value">
                        <?php
                        $method = $payment_result['paymethod'] ?? 'N/A';
                        switch($method) {
                            case 'Card': echo '신용카드'; break;
                            case 'DirectBank': echo '계좌이체'; break;
                            case 'VBank': echo '가상계좌'; break;
                            case 'HPP': echo '휴대폰결제'; break;
                            default: echo $method;
                        }
                        ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">결제금액</span>
                    <span class="info-value amount"><?= number_format($payment_result['amount'] ?? $payment_result['price'] ?? 0) ?>원</span>
                </div>
                <div class="info-row">
                    <span class="info-label">결제일시</span>
                    <span class="info-value"><?= date('Y-m-d H:i:s', strtotime($payment_result['payment_date'] ?? 'now')) ?></span>
                </div>
                <?php if (isset($payment_result['applno']) && !empty($payment_result['applno'])): ?>
                <div class="info-row">
                    <span class="info-label">승인번호</span>
                    <span class="info-value"><?= esc($payment_result['applno']) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="button-group">
                <a href="/" class="btn btn-primary">홈으로 가기</a>
                <a href="/my/orders" class="btn btn-secondary">주문내역 보기</a>
            </div>
        </div>
        
        <div class="footer">
            <p>결제 관련 문의사항이 있으시면 고객센터로 연락해 주세요.</p>
            <p>SpoqPlus 고객센터: 1588-0000</p>
        </div>
    </div>

    <script>
        // 결제 완료 후 브라우저 뒤로가기 방지
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
        
        // 5초 후 자동으로 홈페이지로 이동 (선택사항)
        // setTimeout(function() {
        //     if (confirm('5초 후 홈페이지로 이동합니다. 계속 하시겠습니까?')) {
        //         window.location.href = '/';
        //     }
        // }, 5000);
    </script>
</body>
</html>