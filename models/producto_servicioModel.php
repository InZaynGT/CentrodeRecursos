<?php
require_once 'models/configuracionModel.php';

class Producto  extends Configuracion {

    public function getProductoPorCodigo($codigoProd){

        $pdo = parent::conexion();
        $productos = array();
        $stmt = $pdo->prepare('SELECT * FROM listado_productos_y_servicios where codigo = :codProd;');
        $stmt->bindParam(':codProd', $codigoProd);
        $stmt->execute();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC) ){
            $productos[] = $result;

        }
        return $productos[0];
    }

    public function desactivarServicio($idServicio){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("UPDATE producto_servicio SET estado = 0 WHERE idproducto_servicio = :id and es_servicio = 1;");
        $stmt->bindParam(':id', $idServicio);
        $update = $stmt->execute();
        if($update == true){
            return $update;
        }else{
            die($stmt->errorInfo());
        }
    }

    public function desactivarUltrasonido($idServicio){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("UPDATE producto_servicio SET estado = 0 WHERE idproducto_servicio = :id and es_ultrasonido = 1;");
        $stmt->bindParam(':id', $idServicio);
        $update = $stmt->execute();
        if($update == true){
            return $update;
        }else{
            die($stmt->errorInfo());
        }
    }

    public function desactivarLaboratorio($idServicio){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("UPDATE producto_servicio SET estado = 0 WHERE idproducto_servicio = :id and es_laboratorio = 1;");
        $stmt->bindParam(':id', $idServicio);
        $update = $stmt->execute();
        if($update == true){
            return $update;
        }else{
            die($stmt->errorInfo());
        }
    }

}