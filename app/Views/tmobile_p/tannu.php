<style>
th.day
{
    padding:17px;
}

td.day
{
    padding:17px;
}
.datepicker 
{
    top:50px;
    text-align:center;
    width:320px !important; /* 달력 너비 제한 */
    left:50% !important;
    transform: translateX(-50%); /* 중앙 정렬 */
    margin: 0 auto;
}

.datepicker.datepicker-days
{
    width:320px !important; /* 일 달력 너비 제한 */
}

/* 달력 날짜 배열 수정 */
.datepicker table {
    width: 100%;
    table-layout: fixed;
}

.datepicker table tr {
    display: table-row;
}

.datepicker table td,
.datepicker table th {
    display: table-cell;
    text-align: center;
    vertical-align: middle;
    width: 14.28571%; /* 7일 기준으로 100% / 7 */
    padding: 8px 4px; /* 패딩 조정으로 컴팩트하게 */
    border-radius: 4px;
    font-size: 14px; /* 폰트 크기 조정 */
}

.datepicker .datepicker-days table {
    margin: 0;
}

.datepicker .dow {
    font-weight: bold;
    color: #999;
}

.datepicker .day {
    cursor: pointer;
}

.datepicker .day:hover {
    background-color: #eee;
    border-radius: 4px;
}

.datepicker .day.active,
.datepicker .day.active:hover {
    background-color: #337ab7;
    color: #fff;
}

.datepicker .day.today {
    background-color: #ffdb99;
    color: #000;
}

.datepicker .day.disabled {
    color: #ccc;
    cursor: default;
}

.datepicker .day.disabled:hover {
    background-color: transparent;
}

.overlay {
  background: #fff;
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  transition: all 600ms cubic-bezier(0.86, 0, 0.07, 1);
  top: 100%;
  position: fixed;
  left: 0;
  text-align: left;
  .header {
    padding:20px;
    border-bottom: 1px solid #ddd;
    font: 300 24px Lato;
    position: relative;
    }
  .body {
    padding: 20px;
    font: 300 16px Lato;
  }
}

.content.modal-open .overlay {
  top: 55px;
}

.overlay2 {
  background: #fff;
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  transition: all 600ms cubic-bezier(0.86, 0, 0.07, 1);
  top: 100%;
  position: fixed;
  left: 0;
  text-align: left;
  .header {
    padding:20px;
    border-bottom: 1px solid #ddd;
    font: 300 24px Lato;
    position: relative;
    }
  .body {
    padding: 20px;
    font: 300 16px Lato;
  }
}

.content.modal-open .overlay2 {
  top: 55px;
}


</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
 
<div class="new-title">연차관리</div>
<section class="content">
	
		<?php
		$cc_count = count($tchr_annu_list);
		
		if ($cc_count > 0) :
		foreach ($tchr_annu_list as $r) :?>
		<div class="row" id='status-info'>
            <div class="col-4">
                <div class="small-box2 bgcolor_1">
                <div class="inner">
                <h3><?php echo $r['ANNU_GRANT_DAY']?><sup style="font-size: 20px"> 일</sup></h3>
                <p>연차부여</p>
                </div>
                
                </div>
            </div>
            
            <div class="col-4">
                <div class="small-box2 bg-danger">
                <div class="inner">
                <h3><?php echo $r['ANNU_USE_DAY']?><sup style="font-size: 20px"> 일</sup></h3>
                <p>사용</p>
                </div>

                </div>
            </div>
            
            <div class="col-4">
                <div class="small-box2 bg-info" >
                <div class="inner">
                <h3><?php echo $r['ANNU_LEFT_DAY']?><sup style="font-size: 20px"> 일</sup></h3>
                <p>남은연차</p>
                </div>

                </div>
            </div>
            
        </div>
		<?php 
		$cc_count--;
		endforeach;
		else :
		?>
		<div class="row" id='status-info'>
			<div class="col-lg-12 col-12">
    			<div class='text-center' style="height:50px;margin-top:20px;">
    				<span class='text-center' style='font-size:0.9rem;'>부여된 연차가 없습니다. 관리자에게 문의 하세요.</span>
    			</div>
			</div>
		</div>
		<?php endif;?>
		<!-- <button type="button" class="btn btn-info btn-block bottom-menu">연차신청하기</button> -->
		
		<div class="row">
			<div class="col-md-12">
                
                <!-- CARD BODY [START] -->
				<div class="card-body">
					<?php 
					$bb_count = count($tchr_annu_appct_list);
					foreach ($tchr_annu_appct_list as $r) :
					
					$stat_word = "";
					$stat_color = "black";
					switch ($r['ANNU_APPV_STAT'])
					{
					    case "00": //신청
					        $stat_word = "";
					        $stat_color = "black";
					        break;
					    case "01": //승인
					        $stat_word = "승인일 : " . substr($r['ANNU_APPCT_DATETM'],0,10);
					        $stat_color = "green";
					        break;
					    case "90": //반려
					        $stat_word = "반려일 : " . substr($r['ANNU_REFUS_DATETM'],0,10);
					        $stat_color = "orange";
					        break;
					    case "99": // 취소
					        $stat_word = "취소일 : " . substr($r['ANNU_CANCEL_DATETM'],0,10);
					        $stat_color = "red";
					        break;
					}
					
					?>
                    <?php disp_ynr($sDef['ANNU_APPV_STAT'][$r['ANNU_APPV_STAT']])?>
                    <?php if ($r['ANNU_APPV_STAT'] == "00") :?>
                    <span style='float:right'>
                    	<button type="button" class="btn btn-xs" style="color:red;font-size:1.2rem;" onclick="annu_cancel('<?php echo $r['ANNU_USE_MGMT_HIST_SNO']?>');"><i class="fas fa-ban"></i></button>
                    </span>
                    <?php endif;?>
                    <div class="text-muted mt10 bold size16">
                        <i class="far fa-calendar-alt"></i>&nbsp; <?php echo $r['ANNU_APPCT_S_DATE']?></strong>&nbsp; ~&nbsp;
                        <i class="far fa-calendar-alt"></i>&nbsp; <?php echo $r['ANNU_APPCT_E_DATE']?></strong>
                        ( <?php echo $r['ANNU_USE_DAY']?>일 )
                    </div>
                    <span style="font-size:0.8rem;"><strong><i class="fas fa-pen"></i> 신청일 : <?php echo substr($r['ANNU_APPV_DATETM'],0,10)?></strong></span> &nbsp;| 
                    &nbsp;<span style="font-size:0.8rem;color:<?php echo $stat_color?>"><strong><i class="fas fa-check-circle"></i> <?php echo $stat_word?></strong></span>
                    
                    
                    
                    <hr>
                    <?php
					$bb_count--;
					endforeach;?>
				</div>
                <!-- CARD BODY [END] -->
                			
			</div>
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

