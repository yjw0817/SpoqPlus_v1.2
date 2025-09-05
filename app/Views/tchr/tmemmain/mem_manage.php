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
<h1 class="page-header"><?php echo $title ?></h1>
<!-- CARD HEADER [END] -->



<div class="panel panel-inverse">
	<div class="card-header py-3">
		<h5 class="m-0 text-white">회원 검색</h5>
	</div>
	<form name="form_search_mem_manage" id="form_search_mem_manage" method="post" onsubmit="return false;">
	<div class="bbs_search_box_a mt10 mb10">
		<ul>
			<li>ㆍ 검색어  </li>
			<li style="width: 300px;">
				<input type="text" class="form-control ss-input" name="snm" id="snm" value="<?php echo $search_val['snm']?>" placeholder="회원명, 아이디, 전화번호 검색 (Enter 또는 자동 검색)" style="width: 100%;">
			</li>
		</ul>
	</div>
	<div class="bbs_search_box_b mb10">
			<ul>
			<li>ㆍ 회원상태  </li>
			<li style="white-space: nowrap;">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="smst" id="smst_all" value="" <?php if(empty($search_val['smst'])) {?> checked <?php } ?>>
                    <label class="form-check-label" for="smst_all">전체회원</label>
                </div>
				<div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="smst" id="smst_current" value="00" <?php if($search_val['smst'] == '00') {?> checked <?php } ?>>
                    <label class="form-check-label" for="smst_current">가입회원</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="smst" id="smst_current" value="01" <?php if($search_val['smst'] == '01') {?> checked <?php } ?>>
                    <label class="form-check-label" for="smst_current">현재회원</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="smst" id="smst_expired" value="90" <?php if($search_val['smst'] == '90') {?> checked <?php } ?>>
                    <label class="form-check-label" for="smst_expired">만료회원</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="smst" id="smst_unpaid" value="99" <?php if($search_val['smst'] == '99') {?> checked <?php } ?>>
                    <label class="form-check-label" for="smst_unpaid">탈퇴회원</label>
                </div>
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

	<div class="bbs_search_box_a2 mb10">
			<ul>
			<li>ㆍ 가입장소  </li>
			<li>
				<select class="form-control"  name="sjp" id="sjp">
					<option value="">선택</option>
					<?php foreach ($sDef['JON_PLACE'] as $r => $v) : ?>
					<?php if($r != '') :?>
						<option value="<?php echo $r?>" <?php if($search_val['sjp'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
					<?php endif; ?>
					<?php endforeach; ?>
				</select>

			</li>
			<li>
				<select class="form-control"  name="sdcon" id="sdcon" onchange="dateConCh(this);">
						<option value="">날짜검색조건</option>
						<option value="jon" <?php if($search_val['sdcon'] == "jon") {?> selected <?php } ?> >가입일시</option>
						<option value="reg" <?php if($search_val['sdcon'] == "reg") {?> selected <?php } ?> >등록일시</option>
						<option value="rereg" <?php if($search_val['sdcon'] == "rereg") {?> selected <?php } ?> >재등록일시</option>
						<option value="end" <?php if($search_val['sdcon'] == "end") {?> selected <?php } ?> >종료일시</option>
					</select>

			</li>
			<li>
				<input type="text" class="form-control" name="sdate" id="sdate" placeholder="검색시작일" value="<?php echo $search_val['sdate']?>" autocomplete='off' disabled >

			</li>
			<li>
				<input type="text" class="form-control" name="edate" id="edate" placeholder="검색종료일" value="<?php echo $search_val['edate']?>" autocomplete='off' disabled>
			</li>
			</ul>
		</div>	
	</form>
</div>




<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">회원 리스트</h4>
		<div class="panel-heading-btn">
		</div>
	</div>
	
	<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive ">
		<!-- TABLE [START] -->
		총 회원수 : <?php echo $totalCount?> 명
		<table class="table table-bordered table-hover col-md-12 mt20">
			<thead>
				<tr class='text-center '>
					<th style='width:60px'>#</th>
					<th style='width:70px'>상태</th>
					<th style='width:140px'>이름</th>
					<th style='width:80px'>아이디</th>
					<th style='width:120px'>전화번호</th>
					<th style='width:100px'>생년월일</th>
					<th style='width:50px'>성별</th>
					<th>주소</th>
					<th>가입장소</th>
					<th style='width:120px'>가입일시</th>
					<th style='width:120px'>등록일시</th>
					<th style='width:120px'>재등록일시</th>
					<th style='width:120px'>종료일시</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$listCount = $search_val['listCount'];
				foreach($mem_list as $r) :
					$backColor = "";
					if ($r['MEM_STAT'] == "00") $backColor = "#cfecf0"; // 가입회원
					if ($r['MEM_STAT'] == "90") $backColor = "#fee6ef"; // 종료회원
					if ($r['MEM_STAT'] == "99") $backColor = "#fdcce1"; // 탈퇴회원
				?>
				<tr style="background-color: <?php echo $backColor ?> !important;">
					<td class='text-center'><?php echo $listCount?></td>
					<td><?php echo $sDef['MEM_STAT'][$r['MEM_STAT']]?></td>
					<td>
						<img class="preview_mem_photo"
											id="preview_mem_photo"
											src="<?php echo $r['MEM_THUMB_IMG'] ?>"
											alt="회원사진"
											style="border-radius: 50%; cursor: pointer;"
											onclick="showFullPhoto('<?php echo $r['MEM_MAIN_IMG'] ?>')"
											onerror="this.onerror=null; this.src='/dist/img/default_profile_<?php echo $r['MEM_GENDR'] ?>.png';">
						<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info('<?php echo $r['MEM_SNO']; ?>');">
							<?php echo $r['MEM_NM']; ?>
						</a>
					</td>
					<td><?php echo $r['MEM_ID']?></td>
					<td><?php echo disp_phone($r['MEM_TELNO'])?></td>
					<td><?php echo disp_birth($r['BTHDAY'])?></td>
					<td><?php echo $sDef['MEM_GENDR'][$r['MEM_GENDR']]?></td>
					<td><?php echo $r['MEM_ADDR']?></td>
					<td><?php echo $sDef['JON_PLACE'][$r['JON_PLACE']]?></td>
					<td><?php echo substr($r['JON_DATETM'],0,16)?></td>
					<td><?php echo substr($r['REG_DATETM'],0,16)?></td>
					<td><?php echo substr($r['RE_REG_DATETM'],0,16)?></td>
					<td>
						<?php echo substr($r['END_DATETM'],0,16)?>
						<input type="hidden" id="men_sno" value="<?php echo $r['MEM_SNO']; ?>">
					</td>
					<!-- <td>
						<button type="button" class="btn btn-warning btn-xs" onclick="mem_manage_mem_info('<?php echo $r['MEM_SNO']?>');">정보보기</button>
					</td> -->
				</tr>
				<?php
				$listCount--;
				endforeach; 
				?>
			</tbody>
		</table>
		<!-- TABLE [END] -->
	</div>
	<!-- CARD BODY [END] -->
	<!-- CARD FOOTER [START] -->
	<?php echo $pager ?><br/>
	<!-- CARD BODY [END] -->
	<!-- CARD FOOTER [START] -->
	<!-- CARD FOOTER [END] -->
			
</div>
<br/><br/>
			
	
<!-- ############################## MODAL [ SATRT ] #################################### -->

<!-- ============================= [ modal-default START ] ======================================= -->	

<!-- ============================= [ modal-default END ] ======================================= -->
	
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

	// $(".panel-body table tbody").on('click', 'tr', function(){
	// 	var memSno = $(this).find('#men_sno').val();
	// 	mem_manage_mem_info(memSno);
	// });
})

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

