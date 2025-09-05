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

/* 채팅 말풍선 스타일 */
.direct-chat-text {
  position: relative;
  background-color: #f0f0f0;
  border-radius: 10px;
  padding: 10px 15px;
  margin: 5px 0;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* 회원 메시지 (이제 왼쪽) - 사진 쪽 라운드 줄임 */
.direct-chat-msg:not(.right) .direct-chat-text {
  background-color: #f0f0f0 !important;
  color: #333 !important;
  border: 1px solid #e0e0e0 !important;
  border-radius: 4px 18px 18px 18px !important;
  position: relative !important;
}

/* 강사 메시지 (이제 오른쪽) - 사진 쪽 라운드 줄임 */
.direct-chat-msg.right .direct-chat-text {
  background-color: #FFF2B3 !important;
  color: #333 !important;
  border: 1px solid #e0e0e0 !important;
  border-radius: 18px 4px 18px 18px !important;
  position: relative !important;
}

/* 모든 꼭지 요소 완전 제거 */
.direct-chat-msg .direct-chat-text::before,
.direct-chat-msg .direct-chat-text::after,
.direct-chat-msg.right .direct-chat-text::before,
.direct-chat-msg.right .direct-chat-text::after {
  display: none !important;
  content: none !important;
}

/* 회원 메시지 우측 정렬 보정 */
.overlay .direct-chat-msg.right {
  margin-left: 0 !important;
  margin-right: 0 !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
}

/* 메시지 영역 전체를 화면 너비로 확장 */
.overlay .direct-chat-messages {
  margin-left: -1.25rem !important;
  margin-right: -1.25rem !important;
  padding: 10px 0 !important;
}

/* 회원 메시지 이미지 우측 끝에 고정 */
.overlay .direct-chat-msg.right .direct-chat-img {
  margin-right: 1.25rem !important;
  margin-left: 10px !important;
}

/* 강사 메시지 이미지 좌측 끝에 고정 */
.overlay .direct-chat-msg:not(.right) .direct-chat-img {
  margin-left: 1.25rem !important;
  margin-right: 10px !important;
}

/* 회원 메시지 말풍선 스타일 조정 */
.overlay .direct-chat-msg.right .direct-chat-text {
  max-width: calc(100% - 60px) !important;
  width: auto !important;
  margin-right: 0 !important;
  margin-left: 0 !important;
}

/* 강사 메시지 말풍선 최대 너비 제한 */
.overlay .direct-chat-msg:not(.right) .direct-chat-text {
  max-width: calc(100% - 60px) !important;
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
    				<div class="card-header">
    					<h3 class="card-title">수업내역</h3>
    				</div>
    				<!-- CARD HEADER [END] -->
    			
                    <div class="card-body p-0">
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
    			
                <button type="button" class="close" id="bottom-menu-close" style="margin-right:10px;margin-top:5px;">&times;</button>
                <br />
                <div class='bottom-title text-center'>수업 메세지</div>
                <div class='bottom-content' style='margin-top:15px;'>
                
                    <div class="card-body" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-direction: column;">
						<form id='form_pt_chk' style="display: contents;">
						<input type='hidden' name='buy_sno' id='pt_chk_buy_sno' />

                        <div class="direct-chat-messages" id="clas_msg" style="flex: 1; overflow-y: auto; margin-bottom: 10px;">
                        
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
                        
						<div class="input-group input-group-sm" style="flex-shrink: 0; padding-bottom: 0px;">
                        	<textarea class="form-control" placeholder="수업내용 (Shift+Enter: 줄바꿈, Enter: 전송)" name="clas_conts" id="clas_conts" rows="2" style="resize: vertical; min-height: 38px; max-height: 100px;"></textarea>
                        	<span class="input-group-append">
                            	<button type="button" class="btn btn-info btn-flat" id="btn_clas_comment">입력</button>
                            </span>
                    	</div>
                    	</form>
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
    
    // 수업내용 입력 필드에서 키 이벤트 처리
    $('#clas_conts').on('keydown', function(e) {
        if (e.which === 13 || e.keyCode === 13) { // 엔터 키
            if (e.shiftKey) {
                // Shift + Enter: 줄바꿈 (기본 동작 허용)
                return true;
            } else {
                // Enter만: 메시지 전송
                e.preventDefault(); // 기본 엔터 동작 방지
                $('#btn_clas_comment').click(); // 입력 버튼 클릭
            }
        }
    });
    
    // Window resize event handler
    $(window).on('resize', function() {
        if ($('.overlay').is(':visible') && $('.content').hasClass('modal-open')) {
            var h_size = $(window).height();
            // Resize 시에는 + 20을 사용 (초기 로드 시보다 10px 더 많이)
            var closeHeight = $('#bottom-menu-close').outerHeight(true) + 20;
            var availableHeight = h_size - closeHeight;
            $('#bottom-menu-area').css("height", availableHeight + "px");
        }
    });
})

