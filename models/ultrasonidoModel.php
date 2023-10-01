<?php
require_once 'models/configuracionModel.php';

class Ultrasonido extends Configuracion { 


    public function setUltrasonidoYGenerico($nombreUltrasonido, $precioUltrasonido){
        try{
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("call insertar_ultrasonido_y_generico(:ultrasonido, :precio);");
        $stmt->bindParam(":ultrasonido", $nombreUltrasonido);
        $stmt->bindParam(":precio", $precioUltrasonido);
        $result = $stmt->execute();

        return $result;
    }catch(Exception $e){
            echo '<div class="alert alert-danger alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
                      <b>Error:</b> No se pudo almacenar el registro, el tipo de ultrasonido ya se encuentra almacenado.
                      </div>';
        }
    }

    public function updateUltrasonido($idultrasonido,$nombreUltrasonido,$precioUltrasonido){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("call update_ultrasonido(:idultrasonido,:ultrasonido,:precio);");
        $stmt->bindParam(":idultrasonido", $idultrasonido);
        $stmt->bindParam(":ultrasonido", $nombreUltrasonido);
        $stmt->bindParam(":precio", $precioUltrasonido);
        $result = $stmt->execute();

        //if(!$result){
        //    print_r($stmt->errorInfo());
         //   die();
       // }

        return $result;

    }

    public function getUltrasonidoPorID($id) {
        $pdo = parent::conexion();
        $ultrasonido = array();
        $stmt = $pdo->prepare("SELECT * FROM ultrasonido_por_id WHERE idproducto_servicio = :id;");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
            $ultrasonido[] = $resultado;
        }
        if (count($ultrasonido)){
            return $ultrasonido[0];
        }
        else {
            return $ultrasonido;
        }
    }

    //funcion que trae los ultrasonidos
    public function getUltrasonido($idEmpleado)
    {
        $pdo = parent::conexion();
        $cotizaciones = array();
        $stmt = $pdo->prepare("SELECT idproducto_servicio, nombre, precio
                            FROM producto_servicio
                            WHERE estado = 1 AND es_ultrasonido = 1
                            ORDER BY idproducto_servicio DESC
                            LIMIT 0,100");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cotizaciones[] = $resultado;
        }
        return $cotizaciones;
    }

    //Obtiene los ultrasonidos de la consulta para cobrar y para mostrar en la vista previa del pago
    public function getUtrasonidosConsulta($idConsulta){
        $pdo = parent :: conexion();
        $ultrasonidosConsulta = array();
        $stmt = $pdo->prepare("SELECT * from mostrar_ultrasonidos_consulta where idconsulta = :idConsulta;");
        $stmt->bindParam(':idConsulta', $idConsulta);
        $stmt->execute();

        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $ultrasonidosConsulta[] = $resultado;
        }

        return $ultrasonidosConsulta;
    }
    

    public function getImgUltrasonidoConsulta($idUltrasonido, $idConsulta){
        $pdo = parent :: conexion();
        $stmt = $pdo->prepare("SELECT img_ultrasonido FROM consulta_ultrasonidos WHERE idultrasonido = :idult AND idconsulta = :idcons;");
        $stmt->bindParam(':idult',$idUltrasonido);
        $stmt->bindParam(':idcons',$idConsulta);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_COLUMN);

        return $resultado;

    }
}
