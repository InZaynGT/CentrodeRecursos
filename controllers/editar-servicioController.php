<?php

if (count($_POST) > 0) {
	// print_r($_POST);
	if (isset($_POST['nombre']) && isset($_POST['precio']) && isset($idItem) && isset($slug)) {
	
		$nombre=$_POST['nombre'];
		$precio = $_POST['precio'];

		require_once 'models/servicioModel.php';
		$u = new Servicio();
		$update = $u->updateServicio($idItem,$nombre,$precio);
		$u = null;

		if ($update == true) {
			echo '
				<script>
					$(location).attr("href","'.BASE_DIR.'editar-servicio/'.$idItem.'-'.slug($slug).'/update");
				</script>
			';
		}
		else {
			echo '<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
					<b>Error:</b> No se pudo actualizar el registro, recarge la p&aacute;gina e int&eacute;ntelo nuevamente.
				</div>';
		}
	}

	else {
		echo 'No se puede realizar ninguna acci&oacute;n';
	}
}
else {
	require_once 'models/servicioModel.php';
	$u = new Servicio();
	$servicio = $u->getServicioPorID($idItem);
	$u = null;

	if (count($servicio) > 0) {
		require_once 'views/header.php';
		require_once 'views/editar-servicio.php';
		require_once 'views/footer.php';
	}
	else {
		require_once 'views/404.php';
	}
}
?>
