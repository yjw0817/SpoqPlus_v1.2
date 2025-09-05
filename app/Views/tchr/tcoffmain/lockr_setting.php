

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class=" col-xs-12"><h1 class="page-header">락커(사물함) v설정</h1></div>
		<div class="row">
			<div class="col_md-12" style='margin-bottom:10px;'>
				<button type="button" class="top_bt btc001 "  onclick="location.href='/tbcoffmain/locker_setting';">락커 설정</button>
				<button type="button" class="top_bt btc002"  onclick="location.href='/tbcoffmain/locker_setting2';">골프라커 설정</button>
			</div>
		</div>
		<p>※ 한번에 설정 가능한 범위 수는 50개 까지 입니다. (예 : 시작번호 : 1 / 끝번호 : 50 )</p>
		


<!-- <div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">남자 락커설정</h4>
	</div>
	<div class="panel-body table-responsive">




	</div>
</div> -->




<div class="row">
<div class=" col-xs-12  col-md-6 col-lg-4  panel2 panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">남자 락커설정</h4>
	</div>
	<div class="panel-body table-responsive">

	<form id="form_lockr_01_m">
					<input type="hidden" name="lockr_knd" value="01" />
					<input type="hidden" name="lockr_gendr_set" value="M" />
					<input type="hidden" name="lockr_disp_fmt" value="" />
					<input type="hidden" name="set_hist_stat" value="00" />
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
					
						<!-- [시작번호] [START] -->
				        <div class="input-group2 input-group-sm mb-1">
				        	<span class="input-group-append">
				        		<span class="input-group-text4" style=''>시작번호</span>
				        	</span>
				        	<input type="text" class="input-group-text3"  placeholder="" name="lockr_s_no" id="lockr_s_no" />
				        
				        	<span class="input-group-append">
				        		<span class="input-group-text4" style=''>끝번호</span>
				        	</span>
				        	<input type="text" class="input-group-text3" s placeholder="" name="lockr_e_no" id="lockr_e_no" />
				        	
				        	<button type="button" class="btn-success2"  onclick="btn_lockr_01_m_submit();">등록하기</button>
				        </div>
				        <!-- [끝번호] [END] -->
					</div>
					<!-- CARD BODY [END] -->
					</form>
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
					
						<table class="table table-bordered col-md-12">
							<thead>
								<tr>
									<th style='background: #F1F1F1FF; text-align: center; width:150px'>시작번호</th>
									<th style='background: #F1F1F1FF; text-align: center; width:150px'>끝번호</th>
									<th style='background: #F1F1F1FF; text-align: center; width:150px'>옵션</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($lockr_list['m01'] as $r) : ?>
								<tr class="text-center">
									<td><?php echo $r['LOCKR_S_NO'] ?></td>
									<td><?php echo $r['LOCKR_E_NO'] ?></td>
									<td>
										<button type="button" class="btn btn-danger btn-xs" style='margin-left:10px' onclick="lockr_del('01','M','<?php echo $r['LOCKR_S_NO']?>','<?php echo $r['LOCKR_E_NO']?>','<?php echo $r['LOCKR_SET_HIST_SNO']?>');">삭제하기</button>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</div>
	</div>
</div>

<div class="col-xs-12  col-md-6 col-lg-4 panel2 panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">여자 락커설정</h4>
	</div>
	<div class="panel-body table-responsive">

	<form id="form_lockr_01_f">
					<input type="hidden" name="lockr_knd" value="01" />
					<input type="hidden" name="lockr_gendr_set" value="F" />
					<input type="hidden" name="lockr_disp_fmt" value="" />
					<input type="hidden" name="set_hist_stat" value="00" />
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
					
						<!-- [시작번호] [START] -->
				        <div class="input-group2 input-group-sm mb-1">
				        	<span class="input-group-append">
				        		<span class="input-group-text4" >시작번호</span>
				        	</span>
				        	<input type="text" class="input-group-text3"  placeholder="" name="lockr_s_no" id="lockr_s_no" />
				        
				        	<span class="input-group-append">
				        		<span class="input-group-text4">끝번호</span>
				        	</span>
				        	<input type="text" class="input-group-text3"  placeholder="" name="lockr_e_no" id="lockr_e_no" />
				        	
				        	<button type="button" class="btn-success2" onclick="btn_lockr_01_f_submit();">등록하기</button>
				        </div>
				        <!-- [끝번호] [END] -->
					</div>
					<!-- CARD BODY [END] -->
					</form>
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
					
						<table class="table table-bordered col-md-12">
							<thead>
								<tr>
								<tr>
									<th style='background: #F1F1F1FF; text-align: center; width:150px'>시작번호</th>
									<th style='background: #F1F1F1FF; text-align: center; width:150px'>끝번호</th>
									<th style='background: #F1F1F1FF; text-align: center; width:150px'>옵션</th>
								</tr>
								</tr>
							</thead>
							<tbody>
							<?php foreach($lockr_list['f01'] as $r) : ?>
								<tr class="text-center">
									<td><?php echo $r['LOCKR_S_NO'] ?></td>
									<td><?php echo $r['LOCKR_E_NO'] ?></td>
									<td>
										<button type="button" class="btn btn-danger btn-xs" style='margin-left:10px' onclick="lockr_del('01','F','<?php echo $r['LOCKR_S_NO']?>','<?php echo $r['LOCKR_E_NO']?>','<?php echo $r['LOCKR_SET_HIST_SNO']?>');">삭제하기</button>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</div>
	</div>
