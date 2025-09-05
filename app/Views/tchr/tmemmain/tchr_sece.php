<style>
.table th, .table td {
    padding: 0.3rem !important;
    font-size: 0.9rem;
}

.table-bordered th, .table-bordered td {
    border: 1px solid #a3a3a3;
}

table.table-hover tbody tr:hover {
    background-color: #81b1eb !important; 
}

</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			
				<div class="card card-primary">
					<!-- CARD HEADER [START] -->
					<div class="page-header">
						<h3 class="panel-title">강사 퇴사처리 하기</h3>
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD Search [START] -->
					<div class="card-footer clearfix">
						
    		        </div>

					<!-- CARD BODY [START] -->
					<div class="panel-body">
						<!-- TABLE [START] -->
						<!-- TABLE [START] -->
						<table class="table table-bordered table-hover table-striped col-md-12">
							<thead>
								<tr class='text-center'>
									<th style='width:100px'>수업강사이관</th>
									<th style='width:75px'>회원명</th>
									<th style='width:115px'>구매일시</th>
									
									<th style='width:80px'>판매상태</th>
									<th>판매상품명</th>
									
									<th style='width:60px'>기간</th>
									<th style='width:80px'>시작일</th>
									<th style='width:115px'>종료일</th>
									<th style='width:70px'>수업</th>
									<th style='width:70px'>휴회일</th>
									<th style='width:70px'>휴회횟수</th>
									<th style='width:100px'>판매금액</th>
									<th style='width:100px'>결제금액</th>
									<th style='width:100px'>미수금액</th>
									
									<th style='width:100px'>수업강사</th>
									<th style='width:100px'>판매강사</th>
									
								</tr>
							</thead>
							<tbody>
								<?php 
								if (count($event_list) > 0) :
								foreach ($event_list as $r) :
								if ($r['EVENT_STAT'] == '00') $backColor = "#cfecf0";
								if ($r['EVENT_STAT'] == '01') $backColor = "";
								if ($r['EVENT_STAT'] == '99') $backColor = "#f7d5d9";
								?>
								
								<tr style="background-color: <?php echo $backColor ?>;">
									<td>
										<button type="button" class="btn btn-info btn-xs" onclick="change_stchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['STCHR_ID'] ?>','<?php echo $r['CLAS_DV']?>');">강사이관</button>
									</td>
									<td>
										<?php echo $r['MEM_NM']?>
									</td>
									<td><?php echo substr($r['BUY_DATETM'],0,16)?></td>
									<td><?php echo $sDef['EVENT_STAT_RSON'][$r['EVENT_STAT_RSON']]?></td>
									<td>
										<?php if ($r['EVENT_STAT_RSON'] == "51" || $r['EVENT_STAT_RSON'] == "81" || $r['EVENT_STAT_RSON'] == "61" || $r['EVENT_STAT_RSON'] == "62") :?>
										<i class="fa fa-list" onclick="more_info_show('<?php echo $r['BUY_EVENT_SNO']?>','<?php echo $r['EVENT_STAT_RSON']?>');"></i> 
										<?php endif; ?>
										<?php 	  
											  echo "<small class='badge bg-success'>".$sDef['ACC_RTRCT_MTHD'][$r['ACC_RTRCT_MTHD']]."</small>";
										?>
										<?php echo $r['SELL_EVENT_NM']?><!--  (<?php echo $r['BUY_EVENT_SNO']?>) -->
										
										<?php if($r['LOCKR_SET'] == "Y") : 
												if ($r['LOCKR_NO'] != '') :
													echo disp_locker($r['LOCKR_KND'],$r['LOCKR_GENDR_SET'],$r['LOCKR_NO']);
												else :
												    if ($r['EVENT_STAT'] != '99') :
										?>
												<small class='badge bg-danger' style='cursor:pointer' onclick="lockr_select('<?php echo $r['MEM_SNO']?>','<?php echo $r['BUY_EVENT_SNO']?>','<?php echo $r['LOCKR_KND']?>','<?php echo $mem_info['MEM_GENDR']?>');">선택하기</small>
										<?php
										            endif;
												endif ;
											  endif;
										?>
										
									</td>
									
									<td style="text-align:right"><?php echo disp_produnit($r['USE_PROD'],$r['USE_UNIT'])?></td>
									<td><span id="<?php echo "exr_s_date_".$r['BUY_EVENT_SNO']?>">
									
									<?php if($r['EVENT_STAT'] == "01" && ($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") ) : ?>
									<button type='button' class='btn btn-info btn-xs' onclick="pt_use('<?php echo $r['STCHR_ID']?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">이용시작</button>
									<?php endif; ?>
										<?php echo $r['EXR_S_DATE']?>
									</span></td>
									<td><span id="<?php echo "exr_e_date_".$r['BUY_EVENT_SNO']?>"><?php echo $r['EXR_E_DATE']?></span><?php echo disp_add_cnt($r['ADD_SRVC_EXR_DAY'])?></td>
									
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
									<td class='text-center' style='font-size:0.8rem'>불가능</td>
									<td class='text-center' style='font-size:0.8rem'>불가능</td>
									<?php endif ;?>
									<!-- ############### 휴회 영역 ################# -->
									
									<td style="text-align:right"><?php echo number_format($r['REAL_SELL_AMT']) ?></td>
									<td style="text-align:right"><?php echo number_format($r['BUY_AMT']) ?></td>
									<td style="text-align:right">
									<?php if ($r['RECVB_AMT'] == 0) :?>
										<?php echo number_format($r['RECVB_AMT']) ?>
									<?php else :?>
										<button type='button' class='btn btn-danger btn-xs' onclick="misu_select('<?php echo $r['MEM_SNO']?>','<?php echo $r['BUY_EVENT_SNO']?>');">
										<?php echo number_format($r['RECVB_AMT']) ?>
										</button>
									<?php endif ;?>
									</td>
									
									<td class='text-center'><?php echo $r['STCHR_NM'] ?></td>
									<td class='text-center'><?php echo $r['PTCHR_NM'] ?></td>
								</tr>
								
								
								<?php endforeach;
								else :
								?>
								<tr style='height:180px;'>
									<td class='text-center' colspan="16">
										<div style='margin-top:33px; margin-bottom:33px;'>담당하고 있는 이용중, 예약됨 상품에 대한 수업강사 이전이 완료 되었습니다. 퇴사처리가 가능합니다.</div>
										<div>
										
											<form name="form_sece" id="form_sece">
											<input type="hidden" name="mem_id" id="mem_id" value="<?php echo $mem_id?>" />
											<div class="form-group">
                                                <label for="inputName">퇴사일 지정 :</label>
                                                <input type="text" style='width:80px;' id="sece_date" name="sece_date" value="<?php echo date('Y-m-d')?>" readonly />
                                            </div>
                                            
                                            <div class="form-group">
                                                <button type="button" class="btn btn-danger btn-sm" onclick="sece_last_confirm();">최종 퇴사처리 하기</button>
                                            </div>
											</form>
										</div>
									</td>
								</tr>
								<?php
								endif;
								?>
							</tbody>
						</table>
						<!-- TABLE [END] -->
						<!-- TABLE [END] -->
					</div>
					<!-- CARD BODY [END] -->
					<!-- CARD FOOTER [START] -->
					<div class="card-footer clearfix">
						<!-- BUTTON [START] -->
						
						<!-- BUTTON [END] -->
						<!-- PAGZING [START] -->
                        <?php //echo $pager?>
                        <!-- PAGZING [END] -->
					</div>
					<!-- CARD FOOTER [END] -->
			
				</div>
			
			</div>
		</div>
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->

<!-- ============================= [ modal-sm START 수업강사 변경 ] ============================================ -->
<div class="modal fade" id="modal_pop_stchr">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">수업강사 변경</h4>
                <button type="button" class="close"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
            	<input type="hidden" name="stchr_buy_sno" id="stchr_buy_sno" />
            	<div class="input-group input-group-sm mb-1">
                	<select class="select2 form-control" style="width: 250px;" name="ch_stchr_id" id="ch_stchr_id">
                		<option>강사 선택</option>
                	<?php foreach ($tchr_list as $r) : ?>
						<option value="<?php echo $r['MEM_ID']?>">[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?> </option>
					<?php endforeach; ?>
					</select>
                </div>
            	
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default"  data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-sm btn-success" onclick="btn_stchr_submit();">변경하기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-sm END ] ============================================== -->
	
<!-- ############################## MODAL [ END ] ###################################### -->
	
	
	
</section>


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})


