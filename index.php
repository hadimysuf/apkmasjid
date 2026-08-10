<?php
include_once "session.php";
if(!isset($_SESSION["user_id"])){
	header("Location: login.php");
}
$file	= 'db/database.json';
$name	= '';
if (file_exists($file)){
	$json 	= file_get_contents($file);
	$db		= json_decode($json, true);
	$name	= $db['setting']['nama'];
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Display|Masjid|Admin</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="icon" type="image/png" href="icon.png"/>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/font-awesome.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/_all-skins.min.css">
  <link rel="stylesheet" href="dist/css/bootstrap-datetimepicker.css">
  <link rel="stylesheet" href="dist/css/datatables.min.css">
  <link rel="stylesheet" href="dist/css/buttons.dataTables.min.css">
	<style>
		button.info-box{
			padding:0;
			border:none;
			border-radius:10px;
			overflow:hidden;
		}
		
		button.info-box:active{
			box-shadow: inset 0 0 100px #000000
		}
		.nav-tabs-custom .tab-content h4{
			background-color: #051a10;
			color: #d4af37; 
			font-size: 18px; 
			padding: 10px 15px; 
			margin: 0;
			font-size: 14px;
			text-transform: uppercase;
			border-radius: 8px;
		}
		.section-wallpaper .small-box{
			background-position: center center;
			background-size: cover;
			background-repeat: no-repeat;
			margin-bottom:10px;
			border-radius: 12px;
			overflow: hidden;
		}
		.section-wallpaper .small-box .inner{
			min-height:100px;
		}
		.section-wallpaper .small-box>.small-box-footer{
			background: rgba(0, 0, 0, 0.4);
		}
		#container{
			padding-bottom:20px;
		}
		form .box .input,
		form .box .input-group{
			margin-bottom:1px;
		}
		form .box .input-group>.input-group-addon:first-child{
			background-color: #051a10;
			border-color: #051a10;
			color: #d4af37;
			min-width: 100px;
			text-align: right;
		}
		.dataTable thead{
			background: #051a10;
			color: #d4af37;
		}
		.table-responsive{
			border:none !important;
		}
		.sidebar-menu>li>a{
			cursor:pointer;
		}
		.date-navigation .btn{
			border-radius: 20px;
			height: 30px;
			font-size:12px;
			padding: 2px 15px;
			margin: 15px;
		}
		div.dataTables_wrapper div.dataTables_filter label{
			margin-bottom:0;
		}
		table.dataTable{
			margin-top: 0 !important;
		}
		.no-margin>tbody>tr>td,
		.no-margin>thead>tr>th{
			text-align:center;
			padding:0 !important;
		}
		.no-margin>thead>tr>th{
			padding:3px 0 !important;
		}
		.no-margin>tbody>tr>td{
			margin:0;
			padding: 2px;
		}
		.today{
			background: rgba(212, 175, 55, 0.2) !important;
			font-weight:bold;
		}

		/* ---- ADMIN MODERNIZATION OVERRIDES ---- */
		:root {
			--admin-bg: #f6f4eb;
			--admin-primary: #051a10;
			--admin-accent: #d4af37;
			--admin-sage: #8eb69b;
		}
		body, h1, h2, h3, h4, h5, h6, .main-header .logo, .main-header .navbar, .sidebar-menu > li > a, .btn, .form-control {
			font-family: 'Montserrat', sans-serif !important;
		}
		.content-wrapper {
			background-color: var(--admin-bg) !important;
		}
		.skin-green .main-header .navbar {
			background-color: var(--admin-primary) !important;
		}
		.skin-green .main-header .logo {
			background-color: #030e09 !important;
			color: var(--admin-accent) !important;
		}
		.skin-green .main-header .logo:hover {
			background-color: #020906 !important;
		}
		.skin-green .main-sidebar {
			background-color: var(--admin-primary) !important;
		}
		.skin-green .sidebar-menu>li:hover>a, 
		.skin-green .sidebar-menu>li.active>a, 
		.skin-green .sidebar-menu>li.menu-open>a {
			color: var(--admin-accent) !important;
			background: rgba(212, 175, 55, 0.1) !important;
			border-left-color: var(--admin-accent) !important;
		}
		.skin-green .sidebar-menu>li.header {
			background: #030e09 !important;
			color: var(--admin-sage) !important;
		}
		.box, .nav-tabs-custom {
			border-radius: 12px !important;
			border-top: 3px solid var(--admin-accent) !important;
			box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;
		}
		.btn {
			border-radius: 8px !important;
			transition: all 0.3s ease;
		}
		.btn-success {
			background-color: var(--admin-accent) !important;
			border-color: #cda22a !important;
			color: var(--admin-primary) !important;
			font-weight: bold;
		}
		.btn-success:hover {
			background-color: #cda22a !important;
			box-shadow: 0 2px 8px rgba(212, 175, 55, 0.4);
		}
		.btn-primary {
			background-color: var(--admin-primary) !important;
			border-color: #030e09 !important;
		}
		.btn-primary:hover {
			background-color: #030e09 !important;
		}
		.form-control {
			border-radius: 8px !important;
			border: 1px solid #ddd !important;
		}
		.form-control:focus {
			border-color: var(--admin-accent) !important;
			box-shadow: 0 0 5px rgba(212, 175, 55, 0.5) !important;
		}
		.input-group-addon {
			border-radius: 8px 0 0 8px !important;
			border-color: #ddd !important;
		}
		.input-group .form-control {
			border-radius: 0 8px 8px 0 !important;
		}
	</style>
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="" class="logo" onclick="location.reload()">
      <span class="logo-mini">DM</span>
      <span class="logo-lg"><b>Display</b>Masjid</span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li>
            <a><?=$name?> - Admin display</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVIGATION</li>
		<li class="active"><a data-target="info"><i class="fa fa-comment"></i> <span>Informasi</span></a></li>
		<li><a data-target="running_text"><i class="fa fa-text-width"></i> <span>Running text</span></a></li>
		<li><a data-target="wallpaper"><i class="fa fa-television"></i> <span>Wallpaper</span></a></li>
		<li><a data-target="timer"><i class="fa fa-clock-o"></i> <span>Timer</span></a></li>
		<li><a data-target="keuangan"><i class="fa fa-money"></i> <span>Keuangan</span></a></li>
		<li><a data-target="donasi"><i class="fa fa-qrcode"></i> <span>Donasi & QRIS</span></a></li>
		<li><a data-target="kegiatan"><i class="fa fa-calendar-check-o"></i> <span>Kegiatan</span></a></li>
		<li><a data-target="galeri"><i class="fa fa-picture-o"></i> <span>Galeri Video/Foto</span></a></li>
		<li><a data-target="bumper"><i class="fa fa-bullhorn"></i> <span>Kelola Bumper</span></a></li>
		<li><a data-target="urutan"><i class="fa fa-sort-numeric-asc"></i> <span>Urutan Tampilan</span></a></li>
		<li><a data-target="jadwal"><i class="fa fa-calendar"></i> <span>Setting Jadwal</span></a></li>
		<li><a data-target="latar_sholat"><i class="fa fa-image"></i> <span>Latar Waktu Sholat</span></a></li>
		<li><a data-target="simulasi"><i class="fa fa-history"></i> <span>Simulasi Jadwal</span></a></li>
		<li><a data-target="pengaturan"><i class="fa fa-cogs"></i> <span>Pengaturan</span></a></li>
		<li><a data-target="sistem"><i class="fa fa-microchip"></i> <span>Sistem</span></a></li>
		<li><a data-target="about"><i class="fa fa-info-circle"></i> <span>About</span></a></li>
		<li><a data-target="logout"><i class="fa fa-sign-out"></i> <span>Logout</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
	<div id='container' class="content-wrapper">
	</div>
	<footer class="main-footer">
		<div class="pull-right">
		  <b>Version</b> 1.0.0 (Feb 2020)
		</div>
		<strong>Display|Masjid</strong> Aplication
	</footer>
	<!-- <script src="cordova.js"></script> -->
	<script src="dist/js/jquery.min.js"></script>
	<script src="dist/js/bootstrap.min.js"></script>
	<script src="dist/js/swipe.js"></script>
	<script src="dist/js/adminlte.min.js"></script>
	<script src="dist/js/moment-with-locales.js"></script>
	<script src="dist/js/bootstrap-datetimepicker.min.js"></script>
	<script src="dist/js/datatables.min.js"></script>
	<script src="dist/js/dataTables.buttons.min.js"></script>
	<script src="dist/js/buttons.html5.min.js"></script>
	<script src="display/js/PrayTimes.js"></script>
	<script src="dist/js/fn.js"></script>
</body>
</html>
