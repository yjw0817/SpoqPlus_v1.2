<style>
.alert-rule {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 10px;
}
.alert-rule.active {
    border-color: #28a745;
}
.alert-rule.inactive {
    opacity: 0.6;
}
</style>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- 페이지 헤더 -->
        <h1 class="page-header">로그 설정</h1>
        
        <div class="row">
            <!-- 로그 수집 설정 -->
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">로그 수집 설정</h4>
                    </div>
                    <div class="panel-body">
                        <form id="collectionSettingsForm">
                            <div class="form-group">
                                <label>로그 수집 활성화</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="enableCollection" checked>
                                    <label class="custom-control-label" for="enableCollection">
                                        실시간 로그 수집 및 분석
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>수집 대상 로그 레벨</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="level_critical" checked>
                                    <label class="form-check-label" for="level_critical">CRITICAL</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="level_error" checked>
                                    <label class="form-check-label" for="level_error">ERROR</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="level_warning" checked>
                                    <label class="form-check-label" for="level_warning">WARNING</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="level_info">
                                    <label class="form-check-label" for="level_info">INFO</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="level_debug">
                                    <label class="form-check-label" for="level_debug">DEBUG</label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>로그 파일 경로</label>
                                <input type="text" class="form-control" id="logPath" 
                                       value="<?php echo WRITEPATH . 'logs'; ?>" readonly>
                            </div>
                            
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-append">
                                    <span class="input-group-text" style='width:150px'>로그 보관 기간</span>
                                </span>
                                <select class="form-control" id="retentionDays">
                                    <option value="7">7일</option>
                                    <option value="14">14일</option>
                                    <option value="30" selected>30일</option>
                                    <option value="60">60일</option>
                                    <option value="90">90일</option>
                                    <option value="365">1년</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i> 저장
                            </button>
                        </form>
                    </div>
                </div>

                <!-- 로그 파싱 설정 -->
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">로그 파싱 설정</h4>
                    </div>
                    <div class="panel-body">
                        <form id="parsingSettingsForm">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-append">
                                    <span class="input-group-text" style='width:150px'>로그 포맷</span>
                                </span>
                                <select class="form-control" id="logFormat">
                                    <option value="codeigniter4">CodeIgniter 4</option>
                                    <option value="apache_error">Apache Error Log</option>
                                    <option value="nginx_error">Nginx Error Log</option>
                                    <option value="php_error">PHP Error Log</option>
                                    <option value="custom">커스텀</option>
                                </select>
                            </div>
                            
                            <div class="form-group" id="customFormatGroup" style="display: none;">
                                <label>커스텀 정규식 패턴</label>
                                <textarea class="form-control" id="customPattern" rows="3" 
                                          placeholder="예: /^(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2}) \[(\w+)\] (.+)$/"></textarea>
                            </div>
                            
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-append">
                                    <span class="input-group-text" style='width:150px'>파일 인코딩</span>
                                </span>
                                <select class="form-control" id="fileEncoding">
                                    <option value="UTF-8">UTF-8</option>
                                    <option value="EUC-KR">EUC-KR</option>
                                    <option value="ISO-8859-1">ISO-8859-1</option>
                                </select>
                            </div>
                            
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-append">
                                    <span class="input-group-text" style='width:150px'>최대 처리 크기 (MB)</span>
                                </span>
                                <input type="number" class="form-control" id="maxFileSize" value="10" min="1" max="100">
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i> 저장
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 알림 설정 -->
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">알림 설정</h4>
                        <div class="panel-heading-btn">
                            <button type="button" class="btn btn-xs btn-primary" onclick="addAlertRule()">
                                <i class="fas fa-plus"></i> 규칙 추가
                            </button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="alertRules">
                            <?php if (!empty($alert_settings)): ?>
                                <?php foreach ($alert_settings as $alert): ?>
                                <div class="alert-rule <?php echo $alert['is_active'] ? 'active' : 'inactive'; ?>" 
                                     id="alert-<?php echo $alert['id']; ?>">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h5><?php echo htmlspecialchars($alert['alert_name']); ?></h5>
                                            <p class="mb-1">
                                                레벨: <strong><?php echo $alert['error_level'] ?? '모든 레벨'; ?></strong><br>
                                                조건: <?php echo $alert['threshold_minutes']; ?>분 내 
                                                <?php echo $alert['threshold_count']; ?>회 이상 발생 시
                                            </p>
                                            <p class="mb-0">
                                                <small>알림: <?php echo htmlspecialchars($alert['alert_emails'] ?: '미설정'); ?></small>
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <div class="custom-control custom-switch mb-2">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="toggle-<?php echo $alert['id']; ?>"
                                                       <?php echo $alert['is_active'] ? 'checked' : ''; ?>
                                                       onchange="toggleAlert(<?php echo $alert['id']; ?>)">
                                                <label class="custom-control-label" 
                                                       for="toggle-<?php echo $alert['id']; ?>">활성</label>
                                            </div>
                                            <button class="btn btn-sm btn-info" 
                                                    onclick="editAlert(<?php echo $alert['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="deleteAlert(<?php echo $alert['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">설정된 알림 규칙이 없습니다.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- 성능 설정 -->
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">성능 설정</h4>
                    </div>
                    <div class="panel-body">
                        <form id="performanceSettingsForm">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-append">
                                    <span class="input-group-text" style='width:180px'>실시간 모니터링 간격 (초)</span>
                                </span>
                                <input type="number" class="form-control" id="monitoringInterval" 
                                       value="30" min="10" max="300">
                            </div>
                            
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-append">
                                    <span class="input-group-text" style='width:180px'>동시 처리 작업 수</span>
                                </span>
                                <input type="number" class="form-control" id="concurrentJobs" 
                                       value="5" min="1" max="20">
                            </div>
                            
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-append">
                                    <span class="input-group-text" style='width:180px'>캐시 유효 시간 (분)</span>
                                </span>
                                <input type="number" class="form-control" id="cacheTTL" 
                                       value="60" min="5" max="1440">
                            </div>
                            
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="enableCompression" checked>
                                    <label class="custom-control-label" for="enableCompression">
                                        로그 압축 저장
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i> 저장
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 알림 규칙 모달 -->
<div class="modal fade" id="alertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">알림 규칙 설정</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="alertForm">
                    <input type="hidden" id="alert_id">
                    
                    <div class="form-group">
                        <label>규칙 이름</label>
                        <input type="text" class="form-control" id="alert_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>오류 레벨</label>
                        <select class="form-control" id="alert_level">
                            <option value="">모든 레벨</option>
                            <option value="CRITICAL">CRITICAL</option>
                            <option value="ERROR">ERROR</option>
                            <option value="WARNING">WARNING</option>
                            <option value="INFO">INFO</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>발생 횟수</label>
                                <input type="number" class="form-control" id="threshold_count" 
                                       value="1" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>시간 범위 (분)</label>
                                <input type="number" class="form-control" id="threshold_minutes" 
                                       value="60" min="1" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>알림 이메일 (쉼표로 구분)</label>
                        <input type="text" class="form-control" id="alert_emails" 
                               placeholder="admin@example.com, dev@example.com">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                <button type="button" class="btn btn-primary" onclick="saveAlert()">저장</button>
            </div>
        </div>
    </div>
</div>

<script>
// 로그 포맷 변경 시
document.getElementById('logFormat').addEventListener('change', function() {
    document.getElementById('customFormatGroup').style.display = 
        this.value === 'custom' ? 'block' : 'none';
});

// 수집 설정 저장
document.getElementById('collectionSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var settings = {
        enable_collection: document.getElementById('enableCollection').checked,
        levels: {
            critical: document.getElementById('level_critical').checked,
            error: document.getElementById('level_error').checked,
            warning: document.getElementById('level_warning').checked,
            info: document.getElementById('level_info').checked,
            debug: document.getElementById('level_debug').checked
        },
        retention_days: document.getElementById('retentionDays').value
    };
    
    saveSettings('collection', settings);
});

