<link rel="stylesheet" href="/assets/css/grp_schedule.css">

<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
			
				<div class="col-md-2 only_desktop">
					<div class="mb-3">
						<div class="panel panel-inverse">
							<div class="panel-heading mb10">
								<h3 class="panel-title">그룹수업룸 선택</h3>
							</div>
							<div class="panel-body">
								<div class="mb-2">
									<select class="form-control" name="gx_room_mgmt_sno" id="gx_room_mgmt_sno">
										<?php foreach ($gx_room_list as $r) : ?>
											<option value="<?= esc($r['GX_ROOM_MGMT_SNO']) ?>" <?php if ($r['GX_ROOM_MGMT_SNO'] == $gx_room_mgmt_sno) echo 'selected'; ?>><?= esc($r['GX_ROOM_TITLE']) ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							</div>

						<div class="panel panel-inverse">
							<div class="panel-heading mb10">
								<h3 class="panel-title">그룹수업 설정하기</h3>
							</div>

								<button id="copy_schedule" type="button" class="btn btn-block btn-default size13 wid90 center btn-blue btn-sm" onclick="gx_copy();" style="display:none;">스케쥴 복사</button>

							<form id='form_gx_item'>
							<input type='hidden' name='gx_room_mgmt_sno' id='gx_room_mgmt_sno' value='<?= esc($gx_room_mgmt_sno) ?>' />
							<input type='hidden' name='gx_item_color' id='gx_item_color' />
							<input type='hidden' name='gx_current_date' id='gx_current_date' />
							
							<div class="panel-body">
								<div class="mb-2">
									<label class="form-label"><strong>수업 컬러 선택</strong></label>
								</div>
								<div class="btn-group colorbox" >
    								<ul class="fc-color-picker color-choice" id="color-chooser">
    									<li><a class="text-info" href="#"><i class="fas fa-square"></i></a></li>
    									<li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
    									<li><a class="text-purple" href="#"><i class="fas fa-square"></i></a></li>
    									<li><a class="text-lime" href="#"><i class="fas fa-square"></i></a></li>
    									<li><a class="text-yellow" href="#"><i class="fas fa-square"></i></a></li>
    									<li><a class="text-indigo" href="#"><i class="fas fa-square"></i></a></li>
    									<li><a class="text-gray" href="#"><i class="fas fa-square"></i></a></li>
    									<li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
    									<li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
    									<li><a class="text-dark" href="#"><i class="fas fa-square"></i></a></li>
    									<li><a class="text-default" href="#"><i class="fas fa-square"></i></a></li>
    									<li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
    								</ul>
								</div>
							    	

								<div class="bbs_search_box_lt mb10 mt10">
									<ul>
									<li>
										<input id="new-event" name="new_event" type="text" class="form-control" placeholder="수업명">
									</li>
									<li>
										<select class="form-control" name="gx_tchr_id" id="gx_ptchr_id">
																		<option value="">강사 선택</option>
																	<?php foreach ($tchr_list as $r) : ?>
																		<option value="<?= esc($r['MEM_ID']) ?>">[<?= esc($r['TCHR_POSN_NM']) ?>] <?= esc($r['MEM_NM']) ?> </option>
																	<?php endforeach; ?>
										</select>
									</li>
									
									<li>
										<button id="add-new-event2" type="button"  class="basic_bt05" > 추가</button>
										<button id="add-new-event" type="button" style='display:none' class="sbasic_bt05" > 추가2</button>
									</li>
									</ul>
								</div>




							</div>
							</form>
						</div>
						
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">등록된 그룹수업 & 강사</h4>
							</div>
							<div class="panel-body">

								<!-- the events -->
								<div id="external-events">
									<?php foreach ($gx_item_list as $r) :?>
									<span class="input-group-append" style="position: relative;">
										<div class="external-event form-control mt2" style="color:#ffffff;background-color:<?= esc($r['GX_ITEM_COLOR']) ?>;position:relative;" 
											data-tid="<?= esc($r['TCHR_ID']) ?>" 
											data-item-sno="<?= esc($r['GX_ITEM_SNO']) ?>"
											data-item-name="<?= esc($r['GX_ITEM_NM']) ?>"
											data-tchr-name="<?= esc($r['TCHR_NM']) ?>"
											data-item-color="<?= esc($r['GX_ITEM_COLOR']) ?>"
											data-class-min="<?php echo isset($r['GX_CLASS_MIN']) && $r['GX_CLASS_MIN'] > 0 ? $r['GX_CLASS_MIN'] : 60; ?>"
											><?= esc($r['GX_ITEM_NM']) ?> (<?= esc($r['TCHR_NM']) ?>)
											<a type="button" class="close5" onclick="gx_item_del('<?= esc($r['GX_ITEM_SNO']) ?>'); event.stopPropagation();"><i class="fas fa-times-circle"></i></a>
											<span class="ticket-count-badge"><?php echo $r['EVENT_COUNT'] ?? 0; ?></span>
										</div>
									</span>
									<?php endforeach;?>
    								<!-- <div class="external-event bg-success" data-tid="--id--">요가</div> -->
    								<div class="checkbox" style='display:none'>
    									<label for="drop-remove">
    										<input type="checkbox" id="drop-remove">
    											remove after drop
    									</label>
    								</div>
								</div>
								<p class="mt-3"><b>※ 클릭하여 등록수업을 수정할 수 있습니다.</b></p>
								
								<p class="mt-3"><b>※ 스케쥴 표에 드래그 앤 드롭으로 등록할 수 있습니다.</b></p>
								
							</div>
							
						  <!-- /.card-body -->
						</div>
					   <!-- /.card -->
						
					</div>
				</div>
          	    <!-- /.col -->
				<div class="col-md-10">
					<div class="panel panel-inverse">
							<div class="panel-heading mb10">
								<h3 class="panel-title">주간 스케쥴</h3>
							</div>
						  <!-- THE CALENDAR -->
							<div id="calendar" class="calendar pad10"></div>
						</div>
				</div>
			<!-- /.col -->
			</div>
		      <!-- /.row -->
		</div><!-- /.container-fluid -->
		