var chatInterval; // 전역 변수로 인터벌 저장
var lastMessageTime = null; // 마지막 메시지 시간 저장

function pt_clas_msg(buy_sno)
{
 	$('.content').addClass('modal-open');
 	
 	// 초기 로드 시 높이 설정
 	var h_size = $(window).height();
 	$('#bottom-menu-area').css("height", h_size + "px");
 	
 	// close 버튼과 여백의 실제 높이를 계산 (초기 로드 시 + 10 사용)
 	var closeHeight = $('#bottom-menu-close').outerHeight(true) + 10;
 	$('#bottom-content').css("height", (h_size - closeHeight) + "px");
 	
 	$('#pt_chk_buy_sno').val(buy_sno);
 	
 	// 기존 인터벌이 있으면 제거
 	if (chatInterval) {
 		clearInterval(chatInterval);
 	}
 	
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
// 강사 메시지를 오른쪽으로
cmsg += "<div class='direct-chat-msg right' style='display:flex; margin:0 0 15px 0; padding:0; align-items:flex-start; width:100%; flex-direction:row-reverse;'>";
cmsg += "    <img class='direct-chat-img' src='" + (r['STCHR_THUMB_IMG'] || '/dist/img/default_profile_' + (r['STCHR_GENDR'] || 'M') + '.png') + "' alt='강사사진' style='width:40px; height:40px; border-radius:50%; margin-left:10px; margin-right:0; cursor:pointer; object-fit:cover; flex-shrink:0;' onclick='showFullPhoto(\"" + (r['STCHR_MAIN_IMG'] || r['STCHR_THUMB_IMG'] || '/dist/img/default_profile_' + (r['STCHR_GENDR'] || 'M') + '.png') + "\")' onerror='this.src=\"/dist/img/default_profile_" + (r['STCHR_GENDR'] || 'M') + ".png\"'>";
cmsg += "    <div style='flex:1; display:flex; flex-direction:column; align-items:flex-end; margin-right:0;'>";
cmsg += "        <div class='direct-chat-infos' style='margin-bottom:2px;'>";
cmsg += "            <span class='direct-chat-timestamp' style='font-size:0.7rem; color:#999; margin-right:10px;'>"+ r['CRE_DATETM'] +"</span>";
cmsg += "            <span class='direct-chat-name' style='font-size:0.75rem; color:#666;'>"+ r['STCHR_NM'] +" 강사</span>";
cmsg += "        </div>";
cmsg += "        <div class='direct-chat-text' style='font-size:0.8rem; white-space: pre-wrap; text-align:left;'>";
cmsg += rn_br(r['CLAS_DIARY_CONTS']);
cmsg += "        </div>";
cmsg += "    </div>";
cmsg += "</div>";					
					} else 
					{
// 회원 메시지를 왼쪽으로
cmsg += "<div class='direct-chat-msg' style='display:flex; margin-bottom:15px; align-items:flex-start; width:100%;'>";
cmsg += "    <img class='direct-chat-img' src='" + (r['MEM_THUMB_IMG'] || '/dist/img/default_profile_' + (r['MEM_GENDR'] || 'M') + '.png') + "' alt='회원사진' style='width:40px; height:40px; border-radius:50%; margin-right:10px; cursor:pointer; object-fit:cover; flex-shrink:0;' onclick='showFullPhoto(\"" + (r['MEM_MAIN_IMG'] || r['MEM_THUMB_IMG'] || '/dist/img/default_profile_' + (r['MEM_GENDR'] || 'M') + '.png') + "\")' onerror='this.src=\"/dist/img/default_profile_" + (r['MEM_GENDR'] || 'M') + ".png\"'>";
cmsg += "    <div style='flex:1;'>";
cmsg += "        <div class='direct-chat-infos' style='margin-bottom:2px;'>";
cmsg += "            <span class='direct-chat-name' style='font-size:0.75rem; color:#666;'>"+ r['MEM_NM'] +" 회원</span>";
cmsg += "            <span class='direct-chat-timestamp' style='font-size:0.7rem; color:#999; margin-left:10px;'>"+ r['CRE_DATETM'] +"</span>";
cmsg += "        </div>";
cmsg += "        <div class='direct-chat-text' style='font-size:0.8rem; white-space: pre-wrap; display:inline-block;'>";
cmsg += rn_br(r['CLAS_DIARY_CONTS']);
cmsg += "        </div>";
cmsg += "    </div>";
cmsg += "</div>";					
					}
				});
				
				$('#clas_msg').html(cmsg);
				
				// 스크롤을 맨 아래로
				$('#clas_msg').scrollTop($('#clas_msg')[0].scrollHeight);
				
				// 마지막 메시지 시간 저장 (가장 최신 메시지 시간)
				if (json_result['msg_list'].length > 0) {
					// ASC로 정렬되어 있으므로 마지막 메시지가 가장 최신
					lastMessageTime = json_result['msg_list'][json_result['msg_list'].length - 1]['CRE_DATETM'];
				}
				
				// 5초마다 새 메시지 확인
				chatInterval = setInterval(function() {
					loadNewMessages(buy_sno);
				}, 5000);
				
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

