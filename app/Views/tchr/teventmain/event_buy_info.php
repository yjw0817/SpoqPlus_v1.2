<style>

	/* General Layout & Spacing */
	.panel-body {
		padding: 25px; /* Increase padding for more breathing room inside panels */
	}

	.table-bordered td,
	.table-bordered th {
		padding: 12px 15px !important; /* Increase padding in table cells for better readability */
		vertical-align: middle; /* Vertically align text in table cells */
		border-color: #e0e0e0 !important; /* Lighter border color for a softer look */
	}

	/* Headings */
	.panel-heading {
		border-bottom: 1px solid #e0e0e0; /* Softer border under headings */
		padding: 15px 25px; /* Consistent padding with panel-body */
		background-color: #f8f8f8; /* Slightly off-white background for headings */
		color: #333; /* Darker text for headings */
		font-size: 1.15rem; /* Slightly larger panel titles */
		font-weight: 600; /* Bolder panel titles */
	}

	/* Input Fields & Forms - Consolidated Styles */

	input[type="text"].text-right,
	input[type="password"].form-control,
	input[type="text"].datepp,
	input[type="text"][name="card_appno"],
	input[type="text"][name="cash_amt"], /* Target 현금결제 specifically */
	input[type="text"][name="misu_amt"], /* Target 미수금 specifically */
	input[type="text"][name="card_amt"] /* Ensure 카드결제 amount is also consistent */
	{
		border-radius: 4px;
		border: 1px solid #dcdcdc;
		padding: 8px 12px;
		box-shadow: inset 0 1px 2px rgba(0,0,0,.04);
		transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
		width: auto !important; /* Allow flexbox to manage width */
		margin-left: 0 !important; /* Remove inline margin-left */
		flex-grow: 1; /* Allow the input to grow */
		max-width: 150px; /* **NEW**: Set a max-width for uniformity */
	}


	.form-control:focus,
	input[type="text"].text-right:focus,
	input[type="password"].form-control:focus,
	input[type="text"].datepp:focus,
	input[type="text"][name="card_appno"]:focus,
	input[type="text"][name="cash_amt"]:focus,
	input[type="text"][name="misu_amt"]:focus,
	input[type="text"][name="card_amt"]:focus {
		border-color: #a0c3e6;
		box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
		outline: none;
	}

	
	/* Readonly Inputs */
	.input-readonly {
		background-color: #f5f5f5;
		color: #777;
		cursor: default;
	}

	/* Specific Table Styles */
	.table .bg-lightblue {
		background-color: #e9f5ff !important;
		color: #333 !important;
		font-weight: 600;
	}

	.table .bg-olive {
		background-color: #f3f9f3 !important;
		color: #333 !important;
		font-weight: 600;
	}

	/* Layout for Payment Info Section */
	.row > .col-md-3,
	.row > .col-md-6 {
		display: flex;
		flex-direction: column;
	}

	.input-group.input-group-sm {
		display: flex;
		align-items: center;
		margin-bottom: 15px;
		flex-wrap: nowrap;
	}

	.input-group-append {
		display: flex;
		align-items: center;
	}

	/* Adjusting the width of specific input group text (labels) */
	/* **UPDATED**: Stronger override for label widths */
	.input-group-append > span.input-group-text {
		min-width: 120px !important; /* Consistent minimum width for all labels */
		width: 120px !important; /* Force this width */
		text-align: left;
		padding-left: 15px;
		flex-shrink: 0;
	}

	/* Specific adjustment for 승인번호 explanatory text */
	.col-md-6 .input-group span[style*="font-size:0.9em;"] {
		margin-left: 10px;
		color: #777;
		white-space: nowrap;
		flex-shrink: 0;
	}

	.btn-info {
		background-color: #5bc0de;
		border-color: #5bc0de;
	}

	.btn-info:hover {
		background-color: #46b8da;
		border-color: #46b8da;
	}

	.btn-success {
		background-color: #28a745;
		border-color: #28a745;
		font-size: 0.9rem; /* Match size with other buttons */
		padding: 10px 20px; /* Match padding with other buttons */
		box-shadow: 0 2px 4px rgba(0,0,0,.1);
	}

	.btn-success:hover {
		background-color: #218838;
		border-color: #218838;
		box-shadow: 0 4px 8px rgba(0,0,0,.15);
	}

	/* Footer buttons alignment */
	.card-footer .pagination-sm {
		margin-bottom: 0;
		display: flex;
		align-items: center;
	}

	.card-footer .ac-btn {
		margin-right: 10px;
	}

	.card-footer .ac-btn:last-child {
		margin-right: 0;
	}

	/* Card footer background */
	.card-footer {
		background-color: #f8f8f8;
		border-top: 1px solid #e0e0e0;
		padding: 15px 25px;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	/* Specific `col-md-12` class that might be causing layout issues */
	.table.col-md-12 {
		width: 100%;
		margin-bottom: 0;
	}

	/* Color Admin specific adjustments if needed */
	.panel {
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 4px 12px rgba(0,0,0,0.06);
		border: none;
	}

	.panel-inverse {
		border: none;
	}

	/* Ensure font consistency if possible, check if Source Sans Pro is loaded */
	body {
		font-family: 'Source Sans Pro', sans-serif, 'Apple SD Gothic Neo', 'Malgun Gothic', Dotum, '돋움', sans-serif;
	}

	/* Small adjustments to input-group for visual balance */
	.input-group-sm > .input-group-append > .input-group-text,
	.input-group-sm > .input-group-prepend > .input-group-text {
		padding: 0.4rem 0.75rem;
		font-size: 0.875rem;
	}

	.input-group-sm > .form-control,
	.input-group-sm > .form-select {
		padding: 0.4rem 0.75rem;
		font-size: 0.875rem;
	}

	/* Radio button text alignment */
	.icheck-primary label small {
		vertical-align: middle;
	}
</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4">
			
				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h3 class="panel-title">회원정보</h3>
					</div>
					
				
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
						<!-- TABLE [START] -->
						<table class="table table-bordered col-md-12">
							<tbody>
								<tr class="">
									<td class='bg-lightblue'>회원상태</td>
									<td><?php echo $sDef['MEM_STAT'][$mem_info['MEM_STAT']]?></td>
									<td class='bg-lightblue'>회원이름</td>
									<td><?php echo $mem_info['MEM_NM']?></td>
								</tr>
								<tr class="">
									<td class='bg-lightblue'>회원성별</td>
									<td><?php echo $sDef['MEM_GENDR'][$mem_info['MEM_GENDR']]?></td>
									<td class='bg-lightblue'>회원아이디</td>
									<td><?php echo $mem_info['MEM_ID']?></td>
								</tr>
								<tr class="">
									<td class='bg-lightblue'>생년월일</td>
									<td><?php echo disp_birth($mem_info['BTHDAY'])?></td>
									<td class='bg-lightblue'>전화번호</td>
									<td><?php echo disp_phone($mem_info['MEM_TELNO'])?></td>
								</tr>
							</tbody>
						</table>
						<!-- TABLE [END] -->
					</div>
					<!-- CARD BODY [END] -->
			
				</div>
			</div>
			<div class="col-md-8">
				
				<div class="panel panel-inverse">
							<div class="panel-heading">
						<h3 class="panel-title">구매 상품 정보</h3>
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
						<!-- 구매 상품 정보 [START] -->
						<table class="table table-bordered col-md-12">
							<tbody>
								<tr class="">
									<td class='bg-olive' style='width:150px'>상품명</td>
									<td colspan='3'><?php echo $event_info['SELL_EVENT_NM']?></td>
									<td class='bg-olive' style='width:150px'>입장종류</td>
									<td><?php echo $sDef['ACC_RTRCT_DV'][$event_info['ACC_RTRCT_DV']]?></td>
								</tr>
								<tr class="">
									<td class='bg-olive' style='width:150px'>이용개월</td>
									<td>
										<?php echo $event_info['USE_PROD']?> <?php echo $sDef['USE_UNIT'][$event_info['USE_UNIT']]?>
										<?php if($event_info['ADD_SRVC_EXR_DAY'] > 0) : ?>
										(+<?php echo $event_info['ADD_SRVC_EXR_DAY']?>)
										<?php endif; ?>
									</td>
									<td class='bg-olive' style='width:150px'>수업횟수</td>
									<td>
										<?php echo $event_info['CLAS_CNT']?>
										<?php if($event_info['ADD_SRVC_CLAS_CNT'] > 0) : ?>
										(+<?php echo $event_info['ADD_SRVC_CLAS_CNT']?>)
										<?php endif; ?>
										<?php if($event_info['SEND_EVENT_SNO'] != "" && $event_info['STCHR_NM'] != "") : ?>
										(<?php echo $event_info['STCHR_NM']?>)
										<?php endif; ?>
									</td>
									<td class='bg-olive' style='width:150px'>입장상세</td>
									<td><?php echo $sDef['ACC_RTRCT_MTHD'][$event_info['ACC_RTRCT_MTHD']]?></td>
								</tr>
								
								<tr class="">
									<td class='bg-olive' style='width:150px'>판매가격</td>
									<td>
										<?php echo number_format($event_info['SELL_AMT'])?> 원
										<?php if($event_info['SEND_EVENT_SNO'] != "" && $event_info['PTCHR_NM'] != "") : ?>
										(<?php echo $event_info['PTCHR_NM']?>)
										<?php endif; ?>
									</td>
									<td class='bg-olive' style='width:150px'>휴회가능일</td>
									<?php if ($event_info['DOMCY_POSS_EVENT_YN'] == 'Y') : ?>
									<td><?php echo $event_info['DOMCY_DAY']?></td>
									<td class='bg-olive' style='width:150px'>휴회가능횟수</td>
									<td><?php echo $event_info['DOMCY_CNT']?></td>
									<?php else :?>
									<td colspan='3'>휴회불가능</td>
									<?php endif;?>
								</tr>
								
							</tbody>
						</table>
						<!-- 구매 상품 정보 [END] -->
					</div>
					<!-- CARD BODY [END] -->
			
				</div>
			</div>
		</div>
		<div class='row'>
			<div class="col-md-12">
				
				<div class="panel panel-inverse">
							<div class="panel-heading">
						<h3 class="panel-title">결제 정보 입력</h3>
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
						<!-- 결재정보 구성 [START] -->
		<?php if(isset($refund_amt) && $refund_amt > 0) : ?>
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-info alert-dismissible" >
                    <button type="button" class="close"  data-bs-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-info"></i> 교체환불 진행 중</h5>
                    <p>
                    	환불금액: <strong><?php echo number_format($refund_amt); ?>원</strong><br>
                    	<small>이 금액은 결제 시 자동으로 차감됩니다.</small>
                    </p>
                </div>
			</div>
		</div>
		<?php endif; ?>
		<?php if($event_info['LOCKR_SET'] == "Y") : ?>						
		<div class="row">
			<div class="col_md-12">
				<div class="alert alert-info alert-dismissible" >
                    <button type="button" class="close"  data-bs-dismiss="alert" aria-hidden="true">&times;</button>
                    <small>
                    락커 구매시 이미 이용중인 락커은 번호 옆에 번호가 보입니다. 번호를 선택하면 이전 번호의 락커을 예약 할 수 있습니다. <br />
                    처음 락커을 구매할 때는 번호 없이 구매를 진행하고 회원별 통합 정보에서 예약 구매된 락커의 배정을 통하여 락커을 배정 할 수 있습니다.
                    </small>
                </div>
			</div>
		</div>
		<?php endif; ?>						
<div class="row">
	<div class="col-md-3">						
        <!-- [판매금액] [START] -->
        <div class="input-group input-group-sm mb-1">
        	<span class="input-group-append">
        		<span class="input-group-text" style='width:150px'>판매금액</span>
        	</span>
        	<input type="text" class="input-readonly text-right" style='width:140px; margin-left:5px' placeholder="" name="sell_amt" id="sell_amt" value="<?php echo number_format($event_info['SELL_AMT'])?>" readonly />
        </div>
        <!-- [판매금액] [END] -->
        <?php if(isset($refund_amt) && $refund_amt > 0) : ?>
        <!-- [환불금액] [START] -->
        <div class="input-group input-group-sm mb-1">
        	<span class="input-group-append">
        		<span class="input-group-text" style='width:150px'>환불금액 차감</span>
        	</span>
        	<input type="text" class="input-readonly text-right" style='width:140px; margin-left:5px' placeholder="" value="- <?php echo number_format($refund_amt)?>" readonly />
        </div>
        <!-- [환불금액] [END] -->
        <?php endif; ?>
        <!-- [실제 판매할 금액] [START] -->
        <div class="input-group input-group-sm mb-1">
        	<span class="input-group-append">
        		<span class="input-group-text" style='width:150px'>실제 판매할 금액</span>
        	</span>
        	<?php 
        		$real_sell_amt = $event_info['SELL_AMT'];
        		if(isset($refund_amt) && $refund_amt > 0) {
        			$real_sell_amt = $event_info['SELL_AMT'] - $refund_amt;
        			if($real_sell_amt < 0) $real_sell_amt = 0;
        		}
        	?>
        	<input type="text" class="text-right" style='width:140px; margin-left:5px' placeholder="" name="real_sell_amt" id="real_sell_amt"  value="<?php echo number_format($real_sell_amt)?>" />
        </div>
        <!-- [실제 판매할 금액] [END] -->
        
        
        <!-- [라커] [START] -->
        <?php if($event_info['LOCKR_SET'] == "Y") : ?>
        		
        <input type="hidden" name="exr_s_date" id="exr_s_date" value="<?php echo date('Y-m-d')?>" />
        <div style='margin-top:4px;margin-left:5px;'>
        	<div class="icheck-primary d-inline">
                <input type="radio" name="lockr_gendr_set" id="lockr_gendr_set_m" value="M" checked>
                <label for="lockr_gendr_set_m">
                	<small>남자</small>
                </label>
            </div>
            <div class="icheck-primary d-inline">
                <input type="radio" name="lockr_gendr_set" id="lockr_gendr_set_f" value="F">
                <label for="lockr_gendr_set_f">
                	<small>여자</small>
                </label>
            </div>
            <div class="icheck-primary d-inline">
                <input type="radio" name="lockr_gendr_set" id="lockr_gendr_set_g" value="G">
                <label for="lockr_gendr_set_g">
                	<small>공용</small>
                </label>
            </div>
        </div>
        
        <div class="input-group input-group-sm mb-1" style="display:none">
        	<span class="input-group-append">
        		<span class="input-group-text" style='width:150px'>라커번호</span>
        	</span>
        	<input type="text" class="" style='width:50px; margin-left:5px' placeholder="" name="lockr_no" id="lockr_no" value="" readonly />
        	<span class="input-group-append">
        		<button type="button" class="btn btn-warning btn-flat" style='margin-left:5px; ' onclick="func_lockr_no_clear();" >번호지우기</button>
        	</span>
        </div>
        <div class="input-group input-group-sm mb-1">
        	<?php foreach($get_use_locker_info as $lk) : ?>
        	<span class="input-group-append">
        		<button type="button" class="btn btn-success btn-flat" style='margin-left:5px' onclick="func_lockr_no_select('<?php echo $lk['LOCKR_GENDR_SET']?>','<?php echo $lk['LOCKR_NO']?>')" ><?php echo $sDef['LOCKR_GENDR_SET'][$lk['LOCKR_GENDR_SET']]?> <?php echo $lk['LOCKR_NO']?> 번</button>
        	</span>
        	<?php endforeach; ?>
        </div>
        
        <?php else :?>
        <!-- [운동시작일] [START] -->
        <div class="input-group input-group-sm mb-1">
        	<span class="input-group-append">
        		<span class="input-group-text" style='width:150px'>운동시작일</span>
        	</span>
        	<input type="text" class="datepp text-center" style='width:140px; margin-left:5px' placeholder="" name="exr_s_date" id="exr_s_date" value="<?php echo date('Y-m-d')?>" />
        </div>
        <!-- [운동시작일] [END] -->
        <div style="display:none"><input type="radio" name="lockr_gendr_set" id="lockr_gendr_set_m" value="" checked></div>
        <input type="hidden" name="lockr_no" id="lockr_no" value="" />
        <?php endif; ?>
        <!-- [라커] [END] -->
        
        
	</div>
	<!-- COL MD DIV -->
	<div class="col-md-6">
		<!-- [카드결제] [START] -->
        <div class="input-group input-group-sm mb-1">
        	<span class="input-group-append">
        		<span class="input-group-text" style='width:100px'>카드결제</span>
        	</span>
        	<input type="text" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="card_amt" id="card_amt"  value="" />
        	<!-- 
        	<span class="input-group-append">
            	<button type="button" class="btn btn-info btn-flat" style='margin-left:5px' id="btn_pay_conn">결제</button>
            </span>
             -->
            <span class="input-group-append" style="display:none">
            	<button type="button" class="btn btn-danger btn-flat" style='margin-left:5px' id="btn_pay_direct">수동결제</button>
            </span>
            <?php if(isset($pos_enabled) && $pos_enabled): ?>
            <span class="input-group-append">
            	<button type="button" class="btn btn-primary btn-flat" style='margin-left:5px' id="btn_pos_pay">POS결제</button>
            </span>
            <?php endif; ?>
        	<input type="text" class="" style='width:100px; margin-left:5px' placeholder="승인번호" name="card_appno" id="card_appno"  value="" />
        	<span style='font-size:0.9em;'> * 카드결제시 카드결제 금액을 입력 후 꼭 수동결제 버튼을 클릭하세요.</span>
        </div>
        <!-- [카드결제] [END] -->
        <!-- [현금결제] [START] -->
        <div class="input-group input-group-sm mb-1">
        	<span class="input-group-append">
        		<span class="input-group-text" style='width:100px'>현금결제</span>
        	</span>
        	<input type="text" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="cash_amt" id="cash_amt"  value="" />
        </div>
        <!-- [현금결제] [END] -->
        <!-- [현금결제] [미수금] -->
        <div class="input-group input-group-sm mb-1">
        	<span class="input-group-append">
        		<span class="input-group-text" style='width:100px'>미수금</span>
        	</span>
        	<input type="text" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="misu_amt" id="misu_amt"  value="" readonly />
        </div>
        <!-- [현금결제] [미수금] -->
         <!-- [계좌이체] [START] -->
        <div class="input-group input-group-sm mb-1" style='display:none'>
        	<span class="input-group-append">
        		<span class="input-group-text" style='width:100px'>계좌이체</span>
        	</span>
        	<input type="text" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="acct_amt" id="acct_amt"  value="" />
        	<select class="select2 form-control" style="width: 260px;" name="acct_no" id="acct_no">
        		<option>계좌 선택</option>
        		<option>국민 : 12321-13547-125-25</option>
        		<option>하나 : 8514-254-45387-11</option>
			</select>
        </div>
        <!-- [계좌이체] [END] -->
	</div>
</div>


						<!-- 결재정보 구성 [END] -->
					</div>
					<!-- CARD BODY [END] -->
					<!-- CARD FOOTER [START] -->
					<div class="card-footer clearfix">
						<!-- BUTTON [START] -->
                        <ul class="pagination pagination-sm m-0 float-left">
                        	<li class="ac-btn"><button type="button" class="btn btn-block btn-info btn-sm" onclick="go_info_mem('<?php echo $mem_info['MEM_SNO']?>');">회원정보로 가기</button></li>
							<?php if ($event_info['SEND_EVENT_SNO'] != "") :?>
							<li class="ac-btn"><button type="button" class="btn btn-block btn-info btn-sm" onclick="location.href='/teventmain/send_event_manage';">상품보내기로 가기</button></li>
							<?php else: ?>
							<li class="ac-btn"><button type="button" class="btn btn-block btn-info btn-sm" onclick="location.href='/teventmain/event_buy';">상품구매로 가기</button></li>
							<?php endif; ?>
                        </ul>
                        <!-- BUTTON [END] -->
						<!-- BUTTON [START] -->
						<ul class="pagination pagination-sm m-0 float-right">
						<li class="ac-btn" style="display:none">
    						<div class="icheck-primary d-inline">
                                <input type="radio" id="radioPayIssue1" name="type_pay_issue" checked >
                                <label for="radioPayIssue1">
                                	<small>일반구매</small>
                                </label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="radioPayIssue2" name="type_pay_issue">
                                <label for="radioPayIssue2">
                                	<small>교체구매</small>
                                </label>
                            </div>
						</li>
						
							<li class="ac-btn"><button type="button" class="btn btn-block btn-success btn-sm" id='btn_pay_confirm'>결제완료</button></li>
						</ul>
						
						<!-- BUTTON [END] -->
						
					</div>
					<!-- CARD FOOTER [END] -->
			
				</div>
			
			</div>
		</div>
	</div>

<form name="form_payment_submit" id="form_payment_submit" method="post" action="/teventmain/event_buy_proc">
    <input type='hidden' name='mem_sno' id='mem_sno' value="<?php echo $mem_info['MEM_SNO']?>" />
    <input type='hidden' name='mem_id' id='mem_id' value="<?php echo $mem_info['MEM_ID']?>" />
    <input type='hidden' name='mem_nm' id='mem_nm' value="<?php echo $mem_info['MEM_NM']?>" />
    
    <input type='hidden' name='sell_event_sno' id='sell_event_sno' value="<?php echo $event_info['SELL_EVENT_SNO']?>" />
    <input type='hidden' name='send_event_sno' id='send_event_sno' value="<?php echo $event_info['SEND_EVENT_SNO']?>" />
    

    <input type="hidden" name="van_appno" id="van_appno" />
    <input type="hidden" name="van_appno_sno" id="van_appno_sno" />
    
    <input type="hidden" name="pay_exr_s_date" id="pay_exr_s_date" />
    
    <input type="hidden" name="pay_card_amt" id="pay_card_amt" />
    <input type="hidden" name="pay_acct_amt" id="pay_acct_amt" />
    <input type="hidden" name="pay_acct_no" id="pay_acct_no" />
    <input type="hidden" name="pay_cash_amt" id="pay_cash_amt" />
    <input type="hidden" name="pay_misu_amt" id="pay_misu_amt" />
    <input type="hidden" name="pay_real_sell_amt" id="pay_real_sell_amt" />
    <input type="hidden" name="pay_lockr_no" id="pay_lockr_no" />
    <input type="hidden" name="pay_lockr_gendr_set" id="pay_lockr_gendr_set" />
    
    <input type="hidden" name="pay_issue" id="pay_issue" />
    <?php if(isset($refund_amt) && $refund_amt > 0) : ?>
    <input type="hidden" name="refund_amt" id="refund_amt" value="<?php echo $refund_amt; ?>" />
    <?php endif; ?>
    
</form>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->

<!-- ============================= [ modal-sm START ] ============================================ -->
<div class="modal fade" id="modal-sm">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Small Modal</h4>
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
<!-- ============================= [ modal-sm END ] ============================================== -->


	
<!-- ############################## MODAL [ END ] ###################################### -->
	
	
	
</section>


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
	calu_amt();
})

