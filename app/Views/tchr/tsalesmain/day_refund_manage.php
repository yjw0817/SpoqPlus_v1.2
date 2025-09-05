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
            <h5 class="m-0 text-white">강사검색 </h5>
        </div>
<form name="form_search_day_refund_manage" id="form_search_day_refund_manage" method="post" onsubmit="return false;">

   <div class="bbs_search_box_a6 mb10 mt10">
		<ul>
		  <li>ㆍ 대분류 </li>
		  <li>
			 <select class="form-control"  name="1rd_cate_cd" id="1rd_cate_cd" onChange="cate1_ch();">
					<option value="">대분류 선택</option>
					<?php foreach($cate1 as $c1) :?>
					<option value="<?php echo $c1['1RD_CATE_CD']?>" <?php if($search_val['1rd_cate_cd'] == $c1['1RD_CATE_CD']) {?> selected <?php }?> ><?php echo $c1['CATE_NM']?></option>
					<?php endforeach;?>
			</select>

		  </li>
		  <li>ㆍ 중분류 </li>
          <li>
			 <select class="form-control"  name="2rd_cate_cd" id="2rd_cate_cd">
					<option value="">중분류 선택</option>
					<?php foreach($cate2 as $c2) :?>
					<option value="<?php echo $c2['2RD_CATE_CD']?>" <?php if($search_val['2rd_cate_cd'] == $c2['2RD_CATE_CD']) {?> selected <?php }?> ><?php echo $c2['CATE_NM']?></option>
					<?php endforeach;?>
			</select>
		  </li>
		 </ul>
	</div>	

   <div class="bbs_search_box_a mb10 mt10">
		<ul>
		  <li>ㆍ 회원명 </li>
		  <li>
			 <input type="text" class="form-control" name="snm" id="snm" value="<?php echo $search_val['snm']?>" >
		  </li>	  
		 </ul>
	</div>	


   <div class="bbs_search_box_b4 mb10 mt10">
		<ul>
		  <li>ㆍ 판매상품명 </li>
		  <li>
			 <input type="text" class="form-control" name="senm" id="senm" value="<?php echo $search_val['senm']?>" >
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
			 <select class="form-control"  name="spchnl" id="spchnl">
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
			 <select class="form-control" name="spdv" id="spdv">
				<option value="">구분</option>
				<?php foreach ($sDef['SALES_DV'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val["spdv"] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
				</select>

		  </li>
		  <li>
			 <select class="form-control"  name="spdvr" id="spdvr">
				<option value="">사유</option>
				<?php foreach ($sDef['SALES_DV_RSON'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val["spdvr"] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
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
			<select class="form-control me-2"  name="sdUnit" id="sdUnit" onchange="dateTypeCh(this);">
				<option value="Day" <?php if($search_val['sdUnit'] == "Day") {?> selected <?php } ?> selected >일일</option>
				<option value="Week" <?php if($search_val['sdUnit'] == "Week") {?> selected <?php } ?> >주간</option>
				<option value="Month" <?php if($search_val['sdUnit'] == "Month") {?> selected <?php } ?> >월간</option>
			</select>
			 <select class="form-control"  name="sdcon" id="sdcon" onchange="dateConCh(this);">
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




	
<!-- CARD BODY [START] -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">매출내역 리스트</h4>
		<div class="panel-heading-btn">
		</div>
	</div>
		<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
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
					<th style='width:100px'>수단</th>
					<th style='width:100px'>결제채널</th>
					<th style='width:100px'>결제금액</th>
					<th style='width:100px'>구분</th>
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
	
	
	
</section>


<?=$jsinc ?>

<script>
$(function () {
	// 초기 페이저 이벤트 바인딩
	bindPagerEvents();
	
	// 자동 검색 이벤트 추가
	$('#1rd_cate_cd, #2rd_cate_cd, #spstat, #spchnl, #spmthd, #spdv, #spdvr, #sdUnit, #sdcon').on('change', function() {
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
	}).on('changeDate', function (e) {
		btn_search();
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


$('#edate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$(".ss-input").on("keyup",function(key){
	if(key.keyCode==13) {
		btn_search();
	}
});

// 대분류 선택시 -> 중분류 동적 생성
function cate1_ch()
{
	var cate1 = $('#1rd_cate_cd').val();
	var params = "cate1="+cate1;
	jQuery.ajax({
        url: '/teventmain/ajax_cate2_list',
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
				$('#2rd_cate_cd').empty();
				$('#2rd_cate_cd').append("<option value=''>중분류 선택</option>");
				json_result['cate2'].forEach(function (r,index) {
					$('#2rd_cate_cd').append("<option data-clasdv='" + r['CLAS_DV'] + "' value='" + r['2RD_CATE_CD'] + "'>"+r['CATE_NM']+"</option>");
				});
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
	var formData = $('#form_search_day_refund_manage').serialize();
	
	// 로딩 표시
	$('#search-results').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> 검색중...</div>');
	
	currentSearchRequest = $.ajax({
		url: '/tsalesmain/ajax_day_refund_search',
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

// 페이저 이벤트 바인딩
function bindPagerEvents() {
    $('#pager-container a').off('click').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        if (href && href !== '#') {
            var url = new URL(href, window.location.origin);
            var page = url.searchParams.get('page') || 1;
            
            // 검색 폼에 페이지 번호 추가
            $('#form_search_day_refund_manage').append('<input type="hidden" name="page" value="' + page + '">');
            btn_search();
            // 페이지 번호 제거
            $('#form_search_day_refund_manage input[name="page"]').remove();
        }
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