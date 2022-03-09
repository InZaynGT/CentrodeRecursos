<?php
require_once 'models/configuracionModel.php';

class Consulta extends Configuracion {

    //funciones para llenar los option en la vista agregar-consulta
    public function getDolor(){
		$pdo = parent::conexion();
		$cantidadDolor = array();
		$stmt = $pdo->prepare("SELECT codigo, nombre FROM generico  WHERE tipo = 'CANT'  ORDER BY codigo ASC;");
        $stmt->execute();
		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$cantidadDolor[] = $resultado;
		}

		return $cantidadDolor;

    }

    public function getEts() {
        $pdo = parent:: conexion();
        $ets = array();
        $stmt = $pdo->prepare("SELECT codigo, nombre FROM generico  WHERE tipo = 'ETS' ORDER BY codigo ASC;");
        $stmt->execute();
        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $ets[] = $resultado;
        }
        return $ets;
    }

    public function getMetodosAnticonceptivos(){
        $pdo = parent :: conexion();
        $metodos = array();
        $stmt = $pdo->prepare("SELECT codigo, nombre FROM generico  WHERE tipo = 'MET'  ORDER BY codigo ASC;");
        $stmt->execute();

        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $metodos[] = $resultado;
        }

        return $metodos;
    }

    public function getTiposUltrasonido(){
        $pdo = parent :: conexion();
        $tipos = array();
        $stmt = $pdo->prepare("SELECT codigo, nombre FROM generico  WHERE tipo = 'ULT';  ORDER BY codigo ASC;");
        $stmt->execute();

        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $tipos[] = $resultado;
        }

        return $tipos;
    }

    public function getTiposPeso(){
        $pdo = parent :: conexion();
        $tipos = array();
        $stmt = $pdo->prepare("SELECT codigo, nombre FROM generico  WHERE tipo = 'PES';  ORDER BY codigo ASC;");
        $stmt->execute();

        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $tipos[] = $resultado;
        }

        return $tipos;
    }

    public function setConsultaEnc($idPaciente,$fechaConsulta,$idUsuario){
        $pdo =parent:: conexion();
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("call insertar_consulta_enc(:idpaciente,:fechaconsulta,:idusuario);");
        $stmt->bindParam(':idpaciente', $idPaciente);
        $stmt->bindParam(':fechaconsulta', $fechaConsulta);
        $stmt->bindParam(':idusuario', $idUsuario);

        try{
            
            $stmt->execute();
            
            $pdo->commit();

            $stmt2= $pdo->prepare("SELECT LAST_INSERT_ID();)");
            $stmt2->execute();
            $idConsulta= $stmt2->fetch(PDO::FETCH_LAZY);
            
        }catch(PDOException $e){
            $pdo->rollBack();
            print "Error: " .$e->getMessage();

        } 
        
        return $idConsulta[0];

    }

    public function setAntecedentes($idConsulta,$motivo,$historiaEnfermedad,$ageMedicos,$ageQuirurgicos,$ageAlergicos,$ageTraumaticos,
    $ageViciosYManias, $agiEmbarazos,$agiMenarquia,$agiCiclo,$agiDuracion,$agiDolor, $agiEts, $agiEmbarazada, $agiSemanasEmbarazo,
    $agiMetodoAnticonceptivo, $agiFur, $aobPartos,$aobCesareas, $aobAbortos,$aobHv,$aobHm,$aobObitoFetal,$aobUltimoPapanicolaou,
    $aobCantidadPananicolaou, $aobParejasSexuales,$aobInicioVidaSexual,$aobParejasSexualesPareja){
        $pdo =parent:: conexion();
        $stmt = $pdo->prepare("call insertar_antecedentes(:idconsulta,:motivo,:historia_enfermedad,:age_medicos,:age_quirurgicos,:age_alergicos,
        :age_traumaticos, :age_viciosymanias, :agi_embarazos, :agi_menarquia, :agi_ciclo, :agi_duracion, :agi_dolor, :agi_ets, :agi_embarazada,
        :agi_semanasembarazo, :agi_metodoanticonceptivo, :agi_fur, :aob_partos, :aob_cesareas, :aob_abortos, :aob_hv, :aob_hm, :aob_obitofetal,
        :aob_ultimopapanicolaou, :aob_cantidadpapanicolaou, :aob_parejassexuales, :aob_iniciovidasexual, :aob_parejassexualespareja);");
        $stmt->bindParam(':idconsulta', $idConsulta);
        $stmt->bindParam(':motivo', $motivo);
        $stmt->bindParam(':historia_enfermedad', $historiaEnfermedad);
        $stmt->bindParam(':age_medicos', $ageMedicos);
        $stmt->bindParam(':age_quirurgicos', $ageQuirurgicos);
        $stmt->bindParam(':age_alergicos', $ageAlergicos);
        $stmt->bindParam(':age_traumaticos', $ageTraumaticos);
        $stmt->bindParam(':age_viciosymanias', $ageViciosYManias);
        $stmt->bindParam(':agi_embarazos', $agiEmbarazos);
        $stmt->bindParam(':agi_menarquia', $agiMenarquia);
        $stmt->bindParam(':agi_ciclo', $agiCiclo);
        $stmt->bindParam(':agi_duracion', $agiDuracion);
        $stmt->bindParam(':agi_dolor', $agiDolor);
        $stmt->bindParam(':agi_ets', $agiEts);
        $stmt->bindParam(':agi_embarazada', $agiEmbarazada);
        $stmt->bindParam(':agi_semanasembarazo', $agiSemanasEmbarazo);
        $stmt->bindParam(':agi_metodoanticonceptivo', $agiMetodoAnticonceptivo);
        $stmt->bindParam(':agi_fur', $agiFur);
        $stmt->bindParam(':aob_partos', $aobPartos);
        $stmt->bindParam(':aob_cesareas', $aobCesareas);
        $stmt->bindParam(':aob_abortos', $aobAbortos);
        $stmt->bindParam(':aob_hv', $aobHv);
        $stmt->bindParam(':aob_hm', $aobHm);
        $stmt->bindParam(':aob_obitofetal', $aobObitoFetal);
        $stmt->bindParam(':aob_ultimopapanicolaou', $aobUltimoPapanicolaou);
        $stmt->bindParam(':aob_cantidadpapanicolaou', $aobCantidadPananicolaou);
        $stmt->bindParam(':aob_parejassexuales', $aobParejasSexuales);
        $stmt->bindParam(':aob_iniciovidasexual', $aobInicioVidaSexual);
        $stmt->bindParam(':aob_parejassexualespareja', $aobParejasSexualesPareja);
        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
        }


    }



}