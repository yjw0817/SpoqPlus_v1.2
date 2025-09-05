<style>
/* .table th, .table td {
    padding: 0.3rem !important;
    font-size: 0.9rem;
}

.table-bordered th, .table-bordered td {
    border: 1px solid #a3a3a3;
}

table.table-hover tbody tr:hover {
    background-color: #81b1eb !important; 
} */

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
            <h6 class="m-0 text-white">검색 조건</h6>
        </div>
<form name="form_search_event_jend_hist" id="form_search_event_jend_hist" method="post" onsubmit="return false;">

   <div class="bbs_search_box_a mb10 mt10">
		<ul>
		  <li>ㆍ 회원명  </li>
		  <li>
			<input type="text" class="form-control"name="snm" id="snm"  value="<?php echo $search_val['snm']?>" >
		  </li>
		            <!-- 검색 버튼 제거 - 자동 검색으로 대체 -->
		 </ul>
	</div>	
	 <div class="bbs_search_box_a4 mb10">
		<ul>
		  <li>ㆍ 날짜검색  </li>
		 
		  <li>
			 <select class="form-control"  name="sdcon" id="sdcon" onchange="dateConCh_D(this);">
				<option value="">날짜검색조건</option>
				<option value="je" <?php if($search_val['sdcon'] == "je") {?> selected <?php } ?> >강제종료일</option>
			</select>

		  </li>
		  <li>
			<input type="text" class="form-control" name="sdate" id="sdate" placeholder="검색시작일" value="" autocomplete="off" disabled>
		  </li>
          <li>
			<input type="text" class="form-control" name="edate" id="edate" placeholder="검색종료일" value="" autocomplete="off" disabled>
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
		<form name="form_search_event_jend_hist" id="form_search_event_jend_hist" method="post" action="/tsaleschange/event_jend_hist">
			<div class="input-group input-group-sm mb-1">
				<span class="input-group-append">
					<span class="input-group-text" style='width:120px;margin-left:5px;'>회원명</span>
				</span>
				<input type="text" class="ss-input" name="snm" id="snm" style='width:100px' value="<?php echo $search_val['snm']?>" >
				
				<button type="button" class="btn btn-sm btn-success" style='margin-left:5px;' onclick="btn_search();">검색</button>
				
			</div>
			
			<div class="input-group input-group-sm mb-1">
			<select class=" " style="width: 220px;margin-left:5px;" name="sdcon" id="sdcon" onchange="dateConCh_D(this);">
				<option value="">날짜검색조건</option>
				<option value="je" <?php if($search_val['sdcon'] == "je") {?> selected <?php } ?> >강제종료일</option>
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
		<h3 class="panel-title">강제종료 내역 리스트</h3>
	</div>
		<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		<div id="search-results">
		<!-- TABLE [START] -->
		총 건수 : <?php echo $totalCount?> 건
		<table class="table table-bordered table-hover table-striped col-md-12 mt20">
			<thead>
				<tr class='text-center'>
					<th style='width:70px'>순번</th>
					<th style='width:150px'>회원명(ID)</th>
					<th style='width:130px'>구분</th>
					<th>판매상품명</th>
					
					<th style='width:140px'>강제종료일</th>
					<th style='width:140px'>강제종료ID</th>
					<th style='width:140px'>등록일시</th>
				</tr>
			</thead> 
			<tbody>
				<?php
				$listCount = $search_val['listCount'];
				foreach($hist_list as $r) :
					$backColor = "";
				?>
				<tr style="background-color: <?php echo $backColor ?>;">
					<td class='text-center'><?php echo $listCount?></td>
					<td>
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
						</a>(<?php echo $r['MEM_ID']?>)
					</td>
					<td class='text-center'>강제종료</td>
					<td><?php echo $r['SELL_EVENT_NM']?></td>
					
					<td><?php echo $r['CRE_DATETM']?></td>
					<td><?php echo $r['CRE_ID']?></td>
					<td><?php echo $r['CRE_DATETM']?></td>
				</tr>
				<?php 
				$listCount--;
				endforeach;
				?>
			</tbody>
		</table>
		</div>
		<?php echo $pager ?><br/>
	<!-- CARD BODY [END] -->
	<!-- CARD FOOTER [START] -->
	<!-- CARD FOOTER [END] -->
			
</div>
</div>


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
    
    if ( $('#sdcon').val() != '')
    {
    	$('#sdate').prop('disabled',false);
		$('#edate').prop('disabled',false);
    }
    
    // 날짜 조건 변경 시 자동 검색
    $('#sdcon').on('change', function() {
        if ($(this).val() !== '') {
            $('#sdate, #edate').prop('disabled', false);
        } else {
            $('#sdate, #edate').prop('disabled', true).val('');
        }
        btn_search();
    });
    
    // 날짜 변경 시 자동 검색
    $('#sdate, #edate').on('change', function() {
        if ($('#sdcon').val() !== '') {
            btn_search();
        }
    });
})

$('#sdate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$('#edate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});


// 회원명 입력 시 디바운스된 검색
let searchTimeout;
$('#snm').on('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        btn_search();
    }, 500);
});

// AJAX 검색 함수
function btn_search(page = 1) {
	
	
	var formData = $('#form_search_event_jend_hist').serialize();
    formData += '&page=' + page;
    
    $.ajax({
        url: '/tsaleschange/ajax_event_jend_hist_search',
        type: 'POST',
        data: formData,
        dataType: 'json',
        beforeSend: function() {
            // 로딩 표시
            $('#search-results').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> 검색중...</div>');
        },
        success: function(response) {
            if (response.result === 'true') {
                // 테이블 내용 업데이트
                $('#search-results').html(response.html);
                
                // 페이저 업데이트
                $('ul.pagination.pagination-sm.m-0.float-left').html($(response.pager).html());
                
                // 검색어가 있으면 하이라이트 적용
                var searchTerm = $('#snm').val().trim();
                if (searchTerm) {
                    $('#search-results table tbody tr').each(function() {
                        highlightInCell($(this), searchTerm);
                    });
                }
            } else {
                alertToast('error', response.msg || '검색 중 오류가 발생했습니다.');
            }
        },
        error: function() {
            alertToast('error', '통신 오류가 발생했습니다.');
            $('#search-results').html('<div class="text-center">검색 중 오류가 발생했습니다.</div>');
        }
    });
}

// 페이지네이션 클릭 처리
$(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    var page = 1;
    
    // URL에서 페이지 번호 추출
    var matches = url.match(/page=(\d+)/);
    if (matches) {
        page = matches[1];
    }
    
    btn_search(page);
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

function mem_manage_mem_info(mem_sno)
{
	location.href="/ttotalmain/info_mem/"+mem_sno;
}

// 하이라이트 함수 - 이미지 보존
function highlightInCell(cell, searchTerm) {
    if (!searchTerm || searchTerm === '') return;
    
    cell.contents().each(function() {
        if (this.nodeType === 3) { // Text node
            var text = $(this).text();
            if (text && text.toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
                var regex = new RegExp('(' + escapeRegExp(searchTerm) + ')', 'gi');
                var highlightedText = text.replace(regex, '<mark style="background-color: yellow;">$1</mark>');
                $(this).replaceWith(highlightedText);
            }
        } else if (this.nodeType === 1) { // Element node
            if (this.tagName.toLowerCase() !== 'img' && this.tagName.toLowerCase() !== 'mark') {
                highlightInCell($(this), searchTerm);
            }
        }
    });
}

function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

</script>