</div>

<div class="col-xs-12  col-md-6 col-lg-4 panel2 panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">혼용 락커설정</h4>
	</div>
	<div class="panel-body table-responsive">

	<form id="form_lockr_01_g">
					<input type="hidden" name="lockr_knd" value="01" />
					<input type="hidden" name="lockr_gendr_set" value="G" />
					<input type="hidden" name="lockr_disp_fmt" value="" />
					<input type="hidden" name="set_hist_stat" value="00" />
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
					
						<!-- [시작번호] [START] -->
				        <div class="input-group2 input-group-sm mb-1">
				        	<span class="input-group-append">
				        		<span class="input-group-text4">시작번호</span>
				        	</span>
				        	<input type="text" class="input-group-text3"  placeholder="" name="lockr_s_no" id="lockr_s_no" />
				        
				        	<span class="input-group-append">
				        		<span class="input-group-text4" >끝번호</span>
				        	</span>
				        	<input type="text" class="input-group-text3" placeholder="" name="lockr_e_no" id="lockr_e_no" />
				        	
				        	<button type="button" class="btn-success2" onclick="btn_lockr_01_g_submit();">등록하기</button>
				        </div>
				        <!-- [끝번호] [END] -->
					</div>
					<!-- CARD BODY [END] -->
					</form>
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
					
						<table class="table table-bordered col-md-12">
							<thead>
								<tr>
								<tr>
									<th style='background: #F1F1F1FF; text-align: center; width:150px'>시작번호</th>
									<th style='background: #F1F1F1FF; text-align: center; width:150px'>끝번호</th>
									<th style='background: #F1F1F1FF; text-align: center; width:150px'>옵션</th>
								</tr>
								</tr>
							</thead>
							<tbody>
							<?php foreach($lockr_list['g01'] as $r) : ?>
								<tr class="text-center">
									<td><?php echo $r['LOCKR_S_NO'] ?></td>
									<td><?php echo $r['LOCKR_E_NO'] ?></td>
									<td>
										<button type="button" class="btn btn-danger btn-xs" style='margin-left:10px' onclick="lockr_del('01','G','<?php echo $r['LOCKR_S_NO']?>','<?php echo $r['LOCKR_E_NO']?>','<?php echo $r['LOCKR_SET_HIST_SNO']?>');">삭제하기</button>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</div>
	</div>
</div>

			

			
			
		</div>
	</div>
	
	<form id='form_lockr_del'>
		<input type="hidden" name="del_lockr_hist_sno" id="del_lockr_hist_sno" />
		<input type="hidden" name="del_lockr_knd" id="del_lockr_knd" />
		<input type="hidden" name="del_lockr_gendr_set" id="del_lockr_gendr_set" />
		<input type="hidden" name="del_lockr_s_no" id="del_lockr_s_no" />
		<input type="hidden" name="del_lockr_e_no" id="del_lockr_e_no" />
	</form>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
	
<!-- ############################## MODAL [ END ] ###################################### -->
	
	
	
</section>


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

function lockr_del(knd,lockr_set,s_no,e_no,hist_sno)
{
	$('#del_lockr_hist_sno').val(hist_sno);
    $('#del_lockr_knd').val(knd);
    $('#del_lockr_gendr_set').val(lockr_set);
	$('#del_lockr_s_no').val(s_no);
	$('#del_lockr_e_no').val(e_no);
	
	var params = $("#form_lockr_del").serialize();
	jQuery.ajax({
		url: '/tbcoffmain/ajax_locket_del_proc',
		type: 'POST',
		data:params,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
		dataType: 'text',
		success: function (result) {
			if ( result.substr(0,8) == '<script>' )
        	{
        		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
        		location.href='/tlogin';
        		return;
        	}
        	
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
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
		location.href='/tlogin';
		return;
    });
	
}

function btn_lockr_01_m_submit()
{
	var params = $("#form_lockr_01_m").serialize();
	jQuery.ajax({
		url: '/tbcoffmain/ajax_locket_setting_proc',
		type: 'POST',
		data:params,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
		dataType: 'text',
		success: function (result) {
			if ( result.substr(0,8) == '<script>' )
        	{
        		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
        		return;
        	}
        	
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
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
    	alert('오류가 발생하였습니다.');
		return;
    });
}

function btn_lockr_01_f_submit()
{
	var params = $("#form_lockr_01_f").serialize();
	jQuery.ajax({
		url: '/tbcoffmain/ajax_locket_setting_proc',
		type: 'POST',
		data:params,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
		dataType: 'text',
		success: function (result) {
			if ( result.substr(0,8) == '<script>' )
        	{
        		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
        		location.href='/tlogin';
        		return;
        	}
        	
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
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
		location.href='/tlogin';
		return;
    });	
}

function btn_lockr_01_g_submit()
{
	var params = $("#form_lockr_01_g").serialize();
	jQuery.ajax({
		url: '/tbcoffmain/ajax_locket_setting_proc',
		type: 'POST',
		data:params,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
		dataType: 'text',
		success: function (result) {
			if ( result.substr(0,8) == '<script>' )
        	{
        		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
        		location.href='/tlogin';
        		return;
        	}
        	
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
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
		location.href='/tlogin';
		return;
    });	
}


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