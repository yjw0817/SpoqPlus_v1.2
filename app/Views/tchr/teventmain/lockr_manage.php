<style>
</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<h1 class="page-header"><?php echo $title ?></h1>
				<!-- CARD HEADER [END] -->
				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">락커 관리</h4>
					</div>
					<!-- CARD HEADER [END] -->
				
					<!-- CARD FOOTER [START] -->
					<div class=" clearfix" style='padding:10px;'>
					<div class="row">
						<div class="col_md-12" style='margin-bottom:10px; margin-top:5px;'>
							<button type="button" class="btn btn-blue "  onclick="location.href='/teventmain/lockr_manage';">락커 설정</button>
							<button type="button" class="btn btn-default3"  onclick="location.href='/teventmain/lockr_manage2';">골프라커 설정</button>
						</div>
					</div>

						
						<!-- BUTTON [START] -->
						<?php
							$btn_class["M"] = "btn-default";
							$btn_class["F"] = "btn-default";
							$btn_class["G"] = "btn-default";
							if ($lockr_inf['gendr'] == "M") $btn_class["M"] = "btn-blue";
							if ($lockr_inf['gendr'] == "F") $btn_class["F"] = "btn-blue";
							if ($lockr_inf['gendr'] == "G") $btn_class["G"] = "btn-blue";
						?>
						
						<ul class="card-footer pagination pagination-sm m-0 ">
							<li class="ac-btn"><a href="/teventmain/lockr_manage/<?php echo $lockr_inf['lockr_knd']?>/M" class="btn btn-block btn-default size13 mr5 <?php echo $btn_class['M']?> btn-sm">남자</a></li>
							<li class="ac-btn"><a href="/teventmain/lockr_manage/<?php echo $lockr_inf['lockr_knd']?>/F" class="btn btn-block btn-default size13 <?php echo $btn_class['F']?> btn-sm">여자</a></li>
							<li class="ac-btn"><a href="/teventmain/lockr_manage/<?php echo $lockr_inf['lockr_knd']?>/G" class="btn btn-block btn-default size13 ml5 <?php echo $btn_class['G']?> btn-sm">공용</a></li>
						</ul>
						
						<!-- BUTTON [END] -->
						<!-- PAGZING [START] -->
						<ul class="pagination pagination-sm m-0 float-left">
						</ul>
						<!-- PAGZING [END] -->
					</div>
					<!-- CARD FOOTER [END] -->
					
					
					<!-- CARD BODY [START] -->
					<div class="panel-body card-footer">
						<div class="row">
							<div class="col-md-12">
								<?php 
									$room_i = 0;
									foreach($lockr_range as $r) : 
										$btn_range_class = "btn-default";
										if($room_i == $lockr_inf['srange']) $btn_range_class = "btn-blue";
								?>
								<a href="/teventmain/lockr_manage/<?php echo $lockr_inf['lockr_knd']?>/<?php echo $lockr_inf['gendr']?>/<?php echo $room_i?>" class="btn btn-block <?php echo $btn_range_class?> btn-sm"><?php echo $r['min']?>~<?php echo $r['max']?> [<?php echo $r['poss_cnt'] ?>] 개 사용가능 </a>
								<?php
									$room_i++;
									endforeach; 
								?>
							</div>
							
							<div class="col-md-12 card-footer pad10 mt15">
								<?php foreach($list_room as $r) : ?>
								<div class='btn-group' style='display:inline !important'>
								<?php if($r['LOCKR_STAT'] == '00') : ?> <!-- 이용가능 -->
								<button type="button" class="btn btn-default btn-sm text-center dropdown-toggle dropdown-icon" data-toggle="dropdown" 
								style='width:105px; height:80px; margin-bottom:3px' ><?php echo $r['LOCKR_NO'] ?>
								</button>
								<div class="dropdown-menu text-xs" role="menu">
									<a class="dropdown-item" onclick="lockr_brok('<?php echo $r['LOCKR_KND']?>','<?php echo $r['LOCKR_GENDR_SET']?>','<?php echo $r['LOCKR_NO']?>','<?php echo $r['LOCKR_STAT']?>');">고장</a>
								</div>
								<?php elseif($r['LOCKR_STAT'] == '90') : ?> <!-- 고장 -->
								<button type="button" class="btn btn-warning2 btn-sm text-center dropdown-toggle dropdown-icon" data-toggle="dropdown" 
								style='width:105px; height:80px; margin-bottom:3px'  ><?php echo $r['LOCKR_NO'] ?>
								</button>
								<div class="dropdown-menu text-xs" role="menu">
									<a class="dropdown-item" onclick="lockr_brokn('<?php echo $r['LOCKR_KND']?>','<?php echo $r['LOCKR_GENDR_SET']?>','<?php echo $r['LOCKR_NO']?>','<?php echo $r['LOCKR_STAT']?>');">고장해제</a>
								</div>
								<?php else : ?>
									<?php if($r['LOCKR_STAT'] == '99') : ?> <!-- 이용종료 -->
										<button type="button" class="btn btn-danger2 btn-sm text-center dropdown-toggle dropdown-icon" data-toggle="dropdown" 
										style='width:105px; height:80px margin-bottom:3px' >
										<strong><?php echo $r['LOCKR_NO']?></strong>&nbsp;
										<?php echo $r['MEM_NM'] ?><br />
										<?php echo $r['LOCKR_USE_S_DATE'] ?><br />
										<?php echo $r['LOCKR_USE_E_DATE'] ?>
										</button>
										<div class="dropdown-menu text-xs" role="menu">
											<a class="dropdown-item" onclick="lockr_empty('<?php echo $r['LOCKR_KND']?>','<?php echo $r['LOCKR_GENDR_SET']?>','<?php echo $r['LOCKR_NO']?>','<?php echo $r['LOCKR_STAT']?>');">비우기</a>
										</div>
									<?php else :?> <!-- 이용중 -->
										<button type="button" class="btn btn-info2 btn-sm text-center dropdown-toggle dropdown-icon" data-toggle="dropdown" 
										style='width:105px; height:80px; margin-bottom:3px' >
										<strong><?php echo $r['LOCKR_NO']?></strong>&nbsp;
										<?php echo $r['MEM_NM'] ?><br />
										<?php echo $r['LOCKR_USE_S_DATE'] ?><br />
										<?php echo $r['LOCKR_USE_E_DATE'] ?>
										</button>
										<div class="dropdown-menu text-xs" role="menu">
											<a class="dropdown-item" onclick="lockr_move('<?php echo $r['LOCKR_KND']?>','<?php echo $r['LOCKR_GENDR_SET']?>','<?php echo $r['LOCKR_NO']?>','<?php echo $r['LOCKR_STAT']?>');">이동</a>
										</div>
									<?php endif;?>
								
								<?php
										endif;
									?>
								</div>
								<?php
								endforeach; 
								?>
							</div>
	
						</div>
					</div>
				</div>
				
			<!-- ############################## MODAL [ SATRT ] #################################### -->

			<!-- ============================= [ modal-sm START ] ============================================ -->
			<div class="modal fade" id="modal-lockr-move">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">이동</h4>
							<button type="button" class="close3"  data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div> 
						<div class="modal-body">
							<input type="hidden" name="lockr_move_knd" id="lockr_move_knd" />
							<input type="hidden" name="lockr_move_gendr" id="lockr_move_gendr" />
							<input type="hidden" name="lockr_move_no" id="lockr_move_no" />
							<input type="hidden" name="lockr_move_stat" id="lockr_move_stat" />
							<div class="input-group input-group-sm mb-1">
								<span class="input-group-append">
									<span class="input-group-text" style='width:100px'>이동할 번호</span>
								</span>
								<input type="text" class="form-control" placeholder="이동할 번호" name="lockr_move_no" id="lockr_move_af_no" />
							</div>
							
						</div>
						<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-sm btn-default"  data-bs-dismiss="modal">닫기</button>
							<button type="button" class="btn btn-sm btn-success" onclick="lockr_move_proc();">이동하기</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- ============================= [ modal-sm END ] ============================================== -->
	
