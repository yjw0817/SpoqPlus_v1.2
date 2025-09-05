<style>
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

<!-- Main content -->
<section class="content">
	
		<div class="row " id='status-info'>
	         <div class="new-title"><?php echo $_SESSION["user_name"] ?>님</div>
            <div class="col-4">
                <div class="small-box2 bg-info">
                <div class="inner">
                <h3><?php echo $topinfo['get1']?><sup style="font-size: 20px"> 명</sup></h3>
                <p>수업회원출석</p>
                </div>
                
                <a href="/api/tattdmem" class="small-box-footer">
                More <i class="fas fa-arrow-circle-right"></i>
                </a>
                </div>
            </div>
            
            <div class="col-4">
                <div class="small-box2" >
                <div class="inner">
                <h3><?php echo $topinfo['get2']?><sup style="font-size: 20px"> 회</sup></h3>
                <p>오늘 수업수</p>
                </div>
               
                <a href="/api/tattdmemlist" class="small-box-footer">
                More <i class="fas fa-arrow-circle-right"></i>
                </a>
                </div>
            </div>
            
            <div class="col-4">
                <div class="small-box2 bg-danger" >
                <div class="inner">
                <h3><?php echo $topinfo['get3']?><sup style="font-size: 20px"> 건</sup></h3>
                <p>수업종료예정</p>
                </div>
               
                <a href="/api/teventend" class="small-box-footer">
                More <i class="fas fa-arrow-circle-right"></i>
                </a>
                </div>
            </div>
            
        </div>
        
        
        
       
		<div class="row">
        
            <div class="col-4" onclick="location.href='/api/tsalary';">
                <div class="description-block border-right">
                <!-- <span class="description-percentage text-danger"><i class="fas fa-caret-up"></i> 17%</span> -->
                <h3 class="description-header"><?php echo number_format($midinfo['get1'])?></h5>
                <span class="description-text">수당집계</span>
                </div>
            </div>
            
            <div class="col-4" onclick="location.href='/api/tmem_payment';">
                <div class="description-block border-right">
                <!-- <span class="description-percentage text-danger"><i class="fas fa-caret-up"></i> 17%</span> -->
                <h5 class="description-header"><?php echo number_format($midinfo['get2'])?></h5>
                <span class="description-text">판매금액</span>
                </div>
            </div>
            
            <div class="col-4" onclick="location.href='/api/tmem_attdmemlist';">
                <div class="description-block">
                <!-- <span class="description-percentage text-danger"><i class="fas fa-caret-up"></i> 17%</span> -->
                <h5 class="description-header"><?php echo number_format($midinfo['get3'])?></h5>
                <span class="description-text">수업금액</span>
                </div>
            </div>
        
        </div>
		
       <div class="row mt20">	
          <div class="bbsbox">  
             <div class="bbs-title">오늘 나의 GX 스케쥴</div>
             <div class="bbs-list2">
                <?php if (count($list_gx) > 0) :?>
        						<?php foreach ($list_gx as $r) :
        						$chk_word = "";
        						$gx_flag = "Y";
        						if ($r['CLAS_CHK_YN'] == "Y")
        						{
        						    $chk_word = "완료";
        						    $gx_flag = "N";
        						}
        						
        		?>
                <ul>
                    <li>
                      <?php echo $chk_word?>
        				<span class="bbs_tx01">ㆍ  <?php echo $r['GX_CLAS_TITLE']?></span>
        				<span class="bbs_tx02"><?php echo substr($r['GX_CLAS_S_HH_II'],0,5)?> ~ <?php echo substr($r['GX_CLAS_E_HH_II'],0,5)?></span>
        				<span>
                           <button type="button" class="btn btn-xs" onclick="gx_attd('<?php echo $r['GX_SCHD_MGMT_SNO']?>','<?php echo $gx_flag?>');"><i class="fas fa-chevron-right"></i></button>
                        </span>
                    </li>
                   <?php endforeach;?>
        						<?php else : ?>
                    <li>GX 스케쥴이 없습니다.</li>
                    <?php endif; ?>
                </ul>
             </div>
           </div> 
        </div> 


		<div class="row mt20">	
          <div class="bbsbox">  
             <div class="bbs-title">강사 공지사항</div>
             <div class="bbs-list2">
                <?php if (count($list_unotice) > 0) :?>
        		<?php foreach ($list_unotice as $r) :?>
                <ul>
                    <li>
                      ㆍ <?php echo $r['NOTI_TITLE']?>
        				<span style='float:right'>
                          <button type="button" class="btn btn-xs" onclick="notice_detail('<?php echo $r['NOTI_SNO']?>');"><i class="fas fa-chevron-right"></i></button>
                        </span>
                    </li>
                    <?php endforeach;?>
        			<?php else : ?>
                    <li>공지사항이 없습니다.</li>
                    <?php endif; ?>
                </ul>
             </div>
           </div> 
        </div>

		<div class="row mt20">	
          <div class="bbsbox">  
             <div class="bbs-title">회원 공지사항</div>
             <div class="bbs-list2">
                <?php if (count($list_unotice) > 0) :?>
        		<?php foreach ($list_unotice as $r) :?>
                <ul>
                    <li>
                      ㆍ <?php echo $r['NOTI_TITLE']?>
        			   <span>
                         <button type="button" class="btn btn-xs" onclick="unotice_detail('<?php echo $r['NOTI_SNO']?>');"><i class="fas fa-chevron-right"></i></button>
                       </span>
                    </li>
                    <?php endforeach;?>
        			<?php else : ?>
                    <li>공지사항이 없습니다.</li>
                    <?php endif; ?>
                </ul>
             </div>
           </div> 
        </div> 

 		
                
		<!-- <div class="row mt20">
			<div class="col-md-12">
                <div class="card card-lightblue" style="margin:10px;">
					<div class="card-header">
    					<h3 class="card-title ">강사 공지사항</h3>
    				</div>					
					<div class="card-body card-table">
						<table class="table table-hover col-md-12">
        					<tbody>
        						<?php if (count($list_unotice) > 0) :?>
        						<?php foreach ($list_unotice as $r) :?>
        						<tr class=''>
        							<td><?php echo $r['NOTI_TITLE']?>
        							<span style='float:right'>
                                    	<button type="button" class="btn btn-xs" onclick="notice_detail('<?php echo $r['NOTI_SNO']?>');"><i class="fas fa-chevron-right"></i></button>
                                    </span>
        							</td>
        						</tr>
        						<?php endforeach;?>
        						<?php else : ?>
        						<tr class=''>
        							<td>공지사항이 없습니다.</td>
        						</tr>
        						<?php endif; ?>
        					</tbody>
                        </table>
					</div>
                </div>
                
                
                
                <div class="card card-lightblue" style="margin:10px;">
					<div class="card-header">
    					<h3 class="card-title ">회원 공지사항</h3>
    				</div>
					<div class="card-body card-table">
						<table class="table table-hover col-md-12">
        					<tbody>
        						<?php if (count($list_unotice) > 0) :?>
        						<?php foreach ($list_unotice as $r) :?>
        						<tr class=''>
        							<td><?php echo $r['NOTI_TITLE']?>
        							<span style='float:right'>
                                    	<button type="button" class="btn btn-xs" onclick="unotice_detail('<?php echo $r['NOTI_SNO']?>');"><i class="fas fa-chevron-right"></i></button>
                                    </span>
        							</td>
        						</tr>
        						<?php endforeach;?>
        						<?php else : ?>
        						<tr class=''>
        							<td>공지사항이 없습니다.</td>
        						</tr>
        						<?php endif; ?>
        					</tbody>
                        </table>
					</div>
                </div>		
			</div>           			
		</div> -->
        
			
		<!-- <div class="row">
			<div class="col-md-12">
                <div class="card card-lightblue" style="margin:10px;">
					<div class="card-header">
    					<h3 class="card-title ">나의 오늘 GX 스케쥴</h3>
    				</div>
					
					<div class="card-body card-table">
						<table class="table table-hover col-md-12" style='width:100%;'>
        					<tbody>
        						<?php if (count($list_gx) > 0) :?>
        						<?php foreach ($list_gx as $r) :
        						$chk_word = "";
        						$gx_flag = "Y";
        						if ($r['CLAS_CHK_YN'] == "Y")
        						{
        						    $chk_word = "완료";
        						    $gx_flag = "N";
        						}
        						
        						?>
        						<tr class='' style='width:100%;'>
        							<td><?php echo $chk_word?></td>
        							<td><?php echo $r['GX_CLAS_TITLE']?></td>
        							<td><?php echo substr($r['GX_CLAS_S_HH_II'],0,5)?> ~ <?php echo substr($r['GX_CLAS_E_HH_II'],0,5)?>
        							<span style='float:right'>
                                    	<button type="button" class="btn btn-xs" onclick="gx_attd('<?php echo $r['GX_SCHD_MGMT_SNO']?>','<?php echo $gx_flag?>');"><i class="fas fa-chevron-right"></i></button>
                                    </span>
        							</td>
        						</tr>
        						<?php endforeach;?>
        						<?php else : ?>
        						<tr class=''>
        							<td>GX 스케쥴이 없습니다.</td>
        						</tr>
        						<?php endif; ?>
        					</tbody>
                        </table>
					</div>
                </div>
			
			
			</div>
		</div> -->
		
	
	
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

