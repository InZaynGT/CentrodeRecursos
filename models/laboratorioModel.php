<?php
require_once 'models/configuracionModel.php';

class Laboratorio  extends Configuracion { 
    
    public function setLaboratorio($nombreLaboratorio){
    try{
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("call insertar_laboratorio(:servicio);");
        $stmt->bindParam(":servicio", $nombreLaboratorio);
        $result = $stmt->execute();

        return $result;
    }catch(Exception $e){
        echo '<div class="alert alert-danger alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
                      <b>Error:</b> No se pudo almacenar el tipo de examen, el mismo ya se encuentra almacenado.
                      </div>';
    }
    }

    //funcion que trae los laboratorios
    public function getLaboratorio($idEmpleado)
    {
        $pdo = parent::conexion();
        $cotizaciones = array();
        $stmt = $pdo->prepare("SELECT idproducto_servicio, nombre, precio
                            FROM producto_servicio
                            WHERE estado = 1 AND es_laboratorio = 1
                            ORDER BY idproducto_servicio DESC");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cotizaciones[] = $resultado;
        }
        return $cotizaciones;
    }


    public function updateLaboratorio($idultrasonido,$nombreUltrasonido){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("call update_laboratorio(:idultrasonido,:ultrasonido);");
        $stmt->bindParam(":idultrasonido", $idultrasonido);
        $stmt->bindParam(":ultrasonido", $nombreUltrasonido);
        $result = $stmt->execute();

        //if(!$result){
        //    print_r($stmt->errorInfo());
         //   die();
       // }

        return $result;

    }

    public function getLaboratorioPorID($id) {
        $pdo = parent::conexion();
        $laboratorio = array();
        $stmt = $pdo->prepare("SELECT * FROM laboratorio_por_id WHERE idproducto_servicio = :id;");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
            $laboratorio[] = $resultado;
        }
        if (count($laboratorio)){
            return $laboratorio[0];
        }
        else {
            return $laboratorio;
        }
    }

    public function setLaboratorioConsulta($idConsulta, $idLaboratorio, $idPaciente, $nroReg){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("call insertar_laboratorio_consulta(:idconsulta, :idlaboratorio, :idpaciente, :nroreg);");
        $stmt->bindParam(':idconsulta', $idConsulta);
        $stmt->bindParam(':idlaboratorio', $idLaboratorio);
        $stmt->bindParam(':idpaciente', $idPaciente);
        $stmt->bindParam(':nroreg', $nroReg);
        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }
        return $result;
    }

    public function getLaboratoriosConsulta($idConsulta){
        $pdo = parent :: conexion();
        $ultrasonidos = array();
        $stmt = $pdo->prepare("SELECT * from mostrar_laboratorios_consulta where idconsulta = :idConsulta;");
        $stmt->bindParam(':idConsulta', $idConsulta);
        $stmt->execute();

        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $ultrasonidos[] = $resultado;
        }

        return $ultrasonidos;

    }

    public function getTiposLaboratorio(){
        $pdo = parent :: conexion();
        $tipos = array();
        $stmt = $pdo->prepare("SELECT idproducto_servicio codigo, nombre FROM producto_servicio  WHERE es_laboratorio = TRUE AND estado = 1 ORDER BY codigo ASC;");
        $stmt->execute();

        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $tipos[] = $resultado;
        }

        return $tipos;
    }

    public function deleteLaboratorios($idConsulta){
        $pdo = parent:: conexion();
        $stmt = $pdo->prepare("call delete_laboratorios(:idconsulta);");
        $stmt->bindParam(':idconsulta', $idConsulta);

        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }

    }

    public function getImgLaboratorioConsulta($idLaboratorio, $idConsulta){
        $pdo = parent :: conexion();
        $stmt = $pdo->prepare("SELECT img_laboratorio FROM consulta_laboratorios WHERE idlaboratorio = :idlab AND idconsulta = :idcons;");
        $stmt->bindParam(':idlab',$idLaboratorio);
        $stmt->bindParam(':idcons',$idConsulta);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_COLUMN);

        return $resultado;

    }

}