<style>
/* 달력이 헤더에 가려지지 않도록 z-index 조정 */
.datepicker {
    z-index: 9999 !important;
}
.datepicker-dropdown {
    z-index: 9999 !important;
}
/* 회원 사진 스타일 */
.preview_mem_photo {
    width: 30px;
    height: 30px;
    object-fit: cover;
    border-radius: 50%;
    border: 1px solid #ccc;
    margin-right: 8px;
    vertical-align: middle;
}
</style>
<?php
$sDef = SpoqDef();

// 전화번호 포맷팅 함수
function formatPhoneNumber($phone) {
    $phone = preg_replace("/[^0-9]/", "", $phone);
    $length = strlen($phone);
    
    if ($length == 11) {
        return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $phone);
    } elseif ($length == 10) {
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
    } elseif ($length == 9) {
        return preg_replace("/([0-9]{2})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
    } else {
        return $phone;
    }
}
?>
<!-- Main content -->
<h1 class="page-header"><?php echo $title ?></h1>
<!-- CARD HEADER [END] -->



<div class="panel panel-inverse">
        <div class="card-header py-3">
            <h5 class="m-0 text-white">검색 조건 (종료일 검색)</h5>
        </div>

<div class="clearfix">
	
		<div class="row" id='help_con' style='display:none;'>
			<div class="col_md-12">
				<div class="alert-info2" >

				  <button type="button" class="close2" onclick="$('#help_con').hide();"><i class="fas fa-times" style="font-size:20px;"></i></button>
					<small><strong>출입제한 조건에 맞는 종료일로 보내기 할 회원을 검색합니다.</strong></small><br />
					<small>1. 검색조건 : 해당 출입조건이 검색 종료일 이후로 종료되는 상품으로 검색합니다.</small><br />
					<small>2. 검색된 회원들에게 어떤 상품을 보낼지 대분류, 중분류를 선택하세요</small><br />
					<small>3. 검색된 상품에서 상품 보내기할 회원을 선택한 해주세요. </small><br />
					<small>4. 선택이 완료되면 상품 보내기 버튼을 클릭하여 검색된 상품을 선택하여 상품을 보낼 수 있습니다.</small>
				</div>
			</div>
		</div>


<form name="form_buy_event_manage1" id="form_buy_event_manage1" method="post" onsubmit="return false;">

   <div class="bbs_search_box_a3 mb10 mt10">
		<ul>
		  <li>ㆍ 종료일  </li>
		  <li>
			<input type="text" class="form-control datepp" name="sch_end_sdate" id="sch_end_sdate" value="<?php echo $search_val['sch_end_sdate']?>" >
		  </li>
		  <li>
			 <select class="form-control"  name="acc_rtrct_dv" id="acc_rtrct_dv">	
			    <?php foreach ($acc_rtrct_dv as $r => $key) : ?>
			    <?php if($r != '') :?>
				<option value="<?php echo $r?>" <?php if($search_val['acc_rtrct_dv'] == $r) {?> selected <?php } ?> ><?php echo $key?></option>
			    <?php endif; ?>
			   <?php endforeach; ?>
			</select>
		  </li>		  		
		  <li>
			 <select class="form-control"  name="acc_rtrct_mthd" id="acc_rtrct_mthd">
				<?php foreach ($acc_rtrct_mthd as $r => $key) : ?>
				<?php if($r != '') :?>
				<option value="<?php echo $r?>" <?php if($search_val['acc_rtrct_mthd'] == $r) {?> selected <?php } ?> ><?php echo $key?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>

		  </li>	
		   <li>
			<a href="#" type="button" class="basic_bt02" onclick="$('#help_con').show();" style="display:none;">
			<i class="far fa-question-circle"></i> 주의</a>

		  </li>	  		
		 </ul>
	</div>	
	 <div class="bbs_search_box_a4 mb10 mt10">
		<ul>
		 <li>ㆍ 분류 </li>
		<li>
			 <select class="form-control" name="search_cate1" id="search_cate1">
				<option value="">대분류 선택</option>
				<?php foreach ($sch_cate1 as $r) : ?>
				<option value="<?php echo $r['1RD_CATE_CD']?>" <?php if($search_val['search_cate1'] == $r['1RD_CATE_CD']) {?> selected <?php } ?> ><?php echo $r['CATE_NM']?></option>
				<?php endforeach; ?>
			</select>
		  </li>	
		  <li>
			 <select class="form-control"  name="search_cate2" id="search_cate2">
				<option value="">중분류 선택</option>
				<?php foreach ($sch_cate2 as $r) : ?>
				<option value="<?php echo $r['2RD_CATE_CD']?>" <?php if($search_val['search_cate2'] == $r['2RD_CATE_CD']) {?> selected <?php } ?> ><?php echo $r['CATE_NM']?></option>
				<?php endforeach; ?>
			</select>
		  </li>			  	  		  
         
		 </ul>
	</div>	
	 <div class="bbs_search_box_a8 mb10 mt10">
		<ul>
           <li>ㆍ 상품 </li>
          <li>
			<a href="#" onclick="send_buy_sno();" class="basic_bt04" type="button"><i class="fas fa-gift"></i> 상품보내기</a>
		  </li>
          <li>
			보내기 선택한 회원 수 : <span id='select_mem_sno_count'><?php echo count($send_mem_sno)?></span> 명
		  </li>		  		  
		 </ul>
	</div>	
	</form>
	</div>
