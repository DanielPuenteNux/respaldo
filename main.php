<?php
session_name('_erp');
session_start();

// checamos si NO tenemos ID
if (isset($_SESSION['userID']) == false || $_SESSION['userID'] == 0) {

	// move to index
	header('Location: ../index.php');
	exit;
} else {

	// regeneramos ID de sesión para evitar hijacking (robo de sesión)
	session_regenerate_id(true);

	// validamos fingerprint
	if ($_SESSION['fingerPrint'] !== md5($_SERVER['HTTP_USER_AGENT'])) {

		// limpiamos variables de sesión
		$_SESSION['userID'] = NULL;
		$_SESSION['userName'] = NULL;
		$_SESSION['userFullName'] = NULL;
		$_SESSION['userRoleID'] = NULL;
		$_SESSION['userRole'] = NULL;
		$_SESSION['token_value'] = NULL;
		$_SESSION['fingerPrint'] = NULL;

		// move to index
		header('Location: index.php');
		exit;
	}
}


include("../config/NoCache.php");
include("../config/Database.php");
include("../config/Generales.php");
include("../config/autoloader.php");

// obtenemos objeto
$generals = new Generales();


?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="NÜX SOftware">

	<!-- FAVIcon -->
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#221f20">
	<link rel="shortcut icon" href="../favicon.ico">
	<meta name="msapplication-TileColor" content="#221f20">
	<meta name="msapplication-config" content="/browserconfig.xml">
	<meta name="theme-color" content="#221f20">

	<title>SOFI</title>

	<!-- PWA -->
	<link rel="manifest" href="../manifest.json" />

	<!-- Google font-->
	<!-- <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet"> -->
	<!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet"> -->
	<!-- <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet"> -->
	<link rel="stylesheet" type="text/css" href="../assets/css/fonts_system.css">
	<!-- Globales -->
	<link rel="stylesheet" type="text/css" href="../assets/css/generales.css">
	<link rel="stylesheet" type="text/css" href="../<?php $generals->latest_version('assets/css/notifications.css'); ?>">
	<!-- Font Awesome-->
	<link rel="stylesheet" type="text/css" href="../assets/css/fontawesome.css">
	<!-- ico-font-->
	<link rel="stylesheet" type="text/css" href="../assets/css/icofont.css">
	<!-- Themify icon-->
	<link rel="stylesheet" type="text/css" href="../assets/css/themify.css">
	
	<!-- lottie -->	
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/lottie-player.js'); ?>"></script>
	
	<!-- Feather icon-->
	<link rel="stylesheet" type="text/css" href="../assets/css/feather-icon.css">
	<!-- Plugins css start-->
	<link rel="stylesheet" type="text/css" href="../assets/css/chartist.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/prism.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/select2.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/select2-bootstrap-5-theme.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/sweetalert/sweetalert.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/bootstrap-side-modals.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/photoswipe.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/date-picker.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/default-skin/default-skin.css">
	<!-- Plugins css Ends-->
	<!-- Bootstrap css-->
	<link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/animate.css">
	<link id="bootstrap-file" rel="stylesheet" type="text/css" href="">
	<!-- App css-->
	<link href="../<?php $generals->latest_version('assets/css/style.css'); ?>" rel="stylesheet" type="text/css">
	<link href="../<?php $generals->latest_version('assets/css/sofi_loader.css'); ?>" rel="stylesheet" type="text/css">
	<link href="../<?php $generals->latest_version('assets/css/bitacora.css'); ?>" rel="stylesheet" type="text/css">
	<link id="color" rel="stylesheet" href="../assets/css/color-1.css" media="screen">
	<!-- Responsive css-->
	<link rel="stylesheet" type="text/css" href="../assets/css/responsive.css">
	<!-- Datatable css-->
	<link rel="stylesheet" type="text/css" href="../assets/css/datatable/datatables.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/datatable/buttons.bootstrap5.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/datatable/datatable-extension/select.bootstrap5.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/datatable/datatable-extension/fixedHeader.bootstrap5.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/datatable/datatable-extension/responsive.bootstrap5.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/datatable/datatable-extension/jquery.dataTables.colResize.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/datatable/datatable-extension/rowGroup.dataTables.min.css">
	<!-- Components-->
	<!-- <link href="../<?php /*$generals->latest_version('assets/css/op_viewer.css');*/ ?>" rel="stylesheet" type="text/css"/> -->
	<link href="../<?php $generals->latest_version('assets/css/ticket_comments.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/almacen_vale.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/almacen_vales.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/almacen_entrada.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/almacen_inventario.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/almacen_bitacora.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/almacen_recepcion.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/catalogo_contabilidad.css'); ?>" rel="stylesheet" type="text/css">
	<link href="../<?php $generals->latest_version('assets/css/contabilidad_ordenescompra.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/contabilidad_liberarOC.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/contabilidad_liberarReq.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/logistica_remision.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/logistica_calendario.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/catalogo_compras_proveedores.css') ?>" rel="stylesheet" type="text/css" /><!--MCHAVIRA-->
	<link href="../<?php $generals->latest_version('assets/css/compras_requisicion.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/compras_ordenCompra.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/compras_recepcion_ordenCompra.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/compras_seguimiento.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/dashboard_ordenes_activas.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/uploadFiles.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/ordenProduccion.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/catalogo_sistemas_roles.css') ?>" rel="stylesheet" type="text/css">
	<link href="../<?php $generals->latest_version('assets/css/ordenProduccion_movimientoCarga.css'); ?>" rel="stylesheet" type="text/css"><!--MCHAVIRA-->
	<link href="../<?php $generals->latest_version('assets/css/ordenProduccion_movimientoCargaMultiple.css'); ?>" rel="stylesheet" type="text/css"><!--AALMAGUER-->
	<link href="../<?php $generals->latest_version('assets/css/ordenProduccion_tracker.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/ordenProduccion_secProcesos_editor.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/modal_ordenProduccion_visor.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/ordenProduccion_cierreOP.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/templateSP.css'); ?>" rel="stylesheet" type="text/css" /><!--Mchavira-->
	<link href="../<?php $generals->latest_version('assets/css/diseno_seguimiento.css'); ?>" rel="stylesheet" type="text/css"><!--Mchavira-->
	<link href="../<?php $generals->latest_version('assets/css/calidad_seguimiento.css'); ?>" rel="stylesheet" type="text/css" /><!--Mchavira-->
	<link href="../<?php $generals->latest_version('assets/css/produccion_programacion.css'); ?>" rel="stylesheet" type="text/css" /><!--Mchavira-->
	<link href="../<?php $generals->latest_version('assets/css/movcarga_equipos.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="../<?php $generals->latest_version('assets/css/produccion_programacion_nux.css'); ?>" rel="stylesheet" type="text/css" /><!--Mchavira-->
	<link href="../<?php $generals->latest_version('assets/css/ventas_cotizacion.css'); ?>" rel="stylesheet" type="text/css" />

	<!--CSS-->
	<link href="../<?php $generals->latest_version('assets/css/generales.css'); ?>" rel="stylesheet" type="text/css" />

	<!-- latest jquery | Importante que se cargue al inicio -->
	<script type="text/javascript" src="../assets/js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="../assets/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../assets/js/select2.min.js"></script>
	<script type="text/javascript" src="../assets/js/select2.es.js"></script>

</head>

