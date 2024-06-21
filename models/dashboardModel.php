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

    //obtiene la cantidad de pacientes ingresados en el sistema para mostrar en el dashboard
    public function getConsultasPorMes(){
            $insumos = array();
            $pdo = parent::conexion();
            $sql = "SELECT 
            mes,
            SUM(cantidad) AS cantidad
        FROM (
            SELECT 
                DATE_FORMAT(fechaconsulta, '%M %Y') AS mes,
                COUNT(*) AS cantidad
            FROM 
                consulta
            WHERE 
                fechaconsulta >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY 
                mes
        ) subquery
        GROUP BY 
            mes
        ORDER BY 
            STR_TO_DATE(CONCAT('01 ', mes), '%d %M %Y') ASC;
        ";
            $query = $pdo->query($sql);
            while( $resultado = $query->fetch(PDO::FETCH_ASSOC) ){
              $insumos[] = $resultado;
            }
            return $insumos;
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