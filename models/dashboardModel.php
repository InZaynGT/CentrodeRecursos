<?php
require_once 'models/configuracionModel.php';

class DASHBOARD extends Configuracion { 


    //obtiene la cantidad de pacientes ingresados en el sistema para mostrar en el dashboard
    public function getPacientesCount(){
        $pdo=parent::conexion();
        $stmt = $pdo->prepare("SELECT count(idpaciente) as total from paciente;");
        $result = $stmt->execute();
        $resultado=$stmt->fetch(PDO::FETCH_COLUMN);
           
            if(!$result){
                print_r($stmt->errorInfo());
                die();
            }else {
                return $resultado;

            }

	}

    //obtiene la cantidad de consultas ingresadas en el sistema para mostrar en el dashboard
    public function  getConsultasCount(){
        $pdo =  parent::conexion();
        $stmt = $pdo->prepare("SELECT count(idconsulta) as total from consulta;");
        $result = $stmt->execute();
        $resultado=$stmt->fetch(PDO::FETCH_COLUMN);
           
            if(!$result){
                print_r($stmt->errorInfo());
                die();
            }else {
                return $resultado;

            }

    }

    public function getCitasCount(){
        $pdo =  parent::conexion();
        $stmt = $pdo->prepare("SELECT count(id_event) FROM events WHERE MONTH(start_event) = MONTH(now()) and DAY(start_event) = DAY(now());");
        $result = $stmt->execute();
        $resultado=$stmt->fetch(PDO::FETCH_COLUMN);
           
            if(!$result){
                print_r($stmt->errorInfo());
                die();
            }else {
                return $resultado;

            }

    }

}