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
	<div class="container-fluid">
	
	
        <div class="row" id='status-info' onclick="event_detail_info();">
            <div class="col-lg-3 col-4">
                <div class="small-box bg-info" style='margin:5px'>
                <div class="inner">
                <h3><?php echo count($event_list1)?><sup style="font-size: 20px">개</sup></h3>
                <p>예약상품</p>
                </div>
                <div class="icon">
                <i class="fas fa-shopping-cart"></i>
                </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-4">
                <div class="small-box " style='margin:5px'>
                <div class="inner">
                <h3><?php echo count($event_list2)?><sup style="font-size: 20px">개</sup></h3>
                <p>이용상품</p>
                </div>
                <div class="icon">
                <i class="ion ion-stats-bars"></i>
                </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-4">
                <div class="small-box bg-danger" style='margin:5px'>
                <div class="inner">
                <h3><?php echo count($event_list3)?><sup style="font-size: 20px">개</sup></h3>
                <p>종료상품</p>
                </div>
                <div class="icon">
                <i class="ion ion-stats-bars"></i>
                </div>
                </div>
            </div>
            
        </div>
        
        <div class="row">
    		<div class="col-md-12 card card-olive">
    			<div class="card-header">
					<h3 class="card-title">기본정보</h3>
					<div style="text-align:right">
						<button type='button' class='btn btn-xs btn-default' onclick="direct_attd('<?php echo $mem_info['MEM_SNO']?>');">수동 출석하기</button>
					</div>
				</div>
    		
				<div class="card-body p-0">
					<table class="table table-bordered table-sm col-md-12" style='width:100%;'>
						
						<tbody>
							<tr>
								<td>회원정보</td>
								<td style="text-align:right">
									<?php ($mem_info['MEM_GENDR'] == "M") ? $disp_gendr="<font color='blue'><i class='fa fa-mars'></i></font>" : $disp_gendr="<font color='red'><i class='fa fa-venus'></i></font>"; ?> 
									<?php echo $disp_gendr ?>
									<?php echo $mem_info['MEM_NM'] ?> (<?php echo disp_date_kr($mem_info['BTHDAY']) ?> , <?php echo disp_age($mem_info['BTHDAY'])?>세)
								</td>
							</tr>
							<tr>
								<td>아이디</td>
								<td style="text-align:right"><?php echo $mem_info['MEM_ID'] ?></td>
							</tr>
							<tr>
								<td>전화번호</td>
								<td style="text-align:right"><?php echo disp_phone($mem_info['MEM_TELNO'])?></td>
							</tr>
							<tr>
								<td>락커</td>
								<td style="text-align:right">
								<?php foreach ($lockr_01 as $r1) :?>
								<?php echo $r1['LOCKR_NO']?>번&nbsp;&nbsp;
								<?php endforeach;?>
								</td>
							</tr>
							<tr>
								<td>골프라카</td>
								<td style="text-align:right">
								<?php foreach ($lockr_02 as $r2) :?>
								<?php echo $r2['LOCKR_NO']?>번&nbsp;&nbsp;
								<?php endforeach;?>
								</td>
							</tr>
							<tr>
								<td>매출액</td>
								<td style="text-align:right"><?php echo number_format($rank_info['sum_paymt_amt'])?> 원</td>
							</tr>
							<tr>
								<td>매출순위</td>
								<td style="text-align:right"><?php echo number_format($rank_info['paymt_ranking'])?> 위</td>
							</tr>
							<tr>
								<td>가일입</td>
								<td style="text-align:right"><?php echo $mem_info['JON_DATETM']?></td>
							</tr>
							<tr>
								<td>가입장소</td>
								<td style="text-align:right"><?php echo $sDef['JON_PLACE'][$mem_info['JON_PLACE']]?></td>
							</tr>
							<tr>
								<td>등록일</td>
								<td style="text-align:right"><?php echo $mem_info['REG_DATETM']?></td>
							</tr>
							<tr>
								<td>등록장소</td>
								<td style="text-align:right"><?php echo $sDef['REG_PLACE'][$mem_info['REG_PLACE']]?></td>
							</tr>
						</tbody>
					</table>
				</div>
    		
    			<button type='button' class='btn btn-sm btn-info' onclick="send_buyProduct('<?php echo $mem_info['MEM_SNO']?>');">상품 추천하기</button>
    		
    		</div>
    	</div>
    	
    	<div class="row">
    		<div class="col-md-12 card card-olive">
    			<div class="card-header">
					<h3 class="card-title" style='margin-top:5px;'>메모 정보</h3>
					<div style="text-align:right">
						<button type='button' class='btn btn-xs btn-default' onclick="tmem_mem_insert();">메모 등록하기</button>
					</div>
				</div>
    		
    			<div class="card-body p-0">
					<table class="table table-sm col-md-12">
						
						<tbody>
							<?php
							$short_cut_i = 0;
							if( count($memo_list) > 0) :
							foreach ($memo_list as $m) :
							
							$bb_td_class = "bg-gray";
							if ($m['PRIO_SET'] == "Y") $bb_td_class = "bg-pink";
							?>
							<tr>
							<?php if($m['PRIO_SET'] == "Y") :?>
								<td style='width:80px'  class="memo-text-xs <?php echo $bb_td_class?> text-center" onclick="change_memo_prio_set('N','<?php echo $m['MEMO_MGMT_SNO']?>');">[중요메모] </td>
							<?php else :?>
								<td style='width:80px'  class="memo-text-xs <?php echo $bb_td_class?> text-center" onclick="change_memo_prio_set('Y','<?php echo $m['MEMO_MGMT_SNO']?>');">[메모등록] </td>
							<?php endif;?>
								<td style='' class="memo-text-xs <?php echo $bb_td_class?>"><?php echo $m['CRE_DATETM']?></td>
							</tr>
							<tr>
								<td colspan='2' class="memo-text-xs" title="<?php echo $m['MEMO_CONTS']?>"><span style="font-size:0.8rem;"><?php echo $m['MEMO_CONTS']?></span></td>
							</tr>
							<?php 
							if ($short_cut_i == 5) break;
							$short_cut_i++;
							endforeach;
							else:
							?>
							<tr>
								<td class='text-center'>
									메모내역이 없습니다.
								</td>
							</tr>
							<?php endif;?>
							
						</tbody>
					</table>
				</div>
    		
    		
    		</div>
    	</div>
    	
    	<div class="row">
    		<div class="col-md-12 card card-olive">
    			<div class="card-header">
					<h3 class="card-title">출석현황</h3>
				</div>
    			<div class="card-body p-0">
    				<?php
    				// 두날짜 사이의 날수를 계산한다.
    				$attd_diff_days = disp_diff_date($attd_info['sdate'],$attd_info['edate']) + 1;
    				// 출석 퍼센트를 구한다.
    				$attd_per = round(($attd_info['count'] / $attd_diff_days) * 100);
    				
    				?>
    				<table class="table table-bordered table-striped col-md-12">
    					<tr>
    						<td style='text-align:center'><?php echo date('Y-m-d',strtotime($attd_info['sdate']))?> ~</td>
    						<td style='text-align:center'>&nbsp;&nbsp;<?php echo date('Y-m-d',strtotime($attd_info['edate']))?></td>
    					</tr>
    					<tr>
    						<td style='text-align:center'><?php echo $attd_per?>%</td>
    						<td style='text-align:center'><?php echo $attd_diff_days ?>일(<?php echo $attd_info['count']?>일)</td>
    					</tr>
    				</table>
    				<div class='row p-3'>
    				
    					<?php foreach ($attd_list as $a) : ?>
    					
    						<?php if($a['ATTD_YN'] == "Y") : ?>
    							<?php ($a['AUTO_CHK'] == "Y") ? $auto_chk = "color:red" : $auto_chk = ""; ?>
    							<div class='col-6 attd-div'><div class='attd-text'><small class="badge attd-num"></small><small class="badge badge-success bd-fsize"><i class="far fa-clock"></i>정</small> <span style="<?php echo $auto_chk?>;font-size:0.9rem;"><?php echo substr($a['CRE_DATETM'],0,16)?></span></div></div>
    						<?php else : ?>
    							<div class='col-6 attd-div'><div class='attd-text'><small class="badge attd-num"></small><small class="badge badge-warning bd-fsize"><i class="far fa-clock"></i>재</small> <span style="font-size:0.9rem;"><?php echo substr($a['CRE_DATETM'],0,16)?></span></div></div>
    						<?php endif; ?>
    					<?php endforeach; ?>
    					
    				</div>
    				
    			</div>
    		</div>
    	</div>
		
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ============================= [ modal-default START ] ======================================= -->	
<form name='form_memo_insert' id='form_mem_insert'>
<input type="hidden" name="memo_mem_sno" id="memo_mem_sno" value="<?php echo $mem_info['MEM_SNO'] ?>" />
<div class="modal fade" id="modal_memo_insert" style='top:55px;'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">메모 등록하기</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
            	
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:120px'>중요메모 설정</span>
                	</span>
                	
                	<div style='margin-top:4px;margin-left:5px;'>
                    	<div class="icheck-primary d-inline">
                            <input type="radio" id="memoImp1" name="memo_prio_set" value="N" checked>
                            <label for="memoImp1">
                            	<small>일반</small>
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="memoImp2" name="memo_prio_set" value="Y">
                            <label for="memoImp2">
                            	<small>중요</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<textarea rows='5' class="form-control" name="memo_content" id="memo_content"></textarea>
                </div>
            	
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-sm btn-success" onclick="btn_memo_insert();">메모 등록하기</button>
            </div>
        </div>
    </div>
