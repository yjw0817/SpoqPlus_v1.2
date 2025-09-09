<!-- <div id="load_pre">
	<img src="/dist/img/spinner.gif" alt="loading">
</div> -->
<style>
	.custom-toast {
		z-index: 10000 !important; /* 기존 팝업보다 높게 설정 */
	}
</style>
<div class="modal fade" id="search_user_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h4 class="modal-title">사용자 선택</h4> 
                <button type="button" class="close"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
				<div class="row mb-2">
					<label class="form-label col-form-label col-md-2">검색어</label>
					<div class="col-md-3 ps-0">
						<input type="text" id="sEmployee" class="form-control">
					</div>
					<div class="col-md-auto ps-0">
						<button type="button" class="btn btn-inverse me-3" id="btnTxtSearch"><i class="fa fa-search"></i> 검색</button>
						<button type="button" class="btn btn-inverse btn-primary" id="btnEmpSave" data-bs-dismiss="modal">선택반영</button>
					</div>
				</div>
				<small> * 해당 권한에 이미 등록되어 있는 직원은 목록에서 제외됩니다.</small>
				<div class="com_ta2">
					<table id="gridEmployee" class="table table-striped table-bordered align-middle text-nowrap table-hover">
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

<div class="modal fade" id="modal_top_mem_search_form">
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
				<div class="input-group input-group-sm mb-1 col-sm-6">
					<span class="input-group-append">
						<span class="input-group-text" style='width:150px'>회원 이름</span>
					</span>
					<input class="form-control event-input me-1" type="text" placeholder="검색할 이름" name="search_mem_nm" id="search_mem_nm1" autocomplete='off' style="min-width:300px !important; max-width:300px !important; width:300px !important; flex:0 0 auto !important;">
					<span class="input-group-append">
						<a href="#" id="btn_search_nm1" class="serch_bt" type="button"><i class="fas fa-search"></i> 검색</a>
					</span>
				</div>
				
				<div class="input-group input-group-sm mb-1">
					
					<table class="table table-bordered table-hover table-striped col-md-12" id='top_search_mem_table'>
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

<div class="modal fade" id="modal_top_mem_search_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">회원 검색ㅎ</h4>
                <button type="button" class="close"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
           
            	<!-- FORM [START] -->
                <div class="input-group input-group-sm mb-1">
                	
                	<table class="table table-bordered table-hover table-striped col-md-12" id='top_search_mem_table'>
							<thead>
								<tr>
									<th>상태</th>
									<th>이름</th>
									<th>아이디</th>
									<th>전화번호</th>
									<th>생년월일</th>
									<th>성별</th>
									<th>옵션</th>
								</tr>
							</thead>
							<tbody>
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


<form name="form_top_search_user" id="form_top_search_user" method="post" action="/ttotalmain/info_mem" />
	<input type="hidden" name="top_search_mem_sno" id="top_search_mem_sno" />
</form>
<style>
/* 모달 전체 스타일 */
#modal_mem_insert_form .modal-content {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

/* 모달 헤더 스타일 */
#modal_mem_insert_form .modal-header {
    background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
    color: white;
    padding: 20px 25px;
    border: none;
}

#modal_mem_insert_form .modal-title {
    font-size: 1.3rem;
    font-weight: 600;
}

#modal_mem_insert_form .btn-close,
#modal_mem_insert_form .close {
    opacity: 1;
    color: white;
    background: transparent;
    border: none;
    font-size: 1.8rem;
    line-height: 1;
    padding: 0;
    margin: -10px -10px -10px auto;
}

#modal_mem_insert_form .close span {
    color: white;
    font-size: 2rem;
    font-weight: 400;
    text-shadow: 0 1px 3px rgba(0,0,0,0.5);
    opacity: 1;
    display: block;
    line-height: 0.8;
    background: rgba(0,0,0,0.2);
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

#modal_mem_insert_form .close:hover {
    color: white;
    opacity: 1;
    transform: scale(1.1);
}

#modal_mem_insert_form .close:hover span {
    color: #ffffff;
    text-shadow: 0 2px 4px rgba(0,0,0,0.7);
    background: rgba(0,0,0,0.3);
}

/* 체크인 번호 카드 */
.checkin-number-card {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(30, 136, 229, 0.15);
}

.checkin-number-card h6 {
    color: #1565c0;
    font-weight: 600;
    font-size: 0.95rem;
}

.checkin-number-card .fs-4 {
    font-size: 1.5rem !important;
    color: #1565c0;
}

/* 입력 그룹 스타일 */
#modal_mem_insert_form .input-group {
    margin-bottom: 18px;
}

#modal_mem_insert_form .input-group-text {
    background-color: #f5f9fc;
    border: 1px solid #e0e0e0;
    border-right: none;
    font-size: 0.9rem;
    font-weight: 500;
    color: #555;
    min-width: 150px;
    padding: 8px 15px;
}

#modal_mem_insert_form .input-group-text i {
    color: #1e88e5;
    margin-right: 8px;
    font-size: 0.9rem;
}

#modal_mem_insert_form .input-group-text .text-danger {
    color: #e91e63 !important;
    font-weight: bold;
    margin-left: 3px;
}

#modal_mem_insert_form .form-control {
    border: 1px solid #e0e0e0;
    border-left: none;
    padding: 8px 15px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

#modal_mem_insert_form .form-control:focus {
    border-color: #1e88e5;
    box-shadow: 0 0 0 0.2rem rgba(30, 136, 229, 0.15);
    background-color: #fafbfc;
}

/* 성별 라디오 버튼 스타일 */
#modal_mem_insert_form .form-check-input:checked {
    background-color: #1e88e5;
    border-color: #1e88e5;
}

/* 전화번호 입력 상태 */
.phone-status {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
}

.phone-status i {
    font-size: 1.2rem;
}

#modal_mem_insert_form .form-control.is-valid {
    border-color: #28a745;
    background-image: none !important;
    padding-right: 2.5rem;
}

