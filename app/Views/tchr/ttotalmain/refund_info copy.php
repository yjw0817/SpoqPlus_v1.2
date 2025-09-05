<style>

/* General Layout & Spacing */
.panel-body {
    padding: 25px; /* Increase padding for more breathing room inside panels */
}

/* Card/Panel Styling */
.panel {
    border-radius: 8px; /* Slightly more rounded panels */
    overflow: hidden; /* Ensures content respects border-radius */
    box-shadow: 0 4px 12px rgba(0,0,0,0.06); /* Softer, more modern shadow */
    border: none; /* Remove default panel border if present, rely on shadow */
    margin-bottom: 25px; /* Add space between stacked panels */
}

.panel-inverse,
.card { /* Apply consistent styling to both .panel-inverse and .card elements */
    background-color: #ffffff; /* Ensure background is white for content area */
    border: 1px solid #e0e0e0; /* Add a subtle border for definition */
}

/* Headings within panels */
.page-header {
    border-bottom: 1px solid #e0e0e0; /* Softer border under headings */
    padding: 15px 25px; /* Consistent padding with panel-body */
    background-color: #f8f8f8; /* Slightly off-white background for headings */
    color: #333; /* Darker text for headings */
    font-size: 1.15rem; /* Slightly larger panel titles */
    font-weight: 600; /* Bolder panel titles */
    margin-top: 0;
    margin-bottom: 0;
}

/* Tables */
.table-bordered td,
.table-bordered th {
    padding: 12px 15px !important; /* Increase padding in table cells for better readability */
    vertical-align: middle; /* Vertically align text in table cells */
    border-color: #e0e0e0 !important; /* Lighter border color for a softer look */
}

.table thead th {
    background-color: #f0f0f0; /* Light background for table headers */
    color: #555;
    font-weight: 600;
}

.table tbody tr:nth-child(even) {
    background-color: #f9f9f9; /* Zebra striping for readability */
}

table.table-hover tbody tr:hover {
    background-color: #e9f5ff !important; /* Lighter blue on hover */
    outline: none; /* Remove default outline */
}

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

.table .bg-success {
    background-color: #d4edda !important;
    color: #155724 !important;
    font-weight: 700;
}

/* --- Input Fields & Forms - TARGETED FIXES --- */

/* Base style for form controls, overriding framework defaults if needed */
/* **CRITICAL CHANGE**: Explicitly set border property with !important */
.form-control {
    border-radius: 4px;
    border: 1px solid #dcdcdc !important; /* Force a single 1px border */
    padding: 8px 12px; /* Consistent padding for all form controls */
    box-shadow: inset 0 1px 2px rgba(0,0,0,.04);
    transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    width: auto !important; /* Let flexbox control width */
    margin-left: 0 !important; /* Remove any default margin-left */
    flex-grow: 1; /* Allow inputs to grow within their flex container */
    box-sizing: border-box; /* Include padding and border in the element's total width and height */
    min-height: calc(0.9rem * 1.25 + 8px * 2 + 2px); /* Calculate minimum height based on font-size, line-height, padding, border */
}

/* Target specific inputs that previously had inline styles */
input[type="text"][style*="width:100px; margin-left:5px"],
input[type="text"][style*="width:140px; margin-left:5px"] {
    width: 120px !important; /* Force desired width for these inputs */
    max-width: 120px !important; /* Ensure it doesn't expand beyond this */
    margin-left: 0 !important; /* Remove the problematic margin */
    padding: 8px 12px !important; /* Ensure padding is applied */
    border: 1px solid #dcdcdc !important; /* Ensure these also get the correct border */
    box-sizing: border-box; /* Re-confirm box-sizing for these specific overrides */
}

/* Ensure all relevant inputs follow the desired style */
/* This rule applies consistent max-width, padding, and border to ALL targeted inputs */
input[type="text"].text-right,
input[type="password"].form-control,
input[type="text"].datepp,
input[type="text"][name^="card_appno"],
input[type="text"][name^="cash_amt"],
input[type="text"][name^="card_amt"],
input[type="text"][name="use_amt"],
input[type="text"][name="misu_amt"] {
    max-width: 120px !important; /* Apply consistent max-width to all these inputs */
    padding: 8px 12px !important; /* Ensure padding is consistent */
    border: 1px solid #dcdcdc !important; /* Ensure consistent 1px border */
    margin-left: 0 !important; /* Re-confirm margin reset (will be overridden for appno) */
}

