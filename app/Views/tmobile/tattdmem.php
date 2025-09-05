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

#status-info {
  position: sticky;
  top: 57px;
  height: 150px;
  z-index: 998;
}

.row {
    background-color:#ffffff !important;
}

.container-fluid {
    background-color:#ffffff !important;
}

.overlay {
  z-index: 999;
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

.overlay2 {
  z-index: 1000;
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

.content.modal-open .overlay2 {
  top: 55px;
}


</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
	
	
		<div class="row" id='status-info'>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info " style='margin:5px'>
                <div class="inner">
                <h3><?php echo count($list_clas_attd_n)?><sup style="font-size: 20px">회</sup></h3>
                <p>일반입장</p>
                </div>
                <div class="icon">
                <i class="fas fa-shopping-cart"></i>
                </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success" style='margin:5px'>
                <div class="inner">
                <h3><?php echo count($list_clas_attd_y)?><sup style="font-size: 20px">회</sup></h3>
                <p>수업입장</p>
                </div>
                <div class="icon">
                <i class="ion ion-stats-bars"></i>
                </div>
                </div>
            </div>
            <div class="col-md-12">
	    		<div class='float-right' style='margin-right:10px;' onclick="search_detail();">
	    			<span style='margin-right:15px;font-size:0.9rem'>
	    			<?php echo $ss_ymd?>
	    			</span>
	    			<i class="fas fa-chevron-down"></i>
	    		</div>
	    	</div>
        </div>
	
	
		<div class="row">
			<div class="col-md-12">
                
                <div class="page-header bg-info">
					<h3 class="panel-title">일반입장</h3>
				</div>
				
                <!-- CARD BODY [START] -->
				<div class="panel-body">
                    
                <?php
                if ( count($list_clas_attd_n) > 0) :
                foreach($list_clas_attd_n as $r) :?>    
                    <span style="color:green"><strong><span class="badge bg-info">일반입장</span> <?php disp_mem_info($r['MEM_SNO']);?> <?php echo $r['MEM_NM']?></strong></span>
                    <div class="text-muted" style="font-size:1.0rem;">
                    	PT <?php echo ($r['CLAS_CNT'] + $r['ADD_SRVC_CLAS_CNT'])?>회 중 <?php echo ($r['MEM_REGUL_CLAS_PRGS_CNT'] + $r['SRVC_CLAS_PRGS_CNT'])?>회
                    	<span style='float:right'>
                        	<button type="button" class="btn btn-xs" onclick="pt_chk('<?php echo $r['BUY_EVENT_SNO']?>');"><i class="fas fa-chevron-right"></i></button>
                        </span>
                    </div>
                    <span style="font-size:0.8rem;"><strong><i class="far fa-calendar-alt"></i> <?php echo $r['ATTD_DATETM']?></strong></span> 
                    <hr>
                <?php endforeach;
                else :
                ?>
				<span style='font-size:0.9rem;'>일반 입장이 없습니다.</span>
				<?php endif;?>
				</div>
                <!-- CARD BODY [END] -->
                			
			</div>
			
			<div class="col-md-12">
                
                <div class="page-header bg-success">
					<h3 class="panel-title">수업입장</h3>
				</div>
				
                <!-- CARD BODY [START] -->
				<div class="panel-body">
                    
                <?php 
                if ( count($list_clas_attd_y) > 0) :
                foreach($list_clas_attd_y as $r) :?>    
                    <span style="color:green"><strong><span class="badge bg-success">수업입장</span> <?php disp_mem_info($r['MEM_SNO']);?> <?php echo $r['MEM_NM']?></strong></span>
                    <div class="text-muted" style="font-size:1.0rem;">
                    	PT <?php echo ($r['CLAS_CNT'] + $r['ADD_SRVC_CLAS_CNT'])?>회 중 <?php echo ($r['MEM_REGUL_CLAS_PRGS_CNT'] + $r['SRVC_CLAS_PRGS_CNT'])?>회
                    	<span style='float:right'>
                        	<button type="button" class="btn btn-xs" onclick="pt_chk_auto('<?php echo $r['BUY_EVENT_SNO']?>');"><i class="fas fa-chevron-right"></i></button>
                        </span>
                    </div>
                    <span style="font-size:0.8rem;"><strong><i class="far fa-calendar-alt"></i> <?php echo $r['ATTD_DATETM']?></strong></span> 
                    <hr>
                <?php endforeach;
                else:
                ?>
				<span style='font-size:0.9rem;'>수업 입장이 없습니다.</span>
				<?php endif;?>
				</div>
                <!-- CARD BODY [END] -->
                			
			</div>
			
			
		</div>
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ############################## MODAL [ END ] ###################################### -->

<div class="overlay">
    <div class="row">
    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area">
                <button type="button" class="close" id="bottom-menu-close" style="margin-right:10px;margin-top:5px;">&times;</button>
                <br />
                <div id='bottom-content'>
					
					<div class="panel-body" style="display: flex; flex-direction: column; height: calc(100vh - 120px); position: relative;">
						<form id='form_pt_chk'>
						<div style="margin-bottom: 10px;">
							<button type="button" class="btn btn-info btn-sm col-6 float-left" id="btn_clas_ok" style="margin-bottom:7px;">수업체크</button>
							<button type="button" class="btn btn-warning btn-sm col-6 float-right" id="btn_clas_cancel" style="margin-bottom:7px;">수업취소</button>
						</div>
						<input type='hidden' name='buy_sno' id='pt_chk_buy_sno' />

                        <div class="direct-chat-messages" id="clas_msg" style="flex: 1; overflow-y: auto; padding-bottom: 60px;">
                        
                        	<!-- 
                        	<div class="direct-chat-msg">
                                <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-left">강사1 강사</span>
                                <span class="direct-chat-timestamp float-right">2024-08-27 11:23:22</span>
                                </div>
                                <div class="direct-chat-text" style='font-size:0.8rem;'>
                                충분해요. 저를 믿고 따라오시면 됩니다. 화이팅입니다.
                                </div>
                            </div>
                            
                        	<div class="direct-chat-msg right">
                                <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-right">홍길동 회원</span>
                                <span class="direct-chat-timestamp float-left">2024-08-27 11:23:22</span>
                                </div>
                                <div class="direct-chat-text" style='font-size:0.8rem;'>
                                에고 정말 그래도 될까요 ? 제가 아직 체력이 되지 않아서.
                                </div>
                            </div>
                             -->
                        
                        </div>
                        
						<div class="input-group input-group-sm" style="position: absolute; bottom: 0; left: 0; right: 0; padding: 10px; background-color: #fff; border-top: 1px solid #ddd;">
                        	<input type="text" class="form-control" placeholder="수업내용" name="clas_conts" id="clas_conts">
                        	<span class="input-group-append">
                            	<button type="button" class="btn btn-info btn-flat" id="btn_clas_comment">입력</button>
                            </span>
                    	</div>
                    	</form>
                    </div>
					
					
					
                </div>
            </div>
    	</div>
    </div>
</div>

<div class="overlay2">
    <div class="row">
    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area">
    			<form name="form_tmem_payment" id="form_tmem_payment" method="post" action="/api/tattdmem">
                    <button type="button" class="close" id="bottom-menu-close2" style="margin-right:10px;margin-top:5px;">&times;</button>
                    <br />
                    <div class='bottom-title text-center'>수업출석회원 검색</div>
                    <div class='bottom-content' style='margin-top:15px;'>
                    
                        <div class="card card-success">
                        <div class="panel-body">
                        
                        	<input class="form-control form-control-lg" type="text" name="ss_ymd" id="ss_ymd" placeholder="검색일" readonly value="<?php echo $ss_ymd?>" >
                        
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

<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

function btn_search()
{
	var ssymd = $('#ss_ymd').val();
	location.href="/api/tattdmem/"+ssymd;
}

function search_detail()
{
	$('#ss_ymd').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
    });
    
	$('.overlay').hide();
	$('.overlay2').show();
	
 	var h_size = $(window).height();
  	var c_size = h_size - 200;
  	$('#bottom-menu-area2').css("height",h_size+"px");
 	$('.content').addClass('modal-open');
}

