<style>
.account-management {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin: 20px 0;
}
.account-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}
.account-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.account-card.default {
    border-color: #007bff;
    background: #f8f9ff;
}
.account-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
}
.bank-logo {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #007bff;
    margin-right: 15px;
}
.account-info h5 {
    margin: 0;
    font-weight: 600;
}
.account-info p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9em;
}
.account-toggle {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}
.account-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}
.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 34px;
}
.toggle-slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}
input:checked + .toggle-slider {
    background-color: #28a745;
}
input:checked + .toggle-slider:before {
    transform: translateX(26px);
}
.account-details {
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
}
.detail-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}
.detail-label {
    font-weight: 500;
    color: #6c757d;
}
.detail-value {
    color: #343a40;
}
.default-badge {
    background: #007bff;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75em;
    font-weight: 500;
}
.virtual-account-section {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.va-settings {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin-top: 15px;
}
.va-setting-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
}
.statistics-section {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 15px;
}
.stat-item {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}
.stat-number {
    font-size: 24px;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 5px;
}
.stat-label {
    font-size: 12px;
    color: #6c757d;
}
</style>

<!-- Main content -->
<h1 class="page-header"><?php echo $title ?></h1>

<!-- 지점 선택 (슈퍼 관리자용) -->
<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'sadmin'): ?>
<div class="panel panel-inverse mb-3">
    <div class="panel-body">
        <div class="row align-items-center">
            <div class="col-md-3">
                <label class="fw-bold">지점 선택:</label>
            </div>
            <div class="col-md-6">
                <select class="form-control" id="branch_select" onchange="changeBranch()">
                    <option value="">지점을 선택하세요</option>
                    <?php if(isset($branch_list) && is_array($branch_list)): ?>
                        <?php foreach($branch_list as $branch): ?>
                            <option value="<?= $branch['BCOFF_CD'] ?? '' ?>" 
                                    <?= (isset($selected_branch) && $selected_branch == ($branch['BCOFF_CD'] ?? '')) ? 'selected' : '' ?>>
                                <?= ($branch['BCOFF_NM'] ?? '') . ' (' . ($branch['MNGR_NM'] ?? '') . ')' ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- 계좌 관리 대시보드 -->
