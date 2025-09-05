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
                
                
                <!-- [라커] [START] -->
                <?php if($event_info['LOCKR_SET'] == "Y") : ?>
                		
                <input type="hidden" name="exr_s_date" id="exr_s_date" value="<?php echo date('Y-m-d')?>" />
                <div style='margin-top:5px;margin-left:5px;'>
                	<div class="icheck-primary d-inline">
                        <input type="radio" name="lockr_gendr_set" id="lockr_gendr_set_m" value="M" checked>
                        <label for="lockr_gendr_set_m">
                        	<small>남자</small>
                        </label>
                    </div>
                    <div class="icheck-primary d-inline">
                        <input type="radio" name="lockr_gendr_set" id="lockr_gendr_set_f" value="F">
                        <label for="lockr_gendr_set_f">
                        	<small>여자</small>
                        </label>
                    </div>
                    <div class="icheck-primary d-inline">
                        <input type="radio" name="lockr_gendr_set" id="lockr_gendr_set_g" value="G">
                        <label for="lockr_gendr_set_g">
                        	<small>공용</small>
                        </label>
                    </div>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>라커번호</span>
                	</span>
                	<input type="text" class="" style='width:50px; margin-left:5px' placeholder="" name="lockr_no" id="lockr_no" value="" readonly />
                	<span class="input-group-append">
                		<button type="button" class="btn btn-warning btn-flat" style='margin-left:5px' onclick="func_lockr_no_clear();" >번호지우기</button>
                	</span>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<?php foreach($get_use_locker_info as $lk) : ?>
                	<span class="input-group-append" style='margin-bottom:5px;'>
                		<button type="button" class="btn btn-success btn-flat" style='margin-left:5px' onclick="func_lockr_no_select('<?php echo $lk['LOCKR_GENDR_SET']?>','<?php echo $lk['LOCKR_NO']?>')" ><?php echo $sDef['LOCKR_GENDR_SET'][$lk['LOCKR_GENDR_SET']]?> <?php echo $lk['LOCKR_NO']?> 번</button>
                	</span>
                	<?php endforeach; ?>
                	<?php foreach($get_use_locker_info as $lk) : ?>
                	<span class="input-group-append" style='margin-bottom:5px;'>
                		<button type="button" class="btn btn-success btn-flat" style='margin-left:5px' onclick="func_lockr_no_select('<?php echo $lk['LOCKR_GENDR_SET']?>','<?php echo $lk['LOCKR_NO']?>')" ><?php echo $sDef['LOCKR_GENDR_SET'][$lk['LOCKR_GENDR_SET']]?> <?php echo $lk['LOCKR_NO']?> 번</button>
                	</span>
                	<?php endforeach; ?>
                	
                	
                </div>
                
                <?php else :?>
                <!-- [운동시작일] [START] -->
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:120px'>운동시작일</span>
                	</span>
                	<input type="text" class="datepp text-center" style='width:120px; margin-left:5px' placeholder="" name="exr_s_date" id="exr_s_date" value="<?php echo date('Y-m-d')?>" readonly />
                </div>
                <!-- [운동시작일] [END] -->
                <div style="display:none"><input type="radio" name="lockr_gendr_set" id="lockr_gendr_set_m" value="" checked></div>
                <input type="hidden" name="lockr_no" id="lockr_no" value="" />
                <?php endif; ?>
                <!-- [라커] [END] -->
			
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12" style='margin:20px'>
			
				<!-- [카드결제] [START] -->
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append"  style='display:none'>
                		<span class="input-group-text" style='width:100px'>카드결제</span>
                	</span>
                	<input type="hidden" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="card_amt" id="card_amt"  value="" />
                	<span class="input-group-append" style='display:none'>
                    	<button type="button" class="btn btn-info btn-flat" style='margin-left:5px' id="btn_pay_conn">결제</button>
                    </span>
                    <span class="input-group-append" style='display:none'>
                    	<button type="button" class="btn btn-danger btn-flat" style='margin-left:5px' id="btn_pay_direct">모바일 결제</button>
                    </span>
                	<input type="hidden" class="" style='width:100px; margin-left:5px' placeholder="승인번호" name="card_appno" id="card_appno"  value="" />
                	
                </div>
                <!-- [카드결제] [END] -->
                <!-- [현금결제] [START] -->
                <div class="input-group input-group-sm mb-1" style='display:none'>
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>현금결제</span>
                	</span>
                	<input type="text" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="cash_amt" id="cash_amt"  value="" />
                </div>
                <!-- [현금결제] [END] -->
                <!-- [현금결제] [미수금] -->
                <div class="input-group input-group-sm mb-1" style='display:none'>
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>미수금</span>
                	</span>
                	<input type="text" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="misu_amt" id="misu_amt"  value="" readonly />
                </div>
                <!-- [현금결제] [미수금] -->
                 <!-- [계좌이체] [START] -->
                <div class="input-group input-group-sm mb-1" style='display:none'>
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>계좌이체</span>
                	</span>
                	<input type="text" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="acct_amt" id="acct_amt"  value="" />
                	<select class="select2 form-control" style="width: 260px;" name="acct_no" id="acct_no">
                		<option>계좌 선택</option>
                		<option>국민 : 12321-13547-125-25</option>
                		<option>하나 : 8514-254-45387-11</option>
        			</select>
                </div>
                <!-- [계좌이체] [END] -->
			
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12" style='margin:20px'>
			
                <!-- BUTTON [END] -->
				<!-- BUTTON [START] -->
				<ul class="pagination pagination-sm m-0 float-right">
				<li class="ac-btn" style='display:none'>
					<div class="icheck-primary d-inline">
                        <input type="radio" id="radioPayIssue1" name="type_pay_issue" checked>
                        <label for="radioPayIssue1">
                        	<small>일반구매</small>
                        </label>
                    </div>
                    <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPayIssue2" name="type_pay_issue">
                        <label for="radioPayIssue2">
                        	<small>교체구매</small>
                        </label>
                    </div>
				</li>
				
					<li class="ac-btn"></li>
				</ul>
			
			</div>
		</div>
		
		<!-- 
		<button type="button" class="btn btn-block btn-danger btn-sm" style='height:50px;' id="btn_pay_mobile">모바일 결제(연동)</button>
		 -->
		<button type="button" class="btn btn-block btn-success btn-sm" style='height:50px;' id='btn_pay_confirm'>결제 상품 확인</button>
		
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-default END ] ======================================= -->
<!-- ############################## MODAL [ END ] ###################################### -->

