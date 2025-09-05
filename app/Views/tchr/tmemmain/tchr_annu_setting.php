<style>
</style>
<?php
$sDef = SpoqDef();
?>


<!-- Main content -->
<h1 class="page-header"><?php echo $title ?></h1>
<!-- CARD HEADER [END] -->




<div class="panel panel-inverse">
	<div class="panel-heading">
		<h3 class="panel-title">수업추가 내역 리스트 (강사명 : <?php echo $tchr_info['MEM_NM']?>)</h3>
	</div>
		<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
						<!-- TABLE [START] -->
						<table class="table table-bordered table-hover table-striped col-md-12">
							<thead>
								<tr>
									<th style='width:150px; text-align:center'>연차사용가능 시작일</th>
									<th style='width:150px; text-align:center'>연차사용가능 종료일</th>
									<th style='width:150px; text-align:center'>사용가능일</th>
									<th style='width:150px; text-align:center'>사용일</th>
									<th style='width:150px; text-align:center'>남은일</th>
									<th style='width:150px; text-align:center'>등록일시</th>
									<th style="text-align:center">옵션</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($tchr_annu_list as $r) :?>
								<tr>
									<td><?php echo $r['ANNU_GRANT_S_DATE']?></td>
									<td><?php echo $r['ANNU_GRANT_E_DATE']?></td>
									<td><?php echo $r['ANNU_GRANT_DAY']?></td>
									<td><?php echo $r['ANNU_USE_DAY']?></td>
									<td><?php echo $r['ANNU_LEFT_DAY']?></td>
									<td><?php echo $r['CRE_DATETM']?></td>
									<td></td>
								<?php endforeach;?>
							</tbody>
						</table>
						<!-- TABLE [END] -->
                     <div class="card-footer clearfix">
						<!-- BUTTON [START] -->
						<ul class="pagination pagination-sm m-0 float-right">
							<li class="ac-btn"><button type="button" class="btn btn-block btn-success btn-sm" onclick="annu_set_form();">등록하기</button></li>
						</ul>
						
						<!-- BUTTON [END] -->
						<!-- PAGZING [START] -->
                        <!-- PAGZING [END] -->
					</div>
					<!-- CARD FOOTER [END] -->

					</div>
		
			
				</div>
			

	
<!-- ############################## MODAL [ SATRT ] #################################### -->

<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_tchr_annu_setting">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">연차설정 등록하기</h4>
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
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:120px'>연차부여 시작일</span>
                	</span>
                	<input type="text" class="form-control" name="annu_set_s_date" id="annu_set_s_date">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:120px'>연차부여 종료일</span>
                	</span>
                	<input type="text" class="form-control" name="annu_set_e_date" id="annu_set_e_date">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:120px'>연차부여 일수</span>
                	</span>
                	<input type="text" class="form-control" name="annu_set_days" id="annu_set_days">
                </div>
                
            	
            	
            	</form>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" onclick="annu_set_proc();">등록하기</button>
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

function annu_set_form()
{
	$('#annu_set_s_date').datepicker('destroy');
	$('#annu_set_s_date').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
    });
    
    $('#annu_set_e_date').datepicker('destroy');
	$('#annu_set_e_date').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
    });
    
    $('#modal_tchr_annu_setting').modal("show");
    
}

function annu_set_proc()
{
	if ( $('#annu_set_s_date').val() == "" )
	{
		alertToast('error',"연차부여 시작일을 입력하세요");
		return;
	}
	
	if ( $('#annu_set_e_date').val() == "" )
	{
		alertToast('error',"연차부여 시작일을 입력하세요");
		return;
	}
	
	if ( $('#annu_set_days').val() == "" )
	{
		alertToast('error',"연차부여 일수를 입력하세요");
		return;
	}


	var params = $("#tchr_annu_form").serialize();
    jQuery.ajax({
        url: '/tmemmain/ajax_tchr_annu_setting_proc',
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