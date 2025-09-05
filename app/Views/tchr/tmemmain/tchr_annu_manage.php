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
<!-- Main content -->
<h1 class="page-header"><?php echo $title ?></h1>



<div class="panel panel-inverse">
        <div class="card-header py-3">
            <h5 class="m-0 text-white">강사 검색 </h5>
        </div>
<form name="form_search_tchr_annu_manage" id="form_search_tchr_annu_manage" method="post" onsubmit="return false;">
   <div class="bbs_search_box_a4 mb10 mt10">
		<ul>
		  <li>ㆍ 신청자  </li>
		  <li>
			<input type="text" class="form-control ss-input"  name="snm" id="snm" value="<?php echo $search_val['snm']?>" placeholder="신청자명 검색" style="width: auto;">
		  </li>
		  <li>
			 <select class="form-control" name="sappstat" id="sappstat">
				<option value="">연차상태</option>
				<?php foreach ($sDef['ANNU_APPV_STAT'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val['sappstat'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
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
			 <select class="form-control" name="sdcon" id="sdcon" onchange="dateConCh(this);">
					<option value="">날짜검색조건</option>
					<option value="as" <?php if($search_val['sdcon'] == "as") {?> selected <?php } ?> >연차시작일</option>
					<option value="ae" <?php if($search_val['sdcon'] == "ae") {?> selected <?php } ?> >연차종료일</option>
					
					<option value="00" <?php if($search_val['sdcon'] == "00") {?> selected <?php } ?> >신청일시</option>
					<option value="01" <?php if($search_val['sdcon'] == "01") {?> selected <?php } ?> >승인일시</option>
					<option value="90" <?php if($search_val['sdcon'] == "90") {?> selected <?php } ?> >반려일시</option>
					<option value="99" <?php if($search_val['sdcon'] == "99") {?> selected <?php } ?> >취소일시</option>
			</select>

		  </li>
		  <li>
			<input type="text" class="form-control" name="sdate" id="sdate" placeholder="검색시작일" value="<?php echo $search_val['sdate']?>" autocomplete='off' disabled>
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
		<h4 class="panel-title">연차 내역</h4>
		<div class="panel-heading-btn">
		</div>
	</div>
		<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		<div id="total-count">총 건수 : <?php echo $totalCount?> 건</div>
		<table class="table table-bordered table-hover table-striped col-md-12 mt20">
			<thead>
				<tr class='text-center '>
					<th style='width:70px'>번호</th>
					<th style='width:110px'>신청자</th>
					<th style='width:70px'>상태</th>
					<th style='width:90px'>연차 시작일</th>
					<th style='width:90px'>연차 종료일</th>
					<th style='width:70px'>사용일</th>
					
					<th style='width:150px'>신청일시</th>
					<th style='width:150px'>승인일시</th>
					<th style='width:150px'>반려일시</th>
					<th style='width:150px'>취소일시</th>
					<th >옵션</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($tchr_annu_appct_list as $r) :?>
				<tr>
					<td class='text-center'><?php echo $search_val['listCount']?></td>
					<td>
						<?php if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) : ?>
							<img class="preview_mem_photo"
								id="preview_mem_photo_<?php echo $r['MEM_SNO']?>"
								src="<?php echo isset($r['MEM_THUMB_IMG']) ? $r['MEM_THUMB_IMG'] : '' ?>"
								alt="신청자사진"
								style="cursor: pointer;"
								onclick="showFullPhoto('<?php echo isset($r['MEM_MAIN_IMG']) ? $r['MEM_MAIN_IMG'] : '' ?>')"
								onerror="this.onerror=null; this.src='/dist/img/default_profile_<?php echo isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M' ?>.png';">
						<?php endif; ?>
						<?php echo $r['MEM_NM']?>
					</td>
					<td><?php echo $sDef['ANNU_APPV_STAT'][$r['ANNU_APPV_STAT']]?></td>
					<td><?php echo $r['ANNU_APPCT_S_DATE']?></td>
					<td><?php echo $r['ANNU_APPCT_E_DATE']?></td>
					<td><?php echo $r['ANNU_USE_DAY']?></td>
					
					<td><?php echo $r['ANNU_APPV_DATETM']?></td>
					<td><?php echo $r['ANNU_APPCT_DATETM']?></td>
					<td><?php echo $r['ANNU_REFUS_DATETM']?></td>
					<td><?php echo $r['ANNU_CANCEL_DATETM']?></td>
					<td style="text-align:center">
						<?php if($r['ANNU_APPV_STAT'] == "00") :?>
						<button type="button" class="btn btn-success btn-xs" onclick="annu_appct('<?php echo $r['ANNU_USE_MGMT_HIST_SNO']?>','<?php echo $r['ANNU_GRANT_MGMT_SNO']?>','<?php echo $r['ANNU_USE_DAY']?>');">승인하기</button>
						<button type="button" class="btn btn-warning btn-xs" onclick="annu_refus('<?php echo $r['ANNU_USE_MGMT_HIST_SNO']?>');">반려하기</button>
						<?php endif;?>
					</td>
				<?php
				$search_val['listCount']--;
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
// 현재 검색 요청을 저장할 변수
var currentSearchRequest;

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
    
    // 검색 조건 변경시 자동 검색
    $('#sappstat, #sdcon').on('change', function() {
        btn_search();
    });
    
    // 날짜 입력시 자동 검색 (디바운스 적용)
    $('#sdate, #edate').on('change', function() {
        if ($('#sdcon').val() !== '') {
            debouncedSearch();
        }
    });
    
    // 초기 페이지 로드 시 페이저 이벤트 바인딩
    bindPagerEvents();
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

$('#sdate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$('#edate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});



function btn_search()
{
	// 날짜가 입력되었을 때만 검색조건이 필요
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
	var formData = $('#form_search_tchr_annu_manage').serialize();
	
	// 로딩 표시
	$('.panel-body table tbody').html('<tr><td colspan="11" class="text-center"><i class="fa fa-spinner fa-spin"></i> 검색중...</td></tr>');
	
	currentSearchRequest = $.ajax({
		url: '/tmemmain/ajax_tchr_annu_search',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(response) {
			if (response.result == 'true') {
				// 테이블 내용 업데이트
				$('.panel-body table tbody').html(response.html);
				
				// 전체 건수 업데이트
				$('#total-count').html('총 건수 : ' + response.totalCount + ' 건');
				
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
				$('.panel-body table tbody').html('<tr><td colspan="11" class="text-center">검색 중 오류가 발생했습니다.</td></tr>');
			}
		}
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
			$('#form_search_tchr_annu_manage').append('<input type="hidden" name="page" value="' + page + '">');
			btn_search();
			// 페이지 번호 제거
			$('#form_search_tchr_annu_manage input[name="page"]').remove();
		}
	});
}

// 검색어 하이라이트
function highlightSearchTerm() {
	var searchTerm = $('#snm').val().trim();
	if (searchTerm.length >= 2) {
		// 신청자명에서 검색어 하이라이트
		$('.panel-body table tbody tr').each(function() {
			var $nameCell = $(this).find('td:eq(1)'); // 신청자 컬럼
			var originalText = $nameCell.text();
			var highlightedText = originalText.replace(new RegExp('(' + searchTerm + ')', 'gi'), '<mark>$1</mark>');
			if (originalText !== highlightedText) {
				// a 태그가 있는 경우를 고려하여 처리
				var $link = $nameCell.find('a');
				if ($link.length > 0) {
					var linkText = $link.text();
					var highlightedLinkText = linkText.replace(new RegExp('(' + searchTerm + ')', 'gi'), '<mark>$1</mark>');
					$link.html(highlightedLinkText);
				} else {
					$nameCell.html(highlightedText);
				}
			}
		});
	}
}

// 날짜 검색 조건 변경시 처리
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
	
	// 검색 조건이 변경되면 자동 검색
	btn_search();
}

function annu_appct(hist_sno,mgmt_sno,use_day)
{
	var params = "hist_sno="+hist_sno+"&mgmt_sno="+mgmt_sno+"&use_day="+use_day;
    jQuery.ajax({
        url: '/tmemmain/ajax_tchr_annu_appct_proc',
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
				btn_search(); // 페이지 리로드 대신 AJAX 검색으로 데이터 갱신
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

function annu_refus(hist_sno)
{
	var params = "hist_sno="+hist_sno;
    jQuery.ajax({
        url: '/tmemmain/ajax_tchr_annu_refus_proc',
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
				btn_search(); // 페이지 리로드 대신 AJAX 검색으로 데이터 갱신
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

function showFullPhoto(imageSrc) {
    if (!imageSrc || imageSrc === '') {
        alertToast('info', '원본 사진이 없습니다.');
        return;
    }
    
    // 모달에서 이미지 보여주기
    Swal.fire({
        imageUrl: imageSrc,
        imageAlt: '신청자 사진',
        showConfirmButton: false,
        showCloseButton: true,
        width: 'auto'
    });
}

</script>