<style>
#status-info {
  position: sticky;
  top: 57px;
  height: 120px;
  z-index: 998;
}

.row {
    background-color:#ffffff !important;
}

.container-fluid {
    background-color:#ffffff !important;
}

</style>
<?php
$sDef = SpoqDef();
?>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
	
		<div class="row" style='margin-top:20px'>
			<div class="col-md-12">
                
                <div class="card card-info">
				
    				<!-- CARD HEADER [START] -->
    				<div class="card-header">
    					<h3 class="card-title">추천 상품 리스트</h3>
    				</div>
    				<!-- CARD HEADER [END] -->
    			
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            
                            <?php foreach ($list_event as $r) :?>
                            <li class="item">
                                
                                <div class="">
                                <a href="javascript:void(0)" class="product-title">
                                	
                                	<?php echo $r['SELL_EVENT_NM']?>
                                	<span style='float:right'>
                                    	<button type="button" class="btn btn-xs" onclick="buy_info('<?php echo $user_sno?>','<?php echo $r['SELL_EVENT_SNO']?>');"><i class="fas fa-chevron-right"></i></button>
                                    </span>
                                </a>
                                <span class="product-description" style='margin-top:5px;'>
                                	<span class="badge badge-info"><?php echo $sDef['ACC_RTRCT_DV'][$r['ACC_RTRCT_DV']]?></span>
                                	<span class="badge badge-success"><?php echo $sDef['ACC_RTRCT_MTHD'][$r['ACC_RTRCT_MTHD']]?></span>
                                	<?php if($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") :?>
                                	<?php echo $r['CLAS_CNT']?>회
                                	<?php else:?>
                                	<?php echo disp_produnit($r['USE_PROD'],$r['USE_UNIT'])?>
                                	<?php endif;?>
                                	<span class="badge badge-info float-right"><?php echo number_format($r['SELL_AMT'])?></span>
                                </span>
                                <!-- 
                                <span style="font-size:0.8rem;color:blue">
                                &nbsp;
                                </span>
                                 -->
                                </div>
                            </li>
                            <?php endforeach; ?>
                            
                        </ul>
                    </div>
                    
                    			
				</div>
                			
			</div>
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

	
</section>

<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

function loc1()
{
	location.href="/api/event_buy1";
}

function buy_info(sno,sell_event_sno)
{
	location.href="/api/tmem_send_info/"+sno+"/"+sell_event_sno;
}

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