<?php
require_once 'models/configuracionModel.php';

class documento extends Configuracion { 

    public function getCorrelativoDocumento($idDocumento){

        $pdo =parent:: conexion();
        $recibo = array();
        $stmt = $pdo->prepare("SELECT * FROM mostrar_numero_actual_documento WHERE iddocumento = :idDocumento;");
        $stmt->bindParam(':idDocumento', $idDocumento);
        $stmt->execute();
        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $recibo[] = $resultado;
        }
        return $recibo[0];
    }

}