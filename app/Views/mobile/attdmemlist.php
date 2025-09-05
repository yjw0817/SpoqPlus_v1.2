<style>
#status-info {
  position: sticky;
  top: 57px;
  height: 120px;
  z-index: 998;
}

.row {
    background-color:#ffffff !important;
}

.container-fluid {
    background-color:#ffffff !important;
}

.overlay {
  z-index: 999;
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

if ($topinfo['get1'] == "") $topinfo['get1'] = "0";
?>
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
	
	
        <div class="row" id='status-info'>
            <div class="col-lg-3 col-6">
                <div class="small-box" style='margin:5px'>
                <div class="inner">
                <h3><?php echo $topinfo['get1']?><sup style="font-size: 20px">회</sup></h3>
                <p>전체 수업남은 횟수</p>
                </div>
                <div class="icon">
                <i class="fas fa-shopping-cart"></i>
                </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info" style='margin:5px'>
                <div class="inner">
                <h3><?php echo $topinfo['get2']?><sup style="font-size: 20px">회</sup></h3>
                <p>당월 수업진행</p>
                </div>
                <div class="icon">
                <i class="ion ion-stats-bars"></i>
                </div>
                </div>
            </div>
        </div>
	
		
		
		<div class="row" style='margin-top:20px'>
			<div class="col-md-12">
                
                <div class="card card-primary">
				
    				<!-- CARD HEADER [START] -->
    				<div class="page-header">
    					<h3 class="panel-title">수업내역</h3>
    				</div>
    				<!-- CARD HEADER [END] -->
    			
                    <div class="panel-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            <?php if (count($event_list) > 0) :?>
                            <?php foreach ($event_list as $r) :
                                $item_color = "black";
                                $item_word = "";
                                
                                if ($r['CANCEL_YN'] == 'Y')
                                {
                                    $item_color = "red";
                                    $item_word = " (취소)";
                                }
                            
                            ?>
                            <li class="item">
                                <div class="">
                                <a href="javascript:void(0)" class="product-title" style='color:<?php echo $item_color?>'>
                                	<?php echo $r['SELL_EVENT_NM']?> <?php echo $item_word?>
                                	<!-- <span class="badge float-right" style="text-decoration: line-through;color:red">1,200,000</span>  -->
                                </a>
                                <span class="product-description">
                                	PT <?php echo ($r['MEM_REGUL_CLAS_PRGS_CNT']+$r['MEM_REGUL_CLAS_LEFT_CNT']+$r['SRVC_CLAS_PRGS_CNT']+$r['SRVC_CLAS_LEFT_CNT'])?>회 (<?php echo $r['MEM_NM']?> 회원)
                                	<span style='float:right'>
                                    	<button type="button" class="btn btn-xs" onclick="pt_clas_msg('<?php echo $r['BUY_EVENT_SNO']?>');"><i class="fas fa-chevron-right"></i></button>
                                    </span>
                                </span>
                                <span style="font-size:0.8rem;color:green">
                                 (진행 <?php echo ($r['MEM_REGUL_CLAS_PRGS_CNT']+$r['SRVC_CLAS_PRGS_CNT'])?>회 | 남은횟수 <?php echo ($r['MEM_REGUL_CLAS_LEFT_CNT']+$r['SRVC_CLAS_LEFT_CNT'])?>회 )
                                </span>
                                
                                <span class="float-right" style="font-size:0.8rem;color:green">
                                <?php echo $r['CRE_DATETM']?>
                                </span>
                                </div>
                            </li>
                            <?php endforeach;?>
                            <?php else : ?>
                    			<li class="item" style='font-size:0.9rem;'>수업 내역이 없습니다.</li>
							<?php endif; ?>
                        </ul>
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
                <button type="button" class="close"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default"  data-bs-dismiss="modal">Close</button>
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
                        
                        	<!-- 
                        	<div class="direct-chat-msg">
                                <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-left">강사1 강사</span>
                                <span class="direct-chat-timestamp float-right">2024-08-27 11:23:22</span>
                                </div>
                                <div class="direct-chat-text" style='font-size:0.8rem;'>
                                충분해요. 저를 믿고 따라오시면 됩니다. 화이팅입니다.
                                </div>
                            </div>
                            
                        	<div class="direct-chat-msg right">
                                <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-right">홍길동 회원</span>
                                <span class="direct-chat-timestamp float-left">2024-08-27 11:23:22</span>
                                </div>
                                <div class="direct-chat-text" style='font-size:0.8rem;'>
                                에고 정말 그래도 될까요 ? 제가 아직 체력이 되지 않아서.
                                </div>
                            </div>
                             -->
                        
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