$('#sece_date').datepicker({
    autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
    language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});


function sece_last_confirm()
{
	ToastConfirm.fire({
        icon: "warning",
        title: "  확인 메세지",
        html: "<font color='#000000' >최종 강사 퇴사처리를 진행하시겠습니까?</font><br /><font color='#ff0000'>처리되면 되돌릴 수 없습니다.</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		var params = $("#form_sece").serialize();
            jQuery.ajax({
                url: '/tmemmain/ajax_tchr_sece_proc',
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
        				alert('퇴사처리가 완료 되었습니다.');
        				location.href="/tmemmain/tchr_manage";
        			} 
                }
            }).done((res) => {
            	// 통신 성공시
            	console.log('통신성공');
            }).fail((error) => {
            	// 통신 실패시
            	console.log('통신실패');
            	//alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
        		//location.href='/tlogin';
        		return;
            });
    	}
    });
}


// 수업강사 변경
function change_stchr(buy_sno,event_stat,mem_id,clas_dv)
{

	if (event_stat == '99')
	{
		alertToast('error','종료된 상품은 수업강사를 변경 할 수 없습니다.');
		return;
	}

	if (clas_dv == '21' || clas_dv == '22')
	{
		$('#ch_stchr_id').val(mem_id).trigger('change');
    	$('#stchr_buy_sno').val(buy_sno);
    	$('#modal_pop_stchr').modal("show");
	} else 
	{
		alertToast('error','수업상품이 아닌 상품은 수업강사를 지정 할 수 없습니다.');
		return;
	}
	
}

// 수업강사 변경 처리
function btn_stchr_submit()
{
	var fc_stchr_buy_sno = $('#stchr_buy_sno').val();
	var fc_ch_stchr_id = $('#ch_stchr_id').val();
	
	var params = "fc_stchr_buy_sno="+fc_stchr_buy_sno+"&fc_ch_stchr_id="+fc_ch_stchr_id;
	jQuery.ajax({
        url: '/ttotalmain/ajax_info_mem_stchr_proc',
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
			console.log(json_result);
			if (json_result['result'] == 'true')
			{
				location.reload();
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