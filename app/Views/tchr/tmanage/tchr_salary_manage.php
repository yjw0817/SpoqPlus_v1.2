<style>
.table th, .table td {
    /* padding: 0.3rem !important;
    font-size: 0.9rem; */
}

.table-bordered th, .table-bordered td {
    border: 1px solid #a3a3a3;
}

table.table-hover tbody tr:hover {
    background-color: #81b1eb !important; 
}
.table thead{margin-top:5px;}
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
<!-- CARD HEADER [END] -->

<div class="panel panel-inverse">
        <div class="card-header py-3">
            <h5 class="m-0 text-white">강사 검색</h5>
        </div>
<form name="form_search_tchr_salary_manage" id="form_search_tchr_salary_manage" method="post" onsubmit="return false;">
   <div class="bbs_search_box_a3 mb10 mt10">
		<ul>
		  <li>ㆍ 강사선택  </li>

		  <li>
			 <select class="form-control"  name='tid' id='tid'>
					<option value="">전체강사</option>
					<?php foreach($tchr_list as $t) : ?>
					<option value="<?php echo $t['MEM_ID']?>" <?php if($t['MEM_ID'] == $tid) {?> selected <?php }?> >[<?php echo $t['TCHR_POSN_NM']?>] <?php echo $t['MEM_NM']?> - <?php echo $t['CTRCT_TYPE_NM']?></option>
					<?php endforeach;?>
				</select>

		  </li>
		  <li>
			 <select class="form-control"  name="ss_yy" id="ss_yy">
					<?php for($i=date('Y');$i >= 2020; $i--) :?>
					<option value="<?php echo $i?>" <?php if($ss_yy == $i) {?> selected <?php } ?> ><?php echo $i?>년</option>
					<?php endfor;?>
				</select>
		  </li>
		  <li>
			 <select class="form-control"   name="ss_mm" id="ss_mm">
					<option value="01" <?php if($ss_mm == '01') {?> selected <?php } ?> >1월</option>
					<option value="02" <?php if($ss_mm == '02') {?> selected <?php } ?>>2월</option>
					<option value="03" <?php if($ss_mm == '03') {?> selected <?php } ?>>3월</option>
					<option value="04" <?php if($ss_mm == '04') {?> selected <?php } ?>>4월</option>
					<option value="05" <?php if($ss_mm == '05') {?> selected <?php } ?>>5월</option>
					<option value="06" <?php if($ss_mm == '06') {?> selected <?php } ?>>6월</option>
					<option value="07" <?php if($ss_mm == '07') {?> selected <?php } ?>>7월</option>
					<option value="08" <?php if($ss_mm == '08') {?> selected <?php } ?>>8월</option>
					<option value="09" <?php if($ss_mm == '09') {?> selected <?php } ?>>9월</option>
					<option value="10" <?php if($ss_mm == '10') {?> selected <?php } ?>>10월</option>
					<option value="11" <?php if($ss_mm == '11') {?> selected <?php } ?>>11월</option>
					<option value="12" <?php if($ss_mm == '12') {?> selected <?php } ?>>12월</option>
				</select>
		  </li>
          <li style="display: none;">
			<a href="#"  onclick="btn_search();" class="serch_bt" type="button"><i class="fas fa-search"></i> 검색</a>
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
		<form name="form_search_tchr_salary_manage" id="form_search_tchr_salary_manage" method="post" action="/tmanage/tchr_salary_manage">
			<div class="input-group input-group-sm mb-1">
				
				<select class="" style="width: 150px;margin-left:5px;" name="ss_yy" id="ss_yy">
					<?php for($i=date('Y');$i >= 2020; $i--) :?>
					<option value="<?php echo $i?>" <?php if($ss_yy == $i) {?> selected <?php } ?> ><?php echo $i?>년</option>
					<?php endfor;?>
				</select>
				
				<select class="" style="width: 150px;margin-left:5px;" name="ss_mm" id="ss_mm">
					<option value="01" <?php if($ss_mm == '01') {?> selected <?php } ?> >1월</option>
					<option value="02" <?php if($ss_mm == '02') {?> selected <?php } ?>>2월</option>
					<option value="03" <?php if($ss_mm == '03') {?> selected <?php } ?>>3월</option>
					<option value="04" <?php if($ss_mm == '04') {?> selected <?php } ?>>4월</option>
					<option value="05" <?php if($ss_mm == '05') {?> selected <?php } ?>>5월</option>
					<option value="06" <?php if($ss_mm == '06') {?> selected <?php } ?>>6월</option>
					<option value="07" <?php if($ss_mm == '07') {?> selected <?php } ?>>7월</option>
					<option value="08" <?php if($ss_mm == '08') {?> selected <?php } ?>>8월</option>
					<option value="09" <?php if($ss_mm == '09') {?> selected <?php } ?>>9월</option>
					<option value="10" <?php if($ss_mm == '10') {?> selected <?php } ?>>10월</option>
					<option value="11" <?php if($ss_mm == '11') {?> selected <?php } ?>>11월</option>
					<option value="12" <?php if($ss_mm == '12') {?> selected <?php } ?>>12월</option>
				</select>
				
				<button type="button" class="btn btn-sm btn-success" style='margin-left:5px;' onclick="btn_search();">검색</button>
				
			</div>
			<div class="input-group input-group-sm mb-1">
				<select class='select2' style='width:310px;' name='tid' id='tid'>
					<option value="">강사선택</option>
					<?php foreach($tchr_list as $t) : ?>
					<option value="<?php echo $t['MEM_ID']?>" <?php if($t['MEM_ID'] == $tid) {?> selected <?php }?> >[<?php echo $t['TCHR_POSN_NM']?>] <?php echo $t['MEM_NM']?> - <?php echo $t['CTRCT_TYPE_NM']?></option>
					<?php endforeach;?>
				</select>
			</div>
			
		</form>
	</div>