/* NEW RULE: Apply margin-left specifically to the approval number inputs */ 
input[type="text"][name^="card_appno"],
input[type="text"][name^="deposit_card_appno"],
input[type="text"][name^="etc_card_appno"] {
    margin-left: 5px !important; /* Adds 15px space to the left of 승인번호 input */
}



.form-control:focus,
input[type="text"].text-right:focus,
input[type="password"].form-control:focus,
input[type="text"].datepp:focus,
input[type="text"][name^="card_appno"]:focus,
input[type="text"][name^="cash_amt"]:focus,
input[type="text"][name^="card_amt"]:focus,
input[type="text"][name="use_amt"]:focus,
input[type="text"][name="misu_amt"]:focus {
    border-color: #a0c3e6 !important; /* Ensure focus border color is 1px */
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important; /* Ensure focus shadow applies */
    outline: none;
}

/* Readonly Inputs */
.input-readonly {
    background-color: #f5f5f5;
    color: #777;
    cursor: default;
    border: 1px solid #dcdcdc !important; /* Ensure readonly also has 1px border */
}

/* Input Group Labels (e.g., "판매금액", "카드결제") */
.input-group-text {
    background-color: #f0f0f0;
    color: #555;
    border-color: #dcdcdc !important; /* Force 1px border for labels */
    font-weight: 500;
    min-height: calc(0.9rem * 1.25 + 8px * 2 + 2px); /* Match height of inputs */
    display: flex;
    align-items: center; /* Vertically center label text */
}

/* Consistent label widths for input groups */
.input-group-append > span.input-group-text,
.input-group-prepend > span.input-group-text {
    min-width: 120px !important; /* Force consistent minimum width */
    width: 120px !important; /* Force this width */
    text-align: left;
    padding-left: 15px;
    flex-shrink: 0;
}

/* Layout for Payment/Refund Info Sections */
.input-group.input-group-sm {
    display: flex; /* Use flexbox for the input group */
    align-items: center; /* Vertically align items */
    margin-bottom: 15px; /* Space between input rows */
    flex-wrap: nowrap; /* Prevent wrapping for better horizontal alignment */
}

/* Specific adjustment for explanatory text (e.g., * 카드결제시...) */
.input-group span[style*="font-size:0.9em;"] {
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

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}
.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
    box-shadow: 0 2px 4px rgba(0,0,0,.1);
}
.btn-success:hover {
    background-color: #218838;
    border-color: #218838;
    box-shadow: 0 4px 8px rgba(0,0,0,.15);
}

/* Ensure .btn-flat within an input-group aligns with inputs and is hidden */
.input-group .btn-flat {
    padding: 0 !important; /* No padding when hidden */
    height: 0 !important; /* No height when hidden */
    width: 0 !important; /* No width when hidden */
    margin: 0 !important; /* No margin when hidden */
    border: none !important; /* No border when hidden */
    display: none !important; /* Completely hide the button */
    overflow: hidden; /* Ensure nothing is visible */
}


/* Small buttons like "0 원" and "카드취소" within tables */
.table .btn-sm,
.table .btn-xs {
    padding: 5px 10px;
    font-size: 0.8rem;
    border-radius: 4px;
}

/* Specific style for the red "0 원" button */
.panel-body .btn-danger.btn-sm {
    background-color: #dc3545; /* Red background */
    color: #fff; /* White text */
    border-color: #dc3545;
    font-weight: 700;
    /* --- CRITICAL CHANGE HERE --- */
    padding: 2px 8px !important; /* Reduced vertical padding (was 5px 12px) */
    /* -------------------------- */
    margin-left: 5px; /* Add a little space */
    vertical-align: middle; /* Ensure it aligns nicely with text */
    line-height: 1; /* Make line-height very compact */
    height: auto; /* Allow auto height based on new padding */
}

