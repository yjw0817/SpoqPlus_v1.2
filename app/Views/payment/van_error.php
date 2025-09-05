<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'VAN 결제 오류' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .payment-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
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
        .error-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        .van-provider-badge {
            display: inline-block;
            background: #dc3545;
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
        .error-info {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        .error-message {
            color: #721c24;
            font-weight: 500;
            margin-bottom: 15px;
            padding: 15px;
            background: #ffffff;
            border-radius: 8px;
            border-left: 4px solid #dc3545;
        }
        .error-details {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            font-size: 14px;
            color: #856404;
        }
        .troubleshooting {
            background: #e2e3e5;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        .troubleshooting h6 {
            color: #495057;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .troubleshooting ul {
            margin-bottom: 0;
            padding-left: 20px;
        }
        .troubleshooting li {
            margin-bottom: 8px;
            color: #6c757d;
        }
        .btn-retry {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            margin: 10px;
            transition: all 0.3s ease;
        }
        .btn-retry:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            color: white;
        }
        .btn-home {
            background: #6c757d;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            margin: 10px;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            color: white;
        }
        .contact-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .contact-info h6 {
            color: #0c5460;
            margin-bottom: 10px;
        }
        .contact-info p {
            color: #0c5460;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .error-code {
            display: inline-block;
            background: #dc3545;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin-left: 10px;
        }
        .van-status {
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 13px;
            color: #6c757d;
        }
        .action-buttons {
            margin-top: 30px;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-card">
            <i class="fas fa-exclamation-triangle error-icon"></i>
            
            <div class="van-provider-badge <?= strtolower($van_provider ?? 'van') ?>">
                <?= $van_provider ?? 'VAN' ?> 결제시스템
            </div>
            
            <h2 class="mb-4">결제 처리 중 오류가 발생했습니다</h2>
            <p class="text-muted mb-4">VAN 터미널 결제 처리 과정에서 문제가 발생했습니다.</p>
            
            <div class="error-info">
                <div class="error-message">
                    <i class="fas fa-times-circle me-2"></i>
                    <strong>오류 내용:</strong>
                    <?= esc($error_message ?? '알 수 없는 오류가 발생했습니다.') ?>
                    
                    <?php if (isset($error_code)): ?>
                    <span class="error-code"><?= esc($error_code) ?></span>
                    <?php endif; ?>
                </div>
                
                <?php if (isset($error_details)): ?>
                <div class="error-details">
                    <strong><i class="fas fa-info-circle me-2"></i>상세 정보:</strong><br>
                    <?= esc($error_details) ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- VAN 시스템 상태 정보 -->
            <div class="van-status">
                <strong><i class="fas fa-server me-2"></i>VAN 시스템 상태</strong><br>
                VAN 제공업체: <?= $van_provider ?? 'N/A' ?><br>
                오류 발생 시간: <?= date('Y-m-d H:i:s') ?><br>
                처리 상태: 결제 실패
            </div>
            
            <!-- 문제 해결 가이드 -->
            <div class="troubleshooting">
                <h6><i class="fas fa-tools me-2"></i>문제 해결 방법</h6>
                <ul>
                    <li>카드가 올바르게 삽입되었는지 확인해주세요</li>
                    <li>카드 한도 및 잔액을 확인해주세요</li>
                    <li>터미널과 네트워크 연결 상태를 확인해주세요</li>
                    <li>결제 금액이 올바른지 다시 한 번 확인해주세요</li>
                    <li>다른 카드로 결제를 시도해보세요</li>
                    <li>문제가 지속되면 고객센터에 문의해주세요</li>
                </ul>
            </div>
            
            <!-- 고객센터 연락처 -->
            <div class="contact-info">
                <h6><i class="fas fa-headset me-2"></i>고객센터 안내</h6>
                <p><strong>전화:</strong> 1588-0000 (24시간 운영)</p>
                <p><strong>이메일:</strong> support@spoqplus.com</p>
                <p><strong>운영시간:</strong> 평일 09:00 ~ 18:00</p>
                <p><strong>VAN 문의:</strong> <?= $van_provider ?? 'VAN' ?> 전용 상담원 연결 가능</p>
            </div>
            
            <!-- 액션 버튼들 -->
            <div class="action-buttons">
                <button class="btn btn-retry" onclick="retryPayment()">
                    <i class="fas fa-redo me-2"></i>결제 다시 시도
                </button>
                
                <button class="btn btn-retry" onclick="selectOtherMethod()">
                    <i class="fas fa-credit-card me-2"></i>다른 결제 방법 선택
                </button>
                
                <a href="<?= base_url('/') ?>" class="btn btn-home">
                    <i class="fas fa-home me-2"></i>홈으로 돌아가기
                </a>
            </div>
            
            <!-- 기술 지원 정보 (개발자용) -->
            <?php if (ENVIRONMENT === 'development' && isset($debug_info)): ?>
            <div class="error-details mt-4">
                <strong><i class="fas fa-bug me-2"></i>개발자 정보 (개발 환경에서만 표시):</strong><br>
                <small style="font-family: 'Courier New', monospace;">
                    <?= esc(json_encode($debug_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?>
                </small>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 결제 재시도 기능
        function retryPayment() {
            if (confirm('결제를 다시 시도하시겠습니까?')) {
                // 이전 페이지로 돌아가거나 결제 페이지로 리다이렉트
                history.back();
            }
        }
        
        // 다른 결제 방법 선택
        function selectOtherMethod() {
            if (confirm('다른 결제 방법을 선택하시겠습니까?')) {
                // 결제 방법 선택 페이지로 이동
                window.location.href = '<?= base_url('/payment/select') ?>';
            }
        }
        
        // 고객센터 연결
        function contactSupport() {
            if (confirm('고객센터에 전화를 걸겠습니까?')) {
                window.open('tel:1588-0000');
            }
        }
        
        // 오류 보고 (선택사항)
        function reportError() {
            const errorData = {
                van_provider: '<?= $van_provider ?? 'N/A' ?>',
                error_message: '<?= esc($error_message ?? '') ?>',
                error_code: '<?= esc($error_code ?? '') ?>',
                timestamp: new Date().toISOString(),
                user_agent: navigator.userAgent,
                url: window.location.href
            };
            
            // TODO: 오류 보고 API 호출
            console.log('Error Report:', errorData);
            alert('오류 보고가 전송되었습니다. 빠른 시일 내에 문제를 해결하겠습니다.');
        }
        
        // 페이지 로드 시 오류 로깅
        window.addEventListener('load', function() {
            // TODO: 클라이언트 측 오류 로깅
            if (typeof gtag !== 'undefined') {
                gtag('event', 'van_payment_error', {
                    van_provider: '<?= $van_provider ?? 'unknown' ?>',
                    error_code: '<?= esc($error_code ?? '') ?>',
                    custom_map: {
                        error_message: '<?= esc($error_message ?? '') ?>'
                    }
                });
            }
        });
    </script>
</body>
</html>