<body>

	<!-- Loader starts-->
	<!-- <div class="loader-wrapper" id="sofiLoader">
      <div class="center-body">
			<div class="loader-circle-11">
				<div class="arc"></div>
				<div class="arc"></div>
				<div class="arc"></div>
			</div>
        </div>
      </div>
    </div> -->
	<div class="globalBackground"></div>
	<div class="tabbedPWA_topBar"></div>
	<div class="loader-wrapper" id="sofiLoader">
		<div class="center-body">
			<svg id="sofiLoader_back" class="hormiga2" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 11338.6 11338.6" viewBox="0 0 11338.6 11338.6">
				<path d="M8550.2 3783.8c10.8 80.9 43.2 229.5 159 352 224.9 238 612 215.9 862.9 102.2 365.8-165.8 466.3-537.4 476.9-579.1 84.4-334.1-41.4-683.2-272.5-885.6-357.5-313.1-904.5-220.9-1214.9-34.1-314.6 189.3-504.2 546.8-738 987.8-41.1 77.5-130 251.4-283.9 465.5-189.8 264.2-317.6 365.8-366.6 402.7-151.3 114-297.9 174.7-397.6 207.8 189.6 81.3 460 232.4 664.7 513.4 518.9 712 234.2 1733.3-129.1 2313.5-562.6 898.5-1541.3 1102.9-1743.2 1140.6C5304 8699.6 4521 8451 4029.4 7689.4c-407.4-631.2-673.2-1704.2-128.2-2404.1 210.2-269.9 480.1-409.1 662.1-481.4-99.7-33.1-246.3-93.7-397.6-207.8-49-36.9-176.8-138.5-366.6-402.7-153.8-214.1-242.8-388.1-283.9-465.5-233.9-441-423.4-798.5-738-987.8-310.4-186.8-857.4-279.1-1214.9 34.1-231.1 202.4-356.9 551.5-272.5 885.6 10.5 41.7 111 413.2 476.9 579.1 250.9 113.7 638 135.8 862.9-102.2 115.7-122.4 148.2-271 159-352" style="fill:none;stroke:#87212847;stroke-width:501.9807;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10" />
			</svg>
			<svg id="sofiLoader_runner" class="hormiga" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 11338.6 11338.6" viewBox="0 0 11338.6 11338.6">
				<path d="M8550.2 3783.8c10.8 80.9 43.2 229.5 159 352 224.9 238 612 215.9 862.9 102.2 365.8-165.8 466.3-537.4 476.9-579.1 84.4-334.1-41.4-683.2-272.5-885.6-357.5-313.1-904.5-220.9-1214.9-34.1-314.6 189.3-504.2 546.8-738 987.8-41.1 77.5-130 251.4-283.9 465.5-189.8 264.2-317.6 365.8-366.6 402.7-151.3 114-297.9 174.7-397.6 207.8 189.6 81.3 460 232.4 664.7 513.4 518.9 712 234.2 1733.3-129.1 2313.5-562.6 898.5-1541.3 1102.9-1743.2 1140.6C5304 8699.6 4521 8451 4029.4 7689.4c-407.4-631.2-673.2-1704.2-128.2-2404.1 210.2-269.9 480.1-409.1 662.1-481.4-99.7-33.1-246.3-93.7-397.6-207.8-49-36.9-176.8-138.5-366.6-402.7-153.8-214.1-242.8-388.1-283.9-465.5-233.9-441-423.4-798.5-738-987.8-310.4-186.8-857.4-279.1-1214.9 34.1-231.1 202.4-356.9 551.5-272.5 885.6 10.5 41.7 111 413.2 476.9 579.1 250.9 113.7 638 135.8 862.9-102.2 115.7-122.4 148.2-271 159-352" style="fill:none;stroke:#f42331;stroke-width:501.9807;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10" />
			</svg>
			<div class="eyes">
				<div class="atom atomLeft">
					<div class="eye eyeleft"></div>
					<div class="arc"></div>
					<div class="arc"></div>
					<div class="arc"></div>
				</div>
				<div class="atom atomRight">
					<div class="eye eyeright"></div>
					<div class="arc"></div>
					<div class="arc"></div>
					<div class="arc"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- Loader ends-->

	<!-- Title bar tools -->
	<div class="titleBar">

	</div>

	<!-- page-wrapper Start-->
	<div class="page-wrapper" style="display: none;">
		<!-- Page Header Start-->
		<div class="page-main-header">
			<div class="main-header-right row" style="padding-left:20px;">
				<div class="main-header-left col-auto px-0 d-lg-none">
					<a href="../index">
						<div class="logo-wrapper"></div>
					</a>
				</div>
				<div class="vertical-mobile-sidebar col-auto ps-3 d-none"><i class="fa fa-bars sidebar-bar"></i></div>
				<div class="mobile-sidebar col-auto ps-0 d-block" style="margin-left: 25px;">
					<div class="media-body switch-sm">
						<label class="switch"><a><i id="sidebar-toggle" data-feather="align-left"></i></a></label>
					</div>
				</div>
				<div class="nav-right col p-0">
					<ul class="nav-menus">
						<li id="mainHeader" style="display: flex; flex-wrap: nowrap; justify-content: flex-start; align-items: center; flex-direction: row; margin-left: 0px;">
							<h3 style="font-size: 24px; margin-bottom: 0; font-weight: 600; text-transform: uppercase;"></h3>
						</li>

						<?php

						// checamos si tiene permiso de mover carga
						/*if ($_SESSION['bytMovCarga'] == 1) {

								echo '<li style="height: 34px;">';
								echo '	<a class="text-dark" onclick="show_MovimientoCarga();"><i class="icon_movCarga"></i></a>';
								echo '</li>';
							}
							*/
						?>
						<li><a class="text-dark" style="font-size: 24px; cursor:pointer;" title="Regresar" onclick="javascript:window.history.back()"><i class="icofont icofont-arrow-left"></i></a></li>
						<li><a class="text-dark" style="font-size: 24px; cursor:pointer;" title="Avanzar" onclick="javascript:window.history.forward()"><i class="icofont icofont-arrow-right"></i></a></li>
						<li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
						<li id="NotifCenter" class="NotifCenter" onclick="handle_NotificationItems(event);">
							<div style="cursor:pointer;"><i data-feather="bell"></i></div>
							<div class="media" style="cursor:pointer;">
								<div class="dotted-animation" style="top:-14px;left:16px;"><span class="animate-circle" style="border-color: #c92018;"></span><span class="main-circle" style="background-color: #c92018;"></span></div>
							</div>
							<ul id="NotifCenter_Items" class="NotifCenter_Items notification-dropdown onhover-show-div">
								<li class="notifHeader">Notificaciones <span class="badge rounded-pill badge-primary pull-right">0</span> <span id="btnDismissAll_Notif" class="pull-right" title="Descartar todas" onclick="dismissAll_Notifications();"><i style="stroke:#df2116;position:relative;top:-1px;cursor:pointer;" data-feather="trash-2"></i></span></li>
								<div class="notifBody"></div>
							</ul>
						</li>
						<li>
							<!-- <a><i class="right_side_toggle" data-feather="message-circle"></i><span class="dot" style="display:none;"></span></a> -->
							<a data-bs-original-title="" title="Tickets de soporte" style="cursor: pointer;">
								<svg viewBox="-146.6174 251.4982 20.0431 24" width="20.0431" height="24" xmlns="http://www.w3.org/2000/svg" xmlns:bx="https://boxy-svg.com" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" class="right_side_toggle">
									<g transform="matrix(1.061639, 0, 0, 1.061639, -26.373312, 11.53071)">
										<path d="M -112.823 241.575 L -112.823 235.575 C -112.823 228.647 -105.323 224.316 -99.323 227.781 C -96.538 229.388 -94.823 232.359 -94.823 235.575 L -94.823 241.575" style="fill: none; stroke: rgb(47, 54, 61);"></path>
										<path d="M -94.823 242.575 C -94.823 243.679 -95.718 244.575 -96.823 244.575 L -97.823 244.575 C -98.928 244.575 -99.823 243.679 -99.823 242.575 L -99.823 239.575 C -99.823 238.47 -98.928 237.575 -97.823 237.575 L -94.823 237.575 L -94.823 242.575 Z M -112.823 242.575 C -112.823 243.679 -111.928 244.575 -110.823 244.575 L -109.823 244.575 C -108.718 244.575 -107.823 243.679 -107.823 242.575 L -107.823 239.575 C -107.823 238.47 -108.718 237.575 -109.823 237.575 L -112.823 237.575 L -112.823 242.575 Z" style="fill: rgb(47, 54, 61);"></path>
										<path d="M -102.257 247.355 C -102.257 247.775 -102.597 248.115 -103.016 248.115 L -103.395 248.115 C -103.814 248.115 -104.154 247.775 -104.154 247.355 L -104.154 246.217 C -104.154 245.798 -103.814 245.458 -103.395 245.458 L -102.257 245.458 L -102.257 247.355 Z" style="fill: rgb(47, 54, 61);"></path>
										<path style="fill: none; stroke: rgb(47, 54, 61);" d="M -95.307 240.368 L -95.33183333333334 241.45466666666664 C -95.35666666666667 242.54133333333334 -95.40633333333334 244.71466666666666 -96.58850000000001 245.7891666666667 C -97.77066666666667 246.86366666666666 -100.08533333333334 246.83933333333334 -101.24266666666666 246.82716666666667 L -102.4 246.815" bx:d="M -95.307 240.368 U -95.456 246.888 U -102.4 246.815 1@15752035"></path>
									</g>
								</svg>
								<span class="dot" style="display:none;"></span>
							</a>
						</li>
						<li class="onhover-dropdown">
							<div class="media align-items-center"><img class="align-self-center pull-right img-50 rounded-circle" src="../thumbnail?f=<?php echo $_SESSION['strFile_Face']; ?>&w=100&h=100" alt="header-user">
								<div class="dotted-animation" style="display:none;"><span class="animate-circle"></span><span class="main-circle"></span></div>
							</div>
							<ul class="profile-dropdown onhover-show-div p-20">
								<!-- <li><a><i data-feather="user"></i>Edit Profile</a></li>
							<li><a><i data-feather="mail"></i>Inbox</a></li>
							<li><a><i data-feather="lock"></i>Lock Screen</a></li>
							<li><a><i data-feather="settings"></i>Settings</a></li> -->
								<li><a onclick="doLogout();"><i data-feather="log-out"></i>Salir</a></li>
							</ul>
						</li>
					</ul>
					<div class="d-lg-none mobile-toggle pull-right"><i data-feather="more-horizontal"></i></div>
				</div>
				<script id="result-template" type="text/x-handlebars-template">
					<div class="ProfileCard u-cf">
						<div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
						<div class="ProfileCard-details">
							<div class="ProfileCard-realName">{{name}}</div>
						</div>
					</div>
				</script>
				<script id="empty-template" type="text/x-handlebars-template">
					<div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div>
				</script>
			</div>
		</div>
		<!-- Page Header Ends -->
		<!-- Page Body Start-->
		<div class="page-body-wrapper">

			<!-- Page Sidebar -->
			<?php include_once 'sidebar/sidebar.php' ?>

			<!-- Welcome -->
			<div class="page-body" id="optWelcome">
				<div class="container-fluid">
					<div class="page-header">
						<div class="row">
							<div class="col">
								<div class="page-header-left">
									<h3>Inicio</h3>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-5">
							<div class="card">
								<div class="card-header">
									<h5>INFORMACIÓN</h5>
									<div class="card-header-right">
										<ul class="list-unstyled card-option">
											<li><i class="icofont icofont-simple-left"></i></li>
											<li><i class="icofont icofont-maximize full-card"></i></li>
											<li><i class="icofont icofont-minus minimize-card"></i></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-7">
							<div class="card">
								<div class="card-header">
									<h5>AVISOS</h5>
									<div class="card-header-right">
										<ul class="list-unstyled card-option">
											<li><i class="icofont icofont-simple-left"></i></li>
											<li><i class="icofont icofont-maximize full-card"></i></li>
											<li><i class="icofont icofont-minus minimize-card"></i></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php include_once "usuarios/modal_view.php"; ?>

			<!-- Almacen -->
			<?php include_once "almacen/almacen_entrada_view.php"; ?>
			<?php include_once "almacen/almacen_entradas_view.php"; ?>
			<?php include_once "almacen/almacen_entrada_semiterminado_view.php"; ?>
			<?php include_once "almacen/almacen_entrada_terminado_view.php"; ?>
			<?php include_once "almacen/almacen_vale_view.php"; ?>
			<?php include_once "almacen/almacen_vales_view.php"; ?>
			<?php include_once "almacen/almacen_inventario.php"; ?>
			<?php include_once "almacen/almacen_bitacora_view.php"; ?>
			<?php include_once "almacen/almacen_consumo_mercancia_view.php"; ?>
			<?php include_once "almacen/almacen_recepcion_view.php"; ?>

			<!-- Orden Producción -->
			<?php include_once "ordenProduccion/ordenProduccion_editor_view.php"; ?>
			<?php include_once "ordenProduccion/ordenProduccion_placas_editor_view.php"; ?>
			<?php include_once "ordenProduccion/ordenProduccion_hologramas_editor_view.php"; ?>
			<?php include_once "ordenProduccion/ordenProduccion_nux_editor_view.php"; ?>
			<?php include_once "ordenProduccion/ordenProduccion_tracker_view.php"; ?>
			<?php include_once "ordenProduccion/consulta_ordenProduccion_view.php"; ?>
			<?php include_once "ordenProduccion/consulta_ordenProduccion_seguimiento_view.php"; ?><!--MCHAVIRA-->
			<?php include_once "ordenProduccion/consulta_ordenProduccion_grupos_view.php"; ?><!--MCHAVIRA-->
			<?php include_once "ordenProduccion/consulta_ordenProduccion_proyectos_view.php" ?><!--MCHAVIRA-->
			<?php include_once "ordenProduccion/ordenProduccion_cierreOP_view.php" ?><!--MCHAVIRA-->
			<?php include_once "ordenProduccion/calendario_general_view.php" ?><!--MChavira-->
			<?php include_once "ordenProduccion/ordenProduccion_movimientoCargaMultiple_view.php"; ?>

			<!-- Calidad -->
			<?php include_once "calidad/calidad_seguimiento_view.php"; ?>
			<?php include_once "calidad/calidad_historial_view.php"; ?>

			<!-- Catálogos -->
			<?php include_once "catalogos/Almacen/catalogo_almacen_almacenes_view.php"; ?>
			<?php include_once "catalogos/Almacen/catalogo_almacen_articulos_view.php"; ?>
			<?php include_once "catalogos/Almacen/catalogo_almacen_materiales_nux_view.php"; ?>
			<?php include_once "catalogos/Almacen/catalogo_almacen_material_nux_view.php"; ?>
			<?php include_once "catalogos/Almacen/catalogo_almacen_ubicaciones_view.php"; ?>
			<?php include_once "catalogos/Almacen/catalogo_almacen_umedida_view.php"; ?>
			<?php include_once "catalogos/Almacen/catalogo_almacen_categoriasArticulo_view.php"; ?>
			<?php include_once "catalogos/Almacen/catalogo_almacen_maquinas_view.php"; ?>
			<?php include_once "catalogos/Almacen/catalogo_almacen_tipoTinta_view.php"; ?>
			<?php include_once "catalogos/Almacen/catalogo_almacen_tipoAlmacen_view.php"; ?>
			<?php include_once "catalogos/Almacen/catalogo_almacen_tipoUbicaciones_view.php"; ?>
			<?php include_once "catalogos/Contabilidad/catalogo_contabilidad_formatosCliente_view.php"; ?>
			<?php include_once "catalogos/Contabilidad/catalogo_contabilidad_clientes_view.php"; ?>
			<?php include_once "catalogos/Contabilidad/catalogo_contabilidad_ciudades_view.php" ?> <!--MCHAVIRA-->
			<?php include_once "catalogos/Contabilidad/catalogo_contabilidad_estados_view.php" ?> <!--MCHAVIRA-->
			<?php include_once "catalogos/Contabilidad/catalogo_contabilidad_paises_view.php" ?> <!--MCHAVIRA-->
			<?php include_once "catalogos/Compras/catalogo_compras_proveedores_view.php"; ?>
			<?php include_once "catalogos/Sistemas/catalogo_sistemas_usuarios_view.php"; ?>
			<?php include_once "catalogos/Sistemas/catalogo_sistemas_roles_view.php"; ?> <!--MCHAVIRA-->


			<!-- Compras -->
			<?php include_once "compras/compras_ordenCompra_view.php"; ?>
			<?php include_once "compras/compras_requisiciones_view.php"; ?>
			<?php include_once "compras/compras_requisicion_view.php"; ?>
			<?php include_once "compras/compras_ordenesCompra_view.php"; ?>
			<?php include_once "compras/compras_recepcion_ordenesCompra_view.php"; ?>
			<?php include_once "compras/compras_recepcion_ordenCompra_view.php"; ?>
			<?php include_once "compras/compras_bitacora_view.php"; ?>
			<?php include_once "compras/compras_seguimiento_view.php"; ?>
			<?php include_once "compras/compras_articulos_view.php"; ?>

			<!-- Contabilidad -->
			<?php include_once "contabilidad/contabilidad_ordenescompra_view.php"; ?>
			<?php include_once "contabilidad/contabilidad_liberarOC_view.php"; ?>
			<?php include_once "contabilidad/contabilidad_liberarReq_view.php"; ?>
			<?php include_once "contabilidad/contabilidad_constancias_proveedores_view.php"; ?>

			<!-- Diseño -->
			<?php include_once "diseno/diseno_seguimiento_view.php"; ?><!--MCHAVIRA-->

			<!-- Dashboard -->
			<?php include_once "dashboard/dashboard_ordenes_activas_view.php"; ?>

			<!-- Logistica -->
			<?php include_once "logistica/logistica_remisiones_view.php"; ?>
			<?php include_once "logistica/logistica_remision_view.php"; ?>
			<?php include_once "logistica/logistica_calendario_view.php"; ?>

			<!-- Mantenimiento -->
			<?php include_once "mantenimiento/mantenimiento_tickets_view.php"; ?>
			<?php include_once "mantenimiento/mantenimiento_ticket_view.php"; ?>

			<!-- Producción -->
			<?php include_once "produccion/produccion_programacion_view.php"; ?>
			<?php include_once "produccion/movcarga_equipos_view.php"; ?>
			<?php include_once "produccion/produccion_programacion_nux_view.php"; ?>

			<!-- SOFTWARE -->
			<?php include_once "software/software_tickets_view.php"; ?>
			<?php include_once "software/software_usuarios_view.php"; ?>

			<!-- TI -->
			<?php include_once "TI/ti_tickets_view.php"; ?>

			<!-- Ventas -->
			<?php include_once "ventas/ventas_cotizaciones_view.php"; ?>
			<?php include_once "ventas/ventas_cotizacion_view.php"; ?>
			<?php include_once "ventas/ventas_diseños_view.php"; ?>

			<!-- Visor de OP -->
			<?php include_once "op-viewer.php"; ?>


			<!-- footer start-->
			<footer class="footer">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-6 footer-copyright">
							<p class="mb-0">Copyright <?php echo date("Y"); ?> © Intelligent Forms All rights reserved.</p>
						</div>
						<div class="col-md-6">
							<p class="pull-right mb-0">NÜX Software v<?php

																		// instanciamos database
																		$db = new Database();
																		$db->connectDB();

																		// obtenemos versión
																		$sql = "SELECT f_getCurrentVersion() as strVersion";
																		$row = $db->getValuesDB($sql);
																		$db->closeDB();

																		if (!$row) echo "Unknown";
																		else echo $row["strVersion"];

																		?></p>
						</div>
					</div>
				</div>
			</footer>
			<?php include_once "ticket/ticket.php"; ?>
		</div>
	</div>


	<!-- Modals -->
	<div class="modal draggable fade" id="request_Notifications" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" style="z-index:999999999;">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content shadow">
				<div class="modal-header">
					<h5 class="modal-title">Activar Notificaciones</h5>
				</div>
				<div class="modal-body">
					Para recibir alertas en tiempo real es necesario activar las notificaciones en este dispositivo.<br>
					Presiona "Permitir" cuando lo solicite ...
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary">Continuar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="confirmBox" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" style="z-index:999999999;">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content shadow">
				<div class="modal-header">
					<h5 class="modal-title"></h5>
				</div>
				<div id="confirm-image" class="rounded-circle img-150 modal-img  confirm-image" style="display:none; margin:auto"></div>
				<div class="modal-body"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Si</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal draggable fade" id="confirmMessageBox" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" style="z-index:999999999;">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content shadow" style="align-items: center;">
				<lottie-player src="../assets/images/SofiMessageAnimation.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></lottie-player>
				<div class="modal-header" style="border-bottom: none;">
					<h5 class="modal-title"></h5>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer" style="border-top: none;">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="dateBox">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
			<div class="modal-content shadow">
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="formElement" data-ParamName="p1" data-ValueType="dat" data-ControlID="dateInput" data-Required="1" style="width:90%;">
						<p class="formLabel"></p>
						<input type="text" id="dateInput" class="form-control dateInput text IsDate" style="text-align:center;" onKeyDown="if(event.which == 13) $('#dateBox .btn-primary').click()" placeholder="dd/mm/aaaa" />
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="inputBox_Number" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
			<div class="modal-content shadow">
				<div class="modal-body">
					<div class="formElement" data-ParamName="p1" data-ValueType="int" data-ControlID="ibnInput" data-MinValue="0" data-Required="1" style="width:90%;">
						<p class="formLabel"></p>
						<input type="text" id="ibnInput" class="form-control numeric-positive" style="text-align:center;" onKeyDown="if(event.which == 13) $('#inputBox_Number .btn-primary').click()" />
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary">Cancelar</button>
					<button type="button" class="btn btn-primary">Aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="selectBox" style="overflow:hidden; z-index: 1600;" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content shadow">
				<div class="modal-body">
					<div class="formElement" data-ParamName="p1" data-ValueType="int" data-ControlID="SelectOptions" data-Required="1" style="width:90%;">
						<p class="formLabel"></p>
						<select class="form-control SelectOptions" id="SelectOptions"></select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary">Cancelar</button>
					<button type="button" class="btn btn-primary">Aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="selectBoxAlmacenUbic" style="overflow:hidden;" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
			<div class="modal-content shadow">
				<div class="modal-header">
					<h5 class="modal-title">Ubicación</h5>
					<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
				</div>
				<div class="modal-body">
					<div class="formElement" data-ParamName="intAlmacen" data-getSelect2Text="strAlmacen" data-IsClassID="1" data-ControlID="selectAlmacen" data-ValueType="int" data-MinValue="0" data-Required="1">
						<p class="formLabel">Editar almacén</p>
						<select class="form-control AjaxSelect selectAlmacen dontInit"></select>
					</div>
					<div class="formElement" data-ParamName="intUbicacion" data-getSelect2Text="strUbicacion" style="margin-top: 20px;" data-IsClassID="1" data-ControlID="selectUbic" data-ValueType="int" data-MinValue="0" data-Required="1">
						<p class="formLabel">Editar ubicación</p>
						<select class="form-control AjaxSelect selectUbic dontInit"></select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary">Cancelar</button>
					<button type="button" class="btn btn-primary">Aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="selectBoxOperacionesOP" style="overflow:hidden;" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
			<div class="modal-content shadow">
				<div class="modal-header">
					<h5 class="modal-title">Aprobar diseño</h5>
					<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
				</div>
				<div class="modal-body">
					<div style="width:250px;" class="formElement" data-ParamName="intOperacionOP" data-getSelect2Text="strOperacionOP" data-ControlID="selectOperacionOP" data-ValueType="int" data-MinValue="0" data-Required="1">
						<p class="formLabel">Seleccionar operación</p>
						<select class="form-control AjaxSelect" id="selectOperacionOP">
							<option value="2">Producción</option>
							<option value="3">Pruebas</option>
							<option value="4">Cerrar</option>
						</select>
					</div>
					<div style="width:250px;" class="formElement" id="toggleTipoOperacionOP" data-ParamName="intTipoOperacionOP" data-getSelect2Text="strTipoOperacionOP" style="margin-top: 20px;" data-ControlID="selectTipoOperacionOP" data-ValueType="int" data-MinValue="0" data-Required="1">
						<p class="formLabel">Seleccionar tipo de operación</p>
						<select class="form-control AjaxSelect" id="selectTipoOperacionOP">
							<option value="1">Nueva</option>
							<option value="3">Cambios</option>
						</select>
					</div>
					<div style="width:250px;" class="formElement" data-ParamName="dblCantidad" style="margin-top: 20px;" data-ControlID="newCantOP" data-ValueType="dbl" data-MinValue="0.0001" data-Required="1">
						<p class="formLabel">Cantidad nueva</p>
						<input type="text" id="newCantOP" class="form-control" style="text-align:center;" />
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary">Cancelar</button>
					<button type="button" class="btn btn-primary">Aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="selectBoxTransferencia" style="overflow:hidden;" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
			<div class="modal-content shadow">
				<div class="modal-header">
					<h5 class="modal-title"><span class="titleModalHeader"></span></h5>
					<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
				</div>
				<div class="modal-body">
					<div class="formElement" data-inactive>
						<p class="formLabel">Actual almacén</p>
						<input type="text" readonly id="selectTransAlmacenLabel" class="form-control text" data-value="">
					</div>
					<div class="formElement" data-ParamName="intAlmacen" data-IsClassID="1" data-ControlID="selectTransAlmacen" data-ValueType="int" data-MinValue="0" data-Required="1">
						<p class="formLabel">Nuevo almacén</p>
						<select class="form-control AjaxSelect selectTransAlmacen dontInit"></select>
					</div>
					<div class="formElement" style="margin-top: 20px;" data-inactive>
						<p class="formLabel">Actual ubicación</p>
						<input type="text" readonly id="selectTransUbicacionLabel" class="form-control text" data-value="">
					</div>
					<div class="formElement" data-ParamName="intUbicacion" style="margin-top: 20px;" data-IsClassID="1" data-ControlID="selectTransUbicacion" data-ValueType="int" data-MinValue="0" data-Required="1">
						<p class="formLabel">Nueva ubicación</p>
						<select class="form-control AjaxSelect selectTransUbicacion dontInit"></select>
					</div>
					<div class="formElement" style="margin-top: 20px;" data-inactive>
						<p class="formLabel">Cantidad Total</p>
						<input type="text" readonly id="CantidadTotal" class="form-control text">
					</div>
					<div class="formElement" data-ParamName="dblCantidad" style="margin-top: 20px;" data-IsClassID="1" data-ControlID="NewCantidad" data-ValueType="dbl" data-MinValue="0" data-Required="0">
						<p class="formLabel">Cantidad a Transferir</p>
						<input type="text" id="NewCantidad" class="form-control numeric-positive NewCantidad">
					</div>
					<div class="formElement" style="margin-top: 20px;" data-inactive>
						<p class="formLabel">Cantidad total de paquetes</p>
						<input type="text" readonly id="CantTotalPaquetes" class="form-control text" data-value="">
					</div>
					<div class="formElement" data-ParamName="intCantPaquetes" style="margin-top: 20px;" data-IsClassID="1" data-ControlID="NewCantidadPaquetes" data-ValueType="int" data-MinValue="0" data-Required="0">
						<p class="formLabel">Paquetes a transferir</p>
						<input type="text" id="NewCantidadPaquetes" class="form-control numeric-positive NewCantidadPaquetes">
					</div>
				</div>
				<div class="formElement" style="margin-left:140px;">
					<p class="formLabel" style="color:black; " id="TipoEmpaque"></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary">Cancelar</button>
					<button type="button" class="btn btn-primary">Aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="inputBoxChangeData" style="overflow:hidden;" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
			<div class="modal-content shadow">
				<div class="modal-header">
					<h5 class="modal-title"><span class="titleModalHeader"></span></h5>
					<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
				</div>
				<div class="modal-body">
					<div class="formElement" style="margin-top: 20px;" data-inactive>
						<p class="formLabel">Cantidad Total</p>
						<input type="text" readonly id="CantidadTot" class="form-control text">
					</div>
					<div class="formElement" data-ParamName="dblCantidad" style="margin-top: 20px;" data-IsClassID="1" data-ControlID="CantidadSalida" data-ValueType="dbl" data-MinValue="0" data-Required="0">
						<p class="formLabel">Cantidad a Salir</p>
						<input type="text" id="CantidadSalida" class="numeric-positive form-control CantidadSalida">
					</div>
					<div class="formElement" style="margin-top: 20px; width:130px;" data-inactive>
						<p class="formLabel">Piezas</p>
						<input type="text" readonly id="intPiezasSalida" class="form-control text">
					</div>
					<div class="formElement" style="margin-top: 20px; width:130px;" data-inactive>
						<p class="formLabel">Kilos</p>
						<input type="text" readonly id="dblKilosSalida" class="form-control text">
					</div>
					<div class="formElement" style="margin-top: 20px; width:130px;" data-inactive>
						<p class="formLabel">Revoluciones</p>
						<input type="text" readonly id="intRevSalida" class="form-control text">
					</div>
					<div class="formElement" style="margin-top: 20px;" data-inactive>
						<p class="formLabel">Paquetes Totales</p>
						<input type="text" readonly id="intCantPaquetesTot" class="form-control text">
					</div>
					<div class="formElement" data-ParamName="intCantPaquetes" style="margin-top: 20px;" data-IsClassID="1" data-ControlID="intCantPaquetesSalida" data-ValueType="int" data-MinValue="0" data-Required="0">
						<p class="formLabel">Paquetes a Salir</p>
						<input type="text" id="intCantPaquetesSalida" class="numeric-positive form-control intCantPaquetesSalida">
					</div>
					<div class="formElement" style="width: 89%;" data-ParamName="strObservaciones" data-ValueType="str" data-ControlID="ObservacionesSalida" data-Required="1">
						<label class="formLabel">Observaciones:</label>
						<textarea class="form-control ObservacionesSalida" id="ObservacionesSalida"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary">Cancelar</button>
					<button type="button" class="btn btn-primary">Aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="textAreaBox" style="overflow:hidden; z-index: 1600;" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content shadow">
				<div class="modal-body">
					<div class="formElement" data-ParamName="p1" data-ValueType="str" data-ControlID="ibnTextArea" data-MinValue="" data-Required="1" style="width:96%;">
						<p class="formLabel"></p>
						<textarea name="commentBox" class="form-control" id="ibnTextArea" cols="10" rows="10"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary">Cancelar</button>
					<button type="button" class="btn btn-primary">Aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="textBox" style="overflow:hidden; z-index: 1600;" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content shadow">
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="formElement" style="width:96%;">
						<p class="textBox" style="word-wrap: break-word;"></p>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary">OK</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="firmaBox" style="overflow:hidden;" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content shadow">
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="formElement" data-ParamName="intUserID" data-ValueType="int" data-ControlID="firmaUserID" style="width:90%;">
						<label for="etAlmacen">Nombre:</label>
						<select class="form-select dontInit" id="firmaUserID">
							<?php

							$query = "SELECT intUserID, concat(strNombreCompleto, ' - ', strUserRole, ' - ', strDepartamento) FROM view_users WHERE bytEstatus != 9 ORDER BY strNombreCompleto";

							$db = new Database();
							echo $db->get_SelectOptions($query);
							?>
						</select>
					</div>
					<div class="formElement" data-ParamName="strFirma" data-ValueType="str" data-ControlID="strFirma" style="width:90%;">
						<p class="formLabel">Firma:</p>
						<input type="password" class="form-control" id="strFirma" />
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary">Cancelar</button>
					<button type="button" class="btn btn-primary">Aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="DocumentFileBox" style="cursor:pointer" style="z-index: 1600;" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-md">
			<div class="modal-content shadow" style="height:52%;">
				<div class="modal-body">
					<div class="formElement" data-ParamName="p1" data-ValueType="str" data-ControlID="ibnTextArea" data-MinValue="" data-Required="0" style="width: 100%;height: 100%;">
						<div class="dropzone dropzone-primary" method='POST' enctype='multipart/form-data' id="multiFileUpload" style="height:85%;">
							<div id="dz-messageneedsclick" style="text-align:center;font-size: 15px;margin: 2px 0;">
								<div id="messageModal">
									<i data-feather="upload" style="color:#666666;"></i>
									<!-- <i class="icon-cloud-up" style="font-size:29px;"></i> -->
									<br>
									<span class="noteNeedsclick">Arrastre los archivos aquí o haga clic para cargar.</span>
								</div>
								<input type="file" name="arrInputFile" id="arrInputFile" onchange="previewFileDoc();" hidden accept=".pdf" name="arrInputFile">
							</div>
						</div>
					</div>
				</div>
				<div class="item doc" id="doc0" data-notas="" data-tipo="" data-name="" data-size="" data-path="" style="display:none; margin: auto; border: 1px solid #ced4da; width: 454px; margin-bottom:10px; border-radius:5px;">
					<div class="itemField formElement" style="width: 60px; height:60px; margin-bottom:0px; height:60px; background-color:rgba(226, 224, 224, 0.6); border-top-right-radius: 0px !important; border-bottom-right-radius: 0px !important;">
						<div class="itemField_Data pdf-icon-svg"></div>
					</div>
					<div class="itemField formElement link" onclick="showDoc(this);" style="width:330px; margin-bottom: 0; border-right:0;">
						<div class="contenido" style="text-align: left; width: 100%;">
							<div class="itemField_Data data-center" id="articuloDocName" style="text-align: left; font-size:14px; padding-bottom: 0; ">Procesando..</div>
							<div class="itemField_Data data-center" id="articuloDocSize" style="text-align: left; padding-top:5px; color:rgb(68 66 66 / 73%); ">Procesando..</div>
						</div>
					</div>
					<div class="itemField formElement link" id="removeDoc" onclick="delete_Doc(this);" style="padding-left: 0px !important; margin-bottom:0; height:60px;">
						<i data-feather="x" style="margin-bottom:0; cursor: pointer; color:#c92018;"></i>
					</div>
				</div>

				<div id="docEntradasAlmacen" class="docEntradasAlmacen" style="margin-bottom:5px; display: flow-root; display:none;"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary">Cancelar</button>
					<button type="button" class="btn btn-primary">Aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="docsBox" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content shadow" style="height:60vh;">
				<div class="modal-header">
					<h5 class="modal-title">Documentos:</h5>
				</div>
				<div class="modal-body">
					<div id="wrapperDocsGenerico" class="wrapperDocsGenerico" style="margin-bottom:5px; display: flow-root; overflow-y: auto;"></div>
					<div id="docsBoxInfo" class="w-100 text-center"></div>
				</div>
				<div class="modal-footer" style="flex-direction:row; justify-content: space-between;">
					<button type="button" class="btn btn-primary cargaArchivoDocBox">Cargar archivos</button>

					<div id="tiposArchivosMul">
						<button type="button" class="btn btn-primary tipoArchivoMul" data-buttonValueType="PDF"><i data-feather="plus" style="vertical-align: text-top; width:20px; height:15px;"></i>PDF</button>
						<button type="button" class="btn btn-primary tipoArchivoMul" data-buttonValueType="JPG"><i data-feather="plus" style="vertical-align: text-top; width:20px; height:15px;"></i>JPG</button>
						<button type="button" class="btn btn-primary tipoArchivoMul" data-buttonValueType="PNG"><i data-feather="plus" style="vertical-align: text-top; width:20px; height:15px;"></i>PNG</button>
						<button type="button" class="btn btn-primary tipoArchivoMul" data-buttonValueType="XLSX"><i data-feather="plus" style="vertical-align: text-top; width:20px; height:15px;"></i>Excel</button>
					</div>
					<button type="button" class="btn btn-secondary">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="imageBox" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content shadow" style="height:60vh;">
				<div class="modal-header">
					<h5 class="modal-title">Imagen de perfil:</h5>
				</div>
				<div class="modal-body" style="align-self: center; text-align-last: center;">
					<img class="rounded-circle img-150 modal-img  confirm-image" id="imageBoxInfo" style="margin: 10px;" src="">
					<br>
					<span id="imageBoxText"></span>
					<div id="docsBoxInfo" class="w-100 text-center"></div>
				</div>
				<div class="modal-footer" style="flex-direction:row; justify-content: space-between;">
					<div>
						<button type="button" class="btn btn-primary cargaArchivoDocBox" data-buttonValueType="png">PNG</button>
						<button type="button" class="btn btn-primary cargaArchivoDocBox" data-buttonValueType="jpg">JPG</button>
					</div>
					<button type="button" class="btn btn-secondary">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="bitacoraBox" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content shadow" style="height:60vh;">
				<div class="modal-header">
					<h5 class="modal-title">Bitácora</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body" style="overflow-y: auto; background: #f9f8f8;">
					<div id="wrapperBitacoraGenerico" class="wrapperBitacoraGenerico"></div>
					<div id="bitacoraBoxInfo" class="w-100 text-center"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" style="margin-left:66px;">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="ordenCompraBox" style="overflow:hidden;" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content shadow">
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="formElement" data-ParamName="intOrdenCompra" data-ValueType="int" data-ControlID="ocbOC" style="width:90%;" data-Required="1">
						<label for="ocbOC">Selecciona la orden de compra:</label>
						<select id="ocbOC" class="AjaxSelect" style="width:100%;" data-placeholder=""></select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary">Cancelar</button>
					<button type="button" class="btn btn-primary">Aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal draggable fade" id="selectOP" style="overflow:hidden;" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" style="width:560px;">
			<div class="modal-content shadow">
				<div class="modal-header">
					<h5 class="modal-title"><span class="titleModalHeader"></span></h5>
				</div>
				<div class="modal-body">
					<div class="formElement" data-ParamName="intOrdenProd" data-ValueType="int" data-ControlID="sopOrden" data-Required="1" style="width:510px;">
						<p class="formLabel"></p>
						<select id="sopOrden" class="AjaxSelect form-control" style="width:100%;" data-placeholder="Selecciona una orden"></select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary">Cancelar</button>
					<button type="button" class="btn btn-primary">Aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade loadingCover" id="loadCover" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ">
			<div class="modal-content">
				<div class="modal-body" style="text-align:center; margin-top:10px;">
					<div class="loader-circle-11" style="display: inline-block;">
						<div class="arc"></div>
						<div class="arc"></div>
						<div class="arc"></div>
					</div>
					<div class="msg">Procesando ...</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-backdrop" id="loadCover2" style="display: none; z-index:9997;">
		<div class="lds-ellipsis">
			<div></div>
			<div></div>
			<div></div>
			<div></div>
		</div>
		<div class="msg"></div>
	</div>
	<!--Modal de bitacora OP Generico-->
	<div class="modal draggable fade" id="confirmEditorBitacora" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" style="width:620px; max-width:none; margin:auto;">
			<div class="modal-content" style="position:relative; height:auto;">
				<div class="modal-header">
					<h5 class="modal-title">¿Desea guardar los siguientes cambios?</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="accordion accordion-flush" style="margin-top:20px; margin-bottom:20px;">
					<div class="accordion-item" style="position: relative;">
						<div class="accordion-header" id="headingOne_PP">
							<button class="accordion-button collapsed" type="button" data-bs-target="#confirmEditorBitacora_Body" data-bs-toggle="collapse" aria-expanded="false" aria-controls="confirmEditorBitacora_Body">Cambios Realizados:</button>
						</div>
						<div id="confirmEditorBitacora_Body" class="accordion-collapse collapse" style="border:0px;">
							<div class="accordion-body">
								<div id="wrapper_confirmEditorBitacora" style="margin-left:1px; height:auto;">
									<div id="itemEditBitacora" class="accordion-item" style="display: none;">
										<p class="accordion-header">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseItem1" aria-expanded="false" aria-controls="collapseItem1">
												<i class="icofont"></i>
												<div class="header">
													<div class="title"></div>
												</div>
											</button>
										</p>
										<div id="collapseItem1" class="accordion-collapse collapse">
											<div class="accordion-body">
												<div class="comments"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>					
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
					<button type="button" id="saveEditorBitacora" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal draggable fade" id="bitacoraOPBox" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content shadow" style="height:60vh;">
				<div class="modal-header">
					<h5 class="modal-title">Bitácora</h5>
					<div class="updatedTag" style="margin-left: 30px;"><span class="loading"></span><span class="title" style="display:none;"></span></div>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body" style="overflow-y: auto; background: #f9f8f8;">
					<div id="wrapperBitacoraOP" class="bitacoraWrapper"></div>

					<div id="itemBitacoraOP01" class="bitacoraRow" style="display: none;">
						<div class="icon"><i></i></div>
						<div class="content">
							<div class="header">
								<div class="action"></div>
								<div class="time"></div>
							</div>
							<div class="comments"></div>
							<div class="footer">
								<div class="user"></div>
								<div class="date"></div>
							</div>
						</div>
					</div>
					<div id="itemBitacoraOP02" class="bitacoraRow rowType2" style="display: none;">
						<div class="header">
							<div class="icon"><i></i></div>
							<div class="header">
								<div class="action"></div>
								<div class="title"></div>
								<div class="time"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<button type="button" class="btn btn-primary add-registro">Agregar Registro</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="scrollTop" id="scrollTop" onclick="scrollToTop();" style="display:none;"><i class="fa fa-chevron-up"></i></div>

	<!--MCHAVIRA-->
	<?php include_once('ordenProduccion/ordenProduccion_movimientoCarga_view.php'); ?>
	<?php include_once('ordenProduccionModal.php'); ?>
	<?php include_once('contabilidad/contabilidad_tipoCambio_modal.php'); ?>
	<!--MCHAVIRA-->

	<?php include_once('ordenProduccion/ordenProduccion_secProcesos_editor_view.php'); ?>

	<!-- Upload Files -->
	<?php include_once "uploadFiles.php"; ?>

	<!-- Modal end -->

	<!-- Root element of PhotoSwipe. Must have class pswp. -->
	<div class="pswp" style="z-index: 999999;" tabindex="-1" role="dialog" aria-hidden="true">

		<!-- Background of PhotoSwipe. It's a separate element as animating opacity is faster than rgba(). -->
		<div class="pswp__bg"></div>

		<!-- Slides wrapper with overflow:hidden. -->
		<div class="pswp__scroll-wrap">

			<!-- Container that holds slides. 
				PhotoSwipe keeps only 3 of them in the DOM to save memory.
				Don't modify these 3 pswp__item elements, data is added later on. -->
			<div class="pswp__container">
				<div class="pswp__item"></div>
				<div class="pswp__item"></div>
				<div class="pswp__item"></div>
			</div>

			<!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
			<div class="pswp__ui pswp__ui--hidden">

				<div class="pswp__top-bar">

					<!--  Controls are self-explanatory. Order can be changed. -->

					<div class="pswp__counter"></div>

					<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

					<!-- <button class="pswp__button pswp__button--share" title="Share"></button> -->

					<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

					<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

					<!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
					<!-- element will get class pswp__preloader--active when preloader is running -->
					<div class="pswp__preloader">
						<div class="pswp__preloader__icn">
							<div class="pswp__preloader__cut">
								<div class="pswp__preloader__donut"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
					<div class="pswp__share-tooltip"></div>
				</div>

				<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
				</button>

				<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
				</button>

				<div class="pswp__caption">
					<div class="pswp__caption__center"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Loader -->
	<script type="text/javascript" src="../assets/js/ua-parser.min.js"></script>

	<!-- Bootstrap js-->
	<script type="text/javascript" src="../assets/js/bootstrap/popper.min.js"></script>
	<script type="text/javascript" src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
	<!-- feather icon js-->
	<script type="text/javascript" src="../assets/js/icons/feather-icon/feather.min.js"></script>
	<script type="text/javascript" src="../assets/js/icons/feather-icon/feather-icon.js"></script>
	<!-- Sidebar jquery-->
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/sidebar-menu.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/config.js'); ?>"></script>
	<!-- Datatables start-->
	<script type="text/javascript" src="../assets/js/datatable/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="../assets/js/datatable/datatable.custom.js"></script>
	<script type="text/javascript" src="../assets/js/datatable/dataTables.bootstrap5.min.js"></script>
	<script type="text/javascript" src="../assets/js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="../assets/js/datatable/datatable-extension/buttons.bootstrap5.min.js"></script>
	<script type="text/javascript" src="../assets/js/datatable/datatable-extension/buttons.html5.min.js"></script>
	<script type="text/javascript" src="../assets/js/datatable/datatable-extension/dataTables.fixedHeader.js"></script>
	<script type="text/javascript" src="../assets/js/datatable/datatable-extension/dataTables.select.min.js"></script>
	<script type="text/javascript" src="../assets/js/datatable/datatable-extension/dataTables.responsive.min.js"></script>
	<script type="text/javascript" src="../assets/js/datatable/datatable-extension/responsive.bootstrap5.min.js"></script>
	<script type="text/javascript" src="../assets/js/datatable/datatable-extension/jquery.dataTables.colResize.js"></script>
	<script type="text/javascript" src="../assets/js/datatable/datatable-extension/dataTables.rowGroup.min.js"></script>
	<script type="text/javascript" src="../assets/js/datatable/datatable-extension/jszip.min.js"></script>
	<script type="text/javascript" src="../assets/js/datatable/datatable-extension/pdfmake.min.js"></script>
	<script type="text/javascript" src="../assets/js/datatable/datatable-extension/vfs_fonts.js"></script>
	<!-- Plugins JS start-->
	<script type="text/javascript" src="../assets/js/clipboard/clipboard.min.js"></script>
	<script type="text/javascript" src="../assets/js/counter/jquery.waypoints.min.js"></script>
	<script type="text/javascript" src="../assets/js/counter/jquery.counterup.min.js"></script>
	<script type="text/javascript" src="../assets/js/counter/counter-custom.js"></script>
	<script type="text/javascript" src="../assets/js/custom-card/custom-card.js"></script>
	<script type="text/javascript" src="../assets/js/notify/bootstrap-notify.min.js"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/notify/index.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/chat-menu.js'); ?>"></script>
	<script type="text/javascript" src="../assets/js/height-equal.js"></script>
	<script type="text/javascript" src="../assets/js/popover-custom.js"></script>
	<script type="text/javascript" src="../assets/js/tooltip-init.js"></script>
	<script type="text/javascript" src="../assets/js/jquery.numeric.js"></script>
	<script type="text/javascript" src="../assets/js/sweetalert/sweetalert.min.js"></script>
	<script type="text/javascript" src="../assets/js/tasktimer.min.js"></script>
	<script type="text/javascript" src="../assets/js/photoswipe.min.js"></script>
	<script type="text/javascript" src="../assets/js/photoswipe-ui-default.min.js"></script>
	<script type="text/javascript" src="../assets/js/fullcalendar/dist/index.global.js"></script><!--Plugin de calendario de logistica-->
	<script type="text/javascript" src="../assets/js/fullcalendar/packages/core/locales-all.global.js"></script><!--Arregla el problema de idioma en los botones del calendario de logistica-->
	<script type="text/javascript" src="../assets/js/table2excel/jquery.table2excel.min.js"></script>
	<script type="text/javascript" src="../assets/js/chart.js"></script>

	<script type="text/javascript" src="../assets/js/autosize.min.js"></script>
	<script type="text/javascript" src="../assets/js/date-es-MX.js"></script>
	<script type="text/javascript" src="../assets/js/date-picker/datepicker.js"></script>
	<script type="text/javascript" src="../assets/js/jquery.mask.min.js"></script>
	<!-- Plugins JS Ends-->
	<!-- Theme js-->
	<script type="text/javascript" src="../assets/js/script.js"></script>
	<script type="text/javascript" src="../assets/js/theme-customizer/customizer.js"></script>
	<!-- Models JS-->
	<script type="text/javascript" src="../<?php $generals->latest_version('models/model_Generales.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/messages/messages_client_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/ws/ws_client_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/worker/worker_progressUpdate.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/notifications/notifications_manager_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/almacen/almacen_entrada_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/almacen/almacen_vale_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/contabilidad/contabilidad_Tipo_Cambio_model.js'); ?>"></script><!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('models/contabilidad/contabilidad_clientes_model.js'); ?>"></script><!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('models/ordenProduccion/ordenProduccion_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/ordenProduccion/ordenProduccion_Proyecto_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/ordenProduccion/ordenProduccion_Grupo_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/templateSP/templateSP_model.js'); ?>"></script><!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('models/logistica/logistica_remisiones_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/almacen/almacen_inventario_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/almacen/almacen_constants_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/compras/compras_ordenCompra_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/compras/compras_requisicion_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/compras/compras_proveedor_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/tickets/tickets_ticket_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/users/users_user_role_model.js') ?>"></script> <!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('models/users/users_user_role_modulos_model.js') ?>"></script><!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('models/users/users_user_role_modulos_opciones_model.js') ?>"></script><!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('models/users/users_user_role_modulos_opciones_acciones_model.js') ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/users/users_user_role_tipos_orden_model.js'); ?>"></script><!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('models/users/users_user_role_notificaciones_model.js'); ?>"></script><!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('models/catalogos/catalogos_departamentos_subdepartamento_model.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('models/ventas/ventas_cotizaciones_model.js'); ?>"></script>
	<!-- Components-->
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/generales.js'); ?>"></script> <!-- Debe ser el primero en cargarse -->
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/system_events.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/notifications.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/uploadFiles.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/op_viewer.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/tickets.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/almacen_entrada.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/almacen_entrada_semiTerminado.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/almacen_entrada_terminado.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/almacen_vale.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/almacen_vales.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/almacen_inventario.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/almacen_recepcion.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/ordenProduccion_editor.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/ordenProduccion_placas_editor.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/ordenProduccion_hologramas_editor.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/ordenProduccion_nux_editor.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/ordenProduccion_tracker.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/ordenProduccion_movimientoCarga.js') ?>"></script><!--Mchavira-->
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/ordenProduccion_movimientoCargaMultiple.js') ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/ordenProduccion_secProcesos_editor.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/ordenProduccion_cierreOP.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/modal_ordenProduccion_viewer.js'); ?>"></script><!--Mchavira-->
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/almacen_bitacora.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/almacen_consumo_mercancia.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/calidad_seguimiento.js'); ?>"></script><!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/calidad_historial.js'); ?>"></script><!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/consultaOrdenProduccion.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/consultaOrdenProduccion_seguimiento.js'); ?>"></script> <!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/consultaOrdenProduccion_grupos.js'); ?>"></script> <!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/consultaOrdenProduccion_proyectos.js'); ?>"></script> <!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_almacen_almacenes.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_almacen_articulos.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_almacen_materiales_nux.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_almacen_material_nux.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_almacen_ubicaciones.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_almacen_umedida.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_almacen_categoriasArticulo.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_almacen_maquinas.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_almacen_tipoTinta.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_almacen_tipoAlmacen.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_almacen_tipoUbicacion.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_contabilidad_formatosCliente.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_contabilidad_ciudades.js') ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_contabilidad_clientes.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_contabilidad_estados.js') ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_contabilidad_paises.js') ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_compras_proveedores.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_sistemas_usuarios.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/catalogo_sistemas_roles.js'); ?>"></script> <!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/compras_ordenesCompra.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/compras_recepcion_ordenesCompra.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/compras_recepcion_ordenCompra.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/compras_ordenCompra.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/compras_requisicion.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/compras_bitacora.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/compras_seguimiento.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/compras_articulos.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/contabilidad_ordenesCompra.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/contabilidad_liberarOC.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/contabilidad_liberarReq.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/contabilidad_consultaConstancias.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/contabilidad_tipoCambio.js'); ?>"></script><!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/diseno_seguimiento.js'); ?>"></script><!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/dashboard_ordenes_activas.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/logistica_remisiones.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/logistica_remision.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/logistica_calendario.js'); ?>"></script><!--MCHAVIRA-->
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/calendario_general.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/software_tickets.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/software_usuarios.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/ventas_diseños.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/produccion_programacion.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/movcarga_equipos.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/produccion_programacion_nux.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/ventas_cotizaciones.js'); ?>"></script>
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/ventas_cotizacion.js'); ?>"></script>
	
	<script type="text/javascript" src="../<?php $generals->latest_version('assets/js/main_loader.js'); ?>"></script>


	<script type="text/javascript">
		// monitoreo de sesión
		sessionMonitoring();

		<?php
		// cargamos variables
		if (isset($_SESSION['userName'])) {
			echo "strUserName = '" . $_SESSION['userName'] . "';";
		} else {
			echo "strUserName = '';";
		}

		// cargamos variables
		if (isset($_SESSION['strNombreCompleto'])) {
			echo "strUserFullName = '" . $_SESSION['strNombreCompleto'] . "';";
		} else {
			echo "strUserFullName = '';";
		}

		if (isset($_SESSION['token_id'])) {
			echo "strTokenID = '" . $_SESSION['token_id'] . "';";
		} else {
			echo "strTokenID = '';";
		}

		if (isset($_SESSION['token_value'])) {
			echo "strTokenValue = '" . $_SESSION['token_value'] . "';";
		} else {
			echo "strTokenValue = '';";
		}

		if (isset($_SESSION['strUserDepto'])) {
			echo "strUserDepto = '" . $_SESSION['strUserDepto'] . "';";
		} else {
			echo "strUserDepto = '';";
		}

		if (isset($_SESSION['strUserSubDepto'])) {
			echo "strUserSubDepto = '" . $_SESSION['strUserSubDepto'] . "';";
		} else {
			echo "strUserSubDepto = '';";
		}

		if (isset($_SESSION['strUserRole'])) {
			echo "strUserRole = '" . $_SESSION['strUserRole'] . "';";
		} else {
			echo "strUserRole = '';";
		}

		if (isset($_SESSION['sesionId'])) {
			echo "sesionId = '" . $_SESSION['sesionId'] . "';";
		} else {
			echo "sesionId = '';";
		}

		// mostramos Dashboard si es Dirección General
		if (isset($_SESSION['intUserRole'])) {
			if ($_SESSION['intUserRole'] == 6) echo "showDashboard_OPsActivas();";
		}
		?>
	</script>

</body>

</html>