/* You might also want to ensure the parent td accommodates well */
.table td {
    line-height: normal; /* Ensure the table cell doesn't have an excessive line-height */
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

/* Ensure font consistency */
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

/* Additional specific styles for refund page radio buttons (일반환불, 교체환불) */
.card-footer .icheck-primary.d-inline {
    margin-left: 15px;
    margin-right: 0;
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
					<!-- CARD HEADER [END] -->
					
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
						<h3 class="panel-title">환불 상품 정보</h3>
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
										<?php if ($event_info['CLAS_DV'] == "21" || $event_info['CLAS_DV'] == "22") :?>
										이용시작일 ( <?php echo $event_info['EXR_S_DATE']?> )
										<?php else :?>
										<?php echo $event_info['USE_PROD']?> <?php echo $sDef['USE_UNIT'][$event_info['USE_UNIT']]?>
										( <?php echo $event_info['EXR_S_DATE']?> ~ <?php echo $event_info['EXR_E_DATE']?> )
										<?php endif;?>
									</td>
									<td class='bg-olive' style='width:150px'>수업횟수</td>
									<td><?php echo $event_info['CLAS_CNT']?></td>
									<td class='bg-olive' style='width:150px'>입장상세</td>
									<td><?php echo $sDef['ACC_RTRCT_MTHD'][$event_info['ACC_RTRCT_MTHD']]?></td>
								</tr>
								
								<tr class="">
									<td class='bg-olive' style='width:150px'>판매가격 (미수금)</td>
									<td><?php echo number_format($event_info['SELL_AMT'])?> 원 
    									<button type='button' class='btn btn-danger btn-sm'>
    										<?php echo number_format($event_info['RECVB_AMT'])?> 원
    									</button> 
									</td>
									<td class='bg-olive' style='width:150px'>휴회가능일</td>
									<td><?php echo $event_info['DOMCY_DAY']?></td>
									<td class='bg-olive' style='width:150px'>휴회가능횟수</td>
									<td><?php echo $event_info['DOMCY_CNT']?></td>
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
			<div class="col-md-4">
				
				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h3 class="panel-title">환불 결제내역</h3>
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
						<!-- 환불정보 구성 [START] -->
						<table class="table table-bordered table-hover table-striped col-md-12">
							<thead>
								<tr class='text-center'>
									<th style='width:80px'>결제일자</th>
									<th style='width:100px'>결제수단</th>
									<th style='width:100px'>결제금액</th>
									
								</tr>
							</thead> 
							<tbody>
								<?php 
								foreach($refund_list as $r) :
								    $backColor = "";
								?>
								<tr style="background-color: <?php echo $backColor ?>;">
									<td><?php echo $r['PAYMT_DATE']?></td>
									<td class='text-center'><?php echo $sDef['PAYMT_MTHD'][$r['PAYMT_MTHD']]?></td>
									<td style="text-align:right"><?php echo number_format($r['PAYMT_AMT'])?></td>
								</tr>
								<?php 
								endforeach;
								?>
								
								<tr class='bg-success'>
									<td class='text-center' colspan='2'>환불금액</td>
									<td style="text-align:right" id="total_refund"><?php echo number_format($refund_info['refund_amt'])?></td>
									<input type='hidden' id="ori_total_refund" value="<?php echo number_format($refund_info['refund_amt'])?>" />
								</tr>
							</tbody>
						</table>
						<!-- 환불정보 구성 [END] -->
					</div>
					<!-- CARD BODY [END] -->
					<!-- CARD FOOTER [START] -->
					<div class="card-footer clearfix">
						<!-- BUTTON [START] -->
                        <ul class=" float-left">
                        	<li class="ac-btn"><button type="button" class="btn btn-block btn-info btn-sm" onclick="go_info_mem('<?php echo $mem_info['MEM_SNO']?>');">회원정보로 가기</button></li>
							<li class="ac-btn"><button type="button" class="btn btn-block btn-info btn-sm" onclick="location.href='/teventmain/event_buy';">상품구매로 가기</button></li>
                        </ul>
                        <!-- BUTTON [END] -->
						<!-- BUTTON [START] -->
						<!-- BUTTON [END] -->
						
					</div>
					<!-- CARD FOOTER [END] -->
				</div>
			</div>
			
			
			
			<div class="col-md-4">
				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h3 class="panel-title">이용료 결제</h3>
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
					
						<!-- [결제 할 이용료] [START] -->
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text" style='width:100px'>결제할 이용료</span>
                        	</span>
                        	<input type="text" class="input-readonly text-right" style='width:100px; margin-left:5px' placeholder="" name="use_amt" id="use_amt"  value="<?php echo number_format($refund_info['use_amt'])?>" readonly />
                        	<input type="hidden" id="ori_use_amt" value="<?php echo number_format($refund_info['use_amt'])?>" />
                        </div>
                        <!-- [결제 할 이용료] [END] -->
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
                            <span class="input-group-append">
                            	<button type="button" class="btn btn-danger btn-flat" style='margin-left:5px' id="btn_pay_direct">수동결제</button>
                            </span>
                        	<input type="text" class="" style='width:100px; margin-left:5px' placeholder="승인번호" name="card_appno" id="card_appno"  value="" />
                        	
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
                        <!-- [현금결제] [미수금] --
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text" style='width:100px'>미수금</span>
                        	</span>
                        	<input type="text" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="misu_amt" id="misu_amt"  value="" />
                        </div>
                        <!-- [현금결제] [미수금] -->
                        <!-- [계좌이체] [START] --
                        <div class="input-group input-group-sm mb-1">
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
                
                
                
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h3 class="panel-title">위약금 결제</h3>
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
					
                		<!-- [카드결제] [START] -->
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text" style='width:100px'>카드결제</span>
                        	</span>
                        	<input type="text" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="deposit_card_amt" id="deposit_card_amt"  value="" />
                        	<!-- 
                        	<span class="input-group-append">
                            	<button type="button" class="btn btn-info btn-flat" style='margin-left:5px' id="btn_pay_conn">결제</button>
                            </span>
                             -->
                            <span class="input-group-append">
                            	<button type="button" class="btn btn-danger btn-flat" style='margin-left:5px' id="btn_deposit_pay_direct">수동결제</button>
                            </span>
                        	<input type="text" class="" style='width:100px; margin-left:5px' placeholder="승인번호" name="deposit_card_appno" id="deposit_card_appno"  value="" />
                        	
                        </div>
                        <!-- [카드결제] [END] -->
                        <!-- [현금결제] [START] -->
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text" style='width:100px'>현금결제</span>
                        	</span>
                        	<input type="text" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="deposit_cash_amt" id="deposit_cash_amt"  value="" />
                        </div>
                        <!-- [현금결제] [END] -->
                        <!-- [현금결제] [미수금] --
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text" style='width:100px'>미수금</span>
                        	</span>
                        	<input type="text" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="misu_amt" id="misu_amt"  value="" />
                        </div>
                        <!-- [현금결제] [미수금] -->
                        <!-- [계좌이체] [START] --
                        <div class="input-group input-group-sm mb-1">
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
                
                
                
                
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h3 class="panel-title">기타금액 결제</h3>
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
					
                		<!-- [카드결제] [START] -->
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text" style='width:100px'>카드결제</span>
                        	</span>
                        	<input type="text" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="etc_card_amt" id="etc_card_amt"  value="" />
                        	<!-- 
                        	<span class="input-group-append">
                            	<button type="button" class="btn btn-info btn-flat" style='margin-left:5px' id="btn_pay_conn">결제</button>
                            </span>
                             -->
                            <span class="input-group-append">
                            	<button type="button" class="btn btn-danger btn-flat" style='margin-left:5px' id="btn_etc_pay_direct">수동결제</button>
                            </span>
                        	<input type="text" class="" style='width:100px; margin-left:5px' placeholder="승인번호" name="etc_card_appno" id="etc_card_appno"  value="" />
                        	
                        </div>
                        <!-- [카드결제] [END] -->
                        <!-- [현금결제] [START] -->
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text" style='width:100px'>현금결제</span>
                        	</span>
                        	<input type="text" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="etc_cash_amt" id="etc_cash_amt"  value="" />
                        </div>
                        <!-- [현금결제] [END] -->
                        <!-- [현금결제] [미수금] --
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text" style='width:100px'>미수금</span>
                        	</span>
                        	<input type="text" class="text-right" style='width:100px; margin-left:5px' placeholder="" name="misu_amt" id="misu_amt"  value="" />
                        </div>
                        <!-- [현금결제] [미수금] -->
                        <!-- [계좌이체] [START] --
                        <div class="input-group input-group-sm mb-1">
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
                
                
                
			</div>
			
			
			
			
			
			<div class="col-md-4">
				
				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h3 class="panel-title">환불 하기</h3>
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
						<!-- 환불정보 구성 [START] -->
						<table class="table table-bordered table-hover table-striped col-md-12">
							<thead>
								<tr class='text-center'>
									<th style='width:100px'>환불수단</th>
									<th style='width:100px'>환불금액</th>
									<th style='width:80px'>Action</th>
									
								</tr>
							</thead> 
							<tbody>
								<tr class=''>
									<td class='text-center bg-success'>이용료 결제</td>
									<td style="text-align:right" ><?php echo number_format($refund_info['use_amt'])?></td>
									<td></td>
								</tr>
								<?php 
								foreach($refund_list as $r) :
								    $backColor = "";
								?>
								<tr style="background-color: <?php echo $backColor ?>;">
									<td class='text-center'><?php echo $sDef['PAYMT_MTHD'][$r['PAYMT_MTHD']]?></td>
									<td style="text-align:right"><?php echo number_format($r['PAYMT_AMT'])?></td>
									
									<?php
									$refund_btn_disabled = "disabled";
									if ($refund_info['use_amt'] === "0" || is_null($refund_info['use_amt'])) {
										$refund_btn_disabled = "";
									}
									?>
									
									<?php if($r['PAYMT_MTHD'] != "01") : ?>
									<td>
										<button type='button' class='btn btn-warning btn-xs btn-cc' id="refund_btn_<?php echo $r['PAYMT_MGMT_SNO']?>" onclick="refund_cancel('<?php echo $r['PAYMT_MGMT_SNO']?>','<?php echo $r['PAYMT_AMT']?>','acct');">
    										계좌이체
    									</button>
									</td>
									<?php else :?>
									<td>
										<button type='button' class='btn btn-danger btn-xs btn-cc'  id="refund_btn_<?php echo $r['PAYMT_MGMT_SNO']?>" onclick="refund_cancel('<?php echo $r['PAYMT_MGMT_SNO']?>','<?php echo $r['PAYMT_AMT']?>','card');">
    										카드취소
    									</button>
									</td>
									<?php endif; ?>
								</tr>
								<?php 
								endforeach;
								?>
								
								
								
							</tbody>
						</table>


						<!-- 환불정보 구성 [END] -->
					</div>
					<!-- CARD BODY [END] -->
					<!-- CARD FOOTER [START] -->
					<div class="card-footer clearfix">
						<!-- BUTTON [START] -->
                        <!-- BUTTON [END] -->
						<!-- BUTTON [START] -->
						
						<div class="icheck-primary d-inline">
                            <input type="radio" id="radioRefundIssue1" name="type_refund_issue">
                            <label for="radioRefundIssue1">
                            	<small>일반환불</small>
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="radioRefundIssue2" name="type_refund_issue">
                            <label for="radioRefundIssue2">
                            	<small>교체환불</small>
                            </label>
                        </div>
                        
						
						<ul class="pagination pagination-sm m-0 float-right">
							<li class="ac-btn"><button type="button" class="btn btn-block btn-success btn-sm" id='btn_refund_confirm' disabled>환불 완료</button></li>
						</ul>
						
						<!-- BUTTON [END] -->
						
					</div>
					<!-- CARD FOOTER [END] -->
			
				</div>
			
			</div>
			
			
		</div>
	</div>

<form name="form_payment_submit" id="form_payment_submit" method="post" action="/teventmain/refund_proc">
    <input type='hidden' name='mem_sno' id='mem_sno' value="<?php echo $mem_info['MEM_SNO']?>" />
    <input type='hidden' name='mem_id' id='mem_id' value="<?php echo $mem_info['MEM_ID']?>" />
    <input type='hidden' name='mem_nm' id='mem_nm' value="<?php echo $mem_info['MEM_NM']?>" />
    
    <input type='hidden' name='buy_event_sno' id='buy_event_sno' value="<?php echo $event_info['BUY_EVENT_SNO']?>" />

    <input type="hidden" name="van_appno" id="van_appno" />
    <input type="hidden" name="van_appno_sno" id="van_appno_sno" />
    
    <input type="hidden" name="pay_card_amt" id="pay_card_amt" />
    <input type="hidden" name="pay_acct_amt" id="pay_acct_amt" />
    <input type="hidden" name="pay_acct_no" id="pay_acct_no" />
    <input type="hidden" name="pay_cash_amt" id="pay_cash_amt" />
    <input type="hidden" name="pay_misu_amt" id="pay_misu_amt" />
    
    <input type="hidden" name="refund_issue" id="refund_issue" />
    
    <!-- 위약금 , 기타금액 결제 추가 -->
    <input type="hidden" name="van_deposit_appno" id="van_deposit_appno" />
    <input type="hidden" name="van_deposit_appno_sno" id="van_deposit_appno_sno" />
    
    <input type="hidden" name="van_etc_appno" id="van_etc_appno" />
    <input type="hidden" name="van_etc_appno_sno" id="van_etc_appno_sno" />
    
    <input type="hidden" name="pay_deposit_card_amt" id="pay_deposit_card_amt" />
    <input type="hidden" name="pay_deposit_cash_amt" id="pay_deposit_cash_amt" />
    
    <input type="hidden" name="pay_etc_card_amt" id="pay_etc_card_amt" />
    <input type="hidden" name="pay_etc_cash_amt" id="pay_etc_cash_amt" />
    
    <?php foreach($refund_list as $r) :?>
    <input type="hidden" name="refund_paymt_mgmt_sno[<?php echo $r['PAYMT_MGMT_SNO']?>]" id="refund_paymt_mgmt_sno_<?php echo $r['PAYMT_MGMT_SNO']?>" value="N" />
    <?php endforeach;?>
    
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
})

