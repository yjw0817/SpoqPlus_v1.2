<style>
</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">

				<div class="card card-primary">
					<div class="page-header">
						<h3 class="panel-title">이벤트 선택</h3>
					</div>
					<div class="panel-body">
						
						
						<div class="row">
							<div class="col-md-3" id='event_info1'>
							</div>
							<div class="col-md-1">
							 --->
							</div>
							<div class="col-md-3" id='event_info2'>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								
								<select class="input-sm select2bs4" id="event_move1"
								style="height: calc(1.90rem + 2px) !important;"
								onchange="event_move_info1(this);">
									<option value="">선택하세요</option>
									<?php foreach ($event_list as $e): ?>
									<option value="<?=$e['idx']?>"><?=$e['event_name']?></option>
				                    <?php endforeach; ?>
			                  	</select>
								
							</div>
							<div class="col-md-1">
							 --->
							</div>
							<div class="col-md-3">
								<select class="input-sm select2bs4" id="event_move2"
								style="height: calc(1.90rem + 2px) !important;"
								onchange="event_move_info2(this);">
									<option value="">선택하세요</option>
									<?php foreach ($event_list as $e): ?>
									<option value="<?=$e['idx']?>"><?=$e['event_name']?></option>
				                    <?php endforeach; ?>
			                  	</select>
							</div>
							<div class="col-md-2">
							 	<a class='btn btn-success btn-sm' onclick="f_event_move_submit();">이관하기</a>
							</div>
						</div>
					
					
					</div>
				</div>

			</div>
		</div>

	</div>
	
</section>

<?=$jsinc ?>

<script>
$(document).ready(function(){
	$("#event_move1").select2();
	$("#event_move2").select2();
});

function f_event_move_submit()
{
	if ( confirm('정말로 이관하시겠습니까?') )
	{
		var e1_idx = $('#event_move1').val();
		var e2_idx = $('#event_move2').val();
		
		var params = "e1_idx="+e1_idx+"&e2_idx="+e2_idx;
		jQuery.ajax({
	        url: '/dbmanage/ajax_eventMoveProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					//console.log( json_result['post'] );
					alert("이관이 완료되었습니다.");
				}
	        }
	    });
	}
}
function event_move_info1(t)
{
	
	var event_idx = t.value;
	var params = "event_idx="+event_idx;
	
	if (event_idx)
	{
		jQuery.ajax({
	        url: '/dbmanage/ajax_eventMoveInfo',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					//console.log( json_result['event_result'] );
					$('#event_info1').text( json_result['event_result'][0]['comp_name'] + '  [ ' + json_result['event_result'][0]['event_name'] + ' ]' );
					
				}
	        }
	    });
	} else 
	{
		$('#event_info1').text('');
	}
	
}

function event_move_info2(t)
{
	
	var event_idx = t.value;
	var params = "event_idx="+event_idx;
	
	if (event_idx)
	{
		jQuery.ajax({
	        url: '/dbmanage/ajax_eventMoveInfo',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					//console.log( json_result['event_result'] );
					$('#event_info2').text( json_result['event_result'][0]['comp_name'] + '  [ ' + json_result['event_result'][0]['event_name'] + ' ]' );
					
				}
	        }
	    });
	} else 
	{
		$('#event_info2').text('');
	}
	
}




</script>