<style>

.overlay {
  z-index:1001;
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

.mobile_back
{
    display:none;
}
.mobile_back_v
{
    display:block !important;
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
</style>


<?php
$sDef = SpoqDef();
?>

<!-- Main content -->
<section class="content">
	<div class="container-fluid ">
	
		<div class="row ama-header1">
            <div class="ama-header-card">
                <div class="ama-title">[ <?php echo $_SESSION['comp_nm']?> ] <?php echo $_SESSION['bcoff_nm']?></div>
                <div class="ama-message"><?php echo $_SESSION['user_name']?> 회원님 환영합니다.</div>
                <div class="ama-message-box">
                    <span class="ama-icon">🔊</span>
                    수업 횟수가 3회 남았습니다.
                </div>
            </div>
        </div>
        
    	<div class="row ama-header2">
	
            <div class="col-md-12">
            
            	<div class="stats-container col-md-12" onclick="location.href='/api/event_list';">
                    <div class="stat-item">
                        <div class="number reservation"><?php echo count($event_list1)?></div>
                        <div class="number-label">예약</div>
                    </div>
                    <div class="stat-item">
                        <div class="number used"><?php echo count($event_list2)?></div>
                        <div class="number-label">이용</div>
                    </div>
                    <div class="stat-item" style="display:none">
                        <div class="number completed"><?php echo count($event_list3)?></div>
                        <div class="number-label">종료</div>
                    </div>
                    <div class="stat-item">
                        <div class="number recommended"><?php echo count($send_list)?></div>
                        <div class="number-label">추천</div>
                    </div>
                </div>
            
            </div>
        </div>
        
        
        <div class="row">
		
            <div class="col-md-12 mt10">
            
            	<div class="stats-container col-md-12" onclick="location.href='/api/event_list';">
                    <div class="stat-item">
						<div class="number-label">예약</div>
                        <div class="number reservation"><?php echo count($event_list1)?></div>                      
                    </div>
                    <div class="stat-item"> 
						<div class="number-label">이용</div>
                        <div class="number used"><?php echo count($event_list2)?></div>
                    </div>
                    <div class="stat-item" style="display:none">
						<div class="number-label">종료</div>
                        <div class="number completed"><?php echo count($event_list3)?></div>
                    </div>
                    <div class="stat-item">
						<div class="number-label">추천</div>
                        <div class="number recommended"><?php echo count($send_list)?></div>
                    </div>
                </div>
            
            </div>
        </div>
    </div>

        <!-- 광고 슬라이드 -->
        <div id="carouselExampleIndicators" class="carousel slide mb15" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                	<img class="d-block w-100" style='height:140px;' src="/dist/img/banner004.png" alt="First slide">
                </div>
                <div class="carousel-item">
                	<img class="d-block w-100" style='height:140px;' src="/dist/img/banner005.jpg" alt="Second slide">
                </div>
                <div class="carousel-item">
                	<img class="d-block w-100" style='height:140px;' src="/dist/img/banner006.png" alt="Third slide">
                </div>
            </div>
        </div>
        <!-- -->

        <div class="container-fluid ">
        <div class="row">
            <div class="col-md-12 mt20">
            	<div class='a-title'>회원 공지사항</div>
            	<?php if ( count($list_notice) > 0 ) :?>
            	<?php foreach ($list_notice as $r) :?>
            	<div class="a-list">
                    <div class="a-item">
                    	<div class='a-item-sec item-center item-bold ft-sky'>
                    		<span class="notice-title-word"><?php echo $r['NOTI_TITLE']?></span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<span class="ft-default notice-word">
                    		<?php echo $r['NOTI_CONTS']?>
                    		</span>
                    	</div>
                    	<div class="a-item-line"></div>
                    </div>
                    
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<span class="ft-default">등록일 : <?php echo substr($r['CRE_DATETM'],0,10)?></span>
                    		<div class='item-btn-area'>
                    			<div class="btn bga-cate" onclick="pt_clas_msg('<?php echo $r['NOTI_SNO']?>','noti');">더보기</div>
                    		</div>
                    	</div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else : ?>
                <div class="a-list">
                    <div class="a-item text-center" style='height:50px;margin-top:20px;'>
                    	등록된 공지 사항이 없습니다.
                    </div>
                </div>
                <?php endif;?>
            </div>
        </div>
        
        <div class="row mt20">
            <div class="col-md-12">
            	
            	<div class='a-title'>그룹 스케쥴</div>
            	
            	<?php if ( count($list_gx_group) > 0) :?>
            	
            	<div class='sch-container'>
                	<div class='sch-left'>
                		<ul>
                			<li>-</li>
                			<?php for($i=9;$i<23;$i++) :?>
                			<li><?php echo sprintf('%02d',$i)?>:00</li>
                			<?php endfor;?>
                		</ul>
                	</div>
                	
                	<div class='sch-right'>
                		<?php
                		foreach($list_gx_group as $r) :
                		
                		$disp_gx_clas_title = $r['GX_CLAS_TITLE'];
                		$split_gx_clas_title = explode('(',$r['GX_CLAS_TITLE']);
                		
                		if ( count($split_gx_clas_title) > 0) $disp_gx_clas_title = $split_gx_clas_title[0];
                		
                    	?>
                		<div class='sch-item'>
                			<ul>
                				<li class='sch-title'><?php echo "[" . $r['GX_ROOM_TITLE'] . "] " . $disp_gx_clas_title?></li>
                				<?php for($i=9;$i<23;$i++) :?>
                				
                				<?php
                				if ( isset( $list_gx[$r['GX_ROOM_MGMT_SNO']][$r['GX_CLAS_TITLE']][sprintf('%02d',$i)] ))
                				{
                				    $obj = $list_gx[$r['GX_ROOM_MGMT_SNO']][$r['GX_CLAS_TITLE']][sprintf('%02d',$i)];
                				?>
                				
                				<li>
                					<div class='schedule-item'>
                						<?php echo $obj['GX_STCHR_NM']?> : 
                						<span class="schedule-time"><?php echo substr($obj['GX_CLAS_S_HH_II'],0,5)?>~<?php echo substr($obj['GX_CLAS_E_HH_II'],0,5)?></span>
                						<div class="arrow-container"></div>
                					</div>
                				</li>
                				<?php } else { ?>
                				<li>
                					<div class='schedule-item2'>
                						&nbsp;
                						<span class="schedule-time">&nbsp;</span>
                						<div class="arrow-container2"></div>
                					</div>
                				</li>
                				<?php } ?>
                				<?php endfor; ?>
                			</ul>
                		</div>
                		<?php endforeach; ?>
                	</div>
            	</div>
            	
            	<?php else :?>
            	<div class="a-list">
                    <div class="a-item text-center" style='height:50px;margin-top:20px;'>
                    	오늘의 그룹 스케쥴이 없습니다.
                    </div>
                </div>
            	<?php endif;?>
            	
        	</div>
        </div>
        
        
        <div class="row">
            <div class="col-md-12 mt20">
            	
            	
            	<div class='a-title'>추천상품 현황</div>
            	<?php if ( count($send_list) > 0) :?>
            	<?php foreach ($send_list as $r) :
            	
                $acc_rtrct_class = "ft-sky"; // 일장불가일 경우 빨간색 , 아닐경우 하늘색
            	$disp_prod_cnt = "";
            	
            	if ($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22")
            	{
            	    $disp_prod_cnt = $r['CLAS_CNT']."회";
            	} else 
            	{
            	    $disp_prod_cnt = disp_produnit($r['USE_PROD'],$r['USE_UNIT']);
            	}
                
                if ($r['ACC_RTRCT_MTHD'] == "99") $acc_rtrct_class = "ft-red";
            	
            	?>
                <div class="a-list">
                    <div class="a-item">
                    	<div class='a-item-sec item-bold <?php echo $acc_rtrct_class?>'>
                    		- <?php echo $sDef['ACC_RTRCT_DV'][$r['ACC_RTRCT_DV']]?> (<?php echo $disp_prod_cnt?>)
                    		<span class="item-bold ft-default">추천강사 : <?php echo $r['PTCHR_NM']?></span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec item-center '>
                    		- <?php echo $r['SELL_EVENT_NM']?>
                    		<span class="item-cancel">
                    			<?php if ($r['ORI_SELL_AMT'] != $r['SELL_AMT']) : ?>
                    			<?php echo number_format($r['ORI_SELL_AMT'])?> 원
                    			<?php endif;?>
                    		</span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<span class="ft-blue item-bold">
                    			<span class="ft-red item-light"><span class='imp-btn'>임박</span><?php echo $r['SEND_BUY_E_DATE']?> 까지</span>
                    		</span>
                    		<span class="bold"><?php echo number_format($r['SELL_AMT'])?> 원</span>
                    	</div>
                    	<div class="a-item-line"></div>
                    </div>
                    <div class="a-item mt5">
                    	<div class='a-item-sec'>
                    		<div class='item-btn-area'>
                    			<div class="cate bga-cate"><?php echo $cate_nm[$r['1RD_CATE_CD']][$r['2RD_CATE_CD']]?></div>
                    		</div>
                    		<div class='item-btn-area'>
                    			<div class="btn bga-blue" onclick="send_event('<?php echo $r['SELL_EVENT_SNO']?>','<?php echo $r['SEND_EVENT_MGMT_SNO']?>');">구매하기</div>
                    		</div>
                    	</div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else :?>
            	<div class="a-list">
                    <div class="a-item text-center" style='height:50px;margin-top:20px;'>
                    	추천 상품이 없습니다.
                    </div>
                </div>
            	<?php endif;?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 mt20">
            	
            	
            	<div class='a-title'>이용중인 상품 현황</div>
            	<?php if ( count($event_list2) > 0) :?>
            	<?php foreach ($event_list2 as $r) :
            	
            	$acc_rtrct_class = "ft-blue"; // 일장불가일 경우 빨간색 , 아닐경우 하늘색
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
                    	<div class='a-item-sec'>
                    		<div class='item-btn-area'>
                    			<div class="cate bga-cate"><?php echo $cate_nm[$r['1RD_CATE_CD']][$r['2RD_CATE_CD']]?></div>
                    		</div>
                    		<div class='item-btn-area'>
                    			<div class="btn bga-purple" <?php echo $disp_none_style?> onclick="pt_clas_msg('<?php echo $r['BUY_EVENT_SNO']?>','pt');">수업일지</div>
                    			<div class="btn bga-main"  onclick="buy_event('<?php echo $r['SELL_EVENT_SNO']?>');" style="display:none;">재구매</div>
                    		</div>
                    	</div>
					</div>
                    <div class="a-item mt10">
                    	<div class='a-item-sec item-center item-bold <?php echo $acc_rtrct_class?>'>
                    		- <?php echo $sDef['ACC_RTRCT_DV'][$r['ACC_RTRCT_DV']]?> (<?php echo $disp_prod_cnt?>)
                    		<span class="item-bold ft-default"><?php echo $disp_right_word?></span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec item-center '>
                    		- <?php echo $r['SELL_EVENT_NM']?> (수업강사:<?php echo $r['STCHR_NM']?>, 판매강사:<?php echo $r['PTCHR_NM']?>)
                    		 <span class="item-cancel">
                    			<?php if ($r['REAL_SELL_AMT'] != $r['BUY_AMT']) : ?>
                    			<?php echo number_format($r['REAL_SELL_AMT'])?> 원
                    			<?php endif;?>
                    		</span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<span class="ft-blue item-bold">
                    			- <span class="ft-blue item-light"><?php echo $disp_center_word?></span>
                    		</span>
                    		<span class="bold" style="display:none;"><?php echo number_format($r['BUY_AMT'])?> 원</span>
                    	</div>
                    	<div class="a-item-line"></div>
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
        
        
        
        
        
        <!-- 
        <div class="row">
            <div class="col-md-12">
            	<div class='a-title'>휴회 현황</div>
            	
            	<div class="a-list">
                    <div class="a-item">
                    	<div class='a-item-sec item-center item-bold ft-sky'>
                    		휴회 진행중
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<span class="ft-default">
                    			<span class="">2024-09-01 ~ 2024-11-30</span>
                    		</span>
                    		<span class="ft-blue item-bold">3일간</span>
                    	</div>
                    	<div class="a-item-line"></div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<span class="ft-default">
                    			<span class="">신청일 : 2024-01-15</span>
                    		</span>
                    		<div class='item-btn-area'>
                    			<div class="btn bga-red">취소하기</div>
                    		</div>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
            	<div class="a-warning">
                    <div class="a-item">
                    	<div class='a-item-sec item-center item-bold ft-red'>
                    		유의사항
                    		<span class="item-bold ft-default item-light">X</span>
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<div class='' style='font-size:0.9rem'>
                        		<div>- 휴회 신청은 횟수나 일자가 있을 때 가능 합니다.</div>
                        		<div>- 휴회 신청은 횟수나 일자가 있을 때 가능 합니다.</div>
                        		<div>- 휴회 신청은 횟수나 일자가 있을 때 가능 합니다.</div>
                        		<div>- 휴회 신청은 횟수나 일자가 있을 때 가능 합니다.</div>
                        		<div>- 휴회 신청은 횟수나 일자가 있을 때 가능 합니다.</div>
                    		</div>
                    		
                    	</div>
                    </div>
                </div>
            </div>
        </div>
         -->
        
    </div>
    
    
<div class="overlay">
    <div class="row">
    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area">
    			
                <button type="button" class="close" id="bottom-menu-close" style="margin-right:10px;margin-top:5px;">&times;</button>
                <br />
                <div class='bottom-title text-center' id='btitle'>수업 메세지</div>
                <div class='bottom-content' style='margin-top:15px;'>
                
                    <div class="card-body" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-direction: column;">
						
						<div id='btype_pt' style="display: flex; flex-direction: column; height: 100%;">
						<form id='form_pt_chk' style="display: contents;">
						<input type='hidden' name='buy_sno' id='pt_chk_buy_sno' />

                        <div class="direct-chat-messages" id="clas_msg" style="flex: 1; overflow-y: auto; margin-bottom: 10px;">
                        </div>
                        
						<div class="input-group input-group-sm" style="flex-shrink: 0; padding-bottom: 20px;">
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
</div>



    
<input type="hidden" id="fmode" value="<?php echo $first_mode?>" />
<input type="hidden" id="user_id" value="<?php echo $_SESSION['user_id']?>" />    
</section>

<?=$jsinc ?>

<script>
  document.addEventListener("scroll", () => {
    const scrollY = window.scrollY;
    document.body.classList.toggle("scrolled", scrollY > 170);
  });
  
  
$(function () {
    $('.select2').select2();
    user_set();
    
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
            // Resize 시에는 + 40을 사용 (초기 로드 시보다 10px 더 많이)
            var closeHeight = $('#bottom-menu-close').outerHeight(true) + 40;
            var availableHeight = h_size - closeHeight;
            $('#bottom-menu-area').css("height", availableHeight + "px");
        }
    });
})

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

