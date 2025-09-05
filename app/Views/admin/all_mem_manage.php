<style>
</style>
<?php
$sDef = SpoqDef();
?>
<!-- CARD HEADER [START] -->
<h1 class="page-header"><?php echo $title ?></h1>
<!-- CARD HEADER [END] -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">회원조회</h4>
	</div>
	<div class="panel-body">
		<form name="form_search_mem_manage" id="form_search_mem_manage" method="post" action="/adminmain/all_mem_manage">



<div class="bbs_search_box_a4 mb10 mt10">
		<ul>
		  <li>ㆍ 날짜검색  </li>
		  <!-- <li>
			<input type="text" class="form-control"  name="snm" id="snm"   value="<?php echo $search_val['snm']?>" >
		  </li> -->
		  <li>
			 <select class="form-control" name="sdcon" id="sdcon" onchange="dateConCh(this);">
					<option value="">전체</option>
					<option value="reg" <?php if($search_val['sdcon'] == "reg") {?> selected <?php } ?> >가입일시</option>
				</select>
		  </li>
		  <li>
			<input type="text" class="form-control" name="sdate" id="sdate" placeholder="검색시작일일" data-listener-added_92794c28="true" value="<?php echo $search_val['sdate']?>" autocomplete='off' >
		  </li>
          <li>
			<input type="text" class="form-control" name="edate" id="edate" placeholder="검색종료일" data-listener-added_92794c28="true" value="<?php echo $search_val['edate']?>" autocomplete='off' >
		  </li>	  
		 </ul>
	</div>

