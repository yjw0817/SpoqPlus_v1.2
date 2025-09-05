<style>
/* 달력이 헤더에 가려지지 않도록 z-index 조정 */
.datepicker {
    z-index: 9999 !important;
}
.datepicker-dropdown {
    z-index: 9999 !important;
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
<!-- CARD HEADER [END] -->

<div class="panel panel-inverse">
        <div class="card-header py-3">
            <h5 class="m-0 text-white">검색 조건 </h5>
        </div>

<div class="clearfix">
	
		<div class="row" id='help_con' style='display:none;'>
			<div class="col_md-12">
				<div class="alert-info2" >
				  <button type="button" class="close2" onclick="$('#help_con').hide();"><i class="fas fa-times" style="font-size:20px;"></i></button>
					<small><strong>출입제한조건 및 대분류 또는 중분류에 해당하는 회원중 운동시작일이 검색일(시작) 이후인 상품의 회원을 검색합니다.</strong></small><br />
					<small>1. 검색조건 : 운동시작일 이후로, 출입제한 조건과 대분류 또는 중분류를 선택합니다.</small><br />
					<small>2. 1번 조건에서 구매안한 대분류 또는 중분류를 선택하여 검색합니다.</small><br />
					<small>3. 검색된 상품에서 상품 보내기할 회원을 선택한 해주세요. </small><br />
					<small>4. 선택이 완료되면 상품 보내기 버튼을 클릭하여 검색된 상품을 선택하여 상품을 보낼 수 있습니다.</small>
				</div>
			</div>
		</div>


<form name="form_buy_event_manage1" id="form_buy_event_manage1" method="post" action="/teventmain/buy_event_manage4" onsubmit="return false;">

   <div class="bbs_search_box_a3 mb10 mt10">
		<ul>
		  <li>ㆍ 종료일 </li>
		  <li>
			<input type="number" class="form-control" style="text-align:right;" name="end_days" id="end_days" value="<?php echo $search_val['end_days']?>" min="0" max="999">
		  </li>
		  <li>일전</li>
		  		
		 </ul>
	</div>	
	 <div class="bbs_search_box_a4 mb10 mt10">
		<ul>
			<li>ㆍ 검색 분류 </li>
			<li>
				<select class="form-control"  name="search_cate1" id="search_cate1">
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
			<!-- 검색 버튼 제거 - 자동 검색으로 대체 -->		 		  	  		         
		</ul>
	</div>	
	 <div class="bbs_search_box_a8 mb10 mt10">
		<ul>
           <li>ㆍ 상품 </li>
          <li>
			<a href="#" onclick="send_buy_sno();" class="basic_bt04" type="button"><i class="fas fa-gift"></i> 상품보내기</a>
		  </li>
          <li>
			보내기 선택한 회원 수 : <span id='select_mem_sno_count'><?php echo count($send_mem_sno)?></span> 명
		  </li>		  		  
		 </ul>
	 </div>	
	</form>
	</div>
</div>


	
<!-- CARD BODY [START] -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">보내기상품 리스트(구매안한 상품검색)</h4>
		<div class="panel-heading-btn">
		</div>
	</div>
		<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		<div id="search-results">
		<!-- TABLE [START] -->
		총 건수 : <?php echo $totalCount?> 건

		<table class="table table-bordered table-hover  col-md-12 mt20">

			<thead>
				<tr class='text-center'>
					<th style='width:45px'>선택</th>
					<th style='width:75px'>회원명</th>
					<th style='width:100px'>전화번호</th>
					<th style='width:115px'>구매일시</th>
					<th style='width:80px'>종료일</th>
					<th style='width:80px'>판매상태</th>
					<th>판매상품명</th>
					
					<th style='width:60px'>기간</th>
					<th style='width:80px'>시작일</th>
					<th style='width:115px'>종료일</th>
					<th style='width:70px'>수업</th>
					<th style='width:70px'>휴회일</th>
					<th style='width:70px'>휴회수</th>
					<th style='width:100px'>판매금액</th>
					<th style='width:100px'>결제금액</th>
					<th style='width:100px'>미수금액</th>
					
					<th style='width:100px'>수업강사</th>
					<th style='width:100px'>판매강사</th>
					
				</tr>
			</thead> 
			<tbody>
				<?php 
				foreach($buy_event_list as $r) :
					$mem_mem_chk = "";
					if (in_array($r['MEM_SNO'], $send_mem_sno)) $mem_mem_chk = "checked";
					
				?>
				<tr style="background-color: #f7d5d9;">
					<td class='text-center'>
						<div class="icheck-primary d-inline">
							<input type="checkbox" name="send_chk[]" id="send_chk_<?php echo $r['BUY_EVENT_SNO']?>" value="<?php echo $r['MEM_SNO']?>" onclick="p_chk(this);" <?php echo $mem_mem_chk?>>
							<label for="send_chk_<?php echo $r['BUY_EVENT_SNO']?>">
								<small></small>
							</label>
						</div>
					</td>
				
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
					<td class='text-center'><?php echo substr($r['BUY_DATETM'],0,16)?></td>
					<td class='text-center'><?php echo $r['END_DAYS']?> 일전</td>
					<td class="text-center"><?php echo $sDef['EVENT_STAT_RSON'][$r['EVENT_STAT_RSON']]?></td>
					<td>
						<!-- <i class="fa fa-list" onclick="more_info_show();"></i> --> 
						<?php echo $r['SELL_EVENT_NM']?>
						<?php if($r['LOCKR_SET'] == "Y") : 
								if ($r['LOCKR_NO'] != '') :
									echo disp_locker_word($r['LOCKR_KND'],$r['LOCKR_GENDR_SET'],$r['LOCKR_NO']);
								else :
									echo "[미배정] ";
								endif ;
								endif;
						?>
					</td>
					
					<td class='text-center'><?php echo disp_produnit($r['USE_PROD'],$r['USE_UNIT'])?></td>
					<td class='text-center' style="width:80px"><span id="<?php echo "exr_s_date_".$r['BUY_EVENT_SNO']?>"><?php echo $r['EXR_S_DATE']?></span></td>
					<td class='text-center' style="width:80px"><span id="<?php echo "exr_e_date_".$r['BUY_EVENT_SNO']?>"><?php echo $r['EXR_E_DATE']?></span><?php echo disp_add_cnt($r['ADD_SRVC_EXR_DAY'])?></td>
					
					<!-- ############### 수업 영역 ################# -->
					<?php if($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") :?>
					<?php
						$sum_clas_cnt = $r['MEM_REGUL_CLAS_LEFT_CNT'] + $r['SRVC_CLAS_LEFT_CNT']; // 총 수업 남은 횟수
					?>
					<td class='text-center'><?php echo $sum_clas_cnt?></td>
					
					<?php else :?>
					<td class='text-center'>-</td>
					<?php endif ;?>
					<!-- ############### 수업 영역 ################# -->
					
					<!-- ############### 휴회 영역 ################# -->
					<?php if($r['DOMCY_POSS_EVENT_YN'] == "Y") :?>
					<td class='text-center'><?php echo $r['LEFT_DOMCY_POSS_DAY'] ?></td>
					<td class='text-center'><?php echo $r['LEFT_DOMCY_POSS_CNT'] ?></td>
					<?php else :?>
					<td class='text-center'>-</td>
					<td class='text-center'>-</td>
					<?php endif ;?>
					<!-- ############### 휴회 영역 ################# -->
					
					<td style="text-align:right"><?php echo number_format($r['REAL_SELL_AMT']) ?></td>
					<td style="text-align:right"><?php echo number_format($r['BUY_AMT']) ?></td>
					<td style="text-align:right"><?php echo number_format($r['RECVB_AMT']) ?></td>
					
					<td class='text-center'><?php echo $r['STCHR_NM'] ?></td>
					<td class='text-center'><?php echo $r['PTCHR_NM'] ?></td>
				</tr>
				<?php 
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
    $('.select2').select2();
    
    // 종료일 변경 시 자동 검색
    $('#end_days').on('change input', function() {
        performSearch();
    });
})

