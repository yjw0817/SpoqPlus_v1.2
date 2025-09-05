<style>

	.preview_mem_photo {
		width: 30px;
		height: 30px;
		object-fit: cover;
		border-radius: 50%;
		border: 1px solid #ccc;
	}
</style>

<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
	<!-- CARD HEADER [START] -->
<h1 class="page-header"><?php echo $title ?></h1>

<div class="panel panel-inverse">
        <div class="card-header py-3">
            <h6 class="m-0 text-white">회원 검색</h6>
        </div>
<form name="form_search_mem_attd_manage" id="form_search_mem_attd_manage" method="post" onsubmit="return false;">
   <div class="bbs_search_box_a3 mb10 mt10">
		<ul>
		  <li>ㆍ 회원명  </li>
		  <li style="width: 300px;">
			<input type="text" class="form-control"  name="snm" id="snm"  value="<?php echo $search_val['snm']?>" placeholder="회원명 검색 (Enter 또는 자동 검색)" style="width: 100%;">
		  </li>
		  <li>
			 <select class="form-control"  name="satc" id="satc">
				<option value="">정상/재입장</option>
				<option value="Y" <?php if($search_val['satc'] == "Y") {?> selected <?php } ?>>정상</option>
				<option value="N" <?php if($search_val['satc'] == "N") {?> selected <?php } ?>>재입장</option>
			</select>
		  </li>
		  <li>
			 <select class="form-control"   name="scyn" id="scyn">
				<option value="">수업차감여부</option>
				<option value="Y" <?php if($search_val['scyn'] == "Y") {?> selected <?php } ?>>수업차감</option>
				<option value="N" <?php if($search_val['scyn'] == "N") {?> selected <?php } ?>>수업차감X</option>
			</select>
		  </li>
		 </ul>
	</div>	
	 <div class="bbs_search_box_a4 mb10">
		<ul>
		  <li>ㆍ 날짜검색  </li>
		 
		  <li>
			 <select class="form-control" name="sdcon" id="sdcon" onchange="dateConCh(this);">
				<option value="">날짜검색조건</option>
				<option value="ac" <?php if($search_val['sdcon'] == "ac") {?> selected <?php } ?> >출석일시</option>
			</select>
		  </li>
		  <li>
			<input type="text" class="form-control" name="sdate" id="sdate"  placeholder="검색시작일"  value="<?php echo $search_val['sdate']?>" autocomplete='off' disabled>
		  </li>
          <li>
			<input type="text" class="form-control" name="edate" id="edate" placeholder="검색종료일" value="<?php echo $search_val['edate']?>" autocomplete='off' disabled>
		  </li>
		 </ul>
	</div>
  </form>
</div>


<div class="panel panel-inverse">
	<div class="card-header py-3">
            <h6 class="m-0 text-white">출석회원 리스트</h6>
        </div>
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		<!-- TABLE [START] -->
		총 건수 : <?php echo $totalCount?> 건
		<table class="table table-bordered table-hover col-md-12 mt20">
			<thead>
				<tr class='text-center'>
					<th style='width:55px'>순번</th>
					<th style='width:80px'>날짜</th>
					<th style='width:80px'>시간</th>
					<th style='width:50px'>출석요일</th>
					<th style='width:150px'>출석회원</th>
					<th style='width:100px'>정상/재입장</th>
					<th style='width:120px'>수업차감여부</th>
					<th style='width:700px'>입장내역 상세</th>
				</tr>
			</thead> 
			<tbody>
				<?php 
				foreach($attd_list as $r) :
				
				
				if ($r['ATTD_YN'] == 'Y') : 
					$backColor = "";
					$attd_word = "정상";
				else :
					$backColor = "#cfecf0";
					$attd_word = "재입장";
				endif;
				
				if ($r['CLAS_MGMT_SNO'] != '') :
					$auto_word = "수업차감";
				else :
					$auto_word = "";
				endif;
					/*
						<?php echo $sDef['PAYMT_STAT'][$r['PAYMT_STAT']]?>
						*/
				?>
				<tr style="background-color: <?php echo $backColor ?>;">
					<td class='text-center'><?php echo $search_val['listCount']?></td>
					<td class='text-center'><?php echo $r['ATTD_DT']?></td>
					<td class='text-center'><?php echo $r['ATTD_HM']?></td>
					<td class='text-center'><?php echo $r['ATTD_DOTW']?>요일</td>
					<td class='text-center'>
						<img class="preview_mem_photo"
							id="preview_mem_photo"
							src="<?php echo $r['MEM_THUMB_IMG'] ?>"
							alt="회원사진"
							style="border-radius: 50%; cursor: pointer;"
							onclick="showFullPhoto('<?php echo $r['MEM_MAIN_IMG'] ?>')"
							onerror="this.onerror=null; this.src='/dist/img/default_profile_<?php echo $r['MEM_GENDR'] ?>.png';">
						<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info('<?php echo $r['MEM_SNO']; ?>');">
						<?php echo $r['MEM_NM']?>(<?php echo $r['MEM_ID']?>)
					</td>
					<td class='text-center'><?php echo $attd_word?></td>
					<td class='text-center'><?php echo $auto_word?></td>
					<td>
						<?php echo $r['SELL_EVENT_NM']?>
						<?php if($r['GX_STCHR_NM'] != '') : ?>
							/ 강사:<?php echo $r['GX_STCHR_NM']?>
							/ 수업:<?php echo $r['GX_CLAS_TITLE']?>
							/ 시간:<?php echo $r['GX_CLAS_S_HH_II']?> ~ <?php echo $r['GX_CLAS_E_HH_II']?>
						<?php elseif($r['STCHR_NM'] != '') : ?>
							/ 강사:<?php echo $r['STCHR_NM']?>
							/ 수업수:<?php echo $r['CLAS_CNT']?>
						<?php endif; ?>
					</td>
				</tr>
				<?php 
				$search_val['listCount']--;
				endforeach;
				?>
				
			</tbody>
		</table>
		<!-- TABLE [END] -->
		<!-- TABLE [END] -->
	</div>
	
	<?php echo $pager ?><br/><br/>
	<!-- CARD BODY [END] -->
	<!-- CARD FOOTER [START] -->
	<!-- CARD FOOTER [END] -->
			
