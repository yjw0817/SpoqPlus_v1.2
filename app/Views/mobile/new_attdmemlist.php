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
  width: 50%;
  text-align: center;
}

.domcy-box::before {
  content: '';
  position: absolute;
  height: 70%; /* 선의 높이를 90%로 설정 */
  width: 1px; /* 기본 너비 */
  background-color: #aaa; /* 선 색상 */
  left: 50%; /* 가로 정 가운데 */
  top: 50%; /* 세로 정 가운데 기준 */
  transform: translate(-50%, -50%) scaleX(0.5); /* 너비를 0.5로 축소 */
  transform-origin: center; /* 축소 기준점 설정 */
}
</style>
<?php
$sDef = SpoqDef();

if ($topinfo['get1'] == "") $topinfo['get1'] = "0";
?>
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		
		<div class="row ama-header1">
            <div class="ama-header-card">
                <div class="ama-title">수업 내역</div>
                <div class="ama-message text-left" style='font-size:0.9rem;'>
                	<div>수업 상품의 현황을 알 수 있어요</div>
                	<div>각 수업에 대해서 일지 내용을 확인 할 수 있어요</div>
                </div>
            </div>
        </div>
        
        <div class="row ama-header2">
	
            <div class="col-md-12">
            
            	<div class="stats-container col-md-12">
                    <div class="stat-item">
                        <div class="number reservation"><?php echo $topinfo['get1']?> 회</div>
                        <div class="number-label">전체 수업남은 횟수</div>
                    </div>
                    <div class="stat-item">
                        <div class="number used"><?php echo $topinfo['get2']?> 회</div>
                        <div class="number-label">당월 수업진행</div>
                    </div>
                </div>
            
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
            	<div class="domcy-box">
                    <div class="domcy-content-left">
                        <div>전체 수업남은 횟수</div>
                        <div><?php echo $topinfo['get1']?> 회</div>
                    </div>
                    <div class="domcy-content-right">
                        <div>당월 수업진행</div>
                        <div><?php echo $topinfo['get2']?> 회</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12" style='height:1000px;'>
            	<div class='a-title'>수업 내역 현황</div>
            	
            	<?php foreach($event_list as $r) :
            	$item_color = "black";
            	$item_word = "";
            	
            	if ($r['CANCEL_YN'] == 'Y')
            	{
            	    $item_color = "red";
            	    $item_word = " (취소)";
            	}
            	?>
            	<div class="a-list">
                    <div class="a-item">
                    	<div class='a-item-sec item-center '>
                    		<?php echo $r['SELL_EVENT_NM']?> &nbsp; <?php echo $item_word?>
                    		<span class="item-sky">수업강사 : <?php echo $r['STCHR_NM']?></span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec item-center '>
                    		전체수업
                    		<span class="item-sky"><?php echo ($r['MEM_REGUL_CLAS_PRGS_CNT']+$r['MEM_REGUL_CLAS_LEFT_CNT']+$r['SRVC_CLAS_PRGS_CNT']+$r['SRVC_CLAS_LEFT_CNT'])?> 회</span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec item-center '>
                    		수업일시
                    		<span class="item-sky"><?php echo $r['CRE_DATETM']?></span>
                    	</div>
                    	<div class="a-item-line"></div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<div class='item-btn-area'>
                    			<div class="cate bga-cate">진행 <?php echo ($r['MEM_REGUL_CLAS_PRGS_CNT']+$r['SRVC_CLAS_PRGS_CNT'])?>회</div>
                    			<div class="cate bga-sky">남은횟수 <?php echo ($r['MEM_REGUL_CLAS_LEFT_CNT']+$r['SRVC_CLAS_LEFT_CNT'])?>회</div>
                    		</div>
                    		<div class='item-btn-area'>
                    			<div class="btn bga-purple" onclick="pt_clas_msg('<?php echo $r['BUY_EVENT_SNO']?>');">수업일지</div>
                    		</div>
                    	</div>
                    </div>
                </div>
                <?php endforeach;?>
            	
            </div>
        </div>
		
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ============================= [ modal-default START ] ======================================= -->	
<!-- ============================= [ modal-default END ] ======================================= -->
<!-- ############################## MODAL [ END ] ###################################### -->

<div class="overlay">
    <div class="row">
    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area">
    			
                <button type="button" class="close" id="bottom-menu-close" style="margin-right:10px;margin-top:5px;">&times;</button>
                <br />
                <div class='bottom-title text-center'>수업 메세지</div>
                <div class='bottom-content' style='margin-top:15px;'>
                
                    <div class="panel-body">
						<form id='form_pt_chk'>
						<input type='hidden' name='buy_sno' id='pt_chk_buy_sno' />
						<div class="input-group input-group-sm" style='margin-bottom:10px;'>
                        	<input type="text" class="form-control" placeholder="수업내용" name="clas_conts" id="clas_conts">
                        	<span class="input-group-append">
                            	<button type="button" class="btn btn-info btn-flat" id="btn_clas_comment">입력</button>
                            </span>
                    	</div>
                    	</form>

                        <div class="direct-chat-messages" id="clas_msg">
                        
                        </div>
                    </div>
                
                </div>
            </div>
    	</div>
    </div>
</div>

	
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

function pt_clas_msg(buy_sno)
{
	var h_size = $(window).height();
  	var c_size = h_size - 200;
  	$('#bottom-menu-area').css("height",h_size+"px");
  	$('.direct-chat-messages').css("height",c_size+"px");
 	$('.content').addClass('modal-open');
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
				pt_clas_msg($('#pt_chk_buy_sno').val());
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


// ===================== Modal Script [ END ] =============================

</script>