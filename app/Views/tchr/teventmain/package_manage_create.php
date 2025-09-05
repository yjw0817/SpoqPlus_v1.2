<style>	
	.custom-select-width {
		flex: 1; /* 부모 공간을 균등 분배 */
		max-width: 150px; /* 최대 길이 제한 */
	}
	.event-group-select-width {
		flex-grow: 1; /* 남은 공간을 최대한 채움 */
		width: 100%; /* 부모 컨테이너 너비에 맞춤 */
		max-width: none; /* 최대 너비 제한 제거 */
	}
	.event-group-type-select-width {
		max-width:100px; /* 부모 컨테이너 너비에 맞춤 */
	}
	.event-type-width{
		flex: 1; /* 부모 공간을 균등 분배 */
		max-width: 150px; /* 최대 길이 제한 */
	}
	.sell-price-width{
		flex: 1; /* 부모 공간을 균등 분배 */
		max-width: 120px; /* 최대 길이 제한 */
	}
	.sell-price-width input{
		padding-right: 25px; /* 단위 공간 확보 */
		text-align: right; /* 텍스트 우측 정렬 (선택 사항) */
	}
	.unit-label2 {
		position: absolute;
		right: 10px; /* input 오른쪽 안쪽 여백 */
		top: 50%;
		transform: translateY(-50%); /* 수직 가운데 정렬 */
		pointer-events: none; /* 클릭 방지 */
		color: #6c757d; /* 텍스트 색상 (Bootstrap text-body와 유사) */
	}
	.use-prod-width {
		flex: 1; /* 부모 공간을 균등 분배 */
		max-width: 80px;
		display: flex;
		align-items: center;
	}
	.lock-range {
		flex: 1; /* 부모 공간을 균등 분배 */
		max-width: 150px;
		display: flex;
		align-items: center;
	}
	.use-prod-width input{
		padding-right: 20px; /* "분" 공간 확보 */
		text-align: right; /* 텍스트 우측 정렬 (선택 사항) */
	}
	.prev-time-width {
		/* max-width: 80px;  삭제 */
		display: flex;
		align-items: center;
		position: relative;
		/* 줄바꿈 방지 */
		flex-wrap: nowrap; /* 또는 flex-nowrap */
	}

	.prev-time-width input {
		width: 80px; /* 필요 시 직접 지정 */
		min-width: 80px;
		padding-right: 20px;
		text-align: right;
	}
	.unit-label3 {
		position: absolute;
		right: 50px; /* input 오른쪽 안쪽 여백 */
		top: 50%;
		transform: translateY(-50%); /* 수직 가운데 정렬 */
		pointer-events: none; /* 클릭 방지 */
		color: #6c757d; /* 텍스트 색상 (Bootstrap text-body와 유사) */
	}
	.lesson-time-width{
		flex: 1; /* 부모 공간을 균등 분배 */
		max-width: 80px;
		display: flex;
		align-items: center;
		position: relative; /* "분"을 input 안쪽에 배치하기 위해 */
	} 
	.lesson-time-width input {
		padding-right: 20px; /* "분" 공간 확보 */
		text-align: right; /* 텍스트 우측 정렬 (선택 사항) */
	}
	.unit-label {
		position: absolute;
		right: 10px; /* input 오른쪽 안쪽 여백 */
		top: 50%;
		transform: translateY(-50%); /* 수직 가운데 정렬 */
		pointer-events: none; /* 클릭 방지 */
		color: #6c757d; /* 텍스트 색상 (Bootstrap text-body와 유사) */
	}
	.event-group-input {
		max-width: 400px; 
	}
	.event-input {
		max-width: 500px; 
	}

	.bootstrap-timepicker{
		max-width: 120px; 
	}

	.use_prod-input{
		max-width: 120px; 
	}
	.use-prod-type-width{
		max-width: 80px; 
	}
	.locker-number-type-select-width{
		max-width: 80px; 
	}
	.price-width{
		max-width: 125px; 
	}
	.use-per-day-select-width{
		max-width: 125px; 
	}
	.sell-date-daterange-width {
		width: 210px; /* 고정 너비로 변경 (원하는 값으로 조정) */
		max-width: 100%; /* 부모 너비 초과 방지 */
		flex-shrink: 0; /* flex 컨테이너에서 축소 방지 */
		display: flex; /* 내부 요소 정렬을 위해 */
		align-items: center;
		position: relative;
	}

	/* 사이드바 높이 전체로 설정 */
	.app-sidebar {
		height: 100vh; /* 뷰포트 높이 100% */
		overflow-y: auto; /* 세로 스크롤 활성화 */
	}

	/* 내비게이션 영역 높이 조정 */
	.navbar-sticky {
		height: 100%; /* 부모 요소 높이에 맞춤 */
	}

	/* 콘텐츠와 사이드바 간 레이아웃 보정 */
	.app-content {
		min-height: 100vh; /* 콘텐츠 영역도 전체 높이 유지 */
	}

	/* 스크롤바 스타일링 (선택 사항) */
	.app-sidebar::-webkit-scrollbar {
		width: 8px;
	}
	.app-sidebar::-webkit-scrollbar-thumb {
		background: #888; /* 스크롤바 색상 */
		border-radius: 4px;
	}
	.app-sidebar::-webkit-scrollbar-track {
		background: #f1f1f1; /* 스크롤바 트랙 색상 */
	}
	/* 추가 스타일: .col-md-9와 #m_cate_all 조정 */
	.col-md-9.d-flex {
		width: 100%; /* 부모 .row 너비를 최대한 활용 */
	}
	.m_cate_all {
		flex-grow: 1; /* 남은 공간을 채움 */
		display: flex;
		align-items: center;
	}
	
	.inline-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }
	
	.grp .accordion-button {
		background-color: #007aff !important;
		color: rgba(255,255,255,.85) !important;
		display: block !important;
		text-align: left !important;
		padding: 0.9rem !important; /* 패딩 줄임 */
	}

	.grp .accordion-button.collapsed {
		background-color: #007aff !important;
		color: rgba(255,255,255,.85) !important;
	}

	.grp .accordion-button:hover {
		background-color: #007aff !important;
		color: rgba(255,255,255,.85) !important;
	}

	.prv .accordion-button {
		background-color: #b502ff !important;
		color: rgba(255,255,255,.85) !important;
		display: block !important;
		text-align: left !important;
		padding: 0.9rem !important; /* 패딩 유지 */
	}

	.prv .accordion-button.collapsed {
		background-color: #b502ff !important;
		color: rgba(255,255,255,.85) !important;
	}

	.prv .accordion-button:hover {
		background-color: #b502ff !important;
		color: rgba(255,255,255,.85) !important;
	}

	.mbs .accordion-button {
		background-color: #ff9501 !important;
		color: rgba(255,255,255,.85) !important;
		display: block !important;
		text-align: left !important;
		padding: 0.9rem !important; /* 패딩 줄임 */
	}

	.mbs .accordion-button.collapsed {
		background-color: #ff9501 !important;
		color: rgba(255,255,255,.85) !important;
	}

	.mbs .accordion-button:hover {
		background-color: #ff9501 !important;
		color: rgba(255,255,255,.85) !important;
	}

	.opt .accordion-button {
		background-color: #5ac8fb !important;
		color: rgba(255,255,255,.85) !important;
		display: block !important;
		text-align: left !important;
		padding: 0.9rem !important; /* 패딩 줄임 */
	}

	.opt .accordion-button.collapsed {
		background-color: #5ac8fb !important;
		color: rgba(255,255,255,.85) !important;
	}

	.opt .accordion-button:hover {
		background-color: #5ac8fb !important;
		color: rgba(255,255,255,.85) !important;
	}

	.accordion-body {
		background-color: #ffffff !important;
	}

	.accordion-button h5 {
		margin: 0 !important; /* 마진 제거 */
		display: block !important; /* 블록 요소 강제 */
	}

	.accordion-button p {
		margin: 0 !important;
		display: block !important; /* 블록 요소 강제 */
		margin-top: -4px !important; /* 음수 마진으로 더 붙임 */
	}

	.accordion-item {
		border-top: 1px solid rgba(34, 34, 34, 0.125) !important;  /* 모든 아이템에 상단 테두리 추가 */
	}


	
	.accordion-header {
		position: relative; /* 부모 요소를 기준점으로 설정 */
	}

	.accordion-close {
		position: absolute; /* 절대 위치 */
		top: 15px; 
		right: 10px;       /* 오른쪽에서 10px */
		transform: scale(0.6); /* 버튼을 80% 크기로 축소 */
		z-index: 1000;
		display: block !important; /* 버튼이 항상 보이도록 강제 설정 */
	}
	.price-close{
		vertical-align: middle !important;
	}

	.table-custom {
        border-collapse: separate;
        border-spacing: 0 0; /* 가로와 세로 간격 모두 0 */
    }
    .table-custom td {
        border-top: 0; /* 기본 상단 테두리 제거 */
    }
    #price_body tr:last-child td {
        border-top: 1px solid black !important;
    }
</style>
	
</style>
<?php
	$sDef = SpoqDef();
?>
<link href="/dist/css/vendor.min.css" rel="stylesheet">
<link href="/dist/css/apple/app.min2.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/dist/css/icheck.css">
<script type="text/javascript" src="/dist/js/icheck.min.js"></script>
<!-- <script type="text/javascript" src="/dist/plugins/ionicons/ionicons.js"></script> -->
<!-- <link href="/dist/plugins/ionicons/css/ionicons.min.css" rel="stylesheet"> -->
<script type="text/javascript" src="/dist/js/sidebar-scrollspy.demo.js"></script>
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" type="text/javascript"></script>
<link href="/dist/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" />
<link href="/dist/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
<script src="/dist/plugins/moment/min/moment.min.js"></script>
<script src="/dist/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

	<!-- ================== END page-css ================== -->