#modal_mem_insert_form .form-control.is-invalid {
    border-color: #dc3545;
    background-image: none !important;
    padding-right: 2.5rem;
}


/* 알림 메시지 */
#phone_duplicate_alert {
    border-radius: 8px;
    padding: 12px 18px;
}

/* 모달 본문 */
#modal_mem_insert_form .modal-body {
    padding: 20px 25px;
    background-color: #fafbfc;
}

/* 모달 푸터 */
#modal_mem_insert_form .modal-footer {
    background-color: #f5f7fa;
    border-top: 1px solid #e0e0e0;
    padding: 15px 25px;
}

/* 버튼 스타일 */
#modal_mem_insert_form .btn-success {
    background-color: #1e88e5;
    border-color: #1e88e5;
    padding: 8px 25px;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.3s ease;
}

#modal_mem_insert_form .btn-success:hover {
    background-color: #1565c0;
    border-color: #1565c0;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

#modal_mem_insert_form .btn-default {
    background-color: #fff;
    border: 1px solid #ddd;
    color: #666;
    padding: 8px 25px;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.3s ease;
}

#modal_mem_insert_form .btn-default:hover {
    background-color: #f5f5f5;
    border-color: #ccc;
}

/* 애니메이션 */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.checkin-number-card, .password-info {
    animation: slideDown 0.4s ease-out;
}

/* 추가 스타일 개선 */
#modal_mem_insert_form .modal-dialog {
    max-width: 700px;
}

/* 회원 등록 모달 - 사진 등록 관련 스타일 */
.new-member-photo-row {
    display: flex;
    align-items: flex-end;
    gap: 15px;
}

.new-member-photo-action {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100px;
}

.new-member-photo-guide-text {
    font-size: 13px;
    color: #555;
    margin-bottom: 10px;
    line-height: 1.4;
}

.new-member-photo-wrapper {
    position: relative;
    display: inline-block;
    width: 100px;
    height: 100px;
}

.new-member-preview-photo {
    width: 100px;
    height: 100px;
    object-fit: cover;
    align-content: center;
    text-align: center;
}

/* 얼굴 가이드 오버레이 */
.passport-guide {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 200px;
    height: 250px;
    border: 3px dashed #1e88e5;
    border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
    pointer-events: none;
    opacity: 0.7;
}

/* 캡처 버튼 스타일 */
.capture-btn {
    background-color: #1e88e5;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
}

.capture-btn:hover {
    background-color: #1565c0;
    transform: translateY(-1px);
}

/* 비디오 스타일 */
#new_member_camera_stream {
    border: 2px solid #1e88e5;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* 사진 촬영 섹션 */
#modal_mem_insert_form .border.rounded {
    background-color: #f8f9fa;
    border-color: #e0e0e0 !important;
}

/* 카메라 모달 스타일 */
#modal_new_member_camera .modal-header {
    background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
    color: white;
}

#modal_new_member_camera .modal-title {
    color: white;
}

#modal_new_member_camera .close {
    color: white;
    opacity: 1;
}

#modal_new_member_camera .close:hover {
    color: #e0e0e0;
}

/* z-index 설정으로 모달 위 모달 처리 */
#modal_new_member_camera {
    z-index: 1060;
}

#modal_new_member_camera .modal-backdrop {
    z-index: 1055;
}

#modal_mem_insert_form .input-group-sm .input-group-text,
#modal_mem_insert_form .input-group-sm .form-control {
    font-size: 0.95rem;
    padding: 10px 15px;
}

/* 라벨 정렬 */
#modal_mem_insert_form .input-group-text {
    justify-content: flex-start;
    align-items: center;
}

/* 성별 선택 영역 개선 */
#modal_mem_insert_form .gender-select {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-left: none;
    border-radius: 0 0.375rem 0.375rem 0;
    padding: 10px 15px;
    min-height: 42px;
}

/* 전화번호 필드 position relative */
#modal_mem_insert_form .phone-input-wrapper {
    position: relative;
}

/* 모달 오픈 애니메이션 */
#modal_mem_insert_form.fade .modal-dialog {
    transition: transform 0.3s ease-out;
}

#modal_mem_insert_form.show .modal-dialog {
    transform: none;
}

