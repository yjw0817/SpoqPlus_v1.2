<html lang="en"><head>
	<meta charset="utf-8">
	<title>Color Admin | Widgets Page</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
	<meta content="" name="description">
	<meta content="" name="author">
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="/dist/css/vendor.min.css" rel="stylesheet">
	<link href="/dist/css/apple/app.min.css" rel="stylesheet">
	<link href="/dist/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
	<!-- ================== END core-css ================== -->
	
	<!-- ================== BEGIN page-css ================== -->
	<link href="/dist/plugins/nvd3/build/nv.d3.css" rel="stylesheet">
	<!-- ================== END page-css ================== -->
<style id="monica-reading-highlight-style">
        .monica-reading-highlight {
          animation: fadeInOut 1.5s ease-in-out;
        }

        @keyframes fadeInOut {
          0%, 100% { background-color: transparent; }
          30%, 70% { background-color: rgba(2, 118, 255, 0.20); }
        }
      </style></head>
<body class="pace-done "><div class="pace pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div><div class="pace pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>
	<!-- BEGIN #loader -->
	<div id="loader" class="app-loader loaded">
		<span class="spinner"></span>
	</div>
	<!-- END #loader -->

	<!-- BEGIN #app -->
	<div id="app" class="app app-header-fixed app-sidebar-fixed has-scroll">
		<!-- BEGIN #header -->
		<div id="header" class="app-header">
			<!-- BEGIN navbar-header -->
			<div class="navbar-header">
				<a href="index.html" class="navbar-brand"><span class="navbar-logo"><i class="ion-ios-cloud"></i></span> <b class="me-1">Color</b> Admin</a>
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
					<form action="" method="POST" name="search">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Enter keyword">
							<button type="submit" class="btn btn-search"><i class="ion-ios-search"></i></button>
						</div>
					</form>
				</div>
				<div class="navbar-item dropdown">
					<a href="#" data-bs-toggle="dropdown" class="navbar-link dropdown-toggle icon">
						<i class="ion-ios-notifications"></i>
						<span class="badge">5</span>
					</a>
					<div class="dropdown-menu media-list dropdown-menu-end">
						<div class="dropdown-header">NOTIFICATIONS (5)</div>
						<a href="javascript:;" class="dropdown-item media">
							<div class="media-left">
								<i class="fa fa-bug media-object bg-gray-400"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading">Server Error Reports <i class="fa fa-exclamation-circle text-danger"></i></h6>
								<div class="text-muted fs-10px">3 minutes ago</div>
							</div>
						</a>
						<a href="javascript:;" class="dropdown-item media">
							<div class="media-left">
								<img src="/dist/img/user/user-1.jpg" class="media-object" alt="">
								<i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading">John Smith</h6>
								<p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
								<div class="text-muted fs-10px">25 minutes ago</div>
							</div>
						</a>
						<a href="javascript:;" class="dropdown-item media">
							<div class="media-left">
								<img src="/dist/img/user/user-2.jpg" class="media-object" alt="">
								<i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading">Olivia</h6>
								<p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
								<div class="text-muted fs-10px">35 minutes ago</div>
							</div>
						</a>
						<a href="javascript:;" class="dropdown-item media">
							<div class="media-left">
								<i class="fa fa-plus media-object bg-gray-400"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading"> New User Registered</h6>
								<div class="text-muted fs-10px">1 hour ago</div>
							</div>
						</a>
						<a href="javascript:;" class="dropdown-item media">
							<div class="media-left">
								<i class="fa fa-envelope media-object bg-gray-400"></i>
								<i class="fab fa-google text-warning media-object-icon fs-14px"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading"> New Email From John</h6>
								<div class="text-muted fs-10px">2 hour ago</div>
							</div>
						</a>
						<div class="dropdown-footer text-center">
							<a href="javascript:;" class="text-decoration-none">View more</a>
						</div>
					</div>
				</div>
				<div class="navbar-item navbar-user dropdown">
					<a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
						<img src="/dist/img/user/user-13.jpg" alt=""> 
						<span>
							<span class="d-none d-md-inline">Adam Schwartz</span>
							<b class="caret"></b>
						</span>
					</a>
					<div class="dropdown-menu dropdown-menu-end me-1" style="">
						<a href="javascript:;" class="dropdown-item">Edit Profile</a>
						<a href="javascript:;" class="dropdown-item"><span class="badge bg-danger float-end rounded-pill">2</span> Inbox</a>
						<a href="javascript:;" class="dropdown-item">Calendar</a>
						<a href="javascript:;" class="dropdown-item">Setting</a>
						<div class="dropdown-divider"></div>
						<a href="javascript:;" class="dropdown-item">Log Out</a>
					</div>
				</div>
			</div>
			<!-- END header-nav -->
		</div>
		<!-- END #header -->
	
		<!-- BEGIN #sidebar -->
		<div id="sidebar" class="app-sidebar">
			<!-- BEGIN scrollbar -->
			<div class="app-sidebar-content ps" data-scrollbar="true" data-height="100%" data-init="true" style="height: 100%;">
				<!-- BEGIN menu -->
				<div class="menu">
					<div class="menu-profile">
						<a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile" data-target="#appSidebarProfileMenu">
							<div class="menu-profile-cover with-shadow"></div>
							<div class="menu-profile-image">
								<img src="/dist/img/user/user-13.jpg" alt="">
							</div>
							<div class="menu-profile-info">
								<div class="d-flex align-items-center">
									<div class="flex-grow-1">
										Sean Ngu
									</div>
									<div class="menu-caret ms-auto"></div>
								</div>
								<small>Front end developer</small>
							</div>
						</a>
					</div>
					<div id="appSidebarProfileMenu" class="collapse">
						<div class="menu-item pt-5px">
							<a href="javascript:;" class="menu-link">
								<div class="menu-icon"><i class="fa fa-cog"></i></div>
								<div class="menu-text">Settings</div>
							</a>
						</div>
						<div class="menu-item">
							<a href="javascript:;" class="menu-link">
								<div class="menu-icon"><i class="fa fa-pencil-alt"></i></div>
								<div class="menu-text"> Send Feedback</div>
							</a>
						</div>
						<div class="menu-item pb-5px">
							<a href="javascript:;" class="menu-link">
								<div class="menu-icon"><i class="fa fa-question-circle"></i></div>
								<div class="menu-text"> Helps</div>
							</a>
						</div>
						<div class="menu-divider m-0"></div>
					</div>
					<div class="menu-header">Navigation</div>
					<div class="menu-item has-sub closed">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="ion-ios-pulse"></i>
							</div>
							<div class="menu-text">Dashboard</div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
							<div class="menu-item">
								<a href="index.html" class="menu-link"><div class="menu-text">Dashboard v1</div></a>
							</div>
							<div class="menu-item">
								<a href="index_v2.html" class="menu-link"><div class="menu-text">Dashboard v2</div></a>
							</div>
							<div class="menu-item">
								<a href="index_v3.html" class="menu-link"><div class="menu-text">Dashboard v3</div></a>
							</div>
						</div>
					</div>
					<div class="menu-item has-sub closed">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="ion-ios-mail"></i>
							</div>
							<div class="menu-text">Email</div>
							<div class="menu-badge">10</div>
						</a>
						<div class="menu-submenu">
							<div class="menu-item">
								<a href="email_inbox.html" class="menu-link">
									<div class="menu-text">Inbox</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="email_compose.html" class="menu-link">
									<div class="menu-text">Compose</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="email_detail.html" class="menu-link">
									<div class="menu-text">Detail</div>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item active">
						<a href="widget.html" class="menu-link">
							<div class="menu-icon">
								<i class="ion-ios-nutrition bg-blue"></i> 
							</div>
							<div class="menu-text">Widgets <span class="menu-label">NEW</span></div>
						</a>
					</div>
					<div class="menu-item has-sub expand">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="ion-ios-color-filter bg-indigo"></i>
							</div>
							<div class="menu-text">UI Elements <span class="menu-label">NEW</span></div> 
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu" style="display: block;">
							<div class="menu-item">
								<a href="ui_general.html" class="menu-link">
									<div class="menu-text">General <i class="fa fa-paper-plane text-theme"></i></div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_typography.html" class="menu-link">
									<div class="menu-text">Typography</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_tabs_accordions.html" class="menu-link">
									<div class="menu-text">Tabs &amp; Accordions</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_unlimited_tabs.html" class="menu-link">
									<div class="menu-text">Unlimited Nav Tabs</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_modal_notification.html" class="menu-link">
									<div class="menu-text">Modal &amp; Notification <i class="fa fa-paper-plane text-theme"></i></div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_widget_boxes.html" class="menu-link">
									<div class="menu-text">Widget Boxes</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_media_object.html" class="menu-link">
									<div class="menu-text">Media Object</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_buttons.html" class="menu-link">
									<div class="menu-text">Buttons <i class="fa fa-paper-plane text-theme"></i></div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_icons.html" class="menu-link">
									<div class="menu-text">Icons</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_simple_line_icons.html" class="menu-link">
									<div class="menu-text">Simple Line Icons</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_ionicons.html" class="menu-link">
									<div class="menu-text">Ionicons</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_tree.html" class="menu-link">
									<div class="menu-text">Tree View</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_language_bar_icon.html" class="menu-link">
									<div class="menu-text">Language Bar &amp; Icon</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_social_buttons.html" class="menu-link">
									<div class="menu-text">Social Buttons</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_tour.html" class="menu-link">
									<div class="menu-text">Intro JS</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="ui_offcanvas_toasts.html" class="menu-link">
									<div class="menu-text">Offcanvas &amp; Toasts <i class="fa fa-paper-plane text-theme"></i></div>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item ">
						<a href="bootstrap_5.html" class="menu-link">
							<div class="menu-icon-img">
								<img src="/dist/img/logo/logo-bs5.png" alt="">
							</div>
							<div class="menu-text">Bootstrap 5 <span class="menu-label">NEW</span></div> 
						</a>
					</div>
					<div class="menu-item has-sub closed">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="ion-ios-briefcase bg-gradient-purple-indigo"></i>
							</div>
							<div class="menu-text">Form Stuff <span class="menu-label">NEW</span></div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu" style="display: none;">
							<div class="menu-item">
								<a href="form_elements.html" class="menu-link">
									<div class="menu-text">Form Elements <i class="fa fa-paper-plane text-theme"></i></div>
								</a>
							</div>
							<div class="menu-item">
								<a href="form_plugins.html" class="menu-link">
									<div class="menu-text">Form Plugins <i class="fa fa-paper-plane text-theme"></i></div>
								</a>
							</div>
							<div class="menu-item">
								<a href="form_slider_switcher.html" class="menu-link">
									<div class="menu-text">Form Slider + Switcher</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="form_validation.html" class="menu-link">
									<div class="menu-text">Form Validation</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="form_wizards.html" class="menu-link">
									<div class="menu-text">Wizards <i class="fa fa-paper-plane text-theme"></i></div>
								</a>
							</div>
							<div class="menu-item">
								<a href="form_wysiwyg.html" class="menu-link">
									<div class="menu-text">WYSIWYG</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="form_editable.html" class="menu-link">
									<div class="menu-text">X-Editable</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="form_multiple_upload.html" class="menu-link">
									<div class="menu-text">Multiple File Upload</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="form_summernote.html" class="menu-link">
									<div class="menu-text">Summernote</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="form_dropzone.html" class="menu-link">
									<div class="menu-text">Dropzone</div>
								</a>
							</div>
						</div>
					</div>
					
					<!-- BEGIN minify-button -->
					<div class="menu-item d-flex">
						<a href="javascript:;" class="app-sidebar-minify-btn ms-auto" data-toggle="app-sidebar-minify"><i class="ion-ios-arrow-back"></i> <div class="menu-text">Collapse</div></a>
					</div>
					<!-- END minify-button -->
				</div>
				<!-- END menu -->
			<div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
			<!-- END scrollbar -->
		</div>
		<div class="app-sidebar-bg"></div>
		<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
		<!-- END scroll-top-btn -->
	</div>
	<!-- END #app -->
	
	<!-- ================== BEGIN core-js ================== -->
	<script src="/dist/js/vendor.min.js"></script>
	<script src="/dist/js/app.min.js"></script>
	<script src="/dist/js/theme/apple.min.js"></script>
	<!-- ================== END core-js ================== -->
	<!-- ================== END page-js ================== -->

<div id="monica-content-root" class="monica-widget" style="pointer-events: auto;"></div></body></html>