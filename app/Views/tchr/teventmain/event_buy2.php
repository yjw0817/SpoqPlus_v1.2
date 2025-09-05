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
    
    /* Modal Styling for Apple Theme */
    .bg-gradient-blue-pink {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    #modal_send_info_form .modal-content {
        border: none;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    
    #modal_send_info_form .modal-header {
        border-bottom: none;
        padding: 1.5rem;
    }
    
    #modal_send_info_form .card {
        border: 1px solid rgba(0,0,0,.08);
        border-radius: 0.5rem;
    }
    
    #modal_send_info_form .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,.08);
        padding: 1rem 1.25rem;
    }
    
    #modal_send_info_form .form-control,
    #modal_send_info_form .form-select {
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    #modal_send_info_form .form-control:focus,
    #modal_send_info_form .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    #modal_send_info_form .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #e5e7eb;
        color: #6c757d;
    }
    
    #modal_send_info_form .bg-opacity-10 {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
    }
    
    .text-purple {
        color: #764ba2 !important;
    }

	/* 검색 버튼 높이 조정 */
	.serch_bt {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		height: 31px; /* input-group-sm의 높이에 맞춤 */
		padding: 0.25rem 0.5rem;
		font-size: 0.875rem;
		line-height: 1.5;
		text-decoration: none;
		border-radius: 0.25rem;
	}
	
	.serch_bt:hover {
		text-decoration: none;
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
	
</style>
<script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.js"></script>

<?php
$sDef = SpoqDef();
?>
<!-- Main content -->

<!-- Main content -->
<div id="content">
	<!-- BEGIN profile -->
	
	<!-- END profile -->
	<!-- BEGIN profile-content -->
	<div class="profile-content">
		<!-- BEGIN tab-content -->
		<div class="tab-content p-0">
			<!-- BEGIN #profile-about tab -->
			<div class="tab-pane fade active show" id="profile-about" role="tabpanel">
			
				<!-- BEGIN table -->
				<div class="table-responsive form-inline">
					<?php if(isset($refund_amt) && $refund_amt > 0) : ?>
					<div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
						<strong>교체환불 진행 중</strong><br>
						환불금액: <strong><?php echo number_format($refund_amt); ?>원</strong><br>
						<small>이 금액은 새로운 상품 구매 시 자동으로 차감됩니다.</small>
					</div>
					<?php endif; ?>
					<table class="table table-profile align-middle">
						<thead>
							<tr>
								<th colspan="2">
									<h1 class="page-header"><?php echo $title ?></h1>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr class="highlight">
								<td class="field" >필터</td>
								<td>
									<div class="row ">
										<div class="col-md-12 d-flex flex-wrap">
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
								<input type="text" class="form-control form-control-sm" id="product_search" name="product_search" placeholder="상품 검색 (최소 2글짜 이상)" />
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

															if (!empty($filteredEvents)) : // 필터링된 데이터가 있는 경우만 테이블 출력
														?>
															<table class="table table-hover text-white">
																<tbody>
																	<?php foreach ($filteredEvents as $f) : ?>
																		<tr data-category="<?php echo htmlspecialchars($r); ?>">
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
								<?php foreach ($sDef['M_CATE'] as $r => $v) : ?>
									
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
										<?php foreach ($eventSNOList as $eventData) : ?>
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
												
												<?php foreach ($event_list as $f) : ?>
													<?php if ($f['EVENT_REF_SNO'] == $eventData['SELL_EVENT_SNO'] && $f['SELL_EVENT_SNO'] != $eventData['SELL_EVENT_SNO']): ?>
														<!-- BEGIN widget-list-item -->
														<div class="widget-list-item"
															data-sell-event-sno="<?php echo htmlspecialchars($f['SELL_EVENT_SNO']); ?>"
															data-m-cate = "<?php echo htmlspecialchars($f['M_CATE']); ?>"
															data-event-ref-sno="<?php echo htmlspecialchars($f['EVENT_REF_SNO']); ?>"
															>
															
															<div class="widget-list-media"></div>
															<div class="widget-list-content">
																<h4 class="widget-list-title"><?php echo htmlspecialchars($f['SELL_EVENT_NM']); ?></h4>
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
																								if (empty($f['DOMCY_POSS_EVENT_YN']) || (!empty($f['DOMCY_POSS_EVENT_YN']) && $f['DOMCY_POSS_EVENT_YN'] == 'N' )) {
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
																								if (empty($f['DOMCY_POSS_EVENT_YN']) || (!empty($f['DOMCY_POSS_EVENT_YN']) && $f['DOMCY_POSS_EVENT_YN'] == 'N' )) {
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
																								if (empty($f['DOMCY_POSS_EVENT_YN']) || (!empty($f['DOMCY_POSS_EVENT_YN']) && $f['DOMCY_POSS_EVENT_YN'] == 'N' )) {
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
																								if (empty($f['DOMCY_POSS_EVENT_YN']) || (!empty($f['DOMCY_POSS_EVENT_YN']) && $f['DOMCY_POSS_EVENT_YN'] == 'N' )) {
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
																				if (empty($f['DOMCY_POSS_EVENT_YN']) || (!empty($f['DOMCY_POSS_EVENT_YN']) && $f['DOMCY_POSS_EVENT_YN'] == 'N' )) {
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
																				if (empty($f['DOMCY_POSS_EVENT_YN']) || (!empty($f['DOMCY_POSS_EVENT_YN']) && $f['DOMCY_POSS_EVENT_YN'] == 'N' )) {
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
																				if (empty($f['DOMCY_POSS_EVENT_YN']) || (!empty($f['DOMCY_POSS_EVENT_YN']) && $f['DOMCY_POSS_EVENT_YN'] == 'N' )) {
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
																				if (empty($f['DOMCY_POSS_EVENT_YN']) || (!empty($f['DOMCY_POSS_EVENT_YN']) && $f['DOMCY_POSS_EVENT_YN'] == 'N' )) {
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
																<?php echo number_format($f['SELL_AMT']); ?>원
															</div>
															<div class="widget-list-media"></div>
														</div>
														<!-- END widget-list-item -->
													<?php endif; ?>
												<?php endforeach; ?>
											</div>
											<!-- END widget-list -->
										<?php endforeach; ?>
										<?php endif; ?>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
							<!-- END row -->
						</div>
					</div>
				</div>
				<!-- END table -->
			</div>
			<!-- ============================= [ modal-default START ] ======================================= -->	
			<div class="modal fade" id="modal_mem_search_form">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header bg-lightblue">
							<h5 class="modal-title">회원 검색</h4>
							<button type="button" class="close"  data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="callout callout-info"  style='display:none'>
								<p>설명</p>
								<p><small>해당 부분 설명</small></p>
							</div>
							<!-- 설명 부분 [END] -->
							
							<!-- FORM [START] -->
							<input type="hidden" name="search_esno" id="search_esno" />
							<div class="input-group input-group-sm mb-1 col-sm-6">
								<span class="input-group-append">
									<span class="input-group-text input-group-text-sm" style='width:150px'>회원 이름</span>
								</span>
								<input class="form-control event-input me-1" type="text" placeholder="검색할 이름" name="search_mem_nm" id="search_mem_nm" autocomplete='off' style="min-width:300px !important; max-width:300px !important; width:300px !important; flex:0 0 auto !important;">
								<span class="input-group-append">
									<a href="#" id="btn_search_nm" class="serch_bt" type="button"><i class="fas fa-search"></i> 검색</a>
								</span>
							</div>
							
							<div class="input-group input-group-sm mb-1">
								
								<table class="table table-bordered table-hover table-striped col-md-12" id='search_mem_table'>
										<thead>
											<tr>
												<th>상태</th>
												<th>이름</th>
												<th>아이디</th>
												<th>전화번호</th>
												<th>생년월일</th>
												<th>성별</th>
												<th>선택</th>
											</tr>
										</thead>
										<tbody>
											<tr style="height:45px">
												<td colspan="7" class='text-center'>검색 결과가 없습니다.</td>
											</tr>
										</tbody>
								</table>
								
							</div>
							<!-- FORM [END] -->
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
						</div>
					</div>
				</div>
			</div>
			<!-- ============================= [ modal-default START ] ======================================= -->	
			<div class="modal fade" id="modal_send_info_form">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header bg-gradient-blue-pink text-white">
							<h4 class="modal-title fw-bold"><i class="fas fa-gift me-2"></i>상품 보내기 설정</h4>
							<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-hidden="true"></button>
						</div>
						<div class="modal-body p-3">
						
							<div class="card shadow-sm mb-3">
								<div class="card-header bg-light py-2">
									<h5 class="card-title mb-0 text-dark" style="font-size: 16px;"><i class="fas fa-info-circle text-primary me-1 fs-6"></i>상품 정보</h5>
								</div>
								<div class="card-body py-3">
									<div class="row g-1">
										<div class="col-md-4">
											<div class="d-flex align-items-center">
												<div class="flex-shrink-0">
													<div class="rounded p-1" style="background-color: #e3f2fd;">
														<i class="fas fa-box" style="font-size: 14px; color: #1976d2;"></i>
													</div>
												</div>
												<div class="flex-grow-1 ms-2">
													<div class="text-muted" style="font-size: 13px;">보내기 상품명</div>
													<div class="fw-semibold" style="font-size: 14px;" id="send_event_nm"></div>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="d-flex align-items-center">
												<div class="flex-shrink-0">
													<div class="rounded p-1" style="background-color: #fff3e0;">
														<i class="fas fa-sign-in-alt" style="font-size: 14px; color: #f57c00;"></i>
													</div>
												</div>
												<div class="flex-grow-1 ms-2">
													<div class="text-muted" style="font-size: 13px;">입장제한</div>
													<div class="fw-semibold" style="font-size: 14px;" id="send_acc_rtrct"></div>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="d-flex align-items-center">
												<div class="flex-shrink-0">
													<div class="rounded p-1" style="background-color: #e0f2f1;">
														<i class="fas fa-calendar-alt" style="font-size: 14px; color: #00796b;"></i>
													</div>
												</div>
												<div class="flex-grow-1 ms-2">
													<div class="text-muted" style="font-size: 13px;">이용기간</div>
													<div class="fw-semibold" style="font-size: 14px;" id="send_prod"></div>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="d-flex align-items-center">
												<div class="flex-shrink-0">
													<div class="rounded p-1" style="background-color: #e8f5e9;">
														<i class="fas fa-chalkboard-teacher" style="font-size: 14px; color: #388e3c;"></i>
													</div>
												</div>
												<div class="flex-grow-1 ms-2">
													<div class="text-muted" style="font-size: 13px;">수업횟수</div>
													<div class="fw-semibold" style="font-size: 14px;" id="send_clas_cnt"></div>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="d-flex align-items-center">
												<div class="flex-shrink-0">
													<div class="rounded p-1" style="background-color: #ffebee;">
														<i class="fas fa-pause-circle" style="font-size: 14px; color: #c62828;"></i>
													</div>
												</div>
												<div class="flex-grow-1 ms-2">
													<div class="text-muted" style="font-size: 13px;">휴회횟수 / 휴회일</div>
													<div class="fw-semibold" style="font-size: 14px;"><span id="send_domcy_cnt"></span> / <span id="send_domcy_day"></span></div>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="d-flex align-items-center">
												<div class="flex-shrink-0">
													<div class="rounded p-1" style="background-color: #f3e5f5;">
														<i class="fas fa-won-sign" style="font-size: 14px; color: #6a1b9a;"></i>
													</div>
												</div>
												<div class="flex-grow-1 ms-2">
													<div class="text-muted" style="font-size: 13px;">판매금액</div>
													<div class="fw-semibold text-primary" style="font-size: 14px;" id="send_amt"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<form name="form_send_set" id="form_send_set" method="post" action="/teventmain/send_event_proc">
								<input type="hidden" name="send_sell_event_sno" id="send_sell_event_sno" />
								<div id="send_mem_sno_container"></div>
								
								<div class="card shadow-sm">
									<div class="card-header bg-light py-2">
										<h5 class="card-title mb-0 text-dark" style="font-size: 16px;"><i class="fas fa-cog text-primary me-1 fs-6"></i>보내기 설정</h5>
									</div>
									<div class="card-body py-3">
								
										<div class="row mb-2">
											<div class="col-4">
												<label class="form-label fw-semibold" style="font-size: 14px;">
													<i class="fas fa-won-sign text-muted me-1" style="font-size: 12px;"></i>보내기 금액
												</label>
												<div class="input-group" style="max-width: 120px;">
													<input type="text" class="form-control text-end" name="set_send_amt" id="set_send_amt" style="width: 70px;" inputmode="numeric">
													<span class="input-group-text input-group-text-sm" style="border: none; background: none; padding-left: 5px;">원</span>
												</div>
											</div>
											<div class="col-4 disp_serv_cnt">
												<label class="form-label fw-semibold" style="font-size: 14px;">
													<i class="fas fa-gift text-muted me-1" style="font-size: 12px;"></i>서비스 수업
												</label>
												<div class="input-group" style="max-width: 120px;">
													<input type="text" class="form-control text-end" name="set_send_serv_clas_cnt" id="set_send_serv_clas_cnt" value="0" style="width: 70px;" inputmode="numeric">
													<span class="input-group-text input-group-text-sm" style="border: none; background: none; padding-left: 5px;">회</span>
												</div>
											</div>
											<div class="col-4 disp_serv_day">
												<label class="form-label fw-semibold" style="font-size: 14px;">
													<i class="fas fa-calendar-plus text-muted me-1" style="font-size: 12px;"></i>서비스 일수
												</label>
												<div class="input-group" style="max-width: 120px;">
													<input type="text" class="form-control text-end" name="set_send_serv_day" id="set_send_serv_day" value="0" style="width: 70px;" inputmode="numeric">
													<span class="input-group-text input-group-text-sm" style="border: none; background: none; padding-left: 5px;">일</span>
												</div>
											</div>
										</div>	
										<div class="row mb-2">
											<div class="col-4">
												<label class="form-label fw-semibold" style="font-size: 14px;">
													<i class="fas fa-pause text-muted me-1" style="font-size: 12px;"></i>휴회횟수
												</label>
												<div class="input-group" style="max-width: 120px;">
													<input type="text" class="form-control text-end" name="set_send_domcy_cnt" id="set_send_domcy_cnt" style="width: 70px;" inputmode="numeric">
													<span class="input-group-text input-group-text-sm" style="border: none; background: none; padding-left: 5px;">회</span>
												</div>
											</div>
											<div class="col-4">
												<label class="form-label fw-semibold" style="font-size: 14px;">
													<i class="fas fa-calendar-times text-muted me-1" style="font-size: 12px;"></i>휴회일
												</label>
												<div class="input-group" style="max-width: 120px;">
													<input type="text" class="form-control text-end" name="set_send_domcy_day" id="set_send_domcy_day" style="width: 70px;" inputmode="numeric">
													<span class="input-group-text input-group-text-sm" style="border: none; background: none; padding-left: 5px;">일</span>
												</div>
											</div>
											<div class="col-4">
												<label class="form-label fw-semibold" style="font-size: 14px;">
													<i class="fas fa-hourglass-end text-muted me-1" style="font-size: 12px;"></i>마감일 설정
													<small class="form-text text-muted ms-2" style="font-size: 11px;">
														<i class="fas fa-info-circle me-1" style="font-size: 10px;"></i>최대 7일
													</small>
												</label>
												<div class="input-group" style="max-width: 120px;">
													<input type="text" class="form-control text-end" name="set_end_day" id="set_end_day" value="7" style="width: 70px;" inputmode="numeric" max="7">
													<span class="input-group-text input-group-text-sm" style="border: none; background: none; padding-left: 5px;">일 후</span>
												</div>
											</div>
										</div>
								
										<div class="row">
											<div class="col-4">
												<label class="form-label fw-semibold" style="font-size: 14px;">
													<i class="fas fa-user-tie text-muted me-1" style="font-size: 12px;"></i>판매강사
												</label>
												<select class="form-select" name="set_ptchr_id_nm" id="set_ptchr_id_nm" data-toggle="select2" style="width: 100%;">
													<option value="">선택</option>
												<?php foreach ($tchr_list as $r) : ?>
													<option value="<?php echo $r['MEM_ID']?>|<?php echo $r['MEM_NM']?>">[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?></option>
												<?php endforeach; ?>
												</select>
											</div>
								
											<div class="col-4 disp_serv_cnt">
												<label class="form-label fw-semibold" style="font-size: 14px;">
													<i class="fas fa-chalkboard-teacher text-muted me-1" style="font-size: 12px;"></i>수업강사
												</label>
												<select class="form-select " name="set_stchr_id_nm" id="set_stchr_id_nm" style="width: 100%; ">
													<option value="">강사 선택</option>
												<?php foreach ($tchr_list as $r) : ?>
													<option value="<?php echo $r['MEM_ID']?>|<?php echo $r['MEM_NM']?>">[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?></option>
												<?php endforeach; ?>
												</select>
											</div>
											<div class="col-4">
												<!-- 빈 칸 -->
											</div>
										</div>
									</div>
								</div>
							
							</form>
							
							<!-- FORM [END] -->
						</div>
						<div class="modal-footer bg-light">
							<button type="button" class="btn btn-white" data-bs-dismiss="modal">
								<i class="fas fa-times me-1"></i>취소
							</button>
							<button type="button" class="btn btn-primary" onclick="btn_send_submit();">
								<i class="fas fa-paper-plane me-1"></i>상품 보내기
							</button>
						</div>
					</div>
				</div>
			</div>
			<form name="frm_event_buy_info" id="frm_event_buy_info" method="post" action="/teventmain/event_buy_info">
				<input type="text" name="send_memsno" id="send_memsno" />
				<input type="text" name="send_esno" id="send_esno" />
				<?php if(isset($refund_amt) && $refund_amt > 0) : ?>
				<input type="hidden" name="refund_amt" id="refund_amt" value="<?php echo $refund_amt; ?>" />
				<?php endif; ?>
			</form>
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
	const mode = "<?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>";
	const selectedCate1 = "<?php echo isset($selected_cate1) ? htmlspecialchars($selected_cate1, ENT_QUOTES, 'UTF-8') : ''; ?>";
	const selectedCate2 = "<?php echo isset($selected_cate2) ? htmlspecialchars($selected_cate2, ENT_QUOTES, 'UTF-8') : ''; ?>";
	const selectedMemSno = <?php echo isset($mem_sno) ? json_encode($mem_sno) : '[]'; ?>;
	const cate2SelectedNm = "<?php echo isset($cate2_selected_nm) ? htmlspecialchars($cate2_selected_nm, ENT_QUOTES, 'UTF-8') : ''; ?>";
	
	document.addEventListener("DOMContentLoaded", function () {
		let allCheckBox = document.getElementById("allCheckBox");
		let filterCheckboxes = document.querySelectorAll(".form-check-input:not(#allCheckBox)");
		let productSearch = document.getElementById("product_search");
		let headerElement = document.getElementById("header");

		

	// 페이지 로드 시 실행
		let categoryMap = {
			"check1": "기간제",
			"check2": "횟수제",
			"check3": "PRV",
			"check4": "GRP",
			"check5": "MBS",
			"check6": "OPT",
			"check7": "NOT_PKG",
			"check8": "PKG"
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
				originalTexts.clear(); // 전체 체크 시 강조 초기화
				applyFilters();
			}
		});

		filterCheckboxes.forEach(checkbox => {
			checkbox.addEventListener("change", function () {
				allCheckBox.checked = false;

				let checkedId = this.id;
				let group = groupMap[checkedId] || [];

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

				if (anyGroupFullyChecked || allGroupsUnchecked) {
					allCheckBox.checked = true;
				}

				applyFilters();
			});
		});

		productSearch.addEventListener("input", function () {
			applyFilters();
		});

		function applyFilters() {
			let selectedCategories = [];
			let searchText = normalizeSearchText(productSearch.value.trim());

			filterCheckboxes.forEach(checkbox => {
				if (checkbox.checked) {
					let category = categoryMap[checkbox.id];
					if (category) {
						if (category === "NOT_PKG") {
							selectedCategories.push("PRV", "GRP", "MBS", "OPT");
						} else {
							selectedCategories.push(category);
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

				let matchesCategory = selectedCategories.length === 0 || selectedCategories.includes(category) || allCheckBox.checked;
				let matchesSearch = searchText.length < 2 || title.includes(searchText) || desc.includes(searchText);

				if (matchesCategory && matchesSearch) {
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

				let matchesCategory = selectedCategories.length === 0 || selectedCategories.includes(category) || allCheckBox.checked;
				let matchesSearch = searchText.length < 2 || sellEventName.includes(searchText);

				if (matchesCategory && matchesSearch) {
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
				if (foundSellEventSnos.has(sellEventSno)) {
					head.style.display = "";
				} else {
					head.style.display = "none";
				}
			});

			document.querySelectorAll(".widget-list").forEach(widget => {
				let sellEventSno = widget.getAttribute("data-sell-event-sno");
				if (foundSellEventSnos.has(sellEventSno)) {
					widget.style.display = "";
				} else {
					widget.style.display = "none";
				}
			});

			document.querySelectorAll(".accordion").forEach(accordion => {
				let category = accordion.getAttribute("id").replace("accordion", "");
				let hasVisibleRows = accordion.querySelectorAll(".accordion-body tr").length > 0 &&
									accordion.querySelectorAll(".accordion-body tr[style='display: none;']").length !== 
									accordion.querySelectorAll(".accordion-body tr").length;

				if (foundCategories.has(category) || (hasVisibleRows && (allCheckBox.checked || selectedCategories.length === 0))) {
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

		// 모든 widget-list-item에 대해 클릭 이벤트를 걸어줍니다.
		document.querySelectorAll('.widget-list-item').forEach((item) => {
			item.addEventListener('click', (e) => {
				// data-sell-event-sno 속성값을 읽어옵니다.
				const sellEventSno = item.getAttribute('data-sell-event-sno');
				const mCate = item.getAttribute('data-m-cate');
				
				const mSno = item.getAttribute('data-m-sno');
				if (mode === "buy") {
					// 배열의 첫 번째 값만 전달
					const firstMemSno = selectedMemSno.length > 0 ? selectedMemSno : mSno;
					buy_user_search(sellEventSno, firstMemSno);
				} else if (mode === "send") {
					buy_send_info(sellEventSno);
				} 
			});
		});
		checkSendMemSno();
		// selectedMemSno 값 체크 및 초기 필터링 적용 함수
		function checkSendMemSno() {
			// selectedCate1과 selectedCate2가 있으면 해당 카테고리로 필터링
			if (selectedCate1 || selectedCate2) {
				// 먼저 모든 체크박스를 해제
				document.querySelectorAll(".form-check-input").forEach(cb => cb.checked = false);
				
				// selectedCate1에 따라 체크박스 설정
				if (selectedCate1) {
					switch(selectedCate1) {
						case 'PRV':
							document.getElementById("check3").checked = true;
							break;
						case 'GRP':
							document.getElementById("check4").checked = true;
							break;
						case 'MBS':
							document.getElementById("check5").checked = true;
							break;
						case 'OPT':
							document.getElementById("check6").checked = true;
							break;
					}
				}
				
				
				// selectedCate2가 있으면 해당 상품으로 포커스
				if (cate2SelectedNm) {
					productSearch.value = cate2SelectedNm;
				}


				
				// 필터 적용
				applyFilters();
			} else {
				// 기본적으로 전체 체크박스 선택
				document.getElementById("allCheckBox").checked = true;
				applyFilters();
			}
			
			// selectedMemSno가 있는지 확인하고 디버깅
			if (selectedMemSno && selectedMemSno.length > 0) {
				console.log("Selected members:", selectedMemSno);
			}
		}
	});

	
	
	
	// 구매할 회원 선택
	function buy_user_search(esno,mem_sno)
	{
		if (mem_sno != null && mem_sno != '')
		{
			buy_user_select(mem_sno,esno);
		} else 
		{
			$('#search_mem_nm').val('');
			$("#modal_mem_search_form").modal("show");
			$("#search_esno").val(esno);
			$('#search_mem_nm').val('');
		} 
		
	}

	function buy_user_select(memsno,esno)
	{
		$('#send_memsno').val(memsno);
		$('#send_esno').val(esno);
		$('#frm_event_buy_info').submit();
	}

	$("#search_mem_nm").on("keyup",function(key){
			if(key.keyCode==13) {
				$("#btn_search_nm").trigger("click");
			}
		});

	// 회원명 검색 버튼 클릭
	$('#btn_search_nm').click(function(){

		var esno = $('#search_esno').val();
		var sname = $('#search_mem_nm').val();
		
		if (sname.length < 2)
		{
			alertToast('error','검색어는 두글자 이상을 입력하세요');
			return;
		}
		
		var params = "sname="+sname;
		
		$('#search_mem_table > tbody > tr').remove();
		
		jQuery.ajax({
			url: '/teventmain/ajax_event_buy_search_nm',
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
					json_result['search_mem_list'].forEach(function (r,index) {
						var addTr = "<tr>";
						addTr += "<td>" + r['MEM_STAT_NM'] + "</td>";
						addTr += "<td>" + r['MEM_NM'] + "</td>";
						addTr += "<td>" + r['MEM_ID'] + "</td>";
						addTr += "<td>" + r['DISP_MEM_TELNO'] + "</td>";
						addTr += "<td>" + r['DISP_BTHDAY'] + "</td>";
						addTr += "<td>" + r['MEM_GENDR_NM'] + "</td>";
						addTr += "<td><button type=\"button\" class=\"btn btn-info btn-xs ac-btn\" onclick=\"buy_user_select('"+ r['MEM_SNO'] +"','"+esno+"');\">선택</button></td>";
						addTr += "</tr>";
						
						$('#search_mem_table > tbody:last').append(addTr);
					});
					
				} else 
				{
					alertToast('error','검색된 결과가 없습니다.');
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


	// 보내기 상품 선택
	function buy_send_info(esno)
	{
		var params = "esno="+esno;
		jQuery.ajax({
			url: '/teventmain/ajax_send_event_info',
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
					var ev = json_result['event_info'];
					$('#send_event_nm').text(ev.SELL_EVENT_NM);
					$('#send_acc_rtrct').text(ev.ACC_RTRCT_DV_NM + " (" + ev.ACC_RTRCT_MTHD_NM + ")");
					$('#send_prod').text(ev.USE_PROD_NM);
					$('#send_clas_cnt').text(ev.CLAS_CNT);
					$('#send_domcy_cnt').text(ev.DOMCY_CNT);
					$('#send_domcy_day').text(ev.DOMCY_DAY);
					
					$('#set_send_domcy_cnt').val(ev.DOMCY_CNT);
					$('#set_send_domcy_day').val(ev.DOMCY_DAY);
					if ( ev.DOMCY_POSS_EVENT_YN == 'Y' )
					{
						$('#set_send_domcy_cnt').attr('readonly',false);
						$('#set_send_domcy_day').attr('readonly',false);
					} else 
					{
						$('#set_send_domcy_cnt').attr('readonly',true);
						$('#set_send_domcy_day').attr('readonly',true);
					}
					
					$('#send_amt').text(ev.SELL_AMT.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
					$('#set_send_amt').val( currency_cost(ev.SELL_AMT) );
					if(ev.CLAS_DV == '21' || ev.CLAS_DV == '22' || ev.M_CATE == 'PRV' || ev.EVENT_TYPE == '20')
					{
						$('.disp_serv_cnt').show();
						$('.disp_serv_day').hide();
					} else 
					{
						$('.disp_serv_cnt').hide();
						$('.disp_serv_day').show();
					}
					
					$("#send_sell_event_sno").val(esno);
					
					// 회원 배열을 hidden input으로 추가
					$('#send_mem_sno_container').empty();
					selectedMemSno.forEach(function(memSno, index) {
						$('#send_mem_sno_container').append('<input type="hidden" name="send_mem_sno[]" value="' + memSno + '" />');
					});
					
					$("#modal_send_info_form").modal("show");
					
				
					
				} else 
				{
					alertToast('error','검색된 결과가 없습니다.');
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

	function currency_cost(cost)
	{
		var d_amt = onlyNum( cost );
		return currencyNum(d_amt);
	}

	function btn_send_submit()
	{
		// 입력값 검증
		var sendAmt = $('#set_send_amt').val().replace(/,/g, '');
		var servClassCnt = $('#set_send_serv_clas_cnt').val();
		var servDay = $('#set_send_serv_day').val();
		var domcyCnt = $('#set_send_domcy_cnt').val();
		var domcyDay = $('#set_send_domcy_day').val();
		var endDay = $('#set_end_day').val();
		var ptchrId = $('#set_ptchr_id_nm').val();
		var stchrId = $('#set_stchr_id_nm').val();

		// 필수 입력값 체크
		if (!sendAmt || sendAmt == '0') {
			alertToast('error', '보내기 금액을 입력해주세요.');
			$('#set_send_amt').focus();
			return;
		}

		if (!ptchrId) {
			alertToast('error', '판매강사를 선택해주세요.');
			$('#set_ptchr_id_nm').focus();
			return;
		}

		if (!stchrId) {
			alertToast('error', '수업강사를 선택해주세요.');
			$('#set_stchr_id_nm').focus();
			return;
		}

		// 확인 메시지
		var confirmMessage = '선택된 회원들에게 상품을 보내시겠습니까?';
		
		// SweetAlert2 또는 일반 confirm 사용
		if (typeof Swal !== 'undefined') {
			Swal.fire({
				title: '상품 보내기 확인',
				text: confirmMessage,
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: '보내기',
				cancelButtonText: '취소'
			}).then((result) => {
				if (result.isConfirmed) {
					$('#form_send_set').submit();
				}
			});
		} else {
			if (confirm(confirmMessage)) {
				$('#form_send_set').submit();
			}
		}
	}

	// 숫자만 입력 허용 함수
	function onlyNumbers(input) {
		input.value = input.value.replace(/[^0-9]/g, '');
	}

	// 컴마 포맷 함수
	function addCommas(input) {
		let value = input.value.replace(/[^0-9]/g, '');
		if (value) {
			input.value = parseInt(value).toLocaleString();
		}
	}

	// 마감일 제한 함수
	function limitMaxDays(input) {
		let value = parseInt(input.value.replace(/[^0-9]/g, ''));
		if (value > 7) {
			input.value = '7';
			alertToast('warning', '마감일은 최대 7일까지 설정 가능합니다.');
		}
	}

	// DOM 로드 완료 후 이벤트 바인딩
	$(document).ready(function() {
		// 보내기 금액 - 숫자만 입력 + 컴마 포맷
		$('#set_send_amt').on('input', function() {
			addCommas(this);
		});

		// 나머지 숫자 필드들 - 숫자만 입력
		$('#set_send_serv_clas_cnt, #set_send_serv_day, #set_send_domcy_cnt, #set_send_domcy_day').on('input', function() {
			onlyNumbers(this);
		});

		// 마감일 설정 - 숫자만 입력 + 7일 제한
		$('#set_end_day').on('input', function() {
			onlyNumbers(this);
			limitMaxDays(this);
		});

		// 마감일 설정 - blur 시에도 체크
		$('#set_end_day').on('blur', function() {
			if (this.value === '' || this.value === '0') {
				this.value = '1';
			}
		});
	});

</script>
