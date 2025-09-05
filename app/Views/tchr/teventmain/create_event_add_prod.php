<style>
</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
	
	<form name="event_create_form" id="event_create_form" method="post" action="/teventmain/create_event_proc">
		<input type="hidden" name="clas_dv" id='clas_dv' value="<?php echo $einfo['clas_dv']?>" />
		<input type="hidden" name="sell_event_sno_base" value="<?php echo $einfo['sell_event_sno']?>" />
		<input type="hidden" name="event_step" value="<?php echo $einfo['event_step']?>" />
		<input type="hidden" name="event_depth" value="2" />
		<input type="hidden" name="event_acc_ref_sno" value="" />
		<div class="row">
			<div class="col-md-9">
				<div class="card card-primary">
					<!-- CARD HEADER [START] -->
					<div class="page-header">
						<h5 class="panel-title" style="font-size: 1.0rem !important;">분류 선택</h5>
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
					
						<div class="row">
							<div class="col-md-6">
								<!-- [대분류 선택] [START] -->
								<div class="input-group input-group-sm mb-1">
                                	<span class="input-group-append">
                                		<span class="input-group-text" style='width:150px'>대분류</span>
                                	</span>
                                	<input type="text" class="form-control" style='width:140px; margin-left:5px' value="<?php echo $einfo['1rd_cate_nm']?>" disabled>
                                	<input type="hidden" name="1rd_cate_cd" value="<?php echo $einfo['1rd_cate_cd']?>" />
                                </div>
								<!-- [대분류 선택] [END] -->
							</div>
							<div class="col-md-6">
								<!-- [중분류 선택] [START] -->
								<div class="input-group input-group-sm mb-1">
                                	<span class="input-group-append">
                                		<span class="input-group-text" style='width:150px'>중분류 선택</span>
                                	</span>
                                	<input type="text" class="form-control" style='width:140px; margin-left:5px' value="<?php echo $einfo['2rd_cate_nm']?>" disabled>
                                	<input type="hidden" name="2rd_cate_cd" value="<?php echo $einfo['2rd_cate_cd']?>" />
                                </div>
								<!-- [중분류 선택] [END] -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		
		
		<div class="row">
			<div class="col-md-9">
				<div class="card">
					<!-- CARD BODY [START] -->
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
    						    <!-- [판매금액] [START] -->
    							<div class="input-group input-group-sm mb-1">
                                	<span class="input-group-append">
                                		<span class="input-group-text" style='width:150px'>판매 상품명</span>
                                	</span>
                                	<input type="text" class="form-control" style='width:140px; margin-left:5px' value="<?php echo $einfo['sell_event_nm']?>" disabled>
                                	<input type="hidden" name="sell_event_nm" value="<?php echo $einfo['sell_event_nm']?>" />
                                </div>
    							<!-- [판매금액] [END] -->
    						</div>
    					</div>
					
						<div class="alert alert-info alert-dismissible">
		                    <button type="button" class="close"  data-bs-dismiss="alert" aria-hidden="true">&times;</button>
		                    <!-- <p><i class="icon fas fa-info"></i> 주의!</p> -->
		                    <small>대표 이용기간을 설정하세요. 동일 상품의 다른 이용기간 상품 생성시는 상품 리스트에서 [+이용기간] 기능을 사용하세요</small>
		                </div>
		                <?php 
						if($einfo['clas_dv'] == "21" || $einfo['clas_dv'] == '22') : 
							$disp_prod = "display:none";
							$disp_prod2 = "";
							$pt_chk = "checked";
							$prod_chk = "";
						else :
							$disp_prod = "";
							$disp_prod2 = "display:none";
							$pt_chk = "";
							$prod_chk = "checked";
						endif;
						?>
						<div class="row">
							<div class="col-md-4"  style="<?php echo $disp_prod ?>">
								<!-- [이용기간] [START] -->
								<div class="input-group input-group-sm mb-1">
                                	<span class="input-group-append">
                                		<span class="input-group-text" style='width:150px' id="title_use_prod">이용기간</span>
                                	</span>
                                	<input type="text" class="" style='width:80px; margin-left:5px' placeholder="" name="use_prod" id="use_prod" autocomplete='off' maxlength="3">
                                	
                                	<div style='margin-top:4px;margin-left:5px;'>
                                    	<div class="icheck-primary d-inline">
                                            <input type="radio" name="use_unit" id="use_unit_m" value="M" checked>
                                            <label for="use_unit_m">
                                            	<small>개월</small>
                                            </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="use_unit" id="use_unit_d" value="D">
                                            <label for="use_unit_d">
                                            	<small>일</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
								<!-- [이용기간] [END] -->
							</div>
							<?php if($einfo['clas_dv'] == "21" || $einfo['clas_dv'] == '22') : ?>
							<div class="col-md-4" id="disp_clas_cnt">
								<!-- [수업횟수] [START] -->
								<div class="input-group input-group-sm mb-1">
                                	<span class="input-group-append">
                                		<span class="input-group-text" style='width:150px'>수업횟수</span>
                                	</span>
                                	<input type="text" class="" style='width:80px; margin-left:5px' placeholder="" name="clas_cnt" id="clas_cnt" autocomplete='off' maxlength="3">
                                </div>
								<!-- [수업횟수] [END] -->
							</div>
							<?php else:?>
							<input type="hidden" class="" style='width:80px; margin-left:5px' placeholder="" name="clas_cnt" id="clas_cnt" autocomplete='off' maxlength="3">
							<?php endif;?>
							<div class="col-md-4">
								<!-- [판매금액] [START] -->
								<div class="input-group input-group-sm mb-1">
                                	<span class="input-group-append">
                                		<span class="input-group-text" style='width:150px'>판매금액</span>
                                	</span>
                                	<input type="text" class="" style='width:140px; margin-left:5px' placeholder="" name="sell_amt" id="sell_amt" autocomplete='off'>
                                </div>
								<!-- [판매금액] [END] -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-9">
				<div class="card">
					<!-- CARD HEADER [START] -->
					<div class="page-header">
						<!-- [휴회가능 선택] [START] -->
						<div class="input-group input-group-sm">
                        	<span class="input-group-append">
                        	</span>
                        	<div style='margin-top:4px;margin-left:5px;'>
                            	<?php if($einfo['clas_dv'] == "21" || $einfo['clas_dv'] == '22') : ?> 
                            	<!-- 
                            	<div class="icheck-primary d-inline">
                                    <input type="radio" id="domcy_poss_event_yn_y" name="domcy_poss_event_yn" value="Y" checked onclick="domcy_poss('Y');">
                                    <label for="domcy_poss_event_yn_y">
                                    	<small>휴회가능</small>
                                    </label>
                                </div>
                                 -->
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="domcy_poss_event_yn_n" name="domcy_poss_event_yn" value="N" checked onclick="domcy_poss('N');">
                                    <label for="domcy_poss_event_yn_n">
                                    	<small>휴회불가능</small>
                                    </label>
                                </div>
                                <?php else : ?>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="domcy_poss_event_yn_y" name="domcy_poss_event_yn" value="Y" checked onclick="domcy_poss('Y');">
                                    <label for="domcy_poss_event_yn_y">
                                    	<small>휴회가능</small>
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="domcy_poss_event_yn_n" name="domcy_poss_event_yn" value="N" onclick="domcy_poss('N');">
                                    <label for="domcy_poss_event_yn_n">
                                    	<small>휴회불가능</small>
                                    </label>
                                </div>
                                <?php endif;?>
                                
                            </div>
                        </div>
						<!-- [휴회가능 선택] [END] -->
						
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body" id="disp_domcy"  style="<?php echo $disp_prod ?>" >
					
						<div class="row">
							<div class="col-md-4">
								<!-- [휴회횟수] [START] -->
								<div class="input-group input-group-sm mb-1">
                                	<span class="input-group-append">
                                		<span class="input-group-text" style='width:150px'>휴회횟수 <?php $disp_prod?></span>
                                	</span>
                                	<input type="text" class="" style='width:80px; margin-left:5px' placeholder="" name="domcy_cnt" id="domcy_cnt" autocomplete='off' maxlength="3">
                                </div>
								<!-- [휴회횟수] [END] -->
							</div>
							<div class="col-md-4">
								<!-- [휴회일수] [START] -->
								<div class="input-group input-group-sm mb-1">
                                	<span class="input-group-append">
                                		<span class="input-group-text" style='width:150px'>휴회일수</span>
                                	</span>
                                	<input type="text" class="" style='width:80px; margin-left:5px' placeholder="" name="domcy_day" id="domcy_day" autocomplete='off' maxlength="3">
                                </div>
								<!-- [휴회일수] [END] -->
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-9">
				<div class="card card-warning">
					<!-- CARD HEADER [START] -->
					<div class="page-header">
						<!-- [출입제한설정] [START] -->
						<div class="input-group input-group-sm">
                        	<span class="input-group-append">
                        		<h5 class="panel-title" style="font-size: 1.0rem !important;">출입제한설정</h5>
                        	</span>
                        </div>
						<!-- [출입제한설정] [END] -->
						
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
					
						<div class="alert alert-info alert-dismissible">
		                    <button type="button" class="close"  data-bs-dismiss="alert" aria-hidden="true">&times;</button>
		                    <!-- <p><i class="icon fas fa-info"></i> 주의!</p> -->
		                    <small>1일1회입장 출입제한 상품은 기본 상품 (입장 무제한, 매일) 생성후 상품리스트에서 [+출입제한] 을 클릭하여 생성이 가능합니다. </small>
		                </div>
					
						<div class="row">
							<div class="col-md-6">
								<!-- [출입제한구분] [START] -->
								<div class="input-group input-group-sm mb-1">
                                	<span class="input-group-append">
                                		<span class="input-group-text" style='width:150px'>출입제한구분</span>
                                	</span>
                                	
                                	
                                	<div style='margin-top:4px;margin-left:5px;<?php echo $disp_prod ?>'>
                                    	<div class="icheck-primary d-inline">
                                            <input type="radio" id="acc_rtrct_dv_00" name="acc_rtrct_dv" value="00" <?php echo $prod_chk ?>>
                                            <label for="acc_rtrct_dv_00">
                                            	<small>입장무제한</small>
                                            </label>
                                        </div>
                                        <!-- 
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="acc_rtrct_dv_01" name="acc_rtrct_dv" value="01">
                                            <label for="acc_rtrct_dv_01">
                                            	<small>1일1회입장</small>
                                            </label>
                                        </div>
                                         -->
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="acc_rtrct_dv_99" name="acc_rtrct_dv" value="99">
                                            <label for="acc_rtrct_dv_99">
                                            	<small>입장불가</small>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div style='margin-top:4px;margin-left:5px;<?php echo $disp_prod2 ?>' class="pt_yes">
                                    	<div class="icheck-primary d-inline" id="disp_rtrct_dv_50">
                                            <input type="radio" id="acc_rtrct_dv_50" name="acc_rtrct_dv" value="50" <?php echo $pt_chk ?>>
                                            <label for="acc_rtrct_dv_50">
                                            	<small>수업입장</small>
                                            </label>
                                        </div>
                                        <div class="icheck-primary d-inline" id="disp_rtrct_dv_98">
                                            <input type="radio" id="acc_rtrct_dv_98" name="acc_rtrct_dv" value="99">
                                            <label for="acc_rtrct_dv_98">
                                            	<small>입장불가</small>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    
                                </div>
								<!-- [출입제한구분] [END] -->
							</div>
							<div class="col-md-6">
								<!-- [출입제한방법] [START] -->
								<div class="input-group input-group-sm mb-1">
                                	<span class="input-group-append">
                                		<span class="input-group-text" style='width:150px'>출입제한방법</span>
                                	</span>
                                	<div style='margin-top:4px;margin-left:5px;<?php echo $disp_prod ?>'>
                                    	<div class="icheck-primary d-inline">
                                            <input type="radio" id="acc_rtrct_mthd_00" name="acc_rtrct_mthd" value="00" <?php echo $prod_chk ?> readonly>
                                            <label for="acc_rtrct_mthd_00">
                                            	<small>매일</small>
                                            </label>
                                        </div>
                                        <!-- 
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="acc_rtrct_mthd_05" name="acc_rtrct_mthd" value="05">
                                            <label for="acc_rtrct_mthd_05">
                                            	<small>평일</small>
                                            </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="acc_rtrct_mthd_02" name="acc_rtrct_mthd" value="02">
                                            <label for="acc_rtrct_mthd_02">
                                            	<small>주말</small>
                                            </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="acc_rtrct_mthd_70" name="acc_rtrct_mthd" value="70">
                                            <label for="acc_rtrct_mthd_70">
                                            	<small>할인시간대</small>
                                            </label>
                                        </div>
                                         -->
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="acc_rtrct_mthd_99" name="acc_rtrct_mthd" value="99" disabled >
                                            <label for="acc_rtrct_mthd_99">
                                            	<small>입장불가</small>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div style='margin-top:4px;margin-left:5px;<?php echo $disp_prod2 ?>' class="pt_yes">
                                    	<div class="icheck-primary d-inline" id="disp_rtrct_mthd_00">
                                            <input type="radio" id="acc_rtrct_mthd_50" name="acc_rtrct_mthd" value="50" <?php echo $pt_chk ?>>
                                            <label for="acc_rtrct_mthd_50">
                                            	<small>수업입장</small>
                                            </label>
                                        </div>
                                        <div class="icheck-primary d-inline" id="disp_rtrct_mthd_98">
                                            <input type="radio" id="acc_rtrct_mthd_98" name="acc_rtrct_mthd" value="99" disabled >
                                            <label for="acc_rtrct_mthd_98">
                                            	<small>입장불가</small>
                                            </label>
                                        </div>
                                    </div>
                                    
                                </div>
								<!-- [출입제한방법] [END] -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-9">
				<div class="card card-gray">
					<!-- CARD HEADER [START] -->
					<div class="page-header">
						<!-- [기타정보] [START] -->
						<div class="input-group input-group-sm">
                        	<span class="input-group-append">
                        		<h5 class="panel-title" style="font-size: 1.0rem !important;">기타정보</h5>
                        	</span>
                        </div>
						<!-- [기타정보] [END] -->
						
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
					
						<div class="row">
							<div class="col-md-4">
								<!-- [판매기간] [START] -->
								<div class="input-group input-group-sm mb-1">
                                	<span class="input-group-append">
                                		<span class="input-group-text" style='width:150px'>판매기간</span>
                                	</span>
                                	<input type="text" class="" style='width:80px; margin-left:5px' placeholder="" name="sell_s_date" id="sell_s_date" autocomplete='off'>&nbsp; ~
                                	<input type="text" class="" style='width:80px; margin-left:5px' placeholder="" name="sell_e_date" id="sell_e_date" autocomplete='off'>
                                </div>
								<!-- [휴회횟수] [END] -->
							</div>
							<div class="col-md-4">
								<!-- [공개여부] [START] -->
								<div class="input-group input-group-sm mb-1">
                                	<span class="input-group-append">
                                		<span class="input-group-text" style='width:150px'>공개여부</span>
                                	</span>
                                	<div style='margin-top:4px;margin-left:5px;'>
                                    	<div class="icheck-primary d-inline">
                                            <input type="radio" id="mem_disp_yn_y" name="mem_disp_yn" value="Y" checked>
                                            <label for="mem_disp_yn_y">
                                            	<small>공개</small>
                                            </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="mem_disp_yn_n" name="mem_disp_yn" value="N">
                                            <label for="mem_disp_yn_n">
                                            	<small>비공개</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
								<!-- [공개여부] [END] -->
							</div>
						</div>
						<!-- 
						<div class="row">
							<div class="col-md-6">
								<div class="input-group input-group-sm mb-1">
                                	<span class="input-group-append">
                                		<span class="input-group-text" style='width:150px'>상품이미지</span>
                                	</span>
                                	<input type="file" class="" style='width:250px; margin-left:5px' placeholder="" name="event_img" id="event_img">
                                </div>
							</div>
							<div class="col-md-6">
								<div class="input-group input-group-sm mb-1">
                                	<span class="input-group-append">
                                		<span class="input-group-text" style='width:150px'>상품아이콘</span>
                                	</span>
                                	<input type="file" class="" style='width:250px; margin-left:5px' placeholder="" name="event_icon" id="event_icon">
                                </div>
							</div>
						</div>
						 -->
					</div>
					<!-- CARD FOOTER [START] -->
					<div class="card-footer clearfix">
						<!-- BUTTON [START] -->
						<ul class="pagination pagination-sm m-0 float-right">
							<li class="ac-btn"><button type="button" class="btn btn-block btn-success btn-sm" id="create_event_btn">상품 만들기</button></li>
						</ul>
						
						<!-- BUTTON [END] -->
						<!-- PAGZING [START] -->
                        <!-- PAGZING [END] -->
					</div>
					<!-- CARD FOOTER [END] -->
				</div>
			</div>
		</div>
		
		</form>
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->

