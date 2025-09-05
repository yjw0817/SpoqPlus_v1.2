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
			

				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h3 class="panel-title">매출내역 리스트 - 판매매출액</h3>
					</div>
				

					<div class="panel-body">

									
					<div class="clearfix mb20">
						<button type="button" class="btn btn-sm btn-success" onclick="back_salary();">이전으로 돌아가기</button>
					</div>
					
						<table class="table table-bordered table-hover table-striped col-md-12">
							<thead>
								<tr class='text-center'>
									<th style='width:55px'>순번</th>
									<th style='width:150px'>회원명(ID)</th>
									<th style='width:130px'>구분</th>
									<th>판매상품명</th>
									
									<th style='width:100px'>상태</th>
									<th style='width:100px'>수단</th>
									<th style='width:100px'>결제채널</th>
									<th style='width:100px'>결제금액</th>
									<th style='width:100px'>구분</th>
									<th style='width:120px'>사유</th>
									<th style='width:100px'>회원상태</th>
									
									
									<th style='width:80px'>판매강사</th>
									<th style='width:140px'>등록일시</th>
								</tr>
							</thead> 
							<tbody>
								<?php 
								$listCount = count($detail_sarly['sarly_mgmt'][0]['detail']);
								foreach($detail_sarly['sarly_mgmt'][0]['detail'] as $r) :
								    $backColor = "";
								    /*
								     MEM_NM 회원명
								     MEM_ID 회원아이디
								     
								     SELL_EVENT_NM 판매상품명
								     PAYMT_STAT 상태
								     PAYMT_MTHD 수단
								     PAYMT_AMT 결제금액
								     PAYMT_CHNL 결제채널
								     SALES_DV 구분
								     SALES_DV_RSON 구분사유
								     SALES_MEM_STAT 회원상태
								     PTHCR_ID 판매강사_아이디
								     CRE_DATETM 등록일시
								     */
								?>
								<tr style="background-color: <?php echo $backColor ?>;">
									<td class='text-center'><?php echo $listCount?></td>
									<td><?php echo $r['MEM_NM']?>(<?php echo $r['MEM_ID']?>)</td>
									<td class='text-center'><?php echo $sDef['CLAS_DV'][$r['CLAS_DV']]?></td>
									<td><?php echo $r['SELL_EVENT_NM']?></td>
									
									<td class='text-center'><?php echo $sDef['PAYMT_STAT'][$r['PAYMT_STAT']]?></td>
									<td class='text-center'><?php echo $sDef['PAYMT_CHNL'][$r['PAYMT_CHNL']]?></td>
									<td class='text-center'><?php echo $sDef['PAYMT_MTHD'][$r['PAYMT_MTHD']]?></td>
									
									<?php if($r['PAYMT_AMT'] < 0) : ?>
									<td  style="background-color:#f7d5d9; text-align:right;"><?php echo number_format($r['PAYMT_AMT'])?></td>
									<?php else : ?>
									<td style="text-align:right"><?php echo number_format($r['PAYMT_AMT'])?></td>
									<?php endif ; ?>
									
									<td><?php echo $sDef['SALES_DV'][$r['SALES_DV']]?></td>
									<td><?php echo $sDef['SALES_DV_RSON'][$r['SALES_DV_RSON']]?></td>
									<td><?php echo $sDef['SALES_MEM_STAT'][$r['SALES_MEM_STAT']]?></td>
									
									<td><?php echo $r['PTHCR_ID']?></td>
									
									<td><?php echo $r['CRE_DATETM']?></td>
								</tr>
								<?php 
								$listCount--;
								endforeach;
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
                        <!-- PAGZING [END] -->
					</div>
					<!-- CARD FOOTER [END] -->
			
				</div>
			
			</div>
		</div>
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
	
<!-- ############################## MODAL [ END ] ###################################### -->

<form name="form_back_salary" id="form_back_salary" method="post" action="/tmanage/tchr_salary_manage">
	<input type="hidden" name="ss_yy" value="<?php echo $postVar['ss_yy']?>" />
	<input type="hidden" name="ss_mm" value="<?php echo $postVar['ss_mm']?>" />
	<input type="hidden" name="tid" value="<?php echo $postVar['tid']?>" />
</form>	
	
	
</section>


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

function back_salary()
{
	$('#form_back_salary').submit();
}

$('#sdate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$('#edate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$(".ss-input").on("keyup",function(key){
	if(key.keyCode==13) {
		btn_search();
	}
});

// 대분류 선택시 -> 중분류 동적 생성
function cate1_ch()
{
	var cate1 = $('#1rd_cate_cd').val();
	var params = "cate1="+cate1;
	jQuery.ajax({
        url: '/teventmain/ajax_cate2_list',
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
				$('#2rd_cate_cd').empty();
				$('#2rd_cate_cd').append("<option value=''>중분류 선택</option>");
				json_result['cate2'].forEach(function (r,index) {
					$('#2rd_cate_cd').append("<option data-clasdv='" + r['CLAS_DV'] + "' value='" + r['2RD_CATE_CD'] + "'>"+r['CATE_NM']+"</option>");
				});
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
	
	$('#form_search_month_sales_manage').submit();
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