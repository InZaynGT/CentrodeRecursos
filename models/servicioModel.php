<?php
require_once 'models/configuracionModel.php';

class Servicio extends Configuracion
{


    public function setServicio($nombreServicio, $precioServicio)
    {
        try {
            $pdo = parent::conexion();
            $stmt = $pdo->prepare("call insertar_servicio(:servicio, :precio);");
            $stmt->bindParam(":servicio", $nombreServicio);
            $stmt->bindParam(":precio", $precioServicio);
            $result = $stmt->execute();
            return $result;
        } catch (Exception $e) {
            echo '<div class="alert alert-danger alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
                  <b>Error:</b> No se pudo almacenar el registro, el servicio ya se encuentra almacenado.
                  </div>';
        }
    }

    public function updateServicio($idServicio, $nombreServicio, $precioServicio)
    {
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("call update_servicio(:idservicio,:servicio,:precio);");
        $stmt->bindParam(":idservicio", $idServicio);
        $stmt->bindParam(":servicio", $nombreServicio);
        $stmt->bindParam(":precio", $precioServicio);
        $result = $stmt->execute();

        if (!$result) {
            print_r($stmt->errorInfo());
            die();
        }

        return $result;
    }

    //funcion que trae los servicios
    public function getServicio($idEmpleado)
    {
        $pdo = parent::conexion();
        $cotizaciones = array();
        $stmt = $pdo->prepare("SELECT idproducto_servicio, nombre, precio
                            FROM producto_servicio
                            WHERE estado = 1 AND es_servicio = 1
                            ORDER BY idproducto_servicio DESC");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cotizaciones[] = $resultado;
        }
        return $cotizaciones;
    }

    public function getServicioPorID($id)
    {
        $pdo = parent::conexion();
        $servicio = array();
        $stmt = $pdo->prepare("SELECT * FROM servicio_por_id WHERE idproducto_servicio = :id;");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $servicio[] = $resultado;
        }
        if (count($servicio)) {
            return $servicio[0];
        } else {
            return $servicio;
        }
    }
}
