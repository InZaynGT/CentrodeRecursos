<?php
require_once 'models/configuracionModel.php';

class Servicio extends Configuracion { 
    


    public function getServicios(){
        $pdo = parent::conexion();
        $servicios = array();
        $stmt = $pdo->prepare('SELECT * FROM mostrar_listado_servicios; ');
        $stmt->execute();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC) ){
            $servicios[] = $result;

        }
        return $servicios;

    }

    public function setServicio($nombreServicio, $precioServicio){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("call insertar_servicio(:servicio, :precio);");
        $stmt->bindParam(":servicio", $nombreServicio);
        $stmt->bindParam(":precio", $precioServicio);
        $result = $stmt->execute();

        return $result;

    }

    public function updateServicio($idServicio,$nombreServicio,$precioServicio){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("call update_servicio(:idservicio,:servicio,:precio);");
        $stmt->bindParam(":idservicio", $idServicio);
        $stmt->bindParam(":servicio", $nombreServicio);
        $stmt->bindParam(":precio", $precioServicio);
        $result = $stmt->execute();

        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }

        return $result;

    }

    public function getServicioPorID($id) {
        $pdo = parent::conexion();
        $servicio = array();
        $stmt = $pdo->prepare("SELECT * FROM servicio_por_id WHERE idservicio = :id;");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
            $servicio[] = $resultado;
        }
        if (count($servicio)){
            return $servicio[0];
        }
        else {
            return $servicio;
        }
    }





}
