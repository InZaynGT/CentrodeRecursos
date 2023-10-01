<?php 
require_once 'models/configuracionModel.php';

class Casa extends Configuracion {

    public function getCasas(){
        $pdo = parent::conexion();
        $cotizaciones = array();
        $stmt = $pdo->prepare("SELECT idproveedor, nombre
                                FROM proveedor");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cotizaciones[] = $resultado;
        }
        return $cotizaciones;
    }

    public function getCasaPorNombre($idproveedor){
        $pdo = parent::conexion();
        $cotizaciones = array();
        $stmt = $pdo->prepare("SELECT idproveedor, nombre
                                FROM proveedor
                                WHERE idproveedor = :id");
        $stmt->bindParam(':id', $idproveedor);
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cotizaciones[] = $resultado;
        }
        return $cotizaciones;

    }

}


?>