function go_info_mem(mem_sno)
{
	location.href="/ttotalmain/info_mem/"+mem_sno;
}

// 카드결제금액 입력
$('#card_amt').keyup(function(){
	var d_amt = onlyNum( $('#card_amt').val() );
	$('#card_amt').val(currencyNum(d_amt));
	calu_amt();
});

// 현금결제금액 입력
$('#cash_amt').keyup(function(){
	var d_amt = onlyNum( $('#cash_amt').val() );
	$('#cash_amt').val(currencyNum(d_amt));
	calu_amt();
});


// [위약금 관련]
$('#deposit_card_amt').keyup(function(){
	var d_amt = onlyNum( $('#deposit_card_amt').val() );
	$('#deposit_card_amt').val(currencyNum(d_amt));
});

// 현금결제금액 입력
$('#deposit_cash_amt').keyup(function(){
	var d_amt = onlyNum( $('#deposit_cash_amt').val() );
	$('#deposit_cash_amt').val(currencyNum(d_amt));
});

// [기타금액 결제 관련]
$('#etc_card_amt').keyup(function(){
	var d_amt = onlyNum( $('#etc_card_amt').val() );
	$('#etc_card_amt').val(currencyNum(d_amt));
});

// 현금결제금액 입력
$('#etc_cash_amt').keyup(function(){
	var d_amt = onlyNum( $('#etc_cash_amt').val() );
	$('#etc_cash_amt').val(currencyNum(d_amt));
});