</div>





<!-- CARD BODY [START] -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">회원별 구매 리스트</h4>
		<div class="panel-heading-btn">
		</div>
	</div>
		<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		<div id="search-results">
		총 건수 : <span id="total-count"><?php echo $totalCount?></span> 건

		<table class="table table-bordered table-hover  col-md-12 mt20">

			<thead>
				<tr class='text-center'>
					<th style='width:45px'>선택</th>
					<th style='width:75px'>회원명</th>
					<th style='width:100px'>전화번호</th>
					<th style='width:115px'>구매일시</th>
					<th style='width:80px'>상태</th>
					<th style='width:80px'>구매상태</th>
					<th>이용회원권명</th>
					<th style='width:60px'>기간</th>
					<th style='width:80px'>시작일</th>
					<th style='width:115px'>종료일</th>
					<th style='width:70px'>수업</th>
					<th style='width:70px'>휴회일</th>
					<th style='width:70px'>휴회수</th>
					<th style='width:100px'>구매금액</th>
					<th style='width:100px'>결제금액</th>
					<th style='width:100px'>미수금액</th>
					
					<th style='width:100px'>수업강사</th>
					<th style='width:100px'>판매강사</th>
					
				</tr>
			</thead> 
			<tbody>
				<?php 
				foreach($buy_event_list as $r) :
					$backColor = ""; // 이용중
					if ($r['EVENT_STAT'] == "01") $backColor = "#cfecf0"; // 예약됨
					if ($r['EVENT_STAT'] == "99") $backColor = "#f7d5d9"; // 종료됨
					
					
					
				?>
				<tr style="background-color: <?php echo $backColor ?>;">
					<td class='text-center'>
						<div class="icheck-primary d-inline">
							<input type="checkbox" name="send_chk[]" id="send_chk_<?php echo $r['BUY_EVENT_SNO']?>" value="<?php echo $r['MEM_SNO']?>" onclick="p_chk(this);" >
							<label for="send_chk_<?php echo $r['BUY_EVENT_SNO']?>">
								<small></small>
							</label>
						</div>
					</td>
				
					<td class='text-center'>
						<?php if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) : ?>
							<img class="preview_mem_photo"
								id="preview_mem_photo_<?php echo $r['MEM_SNO']?>"
								src="<?php echo isset($r['MEM_THUMB_IMG']) ? $r['MEM_THUMB_IMG'] : '' ?>"
								alt="회원사진"
								style="cursor: pointer;"
								onclick="showFullPhoto('<?php echo isset($r['MEM_MAIN_IMG']) ? $r['MEM_MAIN_IMG'] : '' ?>')"
								onerror="this.onerror=null; this.src='/dist/img/default_profile_<?php echo isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M' ?>.png';">
						<?php endif; ?>
						<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info('<?php echo $r['MEM_SNO']; ?>');"> 
							<?php echo $r['MEM_NM']?>
						</a>
					</td>
					<td class='text-center'>
						<?php echo isset($r['MEM_TELNO']) && !empty($r['MEM_TELNO']) ? formatPhoneNumber($r['MEM_TELNO']) : '-' ?>
					</td>
					<td class='text-center'><?php echo substr($r['BUY_DATETM'],0,16)?></td>
					<td class='text-center'	><?php echo $sDef['EVENT_STAT'][$r['EVENT_STAT']]?></td>
					<td class='text-center'	><?php echo $sDef['EVENT_STAT_RSON'][$r['EVENT_STAT_RSON']]?></td>
					<td >
						<!-- <i class="fa fa-list" onclick="more_info_show();"></i> --> 
						<?php echo $r['SELL_EVENT_NM']?>
						<?php if($r['LOCKR_SET'] == "Y") : 
								if ($r['LOCKR_NO'] != '') :
									echo disp_locker_word($r['LOCKR_KND'],$r['LOCKR_GENDR_SET'],$r['LOCKR_NO']);
								else :
									echo "[미배정] ";
								endif ;
								endif;
						?>
						
						<!-- (<?php echo $sDef['ACC_RTRCT_MTHD'][$r['ACC_RTRCT_MTHD']]?>) -->
						
					</td>
					
					<td class='text-center'><?php echo disp_produnit($r['USE_PROD'],$r['USE_UNIT'])?></td>
					<td class='text-center'><span id="<?php echo "exr_s_date_".$r['BUY_EVENT_SNO']?>"><?php echo $r['EXR_S_DATE']?></span></td>
					<td class='text-center'><span id="<?php echo "exr_e_date_".$r['BUY_EVENT_SNO']?>"><?php echo $r['EXR_E_DATE']?></span><?php echo disp_add_cnt($r['ADD_SRVC_EXR_DAY'])?></td>
					
					<!-- ############### 수업 영역 ################# -->
					<?php if($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") :?>
					<?php
						$sum_clas_cnt = $r['MEM_REGUL_CLAS_LEFT_CNT'] + $r['SRVC_CLAS_LEFT_CNT']; // 총 수업 남은 횟수
					?>
					<td class='text-center'><?php echo $sum_clas_cnt?></td>
					
					<?php else :?>
					<td class='text-center'>-</td>
					<?php endif ;?>
					<!-- ############### 수업 영역 ################# -->
					
					<!-- ############### 휴회 영역 ################# -->
					<?php if($r['DOMCY_POSS_EVENT_YN'] == "Y") :?>
					<td class='text-center'><?php echo $r['LEFT_DOMCY_POSS_DAY'] ?></td>
					<td class='text-center'><?php echo $r['LEFT_DOMCY_POSS_CNT'] ?></td>
					<?php else :?>
					<td class='text-center'>-</td>
					<td class='text-center'>-</td>
					<?php endif ;?>
					<!-- ############### 휴회 영역 ################# -->
					
					<td style="text-align:right"><?php echo number_format($r['REAL_SELL_AMT']) ?></td>
					<td style="text-align:right"><?php echo number_format($r['BUY_AMT']) ?></td>
					<td style="text-align:right"><?php echo number_format($r['RECVB_AMT']) ?></td>
					
					<td class='text-center'><?php echo $r['STCHR_NM'] ?></td>
					<td class='text-center'><?php echo $r['PTCHR_NM'] ?></td>
				</tr>
				<?php 
				endforeach;
				?>
				
			</tbody>
		</table>
		<!-- TABLE [END] -->
		</div>
		 
		<?php echo $pager ?><br/>
	<!-- CARD BODY [END] -->
	<!-- CARD FOOTER [START] -->
	<!-- CARD FOOTER [END] -->
			
</div>
	<!-- CARD BODY [END] -->

</div>
<br/>
			
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
	
<!-- ############################## MODAL [ END ] ###################################### -->
	
	
	


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
    
    // 초기 페이저 이벤트 바인딩
    bindPagerEvents();
    
    // 세션에 저장된 체크박스 상태 복원
    restoreCheckedMembers();
})

