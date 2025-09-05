<!-- BEGIN #header -->
<style>
	.select2-container--open {
		z-index: 9999 !important;
	}
	.photo-row {
		display: flex;
		align-items: flex-end;
		gap: 15px;
	}

	.photo-action {
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		height: 100px;
	}

	.photo-guide-text {
		font-size: 13px;
		color: #555;
		margin-bottom: 5px;
		line-height: 1.4;
	}
	.profile-photo-wrapper {
		position: relative;
		display: inline-block;
		width: 100px;
		height: 100px;
	}

	.preview_mem_photo {
		width: 100px;
		height: 100px;
		object-fit: cover;
		border-radius: 50%;
		border: 1px solid #ccc;
	}

	.capture-btn {
		bottom: 0;
		right: 0;
		padding: 3px 6px;
		font-size: 12px;
		border-radius: 12px;
		background-color: #007bff;
		color: white;
		border: none;
		cursor: pointer;
	}

	#menu_camera_wrap {
		position: relative;
		width: 100%;
		max-width: 500px;
	}

	#menu_camera_stream {
		width: 100%;
		border-radius: 8px;
	}

	.face-guide {
		position: absolute;
		top: 50%;
		left: 50%;
		width: 140px;
		height: 180px;
		transform: translate(-50%, -50%);
		border: 2px dashed rgba(0, 0, 0, 0.4);
		border-radius: 60% 60% 50% 50%;
		pointer-events: none;
		background-color: rgba(255, 255, 255, 0.1); /* 실루엣 느낌 */
	}

	.passport-guide {
		position: absolute;
		top: 47%;
		left: 50%;
		width: 120px;
		height: 150px;
		transform: translate(-50%, -60%); /* 살짝 위로 올려줌 */
		border: 2px dashed rgba(0, 0, 0, 0.4);
		border-radius: 8px;
		pointer-events: none;
		background-color: rgba(255, 255, 255, 0.05);
		box-shadow: 0 0 8px rgba(0,0,0,0.1);
	}
</style>
<!-- <script src="/dist/plugins/jquery/dist/jquery.min.js"></script> -->
<div id="header" class="app-header">
  <!-- BEGIN navbar-header -->
  <div class="navbar-header">
    <a href="index.html" class="navbar-brand" style="display:flex; aligh-itmes:flex-end;">
		<!-- <img src="/dist/img/logo_s.png" style="height:auto; display:block; vertical-align: bottom; position: relative; top: -3px;"/> -->
       <b class="me-1" style="margin-left: 6px">ARGOS SpoQ</b> 
	   <!-- <medium style="font-weight:bold">관리점 [<?php echo $_SESSION["comp_nm"] ?> : <?php echo $_SESSION["bcoff_nm"] ?>]</medium> -->
    </a>
    <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
  </div>
  <!-- END navbar-header -->
  <!-- BEGIN header-nav -->
  <div class="navbar-nav">
    <div class="navbar-item navbar-form">
        <div class="form-group">
          <input type="text" class="form-control" id="top_search" name="top_search" autocomplete="off" placeholder="회원명 또는 연락처 검색" />
          <button type="button" class="btn btn-search"  onclick="ff_tsearch();"><i class="ion-ios-search"></i></button>
        </div>
    </div>
    <a  href="#modal_mem_insert_form" data-bs-toggle="modal" class="btn btn-primary rounded-pill btn-sm">회원등록 <i class="ion-android-person-add"></i></a>
    <div class="navbar-item navbar-user dropdown">
      <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" 
        data-bs-toggle="dropdown">
        <img class="preview_mem_photo"
				id="user_photo"
				src="<?php echo $_SESSION['mem_info']['MEM_THUMB_IMG'] ?>"
				data-default-thumb="<?php echo $_SESSION['mem_info']['MEM_THUMB_IMG'] ?>"
				data-default-main="<?php echo $_SESSION['mem_info']['MEM_MAIN_IMG'] ?>"
				alt="회원사진"
				style="border-radius: 50%; cursor: pointer;"
				onclick="showFullPhoto('<?php echo $_SESSION['mem_info']['MEM_MAIN_IMG'] ?>')"
				onerror="this.onerror=null; this.src='/dist/img/default_profile.png';">
        <span>
          <span class="d-none d-md-inline"><?php echo $_SESSION['mem_info']['MEM_NM'] ?></span>
          <b class="caret"></b>
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-end me-1">
		<?php
		$employees = $_SESSION['employee_list'];
		$currentMemId = $_SESSION['mem_info']['MEM_ID'] ?? '';
		$count = count($employees);

		foreach ($employees as $r => $v): 
			$isCurrent = ($v['MEM_ID'] === $currentMemId);
		?>
			<?php if (!$isCurrent): ?>
				<a href="javascript:void(0);" class="dropdown-item" onclick="openPasswordModal('<?php echo $v['MEM_ID']; ?>')">
					<?php echo $v["MEM_NM"]; ?>
				</a>
			<?php else: ?>
				<a href="javascript:void(0);" class="dropdown-item disabled text-muted" style="pointer-events: none;">
					<?php echo $v["MEM_NM"]; ?> (현재 사용자)
				</a>
			<?php endif; ?>

			<?php if ($count > 1 && $r === 0): ?>
				<div class="dropdown-divider"></div>
			<?php endif; ?>
		<?php endforeach; ?>

		<div class="dropdown-divider"></div>
		<a href="/tlogin/logout" class="dropdown-item">Log Out</a>
	</div>

    </div>
  </div>
  <!-- END header-nav -->