<div class="account-management">
    <div class="row">
        <div class="col-md-8">
            <h4><i class="fas fa-university"></i> 계좌 관리</h4>
            <p class="text-muted">입금 계좌 및 가상계좌 설정을 관리하고 계좌별 거래 현황을 확인하세요.</p>
        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="addNewAccount()">
                    <i class="fas fa-plus"></i> 계좌 추가
                </button>
                <button type="button" class="btn btn-success" onclick="testAccountConnections()">
                    <i class="fas fa-check-circle"></i> 연결 테스트
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 등록된 계좌 목록 -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">등록된 계좌</h4>
        <div class="panel-tools">
            <button type="button" class="btn btn-sm btn-info" onclick="refreshAccounts()">
                <i class="fas fa-sync"></i> 새로고침
            </button>
        </div>
    </div>
    <div class="panel-body">
        <?php if(isset($bank_accounts) && count($bank_accounts) > 0): ?>
            <?php foreach($bank_accounts as $account): ?>
            <div class="account-card <?= ($account['is_default'] ?? false) ? 'default' : '' ?>" data-account-id="<?= $account['id'] ?? '' ?>">
                <div class="account-header">
                    <div class="d-flex align-items-center">
                        <div class="bank-logo">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="account-info">
                            <h5>
                                <?= $account['bank_nm'] ?>
                                <?php if($account['is_default'] ?? false): ?>
                                    <span class="default-badge">기본</span>
                                <?php endif; ?>
                            </h5>
                            <p><?= $account['account_type'] ?? '일반' ?> 계좌</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <label class="account-toggle">
                            <input type="checkbox" <?= ($account['is_active'] ?? false) ? 'checked' : '' ?> 
                                   onchange="toggleAccount('<?= $account['id'] ?? '' ?>', this.checked)">
                            <span class="toggle-slider"></span>
                        </label>
                        <div class="dropdown ml-2">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="editAccount('<?= $account['id'] ?? '' ?>')">
                                    <i class="fas fa-edit"></i> 수정
                                </a></li>
                                <?php if(!($account['is_default'] ?? false)): ?>
                                <li><a class="dropdown-item" href="#" onclick="setDefaultAccount('<?= $account['id'] ?? '' ?>')">
                                    <i class="fas fa-star"></i> 기본 설정
                                </a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="#" onclick="testSingleAccount('<?= $account['id'] ?? '' ?>')">
                                    <i class="fas fa-check-circle"></i> 연결 테스트
                                </a></li>
                                <?php if($account['account_type'] !== '가상'): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteAccount('<?= $account['id'] ?? '' ?>')">
                                    <i class="fas fa-trash"></i> 삭제
                                </a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="account-details">
                    <div class="detail-row">
                        <span class="detail-label">계좌번호:</span>
                        <span class="detail-value"><?= $account['account_no'] ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">예금주:</span>
                        <span class="detail-value"><?= $account['account_holder'] ?></span>
                    </div>
                    <?php if(isset($account['bank_cd'])): ?>
                    <div class="detail-row">
                        <span class="detail-label">은행코드:</span>
                        <span class="detail-value"><?= $account['bank_cd'] ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($account['monthly_amount'])): ?>
                    <div class="detail-row">
                        <span class="detail-label">이번 달 입금:</span>
                        <span class="detail-value text-success"><?= number_format($account['monthly_amount']) ?>원</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-university fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">등록된 계좌가 없습니다</h5>
                <p class="text-muted">새 계좌를 추가하여 결제 서비스를 시작하세요.</p>
                <button type="button" class="btn btn-primary" onclick="addNewAccount()">
                    <i class="fas fa-plus"></i> 첫 번째 계좌 추가
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- 가상계좌 설정 -->
<div class="virtual-account-section">
    <h5><i class="fas fa-credit-card"></i> 가상계좌 설정</h5>
    <p class="text-muted">고객별 가상계좌 발급 및 관리 설정을 구성하세요.</p>
    
    <div class="va-settings">
        <div class="va-setting-item">
            <span>가상계좌 사용</span>
            <label class="account-toggle">
                <input type="checkbox" <?= ($va_settings['enabled'] ?? false) ? 'checked' : '' ?> 
                       onchange="toggleVirtualAccount(this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>
        <div class="va-setting-item">
            <span>자동 발급</span>
            <label class="account-toggle">
                <input type="checkbox" <?= ($va_settings['auto_issue'] ?? false) ? 'checked' : '' ?> 
                       onchange="toggleAutoIssue(this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>
        <div class="va-setting-item">
            <span>입금 알림</span>
            <label class="account-toggle">
                <input type="checkbox" <?= ($va_settings['notification'] ?? false) ? 'checked' : '' ?> 
                       onchange="toggleNotification(this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>
        <div class="va-setting-item">
            <span>만료 시간</span>
            <select class="form-control form-control-sm" style="width: auto;" onchange="updateExpireTime(this.value)">
                <option value="24" <?= ($va_settings['expire_hours'] ?? 24) == 24 ? 'selected' : '' ?>>24시간</option>
                <option value="48" <?= ($va_settings['expire_hours'] ?? 24) == 48 ? 'selected' : '' ?>>48시간</option>
                <option value="72" <?= ($va_settings['expire_hours'] ?? 24) == 72 ? 'selected' : '' ?>>72시간</option>
                <option value="168" <?= ($va_settings['expire_hours'] ?? 24) == 168 ? 'selected' : '' ?>>7일</option>
            </select>
        </div>
    </div>
</div>