function go_info_mem(mem_sno)
{
	location.href="/ttotalmain/info_mem/"+mem_sno;
}

// 실제 판매할 금액 입력
$('#real_sell_amt').keyup(function(){
	var d_amt = onlyNum( $('#real_sell_amt').val() );
	$('#real_sell_amt').val(currencyNum(d_amt));
	calu_amt();
});

// 카드결제금액 입력
$('#card_amt').keyup(function(){
	var d_amt = onlyNum( $('#card_amt').val() );
	$('#card_amt').val(currencyNum(d_amt));
	calu_amt();
});

$('#card_amt').on('focus', function() {
	if ($(this).val() === '') {
		$(this).val($("#misu_amt").val());
		calu_amt();
	}
});

// 현금결제금액 입력
$('#cash_amt').keyup(function(){
	var d_amt = onlyNum( $('#cash_amt').val() );
	$('#cash_amt').val(currencyNum(d_amt));
	calu_amt();
});

$('#cash_amt').on('focus', function() {
	if ($(this).val() === '') {
		$(this).val($("#misu_amt").val());
		calu_amt();
	}
});

// 미수결제금액 입력
$('#misu_amt').keyup(function(){
	var d_amt = onlyNum( $('#misu_amt').val() );
	$('#misu_amt').val(currencyNum(d_amt));
	calu_amt();
});

