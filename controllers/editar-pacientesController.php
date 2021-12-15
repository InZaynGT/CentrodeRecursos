<?php
permiso(1);
if (count($_POST) > 0) {
	// print_r($_POST);
	if (count($_POST) > 0  && isset($_POST['nombres']) && isset($_POST['apellidos']) && isset($_POST['direccion']) 
	&& isset($_POST['direccionTrabajo']) && isset($_POST['lugarTrabajo']) && isset($_POST['ocupacion']) && isset($_POST['telefono'])
	&& isset($_POST['fechaNacimiento']) && isset($_POST['dpi']) && isset($_POST['genero']) && isset($_POST['estadoCivil']) 
	&& isset($_POST['escolaridad']) && isset($_POST['tipoSangre']) && isset($_POST['conyugue']) && isset($_POST['responsable'])
	&& isset($_POST['padre']) && isset($_POST['madre']) && isset($_POST['hermanos']) && isset($_POST['observaciones']) && isset($idItem) && isset($slug)) {
		
        $nombres = $_POST['nombres'];
		$apellidos = $_POST['apellidos'];
		$direccion = $_POST['direccion'];
		$direccionTrabajo = $_POST['direccionTrabajo'];
		$lugarTrabajo = $_POST['lugarTrabajo'];
		$ocupacion = $_POST['ocupacion'];
		$telefono = $_POST['telefono'];
		$fechaNacimiento = $_POST['fechaNacimiento'];
		$fechaNacimiento = date("Y-m-d");
		$dpi = $_POST['dpi'];
		$genero = $_POST['genero'];
		$estadoCivil = $_POST['estadoCivil'];
		$escolaridad = $_POST['escolaridad'];
		$tipoSangre = $_POST['tipoSangre'];
		$conyugue = $_POST['conyugue'];
		$responsable = $_POST['responsable'];
		$padre = $_POST['padre'];
		$madre = $_POST['madre'];
		$hermanos = $_POST['hermanos'];
		$observaciones = $_POST['observaciones'];
		$fechaModificacion = date('Y-m-d H:i:s');
        $usuarioModificacion = $_SESSION['user']['id'];
    
		require_once 'models/pacientesModel.php';
		$u = new Patient();
		$update = $u->updatePaciente($idItem, $nombres,$apellidos,$direccion,$direccionTrabajo,$lugarTrabajo,$ocupacion,$telefono,
		$fechaNacimiento,$dpi,$genero,$estadoCivil,$escolaridad,$tipoSangre,$conyugue,$responsable,$padre,$madre,$hermanos,$observaciones,
		$fechaModificacion,$usuarioModificacion);
		unset($u);

		if ($update == true) {
			echo '
				<script>
				window.location.href ="'.BASE_DIR.'editar-pacientes/'.$idItem.'-'.slug($slug).'/update"
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

	require_once 'models/pacientesModel.php';
	$p = new Patient();
	$paciente = $p->getPacientePorId($idItem);
	$idEstadoCivil=$paciente['estado_civil'];
	$idGenero =$paciente['genero'];
	$idEscolaridad = $paciente['escolaridad'];
	$idTipoSangre = $paciente['tipo_sangre'];
	$foto = $paciente['foto'];
	unset($p);

	//Mostrar los campos obtenidos de la base y ponerlos en los input de la vista para editarlos
	//Genero, estado civil, profesion, tipo de sangre... están en la tabla generico
	$e = new Patient();
	$estadoCivil = $e->getEstadoCivil($idEstadoCivil);
	unset($e);

	$g = new Patient();
	$genero = $g->getGenero($idGenero);
	unset($g);

	$esc = new Patient();
	$escolaridad = $esc->getEscolaridad($idEscolaridad);
	unset($esc);

	$tip = new Patient();
	$tipoSangre = $tip->getTipoSangre($idTipoSangre);
	unset($tip);
	
	if(count($paciente) > 0){
		require_once 'views/header.php';
		require_once 'views/pacientes-editar.php';
		require_once 'views/footer.php';

	}
	else {
		require_once 'views/404.php';

	}		
	

}
?>