<!-- 계좌별 통계 -->
<div class="statistics-section">
    <h5><i class="fas fa-chart-bar"></i> 계좌별 거래 통계 (최근 30일)</h5>
    <div class="stat-grid">
        <div class="stat-item">
            <div class="stat-number"><?= number_format($stats['total_amount'] ?? 0) ?></div>
            <div class="stat-label">총 입금액 (원)</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?= number_format($stats['total_count'] ?? 0) ?></div>
            <div class="stat-label">총 입금 건수</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?= number_format($stats['avg_amount'] ?? 0) ?></div>
            <div class="stat-label">평균 입금액 (원)</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?= count($bank_accounts ?? []) ?></div>
            <div class="stat-label">등록 계좌 수</div>
        </div>
    </div>
</div>

<!-- 계좌 추가/수정 모달 -->
<div class="modal fade" id="accountModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="accountModalTitle">계좌 추가</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="accountForm">
                    <input type="hidden" id="accountId" name="account_id">
                    
                    <div class="form-group">
                        <label>은행명 <span class="text-danger">*</span></label>
                        <select class="form-control" id="bankName" name="bank_nm" required>
                            <option value="">은행 선택</option>
                            <option value="국민은행">국민은행</option>
                            <option value="신한은행">신한은행</option>
                            <option value="우리은행">우리은행</option>
                            <option value="하나은행">하나은행</option>
                            <option value="기업은행">기업은행</option>
                            <option value="농협은행">농협은행</option>
                            <option value="카카오뱅크">카카오뱅크</option>
                            <option value="케이뱅크">케이뱅크</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>계좌번호 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="accountNo" name="account_no" 
                               placeholder="- 없이 숫자만 입력" required>
                    </div>
                    
                    <div class="form-group">
                        <label>예금주 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="accountHolder" name="account_holder" 
                               placeholder="예금주명" required>
                    </div>
                    
                    <div class="form-group">
                        <label>계좌 유형</label>
                        <select class="form-control" id="accountType" name="account_type">
                            <option value="일반">일반 계좌</option>
                            <option value="급여">급여 계좌</option>
                            <option value="적금">적금 계좌</option>
                        </select>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="isDefault" name="is_default">
                        <label class="form-check-label" for="isDefault">
                            기본 계좌로 설정
                        </label>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="isActive" name="is_active" checked>
                        <label class="form-check-label" for="isActive">
                            계좌 활성화
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveAccount()">
                    <i class="fas fa-save"></i> 저장
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
            </div>
        </div>
    </div>
</div>	
	
</section>


<?=$jsinc ?>

<script>


$(function () {
    // 초기 로딩
    <?php if (isset($selected_branch) && $selected_branch): ?>
    loadAccountStats();
    <?php endif; ?>
});

function addNewAccount() {
    $('#accountModalTitle').text('계좌 추가');
    $('#accountForm')[0].reset();
    $('#accountId').val('');
    $('#accountModal').modal('show');
}

function editAccount(accountId) {
    $('#accountModalTitle').text('계좌 수정');
    
    // 계좌 정보 로드
    $.ajax({
        url: '/smgrmain/ajax_get_account_info',
        type: 'GET',
        data: { account_id: accountId },
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success') {
                const account = response.data;
                $('#accountId').val(account.id);
                $('#bankName').val(account.bank_nm);
                $('#accountNo').val(account.account_no);
                $('#accountHolder').val(account.account_holder);
                $('#accountType').val(account.account_type);
                $('#isDefault').prop('checked', account.is_default);
                $('#isActive').prop('checked', account.is_active);
                $('#accountModal').modal('show');
            } else {
                alertToast('error', response.message || '계좌 정보를 불러올 수 없습니다.');
            }
        },
        error: function() {
            alertToast('error', '서버 오류가 발생했습니다.');
        }
    });
}