// 계좌결제금액 입력
$('#acct_amt').keyup(function(){
	var d_amt = onlyNum( $('#acct_amt').val() );
	$('#acct_amt').val(currencyNum(d_amt));
	calu_amt();
});

// 미수금 자동 계산
function calu_amt()
{
	var g_basic_amt = $('#real_sell_amt').val();
	var g_card_amt = $('#card_amt').val();
	var g_cash_amt = $('#cash_amt').val();
	
	if (g_basic_amt == "") 
	{
		g_basic_amt = 0;
	} else 
	{
		g_basic_amt = onlyNum(g_basic_amt);
	}
	
	if (g_card_amt == "") 
	{
		g_card_amt = 0;
	} else 
	{
		g_card_amt = onlyNum(g_card_amt);
	}
	
	if (g_cash_amt == "") 
	{
		g_cash_amt = 0;
	} else 
	{
		g_cash_amt = onlyNum(g_cash_amt);
	}
	
	var misu_amt = 0;
	misu_amt = g_basic_amt - (parseInt(g_card_amt) + parseInt(g_cash_amt));
	$('#misu_amt').val(currencyNum(misu_amt));
}


function func_lockr_no_select(gendr,lkno)
{
	if (gendr == 'M')
	{
		$('#lockr_gendr_set_m').prop('checked',true);
	} else if (gendr == 'F')
	{
		$('#lockr_gendr_set_f').prop('checked',true);
	} else if (gendr == 'G')
	{
		$('#lockr_gendr_set_g').prop('checked',true);
	}

	$('#lockr_no').val(lkno);
}

