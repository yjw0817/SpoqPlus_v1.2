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
						
						<div class="row">
							<div class="col-md-3">
								업체명 :  
								<input type="text" name="comp_name" id="comp_name" class="input-sm" />
							</div>
							<div class="col-md-3">
								구분 : 
								<select class="input-sm select2bs4"  name="comp_div" id="comp_div" style="height:calc(1.90rem + 2px) !important;">
								<option value="" >선택하세요</option>
								<?php foreach ($gcode_list as $g): ?>
			                    <option value="<?=$g['gcode']?>" ><?=$g['gname']?></option>
			                    <?php endforeach; ?>
			                  	</select>
							</div>
							<div class="col-md-3">
								아이디 : 
								<input type="text" name="comp_id" id="comp_id" class="input-sm" />
								<a class="btn btn-info btn-sm">아이디 중복확인</a>
							</div>
							<div class="col-md-3">
								비밀번호 : 
								<input type="password" name="comp_pass" id="comp_pass" class="input-sm" />
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								접속가능 여부 : <input type="checkbox" name="comp_use" id="comp_use" class="input-sm" value="Y" />								
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
	function comp_insert()
	{
	
		var params = $("#comp_insert_form").serialize();
	    jQuery.ajax({
	        url: '/manage/ajax_mCompanyInsertProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('ok');
					location.href="/manage/mCompanyModify/"+json_result['insert_id'];
					//location.reload();
				}
	        }
	    });
	}

</script>