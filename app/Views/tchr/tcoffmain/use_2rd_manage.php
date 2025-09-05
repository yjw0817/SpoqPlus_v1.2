<style>
</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<!-- CARD HEADER [START] -->
<h1 class="page-header"><?php echo $title ?></h1>
<!-- CARD HEADER [END] -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">이용권 리스트</h4>
	</div>
	<!-- CARD HEADER [END] -->
	
	
	<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		<table class="table table-bordered table-hover table-striped col-md-12">
			<thead class="text-center">
				<tr>
					<th style='width:150px'>대분류 코드</th>
					<th style='width:150px'>대분류명</th>
					<th style='width:150px'>중분류 코드</th>
					<th style='width:150px'>중분류 명</th>
					<th style='width:150px'>그룹적용 설정</th>
					<th style='width:150px'>락커설정</th>
					<th style='width:150px'>수업구분</th>
					<th style='width:150px'>사용여부</th>
					<th>옵션</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($list_2rd_use as $r) :
					$backColor = "";
					if ($r['USE_YN'] =="N") $backColor = "#f7d5d9";
			?>
				<tr class="" style="background-color: <?php echo $backColor ?>;">
					<td class='text-center'><?php echo $r['1RD_CATE_CD'] ?></td>
					<td class='text-center'><?php echo $r['CATE_NM1'] ?></td>
					<td class='text-center'><?php echo $r['2RD_CATE_CD'] ?></td>
					<td class='text-center'><?php echo $r['CATE_NM'] ?></td>
					<td class='text-center'><?php echo $sDef['GRP_CATE_SET'][$r['GRP_CATE_SET']] ?></td>
					<td class='text-center'><?php echo $sDef['LOCKR_KND'][$r['LOCKR_KND']] ?></td>
					<td class='text-center'><?php echo $sDef['CLAS_DV'][$r['CLAS_DV']] ?></td>
					<td class='text-center'><?php echo $r['USE_YN'] ?></td>
					<td class='text-center'>
						<?php if ($r['USE_YN'] == "Y") :?>
							<button type="button" class="btn btn-success btn-xs ac-btn" onclick="use_2rd_change('<?php echo $r['2RD_CATE_CD'] ?>','Y');" disabled>사용함으로 설정</button>
							<button type="button" class="btn btn-danger btn-xs ac-btn" onclick="use_2rd_change('<?php echo $r['2RD_CATE_CD'] ?>','N');">사용안함으로 설정</button>
						<?php else :?>
							<button type="button" class="btn btn-success btn-xs ac-btn" onclick="use_2rd_change('<?php echo $r['2RD_CATE_CD'] ?>','Y');">사용함으로 설정</button>
							<button type="button" class="btn btn-danger btn-xs ac-btn" onclick="use_2rd_change('<?php echo $r['2RD_CATE_CD'] ?>','N');" disabled>사용안함으로 설정</button>
						<?php endif ;?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<!-- TABLE [END] -->
	</div>
	<!-- CARD BODY [END] -->
	<!-- CARD FOOTER [START] -->
	<div class="card-footer clearfix">
		
	</div>
				<!-- CARD FOOTER [END] -->
		
</div>
			
<!-- ############################## MODAL [ SATRT ] #################################### -->
	
<!-- ############################## MODAL [ END ] ###################################### -->
	
<?=$jsinc ?>

<script>

function use_2rd_change(code2,use_yn)
{
	var params = "code2="+code2+"&use_yn="+use_yn;
    jQuery.ajax({
        url: '/tbcoffmain/ajax_use_2rd_change',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
        	if ( result.substr(0,8) == '<script>' )
        	{
        		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
        		location.href='/tlogin';
        		return;
        	}
        	
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				location.reload();
			} else 
			{
				alertToast('error',json_result['msg']);
			}
        }
    }).done((res) => {
    	// 통신 성공시
    	console.log('통신성공');
    }).fail((error) => {
    	// 통신 실패시
    	console.log('통신실패');
    	alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
		location.href='/tlogin';
		return;
    });
}

</script>