<?php
require_once 'models/configuracionModel.php';

class Proveedor extends Configuracion {

	public function addProveedor($nombres,$direccion,$telefono) 
    {
		
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("INSERT INTO clinica.proveedor(nombre, direccion, telefono) VALUES(:nombres,:direccion,:telefono);");
		$stmt->bindParam(':nombres', $nombres);
		$stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);
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

    public function updateProveedor($nombres,$direccion,$telefono){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("UPDATE clinica.proveedor SET nombre = :nombres , direccion = :direccion, telefono = :telefono ;");
    $stmt->bindParam(':nombres', $nombres);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':telefono', $telefono);
	$update = $stmt->execute();
    if($update){
        //CAMBIOS
        require_once 'views/header.php';
		require_once 'views/pacientes.php';
		require_once 'views/footer.php';
        return $update;
    }else{
        //print_r($stmt->errorInfo());
        die();
    }
    
    }

    //funcion que sirve para llenar los datos del proveedor en agregar-consulta
    public function getProveedorPorId($idProveedor){
        $pdo = parent::conexion();
        $paciente = array();
        $stmt = $pdo->prepare("select * from proveedor where idproveedor= :idproveedor;");
        $stmt->bindParam(":idproveedor",$idProveedor);
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

    //funcion que trae los proveedores
    public function getProveedor($idEmpleado)
    {
        $pdo = parent::conexion();
        $cotizaciones = array();
        $stmt = $pdo->prepare("SELECT idproveedor, nombres, direccion, telefono
                                FROM proveedor
                                ORDER BY idproveedor DESC");
        $stmt->bindParam(':proveedor', $idEmpleado);
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cotizaciones[] = $resultado;
        }
        return $cotizaciones;
    }


    //funcion para obtener el nombre del paciente
    public function getNombreProveedorPorId($idPaciente){
        $pdo = parent::conexion();
        $paciente = array();
        $stmt = $pdo->prepare("SELECT nombres FROM proveedor  WHERE idproveedor= :idproveedor;");
        $stmt->bindParam(":idproveedor",$idPaciente);
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
    public function desactivarProveedor($idPaciente){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("UPDATE proveedor SET estado = 0 WHERE idproveedor = :idproveedor");
        $stmt->bindParam(':idproveedor', $idPaciente);
        $update = $stmt->execute();
        if($update == true){
            return $update;
        }else{
            die($stmt->errorInfo());
        }
    }

    public function activarPaciente($idPaciente){
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("UPDATE proveedor SET estado = 1 WHERE idproveedor = :idproveedor");
        $stmt->bindParam(':idproveedor', $idPaciente);
        $update = $stmt->execute();
        if($update == true){
            return $update;
        }else{
            die($stmt->errorInfo());
        }
    }

}
