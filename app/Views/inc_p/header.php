<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta http-equiv="expires" content="-1" >
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Argos SpoQ</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer">
	<link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	
	<!-- IonIcons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	
	<!-- daterange picker -->
	<link rel="stylesheet" href="/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css">
	
	<!-- icheck -->
	<link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	
	<!-- toastr -->
	<link rel="stylesheet" href="/plugins/toastr/toastr.css">
	
	<!-- sweetalert2 toastr -->
	<link rel="stylesheet" href="/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	
	<!-- Theme style -->
	<link rel="stylesheet" href="/dist/css/adminlte.css">
	
	<link rel="stylesheet" href="/dist/css/ama.css?v=1">


	<!-- select2 -->
	<link rel="stylesheet" href="/plugins/select2/css/select2.css">
	<script src="/dist/js/dne.common-1.0.0.js"></script>

	<?php if ( in_array('fullCalendar', $useJs) ) : ?>
		<!-- fullCalendar -->
	  	<link rel="stylesheet" href="/plugins/fullcalendar/main.css" >
	<?php endif; ?>	
	
	<!-- OPTIONAL SCRIPTS -->
	<!-- AdminLTE for demo purposes -->
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	
	<!-- cropper --
	<link rel="stylesheet" href="/plugins/cropperjs-main/dist/cropper.css">
	
	<script src="https://cdn.jsdelivr.net/npm/eruda"></script>
	<script>eruda.init();</script>
	-->


	<link rel="stylesheet" href="/dist/css/font.css">

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
	</style>
	
</head>
<!--
`body` tag options:
  Apply one or more of the following classes to to the body tag
  to get the desired effect
  * sidebar-collapse
  * sidebar-mini
-->
<body class="hold-transition sidebar-mini">
	<div class="wrapper">