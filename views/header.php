<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?php echo $tituloPagina; ?></title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=5" name="viewport" />
	<link rel="shortcut icon" href="<?php echo BASE_DIR ?>clinic.ico" type="image/x-icon">
	<link href="<?php echo BASE_DIR ?>css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo BASE_DIR ?>css/font-awesome.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo BASE_DIR ?>css/theme.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo BASE_DIR ?>css/style.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo BASE_DIR ?>css/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR ?>css/buttons.dataTables.min.css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<script src="<?php echo BASE_DIR; ?>js/jquery-1.11.1.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#example1").dataTable({
				dom: 'Bfrtip',
				buttons: [{
						extend: 'excel',
						footer: true
					},
					{
						extend: 'pdf',
						footer: true,
						orientation: 'landscape',
						pageSize: 'LETTER',
						message: function() {
							return $("#example1_info").text();
						}
					},
					{
						extend: 'print',
						footer: true,
						orientation: 'landscape',
						pageSize: 'LETTER',
						message: function() {
							return $("#example1_info").text();
						}
					}
				]

			});
			$('input').attr('autocomplete', 'off');

			$(".clicmenu").click(function() {
				// alert($(this).attr("data-link"));
				$("html, body").animate({
					scrollTop: $("#" + $(this).attr("data-link")).offset().top
				}, 400);
			});

			$(document).ajaxStart(function() {
				$("input[type=submit]").prop('disabled', true);
				$("input[type=button]").prop('disabled', true);
				$("button").prop('disabled', true);
			});
			$(document).ajaxComplete(function() {
				$("input[type=submit]").prop('disabled', false);
				$("input[type=button]").prop('disabled', false);
				$("button").prop('disabled', false);
			});
		});
	</script>
	<script src="<?php echo BASE_DIR; ?>js/app.js" type="text/javascript"></script>

</head>

<body class="skin-blue">
	
	<header class="header">
		<a href="<?php echo BASE_DIR; ?>" class="logo">
			CENTRO DE RECURSOS
		</a>
		<!--		<nav class="navbar navbar-static-top" role="navigation">-->
		<nav class="navbar navbar-static-top" role="navigation">
			<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<div class="navbar-right">
				<ul class="nav navbar-nav">

					<li class="user user-menu">
						<a href="<?php echo BASE_DIR; ?>inicio">
							<i class="fa fa-user"></i>
							<span><?php echo $_SESSION['user']['username']; ?></span>
						</a>
					</li>
					<li class="user user-menu">
						<a href="<?php echo BASE_DIR; ?>logout">
							<i class="fa fa-sign-out"></i>
							<span>Cerrar Sesi&oacute;n</span>
						</a>
					</li>
				</ul>
			</div>
		</nav>
	</header>
	<div class="wrapper row-offcanvas row-offcanvas-left ">
		<aside class="left-side sidebar-offcanvas">
			<section class="sidebar">
				<div class="user-panel">
					<div class="pull-left image">
						<img src="<?php echo BASE_DIR; ?>img/fundal.jpg" class="img-circle" alt="User Image" />
					</div>
					<div class="pull-left info">
						<p><?php echo $_SESSION['user']['usuario']; ?></p>

						<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
					</div>
				</div>
				<ul class="sidebar-menu">
    <!-- /////////////////////////// ESCRITORIO /////////////////////////////////// -->
    <li>
        <a href="<?php echo BASE_DIR; ?>">
            <i class="fa fa-dashboard"></i> <span>Escritorio</span>
        </a>
    </li>

	<!-- Titulo para Citas -->
    <li>
        <a>
            <span>Citas</span>
        </a>
    </li>

    <!-- Botones para Historiales Clínicos -->
    <li>
        <a href="<?php echo BASE_DIR; ?>citas">
            <i class="fa fa-calendar-check-o"></i> 
            <span>Citas</span>
        </a>
    </li>
    <!-- <li>
        <a target="_blank" href="<?php echo BASE_DIR; ?>reportes/reporte_citas.php">
            <i class="fa fa-book"></i> <span>Generar Lista de Citas</span>
        </a>
    </li> -->

    <!-- Titulo para Historiales Clínicos -->
    <li>
        <a>
            <span>Historiales Clínicos</span>
        </a>
    </li>

    <!-- Botones para Historiales Clínicos -->
    <li>
        <a href="<?php echo BASE_DIR; ?>agregar-paciente">
            <i class="fa fa-user-plus"></i> <span>Crear paciente</span>
        </a>
    </li>
    <li>
        <a href="<?php echo BASE_DIR; ?>pacientes">
            <i class="fa fa-users"></i> <span>Listado de pacientes</span>
        </a>
    </li>

    <!-- Titulo para Consultas -->
    <li>
        <a>
            <span>Consultas</span>
        </a>
    </li>

    <!-- Botones para Consultas -->
    <li>
        <a href="<?php echo BASE_DIR; ?>agregar-consulta">
            <i class="fa fa-stethoscope"></i> <span>Crear consulta</span>
        </a>
    </li>
    <li>
        <a href="<?php echo BASE_DIR; ?>consultas">
            <i class="fa fa-list"></i> <span>Listado de consultas</span>
        </a>
    </li>
</ul>



				</ul>
			</section>
		</aside>