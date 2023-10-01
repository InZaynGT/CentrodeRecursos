<?php
require_once 'models/configuracionModel.php';

class Factura extends Configuracion {

    public function setFactura ($fechaFactura, $idPaciente,$idConsulta, $formaPago, $total, $idUsuario){
        $pdo =parent:: conexion();
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("call insertar_factura_encabezado(:fechafactura, :idpaciente,:idconsulta, :formapago, :total, :idusuario);");
        $stmt->bindParam(':fechafactura',$fechaFactura);
        $stmt->bindParam(':idpaciente',$idPaciente);
        $stmt->bindParam(':idconsulta', $idConsulta);
        $stmt->bindParam(':formapago',$formaPago);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':idusuario', $idUsuario);
        try{
            $stmt->execute();
            $pdo->commit();
            $stmt2= $pdo->prepare("SELECT LAST_INSERT_ID();");
            $stmt2->execute();
            $nroFact= $stmt2->fetch(PDO::FETCH_LAZY);
            return $nroFact[0];

        }catch(PDOException $e){
            $pdo->rollBack();
            print "Error: " .$e->getMessage();
        }
        
        
    }


    public function setDetalleFactura ($nroFactura,$codigoProducto, $cantidad, $precio){
        $pdo =parent:: conexion();
        $stmt = $pdo->prepare("call insertar_factura_detalle(:nrofactura, :codigoproducto, :cantidad, :precio);");
        $stmt->bindParam(':nrofactura', $nroFactura);
        $stmt->bindParam(':codigoproducto',$codigoProducto);
        $stmt->bindParam(':cantidad',$cantidad);
        $stmt->bindParam(':precio', $precio);
        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }
        return $result;


    }

    public function setPagoFactura($nroFactura, $idPaciente, $formaPago,$documento,$observaciones, $monto, $correlativo){
        $pdo =parent:: conexion();
        $stmt = $pdo->prepare("call insertar_factura_pago(:nrofactura, :idPaciente, :formaPago,:documento, :observaciones,:monto,:correlativo);");
        $stmt->bindParam(':nrofactura', $nroFactura);
        $stmt->bindParam(':idPaciente',$idPaciente);
        $stmt->bindParam(':formaPago',$formaPago);
        $stmt->bindParam(':documento',$documento);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':monto', $monto);
        $stmt->bindParam(':correlativo', $correlativo);

        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }
        return $result;

    }

    public function getImpresionReciboEnc($idConsulta){
        $pdo =parent:: conexion();
        $recibo = array();
        $stmt = $pdo->prepare("SELECT * FROM impresion_recibo WHERE idconsulta = :idConsulta;");
        $stmt->bindParam(':idConsulta', $idConsulta);
        $stmt->execute();
        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $recibo[] = $resultado;
        }

        return $recibo[0];

    }

    public function getImpresionReciboDet($idConsulta){
        $pdo = parent:: conexion();
        $recibo =  array();
        $stmt = $pdo->prepare("SELECT fd.nrofactura, fd.codigoproducto, ps.nombre, fd.cantidad, fd.precio FROM factura_detalle fd
        INNER JOIN producto_servicio ps on ps.idproducto_servicio = fd.codigoproducto
        INNER JOIN factura_encabezado fe on fe.nrofactura = fd.nrofactura
        WHERE fe.idconsulta = :idConsulta;");
        $stmt->bindParam(':idConsulta', $idConsulta);
        $stmt->execute();
        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $recibo[] = $resultado;
        }
        return $recibo;


    }

}