function send_event(sell_event_sno,send_sno)
{
	ToastConfirm.fire({
        // icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >추천 상품을 구매하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
			location.href="/api/event_buy_info/"+sell_event_sno+"/"+send_sno;			
    	}
    });
}

function buy_event(sell_event_sno)
{
	ToastConfirm.fire({
        // icon: "question",
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


var chatInterval; // 전역 변수로 인터벌 저장
var lastMessageTime = null; // 마지막 메시지 시간 저장

function pt_clas_msg(buy_sno,mtype)
{
// 	$(".overlay").show();
	
	document.body.style.overflow = 'hidden';
 	$('.content').addClass('modal-open');
 	
 	// 초기 로드 시 높이 설정
 	var h_size = $(window).height();
 	$('#bottom-menu-area').css("height", h_size + "px");
 	
 	// close 버튼과 여백의 실제 높이를 계산 (초기 로드 시 + 30 사용)
 	var closeHeight = $('#bottom-menu-close').outerHeight(true) + 30;
 	$('#bottom-content').css("height", (h_size - closeHeight) + "px");
 	
 	if (mtype == 'pt')
 	{
 		$('#btitle').text('수업 메세지');
 		$('#btype_pt').show();
 		
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
    					// 강사 메시지 (오른쪽으로 변경)
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
    					// 회원 메시지 (왼쪽으로 변경)
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
 	} else 
 	{
 		$('#btitle').text('공지사항');
 		$('#btype_pt').hide();
 		
 		var params = "noti_sno="+buy_sno;
    	jQuery.ajax({
            url: '/api/ajax_mmmain_get_tnotice_detail',
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
                    
                    $('#clas_msg').html(addHtml);
    				
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
							// 강사 메시지 (오른쪽으로 변경)
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
							// 회원 메시지 (왼쪽으로 변경)
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
	document.body.style.overflow = '';
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