<style>

</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			
				<div class="card card-primary">
					<div class="page-header">
						<h3 class="panel-title">업체관리 등록하기</h3>
					</div>
					<div class="panel-body">
						
						<form name="comp_insert_form" id="comp_insert_form">
						<input type='hidden' name='d_chk' id='d_chk' value='N' />
						<div class="row">
							<div class="col-md-3">
								업체그룹명 :  
								<input type="text" name="comp_group_name" id="comp_group_name" class="input-sm" />
							</div>
							
							<div class="col-md-3">
								그룹아이디 : 
								<input type="text" name="comp_group_id" id="comp_group_id" class="input-sm" />
								<a class="btn btn-info btn-sm" onclick="ajax_dchk();">아이디 중복확인</a>
							</div>
							<div class="col-md-3">
								비밀번호 : 
								<input type="password" name="comp_group_pass" id="comp_group_pass" class="input-sm" />
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								접속가능 여부 : <input type="checkbox" name="comp_group_use" id="comp_group_use" class="input-sm" value="Y" />								
							</div>
							<div class="col-md-6">
								<a class="btn btn-success btn-sm" onclick="comp_insert();">등록하기</a>								
							</div>
						</div>
						
						</form>
						
						
					</div>
				</div>
				
			</div>
		</div>
	</div>
</section>
		

<?=$jsinc ?>	

<script>
	$("#comp_group_id").keydown(function(e) {
		$('#d_chk').val('N');
	});

	function ajax_dchk()
	{
		var params = $("#comp_insert_form").serialize();
	    jQuery.ajax({
	        url: '/manage/ajax_mCompanyGroupDchk',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					if (json_result['post'] == 0)
					{
						$('#d_chk').val('Y');
					} else 
					{
						alert('아이디가 중복되었습니다. 다른 아이디를 입력하세요');
						return;
					}
				}
	        }
	    });
	}
	
	function comp_insert()
	{
	
		var dchk = $('#d_chk').val();
		if (dchk == 'Y')
		{
			var params = $("#comp_insert_form").serialize();
		    jQuery.ajax({
		        url: '/manage/ajax_mCompanyGroupInsertProc',
		        type: 'POST',
		        data:params,
		        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
		        dataType: 'text',
		        success: function (result) {
					json_result = $.parseJSON(result);
					if (json_result['result'] == 'true')
					{
						alert('ok');
						location.href="/manage/mCompanyGroup/";
						//location.reload();
					}
		        }
		    });
		} else {
			alert('아이디 중복확인이 되지 않았습니다.');
			return false;
		}
	
		
	}

</script>