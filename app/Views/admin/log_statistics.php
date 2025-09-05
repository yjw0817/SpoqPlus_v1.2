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
.bg-gradient-success {
    background: linear-gradient(to right, #28a745, #1e7e34) !important;
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
.chart-container {
    position: relative;
    height: 300px;
    margin-bottom: 20px;
}
.period-selector {
    margin-bottom: 20px;
}
.comparison-table td {
    vertical-align: middle;
}
.trend-up {
    color: #dc3545;
}
.trend-down {
    color: #28a745;
}
</style>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- 페이지 헤더 -->
        <h1 class="page-header">로그 통계</h1>
        <!-- 기간 선택 -->
        <div class="row period-selector">
            <div class="col-md-12">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary active" onclick="changePeriod('today')">오늘</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="changePeriod('week')">7일</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="changePeriod('month')">30일</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="changePeriod('quarter')">90일</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="changePeriod('custom')">커스텀</button>
                </div>
                
                <div class="float-right">
                    <button class="btn btn-sm btn-success" onclick="exportReport()">
                        <i class="fas fa-file-excel"></i> 리포트 다운로드
                    </button>
                </div>
            </div>
        </div>

        <!-- 주요 통계 -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-danger">
                        <i class="fas fa-exclamation-circle"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">전체 오류</span>
                        <span class="info-box-number" id="total-errors">0</span>
                        <span class="progress-description">
                            <span id="error-trend"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-success">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">해결률</span>
                        <span class="info-box-number" id="resolution-rate">0%</span>
                        <span class="progress-description">
                            <span id="resolution-trend"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-warning">
                        <i class="fas fa-clock"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">평균 해결 시간</span>
                        <span class="info-box-number" id="avg-resolution-time">0h</span>
                        <span class="progress-description">
                            <span id="time-trend"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-info">
                        <i class="fas fa-server"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">영향받은 시스템</span>
                        <span class="info-box-number" id="affected-systems">0</span>
                        <span class="progress-description">
                            <span id="system-trend"></span>
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
                        <div class="panel-heading-btn">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-default btn-xs active" onclick="changeChartType('line')">라인</button>
                                <button type="button" class="btn btn-default btn-xs" onclick="changeChartType('bar')">막대</button>
                                <button type="button" class="btn btn-default btn-xs" onclick="changeChartType('area')">영역</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="chart-container">
                            <canvas id="errorTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">오류 유형별 분포</h4>
                    </div>
                    <div class="panel-body">
                        <div class="chart-container">
                            <canvas id="errorTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- 시간대별 분석 -->
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">시간대별 오류 발생</h4>
                    </div>
                    <div class="panel-body">
                        <div class="chart-container">
                            <canvas id="hourlyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 사용자별 영향도 -->
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">사용자별 오류 영향도</h4>
                    </div>
                    <div class="panel-body">
                        <div class="chart-container">
                            <canvas id="userImpactChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 상세 분석 테이블 -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">상세 통계 분석</h4>
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand">
                                <i class="fa fa-expand"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-collapse">
                                <i class="fa fa-minus"></i>
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered comparison-table">
                            <thead>
                                <tr>
                                    <th>항목</th>
                                    <th>현재 기간</th>
                                    <th>이전 기간</th>
                                    <th>변화율</th>
                                    <th>트렌드</th>
                                </tr>
                            </thead>
                            <tbody id="comparisonTable">
                                <tr>
                                    <td>총 오류 수</td>
                                    <td class="current-value">0</td>
                                    <td class="previous-value">0</td>
                                    <td class="change-rate">0%</td>
                                    <td class="trend-icon">-</td>
                                </tr>
                                <tr>
                                    <td>Critical 오류</td>
                                    <td class="current-value">0</td>
                                    <td class="previous-value">0</td>
                                    <td class="change-rate">0%</td>
                                    <td class="trend-icon">-</td>
                                </tr>
                                <tr>
                                    <td>평균 해결 시간</td>
                                    <td class="current-value">0h</td>
                                    <td class="previous-value">0h</td>
                                    <td class="change-rate">0%</td>
                                    <td class="trend-icon">-</td>
                                </tr>
                                <tr>
                                    <td>영향받은 사용자</td>
                                    <td class="current-value">0</td>
                                    <td class="previous-value">0</td>
                                    <td class="change-rate">0%</td>
                                    <td class="trend-icon">-</td>
                                </tr>
                                <tr>
                                    <td>해결률</td>
                                    <td class="current-value">0%</td>
                                    <td class="previous-value">0%</td>
                                    <td class="change-rate">0%</td>
                                    <td class="trend-icon">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOP 문제 영역 -->
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">가장 많이 발생한 오류 TOP 5</h4>
                    </div>
                    <div class="panel-body">
                        <div id="topErrors" class="list-group"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">가장 오래 해결되지 않은 오류 TOP 5</h4>
                    </div>
                    <div class="panel-body">
                        <div id="longestUnresolved" class="list-group"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 커스텀 기간 모달 -->
<div class="modal fade" id="customPeriodModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">커스텀 기간 설정</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="customPeriodForm">
                    <div class="form-group">
                        <label>시작 날짜</label>
                        <input type="date" class="form-control" id="custom_start_date" required>
                    </div>
                    <div class="form-group">
                        <label>종료 날짜</label>
                        <input type="date" class="form-control" id="custom_end_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                <button type="button" class="btn btn-primary" onclick="applyCustomPeriod()">적용</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var currentPeriod = 'today';
var charts = {};

// 차트 초기화
function initializeCharts() {
    // 오류 추이 차트
    var ctx1 = document.getElementById('errorTrendChart').getContext('2d');
    charts.errorTrend = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'CRITICAL',
                data: [],
                borderColor: '#dc3545',
                backgroundColor: '#dc354520',
                tension: 0.1
            }, {
                label: 'ERROR',
                data: [],
                borderColor: '#fd7e14',
                backgroundColor: '#fd7e1420',
                tension: 0.1
            }, {
                label: 'WARNING',
                data: [],
                borderColor: '#ffc107',
                backgroundColor: '#ffc10720',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // 오류 유형별 분포 차트
    var ctx2 = document.getElementById('errorTypeChart').getContext('2d');
    charts.errorType = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: ['#dc3545', '#fd7e14', '#ffc107', '#0dcaf0']
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

    // 시간대별 차트
    var ctx3 = document.getElementById('hourlyChart').getContext('2d');
    charts.hourly = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: Array.from({length: 24}, (_, i) => i + '시'),
            datasets: [{
                label: '오류 발생 수',
                data: [],
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // 사용자 영향도 차트
    var ctx4 = document.getElementById('userImpactChart').getContext('2d');
    charts.userImpact = new Chart(ctx4, {
        type: 'horizontalBar',
        data: {
            labels: [],
            datasets: [{
                label: '오류 횟수',
                data: [],
                backgroundColor: '#17a2b8'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });
}

// 기간 변경
function changePeriod(period) {
    if (period === 'custom') {
        $('#customPeriodModal').modal('show');
        return;
    }
    
    currentPeriod = period;
    document.querySelectorAll('.period-selector .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    loadStatistics();
}

// 커스텀 기간 적용
function applyCustomPeriod() {
    var startDate = document.getElementById('custom_start_date').value;
    var endDate = document.getElementById('custom_end_date').value;
    
    if (startDate && endDate) {
        currentPeriod = 'custom';
        loadStatistics(startDate, endDate);
        $('#customPeriodModal').modal('hide');
    }
}

// 통계 데이터 로드
function loadStatistics(startDate, endDate) {
    var params = {
        period: currentPeriod
    };
    
    if (startDate && endDate) {
        params.start_date = startDate;
        params.end_date = endDate;
    }
    
    fetch('/adminmain/getLogStatistics', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(params)
    })
    .then(response => response.json())
    .then(data => {
        updateStatistics(data);
        updateCharts(data);
        updateTables(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// 통계 업데이트
function updateStatistics(data) {
    // 주요 통계 업데이트
    document.getElementById('total-errors').textContent = data.summary.total_errors || 0;
    document.getElementById('resolution-rate').textContent = (data.summary.resolution_rate || 0) + '%';
    document.getElementById('avg-resolution-time').textContent = formatTime(data.summary.avg_resolution_time || 0);
    document.getElementById('affected-systems').textContent = data.summary.affected_systems || 0;
    
    // 트렌드 표시
    updateTrend('error-trend', data.trends.error_trend);
    updateTrend('resolution-trend', data.trends.resolution_trend);
    updateTrend('time-trend', data.trends.time_trend);
    updateTrend('system-trend', data.trends.system_trend);
}

// 트렌드 업데이트
function updateTrend(elementId, trend) {
    var element = document.getElementById(elementId);
    if (trend > 0) {
        element.innerHTML = '<i class="fas fa-arrow-up trend-up"></i> +' + trend + '%';
    } else if (trend < 0) {
        element.innerHTML = '<i class="fas fa-arrow-down trend-down"></i> ' + trend + '%';
    } else {
        element.innerHTML = '<i class="fas fa-minus"></i> 0%';
    }
}

// 차트 업데이트
function updateCharts(data) {
    // 오류 추이 차트
    if (data.trends.daily) {
        charts.errorTrend.data.labels = data.trends.daily.map(d => d.date);
        charts.errorTrend.data.datasets[0].data = data.trends.daily.map(d => d.critical || 0);
        charts.errorTrend.data.datasets[1].data = data.trends.daily.map(d => d.error || 0);
        charts.errorTrend.data.datasets[2].data = data.trends.daily.map(d => d.warning || 0);
        charts.errorTrend.update();
    }
    
    // 오류 유형별 분포
    if (data.distribution) {
        charts.errorType.data.labels = Object.keys(data.distribution);
        charts.errorType.data.datasets[0].data = Object.values(data.distribution);
        charts.errorType.update();
    }
    
    // 시간대별 차트
    if (data.hourly) {
        charts.hourly.data.datasets[0].data = data.hourly;
        charts.hourly.update();
    }
    
    // 사용자 영향도 차트
    if (data.user_impact) {
        charts.userImpact.data.labels = data.user_impact.map(u => u.user_name || u.user_id);
        charts.userImpact.data.datasets[0].data = data.user_impact.map(u => u.error_count);
        charts.userImpact.update();
    }
}

// 테이블 업데이트
function updateTables(data) {
    // 비교 테이블 업데이트
    if (data.comparison) {
        updateComparisonRow(0, data.comparison.total_errors);
        updateComparisonRow(1, data.comparison.critical_errors);
        updateComparisonRow(2, data.comparison.avg_resolution_time);
        updateComparisonRow(3, data.comparison.affected_users);
        updateComparisonRow(4, data.comparison.resolution_rate);
    }
    
    // TOP 오류 업데이트
    if (data.top_errors) {
        var topErrorsHtml = '';
        data.top_errors.forEach(function(error, index) {
            topErrorsHtml += '<a href="/adminmain/log_analysis/' + error.id + '" class="list-group-item list-group-item-action">';
            topErrorsHtml += '<div class="d-flex w-100 justify-content-between">';
            topErrorsHtml += '<h6 class="mb-1">' + (index + 1) + '. ' + escapeHtml(error.error_message.substring(0, 50)) + '...</h6>';
            topErrorsHtml += '<small>' + error.count + '회</small>';
            topErrorsHtml += '</div>';
            topErrorsHtml += '</a>';
        });
        document.getElementById('topErrors').innerHTML = topErrorsHtml;
    }
    
    // 미해결 오류 업데이트
    if (data.longest_unresolved) {
        var unresolvedHtml = '';
        data.longest_unresolved.forEach(function(error, index) {
            unresolvedHtml += '<a href="/adminmain/log_analysis/' + error.id + '" class="list-group-item list-group-item-action">';
            unresolvedHtml += '<div class="d-flex w-100 justify-content-between">';
            unresolvedHtml += '<h6 class="mb-1">' + (index + 1) + '. ' + escapeHtml(error.error_message.substring(0, 50)) + '...</h6>';
            unresolvedHtml += '<small>' + formatDays(error.days_unresolved) + '</small>';
            unresolvedHtml += '</div>';
            unresolvedHtml += '</a>';
        });
        document.getElementById('longestUnresolved').innerHTML = unresolvedHtml;
    }
}

// 비교 테이블 행 업데이트
function updateComparisonRow(index, data) {
    var row = document.querySelector('#comparisonTable tr:nth-child(' + (index + 1) + ')');
    row.querySelector('.current-value').textContent = data.current;
    row.querySelector('.previous-value').textContent = data.previous;
    row.querySelector('.change-rate').textContent = data.change_rate + '%';
    
    var trendIcon = row.querySelector('.trend-icon');
    if (data.change_rate > 0) {
        trendIcon.innerHTML = '<i class="fas fa-arrow-up trend-up"></i>';
    } else if (data.change_rate < 0) {
        trendIcon.innerHTML = '<i class="fas fa-arrow-down trend-down"></i>';
    } else {
        trendIcon.innerHTML = '<i class="fas fa-minus"></i>';
    }
}

// 차트 타입 변경
function changeChartType(type) {
    charts.errorTrend.config.type = type === 'area' ? 'line' : type;
    if (type === 'area') {
        charts.errorTrend.data.datasets.forEach(dataset => {
            dataset.fill = true;
        });
    } else {
        charts.errorTrend.data.datasets.forEach(dataset => {
            dataset.fill = false;
        });
    }
    charts.errorTrend.update();
    
    document.querySelectorAll('.panel-heading-btn .btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
}

// 리포트 다운로드
function exportReport() {
    var params = new URLSearchParams({
        period: currentPeriod,
        format: 'excel'
    });
    
    window.location.href = '/adminmain/exportLogStatistics?' + params.toString();
}

// 시간 포맷
function formatTime(minutes) {
    if (minutes < 60) {
        return minutes + '분';
    } else if (minutes < 1440) {
        return Math.round(minutes / 60) + '시간';
    } else {
        return Math.round(minutes / 1440) + '일';
    }
}

// 날짜 포맷
function formatDays(days) {
    if (days === 0) {
        return '오늘';
    } else if (days === 1) {
        return '1일 전';
    } else {
        return days + '일 전';
    }
}

// HTML 이스케이프
function escapeHtml(text) {
    if (!text) return '';
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// 페이지 로드 시 실행
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    loadStatistics();
    
    // 자동 새로고침 (5분마다)
    setInterval(function() {
        loadStatistics();
    }, 300000);
});
</script>