<script src="/dist/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<!-- Main content -->
<div id="content">
	<!-- BEGIN breadcrumb -->
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">상품(종목) 구매관리</a></li>
		<li class="breadcrumb-item"><a href="javascript:;">판매이용권 등록관리</a></li>
		<li class="breadcrumb-item active">팩키지이용권 만들기</li>
	</ol>
	<!-- END breadcrumb -->
	<!-- BEGIN page-header -->
	<h1 class="page-header">팩키지 이용권 만들기 <small>팩치지 상품 그룹 선택후 판매 상품을 추가합니다.</small></h1>
	<!-- END page-header -->
	<hr class="mb-4">
	<!-- BEGIN row -->
	
	<div class="row">
		<!-- BEGIN col-3 -->
		<div style="width: 230px">
			<nav class="navbar navbar-sticky d-none d-xl-block my-n4 py-4 h-200 text-end">
				<nav class="nav" id="bsSpyTarget">
					<a class="nav-link active" href="#select_package" data-toggle="scroll-to">팩키지 만들기</a>
				</nav>
			</nav>
		</div>
		<!-- END col-3 -->
		<!-- BEGIN col-9 -->
		<div class="col-xl-8" id="bsSpyContent">
			<div id="select_package" class="mb-4 pb-3">
				<h4 class="d-flex align-items-center mb-2">
				<iconify-icon class="fs-24px me-2 text-body text-opacity-75 my-n1" icon="solar:winrar-bold-duotone"></iconify-icon>팩키지 이용권 신규/선택
				</h4>
				<p>팩키지 이용권는 하단의 이용권 상품을 1개 이상 추가하여 구성합니다.</p>
				<div class="card">
					<div class="list-group list-group-flush fw-bold">
						<div class="list-group-item align-items-center first-pkg-group">
							<div class="flex-fill">
								<div style="display: flex; align-items: center; ">
									팩키지 이용권 그룹 신규/기존선택<span class="text-danger me-2">*</span>
									<select class="form-select event-group-type-select-width  form-select-sm" id="pkg_pkg_prev_event_group_status" name="pkg_pkg_prev_event_group_status">
										<option value="0">전체</option>
										<option value="Y" selected>정상판매</option>
										<option value="N">판매중지</option>
									</select>
								</div>
								<div class="text-body text-opacity-60">
									<div class="row">
										<div class="col-md-9 pt-2 d-flex align-items-center gap-3">
											<div class="text-body text-opacity-60 align-items-center gap-2 m_cate_all" id="pkg_pkg_m_cate_all" >
												<select class="form-select event-group-select-width" id="pkg_group_event_select" name="pkg_group_event_select">
													<option value="">신규생성</option>
													<?php foreach ($event_list as $r) : ?>
														<?php if ($r["M_CATE"] == "PKG") : ?>
															<option value="<?php echo $r["SELL_EVENT_SNO"]; ?>"
																	data-acc-rtrct-dv="<?php echo $r["ACC_RTRCT_DV"]; ?>"
																	data-event-type="<?php echo $r["EVENT_TYPE"]; ?>"
																	data-clas-min="<?php echo $r["CLAS_MIN"]; ?>"
																	data-m-cate="<?php echo $r["M_CATE"]; ?>"
																	data-event-desc="<?php echo $r["EVENT_DESC"]; ?>"
																	data-sell-event-nm="<?php echo $r["SELL_EVENT_NM"]; ?>"
																	data-ref-count="<?php echo $r["ref_count"]; ?>"
																	data-p-ref-count="<?php echo $r["p_ref_count"]; ?>"
																	data-lockr-set="<?php echo $r["LOCKR_SET"]; ?>"
																	data-pre-enter-min="<?php echo $r["PRE_ENTER_MIN"]; ?>"
																	data-event-ref-sno="<?php echo $r["EVENT_REF_SNO"]; ?>"
																	data-sell-event-sno="<?php echo $r["SELL_EVENT_SNO"]; ?>"
																	data-domcy-poss-event-yn="<?php echo $r["DOMCY_POSS_EVENT_YN"]; ?>"
																	data-lockr-knd="<?php echo $r["LOCKR_KND"]; ?>"
																	data-sell-yn="<?php echo $r["SELL_YN"]; ?>">
																	<?php echo $r["SELL_EVENT_NM"] ;	?>
																	
																	<?php
																		if ($r["SELL_YN"] !== 'Y') {
																			echo "(판매중지)";
																		} 
																	?>

															</option>
														<?php endif; ?>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="list-group-item align-items-center list-pkg-group" id="pkgEventGroupNameSection">
							<div class="flex-fill">
								<div>팩키지 이용권 그룹명<span class="text-danger">*</span></div>
								<div class="text-body text-opacity-60 d-flex align-items-center">
									<input class="form-control event-group-input me-2" type="text" id="pkg_group_event_name" style="flex: 1;" />
									<div class="form-check form-switch mb-0" id="pkg_groupSellYnSection" style="display:none">
										<input class="form-check-input" type="checkbox" id="pkg_groupSellYn" checked="" data-gtm-form-interact-field-id="0">
										<label class="form-check-label" for="pkg_groupSellYn" >(정상판매)</label>
									</div>
								</div>
							</div>
						</div>
						<div class="list-group-item align-items-center list-pkg-group" id="pkg_pkg_prev_event_section">
							<div class="flex-fill">
								<div>팩키지 이용권 신규/기존선택<span class="text-danger">*</span></div>
								<div class="text-body text-opacity-60 d-flex align-items-center gap-3">
									<select class="form-select event-group-select-width" id="pkg_sell_event_select" >
										<option value="">신규생성</option>
										<?php foreach ($event_list as $r) : ?>
											<option value="<?php echo $r["SELL_EVENT_SNO"]; ?>"
													data-acc-rtrct-dv="<?php echo $r["ACC_RTRCT_DV"]; ?>"
													data-event-type="<?php echo $r["EVENT_TYPE"]; ?>"
													data-clas-min="<?php echo $r["CLAS_MIN"]; ?>"
													data-m-cate="<?php echo $r["M_CATE"]; ?>"
													data-event-desc="<?php echo $r["EVENT_DESC"]; ?>"
													data-sell-event-nm="<?php echo $r["SELL_EVENT_NM"]; ?>"
													data-ref-count="<?php echo $r["ref_count"]; ?>"
													data-p-ref-count="<?php echo $r["p_ref_count"]; ?>"
													data-lockr-set="<?php echo $r["LOCKR_SET"]; ?>"
													data-pre-enter-min="<?php echo $r["PRE_ENTER_MIN"]; ?>"
													data-event-ref-sno="<?php echo $r["EVENT_REF_SNO"]; ?>"
													data-sell-event-sno="<?php echo $r["SELL_EVENT_SNO"]; ?>"
													data-domcy-poss-event-yn="<?php echo $r["DOMCY_POSS_EVENT_YN"]; ?>"
													data-lockr-knd="<?php echo $r["LOCKR_KND"]; ?>" 
													data-use-prod="<?php echo $r["USE_PROD"]; ?>" 
													data-use-unit="<?php echo $r["USE_UNIT"]; ?>" 
													data-clas-cnt="<?php echo $r["CLAS_CNT"]; ?>" 
													data-sell-s-date="<?php echo $r["SELL_S_DATE"]; ?>" 
													data-sell-e-date="<?php echo $r["SELL_E_DATE"]; ?>" 
													data-domcy-poss-event-yn="<?php echo $r["DOMCY_POSS_EVENT_YN"]; ?>" 
													data-domcy-cnt="<?php echo $r["DOMCY_CNT"]; ?>" 
													data-domcy-day="<?php echo $r["DOMCY_DAY"]; ?>" 
													data-use-per-day="<?php echo $r["USE_PER_DAY"]; ?>" 
													data-use-per-week="<?php echo $r["USE_PER_WEEK"]; ?>" 
													data-use-per-week-unit="<?php echo $r["USE_PER_WEEK_UNIT"]; ?>" 
													data-enter-from-time="<?php echo $r["ENTER_FROM_TIME"]; ?>" 
													data-enter-to-time="<?php echo $r["ENTER_TO_TIME"]; ?>" 
													data-sell-amt="<?php echo $r["SELL_AMT"]; ?>" 
													data-sell-yn="<?php echo $r["SELL_YN"]; ?>" 
													>
													<?php echo $r["SELL_EVENT_NM"] ;	?>
													<?php
														if ($r["SELL_YN"] !== 'Y') {
															echo "(판매중지)";
														} 
													?>
													<!-- <?php
														echo number_format($r["SELL_AMT"]) . '원'; // 천 단위로 컴마 추가
													?> -->
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>	
						<div class="list-group-item last-pkg-group d-flex align-items-center">
							<div class="flex-fill">
								<div style="display: flex; align-items: center; ">
									팩키지 이용권 (상품)명<span class="text-danger me-2">*</span>
									(판매수: <span id="sold_cnt">0</span>)
									<input type="hidden" id="input_mode">
								</div>
								<div class="text-body text-opacity-60 d-flex align-items-center gap-3">
									<input class="form-control event-input flex-grow-1" type="text" id="pkg_sell_event_name" />
									<div class="form-check form-switch mb-0" id="pkg_eventSellYnSection" style="display:none">
										<input class="form-check-input" type="checkbox" id="pkg_eventSellYn" checked="" data-gtm-form-interact-field-id="0">
										<label class="form-check-label" for="pkg_eventSellYn">(정상판매)</label>
									</div>
								</div>
							</div>
						</div>	
						<div class="list-group-item align-items-center">
							<div class="flex-fill">
								<div>판매기간</div>
								<div class="text-body text-opacity-60 d-flex align-items-center">
									<div class="sell-date-daterange-width position-relative" id="pkg-sell-date-daterange">
										<input type="text" name="pkg-sell-date-daterange" class="form-control" value="" placeholder="" />
										<div class="input-group-text" id="btn_pkg_cal"><i class="fa fa-calendar"></i></div>
									</div>
								</div>
							</div>
						</div>	
					</div>
				</div>
			</div>		
			<div id="select_group" class="mb-4 pb-3">
				<h4 class="d-flex align-items-center mb-2">
					<iconify-icon class="fs-24px me-2 text-body text-opacity-75 my-n1" icon="solar:widget-add-bold-duotone"></iconify-icon> 팩키지 이용권 구성 추가
				</h4>
				<p>신규생성 또는 기존 이용권 그룹을 불러올 수 있습니다.</p>
				<div class="card">
					<div class="list-group list-group-flush fw-bold">
						<!-- 기존 내용 유지 -->
						<div class="list-group-item d-flex align-items-center list1-pkg-event all-pkg-event" id="pkg_cate_section">
							<div class="flex-fill">
								<div>이용권 분류 선택<span class="text-danger">*</span></div>
								<div class="text-body text-opacity-60 d-flex align-items-center">
									<div class="option-list">
										<?php foreach ($sDef['M_CATE'] as $r => $v) : ?>
											<?php if ($r !== 'PKG') : ?>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="m_cate" value="<?php echo $r ?>" id="m_cate_<?php echo $r ?>">
													<label class="form-check-label" for="m_cate_<?php echo $r ?>"><?php echo $v; ?></label>
												</div>
											<?php endif; ?>
										<?php endforeach; ?>
									</div>
									<div class="locker-container ms-2" style="display: none;">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="locker_enabled" id="locker_enabled">
											<label class="form-check-label" for="locker_enabled">락커 여부</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="list-group-item align-items-center middle-pkg-event list-pkg-event" id="prev_event_group_section" style="display:none">
							<div class="flex-fill">
								<div style="display: flex; align-items: center; ">
								이용권그룹 신규/기존선택<span class="text-danger me-2">*</span>
									<select class="form-select event-group-type-select-width  form-select-sm" id="prev_event_group_status" name="prev_event_group_status">
										<option value="0">전체</option>
										<option value="Y" selected>정상판매</option>
										<option value="N">판매중지</option>
									</select>
								</div>
								<div class="text-body text-opacity-60">
									<div class="row">
										<div class="col-md-9 pt-2 d-flex align-items-center gap-3">
											<div class="text-body text-opacity-60 align-items-center gap-2 m_cate_all" id="m_cate_all" style="display:none">
												<select class="form-select event-group-select-width" id="prev_event_group" name="prev_event_group">
													<option value="">선택</option>
													<?php foreach ($event_list as $r) : ?>
														<option value="<?php echo $r["SELL_EVENT_SNO"]; ?>"
																data-acc-rtrct-dv="<?php echo $r["ACC_RTRCT_DV"]; ?>"
																data-event-type="<?php echo $r["EVENT_TYPE"]; ?>"
																data-clas-min="<?php echo $r["CLAS_MIN"]; ?>"
																data-m-cate="<?php echo $r["M_CATE"]; ?>"
																data-event-desc="<?php echo $r["EVENT_DESC"]; ?>"
																data-sell-event-nm="<?php echo $r["SELL_EVENT_NM"]; ?>"
																data-ref-count="<?php echo $r["ref_count"]; ?>"
																data-p-ref-count="<?php echo $r["p_ref_count"]; ?>"
																data-lockr-set="<?php echo $r["LOCKR_SET"]; ?>"
																data-pre-enter-min="<?php echo $r["PRE_ENTER_MIN"]; ?>"
																data-event-ref-sno="<?php echo $r["EVENT_REF_SNO"]; ?>"
																data-sell-event-sno="<?php echo $r["SELL_EVENT_SNO"]; ?>"
																data-domcy-poss-event-yn="<?php echo $r["DOMCY_POSS_EVENT_YN"]; ?>"
																data-lockr-knd="<?php echo $r["LOCKR_KND"]; ?>"
																data-sell-yn="<?php echo $r["SELL_YN"]; ?>">
																<?php echo "[ " .$r["SELL_EVENT_NM"] . " ]";	?>
																<?php
																	// $r["ACC_RTRCT_DV"]가 정의되어 있고, $sDef['ACC_RTRCT_DV2']에 해당 키가 있는지 확인
																	$accRtrctDvText = isset($r["ACC_RTRCT_DV"]) && isset($sDef['ACC_RTRCT_DV2'][$r["ACC_RTRCT_DV"]]) 
																	? $sDef['ACC_RTRCT_DV2'][$r["ACC_RTRCT_DV"]] 
																	: '알 수 없음'; // 기본값 설정

																	$mCatePropertyText = isset($r["EVENT_TYPE"]) && isset($sDef['M_CATE_PROPERTY'][$r["EVENT_TYPE"]]) 
																	? $sDef['M_CATE_PROPERTY'][$r["EVENT_TYPE"]] 
																	: '알 수 없음'; // 기본값 설정


																	// $r["ref_count"]가 숫자인지 확인하고, 0보다 큰 경우 추가 정보 포함
																	$refCount = isset($r["ref_count"]) && is_numeric($r["ref_count"]) ? (int)$r["ref_count"] : 0;

																	if ($refCount > 0) {
																		echo $mCatePropertyText . ", " . $accRtrctDvText . ", 판매중이용권수: " . $refCount;
																	} else {
																		echo $mCatePropertyText . ", " . $accRtrctDvText;
																	}
																?>
																<?php
																	if ($r["SELL_YN"] !== 'Y') {
																		echo "(판매중지)";
																	} 
																?>

														</option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="list-group-item align-items-center last1-pkg-event last-pkg-event" id="pkg_prev_event_section" style="display:none">
							<div class="flex-fill">
								<div>이용권 선택<span class="text-danger">*</span></div>
								<div class="text-body text-opacity-60 d-flex align-items-center gap-3">
									<select class="form-select event-group-select-width" id="prev_event" >
										<option value="">선택</option>
										<option value="0">신규생성</option>
										<?php foreach ($event_list as $r) : ?>
											<option value="<?php echo $r["SELL_EVENT_SNO"]; ?>"
													data-acc-rtrct-dv="<?php echo $r["ACC_RTRCT_DV"]; ?>"
													data-event-type="<?php echo $r["EVENT_TYPE"]; ?>"
													data-clas-min="<?php echo $r["CLAS_MIN"]; ?>"
													data-m-cate="<?php echo $r["M_CATE"]; ?>"
													data-event-desc="<?php echo $r["EVENT_DESC"]; ?>"
													data-sell-event-nm="<?php echo $r["SELL_EVENT_NM"]; ?>"
													data-ref-count="<?php echo $r["ref_count"]; ?>"
													data-p-ref-count="<?php echo $r["p_ref_count"]; ?>"
													data-lockr-set="<?php echo $r["LOCKR_SET"]; ?>"
													data-pre-enter-min="<?php echo $r["PRE_ENTER_MIN"]; ?>"
													data-event-ref-sno="<?php echo $r["EVENT_REF_SNO"]; ?>"
													data-sell-event-sno="<?php echo $r["SELL_EVENT_SNO"]; ?>"
													data-domcy-poss-event-yn="<?php echo $r["DOMCY_POSS_EVENT_YN"]; ?>"
													data-lockr-knd="<?php echo $r["LOCKR_KND"]; ?>" 
													data-use-prod="<?php echo $r["USE_PROD"]; ?>" 
													data-use-unit="<?php echo $r["USE_UNIT"]; ?>" 
													data-clas-cnt="<?php echo $r["CLAS_CNT"]; ?>" 
													data-sell-s-date="<?php echo $r["SELL_S_DATE"]; ?>" 
													data-sell-e-date="<?php echo $r["SELL_E_DATE"]; ?>" 
													data-domcy-poss-event-yn="<?php echo $r["DOMCY_POSS_EVENT_YN"]; ?>" 
													data-domcy-cnt="<?php echo $r["DOMCY_CNT"]; ?>" 
													data-domcy-day="<?php echo $r["DOMCY_DAY"]; ?>" 
													data-use-per-day="<?php echo $r["USE_PER_DAY"]; ?>" 
													data-use-per-week="<?php echo $r["USE_PER_WEEK"]; ?>" 
													data-use-per-week-unit="<?php echo $r["USE_PER_WEEK_UNIT"]; ?>" 
													data-enter-from-time="<?php echo $r["ENTER_FROM_TIME"]; ?>" 
													data-enter-to-time="<?php echo $r["ENTER_TO_TIME"]; ?>" 
													data-sell-amt="<?php echo $r["SELL_AMT"]; ?>" 
													data-sell-yn="<?php echo $r["SELL_YN"]; ?>" 
													>
													
													<?php echo $r["SELL_EVENT_NM"] ;	?>
													<?php
														if ($r["SELL_YN"] !== 'Y') {
															echo "(판매중지)";
														} 
													?>
													<?php
														echo number_format($r["SELL_AMT"]) . '원'; // 천 단위로 컴마 추가
													?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>	
					</div>
				</div>
			</div>	
			<div id="select_group" class="mb-4 pb-3" >
				<h4 class="d-flex align-items-center mb-2">
					<iconify-icon class="fs-24px me-2 text-body text-opacity-75 my-n1" icon="solar:dumbbell-large-bold-duotone"></iconify-icon> 팩키지 이용권 구성
				</h4>
				<p>신규생성 또는 기존 이용권 그룹을 불러올 수 있습니다.</p>
				<div class="card">
					<div class="list-group list-group-flush fw-bold">
						<!-- 기존 내용 유지 -->
						<div class="list-group-item align-items-center all-pkg-event" id="pkg_content" style="display:none">
							<div class="flex-fill">
								<div class="accordion" id="pkg_accordion">
								</div>
								<div id="acc_item_template" style="display:none">
									<div class="accordion-item">
									<input type="hidden" class="num">
										<h2 class="accordion-header" id="heading[id]" style="position: relative;">
											<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse[id]" aria-expanded="true" aria-controls="collapse[id]">
												<h4><b>신규이용권</b></h4>
												<p>
													Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
													Maecenas id gravida libero. Etiam semper id sem a ultricies.
												</p>
											</button>
											<button class="btn-close accordion-close" onClick="closeEvent(this)" ></button>
										</h2>
										<div id="collapse[id]" class="accordion-collapse collapse show" aria-labelledby="heading[id]">
											<div class="accordion-body">
												<strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
											</div>
										</div>
									</div>
								</div>
							<div class="flex-fill">
								<div class="text-body text-opacity-60 d-flex align-items-center mt-4">
									<table class="table fw-bold table-custom" >
										<thead>
											<tr>
												<th >팩키지 이용권 구성</th>
												<th width="150px">판매가</th>
												<th  width="1%"></th>
											</tr>
										</thead>
										<tbody id="price_body">
											<tr >
												<td class="align-middle" >
													<span style="font-weight:bold">합계</span>
												</td>
												<td >
													<div class="text-body text-opacity-60 sell-price-width position-relative d-flex align-items-center">
														<input class="form-control "  id="pkg_event_total_price" maxlength="11" pattern="[0-9]*" inputmode="numeric" style="font-weight:bold" readonly />
														<span class="unit-label">원</span>
													</div>
												</td>
												<td>
												</td>
											</tr>
										</tbody>
									</table>
									<table style="display:none">
										<tbody id="price_row_template">
											<tr>
												<td class="fs-13px align-middle">
													<span id="title_label[id]"></span>
												</td>
												<td>
													<div class="text-body text-opacity-60 sell-price-width position-relative d-flex align-items-center">
														<input type="hidden" class="num" /> 
														<input class="form-control pkg_event_price"  id="pkg_event_price[id]" maxlength="11" pattern="[0-9]*" inputmode="numeric" />
														<span class="unit-label">원</span>
													</div>
												</td>
												<td class="price-close">
													<button class="btn-close" onClick="closePrice(this)"></button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="display:none">
										<tr>
					<!-- 저장 버튼 중앙 정렬 및 길이 150px -->
					<div class="justify-content-center mt-3" id="saveButtonSection" style="display:flex">
						<button class="btn btn-primary me-2 disabled" style="width: 150px;" id="saveButton">저장</button>
						<button class="btn btn-primary" style="width: 150px; display:none" id="cancelButton" >취소</button>
					</div>
					<div class="justify-content-center mt-3" id="editButtonSection" style="display:none" >
						<button class="btn btn-primary me-2" style="width: 150px;" id="editButton">수정</button>
						<button class="btn btn-primary me-2" style="width: 150px;" id="copyButton">복사생성</button>
						<button class="btn btn-primary" style="width: 150px;" id="deleteButton">삭제</button>
					</div>
				</div>
			</div>
			<!-- END #general -->
			<!-- BEGIN #select_group -->
			<div id="select_group" class="mb-4 pb-3" style="display:none;" >
				<div class="card" id="groupTemplate" >
					<div class="list-group list-group-flush fw-bold">
						<div class="list-group-item align-items-center" id="eventTypeSection" style="display: none;">
							<div class="flex-fill">
								<div>기간제/횟수제 구분<span class="text-danger">*</span>
								<input type="hidden" id="p_sell_event_sno" value="<?php echo $pkg_event_sno ?>"></div>
								<inpup type="hidden" id="group_event_sno"></inpup>
								<input type="hidden" id="locker_enabled"></input>
								<input type="hidden" id="h_m_cate"></input>
								<div class="text-body text-opacity-60">
									<select class="form-select event-type-width" id="event_type" name="event_type">
										<?php foreach ($sDef['M_CATE_PROPERTY'] as $r => $v) : ?>
											<?php if ($r !== 'PKG') : ?>
												<option value="<?php echo $r; ?>">
													<?php echo $v; ?>
												</option>
											<?php endif; ?>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="flex-fill">
								<div>입장 가능 여부<span class="text-danger">*</span></div>
								<div class="d-flex align-items-center">
									<div class="text-body text-opacity-60">
										<select class="form-select event-type-width" id="acc_rtrct_dv" name="acc_rtrct_dv">
											<option value="0"></option>
											<?php foreach ($sDef['ACC_RTRCT_DV2'] as $r => $v) : ?>
												<option value="<?php echo $r; ?>">
													<?php echo $v; ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="text-body text-opacity-60 prev-time-width align-items-center ms-3 gap-2" style="display:none" id="prev_enter_section">
										<span>이용(수업)시간</span>
										<input class="form-control" type="number" name="pre_enter_min" step="5" maxlength="3" pattern="[0-9]*" inputmode="numeric" style="width: 80px;" />
										<span class="unit-label3">분</span>
										<span>전부터</span>
									</div>
								</div>
							</div>
							<div class="flex-fill">
								<div>휴회 가능 여부<span class="text-danger">*</span></div>
								<div class="d-flex align-items-center">
									<div class="text-body text-opacity-60">
										<select class="form-select event-type-width" id="domcy_poss_event_yn" name="domcy_poss_event_yn">
											<option value="0"></option>
											<option value="Y">휴회가능</option>
											<option value="N">휴회불가능</option>
										</select>
									</div>
								</div>
							</div>
							<div class="flex-fill" id="locker_select" style="display:none">
								<div>락커구역선택<span class="text-danger">*</span></div>
								<div class="d-flex align-items-center text-body text-opacity-60">
									<select class="form-select use-per-day-select-width me-2" id="lockr_knd" name="lockr_knd" >
										<option value=""></option>
										<?php 
											// $sDef['LOCKR_KND'] 배열(혹은 연관배열)을 임시로 복사
											$knd = $sDef['LOCKR_KND'];
											// 마지막 항목 제거
											array_pop($knd);
											foreach ($knd as $r => $v) : ?>
											<option value="<?php echo $r ?>"><?php echo $v; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
						<div style="display:none">
							<div class="list-group-item align-items-center" id="eventGroupNameSection" style="display: none;">
								<div class="flex-fill">
									<div>이용권 그룹명<span class="text-danger">*</span></div>
									<div class="text-body text-opacity-60 d-flex align-items-center">
										<input class="form-control event-group-input me-2" type="text" name="event_group_name" style="flex: 1;" />
										<div class="form-check form-switch mb-0" style="display:none">
											<input class="form-check-input" type="checkbox" id="groupSellYn" checked="" data-gtm-form-interact-field-id="0">
											<label class="form-check-label" for="groupSellYn" >(정상판매1)</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- 수업시간 입력란 -->
						<div class="list-group-item lesson-time-container" id="lessonTimeContainer" style="display: none;">
							<div class="flex-fill">
								<div>레슨시간<span class="text-danger">*</span></div>
								<div class="text-body text-opacity-60 lesson-time-width position-relative d-flex align-items-center">
									<input class="form-control" type="number" name="clas_min" step="5" maxlength="3" pattern="[0-9]*" inputmode="numeric" />
									<span class="unit-label">분</span>
								</div>
							</div>
						</div>
						<div style="display:none">
							<div class="list-group-item align-items-center" id="eventGroupDescSection" style="display: none;">
								<div class="flex-fill">
									<div>이용권 그룹 설명</div>
									<div class="text-body text-opacity-60"><textarea class="form-control" rows="3" name="event_group_desc"></textarea></div>
								</div>
							</div>
						</div>
						<div class="list-group-item align-items-center" style="display:none">
							<div class="flex-fill">
								<div>이용권그룹</div>
								<div class="text-body text-opacity-60">
									<input class="form-control event-group-input" type="text" name="event_group_name_display" disabled/>
								</div>
							</div>
						</div>
						<div class="list-group-item align-items-center">
							<div class="flex-fill">
								<div style="display: flex; align-items: center; ">
									판매이용권(상품)명<span class="text-danger me-2">*</span>
									(판매수: <span id="sold_cnt">0</span>)
								</div>
								<div class="text-body text-opacity-60 d-flex align-items-center gap-3">
									<input class="form-control event-input flex-grow-1" type="text" name="event_name" disabled/>
									<div class="form-check text-nowrap">
										<input class="form-check-input" type="checkbox" id="autoGenerateName" name="chk_auto_generate_name" checked>
										<label class="form-check-label" for="autoGenerateName">이름자동생성</label>
									</div>
									<div class="form-check form-switch mb-0" style="display:none">
										<input class="form-check-input" type="checkbox" id="eventSellYn" checked="" data-gtm-form-interact-field-id="0">
										<label class="form-check-label" for="eventSellYn">(정상판매)</label>
									</div>
								</div>
							</div>
						</div>	
									
						<div class="list-group-item d-flex align-items-center">
							<div class="flex-fill" id="lesson-cnt-section" style="display: none;">
								<div>이용횟수<span class="text-danger">*</span></div>
								<div class="text-body text-opacity-60 lesson-time-width position-relative d-flex align-items-center">
									<input class="form-control" type="number" name="clas_cnt" step="5" maxlength="3" pattern="[0-9]*" inputmode="numeric" />
									<span class="unit-label">회</span>
								</div>
							</div>
							<div class="flex-fill">
								<div>이용기간<span class="text-danger">*</span></div>
								<div class="d-flex align-items-center text-body text-opacity-60">
									<div  class="text-body text-opacity-60 use-prod-width me-2">
										<input class="form-control" type="number" name="use_prod"  maxlength="3" pattern="[0-9]*" inputmode="numeric" />
									</div>
									<div  class="text-body text-opacity-60">
										<select class="form-select use-prod-type-width" id="use_prod_unit" name="use_prod_unit">
											<option value="M">
												개월
											</option>
											<option value="D">
												일
											</option>
										</select>
									</div>
								</div>
							</div>
							<div class="flex-fill">
								<div>판매가<span class="text-danger">*</span></div>
								<div class="text-body text-opacity-60 sell-price-width position-relative d-flex align-items-center">
									<input class="form-control "  name="event_price" maxlength="11" pattern="[0-9]*" inputmode="numeric" />
									<span class="unit-label">원</span>
								</div>
							</div>		
						</div>
						<div class="list-group-item align-items-center" id="enter_time_section" style="display: none;">
							<div class="flex-fill">
								<div>이용 시간대</div>
								<div class="d-flex align-items-center text-body text-opacity-60">
									<div class="input-group bootstrap-timepicker me-2">
										<input id="fromTime" type="text" class="form-control" disabled />
										<span class="input-group-text input-group-addon">
											<i class="fa fa-clock"></i>
										</span>
									</div>
									<span class="me-2">~</span>
									<div class="input-group bootstrap-timepicker me-3">
										<input id="toTime" type="text" class="form-control" disabled/>
										<span class="input-group-text input-group-addon">
											<i class="fa fa-clock"></i>
										</span>
									</div>
									<div class="form-check me-3">
										<input class="form-check-input" type="checkbox" id="all_time" checked />
										<label class="form-check-label" for="all_time">무제한</label>
									</div>
									<div class="form-check" id="class_time_check_section" style="display:none" >
										<input class="form-check-input" type="checkbox" id="class_all_time"  />
										<label class="form-check-label" for="class_all_time">수업시간</label>
									</div>
									<div class="text-body text-opacity-60 prev-time-width align-items-center ms-3 gap-2" id="class_time_section"  style="display:none">
										<input class="form-control" type="number" name="prev_study_min" step="5" maxlength="3" pattern="[0-9]*" inputmode="numeric" style="width: 80px;" />
										<span class="unit-label3">분</span>
										<span>전부터</span>
									</div>
								</div>
							</div>
						</div>
						<div class="list-group-item align-items-center" id="locker_none_disp1">
							<div class="flex-fill">
								<div>이용 요일</div>
								<div class="text-body text-opacity-60 d-flex align-items-center">
									<div class="form-check form-check-inline">
										<input class="form-check-input day-checkbox" type="checkbox" id="mon" checked />
										<label class="form-check-label" for="mon">월</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input day-checkbox" type="checkbox" id="tue" checked />
										<label class="form-check-label" for="tue">화</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input day-checkbox" type="checkbox" id="wed" checked />
										<label class="form-check-label" for="wed">수</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input day-checkbox" type="checkbox" id="thu" checked />
										<label class="form-check-label" for="thu">목</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input day-checkbox" type="checkbox" id="fri" checked />
										<label class="form-check-label" for="fri">금</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input day-checkbox" type="checkbox" id="sat" checked />
										<label class="form-check-label" for="sat">토</label>
									</div>
									<div class="form-check me-4">
										<input class="form-check-input day-checkbox" type="checkbox" id="sun" checked />
										<label class="form-check-label" for="sun">일</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="all_week" checked />
										<label class="form-check-label" for="all_week">전체요일</label>
									</div>
								</div>
							</div>
						</div>
						<div class="list-group-item align-items-center"  id="locker_none_disp2" style="display:flex"> <!-- gap-4 제거 -->
							<div class="flex-fill me-4" id="enterPerDaySection" style="display:none">
								<div>일일 입장 가능 횟수</div>
								<div class="text-body text-opacity-60">
									<select class="form-select use-per-day-select-width" id="enter_per_day" name="enter_per_day">
										<option value="0">무제한</option>
										<?php for ($i = 1; $i <= 5; $i++) : ?>
											<option value="<?php echo $i; ?>">
												<?php echo $i; ?>회
											</option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
							<div class="flex-fill me-4"> <!-- 첫 번째 flex-fill에 오른쪽 마진 추가 -->
								<div>일일 이용 가능 횟수</div>
								<div class="text-body text-opacity-60">
									<select class="form-select use-per-day-select-width" id="use_per_day" name="use_per_day">
										<option value="0">무제한</option>
										<?php for ($i = 1; $i <= 5; $i++) : ?>
											<option value="<?php echo $i; ?>">
												<?php echo $i; ?>회
											</option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
							<div class="flex-fill me-4"> <!-- 두 번째 flex-fill에 오른쪽 마진 추가 -->
								<div>한주 이용 가능 횟수/일수<span class="text-danger">*</span></div>
								<div class="d-flex align-items-center text-body text-opacity-60">
									<select class="form-select use-per-day-select-width me-2" id="use_per_week_unit" name="use_per_week_unit" data-parsley-required="true" data-gtm-form-interact-field-id="1">
										<option value="0">무제한</option>
										<option value="10">이용가능일수</option>
										<option value="20">이용가능횟수</option>
									</select>
									<div class="text-body text-opacity-60 lesson-time-width position-relative d-flex align-items-center" id="use_cnt_per_week_section" style="visibility:hidden">
										<input class="form-control" type="number" name="use_per_week" step="1" maxlength="3" pattern="[0-9]*" inputmode="numeric" />
										<span class="unit-label">회</span>
									</div>
								</div>
							</div>
						</div>
						<div class="list-group-item align-items-center"  id="locker_disp" style="display:none">
							<div class="flex-fill">
								<div>락커 번호대<span class="text-danger">*</span></div>
								<div class="d-flex align-items-center text-body text-opacity-60">
									<div  class="text-body text-opacity-60 lock-range me-2">
										<input class="form-control" type="text" name="lockr_number_from"  maxlength="4" pattern="[0-9]*" inputmode="numeric" disabled/> ~
										<input class="form-control" type="text" name="lockr_number_to"  maxlength="4" pattern="[0-9]*" inputmode="numeric" disabled/>
									</div>
									<div class="form-check text-nowrap">
										<input class="form-check-input" type="checkbox" id="all_locker" name="all_locker" checked>
										<label class="form-check-label" for="all_locker">전체</label>
									</div>
								</div>
							</div>
							<div class="flex-fill">
								<div>홀수/짝수<span class="text-danger">*</span></div>
								<div class="d-flex align-items-center text-body text-opacity-60">
									<select class="form-select locker-number-type-select-width me-2" id="locker_number_type" name="locker_number_type" >
										<option value="all">전체</option>
										<option value="odd">홀수</option>
										<option value="even">짝수</option>
									</select>
								</div>
							</div>
							<div class="flex-fill">
								<div>락커타입<span class="text-danger">*</span></div>
								<div class="d-flex align-items-center text-body text-opacity-60">
									<div class="form-check me-3">
										<input class="form-check-input" type="checkbox" id="uni_sex_locker" checked />
										<label class="form-check-label" for="locker_type">혼용</label>
									</div>	
									<div class="form-check me-3">
										<input class="form-check-input" type="checkbox" id="man_woman_locker" checked />
										<label class="form-check-label" for="locker_type">일반</label>
									</div>	
								</div>
							</div>
						</div>
						<div class="list-group-item align-items-center"  id="locker_none_disp3" style="display:flex">
							<div class="flex-fill">
								<div>휴회가능횟수</div>
								<div class="d-flex align-items-center text-body text-opacity-60">
									<div class="text-body text-opacity-60 lesson-time-width position-relative d-flex align-items-center me-4">
										<input class="form-control" type="number" name="domcy_cnt" step="1" maxlength="3" pattern="[0-9]*" inputmode="numeric" />
										<span class="unit-label">회</span>
									</div>
								</div>
							</div>
							<div class="flex-fill">
								<div>휴회 가능 일수(휴회 합계일수)</div>
								<div class="d-flex align-items-center text-body text-opacity-60">
									<div class="text-body text-opacity-60 lesson-time-width position-relative d-flex align-items-center me-4">
										<input class="form-control" type="number" name="domcy_day" step="5" maxlength="3" pattern="[0-9]*" inputmode="numeric" />
										<span class="unit-label">일</span>
									</div>
								</div>
							</div>
						</div>
						<div class="list-group-item align-items-center" style="display:none">
							<div class="flex-fill">
								<div>판매기간</div>
								<div class="text-body text-opacity-60 d-flex align-items-center">
									<div class="sell-date-daterange-width position-relative" id="sell-date-daterange">
										<input type="text" name="sell-date-daterange" class="form-control" value="" placeholder="" />
										<div class="input-group-text" id="btn_cal"><i class="fa fa-calendar"></i></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END #select_group -->
		
			<!-- BEGIN #add_event -->
			<div id="add_event" class="mb-4 pb-3" style="display:none">
				
				<!-- 저장 버튼 중앙 정렬 및 길이 150px -->
				 <!-- 저장 버튼 중앙 정렬 및 길이 150px -->
				<div style="display:none">
					<div class="justify-content-center mt-3" id="saveProductSection" style="display:flex">
						<button class="btn btn-primary me-2 disabled" style="width: 150px;" id="saveProductButton">저장</button>
						<button class="btn btn-primary" style="width: 150px; display:none" id="cancelProductButton" >취소</button>
					</div>
					<div class="justify-content-center mt-3" id="editProductSection" style="display:none" >
						<button class="btn btn-primary me-2" style="width: 150px;" id="editProductButton">수정</button>
						<button class="btn btn-primary me-2" style="width: 150px;" id="copyProductButton">복사생성</button>
						<button class="btn btn-primary" style="width: 150px;" id="deleteProductButton">삭제</button>
					</div>
				</div>
			</div>
			<!-- END #notifications -->
			<div class="justify-content-center mt-3" id="saveProductSection" style="display:flex">
				<button class="btn btn-primary me-2" style="width: 150px; " id="cancelPackageButton" onClick="window.history.back()">목록으로</button>
				<button class="btn btn-primary " style="width: 150px;" id="savePackageButton">팩키지 이용권 등록</button>
			</div>
		</div>
		<!-- END col-9-->
	</div>
	<!-- END row -->
</div>


<?=$jsinc ?>

