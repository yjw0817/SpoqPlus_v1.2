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
<!-- CARD HEADER [END] -->


<div class="panel panel-inverse">
        <div class="card-header py-3">
            <h5 class="m-0 text-white">강사 검색</h5>
        </div>
<form name="form_search_gx_list" id="form_search_gx_list" method="post"  action="/tsalesmain/gx_class_attd_list">
   <div class="bbs_search_box_a3  mb10 mt10" >
		<ul style="display: table !important; width: auto !important;">
		  <li style="display: table-cell !important; width: auto !important; padding-right: 30px; vertical-align: middle;">ㆍ 강사선택  </li>

		  <li style="display: table-cell !important; width: 250px !important; padding-right: 10px; vertical-align: middle;">
			 <select class="form-control" style="width: 240px !important;" name='gx_stchr_id' id='gx_stchr_id' onchange="loadGxClassListAndSearch()">
					<option value="">GX강사선택</option>
					<?php foreach($tchr_list as $t) : ?>
					<option value="<?php echo $t['MEM_ID']?>" <?php if($t['MEM_ID'] == $search_val['gx_stchr_id']) {?> selected <?php }?> >[<?php echo $t['TCHR_POSN_NM']?>] <?php echo $t['MEM_NM']?> - <?php echo $t['CTRCT_TYPE_NM']?></option>
					<?php endforeach;?>
				</select>
		  </li>
		  <li style="display: table-cell !important; width: 180px !important; padding-right: 10px; vertical-align: middle;">
			 <select class="form-control" style="width: 170px !important;" name='gx_clas_id' id='gx_clas_id' onchange="autoSearch()">
					<option value="">수업선택</option>
				</select>
		  </li>
		  <li style="display: table-cell !important; width: 90px !important; padding-right: 10px; vertical-align: middle;">
			 <select class="form-control" style="width: 90px !important;" name="syy" id="syy" onchange="loadGxClassListAndSearch()">
					<?php for($i=date('Y');$i >= 2020; $i--) :?>
					<option value="<?php echo $i?>" <?php if($search_val['syy'] == $i) {?> selected <?php } ?> ><?php echo $i?>년</option>
					<?php endfor;?>
				</select>
		  </li>
		  <li style="display: table-cell !important; width: 90px !important; padding-right: 10px; vertical-align: middle;">
			 <select class="form-control" style="width: 80px !important;" name="smm" id="smm" onchange="loadGxClassListAndSearch()">
					<option value="" <?php if($search_val['smm'] == '') {?> selected <?php } ?> >전체</option>
					<option value="01" <?php if($search_val['smm'] == '01') {?> selected <?php } ?> >1월</option>
					<option value="02" <?php if($search_val['smm'] == '02') {?> selected <?php } ?>>2월</option>
					<option value="03" <?php if($search_val['smm'] == '03') {?> selected <?php } ?>>3월</option>
					<option value="04" <?php if($search_val['smm'] == '04') {?> selected <?php } ?>>4월</option>
					<option value="05" <?php if($search_val['smm'] == '05') {?> selected <?php } ?>>5월</option>
					<option value="06" <?php if($search_val['smm'] == '06') {?> selected <?php } ?>>6월</option>
					<option value="07" <?php if($search_val['smm'] == '07') {?> selected <?php } ?>>7월</option>
					<option value="08" <?php if($search_val['smm'] == '08') {?> selected <?php } ?>>8월</option>
					<option value="09" <?php if($search_val['smm'] == '09') {?> selected <?php } ?>>9월</option>
					<option value="10" <?php if($search_val['smm'] == '10') {?> selected <?php } ?>>10월</option>
					<option value="11" <?php if($search_val['smm'] == '11') {?> selected <?php } ?>>11월</option>
					<option value="12" <?php if($search_val['smm'] == '12') {?> selected <?php } ?>>12월</option>
				</select>
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
		<form name="form_search_gx_list" id="form_search_gx_list" method="post" action="/tsalesmain/gx_class_attd_list">
			<div class="input-group input-group-sm mb-1">
				<select class='select2' style='width:350px;' name='gx_stchr_id' id='gx_stchr_id'>
					<option value="">GX강사선택</option>
					<?php foreach($tchr_list as $t) : ?>
					<option value="<?php echo $t['MEM_ID']?>" <?php if($t['MEM_ID'] == $search_val['gx_stchr_id']) {?> selected <?php }?> >[<?php echo $t['TCHR_POSN_NM']?>] <?php echo $t['MEM_NM']?> - <?php echo $t['CTRCT_TYPE_NM']?></option>
					<?php endforeach;?>
				</select>
			</div>
			
			<div class="input-group input-group-sm mb-1">
				
				<select class="" style="width: 150px;margin-left:5px;" name="syy" id="syy">
					<?php for($i=date('Y');$i >= 2020; $i--) :?>
					<option value="<?php echo $i?>" <?php if($search_val['syy'] == $i) {?> selected <?php } ?> ><?php echo $i?>년</option>
					<?php endfor;?>
				</select>
				
				<select class="" style="width: 150px;margin-left:5px;" name="smm" id="smm">
					<option value="" <?php if($search_val['smm'] == '') {?> selected <?php } ?> >전체</option>
					<option value="01" <?php if($search_val['smm'] == '01') {?> selected <?php } ?> >1월</option>
					<option value="02" <?php if($search_val['smm'] == '02') {?> selected <?php } ?>>2월</option>
					<option value="03" <?php if($search_val['smm'] == '03') {?> selected <?php } ?>>3월</option>
					<option value="04" <?php if($search_val['smm'] == '04') {?> selected <?php } ?>>4월</option>
					<option value="05" <?php if($search_val['smm'] == '05') {?> selected <?php } ?>>5월</option>
					<option value="06" <?php if($search_val['smm'] == '06') {?> selected <?php } ?>>6월</option>
					<option value="07" <?php if($search_val['smm'] == '07') {?> selected <?php } ?>>7월</option>
					<option value="08" <?php if($search_val['smm'] == '08') {?> selected <?php } ?>>8월</option>
					<option value="09" <?php if($search_val['smm'] == '09') {?> selected <?php } ?>>9월</option>
					<option value="10" <?php if($search_val['smm'] == '10') {?> selected <?php } ?>>10월</option>
					<option value="11" <?php if($search_val['smm'] == '11') {?> selected <?php } ?>>11월</option>
					<option value="12" <?php if($search_val['smm'] == '12') {?> selected <?php } ?>>12월</option>
				</select>
				<button type="button" class="btn btn-sm btn-success" style='margin-left:5px;' onclick="btn_search();">검색</button>
				
			</div>
		</form>
	</div>
