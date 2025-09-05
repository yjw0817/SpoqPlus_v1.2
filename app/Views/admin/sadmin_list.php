
<?php
$sDef = SpoqDef();
?>
		
<!-- CARD HEADER [START] -->
<h1 class="page-header"><?php echo $title ?></h1>
<!-- CARD HEADER [END] -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">고객사 리스트</h4>
	</div>
	<!-- CARD BODY [START] -->
	<div class="panel-body">
		<!-- TABLE [START] -->
		<div class="table-responsive">
			<!-- <table id="data-table-rowreorder" width="100%" class="table table-striped table-bordered align-middle text-nowrap"> -->
			<table id="data-table-default" width="100%" class="table table-striped table-bordered align-middle text-nowrap dataTable dtr-inline collapsed" aria-describedby="data-table-default_info" style="width: 100%;"><colgroup><col data-dt-column="0" style="width: 39px;"><col data-dt-column="1" style="width: 39.5px;"><col data-dt-column="2" style="width: 178.469px;"><col data-dt-column="3" style="width: 215.031px;"></colgroup>
				<thead>
					<tr style='text-align:center'>
						<th style='width:70px'>번호</th>
						<th style='width:120px'>회사코드</th>
						<th style='width:150px'>회사명</th>
						<th style='width:150px'>회사 전화번호</th>
						<th style='width:120px'>아이디</th>
						<th style='width:150px'>담당자</th>
						<th style='width:150px'>담당자 전화번호</th>
						<th style='width:150px'>대표자</th>
						<th style='width:150px'>대표자 전화번호</th>
						<th style='width:150px'>등록일</th>
						<th>비고</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($one_cate_main_list as $r): ?>
					<tr>
						<td class="text-center"><?php echo $search_val['listCount']?></td>
						<td><?php echo $r['COMP_CD'] ?></td>
						<td><?php echo $r['COMP_NM'] ?></td>
						<td class="text-center"><?php echo disp_phone($r['COMP_TELNO']) ?></td>
						<td><?php echo $r['SMGMT_ID'] ?></td>
						<td><?php echo $r['MNGR_NM'] ?></td>
						<td class="text-center"><?php echo disp_phone($r['MNGR_TELNO']) ?></td>
						<td><?php echo $r['CEO_NM'] ?></td>
						<td class="text-center"><?php echo disp_phone($r['CEO_TELNO']) ?></td>
						
						<td><?php echo $r['CRE_DATETM'] ?></td>
						<td>
							<button type="button" class="btn btn-info btn-xs" onclick="sadmin_modify('<?php echo $r['COMP_CD']?>','<?php echo $r['SMGMT_ID']?>');">정보보기</button>
						</td>
					</tr>
					<?php
					$search_val['listCount']--;
					endforeach; ?>
				</tbody>
			</table>
			
		<button type="button" id="btnNew" class="btn btn-success btn-sm"  data-target="#modal_sadmin_form">등록하기</button>
		</div>
		<!-- TABLE [END] -->


		<div class="card-footer clearfix">
	<!-- BUTTON [START] -->

	
	<!-- BUTTON [END] -->
	<!-- PAGZING [START] -->
	<?=$pager?>
	<!-- PAGZING [END] -->
</div>
<!-- CARD FOOTER [END] -->
	</div>
</div>
			
