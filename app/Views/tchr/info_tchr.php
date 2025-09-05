<style>
	/* 부모 div를 오른쪽 정렬 */
	.row {
		display: flex;
		flex-wrap: wrap;
	}

	/* 테이블이 포함된 컬럼 스타일 */
	.col-md-2 {
		width: auto !important; /* 자동 너비 조절 */
		min-width: 200px; /* 최소 너비 설정 (필요에 따라 조정) */
	}

	/* 테이블 스타일 */
	.table {
		/* width: auto !important;  */
		/* 자동 너비 조절 */
		min-width: 150px; /* 최소 너비 */
		table-layout: auto; /* 열 크기 자동 조정 */
	}

	/* 헤더 셀 스타일 */
	.table th {
		white-space: nowrap; /* 글자가 줄 바꿈되지 않도록 설정 */
		text-align: center; /* 헤더 텍스트 가운데 정렬 */
	}

	/* 테이블 본문 스타일 */
	.table td {
		white-space: nowrap; /* 줄 바꿈 방지 */
		text-align: center; /* 텍스트 가운데 정렬 */
	}

</style>
<?php
$sDef = SpoqDef();
?>

<h1 class="page-header"><?php echo $title ?></h1>
<div class="row">
	<div class="col-xl-3 col-md-6">
		<div class="widget widget-stats bg-blue">
			<div class="stats-icon"><i class="fa fa-users"></i></div>
			<div class="stats-info">
				<h4>오늘 가입회원</h4>
				<p><?php echo $get_mem_count['JON']?></p>
			</div>
			<div class="stats-link">
				<a href="<?php echo "/tmemmain/mem_manage?sdcon=jon&sdate=" . date('Y-m-d') . "&edate=" . date('Y-m-d')?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-md-6">
		<div class="widget widget-stats bg-info">
			<div class="stats-icon"><i class="fa fa-users"></i></div>
			<div class="stats-info">
				<h4>오늘 등록회원</h4>
				<p><?php echo $get_mem_count['REG']?></p>
			</div>
			<div class="stats-link">
				<a href="<?php echo "/tmemmain/mem_manage?sdcon=jon&sdate=" . date('Y-m-d') . "&edate=" . date('Y-m-d')?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-md-6">
		<div class="widget widget-stats bg-success">
			<div class="stats-icon"><i class="fa fa-users"></i></div>
			<div class="stats-info">
				<h4>오늘 재등록회원</h4>
				<p><?php echo $get_mem_count['RE_REG']?></p>
			</div>
			<div class="stats-link">
			<a href="<?php echo "/tmemmain/mem_manage?sdcon=rereg&sdate=" . date('Y-m-d') . "&edate=" . date('Y-m-d')?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-md-6">
		<div class="widget widget-stats bg-danger">
			<div class="stats-icon"><i class="fa fa-users"></i></div>
			<div class="stats-info">
				<h4>오늘 종료회원</h4>
				<p><?php echo $get_mem_count['END']?></p>
			</div>
			<div class="stats-link">
				<a href="<?php echo "/tmemmain/mem_manage?sdcon=end&sdate=" . date('Y-m-d') . "&edate=" . date('Y-m-d')?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
			</div>
		</div>
	</div>
