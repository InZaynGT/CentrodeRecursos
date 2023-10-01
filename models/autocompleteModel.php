<?php
require_once 'models/configuracionModel.php';

class autocomplete extends Configuracion { 

    public function getPacientes($search){
		$data = array();
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT idpaciente, nombres, apellidos FROM vista_pacientes 
        where concat_ws(' ',idpaciente,nombres,apellidos)  like '%$search%'
		LIMIT 50;");
		$stmt->execute();

		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			
			$data[] = $result['idpaciente'].' - '.$result['nombres'].' '.$result['apellidos'];

		}
		

		return $data;
	}

	public function getProductos($search){
		$data = array();
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT idproducto_servicio, nombre FROM producto_servicio WHERE
		 concat_ws('',idproducto_servicio,nombre) LIKE '%$search%' AND es_servicio in(1,0) 
		LIMIT 50;");
		$stmt->execute();

		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			
			$data[] = '['.$result['idproducto_servicio'].']'.' - '.$result['nombre'];
		}

		return $data;

	}

	//retorna el resultado de la búsqueda en el autocomplete para la receta
	public function getMedicamentos($search){
		$data = array();
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT idmedicamento, nombre FROM vista_medicamentos
		WHERE concat_ws('', codigofiltro, nombre) LIKE '%$search%'
		LIMIT 50;");
		$stmt->execute();

		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			
			$data[] = '['.$result['idmedicamento'].']'.' - '.$result['nombre'];
		}

		return $data;

	}

	//Esta funcion retorna la información de los medicamentos para agregarlos en la receta
	public function getAgregarMedicamentos($idMedicamento){
		$data = array();
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT idmedicamento, nombre, dosificacion, uso FROM vista_medicamentos
		WHERE idmedicamento = :idmed;");
		$stmt->bindParam(':idmed',$idMedicamento);
		$stmt->execute();

		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			
			$data = $result;
		}

		return $data;

	}


}