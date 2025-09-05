<style>
.overview-dashboard {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin: 20px 0;
}
.setup-progress {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.progress-step {
    display: inline-block;
    width: 18%;
    text-align: center;
    position: relative;
    margin-bottom: 10px;
}
.progress-step:before {
    content: '';
    position: absolute;
    top: 15px;
    left: 50%;
    width: 100%;
    height: 2px;
    background: #ddd;
    z-index: 1;
}
.progress-step:first-child:before {
    display: none;
}
.progress-step.completed:before {
    background: #28a745;
}
.step-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #ddd;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 2;
    color: white;
    font-weight: bold;
    margin-bottom: 5px;
}
.progress-step.completed .step-icon {
    background: #28a745;
}
.progress-step.current .step-icon {
    background: #007bff;
}
.progress-step.pending .step-icon {
    background: #6c757d;
}
.setup-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
}
.setup-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.setup-card.completed {
    border-color: #28a745;
    background: #f8fff8;
}
.setup-card.current {
    border-color: #007bff;
    background: #f8f9ff;
}
.setup-card.pending {
    border-color: #6c757d;
    background: #f8f9fa;
}
.status-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    margin-bottom: 15px;
}
.status-completed { background: #28a745; }
.status-current { background: #007bff; }
.status-pending { background: #6c757d; }
.status-blocked { background: #dc3545; }
.payment-summary {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.summary-stat {
    text-align: center;
    padding: 15px;
    border-right: 1px solid #e9ecef;
}
.summary-stat:last-child {
    border-right: none;
}
.stat-number {
    font-size: 24px;
    font-weight: bold;
    color: #007bff;
}
.stat-label {
    font-size: 12px;
    color: #6c757d;
    margin-top: 5px;
}
</style>

<!-- Main content -->
<h1 class="page-header"><?php echo $title ?></h1>

<!-- 지점 선택 (슈퍼 관리자용) -->
<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'sadmin'): ?>
<div class="panel panel-inverse mb-3">
    <div class="panel-body">
        <div class="row align-items-center">
            <div class="col-md-3">
                <label class="fw-bold">지점 선택:</label>
            </div>
            <div class="col-md-6">
                <select class="form-control" id="branch_select" onchange="changeBranch()">
                    <option value="">지점을 선택하세요</option>
                    <?php if(isset($branch_list) && is_array($branch_list)): ?>
                        <?php foreach($branch_list as $branch): ?>
                            <option value="<?= $branch['BCOFF_CD'] ?? '' ?>" 
                                    <?= (isset($selected_branch) && $selected_branch == ($branch['BCOFF_CD'] ?? '')) ? 'selected' : '' ?>>
                                <?= ($branch['BCOFF_NM'] ?? '') . ' (' . ($branch['MNGR_NM'] ?? '') . ')' ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- 결제 설정 대시보드 -->
<div class="overview-dashboard">
    <div class="row">
        <div class="col-md-6">
            <h4><i class="fas fa-credit-card"></i> 결제 설정 현황</h4>
            <p class="text-muted">지점의 PG, VAN, 계좌 설정을 관리하고 운영 상태를 확인하세요.</p>
        </div>
        <div class="col-md-6 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="location.href='/smgrmain/pg_van_settings'">
                    <i class="fas fa-cogs"></i> PG/VAN 설정
                </button>
                <button type="button" class="btn btn-success" onclick="location.href='/smgrmain/bank_account_management'">
                    <i class="fas fa-university"></i> 계좌 관리
                </button>
                <button type="button" class="btn btn-info" onclick="location.href='/smgrmain/payment_status_inquiry'">
                    <i class="fas fa-search"></i> 결제 조회
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 설정 진행 단계 -->
<div class="setup-progress">
    <h5 class="mb-3"><i class="fas fa-tasks"></i> 설정 진행 단계</h5>
    
    <!-- 진행바 -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="progress-step <?= ($setup_status['basic_info_yn'] ?? 'N') == 'Y' ? 'completed' : 'current' ?>">
                <div class="step-icon">
                    <i class="fas <?= ($setup_status['basic_info_yn'] ?? 'N') == 'Y' ? 'fa-check' : 'fa-1' ?>"></i>
                </div>
                <div class="step-label">기본정보</div>
            </div>
            <div class="progress-step <?= ($setup_status['pg_setup_yn'] ?? 'N') == 'Y' ? 'completed' : (($setup_status['basic_info_yn'] ?? 'N') == 'Y' ? 'current' : 'pending') ?>">
                <div class="step-icon">
                    <i class="fas <?= ($setup_status['pg_setup_yn'] ?? 'N') == 'Y' ? 'fa-check' : 'fa-2' ?>"></i>
                </div>
                <div class="step-label">PG설정</div>
            </div>
            <div class="progress-step <?= ($setup_status['van_setup_yn'] ?? 'N') == 'Y' ? 'completed' : (($setup_status['pg_setup_yn'] ?? 'N') == 'Y' ? 'current' : 'pending') ?>">
                <div class="step-icon">
                    <i class="fas <?= ($setup_status['van_setup_yn'] ?? 'N') == 'Y' ? 'fa-check' : 'fa-3' ?>"></i>
                </div>
                <div class="step-label">VAN설정</div>
            </div>
            <div class="progress-step <?= ($setup_status['bank_setup_yn'] ?? 'N') == 'Y' ? 'completed' : (($setup_status['van_setup_yn'] ?? 'N') == 'Y' ? 'current' : 'pending') ?>">
                <div class="step-icon">
                    <i class="fas <?= ($setup_status['bank_setup_yn'] ?? 'N') == 'Y' ? 'fa-check' : 'fa-4' ?>"></i>
                </div>
                <div class="step-label">계좌설정</div>
            </div>
            <div class="progress-step <?= ($setup_status['ready_yn'] ?? 'N') == 'Y' ? 'completed' : (($setup_status['bank_setup_yn'] ?? 'N') == 'Y' ? 'current' : 'pending') ?>">
                <div class="step-icon">
                    <i class="fas <?= ($setup_status['ready_yn'] ?? 'N') == 'Y' ? 'fa-check' : 'fa-5' ?>"></i>
                </div>
                <div class="step-label">운영준비</div>
            </div>
        </div>
    </div>
    
    <!-- 진행률 -->
    <div class="row">
        <div class="col-md-8">
            <div class="progress mb-2">
                <div class="progress-bar" role="progressbar" style="width: <?= $setup_status['setup_progress'] ?? 0 ?>%" 
                     aria-valuenow="<?= $setup_status['setup_progress'] ?? 0 ?>" aria-valuemin="0" aria-valuemax="100">
                    <?= $setup_status['setup_progress'] ?? 0 ?>%
                </div>
            </div>
            <small class="text-muted">전체 설정 완료율</small>
        </div>
        <div class="col-md-4 text-right">
            <?php if(($setup_status['setup_progress'] ?? 0) < 100): ?>
                <button type="button" class="btn btn-primary" onclick="continueNextStep()">
                    <i class="fas fa-arrow-right"></i> 다음 단계 진행
                </button>
            <?php else: ?>
                <span class="badge badge-success badge-lg">
                    <i class="fas fa-check"></i> 설정 완료
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- 설정 카드들 -->
<div class="row">
    <!-- PG 설정 -->
    <div class="col-md-6 mb-4">
        <div class="setup-card <?= ($setup_status['pg_setup_yn'] ?? 'N') == 'Y' ? 'completed' : (($setup_status['basic_info_yn'] ?? 'N') == 'Y' ? 'current' : 'pending') ?> p-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="status-icon status-<?= ($setup_status['pg_setup_yn'] ?? 'N') == 'Y' ? 'completed' : (($setup_status['basic_info_yn'] ?? 'N') == 'Y' ? 'current' : 'pending') ?>">
                        <i class="fas fa-credit-card"></i>
                    </div>
                </div>
                <div class="col">
                    <h5 class="mb-1">PG 설정</h5>
                    <p class="text-muted mb-2">결제 게이트웨이 설정 및 관리</p>
                    <?php if(isset($pg_summary)): ?>
                        <small class="text-success">
                            <i class="fas fa-check-circle"></i> 
                            <?= count($pg_summary['active'] ?? []) ?>개 PG사 활성화
                        </small>
                    <?php else: ?>
                        <small class="text-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            PG 설정 필요
                        </small>
                    <?php endif; ?>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="location.href='/smgrmain/pg_van_settings'">
                        설정하기
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- VAN 설정 -->
    <div class="col-md-6 mb-4">
        <div class="setup-card <?= ($setup_status['van_setup_yn'] ?? 'N') == 'Y' ? 'completed' : (($setup_status['pg_setup_yn'] ?? 'N') == 'Y' ? 'current' : 'pending') ?> p-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="status-icon status-<?= ($setup_status['van_setup_yn'] ?? 'N') == 'Y' ? 'completed' : (($setup_status['pg_setup_yn'] ?? 'N') == 'Y' ? 'current' : 'pending') ?>">
                        <i class="fas fa-network-wired"></i>
                    </div>
                </div>
                <div class="col">
                    <h5 class="mb-1">VAN 설정</h5>
                    <p class="text-muted mb-2">부가가치통신망 설정 및 관리</p>
                    <?php if(isset($van_summary)): ?>
                        <small class="text-success">
                            <i class="fas fa-check-circle"></i> 
                            <?= count($van_summary['active'] ?? []) ?>개 VAN사 활성화
                        </small>
                    <?php else: ?>
                        <small class="text-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            VAN 설정 필요
                        </small>
                    <?php endif; ?>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="location.href='/smgrmain/pg_van_settings#van-tab'">
                        설정하기
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 계좌 설정 -->
    <div class="col-md-6 mb-4">
        <div class="setup-card <?= ($setup_status['bank_setup_yn'] ?? 'N') == 'Y' ? 'completed' : (($setup_status['van_setup_yn'] ?? 'N') == 'Y' ? 'current' : 'pending') ?> p-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="status-icon status-<?= ($setup_status['bank_setup_yn'] ?? 'N') == 'Y' ? 'completed' : (($setup_status['van_setup_yn'] ?? 'N') == 'Y' ? 'current' : 'pending') ?>">
                        <i class="fas fa-university"></i>
                    </div>
                </div>
                <div class="col">
                    <h5 class="mb-1">계좌 설정</h5>
                    <p class="text-muted mb-2">입금 계좌 및 가상계좌 관리</p>
                    <?php if(isset($bank_summary)): ?>
                        <small class="text-success">
                            <i class="fas fa-check-circle"></i> 
                            <?= count($bank_summary['accounts'] ?? []) ?>개 계좌 등록
                        </small>
                    <?php else: ?>
                        <small class="text-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            계좌 설정 필요
                        </small>
                    <?php endif; ?>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="location.href='/smgrmain/bank_account_management'">
                        설정하기
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 테스트 및 검증 -->
    <div class="col-md-6 mb-4">
        <div class="setup-card <?= ($setup_status['ready_yn'] ?? 'N') == 'Y' ? 'completed' : (($setup_status['bank_setup_yn'] ?? 'N') == 'Y' ? 'current' : 'pending') ?> p-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="status-icon status-<?= ($setup_status['ready_yn'] ?? 'N') == 'Y' ? 'completed' : (($setup_status['bank_setup_yn'] ?? 'N') == 'Y' ? 'current' : 'pending') ?>">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
                <div class="col">
                    <h5 class="mb-1">테스트 및 운영준비</h5>
                    <p class="text-muted mb-2">결제 테스트 및 운영 준비 완료</p>
                    <?php if(($setup_status['ready_yn'] ?? 'N') == 'Y'): ?>
                        <small class="text-success">
                            <i class="fas fa-check-circle"></i> 
                            운영 준비 완료
                        </small>
                    <?php else: ?>
                        <small class="text-info">
                            <i class="fas fa-info-circle"></i> 
                            테스트 진행 중
                        </small>
                    <?php endif; ?>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="runPaymentTest()">
                        테스트 실행
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 결제 현황 요약 -->
<?php if(($setup_status['setup_progress'] ?? 0) > 0): ?>
<div class="payment-summary">
    <h5 class="mb-3"><i class="fas fa-chart-line"></i> 결제 현황 요약 (최근 7일)</h5>
    <div class="row">
        <div class="col-md-3">
            <div class="summary-stat">
                <div class="stat-number"><?= number_format($payment_stats['total_amount'] ?? 0) ?></div>
                <div class="stat-label">총 결제금액 (원)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-stat">
                <div class="stat-number"><?= number_format($payment_stats['total_count'] ?? 0) ?></div>
                <div class="stat-label">총 결제건수 (건)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-stat">
                <div class="stat-number"><?= number_format($payment_stats['success_rate'] ?? 0, 1) ?>%</div>
                <div class="stat-label">결제 성공률</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-stat">
                <div class="stat-number"><?= number_format($payment_stats['avg_amount'] ?? 0) ?></div>
                <div class="stat-label">평균 결제금액 (원)</div>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-6">
            <canvas id="paymentTrendChart" height="200"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="paymentMethodChart" height="200"></canvas>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- 최근 결제 이력 -->
<?php if(isset($recent_payments) && count($recent_payments) > 0): ?>
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">최근 결제 이력</h4>
        <div class="panel-tools">
            <a href="/smgrmain/payment_status_inquiry" class="btn btn-sm btn-primary">
                전체 보기
            </a>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>결제일시</th>
                        <th>주문번호</th>
                        <th>결제금액</th>
                        <th>결제수단</th>
                        <th>상태</th>
                        <th>회원명</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recent_payments as $payment): ?>
                    <tr>
                        <td><?= date('m-d H:i', strtotime($payment['PAYMT_DATE'])) ?></td>
                        <td><?= $payment['ORDER_NO'] ?></td>
                        <td class="text-right"><?= number_format($payment['PAYMT_AMT']) ?>원</td>
                        <td><?= $payment['PAYMT_METHOD'] ?></td>
                        <td>
                            <span class="badge badge-<?= $payment['PAYMT_STATUS'] == 'SUCCESS' ? 'success' : 'danger' ?>">
                                <?= $payment['PAYMT_STATUS'] ?>
                            </span>
                        </td>
                        <td><?= $payment['MEM_NM'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

</section>

<?=$jsinc ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(function () {
    // 차트 초기화
    initializeCharts();
    
    // 설정 상태 체크
    checkSetupStatus();
});

function continueNextStep() {
    const setupStatus = <?= json_encode($setup_status ?? []) ?>;
    
    if (setupStatus.basic_info_yn !== 'Y') {
        location.href = '/smgrmain/branch_basic_setup';
    } else if (setupStatus.pg_setup_yn !== 'Y') {
        location.href = '/smgrmain/pg_van_settings';
    } else if (setupStatus.van_setup_yn !== 'Y') {
        location.href = '/smgrmain/pg_van_settings#van-tab';
    } else if (setupStatus.bank_setup_yn !== 'Y') {
        location.href = '/smgrmain/bank_account_management';
    } else if (setupStatus.ready_yn !== 'Y') {
        runPaymentTest();
    } else {
        alertToast('info', '모든 설정이 완료되었습니다.');
    }
}

function runPaymentTest() {
    ToastConfirm.fire({
        icon: "question",
        title: "결제 테스트",
        html: "<font color='#000000'>결제 시스템 테스트를 진행하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
        if (result.isConfirmed) {
            // 테스트 모달 표시
            $('#paymentTestModal').modal('show');
        }
    });
}

function checkSetupStatus() {
    $.ajax({
        url: '/smgrmain/ajax_get_setup_status',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success') {
                updateSetupProgress(response.data);
            }
        }
    });
}

function updateSetupProgress(statusData) {
    // 진행률 업데이트
    $('.progress-bar').css('width', statusData.setup_progress + '%');
    $('.progress-bar').text(statusData.setup_progress + '%');
    
    // 카드 상태 업데이트
    updateCardStatus('.setup-card:nth-child(1)', statusData.pg_setup_yn);
    updateCardStatus('.setup-card:nth-child(2)', statusData.van_setup_yn);
    updateCardStatus('.setup-card:nth-child(3)', statusData.bank_setup_yn);
    updateCardStatus('.setup-card:nth-child(4)', statusData.ready_yn);
}

function updateCardStatus(selector, isCompleted) {
    const card = $(selector);
    if (isCompleted === 'Y') {
        card.removeClass('current pending').addClass('completed');
    } else {
        card.removeClass('completed pending').addClass('current');
    }
}

function initializeCharts() {
    // 결제 추이 차트
    const trendCtx = document.getElementById('paymentTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($chart_data['trend_labels'] ?? []) ?>,
                datasets: [{
                    label: '일별 결제금액',
                    data: <?= json_encode($chart_data['trend_data'] ?? []) ?>,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: '최근 7일 결제 추이'
                    }
                }
            }
        });
    }
    
    // 결제수단별 차트
    const methodCtx = document.getElementById('paymentMethodChart');
    if (methodCtx) {
        new Chart(methodCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($chart_data['method_labels'] ?? []) ?>,
                datasets: [{
                    data: <?= json_encode($chart_data['method_data'] ?? []) ?>,
                    backgroundColor: [
                        '#007bff',
                        '#28a745',
                        '#ffc107',
                        '#dc3545',
                        '#6f42c1'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: '결제수단별 비율'
                    }
                }
            }
        });
    }
}

// 지점 변경 함수
function changeBranch() {
    const selectedBranch = $('#branch_select').val();
    if (selectedBranch) {
        window.location.href = '/smgrmain/payment_settings_overview?bcoff_cd=' + selectedBranch;
    } else {
        window.location.href = '/smgrmain/payment_settings_overview';
    }
}

// 선택된 지점 코드 가져오기
function getSelectedBranchCode() {
    // URL 파라미터에서 먼저 확인
    const urlParams = new URLSearchParams(window.location.search);
    const urlBcoffCd = urlParams.get('bcoff_cd');
    if (urlBcoffCd) {
        return urlBcoffCd;
    }
    
    // 세션에서 확인
    <?php if (isset($_SESSION['bcoff_cd']) && $_SESSION['bcoff_cd']): ?>
    return '<?= $_SESSION['bcoff_cd'] ?>';
    <?php else: ?>
    return '';
    <?php endif; ?>
}
</script>