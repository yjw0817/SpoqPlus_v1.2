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
?>
<h1 class="page-header"><?php echo $title ?></h1>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://jqueryui.com/resources/demos/datepicker/i18n/datepicker-ko.js"></script>
<!-- CARD HEADER [END] -->


<div class="panel panel-inverse">
        <div class="card-header py-3">
            <h5 class="m-0 text-white">강사 검색</h5>
        </div>
<form name="form_search_month_class_sales_manage" id="form_search_month_class_sales_manage" method="post" onsubmit="return false;">
   <input type="hidden" name="mem_sno" id="mem_sno" value="<?php echo isset($search_val['mem_sno']) ? $search_val['mem_sno'] : '' ?>">
   <div class="bbs_search_box_a6 mb10 mt10">
		<ul>
		  <li>ㆍ 강사명  </li>
		  <li>
			<input type="text" class="form-control"   name="stnm" id="stnm" value="<?php echo $search_val['stnm']?>" >

		  </li>
		   <li>ㆍ 회원명 </li>
		  <li>
			<input type="text" class="form-control"  name="snm" id="snm" value="<?php echo $search_val['snm']?>" >
		</li>
		 </ul>
	</div>	
	 <div class="bbs_search_box_a3 mb10 mt10">
		<ul>
		 <li>ㆍ 판매상품명  </li>
		  <li>
			<input type="text" class="form-control" name="senm" id="senm" value="<?php echo $search_val['senm']?>" >
		  </li>
          <li>
			 <select class="form-control"  name="scyn" id="scyn">
				<option value="">자동차감</option>
				<option value="Y" <?php if($search_val['scyn'] == "Y") {?> selected <?php } ?>>Y</option>
				<option value="N" <?php if($search_val['scyn'] == "N") {?> selected <?php } ?>>N</option>
			</select>
		  </li>
          <li>
			 <select class="form-control"  name="scdv" id="scdv">
				<option value="">정규/서비스</option>
				<?php foreach ($sDef['PT_CLAS_DV'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val['scdv'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
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
		 
		  <li><select class="form-control me-2"  name="sdUnit" id="sdUnit" onchange="dateTypeCh(this);">
				<option value="Day" <?php if($search_val['sdUnit'] == "Day") {?> selected <?php } ?> selected >일일</option>
				<option value="Week" <?php if($search_val['sdUnit'] == "Week") {?> selected <?php } ?> >주간</option>
				<option value="Month" <?php if($search_val['sdUnit'] == "Month") {?> selected <?php } ?> >월간</option>
			</select>
			 <select class="form-control" name="sdcon" id="sdcon" onchange="dateConCh(this);">
				<!-- <option value="">날짜검색조건</option> -->
				<option value="sc" <?php if($search_val['sdcon'] == "sc") {?> selected <?php } ?> >등록일시</option>
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
		<h4 class="panel-title">강사 검색</h4>
	</div>
	
	<div class="card-footer clearfix">
		<form name="form_search_month_class_sales_manage" id="form_search_month_class_sales_manage" method="post" action="/tsalesmain/month_class_sales_manage">
			<div class="input-group input-group-sm mb-1">
				<span class="input-group-append">
					<span class="input-group-text" style='width:120px;margin-left:5px;'>강사명</span>
				</span>
				<input type="text" class="ss-input" name="stnm" id="stnm" style='width:100px' value="<?php echo $search_val['stnm']?>" >
				
				<span class="input-group-append">
					<span class="input-group-text" style='width:120px;margin-left:5px;'>회원명</span>
				</span>
				<input type="text" class="ss-input" name="snm" id="snm" style='width:100px' value="<?php echo $search_val['snm']?>" >
				
				<span class="input-group-append">
					<span class="input-group-text" style='width:120px;margin-left:5px;'>판매상품명</span>
				</span>
				<input type="text" class="ss-input" name="senm" id="senm" style='width:100px' value="<?php echo $search_val['senm']?>" >
				
				<select class=" " style="width: 150px;margin-left:5px;" name="scyn" id="scyn">
				<option value="">자동차감</option>
				<option value="Y" <?php if($search_val['scyn'] == "Y") {?> selected <?php } ?>>Y</option>
				<option value="N" <?php if($search_val['scyn'] == "N") {?> selected <?php } ?>>N</option>
				</select>
				
				<select class=" " style="width: 150px;margin-left:5px;" name="scdv" id="scdv">
				<option value="">정규/서비스</option>
				<?php foreach ($sDef['PT_CLAS_DV'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val['scdv'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
				</select>
				
				<button type="button" class="btn btn-sm btn-success" style='margin-left:5px;' onclick="btn_search();">검색</button>
				
			</div>
			
			<div class="input-group input-group-sm mb-1">
			<select class=" " style="width: 220px;margin-left:5px;" name="sdcon" id="sdcon" onchange="dateConCh(this);">
				<option value="">날짜검색조건</option>
				<option value="sc" <?php if($search_val['sdcon'] == "sc") {?> selected <?php } ?> >수업일시</option>
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
		<h4 class="panel-title">수업내역 리스트</h4>
	</div>
		<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		<!-- TABLE [START] -->
		총 건수 : <?php echo $totalCount?> 건 / 총 수업금액 : <?php echo number_format($sumCost)?> 원 
		<table class="table table-bordered table-hover col-md-12 mt20">
			<thead>
				<tr class='text-center' >
					<th style='width:55px'>순번</th>
					<th style='width:130px'>수업강사</th>
					<th style='width:150px'>회원명(ID)</th>
					<th style='width:100px'>수업구분</th>
					<th>판매상품명</th>
					<th style='width:75px'>자동차감</th>
					<th style='width:75px'>정규<br/>서비스</th>
					<th style='width:75px'>정규<br/> 수업수</th>
					
					<th style='width:75px'>정규<br/>남은수</th>
					<th style='width:75px'>정규<br/>진행수</th>
					<th style='width:75px'>서비스<br/>남은수</th>
					<th style='width:75px'>서비스<br/>진행수</th>
					
					<th style='width:100px'>수업금액</th>
					<th style='width:100px'>수업요일</th>
					<th style='width:140px'>수업일시</th>
				</tr>
			</thead> 
			<tbody>
				<?php 
				foreach($sales_list as $r) :
					$backColor = "";
					if ($r['PT_CLAS_DV'] == "01") $backColor = "#cfecf0";
				?>
				<tr style="background-color: <?php echo $backColor ?>;">
					<td class='text-center'><?php echo $search_val['listCount']?></td>
					<td class='text-center'>
						<?php if(isset($r['TCHR_THUMB_IMG']) || isset($r['TCHR_GENDR'])) : ?>
							<img class="preview_mem_photo"
								id="preview_tchr_photo_<?php echo $r['TCHR_SNO']?>"
								src="<?php echo isset($r['TCHR_THUMB_IMG']) ? $r['TCHR_THUMB_IMG'] : '' ?>"
								alt="강사사진"
								style="cursor: pointer;"
								onclick="showFullPhoto('<?php echo isset($r['TCHR_MAIN_IMG']) ? $r['TCHR_MAIN_IMG'] : '' ?>')"
								onerror="this.onerror=null; this.src='/dist/img/default_profile_<?php echo isset($r['TCHR_GENDR']) ? $r['TCHR_GENDR'] : 'M' ?>.png';">
						<?php endif; ?>
						<?php echo $r['STCHR_NM']?>
					</td>
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
					<td class='text-center'><?php echo $sDef['CLAS_DV'][$r['CLAS_DV']]?></td>
					<td><?php echo $r['SELL_EVENT_NM']?></td>
					
					<td class='text-center'><?php echo $r['AUTO_CHK']?></td>
					<td class='text-center'><?php echo $sDef['PT_CLAS_DV'][$r['PT_CLAS_DV']]?></td>
					<td class='text-center'><?php echo $r['CLAS_CNT']?></td>
					
					<td class='text-center'><?php echo $r['MEM_REGUL_CLAS_LEFT_CNT']?></td>
					<td class='text-center'><?php echo $r['MEM_REGUL_CLAS_PRGS_CNT']?></td>
					<td class='text-center'><?php echo $r['SRVC_CLAS_LEFT_CNT']?></td>
					<td class='text-center'><?php echo $r['SRVC_CLAS_PRGS_CNT']?></td>
					
					<td style="text-align:right"><?php echo number_format($r['TCHR_1TM_CLAS_PRGS_AMT'])?></td>
					<td class='text-center'><?php echo $r['CLAS_CHK_DOTW']?></td>
					<td><?php echo $r['CRE_DATETM']?></td>
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
<br/>

</div>
<br/>
			
<!-- ############################## MODAL [ SATRT ] #################################### -->
	
<!-- ############################## MODAL [ END ] ###################################### -->
	
	
	
</section>


<?=$jsinc ?>

<script>
// $(function () {
//     $('.select2').select2();
    
//     if ( $('#sdcon').val() != '')
//     {
//     	$('#sdate').prop('disabled',false);
// 		$('#edate').prop('disabled',false);
//     }
// })

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
    // 페이지 로드 시 URL에서 mem_sno 제거 (첫 진입 후)
    <?php if (isset($search_val['mem_sno']) && $search_val['mem_sno']) : ?>
    // URL에서 mem_sno 파라미터 제거
    if (window.history && window.history.replaceState) {
        const cleanUrl = '/tsalesmain/month_class_sales_manage';
        window.history.replaceState({}, document.title, cleanUrl);
    }
    <?php endif; ?>
    
    // 타이핑 시 자동 검색 (디바운스 적용)
    $('#stnm, #snm, #senm').on('input', function() {
        // 검색어가 입력되면 mem_sno 제거
        if ($('#stnm').val() || $('#snm').val() || $('#senm').val()) {
            $('#mem_sno').val('');
        }
        
        // 검색어가 2자 이상이거나 비어있을 때 자동 검색
        if (this.value.length >= 2 || this.value.length === 0) {
            debouncedSearch();
        }
    });
    
    // 선택 박스 변경시 자동 검색
    $('#scyn, #scdv, #sdUnit, #sdcon').on('change', function() {
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
			
			// nthUnit 변경 후 자동 검색 실행
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
    const snmInput = document.getElementById('snm');

    if (snmInput) {
        snmInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // 폼 자동 제출 방지 (선택 사항)
                btn_search();           // 원하는 함수 호출
            }
        });
    }

	const stnmInput = document.getElementById('stnm');

    if (stnmInput) {
        stnmInput.addEventListener('keydown', function (event) {
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
	// 날짜 검색조건 유효성 검사
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
	var formData = $('#form_search_month_class_sales_manage').serialize();
	
	// 로딩 표시
	$('.panel-body').html('<div class="text-center" style="padding: 50px;"><i class="fa fa-spinner fa-spin fa-3x"></i><br>검색중...</div>');
	
	currentSearchRequest = $.ajax({
		url: '/tsalesmain/ajax_month_class_sales_search',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(response) {
			if (response.result == 'true') {
				// 테이블 내용 업데이트
				$('.panel-body').html(response.html);
				
				// 페이저 이벤트 재바인딩
				bindPagerEvents();
				
				// 검색어 하이라이트
				highlightSearchTerm();
			} else {
				alertToast('error', '검색 중 오류가 발생했습니다.');
				$('.panel-body').html('<div class="text-center" style="padding: 50px;">검색 중 오류가 발생했습니다.</div>');
			}
		},
		error: function(xhr) {
			// abort된 경우는 에러 메시지 표시하지 않음
			if (xhr.statusText !== 'abort') {
				alertToast('error', '검색 중 오류가 발생했습니다.');
				$('.panel-body').html('<div class="text-center" style="padding: 50px;">검색 중 오류가 발생했습니다.</div>');
			}
		},
		complete: function() {
			currentSearchRequest = null;
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
			$('#form_search_month_class_sales_manage').append('<input type="hidden" name="page" value="' + page + '">');
			btn_search();
			// 페이지 번호 제거
			$('#form_search_month_class_sales_manage input[name="page"]').remove();
		}
	});
}

// 검색어 하이라이트
function highlightSearchTerm() {
	var searchTerms = {
		stnm: $('#stnm').val().trim(),
		snm: $('#snm').val().trim(),
		senm: $('#senm').val().trim()
	};
	
	$('.panel-body table tbody tr').each(function() {
		var $row = $(this);
		
		// 강사명 하이라이트
		if (searchTerms.stnm.length >= 2) {
			var $tchrCell = $row.find('td:eq(1)');
			highlightInCell($tchrCell, searchTerms.stnm);
		}
		
		// 회원명 하이라이트
		if (searchTerms.snm.length >= 2) {
			var $memCell = $row.find('td:eq(2)');
			highlightInCell($memCell, searchTerms.snm);
		}
		
		// 상품명 하이라이트
		if (searchTerms.senm.length >= 2) {
			var $prodCell = $row.find('td:eq(4)');
			highlightInCell($prodCell, searchTerms.senm);
		}
	});
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

// 날짜 포맷팅 함수
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// 주차 계산 함수
function getWeekOfMonth(date) {
    const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    const firstDayOfWeek = firstDay.getDay();
    const dayOfMonth = date.getDate();
    
    // 첫 주의 시작일을 계산 (월요일 기준)
    const firstMondayDate = 1 + (7 - firstDayOfWeek + 1) % 7;
    
    if (dayOfMonth < firstMondayDate) {
        return 1;
    }
    
    return Math.ceil((dayOfMonth - firstMondayDate + 1) / 7) + 1;
}

// 날짜 타입 변경 처리
function dateTypeCh(obj) {
    const unit = $(obj).val();
    const $sdate = $("#sdate");
    const $edate = $("#edate");
    const $nthUnit = $("#nthUnit");
    
    if (unit === 'Day') {
        $sdate.attr('type', 'text').prop('readonly', false);
        $edate.css('display', 'none');
        $nthUnit.css('display', 'none');
        $sdate.val(formatDate(new Date()));
    } else if (unit === 'Week') {
        $sdate.attr('type', 'text').prop('readonly', true);
        $edate.css('display', 'block');
        $nthUnit.css('display', 'block').css('width', '120px');
        
        // 이번 주 설정
        const today = new Date();
        const startOfWeek = new Date(today);
        startOfWeek.setDate(today.getDate() - (today.getDay() === 0 ? 6 : today.getDay() - 1));
        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6);
        
        $sdate.val(formatDate(startOfWeek));
        $edate.val(formatDate(endOfWeek));
        $nthUnit.val(formatDate(today).substring(0, 7) + ' ' + getWeekOfMonth(today) + '주차');
    } else if (unit === 'Month') {
        $sdate.attr('type', 'hidden').prop('readonly', true);
        $edate.css('display', 'none');
        $nthUnit.css('display', 'block').css('width', '100px');
        
        // 이번 달 설정
        const today = new Date();
        const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        
        $sdate.val(formatDate(startOfMonth));
        $edate.val(formatDate(endOfMonth));
        $nthUnit.val(formatDate(today).substring(0, 7));
    }
    
    btn_search();
}

// 이전 날짜 버튼
function prevBtnClick() {
    const unit = $("#sdUnit").val();
    const $sdate = $("#sdate");
    const $edate = $("#edate");
    const $nthUnit = $("#nthUnit");
    
    if (unit === 'Day') {
        const currentDate = new Date($sdate.val());
        currentDate.setDate(currentDate.getDate() - 1);
        $sdate.val(formatDate(currentDate));
    } else if (unit === 'Week') {
        const currentStart = new Date($sdate.val());
        currentStart.setDate(currentStart.getDate() - 7);
        const currentEnd = new Date(currentStart);
        currentEnd.setDate(currentStart.getDate() + 6);
        
        $sdate.val(formatDate(currentStart));
        $edate.val(formatDate(currentEnd));
        $nthUnit.val(formatDate(currentStart).substring(0, 7) + ' ' + getWeekOfMonth(currentStart) + '주차');
    } else if (unit === 'Month') {
        const currentDate = new Date($sdate.val());
        currentDate.setMonth(currentDate.getMonth() - 1);
        const startOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const endOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
        
        $sdate.val(formatDate(startOfMonth));
        $edate.val(formatDate(endOfMonth));
        $nthUnit.val(formatDate(currentDate).substring(0, 7));
    }
    
    btn_search();
}

// 다음 날짜 버튼
function nextBtnClick() {
    const unit = $("#sdUnit").val();
    const $sdate = $("#sdate");
    const $edate = $("#edate");
    const $nthUnit = $("#nthUnit");
    
    if (unit === 'Day') {
        const currentDate = new Date($sdate.val());
        currentDate.setDate(currentDate.getDate() + 1);
        $sdate.val(formatDate(currentDate));
    } else if (unit === 'Week') {
        const currentStart = new Date($sdate.val());
        currentStart.setDate(currentStart.getDate() + 7);
        const currentEnd = new Date(currentStart);
        currentEnd.setDate(currentStart.getDate() + 6);
        
        $sdate.val(formatDate(currentStart));
        $edate.val(formatDate(currentEnd));
        $nthUnit.val(formatDate(currentStart).substring(0, 7) + ' ' + getWeekOfMonth(currentStart) + '주차');
    } else if (unit === 'Month') {
        const currentDate = new Date($sdate.val());
        currentDate.setMonth(currentDate.getMonth() + 1);
        const startOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const endOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
        
        $sdate.val(formatDate(startOfMonth));
        $edate.val(formatDate(endOfMonth));
        $nthUnit.val(formatDate(currentDate).substring(0, 7));
    }
    
    btn_search();
}

// 회원별 통합정보 보기
function mem_manage_mem_info(mem_sno)
{
	location.href="/ttotalmain/info_mem/"+mem_sno;
}

function showFullPhoto(imageSrc) {
    if (!imageSrc || imageSrc === '') {
        alertToast('info', '원본 사진이 없습니다.');
        return;
    }
    
    // 모달에서 이미지 보여주기
    Swal.fire({
        imageUrl: imageSrc,
        imageAlt: '사진',
        showConfirmButton: false,
        showCloseButton: true,
        width: 'auto'
    });
}

</script>