</div>

	<!-- Brand Logo -->
	<!-- <a href="index3.html" class="brand-link"> -->
		<!-- <img src="/dist/img/ColorAdminLogo.png" alt="ColorAdmin Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
		<!-- <span class="brand-text font-weight-light">ARGOS SpoQ [Admin]</span> -->
	<!-- </a> -->
  <div id="sidebar" class="app-sidebar">
  <!-- BEGIN scrollbar -->
  <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
    <!-- BEGIN menu -->
    <div class="menu">
		<div class="menu-profile">
			<a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile" data-target="#appSidebarProfileMenu">
				<div class="menu-profile-cover with-shadow"></div>
				<div class="menu-profile-image">
					<!-- 사진 썸네일 -->
					<img class="preview_mem_photo"
								id="mgr_photo"
								src="<?php echo $_SESSION['mem_info']['MEM_THUMB_IMG'] ?>"
								data-default-thumb="<?php echo $_SESSION['mem_info']['MEM_THUMB_IMG'] ?>"
								data-default-main="<?php echo $_SESSION['mem_info']['MEM_MAIN_IMG'] ?>"
								alt="회원사진"
								style="border-radius: 50%; cursor: pointer;"
								onclick="showFullPhoto('<?php echo $_SESSION['mem_info']['MEM_MAIN_IMG'] ?>')"
								onerror="this.onerror=null; this.src='/dist/img/default_profile.png';">
				</div>
				<div class="menu-profile-info">
					<div class="d-flex align-items-center">
						<div class="flex-grow-1">
							<?php echo $_SESSION['mem_info']['MEM_NM'] ?>
						</div>
						<div class="menu-caret ms-auto"></div>
					</div>
					<small><?php echo $_SESSION["TCHR_POSN_NM"] ?></small>
					<medium>[<?php echo $_SESSION["bcoff_nm"] ?>]</medium>
				</div>
			</a>
		</div>
		<div id="appSidebarProfileMenu" class="collapse">
			<div class="menu-item pt-5px">
				<a href="javascript:;" class="menu-link"  onclick="menu_mem_info_modify('<?php echo $_SESSION['mem_info']['MEM_ID']?>');">
					<div class="menu-icon"><i class="fa fa-cog"></i></div>
					<div class="menu-text">내정보</div>
				</a>
			</div>
			<!-- <div class="menu-item">
				<a href="javascript:;" class="menu-link">
					<div class="menu-icon"><i class="fa fa-pencil-alt"></i></div>
					<div class="menu-text">피드백 보내기기</div>
				</a>
			</div> -->
			<div class="menu-item">
				<a href="/tlogin/logout" class="menu-link">
					<div class="menu-icon"><i class="fa fa-pencil-alt"></i></div>
					<div class="menu-text">로그아웃</div>
				</a>
			</div>
			<!-- <div class="menu-item pb-5px">
				<a href="javascript:;" class="menu-link">
					<div class="menu-icon"><i class="fa fa-question-circle"></i></div>
					<div class="menu-text">도움말</div>
				</a>
			</div> -->
			<div class="menu-divider m-0"></div>
		</div>
		<div class="menu-header">지점관리 메뉴</div>
		<div id="sideMenu" class="menu">
		<?php 
			$lastLevel = 0;
			$parCdMenu = "";
			$isClose = false;
			$parIdx = 0;
			$chIdx = 0;
			$active = '';
			$active2 = '';
			foreach ($_SESSION['menu_list'] as $menu) { 
				if ($menu['menu_level'] >= 1) {
					// 부모 메뉴가 변경되었을 때 닫기 처리
					if ($parCdMenu !== "" && $parCdMenu !== $menu['par_cd_menu']) {
						echo '</div></div>'; // 기존 서브메뉴 닫기
						if (($lastLevel - $menu['menu_level']) > 1) {
							for ($i = 1; $i <= ($lastLevel - $menu['menu_level']); $i++) {
								echo '</div></div>';
							}
						}
						$isClose = false;
					}

					// `child_cnt` 값 확인 후 숫자로 변환
					$childCount = isset($menu['child_cnt']) ? (int) trim($menu['child_cnt']) : 0;

					// 1️⃣ **자식 메뉴가 있는 경우거나 부모인 경우**
					if ($childCount > 0 || $menu['menu_level'] == 1) {
						$parIdx ++;
						$chIdx = 0;
						$parCdMenu = $menu['cd_menu'];
						if($parIdx == $menu1)
						{
							$active = ' active';
						} else
						{
							$active = '';
						}
						echo '<div class="menu-item has-sub' . $active . '">';
						if(isset($menu['url_path']) && $menu['url_path'] !="")
						{
							echo '    <a href="' . htmlspecialchars($menu['url_path']) . '?m1=' . $parIdx . '&m2=' . $chIdx . '" class="menu-link" data-id="' . htmlspecialchars($menu['cd_menu']) . '">';
						} else
						{
							echo '    <a href="javascript:;" class="menu-link" data-id="' . htmlspecialchars($menu['cd_menu']) .  '">';
						}
						
						echo '        <div class="menu-icon"><i class="' . $menu['icon'] . ' ' . $menu['color'] . '"></i></div>';
						echo '        <div class="menu-text">' . htmlspecialchars($menu['nm_menu']) . '</div>';
						if($childCount > 0)
						{
							echo '        <div class="menu-caret"></div>';
						}
						echo '    </a>';
						if($childCount > 0)
						{
							echo '    <div class="menu-submenu">';
						}
						$isClose = true;
					} 
					// 2️⃣ **자식 메뉴인 경우 (자식 메뉴가 없는것중 부모메뉴가 아닌것)
					else {
						$chIdx ++;
						if($parIdx == $menu1 && $chIdx == $menu2)
						{
							$active2 = ' active';
						} else
						{
							$active2 = '';
						}
						echo '<div class="menu-item' . $active2 .'">';
						echo '    <a href="' . htmlspecialchars($menu['url_path']) . '?m1=' . $parIdx . '&m2=' . $chIdx . '" class="menu-link" data-id="' . htmlspecialchars($menu['cd_menu']) . '">';
						if ($menu['menu_level'] === 2) {
							echo '        <div class="menu-icon"><i class="fa fa-book"></i></div>';
						}
						echo '        <div class="menu-text">' . htmlspecialchars($menu['nm_menu']) . '</div>';
						echo '    </a>';
						echo '</div>';
					}

					$lastLevel = $menu['menu_level'];
				}
			}

			// 마지막 레벨 닫기
			if ($isClose) {
				echo '</div></div>';
			}
			?>
		</div>
		<div class="menu-item d-flex">
			<a href="javascript:;" class="app-sidebar-minify-btn ms-auto d-flex align-items-center text-decoration-none" data-toggle="app-sidebar-minify">
				<ion-icon name="arrow-back" class="me-1 md flip-rtl hydrated" role="img"></ion-icon> 
				<div class="menu-text"><i class="fas fa-angle-double-left"></i></div></a>
		</div>
		<!-- END minify-button -->
	</div>
    <!-- END menu -->
  </div>