/*
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
*/

function calu_amt()
{

	
            	
	var g_basic_amt = $('#ori_use_amt').val();
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
	
	g_basic_amt = g_basic_amt - (parseInt(g_card_amt) + parseInt(g_cash_amt));
	$('#use_amt').val(currencyNum(g_basic_amt));
	
	/*
	if ($('#use_amt').val() == '')
	{
		$('.btn-cc').attr('disabled',false);
	} else 
	{
		$('.btn-cc').attr('disabled',true);
	}	
	*/
	var total_refund = $('#ori_total_refund').val();
	total_refund = onlyNum(total_refund);
    var re_total_refund = parseInt(total_refund) + parseInt(g_card_amt) + parseInt(g_cash_amt);
	
	$('#total_refund').text(currencyNum(re_total_refund));
	
	/*
	if ( $('#total_refund').text() == $('#re_total_refund').val() )
	{
		$('#btn_refund_confirm').attr('disabled',false);
	} else 
	{
		$('#btn_refund_confirm').attr('disabled',true);
	}
	*/
	
}

function refund_cancel(paymt_sno,paymt_amt,ctype)
{
	var mem_id = $('#mem_id').val();
	var buy_event_sno = $('#buy_event_sno').val();
	
	//var card_amt = $('#card_amt').val();
	//var card_appno = $('#card_appno').val();
	//var params = "mem_id="+mem_id+"&buy_event_sno="+buy_event_sno+"&paymt_amt="+paymt_amt+"&card_appno="+card_appno+"&paymt_sno="+paymt_sno;
	if(ctype == 'card')
	{
		var params = "mem_id="+mem_id+"&buy_event_sno="+buy_event_sno+"&paymt_amt="+paymt_amt+"&paymt_sno="+paymt_sno;
		jQuery.ajax({
			url: '/teventmain/ajax_event_buy_van_direct_cancel_proc',
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
				
				console.log(json_result);
				
				if (json_result['result'] == 'true')
				{
					var re_amt = $('#total_refund').text();
					re_amt = onlyNum(re_amt);
					paymt_amt = onlyNum(paymt_amt);
					
					console.log(paymt_amt);
					
					re_amt = parseInt(re_amt) - parseInt(paymt_amt);
					
					if (re_amt == 0 )
					{
						$('#total_refund').text("0");
						$('#btn_refund_confirm').attr('disabled',false);
					} else 
					{
						$('#total_refund').text(currencyNum(re_amt));
						$('#btn_refund_confirm').attr('disabled',true);
					}
					
					$('#refund_btn_'+paymt_sno).attr("disabled",true); // 두번 결제 취소를 막기 위한 버튼 비활성화
					$('#refund_paymt_mgmt_sno_'+paymt_sno).val(json_result['appno_sno']);
					alertToast('success','결제취소가 완료 되었습니다.');
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
	}
	else if(ctype == 'cash')
	{
		
	}
}

$('#btn_refund_confirm').click(function(){
	$('#pay_card_amt').val($('#card_amt').val());
	$('#pay_cash_amt').val($('#cash_amt').val());
	
	// 위약금 , 기타금액 [추가 시작]
	$('#pay_deposit_card_amt').val($('#deposit_card_amt').val());
	$('#pay_deposit_cash_amt').val($('#deposit_cash_amt').val());
	
	$('#pay_etc_card_amt').val($('#etc_card_amt').val());
	$('#pay_etc_cash_amt').val($('#etc_cash_amt').val());
	// 위약금 , 기타금액 [추가 끝]
	
	$('#pay_acct_amt').val('');
	$('#pay_acct_no').val('');
	$('#pay_misu_amt').val('');
	
	//$('#pay_acct_amt').val($('#acct_amt').val());
	//$('#pay_acct_no').val($('#acct_no').val());
	//$('#pay_misu_amt').val($('#misu_amt').val());
	
	if ( $('#card_amt').val() != "" )
	{
		if ( $('#card_amt').attr("readonly") == undefined )
		{
			
			$('#btn_pay_direct').click();
		}
	} else if ( $('#deposit_card_amt').val() != "" )
	{
		if ( $('#deposit_card_amt').attr("readonly") == undefined )
		{
			$('#btn_deposit_pay_direct').click();
		}
	} else if ( $('#etc_card_amt').val() != "" )
	{
		if ( $('#etc_card_amt').attr("readonly") == undefined )
		{
			$('#btn_etc_pay_direct').click();
		}
	} else
	{
		if ( $('#radioRefundIssue1').is(':checked') == false && $('#radioRefundIssue2').is(':checked') == false )
		{
			alertToast('error','일반환불,교체환불중 하나를 선택하세요');
			return;
		}
		
		if ( $('#radioRefundIssue1').is(':checked') == true )
		{
			$('#refund_issue').val('N');
		}
		
		if ( $('#radioRefundIssue2').is(':checked') == true )
		{
			$('#refund_issue').val('Y');
		}
		
		$('#form_payment_submit').submit();
	}
});
    
// 수동결제 버튼 클릭
$('#btn_pay_direct').click(function(){
	var mem_id = $('#mem_id').val();
	var sell_event_sno = $('#sell_event_sno').val();
	var card_amt = $('#card_amt').val();
	var card_appno = $('#card_appno').val();
	
	if ( onlyNum($('#use_amt').val()) < 0 )
	{
		alertToast('error','이용료 결제 금액이 너무 커서 결제 할 수 없습니다.');
		return;
	}
	
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
				
				//$('#btn_pay_conn').attr("disabled",true);
				$('#btn_pay_direct').attr("disabled",true);
				$('#card_amt').attr("readonly",true);
				
				if ( $('#deposit_card_amt').val() != "" )
				{
					if ( $('#deposit_card_amt').attr("readonly") == undefined )
					{
						$('#btn_deposit_pay_direct').click();
					}
				} else if ( $('#etc_card_amt').val() != "" )
				{
					if ( $('#etc_card_amt').attr("readonly") == undefined )
					{
						$('#btn_etc_pay_direct').click();
					}
				} else
				{
					if ( $('#radioRefundIssue1').is(':checked') == false && $('#radioRefundIssue2').is(':checked') == false )
					{
						alertToast('error','일반환불,교체환불중 하나를 선택하세요');
						return;
					}
					
					if ( $('#radioRefundIssue1').is(':checked') == true )
					{
						$('#refund_issue').val('N');
					}
					
					if ( $('#radioRefundIssue2').is(':checked') == true )
					{
						$('#refund_issue').val('Y');
					}
					
					$('#form_payment_submit').submit();
				}
				
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



// 수동결제 버튼 클릭 (위약금)
$('#btn_deposit_pay_direct').click(function(){
	var mem_id = $('#mem_id').val();
	var sell_event_sno = $('#sell_event_sno').val();
	var card_amt = $('#deposit_card_amt').val();
	var card_appno = $('#deposit_card_appno').val();
	
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
				$('#van_deposit_appno').val(json_result['appno']);
				$('#van_deposit_appno_sno').val(json_result['appno_sno']);
				
				$('#btn_deposit_pay_direct').attr("disabled",true);
				$('#deposit_card_amt').attr("readonly",true);
				
				if ( $('#etc_card_amt').val() != "" )
				{
					if ( $('#etc_card_amt').attr("readonly") == undefined )
					{
						$('#btn_etc_pay_direct').click();
					}
				} else
				{
					if ( $('#radioRefundIssue1').is(':checked') == false && $('#radioRefundIssue2').is(':checked') == false )
					{
						alertToast('error','일반환불,교체환불중 하나를 선택하세요');
						return;
					}
					
					if ( $('#radioRefundIssue1').is(':checked') == true )
					{
						$('#refund_issue').val('N');
					}
					
					if ( $('#radioRefundIssue2').is(':checked') == true )
					{
						$('#refund_issue').val('Y');
					}
					
					$('#form_payment_submit').submit();
				}
				
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



// 수동결제 버튼 클릭 (기타)
$('#btn_etc_pay_direct').click(function(){
	var mem_id = $('#mem_id').val();
	var sell_event_sno = $('#sell_event_sno').val();
	var card_amt = $('#etc_card_amt').val();
	var card_appno = $('#etc_card_appno').val();
	
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
				$('#van_etc_appno').val(json_result['appno']);
				$('#van_etc_appno_sno').val(json_result['appno_sno']);
				
				$('#btn_etc_pay_direct').attr("disabled",true);
				$('#etc_card_amt').attr("readonly",true);
				
				if ( $('#radioRefundIssue1').is(':checked') == false && $('#radioRefundIssue2').is(':checked') == false )
				{
					alertToast('error','일반환불,교체환불중 하나를 선택하세요');
					return;
				}
				
				if ( $('#radioRefundIssue1').is(':checked') == true )
				{
					$('#refund_issue').val('N');
				}
				
				if ( $('#radioRefundIssue2').is(':checked') == true )
				{
					$('#refund_issue').val('Y');
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

$(document).ready(function() {



    // 카드결제 입력란 포커스 이벤트
    $('#card_amt').focus(function() {
        if (!$(this).val()) {
			$(this).val($("#use_amt").val());
        }
        calu_amt();
    });

    // 현금결제 입력란 포커스 이벤트
    $('#cash_amt').focus(function() {
        if (!$(this).val()) {
			$(this).val($("#use_amt").val());
        }
        calu_amt();
    });

   
});

</script>