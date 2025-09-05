<style>
.table th, .table td {
    padding: 0.3rem !important;
    font-size: 0.9rem;
}

.table-bordered th, .table-bordered td {
    border: 1px solid #a3a3a3;
}

table.table-hover tbody tr:hover {
    background-color: #81b1eb !important; 
}

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

/* Specific heading background for transfer page */
/* The HTML uses class="page-header bg-info" within card-primary, let's target that */
.card.card-primary .page-header.bg-info {
    background-color: #e6f6fb !important; /* A light info blue for header */
    color: #333 !important; /* Ensure readable text color */
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

/* Reduce padding for table cells specifically in the "환불 상품 정보" panel and "양도할 상품 정보" */
.panel:has(.panel-heading h3:contains("환불 상품 정보")) .panel-body .table-bordered td,
.panel:has(.panel-heading h3:contains("양도할 상품 정보")) .panel-body .table-bordered td {
    padding-top: 8px !important;
    padding-bottom: 8px !important;
}

/* --- Input Fields & Forms - TARGETED FIXES --- */

/* Base style for form controls, overriding framework defaults if needed */
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

/* Target specific inputs that previously had inline styles. This is crucial for consistency. */
input[type="text"][style*="width:100px; margin-left:5px"],
input[type="text"][style*="width:140px; margin-left:5px"] {
    width: 120px !important; /* Force desired width for these inputs */
    max-width: 120px !important; /* Ensure it doesn't expand beyond this */
    margin-left: 0 !important; /* Remove the problematic margin */
    padding: 8px 12px !important; /* Ensure padding is applied */
    border: 1px solid #dcdcdc !important; /* Ensure these also get the correct border */
    box-sizing: border-box; /* Re-confirm box-sizing for these specific overrides */
}

/* Ensure all relevant inputs follow the desired style and width */
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
}

/* Apply margin-left specifically to the approval number inputs */
/* This is for inputs like '승인번호' when they are adjacent to other inputs */
input[type="text"][name^="card_appno"] {
    margin-left: 15px !important; /* Adds 15px space to the left of 승인번호 input */
}

/* Style for the "라커번호" input, which has text-center class and custom style */
input[type="text"].text-center[name="lockr_no"] {
    width: 120px !important;
    max-width: 120px !important;
    margin-left: 0 !important;
    padding: 8px 12px !important;
    border: 1px solid #dcdcdc !important;
    box-sizing: border-box;
}

.form-control:focus,
input[type="text"].text-right:focus,
input[type="password"].form-control:focus,
input[type="text"].datepp:focus,
input[type="text"][name^="card_appno"]:focus,
input[type="text"][name^="cash_amt"]:focus,
input[type="text"][name^="card_amt"]:focus,
input[type="text"][name="use_amt"]:focus,
input[type="text"][name="misu_amt"]:focus,
input[type="text"].text-center[name="lockr_no"]:focus
{
    border-color: #a0c3e6 !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
    outline: none;
}

/* Readonly Inputs */
.input-readonly {
    background-color: #f5f5f5;
    color: #777;
    cursor: default;
    border: 1px solid #dcdcdc !important;
}

/* Input Group Labels (e.g., "판매금액", "카드결제") for payment/refund pages */
.input-group-text {
    background-color: #f0f0f0;
    color: #555;
    border-color: #dcdcdc !important;
    font-weight: 500;
    min-height: calc(0.9rem * 1.25 + 8px * 2 + 2px); /* Match height of inputs */
    display: flex;
    align-items: center; /* Vertically center label text */
}

/* Consistent label widths for input groups on payment/refund pages */
.input-group-append > span.input-group-text,
.input-group-prepend > span.input-group-text {
    min-width: 80px !important;
    width: 90px !important; /* Force this width */
    text-align: left;
    padding-left: 15px;
    flex-shrink: 0;
}

/* Layout for Payment/Refund Info Sections */
.input-group.input-group-sm {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    flex-wrap: nowrap;
}

