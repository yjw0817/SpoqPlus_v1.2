<style>
</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">

				<div class="card card-primary">
					<div class="page-header">
						<h3 class="panel-title">문자전송 이벤트 등록하기</h3>
					</div>
					<div class="panel-body">

						<form name="smslistForm" id="smslistForm" method="post">
							<table class="table table-bordered table-hover table-striped col-md-12">
								<thead>
									<tr>
										<th>업체</th>
										<th>신청이벤트</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><select class="form-control input-sm select2bs4"
											name="company_idx" id="company_idx"
											style="height: calc(1.90rem + 2px) !important;">
												<option value="">업체 선택</option>
											<?php foreach($company_list as $c) : ?>
											<option value="<?=$c['idx']?>"><?=$c['comp_name']?></option>
											<?php endforeach; ?>
						                  	</select>
						                </td>
										<td><select class="form-control input-sm select2bs4"
											name="event_idx" id="event_idx"
											style="height: calc(1.90rem + 2px) !important;">
												<option value="">이벤트 선택</option>
											<?php foreach($event_list as $e) : ?>
											<option value="<?=$e['idx']?>"><?=$e['event_name']?></option>
											<?php endforeach; ?>
						                  	</select>
						                </td>
						                <td>
						                	<a class='btn btn-sm btn-success' onclick="insert_smslist();">이벤트등록</a>
						                </td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
				</div>

			</div>
		</div>
		
		
		
		<div class="card card-primary">
			<div class="page-header">
				<h3 class="panel-title">문자리스트 검색 조건</h3>
			</div>
			<div class="panel-body">
			

				<form name="smslistSearchForm" id="smslistSearchForm" method="post" action="/dbmanage/mDblist">
					<table class="table table-bordered table-hover table-striped col-md-12">
						<thead>
							<tr>
								<th>업체</th>
								<td>
								<select class="form-control input-sm select2bs4"
								name="cnm" id="cnm"
								style="height: calc(1.90rem + 2px) !important;">
									<option value="">업체 선택</option>
								<?php foreach($company_list as $c) : ?>
									<?php if ($c['idx'] == $search_val['cnm']) : ?>
										<option value="<?=$c['idx']?>" selected><?=$c['comp_name']?></option>
									<?php else: ?>
										<option value="<?=$c['idx']?>"><?=$c['comp_name']?></option>
									<?php endif; ?>
								<?php endforeach; ?>
			                  	</select>
								
								</td>
								<th>신청이벤트</th>
								<td>
								<select class="form-control input-sm select2bs4"
								name="ecd" id="ecd"
								style="height: calc(1.90rem + 2px) !important;">
									<option value="">이벤트 선택</option>
								<?php foreach($event_list as $e) : ?>
									<?php if ($e['idx'] == $search_val['ecd']) : ?>
										<option value="<?=$e['idx']?>" selected><?=$e['event_name']?></option>
									<?php else: ?>
										<option value="<?=$e['idx']?>"><?=$e['event_name']?></option>
									<?php endif; ?>
								<?php endforeach; ?>
			                  	</select>
			                  	
								</td>
								<td>
									<a class='btn btn-sm btn-success' onclick="f_search();" >검색</a>
								</td>
							</tr>

						</thead>
					</table>
				</form>
				
			</div>
		</div>

		

		<div class="row">
			<div class="col-md-12">
				<?=$pager?>
				<table class="table table-bordered table-hover table-striped table-hover col-md-12">
					<thead>
						<tr>
							<?php
							if ( $_SESSION['session_admin'] == 'Y') :
								echo '<th>삭제</th>';
							endif;
							?>
							<th>번호</th>
							
							<th>업체</th>
							<th>신청이벤트</th>
							<th>전화번호 관리</th>
							<th>사용여부</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$listCount = $search_val['listCount'];
						foreach ( $sms_list as $r ) :
							?>
						<tr>
							<?php
							if ( $_SESSION['session_admin'] == 'Y') :
								echo "<td><a class='btn btn-sm btn-danger' onclick='f_del(" . $r['idx'] . ")'>삭제</a></td>";
							endif;
							?>
							<td><?=$listCount?></td>
							<?php
							if ( $_SESSION['session_admin'] == 'Y') :
							?>
							<td><?=$r['comp_name']?></td>
							<td><?=$r['event_name']?></td>
							
							
							<!-- 
							<td><select class="input-sm select2bs4"
								style="height: calc(1.90rem + 2px) !important;"
								onchange="change_company(this,<?=$r['idx']?>);">
									<option value="">선택하세요</option>
								<?php foreach ($company_list as $c): ?>
									<?php if ($c['idx'] == $r['company_idx']) : ?>
										<option value="<?=$c['idx']?>" selected><?=$c['comp_name']?></option>
									<?php else: ?>
										<option value="<?=$c['idx']?>"><?=$c['comp_name']?></option>
									<?php endif; ?>
			                    <?php endforeach; ?>
			                  	</select>
							</td>
							
							<td><select class="input-sm select2bs4"
								style="height: calc(1.90rem + 2px) !important;"
								onchange="change_event(this,<?=$r['idx']?>);">
									<option value="">선택하세요</option>
								<?php foreach ($event_list as $e): ?>
									<?php if ($e['idx'] == $r['event_idx']) : ?>
										<option value="<?=$e['idx']?>" selected><?=$e['event_name']?></option>
									<?php else: ?>
										<option value="<?=$e['idx']?>"><?=$e['event_name']?></option>
									<?php endif; ?>
			                    <?php endforeach; ?>
			                  	</select>
							</td>
							 -->
							 
							<?php
							else :
							?>
							<td><?=$r['comp_name'] ?></td>
							<td><?=$r['event_name'] ?></td>
							<?php endif; ?>
							<td>
								<textarea row='2' style='width:100%' name='phone_list' id='phone_list_<?=$r['idx']?>' placeholder="여러번호입력시 , 로 구분하세요"><?=$r['phone_list']?></textarea>
								<a class='btn btn-success btn-sm' onclick='update_phone(<?=$r['idx']?>)'>전화번호 업데이트</a>
							</td>
							<td>
							<select name='use_sms' id='use_sms' onchange="update_use_sms(<?=$r['idx']?>,this.value);">
								<?php if($r['use_sms'] == 'Y') :  ?>
								<option value="Y" selected>사용</option>
								<option value="N">사용안함</option>
								<?php else: ?>
								<option value="Y">사용</option>
								<option value="N" selected>사용안함</option>
								<?php endif; ?>
							</select>
							</td>
						</tr>
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
	
	
	<div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">상담내용</h4>
              <button type="button" class="close"  data-bs-dismiss="modal" aria-label="닫기">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            
            <form name="scontent_form" id="scontent_form" method="post"> 
            
            	<table class="table table-bordered table-hover table-striped col-md-12">
					<thead>
						<tr>
							<th>상담내용</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<textarea style="width:100%" name="scontent" id="scontent" rows='10'></textarea>
							</td>
						</tr>
					</tbody>
				</table>
				<input type="hidden" name="db_idx" id="db_idx" />
			</form>
			
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-primary btn-sm" onclick="scontent_modify();">입력하기</button>
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

	function f_del(idx)
	{
		if ( confirm("정말로 삭제하시겠습니까?") )
		{
			var params = "idx="+idx;
		    jQuery.ajax({
		        url: '/dbmanage/ajax_dblist_deleteProc',
		        type: 'POST',
		        data:params,
		        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
		        dataType: 'text',
		        success: function (result) {
					json_result = $.parseJSON(result);
					if (json_result['result'] == 'true')
					{
						alert("삭제가 완료 되었습니다.");
						location.reload();
					}
		        }
		    });
		}
		
	}
	
	function update_use_sms(db_idx,use_sms)
	{
		var params = "idx="+db_idx+"&use_sms="+use_sms;
	    jQuery.ajax({
	        url: '/manage/ajax_update_use_sms',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert("사용설정이 변경되었습니다.");
				}
	        }
	    });
	    
	}
	
	function update_phone(db_idx)
	{
		var phone_list= $('#phone_list_'+db_idx).val();
		var params = "idx="+db_idx+"&phone_list="+phone_list;
	    jQuery.ajax({
	        url: '/manage/ajax_update_phonelist',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert("전화번호 리스트가 업데이트 되었습니다.");
				}
	        }
	    });
		
	}

	function scontent_pop(db_idx)
	{
		var params = "idx="+db_idx;
	    jQuery.ajax({
	        url: '/dbmanage/ajax_scontentCheck',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					$('#modal-xl').modal('show');
					
					$('#db_idx').val( json_result['db_content']['idx'] );
					//$('#scontent').val("aaaa");
					$('#scontent').val( json_result['db_content']['scontent'] );
				}
	        }
	    });
	}
	
	function scontent_modify()
	{
		var params = $("#scontent_form").serialize();
	    jQuery.ajax({
	        url: '/dbmanage/ajax_scontentModifyProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('상담내용이 입력 되었습니다.');
					//location.href="/manage/mCompanyModify";
					location.reload();
				}
	        }
	    });
	}

	

	function f_search()
	{
		var f = document.smslistSearchForm;
		f.action = "/manage/mSmsManager";
		
		f.submit();
	}
	
	function change_scode(t,idx)
	{
		var params = "idx="+idx+"&user_status="+t.value;
	    jQuery.ajax({
	        url: '/dbmanage/ajax_mDblistStatusChange',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('상태가 변경되었습니다.');
					//location.reload();
				}
	        }
	    });
	}
	
	
	function insert_smslist()
	{
		if ( $('#company_idx').val() == "")
		{
			alert('업체를 선택하세요');
			return;
		}
		
		if ( $('#event_idx').val() == "")
		{
			alert('이벤트를 선택하세요');
			return;
		}
	
		var params = $("#smslistForm").serialize();
	    jQuery.ajax({
	        url: '/manage/ajax_mSmslistInsertProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('문자관리 이벤트가 등록되었습니다.');
					location.reload();
				}
	        }
	    });
	}
	
	function f_del(idx)
	{
		if ( confirm("정말로 삭제하시겠습니까?") )
		{
			var params = "idx="+idx;
		    jQuery.ajax({
		        url: '/manage/ajax_smslist_deleteProc',
		        type: 'POST',
		        data:params,
		        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
		        dataType: 'text',
		        success: function (result) {
					json_result = $.parseJSON(result);
					if (json_result['result'] == 'true')
					{
						alert("삭제가 완료 되었습니다.");
						location.reload();
					}
		        }
		    });
		}
		
	}
	
</script>