<div class="overlay">
    <div class="row">
    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area">
                <button type="button" class="close" id="bottom-menu-close" 
               style="position: relative; top: 28px; right: 20px; z-index: 999; color: #ffffff;">&times;</button>
                <br />
                <div id='bottom-content'>

                </div>
            </div>
    	</div>
    </div>
</div>
<input type="hidden" id="fmode" value="<?php echo $first_mode?>" />
<input type="hidden" id="user_id" value="<?php echo $_SESSION['user_id']?>" />

</section>

<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
    user_set();
})

function gx_attd(gx_sno,gx_flag)
{
	var ff_msg = "";
	if (gx_flag == 'Y')
	{
		ff_msg = "GX 수업 체크를 하시겠습니까?";
	} else 
	{
		ff_msg = "GX 수업 체크를 취소 하시겠습니까?";
	}
	
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >"+ff_msg+"</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
			var params = "gx_schd_mgmt_sno="+gx_sno+"&gx_flag="+gx_flag;
            jQuery.ajax({
                url: '/api/ajax_gx_attd_proc',
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
        				alert(json_result['msg']);
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
        		location.href='/login';
        		return;
            });	
    	}
    });
	
}

function user_set()
{
	// 아이디,비밀번호로 로그인 하였을때 아이디를 새롭게 저장해야한다.
	sitenm = "mmmain";
	nbCall_get('uid');
}

