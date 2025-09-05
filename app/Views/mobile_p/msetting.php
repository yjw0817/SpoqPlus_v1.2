<style>


</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12" style='margin-top:50px;'>
				<button id='set_id' style='height:50px' class='btn btn-default btn-default btn-sm btn-block'>아이디/비밀번호로 로그인 설정</button>	
				<!--<button id='set_bio' style='height:50px' class='btn btn-default btn-sm btn-block'>지문,얼굴인식로 로그인 설정</button>-->
				<!--<button id='set_kpad' style='height:50px' class='btn btn-default btn-sm btn-block'>간편비밀번호 로그인 설정</button>-->
			</div>
		</div>
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ############################## MODAL [ END ] ###################################### -->
<form id='form_kpad'>
<textarea style='display:none;' id='spwd' name='spwd'></textarea>
</form>
</section>

<?=$jsinc ?>
<script>

$(function () {
	sitenm = "msetting";
    nbCall_get('logintp');
})

function msetting_get_logintp(r)
{
	if (r == '') $('#set_id').trigger('click');
	if (r == 'bio') $('#set_bio').trigger('click');
	if (r == 'kpad') 
	{
		$('#set_kpad').removeClass('btn-default');
    	$('#set_kpad').addClass('btn-info');
    	
    	$('#set_bio').removeClass('btn-info');
    	$('#set_id').removeClass('btn-info');
    	
    	$('#set_bio').addClass('btn-default');
    	$('#set_id').addClass('btn-default');
	}
}

function msetting_keypad_result(r)
{
	$('#spwd').val(r);
	var params = $("#form_kpad").serialize();
	jQuery.ajax({
        url: '/api/ajax_spwd_change',
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
			console.log(json_result);
			if (json_result['result'] == 'true')
			{
				alertToast('success',json_result['msg']);
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

// set_id
$('#set_id').click(function(){
	$('#set_id').removeClass('btn-default');
	$('#set_id').addClass('btn-info');
	
	$('#set_bio').removeClass('btn-info');
	$('#set_kpad').removeClass('btn-info');
	
	$('#set_bio').addClass('btn-default');
	$('#set_kpad').addClass('btn-default');
	
	nbCall_save('logintp','');
});

$('#set_bio').click(function(){
	$('#set_bio').removeClass('btn-default');
	$('#set_bio').addClass('btn-info');
	
	$('#set_id').removeClass('btn-info');
	$('#set_kpad').removeClass('btn-info');
	
	$('#set_id').addClass('btn-default');
	$('#set_kpad').addClass('btn-default');
	
	nbCall_save('logintp','bio');
});

$('#set_kpad').click(function(){
	$('#set_kpad').removeClass('btn-default');
	$('#set_kpad').addClass('btn-info');
	
	$('#set_bio').removeClass('btn-info');
	$('#set_id').removeClass('btn-info');
	
	$('#set_bio').addClass('btn-default');
	$('#set_id').addClass('btn-default');
	
	nbCall_save('logintp','kpad');
	
	nbCall_keypad('간변비밀번호를 설정하세요','');
});


// ===================== Modal Script [ START ] ===========================

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