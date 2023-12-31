<?php
//permiso(1);
require_once 'models/pacienteModel.php';
$u = new Patient();
$idPaciente = $_SESSION['user']['id'];
$array = $u->getPaciente($idPaciente);

if ($_SERVER['REQUEST_METHOD']=="POST") {
	if (count($_POST) > 0 && isset($_POST['nombres']) && isset($_POST['apellidos']) && isset($_POST['direccion']) 
	&& isset($_POST['direccionTrabajo']) && isset($_POST['lugarTrabajo']) && isset($_POST['ocupacion']) && isset($_POST['telefono'])
	&& isset($_POST['fechaNacimiento']) && isset($_POST['dpi']) && isset($_POST['genero']) && isset($_POST['estadoCivil']) 
	&& isset($_POST['escolaridad']) && isset($_POST['tipoSangre']) && isset($_POST['conyugue']) && isset($_POST['responsable'])
	&& isset($_POST['religion']) && isset($_POST['padre']) && isset($_POST['madre']) && isset($_POST['hermanos']) 
	&& isset($_POST['observaciones'])
	) {

		$nombres = $_POST['nombres'];
		$apellidos = $_POST['apellidos'];
		$direccion = $_POST['direccion'];
		$direccionTrabajo = $_POST['direccionTrabajo'];
		$lugarTrabajo = $_POST['lugarTrabajo'];
		$ocupacion = $_POST['ocupacion'];
		$telefono = $_POST['telefono'];
		$fechaNacimiento = $_POST['fechaNacimiento'];
		$dpi = $_POST['dpi'];
		$genero = $_POST['genero'];
		$estadoCivil = $_POST['estadoCivil'];
		$escolaridad = $_POST['escolaridad'];
		$tipoSangre = $_POST['tipoSangre'];
		$conyugue = $_POST['conyugue'];
		$responsable = $_POST['responsable'];
		$religion = $_POST['religion'];
		$padre = $_POST['padre'];
		$madre = $_POST['madre'];
		$hermanos = $_POST['hermanos'];
		$observaciones = $_POST['observaciones'];
		$fechaIngreso = date('Y-m-d H:i:s');
		$img = $_POST['image'];

		if(empty($img)){
			$filename[0]="";

		}
		else{
			//subir la foto al directorio
		$folderPath = "/xampp/uploads/pacientes/";

		$image_parts = explode(";base64",$img);
		$image_type_aux = explode("image/", $image_parts[0]);
		$image_type = $image_type_aux[1];

		$image_base64 = base64_decode($image_parts[1]);
		$filename = uniqid("",false).'.png';

		$file = $folderPath.$filename;
		file_put_contents($file,$image_base64);
		$filename = explode('.',$filename);
			
		}
	
		
		require_once 'models/pacienteModel.php';
						$p = new Patient();
						$insert = $p->addPatient($nombres,$apellidos,$direccion,$direccionTrabajo,$lugarTrabajo,$ocupacion,$telefono,
						$fechaNacimiento,$dpi,$genero, $estadoCivil, $escolaridad, $tipoSangre, $conyugue, $responsable,$religion, $padre, $madre, 
						$hermanos, $observaciones,$fechaIngreso,$filename[0]);
						if ($insert == true) {
							echo '<div class="alert alert-success alert-dismissable">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
										<b>OK:</b> Registro almacenado correctamente.
									</div>';
							echo '
								<script>
									$(location).attr("href","'.BASE_DIR.'pacientes");
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
	else {
		echo 'No se puede realizar ninguna acci&oacute;n';
	}
}
else {
	
	require_once 'views/header.php';
	require_once 'views/pacientes.php';
	require_once 'views/footer.php';
}
?>
