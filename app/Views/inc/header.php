<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta http-equiv="expires" content="-1" >
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ARGOS SpoQ</title>
	
<!-- jQuery -->
<script src="/plugins/moment/moment.min.js"></script>
<!-- <script src="/dist/plugins/jquery/dist/jquery.min.js"></script> -->
<script src="/plugins/jquery/jquery.min.js"></script>
<script src="/dist/js/vendor.min.js"></script>
<script src="/dist/js/app.min.js"></script>
<script src="/dist/js/theme/apple.min.js"></script>

<!-- Bootstrap -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- jQuery UI -->
<script src="/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="/plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="/plugins/jquery.maskMoney.js"></script>
<script src="/dist/plugins/jstree/dist/jstree.min.js"></script>


<link href="/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet">
<!-- date-picker -->
<script src="/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="/plugins/bootstrap-datepicker/dist/locales/bootstrap-datepicker.ko.min.js"></script>
<script src="/dist/js/dne.common-1.0.0.js"></script>
<script src="/dist/js/common.js"></script>
<!-- <link href="/dist/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="/dist/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
<link href="/dist/plugins/datatables.net-rowreorder-bs4/css/rowReorder.bootstrap4.min.css" rel="stylesheet" />
<script src="/dist/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/dist/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/dist/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="/dist/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="/dist/plugins/datatables.net-rowreorder/js/dataTables.rowReorder.min.js"></script>
<script src="/dist/plugins/datatables.net-rowreorder-bs4/js/rowReorder.bootstrap4.min.js"></script> -->

<?php if ( in_array('fullCalendar', $useJs) ) : ?>
	
	<div id="fullcalendar_proc_url" data-name="<?=$fullCalendar['url']?>" style='display:none' ></div>
	<!-- fullCalendar 5.5.1 -->
	<script src="/plugins/fullcalendar/main.js"></script>
	<script src="/dist/js/amajs/ama_calendar.js?10"></script>
<?php endif; ?>


<!-- select2 -->
<script src="/plugins/select2/js/select2.js"></script>

<!-- toastr -->
<script src="/plugins/toastr/toastr.min.js"></script>

<!-- SweetAlert2 toastr -->
<script src="/plugins/sweetalert2/sweetalert2.min.js"></script>

<!-- inputmask -->
<script src="/plugins/inputmask/jquery.inputmask.min.js"></script>

<!--  cropper -->
<script src="/plugins/cropperjs-main/dist/cropper.js"></script>
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome Icons -->
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer">
	
	<link href="/dist/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	<link href="/dist/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
	<script src="/dist/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="/dist/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="/dist/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script src="/dist/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
	<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

	

	
	<!-- IonIcons -->
  	<!-- <link src="/plugins/ionicons/css/ionicons.min.css" rel="stylesheet" /> -->

	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/js/ionicons.js"> -->
	
	<!-- daterange picker -->
	<link rel="stylesheet" href="/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css">
	
	<!-- icheck -->
	<link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	

	<!-- toastr -->
	<link rel="stylesheet" href="/plugins/toastr/toastr.css">
	
	<!-- sweetalert2 toastr -->
	<link rel="stylesheet" href="/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	

	<link href="/dist/plugins/animate.css/animate.min.css" rel="stylesheet" />
  	<link href="/dist/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
   	<link href="/dist/plugins/jquery-ui-dist/jquery-ui.min.css" rel="stylesheet" />
  	<link href="/dist/plugins/pace-js/themes/black/pace-theme-flash.css" rel="stylesheet" />
  	<!-- <link href="/dist/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" /> -->
	
	<!-- select2 -->
	<link rel="stylesheet" href="/plugins/select2/css/select2.css">

	<?php if ( in_array('fullCalendar', $useJs) ) : ?>
		<!-- fullCalendar -->
	  	<link rel="stylesheet" href="/plugins/fullcalendar/main.css" >
	<?php endif; ?>	
	<!-- Theme style -->
	<link href="/dist/css/vendor.min.css" rel="stylesheet" />
    <link href="/dist/css/apple/app.min.css" rel="stylesheet" />

	<!-- OPTIONAL SCRIPTS -->
	<!-- ColorAdmin for demo purposes -->
	<!-- ColorAdmin dashboard demo (This is only for demo purposes) -->
	
	<!-- cropper --
	<link rel="stylesheet" href="/plugins/cropperjs-main/dist/cropper.css">
	
	<script src="https://cdn.jsdelivr.net/npm/eruda"></script>
	<script>eruda.init();</script>
	-->
	<style>
		#load_pre {
			width:100%;
			height:100%;
			top:0;
			left:0;
			position:fixed;
			display:block;
			opacity:0.8;
			background: white;
			z-index:999999;
			text-align:center;
			
		}
		
		#load_pre > img {
			position: absolute;
			top:50%;
			left:50%;
			z-index:1000000;
		}

		.table-hover tbody tr:hover {
			outline: 1px solid #808080; /* 옅은 회색 테두리 */
		}
		table.table-hover tbody tr:hover {
			background-color: #81b1eb !important; 
		}

		
		.table-responsive {
			width: 100%;
			overflow-x: auto;
		}

		.table {
			width: 100%;
			table-layout: auto;
		}

		.table th,
		.table td {
			white-space: nowrap; /* 글자가 줄바꿈되지 않도록 설정 */
			overflow: hidden;
			text-overflow: ellipsis; /* 내용이 넘칠 경우 "..."으로 표시 */
		}
		#grid {
			min-width: 100%;
		}

		.table-bordered th, .table-bordered td {
			border: 1px solid #d2d2d2 !important;
		}


		.app-content {
			flex: 1;
			display: flex;
			flex-direction: column;
			min-height: 100vh;
		}

		

		
		/* .table th, .table td {
			padding: 0.3rem !important;
			font-size: 0.9rem;
		} */
		/* .table th, .table td {
			padding: 0.3rem !important;
			font-size: 0.9rem;
		}

		.table-bordered th, .table-bordered td {
			border: 1px solid #a3a3a3;
		}

		table.table-hover tbody tr:hover {
			background-color: #81b1eb !important; 
		} */
		 
	</style>
	
</head>
<!--
`body` tag options:
  Apply one or more of the following classes to to the body tag
  to get the desired effect
  * sidebar-collapse
  * sidebar-mini
-->
<body class="pace-done">
<div class="row" style="position: relative;">

<div id="app" class="app app-header-fixed app-sidebar-fixed has-scroll">


<div><div>