<script>
	// PHP에서 $sDef를 JavaScript 변수로 전달
    const sDef = <?php echo json_encode($sDef); ?>;
	document.addEventListener("DOMContentLoaded", function () {
		//팩키지 이용권 그룹 섹션
		const pkg_pkg_prev_event_group_status = document.getElementById("pkg_pkg_prev_event_group_status");

		const pkg_prev_event_section = document.getElementById("pkg_prev_event_section");
		const pkgEventGroupNameSection = document.getElementById("pkgEventGroupNameSection");
		
		const pkg_groupSellYnSection = document.getElementById("pkg_groupSellYnSection");

		const pkg_groupSellYn = document.getElementById("pkg_groupSellYn");
		const pkg_groupSellYnLabel = document.querySelector('label[for="pkg_groupSellYn"]');
		
		const pkg_eventSellYn = document.getElementById("pkg_eventSellYn");
		const pkg_eventSellYnLabel = document.querySelector('label[for="pkg_eventSellYn"]');

		const pkg_group_event_name = document.getElementById("pkg_group_event_name");
		const pkg_group_event_select = document.getElementById("pkg_group_event_select");

		//판매기간
		const pkgSellDateDateRange = document.getElementById('pkg-sell-date-daterange');
		const btn_pkg_cal = document.querySelector("#btn_pkg_cal");

		let isReloaded = false;

		$(pkgSellDateDateRange).daterangepicker({
			"locale": {
				"format": "YYYY-MM-DD",
				"separator": " ~ ",
				"applyLabel": "확인",
				"cancelLabel": "취소",
				"fromLabel": "From",
				"toLabel": "To",
				"customRangeLabel": "Custom",
				"weekLabel": "W",
				"daysOfWeek": ["일", "월", "화", "수", "목", "금", "토"],
				"monthNames": ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
			},
			"startDate": new Date(),
			"endDate": new Date(),
			"drops": "auto"
		}, function (start, end, label) {
			pkgSellDateDateRange.querySelector('input').value = start.format('YYYY-MM-DD') + ' ~ ' + end.format('YYYY-MM-DD');
		});

		pkg_groupSellYn.addEventListener('change', function () {
			pkg_groupSellYnLabel.textContent = this.checked ? '(정상판매)' : '(판매중지)';
			
			fnChangeSellYn("group");
		});

		pkg_eventSellYn.addEventListener('change', function () {
			pkg_eventSellYnLabel.textContent = this.checked ? '(정상판매)' : '(판매중지)';
			fnChangeSellYn("event");
		});
		
		pkg_group_event_select.addEventListener("change", function(e){
			const selectedOption = pkg_group_event_select.options[pkg_group_event_select.selectedIndex];
			const sellEventNm = selectedOption.getAttribute("data-sell-event-nm") || "";
			const sellYn = selectedOption.getAttribute("data-sell-yn")  || "N";
			pkg_eventSellYnSection.style.display="none";
			
			pkg_group_event_name.value = sellEventNm;
			pkg_sell_event_select.value = "";
			removeAllItems();
			pkg_groupSellYn.checked = (sellYn == "Y");
			pkg_groupSellYnLabel.textContent = sellYn == "Y" ? '(정상판매)' : '(판매중지)';
			if(this.value == "")
		 	{
				pkg_group_event_name.removeAttribute("disabled");
				pkg_groupSellYnSection.style.display="none";

			} else {
				pkg_group_event_name.setAttribute("disabled", "true");
				pkg_groupSellYnSection.style.display="";
			} 
			filterPkgEventSelectOptions();
		});


		const pkg_sell_event_select = document.getElementById("pkg_sell_event_select");
		const pkg_sell_event_name = document.getElementById("pkg_sell_event_name");

		const pkg_cate_section = document.getElementById("pkg_cate_section");
		const pkg_eventSellYnSection = document.getElementById("pkg_eventSellYnSection");
		pkg_sell_event_select.addEventListener("change", function(e){
			const selectedOption = pkg_sell_event_select.options[pkg_sell_event_select.selectedIndex];
			const sellEventNm = selectedOption.getAttribute("data-sell-event-nm") || "";
			const sellYn = selectedOption.getAttribute("data-sell-yn")  || "N";
			if(pkg_groupSellYn.checked || selectedOption.value != "")
			{
				removeAllItems();
				
				pkg_sell_event_name.value = sellEventNm;
				pkg_eventSellYn.checked = (sellYn == "Y");
				pkg_eventSellYnLabel.textContent = sellYn == "Y" ? '(정상판매)' : '(판매중지)';
				if(this.value == "")
				{
					pkg_sell_event_name.removeAttribute("disabled");
					pkg_prev_event_section.style.display="";
					pkg_cate_section.style.display="";
					pkg_eventSellYnSection.style.display="none";
					// pkg_groupSellYnSection.style.display="none";
					// pkg_prev_event_section.style.display="";
					

				} else {
					
					pkg_sell_event_name.setAttribute("disabled", "true");
					pkg_prev_event_section.style.display="none";
					pkg_cate_section.style.display="none";
					pkg_eventSellYnSection.style.display="";
					// pkg_groupSellYnSection.style.display="";
					// pkg_prev_event_section.style.display="none";
				} 
				load_package_items();
			} else
			{
				alertToast('error',"판매중지된 이용권 그룹의 이용권은 신규생성할수 없습니다.");
			}
			
		});

		function removeAllItems()
		{
			pkgAccordion.innerHTML = "";

			const priceBody = document.getElementById('price_body');
			const rows = priceBody.getElementsByTagName('tr');
			
			// 마지막 행을 제외한 모든 행 제거
			while (rows.length > 1) {
				priceBody.removeChild(rows[0]);
			}

			// #pkg_event_total_price 값 비우기
			const totalPriceInput = document.getElementById('pkg_event_total_price');
			if (totalPriceInput) {
				totalPriceInput.value = '';
			} else {
				console.error('#pkg_event_total_price 요소를 찾을 수 없습니다.');
			}
			
			pkg_content.style.display="none";
			pkg_sell_event_name.value = "";
		}

		function load_package_items(){
			const options = prev_event.querySelectorAll('option:not([value=""])');
			const groupTemp = document.getElementById("groupTemplate");
			const eventTypeSelect = groupTemp.querySelector("#event_type");       

			// const eventGroupNameDisplay = groupTemp.querySelector('input[name="event_group_name_display"]'); //이용권 그룹명groupTemp
			const eventName = groupTemp.querySelector('input[name="event_name"]');  //중분류 명
			// const autoGenerateNameCheckbox = groupTemp.querySelector("#autoGenerateName"); // 변수 이름 변경
			
			const eventSellYn = groupTemp.querySelector("#eventSellYn");
			const eventSellYnLabel = groupTemp.querySelector('label[for="eventSellYn"]');
			const use_per_day = groupTemp.querySelector("#use_per_day");
			const use_per_week = groupTemp.querySelector('input[name="use_per_week"]');


			//이용권 섹션
			const eventGroupNameDisplay = groupTemp.querySelector('input[name="event_group_name_display"]'); //이용권 그룹명
			const autoGenerateNameCheckbox = groupTemp.querySelector("#autoGenerateName"); // 변수 이름 변경
			


			//이용기간
			const useProdInput = groupTemp.querySelector('input[name="use_prod"]');
			const useProdUnit = groupTemp.querySelector('select[name="use_prod_unit"]'); // 개월, 일 단위

			

			//이용권 가격
			const eventPrice = groupTemp.querySelector('input[name="event_price"]');


			//이용 횟수
			const lessionCntSection = groupTemp.querySelector("#lesson-cnt-section");
			const clasCntInput = groupTemp.querySelector('input[name="clas_cnt"]');

			//이용 시간대
			const fromTimeInput = groupTemp.querySelector("#fromTime");
			const toTimeInput = groupTemp.querySelector("#toTime");
			const allTimeCheckbox = groupTemp.querySelector("#all_time");

			
			//한주 이용 가능 횟수 섹션
			const use_cnt_per_week_section = groupTemp.querySelector("#use_cnt_per_week_section");
			const select_use_per_week_unit = groupTemp.querySelector("#use_per_week_unit");
			const unitLabel = use_cnt_per_week_section.querySelector('.unit-label');

			const locker_none_disp1 = groupTemp.querySelector("#locker_none_disp1");  //요일 섹션
			const locker_none_disp2 = groupTemp.querySelector("#locker_none_disp2");  //일일 이용가능 횟수 섹션
			const locker_none_disp3 = groupTemp.querySelector("#locker_none_disp3");  //휴회 섹션
			const all_locker = groupTemp.querySelector("#all_locker");

			//요일 선택 섹션
			const all_week = groupTemp.querySelector("#all_week");
			const dayCheckboxes = groupTemp.querySelectorAll('.day-checkbox');

			//일일 이용 가능횟수
			

			//휴회
			const domcyCntInput = groupTemp.querySelector('input[name="domcy_cnt"]');
			const domcyDayInput = groupTemp.querySelector('input[name="domcy_day"]');

			//락커
			const locker_disp = groupTemp.querySelector("#locker_disp");  //락커섹션
			//락커 번호대
			const lockr_number_from = groupTemp.querySelector('input[name="lockr_number_from"]');
			const lockr_number_to = groupTemp.querySelector('input[name="lockr_number_to"]');
			// 락커 체크박스 요소 가져오기
			const uniSexLocker = document.getElementById('uni_sex_locker');
			const manWomanLocker = document.getElementById('man_woman_locker');
			
			//판매기간
			const sellDateDateRange = groupTemp.querySelector('input[name="sell-date-daterange"]');
			const btn_cal = groupTemp.querySelector("#btn_cal");

			//판매 이용권 번호
			const p_sell_event_sno = groupTemp.querySelector("#p_sell_event_sno");
			//판매수
			const sold_cnt = groupTemp.querySelector("#sold_cnt");

			// 사용 안하는 파트
			const classTimeCheckSection = groupTemp.querySelector("#class_time_check_section");
			const classTimeSection = groupTemp.querySelector("#class_time_section");
			const enterTimeSection = groupTemp.querySelector("#enter_time_section");
			const prevStudyMinInput = groupTemp.querySelector('input[name="prev_study_min"]');
			const locker_enabled_input = groupTemp.querySelector('#locker_enabled');
			const enterAvailable = groupTemp.querySelector("#acc_rtrct_dv");      
			const prevEnterSection = groupTemp.querySelector("#prev_enter_section");  // 입장전 시간 섹션
			const clasMinInput = groupTemp.querySelector('input[name="clas_min"]');
			const eventGroupNameInput = groupTemp.querySelector('input[name="event_group_name"]');
			const eventGroupDescTextarea = groupTemp.querySelector('textarea[name="event_group_desc"]');  // 입장전 시간 섹션
			const prevEnterMinInput = groupTemp.querySelector('input[name="pre_enter_min"]');
			const domcy_poss_event_yn = groupTemp.querySelector("#domcy_poss_event_yn");

			
			const groupSellYn = groupTemp.querySelector("#groupSellYn");
			const locker_number_type = groupTemp.querySelector('input[name="locker_number_type"]');
			const eventGroupNameSection = groupTemp.querySelector("#eventGroupNameSection");
			const lessonTimeContainer = groupTemp.querySelector("#lessonTimeContainer");
			const eventGroupDescSection = groupTemp.querySelector("#eventGroupDescSection");

			// prev_event.options[prev_event.selectedIndex];
			options.forEach(option => {
				const eventType = option.getAttribute("data-event-type") || "0";
				const accRtrctDv = option.getAttribute("data-acc-rtrct-dv") || "0";
				const clasMin = option.getAttribute("data-clas-min") || "";
				const eventDesc = option.getAttribute("data-event-desc") || "";
				let sellEventNm = option.getAttribute("data-sell-event-nm") || "";
				const lockrSet = option.getAttribute("data-lockr-set")  || "N";
				const preEnterMin =  option.getAttribute("data-pre-enter-min")  || "";
				const domcyPossEventYn = option.getAttribute("data-domcy-poss-event-yn") || "";
				const domcyCnt = option.getAttribute("data-domcy-cnt") || "";
				const domcyDay = option.getAttribute("data-domcy-day") || "";
				const lockr_knd_value = option.getAttribute("data-lockr-knd") || "";
				const use_prod = option.getAttribute("data-use-prod") || "";
				const use_unit = option.getAttribute("data-use-unit") || "M";
				const clas_cnt = option.getAttribute("data-clas-cnt") || "";
				const sell_event_sno = option.getAttribute("data-sell-event-sno") || "";
				const sell_s_date = option.getAttribute("data-sell-s-date") || "";
				const sell_e_date = option.getAttribute("data-sell-e-date") || "";
				const input_use_per_day = option.getAttribute("data-use-per-day") || "";
				const input_use_per_week = option.getAttribute("data-use-per-week") || "";
				const input_use_per_week_unit= option.getAttribute("data-use-per-week-unit") || "";
				const enter_from_time = option.getAttribute("data-enter-from-time") || "";
				const enter_to_time = option.getAttribute("data-enter-to-time") || "";
				const sell_amt = option.getAttribute("data-sell-amt") || "";
				const sellYn = option.getAttribute("data-sell-yn") || "N";
				const event_ref_sno = option.getAttribute("data-event-ref-sno") || "";
				const mCate = option.getAttribute("data-m-cate") || "";
				
				// 현재 selectBox에 선택되어 있는 value와 동일한 옵션이 표시되어 있으면
				if (option.value != pkg_sell_event_select.value && event_ref_sno === pkg_sell_event_select.value) {
					eventSellYn.checked = (sellYn == "Y" || pkg_sell_event_select.value == "");
					eventSellYnLabel.textContent = eventSellYn.checked ? '(정상판매)' : '(판매중지)';
					
					inputMode.value = "U";
					groupSellYn.removeAttribute("disabled");
					

					// 현재 화면에서 동일한 값을 가진 radio 버튼을 찾아 선택
					const targetRadio = document.querySelector(`input[name="m_cate"][value="${mCate}"]`);
					if (targetRadio) {
						targetRadio.checked = true;
					}
					eventName.value=sellEventNm;
					eventTypeSelect.value = eventType;
					enterAvailable.value = accRtrctDv;
					prevEnterMinInput.value = preEnterMin;
					domcy_poss_event_yn.value = domcyPossEventYn;

					
					use_per_day.value = input_use_per_day || "0";
					use_per_week.value = input_use_per_week || "0";
					use_per_week_unit.value = input_use_per_week_unit || "0";
					if(use_per_week_unit.value != "0")
					{
						use_cnt_per_week_section.style.visibility = "visibile";
					} else
					{
						use_cnt_per_week_section.style.visibility = "hidden";
					}
					useProdInput.value = use_prod;
					useProdUnit.value =  use_unit;
					clasCntInput.value = clas_cnt;
					p_sell_event_sno.value = sell_event_sno;
					
					prev_event.value = sell_event_sno;
					sellDateDateRange.value = sell_s_date + (sell_s_date != "" || sell_e_date != "" ? " ~ " : "") + sell_e_date;
					// domcy_poss_event_yn.value = domcyPossEventYn;
					// if(domcy_poss_event_yn.value == "Y")
					// {
					// 	locker_none_disp3.style.display="flex";
					// } else
					// {
					// 	locker_none_disp3.style.display="none";
					// }
					domcyDayInput.value = domcyDay;
					domcyCntInput.value = domcyCnt;
					fromTimeInput.value = convertTo12Hour(enter_from_time);
					toTimeInput.value = convertTo12Hour(enter_to_time);
					let value = sell_amt.replace(/[^0-9]/g, '');
					eventPrice.value = Number(value).toLocaleString('ko-KR');

					if(pkg_sell_event_select.value != "")
					{
						productModeChange("N");
					} else
					{
						productModeChange("I");
					}

					checkSelections();
					loadSoldCnt(pkg_sell_event_select.value);
					if(sell_event_sno != "")
						eventSellYn.removeAttribute("disabled");
					else
						eventSellYn.setAttribute("disabled", "true");

					if(pkg_sell_event_select.value !="")
					{
						let clonedChildren = cloneChildren('acc_item_template');
						const itemCount = pkgAccordion.querySelectorAll('.accordion-item').length;
						const newId = itemCount + 1; // 현재 개수 + 1
						clonedChildren = replaceText(clonedChildren, '[id]', newId.toString());
						clonedChildren = addClass(clonedChildren, 'accordion-item', mCate.toLowerCase()); 
						sellEventNm = (sellEventNm == ""  ? "신규이용권" : sellEventNm);
						clonedChildren = modifyContent(clonedChildren, 'h4 b', sellEventNm);

						let eventDesc = (sDef.M_CATE_PROPERTY[eventType] == "" ? "" :  sDef.M_CATE_PROPERTY[eventType] + " | ");
						if(mCate == "PRV")
						{
							eventDesc += sDef.M_CATE[mCate] + " | " +sDef.ACC_RTRCT_DV2[accRtrctDv] + (clasMin != "" ? " | " +  clasMin + "분" : "");
						} else 
						{
							eventDesc += sDef.M_CATE[mCate] + (accRtrctDv != "0" ? " | " + sDef.ACC_RTRCT_DV2[accRtrctDv]  : "") ;
						}
						clonedChildren = modifyContent(clonedChildren, '.accordion-button p', eventDesc);

						let clonedGroup = cloneById('groupTemplate');
						// <div class="accordion-body"> 내부 내용 수정
						// clonedChildren = modifyInnerHTML(clonedChildren, '.accordion-body', '<strong>수정된 바디입니다.</strong> 새로운 내용이 여기에 들어갑니다.');
						
						// emptyElement('pkg_accordion');
						appendElement(clonedChildren, 'pkg_accordion');
						replaceAccordionBodyWithClonedGroup(`collapse${newId}`, clonedGroup);
						pkg_content.style.display="";
						setPkgProductDisp();
						updateAccordionItems();
						let element = pkgAccordion.querySelector("#collapse"+newId.toString());
						setListenerAndVariable(element);
						pkg_sell_event_name.setAttribute("disabled", "true");
						let accordionItem = element.closest(".accordion-item");
						let price = accordionItem.querySelector('input[name="event_price"]');
						let num = accordionItem.querySelector(".num");
						num.value = newId.toString();
						let clonedPriceRow = cloneChildren('price_row_template');
						clonedPriceRow = replaceText(clonedPriceRow, '[id]', newId.toString());
						let h_m_cate = accordionItem.querySelector("#h_m_cate");
						h_m_cate.value = mCate;
						appendElementBeforeLastTr(clonedPriceRow, 'price_body');

						let pkgEventPrice = document.getElementById('pkg_event_price'+newId.toString());
						let title_label = document.getElementById("title_label"+newId.toString());
						title_label.innerHTML=sellEventNm;
						pkgEventPrice.value = price.value;
						let num2 = getNumOfPrice(pkgEventPrice);
						num2.value = newId.toString();
						pkgEventPriceAddEventListener(pkgEventPrice);
						calculateTotalPrice();
					}
				}
			});
			function productModeChange(mode)
			{
				if(mode=="E" || mode=="I")  //Normal Mode
				{
					useProdInput.removeAttribute("disabled");
					useProdUnit.removeAttribute("disabled");
					eventPrice.removeAttribute("disabled");
					clasCntInput.removeAttribute("disabled");
					
					autoGenerateNameCheckbox.removeAttribute("disabled");
					
					allTimeCheckbox.removeAttribute("disabled");
					if(!allTimeCheckbox.checked)
					{
						fromTimeInput.removeAttribute("disabled");
						toTimeInput.removeAttribute("disabled");
					}
					dayCheckboxes.forEach(checkbox => {
						checkbox.removeAttribute("disabled");
					});
					all_week.removeAttribute("disabled");
					select_use_per_week_unit.removeAttribute("disabled");
					use_per_day.removeAttribute("disabled");
					domcyDayInput.removeAttribute("disabled");
					domcyCntInput.removeAttribute("disabled");
					sellDateDateRange.removeAttribute("disabled");
					if(!all_locker.checked)
					{
						lockr_number_from.removeAttribute("disabled");
						lockr_number_to.removeAttribute("disabled");
					}
					if(locker_number_type)
					locker_number_type.removeAttribute("disabled");
					uniSexLocker.removeAttribute("disabled");
					manWomanLocker.removeAttribute("disabled");
					btn_cal.removeAttribute("disabled");
					saveProductSection.style.display="flex";
					editProductSection.style.display="none";
					
					if(mode == "I")
					{
						cancelProductButton.style.display="none";
					} else
					{
						cancelProductButton.style.display="";
					}
					
				} else if(mode == "N")
				{
					useProdInput.setAttribute("disabled", "true");
					useProdUnit.setAttribute("disabled", "true");
					eventPrice.setAttribute("disabled", "true");
					clasCntInput.setAttribute("disabled", "true");
					fromTimeInput.setAttribute("disabled", "true");
					toTimeInput.setAttribute("disabled", "true");
					allTimeCheckbox.setAttribute("disabled", "true");
					dayCheckboxes.forEach(checkbox => {
						checkbox.setAttribute("disabled", "true");
					});
					all_week.setAttribute("disabled", "true");
					select_use_per_week_unit.setAttribute("disabled", "true");
					use_per_day.setAttribute("disabled", "true");
					domcyDayInput.setAttribute("disabled", "true");
					domcyCntInput.setAttribute("disabled", "true");
					sellDateDateRange.setAttribute("disabled", "true");
					lockr_number_from.setAttribute("disabled", "true");
					lockr_number_to.setAttribute("disabled", "true");
					if(locker_number_type)
					locker_number_type.setAttribute("disabled", "true");
					uniSexLocker.setAttribute("disabled", "true");
					manWomanLocker.setAttribute("disabled", "true");
					btn_cal.setAttribute("disabled", "true");
					saveProductSection.style.display="none";
					editProductSection.style.display="flex";
					autoGenerateNameCheckbox.setAttribute("disabled", "true");
					autoGenerateNameCheckbox.checked = true;
					eventName.setAttribute("disabled", "true");
				}
			}

			// 신규: 기간제/횟수제 및 입장 가능여부 선택 시
			function checkSelections() {
				const mCate = groupTemplate.querySelector('input[name="m_cate"]:checked')?.value || "";
				const isEventTypeSelected = eventTypeSelect.value !== "0";
				const isAccRtrctSelected = enterAvailable.value !== "0";
				const is_domcy_poss_event_yn_selected = domcy_poss_event_yn.value !== "0";

				// 만약 횟수제(eventTypeSelect.value가 '20'이 아닌 경우) 레슨 횟수 영역 숨기기
				if (eventTypeSelect.value != "20") {
					lessionCntSection.style.display = "none";
				} else {
					lessionCntSection.style.display = "";
				}

				enterVisible();

				// [2] 기본 조건: 신규 그룹이고 event_type/acc_rtrct_dv(입장가능여부)가 선택됨
				if (isEventTypeSelected && isAccRtrctSelected && is_domcy_poss_event_yn_selected) {
					eventGroupNameSection.style.display = "flex";
					eventGroupDescSection.style.display = "flex";
					if (mCate === "PRV") {
						lessonTimeContainer.style.display = "flex";
					} else {
						lessonTimeContainer.style.display = "none";
					clasMinInput.value = "";
					}
				} else {
					eventGroupNameSection.style.display = "none";
					eventGroupNameInput.value = "";
					lessonTimeContainer.style.display = "none";
					clasMinInput.value = "";
					eventGroupDescSection.style.display = "none";
					eventGroupDescTextarea.value = "";
					saveButton.classList.add("disabled");
					cancelButton.style.display = "none";
				}
			}
			function enterVisible(){
				// [1] enterAvailable 값이 "10"이면 prevEnterSection 노출
				if (enterAvailable.value === "00" || enterAvailable.value === "0") {
					prevEnterSection.style.display = "flex"; // flex로 보이도록
					enterTimeSection.style.display="";
				} else {
					prevEnterSection.style.display = "none"; // 숨김
					enterTimeSection.style.display="none";
				}
			}

			
		}
		// accordion-item의 mb-2 클래스를 업데이트하는 함수
		function updateAccordionItems() {
			const items = document.querySelectorAll('.accordion-item'); // 모든 accordion-item 선택
			items.forEach((item, index) => {
				if (index < items.length - 2) {
					// 마지막 아이템이 아닌 경우 mb-2 추가
					item.classList.add('mb-2');
				} else {
					// 마지막 아이템인 경우 mb-2 제거
					item.classList.remove('mb-2');
				}
			});
		}


		// 이용권 그룹 섹션
		const inputMode = document.getElementById("input_mode");  //이용권그룹 CRUD 상태 저장
		inputMode.value = "I";
		// 이용권 분류 섹션
		const radioButtons = document.querySelectorAll('input[name="m_cate"]');  // 이용권 분류 (라디오 버튼들)
		const lockerContainer = document.querySelector(".locker-container");     // 락커 여부 섹션
		const locker_enabled = document.querySelector("#locker_enabled");        // 락커 활성화 여부
		// 이용권 그룹 불러오기 섹션
		const mCateAllDiv = document.querySelector("#m_cate_all");               // 기존 이용권 그룹 선택 섹션
		const prev_event_group_status = document.getElementById("prev_event_group_status");
		const groupSelectBox = document.querySelector('select[name="prev_event_group"]'); // 기존 이용권 그룹 선택

		const prev_event = document.getElementById("prev_event");		



		const savePackageButton = document.getElementById("savePackageButton");

		savePackageButton.addEventListener("click", fnSavePackage);
		
		//이용권 그룹 저장 버튼 섹션
		const saveButtonSection = document.getElementById("saveButtonSection");
		const saveButton = document.getElementById("saveButton");  //이용권 그룹 저장버튼

		//이용권 그룹 수정 버튼 섹션
		const editButtonSection = document.getElementById("editButtonSection");
		const editButton = document.getElementById("editButton");  
		const copyButton = document.getElementById("copyButton");
		const deleteButton = document.getElementById("deleteButton");
		const cancelButton = document.getElementById("cancelButton");

		
		//판매 이용권 저장 버튼 섹션
		const saveProductSection = document.getElementById("saveProductSection");
		const saveProductButton = document.getElementById("saveProductButton");

		//판매 이용권 수정 버튼 섹션
		const editProductSection = document.getElementById("editProductSection");
		const editProductButton = document.getElementById("editProductButton");
		const cancelProductButton = document.getElementById("cancelProductButton");
		const copyProductButton = document.getElementById("copyProductButton");
		const deleteProductButton = document.getElementById("deleteProductButton");


		const groupTemplate = document.getElementById('groupTemplate');

		const days = {
						"mon": "월",
						"tue": "화",
						"wed": "수",
						"thu": "목",
						"fri": "금",
						"sat": "토",
						"sun": "일"
		};


			filterPkgGroupSelectOptions();
		// 필터링 함수
		function filterPkgGroupSelectOptions() {
			const options = pkg_group_event_select.querySelectorAll('option:not([value=""])');
			options.forEach(option => {
				const mCate = option.getAttribute("data-m-cate");
				const lockerSet = option.getAttribute("data-lockr-set") || "N";
				const eventRefSno = option.getAttribute("data-event-ref-sno") || "";
				const sellEventSno = option.getAttribute("data-sell-event-sno") || "";
				option.style.display = ( mCate == "PKG" && eventRefSno == sellEventSno ) ? "" : "none"; 
			});

			// 필터링 후, 이미 선택된 옵션이 숨겨졌다면 첫 번째 표시되는 옵션으로 선택
			let isSelectedVisible = false;

			options.forEach(option => {
				// 표시되어 있는 옵션 중 첫 번째 옵션을 찾음
				if (option.style.display !== "none") {
					// 현재 selectBox에 선택되어 있는 value와 동일한 옵션이 표시되어 있으면
					if (option.value === pkg_group_event_select.value) {
						isSelectedVisible = true;
					}
				}
			});

			// 현재 선택된 옵션이 보이지 않는다면, 첫 번째 표시된 옵션으로 선택
			if (!isSelectedVisible ) {
				prev_event.value = "";
				sold_cnt.value = "0";
			}
		}

		function pkg_groupSelectBoxChange(){
			const selectedOption = pkg_group_event_select.options[pkg_group_event_select.selectedIndex];
			const sellEventNm = selectedOption.getAttribute("data-sell-event-nm") || "";
			const sellEventSno = selectedOption.getAttribute("data-sell-event-sno") || "";
			filterPkgEventSelectOptions();
		}

		// 필터링 함수
		function filterPkgEventSelectOptions() {
			const options = pkg_sell_event_select.querySelectorAll('option:not([value=""])');
			options.forEach(option => {
				const mCate = option.getAttribute("data-m-cate");
				const lockerSet = option.getAttribute("data-lockr-set") || "N";
				const eventRefSno = option.getAttribute("data-event-ref-sno") || "";
				const sellEventSno = option.getAttribute("data-sell-event-sno") || "";
				option.style.display = ( eventRefSno ===  pkg_group_event_select.value && sellEventSno !== pkg_group_event_select.value) ? "" : "none"; 
			});

			// 필터링 후, 이미 선택된 옵션이 숨겨졌다면 첫 번째 표시되는 옵션으로 선택
			let isSelectedVisible = false;

			options.forEach(option => {
				// 표시되어 있는 옵션 중 첫 번째 옵션을 찾음
				if (option.style.display !== "none") {
					// 현재 selectBox에 선택되어 있는 value와 동일한 옵션이 표시되어 있으면
					if (option.value === pkg_sell_event_select.value) {
						isSelectedVisible = true;
					}
				}
			});

			// 현재 선택된 옵션이 보이지 않는다면, 첫 번째 표시된 옵션으로 선택
			if (!isSelectedVisible ) {
				prev_event.value = "";
				sold_cnt.value = "0";
			}
		}

		setListenerAndVariable(groupTemplate);

		function setListenerAndVariable(element)
		{
			// 기간제/횟수제, 입장가능여부, 휴회가능여부, 락커그룹 섹션
			let eventTypeSection = element.querySelector("#eventTypeSection");
			let eventTypeSelect = element.querySelector("#event_type");           // 기간제/회수제 구분
			let enterAvailable = element.querySelector("#acc_rtrct_dv");          // 입장가능여부
			let prevEnterSection = element.querySelector("#prev_enter_section");  // 입장전 시간 섹션
			let prevEnterMinInput = element.querySelector('input[name="pre_enter_min"]');

			// 휴회가능 여부
			let domcy_poss_event_yn = element.querySelector("#domcy_poss_event_yn");

			// 락커 구역 섹션
			let locker_select = element.querySelector("#locker_select");
			let lockr_knd = element.querySelector("#lockr_knd");         // 락커 구역
			

			// 락커 번호 유형
			const locker_number_type = element.querySelector('input[name="locker_number_type"]');

			// 레슨시간 섹션
			const lessonTimeContainer = element.querySelector("#lessonTimeContainer");
			const clasMinInput = element.querySelector('input[name="clas_min"]');

			// 이용권 그룹 섹션
			const eventGroupNameSection = element.querySelector("#eventGroupNameSection");
			const eventGroupNameInput = element.querySelector('input[name="event_group_name"]');
			const eventGroupDescTextarea = element.querySelector('textarea[name="event_group_desc"]');
			const groupSellYn = element.querySelector("#groupSellYn");
			const groupSellYnLabel = element.querySelector('label[for="groupSellYn"]');

			// 이용권 그룹 설명 섹션
			const eventGroupDescSection = element.querySelector("#eventGroupDescSection");




			//이용권 섹션

			
			const eventGroupNameDisplay = element.querySelector('input[name="event_group_name_display"]'); //이용권 그룹명
			const eventName = element.querySelector('input[name="event_name"]');  //중분류 명
			const autoGenerateNameCheckbox = element.querySelector("#autoGenerateName"); // 변수 이름 변경
			
			const eventSellYn = element.querySelector("#eventSellYn");
			const eventSellYnLabel = element.querySelector('label[for="eventSellYn"]');


			//이용기간
			const useProdInput = element.querySelector('input[name="use_prod"]');
			const useProdUnit = element.querySelector('select[name="use_prod_unit"]'); // 개월, 일 단위

			

			//이용권 가격
			const eventPrice = element.querySelector('input[name="event_price"]');


			//이용 횟수
			const lessionCntSection = element.querySelector("#lesson-cnt-section");
			const clasCntInput = element.querySelector('input[name="clas_cnt"]');

			//이용 시간대
			const fromTimeInput = element.querySelector("#fromTime");
			const toTimeInput = element.querySelector("#toTime");
			const allTimeCheckbox = element.querySelector("#all_time");

			
			//한주 이용 가능 횟수 섹션
			const use_cnt_per_week_section = element.querySelector("#use_cnt_per_week_section");
			const use_per_week = element.querySelector('input[name="use_per_week"]');
			const select_use_per_week_unit = element.querySelector("#use_per_week_unit");
			const unitLabel = use_cnt_per_week_section.querySelector('.unit-label');

			const locker_none_disp1 = element.querySelector("#locker_none_disp1");  //요일 섹션
			const locker_none_disp2 = element.querySelector("#locker_none_disp2");  //일일 이용가능 횟수 섹션
			const locker_none_disp3 = element.querySelector("#locker_none_disp3");  //휴회 섹션
			const all_locker = element.querySelector("#all_locker");

			//요일 선택 섹션
			const all_week = element.querySelector("#all_week");
			const dayCheckboxes = element.querySelectorAll('.day-checkbox');

			//일일 이용 가능횟수
			const use_per_day = element.querySelector("#use_per_day");

			//휴회
			const domcyCntInput = element.querySelector('input[name="domcy_cnt"]');
			const domcyDayInput = element.querySelector('input[name="domcy_day"]');

			//락커
			const locker_disp = element.querySelector("#locker_disp");  //락커섹션
			//락커 번호대
			const lockr_number_from = element.querySelector('input[name="lockr_number_from"]');
			const lockr_number_to = element.querySelector('input[name="lockr_number_to"]');
			// 락커 체크박스 요소 가져오기
			const uniSexLocker = document.getElementById('uni_sex_locker');
			const manWomanLocker = document.getElementById('man_woman_locker');
			
			//판매기간
			const sellDateDateRange = element.querySelector('input[name="sell-date-daterange"]');
			const btn_cal = element.querySelector("#btn_cal");

			//판매 이용권 번호
			const p_sell_event_sno = element.querySelector("#p_sell_event_sno");
			//판매수
			const sold_cnt = element.querySelector("#sold_cnt");

			// 사용 안하는 파트
			const classTimeCheckSection = element.querySelector("#class_time_check_section");
			const classTimeSection = element.querySelector("#class_time_section");
			const enterTimeSection = element.querySelector("#enter_time_section");
			const prevStudyMinInput = element.querySelector('input[name="prev_study_min"]');
			if(element.id != "groupTemplate")
			{
				
				eventName.addEventListener("input", changeEventName);				
			}

			if(element.id == "groupTemplate")
			{
				cateChange();
				all_locker.addEventListener('change', function(){
					if(all_locker.checked)
					{
						lockr_number_from.setAttribute("disabled", "true");
						lockr_number_to.setAttribute("disabled", "true");
					} else
					{
						lockr_number_from.removeAttribute("disabled");
						lockr_number_to.removeAttribute("disabled");
					}
				});

				groupSellYn.addEventListener('change', function () {
					groupSellYnLabel.textContent = this.checked ? '(정상판매)' : '(판매중지)';
					fnChangeSellYn("group");
				});

				eventSellYn.addEventListener('change', function () {
					eventSellYnLabel.textContent = this.checked ? '(정상판매)' : '(판매중지)';
					fnChangeSellYn("event");
				});

				prev_event_group_status.addEventListener('change', function(e){
					filterGroupSelectOptions();
					groupSelectBoxChange();
				});
					
			}

			// 체크박스 상태 변경 감지
			uniSexLocker.addEventListener('change', lockerGenderCheckBoxChanged);
			manWomanLocker.addEventListener('change', lockerGenderCheckBoxChanged);
			

			function lockerGenderCheckBoxChanged() {

				// "혼용"과 "남녀"가 동시에 체크되면 "전체" 체크
				if (!uniSexLocker.checked && !manWomanLocker.checked) {
					uniSexLocker.checked = true;
					manWomanLocker.checked = true;
				}
			}

			init();

			// 초기 상태 설정: 이용권 분류 선택만 표시, 저장 버튼 및 요소 비활성화
			function init()
			{
				prev_event_group_section.style.display="none";
				// eventTypeSection.style.display = "none";
				eventGroupNameSection.style.display = "none";
				lessonTimeContainer.style.display = "none";
				eventGroupDescSection.style.display = "none";
				saveButtonSection.style.display = "flex"; // 초기 표시
				editButtonSection.style.display = "none"; // 초기 숨김
				cancelButton.style.display = "none"; // 초기 숨김
				saveButton.classList.add("disabled");
				eventTypeSelect.setAttribute("disabled", "true");
				enterAvailable.setAttribute("disabled", "true");
				domcy_poss_event_yn.setAttribute("disabled", "true");
				lockr_knd.setAttribute("disabled", "true");
				prevEnterMinInput.setAttribute("disabled", "true");
				eventGroupNameInput.setAttribute("disabled", "true");
				clasMinInput.setAttribute("disabled", "true");
				eventGroupDescTextarea.setAttribute("disabled", "true");
			}

			
			
			lockr_number_to.addEventListener("input", function(e){
				let value = this.value.replace(/[^0-9]/g, '');
				this.value = Number(value).toLocaleString('ko-KR');
			});
			lockr_number_from.addEventListener("input", function(e){
				let value = this.value.replace(/[^0-9]/g, '');
				this.value = Number(value).toLocaleString('ko-KR');
			});

			
			

			eventPrice.addEventListener("input", function (e){
				let value = this.value.replace(/[^0-9]/g, '');
				this.value = Number(value).toLocaleString('ko-KR');
				changePkgEventPrice(eventPrice);
			});

			select_use_per_week_unit.addEventListener("change", function(e){
				if(this.value == "10")
				{
					use_cnt_per_week_section.style.visibility = "visible";
					unitLabel.textContent = "일";
				} else if(this.value == "20")
				{
					use_cnt_per_week_section.style.visibility = "visible";
					unitLabel.textContent = "회";
				} else
				{
					use_per_week.value="";
					use_cnt_per_week_section.style.visibility = "hidden";
				}
			});
			// all_time 체크박스 변경 시 호출
			allTimeCheckbox.addEventListener("change", updateTimeInputsVisibility);
			// 요일 체크박스 이벤트 추가
			dayCheckboxes.forEach(checkbox => {
				checkbox.addEventListener("change", generateEventName);
			});
			// 이름자동생성 체크박스 이벤트
			autoGenerateNameCheckbox.addEventListener("change", generateEventName);
			useProdUnit.addEventListener("change", generateEventName);
			fromTimeInput.addEventListener("input", generateEventName);
			toTimeInput.addEventListener("input", generateEventName);
			// saveProductButton.addEventListener("click", fnEventSave);
			if(element.id == "groupTemplate")
			{
				domcy_poss_event_yn.addEventListener("change", checkSelections);
			}
			
			// 이벤트 리스너 정의 후 함수 정의
			function updateTimeInputsVisibility() {
				if (allTimeCheckbox.checked) {
					fromTimeInput.value = "";
					toTimeInput.value = "";
					fromTimeInput.disabled = true;
					toTimeInput.disabled = true;
				} else {
					fromTimeInput.disabled = false;
					toTimeInput.disabled = false;
				}
				generateEventName();
			}
			all_week.addEventListener("change", checkAllWeek);
			function checkAllWeek()
			{
				// 선택된 요일 문자열 생성
				dayCheckboxes.forEach(checkbox => {
					checkbox.checked = true;
				});
				all_week.checked = true;
				generateEventName();
			}
			// 저장 버튼 클릭 이벤트 추가
			saveButton.addEventListener("click", function (e) {
				if (!this.hasAttribute("disabled")) { // 비활성화 상태가 아닐 때만 실행
					fnGroupSave();
				}
			});

			locker_enabled.addEventListener("change", function(e){
				lockerCheckVisible();
				filterGroupSelectOptions();
				groupSelectBoxChange();
			});

			
			if(element.id == "groupTemplate")
			{
				// 이용권 분류 선택 시
				radioButtons.forEach(radio => {
					radio.addEventListener("change", cateChange);
				});
			}
			
			function cateChange(e){
				
				prev_event_group_section.style.display="flex";
				groupSelectBox.value = "";
				updateLockerVisibility();

				mCateAllDiv.style.display = "none";
				// eventTypeSection.style.display = "none";
				eventGroupNameSection.style.display = "none";
				eventGroupNameInput.value = "";
				lessonTimeContainer.style.display = "none";
				clasMinInput.value = "";
				eventGroupDescSection.style.display = "none";
				eventGroupDescTextarea.value = "";
				saveButtonSection.style.display = "flex";
				editButtonSection.style.display = "none";
				cancelButton.style.display = "none";
				saveButton.classList.add("disabled");
				
				
				
					eventTypeSelect.setAttribute("disabled", "true");
					enterAvailable.setAttribute("disabled", "true");
					domcy_poss_event_yn.setAttribute("disabled", "true");
					
				if(element.id == "groupTemplate")
				{
					lockr_knd.setAttribute("disabled", "true");
					prevEnterMinInput.setAttribute("disabled", "true");
					eventGroupNameInput.setAttribute("disabled", "true");
					clasMinInput.setAttribute("disabled", "true");
					eventGroupDescTextarea.setAttribute("disabled", "true");
					groupSelectBox.dispatchEvent(new Event("change"));
				}
				
				let mCateVal = iCheck.radioGet("m_cate");
				
				if(element.id == "groupTemplate")
				{
					if(mCateVal!= "OPT")
					{
						locker_disp.style.display="none";
						eventTypeSelect.removeAttribute("disabled");
						if(mCateVal== "PRV" )
						{
							eventTypeSelect.setAttribute("disabled", "true");
							eventTypeSelect.value="20";
						}
					} else
					{
						eventTypeSelect.setAttribute("disabled", "true");
						eventTypeSelect.value="10";
					}
					if (lockerContainer.style.display == "none")
					{
						locker_enabled.checked = false;
					}	
				
					eventTypeSelect.setAttribute("disabled", "true");
					eventTypeSection.style.display="flex";
				
				
					enterVisible();
				}
				
				lockerCheckVisible();
				
				filterGroupSelectOptions();
				setPkgProductDisp();
				
			}
			
			function saveVisible() {
				const isGroupNameVisible = eventGroupNameSection.style.display !== "none";
				const isLessonTimeVisible = lessonTimeContainer.style.display !== "none";
				
				const hasGroupName = eventGroupNameInput.value.trim() !== "";
				const hasLessonTime = clasMinInput.value.trim() !== "";

				eventGroupNameDisplay.value = eventGroupNameInput.value;
				// 저장 버튼 활성화 조건
				if (isGroupNameVisible && isLessonTimeVisible) {
					if (hasGroupName && hasLessonTime) {
						saveButton.classList.remove("disabled");
					} else {
						saveButton.classList.add("disabled");
					}
				} else if (isGroupNameVisible) {
					if (hasGroupName) {
						saveButton.classList.remove("disabled");
					} else {
						saveButton.classList.add("disabled");
					}
				} else if (isLessonTimeVisible) {
					if (hasLessonTime) {
						saveButton.classList.remove("disabled");
					} else {
						saveButton.classList.add("disabled");
					}
				} else {
					saveButton.classList.add("disabled");
				}
				
				generateEventName();
			}

			// 신규: 기간제/횟수제 및 입장 가능여부 선택 시
			function checkSelections() {
				const mCate = element.querySelector('input[name="m_cate"]:checked')?.value || "";
				const isEventTypeSelected = eventTypeSelect.value !== "0";
				const isAccRtrctSelected = enterAvailable.value !== "0";
				const is_domcy_poss_event_yn_selected = domcy_poss_event_yn.value !== "0";

				// 만약 횟수제(eventTypeSelect.value가 '20'이 아닌 경우) 레슨 횟수 영역 숨기기
				if (eventTypeSelect.value != "20") {
					lessionCntSection.style.display = "none";
				} else {
					lessionCntSection.style.display = "";
				}

				enterVisible();

				// [2] 기본 조건: 신규 그룹이고 event_type/acc_rtrct_dv(입장가능여부)가 선택됨
				if (isEventTypeSelected && isAccRtrctSelected && is_domcy_poss_event_yn_selected) {
					eventGroupNameSection.style.display = "flex";
					eventGroupDescSection.style.display = "flex";
					if (mCate === "PRV") {
						lessonTimeContainer.style.display = "flex";
					} else {
						lessonTimeContainer.style.display = "none";
					clasMinInput.value = "";
					}
				} else {
					eventGroupNameSection.style.display = "none";
					eventGroupNameInput.value = "";
					lessonTimeContainer.style.display = "none";
					clasMinInput.value = "";
					eventGroupDescSection.style.display = "none";
					eventGroupDescTextarea.value = "";
					saveButton.classList.add("disabled");
					cancelButton.style.display = "none";
				}
			}

			

			// 기존: prev_event_group 선택 시
			if(element.id == "groupTemplate")
			{
				eventTypeSelect.addEventListener("change", checkSelections);
				enterAvailable.addEventListener("change", checkSelections);
				eventGroupNameInput.addEventListener("input", saveVisible);
				clasMinInput.addEventListener("input", saveVisible);
				groupSelectBox.addEventListener("change", groupSelectBoxChange);
				prev_event.addEventListener("change", eventSelectBoxChange);
			}

			function eventSelectBoxChange(){
				if(element.id == "groupTemplate")
				{
					// const groupSelectedoption = pkg_prev_event_group.options[pkg_prev_event_group.selectedIndex];
					// const mCate = groupSelectedoption.getAttribute("data-m-cate") || "";
					const selectedOption = prev_event.options[prev_event.selectedIndex];
					const groupSelectedOption = groupSelectBox.options[groupSelectBox.selectedIndex];
					const eventType = groupSelectedOption.getAttribute("data-event-type") || "0";
					const accRtrctDv = groupSelectedOption.getAttribute("data-acc-rtrct-dv") || "0";
					const clasMin = groupSelectedOption.getAttribute("data-clas-min") || "";
					const lockrSet = groupSelectedOption.getAttribute("data-lockr-set")  || "N";
					const lockr_knd_value = groupSelectedOption.getAttribute("data-lockr-knd") || "";
					const preEnterMin =  groupSelectedOption.getAttribute("data-pre-enter-min")  || "";
					const domcyPossEventYn = groupSelectedOption.getAttribute("data-domcy-poss-event-yn") || "";

					const mCate = iCheck.radioGet("m_cate");
					const eventDesc = selectedOption.getAttribute("data-event-desc") || "";
					let sellEventNm = selectedOption.getAttribute("data-sell-event-nm") || "";
					const domcyCnt = selectedOption.getAttribute("data-domcy-cnt") || "";
					const domcyDay = selectedOption.getAttribute("data-domcy-day") || "";
					const use_prod = selectedOption.getAttribute("data-use-prod") || "";
					const use_unit = selectedOption.getAttribute("data-use-unit") || "M";
					const clas_cnt = selectedOption.getAttribute("data-clas-cnt") || "";
					const sell_event_sno = selectedOption.getAttribute("data-sell-event-sno") || "";
					const sell_s_date = selectedOption.getAttribute("data-sell-s-date") || "";
					const sell_e_date = selectedOption.getAttribute("data-sell-e-date") || "";
					const input_use_per_day = selectedOption.getAttribute("data-use-per-day") || "";
					const input_use_per_week = selectedOption.getAttribute("data-use-per-week") || "";
					const input_use_per_week_unit= selectedOption.getAttribute("data-use-per-week-unit") || "";
					const enter_from_time = selectedOption.getAttribute("data-enter-from-time") || "";
					const enter_to_time = selectedOption.getAttribute("data-enter-to-time") || "";
					const sell_amt = selectedOption.getAttribute("data-sell-amt") || "";
					const sellYn = selectedOption.getAttribute("data-sell-yn") || "N";

					eventSellYn.checked = (sellYn == "Y" || selectedOption.value == "");
					eventSellYnLabel.textContent = eventSellYn.checked ? '(정상판매)' : '(판매중지)';
					
					inputMode.value = "U";
					groupSellYn.removeAttribute("disabled");
					
					eventName.value=sellEventNm;
					use_per_day.value = input_use_per_day || "0";
					use_per_week.value = input_use_per_week || "0";
					use_per_week_unit.value = input_use_per_week_unit || "0";
					if(use_per_week_unit.value != "0")
					{
						use_cnt_per_week_section.style.visibility = "visibile";
					} else
					{
						use_cnt_per_week_section.style.visibility = "hidden";
					}
					useProdInput.value = use_prod;
					useProdUnit.value =  use_unit;
					clasCntInput.value = clas_cnt;
					// p_sell_event_sno.value = sell_event_sno;
					
					prev_event.value = sell_event_sno;
					sellDateDateRange.value = sell_s_date + (sell_s_date != "" || sell_e_date != "" ? " ~ " : "") + sell_e_date;
					// domcy_poss_event_yn.value = domcyPossEventYn;
					// if(domcy_poss_event_yn.value == "Y")
					// {
					// 	locker_none_disp3.style.display="flex";
					// } else
					// {
					// 	locker_none_disp3.style.display="none";
					// }
					domcyDayInput.value = domcyDay;
					domcyCntInput.value = domcyCnt;
					fromTimeInput.value = convertTo12Hour(enter_from_time);
					toTimeInput.value = convertTo12Hour(enter_to_time);
					let value = sell_amt.replace(/[^0-9]/g, '');
					eventPrice.value = Number(value).toLocaleString('ko-KR');

					if(selectedOption.value != "")
					{
						productModeChange("N");
					} else
					{
						productModeChange("I");
					}

					checkSelections();
					loadSoldCnt(p_sell_event_sno.value);
					if(sell_event_sno != "")
						eventSellYn.removeAttribute("disabled");
					else
						eventSellYn.setAttribute("disabled", "true");

					if(selectedOption.value !="")
					{
						let clonedChildren = cloneChildren('acc_item_template');
						const itemCount = pkgAccordion.querySelectorAll('.accordion-item').length;
						const newId = itemCount + 1; // 현재 개수 + 1
						clonedChildren = replaceText(clonedChildren, '[id]', newId.toString());
						clonedChildren = addClass(clonedChildren, 'accordion-item', mCate.toLowerCase()); 
						sellEventNm = (sellEventNm == ""  ? "신규이용권" : sellEventNm);
						clonedChildren = modifyContent(clonedChildren, 'h4 b', sellEventNm);
						let eventDesc = (sDef.M_CATE_PROPERTY[eventType] == "" ? "" :  sDef.M_CATE_PROPERTY[eventType] + " | ");

						
						// if(sellEventNm == "신규이용권")
						// {
						// 	eventDesc += sDef.M_CATE[mCate];
						// } else
						// {
							if(mCate == "PRV")
							{
								eventDesc += sDef.M_CATE[mCate] + " | " +sDef.ACC_RTRCT_DV2[accRtrctDv] + (clasMin != "" ? " | " +  clasMin + "분" : "");
							} else 
							{
								eventDesc += sDef.M_CATE[mCate] + (accRtrctDv != "0" ? " | " + sDef.ACC_RTRCT_DV2[accRtrctDv]  : "") ;
							}
						// }
						
						clonedChildren = modifyContent(clonedChildren, '.accordion-button p', eventDesc);

						let clonedGroup = cloneById('groupTemplate');
						// <div class="accordion-body"> 내부 내용 수정
						// clonedChildren = modifyInnerHTML(clonedChildren, '.accordion-body', '<strong>수정된 바디입니다.</strong> 새로운 내용이 여기에 들어갑니다.');
						
						// emptyElement('pkg_accordion');
						appendElement(clonedChildren, 'pkg_accordion');
						replaceAccordionBodyWithClonedGroup(`collapse${newId}`, clonedGroup);
						pkg_content.style.display="";
						setPkgProductDisp();
						updateAccordionItems();
						let element = pkgAccordion.querySelector("#collapse"+newId.toString());
						let group_selected_value = prev_event_group.value; 
						let prev_selected_value = prev_event.value;
						if(element.id != "groupTemplate")
						{
							setListenerAndVariable(element);
						}
						filterEventSelectOptions();
						prev_event.value = prev_selected_value;
						pkg_prev_event_section.style.display = "";
						let accordionItem = element.closest(".accordion-item");
						let price = accordionItem.querySelector('input[name="event_price"]');
						let num = accordionItem.querySelector(".num");
						num.value = newId.toString();

						let clonedPriceRow = cloneChildren('price_row_template');
						clonedPriceRow = replaceText(clonedPriceRow, '[id]', newId.toString());
						appendElementBeforeLastTr(clonedPriceRow, 'price_body');

						let title_label = document.getElementById("title_label"+newId.toString());
						title_label.innerHTML=sellEventNm;

						let h_m_cate = accordionItem.querySelector("#h_m_cate");
						h_m_cate.value = mCate;
						
						let pkgEventPrice = document.getElementById('pkg_event_price'+newId.toString());
						pkgEventPrice.value = price.value;
						let num2 = getNumOfPrice(pkgEventPrice);
						num2.value = newId.toString();
						pkgEventPriceAddEventListener(pkgEventPrice);
						calculateTotalPrice();
					}
				}
			}

			function groupSelectBoxChange() {
					
				if(element.id == "groupTemplate")
				{
					const selectedOption = groupSelectBox.options[groupSelectBox.selectedIndex];
					const eventType = selectedOption.getAttribute("data-event-type") || "0";
					const accRtrctDv = selectedOption.getAttribute("data-acc-rtrct-dv") || "0";
					const clasMin = selectedOption.getAttribute("data-clas-min") || "";
					const mCate = selectedOption.getAttribute("data-m-cate") || "";
					const eventDesc = selectedOption.getAttribute("data-event-desc") || "";
					const sellEventNm = selectedOption.getAttribute("data-sell-event-nm") || "";
					const lockrSet = selectedOption.getAttribute("data-lockr-set")  || "N";
					const sellYn = selectedOption.getAttribute("data-sell-yn")  || "N";
					const preEnterMin =  selectedOption.getAttribute("data-pre-enter-min")  || "";
					const refCount =  selectedOption.getAttribute("data-ref-count")  || "";
					const domcyPossEventYn = selectedOption.getAttribute("data-domcy-poss-event-yn") || "";
					const lockr_knd_value = selectedOption.getAttribute("data-lockr-knd") || "";
					initMakeProductFields();
					productModeChange("I");
					eventTypeSelect.setAttribute("disabled", "true");
					enterAvailable.setAttribute("disabled", "true");
					domcy_poss_event_yn.setAttribute("disabled", "true");
					lockr_knd.setAttribute("disabled", "true");
					prevEnterMinInput.setAttribute("disabled", "true");
					eventGroupNameInput.setAttribute("disabled", "true");
					clasMinInput.setAttribute("disabled", "true");
					eventGroupDescTextarea.setAttribute("disabled", "true");
					pkg_sell_event_name.removeAttribute("disabled");
					if (groupSelectBox.value !== "" && groupSelectBox.value !== "0" ) {  //기존 이용권
						eventTypeSection.style.display = "flex";
						eventGroupNameSection.style.display = "flex";
						eventGroupDescSection.style.display = "flex";
						saveButtonSection.style.display = "none";
						editButtonSection.style.display = "flex";
						pkg_prev_event_section.style.display = "";
						saveButton.classList.remove("disabled");
						editButton.classList.remove("disabled");
						copyButton.classList.remove("disabled");
						deleteButton.classList.remove("disabled");
						let cnt = parseInt(sold_cnt.textContent);
						if(cnt == 0)
						{
							saveProductButton.classList.remove("disabled");
						}
						inputMode.value = "U";
						groupSellYn.removeAttribute("disabled");

						
						
						eventTypeSelect.value = eventType;
						enterAvailable.value = accRtrctDv;
						eventGroupNameInput.value = sellEventNm;
						eventGroupDescTextarea.value = eventDesc;
						prevEnterMinInput.value = preEnterMin;
						
						domcy_poss_event_yn.value = domcyPossEventYn ;
						if(domcy_poss_event_yn.value == "Y")
						{
							locker_none_disp3.style.display="flex";
						} else
						{
							locker_none_disp3.style.display="none";
						}
						lockr_knd.value = lockr_knd_value;

						clasMinInput.value = clasMin;
						editButton.style.display = "";
						copyButton.style.display = "";
						deleteButton.style.display = "";
						if (mCate === "PRV" || mCate === "GRP") {
							lessonTimeContainer.style.display = "flex";
						} else {
							lessonTimeContainer.style.display = "none";
							clasMinInput.value = "";
						}
						eventGroupNameDisplay.value = sellEventNm;
						const pRefCount = parseInt(selectedOption.getAttribute("data-p-ref-count") || "0", 10);
						if (pRefCount > 0) {
							editButton.style.display="none";
							deleteButton.style.display="none";
						} else {
							editButton.style.display="";
							deleteButton.style.display="";
						}
						if (eventTypeSelect.value != "20") {
							lessionCntSection.style.display = "none";
						} else {
							lessionCntSection.style.display = "";
						}
						//------
						groupSellYn.checked = (sellYn == "Y");
						groupSellYnLabel.textContent = groupSellYn.checked ? '(정상판매)' : '(판매중지)';
					} else {  //신규 이용권
						
						pkg_prev_event_section.style.display = "none";
						eventGroupNameSection.style.display = "none";
						eventGroupNameInput.value = "";
						lessonTimeContainer.style.display = "none";
						clasMinInput.value = "";
						preEnterMin.value = "";
						groupSellYn.checked = true;
						groupSellYnLabel.textContent = '(정상판매)';
						initMakeProductFields();
						eventGroupDescSection.style.display = "none";
						eventGroupDescTextarea.value = "";
						saveButtonSection.style.display = "flex";
						editButtonSection.style.display = "none";
						saveButton.classList.add("disabled");
						editButton.classList.add("disabled");
						copyButton.classList.add("disabled");
						deleteButton.classList.add("disabled");
						initEventGroupFields();
						
						var m_cate = iCheck.radioGet("m_cate");
						if (m_cate !== "OPT") {
							eventTypeSelect.removeAttribute("disabled");
						} else
						{
							eventTypeSelect.setAttribute("disabled", "true");
						}

						if(m_cate != "OPT")
						{
							locker_disp.style.display="none";
							eventTypeSelect.removeAttribute("disabled");
							if(m_cate == "PRV")
							{
								eventTypeSelect.setAttribute("disabled", "true");
								eventTypeSelect.value="20";
							}
						} else
						{
							eventTypeSelect.setAttribute("disabled", "true");
							eventTypeSelect.value="10";
						}
						//------------------

						mCateAllDiv.style.display = "flex";
						eventGroupNameSection.style.display = "none";
						eventGroupNameInput.value = "";
						lessonTimeContainer.style.display = "none";
						clasMinInput.value = "";
						eventGroupDescSection.style.display = "none";
						eventGroupDescTextarea.value = "";


						if(element.id=="groupTemplate")
						{
							// enterAvailable.removeAttribute("disabled");
							// domcy_poss_event_yn.removeAttribute("disabled");
							// lockr_knd.removeAttribute("disabled");
							// prevEnterMinInput.removeAttribute("disabled");
						}
						
						eventGroupNameInput.removeAttribute("disabled");
						clasMinInput.removeAttribute("disabled");
						eventGroupDescTextarea.removeAttribute("disabled");
						


						saveButtonSection.style.display = "flex";
						editButtonSection.style.display = "none";
						cancelButton.style.display = "none";
						saveButton.classList.add("disabled");
						saveProductButton.classList.add("disabled");
						inputMode.value = "I";
						groupSellYn.setAttribute("disabled", true);
				
					}
					enterVisible();
					generateEventName();
					filterEventSelectOptions();
					setPkgProductDisp();
				}
				
			}
			
			function initMakeProductFields() {
				saveProductButton.classList.add("disabled");
				
				useProdInput.value = "";
				autoGenerateNameCheckbox.checked = true;
				useProdInput.value = "";
				eventPrice.value = "";
				clasCntInput.value = "";
				fromTimeInput.value = "";
				toTimeInput.value = "";
				allTimeCheckbox.checked = true;
				all_week.checked = true;
				use_per_week.value = "0";
				use_per_day.value = "0";
				domcyCntInput.value = "";
				domcyDayInput.value = "";
				lockr_number_from.value = "";
				lockr_number_to.value = "";
				uniSexLocker.checked = true;
				manWomanLocker.checked = true;
				sellDateDateRange.value = "";
				use_per_week_unit.value = "0";
				sold_cnt.textContent = "0";
				p_sell_event_sno.value = "";
				eventSellYn.setAttribute("disabled", "true");
			}


			// 필드 초기화 함수
			function initEventGroupFields() {
				
				eventTypeSelect.value = "0";
				
				enterAvailable.value = "0";
				domcy_poss_event_yn.value = "0";
				if(domcy_poss_event_yn.value == "Y")
				{
					locker_none_disp3.style.display="flex";
				} else
				{
					locker_none_disp3.style.display="none";
				}
				eventGroupNameInput.value = "";
				eventGroupDescTextarea.value = "";
				eventGroupNameDisplay.value = "";
				
				eventName.value = "";
				clasMinInput.value = "";
			}

			
			// 이름 자동 생성 함수
			function generateEventName() {
				if (autoGenerateNameCheckbox.checked) {
					eventName.setAttribute("disabled", "true");
					let pName = "";
					let daysSelected = "";
					

					// 선택된 요일 문자열 생성
					dayCheckboxes.forEach(checkbox => {
						if (checkbox.checked) {
							daysSelected += days[checkbox.id];
						}
					});

					// 모든 요일 (월화수목금토일)이 선택된 경우 표시 생략
					if (daysSelected === "월화수목금토일") {
						daysSelected = "";
						all_week.checked = true;
					} else 
					{
						if(daysSelected === "")
						{
							// 선택된 요일 문자열 생성
							dayCheckboxes.forEach(checkbox => {
								checkbox.checked = true;
							});
							daysSelected = "월화수목금토일";
							all_week.checked = true;
						} else
							all_week.checked = false;
					}

						// pName = eventGroupNameDisplay.value
						// 	+ (useProdInput.value !== "" ? " " + useProdInput.value + useProdUnit.selectedOptions[0].text : "")
						// 	+ (fromTimeInput.value !== "" || toTimeInput.value !== "" ? " (이용시간대:" + fromTimeInput.value + " ~ " + toTimeInput.value + ")" : "")
						// 	+ (daysSelected !== "" ? " (" + daysSelected + ")" : "")
						// 	+ (sellDateDateRange.value !== "" ? " (판매기간:" + sellDateDateRange.value + ")" : "");
						pName = eventGroupNameDisplay.value
							+ (clasCntInput.value !=="" ? " " + clasCntInput.value + "회" : "")
							+ (useProdInput.value !== "" ? " " + useProdInput.value + useProdUnit.selectedOptions[0].text : "")
							+ (fromTimeInput.value !== "" || toTimeInput.value !== "" ? " (이용시간대:" + fromTimeInput.value + " ~ " + toTimeInput.value + ")" : "")
							+ (daysSelected !== "" ? " (" + daysSelected + ")" : "");
						eventName.value = pName;
				} else
				{
					eventName.removeAttribute("disabled");
				}
				changeEventName();
			}

			function enterVisible(){
				// [1] enterAvailable 값이 "10"이면 prevEnterSection 노출
				if (enterAvailable.value === "00" || enterAvailable.value === "0") {
					prevEnterSection.style.display = "flex"; // flex로 보이도록
					enterTimeSection.style.display="";
				} else {
					prevEnterSection.style.display = "none"; // 숨김
					enterTimeSection.style.display="none";
				}
			}

			// 락커 표시 제어
			function updateLockerVisibility() {
				const selectedValue = document.querySelector('input[name="m_cate"]:checked')?.value || "";
				lockerContainer.style.display = selectedValue === "OPT" ? "block" : "none";
			}

			// 필터링 함수
			function filterGroupSelectOptions() {
				const selectedCate = document.querySelector('input[name="m_cate"]:checked')?.value || "";
				const options = groupSelectBox.querySelectorAll('option:not([value=""])');
				const lockerValue  = locker_enabled && locker_enabled.checked ? "Y" : "N";
				options.forEach(option => {
					const mCate = option.getAttribute("data-m-cate");
					const lockerSet = option.getAttribute("data-lockr-set") || "N";
					const eventRefSno = option.getAttribute("data-event-ref-sno") || "";
					const sellEventSno = option.getAttribute("data-sell-event-sno") || "";
					const sellYn = option.getAttribute("data-sell-yn") || "";
					option.style.display = (selectedCate && mCate === selectedCate && lockerSet === lockerValue && eventRefSno === sellEventSno && (prev_event_group_status.value == "0" || prev_event_group_status.value == sellYn)) ? "" : "none"; 
				});

				// 필터링 후, 이미 선택된 옵션이 숨겨졌다면 첫 번째 표시되는 옵션으로 선택
				let isSelectedVisible = false;

				options.forEach(option => {
					// 표시되어 있는 옵션 중 첫 번째 옵션을 찾음
					if (option.style.display !== "none") {
						// 현재 selectBox에 선택되어 있는 value와 동일한 옵션이 표시되어 있으면
						if (option.value === groupSelectBox.value) {
							isSelectedVisible = true;
						}
					}
				});

				// 현재 선택된 옵션이 보이지 않는다면, 첫 번째 표시된 옵션으로 선택
				if (!isSelectedVisible ) {
					groupSelectBox.value = "";
				}
			}

			// 필터링 함수
			function filterEventSelectOptions() {
				
				const selectedCate = document.querySelector('input[name="m_cate"]:checked')?.value || "";
				const options = prev_event.querySelectorAll('option:not([value=""])');
				options.forEach(option => {
					const mCate = option.getAttribute("data-m-cate");
					const lockerSet = option.getAttribute("data-lockr-set") || "N";
					const eventRefSno = option.getAttribute("data-event-ref-sno") || "";
					const sellEventSno = option.getAttribute("data-sell-event-sno") || "";
					option.style.display = ( eventRefSno ===  groupSelectBox.value && (sellEventSno !== groupSelectBox.value )|| option.value == "0" ) ? "" : "none"; 
				});

				// 필터링 후, 이미 선택된 옵션이 숨겨졌다면 첫 번째 표시되는 옵션으로 선택
				let isSelectedVisible = false;

				options.forEach(option => {
					// 표시되어 있는 옵션 중 첫 번째 옵션을 찾음
					if (option.style.display !== "none") {
						// 현재 selectBox에 선택되어 있는 value와 동일한 옵션이 표시되어 있으면
						if (option.value === groupSelectBox.value) {
							isSelectedVisible = true;
						}
					}
				});

				// 현재 선택된 옵션이 보이지 않는다면, 첫 번째 표시된 옵션으로 선택
				if (!isSelectedVisible ) {
					prev_event.value = "";
					sold_cnt.value = "0";
				}
			}

			// 초기 상태 설정
			updateLockerVisibility();
			filterGroupSelectOptions();

			// 이벤트 리스너 추가
			

			// 입력 제어
			clasMinInput.addEventListener('input', function (e) {
				this.value = this.value.replace(/[^0-9]/g, '');
				if (this.value.length > 3) {
					this.value = this.value.slice(0, 3);
				}
			});
			if(element.id == "groupTemplate")
			{
				domcy_poss_event_yn.addEventListener("change", function(e){
					if(this.value == "Y")
					{
						locker_none_disp3.style.display="flex";
					} else
					{
						locker_none_disp3.style.display="none";
					}
				});
				prevStudyMinInput.addEventListener('input', function (e) {
					this.value = this.value.replace(/[^0-9]/g, '');
					if (this.value.length > 3) {
						this.value = this.value.slice(0, 3);
					}
				});

				prevEnterMinInput.addEventListener('input', function (e) {
					this.value = this.value.replace(/[^0-9]/g, '');
					if (this.value.length > 3) {
						this.value = this.value.slice(0, 3);
					}
				});
			}
			clasCntInput.addEventListener('input', function (e) {
				this.value = this.value.replace(/[^0-9]/g, '');
				if (this.value.length > 3) {
					this.value = this.value.slice(0, 3);
				}
			});

			

			domcyCntInput.addEventListener('input', function (e) {
				this.value = this.value.replace(/[^0-9]/g, '');
				if (this.value.length > 3) {
					this.value = this.value.slice(0, 3);
				}
			});

			domcyDayInput.addEventListener('input', function (e) {
				this.value = this.value.replace(/[^0-9]/g, '');
				if (this.value.length > 3) {
					this.value = this.value.slice(0, 3);
				}
			});

			useProdInput.addEventListener('input', function (e) {
				this.value = this.value.replace(/[^0-9]/g, '');
				if (this.value.length > 3) {
					this.value = this.value.slice(0, 3);
				}
				generateEventName();
			});

			use_per_week.addEventListener('input', function (e) {
				this.value = this.value.replace(/[^0-9]/g, '');
				if (this.value.length > 3) {
					this.value = this.value.slice(0, 3);
				}
			});

			

			$(fromTimeInput).timepicker({
				showMeridian: true,
				minuteStep: 5,
				defaultTime: false,
				icons: {
					up: 'fa fa-chevron-up',
					down: 'fa fa-chevron-down'
				}
			}).on('show.timepicker', function(e) {
				// toTime 값 확인
				const toTimeVal = $(toTimeInput).val();
				if (!toTimeVal) {
					const fromTimeVal = $(fromTimeInput).val();
					if(!fromTimeVal){
						// toTime이 없으면 기본값 (빈 값) 유지
						$(this).timepicker('setTime', ''); // 초기값 없음
					} 
				} else {
					// toTime이 있으면 1시간 전으로 설정
					const toMinutes = parseTimeToMinutes(toTimeVal);
					const fromMinutes = toMinutes - 60; // 1시간 전
					const fromTimeStr = minutesToTimeString(fromMinutes);
					$(this).timepicker('setTime', fromTimeStr);
				}
			}).on('changeTime.timepicker', function(e) {
				let time = $(this).val();
				$(this).val(time.replace('AM', '오전').replace('PM', '오후'));
				generateEventName();
			});

			$(toTimeInput).timepicker({
				showMeridian: true,
				minuteStep: 5,
				defaultTime: false,
				icons: {
					up: 'fa fa-chevron-up',
					down: 'fa fa-chevron-down'
				}
			}).on('show.timepicker', function(e) {
				// fromTime 값 확인
				const fromTimeVal = $(fromTimeInput).val();
				if (!fromTimeVal) {
					const toTimeVal = $(toTimeInput).val();
					if(!toTimeVal)
					{
						// fromTime이 없으면 기본값 (빈 값) 유지
						$(this).timepicker('setTime', '');
					}
					
				} else {
					// fromTime이 있으면 1시간 후로 설정
					const fromMinutes = parseTimeToMinutes(fromTimeVal);
					const toMinutes = fromMinutes + 60; // 1시간 후
					const toTimeStr = minutesToTimeString(toMinutes);
					$(this).timepicker('setTime', toTimeStr);
				}
			}).on('changeTime.timepicker', function(e) {
				let time = $(this).val();
				$(this).val(time.replace('AM', '오전').replace('PM', '오후'));
				generateEventName();
			});
			$(sellDateDateRange).daterangepicker({
				"locale": {
					"format": "YYYY-MM-DD",
					"separator": " ~ ",
					"applyLabel": "확인",
					"cancelLabel": "취소",
					"fromLabel": "From",
					"toLabel": "To",
					"customRangeLabel": "Custom",
					"weekLabel": "W",
					"daysOfWeek": ["일", "월", "화", "수", "목", "금", "토"],
					"monthNames": ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
				},
				"startDate": new Date(),
				"endDate": new Date(),
				"drops": "auto"
			}, function (start, end, label) {
				sellDateDateRange.querySelector('input').value = start.format('YYYY-MM-DD') + ' ~ ' + end.format('YYYY-MM-DD');
				generateEventName();
			});

			function lockerCheckVisible()
			{
				if(element.id == "groupTemplate")
				{
					if(locker_enabled.checked)
					{
						enterTimeSection.style.display="none";
						locker_none_disp1.style.display="none";
						locker_none_disp2.style.display="none";
						locker_none_disp3.style.display="none";
						locker_disp.style.display="flex";
						locker_select.style.display="";
						checkAllWeek();
						fromTimeInput.value = "";
						toTimeInput.value = "";
						toTimeInput.disabled = true;
						fromTimeInput.disabled = true;
						allTimeCheckbox.checked = true;
						use_per_day.value = "0";
						select_use_per_week_unit.value = "0";
						use_per_week.value = "";
						domcyCntInput.value = "";
						domcyDayInput.value = "";
					} else {
						enterTimeSection.style.display="";
						locker_none_disp1.style.display="flex";
						locker_none_disp2.style.display="flex";
						locker_none_disp3.style.display="flex";
						locker_disp.style.display="none";
						locker_select.style.display="none";
					}	
					if(iCheck.radioGet("m_cate") != "OPT")
					{
						enterTimeSection.style.display="";
						locker_none_disp1.style.display="flex";
						locker_none_disp2.style.display="flex";
						locker_none_disp3.style.display="flex";
						locker_disp.style.display="none";
						locker_select.style.display="none";
					} 
				}
				
			}
			
			function parseTimeToMinutes(timeStr) {
				const [time, period] = timeStr.split(' ');
				const [hours, minutes] = time.split(':').map(Number);
				let totalMinutes = (hours % 12) * 60 + minutes;
				if (period === '오후') totalMinutes += 12 * 60;
				return totalMinutes;
			}

			// 분 단위 시간을 문자열로 변환
			function minutesToTimeString(minutes) {
				const hours = Math.floor(minutes / 60) % 24;
				const mins = minutes % 60;
				const period = hours >= 12 ? 'PM' : 'AM';
				const displayHours = hours % 12 || 12; // 0시 → 12시
				return `${displayHours}:${mins < 10 ? '0' + mins : mins} ${period}`;
			}

			// 시간 문자열을 분 단위로 변환 (기존 함수)
			function parseTimeToMinutes(timeStr) {
				const [time, period] = timeStr.split(' ');
				const [hours, minutes] = time.split(':').map(Number);
				let totalMinutes = (hours % 12) * 60 + minutes;
				if (period === '오후' || period === 'PM') totalMinutes += 12 * 60;
				return totalMinutes;
			}

			
			

			// Group 저장시 발리데이션
			function groupSaveIsValid()
			{

				if (eventTypeSection.style.display !== "none") {
					if (!eventTypeSelect.value || eventTypeSelect.value === "0") {
						alertToast('error',"기간제/횟수제를 선택하세요.");
						eventTypeSelect.focus();
						return false;
					}
					if (!enterAvailable.value || enterAvailable.value === "0") {
						alertToast('error',"입장 가능여부를 선택하세요.");
						enterAvailable.focus();
						return false;
					}
					if (!domcy_poss_event_yn.value || domcy_poss_event_yn.value === "0") {
						alertToast('error',"휴회 가능여부를 선택하세요.");
						domcy_poss_event_yn.focus();
						return false;
					}
					if(locker_select.style.display !== "none")
					{
						if (!lockr_knd.value || lockr_knd.value === "0") {
							alertToast('error',"락커구역을 선택하세요.");
							lockr_knd.focus();
							return false;
						}
					}
					
				}

				// 2) eventGroupNameInput 의 값이 없는 경우 return false
				if (!eventGroupNameInput.value.trim()) {
					alertToast('error',"이용권 그룹명을 입력하세요.");
					eventGroupNameInput.focus();
					return false;
				}

				// 3) lessonTimeContainer 이 보여질 때(lessonTimeContainer.style.display !== "none") 
				//    → clasMinInput 의 값이 없는 경우 return false
				if (lessonTimeContainer.style.display !== "none") {
					if (!clasMinInput.value.trim()) {
						alertToast('error',"레슨시간(분)을 입력하세요.");
						clasMinInput.focus();
						return false;
					}
				}

				if(locker_select.style.display !== "none")
				{
					if(!locker_select.value == "0")
					{
						alertToast('error',"락커 구역을 선택하세요.");
						locker_select.focus();
						return false;
					}
				}

				// 모든 검사 통과 시 true
				return true;
			}

			

			function setGroupSelectBox(json_result) {
				var groupSelected =  json_result["sell_event_sno"];
				if(groupSelected == "" || groupSelected == "null"  ){
					groupSelected =  groupSelectBox.value;
				}
				const eventList = json_result['event_list'];

				groupSelectBox.innerHTML = '';

				const defaultOption = document.createElement('option');
				defaultOption.value = '';
				defaultOption.textContent = '신규생성';
				groupSelectBox.appendChild(defaultOption);

				eventList.forEach((r) => {
					const option = document.createElement('option');
					option.value = r.SELL_EVENT_SNO || '';

					option.setAttribute('data-acc-rtrct-dv', r.ACC_RTRCT_DV ?? '');
					option.setAttribute('data-event-type', r.EVENT_TYPE ?? '');
					option.setAttribute('data-clas-min', r.CLAS_MIN ?? '');
					option.setAttribute('data-m-cate', r.M_CATE ?? '');
					option.setAttribute('data-event-desc', r.EVENT_DESC ?? '');
					option.setAttribute('data-sell-event-nm', r.SELL_EVENT_NM ?? '');
					option.setAttribute('data-ref-count', r.ref_count ?? '');
					option.setAttribute('data-p-ref-count', r.p_ref_count ?? '');
					option.setAttribute('data-lockr-set', r.LOCKR_SET ?? '');
					option.setAttribute('data-lockr-knd', r.LOCKR_KND ?? '');
					option.setAttribute('data-pre-enter-min', r.PRE_ENTER_MIN ?? '');
					option.setAttribute('data-event-ref-sno', r.EVENT_REF_SNO ?? '');
					option.setAttribute('data-sell-event-sno', r.SELL_EVENT_SNO ?? '');
					option.setAttribute('data-domcy-poss-event-yn', r.DOMCY_POSS_EVENT_YN ?? '');
					option.setAttribute('data-sell-yn', r.SELL_YN ?? '');

					const accRtrctDvText = (r.ACC_RTRCT_DV && sDef.ACC_RTRCT_DV2 && sDef.ACC_RTRCT_DV2[r.ACC_RTRCT_DV])
						? sDef.ACC_RTRCT_DV2[r.ACC_RTRCT_DV]
						: '알 수 없음';
					const mCatePropertyText = (r.EVENT_TYPE && sDef.M_CATE_PROPERTY && sDef.M_CATE_PROPERTY[r.EVENT_TYPE])
						? sDef.M_CATE_PROPERTY[r.EVENT_TYPE]
						: '알 수 없음';
					const refCount = (r.ref_count && !isNaN(r.ref_count)) ? parseInt(r.ref_count) : 0;

					let optionText = `[ ${r.SELL_EVENT_NM || ''} ] ${mCatePropertyText}, ${accRtrctDvText}`;
					if (refCount > 0) {
						optionText += `, 등록상품수: ${refCount}`;
					}
					if (r.SELL_YN !== 'Y') {
						optionText += ' (판매중지)';
					}
					option.textContent = optionText;

					groupSelectBox.appendChild(option);
				});

				filterGroupSelectOptions();
				groupSelectBox.value = groupSelected;
			}

			// 이용권 그룹을 저장
			function fnGroupSave() {
				if(!groupSaveIsValid())
				{
					return
				}
				var m_cate = iCheck.radioGet("m_cate");
				var tblParam = {};
				tblParam.m_cate = m_cate;
				tblParam.type = inputMode.value;
				if(inputMode.value == "U")
				{
					tblParam.sell_event_sno = groupSelectBox.value;
				}
				tblParam.lockr_set = (locker_enabled.checked ? "Y" : "N");
				tblParam.event_type = eventTypeSelect.value;
				tblParam.acc_rtrct_dv = enterAvailable.value;
				tblParam.domcy_poss_event_yn = domcy_poss_event_yn.value;
				tblParam.lockr_knd = lockr_knd.value;
				tblParam.pre_enter_min = prevEnterMinInput.value;
				tblParam.sell_event_nm = eventGroupNameInput.value;
				tblParam.event_desc = eventGroupDescTextarea.value;
				tblParam.clas_min = clasMinInput.value.trim();
				tblParam.sell_yn = 'Y';
				jQuery.ajax({
					url: '/teventmain/event_group_save',
					type: 'POST',
					data: tblParam,
					contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
					dataType: 'text',
					success: function (result) {
						if (result.substr(0, 8) == '<script>') {
							alertToast('error','로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
							location.href = '/tlogin';
							return;
						}
						json_result = $.parseJSON(result);
						if (json_result['result'] == 'true') {
							alertToast('success','이용권그룹이 성공적으로 저장되었습니다.');
							setGroupSelectBox(json_result);
							mCateAllDiv.style.display = "";
							editButton.classList.remove("disabled");
							copyButton.classList.remove("disabled");
							deleteButton.classList.remove("disabled");
							// cancelClick();
						}
					}
				}).done((res) => {
					console.log('통신성공');
				}).fail((error) => {
					console.log('통신실패');
					alertToast('error','오류가 발생하였습니다.');
					return;
				});
			}

				
			

			deleteButton.addEventListener("click", fnDeleteGroup);



			function setEventSelectBox(json_result)
			{
				const prevSelected=json_result['sell_event_sno'];
				if(prevSelected == "" || prevSelected == "null"  ){
					prevSelected=prev_event.value;
				}
				prev_event.innerHTML = ''; // 기존 옵션 제거

				// 기본 옵션 추가: "신규생성"
				const defaultOption = document.createElement('option');
				defaultOption.value = '';
				defaultOption.textContent = '신규생성';
				prev_event.appendChild(defaultOption);
				const eventList = json_result['event_list'];
				// eventList 데이터로 옵션 동적 생성
				eventList.forEach((r) => {
					const option = document.createElement('option');

					// option의 value 설정
					option.value = r.SELL_EVENT_SNO || '';

					// data-* 속성 설정 (null/undefined 처리를 위해 ?? 연산자 사용)
					option.setAttribute('data-acc-rtrct-dv', r.ACC_RTRCT_DV ?? '');
					option.setAttribute('data-event-type', r.EVENT_TYPE ?? '');
					option.setAttribute('data-clas-min', r.CLAS_MIN ?? '');
					option.setAttribute('data-m-cate', r.M_CATE ?? '');
					option.setAttribute('data-event-desc', r.EVENT_DESC ?? '');
					option.setAttribute('data-sell-event-nm', r.SELL_EVENT_NM ?? '');
					option.setAttribute('data-ref-count', r.ref_count ?? ''); // ref_count로 표기
					option.setAttribute('data-p-ref-count', r.p_ref_count ?? ''); // p_ref_count로 표기
					option.setAttribute('data-lockr-set', r.LOCKR_SET ?? '');
					option.setAttribute('data-pre-enter-min', r.PRE_ENTER_MIN ?? '');
					option.setAttribute('data-event-ref-sno', r.EVENT_REF_SNO ?? '');
					option.setAttribute('data-sell-event-sno', r.SELL_EVENT_SNO ?? '');
					option.setAttribute('data-domcy-poss-event-yn', r.DOMCY_POSS_EVENT_YN ?? '');
					option.setAttribute('data-lockr-knd', r.LOCKR_KND ?? '');
					option.setAttribute('data-use-prod', r.USE_PROD ?? '');
					option.setAttribute('data-use-unit', r.USE_UNIT ?? '');
					option.setAttribute('data-clas-cnt', r.CLAS_CNT ?? '');
					option.setAttribute('data-sell-s-date', r.SELL_S_DATE ?? '');
					option.setAttribute('data-sell-e-date', r.SELL_E_DATE ?? '');
					option.setAttribute('data-domcy-cnt', r.DOMCY_CNT ?? '');
					option.setAttribute('data-domcy-day', r.DOMCY_DAY ?? '');
					option.setAttribute('data-use-per-day', r.USE_PER_DAY ?? '');
					option.setAttribute('data-use-per-week', r.USE_PER_WEEK ?? '');
					option.setAttribute('data-use-per-week-unit', r.USE_PER_WEEK_UNIT ?? '');
					option.setAttribute('data-enter-from-time', r.ENTER_FROM_TIME ?? '');
					option.setAttribute('data-enter-to-time', r.ENTER_TO_TIME ?? '');
					option.setAttribute('data-sell-amt', r.SELL_AMT ?? '');
					option.setAttribute('data-sell-yn', r.SELL_YN ?? '');
					// 표시 텍스트 설정
					option.textContent = (r.SELL_EVENT_NM + (r.SELL_YN == 'N' ? " (판매중지)" : "") || '') + " " + (r["SELL_AMT"] != null ? Number(r["SELL_AMT"]).toLocaleString() : '0') + '원';

					// select에 option 추가
					prev_event.appendChild(option);
				});
				filterEventSelectOptions();
				prev_event.value = prevSelected;
				p_sell_event_sno.value = json_result['sell_event_sno'];
				eventSellYn.removeAttribute("disabled");
			}
			

			function fnDeleteGroup()
			{
				ToastConfirm.fire({
					icon: "question",
					title: "  확인 메세지",
					html: "<font color='#000000' >정말로 삭제하시겠습니까?</font>",
					showConfirmButton: true,
					showCancelButton: true,
					confirmButtonColor: "#28a745",
				}).then((result) => {
					if (result.isConfirmed) 
					{
						var param = {};
						param.sell_event_sno = groupSelectBox.value;
						jQuery.ajax({
							url: '/teventmain/event_group_delete',
							type: 'POST',
							data:param,
							contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
							dataType: 'text',
							success: function (result) {
								if ( result.substr(0,8) == '<script>' )
								{
									alert('오류가 발생하였습니다.');
								}
								
								json_result = $.parseJSON(result);
								if (json_result['result'] == 'true')
								{
									groupSelectBox.remove(groupSelectBox.selectedIndex);
									groupSelectBox.value = "";
									groupSelectBoxChange();
									alertToast('success','이용권 그룹이 성공적으로 삭제되었습니다.');
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
				});
			}

			function fnDeleteEvent()
			{
				ToastConfirm.fire({
					icon: "question",
					title: "  확인 메세지",
					html: "<font color='#000000' >정말로 삭제하시겠습니까?</font>",
					showConfirmButton: true,
					showCancelButton: true,
					confirmButtonColor: "#28a745",
				}).then((result) => {
					if (result.isConfirmed) 
					{
						var param = {};
						param.sell_event_sno = p_sell_event_sno.value;

						const selectedProductOption = prev_event.options[prev_event.selectedIndex];
						param.event_ref_sno = selectedProductOption.getAttribute("data-event-ref-sno") ;
						jQuery.ajax({
							url: '/teventmain/event_delete',
							type: 'POST',
							data:param,
							contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
							dataType: 'text',
							success: function (result) {
								if ( result.substr(0,8) == '<script>' )
								{
									alert('오류가 발생하였습니다.');
								}
								
								json_result = $.parseJSON(result);
								if (json_result['result'] == 'true')
								{
									// 새로 그릴 event_list 데이터
									setGroupSelectBox(json_result);
									groupSelectBox.value =param.event_ref_sno; 
									groupSelectBoxChange();
									setEventSelectBox(json_result);
									
									prev_event.remove(prev_event.selectedIndex);
									prev_event.value = "";
									sold_cnt.value = "0";
									eventSelectBoxChange();
									p_sell_event_sno.value = "";
									eventSellYn.setAttribute("disabled", "true");
									eventName.value = eventGroupNameInput.value;
									alertToast('success','이용권이 성공적으로 삭제되었습니다.');
									
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
				});
			}

			function fnChangeSellYn(category)
			{
				eventSellYn.setAttribute("disabled", "true");
				groupSellYn.setAttribute("disabled", "true");
				var param = {};
				param.category = category;
				if(category == "group")   //group
				{
					param.sell_yn = (groupSellYn.checked ? "Y" : "N");
					param.sell_event_sno = groupSelectBox.value;
				} else					  //event
				{
					param.sell_yn = (eventSellYn.checked ? "Y" : "N");
					param.sell_event_sno = prev_event.value;
				}

				jQuery.ajax({
					url: '/teventmain/change_sell_yn',
					type: 'POST',
					data:param,
					contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
					dataType: 'text',
					success: function (result) {
						if ( result.substr(0,8) == '<script>' )
						{
							alert('오류가 발생하였습니다.');
						}
						
						json_result = $.parseJSON(result);
						if (json_result['result'] == 'true')
						{
							if(category=="group")
							{
								// 새로 그릴 event_list 데이터
								setGroupSelectBox(json_result);
								groupSelectBox.value = param.sell_event_sno; 
							} else
							{
								setEventSelectBox(json_result);
								prev_event.value = param.sell_event_sno;
							}
						} else
						{
							alertToast('error',json_result["msg"]);
							groupSellYn.checked = true;
						}
						groupSellYn.removeAttribute("disabled")
						eventSellYn.removeAttribute("disabled");
					}
				}).done((res) => {
					// 통신 성공시
					console.log('통신성공');
				}).fail((error) => {
					// 통신 실패시
					console.log('통신실패');
					eventSellYn.removeAttribute("disabled");
					alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
					location.href='/tlogin';
					return;
				});
			}

			function setWeekDaysFromItemObject(itemObject) {
				// 요일 매핑 객체
				const dayMap = {
					'월': 'mon',
					'화': 'tue',
					'수': 'wed',
					'목': 'thu',
					'금': 'fri',
					'토': 'sat',
					'일': 'sun'
				};

				// WEEK_SELECT 값 가져오기 (예: "월수금")
				const weekSelect = itemObject["WEEK_SELECT"] || '월화수목금토일';

				// 모든 체크박스 기본적으로 체크 해제
				dayCheckboxes.forEach(checkbox => {
					checkbox.checked = false;
				});
				allWeekCheckbox.checked = false;

				// "월수금" 문자열에서 각 요일 체크
				if (weekSelect) {
					const selectedDays = weekSelect.split(''); // ['월', '수', '금']
					selectedDays.forEach(day => {
						const checkboxId = dayMap[day];
						if (checkboxId) {
							document.getElementById(checkboxId).checked = true;
						}
					});

					// 모든 요일이 선택되었는지 확인 (월화수목금토일)
					const allDays = '월화수목금토일';
					const isAllSelected = allDays.split('').every(day => weekSelect.includes(day));
					allWeekCheckbox.checked = isAllSelected;
				}
			}
			
			filterPkgEventSelectOptions();

			// setProduct();
			
			
			if(!isReloaded)
			{
				const pkgItemObject = <?= json_encode($pkgItemObject, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
				const pkgGroupObject = <?= json_encode($pkgGroupObject, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
				const productList = <?= json_encode($itemList, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
				setPackage(pkgGroupObject, pkgItemObject);
				// setPackageProduct(productList);
			}

			function setPackage(pkgGroupObject, pkgItemObject)
			{
				if(pkgGroupObject['SELL_EVENT_SNO'])
				{
					pkg_group_event_select.value = pkgGroupObject['SELL_EVENT_SNO'];
					pkg_group_event_name.value = pkgGroupObject['SELL_EVENT_NM'];

					
					pkg_groupSellYnSection.style.display="";
					
					filterPkgEventSelectOptions();
					let sell_s_date = pkgItemObject['SELL_S_DATE'];
					let sell_e_date = pkgItemObject['SELL_E_DATE'];
					let dateRange = document.querySelector('input[name="pkg-sell-date-daterange"]');
					dateRange.value = sell_s_date + (sell_s_date != "" || sell_e_date != "" ? " ~ " : "") + sell_e_date;

					pkg_sell_event_select.value = pkgItemObject['SELL_EVENT_SNO'];
					pkg_sell_event_name.value = pkgItemObject['SELL_EVENT_NM'];

					let selectedOption = pkg_sell_event_select.options[pkg_sell_event_select.selectedIndex];
					let sellEventNm = selectedOption.getAttribute("data-sell-event-nm") || "";
					let sellYn = selectedOption.getAttribute("data-sell-yn")  || "N";
					if(pkg_groupSellYn.checked || selectedOption.value != "")
					{
						removeAllItems();
						
						pkg_sell_event_name.value = sellEventNm;
						pkg_eventSellYn.checked = (sellYn == "Y");
						pkg_eventSellYnLabel.textContent = sellYn == "Y" ? '(정상판매)' : '(판매중지)';
						if(this.value == "")
						{
							pkg_sell_event_name.removeAttribute("disabled");
							pkg_prev_event_section.style.display="";
							pkg_cate_section.style.display="";
							pkg_eventSellYnSection.style.display="none";
							// pkg_groupSellYnSection.style.display="none";
							// pkg_prev_event_section.style.display="";

						} else {
							
							pkg_sell_event_name.setAttribute("disabled", "true");
							pkg_prev_event_section.style.display="none";
							pkg_cate_section.style.display="none";
							pkg_eventSellYnSection.style.display="";
							// pkg_groupSellYnSection.style.display="";
							// pkg_prev_event_section.style.display="none";
						} 
						
						isReloaded = true;
						load_package_items();
					} else
					{
						alertToast('error',"판매중지된 이용권 그룹의 이용권은 신규생성할수 없습니다.");
					}
					
				}
			}

			
			function productModeChange(mode)
			{
				if(mode=="E" || mode=="I")  //Normal Mode
				{
					useProdInput.removeAttribute("disabled");
					useProdUnit.removeAttribute("disabled");
					eventPrice.removeAttribute("disabled");
					clasCntInput.removeAttribute("disabled");
					
					autoGenerateNameCheckbox.removeAttribute("disabled");
					
					allTimeCheckbox.removeAttribute("disabled");
					if(!allTimeCheckbox.checked)
					{
						fromTimeInput.removeAttribute("disabled");
						toTimeInput.removeAttribute("disabled");
					}
					dayCheckboxes.forEach(checkbox => {
						checkbox.removeAttribute("disabled");
					});
					all_week.removeAttribute("disabled");
					select_use_per_week_unit.removeAttribute("disabled");
					use_per_day.removeAttribute("disabled");
					domcyDayInput.removeAttribute("disabled");
					domcyCntInput.removeAttribute("disabled");
					sellDateDateRange.removeAttribute("disabled");
					if(!all_locker.checked)
					{
						lockr_number_from.removeAttribute("disabled");
						lockr_number_to.removeAttribute("disabled");
					}
					if(locker_number_type)
					locker_number_type.removeAttribute("disabled");
					uniSexLocker.removeAttribute("disabled");
					manWomanLocker.removeAttribute("disabled");
					btn_cal.removeAttribute("disabled");
					saveProductSection.style.display="flex";
					editProductSection.style.display="none";
					
					if(mode == "I")
					{
						cancelProductButton.style.display="none";
					} else
					{
						cancelProductButton.style.display="";
					}
					
				} else if(mode == "N")
				{
					useProdInput.setAttribute("disabled", "true");
					useProdUnit.setAttribute("disabled", "true");
					eventPrice.setAttribute("disabled", "true");
					clasCntInput.setAttribute("disabled", "true");
					fromTimeInput.setAttribute("disabled", "true");
					toTimeInput.setAttribute("disabled", "true");
					allTimeCheckbox.setAttribute("disabled", "true");
					dayCheckboxes.forEach(checkbox => {
						checkbox.setAttribute("disabled", "true");
					});
					all_week.setAttribute("disabled", "true");
					select_use_per_week_unit.setAttribute("disabled", "true");
					use_per_day.setAttribute("disabled", "true");
					domcyDayInput.setAttribute("disabled", "true");
					domcyCntInput.setAttribute("disabled", "true");
					sellDateDateRange.setAttribute("disabled", "true");
					lockr_number_from.setAttribute("disabled", "true");
					lockr_number_to.setAttribute("disabled", "true");
					if(locker_number_type)
					locker_number_type.setAttribute("disabled", "true");
					uniSexLocker.setAttribute("disabled", "true");
					manWomanLocker.setAttribute("disabled", "true");
					btn_cal.setAttribute("disabled", "true");
					saveProductSection.style.display="none";
					editProductSection.style.display="flex";
					autoGenerateNameCheckbox.setAttribute("disabled", "true");
					autoGenerateNameCheckbox.checked = true;
					eventName.setAttribute("disabled", "true");
				}
			}

			function changeEventName(){
				const accordionItem = element.closest('.accordion-item');
				if(accordionItem)
				{
					let title_label = document.getElementById(element.id.replace("collapse", "title_label"));
					let bElement = accordionItem.querySelector("h4 b");
					if(eventName.value != "")
					{
						bElement.textContent = eventName.value;
						title_label.textContent = eventName.value;
					}
				}
				
			}
			

			
		}
		
		// 이용권 상품을 저장
		function fnEventSave() {
			if(!groupSaveIsValid())
			{
				return;
			}

			if(!productSaveIsValid())
			{
				return
			}
			var m_cate = iCheck.radioGet("m_cate");
			var tblParam = {};
			tblParam.m_cate = m_cate;
			let mode = "I";
			if(p_sell_event_sno.value != "")
			{
				mode = "U";
				tblParam.sell_event_sno = p_sell_event_sno.value;
			}
			tblParam.type = mode;
			tblParam.event_ref_sno = groupSelectBox.value;
			if(lockerContainer.style.display != "none")
			{
				
				if(locker_enabled.checked == true)
				{
					tblParam.lockr_set = 'Y';
				} else
				{
					tblParam.lockr_set = 'N';
				}
			} else
			{
				tblParam.lockr_set = 'N';
			}
			let lockr_gendr_set;

			// "혼용"과 "일반" 모두 체크 시 'GFM'
			if (uniSexLocker.checked && manWomanLocker.checked) {
				lockr_gendr_set = 'GFM';
			}
			// "일반"만 체크 시 'FM'
			else if (!uniSexLocker.checked && manWomanLocker.checked) {
				lockr_gendr_set = 'FM';
			}
			// "혼용"만 체크 시 'G'
			else if (uniSexLocker.checked && !manWomanLocker.checked) {
				lockr_gendr_set = 'G';
			}
			// 둘 다 체크 해제 시 기본값 (필요 시 조정 가능)
			else {
				lockr_gendr_set = ''; // 또는 다른 기본값 설정 가능
			}
			
			tblParam.event_type = eventTypeSelect.value;
			tblParam.acc_rtrct_dv = enterAvailable.value;
			tblParam.pre_enter_min = prevEnterMinInput.value;
			if (lessonTimeContainer.style.display !== "none") {
				tblParam.clas_min = clasMinInput.value.trim();
			}
			tblParam.clas_cnt = clasCntInput.value;
			tblParam.sell_yn = 'Y';
			tblParam.sell_event_nm = eventName.value;
			tblParam.use_prod = useProdInput.value;
			tblParam.use_unit = useProdUnit.value;
			tblParam.sell_amt = removeCommas(eventPrice.value);
			tblParam.enter_from_time = convertTo24Hour(fromTime.value);
			tblParam.enter_to_time = convertTo24Hour(toTime.value);
			tblParam.lockr_gendr_set = lockr_gendr_set;
			tblParam.lockr_number_from	= lockr_number_from.value;
			tblParam.lockr_number_to	= lockr_number_to.value;
			tblParam.lockr_knd = lockr_knd.value;
			let daysSelected = "";
			// 선택된 요일 문자열 생성
			dayCheckboxes.forEach(checkbox => {
				if (checkbox.checked) {
					daysSelected += days[checkbox.id];
				}
			});
			tblParam.week_select = daysSelected;
			tblParam.use_per_day = use_per_day.value;
			tblParam.use_per_week_unit = select_use_per_week_unit.value;
			tblParam.use_per_week = use_per_week.value;
			tblParam.domcy_day = domcyDayInput.value;
			tblParam.domcy_cnt = domcyCntInput.value;

			tblParam.domcy_poss_event_yn = domcy_poss_event_yn.value;
			tblParam.event_desc = "";
			// " ~ " 기준으로 분리
			var [startDate, endDate] = sellDateDateRange.value.split(" ~ ");

			// 원하는 객체나 변수에 각각 저장
			tblParam.sell_s_date = (startDate ? startDate:"");
			tblParam.sell_e_date = (endDate ? endDate : "");
			jQuery.ajax({
				url: '/teventmain/event_save',
				type: 'POST',
				data: tblParam,
				contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
				dataType: 'text',
				success: function (result) {
					if (result.substr(0, 8) == '<script>') {
						alertToast('error','로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
						location.href = '/tlogin';
						return;
					}
					json_result = $.parseJSON(result);
					if (json_result['result'] == 'true') {
						if(p_sell_event_sno.value == "")
						{
							setGroupSelectBox(json_result);
							groupSelectBox.value =tblParam.event_ref_sno; 
						}
						setEventSelectBox(json_result);
						
						productModeChange("N");
						alertToast('success','이용권이 성공적으로 저장되었습니다.');
					}
				}
			}).done((res) => {
				console.log('통신성공');
			}).fail((error) => {
				console.log('통신실패');
				alertToast('error','오류가 발생하였습니다.');
			});
		}

		let updateList = [];

		function packageSaveIsValid()
		{
			if(pkg_group_event_select.value == "" && !pkg_group_event_name.value.trim())
			{
				alertToast('error',"팩키지 이용권 그룹명을 입력해주세요.");
				pkg_group_event_name.focus();
				return false;
			}
			
			if(pkg_sell_event_select.value == "" &&!pkg_sell_event_name.value.trim())
			{
				alertToast('error',"팩키지 이용권 상품명을 입력해주세요.");
				pkg_sell_event_name.focus();
				return false;
			}

			

			updateList = [];
			const collapseElements = pkgAccordion.querySelectorAll('[id^="collapse"]');

			for (const element of collapseElements) {
				const eventTypeSection = element.querySelector("#eventTypeSection");
				const eventTypeSelect = element.querySelector("#event_type");           // 기간제/회수제 구분
				const enterAvailable = element.querySelector("#acc_rtrct_dv");          // 입장가능여부
				const prevEnterSection = element.querySelector("#prev_enter_section");  // 입장전 시간 섹션
				const prevEnterMinInput = element.querySelector('input[name="pre_enter_min"]');
				const groupInput = element.querySelector("#group_event_sno");
				// 휴회가능 여부
				const domcy_poss_event_yn = element.querySelector("#domcy_poss_event_yn");

				// 락커 구역 섹션
				const locker_select = element.querySelector("#locker_select");
				const lockr_knd = element.querySelector("#lockr_knd");         // 락커 구역
				const h_m_cate = element.querySelector("#h_m_cate");

				// 락커 번호 유형
				const locker_number_type = element.querySelector('input[name="locker_number_type"]');

				// 레슨시간 섹션
				const lessonTimeContainer = element.querySelector("#lessonTimeContainer");
				const clasMinInput = element.querySelector('input[name="clas_min"]');

				// 이용권 그룹 섹션
				const eventGroupNameSection = element.querySelector("#eventGroupNameSection");
				const eventGroupNameInput = element.querySelector('input[name="event_group_name"]');
				const eventGroupDescTextarea = element.querySelector('textarea[name="event_group_desc"]');
				const groupSellYn = element.querySelector("#groupSellYn");
				const groupSellYnLabel = element.querySelector('label[for="groupSellYn"]');

				// 이용권 그룹 설명 섹션
				const eventGroupDescSection = element.querySelector("#eventGroupDescSection");




				//이용권 섹션
				const eventGroupNameDisplay = element.querySelector('input[name="event_group_name_display"]'); //이용권 그룹명
				const eventName = element.querySelector('input[name="event_name"]');  //중분류 명
				const autoGenerateNameCheckbox = element.querySelector("#autoGenerateName"); // 변수 이름 변경
				
				const eventSellYn = element.querySelector("#eventSellYn");
				const eventSellYnLabel = element.querySelector('label[for="eventSellYn"]');


				//이용기간
				const useProdInput = element.querySelector('input[name="use_prod"]');
				const useProdUnit = element.querySelector('select[name="use_prod_unit"]'); // 개월, 일 단위

				

				//이용권 가격
				const eventPrice = element.querySelector('input[name="event_price"]');


				//이용 횟수
				const lessionCntSection = element.querySelector("#lesson-cnt-section");
				const clasCntInput = element.querySelector('input[name="clas_cnt"]');

				//이용 시간대
				const fromTimeInput = element.querySelector("#fromTime");
				const toTimeInput = element.querySelector("#toTime");
				const allTimeCheckbox = element.querySelector("#all_time");

				
				//한주 이용 가능 횟수 섹션
				const use_cnt_per_week_section = element.querySelector("#use_cnt_per_week_section");
				const use_per_week = element.querySelector('input[name="use_per_week"]');
				const select_use_per_week_unit = element.querySelector("#use_per_week_unit");
				const unitLabel = use_cnt_per_week_section.querySelector('.unit-label');

				const locker_none_disp1 = element.querySelector("#locker_none_disp1");  //요일 섹션
				const locker_none_disp2 = element.querySelector("#locker_none_disp2");  //일일 이용가능 횟수 섹션
				const locker_none_disp3 = element.querySelector("#locker_none_disp3");  //휴회 섹션
				const all_locker = element.querySelector("#all_locker");

				//요일 선택 섹션
				const all_week = element.querySelector("#all_week");
				const dayCheckboxes = element.querySelectorAll('.day-checkbox');

				//일일 이용 가능횟수
				const use_per_day = element.querySelector("#use_per_day");

				//휴회
				const domcyCntInput = element.querySelector('input[name="domcy_cnt"]');
				const domcyDayInput = element.querySelector('input[name="domcy_day"]');

				//락커
				const locker_disp = element.querySelector("#locker_disp");  //락커섹션
				//락커 번호대
				const lockr_number_from = element.querySelector('input[name="lockr_number_from"]');
				const lockr_number_to = element.querySelector('input[name="lockr_number_to"]');
				// 락커 체크박스 요소 가져오기
				const uniSexLocker = document.getElementById('uni_sex_locker');
				const manWomanLocker = document.getElementById('man_woman_locker');
				
				//판매기간
				const sellDateDateRange = element.querySelector('input[name="sell-date-daterange"]');
				const btn_cal = element.querySelector("#btn_cal");


				//판매 이용권 번호
				const p_sell_event_sno = element.querySelector("#p_sell_event_sno");
				//판매수
				const sold_cnt = element.querySelector("#sold_cnt");

				// 사용 안하는 파트
				const classTimeCheckSection = element.querySelector("#class_time_check_section");
				const classTimeSection = element.querySelector("#class_time_section");
				const enterTimeSection = element.querySelector("#enter_time_section");
				const prevStudyMinInput = element.querySelector('input[name="prev_study_min"]');
				const locker_enabled_input = element.querySelector('#locker_enabled');
				

				if (eventTypeSection.style.display !== "none") {
					if (!eventTypeSelect.value || eventTypeSelect.value === "0") {
						alertToast('error',"기간제/횟수제를 선택하세요.");
						eventTypeSelect.focus();
						return false;
					}
					if (!enterAvailable.value || enterAvailable.value === "0") {
						alertToast('error',"입장 가능여부를 선택하세요.");
						enterAvailable.focus();
						return false;
					}
					if (!domcy_poss_event_yn.value || domcy_poss_event_yn.value === "0") {
						alertToast('error',"휴회 가능여부를 선택하세요.");
						domcy_poss_event_yn.focus();
						return false;
					}
					if(locker_select.style.display !== "none")
					{
						if (!lockr_knd.value || lockr_knd.value === "0") {
							alertToast('error',"락커구역을 선택하세요.");
							lockr_knd.focus();
							return false;
						}
					}
					
				}

				// // 2) eventGroupNameInput 의 값이 없는 경우 return false
				// if (!eventGroupNameInput.value.trim()) {
				// 	alertToast('error',"이용권 그룹명을 입력하세요.");
				// 	eventGroupNameInput.focus();
				// 	return false;
				// }

				// 3) lessonTimeContainer 이 보여질 때(lessonTimeContainer.style.display !== "none") 
				//    → clasMinInput 의 값이 없는 경우 return false
				if (lessonTimeContainer.style.display !== "none") {
					if (!clasMinInput.value.trim()) {
						alertToast('error',"레슨시간(분)을 입력하세요.");
						clasMinInput.focus();
						return false;
					}
				}

				if(locker_select.style.display !== "none")
				{
					if(!locker_select.value == "0")
					{
						alertToast('error',"락커 구역을 선택하세요.");
						locker_select.focus();
						return false;
					}
				}

				// eventName.value 필수 입력
				if (!eventName.value.trim()) {
					alertToast('error',"이벤트명을 입력해주세요.");
					eventName.focus();
					return false;
				}
				
				// useProdInput.value 필수 입력
				if (!useProdInput.value.trim() || useProdInput.value == "0") {
					alertToast('error',"이용기간을 입력해주세요.");
					useProdInput.focus();
					return false;
				}
				
				// eventPrice.value 필수 입력
				if (!eventPrice.value.trim()) {
					alertToast('error',"판매가를 입력해주세요.");
					eventPrice.focus();
					return false;
				}
				if(locker_none_disp2.style.display != "none" && (select_use_per_week_unit.value != "0" &&  (use_per_week.value.trim() == "" || use_per_week.value.trim() == "0")))
				{
					alertToast('error',"한주 이용 가능 횟수/일수를 입력해주세요.");
					use_per_week.focus();
					return false;
				}
				if (eventTypeSection.style.display !== "none") {
					if(locker_select.style.display !== "none" && !all_locker.checked)
					{
						if(!lockr_number_from.value.trim())
						{
							alertToast('error',"락커 번호대 시작번호를 입력하세요.");
							lockr_number_from.focus();
							return false;
						}
						if(!lockr_number_to.value.trim())
						{
							alertToast('error',"락커 번호대 끝번호를 입력하세요.");
							lockr_number_to.focus();
							return false;
						}
					}
					
				}

				
				var tblParam = {};
				
				tblParam.m_cate = h_m_cate.value;
				let mode = "I";
				if(p_sell_event_sno.value != "")
				{
					mode = "U";
					tblParam.sell_event_sno = p_sell_event_sno.value;
				}
				tblParam.type = mode;
				tblParam.event_ref_sno = groupInput.value;
				
				// if(lockerContainer.style.display != "none")
				// {
					
				// 	if(locker_enabled.checked == true)
				// 	{
				// 		tblParam.lockr_set = 'Y';
				// 	} else
				// 	{
				// 		tblParam.lockr_set = 'N';
				// 	}
				// } else
				// {
				// 	tblParam.lockr_set = 'N';
				// }

				tblParam.lockr_set = locker_enabled_input.value;

				let lockr_gendr_set;

				// "혼용"과 "일반" 모두 체크 시 'GFM'
				if (uniSexLocker.checked && manWomanLocker.checked) {
					lockr_gendr_set = 'GFM';
				}
				// "일반"만 체크 시 'FM'
				else if (!uniSexLocker.checked && manWomanLocker.checked) {
					lockr_gendr_set = 'FM';
				}
				// "혼용"만 체크 시 'G'
				else if (uniSexLocker.checked && !manWomanLocker.checked) {
					lockr_gendr_set = 'G';
				}
				// 둘 다 체크 해제 시 기본값 (필요 시 조정 가능)
				else {
					lockr_gendr_set = ''; // 또는 다른 기본값 설정 가능
				}
				
				tblParam.event_type = eventTypeSelect.value;
				tblParam.acc_rtrct_dv = enterAvailable.value;
				tblParam.pre_enter_min = prevEnterMinInput.value;
				if (lessonTimeContainer.style.display !== "none") {
					tblParam.clas_min = clasMinInput.value.trim();
				}
				tblParam.clas_cnt = clasCntInput.value;
				tblParam.sell_yn = 'Y';
				tblParam.sell_event_nm = eventName.value;
				tblParam.use_prod = useProdInput.value;
				tblParam.use_unit = useProdUnit.value;
				tblParam.sell_amt = eventPrice.value.replace(/,/g, "");
				tblParam.enter_from_time = convertTo24Hour(fromTime.value);
				tblParam.enter_to_time = convertTo24Hour(toTime.value);
				tblParam.lockr_gendr_set = lockr_gendr_set;
				tblParam.lockr_number_from	= lockr_number_from.value;
				tblParam.lockr_number_to	= lockr_number_to.value;
				tblParam.lockr_knd = lockr_knd.value;
				let daysSelected = "";
				// 선택된 요일 문자열 생성
				dayCheckboxes.forEach(checkbox => {
					if (checkbox.checked) {
						daysSelected += days[checkbox.id];
					}
				});
				tblParam.week_select = daysSelected;
				tblParam.use_per_day = use_per_day.value;
				tblParam.use_per_week_unit = select_use_per_week_unit.value;
				tblParam.use_per_week = use_per_week.value;
				tblParam.domcy_day = domcyDayInput.value;
				tblParam.domcy_cnt = domcyCntInput.value;

				tblParam.domcy_poss_event_yn = domcy_poss_event_yn.value;
				tblParam.event_desc = "";
				// " ~ " 기준으로 분리
				var [startDate, endDate] = sellDateDateRange.value.split(" ~ ");

				// 원하는 객체나 변수에 각각 저장
				tblParam.sell_s_date = (startDate ? startDate:"");
				tblParam.sell_e_date = (endDate ? endDate : "");
				updateList.push(tblParam);
			}
			return true;
		}

		function fnSavePackage(){
			if(!packageSaveIsValid())
			{
				return;
			}
			tblParam = {};
			tblParam.itemList = updateList;
			tblParam.pkg_sell_event_sno = pkg_sell_event_select.value;
			tblParam.pkg_sell_event_nm = pkg_sell_event_name.value;
			tblParam.pkg_group_event_nm = pkg_group_event_name.value.trim();
			tblParam.pkg_group_event_sno = pkg_group_event_select.value;
			tblParam.sell_amt = removeCommas(pkg_event_total_price.value);
			var [startDate, endDate] = pkgSellDateDateRange.querySelector('input').value.split(" ~ ");

			// 원하는 객체나 변수에 각각 저장
			tblParam.sell_s_date = (startDate ? startDate:"");
			tblParam.sell_e_date = (endDate ? endDate : "");

			jQuery.ajax({
				url: '/teventmain/pkg_save',
				type: 'POST',
				data: tblParam,
				contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
				dataType: 'text',
				success: function (result) {
					if (result.substr(0, 8) == '<script>') {
						alertToast('error','로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
						location.href = '/tlogin';
						return;
					}
					json_result = $.parseJSON(result);
					if (json_result['result'] == 'true') {
						setPkgGroupSelectBox(json_result);
						setPkgEventSelectBox(json_result);
						setGroupSelectBox(json_result);
						setEventSelectBox(json_result);
						
						// if(p_sell_event_sno.value == "")
						// {
						// 	setGroupSelectBox(json_result);
						// 	groupSelectBox.value =tblParam.event_ref_sno; 
						// }
						// setEventSelectBox(json_result);
						
						// productModeChange("N");
						alertToast('success','이용권이 성공적으로 저장되었습니다.');
					} else
					{
						alertToast('error',json_result['msg']);
					}
				}
			}).done((res) => {
				console.log('통신성공');
			}).fail((error) => {
				console.log('통신실패');
				alertToast('error','오류가 발생하였습니다.');
			});
		}
		function fnChangeSellYn(category)
		{
			// pkg_eventSellYn.setAttribute("disabled", "true");
			// pkg_groupSellYn.setAttribute("disabled", "true");
			var param = {};
			param.category = category;
			if(category == "group")   //group
			{
				param.sell_yn = (pkg_groupSellYn.checked ? "Y" : "N");
				param.sell_event_sno = pkg_group_event_select.value;
			} else					  //event
			{
				param.sell_yn = (pkg_eventSellYn.checked ? "Y" : "N");
				param.sell_event_sno = pkg_sell_event_select.value;
			}

			jQuery.ajax({
				url: '/teventmain/change_sell_yn',
				type: 'POST',
				data:param,
				contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
				dataType: 'text',
				success: function (result) {
					if ( result.substr(0,8) == '<script>' )
					{
						alert('오류가 발생하였습니다.');
					}
					
					json_result = $.parseJSON(result);
					if (json_result['result'] == 'true')
					{
						if(category=="group")
						{
							// 새로 그릴 event_list 데이터
							setPkgGroupSelectBox(json_result);
							pkg_group_event_select.value = param.sell_event_sno; 
						} else
						{
							setPkgEventSelectBox(json_result);
							pkg_sell_event_select.value = param.sell_event_sno;
						}
					} else
					{
						alertToast('error',json_result["msg"]);
						if(category=="group")
						{
							pkg_groupSellYn.checked = true;
						}
					}
					groupSellYn.removeAttribute("disabled")
					eventSellYn.removeAttribute("disabled");
				}
			}).done((res) => {
				// 통신 성공시
				console.log('통신성공');
			}).fail((error) => {
				// 통신 실패시
				console.log('통신실패');
				eventSellYn.removeAttribute("disabled");
				alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
				location.href='/tlogin';
				return;
			});
		}
	});

	function setPkgGroupSelectBox(json_result)
	{
		var groupSelected =  json_result["pkg_group_event_sno"];
		if(groupSelected == "" || groupSelected == "null"  ){
			groupSelected =  groupSelectBox.value;
		}
		const eventList = json_result['event_list'];

		pkg_group_event_select.innerHTML = '';

		const defaultOption = document.createElement('option');
		defaultOption.value = '';
		defaultOption.textContent = '신규생성';
		pkg_group_event_select.appendChild(defaultOption);
		eventList.forEach((r) => {
			if(r.M_CATE == 'PKG' && r.EVENT_REF_SNO == r.SELL_EVENT_SNO)
			{
				const option = document.createElement('option');
				option.value = r.SELL_EVENT_SNO || '';

				option.setAttribute('data-sell-event-nm', r.SELL_EVENT_NM ?? '');
				option.setAttribute('data-ref-count', r.ref_count ?? '');
				option.setAttribute('data-p-ref-count', r.p_ref_count ?? '');
				option.setAttribute('data-sell-yn', r.SELL_YN ?? '');
				
				
				const refCount = (r.ref_count && !isNaN(r.p_ref_count)) ? parseInt(r.p_ref_count) : 0;
				let optionText = `[ ${r.SELL_EVENT_NM || ''} ]`;
				if (refCount > 0) {
					optionText += `, 등록상품수: ${refCount}`;
				}
				if (r.SELL_YN !== 'Y') {
					optionText += ' (판매중지)';
				}
				
				option.textContent = optionText;
				pkg_group_event_select.appendChild(option);
			}
			
		});

		pkg_group_event_select.value = groupSelected;
	}

	function setPkgEventSelectBox(json_result)
	{
		const prevSelected=json_result['pkg_sell_event_sno'];
		if(prevSelected == "" || prevSelected == "null"  ){
			prevSelected=prev_event.value;
		}
		pkg_sell_event_select.innerHTML = ''; // 기존 옵션 제거

		// 기본 옵션 추가: "신규생성"
		const defaultOption = document.createElement('option');
		defaultOption.value = '';
		defaultOption.textContent = '신규생성';
		pkg_sell_event_select.appendChild(defaultOption);
		const eventList = json_result['event_list'];
		// eventList 데이터로 옵션 동적 생성
		eventList.forEach((r) => {
			if(r.M_CATE == 'PKG')
			{
				const option = document.createElement('option');
				// option의 value 설정
				option.value = r.SELL_EVENT_SNO || '';

				// data-* 속성 설정 (null/undefined 처리를 위해 ?? 연산자 사용)
				option.setAttribute('data-acc-rtrct-dv', r.ACC_RTRCT_DV ?? '');
				option.setAttribute('data-event-type', r.EVENT_TYPE ?? '');
				option.setAttribute('data-clas-min', r.CLAS_MIN ?? '');
				option.setAttribute('data-m-cate', r.M_CATE ?? '');
				option.setAttribute('data-event-desc', r.EVENT_DESC ?? '');
				option.setAttribute('data-sell-event-nm', r.SELL_EVENT_NM ?? '');
				option.setAttribute('data-ref-count', r.ref_count ?? ''); // ref_count로 표기
				option.setAttribute('data-p-ref-count', r.p_ref_count ?? ''); // p_ref_count로 표기
				option.setAttribute('data-lockr-set', r.LOCKR_SET ?? '');
				option.setAttribute('data-pre-enter-min', r.PRE_ENTER_MIN ?? '');
				option.setAttribute('data-event-ref-sno', r.EVENT_REF_SNO ?? '');
				option.setAttribute('data-sell-event-sno', r.SELL_EVENT_SNO ?? '');
				option.setAttribute('data-domcy-poss-event-yn', r.DOMCY_POSS_EVENT_YN ?? '');
				option.setAttribute('data-lockr-knd', r.LOCKR_KND ?? '');
				option.setAttribute('data-use-prod', r.USE_PROD ?? '');
				option.setAttribute('data-use-unit', r.USE_UNIT ?? '');
				option.setAttribute('data-clas-cnt', r.CLAS_CNT ?? '');
				option.setAttribute('data-sell-s-date', r.SELL_S_DATE ?? '');
				option.setAttribute('data-sell-e-date', r.SELL_E_DATE ?? '');
				option.setAttribute('data-domcy-cnt', r.DOMCY_CNT ?? '');
				option.setAttribute('data-domcy-day', r.DOMCY_DAY ?? '');
				option.setAttribute('data-use-per-day', r.USE_PER_DAY ?? '');
				option.setAttribute('data-use-per-week', r.USE_PER_WEEK ?? '');
				option.setAttribute('data-use-per-week-unit', r.USE_PER_WEEK_UNIT ?? '');
				option.setAttribute('data-enter-from-time', r.ENTER_FROM_TIME ?? '');
				option.setAttribute('data-enter-to-time', r.ENTER_TO_TIME ?? '');
				option.setAttribute('data-sell-amt', r.SELL_AMT ?? '');
				option.setAttribute('data-sell-yn', r.SELL_YN ?? '');
				// 표시 텍스트 설정
				// option.textContent = (r.SELL_EVENT_NM + (r.SELL_YN == 'N' ? " (판매중지)" : "") || '') + " " + (r["SELL_AMT"] != null ? Number(r["SELL_AMT"]).toLocaleString() : '0') + '원';
				option.textContent = (r.SELL_EVENT_NM + (r.SELL_YN == 'N' ? " (판매중지)" : "") || '') ;
				// select에 option 추가
				pkg_sell_event_select.appendChild(option);
			}
		});
		filterPkgEventSelectOptions();
		pkg_sell_event_select.value = prevSelected;
		p_sell_event_sno.value = json_result['pkg_sell_event_sno'];
	}

	// 필터링 함수
	function filterPkgGroupSelectOptions() {
		const options = pkg_group_event_select.querySelectorAll('option:not([value=""])');
		options.forEach(option => {
			const mCate = option.getAttribute("data-m-cate");
			const lockerSet = option.getAttribute("data-lockr-set") || "N";
			const eventRefSno = option.getAttribute("data-event-ref-sno") || "";
			const sellEventSno = option.getAttribute("data-sell-event-sno") || "";
			option.style.display = ( mCate == "PKG" && eventRefSno == sellEventSno ) ? "" : "none"; 
		});

		// 필터링 후, 이미 선택된 옵션이 숨겨졌다면 첫 번째 표시되는 옵션으로 선택
		let isSelectedVisible = false;

		options.forEach(option => {
			// 표시되어 있는 옵션 중 첫 번째 옵션을 찾음
			if (option.style.display !== "none") {
				// 현재 selectBox에 선택되어 있는 value와 동일한 옵션이 표시되어 있으면
				if (option.value === pkg_group_event_select.value) {
					isSelectedVisible = true;
				}
			}
		});

		// 현재 선택된 옵션이 보이지 않는다면, 첫 번째 표시된 옵션으로 선택
		if (!isSelectedVisible ) {
			prev_event.value = "";
			sold_cnt.value = "0";
		}
	}

	// 필터링 함수
	function filterPkgEventSelectOptions() {
		const options = pkg_sell_event_select.querySelectorAll('option:not([value=""])');
		options.forEach(option => {
			const mCate = option.getAttribute("data-m-cate");
			const lockerSet = option.getAttribute("data-lockr-set") || "N";
			const eventRefSno = option.getAttribute("data-event-ref-sno") || "";
			const sellEventSno = option.getAttribute("data-sell-event-sno") || "";
			option.style.display = ( eventRefSno ===  pkg_group_event_select.value && sellEventSno !== pkg_group_event_select.value) ? "" : "none"; 
		});

		// 필터링 후, 이미 선택된 옵션이 숨겨졌다면 첫 번째 표시되는 옵션으로 선택
		let isSelectedVisible = false;

		options.forEach(option => {
			// 표시되어 있는 옵션 중 첫 번째 옵션을 찾음
			if (option.style.display !== "none") {
				// 현재 selectBox에 선택되어 있는 value와 동일한 옵션이 표시되어 있으면
				if (option.value === pkg_sell_event_select.value) {
					isSelectedVisible = true;
				}
			}
		});

		// 현재 선택된 옵션이 보이지 않는다면, 첫 번째 표시된 옵션으로 선택
		if (!isSelectedVisible ) {
			prev_event.value = "";
			sold_cnt.value = "0";
		}
	}

	function setGroupSelectBox(json_result) {
		// // 이용권 그룹 섹션
		// const inputMode = document.getElementById("input_mode");  //이용권그룹 CRUD 상태 저장
		// inputMode.value = "I";
		// // 이용권 분류 섹션
		// const radioButtons = document.querySelectorAll('input[name="m_cate"]');  // 이용권 분류 (라디오 버튼들)
		// const lockerContainer = document.querySelector(".locker-container");     // 락커 여부 섹션
		// const locker_enabled = document.querySelector("#locker_enabled");        // 락커 활성화 여부
		// // 이용권 그룹 불러오기 섹션
		// const mCateAllDiv = document.querySelector("#m_cate_all");               // 기존 이용권 그룹 선택 섹션
		// const prev_event_group_status = document.getElementById("prev_event_group_status");
		const groupSelectBox = document.querySelector('select[name="prev_event_group"]'); // 기존 이용권 그룹 선택

		const prev_event = document.getElementById("prev_event");		

		var groupSelected =  json_result["sell_event_sno"];
		if(groupSelected == "" || groupSelected == "null"  ){
			groupSelected =  groupSelectBox.value;
		}
		const eventList = json_result['event_list'];

		groupSelectBox.innerHTML = '';

		const defaultOption = document.createElement('option');
		defaultOption.value = '';
		defaultOption.textContent = '신규생성';
		groupSelectBox.appendChild(defaultOption);
		eventList.forEach((r) => {
			const option = document.createElement('option');
			option.value = r.SELL_EVENT_SNO || '';

			option.setAttribute('data-acc-rtrct-dv', r.ACC_RTRCT_DV ?? '');
			option.setAttribute('data-event-type', r.EVENT_TYPE ?? '');
			option.setAttribute('data-clas-min', r.CLAS_MIN ?? '');
			option.setAttribute('data-m-cate', r.M_CATE ?? '');
			option.setAttribute('data-event-desc', r.EVENT_DESC ?? '');
			option.setAttribute('data-sell-event-nm', r.SELL_EVENT_NM ?? '');
			option.setAttribute('data-ref-count', r.ref_count ?? '');
			option.setAttribute('data-p-ref-count', r.p_ref_count ?? '');
			option.setAttribute('data-lockr-set', r.LOCKR_SET ?? '');
			option.setAttribute('data-lockr-knd', r.LOCKR_KND ?? '');
			option.setAttribute('data-pre-enter-min', r.PRE_ENTER_MIN ?? '');
			option.setAttribute('data-event-ref-sno', r.EVENT_REF_SNO ?? '');
			option.setAttribute('data-sell-event-sno', r.SELL_EVENT_SNO ?? '');
			option.setAttribute('data-domcy-poss-event-yn', r.DOMCY_POSS_EVENT_YN ?? '');
			option.setAttribute('data-sell-yn', r.SELL_YN ?? '');
			
			const accRtrctDvText = (r.ACC_RTRCT_DV && sDef.ACC_RTRCT_DV2 && sDef.ACC_RTRCT_DV2[r.ACC_RTRCT_DV])
				? sDef.ACC_RTRCT_DV2[r.ACC_RTRCT_DV]
				: '알 수 없음';
			const mCatePropertyText = (r.EVENT_TYPE && sDef.M_CATE_PROPERTY && sDef.M_CATE_PROPERTY[r.EVENT_TYPE])
				? sDef.M_CATE_PROPERTY[r.EVENT_TYPE]
				: '알 수 없음';
			const refCount = (r.ref_count && !isNaN(r.ref_count)) ? parseInt(r.ref_count) : 0;
			let optionText = `[ ${r.SELL_EVENT_NM || ''} ] ${mCatePropertyText}, ${accRtrctDvText}`;
			if (refCount > 0) {
				optionText += `, 등록상품수: ${refCount}`;
			}
			if (r.SELL_YN !== 'Y') {
				optionText += ' (판매중지)';
			}
			
			option.textContent = optionText;

			groupSelectBox.appendChild(option);
		});

		filterGroupSelectOptions();
		groupSelectBox.value = groupSelected;
	}

	function setEventSelectBox(json_result)
	{
		const prevSelected=json_result['sell_event_sno'];
		if(prevSelected == "" || prevSelected == "null"  ){
			prevSelected=prev_event.value;
		}
		prev_event.innerHTML = ''; // 기존 옵션 제거

		// 기본 옵션 추가: "신규생성"
		const defaultOption = document.createElement('option');
		defaultOption.value = '';
		defaultOption.textContent = '신규생성';
		prev_event.appendChild(defaultOption);
		const eventList = json_result['event_list'];
		// eventList 데이터로 옵션 동적 생성
		eventList.forEach((r) => {
			const option = document.createElement('option');

			// option의 value 설정
			option.value = r.SELL_EVENT_SNO || '';

			// data-* 속성 설정 (null/undefined 처리를 위해 ?? 연산자 사용)
			option.setAttribute('data-acc-rtrct-dv', r.ACC_RTRCT_DV ?? '');
			option.setAttribute('data-event-type', r.EVENT_TYPE ?? '');
			option.setAttribute('data-clas-min', r.CLAS_MIN ?? '');
			option.setAttribute('data-m-cate', r.M_CATE ?? '');
			option.setAttribute('data-event-desc', r.EVENT_DESC ?? '');
			option.setAttribute('data-sell-event-nm', r.SELL_EVENT_NM ?? '');
			option.setAttribute('data-ref-count', r.ref_count ?? ''); // ref_count로 표기
			option.setAttribute('data-p-ref-count', r.p_ref_count ?? ''); // p_ref_count로 표기
			option.setAttribute('data-lockr-set', r.LOCKR_SET ?? '');
			option.setAttribute('data-pre-enter-min', r.PRE_ENTER_MIN ?? '');
			option.setAttribute('data-event-ref-sno', r.EVENT_REF_SNO ?? '');
			option.setAttribute('data-sell-event-sno', r.SELL_EVENT_SNO ?? '');
			option.setAttribute('data-domcy-poss-event-yn', r.DOMCY_POSS_EVENT_YN ?? '');
			option.setAttribute('data-lockr-knd', r.LOCKR_KND ?? '');
			option.setAttribute('data-use-prod', r.USE_PROD ?? '');
			option.setAttribute('data-use-unit', r.USE_UNIT ?? '');
			option.setAttribute('data-clas-cnt', r.CLAS_CNT ?? '');
			option.setAttribute('data-sell-s-date', r.SELL_S_DATE ?? '');
			option.setAttribute('data-sell-e-date', r.SELL_E_DATE ?? '');
			option.setAttribute('data-domcy-cnt', r.DOMCY_CNT ?? '');
			option.setAttribute('data-domcy-day', r.DOMCY_DAY ?? '');
			option.setAttribute('data-use-per-day', r.USE_PER_DAY ?? '');
			option.setAttribute('data-use-per-week', r.USE_PER_WEEK ?? '');
			option.setAttribute('data-use-per-week-unit', r.USE_PER_WEEK_UNIT ?? '');
			option.setAttribute('data-enter-from-time', r.ENTER_FROM_TIME ?? '');
			option.setAttribute('data-enter-to-time', r.ENTER_TO_TIME ?? '');
			option.setAttribute('data-sell-amt', r.SELL_AMT ?? '');
			option.setAttribute('data-sell-yn', r.SELL_YN ?? '');
			// 표시 텍스트 설정
			option.textContent = (r.SELL_EVENT_NM + (r.SELL_YN == 'N' ? " (판매중지)" : "") || '') + " " + (r["SELL_AMT"] != null ? Number(r["SELL_AMT"]).toLocaleString() : '0') + '원';

			// select에 option 추가
			prev_event.appendChild(option);
		});
		filterEventSelectOptions();
		prev_event.value = prevSelected;
		p_sell_event_sno.value = json_result['sell_event_sno'];
		// eventSellYn.removeAttribute("disabled");
	}

	// 필터링 함수
	function filterGroupSelectOptions() {

		const groupSelectBox = document.querySelector('select[name="prev_event_group"]'); // 기존 이용권 그룹 선택
		const selectedCate = document.querySelector('input[name="m_cate"]:checked')?.value || "";
		const options = groupSelectBox.querySelectorAll('option:not([value=""])');
		const lockerValue  = locker_enabled && locker_enabled.checked ? "Y" : "N";
		options.forEach(option => {
			const mCate = option.getAttribute("data-m-cate");
			const lockerSet = option.getAttribute("data-lockr-set") || "N";
			const eventRefSno = option.getAttribute("data-event-ref-sno") || "";
			const sellEventSno = option.getAttribute("data-sell-event-sno") || "";
			const sellYn = option.getAttribute("data-sell-yn") || "";
			option.style.display = (selectedCate && mCate === selectedCate && lockerSet === lockerValue && eventRefSno === sellEventSno && (prev_event_group_status.value == "0" || prev_event_group_status.value == sellYn)) ? "" : "none"; 
		});

		// 필터링 후, 이미 선택된 옵션이 숨겨졌다면 첫 번째 표시되는 옵션으로 선택
		let isSelectedVisible = false;

		options.forEach(option => {
			// 표시되어 있는 옵션 중 첫 번째 옵션을 찾음
			if (option.style.display !== "none") {
				// 현재 selectBox에 선택되어 있는 value와 동일한 옵션이 표시되어 있으면
				if (option.value === groupSelectBox.value) {
					isSelectedVisible = true;
				}
			}
		});

		// 현재 선택된 옵션이 보이지 않는다면, 첫 번째 표시된 옵션으로 선택
		if (!isSelectedVisible ) {
			groupSelectBox.value = "";
		}
	}

	// 필터링 함수
	function filterEventSelectOptions() {
		
		const groupSelectBox = document.querySelector('select[name="prev_event_group"]'); // 기존 이용권 그룹 선택
		
		const selectedCate = document.querySelector('input[name="m_cate"]:checked')?.value || "";
		const options = prev_event.querySelectorAll('option:not([value=""])');
		options.forEach(option => {
			const mCate = option.getAttribute("data-m-cate");
			const lockerSet = option.getAttribute("data-lockr-set") || "N";
			const eventRefSno = option.getAttribute("data-event-ref-sno") || "";
			const sellEventSno = option.getAttribute("data-sell-event-sno") || "";
			option.style.display = ( eventRefSno ===  groupSelectBox.value && (sellEventSno !== groupSelectBox.value )|| option.value == "0" ) ? "" : "none"; 
		});

		// 필터링 후, 이미 선택된 옵션이 숨겨졌다면 첫 번째 표시되는 옵션으로 선택
		let isSelectedVisible = false;

		options.forEach(option => {
			// 표시되어 있는 옵션 중 첫 번째 옵션을 찾음
			if (option.style.display !== "none") {
				// 현재 selectBox에 선택되어 있는 value와 동일한 옵션이 표시되어 있으면
				if (option.value === groupSelectBox.value) {
					isSelectedVisible = true;
				}
			}
		});

		// 현재 선택된 옵션이 보이지 않는다면, 첫 번째 표시된 옵션으로 선택
		if (!isSelectedVisible ) {
			prev_event.value = "";
			sold_cnt.value = "0";
		}
	}

	const pkg_content = document.getElementById("pkg_content");
	const pkgAccordion = document.getElementById('pkg_accordion');

	const list1PkgEvent = document.querySelector('.list1-pkg-event');
	const middlePkgEvent = document.querySelector('.middle-pkg-event');
	const last1PkgEvent = document.querySelector('.last1-pkg-event');

	function setPkgProductDisp()
	{
		if (last1PkgEvent) {
			// .last2-pkg-event의 display 상태 확인
			const isLast1Hidden = window.getComputedStyle(last1PkgEvent).display === 'none';
			if (isLast1Hidden) {
				list1PkgEvent.classList.add('first-pkg-event');
				list1PkgEvent.classList.remove('all-pkg-event');
				// .last2-pkg-event이 숨겨져 있으면 .last1-pkg-event에 하단 테두리 포함 스타일 적용
				middlePkgEvent.classList.remove('list-pkg-event');
				middlePkgEvent.classList.add('last-pkg-event');
			} else {
				middlePkgEvent.classList.add('list-pkg-event');
				middlePkgEvent.classList.remove('last-pkg-event');
				// .last2-pkg-event이 숨겨져 있으면 .last1-pkg-event에 하단 테두리 포함 스타일 적용
				last1PkgEvent.classList.remove('list-pkg-event');
				last1PkgEvent.classList.add('last-pkg-event');
			}
		}
		
	}

	function calculateTotalPrice() {
		// 모든 event_price 입력 필드 선택
		const eventPriceInputs = pkg_accordion.querySelectorAll('input[name="event_price"]');
		
		// 합계 입력 필드 선택
		const totalPriceInput = document.getElementById('pkg_event_total_price');
		
		// 합계 입력 필드가 없으면 오류 처리
		if (!totalPriceInput) {
			console.error('pkg_event_total_price 입력 필드를 찾을 수 없습니다.');
			return;
		}

		// event_price 입력값 합계 계산
		const totalPrice = Array.from(eventPriceInputs).reduce((sum, input) => {
			// 입력값을 숫자로 변환 (유효하지 않으면 0으로 처리)
			const value = parseFloat(removeCommas(input.value)) || 0;
			return sum + value;
		}, 0);

		// 합계를 pkg_event_total_price에 설정
		totalPriceInput.value = totalPrice.toLocaleString('ko-KR'); // 천 단위로 쉼표 추가
	}

	//해당 팩키지 요약 가격으로 아이템 번호 input을 가져온다.
	function getNumOfPrice(pkgEventPrice)
	{
		let tempDiv = pkgEventPrice.closest("div");
		return tempDiv.querySelector(".num");
	}

	//아이템 가격으로 아이템 번호 input을 가져온다.
	function getNumOfItemPrice(eventPrice)
	{
		let accordionItem = eventPrice.closest(".accordion-item");
		return accordionItem.querySelector(".num");
	}

	//팩키지 요약 가격 변경시 해당 Item가격을 변동한다.
	function changeItemPrice(pkgEventPrice)
	{
		let num = getNumOfPrice(pkgEventPrice);
		let tempPkgAccordion = document.getElementById('pkg_accordion');
		let collapse = document.getElementById("collapse"+num.value);
		let eventPrice = collapse.querySelector('input[name="event_price"]');
		eventPrice.value = pkgEventPrice.value;
		calculateTotalPrice();
	}

	//아이템가격 변동시 해당 팩키지 요약 가격을 변동한다.
	function changePkgEventPrice(itemPrice)
	{
		let num = getNumOfItemPrice(itemPrice);
		let tmp_pkg_event_price = document.getElementById("pkg_event_price"+num.value);
		tmp_pkg_event_price.value = itemPrice.value;
		calculateTotalPrice();
	}

	function closePrice(button) {
		// Price Row 삭제		
		let row = button.closest('tr');
		if (row) {
			//Accordion 삭제
			let num = row.querySelector('.num');
			let collapse = document.getElementById("collapse"+num.value);
			let accordionItem = collapse.closest('.accordion-item');
			if (accordionItem) {
				// DOM에서 삭제
				accordionItem.remove();
				setPkgProductDisp();
			} else {
				console.error('accordion-item을 찾을 수 없습니다.');
			}
			let itemCount = pkgAccordion.querySelectorAll('.accordion-item').length;
			if(itemCount == 0)
			{
				let pkg_content = document.getElementById("pkg_content");
				pkg_content.style.display="none";
			}
			row.remove();
			calculateTotalPrice();
		} else {
			console.error('Price Row를 찾을 수 없습니다.');
		}
	}


	function closeEvent(button) {
		
		let accordionItem = button.closest('.accordion-item');
		// 버튼이 속한 accordion-item 찾기
		if (accordionItem) {
			let num = getNumOfItemPrice(button)
			let tmp_pkg_event_price = document.getElementById("pkg_event_price" + num.value);
			let row = tmp_pkg_event_price.closest("tr");
			row.remove();
			// DOM에서 삭제
			accordionItem.remove();
			setPkgProductDisp();
			calculateTotalPrice();
		} else {
			console.error('accordion-item을 찾을 수 없습니다.');
		}
		const itemCount = pkgAccordion.querySelectorAll('.accordion-item').length;
		if(itemCount == 0)
		{
			let pkg_content = document.getElementById("pkg_content");
			pkg_content.style.display="none";
		}
	}

	function convertTo24Hour(timeStr) {
		if(timeStr && timeStr != "")
		{
			const [time, period] = timeStr.split(' ');
			let [hours, minutes] = time.split(':').map(Number);
			if (period === '오후' && hours !== 12) hours += 12;
			if (period === '오전' && hours === 12) hours = 0;
			return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:00`;
		}
			return "";
	}

	function convertTo12Hour(timeStr) {
		if(timeStr && timeStr != "")
		{
			const [hours, minutes] = timeStr.split(':').map(Number);
			const period = hours >= 12 ? '오후' : '오전';
			const displayHours = hours % 12 || 12; // 0시 또는 12시는 12로 표시
			return `${displayHours}:${minutes.toString().padStart(2, '0')} ${period}`;
		} else
			return "";
		
	}

	// parentId의 하위 요소들을 복사하여 반환
	function cloneChildren(parentId) {
		const parent = document.getElementById(parentId);
		if (!parent) {
			console.error(`ID가 ${parentId}인 요소를 찾을 수 없습니다.`);
			return [];
		}

		const children = parent.children;
		const clonedChildren = [];
		for (let i = 0; i < children.length; i++) {
			const clonedChild = children[i].cloneNode(true);
			clonedChildren.push(clonedChild);
		}
		return clonedChildren;
	}

	// parentId를 포함한 하위요소까지 복사
	function cloneById(parentId) {
		// 원본 요소 가져오기
		const parentElement = document.getElementById(parentId);
		if (!parentElement) {
			console.error(`ID가 ${parentId}인 요소를 찾을 수 없습니다.`);
			return null;
		}

		// 1. 원본의 모든 입력 상태 저장
		const inputStates = {};
		const inputs = parentElement.querySelectorAll('select, input:not([type="file"]), textarea');
		inputs.forEach((input, index) => {
			const identifier = input.id || input.name || `input_${index}`;
			if (input.type === 'checkbox' || input.type === 'radio') {
				inputStates[identifier] = { checked: input.checked };
			} else {
				inputStates[identifier] = { value: input.value };
			}
		});

		// 2. 요소 깊은 복사
		const clonedElement = parentElement.cloneNode(true);

		// 3. 클론된 요소의 id 속성 삭제
		clonedElement.removeAttribute('id');

		// 4. 클론된 요소에 상태 복원 및 disabled 속성 제거
		const clonedInputs = clonedElement.querySelectorAll('select, input:not([type="file"]), textarea');
		clonedInputs.forEach((clonedInput, index) => {
			const identifier = clonedInput.id || clonedInput.name || `input_${index}`;
			const state = inputStates[identifier];
			if (state) {
				if (clonedInput.type === 'checkbox' || clonedInput.type === 'radio') {
					clonedInput.checked = state.checked;
				} else {
					clonedInput.value = state.value;
				}
			}
			// disabled 속성 제거
			clonedInput.removeAttribute('disabled');
		});

		// 5. 기타 disabled 속성 제거 (버튼 등)
		const disabledElements = clonedElement.querySelectorAll('[disabled]');
		disabledElements.forEach(element => {
			element.removeAttribute('disabled');
		});

		return clonedElement;
	}

	// 정규식 특수문자 이스케이프 처리
	function escapeRegExp(string) {
		return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
	}

	// clonedChildren을 targetId 요소에 붙여넣기
	function appendElement(clonedChildren, targetId) {
		const target = document.getElementById(targetId);
		if (!target) {
			console.error(`ID가 ${targetId}인 요소를 찾을 수 없습니다.`);
			return;
		}

		clonedChildren.forEach(child => {
			target.appendChild(child);
		});
	}

	function appendElementBeforeLastTr(clonedChildren, targetId) {
		// 대상 <tbody> 요소 찾기
		const target = document.getElementById(targetId);
		if (!target) {
			console.error(`ID가 ${targetId}인 요소를 찾을 수 없습니다.`);
			return;
		}

		// <tbody> 내의 모든 <tr> 요소 찾기
		const trElements = target.querySelectorAll('tr');
		if (trElements.length === 0) {
			console.error('tbody 내에 tr 요소가 없습니다. 요소를 끝에 추가합니다.');
			clonedChildren.forEach(child => target.appendChild(child));
			return;
		}

		// 마지막 <tr> 요소
		const lastTr = trElements[trElements.length - 1];

		// clonedChildren을 마지막 <tr> 바로 앞에 삽입
		clonedChildren.forEach(child => {
			target.insertBefore(child, lastTr);
		});
	}

	// targetId의 하위 요소 중 parentId 요소와 그 하위 요소를 삭제
	function removeChildElement(targetId, parentId)
	{
		const target = document.getElementById(targetId);
		if (!target) {
			console.error(`ID가 ${targetId}인 요소를 찾을 수 없습니다.`);
			return;
		}

		const elementToRemove = target.querySelector(`#${parentId}`);
		if (elementToRemove) {
			elementToRemove.remove();
		} else {
			console.warn(`ID가 ${parentId}인 요소를 ${targetId}에서 찾을 수 없습니다.`);
		}
	}

	// parentId 요소와 그 하위 요소를 모두 삭제
	function removeElementById(parentId) {
		const parent = document.getElementById(parentId);
		if (!parent) {
			console.error(`ID가 ${parentId}인 요소를 찾을 수 없습니다.`);
			return;
		}
		parent.remove();
	}

	// targetId 요소의 하위 요소와 콘텐츠를 모두 삭제
	function emptyElement(targetId) {
		const target = document.getElementById(targetId);
		if (!target) {
			console.error(`ID가 ${targetId}인 요소를 찾을 수 없습니다.`);
			return;
		}
		while (target.firstChild) {
			target.removeChild(target.firstChild);
		}
	}

	// clonedChildren의 모든 텍스트에서 origStr을 replaceStr로 치환
	function replaceText(clonedChildren, origStr, replaceStr) {
		const escapedStr = escapeRegExp(origStr);
		const replacedChildren = clonedChildren.map(child => {
			const clonedChild = child.cloneNode(true);
			// 모든 텍스트 노드를 순회하며 치환
			const walker = document.createTreeWalker(clonedChild, NodeFilter.SHOW_TEXT, null, false);
			let node;
			while (node = walker.nextNode()) {
				node.textContent = node.textContent.replace(new RegExp(escapedStr, 'g'), replaceStr);
			}
			// 속성 값에서도 치환
			const elementsWithAttributes = clonedChild.querySelectorAll('[id], [data-bs-target], [aria-controls], [aria-labelledby], [name]');
			elementsWithAttributes.forEach(element => {
				if (element.id) {
					element.id = element.id.replace(new RegExp(escapedStr, 'g'), replaceStr);
				}
				if (element.getAttribute('data-bs-target')) {
					element.setAttribute('data-bs-target', element.getAttribute('data-bs-target').replace(new RegExp(escapedStr, 'g'), replaceStr));
				}
				if (element.getAttribute('aria-controls')) {
					element.setAttribute('aria-controls', element.getAttribute('aria-controls').replace(new RegExp(escapedStr, 'g'), replaceStr));
				}
				if (element.getAttribute('aria-labelledby')) {
					element.setAttribute('aria-labelledby', element.getAttribute('aria-labelledby').replace(new RegExp(escapedStr, 'g'), replaceStr));
				}
				if (element.getAttribute('name')) {
					element.setAttribute('name', element.getAttribute('name').replace(new RegExp(escapedStr, 'g'), replaceStr));
				}
			});
			return clonedChild;
		});
		return replacedChildren;
	}

	// clonedChildren 내의 targetClass를 가진 요소에 pkg 클래스를 추가
	function addClass(clonedChildren, targetClass, pkg) {
		const replacedChildren = clonedChildren.map(child => {
			const clonedChild = child.cloneNode(true);
			// targetClass를 가진 모든 요소를 찾아 pkg 클래스 추가
			const elements = clonedChild.querySelectorAll(`.${targetClass}`);
			elements.forEach(element => {
				element.classList.add(pkg);
			});
			// clonedChild 자체가 targetClass를 가지는 경우도 처리
			if (clonedChild.classList.contains(targetClass)) {
				clonedChild.classList.add(pkg);
			}
			return clonedChild;
		});
		return replacedChildren;
	}

	// 특정 선택자에 해당하는 요소의 텍스트를 수정
	function modifyContent(clonedChildren, selector, newContent) {
		const modifiedChildren = clonedChildren.map(child => {
			const clonedChild = child.cloneNode(true);
			const elements = clonedChild.querySelectorAll(selector);
			elements.forEach(element => {
				element.textContent = newContent;
			});
			return clonedChild;
		});
		return modifiedChildren;
	}

	// 특정 선택자에 해당하는 요소의 innerHTML을 수정
	function modifyInnerHTML(clonedChildren, selector, newHTML) {
		const modifiedChildren = clonedChildren.map(child => {
			const clonedChild = child.cloneNode(true);
			const elements = clonedChild.querySelectorAll(selector);
			elements.forEach(element => {
				element.innerHTML = newHTML;
			});
			return clonedChild;
		});
		return modifiedChildren;
	}

	function replaceAccordionBodyWithClonedGroup(targetId, clonedGroup) {
		// 1. 대상 요소 찾기
		const targetElement = document.getElementById(targetId);
		if (!targetElement) {
			console.error(`ID가 '${targetId}'인 요소를 찾을 수 없습니다.`);
			return;
		}

		// 2. .accordion-body 클래스를 가진 모든 하위 요소 제거
		const accordionBodies = targetElement.querySelectorAll('.accordion-body');
		accordionBodies.forEach(body => body.remove());

		// 3. 클론된 그룹 추가
		targetElement.appendChild(clonedGroup);
	}

	function appendClonedGroupToTarget(targetId, clonedGroup) {
		// 1. 대상 요소 찾기
		const targetElement = document.getElementById(targetId);
		if (!targetElement) {
			console.error(`ID가 '${targetId}'인 요소를 찾을 수 없습니다.`);
			return;
		}

		// 2. 클론된 그룹 추가
		targetElement.appendChild(clonedGroup);
	}
	
	// 입력값에서 쉼표를 제거하는 함수
	function removeCommas(value) {
		return value.replace(/,/g, "");
	}

	function loadSoldCnt(sell_event_sno)
	{
		var param = {};
		param.sell_event_sno = sell_event_sno;

		jQuery.ajax({
			url: '/teventmain/event_sold_cnt',
			type: 'POST',
			data:param,
			contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
			dataType: 'text',
			success: function (result) {
				if ( result.substr(0,8) == '<script>' )
				{
					alert('오류가 발생하였습니다.');
				}
				
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					sold_cnt.textContent = json_result['sold_cnt'];
				}
				let cnt = parseInt(sold_cnt.textContent);
				if(cnt == 0)
				{
					saveProductButton.classList.remove("disabled");
					editProductButton.classList.remove("disabled");
					deleteProductButton.classList.remove("disabled");
				} else
				{
					saveProductButton.classList.add("disabled", "true");
					editProductButton.classList.add("disabled", "true");
					deleteProductButton.classList.add("disabled", "true");
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

	function pkgEventPriceAddEventListener(priceInput)
	{
		priceInput.addEventListener("input", function (e){
			let value = this.value.replace(/[^0-9]/g, '');
			this.value = Number(value).toLocaleString('ko-KR');
			changeItemPrice(priceInput);
		});
	}

</script>