function send_buy_sno()
{
	if ( $('#select_mem_sno_count').text() == "0")
	{
		alertToast('error',"보내기 회원을 선택해주세요.");
		return;
	}
	
    var acc_dv = $('#acc_rtrct_dv').val();
    var acc_mthd = $('#acc_rtrct_mthd').val();
    var a1rd_cd = $('#search_cate1').val();
    var a2rd_cd = $('#search_cate2').val();
   
	if ( a1rd_cd == '' )
	{
		alertToast('error',"대분류를 선택해주세요");
		return;
	}
	
	// 세션에 파라미터 저장 후 이동
	var params = {
		'1RD_CATE_CD': a1rd_cd,
		'2RD_CATE_CD': a2rd_cd
	};
	
	jQuery.ajax({
        url: '/teventmain/ajax_set_send_params',
        type: 'POST',
        data: params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
        	if ( result.substr(0,8) == '<script>' )
        	{
        		alert('로그인이 만료 되었습니다. 다시 로그인해주세요');
        		location.href='/tlogin';
        		return;
        	}
        	
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				location.href="/teventmain/send_event3";
			} else 
			{
				alertToast('error',json_result['msg']);
			}
        }
    }).fail((error) => {
    	console.log('통신실패');
    	alert('로그인이 만료 되었습니다. 다시 로그인해주세요');
		location.href='/tlogin';
		return;
    });
}