<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_sadmin_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">슈퍼관리자 등록하기</h4>
                <button type="button" class="close3"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            	
            	
            	<!-- FORM [START] -->
            	<form id="sadmin_form" method="post" action="/adminmain/ajax_sadmin_insert_proc">
            	<input type="hidden" name="id_chk" id="id_chk" value="N" />
            	<input type="hidden" name="code_chk" id="code_chk" value="N" />
            	
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>슈퍼관리자 아이디<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="" name="smgmt_id" id="smgmt_id" autocomplete='off'>
                	<span class="input-group-append">
                    	<button type="button" class="basic_bt06" id="btn_smgmt_id_chk">중복체크</button>
                    </span>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>슈퍼관리자 비밀번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="password" class="form-control" placeholder="" name="smgmt_pwd" id="smgmt_pwd" autocomplete='off' />
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회사 코드<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="회사코드 5 ~ 14자리" name="comp_cd" id="comp_cd" autocomplete='off' maxlength="14">
                	<span class="input-group-append">
                    	<button type="button" class="basic_bt06" id="btn_code_chk2">중복체크</button>
                    </span>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회사명<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="" name="comp_nm" id="comp_nm" autocomplete='off'>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회사 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control phone-input" placeholder="" name="comp_telno" id="comp_telno" autocomplete='off' >
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회사 전화번호2</span>
                	</span>
                	<input type="text" class="form-control phone-input" placeholder="" name="comp_telno2" id="comp_telno2" autocomplete='off' >
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회사 주소</span>
                	</span>
                	<input type="text" class="form-control" placeholder="" name="comp_addr" id="comp_addr" autocomplete='off'>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회사 메모</span>
                	</span>
                	<input type="text" class="form-control" placeholder="" name="comp_memo" id="comp_memo" autocomplete='off'>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>담당자명<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="" name="mngr_nm" id="mngr_nm" autocomplete='off'>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text phone-input" style='width:150px'>담당자 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control phone-input" placeholder="" name="mngr_telno" id="mngr_telno" autocomplete='off' >
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>대표자명<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="" name="ceo_nm" id="ceo_nm" autocomplete='off'>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text phone-input" style='width:150px'>대표자 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control phone-input" placeholder="" name="ceo_telno" id="ceo_telno" autocomplete='off' >
                </div>
                
                <div id="id_chk_true" style="display:none;margin-bottom:4px; margin-top:20px">
                	<button type="button" class="btn btn-info  btn-block" >슈퍼관리자 아이디 중복체크가 완료 되었습니다.</button>
                </div>
                <div id="id_chk_false" style="margin-bottom:4px">
                	<button type="button" class="btn btn-danger  btn-block" >슈퍼관리자 아이디 중복체크를 해주세요</button>
                </div>
                
                <div id="code_chk_true" style="display:none">
                	<button type="button" class="btn btn-info btn-block" >회사코드 중복체크가 완료 되었습니다.</button>
                </div>
                <div id="code_chk_false">
                	<button type="button" class="btn btn-danger btn-block" >회사코드 중복체크를 해주세요</button>
                </div>
                
                
            	<!-- <p><small>----</small></p> -->
            	
            	</form>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="sadmin_create_btn">등록하기</button>
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-default END ] ======================================= -->

<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_sadmin_modify_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">슈퍼관리자 수정하기</h4>
                <button type="button" class="close3"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            	
            	
            	<!-- FORM [START] -->
            	<form id="sadmin_modify_form" method="post" action="/adminmain/ajax_sadmin_modify_proc" autocomplete="off">
            	
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>슈퍼관리자 아이디<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="" name="smgmt_id" id="modify_smgmt_id" readonly />
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>슈퍼관리자 비밀번호</span>
                	</span>
                	<input type="password" class="form-control" placeholder="비밀번호 수정시만 입력해 주세요." name="smgmt_pwd" id="modify_smgmt_pwd" autocomplete='off' />
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회사 코드<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="회사코드 5 ~ 14자리" name="comp_cd" id="modify_comp_cd" readonly />
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회사명<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="" name="comp_nm" id="modify_comp_nm" autocomplete='off'>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회사 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control phone-input" placeholder="" name="comp_telno" id="modify_comp_telno" autocomplete='off' >
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회사 전화번호2</span>
                	</span>
                	<input type="text" class="form-control phone-input" placeholder="" name="comp_telno2" id="modify_comp_telno2" autocomplete='off' >
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회사 주소</span>
                	</span>
                	<input type="text" class="form-control" placeholder="" name="comp_addr" id="modify_comp_addr" autocomplete='off'>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회사 메모</span>
                	</span>
                	<input type="text" class="form-control" placeholder="" name="comp_memo" id="modify_comp_memo" autocomplete='off'>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>담당자명<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="" name="mngr_nm" id="modify_mngr_nm" autocomplete='off'>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>담당자 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control phone-input" placeholder="" name="mngr_telno" id="modify_mngr_telno" autocomplete='off' >
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>대표자명<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="" name="ceo_nm" id="modify_ceo_nm" autocomplete='off'>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>대표자 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control phone-input" placeholder="" name="ceo_telno" id="modify_ceo_telno" autocomplete='off' >
                </div>
                
         
            	</form>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="sadmin_modify_btn">수정하기</button>
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>


