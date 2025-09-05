<!DOCTYPE html>


<html lang="en" class="hydrated"><head>
	<meta charset="utf-8"><style data-styles="">ion-icon{visibility:hidden}.hydrated{visibility:inherit}</style>
	<title>Argos SpoQ | Admin Login</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
	<meta content="" name="description">
	<meta content="" name="author">
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="/dist/css/vendor.min.css" rel="stylesheet">
	<link href="/dist/css/apple/app.min.css" rel="stylesheet">
	<script src="/dist/plugins/ionicons/dist/ionicons/ionicons.js" type="text/javascript"></script>
	<!-- ================== END core-css ================== -->
<style id="monica-reading-highlight-style">
        .monica-reading-highlight {
          animation: fadeInOut 1.5s ease-in-out;
        }

        @keyframes fadeInOut {
          0%, 100% { background-color: transparent; }
          30%, 70% { background-color: rgba(2, 118, 255, 0.20); }
        }
      </style></head>
<body class="pace-done pace-top" monica-id="ofpnmcalabcbjgholdjcjblkibolbppb" monica-version="7.9.1"><div class="pace pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>
	<!-- BEGIN #loader -->
	<div id="loader" class="app-loader loaded">
		<span class="spinner"></span>
	</div>
	<!-- END #loader -->

	
	<!-- BEGIN #app -->
	<div id="app" class="app">
		<!-- BEGIN login -->
		<div class="login login-v2 fw-bold">
			<!-- BEGIN login-cover -->
			<div class="login-cover">
				<div class="login-cover-img" style="background-image: url(/dist/img/login-bg/login-bg-17.jpg)" data-id="login-cover-image"></div>
				<div class="login-cover-bg"></div>
			</div>
			<!-- END login-cover -->
			
			<!-- BEGIN login-container -->
			<div class="login-container">
				<!-- BEGIN login-header -->
				<div class="login-header">
					<div class="brand">
						<div class="d-flex align-items-center">
							<span class="logo"><ion-icon name="cloud" role="img" class="md hydrated"></ion-icon></span> <b>ARGOS</b> SpoQ
						</div>
						<small>DYCIS Admin Management System</small>
					</div>
					<div class="icon">
						<i class="fa fa-lock"></i>
					</div>
				</div>
				<!-- END login-header -->
				
				<!-- BEGIN login-content -->
				<div class="login-content">
          <form name="direct_login_form" id="direct_login_form" action="/adminlogin/adminLoginAction" method="post" onSubmit="return direct_login_form_submit();">
						<div class="form-floating mb-20px">
							<input type="text" class="form-control fs-13px h-45px border-0" placeholder="Login ID" id="admin_id" name="admin_id">
							<label for="loginId" class="d-flex align-items-center text-gray-600 fs-13px">Login ID</label>
						</div>
						<div class="form-floating mb-20px">
							<input type="password" class="form-control fs-13px h-45px border-0" placeholder="Password" id="admin_pass" name="admin_pass"  autocomplete="new-password">
							<label for="loginId" class="d-flex align-items-center text-gray-600 fs-13px">Password</label>
						</div>
						<!--
						<div class="form-check mb-20px">
							<input class="form-check-input border-0" type="checkbox" value="1" id="rememberMe">
							<label class="form-check-label fs-13px text-gray-500" for="rememberMe">
								Remember Me
							</label>
						</div>
						-->
						<div class="mb-20px">
							<button type="submit" class="btn btn-primary d-block w-100 h-45px btn-lg">Sign me in</button>
						</div>
						<!-- <div class="text-gray-500">
							Not a member yet? Click <a href="register_v3.html" class="text-white">here</a> to register.
						</div> -->
					</form>
				</div>
				<!-- END login-content -->
			</div>
			<!-- END login-container -->
		</div>
		<!-- END login -->
		
		<!-- BEGIN login-bg -->
		<!-- <div class="login-bg-list clearfix">
			<div class="login-bg-list-item active"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="/dist/img/login-bg/login-bg-17.jpg" style="background-image: url(/dist/img/login-bg/login-bg-17.jpg)"></a></div>
			<div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="/dist/img/login-bg/login-bg-16.jpg" style="background-image: url(/dist/img/login-bg/login-bg-16.jpg)"></a></div>
			<div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="/dist/img/login-bg/login-bg-15.jpg" style="background-image: url(/dist/img/login-bg/login-bg-15.jpg)"></a></div>
			<div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="/dist/img/login-bg/login-bg-14.jpg" style="background-image: url(/dist/img/login-bg/login-bg-14.jpg)"></a></div>
			<div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="/dist/img/login-bg/login-bg-13.jpg" style="background-image: url(/dist/img/login-bg/login-bg-13.jpg)"></a></div>
			<div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="/dist/img/login-bg/login-bg-12.jpg" style="background-image: url(/dist/img/login-bg/login-bg-12.jpg)"></a></div>
		</div> -->
		<!-- END login-bg -->
				<!-- END theme-switch -->
		<!-- END theme-panel -->
		<!-- BEGIN scroll-top-btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-theme btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
		<!-- END scroll-top-btn -->
	</div>
	<!-- END #app -->
	
	<!-- ================== BEGIN core-js ================== -->
	<script src="/dist/js/vendor.min.js" type="text/javascript"></script>
	<script src="/dist/js/app.min.js" type="text/javascript"></script>
	<!-- ================== END core-js ================== -->
	
	<!-- ================== BEGIN page-js ================== -->
	<script src="/dist/js/demo/login-v2.demo.js" type="text/javascript"></script>
	<!-- ================== END page-js ================== -->
	<script async="" src="https://www.googletagmanager.com/gtag/js?id=G-Y3Q0VGQKY3" type="text/javascript"></script>
	<script type="text/javascript">
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
	
		gtag('config', 'G-Y3Q0VGQKY3');
	</script>
<!-- <script defer="" src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon="{&quot;rayId&quot;:&quot;917c61974b972f3f&quot;,&quot;version&quot;:&quot;2025.1.0&quot;,&quot;r&quot;:1,&quot;token&quot;:&quot;4db8c6ef997743fda032d4f73cfeff63&quot;,&quot;serverTiming&quot;:{&quot;name&quot;:{&quot;cfExtPri&quot;:true,&quot;cfL4&quot;:true,&quot;cfSpeedBrain&quot;:true,&quot;cfCacheStatus&quot;:true}}}" crossorigin="anonymous"></script> -->

<div id="monica-content-root" class="monica-widget" style="pointer-events: auto;"></div>
<script>
	function direct_login_form_submit()
	{
		var f = document.direct_login_form;
		if ( f.admin_id.value == "" )
		{
			alert("어드민 아이디를 입력하세요");
			f.admin_id.focus();
			return false;
		}
		
		if ( f.admin_pass.value == "" )
		{
			alert("어드민 비밀번호를 입력하세요.");
			f.admin_pass.focus();
			return false;
		}
		
		return true;
		
	}
</script>
</body>
</html>