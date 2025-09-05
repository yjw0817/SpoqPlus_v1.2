<style>
.code-context {
    background-color: #2a2c2e;
    color: #f8f8f2;
    border-radius: 5px;
    padding: 15px;
    font-family: 'Consolas', 'Monaco', monospace;
    overflow-x: auto;
    font-size: 13px;
}
.code-line {
    white-space: pre;
    line-height: 1.6;
}
.error-line {
    background-color: rgba(255, 0, 0, 0.2);
    font-weight: bold;
    display: block;
    margin: 0 -15px;
    padding: 0 15px;
}
.line-number {
    display: inline-block;
    width: 50px;
    color: #6c757d;
    text-align: right;
    margin-right: 15px;
    user-select: none;
}
.fix-suggestion {
    background-color: #e8f5e9;
    border: 1px solid #4caf50;
    border-radius: 5px;
    padding: 15px;
    margin: 10px 0;
}
.stack-trace {
    background-color: #2a2c2e;
    color: #f8f8f2;
    padding: 15px;
    border-radius: 5px;
    font-family: 'Consolas', 'Monaco', monospace;
    font-size: 12px;
    overflow-x: auto;
    white-space: pre-wrap;
}
</style>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- 페이지 헤더 -->
        <h1 class="page-header">오류 분석</h1>
        
        <?php if (!empty($error)): ?>
        <div class="row">
            <!-- 오류 기본 정보 -->
            <div class="col-md-12">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">오류 상세 정보</h4>
                        <div class="panel-heading-btn">
                            <?php if (!$error['is_resolved']): ?>
                            <button type="button" class="btn btn-xs btn-warning" onclick="showFixModal()">
                                <i class="fas fa-wrench"></i> 수정하기
                            </button>
                            <?php else: ?>
                            <span class="badge bg-success">해결됨</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-4">오류 레벨:</dt>
                                    <dd class="col-sm-8">
                                        <?php 
                                        $levelClass = '';
                                        switch($error['error_level']) {
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
                                        <span class="<?php echo $levelClass; ?>"><?php echo $error['error_level']; ?></span>
                                    </dd>
                                    
                                    <dt class="col-sm-4">발생 시간:</dt>
                                    <dd class="col-sm-8"><?php echo date('Y-m-d H:i:s', strtotime($error['log_date'])); ?></dd>
                                    
                                    <dt class="col-sm-4">첫 발생:</dt>
                                    <dd class="col-sm-8"><?php echo date('Y-m-d H:i:s', strtotime($error['first_occurred'])); ?></dd>
                                    
                                    <dt class="col-sm-4">마지막 발생:</dt>
                                    <dd class="col-sm-8"><?php echo date('Y-m-d H:i:s', strtotime($error['last_occurred'])); ?></dd>
                                    
                                    <dt class="col-sm-4">발생 횟수:</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge bg-danger"><?php echo $error['occurrence_count']; ?>회</span>
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-4">파일:</dt>
                                    <dd class="col-sm-8">
                                        <code><?php echo htmlspecialchars($error['file_path'] ?? 'N/A'); ?></code>
                                    </dd>
                                    
                                    <dt class="col-sm-4">라인:</dt>
                                    <dd class="col-sm-8"><?php echo $error['line_number'] ?? 'N/A'; ?></dd>
                                    
                                    <dt class="col-sm-4">사용자:</dt>
                                    <dd class="col-sm-8">
                                        <?php echo htmlspecialchars($error['user_name'] ?? $error['user_id'] ?? 'N/A'); ?>
                                    </dd>
                                    
                                    <dt class="col-sm-4">IP 주소:</dt>
                                    <dd class="col-sm-8"><?php echo $error['ip_address'] ?? 'N/A'; ?></dd>
                                    
                                    <dt class="col-sm-4">요청 URL:</dt>
                                    <dd class="col-sm-8">
                                        <small><?php echo htmlspecialchars($error['request_url'] ?? 'N/A'); ?></small>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <h5>오류 메시지:</h5>
                            <div class="alert alert-danger">
                                <?php echo nl2br(htmlspecialchars($error['error_message'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- 코드 컨텍스트 -->
            <?php if (!empty($error['code_context'])): ?>
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">코드 컨텍스트</h4>
                    </div>
                    <div class="panel-body">
                        <div class="code-context">
                            <?php foreach ($error['code_context'] as $line): ?>
                            <div class="code-line <?php echo $line['is_error_line'] ? 'error-line' : ''; ?>">
                                <span class="line-number"><?php echo $line['line_number']; ?>:</span><?php echo htmlspecialchars(rtrim($line['code'])); ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- AI 수정 제안 -->
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">AI 수정 제안</h4>
                        <div class="panel-heading-btn">
                            <button type="button" class="btn btn-xs btn-primary" onclick="generateSuggestion()">
                                <i class="fas fa-robot"></i> 제안 생성
                            </button>
                        </div>
                    </div>
                    <div class="panel-body" id="suggestion-container">
                        <div class="text-center text-muted">
                            <p>AI 수정 제안을 생성하려면 버튼을 클릭하세요.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- 스택 트레이스 -->
            <?php if (!empty($error['stack_trace'])): ?>
            <div class="col-md-12">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">스택 트레이스</h4>
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
                        <div class="stack-trace">
<?php echo htmlspecialchars($error['stack_trace']); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <!-- 수정 이력 -->
            <?php if (!empty($error['fix_history'])): ?>
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">수정 이력</h4>
                    </div>
                    <div class="panel-body">
                        <div class="timeline">
                            <?php foreach ($error['fix_history'] as $fix): ?>
                            <div>
                                <i class="fas fa-wrench bg-warning"></i>
                                <div class="timeline-item">
                                    <span class="time">
                                        <i class="fas fa-clock"></i> 
                                        <?php echo date('Y-m-d H:i', strtotime($fix['fixed_at'])); ?>
                                    </span>
                                    <h3 class="timeline-header">
                                        <?php echo htmlspecialchars($fix['fixed_by']); ?>님이 수정
                                    </h3>
                                    <div class="timeline-body">
                                        <?php echo nl2br(htmlspecialchars($fix['fix_description'])); ?>
                                        <?php if ($fix['is_auto_fix']): ?>
                                        <span class="badge bg-info">자동 수정</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- 비슷한 오류 -->
            <?php if (!empty($error['similar_errors'])): ?>
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">비슷한 오류</h4>
                    </div>
                    <div class="panel-body">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>발생 시간</th>
                                    <th>사용자</th>
                                    <th>상태</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($error['similar_errors'] as $similar): ?>
                                <tr style="cursor: pointer;" onclick="window.location.href='/adminmain/log_analysis/<?php echo $similar['id']; ?>?m1=4&m2=3'">
                                    <td><?php echo date('m-d H:i', strtotime($similar['log_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($similar['user_name'] ?? $similar['user_id'] ?? '-'); ?></td>
                                    <td>
                                        <?php if ($similar['is_resolved']): ?>
                                        <span class="badge bg-success">해결됨</span>
                                        <?php else: ?>
                                        <span class="badge bg-secondary">미해결</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <?php else: ?>
        <div class="alert alert-warning">
            <h4><i class="icon fas fa-exclamation-triangle"></i> 오류!</h4>
            요청하신 오류 정보를 찾을 수 없습니다.
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- 수정 모달 -->
<div class="modal fade" id="fixModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">오류 수정</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="fixForm">
                    <input type="hidden" id="error_id" value="<?php echo $error['id'] ?? ''; ?>">
                    
                    <div class="form-group">
                        <label>수정 설명</label>
                        <textarea class="form-control" id="fix_description" rows="3" 
                                  placeholder="수정 내용을 간단히 설명해주세요"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>수정 코드</label>
                        <textarea class="form-control" id="fixed_code" rows="10" 
                                  style="font-family: monospace;"
                                  placeholder="수정된 코드를 입력하세요"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="apply_fix">
                            <label class="custom-control-label" for="apply_fix">
                                실제 파일에 수정 사항 적용
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">취소</button>
                <button type="button" class="btn btn-primary" onclick="applyFix()">수정 적용</button>
            </div>
        </div>
    </div>
</div>

<script>
// AI 수정 제안 생성
function generateSuggestion() {
    var container = document.getElementById('suggestion-container');
    container.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> AI가 분석 중입니다...</div>';
    
    fetch('/adminmain/suggestFix', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'error_id=' + <?php echo $error['id'] ?? 0; ?>
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.suggestion) {
            var html = '<div class="fix-suggestion">';
            html += '<h5><i class="fas fa-lightbulb"></i> 수정 제안</h5>';
            html += '<p>' + data.suggestion.description + '</p>';
            
            if (data.suggestion.code) {
                html += '<h6>제안 코드:</h6>';
                html += '<pre class="code-context">' + escapeHtml(data.suggestion.code) + '</pre>';
            }
            
            if (data.suggestion.explanation) {
                html += '<h6>설명:</h6>';
                html += '<p>' + data.suggestion.explanation + '</p>';
            }
            
            html += '<button class="btn btn-sm btn-success mt-2" onclick="applySuggestion(\'' + 
                    escapeHtml(JSON.stringify(data.suggestion)) + '\')">이 제안 적용</button>';
            html += '</div>';
            
            container.innerHTML = html;
        } else {
            container.innerHTML = '<div class="alert alert-warning">AI 제안을 생성할 수 없습니다.</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        container.innerHTML = '<div class="alert alert-danger">오류가 발생했습니다.</div>';
    });
}

// 수정 모달 표시
function showFixModal() {
    $('#fixModal').modal('show');
}

// AI 제안 적용
function applySuggestion(suggestionJson) {
    try {
        var suggestion = JSON.parse(suggestionJson);
        document.getElementById('fix_description').value = suggestion.description || '';
        document.getElementById('fixed_code').value = suggestion.code || '';
        showFixModal();
    } catch (e) {
        console.error('Error applying suggestion:', e);
    }
}

// 수정 적용
function applyFix() {
    var formData = {
        error_id: document.getElementById('error_id').value,
        fix_description: document.getElementById('fix_description').value,
        fixed_code: document.getElementById('fixed_code').value,
        apply_fix: document.getElementById('apply_fix').checked
    };
    
    if (!formData.fix_description) {
        alert('수정 설명을 입력해주세요.');
        return;
    }
    
    fetch('/adminmain/applyFix', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('수정이 성공적으로 적용되었습니다.');
            location.reload();
        } else {
            alert('수정 적용 중 오류가 발생했습니다: ' + (data.message || ''));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('오류가 발생했습니다.');
    });
}

// HTML 이스케이프
function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script>