function p_chk(t)
{
	var chk_tf = $(t).prop('checked');
	var params = "send_mem_sno="+$(t).val()+"&chk_tf="+chk_tf;
	jQuery.ajax({
        url: '/teventmain/ajax_selected_mem_sno_chk',
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
		location.href='/tlogin';
		return;
    });
	
}

$('#search_cate1').change(function(){
	var selectedCate1 = $(this).val();
	
	// search_cate2 초기화
	$('#search_cate2').empty();
	$('#search_cate2').append('<option value="">중분류 선택</option>');
	
	if (selectedCate1 != '') {
		// 선택된 대분류에 해당하는 중분류 목록을 AJAX로 가져오기
		jQuery.ajax({
			url: '/teventmain/ajax_sch_cate2_by_1rd',
			type: 'POST',
			data: 'search_cate1=' + selectedCate1,
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
					// 중분류 옵션들을 추가
					$.each(json_result['cate2'], function(index, item) {
						$('#search_cate2').append('<option value="' + item['2RD_CATE_CD'] + '">' + item['CATE_NM'] + '</option>');
					});
					
					// 중분류 목록이 로드된 후 자동 검색 실행
					btn_search();
				} else 
				{
					alertToast('error', json_result['msg']);
				}
			}
		}).done((res) => {
			console.log('통신성공');
		}).fail((error) => {
			console.log('통신실패');
			alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
			location.href='/tlogin';
			return;
		});
	} else {
		// 대분류를 선택 해제한 경우에도 자동 검색 실행
		btn_search();
	}
});

$('#search_cate2').change(function(){
	// 중분류 변경 시 자동 검색 (대분류 선택 조건 제거)
	btn_search();
});

// 출입제한 조건 변경 시 자동 검색
$('#acc_rtrct_dv, #acc_rtrct_mthd').change(function(){
	btn_search();
});

// 종료일 변경 시 자동 검색
$('#sch_end_sdate').change(function(){
	btn_search();
});

// AJAX 요청 관리를 위한 변수
let currentSearchRequest = null;

