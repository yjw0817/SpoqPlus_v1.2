<?php
/**
 * 기존 얼굴 데이터 품질 분석 도구
 * 재등록이 필요한 회원을 식별하고 전체 데이터 현황을 파악
 */

// CodeIgniter 환경 설정
define('FCPATH', dirname(__FILE__) . '/');
define('APPPATH', FCPATH . '../app/');
define('SYSTEMPATH', FCPATH . '../vendor/codeigniter4/framework/system/');

require FCPATH . '../vendor/autoload.php';

// 데이터베이스 연결
$db = \Config\Database::connect();

// 전체 데이터 현황
$stats = $db->query("
    SELECT 
        COUNT(*) as total_faces,
        SUM(CASE WHEN glasses_detected = 1 THEN 1 ELSE 0 END) as glasses_faces,
        SUM(CASE WHEN quality_score >= 0.8 THEN 1 ELSE 0 END) as high_quality,
        SUM(CASE WHEN quality_score BETWEEN 0.7 AND 0.8 THEN 1 ELSE 0 END) as medium_quality,
        SUM(CASE WHEN quality_score < 0.7 THEN 1 ELSE 0 END) as low_quality,
        SUM(CASE WHEN quality_score IS NULL THEN 1 ELSE 0 END) as no_quality,
        AVG(quality_score) as avg_quality,
        MIN(registered_date) as oldest_registration,
        MAX(registered_date) as newest_registration,
        DATEDIFF(NOW(), MIN(registered_date)) as days_since_oldest
    FROM member_faces
    WHERE is_active = 1
")->getRow();

// 인식 성능별 회원 분석
$memberPerformance = $db->query("
    SELECT 
        mf.mem_sno,
        mf.face_id,
        mf.quality_score,
        mf.glasses_detected,
        mf.registered_date,
        DATEDIFF(NOW(), mf.registered_date) as days_old,
        COUNT(DISTINCT frl.log_id) as total_attempts,
        SUM(CASE WHEN frl.success = 1 THEN 1 ELSE 0 END) as successful_attempts,
        AVG(frl.confidence_score) as avg_confidence,
        MIN(frl.confidence_score) as min_confidence,
        MAX(frl.confidence_score) as max_confidence,
        COUNT(DISTINCT DATE(frl.recognition_time)) as active_days
    FROM member_faces mf
    LEFT JOIN face_recognition_logs frl ON mf.mem_sno = frl.mem_sno
        AND frl.recognition_time >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    WHERE mf.is_active = 1
    GROUP BY mf.face_id
    HAVING total_attempts > 0
    ORDER BY (successful_attempts / total_attempts) ASC, total_attempts DESC
")->getResultArray();

// 재등록 권장 기준
$reregisterCriteria = [
    'low_success_rate' => 0.7,      // 성공률 70% 미만
    'low_confidence' => 0.7,         // 평균 신뢰도 0.7 미만
    'min_attempts' => 5,             // 최소 시도 횟수
    'old_registration' => 180        // 180일 이상 된 등록
];

// 재등록 권장 대상 분류
$reregisterCandidates = [];
$performanceGroups = [
    'excellent' => [],
    'good' => [],
    'fair' => [],
    'poor' => [],
    'critical' => []
];

foreach ($memberPerformance as $member) {
    $successRate = $member['total_attempts'] > 0 ? 
        $member['successful_attempts'] / $member['total_attempts'] : 0;
    
    $needsReregister = false;
    $reasons = [];
    
    // 재등록 필요 여부 판단
    if ($successRate < $reregisterCriteria['low_success_rate'] && 
        $member['total_attempts'] >= $reregisterCriteria['min_attempts']) {
        $needsReregister = true;
        $reasons[] = sprintf("낮은 성공률 (%.1f%%)", $successRate * 100);
    }
    
    if ($member['avg_confidence'] < $reregisterCriteria['low_confidence']) {
        $needsReregister = true;
        $reasons[] = sprintf("낮은 평균 신뢰도 (%.3f)", $member['avg_confidence']);
    }
    
    if ($member['days_old'] > $reregisterCriteria['old_registration'] && $successRate < 0.8) {
        $needsReregister = true;
        $reasons[] = sprintf("%d일 경과한 오래된 등록", $member['days_old']);
    }
    
    if ($needsReregister) {
        $member['success_rate'] = $successRate;
        $member['reasons'] = $reasons;
        $reregisterCandidates[] = $member;
    }
    
    // 성능 그룹 분류
    if ($successRate >= 0.95) {
        $performanceGroups['excellent'][] = $member;
    } elseif ($successRate >= 0.85) {
        $performanceGroups['good'][] = $member;
    } elseif ($successRate >= 0.70) {
        $performanceGroups['fair'][] = $member;
    } elseif ($successRate >= 0.50) {
        $performanceGroups['poor'][] = $member;
    } else {
        $performanceGroups['critical'][] = $member;
    }
}

// 안경 착용 변화 분석
$glassesAnalysis = $db->query("
    SELECT 
        mf.mem_sno,
        mf.glasses_detected as registered_glasses,
        SUM(CASE WHEN frl.glasses_detected = mf.glasses_detected THEN 1 ELSE 0 END) as same_condition,
        SUM(CASE WHEN frl.glasses_detected != mf.glasses_detected THEN 1 ELSE 0 END) as different_condition,
        AVG(CASE WHEN frl.glasses_detected = mf.glasses_detected THEN frl.confidence_score END) as same_avg_confidence,
        AVG(CASE WHEN frl.glasses_detected != mf.glasses_detected THEN frl.confidence_score END) as diff_avg_confidence
    FROM member_faces mf
    JOIN face_recognition_logs frl ON mf.mem_sno = frl.mem_sno
    WHERE mf.is_active = 1
    AND frl.recognition_time >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY mf.mem_sno
    HAVING different_condition > 0
")->getResultArray();

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>얼굴 데이터 품질 분석</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .quality-badge {
            display: inline-block;
            padding: 0.25em 0.6em;
            font-size: 0.875rem;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
        }
        .quality-high { background-color: #28a745; color: white; }
        .quality-medium { background-color: #ffc107; color: black; }
        .quality-low { background-color: #dc3545; color: white; }
        .quality-none { background-color: #6c757d; color: white; }
        
        .performance-excellent { color: #28a745; }
        .performance-good { color: #17a2b8; }
        .performance-fair { color: #ffc107; }
        .performance-poor { color: #fd7e14; }
        .performance-critical { color: #dc3545; }
        
        .recommendation-box {
            background-color: #f8f9fa;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 15px 0;
        }
        
        .data-card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.2s;
        }
        .data-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <h1 class="mb-4"><i class="bi bi-bar-chart-line"></i> 얼굴 데이터 품질 분석</h1>
        
        <!-- 전체 현황 요약 -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">전체 데이터 현황</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h5>총 등록 얼굴</h5>
                                <h2><?= number_format($stats->total_faces) ?></h2>
                                <small class="text-muted">안경 착용: <?= number_format($stats->glasses_faces) ?>명</small>
                            </div>
                            <div class="col-md-3">
                                <h5>품질 분포</h5>
                                <div class="mb-1">
                                    <span class="quality-badge quality-high">높음</span> 
                                    <?= number_format($stats->high_quality) ?>명 
                                    (<?= $stats->total_faces > 0 ? number_format($stats->high_quality / $stats->total_faces * 100, 1) : 0 ?>%)
                                </div>
                                <div class="mb-1">
                                    <span class="quality-badge quality-medium">중간</span> 
                                    <?= number_format($stats->medium_quality) ?>명 
                                    (<?= $stats->total_faces > 0 ? number_format($stats->medium_quality / $stats->total_faces * 100, 1) : 0 ?>%)
                                </div>
                                <div class="mb-1">
                                    <span class="quality-badge quality-low">낮음</span> 
                                    <?= number_format($stats->low_quality) ?>명 
                                    (<?= $stats->total_faces > 0 ? number_format($stats->low_quality / $stats->total_faces * 100, 1) : 0 ?>%)
                                </div>
                                <?php if ($stats->no_quality > 0): ?>
                                <div>
                                    <span class="quality-badge quality-none">미측정</span> 
                                    <?= number_format($stats->no_quality) ?>명
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3">
                                <h5>평균 품질 점수</h5>
                                <h2><?= number_format($stats->avg_quality ?? 0, 3) ?></h2>
                                <small class="text-muted">목표: 0.800 이상</small>
                            </div>
                            <div class="col-md-3">
                                <h5>데이터 연령</h5>
                                <p>가장 오래된 등록: <strong><?= $stats->days_since_oldest ?>일 전</strong></p>
                                <small class="text-muted">
                                    <?= date('Y-m-d', strtotime($stats->oldest_registration)) ?> ~ 
                                    <?= date('Y-m-d', strtotime($stats->newest_registration)) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 재등록 권장 -->
        <?php if (!empty($reregisterCandidates)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning">
                        <h4 class="mb-0"><i class="bi bi-exclamation-triangle"></i> 재등록 권장 대상</h4>
                    </div>
                    <div class="card-body">
                        <p>총 <strong><?= count($reregisterCandidates) ?>명</strong>의 회원이 재등록을 통해 인식률을 개선할 수 있습니다.</p>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>회원번호</th>
                                        <th>성공률</th>
                                        <th>평균 신뢰도</th>
                                        <th>시도 횟수</th>
                                        <th>등록일</th>
                                        <th>재등록 사유</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($reregisterCandidates, 0, 20) as $candidate): ?>
                                    <tr>
                                        <td><?= $candidate['mem_sno'] ?></td>
                                        <td>
                                            <span class="<?= $candidate['success_rate'] < 0.5 ? 'text-danger' : 'text-warning' ?>">
                                                <?= number_format($candidate['success_rate'] * 100, 1) ?>%
                                            </span>
                                        </td>
                                        <td><?= number_format($candidate['avg_confidence'], 3) ?></td>
                                        <td><?= $candidate['total_attempts'] ?>회</td>
                                        <td><?= $candidate['days_old'] ?>일 전</td>
                                        <td>
                                            <?php foreach ($candidate['reasons'] as $reason): ?>
                                            <small class="d-block"><?= $reason ?></small>
                                            <?php endforeach; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php if (count($reregisterCandidates) > 20): ?>
                            <p class="text-muted">... 외 <?= count($reregisterCandidates) - 20 ?>명</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- 성능 그룹별 분석 -->
        <div class="row mb-4">
            <div class="col-12">
                <h3>성능 그룹별 분포</h3>
                <div class="row">
                    <?php 
                    $groupInfo = [
                        'excellent' => ['label' => '우수', 'desc' => '95% 이상', 'icon' => 'star-fill'],
                        'good' => ['label' => '양호', 'desc' => '85-95%', 'icon' => 'star-half'],
                        'fair' => ['label' => '보통', 'desc' => '70-85%', 'icon' => 'circle-fill'],
                        'poor' => ['label' => '미흡', 'desc' => '50-70%', 'icon' => 'exclamation-circle'],
                        'critical' => ['label' => '위험', 'desc' => '50% 미만', 'icon' => 'x-circle-fill']
                    ];
                    ?>
                    <?php foreach ($performanceGroups as $group => $members): ?>
                    <div class="col-md-2 mb-3">
                        <div class="card data-card h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-<?= $groupInfo[$group]['icon'] ?> performance-<?= $group ?>" style="font-size: 2rem;"></i>
                                <h5 class="mt-2"><?= $groupInfo[$group]['label'] ?></h5>
                                <h3><?= count($members) ?></h3>
                                <small class="text-muted"><?= $groupInfo[$group]['desc'] ?></small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- 안경 착용 변화 분석 -->
        <?php if (!empty($glassesAnalysis)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <h3>안경 착용 변화 영향</h3>
                <div class="card">
                    <div class="card-body">
                        <p>안경 착용 상태가 변화한 회원들의 인식 성능 분석</p>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>회원번호</th>
                                    <th>등록 시 안경</th>
                                    <th>동일 조건 인식</th>
                                    <th>다른 조건 인식</th>
                                    <th>동일 조건 신뢰도</th>
                                    <th>다른 조건 신뢰도</th>
                                    <th>신뢰도 차이</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($glassesAnalysis, 0, 10) as $analysis): ?>
                                <tr>
                                    <td><?= $analysis['mem_sno'] ?></td>
                                    <td><?= $analysis['registered_glasses'] ? '착용' : '미착용' ?></td>
                                    <td><?= $analysis['same_condition'] ?>회</td>
                                    <td><?= $analysis['different_condition'] ?>회</td>
                                    <td><?= number_format($analysis['same_avg_confidence'], 3) ?></td>
                                    <td><?= number_format($analysis['diff_avg_confidence'], 3) ?></td>
                                    <td>
                                        <span class="<?= ($analysis['same_avg_confidence'] - $analysis['diff_avg_confidence']) > 0.1 ? 'text-danger' : '' ?>">
                                            <?= number_format($analysis['same_avg_confidence'] - $analysis['diff_avg_confidence'], 3) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- 권장사항 -->
        <div class="row">
            <div class="col-12">
                <div class="recommendation-box">
                    <h4><i class="bi bi-lightbulb"></i> 데이터 품질 개선 권장사항</h4>
                    <ol>
                        <li><strong>즉시 조치 필요:</strong>
                            <ul>
                                <li>임계값을 0.85에서 0.75로 조정하여 즉시 인식률 개선</li>
                                <li>안경 교차 매칭 기능 활성화</li>
                                <li>데이터베이스 인덱스 최적화</li>
                            </ul>
                        </li>
                        <li><strong>단기 개선 (1주):</strong>
                            <ul>
                                <li><?= count($reregisterCandidates) ?>명의 재등록 권장 대상에게 안내 발송</li>
                                <li>성공률 50% 미만인 <?= count($performanceGroups['critical']) ?>명 우선 처리</li>
                            </ul>
                        </li>
                        <li><strong>장기 개선 (1개월):</strong>
                            <ul>
                                <li>180일 이상 경과한 등록 데이터 점진적 갱신</li>
                                <li>다중 조건 등록 시스템 도입 (안경 착용/미착용 별도 등록)</li>
                            </ul>
                        </li>
                    </ol>
                    
                    <div class="alert alert-success mt-3">
                        <strong>중요:</strong> 기존 데이터를 삭제할 필요 없이 알고리즘 개선만으로 
                        약 <strong>10-15%</strong>의 즉각적인 성능 향상이 예상됩니다.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // 자동 새로고침 비활성화 (분석 페이지는 수동 새로고침 권장)
    console.log('데이터 분석 완료. 총 <?= count($memberPerformance) ?>명의 회원 데이터 분석됨.');
    </script>
</body>
</html>