<style>
    .nav-sidebar > .nav-item {
        font-size: 1.0rem !important;
    }
</style>
<!-- sidebar-light-info -->
<!-- sidebar-light-teal -->
<!-- sidebar-dark-primary -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-teal elevation-4">
	<!-- Brand Logo -->
	<a href="#" class="brand-link">
		<!-- <img src="/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">-->
		<span class="brand-text font-weight-light">ARGOS SpoQ [지점관리]</span>
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
				<a href="/tchrmain/dashboard" class=""><?php echo $_SESSION["user_name"] ?>님</a>
				&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-xs ac-btn" onclick="location.href='/tlogin/logout';">로그아웃</button>
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
			
			<!-- 통합정보 [ Start ] -->
			<?php ($left['menu1']) == 9 ? $menu_oc = "menu-open" : $menu_oc = "menu-close"; ?>
			<li class="nav-item <?php echo $menu_oc?>">
				<?php ($left['menu1']) == 9 ? $menu_av1 = "active" : $menu_av1 = ""; ?>
				<a href="#" class="nav-link menu-link <?php echo $menu_av1?>" >
					<i class="nav-icon fas fa-info"></i>
					<p>
						통합정보
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<?php ($left['menu2']) == 2 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tchrmain/dashboard" class="nav-link menu-link <?php echo $menu_av2?>" >  <!-- onclick="loadMainContent('/tchrmain/dashboard'); return false;" -->
							<i class="fas fa-angle-right nav-icon"></i>
							<p>통합정보</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 1 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/ttotalmain/info_mem" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>회원별 통합정보</p>
						</a>
					</li>
				</ul>
			</li>
			<!-- 통합정보 [ End ] -->
			
			
			<!-- 지점관리 [ Start ] -->
			<?php ($left['menu1']) == 1 ? $menu_oc = "menu-open" : $menu_oc = "menu-close"; ?>
			<li class="nav-item <?php echo $menu_oc?>">
				<?php ($left['menu1']) == 1 ? $menu_av1 = "active" : $menu_av1 = ""; ?>
				<a href="#" class="nav-link menu-link <?php echo $menu_av1?>">
					<i class="nav-icon fas fa-building"></i>
					<p>
						지점관리
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<!-- 
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<?php ($left['menu2']) == 1 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tbcoffmain/bocff_setting" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>지점 환경 설정</p>
						</a>
					</li>
				</ul>
				 -->
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<?php ($left['menu2']) == 2 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tbcoffmain/use_2rd_manage" class="nav-link menu-link <?php echo $menu_av2?>" >
							<i class="fas fa-angle-right nav-icon"></i>
							<p>이용권 사용 설정</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 3 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tbcoffmain/notice_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>회원 공지사항 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 6 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tbcoffmain/notice_manage2" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>강사 공지사항 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 7 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tbcoffmain/locker_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>락커 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 4 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tbcoffmain/locker_setting" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>락커 설정</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 5 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tbcoffmain/grp_schedule_room" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>그룹수업 스케쥴관리</p>
						</a>
					</li>
				</ul>
			</li>
			<!-- 지점관리 [ End ] -->
			
			
			<!-- 직원/회원 설정 [ Start ] -->
			<?php ($left['menu1']) == 2 ? $menu_oc = "menu-open" : $menu_oc = "menu-close"; ?>
			<li class="nav-item <?php echo $menu_oc?>">
				<?php ($left['menu1']) == 2 ? $menu_av1 = "active" : $menu_av1 = ""; ?>
				<a href="#" class="nav-link menu-link <?php echo $menu_av1?>">
					<i class="nav-icon fas fa-user"></i>
					<p>
						직원/회원 설정
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<?php ($left['menu2']) == 1 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tmemmain/tchr_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>강사관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 2 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tmemmain/mem_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>회원관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 3 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tmemmain/tchr_annu_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>강사 연차 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 4 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tmemmain/tchr_salary_setting_list" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>강사 수당 설정</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 5 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tmemmain/mem_introd_list" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>추천회원현황</p>
						</a>
					</li>
				</ul>
			</li>
			<!-- 직원/회원 설정 [ End ] -->
			
			<!-- 상품/구매 관리 [ Start ] -->
			<?php ($left['menu1']) == 3 ? $menu_oc = "menu-open" : $menu_oc = "menu-close"; ?>
			<li class="nav-item <?php echo $menu_oc?>">
				<?php ($left['menu1']) == 3 ? $menu_av1 = "active" : $menu_av1 = ""; ?>
				<a href="#" class="nav-link menu-link <?php echo $menu_av1?>">
					<i class="nav-icon fas fa-shopping-cart"></i>
					<p>
						상품/구매 관리
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<?php ($left['menu2']) == 2 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/teventmain/event_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>상품 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 3 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/teventmain/lockr_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>락커 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 1 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/teventmain/event_buy" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>상품 구매하기</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 4 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/teventmain/buy_event_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>구매 상품 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 5 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/teventmain/buy_eventhist_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>구매 내역 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 6 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/teventmain/misu_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>미수금 관리</p>
						</a>
					</li>
				</ul>
			</li>
			<!-- 상품/구매 관리 [ End ] -->
			
			<!-- 상품 보내기 관리 [ Start ] -->
			<?php ($left['menu1']) == 10 ? $menu_oc = "menu-open" : $menu_oc = "menu-close"; ?>
			<li class="nav-item <?php echo $menu_oc?>">
				<?php ($left['menu1']) == 10 ? $menu_av1 = "active" : $menu_av1 = ""; ?>
				<a href="#" class="nav-link menu-link <?php echo $menu_av1?>">
					<i class="nav-icon fas fa-shopping-bag"></i>
					<p>
						상품 보내기 관리
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<?php ($left['menu2']) == 1 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/teventmain/send_event_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>보내기 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 2 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/teventmain/buy_event_manage1" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>보내기(종료일)</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 3 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/teventmain/buy_event_manage3" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>보내기(수업)</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 4 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/teventmain/buy_event_manage2" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>보내기(구매안한상품)</p>
						</a>
					</li>
				</ul>
			</li>
			<!-- 상품 보내기 관리 [ End ] -->
			
			
			<!-- 매출 관리 [ Start ] -->
			<?php ($left['menu1']) == 4 ? $menu_oc = "menu-open" : $menu_oc = "menu-close"; ?>
			<li class="nav-item <?php echo $menu_oc?>">
				<?php ($left['menu1']) == 4 ? $menu_av1 = "active" : $menu_av1 = ""; ?>
				<a href="#" class="nav-link menu-link <?php echo $menu_av1?>">
					<i class="nav-icon fas fa-money-check-alt"></i>
					<p>
						매출 관리
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<?php ($left['menu2']) == 1 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tsalesmain/month_sales_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>전체 매출 현황</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 5 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tsalesmain/day_refund_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>환불 매출 현황</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 4 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tsalesmain/month_class_sell_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>수업매출 현황</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 3 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tsalesmain/month_class_sales_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>수업진행 현황</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 6 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tsalesmain/pt_sales_rank" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>PT매출 순위</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 7 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tsalesmain/gx_class_attd_list" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>GX 현황</p>
						</a>
					</li>
				</ul>
				
			</li>
			<!-- 매출 관리 [ End ] -->
			
			<!-- 회원/상품 현황관리 [ Start ] -->
			<?php ($left['menu1']) == 5 ? $menu_oc = "menu-open" : $menu_oc = "menu-close"; ?>
			<li class="nav-item <?php echo $menu_oc?>">
				<?php ($left['menu1']) == 5 ? $menu_av1 = "active" : $menu_av1 = ""; ?>
				<a href="#" class="nav-link menu-link <?php echo $menu_av1?>">
					<i class="nav-icon fas fa-clipboard"></i>
					<p>
						회원/상품 현황관리
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<?php ($left['menu2']) == 1 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/teventmem/mem_attd_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>회원 출석 현황</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 4 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/teventmem/trans_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>양도 현황</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 5 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/teventmem/domcy_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>휴회 현황</p>
						</a>
					</li>
				</ul>
			</li>
			<!-- 회원/상품 현황관리 [ End ] -->
			
			<!-- 상품변경 내역관리 [ Start ] -->
			<?php ($left['menu1']) == 6 ? $menu_oc = "menu-open" : $menu_oc = "menu-close"; ?>
			<li class="nav-item <?php echo $menu_oc?>">
				<?php ($left['menu1']) == 6 ? $menu_av1 = "active" : $menu_av1 = ""; ?>
				<a href="#" class="nav-link menu-link <?php echo $menu_av1?>">
					<i class="nav-icon fas fa-history"></i>
					<p>
						상품변경 내역관리
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<?php ($left['menu2']) == 1 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tsaleschange/event_jend_hist" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>강제종료 내역</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 2 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tsaleschange/tchr_change_hist" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>강사변경 내역</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 3 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tsaleschange/priod_change_hist" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>기간변경 내역</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 4 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tsaleschange/domcy_change_hist" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>휴회추가 내역</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 5 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tsaleschange/add_srvc_hist" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>수업추가 내역</p>
						</a>
					</li>
				</ul>
			</li>
			<!-- 상품변경 내역관리 [ End ] -->
			
			<!-- 회원출석 집계 [ Start ] --
			<?php ($left['menu1']) == 7 ? $menu_oc = "menu-open" : $menu_oc = "menu-close"; ?>
			<li class="nav-item <?php echo $menu_oc?>">
				<?php ($left['menu1']) == 7 ? $menu_av1 = "active" : $menu_av1 = ""; ?>
				<a href="#" class="nav-link menu-link <?php echo $menu_av1?>">
					<i class="nav-icon fas fa-chart-bar"></i>
					<p>
						회원출석 집계
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<?php ($left['menu2']) == 1 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tattdtotal/mem_attd_total" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>기간검색/회원출석 집계</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 2 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tattdtotal/mem_attd_h_total" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>시간대별</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 3 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tattdtotal/mem_attd_w_total" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>요일별</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 4 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tattdtotal/mem_attd_d_total" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="far fa-circle nav-icon"></i>
							<p>일별</p>
						</a>
					</li>
				</ul>
			</li>
			<!-- 회원출석 집계 [ End ] -->
			
			<!-- 강사 관리 [ Start ] -->
			<?php ($left['menu1']) == 8 ? $menu_oc = "menu-open" : $menu_oc = "menu-close"; ?>
			<li class="nav-item <?php echo $menu_oc?>">
				<?php ($left['menu1']) == 8 ? $menu_av1 = "active" : $menu_av1 = ""; ?>
				<a href="#" class="nav-link menu-link <?php echo $menu_av1?>">
					<i class="nav-icon fas fa-id-card"></i>
					<p>
						강사 관리
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<?php ($left['menu2']) == 1 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tmanage/annu_appt_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>연차신청/내역</p>
						</a>
					</li>
					<li class="nav-item">
						<?php ($left['menu2']) == 2 && ($menu_av1 == "active") ? $menu_av2 = "active" : $menu_av2 = ""; ?>
						<a href="/tmanage/tchr_salary_manage" class="nav-link menu-link <?php echo $menu_av2?>">
							<i class="fas fa-angle-right nav-icon"></i>
							<p>수당 집계표</p>
						</a>
					</li>
				</ul>
			</li>
			<!-- 강사 관리 [ End ] -->
			<!-- <li class="nav-header">EXAMPLES</li> -->
		</ul>
		<div style="margin-bottom:100px;"></div>
	</nav>
	<!-- /.sidebar-menu -->
	</div>
<!-- /.sidebar -->
</aside>

<script>
	// document.addEventListener('DOMContentLoaded', function () {
	// 	document.querySelectorAll('.menu-link').forEach(link => {
	// 		link.addEventListener('click', function (e) {
	// 			e.preventDefault();
	// 			const url = this.getAttribute('href');

	// 			fetch('/ajax/loadView', {
	// 				method: 'POST',
	// 				headers: { 
	// 					'Content-Type': 'application/json',
    //     				'X-Requested-With': 'XMLHttpRequest', // AJAX 요청 헤더 추가
	// 				 },
	// 				body: JSON.stringify({ url: url }),
	// 			})
	// 				.then(response => response.text())
	// 				.then(html => {
	// 					document.getElementById('content-area').innerHTML = html;
	// 				})
	// 				.catch(error => console.error('Error:', error));
	// 		});
	// 	});
	// });
	
</script>