// 회원별 통합정보 보기
function mem_manage_mem_info(mem_sno)
{
	location.href="/ttotalmain/info_mem/"+mem_sno;
}

// AJAX 검색 함수
function performSearch(page = 1) {
    var formData = $('#form_buy_event_manage1').serialize();
    formData += '&page=' + page;
    
    $.ajax({
        url: '/teventmain/ajax_buy_event_manage4_search',
        type: 'POST',
        data: formData,
        dataType: 'json',
        beforeSend: function() {
            // 로딩 표시
            $('.panel-body').addClass('loading');
        },
        success: function(response) {
            if (response.result === 'true') {
                // 테이블 내용 업데이트
                $('.panel-body').html(response.html);
                
                // 체크박스 상태 복원
                if (response.checked_items) {
                    response.checked_items.forEach(function(mem_sno) {
                        $('input[value="' + mem_sno + '"]').prop('checked', true);
                    });
                }
                
                // 선택한 회원 수 업데이트
                $('#select_mem_sno_count').text(response.selected_count);
            } else {
                alertToast('error', response.msg || '검색 중 오류가 발생했습니다.');
            }
        },
        error: function() {
            alertToast('error', '통신 오류가 발생했습니다.');
        },
        complete: function() {
            $('.panel-body').removeClass('loading');
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
    
    performSearch(page);
});

$('#search_cate1').change(function(){
	var selectedCate1 = $(this).val();
	
	// search_cate2 초기화
	$('#search_cate2').empty();
	$('#search_cate2').append('<option value="">중분류 선택</option>');
	// 선택된 대분류에 해당하는 중분류 목록을 AJAX로 가져오기
	jQuery.ajax({
		url: '/teventmain/ajax_sch_cate2_by_1rd_set1',
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
				// 대분류 변경 후 자동 검색
				performSearch();
			} else 
			{
				alertToast('error', json_result['msg']);
			}
		}
	}).done((res) => {
		console.log('통신성공');
	}).fail((error) => {
		console.log('통신실패');
		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
		location.href='/tlogin';
		return;
	});
});

