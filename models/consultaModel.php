<?php
require_once 'models/configuracionModel.php';

class Consulta extends Configuracion
{
    public function setConsulta($idpaciente, $fechaConsulta, $idUsuario, $observaciones)
    {
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("INSERT INTO clinica.consulta(idpaciente, fechaconsulta, fechaoperacion, estado, idusuario,observaciones) 
        VALUES(:idpaciente,:fechaConsulta,NOW(),1,:idUsuario,:observaciones);
        ");
        $stmt->bindParam(':idpaciente', $idpaciente);
        $stmt->bindParam(':fechaConsulta', $fechaConsulta);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':observaciones', $observaciones);
        $insert = $stmt->execute();
        if ($insert == false) {
            print_r($stmt->errorInfo());
        }
        return $insert;
    }

    public function getCliente()
    {
        $pdo = parent::conexion();
        $cotizaciones = array();
        $stmt = $pdo->prepare("SELECT idpaciente, nombre
                                FROM paciente
                                WHERE estado = 1 
                                ORDER BY idpaciente DESC");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cotizaciones[] = $resultado;
        }
        return $cotizaciones;
    }

    public function getConsulta($idPaciente)
    {
        $pdo = parent::conexion();
        $cotizaciones = array();
        $stmt = $pdo->prepare("SELECT c.*, p.nombre AS nombre 
        FROM clinica.consulta c 
        INNER JOIN clinica.paciente p ON p.idpaciente = c.idpaciente 
        WHERE c.estado = 1 AND p.idpaciente = :idPaciente
        ORDER BY fechaconsulta DESC;");
        $stmt->bindParam(":idPaciente", $idPaciente, PDO::PARAM_INT);
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cotizaciones[] = $resultado;
        }
        return $cotizaciones;
    }

    public function getPacientesConsulta()
    {
        $pdo = parent::conexion();
        $cotizaciones = array();
        $stmt = $pdo->prepare("SELECT DISTINCT c.idpaciente, p.nombre AS nombre 
        FROM clinica.consulta c 
        INNER JOIN clinica.paciente p ON p.idpaciente = c.idpaciente 
        WHERE p.estado = 1
        ORDER BY c.idpaciente DESC;");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cotizaciones[] = $resultado;
        }
        return $cotizaciones;
    }

    public function getConsultaPorID($idConsulta)
    {
        $pdo = parent::conexion();
        $cotizaciones = array();
        $stmt = $pdo->prepare("SELECT c.*, p.nombre AS nombre 
        FROM clinica.consulta c 
        INNER JOIN clinica.paciente p 
        ON p.idpaciente = c.idpaciente 
        WHERE c.idconsulta = :idConsulta;");
        $stmt->bindParam(':idConsulta', $idConsulta);
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cotizaciones[] = $resultado;
        }
        return $cotizaciones;
    }

    public function updateConsulta($fechaConsulta, $observaciones, $usuarioModifica, $idConsulta)
    {
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("UPDATE clinica.consulta SET fechaconsulta = :fechaConsulta,
                                                             observaciones = :observaciones, 
                                                             fechamodificacion = NOW(), 
                                                             usuariomodificacion = :usuarioModifica 
                                                             WHERE idconsulta = :idConsulta;");
        $stmt->bindParam(':fechaConsulta', $fechaConsulta);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':usuarioModifica', $usuarioModifica);
        $stmt->bindParam(':idConsulta', $idConsulta);
        $update = $stmt->execute();
        if ($update == false) {
            print_r($stmt->errorInfo());
        }
        return $update;
    }

    public function getPacienteporID($idPaciente)
    {
        $pdo = parent::conexion();
        $cotizaciones = array();
        $stmt = $pdo->prepare("SELECT DISTINCT c.*, a.*, ef.*, tm.*, ed.*, aten.*, dm.*, ad.*, pos.*, md.*
        FROM clinica.paciente c
        INNER JOIN clinica.antecedentes a ON c.idpaciente = a.id_paciente
        INNER JOIN clinica.evaluaciones_fisioterapeutas ef ON c.idpaciente = ef.id_paciente
        INNER JOIN clinica.tono_muscular tm ON c.idpaciente = tm.id_paciente
        INNER JOIN clinica.escala_desarrollo ed ON c.idpaciente = ed.id_paciente
        INNER JOIN clinica.atencion aten ON c.idpaciente = aten.id_paciente
        INNER JOIN clinica.destrezas_manuales dm ON c.idpaciente = dm.id_paciente
        INNER JOIN clinica.actividad_diaria ad ON c.idpaciente = ad.id_paciente
        INNER JOIN clinica.postura pos ON c.idpaciente = pos.id_paciente
        INNER JOIN clinica.marcha_desplazamiento md ON c.idpaciente = md.id_paciente
        WHERE c.idpaciente = :idPaciente;");
        $stmt->bindParam(':idPaciente', $idPaciente);
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cotizaciones[] = $resultado;
        }
        return $cotizaciones;
    }

    public function anularConsulta($idConsulta){
        $pdo = parent::conexion();
        try {
            $stmt = $pdo->prepare("UPDATE clinica.consulta SET estado = 0 WHERE idconsulta = :idconsulta");
            $stmt->bindParam(':idconsulta', $idConsulta);
            
            $update = $stmt->execute();
            
            if ($update == false) {
                // Error en la actualización
                return false;
            }
            
            // Éxito en la actualización
            return true;
        } catch (PDOException $e) {
            // Captura la excepción y devuelve false en caso de error
            return false;
        }
    }
    
    

}