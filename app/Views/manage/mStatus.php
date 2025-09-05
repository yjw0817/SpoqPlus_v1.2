<style>

</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			
				<div class="card card-primary">
					<div class="page-header">
						<h3 class="panel-title">진행 상태 및 내원이유 관리</h3>
					</div>
					<div class="panel-body">
						<div class='row'>
							<table class="table table-bordered table-hover table-striped col-md-4">
								<thead>
									<tr>
										<th>진행상태명</th>
										<th>진행상태코드</th>
										<th>기능</th>
									</tr>
								</thead>
								<tbody>
									<form name='scode_form' id='scode_form'>
										<tr>
											<td>
												<input type='text' class='input-sm' id='sname' name='sname' style='width:100%' placeholder='진행상태명' />
											</td>
											<td>
												<input type='text' class='input-sm' id='scode' name='scode' style='width:100%' placeholder='진행상태코드' />
											</td>
											<td>
												<a class='btn btn-sm btn-success' onclick="scode_form_submit();">추가하기</a>
											</td>
										</tr>
									</form>
									
									<?php foreach ($scode_list as $r) { ?>
										<tr>
											<td><?=$r['sname']?></td>
											<td><?=$r['scode']?></td>
											<td>
												<a class='btn btn-sm btn-danger' onclick="scode_del(<?=$r['idx']?>);">삭제</a>
											</td>
										</tr>
									<?php } ?>
									
									
								</tbody>
							</table>
							
							<table class="table table-bordered table-hover table-striped col-md-4">
								<thead>
									<tr>
										<th>내원이유명</th>
										<th>내원이유코드</th>
										<th>기능</th>
									</tr>
								</thead>
								<tbody>
									<form name='ncode_form' id='ncode_form'>
										<tr>
											<td>
												<input type='text' class='input-sm' id='nname' name='nname' style='width:100%' placeholder='내원이유명' />
											</td>
											<td>
												<input type='text' class='input-sm' id='ncode' name='ncode' style='width:100%' placeholder='내원이유코드' />
											</td>
											<td>
												<a class='btn btn-sm btn-success' onclick="ncode_form_submit();">추가하기</a>
											</td>
										</tr>
									</form>
									
									<?php foreach ($ncode_list as $r) { ?>
										<tr>
											<td><?=$r['nname']?></td>
											<td><?=$r['ncode']?></td>
											<td>
												<a class='btn btn-sm btn-danger' onclick="ncode_del(<?=$r['idx']?>);">삭제</a>
											</td>
										</tr>
									<?php } ?>
									
								</tbody>
							</table>
							
							<table class="table table-bordered table-hover table-striped col-md-4">
								<thead>
									<tr>
										<th>업체구분명</th>
										<th>업체구분코드</th>
										<th>기능</th>
									</tr>
								</thead>
								<tbody>
									<form name='gcode_form' id='gcode_form'>
										<tr>
											<td>
												<input type='text' class='input-sm' id='gname' name='gname' style='width:100%' placeholder='업체구분명' />
											</td>
											<td>
												<input type='text' class='input-sm' id='gcode' name='gcode' style='width:100%' placeholder='업체구분코드' />
											</td>
											<td>
												<a class='btn btn-sm btn-success' onclick="gcode_form_submit();">추가하기</a>
											</td>
										</tr>
									</form>
									
									<?php foreach ($gcode_list as $r) { ?>
										<tr>
											<td><?=$r['gname']?></td>
											<td><?=$r['gcode']?></td>
											<td>
												<a class='btn btn-sm btn-danger' onclick="gcode_del(<?=$r['idx']?>);">삭제</a>
											</td>
										</tr>
									<?php } ?>
									
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

	function scode_del(idx)
	{
		var params = "idx="+idx;
	    jQuery.ajax({
	        url: '/manage/ajax_scode_delete',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('ok');
					location.reload();
				}
	        }
	    });
	}
	
	function ncode_del(idx)
	{
		var params = "idx="+idx;
	    jQuery.ajax({
	        url: '/manage/ajax_ncode_delete',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('ok');
					location.reload();
				}
	        }
	    });
	}
	
	function gcode_del(idx)
	{
		var params = "idx="+idx;
	    jQuery.ajax({
	        url: '/manage/ajax_gcode_delete',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('ok');
					location.reload();
				}
	        }
	    });
	}
	

	function scode_form_submit()
	{
		if ( $('#sname').val() == "" )
		{
			alert('진행 상태명을 입력하세요');
			$('#sname').focus();
			return;
		}
		
		if ( $('#scode').val() == "" )
		{
			alert('진행 상태코드를 입력하세요');
			$('#scode').focus();
			return;
		}
		
		var params = $("#scode_form").serialize();
	    jQuery.ajax({
	        url: '/manage/ajax_scode_insert',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('ok');
					location.reload();
				}
	        }
	    });
		
	}
	
	
	function ncode_form_submit()
	{
		if ( $('#nname').val() == "" )
		{
			alert('내원이유명을 입력하세요');
			$('#nname').focus();
			return;
		}
		
		if ( $('#ncode').val() == "" )
		{
			alert('내원이유 코드를 입력하세요');
			$('#ncode').focus();
			return;
		}
		
		var params = $("#ncode_form").serialize();
	    jQuery.ajax({
	        url: '/manage/ajax_ncode_insert',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
	            json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('ok');
					location.reload();
				}
	        }
	    });
		
	}
	
	function gcode_form_submit()
	{
		if ( $('#gname').val() == "" )
		{
			alert('업체구분명을 입력하세요');
			$('#gname').focus();
			return;
		}
		
		if ( $('#gcode').val() == "" )
		{
			alert('업체구분 코드를 입력하세요');
			$('#scode').focus();
			return;
		}
		
		var params = $("#gcode_form").serialize();
	    jQuery.ajax({
	        url: '/manage/ajax_gcode_insert',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
	            json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('ok');
					location.reload();
				}
	        }
	    });
		
	}

</script>