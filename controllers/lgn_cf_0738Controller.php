<?php
	if (count($_POST) > 0) {
		if (count($_POST) == 1 && isset($_POST['usuario'])) {
			$usuario = trim($_POST['usuario']);
			// Validar campos
			if ($usuario == '') {
				echo '
					<div class="alert alert-danger">
						<b>Error:</b> Rellene el campo usuario.
					</div>
				';
			}
			else {
				// Validar usuario
				require_once 'models/usuarioModel.php';
				$u = new Usuario();
				$user = $u->getUsuario($usuario);
				$u = null;

				if (count($user) == 0){
					echo '
						<div class="alert alert-danger">
							<b>Error:</b> El usuario ingresado no existe.
						</div>
					';
				}
				else if($user[0]['ESTADO'] == 0) {
					echo '
						<div class="alert alert-danger">
							<b>Error:</b> Su cuenta est&aacute; desactivada.
						</div>
					';
				}
				else {
					// Mostrar demas campos

					echo '
					</br>
					</br>
						<script>
							$(document).ready(function(){
								$("#frmLogin").hide();
								$("#password").focus();
								$("#usuarioFrm2").val("'.$user[0]['NICK'].'");
							});
						</script>
						<h3 class="text-light-blue">"'.$user[0]['FRASE'].'"</h3>
						';



					echo '	</select>

						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-lock"></i>
							</div>
							<input class="form-control" type="password" id="password" name="password" placeholder="Su contrase&ntilde;a"/>
						</div>
						<button type="submit" id="btnAceptar" class="btn btn-primary">
							<i class="fa fa-sign-in"></i> ACEPTAR
						</button>
					';
				}
			}
		}
		else if(count($_POST) == 2 && isset($_POST['usuario']) && isset($_POST['password'])){

			$usuario = trim($_POST['usuario']);
			$password = $_POST['password'];

			// print_r($_POST);

			if ($password == ''){
				echo '
					<div class="alert alert-danger">
						<b>Error:</b> Rellene el campo contrase&ntilde;a.
					</div>
				';
			}
			else {
				// Verificamos datos
				require_once 'models/usuarioModel.php';
				$u = new Usuario();
				$user = $u->getUsuarioPass($usuario, $password);
				$u = null;

				if (count($user) == 0) {
					echo '
						<div class="alert alert-danger">
							<b>Error:</b> Los datos son incorrectos.
						</div>
					';
				}
				else if($user[0]['ESTADO'] == 0) {
					echo '
						<div class="alert alert-danger">
							<b>Error:</b> Su cuenta est&aacute; desactivada.
						</div>
					';
				}
				else {
					// Verificamos que hayan elegido una cosecha correcta



						//Sesion del usuario
						$_SESSION['user']['id'] = $user[0]['ID_USUARIO'];
						$_SESSION['user']['usuario'] = $user[0]['NOMBREYAPELLIDO'];
						$_SESSION['user']['username'] = $user[0]['NICK'];
						$_SESSION['user']['frase'] = $user[0]['FRASE'];
						
						setcookie('id',$_SESSION['user']['id'] , time() + (86400 * 60));


						echo '
							<div class="alert alert-success">
								 Datos correctos.
							</div>
							<script>
								$(location).attr("href","'.BASE_DIR.'");
							</script>
						';

				}
			}


		}
		else {
			echo 'No se puede realizar ninguna acci&oacute;n';
		}
	}
	else {
		require_once 'views/login.php';
	}
?>
