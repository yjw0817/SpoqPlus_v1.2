<!-- 락커 정면도 모달 -->
<div class="modal fade locker-front-modal" id="lockerFrontModal" tabindex="-1" aria-labelledby="lockerFrontModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lockerFrontModalLabel">
                    <i class="fas fa-th me-2"></i>락커 정면도 - <span id="frontViewGroupName"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- 범례 -->
                <div class="mb-3">
                    <div class="legend-item">
                        <div class="legend-color" style="background: white; border-color: #dee2e6;"></div>
                        <span>사용가능</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #ffebee; border-color: #f44336;"></div>
                        <span>사용중</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #fff3e0; border-color: #ff9800;"></div>
                        <span>점검중</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #f3e5f5; border-color: #9c27b0;"></div>
                        <span>예약됨</span>
                    </div>
                </div>

                <!-- 번호 부여 컨트롤 -->
                <div class="numbering-controls">
                    <h6 class="mb-3"><i class="fas fa-sort-numeric-up me-2"></i>번호 부여 설정</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="numbering-pattern">
                                <label class="form-label mb-0">패턴:</label>
                                <input type="text" class="form-control form-control-sm pattern-input" id="numberPattern" placeholder="예: A-{row}-{col:02d}">
                                <button class="btn btn-sm btn-primary" id="applyPattern">
                                    <i class="fas fa-check me-1"></i>적용
                                </button>
                            </div>
                            <small class="text-muted">
                                {row}: 행 번호, {col}: 열 번호, {floor}: 층 번호<br>
                                :02d는 2자리 0채움 (예: 01, 02, ...)
                            </small>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label mb-1">빠른 설정:</label>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-secondary" onclick="setNumberPattern('A-{row}-{col:02d}')">A-1-01</button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="setNumberPattern('{floor}F-{row}{col:02d}')">1F-101</button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="setNumberPattern('{row:02d}{col:02d}')">0101</button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="setNumberPattern('L{floor}-{row}-{col}')">L1-1-1</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 락커 정면도 뷰 -->
                <div class="locker-front-view">
                    <div id="lockerGridContainer"></div>
                </div>

                <!-- 선택된 락커 정보 -->
                <div id="selectedLockerInfo" class="alert alert-info d-none">
                    <h6 class="mb-2">선택된 락커 정보</h6>
                    <div id="selectedLockerDetails"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary" id="saveLockerNumbers">
                    <i class="fas fa-save me-1"></i>저장
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// 락커 정면도 관련 전역 변수
let currentLockerGroup = null;
let selectedLockers = new Set();
let lockerData = {};

// 락커 정면도 모달 표시
function showLockerFrontView(lockerGroupObject) {
    console.log('🔍 락커 정면도 표시:', lockerGroupObject);
    
    currentLockerGroup = lockerGroupObject;
    const groupData = lockerGroupObject.data;
    
    // 모달 제목 설정
    $('#frontViewGroupName').text(groupData.groupName || '락커 그룹');
    
    // 그리드 생성
    createLockerGrid(groupData);
    
    // 기존 락커 번호 로드
    loadExistingLockerNumbers(groupData.groupSno || groupData.group_sno);
    
    // 모달 표시
    const modal = new bootstrap.Modal(document.getElementById('lockerFrontModal'));
    modal.show();
}

// 락커 그리드 생성
function createLockerGrid(groupData) {
    const container = $('#lockerGridContainer');
    container.empty();
    
    const horizontalCount = parseInt(groupData.horizontalCount) || parseInt(groupData.cols) || 1;
    const verticalCount = parseInt(groupData.logicalHeight) || parseInt(groupData.rows) || 1;
    
    console.log(`📊 락커 그리드 생성: ${horizontalCount}x${verticalCount}`);
    
    // 층별로 그리드 생성 (아래층부터 위로)
    for (let floor = verticalCount; floor >= 1; floor--) {
        const floorDiv = $('<div class="locker-floor mb-3"></div>');
        const floorLabel = $(`<div class="locker-floor-label">${floor}층</div>`);
        floorDiv.append(floorLabel);
        
        const gridDiv = $('<div class="locker-grid"></div>');
        
        // 각 층의 락커 생성
        for (let row = 1; row <= 1; row++) { // 평면도에서는 1행만
            const rowDiv = $('<div class="locker-row"></div>');
            
            for (let col = 1; col <= horizontalCount; col++) {
                const lockerId = `${floor}-${row}-${col}`;
                const lockerNo = generateLockerNumber(floor, row, col);
                
                const cell = $(`
                    <div class="locker-cell" data-locker-id="${lockerId}" 
                         data-floor="${floor}" data-row="${row}" data-col="${col}">
                        <span class="locker-number">${lockerNo}</span>
                    </div>
                `);
                
                // 클릭 이벤트
                cell.on('click', function() {
                    toggleLockerSelection($(this));
                });
                
                // 더블클릭 이벤트 (개별 편집)
                cell.on('dblclick', function(e) {
                    e.stopPropagation();
                    editLockerNumber($(this));
                });
                
                rowDiv.append(cell);
                
                // 락커 데이터 저장
                lockerData[lockerId] = {
                    floor: floor,
                    row: row,
                    col: col,
                    number: lockerNo,
                    status: 'available'
                };
            }
            
            gridDiv.append(rowDiv);
        }
        
        floorDiv.append(gridDiv);
        container.prepend(floorDiv); // 위층이 위로 가도록
    }
}

