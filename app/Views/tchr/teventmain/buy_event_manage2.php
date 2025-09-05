<style>
/* 달력이 헤더에 가려지지 않도록 z-index 조정 */
.datepicker {
    z-index: 9999 !important;
}
.datepicker-dropdown {
    z-index: 9999 !important;
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


<form name="form_buy_event_manage1" id="form_buy_event_manage1" method="post" action="/teventmain/buy_event_manage2" onsubmit="return false;">

   <div class="bbs_search_box_a3 mb10 mt10">
		<ul>
		  <li>ㆍ 검색일(시작)  </li>
		  <li>
			<input type="text" class="form-control datepp" name="sch_end_sdate" id="sch_end_sdate"  value="<?php echo $search_val['sch_end_sdate']?>" >
		  </li>
		  <li>
			 <select class="form-control" name="acc_rtrct_dv" id="acc_rtrct_dv">
				<?php foreach ($acc_rtrct_dv as $r => $key) : ?>
				<?php if($r != '') :?>
				<option value="<?php echo $r?>" <?php if($search_val['acc_rtrct_dv'] == $r) {?> selected <?php } ?> ><?php echo $key?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>
		  </li>		  		
		  <li>
			 <select class="form-control" name="acc_rtrct_mthd" id="acc_rtrct_mthd">
				<?php foreach ($acc_rtrct_mthd as $r => $key) : ?>
				<?php if($r != '') :?>
				<option value="<?php echo $r?>" <?php if($search_val['acc_rtrct_mthd'] == $r) {?> selected <?php } ?> ><?php echo $key?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>
		  </li>			  
		   <li>
			<a href="#" type="button" class="basic_bt02" onclick="$('#help_con').show();" style="display:none;">
			<i class="far fa-question-circle"></i> 주의</a>

		  </li>	  		
		 </ul>
	</div>	
	 <div class="bbs_search_box_b3 mb10 mt10">
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
		</ul>
	</div>	
	 <div class="bbs_search_box_a4 mb10 mt10">
		<ul>
           <li>ㆍ 미구매 분류 </li>
           <li>
			<select class="form-control"  name="search_cate11" id="search_cate11">
				<option value="">대분류 선택</option>
				<?php foreach ($sch_cate1 as $r) : ?>
				<option value="<?php echo $r['1RD_CATE_CD']?>" <?php if($search_val['search_cate11'] == $r['1RD_CATE_CD']) {?> selected <?php } ?> ><?php echo $r['CATE_NM']?></option>
				<?php endforeach; ?>
			</select>
			</li>	
            <li>
			<select class="form-control"  name="search_cate22" id="search_cate22">
				<option value="">중분류 선택</option>
				<?php foreach ($sch_cate2 as $r) : ?>
				<option value="<?php echo $r['2RD_CATE_CD']?>" <?php if($search_val['search_cate22'] == $r['2RD_CATE_CD']) {?> selected <?php } ?> ><?php echo $r['CATE_NM']?></option>
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




<!-- <div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">검색 조건</h4>
	</div>

	<div class="card-footer clearfix">
		
		<div class="row" id='help_con' style='display:none;'>
			<div class="col_md-12">
				<div class="alert alert-warning alert-dismissible" >
					<button type="button" class="close" onclick="$('#help_con').hide();" >&times;</button>
					<small><strong>출입제한조건 및 대분류 또는 중분류에 해당하는 회원중 운동시작일이 검색일(시작) 이후인 상품의 회원을 검색합니다.</strong></small><br />
					<small>1. 검색조건 : 운동시작일 이후로, 출입제한 조건과 대분류 또는 중분류를 선택합니다.</small><br />
					<small>2. 1번 조건에서 구매안한 대분류 또는 중분류를 선택하여 검색합니다.</small><br />
					<small>3. 검색된 상품에서 상품 보내기할 회원을 선택한 해주세요. </small><br />
					<small>4. 선택이 완료되면 상품 보내기 버튼을 클릭하여 검색된 상품을 선택하여 상품을 보낼 수 있습니다.</small>
				</div>
			</div>
		</div>
		
		<form name="form_buy_event_manage1" id="form_buy_event_manage1" method="post" action="/teventmain/buy_event_manage2">

		<div class="input-group input-group-sm mb-1">
			<span class="input-group-append">
				<span class="input-group-text input-readonly" style='width:150px'>검색일(시작)</span>
			</span>
			<input type="text" class="datepp" name="sch_end_sdate" id="sch_end_sdate" style='width:100px' value="<?php echo $search_val['sch_end_sdate']?>" >

			<select class=" " style="width: 150px;margin-left:5px;" name="acc_rtrct_dv" id="acc_rtrct_dv">

			<?php foreach ($acc_rtrct_dv as $r => $key) : ?>
			<?php if($r != '') :?>
				<option value="<?php echo $r?>" <?php if($search_val['acc_rtrct_dv'] == $r) {?> selected <?php } ?> ><?php echo $key?></option>
			<?php endif; ?>
			<?php endforeach; ?>
			</select>
			
			<select class=" " style="width: 150px;margin-left:5px;" name="acc_rtrct_mthd" id="acc_rtrct_mthd">

			<?php foreach ($acc_rtrct_mthd as $r => $key) : ?>
			<?php if($r != '') :?>
				<option value="<?php echo $r?>" <?php if($search_val['acc_rtrct_mthd'] == $r) {?> selected <?php } ?> ><?php echo $key?></option>
			<?php endif; ?>
			<?php endforeach; ?>
			</select>
			<button type="button" class="btn btn-sm btn-warning" onclick="$('#help_con').show();">
			<i class="fas fa-question"></i>
			</button>
			
		</div>
		
		<div class="input-group input-group-sm mb-1">
			<span class="input-group-append">
				<span class="input-group-text input-readonly" style='width:150px'>검색분류</span>
			</span>
			
			<select class=" " style="width: 150px;margin-left:5px;" name="search_cate1" id="search_cate1">
				<option value="">대분류 선택</option>
			<?php foreach ($sch_cate1 as $r) : ?>
				<option value="<?php echo $r['1RD_CATE_CD']?>" <?php if($search_val['search_cate1'] == $r['1RD_CATE_CD']) {?> selected <?php } ?> ><?php echo $r['CATE_NM']?></option>
			<?php endforeach; ?>
			</select>
			<select class=" " style="width: 150px;margin-left:5px;" name="search_cate2" id="search_cate2">
				<option value="">중분류 선택</option>
			<?php foreach ($sch_cate2 as $r) : ?>
				<option value="<?php echo $r['2RD_CATE_CD']?>" <?php if($search_val['search_cate2'] == $r['2RD_CATE_CD']) {?> selected <?php } ?> ><?php echo $r['CATE_NM']?></option>
			<?php endforeach; ?>
			</select>
			
			<span class="input-group-append">
				<span class="input-group-text input-readonly" style='width:150px;margin-left:10px;'>구매안한 분류</span>
			</span>
			<select class=" " style="width: 150px;margin-left:5px;" name="search_cate11" id="search_cate11">
				<option value="">대분류 선택</option>
			<?php foreach ($sch_cate1 as $r) : ?>
				<option value="<?php echo $r['1RD_CATE_CD']?>" <?php if($search_val['search_cate11'] == $r['1RD_CATE_CD']) {?> selected <?php } ?> ><?php echo $r['CATE_NM']?></option>
			<?php endforeach; ?>
			</select>
			<select class=" " style="width: 150px;margin-left:5px;" name="search_cate22" id="search_cate22">
				<option value="">중분류 선택</option>
			<?php foreach ($sch_cate2 as $r) : ?>
				<option value="<?php echo $r['2RD_CATE_CD']?>" <?php if($search_val['search_cate22'] == $r['2RD_CATE_CD']) {?> selected <?php } ?> ><?php echo $r['CATE_NM']?></option>
			<?php endforeach; ?>
			</select>
			
			<button type="button" class="btn btn-sm btn-success" style='margin-left:5px;' onclick="btn_search();">검색</button>
			<button type="button" class="btn btn-sm btn-success" style='margin-left:5px; margin-right:25px' onclick="send_buy_sno();">상품보내기</button>
			보내기 선택한 회원 수 : <span id='select_mem_sno_count'><?php echo count($send_mem_sno)?></span> 명
		</div>

		</form>
	</div>
</div> -->
	
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
		<table class="table table-bordered table-hover col-md-12 mt20">
			<thead>
				<tr class='text-center'>
					<th style='width:60px'>선택</th>
					<th style='width:70px'>상태</th>
					<th style='width:80px'>이름</th>
					<th style='width:80px'>아이디</th>
					<th style='width:120px'>전화번호</th>
					<th style='width:100px'>생년월일</th>
					<th style='width:50px'>성별</th>
					<th>주소</th>
					<th>가입장소</th>
					<th style='width:150px'>가입일시</th>
					<th style='width:150px'>등록일시</th>
					<th style='width:150px'>재등록일시</th>
					<th style='width:150px'>종료일시</th>
					<th>옵션</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($mem_list as $r): ?>
				<?php
				$backColor = "";
				if ($r['MEM_STAT'] == "00") $backColor = "#cfecf0"; //가입회원
				if ($r['MEM_STAT'] == "90") $backColor = "#f7d5d9"; //종료회원
				
				$mem_mem_chk = "";
				if (in_array($r['MEM_SNO'], $send_mem_sno)) $mem_mem_chk = "checked";
				
				?>
				<tr style="background-color: <?php echo $backColor ?>;">
					<td class='text-center'>
						<div class="icheck-primary d-inline">
							<input type="checkbox" name="send_chk[]" id="send_chk_<?php echo $r['MEM_SNO']?>" value="<?php echo $r['MEM_SNO']?>" onclick="p_chk(this);" <?php echo $mem_mem_chk?>>
							<label for="send_chk_<?php echo $r['MEM_SNO']?>">
								<small></small>
							</label>
						</div>
					</td>
					<td><?php echo $sDef['MEM_STAT'][$r['MEM_STAT']]?></td>
					<td><?php echo $r['MEM_NM']?></td>
					<td><?php echo $r['MEM_ID']?></td>
					<td><?php echo disp_phone($r['MEM_TELNO'])?></td>
					<td><?php echo disp_birth($r['BTHDAY'])?></td>
					<td><?php echo $sDef['MEM_GENDR'][$r['MEM_GENDR']]?></td>
					<td><?php echo $r['MEM_ADDR']?></td>
					<td><?php echo $sDef['JON_PLACE'][$r['JON_PLACE']]?></td>
					<td><?php echo substr($r['JON_DATETM'],0,16)?></td>
					<td><?php echo substr($r['REG_DATETM'],0,16)?></td>
					<td><?php echo substr($r['RE_REG_DATETM'],0,16)?></td>
					<td><?php echo substr($r['END_DATETM'],0,16)?></td>
					<td style="text-align:center">
						<button type="button" class="btn btn-warning btn-xs" onclick="mem_manage_mem_info('<?php echo $r['MEM_SNO']?>');">정보보기</button>
					</td>
				</tr>
				<?php endforeach; ?>
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
    
    // 날짜 변경 시 자동 검색
    $('#sch_end_sdate').on('change', function() {
        performSearch();
    });
    
    // 출입제한 조건 변경 시 자동 검색
    $('#acc_rtrct_dv, #acc_rtrct_mthd').on('change', function() {
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
        url: '/teventmain/ajax_buy_event_manage2_search',
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
                        $('#send_chk_' + mem_sno).prop('checked', true);
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

</script>