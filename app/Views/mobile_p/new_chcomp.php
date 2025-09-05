<style>
</style>

<!-- Main content -->
<section class="content">
   <div class="row">   
     <div class="new-title">지점 리스트</div>	
  </div>
	
		<div class="row">
            <div class="col-md-12">
            	
                
                <!-- CARD BODY [START] -->
                <?php foreach ($comp_list as $r) :?>
                <?php
                $disp_mem_stat = "";
                if($r['MEM_STAT'] == 01) $disp_mem_stat="이용중";
                if($r['MEM_STAT'] == 90) $disp_mem_stat="종료됨";
                
                ?>
                <div class="a-list">
                    <div class="a-item">
                    	<div class='a-item-sec item-center item-bold ft-sky'>
                    		<div class="number-label2 bga-sky mr5 mb5"><!--종료시 bga-cate-->
                                <?php echo $disp_mem_stat?> 
                            </div> 
                    		<!-- <span class="item-bold ft-default">추천강사 : 이시영</span> -->
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<span class="ft-default">
                    			<span class="">- <?php echo $r['BCOFF_NM']?></span>
                    		</span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<span class="ft-default">
                    			<span class="">- <?php echo $r['BCOFF_ADDR']?></span>
                    		</span>
                    	</div>
                    	<div class="a-item-line"></div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<span class="ft-default">
                    			<span class=""><i class="fas fa-phone"></i> <?php echo disp_phone($r['BCOFF_TELNO'])?></span>
                    		</span>
                    		
                    		<?php if($r['MEM_STAT'] == 01) :?>
                    		<div class='item-btn-area'>
                    			<div class="btn bga-purple" onclick="setcomp('<?php echo $r['COMP_CD']?>','<?php echo $r['BCOFF_CD']?>');">설정하기</div>
                    		</div>
                    		<?php else :?>
                    		<div class='item-btn-area'>
                    			<div class="btn bga-sky" onclick="setcomp('<?php echo $r['COMP_CD']?>','<?php echo $r['BCOFF_CD']?>');">설정하기</div>
                    		</div>
                    		<?php endif;?>
                    		
                    		
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

function setcomp(comp_cd,bcoff_cd)
{
    // 로딩 표시 (선택사항)
    // showLoading();
    
    var params = "comp_cd=" + comp_cd + "&bcoff_cd=" + bcoff_cd;
    
    jQuery.ajax({
        url: '/api/setcomp_proc/' + comp_cd + '/' + bcoff_cd,
        type: 'GET',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
            // hideLoading();
            
            if (result.substr(0,8) == '<script>') {
                alert('로그인이 만료 되었습니다. 다시 로그인해주세요.');
                location.href='/login';
                return;
            }
            
            try {
                json_result = $.parseJSON(result);
                
                if (json_result['result'] == 'true') {
                    // 성공시 확인 버튼이 있는 팝업 표시 후 이동
                    var redirectUrl = json_result['redirect_url'] || '/api/mmmain/1';
                    
                    // SweetAlert2를 사용한 확인 버튼이 있는 팝업
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '알림',
                            text: json_result['msg'],
                            confirmButtonText: '확인',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.href = redirectUrl;
                            }
                        });
                    } else {
                        // SweetAlert2가 없는 경우 일반 alert 사용
                        alert(json_result['msg']);
                        location.href = redirectUrl;
                    }
                } else {
                    // 실패 토스트 팝업 표시
                    if (typeof alertToast === 'function') {
                        alertToast('error', json_result['msg']);
                    } else {
                        alert(json_result['msg']);
                    }
                }
            } catch (e) {
                // JSON 파싱 오류
                if (typeof alertToast === 'function') {
                    alertToast('error', '처리 중 오류가 발생했습니다. 다시 시도해 주세요.');
                } else {
                    alert('처리 중 오류가 발생했습니다. 다시 시도해 주세요.');
                }
            }
        },
        error: function(xhr, status, error) {
            // hideLoading();
            
            // 통신 실패 토스트 팝업 표시
            if (typeof alertToast === 'function') {
                alertToast('error', '네트워크 오류가 발생했습니다. 다시 시도해 주세요.');
            } else {
                alert('네트워크 오류가 발생했습니다. 다시 시도해 주세요.');
            }
        }
    });
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