<style>

</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			
				<div class="card card-primary">
					<div class="page-header">
						<h3 class="panel-title">업체그룹 관리</h3>
					</div>
					<div class="panel-body">
						<form name="comp_modify_form" id="comp_modify_form">
						<input type="hidden" name="idx" id="idx" value="<?=$view['data'][0]['idx']?>" />
						<div class="row">
							<div class="col-md-3">
								업체명 :  
								<input type="text" name="comp_group_name" id="comp_group_name" class="input-sm" value="<?=$view['data'][0]['comp_group_name']?>" />
							</div>
							
							<div class="col-md-3">
								아이디 : 
								<input type="text" name="comp_group_id" id="comp_group_id" class="input-sm" readonly value="<?=$view['data'][0]['comp_group_id']?>" />
								
							</div>
							<div class="col-md-3">
								비밀번호 : 
								<input type="password" name="comp_group_pass" id="comp_group_pass" class="input-sm" value="<?=$view['data'][0]['comp_group_pass']?>" />
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								접속가능 여부 : <input type="checkbox" name="comp_group_use" id="comp_group_use" class="input-sm" value="Y" <?php if($view['data'][0]['comp_group_use'] == 'Y') { echo 'checked'; } ?> />
							</div>
							<div class="col-md-6">
								<a class="btn btn-success btn-sm" onclick="comp_group_modify();">수정하기</a>								
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
						<h3 class="panel-title">관리 업체</h3>
					</div>
					<div class="panel-body">
					
						<form name="event_insert_form" id="event_insert_form">
						<input type="hidden" name="comp_group_idx" id="comp_group_idx" value="<?=$view['data'][0]['idx']?>" />
						<div class="row">
							<div class="col-md-9">
								<select class="form-control input-sm select2bs4"
											name="comp_idx" id="comp_idx"
											style="height: calc(1.90rem + 2px) !important;">
												<option value="">업체 선택</option>
											<?php foreach($company_list as $c) : ?>
											<option value="<?=$c['idx']?>"><?=$c['comp_name']?> (<?=$c['comp_id'] ?>)</option>
											<?php endforeach; ?>
						        </select>
							</div>
						
							<div class="col-md-3">
								<a class="btn btn-success btn-sm" onclick="manage_comp_insert();">관리업체 등록</a>								
							</div>
						
						</form>
					
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<table class="table table-bordered table-hover table-striped col-md-12">
								<thead>
									<tr>
										<th>업체명</th>
										<th>업체아이디</th>
										<th>관리</th>
									</tr>
								</thead>
								
								<tbody>
									<?php 
									foreach ($company_group_manage_list as $r) : 
									?>
									<tr>
										<td><?=$r['comp_name'] ?></td>
										<td><?=$r['comp_id'] ?></td>
										<td>
	                						<a class='btn btn-sm btn-danger' onclick="manage_comp_del(<?=$r['cg_idx']?>);" >삭제</a>
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
	
	
</section>
		

<?=$jsinc ?>	

<script>

$("#comp_idx").select2();

	function comp_group_modify()
	{
		var params = $("#comp_modify_form").serialize();
	    jQuery.ajax({
	        url: '/manage/ajax_mCompanyGroupModifytProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('업체그룹 정보가 수정 되었습니다.');
					//location.href="/manage/mCompanyModify";
					location.reload();
				}
	        }
	    });
	}
	
	function manage_comp_insert()
	{
		var params = $("#event_insert_form").serialize();
	    jQuery.ajax({
	        url: '/manage/ajax_CompanyGroupManageInsertProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('관리 업체가 등록 되었습니다.');
					//location.href="/manage/mCompanyModify";
					location.reload();
				}
	        }
	    });
	}

	function manage_comp_del(idx)
	{
		if (confirm('정말로 삭제하시겠습니까?'))
		{
		
			var params = "idx="+idx;
		    jQuery.ajax({
		        url: '/manage/ajax_CompanyGroupManageDeleteProc',
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
	}

</script>