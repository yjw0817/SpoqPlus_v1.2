<style>
.setup-progress {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin: 20px 0;
}
.progress-step {
    display: inline-block;
    width: 18%;
    text-align: center;
    position: relative;
    margin-bottom: 10px;
}
.progress-step:before {
    content: '';
    position: absolute;
    top: 15px;
    left: 50%;
    width: 100%;
    height: 2px;
    background: #ddd;
    z-index: 1;
}
.progress-step:first-child:before {
    display: none;
}
.progress-step.completed:before {
    background: #28a745;
}
.step-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #ddd;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 2;
    color: white;
    font-weight: bold;
    margin-bottom: 5px;
}
.progress-step.completed .step-icon {
    background: #28a745;
}
.progress-step.current .step-icon {
    background: #007bff;
}
.status-badge {
    font-size: 0.8em;
    padding: 2px 6px;
    border-radius: 3px;
}
.status-pending { background: #ffc107; color: #000; }
.status-progress { background: #007bff; color: #fff; }
.status-completed { background: #28a745; color: #fff; }
.status-blocked { background: #dc3545; color: #fff; }
.branch-row:hover {
    background-color: #f8f9fa;
}
.branch-row.table-active {
    background-color: #e7f3ff;
}
</style>
<?php
// Initialize default values to prevent errors
$sDef = SpoqDef();
$setup_status = $setup_status ?? [
    'basic_info_yn' => 'N',
    'pg_setup_yn' => 'N', 
    'van_setup_yn' => 'N',
    'bank_setup_yn' => 'N',
    'ready_yn' => 'N',
    'setup_progress' => 0
];
$bc_appct_list = $bc_appct_list ?? [];
$search_val = $search_val ?? ['listCount' => 0];
$pager = $pager ?? '';
?>
<!-- Main content -->
<h1 class="page-header"><?php echo $title ?></h1>

<!-- 지점 설정 진행 현황 대시보드 -->
<div class="setup-progress">
    <h4 class="mb-3"><i class="fas fa-cogs"></i> 지점 설정 진행 현황</h4>
    
    <!-- 지점 선택 안내 -->
    <div id="no-branch-selected" class="text-center py-4">
        <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
        <p class="text-muted">하단 테이블에서 지점을 선택하면 해당 지점의 설정 진행 현황이 표시됩니다.</p>
    </div>
    
    <!-- 선택된 지점 정보 -->
    <div id="selected-branch-info" style="display: none;">
        <div class="mb-3">
            <strong>선택된 지점:</strong> <span id="selected-branch-name">-</span>
        </div>
        
        <!-- 설정 단계 진행바 -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="progress-step" id="step-1">
                    <div class="step-icon">1</div>
                    <div class="step-label">기본정보</div>
                </div>
                <div class="progress-step" id="step-2">
                    <div class="step-icon">2</div>
                    <div class="step-label">PG설정</div>
                </div>
                <div class="progress-step" id="step-3">
                    <div class="step-icon">3</div>
                    <div class="step-label">VAN설정</div>
                </div>
                <div class="progress-step" id="step-4">
                    <div class="step-icon">4</div>
                    <div class="step-label">계좌설정</div>
                </div>
                <div class="progress-step" id="step-5">
                    <div class="step-icon">5</div>
                    <div class="step-label">운영준비</div>
                </div>
            </div>
        </div>
        
        <!-- 현재 진행률 -->
        <div class="row">
            <div class="col-md-6">
                <div class="progress mb-2">
                    <div class="progress-bar" id="progress-bar" role="progressbar" style="width: 0%" 
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        0%
                    </div>
                </div>
                <small class="text-muted">설정 완료율</small>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-primary btn-sm" id="continue-setup-btn" onclick="continueSetupForBranch()" style="display: none;">
                    <i class="fas fa-play"></i> 설정 계속하기
                </button>
                <span class="badge badge-success" id="setup-complete-badge" style="display: none;"><i class="fas fa-check"></i> 설정 완료</span>
            </div>
        </div>
    </div>
</div>

<!-- 승인된 지점 관리 -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">승인된 지점 관리</h4>
		<div class="panel-tools">
            <button type="button" class="btn btn-sm btn-info" onclick="refreshList()">
                <i class="fas fa-sync"></i> 새로고침
            </button>
        </div>
	</div>
					
	<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
        <!-- 검색 필터 -->
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" class="form-control" id="searchText" placeholder="지점명/관리자명 검색">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-primary" onclick="searchBranches()">
                    <i class="fas fa-search"></i> 검색
                </button>
            </div>
        </div>

		<!-- TABLE [START] -->
		<table class="table table-bordered table-hover table-striped col-md-12">
			<thead>
				<tr style='text-align:center'>
					<th style='width:3%'>번호</th>
					<th style='width:12%'>회사명</th>
					<th style='width:12%'>지점명(코드)</th>
					<th style='width:12%'>지점 전화번호</th>
					<th style='width:10%'>관리자 아이디</th>
					<th style='width:10%'>관리자명</th>
					<th style='width:12%'>관리자 전화번호</th>
					<th style='width:12%'>승인일</th>
					<th style='width:10%'>설정진행률</th>
					<th style='width:17%'>관리</th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($bc_appct_list) && count($bc_appct_list) > 0): ?>
                <?php foreach($bc_appct_list as $r): ?>
				<tr class="branch-row" data-bcoff-cd="<?php echo $r['BCOFF_CD'] ?? ''?>" data-bcoff-name="<?php echo ($r['BCOFF_NM'] ?? '-') . '(' . ($r['BCOFF_CD'] ?? '-') . ')'?>" style="cursor: pointer;">
					<td class="text-center"><?php echo $search_val['listCount'] ?? 0?></td>
					<td><?php echo $r['COMP_NM'] ?? '-'?> </td>
					<td><?php echo ($r['BCOFF_NM'] ?? '-') . '(' . ($r['BCOFF_CD'] ?? '-') . ')'?></td>
					<td class="text-center"><?php echo isset($r['BCOFF_TELNO']) ? $r['BCOFF_TELNO'] : '-'?></td>
					<td><?php echo $r['BCOFF_MGMT_ID'] ?? '-'?></td>
					<td><?php echo $r['MNGR_NM'] ?? '-'?></td>
					<td class="text-center"><?php echo isset($r['MNGR_TELNO']) ? $r['MNGR_TELNO'] : '-'?></td>
					<td class="text-center"><?php echo $r['BCOFF_APPV_DATETM'] ?? '-'?></td>
                    <td class="text-center">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: <?= $r['setup_progress'] ?? 0 ?>%" 
                                 aria-valuenow="<?= $r['setup_progress'] ?? 0 ?>" 
                                 aria-valuemin="0" aria-valuemax="100">
                                <?= $r['setup_progress'] ?? 0 ?>%
                            </div>
                        </div>
                    </td>
					<td class="text-center">
                        <button type="button" class="btn btn-info btn-xs" onclick="console.log('Button clicked for:', '<?php echo $r['BCOFF_CD'] ?? ''?>'); viewBranchDetail('<?php echo $r['BCOFF_CD'] ?? ''?>');">
                            <i class="fas fa-eye"></i> 상세
                        </button>
                        <?php if(($r['setup_progress'] ?? 0) < 100): ?>
                        <button type="button" class="btn btn-primary btn-xs" onclick="setupBranch('<?php echo $r['BCOFF_CD'] ?? ''?>');">
                            <i class="fas fa-cogs"></i> 설정
                        </button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-success btn-xs" onclick="managePayments('<?php echo $r['BCOFF_CD'] ?? ''?>');">
                            <i class="fas fa-credit-card"></i> 결제관리
                        </button>
					</td>
				</tr>
				<?php
				if(isset($search_val['listCount'])) $search_val['listCount']--;
				endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i><br>
                        승인된 지점이 없습니다.
                    </td>
                </tr>
                <?php endif; ?>
			</tbody>
		</table>
		<!-- TABLE [END] -->

	<!-- CARD FOOTER [START] -->
	<div class="card-footer clearfix">
		<!-- PAGZING [START] -->
		<?=$pager?>
		<!-- PAGZING [END] -->
	</div>
	<!-- CARD FOOTER [END] -->




	</div>
	<!-- CARD BODY [END] -->

</div>
	

<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_bc_appct_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">지점 신청하기</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            	
            	
            	<!-- FORM [START] -->
            	<form id="bc_appct_form" method="post" action="/smgrmain/ajax_bc_appct_proc">
            	<input type="hidden" name="id_chk" id="id_chk" value="N" />
            	
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text jreq" style='width:150px'>지점관리자 아이디<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_mgmt_id" id="bcoff_mgmt_id" >
                	<span class="input-group-append">
                    	<button type="button" class="basic_bt06" id="btn_code_chk">중복체크</button>
                    </span>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점관리자 비밀번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="password" class="form-control" name="bcoff_mgmt_pwd" id="bcoff_mgmt_pwd" autocomplete="new-password">
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점관리자 이름<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="mngr_nm" id="mngr_nm" autocomplete='off'>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점관리자 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control phone-input" name="mngr_telno" id="mngr_telno" autocomplete='off' >
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>대표자명<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="ceo_nm" id="ceo_nm" autocomplete='off'>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>대표자 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control phone-input" name="ceo_telno" id="ceo_telno" autocomplete='off'>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점명<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_nm" id="bcoff_nm" autocomplete='off'>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점 주소</span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_addr" id="bcoff_addr" autocomplete='off'>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control phone-input" name="bcoff_telno" id="bcoff_telno" autocomplete='off' >
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점 전화번호2</span>
                	</span>
                	<input type="text" class="form-control phone-input" name="bcoff_telno2" id="bcoff_telno2" autocomplete='off' >
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점 메모</span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_memo" id="bcoff_memo" autocomplete='off'>
                </div>
                
                <div id="id_chk_true" style="display:none">
                	<button type="button" class="btn btn-info  btn-block" >중복체크가 완료 되었습니다.</button>
                </div>
                <div id="id_chk_false" class="mt20">
                	<button type="button" class="btn btn-danger  btn-block" >중복체크를 해주세요</button>
                </div>
            	
            	</form>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="bc_appct_form_create_btn">등록하기</button>
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-default END ] ======================================= -->	

<!-- 신청 상세 정보 모달 -->
<div class="modal fade" id="requestDetailModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">지점 신청 상세 정보</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="requestDetailContent">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">신청 정보를 불러오는 중...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
	
</section>


<?=$jsinc ?>

<script>


$(function () {
    $('.select2').select2();
    
    // 지점 행 클릭 이벤트
    $(document).on('click', '.branch-row', function() {
        const bcoffCd = $(this).data('bcoff-cd');
        const bcoffName = $(this).data('bcoff-name');
        
        if (bcoffCd) {
            // 이전 선택된 행의 하이라이트 제거
            $('.branch-row').removeClass('table-active');
            // 현재 선택된 행 하이라이트
            $(this).addClass('table-active');
            
            // 지점 설정 정보 로드
            loadBranchSetupStatus(bcoffCd, bcoffName);
        }
    });

	
})

// 지점 관리 함수들
function refreshList() {
    location.reload();
}

function searchBranches() {
    const searchText = $('#searchText').val();
    
    let url = new URL(window.location);
    if (searchText) {
        url.searchParams.set('search', searchText);
    } else {
        url.searchParams.delete('search');
    }
    
    window.location.href = url.toString();
}


function viewBranchDetail(bcoffCd) {
    console.log('viewBranchDetail called with:', bcoffCd); // 디버깅용
    
    if (!bcoffCd) {
        alertToast('error', '지점 코드가 없습니다.');
        return;
    }
    
    // 지점 상세 페이지로 이동
    window.location.href = '/smgrmain/branch_detail_view?bcoff_cd=' + bcoffCd;
}

function setupBranch(bcoffCd) {
    window.location.href = '/smgrmain/payment_settings_overview?bcoff_cd=' + bcoffCd;
}

function managePayments(bcoffCd) {
    window.location.href = '/smgrmain/payment_status_inquiry?bcoff_cd=' + bcoffCd;
}

function viewRequestDetail(requestSno) {
    // 모달로 신청 상세 정보 표시
    $('#requestDetailModal').modal('show');
    loadRequestDetail(requestSno);
}

function loadRequestDetail(requestSno) {
    console.log('Loading request detail for SNO:', requestSno); // 디버깅용
    
    $.ajax({
        url: '/smgrmain/ajax_get_request_detail',
        type: 'GET',
        data: { request_sno: requestSno },
        dataType: 'json',
        success: function(response) {
            console.log('Response received:', response); // 디버깅용
            
            if (response.result === 'success' && response.data) {
                displayRequestDetail(response.data);
            } else {
                $('#requestDetailContent').html('<div class="alert alert-warning">신청 정보를 불러올 수 없습니다.</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error); // 디버깅용
            $('#requestDetailContent').html('<div class="alert alert-danger">서버 오류가 발생했습니다.</div>');
        }
    });
}

function displayRequestDetail(data) {
    console.log('Displaying request detail:', data); // 디버깅용
    
    let html = `
        <div class="row">
            <div class="col-md-6">
                <h6>지점 정보</h6>
                <table class="table table-sm">
                    <tr><td width="40%">지점명:</td><td>${data.BCOFF_NM || '-'}</td></tr>
                    <tr><td>지점 전화번호:</td><td>${formatPhoneNumber(data.BCOFF_TELNO)}</td></tr>
                    <tr><td>지점 주소:</td><td>${data.BCOFF_ADDR || '-'}</td></tr>
                    <tr><td>지점 메모:</td><td>${data.BCOFF_MEMO || '-'}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>관리자 정보</h6>
                <table class="table table-sm">
                    <tr><td width="40%">관리자 ID:</td><td>${data.BCOFF_MGMT_ID || '-'}</td></tr>
                    <tr><td>관리자명:</td><td>${data.MNGR_NM || '-'}</td></tr>
                    <tr><td>관리자 전화번호:</td><td>${formatPhoneNumber(data.MNGR_TELNO)}</td></tr>
                </table>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <h6>대표자 정보</h6>
                <table class="table table-sm">
                    <tr><td width="40%">대표자명:</td><td>${data.CEO_NM || '-'}</td></tr>
                    <tr><td>대표자 전화번호:</td><td>${formatPhoneNumber(data.CEO_TELNO)}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>신청 정보</h6>
                <table class="table table-sm">
                    <tr><td width="40%">신청일:</td><td>${formatDateTime(data.BCOFF_APPCT_DATETM)}</td></tr>
                    <tr><td>승인일:</td><td>${formatDateTime(data.BCOFF_APPV_DATETM)}</td></tr>
                    <tr>
                        <td>상태:</td>
                        <td>
                            <span class="badge badge-${getStatusColor(data.BCOFF_APPCT_STAT)}">
                                ${getStatusText(data.BCOFF_APPCT_STAT)}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    `;
    
    $('#requestDetailContent').html(html);
}

function getStatusText(status) {
    switch(status) {
        case '00': return '신청';
        case '01': return '승인';
        case '02': return '거절';
        case '99': return '취소';
        default: return '알수없음';
    }
}

function getStatusColor(status) {
    switch(status) {
        case '00': return 'warning';
        case '01': return 'success';
        case '02': return 'danger';
        case '99': return 'secondary';
        default: return 'secondary';
    }
}

function formatDateTime(datetime) {
    if (!datetime || datetime === null || datetime === 'null') {
        return '-';
    }
    
    try {
        // 날짜 문자열을 Date 객체로 변환
        const date = new Date(datetime);
        
        // 유효한 날짜인지 확인
        if (isNaN(date.getTime())) {
            return datetime; // 원본 반환
        }
        
        // YYYY-MM-DD HH:mm 형식으로 포맷
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        
        return `${year}-${month}-${day} ${hours}:${minutes}`;
    } catch (e) {
        return datetime; // 오류 시 원본 반환
    }
}

function formatPhoneNumber(phone) {
    if (!phone || phone === null || phone === 'null') {
        return '-';
    }
    
    // 숫자만 추출
    const numbers = phone.replace(/\D/g, '');
    
    // 전화번호 형식에 따라 포맷팅
    if (numbers.length === 11) {
        // 휴대폰 번호: 010-1234-5678
        return numbers.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
    } else if (numbers.length === 10) {
        // 지역번호: 02-1234-5678 또는 031-123-4567
        if (numbers.startsWith('02')) {
            return numbers.replace(/(\d{2})(\d{4})(\d{4})/, '$1-$2-$3');
        } else {
            return numbers.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        }
    } else if (numbers.length === 9) {
        // 지역번호: 031-123-4567
        return numbers.replace(/(\d{3})(\d{3})(\d{3})/, '$1-$2-$3');
    } else if (numbers.length === 8) {
        // 지역번호: 02-123-4567
        return numbers.replace(/(\d{2})(\d{3})(\d{3})/, '$1-$2-$3');
    }
    
    return phone; // 기본값으로 원본 반환
}

function continueSetup() {
    window.location.href = '/smgrmain/payment_settings_overview';
}

function continueSetupForBranch() {
    const selectedBcoffCd = $('.branch-row.table-active').data('bcoff-cd');
    if (selectedBcoffCd) {
        window.location.href = '/smgrmain/payment_settings_overview?bcoff_cd=' + selectedBcoffCd;
    }
}

function loadBranchSetupStatus(bcoffCd, bcoffName) {
    // 지점 설정 정보 로드
    $.ajax({
        url: '/smgrmain/ajax_get_branch_setup_status',
        type: 'GET',
        data: { bcoff_cd: bcoffCd },
        dataType: 'json',
        beforeSend: function() {
            $('#no-branch-selected').hide();
            $('#selected-branch-info').show();
            $('#selected-branch-name').text(bcoffName);
        },
        success: function(response) {
            if (response.result === 'success') {
                updateSetupProgress(response.data);
            } else {
                // 오류 시 기본값 표시
                updateSetupProgress({
                    basic_info_yn: 'Y',
                    pg_setup_yn: 'N',
                    van_setup_yn: 'N',
                    bank_setup_yn: 'N',
                    ready_yn: 'N',
                    setup_progress: 20
                });
            }
        },
        error: function() {
            // 오류 시 기본값 표시
            updateSetupProgress({
                basic_info_yn: 'Y',
                pg_setup_yn: 'N',
                van_setup_yn: 'N',
                bank_setup_yn: 'N',
                ready_yn: 'N',
                setup_progress: 20
            });
        }
    });
}

function updateSetupProgress(statusData) {
    // 진행률 업데이트
    const progressBar = $('#progress-bar');
    progressBar.css('width', statusData.setup_progress + '%');
    progressBar.text(statusData.setup_progress + '%');
    progressBar.attr('aria-valuenow', statusData.setup_progress);
    
    // 모든 단계 초기화
    $('#step-1, #step-2, #step-3, #step-4, #step-5').removeClass('completed current');
    
    // 단계별 상태 업데이트
    updateStepStatus('#step-1', statusData.basic_info_yn);
    updateStepStatus('#step-2', statusData.pg_setup_yn, statusData.basic_info_yn);
    updateStepStatus('#step-3', statusData.van_setup_yn, statusData.pg_setup_yn);
    updateStepStatus('#step-4', statusData.bank_setup_yn, statusData.van_setup_yn);
    updateStepStatus('#step-5', statusData.ready_yn, statusData.bank_setup_yn);
    
    // 버튼 상태 업데이트
    if (statusData.setup_progress >= 100) {
        $('#continue-setup-btn').hide();
        $('#setup-complete-badge').show();
    } else {
        $('#continue-setup-btn').show();
        $('#setup-complete-badge').hide();
    }
}

function updateStepStatus(selector, isCompleted, prevCompleted = 'Y') {
    const step = $(selector);
    if (isCompleted === 'Y') {
        step.addClass('completed').removeClass('current');
    } else if (prevCompleted === 'Y') {
        step.addClass('current').removeClass('completed');
    } else {
        step.removeClass('completed current');
    }
}


</script>