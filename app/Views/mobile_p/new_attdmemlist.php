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
  background-color: #FFF2B3 !important;
  color: #333 !important;
  border: 1px solid #e0e0e0 !important;
  border-radius: 4px 18px 18px 18px !important;
  position: relative !important;
}

/* 강사 메시지 (이제 오른쪽) - 사진 쪽 라운드 줄임 */
.direct-chat-msg.right .direct-chat-text {
  background-color: #f0f0f0 !important;
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
   <div class="row">   
     <div class="new-title">수업내역</div>	
  </div>
        
        <!-- <div class="row ama-header2">
	
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
        </div> -->
        
        <div class="row">
            <div class="col-md-12 mt10">
            	<div class="stats-container col-md-12">
                    <div class="stat-item">
                        <div class="number-label">전체 수업남은 횟수</div>
                        <div class="number reservation"><?php echo $topinfo['get1']?></div>
                    </div>
          
                    <div class="stat-item">
                        <div class="number-label">당월 수업진행 횟수</div>
                        <div class="number used"><?php echo $topinfo['get2']?></div>
                    </div>
                </div>
            </div>
        </div>


        <!-- <div class="row">
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
        </div> -->
        
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
								<?php if(!empty($r['CLAS_MGMT_SNO'])) : ?><div class="cate bg-yellow" style="color:black; font-weight:bold">수업체크됨</div><?php endif; ?>
                    		</div>
                    		<div class='item-btn-area'>
                    			<div class="btn bga-purple" onclick="pt_clas_msg('<?php echo $r['BUY_EVENT_SNO']?>', '<?php echo isset($r['CLAS_MGMT_SNO']) ? $r['CLAS_MGMT_SNO'] : '' ?>', '<?php echo $r['ATTD_YMD']?>', '<?php echo $r['STCHR_ID']?>');">수업일지</div>
                    		</div>
                    	</div>
                    </div>
                </div>
                <?php endforeach;?>
            	
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
                <div id='bottom-content' style="position: relative; height: 100%;">
                
                    <div class="card-body" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-direction: column; padding: 0;">
						<form id='form_pt_chk' style="display: contents;">
						<!-- 버튼 영역 -->
						<div style="padding: 10px; flex-shrink: 0;">
							<button type="button" class="btn btn-secondary btn-sm col-12" id="btn_clas_ok" style="margin-bottom:7px;" disabled>수업체크</button>
							<input type='hidden' name='buy_sno' id='pt_chk_buy_sno' />
							<input type='hidden' name='attd_mgmt_sno' id='pt_chk_attd_mgmt_sno' />
							<input type='hidden' name='attd_ymd' id='pt_chk_attd_ymd' />
							<input type='hidden' name='stchr_id' id='pt_chk_stchr_id' />
						</div>

                        <!-- 메시지 영역 - flex: 1로 남은 공간 모두 차지 -->
                        <div class="direct-chat-messages" id="clas_msg" style="flex: 1; overflow-y: auto; padding: 10px;">
                        
                        </div>
                        
                        <!-- 입력창 영역 - 항상 보이도록 -->
						<div style="padding: 10px; padding-bottom: 20px; border-top: 1px solid #e0e0e0; background-color: #fff; flex-shrink: 0;">
							<div class="input-group input-group-sm">
								<textarea class="form-control" placeholder="수업내용 (Shift+Enter: 줄바꿈, Enter: 전송)" name="clas_conts" id="clas_conts" rows="2" style="resize: none; overflow-y: auto; line-height: 1.5;"></textarea>
								<span class="input-group-append">
									<button type="button" class="btn btn-info btn-flat" id="btn_clas_comment">입력</button>
								</span>
							</div>
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
            $('#bottom-menu-area').css("height", h_size + "px");
            
            // 리사이즈 시 overlay를 10px 더 아래로 (55px + 10px = 65px)
            $('.overlay').css('top', '65px');
            
            // close 버튼과 여백의 실제 높이를 계산 (resize 시에는 + 50 사용 - 20px 보정)
            var closeHeight = $('#bottom-menu-close').outerHeight(true) + 50;
            $('#bottom-content').css("height", (h_size - closeHeight) + "px");
        }
    });
})

 document.addEventListener("scroll", () => {
    const scrollY = window.scrollY;
    document.body.classList.toggle("scrolled", scrollY > 120);
  });