function func_lockr_no_clear()
{
	$('#lockr_no').val('');
}
    
$('#btn_pay_confirm').click(function(){
	
	if ( $('#misu_amt').val() != '' )
	{
		if ( parseInt($('#misu_amt').val()) < 0 )
		{
			alertToast('error','미수금은 - 가 될 수 없습니다. 금액을 정확히 입력하세요');
			return;
		}
	}
	
	
	if ( $('#radioPayIssue1').is(':checked') == false && $('#radioPayIssue2').is(':checked') == false )
	{
		alertToast('error','일반구매,교체구매중 하나를 선택하세요');
		return;
	}
	if ( !($('#card_amt').val() == '' || $('#card_amt').val() == 0 ))
	{
		$('#btn_pay_direct').click();
	} else
	{
		$('#pay_acct_amt').val($('#acct_amt').val());
		$('#pay_acct_no').val($('#acct_no').val());
		$('#pay_cash_amt').val($('#cash_amt').val());
		$('#pay_misu_amt').val($('#misu_amt').val());
		$('#pay_exr_s_date').val($('#exr_s_date').val());
		$('#pay_real_sell_amt').val($('#real_sell_amt').val());
		$('#pay_lockr_no').val($('#lockr_no').val());
		$('#pay_lockr_gendr_set').val($("input[name='lockr_gendr_set']:checked").val());
		
		if ( $('#radioPayIssue1').is(':checked') == true )
		{
			$('#pay_issue').val('N');
		}
		
		if ( $('#radioPayIssue2').is(':checked') == true )
		{
			$('#pay_issue').val('Y');
		}
		
		$('#form_payment_submit').submit();
	}
});
    