<?=$jsinc ?>

<script>
$(function (){
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

	$("#btnNew").click(function(e)
	{
		$("#smgmt_id").val('');
		$("#smgmt_pwd").val('');
		$("#comp_cd").val('');
		$("#comp_nm").val('');
		$("#comp_telno").val('');
		$("#comp_telno2").val('');
		$("#comp_addr").val('');
		$("#comp_memo").val('');
		$("#mngr_telno").val('');
		$("#ceo_nm").val('');
		$("#ceo_telno").val('');
		$('#id_chk').val("N");
		$('#id_chk_false').show();
		$('#id_chk_true').hide();		
		$('#code_chk').val("N");
		$('#code_chk_false').show();
		$('#code_chk_true').hide();	

		$("#modal_sadmin_form").modal("show");
	});
	$('#data-table-rowreorder').DataTable({
		responsive: true,
		rowReorder: true
	});


	// 슈퍼관리자 아이디 키체크
	$('#smgmt_id').keyup(function(e){
		$('#id_chk').val("N");
		$('#id_chk_false').show();
		$('#id_chk_true').hide();
	});

	// 회사코드 키체크
	$('#comp_cd').keyup(function(e){
		$('#code_chk').val("N");
		$('#code_chk_false').show();
		$('#code_chk_true').hide();
	});

	$('#sadmin_modify_btn').click(function(){

		if ( $('#modify_comp_nm').val() == "" )
		{
			alertToast('error','회사명을 입력하세요.');
			return;
		}
		
		if ( $('#modify_comp_telno').val() == "" )
		{
			alertToast('error','회사전화번호를 입력하세요.');
			return;
		}
		
		if ( $('#modify_mngr_nm').val() == "" )
		{
			alertToast('error','담당자명을 입력하세요.');
			return;
		}
		
		if ( $('#modify_mngr_telno').val() == "" )
		{
			alertToast('error','담당자 전화번호를 입력하세요.');
			return;
		}
		
		if ( $('#modify_ceo_nm').val() == "" )
		{
			alertToast('error','대표자명을 입력하세요.');
			return;
		}
		
		if ( $('#modify_ceo_telno').val() == "" )
		{
			alertToast('error','대표자 전화번호를 입력하세요.');
			return;
		}
		
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
				$('#sadmin_modify_form').submit();
				// 성공일 경우
				//$("#modal_sadmin_form").modal('hide');
				//alertToast('success','대분류가 생성 되었습니다.');
			}
		});	
		
	});

	$('#sadmin_create_btn').click(function(){
		// 실패일 경우 warning error success info question
		//alertToast('error','대분류 코드를 입력하세요');
		
		if ( $('#id_chk').val() == "N" )
		{
			alertToast('error','아이디 중복체크를 해주세요');
			return;
		}
		
		if ( $('#code_chk').val() == "N" )
		{
			alertToast('error','코드 중복체크를 해주세요');
			return;
		}
		
		if ( $('#smgmt_id').val() == "" )
		{
			alertToast('error','아이디를 입력하세요.');
			return;
		}
		
		if ( $('#comp_cd').val() == "" )
		{
			alertToast('error','회사코드를 입력하세요.');
			return;
		}
		
		if ( $('#comp_cd').val().length < 5 )
		{
			alertToast('error','회사코드는 5자리 이상이어야 합니다.');
			return;
		}
		
		if ( $('#comp_nm').val() == "" )
		{
			alertToast('error','회사명을 입력하세요.');
			return;
		}
		
		if ( $('#comp_telno').val() == "" )
		{
			alertToast('error','회사전화번호를 입력하세요.');
			return;
		}
		
		if ( $('#mngr_nm').val() == "" )
		{
			alertToast('error','담당자명을 입력하세요.');
			return;
		}
		
		if ( $('#mngr_telno').val() == "" )
		{
			alertToast('error','담당자 전화번호를 입력하세요.');
			return;
		}
		
		if ( $('#ceo_nm').val() == "" )
		{
			alertToast('error','대표자명을 입력하세요.');
			return;
		}
		
		if ( $('#ceo_telno').val() == "" )
		{
			alertToast('error','대표자 전화번호를 입력하세요.');
			return;
		}
		
		ToastConfirm.fire({
			icon: "question",
			title: "  확인 메세지",
			html: "<font color='#000000' >등록 하시겠습니까?</font>",
			showConfirmButton: true,
			showCancelButton: true,
			confirmButtonColor: "#28a745",
		}).then((result) => {
			if (result.isConfirmed) 
			{
				$('#sadmin_form').submit();
				// 성공일 경우
				//$("#modal_sadmin_form").modal('hide');
				//alertToast('success','대분류가 생성 되었습니다.');
			}
		});	
		
	});

	$('#btn_smgmt_id_chk').click(function(){
		
		if ( $('#smgmt_id').val() == "" )
		{
			alertToast('error','아이디를 입력하세요.');
			return;
		}

		var params = "smgmt_id="+$('#smgmt_id').val();
		jQuery.ajax({
			url: '/adminmain/ajax_sadmin_smgmt_id_chk',
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
					alertToast('error','아이디가 중복되었습니다. 다른 코드를 입력하세요');
				}
			}
		});
		
	});

	$('#btn_code_chk2').click(function(){
		
		if ( $('#comp_cd').val() == "" )
		{
			alertToast('error','회사코드를 입력하세요.');
			return;
		}
		
		if ( $('#comp_cd').val().length < 5 )
		{
			alertToast('error','회사코드는 5자리 이상이어야 합니다.');
			return;
		}

		var params = "comp_cd="+$('#comp_cd').val();
		jQuery.ajax({
			url: '/adminmain/ajax_sadmin_comp_cd_chk',
			type: 'POST',
			data:params,
			contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
			dataType: 'text',
			success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					$('#code_chk').val("Y");
					$('#code_chk_false').hide();
					$('#code_chk_true').show();	
				} else 
				{
					alertToast('error','코드가 중복되었습니다. 다른 코드를 입력하세요');
				}
			}
		});
		
	});
});


