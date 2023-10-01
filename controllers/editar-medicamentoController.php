<?php

if ($_SERVER['REQUEST_METHOD']=="POST") {

    if(isset($_POST['idMedicamento'])){
        $idMedicamento = $_POST['idMedicamento'];

        require_once 'models/medicamentoModel.php';

        $m = new Medicamento();
        $medicamento = $m->getMedicamentoPorId($idMedicamento);
        unset($m);

        echo json_encode($medicamento);
    }

    if(isset($_POST['codigoFiltro']) && isset($_POST['nombreMedicamento'])){
        $codigoFiltro=$_POST['codigoFiltro'];
		$nombreMedicamento = $_POST['nombreMedicamento'];
        $dosificacion = $_POST['dosificacion'];

		require_once 'models/medicamentoModel.php';
		$u = new Medicamento();
		$update = $u->updateMedicamento($idItem,$codigoFiltro,$nombreMedicamento,$dosificacion);
		unset($u);

		//El error 1062 es de mysql por violación de llave única
		if($update == 1062){
			echo '<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
					<b>Error:</b> Ya existe un producto con la información ingresada, verifique.
				</div>';
				exit;

		}
		//CAMBIOS1703
		else if ($update == false) {
			echo '
            
				<script>
					$(location).attr("href","'.BASE_DIR.'editar-medicamento/'.$idItem.'-'.slug($slug).'/update");
				</script>
			';
		}
		else if ($update == true){
			echo '<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
					<b>Error:</b> No se pudo actualizar el registro, recarge la p&aacute;gina e int&eacute;ntelo nuevamente.
				</div>';
		}
	

    }

	
		
}
else {


		require_once 'views/header.php';
		require_once 'views/editar-medicamento.php';
		require_once 'views/footer.php';
	
}
?>
