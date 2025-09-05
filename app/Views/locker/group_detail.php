<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<h1 class="page-header"><?php echo $title ?></h1>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <div class="panel-title">
            <?= $floor['floor_nm'] ?> - <?= $zone['zone_nm'] ?> - <?= $group['group_nm'] ?>
            <span class="badge bg-<?= $zone['zone_gendr'] === 'M' ? 'primary' : ($zone['zone_gendr'] === 'F' ? 'pink' : 'success') ?>">
                <?= $zone['zone_gendr'] === 'M' ? '남성 전용' : ($zone['zone_gendr'] === 'F' ? '여성 전용' : '혼용') ?>
            </span>
        </div>
        <div class="panel-heading-btn">
            <a href="<?= site_url('locker/zone_detail/' . $zone['zone_sno']) ?>" class="btn btn-sm btn-default">
                <i class="fas fa-arrow-left"></i> 구역으로 돌아가기
            </a>
        </div>
    </div>
    
    <div class="panel-body">
        <!-- 락커 정보 -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">락커 그룹 정보</h5>
                        <p class="mb-1">
                            <strong>크기:</strong> 
                            <?= $group['rows'] ?>행 × <?= $group['cols'] ?>열 × <?= $group['levels'] ?>단
                        </p>
                        <p class="mb-1">
                            <strong>락커 규격:</strong><br>
                            가로 <?= $group['locker_width'] ?>cm ×
                            세로 <?= $group['locker_height'] ?>cm ×
                            높이 <?= $group['level_height'] ?>cm ×
                            깊이 <?= $group['locker_depth'] ?>cm
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">사용 현황</h5>
                        <?php
                        $total = count($lockers);
                        $used = array_filter($lockers, function($locker) { return $locker['status'] === 'U'; });
                        $disabled = array_filter($lockers, function($locker) { return $locker['status'] === 'D'; });
                        $available = array_filter($lockers, function($locker) { return $locker['status'] === 'A'; });
                        ?>
                        <div class="progress mb-2" style="height: 20px;">
                            <div class="progress-bar bg-success" style="width: <?= ($total > 0 ? count($available) / $total * 100 : 0) ?>%">
                                <?= count($available) ?>
                            </div>
                            <div class="progress-bar bg-danger" style="width: <?= ($total > 0 ? count($used) / $total * 100 : 0) ?>%">
                                <?= count($used) ?>
                            </div>
                            <div class="progress-bar bg-secondary" style="width: <?= ($total > 0 ? count($disabled) / $total * 100 : 0) ?>%">
                                <?= count($disabled) ?>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col">
                                <small class="text-success">사용가능: <?= count($available) ?></small>
                            </div>
                            <div class="col">
                                <small class="text-danger">사용중: <?= count($used) ?></small>
                            </div>
                            <div class="col">
                                <small class="text-secondary">사용불가: <?= count($disabled) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 락커 배치도 -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">락커 배치도</h5>
                <div class="mb-2">
                    <div class="btn-group">
                        <?php for ($level = 0; $level < $group['levels']; $level++): ?>
                        <button class="btn btn-outline-primary level-btn<?= $level === 0 ? ' active' : '' ?>" data-level="<?= $level ?>">
                            <?= chr(65 + $level) ?>단
                        </button>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="locker-grid">
                    <?php for ($level = 0; $level < $group['levels']; $level++): ?>
                    <div class="level-container<?= $level === 0 ? ' active' : '' ?>" data-level="<?= $level ?>">
                        <div class="grid" style="grid-template-columns: repeat(<?= $group['cols'] ?>, 1fr);">
                            <?php
                            $levelLockers = array_filter($lockers, function($locker) use ($level) {
                                return $locker['level_idx'] == $level;
                            });
                            usort($levelLockers, function($a, $b) {
                                if ($a['row_idx'] === $b['row_idx']) {
                                    return $a['col_idx'] - $b['col_idx'];
                                }
                                return $a['row_idx'] - $b['row_idx'];
                            });
                            foreach ($levelLockers as $locker):
                            ?>
                            <div class="locker <?= $locker['status'] ?>" data-locker="<?= $locker['locker_sno'] ?>">
                                <div class="locker-content">
                                    <div class="locker-no"><?= $locker['locker_no'] ?></div>
                                    <div class="locker-status">
                                        <?= $locker['status'] === 'A' ? '사용가능' : ($locker['status'] === 'U' ? '사용중' : '사용불가') ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.badge.bg-pink {
    background-color: #ff69b4 !important;
}

.locker-grid {
    position: relative;
    width: 100%;
    overflow-x: auto;
}

.level-container {
    display: none;
}

.level-container.active {
    display: block;
}

.grid {
    display: grid;
    gap: 10px;
    padding: 10px;
}

.locker {
    aspect-ratio: 1;
    border: 2px solid #ccc;
    border-radius: 4px;
    padding: 5px;
    cursor: pointer;
    transition: all 0.2s;
}

.locker:hover {
    transform: scale(1.05);
}

.locker.A {
    background-color: #d4edda;
    border-color: #28a745;
}

.locker.U {
    background-color: #f8d7da;
    border-color: #dc3545;
}

.locker.D {
    background-color: #e9ecef;
    border-color: #6c757d;
}

.locker-content {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.locker-no {
    font-weight: bold;
    margin-bottom: 5px;
}

.locker-status {
    font-size: 12px;
}
</style>

<script>
$(document).ready(function() {
    // 단수 버튼 클릭 이벤트
    $('.level-btn').on('click', function() {
        const level = $(this).data('level');
        $('.level-btn').removeClass('active');
        $(this).addClass('active');
        $('.level-container').removeClass('active');
        $(`.level-container[data-level="${level}"]`).addClass('active');
    });

    // 락커 클릭 이벤트
    $('.locker').on('click', function() {
        const locker_sno = $(this).data('locker');
        // TODO: 락커 상세 정보 표시 또는 상태 변경
    });
});
</script>

<?= $jsinc ?> 