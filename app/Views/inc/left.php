<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="index3.html" class="brand-link">
		<!-- <img src="/dist/img/ColorAdminLogo.png" alt="ColorAdmin Logo" class="brand-image img-circle elevation-3" style="opacity: .8">-->
		<span class="brand-text font-weight-light">ARGOS SpoQ</span>
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
				<a href="#" class="d-block">님 환영합니다.</a>
			</div>
			
		</div>
		
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="info">
				<a href="/login/logout" class="d-block">로그아웃</a>
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
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
			<!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
			
			
			<!-- 일반 메뉴 [ Start ] -->
			<li class="nav-item menu-open">
				<a href="#" class="nav-link active">
					<i class="nav-icon fas fa-tachometer-alt"></i>
					<p>
						디비관리
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<a href="/dbmanage/mDblist" class="nav-link active">
							<i class="far fa-circle nav-icon"></i>
							<p>디비 리스트</p>
						</a>
					</li>
					
					<li class="nav-item">
						<a href="/dbmanage/mDblistSta1" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>디비통계(대행사별)</p>
						</a>
					</li>
					
					<li class="nav-item">
						<a href="/dbmanage/mDblistSta2" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>디비통계(이벤트별)</p>
						</a>
					</li>
					
					<li class="nav-item">
						<a href="/dbmanage/mDblistSta3" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>예약통계(대행사별)</p>
						</a>
					</li>
					
					<li class="nav-item">
						<a href="/dbmanage/mDblistSta4" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>예약통계(이벤트별)</p>
						</a>
					</li>
					
					<!-- =================================================================
								2023-04-24 추가 [시작] 
					 ================================================================= -->
					
					<li class="nav-item">
						<a href="/dbmanage/mDblistSta5" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>내원예약통계(대행사별)</p>
						</a>
					</li>
					
					<li class="nav-item">
						<a href="/dbmanage/mDblistSta6" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>내원예약통계(이벤트별)</p>
						</a>
					</li>
					
					<li class="nav-item">
						<a href="/dbmanage/mDblistSta7" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>내원완료통계(대행사별)</p>
						</a>
					</li>
					
					<li class="nav-item">
						<a href="/dbmanage/mDblistSta8" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>내원완료통계(이벤트별)</p>
						</a>
					</li>
					
					
					<li class="nav-item">
						<a href="/dbmanage/mDblistUpload" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>디비일괄입력</p>
						</a>
					</li>
					
					<li class="nav-item">
						<a href="/dbmanage/mDbTmList" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>디비수동배정</p>
						</a>
					</li>
					
					<li class="nav-item">
						<a href="/dbmanage/mDbTmListD" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>영구디비배정</p>
						</a>
					</li>
					
					<li class="nav-item">
						<a href="/dbmanage/mDbTmListB" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>지난부재디비관리</p>
						</a>
					</li>
					
				</ul>
			</li>
			<!-- 일반 메뉴 [ End ] -->
			
			
			<!-- 관리자 메뉴 [ Start ] -->
			<li class="nav-item menu-close">
				<a href="#" class="nav-link">
					<i class="nav-icon fas fa-tachometer-alt"></i>
					<p>
						관리메뉴
						<i class="right fas fa-angle-left"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<a href="/manage/mStatus" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>코드관리</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="/manage/mCompanyGroup" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>업체그룹 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="/manage/mCompany" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>업체&이벤트 관리</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="/dbmanage/mDblistMoveEvent" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>이벤트 디비이관</p>
						</a>
					</li>
					
					<li class="nav-item">
						<a href="/manage/mSmsManager" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>문자관리</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="/manage/mTmManager" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>TM관리</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="/manage/mAutoManager" class="nav-link">
							<i class="far fa-circle nav-icon"></i>
							<p>자동배정관리</p>
						</a>
					</li>
				</ul>
			</li>
			<!-- 관리자 메뉴 [ End ] -->
          
			<!-- <li class="nav-header">EXAMPLES</li> -->
		</ul>
		
		<ul>
		</ul>
		
		<form name="left_dblistSearchForm" id="left_dblistSearchForm" method="post" action="/dbmanage/mDblist">
			<input type='hidden' name='didx' id='didx' />
			<input type='hidden' name='call_idx' id='call_idx' />
		</form>
		
	</nav>
	<!-- /.sidebar-menu -->
	</div>
<!-- /.sidebar -->
</aside>

<script>
	
	
</script>


