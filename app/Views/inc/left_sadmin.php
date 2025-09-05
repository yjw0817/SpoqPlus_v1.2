<!-- BEGIN #header -->

<!-- <script src="/dist/plugins/jquery/dist/jquery.min.js"></script> -->
<div id="header" class="app-header">
  <!-- BEGIN navbar-header -->
  <div class="navbar-header">
    <a href="index.html" class="navbar-brand" style="display:flex; aligh-itmes:flex-end;">
		<!-- <img src="/dist/img/logo_s.png" style="height:auto; display:block; vertical-align: bottom; position: relative; top: -3px;"/> -->
       <b class="me-1" style="margin-left: 6px">ARGOS SpoQ</b>
    </a>
    <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
  </div>
  <!-- END navbar-header -->
  <!-- BEGIN header-nav -->
  <div class="navbar-nav" style='display:none' >
    <div class="navbar-item navbar-form">
      <form action="" method="POST" name="search">
        <div class="form-group">
          <input type="text" class="form-control" id="top_search" placeholder="Enter keyword" />
          <button type="submit" class="btn btn-search"><i class="ion-ios-search"></i></button>
        </div>
      </form>
    </div>
    ...
    <div class="navbar-item navbar-user dropdown">
      <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" 
        data-bs-toggle="dropdown">
        <img src="/dist/img/user/user-13.jpg" alt="" /> 
        <span>
          <span class="d-none d-md-inline">Adam Schwartz</span>
          <b class="caret"></b>
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-end me-1">
        ...
      </div>
    </div>
  </div>
  <!-- END header-nav -->
</div>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
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
				<!-- <div class="menu-profile-image">
					<img src="/dist/img/user/user-13.jpg" alt="">
				</div> -->
				<div class="menu-profile-info">
					<div class="d-flex align-items-center">
						<div class="flex-grow-1">
							<?php echo $_SESSION["user_name"] ?>
						</div>
						<div class="menu-caret ms-auto"></div>
					</div>
					<small><?php echo $_SESSION["comp_nm"] ?></small>
				</div>
			</a>
		</div>
		<div id="appSidebarProfileMenu" class="collapse">
			<!-- <div class="menu-item pt-5px">
				<a href="/api/msetting" class="menu-link">
					<div class="menu-icon"><i class="fa fa-cog"></i></div>
					<div class="menu-text">내정보</div>
				</a>
			</div> -->
			<!-- <div class="menu-item">
				<a href="javascript:;" class="menu-link">
					<div class="menu-icon"><i class="fa fa-pencil-alt"></i></div>
					<div class="menu-text">피드백 보내기기</div>
				</a>
			</div> -->
			<div class="menu-item">
				<a href="/slogin/logout" class="menu-link">
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
		<div class="menu-header">관리자 메뉴</div>
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
		
		<!-- END minify-button -->
	</div>
    <!-- END menu -->
  </div>
</div>
<!-- END #sidebar -->
	
<!-- /.sidebar -->
</aside>

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
	});

</script>