</div> -->

<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">강사 수당 집계표</h4>
		<div class="panel-heading-btn">
		</div>
	</div>
		<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		<!-- TABLE [START] -->
		<?php if(empty($list_sarly)) : ?>
			<div class="text-center" style="padding: 50px;">검색 결과가 없습니다.</div>
		<?php else : ?>
			<table class="table table-bordered table-hover table-striped col-md-12">
				<?php
				foreach($list_sarly as $key => $tchr_sarly) :
				?>
			<thead>
				<tr>
					<td class='bg-white text-center bold' colspan="13">
						<?php if(isset($tchr_sarly['MEM_THUMB_IMG']) || isset($tchr_sarly['MEM_GENDR'])) : ?>
							<img class="preview_mem_photo"
								id="preview_mem_photo_<?php echo $tchr_sarly['MEM_SNO']?>"
								src="<?php echo isset($tchr_sarly['MEM_THUMB_IMG']) ? $tchr_sarly['MEM_THUMB_IMG'] : '' ?>"
								alt="강사사진"
								style="cursor: pointer;"
								onclick="showFullPhoto('<?php echo isset($tchr_sarly['MEM_MAIN_IMG']) ? $tchr_sarly['MEM_MAIN_IMG'] : '' ?>')"
								onerror="this.onerror=null; this.src='/dist/img/default_profile_<?php echo isset($tchr_sarly['MEM_GENDR']) ? $tchr_sarly['MEM_GENDR'] : 'M' ?>.png';">
						<?php endif; ?>
						<?php echo $tchr_sarly['MEM_NM']?> (<?php echo $key?>)
					</td>
				</tr>
				<tr class='text-center'>
					<th class='' style='width:90px'>적용 시작일</th>
					<th class='' style='width:90px'>적용 종료일</th>
					<th class='' style='width:90px'>지급조건</th>
					<th class=''>지급방법</th>
					
					<th class='bg-info' style='width:90px'>집계 시작일</th>
					<th class='bg-info' style='width:90px'>집계 종료일</th>
					
					<th class='bg-success' style='width:90px'>판매매출액</th>
					<th class='bg-success' style='width:90px'>수업매출액</th>
					<th class='bg-success' style='width:90px'>수업횟수</th>
					
					<th class='bg-warning' style='width:90px'>요율(%)</th>
					<th class='bg-warning' style='width:90px'>수당금액</th>
					<th class='bg-warning' style='width:90px'>1회금액</th>
					
					<th class='bg-pink' style='width:90px'>지급금액</th>
				</tr>
			</thead> 
			<tbody>
				<?php
					$sum_cost = 0;
					foreach ($tchr_sarly['sarly_mgmt'] AS $r) :
						$sum_cost = $sum_cost + $r['cost'];
						
						$rate_class = "";
						$amt_class = "";
						$onetm_class = "";
						if($r['calcu_set_rate_yn'] == 'N') $rate_class = 'bg-danger';
						if($r['calcu_set_amt_yn'] == 'N') $amt_class = 'bg-danger';
						if($r['calcu_set_1tm_yn'] == 'N') $onetm_class = 'bg-danger';
						
						if ($r['sell_amt'] == 0 && $r['pt_amt'] == 0 && $r['pt_count'] == 0)
						{
							if($r['calcu_set_rate_yn'] == 'N') $rate_class = 'bg-warning';
							if($r['calcu_set_amt_yn'] == 'N') $amt_class = 'bg-warning';
							if($r['calcu_set_1tm_yn'] == 'N') $onetm_class = 'bg-warning';
						}
				?>
				
				<tr>
					<td><?php echo $r['SARLY_APLY_S_DATE']?></td>
					<td><?php echo $r['SARLY_APLY_E_DATE']?></td>
					
					<td><?php echo $sDef['SARLY_PMT_COND'][$r['SARLY_PMT_COND']]?></td>
					<td>
						<i style='cursor:pointer;color:skyblue' class="fas fa-info-circle" onclick="info_detail('<?php echo $r['SARLY_MGMT_SNO']?>');"></i>
						<?php echo $sDef['SARLY_PMT_MTHD_NAME'][$r['SARLY_PMT_MTHD']]?>
					</td>
					
					<td><?php echo substr($r['new_s_date'],0,10)?></td>
					<td><?php echo substr($r['new_e_date'],0,10)?></td>
					
					<!-- 판매 매출액 -->
					<?php if($r['sell_amt'] > 0 ) :?>
					<td style="cursor:pointer; text-align:right" onclick="detail_info('<?php echo $key?>','01','<?php echo $r['SARLY_MGMT_SNO']?>');"><?php echo number_format($r['sell_amt'])?></td>
					<?php else :?>
					<td style='color:red; text-align:right;'><?php echo number_format($r['sell_amt'])?></td>
					<?php endif ;?>
					
					<!-- 수업 매출액 -->
					<?php if($r['pt_amt'] > 0 ) :?>
					<td style="cursor:pointer; text-align:right;" onclick="detail_info('<?php echo $key?>','02','<?php echo $r['SARLY_MGMT_SNO']?>');"><?php echo number_format($r['pt_amt'])?></td>
					<?php else :?>
					<td style='color:red; text-align:right;'><?php echo number_format($r['pt_amt'])?></td>
					<?php endif ;?>
					
					<!-- 수업횟수 -->
					<?php if($r['pt_count'] > 0 ) :?>
					<?php if($r['SARLY_PMT_MTHD'] == '32') :?>
					<td style="cursor:pointer; text-align:right;" onclick="detail_info('<?php echo $key?>','04','<?php echo $r['SARLY_MGMT_SNO']?>');"><?php echo number_format($r['pt_count'])?></td>
					<?php else :?>
					<td style="cursor:pointer; text-align:right;" onclick="detail_info('<?php echo $key?>','03','<?php echo $r['SARLY_MGMT_SNO']?>');"><?php echo number_format($r['pt_count'])?></td>
					<?php endif;?>
					<?php else :?>
					<td style='color:red; text-align:right;'><?php echo number_format($r['pt_count'])?></td>
					<?php endif ;?>
					
					<?php if($rate_class == 'bg-danger') :?>
						<td class='text-right <?php echo $rate_class?>' onclick="more_salary_setting('<?php echo $r['SARLY_MGMT_SNO']?>');"><?php echo number_format($r['calcu_set_rate'])?></td>
					<?php else :?>
						<td class='text-right <?php echo $rate_class?>'><?php echo number_format($r['calcu_set_rate'])?></td>
					<?php endif;?>
					
					<?php if($amt_class == 'bg-danger') :?>
						<td class='text-right <?php echo $amt_class?>' onclick="more_salary_setting('<?php echo $r['SARLY_MGMT_SNO']?>');"><?php echo number_format($r['calcu_set_amt'])?></td>
					<?php else :?>
						<td class='text-right <?php echo $amt_class?>'><?php echo number_format($r['calcu_set_amt'])?></td>
					<?php endif;?>
					
					<?php if($amt_class == 'bg-danger') :?>
						<td class='text-right <?php echo $onetm_class?>' onclick="more_salary_setting('<?php echo $r['SARLY_MGMT_SNO']?>');"><?php echo number_format($r['calcu_set_1tm'])?></td>
					<?php else :?>
						<td class='text-right <?php echo $onetm_class?>'><?php echo number_format($r['calcu_set_1tm'])?></td>
					<?php endif;?>
					
					<td style="text-align:right"><?php echo number_format($r['cost'])?></td>
				</tr>
				 
				<?php 
					endforeach;
				echo "<tr class='a'><td class='text-right aa' colspan='13'><b>". number_format($sum_cost) . "</b></td></tr>";
				echo "</tbody>";
				endforeach;
				?>
                
			</table>
		<?php endif; ?>
		<!-- TABLE [END] -->
		<!-- TABLE [END] -->
	</div>
					<!-- CARD BODY [END] -->