/* Specific adjustment for explanatory text (e.g., * 카드결제시...) */
.input-group span[style*="font-size:0.9em;"] {
    margin-left: 10px;
    color: #777;
    white-space: nowrap;
    flex-shrink: 0;
}


/* --- Buttons (General) --- */
.btn {
    border-radius: 6px;
    padding: 10px 20px;
    font-weight: 600;
    transition: all 0.2s ease-in-out;
    font-size: 0.9rem; /* Consistent size for all general buttons */
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

/* Ensure .btn-flat within an input-group is hidden on payment/refund pages (except where needed) */
.input-group .btn-flat {
    padding: 0 !important;
    height: 0 !important;
    width: 0 !important;
    margin: 0 !important;
    border: none !important;
    display: none !important;
    overflow: hidden;
}

/* Small buttons like "0 원" and "카드취소" within tables */
.table .btn-sm,
.table .btn-xs {
    padding: 5px 10px;
    font-size: 0.8rem;
    border-radius: 4px;
}

.panel-body .btn-danger.btn-sm {
    background-color: #dc3545;
    color: #fff;
    border-color: #dc3545;
    font-weight: 700;
    padding: 2px 8px !important; /* Reduced vertical padding */
    margin-left: 5px;
    vertical-align: middle;
    line-height: 1;
    height: auto;
}

/* --- Specific Button Styles (Not in General Buttons) --- */

/* Style for the "번호지우기" button on payment page */
.input-group button.btn.btn-danger[onclick*="btn_lockr_no_clear"] {
    display: inline-flex !important; /* Make it visible */
    background-color: #6c757d !important; /* A neutral gray color (similar to btn-secondary) */
    border-color: #6c757d !important;
    color: #fff !important;
    padding: 8px 12px !important; /* Match input padding */
    font-size: 0.9rem !important; /* Match input font size */
    font-weight: 600 !important;
    border-radius: 4px !important; /* Match input border radius */
    white-space: nowrap !important; /* Prevent text from wrapping */
    height: calc(0.9rem * 1.25 + 8px * 2 + 2px) !important; /* Match input height */
    align-items: center !important; /* Vertically center text */
    justify-content: center !important; /* Horizontally center text */
    margin-left: 10px !important; /* Space between input and button */
    flex-shrink: 0; /* Prevent it from shrinking */
}

/* Hover effect for the "번호지우기" button */
.input-group button.btn.btn-danger[onclick*="btn_lockr_no_clear"]:hover {
    background-color: #5a6268 !important; /* Darker gray on hover */
    border-color: #545b62 !important;
}

/* --- NEW RULE: 수동결제 button on 양도 page (btn-flat is present here) --- */
/* Target the "수동결제" button on the transfer page specifically to make it visible and style it */
.input-group button.btn.btn-danger.btn-flat[id="btn_pay_direct"] {
    display: inline-flex !important; /* Make it visible */
    background-color: #6c757d !important; /* Neutral gray, or choose a subtle color for this action */
    border-color: #6c757d !important;
    color: #fff !important;
    padding: 8px 12px !important; /* Consistent padding with inputs */
    font-size: 0.9rem !important; /* Consistent font size */
    font-weight: 600 !important;
    border-radius: 4px !important; /* Consistent border radius */
    white-space: nowrap !important; /* Prevent text wrapping */
    height: calc(0.9rem * 1.25 + 8px * 2 + 2px) !important; /* Match input height */
    align-items: center !important;
    justify-content: center !important;
    margin-left: 10px !important; /* Space between input and button */
    flex-shrink: 0; /* Prevent shrinking */
    width: 95px !important; /* Explicit width to match previous designs */
}

.input-group button.btn.btn-danger.btn-flat[id="btn_pay_direct"]:hover {
    background-color: #5a6268 !important; /* Darker gray on hover */
    border-color: #545b62 !important;
}

/* --- Icons --- */
/* Style for gender icons */
.table td i.fa.fa-venus,
.table td i.fa.fa-mars {
    font-size: 0.9em; /* Slightly smaller than default text */
    vertical-align: baseline; /* Align with text */
    margin-right: 3px; /* Small space after icon */
}

.table td font[color="red"] {
    color: #dc3545 !important; /* Ensure red color is consistent */
}
.table td font[color="blue"] {
    color: #007bff !important; /* Ensure blue color is consistent */
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


/* --- CSS FOR LOCKER SETTING PAGE --- */

/* Parent container for inputs and button in locker settings */
.input-group2 {
    display: flex;
    align-items: center; /* Align items vertically in the center */
    flex-wrap: nowrap; /* Prevent wrapping onto multiple lines */
    margin-bottom: 15px; /* Space below this group */
}

/* Style for "시작번호" and "끝번호" labels */
.input-group-text4 {
    background-color: #f0f0f0; /* Consistent label background */
    color: #555;
    border: 1px solid #dcdcdc; /* Consistent label border */
    border-radius: 4px; /* Match input border-radius */
    padding: 8px 12px; /* Consistent padding with inputs */
    font-weight: 500;
    white-space: nowrap; /* Prevent label text from wrapping */
    min-width: auto; /* Allow natural width */
    flex-shrink: 0; /* Prevent from shrinking */
    display: inline-flex; /* Use inline-flex to center text if needed */
    align-items: center; /* Vertically align text within label */
    margin-right: 5px; /* Small space after label */
    height: calc(0.9rem * 1.25 + 8px * 2 + 2px); /* Match input height */
}

/* Style for "시작번호" and "끝번호" input fields */
.input-group-text3 { /* This class is applied directly to the input fields */
    border-radius: 4px;
    border: 1px solid #dcdcdc !important; /* Force single 1px border */
    padding: 8px 12px !important; /* Consistent padding */
    box-shadow: inset 0 1px 2px rgba(0,0,0,.04);
    transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    width: 80px !important; /* Set a fixed, smaller width */
    max-width: 80px !important; /* Prevent from growing */
    flex-grow: 0 !important; /* Prevent from growing */
    margin-left: 0 !important; /* Remove any default or inherited margin */
    margin-right: 10px; /* Add space to the right of the input (between "시작번호" input and "끝번호" label) */
    box-sizing: border-box; /* Crucial for correct width calculation */
    height: calc(0.9rem * 1.25 + 8px * 2 + 2px); /* Match label height */
}

/* Adjust margin specifically for the second input (e.g., '끝번호' input) */
.input-group2 .input-group-append:nth-of-type(2) + .input-group-text3 {
    margin-left: 0 !important; /* Ensure no extra margin from a previous rule */
}
/* Re-applying margin for the '끝번호' label (the second input-group-append) */
.input-group2 .input-group-append:nth-of-type(2) .input-group-text4 {
    margin-left: 5px; /* Small space before '끝번호' label */
}


/* Style for the "등록하기" button in locker settings */
.btn-success2 {
    background-color: #28a745; /* Green color */
    border-color: #28a745;
    border-radius: 6px;
    padding: 8px 15px; /* Adjusted padding to match input height, narrower horizontally */
    font-weight: 600;
    font-size: 0.9rem;
    color: #fff; /* White text */
    transition: all 0.2s ease-in-out;
    height: calc(0.9rem * 1.25 + 8px * 2 + 2px); /* Match height of inputs/labels */
    display: inline-flex; /* Use flex to vertically center text */
    align-items: center; /* Vertically center text */
    justify-content: center; /* Horizontally center text */
    margin-left: 15px; /* Space to the left of the button */
    flex-shrink: 0; /* Prevent from shrinking */
}

.btn-success2:hover {
    background-color: #218838;
    border-color: #218838;
}

/* Fix for .col_md-12 which might be a typo for .col-md-12 */
.col_md-12 {
    width: 100%;
    position: relative; /* Often needed for correct block formatting context */
    padding-left: 15px; /* Standard Bootstrap column padding */
    padding-right: 15px;
    box-sizing: border-box; /* Include padding in width */
}

/* General button styling (should be above custom button styles) */
.btn {
    border-radius: 6px;
    padding: 10px 20px;
    font-weight: 600;
    transition: all 0.2s ease-in-out;
    font-size: 0.9rem; /* Consistent size for all general buttons */
}

</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			
				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h3 class="panel-title">양도 할 상품</h3>
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
						<!-- TABLE [START] -->
						<table class="table table-bordered table-hover table-striped col-md-12">
							<thead>
								<tr class='text-center'>
									<th style='width:115px'>구매일시</th>
									<th style='width:80px'>판매상태</th>
									<th>판매상품명</th>
									<th style='width:60px'>기간</th>
									<th style='width:80px'>시작일</th>
									<th style='width:115px'>종료일</th>
									<th style='width:70px'>수업</th>
									<th style='width:70px'>휴회일</th>
									<th style='width:70px'>휴회수</th>
									<th style='width:100px'>판매금액</th>
									<th style='width:100px'>결제금액</th>
									<th style='width:100px'>미수금액</th>
									<th style='width:100px'>수업강사</th>
									<th style='width:100px'>판매강사</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo substr($data_buy_info['BUY_DATETM'],0,16)?></td>
									<td><?php echo $sDef['EVENT_STAT_RSON'][$data_buy_info['EVENT_STAT_RSON']]?></td>
									<td>
										<i class="fa fa-list" onclick="more_info_show();"></i> 
										<?php if($data_buy_info['LOCKR_SET'] == "Y") : 
												if ($data_buy_info['LOCKR_NO'] != '') :
													echo disp_locker($data_buy_info['LOCKR_KND'],$data_buy_info['LOCKR_GENDR_SET'],$data_buy_info['LOCKR_NO']);
												else :   
										?>
												<button type='button' class='btn btn-danger btn-xs' onclick="lockr_select('<?php echo $data_buy_info['MEM_SNO']?>','<?php echo $data_buy_info['BUY_EVENT_SNO']?>','<?php echo $data_buy_info['LOCKR_KND']?>','<?php echo $mem_info['MEM_GENDR']?>');">선택하기</button>;
										<?php
												endif ;
											  endif;
											  
											  if ($data_buy_info['ACC_RTRCT_DV'] == "01") echo "<small class='badge bg-success'>".$sDef['ACC_RTRCT_MTHD'][$data_buy_info['ACC_RTRCT_MTHD']]."</small>";
										?>
										<?php echo $data_buy_info['SELL_EVENT_NM']?> (<?php echo $data_buy_info['BUY_EVENT_SNO']?>)
									</td>
									
									<td style="text-align:right"><?php echo disp_produnit($data_buy_info['USE_PROD'],$data_buy_info['USE_UNIT'])?></td>
									<td><span id="<?php echo "exr_s_date_".$data_buy_info['BUY_EVENT_SNO']?>">
									
									<?php if($data_buy_info['EVENT_STAT'] == "01" && ($data_buy_info['CLAS_DV'] == "21" || $data_buy_info['CLAS_DV'] == "22") ) : ?>
									<button type='button' class='btn btn-info btn-xs' onclick="pt_use('<?php echo $data_buy_info['STCHR_ID']?>','<?php echo $data_buy_info['BUY_EVENT_SNO'] ?>');">이용시작</button>
									<?php endif; ?>
										<?php echo $data_buy_info['EXR_S_DATE']?>
									</span></td>
									<td><span id="<?php echo "exr_e_date_".$data_buy_info['BUY_EVENT_SNO']?>"><?php echo $data_buy_info['EXR_E_DATE']?></span><?php echo disp_add_cnt($data_buy_info['ADD_SRVC_EXR_DAY'])?></td>
									
									<!-- ############### 수업 영역 ################# -->
									<?php if($data_buy_info['CLAS_DV'] == "21" || $data_buy_info['CLAS_DV'] == "22") :?>
									<?php
									   $sum_clas_cnt = $data_buy_info['MEM_REGUL_CLAS_LEFT_CNT'] + $data_buy_info['SRVC_CLAS_LEFT_CNT']; // 총 수업 남은 횟수
									?>
									<td class='text-center'><?php echo $sum_clas_cnt?></td>
									
									<?php else :?>
									<td class='text-center'>-</td>
									<?php endif ;?>
									<!-- ############### 수업 영역 ################# -->
									
									<!-- ############### 휴회 영역 ################# -->
									<?php if($data_buy_info['DOMCY_POSS_EVENT_YN'] == "Y") :?>
									<td class='text-center'><?php echo $data_buy_info['LEFT_DOMCY_POSS_DAY'] ?></td>
									<td class='text-center'><?php echo $data_buy_info['LEFT_DOMCY_POSS_CNT'] ?></td>
									<?php else :?>
									<td class='text-center'>-</td>
									<td class='text-center'>-</td>
									<?php endif ;?>
									<!-- ############### 휴회 영역 ################# -->
									
									<td style="text-align:right"><?php echo number_format($data_buy_info['REAL_SELL_AMT']) ?></td>
									<td style="text-align:right"><?php echo number_format($data_buy_info['BUY_AMT']) ?></td>
									<td style="text-align:right">
									<?php if ($data_buy_info['RECVB_AMT'] == 0) :?>
										<?php echo number_format($data_buy_info['RECVB_AMT']) ?>
									<?php else :?>
										<button type='button' class='btn btn-danger btn-xs' onclick="misu_select('<?php echo $data_buy_info['MEM_SNO']?>','<?php echo $data_buy_info['BUY_EVENT_SNO']?>');">
										<?php echo number_format($data_buy_info['RECVB_AMT']) ?>
										</button>
									<?php endif ;?>
									</td>
									
									<td class='text-center'><?php echo $data_buy_info['STCHR_NM'] ?></td>
									<td class='text-center'><?php echo $data_buy_info['PTCHR_NM'] ?></td>
								</tr>
							</tbody>
						</table>
						<!-- TABLE [END] -->
					</div>
					<!-- CARD BODY [END] -->
			
				</div>
			
			</div>
		</div>
		
		<div class="row">
			<!-- [양도자 정보] -->
			<div class="col-md-3">
				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h3 class="panel-title">양도자 정보</h3>
					</div>
					<!-- CARD HEADER [END] -->
					<!-- CARD BODY [START] -->
					<div class="panel-body">
						<!-- TABLE [START] -->
						<table class="table table-bordered col-md-12">
							<tr>
								<td>회원상태</td>
								<td><?php echo $sDef['MEM_STAT'][$mem_info['MEM_STAT']]?></td>
							</tr>
							<tr>
								<td>출입조건</td>
								<td>
									<?php echo $sDef['ACC_RTRCT_MTHD'][$data_buy_info['ACC_RTRCT_MTHD']]?>
								</td>
							<tr>
								<td>이름</td>
								<td>
    								<?php ($mem_info['MEM_GENDR'] == "M") ? $disp_gendr="<font color='blue'><i class='fa fa-mars'></i></font>" : $disp_gendr="<font color='red'><i class='fa fa-venus'></i></font>"; ?> 
    								<?php echo $disp_gendr ?>
    								<?php echo $mem_info['MEM_NM'] ?>
								</td>
							</tr>
							<tr>
								<td>아이디</td>
								<td><?php echo $mem_info['MEM_ID']?></td>
							</tr>
							<tr>
								<td>전화번호</td>
								<td><?php echo disp_phone($mem_info['MEM_TELNO'])?></td>
							</tr>
							<tr>
								<td>생년월일</td>
								<td><?php echo disp_birth($mem_info['BTHDAY'])?></td>
							</tr>
							<tr>
								<td>등록일</td>
								<td><?php echo $mem_info['REG_DATETM']?></td>
							</tr>
						</table>
						
					</div>
				</div>
			</div>
			<!-- [양도자 정보] -->
			
			<!-- [양수자 정보] -->
			<div class="col-md-3">
				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h3 class="panel-title">양수자 정보</h3>
					</div>
					<!-- CARD HEADER [END] -->
					<!-- CARD BODY [START] -->
					<div class="panel-body">
						<!-- TABLE [START] -->
						<table class="table table-bordered col-md-12">
							<tr>
								<td>회원상태</td>
								<td><?php echo $sDef['MEM_STAT'][$tmem_info['MEM_STAT']]?></td>
							</tr>
							<tr>
								<td>출입조건</td>
								<td>
									<?php echo $sDef['ACC_RTRCT_MTHD'][$tmem_info['acc_mthd']]?>								
								</td>
							<tr>
							<tr>
								<td>이름</td>
								<td>
    								<?php ($tmem_info['MEM_GENDR'] == "M") ? $disp_gendr="<font color='blue'><i class='fa fa-mars'></i></font>" : $disp_gendr="<font color='red'><i class='fa fa-venus'></i></font>"; ?> 
    								<?php echo $disp_gendr ?>
    								<?php echo $tmem_info['MEM_NM'] ?>
								</td>
							</tr>
							<tr>
								<td>아이디</td>
								<td><?php echo $tmem_info['MEM_ID']?></td>
							</tr>
							<tr>
								<td>전화번호</td>
								<td><?php echo disp_phone($tmem_info['MEM_TELNO'])?></td>
							</tr>
							<tr>
								<td>생년월일</td>
								<td><?php echo disp_birth($tmem_info['BTHDAY'])?></td>
							</tr>
							<tr>
								<td>등록일</td>
								<td><?php echo $tmem_info['REG_DATETM']?></td>
							</tr>
						</table>
						
					</div>
				</div>
			</div>
			<!-- [양수자 정보] -->
			
			<!-- [양도할 상품 정보] -->
			<div class="col-md-3">
				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h3 class="panel-title">양도할 상품 정보</h3>
					</div>
					<!-- CARD HEADER [END] -->
					<!-- CARD BODY [START] -->
					<?php if ($data_buy_info['CLAS_DV'] == "21" || $data_buy_info['CLAS_DV'] == "22") :?>
					<div class="panel-body">
						<!-- TABLE [START] -->
						<table class="table table-bordered col-md-12">
							<tr>
								<td>결제금액</td>
								<td><?php echo number_format($trans_buy_info['buy_amt'])?></td>
							</tr>
							<tr>
								<td>이용금액</td>
								<td><?php echo number_format($trans_buy_info['use_amt'])?></td>
							</tr>
							<tr class='bg-info'>
								<td>양도할 금액</td>
								<td><?php echo number_format($trans_buy_info['refund_amt'])?></td>
							</tr>
							<tr>
								<td>1회 수업금액</td>
								<td><?php echo number_format($trans_buy_info['1tm_clas_prgs_amt'])?></td>
							</tr>
							<tr>
								<td>계약 수업수</td>
								<td><?php echo number_format($trans_buy_info['clas_cnt'])?></td>
							</tr>
							<tr>
								<td>사용 수업수</td>
								<td><?php echo number_format($trans_buy_info['mem_regul_clas_prgs_cnt'])?></td>
							</tr>
							<tr class='bg-info'>
								<td>양도할 남은 수업수</td>
								<td><?php echo number_format($trans_buy_info['mem_regul_clas_left_cnt'])?></td>
							</tr>
						</table>
					</div>
					<?php else :?>
					<div class="panel-body">
						<!-- TABLE [START] -->
						<table class="table table-bordered col-md-12">
							<tr>
								<td>결제금액</td>
								<td><?php echo number_format($trans_buy_info['buy_amt'])?></td>
							</tr>
							<tr>
								<td>이용금액</td>
								<td><?php echo number_format($trans_buy_info['use_amt'])?></td>
							</tr>
							<tr class='bg-info'>
								<td>양도할 금액</td>
								<td><?php echo number_format($trans_buy_info['refund_amt'])?></td>
							</tr>
							<tr>
								<td>1일 수업금액</td>
								<td><?php echo number_format(floor($trans_buy_info['1tm_exr_amt']))?></td>
							</tr>
							<tr>
								<td>계약일수</td>
								<td><?php echo number_format($trans_buy_info['total_exr_day_cnt'])?></td>
							</tr>
							<tr>
								<td>사용한 일수</td>
								<td><?php echo number_format($trans_buy_info['use_day_cnt'])?></td>
							</tr>
							<tr class='bg-info'>
								<td>양도할 남은 이용일</td>
								<td>
								<?php 
								if ($trans_buy_info['ori_left_day_cnt'] == $trans_buy_info['left_day_cnt']) :
								    echo number_format($trans_buy_info['ori_left_day_cnt']);
								else :
								    echo number_format($trans_buy_info['left_day_cnt']) . "(" . number_format($trans_buy_info['ori_left_day_cnt']) . ")";
								endif;
								?>
								</td>
							</tr>
						</table>
					</div>
					<?php endif ;?>
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h3 class="panel-title">양도비 결제 정보</h3>
					</div>
					<!-- CARD HEADER [END] -->
					<!-- CARD BODY [START] -->
					<div class="panel-body">
					
						<div class="col_md-12">
            				<div class="alert alert-info alert-dismissible" >
                                <button type="button" class="close"  data-bs-dismiss="alert" aria-hidden="true">&times;</button>
                                <small>
                                운동시작일은 수업상품일 경우에는 상관없이 예약 상품으로 결정됩니다.
                                락커의 양도일 경우에는 락커을 이용하실 락커을 양수자가 다시 선택하셔야 합니다.
                                </small>
                            </div>
            			</div>
					
					
    					<!-- [운동시작일] [START] -->
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text" >운동시작일</span>
                        	</span>
                        	<input type="text" class="datepp" style='width:90px; margin-left:5px' placeholder="" name="exr_s_date" id="exr_s_date" value="<?php echo date('Y-m-d')?>" />
                        </div>
                        <!-- [운동시작일] [END] -->
        				<!-- [현금결제] [START] -->
        				<div>&nbsp;</div>
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text" >현금결제</span>
                        	</span>
                        	<input type="text" class="" style='width:90px; margin-left:5px' placeholder="" name="cash_amt" id="cash_amt"  value="" />
                        </div>
                        <!-- [현금결제] [END] -->
                		<!-- [카드결제] [START] -->
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text">카드결제</span>
                        	</span>
                        	<input type="text" class="" style='width:90px; margin-left:5px' placeholder="" name="card_amt" id="card_amt"  value="" />
                        	<!-- 
                        	<span class="input-group-append">
                            	<button type="button" class="btn btn-info btn-flat" id="btn_pay_conn">결제</button>
                            </span>
                             -->
                            <span class="input-group-append" style="display:none">
                            	<button type="button" class="btn btn-danger btn-flat" style='margin-left:5px' id="btn_pay_direct">수동결제</button>
                            </span>
                        	<input type="text" class="" style='width:90px; margin-left:5px' placeholder="승인번호" name="card_appno" id="card_appno"  value="" />
                        	
                        </div>
                        <!-- [카드결제] [END] -->
                        <!-- [계좌이체] [START] --
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text" style='width:100px'>계좌이체</span>
                        	</span>
                        	<input type="text" class="" style='width:100px; margin-left:5px' placeholder="" name="acct_amt" id="acct_amt"  value="" />
                        	<select class="select2 form-control" style="width: 260px;" name="acct_no" id="acct_no">
                        		<option>계좌 선택</option>
                        		<option>국민 : 12321-13547-125-25</option>
                        		<option>하나 : 8514-254-45387-11</option>
                			</select>
                        </div>
                        <!-- [계좌이체] [END] -->
                        
                        <!-- [현금결제] [미수금] --
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text" style='width:100px'>미수금</span>
                        	</span>
                        	<input type="text" class="" style='width:100px; margin-left:5px' placeholder="" name="misu_amt" id="misu_amt"  value="" />
                        </div>
                        <!-- [현금결제] [미수금] -->
					</div>
					
					<!-- CARD FOOTER [START] -->
					<div class="card-footer clearfix">
						<!-- BUTTON [START] -->
                        <ul class="pagination pagination-sm m-0 float-left">
                        </ul>
                        <!-- BUTTON [END] -->
						<!-- BUTTON [START] -->
						<ul class="pagination pagination-sm m-0 float-right">
							<li class="ac-btn"><button type="button" class="btn btn-block btn-success btn-sm" id='btn_trans_confirm'>양도완료</button></li>
						</ul>
						
						<!-- BUTTON [END] -->
						
					</div>
					<!-- CARD FOOTER [END] -->
					
					
				</div>
			</div>
		</div>
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->

	
<!-- ############################## MODAL [ END ] ###################################### -->
	
<form name="form_trans_submit" id="form_trans_submit" method="post" action="/ttotalmain/event_trans_proc">
    <input type='hidden' name='mem_sno' id='mem_sno' value="<?php echo $mem_info['MEM_SNO']?>" />
    <input type='hidden' name='tmem_sno' id='tmem_sno' value="<?php echo $tmem_info['MEM_SNO']?>" />
    <input type='hidden' name='buy_event_sno' id='buy_event_sno' value="<?php echo $data_buy_info['BUY_EVENT_SNO']?>" />

    <input type="hidden" name="van_appno" id="van_appno" />
    <input type="hidden" name="van_appno_sno" id="van_appno_sno" />
    
    <input type="hidden" name="pay_exr_s_date" id="pay_exr_s_date" />
    <input type="hidden" name="pay_card_amt" id="pay_card_amt" />
    <input type="hidden" name="pay_cash_amt" id="pay_cash_amt" />
</form>	
	
</section>


<?=$jsinc ?>

<script>
	
	$(function () {
		$('.select2').select2();
	})

	// 카드결제금액 입력
	$('#card_amt').keyup(function(){
		var d_amt = onlyNum( $('#card_amt').val() );
		$('#card_amt').val(currencyNum(d_amt));
	});

	// 현금결제금액 입력
	$('#cash_amt').keyup(function(){
		var d_amt = onlyNum( $('#cash_amt').val() );
		$('#cash_amt').val(currencyNum(d_amt));
	});

	$("#btn_trans_confirm").click(function(){
		$('#pay_exr_s_date').val( $('#exr_s_date').val() );
		$('#pay_card_amt').val( $('#card_amt').val() );
		$('#pay_cash_amt').val( $('#cash_amt').val() );
		
		$('#form_trans_submit').submit();
	});

	// 수동결제 버튼 클릭
	$('#btn_pay_direct').click(function(){

		var mem_id = $('#mem_id').val();
		var sell_event_sno = $('#sell_event_sno').val();
		var card_amt = $('#card_amt').val();
		var card_appno = $('#card_appno').val();
		
		if (card_amt == '' || card_amt == 0)
		{
			alertToast('error','카드결제 금액을 입력하세요');
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
					
					$('#btn_pay_conn').attr("disabled",true);
					$('#btn_pay_direct').attr("disabled",true);
					$('#card_amt').attr("readonly",true);
					
					alertToast('success','카드 수동결제가 완료 되었습니다.');
					
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