// 새 메시지만 불러오는 함수
function loadNewMessages(buy_sno) {
	var params = "buy_sno=" + buy_sno;
	if (lastMessageTime) {
		params += "&last_time=" + encodeURIComponent(lastMessageTime);
	}
	
	jQuery.ajax({
		url: '/api/ajax_clas_msg',
		type: 'POST',
		data: params,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function(result) {
			if (result.substr(0, 8) == '<script>') {
				clearInterval(chatInterval);
				return;
			}
			
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true' && json_result['msg_list'].length > 0) {
				var cmsg = '';
				var scrollToBottom = false;
				
				// 현재 스크롤이 거의 바닥에 있는지 확인
				var msgBox = $('#clas_msg');
				if (msgBox.scrollTop() + msgBox.innerHeight() >= msgBox[0].scrollHeight - 50) {
					scrollToBottom = true;
				}
				
				// 새 메시지만 추가
				var newestTime = lastMessageTime;
				json_result['msg_list'].forEach(function(r, index) {
					// 기존 메시지와 중복 체크
					if (!lastMessageTime || r['CRE_DATETM'] > lastMessageTime) {
						// 가장 최신 시간 추적
						if (!newestTime || r['CRE_DATETM'] > newestTime) {
							newestTime = r['CRE_DATETM'];
						}
						if (r['MEM_DV'] == 'T') {
							// 강사 메시지를 오른쪽으로
cmsg += "<div class='direct-chat-msg right' style='display:flex; margin:0 0 15px 0; padding:0; align-items:flex-start; width:100%; flex-direction:row-reverse;'>";
cmsg += "    <img class='direct-chat-img' src='" + (r['STCHR_THUMB_IMG'] || '/dist/img/default_profile_' + (r['STCHR_GENDR'] || 'M') + '.png') + "' alt='강사사진' style='width:40px; height:40px; border-radius:50%; margin-left:10px; margin-right:0; cursor:pointer; object-fit:cover; flex-shrink:0;' onclick='showFullPhoto(\"" + (r['STCHR_MAIN_IMG'] || r['STCHR_THUMB_IMG'] || '/dist/img/default_profile_' + (r['STCHR_GENDR'] || 'M') + '.png') + "\")' onerror='this.src=\"/dist/img/default_profile_" + (r['STCHR_GENDR'] || 'M') + ".png\"'>";
cmsg += "    <div style='flex:1; display:flex; flex-direction:column; align-items:flex-end; margin-right:0;'>";
cmsg += "        <div class='direct-chat-infos' style='margin-bottom:2px;'>";
cmsg += "            <span class='direct-chat-timestamp' style='font-size:0.7rem; color:#999; margin-right:10px;'>"+ r['CRE_DATETM'] +"</span>";
cmsg += "            <span class='direct-chat-name' style='font-size:0.75rem; color:#666;'>"+ r['STCHR_NM'] +" 강사</span>";
cmsg += "        </div>";
cmsg += "        <div class='direct-chat-text' style='font-size:0.8rem; white-space: pre-wrap; text-align:left;'>";
cmsg += rn_br(r['CLAS_DIARY_CONTS']);
cmsg += "        </div>";
cmsg += "    </div>";
cmsg += "</div>";
						} else {
							// 회원 메시지를 왼쪽으로
cmsg += "<div class='direct-chat-msg' style='display:flex; margin-bottom:15px; align-items:flex-start; width:100%;'>";
cmsg += "    <img class='direct-chat-img' src='" + (r['MEM_THUMB_IMG'] || '/dist/img/default_profile_' + (r['MEM_GENDR'] || 'M') + '.png') + "' alt='회원사진' style='width:40px; height:40px; border-radius:50%; margin-right:10px; cursor:pointer; object-fit:cover; flex-shrink:0;' onclick='showFullPhoto(\"" + (r['MEM_MAIN_IMG'] || r['MEM_THUMB_IMG'] || '/dist/img/default_profile_' + (r['MEM_GENDR'] || 'M') + '.png') + "\")' onerror='this.src=\"/dist/img/default_profile_" + (r['MEM_GENDR'] || 'M') + ".png\"'>";
cmsg += "    <div style='flex:1;'>";
cmsg += "        <div class='direct-chat-infos' style='margin-bottom:2px;'>";
cmsg += "            <span class='direct-chat-name' style='font-size:0.75rem; color:#666;'>"+ r['MEM_NM'] +" 회원</span>";
cmsg += "            <span class='direct-chat-timestamp' style='font-size:0.7rem; color:#999; margin-left:10px;'>"+ r['CRE_DATETM'] +"</span>";
cmsg += "        </div>";
cmsg += "        <div class='direct-chat-text' style='font-size:0.8rem; white-space: pre-wrap; display:inline-block;'>";
cmsg += rn_br(r['CLAS_DIARY_CONTS']);
cmsg += "        </div>";
cmsg += "    </div>";
cmsg += "</div>";
						}
					}
				});
				
				if (cmsg) {
					$('#clas_msg').append(cmsg);
					
					// 마지막 메시지 시간 업데이트 (가장 최신 메시지 시간으로)
					if (newestTime && newestTime > lastMessageTime) {
						lastMessageTime = newestTime;
					}
					
					// 스크롤이 바닥에 있었다면 새 메시지로 스크롤
					if (scrollToBottom) {
						msgBox.scrollTop(msgBox[0].scrollHeight);
					}
				}
			}
		}
	});
}

