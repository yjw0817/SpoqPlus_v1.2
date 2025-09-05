<style>

</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			
				<div class="card card-primary">
					<div class="page-header">
						<h3 class="panel-title">디비 자동배정 시간 설정</h3>
					</div>
					<div class="panel-body">
						
							
							<form name='autoset_form' id='autoset_form'>
							<div class='row'>
							<?php
							for($i=0; $i<24 ;$i++) :
							$num = sprintf('%02d',$i);
							
							$add_chk = '';
							$add_chk2 = '';
							if ($get_auto_time_info['h_'.$num.'00'] == 'Y') :
								$add_chk = 'checked';
							endif;
							
							if ($get_auto_time_info['h_'.$num.'30'] == 'Y') :
								$add_chk2 = 'checked';
							endif;
							
							
							?>
							
							<div class="icheck-primary d-inline col-md-1">
								<input type="checkbox" id="h_<?=$num?>00" name="h_<?=$num?>00" value="Y" class="compset_chk" <?=$add_chk?> />
								<label for="h_<?=$num?>00">
									<?=$num?>시
								</label>
							</div>
							
							<div class="icheck-primary d-inline col-md-1">
								<input type="checkbox" id="h_<?=$num?>30" name="h_<?=$num?>30" value="Y" class="compset_chk" <?=$add_chk2?> />
								<label for="h_<?=$num?>30">
									<?=$num?>시30분
								</label>
							</div>
							
							<?php
							endfor;
							?>
							</div>
							</form>
									
						
						
						
						
					</div>
					
					<div class="card-footer">
						<div class='row'>
							<a class='btn btn-success btn-sm' onclick="auto_set_sub();">설정완료</a>
						</div>
					</div>
						
				</div>
				
			</div>
		</div>
	</div>
</section>
		

<?=$jsinc ?>	

<script>

	function auto_set_sub()
	{
		var params = $("#autoset_form").serialize();
	    jQuery.ajax({
	        url: '/manage/ajax_set_auto_time',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
	            json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('설정이 완료 되었습니다.');
				}
	        }
	    });
		
	}

</script>