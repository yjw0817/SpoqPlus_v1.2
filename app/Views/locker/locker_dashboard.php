<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<h1 class="page-header"><?php echo $title ?></h1>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">락커 현황 대시보드</h4>
    </div>
    
    <div class="panel-body">
        <!-- 전체 현황 요약 -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h4 class="card-title">전체 락커</h4>
                        <h2 id="totalLockers">0</h2>
                        <p class="mb-0">Total Lockers</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h4 class="card-title">사용 가능</h4>
                        <h2 id="availableLockers">0</h2>
                        <p class="mb-0">Available</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h4 class="card-title">사용 중</h4>
                        <h2 id="occupiedLockers">0</h2>
                        <p class="mb-0">Occupied</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h4 class="card-title">점검/예약</h4>
                        <h2 id="otherLockers">0</h2>
                        <p class="mb-0">Maintenance/Reserved</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 층별 현황 -->
        <div class="mb-4">
            <h5>층별 락커 현황</h5>
            <div id="floorStatusContainer"></div>
        </div>

        <!-- 최근 사용 내역 -->
        <div class="mb-4">
            <h5>최근 락커 사용 내역</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>일시</th>
                            <th>락커 번호</th>
                            <th>사용자</th>
                            <th>상태 변경</th>
                            <th>메모</th>
                        </tr>
                    </thead>
                    <tbody id="recentHistory">
                        <tr>
                            <td colspan="5" class="text-center">데이터를 불러오는 중...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 만료 예정 락커 -->
        <div class="mb-4">
            <h5>만료 예정 락커</h5>
            <div class="table-responsive">
                <table class="table table-warning">
                    <thead>
                        <tr>
                            <th>락커 번호</th>
                            <th>사용자</th>
                            <th>만료일</th>
                            <th>남은 일수</th>
                            <th>작업</th>
                        </tr>
                    </thead>
                    <tbody id="expiringLockers">
                        <tr>
                            <td colspan="5" class="text-center">데이터를 불러오는 중...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    loadDashboardData();
    
    // 30초마다 자동 새로고침
    setInterval(loadDashboardData, 30000);
});

function loadDashboardData() {
    // 전체 현황 로드
    $.ajax({
        url: '<?= site_url('locker/ajax_get_dashboard_summary') ?>',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                updateSummary(response.data);
                updateFloorStatus(response.floors);
            }
        },
        error: function() {
            console.error('Failed to load dashboard summary');
        }
    });
    
    // 최근 사용 내역 로드
    loadRecentHistory();
    
    // 만료 예정 락커 로드
    loadExpiringLockers();
}

function updateSummary(data) {
    $('#totalLockers').text(data.total || 0);
    $('#availableLockers').text(data.available || 0);
    $('#occupiedLockers').text(data.occupied || 0);
    $('#otherLockers').text((data.maintenance || 0) + (data.reserved || 0));
}

function updateFloorStatus(floors) {
    const container = $('#floorStatusContainer');
    container.empty();
    
    if (!floors || floors.length === 0) {
        container.html('<p>층별 데이터가 없습니다.</p>');
        return;
    }
    
    floors.forEach(floor => {
        const total = floor.total || 0;
        const available = floor.available || 0;
        const occupied = floor.occupied || 0;
        const occupancyRate = total > 0 ? Math.round((occupied / total) * 100) : 0;
        
        const floorHtml = `
            <div class="mb-3">
                <h6>${floor.floor_nm} (${floor.floor_ord}층)</h6>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: ${occupancyRate}%">
                        사용중 ${occupied}개 (${occupancyRate}%)
                    </div>
                    <div class="progress-bar bg-success" role="progressbar" style="width: ${100-occupancyRate}%">
                        사용가능 ${available}개
                    </div>
                </div>
            </div>
        `;
        container.append(floorHtml);
    });
}

function loadRecentHistory() {
    $.ajax({
        url: '<?= site_url('locker/ajax_get_recent_history') ?>',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.history) {
                updateRecentHistory(response.history);
            }
        },
        error: function() {
            $('#recentHistory').html('<tr><td colspan="5" class="text-center">데이터를 불러올 수 없습니다.</td></tr>');
        }
    });
}

function updateRecentHistory(history) {
    const tbody = $('#recentHistory');
    tbody.empty();
    
    if (history.length === 0) {
        tbody.html('<tr><td colspan="5" class="text-center">최근 사용 내역이 없습니다.</td></tr>');
        return;
    }
    
    history.forEach(item => {
        const row = `
            <tr>
                <td>${item.action_date}</td>
                <td>${item.locker_no}</td>
                <td>${item.user_id || '-'}</td>
                <td>${getActionTypeText(item.action_type)}</td>
                <td>${item.notes || '-'}</td>
            </tr>
        `;
        tbody.append(row);
    });
}

function getActionTypeText(actionType) {
    const types = {
        'assign': '<span class="badge bg-primary">할당</span>',
        'release': '<span class="badge bg-success">반납</span>',
        'reserve': '<span class="badge bg-warning">예약</span>',
        'cancel': '<span class="badge bg-secondary">취소</span>'
    };
    return types[actionType] || actionType;
}

function loadExpiringLockers() {
    $.ajax({
        url: '<?= site_url('locker/ajax_get_expiring_lockers') ?>',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.lockers) {
                updateExpiringLockers(response.lockers);
            }
        },
        error: function() {
            $('#expiringLockers').html('<tr><td colspan="5" class="text-center">데이터를 불러올 수 없습니다.</td></tr>');
        }
    });
}

function updateExpiringLockers(lockers) {
    const tbody = $('#expiringLockers');
    tbody.empty();
    
    if (lockers.length === 0) {
        tbody.html('<tr><td colspan="5" class="text-center">만료 예정인 락커가 없습니다.</td></tr>');
        return;
    }
    
    lockers.forEach(locker => {
        const daysLeft = Math.ceil((new Date(locker.expire_date) - new Date()) / (1000 * 60 * 60 * 24));
        const badgeClass = daysLeft <= 3 ? 'bg-danger' : (daysLeft <= 7 ? 'bg-warning' : 'bg-info');
        
        const row = `
            <tr>
                <td>${locker.locker_no}</td>
                <td>${locker.assigned_user_id || '-'}</td>
                <td>${locker.expire_date}</td>
                <td><span class="badge ${badgeClass}">${daysLeft}일</span></td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="extendLocker(${locker.locker_sno})">
                        <i class="fas fa-calendar-plus"></i> 연장
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function extendLocker(lockerSno) {
    // TODO: 락커 사용 기간 연장 기능
    alert('락커 사용 기간 연장 기능은 준비 중입니다.');
}
</script>