</div>
<!-- END #sidebar -->
<div class="modal fade" id="menu_modal_mem_modify">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">내정보 수정</h4>
                <button type="button" class="close3"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
				<div class="panel-body">
					<form id="menu_form_mem_modify">
						<div class="form-group">
							
							<div class="photo-row">
								<!-- 사진 썸네일 -->
								<img class="preview_mem_photo"
									id="menu_preview_mem_photo"
									src="<?php echo $_SESSION['mem_info']['MEM_THUMB_IMG'] ?>"
									data-default-thumb="<?php echo $_SESSION['mem_info']['MEM_THUMB_IMG'] ?>"
									data-default-main="<?php echo $_SESSION['mem_info']['MEM_MAIN_IMG'] ?>"
									alt="회원사진"
									style="border-radius: 50%; cursor: pointer;"
									onclick="showFullPhoto('<?php echo $_SESSION['mem_info']['MEM_MAIN_IMG'] ?>')"
									onerror="this.onerror=null; this.src='/dist/img/default_profile.png';">
								<!-- 오른쪽 텍스트 + 버튼 -->
								<div class="photo-action">
									<!-- 안내 문구 -->
									<div class="photo-guide-text">
										정면을 바라보며,<br>
										상반신이 잘 보이도록 촬영해주세요.
									</div>
									<!-- 버튼: 사진과 같은 행에, 하단 정렬 -->
									<button type="button" class="capture-btn" onclick="menu_openCamera()">
									<i class="fas fa-camera"></i></button>
								</div>
							</div>

							<!-- 웹캠 영상 영역 -->
							<div id="menu_camera_wrap" style="margin-top: 10px; display: none; text-align: center;">
								<!-- 웹캠 영상 -->
								<video id="menu_camera_stream" autoplay playsinline></video>

								<!-- 얼굴 가이드 -->
								<div class="passport-guide"></div>

								<!-- 촬영 버튼 -->
								<button type="button" class="btn btn-success btn-sm mt-2" onclick="menu_capturePhoto()">촬영</button>
							</div>

							<input type="hidden" id="menu_captured_photo" name="menu_captured_photo" />
							<input type="hidden" id="menu_mem_sno" name="menu_mem_sno" value="<?php echo $_SESSION['mem_info']['MEM_SNO']?>" />
						</div>
						
						<div class="form-group mt20">
							<label for="inputName" class="mb5">아이디</label>
							<input type="text" id="menu_mem_id" name="menu_mem_id" class="form-control" value="<?php echo $_SESSION['mem_info']['MEM_ID']?>" readonly>
						</div>
						
						<div class="form-group mt10">
							<label for="inputName" class="mb5">비밀번호</label>
							<input type="text" id="menu_mem_pwd" name="menu_mem_pwd" class="form-control" placeholder="비밀번호 변경시에만 입력해주세요.">
						</div>
						
						<div class="form-group mt10">
							<label for="inputName" class="mb5">이름<span class="text-danger">*</span></label>
							<input type="text" id="menu_mem_nm" name="menu_mem_nm" class="form-control" value="<?php echo $_SESSION['mem_info']['MEM_NM']?>">
						</div>
						<div class="form-group mt10">
							<label for="inputName" class="mb5">생년월일</label>
							<input type="text" id="menu_bthday" name="menu_bthday" class="form-control" value="<?php echo $_SESSION['mem_info']['BTHDAY']?>" data-inputmask="'mask': ['9999/99/99']" data-mask >
						</div>
						
						<div class="form-group mt10">
							<label for="inputName">성별</label>
							<div class="icheck-primary d-inline ml20">
								<input type="radio" id="menu_radioGrpCate1" name="menu_mem_gendr" value="M" <?php if($_SESSION['mem_info']['MEM_GENDR'] == "M") {?> checked <?php }?> >
								<label for="menu_radioGrpCate1">
									<small>남</small>
								</label>
							</div>
							<div class="icheck-primary d-inline ml20">
								<input type="radio" id="menu_radioGrpCate2" name="menu_mem_gendr" value="F" <?php if($_SESSION['mem_info']['MEM_GENDR'] == "F") {?> checked <?php }?> >
								<label for="menu_radioGrpCate2">
									<small>여</small>
								</label>
							</div>
						</div>
						<div class="form-group mt10">
							<label for="inputName" class="mb5">전화번호<span class="text-danger">*</span></label>
							<input type="text" id="menu_mem_telno" name="menu_mem_telno" class="form-control phone-input" value="<?php echo disp_phone($_SESSION['mem_info']['MEM_TELNO'])?>" >
						</div>
										
						<div class="form-group mt10">
							<label for="inputName" class="mb5">회원 주소</label>
							<input type="text" id="menu_mem_addr" name="menu_mem_addr" class="form-control" value="<?php echo $_SESSION['mem_info']['MEM_ADDR']?>">
						</div>
					</form>
				</div>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
            	<button type="button" class='btn btn-info btn-sm' onclick="menu_mem_modify();">내정보 수정하기</button>
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
<!-- /.sidebar -->
<!-- 비밀번호 입력 모달 -->
<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="passwordModalLabel">비밀번호 입력</h5>
        <button type="button" class="btn-close" onclick="closePasswordModal()" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="password" id="modalPassword" class="form-control" placeholder="비밀번호를 입력하세요" autocomplete="new-password" onkeydown="handleEnter(event)">
        <input type="hidden" id="modalMemId">
      </div>
      <div class="modal-footer justify-content-center">
		<button type="button" class="btn btn-primary" onclick="submitPassword()">확인</button>
		<button type="button" class="btn btn-secondary me-2" onclick="closePasswordModal()">취소</button>
	  </div>
    </div>
  </div>
