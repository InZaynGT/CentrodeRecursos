<?php
require_once 'models/configuracionModel.php';

class Patient extends Configuracion {

	public function addPatient($nombres,$apellidos,$direccion,$direccionTrabajo,$lugarTrabajo,$ocupacion,$telefono,
    $fechaNacimiento,$dpi,$genero, $estadoCivil, $escolaridad, $tipoSangre, $conyugue, $responsable,$religion, $padre, $madre, 
    $hermanos, $observaciones,$fechaIngreso,$foto
    ) {
		
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("call insertar_paciente(:nombres,:apellidos,:direccion,:direccionTrabajo,:lugarTrabajo,
        :ocupacion,:telefono,:fechaNacimiento,:dpi,:genero,:estadoCivil,:escolaridad,:tipoSangre,:conyugue,:responsable,
        :religion,:padre,:madre,:hermanos,:observaciones,:fechaIngreso,:nombreFoto);");
		$stmt->bindParam(':nombres', $nombres);
		$stmt->bindParam(':apellidos', $apellidos);
		$stmt->bindParam(':direccion', $direccion);
		$stmt->bindParam(':direccionTrabajo', $direccionTrabajo);
		$stmt->bindParam(':lugarTrabajo', $lugarTrabajo);
        $stmt->bindParam(':ocupacion', $ocupacion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fechaNacimiento', $fechaNacimiento);
        $stmt->bindParam(':dpi', $dpi);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':estadoCivil', $estadoCivil);
        $stmt->bindParam(':escolaridad', $escolaridad);
        $stmt->bindParam(':tipoSangre', $tipoSangre); 
        $stmt->bindParam(':conyugue', $conyugue);
        $stmt->bindParam(':responsable', $responsable);
        $stmt->bindParam(':religion', $religion);
        $stmt->bindParam(':padre', $padre);
        $stmt->bindParam(':madre', $madre);
        $stmt->bindParam(':hermanos', $hermanos);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':fechaIngreso', $fechaIngreso);
        $stmt->bindParam(':nombreFoto',$foto);
		$insert = $stmt->execute();
        if ($insert == false){
            print_r($stmt->errorInfo());
                }
        return $insert;