$("#bottom-menu-close2").click(function(){
	$('.content').removeClass('modal-open');
});

function pt_chk(buy_sno)
{
	$('.overlay').show();
	$('.overlay2').hide();
	
	var h_size = $(window).height();
  	var c_size = h_size - 250; // Adjusted for input at bottom
  	$('#bottom-menu-area').css("height",h_size+"px");
  	$('.direct-chat-messages').css("height",c_size+"px");
 	$('.content').addClass('modal-open');
 	
 	$('#pt_chk_buy_sno').val(buy_sno);
 	
 	var params = "buy_sno="+buy_sno;
    jQuery.ajax({
        url: '/api/ajax_clas_msg',
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
				var cmsg = '';
				
				json_result['msg_list'].forEach(function (r,index) {
				
					cmsg += "";
					
					if ( r['MEM_DV'] == 'T' )
					{
cmsg += "<div class='direct-chat-msg'>";
cmsg += "    <div class='direct-chat-infos clearfix'>";
cmsg += "    <span class='direct-chat-name float-left'>"+ r['STCHR_NM'] +" 강사</span>";
cmsg += "    <span class='direct-chat-timestamp float-right'>"+ r['CRE_DATETM'] +"</span>";
cmsg += "    </div>";
cmsg += "    <div class='direct-chat-text' style='font-size:0.8rem;'>";
cmsg += r['CLAS_DIARY_CONTS'];
cmsg += "    </div>";
cmsg += "</div>";					
					} else 
					{
cmsg += "<div class='direct-chat-msg right'>";
cmsg += "    <div class='direct-chat-infos clearfix'>";
cmsg += "    <span class='direct-chat-name float-right'>"+ r['MEM_NM'] +" 회원</span>";
cmsg += "    <span class='direct-chat-timestamp float-left'>"+ r['CRE_DATETM'] +"</span>";
cmsg += "    </div>";
cmsg += "    <div class='direct-chat-text' style='font-size:0.8rem;'>";
cmsg += r['CLAS_DIARY_CONTS'];
cmsg += "    </div>";
cmsg += "</div>";					
					}
				});
				
				$('#clas_msg').html(cmsg);
				
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
		location.href='/login';
		return;
    });
 	
 	
}