</div>
			
	
<!-- ############################## MODAL [ SATRT ] #################################### -->

<!-- ============================= [ modal-sm START 운동시작일 변경 ] ============================================ -->
<div class="modal fade" id="modal_salary">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">수당설정 상세</h4>
                <button type="button" class="close3"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>적용일</span>
                	</span>
                	<input type="text" class="form-control" id="sdetail_date" readonly value="2024-06-26 ~ 2024-10-31">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>수당 대상</span>
                	</span>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>대분류</span>
                	</span>
                	<input type="text" class="form-control" id="sdetail_item1" readonly value="헬스, 기타, 골프, 기본PT, 새벽PT, 락커, 골프라커">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>중분류</span>
                	</span>
                	<input type="text" class="form-control" id="sdetail_item2" readonly value="헬스, 기타, 골프, 기본PT, 새벽PT, 락커, 골프라커">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>지급조건</span>
                	</span>
                	<input type="text" class="form-control" id="sdetail_pmt_cond" readonly value="전체강사">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>부가세설정</span>
                	</span>
                	<input type="text" class="form-control" id="sdetail_vat_yn" readonly value="부가세포함">
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>조건설정</span>
                	</span>
                	<input type="text" class="form-control" id="sdetail_pmt_mthd" readonly value="판매매출액(요율%)">
                </div>
                <div class="input-group input-group-sm mb-1" id="sdetail_list">
                </div>
                
                
            	
            	
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-sm END ] ============================================== -->
	
