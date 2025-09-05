<style>
</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		
		<div class="row">
			<div class="col-md-12">
				TM 배정 (시작/종료) 관리
			</div>
			<div class="col-md-12">
				<div class="row">
				<?php foreach($tm_list AS $t) : 
						$bae_yn[$t['tm_id']] = $t['bae_yn'];
				
						if ($bae_yn[$t['tm_id']] == 'N')
						{
							$bae_N = '';
							$bae_Y = 'display:none';
						} else 
						{
							$bae_N = 'display:none';
							$bae_Y = '';
						}
				?>
					<div class="col-md-1">
						<a class='btn btn-info btn-sm'><?=$t['tm_name']?></a>
						<a class='btn btn-success btn-sm' id="<?=$t['tm_id']?>_N" style="<?=$bae_N?>" onclick="bae_YN_func('Y','<?=$t['tm_id']?>')">배정시작</a>
						<a class='btn btn-danger btn-sm' id="<?=$t['tm_id']?>_Y" style="<?=$bae_Y?>" onclick="bae_YN_func('N','<?=$t['tm_id']?>')">배정종료</a>
					</div>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
		
		
		<div class="row" style='margin-top: 20px'>
			<div class="col-md-12">
				<a class='btn btn-success btn-xs' onclick='f_sub();'>선택배정하기</a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
			<form name='sudong_form' id='sudong_form' method='post'>
				<table class="table table-bordered table-hover table-striped table-hover col-md-12">
					<thead>
						<tr>
							<th>
								<div class="icheck-primary d-inline col-md-3">
									<input type="checkbox" id="compset_chk_all" name="" class="compset_chk_all">
									<label for="compset_chk_all">전체선택</label>
								</div>
							</th>
							<th>번호</th>
							<th>신청일시</th>
							<th>이름</th>
							<th>전화번호</th>
							<th>업체</th>
							<th>신청이벤트</th>
							<th>TM배정</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$listCount = count($db_list);
						foreach ( $db_list as $r ) :
						?>
						<tr>
							<td>
								<div class="icheck-primary d-inline col-md-3">
									<input type="checkbox" id="compset_chk_<?=$r['idx']?>" name="compset_idx[]" value="<?=$r['idx']?>" class="compset_chk">
									<label for="compset_chk_<?=$r['idx']?>"></label>
								</div>
							
							</td>
							<td><?=$listCount?></td>
							<td><?=disp_date($r['uptime'])?></td>
							<td><?=$r['user_name'] ?></td>
							<td><?=$r['user_phone']?></td>
							<td><?=$r['comp_name']?></td>
							<td><?=$r['event_name']?></td>
							<td>
								<select class="input-sm select2bs4" name='compset_tm[<?=$r['idx']?>]'
								style="height: calc(1.90rem + 2px) !important;">
									<option value="">선택하세요</option>
									<?php if ( isset($set_comp_bae_list[$r['company_idx']])): ?>
									<?php for($i=0; $i< count($set_comp_bae_list[$r['company_idx']]['tm_list']) ; $i++) :?>
										<?php if ($set_comp_bae_list[$r['company_idx']]['tm_list'][$i] == $r['set_tm_id']) : ?>
											<option value="<?=$set_comp_bae_list[$r['company_idx']]['tm_list'][$i]?>" selected><?=$set_comp_bae_list[$r['company_idx']]['tm_list'][$i]?></option>
										<?php else: ?>
											<option value="<?=$set_comp_bae_list[$r['company_idx']]['tm_list'][$i]?>"><?=$set_comp_bae_list[$r['company_idx']]['tm_list'][$i]?></option>
										<?php endif; ?>
									<?php endfor; ?>
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
			</form>
			</div>

		</div>

	</div>
	
	
	
</section>


<?=$jsinc ?>

<script>

	$(document).ready(function() {
		$("#compset_chk_all").click(function() {
			if($("#compset_chk_all").is(":checked")) $(".compset_chk").prop("checked", true);
			else $(".compset_chk").prop("checked", false);
		});
	});
	
	function f_sub()
	{
		var params = $("#sudong_form").serialize();
	    jQuery.ajax({
	        url: '/dbmanage/ajax_mDbTmList_proc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('선택배정 ' + json_result['update_count'] + '건이 완료 되었습니다.');
					location.reload();
				}
	        }
	    });
	
	}
	
	function bae_YN_func(flag,tm_id)
	{
	
		var params = "flag="+flag+"&tm_id="+tm_id;
	    jQuery.ajax({
	        url: '/manage/ajax_tm_autoset',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					if (json_result['flag'] == 'Y')
					{
						$('#'+ json_result['tm_id'] +'_Y').show();
						$('#'+ json_result['tm_id'] +'_N').hide();
					} else 
					{
						$('#'+ json_result['tm_id'] +'_Y').hide();
						$('#'+ json_result['tm_id'] +'_N').show();
					}
				}
	        }
	    });
		
	}
	
</script>