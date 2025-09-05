<style>
</style>
<?php
$sDef = SpoqDef();
?>

<style>
</style>
<?php
$sDef = SpoqDef();
?>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10">
				<!-- Main content -->
				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">약관 생성</h4>
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">



						<!-- FORM [START] -->
						<form id="terms_insert_form" name="terms_insert_form" method="post" action="/adminmain/terms_insert_proc">


							<div class="input-group input-group-sm mb-1">
								<span class="input-group-text" style='width:110px'>약관 코드 <span style="color:red">*</span></span>
								<input type="text" class="form-control" name="terms_knd_cd" id="terms_knd_cd" required>
								
							<!-- 
							</div>
							<div class="input-group input-group-sm mb-1">
							-->
								<span class="input-group-append">
									<span class="input-group-text" style='width:110px'>약관 회차</span>
								</span>
								<input type="text" class="form-control" name="terms_round" id="terms_round" readonly value="1">
							
							</div>
							<div class="input-group input-group-sm mb-1">
								<span class="input-group-append">
									<span class="input-group-text" style='width:110px'>약관 개정일</span>
								</span>
								<input type="text" class="form-control" name="terms_date" id="terms_date" value="<?php echo date('Y-m-d')?>">
							<!-- 	
							</div>
							<div class="input-group input-group-sm mb-1">
							-->
								<span class="input-group-append">
									<span class="input-group-text" style='width:110px'>약관 사용여부</span>
								</span>
								
								<div class="form-control">
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
									<span class="input-group-text" style='width:110px'>약관 제목</span>
								</span>
								<input type="text" class="form-control" name="terms_title" id="terms_title">
							</div>

							<div class="input-group input-group-sm mb-1">
								<textarea class="form-control" name="terms_conts" id="terms_conts" rows="16"></textarea>
							</div>

							
							<span style='font-size:0.9rem;color:red'>약관의 수정은 불가능 합니다. 수정하시려면 약관을 회차를 개정 하셔야 합니다.</span>
						</form>
						<!-- FORM [END] -->

						</div>
						<!-- CARD BODY [END] -->
						<!-- CARD FOOTER [START] -->
						<div class="card-footer clearfix">
						<!-- BUTTON [START] -->
						<ul class="pagination pagination-sm m-0 float-right">
							<li class="ac-btn">
								<button type="button" class="btn btn-block btn-success btn-sm" onclick="terms_submit();">기본약관 생성하기</button>
							</li>
						</ul>

						<!-- BUTTON [END] -->
						<!-- PAGZING [START] -->
						<!-- PAGZING [END] -->
						</div>
						<!-- CARD FOOTER [END] -->
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
	const termsCode = $('#terms_knd_cd').val().trim();

    if (termsCode === '') {
        alert('약관 코드를 입력해 주세요.');
        $('#terms_knd_cd').focus();
        return false;
    }
	$('#terms_insert_form').submit();
}

</script>