<style>
    .nav-sidebar > .nav-item {
        font-size: 1.0rem !important;
    }
    .swal2-container
    {
        top:47px !important;
    }
    
    .content-wrapper
    {
        background-color:#ffffff;
    }
    
     .mm-hh
    {
        margin-right: -45px !important;
    }
</style>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style='position:fixed;'>
	<!-- Brand Logo -->
	 <!--
	<a href="#" class="brand-link">
		<span class="brand-text font-weight-light">ARGOS SpoQ </span>
	</a>
	-->
	<!-- Brand Logo -->
	<a href="#" class="brand-link">
		<!-- <img src="/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">-->
		<span class="brand-text font-weight-light">
		<?php 
		if (isset($_SESSION['bcoff_nm'])) :
		echo $_SESSION['bcoff_nm'];
		else :
		echo "SpoQ PLUS";
		endif;
		?>
		</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
		    <!-- 
			<div class="image">
				<img src="/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
			</div>
			-->
			<div class="info" style='width:100%'>
				<a href="#" class=""><?php echo $_SESSION["user_name"] ?>님</a>
				&nbsp;&nbsp;&nbsp;<i class="fas fa-cog" style='color:white' onclick="location.href='/api/msetting';"></i>
				<div class='float-right'><button type="button" class="btn btn-warning btn-xs ac-btn" onclick="location.href='/login/logout';">로그아웃</button></div>
			</div>
			
		</div>
		
		<!-- SidebarSearch Form --
		<div class="form-inline">
			<div class="input-group" data-widget="sidebar-search">
				<input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" id='left_tsearch'>
				<div class="input-group-append">
					<button class="btn btn-sidebar" onclick="alert('pl');">
						<i class="fas fa-search fa-fw"></i>
					</button>
				</div>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
			<?php
				$lastLevel = 0;
				$parCdMenu = "";
				$openLevels = []; // 열린 레벨 추적
				$parIdx = 0;
				$chIdx = 0;

				// menu1, menu2 기본값 설정 (GET 파라미터에서 가져옴)
				$menu1 = isset($_GET['m1']) ? (int)$_GET['m1'] : 0;
				$menu2 = isset($_GET['m2']) ? (int)$_GET['m2'] : 0;

				foreach ($_SESSION['menu_list'] as $menu) {
					if ($menu['menu_level'] < 1) {
						continue; // 유효하지 않은 메뉴 레벨 건너뛰기
					}

					// 부모 메뉴 변경 시 열린 메뉴 닫기
					if ($parCdMenu !== "" && $parCdMenu !== $menu['par_cd_menu']) {
						while (count($openLevels) > $menu['menu_level'] - 1) {
							echo '</ul></li>';
							array_pop($openLevels);
						}
					}

					$childCount = isset($menu['child_cnt']) ? (int)trim($menu['child_cnt']) : 0;
					$active = ($parIdx == ($menu1 - 1) && $menu['menu_level'] == 1) ? ' active menu-is-opening menu-open' : '';

					// 1. 부모 메뉴 또는 자식 메뉴가 있는 경우
					if ($childCount > 0 || $menu['menu_level'] == 1) {
						$parIdx++;
						$chIdx = 0;
						$parCdMenu = $menu['cd_menu'];

						// 자식 메뉴가 있으면 menu-close 클래스 추가
						$menuClass = $childCount > 0 ? 'nav-item menu-close' : 'nav-item';
						echo '<li class="' . $menuClass . $active . '">';

						// URL 처리
						$url = !empty($menu['url_path']) ? htmlspecialchars($menu['url_path']) . '?m1=' . $parIdx . '&m2=' . $chIdx : '#';
						echo '<a href="' . $url . '" class="nav-link' . $active . '">';
						echo '<i class="nav-icon ' . htmlspecialchars($menu['color'] ?? '') . '"></i>';
						echo '<p>' . htmlspecialchars($menu['nm_menu']);
						if ($childCount > 0) {
							echo '<i class="right fas fa-chevron-up"></i>';
						}
						echo '</p></a>';

						// 자식 메뉴가 있으면 하위 메뉴 시작
						if ($childCount > 0) {
							echo '<ul class="nav nav-treeview">';
							$openLevels[] = $menu['menu_level'];
						}
					} else {
						// 2. 자식 메뉴
						$chIdx++;
						$active = ($parIdx == $menu1 && $chIdx == $menu2) ? ' active' : '';

						echo '<li class="nav-item' . $active . '">';
						echo '<a href="' . htmlspecialchars($menu['url_path']) . '?m1=' . $parIdx . '&m2=' . $chIdx . '" class="nav-link' . $active . '" data-id="' . htmlspecialchars($menu['cd_menu']) . '">';
						// 자식 메뉴는 고정된 fa-angle-right 아이콘 사용
						echo '<i class="fas fa-angle-right nav-icon"></i>';
						echo '<p>' . htmlspecialchars($menu['nm_menu']) . '</p>';
						echo '</a>';
						echo '</li>';
					}

					$lastLevel = $menu['menu_level'];
				}

				// 남은 열린 레벨 닫기
				while (count($openLevels) > 0) {
					echo '</ul></li>';
					array_pop($openLevels);
				}
				?>
			<!-- 보낸상품관리 [ End ] -->
			
			
			
			<!-- <li class="nav-header">EXAMPLES</li> -->
		</ul>
		<div style="margin-bottom:100px;"></div>
	</nav>
	<!-- /.sidebar-menu -->
	</div>
<!-- /.sidebar -->
</aside>

<script>
	
	
</script>


