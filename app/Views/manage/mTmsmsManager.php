<style>

</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			
				<div class="card card-primary">
					<div class="page-header">
						<h3 class="panel-title">TM 문자관리</h3>
					</div>
					
					<div class="panel-body">
						<div class="row">
							<a class='btn btn-sm btn-success' onclick="tm_sms_modal_pop('')">TM 문자등록</a>
						</div>
					</div>
					
					<div class="panel-body">
						
					
						<div class="row">
							<?php
							foreach ($tm_sms_list as $r) : 
							?>
								<div class="col-md-3">
									<div class="row">
										<div class="col-md-12">
											<table class="table table-bordered table-hover table-striped col-md-12">
												<tr>
													<td style='width:120px'>단축버튼명</td>
													<td class='bg-white'><?=$r['sms_stitle']?></td>
												<tr>
												<tr>
													<td style='width:120px'>관리제목</td>
													<td class='bg-white'><?=$r['sms_title']?></td>
												<tr>
												<tr>
													<td colspan="2" class='bg-white' style='padding:25px'><?=nl2br($r['sms_content'])?></td>
												<tr>
												
												<tr>
													<td colspan="2">
														<a class='btn btn-info btn-xs' onclick="tm_sms_modal_pop(<?=$r['idx']?>)">수정하기</a>
														<a class='btn btn-danger btn-xs' onclick="tm_sms_delete_proc(<?=$r['idx']?>)">삭제하기</a>
													</td>
												<tr>
											</table>
										</div>
									</div>
								</div>
							
							<?php
							endforeach;
							?>
						</div>
						
					</div>
				</div>
				
			</div>
		</div>
	</div>
	
	<!-- =========================================== 
				TM SMS 등록 
	=========================================== -->
	<div class="modal fade" id="tm_sms_modal_pop">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">티엠 문자설정</h4>
              <button type="button" class="close"  data-bs-dismiss="modal" aria-label="닫기">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <!-- Form Start -->
            <form name="tm_sms_modal_form" id="tm_sms_modal_form" method="post"> 
            	
            	
            	<div class="row">
					<div class="col-md-4">
            	
		            	<table class="table table-bordered table-hover table-striped col-md-12">
							<tr>
								<th class='text-center' style='width:120px'>단축버튼명</th>
								<td>
									<input style='width:150px' type="text" class="" id="sms_stitle" name="sms_stitle" />
								</td>
							</tr>
							<tr>
								<th class='text-center' style='width:120px'>관리제목</th>
								<td>
									<input style='width:150px' type="text" class="" id="sms_title" name="sms_title" />
								</td>
							</tr>
							<tr>
								<td colspan='2'>
									<textarea style='width:100%' type="text" class="sms_content" id="sms_content" name="sms_content" rows="10" ></textarea>
									<br />
									<span class="textCount">0자</span>
		    						<span class="textTotal">/200자</span>
								</td>
							</tr>
						</table>
						<input type="hidden" name="idx" id="idx" />
				
					</div>
					<div class="col-md-4">
						[#이름] , [#예약시간]
						<br />
						예) <br />
						[#이름]님 안녕하세요.<br /> 
						[#예약시간]에 내원 예약되었습니다.<br />
					</div>
				</div>
				
				
			</form>
			<!-- Form End -->
            </div>
            <div class="modal-footer text-left">
            	<button type="button" class="btn btn-primary btn-sm" onclick="tm_sms_modal_action();" id="tm_sms_model_btn">등록/수정 하기</button>
              	<button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

	<form name='tm_sms_delete_form' id='tm_sms_delete_form'>
		<input type='hidden' name='delete_idx' id='delete_idx' />
	</form>

	
</section>
		

<?=$jsinc ?>	

<script>

$('#sms_content').keyup(function (e) {
	let content = $(this).val();
    
    // 글자수 세기
    if (content.length == 0 || content == '') {
    	$('.textCount').text('0자');
    } else {
    	$('.textCount').text(content.length + '자');
    }
    
    // 글자수 제한
    if (content.length > 200) {
    	// 200자 부터는 타이핑 되지 않도록
        //$(this).val($(this).val().substring(0, 200));
        // 200자 넘으면 알림창 뜨도록
        //alert('글자수는 200자까지 입력 가능합니다.');
    }
});

function tm_sms_delete_proc(idx)
{
	if ( confirm('정말로 삭제하시겠습니까?') )
	{
		$('#delete_idx').val(idx);
		var params = $("#tm_sms_delete_form").serialize();
	    jQuery.ajax({
	        url: '/manage/ajax_delete_tm_sms_proc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('정보가 삭제 되었습니다.');
					location.reload();
				}
	        }
	    });
	}
}

function tm_sms_modal_pop(idx)
{
	$('#tm_sms_modal_pop').modal('show');
	
	if (idx != '')
	{
		$('#idx').val(idx);
		var params = "idx="+idx;
	    jQuery.ajax({
	        url: '/manage/ajax_get_tmSms',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					$('#sms_title').val( json_result['db_content']['sms_title'] );
					$('#sms_stitle').val( json_result['db_content']['sms_stitle'] );
					$('#sms_content').val( json_result['db_content']['sms_content'] );
					
					let read_content = $('#sms_content').val();
					$('.textCount').text(read_content.length + '자');
					
					$('#tm_sms_model_btn').text('수정하기');
				}
	        }
	    });
	} else 
	{	
		$('#sms_title').val('');
		$('#sms_stitle').val('');
		$('#sms_content').val('');
		$('#idx').val('');
		$('#tm_sms_model_btn').text('등록하기');
	}
}

function tm_sms_modal_action()
{
	var params = $("#tm_sms_modal_form").serialize();
    jQuery.ajax({
        url: '/manage/ajax_action_tm_sms_proc',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				if ( json_result['mode'] == 'insert' )
				{
					alert('정보가 입력 되었습니다.');
				} else if ( json_result['mode'] == 'modify' )
				{
					alert('정보가 수정 되었습니다.');
				}
				location.reload();
			}
        }
    });

}




</script>