var chatInterval; // 전역 변수로 인터벌 저장
var lastMessageTime = null; // 마지막 메시지 시간 저장

function pt_clas_msg(buy_sno, clas_mgmt_sno, attd_ymd, stchr_id)
{
 	$('.content').addClass('modal-open');
 	
 	// 초기 로드 시 높이 설정
 	var h_size = $(window).height();
 	$('#bottom-menu-area').css("height", h_size + "px");
 	
 	// close 버튼과 여백의 실제 높이를 계산 (초기 로드 시 + 40 사용 - 20px 보정)
 	var closeHeight = $('#bottom-menu-close').outerHeight(true) + 40;
 	$('#bottom-content').css("height", (h_size - closeHeight) + "px");
 	
 	$('#pt_chk_buy_sno').val(buy_sno);
 	$('#pt_chk_attd_mgmt_sno').val(clas_mgmt_sno || '');
 	$('#pt_chk_attd_ymd').val(attd_ymd || '');
 	$('#pt_chk_stchr_id').val(stchr_id || '');
 	
 		// 기존 인터벌이 있으면 제거
	if (chatInterval) {
		clearInterval(chatInterval);
	}
	
	var params = "buy_sno="+buy_sno+"&attd_ymd="+(attd_ymd || '');
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
				
				// 수업 체크 버튼 상태 설정
				if (json_result.hasOwnProperty('attend_pt_without_pt_check_attd_mgmt_sno')) {
					// attd_mgmt_sno가 있으면 수업 체크 가능
					if (json_result['attend_pt_without_pt_check_attd_mgmt_sno']) {
						$('#btn_clas_ok').prop('disabled', false);
						$('#btn_clas_ok').removeClass('btn-secondary').addClass('btn-info');
						// attd_mgmt_sno 값을 hidden 필드에 설정
						$('#pt_chk_attd_mgmt_sno').val(json_result['attend_pt_without_pt_check_attd_mgmt_sno']);
					} else {
						$('#btn_clas_ok').prop('disabled', true);
						$('#btn_clas_ok').removeClass('btn-info').addClass('btn-secondary');
					}
				}
				
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
				pt_clas_msg($('#pt_chk_buy_sno').val(), $('#pt_chk_attd_mgmt_sno').val(), $('#pt_chk_attd_ymd').val(), $('#pt_chk_stchr_id').val());
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
	// overlay 위치 원래대로 복구
	$('.overlay').css('top', '');
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

// 수업체크 버튼 클릭 이벤트
$("#btn_clas_ok").click(function(){
	Swal.fire({
		text: '수업 1회진행을 확인합니다.',
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#17a2b8',
		cancelButtonColor: '#6c757d',
		confirmButtonText: '확인',
		cancelButtonText: '취소'
	}).then((result) => {
		if (!result.isConfirmed) {
			return;
		}
		
		var params = $("#form_pt_chk").serialize();
		jQuery.ajax({
			url: '/api/ajax_clas_ok_mem',
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
					// 수업체크 완료 후 메시지 목록 새로고침
					pt_clas_msg($('#pt_chk_buy_sno').val(), $('#pt_chk_attd_mgmt_sno').val(), $('#pt_chk_attd_ymd').val(), $('#pt_chk_stchr_id').val());
					
					Swal.fire({
						title: '완료',
						text: '수업체크가 완료되었습니다.',
						icon: 'success',
						confirmButtonColor: '#17a2b8',
						confirmButtonText: '확인'
					});
				} else {
					Swal.fire({
						title: '오류',
						text: json_result['msg'],
						icon: 'error',
						confirmButtonColor: '#dc3545',
						confirmButtonText: '확인'
					});
				}
			}
		}).done((res) => {
			console.log('통신성공');
		}).fail((error) => {
			console.log('통신실패');
			alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
			location.href='/login';
			return;
		});
	});
});

</script>