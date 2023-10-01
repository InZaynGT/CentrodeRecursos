<?php
require_once 'models/configuracionModel.php';

class Receta extends Configuracion {

    public function setReceta($idConsulta,$idPaciente, $idMedicamento, $dosificacion, $uso){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("call insert_receta(:idconsulta, :idpaciente, :idmedicamento, :dosificacion, :uso);");
        $stmt->bindParam(':idconsulta', $idConsulta);
        $stmt->bindParam(':idpaciente', $idPaciente);
        $stmt->bindParam(':idmedicamento', $idMedicamento);
        $stmt->bindParam(':dosificacion', $dosificacion);
        $stmt->bindParam(':uso', $uso);
        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }
        return $result;
    }

    public function getRecetaEncabezado($idConsulta){
        $pdo = parent::conexion();
        $encabezado = array();
        $stmt = $pdo->prepare("SELECT * FROM obtener_receta_encabezado WHERE idconsulta = :idConsulta;");
        $stmt->bindParam(':idConsulta', $idConsulta);

        $resultado = $stmt->execute();

        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $encabezado[] = $resultado;
        }
        return $encabezado[0];

    }

    public function getRecetaDetalle($idConsulta){
        $pdo = parent::conexion();
        $detalle = array();
        $stmt = $pdo->prepare("SELECT * FROM obtener_receta_detalle WHERE idconsulta = :idConsulta;");
        $stmt->bindParam(':idConsulta', $idConsulta);

        $resultado = $stmt->execute();

        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $detalle[] = $resultado;
        }
        return $detalle;

    }

    public function deleteMedicamentos($idConsulta){
        $pdo = parent:: conexion();
        $stmt = $pdo->prepare("call delete_medicamentos(:idconsulta);");
        $stmt->bindParam(':idconsulta', $idConsulta);

        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }

    }

}