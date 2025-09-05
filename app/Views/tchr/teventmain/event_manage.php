<style>
</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content --> 
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<!-- Main content -->
				<h1 class="page-header"><?php echo $title ?></h1>
				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">상품 리스트</h4>
						<div class="panel-heading-btn">
						</div>
					</div>
						<!-- CARD BODY [START] -->
					<div class="panel-body table-responsive">
									<!-- CARD HEADER [END] -->
									
									<!-- CARD FOOTER [START] -->
									<form name="event_manage_form" id="event_manage_form" method="post" action="/teventmain/event_manage">
					<input type="hidden" name="day_n" id="day_n" value="<?php echo $search_val['day_n']?>" />
					<input type="hidden" name="1rd_cd" id="1rd_cd" value="<?php echo $search_val['1rd_cd']?>" />
					<input type="hidden" name="2rd_cd" id="2rd_cd" value="<?php echo $search_val['2rd_cd']?>" />
					
					<div class="card-footer clearfix">
						
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<ul class="pagination m-0 float-left">
							<?php 
							$btn1_class = "btn-default";
							if ($search_val['1rd_cd'] == "") $btn1_class = "btn-success";
							?>
							<li class="ac-btn"><button type="button" class="btn btn-block btn-xs <?php echo $btn1_class?>" onclick="form_sch_cate1('');">전체</button></li>
							<?php foreach ($cate1_nm as $c1 => $c1nm) :
							$btn1_class = "btn-default";
							if ($search_val['1rd_cd'] == $c1) $btn1_class = "btn-success";
							?>
								<li class="ac-btn"><button type="button" class="btn btn-block btn-xs <?php echo $btn1_class?>" onclick="form_sch_cate1('<?php echo $c1?>');"><?php echo $c1nm?></button></li>
							<?php endforeach; ?>
							</ul>
							
							<!-- BUTTON [START] -->
							<ul class="pagination pagination-sm m-0 float-right">
								<?php if($search_val['day_n'] == "Y") :?>
								<li class="ac-btn"><button type="button" class="btn btn-block btn-danger btn-xs" id="form_btn_day_n_n">1일1회입장 안보이기</button></li>
								<?php else :?>
								<li class="ac-btn"><button type="button" class="btn btn-block btn-success btn-xs" id="form_btn_day_n_y">1일1회입장 보이기</button></li>
								<?php endif;?>
							</ul>
							<!-- BUTTON [END] -->
						</div>
					</div>
					
					<div class="row" style='margin-top:5px;'>
						<div class="col-md-12">
							<ul class="pagination m-0 float-left">
							<?php 
							$btn2_class = "btn-default";
							if ($search_val['2rd_cd'] == "") $btn2_class = "btn-info";
							?>
							<li class="ac-btn"><button type="button" class="btn btn-block btn-xs <?php echo $btn2_class?>" onclick="form_sch_cate2('');">전체</button></li>
							<?php
							if ($search_val['1rd_cd'] != '') :
								if (isset($cate2_nm[$search_val['1rd_cd']])) :
									foreach ($cate2_nm[$search_val['1rd_cd']] as $c2 => $c2nm) :
									$btn2_class = "btn-default";
									if ($search_val['2rd_cd'] == $c2) $btn2_class = "btn-info";
									?>
										<li class="ac-btn"><button type="button" class="btn btn-block btn-xs <?php echo $btn2_class?>" onclick="form_sch_cate2('<?php echo $c2?>');"><?php echo $c2nm?></button></li>
									<?php 
									endforeach;
								endif;
							endif;
							?>
							</ul>
						</div>
					</div>
					
					<!-- CARD FOOTER [END] -->
					</form>
					
					<!-- CARD FOOTER [START] -->
					<div class="card-footer clearfix">
						<!-- BUTTON [START] -->
						<ul class="pagination pagination-sm m-0 float-right">
							<li class="ac-btn"><button type="button" class="btn btn-block btn-primary btn-sm" onclick="btn_create_event();">기본 상품만들기</button></li>
						</ul>
						
						<!-- BUTTON [END] -->
						<!-- PAGZING [START] -->
						<?=$pager?>
						<!-- PAGZING [END] -->
					</div>
					<!-- CARD FOOTER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
						<!-- TABLE [START] -->
						<table class="table table-bordered table-hover table-striped text-nowrap col-md-12">
							<thead>
								<tr class='text-center'>
									<th style='width:80px'>대분류</th>
									<th style='width:80px'>중분류</th>
									<th style='width:350px'>상품명</th>
									<th style='width:100px'>이용기간</th>
									<th style='width:100px'>수업횟수</th>
									<th style='width:100px'>판매금액</th>
									<th>비고</th>
									<th style='width:100px'>휴회횟수</th>
									<th style='width:100px'>휴회일수</th>
									<th style='width:200px'>판매기간</th>
									<th style='width:60px'>설정</th>
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
										$disp_acc = '<span class="badge ' . $btn_css . '" style="font-weight:bold">' . $disp_acc_rtrct_dv . '</span>';
									endif;
									
									if ($r['ACC_RTRCT_DV'] == "01") :
										$disp_acc_rtrct_mthd = $sDef['ACC_RTRCT_MTHD'][$r['ACC_RTRCT_MTHD']];
										$disp_acc .= '<span class="badge bg-yellow" style="color:black; font-weight:bold">' . $disp_acc_rtrct_mthd . '</span>';
									endif;
									
									$disp_sell_event_nm = $disp_acc . $r['SELL_EVENT_NM'];
									
									/**
									 * 제목 표기 [END]
									 */
									
								?>
									<?php if($r['SELL_YN'] == "N") :?>
									<tr class="" style="background-color:#cfecf0">
										<td><?php echo $cate1_nm[$r['1RD_CATE_CD']]?></td>
										<td><?php echo $cate2_nm[$r['1RD_CATE_CD']][$r['2RD_CATE_CD']]?></td>
										<td colspan='4'>
											<?php echo disp_depth($r['EVENT_DEPTH'])?>
											<!-- <button type="button" class="form-control btn btn-secondary btn-xs ac-btn" style="height: calc(1.5rem) !important;"><?php echo $disp_sell_event_nm?></button>-->
											<?php echo $disp_sell_event_nm?>
											( <?php echo $r['SELL_EVENT_SNO']?> )
										</td>
										<td>
											<button type="button" class="btn btn-success btn-xs ac-btn" onclick="add_prod('<?php echo $r['SELL_EVENT_SNO']?>');">+다른 개월또는 횟수 만들기</button>
										</td>
										<td></td>
										<td></td>
										<td class='text-center'>
										</td>
										<td></td>
									</tr>	
									<?php else :?>
									
									<?php 
									$disp_mem_disp_yn = "";
									if ($r['MEM_DISP_YN'] == "N") $disp_mem_disp_yn = "bg-gray";
									?>
									
									<tr class="<?php echo $disp_mem_disp_yn?>">
										<td><?php echo $cate1_nm[$r['1RD_CATE_CD']]?></td>
										<td><?php echo $cate2_nm[$r['1RD_CATE_CD']][$r['2RD_CATE_CD']]?></td>
										<td>
											<?php echo disp_depth($r['EVENT_DEPTH'])?>
											<?php echo $disp_sell_event_nm?>
											( <?php echo $r['SELL_EVENT_SNO']?> )
										</td>
										<td>
											<?php echo disp_zeronull($r['USE_PROD'],$sDef['USE_UNIT'][$r['USE_UNIT']])?>
										</td>
										<td><?php echo disp_zeronull($r['CLAS_CNT'])?></td>
										<td><?php echo number_format($r['SELL_AMT'])?></td>
										<?php if($r['ACC_RTRCT_DV'] == "00") :?>
										<td><button type="button" class="btn btn-info btn-xs ac-btn" style="margin-left:15px" onclick="add_acc('<?php echo $r['SELL_EVENT_SNO']?>');">+1일1회입장 만들기</button></td>
										<?php else :?>
										<td><button type="button" class="btn btn-default btn-xs ac-btn" style="margin-left:15px" disabled >+1일1회입장</button></td>
										<?php endif;?>
										
										<td class='text-center'><?php echo $r['DOMCY_CNT']?></td>
										<td class='text-center'><?php echo $r['DOMCY_DAY']?></td>
										
										<td class='text-center'>
										<?php if($r['SELL_S_DATE'] == "") :?>
										무제한
										<?php else :?>
										<?php echo $r['SELL_S_DATE']?> ~ <?php echo $r['SELL_E_DATE']?>
										<?php endif;?>
										</td>
										<td class='text-center'>
										<button type="button" class="btn btn-warning btn-xs ac-btn" onclick="sell_event_setting('<?php echo $r['SELL_EVENT_SNO']?>');">설정</button>
										</td>
										
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
						<ul class="pagination pagination-sm m-0 float-right">
							<li class="ac-btn"><button type="button" class="btn btn-block btn-primary btn-sm" onclick="btn_create_event();">기본 상품만들기</button></li>
						</ul>
						
						<!-- BUTTON [END] -->
						<!-- PAGZING [START] -->
						<?=$pager?>
						<!-- PAGZING [END] -->
					</div>
					<!-- CARD FOOTER [END] -->

				</div>
							
					
				<!-- ############################## MODAL [ SATRT ] #################################### -->

				<!-- ============================= [ modal-sm START 운동시작일 변경 ] ============================================ -->
				<div class="modal fade" id="modal_sell_setting">
					<div class="modal-dialog modal-sm">
						<form id="form_sell_setting">
						<input type="hidden" name="sell_event_sno" id="modal_sell_event_sno" />        
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">판매기간 변경</h4>
								<button type="button" class="close"  data-bs-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="input-group input-group-sm mb-1">
									<span class="input-group-append">
										<span class="input-group-text" style='width:150px'>판매시작일</span>
									</span>
									<input type="text" class="form-control" name="sell_s_date" id="modal_sell_s_date">
								</div>
								<div class="input-group input-group-sm mb-1">
									<span class="input-group-append">
										<span class="input-group-text" style='width:150px'>판매종료일</span>
									</span>
									<input type="text" class="form-control" name="sell_e_date" id="modal_sell_e_date">
								</div>
								
								<!-- [공개여부] [START] -->
								<div style='margin-top:4px;margin-left:5px;'>
									<div class="icheck-primary d-inline">
										<input type="radio" id="mem_disp_yn_y" name="mem_disp_yn" value="Y">
										<label for="mem_disp_yn_y">
											<small>공개</small>
										</label>
									</div>
									<div class="icheck-primary d-inline">
										<input type="radio" id="mem_disp_yn_n" name="mem_disp_yn" value="N">
										<label for="mem_disp_yn_n">
											<small>비공개</small>
										</label>
									</div>
								</div>
								<!-- [공개여부] [END] -->
							</div>
							
							<div class="modal-footer justify-content-between">
								<button type="button" class="btn btn-sm btn-default"  data-bs-dismiss="modal">닫기</button>
								<button type="button" class="btn btn-sm btn-success" onclick="btn_change_setting_submit();">변경하기</button>
							</div>
						</div>
						</form>
					</div>
				</div>
				<!-- ============================= [ modal-sm END ] ============================================== -->

				<!-- ############################## MODAL [ END ] ###################################### -->
					
			</div>
		</div>
	</div>
