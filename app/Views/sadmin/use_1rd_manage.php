<style>
    
</style>

<?php
$sDef = SpoqDef();
?>
<!-- CARD HEADER [START] -->
<h1 class="page-header"><?php echo $title ?></h1>
<!-- CARD HEADER [END] -->
<!-- CARD HEADER [END] -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h3 class="panel-title">대분류 리스트</h3>
	</div>
	<!-- CARD HEADER [END] -->
	
	
	<!-- CARD BODY [START] -->
	<div class="panel-body">
		<div class="table-responsive">
			<!-- TABLE [START] -->
			<table class="table table-bordered table-hover table-striped">
				<thead>
					<tr>
						<th class="text-center" style='width:10%'>대분류 코드</th>
						<th class="text-center" style='width:10%'>대분류명</th>
						<th class="text-center" style='width:10%'>중분류 구성</th>
						<th class="text-center" style='width:10%'>락커 설정</th>
						<th class="text-center" style='width:10%'>사용설정</th>
						<th class="text-center" style='width:3%'>사용화면수</th>
						<th class="text-center" style='width:10%'>옵션</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($list_1rd_use as $index => $r) : 
					// 기본 배경색 가져오기 (Bootstrap의 table-striped가 적용될 수도 있음)
					$defaultBgColor = ($index % 2 == 0) ? "inherit" : "inherit"; // 'inherit'으로 부모 배경색 유지
					$backColor = ($r['USE_YN'] == "N") ? "rgba(247, 213, 217, 0.5)" : "transparent"; // N일 때 빨간색 반투명

				?>
					<tr style="background-color: <?php echo $defaultBgColor ?>; <?php echo ($r['USE_YN'] == "N") ? "background-image: linear-gradient($backColor, $backColor);" : ""; ?>">


						<td class="text-center"><?php echo $r['1RD_CATE_CD'] ?></td>
						<td class="text-center"><?php echo $r['CATE_NM'] ?></td>
						<td class="text-center"><?php echo $sDef['GRP_CATE_SET'][$r['GRP_CATE_SET']] ?></td>
						<td class="text-center"><?php echo $sDef['LOCKR_SET'][$r['LOCKR_SET']] ?></td>
						<td class="text-center">
							<?php if ($r['USE_YN'] == "Y") :?>
							사용함
							<?php else :?>
							사용안함
							<?php endif ;?>
						</td>
						<td class="text-center">
							<?php echo $r['counter']?>
						</td>
						<td class="text-center">
							<?php if ($r['USE_YN'] == "Y") :?>
								<button type="button" class="btn btn-success btn-xs ac-btn" onclick="use_1rd_change('<?php echo $r['1RD_CATE_CD'] ?>','Y');" disabled>사용함으로 설정</button>
								<?php if ($r['counter'] == 0) :?>
									<button type="button" class="btn btn-danger btn-xs ac-btn" onclick="use_1rd_change('<?php echo $r['1RD_CATE_CD'] ?>','N');">사용안함으로 설정</button>
								<?php else :?>
									<button type="button" class="btn btn-danger btn-xs ac-btn" onclick="use_1rd_change('<?php echo $r['1RD_CATE_CD'] ?>','N');" disabled>사용안함으로 설정</button>
								<?php endif ;?>
							<?php else :?>
								<button type="button" class="btn btn-success btn-xs ac-btn" onclick="use_1rd_change('<?php echo $r['1RD_CATE_CD'] ?>','Y');">사용함으로 설정</button>
								<button type="button" class="btn btn-danger btn-xs ac-btn" onclick="use_1rd_change('<?php echo $r['1RD_CATE_CD'] ?>','N');" disabled>사용안함으로 설정</button>
							<?php endif ;?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
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

function use_1rd_change(code1,use_yn)
{
	var params = "code1="+code1+"&use_yn="+use_yn;
    jQuery.ajax({
        url: '/smgrmain/ajax_use_1rd_change',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				console.log(json_result);
				location.reload();
			}
        }
    });
}

</script>