</div>
</form>
<!-- ============================= [ modal-default END ] ======================================= -->
<!-- ############################## MODAL [ END ] ###################################### -->

<div class="overlay">
    <div class="row">
    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area">
                <button type="button" class="close" id="bottom-menu-close" style="margin-right:10px;margin-top:5px;">&times;</button>
                <br />
                <div class='bottom-title text-center'>상품 현황</div>
                <div class='bottom-content'>
                <div class="direct-chat-messages" id="clas_msg">    
                    
                    
            		<div class="row">
            			<div class="col-md-12">
                            
                            <div class="card card-info">
            				
                				<!-- CARD HEADER [START] -->
                				<div class="card-header">
                					<h3 class="card-title">예약 상품 현황</h3>
                				</div>
                				<!-- CARD HEADER [END] -->
                			
                                <div class="card-body p-0">
                                    <ul class="products-list product-list-in-card pl-2 pr-2">
                                    	<?php if (count($event_list1) > 0) :?>
                                    	<?php foreach ($event_list1 as $r) :?>
                                        <li class="item">
                                            
                                            <div class="">
                                            <a href="javascript:void(0)" class="product-title">
                                            	<span class="badge badge-success"><?php echo $sDef['ACC_RTRCT_MTHD'][$r['ACC_RTRCT_MTHD']]?></span>
                                            	<?php echo $r['SELL_EVENT_NM']?>
                                            	<!-- <span class="badge float-right" style="text-decoration: line-through;color:red">300,000</span>  -->
                                            </a>
                                            <span class="product-description">
                                            	<?php if($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") :?>
                                            	<?php
                                            	$info1 = $r['ADD_SRVC_CLAS_CNT'] +  $r['CLAS_CNT']; // 총 부여 갯수
                                            	$info2 = $r['SRVC_CLAS_PRGS_CNT'] +  $r['MEM_REGUL_CLAS_PRGS_CNT']; // 총 사용 갯수
                                            	$info3 = $info1 - $info2;
                                            	?>
                                            	<?php echo $info1?>회 중 <?php echo $info2?>회 이용 <span style="font-size:0.8rem;color:green">(<?php echo $r['STCHR_NM']?> 강사)</span>
                                            	<?php else:?>
                                            	<?php echo disp_produnit($r['USE_PROD'],$r['USE_UNIT'])?> <span style="font-size:0.8rem;color:green"></span>
                                            	<?php endif;?>
                                            	<span class="badge badge-info float-right"><?php echo number_format($r['REAL_SELL_AMT'])?></span>
                                            </span>
                                            <span style='float:right'>
                                            	<div class="btn-group">
        											<button type="button" class="btn btn-success btn-xs dropdown-toggle dropdown-icon" data-toggle="dropdown">설정하기
        												<span class="sr-only">Toggle Dropdown</span>
        											</button>
    												<div class="dropdown-menu text-sm" role="menu">
    													<a class="dropdown-item" onclick="change_domcy_day('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['DOMCY_POSS_EVENT_YN'] ?>');">휴회일 추가</a>
    													<a class="dropdown-item" onclick="change_domcy_cnt('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['DOMCY_POSS_EVENT_YN'] ?>');">휴회횟수 추가</a>
    													<a class="dropdown-item" onclick="change_clas_cnt('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['CLAS_DV']?>');">수업추가</a>
    												<div class="dropdown-divider"></div>
    													<a class="dropdown-item" onclick="change_exr_s_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['CLAS_DV']?>','<?php echo $r['EXR_S_DATE']?>');">운동시작일</a>
    													<a class="dropdown-item" onclick="change_exr_e_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['CLAS_DV']?>','<?php echo $r['EXR_E_DATE']?>');">운동종료일</a>
    												<!-- 
    												<div class="dropdown-divider"></div>
    													<a class="dropdown-item" onclick="change_stchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['STCHR_ID'] ?>','<?php echo $r['CLAS_DV']?>');">수업강사</a>
    													<a class="dropdown-item" onclick="change_ptchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['PTCHR_ID'] ?>','<?php echo substr($r['BUY_DATETM'],0,10)?>');">판매강사</a>
    												<div class="dropdown-divider"></div>
    													<a class="dropdown-item" onclick="change_event_trans('<?php echo $r['MEM_SNO'] ?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">양도하기</a>
    													<a class="dropdown-item" onclick="change_event_refund('<?php echo $r['MEM_SNO']?>','<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>');">환불하기</a>
    												 -->
    													<a class="dropdown-item" onclick="change_event_just_end('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>');">강제종료</a>
    												<div class="dropdown-divider"></div>
    													<a class="dropdown-item" onclick="test_clas_chk('<?php echo $r['STCHR_ID']?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">수업하기(TEST)</a>
    												</div>
        										</div>
                                            </span>
                                            <span style="font-size:0.8rem;color:blue">
                                            <?php echo $r['EXR_S_DATE']?> ~ <?php echo $r['EXR_E_DATE']?>
                                            </span>
                                            </div>
                                        </li>
                                    	<?php endforeach; ?>
                                    	<?php else : ?>
                                			<li class="item" style='font-size:0.9rem;'>예약 상품이 없습니다.</li>
            							<?php endif; ?>
                                    </ul>
                                </div>
                                
                                			
            				</div>
                            			
            			</div>
            		</div>
            		
            		<div class="row" style='margin-top:20px'>
            			<div class="col-md-12">
                            
                            <div class="card card-primary">
            				
                				<!-- CARD HEADER [START] -->
                				<div class="card-header">
                					<h3 class="card-title">이용 상품 현황</h3>
                				</div>
                				<!-- CARD HEADER [END] -->
                			
                                <div class="card-body p-0">
                                    <ul class="products-list product-list-in-card pl-2 pr-2">
                                    	<?php if (count($event_list2) > 0) :?>
                                        <?php foreach ($event_list2 as $r) :?>
                                        <li class="item">
                                            
                                            <div class="">
                                            <a href="javascript:void(0)" class="product-title">
                                            	<span class="badge badge-success"><?php echo $sDef['ACC_RTRCT_MTHD'][$r['ACC_RTRCT_MTHD']]?></span>
                                            	<?php echo $r['SELL_EVENT_NM']?>
                                            	<!-- <span class="badge float-right" style="text-decoration: line-through;color:red">300,000</span>  -->
                                            </a>
                                            <span class="product-description">
                                            	<?php if($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") :?>
                                            	<?php
                                            	$info1 = $r['ADD_SRVC_CLAS_CNT'] +  $r['CLAS_CNT']; // 총 부여 갯수
                                            	$info2 = $r['SRVC_CLAS_PRGS_CNT'] +  $r['MEM_REGUL_CLAS_PRGS_CNT']; // 총 사용 갯수
                                            	$info3 = $info1 - $info2;
                                            	?>
                                            	<?php echo $info1?>회 중 <?php echo $info2?>회 이용 <span style="font-size:0.8rem;color:green">(<?php echo $r['STCHR_NM']?> 강사)</span>
                                            	<?php else:?>
                                            	<?php echo disp_produnit($r['USE_PROD'],$r['USE_UNIT'])?> <span style="font-size:0.8rem;color:green"></span>
                                            	<?php endif;?>
                                            	<span class="badge badge-info float-right"><?php echo number_format($r['REAL_SELL_AMT'])?></span>
                                            </span>
                                            <span style='float:right'>
                                            	<div class="btn-group">
        											<button type="button" class="btn btn-success btn-xs dropdown-toggle dropdown-icon" data-toggle="dropdown">설정하기
        												<span class="sr-only">Toggle Dropdown</span>
        											</button>
    												<div class="dropdown-menu text-sm" role="menu">
    													<a class="dropdown-item" onclick="change_domcy_day('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['DOMCY_POSS_EVENT_YN'] ?>');">휴회일 추가</a>
    													<a class="dropdown-item" onclick="change_domcy_cnt('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['DOMCY_POSS_EVENT_YN'] ?>');">휴회횟수 추가</a>
    													<a class="dropdown-item" onclick="change_clas_cnt('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['CLAS_DV']?>');">수업추가</a>
    												<div class="dropdown-divider"></div>
    													<a class="dropdown-item" onclick="change_exr_s_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['CLAS_DV']?>','<?php echo $r['EXR_S_DATE']?>');">운동시작일</a>
    													<a class="dropdown-item" onclick="change_exr_e_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['CLAS_DV']?>','<?php echo $r['EXR_E_DATE']?>');">운동종료일</a>
    												<!-- 
    												<div class="dropdown-divider"></div>
    													<a class="dropdown-item" onclick="change_stchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['STCHR_ID'] ?>','<?php echo $r['CLAS_DV']?>');">수업강사</a>
    													<a class="dropdown-item" onclick="change_ptchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['PTCHR_ID'] ?>','<?php echo substr($r['BUY_DATETM'],0,10)?>');">판매강사</a>
    												<div class="dropdown-divider"></div>
    													<a class="dropdown-item" onclick="change_event_trans('<?php echo $r['MEM_SNO'] ?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">양도하기</a>
    													<a class="dropdown-item" onclick="change_event_refund('<?php echo $r['MEM_SNO']?>','<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>');">환불하기</a>
    												 -->
    													<a class="dropdown-item" onclick="change_event_just_end('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>');">강제종료</a>
    												<div class="dropdown-divider"></div>
    													<a class="dropdown-item" onclick="test_clas_chk('<?php echo $r['STCHR_ID']?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">수업하기(TEST)</a>
    												</div>
        										</div>
                                            </span>
                                            <span style="font-size:0.8rem;color:blue">
                                            <?php echo $r['EXR_S_DATE']?> ~ <?php echo $r['EXR_E_DATE']?>
                                            </span>
                                            </div>
                                        </li>
                                    	<?php endforeach; ?>
                                    	<?php else : ?>
                                			<li class="item" style='font-size:0.9rem;'>이용중인 상품이 없습니다.</li>
            							<?php endif; ?>
                                    </ul>
                                </div>
                                
                                			
            				</div>
                            			
            			</div>
            		</div>
            		
            		<div class="row" style='margin-top:20px'>
            			<div class="col-md-12">
                            
                            <div class="card card-danger">
            				
                				<!-- CARD HEADER [START] -->
                				<div class="card-header">
                					<h3 class="card-title">종료 상품 현황</h3>
                				</div>
                				<!-- CARD HEADER [END] -->
                			
                                <div class="card-body p-0">
                                    <ul class="products-list product-list-in-card pl-2 pr-2">
                                    	<?php if (count($event_list3) > 0) :?>
                                        <?php foreach ($event_list3 as $r) :?>
                                        <li class="item">
                                           
                                            <div class="">
                                            <a href="javascript:void(0)" class="product-title">
                                            	<span class="badge badge-success"><?php echo $sDef['ACC_RTRCT_MTHD'][$r['ACC_RTRCT_MTHD']]?></span>
                                            	<?php echo $r['SELL_EVENT_NM']?>
                                            	<!-- <span class="badge float-right" style="text-decoration: line-through;color:red">300,000</span>  -->
                                            </a>
                                            <span class="product-description">
                                            	<?php if($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") :?>
                                            	<?php
                                            	$info1 = $r['ADD_SRVC_CLAS_CNT'] +  $r['CLAS_CNT']; // 총 부여 갯수
                                            	$info2 = $r['SRVC_CLAS_PRGS_CNT'] +  $r['MEM_REGUL_CLAS_PRGS_CNT']; // 총 사용 갯수
                                            	$info3 = $info1 - $info2;
                                            	?>
                                            	<?php echo $info1?>회 중 <?php echo $info2?>회 이용 <span style="font-size:0.8rem;color:green">(<?php echo $r['STCHR_NM']?> 강사)</span>
                                            	<?php else:?>
                                            	<?php echo disp_produnit($r['USE_PROD'],$r['USE_UNIT'])?> <span style="font-size:0.8rem;color:green"></span>
                                            	<?php endif;?>
                                            	<span class="badge badge-info float-right"><?php echo number_format($r['REAL_SELL_AMT'])?></span>
                                            </span>
                                            <span style="font-size:0.8rem;color:blue">
                                            <?php echo $sDef['EVENT_STAT_RSON'][$r['EVENT_STAT_RSON']]?>
                                            <!--  
                                            <?php echo $r['EXR_S_DATE']?> ~ <?php echo $r['EXR_E_DATE']?>
                                             -->
                                            </span>
                                            </div>
                                        </li>
                                    	<?php endforeach; ?>
                                    	<?php else : ?>
                                			<li class="item" style='font-size:0.9rem;'>종료된 상품이 없습니다.</li>
            							<?php endif; ?>
                                    
                                    </ul>
                                </div>
                                
                                			
            				</div>
                            			
            			</div>
            		</div>                  
                  
                
                </div>
            </div>
            </div>
    	</div>
    </div>
</div>

<!-- modal start -->

<!-- ============================= [ modal-sm START 운동시작일 변경 ] ============================================ -->
<div class="modal fade" id="modal_pop_sdate">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">운동 시작일 변경</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<input type="hidden" name="pop_sdate_buy_sno" id="pop_sdate_buy_sno" />
            	<div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:150px'>현재 운동시작일</span>
                	</span>
                	<input type="text" class="form-control" name="pop_sdate_info_sdate" id="pop_sdate_info_sdate" readonly>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>변경할 운동시작일</span>
                	</span>
                	<input type="text" class="form-control" name="pop_sdate_change_sdate" id="pop_sdate_change_sdate">
                </div>
            	
            	
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-sm btn-success" onclick="btn_change_sdate_submit();">변경하기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-sm END ] ============================================== -->

<!-- ============================= [ modal-sm START 운동종료일 변경 ] ============================================ -->
<div class="modal fade" id="modal_pop_edate">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">운동 종료일 변경</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<input type="hidden" name="pop_edate_buy_sno" id="pop_edate_buy_sno" />
            	<div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text input-readonly" style='width:150px'>현재 운동종료일</span>
                	</span>
                	<input type="text" class="form-control" name="pop_edate_info_edate" id="pop_edate_info_edate" readonly>
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>변경할 운동종료일</span>
                	</span>
                	<input type="text" class="form-control" name="pop_edate_change_edate" id="pop_edate_change_edate" onchange="edate_calu_days();">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>추가될 일수</span>
                	</span>
                	<input type="text" class="form-control" id="pop_edate_change_add_edate_cnt" readonly>
                </div>
            	
            	
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-sm btn-success" onclick="btn_change_edate_submit();">변경하기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-sm END ] ============================================== -->

<!-- ============================= [ modal-sm START 휴회일 추가 ] ============================================ -->
<div class="modal fade" id="modal_pop_domcy_day" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">휴회일 추가</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<input type="hidden" name="domcy_day_buy_sno" id="domcy_day_buy_sno" />
            	<div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>추가할 휴회일 수</span>
                	</span>
                	<input type="text" class="form-control" name="domcy_day_day" id="domcy_day_day">
                </div>
            	
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-sm btn-success" onclick="btn_domcy_day_submit();">변경하기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-sm END ] ============================================== -->

<!-- ============================= [ modal-sm START 휴회횟수 추가 ] ============================================ -->
<div class="modal fade" id="modal_pop_domcy_cnt">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">휴회횟수 추가</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<input type="hidden" name="domcy_cnt_buy_sno" id="domcy_cnt_buy_sno" />
            	<div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>추가할 휴회 횟수</span>
                	</span>
                	<input type="text" class="form-control" name="domcy_cnt_cnt" id="domcy_cnt_cnt">
                </div>
            	
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-sm btn-success" onclick="btn_domcy_cnt_submit();">변경하기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-sm END ] ============================================== -->

<!-- ============================= [ modal-sm START 수업 추가 ] ============================================ -->
<div class="modal fade" id="modal_pop_clas_cnt">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">수업 추가</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<input type="hidden" name="clas_cnt_buy_sno" id="clas_cnt_buy_sno" />
            	<div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>추가할 수업수</span>
                	</span>
                	<input type="text" class="form-control" name="clas_cnt_cnt" id="clas_cnt_cnt">
                </div>
            	
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-sm btn-success" onclick="btn_clas_cnt_submit();">변경하기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-sm END ] ============================================== -->

<!-- ============================= [ modal-sm START 수업강사 변경 ] ============================================ -->
<div class="modal fade" id="modal_pop_stchr">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">수업강사 변경</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
            	<input type="hidden" name="stchr_buy_sno" id="stchr_buy_sno" />
            	<div class="input-group input-group-sm mb-1">
                	<select class="select2 form-control" style="width: 250px;" name="ch_stchr_id" id="ch_stchr_id">
                		<option>강사 선택</option>
                	<?php foreach ($tchr_list as $r) : ?>
						<option value="<?php echo $r['MEM_ID']?>">[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?> </option>
					<?php endforeach; ?>
					</select>
                </div>
            	
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-sm btn-success" onclick="btn_stchr_submit();">변경하기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-sm END ] ============================================== -->

<!-- ============================= [ modal-sm START 판매강사 변경 ] ============================================ -->
<div class="modal fade" id="modal_pop_ptchr">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">판매강사 변경</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
            	<input type="hidden" name="ptchr_buy_sno" id="ptchr_buy_sno" />
            	<div class="input-group input-group-sm mb-1">
                	<select class="select2 form-control" style="width: 250px;" name="ch_ptchr_id" id="ch_ptchr_id">
                		<option>강사 선택</option>
                	<?php foreach ($tchr_list as $r) : ?>
						<option value="<?php echo $r['MEM_ID']?>">[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?> </option>
					<?php endforeach; ?>
					</select>
                </div>
            	
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-sm btn-success" onclick="btn_ptchr_submit();">변경하기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-sm END ] ============================================== -->

<form name="form_change_sdate" id="form_change_sdate" method="post" action="/ttotalmain/ajax_info_mem_change_sdate_proc">
	<input type="hidden" name="fc_sdate_buy_sno" id="fc_sdate_buy_sno" />
	<input type="hidden" name="fc_sdate_sdate" id="fc_sdate_sdate" />
</form>

<form name="form_change_edate" id="form_change_edate" method="post" action="/ttotalmain/ajax_info_mem_change_edate_proc">
	<input type="hidden" name="fc_edate_buy_sno" id="fc_edate_buy_sno" />
	<input type="hidden" name="fc_edate_edate" id="fc_edate_edate" />
</form>

<form name="form_domcy_day" id="form_domcy_day" method="post" action="/ttotalmain/ajax_info_mem_domcyday_proc">
	<input type="hidden" name="fc_domcy_day_buy_sno" id="fc_domcy_day_buy_sno" />
	<input type="hidden" name="fc_domcy_day_day" id="fc_domcy_day_day" />
</form>

<form name="form_domcy_cnt" id="form_domcy_cnt" method="post" action="/ttotalmain/ajax_info_mem_domcycnt_proc">
	<input type="hidden" name="fc_domcy_cnt_buy_sno" id="fc_domcy_cnt_buy_sno" />
	<input type="hidden" name="fc_domcy_cnt_cnt" id="fc_domcy_cnt_cnt" />
</form>

<form name="form_clas_cnt" id="form_clas_cnt" method="post" action="/ttotalmain/ajax_info_mem_clascnt_proc">
	<input type="hidden" name="fc_clas_cnt_buy_sno" id="fc_clas_cnt_buy_sno" />
	<input type="hidden" name="fc_clas_cnt_cnt" id="fc_clas_cnt_cnt" />
</form>

<form name="form_stchr" id="form_stchr" method="post" action="/ttotalmain/ajax_info_mem_stchr_proc">
	<input type="hidden" name="fc_stchr_buy_sno" id="fc_stchr_buy_sno" />
	<input type="hidden" name="fc_ch_stchr_id" id="fc_ch_stchr_id" />
</form>

<form name="form_ptchr" id="form_ptchr" method="post" action="/ttotalmain/ajax_info_mem_ptchr_proc">
	<input type="hidden" name="fc_ptchr_buy_sno" id="fc_ptchr_buy_sno" />
	<input type="hidden" name="fc_ch_ptchr_id" id="fc_ch_ptchr_id" />
</form>

<!--  modal end  -->



	
</section>

<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

// 수동출석
function direct_attd(mem_sno)
{
	var params = "mem_sno="+mem_sno;
	jQuery.ajax({
        url: '/api/ajax_direct_attd',
        type: 'POST',
        data:params,
        async: false,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
        	if ( result.substr(0,8) == '<script>' )
        	{
        		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [011]');
        		location.href='/togin';
        		return;
        	}
        	
			json_result = $.parseJSON(result);
			console.log(json_result);
			if (json_result['result'] == 'true')
			{
				alert( json_result['msg'] );
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
    	alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [022]');
		location.href='/login';
		return;
    });
}

// 상품 추천하기
function send_buyProduct(sno)
{
	location.href="/api/tmem_send1/"+sno;
}


// 메모 모달 띄우기
function tmem_mem_insert()
{
	$('#modal_memo_insert').modal();
}

// 메모 등록 처리하기
function btn_memo_insert()
{
	var params = $("#form_mem_insert").serialize();
    jQuery.ajax({
        url: '/api/ajax_memo_insert_proc',
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
}


function event_detail_info()
{
	var h_size = $(window).height();
  	var c_size = h_size - 200;
  	$('#bottom-menu-area').css("height",h_size+"px");
  	$('.direct-chat-messages').css("height",c_size+"px");
 	$('.content').addClass('modal-open');
}

$("#bottom-menu-close").click(function(){
	$('.content').removeClass('modal-open');
});

// 설정하기

// 휴회횟수 숫자입력
$('#domcy_acppt_i_cnt').keyup(function(){
	var d_cnt = onlyNum2( $('#domcy_acppt_i_cnt').val());
	$('#domcy_acppt_i_cnt').val(d_cnt);
});

// 상품 보내기
function send_event(mem_sno)
{
	location.href="/teventmain/send_event/"+mem_sno;
}

function rn_br(word)
{
	return word.replace(/(?:\r\n|\r|\n)/g, '<br />');
}

function test_clas_chk(stchr_id,buy_sno)
{
	if (stchr_id == '')
	{
		alertToast('error',"수업강사를 먼저 배정 해야 합니다.");
		return;
	}
	
	var params = "stchr_id="+stchr_id+"&buy_sno="+buy_sno;
    jQuery.ajax({
        url: '/api/ajax_clas_chk',
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
				alert( json_result['msg'] );
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
	
	//location.href="/ttotalmain/ajax_clas_chk/"+stchr_id+"/"+buy_sno;
}

// PT, Golf PT 예약중인 상품을 운동 시작일로 변경하기
function pt_use(stchr_id,buy_sno)
{
	if (stchr_id == '')
	{
		alertToast('error',"수업강사를 먼저 배정 해야 합니다.");
		return;
	}
	//location.href="/ttotalmain/ajax_pt_use/"+stchr_id+"/"+buy_sno;
	
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >이용시작을 하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		// 성공일 경우	
			var params = "buy_sno="+buy_sno;
        	jQuery.ajax({
                url: '/ttotalmain/ajax_pt_use',
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
        			console.log(json_result);
        			if (json_result['result'] == 'true')
        			{
        				alert( '해당 상품의 이용이 시작 되었습니다.' );
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
    	}
    });
	
}

// 운동 시작일 변경
function change_exr_s_date(buy_sno,event_stat,clas_dv,sdate)
{
	if (clas_dv == '21' || clas_dv == '22')
	{
		alertToast('error','수업상품의 운동 시작일은 변경이 불가능 합니다. 이용시작 버튼을 이용해주세요');
		return;
	}

	if(event_stat == "01")
	{
		$('#pop_sdate_change_sdate').datepicker('destroy');
		
    	$('#modal_pop_sdate').modal();
    	$('#pop_sdate_buy_sno').val(buy_sno);
    	$('#pop_sdate_info_sdate').val(sdate); // 현재 운동시작일
    	$('#pop_sdate_change_sdate').val(sdate); // 변경할 운동시작일
    	
    	$('#pop_sdate_change_sdate').datepicker({
            autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
            language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
        });
	} else 
	{
		alertToast('error','이용중이거나 종료된 상품은 시작일을 변경할 수 없습니다.');
	}
}

// 운동 시작일 변경 처리
function btn_change_sdate_submit()
{
var fc_sdate_buy_sno = $('#pop_sdate_buy_sno').val();
var fc_sdate_sdate = $('#pop_sdate_change_sdate').val();

	var params = "fc_sdate_buy_sno="+fc_sdate_buy_sno+"&fc_sdate_sdate="+fc_sdate_sdate;
	jQuery.ajax({
        url: '/api/ajax_info_mem_change_sdate_proc',
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
			console.log(json_result);
			if (json_result['result'] == 'true')
			{
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

// 운동 종료일 변경
function change_exr_e_date(buy_sno,event_stat,clas_dv,edate)
{

	if ( clas_dv == '21' || clas_dv == '22' )
	{
		alertToast('error','수업상품의 운동 종료일은 변경 할 수 없습니다. 강제 종료기능을 이용해주세요.');
		return;
	}

	if (edate == '')
	{
		alertToast('error','운동종료일이 없는 상품은 운동 종료일을 변경 할 수 없습니다.');
		return;
	}
	
	if(event_stat != "99")
	{
		$('#pop_edate_change_edate').datepicker('destroy');
	
    	$('#modal_pop_edate').modal();
    	$('#pop_edate_buy_sno').val(buy_sno);
    	$('#pop_edate_info_edate').val(edate); // 현재 운동시작일
    	$('#pop_edate_change_edate').val(edate); // 변경할 운동시작일
    	$('#pop_edate_change_add_edate_cnt').val(0);
    	
    	$('#pop_edate_change_edate').datepicker({
            autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
            language : "ko",	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
        });
    } else 
	{
		alertToast('error','종료된 상품은 종료일을 변경할 수 없습니다.');
	}
}

function edate_calu_days()
{
	var sDate = $('#pop_edate_info_edate').val();
	var eDate = $('#pop_edate_change_edate').val();
	
	const getDateDiff = (d1, d2) => {
		const date1 = new Date(d1);
		const date2 = new Date(d2);
		  
		const diffDate = date2.getTime() - date1.getTime();
		  
		return (diffDate / (1000 * 60 * 60 * 24)); // 밀리세컨 * 초 * 분 * 시 = 일
	}
	
	var day_cnt = getDateDiff(sDate,eDate);
	$('#pop_edate_change_add_edate_cnt').val(day_cnt);
}

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


// 운동 종료일 변경 처리
function btn_change_edate_submit()
{
	var fc_edate_buy_sno = $('#pop_edate_buy_sno').val();
	var fc_edate_edate = $('#pop_edate_change_edate').val();

	var params = "fc_edate_buy_sno="+fc_edate_buy_sno+"&fc_edate_edate="+fc_edate_edate;
	jQuery.ajax({
        url: '/api/ajax_info_mem_change_edate_proc',
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
			console.log(json_result);
			if (json_result['result'] == 'true')
			{
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

// 수업강사 변경
function change_stchr(buy_sno,event_stat,mem_id,clas_dv)
{

	if (event_stat == '99')
	{
		alertToast('error','종료된 상품은 수업강사를 변경 할 수 없습니다.');
		return;
	}

	if (clas_dv == '21' || clas_dv == '22')
	{
		$('#ch_stchr_id').val(mem_id).trigger('change');
    	$('#stchr_buy_sno').val(buy_sno);
    	$('#modal_pop_stchr').modal();
	} else 
	{
		alertToast('error','수업상품이 아닌 상품은 수업강사를 지정 할 수 없습니다.');
		return;
	}
	
}

// 수업강사 변경 처리
function btn_stchr_submit()
{
	var fc_stchr_buy_sno = $('#stchr_buy_sno').val();
	var fc_ch_stchr_id = $('#ch_stchr_id').val();
	
// ### test
// $('#fc_stchr_buy_sno').val(fc_stchr_buy_sno);
// $('#fc_ch_stchr_id').val(fc_ch_stchr_id);
// $('#form_stchr').submit();
// return;

	var params = "fc_stchr_buy_sno="+fc_stchr_buy_sno+"&fc_ch_stchr_id="+fc_ch_stchr_id;
	jQuery.ajax({
        url: '/ttotalmain/ajax_info_mem_stchr_proc',
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
			console.log(json_result);
			if (json_result['result'] == 'true')
			{
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

// 판매강사 변경
function change_ptchr(buy_sno,event_stat,mem_id,buy_date)
{
	if ( event_stat == '99' )
	{
		alertToast('error','종료된 상품의 판매강사는 변경이 불가능 합니다.');
		return;
	}
	
	// 오늘날짜 구하기
	var now_date = new Date();
	var disp_now_date = now_date.getFullYear() + "-" + ("0"+(now_date.getMonth()+1)).slice(-2)+"-"+("0"+now_date.getDate()).slice(-2);
	
	if (buy_date != disp_now_date)
	{
		alertToast('error','판매강사 변경은 당일만 가능합니다.');
		return;
	}

	$('#ch_ptchr_id').val(mem_id).trigger('change');
	$('#ptchr_buy_sno').val(buy_sno);
	$('#modal_pop_ptchr').modal();
}

// 판매강사 변경 처리
function btn_ptchr_submit()
{
	var fc_ptchr_buy_sno = $('#ptchr_buy_sno').val();
	var fc_ch_ptchr_id = $('#ch_ptchr_id').val();
	
// ### test
// $('#fc_ptchr_buy_sno').val(fc_ptchr_buy_sno);
// $('#fc_ch_ptchr_id').val(fc_ch_ptchr_id);
// $('#form_ptchr').submit();
// return;

	var params = "fc_ptchr_buy_sno="+fc_ptchr_buy_sno+"&fc_ch_ptchr_id="+fc_ch_ptchr_id;
	jQuery.ajax({
        url: '/ttotalmain/ajax_info_mem_ptchr_proc',
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
			console.log(json_result);
			if (json_result['result'] == 'true')
			{
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

// 휴회일 추가
function change_domcy_day(buy_sno,event_stat,domcy_yn)
{
	if (event_stat == "99")
	{
		alertToast('error','종료된 상품은 휴회일 추가를 할 수 없습니다.');
		return;
	}
	
	if (domcy_yn == "N")
	{
		alertToast('error','해당 상품은 휴회이 불가능한 상품입니다.');
		return;
	}
	
	$('#domcy_day_buy_sno').val(buy_sno);
	$('#modal_pop_domcy_day').modal();
}

// 휴회일 추가 처리
function btn_domcy_day_submit()
{
	var domcy_day_buy_sno = $('#domcy_day_buy_sno').val();
	var domcy_day_day = $('#domcy_day_day').val();
	
	var params = "fc_domcy_day_buy_sno="+domcy_day_buy_sno+"&fc_domcy_day_day="+domcy_day_day;
	jQuery.ajax({
        url: '/api/ajax_info_mem_domcyday_proc',
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
			console.log(json_result);
			if (json_result['result'] == 'true')
			{
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

// 휴회횟수 추가
function change_domcy_cnt(buy_sno,event_stat,domcy_yn)
{
	if (event_stat == "99")
	{
		alertToast('error','종료된 상품은 휴회일 추가를 할 수 없습니다.');
		return;
	}
	
	if (domcy_yn == "N")
	{
		alertToast('error','해당 상품은 휴회이 불가능한 상품입니다.');
		return;
	}
	
	$('#domcy_cnt_buy_sno').val(buy_sno);
	$('#modal_pop_domcy_cnt').modal();
}

// 휴회횟수 추가 처리
function btn_domcy_cnt_submit()
{
	var domcy_cnt_buy_sno = $('#domcy_cnt_buy_sno').val();
	var domcy_cnt_cnt = $('#domcy_cnt_cnt').val();
	
	var params = "fc_domcy_cnt_buy_sno="+domcy_cnt_buy_sno+"&fc_domcy_cnt_cnt="+domcy_cnt_cnt;
	jQuery.ajax({
        url: '/api/ajax_info_mem_domcycnt_proc',
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
			console.log(json_result);
			if (json_result['result'] == 'true')
			{
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

// 수업 추가
function change_clas_cnt(buy_sno,event_stat,clas_dv)
{
	if (event_stat == '99')
	{
		alertToast('error','종료된 상품은 수업 추가를 할 수 없습니다.');
		return;
	}
	
	if (clas_dv == '21' || clas_dv == '22')
	{
		$('#clas_cnt_buy_sno').val(buy_sno);
		$('#modal_pop_clas_cnt').modal();
	} else 
	{
		alertToast('error','수업 상품이 아닌 상품은 수업 추가를 할 수 없습니다.');
		return;
	}
}

function btn_clas_cnt_submit()
{
	var clas_cnt_buy_sno = $('#clas_cnt_buy_sno').val();
	var clas_cnt_cnt = $('#clas_cnt_cnt').val();
	
// test
// $('#fc_clas_cnt_buy_sno').val(clas_cnt_buy_sno);
// $('#fc_clas_cnt_cnt').val(clas_cnt_cnt);
// $('#form_clas_cnt').submit();
// return;

	var params = "fc_clas_cnt_buy_sno="+clas_cnt_buy_sno+"&fc_clas_cnt_cnt="+clas_cnt_cnt;
	jQuery.ajax({
        url: '/api/ajax_info_mem_clascnt_proc',
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
			console.log(json_result);
			if (json_result['result'] == 'true')
			{
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


// 강제 종료하기
function change_event_just_end(buy_sno)
{
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >강제 종료를 하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		// 성공일 경우	
			var params = "buy_sno="+buy_sno;
        	jQuery.ajax({
                url: '/api/ajax_info_mem_just_end_proc',
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
        			console.log(json_result);
        			if (json_result['result'] == 'true')
        			{
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

// 중요메모 메모 토클 처리하기
function change_memo_prio_set(flag_yn,memo_sno)
{
	var params = "prio_set="+flag_yn+"&memo_mgmt_sno="+memo_sno;
	jQuery.ajax({
        url: '/api/ajax_memo_prio_set',
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
			} else 
			{
				console.log(json_result);
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