<div class="overlay">
    <div class="row">
 <div class="new-title">연차신청하기</div>

    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area">
                <button type="button" class="close" id="bottom-menu-close" style="margin-right:20px;margin-top:-35px; color:#fff">&times;</button>
                <br />
                <div id='bottom-content' class="p-4">

    				<!-- FORM [START] -->
                	<form id="tchr_annu_form">
                	
                	<input type="hidden" name="mem_sno" id="mem_sno" value="<?php echo $tchr_info['MEM_SNO']?>" />
                	<input type="hidden" name="mem_id" id="mem_id" value="<?php echo $tchr_info['MEM_ID']?>" />
                	<input type="hidden" name="mem_nm" id="mem_nm" value="<?php echo $tchr_info['MEM_NM']?>" />
                	<input type="hidden" name="annu_grant_mgmt_sno" id="annu_grant_mgmt_sno" value="<?php echo $annu_grant_mgmt_sno?>" />
                    
                    <div class="input-group input-group-sm mb-1">
                    	<span class="input-group-append">
                    		<span class="input-group-text" >연차 사용 가능 시작일</span>
                    	</span>
                    	<input type="text" class="form-control" name="annu_appct_s_date" id="annu_appct_s_date" onchange="edate_calu_days()" value="<?php echo date('Y-m-d')?>">
                    </div>
                    <div class="input-group input-group-sm mb-1">
                    	<span class="input-group-append">
                    		<span class="input-group-text" >연차 사용 가능 종료일</span>
                    	</span>
                    	<input type="text" class="form-control" name="annu_appct_e_date" id="annu_appct_e_date" onchange="edate_calu_days()" value="<?php echo date('Y-m-d')?>">
                    </div>
                    <div class="input-group input-group-sm mb-1">
                    	<span class="input-group-append">
                    		<span class="input-group-text">사용일</span>
                    	</span>
                    	<input type="text" class="form-control" name="annu_days" id="annu_days" readonly>
                    </div>
                    
                	<!-- 
                	 -->
                	</form>
                	<!-- FORM [END] -->
					
					<button type="button" class="btn bga-main pad15 ft-white mt20 radius0 btn-block" onclick="annu_set_proc();">신청하기</button>
                </div>
            </div>
    	</div>
    </div>
</div>

