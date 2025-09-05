<style>
.domcy-box {
  margin-top:10px;
  display: flex;
  align-items: center; /* 세로 가운데 정렬 */
  justify-content: center; /* 가로 가운데 정렬 */
  position: relative;
  width: 100%;
  height: 70px;
  border: 1px solid #eee;
  border-radius: 5px; /* 모서리를 5px 둥글게 처리 */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06); /* 그림자 추가 */
  background-color: #fff; /* 배경색 추가 (필요시) */
}

.domcy-content-left,
.domcy-content-right {
  width: 50%;
  text-align: center;
}

.domcy-box::before {
  content: '';
  position: absolute;
  height: 70%; /* 선의 높이를 90%로 설정 */
  width: 1px; /* 기본 너비 */
  background-color: #aaa; /* 선 색상 */
  left: 50%; /* 가로 정 가운데 */
  top: 50%; /* 세로 정 가운데 기준 */
  transform: translate(-50%, -50%) scaleX(0.5); /* 너비를 0.5로 축소 */
  transform-origin: center; /* 축소 기준점 설정 */
}
</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
   <div class="row">   
     <div class="new-title">결제내역 현황</div>	
     <div class="newbx01">
     ※ 환불을 원하시면 해당 센터에 문의 하세요.
     </div>	
  </div>

	
        
        <div class="row ama-header2"  style="display:none">
	
            <div class="col-md-12">
            
            	<div class="stats-container col-md-12">
                    <div class="stat-item">
                        <div class="number reservation" style='font-size:1.2rem;'><?php echo number_format($sum1[0]['sum_cost'])?>원</div>
                        <div class="number-label">결제금액</div>
                    </div>
                    <div class="stat-item">
                        <div class="number used" style='font-size:1.2rem;'><?php echo number_format($sum2[0]['sum_cost'])?>원</div>
                        <div class="number-label">환불금액</div>
                    </div>
                </div>
            
            </div>
        </div>
        
        <div class="row" style="display:none">
            <div class="col-12">
            	<div class="domcy-box">
                    <div class="domcy-content-left">
                        <div>결제금액</div>
                        <div><?php echo number_format($sum1[0]['sum_cost'])?>원</div>
                    </div>
                    <div class="domcy-content-right">
                        <div>환불금액</div>
                        <div><?php echo number_format($sum2[0]['sum_cost'])?>원</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
            	<div class='a-title'>결제 현황</div>
            	
            	<?php foreach($list1 as $r) :?>
            	<div class="a-list">
                    <div class="a-item">
                    	<div class='a-item-sec item-center '>
                    		[ <?php echo $r['SELL_EVENT_NM']?> ] &nbsp;
                    		<span class="item-sky bold"><?php echo number_format($r['PAYMT_AMT'])?> 원</span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec item-center '>
                    		결재일
                    		<span class="item-sky"><?php echo $r['CRE_DATETM']?></span>
                    	</div>
                    	<div class="mt10"></div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<div class='item-btn-area'>
                    			<div class="cate bga-cate"><?php echo $sDef['SALES_DV_RSON'][$r['SALES_DV_RSON']]?></div>
                    		</div>
                    		<div class='item-btn-area' style="display:none;">
                    			<div class="cate bga-blue"><?php echo $sDef['PAYMT_CHNL'][$r['PAYMT_CHNL']]?></div>
                    			<div class="cate bga-purple"><?php echo $sDef['PAYMT_MTHD'][$r['PAYMT_MTHD']]?></div>
                    		</div>
                    	</div>
                        <div class="a-item-line"></div>
                    </div>
                </div>
                <?php endforeach;?>
            	
            </div>
        </div>
        
        <div class="row" style="display:none">
            <div class="col-md-12">
            	<div class='a-title'>환불 현황</div>
            	
            	<?php foreach($list2 as $r) :?>
            	<div class="a-list3">
                    <div class="a-item">
                    	<div class='a-item-sec item-center '>
                    		<?php echo $r['SELL_EVENT_NM']?>
                    		<span class="item-sky"><?php echo number_format($r['PAYMT_AMT'])?> 원</span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec item-center '>
                    		결재일
                    		<span class="item-sky"><?php echo $r['CRE_DATETM']?></span>
                    	</div>
                    	<div class="a-item-line"></div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<div class='item-btn-area'>
                    			<div class="cate bga-cate"><?php echo $sDef['SALES_DV_RSON'][$r['SALES_DV_RSON']]?></div>
                    		</div>
                    		<div class='item-btn-area' style="display:none;">
                    			<div class="cate bga-blue"><?php echo $sDef['PAYMT_CHNL'][$r['PAYMT_CHNL']]?></div>
                    			<div class="cate bga-purple"><?php echo $sDef['PAYMT_MTHD'][$r['PAYMT_MTHD']]?></div>
                    		</div>
                    	</div>
                    </div>
                </div>
                <?php endforeach;?>
            	
            </div>
       </div>

	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ============================= [ modal-default START ] ======================================= -->	

<!-- ============================= [ modal-default END ] ======================================= -->
<!-- ############################## MODAL [ END ] ###################################### -->
	
</section>

<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

 document.addEventListener("scroll", () => {
    const scrollY = window.scrollY;
    document.body.classList.toggle("scrolled", scrollY > 120);
  });

$(".bottom-menu").click(function(){
	$(".overlay").show();
 	var h_size = $(window).height();
 	$('#bottom-menu-area').css("height",h_size+"px");
});

$("#bottom-menu-close").click(function(){
	$(".overlay").hide();
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