function sadmin_modify(comp_cd,smgmt_id)
{
	var params = "comp_cd="+comp_cd+"&smgmt_id="+smgmt_id;
    jQuery.ajax({
        url: '/adminmain/ajax_sadmin_smgmt_modify',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
            json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				var obj = json_result['sinfo'];
				
				$('#modify_smgmt_id').val(obj['SMGMT_ID']);
				$('#modify_comp_cd').val(obj['COMP_CD']);
				$('#modify_comp_nm').val(obj['COMP_NM']);
				$('#modify_comp_telno').val(formatPhoneNumber(obj['COMP_TELNO']));
				$('#modify_comp_telno2').val(formatPhoneNumber(obj['COMP_TELNO2']));
				$('#modify_comp_addr').val(obj['COMP_ADDR']);
				$('#modify_comp_memo').val(obj['COMP_MEMO']);
				$('#modify_mngr_nm').val(obj['MNGR_NM']);
				$('#modify_mngr_telno').val(formatPhoneNumber(obj['MNGR_TELNO']));
				$('#modify_ceo_nm').val(obj['CEO_NM']);
				$('#modify_ceo_telno').val(formatPhoneNumber(obj['CEO_TELNO']));
				
				$('#modal_sadmin_modify_form').modal("show");
			} else 
			{
				alertToast('error','정보를 불러올 수 없습니다.');
			}
        }
    });
}



</script>