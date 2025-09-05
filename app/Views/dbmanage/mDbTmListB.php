<style>
</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">

		<div class="row">
			<div class="col-md-12">
				
				부재디비 상태로 설정한지 <input id='bu_days' style="text-align:center" type="text" size="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="<?=$bu_day_auto_set['bu_days']?>" />일
				동안 부재디비 상태로 그대로 있는 디비를 영구부재로 설정합니다.
				<a class='btn btn-sm btn-success' onclick="bu_day_auto_set_proc();">설정완료</a>
			</div>
		</div>

		
		<div class="row">
				<?php foreach ($db_list as $r) : ?>
				<div class="col-md-1" style='text-align:center; margin:3px; padding:3px; border:solid 1px red' >
					<?=disp_date($r['bu_date'])?> : <?=$r['counter'] ?> 건 <br />
					<a class='btn btn-xs btn-warning' onclick="bu_proc('<?=$r['bu_date']?>','a5');">예약반려</a>
					<a class='btn btn-xs btn-info' onclick="bu_proc('<?=$r['bu_date']?>','a15');">영구부재</a>
				</div>
				<?php endforeach; ?>

		</div>

	</div>
	
	
	
</section>


<?=$jsinc ?>

<script>
	
	function bu_day_auto_set_proc()
	{
		var bu_days = $('#bu_days').val();
		
		if (bu_days == "")
		{
			alert("일자가 비었습니다. 일자를 입력하세요");

		} else 
		{
			var params = "bu_days="+ bu_days ;
			jQuery.ajax({
		        url: '/dbmanage/ajax_bu_day_auto_set_proc',
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
	}
	
	function bu_proc(bu_date,scode)
	{
		var msg = "정말로 영구부재로 처리 하시겠습니까?";
		if (scode == "a5") msg = "정말로 예약반려로 처리 하시겠습니까?";
	
		if ( confirm(msg) )
		{
			var params = "bu_date="+bu_date+"&scode="+scode;
			jQuery.ajax({
		        url: '/dbmanage/ajax_bu_proc',
		        type: 'POST',
		        data:params,
		        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
		        dataType: 'text',
		        success: function (result) {
					json_result = $.parseJSON(result);
					if (json_result['result'] == 'true')
					{
						console.log( json_result );
						alert('처리되었습니다.');
						
						location.reload();
					}
		        }
		    });
		} else 
		{

		}
	}
	
	
	
</script>