/* app-content의 높이를 auto로 변경하고 min-height 사용 */
.app-content {
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    min-height: calc(100vh - 65px) !important;
    height: auto !important; /* 고정 높이 제거 */
    overflow-y: visible !important; /* 스크롤을 visible로 변경 */

/* content-wrapper가 footer를 밀어내도록 설정 */
.content-wrapper {
    flex: 1 !important;
    min-height: 100% !important;
}

/* footer를 하단에 고정하되 콘텐츠에 따라 밀려나도록 */
.main-footer {
    margin-top: auto !important;
    flex-shrink: 0 !important;
}
</style>

<div class="modal fade" id="modal_mem_insert_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>회원 등록하기</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            
            	
            	<!-- FORM [START] -->
            	<form id="mem_insert_form">
					<input type="hidden" name="new_mem_id" id="new_mem_id" value="" />
					<input type="hidden" name="new_mem_pwd" id="new_mem_pwd" value="" />
					
					<!-- 체크인 번호 표시 영역 -->
					<div class="checkin-number-card p-3 mb-3" id="checkin_number_section" style="display: none; background-color: #f8f9fa; border-radius: 8px;">
						<div class="row align-items-center">
							<div class="col-md-9">
								<h6 class="mb-1 text-primary"><i class="fas fa-check-circle me-2"></i>체크인 정보가 생성되었습니다</h6>
								<div class="d-flex align-items-center mb-1">
									<span class="fs-4 fw-bold text-dark me-3" id="checkin_number_display">-</span>
									<div>
										<small class="text-muted">체크인 번호</small>
									</div>
								</div>
								<div class="d-flex align-items-center">
									<small class="text-muted me-2">비밀번호:</small>
									<span id="password_display" class="fw-bold">-</span>
									<small class="text-dark ms-3" style="font-weight: 500;"><i class="fas fa-info-circle me-1 text-primary"></i>비밀번호는 체크인 번호 + 5898 형식입니다</small>
								</div>
							</div>
							<div class="col-md-3 text-end">
								<i class="fas fa-id-card fa-2x text-primary opacity-50"></i>
							</div>
						</div>
					</div>
					
					<!-- 전화번호 중복 경고 -->
					<div class="alert alert-danger mb-3" id="phone_duplicate_alert" style="display: none;">
						<i class="fas fa-exclamation-triangle me-2"></i>
						<span id="phone_duplicate_message">이미 등록된 전화번호입니다.</span>
					</div>
					
					<div class="input-group input-group-sm mb-3">
						<span class="input-group-text" style='width:150px'>
							<i class="fas fa-user"></i>회원 이름<span class="text-danger">*</span>
						</span>
						<input type="text" class="form-control" placeholder="회원 이름을 입력하세요" name="new_mem_nm" id="new_mem_nm" autocomplete='off' required>
					</div>
					
					<div class="input-group input-group-sm mb-3">
						<span class="input-group-text" style='width:150px'>
							<i class="fas fa-calendar-alt"></i>생년월일
						</span>
						<input type="text" class="form-control" placeholder="YYYY/MM/DD" name="bthday" id="new_new_bthday" autocomplete='off' data-inputmask="'mask': ['9999/99/99']" data-mask>
					</div>
					
					<div class="input-group input-group-sm mb-3">
						<span class="input-group-text" style='width:150px'>
							<i class="fas fa-venus-mars"></i>성별
						</span>
						<div class="gender-select d-flex align-items-center">
							<div class="form-check form-check-inline me-3">
								<input class="form-check-input" type="radio" id="new_radioGrpCate1" name="mem_gendr" value="M" checked>
								<label class="form-check-label" for="new_radioGrpCate1">남성</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" id="new_radioGrpCate2" name="mem_gendr" value="F">
								<label class="form-check-label" for="new_radioGrpCate2">여성</label>
							</div>
						</div>
					</div>
					
					<div class="input-group input-group-sm mb-3 position-relative">
						<span class="input-group-text" style='width:150px'>
							<i class="fas fa-phone"></i>전화번호<span class="text-danger">*</span>
						</span>
						<input type="text" class="form-control phone-input" placeholder="01012345678" name="mem_telno" id="new_mem_telno" autocomplete='off' required>
						<div class="phone-status" id="phone_status" style="display: none;">
							<i class="fas fa-check-circle text-success" id="phone_check_icon" style="display: none;"></i>
							<i class="fas fa-times-circle text-danger" id="phone_error_icon" style="display: none;"></i>
						</div>
					</div>
					
					<div class="input-group input-group-sm mb-3">
						<span class="input-group-text" style='width:150px'>
							<i class="fas fa-envelope"></i>이메일
						</span>
						<input type="email" class="form-control" placeholder="example@email.com" name="mem_email" id="new_mem_email" autocomplete='off'>
					</div>
					
					<div class="input-group input-group-sm mb-3">
						<span class="input-group-text" style='width:150px'>
							<i class="fas fa-map-marker-alt"></i>주소
						</span>
						<input type="text" class="form-control" placeholder="주소를 입력하세요" name="mem_addr" autocomplete='off'>
					</div>
					
					
					<!-- 회원 사진 등록 섹션 -->
					<div class="mt-4 p-3 border rounded">
						<h6 class="mb-3"><i class="fas fa-camera me-2"></i>회원 사진 등록</h6>
						<div class="new-member-photo-row">
							<!-- 사진 썸네일 -->
							<div class="new-member-photo-wrapper">
								<img class="new-member-preview-photo"
									id="new_member_photo_preview" 
									src="/dist/img/default_profile.png" 
									alt="회원사진"
									style="border-radius: 10%; cursor: pointer; border: 2px solid #ddd;"
									onclick="showNewMemberFullPhoto()"
									onerror="this.src='/dist/img/default_profile.png'" >
							</div>
							<!-- 오른쪽 텍스트 + 버튼 -->
							<div class="new-member-photo-action">
								<!-- 안내 문구 -->
								<div class="new-member-photo-guide-text">
									<i class="fas fa-info-circle text-info"></i> 정면을 바라보며,<br>
									상반신이 잘 보이도록 촬영해주세요.
								</div>
								<!-- 버튼: 사진과 같은 행에, 하단 정렬 -->
								<button type="button" class="btn btn-primary btn-sm" onclick="openNewMemberCameraModal()">
									<i class="fas fa-camera"></i> 사진 촬영
								</button>
							</div>
						</div>

						<input type="hidden" id="new_member_captured_photo" name="captured_photo" />
						
						<!-- 얼굴 인식 데이터 필드들 -->
						<input type="hidden" id="new_member_face_encoding_data" name="face_encoding_data" />
						<input type="hidden" id="new_member_glasses_detected" name="glasses_detected" value="0" />
						<input type="hidden" id="new_member_quality_score" name="quality_score" value="0" />
					</div>
					
					
            	
            	</form>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="mem_insert_btn">등록하기</button>
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<!-- 카메라 촬영 모달 -->
<div class="modal fade" id="modal_new_member_camera" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-camera me-2"></i>회원 사진 촬영</h5>
                <button type="button" class="close" onclick="closeNewMemberCameraModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <p class="text-muted">
                        <i class="fas fa-info-circle"></i> 정면을 바라보며 상반신이 잘 보이도록 촬영해주세요.
                    </p>
                </div>
                
                <!-- 웹캠 영상 -->
                <div id="new_member_camera_container">
                    <video id="new_member_camera_stream" autoplay playsinline style="width: 100%; max-width: 600px; border-radius: 10px;"></video>
                </div>
                
                <!-- 캡처된 이미지 미리보기 -->
                <div id="new_member_captured_preview" style="display: none;">
                    <img id="new_member_captured_image" style="width: 100%; max-width: 600px; border-radius: 10px;" />
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <div id="camera_buttons">
                    <button type="button" class="btn btn-success" onclick="captureNewMemberPhoto()">
                        <i class="fas fa-camera"></i> 촬영
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeNewMemberCameraModal()">
                        <i class="fas fa-times"></i> 취소
                    </button>
                </div>
                <div id="preview_buttons" style="display: none;">
                    <button type="button" class="btn btn-primary" onclick="confirmNewMemberPhoto()">
                        <i class="fas fa-check"></i> 사용
                    </button>
                    <button type="button" class="btn btn-warning" onclick="retakeNewMemberPhoto()">
                        <i class="fas fa-redo"></i> 다시 촬영
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeNewMemberCameraModal()">
                        <i class="fas fa-times"></i> 취소
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /.content -->
 
<footer class="main-footer">
	<strong>Copyright &copy; 2024 <a href="http://www.SpoQone.com">Argos SpoQ</a>.
	</strong>
			All rights reserved. 
		<div class="float-right d-none d-sm-inline-block">
		<b>[ Version 1.0 ]</b>
	</div>
</footer>
</div>
<!-- /.content-wrapper -->
<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
	<!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->


</div>



<!-- ./wrapper -->
<div id="fr_storeId" data-name="main" style='display:none' ></div>
<!-- REQUIRED SCRIPTS -->






<script>
	window.onload = function() { 
		setTimeout(() => $('#load_pre').hide(),100);
	}
</script>

<script>

var sitenm = "";

$(function () {
	$("#top_search").on("keyup",function(key){
		if(key.keyCode==13) {
			ff_tsearch();
		}
	});
	
	// 회원등록 모달이 열릴 때 초기화
	$('#modal_mem_insert_form').on('show.bs.modal', function () {
		$('#mem_insert_form')[0].reset();
		$('#new_mem_id').val('');
		$('#new_mem_pwd').val('');
		$('#checkin_number_section').hide();
		$('#checkin_number_display').text('-');
		$('#password_display').text('-');
		$('#phone_duplicate_alert').hide();
		$('#phone_status').hide();
		$('.form-control').removeClass('is-valid is-invalid');
		
		// 사진 초기화
		$('#new_member_photo_preview').attr('src', '/dist/img/default_profile.png');
		$('#new_member_captured_photo').val('');
		$('#new_member_face_encoding_data').val('');
		$('#new_member_glasses_detected').val('0');
		$('#new_member_quality_score').val('0');
		$('#new_member_camera_wrap').hide();
		stopNewMemberCamera();
	});
	
	// 모달이 닫힐 때 카메라 종료
	$('#modal_mem_insert_form').on('hidden.bs.modal', function () {
		stopNewMemberCamera();
		// 카메라 모달도 강제로 닫기
		$('#modal_new_member_camera').modal('hide');
	});
	
	// 카메라 모달이 닫힐 때도 카메라 종료
	$('#modal_new_member_camera').on('hidden.bs.modal', function () {
		stopNewMemberCamera();
	});

	$(".phone-input").on("input", function(e) {
		// 숫자만 입력되도록 처리
		let value = $(this).val();
		value = value.replace(/[^0-9]/g, '');
		// 최대 11자리까지만 허용
		if (value.length > 11) {
			value = value.slice(0, 11);
		}
		$(this).val(value);
		
		// 회원등록 모달에서 전화번호 입력시 체크인 번호 생성
		if ($(this).attr('id') === 'new_mem_telno') {
			const phoneLength = $(this).val().length;
			
			if (phoneLength < 11) {
				// 11자리 미만일 때
				$('#checkin_number_section').hide();
						$('#phone_duplicate_alert').hide();
				$('#new_mem_telno').removeClass('is-valid is-invalid');
				$('#phone_status').hide();
			} else if (phoneLength === 11) {
				// 정확히 11자리일 때 체크인 번호 생성
				generateCheckinNumber($(this).val());
			} else {
				// 11자리 초과일 때
				$('#new_mem_telno').removeClass('is-valid').addClass('is-invalid');
				$('#phone_status').show();
				$('#phone_check_icon').hide();
				$('#phone_error_icon').show();
			}
		}
	}).on("focus", function() {
		if (!$(this).val()) {
			$(this).val(""); // 빈 값으로 시임
		}
    });

	$(".phone-input").on("keypress", function(e) {
		const key = String.fromCharCode(e.which);
		if (!/[0-9]/.test(key)) {
			e.preventDefault();
		}
	});
	
	// 체크인 번호 생성 함수
	function generateCheckinNumber(phoneNumber) {
		$.ajax({
			url: '/tmemmain/ajax_generate_mem_id',
			type: 'POST',
			data: { phone_number: phoneNumber },
			dataType: 'json',
			success: function(result) {
				if (result.result === 'true') {
					$('#new_mem_id').val(result.mem_id);
					$('#checkin_number_display').text(result.mem_id);
					$('#password_display').text(result.mem_id + '5898');
					$('#checkin_number_section').slideDown();
					$('#new_mem_telno').addClass('is-valid');
					$('#phone_status').show();
					$('#phone_check_icon').show();
					$('#phone_error_icon').hide();
					$('#phone_duplicate_alert').hide();
				} else if (result.is_duplicate) {
					$('#phone_duplicate_alert').slideDown();
					$('#phone_duplicate_message').text(result.msg);
					$('#checkin_number_section').hide();
								$('#new_mem_telno').removeClass('is-valid').addClass('is-invalid');
					$('#phone_status').show();
					$('#phone_check_icon').hide();
					$('#phone_error_icon').show();
				}
			},
			error: function() {
				console.log('체크인 번호 생성 중 오류가 발생했습니다.');
			}
		});
	}
	

	$('#mem_insert_btn').click(function(){
		// 실패일 경우 warning error success info question
		
		if ( $('#new_mem_nm').val() == "" )
		{
			alertToast('error','이름을 입력하세요');
			return;
		}
		
		if ( $('#new_mem_telno').val() == "" )
		{
			alertToast('error','전화번호를 입력하세요');
			return;
		}
		
		if ( $('#new_mem_id').val() == "" )
		{
			alertToast('error','전화번호를 올바르게 입력해주세요');
			return;
		}
		
		ToastConfirm.fire({
			icon: "question",
			title: "  확인 메세지",
			html: "<font color='#000000' >회원을 등록하시겠습니까?<br/>체크인 번호: <b>" + $('#new_mem_id').val() + "</b><br/>비밀번호: <b>" + $('#new_mem_id').val() + "5898</b></font>",
			showConfirmButton: true,
			showCancelButton: true,
			confirmButtonColor: "#28a745",
		}).then((result) => {
			if (result.isConfirmed) 
			{
				var params = $("#mem_insert_form").serialize();
				jQuery.ajax({
					url: '/tmemmain/ajax_mem_insert_proc',
					type: 'POST',
					data:params,
					contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
					dataType: 'text',
					success: function (result) {
						if ( result.substr(0,8) == '<script>' )
						{
							alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
							top_buy_user_select('01053624856');
							return;
						}
						
						json_result = $.parseJSON(result);
						if (json_result['result'] == 'true')
						{
							alertToast('success', '회원이 성공적으로 등록되었습니다.');
							setTimeout(function() {
								location.href = "/ttotalmain/info_mem/" + json_result['mem_sno'];
							}, 1500);
						} else if (json_result['is_duplicate']) {
							alertToast('error', json_result['msg']);
							$('#phone_duplicate_alert').slideDown();
							$('#phone_duplicate_message').text(json_result['msg']);
							$('#new_mem_telno').addClass('is-invalid');
						} else {
							alertToast('error', json_result['msg'] || '회원 등록 중 오류가 발생했습니다.');
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
		
	});
});

function loc_user_info(user_sno)
{
	location.href="/api/tmem_mem_event_list/"+user_sno;
}

//숫자만 리턴 ( - 미포함 )
function onlyNum2(num)
{
	if (num != '' && num != null)
	{
		var re_num = num.toString().replace(/[0-9]/gi, '');
    	var regex = /[^0-9]/g;
    	return num.replace(regex,"");
	}
}

//숫자만 리턴 ( -포함 )
function onlyNum(num)
{
	if (num != '' && num != null)
	{
		var re_num = num.toString().replace(/[^-0-9]/gi, '');
    	var regex = /[^0-9]/g;
    	if(re_num >= 0 )
    	{
    		return num.replace(regex,"");
    	} else 
    	{
    		return "-" + num.replace(regex,"");
    	}
	}
}

// 천단위 콤마 ( -포함 )
function currencyNum(num)
{
	if (num != '' && num != null)
	{
		return num.toString().replace(/(-?)\B(?=(\d{3})+(?!\d))/g, ",");
	}
}
	
var Toast = Swal.mixin({
      toast: true,
      position: 'top',
      showConfirmButton: true,
      confirmButtonText: '확인',
      timer: null, // 자동 닫기 비활성화
      width: '500px',
      footer: "Argos SpoQ",
      timerProgressBar: false,
      // allowOutsideClick와 allowEscapeKey는 toast 모드에서 지원되지 않음
      customClass: {
        confirmButton: 'btn btn-primary btn-sm'
      }
    });

var ToastConfirm = Swal.mixin({
      //toast: true,
      //position: 'top',
      showConfirmButton: false,
      width: '500px',
      footer: "Argos SpoQ",
	  customClass: {
			popup: 'custom-toast' // 커스텀 클래스 추가
		}
    }); 

	function alertToast(type,msg)
	{
		// 타입별 버튼 스타일 설정
		let buttonClass = 'btn btn-primary btn-sm';
		if (type === 'error') {
			buttonClass = 'btn btn-danger btn-sm';
		} else if (type === 'warning') {
			buttonClass = 'btn btn-warning btn-sm';
		} else if (type === 'success') {
			buttonClass = 'btn btn-success btn-sm';
		} else if (type === 'info') {
			buttonClass = 'btn btn-info btn-sm';
		}
		
		Toast.fire({
	        icon: type,
	        title: "  알림 메세지",
	        html: "<font color='#000000' >" + msg + "</font>",
	        customClass: {
	        	confirmButton: buttonClass
	        }
	    });	
	}
	    

	function ff_tsearch(sname)
	{
		var params = "sv="+$('#top_search').val();
		if(sname)
		{
			params = "sv=" + sname;
		} else if($('#top_search').val() == "")
		{
			return;
		}
    	jQuery.ajax({
            url: '/ttotalmain/top_search_proc',
            type: 'POST',
            data:params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
            dataType: 'text',
            beforeSend:function(){
            	$('#load_pre').show();
            },
            complete:function(){
            	setTimeout(() => $('#load_pre').hide(),100);
            },
            error:function(){
            	alert('로그인 인증이 만료 되었거나 처리중 오류가 있습니다. 다시 로그인 하세요.');
            	location.href="/tlogin";
            },
            success: function (result) {
				
				$('#top_search_mem_table > tbody > tr').remove();
    			json_result = $.parseJSON(result);
    			if (json_result['result'] == 'true')
    			{
     				//console.log(json_result);
     				
     				console.log(json_result['search_mem_list'].length);
     				
     				if (json_result['search_mem_list'].length == 0)
     				{
     					alertToast('error','검색된 정보가 없습니다.');
     					return;
     				}
     				
     				if (json_result['search_mem_list'].length == 1)
     				{
      					top_buy_user_select(json_result['search_mem_list'][0]['MEM_SNO']);
      					console.log(json_result['search_mem_list'][0]);
     					return;
     				}
     				
    				$('#modal_top_mem_search_form').modal("show");
    				
    				json_result['search_mem_list'].forEach(function (r,index) {
						var addTr = "<tr>";
						addTr += "<td>" + r['MEM_STAT_NM'] + "</td>";
						addTr += "<td>" + r['MEM_NM'] + "</td>";
						addTr += "<td>" + r['MEM_ID'] + "</td>";
						addTr += "<td>" + r['MEM_TELNO'] + "</td>";
						addTr += "<td>" + r['BTHDAY'] + "</td>";
						addTr += "<td>" + r['MEM_GENDR_NM'] + "</td>";
						addTr += "<td><button type=\"button\" class=\"btn btn-info btn-xs ac-btn\" onclick=\"top_buy_user_select('"+ r['MEM_SNO'] +"');\">선택</button></td>";
						addTr += "</tr>";
						
						$('#top_search_mem_table > tbody:last').append(addTr);
					});
					
    			} else 
    			{
    				console.log(json_result);
    			} 
            }
        });
	}
	
	function top_buy_user_select (mem_sno)
	{
		location.href="/ttotalmain/info_mem/"+mem_sno;
		//$('#top_search_mem_sno').val(mem_sno);
		//$('#form_top_search_user').submit();
	}

	function ff_tsearch_clear()
	{
		$("#top_tsearch").val('');
	}
	
	$('[data-mask]').inputmask();
	
	// 회원 등록 모달 - 카메라 관련 함수들
	let newMemberStream = null;
	let tempCapturedPhoto = null;
	
	function openNewMemberCameraModal() {
		// 카메라 모달 열기
		$('#modal_new_member_camera').modal('show');
		
		// 브라우저 호환성 체크
		if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
			console.error('getUserMedia is not supported in this browser/context');
			
			// HTTPS 체크
			if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost') {
				alert('카메라를 사용하려면 HTTPS 연결이 필요합니다.\n\n' +
					  '현재 연결: ' + window.location.protocol + '//' + window.location.hostname + '\n' +
					  'HTTPS로 접속하거나 시스템 관리자에게 문의하세요.');
			} else {
				alert('이 브라우저는 카메라 기능을 지원하지 않습니다.\nChrome, Firefox, Edge 등 최신 브라우저를 사용해주세요.');
			}
			$('#modal_new_member_camera').modal('hide');
			return;
		}
		
		// 카메라 시작
		navigator.mediaDevices.getUserMedia({
			video: true
		})
		.then(function(mediaStream) {
			newMemberStream = mediaStream;
			const video = document.getElementById('new_member_camera_stream');
			video.srcObject = mediaStream;
		})
		.catch(function(err) {
			console.error('카메라 접근 오류:', err);
			if (err.name === 'NotAllowedError') {
				alert('카메라 접근이 거부되었습니다.\n브라우저 설정에서 카메라 권한을 허용해주세요.');
			} else if (err.name === 'NotFoundError') {
				alert('카메라를 찾을 수 없습니다.\n카메라가 연결되어 있는지 확인해주세요.');
			} else if (err.name === 'NotReadableError') {
				alert('카메라가 이미 다른 프로그램에서 사용 중입니다.');
			} else {
				alert('카메라 접근 중 오류가 발생했습니다: ' + err.message);
			}
			$('#modal_new_member_camera').modal('hide');
		});
	}
	
	async function captureNewMemberPhoto() {
		console.log('📸 captureNewMemberPhoto 함수 호출됨!');
		
		const video = document.getElementById('new_member_camera_stream');
		const canvas = document.createElement('canvas');
		canvas.width = video.videoWidth;
		canvas.height = video.videoHeight;
		const ctx = canvas.getContext('2d');
		ctx.drawImage(video, 0, 0);
		
		// 📌 JPEG로 base64 생성 (품질 0.9)
		const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
		console.log('📸 Base64 이미지 생성 완료:', dataUrl.substring(0, 50) + '...');
		
		// 임시 저장
		tempCapturedPhoto = dataUrl;
		
		// 미리보기 표시
		document.getElementById('new_member_captured_image').src = dataUrl;
		document.getElementById('new_member_camera_container').style.display = 'none';
		document.getElementById('new_member_captured_preview').style.display = 'block';
		document.getElementById('camera_buttons').style.display = 'none';
		document.getElementById('preview_buttons').style.display = 'block';
		
		// 카메라 정지
		stopNewMemberCamera();
		
		// 🔍 얼굴 인식 처리 시작
		console.log('📸 얼굴 인식 함수 호출 시작...');
		processNewMemberFaceRecognition(dataUrl);
	}
	
	function confirmNewMemberPhoto() {
		// 썸네일 이미지 변경
		const preview = document.getElementById('new_member_photo_preview');
		preview.src = tempCapturedPhoto;
		
		// base64 저장
		document.getElementById('new_member_captured_photo').value = tempCapturedPhoto;
		
		// 얼굴 인식 데이터는 captureNewMemberPhoto에서 이미 처리됨
		// 성공 여부 확인
		const faceData = document.getElementById('new_member_face_encoding_data').value;
		if (faceData) {
			console.log('✅ 얼굴 인식 데이터가 포함된 사진 등록');
			alertToast('success', '사진과 얼굴 인식 데이터가 등록되었습니다.');
		} else {
			console.log('⚠️ 얼굴 인식 데이터 없이 사진만 등록');
			alertToast('success', '사진이 등록되었습니다.');
		}
		
		// 모달 닫기
		closeNewMemberCameraModal();
	}
	
	function retakeNewMemberPhoto() {
		// UI 초기화
		document.getElementById('new_member_camera_container').style.display = 'block';
		document.getElementById('new_member_captured_preview').style.display = 'none';
		document.getElementById('camera_buttons').style.display = 'block';
		document.getElementById('preview_buttons').style.display = 'none';
		
		// 카메라 다시 시작
		navigator.mediaDevices.getUserMedia({
			video: true
		})
		.then(function(mediaStream) {
			newMemberStream = mediaStream;
			const video = document.getElementById('new_member_camera_stream');
			video.srcObject = mediaStream;
		});
	}
	
	function closeNewMemberCameraModal() {
		// 카메라 정지
		stopNewMemberCamera();
		
		// UI 초기화
		document.getElementById('new_member_camera_container').style.display = 'block';
		document.getElementById('new_member_captured_preview').style.display = 'none';
		document.getElementById('camera_buttons').style.display = 'block';
		document.getElementById('preview_buttons').style.display = 'none';
		
		// 모달 닫기
		$('#modal_new_member_camera').modal('hide');
	}
	
	function stopNewMemberCamera() {
		if (newMemberStream) {
			newMemberStream.getTracks().forEach(track => track.stop());
			newMemberStream = null;
		}
	}
	
	function showNewMemberFullPhoto() {
		const photoSrc = document.getElementById('new_member_photo_preview').src;
		if (!photoSrc || photoSrc.includes('default_profile.png')) {
			alertToast('warning', '등록된 사진이 없습니다.');
			return;
		}
		
		// 새 창에서 이미지 표시
		const newWindow = window.open('', '_blank', 'width=600,height=800');
		newWindow.document.write(`
			<html>
			<head><title>회원 사진</title></head>
			<body style="margin:0; display:flex; justify-content:center; align-items:center; background:#000;">
				<img src="${photoSrc}" style="max-width:100%; max-height:100%; object-fit:contain;">
			</body>
			</html>
		`);
	}
	
	// 🔍 얼굴 인식 처리 함수 (info_mem2와 동일한 방식)
	async function processNewMemberFaceRecognition(imageBase64) {
		try {
			// 얼굴 인식 상태 표시
			showNewMemberFaceRecognitionStatus('processing', '얼굴 분석 중...');
			
			// Base64에서 data:image/jpeg;base64, 부분 제거
			let base64Data = imageBase64;
			if (base64Data.includes(',')) {
				base64Data = base64Data.split(',')[1];
			}
			
			// Base64 데이터 검증
			if (!base64Data || base64Data.length < 100) {
				throw new Error('유효하지 않은 이미지 데이터입니다.');
			}
			
			// 얼굴 인식 API 호출
			console.log('🔍 얼굴 인식 API 호출 시작...');
			console.log('Base64 데이터 크기:', base64Data.length);
			console.log('Base64 시작 부분:', base64Data.substring(0, 50) + '...');
			
			// 세션에서 comp_cd, bcoff_cd 가져오기 (PHP 변수를 JavaScript로 전달)
			const comp_cd = '<?= session()->get('comp_cd') ?>';
			const bcoff_cd = '<?= session()->get('bcoff_cd') ?>';
			
			console.log('📍 회사 코드 (param1):', comp_cd);
			console.log('📍 지점 코드 (param2):', bcoff_cd);
			
			const response = await fetch('/FaceTest/recognize_for_registration', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({
					image: base64Data,  // FaceTest controller expects 'image' field, not 'image_base64'
					param1: comp_cd,    // 회사 코드
					param2: bcoff_cd    // 지점 코드
				})
			});
			
			const result = await response.json();
			console.log('🔍 얼굴 인식 API 응답:', result);
			
			if (result.success && result.face_detected) {
				// 얼굴 검출 성공
				console.log('✅ 얼굴 검출 성공!');
				console.log('얼굴 데이터:', result.face_data);
				
				showNewMemberFaceRecognitionStatus('success', '얼굴이 정상적으로 인식되었습니다!');
				
				// 얼굴 인코딩 데이터 검증
				if (result.face_data && result.face_data.face_encoding && Array.isArray(result.face_data.face_encoding)) {
					console.log('얼굴 인코딩 배열 크기:', result.face_data.face_encoding.length);
					
					// 얼굴 데이터를 hidden 필드에 저장
					document.getElementById('new_member_face_encoding_data').value = JSON.stringify(result.face_data);
					document.getElementById('new_member_glasses_detected').value = result.face_data.glasses_detected ? '1' : '0';
					document.getElementById('new_member_quality_score').value = result.face_data.quality_score || 0.85;
					
					console.log('저장된 얼굴 데이터:', document.getElementById('new_member_face_encoding_data').value);
				} else {
					throw new Error('얼굴 인코딩 데이터가 유효하지 않습니다.');
				}
				
			} else {
				// 얼굴 검출 실패
				const errorMsg = result.error || '얼굴을 인식할 수 없습니다. 다시 촬영해주세요.';
				console.log('❌ 얼굴 검출 실패:', errorMsg);
				showNewMemberFaceRecognitionStatus('error', errorMsg);
				
				// 얼굴 데이터 초기화
				document.getElementById('new_member_face_encoding_data').value = '';
				document.getElementById('new_member_glasses_detected').value = '0';
				document.getElementById('new_member_quality_score').value = '0';
			}
		} catch (error) {
			console.error('얼굴 인식 오류:', error);
			showNewMemberFaceRecognitionStatus('error', '얼굴 인식 중 오류가 발생했습니다: ' + error.message);
			
			// 얼굴 데이터 초기화
			document.getElementById('new_member_face_encoding_data').value = '';
			document.getElementById('new_member_glasses_detected').value = '0';
			document.getElementById('new_member_quality_score').value = '0';
		}
	}
	
	// 얼굴 인식 상태 표시 함수
	function showNewMemberFaceRecognitionStatus(type, message) {
		// 카메라 모달의 상태 표시
		let statusDiv = document.getElementById('new_member_face_recognition_status');
		if (!statusDiv) {
			statusDiv = document.createElement('div');
			statusDiv.id = 'new_member_face_recognition_status';
			statusDiv.style.cssText = `
				margin: 10px 20px;
				padding: 10px 15px;
				border-radius: 6px;
				font-size: 14px;
				text-align: center;
			`;
			
			// 카메라 모달 body에 추가
			const modalBody = document.querySelector('#modal_new_member_camera .modal-body');
			if (modalBody) {
				modalBody.insertBefore(statusDiv, modalBody.querySelector('#new_member_camera_container'));
			}
		}
		
		// 타입에 따른 스타일 설정
		switch(type) {
			case 'processing':
				statusDiv.style.backgroundColor = '#e3f2fd';
				statusDiv.style.color = '#1565c0';
				statusDiv.style.border = '1px solid #90caf9';
				statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + message;
				break;
			case 'success':
				statusDiv.style.backgroundColor = '#e8f5e9';
				statusDiv.style.color = '#2e7d32';
				statusDiv.style.border = '1px solid #81c784';
				statusDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + message;
				break;
			case 'error':
				statusDiv.style.backgroundColor = '#ffebee';
				statusDiv.style.color = '#c62828';
				statusDiv.style.border = '1px solid #ef9a9a';
				statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + message;
				break;
		}
		
		statusDiv.style.display = 'block';
	}
	
	// [ Native Bridge ]
	
	// Bridge 호출
	
	// 외부 링크 이동 (callback 없음)
	function nbCall_outLink(url)
	{
		var urlInterface = {
            "action" : "moveOutLink",
            "moveURL" : url
        };
        sendNativeFunction(urlInterface);
	}
	
	// 속성 저장 (callback 없음)
	function nbCall_save(key,value)
	{
		var savePropertyInterface = {
            "action" : "saveProperty",
            "key" : key,
            "value" : value
        };
        sendNativeFunction(savePropertyInterface);
	}
	
	// 속성 가져오기
	function nbCall_get(key)
	{
		var getPropertyInterface = {
            "action" : "getProperty",
            "key" : key
        };
        sendNativeFunction(getPropertyInterface);
	}
	
	// 생체인증 실행
	function nbCall_bio() {
        var bioAuthInterface = {
            "action" : "bioAuth"
        };
        sendNativeFunction(bioAuthInterface);
    }
    
    // 암호화 실행
    function nbCall_enc(value) {
        var encryptInterface = {
            "action" : "encrypt",
            "text" : value
        };
        sendNativeFunction(encryptInterface);
    }
    
    function nbCall_keypad(title='',subtitle='')
    {
    	//mode : shuffle, normal
    	var keypadInterface = {
            "action" : "keypad",
            "isShow" : true,
            "mode" : "shuffle",
            "maxLength" : 6,
            "title" : title,
            "titleTextColor" : "#000000",
            "subTitle" : subtitle,
            "subTitleColor" : "#000000",
        };
        sendNativeFunction(keypadInterface);
    }
	
	// Native Callback 받기
    function nativeCallback(callbackResult) {
    	
    	const resultObject = JSON.parse(callbackResult);
    	
    	// 생체인증 체크하기
    	if ( resultObject['action'] === "bioAuth" ) bdg_bioAuth(resultObject);
    	
    	// 저장 값 가져오기
    	if ( resultObject['action'] === "getProperty" ) bdg_getProperty(resultObject);
    	
    	// 보안키패드 결과값 가져오기
    	if ( resultObject['action'] === "keypad" ) bdg_keypad(resultObject);
    	
    	// 암호화
    	if ( resultObject['action'] === "encrypt" ) bdg_encrypt(resultObject);
    	
    }
    
    // bioAuth
    function bdg_bioAuth(r)
    {
    	if ( r['result']['status'] == 'success' )
		{
			alert('생체인증성공');
		} else if ( r['result']['status'] == 'fail' )
		{
			alert('생체인증 실패');
		} else if ( r['result']['status'] == 'unavailable' )
		{
			alert('생체인증 사용불가능');
		} else 
		{
			alert('기타오류');
		}
    }
    
    // getProperty
    function bdg_getProperty(r)
    {
    	if ( r['result']['key'] == 'uid' )
    	{
    		if (sitenm == 'mmmain')
    		{
    			mmmain_chk_user_set(r['result']['value']);
    		} else 
    		{
    			alert( sitenm ); 
    			alert( r['result']['value'] );
    		}
    	}
    	
    	if ( r['result']['key'] == 'logintp' )
    	{
    		if (sitenm == 'msetting')
    		{
    			msetting_get_logintp(r['result']['value']);
    		} else 
    		{
    			alert( sitenm ); 
    			alert( r['result']['value'] );
    		}
    	}
    }
    
    // keypad
    function bdg_keypad(r)
    {
    	if (sitenm == 'msetting')
    	{
    		if ( r['result']['status'] == 'complete' )
        	{
        		msetting_keypad_result( r['result']['data'] );
        	} else if ( r['result']['status'] == 'close' )
        	{
        		return false;
        	} else 
        	{
        		return false;
        	}
    	} else 
    	{
    		if ( r['result']['status'] == 'complete' )
        	{
        		alert( r['result']['data'] );
        	} else if ( r['result']['status'] == 'close' )
        	{
        		alert('close');
        	} else 
        	{
        		alert('기타');
        	}
    	}
    }
    
    // bdg_encrypt
    function bdg_encrypt(r)
    {
    	alert( r['result']['encryptData'] );
    }
    
    // Native Bridge 실행을 위한 기본 설정
	
    function sendNativeFunction(jsonOBJ) {
        if (isIOSApp()) {
            window.webkit.messageHandlers.baseApp.postMessage(JSON.stringify(jsonOBJ));
        } else if(isAndroidApp()) {
            window.baseApp.run(JSON.stringify(jsonOBJ));
        }
    }
    
    function isIOSApp() {
		return /iOSApp/.test(navigator.userAgent) && !window.MSStream;
    }
    
    function isAndroidApp() {
		return /androidApp/.test(navigator.userAgent);
    }

	$("#search_mem_nm1").on("keyup",function(key){
			if(key.keyCode==13) {
				$("#btn_search_nm1").trigger("click");
			}
		});

	// 회원명 검색 버튼 클릭
	$('#btn_search_nm1').click(function(){

		var sname = $('#search_mem_nm1').val();
		ff_tsearch(sname);
	});

	
</script>