// POS 결제 버튼 클릭
$('#btn_pos_pay').click(function(){
	if ( $('#card_amt').val() == '' || $('#card_amt').val() == 0 )
	{
		alertToast('error','카드결제 금액을 입력하세요.');
		return;
	}

	if ( parseInt($('#misu_amt').val()) < 0 )
	{
		alertToast('error','미수금은 - 가 될 수 없습니다. 금액을 정확히 입력하세요');
		return;
	}

	var mem_id = $('#mem_id').val();
	var sell_event_sno = $('#sell_event_sno').val();
	var card_amt = $('#card_amt').val();
	
	// POS 결제 처리
	ToastConfirm.fire({
		icon: 'question',
		title: 'POS 결제 진행',
		html: '<font color="#000000">POS 단말기로 결제를 진행하시겠습니까?<br>금액: ' + card_amt + '원</font>',
		showConfirmButton: true,
		showCancelButton: true,
		confirmButtonColor: '#28a745',
		confirmButtonText: '결제 진행',
		cancelButtonText: '취소'
	}).then((result) => {
		if (result.isConfirmed) {
			// POS 결제 요청
			jQuery.ajax({
				url: '/teventmain/ajax_event_buy_pos_proc',
				type: 'POST',
				data: {
					mem_id: mem_id,
					sell_event_sno: sell_event_sno,
					card_amt: card_amt
				},
				dataType: 'json',
				success: function (response) {
					if (response.result == 'success') {
						// POS 결제 성공
						$('#van_appno').val(response.approval_no);
						$('#van_appno_sno').val(response.transaction_id);
						$('#card_appno').val(response.approval_no);
						
						$('#btn_pay_conn').attr("disabled",true);
						$('#btn_pay_direct').attr("disabled",true);
						$('#btn_pos_pay').attr("disabled",true);
						$('#card_amt').attr("readonly",true);
						
						alertToast('success','POS 결제가 완료되었습니다.');
						
						// 결제 정보 설정
						$('#pay_card_amt').val($('#card_amt').val());
						$('#pay_acct_amt').val($('#acct_amt').val());
						$('#pay_acct_no').val($('#acct_no').val());
						$('#pay_cash_amt').val($('#cash_amt').val());
						$('#pay_misu_amt').val($('#misu_amt').val());
						$('#pay_exr_s_date').val($('#exr_s_date').val());
						$('#pay_real_sell_amt').val($('#real_sell_amt').val());
						$('#pay_lockr_no').val($('#lockr_no').val());
						$('#pay_lockr_gendr_set').val($("input[name='lockr_gendr_set']:checked").val());
						
						// POS 결제 플래그 설정
						$('<input>').attr({
							type: 'hidden',
							name: 'use_pos',
							value: 'Y'
						}).appendTo('#data_form');
						
						$('<input>').attr({
							type: 'hidden',
							name: 'pos_transaction_id',
							value: response.transaction_id
						}).appendTo('#data_form');
					} else {
						alertToast('error', response.message || 'POS 결제 실패');
					}
				},
				error: function() {
					alertToast('error','POS 결제 요청 중 오류가 발생했습니다.');
				}
			});
		}
	});
});

