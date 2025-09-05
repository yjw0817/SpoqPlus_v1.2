<?php
/**
 * 얼굴 인식 정확도 분석 및 개선 도구
 * 실시간 성능 모니터링과 임계값 최적화 추천
 */

// CodeIgniter 환경 설정
define('FCPATH', dirname(__FILE__) . '/');
define('APPPATH', FCPATH . '../app/');
define('SYSTEMPATH', FCPATH . '../vendor/codeigniter4/framework/system/');

require FCPATH . '../vendor/autoload.php';

// 데이터베이스 연결
$db = \Config\Database::connect();

// 분석 기간 설정
$period = $_GET['period'] ?? '24h';
$startDate = match($period) {
    '1h' => date('Y-m-d H:i:s', strtotime('-1 hour')),
    '24h' => date('Y-m-d H:i:s', strtotime('-24 hours')),
    '7d' => date('Y-m-d H:i:s', strtotime('-7 days')),
    '30d' => date('Y-m-d H:i:s', strtotime('-30 days')),
    default => date('Y-m-d H:i:s', strtotime('-24 hours'))
};

// 전체 통계 조회
$stats = $db->query("
    SELECT 
        COUNT(*) as total_attempts,
        SUM(success) as successful_attempts,
        AVG(confidence_score) as avg_confidence,
        MIN(confidence_score) as min_confidence,
        MAX(confidence_score) as max_confidence,
        STD(confidence_score) as std_confidence,
        AVG(processing_time_ms) as avg_processing_time,
        SUM(CASE WHEN glasses_detected = 1 THEN 1 ELSE 0 END) as glasses_count,
        SUM(CASE WHEN success = 1 AND glasses_detected = 1 THEN 1 ELSE 0 END) as glasses_success
    FROM face_recognition_logs
    WHERE recognition_time >= ?
", [$startDate])->getRow();

// 신뢰도 분포 분석
$confidenceDistribution = $db->query("
    SELECT 
        CASE 
            WHEN confidence_score >= 0.85 THEN 'high'
            WHEN confidence_score >= 0.75 THEN 'medium'
            WHEN confidence_score >= 0.70 THEN 'low'
            ELSE 'very_low'
        END as confidence_level,
        COUNT(*) as count,
        SUM(success) as success_count
    FROM face_recognition_logs
    WHERE recognition_time >= ?
    GROUP BY confidence_level
    ORDER BY 
        CASE confidence_level
            WHEN 'high' THEN 1
            WHEN 'medium' THEN 2
            WHEN 'low' THEN 3
            WHEN 'very_low' THEN 4
        END
", [$startDate])->getResultArray();

// 시간대별 분석
$hourlyStats = $db->query("
    SELECT 
        HOUR(recognition_time) as hour,
        COUNT(*) as attempts,
        SUM(success) as successes,
        AVG(confidence_score) as avg_confidence,
        AVG(processing_time_ms) as avg_processing_time
    FROM face_recognition_logs
    WHERE recognition_time >= ?
    GROUP BY HOUR(recognition_time)
    ORDER BY hour
", [$startDate])->getResultArray();

// 경계선 케이스 분석 (0.70-0.75 구간)
$borderlineCases = $db->query("
    SELECT 
        COUNT(*) as total_borderline,
        SUM(success) as borderline_success,
        AVG(confidence_score) as avg_borderline_score,
        SUM(CASE WHEN glasses_detected = 1 THEN 1 ELSE 0 END) as borderline_glasses
    FROM face_recognition_logs
    WHERE recognition_time >= ?
    AND confidence_score BETWEEN 0.70 AND 0.75
", [$startDate])->getRow();

// 실패 원인 분석
$failureAnalysis = $db->query("
    SELECT 
        error_message,
        COUNT(*) as count,
        AVG(confidence_score) as avg_score
    FROM face_recognition_logs
    WHERE recognition_time >= ?
    AND success = 0
    AND error_message IS NOT NULL
    GROUP BY error_message
    ORDER BY count DESC
    LIMIT 10
", [$startDate])->getResultArray();

// 계산된 지표
$successRate = $stats->total_attempts > 0 ? ($stats->successful_attempts / $stats->total_attempts) * 100 : 0;
$glassesSuccessRate = $stats->glasses_count > 0 ? ($stats->glasses_success / $stats->glasses_count) * 100 : 0;
$borderlineRate = $stats->total_attempts > 0 ? ($borderlineCases->total_borderline / $stats->total_attempts) * 100 : 0;

// 추천사항 생성
$recommendations = [];

if ($successRate < 80) {
    $recommendations[] = [
        'type' => 'critical',
        'title' => '낮은 성공률',
        'message' => sprintf('현재 성공률이 %.1f%%로 목표치(80%%)보다 낮습니다.', $successRate),
        'action' => '임계값을 0.75에서 0.70으로 낮추는 것을 권장합니다.'
    ];
}

if ($borderlineRate > 20) {
    $recommendations[] = [
        'type' => 'warning',
        'title' => '높은 경계선 비율',
        'message' => sprintf('경계선 케이스가 %.1f%%로 너무 많습니다.', $borderlineRate),
        'action' => '적응형 임계값 시스템 도입을 권장합니다.'
    ];
}

if ($glassesSuccessRate < 70 && $stats->glasses_count > 10) {
    $recommendations[] = [
        'type' => 'warning',
        'title' => '안경 착용자 인식률 저하',
        'message' => sprintf('안경 착용자 성공률이 %.1f%%로 낮습니다.', $glassesSuccessRate),
        'action' => '안경 교차 매칭 기능을 활성화하세요.'
    ];
}

if ($stats->avg_processing_time > 500) {
    $recommendations[] = [
        'type' => 'info',
        'title' => '처리 시간 개선 필요',
        'message' => sprintf('평균 처리 시간이 %.0fms로 목표(200ms)보다 느립니다.', $stats->avg_processing_time),
        'action' => '이미지 크기 최적화 또는 서버 성능 개선이 필요합니다.'
    ];
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>얼굴 인식 정확도 분석 대시보드</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <style>
        .metric-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .metric-value {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .metric-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .success-rate { color: #28a745; }
        .warning-rate { color: #ffc107; }
        .danger-rate { color: #dc3545; }
        .recommendation-card {
            border-left: 4px solid;
            margin-bottom: 15px;
        }
        .recommendation-card.critical { border-color: #dc3545; }
        .recommendation-card.warning { border-color: #ffc107; }
        .recommendation-card.info { border-color: #17a2b8; }
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row mb-4">
            <div class="col">
                <h1><i class="bi bi-person-bounding-box"></i> 얼굴 인식 정확도 분석</h1>
                <p class="text-muted">실시간 성능 모니터링 및 최적화 추천</p>
            </div>
            <div class="col-auto">
                <select class="form-select" onchange="location.href='?period='+this.value">
                    <option value="1h" <?= $period == '1h' ? 'selected' : '' ?>>최근 1시간</option>
                    <option value="24h" <?= $period == '24h' ? 'selected' : '' ?>>최근 24시간</option>
                    <option value="7d" <?= $period == '7d' ? 'selected' : '' ?>>최근 7일</option>
                    <option value="30d" <?= $period == '30d' ? 'selected' : '' ?>>최근 30일</option>
                </select>
            </div>
        </div>

        <!-- 주요 지표 -->
        <div class="row">
            <div class="col-md-3">
                <div class="metric-card bg-light">
                    <div class="metric-label">전체 성공률</div>
                    <div class="metric-value <?= $successRate >= 80 ? 'success-rate' : ($successRate >= 60 ? 'warning-rate' : 'danger-rate') ?>">
                        <?= number_format($successRate, 1) ?>%
                    </div>
                    <small class="text-muted"><?= number_format($stats->successful_attempts) ?> / <?= number_format($stats->total_attempts) ?></small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card bg-light">
                    <div class="metric-label">평균 신뢰도</div>
                    <div class="metric-value"><?= number_format($stats->avg_confidence, 3) ?></div>
                    <small class="text-muted">표준편차: <?= number_format($stats->std_confidence, 3) ?></small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card bg-light">
                    <div class="metric-label">안경 착용자 성공률</div>
                    <div class="metric-value <?= $glassesSuccessRate >= 70 ? 'success-rate' : 'warning-rate' ?>">
                        <?= number_format($glassesSuccessRate, 1) ?>%
                    </div>
                    <small class="text-muted"><?= number_format($stats->glasses_success) ?> / <?= number_format($stats->glasses_count) ?></small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card bg-light">
                    <div class="metric-label">평균 처리 시간</div>
                    <div class="metric-value"><?= number_format($stats->avg_processing_time, 0) ?>ms</div>
                    <small class="text-muted">목표: 200ms 이하</small>
                </div>
            </div>
        </div>

        <!-- 추천사항 -->
        <?php if (!empty($recommendations)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <h3><i class="bi bi-lightbulb"></i> 개선 추천사항</h3>
                <?php foreach ($recommendations as $rec): ?>
                <div class="card recommendation-card <?= $rec['type'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $rec['title'] ?></h5>
                        <p class="card-text"><?= $rec['message'] ?></p>
                        <p class="card-text"><strong>권장 조치:</strong> <?= $rec['action'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- 차트 -->
        <div class="row mt-4">
            <div class="col-md-6">
                <h4>신뢰도 분포</h4>
                <div class="chart-container">
                    <canvas id="confidenceChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <h4>시간대별 성공률</h4>
                <div class="chart-container">
                    <canvas id="hourlyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 상세 분석 -->
        <div class="row mt-4">
            <div class="col-md-6">
                <h4>경계선 케이스 분석</h4>
                <table class="table">
                    <tr>
                        <td>경계선 케이스 (0.70-0.75)</td>
                        <td><?= number_format($borderlineCases->total_borderline) ?>건 (<?= number_format($borderlineRate, 1) ?>%)</td>
                    </tr>
                    <tr>
                        <td>경계선 성공률</td>
                        <td><?= $borderlineCases->total_borderline > 0 ? number_format(($borderlineCases->borderline_success / $borderlineCases->total_borderline) * 100, 1) : 0 ?>%</td>
                    </tr>
                    <tr>
                        <td>경계선 평균 점수</td>
                        <td><?= number_format($borderlineCases->avg_borderline_score ?? 0, 3) ?></td>
                    </tr>
                    <tr>
                        <td>경계선 안경 착용</td>
                        <td><?= number_format($borderlineCases->borderline_glasses) ?>건</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h4>주요 실패 원인</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>오류 메시지</th>
                            <th>발생 수</th>
                            <th>평균 점수</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($failureAnalysis as $failure): ?>
                        <tr>
                            <td><?= htmlspecialchars($failure['error_message'] ?? '알 수 없음') ?></td>
                            <td><?= number_format($failure['count']) ?></td>
                            <td><?= number_format($failure['avg_score'], 3) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 최적 임계값 제안 -->
        <div class="row mt-4">
            <div class="col-12">
                <h4>최적 임계값 제안</h4>
                <div class="alert alert-info">
                    <h5>현재 설정</h5>
                    <ul>
                        <li>높은 신뢰도: 0.85 이상</li>
                        <li>중간 신뢰도: 0.75 - 0.85</li>
                        <li>낮은 신뢰도: 0.70 - 0.75</li>
                    </ul>
                    
                    <h5>권장 설정 (분석 기반)</h5>
                    <ul>
                        <?php if ($successRate < 80): ?>
                        <li><strong>중간 신뢰도: 0.70으로 하향 조정</strong> (현재 성공률이 낮음)</li>
                        <?php endif; ?>
                        <?php if ($borderlineRate > 20): ?>
                        <li><strong>적응형 임계값 도입</strong> (경계선 케이스 많음)</li>
                        <?php endif; ?>
                        <?php if ($glassesSuccessRate < 70): ?>
                        <li><strong>안경 변화 페널티: 0.95 → 0.90</strong> (안경 인식률 개선)</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
    // 신뢰도 분포 차트
    const confidenceData = <?= json_encode($confidenceDistribution) ?>;
    const confidenceCtx = document.getElementById('confidenceChart').getContext('2d');
    new Chart(confidenceCtx, {
        type: 'bar',
        data: {
            labels: confidenceData.map(d => {
                switch(d.confidence_level) {
                    case 'high': return '높음 (≥0.85)';
                    case 'medium': return '중간 (0.75-0.85)';
                    case 'low': return '낮음 (0.70-0.75)';
                    case 'very_low': return '매우 낮음 (<0.70)';
                }
            }),
            datasets: [{
                label: '전체 시도',
                data: confidenceData.map(d => d.count),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }, {
                label: '성공',
                data: confidenceData.map(d => d.success_count),
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
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

    // 시간대별 성공률 차트
    const hourlyData = <?= json_encode($hourlyStats) ?>;
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    new Chart(hourlyCtx, {
        type: 'line',
        data: {
            labels: hourlyData.map(d => d.hour + '시'),
            datasets: [{
                label: '성공률 (%)',
                data: hourlyData.map(d => d.attempts > 0 ? (d.successes / d.attempts * 100) : 0),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                yAxisID: 'y'
            }, {
                label: '시도 횟수',
                data: hourlyData.map(d => d.attempts),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: '성공률 (%)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: '시도 횟수'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // 자동 새로고침 (5분마다)
    setTimeout(() => location.reload(), 300000);
    </script>
</body>
</html>