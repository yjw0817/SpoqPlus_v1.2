<style>

</style>
<?php
$sDef = SpoqDef();
?>
<h1 class="page-header"><?php echo $title ?></h1>
<!-- CARD HEADER [END] -->
<!-- CARD HEADER [END] -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">강사 연차관리 리스트</h4>
		<div class="panel-heading-btn">
		</div>
	</div>

					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
	<div class="panel-body">
		<!-- TABLE [START] -->
		<table class="table table-bordered table-hover table-striped col-md-12">
			<thead>
				<tr class='text-center'>
					<th style='width:70px'>순번</th>
					<th style='width:150px'>연차 사용 가능 시작일</th>
					<th style='width:150px'>연차 사용 가능 종료일</th>
					<th style='width:150px'>부여일</th>
					<th style='width:150px'>사용일</th>
					<th style='width:150px'>남은일</th>
					<th style='width:150px'>등록일시</th>
					<th>옵션</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$cc_count = count($tchr_annu_list);
				foreach ($tchr_annu_list as $r) :?>
				<tr>
					<td class='text-center'><?php echo $cc_count?></td>
					<td><?php echo $r['ANNU_GRANT_S_DATE']?></td>
					<td><?php echo $r['ANNU_GRANT_E_DATE']?></td>
					<td><?php echo $r['ANNU_GRANT_DAY']?></td>
					<td><?php echo $r['ANNU_USE_DAY']?></td>
					<td><?php echo $r['ANNU_LEFT_DAY']?></td>
					<td><?php echo $r['CRE_DATETM']?></td>
					<td></td>
				<?php 
				$cc_count--;
				endforeach;
				?>
			</tbody>
		</table>
		<!-- TABLE [END] -->

	<div class="card-footer clearfix">
		<!-- BUTTON [START] -->
		<ul class="pagination pagination-sm m-0 float-right">
			<li class="ac-btn"><button type="button" class="btn btn-block btn-success btn-sm" onclick="annu_set_form();">연차신청</button></li>
		</ul>
		
		<!-- BUTTON [END] -->
		<!-- PAGZING [START] -->
		<!-- PAGZING [END] -->
	</div>


	</div>
	<!-- CARD BODY [END] -->
			
</div>
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">강사 연차신청 리스트</h4>
		<div class="panel-heading-btn">
		</div>
	</div>		
					<!-- CARD BODY [START] -->
		<div class="panel-body">
			<!-- TABLE [START] -->
			<table class="table table-bordered table-hover table-striped col-md-12">
				<thead>
					<tr class='text-center'>
						<th style='width:70px'>순번</th>
						<th style='width:150px'>상태</th>
						<th style='width:150px'>연차 사용 가능 시작일</th>
						<th style='width:150px'>연차 사용 가능 종료일</th>
						<th style='width:150px'>사용일</th>
						
						<th style='width:150px'>신청일시</th>
						<th style='width:150px'>승인일시</th>
						<th style='width:150px'>반려일시</th>
						<th style='width:150px'>취소일시</th>
						<th>옵션</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$bb_count = count($tchr_annu_appct_list);
					foreach ($tchr_annu_appct_list as $r) :?>
					<tr>
						<td class='text-center'><?php echo $bb_count?></td>
						<td><?php echo $sDef['ANNU_APPV_STAT'][$r['ANNU_APPV_STAT']]?></td>
						<td><?php echo $r['ANNU_APPCT_S_DATE']?></td>
						<td><?php echo $r['ANNU_APPCT_E_DATE']?></td>
						<td><?php echo $r['ANNU_USE_DAY']?></td>
						
						<td><?php echo $r['ANNU_APPV_DATETM']?></td>
						<td><?php echo $r['ANNU_APPCT_DATETM']?></td>
						<td><?php echo $r['ANNU_REFUS_DATETM']?></td>
						<td><?php echo $r['ANNU_CANCEL_DATETM']?></td>
						<td>
							<?php if($r['ANNU_APPV_STAT'] == "00") :?>
							<button type="button" class="btn btn-danger btn-xs" onclick="annu_cancel('<?php echo $r['ANNU_USE_MGMT_HIST_SNO']?>');">취소하기</button>
							<?php endif;?>
						</td>
					<?php
					$bb_count--;
					endforeach;?>
				</tbody>
			</table>
			<!-- TABLE [END] -->
			 		<div class="card-footer clearfix">
			<!-- BUTTON [START] -->
			
			<!-- BUTTON [END] -->
			<!-- PAGZING [START] -->
			<!-- PAGZING [END] -->
		</div>
		</div>
		<!-- CARD BODY [END] -->
		<!-- CARD FOOTER [START] -->

		<!-- CARD FOOTER [END] -->

	</div>