<!-- ============================= [ 이벤트 서브메뉴 START ] ============================================ -->
<style>
.event-submenu {
    z-index: 99999 !important;
    position: absolute !important;
}
.main-footer {
z-index: 1000 !important; /* footer의 z-index를 낮게 설정 */
}

/* AdminLTE의 layout-footer-fixed 설정 덮어쓰기 */
.layout-footer-fixed .wrapper .main-footer {
z-index: 1000 !important;
}
</style>
<div id="event-submenu" class="event-submenu" style="display: none; position: absolute; z-index: 99999; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 0; min-width: 160px;">
    <div class="submenu-item" onclick="showReservationHistory()" style="padding: 12px 16px; cursor: pointer; border-bottom: 1px solid #eee; display: flex; align-items: center;">
        <i class="fas fa-list text-primary" style="width: 16px; margin-right: 8px;"></i>
        <span>예약내역 보기</span>
    </div>
    <div class="submenu-item" onclick="changeInstructor()" style="padding: 12px 16px; cursor: pointer; border-bottom: 1px solid #eee; display: none; align-items: center;">
        <i class="fas fa-user-edit text-warning" style="width: 16px; margin-right: 8px;"></i>
        <span>강사변경</span>
    </div>
    <div class="submenu-item" onclick="editClass()" style="padding: 12px 16px; cursor: pointer; border-bottom: 1px solid #eee; display: flex; align-items: center;">
        <i class="fas fa-edit text-info" style="width: 16px; margin-right: 8px;"></i>
        <span>수업 수정</span>
    </div>
    <div class="submenu-item" onclick="cancelClass()" style="padding: 12px 16px; cursor: pointer; display: none; align-items: center;">
        <i class="fas fa-times-circle text-danger" style="width: 16px; margin-right: 8px;"></i>
        <span>수업취소</span>
    </div>
</div>
<!-- ============================= [ 이벤트 서브메뉴 END ] ============================================ -->

<!-- ============================= [ 좌측 수업 아이템 서브메뉴 START ] ============================================ -->
<div id="external-item-submenu" class="event-submenu" style="display: none; position: absolute; z-index: 99999; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 0; min-width: 160px;">
    <div class="submenu-item" onclick="editExternalItem()" style="padding: 12px 16px; cursor: pointer; display: flex; align-items: center;">
        <i class="fas fa-edit text-info" style="width: 16px; margin-right: 8px;"></i>
        <span>등록수업 수정</span>
    </div>
</div>
<!-- ============================= [ 좌측 수업 아이템 서브메뉴 END ] ============================================ -->
		
