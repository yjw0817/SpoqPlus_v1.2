<style>
	.bbs_search_box_a4 ul li {
	display: flex;
	align-items: left;
}

.nav_bt {
	display: inline-flex;
	justify-content: center;
	align-items: center;
	width: 36px;  /* 또는 원하는 고정값 */
	padding: 0;
}

/* #Fdate1에만 적용되는 스타일 */
.ui-datepicker.nthUnit-table-hidden table {
    display: none;
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://jqueryui.com/resources/demos/datepicker/i18n/datepicker-ko.js"></script>
<!-- CARD HEADER [END] -->


<div class="panel panel-inverse">
        <div class="card-header py-3">
            <h5 class="m-0 text-white">검색 조건</h5>
        </div>
<form name="form_search_send_event_manage" id="form_search_send_event_manage" method="post" onsubmit="return false;">

   <div class="bbs_search_box_a mb10 mt10">
		<ul>
		  <li>ㆍ 회원명  </li>
		  <li>
			<input type="text" class="form-control"  name="snm" id="snm" value="<?php echo $search_val['snm']?>" >
		  </li>
		  
		 </ul>
	</div>	
	 <div class="bbs_search_box_a4 mb10 mt10">
		<ul>
		 <li>ㆍ 판매상품명  </li>
		  <li>
			<input type="text" class="form-control" name="senm" id="senm" value="<?php echo $search_val['senm']?>" >
		  </li>
          <li>
			 <select class="form-control"   name="ssstat" id="ssstat">
				<option value="">보내기상태</option>
				<?php foreach ($sDef['SEND_STAT'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val['ssstat'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>
		  </li>		  		  
          <li style="display: none;">
			<a href="#"  onclick="btn_search();" class="serch_bt" type="button"><i class="fas fa-search"></i> 검색</a>
		  </li>
		 </ul>
	</div>	
	 <div class="bbs_search_box_a4 mb10">
		<ul>
		  <li>ㆍ 날짜검색  </li>
		 
		  <li>
		     <select class="form-control me-2"  name="sdUnit" id="sdUnit" onchange="dateTypeCh(this);">
				<option value="Day" <?php if($search_val['sdUnit'] == "Day") {?> selected <?php } ?> selected >일일</option>
				<option value="Week" <?php if($search_val['sdUnit'] == "Week") {?> selected <?php } ?> >주간</option>
				<option value="Month" <?php if($search_val['sdUnit'] == "Month") {?> selected <?php } ?> >월간</option>
			</select>
			 <select class="form-control"name="sdcon" id="sdcon" onchange="dateConCh(this);">
				<!-- <option value="">날짜검색조건</option> -->
				<option value="sd" <?php if($search_val['sdcon'] == "sd") {?> selected <?php } ?> >보낸일시</option>
			</select>
		  </li>
		  <li style="width:36px">
			<a href="#"  onclick="prevBtnClick();" class="serch_bt nav_bt" id="prevBtn" type="button"><i class="fas fa-play fa-flip-horizontal fa-reward"></i></a>
		  </li>
		  <li style="width:auto">
			<input type="<?php if($search_val['sdUnit'] != 'Day' && $search_val['sdUnit'] != 'Week') {?>hidden<?php } else {?>text<?php } ?>" class="form-control me-2" style="text-align:center; width:100px;" name="sdate" id="sdate"  placeholder="검색시작일"  value="<?php echo $search_val['sdate']?>" autocomplete='off' <?php if($search_val['sdUnit'] != 'Day') {?> readonly <?php } ?> >
			<input type="text" class="form-control me-2" style="text-align:center; <?php if($search_val['sdUnit'] == 'Week') {?> width:120px; <?php } else {?> width:100px; <?php } ?> <?php if($search_val['nthUnit'] == '') {?> display:none; <?php } ?>" name="nthUnit" id="nthUnit" value="<?php echo $search_val['nthUnit']?>" autocomplete='off'  readonly >
			<input type="text" class="form-control me-2" style="text-align:center; width:100px;  <?php if($search_val['sdUnit'] != 'Week') {?> display:none; <?php } ?>" name="edate" id="edate" placeholder="검색종료일" value="<?php echo $search_val['edate']?>" autocomplete='off' <?php if($search_val['sdUnit'] != 'Day' ) {?> readonly <?php } ?>>
		  </li>
          <li>
			<a href="#"  onclick="nextBtnClick();" class="serch_bt nav_bt" id="nextBtn" type="button"><i class="fas fa-play"></i></a>
		  </li>
		 </ul>
	</div>
	</form>
</div>






<!-- <div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">검색 조건</h4>
	</div>

	<div class="card-footer clearfix">
		<form name="form_search_send_event_manage" id="form_search_send_event_manage" method="post" action="/teventmain/send_event_manage">
			<div class="input-group input-group-sm mb-1">
				<span class="input-group-append">
					<span class="input-group-text" style='width:120px;margin-left:5px;'>회원명</span>
				</span>
				<input type="text" class="ss-input" name="snm" id="snm" style='width:100px' value="<?php echo $search_val['snm']?>" >
				
				<span class="input-group-append">
					<span class="input-group-text" style='width:120px;margin-left:5px;'>판매상품명</span>
				</span>
				<input type="text" class="ss-input" name="senm" id="senm" style='width:100px' value="<?php echo $search_val['senm']?>" >
				
				<select class=" " style="width: 150px;margin-left:5px;" name="ssstat" id="ssstat">
				<option value="">보내기상태</option>
				<?php foreach ($sDef['SEND_STAT'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val['ssstat'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
				</select>
				
				<button type="button" class="btn btn-sm btn-success" style='margin-left:5px;' onclick="btn_search();">검색</button>
				
			</div>
			
			<div class="input-group input-group-sm mb-1">
			<select class=" " style="width: 220px;margin-left:5px;" name="sdcon" id="sdcon" onchange="dateConCh(this);">
				<option value="">날짜검색조건</option>
				<option value="sd" <?php if($search_val['sdcon'] == "sd") {?> selected <?php } ?> >보낸일시</option>
			</select>
			
			<span class="input-group-append">
				<span class="input-group-text" style='width:120px;margin-left:5px;'>검색시작일</span>
			</span>
			<input type="text" class="" name="sdate" id="sdate" style='width:100px' value="<?php echo $search_val['sdate']?>" autocomplete='off' disabled>
			
			<span class="input-group-append">
				<span class="input-group-text" style='width:120px;margin-left:5px;'>검색종료일</span>
			</span>
			<input type="text" class="" name="edate" id="edate" style='width:100px' value="<?php echo $search_val['edate']?>" autocomplete='off' disabled>
			</div>
			
		</form>
	</div>
</div> -->
	
	<!-- CARD BODY [START] -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">상품 보내기 리스트</h4>
		<div class="panel-heading-btn">
		</div>
	</div>
		<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		<!-- TABLE [START] -->
		총 건수 : <?php echo $totalCount?> 건
		<table class="table table-bordered table-hover col-md-12 mt20">
			<thead>
				<tr class='text-center'>
					<th style='width:55px'>순번</th>
					<th style='width:75px'>회원명</th>
					<th style='width:100px'>전화번호</th>
					<th style='width:115px'>보낸일시</th>
					<th style='width:60px'>상태</th>
					<th>판매상품명</th>
					<th style='width:160px'>판매금액(원)</th>
					<th style='width:100px'>기간</th>
					<th style='width:80px'>수업횟수</th>
					<th style='width:70px'>휴회일</th>
					<th style='width:70px'>휴회수</th>
					<th style='width:100px'>수업강사</th>
					<th style='width:100px'>판매강사</th>
					<th></th>
				</tr>
			</thead> 
			<tbody>
				<?php 
				foreach($send_event_list as $r) :
					$backColor = "";
					if ($r['SEND_STAT'] == "01") $backColor = "#cfecf0"; //보내기상품 결제함.
					if ($r['SEND_STAT'] == "99") $backColor = "#f7d5d9"; //보내기상품 취소함.
				?>
				<tr style="background-color: <?php echo $backColor ?>;">
					<td class='text-center'><?php echo $search_val['listCount']?></td>
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
					<td class='text-center'><?php echo substr($r['CRE_DATETM'],0,16)?></td>
					<td class='text-center'><?php echo $sDef['SEND_STAT'][$r['SEND_STAT']]?></td>
					<td ><?php echo $r['SELL_EVENT_NM']?></td>
					<td style="text-align:right">
						<?php if($r['SELL_AMT'] != (is_null($r['ORI_SELL_AMT']) ? 0 : $r['ORI_SELL_AMT'])) :?>
						<span style="text-decoration: line-through;">(<?php echo number_format($r['ORI_SELL_AMT'])?>)</span>
						<?php endif;?>
						<?php echo number_format($r['SELL_AMT']) ?>
					</td>
					<td class='text-center'>
						<?php echo disp_produnit($r['USE_PROD'],$r['USE_UNIT'])?>
						<?php if($r['ADD_SRVC_EXR_DAY'] != 0) :?>
						(+<?php echo $r['ADD_SRVC_EXR_DAY']?>)
						<?php endif;?>
					</td>
					<td class='text-center'>
						<?php echo $r['CLAS_CNT']?>
						<?php if($r['ADD_SRVC_CLAS_CNT'] != 0) :?>
						(+<?php echo $r['ADD_SRVC_CLAS_CNT']?>)
						<?php endif;?>
					</td>
					<td class='text-center'>
						<?php if($r['DOMCY_DAY'] != (is_null($r['ORI_DOMCY_DAY']) ? 0 : $r['ORI_DOMCY_DAY'])) :?>
						<span style="text-decoration: line-through;">(<?php echo $r['ORI_DOMCY_DAY']?>)</span>
						<?php endif;?>
						<?php echo $r['DOMCY_DAY']?>
					</td>
					<td class='text-center'>
						<?php if($r['DOMCY_CNT'] != (is_null($r['ORI_DOMCY_CNT']) ? 0 : $r['ORI_DOMCY_CNT'])) :?>
						<span style="text-decoration: line-through;">(<?php echo $r['ORI_DOMCY_CNT']?>)</span>
						<?php endif;?>
						<?php echo $r['DOMCY_CNT']?>
					</td>
					<td class='text-center'><?php echo $r['STCHR_NM'] ?></td>
					<td class='text-center'><?php echo $r['PTCHR_NM'] ?></td>
					<td class='text-center'>
						<?php if($r['SEND_STAT'] == "00") : ?>
						<button type="button" class="btn btn-danger btn-xs ac-btn" onclick="send_event_cancel('<?php echo $r['SEND_EVENT_MGMT_SNO']?>');"> 보내기 상품취소</button>
						<button type="button" class="btn btn-info btn-xs ac-btn" onclick="send_event_buy('<?php echo $r['SEND_EVENT_MGMT_SNO']?>');"> 보내기 상품구매</button>
						<?php endif;?>
					</td>
				</tr>
				<?php 
				$search_val['listCount']--;
				endforeach;
				?>
				
			</tbody>
		</table>
		<!-- TABLE [END] -->
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
// 디바운스 함수
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// 디바운스된 검색 함수 (500ms 지연)
const debouncedSearch = debounce(function() {
    btn_search();
}, 500);

// AJAX 요청 관리를 위한 변수
let currentSearchRequest = null;

$(function () {
    // 타이핑 시 자동 검색 (디바운스 적용)
    $('#snm, #senm').on('input', function() {
        // 검색어가 2자 이상이거나 비어있을 때 자동 검색
        if (this.value.length >= 2 || this.value.length === 0) {
            debouncedSearch();
        }
    });
    
    // 선택 박스 변경시 자동 검색
    $('#ssstat, #sdUnit, #sdcon').on('change', function() {
        btn_search();
    });
    
    // 초기 페이지 로드 시 검색 실행
    btn_search();
    
    // 페이저 이벤트 바인딩
    bindPagerEvents();

    $('#sdate').datepicker({
		format: 'yyyy-mm-dd',
		language: 'ko',
		autoclose: true,
		todayHighlight: true
	}).on('changeDate', function (e) {
		if ($('#sdUnit').val() === 'Day') {
			const selectedDate = $('#sdate').val();
			$('#edate').val(selectedDate);
			btn_search();
		}
	});

	$('#edate').datepicker({
			autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
			language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
	});

	$("#nthUnit").datepicker({
		monthNames: [ "1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월" ],
		monthNamesShort: [ "1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월" ],
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm',
		language: 'ko',
		onClose: function(dateText, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			var selectedDate = new Date(year, month, 1);
			$(this).datepicker('setDate', selectedDate);

			// 현재 sdUnit이 Week 또는 Month인지 확인
			const unit = $("#sdUnit").val();
			const $sdate = $("#sdate");
			const $edate = $("#edate");

			if (unit === 'Month') {
				const lastDay = new Date(year, parseInt(month) + 1, 0);
				$sdate.val(formatDate(new Date(year, month, 1)));
				$edate.val(formatDate(lastDay));
				$("#nthUnit").val(`${year}-${("0"+(parseInt(month)+1)).slice(-2)}`);
			}
			else if (unit === 'Week') {
				const sunday = new Date(year, month, 7); // 일단 2번째 주쯤을 기준으로 설정
				let week = getWeekOfMonth(sunday);      // 선택 월 기준 주차 계산
				let start = new Date(year, month, 1);
				start.setDate(start.getDate() - (start.getDay() === 0 ? 6 : start.getDay() - 1));
				let end = new Date(start);
				end.setDate(start.getDate() + 6);

				$sdate.val(formatDate(start));
				$edate.val(formatDate(end));
				$("#nthUnit").val(`${year}-${("0"+(parseInt(month)+1)).slice(-2)} ${week}주차`);
			}

			$(inst.dpDiv).removeClass('nthUnit-table-hidden');
		},
		beforeShow: function(input, inst) {

			$(inst.dpDiv).addClass('nthUnit-table-hidden');

			const datestr = $(input).val();
			if (datestr.length > 0) {
				const actDate = datestr.split('-');
				const year = actDate[0];
				const month = parseInt(actDate[1], 10) - 1;
				$(input).datepicker('option', 'defaultDate', new Date(year, month));
				$(input).datepicker('setDate', new Date(year, month));
			}
		}
	});
});

document.addEventListener('DOMContentLoaded', function () {
    const snmInput = document.getElementById('snm');

    if (snmInput) {
        snmInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // 폼 자동 제출 방지 (선택 사항)
                btn_search();           // 원하는 함수 호출
            }
        });
    }

	const senmInput = document.getElementById('senm');

    if (senmInput) {
        senmInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // 폼 자동 제출 방지 (선택 사항)
                btn_search();           // 원하는 함수 호출
            }
        });
    }
});

$('#sdate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$('#edate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});


$(".ss-input").on("keyup",function(key){
	if(key.keyCode==13) {
		btn_search();
	}
});

function btn_search()
{
	// 날짜 검색조건
	if ( $('#sdcon').val() != '' )
	{
		if ($('#sdate').val() == "" || $('#edate').val() == "")
		{
			alertToast('error','검색 시작일과 종료일을 입력하세요.');
			return;
		}
	}
	
	if ($('#sdate').val() != "" || $('#edate').val() != "")
	{
		if ( $('#sdcon').val() == '' )
		{
			alertToast('error','날짜검색 조건을 선택하세요.');
			return;
		}
	}
	
	// 이전 검색 요청이 있으면 취소
	if (currentSearchRequest && currentSearchRequest.readyState !== 4) {
		currentSearchRequest.abort();
	}
	
	// AJAX 검색 요청
	var formData = $('#form_search_send_event_manage').serialize();
	
	// 로딩 표시
	$('.panel-body table tbody').html('<tr><td colspan="14" class="text-center"><i class="fa fa-spinner fa-spin"></i> 검색중...</td></tr>');
	
	currentSearchRequest = $.ajax({
		url: '/teventmain/ajax_send_event_search',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(response) {
			if (response.result == 'true') {
				// 테이블 내용 업데이트
				$('.panel-body table tbody').html(response.html);
				
				// 페이저 업데이트
				$('ul.pagination.pagination-sm.m-0.float-left').html($(response.pager).html());
				
				// 총 건수 업데이트
				$('.panel-body').contents().filter(function() {
					return this.nodeType === 3 && this.textContent.includes('총 건수');
				}).first().replaceWith('총 건수 : ' + response.totalCount + ' 건');
				
				// 페이저 이벤트 재바인딩
				bindPagerEvents();
				
				// 검색어 하이라이트
				highlightSearchTerm();
			} else {
				alertToast('error', '검색 중 오류가 발생했습니다.');
			}
		},
		error: function(xhr) {
			// abort된 경우는 에러 메시지 표시하지 않음
			if (xhr.statusText !== 'abort') {
				alertToast('error', '검색 중 오류가 발생했습니다.');
				$('.panel-body table tbody').html('<tr><td colspan="14" class="text-center">검색 중 오류가 발생했습니다.</td></tr>');
			}
		},
		complete: function() {
			currentSearchRequest = null;
		}
	});
}

function send_event_buy(send_sno)
{
	location.href="/teventmain/event_buy_info/"+send_sno;
}

function send_event_cancel(send_sno)
{
	var params = "send_sno="+send_sno;
	jQuery.ajax({
        url: '/teventmain/ajax_send_event_cancel',
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
			
			console.log(json_result);
			
			if (json_result['result'] == 'true')
			{
				location.reload();
			} else 
			{
				alertToast('error','취소에 실패하였습니다.');
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

// 날짜 포맷 함수
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// 월의 몇 번째 주인지 계산
function getWeekOfMonth(date) {
    const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    const firstDayOfWeek = firstDay.getDay();
    const offsetDate = date.getDate() + firstDayOfWeek - 1;
    return Math.ceil(offsetDate / 7);
}

// 날짜 타입 변경 처리
function dateTypeCh(obj) {
    const unit = $(obj).val();
    const $sdate = $("#sdate");
    const $edate = $("#edate");
    const $nthUnit = $("#nthUnit");
    
    if (unit === 'Day') {
        $sdate.show().attr('type', 'text').prop('readonly', false);
        $edate.show().css('display', 'none');
        $nthUnit.css('display', 'none');
    } else if (unit === 'Week') {
        $sdate.show().attr('type', 'text').prop('readonly', true);
        $edate.show().css('display', 'block');
        $nthUnit.css('display', 'block');
    } else if (unit === 'Month') {
        $sdate.show().attr('type', 'text').prop('readonly', true);
        $edate.show().css('display', 'none');
        $nthUnit.css('display', 'block');
    }
    
    // 날짜 변경 후 자동 검색
    btn_search();
}

// 이전 버튼 클릭
function prevBtnClick() {
    const unit = $("#sdUnit").val();
    const $sdate = $("#sdate");
    const $edate = $("#edate");
    const $nthUnit = $("#nthUnit");
    
    if (unit === 'Day') {
        const currentDate = new Date($sdate.val());
        if (!isNaN(currentDate)) {
            currentDate.setDate(currentDate.getDate() - 1);
            $sdate.val(formatDate(currentDate));
            $edate.val(formatDate(currentDate));
        }
    } else if (unit === 'Week') {
        const currentStart = new Date($sdate.val());
        if (!isNaN(currentStart)) {
            currentStart.setDate(currentStart.getDate() - 7);
            const currentEnd = new Date(currentStart);
            currentEnd.setDate(currentEnd.getDate() + 6);
            
            $sdate.val(formatDate(currentStart));
            $edate.val(formatDate(currentEnd));
            
            const week = getWeekOfMonth(currentStart);
            $nthUnit.val(`${currentStart.getFullYear()}-${String(currentStart.getMonth() + 1).padStart(2, '0')} ${week}주차`);
        }
    } else if (unit === 'Month') {
        const currentNth = $nthUnit.val();
        if (currentNth) {
            const [year, month] = currentNth.split('-');
            const prevMonth = new Date(year, parseInt(month) - 1, 1);
            prevMonth.setMonth(prevMonth.getMonth() - 1);
            
            const lastDay = new Date(prevMonth.getFullYear(), prevMonth.getMonth() + 1, 0);
            $sdate.val(formatDate(prevMonth));
            $edate.val(formatDate(lastDay));
            $nthUnit.val(`${prevMonth.getFullYear()}-${String(prevMonth.getMonth() + 1).padStart(2, '0')}`);
        }
    }
    
    btn_search();
}

// 다음 버튼 클릭
function nextBtnClick() {
    const unit = $("#sdUnit").val();
    const $sdate = $("#sdate");
    const $edate = $("#edate");
    const $nthUnit = $("#nthUnit");
    
    if (unit === 'Day') {
        const currentDate = new Date($sdate.val());
        if (!isNaN(currentDate)) {
            currentDate.setDate(currentDate.getDate() + 1);
            $sdate.val(formatDate(currentDate));
            $edate.val(formatDate(currentDate));
        }
    } else if (unit === 'Week') {
        const currentStart = new Date($sdate.val());
        if (!isNaN(currentStart)) {
            currentStart.setDate(currentStart.getDate() + 7);
            const currentEnd = new Date(currentStart);
            currentEnd.setDate(currentEnd.getDate() + 6);
            
            $sdate.val(formatDate(currentStart));
            $edate.val(formatDate(currentEnd));
            
            const week = getWeekOfMonth(currentStart);
            $nthUnit.val(`${currentStart.getFullYear()}-${String(currentStart.getMonth() + 1).padStart(2, '0')} ${week}주차`);
        }
    } else if (unit === 'Month') {
        const currentNth = $nthUnit.val();
        if (currentNth) {
            const [year, month] = currentNth.split('-');
            const nextMonth = new Date(year, parseInt(month) - 1, 1);
            nextMonth.setMonth(nextMonth.getMonth() + 1);
            
            const lastDay = new Date(nextMonth.getFullYear(), nextMonth.getMonth() + 1, 0);
            $sdate.val(formatDate(nextMonth));
            $edate.val(formatDate(lastDay));
            $nthUnit.val(`${nextMonth.getFullYear()}-${String(nextMonth.getMonth() + 1).padStart(2, '0')}`);
        }
    }
    
    btn_search();
}

// 페이저 이벤트 바인딩
function bindPagerEvents() {
    $('.card-footer a').off('click').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        if (href && href !== '#') {
            var url = new URL(href, window.location.origin);
            var page = url.searchParams.get('page') || 1;
            
            // 검색 폼에 페이지 번호 추가
            $('#form_search_send_event_manage').append('<input type="hidden" name="page" value="' + page + '">');
            btn_search();
            // 페이지 번호 제거
            $('#form_search_send_event_manage input[name="page"]').remove();
        }
    });
}

// 검색어 하이라이트
function highlightSearchTerm() {
    var searchTerm = $('#snm').val().trim();
    if (searchTerm.length >= 2) {
        // 회원명에서 검색어 하이라이트
        $('.panel-body table tbody tr').each(function() {
            var $nameCell = $(this).find('td:eq(1)'); // 회원명 컬럼
            highlightInCell($nameCell, searchTerm);
        });
    }
    
    var eventSearchTerm = $('#senm').val().trim();
    if (eventSearchTerm.length >= 2) {
        // 판매상품명에서 검색어 하이라이트
        $('.panel-body table tbody tr').each(function() {
            var $eventCell = $(this).find('td:eq(5)'); // 판매상품명 컬럼
            highlightInCell($eventCell, eventSearchTerm);
        });
    }
}

// 셀 내용 하이라이트
function highlightInCell(cell, searchTerm) {
    // 텍스트 노드만 처리
    cell.contents().each(function() {
        if (this.nodeType === 3) { // 텍스트 노드인 경우
            var text = $(this).text();
            if (text && text.toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
                // 텍스트에서 검색어 하이라이트
                var regex = new RegExp('(' + escapeRegExp(searchTerm) + ')', 'gi');
                var highlightedText = text.replace(regex, '<mark style="background-color: yellow;">$1</mark>');
                $(this).replaceWith(highlightedText);
            }
        } else if (this.nodeType === 1) { // 엘리먼트 노드인 경우
            // 자식 노드가 있으면 재귀적으로 처리 (img 태그는 제외)
            if (this.tagName.toLowerCase() !== 'img' && this.tagName.toLowerCase() !== 'mark') {
                highlightInCell($(this), searchTerm);
            }
        }
    });
}

// 정규식 특수문자 이스케이프
function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// 날짜 검색조건 변경 처리
function dateConCh(obj) {
    if ($(obj).val() != '') {
        $('#sdate').prop('disabled', false);
        $('#edate').prop('disabled', false);
    } else {
        $('#sdate').prop('disabled', true);
        $('#edate').prop('disabled', true);
        $('#sdate').val('');
        $('#edate').val('');
    }
}

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

</script>