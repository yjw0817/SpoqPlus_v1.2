<style>
.fix-panel {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 15px;
}
.code-diff {
    font-family: 'Consolas', 'Monaco', monospace;
    font-size: 13px;
    line-height: 1.5;
}
.diff-added {
    background-color: #d4edda;
    color: #155724;
}
.diff-removed {
    background-color: #f8d7da;
    color: #721c24;
}
.fix-method {
    cursor: pointer;
    transition: all 0.3s;
    border: 2px solid transparent;
}
.fix-method:hover {
    background-color: #e9ecef;
    border-color: #007bff;
}
.fix-method.selected {
    background-color: #e7f3ff;
    border-color: #007bff;
}
</style>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- 페이지 헤더 -->
        <h1 class="page-header">오류 수정</h1>
        
        <?php if (!empty($error)): ?>
        <div class="row">
            <!-- 오류 정보 요약 -->
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> 오류 정보</h5>
                    <strong><?php echo htmlspecialchars($error['error_message']); ?></strong><br>
                    <small>
                        파일: <?php echo htmlspecialchars($error['file_path'] ?? 'Unknown'); ?> 
                        (라인: <?php echo $error['line_number'] ?? '?'; ?>)
                    </small>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- 수정 방법 선택 -->
            <div class="col-md-4">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">수정 방법 선택</h4>
                    </div>
                    <div class="panel-body">
                        <div class="fix-method fix-panel" onclick="selectFixMethod('auto')" id="method-auto">
                            <h5><i class="fas fa-robot"></i> AI 자동 수정</h5>
                            <p class="mb-0">AI가 분석하여 자동으로 수정 코드를 생성합니다.</p>
                            <small class="text-muted">권장: 일반적인 오류</small>
                        </div>
                        
                        <div class="fix-method fix-panel" onclick="selectFixMethod('template')" id="method-template">
                            <h5><i class="fas fa-clipboard-list"></i> 템플릿 기반 수정</h5>
                            <p class="mb-0">일반적인 오류 패턴에 대한 검증된 수정 템플릿을 사용합니다.</p>
                            <small class="text-muted">권장: 반복적인 오류</small>
                        </div>
                        
                        <div class="fix-method fix-panel" onclick="selectFixMethod('manual')" id="method-manual">
                            <h5><i class="fas fa-code"></i> 수동 수정</h5>
                            <p class="mb-0">직접 코드를 수정합니다.</p>
                            <small class="text-muted">권장: 복잡한 비즈니스 로직</small>
                        </div>
                    </div>
                </div>

                <!-- 수정 이력 -->
                <?php if (!empty($similar_fixes)): ?>
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">유사 오류 수정 이력</h4>
                    </div>
                    <div class="panel-body">
                        <?php foreach ($similar_fixes as $fix): ?>
                        <div class="fix-panel">
                            <small class="text-muted">
                                <?php echo date('Y-m-d', strtotime($fix['fixed_at'])); ?> - 
                                <?php echo htmlspecialchars($fix['fixed_by']); ?>
                            </small>
                            <p class="mb-0"><?php echo htmlspecialchars($fix['fix_description']); ?></p>
                            <a href="#" onclick="applySimilarFix(<?php echo $fix['id']; ?>)" class="small">
                                이 수정 방법 적용 →
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- 수정 작업 영역 -->
            <div class="col-md-8">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">수정 작업</h4>
                        <div class="panel-heading-btn">
                            <button type="button" class="btn btn-xs btn-info" onclick="previewFix()" style="display: none;" id="preview-btn">
                                <i class="fas fa-eye"></i> 미리보기
                            </button>
                        </div>
                    </div>
                    <div class="panel-body" id="fix-workspace">
                        <div class="text-center text-muted">
                            <i class="fas fa-arrow-left fa-2x mb-3"></i>
                            <p>왼쪽에서 수정 방법을 선택하세요.</p>
                        </div>
                    </div>
                </div>

                <!-- 수정 미리보기 -->
                <div class="panel panel-inverse" id="preview-card" style="display: none;">
                    <div class="panel-heading">
                        <h4 class="panel-title">수정 미리보기</h4>
                    </div>
                    <div class="panel-body">
                        <div id="diff-viewer" class="code-diff"></div>
                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn btn-success" onclick="applyFix()">
                            <i class="fas fa-check"></i> 수정 적용
                        </button>
                        <button type="button" class="btn btn-warning" onclick="testFix()">
                            <i class="fas fa-flask"></i> 테스트
                        </button>
                        <button type="button" class="btn btn-default" onclick="cancelPreview()">
                            취소
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php else: ?>
        <div class="alert alert-warning">
            <h4><i class="icon fas fa-exclamation-triangle"></i> 오류!</h4>
            수정할 오류 정보를 찾을 수 없습니다.
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
var selectedMethod = null;
var currentFix = {
    error_id: <?php echo $error['id'] ?? 0; ?>,
    file_path: '<?php echo addslashes($error['file_path'] ?? ''); ?>',
    line_number: <?php echo $error['line_number'] ?? 0; ?>,
    original_code: '',
    fixed_code: ''
};