</div> -->

<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">GX 수업 현황</h4>
	</div>
		<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
	<!-- CARD BODY [START] -->
	<div class="panel-body">
		<!-- TABLE [START] -->
		<!-- TABLE [START] -->
		<table class="table table-bordered table-hover table-striped col-md-12">
			<thead>
				<tr class='text-center'>
					
					<th style='width:70px'>순번</th>
					<th style='width:180px'>GX ROOM</th>
					<th>GX 수업명</th>
					<th style='width:120px'>GX 수업일자</th>
					<th style='width:150px'>GX 수업시작시각</th>
					<th style='width:150px'>GX 수업종료시각</th>
					<th style='width:180px'>GX 수업체크</th>
					<th style='width:180px'>GX 수업강사</th>
					<th>비고</th>
				</tr>
			</thead> 
			<tbody>
				<?php 
				foreach($gx_list as $r) :
					$backColor = "";
				?>
				<tr style="background-color: <?php echo $backColor ?>;">
					<td class='text-center'><?php echo $search_val['listCount']?></td>
					<td class='text-center'><?php echo $r['GX_ROOM_TITLE']?></td>
					<td><?php echo $r['GX_CLAS_TITLE']?></td>
					
					<td class='text-center'><?php echo $r['GX_CLAS_S_DATE']?></td>
					<td class='text-center'><?php echo $r['GX_CLAS_S_HH_II']?></td>
					<td class='text-center'><?php echo $r['GX_CLAS_E_HH_II']?></td>
					<td class='text-center'><?php echo $r['CLAS_CHK_YN']?></td>
					<td class='text-center'>
						<?php 
						// 강사 정보 찾기
						$tchr_info = null;
						foreach($tchr_list as $tchr) {
							if($tchr['MEM_ID'] == $r['GX_STCHR_ID']) {
								$tchr_info = $tchr;
								break;
							}
						}
						?>
						<?php if($tchr_info && (isset($tchr_info['MEM_THUMB_IMG']) || isset($tchr_info['MEM_GENDR']))) : ?>
							<img class="preview_mem_photo"
								id="preview_tchr_photo_<?php echo $r['GX_STCHR_SNO']?>"
								src="<?php echo isset($tchr_info['MEM_THUMB_IMG']) ? $tchr_info['MEM_THUMB_IMG'] : '' ?>"
								alt="강사사진"
								style="cursor: pointer;"
								onclick="showFullPhoto('<?php echo isset($tchr_info['MEM_MAIN_IMG']) ? $tchr_info['MEM_MAIN_IMG'] : '' ?>')"
								onerror="this.onerror=null; this.src='/dist/img/default_profile_<?php echo isset($tchr_info['MEM_GENDR']) ? $tchr_info['MEM_GENDR'] : 'M' ?>.png';">
						<?php endif; ?>
						<?php echo $r['GX_STCHR_NM']?>
					</td>
					<td></td>
				</tr>
				<?php 
				$search_val['listCount']--;
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
<br/>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
	