<!-- ############################## MODAL [ END ] ###################################### -->
	
<form name="form_detail_info" id="form_detail_info" method="post" action="/tmanage/tchr_salary_detail">
	<input type="hidden" name="ss_yy" id="detail_ss_yy" value="<?php echo $ss_yy?>" />
	<input type="hidden" name="ss_mm" id="detail_ss_mm" value="<?php echo $ss_mm?>" />
	<input type="hidden" name="mid" id="detail_mid" />
	<input type="hidden" name="kind" id="detail_kind" />
	<input type="hidden" name="sno" id="detail_sno" />
	<input type="hidden" name="tid" id="search_mid" value="<?php echo $tid?>" />
</form>	
	
</section>


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
    
    // 초기 페이지 로드 시 전체강사 검색
    btn_search();
    
    // 강사 선택 변경 시 자동 검색
    $('#tid').on('change', function() {
        btn_search();
    });
    
    // 년도 변경 시 자동 검색
    $('#ss_yy').on('change', function() {
        btn_search();
    });
    
    // 월 변경 시 자동 검색
    $('#ss_mm').on('change', function() {
        btn_search();
    });
})

function info_detail(sno)
{
	
	
	var params = "sno="+sno;
	jQuery.ajax({
        url: '/tmanage/ajax_salary_detail',
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
				$('#modal_salary').modal("show");
				
				console.log(json_result['info']);
				console.log('-------');
				console.log(json_result['sub']);
				
				$('#sdetail_date').val(json_result['info']['TEXT_DATE']); // 적용일
				$('#sdetail_item1').val(json_result['info']['TEXT_ITEM1']); // 대분류
				$('#sdetail_item2').val(json_result['info']['TEXT_ITEM2']); // 중분류
				$('#sdetail_pmt_cond').val(json_result['info']['TEXT_PMT_COND']); // 지급조건
				$('#sdetail_vat_yn').val(json_result['info']['TEXT_VAT_YN']); // 부가세설정
				$('#sdetail_pmt_mthd').val(json_result['info']['TEXT_PMT_MTHD']); // 조건설정 (타이틀)
				
				var add_html = "";
				json_result['sub'].forEach(function (r,index) {
					add_html += "<span class='input-group-text' style='width:100%;font-size:0.9rem;'>"+r+"</span>";
				});
				
				if (add_html == '')
				{
					add_html = "<span class='input-group-text' style='width:100%;font-size:0.9rem;color:red'>조건설정이 없습니다.</span>";
				}
				
				$('#sdetail_list').html(add_html);
				
			} else 
			{
				console.log(json_result);
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


function detail_info(mid,kind,sno)
{
	//location.href="/tmanage/tchr_salary_detail/"+ss_yy+"/"+ss_mm+"/"+mid+"/"+kind+"/"+sno;
	
	$('#detail_mid').val(mid);
	$('#detail_kind').val(kind);
	$('#detail_sno').val(sno);
	// 검색 조건을 추가로 설정
	$('#detail_ss_yy').val($('#ss_yy').val());
	$('#detail_ss_mm').val($('#ss_mm').val());
	$('#search_mid').val($('#tid').val());
	$('#form_detail_info').submit();
}

function more_salary_setting(sm_sno)
{
	location.href="/tmemmain/tchr_salary_setting/"+sm_sno;
}

// AJAX 요청 관리를 위한 변수
let currentSearchRequest = null;

function btn_search()
{
    // 전체강사 또는 특정 강사 둘 다 검색 진행
    // 비어있으면 전체강사로 처리
    
    // 이전 검색 요청이 있으면 취소
    if (currentSearchRequest && currentSearchRequest.readyState !== 4) {
        currentSearchRequest.abort();
    }
    
    // AJAX 검색 요청
    var formData = $('#form_search_tchr_salary_manage').serialize();
    
    // 로딩 표시
    $('.panel-body').html('<div class="text-center" style="padding: 50px;"><i class="fa fa-spinner fa-spin fa-3x"></i><br>검색중...</div>');
    
    currentSearchRequest = $.ajax({
        url: '/tmanage/ajax_tchr_salary_search',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.result == 'true') {
                // 테이블 내용 업데이트
                $('.panel-body').html(response.html);
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