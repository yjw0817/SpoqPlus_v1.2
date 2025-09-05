<style>
.quick-filters {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}
.quick-filter-btn {
    padding: 6px 12px;
    border: 1px solid #e9ecef;
    background: #f8f9fa;
    border-radius: 4px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s ease;
}
.quick-filter-btn:hover {
    background: #e9ecef;
}
.quick-filter-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}
.date-range-picker {
    display: flex;
    align-items: center;
    gap: 10px;
}
.custom-checkbox {
    margin-bottom: 10px;
}
.error-message {
    font-size: 14px;
    color: #343a40;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 400px;
}
</style>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- 페이지 헤더 -->
        <h1 class="page-header">로그 검색</h1>
        
        <!-- 검색 필터 -->
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">검색 필터</h4>
                <div class="panel-heading-btn">
                    <button class="btn btn-xs btn-icon btn-circle btn-default" onclick="resetFilters()">
                        <i class="fas fa-redo"></i>
                    </button>
                </div>
            </div>
            <div class="panel-body">
            
            <form id="searchForm" method="GET" action="/adminmain/log_search">
                <input type="hidden" name="m1" value="4">
                <input type="hidden" name="m2" value="2">
                
                <!-- 빠른 필터 -->
                <div class="quick-filters">
                    <button type="button" class="quick-filter-btn" onclick="setQuickFilter('today')">오늘</button>
                    <button type="button" class="quick-filter-btn" onclick="setQuickFilter('yesterday')">어제</button>
                    <button type="button" class="quick-filter-btn" onclick="setQuickFilter('week')">최근 7일</button>
                    <button type="button" class="quick-filter-btn" onclick="setQuickFilter('month')">최근 30일</button>
                    <button type="button" class="quick-filter-btn" onclick="setQuickFilter('critical')">Critical Only</button>
                    <button type="button" class="quick-filter-btn" onclick="setQuickFilter('unresolved')">미해결 오류</button>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-append">
                                <span class="input-group-text" style='width:150px'>날짜 범위</span>
                            </span>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="<?php echo $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days')); ?>">
                            <span class="input-group-text">~</span>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="<?php echo $_GET['end_date'] ?? date('Y-m-d'); ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-append">
                                <span class="input-group-text" style='width:150px'>키워드 검색</span>
                            </span>
                            <input type="text" class="form-control" name="keyword" 
                                   placeholder="오류 메시지, 파일명, URL 등" 
                                   value="<?php echo $_GET['keyword'] ?? ''; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="mb-3">오류 레벨</h6>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" name="levels[]" value="CRITICAL" id="level_critical"
                                   <?php echo in_array('CRITICAL', $_GET['levels'] ?? ['CRITICAL']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="level_critical">
                                <span class="badge bg-danger">CRITICAL</span>
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" name="levels[]" value="ERROR" id="level_error"
                                   <?php echo in_array('ERROR', $_GET['levels'] ?? ['ERROR']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="level_error">
                                <span class="badge bg-warning text-dark">ERROR</span>
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" name="levels[]" value="WARNING" id="level_warning"
                                   <?php echo in_array('WARNING', $_GET['levels'] ?? ['WARNING']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="level_warning">
                                <span class="badge bg-info">WARNING</span>
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" name="levels[]" value="INFO" id="level_info"
                                   <?php echo in_array('INFO', $_GET['levels'] ?? []) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="level_info">
                                <span class="badge bg-primary">INFO</span>
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" name="levels[]" value="DEBUG" id="level_debug"
                                   <?php echo in_array('DEBUG', $_GET['levels'] ?? []) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="level_debug">
                                <span class="badge bg-secondary">DEBUG</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-append">
                                <span class="input-group-text" style='width:120px'>해결 상태</span>
                            </span>
                            <select class="form-control" name="is_resolved">
                                <option value="">전체</option>
                                <option value="0" <?php echo ($_GET['is_resolved'] ?? '') === '0' ? 'selected' : ''; ?>>미해결</option>
                                <option value="1" <?php echo ($_GET['is_resolved'] ?? '') === '1' ? 'selected' : ''; ?>>해결됨</option>
                            </select>
                        </div>
                        
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-append">
                                <span class="input-group-text" style='width:120px'>사용자 ID</span>
                            </span>
                            <input type="text" class="form-control" name="user_id" 
                                   placeholder="특정 사용자 검색" 
                                   value="<?php echo $_GET['user_id'] ?? ''; ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-append">
                                <span class="input-group-text" style='width:120px'>정렬 기준</span>
                            </span>
                            <select class="form-control" name="order_by">
                                <option value="log_date" <?php echo ($_GET['order_by'] ?? 'log_date') === 'log_date' ? 'selected' : ''; ?>>발생 시간</option>
                                <option value="error_level" <?php echo ($_GET['order_by'] ?? '') === 'error_level' ? 'selected' : ''; ?>>오류 레벨</option>
                                <option value="occurrence_count" <?php echo ($_GET['order_by'] ?? '') === 'occurrence_count' ? 'selected' : ''; ?>>발생 횟수</option>
                            </select>
                        </div>
                        
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-append">
                                <span class="input-group-text" style='width:120px'>정렬 방향</span>
                            </span>
                            <select class="form-control" name="order_dir">
                                <option value="DESC" <?php echo ($_GET['order_dir'] ?? 'DESC') === 'DESC' ? 'selected' : ''; ?>>최신순</option>
                                <option value="ASC" <?php echo ($_GET['order_dir'] ?? '') === 'ASC' ? 'selected' : ''; ?>>오래된순</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> 검색
                    </button>
                </div>
            </form>
            </div>
        </div>

        <!-- 검색 결과 -->
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">검색 결과 (<?php echo $search_val['listCount'] ?? 0; ?>건)</h4>
                <div class="panel-heading-btn">
                    <div class="btn-group">
                        <button class="btn btn-xs btn-success" onclick="exportData('excel')">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                        <button class="btn btn-xs btn-primary" onclick="exportData('csv')">
                            <i class="fas fa-file-csv"></i> CSV
                        </button>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 50px;">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                </th>
                                <th style="width: 140px;">발생 시간</th>
                                <th style="width: 80px;">레벨</th>
                                <th>오류 메시지</th>
                                <th style="width: 150px;">파일</th>
                                <th style="width: 80px;">사용자</th>
                                <th style="width: 60px;">횟수</th>
                                <th style="width: 80px;">상태</th>
                                <th style="width: 100px;">옵션</th>
                            </tr>
                        </thead>
                    <tbody>
                        <?php if (!empty($log_results['data'])): ?>
                            <?php foreach ($log_results['data'] as $log): ?>
                            <tr onclick="viewDetail(<?php echo $log['id']; ?>)">
                                <td onclick="event.stopPropagation();">
                                    <input type="checkbox" class="log-select" value="<?php echo $log['id']; ?>">
                                </td>
                                <td><?php echo date('Y-m-d H:i:s', strtotime($log['log_date'])); ?></td>
                                <td class="text-center">
                                    <?php 
                                    $levelClass = '';
                                    switch($log['error_level']) {
                                        case 'CRITICAL':
                                            $levelClass = 'badge bg-danger';
                                            break;
                                        case 'ERROR':
                                            $levelClass = 'badge bg-warning text-dark';
                                            break;
                                        case 'WARNING':
                                            $levelClass = 'badge bg-info';
                                            break;
                                        case 'INFO':
                                            $levelClass = 'badge bg-primary';
                                            break;
                                        case 'DEBUG':
                                            $levelClass = 'badge bg-secondary';
                                            break;
                                        default:
                                            $levelClass = 'badge bg-secondary';
                                    }
                                    ?>
                                    <span class="<?php echo $levelClass; ?>"><?php echo $log['error_level']; ?></span>
                                </td>
                                <td>
                                    <div class="error-message" title="<?php echo htmlspecialchars($log['error_message']); ?>">
                                        <?php echo htmlspecialchars(mb_substr($log['error_message'], 0, 60)) . (mb_strlen($log['error_message']) > 60 ? '...' : ''); ?>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo basename($log['file_path'] ?? '') . ':' . ($log['line_number'] ?? ''); ?>
                                    </small>
                                </td>
                                <td><?php echo $log['user_name'] ?? $log['user_id'] ?? '-'; ?></td>
                                <td>
                                    <span class="badge badge-secondary"><?php echo $log['occurrence_count'] ?? 1; ?></span>
                                </td>
                                <td>
                                    <?php if ($log['is_resolved']): ?>
                                        <span class="text-success"><i class="fas fa-check-circle"></i> 해결</span>
                                    <?php else: ?>
                                        <span class="text-danger"><i class="fas fa-times-circle"></i> 미해결</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center" onclick="event.stopPropagation();">
                                    <a href="/adminmain/log_analysis/<?php echo $log['id']; ?>?m1=4&m2=3" 
                                       class="btn btn-primary btn-xs">분석</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-search fa-2x mb-2"></i>
                                    <p class="mb-0">검색 결과가 없습니다.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    </table>
                </div>
            </div>
            
            <!-- 페이징 및 일괄 작업 -->
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-success btn-sm" onclick="markAsResolved()" disabled id="bulkResolveBtn">
                            <i class="fas fa-check"></i> 선택 항목 해결 처리
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteSelected()" disabled id="bulkDeleteBtn">
                            <i class="fas fa-trash"></i> 선택 항목 삭제
                        </button>
                    </div>
                    <div class="col-md-6">
                        <?php if (!empty($pager)): ?>
                            <?php echo $pager; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// 빠른 필터 설정
function setQuickFilter(type) {
    // 모든 빠른 필터 버튼 비활성화
    document.querySelectorAll('.quick-filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // 클릭한 버튼 활성화
    event.target.classList.add('active');
    
    switch(type) {
        case 'today':
            document.getElementById('start_date').value = '<?php echo date('Y-m-d'); ?>';
            document.getElementById('end_date').value = '<?php echo date('Y-m-d'); ?>';
            break;
        case 'yesterday':
            document.getElementById('start_date').value = '<?php echo date('Y-m-d', strtotime('-1 day')); ?>';
            document.getElementById('end_date').value = '<?php echo date('Y-m-d', strtotime('-1 day')); ?>';
            break;
        case 'week':
            document.getElementById('start_date').value = '<?php echo date('Y-m-d', strtotime('-7 days')); ?>';
            document.getElementById('end_date').value = '<?php echo date('Y-m-d'); ?>';
            break;
        case 'month':
            document.getElementById('start_date').value = '<?php echo date('Y-m-d', strtotime('-30 days')); ?>';
            document.getElementById('end_date').value = '<?php echo date('Y-m-d'); ?>';
            break;
        case 'critical':
            document.querySelectorAll('input[name="levels[]"]').forEach(cb => cb.checked = false);
            document.getElementById('level_critical').checked = true;
            break;
        case 'unresolved':
            document.querySelector('select[name="is_resolved"]').value = '0';
            break;
    }
}

// 필터 초기화
function resetFilters() {
    document.getElementById('searchForm').reset();
    document.getElementById('start_date').value = '<?php echo date('Y-m-d', strtotime('-7 days')); ?>';
    document.getElementById('end_date').value = '<?php echo date('Y-m-d'); ?>';
}

// 전체 선택
function toggleSelectAll() {
    var isChecked = document.getElementById('selectAll').checked;
    document.querySelectorAll('.log-select').forEach(cb => {
        cb.checked = isChecked;
    });
    updateBulkButtons();
}

// 일괄 작업 버튼 업데이트
function updateBulkButtons() {
    var checkedCount = document.querySelectorAll('.log-select:checked').length;
    document.getElementById('bulkResolveBtn').disabled = checkedCount === 0;
    document.getElementById('bulkDeleteBtn').disabled = checkedCount === 0;
}

// 상세 보기
function viewDetail(logId) {
    window.location.href = '/adminmain/log_analysis/' + logId + '?m1=4&m2=3';
}

// 선택 항목 해결 처리
function markAsResolved() {
    var selectedIds = [];
    document.querySelectorAll('.log-select:checked').forEach(cb => {
        selectedIds.push(cb.value);
    });
    
    if (selectedIds.length === 0) return;
    
    if (confirm(selectedIds.length + '개의 오류를 해결됨으로 처리하시겠습니까?')) {
        // AJAX 처리
        fetch('/adminmain/bulkResolve', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'ids=' + selectedIds.join(',')
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('처리되었습니다.');
                location.reload();
            }
        });
    }
}

// 데이터 내보내기
function exportData(format) {
    var params = new URLSearchParams(window.location.search);
    params.set('export', format);
    window.location.href = '/adminmain/exportLogData?' + params.toString();
}

// 체크박스 변경 이벤트
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.log-select').forEach(cb => {
        cb.addEventListener('change', updateBulkButtons);
    });
});
</script>