<!-- ############################## MODAL [ END ] ###################################### -->
	
</section>

<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

// 이용기간 숫자입력
$('#use_prod').keyup(function(){
	var d_cnt = onlyNum( $('#use_prod').val());
	$('#use_prod').val(d_cnt);
});

// 휴회횟수 숫자입력
$('#domcy_cnt').keyup(function(){
	var d_cnt = onlyNum( $('#domcy_cnt').val());
	$('#domcy_cnt').val(d_cnt);
});

// 휴회일수 숫자입력
$('#domcy_day').keyup(function(){
	var d_cnt = onlyNum( $('#domcy_day').val());
	$('#domcy_day').val(d_cnt);
});

// 판매금액 숫자입력
$('#sell_amt').keyup(function(){
	var d_amt = onlyNum( $('#sell_amt').val() );
	$('#sell_amt').val(currencyNum(d_amt));
});

$('#sell_s_date').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$('#sell_e_date').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

// 대분류 선택시 -> 중분류 동적 생성
function cate1_ch()
{
	var cate1 = $('#1rd_cate_cd').val();
	var params = "cate1="+cate1;
	jQuery.ajax({
        url: '/teventmain/ajax_cate2_list',
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
				$('#2rd_cate_cd').empty();
				$('#2rd_cate_cd').append("<option value=''>중분류 선택</option>");
				json_result['cate2'].forEach(function (r,index) {
					$('#2rd_cate_cd').append("<option data-clasdv='" + r['CLAS_DV'] + "' value='" + r['2RD_CATE_CD'] + "'>"+r['CATE_NM']+"</option>");
				});
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

// 중분류 설정에 따른 display
$("#2rd_cate_cd").on("change", function () {
    var clas_dv = $(this).find("option:selected").data("clasdv");
    
    $('#clas_dv').val(clas_dv);
    
    switch(clas_dv)
	{
		case 11 : // 중분류
			$('#title_use_prod').text('이용기간');
			$('#disp_clas_cnt').hide();
		break
		case 12 : // 골프중분류
			$('#title_use_prod').text('이용기간');
			$('#disp_clas_cnt').hide();
		break
		case 21 : // PT
			$('#title_use_prod').text('무료 이용기간');
			$('#disp_clas_cnt').show();
		break
		case 22 : // 골프 PT
			$('#title_use_prod').text('무료 이용기간');
			$('#disp_clas_cnt').show();
		break
		case 31 : // 그룹수업
			$('#title_use_prod').text('이용기간');
			$('#disp_clas_cnt').hide();
		break
		case 91 : // 기타(입장불가)
			$('#title_use_prod').text('이용기간');
			$('#disp_clas_cnt').hide();
		break
	}
});

// 휴일 사용 설정에 따른 display
function domcy_poss(domcy_yn)
{
	if(domcy_yn == 'N')
	{
		$('#disp_domcy').hide();
		$('#domcy_cnt').val('');
		$('#domcy_day').val('');
	} else 
	{
		$('#disp_domcy').show();
	}
}

$('#acc_rtrct_dv_00').click(function(){
		$('#acc_rtrct_mthd_00').attr('disabled',false);
		$('#acc_rtrct_mthd_99').attr('disabled',true);
		
		$('#acc_rtrct_mthd_00').prop('checked',true);
});


$('#acc_rtrct_dv_99').click(function(){
		$('#acc_rtrct_mthd_00').attr('disabled',true);
		$('#acc_rtrct_mthd_99').attr('disabled',false);
		
		$('#acc_rtrct_mthd_99').prop("checked", true);
});

$('#acc_rtrct_dv_50').click(function(){
		$('#acc_rtrct_mthd_50').attr('disabled',false);
		$('#acc_rtrct_mthd_98').attr('disabled',true);
		
		$('#acc_rtrct_mthd_50').prop('checked',true);
});

$('#acc_rtrct_dv_98').click(function(){
		$('#acc_rtrct_mthd_50').attr('disabled',true);
		$('#acc_rtrct_mthd_98').attr('disabled',false);
		
		$('#acc_rtrct_mthd_98').prop("checked", true);
});

$('#create_event_btn').click(function(){
	// 실패일 경우 warning error success info question
	
	// 11 : 중분류 / 12 : 골프중분류 / 21 : PT / 22: 골프PT / 31: 그룹수업 / 91: 그룹수업
	var clas_dv = $('#clas_dv').val();
	
	if ( clas_dv == "21" || clas_dv == "22" ) // 수업횟수 체크
	{
		if ( $('#clas_cnt').val() == '' )
		{
			alertToast('error','수업횟수를 입력하세요');
    		$('#clas_cnt').focus();
    		return;
		}
	} else // 이용기간 체크
	{
		if ( $('#use_prod').val() == '' )
		{
			alertToast('error','이용기간을 입력하세요');
    		$('#use_prod').focus();
    		return;
		}
	}
	
	if ( $('#sell_amt').val() == '' )
	{
		alertToast('error','판매 금액을 입력하세요');
		$('#sell_amt').focus();
		return;
	}
	
	// 휴회가능 체크일경우 휴회일, 휴회 횟수를 체크한다.
	
	if ( $('#domcy_poss_event_yn_y').prop('checked') == true )
	{
		if ( $('#domcy_cnt').val() == '' )
    	{
    		alertToast('error','휴회횟수를 입력하세요');
    		$('#domcy_cnt').focus();
    		return;
    	} 
    	
    	if ( $('#domcy_day').val() == '' )
    	{
    		alertToast('error','휴회일수를 입력하세요');
    		$('#domcy_day').focus();
    		return;
    	}
	}
	
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >상품을 생성 하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		$('#event_create_form').submit();
    		// 성공일 경우	
    		//$("#modal_two_cate_form").modal('hide');
			//alertToast('success','중분류가 생성 되었습니다.');
    	}
    });
});

