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
		<h4 class="panel-title">강사 공지사항 리스트</h4>
	</div>
	<!-- CARD HEADER [END] -->
	
	

	<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		<!-- TABLE [START] -->
		<table  class="table table-bordered table-hover table-striped col-md-12">
			<thead>
				<tr class='text-center'>
					<th style='width:70px'>번호</th>
					<th style='width:130px'>상위노출</th>
					<th style='width:130px'>상태</th>
					<th>제목</th>
					<th style='width:130px'>공지 시작일</th>
					<th style='width:130px'>공지 종료일</th>
					<th style='width:140px'>등록아이디</th>
					<th style='width:140px'>등록일시</th>
					<th style='width:140px'>Action</th>
				</tr>
			</thead> 
			<tbody>
				<?php 
				foreach($noti_list as $r) :
					$backColor = "";
				?>
				<tr style="background-color: <?php echo $backColor ?>;">
					<td class='text-center'><?php echo $search_val['listCount']?></td>
					<td class='text-center'><?php echo $r['NOTI_TOP']?></td>
					<td class='text-center'><?php echo $sDef['NOTI_STAT'][$r['NOTI_STAT']]?></td>
					<td><?php echo $r['NOTI_TITLE']?></td>
					<td class='text-center'><?php echo $r['NOTI_S_DATE']?></td>
					<td class='text-center'><?php echo $r['NOTI_E_DATE']?></td>
					<td><?php echo $r['CRE_ID']?></td>
					<td><?php echo $r['CRE_DATETM']?></td>
					<td style="text-align:center">
						<button type="button" class="btn btn-block btn-success btn-xs" onclick="notice_modify_form('<?php echo $r['NOTI_SNO']?>');">수정하기</button>
					</td>
				</tr>
				<?php 
				$search_val['listCount']--;
				endforeach;
				?>
			</tbody>
		</table>
		<!-- TABLE [END] -->
		
			<!-- CARD FOOTER [START] -->
	<div class="card-footer clearfix">
		<!-- BUTTON [START] -->
		<ul class="pagination pagination-sm m-0 float-right">
			<li class="ac-btn"><a href="#modal_notice_form" class="btn btn-success btn-sm" data-bs-toggle="modal">등록하기</a></li>
		</ul>
		<!-- BUTTON [END] -->
		<!-- PAGZING [START] -->
		<?php //echo $pager?>
		<!-- PAGZING [END] -->
	</div>
	<!-- CARD FOOTER [END] -->


	</div>
	<!-- CARD BODY [END] -->


</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->


<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_notice_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">강사 공지사항 등록하기</h5>
                <button type="button" class="close2"  data-bs-dismiss="modal" aria-label="Close">
                <i class="fas fa-times" style="font-size:18px;"></i>
            </div>
            <div class="modal-body">
            
            	
            	<!-- FORM [START] -->
            	<form id="notice_insert_form">
            	<input type="hidden" name="noti_dv" id="noti_dv" value="02" />
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>제목</span>
                	</span>
                	<input type="text" class="form-control" placeholder="공지사항 제목" name="noti_title">
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>시작~종료일</span>
                	</span>
                	<input type="text" class="form-control" name="noti_s_date" id="noti_s_date" placeholder="공지시작일">
					~<input type="text" class="form-control" name="noti_e_date" id="noti_e_date" placeholder="공지종료일">
                	
                	
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<textarea rows='8' class="form-control" name="noti_conts"></textarea>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>상위노출</span>
                	</span>
                	
                	<div style='margin-top:4px;margin-left:5px;'>
                    	<div class="icheck-primary d-inline">
                            <input type="radio" id="radioNotiTop1" name="noti_top" value="Y" />
                            <label for="radioNotiTop1">
                            	<small>노출</small>
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="radioNotiTop2" name="noti_top" value="N" checked />
                            <label for="radioNotiTop2">
                            	<small>비노출</small>
                            </label>
                        </div>
                    </div>
                </div>
                
            	<!--  -->
            	
            	</form>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="notice_insert_btn">등록하기</button>
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-default END ] ======================================= -->

