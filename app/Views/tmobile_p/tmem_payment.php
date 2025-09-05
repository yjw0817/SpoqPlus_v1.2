<style>
#status-info {
  position: sticky;
  top: 57px;
  height: 125px;
  z-index: 998;
}

.row {
    background-color:#ffffff !important;
}

.container-fluid {
    background-color:#ffffff !important;
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

</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
 <div class="new-title"> PT(판매) 매출</div>
<section class="content">
	
	
        <div class="row" id='status-info'>
            <div class="col-lg-3 col-6">
                <div class="small-box2 bg-info" >
                <div class="inner">
                <h3><?php echo number_format($sum1[0]['sum_cost'])?><sup style="font-size: 20px"> 원</sup></h3>
                 <h6>결제금액</h6>
                </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box2 bg-danger">
                <div class="inner">
                <h3><?php echo number_format($sum2[0]['sum_cost'])?><sup style="font-size: 20px"> 원</sup></h3>
                <h6>환불금액</h6>
                </div>

                </div>
            </div>
            
            <div class="col-md-12">
	    		<div class='float-right' style='margin-right:15px;' onclick="search_detail();">
	    			<span style='margin-right:15px;font-size:0.9rem'>
	    			<?php echo $ss_yy?>년 | <?php echo $ss_mm?>월
	    			</span>
	    			<i class="fas fa-chevron-down"></i>
	    		</div>
	    	</div>
            
        </div>
	
		<div class="row mt40 p-2" >
			<div class="col-md-12">
                
                <div class="card card-info">
				
    				<!-- CARD HEADER [START] -->
    				<div class="card-header">
    					<h3 class="card-title">결제 현황</h3>
    				</div>
    				<!-- CARD HEADER [END] -->
    			
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                        	<?php if (count($list1) > 0) :?>
                            <?php foreach($list1 as $r) :?>
                            <li class="item">
                                <div>
                                <a href="javascript:void(0)" class="product-title">
                                	<?php echo $r['SELL_EVENT_NM']?>
                                	<span class="badge float-right"><?php echo $sDef['PAYMT_CHNL'][$r['PAYMT_CHNL']]?> | <?php echo $sDef['PAYMT_MTHD'][$r['PAYMT_MTHD']]?></span>
                                </a>
                                <span class="product-description">
                                	<?php disp_mem_info($r['MEM_SNO']);?>
                                	<?php echo $r['MEM_NM']?> 회원님 (<?php echo $sDef['SALES_DV_RSON'][$r['SALES_DV_RSON']]?>)
                                	<span class="badge badge-info float-right"><?php echo number_format($r['PAYMT_AMT'])?></span>
                                </span>
                                <span style="font-size:0.8rem;color:blue;float:right">
                                <?php echo $r['CRE_DATETM']?>
                                </span>
                                </div>
                            </li>
                            <?php endforeach;?>
                            <?php else : ?>
                    			<li class="item" style='font-size:0.9rem;'>결제한 내역이 없습니다.</li>
							<?php endif; ?>
                        
                        </ul>
                    </div>
                    
                    			
				</div>
                			
			</div>
		</div>
		

		
		<div class="row mt20 p-2" >
			<div class="col-md-12">
                
                <div class="card card-danger">
				
    				<!-- CARD HEADER [START] -->
    				<div class="card-header">
    					<h3 class="card-title">환불 현황</h3>
    				</div>
    				<!-- CARD HEADER [END] -->
    			
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                        	<?php if (count($list2) > 0) :?>
                            <?php foreach($list2 as $r) :?>
                            <li class="item">
                                <div>
                                <a href="javascript:void(0)" class="product-title">
                                	<?php echo $r['SELL_EVENT_NM']?>
                                	<span class="badge float-right"><?php echo $sDef['PAYMT_CHNL'][$r['PAYMT_CHNL']]?> | <?php echo $sDef['PAYMT_MTHD'][$r['PAYMT_MTHD']]?></span>
                                </a>
                                <span class="product-description">
                                	<?php disp_mem_info($r['MEM_SNO']);?>
                                	<?php echo $r['MEM_NM']?> 회원님 (<?php echo $sDef['SALES_DV_RSON'][$r['SALES_DV_RSON']]?>)
                                	<span class="badge badge-danger float-right"><?php echo number_format($r['PAYMT_AMT'])?></span>
                                </span>
                                <span style="font-size:0.8rem;color:red;float:right">
                                <?php echo $r['CRE_DATETM']?>
                                </span>
                                </div>
                            </li>
                            <?php endforeach;?>
                        	<?php else : ?>
                    			<li class="item" style='font-size:0.9rem;'>환불한 내역이 없습니다.</li>
							<?php endif; ?>
                        </ul>
                    </div>
                    
                    			
				</div>
                			
			</div>
		</div>

	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ############################## MODAL [ END ] ###################################### -->
<div class="overlay">
    <div class="row"><div class="new-title"> PT(판매) 매출 검색</div>
    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area">
    			<form name="form_tmem_payment" id="form_tmem_payment" method="post" action="/api/tmem_payment">
                    <button type="button" class="close" id="bottom-menu-close" 
                     style="margin-right:20px;margin-top:-35px; color:#fff">&times;</button>
                    <br />
                 
                    <div class='bottom-content'>
                    
                        <div class="card card-success">
                        <div class="card-body">
                        
                        <select class="text-center" style="width:99%;height:50px;margin-bottom:10px;" name="ss_yy" id="ss_yy">
                        	<?php for($i=date('Y');$i>2020;$i--) :?>
                        	<option value="<?php echo $i?>" <?php if ($i == $ss_yy) {?> selected <?php } ?> ><?php echo $i?>년</option>
                        	<?php endfor; ?>
    					</select>
    					
    					<select class="text-center" style="width:99%;height:50px;margin-bottom:10px;" name="ss_mm" id="ss_mm">
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
                        
                        <button type="button" class='btn btn-block bga-main ft-white btn-sm p-3 bottom-menu' onclick="btn_search();">검색하기</button>
                    
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
	var yy = $('#ss_yy').val();
	var mm = $('#ss_mm').val();
	
	location.href="/api/tmem_payment/"+yy+"/"+mm;
// 	$('#form_tmem_payment').submit();
}

function search_detail()
{
 	var h_size = $(window).height();
  	var c_size = h_size - 200;
  	$('#bottom-menu-area').css("height",h_size+"px");
 	$('.content').addClass('modal-open');
}

$(".bottom-menu").click(function(){
	$(".overlay").show();
 	var h_size = $(window).height();
 	$('#bottom-menu-area').css("height",h_size+"px");
});

$("#bottom-menu-close").click(function(){
	$('.content').removeClass('modal-open');
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