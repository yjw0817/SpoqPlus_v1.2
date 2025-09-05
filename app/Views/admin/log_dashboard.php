<style>
.info-box {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border-radius: .25rem;
    background-color: #fff;
    display: flex;
    margin-bottom: 1rem;
    min-height: 80px;
    padding: .5rem;
    position: relative;
    width: 100%;
}
.info-box .info-box-icon {
    align-items: center;
    display: flex;
    font-size: 1.875rem;
    justify-content: center;
    text-align: center;
    width: 70px;
    border-radius: .25rem;
}
.info-box .info-box-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    line-height: 1.8;
    flex: 1;
    padding: 0 10px;
}
.info-box .info-box-number {
    display: block;
    margin-top: .25rem;
    font-size: 1.5rem;
    font-weight: 700;
}
.info-box .info-box-text {
    display: block;
    font-size: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.info-box .progress {
    background-color: rgba(0,0,0,.125);
    height: 2px;
    margin: 5px 0;
}
.info-box .progress-description {
    font-size: 12px;
    margin: 0;
}
.bg-gradient-danger {
    background: linear-gradient(to right, #dc3545, #a02932) !important;
    color: #fff !important;
}
.bg-gradient-warning {
    background: linear-gradient(to right, #ffc107, #d39e00) !important;
    color: #000 !important;
}
.bg-gradient-info {
    background: linear-gradient(to right, #17a2b8, #117a8b) !important;
    color: #fff !important;
}
.bg-gradient-primary {
    background: linear-gradient(to right, #007bff, #0056b3) !important;
    color: #fff !important;
}
</style>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- 상단 통계 박스 -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Critical 오류</span>
                        <span class="info-box-number" id="critical-count">0</span>
                        <div class="progress">
                            <div class="progress-bar bg-danger" style="width: 0%"></div>
                        </div>
                        <span class="progress-description">
                            전체 대비 <span id="critical-percent">0</span>%
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-warning">
                        <i class="fas fa-times-circle"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Error 오류</span>
                        <span class="info-box-number" id="error-count">0</span>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: 0%"></div>
                        </div>
                        <span class="progress-description">
                            전체 대비 <span id="error-percent">0</span>%
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-info">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Warning 경고</span>
                        <span class="info-box-number" id="warning-count">0</span>
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: 0%"></div>
                        </div>
                        <span class="progress-description">
                            전체 대비 <span id="warning-percent">0</span>%
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-primary">
                        <i class="fas fa-users"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">영향받은 사용자</span>
                        <span class="info-box-number" id="affected-users"><?php echo $view['affected_users'] ?? 0; ?></span>
                        <span class="progress-description">
                            오늘 기준
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 차트 영역 -->
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">오류 발생 추이</h4>
                    </div>
                    <div class="panel-body">
                        <canvas id="errorTrendChart" height="80"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">오류 유형 분포</h4>
                    </div>
                    <div class="panel-body">
                        <canvas id="errorTypeChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 최근 오류 리스트 -->
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">최근 발생한 주요 오류</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 150px;">발생 시간</th>
                                <th style="width: 100px;">레벨</th>
                                <th>오류 메시지</th>
                                <th style="width: 200px;">파일 위치</th>
                                <th style="width: 80px;">발생횟수</th>
                                <th style="width: 100px;">옵션</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($view['top_errors'])): ?>
                                <?php foreach ($view['top_errors'] as $error): ?>
                                <tr>
                                    <td class="text-center"><?php echo date('Y-m-d H:i:s', strtotime($error['last_occurred'] ?? 'now')); ?></td>
                                    <td class="text-center">
                                        <?php 
                                        $levelClass = '';
                                        switch($error['error_level'] ?? '') {
                                            case 'CRITICAL':
                                                $levelClass = 'badge bg-danger';
                                                break;
                                            case 'ERROR':
                                                $levelClass = 'badge bg-warning text-dark';
                                                break;
                                            case 'WARNING':
                                                $levelClass = 'badge bg-info';
                                                break;
                                            default:
                                                $levelClass = 'badge bg-secondary';
                                        }
                                        ?>
                                        <span class="<?php echo $levelClass; ?>"><?php echo $error['error_level'] ?? 'INFO'; ?></span>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars(mb_substr($error['error_message'] ?? '', 0, 60)) . (mb_strlen($error['error_message'] ?? '') > 60 ? '...' : ''); ?>
                                    </td>
                                    <td class="text-center">
                                        <small class="text-muted">
                                            <?php 
                                            $filePath = $error['file_path'] ?? '';
                                            echo htmlspecialchars(basename($filePath)) . ':' . ($error['line_number'] ?? '0'); 
                                            ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary"><?php echo $error['total_count'] ?? 1; ?>회</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="/adminmain/log_analysis/<?php echo $error['id'] ?? ''; ?>?m1=4&m2=3" 
                                           class="btn btn-primary btn-xs">분석</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        최근 발생한 오류가 없습니다.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <div class="text-right">
                    <a href="/adminmain/log_search?m1=4&m2=2" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i> 상세 검색
                    </a>
                    <a href="/adminmain/log_statistics?m1=4&m2=6" class="btn btn-info btn-sm">
                        <i class="fas fa-chart-bar"></i> 통계 보기
                    </a>
                    <a href="/adminmain/log_settings?m1=4&m2=5" class="btn btn-secondary btn-sm">
                        <i class="fas fa-cog"></i> 설정
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// 차트 초기화
var errorTrendChart;
var errorTypeChart;

function initializeCharts() {
    // 트렌드 차트
    var ctx1 = document.getElementById('errorTrendChart').getContext('2d');
    errorTrendChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Critical',
                data: [],
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                tension: 0.4
            }, {
                label: 'Error',
                data: [],
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.4
            }, {
                label: 'Warning',
                data: [],
                borderColor: '#17a2b8',
                backgroundColor: 'rgba(23, 162, 184, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // 유형별 분포 차트
    var ctx2 = document.getElementById('errorTypeChart').getContext('2d');
    errorTypeChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Critical', 'Error', 'Warning'],
            datasets: [{
                data: [],
                backgroundColor: ['#dc3545', '#ffc107', '#17a2b8'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

// 대시보드 데이터 로드
function loadDashboardData() {
    fetch('/adminmain/getLogData', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'type=dashboard'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateDashboard(data.data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// 대시보드 업데이트
function updateDashboard(data) {
    // 통계 카드 업데이트
    var todayStats = data.today_stats || [];
    var criticalCount = 0, errorCount = 0, warningCount = 0;
    var totalCount = 0;
    
    todayStats.forEach(function(stat) {
        totalCount += parseInt(stat.count);
        switch(stat.error_level) {
            case 'CRITICAL':
                criticalCount = stat.count;
                break;
            case 'ERROR':
                errorCount = stat.count;
                break;
            case 'WARNING':
                warningCount = stat.count;
                break;
        }
    });
    
    document.getElementById('critical-count').textContent = criticalCount;
    document.getElementById('error-count').textContent = errorCount;
    document.getElementById('warning-count').textContent = warningCount;
    
    // 퍼센트 계산
    if (totalCount > 0) {
        document.getElementById('critical-percent').textContent = Math.round((criticalCount / totalCount) * 100);
        document.getElementById('error-percent').textContent = Math.round((errorCount / totalCount) * 100);
        document.getElementById('warning-percent').textContent = Math.round((warningCount / totalCount) * 100);
        
        // 프로그레스 바 업데이트
        document.querySelector('.bg-danger').style.width = Math.round((criticalCount / totalCount) * 100) + '%';
        document.querySelector('.bg-warning').style.width = Math.round((errorCount / totalCount) * 100) + '%';
        document.querySelector('.bg-info').style.width = Math.round((warningCount / totalCount) * 100) + '%';
    }
    
    // 차트 업데이트
    if (data.trend_data) {
        updateTrendChart(data.trend_data);
    }
    
    // 유형별 분포 차트 업데이트
    errorTypeChart.data.datasets[0].data = [criticalCount, errorCount, warningCount];
    errorTypeChart.update();
}

// 트렌드 차트 업데이트
function updateTrendChart(trendData) {
    var labels = [];
    var criticalData = [];
    var errorData = [];
    var warningData = [];
    
    // 날짜별로 그룹화
    var groupedData = {};
    trendData.forEach(function(item) {
        if (!groupedData[item.date]) {
            groupedData[item.date] = {
                critical: 0,
                error: 0,
                warning: 0
            };
        }
        
        switch(item.error_level) {
            case 'CRITICAL':
                groupedData[item.date].critical = item.count;
                break;
            case 'ERROR':
                groupedData[item.date].error = item.count;
                break;
            case 'WARNING':
                groupedData[item.date].warning = item.count;
                break;
        }
    });
    
    // 차트 데이터 생성
    Object.keys(groupedData).sort().forEach(function(date) {
        labels.push(date);
        criticalData.push(groupedData[date].critical);
        errorData.push(groupedData[date].error);
        warningData.push(groupedData[date].warning);
    });
    
    errorTrendChart.data.labels = labels;
    errorTrendChart.data.datasets[0].data = criticalData;
    errorTrendChart.data.datasets[1].data = errorData;
    errorTrendChart.data.datasets[2].data = warningData;
    errorTrendChart.update();
}

// 초기 데이터 설정
var initialData = {
    today_stats: <?php echo json_encode($view['today_stats'] ?? []); ?>,
    trend_data: <?php echo json_encode($view['trend_data'] ?? []); ?>,
    affected_users: <?php echo $view['affected_users'] ?? 0; ?>
};

// 페이지 로드 시 실행
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    
    // 초기 데이터가 있으면 표시
    if (initialData.today_stats.length > 0 || initialData.trend_data.length > 0) {
        updateDashboard(initialData);
    } else {
        // 없으면 AJAX로 로드
        loadDashboardData();
    }
    
    // 5분마다 자동 새로고침
    setInterval(loadDashboardData, 300000);
});
</script>

<!-- 실시간 모니터링 알림 -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">실시간 로그 모니터링 설정</h4>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <h5><i class="fa fa-info-circle"></i> 실시간 로그 캐치 시스템이 활성화되었습니다!</h5>
                    <p>이제 critical과 error 레벨의 로그가 자동으로 데이터베이스에 저장됩니다.</p>
                    <hr>
                    <h6>기존 로그 파일 파싱하기:</h6>
                    <p>터미널에서 다음 명령어를 실행하여 기존 로그 파일을 파싱할 수 있습니다:</p>
                    <pre class="m-b-10">php spark logs:parse --days=30</pre>
                    <p>또는 기존 데이터를 모두 삭제하고 새로 파싱:</p>
                    <pre>php spark logs:parse --days=30 --clear</pre>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>자동 새로고침 간격</label>
                            <select id="refreshInterval" class="form-control" onchange="updateRefreshInterval()">
                                <option value="0">사용 안함</option>
                                <option value="10000">10초</option>
                                <option value="30000">30초</option>
                                <option value="60000">1분</option>
                                <option value="300000" selected>5분</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>마지막 업데이트</label>
                            <p class="form-control-static" id="lastUpdate">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var refreshTimer;

function updateRefreshInterval() {
    var interval = parseInt(document.getElementById('refreshInterval').value);
    
    // 기존 타이머 제거
    if (refreshTimer) {
        clearInterval(refreshTimer);
    }
    
    // 새 타이머 설정
    if (interval > 0) {
        refreshTimer = setInterval(function() {
            loadDashboardData();
            document.getElementById('lastUpdate').textContent = new Date().toLocaleString('ko-KR');
        }, interval);
    }
}

// 초기 새로고침 간격 설정
updateRefreshInterval();
</script>