// 락커 번호 생성
function generateLockerNumber(floor, row, col, pattern = null) {
    if (!pattern) {
        pattern = $('#numberPattern').val() || '{floor}-{row}-{col:02d}';
    }
    
    return pattern
        .replace('{floor}', floor)
        .replace('{row}', row)
        .replace('{col}', col)
        .replace('{col:02d}', col.toString().padStart(2, '0'))
        .replace('{row:02d}', row.toString().padStart(2, '0'));
}

// 패턴 설정
function setNumberPattern(pattern) {
    $('#numberPattern').val(pattern);
    applyNumberPattern();
}

// 번호 패턴 적용
function applyNumberPattern() {
    const pattern = $('#numberPattern').val();
    
    $('.locker-cell').each(function() {
        const $cell = $(this);
        const floor = parseInt($cell.data('floor'));
        const row = parseInt($cell.data('row'));
        const col = parseInt($cell.data('col'));
        const lockerId = $cell.data('locker-id');
        
        const newNumber = generateLockerNumber(floor, row, col, pattern);
        $cell.find('.locker-number').text(newNumber);
        
        // 데이터 업데이트
        if (lockerData[lockerId]) {
            lockerData[lockerId].number = newNumber;
        }
    });
    
    showToast('번호 패턴이 적용되었습니다.', 'success');
}

// 락커 선택 토글
function toggleLockerSelection($cell) {
    const lockerId = $cell.data('locker-id');
    
    if ($cell.hasClass('selected')) {
        $cell.removeClass('selected');
        selectedLockers.delete(lockerId);
    } else {
        $cell.addClass('selected');
        selectedLockers.add(lockerId);
    }
    
    updateSelectedInfo();
}

// 선택된 락커 정보 업데이트
function updateSelectedInfo() {
    const $info = $('#selectedLockerInfo');
    const $details = $('#selectedLockerDetails');
    
    if (selectedLockers.size === 0) {
        $info.addClass('d-none');
        return;
    }
    
    $info.removeClass('d-none');
    $details.html(`선택된 락커: ${selectedLockers.size}개`);
}

// 개별 락커 번호 편집
function editLockerNumber($cell) {
    const currentNumber = $cell.find('.locker-number').text();
    const newNumber = prompt('새 락커 번호를 입력하세요:', currentNumber);
    
    if (newNumber && newNumber !== currentNumber) {
        $cell.find('.locker-number').text(newNumber);
        const lockerId = $cell.data('locker-id');
        if (lockerData[lockerId]) {
            lockerData[lockerId].number = newNumber;
        }
    }
}

// 기존 락커 번호 로드
function loadExistingLockerNumbers(groupSno) {
    if (!groupSno) return;
    
    $.ajax({
        url: '<?= site_url('locker/ajax_get_lockers') ?>',
        type: 'GET',
        data: { group_sno: groupSno },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.lockers) {
                // 기존 락커 번호 적용
                response.lockers.forEach(locker => {
                    const lockerId = `${locker.locker_floor}-${locker.locker_row}-${locker.locker_col}`;
                    const $cell = $(`.locker-cell[data-locker-id="${lockerId}"]`);
                    
                    if ($cell.length > 0) {
                        $cell.find('.locker-number').text(locker.locker_no);
                        
                        // 데이터 업데이트
                        if (lockerData[lockerId]) {
                            lockerData[lockerId].number = locker.locker_no;
                            lockerData[lockerId].status = locker.locker_status || 'available';
                        }
                        
                        // 상태에 따른 스타일 적용
                        if (locker.locker_status) {
                            $cell.removeClass('available occupied maintenance reserved');
                            $cell.addClass(locker.locker_status);
                        }
                    }
                });
            }
        },
        error: function() {
            console.error('Failed to load existing locker numbers');
        }
    });
}