function saveAccount() {
    if (!validateAccountForm()) return;
    
    const formData = new FormData($('#accountForm')[0]);
    const isEdit = $('#accountId').val() !== '';
    
    // 지점 코드 추가
    const bcoffCd = getSelectedBranchCode();
    if (!bcoffCd) {
        alertToast('error', '지점을 선택해주세요.');
        return;
    }
    formData.append('bcoff_cd', bcoffCd);
    
    // 회사 코드 추가 (슈퍼 관리자인 경우)
    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'sadmin'): ?>
    formData.append('comp_cd', '<?= $_SESSION['comp_cd'] ?? '' ?>');
    <?php endif; ?>
    
    $.ajax({
        url: isEdit ? '/smgrmain/ajax_update_account' : '/smgrmain/ajax_add_account',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success') {
                alertToast('success', isEdit ? '계좌가 수정되었습니다.' : '계좌가 추가되었습니다.');
                $('#accountModal').modal('hide');
                setTimeout(() => location.reload(), 1500);
            } else {
                alertToast('error', response.message || '처리 중 오류가 발생했습니다.');
            }
        },
        error: function() {
            alertToast('error', '서버 오류가 발생했습니다.');
        }
    });
}

function validateAccountForm() {
    const bankName = $('#bankName').val();
    const accountNo = $('#accountNo').val();
    const accountHolder = $('#accountHolder').val();
    
    if (!bankName) {
        alertToast('error', '은행명을 선택해주세요.');
        $('#bankName').focus();
        return false;
    }
    
    if (!accountNo) {
        alertToast('error', '계좌번호를 입력해주세요.');
        $('#accountNo').focus();
        return false;
    }
    
    // 계좌번호 유효성 검사 (숫자만)
    if (!/^\d+$/.test(accountNo)) {
        alertToast('error', '계좌번호는 숫자만 입력 가능합니다.');
        $('#accountNo').focus();
        return false;
    }
    
    if (!accountHolder) {
        alertToast('error', '예금주명을 입력해주세요.');
        $('#accountHolder').focus();
        return false;
    }
    
    return true;
}

function toggleAccount(accountId, isActive) {
    $.ajax({
        url: '/smgrmain/ajax_toggle_account',
        type: 'POST',
        data: { 
            account_id: accountId,
            is_active: isActive ? 'Y' : 'N'
        },
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success') {
                alertToast('success', isActive ? '계좌가 활성화되었습니다.' : '계좌가 비활성화되었습니다.');
            } else {
                alertToast('error', response.message || '처리 중 오류가 발생했습니다.');
                // 체크박스 상태 되돌리기
                $(`input[onchange*="${accountId}"]`).prop('checked', !isActive);
            }
        },
        error: function() {
            alertToast('error', '서버 오류가 발생했습니다.');
            $(`input[onchange*="${accountId}"]`).prop('checked', !isActive);
        }
    });
}

function setDefaultAccount(accountId) {
    ToastConfirm.fire({
        icon: "question",
        title: "기본 계좌 설정",
        html: "<font color='#000000'>이 계좌를 기본 계좌로 설정하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#007bff",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/smgrmain/ajax_set_default_account',
                type: 'POST',
                data: { account_id: accountId },
                dataType: 'json',
                success: function(response) {
                    if (response.result === 'success') {
                        alertToast('success', '기본 계좌가 설정되었습니다.');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        alertToast('error', response.message || '처리 중 오류가 발생했습니다.');
                    }
                },
                error: function() {
                    alertToast('error', '서버 오류가 발생했습니다.');
                }
            });
        }
    });
}

function deleteAccount(accountId) {
    ToastConfirm.fire({
        icon: "warning",
        title: "계좌 삭제",
        html: "<font color='#000000'>정말로 이 계좌를 삭제하시겠습니까?<br>삭제된 계좌는 복구할 수 없습니다.</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/smgrmain/ajax_delete_account',
                type: 'POST',
                data: { account_id: accountId },
                dataType: 'json',
                success: function(response) {
                    if (response.result === 'success') {
                        alertToast('success', '계좌가 삭제되었습니다.');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        alertToast('error', response.message || '삭제 중 오류가 발생했습니다.');
                    }
                },
                error: function() {
                    alertToast('error', '서버 오류가 발생했습니다.');
                }
            });
        }
    });
}

