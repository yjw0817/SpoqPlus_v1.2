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
		<h4 class="panel-title">중분류(대분류) 분류 리스트</h4>
		<div class="panel-heading-btn">
		</div>
	</div>
					<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		<table class="table table-striped table-bordered align-middle text-nowrap">
			<thead>
				<tr style='text-align:center'>
					<th style='width:70px'>번호</th>
					<th style='width:130px'>대분류 코드</th>
					<th style='width:130px'>대분류명</th>
					<th style='width:130px'>중분류 구성</th>
					<th style='width:100px'>락커 설정</th>
					<th style='width:150px'>등록일시</th>
					<th>비고</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($one_cate_main_list as $r): ?>
				<tr class='text-center'>
					<td><?php echo $search_val['listCount']?></td>
					<td><?php echo $r['1RD_CATE_CD'] ?></td>
					<td><?php echo $r['CATE_NM'] ?></td>
					<td><?php echo $sDef['GRP_CATE_SET'][$r['GRP_CATE_SET']] ?></td>
					<td><?php echo $sDef['LOCKR_SET'][$r['LOCKR_SET']] ?></td>
					<td><?php echo $r['CRE_DATETM'] ?></td>
					<td></td>
				</tr>
				<?php
				$search_val['listCount']--;
				endforeach; 
				?>
			</tbody>
		</table>
		<!-- TABLE [END] -->
		<div class="card-footer clearfix">
		<!-- BUTTON [START] -->
		<ul class="pagination pagination-sm m-0 float-right">
			<li class="ac-btn">
				<button type="button" id="btnNew" class="btn btn-block btn-success btn-sm" >등록하기</button>
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

	<!-- CARD FOOTER [START] -->

	
<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_one_cate_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">대분류 등록하기</h4>
                <button type="button" class="close3"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            	
            	
            	<!-- FORM [START] -->
            	<form id="one_cate_form" method="post" action="/adminmain/ajax_onc_cate_proc">
            	<input type="hidden" name="code_chk" id="code_chk" value="N" />
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>대분류 코드</span>
                	</span>
                	<input type="text" class="form-control" placeholder="대분류 코드 4자리 (숫자만 입력)" name="1rd_cate_cd" id="1rd_cate_cd" maxlength='4' autocomplete='off'>
                	<span class="input-group-append">
                    	<button type="button" class="basic_bt06" id="btn_code_chk">중복체크</button>
                    </span>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>대분류 명</span>
                	</span>
                	<input type="text" class="form-control" placeholder="표시할 중분류분류을 입력하세요" name="cate_nm" id="cate_nm" autocomplete='off'>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>중분류 구성</span>
                	</span>
                	
                	<div style='margin-top:4px;margin-left:5px;'>
                    	<div class="icheck-primary d-inline">
                            <input type="radio" id="radioGrpCate1" name="grp_cate_set" value="1RD" checked>
                            <label for="radioGrpCate1">
                            	<small>대분류</small>
                            </label>
                        </div>
                        <div class="icheck-primary d-inline ml20">
                            <input type="radio" id="radioGrpCate2" name="grp_cate_set" value="2RD">
                            <label for="radioGrpCate2">
                            	<small>중분류</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>락커 설정</span>
                	</span>
                	
                	<div style='margin-top:4px;margin-left:5px;'>
                        <div class="icheck-primary d-inline">
                        	<input type="checkbox" id="checkLockerSet" name="lockr_set">
                        	<label for="checkLockerSet">
                        		<small>락커으로 설정</small>
                        	</label>
                        </div>
                	</div>
                </div>
                
            	<p class="mt20">※ 락커 설정은 락커 중분류분류일경우에만 체크하세요.</p>
            	
            	<div id="code_chk_true" style="display:none">
                	<button type="button" class="btn btn-info btn-block" >중복체크가 완료 되었습니다.</button>
                </div>
                <div id="code_chk_false">
                	<button type="button" class="btn btn-danger  btn-block" >중복체크를 해주세요</button>
                </div>
                
            	</form>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="one_cate_form_create_btn">등록하기</button>
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-default END ] ======================================= -->	
	
</section>


<?=$jsinc ?>

<script>
	$("#btnNew").click(function(e)
	{
		$("#modal_one_cate_form").modal("show");
	});
	// 대분류 코드 4자리 숫자 입력 체크
	$('#1rd_cate_cd').keyup(function(e){
		var d_amt = onlyNum( $('#1rd_cate_cd').val() );
		$('#1rd_cate_cd').val(d_amt);
		code_change(e);
	});

	function code_change(t)
	{
		$('#code_chk').val("N");
		$('#code_chk_false').show();
		$('#code_chk_true').hide();
	}

	$('#btn_code_chk').click(function(){
		if ( $('#1rd_cate_cd').val() == "" )
		{
			alertToast('error','대분류 코드를 입력하세요');
			return;
		}
		
		if ( $('#1rd_cate_cd').val().length < 4 )
		{
			alertToast('error',"중분류분류코드는 4자리가 되어야 합니다.");
			$('#1rd_cate_cd').focus();
			return;
		}

		var params = "cate_cd="+$('#1rd_cate_cd').val();
		jQuery.ajax({
			url: '/adminmain/ajax_code_chk',
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


	$('#one_cate_form_create_btn').click(function(){
		// 실패일 경우 warning error success info question
		//alertToast('error','대분류 코드를 입력하세요');
		
		if ( $('#code_chk').val() == "N" )
		{
			alertToast('error','코드 중복체크를 해주세요');
			return;
		}
		
		if ( $('#1rd_cate_cd').val() == "" )
		{
			alertToast('error','대분류 코드를 입력하세요');
			$('#1rd_cate_cd').focus();
			return;
		}
		
		if ( $('#1rd_cate_cd').val().length < 4 )
		{
			alertToast('error',"중분류분류코드는 4자리가 되어야 합니다.");
			$('#1rd_cate_cd').focus();
			return;
		}
		
		if ( $('#cate_nm').val() == "" )
		{
			alertToast('error','중분류분류을 입력하세요');
			$('#cate_nm').focus();
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
				$('#one_cate_form').submit();
				// 성공일 경우
				//$("#modal_one_cate_form").modal('hide');
				//alertToast('success','중분류분류가 생성 되었습니다.');
			}
		});	
		
	});

</script>