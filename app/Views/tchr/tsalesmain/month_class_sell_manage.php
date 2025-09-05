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
<h1 class="page-header"><?php echo $title ?></h1>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://jqueryui.com/resources/demos/datepicker/i18n/datepicker-ko.js"></script>

<div class="panel panel-inverse">
        <div class="card-header py-3">
            <h5 class="m-0 text-white">회원 검색 </h5>
        </div>
<form name="form_search_month_class_sell_manage" id="form_search_month_class_sell_manage" method="post" onsubmit="return false;">

   <div class="bbs_search_box_a6 mb10 mt10">
		<ul>
		  <li>ㆍ 강사명 </li>
		  <li>
			<select class="form-control" name='ptchr_id' id='ptchr_id'>
				<option value="">판매강사선택</option>
				<?php foreach($tchr_list as $t) : ?>
				<option value="<?php echo $t['MEM_ID']?>" <?php if($t['MEM_ID'] == $search_val['ptchr_id']) {?> selected <?php }?> >[<?php echo $t['TCHR_POSN_NM']?>] <?php echo $t['MEM_NM']?> - <?php echo $t['CTRCT_TYPE_NM']?></option>
				<?php endforeach;?>
			</select>

		  </li>
		  <li>ㆍ 회원명 </li>
          <li>
			  <input type="text" class="form-control" name="snm" id="snm"  value="<?php echo $search_val['snm']?>" >
		  </li>
		 </ul>
	</div>	



   <div class="bbs_search_box_b4 mb10 mt10">
		<ul>
		  <li>ㆍ 판매상품명 </li>
		  <li>
			 <input type="text" class="form-control"  name="senm" id="senm" value="<?php echo $search_val['senm']?>" >
		  </li>
		  <li>
			 <select class="form-control"  name="spstat" id="spstat">
				<option value="">결제상태</option>
				<?php foreach ($sDef['PAYMT_STAT'] as $r => $v) : ?>
				<?php if($r != '') :?>
				<option value="<?php echo $r?>" <?php if($search_val['spstat'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>
		  </li>
		  <li>
			 <select class="form-control"   name="spchnl" id="spchnl">
				<option value="">결제채널</option>
				<?php foreach ($sDef['PAYMT_CHNL'] as $r => $v) : ?>
				<?php if($r != '') :?>
				<option value="<?php echo $r?>" <?php if($search_val['spchnl'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>
		  </li>
		  <li>
			 <select class="form-control" name="spmthd" id="spmthd">
				<option value="">결제수단</option>
				<?php foreach ($sDef['PAYMT_MTHD'] as $r => $v) : ?>
				<?php if($r != '') :?>
				<option value="<?php echo $r?>" <?php if($search_val["spmthd"] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>
		  </li>
		  <li>
			 <select class="form-control"  name="spdv" id="spdv">
				<option value="">매출구분</option>
				<?php foreach ($sDef['SALES_DV'] as $r => $v) : ?>
				<?php if($r != '') :?>
				<option value="<?php echo $r?>" <?php if($search_val["spdv"] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>
		  </li>
		  <li>
			 <select class="form-control" name="spdvr" id="spdvr">
				<option value="">사유</option>
				<?php foreach ($sDef['SALES_DV_RSON'] as $r => $v) : ?>
				<?php if($r != '') :?>
				<option value="<?php echo $r?>" <?php if($search_val["spdvr"] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>
		  </li>
	
          <!-- 검색 버튼 제거 - 자동 검색으로 대체 -->		  	  		  		  		  		  
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
			 <select class="form-control"  name="sdcon" id="sdcon" onchange="dateConCh(this);" >
				<option value="sd" <?php if($search_val['sdcon'] == "sd") {?> selected <?php } ?> selected >등록일시</option>
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

<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">매출내역 리스트</h4>
		<div class="panel-heading-btn">
		</div>
	</div>
	<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive ">
		<!-- TABLE [START] -->
		<div id="search-results">
			총 건수 : <span id="total-count"><?php echo $totalCount?></span> 건 / 총 결제금액 : <span id="total-amount"><?php echo number_format($sumCost)?></span> 원
						<table class="table table-bordered table-hover col-md-12 mt20">
							<thead>
								<tr class='text-center'>
									<th style='width:55px'>순번</th>
									<th style='width:150px'>회원명(ID)</th>
									<th style='width:130px'>구분</th>
									<th>판매상품명</th>
									
									<th style='width:100px'>상태</th>
									<th style='width:100px'>결제채널</th>
									<th style='width:100px'>수단</th>
									<th style='width:100px'>결제금액</th>
									<th style='width:100px'>매출구분</th>
									<th style='width:120px'>사유</th>
									<th style='width:100px'>회원상태</th>
									
									
									<th style='width:80px'>판매강사</th>
									<th style='width:140px'>등록일시</th>
								</tr>
							</thead> 
							<tbody>
								<?php 
								foreach($sales_list as $r) :
								    $backColor = "";
								    /*
								     MEM_NM 회원명
								     MEM_ID 회원아이디
								     
								     SELL_EVENT_NM 판매상품명
								     PAYMT_STAT 상태
								     PAYMT_MTHD 수단
								     PAYMT_AMT 결제금액
								     PAYMT_CHNL 결제채널
								     SALES_DV 구분
								     SALES_DV_RSON 구분사유
								     SALES_MEM_STAT 회원상태
								     PTHCR_ID 판매강사_아이디
								     CRE_DATETM 등록일시
								     */
								?>
								<tr style="background-color: <?php echo $backColor ?>;">
									<td class='text-center'><?php echo $search_val['listCount']?></td>
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
									<td class='text-center'> <?php 
										echo (is_null($r['CLAS_DV']) ) 
											? $r['CATE_NM'] 
											: $sDef['CLAS_DV'][$r['CLAS_DV']];
									?></td>
									<td><?php echo $r['SELL_EVENT_NM']?></td>
									
									<td class='text-center'><?php echo $sDef['PAYMT_STAT'][$r['PAYMT_STAT']]?></td>
									<td class='text-center'><?php echo $sDef['PAYMT_CHNL'][$r['PAYMT_CHNL']]?></td>
									<td class='text-center'><?php echo $sDef['PAYMT_MTHD'][$r['PAYMT_MTHD']]?></td>
									
									<?php if($r['PAYMT_AMT'] < 0) : ?>
									<td style="background-color:#f7d5d9; text-align:right;"><?php echo number_format($r['PAYMT_AMT'])?></td>
									<?php else : ?>
									<td style="text-align:right"><?php echo number_format($r['PAYMT_AMT'])?></td>
									<?php endif ; ?>
									
									<td><?php echo $sDef['SALES_DV'][$r['SALES_DV']]?></td>
									<td><?php echo $sDef['SALES_DV_RSON'][$r['SALES_DV_RSON']]?></td>
									<td><?php echo $sDef['SALES_MEM_STAT'][$r['SALES_MEM_STAT']]?></td>
									
									<td><?php echo $r['PTHCR_ID']?></td>
									
									<td><?php echo $r['CRE_DATETM']?></td>
								</tr>
								<?php 
								$search_val['listCount']--;
								endforeach;
								?>
								
							</tbody>
						</table>
		</div>
		<!-- TABLE [END] -->
		<?php echo $pager ?><br/>
	<!-- CARD BODY [END] -->
	<!-- CARD FOOTER [START] -->
	<!-- CARD FOOTER [END] -->
			
</div>
	<!-- CARD BODY [END] -->
			
</div>
			
<!-- ############################## MODAL [ SATRT ] #################################### -->
	
<!-- ############################## MODAL [ END ] ###################################### -->
	
	
	
</section>

<?=$jsinc ?>

<script>
$(function () {
	// 초기 페이저 이벤트 바인딩
	bindPagerEvents();
	
	// 자동 검색 이벤트 추가
	$('#ptchr_id, #spstat, #spchnl, #spmthd, #spdv, #spdvr, #sdUnit, #sdcon').on('change', function() {
		btn_search();
	});
	
	// 디바운스된 검색어 입력 이벤트
	let searchTimeout;
	$('#snm, #senm').on('input', function() {
		clearTimeout(searchTimeout);
		searchTimeout = setTimeout(function() {
			btn_search();
		}, 500);
	});
	
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
			btn_search();
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
    const snm = document.getElementById('snm');

    if (snm) {
        snm.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // 폼 자동 제출 방지 (선택 사항)
                btn_search();           // 원하는 함수 호출
            }
        });
    }
});

$(".ss-input").on("keyup",function(key){
	if(key.keyCode==13) {
		btn_search();
	}
});

// AJAX 요청 관리를 위한 변수
let currentSearchRequest = null;

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
	var formData = $('#form_search_month_class_sell_manage').serialize();
	
	// 로딩 표시
	$('#search-results').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> 검색중...</div>');
	
	currentSearchRequest = $.ajax({
		url: '/tsalesmain/ajax_month_class_sell_search',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(response) {
			if (response.result == 'true') {
				// 검색 결과 업데이트
				$('#search-results').html(response.html);
				$('#total-count').text(response.totalCount);
				$('#total-amount').text(response.totalAmount);
				
				// 페이저 업데이트
				$('#pager-container').html(response.pager);
				
				// 페이저 이벤트 재바인딩
				bindPagerEvents();
				
				// 검색어가 있으면 하이라이트 적용
				var searchTerms = [];
				if ($('#snm').val().trim()) searchTerms.push($('#snm').val().trim());
				if ($('#senm').val().trim()) searchTerms.push($('#senm').val().trim());
				
				if (searchTerms.length > 0) {
					searchTerms.forEach(function(term) {
						$('#search-results table tbody tr').each(function() {
							highlightInCell($(this), term);
						});
					});
				}
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

// 페이저 이벤트 바인딩
function bindPagerEvents() {
    $('#pager-container a').off('click').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        if (href && href !== '#') {
            var url = new URL(href, window.location.origin);
            var page = url.searchParams.get('page') || 1;
            
            // 검색 폼에 페이지 번호 추가
            $('#form_search_month_class_sell_manage').append('<input type="hidden" name="page" value="' + page + '">');
            btn_search();
            // 페이지 번호 제거
            $('#form_search_month_class_sell_manage input[name="page"]').remove();
        }
    });
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

// 날짜 포맷 함수
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
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
            const weekEnd = new Date(currentStart);
            weekEnd.setDate(currentStart.getDate() + 6);
            $sdate.val(formatDate(currentStart));
            $edate.val(formatDate(weekEnd));
            
            const year = currentStart.getFullYear();
            const month = currentStart.getMonth() + 1;
            const week = getWeekOfMonth(currentStart);
            $nthUnit.val(`${year}-${String(month).padStart(2, '0')} ${week}주차`);
        }
    } else if (unit === 'Month') {
        const currentDate = new Date($sdate.val());
        if (!isNaN(currentDate)) {
            const year = currentDate.getFullYear();
            let month = currentDate.getMonth() - 1;
            
            if (month < 0) {
                month = 11;
                currentDate.setFullYear(year - 1);
            }
            
            const firstDay = new Date(currentDate.getFullYear(), month, 1);
            const lastDay = new Date(currentDate.getFullYear(), month + 1, 0);
            
            $sdate.val(formatDate(firstDay));
            $edate.val(formatDate(lastDay));
            $nthUnit.val(`${currentDate.getFullYear()}-${String(month + 1).padStart(2, '0')}`);
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
            const weekEnd = new Date(currentStart);
            weekEnd.setDate(currentStart.getDate() + 6);
            $sdate.val(formatDate(currentStart));
            $edate.val(formatDate(weekEnd));
            
            const year = currentStart.getFullYear();
            const month = currentStart.getMonth() + 1;
            const week = getWeekOfMonth(currentStart);
            $nthUnit.val(`${year}-${String(month).padStart(2, '0')} ${week}주차`);
        }
    } else if (unit === 'Month') {
        const currentDate = new Date($sdate.val());
        if (!isNaN(currentDate)) {
            const year = currentDate.getFullYear();
            let month = currentDate.getMonth() + 1;
            
            if (month > 11) {
                month = 0;
                currentDate.setFullYear(year + 1);
            }
            
            const firstDay = new Date(currentDate.getFullYear(), month, 1);
            const lastDay = new Date(currentDate.getFullYear(), month + 1, 0);
            
            $sdate.val(formatDate(firstDay));
            $edate.val(formatDate(lastDay));
            $nthUnit.val(`${currentDate.getFullYear()}-${String(month + 1).padStart(2, '0')}`);
        }
    }
    
    btn_search();
}

// 월의 주차 계산
function getWeekOfMonth(date) {
    const firstDayOfMonth = new Date(date.getFullYear(), date.getMonth(), 1);
    const firstDayWeekday = firstDayOfMonth.getDay();
    const adjustedDate = date.getDate() + firstDayWeekday - 1;
    return Math.ceil(adjustedDate / 7);
}

</script>