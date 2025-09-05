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
    max-width: 320px;
    margin: 0 auto;
}

.datepicker.datepicker-days
{
    width:100% !important;
    max-width: 320px;
}

/* 달력 날짜 정렬 및 컴팩트 표시 */
.datepicker table {
    width: 100%;
    table-layout: fixed;
}

.datepicker table th,
.datepicker table td {
    text-align: center;
    vertical-align: middle;
    padding: 4px;
    width: 14.28%;
    font-size: 12px;
    line-height: 1.2;
}

.datepicker table .day {
    min-height: 28px;
    line-height: 28px;
    padding: 0;
    border-radius: 3px;
}

.datepicker .datepicker-switch {
    font-size: 13px;
}

.datepicker .prev, 
.datepicker .next {
    font-size: 14px;
    padding: 6px 10px;
}

/* 모바일 최적화 */
@media (max-width: 480px) {
    .datepicker {
        max-width: 300px;
        font-size: 11px;
    }
    
    .datepicker table th,
    .datepicker table td {
        font-size: 11px;
        padding: 3px;
    }
    
    .datepicker table .day {
        min-height: 26px;
        line-height: 26px;
    }
}

/* SweetAlert2 모바일 최적화 */
.swal-mobile-popup {
    width: 90% !important;
    max-width: 350px !important;
    font-size: 14px !important;
}

.swal-mobile-title {
    font-size: 18px !important;
    font-weight: bold !important;
}

.swal-mobile-content {
    font-size: 14px !important;
    line-height: 1.4 !important;
}

