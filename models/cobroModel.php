<?php 
require_once 'models/configuracionModel.php';

class Cobro extends Configuracion { 

    public function getDocumentos(){
        $pdo=parent::conexion();
        $documentos = array();
        $stmt = $pdo->prepare("SELECT * FROM mostrar_lista_documentos;");
        $result = $stmt->execute();
        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $documentos[] = $resultado;
        }

        return $documentos;

        if(!$result){
            print_r($stmt->errorInfo());
        }

    }

    public function getFormasPago(){
        $pdo=parent::conexion();
        $formasPago = array();
        $stmt = $pdo->prepare("SELECT * FROM mostrar_lista_formas_pago;");
        $result = $stmt->execute();
        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $formasPago[] = $resultado;
        }

        return $formasPago;

        if(!$result){
            print_r($stmt->errorInfo());
        }

    }

}