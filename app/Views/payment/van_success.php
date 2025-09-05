<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'VAN 결제 완료' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .payment-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .payment-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .van-provider-badge {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .van-provider-badge.kicc { background: #e74c3c; }
        .van-provider-badge.nice { background: #f39c12; }
        .van-provider-badge.ksnet { background: #2ecc71; }
        .payment-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
        }
        .info-value {
            font-weight: 500;
            color: #495057;
        }
        .amount-highlight {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .btn-home {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            color: white;
        }
        .terminal-info {
            background: #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            font-size: 13px;
            color: #6c757d;
        }
        .qr-section {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .receipt-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-receipt {
            background: #17a2b8;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .btn-receipt:hover {
            background: #138496;
            color: white;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-card">
            <i class="fas fa-check-circle success-icon"></i>
            
            <div class="van-provider-badge <?= strtolower($van_provider ?? 'van') ?>">
                <?= $van_provider ?? 'VAN' ?> 결제시스템
            </div>
            
            <h2 class="mb-4">결제가 완료되었습니다!</h2>
            <p class="text-muted mb-4">VAN 터미널을 통한 결제가 성공적으로 처리되었습니다.</p>
            
            <div class="payment-info">
                <div class="info-row">
                    <span class="info-label">결제 금액</span>
                    <span class="info-value amount-highlight">
                        <?= number_format($payment_result['amount'] ?? 0) ?>원
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">상품명</span>
                    <span class="info-value"><?= esc($payment_result['sell_event_nm'] ?? 'N/A') ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">결제 일시</span>
                    <span class="info-value"><?= esc($payment_result['payment_date'] ?? date('Y-m-d H:i:s')) ?></span>
                </div>
                
                <?php if (isset($payment_result['van_txn_id'])): ?>
                <div class="info-row">
                    <span class="info-label">VAN 거래번호</span>
                    <span class="info-value"><?= esc($payment_result['van_txn_id']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (isset($payment_result['van_transaction_seq'])): ?>
                <div class="info-row">
                    <span class="info-label">거래 순번</span>
                    <span class="info-value"><?= esc($payment_result['van_transaction_seq']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (isset($payment_result['van_order_id'])): ?>
                <div class="info-row">
                    <span class="info-label">주문번호</span>
                    <span class="info-value"><?= esc($payment_result['van_order_id']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (isset($payment_result['approval_no']) || isset($payment_result['auth_number'])): ?>
                <div class="info-row">
                    <span class="info-label">승인번호</span>
                    <span class="info-value">
                        <?= esc($payment_result['approval_no'] ?? $payment_result['auth_number'] ?? 'N/A') ?>
                    </span>
                </div>
                <?php endif; ?>
                
                <?php if (isset($payment_result['card_no']) || isset($payment_result['card_number'])): ?>
                <div class="info-row">
                    <span class="info-label">카드번호</span>
                    <span class="info-value">
                        <?= esc($payment_result['card_no'] ?? $payment_result['card_number'] ?? 'N/A') ?>
                    </span>
                </div>
                <?php endif; ?>
                
                <?php if (isset($payment_result['card_name'])): ?>
                <div class="info-row">
                    <span class="info-label">카드사</span>
                    <span class="info-value"><?= esc($payment_result['card_name']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (isset($payment_result['install_month']) && $payment_result['install_month'] !== '00'): ?>
                <div class="info-row">
                    <span class="info-label">할부 개월</span>
                    <span class="info-value"><?= esc($payment_result['install_month']) ?>개월</span>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- VAN 터미널 정보 -->
            <div class="terminal-info">
                <strong><i class="fas fa-credit-card me-2"></i>터미널 정보</strong><br>
                
                <?php if (isset($payment_result['terminal_id'])): ?>
                터미널 ID: <?= esc($payment_result['terminal_id']) ?><br>
                <?php endif; ?>
                
                <?php if (isset($payment_result['merchant_no'])): ?>
                가맹점번호: <?= esc($payment_result['merchant_no']) ?><br>
                <?php endif; ?>
                
                <?php if (isset($payment_result['merchant_id'])): ?>
                상점 ID: <?= esc($payment_result['merchant_id']) ?><br>
                <?php endif; ?>
                
                <?php if (isset($payment_result['store_id'])): ?>
                매장 ID: <?= esc($payment_result['store_id']) ?><br>
                <?php endif; ?>
                
                <?php if (isset($payment_result['van_id'])): ?>
                VAN ID: <?= esc($payment_result['van_id']) ?><br>
                <?php endif; ?>
                
                VAN 제공업체: <?= $van_provider ?? 'N/A' ?>
            </div>
            
            <!-- 영수증 관련 기능 -->
            <div class="receipt-actions">
                <button class="btn btn-receipt" onclick="printReceipt()">
                    <i class="fas fa-print me-1"></i>영수증 출력
                </button>
                <button class="btn btn-receipt" onclick="emailReceipt()">
                    <i class="fas fa-envelope me-1"></i>이메일 전송
                </button>
                <button class="btn btn-receipt" onclick="downloadReceipt()">
                    <i class="fas fa-download me-1"></i>영수증 다운로드
                </button>
            </div>
            
            <!-- QR 코드 영역 (선택사항) -->
            <?php if (isset($payment_result['qr_data'])): ?>
            <div class="qr-section">
                <h6><i class="fas fa-qrcode me-2"></i>결제 확인 QR코드</h6>
                <div id="qrcode"></div>
                <small class="text-muted">QR코드를 스캔하여 결제 내역을 확인하세요</small>
            </div>
            <?php endif; ?>
            
            <a href="<?= base_url('/') ?>" class="btn btn-home">
                <i class="fas fa-home me-2"></i>홈으로 돌아가기
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php if (isset($payment_result['qr_data'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script>
        // QR 코드 생성
        QRCode.toCanvas(document.getElementById('qrcode'), '<?= $payment_result['qr_data'] ?>', {
            width: 150,
            height: 150,
            margin: 2,
            color: {
                dark: '#000000',
                light: '#FFFFFF'
            }
        });
    </script>
    <?php endif; ?>
    
    <script>
        // 영수증 출력 기능
        function printReceipt() {
            const printContent = document.querySelector('.payment-card').innerHTML;
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>VAN 결제 영수증</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .payment-info { border: 1px solid #ddd; padding: 15px; margin: 10px 0; }
                        .info-row { display: flex; justify-content: space-between; margin: 5px 0; }
                        .amount-highlight { font-size: 18px; font-weight: bold; }
                        .terminal-info { background: #f5f5f5; padding: 10px; margin: 10px 0; }
                        .success-icon, .btn-home, .receipt-actions, .qr-section { display: none; }
                    </style>
                </head>
                <body>
                    <h2>VAN 결제 영수증</h2>
                    ${printContent}
                    <div style="margin-top: 20px; text-align: center; font-size: 12px; color: #666;">
                        출력일시: ${new Date().toLocaleString('ko-KR')}
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }
        
        // 이메일 전송 기능
        function emailReceipt() {
            alert('영수증 이메일 전송 기능을 준비 중입니다.');
            // TODO: 실제 이메일 전송 API 연동
        }
        
        // 영수증 다운로드 기능
        function downloadReceipt() {
            alert('영수증 다운로드 기능을 준비 중입니다.');
            // TODO: PDF 생성 및 다운로드 기능 구현
        }
        
        // 자동으로 5초 후 홈으로 이동 (선택사항)
        // setTimeout(() => {
        //     if (confirm('5초 후 자동으로 홈으로 이동합니다. 계속 하시겠습니까?')) {
        //         window.location.href = '<?= base_url('/') ?>';
        //     }
        // }, 5000);
    </script>
</body>
</html>