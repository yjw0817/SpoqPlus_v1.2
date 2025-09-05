<!-- 락커 상태 관리 모달 -->
<div class="modal fade" id="lockerStatusModal" tabindex="-1" aria-labelledby="lockerStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lockerStatusModalLabel">
                    <i class="fas fa-info-circle me-2"></i>락커 상태 관리
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="lockerStatusForm">
                    <input type="hidden" id="statusLockerSno">
                    
                    <div class="mb-3">
                        <label class="form-label">락커 번호</label>
                        <input type="text" class="form-control" id="statusLockerNo" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">상태</label>
                        <select class="form-select" id="statusLockerStatus">
                            <option value="available">사용가능</option>
                            <option value="occupied">사용중</option>
                            <option value="maintenance">점검중</option>
                            <option value="reserved">예약됨</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="userAssignDiv" style="display: none;">
                        <label class="form-label">사용자</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="statusUserId" placeholder="사용자 ID">
                            <button class="btn btn-outline-secondary" type="button" id="searchUserBtn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <div id="userInfo" class="mt-2"></div>
                    </div>
                    
                    <div class="mb-3" id="expireDateDiv" style="display: none;">
                        <label class="form-label">만료일</label>
                        <input type="date" class="form-control" id="statusExpireDate">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">메모</label>
                        <textarea class="form-control" id="statusNotes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                <button type="button" class="btn btn-primary" id="saveLockerStatus">저장</button>
            </div>
        </div>
    </div>
</div>

<script>
// 락커 상태 변경 시 UI 업데이트
$('#statusLockerStatus').on('change', function() {
    const status = $(this).val();
    
    if (status === 'occupied' || status === 'reserved') {
        $('#userAssignDiv, #expireDateDiv').show();
    } else {
        $('#userAssignDiv, #expireDateDiv').hide();
        $('#statusUserId').val('');
        $('#statusExpireDate').val('');
        $('#userInfo').empty();
    }
});

// 락커 상태 편집 모달 표시
function showLockerStatusModal(lockerData) {
    $('#statusLockerSno').val(lockerData.locker_sno || '');
    $('#statusLockerNo').val(lockerData.locker_no || '');
    $('#statusLockerStatus').val(lockerData.status || 'available').trigger('change');
    $('#statusUserId').val(lockerData.assigned_user_id || '');
    $('#statusExpireDate').val(lockerData.expire_date || '');
    $('#statusNotes').val(lockerData.notes || '');
    
    const modal = new bootstrap.Modal(document.getElementById('lockerStatusModal'));
    modal.show();
}

// 락커 상태 저장
$('#saveLockerStatus').on('click', function() {
    const data = {
        locker_sno: $('#statusLockerSno').val(),
        status: $('#statusLockerStatus').val(),
        assigned_user_id: $('#statusUserId').val(),
        expire_date: $('#statusExpireDate').val(),
        notes: $('#statusNotes').val()
    };
    
    $.ajax({
        url: '<?= site_url('locker/ajax_update_locker_status') ?>',
        type: 'POST',
        data: {
            ...data,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                showToast('락커 상태가 업데이트되었습니다.', 'success');
                $('#lockerStatusModal').modal('hide');
                
                // 정면도 뷰 업데이트
                updateLockerCellStatus(data.locker_sno, data.status);
            } else {
                showToast('상태 업데이트 실패: ' + response.message, 'error');
            }
        },
        error: function() {
            showToast('상태 업데이트 중 오류가 발생했습니다.', 'error');
        }
    });
});

// 락커 셀 상태 업데이트
function updateLockerCellStatus(lockerSno, status) {
    // TODO: 정면도에서 해당 락커 셀의 상태 업데이트
}

// 사용자 검색
$('#searchUserBtn').on('click', function() {
    const userId = $('#statusUserId').val();
    
    if (!userId) {
        showToast('사용자 ID를 입력하세요.', 'warning');
        return;
    }
    
    // TODO: 사용자 검색 API 호출
    $('#userInfo').html(`<div class="alert alert-info">사용자 정보: ${userId}</div>`);
});
</script>