<!-- <form name="form_payment_submit" id="form_payment_submit" method="post" action="/api/event_buy_proc"> -->
	<form name="form_payment_submit" id="form_payment_submit">
    <input type='hidden' name='mem_sno' id='mem_sno' value="<?php echo $mem_info['MEM_SNO']?>" />
    <input type='hidden' name='mem_id' id='mem_id' value="<?php echo $mem_info['MEM_ID']?>" />
    <input type='hidden' name='mem_nm' id='mem_nm' value="<?php echo $mem_info['MEM_NM']?>" />
    
    <input type='hidden' name='sell_event_sno' id='sell_event_sno' value="<?php echo $event_info['SELL_EVENT_SNO']?>" />
    <input type='hidden' name='send_event_sno' id='send_event_sno' value="<?php echo $event_info['SEND_EVENT_SNO']?>" />
    

    <input type="hidden" name="van_appno" id="van_appno" />
    <input type="hidden" name="van_appno_sno" id="van_appno_sno" />
    
    <input type="hidden" name="pay_exr_s_date" id="pay_exr_s_date" />
    
    <input type="hidden" name="pay_card_amt" id="pay_card_amt" />
    <input type="hidden" name="pay_acct_amt" id="pay_acct_amt" />
    <input type="hidden" name="pay_acct_no" id="pay_acct_no" />
    <input type="hidden" name="pay_cash_amt" id="pay_cash_amt" />
    <input type="hidden" name="pay_misu_amt" id="pay_misu_amt" />
    <input type="hidden" name="pay_real_sell_amt" id="pay_real_sell_amt" />
    <input type="hidden" name="pay_lockr_no" id="pay_lockr_no" />
    <input type="hidden" name="pay_lockr_gendr_set" id="pay_lockr_gendr_set" />
    
    <input type="hidden" name="pay_issue" id="pay_issue" />
    
</form>

	
</section>

<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
    var d_amt = onlyNum( $('#real_sell_amt').val() );
	$('#card_amt').val(currencyNum(d_amt));
	calu_amt();
})

// 카드결제금액 입력
$('#card_amt').keyup(function(){
	var d_amt = onlyNum( $('#card_amt').val() );
	$('#card_amt').val(currencyNum(d_amt));
	calu_amt();
});

// 현금결제금액 입력
$('#cash_amt').keyup(function(){
	var d_amt = onlyNum( $('#cash_amt').val() );
	$('#cash_amt').val(currencyNum(d_amt));
	calu_amt();
});

// 미수결제금액 입력
$('#misu_amt').keyup(function(){
	var d_amt = onlyNum( $('#misu_amt').val() );
	$('#misu_amt').val(currencyNum(d_amt));
	calu_amt();
});

// 계좌결제금액 입력
$('#acct_amt').keyup(function(){
	var d_amt = onlyNum( $('#acct_amt').val() );
	$('#acct_amt').val(currencyNum(d_amt));
	calu_amt();
});

// 미수금 자동 계산
function calu_amt()
{
	var g_basic_amt = $('#real_sell_amt').val();
	var g_card_amt = $('#card_amt').val();
	var g_cash_amt = $('#cash_amt').val();
	
	if (g_basic_amt == "") 
	{
		g_basic_amt = 0;
	} else 
	{
		g_basic_amt = onlyNum(g_basic_amt);
	}
	
	if (g_card_amt == "") 
	{
		g_card_amt = 0;
	} else 
	{
		g_card_amt = onlyNum(g_card_amt);
	}
	
	if (g_cash_amt == "") 
	{
		g_cash_amt = 0;
	} else 
	{
		g_cash_amt = onlyNum(g_cash_amt);
	}
	
	var misu_amt = 0;
	misu_amt =g_basic_amt - (parseInt(g_card_amt) + parseInt(g_cash_amt));
	$('#misu_amt').val(currencyNum(misu_amt));
}