<!-- ############################## MODAL [ END ] ###################################### -->

<form name="form_locker_select" id="form_lockr_select" method="post" action="/ttotalmain/lockr_select_proc">
	<input type="hidden" name="set_lockr_no" id="set_lockr_no" />
    <input type="hidden" name="set_lockr_knd" id="set_lockr_knd" value="<?php echo $lockr_inf['lockr_knd']?>" />
    <input type="hidden" name="set_lockr_gendr" id="set_lockr_gendr" value="<?php echo $lockr_inf['gendr']?>" />
    	
</form>	
</section>


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

function select_my_lockr(lockr_no,buy_sno,lockr_knd,lockr_gendr)
{
	$('#set_lockr_no').val(lockr_no);
	$('#set_buy_sno').val(buy_sno);
	$('#set_lockr_knd').val(lockr_knd);
	$('#set_lockr_gendr').val(lockr_gendr);
	$('#form_lockr_select').submit();
}

function select_lockr(lockr_no)
{
	$('#set_lockr_no').val(lockr_no);
	$('#form_lockr_select').submit();
}

// 이동
function lockr_move(knd,gendr,no,stat)
{
	$('#lockr_move_knd').val(knd);
	$('#lockr_move_gendr').val(gendr);
	$('#lockr_move_no').val(no);
	$('#lockr_move_stat').val(stat);
	$('#modal-lockr-move').modal("show");
}

function lockr_move_proc()
{
	var knd = $('#lockr_move_knd').val();
	var gendr = $('#lockr_move_gendr').val();
	var no = $('#lockr_move_no').val();
	var af_no = $('#lockr_move_af_no').val();
	var stat = $('#lockr_move_stat').val();
	
	var params = "knd="+knd+"&gendr="+gendr+"&no="+no+"&stat="+stat+"&af_no="+af_no;
    jQuery.ajax({
        url: '/teventmain/ajax_lockr_move',
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
				alertToast('error','해당 번호로는 이동할 수 없습니다.');
				return;
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

// 비우기
function lockr_empty(knd,gendr,no,stat)
{
	var params = "knd="+knd+"&gendr="+gendr+"&no="+no+"&stat="+stat;
    jQuery.ajax({
        url: '/teventmain/ajax_lockr_empty',
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

// 고장
function lockr_brok(knd,gendr,no,stat)
{
	var params = "knd="+knd+"&gendr="+gendr+"&no="+no+"&stat="+stat;
    jQuery.ajax({
        url: '/teventmain/ajax_lockr_brok',
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

// 고장해제
function lockr_brokn(knd,gendr,no,stat)
{
	var params = "knd="+knd+"&gendr="+gendr+"&no="+no+"&stat="+stat;
    jQuery.ajax({
        url: '/teventmain/ajax_lockr_brokn',
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

$("#script_modal_sm").click(function(){
	$("#modal-sm").modal("show");
});

$("#script_modal_default").click(function(){
	$("#modal-default").modal("show");
});

$("#script_modal_lg").click(function(){
	$("#modal-lg").modal("show");
});

$("#script_modal_xl").click(function(){
	$("#modal-xl").modal("show");
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