<style>

</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			
				<div class="card card-primary">
					<div class="page-header">
						<h3 class="panel-title">업체관리 리스트</h3>
					</div>
					<div class="panel-body">
						
						<form name="companyForm" id="companyForm" method="post" action="/manage/mCompany">
							<table class="table table-bordered table-hover table-striped col-md-12">
								<thead>
									<tr>
										<th>아이디</th>
										<th>업체명</th>
										<th>구분</th>
										<th>사용여부</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<input type="text" name="comp_id" id="comp_id" />
										</td>
										<td>
											<input type="text" name="comp_name" id="comp_name" />
										</td>
										<td>
											<select class="form-control input-sm select2bs4"  name="comp_div" id="comp_div" style="height:calc(1.90rem + 2px) !important;">
											<option value="" >선택하세요</option>
											<?php foreach ($gcode_list as $g): ?>
						                    <option value="<?=$g['gcode']?>" ><?=$g['gname']?></option>
						                    <?php endforeach; ?>
						                  	</select>
										</td>
										<td>
											<input type="text" name="comp_use" id="comp_use" />
										</td>
									</tr>
								</tbody>
							</table>
						</form>	
						
						<a class='btn btn-sm btn-success' onclick="f_search();" >검색</a>
					
						<a class='btn btn-sm btn-success' href="/manage/mCompanyInsert">업체등록</a>
					</div>
				</div>
				
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered table-hover table-striped col-md-12">
					<thead>
						<tr>
							<th>삭제</th>
							<th>아이디</th>
							<th>업체명</th>
							<th>구분</th>
							<th>사용여부</th>
							<th>이벤트수</th>
							<th>이벤트 관리</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						foreach ($company_list as $r) : 
						?>
						<tr>
							<td>
								<a class='btn btn-sm btn-danger' onclick="f_comp_del(<?=$r['idx']?>,'Y');" >삭제</a>
							</td>
							<td><?=$r['comp_id'] ?></td>
							<td><?=$r['comp_name'] ?></td>
							<td><?=$r['gname'] ?></td>
							<td><?=$r['comp_use'] ?></td>
							<td><?=$r['comp_event_counter'] ?></td>
							<td>
								<a onclick="mcompany_event(<?=$r['idx']?>);">
									<i class="fas fa-cog"></i>
								</a>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?=$pager?>
			</div>
			
		</div>
		
		
		
		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered table-hover table-striped col-md-12">
					<thead>
						<tr>
							<th>삭제취소</th>
							<th>아이디</th>
							<th>업체명</th>
							<th>구분</th>
							<th>사용여부</th>
							<th>이벤트수</th>
							<th>이벤트 관리</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						foreach ($company_list_del as $r) : 
						?>
						<tr>
							<td>
								<a class='btn btn-sm btn-info' onclick="f_comp_del(<?=$r['idx']?>,'N');" >삭제취소</a>
							</td>
							<td><?=$r['comp_id'] ?></td>
							<td><?=$r['comp_name'] ?></td>
							<td><?=$r['gname'] ?></td>
							<td><?=$r['comp_use'] ?></td>
							<td><?=$r['comp_event_counter'] ?></td>
							<td>
								<a onclick="mcompany_event(<?=$r['idx']?>);">
									<i class="fas fa-cog"></i>
								</a>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			
		</div>
		
		
		
		
	</div>
</section>
		

<?=$jsinc ?>	

<script>
	function f_search()
	{
		var f = document.companyForm;
		f.submit();
	}
	
	function mcompany_event(idx)
	{
		location.href="/manage/mCompanyModify/"+idx;
	}
	
	function f_comp_del(idx,delyn)
	{
		var params = "idx="+idx+"&delyn="+delyn;
	    jQuery.ajax({
	        url: '/manage/ajax_mCompanyDeleteProc',
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