<!-- ############################## MODAL [ END ] ###################################### -->
	
	
	
</section>
			
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
	
<!-- ############################## MODAL [ END ] ###################################### -->
	
	
	


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

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
	$('#form_search_gx_list').submit();
}

// 자동 검색 함수
function autoSearch() {
	$('#form_search_gx_list').submit();
}

// 수업 리스트 로딩 후 자동 검색
function loadGxClassListAndSearch() {
	loadGxClassList(function() {
		// 수업 리스트 로딩 완료 후 자동 검색 실행
		setTimeout(function() {
			autoSearch();
		}, 100); // 약간의 딜레이를 주어 드롭다운 업데이트 완료 대기
	});
}

// 수업 리스트 동적 로딩
function loadGxClassList(callback) {
	var stchrId = $('#gx_stchr_id').val();
	var year = $('#syy').val();
	var month = $('#smm').val();
	var currentClassId = $('#gx_clas_id').val() || selectedGxClasId; // 현재 선택된 수업 ID 저장
	
	if (!year) {
		year = new Date().getFullYear();
	}
	
	// Ajax로 수업 리스트 가져오기
	$.ajax({
		url: '/tsalesmain/getGxClassList',
		type: 'POST',
		data: {
			gx_stchr_id: stchrId,
			syy: year,
			smm: month
		},
		dataType: 'json',
		success: function(response) {
			var classSelect = $('#gx_clas_id');
			classSelect.empty();
			classSelect.append('<option value="">수업선택</option>');
			
			var hasCurrentClass = false;
			
			if (response.success && response.data && response.data.length > 0) {
				$.each(response.data, function(index, item) {
					var option = $('<option></option>')
						.attr('value', item.gx_clas_id)
						.text(item.gx_clas_title);
					
					// 기존 선택된 수업이 새 리스트에 있는지 확인
					if (currentClassId && item.gx_clas_id == currentClassId) {
						option.attr('selected', 'selected');
						hasCurrentClass = true;
					}
					
					classSelect.append(option);
				});
			}
			
			// 기존 선택된 수업이 새 리스트에 없으면 "수업선택"으로 초기화
			if (!hasCurrentClass) {
				classSelect.val('');
			}
			
			// 콜백 함수 실행
			if (callback && typeof callback === 'function') {
				callback();
			}
		},
		error: function(xhr, status, error) {
			console.log('수업 리스트 로딩 오류:', error);
			var classSelect = $('#gx_clas_id');
			classSelect.empty();
			classSelect.append('<option value="">수업선택</option>');
			
			// 에러 발생 시에도 콜백 함수 실행 (검색은 계속 진행)
			if (callback && typeof callback === 'function') {
				callback();
			}
		}
	});
}

// 서버에서 전달된 선택된 수업 정보
var selectedGxClasId = '<?php echo $search_val['gx_clas_id'] ?? ''; ?>';

// 페이지 로드 시 초기 수업 리스트 로딩 (자동 검색 없음)
$(document).ready(function() {
	loadGxClassList();
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

</script>