$('#sdate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$('#edate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

// 회원별 통합정보 보기
function mem_manage_mem_info(mem_sno)
{
	location.href="/ttotalmain/info_mem/"+mem_sno;
}



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
	var formData = $('#form_search_mem_manage').serialize();
	
	// 로딩 표시
	$('.panel-body table tbody').html('<tr><td colspan="13" class="text-center"><i class="fa fa-spinner fa-spin"></i> 검색중...</td></tr>');
	
	currentSearchRequest = $.ajax({
		url: '/tmemmain/ajax_mem_search',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(response) {
			if (response.result == 'true') {
				// 테이블 내용 업데이트
				$('.panel-body table tbody').html(response.html);
				
				// 전체 회원수 업데이트
				$('.panel-body').find('table').prev().html('총 회원수 : ' + response.totalCount + ' 명');
				
				// 페이저 업데이트
				$('ul.pagination.pagination-sm.m-0.float-left').html($(response.pager).html());
				
				// 페이저 이벤트 재바인딩
				bindPagerEvents();
				
				// 검색어 하이라이트 (선택사항)
				highlightSearchTerm();
			} else {
				alertToast('error', '검색 중 오류가 발생했습니다.');
			}
		},
		error: function(xhr) {
			// abort된 경우는 에러 메시지 표시하지 않음
			if (xhr.statusText !== 'abort') {
				alertToast('error', '검색 중 오류가 발생했습니다.');
				$('.panel-body table tbody').html('<tr><td colspan="13" class="text-center">검색 중 오류가 발생했습니다.</td></tr>');
			}
		},
		complete: function() {
			currentSearchRequest = null;
		}
	});
}

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