// 수동결제 버튼 클릭
$('#btn_pay_direct').click(function(){

	if ( $('#card_amt').val() == '' || $('#card_amt').val() == 0 )
	{
		alertToast('error','카드결제 금액을 입력하세요.');
		return;
	}

	if ( parseInt($('#misu_amt').val()) < 0 )
	{
		alertToast('error','미수금은 - 가 될 수 없습니다. 금액을 정확히 입력하세요');
		return;
	}

	var mem_id = $('#mem_id').val();
	var sell_event_sno = $('#sell_event_sno').val();
	var card_amt = $('#card_amt').val();
	var card_appno = $('#card_appno').val();
	
	var params = "mem_id="+mem_id+"&sell_event_sno="+sell_event_sno+"&card_amt="+card_amt+"&card_appno="+card_appno;
	
	jQuery.ajax({
        url: '/teventmain/ajax_event_buy_van_direct_proc',
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
				$('#van_appno').val(json_result['appno']);
				$('#van_appno_sno').val(json_result['appno_sno']);
				
				$('#btn_pay_conn').attr("disabled",true);
				$('#btn_pay_direct').attr("disabled",true);
				$('#card_amt').attr("readonly",true);
				
				// alertToast('success','카드 수동결제가 완료 되었습니다.');
				$('#pay_card_amt').val($('#card_amt').val());
				$('#pay_acct_amt').val($('#acct_amt').val());
				$('#pay_acct_no').val($('#acct_no').val());
				$('#pay_cash_amt').val($('#cash_amt').val());
				$('#pay_misu_amt').val($('#misu_amt').val());
				$('#pay_exr_s_date').val($('#exr_s_date').val());
				$('#pay_real_sell_amt').val($('#real_sell_amt').val());
				$('#pay_lockr_no').val($('#lockr_no').val());
				$('#pay_lockr_gendr_set').val($("input[name='lockr_gendr_set']:checked").val());
				
				
				
				if ( $('#radioPayIssue1').is(':checked') == true )
				{
					$('#pay_issue').val('N');
				}
				
				if ( $('#radioPayIssue2').is(':checked') == true )
				{
					$('#pay_issue').val('Y');
				}
				
				$('#form_payment_submit').submit();
				
			} else 
			{
				alertToast('error','결제에 실패 하였습니다.');
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
	
});


// ===================== Modal Script [ START ] ===========================
$("#script_modal_sm").click(function(){
	$("#modal-sm").modal("show");
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