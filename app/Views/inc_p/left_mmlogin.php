<style>
    .nav-sidebar > .nav-item {
        font-size: 1.0rem !important;
    }
    .swal2-container
    {
        top:47px !important;
    }
    
    /* 닫기 버튼 스타일 - 그라데이션 + 애니메이션 */
    .close-app-btn {
        background: linear-gradient(45deg, #4c9aff, #0066cc);
        color: white !important;
        border: none;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(76, 154, 255, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-left: 65px;
        vertical-align: middle;
    }
    
    .close-app-btn span {
        color: white !important;
    }
    
    .close-app-btn:hover {
        background: linear-gradient(45deg, #2680eb, #0052cc);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 154, 255, 0.4);
    }
    
    .close-app-btn:active {
        transform: translateY(0);
        box-shadow: 0 1px 3px rgba(76, 154, 255, 0.3);
    }
    
    .close-app-btn i {
        font-size: 12px;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }
    
    /* 다크 모드 스타일 */
    .dark-mode .close-app-btn {
        background: linear-gradient(45deg, #4c9aff, #0066cc);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
    }
    
    .dark-mode .close-app-btn:hover {
        box-shadow: 0 4px 12px rgba(76, 154, 255, 0.6);
    }
    
    /* 사용자 정보 영역 스타일 조정 */
    .user-panel .info {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-lightblue elevation-4" style='position:fixed;'>
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
		<div class="user-panel mt-2 pb-2 d-flex" style='border-bottom: 1px solid #ddd;'>
		    <!-- 
			<div class="image">
				<img src="/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
			</div>
			-->
			<div class="info" style='width:100%'>
				<div style="display: flex; align-items: center;">
					<a href="#" class="" style="flex: 1;"><?php echo $_SESSION["user_name"] ?>님&nbsp;&nbsp; <i class="fas fa-cog" style='color:#4f4f4f' onclick="location.href='/api/msetting';"></i></a>
					
					<!-- 닫기 버튼 -->
					<button type="button" class="close-app-btn" onclick="closeApp();">
						<i class="fas fa-power-off"></i>
						<span>종료</span>
					</button>
				</div>
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
			</ul>
			<div style="margin-bottom:100px;"></div>
		</nav>
	<!-- /.sidebar-menu -->
	</div>
<!-- /.sidebar -->
</aside>

<script>
	// $(document).ready(function () {
	// 	$('.nav-link').on('click', function (e) {

	// 		var $clickedElement = $(this); // 클릭된 요소

	// 		// (1) 바로 부모를 제외한 상위에서 has-sub 클래스를 가진 가장 가까운 요소 찾기
	// 		var $parentHasSub = $clickedElement.parent().parent().closest('.menu-close');

	// 		if ($parentHasSub.length && !$parentHasSub.hasClass('menu-open')) {
	// 			$('.menu-close').not($parentHasSub).removeClass('menu-open'); // 다른 has-sub의 active 제거
	// 			$('.menu-close').not($parentHasSub).removeClass('menu-is-opening'); // 다른 has-sub의 active 제거
	// 			$parentHasSub.addClass('menu-is-opening'); // 상위 has-sub에 active 추가
	// 			$parentHasSub.addClass('menu-open'); // 상위 has-sub에 active 추가
	// 		}

	// 		// (2) 바로 부모가 has-sub을 가지고 있지 않은 경우
	// 		var $directParent = $clickedElement.parent(); // 바로 부모 div
	// 		clickedElement.addClass('active');
	// 		if (!$directParent.hasClass('menu-close')) {
	// 			// has-sub을 가지지 않은 다른 div들의 active 제거
	// 			$('li:not(.menu-close)').removeClass('active');
	// 			// 현재 클릭한 메뉴의 바로 부모가 active가 없으면 추가
	// 			if (!$directParent.hasClass('active')) {
	// 				$directParent.addClass('active');
	// 			}
	// 		}
	// 	});
	// });
	
	// 앱/브라우저 닫기 함수
	function closeApp() {
	    // 네이티브 앱인 경우
	    if (window.AndroidInterface && typeof window.AndroidInterface.closeApp === 'function') {
	        // Android 앱
	        window.AndroidInterface.closeApp();
	    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.closeApp) {
	        // iOS 앱
	        window.webkit.messageHandlers.closeApp.postMessage({});
	    } else {
	        // 웹뷰가 설정되지 않은 경우
	        // 로그아웃 처리
	        if (confirm('종료하시겠습니까?\n\n(앱 종료 기능은 앱 설정이 필요합니다)')) {
	            // 세션 삭제
	            $.ajax({
	                url: '/api/mobileLogout',
	                type: 'POST',
	                success: function() {
	                    // 로그인 페이지로 이동
	                    window.location.href = '/login';
	                }
	            });
	        }
	    }
	}
	
</script>