</div>
<script>
	
	$(document).ready(function () {
		$('.menu-link').on('click', function (e) {

			var $clickedElement = $(this); // 클릭된 요소

			// (1) 바로 부모를 제외한 상위에서 has-sub 클래스를 가진 가장 가까운 요소 찾기
			var $parentHasSub = $clickedElement.parent().parent().closest('.has-sub');

			if ($parentHasSub.length && !$parentHasSub.hasClass('active')) {
				$('.has-sub').not($parentHasSub).removeClass('active'); // 다른 has-sub의 active 제거
				$parentHasSub.addClass('active'); // 상위 has-sub에 active 추가
			}

			// (2) 바로 부모가 has-sub을 가지고 있지 않은 경우
			var $directParent = $clickedElement.parent(); // 바로 부모 div
			if (!$directParent.hasClass('has-sub')) {
				// has-sub을 가지지 않은 다른 div들의 active 제거
				$('div:not(.has-sub)').removeClass('active');
				// 현재 클릭한 메뉴의 바로 부모가 active가 없으면 추가
				if (!$directParent.hasClass('active')) {
					$directParent.addClass('active');
				}
			}
		});

		$(".phone-input").on("input", function(e) {
			let errorDiv = $(this).closest(".input-group").next(".error-message") || $("#telno-error");
			handlePhoneInput(this, e, errorDiv);
		}).on("focus", function() {
			if (!$(this).val()) {
				$(this).val(""); // 빈 값으로 시작
			}
		});

		$(".phone-input").on("keypress", function(e) {
			const key = String.fromCharCode(e.which);
			if (!/[0-9]/.test(key)) {
				e.preventDefault();
			}
		});
	});

	let passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));

	function openPasswordModal(mem_id) {
		document.getElementById('modalMemId').value = mem_id;
		document.getElementById('modalPassword').value = '';
		passwordModal.show();

		setTimeout(() => {
			document.getElementById('modalPassword').focus();
		}, 500);
	}

	function closePasswordModal() {
		passwordModal.hide();
	}

	function handleEnter(e) {
		if (e.key === 'Enter') {
			e.preventDefault();
			submitPassword();
		}
	}

	function submitPassword() {
		const mem_id = document.getElementById('modalMemId').value;
		const password = document.getElementById('modalPassword').value.trim();

		if (password === "") {
			alert("비밀번호를 입력하세요.");
			return;
		}

		closePasswordModal();
		selectEmployee(mem_id, password);
	}

	
	function selectEmployee(mem_id, password){
		var params = {mem_id : mem_id, password: password };
		jQuery.ajax({
			url: '/tlogin/changeTLogin',
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
					location.reload();
				}  else
				{
					alert('잘못된 비밀번호입니다.');
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


	// 회원정보 수정하기
	function menu_mem_info_modify(mem_id) {
		// 썸네일 이미지 초기화
		const img = document.getElementById('menu_preview_mem_photo');
		const thumb = img.getAttribute('data-default-thumb');
		const main = img.getAttribute('data-default-main');

		img.src = thumb;
		img.setAttribute('onclick', `showFullPhoto('${main}')`);

		// hidden 필드 초기화
		document.getElementById('menu_captured_photo').value = "";

		// 모달 열기
		$('#menu_modal_mem_modify').modal("show");
	}

	let menu_stream = null;

	function menu_openCamera() {
		navigator.mediaDevices.getUserMedia({ video: true })
			.then(function(mediaStream) {
				menu_stream = mediaStream;
				const video = document.getElementById('menu_camera_stream');
				video.srcObject = menu_stream;
				document.getElementById('menu_camera_wrap').style.display = 'block';
			})
			.catch(function(err) {
				alert("카메라 접근 권한이 필요합니다.");
			});
	}

	function menu_capturePhoto() {
		const video = document.getElementById('menu_camera_stream');
		const canvas = document.createElement('canvas');
		canvas.width = video.videoWidth;
		canvas.height = video.videoHeight;

		const ctx = canvas.getContext('2d');
		ctx.drawImage(video, 0, 0);

		// const dataUrl = canvas.toDataURL('image/png');
		// 📌 JPEG로 base64 생성 (품질 0.9)
		const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
		

		// 썸네일 이미지 변경
		const preview = document.getElementById('menu_preview_mem_photo');
		preview.src = dataUrl;

		// base64 저장
		document.getElementById('menu_captured_photo').value = dataUrl;

		// 동적으로 onclick의 원본 이미지도 base64로 변경
		preview.setAttribute('onclick', `showFullPhoto("${dataUrl}")`);

		// 웹캠 종료
		if (menu_stream) {
			menu_stream.getTracks().forEach(track => track.stop());
		}
		document.getElementById('menu_camera_wrap').style.display = 'none';
	}

	// 회원정보 수정하기 처리
	function menu_mem_modify()
	{
		if($('#menu_mem_nm').val() == "" )
		{
			$('#menu_mem_nm').focus();
			alertToast('error','지점관리자명을 입력하세요');
			return;
		}
		
		if( $('#menu_mem_telno').val() == "" )
		{
			$('#menu_mem_telno').focus();
			alertToast('error','지점관리자 전화번호를 입력하세요');
			return;
		} else if(!checkPhoneNumber($('#menu_mem_telno').val()))
		{
			$('#menu_mem_telno').focus();
			alertToast('error','올바른 지점관리자 전화번호를 입력하세요');
			return;
		}
		
		var params = $("#menu_form_mem_modify").serialize();
		jQuery.ajax({
			url: '/ttotalmain/ajax_mgr_modify_proc',
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
					location.reload();
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

	function menu_stopCamera() {
		if (stream) {
			stream.getTracks().forEach(track => track.stop());
			stream = null;
		}
		document.getElementById('menu_camera_wrap').style.display = 'none';
	}

	document.addEventListener('DOMContentLoaded', function () {
		const modal = document.getElementById('menu_modal_mem_modify');
		if (modal) {
			modal.addEventListener('hidden.bs.modal', function () {
				menu_stopCamera();
			});
		}
	});
</script>
