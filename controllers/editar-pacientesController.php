<?php

if (!empty($_POST)) {

    if ($_POST > 1) {
		//Información Personal
		$nombre = $_POST['nom_paciente'];
		$fecha= new DateTime($_POST['fechadeNacimiento']);
		$fechaFormateada = $fecha->format('Y-m-d H:i:s');
		$idUsuario = $_SESSION['user']['id'];
		$edad = isset($_POST['edad']) && is_numeric($_POST['edad']) ? intval($_POST['edad']) : 0;
		$sexo = $_POST['sexo'];
		$estado_civil= $_POST['estadoCivil'];
		$direccion = $_POST['direccion'];
		$nombre_encargado = $_POST['nom_encargado'];
		$telefono_encargado = $_POST['telefono_encargado'];
		$diagnostico = $_POST['diagnostico'];
		$medicamentos_admin = $_POST['medicamentos_Admin'];
		$medico_Tratante = $_POST['medico_Tratante'];
		$telefono_medico = $_POST['telefono_medico'];
		$examenes_realizados = $_POST['ciclo'];
		$convulsiona = isset($_POST['convulsiona']) ? 1 : 0;
		$usa_protesis = isset($_POST['usa_protesis']) ? 1 : 0;
		$cual_protesis = $_POST['cual_protesis'];
		$historia_enfermedad = $_POST['historia_enfermedad'];
		$observaciones = $_POST['observaciones'];
		require_once 'models/pacienteModel.php';

		//Antecedentes
		$cardiovascular = $_POST['cardiovascular'];
		$pulmonares = $_POST['pulmonares'];
		$digestivos = $_POST['digestivos'];
		$diabetes = $_POST['diabetes'];
		$renales = $_POST['renales'];
		$quirurgicos = $_POST['quirurgicos'];
		$alergicos = $_POST['alergicos'];
		$transfuciones = $_POST['transfuciones'];
		$medicamentos = $_POST['medicamentos'];
		$otros = $_POST['otros_antecedentes'];
		$alcohol = $_POST['alcohol'];
		$tabaquismo = $_POST['tabaquismo'];
		$drogas = $_POST['drogas'];
		$inmunizaciones = $_POST['inmunizaciones'];
		$otros_inmuniz = $_POST['otros_inmuniz'];
		$padre_vivo = isset($_POST['padre_vivo']) ? 1 : 0;
		$padre_enfermedad = $_POST['enfermedad_padre'];
		$madre_vivo = isset($_POST['madre_vivo']) ? 1 : 0;
		$madre_enfermedad = $_POST['enfermedad_madre'];
		$num_hermanos = isset($_POST['num_hermanos']) ? intval($_POST['num_hermanos']) : 0;
		$herman_vive = isset($_POST['herman_vive']) ? intval($_POST['herman_vive']) : 0;
		$enfermedad_herm = $_POST['enfermedad_herm'];
		$observaciones_antecedentes = $_POST['observaciones'];

		// Evaluaciones Fisioterapéuticas
		$dolor_donde = $_POST['localiza_dolor'];
		$dolor_irradiacion = $_POST['irradia_dolor'];
		$tipo_dolor = $_POST['tipo_dolor'];
		$escala_visual_dolor = $_POST['escaladolor'];

		// Tono Muscular
		if (isset($_POST['tono_muscular'])) {$tipo_tono = $_POST['tono_muscular'];} else {$tipo_tono = 0;}
		if (isset($_POST['limit_amplit'])) {$limitacion_artic = 1;} else {$limitacion_artic = 0;}
		$especificacion = $_POST['especifica_tono'];

		// Escala de Desarollo
		if (isset($_POST['control_cuello'])) {$control_cuello = $_POST['control_cuello'];} else {$control_cuello = 0;}
		if (isset($_POST['prono_supino'])) {$rotacion_prono_supino = $_POST['prono_supino'];} else {$rotacion_prono_supino = 0;}
		if (isset($_POST['supino_prono'])) {$rotacion_supino_prono = $_POST['supino_prono'];} else {$rotacion_supino_prono = 0;}
		if (isset($_POST['tronco_superior'])) {$tronco_superior = $_POST['tronco_superior'];} else {$tronco_superior = 0;}
		if (isset($_POST['tronco_inferior'])) {$tronco_inferior = $_POST['tronco_inferior'];} else {$tronco_inferior = 0;}
		if (isset($_POST['cuatro_puntos'])) {$cuatro_puntos = $_POST['cuatro_puntos'];} else {$cuatro_puntos = 0;}
		if (isset($_POST['pos_sedente'])) {$posicion_sedente = $_POST['pos_sedente'];}else {$posicion_sedente = 0;}
		if (isset($_POST['pos_hincado'])) {$posicion_hincado = $_POST['pos_hincado'];} else {$posicion_hincado = 0;}
		if (isset($_POST['pos_semihincado'])) {$posicion_semihincado = $_POST['pos_semihincado'];} else {$posicion_semihincado = 0;}
		if (isset($_POST['pos_bidepestacion'])) {$posicion_bidepestacion = $_POST['pos_bidepestacion'];} else {$posicion_bidepestacion = 0;}

		// Atencion
		if (isset($_POST['localizacion'])) {$localizacion = $_POST['localizacion'];} else {$localizacion = 0;}
		if (isset($_POST['fijacion'])) {$fijacion = $_POST['fijacion'];} else {$fijacion = 0;}
		if (isset($_POST['seguimiento'])) {$seguimiento = $_POST['seguimiento'];} else {$seguimiento = 0;}
		if (isset($_POST['alcance'])) {$alcance = $_POST['alcance'];} else {$alcance = 0;}
		if (isset($_POST['manipulacion'])) {$manipulacion = $_POST['manipulacion'];} else {$manipulacion = 0;}
		if (isset($_POST['exploracion'])) {$exploracion = $_POST['exploracion'];} else {$exploracion = 0;}

		// Destrezas Manuales
		if (isset($_POST['sostiene_objeto'])) {$sostiene_objeto = $_POST['sostiene_objeto'];} else {$sostiene_objeto = 0;}
		if (isset($_POST['suelta_objeto'])) {$suelta_objeto = $_POST['suelta_objeto'];} else {$suelta_objeto = 0;}
		if (isset($_POST['atrapa_objeto'])) {$atrapa_objeto = $_POST['atrapa_objeto'];} else {$atrapa_objeto = 0;}
		if (isset($_POST['lanza_objeto'])) {$lanza_objeto = $_POST['lanza_objeto'];} else {$lanza_objeto = 0;}
		if (isset($_POST['realiza_nudo'])) {$realiza_nudo = $_POST['realiza_nudo'];} else {$realiza_nudo = 0; }
		if (isset($_POST['encaja'])) {$encaja = $_POST['encaja']; } else {$encaja = 0; }

		//Actividades de la Vida Diaria

		if (isset($_POST['alimentacion'])) {$alimentacion = $_POST['alimentacion']; } else {$alimentacion = 0; }
		if (isset($_POST['higiene'])) {$higiene = $_POST['higiene']; } else {$higiene = 0; }
		if (isset($_POST['vestuario'])) {$vestuario = $_POST['vestuario']; } else {$vestuario = 0; }
		if (isset($_POST['control_esfinteres'])) {$control_esfinteres = $_POST['control_esfinteres']; } else {$control_esfinteres = 0; }
		if (isset($_POST['orden_limpieza'])) {$orden_limpieza = $_POST['orden_limpieza']; } else {$orden_limpieza = 0; }
		if (isset($_POST['ocio_recreacion'])) {$ocio_recreacion = $_POST['ocio_recreacion']; } else {$ocio_recreacion = 0; }
		$observaciones_actividades = $_POST['observaciones_actividades']; 
		
		//Postura
		$observaciones_postura = $_POST['observaciones_postura']; 

		//Marcha Y/O Desplazamiento
		if (isset($_POST['marcha'])) {$marcha = $_POST['marcha']; } else {$marcha = 0; }
		if (isset($_POST['sustentacion'])) {$sustentacion = $_POST['sustentacion']; } else {$sustentacion = 0; }
		if (isset($_POST['silla_ruedas'])) {$silla_ruedas = $_POST['silla_ruedas']; } else {$silla_ruedas = 0; }
		if (isset($_POST['apoyo'])) {$apoyo = $_POST['apoyo']; } else {$apoyo = 0; }
		if (isset($_POST['equilibrio'])) {$equilibrio = $_POST['equilibrio']; } else {$equilibrio = 0; }
		if (isset($_POST['coordinacion'])) {$coordinacion = $_POST['coordinacion']; } else {$coordinacion = 0; }
		if (isset($_POST['dispositivo'])) {$dispositivo = $_POST['dispositivo']; } else {$dispositivo = 0; }
		$cual_dispositivo = $_POST['cual_dispositivo']; 

		$c = new Patient();
		$c->actualizarPaciente($idItem,$nombre,$fechaFormateada,$edad, $sexo, $estado_civil, $direccion, $nombre_encargado,
		$telefono_encargado, $diagnostico, $medicamentos_admin, $medico_Tratante, $telefono_medico, $examenes_realizados,
		$convulsiona, $usa_protesis, $cual_protesis,$historia_enfermedad, $observaciones,$idUsuario);

		$c->ActualizarAntecedente($cardiovascular, $pulmonares, $digestivos, $diabetes, $renales, $quirurgicos, 
		$alergicos, $transfuciones, $medicamentos, $otros, $alcohol, $tabaquismo, $drogas, $inmunizaciones, $otros_inmuniz,
		$padre_vivo, $padre_enfermedad, $madre_vivo, $madre_enfermedad, $num_hermanos, $herman_vive, $enfermedad_herm,
		$observaciones_antecedentes, $idItem);

		$c->ActualizarEvaluaciones($dolor_donde, $dolor_irradiacion, $tipo_dolor, $escala_visual_dolor, $idItem);
		
		$c->ActualizarTonoMuscular($tipo_tono,$limitacion_artic,$especificacion,$idItem);

		$c->ActualizarEscalaDesarrollo($control_cuello, $rotacion_prono_supino,$rotacion_supino_prono, $tronco_superior,
		$tronco_inferior,$cuatro_puntos,$posicion_sedente,$posicion_hincado,$posicion_semihincado,$posicion_bidepestacion,$idItem);

		$c->ActualizarAtencion($localizacion, $fijacion, $seguimiento, $alcance,$manipulacion, $exploracion,$idItem);

		$c->ActualizarDestrezasManuales($sostiene_objeto, $suelta_objeto,$atrapa_objeto,$lanza_objeto,$realiza_nudo,
		$encaja,$idItem);

		$c->ActualizarActividadDiaria($alimentacion, $higiene,$vestuario,$control_esfinteres,$orden_limpieza,$ocio_recreacion,
		$observaciones_actividades,$idItem);

		$c->ActualizarPostura($observaciones_postura,$idItem);

		$c->ActualizarMarchaDesplazamiento($marcha,$sustentacion,$coordinacion,$equilibrio,$apoyo,$silla_ruedas,$dispositivo,
		$cual_dispositivo,$idItem);

		echo '<script>alert("Paciente actualizado exitosamente.");</script>';
		echo '<meta http-equiv="refresh" content="1;url=http://localhost/clinica/pacientes">';
		exit;
    }

} else {

    require_once 'models/consultaModel.php';
    $con = new Consulta();
    $consulta = $con->getPacienteporID($idItem);


    require_once 'views/header.php';
    require_once 'views/pacientes-editar.php';
    require_once 'views/footer.php';
}