</div>
<!-- Main content -->
<div class="row">
	<div class="col-md-4 ui-sortable">
		<div class="panel panel-inverse">
			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title">회원상태별</h4>
			</div>
			<div class="panel-body p-0">
				<div class="todolist">
					<?php
					$total_4 = 0;
					foreach ($all_sales_total_4 as $r) :
					$total_4 += $r['sum_cost'];
					?>
					<div class="todolist-item">
						<label class="todolist-label" >[<?php echo $sDef['SALES_MEM_STAT'][$r['SALES_MEM_STAT']]?>] <?php echo number_format($r['sum_cost'])?></label>
					</div>
					<?php endforeach;?>
					<div class="todolist-item">
						<label class="todolist-label"  style="font-weight:bold">[합계]&nbsp;<?php echo number_format($total_4)?></label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 ui-sortable">
		<div class="panel panel-inverse">
			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title">매출구분별</h4>
			</div>
			<div class="panel-body p-0">
				<table class="table table-panel align-middle mb-0">
					<thead>
						<tr>	
							<th>매출구분</th>
							<th>금액</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$total_3 = 0;
						foreach ($all_sales_total_3 as $r) :
						$total_3 += $r['sum_cost'];
						?>
							<tr>
								<td nowrap=""><?php echo $sDef['SALES_DV'][$r['SALES_DV']]?></td>
								<td><?php echo number_format($r['sum_cost'])?><!-- <span class="text-success"><i class="fa fa-arrow-up"></i></span> --></td>
							</tr>
						<?php endforeach;?>
						<tr>
							<td nowrap=""><label style="font-weight:bold">합계</label></td>
							<td style="font-weight:bold"><?php echo number_format($total_3)?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-4 ui-sortable">
		<div class="panel panel-inverse">
			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title">매출사유별</h4>
			</div>
			<div class="panel-body p-0">
				<table class="table table-panel align-middle mb-0">
					<thead>
						<tr class='text-center'>
							<th>매출사유</th>
							<th>금액</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$total_2 = 0;
						foreach ($all_sales_total_2 as $r) :
						$total_2 += $r['sum_cost'];
						?>
							<tr>
								<td nowrap=""><?php echo $sDef['SALES_DV_RSON'][$r['SALES_DV_RSON']]?></td>
								<td><?php echo number_format($r['sum_cost'])?></td>
							</tr>
						<?php endforeach;?>
						<tr>
							<td nowrap=""><label style="font-weight:bold">합계</label></td>
							<td style="font-weight:bold"><?php echo number_format($total_2)?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-4 ui-sortable">
		<div class="panel panel-inverse">
			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title">매출구분별 상세</h4>
			</div>
			<div class="panel-body p-0">
			<table class="table table-panel align-middle mb-0">
					<thead>
						<tr class='text-center'>
							<th>매출구분</th>
							<th>매출사유</th>
							<th>금액</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$total_1 = 0;
					foreach ($all_sales_total_1 as $r) :
					$total_1 += $r['sum_cost'];
					?>
						<tr>
							<td nowrap=""><?php echo $sDef['SALES_DV'][$r['SALES_DV']]?></td>
							<td nowrap=""><?php echo $sDef['SALES_DV_RSON'][$r['SALES_DV_RSON']]?></td>
							<td nowrap=""><?php echo number_format($r['sum_cost'])?></td>
						</tr>
					<?php endforeach;?>
						<tr>
							<td nowrap="" colspan="2"><label style="font-weight:bold">합계</label></td>
							<td class='text-center' style="font-weight:bold"><?php echo number_format($total_1)?></td>
						</tr>
					</tbody>
				</table>

			</div>
		</div>
	</div>
	<div class="col-md-8 ui-sortable">
		<div class="panel panel-inverse">
			<div class="panel-heading ui-sortable-handle">
				<h4 class="panel-title">결제채널별</h4>
			</div>
			<div class="panel-body p-0">
			<table class="table table-panel align-middle mb-0">
					<thead>
						<tr class='text-center'>
							<th nowrap="">결제채널</th>
							<th nowrap="">결제방법</th>
							<th nowrap="">매출구분</th>
							<th nowrap="">매출사유</th>
							<th nowrap="">금액</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$total_0 = 0;
					foreach ($all_sales_total as $r) :
					$total_0 += $r['sum_cost'];
					?>
						<tr>
							<td><?php echo $sDef['PAYMT_CHNL'][$r['PAYMT_CHNL']]?></td>
							<td><?php echo $sDef['PAYMT_MTHD'][$r['PAYMT_MTHD']]?></td>
							<td><?php echo $sDef['SALES_DV'][$r['SALES_DV']]?></td>
							<td><?php echo $sDef['SALES_DV_RSON'][$r['SALES_DV_RSON']]?></td>
							<td><?php echo number_format($r['sum_cost'])?></td>
						</tr>
					<?php endforeach;?>
						<tr>
							<td nowrap="" colspan="4"><label style="font-weight:bold">합계</label></td>
							<td class='text-center'  style="font-weight:bold"><?php echo number_format($total_0)?></td>
						</tr>
					</tbody>
				</table>

			</div>
		</div>
	</div>
</div>


					

	
	
<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

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