function id_change(t)
{
	$('#id_chk').val("N");
	$('#id_chk_false').show();
	$('#id_chk_true').hide();
	
	
}

$('#mem_id').keyup(function(event){    
    if (!(event.keyCode >=37 && event.keyCode<=40)) 
    {    
        var inputVal = $(this).val();    
        $(this).val(inputVal.replace(/[^a-z0-9]/gi,''));   
    }  
});

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
					
				} else 
				{
					alertToast('error', json_result['msg']);
				}
			}
		}).done((res) => {
			console.log('통신성공');
			// 대분류 변경 후 자동 검색 실행
			btn_search();
		}).fail((error) => {
			console.log('통신실패');
			alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
			location.href='/tlogin';
			return;
		});
	} else {
		// 대분류가 비어있어도 자동 검색 실행
		btn_search();
	}
});







// 중분류 변경시 자동 검색
$('#search_cate2').change(function(){
	btn_search();
});

// 가입장소 변경시 자동 검색
$('#sjp').change(function(){
	btn_search();
});

// 회원상태 변경시 자동 검색
$('input[name="smst"]').change(function(){
	btn_search();
});

// 날짜 입력시 자동 검색 (날짜가 모두 입력되었을 때만)
$('#sdate, #edate').on('change', function(){
	// 날짜 검색조건이 선택되어 있을 때 자동 검색
	if($('#sdcon').val() != ''){
		btn_search();
	}
});

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
				var formData = $('#form_search_mem_manage').serialize() + '&page=' + page;
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
	$('.panel-body table tbody').html('<tr><td colspan="13" class="text-center"><i class="fa fa-spinner fa-spin"></i> 로딩중...</td></tr>');
	
	$.ajax({
		url: '/tmemmain/ajax_mem_search',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(response) {
			if (response.result == 'true') {
				// 테이블 내용 업데이트
				$('.panel-body table tbody').html(response.html);
				
				// 전체 회원수 업데이트
				$('.panel-body').find('table').prev().html('총 회원수 : ' + response.totalCount + ' 명');
				
				// 페이저 업데이트
				$('ul.pagination.pagination-sm.m-0.float-left').html($(response.pager).html());
				
				// 페이저 이벤트 재바인딩
				bindPagerEvents();
				
				// 스크롤을 테이블 상단으로 이동
				$('html, body').animate({
					scrollTop: $('.panel-body').offset().top - 100
				}, 300);
			} else {
				alertToast('error', '페이지 로딩 중 오류가 발생했습니다.');
			}
		},
		error: function() {
			alertToast('error', '페이지 로딩 중 오류가 발생했습니다.');
			$('.panel-body table tbody').html('<tr><td colspan="13" class="text-center">페이지 로딩 중 오류가 발생했습니다.</td></tr>');
		}
	});
}

// 초기 페이저 이벤트 바인딩
$(document).ready(function() {
	bindPagerEvents();
});

// 검색어 하이라이트 함수
function highlightSearchTerm() {
	var searchTerm = $('#snm').val().trim();
	if (searchTerm.length > 0) {
		// 이름, 아이디, 전화번호 컬럼에서 검색어 하이라이트
		$('.panel-body table tbody tr').each(function() {
			// 이름 컬럼 (3번째)
			var nameCell = $(this).find('td:eq(2)');
			var nameHtml = nameCell.html();
			if (nameHtml) {
				// a 태그 내부의 텍스트만 하이라이트
				nameHtml = nameHtml.replace(new RegExp('(' + escapeRegExp(searchTerm) + ')', 'gi'), '<mark style="background-color: yellow;">$1</mark>');
				nameCell.html(nameHtml);
			}
			
			// 아이디 컬럼 (4번째)
			var idCell = $(this).find('td:eq(3)');
			highlightCell(idCell, searchTerm);
			
			// 전화번호 컬럼 (5번째)
			var phoneCell = $(this).find('td:eq(4)');
			highlightCell(phoneCell, searchTerm);
		});
	}
}

// 셀 내용 하이라이트
function highlightCell(cell, searchTerm) {
	var cellText = cell.text();
	if (cellText && cellText.toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
		var highlightedText = cellText.replace(new RegExp('(' + escapeRegExp(searchTerm) + ')', 'gi'), '<mark style="background-color: yellow;">$1</mark>');
		cell.html(highlightedText);
	}
}

// 정규식 특수문자 이스케이프
function escapeRegExp(string) {
	return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

</script>