// ===================== Modal Script [ START ] ===========================

// ===================== Modal Script [ END ] =============================

//Date picker
$('.datepp').datepicker({
    format: "yyyy-mm-dd",	//데이터 포맷 형식(yyyy : 년 mm : 월 dd : 일 )
    autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
    clearBtn : false, //날짜 선택한 값 초기화 해주는 버튼 보여주는 옵션 기본값 false 보여주려면 true
    immediateUpdates: false,	//사용자가 보는 화면으로 바로바로 날짜를 변경할지 여부 기본값 :false 
    multidate : false, //여러 날짜 선택할 수 있게 하는 옵션 기본값 :false 
    templates : {
        leftArrow: '&laquo;',
        rightArrow: '&raquo;'
    }, //다음달 이전달로 넘어가는 화살표 모양 커스텀 마이징 
    showWeekDays : true ,// 위에 요일 보여주는 옵션 기본값 : true
    title: "날짜선택",	//캘린더 상단에 보여주는 타이틀
    todayHighlight : true ,	//오늘 날짜에 하이라이팅 기능 기본값 :false 
    toggleActive : true,	//이미 선택된 날짜 선택하면 기본값 : false인경우 그대로 유지 true인 경우 날짜 삭제
    weekStart : 0 ,//달력 시작 요일 선택하는 것 기본값은 0인 일요일 
    
    //startDate: '-10d',	//달력에서 선택 할 수 있는 가장 빠른 날짜. 이전으로는 선택 불가능 ( d : 일 m : 달 y : 년 w : 주)
    //endDate: '+10d',	//달력에서 선택 할 수 있는 가장 느린 날짜. 이후로 선택 불가 ( d : 일 m : 달 y : 년 w : 주)
    //datesDisabled : ['2019-06-24','2019-06-26'],//선택 불가능한 일 설정 하는 배열 위에 있는 format 과 형식이 같아야함.
    //daysOfWeekDisabled : [0,6],	//선택 불가능한 요일 설정 0 : 일요일 ~ 6 : 토요일
    //daysOfWeekHighlighted : [3], //강조 되어야 하는 요일 설정
    //disableTouchKeyboard : false,	//모바일에서 플러그인 작동 여부 기본값 false 가 작동 true가 작동 안함.
    //calendarWeeks : false, //캘린더 옆에 몇 주차인지 보여주는 옵션 기본값 false 보여주려면 true
    //multidateSeparator :",", //여러 날짜를 선택했을 때 사이에 나타나는 글짜 2019-05-01,2019-06-01
    
    language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

</script>