</div>
		</div>
		
		
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->

<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_tchr_annu_appct">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">연차 신청하기</h4>
                <button type="button" class="close"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            	
            	<!-- FORM [START] -->
            	<form id="tchr_annu_form">
            	
            	<input type="hidden" name="mem_sno" id="mem_sno" value="<?php echo $tchr_info['MEM_SNO']?>" />
            	<input type="hidden" name="mem_id" id="mem_id" value="<?php echo $tchr_info['MEM_ID']?>" />
            	<input type="hidden" name="mem_nm" id="mem_nm" value="<?php echo $tchr_info['MEM_NM']?>" />
            	<input type="hidden" name="annu_grant_mgmt_sno" id="annu_grant_mgmt_sno" value="<?php echo $annu_grant_mgmt_sno?>" />
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>연차 사용 가능 시작일</span>
                	</span>
                	<input type="text" class="form-control" name="annu_appct_s_date" id="annu_appct_s_date" onchange="edate_calu_days()" value="<?php echo date('Y-m-d')?>">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>연차 사용 가능 종료일</span>
                	</span>
                	<input type="text" class="form-control" name="annu_appct_e_date" id="annu_appct_e_date" onchange="edate_calu_days()" value="<?php echo date('Y-m-d')?>">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>사용일</span>
                	</span>
                	<input type="text" class="form-control" name="annu_days" id="annu_days" readonly>
                </div>
                
            	
            	
            	</form>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" onclick="annu_set_proc();">신청하기</button>
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-default END ] ======================================= -->
	
<!-- ############################## MODAL [ END ] ###################################### -->
	
	
	
</section>


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

function edate_calu_days()
{
	var sDate = $('#annu_appct_s_date').val();
	var eDate = $('#annu_appct_e_date').val();
	
	const getDateDiff = (d1, d2) => {
		const date1 = new Date(d1);
		const date2 = new Date(d2);
		  
		const diffDate = date2.getTime() - date1.getTime();
		  
		return (diffDate / (1000 * 60 * 60 * 24)) + 1; // 밀리세컨 * 초 * 분 * 시 = 일
	}
	
	var day_cnt = getDateDiff(sDate,eDate);
	$('#annu_days').val(day_cnt);
}

function annu_set_form()
{
	if ( $('#annu_grant_mgmt_sno').val() == '' )
	{
		alertToast('error','신청할 연차가 없습니다.');
		return;
	}

	$('#annu_appct_s_date').datepicker('destroy');
	$('#annu_appct_s_date').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
    });
    
    $('#annu_appct_e_date').datepicker('destroy');
	$('#annu_appct_e_date').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
    });
    
    edate_calu_days();
    
    $('#modal_tchr_annu_appct').modal("show");
    
}

function annu_set_proc()
{
	var params = $("#tchr_annu_form").serialize();
    jQuery.ajax({
        url: '/tmanage/ajax_tchr_annu_appct_proc',
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

function annu_cancel(hist_sno)
{
	var params = "hist_sno="+hist_sno;
    jQuery.ajax({
        url: '/tmanage/ajax_tchr_annu_cancel_proc',
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

</script>