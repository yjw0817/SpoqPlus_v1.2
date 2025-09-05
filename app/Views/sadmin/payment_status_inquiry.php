<style>
.inquiry-dashboard {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin: 20px 0;
}
.search-filters {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.status-badge {
    font-size: 0.8em;
    padding: 3px 8px;
    border-radius: 12px;
    font-weight: 500;
}
.status-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.status-pending { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
.status-failed { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
.status-cancelled { background: #e2e3e5; color: #383d41; border: 1px solid #d6d8db; }
.payment-row {
    cursor: pointer;
    transition: background-color 0.2s ease;
}
.payment-row:hover {
    background-color: #f8f9fa;
}
.amount-cell {
    font-weight: 600;
    color: #007bff;
}
.date-cell {
    font-size: 0.9em;
    color: #6c757d;
}
.quick-stats {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}
.stat-item {
    text-align: center;
    padding: 10px;
}
.stat-number {
    font-size: 20px;
    font-weight: bold;
    color: #007bff;
}
.stat-label {
    font-size: 11px;
    color: #6c757d;
    margin-top: 5px;
}
.export-section {
    text-align: right;
    margin-bottom: 15px;
}
</style>

<?php
// Initialize default values to prevent errors
$sDef = SpoqDef();
$view = $view ?? [];
$payment_list = $view['payment_list'] ?? $payment_list ?? [];
$search_val = $view['search_val'] ?? $search_val ?? ['listCount' => 0];
$pager = $view['pager'] ?? $pager ?? '';
$quick_stats = $view['quick_stats'] ?? $quick_stats ?? [
    'total_count' => 0,
    'success_count' => 0,
    'pending_count' => 0,
    'failed_count' => 0,
    'total_amount' => 0,
    'success_amount' => 0
];
$selected_branch = $view['selected_branch'] ?? $selected_branch ?? '';
$branch_list = $view['branch_list'] ?? $branch_list ?? [];
?>

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

<!-- 결제 조회 대시보드 -->
<div class="inquiry-dashboard">
    <div class="row">
        <div class="col-md-8">
            <h4><i class="fas fa-search"></i> 결제 현황 조회</h4>
            <p class="text-muted">결제 내역을 조회하고 상세 정보를 확인할 수 있습니다.</p>
        </div>
        <div class="col-md-4 text-right">
            <div class="export-section">
                <div class="btn-group">
                    <button type="button" class="btn btn-success btn-sm" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                    <button type="button" class="btn btn-info btn-sm" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="printResults()">
                        <i class="fas fa-print"></i> 인쇄
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 빠른 통계 -->
<div class="quick-stats">
    <div class="row">
        <div class="col-md-2">
            <div class="stat-item">
                <div class="stat-number"><?= number_format($quick_stats['total_count']) ?></div>
                <div class="stat-label">총 건수</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-item">
                <div class="stat-number text-success"><?= number_format($quick_stats['success_count']) ?></div>
                <div class="stat-label">성공</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-item">
                <div class="stat-number text-warning"><?= number_format($quick_stats['pending_count']) ?></div>
                <div class="stat-label">대기</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-item">
                <div class="stat-number text-danger"><?= number_format($quick_stats['failed_count']) ?></div>
                <div class="stat-label">실패</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-item">
                <div class="stat-number"><?= number_format($quick_stats['total_amount']) ?></div>
                <div class="stat-label">총 금액 (원)</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-item">
                <div class="stat-number text-success"><?= number_format($quick_stats['success_amount']) ?></div>
                <div class="stat-label">성공 금액 (원)</div>
            </div>
        </div>
    </div>
</div>

<!-- 검색 필터 -->
<div class="search-filters">
    <form id="searchForm" method="GET">
        <div class="row">
            <div class="col-md-3">
                <label>조회 기간</label>
                <div class="input-group">
                    <input type="date" class="form-control" name="start_date" id="startDate" 
                           value="<?= $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days')) ?>">
                    <div class="input-group-text">~</div>
                    <input type="date" class="form-control" name="end_date" id="endDate" 
                           value="<?= $_GET['end_date'] ?? date('Y-m-d') ?>">
                </div>
            </div>
            <div class="col-md-2">
                <label>결제상태</label>
                <select class="form-control" name="payment_status" id="paymentStatus">
                    <option value="">전체</option>
                    <option value="SUCCESS" <?= ($_GET['payment_status'] ?? '') == 'SUCCESS' ? 'selected' : '' ?>>성공</option>
                    <option value="PENDING" <?= ($_GET['payment_status'] ?? '') == 'PENDING' ? 'selected' : '' ?>>대기</option>
                    <option value="FAILED" <?= ($_GET['payment_status'] ?? '') == 'FAILED' ? 'selected' : '' ?>>실패</option>
                    <option value="CANCELLED" <?= ($_GET['payment_status'] ?? '') == 'CANCELLED' ? 'selected' : '' ?>>취소</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>결제수단</label>
                <select class="form-control" name="payment_method" id="paymentMethod">
                    <option value="">전체</option>
                    <option value="CARD" <?= ($_GET['payment_method'] ?? '') == 'CARD' ? 'selected' : '' ?>>카드</option>
                    <option value="BANK" <?= ($_GET['payment_method'] ?? '') == 'BANK' ? 'selected' : '' ?>>계좌이체</option>
                    <option value="VBANK" <?= ($_GET['payment_method'] ?? '') == 'VBANK' ? 'selected' : '' ?>>가상계좌</option>
                    <option value="PHONE" <?= ($_GET['payment_method'] ?? '') == 'PHONE' ? 'selected' : '' ?>>휴대폰</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>PG사</label>
                <select class="form-control" name="pg_provider" id="pgProvider">
                    <option value="">전체</option>
                    <option value="INICIS" <?= ($_GET['pg_provider'] ?? '') == 'INICIS' ? 'selected' : '' ?>>이니시스</option>
                    <option value="KCP" <?= ($_GET['pg_provider'] ?? '') == 'KCP' ? 'selected' : '' ?>>KCP</option>
                    <option value="TOSS" <?= ($_GET['pg_provider'] ?? '') == 'TOSS' ? 'selected' : '' ?>>토스페이먼츠</option>
                    <option value="NICE" <?= ($_GET['pg_provider'] ?? '') == 'NICE' ? 'selected' : '' ?>>나이스페이</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>검색어</label>
                <input type="text" class="form-control" name="search_keyword" id="searchKeyword" 
                       placeholder="주문번호, 회원명" value="<?= $_GET['search_keyword'] ?? '' ?>">
            </div>
            <div class="col-md-1">
                <label>&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search"></i> 검색
                    </button>
                </div>
            </div>
        </div>
        
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary" onclick="setDateRange('today')">오늘</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setDateRange('week')">7일</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setDateRange('month')">30일</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setDateRange('3month')">3개월</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">초기화</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- 결제 내역 리스트 -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">결제 내역</h4>
        <div class="panel-tools">
            <button type="button" class="btn btn-sm btn-info" onclick="refreshList()">
                <i class="fas fa-sync"></i> 새로고침
            </button>
        </div>
    </div>
    <div class="panel-body table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr style='text-align:center'>
                    <th style='width:3%'>번호</th>
                    <th style='width:10%'>결제일시</th>
                    <th style='width:12%'>주문번호</th>
                    <th style='width:8%'>회원명</th>
                    <th style='width:10%'>상품명</th>
                    <th style='width:8%'>결제금액</th>
                    <th style='width:8%'>결제수단</th>
                    <th style='width:8%'>PG사</th>
                    <th style='width:8%'>상태</th>
                    <th style='width:12%'>승인번호</th>
                    <th style='width:13%'>관리</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($payment_list) > 0): ?>
                    <?php foreach($payment_list as $payment): ?>
                    <tr class="payment-row" onclick="viewPaymentDetail('<?= $payment['ORDER_NO'] ?? '' ?>')">
                        <td class="text-center"><?= $search_val['listCount'] ?? 0 ?></td>
                        <td class="date-cell"><?= date('m-d H:i', strtotime($payment['PAYMT_DATE'] ?? '')) ?></td>
                        <td class="text-center">
                            <span class="badge badge-light"><?= $payment['ORDER_NO'] ?? '-' ?></span>
                        </td>
                        <td><?= $payment['MEM_NM'] ?? '-' ?></td>
                        <td><?= $payment['PRODUCT_NM'] ?? '-' ?></td>
                        <td class="text-right amount-cell"><?= number_format($payment['PAYMT_AMT'] ?? 0) ?>원</td>
                        <td class="text-center"><?= $payment['PAYMT_METHOD'] ?? '-' ?></td>
                        <td class="text-center"><?= $payment['PG_PROVIDER'] ?? '-' ?></td>
                        <td class="text-center">
                            <?php 
                            $status = $payment['PAYMT_STATUS'] ?? 'UNKNOWN';
                            $statusClass = '';
                            $statusText = '';
                            switch($status) {
                                case 'SUCCESS': $statusClass = 'status-success'; $statusText = '성공'; break;
                                case 'PENDING': $statusClass = 'status-pending'; $statusText = '대기'; break;
                                case 'FAILED': $statusClass = 'status-failed'; $statusText = '실패'; break;
                                case 'CANCELLED': $statusClass = 'status-cancelled'; $statusText = '취소'; break;
                                default: $statusClass = 'status-pending'; $statusText = '알수없음'; break;
                            }
                            ?>
                            <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                        </td>
                        <td class="text-center"><?= $payment['APPROVAL_NO'] ?? '-' ?></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-info btn-xs" onclick="event.stopPropagation(); viewPaymentDetail('<?= $payment['ORDER_NO'] ?? '' ?>')">
                                    <i class="fas fa-eye"></i> 상세
                                </button>
                                <?php if(($payment['PAYMT_STATUS'] ?? '') == 'SUCCESS'): ?>
                                <button type="button" class="btn btn-warning btn-xs" onclick="event.stopPropagation(); refundPayment('<?= $payment['ORDER_NO'] ?? '' ?>')">
                                    <i class="fas fa-undo"></i> 환불
                                </button>
                                <?php endif; ?>
                                <button type="button" class="btn btn-secondary btn-xs" onclick="event.stopPropagation(); printReceipt('<?= $payment['ORDER_NO'] ?? '' ?>')">
                                    <i class="fas fa-receipt"></i> 영수증
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php
                    if(isset($search_val['listCount'])) $search_val['listCount']--;
                    endforeach; 
                    ?>
                <?php else: ?>
                <tr>
                    <td colspan="11" class="text-center text-muted py-4">
                        <i class="fas fa-credit-card fa-2x mb-2"></i><br>
                        조회된 결제 내역이 없습니다.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- CARD FOOTER [START] -->
    <div class="card-footer clearfix" style="margin-bottom: 30px;">
        <!-- PAGZING [START] -->
        <?=$pager?>
        <!-- PAGZING [END] -->
    </div>
    <!-- CARD FOOTER [END] -->
</div>

<!-- 결제 상세 모달 -->
<div class="modal fade" id="paymentDetailModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">결제 상세 정보</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="paymentDetailContent">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">결제 정보를 불러오는 중...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary" onclick="printDetail()">
                    <i class="fas fa-print"></i> 인쇄
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 환불 모달 -->
<div class="modal fade" id="refundModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">결제 환불</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="refundForm">
                    <input type="hidden" id="refundOrderNo" name="order_no">
                    
                    <div class="form-group">
                        <label>환불 유형</label>
                        <select class="form-control" id="refundType" name="refund_type" required>
                            <option value="">선택하세요</option>
                            <option value="FULL">전액 환불</option>
                            <option value="PARTIAL">부분 환불</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="refundAmountGroup" style="display:none;">
                        <label>환불 금액</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="refundAmount" name="refund_amount" placeholder="환불할 금액">
                            <div class="input-group-append">
                                <span class="input-group-text">원</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>환불 사유 <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="refundReason" name="refund_reason" rows="3" placeholder="환불 사유를 입력하세요" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                <button type="button" class="btn btn-warning" onclick="processRefund()">
                    <i class="fas fa-undo"></i> 환불 처리
                </button>
            </div>
        </div>
    </div>
</div>	
	
</section>


<?=$jsinc ?>

<script>


$(function () {
    $('.select2').select2();

	$("#btnNew").click(function(e){
		$("#bcoff_mgmt_id").val('');
		$("#bcoff_mgmt_pwd").val('');
		$("#mngr_nm").val('');
		$(".phone-input").removeClass("is-valid");
		$(".phone-input").removeClass("is-invalid");
		$(".phone-input").val('');
		$("#ceo_nm").val('');
		$("#bcoff_nm").val('');
		$("#bcoff_addr").val('');
		$("#bcoff_memo").val('');
		$('#id_chk').val("N");
		$('#id_chk_false').show();
		$('#id_chk_true').hide();		
		$("#modal_bc_appct_form").modal("show");
	});
	$('#bcoff_mgmt_id').keyup(function(e){
		$('#id_chk').val("N");
		$('#id_chk_false').show();
		$('#id_chk_true').hide();
	});


	$(".phone-input").on("input", function(e) {
		let errorDiv = $(this).closest(".input-group").next(".error-message") || $("#telno-error");
		handlePhoneInput(this, e, errorDiv);
	}).on("focus", function() {
		if (!$(this).val()) {
			$(this).val(""); // 빈 값으로 시작
		}
    });

	$(".phone-input").on("keypress", function(e) {
		const key = String.fromCharCode(e.which);
		if (!/[0-9]/.test(key)) {
			e.preventDefault();
		}
	});


	$('#bc_appct_form_create_btn').click(function(){
		// 실패일 경우 warning error success info question
		//alertToast('error','대분류 코드를 입력하세요');
		
		if ( $('#id_chk').val() == "N" )
		{
			$('#id_chk').focus();
			alertToast('error','지점관리자 아이디 중복체크를 해주세요');
			return;
		}
		
		if( $('#bcoff_mgmt_id').val() == "" )
		{
			$('#bcoff_mgmt_id').focus()
			alertToast('error','지점관리자 아이디를 입력하세요');
			return;
		}
		
		if( $('#bcoff_mgmt_pwd').val() == "" )
		{
			$('#bcoff_mgmt_pwd').focus();
			alertToast('error','지점관리자 비밀번호를 입력하세요');
			return;
		}

		if($('#mngr_nm').val() == "" )
		{
			$('#mngr_nm').focus();
			alertToast('error','지점관리자명을 입력하세요');
			return;
		}
		
		if( $('#mngr_telno').val() == "" )
		{
			$('#mngr_telno').focus();
			alertToast('error','지점관리자 전화번호를 입력하세요');
			return;
		} else if(!checkPhoneNumber($('#mngr_telno').val()))
		{
			$('#mngr_telno').focus();
			alertToast('error','올바른 지점관리자 전화번호를 입력하세요');
			return;
		}

		
		
		if( $('#ceo_nm').val() == "" )
		{
			$('#ceo_nm').focus();
			alertToast('error','대표자명 입력하세요');
			return;
		}
		
		if( $('#ceo_telno').val() == "" )
		{
			$('#ceo_telno').focus();
			alertToast('error','대표자 전화번호를 입력하세요');
			return;
		} else if(!checkPhoneNumber($('#ceo_telno').val())){
			$('#ceo_telno').focus();
			alertToast('error','올바른 대표자 전화번호를 입력하세요');
			return;
		}
		
		if( $('#bcoff_nm').val() == "" )
		{
			$('#bcoff_nm').focus();
			alertToast('error','지점명을 입력하세요');
			return;
		}
		
		if( $('#bcoff_telno').val() == "" )
		{
			$('#bcoff_telno').focus();
			alertToast('error','지점 전화번호를 입력하세요');
			return;
		} else if(!checkPhoneNumber($('#bcoff_telno').val())){
			$('#bcoff_telno').focus();
			alertToast('error','올바른 지점 전화번호를 입력하세요');
			return;
		}
		
		ToastConfirm.fire({
			icon: "question",
			title: "  확인 메세지",
			html: "<font color='#000000' >생성하시겠습니까?</font>",
			showConfirmButton: true,
			showCancelButton: true,
			confirmButtonColor: "#28a745",
		}).then((result) => {
			if (result.isConfirmed) 
			{
				$('#bc_appct_form').submit();
				// 성공일 경우	
				//$("#modal_two_cate_form").modal('hide');
				//alertToast('success','중분류가 생성 되었습니다.');
			}
		});	
		
	});

	$('#btn_code_chk').click(function(){

		var params = "bcoff_mgmt_id="+$('#bcoff_mgmt_id').val();
		jQuery.ajax({
			url: '/smgrmain/ajax_bc_appct_id_chk',
			type: 'POST',
			data:params,
			contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
			dataType: 'text',
			success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					$('#id_chk').val("Y");
					$('#id_chk_false').hide();
					$('#id_chk_true').show();		
				} else 
				{
					alertToast('error','지점관리자 아이디가 중복되었습니다. 다른 아이디를 입력하세요');
				}
			}
		});
	});


})

// 지점 변경 함수
function changeBranch() {
    const selectedBranch = $('#branch_select').val();
    if (selectedBranch) {
        window.location.href = '/smgrmain/payment_status_inquiry?bcoff_cd=' + selectedBranch;
    } else {
        window.location.href = '/smgrmain/payment_status_inquiry';
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