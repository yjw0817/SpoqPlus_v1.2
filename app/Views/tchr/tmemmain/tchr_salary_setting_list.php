<style>
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
?>
<h1 class="page-header"><?php echo $title ?></h1>



<div class="panel panel-inverse">
        <div class="card-header py-3">
            <h5 class="m-0 text-white">강사 검색</h5>
        </div>
<form name="form_search_tchr_salary_setting_list" id="form_search_tchr_salary_setting_list" method="post" onsubmit="return false;">
   <div class="bbs_search_box_a3 mb10 mt10">
		<ul>
		  <li>ㆍ 강사명  </li>
		  <li style="width: 300px;">
			<input type="text" class="form-control ss-input" name="snm" id="snm"   value="<?php echo $search_val['snm']?>" placeholder="강사명 검색 (Enter 또는 자동 검색)" style="width: 100%;">
		  </li>
		  <li>
			 <select class="form-control"  name="spcond" id="spcond">
				<option value="">지급조건</option>
				<?php foreach ($sDef['SARLY_PMT_COND'] as $r => $v) : ?>
				<?php if($r != '' && $r != '01') :?>
					<option value="<?php echo $r?>" <?php if($search_val['spcond'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>
		  </li>
		  <li>
			 <select class="form-control"  name="spmthdnm" id="spmthdnm">
				<option value="">지급방법</option>
				<?php foreach ($sDef['SARLY_PMT_MTHD_NAME'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val['spmthdnm'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>
		  </li>
		 </ul>
	</div>	
	<div class="bbs_search_box_a4 mb10">
		<ul>
		  <li>ㆍ 날짜검색  </li>
		 
		  <li>
			 <select class="form-control"  name="sdcon" id="sdcon" onchange="dateConCh(this);">
				<option value="">날짜검색조건</option>
				<option value="ss" <?php if($search_val['sdcon'] == "ss") {?> selected <?php } ?> >적용시작일</option>
				<option value="se" <?php if($search_val['sdcon'] == "se") {?> selected <?php } ?> >적용종료일</option>
				<option value="sc" <?php if($search_val['sdcon'] == "sc") {?> selected <?php } ?> >등록일시</option>
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






<!-- <div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">강사 검색</h4>
	</div>				

	<div class="card-footer clearfix">
		<form name="form_search_tchr_salary_setting_list" id="form_search_tchr_salary_setting_list" method="post" action="/tmemmain/tchr_salary_setting_list">
			<div class="input-group input-group-sm mb-1">
				<span class="input-group-append">
					<span class="input-group-text" style='width:120px;margin-left:5px;'>강사명</span>
				</span>
				<input type="text" class="ss-input" name="snm" id="snm" style='width:100px' value="<?php echo $search_val['snm']?>" >
				
				<select class=" " style="width: 150px;margin-left:5px;" name="spcond" id="spcond">
				<option value="">지급조건</option>
				<?php foreach ($sDef['SARLY_PMT_COND'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val['spcond'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
				</select>
				
				<select class=" " style="width: 350px;margin-left:5px;" name="spmthdnm" id="spmthdnm">
				<option value="">지급방법</option>
				<?php foreach ($sDef['SARLY_PMT_MTHD_NAME'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val['spmthdnm'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
				</select>
				
				<button type="button" class="btn btn-sm btn-success" style='margin-left:5px;' onclick="btn_search();">검색</button>
				
			</div>
			
			<div class="input-group input-group-sm mb-1">
			<select class=" " style="width: 220px;margin-left:5px;" name="sdcon" id="sdcon" onchange="dateConCh(this);">
				<option value="">날짜검색조건</option>
				<option value="ss" <?php if($search_val['sdcon'] == "ss") {?> selected <?php } ?> >적용시작일</option>
				<option value="se" <?php if($search_val['sdcon'] == "se") {?> selected <?php } ?> >적용종료일</option>
				<option value="sc" <?php if($search_val['sdcon'] == "sc") {?> selected <?php } ?> >등록일시</option>
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
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">강사 수당 설정 리스트</h4>
		<div class="panel-heading-btn">
		</div>
	</div>
		<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
						<!-- TABLE [START] -->
						<!-- TABLE [START] -->
		<table class="table table-bordered table-hover table-striped col-md-12">
			<thead>
				<tr class='text-center'>
					<th style='width:150px'>강사 명</th>
					<th style='width:150px'>적용 시작일</th>
					<th style='width:150px'>적용 종료일</th>
					<th style='width:100px'>지급조건</th>
					<th>지급방법</th>
					<th style='width:140px'>등록일시</th>
					<th style='width:140px'></th>
				</tr>
			</thead> 
			<tbody>
				<?php 
				foreach($list_sarly as $r) :
					/*
						<?php echo $sDef['PAYMT_STAT'][$r['PAYMT_STAT']]?>
						*/
				?>
				<tr id="row_<?php echo $r['SARLY_MGMT_SNO']; ?>"> <!-- 고유 ID 추가 -->
					<td>
						<?php if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) : ?>
							<img class="preview_mem_photo"
								id="preview_mem_photo_<?php echo $r['SARLY_MGMT_SNO']?>"
								src="<?php echo isset($r['MEM_THUMB_IMG']) ? $r['MEM_THUMB_IMG'] : '' ?>"
								alt="강사사진"
								style="cursor: pointer;"
								onclick="showFullPhoto('<?php echo isset($r['MEM_MAIN_IMG']) ? $r['MEM_MAIN_IMG'] : '' ?>')"
								onerror="this.onerror=null; this.src='/dist/img/default_profile_<?php echo isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M' ?>.png';">
						<?php endif; ?>
						<?php echo $r['TCHR_NM']?> (<?php echo $r['TCHR_ID']?>)
					</td>
					<td><?php echo $r['SARLY_APLY_S_DATE']?></td>
					<td><?php echo $r['SARLY_APLY_E_DATE']?></td>
					<td><?php echo $sDef['SARLY_PMT_COND'][$r['SARLY_PMT_COND']]?></td>
					<td><?php
							echo $sDef['SARLY_PMT_MTHD_NAME'][$r['SARLY_PMT_MTHD']];
							if (isset($r['SUB_COUNT']) && intval($r['SUB_COUNT']) > 0) {
								echo " (수당설정 있음)";
							}
						?></td>
					<td><?php echo $r['CRE_DATETM']?></td>
					<td style="text-align:center">
						<button type="button" class="btn btn-block btn-primary btn-xs" id="btn_sarly_mod" onclick="sarly_mod('<?php echo $r['SARLY_MGMT_SNO']?>');">수정하기</button>
						<button type="button" class="btn btn-block btn-warning btn-xs" id="btn_sarly_mod" onclick="sarly_del('<?php echo $r['SARLY_MGMT_SNO']?>');">삭제하기</button>
					</td>
				</tr>
				<?php 
				endforeach;
				?>
				
			</tbody>
		</table>
		<!-- TABLE [END] -->
        <div class="card-footer clearfix">

		<ul class="pagination pagination-sm m-0 float-right">
			<li class="ac-btn"><button type="button" class="btn btn-block btn-success btn-sm" onclick="create_sarly();">등록하기</button></li>
		</ul>

		<!-- PAGZING [START] -->
		<?php echo $pager?>
		<!-- PAGZING [END] -->

	</div>
	</div>
</div>
			
<!-- ############################## MODAL [ SATRT ] #################################### -->
	
<!-- ############################## MODAL [ END ] ###################################### -->
	
	
	


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
    
    if ( $('#sdcon').val() != '')
    {
    	$('#sdate').prop('disabled',false);
		$('#edate').prop('disabled',false);
    }
    
    // 타이핑 시 자동 검색 (디바운스 적용)
    $('#snm').on('input', function() {
        // 검색어가 2자 이상이거나 비어있을 때 자동 검색
        if (this.value.length >= 2 || this.value.length === 0) {
            debouncedSearch();
        }
    });
    
    // 검색 조건 변경시 자동 검색
    $('#spcond, #spmthdnm').on('change', function() {
        btn_search();
    });
    
    // 날짜 검색조건 변경시 
    $('#sdcon').on('change', function() {
        dateConCh(this);
    });
    
    // 날짜 입력시 자동 검색
    $('#sdate, #edate').on('change', function() {
        // 날짜 검색조건이 선택되어 있을 때 자동 검색
        if($('#sdcon').val() != '') {
            btn_search();
        }
    });
    
    // 초기 페이저 이벤트 바인딩
    bindPagerEvents();
});

$('#sdate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$('#edate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

// 엔터키 이벤트 (이미 input 이벤트에서 처리되므로 엔터키만 따로 처리)
$('#snm').on('keydown', function(event) {
    if (event.key === 'Enter' || event.keyCode === 13) {
        event.preventDefault();
        btn_search();
    }
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
	var formData = $('#form_search_tchr_salary_setting_list').serialize();
	
	// 로딩 표시
	$('.panel-body table tbody').html('<tr><td colspan="7" class="text-center"><i class="fa fa-spinner fa-spin"></i> 검색중...</td></tr>');
	
	currentSearchRequest = $.ajax({
		url: '/tmemmain/ajax_tchr_salary_search',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(response) {
			if (response.result == 'true') {
				// 테이블 내용 업데이트
				$('.panel-body table tbody').html(response.html);
				
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
				$('.panel-body table tbody').html('<tr><td colspan="7" class="text-center">검색 중 오류가 발생했습니다.</td></tr>');
			}
		},
		complete: function() {
			currentSearchRequest = null;
		}
	});
}

function create_sarly()
{
	location.href="/tmemmain/tchr_salary_setting/";
}

function sarly_mod(sarly_mgmt_sno)
{
	location.href="/tmemmain/tchr_salary_setting/"+sarly_mgmt_sno;
}

function sarly_del(sarly_mgmt_sno)
{
	if (!confirm("정말 삭제하시겠습니까?")) return;
	var params = {sarly_mgmt_sno: sarly_mgmt_sno};
    jQuery.ajax({
        url: '/tmemmain/ajax_sarly_del',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
			const json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				$('#row_' + sarly_mgmt_sno).remove();
				alertToast('success', '수당설정이 삭제되었습니다.');
			} else 
			{
				alertToast('error','정보를 불러올 수 없습니다.');
			}
        },
		error: function () {
			try {
				if (data.status == 403) {
					alert(data.responseJSON.message);
					fnLoginRedirect();

				} else {
					alert("작업에 실패 하였습니다.");
					console.log("error:::" + data);
				}
				
			}
			catch(e) {
				alert("작업에 실패 하였습니다.");
				
			}
		}
    });
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

function showFullPhoto(imageSrc) {
    if (!imageSrc || imageSrc === '') {
        alertToast('info', '원본 사진이 없습니다.');
        return;
    }
    
    // 모달에서 이미지 보여주기
    Swal.fire({
        imageUrl: imageSrc,
        imageAlt: '강사 사진',
        showConfirmButton: false,
        showCloseButton: true,
        width: 'auto'
    });
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
            $('#form_search_tchr_salary_setting_list').append('<input type="hidden" name="page" value="' + page + '">');
            btn_search();
            // 페이지 번호 제거
            $('#form_search_tchr_salary_setting_list input[name="page"]').remove();
        }
    });
}

// 검색어 하이라이트
function highlightSearchTerm() {
    var searchTerm = $('#snm').val().trim();
    if (searchTerm.length >= 2) {
        // 강사명에서 검색어 하이라이트
        $('.panel-body table tbody tr').each(function() {
            var $nameCell = $(this).find('td:eq(0)'); // 강사명 컬럼
            var nameText = $nameCell.text();
            if (nameText) {
                // 이미지 태그를 제외하고 텍스트만 하이라이트
                var $img = $nameCell.find('img').detach();
                var highlightedText = nameText.replace(new RegExp('(' + searchTerm + ')', 'gi'), '<mark>$1</mark>');
                $nameCell.html(highlightedText);
                if ($img.length > 0) {
                    $nameCell.prepend($img);
                }
            }
        });
    }
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

</script>