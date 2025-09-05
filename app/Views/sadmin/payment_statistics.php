<style>
.analytics-dashboard {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin: 20px 0;
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
.stat-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 20px;
    color: white;
}
.stat-icon.revenue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-icon.transactions { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-icon.success { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-icon.users { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.stat-number {
    font-size: 28px;
    font-weight: bold;
    color: #343a40;
    margin-bottom: 5px;
}
.stat-label {
    font-size: 14px;
    color: #6c757d;
    margin-bottom: 10px;
}
.stat-change {
    font-size: 12px;
    font-weight: 500;
}
.stat-change.positive { color: #28a745; }
.stat-change.negative { color: #dc3545; }
.chart-container {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.chart-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 20px;
}
.chart-tabs {
    display: flex;
    gap: 10px;
}
.chart-tab {
    padding: 8px 16px;
    border: 1px solid #e9ecef;
    background: #f8f9fa;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
}
.chart-tab.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}
.filter-section {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.table-section {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
}
.export-section {
    text-align: right;
    margin-bottom: 15px;
}
</style>
<?php
// Initialize default values to prevent errors
$sDef = SpoqDef();
$view = $view ?? [];
$analytics_data = $view['analytics_data'] ?? $analytics_data ?? [
    'total_revenue' => 0,
    'total_transactions' => 0,
    'success_rate' => 0,
    'total_users' => 0,
    'revenue_change' => 0,
    'transaction_change' => 0,
    'success_change' => 0,
    'user_change' => 0
];
$payment_summary = $view['payment_summary'] ?? $payment_summary ?? [];
$search_val = $view['search_val'] ?? $search_val ?? ['listCount' => 0];
$pager = $view['pager'] ?? $pager ?? '';
$selected_branch = $view['selected_branch'] ?? $selected_branch ?? '';
$branch_list = $view['branch_list'] ?? $branch_list ?? [];
?>

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

<!-- 결제 분석 대시보드 -->
<div class="analytics-dashboard">
    <div class="row">
        <div class="col-md-8">
            <h4><i class="fas fa-chart-line"></i> 결제 통계 및 분석</h4>
            <p class="text-muted">결제 데이터를 분석하여 비즈니스 인사이트를 확인하세요.</p>
        </div>
        <div class="col-md-4 text-right">
            <div class="export-section">
                <div class="btn-group">
                    <button type="button" class="btn btn-info btn-sm" onclick="refreshAnalytics()">
                        <i class="fas fa-sync"></i> 새로고침
                    </button>
                    <button type="button" class="btn btn-success btn-sm" onclick="exportReport()">
                        <i class="fas fa-download"></i> 리포트 다운로드
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 주요 지표 카드 -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon revenue"><i class="fas fa-won-sign"></i></div>
        <div class="stat-number"><?= number_format($analytics_data['total_revenue']) ?>원</div>
        <div class="stat-label">총 결제 금액</div>
        <div class="stat-change <?= $analytics_data['revenue_change'] >= 0 ? 'positive' : 'negative' ?>">
            <i class="fas fa-<?= $analytics_data['revenue_change'] >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
            <?= abs($analytics_data['revenue_change']) ?>% 전월 대비
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon transactions"><i class="fas fa-credit-card"></i></div>
        <div class="stat-number"><?= number_format($analytics_data['total_transactions']) ?>건</div>
        <div class="stat-label">총 거래 건수</div>
        <div class="stat-change <?= $analytics_data['transaction_change'] >= 0 ? 'positive' : 'negative' ?>">
            <i class="fas fa-<?= $analytics_data['transaction_change'] >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
            <?= abs($analytics_data['transaction_change']) ?>% 전월 대비
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
        <div class="stat-number"><?= number_format($analytics_data['success_rate'], 1) ?>%</div>
        <div class="stat-label">결제 성공률</div>
        <div class="stat-change <?= $analytics_data['success_change'] >= 0 ? 'positive' : 'negative' ?>">
            <i class="fas fa-<?= $analytics_data['success_change'] >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
            <?= abs($analytics_data['success_change']) ?>%p 전월 대비
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon users"><i class="fas fa-users"></i></div>
        <div class="stat-number"><?= number_format($analytics_data['total_users']) ?>명</div>
        <div class="stat-label">이용 고객 수</div>
        <div class="stat-change <?= $analytics_data['user_change'] >= 0 ? 'positive' : 'negative' ?>">
            <i class="fas fa-<?= $analytics_data['user_change'] >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
            <?= abs($analytics_data['user_change']) ?>% 전월 대비
        </div>
    </div>
</div>

<!-- 차트 영역 -->
<div class="row">
    <div class="col-md-8">
        <div class="chart-container">
            <div class="chart-header">
                <h5><i class="fas fa-chart-area"></i> 결제 추이 분석</h5>
                <div class="chart-tabs">
                    <div class="chart-tab active" onclick="changeChartPeriod('daily')">일간</div>
                    <div class="chart-tab" onclick="changeChartPeriod('weekly')">주간</div>
                    <div class="chart-tab" onclick="changeChartPeriod('monthly')">월간</div>
                </div>
            </div>
            <div style="height: 400px;">
                <canvas id="paymentTrendChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="chart-container">
            <h5><i class="fas fa-chart-pie"></i> 결제수단별 비율</h5>
            <div style="height: 400px;">
                <canvas id="paymentMethodChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- 필터 및 검색 -->
<div class="filter-section">
    <form id="analyticsFilter" method="GET">
        <div class="row">
            <div class="col-md-3">
                <label>조회 기간</label>
                <div class="input-group">
                    <input type="date" class="form-control" name="start_date" id="startDate" 
                           value="<?= $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days')) ?>">
                    <div class="input-group-text">~</div>
                    <input type="date" class="form-control" name="end_date" id="endDate" 
                           value="<?= $_GET['end_date'] ?? date('Y-m-d') ?>">
                </div>
            </div>
            <div class="col-md-2">
                <label>결제수단</label>
                <select class="form-control" name="payment_method" id="paymentMethod">
                    <option value="">전체</option>
                    <option value="CARD" <?= ($_GET['payment_method'] ?? '') == 'CARD' ? 'selected' : '' ?>>카드</option>
                    <option value="BANK" <?= ($_GET['payment_method'] ?? '') == 'BANK' ? 'selected' : '' ?>>계좌이체</option>
                    <option value="VBANK" <?= ($_GET['payment_method'] ?? '') == 'VBANK' ? 'selected' : '' ?>>가상계좌</option>
                    <option value="PHONE" <?= ($_GET['payment_method'] ?? '') == 'PHONE' ? 'selected' : '' ?>>휴대폰</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>PG사</label>
                <select class="form-control" name="pg_provider" id="pgProvider">
                    <option value="">전체</option>
                    <option value="INICIS" <?= ($_GET['pg_provider'] ?? '') == 'INICIS' ? 'selected' : '' ?>>이니시스</option>
                    <option value="KCP" <?= ($_GET['pg_provider'] ?? '') == 'KCP' ? 'selected' : '' ?>>KCP</option>
                    <option value="TOSS" <?= ($_GET['pg_provider'] ?? '') == 'TOSS' ? 'selected' : '' ?>>토스페이먼츠</option>
                    <option value="NICE" <?= ($_GET['pg_provider'] ?? '') == 'NICE' ? 'selected' : '' ?>>나이스페이</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>금액 범위</label>
                <select class="form-control" name="amount_range" id="amountRange">
                    <option value="">전체</option>
                    <option value="0-10000" <?= ($_GET['amount_range'] ?? '') == '0-10000' ? 'selected' : '' ?>>1만원 이하</option>
                    <option value="10000-50000" <?= ($_GET['amount_range'] ?? '') == '10000-50000' ? 'selected' : '' ?>>1-5만원</option>
                    <option value="50000-100000" <?= ($_GET['amount_range'] ?? '') == '50000-100000' ? 'selected' : '' ?>>5-10만원</option>
                    <option value="100000-" <?= ($_GET['amount_range'] ?? '') == '100000-' ? 'selected' : '' ?>>10만원 이상</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter"></i> 필터 적용
                    </button>
                </div>
            </div>
            <div class="col-md-1">
                <label>&nbsp;</label>
                <div>
                    <button type="button" class="btn btn-secondary btn-block" onclick="resetFilters()">
                        <i class="fas fa-undo"></i> 초기화
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- 상세 통계 테이블 -->
<div class="table-section">
    <div class="panel-heading">
        <h5 class="panel-title"><i class="fas fa-table"></i> 상세 통계</h5>
    </div>
    <div class="panel-body table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr style='text-align:center'>
                    <th style='width:8%'>날짜</th>
                    <th style='width:10%'>결제수단</th>
                    <th style='width:10%'>PG사</th>
                    <th style='width:8%'>총 건수</th>
                    <th style='width:8%'>성공 건수</th>
                    <th style='width:8%'>실패 건수</th>
                    <th style='width:8%'>성공률</th>
                    <th style='width:12%'>총 금액</th>
                    <th style='width:12%'>성공 금액</th>
                    <th style='width:8%'>평균 금액</th>
                    <th style='width:8%'>상세</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($payment_summary) > 0): ?>
                    <?php foreach($payment_summary as $summary): ?>
                    <tr>
                        <td class="text-center"><?= $summary['payment_date'] ?? '-' ?></td>
                        <td class="text-center"><?= $summary['payment_method'] ?? '-' ?></td>
                        <td class="text-center"><?= $summary['pg_provider'] ?? '-' ?></td>
                        <td class="text-center"><?= number_format($summary['total_count'] ?? 0) ?></td>
                        <td class="text-center text-success"><?= number_format($summary['success_count'] ?? 0) ?></td>
                        <td class="text-center text-danger"><?= number_format($summary['failed_count'] ?? 0) ?></td>
                        <td class="text-center">
                            <span class="badge badge-<?= ($summary['success_rate'] ?? 0) >= 95 ? 'success' : (($summary['success_rate'] ?? 0) >= 90 ? 'warning' : 'danger') ?>">
                                <?= number_format($summary['success_rate'] ?? 0, 1) ?>%
                            </span>
                        </td>
                        <td class="text-right"><?= number_format($summary['total_amount'] ?? 0) ?>원</td>
                        <td class="text-right text-success"><?= number_format($summary['success_amount'] ?? 0) ?>원</td>
                        <td class="text-right"><?= number_format($summary['avg_amount'] ?? 0) ?>원</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info btn-xs" onclick="viewDetailStats('<?= $summary['payment_date'] ?? '' ?>', '<?= $summary['payment_method'] ?? '' ?>', '<?= $summary['pg_provider'] ?? '' ?>')">
                                <i class="fas fa-chart-bar"></i> 상세
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="11" class="text-center text-muted py-4">
                        <i class="fas fa-chart-line fa-2x mb-2"></i><br>
                        조회된 통계 데이터가 없습니다.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- CARD FOOTER [START] -->
    <div class="card-footer clearfix">
        <!-- PAGZING [START] -->
        <?=$pager?>
        <!-- PAGZING [END] -->
    </div>
    <!-- CARD FOOTER [END] -->
</div>

<!-- 상세 통계 모달 -->
<div class="modal fade" id="detailStatsModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">상세 통계 정보</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailStatsContent">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">통계 정보를 불러오는 중...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary" onclick="exportDetailStats()">
                    <i class="fas fa-download"></i> 다운로드
                </button>
            </div>
        </div>
    </div>
</div>

</section>

<?=$jsinc ?>

<!-- Chart.js 라이브러리 -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let paymentTrendChart;
let paymentMethodChart;
let currentChartPeriod = 'daily';

$(function () {
    // 초기 차트 로드
    initializeCharts();
    loadAnalyticsData();
});

function initializeCharts() {
    // 결제 추이 차트
    const trendCtx = document.getElementById('paymentTrendChart').getContext('2d');
    paymentTrendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: '결제 금액',
                data: [],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }, {
                label: '결제 건수',
                data: [],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: '결제 금액 (원)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: '결제 건수'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
    
    // 결제수단별 차트
    const methodCtx = document.getElementById('paymentMethodChart').getContext('2d');
    paymentMethodChart = new Chart(methodCtx, {
        type: 'doughnut',
        data: {
            labels: ['카드', '계좌이체', '가상계좌', '휴대폰'],
            datasets: [{
                data: [0, 0, 0, 0],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function loadAnalyticsData() {
    const params = new URLSearchParams(window.location.search);
    
    $.ajax({
        url: '/smgrmain/ajax_get_analytics_data',
        type: 'GET',
        data: params.toString(),
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success') {
                updateCharts(response.data);
            }
        },
        error: function() {
            console.error('분석 데이터 로드 실패');
        }
    });
}

function updateCharts(data) {
    // 추이 차트 업데이트
    if (data.trend) {
        paymentTrendChart.data.labels = data.trend.labels;
        paymentTrendChart.data.datasets[0].data = data.trend.amounts;
        paymentTrendChart.data.datasets[1].data = data.trend.counts;
        paymentTrendChart.update();
    }
    
    // 결제수단 차트 업데이트
    if (data.methods) {
        paymentMethodChart.data.datasets[0].data = data.methods;
        paymentMethodChart.update();
    }
}

function changeChartPeriod(period) {
    currentChartPeriod = period;
    
    // 탭 활성화 변경
    $('.chart-tab').removeClass('active');
    $(event.target).addClass('active');
    
    // 차트 데이터 다시 로드
    $.ajax({
        url: '/smgrmain/ajax_get_chart_data',
        type: 'GET',
        data: { period: period },
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success') {
                updateCharts(response.data);
            }
        }
    });
}

function refreshAnalytics() {
    location.reload();
}

function exportReport() {
    const params = new URLSearchParams(window.location.search);
    params.append('export', 'true');
    
    window.open('/smgrmain/export_analytics_report?' + params.toString(), '_blank');
}

function resetFilters() {
    $('#startDate').val('<?= date('Y-m-d', strtotime('-30 days')) ?>');
    $('#endDate').val('<?= date('Y-m-d') ?>');
    $('#paymentMethod').val('');
    $('#pgProvider').val('');
    $('#amountRange').val('');
    $('#analyticsFilter').submit();
}

function viewDetailStats(date, method, provider) {
    $('#detailStatsModal').modal('show');
    
    $.ajax({
        url: '/smgrmain/ajax_get_detail_stats',
        type: 'GET',
        data: {
            payment_date: date,
            payment_method: method,
            pg_provider: provider
        },
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success') {
                displayDetailStats(response.data);
            } else {
                $('#detailStatsContent').html('<div class="alert alert-warning">상세 정보를 불러올 수 없습니다.</div>');
            }
        },
        error: function() {
            $('#detailStatsContent').html('<div class="alert alert-danger">서버 오류가 발생했습니다.</div>');
        }
    });
}

function displayDetailStats(data) {
    let html = `
        <div class="row">
            <div class="col-md-6">
                <h6>기본 정보</h6>
                <table class="table table-sm">
                    <tr><td>날짜:</td><td>${data.date}</td></tr>
                    <tr><td>결제수단:</td><td>${data.method}</td></tr>
                    <tr><td>PG사:</td><td>${data.provider}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>거래 통계</h6>
                <table class="table table-sm">
                    <tr><td>총 건수:</td><td>${numberFormat(data.total_count)}건</td></tr>
                    <tr><td>성공 건수:</td><td class="text-success">${numberFormat(data.success_count)}건</td></tr>
                    <tr><td>실패 건수:</td><td class="text-danger">${numberFormat(data.failed_count)}건</td></tr>
                    <tr><td>성공률:</td><td>${data.success_rate}%</td></tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h6>금액 통계</h6>
                <table class="table table-sm">
                    <tr><td>총 금액:</td><td>${numberFormat(data.total_amount)}원</td></tr>
                    <tr><td>성공 금액:</td><td class="text-success">${numberFormat(data.success_amount)}원</td></tr>
                    <tr><td>평균 금액:</td><td>${numberFormat(data.avg_amount)}원</td></tr>
                    <tr><td>최고 금액:</td><td>${numberFormat(data.max_amount)}원</td></tr>
                    <tr><td>최저 금액:</td><td>${numberFormat(data.min_amount)}원</td></tr>
                </table>
            </div>
        </div>
    `;
    
    $('#detailStatsContent').html(html);
}

function exportDetailStats() {
    // 상세 통계 내보내기 로직
    alertToast('info', '상세 통계 다운로드 기능은 개발 중입니다.');
}

function numberFormat(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// 지점 변경 함수
function changeBranch() {
    const selectedBranch = $('#branch_select').val();
    if (selectedBranch) {
        window.location.href = '/smgrmain/payment_statistics?bcoff_cd=' + selectedBranch;
    } else {
        window.location.href = '/smgrmain/payment_statistics';
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