<div class="bbs_search_box_a4 mb10 mt10">
		<ul>
		  <li>ㆍ 회원상태  </li>
		  <!-- <li>
			<input type="text" class="form-control"  name="snm" id="snm"   value="<?php echo $search_val['snm']?>" >
		  </li> -->
		  <li>
			 <select class="form-control" name="smst" id="smst">
				<option value="">전체</option>
				<?php foreach ($sDef['MEM_STAT'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val['smst'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
				</select>
		  </li>
		  <li>
			<input type="text" class="form-control" name="snm" id="snm" value="<?php echo $search_val['snm']?>" >
		  </li>
           
          <li>
			<a href="#"  onclick="btn_search();" class="serch_bt" type="button"><i class="fas fa-search"></i> 검색</a>
		  </li>
		 </ul>
	</div>


<!-- 
			<div class="input-group input-group-sm mb-1">
				<span class="input-group-append">
					<span class="input-group-text" style='width:120px;margin-left:5px;'>날짜검색옵션</span>
				</span>
				<select class=" " style="width: 100px;" name="sdcon" id="sdcon" onchange="dateConCh(this);">
					<option value="">전체</option>
					<option value="reg" <?php if($search_val['sdcon'] == "reg") {?> selected <?php } ?> >가입일시</option>
				</select>
				<div class="col-lg-3" style="margin-left:5px;" >
					<div class="input-group input-daterange">
						<input type="text" class="form-control" name="sdate" id="sdate" placeholder="검색시작일일" data-listener-added_92794c28="true" value="<?php echo $search_val['sdate']?>" autocomplete='off' >
						<span class="input-group-text input-group-addon">~</span>
						<input type="text" class="form-control" name="edate" id="edate" placeholder="검색종료일" data-listener-added_92794c28="true" value="<?php echo $search_val['edate']?>" autocomplete='off' >
					</div>
				</div>
			</div>
			<div class="input-group input-group-sm mb-1">
				<span class="input-group-append">
					<span class="input-group-text" style='width:120px;margin-left:5px;'>회원상태</span>
				</span>
				<select class=" " style="width: 100px;" name="smst" id="smst">
				<option value="">전체</option>
				<?php foreach ($sDef['MEM_STAT'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val['smst'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
				</select>

				<input type="text" class="ss-input" name="snm" id="snm" style='margin-left:5px;' value="<?php echo $search_val['snm']?>" >
				<button type="button" class="btn btn-inverse" id="btnSearch"  onclick="btn_search();"><i class="fa fa-search"></i> 검색</button>
			</div> -->


		</form>
		
	</div>
</div>
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">회원 리스트</h4>
		<div class="panel-heading-btn">
		</div>
	</div>
	<div class="panel-body table-responsive">
		 총 회원수 : <?php echo $totalCount?> 명
		<!-- <div id="data-table-default_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer"><div class="row mt-2 justify-content-between"><div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto"><div class="dt-length"><select name="data-table-default_length" aria-controls="data-table-default" class="form-select form-select-sm" id="dt-length-0"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select><label for="dt-length-0"> entries per page</label></div></div><div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto"><div class="dt-search"><label for="dt-search-0">Search:</label><input type="search" class="form-control form-control-sm" id="dt-search-0" placeholder="" aria-controls="data-table-default"></div></div></div><div class="row mt-2 justify-content-between dt-layout-table"><div class="d-md-flex justify-content-between align-items-center col-12 dt-layout-full col-md"> -->
			<table id="data-table-default" width="100%" class="table table-striped table-bordered align-middle text-nowrap dataTable dtr-inline collapsed" aria-describedby="data-table-default_info" style="width: 100%;"><colgroup><col data-dt-column="0" style="width: 39px;"><col data-dt-column="1" style="width: 39.5px;"><col data-dt-column="2" style="width: 178.469px;"><col data-dt-column="3" style="width: 215.031px;"></colgroup>
				<thead>
					<tr class="">
						<th width="1%" data-dt-column="0" rowspan="1" colspan="1" class="dt-type-numeric dt-orderable-asc dt-orderable-desc dt-ordering-asc" aria-sort="ascending" aria-label=": Activate to invert sorting" tabindex="0">
							<span class="dt-column-title" role="button"></span>
							<span class="dt-column-order"></span>
						</th>
						<th class="text-nowrap dt-orderable-asc dt-orderable-desc" style='width:140px' data-dt-column="2" rowspan="1" colspan="1" aria-label="Rendering engine: Activate to sort" tabindex="0">
							<span class="dt-column-title" role="button">회사설정</span>
							<span class="dt-column-order"></span>
						</th>
						<th class="text-nowrap dt-orderable-asc dt-orderable-desc" style='width:140px' data-dt-column="3" rowspan="1" colspan="1" aria-label="Browser: Activate to sort" tabindex="0">
							<span class="dt-column-title" role="button">지점설정</span>
							<span class="dt-column-order"></span>
						</th>
						<th class="text-nowrap dt-orderable-asc dt-orderable-desc dtr-hidden" style='width:80px' data-dt-column="4" rowspan="1" colspan="1" aria-label="Platform(s): Activate to sort" tabindex="0" style="display: none;">
							<span class="dt-column-title" role="button">이름</span>
							<span class="dt-column-order"></span>
						</th>
						<th class="text-nowrap dt-type-numeric dt-orderable-asc dt-orderable-desc dtr-hidden" style='width:80px' data-dt-column="5" rowspan="1" colspan="1" aria-label="Engine version: Activate to sort" tabindex="0" style="display: none;">
							<span class="dt-column-title" role="button">아이디</span>
							<span class="dt-column-order"></span>
						</th>
						<th class="text-nowrap dt-orderable-asc dt-orderable-desc dtr-hidden" style='width:120px' data-dt-column="6" rowspan="1" colspan="1" aria-label="CSS grade: Activate to sort" tabindex="0" style="display: none;">
							<span class="dt-column-title" role="button">전화번호</span>
							<span class="dt-column-order"></span>
						</th>
						<th class="text-nowrap dt-orderable-asc dt-orderable-desc dtr-hidden" style='width:100px' data-dt-column="6" rowspan="1" colspan="1" aria-label="CSS grade: Activate to sort" tabindex="0" style="display: none;">
							<span class="dt-column-title" role="button">생년월일</span>
							<span class="dt-column-order"></span>
						</th>
						<th class="text-nowrap dt-orderable-asc dt-orderable-desc dtr-hidden" style='width:50px' data-dt-column="6" rowspan="1" colspan="1" aria-label="CSS grade: Activate to sort" tabindex="0" style="display: none;">
							<span class="dt-column-title" role="button">성별</span>
							<span class="dt-column-order"></span>
						</th>
						<th class="text-nowrap dt-orderable-asc dt-orderable-desc dtr-hidden" style='width:150px' data-dt-column="6" rowspan="1" colspan="1" aria-label="CSS grade: Activate to sort" tabindex="0" style="display: none;">
							<span class="dt-column-title" role="button">주소</span>
							<span class="dt-column-order"></span>
						</th>
						<th class="text-nowrap dt-orderable-asc dt-orderable-desc dtr-hidden" style='width:150px' data-dt-column="6" rowspan="1" colspan="1" aria-label="CSS grade: Activate to sort" tabindex="0" style="display: none;">
							<span class="dt-column-title" role="button">등록일시</span>
							<span class="dt-column-order"></span>
						</th>
						<th class="text-nowrap dt-orderable-asc dt-orderable-desc dtr-hidden" style='width:80px' data-dt-column="6" rowspan="1" colspan="1" aria-label="CSS grade: Activate to sort" tabindex="0" style="display: none;">
							<span class="dt-column-title" role="button">접속가능</span>
							<span class="dt-column-order"></span>
						</th>
						<th class="text-nowrap dt-orderable-asc dt-orderable-desc dtr-hidden" style='width:80px' data-dt-column="6" rowspan="1" colspan="1" aria-label="CSS grade: Activate to sort" tabindex="0" style="display: none;">
							<span class="dt-column-title" role="button">사용(탈퇴)</span>
							<span class="dt-column-order"></span>
						</th>
						<th class="text-nowrap dt-orderable-asc dt-orderable-desc dtr-hidden" style='width:140px' data-dt-column="6" rowspan="1" colspan="1" aria-label="CSS grade: Activate to sort" tabindex="0" style="display: none;">
							<span class="dt-column-title" role="button">탈퇴일시</span>
							<span class="dt-column-order"></span>
						</th>
						<th class="text-nowrap dt-orderable-asc dt-orderable-desc dtr-hidden center" style='width:80px' data-dt-column="6" rowspan="1" colspan="1" aria-label="CSS grade: Activate to sort" tabindex="0" style="display: none;">
							<span class="dt-column-title" role="button">비고</span>
							<span class="dt-column-order"></span>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$listCount = $search_val['listCount'];
					foreach($mem_list as $r) :
						$backColor = "";
	// 								if ($r['MEM_STAT'] == "00") $backColor = "#cfecf0"; //가입회원
	// 								if ($r['MEM_STAT'] == "90") $backColor = "#f7d5d9"; //종료회원
	// 								if ($r['MEM_STAT'] == "99") $backColor = "#777777"; //탈퇴회원
					?>
					<tr class="odd gradeX">
						<td class='text-center'><?php echo $listCount?></td>
						<td class="fw-bold dt-type-numeric sorting_1"><?php echo $r['COMP_NM']?></td> 
						<td class="fw-bold dt-type-numeric sorting_1"><?php echo $r['BCOFF_NM']?></td>
						<td class="fw-bold dt-type-numeric sorting_1"><?php echo $r['MEM_NM']?></td>
						<td class="fw-bold dt-type-numeric sorting_1"><?php echo $r['MEM_ID']?></td>
						<td class="fw-bold dt-type-numeric sorting_1"><?php echo disp_phone($r['MEM_TELNO'])?></td>
						<td class="fw-bold dt-type-numeric sorting_1"><?php echo disp_birth($r['BTHDAY'])?></td>
						<td class="fw-bold dt-type-numeric sorting_1 text-center"><?php echo $sDef['MEM_GENDR'][$r['MEM_GENDR']]?></td>
						<td class="fw-bold dt-type-numeric sorting_1"><?php echo $r['MEM_ADDR']?></td>
						<td class="fw-bold dt-type-numeric sorting_1"><?php echo $r['CRE_DATETM']?></td>
						<td class="fw-bold dt-type-numeric sorting_1 text-center"><?php echo $r['CONN_POSS_YN']?></td>
						<td class="fw-bold dt-type-numeric sorting_1 text-center"><?php echo $r['USE_YN']?></td>
						<td class="fw-bold dt-type-numeric sorting_1"><?php echo $r['SECE_DATETM']?></td>
						<td class="fw-bold dt-type-numeric sorting_1 text-center">
							<?php if ($r['USE_YN'] == "Y") :?>
							<button type="button" class="btn btn-warning btn-xs" onclick="mem_sece_confirm('<?php echo $r['MEM_SNO']?>');">탈퇴하기</button>
							<?php endif; ?>
						</td>
					</tr>
					<?php
						$listCount--;
						endforeach; 
						?>
				</tbody>
				<tfoot></tfoot>
			</table>
		</div></div>
		<div class="row mt-2 justify-content-center">
			<div class="d-md-flex justify-content-center align-items-center dt-layout-start col-md-auto me-auto">
			<!-- <div class="dt-info" aria-live="polite" id="data-table-default_info" role="status">Showing 1 to 10 of 57 entries</div> -->
			</div><div class="d-md-flex justify-content-centern align-items-center dt-layout-end col-md-auto ms-auto">
			<div class="dt-paging">
				<nav aria-label="pagination">
				<!-- <ul class="pagination"><li class="dt-paging-button page-item disabled"><button class="page-link first" role="link" type="button" aria-controls="data-table-default" aria-disabled="true" aria-label="First" data-dt-idx="first" tabindex="-1">«</button></li><li class="dt-paging-button page-item disabled"><button class="page-link previous" role="link" type="button" aria-controls="data-table-default" aria-disabled="true" aria-label="Previous" data-dt-idx="previous" tabindex="-1">‹</button></li><li class="dt-paging-button page-item active"><button class="page-link" role="link" type="button" aria-controls="data-table-default" aria-current="page" data-dt-idx="0">1</button></li><li class="dt-paging-button page-item"><button class="page-link" role="link" type="button" aria-controls="data-table-default" data-dt-idx="1">2</button></li><li class="dt-paging-button page-item"><button class="page-link" role="link" type="button" aria-controls="data-table-default" data-dt-idx="2">3</button></li><li class="dt-paging-button page-item"><button class="page-link" role="link" type="button" aria-controls="data-table-default" data-dt-idx="3">4</button></li><li class="dt-paging-button page-item"><button class="page-link" role="link" type="button" aria-controls="data-table-default" data-dt-idx="4">5</button></li><li class="dt-paging-button page-item"><button class="page-link" role="link" type="button" aria-controls="data-table-default" data-dt-idx="5">6</button></li><li class="dt-paging-button page-item"><button class="page-link next" role="link" type="button" aria-controls="data-table-default" aria-label="Next" data-dt-idx="next">›</button></li><li class="dt-paging-button page-item"> -->
			<?=$pager?>
			<!-- <button class="page-link last" role="link" type="button" aria-controls="data-table-default" aria-label="Last" data-dt-idx="last">»</button> -->
		<!-- </li></ul> -->
	              </nav>
				</div>
			</div>
		</div>
	  </div>
	</div>
</div>
<div class="card-footer clearfix">
	<!-- BUTTON [START] --
	<ul class="pagination pagination-sm m-0 float-right">
		<li class="ac-btn"><a href="#modal_mem_insert_form" class="btn btn-success btn-sm" data-bs-toggle="modal">등록하기</a></li>
	</ul>
	
	<!-- BUTTON [END] -->
	<!-- PAGZING [START] -->
	
	<!-- PAGZING [END] -->
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
})


function mem_sece_confirm(mem_sno)
{
	ToastConfirm.fire({
        icon: "question",
        title: " [ 회원탈퇴 ]",
        html: "<font color='#000000' >회원 탈퇴를 진행 하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		var params = "mem_sno="+mem_sno;
    	    jQuery.ajax({
    	        url: '/adminmain/admin_sece_member_proc',
    	        type: 'POST',
    	        data:params,
    	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
    	        dataType: 'text',
    	        success: function (result) {
    	        	if ( result.substr(0,8) == '<script>' )
                	{
                		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                		location.href='/adminlogin';
                		return;
                	}
                	
    	            json_result = $.parseJSON(result);
    				if (json_result['result'] == 'true')
    				{
    					location.reload();
    				}
    	        }
    	    }).done((res) => {
            	// 통신 성공시
            	console.log('통신성공');
            }).fail((error) => {
            	// 통신 실패시
            	console.log('통신실패');
            	alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
        		location.href='/adminlogin';
        		return;
            });
    	}
    });	
}

$('#sdate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$('#edate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

// 회원별 통합정보 보기
function mem_manage_mem_info(mem_sno)
{
	location.href="/ttotalmain/info_mem/"+mem_sno;
}



$(".ss-input").on("keyup",function(key){
	if(key.keyCode==13) {
		btn_search();
	}
});

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

	$('#form_search_mem_manage').submit();
}

$('#mem_insert_btn').click(function(){
	// 실패일 경우 warning error success info question
	
	if ( $('#id_chk').val() == "N" )
	{
		alertToast('error','아이디 중복체크를 해주세요');
		return;
	}
	
	if ( $('#mem_id').val() == "" )
	{
		alertToast('error','아이디를 입력하세요');
		return;
	}
	
	if ( $('#mem_pwd').val() == "" )
	{
		alertToast('error','비밀번호를 입력하세요');
		return;
	}
	
	if ( $('#mem_nm').val() == "" )
	{
		alertToast('error','이름을 입력하세요');
		return;
	}
	
	
	if ( $('#mem_telno').val() == "" )
	{
		alertToast('error','전화번호를 입력하세요');
		return;
	}
	
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >회원을 등록하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		var params = $("#mem_insert_form").serialize();
    	    jQuery.ajax({
    	        url: '/tmemmain/ajax_mem_insert_proc',
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
    					location.reload();
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
    });	
	
});

function id_change(t)
{
	$('#id_chk').val("N");
	$('#id_chk_false').show();
	$('#id_chk_true').hide();
	
	
}

$('#mem_id').keyup(function(event){    
    if (!(event.keyCode >=37 && event.keyCode<=40)) 
    {    
        var inputVal = $(this).val();    
        $(this).val(inputVal.replace(/[^a-z0-9]/gi,''));   
    }  
});


$('#btn_code_chk').click(function(){
	if ( $('#mem_id').val() == "" )
	{
		alertToast('error','아이디를 입력하세요');
		return;
	}

	var params = "mem_id="+$('#mem_id').val();
    jQuery.ajax({
        url: '/tmemmain/ajax_id_chk',
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
				$('#id_chk').val("Y");
            	$('#id_chk_false').hide();
            	$('#id_chk_true').show();			
			} else 
			{
				alertToast('error','아이디가 중복되었습니다. 다른 아이디를 입력하세요');
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
});

</script>