// 파싱 설정 저장
document.getElementById('parsingSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var settings = {
        log_format: document.getElementById('logFormat').value,
        custom_pattern: document.getElementById('customPattern').value,
        file_encoding: document.getElementById('fileEncoding').value,
        max_file_size: document.getElementById('maxFileSize').value
    };
    
    saveSettings('parsing', settings);
});

// 성능 설정 저장
document.getElementById('performanceSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var settings = {
        monitoring_interval: document.getElementById('monitoringInterval').value,
        concurrent_jobs: document.getElementById('concurrentJobs').value,
        cache_ttl: document.getElementById('cacheTTL').value,
        enable_compression: document.getElementById('enableCompression').checked
    };
    
    saveSettings('performance', settings);
});

// 설정 저장
function saveSettings(type, settings) {
    fetch('/adminmain/log_settings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'type=' + type + '&settings=' + JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('설정이 저장되었습니다.');
        } else {
            alert('설정 저장 중 오류가 발생했습니다.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('오류가 발생했습니다.');
    });
}

// 알림 규칙 추가
function addAlertRule() {
    document.getElementById('alert_id').value = '';
    document.getElementById('alertForm').reset();
    $('#alertModal').modal('show');
}

// 알림 규칙 편집
function editAlert(alertId) {
    // 알림 정보 로드 및 모달 표시
    fetch('/adminmain/getAlert/' + alertId)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.alert) {
                document.getElementById('alert_id').value = data.alert.id;
                document.getElementById('alert_name').value = data.alert.alert_name;
                document.getElementById('alert_level').value = data.alert.error_level || '';
                document.getElementById('threshold_count').value = data.alert.threshold_count;
                document.getElementById('threshold_minutes').value = data.alert.threshold_minutes;
                document.getElementById('alert_emails').value = data.alert.alert_emails || '';
                
                $('#alertModal').modal('show');
            }
        });
}

// 알림 저장
function saveAlert() {
    var alertData = {
        id: document.getElementById('alert_id').value,
        alert_name: document.getElementById('alert_name').value,
        error_level: document.getElementById('alert_level').value,
        threshold_count: document.getElementById('threshold_count').value,
        threshold_minutes: document.getElementById('threshold_minutes').value,
        alert_emails: document.getElementById('alert_emails').value
    };
    
    fetch('/adminmain/saveAlert', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(alertData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('알림 규칙이 저장되었습니다.');
            $('#alertModal').modal('hide');
            location.reload();
        } else {
            alert('저장 중 오류가 발생했습니다.');
        }
    });
}

// 알림 토글
function toggleAlert(alertId) {
    var isActive = document.getElementById('toggle-' + alertId).checked;
    
    fetch('/adminmain/toggleAlert', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + alertId + '&is_active=' + (isActive ? 1 : 0)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            var alertDiv = document.getElementById('alert-' + alertId);
            if (isActive) {
                alertDiv.classList.remove('inactive');
                alertDiv.classList.add('active');
            } else {
                alertDiv.classList.remove('active');
                alertDiv.classList.add('inactive');
            }
        }
    });
}

// 알림 삭제
function deleteAlert(alertId) {
    if (confirm('이 알림 규칙을 삭제하시겠습니까?')) {
        fetch('/adminmain/deleteAlert', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + alertId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('alert-' + alertId).remove();
            } else {
                alert('삭제 중 오류가 발생했습니다.');
            }
        });
    }
}
</script>