function mmmain_chk_user_set(user_id)
{
	if ( $('#user_id').val() != user_id )
	{
		nbCall_save('uid',$('#user_id').val());
		nbCall_save('logintp','');
	}
}

function rn_br(word)
{
	return word.replace(/(?:\r\n|\r|\n)/g, '<br />');
}

function notice_detail(noti_sno)
{
    debugger;
	//$(".overlay").show();
 	
 	$('#bottom-content').empty();
 	
 	var h_size = $(window).height();
  	var c_size = h_size - 200;
  	$('#bottom-menu-area').css("height",h_size+"px");
 	$('.content').addClass('modal-open');
 	
 	var params = "noti_sno="+noti_sno;
	jQuery.ajax({
        url: '/api/ajax_mtmain_get_tnotice_detail',
        type: 'POST',
        data:params,
        async: false,
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
			//console.log(json_result);
			if (json_result['result'] == 'true')
			{
				// 성공시
				var obj = json_result['content'];
				
                var addHtml = "";				
                addHtml = "<div class='new-title'>강사 공지</div>";
                addHtml += "<div class='col-12' style='padding:20px'>";
                addHtml += "   <p class='lead'>"+ obj['NOTI_TITLE'] +"</p>";
                addHtml += "   <div class='table-responsive'>";
                addHtml += "    <table class='table'>";
                addHtml += "    <tr>";
                addHtml += "    <th style='width:30%'>공지 시작일</th>";
                addHtml += "    <td>"+ obj['NOTI_S_DATE'] +"</td>";
                addHtml += "    </tr>";
                addHtml += "    <tr>";
                addHtml += "    <th>공지 종료일</th>";
                addHtml += "    <td>"+ obj['NOTI_E_DATE'] +"</td>";
                addHtml += "    </tr>";
                addHtml += "    <tr>";
                addHtml += "    <th class='text-center' colspan='2'>내 용</th>";
                addHtml += "    </tr>";
                addHtml += "    <tr>";
                addHtml += "    <td colspan='2'>"+ rn_br(obj['NOTI_CONTS']) +"</td>";
                addHtml += "    </tr>";
                addHtml += "    </table>";
                addHtml += "    </div>";
                addHtml += "</div>";
                
                $('#bottom-content').html(addHtml);
				
			} else 
			{
				// 실패시
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
		location.href='/login';
		return;
    });
 	
}

function unotice_detail(noti_sno)
{
	//$(".overlay").show();
 	
 	$('#bottom-content').empty();
 	
 	var h_size = $(window).height();
  	var c_size = h_size - 200;
  	$('#bottom-menu-area').css("height",h_size+"px");
 	$('.content').addClass('modal-open');
 	
 	var params = "noti_sno="+noti_sno;
	jQuery.ajax({
        url: '/api/ajax_mtmain_get_unotice_detail',
        type: 'POST',
        data:params,
        async: false,
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
			//console.log(json_result);
			if (json_result['result'] == 'true')
			{
				// 성공시
				var obj = json_result['content'];
				
                var addHtml = "";				
                addHtml = "<div class='new-title'>회원 공지</div>";
                addHtml += "<div class='col-12' style='padding:20px'>";
                addHtml += "   <p class='lead'>"+ obj['NOTI_TITLE'] +"</p>";
                addHtml += "   <div class='table-responsive'>";
                addHtml += "    <table class='table'>";
                addHtml += "    <tr>";
                addHtml += "    <th style='width:30%'>공지 시작일</th>";
                addHtml += "    <td>"+ obj['NOTI_S_DATE'] +"</td>";
                addHtml += "    </tr>";
                addHtml += "    <tr>";
                addHtml += "    <th>공지 종료일</th>";
                addHtml += "    <td>"+ obj['NOTI_E_DATE'] +"</td>";
                addHtml += "    </tr>";
                addHtml += "    <tr>";
                addHtml += "    <th class='text-center' colspan='2'>내 용</th>";
                addHtml += "    </tr>";
                addHtml += "    <tr>";
                addHtml += "    <td colspan='2'>"+ rn_br(obj['NOTI_CONTS']) +"</td>";
                addHtml += "    </tr>";
                addHtml += "    </table>";
                addHtml += "    </div>";
                addHtml += "</div>";
                
                $('#bottom-content').html(addHtml);
				
			} else 
			{
				// 실패시
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
		location.href='/login';
		return;
    });
 	
}


$(".bottom-menu").click(function(){
	$(".overlay").show();
 	var h_size = $(window).height();
 	$('#bottom-menu-area').css("height",h_size+"px");
 	$('#bottom-content').empty();
});

$("#bottom-menu-close").click(function(){
	//$(".overlay").hide();
	$('.content').removeClass('modal-open');
	$('#bottom-content').empty();
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