@media (max-width: 480px) {
    .swal-mobile-popup {
        width: 95% !important;
        margin: 0 auto !important;
        font-size: 13px !important;
    }
    
    .swal-mobile-title {
        font-size: 16px !important;
    }
    
    .swal-mobile-content {
        font-size: 13px !important;
    }
    
    .swal2-confirm, .swal2-cancel {
        font-size: 14px !important;
        padding: 8px 16px !important;
        min-width: 80px !important;
    }
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

/* 강사 메시지 (왼쪽) - 사진 쪽 라운드 줄임 */
.direct-chat-msg .direct-chat-text {
  background-color: #FFF2B3 !important;
  color: #333 !important;
  border: 1px solid #e0e0e0 !important;
  border-radius: 4px 18px 18px 18px !important;
  position: relative !important;
}

/* 회원 메시지 (오른쪽) - 사진 쪽 라운드 줄임 */
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

#status-info {
  position: sticky;
  top: 57px;
  height: 150px;
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

.overlay2 {
  z-index: 1000;
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

.content.modal-open .overlay2 {
  top: 55px;
}


</style>

<!-- Main content -->
 <div class="new-title">수업출석회원 </div>
<section class="content">

		<div class="row" id='status-info'>
            <div class="col-lg-3 col-6">
                <div class="small-box2 bg-info">
                <div class="inner">
                <h3><?php echo count($list_clas_attd_n)?><sup style="font-size: 20px">회</sup></h3>
                <p>일반입장</p>
                </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box2 bg-success">
                <div class="inner">
                <h3><?php echo count($list_clas_attd_y)?><sup style="font-size: 20px">회</sup></h3>
                <p>수업입장</p>
                </div>
                </div>
            </div>
            <div class="col-md-12 mb20">
	    		<div class='float-right' style='margin-right:15px;' onclick="search_detail();">
	    			<span style='margin-right:15px;font-size:0.9rem'>
	    			<?php echo $ss_ymd?>
	    			</span>
	    			<i class="fas fa-chevron-down"></i>
	    		</div>
	    	</div>
        </div>
	
	
		<div class="row p-2">
			<div class="col-md-12 mt30">
                
                <div class="card-header bg-info">
					<h3 class="card-title">일반입장</h3>
				</div>
				
                <!-- CARD BODY [START] -->
				<div class="card-body">
                    
                <?php
                if ( count($list_clas_attd_n) > 0) :
                foreach($list_clas_attd_n as $r) :?>    
                    <span style="color:green"><strong><span class="badge badge-info">일반입장</span> <?php disp_mem_info($r['MEM_SNO']);?> <?php echo $r['MEM_NM']?> <?php if(!empty($r['CLAS_MGMT_SNO'])) : ?><span class="badge badge-warning">수업체크됨</span><?php endif; ?></strong></span>
                    <div class="text-muted" style="font-size:1.0rem;">
                    	PT <?php echo ($r['CLAS_CNT'] + $r['ADD_SRVC_CLAS_CNT'])?>회 중 <?php echo ($r['MEM_REGUL_CLAS_PRGS_CNT'] + $r['SRVC_CLAS_PRGS_CNT'])?>회
                    	<span style='float:right'>
                        	<button type="button" class="btn btn-xs" onclick="pt_chk('<?php echo $r['BUY_EVENT_SNO']?>','<?php echo $r['ATTD_MGMT_SNO']?>','<?php echo $r['ATTD_YMD']?>','<?php echo isset($r['CLAS_MGMT_SNO']) ? $r['CLAS_MGMT_SNO'] : ''?>');"><i class="fas fa-chevron-right"></i></button>
                        </span>
                    </div>
                    <span style="font-size:0.8rem;"><strong><i class="far fa-calendar-alt"></i> <?php echo $r['ATTD_DATETM']?></strong></span> 
                    <hr>
                <?php endforeach;
                else :
                ?>
				<span style='font-size:0.9rem;'>일반 입장이 없습니다.</span>
				<?php endif;?>
				</div>
                <!-- CARD BODY [END] -->
                			
			</div>
			
			<div class="col-md-12">
                
                <div class="card-header bg-success">
					<h3 class="card-title">수업입장</h3>
				</div>
				
                <!-- CARD BODY [START] -->
				<div class="card-body">
                    
                <?php 
                if ( count($list_clas_attd_y) > 0) :
                foreach($list_clas_attd_y as $r) :?>    
                    <span style="color:green"><strong><span class="badge badge-success">수업입장</span> <?php disp_mem_info($r['MEM_SNO']);?> <?php echo $r['MEM_NM']?> <?php if(!empty($r['CLAS_MGMT_SNO'])) : ?><span class="badge badge-warning">수업체크됨</span><?php endif; ?></strong></span>
                    <div class="text-muted" style="font-size:1.0rem;">
                    	PT <?php echo ($r['CLAS_CNT'] + $r['ADD_SRVC_CLAS_CNT'])?>회 중 <?php echo ($r['MEM_REGUL_CLAS_PRGS_CNT'] + $r['SRVC_CLAS_PRGS_CNT'])?>회
                    	<span style='float:right'>
                        	<button type="button" class="btn btn-xs" onclick="pt_chk('<?php echo $r['BUY_EVENT_SNO']?>','<?php echo $r['ATTD_MGMT_SNO']?>','<?php echo $r['ATTD_YMD']?>','<?php echo isset($r['CLAS_MGMT_SNO']) ? $r['CLAS_MGMT_SNO'] : ''?>');"><i class="fas fa-chevron-right"></i></button>
                        </span>
                    </div>
                    <span style="font-size:0.8rem;"><strong><i class="far fa-calendar-alt"></i> <?php echo $r['ATTD_DATETM']?></strong></span> 
                    <hr>
                <?php endforeach;
                else:
                ?>
				<span style='font-size:0.9rem;'>수업 입장이 없습니다.</span>
				<?php endif;?>
				</div>
                <!-- CARD BODY [END] -->
                			
			</div>
			
			
		</div>

<!-- ############################## MODAL [ SATRT ] #################################### -->
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
							<button type="button" class="btn btn-info btn-sm col-6 float-left" id="btn_clas_ok" style="margin-bottom:7px; display:none;">수업체크</button>
							<button type="button" class="btn btn-warning btn-sm col-12" id="btn_clas_cancel" style="margin-bottom:7px; display:none;" disabled>수업체크 취소</button>
							<input type='hidden' name='buy_sno' id='pt_chk_buy_sno' />
							<input type='hidden' name='attd_mgmt_sno' id='pt_chk_attd_mgmt_sno' />
							<input type='hidden' name='attd_ymd' id='pt_chk_attd_ymd' />
						</div>

                        <!-- 메시지 영역 - flex: 1로 남은 공간 모두 차지 -->
                        <div class="direct-chat-messages" id="clas_msg" style="flex: 1; overflow-y: auto; padding: 10px;">
                        
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

<div class="overlay2">
    <div class="row"><div class="new-title">검색할 날짜</div>
    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area" style="border-bottom:0px;">
    			<form name="form_tmem_payment" id="form_tmem_payment" method="post" action="/api/tattdmem">
                    <button type="button" class="close" id="bottom-menu-close2" 
                    style="margin-right:20px;margin-top:-35px; color:#fff">&times;</button>
                    <br />
                    <div class='bottom-title text-center'>날짜 선택</div>
                    <div class='bottom-content'>
                    
                        <div class="card card-success">
                        <div class="card-body">
                        
                        	<input class="form-control form-control-lg" type="text" name="ss_ymd" id="ss_ymd" placeholder="검색일" readonly value="<?php echo $ss_ymd?>" >
                        
                        </div>
                        </div>
                        
                        <!--<button type="button" class='btn btn-block bga-main ft-white btn-sm p-3 bottom-menu' onclick="btn_search();" style="display: none;">검색하기</button>-->
                    
                    </div>
                </form>
            </div>
    	</div>
    </div>
</div>
	
</section>

<?=$jsinc ?>

<script>
var chatInterval; // 전역 변수로 인터벌 저장
var lastMessageTime = null; // 마지막 메시지 시간 저장

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
    
    // 윈도우 리사이즈 이벤트 처리
    $(window).resize(function() {
        if ($('.overlay').is(':visible')) {
            var h_size = $(window).height();
            $('#bottom-menu-area').css("height", h_size + "px");
            
            // close 버튼과 br 태그의 실제 높이를 계산
            var closeHeight = $('#bottom-menu-close').outerHeight(true) + 40;
            $('#bottom-content').css("height", (h_size - closeHeight) + "px");
        }
    });
})

