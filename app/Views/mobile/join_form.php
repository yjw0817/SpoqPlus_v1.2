<style>
    #hamb_menu { display:none !important; }
</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
	
		<div class="row">
			<div class="col-md-12">
                
                <div class="row">
        			<div class="col-md-12">
        				<div class="panel-body">
                    	<form name="form_join" id="form_join" method="post" enctype="multipart/form-data" action="/api/join_form_proc">
                    	<input type="hidden" name="id_chk" id="id_chk" value="N" />
                    	<!-- 
                        <div class="form-group">
                            <label for="inputName">회원 사진</label>
                            <input type="file" id="mem_photo" />
                            
                            <div class="img-container" style="max-height:450px">
                            	<img src="" />
                            </div>
                        </div>
                        
                        <button type="button" class='btn btn-block btn-info btn-sm p-3' onclick="tmem_upload();">회원사진 올리기</button>
                         -->
                        <div class="form-group">
                            <label for="inputName">회원 아이디</label>
                            <input type="text" id="mem_id" name="mem_id" class="form-control" value="" onkeyup="id_change(this);">
                            <button type="button" class='btn  btn-info btn-sm p-2 float-right' id="btn_code_chk">아이디중복확인</button>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputName">회원 비밀번호</label>
                            <input type="password" id="mem_pwd" name="mem_pwd" class="form-control" placeholder="비밀번호 변경시에만 입력해주세요.">
                        </div>
                        
                        <div class="form-group">
                            <label for="inputName">회원 이름</label>
                            <input type="text" id="mem_nm" name="mem_nm" class="form-control" value="<?php echo $chkdata['mem_nm']?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputName">회원 생년월일</label>
                            <input type="text" id="bthday" name="bthday" class="form-control" value="<?php echo $chkdata['mem_birth']?>" readonly data-inputmask="'mask': ['9999/99/99']" data-mask >
                        </div>
                        
                        <div class="form-group">
                            <label for="inputName">회원 성별</label>
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="radioGrpCate1" name="mem_gendr" value="M" <?php if($chkdata['mem_gendr'] == "M") {?> checked <?php } ?> >
                                <label for="radioGrpCate1">
                                	<small>남</small>
                                </label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="radioGrpCate2" name="mem_gendr" value="F" <?php if($chkdata['mem_gendr'] == "F") {?> checked <?php } ?> >
                                <label for="radioGrpCate2">
                                	<small>여</small>
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputName">회원 전화번호</label>
                            <input type="text" id="mem_telno" name="mem_telno" class="form-control" value="<?php echo $chkdata['mem_phone']?>" readonly data-inputmask="'mask': ['99-9999-999[9]','999-9999-9999']" data-mask>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputName">회원 주소</label>
                            <input type="text" id="mem_addr" name="mem_addr" class="form-control" value="">
                        </div>
                        
                        <div id='id_chk_true' style='display:none'>
                        	<button type="button" id="mem_insert_btn" class='btn btn-block btn-info btn-sm p-3'>회원가입하기</button>
                        </div>
                        <div id='id_chk_false'>
                        	<button type="button" class='btn btn-block btn-warning btn-sm p-3'>아이디중복 확인을 해주세요</button>
                        </div>
                        
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

function id_change(t)
{
	$('#id_chk').val("N");
	$('#id_chk_false').show();
	$('#id_chk_true').hide();
	
	
}

$('#btn_code_chk').click(function(){
	if ( $('#mem_id').val() == "" )
	{
		alertToast('error','아이디를 입력하세요');
		return;
	}

	var params = "mem_id="+$('#mem_id').val();
    jQuery.ajax({
        url: '/api/ajax_id_chk',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
            json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				$('#id_chk').val("Y");
            	$('#id_chk_false').hide();
            	$('#id_chk_true').show();			
			} else 
			{
				alertToast('error','아이디가 중복되었습니다. 다른 아이디를 입력하세요');
			}
        }
    });
});

$('#mem_insert_btn').click(function(){
	// 실패일 경우 warning error success info question
	
	if ( $('#id_chk').val() == "N" )
	{
		alertToast('error','아이디 중복체크를 해주세요');
		return;
	}
	
	if ( $('#mem_id').val() == "" )
	{
		alertToast('error','아이디를 입력하세요');
		return;
	}
	
	if ( $('#mem_pwd').val() == "" )
	{
		alertToast('error','비밀번호를 입력하세요');
		return;
	}
	
	if ( $('#mem_nm').val() == "" )
	{
		alertToast('error','이름을 입력하세요');
		return;
	}
	
	if ( $('#bthday').val() == "" )
	{
		alertToast('error','생년월일을 입력하세요');
		return;
	}
	
	if ( $('#mem_telno').val() == "" )
	{
		alertToast('error','전화번호를 입력하세요');
		return;
	}
	
	if ( $("input:radio[name=mem_gendr]:checked").length == 0 )
	{
		alertToast('error','성별을 선택하세요.');
		return;
	}
	
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >회원을 등록하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		var f = document.form_join;
    		f.submit();
    		//$('#form_join').submit();
    	}
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
	$(".overlay").hide();
// 	var h_size = $(window).height() + $(window).scrollTop();
// 	$( ".overlay" ).animate({
//     top : h_size+'px'
//       }, {
//         duration: 300,
//         complete: function() {
//           $(".overlay").hide();
//         }
//       });
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