function func_lockr_no_select(gendr,lkno)
{
	if (gendr == 'M')
	{
		$('#lockr_gendr_set_m').prop('checked',true);
	} else if (gendr == 'F')
	{
		$('#lockr_gendr_set_f').prop('checked',true);
	} else if (gendr == 'G')
	{
		$('#lockr_gendr_set_g').prop('checked',true);
	}

	$('#lockr_no').val(lkno);
}

function func_lockr_no_clear()
{
	$('#lockr_no').val('');
}

$('#btn_pay_mobile').click(function(){
	location.href="/api/mobile_pay";
});
    
$('#btn_pay_confirm').click(function(){
	$('#pay_card_amt').val($('#card_amt').val());
	$('#pay_acct_amt').val($('#acct_amt').val());
	$('#pay_acct_no').val($('#acct_no').val());
	$('#pay_cash_amt').val($('#cash_amt').val());
	$('#pay_misu_amt').val($('#misu_amt').val());
	$('#pay_exr_s_date').val($('#exr_s_date').val());
	$('#pay_real_sell_amt').val($('#real_sell_amt').val());
	$('#pay_lockr_no').val($('#lockr_no').val());
	$('#pay_lockr_gendr_set').val($("input[name='lockr_gendr_set']:checked").val());
	
	if ( $('#misu_amt').val() != '' )
	{
		if ( parseInt($('#misu_amt').val()) < 0 )
		{
			alertToast('error','미수금은 - 가 될 수 없습니다. 금액을 정확히 입력하세요');
			return;
		}
	}
	
	
	
	if ( $('#radioPayIssue1').is(':checked') == false && $('#radioPayIssue2').is(':checked') == false )
	{
		alertToast('error','일반구매,교체구매중 하나를 선택하세요');
		return;
	}
	
	if ( $('#radioPayIssue1').is(':checked') == true )
	{
		$('#pay_issue').val('N');
	}
	
	if ( $('#radioPayIssue2').is(':checked') == true )
	{
		$('#pay_issue').val('Y');
	}
	
	var params = $("#form_payment_submit").serialize();
    jQuery.ajax({
        url: '/api/ajax_pre_event_buy_proc',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
        	if ( result.substr(0,8) == '<script>' )
        	{
        		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
        		location.href='/login';
        		return;
        	}
        	
            json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				location.href="/api/mobile_pay/"+json_result['sno']; //모바일 결제부분으로 이동.
			} else 
			{
				alertToast('error',json_result['msg']);
				return;
			}
        }
    }).done((res) => {
    	// 통신 성공시
    	console.log('통신성공');
    }).fail((error) => {
    	// 통신 실패시
    	console.log('통신실패');
    	alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
		location.href='/login';
		return;
    });
});
    
// 수동결제 버튼 클릭
$('#btn_pay_direct').click(function(){

	if ( parseInt($('#misu_amt').val()) < 0 )
	{
		alertToast('error','미수금은 - 가 될 수 없습니다. 금액을 정확히 입력하세요');
		return;
	}

	var mem_id = $('#mem_id').val();
	var sell_event_sno = $('#sell_event_sno').val();
	var card_amt = $('#card_amt').val();
	var card_appno = $('#card_appno').val();
	
	var params = "mem_id="+mem_id+"&sell_event_sno="+sell_event_sno+"&card_amt="+card_amt+"&card_appno="+card_appno;
	
	jQuery.ajax({
        url: '/api/ajax_event_buy_van_direct_proc',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
        	if ( result.substr(0,8) == '<script>' )
        	{
        		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
        		location.href='/login';
        		return;
        	}
        	
			json_result = $.parseJSON(result);
			
			if (json_result['result'] == 'true')
			{
				$('#van_appno').val(json_result['appno']);
				$('#van_appno_sno').val(json_result['appno_sno']);
				
				$('#btn_pay_conn').attr("disabled",true);
				$('#btn_pay_direct').attr("disabled",true);
				$('#card_amt').attr("readonly",true);
				
				alertToast('success','카드 수동결제가 완료 되었습니다.');
				
			} else 
			{
				alertToast('error','결제에 실패 하였습니다.');
			}
        }
    }).done((res) => {
    	// 통신 성공시
    	console.log('통신성공');
    }).fail((error) => {
    	// 통신 실패시
    	console.log('통신실패');
    	alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
		location.href='/login';
		return;
    });
	
});

// ===================== Modal Script [ START ] ===========================

$("#script_modal_default").click(function(){
	$("#modal-default").modal();
});

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