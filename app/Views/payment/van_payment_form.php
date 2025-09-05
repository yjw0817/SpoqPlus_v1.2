<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'VAN 결제' ?></title>
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
            max-width: 800px;
            width: 100%;
        }
        .van-provider-selector {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .van-option {
            flex: 1;
            min-width: 200px;
            max-width: 250px;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        .van-option:hover {
            border-color: #007bff;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .van-option.selected {
            border-color: #007bff;
            background: #f8f9ff;
        }
        .van-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            cursor: pointer;
        }
        .van-logo {
            width: 80px;
            height: 50px;
            margin: 0 auto 15px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            color: #495057;
        }
        .van-logo.kicc { background: #fee; color: #e74c3c; }
        .van-logo.nice { background: #fff8e1; color: #f39c12; }
        .van-logo.ksnet { background: #e8f5e8; color: #2ecc71; }
        .van-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: #495057;
        }
        .van-description {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 10px;
        }
        .van-features {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }
        .van-feature {
            background: #e9ecef;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            color: #6c757d;
        }
        .payment-form {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-top: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #dee2e6;
            padding: 12px 15px;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        .payment-summary {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .summary-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            font-weight: 600;
            color: #007bff;
        }
        .btn-payment {
            width: 100%;
            padding: 15px;
            border-radius: 25px;
            border: none;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .btn-payment:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            color: white;
        }
        .btn-payment:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }
        .terminal-info {
            background: #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            font-size: 13px;
            color: #6c757d;
        }
        .payment-methods {
            display: flex;
            gap: 15px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        .payment-method {
            flex: 1;
            min-width: 120px;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        .payment-method:hover, .payment-method.selected {
            border-color: #007bff;
            background: #f8f9ff;
        }
        .payment-method input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            cursor: pointer;
        }
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .loading-content {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .security-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            font-size: 13px;
            color: #0c5460;
        }
        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            color: #721c24;
            display: none;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-card">
            <h2 class="text-center mb-4">
                <i class="fas fa-credit-card me-2"></i>VAN 터미널 결제
            </h2>
            
            <!-- VAN 제공업체 선택 -->
            <div class="van-provider-selector">
                <div class="van-option" data-provider="kicc">
                    <input type="radio" name="van_provider" value="kicc" id="kicc_radio">
                    <div class="van-logo kicc">KICC</div>
                    <div class="van-name">KICC VAN</div>
                    <div class="van-description">신뢰성 높은 터미널 결제</div>
                    <div class="van-features">
                        <span class="van-feature">승인취소</span>
                        <span class="van-feature">부분취소</span>
                        <span class="van-feature">정산보고</span>
                    </div>
                </div>
                
                <div class="van-option" data-provider="nice">
                    <input type="radio" name="van_provider" value="nice" id="nice_radio">
                    <div class="van-logo nice">NICE</div>
                    <div class="van-name">Nice VAN</div>
                    <div class="van-description">온/오프라인 통합 결제</div>
                    <div class="van-features">
                        <span class="van-feature">배치처리</span>
                        <span class="van-feature">거래조회</span>
                        <span class="van-feature">실시간</span>
                    </div>
                </div>
                
                <div class="van-option" data-provider="ksnet">
                    <input type="radio" name="van_provider" value="ksnet" id="ksnet_radio">
                    <div class="van-logo ksnet">KSNET</div>
                    <div class="van-name">KSNET VAN</div>
                    <div class="van-description">빠른 실시간 처리</div>
                    <div class="van-features">
                        <span class="van-feature">실시간</span>
                        <span class="van-feature">매출승인</span>
                        <span class="van-feature">암호화</span>
                    </div>
                </div>
            </div>
            
            <form id="vanPaymentForm" class="payment-form">
                <!-- 결제 요약 정보 -->
                <div class="payment-summary">
                    <h5 class="mb-3"><i class="fas fa-receipt me-2"></i>결제 정보</h5>
                    <div class="summary-row">
                        <span>상품명:</span>
                        <span><?= esc($payment_data['sell_event_nm'] ?? '상품명') ?></span>
                    </div>
                    <div class="summary-row">
                        <span>결제자:</span>
                        <span><?= esc($payment_data['mem_nm'] ?? '고객명') ?></span>
                    </div>
                    <div class="summary-row">
                        <span>결제 금액:</span>
                        <span class="text-primary fw-bold">
                            <?= number_format($payment_data['paymt_amt'] ?? 0) ?>원
                        </span>
                    </div>
                </div>
                
                <!-- 결제 수단 선택 -->
                <div class="form-group">
                    <label class="form-label fw-bold">결제 수단</label>
                    <div class="payment-methods">
                        <div class="payment-method selected" data-method="card">
                            <input type="radio" name="payment_method" value="CARD" checked id="card_radio">
                            <i class="fas fa-credit-card fa-2x mb-2 text-primary"></i>
                            <div>신용카드</div>
                        </div>
                        <div class="payment-method" data-method="vbank">
                            <input type="radio" name="payment_method" value="VBANK" id="vbank_radio">
                            <i class="fas fa-university fa-2x mb-2 text-success"></i>
                            <div>가상계좌</div>
                        </div>
                        <div class="payment-method" data-method="bank">
                            <input type="radio" name="payment_method" value="BANK" id="bank_radio">
                            <i class="fas fa-building fa-2x mb-2 text-info"></i>
                            <div>계좌이체</div>
                        </div>
                    </div>
                </div>
                
                <!-- 추가 옵션 -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="installment" class="form-label">할부 개월</label>
                            <select class="form-control" id="installment" name="installment">
                                <option value="00">일시불</option>
                                <option value="02">2개월</option>
                                <option value="03">3개월</option>
                                <option value="06">6개월</option>
                                <option value="12">12개월</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="currency" class="form-label">통화</label>
                            <select class="form-control" id="currency" name="currency">
                                <option value="KRW">원화 (KRW)</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- VAN 터미널 정보 -->
                <div class="terminal-info" id="terminalInfo" style="display: none;">
                    <strong><i class="fas fa-server me-2"></i>선택된 VAN 터미널 정보</strong>
                    <div id="terminalDetails" class="mt-2"></div>
                </div>
                
                <!-- 보안 정보 -->
                <div class="security-info">
                    <i class="fas fa-shield-alt me-2"></i>
                    <strong>보안 안내:</strong> 본 결제는 VAN 터미널을 통해 안전하게 처리됩니다. 
                    모든 카드 정보는 암호화되어 전송되며, PCI-DSS 보안 기준을 준수합니다.
                </div>
                
                <!-- 오류 메시지 -->
                <div class="error-message" id="errorMessage">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="errorText"></span>
                </div>
                
                <!-- 결제 버튼 -->
                <button type="submit" class="btn btn-payment" id="paymentButton" disabled>
                    <i class="fas fa-lock me-2"></i>VAN 터미널로 결제하기
                </button>
                
                <!-- 숨김 필드 -->
                <input type="hidden" name="bcoff_cd" value="<?= esc($payment_data['bcoff_cd'] ?? '') ?>">
                <input type="hidden" name="mem_sno" value="<?= esc($payment_data['mem_sno'] ?? '') ?>">
                <input type="hidden" name="mem_id" value="<?= esc($payment_data['mem_id'] ?? '') ?>">
                <input type="hidden" name="mem_nm" value="<?= esc($payment_data['mem_nm'] ?? '') ?>">
                <input type="hidden" name="mem_tel" value="<?= esc($payment_data['mem_tel'] ?? '') ?>">
                <input type="hidden" name="mem_email" value="<?= esc($payment_data['mem_email'] ?? '') ?>">
                <input type="hidden" name="sell_event_sno" value="<?= esc($payment_data['sell_event_sno'] ?? '') ?>">
                <input type="hidden" name="sell_event_nm" value="<?= esc($payment_data['sell_event_nm'] ?? '') ?>">
                <input type="hidden" name="paymt_amt" value="<?= esc($payment_data['paymt_amt'] ?? '') ?>">
                <input type="hidden" name="paymt_chnl" value="VAN">
            </form>
        </div>
    </div>
    
    <!-- 로딩 오버레이 -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <h5>VAN 터미널 결제 처리 중...</h5>
            <p class="text-muted">잠시만 기다려주세요. 결제가 진행 중입니다.</p>
            <div id="loadingStatus">VAN 연결 중...</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // VAN 제공업체 선택 이벤트
            $('.van-option').on('click', function() {
                $('.van-option').removeClass('selected');
                $(this).addClass('selected');
                $(this).find('input[type="radio"]').prop('checked', true);
                
                const provider = $(this).data('provider');
                updateTerminalInfo(provider);
                $('#paymentButton').prop('disabled', false);
                updatePaymentButton(provider);
            });
            
            // 결제 수단 선택 이벤트
            $('.payment-method').on('click', function() {
                $('.payment-method').removeClass('selected');
                $(this).addClass('selected');
                $(this).find('input[type="radio"]').prop('checked', true);
                
                const method = $(this).data('method');
                updateInstallmentOptions(method);
            });
            
            // 폼 제출 이벤트
            $('#vanPaymentForm').on('submit', function(e) {
                e.preventDefault();
                
                const selectedProvider = $('input[name="van_provider"]:checked').val();
                if (!selectedProvider) {
                    showError('VAN 제공업체를 선택해주세요.');
                    return;
                }
                
                processVanPayment(selectedProvider);
            });
        });
        
        // VAN 터미널 정보 업데이트
        function updateTerminalInfo(provider) {
            const terminalInfo = {
                kicc: {
                    name: 'KICC VAN',
                    features: ['터미널 기반 승인', '서명 검증', '정산 보고서'],
                    description: '안정적인 터미널 결제 시스템'
                },
                nice: {
                    name: 'Nice VAN',
                    features: ['온/오프라인 통합', '배치 처리', '거래 조회'],
                    description: '유연한 결제 처리 시스템'
                },
                ksnet: {
                    name: 'KSNET VAN',
                    features: ['실시간 처리', '고속 승인', '암호화 통신'],
                    description: '빠른 실시간 결제 시스템'
                }
            };
            
            const info = terminalInfo[provider];
            if (info) {
                const details = `
                    <strong>${info.name}</strong><br>
                    ${info.description}<br>
                    지원 기능: ${info.features.join(', ')}
                `;
                $('#terminalDetails').html(details);
                $('#terminalInfo').show();
            }
        }
        
        // 결제 버튼 업데이트
        function updatePaymentButton(provider) {
            const providerNames = {
                kicc: 'KICC',
                nice: 'Nice',
                ksnet: 'KSNET'
            };
            
            $('#paymentButton').html(`
                <i class="fas fa-lock me-2"></i>${providerNames[provider]} VAN으로 결제하기
            `);
        }
        
        // 할부 옵션 업데이트
        function updateInstallmentOptions(method) {
            const $installment = $('#installment');
            
            if (method === 'card') {
                $installment.prop('disabled', false);
            } else {
                $installment.val('00').prop('disabled', true);
            }
        }
        
        // VAN 결제 처리
        function processVanPayment(provider) {
            showLoading();
            hideError();
            
            const formData = new FormData($('#vanPaymentForm')[0]);
            const apiEndpoints = {
                kicc: '/payment/kicc-van-init',
                nice: '/payment/nice-van-init',
                ksnet: '/payment/ksnet-van-init'
            };
            
            updateLoadingStatus('VAN 터미널 연결 중...');
            
            $.ajax({
                url: apiEndpoints[provider],
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                timeout: 30000,
                success: function(response) {
                    if (response.status === 'success') {
                        updateLoadingStatus('결제 정보 전송 중...');
                        handleVanPaymentSuccess(response.data, provider);
                    } else {
                        hideLoading();
                        showError(response.message || 'VAN 결제 초기화에 실패했습니다.');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    
                    let errorMessage = 'VAN 결제 처리 중 오류가 발생했습니다.';
                    if (status === 'timeout') {
                        errorMessage = 'VAN 터미널 연결 시간이 초과되었습니다.';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    showError(errorMessage);
                }
            });
        }
        
        // VAN 결제 성공 처리
        function handleVanPaymentSuccess(data, provider) {
            updateLoadingStatus('터미널 승인 대기 중...');
            
            // VAN 제공업체별 처리
            if (provider === 'kicc' && data.redirect_url) {
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1000);
            } else if (provider === 'nice' && data.next_redirect_url) {
                setTimeout(() => {
                    window.location.href = data.next_redirect_url;
                }, 1000);
            } else if (provider === 'ksnet' && data.redirect_url) {
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1000);
            } else {
                // 폼 기반 처리
                createVanPaymentForm(data, provider);
            }
        }
        
        // VAN 결제 폼 생성 및 제출
        function createVanPaymentForm(data, provider) {
            const form = $('<form>', {
                method: 'POST',
                action: data.payment_url || `/payment/van/${provider}/process`,
                style: 'display: none;'
            });
            
            // 폼 데이터 추가
            if (data.payment_form_data) {
                $.each(data.payment_form_data, function(key, value) {
                    form.append($('<input>', {
                        type: 'hidden',
                        name: key,
                        value: value
                    }));
                });
            }
            
            $('body').append(form);
            form.submit();
        }
        
        // 로딩 표시
        function showLoading() {
            $('#loadingOverlay').css('display', 'flex');
        }
        
        // 로딩 숨김
        function hideLoading() {
            $('#loadingOverlay').hide();
        }
        
        // 로딩 상태 업데이트
        function updateLoadingStatus(status) {
            $('#loadingStatus').text(status);
        }
        
        // 오류 메시지 표시
        function showError(message) {
            $('#errorText').text(message);
            $('#errorMessage').show();
            
            // 3초 후 자동 숨김
            setTimeout(hideError, 3000);
        }
        
        // 오류 메시지 숨김
        function hideError() {
            $('#errorMessage').hide();
        }
        
        // 페이지 이탈 방지 (결제 진행 중)
        let paymentInProgress = false;
        
        window.addEventListener('beforeunload', function(e) {
            if (paymentInProgress) {
                e.preventDefault();
                e.returnValue = '결제가 진행 중입니다. 페이지를 벗어나시겠습니까?';
                return e.returnValue;
            }
        });
    </script>
</body>
</html>