function testSingleAccount(accountId) {
    const card = $(`.account-card[data-account-id="${accountId}"]`);
    const originalText = card.find('.account-info h5').text();
    
    // 테스트 중 표시
    card.find('.account-info h5').html('<i class="fas fa-spinner fa-spin"></i> 연결 테스트 중...');
    
    $.ajax({
        url: '/smgrmain/ajax_test_account_connection',
        type: 'POST',
        data: { account_id: accountId },
        dataType: 'json',
        success: function(response) {
            card.find('.account-info h5').text(originalText);
            
            if (response.result === 'success') {
                alertToast('success', '계좌 연결 테스트 성공');
            } else {
                alertToast('error', response.message || '계좌 연결 실패');
            }
        },
        error: function() {
            card.find('.account-info h5').text(originalText);
            alertToast('error', '테스트 중 오류가 발생했습니다.');
        }
    });
}

function testAccountConnections() {
    ToastConfirm.fire({
        icon: "question",
        title: "전체 계좌 연결 테스트",
        html: "<font color='#000000'>등록된 모든 계좌의 연결 상태를 테스트하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
        if (result.isConfirmed) {
            $('.account-card').each(function() {
                const accountId = $(this).data('account-id');
                if (accountId) {
                    testSingleAccount(accountId);
                }
            });
        }
    });
}

function toggleVirtualAccount(enabled) {
    updateVASettings('enabled', enabled);
}

function toggleAutoIssue(enabled) {
    updateVASettings('auto_issue', enabled);
}

function toggleNotification(enabled) {
    updateVASettings('notification', enabled);
}

function updateExpireTime(hours) {
    updateVASettings('expire_hours', parseInt(hours));
}

function updateVASettings(key, value) {
    const bcoffCd = getSelectedBranchCode();
    if (!bcoffCd) {
        alertToast('error', '지점을 선택해주세요.');
        return;
    }
    
    $.ajax({
        url: '/smgrmain/ajax_update_va_settings',
        type: 'POST',
        data: { 
            setting_key: key,
            setting_value: value,
            bcoff_cd: bcoffCd,
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'sadmin'): ?>
            comp_cd: '<?= $_SESSION['comp_cd'] ?? '' ?>'
            <?php endif; ?>
        },
        dataType: 'json',
        success: function(response) {
            if (response.result !== 'success') {
                alertToast('error', response.message || '설정 저장 중 오류가 발생했습니다.');
            }
        }
    });
}

function refreshAccounts() {
    location.reload();
}

function loadAccountStats() {
    $.ajax({
        url: '/smgrmain/ajax_get_account_stats',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success') {
                updateStatistics(response.data);
            }
        }
    });
}

function updateStatistics(stats) {
    $('.stat-grid .stat-item:nth-child(1) .stat-number').text(numberFormat(stats.total_amount || 0));
    $('.stat-grid .stat-item:nth-child(2) .stat-number').text(numberFormat(stats.total_count || 0));
    $('.stat-grid .stat-item:nth-child(3) .stat-number').text(numberFormat(stats.avg_amount || 0));
}

function numberFormat(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// 지점 변경 함수
function changeBranch() {
    const selectedBranch = $('#branch_select').val();
    if (selectedBranch) {
        window.location.href = '/smgrmain/bank_account_management?bcoff_cd=' + selectedBranch;
    } else {
        window.location.href = '/smgrmain/bank_account_management';
    }
}

// 선택된 지점 코드 가져오기
function getSelectedBranchCode() {
    // URL 파라미터에서 먼저 확인
    const urlParams = new URLSearchParams(window.location.search);
    const urlBcoffCd = urlParams.get('bcoff_cd');
    if (urlBcoffCd) {
        return urlBcoffCd;
    }
    
    // 세션에서 확인
    <?php if (isset($_SESSION['bcoff_cd']) && $_SESSION['bcoff_cd']): ?>
    return '<?= $_SESSION['bcoff_cd'] ?>';
    <?php else: ?>
    return '';
    <?php endif; ?>
}

</script>