</section>


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})



function sell_event_setting(sell_sno)
{
	var params = "sell_event_sno="+sell_sno;
	jQuery.ajax({
        url: '/teventmain/ajax_event_setting_info',
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
				$('#modal_sell_s_date').val(json_result['info']['SELL_S_DATE']);
				$('#modal_sell_e_date').val(json_result['info']['SELL_E_DATE']);
				
				$('#modal_sell_s_date').datepicker({
                    autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
                    language : "ko",	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
                });
                
                $('#modal_sell_e_date').datepicker({
                    autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
                    language : "ko",	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
                });
				
				if (json_result['info']['MEM_DISP_YN'] == 'Y')
				{
					$('#mem_disp_yn_y').prop('checked',true);
				} else 
				{
					$('#mem_disp_yn_n').prop('checked',true);
				}
				
				$('#modal_sell_event_sno').val(sell_sno);
				$('#modal_sell_setting').modal("show");
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

function btn_change_setting_submit()
{
	var params = $("#form_sell_setting").serialize();
    jQuery.ajax({
        url: '/teventmain/ajax_event_setting_proc',
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

// 양식 제출 확인 오류 메세지를 막기 위한 처리 방법 ( 임시 조치 ?? )
if(window.history.replaceState){
	window.history.replaceState(null, null, window.location.href);
}

$('#form_btn_day_n_n').click(function(){
	$('#day_n').val('N');
	$('#event_manage_form').submit();
});

$('#form_btn_day_n_y').click(function(){
	$('#day_n').val('Y');
	$('#event_manage_form').submit();
});

function form_sch_cate1(cate_code)
{
	$('#1rd_cd').val(cate_code);
	$('#2rd_cd').val('');
	$('#event_manage_form').submit();
}

function form_sch_cate2(cate_code)
{
	$('#2rd_cd').val(cate_code);
	$('#event_manage_form').submit();
}

function btn_create_event()
{
	location.href="/teventmain/create_event";
}


function add_prod(sno)
{
	location.href="/teventmain/create_event_add_prod/"+sno;
}

function add_acc(sno)
{
	location.href="/teventmain/create_event_add_acc/"+sno;
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