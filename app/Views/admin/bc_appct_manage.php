<style>
	
</style>
<?php
$sDef = SpoqDef();
?>

<!-- CARD HEADER [START] -->
<h1 class="page-header"><?php echo $title ?></h1>
<!-- CARD HEADER [END] -->
<!-- CARD HEADER [END] -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">지점 신청 리스트</h4>
	</div>
	<!-- CARD BODY [START] -->
	<div class="panel-body">
		<!-- TABLE [START] -->
		<div class="d-md-flex justify-content-between align-items-center col-12 dt-layout-full col-md table-responsive">
			<table id="data-table-rowreorder" width="100%" class="table table-striped table-bordered align-middle text-nowrap">
				<thead>
					<tr style='text-align:center'>
						<th style='width:4%'>번호</th>
						<th style='width:10%'>회사명(코드)</th>
						<th style='width:10%'>지점명</th>
						
						<th style='width:10%'>지점 전화번호</th>
						<th style='width:10%'>관리자 아이디</th>
						
						<th style='width:10%'>관리자명</th>
						<th style='width:10%'>관리자 전화번호</th>
						<th style='width:10%'>신청상태</th>
						<th style='width:10%'>신청일</th>
						<th style='width:10%'>승인일</th>
						<th style='width:10%'>옵션</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($bc_appct_list as $r): ?>
					<tr>
						<td class="text-center"><?php echo $search_val['listCount']?></td>
						<td><?php echo $r['COMP_NM']?> (<?php echo $r['COMP_CD']?>)</td>
						<td><?php echo $r['BCOFF_NM']?></td>
						<td class="text-center"><?php echo disp_phone($r['BCOFF_TELNO'])?></td>
						<td  class="text-center"><?php echo $r['BCOFF_MGMT_ID']?></td>
						<td  class="text-center"><?php echo $r['MNGR_NM']?></td>
						<td class="text-center"><?php echo disp_phone($r['MNGR_TELNO'])?></td>
						<td class="text-center"><?php echo $sDef['BCOFF_APPCT_STAT'][$r['BCOFF_APPCT_STAT']]?></td>
						<td class="text-center"><?php echo $r['BCOFF_APPCT_DATETM']?></td>
						<td class="text-center"><?php echo $r['BCOFF_APPV_DATETM']?></td>
						<td  class="text-center">
						<?php if($r['BCOFF_APPCT_STAT'] == "00"): ?>
							<button type="button" class="btn btn-success btn-xs ac-btn" onclick="btn_appv('<?php echo $r['BCOFF_APPCT_MGMT_SNO']?>');">승인</button>
							<button type="button" class="btn btn-danger btn-xs ac-btn" onclick="btn_refus('<?php echo $r['BCOFF_APPCT_MGMT_SNO']?>');">반려</button>
						<?php endif; ?>
						<button type="button" class="btn btn-success btn-xs ac-btn" onclick="btn_modify('<?php echo $r['BCOFF_APPCT_MGMT_SNO']?>');">정보보기</button>
						</td>
					</tr>
					<?php
					$search_val['listCount']--;
					endforeach; ?>
				</tbody>
			</table>
	</div>
	<!-- TABLE [END] -->
	<!-- CARD BODY [END] -->
	<!-- CARD FOOTER [START] -->
	<div class="card-footer clearfix">
		<!-- BUTTON [START] -->
		<!-- 
		<ul class="pagination pagination-sm m-0 float-right">
			<li class="ac-btn">
				<a href="#modal_bc_appct_form" class="btn btn-success btn-sm" data-bs-toggle="modal">지점신청</a>
			</li>
		</ul>
			-->
		
		<!-- BUTTON [END] -->
		<!-- PAGZING [START] -->
		<?=$pager?>
		<!-- PAGZING [END] -->
	</div>
	<!-- CARD FOOTER [END] -->
</div>
<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_bc_appct_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">지점 신청하기</h4>
                <button type="button" class="close3"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            	
            	<!-- FORM [START] -->
            	<form id="bc_appct_form" method="post" action="/smgrmain/ajax_bc_appct_proc">
            	
            	
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점관리자 아이디<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_mgmt_id">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점관리자 비밀번호<span class="text-danger" autocomplete="new-password">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_mgmt_pwd">
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점관리자명<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="mngr_nm">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text phone-input" style='width:150px'>지점관리자 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="mngr_telno">
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>대표자명<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="ceo_nm">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text phone-input" style='width:150px'>대표자 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="ceo_telno">
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점명<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_nm">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점 주소</span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_addr">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text phone-input" style='width:150px'>지점 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_telno">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text phone-input" style='width:150px'>지점 전화번호2</span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_telno2">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점 메모</span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_memo">
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