// 락커 번호 저장
$('#saveLockerNumbers').on('click', function() {
    saveLockerNumbers();
});

// 락커 번호 저장 함수
function saveLockerNumbers() {
    const groupSno = currentLockerGroup.data.groupSno || currentLockerGroup.data.group_sno;
    
    if (!groupSno) {
        showToast('락커 그룹이 먼저 저장되어야 합니다.', 'warning');
        return;
    }
    
    // 저장할 데이터 준비
    const lockersToSave = [];
    for (const [lockerId, data] of Object.entries(lockerData)) {
        lockersToSave.push({
            group_sno: groupSno,
            locker_no: data.number,
            locker_floor: data.floor,
            locker_row: data.row,
            locker_col: data.col,
            status: data.status || 'available'
        });
    }
    
    console.log('Saving locker numbers:', lockersToSave);
    
    $.ajax({
        url: '<?= site_url('locker/ajax_save_locker_numbers') ?>',
        type: 'POST',
        data: {
            group_sno: groupSno,
            lockers: JSON.stringify(lockersToSave),
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                showToast('락커 번호가 저장되었습니다.', 'success');
                $('#lockerFrontModal').modal('hide');
            } else {
                showToast('저장 실패: ' + response.message, 'error');
            }
        },
        error: function() {
            showToast('저장 중 오류가 발생했습니다.', 'error');
        }
    });
}

// 패턴 적용 버튼 이벤트
$('#applyPattern').on('click', applyNumberPattern);

// 락커 그룹에 정면도 보기 메뉴 추가
function addLockerGroupContextMenu() {
    // 기존 컨텍스트 메뉴에 추가하거나 새로 생성
    $(document).on('contextmenu', function(e) {
        const target = e.target;
        const fabricObject = canvas.findTarget(e.originalEvent);
        
        if (fabricObject && fabricObject.data && fabricObject.data.type === 'locker-group') {
            e.preventDefault();
            
            // 컨텍스트 메뉴 표시
            showLockerGroupContextMenu(e.pageX, e.pageY, fabricObject);
        }
    });
}

// 컨텍스트 메뉴 표시
function showLockerGroupContextMenu(x, y, lockerGroup) {
    // 기존 메뉴 제거
    $('.locker-context-menu').remove();
    
    const menu = $(`
        <div class="locker-context-menu" style="
            position: absolute;
            left: ${x}px;
            top: ${y}px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 10000;
            padding: 5px 0;
        ">
            <div class="menu-item" onclick="showLockerFrontView(fabricCanvases[Object.keys(fabricCanvases)[0]].getActiveObject())" style="
                padding: 8px 20px;
                cursor: pointer;
                hover: background: #f0f0f0;
            ">
                <i class="fas fa-th me-2"></i>정면도 보기
            </div>
            <div class="menu-item" onclick="editSelectedLockerGroup()" style="
                padding: 8px 20px;
                cursor: pointer;
            ">
                <i class="fas fa-edit me-2"></i>수정
            </div>
            <div class="menu-item" onclick="deleteSelectedLockerGroup()" style="
                padding: 8px 20px;
                cursor: pointer;
                color: #dc3545;
            ">
                <i class="fas fa-trash me-2"></i>삭제
            </div>
        </div>
    `);
    
    $('body').append(menu);
    
    // 메뉴 외부 클릭시 닫기
    $(document).one('click', function() {
        menu.remove();
    });
}

// 초기화 시 이벤트 추가
$(document).ready(function() {
    // fabricCanvases가 정의될 때까지 대기
    setTimeout(function() {
        if (typeof fabricCanvases !== 'undefined') {
            setupLockerGroupDoubleClick();
        }
    }, 1000);
});

// 락커 그룹 더블클릭 이벤트 설정
function setupLockerGroupDoubleClick() {
    // fabricCanvases가 정의되었는지 확인
    if (typeof fabricCanvases === 'undefined') {
        console.warn('fabricCanvases is not defined yet');
        return;
    }
    
    // 각 캔버스에 대해 이벤트 설정
    Object.values(fabricCanvases).forEach(canvas => {
        canvas.on('mouse:dblclick', function(options) {
            if (options.target && options.target.data && options.target.data.type === 'locker-group') {
                console.log('락커 그룹 더블클릭:', options.target.data);
                showLockerFrontView(options.target);
            }
        });
    });
}

// 새 캔버스가 추가될 때마다 이벤트 설정
window.setupLockerGroupDoubleClick = setupLockerGroupDoubleClick;
</script>

<!-- 락커 상태 관리 모달 포함 -->
<?php include(__DIR__ . '/locker_status_modal.php'); ?>