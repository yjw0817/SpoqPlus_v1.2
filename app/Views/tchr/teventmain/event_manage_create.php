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
		max-width: 100px; /* 최대 길이 제한 */
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
		max-width: 550px; 
	}

	.bootstrap-timepicker{
		max-width: 120px; 
	}

	.use_prod-input{
		max-width: 120px; 
	}
	.use-prod-type-width{
		max-width: 90px; 
	}
	.locker-number-type-select-width{
		max-width: 80px; 
	}
	.use-per-day-select-width{
		max-width: 135px; 
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
	#m_cate_all {
		flex-grow: 1; /* 남은 공간을 채움 */
		display: flex;
		align-items: center;
	}
	.inline-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }
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
<div id="content" >
	<!-- BEGIN breadcrumb -->
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">상품(종목) 구매관리</a></li>
		<li class="breadcrumb-item"><a href="javascript:;">판매이용권 등록관리</a></li>
		<li class="breadcrumb-item active">판매이용권 만들기</li>
	</ol>
	<!-- END breadcrumb -->
	<!-- BEGIN page-header -->
	<h1 class="page-header">판매이용권 만들기 <small>이용권 그룹을 생성후 판매이용권을 만듭니다.</small></h1>
	<!-- END page-header -->
	<hr class="mb-4">
	<!-- BEGIN row -->
	<input type="hidden" id="input_mode">
	<div class="row">
		<!-- BEGIN col-3 -->
		<div style="width: 230px">
			<nav class="navbar navbar-sticky d-none d-xl-block my-n4 py-4 h-200 text-end">
				<nav class="nav" id="bsSpyTarget">
					<!-- <a class="nav-link active" href="#select_cate" data-toggle="scroll-to">분류선택</a> -->
					<a class="nav-link" href="#general" data-toggle="scroll-to">이용권그룹</a>
					<a class="nav-link" href="#notifications" data-toggle="scroll-to">판매이용권</a>
				</nav>
			</nav>
		</div>
		<!-- END col-3 -->
		<!-- BEGIN col-9 -->
		<div class="col-xl-8" id="bsSpyContent">

			<!-- BEGIN #select_cate -->
			<div id="select_cate" class="mb-4 pb-3" style="display:none">
				<h4 class="d-flex align-items-center mb-2">
					<iconify-icon class="fs-24px me-2 text-body text-opacity-75 my-n1" icon="solar:winrar-bold-duotone"></iconify-icon> 분류선택
				</h4>
				<p>모든 판매 이용권은 분류선택이 필수입니다.</p>
				<div class="card">
					<div class="list-group list-group-flush fw-bold">
						<!-- 기존 내용 유지 -->
						<div class="list-group-item d-flex align-items-center">
							<div class="flex-fill me-4"> <!-- 첫 번째 flex-fill에 오른쪽 마진 추가 -->
								<div>대분류 선택</div>
								<div class="text-body text-opacity-60">
									<select class="form-select " style="width: 200px;" name="1rd_cate_cd" id="1rd_cate_cd" >
                                		<option value=''>대분류 선택</option>
                                		<?php foreach($cate1 as $c1) :?>
											<?php
												$cateFull = $c1['1RD_CATE_CD'];
												$cd = substr($cateFull, 0, 3);        // 앞 3글자: 카테고리 코드
												$lockrYn = substr($cateFull, -1);    // 마지막 1글자: 락커 여부
											?>
                                			<option value="<?php echo $cateFull; ?>" 													
												data-m-cate ="<?php echo $cd; ?>"
												data-lockr-yn-set="<?php echo $lockrYn; ?>"
												data-grp-cate-set="<?php echo $c1["GRP_CATE_SET"]; ?>"
											><?php echo  $sDef['M_CATE'][$cd] ?><?php echo  $lockrYn == "Y" ? "(락커)" : ""; ?></option>
                                		<?php endforeach;?>
                					</select>
								</div>
							</div>
							<div class="flex-fill me-4"> <!-- 두 번째 flex-fill에 오른쪽 마진 추가 -->
								<div>중분류 선택<span class="text-danger">*</span></div>
								<div class="d-flex align-items-center text-body text-opacity-60">
									<select class="form-select me-3" style="width: 250px;" name="2rd_cate_cd" id="2rd_cate_cd">
                                		<option>중분류 선택</option>
                					</select>
									<input type="hidden" id="two_cate_mode" value="new" />
									<button class="btn btn-primary" style="width: 100px; visibility:hidden;" id="saveCateButton" >저장</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END #select_cate -->


			<!-- BEGIN #general -->
			<div id="general" class="mb-4 pb-3">
				<h4 class="d-flex align-items-center mb-2">
					<iconify-icon class="fs-24px me-2 text-body text-opacity-75 my-n1" icon="solar:winrar-bold-duotone"></iconify-icon> 이용권그룹
				</h4>
				<p>신규생성 또는 기존 이용권 그룹을 불러올 수 있습니다.</p>
				<div class="card">
					<div class="list-group list-group-flush fw-bold">
						<!-- 기존 내용 유지 -->
						<div class="list-group-item d-flex align-items-center">
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
						<div class="list-group-item align-items-center" id="newExistingSection" style="display: none;">
							<div class="flex-fill">
								<div style="display: flex; align-items: center; ">
									이용권그룹 신규/불러오기<span class="text-danger me-2">*</span>
									<select class="form-select event-group-type-select-width  form-select-sm" id="prev_event_group_status" name="prev_event_group_status">
										<option value="0">전체</option>
										<option value="Y" selected>정상판매</option>
										<option value="N">판매중지</option>
									</select>
								</div>
								<div class="text-body text-opacity-60">
									<div class="row">
										<div class="col-md-9 pt-2 d-flex align-items-center gap-3">
											<div class="form-check form-check-inline" style="display:none">
												<input class="form-check-input" type="radio" id="m_cate_new" name="new_existing" value="I" data-gtm-form-interact-field-id="0">
												<label class="form-check-label" for="m_cate_new">신규이용권그룹</label>
											</div>
											<div class="form-check form-check-inline" style="display:none">
												<input class="form-check-input" type="radio" id="m_cate_existing" name="new_existing" value="existing" data-gtm-form-interact-field-id="0">
												<label class="form-check-label" for="m_cate_existing">기존이용권그룹</label>
											</div>
											<div class="text-body text-opacity-60 align-items-center gap-2" id="m_cate_all" style="display:none">
												<select class="form-select event-group-select-width" id="select-required-1" name="prev_event_group">
													<option value="">신규생성</option>
													<?php foreach ($event_list as $r) : ?>
														<option value="<?php echo $r["SELL_EVENT_SNO"]; ?>"
																data-acc-rtrct-dv="<?php echo $r["ACC_RTRCT_DV"]; ?>"
																data-event-type="<?php echo $r["EVENT_TYPE"]; ?>"
																data-clas-min="<?php echo $r["CLAS_MIN"]; ?>"
																data-m-cate="<?php echo $r["M_CATE"]; ?>"
																data-event-desc="<?php echo $r["EVENT_DESC"]; ?>"
																data-1rd-cate-cd="<?php echo $r["M_CATE"] . $r["LOCKR_SET"]; ?>"
																data-2rd-cate-cd="<?php echo $r["EVENT_REF_SNO"]; ?>"
																data-grp-cate-set="<?php echo $r["GRP_CATE_SET"]; ?>"
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
						<div class="list-group-item align-items-center" id="eventTypeSection" style="display: none;">
							<div class="flex-fill">
								<div>기간제/횟수제 구분<span class="text-danger">*</span></div>
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
										<select class="form-select event-type-width" id="acc_rtrct_dv" name="acc_rtrct_dv" disabled="true">
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
										<select class="form-select use-per-day-select-width" id="domcy_poss_event_yn" name="domcy_poss_event_yn">
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
									<select class="form-select event-type-width me-2" id="lockr_knd" name="lockr_knd" >
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
						<div class="list-group-item align-items-center" id="eventGroupNameSection" style="display: none;">
							<div class="flex-fill">
								<div>이용권 그룹명<span class="text-danger">*</span></div>
								<div class="text-body text-opacity-60 d-flex align-items-center">
									<input class="form-control event-group-input me-2" type="text" name="event_group_name" style="flex: 1;" />
									<div class="form-check form-switch mb-0">
										<input class="form-check-input" type="checkbox" id="groupSellYn" checked="" data-gtm-form-interact-field-id="0">
										<label class="form-check-label" for="groupSellYn" >(정상판매)</label>
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
						<div class="list-group-item align-items-center" id="eventGroupDescSection" style="display: none;">
							<div class="flex-fill">
								<div>이용권 그룹 설명</div>
								<div class="text-body text-opacity-60"><textarea class="form-control" rows="3" name="event_group_desc"></textarea></div>
							</div>
						</div>
					</div>
				</div>
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
			<!-- END #general -->
		
			<!-- BEGIN #notifications -->
			<div id="notifications" class="mb-4 pb-3">
				<h4 class="d-flex align-items-center mb-2 mt-3">
					<iconify-icon class="fs-24px me-2 text-body text-opacity-75 my-n1" icon="solar:treadmill-round-bold-duotone"></iconify-icon> 
					판매 이용권
				</h4>
				<p>선택된 이용권 그룹의 판매 이용권을 만듭니다.</p>
				<div class="card" id="makeProduct" >
					<div class="list-group list-group-flush fw-bold">
						<div class="list-group-item align-items-center" style="display:none">
							<div class="flex-fill">
								<div>이용권그룹</div>
								<div class="text-body text-opacity-60">
									<input class="form-control event-group-input" type="text" name="event_group_name_display" disabled/>
								</div>
							</div>
						</div>
						<div class="list-group-item d-flex align-items-center">
							<div class="flex-fill">
								<div style="display:none">등록상품 리스트<span class="text-danger">*</span></div>
								<div class="widget-list rounded mb-4 py-3" style="display:none" >  <!-- data-category 추가 -->
									<!-- END widget-list-item -->
									<?php $index = 0; foreach ($event_list as $f) : ?>													<!-- BEGIN widget-list-item -->
									<!-- BEGIN widget-list-item -->
										<div class="widget-list-item"
											
											data-acc-rtrct-dv="<?php echo $f["ACC_RTRCT_DV"]; ?>"
											data-event-type="<?php echo $f["EVENT_TYPE"]; ?>"
											data-clas-min="<?php echo $f["CLAS_MIN"]; ?>"
											data-m-cate="<?php echo $f["M_CATE"]; ?>"
											data-2rd-cate-cd="<?php echo $f["2RD_CATE_CD"]; ?>"
											data-event-desc="<?php echo $f["EVENT_DESC"]; ?>"
											data-sell-event-nm="<?php echo $f["SELL_EVENT_NM"]; ?>"
											data-ref-count="<?php echo $f["ref_count"]; ?>"
											data-p-ref-count="<?php echo $f["p_ref_count"]; ?>"
											data-lockr-set="<?php echo $f["LOCKR_SET"]; ?>"
											data-pre-enter-min="<?php echo $f["PRE_ENTER_MIN"]; ?>"
											data-event-ref-sno="<?php echo $f["EVENT_REF_SNO"]; ?>"
											data-sell-event-sno="<?php echo $f["SELL_EVENT_SNO"]; ?>"
											data-domcy-poss-event-yn="<?php echo $f["DOMCY_POSS_EVENT_YN"]; ?>"
											data-lockr-knd="<?php echo $f["LOCKR_KND"]; ?>" 
											data-use-prod="<?php echo $f["USE_PROD"]; ?>" 
											data-use-unit="<?php echo $f["USE_UNIT"]; ?>" 
											data-clas-cnt="<?php echo $f["CLAS_CNT"]; ?>" 
											data-sell-s-date="<?php echo $f["SELL_S_DATE"]; ?>" 
											data-sell-e-date="<?php echo $f["SELL_E_DATE"]; ?>" 
											data-domcy-poss-event-yn="<?php echo $f["DOMCY_POSS_EVENT_YN"]; ?>" 
											data-domcy-cnt="<?php echo $f["DOMCY_CNT"]; ?>" 
											data-domcy-day="<?php echo $f["DOMCY_DAY"]; ?>" 
											data-use-per-day="<?php echo $f["USE_PER_DAY"]; ?>" 
											data-use-per-week="<?php echo $f["USE_PER_WEEK"]; ?>" 
											data-use-per-week-unit="<?php echo $f["USE_PER_WEEK_UNIT"]; ?>" 
											data-enter-from-time="<?php echo $f["ENTER_FROM_TIME"]; ?>" 
											data-enter-to-time="<?php echo $f["ENTER_TO_TIME"]; ?>" 
											data-sell-amt="<?php echo $f["SELL_AMT"]; ?>" 
											data-sell-yn="<?php echo $f["SELL_YN"]; ?>" 
											>


											
											<div class="widget-list-media"></div>
											<div class="widget-list-content">
												<h4 class="widget-list-title">
													<?php echo htmlspecialchars($f['SELL_EVENT_NM']) ?>
													<?php 
														if (!empty($f['SELL_S_DATE']) || !empty($f['SELL_E_DATE'])) {
															echo htmlspecialchars(" (판매기간: " . $f['SELL_S_DATE'] . " ~ " . $f['SELL_E_DATE'] . ")");
														}
													?>
												</h4>
												<p class="widget-list-desc">
													<?php 
														$content = '';
														switch ($f["M_CATE"]) {
															case 'PKG':
																$index = 0; // 인덱스 초기화
																foreach ($event_list as $g) : 
																	
																	$content = '';
																	if ($f['SELL_EVENT_SNO'] == $g['EVENT_REF_SNO']): 
																		// 이벤트명 앞에 줄바꿈 추가 조건
																		$title = ($index > 0 ? '<br>' : '');
																		$title  .= '<b>' . $g['SELL_EVENT_NM']. '</b> ';
																		switch ($g['M_CATE']) {
																			case 'PRV':
																				if (!empty($g['USE_PROD'])) {
																					$content = ($content != "" ? $content . ' / ' : ''); 
																					$content = $content . htmlspecialchars($g['USE_PROD']) . '개월';
																				}
																				
																				if (!empty($g['CLAS_CNT'])) {
																					$content = ($content != "" ? $content . ' / ' : ''); 
																					$content = $content . htmlspecialchars($g['CLAS_CNT']) . '회';
																				}
	
																				if (!empty($g['CLAS_MIN'])) {
																					$content = ($content != "" ? $content . ' / ' : ''); 
																					$content = $content . htmlspecialchars($g['CLAS_MIN']) . '분';
																				}
	
																				if (isset($sDef['ACC_RTRCT_DV2'][$g['ACC_RTRCT_DV']])) {
																					$content = ($content != "" ? $content . ' / ' : ''); 
																					$content = $content . $sDef['ACC_RTRCT_DV2'][$g['ACC_RTRCT_DV']];
																				}
	
																				if (!empty($g['USE_PER_DAY'])) {
																					$content = ($content != "" ? $content . ' / 일일 ' : '일일 '); 
																					$content = $content . htmlspecialchars($g['USE_PER_DAY']) . '회';
																				}
	
																				if (!empty($g['DOMCY_DAY'])) {
																					$content = ($content != "" ? $content . ' / 휴회기간 ' : '휴회기간 '); 
																					$content = $content . htmlspecialchars($g['DOMCY_DAY']) . '일';
																				} 
																				if (empty($f['DOMCY_POSS_EVENT_YN']) || $f['DOMCY_POSS_EVENT_YN'] == 'N' ) {
																					$content = ($content != "" ? $content . ' / 휴회불가 ' : '휴회불가 '); 
																				}
																				if (!empty($g['DOMCY_CNT'])) {
																					$content = ($content != "" ? $content . ' / 휴회횟수 ' : '휴회횟수 '); 
																					$content = $content . htmlspecialchars($g['DOMCY_CNT']) . '회';
																				}
																				if (!empty($g['WEEK_SELECT']) && $g['WEEK_SELECT'] != "") {
																					if($g['WEEK_SELECT'] =="월화수목금토일")
																					{
																						$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																					} else
																					{
																						$content = ($content != "" ? $content . ' / 이용요일: ' : '이용요일: '); 
																						$content = $content . htmlspecialchars($g['WEEK_SELECT']);
																					}
																				} else
																				{
																					$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																				}
																				echo $title . $content;
																				break;
																			case 'GRP':
																				if (!empty($g['USE_PROD'])) {
																					$content = ($content != "" ? $content . ' / ' : ''); 
																					$content = $content . htmlspecialchars($g['USE_PROD']) . '개월';
																				}
																				if (isset($sDef['ACC_RTRCT_DV2'][$g['ACC_RTRCT_DV']])) {
																					$content = ($content != "" ? $content . ' / ' : ''); 
																					$content = $content . $sDef['ACC_RTRCT_DV2'][$g['ACC_RTRCT_DV']];
																				}
	
																				if (!empty($g['USE_PER_DAY'])) {
																					$content = ($content != "" ? $content . ' / 일일 ' : '일일 '); 
																					$content = $content . htmlspecialchars($g['USE_PER_DAY']) . '회';
																				}
																				if (!empty($g['DOMCY_DAY'])) {
																					$content = ($content != "" ? $content . ' / 휴회기간 ' : '휴회기간 '); 
																					$content = $content . htmlspecialchars($g['DOMCY_DAY']) . '일';
																				} 
																				if (empty($f['DOMCY_POSS_EVENT_YN']) || $f['DOMCY_POSS_EVENT_YN'] == 'N' ) {
																					$content = ($content != "" ? $content . ' / 휴회불가 ' : '휴회불가 '); 
																				}
																				if (!empty($g['DOMCY_CNT'])) {
																					$content = ($content != "" ? $content . ' / 휴회횟수 ' : '휴회횟수 '); 
																					$content = $content . htmlspecialchars($g['DOMCY_CNT']) . '회';
																				}
																				if (!empty($g['WEEK_SELECT']) && $g['WEEK_SELECT'] != "") {
																					if($g['WEEK_SELECT'] =="월화수목금토일")
																					{
																						$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																					} else
																					{
																						$content = ($content != "" ? $content . ' / 이용요일: ' : '이용요일: '); 
																						$content = $content . htmlspecialchars($g['WEEK_SELECT']);
																					}
																				} else
																				{
																					$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																				}
																				echo $title . $content;
																				break;
																			case 'MBS':
																				if (!empty($g['USE_PROD'])) {
																					$content = ($content != "" ? $content . ' / ' : ''); 
																					$content = $content . htmlspecialchars($g['USE_PROD']) . '개월';
																				}
																				if (isset($sDef['ACC_RTRCT_DV2'][$g['ACC_RTRCT_DV']])) {
																					$content = ($content != "" ? $content . ' / ' : ''); 
																					$content = $content . $sDef['ACC_RTRCT_DV2'][$g['ACC_RTRCT_DV']];
																				}
	
																				if (!empty($g['USE_PER_DAY'])) {
																					$content = ($content != "" ? $content . ' / 일일 ' : '일일 '); 
																					$content = $content . htmlspecialchars($g['USE_PER_DAY']) . '회';
																				}
																				if (!empty($g['DOMCY_DAY'])) {
																					$content = ($content != "" ? $content . ' / 휴회기간 ' : '휴회기간 '); 
																					$content = $content . htmlspecialchars($g['DOMCY_DAY']) . '일';
																				}
																				if (empty($f['DOMCY_POSS_EVENT_YN']) || $f['DOMCY_POSS_EVENT_YN'] == 'N' ) {
																					$content = ($content != "" ? $content . ' / 휴회불가 ' : '휴회불가 '); 
																				}
																				if (!empty($g['DOMCY_CNT'])) {
																					$content = ($content != "" ? $content . ' / 휴회횟수 ' : '휴회횟수 '); 
																					$content = $content . htmlspecialchars($g['DOMCY_CNT']) . '회';
																				}
																				if (!empty($g['WEEK_SELECT']) && $g['WEEK_SELECT'] != "") {
																					if($g['WEEK_SELECT'] =="월화수목금토일")
																					{
																						$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																					} else
																					{
																						$content = ($content != "" ? $content . ' / 이용요일: ' : '이용요일: '); 
																						$content = $content . htmlspecialchars($g['WEEK_SELECT']);
																					}
																				} else
																				{
																					$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																				}
																				echo $title . $content;
																				break;
																			case 'OPT':
																				if (!empty($g['USE_PROD'])) {
																					$content = ($content != "" ? $content . ' / ' : ''); 
																					$content = $content . htmlspecialchars($g['USE_PROD']) . '개월';
																				}
																				if (isset($sDef['ACC_RTRCT_DV2'][$g['ACC_RTRCT_DV']])) {
																					$content = ($content != "" ? $content . ' / ' : ''); 
																					$content = $content . $sDef['ACC_RTRCT_DV2'][$g['ACC_RTRCT_DV']];
																				}
	
																				if (!empty($g['USE_PER_DAY'])) {
																					$content = ($content != "" ? $content . ' / 일일 ' : '일일 '); 
																					$content = $content . htmlspecialchars($g['USE_PER_DAY']) . '회';
																				}
																				if (!empty($g['DOMCY_DAY'])) {
																					$content = ($content != "" ? $content . ' / 휴회기간 ' : '휴회기간 '); 
																					$content = $content . htmlspecialchars($g['DOMCY_DAY']) . '일';
																				}
																				if (empty($f['DOMCY_POSS_EVENT_YN']) || $f['DOMCY_POSS_EVENT_YN'] == 'N' ) {
																					$content = ($content != "" ? $content . ' / 휴회불가 ' : '휴회불가 '); 
																				}
																				if (!empty($g['DOMCY_CNT'])) {
																					$content = ($content != "" ? $content . ' / 휴회횟수 ' : '휴회횟수 '); 
																					$content = $content . htmlspecialchars($g['DOMCY_CNT']) . '회';
																				}
																				if (!empty($g['WEEK_SELECT']) && $g['WEEK_SELECT'] != "") {
																					if($g['WEEK_SELECT'] =="월화수목금토일")
																					{
																						$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																					} else
																					{
																						$content = ($content != "" ? $content . ' / 이용요일: ' : '이용요일: '); 
																						$content = $content . htmlspecialchars($g['WEEK_SELECT']);
																					}
																				} else
																				{
																					$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																				}
																				echo $title . $content;
																				break;
																			default:
																				echo htmlspecialchars($r);
																				break;
																		}
																		$index++; // 다음 루프를 위해 인덱스 증가
																	endif;
																endforeach; 
																break;
															case 'PRV':
																if (!empty($f['USE_PROD'])) {
																	$content = ($content != "" ? $content . ' / ' : ''); 
																	$content = $content . htmlspecialchars($f['USE_PROD']) . '개월';
																}
																
																if (!empty($f['CLAS_CNT'])) {
																	$content = ($content != "" ? $content . ' / ' : ''); 
																	$content = $content . htmlspecialchars($f['CLAS_CNT']) . '회';
																}

																if (!empty($f['CLAS_MIN'])) {
																	$content = ($content != "" ? $content . ' / ' : ''); 
																	$content = $content . htmlspecialchars($f['CLAS_MIN']) . '분';
																}

																if (isset($sDef['ACC_RTRCT_DV2'][$f['ACC_RTRCT_DV']])) {
																	$content = ($content != "" ? $content . ' / ' : ''); 
																	$content = $content . $sDef['ACC_RTRCT_DV2'][$f['ACC_RTRCT_DV']];
																}

																if (!empty($f['USE_PER_DAY'])) {
																	$content = ($content != "" ? $content . ' / 일일 ' : '일일 '); 
																	$content = $content . htmlspecialchars($f['USE_PER_DAY']) . '회';
																}

																if (!empty($f['DOMCY_DAY'])) {
																	$content = ($content != "" ? $content . ' / 휴회기간 ' : '휴회기간 '); 
																	$content = $content . htmlspecialchars($f['DOMCY_DAY']) . '일';
																} 
																if (empty($f['DOMCY_POSS_EVENT_YN']) || $f['DOMCY_POSS_EVENT_YN'] == 'N' ) {
																	$content = ($content != "" ? $content . ' / 휴회불가 ' : '휴회불가 '); 
																}
																if (!empty($f['DOMCY_CNT'])) {
																	$content = ($content != "" ? $content . ' / 휴회횟수 ' : '휴회횟수 '); 
																	$content = $content . htmlspecialchars($f['DOMCY_CNT']) . '회';
																}
																if (!empty($f['WEEK_SELECT']) && $f['WEEK_SELECT'] != "") {
																	if($f['WEEK_SELECT'] =="월화수목금토일")
																	{
																		$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																	} else
																	{
																		$content = ($content != "" ? $content . ' / 이용요일: ' : '이용요일: '); 
																		$content = $content . htmlspecialchars($f['WEEK_SELECT']);
																	}
																} else
																{
																	$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																}
																echo $content;
																break;
															case 'GRP':
																if (!empty($f['USE_PROD'])) {
																	$content = ($content != "" ? $content . ' / ' : ''); 
																	$content = $content . htmlspecialchars($f['USE_PROD']) . '개월';
																}
																if (isset($sDef['ACC_RTRCT_DV2'][$f['ACC_RTRCT_DV']])) {
																	$content = ($content != "" ? $content . ' / ' : ''); 
																	$content = $content . $sDef['ACC_RTRCT_DV2'][$f['ACC_RTRCT_DV']];
																}

																if (!empty($f['USE_PER_DAY'])) {
																	$content = ($content != "" ? $content . ' / 일일 ' : '일일 '); 
																	$content = $content . htmlspecialchars($f['USE_PER_DAY']) . '회';
																}
																if (!empty($f['DOMCY_DAY'])) {
																	$content = ($content != "" ? $content . ' / 휴회기간 ' : '휴회기간 '); 
																	$content = $content . htmlspecialchars($f['DOMCY_DAY']) . '일';
																} 
																if (empty($f['DOMCY_POSS_EVENT_YN']) || $f['DOMCY_POSS_EVENT_YN'] == 'N' ) {
																	$content = ($content != "" ? $content . ' / 휴회불가 ' : '휴회불가 '); 
																}
																if (!empty($f['DOMCY_CNT'])) {
																	$content = ($content != "" ? $content . ' / 휴회횟수 ' : '휴회횟수 '); 
																	$content = $content . htmlspecialchars($f['DOMCY_CNT']) . '회';
																}
																if (!empty($f['WEEK_SELECT']) && $f['WEEK_SELECT'] != "") {
																	if($f['WEEK_SELECT'] =="월화수목금토일")
																	{
																		$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																	} else
																	{
																		$content = ($content != "" ? $content . ' / 이용요일: ' : '이용요일: '); 
																		$content = $content . htmlspecialchars($f['WEEK_SELECT']);
																	}
																} else
																{
																	$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																}
																echo $content;
																break;
															case 'MBS':
																if (!empty($f['USE_PROD'])) {
																	$content = ($content != "" ? $content . ' / ' : ''); 
																	$content = $content . htmlspecialchars($f['USE_PROD']) . '개월';
																}
																if (isset($sDef['ACC_RTRCT_DV2'][$f['ACC_RTRCT_DV']])) {
																	$content = ($content != "" ? $content . ' / ' : ''); 
																	$content = $content . $sDef['ACC_RTRCT_DV2'][$f['ACC_RTRCT_DV']];
																}

																if (!empty($f['USE_PER_DAY'])) {
																	$content = ($content != "" ? $content . ' / 일일 ' : '일일 '); 
																	$content = $content . htmlspecialchars($f['USE_PER_DAY']) . '회';
																}
																if (!empty($f['DOMCY_DAY'])) {
																	$content = ($content != "" ? $content . ' / 휴회기간 ' : '휴회기간 '); 
																	$content = $content . htmlspecialchars($f['DOMCY_DAY']) . '일';
																}
																if (empty($f['DOMCY_POSS_EVENT_YN']) || $f['DOMCY_POSS_EVENT_YN'] == 'N' ) {
																	$content = ($content != "" ? $content . ' / 휴회불가 ' : '휴회불가 '); 
																}
																if (!empty($f['DOMCY_CNT'])) {
																	$content = ($content != "" ? $content . ' / 휴회횟수 ' : '휴회횟수 '); 
																	$content = $content . htmlspecialchars($f['DOMCY_CNT']) . '회';
																}
																if (!empty($f['WEEK_SELECT']) && $f['WEEK_SELECT'] != "") {
																	if($f['WEEK_SELECT'] =="월화수목금토일")
																	{
																		$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																	} else
																	{
																		$content = ($content != "" ? $content . ' / 이용요일: ' : '이용요일: '); 
																		$content = $content . htmlspecialchars($f['WEEK_SELECT']);
																	}
																} else
																{
																	$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																}
																echo $content;
																break;
															case 'OPT':
																if (!empty($f['USE_PROD'])) {
																	$content = ($content != "" ? $content . ' / ' : ''); 
																	$content = $content . htmlspecialchars($f['USE_PROD']) . '개월';
																}
																if (isset($sDef['ACC_RTRCT_DV2'][$f['ACC_RTRCT_DV']])) {
																	$content = ($content != "" ? $content . ' / ' : ''); 
																	$content = $content . $sDef['ACC_RTRCT_DV2'][$f['ACC_RTRCT_DV']];
																}

																if (!empty($f['USE_PER_DAY'])) {
																	$content = ($content != "" ? $content . ' / 일일 ' : '일일 '); 
																	$content = $content . htmlspecialchars($f['USE_PER_DAY']) . '회';
																}
																if (!empty($f['DOMCY_DAY'])) {
																	$content = ($content != "" ? $content . ' / 휴회기간 ' : '휴회기간 '); 
																	$content = $content . htmlspecialchars($f['DOMCY_DAY']) . '일';
																} 
																if (empty($f['DOMCY_POSS_EVENT_YN']) || $f['DOMCY_POSS_EVENT_YN'] == 'N' ) {
																	$content = ($content != "" ? $content . ' / 휴회불가 ' : '휴회불가 '); 
																}
																if (!empty($f['DOMCY_CNT'])) {
																	$content = ($content != "" ? $content . ' / 휴회횟수 ' : '휴회횟수 '); 
																	$content = $content . htmlspecialchars($f['DOMCY_CNT']) . '회';
																}
																if (!empty($f['WEEK_SELECT']) && $f['WEEK_SELECT'] != "") {
																	if($f['WEEK_SELECT'] =="월화수목금토일")
																	{
																		$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																	} else
																	{
																		$content = ($content != "" ? $content . ' / 이용요일: ' : '이용요일: '); 
																		$content = $content . htmlspecialchars($f['WEEK_SELECT']);
																	}
																} else
																{
																	$content = ($content != "" ? $content . ' / 이용요일: 무제한' : '이용요일: 무제한'); 
																}
																echo $content;
																break;
															default:
																echo htmlspecialchars($r);
																break;
														}
													?>
												</p>
											</div>
											<div class="widget-list-action text-nowrap text-body text-opacity-50 fw-bold text-decoration-none">
												<span class="me-5"><?php echo number_format($f['SELL_AMT']); ?>원</span>
											</div>
											<div class="widget-list-media"></div>
										</div>
									<?php endforeach; ?>
									<!-- END widget-list-item -->
								</div>
								<div>판매 이용권 신규/불러오기<span class="text-danger">*</span></div>
								<div class="text-body text-opacity-60 d-flex align-items-center gap-3">
									<select class="form-select event-group-select-width" id="prev_event" >
										<option value="">신규생성</option>
										<?php foreach ($event_list as $r) : ?>
											<option value="<?php echo $r["SELL_EVENT_SNO"]; ?>"
													data-acc-rtrct-dv="<?php echo $r["ACC_RTRCT_DV"]; ?>"
													data-event-type="<?php echo $r["EVENT_TYPE"]; ?>"
													data-clas-min="<?php echo $r["CLAS_MIN"]; ?>"
													data-m-cate="<?php echo $r["M_CATE"]; ?>"
													data-2rd-cate-cd="<?php echo $r["2RD_CATE_CD"]; ?>"
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
						<div class="list-group-item d-flex align-items-center">
							<div class="flex-fill">
								<div style="display: flex; align-items: center; ">
									판매이용권(상품)명<span class="text-danger me-2">*</span>
									(판매수: <span id="sold_cnt">0</span>)
									<input type="hidden" id="p_sell_event_sno" value="<?php echo $sell_event_sno ?>">
								</div>
								<div class="text-body text-opacity-60 d-flex align-items-center gap-3">
									<input class="form-control event-input flex-grow-1" type="text" name="event_name" disabled/>
									<div class="form-check text-nowrap">
										<input class="form-check-input" type="checkbox" id="autoGenerateName" name="chk_auto_generate_name" checked>
										<label class="form-check-label" for="autoGenerateName">이름자동생성</label>
									</div>
									<div class="form-check form-switch mb-0">
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
											<!--<option value="">무제한</option>-->
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
								<div>입장 가능 시간대</div>
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
										<?php foreach ($sDef['ACC_RTRCT_WEEK_UNIT'] as $key => $value) : ?>
											<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
										<?php endforeach; ?>
									</select>
									<div class="text-body text-opacity-60 lesson-time-width position-relative d-flex align-items-center" id="use_cnt_per_week_section" style="visibility:hidden">
										<input class="form-control" type="number" name="use_per_week" step="1" maxlength="3" pattern="[0-9]*" inputmode="numeric" />
										<span class="unit-label">회</span>
									</div>
								</div>
							</div>
						</div>
						<div style="display:none">
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
						<div class="list-group-item d-flex align-items-center">
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
				<!-- 저장 버튼 중앙 정렬 및 길이 150px -->
				 <!-- 저장 버튼 중앙 정렬 및 길이 150px -->
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
			<!-- END #notifications -->
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
		let two_rd_changed = false;
		// 이용권 그룹 섹션
		const inputMode = document.getElementById("input_mode");  //이용권그룹 CRUD 상태 저장
		const saveCateButton = document.getElementById("saveCateButton");
		// 이용권 분류 섹션
		const radioButtons = document.querySelectorAll('input[name="m_cate"]');  //대분류
		const lockerContainer = document.querySelector(".locker-container");     //락커 여부 섹션
		const locker_enabled = document.getElementById("locker_enabled");

		// 이용권 그룹 불러오기 섹션
		const newExistingSection = document.getElementById("newExistingSection");
		const radioNew = document.getElementById("m_cate_new");
		const radioExisting = document.getElementById("m_cate_existing");
		const mCateAllDiv = document.getElementById("m_cate_all");  //기존 이용권 그룹 선택 섹션
		const prev_event_group_status = document.getElementById("prev_event_group_status");
		const groupSelectBox = document.querySelector('select[name="prev_event_group"]'); //기존 이용권 그룹 선택
		

		// 기간제/횟수제, 입장가능여부, 휴회가능여부, 락커그룹 섹션 
		const eventTypeSection = document.getElementById("eventTypeSection");
		const eventTypeSelect = document.getElementById("event_type");   //기간제/회수제 구분
		const enterAvailable = document.getElementById("acc_rtrct_dv");  //입장가능여부
		
		const prevEnterSection = document.getElementById("prev_enter_section");  //입장전 시간 섹션
		const prevEnterMinInput = document.querySelector('input[name="pre_enter_min"]');

		//휴회가능 여부
		const domcy_poss_event_yn = document.getElementById("domcy_poss_event_yn");

		//락커 구역 섹션
		const locker_select = document.getElementById("locker_select");
		const lockr_knd = document.getElementById("lockr_knd");  //락커 구역

		//레슨시간 섹션
		const lessonTimeContainer = document.getElementById("lessonTimeContainer");
		const clasMinInput = document.querySelector('input[name="clas_min"]');

		//이용권 그룹섹션
		const eventGroupNameSection = document.getElementById("eventGroupNameSection");
		const eventGroupNameInput = document.querySelector('input[name="event_group_name"]');
		const eventGroupDescTextarea = document.querySelector('textarea[name="event_group_desc"]');
		const groupSellYn = document.getElementById("groupSellYn");
		const groupSellYnLabel = document.querySelector('label[for="groupSellYn"]');

		//이용권 그룹 설명 섹션
		const eventGroupDescSection = document.getElementById("eventGroupDescSection");

		//이용권 그룹 저장 버튼 섹션
		const saveButtonSection = document.getElementById("saveButtonSection");
		const saveButton = document.getElementById("saveButton");  //이용권 그룹 저장버튼

		//이용권 그룹 수정 버튼 섹션
		const editButtonSection = document.getElementById("editButtonSection");
		const editButton = document.getElementById("editButton");  
		const copyButton = document.getElementById("copyButton");
		const deleteButton = document.getElementById("deleteButton");
		const cancelButton = document.getElementById("cancelButton");



		//이용권 섹션
		const makeProduct = document.getElementById("makeProduct");

		const prev_event = document.getElementById("prev_event");		
		const eventGroupNameDisplay = document.querySelector('input[name="event_group_name_display"]'); //이용권 그룹명
		const eventName = document.querySelector('input[name="event_name"]');  //중분류 명
		const autoGenerateNameCheckbox = document.getElementById("autoGenerateName"); // 변수 이름 변경
		
		const eventSellYn = document.getElementById("eventSellYn");
		const eventSellYnLabel = document.querySelector('label[for="eventSellYn"]');


		//이용기간
		const useProdInput = document.querySelector('input[name="use_prod"]');
		const useProdUnit = document.querySelector('select[name="use_prod_unit"]'); // 개월, 일 단위

		

		//이용권 가격
		const eventPrice = document.querySelector('input[name="event_price"]');

		//이용 횟수
		const lessionCntSection = document.getElementById("lesson-cnt-section");
		const clasCntInput = document.querySelector('input[name="clas_cnt"]');

		//이용 시간대
		const fromTimeInput = document.getElementById("fromTime");
		const toTimeInput = document.getElementById("toTime");
		const allTimeCheckbox = document.getElementById("all_time");

		
		//한주 이용 가능 횟수 섹션
		const use_cnt_per_week_section = document.getElementById("use_cnt_per_week_section");
		const use_per_week = document.querySelector('input[name="use_per_week"]');
		const select_use_per_week_unit = document.getElementById("use_per_week_unit");
		const unitLabel = use_cnt_per_week_section.querySelector('.unit-label');

		const locker_none_disp1 = document.getElementById("locker_none_disp1");  //요일 섹션
		const locker_none_disp2 = document.getElementById("locker_none_disp2");  //일일 이용가능 횟수 섹션
		const locker_none_disp3 = document.getElementById("locker_none_disp3");  //휴회 섹션
		const all_locker = document.getElementById("all_locker");

		//요일 선택 섹션
		const all_week = document.getElementById("all_week");
		const dayCheckboxes = document.querySelectorAll('.day-checkbox');

		//일일 이용 가능횟수
		const use_per_day = document.getElementById("use_per_day");

		//휴회
		const domcyCntInput = document.querySelector('input[name="domcy_cnt"]');
		const domcyDayInput = document.querySelector('input[name="domcy_day"]');

		//락커
		const locker_disp = document.getElementById("locker_disp");  //락커섹션
		//락커 번호대
		const lockr_number_from = document.querySelector('input[name="lockr_number_from"]');
		const lockr_number_to = document.querySelector('input[name="lockr_number_to"]');
		// 락커 체크박스 요소 가져오기
        const uniSexLocker = document.getElementById('uni_sex_locker');
        const manWomanLocker = document.getElementById('man_woman_locker');
		
		//판매기간
		const sellDateDateRange = document.querySelector('input[name="sell-date-daterange"]');
		const btn_cal = document.getElementById("btn_cal");
		
		//판매 이용권 저장 버튼 섹션
		const saveProductSection = document.getElementById("saveProductSection");
		const saveProductButton = document.getElementById("saveProductButton");

		//판매 이용권 수정 버튼 섹션
		const editProductSection = document.getElementById("editProductSection");
		const editProductButton = document.getElementById("editProductButton");
		const cancelProductButton = document.getElementById("cancelProductButton");
		const copyProductButton = document.getElementById("copyProductButton");
		const deleteProductButton = document.getElementById("deleteProductButton");

		//판매 이용권 번호
		const p_sell_event_sno = document.getElementById("p_sell_event_sno");
		//판매수
		const sold_cnt = document.getElementById("sold_cnt");

		// 사용 안하는 파트
		const classTimeCheckSection = document.getElementById("class_time_check_section");
		const classTimeSection = document.getElementById("class_time_section");
		const enterTimeSection = document.getElementById("enter_time_section");
		const prevStudyMinInput = document.querySelector('input[name="prev_study_min"]');

		const one_rd_cate_cd = document.getElementById("1rd_cate_cd");
		const two_rd_cate_cd = document.getElementById("2rd_cate_cd");

		one_rd_cate_cd.addEventListener("change", cate1_ch);
		two_rd_cate_cd.addEventListener("change", cate2_ch);

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

        // 체크박스 상태 변경 감지
        uniSexLocker.addEventListener('change', lockerGenderCheckBoxChanged);
        manWomanLocker.addEventListener('change', lockerGenderCheckBoxChanged);
        prev_event_group_status.addEventListener('change', function(e){
			filterGroupSelectOptions();
			groupSelectBoxChange();
		});

		lockr_knd.addEventListener("change", function(e){
			checkSelections();
		});

		function lockerGenderCheckBoxChanged() {

			// "혼용"과 "남녀"가 동시에 체크되면 "전체" 체크
            if (!uniSexLocker.checked && !manWomanLocker.checked) {
				uniSexLocker.checked = true;
				manWomanLocker.checked = true;
            }
        }
		saveCateButton.addEventListener("click", fnCateSave);

		copyProductButton.addEventListener("click", function(e){
			eventSelectBoxChange();
			productModeChange("I");
			eventName.removeAttribute("disabled");
			cancelProductButton.style.display="";
			eventName.value = eventName.value + " 복사";
			// autoGenerateNameCheckbox.checked = false;
			p_sell_event_sno.value = "";
			eventSellYn.setAttribute("disabled", "true");
			sold_cnt.textContent = "0";
		});
		cancelProductButton.addEventListener("click", function(e){
			eventSelectBoxChange();
			productModeChange("N");
			autoGenerateNameCheckbox.checked = true;
		});

		editProductButton.addEventListener("click", function(e){
			productModeChange("E");
		});
		
		const days = {
					"mon": "월",
					"tue": "화",
					"wed": "수",
					"thu": "목",
					"fri": "금",
					"sat": "토",
					"sun": "일"
		};

		deleteProductButton.addEventListener("click", fnDeleteEvent);
		

		init();

		// 초기 상태 설정: 이용권 분류 선택만 표시, 저장 버튼 및 요소 비활성화
		function init()
		{
			newExistingSection.style.display = "none";
			eventTypeSection.style.display = "none";
			eventGroupNameSection.style.display = "none";
			lessonTimeContainer.style.display = "none";
			eventGroupDescSection.style.display = "none";
			saveButtonSection.style.display = "flex"; // 초기 표시
			editButtonSection.style.display = "none"; // 초기 숨김
			cancelButton.style.display = "none"; // 초기 숨김
			saveButton.classList.add("disabled");
			eventTypeSelect.setAttribute("disabled", "true");
			////enterAvailable.setAttribute("disabled", "true");

			//domcy_poss_event_yn.setAttribute("disabled", "true");
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
			generateEventName();
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
		useProdUnit.addEventListener("change", function(e){
			/*if(this.value == "")
			{
				useProdInput.value = "";
			}*/
			generateEventName();
		});
		fromTimeInput.addEventListener("input", generateEventName);
		toTimeInput.addEventListener("input", generateEventName);
		saveProductButton.addEventListener("click", fnEventSave);
		domcy_poss_event_yn.addEventListener("change", checkSelections);
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

		// copyButton 이벤트
		copyButton.addEventListener("click", function(e) {
			if(iCheck.radioGet("m_cate") != "OPT" && iCheck.radioGet("m_cate") != "PRV")
			{
				eventTypeSelect.removeAttribute("disabled");
				domcy_poss_event_yn.removeAttribute("disabled");
			}  else if(iCheck.radioGet("m_cate") != "OPT")
			{
				domcy_poss_event_yn.removeAttribute("disabled");
			}
			//.removeAttribute("disabled");
			//domcy_poss_event_yn.removeAttribute("disabled");
			lockr_knd.removeAttribute("disabled");
			prevEnterMinInput.removeAttribute("disabled");
			eventGroupNameInput.removeAttribute("disabled");
			clasMinInput.removeAttribute("disabled");
			eventGroupDescTextarea.removeAttribute("disabled");
			radioNew.checked = true;
			editButton.style.display = "none";
			copyButton.style.display = "none";
			deleteButton.style.display = "none";
			saveButtonSection.style.display = "flex";
			eventGroupNameInput.value = eventGroupNameInput.value + " 복사";
			eventGroupNameDisplay.value = eventGroupNameInput.value ;
			saveProductButton.classList.add("disabled");
			saveButton.classList.remove("disabled");
			cancelButton.style.display="";
			inputMode.value = "I";
			groupSellYn.setAttribute("disabled", true);
		});

		// 취소 버튼 클릭 이벤트
		cancelButton.addEventListener("click", cancelClick);
		// 수정 버튼 클릭 이벤트
		editButton.addEventListener("click", function(e) {
					
			if(iCheck.radioGet("m_cate") != "OPT")
			{
				eventTypeSelect.removeAttribute("disabled");
				domcy_poss_event_yn.removeAttribute("disabled");
			} 
			lockr_knd.removeAttribute("disabled");
			prevEnterMinInput.removeAttribute("disabled");
			eventGroupDescTextarea.removeAttribute("disabled");
			clasMinInput.removeAttribute("disabled");
			eventGroupNameInput.removeAttribute("disabled");
			editButtonSection.style.display = "none";
			cancelButton.style.display = "";
			saveButtonSection.style.display = "flex";
			saveButton.classList.remove("disabled");
			inputMode.value = "U";
			groupSellYn.removeAttribute("disabled");
			saveProductButton.classList.add("disabled");
			editProductButton.classList.add("disabled");
			copyProductButton.classList.add("disabled");
			deleteProductButton.classList.add("disabled");
			
		});

		// 이용권 분류 선택 시
		radioButtons.forEach(radio => {
			radio.addEventListener("change", function () {
				
				newExistingSection.style.display = "flex";
				groupSelectBox.value = "";
				updateLockerVisibility();

				radioNew.checked = false;
				radioExisting.checked = false;

				mCateAllDiv.style.display = "none";
				eventTypeSection.style.display = "none";
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
				initEventGroupFields();
				eventTypeSelect.setAttribute("disabled", "true");
				////enterAvailable.setAttribute("disabled", "true");

				//domcy_poss_event_yn.setAttribute("disabled", "true");
				lockr_knd.setAttribute("disabled", "true");
				prevEnterMinInput.setAttribute("disabled", "true");
				eventGroupNameInput.setAttribute("disabled", "true");
				clasMinInput.setAttribute("disabled", "true");
				eventGroupDescTextarea.setAttribute("disabled", "true");
				groupSelectBox.dispatchEvent(new Event("change"));

				if(this.value != "OPT")
				{
					locker_disp.style.display="none";
					eventTypeSelect.removeAttribute("disabled");
					if(this.value == "PRV")
					{
						eventTypeSelect.setAttribute("disabled", "true");
						eventTypeSelect.value="20";
					}
				} else
				{
					eventTypeSelect.setAttribute("disabled", "true");
					eventTypeSelect.value="10";
					checkSelections();
				}
				if (lockerContainer.style.display == "none")
				{
					locker_enabled.checked = false;
				}
				$('#1rd_cate_cd').val('');
				$('#2rd_cate_cd').empty();
				$('#2rd_cate_cd').append("<option value=''>중분류 선택</option>");
				$('#2rd_cate_cd').val('');

				eventTypeSection.style.display="flex";
				enterVisible();
				
				lockerCheckVisible();
				
				filterGroupSelectOptions();
			});
		});

		function cancelClick(){
			const selectedValue = groupSelectBox.value;
			inputMode.value = "I";
			groupSellYn.setAttribute("disabled", true);
			let cnt = parseInt(sold_cnt.textContent);
			if(cnt == 0)
			{
				saveProductButton.classList.remove("disabled");
				editProductButton.classList.remove("disabled");
				deleteProductButton.classList.remove("disabled");
			}
			
			copyProductButton.classList.remove("disabled");
			
			copyButton.style.display = "";
			if (selectedValue) {
				const selectedOption = groupSelectBox.querySelector(`option[value="${selectedValue}"]`);
				if (selectedOption) {
					eventTypeSelect.value = selectedOption.getAttribute("data-event-type") || "0";
					enterAvailable.value = selectedOption.getAttribute("data-acc-rtrct-dv") || "0";
					eventGroupNameInput.value = selectedOption.getAttribute("data-sell-event-nm") || "";
					eventGroupDescTextarea.value = selectedOption.getAttribute("data-event-desc") || "";
					const refCount = selectedOption.getAttribute("data-ref-count") || "";
					clasMinInput.value = selectedOption.getAttribute("data-clas-min") || "";
					var m_cate = iCheck.radioGet("m_cate");
					if (m_cate === "PRV" || m_cate === "GRP") {
						lessonTimeContainer.style.display = "flex";
					} else {
						lessonTimeContainer.style.display = "none";
						clasMinInput.value = "";
					}		
					
					if(refCount > 0)
					{

					} else
					{
						editButton.style.display="";
						deleteButton.style.display = "";
					}
				}
			} else {
				initEventGroupFields();
			}

			eventTypeSelect.setAttribute("disabled", "true");
			//enterAvailable.setAttribute("disabled", "true");

			//domcy_poss_event_yn.setAttribute("disabled", "true");
			lockr_knd.setAttribute("disabled", "true");
			prevEnterMinInput.setAttribute("disabled", "true");
			eventGroupNameInput.setAttribute("disabled", "true");
			domcy_poss_event_yn.setAttribute("disabled", "true");
			clasMinInput.setAttribute("disabled", "true");
			eventGroupDescTextarea.setAttribute("disabled", "true");
			saveButtonSection.style.display = "none";
			editButtonSection.style.display = "flex";
			cancelButton.style.display = "none";
			saveButton.classList.add("disabled");
			makeProduct.style.display = "";
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
			const mCate = document.querySelector('input[name="m_cate"]:checked')?.value || "";
			const isEventTypeSelected = eventTypeSelect.value !== "0";
			const isAccRtrctSelected = enterAvailable.value !== "0";
			const is_domcy_poss_event_yn_selected = domcy_poss_event_yn.value !== "0";
			const lockerValue  = locker_enabled && locker_enabled.checked ? "Y" : "N";
			// 만약 횟수제(eventTypeSelect.value가 '20'이 아닌 경우) 레슨 횟수 영역 숨기기
			if (eventTypeSelect.value != "20") {
				lessionCntSection.style.display = "none";
			} else {
				lessionCntSection.style.display = "";
			}

			enterVisible();
			
			// [2] 기본 조건: 신규 그룹이고 event_type/acc_rtrct_dv(입장가능여부)가 선택됨
			if (isEventTypeSelected && isAccRtrctSelected && is_domcy_poss_event_yn_selected  && mCate != "OPT" || mCate == "OPT" && lockerValue == "N" 
			     || mCate == "OPT" && lockerValue == "Y" && lockr_knd.value != "") {
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

		eventTypeSelect.addEventListener("change", checkSelections);
		enterAvailable.addEventListener("change", checkSelections);
		eventGroupNameInput.addEventListener("input", saveVisible);
		clasMinInput.addEventListener("input", saveVisible);

		// 기존: prev_event_group 선택 시
		groupSelectBox.addEventListener("change", groupSelectBoxChange);
		prev_event.addEventListener("change", eventSelectBoxChange);

		function eventSelectBoxChange(){
			const selectedOption = prev_event.options[prev_event.selectedIndex];
			const groupSelectedOption = groupSelectBox.options[groupSelectBox.selectedIndex];
			const eventType = groupSelectedOption.getAttribute("data-event-type") || "0";
			const accRtrctDv = groupSelectedOption.getAttribute("data-acc-rtrct-dv") || "0";
			const clasMin = groupSelectedOption.getAttribute("data-clas-min") || "";
			const lockrSet = groupSelectedOption.getAttribute("data-lockr-set")  || "N";
			const lockr_knd_value = groupSelectedOption.getAttribute("data-lockr-knd") || "";
			const preEnterMin =  groupSelectedOption.getAttribute("data-pre-enter-min")  || "";
			const domcyPossEventYn = groupSelectedOption.getAttribute("data-domcy-poss-event-yn") || "";
			const eventDesc = groupSelectedOption.getAttribute("data-event-desc") || "";
			const mCate = selectedOption.getAttribute("data-m-cate") || "";
			const sellEventNm = selectedOption.getAttribute("data-sell-event-nm") || "";
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
			
			makeProduct.style.display = "";
			
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

			if(selectedOption.value != "")
			{
				productModeChange("N");
			} else
			{
				productModeChange("I");
			}
			
			

			checkSelections();
			loadSoldCnt();
			if(sell_event_sno != "")
				eventSellYn.removeAttribute("disabled");
			else
				eventSellYn.setAttribute("disabled", "true");
		}

		function groupSelectBoxChange() {
			
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
			////enterAvailable.setAttribute("disabled", "true");

			//domcy_poss_event_yn.setAttribute("disabled", "true");
			lockr_knd.setAttribute("disabled", "true");
			prevEnterMinInput.setAttribute("disabled", "true");
			eventGroupNameInput.setAttribute("disabled", "true");
			clasMinInput.setAttribute("disabled", "true");
			eventGroupDescTextarea.setAttribute("disabled", "true");
			eventTypeSection.style.display = "flex";
			
			
			if(selectedOption.dataset['2rdCateCd'] )
			{ 
				$('#1rd_cate_cd').val(selectedOption.dataset['1rdCateCd']);
				var cate1 = one_rd_cate_cd.value;
				var params = "cate1="+cate1;
				jQuery.ajax({
					url: '/teventmain/ajax_cate2_list',
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
							let prev_val = two_rd_cate_cd.value;
							$('#2rd_cate_cd').empty();
							$('#2rd_cate_cd').append("<option value=''>중분류 선택</option>");
							json_result['cate2'].forEach(function (r,index) {
								$('#2rd_cate_cd').append("<option data-clasdv='" + r['CLAS_DV'] + "' value='" + r['2RD_CATE_CD'] + "'>"+r['CATE_NM']+"</option>");
							});
							if(!two_rd_changed)
							{
								$('#2rd_cate_cd').val(selectedOption.dataset['2rdCateCd']) ;
								$("#two_cate_mode").val("saved");
								saveCateButton.style.visibility = "hidden";
							} else
							{
								$("#2rd_cate_cd").val(prev_val);
							}
							two_rd_changed = false;
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
			} else
			{
				if(!two_rd_changed)
				{
					$('#2rd_cate_cd').val('');
					$("#two_cate_mode").val("new");
				}
				two_rd_changed = false;
			}
			if (groupSelectBox.value !== "") {  //기존 이용권
				eventGroupNameSection.style.display = "flex";
				eventGroupDescSection.style.display = "flex";
				saveButtonSection.style.display = "none";
				editButtonSection.style.display = "flex";
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
				makeProduct.style.display = "";
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

				domcy_poss_event_yn.setAttribute("disabled", "true");
				
			} else {  //신규 이용권
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
				

				if(m_cate != "OPT")
				{
					locker_disp.style.display="none";
					eventTypeSelect.removeAttribute("disabled");
					enterAvailable.value = "00";
					if(m_cate == "PRV")
					{
						eventTypeSelect.setAttribute("disabled", "true");
						eventTypeSelect.value="20";
					}
					domcy_poss_event_yn.removeAttribute("disabled");
					domcy_poss_event_yn.value = "0";
				} else
				{
					enterAvailable.value = "99";
					eventTypeSelect.setAttribute("disabled", "true");
					eventTypeSelect.value="10";
					domcy_poss_event_yn.setAttribute("disabled", "true");
					domcy_poss_event_yn.value = "N";
				}
				//------------------

				mCateAllDiv.style.display = "flex";
				eventGroupNameSection.style.display = "none";
				eventGroupNameInput.value = "";
				lessonTimeContainer.style.display = "none";
				clasMinInput.value = "";
				eventGroupDescSection.style.display = "none";
				eventGroupDescTextarea.value = "";

				//enterAvailable.removeAttribute("disabled");
				//domcy_poss_event_yn.removeAttribute("disabled");
				lockr_knd.removeAttribute("disabled");
				prevEnterMinInput.removeAttribute("disabled");
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
			const lockerValue  = locker_enabled && locker_enabled.checked ? "Y" : "N";
			var m_cate = iCheck.radioGet("m_cate");
			if(m_cate == "OPT" && lockerValue == "Y" && lockr_knd.value != "")
			{
				checkSelections();
			}
		}
		
		function initMakeProductFields() {
			saveProductButton.classList.add("disabled");
			if(prev_event.value == "")
			{
				
			}
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
				option.style.display = ( eventRefSno ===  groupSelectBox.value && sellEventSno !== groupSelectBox.value) ? "" : "none"; 
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

		domcy_poss_event_yn.addEventListener("change", function(e){
			if(this.value == "Y")
			{
				locker_none_disp3.style.display="flex";
			} else
			{
				locker_none_disp3.style.display="none";
			}
		});
		clasCntInput.addEventListener('input', function (e) {
			this.value = this.value.replace(/[^0-9]/g, '');
			if (this.value.length > 3) {
				this.value = this.value.slice(0, 3);
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
			/*
			if(this.value != "0" && useProdUnit.value == "")
			{
				if(useProdUnit.value == "")
				useProdUnit.value = "M";
			}
			if (this.value == "0")
			{
				this.value = "";
				useProdUnit.value = "";
			} */
			generateEventName();
		});

		use_per_week.addEventListener('input', function (e) {
			this.value = this.value.replace(/[^0-9]/g, '');
			if (this.value.length > 3) {
				this.value = this.value.slice(0, 3);
			}
		});

		$("#fromTime").timepicker({
			showMeridian: true,
			minuteStep: 5,
			defaultTime: false,
			icons: {
				up: 'fa fa-chevron-up',
				down: 'fa fa-chevron-down'
			}
		}).on('show.timepicker', function(e) {
			// toTime 값 확인
			const toTimeVal = $("#toTime").val();
			if (!toTimeVal) {
				const fromTimeVal = $("#fromTime").val();
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

		$("#toTime").timepicker({
			showMeridian: true,
			minuteStep: 5,
			defaultTime: false,
			icons: {
				up: 'fa fa-chevron-up',
				down: 'fa fa-chevron-down'
			}
		}).on('show.timepicker', function(e) {
			// fromTime 값 확인
			const fromTimeVal = $("#fromTime").val();
			if (!fromTimeVal) {
				const toTimeVal = $("#toTime").val();
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

		$('#sell-date-daterange').daterangepicker({
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
			$("#sell-date-daterange input").val(start.format('YYYY-MM-DD') + ' ~ ' + end.format('YYYY-MM-DD'));
			generateEventName();
		});

		function lockerCheckVisible()
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
				if (!clasMinInput.value.trim() || clasMinInput.value.trim() == "0") {
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

			// if(one_rd_cate_cd.value.trim())
			// {
			// 	alertToast('error',"대분류를 선택해주세요.");
			// 	one_rd_cate_cd.focus();
			// 	return false;
			// }

			// if(two_rd_cate_cd.value.trim())
			// {
			// 	alertToast('error',"중분류를 선택하고 분류를 저장해주세요.");
			// 	two_rd_cate_cd.focus();
			// 	return false;
			// }

			// 모든 검사 통과 시 true
			return true;
		}

		function setGroupSelectBox(json_result) {
			var groupSelected =  json_result["sell_event_sno"];
			if(groupSelected == "" || groupSelected == null  ){
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
				option.setAttribute('data-1rd-cate-cd', r['1RD_CATE_CD'] ?? '');
				option.setAttribute('data-2rd-cate-cd', r['2RD_CATE_CD'] ?? '');
				option.setAttribute('data-grp-cate-set', r.GRP_CATE_SET ?? '');
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
			groupSelectBoxChange();
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
			tblParam.cate1 = one_rd_cate_cd.value;
			tblParam.cate2 = two_rd_cate_cd.value;
			tblParam.grp_cate_set = 
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
						radioExisting.checked = true;
						mCateAllDiv.style.display = "";
						editButton.classList.remove("disabled");
						copyButton.classList.remove("disabled");
						deleteButton.classList.remove("disabled");
						cancelClick();
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

			
		function productSaveIsValid()
		{
			// eventName.value 필수 입력
			if (!eventName.value.trim()) {
				alertToast('error',"이벤트명을 입력해주세요.");
				eventName.focus();
				return false;
			}
			
			// useProdInput.value 필수 입력
			if ((!useProdInput.value.trim() || useProdInput.value == "0") /* && useProdUnit.value != ""*/) {
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
			
			// 모든 검증 통과 시 true 반환
			return true;			
		}

		// 이용권 상품을 저장
		function fnEventSave() {
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
			tblParam.cate1 = one_rd_cate_cd.value;
			tblParam.cate2 = two_rd_cate_cd.value;
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
						var tempList = {};
							tempList.event_list = json_result['event_list'];
							setGroupSelectBox(tempList);
						if(p_sell_event_sno.value == "")
						{
							p_sell_event_sno.value =tblParam.event_ref_sno; 
						}
						
						setEventSelectBox(json_result);
						eventSelectBoxChange();
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
								var tempList = {};
								tempList.event_list = json_result['event_list'];
								tempList.sell_event_sno = param.event_ref_sno; 
								setGroupSelectBox(tempList);
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
			// 모든 요일 체크박스 요소 가져오기
			const dayCheckboxes = document.querySelectorAll('.day-checkbox');
			const allWeekCheckbox = document.getElementById('all_week');

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
		

		setProduct();

		function setProduct()
		{
			const itemObject = <?= json_encode($itemObject, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
			const groupObject = <?= json_encode($groupObject, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
			if(groupObject['SELL_EVENT_SNO'])
			{
				
				// 해당 값과 같은 value를 가진 라디오 버튼을 찾아 checked
				radioButtons.forEach(radio => {
					if (radio.value === groupObject["M_CATE"]) {
						radio.checked = true;
					}
				});

				if(groupObject["M_CATE"] == "OPT")
				{
					lockerContainer.style.display = "flex";
					if(groupObject["LOCKR_SET"] == "Y")
					{
						locker_enabled.checked = true;
					}				
				}

				newExistingSection.style.display="";
				mCateAllDiv.style.display="flex";
				filterGroupSelectOptions();
				groupSelectBox.value = groupObject['SELL_EVENT_SNO'];
				
				radioExisting.checked = true;
				mCateAllDiv.style.display = "";
				editButton.classList.remove("disabled");
				copyButton.classList.remove("disabled");
				deleteButton.classList.remove("disabled");
				cancelClick();
				updateLockerVisibility();
				if (lockerContainer.style.display != "none")
				{
					locker_enabled.checked = (itemObject['LOCKR_SET']=="Y");
				}
				
				saveButtonSection.style.display = "none";
				editButtonSection.style.display = "flex";
				saveButton.classList.remove("disabled");
				editButton.classList.remove("disabled");
				copyButton.classList.remove("disabled");
				deleteButton.classList.remove("disabled");
				
				
				inputMode.value = "U";

				eventTypeSelect.value = groupObject['EVENT_TYPE'];
				enterAvailable.value = groupObject['ACC_RTRCT_DV'];
				eventGroupNameInput.value = groupObject['SELL_EVENT_NM'];
				eventGroupDescTextarea.value = groupObject['EVENT_DESC'];
				prevEnterMinInput.value = groupObject['PRE_ENTER_MIN'];
				if (lessonTimeContainer.style.display != "none")
				{
					clasMinInput.value = itemObject['CLAS_MIN'];
				}
				
				makeProduct.style.display = "";
				editButton.style.display = "";
				copyButton.style.display = "";
				deleteButton.style.display = "";
				const mCate = groupObject['M_CATE'];
				if (mCate === "PRV" || mCate === "GRP") {
					lessonTimeContainer.style.display = "flex";
				} else {
					lessonTimeContainer.style.display = "none";
					clasMinInput.value = "";
					if(mCate === "OPT")
					{
						if(groupObject["LOCKR_SET"] == "Y")
						{
							locker_select.style.display = "";
							lockr_knd.value = groupObject["LOCKR_KND"];
						} else
						{
							locker_select.style.display = "none";
						}
					}
				}
				eventTypeSection.style.display="flex";
				eventGroupNameDisplay.value = groupObject['SELL_EVENT_NM'];
				const selectedOption = groupSelectBox.options[groupSelectBox.selectedIndex];
				const refCount = parseInt(selectedOption.getAttribute("data-ref-count") || "0", 10);
				if (refCount > 0) {
					editButton.style.display="none";
					deleteButton.style.display="none";
				} else {
					editButton.style.display="";
					deleteButton.style.display="";
				}
				eventName.value=itemObject['SELL_EVENT_NM'];

				useProdInput.value = itemObject['USE_PROD'];
				useProdUnit.value =  itemObject['USE_UNIT'];
				clasCntInput.value = itemObject['CLAS_CNT'];
				domcyDayInput.value = itemObject['DOMCY_DAY'];
				domcyCntInput.value = itemObject['DOMCY_CNT'];
				setWeekDaysFromItemObject(itemObject);
				p_sell_event_sno.value = itemObject['SELL_EVENT_SNO'];
				eventSellYn.removeAttribute("disabled");
				if(itemObject['USE_PER_WEEK_UNIT'] != '' && itemObject['USE_PER_WEEK_UNIT'] != "0")
				{
					use_per_week.value = itemObject['USE_PER_WEEK'];
					use_per_week_unit.value = itemObject['USE_PER_WEEK_UNIT'];
				}
					use_per_day.value = itemObject['USE_PER_DAY'];

				sellDateDateRange.value = (itemObject['SELL_S_DATE'] == null ? "": itemObject['SELL_S_DATE'] ) +
				                          (itemObject['SELL_S_DATE'] != null || itemObject['SELL_E_DATE'] != null ? " ~ " : "") + (itemObject['SELL_E_DATE'] == null ? "": itemObject['SELL_E_DATE'] ) ;
				domcy_poss_event_yn.value = itemObject['DOMCY_POSS_EVENT_YN'];
				if(domcy_poss_event_yn.value == "Y")
				{
					locker_none_disp3.style.display="flex";
				} else
				{
					locker_none_disp3.style.display="none";
				}
				fromTimeInput.value = convertTo12Hour(itemObject['ENTER_FROM_TIME']);
				toTimeInput.value = convertTo12Hour(itemObject['ENTER_TO_TIME']);
				allTimeCheckbox.checked = !(fromTimeInput.value || toTimeInput.value);
				let value = itemObject['SELL_AMT'].replace(/[^0-9]/g, '');
				eventPrice.value = Number(value).toLocaleString('ko-KR');

				productModeChange("N");
				loadSoldCnt();
				checkSelections();
				filterEventSelectOptions();
				groupSellYn.removeAttribute("disabled");
				prev_event.value = itemObject['SELL_EVENT_SNO'];
				
			} else
			{
				filterEventSelectOptions();
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

		function loadSoldCnt()
		{
			var param = {};
			param.sell_event_sno = p_sell_event_sno.value;

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
					/*
					if(cnt == 0)
					{
						saveProductButton.classList.remove("disabled");
						editProductButton.classList.remove("disabled");
						deleteProductButton.classList.remove("disabled");
					} else
					{
						saveProductButton.classList.add("disabled");
						editProductButton.classList.add("disabled");
						deleteProductButton.classList.add("disabled");
					}*/
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

		// 대분류 선택시 -> 중분류 동적 생성
		function cate1_ch()
		{
			saveCateButton.style.visibility = "hidden";
			var cate1 = one_rd_cate_cd.value;
			var params = "cate1="+cate1;
			jQuery.ajax({
				url: '/teventmain/ajax_cate2_list',
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
						$('#2rd_cate_cd').empty();
						$('#2rd_cate_cd').append("<option value=''>중분류 선택</option>");
						json_result['cate2'].forEach(function (r,index) {
							$('#2rd_cate_cd').append("<option data-clasdv='" + r['CLAS_DV'] + "' value='" + r['2RD_CATE_CD'] + "'>"+r['CATE_NM']+"</option>");
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
				location.href='/tlogin';
				return;
			});
		}

		// 중분류 선택시 -> 
		function cate2_ch()
		{
			two_rd_changed = true;
			var cate2 = two_rd_cate_cd.value;
			const selectedOption = groupSelectBox.options[groupSelectBox.selectedIndex];
			$("#two_cate_mode").val("new");
			if(groupSelectBox.value != "" && cate2 != selectedOption.dataset['2rdCateCd'] && cate2 != '')
			{
				saveCateButton.style.visibility = "visible";
			} else
			{
				saveCateButton.style.visibility = "hidden";
			}
			const options = document.querySelectorAll('#prev_event option');
			let matchedOption = null;
			for (let option of options) {
				if (option.dataset['2rdCateCd'] === cate2) {
					matchedOption = option;
					break; // 첫 번째 항목을 찾으면 루프 종료
				}
			}
			if (matchedOption) {

				radioButtons.forEach(radio => {
					if (radio.value === matchedOption.dataset.mCate) {
						radio.checked = true;
					}
				});

				if(matchedOption.dataset.mCate == "OPT")
				{
					lockerContainer.style.display = "flex";
					if(matchedOption.dataset.lockrSet == "Y")
					{
						locker_enabled.checked = true;
					}				
				}

				newExistingSection.style.display="";
				mCateAllDiv.style.display="flex";
				filterGroupSelectOptions();
				groupSelectBoxChange();

			}

			
		}

		// 이용권 분류를 저장
		function fnCateSave() {
			var cate1 = one_rd_cate_cd.value;
			var cate2 = two_rd_cate_cd.value;

			const one_rd_cate_cd_option = one_rd_cate_cd.querySelector(`option[value="${one_rd_cate_cd.value}"]`);


			var tblParam = {};
			tblParam.cate1 = cate1;
			tblParam.cate2 = cate2;
			tblParam.grp_cate_set = one_rd_cate_cd_option.dataset.grpCateSet;
			tblParam.event_ref_sno = groupSelectBox.value;
			const selectedOption = groupSelectBox.querySelector(`option[value="${groupSelectBox.value}"]`);
			selectedOption.setAttribute("data-1rd-cate-cd", cate1);
			selectedOption.setAttribute("data-2rd-cate-cd", cate2);
			
			jQuery.ajax({
				url: '/teventmain/cate_save',
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
						saveCateButton.style.visibility = "hidden";
						alertToast('success','분류코드가 성공적으로 저장되었습니다.');
					}
				}
			}).done((res) => {
				console.log('통신성공');
			}).fail((error) => {
				console.log('통신실패');
				alertToast('error','오류가 발생하였습니다.');
			});
		}
	});

	
</script>