<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_modify_bc_appct_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">지점 수정하기</h4>
                <button type="button" class="close3"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            	<!-- 설명 부분 [START] -->
                <div class="callout callout-info"  style='display:none'>
                	<p>설명</p>
                	<p><small>해당 부분 설명</small></p>
                </div>
                <!-- 설명 부분 [END] -->
            	
            	<!-- FORM [START] -->
            	<form id="modify_bc_appct_form" method="post" action="/adminmain/ajax_modify_bc_appct_proc">
            	<input type="hidden" name="bcoff_appct_mgmt_sno" id="modify_bcoff_appct_mgmt_sno" />
            	
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점관리자 아이디<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_mgmt_id" id="modify_bcoff_mgmt_id" readonly />
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점관리자 비밀번호</span>
                	</span>
                	<input type="text" class="form-control" placeholder="비밀번호 수정시에만 입력하세요" name="bcoff_mgmt_pwd" id="modify_bcoff_mgmt_pwd" autocomplete="new-password" />
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점관리자명<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="mngr_nm" id="modify_mngr_nm" />
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점관리자 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control phone-input" name="mngr_telno" id="modify_mngr_telno" />
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>대표자명<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="ceo_nm" id="modify_ceo_nm" />
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>대표자 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control phone-input" name="ceo_telno" id="modify_ceo_telno" />
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점명<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_nm" id="modify_bcoff_nm" />
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점 주소</span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_addr" id="modify_bcoff_addr" />
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control phone-input" name="bcoff_telno" id="modify_bcoff_telno" />
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점 전화번호2</span>
                	</span>
                	<input type="text" class="form-control phone-input" name="bcoff_telno2" id="modify_bcoff_telno2" />
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>지점 메모</span>
                	</span>
                	<input type="text" class="form-control" name="bcoff_memo" id="modify_bcoff_memo" />
                </div>
                
            	
            	
            	</form>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="bc_appct_form_modify_btn">수정하기</button>
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-default END ] ======================================= -->	

<!-- <button type="button" id="btnTest" class="btn btn-success btn-sm" >테스트</button> -->
<button type="button" id="btnTest" class="btn btn-success btn-sm" style=" left: -9999px; visibility: hidden;">테스트</button>
</section>

<form id="bcoff_appct_form" method="post">
	<input type="hidden" name="bcoff_appct_mgmt_sno" id="bcoff_appct_mgmt_sno" />
</form>

<?=$jsinc ?>

<script>

$(function () {
    $('.select2').select2();
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

})


function btn_modify(sno)
{
	var params = "bcoff_appct_mgmt_sno="+sno;
    jQuery.ajax({
        url: '/adminmain/ajax_bcoff_appct_mgmt_modify',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
            json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				var obj = json_result['binfo'];
				
				$(".phone-input").removeClass("is-valid");
				$(".phone-input").removeClass("is-invalid");
				$('#modify_bcoff_appct_mgmt_sno').val(obj['BCOFF_APPCT_MGMT_SNO']);
				$('#modify_bcoff_mgmt_id').val(obj['BCOFF_MGMT_ID']);
				$('#modify_mngr_nm').val(obj['MNGR_NM']);
				$('#modify_mngr_telno').val(formatPhoneNumber(obj['MNGR_TELNO']));
				$('#modify_ceo_nm').val(obj['CEO_NM']);
				$('#modify_ceo_telno').val(formatPhoneNumber(obj['CEO_TELNO']));
				$('#modify_bcoff_nm').val(obj['BCOFF_NM']);
				$('#modify_bcoff_addr').val(obj['BCOFF_ADDR']);
				$('#modify_bcoff_telno').val(formatPhoneNumber(obj['BCOFF_TELNO']));
				$('#modify_bcoff_telno2').val(formatPhoneNumber(obj['BCOFF_TELNO2']));
				$('#modify_bcoff_memo').val(obj['BCOFF_MEMO']);
				
				$('#modal_modify_bc_appct_form').modal("show");
			} else 
			{
				alertToast('error','정보를 불러올 수 없습니다.');
			}
        }
    });
}
			
function btn_appv(sno)
{
	$("#bcoff_appct_mgmt_sno").val(sno);
	
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >승인하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		$('#bcoff_appct_form').attr("action", "/adminmain/bc_appct_appv_proc");
    		$('#bcoff_appct_form').submit();
    		// 성공일 경우	
    		//$("#modal_two_cate_form").modal('hide');
			//alertToast('success','중분류가 생성 되었습니다.');
    	}
    });	
}

function btn_refus(sno)
{
	$("#bcoff_appct_mgmt_sno").val(sno);
}

$('#bc_appct_form_modify_btn').click(function(){
	
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >수정 하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		$('#modify_bc_appct_form').submit();
    	}
    });	
	
});


$('#bc_appct_form_create_btn').click(function(){
	// 실패일 경우 warning error success info question
	//alertToast('error','대분류 코드를 입력하세요');
	
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >승인하시겠습니까?</font>",
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
	alertToast('error','코드가 중복되었습니다. 다른 코드를 입력하세요');
});

</script>