<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_notice_modify_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">강사 공지사항 수정하기</h5>
                <button type="button" class="close2"  data-bs-dismiss="modal" aria-label="Close">
				<i class="fas fa-times" style="font-size:18px;"></i>
                </button>
            </div>
            <div class="modal-body">
         
            	
            	<!-- FORM [START] -->
            	<form id="notice_modify_form">
            	<input type="hidden" name="mod_noti_sno" id="mod_noti_sno" />
            	<input type="hidden" name="mod_noti_dv" id="mod_noti_dv" value="02" />
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>제목</span>
                	</span>
                	<input type="text" class="form-control" placeholder="공지사항 제목" name="mod_noti_title" id="mod_noti_title">
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>시작~종료일</span>
                	</span>
                	<input type="text" class="form-control" name="mod_noti_s_date" id="mod_noti_s_date" placeholder="공지시작일">
                	~<input type="text" class="form-control" name="mod_noti_e_date" id="mod_noti_e_date" placeholder="공지종료일">
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<textarea rows='8' class="form-control" name="mod_noti_conts" id="mod_noti_conts"></textarea>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>상위노출</span>
                	</span>
                	
                	<div style='margin-top:4px;margin-left:5px;'>
                    	<div class="icheck-primary d-inline">
                            <input type="radio" id="mod_radioNotiTop1" name="mod_noti_top" value="Y" />
                            <label for="mod_radioNotiTop1">
                            	<small>노출</small>
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="mod_radioNotiTop2" name="mod_noti_top" value="N" />
                            <label for="mod_radioNotiTop2">
                            	<small>비노출</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:100px'>공지상태</span>
                	</span>
                	
                	<div style='margin-top:4px;margin-left:5px;'>
                    	<div class="icheck-primary d-inline">
                            <input type="radio" id="mod_radioNotiStat1" name="mod_noti_stat" value="00" />
                            <label for="mod_radioNotiStat1">
                            	<small>등록</small>
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="mod_radioNotiStat2" name="mod_noti_stat" value="90" />
                            <label for="mod_radioNotiStat2">
                            	<small>취소</small>
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="mod_radioNotiStat3" name="mod_noti_stat" value="99" />
                            <label for="mod_radioNotiStat3">
                            	<small>삭제</small>
                            </label>
                        </div>
                    </div>
                </div>
                
            	<!--  -->
            	
            	</form>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="notice_modify_btn">수정하기</button>
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

function notice_modify_form(noti_sno)
{
	var params = "noti_sno="+noti_sno+"&noti_dv=02";
    jQuery.ajax({
        url: '/tbcoffmain/ajax_get_notice_info',
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
				console.log( json_result['noti_info'] );
				
				$('#mod_noti_sno').val( json_result['noti_info']['NOTI_SNO'] );
				$('#mod_noti_title').val( json_result['noti_info']['NOTI_TITLE'] );
				$('#mod_noti_s_date').val( json_result['noti_info']['NOTI_S_DATE'] );
				$('#mod_noti_e_date').val( json_result['noti_info']['NOTI_E_DATE'] );
				$('#mod_noti_conts').val( json_result['noti_info']['NOTI_CONTS'] );
				
				if ( json_result['noti_info']['NOTI_TOP'] == "N" )
				{
					$('#mod_radioNotiTop2').prop("checked",true);
				} else 
				{
					$('#mod_radioNotiTop1').prop("checked",true);
				}
				
				if ( json_result['noti_info']['NOTI_STAT'] == "00" )
				{
					$('#mod_radioNotiStat1').prop("checked",true);
				} else if ( json_result['noti_info']['NOTI_STAT'] == "90" )
				{
					$('#mod_radioNotiStat2').prop("checked",true);
				} else if ( json_result['noti_info']['NOTI_STAT'] == "99" )
				{
					$('#mod_radioNotiStat3').prop("checked",true);
				}
				
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

	$('#modal_notice_modify_form').modal("show");
}

$('#noti_s_date').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$('#noti_e_date').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$('#mod_noti_s_date').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$('#mod_noti_e_date').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$('#notice_insert_btn').click(function(){
	// 실패일 경우 warning error success info question
	//alertToast('error','대분류 코드를 입력하세요');
	
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >공지사항을 등록하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		var params = $("#notice_insert_form").serialize();
    	    jQuery.ajax({
    	        url: '/tbcoffmain/ajax_notice_insert_proc',
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

$('#notice_modify_btn').click(function(){
	// 실패일 경우 warning error success info question
	//alertToast('error','대분류 코드를 입력하세요');
	
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >공지사항을 수정 하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		var params = $("#notice_modify_form").serialize();
    	    jQuery.ajax({
    	        url: '/tbcoffmain/ajax_notice_modify_proc',
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