// 수정 방법 선택
function selectFixMethod(method) {
    selectedMethod = method;
    
    // UI 업데이트
    document.querySelectorAll('.fix-method').forEach(el => {
        el.classList.remove('selected');
    });
    document.getElementById('method-' + method).classList.add('selected');
    
    // 작업 영역 업데이트
    loadFixWorkspace(method);
}

// 수정 작업 영역 로드
function loadFixWorkspace(method) {
    var workspace = document.getElementById('fix-workspace');
    document.getElementById('preview-btn').style.display = 'inline-block';
    
    switch(method) {
        case 'auto':
            loadAutoFix();
            break;
        case 'template':
            loadTemplateFix();
            break;
        case 'manual':
            loadManualFix();
            break;
    }
}

// AI 자동 수정 로드
function loadAutoFix() {
    var workspace = document.getElementById('fix-workspace');
    workspace.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>AI가 오류를 분석하고 있습니다...</p></div>';
    
    fetch('/adminmain/suggestFix', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'error_id=' + currentFix.error_id + '&method=auto'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.suggestion) {
            var html = '<div class="fix-panel">';
            html += '<h5>AI 수정 제안</h5>';
            html += '<p>' + escapeHtml(data.suggestion.description) + '</p>';
            
            if (data.suggestion.confidence) {
                html += '<div class="progress mb-3">';
                html += '<div class="progress-bar bg-success" style="width: ' + data.suggestion.confidence + '%">';
                html += data.suggestion.confidence + '% 확신도</div>';
                html += '</div>';
            }
            
            html += '<h6>수정 코드:</h6>';
            html += '<pre class="code-diff">' + escapeHtml(data.suggestion.code) + '</pre>';
            
            html += '<div class="form-group">';
            html += '<label>수정 설명 (선택사항)</label>';
            html += '<textarea class="form-control" id="fix-description" rows="2">' + 
                    escapeHtml(data.suggestion.description) + '</textarea>';
            html += '</div>';
            
            html += '</div>';
            
            workspace.innerHTML = html;
            currentFix.fixed_code = data.suggestion.code;
            currentFix.original_code = data.suggestion.original_code || '';
        } else {
            workspace.innerHTML = '<div class="alert alert-warning">AI 제안을 생성할 수 없습니다. 다른 방법을 시도해보세요.</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        workspace.innerHTML = '<div class="alert alert-danger">오류가 발생했습니다.</div>';
    });
}

// 템플릿 기반 수정 로드
function loadTemplateFix() {
    var workspace = document.getElementById('fix-workspace');
    
    fetch('/adminmain/getFixTemplates', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'error_type=' + encodeURIComponent('<?php echo $error['error_level'] ?? ''; ?>')
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.templates.length > 0) {
            var html = '<h5>수정 템플릿 선택</h5>';
            html += '<div class="list-group mb-3">';
            
            data.templates.forEach(function(template) {
                html += '<a href="#" class="list-group-item list-group-item-action" ';
                html += 'onclick="applyTemplate(' + template.id + ')">';
                html += '<h6 class="mb-1">' + escapeHtml(template.name) + '</h6>';
                html += '<p class="mb-1">' + escapeHtml(template.description) + '</p>';
                html += '<small>사용 횟수: ' + template.usage_count + '회</small>';
                html += '</a>';
            });
            
            html += '</div>';
            html += '<div id="template-workspace"></div>';
            
            workspace.innerHTML = html;
        } else {
            workspace.innerHTML = '<div class="alert alert-info">사용 가능한 템플릿이 없습니다. 수동 수정을 시도해보세요.</div>';
        }
    });
}

