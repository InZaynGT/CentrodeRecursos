<?php
permiso(1);
if (count($_POST) > 0) {
	if (count($_POST) > 3 && isset($_POST['empleado']) && isset($_POST['nick']) && isset($_POST['password'])
			&& isset($_POST['password2'])) {

		$empleado = $_POST['empleado'];
		$nick = trim($_POST['nick']);
		$password = $_POST['password'];
		$password2 = $_POST['password2'];

		// Validar campos vacios
		if ($empleado == '' || $nick == '' || $password == '' || $password2 == '') {
			echo '<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
						<b>Error:</b> Rellene los campos obligatorios (*).
					</div>';
		}
		else {
			// Validar que exista usuario
			require_once 'models/empleadoModel.php';
			$e = new Empleado();
			$idEmpleado = $e->getEmpleadoPorNombre($empleado);
			$e = null;

			if ($idEmpleado == 0) {
				echo '<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
						<b>Error:</b> El empleado seleccionado no existe.
					</div>';
			}
			else {
				// Verificamos que no exista el nick
				require_once 'models/usuarioModel.php';
				$u = new Usuario();
				$nickBD = $u->getUsuario($nick);
			//	$empleadoBD = $u->getEmpleadoPorID($idEmpleado);
				$u = null;

				if (count($nickBD) > 0) {
					echo '<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
						<b>Error:</b> El nick escrito ya existe, ingrese uno nuevo.
					</div>';
				}
				// else if (count($empleadoBD) > 0) {
				// 	echo '<div class="alert alert-danger alert-dismissable">
				// 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
				// 		<b>Error:</b> Este empleado ya tiene una cuenta de usuario.
				// 	</div>';
				// }
				else {
					// Verificamos que los passwords coincidan
					if($password != $password2) {
						echo '<div class="alert alert-danger alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
							<b>Error:</b> Las contrase&ntilde;as no coinciden.
						</div>';
					}
					else {
						$u = new Usuario();
						$insert = $u->setUsuario($nick, $password, $idEmpleado);
						if ($insert == true) {
							echo '<div class="alert alert-success alert-dismissable">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
										<b>OK:</b> Registro almacenado correctamente.
									</div>';
							// sleep(1);
							echo '
								<script>
									$(location).attr("href","'.BASE_DIR.'usuarios");
								</script>
							';
						}
						else {
							echo '<div class="alert alert-danger alert-dismissable">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
									<b>Error:</b> No se pudo almacenar el registro, recarge la p&aacute;gina e int&eacute;ntelo nuevamente.
								</div>';
						}
					}
				}
			}
		}
	}
	else {
		echo 'No se puede realizar ninguna acci&oacute;n';
	}
}
else {
	require_once 'models/usuarioModel.php';
	require_once 'models/empleadoModel.php';
	$u = new Usuario();
	$usuarios = $u->getUsuarios();
	$u = null;
	$e = new Empleado();
	$empleados = $e->getEmpleados();
	$u = null;

	$tags = '';
	foreach ($empleados as $empleado){
		$tags .= '"['.$empleado['ID_EMPLEADO'].'] '.$empleado['NOMBRES'].' '.$empleado['APELLIDOS'].'",';
	}

	require_once 'views/header.php';
	require_once 'views/usuarios.php';
	require_once 'views/footer.php';
}
?>