<!-- ============================= [ modal-sm START ] ============================================ -->
<div class="modal fade" id="modal-gx-stchr">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">수업강사 변경 / 삭제 / 강제 수업체크</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem; color: #000; opacity: 0.5;">
                    	<span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                	<div class="input-group input-group-sm mb-1"><div class="input-group input-group-sm mb-1">
                		<input type="hidden" name="gx_schd_mgmt_sno" id="gx_schd_mgmt_sno" />
                    	<select class="select2 form-control" style="width: 250px;" name="ch_gx_stchr_id" id="ch_gx_stchr_id">
                    		<option>강사 선택</option>
                    	<?php foreach ($tchr_list as $r) : ?>
    						<option value="<?php echo $r['MEM_ID']?>">[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?> </option>
    					<?php endforeach; ?>
    					</select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default"  data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="btn_gx_stchr_delete();">삭제하기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_gx_stchr_change();">변경하기</button>
                </div>
            </div>
        </div>
	</div>
</div>
<!-- ============================= [ modal-sm END ] ============================================== -->	

<!-- ============================= [ modal-sm START ] ============================================ -->
<div class="modal fade" id="modal-gx-copy">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    				<h4 class="modal-title">현재주간 스케쥴 복사</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem; color: #000; opacity: 0.5;">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                	<div class="input-group input-group-sm mb-1">
                    	<span class="input-group-append">
                    		<span class="input-group-text" style='width:150px'>언제까지 복사할까요</span>
                    	</span>
                    	<input type="text" class="form-control" name="pop_copy_edate" id="pop_copy_edate">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default"  data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_gx_copy_proc();">복사하기</button>
                </div>
            </div>
        </div>
	</div>
</div>
<!-- ============================= [ modal-sm END ] ============================================== -->	

<!-- ============================= [ 그룹수업 수정하기 modal START ] ============================================ -->
<div class="modal fade" id="modal-group-class-edit">
	<div class="modal-dialog" style="max-width: 520px; width: 520px;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">그룹수업 수정하기</h4>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem; color: #000; opacity: 0.5;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				
				<!-- 담당강사와 참석 가능한 이용권 좌우 배치 -->
				<div class="row mb-3">
					<div class="col-6">
						<div class="form-group">
							<label for="edit_class_name" class="form-label">수업 이름</label>
							<input type="text" class="form-control" id="edit_class_name" placeholder="스피닝">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="edit_instructor" class="form-label">담당강사</label>
							<select class="form-control" id="edit_instructor">
								<option value="">강사 선택</option>
								<?php foreach ($view['tchr_list'] as $tchr): ?>
								<option value="<?php echo $tchr['MEM_ID']; ?>"><?php echo $tchr['MEM_NM']; ?> (<?php echo $tchr['TCHR_POSN_NM']; ?>)</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
				
				<!-- 수업 시간 -->
				<div class="row mb-3">
					<div class="col-3">
						<label for="edit_duration" class="form-label">수업 시간</label>
						<div class="input-group input-group-sm">
							<input type="number" class="form-control" id="edit_duration" value="50" min="0" step="1" oninput="validateNumberInput(this)">
							<span class="input-group-text">분</span>
						</div>
					</div>
					<div class="col-3">
						<label for="edit_participants" class="form-label">이용권 차감횟수</label>
						<div class="input-group input-group-sm">
							<input type="number" class="form-control" id="edit_participants" value="1" min="0" step="1" oninput="validateNumberInput(this)">
							<span class="input-group-text">회</span>
						</div>
					</div>
					<div class="col-3">
						<label for="edit_capacity" class="form-label">수업 정원 인원</label>
						<div class="input-group input-group-sm">
							<input type="number" class="form-control" id="edit_capacity" value="28" min="0" step="1" oninput="validateNumberInput(this); handleCapacityChange(this);">
							<span class="input-group-text">명</span>
						</div>
					</div>
					<div class="col-3">
						<label for="edit_max_capacity" class="form-label">대기 가능 인원</label>
						<div class="input-group input-group-sm">
							<input type="number" class="form-control" id="edit_max_capacity" value="10" min="0" step="1" oninput="validateNumberInput(this)">
							<span class="input-group-text">명</span>
						</div>
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-6">
						<div class="form-group">
							<label class="form-label">참석 가능한 이용권</label>
							<button type="button" id="btn-ticket-selection" class="btn btn-outline-primary btn-sm" style="width: 100%;" onclick="openTicketSelectionModal();">
								참석 가능한 이용권 없음 (선택추가)
							</button>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<div class="d-flex align-items-center">
								<label for="edit_instructor" class="form-label mb-0 me-2">자리 예약 가능</label>
								<div class="form-check form-switch mb-0">
									<input class="form-check-input" type="checkbox" id="edit_reservation" checked onchange="toggleReservationField(); handleReservationToggle();">
									<label class="form-check-label" for="edit_reservation"></label>
								</div>
							</div>
							<div class="mt-1">
								<input type="number" class="form-control form-control-sm" id="edit_reservation_num" style="width: 60px; display: inline-block;" min="0" step="1" oninput="validateNumberInput(this); handleReservationNumChange(this);">
								<span class="ms-2">명</span>
							</div>
						</div>
					</div>
				</div>
				
				<!-- 수업 이미지 선택 -->
				<div class="form-group mb-3">
					<div class="d-flex align-items-center mb-2">
						<label class="form-label mb-0">회원앱 수업 이미지</label>
						<button type="button" class="btn btn-outline-primary btn-xs ms-2" style="font-size: 10px; padding: 2px 8px;" onclick="openClassImageModal();">설정하기</button>
					</div>
					<div class="row align-items-start">
						<div class="col-4">
							<div class="border text-center p-2" style="cursor: pointer;" onclick="selectClassImage(this);">
								<div style="width: 100%; height: 56px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
									<span style="color: #6c757d; font-size: 12px;">이미지 1</span>
								</div>
							</div>
						</div>
						<div class="col-8">
							<div class="alert alert-warning mb-0" style="padding: 6px 10px; font-size: 11px; line-height: 1.3;">
								회원앱에서 그룹수업 자리 예약시, 수업이 진행되는 룸(장소)을 보고 특정 자리를 예약하는데 도움을 줄 수 있습니다.
							</div>
						</div>
					</div>
				</div>
				
				<!-- 수업 공개/폐쇄 설정 -->
				<div class="form-group mb-3">
					<div class="d-flex align-items-center mb-2">
						<label class="form-label mb-0">수업 공개/폐쇄</label>
						<button type="button" class="btn btn-outline-primary btn-xs ms-2" style="font-size: 10px; padding: 2px 8px;" onclick="openAutoScheduleModal();">자동 공개/폐강 설정</button>
					</div>
					<div class="row">
						<div class="col-4">
							<div class="d-flex align-items-center">
								<span class="badge bg-primary" style="height:19px; font-size:12px;">공개</span>
								<span class="small px-2 py-1 ms-2" style="background-color: #e9ecef; color: #495057; border-radius: 4px;" id="open_schedule_text">1일(달) 전, 13시 00분</span>
							</div>
						</div>
						<div class="col-8">
							<div class="d-flex align-items-center">
								<span class="badge bg-danger" style="height:19px; font-size:12px;">폐강</span>
								<span class="small px-2 py-1 ms-2" style="background-color: #e9ecef; color: #495057; border-radius: 4px;" id="close_schedule_text">미설정</span>
							</div>
						</div>
					</div>
				</div>
				
				<!-- 수업정산 설정 -->
				<div class="mb-3">
					<div class="form-group">
						<label class="form-label">수업비 정산방법 설정</label>
						<button type="button" id="btn-settlement-setup" class="btn btn-outline-primary btn-xs ms-2" style="font-size: 10px; padding: 2px 8px;">설정하기</button>
					</div>
					
					<!-- 수업정산 설정 내역 표시 -->
					<div id="settlement-display" class="mt-2 p-2" style="background-color: #f8f9fa; border-radius: 4px; border-left: 3px solid #007bff; font-size: 13px; line-height: 1.4;">
						미설정
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
				<button type="button" class="btn btn-primary" onclick="saveGroupClass();">수업 수정</button>
			</div>
		</div>
	</div>
</div>
<!-- ============================= [ 그룹수업 수정하기 modal END ] ============================================== -->
<div class="modal fade" id="modal-ticket-selection">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
				<h4 class="modal-title">참석 가능한 이용권 설정</h4>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem; color: #000; opacity: 0.5;">
					<span aria-hidden="true">&times;</span>
				</button>
            </div>
            <div class="modal-body">
				<div class="row mb-2">
					<label class="form-label col-form-label col-md-2" for="ticket-search">검색어</label>
					<div class="col-md-3 ps-0">
						<input type="text" class="form-control" id="ticket-search" placeholder="이용권 정보 검색..." onkeyup="filterTicketList()">
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-6">
						<span id="selected-ticket-count">선택된 이용권 : 10개</span>
					</div>
					<div class="col-6 text-end">
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="checkbox" id="show-stopped-tickets" onchange="loadTicketList()">
							<label class="form-check-label" for="show-stopped-tickets">판매중지 이용권 보기</label>
						</div>
					</div>
				</div>
				<div class="table-responsive" style="height: 400px; overflow-y: auto; border: 1px solid #dee2e6;">
					<table class="table table-bordered mb-0">
						<thead class="table-secondary sticky-top">
							<tr>
								<th style="width: 50px;">
									<input type="checkbox" id="select-all-tickets" onchange="toggleAllTickets(this)">
								</th>
								<th>이용권 정보</th>
								<th style="width: 100px;">판매 상태</th>
								<th style="width: 100px;">이용권 번호</th>
							</tr>
						</thead>
						<tbody id="ticket-list">
							<!-- 이용권 목록이 동적으로 생성됩니다 -->
						</tbody>
					</table>
				</div>
            	<!-- FORM [END] -->
            </div>
           
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
				<button type="button" class="btn btn-primary" onclick="saveTicketSelection();">저장</button>
			</div>
        </div>
    </div>
</div>
<!-- =====
<!-- ============================= [ 참석 가능한 이용권 설정 modal END ] ============================================== -->

<!-- ============================= [ 수업정산 설정 modal START ] ============================================== -->
<div class="modal fade" id="modal-settlement-setup">
    <div class="modal-dialog" style="max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
				<h4 class="modal-title">수업 정산 설정</h4>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
            </div>
            <div class="modal-body" style="padding: 20px;">
								<!-- 수업에 0명 참석 시 정산 여부 -->
				<div class="mb-4">
					<div class="d-flex align-items-center">
						<span>수업에 0명 참석 시&nbsp;&nbsp;수업비 지급</span>
						<input class="form-check-input ms-2" type="checkbox" id="zero_attendance_payment">
					</div>
				</div>
				
				<!-- 수업 출석 인원당 수당 설정 -->
				<div class="mb-4">
					<div class="d-flex align-items-center mb-3">
						<span class="me-2">수업 출석 인원에 따른 회당 수업비 요율 적용</span>
						<input class="form-check-input" type="checkbox" id="attendance_based_payment" onchange="toggleAttendanceBasedPayment()">
					</div>
					
					<!-- 체크 시 표시되는 설명 -->
					<div id="attendance_based_description" style="display: none;">
						<div class="alert alert-info" style="padding: 8px; font-size: 12px; margin-bottom: 15px;">
							수업 인원에 따라 수업비 요율이 적용됩니다.<br>
							<strong>구간에 제외된 인원수는 100% 지급됩니다.</strong>
						</div>
					</div>
					
					<!-- 미체크 시 표시되는 설명 -->
					<div id="fixed_payment_description">
						<div class="alert alert-secondary" style="padding: 8px; font-size: 12px; margin-bottom: 15px;">
							수업 인원에 상관없이 회당 수업비가 지급됩니다.
						</div>
					</div>
					
					<!-- 구간별 수당 설정 영역 -->
					<div id="range_settings" style="display: none;">
						<div class="d-flex align-items-center mb-2 settlement-range" data-range-index="0">
							<input type="number" class="form-control form-control-sm text-center me-1 range-start" id="range_start" value="0" min="0" style="width: 60px;" disabled oninput="validateNumberInput(this)">
							<span class="small me-2">명 부터</span>
							<input type="number" class="form-control form-control-sm text-center me-1 range-end" id="range_end" value="28" min="1" style="width: 60px;" oninput="validateRangeInputs(this); validateNumberInput(this)">
							<span class="small me-2">명 까지 1 회당 수업비의</span>
							<input type="number" class="form-control form-control-sm text-center me-1 range-percent" id="range_percent" value="0" min="0" max="100" style="width: 60px;" oninput="validateNumberOnly(this); validateNumberInput(this)">
							<span class="small">%</span>
						</div>
						
						<!-- 구간 추가 버튼 -->
						<div class="mb-3">
							<button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="addSettlementRange();">
								+ 구간 추가
							</button>
						</div>
					</div>
				</div>
            </div>
           
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
				<button type="button" class="btn btn-primary" id="btn-save-settlement">저장</button>
			</div>
        </div>
    </div>
</div>
<!-- ============================= [ 수업정산 설정 modal END ] ============================================== -->

<!-- ============================= [ 자동 공개/폐강 설정 modal START ] ============================================== -->
<div class="modal fade" id="modal-auto-schedule">
    <div class="modal-dialog" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
				<h4 class="modal-title">수업 자동 공개 · 폐강 설정</h4>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem; color: #000; opacity: 0.5;">
					<span aria-hidden="true">&times;</span>
				</button>
            </div>
            <div class="modal-body" style="padding: 20px;">
				<!-- 자동 공개 설정 -->
				<div class="mb-3">
					<div class="d-flex align-items-center mb-3">
						<i class="far fa-calendar-plus text-primary me-2"></i>
						<span class="fw-bold text-primary">자동 공개 설정</span>
					</div>
					<div class="alert alert-light border-primary" style="padding: 10px; font-size: 13px; margin-bottom: 15px; background-color: #f8f9ff;">
					수업 공개를 일 단위로 설정 시 <span class="text-primary fw-bold">1</span>일씩 예약할 수 있도록 공개됩니다.
					</div>
					
					<div class="form-check mb-3">
						<input class="form-check-input" type="checkbox" id="auto_open_enable">
						<label class="form-check-label" for="auto_open_enable" style="font-size: 14px;">
							수업 자동 공개 기능을 사용합니다.
						</label>
					</div>
					
					<div id="auto_open_settings" style="display: none;">
						<div class="d-flex align-items-center flex-wrap mb-2" style="gap: 3px;">
							<span class="small">해당 수업을</span>
							<input type="number" class="form-control form-control-sm text-center" id="auto_open_days" value="1" min="1" max="30" style="width: 60px;" oninput="validateNumberInput(this)">
							<select class="form-control form-control-sm" id="auto_open_unit" style="width: 60px;">
								<option value="day">일</option>
								<option value="week">주</option>
							</select>
							<span class="small">전,</span>
							<select class="form-control form-control-sm" id="auto_open_weekday" style="width: 80px; display: none;">
								<option value="1">월요일</option>
								<option value="2">화요일</option>
								<option value="3">수요일</option>
								<option value="4">목요일</option>
								<option value="5">금요일</option>
								<option value="6">토요일</option>
								<option value="0">일요일</option>
							</select>
							<select class="form-control form-control-sm" id="auto_open_hour" style="width: 60px;">
								<?php for($h = 0; $h <= 23; $h++): ?>
								<option value="<?php echo sprintf('%02d', $h); ?>" <?php echo $h == 13 ? 'selected' : ''; ?>><?php echo sprintf('%02d', $h); ?></option>
								<?php endfor; ?>
							</select>
							<span class="small">시</span>
							<select class="form-control form-control-sm" id="auto_open_minute" style="width: 60px;">
								<option value="00" selected>00</option>
								<option value="30">30</option>
							</select>
							<span class="small">분에</span>
						</div>
					</div>
					
					<div class="text-primary fw-bold mt-2" id="auto_open_result" style="font-size: 15px;">
						<span class="text-primary">1</span>일씩 예약할 수 있도록 공개됩니다.
					</div>
				</div>
				
				<hr style="margin: 20px 0;">
				
				<!-- 자동 폐강 설정 -->
				<div class="mb-3">
					<div class="d-flex align-items-center mb-3">
						<i class="fas fa-times-circle text-danger me-2"></i>
						<span class="fw-bold text-danger">자동 폐강 설정</span>
					</div>
					
					<div class="form-check mb-3">
						<input class="form-check-input" type="checkbox" id="auto_close_enable">
						<label class="form-check-label" for="auto_close_enable" style="font-size: 14px;">
							수업 예약인원 미달 시 자동 폐강 기능을 사용합니다.
						</label>
					</div>
					
					<div id="auto_close_settings" style="display: none;">
						<div class="row align-items-center mb-3">
							<div class="col-auto">
								<span class="small">수업시작시간</span>
							</div>
							<div class="col-3">
								<select class="form-control form-control-sm" id="auto_close_time">
									<option value="">선택</option>
									<option value="15" selected>15분전</option>
									<option value="30">30분전</option>
									<option value="60">1시간전</option>
									<option value="180">3시간전</option>
									<option value="1440">24시간(1일)전</option>
									<option value="4320">72시간(3일)전</option>
								</select>
							</div>
							<div class="col-auto">
								<span class="small">전까지 예약인원이</span>
							</div>
						</div>
						
						<div class="row align-items-center mb-2">
							<div class="col-auto">
								<span class="small">수업정원 28명중<span>
							</div>
							<div class="col-2">
								<input type="number" class="form-control form-control-sm text-center" id="auto_close_min_people" value="28" min="1" oninput="validateNumberInput(this)">
							</div>
							<div class="col-auto">
								<span class="small">명 이하일 경우 수업 폐강됩니다.</span>
							</div>
						</div>
					</div>
				</div>
            </div>
           
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
				<button type="button" class="btn btn-primary" onclick="saveAutoScheduleSettings();">설정 완료</button>
			</div>
        </div>
    </div>
</div>
<!-- ============================= [ 자동 공개/폐강 설정 modal END ] ============================================== -->

<!-- ============================= [ 수업 이미지 설정 modal START ] ============================================== -->
<div class="modal fade" id="modal-class-image">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
				<h4 class="modal-title">그룹 수업 이미지 등록</h4>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem; color: #000; opacity: 0.5;">
					<span aria-hidden="true">&times;</span>
				</button>
            </div>
            <div class="modal-body">
				<div class="mb-3">
					<div class="alert alert-info" style="padding: 10px; font-size: 13px;">
						회원앱에서 그룹수업 예약 시, 수업이 진행되는 룸(장소)을 보고 특정 자리를 예약하는데 도움을 줄 수 있습니다.<br>
						<strong>이미지 업로드 권장사항:</strong><br>
						• 비율: 4:3 (720픽셀 × 540픽셀 권장)<br>
						• 용량: 1MB 이하 권장 (최대 5MB)<br>
						• 형식: JPG/PNG만 업로드 가능
					</div>
				</div>
				
				<!-- 이미지 업로드 버튼 -->
				<div class="mb-3">
					<button type="button" class="btn btn-primary btn-sm" onclick="$('#class-image-upload').click();">
						<i class="fas fa-plus"></i> 이미지 추가
					</button>
					<input type="file" id="class-image-upload" accept="image/*" style="display: none;" onchange="uploadClassImage(this)">
				</div>
				
				<!-- 이미지 목록 -->
				<div class="row" id="class-image-list">
					<!-- 이미지들이 동적으로 추가됩니다 -->
				</div>
				
				<!-- 로딩 상태 -->
				<div id="image-loading" class="text-center" style="display: none;">
					<i class="fas fa-spinner fa-spin"></i> 이미지 업로드 중...
				</div>
            </div>
           
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
				<button type="button" class="btn btn-primary" onclick="saveClassImage();">저장</button>
			</div>
        </div>
    </div>
</div>
<!-- ============================= [ 수업 이미지 설정 modal END ] ============================================== -->

<!-- ============================= [ 스케줄 삭제 modal START ] ============================================== -->
<div class="modal fade" id="modal-delete-schedule">
    <div class="modal-dialog" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header bg-danger">
				<h4 class="modal-title text-white">스케줄 삭제</h4>
				<button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem; opacity: 0.8;">
					<span aria-hidden="true">&times;</span>
				</button>
            </div>
            <div class="modal-body" style="padding: 20px;">
				<div class="alert alert-warning" style="margin-bottom: 20px;">
					<i class="fas fa-exclamation-triangle"></i>
					<strong>주의:</strong> 선택한 기간의 모든 스케줄과 예약내역이 함께 삭제 됩니다. 이 작업은 되돌릴 수 없습니다.
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group mb-3">
							<label for="delete_start_date" class="form-label">삭제 시작일</label>
							<input type="date" class="form-control form-control-sm" id="delete_start_date">
							<small class="text-muted">오늘 이후 날짜만 선택 가능합니다.</small>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group mb-3">
							<label for="delete_end_date" class="form-label">삭제 종료일</label>
							<input type="date" class="form-control form-control-sm" id="delete_end_date">
							<small class="text-muted">시작일과 같거나 이후 날짜를 선택하세요.</small>
						</div>
					</div>
				</div>
				

				
				<div id="schedule_calendar_preview" style="margin-bottom: 20px;">
					<h6>선택 기간의 수업 일정 미리보기:</h6>
											<div id="schedule_preview_content" class="border rounded p-3" style="max-height: 150px; overflow-y: auto; background-color: #f8f9fa;">
						<p class="text-muted mb-0">날짜를 선택하면 해당 기간의 수업 일정을 확인할 수 있습니다.</p>
					</div>
				</div>
				
				<div id="delete_validation_message" class="alert alert-danger" style="display: none;"></div>
				
				<div id="delete_summary" class="alert alert-info" style="display: none;">
					<strong>삭제될 기간:</strong> <span id="delete_period_text"></span>
					<br><strong>삭제될 수업 수:</strong> <span id="delete_class_count">0</span>개
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
				<button type="button" class="btn btn-danger" id="btn-confirm-delete" onclick="confirmDeleteSchedule();" disabled>
					<i class="fas fa-trash-alt"></i> 스케줄 삭제
				</button>
			</div>
        </div>
    </div>
</div>
<!-- ============================= [ 스케줄 삭제 modal END ] ============================================== -->

<!-- ============================= [ 예약내역 조회 modal START ] ============================================== -->
<div class="modal fade" id="modal-reservation-history">
    <div class="modal-dialog modal-lg" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
				<h4 class="modal-title">
					예약내역 보기
				</h4>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem; color: #000; opacity: 0.5;">
					<span aria-hidden="true">&times;</span>
				</button>
            </div>
            <div class="modal-body" style="padding: 15px;">
				
				<!-- 수업 정보 표시 -->
				<div class="alert alert-info mb-3" style="padding: 10px;">
					<div class="row">
						<div class="col-4">
							<strong>수업명:</strong> <span id="reservation_class_title">-</span>
						</div>
						<div class="col-4">
							<strong>담당강사:</strong> <span id="reservation_instructor">-</span>
						</div>
						<div class="col-4">
							<strong>수업일시:</strong> <span id="reservation_class_datetime">-</span>
						</div>
					</div>

				</div>
				
				<!-- 예약 현황 통계 (버튼형) -->
				<div class="row mb-3" style="padding-bottom: 10px;">
					<div class="col-md-2">
						<button type="button" class="btn btn-outline-primary w-100 filter-btn" data-filter="confirmed" onclick="filterReservations('confirmed')">
							<div class="h5 mb-1" id="stat_current_reservations">0</div>
							<small>현재예약</small>
						</button>
					</div>
					<div class="col-md-2">
						<button type="button" class="btn btn-outline-success w-100 filter-btn" data-filter="attended" onclick="filterReservations('attended')">
							<div class="h5 mb-1" id="stat_attended_reservations">0</div>
							<small>출석</small>
						</button>
					</div>
					<div class="col-md-2">
						<button type="button" class="btn btn-outline-danger w-100 filter-btn" data-filter="absent" onclick="filterReservations('absent')">
							<div class="h5 mb-1" id="stat_absent_reservations">0</div>
							<small>결석</small>
						</button>
					</div>
					<div class="col-md-2">
						<button type="button" class="btn btn-outline-warning w-100 filter-btn" data-filter="waiting" onclick="filterReservations('waiting')">
							<div class="h5 mb-1" id="stat_waiting_reservations">0</div>
							<small>대기</small>
						</button>
					</div>
					<div class="col-md-2">
						<button type="button" class="btn btn-outline-secondary w-100 filter-btn" data-filter="cancelled" onclick="filterReservations('cancelled')">
							<div class="h5 mb-1" id="stat_cancelled_reservations">0</div>
							<small>취소</small>
						</button>
					</div>
					<div class="col-md-2">
						<button type="button" class="btn btn-outline-dark w-100 filter-btn active" data-filter="all" onclick="filterReservations('all')">
							<div class="h5 mb-1">전체<span id="total_capacity_main" style="font-size: 11px; font-weight: normal;">(정원:28)</span></div>
							<small id="total_capacity_details">예약:<span id="total_reservations">0</span>, 잔여:<span id="total_remaining">28</span></small>
						</button>
					</div>
				</div>
				
				<!-- 회원 검색 및 예약 -->
				<div class="card mb-3">
					<div class="card-body" style="padding: 10px;">
						<div class="row">
							<div class="col-md-6">
								<input type="text" class="form-control form-control-sm" id="search_member_name" placeholder="회원명 검색 후 바로 예약하세요" onkeyup="searchMembers(event);">
							</div>
							<div class="col-md-6">
								<small class="text-muted">회원을 검색하여 표시된 이용권으로 바로 예약할 수 있습니다.</small>
							</div>
							<!-- 이용권 선택 영역 숨김 -->
							<div class="col-md-3" id="ticket_selection_area" style="display: none;">
								<select class="form-control form-control-sm" id="member_ticket_list">
									<option value="">이용권을 선택하세요</option>
								</select>
							</div>
							<!-- 예약 버튼 숨김 -->
							<div class="col-md-2" style="display: none;">
								<button type="button" class="btn btn-success btn-sm" style="width: 100%;" onclick="makeReservation();">
									예약
								</button>
							</div>
						</div>
						
						<!-- 회원 검색 결과 -->
						<div id="member_search_results" style="display: none; margin-top: 10px;">
							<div class="border rounded p-2" style="max-height: 150px; overflow-y: auto; background-color: #f8f9fa;">
								<div class="small text-muted mb-2">검색된 회원을 선택하세요:</div>
								<div id="member_list_container">
									<!-- 검색된 회원 목록이 여기에 표시됩니다 -->
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<!-- 예약자 목록 테이블 -->
				<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
					<table id="reservation_history_table" width="100%" class="table table-striped table-bordered align-middle text-nowrap " style="width: 100%;">
						<thead>
							<tr>
								<th width="40px" class="text-nowrap dt-orderable-asc dt-orderable-desc" style="text-align: center !important;">
									<span class="dt-column-title">번호</span>
								</th>
								<th class="text-nowrap dt-orderable-asc dt-orderable-desc" style="text-align: center !important;">
									<span class="dt-column-title">회원명</span>
								</th>
								<th class="text-nowrap dt-orderable-asc dt-orderable-desc" style="text-align: center !important;">
									<span class="dt-column-title">연락처</span>
								</th>
								<th class="text-nowrap dt-orderable-asc dt-orderable-desc" style="text-align: center !important;">
									<span class="dt-column-title">예약일시</span>
								</th>
								<th class="text-nowrap dt-orderable-asc dt-orderable-desc" style="text-align: center !important;">
									<span class="dt-column-title">상태</span>
								</th>
								<th class="text-nowrap dt-orderable-asc dt-orderable-desc" style="text-align: center !important;">
									<span class="dt-column-title">이용권</span>
								</th>
								<th width="60px" class="text-nowrap dt-orderable-asc dt-orderable-desc" style="text-align: center !important;">
									<span class="dt-column-title">좌석</span>
								</th>
								<th class="text-nowrap dt-orderable-asc dt-orderable-desc" style="text-align: center !important;">
									<span class="dt-column-title">관리</span>
								</th>
							</tr>
						</thead>
						<tbody id="reservation_history_tbody">
							<tr>
								<td colspan="8" class="text-center text-muted" style="padding: 40px; vertical-align: middle;">
									예약내역을 조회하고 있습니다...
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
			</div>
        </div>
    </div>
</div>
<!-- ============================= [ 예약내역 조회 modal END ] ============================================== -->

<!-- ============================= [ 스케줄 수정 modal START ] ============================================== -->
<div class="modal fade" id="modal-schedule-edit">
	<div class="modal-dialog" style="max-width: 520px; width: 520px;">
		<div class="modal-content">
			<div class="modal-header">
				<div>
					<h4 class="modal-title">스케줄 수정하기</h4>
					<small class="text-muted" id="schedule-item-info">아이템 정보 확인 중...</small>
				</div>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem; color: #000; opacity: 0.5;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				
				<!-- 수업 이름과 담당강사 좌우 배치 -->
				<div class="row mb-3">
					<div class="col-6">
						<div class="form-group">
							<label for="edit_schedule_title" class="form-label">수업 이름</label>
							<input type="text" class="form-control" id="edit_schedule_title" placeholder="스피닝">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="edit_schedule_instructor" class="form-label">담당강사</label>
							<select class="form-control" id="edit_schedule_instructor">
								<option value="">강사 선택</option>
								<?php foreach ($view['tchr_list'] as $tchr): ?>
								<option value="<?php echo $tchr['MEM_ID']; ?>"><?php echo $tchr['MEM_NM']; ?> (<?php echo $tchr['TCHR_POSN_NM']; ?>)</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
				
				<!-- 수업 설정 -->
				<div class="row mb-3">
					<div class="col-3">
						<label for="edit_schedule_duration" class="form-label">수업 시간</label>
						<div class="input-group input-group-sm">
							<input type="number" class="form-control" id="edit_schedule_duration" value="50" min="0" step="1" oninput="validateNumberInput(this)">
							<span class="input-group-text">분</span>
						</div>
					</div>
					<div class="col-3">
						<label for="edit_schedule_deduct" class="form-label">이용권 차감횟수</label>
						<div class="input-group input-group-sm">
							<input type="number" class="form-control" id="edit_schedule_deduct" value="1" min="0" step="1" oninput="validateNumberInput(this)">
							<span class="input-group-text">회</span>
						</div>
					</div>
					<div class="col-3">
						<label for="edit_schedule_capacity" class="form-label">수업 정원 인원</label>
						<div class="input-group input-group-sm">
							<input type="number" class="form-control" id="edit_schedule_capacity" value="28" min="0" step="1" oninput="validateNumberInput(this); handleScheduleCapacityChange(this);">
							<span class="input-group-text">명</span>
						</div>
					</div>
					<div class="col-3">
						<label for="edit_schedule_waiting" class="form-label">대기 가능 인원</label>
						<div class="input-group input-group-sm">
							<input type="number" class="form-control" id="edit_schedule_waiting" value="10" min="0" step="1" oninput="validateNumberInput(this)">
							<span class="input-group-text">명</span>
						</div>
					</div>
				</div>

				<!-- 참석 가능한 이용권과 자리 예약 가능 좌우 배치 -->
				<div class="row mb-3">
					<div class="col-6">
						<div class="form-group">
							<label class="form-label">참석 가능한 이용권</label>
							<button type="button" id="btn-schedule-ticket-selection" class="btn btn-outline-primary btn-sm" style="width: 100%;" onclick="openScheduleTicketSelectionModal();">
								참석 가능한 이용권 없음 (선택추가)
							</button>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<div class="d-flex align-items-center">
								<label for="edit_schedule_reservation" class="form-label mb-0 me-2">자리 예약 가능</label>
								<div class="form-check form-switch mb-0">
									<input class="form-check-input" type="checkbox" id="edit_schedule_reservation" onchange="toggleScheduleReservationField(); handleScheduleReservationToggle();">
									<label class="form-check-label" for="edit_schedule_reservation"></label>
								</div>
							</div>
							<div class="mt-1">
								<input type="number" class="form-control form-control-sm" id="edit_schedule_reservation_num" style="width: 60px; display: inline-block;" min="0" step="1" disabled oninput="validateNumberInput(this); handleScheduleReservationNumChange(this);">
								<span class="ms-2">명</span>
							</div>
						</div>
					</div>
				</div>
				
				<!-- 수업 이미지 (선택사항) -->
				<div class="form-group mb-3">
					<div class="d-flex align-items-center mb-2">
						<label class="form-label mb-0">회원앱 수업 이미지</label>
						<button type="button" class="btn btn-outline-primary btn-xs ms-2" style="font-size: 10px; padding: 2px 8px;" onclick="openScheduleClassImageModal();">설정하기</button>
					</div>
					<div class="row align-items-start">
						<div class="col-4">
							<div class="border text-center p-2" style="cursor: pointer;" onclick="selectScheduleClassImage(this);">
								<div style="width: 100%; height: 56px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
									<span style="color: #6c757d; font-size: 12px;">이미지 1</span>
								</div>
							</div>
						</div>
						<div class="col-8">
							<div class="alert alert-warning mb-0" style="padding: 6px 10px; font-size: 11px; line-height: 1.3;">
								회원앱에서 그룹수업 자리 예약시, 수업이 진행되는 룸(장소)을 보고 특정 자리를 예약하는데 도움을 줄 수 있습니다.
							</div>
						</div>
					</div>
				</div>
				
				<!-- 수업 공개/폐쇄 설정 -->
				<div class="form-group mb-3">
					<div class="d-flex align-items-center mb-2">
						<label class="form-label mb-0">수업 공개/폐쇄</label>
						<button type="button" class="btn btn-outline-primary btn-xs ms-2" style="font-size: 10px; padding: 2px 8px;" onclick="openScheduleAutoScheduleModal();">자동 공개/폐강 설정</button>
					</div>
					<div class="row">
						<div class="col-4">
							<div class="d-flex align-items-center">
								<span class="badge bg-primary" style="height:19px; font-size:12px;">공개</span>
								<span class="small px-2 py-1 ms-2" style="background-color: #e9ecef; color: #495057; border-radius: 4px;" id="schedule_open_schedule_text">미설정</span>
							</div>
						</div>
						<div class="col-8">
							<div class="d-flex align-items-center">
								<span class="badge bg-danger" style="height:19px; font-size:12px;">폐강</span>
								<span class="small px-2 py-1 ms-2" style="background-color: #e9ecef; color: #495057; border-radius: 4px;" id="schedule_close_schedule_text">미설정</span>
							</div>
						</div>
					</div>
				</div>
				
				<!-- 수업정산 설정 -->
				<div class="mb-3">
					<div class="form-group">
						<label class="form-label">수업비 정산방법 설정</label>
						<button type="button" id="btn-schedule-settlement-setup" class="btn btn-outline-primary btn-xs ms-2" style="font-size: 10px; padding: 2px 8px;" onclick="openScheduleSettlementSetupModal();">설정하기</button>
					</div>
					
					<!-- 수업정산 설정 내역 표시 -->
					<div id="schedule-settlement-display" class="mt-2 p-2" style="background-color: #f8f9fa; border-radius: 4px; border-left: 3px solid #007bff; font-size: 13px; line-height: 1.4;">
						미설정
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
				<button type="button" class="btn btn-danger" onclick="deleteSchedule();" style="margin-right: auto;">삭제</button>
				<button type="button" class="btn btn-primary" onclick="saveSchedule();">수정 완료</button>
				<button type="button" class="btn btn-info" onclick="btn_gx_stchr_attd();">수동 수업체크</button>
			</div>
		</div>
	</div>
</div>
<!-- ============================= [ 스케줄 수정 modal END ] ============================================== -->

<form name='form_copy_date' id='form_copy_date' method='post' action='/tbcoffmain/ajax_copy_schedule'>
	<input type='hidden' name='copy_sdate' id='copy_sdate' />
	<input type='hidden' name='copy_edate' id='copy_edate' />
	<input type='hidden' name='gx_room_mgmt_sno' value='<?php echo $gx_room_mgmt_sno; ?>' />
</form>
		
	</section>
<!-- /.content -->
    
<?=$jsinc ?>	

<input type="hidden" id="fullcalendar_proc_url" data-name="/tbcoffmain/grp_schedule_proc">
<input type="hidden" id="current_gx_room_mgmt_sno" value="<?= esc($gx_room_mgmt_sno) ?>">

<script src="/dist/js/amajs/ama_calendar.js"></script>
<script src="/assets/js/grp_schedule.js"></script>