// 수동 수정 로드
function loadManualFix() {
    var workspace = document.getElementById('fix-workspace');
    
    var html = '<div class="form-group">';
    html += '<label>원본 코드</label>';
    html += '<textarea class="form-control" id="original-code" rows="8" readonly>';
    html += '<?php echo addslashes($error['code_context'] ?? '// 코드를 불러오는 중...'); ?>';
    html += '</textarea>';
    html += '</div>';
    
    html += '<div class="form-group">';
    html += '<label>수정된 코드</label>';
    html += '<textarea class="form-control" id="fixed-code" rows="8" ';
    html += 'placeholder="수정된 코드를 입력하세요..."></textarea>';
    html += '</div>';
    
    html += '<div class="form-group">';
    html += '<label>수정 설명</label>';
    html += '<textarea class="form-control" id="fix-description" rows="2" ';
    html += 'placeholder="어떤 문제를 어떻게 수정했는지 설명해주세요..."></textarea>';
    html += '</div>';
    
    workspace.innerHTML = html;
    
    // 원본 코드 로드
    loadOriginalCode();
}

// 원본 코드 로드
function loadOriginalCode() {
    fetch('/adminmain/getErrorContext', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'error_id=' + currentFix.error_id
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.context) {
            document.getElementById('original-code').value = data.context;
            currentFix.original_code = data.context;
        }
    });
}

// 미리보기
function previewFix() {
    if (selectedMethod === 'manual') {
        currentFix.fixed_code = document.getElementById('fixed-code').value;
    }
    
    if (!currentFix.fixed_code) {
        alert('수정할 코드가 없습니다.');
        return;
    }
    
    // Diff 표시
    showDiff(currentFix.original_code, currentFix.fixed_code);
    
    document.getElementById('preview-card').style.display = 'block';
}

// Diff 표시
function showDiff(original, fixed) {
    var diffViewer = document.getElementById('diff-viewer');
    
    // 간단한 줄 단위 diff (실제로는 더 정교한 diff 라이브러리 사용 권장)
    var originalLines = original.split('\n');
    var fixedLines = fixed.split('\n');
    
    var html = '<table class="table table-sm">';
    var maxLines = Math.max(originalLines.length, fixedLines.length);
    
    for (var i = 0; i < maxLines; i++) {
        var origLine = originalLines[i] || '';
        var fixedLine = fixedLines[i] || '';
        
        if (origLine !== fixedLine) {
            if (origLine) {
                html += '<tr class="diff-removed">';
                html += '<td width="30">-</td>';
                html += '<td>' + escapeHtml(origLine) + '</td>';
                html += '</tr>';
            }
            if (fixedLine) {
                html += '<tr class="diff-added">';
                html += '<td width="30">+</td>';
                html += '<td>' + escapeHtml(fixedLine) + '</td>';
                html += '</tr>';
            }
        } else {
            html += '<tr>';
            html += '<td width="30">&nbsp;</td>';
            html += '<td>' + escapeHtml(origLine) + '</td>';
            html += '</tr>';
        }
    }
    
    html += '</table>';
    diffViewer.innerHTML = html;
}

// 수정 적용
function applyFix() {
    var fixData = {
        error_id: currentFix.error_id,
        file_path: currentFix.file_path,
        original_code: currentFix.original_code,
        fixed_code: currentFix.fixed_code,
        fix_description: document.getElementById('fix-description') ? 
                        document.getElementById('fix-description').value : '',
        fix_method: selectedMethod
    };
    
    if (confirm('정말로 이 수정사항을 적용하시겠습니까?')) {
        fetch('/adminmain/applyFix', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(fixData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('수정이 성공적으로 적용되었습니다.');
                window.location.href = '/adminmain/log_analysis/' + currentFix.error_id;
            } else {
                alert('수정 적용 중 오류가 발생했습니다: ' + (data.message || ''));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('오류가 발생했습니다.');
        });
    }
}

// 테스트
function testFix() {
    alert('수정 사항 테스트 기능은 준비 중입니다.');
}

// 미리보기 취소
function cancelPreview() {
    document.getElementById('preview-card').style.display = 'none';
}

// 유사 수정 적용
function applySimilarFix(fixId) {
    // 유사 수정 내용 로드 및 적용
    alert('유사 수정 적용 기능은 준비 중입니다.');
}

// 템플릿 적용
function applyTemplate(templateId) {
    // 템플릿 내용 로드 및 적용
    alert('템플릿 적용 기능은 준비 중입니다.');
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
</script>