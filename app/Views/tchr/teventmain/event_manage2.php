<style>
	.form-check {
		position: relative;
		padding-left: 15px;
	}
	.form-check.border-start-custom::after {
		content: "";
		position: absolute;
		left: 0;
		top: 50%;
		transform: translateY(-50%);
		width: 1px;
		height: 20px;
		background-color: #ccc; /* 세로줄 색상 */
	}
	.table-profile td.field {
		width: 10% !important;
		max-width: 200px !important;
		white-space: nowrap !important;
		overflow: hidden !important;
		text-overflow: ellipsis !important;
	}
	.fixed-width {
		width: 300px; /* 가로 크기 고정 */
		max-width: 300px; /* 최대 크기 설정 */
	}
	/* 선택된 td에 대한 스타일 */
	.selected {
		background-color: rgba(255, 255, 255, 0.3) !important;
		color: #fff !important;
	}
	/* 테이블 행(row) 하단의 밑줄 제거 */
	.table-hover > :not(caption) > * > * {
		border-bottom-width: 0 !important; /* 하단 테두리 제거 */
	}
	
	/* widget-list-item hover 효과 */
	.widget-list-item {
		transition: background-color 0.3s ease-in-out;
		cursor: pointer; /* 마우스 커서를 손가락 모양으로 변경 */
	}

	.widget-list-item:hover {
		background-color: rgba(200, 200, 200, 0.2); /* 연한 회색 배경 */
	}

	/* 검색 입력 필드 컨테이너 */
	.search-group {
		position: relative;
		display: flex;
		align-items: center;
	}

	/* 검색 입력 필드 */
	.search-group .form-control {
		padding-right: 40px; /* 버튼 공간 확보 */
	}

	/* 돋보기 버튼 */
	.search-group .btn-search {
		position: absolute;
		right: 10px;
		top: 50%;
		transform: translateY(-50%);
		background: none;
		border: none;
		cursor: pointer;
		color: #888; /* 아이콘 색상 */
		font-size: 18px;
	}

	/* 돋보기 버튼 호버 효과 */
	.search-group .btn-search:hover {
		color: #333; /* 어두운 색상으로 변경 */
	}
	.row {
		margin-left: 0;
		margin-right: 0;
	}
	.breadcrumb {
		display: none;
	}

	.search-highlight {
		color: #ff8080; /* HEX: 따뜻하고 연한 붉은색 */
	}

	.text-teal-light {
        color: #80CBC4 !important;
    }
    .text-purple-light {
        color: #CE93D8 !important;
    }
    .text-blue-light {
        color: #90CAF9 !important;
    }
    .text-warning-light {
        color: #FFE082 !important;
    }

	
	/* 첫 번째 박스: 상단, 좌측, 우측 테두리 */
	.first-pkg-group {
		border-top: 1px solid blue !important; /* 상단 테두리 */
		border-left: 1px solid blue !important; /* 좌측 테두리 */
		border-right: 1px solid blue !important; /* 우측 테두리 */
	}

	/* 중간 박스들: 좌측, 우측 테두리 */
	.list-pkg-group {
		border-left: 1px solid blue !important; /* 좌측 테두리 */
		border-right: 1px solid blue !important; /* 우측 테두리 */
	}

	/* 마지막 박스: 하단, 좌측, 우측 테두리 */
	.last-pkg-group {
		border-bottom: 1px solid blue !important; /* 하단 테두리 */
		border-left: 1px solid blue !important; /* 좌측 테두리 */
		border-right: 1px solid blue !important; /* 우측 테두리 */
	}

	.first-pkg-event {
		border-top: 1px solid orange !important; /* 상단 테두리 */
		border-left: 1px solid orange !important; /* 좌측 테두리 */
		border-right: 1px solid orange !important; /* 우측 테두리 */
	}

	.all-pkg-event {
		border: 1px solid orange !important; /* 전체 테두리 */
	}
	.one-pkg-event {
		border: 1px solid orange !important; /* 상단 테두리 */
	}

	.list-pkg-event {
		border-left: 1px solid orange !important; /* 좌측 테두리 */
		border-right: 1px solid orange !important;/* 우측 테두리 */
	}

	.last-pkg-event {
		border-bottom: 1px solid orange !important; /* 하단 테두리 */
		border-left: 1px solid orange !important; /* 좌측 테두리 */
		border-right: 1px solid orange !important; /* 우측 테두리 */
	}

	/* .custom-col-md-3 {
		max-width: 300px !important;
		width: 300px !important;
	} */

