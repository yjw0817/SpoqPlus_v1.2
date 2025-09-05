<style>


</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class='text-center' style='margin-top:100px'>센터 입장 위한 QR 코드 입니다.</div>	
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div style='width:100%;text-align:center;'>
					<div id="qrcode" class='qrcode' style="margin:80px 0px;"></div>
				</div> 
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class='text-center' style='margin:10px'>
				위에 발급된 QR 코드는 시간 단위로 새롭게 갱신되어 발급되고 있습니다.<br /> 
				이전에 발급된 QR코드를 캡쳐해서 이용시 체크가 되지 않습니다. 이점 참고하시어 이용 하시기 바랍니다.
				</div>	
			</div>
		</div>
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ############################## MODAL [ END ] ###################################### -->
<input type='hidden' id='base_qrcode' value="<?php echo $base_qrcode?>" />
<input type='hidden' id='qrcode_val' />

<button type='button' class='btn btn-block btn-sm btn-info' onclick="temp_attd();">임시 출석</button>

</section>

<?=$jsinc ?>
<script src="/plugins/qrcode.js"></script>
<script>

function temp_attd()
{
	var qrcode_val = $('#qrcode_val').val();
	var params = "qrcode_val="+qrcode_val;
	jQuery.ajax({
        url: '/api/qrcode_attd',
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
				alertToast('success',json_result['msg']);
				//console.log(json_result);	
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

$("#qrcode > img").css({"margin":"auto"});

$(function () {
	var base_qrcode = $('#base_qrcode').val();
	var date_timestamp = Date.now();
	$('#qrcode_val').val(base_qrcode+"|"+date_timestamp);
	//console.log(base_qrcode+"|"+date_timestamp);
		var qrcode = new QRCode(document.getElementById("qrcode"), {
        	text: base_qrcode+"|"+date_timestamp,
        	width: 200,
        	height: 200,
        	colorDark : "#000000",
        	colorLight : "#ffffff",
        	correctLevel : QRCode.CorrectLevel.H
        });

	setInterval(function(){
		var base_qrcode = $('#base_qrcode').val();
		var date_timestamp = Date.now();
		$('#qrcode_val').val(base_qrcode+"|"+date_timestamp);
		$('#qrcode').empty();
		//console.log(base_qrcode+"|"+date_timestamp);
		var qrcode = new QRCode(document.getElementById("qrcode"), {
        	text: base_qrcode+"|"+date_timestamp,
        	width: 200,
        	height: 200,
        	colorDark : "#000000",
        	colorLight : "#ffffff",
        	correctLevel : QRCode.CorrectLevel.H
        });
	},10000);
})

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