$("#btn_clas_ok").click(function(){
	var params = $("#form_pt_chk").serialize();
    jQuery.ajax({
        url: '/api/ajax_clas_ok',
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
				alert( json_result['msg'] );
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
		location.href='/login';
		return;
    });
});

$("#btn_clas_cancel").click(function(){
	var params = $("#form_pt_chk").serialize();
    jQuery.ajax({
        url: '/api/ajax_clas_cancel',
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
				alert( json_result['msg'] );
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
		location.href='/login';
		return;
    });
});


$("#btn_clas_comment").click(function(){

	if ( $('#clas_conts').val() == '' )
	{
		alertToast('error','수업 내용을 입력하세요.');
		return;
	}
	
	var params = $("#form_pt_chk").serialize();
    jQuery.ajax({
        url: '/api/ajax_clas_diary_insert_proc',
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
				$('#clas_conts').val('');
				pt_chk($('#pt_chk_buy_sno').val());
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



$(".bottom-menu").click(function(){
	$(".overlay").show();
 	var h_size = $(window).height();
 	$('#bottom-menu-area').css("height",h_size+"px");
 	
// 	var h_re = $(window).scrollTop() + 100;
// 	$(".overlay").animate({top: h_re+'px'});
});

$("#bottom-menu-close").click(function(){
	$('.content').removeClass('modal-open');
});

// ===================== Modal Script [ START ] ===========================

$("#script_modal_default").click(function(){
	$("#modal-default").modal("show");
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