<style>
</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">

		<div class="row">
			<div class="col-md-12">
				<form name='tm_bae_form' id='tm_bae_form' method='post' action='/dbmanage/mDbTmListD'>
				<select class="input-sm select2bs4"
					style="height: calc(1.90rem + 2px) !important;"
					name="tm_bae_id" id='tm_bae_id'>
						<option value="">선택하세요</option>
					<?php foreach ($tm_list as $tm): ?>
						<option value="<?=$tm['tm_id']?>" <?php if($tm['tm_id'] == $tm_bae_id) { ?> selected <?php } ?> ><?=$tm['tm_name']?></option>
                    <?php endforeach; ?>
                </select>
                <input type='text' name='tm_bae_count' id='tm_bae_count' value="<?=$tm_bae_count?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
                <a class='btn btn-sm btn-info' onclick="tm_bae_submit();">일괄배정하기</a>
                </form>
			</div>
		</div>


					

		<div class="row">
			<div class="col-md-12">
				<a class='btn btn-success btn-xs' onclick='f_sub();'>선택배정하기</a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
			<form name='sudong_gd_form' id='sudong_gd_form' method='post'>
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
						//$tm_bae_count = 3;
						//$tm_bae_id = 'testtm1';
						
						$listCount = count($db_list);
						foreach ( $db_list as $r ) :
						
						$disp_chk = '';
						$possible_tm_id = '';
						$chk_tm_id_array = array();
						if ($tm_bae_count > 0 && $tm_bae_id != '')
						{
							$chk_tm_id_array = array();
							$chk_tm_id_array = explode(',',$r['tm_id_sum']);
							
							$chk_yn = 'Y';
							
							if ( in_array($tm_bae_id,$chk_tm_id_array) )
							{
								$chk_yn = 'N';
							}
							
							if ($chk_yn == 'Y')
							{
								$disp_chk = 'checked';
								$possible_tm_id = $tm_bae_id;
								$tm_bae_count--;
							}
						}
						
						?>
						<tr>
							<td>
								<div class="icheck-primary d-inline col-md-3">
									<?=$r['idx'] ?>
									<input type="checkbox" id="compset_chk_<?=$r['idx']?>" name="compset_idx[]" value="<?=$r['idx']?>" class="compset_chk" <?=$disp_chk?> >
									<label for="compset_chk_<?=$r['idx']?>"></label>
								</div>
							
							</td>
							<td><?=$listCount?></td>
							<td><?=disp_date($r['uptime'])?></td>
							<td><?=$r['user_name'] ?></td>
							<td><?=disp_phone($r['user_phone'])?></td>
							<td><?=$r['comp_name']?></td>
							<td><?=$r['event_name']?></td>
							<td>
								<select class="input-sm select2bs4"
								style="height: calc(1.90rem + 2px) !important;"
								name="compset_tm[<?=$r['idx']?>]">
									<option value="">선택하세요</option>
								<?php foreach ($tm_list as $tm): ?>
									<option value="<?=$tm['tm_id']?>" <?php if($tm['tm_id'] == $possible_tm_id) { ?> selected <?php } ?> ><?=$tm['tm_name']?></option>
			                    <?php endforeach; ?>
			                  	</select>
							</td>
							<td onmouseover="showTooltip(<?=$r['idx']?>);" onmouseout="hideTooltip(<?=$r['idx']?>);" onmousemove="moveTooltip(event,<?=$r['idx']?>);">
								<a class='btn btn-sm btn-info' >내역보기</a>
								<div id='tool_<?=$r['idx']?>' style='position:fixed;width:600px;height:200px;font-size:14px;display:none;border:2px solid blue;background-color:#eee'>[상세내역]<br /><?=nl2br($r['db_content'])?></div>
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
	
	function gd_history(idx)
	{
		var params = "idx="+idx;
		jQuery.ajax({
	        url: '/dbmanage/ajax_gd_history',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert(json_result['alert_msg']);
				}
	        }
	    });
	}
	
	function f_sub()
	{
		var params = $("#sudong_gd_form").serialize();
	    jQuery.ajax({
	        url: '/dbmanage/ajax_mDbTmListD_proc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					console.log(json_result);
					alert('선택배정 ' + json_result['update_count'] + '건이 완료 되었습니다.');
					location.reload();
				}
	        }
	    });
	
	}
	
	function showTooltip(idx) 
	{
		$('#tool_'+idx).show();
	}

	function hideTooltip(idx) 
	{
		$('#tool_'+idx).hide();
	}

	function moveTooltip(event,idx) 
	{
		var x = event.clientX - 600;
		var y = event.clientY;
		
		$('#tool_'+idx).css({
			"top": y,
			"left": x,
			"position": "fixed"
		})
		
	}
	
	function tm_bae_submit()
	{
		var f = $('#tm_bae_form');
		
		if ( $('#tm_bae_id').val() == '' )
		{
			alert('일괄 배정할 티엠을 선택하세요');
			return;
		}
		
		if ( $('#tm_bae_count').val() == '' )
		{
			alert('일괄 배정할 숫자를 입력하세요');
			return;
		}
		
		f.submit();
	}
	
</script>