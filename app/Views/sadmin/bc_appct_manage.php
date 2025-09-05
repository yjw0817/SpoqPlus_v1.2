<style>
</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<h1 class="page-header"><?php echo $title ?></h1>
<!-- CARD HEADER [END] -->
<!-- CARD HEADER [END] -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">지점 신청 리스트</h4>
	</div>
					
	<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		<table class="table table-bordered table-hover table-striped col-md-12">
			<thead>
				<tr style='text-align:center'>
					<th style='width:3%'>번호</th>
					<th style='width:10%'>회사명</th>
					<th style='width:10%'>지점명(코드)</th>
					
					<th style='width:10%'>지점 전화번호</th>
					<th style='width:5%'>관리자 아이디</th>
					
					<th style='width:5%'>관리자명</th>
					<th style='width:10%'>관리자 전화번호</th>
					<th style='width:7%'>신청상태</th>
					<th style='width:10%'>신청일</th>
					<th style='width:10%'>승인일</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($bc_appct_list as $r): ?>
				<tr>
					<td class="text-center"><?php echo $search_val['listCount']?></td>
					<td><?php echo $r['COMP_NM']?> </td>
					<td><?php echo $r['BCOFF_NM']?>(<?php echo $r['BCOFF_CD']?>)</td>
					<td class="text-center"><?php echo disp_phone($r['BCOFF_TELNO'])?></td>
					<td><?php echo $r['BCOFF_MGMT_ID']?></td>
					<td><?php echo $r['MNGR_NM']?></td>
					<td class="text-center"><?php echo disp_phone($r['MNGR_TELNO'])?></td>
					<td class="text-center"><?php echo $sDef['BCOFF_APPCT_STAT'][$r['BCOFF_APPCT_STAT']]?></td>
					<td class="text-center"><?php echo $r['BCOFF_APPCT_DATETM']?></td>
					<td class="text-center"><?php echo $r['BCOFF_APPV_DATETM']?></td>
				</tr>
				<?php
				$search_val['listCount']--;
				endforeach; ?>
			</tbody>
		</table>
		<!-- TABLE [END] -->

	<!-- CARD FOOTER [START] -->
	<div class="card-footer clearfix">
		<!-- BUTTON [START] -->
		<ul class="pagination pagination-sm m-0 float-right">
			<li class="ac-btn">
				<button type="button" id='btnNew' class="btn btn-block btn-success btn-sm">지점신청</button>
			</li>
		</ul>
		
		<!-- BUTTON [END] -->
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
                <h5 class="modal-title">지점 신청하기</h4>
                <button type="button" class="close3"  data-bs-dismiss="modal" aria-label="Close">
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


</script>