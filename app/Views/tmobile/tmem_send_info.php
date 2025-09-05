<style>
th.day
{
    padding:13px;
}

td.day
{
    padding:13px;
}
.datepicker 
{
    top:50px;
    text-align:center;
    width:100% !important;
    left:0px !important;
}

.datepicker.datepicker-days
{
    width:100% !important;
}
</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12" style='margin:20px'>
				<li class="list-group-item">
                <b>상품명</b> <a class="float-right"><?php echo $event_info['SELL_EVENT_NM']?></a>
                </li>
                
                <li class="list-group-item">
                <b>입장종류</b> <a class="float-right"><?php echo $sDef['ACC_RTRCT_DV'][$event_info['ACC_RTRCT_DV']]?></a>
                </li>
                
                <li class="list-group-item">
                <b>이용개월</b> <a class="float-right">
                <?php echo $event_info['USE_PROD']?> <?php echo $sDef['USE_UNIT'][$event_info['USE_UNIT']]?>
				<?php if($event_info['ADD_SRVC_EXR_DAY'] > 0) : ?>
				(+<?php echo $event_info['ADD_SRVC_EXR_DAY']?> 일)
				<?php endif; ?>
                </a>
        		</li>
        		
        		<li class="list-group-item">
                <b>수업횟수</b> <a class="float-right">
                <?php echo $event_info['CLAS_CNT']?>
				<?php if($event_info['ADD_SRVC_CLAS_CNT'] > 0) : ?>
				(+<?php echo $event_info['ADD_SRVC_CLAS_CNT']?> 회)
				<?php endif; ?>
				<?php if($event_info['SEND_EVENT_SNO'] != "" && $event_info['STCHR_NM'] != "") : ?>
				(<?php echo $event_info['STCHR_NM']?>)
				<?php endif; ?>
                </a>
                </li>
                
                <li class="list-group-item">
                <b>입장상세</b> <a class="float-right"><?php echo $sDef['ACC_RTRCT_MTHD'][$event_info['ACC_RTRCT_MTHD']]?></a>
                </li>
                
                <li class="list-group-item">
                <b>휴회가능일</b> <a class="float-right"><?php echo $event_info['DOMCY_DAY']?></a>
                </li>
                
                <li class="list-group-item">
                <b>휴회가능횟수</b> <a class="float-right"><?php echo $event_info['DOMCY_CNT']?></a>
                </li>
                
                <li class="list-group-item">
                <b>판매가격</b> <a class="float-right">
                
                <?php if ($event_info['SELL_AMT'] == $event_info['ORI_SELL_AMT']) :?>
                <?php echo number_format($event_info['SELL_AMT'])?> 원
                <?php else :?>
                <span style="text-decoration: line-through;color:red">
                <?php echo number_format($event_info['ORI_SELL_AMT'])?> 원
                </span>
                <?php echo number_format($event_info['SELL_AMT'])?> 원
                <?php endif;?>
                </a>
                </li>
                
                <?php if($event_info['SEND_EVENT_SNO'] != "" && $event_info['PTCHR_NM'] != "") : ?>
                <li class="list-group-item">
                <b>판매강사</b> <a class="float-right"><?php echo $event_info['PTCHR_NM']?></a>
                </li>
                <?php endif; ?>	
			
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12" style='margin-left:20px;margin-right:20px;'>
				 <!-- [판매금액] [START] -->
                <div class="input-group input-group-sm mb-1" style='display:none'>
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>판매금액</span>
                	</span>
                	<input type="text" class="input-readonly text-right" style='width:140px; margin-left:5px' placeholder="" name="sell_amt" id="sell_amt" value="<?php echo number_format($event_info['SELL_AMT'])?>" readonly />
                </div>
                <!-- [판매금액] [END] -->
                <!-- [실제 판매할 금액] [START] -->
                <div class="input-group input-group-sm mb-1" style='display:none'>
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>실제 판매할 금액</span>
                	</span>
                	<input type="text" class="text-right" style='width:140px; margin-left:5px' placeholder="" name="real_sell_amt" id="real_sell_amt"  value="<?php echo number_format($event_info['SELL_AMT'])?>" />
                </div>
                <!-- [실제 판매할 금액] [END] -->
                
                
                
                
                
                <form name="form_send_set" id="form_send_set" method="post" action="/api/send_event_proc">
            	<input type="hidden" name="send_sell_event_sno" id="send_sell_event_sno" value="<?php echo $event_info['SELL_EVENT_SNO']?>" />
            	<input type="hidden" name="send_mem_sno" id="send_mem_sno" value="<?php echo $mem_info['MEM_SNO']?>" />
            	<input type="hidden" id="clas_dv" value="<?php echo $event_info['CLAS_DV']?>" />
            	<input type="hidden" id="domcy_yn" value="<?php echo $event_info['DOMCY_POSS_EVENT_YN']?>" />
            	<div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>보내기 금액</span>
                	</span>
                	<input type="text" class="form-control" name="set_send_amt" id="set_send_amt" value="<?php echo number_format($event_info['SELL_AMT'])?>" />
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append disp_serv_cnt">
                		<span class="input-group-text input-readonly disp_serv_cnt" style='width:100px'>서비스 수업</span>
                	</span>
                	<input type="text" class="form-control disp_serv_cnt" name="set_send_serv_clas_cnt" id="set_send_serv_clas_cnt" value="0">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly disp_serv_day" style='width:100px'>서비스 일수</span>
                	</span>
                	<input type="text" class="form-control disp_serv_day" name="set_send_serv_day" id="set_send_serv_day" value="0">
                	
                </div>	
                <div class="input-group input-group-sm mb-1">
                	
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>휴회횟수</span>
                	</span>
                	<input type="text" class="form-control" name="set_send_domcy_cnt" id="set_send_domcy_cnt" value="<?php echo $event_info['DOMCY_CNT']?>" />
                </div>
				<div class="input-group input-group-sm mb-1">                	
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>휴회일</span>
                	</span>
                	<input type="text" class="form-control" name="set_send_domcy_day" id="set_send_domcy_day" value="<?php echo $event_info['DOMCY_DAY']?>" />
                </div>
                <div class="input-group input-group-sm mb-1">
                	
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>마감일 설정</span>
                	</span>
                	<input type="text" class="" style="width:30px" name="set_end_day" id="set_end_day" value="7"> <span style='font-size:0.8rem;color:red;'>*최대 7일까지 설정이 가능합니다.</span>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>판매강사</span>
                	</span>
                	<select class="select2 form-control" name="set_ptchr_id_nm" id="set_ptchr_id_nm">
                		<option value="">강사 선택</option>
                	<?php foreach ($tchr_list as $r) : ?>
						<option value="<?php echo $r['MEM_ID']?>|<?php echo $r['MEM_NM']?>" <?php if($r['MEM_ID'] == $_SESSION['user_id']) { ?> selected <?php } ?> >[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?> </option>
					<?php endforeach; ?>
					</select>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly disp_serv_cnt" style='width:100px'>수업강사</span>
                	</span>
                	<span class="disp_serv_cnt">
                	<select class="select2 form-control"  name="set_stchr_id_nm" id="set_stchr_id_nm">
                		<option value="">강사 선택</option>
                	<?php foreach ($tchr_list as $r) : ?>
						<option value="<?php echo $r['MEM_ID']?>|<?php echo $r['MEM_NM']?>">[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?> </option>
					<?php endforeach; ?>
					</select>
					</span>
                </div>
                
                </form>
			
			</div>
		</div>
		
		<button type="button" class="btn btn-block btn-success btn-sm" style='height:50px;' onclick="btn_send_submit();">추천상품 보내기</button>
		
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ============================= [ modal-default START ] ======================================= -->	
<!-- ============================= [ modal-default END ] ======================================= -->
<!-- ############################## MODAL [ END ] ###################################### -->
	
</section>

<?=$jsinc ?>

<script>

set_disp($('#clas_dv').val(),$('#domcy_yn').val());

function set_disp(CLAS_DV,DOMCY_POSS_EVENT_YN)
{
	if(CLAS_DV == '21' || CLAS_DV == '22')
	{
		$('.disp_serv_cnt').show();
		$('.disp_serv_day').hide();
	} else 
	{
		$('.disp_serv_cnt').hide();
		$('.disp_serv_day').show();
	}
	
	if ( DOMCY_POSS_EVENT_YN == 'Y' )
	{
		$('#set_send_domcy_cnt').attr('readonly',false);
		$('#set_send_domcy_day').attr('readonly',false);
	} else 
	{
		$('#set_send_domcy_cnt').attr('readonly',true);
		$('#set_send_domcy_day').attr('readonly',true);
	}
}

// 보내기 금액
$('#set_send_amt').keyup(function(){
	var d_amt = onlyNum( $('#set_send_amt').val() );
	$('#set_send_amt').val(currencyNum(d_amt));
});

$(function () {
    $('.select2').select2();
})

function btn_send_submit()
{

	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >상품을 추천하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
			$('#form_send_set').submit();		
    	}
    });
	
}

// ===================== Modal Script [ START ] ===========================

// ===================== Modal Script [ END ] =============================

</script>