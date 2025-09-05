<style>
.table th, .table td {
    padding: 0.3rem !important;
    font-size: 0.9rem;
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
						<h3 class="panel-title">보내기 상품 선택하기</h3>
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD FOOTER [START] -->
					<form name="event_manage_form" id="event_manage_form" method="post" action="/teventmain/send_event">
					<input type="hidden" name="day_n" id="day_n" value="<?php echo $search_val['day_n']?>" />
					<div class="card-footer clearfix">
						<!-- BUTTON [START] -->
						<!-- BUTTON [END] -->
					</div>
					<!-- CARD FOOTER [END] -->
					</form>
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
						<!-- TABLE [START] -->
						<table class="table table-bordered table-hover text-nowrap col-md-12">
							<thead>
								<tr>
									<th style='width:50px'>번호</th>
									<th style='width:80px'>대분류</th>
									<th style='width:80px'>이용권</th>
									<th style='width:350px'>상품명</th>
									<th style='width:100px'>이용기간</th>
									<th style='width:100px'>수업횟수</th>
									<th style='width:100px'>판매금액</th>
									<th>옵션</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($event_list as $r) :
								    
    								/**
    								 * 제목 표기 [START]
    								 */
								    $disp_acc_rtrct_dv = $sDef['ACC_RTRCT_DV'][$r['ACC_RTRCT_DV']];
								    $disp_acc = "";
								    
								    $btn_css = "";
								    if ($r['ACC_RTRCT_DV'] == "00") :
								        $btn_css = "bg-success";
								        elseif ($r['ACC_RTRCT_DV'] == "01") :
								        $btn_css = "bg-info";
								        elseif ($r['ACC_RTRCT_DV'] == "99") :
								        $btn_css = "bg-danger";
								    endif;
								    
								    if ($disp_acc_rtrct_dv != '') :
								        $disp_acc = '<span class="badge ' . $btn_css . '">' . $disp_acc_rtrct_dv . '</span>';
								    endif;
								    
								    if ($r['ACC_RTRCT_DV'] == "01") :
    								    $disp_acc_rtrct_mthd = $sDef['ACC_RTRCT_MTHD'][$r['ACC_RTRCT_MTHD']];
    								    $disp_acc .= '<span class="badge bg-warning">' . $disp_acc_rtrct_mthd . '</span>';
								    endif;
								    
								    $disp_sell_event_nm = $disp_acc . $r['SELL_EVENT_NM'];
								    
								    /**
								     * 제목 표기 [END]
								     */
								    
								?>
									<?php if($r['SELL_YN'] == "N") :?>
									<tr class="" style="background-color:#cfecf0">
    									<td></td>
    									<td><?php echo $cate1_nm[$r['1RD_CATE_CD']]?></td>
    									<td><?php echo $cate2_nm[$r['1RD_CATE_CD']][$r['2RD_CATE_CD']]?></td>
    									<td colspan='4'>
											<?php echo disp_depth($r['EVENT_DEPTH'])?>
											<?php echo $disp_sell_event_nm?>
										</td>
										<td><!-- action --></td>
									</tr>	
									<?php else :?>
									
									<?php 
									// 이용중 예약됨 이 있는지에 따라 테이블 색상을 변경한다.
									// 예약됨 : warning
									// 이용중 : success
									
									$my_bg_class = "";
									if ( ( count($event_list_00) + count($event_list_00) ) > 0 )
									{
									    foreach($event_list_00 as $m)
									    {
									        if ($m['BUY_EVENT_SNO'] == $r['SELL_EVENT_SNO']) $my_bg_class = "bg-warning";
									    }
									    
									    foreach($event_list_01 as $m)
									    {
									        if ($m['BUY_EVENT_SNO'] == $r['SELL_EVENT_SNO']) $my_bg_class = "bg-warning";
									    }
									}
									?>
									
									
									
    								<tr class="<?php echo $my_bg_class?>">
    									<td></td>
    									<td><?php echo $cate1_nm[$r['1RD_CATE_CD']]?></td>
    									<td><?php echo $cate2_nm[$r['1RD_CATE_CD']][$r['2RD_CATE_CD']]?></td>
    									<td>
    										<?php echo disp_depth($r['EVENT_DEPTH'])?>
    										<?php echo $disp_sell_event_nm?>
    									</td>
    									<td>
    										<?php echo disp_zeronull($r['USE_PROD'],$sDef['USE_UNIT'][$r['USE_UNIT']])?>
    									</td>
    									<td><?php echo disp_zeronull($r['CLAS_CNT'])?></td>
    									<td><?php echo number_format($r['SELL_AMT'])?></td>
    									<?php if($r['ACC_RTRCT_DV'] == "00") :?>
    									<td><!-- action -->
    										<button type="button" class="btn btn-info btn-xs ac-btn" onclick="buy_send_info('<?php echo $r['SELL_EVENT_SNO']?>');"> 보내기 상품선택</button>
    									</td>
    									<?php else :?>
    									<td><!-- action -->
    										<button type="button" class="btn btn-info btn-xs ac-btn" onclick="buy_send_info('<?php echo $r['SELL_EVENT_SNO']?>');"> 보내기 상품선택</button>
    									</td>
    									<?php endif;?>
    									
    								</tr>
									<?php endif;?>
								<?php endforeach; ?>
							</tbody>
						</table>
						<!-- TABLE [END] -->
					</div>
					<!-- CARD BODY [END] -->
					<!-- CARD FOOTER [START] -->
					<div class="card-footer clearfix">
						<!-- BUTTON [START] -->
						
						<!-- BUTTON [END] -->
						<!-- PAGZING [START] -->
                        <?=$pager?>
                        <!-- PAGZING [END] -->
					</div>
					<!-- CARD FOOTER [END] -->
			
				</div>
			
			</div>
		</div>
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->

<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_send_info_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">상품 보내기 설정</h4>
                <button type="button" class="close"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
                <table class="table table-bordered table-hover text-nowrap col-md-12">
					<tbody>
						<tr>
							<td>보내기 상품명</td>
							<td id='send_event_nm'></td>
							<td>입장제한</td>
							<td id='send_acc_rtrct'></td>
						</tr>
						<tr>
							<td>이용기간</td>
							<td id='send_prod'></td>
							<td>수업횟수</td>
							<td id='send_clas_cnt'></td>
						</tr>
						<tr>
							<td>휴회횟수</td>
							<td id='send_domcy_cnt'></td>
							<td>휴회일</td>
							<td id='send_domcy_day'></td>
						</tr>
						<tr>
							<td>판매금액</td>
							<td colspan="3" id='send_amt'></td>
						</tr>
					</tbody>
				</table>
            	
            	<form name="form_send_set" id="form_send_set" method="post" action="/teventmain/send_event_proc">
            	<input type="hidden" name="send_sell_event_sno" id="send_sell_event_sno" />
            	<input type="hidden" name="send_mem_sno" id="send_mem_sno" value="<?php echo $mem_sno?>" />
            	<div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>보내기 금액</span>
                	</span>
                	<input type="text" class="form-control" name="set_send_amt" id="set_send_amt">
                	
                	<span class="input-group-append disp_serv_cnt">
                		<span class="input-group-text input-readonly disp_serv_cnt" style='width:100px'>서비스 수업</span>
                	</span>
                	<input type="text" class="form-control disp_serv_cnt" name="set_send_serv_clas_cnt" id="set_send_serv_clas_cnt" value="0">
                	
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly disp_serv_day" style='width:100px'>서비스 일수</span>
                	</span>
                	<input type="text" class="form-control disp_serv_day" name="set_send_serv_day" id="set_send_serv_day" value="0">
                	
                </div>	
                <div class="input-group input-group-sm mb-1">
                	
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>휴회횟수</span>
                	</span>
                	<input type="text" class="form-control" name="set_send_domcy_cnt" id="set_send_domcy_cnt">
                	
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>휴회일</span>
                	</span>
                	<input type="text" class="form-control" name="set_send_domcy_day" id="set_send_domcy_day">
                	
                </div>
                <div class="input-group input-group-sm mb-1">
                	
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>마감일 설정</span>
                	</span>
                	<input type="text" class="" style="width:30px" name="set_end_day" id="set_end_day" value="7"> *최대 7일까지 설정이 가능합니다.
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:100px'>판매강사</span>
                	</span>
                	<select class="select2 form-control" style="width: 250px;" name="set_ptchr_id_nm" id="set_ptchr_id_nm">
                		<option value="">강사 선택</option>
                	<?php foreach ($tchr_list as $r) : ?>
						<option value="<?php echo $r['MEM_ID']?>|<?php echo $r['MEM_NM']?>">[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?> </option>
					<?php endforeach; ?>
					</select>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly disp_serv_cnt" style='width:100px'>수업강사</span>
                	</span>
                	<span class="disp_serv_cnt">
                	<select class="select2 form-control" style="width: 250px;" name="set_stchr_id_nm" id="set_stchr_id_nm">
                		<option value="">강사 선택</option>
                	<?php foreach ($tchr_list as $r) : ?>
						<option value="<?php echo $r['MEM_ID']?>|<?php echo $r['MEM_NM']?>">[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?> </option>
					<?php endforeach; ?>
					</select>
					</span>
                </div>
                
                </form>
                
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default"  data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-sm btn-success" onclick="btn_send_submit();">보내기</button>
            </div>
        </div>
    </div>
</div>
<form name="frm_event_buy_info" id="frm_event_buy_info" method="post" action="/teventmain/event_buy_info">
	<input type="hidden" name="send_memsno" id="send_memsno" />
	<input type="hidden" name="send_esno" id="send_esno" />
</form>
<!-- ============================= [ modal-default END ] ======================================= -->


<!-- ############################## MODAL [ END ] ###################################### -->
	
	
	
</section>


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

// 1일1회입장 안보이기
$('#form_btn_day_n_n').click(function(){
	$('#day_n').val('N');
	$('#event_manage_form').submit();
});

// 1일1회입장 보이기
$('#form_btn_day_n_y').click(function(){
	$('#day_n').val('Y');
	$('#event_manage_form').submit();
});

function btn_send_submit()
{
	$('#form_send_set').submit();
}

// 보내기 상품 선택
function buy_send_info(esno)
{
	var params = "esno="+esno;
	jQuery.ajax({
        url: '/teventmain/ajax_send_event_info',
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
				var ev = json_result['event_info'];
				$('#send_event_nm').text(ev.SELL_EVENT_NM);
				$('#send_acc_rtrct').text(ev.ACC_RTRCT_DV_NM + " (" + ev.ACC_RTRCT_MTHD_NM + ")");
				$('#send_prod').text(ev.USE_PROD_NM);
				$('#send_clas_cnt').text(ev.CLAS_CNT);
				$('#send_domcy_cnt').text(ev.DOMCY_CNT);
				$('#send_domcy_day').text(ev.DOMCY_DAY);
				
				$('#set_send_domcy_cnt').val(ev.DOMCY_CNT);
				$('#set_send_domcy_day').val(ev.DOMCY_DAY);
				
				if ( ev.DOMCY_POSS_EVENT_YN == 'Y' )
				{
					$('#set_send_domcy_cnt').attr('readonly',false);
					$('#set_send_domcy_day').attr('readonly',false);
				} else 
				{
					$('#set_send_domcy_cnt').attr('readonly',true);
					$('#set_send_domcy_day').attr('readonly',true);
				}
				
				$('#send_amt').text(ev.SELL_AMT.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				$('#set_send_amt').val( currency_cost(ev.SELL_AMT) );
				
				if(ev.CLAS_DV == '21' || ev.CLAS_DV == '22')
				{
					$('.disp_serv_cnt').show();
					$('.disp_serv_day').hide();
				} else 
				{
					$('.disp_serv_cnt').hide();
					$('.disp_serv_day').show();
				}
				
				$("#send_sell_event_sno").val(esno);
				$("#modal_send_info_form").modal("show");
				
			} else 
			{
				alertToast('error','검색된 결과가 없습니다.');
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

function currency_cost(cost)
{
	var d_amt = onlyNum( cost );
	return currencyNum(d_amt);
}


// 보내기 금액
$('#set_send_amt').keyup(function(){
	var d_amt = onlyNum( $('#set_send_amt').val() );
	$('#set_send_amt').val(currencyNum(d_amt));
});




$("#top_search").on("keyup",function(key){
		if(key.keyCode==13) {
			$("#btn_search_nm").trigger("click");
		}
	});

// 회원명 검색 버튼 클릭
$('#btn_search_nm').click(function(){

	var esno = $('#search_esno').val();
	var sname = $('#search_mem_nm').val();
	var params = "sname="+sname;
	
	$('#search_mem_table > tbody > tr').remove();
	
	jQuery.ajax({
        url: '/teventmain/ajax_event_buy_search_nm',
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
				json_result['search_mem_list'].forEach(function (r,index) {
					var addTr = "<tr>";
					addTr += "<td>" + r['MEM_STAT_NM'] + "</td>";
					addTr += "<td>" + r['MEM_NM'] + "</td>";
					addTr += "<td>" + r['MEM_ID'] + "</td>";
					addTr += "<td>" + r['MEM_TELNO'] + "</td>";
					addTr += "<td>" + r['BTHDAY'] + "</td>";
					addTr += "<td>" + r['MEM_GENDR_NM'] + "</td>";
					addTr += "<td><button type=\"button\" class=\"btn btn-info btn-xs ac-btn\" onclick=\"buy_user_select('"+ r['MEM_SNO'] +"','"+esno+"');\">선택</button></td>";
					addTr += "</tr>";
					
					$('#search_mem_table > tbody:last').append(addTr);
				});
			} else 
			{
				alertToast('error','검색된 결과가 없습니다.');
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

function buy_user_select(memsno,esno)
{
	$('#send_memsno').val(memsno);
	$('#send_esno').val(esno);
	$('#frm_event_buy_info').submit();
}

// ===================== Modal Script [ START ] ===========================

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