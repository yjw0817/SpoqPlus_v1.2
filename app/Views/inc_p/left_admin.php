<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="index3.html" class="brand-link">
		<!-- <img src="/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">-->
		<span class="brand-text font-weight-light">ARGOS SpoQ [Admin]</span>
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
			<div class="info">
				<a href="/adminmain/dashboard" class="d-block">Admin 님 환영합니다.</a>
			</div>
			
		</div>
		
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="info">
				<a href="/adminlogin/logout" class="d-block">로그아웃</a>
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
			<!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
			
			<!-- 시스템 설정 [ Start ] --
			<?php ($left['menu1']) == 1 ? $menu_oc = "menu-open" : $menu_oc = "menu-close"; ?>
			<li class="nav-item <?php echo $menu_oc?>">
				<?php ($left['menu1']) == 1 ? $menu_av1 = "active" : $menu_av1 = ""; ?>
				<a href="#" class="nav-link <?php echo $menu_av1?>">
					<i class="nav-icon fas fa-tachometer-alt"></i>
					<p>
						시스템 설정
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<?php ($left['menu2']) == 1 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="#" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>코드그룹관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 2 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="#" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>코드관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 3 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="#" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>메시지 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 4 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="#" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>Redis 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 5 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="#" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>연계설정</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 6 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="#" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>로그관리</p>
						</a>
					</li>
				</ul>
			</li>
			<!-- 시스템 설정 [ End ] -->
			
			
			<!-- 사이트 설정 [ Start ] -->
			<?php ($left['menu1']) == 2 ? $menu_oc = "menu-open" : $menu_oc = "menu-close"; ?>
			<li class="nav-item <?php echo $menu_oc?>">
				<?php ($left['menu1']) == 2 ? $menu_av1 = "active" : $menu_av1 = ""; ?>
				<a href="#" class="nav-link <?php echo $menu_av1?>">
					<i class="nav-icon fas fa-tachometer-alt"></i>
					<p>
						사이트 설정
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<?php ($left['menu2']) == 1 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/adminmain/one_cate_list" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>대분류 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 2 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/adminmain/sadmin_list" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>슈퍼관리자 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 3 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/adminmain/bc_appct_manage" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>지점신청 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 4 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/adminmain/terms_manage" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>약관 관리</p>
						</a>
					</li>
					<!-- 
					<li class="nav-item">
						<?php ($left['menu2']) == 5 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/adminmain/survey_manage" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>설문조사 관리</p>
						</a>
					</li>
					 -->
					 <li class="nav-item">
						<?php ($left['menu2']) == 6 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/adminmain/all_mem_manage" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>회원 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 7 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/adminmain/set_menu" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>메뉴(프로그램)등록관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 8 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/adminmain/set_menu_comp" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>회사별 메뉴설정</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 9 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/adminmain/set_menu_bcoff" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>지점별 메뉴설정</p>
						</a>
					</li>
				</ul>
			</li>
			<!-- 사이트 설정 [ End ] -->
			
			<!-- 내역 관리 [ Start ] --
			<?php ($left['menu1']) == 3 ? $menu_oc = "menu-open" : $menu_oc = "menu-close"; ?>
			<li class="nav-item <?php echo $menu_oc?>">
				<?php ($left['menu1']) == 3 ? $menu_av1 = "active" : $menu_av1 = ""; ?>
				<a href="#" class="nav-link <?php echo $menu_av1?>">
					<i class="nav-icon fas fa-tachometer-alt"></i>
					<p>
						내역 관리
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<?php ($left['menu2']) == 1 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="#" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>설문조사 내역</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 2 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="#" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>운동목적</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 3 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="#" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>매출 내역</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 4 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="#" class="nav-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>회원 내역</p>
						</a>
					</li>
				</ul>
			</li>
			<!-- 내역 관리 [ End ] -->
			
			
          
			<!-- <li class="nav-header">EXAMPLES</li> -->
		</ul>
		
	</nav>
	<!-- /.sidebar-menu -->
	</div>
<!-- /.sidebar -->
</aside>

<script>
	
	
</script>