function btn_search()
{
	var ssymd = $('#ss_ymd').val();
	location.href="/api/tattdmem/"+ssymd;
}

function search_detail()
{
	$('#ss_ymd').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
    }).on('changeDate', function(e) {
        // 날짜 선택 시 자동으로 검색 실행
        setTimeout(function() {
            btn_search();
        }, 100);
    });
    
	$('.overlay').hide();
	$('.overlay2').show();
	
 	var h_size = $(window).height();
  	var c_size = h_size - 200;
  	$('#bottom-menu-area2').css("height",h_size+"px");
 	$('.content').addClass('modal-open');
}

$("#bottom-menu-close2").click(function(){
	$('.content').removeClass('modal-open');
});

function pt_chk(buy_sno,attd_mgmt_sno,attd_ymd,clas_mgmt_sno)
{
	$('.overlay').show();
	$('.overlay2').hide();
	
	var h_size = $(window).height();
  	$('#bottom-menu-area').css("height",h_size+"px");
  	
  	// close 버튼과 br 태그의 실제 높이를 계산
  	var closeHeight = $('#bottom-menu-close').outerHeight(true) + 30; // 처음 로드 시
  	$('#bottom-content').css("height",(h_size - closeHeight)+"px");
 	$('.content').addClass('modal-open');
 	
 	$('#pt_chk_buy_sno').val(buy_sno);
 	$('#pt_chk_attd_mgmt_sno').val(attd_mgmt_sno);
 	$('#pt_chk_attd_ymd').val(attd_ymd);
 	
 	// 수업체크 취소 버튼 표시 여부 결정
 	// CLAS_MGMT_SNO가 있고, 출석 날짜가 오늘인 경우에만 수업체크 취소 버튼 표시
 	var today = new Date().toISOString().split('T')[0]; // YYYY-MM-DD 형식
 	if (clas_mgmt_sno && clas_mgmt_sno !== '' && attd_ymd === today) {
		$('#btn_clas_cancel').show(); 
		$('#btn_clas_cancel').prop('disabled', false);
 	} else {
		$('#btn_clas_cancel').hide(); 
		$('#btn_clas_cancel').prop('disabled', true);
 	}
 	
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
// 강사 메시지 (왼쪽)
cmsg += "<div class='direct-chat-msg' style='display:flex; margin-bottom:15px; align-items:flex-start; width:100%;'>";
cmsg += "    <img class='direct-chat-img' src='" + (r['STCHR_THUMB_IMG'] || '/dist/img/default_profile_' + (r['STCHR_GENDR'] || 'M') + '.png') + "' alt='강사사진' style='width:40px; height:40px; border-radius:50%; margin-right:10px; cursor:pointer; object-fit:cover; flex-shrink:0;' onclick='showFullPhoto(\"" + (r['STCHR_MAIN_IMG'] || r['STCHR_THUMB_IMG'] || '/dist/img/default_profile_' + (r['STCHR_GENDR'] || 'M') + '.png') + "\")' onerror='this.src=\"/dist/img/default_profile_" + (r['STCHR_GENDR'] || 'M') + ".png\"'>";
cmsg += "    <div style='flex:1;'>";
cmsg += "        <div class='direct-chat-infos' style='margin-bottom:2px;'>";
cmsg += "            <span class='direct-chat-name' style='font-size:0.75rem; color:#666;'>"+ r['STCHR_NM'] +" 강사</span>";
cmsg += "            <span class='direct-chat-timestamp' style='font-size:0.7rem; color:#999; margin-left:10px;'>"+ r['CRE_DATETM'] +"</span>";
cmsg += "        </div>";
cmsg += "        <div class='direct-chat-text' style='font-size:0.8rem; white-space: pre-wrap; display:inline-block;'>";
cmsg += rn_br(r['CLAS_DIARY_CONTS']);
cmsg += "        </div>";
cmsg += "    </div>";
cmsg += "</div>";					
					} else 
					{
// 회원 메시지 (오른쪽)
cmsg += "<div class='direct-chat-msg right' style='display:flex; margin:0 0 15px 0; padding:0; align-items:flex-start; width:100%; flex-direction:row-reverse;'>";
cmsg += "    <img class='direct-chat-img' src='" + (r['MEM_THUMB_IMG'] || '/dist/img/default_profile_' + (r['MEM_GENDR'] || 'M') + '.png') + "' alt='회원사진' style='width:40px; height:40px; border-radius:50%; margin-left:10px; margin-right:0; cursor:pointer; object-fit:cover; flex-shrink:0;' onclick='showFullPhoto(\"" + (r['MEM_MAIN_IMG'] || r['MEM_THUMB_IMG'] || '/dist/img/default_profile_' + (r['MEM_GENDR'] || 'M') + '.png') + "\")' onerror='this.src=\"/dist/img/default_profile_" + (r['MEM_GENDR'] || 'M') + ".png\"'>";
cmsg += "    <div style='flex:1; display:flex; flex-direction:column; align-items:flex-end; margin-right:0;'>";
cmsg += "        <div class='direct-chat-infos' style='margin-bottom:2px;'>";
cmsg += "            <span class='direct-chat-timestamp' style='font-size:0.7rem; color:#999; margin-right:10px;'>"+ r['CRE_DATETM'] +"</span>";
cmsg += "            <span class='direct-chat-name' style='font-size:0.75rem; color:#666;'>"+ r['MEM_NM'] +" 회원</span>";
cmsg += "        </div>";
cmsg += "        <div class='direct-chat-text' style='font-size:0.8rem; white-space: pre-wrap; text-align:left;'>";
cmsg += rn_br(r['CLAS_DIARY_CONTS']);
cmsg += "        </div>";
cmsg += "    </div>";
cmsg += "</div>";					
					}
				});
				
				$('#clas_msg').html(cmsg);
				
				// 스크롤을 맨 아래로
				$('#clas_msg').scrollTop($('#clas_msg')[0].scrollHeight);
				
				// 가장 최신 메시지 시간 저장 (ASC 정렬이므로 마지막 요소가 최신)
				if (json_result['msg_list'].length > 0) {
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

$("#btn_clas_ok").click(function(){
	// 오늘 날짜와 출석 날짜 비교
	var today = new Date().toISOString().split('T')[0]; // YYYY-MM-DD 형식
	var attdYmd = $('#pt_chk_attd_ymd').val();
	/*
	if (today !== attdYmd) {
		Swal.fire({
			title: '알림',
			text: '수업체크는 당일만 가능합니다.',
			icon: 'warning',
			confirmButtonColor: '#dc3545',
			confirmButtonText: '확인',
			customClass: {
				popup: 'swal-mobile-popup',
				title: 'swal-mobile-title',
				content: 'swal-mobile-content'
			}
		});
		return;
	}
	*/
	Swal.fire({
		title: '수업체크',
		text: '수업체크를 하시겠습니까?',
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#17a2b8',
		cancelButtonColor: '#6c757d',
		confirmButtonText: '확인',
		cancelButtonText: '취소',
		customClass: {
			popup: 'swal-mobile-popup',
			title: 'swal-mobile-title',
			content: 'swal-mobile-content'
		}
	}).then((result) => {
		if (!result.isConfirmed) {
			return;
		}
		
		var params = $("#form_pt_chk").serialize();
		jQuery.ajax({
			url: '/api/ajax_clas_ok',
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
					Swal.fire({
						title: '완료',
						text: json_result['msg'],
						icon: 'success',
						confirmButtonColor: '#17a2b8',
						confirmButtonText: '확인'
					}).then(() => {
						location.reload();
					});
				} else 
				{
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
});

$("#btn_clas_cancel").click(function(){
	Swal.fire({
		title: '수업취소',
		text: '정말로 회원수업취소 하시겠습니까?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#dc3545',
		cancelButtonColor: '#6c757d',
		confirmButtonText: '취소하기',
		cancelButtonText: '돌아가기',
		customClass: {
			popup: 'swal-mobile-popup',
			title: 'swal-mobile-title',
			content: 'swal-mobile-content'
		}
	}).then((result) => {
		if (!result.isConfirmed) {
			return;
		}
		
		var params = $("#form_pt_chk").serialize();
		jQuery.ajax({
			url: '/api/ajax_clas_cancel_mem',
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
					Swal.fire({
						title: '완료',
						text: json_result['msg'],
						icon: 'success',
						confirmButtonColor: '#17a2b8',
						confirmButtonText: '확인'
					}).then(() => {
						location.reload();
					});
				} else 
				{
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
});

// rn_br 함수 추가
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

// 새 메시지만 불러오는 함수
function loadNewMessages(buy_sno) {
	var params = "buy_sno=" + buy_sno;
	if (lastMessageTime) {
		params += "&last_time=" + encodeURIComponent(lastMessageTime);
		console.log("Checking for messages after: " + lastMessageTime);
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
			console.log("Received " + (json_result['msg_list'] ? json_result['msg_list'].length : 0) + " messages");
			if (json_result['result'] == 'true' && json_result['msg_list'].length > 0) {
				var cmsg = '';
				var scrollToBottom = false;
				
				// 현재 스크롤이 거의 바닥에 있는지 확인
				var msgBox = $('#clas_msg');
				if (msgBox.scrollTop() + msgBox.innerHeight() >= msgBox[0].scrollHeight - 50) {
					scrollToBottom = true;
				}
				
				// 새 메시지만 추가
				var hasNewMessages = false;
				var newestMessageTime = lastMessageTime;
				
				json_result['msg_list'].forEach(function(r, index) {
					// 기존 메시지와 중복 체크
					if (!lastMessageTime || r['CRE_DATETM'] > lastMessageTime) {
						hasNewMessages = true;
						// 가장 최신 메시지 시간 추적
						if (!newestMessageTime || r['CRE_DATETM'] > newestMessageTime) {
							newestMessageTime = r['CRE_DATETM'];
						}
						if (r['MEM_DV'] == 'T') {
							// 강사 메시지 (왼쪽)
cmsg += "<div class='direct-chat-msg' style='display:flex; margin-bottom:15px; align-items:flex-start; width:100%;'>";
cmsg += "    <img class='direct-chat-img' src='" + (r['STCHR_THUMB_IMG'] || '/dist/img/default_profile_' + (r['STCHR_GENDR'] || 'M') + '.png') + "' alt='강사사진' style='width:40px; height:40px; border-radius:50%; margin-right:10px; cursor:pointer; object-fit:cover; flex-shrink:0;' onclick='showFullPhoto(\"" + (r['STCHR_MAIN_IMG'] || r['STCHR_THUMB_IMG'] || '/dist/img/default_profile_' + (r['STCHR_GENDR'] || 'M') + '.png') + "\")' onerror='this.src=\"/dist/img/default_profile_" + (r['STCHR_GENDR'] || 'M') + ".png\"'>";
cmsg += "    <div style='flex:1;'>";
cmsg += "        <div class='direct-chat-infos' style='margin-bottom:2px;'>";
cmsg += "            <span class='direct-chat-name' style='font-size:0.75rem; color:#666;'>"+ r['STCHR_NM'] +" 강사</span>";
cmsg += "            <span class='direct-chat-timestamp' style='font-size:0.7rem; color:#999; margin-left:10px;'>"+ r['CRE_DATETM'] +"</span>";
cmsg += "        </div>";
cmsg += "        <div class='direct-chat-text' style='font-size:0.8rem; white-space: pre-wrap; display:inline-block;'>";
cmsg += rn_br(r['CLAS_DIARY_CONTS']);
cmsg += "        </div>";
cmsg += "    </div>";
cmsg += "</div>";
						} else {
							// 회원 메시지 (오른쪽)
cmsg += "<div class='direct-chat-msg right' style='display:flex; margin:0 0 15px 0; padding:0; align-items:flex-start; width:100%; flex-direction:row-reverse;'>";
cmsg += "    <img class='direct-chat-img' src='" + (r['MEM_THUMB_IMG'] || '/dist/img/default_profile_' + (r['MEM_GENDR'] || 'M') + '.png') + "' alt='회원사진' style='width:40px; height:40px; border-radius:50%; margin-left:10px; margin-right:0; cursor:pointer; object-fit:cover; flex-shrink:0;' onclick='showFullPhoto(\"" + (r['MEM_MAIN_IMG'] || r['MEM_THUMB_IMG'] || '/dist/img/default_profile_' + (r['MEM_GENDR'] || 'M') + '.png') + "\")' onerror='this.src=\"/dist/img/default_profile_" + (r['MEM_GENDR'] || 'M') + ".png\"'>";
cmsg += "    <div style='flex:1; display:flex; flex-direction:column; align-items:flex-end; margin-right:0;'>";
cmsg += "        <div class='direct-chat-infos' style='margin-bottom:2px;'>";
cmsg += "            <span class='direct-chat-timestamp' style='font-size:0.7rem; color:#999; margin-right:10px;'>"+ r['CRE_DATETM'] +"</span>";
cmsg += "            <span class='direct-chat-name' style='font-size:0.75rem; color:#666;'>"+ r['MEM_NM'] +" 회원</span>";
cmsg += "        </div>";
cmsg += "        <div class='direct-chat-text' style='font-size:0.8rem; white-space: pre-wrap; text-align:left;'>";
cmsg += rn_br(r['CLAS_DIARY_CONTS']);
cmsg += "        </div>";
cmsg += "    </div>";
cmsg += "</div>";
						}
					}
				});
				
				if (cmsg && hasNewMessages) {
					$('#clas_msg').append(cmsg);
					
					// 가장 최신 메시지 시간으로 업데이트
					lastMessageTime = newestMessageTime;
					
					// 스크롤이 바닥에 있었다면 새 메시지로 스크롤
					if (scrollToBottom) {
						msgBox.scrollTop(msgBox[0].scrollHeight);
					}
				}
			}
		}
	});
}

$("#btn_clas_comment").click(function(){

	if ( $('#clas_conts').val() == '' )
	{
		alertToast('error','수업 내용을 입력하세요.');
		return;
	}
	
	var params = $("#form_pt_chk").serialize();
    jQuery.ajax({
        url: '/api/ajax_clas_diary_insert_proc',
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
				pt_chk($('#pt_chk_buy_sno').val(), $('#pt_chk_attd_mgmt_sno').val(), $('#pt_chk_attd_ymd').val());
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



$(".bottom-menu").click(function(){
	$(".overlay").show();
 	var h_size = $(window).height();
 	$('#bottom-menu-area').css("height",h_size+"px");
 	
// 	var h_re = $(window).scrollTop() + 100;
// 	$(".overlay").animate({top: h_re+'px'});
});

$("#bottom-menu-close").click(function(){
	$('.content').removeClass('modal-open');
	// 인터벌 정리
	if (chatInterval) {
		clearInterval(chatInterval);
		chatInterval = null;
	}
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