$('#search_cate2').change(function(){
	// 중분류 변경 시 자동 검색
	performSearch();
});

$('#search_cate11').change(function(){
	var selectedCate11 = $(this).val();
	
	// search_cate22 초기화
	$('#search_cate22').empty();
	$('#search_cate22').append('<option value="">중분류 선택</option>');
	// 선택된 대분류에 해당하는 중분류 목록을 AJAX로 가져오기
	jQuery.ajax({
		url: '/teventmain/ajax_sch_cate2_by_1rd_set2',
		type: 'POST',
		data: 'search_cate11=' + selectedCate11,
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
					$('#search_cate22').append('<option value="' + item['2RD_CATE_CD'] + '">' + item['CATE_NM'] + '</option>');
				});
			} else 
			{
				alertToast('error', json_result['msg']);
			}
		}
	}).done((res) => {
		console.log('통신성공');
	}).fail((error) => {
		console.log('통신실패');
		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
		location.href='/tlogin';
		return;
	});
});

$('#search_cate22').change(function(){
	// 중분류 변경 시 자동 검색
	performSearch();
});

function btn_search()
{
	performSearch();
}

function send_buy_sno()
{
	if ( $('#select_mem_sno_count').text() == "0")
	{
		alertToast('error',"보내기 회원을 선택해주세요.");
		return;
	}
	
    var acc_dv = $('#acc_rtrct_dv').val();
    var acc_mthd = $('#acc_rtrct_mthd').val();
    var a1rd_cd = $('#search_cate11').val();
    var a2rd_cd = $('#search_cate22').val();
    
    if (a1rd_cd == "" && a2rd_cd == "")
    {
    	alertToast('error',"미구매 분류에서 대분류 또는 중분류 중 하나 이상을 선택하세요.");
		return;
    }

	if (a1rd_cd != '')
	{
		location.href="/teventmain/send_event?acc_dv="+acc_dv+"&acc_mthd="+acc_mthd+"&1rd_cd="+a1rd_cd+"&2rd_cd="+a2rd_cd;
	}
	
	if (a2rd_cd != '')
	{
		location.href="/teventmain/send_event?2rd_cd="+a2rd_cd;
	}
	
}

function p_chk(t)
{
	var chk_tf = $(t).prop('checked');
	var params = "send_mem_sno="+$(t).val()+"&chk_tf="+chk_tf;
	//var params = "send_buy_sno="+$(t).val()+"&chk_tf="+chk_tf;
	jQuery.ajax({
        url: '/teventmain/ajax_send_mem_sno_chk',
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
				$('#select_mem_sno_count').text(json_result['send_mem_sno'].length);
			} else 
			{
				alertToast('error',json_result['msg']);
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

$('#domcy_acppt_i_sdate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
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

</script>