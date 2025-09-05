<style>
</style>
<?php
$sDef = SpoqDef();
?>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			<!-- Main content -->
			<div class="panel panel-inverse">
				<div class="panel-heading">
					<h4 class="panel-title">약관 개정</h4>
				</div>
				<!-- CARD HEADER [END] -->
				
				<!-- CARD BODY [START] -->
				<div class="panel-body">

					
					<!-- FORM [START] -->
					<form id="terms_insert_form" name="terms_insert_form" method="post" action="/adminmain/terms_insert_proc">
					
					
					<div class="input-group input-group-sm mb-1">
						<span class="input-group-append">
							<span class="input-group-text2" style='width:110px'>약관 코드</span>
						</span>
						<input type="text" class="form-control2" name="terms_knd_cd" id="terms_knd_cd" readonly value="<?php echo $terms_info['TERMS_KND_CD']?>" >
						
					<!-- 
					</div>
					<div class="input-group input-group-sm mb-1">
						-->
						<span class="input-group-append">
							<span class="input-group-text2" style='width:110px'>약관 회차</span>
						</span>
						<input type="text" class="form-control2" name="terms_round" id="terms_round" readonly value="<?php echo ($terms_info['TERMS_ROUND'] + 1)?>">
						
					</div>
					<div class="input-group input-group-sm mb-1">
						<span class="input-group-append">
							<span class="input-group-text2" style='width:110px'>약관 개정일</span>
						</span>
						<input type="text" class="form-control2" name="terms_date" id="terms_date" value="<?php echo date('Y-m-d')?>">
					<!-- 	
					</div>
					<div class="input-group input-group-sm mb-1">
						-->
						<span class="input-group-append">
							<span class="input-group-text2" style='width:110px'>약관 사용여부</span>
						</span>
						
						<div class="form-control2">
							<div class="icheck-primary d-inline">
								<input type="radio" id="terms_use_y" name="terms_use_yn" value="Y" checked>
								<label for="terms_use_y">
									<small>사용함</small>
								</label>
							</div>
							<div class="icheck-primary d-inline">
								<input type="radio" id="terms_use_n" name="terms_use_yn" value="N">
								<label for="terms_use_n">
									<small>사용안함</small>
								</label>
							</div>
						</div>
					</div>
					<div class="input-group input-group-sm mb-1">
						<span class="input-group-append">
							<span class="input-group-text2" style='width:110px'>약관 제목</span>
						</span>
						<input type="text" class="form-control3" name="terms_title" id="terms_title" value="<?php echo $terms_info['TERMS_TITLE']?>" >
					</div>
					
					<div class="input-group input-group-sm mb-1">
						<textarea class="form-control3" name="terms_conts" id="terms_conts" rows="16"><?php echo $terms_info['TERMS_CONTS']?></textarea>
					</div>
					
					<span style='font-size: 0.9rem; font-weight: 600;margin-top: 20px;'>[ 주의 설명 ]</span>
					<span style='font-size:0.8rem;color:red'>- 약관을 개정하면 하고 사용함 설정이라면 이전 회사의 사용여부는 자동으로 사용안함으로 설정이 됩니다.</span>
					</form>
					<!-- FORM [END] -->
					
						<div class="card-footer clearfix">
					<!-- BUTTON [START] -->
					<ul class="pagination pagination-sm m-0 float-right">
						<li class="ac-btn">
							<button type="button" class="btn btn-block btn-success btn-sm" onclick="terms_submit();">약관 개정하기</button>
						</li>
					</ul>
					
					<!-- BUTTON [END] -->
					<!-- PAGZING [START] -->
					<!-- PAGZING [END] -->
				</div>
				</div>
				<!-- CARD BODY [END] -->
				<!-- CARD FOOTER [START] -->

				<!-- CARD FOOTER [END] -->
						
			</div>
			</div>
		</div>
	</div>

<!-- ============================= [ modal-default START ] ======================================= -->	

<!-- ============================= [ modal-default END ] ======================================= -->	
	
</section>
			
<?=$jsinc ?>

<script>

$(function () {
    $('.select2').select2();
})

$('#terms_date').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

function terms_submit()
{
	$('#terms_insert_form').submit();
}

</script>