function btn_search()
{
	// 검색 전 현재 화면의 체크된 회원만 세션에 유지
	sync_checked_members();
	
	// 이전 검색 요청이 있으면 취소
	if (currentSearchRequest && currentSearchRequest.readyState !== 4) {
		currentSearchRequest.abort();
	}
	
	// AJAX 검색 요청
	var formData = $('#form_buy_event_manage1').serialize();
	
	// 로딩 표시
	$('#search-results').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> 검색중...</div>');
	
	currentSearchRequest = $.ajax({
		url: '/teventmain/ajax_buy_event_manage1_search',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(response) {
			if (response.result == 'true') {
				// 검색 결과 업데이트
				$('#search-results').html(response.html);
				$('#total-count').text(response.totalCount);
				
				// 페이저 업데이트
				$('#pager-container').html(response.pager);
				
				// 페이저 이벤트 재바인딩
				bindPagerEvents();
				
				// 세션에 저장된 체크박스 상태 복원
				restoreCheckedMembers();
			} else {
				alertToast('error', '검색 중 오류가 발생했습니다.');
			}
		},
		error: function(xhr) {
			// abort된 경우는 에러 메시지 표시하지 않음
			if (xhr.statusText !== 'abort') {
				alertToast('error', '검색 중 오류가 발생했습니다.');
				$('#search-results').html('<div class="text-center">검색 중 오류가 발생했습니다.</div>');
			}
		},
		complete: function() {
			currentSearchRequest = null;
		}
	});
}

// 현재 화면에 표시된 체크박스 상태를 세션과 동기화
function sync_checked_members()
{
	// 현재 화면의 모든 체크박스를 확인
	$('input[name="send_chk[]"]').each(function() {
		var mem_sno = $(this).val();
		var is_checked = $(this).prop('checked');
		
		// AJAX로 세션 업데이트 (동기식 호출)
		jQuery.ajax({
			url: '/teventmain/ajax_selected_mem_sno_chk',
			type: 'POST',
			data: "send_mem_sno=" + mem_sno + "&chk_tf=" + is_checked,
			async: false, // 동기식으로 처리
			contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
			dataType: 'text'
		});
	});
}

$('#domcy_acppt_i_sdate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
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

// 회원 사진 전체 보기
function showFullPhoto(imageSrc) {
	if (!imageSrc || imageSrc === '') {
		alertToast('info', '등록된 사진이 없습니다.');
		return;
	}
	
	// 모달 또는 새 창으로 이미지 표시
	var modal = '<div class="modal fade" id="photoModal" tabindex="-1" role="dialog">';
	modal += '<div class="modal-dialog modal-dialog-centered" role="document">';
	modal += '<div class="modal-content">';
	modal += '<div class="modal-header">';
	modal += '<h5 class="modal-title">회원 사진</h5>';
	modal += '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>';
	modal += '</div>';
	modal += '<div class="modal-body text-center">';
	modal += '<img src="' + imageSrc + '" class="img-fluid" alt="회원 사진">';
	modal += '</div>';
	modal += '</div>';
	modal += '</div>';
	modal += '</div>';
	
	$('body').append(modal);
	$('#photoModal').modal('show');
	$('#photoModal').on('hidden.bs.modal', function () {
		$(this).remove();
	});
}

// 회원별 통합정보 보기
function mem_manage_mem_info(mem_sno)
{
	location.href="/ttotalmain/info_mem/"+mem_sno;
}

// 페이저 이벤트 바인딩
function bindPagerEvents() {
    $('#pager-container a').off('click').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        if (href && href !== '#') {
            var url = new URL(href, window.location.origin);
            var page = url.searchParams.get('page') || 1;
            
            // 검색 폼에 페이지 번호 추가
            $('#form_buy_event_manage1').append('<input type="hidden" name="page" value="' + page + '">');
            btn_search();
            // 페이지 번호 제거
            $('#form_buy_event_manage1 input[name="page"]').remove();
        }
    });
}

// 세션에 저장된 체크박스 상태 복원
function restoreCheckedMembers() {
    // AJAX로 세션에서 체크된 회원 목록 가져오기
    $.ajax({
        url: '/teventmain/ajax_get_selected_mem_sno',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.result == 'true' && response.send_mem_sno) {
                // 체크된 회원 목록으로 체크박스 상태 복원
                response.send_mem_sno.forEach(function(mem_sno) {
                    $('input[name="send_chk[]"][value="' + mem_sno + '"]').prop('checked', true);
                });
                $('#select_mem_sno_count').text(response.send_mem_sno.length);
            }
        }
    });
}

</script>