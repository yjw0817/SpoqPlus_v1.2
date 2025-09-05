<style>
#status-info {
  position: sticky;
  top: 57px;
  height: 140px;
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
<section class="content">
	<div class="container-fluid">
	
	   <div class="row" id='status-info'>
            <div class="col-lg-3 col-12">
                <div class="small-box bg-info" style='margin:5px'>
                <div class="inner">
                <h3><span id='select_mem_sno_count'><?php echo count($send_mem_sno)?></span><sup style="font-size: 20px">명</sup></h3>
                <p>보내기 선택한 회원 수</p>
                </div>
                <div class="icon">
                <i class="fas fa-shopping-cart"></i>
                </div>
                </div>
            </div>
            
            <div class="col-md-12">
	    		<!-- <div class='float-left' style='margin-left:10px;'><i class="fas fa-search"></i></div> -->
	    		<div class='float-right' style='margin-right:10px;' onclick="search_detail();">
	    			<!-- <span style='margin-right:15px;font-size:0.9rem'>검색조건을 선택하세요</span> -->
	    			<span style='margin-right:15px;font-size:0.9rem'>
	    			<?php echo $search_val['sch_end_sdate']?> | 
	    			<?php 
	    			foreach ($acc_rtrct_dv as $r => $key) :
                	   if($r != '') :
						  if($search_val['acc_rtrct_dv'] == $r) { echo $key; }
					   endif;
					endforeach; ?> | 
					<?php 
					foreach ($acc_rtrct_mthd as $r => $key) :
                	   if($r != '') :
						  if($search_val['acc_rtrct_mthd'] == $r) { echo $key; }
					   endif;
					endforeach; ?> |
					<?php 
					foreach ($sch_cate1 as $r) :
					   if($search_val['search_cate1'] == $r['1RD_CATE_CD']) { echo $r['CATE_NM']; }
        			endforeach; ?>
        			<?php 
					foreach ($sch_cate2 as $r) :
					   if($search_val['search_cate2'] == $r['2RD_CATE_CD']) { echo $r['CATE_NM']; }
        			endforeach; ?>
	    			</span>
	    			<i class="fas fa-chevron-down"></i>
	    		</div>
	    	</div>
       </div>
       
		<div class="row" style='margin-top:20px'>
			<div class="col-md-12">
                
                <div class="card card-info">
				
    				<!-- CARD HEADER [START] -->
    				<div class="card-header">
    					<h3 class="card-title">보낼상품 현황</h3>
    				</div>
    				<!-- CARD HEADER [END] -->
    			
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                        	<?php 
								foreach($buy_event_list as $r) :
								    $backColor = ""; // 이용중
								    if ($r['EVENT_STAT'] == "01") $backColor = "#cfecf0"; // 예약됨
								    if ($r['EVENT_STAT'] == "99") $backColor = "#f7d5d9"; // 종료됨
								    
								    $mem_mem_chk = "";
								    if (in_array($r['MEM_SNO'], $send_mem_sno)) $mem_mem_chk = "checked";
								    
							?>
                            <li class="item">
                                <div class="product-img" style='border-radius:6px;padding:3px;width:45px;margin-top:20px;'>
                                	<div class="icheck-primary d-inline" style="margin-left:10px;">
                                        <input type="checkbox" name="send_chk[]" id="send_chk_<?php echo $r['BUY_EVENT_SNO']?>" value="<?php echo $r['MEM_SNO']?>" onclick="p_chk(this);" <?php echo $mem_mem_chk?>>
                                        <label for="send_chk_<?php echo $r['BUY_EVENT_SNO']?>">
                                        	<small></small>
                                        </label>
                                    </div>
                                </div>
                                <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">
                                	<span class="badge badge-success"><?php echo $sDef['ACC_RTRCT_MTHD'][$r['ACC_RTRCT_MTHD']]?></span>
                                	<?php echo $r['SELL_EVENT_NM']?>
                                	
                                	<!-- <span class="badge float-right" style="text-decoration: line-through;color:red">300,000</span>  -->
                                </a>
                                <span class="product-description">
                                	
                                	<?php if($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") :?>
									<?php
									   $sum_clas_cnt = $r['MEM_REGUL_CLAS_LEFT_CNT'] + $r['SRVC_CLAS_LEFT_CNT']; // 총 수업 남은 횟수
									?>
									<span class='text-center'><?php echo $sum_clas_cnt?></span>
									<?php else :?>
									<span class='text-center'><?php echo disp_produnit($r['USE_PROD'],$r['USE_UNIT'])?></span>
									<?php endif ;?>
                                	<span style="font-size:0.8rem;color:green">(<?php echo $r['MEM_NM']?>)</span>
                                	<span class="badge badge-info float-right"><?php echo number_format($r['REAL_SELL_AMT'])?></span>
                                </span>
                                <span style="font-size:0.8rem;color:green">
                                2024-05-01 ~ 2024-08-01
                                </span>
                                </div>
                            </li>
                        
                        	<?php 
							endforeach;
							?>
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

<div class="overlay">
    <div class="row">
    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area">
    			<form name="form_buy_event_manage1" id="form_buy_event_manage1" method="post" action="/api/teventsend1">
                    <button type="button" class="close" id="bottom-menu-close" style="margin-right:10px;margin-top:5px;">&times;</button>
                    <br />
                    <div class='bottom-title text-center'>보내기조건 검색</div>
                    <div class='bottom-content' style='margin-top:15px;'>
                    
                        <div class="card card-success">
                        <div class="card-body">
                        
                        
	                	<input type="text" class="datepp" style="width:100%;height:50px;margin-bottom:10px;" name="sch_end_sdate" id="sch_end_sdate" value="<?php echo $search_val['sch_end_sdate']?>" >
                        
                        
                        <select class=" " style="width:100%;height:50px;margin-bottom:10px;" name="acc_rtrct_dv" id="acc_rtrct_dv">
                        		<!-- <option>출입구분</option> -->
                    	<?php foreach ($acc_rtrct_dv as $r => $key) : ?>
                    	<?php if($r != '') :?>
    						<option value="<?php echo $r?>" <?php if($search_val['acc_rtrct_dv'] == $r) {?> selected <?php } ?> ><?php echo $key?></option>
    					<?php endif; ?>
    					<?php endforeach; ?>
    					</select>
    					
    					<select class=" " style="width:100%;height:50px;margin-bottom:10px;" name="acc_rtrct_mthd" id="acc_rtrct_mthd">
                    		<!-- <option>출입조건</option> -->
                    	<?php foreach ($acc_rtrct_mthd as $r => $key) : ?>
                    	<?php if($r != '') :?>
    						<option value="<?php echo $r?>" <?php if($search_val['acc_rtrct_mthd'] == $r) {?> selected <?php } ?> ><?php echo $key?></option>
    					<?php endif; ?>
    					<?php endforeach; ?>
    					</select>
    					
    					
    					<select class=" " style="width:100%;height:50px;margin-bottom:10px;" name="search_cate1" id="search_cate1">
                    		<option value="">대분류 선택</option>
                    	<?php foreach ($sch_cate1 as $r) : ?>
    						<option value="<?php echo $r['1RD_CATE_CD']?>" <?php if($search_val['search_cate1'] == $r['1RD_CATE_CD']) {?> selected <?php } ?> ><?php echo $r['CATE_NM']?></option>
    					<?php endforeach; ?>
    					</select>
	                	<select class=" " style="width:100%;height:50px;margin-bottom:10px;" name="search_cate2" id="search_cate2">
                    		<option value="">중분류 선택</option>
                    	<?php foreach ($sch_cate2 as $r) : ?>
    						<option value="<?php echo $r['2RD_CATE_CD']?>" <?php if($search_val['search_cate2'] == $r['2RD_CATE_CD']) {?> selected <?php } ?> ><?php echo $r['CATE_NM']?></option>
    					<?php endforeach; ?>
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

<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

$('#search_cate1').change(function(){
	if ( $('#search_cate2').val() != '' )
	{
		alertToast('error',"대분류를 선택하시면 중분류를 선택 할 수 없습니다. 중분류 조건을 삭제합니다.");
	}
	
	$('#search_cate2').val('');
});

$('#search_cate2').change(function(){
	if ( $('#search_cate1').val() != '' )
	{
		alertToast('error',"중분류를 선택하시면 대분류를 선택 할 수 없습니다. 대분류 조건을 삭제합니다.");
	}
	
	$('#search_cate1').val('');
});

function btn_search()
{
	if ( $('#search_cate1').val() == '' && $('#search_cate2').val() == '' )
	{
		alertToast('error',"대분류, 중분류중 하나의 조건을 선택하세요");
		return;
	}

	$('#form_buy_event_manage1').submit();
}

function search_detail()
{
 	var h_size = $(window).height();
  	var c_size = h_size - 200;
  	$('#bottom-menu-area').css("height",h_size+"px");
 	$('.content').addClass('modal-open');
}

function p_chk(t)
{
	var chk_tf = $(t).prop('checked');
	var params = "send_mem_sno="+$(t).val()+"&chk_tf="+chk_tf;
	jQuery.ajax({
        url: '/api/ajax_send_mem_sno_chk',
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
				//console.log(json_result);
				$('#select_mem_sno_count').text(json_result['send_mem_sno'].length);
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