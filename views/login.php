<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administraci&oacute;n :: Clinica</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
	<link rel="shortcut icon" href="<?php echo BASE_DIR ?>favicon.ico" type="image/x-icon" >
	<link href="<?php echo BASE_DIR ?>css/font-awesome.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo BASE_DIR ?>css/style.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo BASE_DIR ?>css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo BASE_DIR ?>css/theme.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo BASE_DIR ?>css/login.css" rel="stylesheet" type="text/css" />

		<!-------  PWA ------->
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="application-name" content="Sistema">
	<meta name="apple-mobile-web-app-title" content="Sistema">
	<meta name="theme-color" content="#404242">
	<meta name="msapplication-navbutton-color" content="#404242">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="msapplication-starturl" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!------- END PWA ------->
	<link rel="manifest" href="manifest_v_0.2.json">
    <script src="<?php echo BASE_DIR; ?>js/jquery-1.11.1.js"></script>
 
		

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<script>
		$(document).ready(function(){
			$('input').attr('autocomplete','off');
			$('#usuario').focus();

			$("#frmLogin").submit(function() {
				$.ajax({
					type: "POST",
					url: $("#frmLogin").attr('action'),
					data: $("#frmLogin").serialize(),
					beforeSend: function () {
					$("#result").html('<div class="loading"></div>');
					},
						success: function(data) {
						$("#result").html(data);
					}
				});
				return false;
			});

			$("#frm2Login").submit(function() {
				$.ajax({
					type: "POST",
					url: $("#frm2Login").attr('action'),
					data: $("#frm2Login").serialize(),
					beforeSend: function () {
					$("#result2").html('<div class="loading"></div>');
					},
						success: function(data) {
						$("#result2").html(data);
					}
				});
				return false;
			});
		});
	</script>
</head>
<div>
</br>
</div>
<body class="loginbody">
	<div style="position: relative;">
		<div style="background-color: transparent; position: absolute; left: 100px; top: -50px; z-index: 3;">
		<a href="<?php  echo BASE_DIR; ?>">
			<i class="fa fa-user" style="font-size:80px;text-align: center;display: block;color:#3C78BC;padding: 10px;"></i>
		</a>
	</div>
		<div >
			<!-- <h1><i class="fa fa-key"></i> Ingresar al Sistema</h1> -->

			<div>

				<form id="frmLogin" action="">
					<div id="divUsuario">
					</br>
					</br>
				</br>

						<!-- <label>Usuario: </label> -->
						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-user"></i>
							</div>
							<input class="form-control" type="text" id="usuario" name="usuario" placeholder="Su nombre de usuario"/>
						</div>
					</div>
					<button type="submit" id="btnAceptar" class="btn btn-primary"><i class="fa fa-sign-in"></i> ACEPTAR </button>
				</form>
				<form id="frm2Login" action="">
					<input type="hidden" name="usuario" id="usuarioFrm2" value="">
					<div id="result"></div>
					<div id="result2"></div>
				</form>
			</div>
		 </div>
		<p>
			<i class="fa fa-copyright"></i>
			Sistemas Altek <?php echo date("Y"); ?>. Todos los derechos reservados.
		</p>
	</div>
</body>
</html>