        //Para comprobar si hay un error al insertar
        //if ($insert == false){
	//print_r($stmt->errorInfo());
	   // }
    }

    public function getPacientes(){
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT * FROM vista_pacientes;");
		$stmt->execute();

		$result = $stmt->fetchAll();

		foreach($result as $pacientes){
			$data[] = array(
				'idpaciente' => $pacientes["idpaciente"],
                'nombres' => $pacientes["nombres"],
                'apellidos' => $pacientes["apellidos"],
                'direccion' => $pacientes["direccion"],
                'telefono' => $pacientes["telefono"],
                'fecha_ingreso' => $pacientes["fecha_ingreso"],
                'ultima_consulta' => $pacientes["ultima_consulta"],
                'estado' =>$pacientes["estado"]

			);
		}

		return $data;

	}

    public function updatePaciente($idItem, $nombres,$apellidos,$direccion,$direccionTrabajo,$lugarTrabajo,$ocupacion,$telefono,
    $fechaNacimiento,$dpi,$genero,$estadoCivil,$escolaridad,$tipoSangre,$conyugue,$responsable,$padre,$madre,$hermanos,$observaciones,
    $fechaModificacion,$usuarioModificacion){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("call update_paciente(:idPaciente,:nombres,:apellidos,:direccion,:direccion_trabajo,:lugar_trabajo,
    :ocupacion,:telefono,:fecha_nacimiento,:dpi,:genero,:estado_civil,:escolaridad,:tipo_sangre,:conyugue,:responsable,:padre,:madre,
    :hermanos,:observaciones,:fecha_modificacion,:usuario_modificacion);");
    $stmt->bindParam(':idPaciente', $idItem);
    $stmt->bindParam(':nombres', $nombres);
    $stmt->bindParam(':apellidos', $apellidos);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':direccion_trabajo', $direccionTrabajo);
    $stmt->bindParam(':lugar_trabajo', $lugarTrabajo);
    $stmt->bindParam(':ocupacion', $ocupacion);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':fecha_nacimiento', $fechaNacimiento);
    $stmt->bindParam(':dpi', $dpi);
    $stmt->bindParam(':genero', $genero);
    $stmt->bindParam(':estado_civil', $estadoCivil);
    $stmt->bindParam(':escolaridad', $escolaridad);
    $stmt->bindParam(':tipo_sangre', $tipoSangre);
    $stmt->bindParam(':conyugue', $conyugue);
    $stmt->bindParam(':responsable', $responsable);
    $stmt->bindParam(':padre', $padre);
    $stmt->bindParam(':madre', $madre);
    $stmt->bindParam(':hermanos', $hermanos);
    $stmt->bindParam(':observaciones', $observaciones);
    $stmt->bindParam(':responsable', $responsable);
    $stmt->bindParam(':fecha_modificacion', $fechaModificacion);
    $stmt->bindParam(':usuario_modificacion', $usuarioModificacion);

	$update = $stmt->execute();
    if($update){
        return $update;
    }else{
        die($stmt->errorInfo());
    }
    
    }

    public function getPacientePorId($idPaciente){
        $pdo = parent::conexion();
        $paciente = array();
        $stmt = $pdo->prepare("call mostrar_info_paciente(:idpaciente)");
        $stmt->bindParam(":idpaciente",$idPaciente);
        //$get = $stmt->execute() sirve para saber si se ejecutó el statement.
        $stmt->execute();
        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $paciente[] =$resultado;
        }
        if(count($paciente)){
            return $paciente[0];
        }
        else{
            return $paciente;
        }

    }

    public function getEstadoCivil($idEstadoCivil) {
		$pdo = parent::conexion();
		$estadoCivil = array();
		$stmt = $pdo->prepare("SELECT codigo, nombre FROM generico  WHERE tipo = 'CIV' AND codigo=:idEstadoCivil;");
		$stmt->bindParam(':idEstadoCivil', $idEstadoCivil);
        $stmt->execute();
		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$estadoCivil[] = $resultado;
		}
		return $estadoCivil[0];
	}

    public function getGenero($idGenero) {
		$pdo = parent::conexion();
		$genero = array();
		$stmt = $pdo->prepare("SELECT codigo, nombre FROM generico  WHERE tipo = 'SEX' AND codigo=:idGenero;");
		$stmt->bindParam(':idGenero', $idGenero);
       $get= $stmt->execute();
       if($get){
        while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$genero[] = $resultado;
		}
		return $genero[0];

       }else{
           die($stmt->errorInfo());
       }
		
	}

    public function getEscolaridad($idEscolaridad) {
		$pdo = parent::conexion();
		$escolaridad = array();
		$stmt = $pdo->prepare("SELECT codigo, nombre FROM generico  WHERE tipo = 'ESC' AND codigo=:idEscolaridad;");
		$stmt->bindParam(':idEscolaridad', $idEscolaridad);
       $get= $stmt->execute();
       if($get){
        while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$escolaridad[] = $resultado;
		}
		return $escolaridad[0];

       }else{
           die($stmt->errorInfo());
       }
		
    }

    public function getTipoSangre($idTipoSangre){
        $pdo = parent::conexion();
		$tipoSangre = array();
		$stmt = $pdo->prepare("SELECT codigo, nombre FROM generico  WHERE tipo = 'TSA' AND codigo=:idTipoSangre;");
		$stmt->bindParam(':idTipoSangre', $idTipoSangre);
       $get= $stmt->execute();
       if($get){
        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$tipoSangre[] = $resultado;
		}
		return $tipoSangre[0];

       }else{
           die($stmt->errorInfo());
       }


    }
    
    public function desactivarPaciente($idPaciente){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("UPDATE paciente SET estado = 0 WHERE idpaciente = :idpaciente");
        $stmt->bindParam(':idpaciente', $idPaciente);
        $update = $stmt->execute();
        if($update == true){
            return $update;
        }else{
            die($stmt->errorInfo());
        }
    }

    public function activarPaciente($idPaciente){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("UPDATE paciente SET estado = 1 WHERE idpaciente = :idpaciente");
        $stmt->bindParam(':idpaciente', $idPaciente);
        $update = $stmt->execute();
        if($update == true){
            return $update;
        }else{
            die($stmt->errorInfo());
        }
    }

}
?>