$("#bottom-menu-close").click(function(){
	$('.content').removeClass('modal-open');
	// 인터벌 정리
	if (chatInterval) {
		clearInterval(chatInterval);
		chatInterval = null;
	}
});

// ===================== Modal Script [ START ] ===========================


// ===================== Modal Script [ END ] =============================

function rn_br(word)
{
	return word.replace(/(?:\r\n|\r|\n)/g, '<br />');
}

// 사진 상세보기 함수
function showFullPhoto(imageSrc) {
    if (!imageSrc || imageSrc === '') {
        alertToast('error', '사진이 없습니다.');
        return;
    }
    
    // 모달이 이미 있다면 제거
    $('#photoModal').remove();
    
    // 모달 HTML 생성
    var modalHtml = `
        <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="photoModalLabel">사진 보기</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="${imageSrc}" class="img-fluid" alt="상세 사진" style="max-width: 100%; height: auto;" 
                             onerror="this.src='/dist/img/no_image.png'; this.alt='이미지를 불러올 수 없습니다.';">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // 모달을 body에 추가
    $('body').append(modalHtml);
    
    // 모달 표시
    $('#photoModal').modal('show');
    
    // 모달이 닫힐 때 DOM에서 제거
    $('#photoModal').on('hidden.bs.modal', function () {
        $(this).remove();
    });
}

</script>