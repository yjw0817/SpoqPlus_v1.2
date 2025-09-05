<style>

</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			
				<div class="card card-primary">
					<div class="page-header">
						<h3 class="panel-title">TM 관리</h3>
					</div>
					
					<div class="panel-body">
						<div class="row">
							<a class='btn btn-sm btn-success' onclick="tm_modal_pop('')">TM 등록</a>
						</div>
					</div>
					
					<div class="panel-body">
						
					
						<div class="row">
							<div class="col-md-12">
								<table class="table table-bordered table-hover table-striped col-md-12">
									<thead>
										<tr>
											<th>번호</th>
											<th>TM 아이디</th>
											<th>이름</th>
											<th>전화번호</th>
											<!-- <th>그룹</th> -->
											<th>등록일</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$listCount = $search_val['listCount'];
										foreach ($tm_list as $r) : 
										?>
										<tr>
											<td><?=$listCount?></td>
											<td><?=$r['tm_id'] ?></td>
											<td><?=$r['tm_name'] ?></td>
											<td><?=$r['tm_phone'] ?></td>
											<!-- <td><?=$r['tm_group'] ?></td> -->
											<td><?=$r['rg_date'] ?></td>
											<td>
												<a class='btn btn-xs btn-success' 	onclick="tm_modal_pop('<?=$r['idx']?>')">정보수정</a>
												<a class='btn btn-xs btn-info' 		onclick="compset_modal_pop('<?=$r['tm_id']?>')">업체설정</a>
											</td>
										</tr>
										<input type='hidden' id="tm_comp_idx_<?=$r['tm_id']?>" value="<?=$r['comp_idx']?>" />
										<?php
										$listCount--;
										endforeach; 
										?>
									</tbody>
								</table>
								<?=$pager?>
							</div>
						</div>
				
						
					</div>
				</div>
				
			</div>
		</div>
	</div>
	
	<!-- =========================================== 
				TM 등록 / 수정 Modal 
	=========================================== -->
	<div class="modal fade" id="tm_modal_pop">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">티엠설정</h4>
              <button type="button" class="close"  data-bs-dismiss="modal" aria-label="닫기">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <!-- Form Start -->
            <form name="tm_modal_form" id="tm_modal_form" method="post"> 
            
            	<table class="table table-bordered table-hover table-striped col-md-12">
					<tr>
						<th class='text-center' style='width:100px'>TM ID</th>
						<td>
							<input style='width:150px' type="text" class="" id="tm_id" name="tm_id" />
						</td>
						<th class='text-center' style='width:100px'>Password</th>
						<td>
							<input style='width:150px' type="text" class="" id="tm_pass" name="tm_pass" />
						</td>
						<th class='text-center' style='width:100px'>이름</th>
						<td>
							<input style='width:150px' type="text" class="" id="tm_name" name="tm_name" />
						</td>
						<th  class='text-center'style='width:100px'>전화번호</th>
						<td>
							<input style='width:150px' type="text" class="" id="tm_phone" name="tm_phone" />
						</td>
					</tr>
				</table>
				<input type="hidden" name="tm_group" id="tm_group" value="tm" />
				<input type="hidden" name="idx" id="idx" />
			</form>
			<!-- Form End -->
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-primary btn-sm" onclick="tm_modal_action();" id="tm_model_btn">등록/수정 하기</button>
              	<button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      
      <!-- =========================================== 
				TM 업체 배분 설정 Modal 
	  =========================================== -->
	  <div class="modal fade" id="compset_modal_pop">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">업체설정</h4>
              <button type="button" class="close"  data-bs-dismiss="modal" aria-label="닫기">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <!-- Form Start -->
            <form name="compset_modal_form" id="compset_modal_form" method="post"> 
				<div class="row col-md-12">
				<?php
				foreach ($compset_list as $c) :
				?>            	
            	
            	<div class="icheck-primary d-inline col-md-3">
					<input type="checkbox" id="compset_chk_<?=$c['comp_idx']?>" name="compset_idx[]" value="<?=$c['comp_idx']?>" class="compset_chk">
					<label for="compset_chk_<?=$c['comp_idx']?>">
					<?=$c['comp_name']?>
					</label>
				</div>
            	
            	<?php
            	endforeach;
            	?>
            	</div>
            	
				<input type="hidden" name="compset_tm_id" id="compset_tm_id" />
			</form>
			<!-- Form End -->
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-primary btn-sm" onclick="compset_modal_action();" id="compset_model_btn">설정완료</button>
              	<button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
	  
	  
      
	
	
</section>
		

<?=$jsinc ?>	

<script>

function compset_modal_pop(tm_id)
{
	$('#compset_tm_id').val(tm_id);
	$('#compset_modal_pop').modal('show');
	$('.compset_chk').prop("checked", false);
	
	var tm_comp_idx = $('#tm_comp_idx_'+tm_id).val();
	
	if (tm_comp_idx != '' )
	{
		var arr =  tm_comp_idx.split(",");
		for (i = 0; i < arr.length; i++) {
		
			$('#compset_chk_'+ arr[i] ).prop("checked", true);
		} 
	}
	
}

function compset_modal_action()
{
	var params = $("#compset_modal_form").serialize();
    jQuery.ajax({
        url: '/manage/ajax_action_tm_compset_proc',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				location.reload();
			}
        }
    });
}




function tm_modal_pop(idx)
{
	$('#idx').val(idx);
	$('#tm_modal_pop').modal('show');
	
	if (idx != '')
	{
		var params = "idx="+idx;
	    jQuery.ajax({
	        url: '/manage/ajax_get_tmInfo',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					//$('#modal-xl').modal('show');
					$('#tm_id').val( json_result['db_content']['tm_id'] );
					$('#tm_pass').val( json_result['db_content']['tm_pass'] );
					$('#tm_name').val( json_result['db_content']['tm_name'] );
					$('#tm_phone').val( json_result['db_content']['tm_phone'] );
					//$('#tm_group').val( json_result['db_content']['tm_group'] );
					
					$('#tm_model_btn').text('수정하기');
				}
	        }
	    });
	} else 
	{	
		$('#tm_model_btn').text('등록하기');
	}
}

function tm_modal_action()
{
	var params = $("#tm_modal_form").serialize();
    jQuery.ajax({
        url: '/manage/ajax_action_tm_proc',
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