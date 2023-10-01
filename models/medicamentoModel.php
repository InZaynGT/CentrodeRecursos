<?php
require_once 'models/configuracionModel.php';

class Medicamento extends Configuracion
{

    public function insertMedicamento($codigoFiltro, $nombreMedicamento, $dosificacionMedicamento, $usoMedicamento,$costo,$precioP,$precioA)
    {
        try {
            $pdo = parent::conexion();
            $stmt = $pdo->prepare("INSERT INTO medicamento(codigofiltro, nombre, dosificacion, uso, costo, precioP, precioA) 
                                    VALUES(:codigoFiltro, :nombreMed, :dosificacion, :uso, :costo, :precioP, :precioA);");
            $stmt->bindValue(':codigoFiltro', $codigoFiltro);
            $stmt->bindValue(':nombreMed', $nombreMedicamento);
            $stmt->bindValue(':dosificacion', $dosificacionMedicamento);
            $stmt->bindValue(':uso', $usoMedicamento);
            $stmt->bindValue(':costo', $costo);
            $stmt->bindValue(':precioP', $precioP);
            $stmt->bindValue(':precioA', $precioA);
            $result = $stmt->execute();
            if ($result == 1062) {
                echo '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
            <b>OK:</b> Registro almacenado correctamente.
            </div>';
                echo '<script>
                    window.location="' . BASE_DIR . 'medicamentos/"
                </script>';
                die();
            }
            return $result;
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
            <b>Error:</b> No se pudo almacenar el registro, ya se encuentra almacenado.
            </div>',$e;
        }
    }

    public function getMedicamentoPorId($idMedicamento)
    {
        $pdo = parent::conexion();
        $medicamento = array();
        $stmt = $pdo->prepare("SELECT codigofiltro, nombre, dosificacion  FROM vista_medicamentos WHERE idmedicamento = :id;");
        $stmt->bindParam(':id', $idMedicamento);
        $stmt->execute();
        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $medicamento[] = $resultado;
        }
        if (count($medicamento)) {
            return $medicamento[0];
        } else {
            return $medicamento;
        }
    }

    public function updateMedicamento($idMedicamento, $codigoFiltro, $nombre, $dosificacion)
    {

        $pdo = parent::conexion();
        $stmt = $pdo->prepare("call update_medicamento(:idmedicamento,:codigofiltro,:nombre, :dosificacion);");
        $stmt->bindParam(":idmedicamento", $idMedicamento);
        $stmt->bindParam(":codigofiltro", $codigoFiltro);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":dosificacion", $dosificacion);
        $result = $stmt->execute();
        if ($stmt->errorInfo()) {
            $error = $stmt->errorInfo();
            if ($error[1] == 1062) {
                return $error[1];
            }
        } else {
            return $result;
        }
    }

    //Se le cambia el estado a un medicamento para que ya no aparezca
    public function eliminarMedicamento($idMedicamento)
    {
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("UPDATE medicamento SET activo = 0 WHERE idmedicamento = :idmedicamento;");
        $stmt->bindParam(":idmedicamento", $idMedicamento);
        $result = $stmt->execute();

        return $result;
    }

    //funcion que trae los medicamentos
    public function getMedicamento($idEmpleado)
    {
        $pdo = parent::conexion();
        $cotizaciones = array();
        $stmt = $pdo->prepare("SELECT idmedicamento, codigofiltro, nombre, dosificacion, uso, existencia
                            FROM medicamento
                            WHERE activo = 1
                            ORDER BY idmedicamento DESC
                            LIMIT 0,100");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cotizaciones[] = $resultado;
        }
        return $cotizaciones;
    }

}
