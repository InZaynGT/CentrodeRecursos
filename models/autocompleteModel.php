<?php
require_once 'models/configuracionModel.php';

class autocomplete extends Configuracion { 

    public function getPacientes($search){
		$data = array();
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT idpaciente, nombres, apellidos FROM vista_pacientes 
        where concat_ws(' ',idpaciente,nombres,apellidos)  like '%$search%';");
		$stmt->execute();

		while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			//array_push($data,$result['idpaciente'],$result['nombres'],$result['apellidos']);
			$data[] = $result['idpaciente'].' - '.$result['nombres'].' '.$result['apellidos'];

		}
		

		return $data;
      

	}


}