<div class="overlay2">
    <div class="row">
    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area">
    			<form name="form_tmem_payment" id="form_tmem_payment" method="post" action="/api/tmem_payment">
                    <button type="button" class="close" id="bottom-menu-close" style="margin-right:10px;margin-top:5px;">&times;</button>
                    <br />
                    <div class='bottom-title text-center'>판매매출 검색</div>
                    <div class='bottom-content' style='margin-top:15px;'>
                    
                        <div class="card card-success">
                        <div class="card-body">
                        
                        <select class="text-center" style="width:100%;height:50px;margin-bottom:10px;" name="ss_yy" id="ss_yy">
                        	<?php for($i=date('Y');$i>2020;$i--) :?>
                        	<option value="<?php echo $i?>" <?php if ($i == $ss_yy) {?> selected <?php } ?> ><?php echo $i?>년</option>
                        	<?php endfor; ?>
    					</select>
    					
    					<select class="text-center" style="width:100%;height:50px;margin-bottom:10px;" name="ss_mm" id="ss_mm">
    						<option value="01" <?php if ($ss_mm == '01' ) {?> selected <?php } ?> >01월</option>
    						<option value="02" <?php if ($ss_mm == '02' ) {?> selected <?php } ?> >02월</option>
    						<option value="03" <?php if ($ss_mm == '03' ) {?> selected <?php } ?> >03월</option>
    						<option value="04" <?php if ($ss_mm == '04' ) {?> selected <?php } ?> >04월</option>
    						<option value="05" <?php if ($ss_mm == '05' ) {?> selected <?php } ?> >05월</option>
    						<option value="06" <?php if ($ss_mm == '06' ) {?> selected <?php } ?> >06월</option>
    						<option value="07" <?php if ($ss_mm == '07' ) {?> selected <?php } ?> >07월</option>
    						<option value="08" <?php if ($ss_mm == '08' ) {?> selected <?php } ?> >08월</option>
    						<option value="09" <?php if ($ss_mm == '09' ) {?> selected <?php } ?> >09월</option>
    						<option value="10" <?php if ($ss_mm == '10' ) {?> selected <?php } ?> >10월</option>
    						<option value="11" <?php if ($ss_mm == '11' ) {?> selected <?php } ?> >11월</option>
    						<option value="12" <?php if ($ss_mm == '12' ) {?> selected <?php } ?> >12월</option>
    					</select>
                        
                        </div>
                        </div>
                        
                        <button type="button" class='btn btn-block btn-success btn-sm p-3 bottom-menu' onclick="btn_search();">검색하기</button>
                    
                    </div>
                </form>
            </div>
    	</div>
    </div>
</div>
	
</section>
<button type="button" class="btn btn-block bga-main pad15 ft-white mt20 radius0 bottom-menu">연차신청하기</button>
<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

function edate_calu_days()
{
	var sDate = $('#annu_appct_s_date').val();
	var eDate = $('#annu_appct_e_date').val();
	
	const getDateDiff = (d1, d2) => {
		const date1 = new Date(d1);
		const date2 = new Date(d2);
		  
		const diffDate = date2.getTime() - date1.getTime();
		  
		return (diffDate / (1000 * 60 * 60 * 24)) + 1; // 밀리세컨 * 초 * 분 * 시 = 일
	}
	
	var day_cnt = getDateDiff(sDate,eDate);
	$('#annu_days').val(day_cnt);
}

function annu_set_form()
{
	if ( $('#annu_grant_mgmt_sno').val() == '' )
	{
		alertToast('error','신청할 연차가 없습니다.');
		return;
	}

	$('#annu_appct_s_date').datepicker('destroy');
	$('#annu_appct_s_date').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
    });
    
    $('#annu_appct_e_date').datepicker('destroy');
	$('#annu_appct_e_date').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
    });
    
    edate_calu_days();
    
    $('#modal_tchr_annu_appct').modal();
    
}

function annu_set_proc()
{
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >연차를 신청 하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		var params = $("#tchr_annu_form").serialize();
            jQuery.ajax({
                url: '/api/ajax_tchr_annu_appct_proc',
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
        				location.reload();
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
    	}
    });	
	
}

function annu_cancel(hist_sno)
{

	ToastConfirm.fire({
        icon: "error",
        title: "  확인 메세지",
        html: "<font color='#000000' >연차신청을 취소 하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		var params = "hist_sno="+hist_sno;
            jQuery.ajax({
                url: '/api/ajax_tchr_annu_cancel_proc',
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
        				location.reload();
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
    	}
    });	
	
}

$(".bottom-menu").click(function(){
	if ( $('#annu_grant_mgmt_sno').val() == '' )
	{
		alertToast('error','신청할 연차가 없습니다.');
		return;
	}
	
	var h_size = $(window).height();
  	var c_size = h_size - 200;
  	$('#bottom-menu-area').css("height",h_size+"px");
 	$('.content').addClass('modal-open');
 	
 	annu_set_form();
});

$(".bottom-menu").click(function(){
	$(".overlay").show();
 	var h_size = $(window).height();
 	$('#bottom-menu-area').css("height",h_size+"px");
});

$("#bottom-menu-close").click(function(){
	$('.content').removeClass('modal-open');
});

function search_detail()
{
 	var h_size = $(window).height();
  	var c_size = h_size - 200;
  	$('#bottom-menu-area').css("height",h_size+"px");
 	$('.content').addClass('modal-open');
}


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