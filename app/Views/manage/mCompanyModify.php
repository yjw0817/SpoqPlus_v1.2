<style>

</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			
				<div class="card card-primary">
					<div class="page-header">
						<h3 class="panel-title">업체관리</h3>
					</div>
					<div class="panel-body">
						<form name="comp_modify_form" id="comp_modify_form">
						<input type="hidden" name="idx" id="idx" value="<?=$view['data'][0]['idx']?>" />
						<div class="row">
							<div class="col-md-3">
								업체명 :  
								<input type="text" name="comp_name" id="comp_name" class="input-sm" value="<?=$view['data'][0]['comp_name']?>" />
							</div>
							<div class="col-md-3">
								구분 : 
								<select class="input-sm select2bs4"  name="comp_div" id="comp_div" style="height:calc(1.90rem + 2px) !important;">
								<option value="" >선택하세요</option>
								<?php foreach ($gcode_list as $g): ?>
									<?php if ($g['gcode'] == $view['data'][0]['comp_div']) : ?>
										<option value="<?=$g['gcode']?>" selected ><?=$g['gname']?></option>
									<?php else: ?>
										<option value="<?=$g['gcode']?>" ><?=$g['gname']?></option>
									<?php endif; ?>
			                    <?php endforeach; ?>
			                  	</select>
							</div>
							<div class="col-md-3">
								아이디 : 
								<input type="text" name="comp_id" id="comp_id" class="input-sm" readonly value="<?=$view['data'][0]['comp_id']?>" />
								
							</div>
							<div class="col-md-3">
								비밀번호 : 
								<input type="password" name="comp_pass" id="comp_pass" class="input-sm" value="<?=$view['data'][0]['comp_pass']?>" />
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								접속가능 여부 : <input type="checkbox" name="comp_use" id="comp_use" class="input-sm" value="Y" <?php if($view['data'][0]['comp_use'] == 'Y') { echo 'checked'; } ?> />
							</div>
							<div class="col-md-6">
								<a class="btn btn-success btn-sm" onclick="comp_modify();">수정하기</a>								
							</div>
						</div>
						
						</form>
						
						
					</div>
				</div>
				
			</div>
		</div>
		
		
		<div class="row">
			<div class="col-md-12">
			
				<div class="card card-primary">
					<div class="page-header">
						<h3 class="panel-title">이벤트 관리</h3>
					</div>
					<div class="panel-body">
					
						<form name="event_insert_form" id="event_insert_form">
						<input type="hidden" name="comp_idx" id="comp_idx" value="<?=$view['data'][0]['idx']?>" />
						<div class="row">
							<div class="col-md-3">
								이벤트명 :  
								<input type="text" name="event_name" id="event_name" class="input-sm" value="" />
							</div>
							<div class="col-md-3">
								구분 : 
								<select class="input-sm select2bs4"  name="event_div" id="event_div" style="height:calc(1.90rem + 2px) !important;">
								<option value="" >선택하세요</option>
								<?php foreach ($ncode_list as $n): ?>
									<option value="<?=$n['ncode']?>" ><?=$n['nname']?></option>
			                    <?php endforeach; ?>
			                  	</select>
							</div>
							<div class="col-md-3">
								담당자 : 
								<input type="text" name="event_damdang" id="event_damdang" class="input-sm" value="" />
								
							</div>
							<div class="col-md-3">
								이벤트 URL : 
								<input type="text" name="event_url" id="event_url" class="input-sm" value="" />
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-12">
								<a class="btn btn-success btn-sm" onclick="event_insert();">이벤트 등록</a>								
							</div>
						</div>
						
						</form>
					
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<table class="table table-bordered table-hover table-striped col-md-12">
								<thead>
									<tr>
										<th>코드</th>
										<th>이벤트명</th>
										<th>구분</th>
										<th>담당자</th>
										<th>이벤트 URL</th>
										<th>관리</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									foreach ($event_list as $r) : 
									?>
									<tr>
										<td><?=$r['idx'] ?></td>
										<td><?=$r['event_name'] ?></td>
										<td><?=$r['nname'] ?></td>
										<td><?=$r['event_damdang'] ?></td>
										<td><?=$r['event_url'] ?></td>
										<td>
											<a  class="btn btn-success btn-sm" onclick="mcompany_event_modify(<?=$r['idx']?>,<?=$r['comp_idx']?>);">
	                  							이벤트수정
	                						</a>
	                						<a class='btn btn-sm btn-danger' onclick="f_event_del(<?=$r['idx']?>,'Y');" >삭제</a>
                						</td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
						
					</div>
					
					
					
					
					<div class="row">
						<div class="col-md-12">
							<table class="table table-bordered table-hover table-striped col-md-12">
								<thead>
									<tr>
										<th>코드</th>
										<th>이벤트명</th>
										<th>구분</th>
										<th>담당자</th>
										<th>이벤트 URL</th>
										<th>관리</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									foreach ($event_list_del as $r) : 
									?>
									<tr>
										<td><?=$r['idx'] ?></td>
										<td><?=$r['event_name'] ?></td>
										<td><?=$r['nname'] ?></td>
										<td><?=$r['event_damdang'] ?></td>
										<td><?=$r['event_url'] ?></td>
										<td>
											<a  class="btn btn-success btn-sm" onclick="mcompany_event_modify(<?=$r['idx']?>,<?=$r['comp_idx']?>);">
	                  							이벤트수정
	                						</a>
	                						<a class='btn btn-sm btn-info' onclick="f_event_del(<?=$r['idx']?>,'N');" >삭제취소</a>
                						</td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
						
					</div>
					
					
					
					
					
				</div>
			</div>
		</div>
		
		
	</div>
	
	
	<div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">이벤트 수정하기</h4>
              <button type="button" class="close"  data-bs-dismiss="modal" aria-label="닫기">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            
            <form name="event_modiy_form" id="event_modify_form" method="post"> 
            
            	<table class="table table-bordered table-hover table-striped col-md-12">
					<thead>
						<tr>
							<th>코드</th>
							<th>이벤트명</th>
							<th>구분</th>
							<th>담당자</th>
							<th>이벤트 URL</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><input type="text" name="get_event_idx" id="get_event_idx" readonly /></td>
							<td><input type="text" name="get_event_name" id="get_event_name" /></td>
							<td>
								<select class="input-sm select2bs4"  name="get_event_div" id="get_event_div" style="height:calc(1.90rem + 2px) !important;">
								<option value="" >선택하세요</option>
								<?php foreach ($ncode_list as $n): ?>
									<option value="<?=$n['ncode']?>" ><?=$n['nname']?></option>
			                    <?php endforeach; ?>
			                  	</select>
							</td>
							<td><input type="text" name="get_event_damdang" id="get_event_damdang" /></td>
							<td><input type="text" name="get_event_url" id="get_event_url" /></td>
						</tr>
					</tbody>
				</table>
				<input type="hidden" name="get_event_comp_idx" id="get_event_comp_idx" />
			</form>
			
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-primary btn-sm" onclick="event_modify();">수정하기</button>
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

	function mcompany_event_modify(idx,comp_idx)
	{
		var params = "idx="+idx+"&comp_idx="+comp_idx;
	    jQuery.ajax({
	        url: '/manage/ajax_mEventGet',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					$('#modal-xl').modal('show');
					
					$('#get_event_idx').val( json_result['eventinfo']['idx'] );
					$('#get_event_comp_idx').val( json_result['eventinfo']['comp_idx'] );
					$('#get_event_name').val( json_result['eventinfo']['event_name'] );
					$('#get_event_div').val( json_result['eventinfo']['event_div'] ).attr("selected", "selected");
					
					
					$('#get_event_damdang').val( json_result['eventinfo']['event_damdang'] );
					$('#get_event_url').val( json_result['eventinfo']['event_url'] );
				}
	        }
	    });
	}

	function comp_modify()
	{
		var params = $("#comp_modify_form").serialize();
	    jQuery.ajax({
	        url: '/manage/ajax_mCompanyModifytProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('업체내용이 수정 되었습니다.');
					//location.href="/manage/mCompanyModify";
					location.reload();
				}
	        }
	    });
	}
	
	function event_insert()
	{
		var params = $("#event_insert_form").serialize();
	    jQuery.ajax({
	        url: '/manage/ajax_mEvnetInsertProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('이벤트가 등록 되었습니다.');
					//location.href="/manage/mCompanyModify";
					location.reload();
				}
	        }
	    });
	}
	
	function event_modify()
	{
		var params = $("#event_modify_form").serialize();
	    jQuery.ajax({
	        url: '/manage/ajax_mEventModifytProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('이벤트가 수정 되었습니다.');
					//location.href="/manage/mCompanyModify";
					location.reload();
				}
	        }
	    });
	}
	
	function f_event_del(idx,delyn)
	{
		var params = "idx="+idx+"&delyn="+delyn;
	    jQuery.ajax({
	        url: '/manage/ajax_mEventDeleteProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					//alert('삭제되었습니다.');
					location.reload();
				}
	        }
	    });
	}

</script>