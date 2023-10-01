<?php

if ($_SERVER['REQUEST_METHOD']=="POST") {
	
	
		$nombre=$_POST['nombre'];
		$precio = $_POST['precio'];

		require_once 'models/ultrasonidoModel.php';
		$u = new Ultrasonido();
		$update = $u->updateUltrasonido($idItem,$nombre,$precio);
		$u = null;

		if ($update == true) {
			echo '
            
				<script>
					$(location).attr("href","'.BASE_DIR.'editar-ultrasonido/'.$idItem.'-'.slug($slug).'/update");
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
	require_once 'models/ultrasonidoModel.php';
	$u = new Ultrasonido();
	$ultrasonido = $u->getUltrasonidoPorID($idItem);
	$u = null;

	if (count($ultrasonido) > 0) {
		require_once 'views/header.php';
		require_once 'views/editar-ultrasonido.php';
		require_once 'views/footer.php';
	}
	else {
		require_once 'views/404.php';
	}
}
?>