</div>
<br/><br/>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
	
<!-- ############################## MODAL [ END ] ###################################### -->
	
	
	
</section>


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
    
    if ( $('#sdcon').val() != '')
    {
    	$('#sdate').prop('disabled',false);
		$('#edate').prop('disabled',false);
    }
})

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
});

$('#sdate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$('#edate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});


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

// 검색어 입력 이벤트
$('#snm').on('keydown', function(event) {
    if (event.key === 'Enter' || event.keyCode === 13) {
        event.preventDefault();
        btn_search();
    }
});

// 타이핑 시 자동 검색 (디바운스 적용)
$('#snm').on('input', function() {
    // 검색어가 2자 이상이거나 비어있을 때 자동 검색
    if (this.value.length >= 2 || this.value.length === 0) {
        debouncedSearch();
    }
});

// 정상/재입장 선택 변경 시 자동 검색
$('#satc').on('change', function() {
    btn_search();
});

// 수업체크 선택 변경 시 자동 검색
$('#scyn').on('change', function() {
    btn_search();
});

// 날짜 입력시 자동 검색 (날짜가 하나라도 입력되었을 때)
$('#sdate, #edate').on('change', function() {
    // 날짜 검색조건이 선택되어 있을 때 자동 검색
    if($('#sdcon').val() != '') {
        btn_search();
    }
});

// AJAX 요청 관리를 위한 변수
let currentSearchRequest = null;

function btn_search()
{
	// 날짜 검색조건 - 날짜가 입력되었을 때만 검색조건이 필요
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
	var formData = $('#form_search_mem_attd_manage').serialize();
	
	// 로딩 표시
	$('.panel-body table tbody').html('<tr><td colspan="8" class="text-center"><i class="fa fa-spinner fa-spin"></i> 검색중...</td></tr>');
	
	currentSearchRequest = $.ajax({
		url: '/teventmem/ajax_mem_attd_search',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(response) {
			if (response.result == 'true') {
				// 테이블 내용 업데이트
				$('.panel-body table tbody').html(response.html);
				
				// 전체 건수 업데이트
				$('.panel-body').find('table').prev().html('총 건수 : ' + response.totalCount + ' 건');
				
				// 페이저 업데이트
				
				$('ul.pagination.pagination-sm.m-0.float-left').html($(response.pager).html());
				
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
				$('.panel-body table tbody').html('<tr><td colspan="8" class="text-center">검색 중 오류가 발생했습니다.</td></tr>');
			}
		},
		complete: function() {
			currentSearchRequest = null;
		}
	});
}

// 페이저 이벤트 바인딩 함수
function bindPagerEvents() {
	// 페이지 링크 클릭 이벤트
	$('.pagination a').off('click').on('click', function(e) {
		e.preventDefault();
		var href = $(this).attr('href');
		if (href && href != '#') {
			// URL에서 페이지 파라미터 추출
			var page = getParameterByName('page', href);
			if (page) {
				// 현재 폼 데이터에 페이지 추가
				var formData = $('#form_search_mem_attd_manage').serialize() + '&page=' + page;
				ajaxLoadPage(formData);
			}
		}
	});
}

// URL에서 파라미터 값 추출
function getParameterByName(name, url) {
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, '\\$&');
	var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
		results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

// AJAX로 페이지 로드
function ajaxLoadPage(formData) {
	// 로딩 표시
	$('.panel-body table tbody').html('<tr><td colspan="8" class="text-center"><i class="fa fa-spinner fa-spin"></i> 로딩중...</td></tr>');
	
	$.ajax({
		url: '/teventmem/ajax_mem_attd_search',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(response) {
			if (response.result == 'true') {
				// 테이블 내용 업데이트
				$('.panel-body table tbody').html(response.html);
				
				// 전체 건수 업데이트
				$('.panel-body').find('table').prev().html('총 건수 : ' + response.totalCount + ' 건');
				
				// 페이저 업데이트
				$('ul.pagination.pagination-sm.m-0.float-left').html($(response.pager).html());
				
				// 페이저 이벤트 재바인딩
				bindPagerEvents();
				
				// 스크롤을 테이블 상단으로 이동
				$('html, body').animate({
					scrollTop: $('.panel-body').offset().top - 100
				}, 300);
				
				// 검색어 하이라이트
				highlightSearchTerm();
			} else {
				alertToast('error', '페이지 로딩 중 오류가 발생했습니다.');
			}
		},
		error: function() {
			alertToast('error', '페이지 로딩 중 오류가 발생했습니다.');
			$('.panel-body table tbody').html('<tr><td colspan="8" class="text-center">페이지 로딩 중 오류가 발생했습니다.</td></tr>');
		}
	});
}

// 검색어 하이라이트 함수
function highlightSearchTerm() {
	var searchTerm = $('#snm').val().trim();
	if (searchTerm.length > 0) {
		// 회원명(아이디) 컬럼에서 검색어 하이라이트
		$('.panel-body table tbody tr').each(function() {
			// 회원명 컬럼 (5번째, 인덱스 4)
			var cell = $(this).find('td:eq(4)');
			highlightCell(cell, searchTerm);
		});
	}
}

// 셀 내용 하이라이트
function highlightCell(cell, searchTerm) {
	// 링크 태그 내의 텍스트를 찾아서 하이라이트
	var links = cell.find('a');
	if (links.length > 0) {
		links.each(function() {
			var linkText = $(this).text();
			if (linkText.toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
				var highlightedText = linkText.replace(new RegExp('(' + escapeRegExp(searchTerm) + ')', 'gi'), '<mark style="background-color: yellow;">$1</mark>');
				$(this).html(highlightedText);
			}
		});
	} else {
		// 일반 텍스트 하이라이트
		var cellText = cell.text();
		if (cellText.toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
			var cellHtml = cell.html();
			var highlightedHtml = cellHtml.replace(new RegExp('(' + escapeRegExp(searchTerm) + ')', 'gi'), '<mark style="background-color: yellow;">$1</mark>');
			cell.html(highlightedHtml);
		}
	}
}

// 정규식 특수문자 이스케이프
function escapeRegExp(string) {
	return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// 초기 페이저 이벤트 바인딩
$(document).ready(function() {
	bindPagerEvents();
	// 페이지 로드 시 검색어가 있으면 하이라이트 적용
	highlightSearchTerm();
});

// 날짜 검색조건 변경 시 호출되는 함수
function dateConCh(t){
	if($(t).val() != ''){
		$('#sdate').prop('disabled',false);
		$('#edate').prop('disabled',false);
		// 날짜 검색조건이 선택되면 자동 검색 (기존 날짜가 있을 때)
		if($('#sdate').val() != '' || $('#edate').val() != ''){
			btn_search();
		}
	}else{
		$('#sdate').prop('disabled',true);
		$('#edate').prop('disabled',true);
		$('#sdate').val('');
		$('#edate').val('');
		// 날짜 검색조건이 해제되면 자동 검색 (전체 조회)
		btn_search();
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

// 회원별 통합정보 보기
function mem_manage_mem_info(mem_sno)
{
	location.href="/ttotalmain/info_mem/"+mem_sno;
}

</script>