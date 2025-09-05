<style>
th.day
{
    padding:13px;
}

td.day
{
    padding:13px;
}
.datepicker 
{
    top:50px;
    text-align:center;
    width:100% !important;
    left:0px !important;
}

.datepicker.datepicker-days
{
    width:100% !important;
}


.overlay {
  background: #fff;
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  transition: all 600ms cubic-bezier(0.86, 0, 0.07, 1);
  top: 100%;
  position: fixed;
  left: 0;
  text-align: left;
  .header {
    padding:20px;
    border-bottom: 1px solid #ddd;
    font: 300 24px Lato;
    position: relative;
    }
  .body {
    padding: 20px;
    font: 300 16px Lato;
  }
}

.content.modal-open .overlay {
  top: 55px;
}

</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
	
	
	
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info" style='margin:5px'>
                <div class="inner">
                <h3><?php echo $poss_domcy['day']?><sup style="font-size: 20px">일</sup></h3>
                <p>휴회가능일</p>
                </div>
                <div class="icon">
                <i class="fas fa-shopping-cart"></i>
                </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning" style='margin:5px'>
                <div class="inner">
                <h3><?php echo $poss_domcy['cnt']?><sup style="font-size: 20px">회</sup></h3>
                <p>휴회가능횟수</p>
                </div>
                <div class="icon">
                <i class="ion ion-stats-bars"></i>
                </div>
                </div>
            </div>
            
        </div>
        
        <button type="button" class='btn btn-block btn-success btn-sm p-3 bottom-menu'>휴회신청하기</button>	
	
		<div class="row" style='margin-top:20px'>
			<div class="col-md-12">
                
                <div class="card">
					<!-- CARD HEADER [START] -->
					<div class="card-header">
						<h3 class="card-title">휴회신청 리스트</h3>
					</div>
					<!-- CARD HEADER [END] -->
					
					<div class="card-body card-table">
						<table class="table table-bordered table-hover col-md-12">
        					<thead>
        						<tr class='text-center bg-olive'>
        							<th>상태</th>
        							<th>시작일</th>
        							<th>종료일</th>
        							<th>사용일</th>
        							<th>신청일</th>
        						</tr>
        					</thead>
        					<tbody>
        					<?php foreach($domcy_list as $r) :?>
        						<tr class='text-center'>
        							<td><?php echo $sDef['DOMCY_MGMT_STAT'][$r['DOMCY_MGMT_STAT']]?></td>
        							<td><?php echo $r['DOMCY_S_DATE']?></td>
        							<td><?php echo $r['DOMCY_E_DATE']?></td>
        							<td><?php echo $r['DOMCY_USE_DAY']?></td>
        							<td><?php echo substr($r['CRE_DATETM'],0,10)?></td>
        						</tr>
        					<?php endforeach;?>
        					</tbody>
                        </table>
					</div>
				</div>
                			
			</div>
		</div>
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-default END ] ======================================= -->
<!-- ############################## MODAL [ END ] ###################################### -->

<div class="overlay" >
    <div class="row">
    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area">
                <button type="button" class="close" id="bottom-menu-close" style="margin-right:10px;margin-top:5px;">&times;</button>
                <br />
                <div class='bottom-title text-center'>휴회신청</div>
                <div class='bottom-content' style='margin-top:15px;'>
                
                    <div class="card card-success">
                    <div class="card-body">
                    <input class="form-control form-control-lg" type="text" name="domcy_acppt_i_sdate" id="domcy_acppt_i_sdate" placeholder="휴회시작일">
                    <br>
                    <input class="form-control form-control-lg" type="text" name="domcy_acppt_i_cnt" id="domcy_acppt_i_cnt" placeholder="휴회 신청일수"  onkeyup="daycnt_calu_date();">
                    <br>
                    <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px;height:45px;font-size:18px;'>휴회 종료일</span>
                	</span>
                	<input type="text" class="form-control" id="domcy_acppt_e_sdate" style='height:45px;font-size:18px;' readonly>
                	</div>
                    
                    </div>
                    
                    </div>
                    
                    <button type="button" class='btn btn-block btn-success btn-sm p-3 bottom-menu-submit'>휴회신청 등록하기</button>
                
                </div>
            </div>
    	</div>
    </div>
</div>

<form name="form_domcy" id="form_domcy" method="post" action="/ttotalmain/ajax_domcy_acppt_proc">
	<input type="hidden" name="fc_domcy_cnt" id="fc_domcy_cnt" value="<?php echo $poss_domcy['cnt']?>" />
	<input type="hidden" name="fc_domcy_day" id="fc_domcy_day" value="<?php echo $poss_domcy['day']?>" />
	<input type="hidden" name="fc_domcy_buy_sno" id="fc_domcy_aply_buy_sno" value="<?php echo $poss_domcy['buy_event_sno']?>" />
	<input type="hidden" name="fc_domcy_mem_sno" id="fc_domcy_mem_sno" value="<?php echo $_SESSION['user_sno']?>" />
	<input type="hidden" name="fc_domcy_s_date" id="fc_domcy_s_date" />
	<input type="hidden" name="fc_domcy_use_day" id="fc_domcy_use_day" />
</form>
	
</section>

<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

function daycnt_calu_date()
{
	var sDate = $('#domcy_acppt_i_sdate').val();
	if ( sDate == '' )
	{
		alertToast('error','휴회신청일을 먼저 선택하세요');
		$('#domcy_acppt_i_cnt').val('');
		return;
	}
	
	var result = new Date(sDate);
	var addDay = $('#domcy_acppt_i_cnt').val();
	
	result.setDate(result.getDate() + Number(addDay));
	
	var date_y = result.getFullYear();
	var date_m = result.getMonth()+1;
	var date_d = result.getDate();
	
	var result_date = date_y+"-"+(("00"+date_m.toString()).slice(-2))+"-"+(("00"+date_d.toString()).slice(-2));
	
	$('#domcy_acppt_e_sdate').val(result_date);
}

$(".bottom-menu").click(function(){
	
    $('#domcy_acppt_i_sdate').datepicker('destroy');
	$('#domcy_acppt_i_sdate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
    });    
    $('.content').addClass('modal-open');
});

$(".bottom-menu-submit").click(function(){
	$('#fc_domcy_s_date').val($('#domcy_acppt_i_sdate').val());
	$('#fc_domcy_use_day').val($('#domcy_acppt_i_cnt').val());
	
	var params = $("#form_domcy").serialize();
    jQuery.ajax({
        url: '/api/ajax_domcy_acppt_proc',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
        	if ( result.substr(0,8) == '<script>' )
        	{
        		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
        		location.href='/login';
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
		location.href='/login';
		return;
    });
	
});

$("#bottom-menu-close").click(function(){
	$('.content').removeClass('modal-open');
});

// ===================== Modal Script [ START ] ===========================

$("#script_modal_default").click(function(){
	$("#modal-default").modal();
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