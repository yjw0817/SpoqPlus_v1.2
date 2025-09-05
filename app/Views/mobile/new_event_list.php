<style>
.overlay {
  z-index:1002;
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

.domcy-box {
  margin-top:10px;
  display: flex;
  align-items: center; /* 세로 가운데 정렬 */
  justify-content: center; /* 가로 가운데 정렬 */
  position: relative;
  width: 100%;
  height: 70px;
  border: 1px solid #eee;
  border-radius: 5px; /* 모서리를 5px 둥글게 처리 */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06); /* 그림자 추가 */
  background-color: #fff; /* 배경색 추가 (필요시) */
}

.domcy-content-left,
.domcy-content-right {
  width: 33%;
  text-align: center;
}

.domcy-box::before {
  content: '';
  position: absolute;
  height: 70%; /* 선의 높이를 90%로 설정 */
  width: 1px; /* 기본 너비 */
  background-color: #aaa; /* 선 색상 */
  left: 33%; /* 가로 정 가운데 */
  top: 50%; /* 세로 정 가운데 기준 */
  transform: translate(-50%, -50%) scaleX(0.5); /* 너비를 0.5로 축소 */
  transform-origin: center; /* 축소 기준점 설정 */
}
</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
	
	
		<div class="row ama-header1">
            <div class="ama-header-card">
                <div class="ama-title">구매상품 현황</div>
                <div class="ama-message text-left" style='font-size:0.9rem;'>
                	<div>예약됨,이용중,종료됨 상품을 확인하세요</div>
                	<div>---</div>
                </div>
            </div>
        </div>
        
        <div class="row ama-header2">
	
            <div class="col-md-12">
            
            	<div class="stats-container col-md-12">
                    <div class="stat-item">
                        <div class="number reservation"><?php echo count($event_list1)?></div>
                        <div class="number-label">예약상품</div>
                    </div>
                    <div class="stat-item">
                        <div class="number used"><?php echo count($event_list2)?></div>
                        <div class="number-label">이용상품</div>
                    </div>
                    <div class="stat-item">
                        <div class="number completed"><?php echo count($event_list3)?></div>
                        <div class="number-label">종료상품</div>
                    </div>
                </div>
            
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
            	<div class="domcy-box">
                    <div class="domcy-content-left">
                        <div style='color:#0698A9'>예약상품</div>
                        <div style='color:#0698A9'><?php echo count($event_list1)?>개</div>
                    </div>
                    <div class="domcy-content-right">
                        <div>이용상품</div>
                        <div><?php echo count($event_list2)?>개</div>
                    </div>
                    <div class="domcy-content-right">
                        <div style='color:#A90647'>종료상품</div>
                        <div style='color:#A90647'><?php echo count($event_list3)?>개</div>
                    </div>
                </div>
            </div>
        </div>
		
		<div class="row">
            <div class="col-md-12">
            	
            	
            	<div class='a-title'>예약된 상품 현황</div>
            	<?php if ( count($event_list1) > 0) :?>
            	<?php foreach ($event_list1 as $r) :
            	
            	$acc_rtrct_class = "ft-sky"; // 일장불가일 경우 빨간색 , 아닐경우 하늘색
            	$disp_prod_cnt = "";
            	$disp_right_word = "";
            	$disp_center_word = "";
            	$disp_none_style = "style='display:none'";
            	
            	if ($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22")
            	{
            	    $disp_prod_cnt = $r['CLAS_CNT']."회";
            	    $disp_right_word = "수업강사 : " . $r['STCHR_NM'];
            	    
            	    $info1 = $r['ADD_SRVC_CLAS_CNT'] +  $r['CLAS_CNT'];
            	    $info2 = $r['SRVC_CLAS_PRGS_CNT'] +  $r['MEM_REGUL_CLAS_PRGS_CNT'];
            	    
            	    $disp_center_word = $info1."회중 ".$info2."회 이용중";
            	    $disp_none_style = "";
            	} else
            	{
            	    $disp_prod_cnt = disp_produnit($r['USE_PROD'],$r['USE_UNIT']);
            	    $disp_center_word = $r['EXR_S_DATE'] . "~" . $r['EXR_E_DATE'];
            	    
            	    if ($r['LOCKR_SET'] == "Y")
            	    {
            	        $disp_right_word = $sDef['LOCKR_GENDR_SET'][$r['LOCKR_GENDR_SET']] ." " . $r['LOCKR_NO'] . "번";
            	    }
            	    
            	}
            	
            	if ($r['ACC_RTRCT_MTHD'] == "99") $acc_rtrct_class = "ft-red";
            	?>
            	
                <div class="a-list2">
                    <div class="a-item">
                    	<div class='a-item-sec item-center item-bold <?php echo $acc_rtrct_class?>'>
                    		<?php echo $sDef['ACC_RTRCT_DV'][$r['ACC_RTRCT_DV']]?> (<?php echo $disp_prod_cnt?>)
                    		<span class="item-bold ft-default"><?php echo $disp_right_word?></span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec item-center '>
                    		<?php echo $r['SELL_EVENT_NM']?>
                    		<span class="item-cancel">
                    			<?php if ($r['REAL_SELL_AMT'] != $r['BUY_AMT']) :?>
                    			<?php echo number_format($r['REAL_SELL_AMT'])?> 원
                    			<?php endif;?>
                    		</span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<span class="ft-blue item-bold">
                    			<span class="ft-sky item-light"><?php echo $disp_center_word?></span>
                    		</span>
                    		<span class=""><?php echo number_format($r['BUY_AMT'])?> 원</span>
                    	</div>
                    	<div class="a-item-line"></div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<div class='item-btn-area'>
                    			<div class="cate bga-cate"><?php echo $cate_nm[$r['1RD_CATE_CD']][$r['2RD_CATE_CD']]?></div>
                    		</div>
                    		<div class='item-btn-area'>
                    			<!--  -->
                    			<div class="btn bga-gray" <?php echo $disp_none_style?> >수업일지</div>
                    			<div class="btn bga-sky"  onclick="buy_event('<?php echo $r['SELL_EVENT_SNO']?>');">재구매</div>
                    		</div>
                    	</div>
                    </div>
                </div>
                
                <?php endforeach; ?>
                <?php else :?>
            	<div class="a-list">
                    <div class="a-item text-center" style='height:50px;margin-top:20px;'>
                    	예약된 상품이 없습니다.
                    </div>
                </div>
            	<?php endif;?>
            </div>
        </div>
	
        
	
		<div class="row">
            <div class="col-md-12">
            	
            	
            	<div class='a-title'>이용중인 상품 현황</div>
            	<?php if ( count($event_list2) > 0) :?>
            	<?php foreach ($event_list2 as $r) :
            	
            	$acc_rtrct_class = "ft-sky"; // 일장불가일 경우 빨간색 , 아닐경우 하늘색
            	$disp_prod_cnt = "";
            	$disp_right_word = "";
            	$disp_center_word = "";
            	$disp_none_style = "style='display:none'";
            	
            	if ($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22")
            	{
            	    $disp_prod_cnt = $r['CLAS_CNT']."회";
            	    $disp_right_word = "수업강사 : " . $r['STCHR_NM'];
            	    
            	    $info1 = $r['ADD_SRVC_CLAS_CNT'] +  $r['CLAS_CNT'];
            	    $info2 = $r['SRVC_CLAS_PRGS_CNT'] +  $r['MEM_REGUL_CLAS_PRGS_CNT'];
            	    
            	    $disp_center_word = $info1."회중 ".$info2."회 이용중";
            	    $disp_none_style = "";
            	} else
            	{
            	    $disp_prod_cnt = disp_produnit($r['USE_PROD'],$r['USE_UNIT']);
            	    $disp_center_word = $r['EXR_S_DATE'] . "~" . $r['EXR_E_DATE'];
            	    
            	    if ($r['LOCKR_SET'] == "Y")
            	    {
            	        $disp_right_word = $sDef['LOCKR_GENDR_SET'][$r['LOCKR_GENDR_SET']] ." " . $r['LOCKR_NO'] . "번";
            	    }
            	    
            	}
            	
            	if ($r['ACC_RTRCT_MTHD'] == "99") $acc_rtrct_class = "ft-red";
            	?>
            	
                <div class="a-list">
                    <div class="a-item">
                    	<div class='a-item-sec item-center item-bold <?php echo $acc_rtrct_class?>'>
                    		<?php echo $sDef['ACC_RTRCT_DV'][$r['ACC_RTRCT_DV']]?> (<?php echo $disp_prod_cnt?>)
                    		<span class="item-bold ft-default"><?php echo $disp_right_word?></span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec item-center '>
                    		<?php echo $r['SELL_EVENT_NM']?>
                    		<span class="item-cancel">
                    			<?php if ($r['REAL_SELL_AMT'] != $r['BUY_AMT']) :?>
                    			<?php echo number_format($r['REAL_SELL_AMT'])?> 원
                    			<?php endif;?>
                    		</span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<span class="ft-blue item-bold">
                    			<span class="ft-sky item-light"><?php echo $disp_center_word?></span>
                    		</span>
                    		<span class=""><?php echo number_format($r['BUY_AMT'])?> 원</span>
                    	</div>
                    	<div class="a-item-line"></div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<div class='item-btn-area'>
                    			<div class="cate bga-cate"><?php echo $cate_nm[$r['1RD_CATE_CD']][$r['2RD_CATE_CD']]?></div>
                    		</div>
                    		<div class='item-btn-area'>
                    			<div class="btn bga-purple" <?php echo $disp_none_style?> onclick="pt_clas_msg('<?php echo $r['BUY_EVENT_SNO']?>','pt');">수업일지</div>
                    			<div class="btn bga-sky"  onclick="buy_event('<?php echo $r['SELL_EVENT_SNO']?>');">재구매</div>
                    		</div>
                    	</div>
                    </div>
                </div>
                
                <?php endforeach; ?>
                <?php else :?>
            	<div class="a-list">
                    <div class="a-item text-center" style='height:50px;margin-top:20px;'>
                    	이용중인 상품이 없습니다.
                    </div>
                </div>
            	<?php endif;?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
            	
            	
            	<div class='a-title'>종료된 상품 현황</div>
            	<?php if ( count($event_list3) > 0) :?>
            	<?php foreach ($event_list3 as $r) :
            	
            	$acc_rtrct_class = "ft-sky"; // 일장불가일 경우 빨간색 , 아닐경우 하늘색
            	$disp_prod_cnt = "";
            	$disp_right_word = "";
            	$disp_center_word = "";
            	$disp_none_style = "style='display:none'";
            	
            	if ($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22")
            	{
            	    $disp_prod_cnt = $r['CLAS_CNT']."회";
            	    $disp_right_word = "수업강사 : " . $r['STCHR_NM'];
            	    
            	    $info1 = $r['ADD_SRVC_CLAS_CNT'] +  $r['CLAS_CNT'];
            	    $info2 = $r['SRVC_CLAS_PRGS_CNT'] +  $r['MEM_REGUL_CLAS_PRGS_CNT'];
            	    
            	    $disp_center_word = $info1."회중 ".$info2."회 이용중";
            	    $disp_none_style = "";
            	} else
            	{
            	    $disp_prod_cnt = disp_produnit($r['USE_PROD'],$r['USE_UNIT']);
            	    $disp_center_word = $r['EXR_S_DATE'] . "~" . $r['EXR_E_DATE'];
            	    
            	    if ($r['LOCKR_SET'] == "Y")
            	    {
            	        $disp_right_word = $sDef['LOCKR_GENDR_SET'][$r['LOCKR_GENDR_SET']] ." " . $r['LOCKR_NO'] . "번";
            	    }
            	    
            	}
            	
            	if ($r['ACC_RTRCT_MTHD'] == "99") $acc_rtrct_class = "ft-red";
            	?>
            	
                <div class="a-list3">
                    <div class="a-item">
                    	<div class='a-item-sec item-center item-bold <?php echo $acc_rtrct_class?>'>
                    		<?php echo $sDef['ACC_RTRCT_DV'][$r['ACC_RTRCT_DV']]?> (<?php echo $disp_prod_cnt?>)
                    		<span class="item-bold ft-default"><?php echo $disp_right_word?></span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec item-center '>
                    		<?php echo $r['SELL_EVENT_NM']?>
                    		<span class="item-cancel">
                    			<?php if ($r['REAL_SELL_AMT'] != $r['BUY_AMT']) :?>
                    			<?php echo number_format($r['REAL_SELL_AMT'])?> 원
                    			<?php endif;?>
                    		</span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<span class="ft-blue item-bold">
                    			<span class="ft-sky item-light"><?php echo $disp_center_word?></span>
                    		</span>
                    		<span class=""><?php echo number_format($r['BUY_AMT'])?> 원</span>
                    	</div>
                    	<div class="a-item-line"></div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<div class='item-btn-area'>
                    			<div class="cate bga-cate"><?php echo $cate_nm[$r['1RD_CATE_CD']][$r['2RD_CATE_CD']]?></div>
                    		</div>
                    		<div class='item-btn-area'>
                    			<div class="btn bga-purple" <?php echo $disp_none_style?> onclick="pt_clas_msg('<?php echo $r['BUY_EVENT_SNO']?>','pt');">수업일지</div>
                    			<div class="btn bga-sky"  onclick="buy_event('<?php echo $r['SELL_EVENT_SNO']?>');">재구매</div>
                    		</div>
                    	</div>
                    </div>
                </div>
                
                <?php endforeach; ?>
                <?php else :?>
            	<div class="a-list">
                    <div class="a-item text-center" style='height:50px;margin-top:20px;'>
                    	종료된 상품이 없습니다.
                    </div>
                </div>
            	<?php endif;?>
            </div>
        </div>
		
	</div>
	
	<div class="overlay">
        <div class="row">
        	<div class="col_md-12" style='width:100%'>
        		<div class="" id="bottom-menu-area">
        			
                    <button type="button" class="close" id="bottom-menu-close" style="margin-right:10px;margin-top:5px;">&times;</button>
                    <br />
                    <div class='bottom-title text-center' id='btitle'>수업 메세지</div>
                    <div class='bottom-content' style='margin-top:15px;'>
                    
                        <div class="panel-body">
    						
    						<div id='btype_pt'>
    						<form id='form_pt_chk'>
    						<input type='hidden' name='buy_sno' id='pt_chk_buy_sno' />
    						<div class="input-group input-group-sm" style='margin-bottom:10px;'>
                            	<input type="text" class="form-control" placeholder="수업내용" name="clas_conts" id="clas_conts">
                            	<span class="input-group-append">
                                	<button type="button" class="btn btn-info btn-flat" id="btn_clas_comment">입력</button>
                                </span>
                        	</div>
                        	</form>
                        	</div>
    
                            <div class="direct-chat-messages" id="clas_msg">
                            </div>
                        </div>
                    
                    </div>
                </div>
        	</div>
        </div>
    </div>
	
	
	
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ============================= [ modal-default START ] ======================================= -->	
<!-- ============================= [ modal-default END ] ======================================= -->
<!-- ############################## MODAL [ END ] ###################################### -->

	
</section>

<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

 document.addEventListener("scroll", () => {
    const scrollY = window.scrollY;
    document.body.classList.toggle("scrolled", scrollY > 120);
  });

function buy_event(sell_event_sno)
{
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >해당 상품을 구매하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
			location.href="/api/event_buy_info/"+sell_event_sno;			
    	}
    });
}
function pt_clas_msg(buy_sno,mtype)
{
// 	$(".overlay").show();
	
	//document.body.style.overflow = 'hidden';


  	var h_size = $(window).height();
  	var c_size = h_size - 200;
  	$('#bottom-menu-area').css("height",h_size+"px");
  	
 	
 	$('.content').addClass('modal-open');
 	
 	if (mtype == 'pt')
 	{
 		$('.direct-chat-messages').css("height",c_size+"px");
 		$('#btitle').text('수업 메세지');
 		$('#btype_pt').show();
 		
 		$('#pt_chk_buy_sno').val(buy_sno);
 	
     	var params = "buy_sno="+buy_sno;
        jQuery.ajax({
            url: '/api/ajax_clas_msg',
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
    				var cmsg = '';
    				
    				json_result['msg_list'].forEach(function (r,index) {
    				
    					cmsg += "";
    					
    					
    					if ( r['MEM_DV'] == 'T' )
    					{
    					
    cmsg += "<div class='direct-chat-msg'>";
    cmsg += "    <div class='direct-chat-infos clearfix'>";
    cmsg += "    <span class='direct-chat-name float-left'>"+ r['STCHR_NM'] +" 강사</span>";
    cmsg += "    <span class='direct-chat-timestamp float-right'>"+ r['CRE_DATETM'] +"</span>";
    cmsg += "    </div>";
    cmsg += "    <div class='direct-chat-text' style='font-size:0.8rem;'>";
    cmsg += r['CLAS_DIARY_CONTS'];
    cmsg += "    </div>";
    cmsg += "</div>";					
    					} else 
    					{
    cmsg += "<div class='direct-chat-msg right'>";
    cmsg += "    <div class='direct-chat-infos clearfix'>";
    cmsg += "    <span class='direct-chat-name float-right'>"+ r['MEM_NM'] +" 회원</span>";
    cmsg += "    <span class='direct-chat-timestamp float-left'>"+ r['CRE_DATETM'] +"</span>";
    cmsg += "    </div>";
    cmsg += "    <div class='direct-chat-text' style='font-size:0.8rem;'>";
    cmsg += r['CLAS_DIARY_CONTS'];
    cmsg += "    </div>";
    cmsg += "</div>";					
    					}
    					
    				});
    				
    				$('#clas_msg').html(cmsg);
    				
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
 	
}

function rn_br(word)
{
	return word.replace(/(?:\r\n|\r|\n)/g, '<br />');
}


$("#btn_clas_comment").click(function(){

	if ( $('#clas_conts').val() == '' )
	{
		alertToast('error','내용을 입력하세요.');
		return;
	}
	
	var params = $("#form_pt_chk").serialize();
    jQuery.ajax({
        url: '/api/ajax_clas_diary_mem_insert_proc',
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
				$('#clas_conts').val('');
				pt_clas_msg($('#pt_chk_buy_sno').val(),'pt');
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
	document.body.style.overflow = '';
});

// ===================== Modal Script [ START ] ===========================

$("#script_modal_default").click(function(){
	$("#modal-default").modal("show");
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