</style>
<script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.js"></script>
<?php
	$sDef = SpoqDef();
?>
<!-- Main content -->
<div id="content" >
	<!-- BEGIN profile -->
	<div class="profile">
		<div class="profile-header">
			<!-- BEGIN profile-header-cover -->
			<div class="profile-header-cover"></div>
			<!-- END profile-header-cover -->
			<!-- BEGIN profile-header-content -->
			<div class="profile-header-content">
				
				<!-- END profile-header-img -->
				<!-- BEGIN profile-header-info -->
				<div class="profile-header-info">
				<h1 class="page-header"><?php echo $title ?></h1>
					<p class="mb-2">기본이용권그룹 생성 -> 판매이용권 생성</p>
					<!-- <a href="#" class="btn btn-xs btn-yellow">기본상품 만들기</a> -->
					<a href="/teventmain/event_manage_create" class="btn btn-xs btn-yellow">판매이용권 만들기</a>
					<a href="/teventmain/package_manage_create" class="btn btn-xs btn-yellow" style="display:none">팩키지상품 만들기</a>
				</div>
				<!-- END profile-header-info -->
			</div>
			<!-- END profile-header-content -->
			<!-- BEGIN profile-header-tab -->
			<ul class="profile-header-tab nav nav-tabs" role="tablist">
				<li class="nav-item" role="presentation"><a href="#profile-about" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">판매상품</a></li>
				<li class="nav-item" role="presentation"><a href="#profile-post" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1" style="display:none">판매중지상품</a></li>
			</ul>
			<!-- END profile-header-tab -->
		</div>
	</div>
	<!-- END profile -->
	<!-- BEGIN profile-content -->
	<div class="profile-content">
		<!-- BEGIN tab-content -->
		<div class="tab-content p-0">
			<!-- BEGIN #profile-about tab -->
			<div class="tab-pane fade active show" id="profile-about" role="tabpanel">
			
				<!-- BEGIN table -->
				<div class="table-responsive form-inline">
					<table class="table table-profile align-middle">
						<thead>
							<tr>
								<th colspan="2">
									<h4>판매이용권<!-- <small>UXUI + Frontend Developer</small> --> </h4>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr class="highlight">
								<td class="field" >필터</td>
								<td>
									<div class="row ">
										<div class="col-md-12 d-flex flex-wrap search">
											<div class="form-check me-3" style="padding-left:25px">
												<input class="form-check-input" type="checkbox" value="" id="allCheckBox" checked="">
												<label class="form-check-label" for="allCheckBox">전체</label>
											</div>
											<div class="form-check me-3 border-start-custom ps-5">
												<input class="form-check-input" type="checkbox" value="" id="check1" >
												<label class="form-check-label" for="check1">기간제</label>
											</div>
											<div class="form-check me-3">
												<input class="form-check-input" type="checkbox" value="" id="check2" >
												<label class="form-check-label" for="check2">횟수제</label>
											</div>
											<div class="form-check me-3 border-start-custom ps-5">
												<input class="form-check-input" type="checkbox" value="" id="check3" >
												<label class="form-check-label" for="check3">개인레슨</label>
											</div>
											<div class="form-check me-3 ">
												<input class="form-check-input" type="checkbox" value="" id="check4" >
												<label class="form-check-label" for="check4">그룹수업</label>
											</div>
											<div class="form-check me-3">
												<input class="form-check-input" type="checkbox" value="" id="check5" >
												<label class="form-check-label" for="check5">회원권 (시설이용)</label>
											</div>
											<div class="form-check me-3">
												<input class="form-check-input" type="checkbox" value="" id="check6" >
												<label class="form-check-label" for="check6">옵션 상품</label>
											</div>
											<div class="form-check me-3 border-start-custom ps-5" style="display:none">
												<input class="form-check-input" type="checkbox" value="" id="check7" >
												<label class="form-check-label" for="check7">단일 상품</label>
											</div>
											<div class="form-check me-3" style="display:none">
												<input class="form-check-input" type="checkbox" value="" id="check8" >
												<label class="form-check-label" for="check8">팩키지 상품</label>
											</div>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>	
					<div class="row">
						<div class="col-md-3">
							<div class="search-group mb-5px">
								<input type="text" class="form-control" id="product_search" name="product_search" placeholder="상품 검색 (최소 2글짜 이상)" />
								<button type="button" class="btn btn-search" ><i class="ion-ios-search"></i></button>
							</div>
							<?php foreach ($sDef['M_CATE'] as $r => $v) : ?>
								<?php if ($r !== 'PKG') : ?>
									<!-- BEGIN #accordion -->
									<div class="accordion" id="accordion<?php echo $r ?>" data-category="<?php echo htmlspecialchars($r); ?>">
										<div class="accordion-item border-0">
											<div class="accordion-header" id="accordionHeading<?php echo $r ?>">
												<button class="accordion-button bg-gray-900 text-white px-3 py-10px pointer-cursor" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $r ?>" aria-expanded="true">
												       <?php
															// $sDef 대신 앞에서 정의한 $SpoQDef를 사용한다고 가정합니다.
															// 만약 $sDef가 맞다면 $SpoQDef를 $sDef로 변경해주세요.
															$iconClass = isset($sDef['M_CATE_ICON'][$r]) ? $sDef['M_CATE_ICON'][$r] : '';
															$colorClass = isset($sDef['M_CATE_COLOR'][$r]) ? $sDef['M_CATE_COLOR'][$r] : '';
															
															// Iconify 아이콘 태그로 변경
															echo '<iconify-icon icon="' . $iconClass . '" class="' . $colorClass . ' me-2" style="font-size: 1.7rem;"></iconify-icon>';
															?>
														<?php echo $v ?> 
												</button>
											</div>
											<div id="collapse<?php echo $r ?>" class="accordion-collapse collapse show" data-bs-parent="#accordion<?php echo $r ?>">
												<div class="accordion-body bg-gray-800 text-white">
													<div class="table-responsive">
														<?php 
															$filteredEvents = array_filter($event_list, function($f) use ($r) {
																return ($f['M_CATE'] == $r) && ($f['SELL_EVENT_SNO'] == $f['EVENT_REF_SNO']) && ($f['SELL_YN'] == 'Y');
															});

															// 디버깅 로그
															foreach ($filteredEvents as $f) {
																error_log("FilteredEvent: SELL_EVENT_SNO={$f['SELL_EVENT_SNO']}, EVENT_TYPE={$f['EVENT_TYPE']}, M_CATE={$f['M_CATE']}, SELL_EVENT_NM=" . htmlspecialchars($f['SELL_EVENT_NM']));
															}

															if (!empty($filteredEvents)) : // 필터링된 데이터가 있는 경우만 테이블 출력
														?>
															<table class="table table-hover text-white">
																<tbody>
																	<?php foreach ($filteredEvents as $f) : ?>
																		<tr data-category="<?php echo htmlspecialchars($r); ?>" 
																			data-event-type="<?php echo htmlspecialchars($f['EVENT_TYPE']); ?>"> <!-- null 없음 -->
																			<td data-sell-event-sno="<?php echo htmlspecialchars($f['SELL_EVENT_SNO']); ?>">
																				<?php echo htmlspecialchars($f['SELL_EVENT_NM']); ?>
																			</td>
																		</tr>
																	<?php endforeach; ?>
																</tbody>
															</table>
														<?php endif; ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>

						<div class="col-md-9">
							<!-- BEGIN row -->
							<div class="row">
								<?php
									$index3 = 0; 
									foreach ($sDef['M_CATE'] as $r => $v) : ?>
									<?php if ($r !== 'PKG') : ?>
										<?php 
											// 각 M_CATE별로 SELL_EVENT_SNO를 기준으로 위젯을 만든다.
											$eventSNOList = [];
											foreach ($event_list as $f) {
												if (!empty($f['M_CATE']) && $f['M_CATE'] == $r && $f['SELL_EVENT_SNO'] == $f['EVENT_REF_SNO'] &&  $f['ref_count'] > 0 && ($f['SELL_YN'] == 'Y') ) {
													$eventSNOList[$f['SELL_EVENT_SNO']] = $eventSNOList[$f['SELL_EVENT_SNO']] = [
														'SELL_EVENT_SNO' => $f['SELL_EVENT_SNO'],
														'SELL_EVENT_NM' => $f['SELL_EVENT_NM'],
														'EVENT_REF_SNO' => $f['EVENT_REF_SNO']
													]; // SELL_EVENT_SNO 저장
												}
											}
											if (!empty($eventSNOList)) : // 관련 데이터가 있는 경우만 실행
										?>
										<?php
											$index2 = 0; 
											foreach ($eventSNOList as $eventData) : ?>
											<!-- M_CATE별 SELL_EVENT_NM 헤더 -->
											<div class="mb-10px mt-10px fs-13px head-container" 
												id="head-<?php echo htmlspecialchars($eventData['SELL_EVENT_SNO']); ?>" 
												data-category="<?php echo htmlspecialchars($r); ?>">  <!-- data-category 추가 -->
												<b class="text-body">
												<?php echo '<iconify-icon icon="' . (isset($sDef['M_CATE_ICON'][$r]) ? $sDef['M_CATE_ICON'][$r] : '') . '" class="' . (isset($sDef['M_CATE_COLOR'][$r]) ? $sDef['M_CATE_COLOR'][$r] : '') . ' me-2" style="font-size: 2em;"></iconify-icon>'; ?>
													<?php echo htmlspecialchars($eventData['SELL_EVENT_NM']); ?>
												</b>
											</div>

											<!-- BEGIN widget-list -->
											<div class="widget-list rounded mb-4 py-3"
												data-id="widget"
												id="widget-<?php echo htmlspecialchars($eventData['SELL_EVENT_SNO']); ?>"
												data-sell-event-sno="<?php echo htmlspecialchars($eventData['SELL_EVENT_SNO']); ?>"
												data-category="<?php echo htmlspecialchars($r); ?>">  <!-- data-category 추가 -->
												
												
												<?php
												$index = 0;
												foreach ($event_list as $f) :  if ($f['EVENT_REF_SNO'] == $eventData['SELL_EVENT_SNO'] && $f['SELL_EVENT_SNO'] != $eventData['SELL_EVENT_SNO']): 
													$checkbox_id = 'mem_disp_yn_' .  $index3 .  $index2 . $index;
												?>
														<!-- BEGIN widget-list-item -->
														<div class="widget-list-item"
															data-sell-event-sno="<?php echo htmlspecialchars($f['SELL_EVENT_SNO']); ?>"
															data-m-cate="<?php echo htmlspecialchars($f['M_CATE']); ?>"
															data-event-ref-sno="<?php echo htmlspecialchars($f['EVENT_REF_SNO']); ?>"
															data-event-type="<?php echo htmlspecialchars($f['EVENT_TYPE']); ?>">
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
																		switch ($r) {
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
																<?php
																$checkbox_id = 'mem_disp_yn_' . $index;

																// 체크 여부 (Y면 checked, 아니면 비워둠)
																$isChecked = ($f['MEM_DISP_YN'] === 'Y') ? 'checked' : '';

																// 라벨 표시 여부 (Y면 보이고, 아니면 visibility: hidden)
																$labelStyle = ($f['MEM_DISP_YN'] === 'Y') ? '' : 'style="visibility:hidden;"';
																?>
																<div class="form-check form-switch mb-0">
																	<input
																		class="form-check-input"
																		type="checkbox"
																		id="<?php echo $checkbox_id; ?>"
																		onchange="mem_disp_change(event, this)"
																		<?php echo $isChecked; ?>
																		data-gtm-form-interact-field-id="0">

																	<label class="form-check-label"
																		for="<?php echo $checkbox_id; ?>"
																		<?php echo $labelStyle; ?>>
																		모바일공개
																	</label>
																</div>
															</div>
															<div class="widget-list-media"></div>
														</div>
														<!-- END widget-list-item -->
													<?php $index++; // 다음 인덱스 증가
														endif;  
														endforeach; ?>
											</div>
											<!-- END widget-list -->
										<?php $index2++; endforeach; ?>
										<?php endif; ?>
									<?php endif; ?>
								<?php  $index3++; endforeach; ?>
							</div>
							<!-- END row -->
						</div>
					</div>
				</div>
				<!-- END table -->
			</div>
			<!-- END #profile-about tab -->
			<!-- BEGIN #profile-post tab -->
			<!-- END #profile-friends tab -->
		</div>
		<!-- END tab-content -->
	</div>
	<!-- END profile-content -->
</div>


<?=$jsinc ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let allCheckBox = document.getElementById("allCheckBox");
    const searchArea = document.querySelector(".search");
    const filterCheckboxes = searchArea.querySelectorAll(".form-check-input:not(#allCheckBox)");
    let productSearch = document.getElementById("product_search");
    let headerElement = document.getElementById("header");

    let categoryMap = {
        "check1": { type: "event_type", value: "10" },
        "check2": { type: "event_type", value: "20" },
        "check3": { type: "m_cate", value: "PRV" },
        "check4": { type: "m_cate", value: "GRP" },
        "check5": { type: "m_cate", value: "MBS" },
        "check6": { type: "m_cate", value: "OPT" },
        "check7": { type: "m_cate", value: "NOT_PKG" },
        "check8": { type: "m_cate", value: "PKG" }
    };

    let groupMap = {
        "check1": ["check1", "check2"],
        "check2": ["check1", "check2"],
        "check3": ["check3", "check4", "check5", "check6"],
        "check4": ["check3", "check4", "check5", "check6"],
        "check5": ["check3", "check4", "check5", "check6"],
        "check6": ["check3", "check4", "check5", "check6"],
        "check7": ["check7", "check8"],
        "check8": ["check7", "check8"]
    };

    let originalTexts = new Map();

    function normalizeSearchText(text) {
        return text
            .replace(/p\.?t/gi, "PT")
            .replace(/g\.?x/gi, "GX")
            .replace(/피티/gi, "PT")
            .toLowerCase();
    }

    function highlightText(element, searchText) {
        if (!searchText || searchText.length < 2) {
            if (originalTexts.has(element)) {
                element.innerHTML = originalTexts.get(element);
            }
            return;
        }

        if (!originalTexts.has(element)) {
            originalTexts.set(element, element.innerHTML);
        }

        let originalText = originalTexts.get(element);
        let normalizedText = normalizeSearchText(originalText);
        let normalizedSearch = normalizeSearchText(searchText);

        if (normalizedText.includes(normalizedSearch)) {
            let regex = new RegExp(`(${searchText.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            element.innerHTML = originalText.replace(regex, '<span class="search-highlight">$1</span>');
        } else {
            element.innerHTML = originalText;
        }
    }

    allCheckBox.addEventListener("change", function (event) {
        if (!this.checked) {
            this.checked = true;
        }

        if (this.checked && event.isTrusted) {
            filterCheckboxes.forEach(cb => cb.checked = false);
            document.querySelectorAll("[id^='head']:not(#header), [id^='widget'], [data-category]").forEach(el => {
                el.removeAttribute("style");
            });
            originalTexts.clear();
            applyFilters();
        }
    });

    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            allCheckBox.checked = false;

            let checkedId = this.id;
            let group = groupMap[checkedId] || [];

            // 선택된 그룹 외 다른 그룹 체크박스 해제
            filterCheckboxes.forEach(cb => {
                if (!group.includes(cb.id)) {
                    cb.checked = false;
                }
            });

            const uniqueGroups = [...new Set(Object.values(groupMap))];
            const anyGroupFullyChecked = uniqueGroups.some(group =>
                group.every(id => document.getElementById(id).checked)
            );
            const allGroupsUnchecked = uniqueGroups.every(group =>
                group.every(id => !document.getElementById(id).checked)
            );

            if (anyGroupFullyChecked) {
                // 한 그룹의 모든 체크박스 체크 시: "전체" 체크하고 해당 그룹 체크박스 해제
                allCheckBox.checked = true;
                filterCheckboxes.forEach(cb => {
                    if (group.includes(cb.id)) {
                        cb.checked = false;
                    }
                });
            } else if (allGroupsUnchecked) {
                // 모든 그룹 체크 해제 시: "전체" 체크박스 해제
                allCheckBox.checked = false;
            } else {
                // 하나라도 체크 시: "전체" 체크박스 해제
                allCheckBox.checked = false;
            }

            applyFilters();
        });
    });

    productSearch.addEventListener("input", function () {
        applyFilters();
    });

    function applyFilters() {
        let eventTypeFilters = [];
        let mCateFilters = [];
        let searchText = normalizeSearchText(productSearch.value.trim());

        filterCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                let filter = categoryMap[checkbox.id];
                if (filter) {
                    if (filter.type === "m_cate") {
                        if (filter.value === "NOT_PKG") {
                            mCateFilters.push(...["PRV", "GRP", "MBS", "OPT"]);
                        } else {
                            mCateFilters.push(filter.value);
                        }
                    } else if (filter.type === "event_type") {
                        eventTypeFilters.push(filter.value);
                    }
                }
            }
        });

        let foundSellEventSnos = new Set();
        let foundCategories = new Set();

        document.querySelectorAll(".widget-list-item").forEach(item => {
            let titleElement = item.querySelector(".widget-list-title");
            let descElement = item.querySelector(".widget-list-desc");

            if (!originalTexts.has(titleElement)) originalTexts.set(titleElement, titleElement.innerHTML);
            if (!originalTexts.has(descElement)) originalTexts.set(descElement, descElement.innerHTML);

            let originalTitle = originalTexts.get(titleElement) || "";
            let originalDesc = originalTexts.get(descElement) || "";
            let title = normalizeSearchText(originalTitle);
            let desc = normalizeSearchText(originalDesc);
            let category = item.closest(".widget-list")?.getAttribute("data-category");
            let sellEventSno = item.closest(".widget-list")?.getAttribute("data-sell-event-sno");
            let eventType = item.getAttribute("data-event-type");

            let matchesEventType = eventTypeFilters.length === 0 || eventTypeFilters.includes(eventType);
            let matchesMCate = mCateFilters.length === 0 || mCateFilters.includes(category);
            let matchesFilters = (eventTypeFilters.length === 0 && mCateFilters.length === 0) || allCheckBox.checked || (matchesEventType && matchesMCate);
            let matchesSearch = searchText.length < 2 || title.includes(searchText) || desc.includes(searchText);

            if (matchesFilters && matchesSearch) {
                item.style.display = "";
                if (titleElement) highlightText(titleElement, searchText);
                if (descElement) highlightText(descElement, searchText);
                if (sellEventSno) foundSellEventSnos.add(sellEventSno);
                if (category) foundCategories.add(category);
            } else {
                item.style.display = "none";
                if (titleElement) highlightText(titleElement, "");
                if (descElement) highlightText(descElement, "");
            }
        });

        document.querySelectorAll(".accordion-body tr[data-category]").forEach(row => {
            let tdElement = row.querySelector("td");

            if (!originalTexts.has(tdElement)) originalTexts.set(tdElement, tdElement.innerHTML);

            let originalSellEventName = originalTexts.get(tdElement) || "";
            let sellEventName = normalizeSearchText(originalSellEventName);
            let category = row.getAttribute("data-category");
            let sellEventSno = tdElement?.getAttribute("data-sell-event-sno");
            let eventType = row.getAttribute("data-event-type");

            let matchesEventType = eventTypeFilters.length === 0 || eventTypeFilters.includes(eventType);
            let matchesMCate = mCateFilters.length === 0 || mCateFilters.includes(category);
            let matchesFilters = (eventTypeFilters.length === 0 && mCateFilters.length === 0) || allCheckBox.checked || (matchesEventType && matchesMCate);
            let matchesSearch = searchText.length < 2 || sellEventName.includes(searchText);

            if (matchesFilters && matchesSearch) {
                row.style.display = "";
                if (tdElement) highlightText(tdElement, searchText);
                if (sellEventSno) foundSellEventSnos.add(sellEventSno);
                if (category) foundCategories.add(category);
            } else {
                row.style.display = "none";
                if (tdElement) highlightText(tdElement, "");
            }
        });

        document.querySelectorAll("[id^='head']:not(#header)").forEach(head => {
            let sellEventSno = head.getAttribute("id").replace("head-", "");
            let widgetList = document.querySelector(`.widget-list[data-sell-event-sno="${sellEventSno}"]`);
            let hasVisibleItems = widgetList && widgetList.querySelectorAll(".widget-list-item:not([style='display: none;'])").length > 0;
            if (foundSellEventSnos.has(sellEventSno) && hasVisibleItems) {
                head.style.display = "";
            } else {
                head.style.display = "none";
            }
        });

        document.querySelectorAll(".widget-list").forEach(widget => {
            let sellEventSno = widget.getAttribute("data-sell-event-sno");
            let hasVisibleItems = widget.querySelectorAll(".widget-list-item:not([style='display: none;'])").length > 0;
            if (foundSellEventSnos.has(sellEventSno) && hasVisibleItems) {
                widget.style.display = "";
            } else {
                widget.style.display = "none";
            }
        });

        document.querySelectorAll(".accordion").forEach(accordion => {
            let category = accordion.getAttribute("id").replace("accordion", "");
            let hasVisibleRows = accordion.querySelectorAll(".accordion-body tr:not([style='display: none;'])").length > 0;
            if (foundCategories.has(category) || (hasVisibleRows && (allCheckBox.checked || (eventTypeFilters.length === 0 && mCateFilters.length === 0)))) {
                accordion.style.display = "";
            } else {
                accordion.style.display = "none";
            }
        });

        if (allCheckBox.checked && searchText.length < 2) {
            document.querySelectorAll("[id^='head']:not(#header), .widget-list, .widget-list-item, .accordion, .accordion-body tr[data-category]").forEach(el => {
                el.removeAttribute("style");
            });
            document.querySelectorAll(".widget-list-title, .widget-list-desc, .accordion-body td").forEach(el => {
                highlightText(el, "");
            });
        }
    }

    applyFilters();

    function addTableClickEvent() {
        let tableCells = document.querySelectorAll("table.table-hover tbody tr td");

        tableCells.forEach(function (td) {
            td.addEventListener("click", function () {
                tableCells.forEach(cell => cell.classList.remove("selected"));
                this.classList.add("selected");

                let sellEventSno = this.getAttribute("data-sell-event-sno");

                document.querySelectorAll("[id^='head']:not(#header)").forEach(div => div.style.display = "none");
                let headElement = document.getElementById("head-" + sellEventSno);
                if (headElement) headElement.style.display = "";

                document.querySelectorAll("[id^='widget']").forEach(div => div.style.display = "none");

                document.querySelectorAll("[data-sell-event-sno]").forEach(widget => {
                    if (widget.getAttribute("data-sell-event-sno") === sellEventSno) {
                        widget.style.display = "";

                        let widgetItems = widget.querySelectorAll("[data-event-ref-sno]");
                        widgetItems.forEach(item => {
                            if (item.getAttribute("data-event-ref-sno") === sellEventSno) {
                                item.style.display = "";
                            } else {
                                item.style.display = "none";
                            }
                        });
                    }
                });
            });
        });
    }

    function removeTableClickEvent() {
        let tableCells = document.querySelectorAll("table.table-hover tbody tr td");
        tableCells.forEach(td => {
            td.replaceWith(td.cloneNode(true));
        });
    }

    addTableClickEvent();

    document.querySelectorAll('.widget-list-item').forEach((item) => {
        item.addEventListener('click', (e) => {
            if (e.target.closest('.form-check') || e.target.classList.contains('form-check-input') || e.target.classList.contains('form-check-label')) {
                return;
            }

            const sellEventSno = item.getAttribute('data-sell-event-sno');
            const mCate = item.getAttribute('data-m_cate');
            if (mCate === 'PKG') {
                location.href = `/teventmain/package_manage_create/${sellEventSno}`;
            } else {
                location.href = `/teventmain/event_manage_create/${sellEventSno}`;
            }
        });
    });
});

function mem_disp_change(e, el) {
    e.stopPropagation();
    e.preventDefault();

    const label = el.nextElementSibling; // <label> 요소
    label.style.visibility = el.checked ? 'visible' : 'hidden';

	// 가장 가까운 부모 .widget-list-item 찾기
	const widget = el.closest('.widget-list-item');

	if (!widget) {
		console.warn('widget-list-item not found');
		return;
	}

	// data-sell-event-sno 읽기
	const sellEventSno = widget.getAttribute('data-sell-event-sno');

	// 2. 체크 여부
	const isChecked = el.checked ? 'Y' : 'N';

	// 3. Ajax 파라미터 구성
	const param = {
		sell_event_sno: sellEventSno,
		mem_disp_yn: isChecked
	};

	jQuery.ajax({
		url: '/teventmain/change_mobile_disp',
		type: 'POST',
		data:param,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
		dataType: 'text',
		success: function (result) {
			if ( result.substr(0,8) == '<script>' )
			{
				alert('오류가 발생하였습니다.');
				return;
			}
			
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
			} else
			{
				alertToast('error','오류가 발생하였습니다.');
			}
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

</script>
