<style>

</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
	
		<div class="row">
			<div class="col-md-12">
                
                <div class="card card-primary">
                	<!-- 
                    <div class="page-header">
                    	<h3 class="panel-title">회원정보</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            	<i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                     -->
                    <div class="panel-body">
                    	<form id="form_tmodify">
                        <div class="form-group">
                            <label for="inputName">강사 아이디</label>
                            <input type="text" id="mem_id" name="mem_id" class="form-control" value="<?php echo $tinfo['MEM_ID']?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputName">강사 비밀번호</label>
                            <input type="text" id="mem_pwd" name="mem_pwd" class="form-control" placeholder="비밀번호 변경시에만 입력해주세요.">
                        </div>
                        
                        <div class="form-group">
                            <label for="inputName">강사 이름</label>
                            <input type="text" id="mem_nm" name="mem_nm" class="form-control" value="<?php echo $tinfo['MEM_NM']?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="inputName">강사 생년월일</label>
                            <input type="text" id="bthday" name="bthday" class="form-control" value="<?php echo $tinfo['BTHDAY']?>" data-inputmask="'mask': ['9999/99/99']" data-mask >
                        </div>
                       
                        <div class="form-group">
                            <label for="inputName">강사 성별</label>
                            
                            <div style='margin-top:4px;margin-left:5px;'>
                            	<div class="icheck-primary d-inline">
                                    <input type="radio" id="radioGrpCate1" name="mem_gendr" value="M" <?php if($tinfo['MEM_GENDR'] == "M") {?> checked <?php }?> >
                                    <label for="radioGrpCate1">
                                    	<small>남</small>
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioGrpCate2" name="mem_gendr" value="F" <?php if($tinfo['MEM_GENDR'] == "F") {?> checked <?php }?> >
                                    <label for="radioGrpCate2">
                                    	<small>여</small>
                                    </label>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="form-group">
                            <label for="inputName">강사 전화번호</label>
                            <input type="text" id="mem_telno" name="mem_telno" class="form-control" value="<?php echo $tinfo['MEM_TELNO']?>" data-inputmask="'mask': ['99-9999-999[9]','999-9999-9999']" data-mask>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputName">강사 주소</label>
                            <input type="text" id="mem_addr" name="mem_addr" class="form-control" value="<?php echo $tinfo['MEM_ADDR']?>">
                        </div>
                        
                        <button type="button" class='btn btn-block btn-info btn-sm p-3' onclick="tmem_modify();">강사정보 수정하기</button>
                        </form>
                        <!-- 
                        <div class="form-group">
                            <label for="inputDescription">Project Description</label>
                            <textarea id="inputDescription" class="form-control" rows="4"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputStatus">Status</label>
                            <select id="inputStatus" class="form-control custom-select">
                                <option selected disabled>Select one</option>
                                <option>On Hold</option>
                                <option>Canceled</option>
                                <option>Success</option>
                            </select>
                        </div>
                         -->
                        
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
                <button type="button" class="close"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default"  data-bs-dismiss="modal">Close</button>
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

function tmem_modify()
{
	var params = $("#form_tmodify").serialize();
    jQuery.ajax({
        url: '/api/ajax_tmem_modify_proc',
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