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

    //funcion para llenar la vista de editar consulta
    public function getDolorPorId($idDolor){
		$pdo = parent::conexion();
		$cantidadDolor = array();
		$stmt = $pdo->prepare("SELECT codigo, nombre FROM generico  WHERE tipo = 'CANT' and codigo=:idDolor;");
        $stmt->bindParam(':idDolor',$idDolor);
        $stmt->execute();
		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$cantidadDolor[] = $resultado;
		}

		return $cantidadDolor[0];

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
        $stmt = $pdo->prepare("SELECT codigo, nombre FROM generico  WHERE tipo = 'ULT' ORDER BY codigo ASC;");
        $stmt->execute();

        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $tipos[] = $resultado;
        }

        return $tipos;
    }

    //funcion que obtiene los ultrasonidos asignados a una consulta, se usa en editarconsulta
    public function getUtrasonidosConsulta($idConsulta){
        $pdo = parent :: conexion();
        $ultrasonidos = array();
        $stmt = $pdo->prepare("SELECT u.tipo codigo, g.nombre, u.valor FROM ultrasonido u
        INNER JOIN generico g on g.codigo = u.tipo
        WHERE u.tipo = g.codigo and g.tipo = 'ULT' and u.idconsulta = :idConsulta");
        $stmt->bindParam(':idConsulta', $idConsulta);
        $stmt->execute();

        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $ultrasonidos[] = $resultado;
        }

        return $ultrasonidos;

    }

    public function getTiposPeso(){
        $pdo = parent :: conexion();
        $tipos = array();
        $stmt = $pdo->prepare("SELECT codigo, nombre FROM generico  WHERE tipo = 'PES'  ORDER BY codigo ASC;");
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
            die();
        }


    }

    //actualiza la información de los antecedentes
    public function updateAntecedentes($idConsulta,$motivo,$historiaEnfermedad,$ageMedicos,$ageQuirurgicos,$ageAlergicos,$ageTraumaticos,
    $ageViciosYManias, $agiEmbarazos,$agiMenarquia,$agiCiclo,$agiDuracion,$agiDolor, $agiEts, $agiEmbarazada, $agiSemanasEmbarazo,
    $agiMetodoAnticonceptivo, $agiFur, $aobPartos,$aobCesareas, $aobAbortos,$aobHv,$aobHm,$aobObitoFetal,$aobUltimoPapanicolaou,
    $aobCantidadPananicolaou, $aobParejasSexuales,$aobInicioVidaSexual,$aobParejasSexualesPareja){
        $pdo =parent:: conexion();
        $stmt = $pdo->prepare("call update_antecedentes(:idconsulta,:motivo,:historia_enfermedad,:age_medicos,:age_quirurgicos,:age_alergicos,
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
        $stmt->bindParam(':agi_embarazada', $agiEmbarazada,PDO::PARAM_BOOL);
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
            die();
        }


    }

    public function setExamenFisico($idConsulta,$pa,$temperatura,$pulso,$spo,$pesoLibras,$pesoOnzas,$estaturaMetros,$estaturaCentimetros,
    $pesoCalidad,$fr,$pielYMucosas,$cabezaYCuello,$torax,$abdomen,$caderaYColumna,$ginecologico,$impresionClinca){
        $pdo =parent:: conexion();
        $stmt = $pdo->prepare("call insertar_examen_fisico(:idconsulta,:pa,:temperatura,:pulso,:spo,:pesolibras,:pesoonzas,:estaturametros,
        :estaturacentimetros, :pesocalidad,:fr,:pielymucosas,:cabezaycuello,:torax, :abdomen,:caderaycolumna,:ginecologico,:impresionclinica);");
        $stmt->bindParam(':idconsulta', $idConsulta);
        $stmt->bindParam(':pa',$pa);
        $stmt->bindParam(':temperatura',$temperatura);
        $stmt->bindParam(':pulso', $pulso);
        $stmt->bindParam(':spo',$spo);
        $stmt->bindParam(':pesolibras',$pesoLibras);
        $stmt->bindParam(':pesoonzas',$pesoOnzas);
        $stmt->bindParam(':estaturametros', $estaturaMetros);
        $stmt->bindParam(':estaturacentimetros', $estaturaCentimetros);
        $stmt->bindParam(':pesocalidad',$pesoCalidad);
        $stmt->bindParam(':fr',$fr);
        $stmt->bindParam(':pielymucosas', $pielYMucosas);
        $stmt->bindParam(':cabezaycuello', $cabezaYCuello);
        $stmt->bindParam(':torax', $torax);
        $stmt->bindParam(':abdomen', $abdomen);
        $stmt->bindParam(':caderaycolumna', $caderaYColumna);
        $stmt->bindParam(':ginecologico',$ginecologico);
        $stmt->bindParam(':impresionclinica', $impresionClinca);
        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }

    
    }

    //actualiza los datos del examen físico
    public function updateExamenFisico($idConsulta,$pa,$temperatura,$pulso,$spo,$pesoLibras,$pesoOnzas,$estaturaMetros,$estaturaCentimetros,
    $pesoCalidad,$fr,$pielYMucosas,$cabezaYCuello,$torax,$abdomen,$caderaYColumna,$ginecologico,$impresionClinca){
        $pdo =parent:: conexion();
        $stmt = $pdo->prepare("call update_examen_fisico(:idconsulta,:pa,:temperatura,:pulso,:spo,:pesolibras,:pesoonzas,:estaturametros,
        :estaturacentimetros, :pesocalidad,:fr,:pielymucosas,:cabezaycuello,:torax, :abdomen,:caderaycolumna,:ginecologico,:impresionclinica);");
        $stmt->bindParam(':idconsulta', $idConsulta);
        $stmt->bindParam(':pa',$pa);
        $stmt->bindParam(':temperatura',$temperatura);
        $stmt->bindParam(':pulso', $pulso);
        $stmt->bindParam(':spo',$spo);
        $stmt->bindParam(':pesolibras',$pesoLibras);
        $stmt->bindParam(':pesoonzas',$pesoOnzas);
        $stmt->bindParam(':estaturametros', $estaturaMetros);
        $stmt->bindParam(':estaturacentimetros', $estaturaCentimetros);
        $stmt->bindParam(':pesocalidad',$pesoCalidad);
        $stmt->bindParam(':fr',$fr);
        $stmt->bindParam(':pielymucosas', $pielYMucosas);
        $stmt->bindParam(':cabezaycuello', $cabezaYCuello);
        $stmt->bindParam(':torax', $torax);
        $stmt->bindParam(':abdomen', $abdomen);
        $stmt->bindParam(':caderaycolumna', $caderaYColumna);
        $stmt->bindParam(':ginecologico',$ginecologico);
        $stmt->bindParam(':impresionclinica', $impresionClinca);
        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }

    
    }

    public function setColposcopia($idConsulta,$referidoPor,$mucosaOrig,$ectopia,$zonaTransformacion,$zonaTransformacionAtipica,
    $epitelioAceitoPos,$leucoplasia,$puntuacion,$mosaico,$mosaicoPuntuacion,$atipiasVasculares,$carcinoma,$condiloma,$cervitis,
    $atrofias, $otros, $impresionColposcopica,$unionEscamoColumnar,$resHB,$resultHistBiopsia,$correlacion,$sugerencia,$tratamientoAdoptado,
    $referidoA,$fechaReferencia, $foto){
        $pdo =parent:: conexion();
        $stmt = $pdo->prepare("call insertar_colposcopia(:idconsulta,:referidopor,:mucosaorig,:ectopia,:zonatransformacion,:zonatransformacionAtip,
        :epitelioAceitoPos, :leucoplasia, :puntuacion, :mosaico, :mosaicopuntuacion, :atipiasvasculares,:carcicoma, :condiloma, :cervitis, 
        :atrofias, :otros, :impresioncolposcopica,:unionescamocolumnar,:resHB, :resulthistbiopsia, :correlacion, :sugerencia, :tratamientoadoptado,
        :referidoA,:fechareferencia,:foto);");
        $stmt->bindParam(':idconsulta', $idConsulta);
        $stmt->bindParam(':referidopor', $referidoPor);
        $stmt->bindParam(':mucosaorig', $mucosaOrig,PDO::PARAM_BOOL);
        $stmt->bindParam(':ectopia', $ectopia,PDO::PARAM_BOOL);
        $stmt->bindParam(':zonatransformacion', $zonaTransformacion,PDO::PARAM_BOOL);
        $stmt->bindParam(':zonatransformacionAtip', $zonaTransformacionAtipica,PDO::PARAM_BOOL);
        $stmt->bindParam(':epitelioAceitoPos', $epitelioAceitoPos,PDO::PARAM_BOOL);
        $stmt->bindParam(':leucoplasia', $leucoplasia,PDO::PARAM_BOOL);
        $stmt->bindParam(':puntuacion', $puntuacion,PDO::PARAM_BOOL);
        $stmt->bindParam(':mosaico', $mosaico,PDO::PARAM_BOOL);
        $stmt->bindParam(':mosaicopuntuacion', $mosaicoPuntuacion,PDO::PARAM_BOOL);
        $stmt->bindParam(':atipiasvasculares', $atipiasVasculares,PDO::PARAM_BOOL);
        $stmt->bindParam(':carcicoma', $carcinoma,PDO::PARAM_BOOL);
        $stmt->bindParam(':condiloma', $condiloma,PDO::PARAM_BOOL);
        $stmt->bindParam(':cervitis', $cervitis,PDO::PARAM_BOOL);
        $stmt->bindParam(':atrofias', $atrofias,PDO::PARAM_BOOL);
        $stmt->bindParam(':otros', $otros,PDO::PARAM_BOOL);
        $stmt->bindParam(':impresioncolposcopica', $impresionColposcopica);
        $stmt->bindParam(':unionescamocolumnar', $unionEscamoColumnar);
        $stmt->bindParam(':resHB', $resHB);
        $stmt->bindParam(':resulthistbiopsia', $resultHistBiopsia);
        $stmt->bindParam(':correlacion', $correlacion);
        $stmt->bindParam(':sugerencia', $sugerencia);
        $stmt->bindParam(':tratamientoadoptado', $tratamientoAdoptado);
        $stmt->bindParam(':referidoA', $referidoA);
        $stmt->bindParam(':fechareferencia', $fechaReferencia);
        $stmt->bindParam(':foto', $foto);

        $result = $stmt->execute();

        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }

    }

    //actualiza los datos de la colposcopia
    public function updateColposcopia($idConsulta,$referidoPor,$mucosaOrig,$ectopia,$zonaTransformacion,$zonaTransformacionAtipica,
    $epitelioAceitoPos,$leucoplasia,$puntuacion,$mosaico,$mosaicoPuntuacion,$atipiasVasculares,$carcinoma,$condiloma,$cervitis,
    $atrofias, $otros, $impresionColposcopica,$unionEscamoColumnar,$resHB,$resultHistBiopsia,$correlacion,$sugerencia,$tratamientoAdoptado,
    $referidoA,$fechaReferencia, $foto){
        $pdo =parent:: conexion();
        $stmt = $pdo->prepare("call update_colposcopia(:idconsulta,:referidopor,:mucosaorig,:ectopia,:zonatransformacion,:zonatransformacionAtip,
        :epitelioAceitoPos, :leucoplasia, :puntuacion, :mosaico, :mosaicopuntuacion, :atipiasvasculares,:carcicoma, :condiloma, :cervitis, 
        :atrofias, :otros, :impresioncolposcopica,:unionescamocolumnar,:resHB, :resulthistbiopsia, :correlacion, :sugerencia, :tratamientoadoptado,
        :referidoA,:fechareferencia,:foto);");
        $stmt->bindParam(':idconsulta', $idConsulta);
        $stmt->bindParam(':referidopor', $referidoPor);
        $stmt->bindParam(':mucosaorig', $mucosaOrig,PDO::PARAM_BOOL);
        $stmt->bindParam(':ectopia', $ectopia,PDO::PARAM_BOOL);
        $stmt->bindParam(':zonatransformacion', $zonaTransformacion,PDO::PARAM_BOOL);
        $stmt->bindParam(':zonatransformacionAtip', $zonaTransformacionAtipica,PDO::PARAM_BOOL);
        $stmt->bindParam(':epitelioAceitoPos', $epitelioAceitoPos,PDO::PARAM_BOOL);
        $stmt->bindParam(':leucoplasia', $leucoplasia,PDO::PARAM_BOOL);
        $stmt->bindParam(':puntuacion', $puntuacion,PDO::PARAM_BOOL);
        $stmt->bindParam(':mosaico', $mosaico,PDO::PARAM_BOOL);
        $stmt->bindParam(':mosaicopuntuacion', $mosaicoPuntuacion,PDO::PARAM_BOOL);
        $stmt->bindParam(':atipiasvasculares', $atipiasVasculares,PDO::PARAM_BOOL);
        $stmt->bindParam(':carcicoma', $carcinoma,PDO::PARAM_BOOL);
        $stmt->bindParam(':condiloma', $condiloma,PDO::PARAM_BOOL);
        $stmt->bindParam(':cervitis', $cervitis,PDO::PARAM_BOOL);
        $stmt->bindParam(':atrofias', $atrofias,PDO::PARAM_BOOL);
        $stmt->bindParam(':otros', $otros,PDO::PARAM_BOOL);
        $stmt->bindParam(':impresioncolposcopica', $impresionColposcopica);
        $stmt->bindParam(':unionescamocolumnar', $unionEscamoColumnar);
        $stmt->bindParam(':resHB', $resHB);
        $stmt->bindParam(':resulthistbiopsia', $resultHistBiopsia);
        $stmt->bindParam(':correlacion', $correlacion);
        $stmt->bindParam(':sugerencia', $sugerencia);
        $stmt->bindParam(':tratamientoadoptado', $tratamientoAdoptado);
        $stmt->bindParam(':referidoA', $referidoA);
        $stmt->bindParam(':fechareferencia', $fechaReferencia);
        $stmt->bindParam(':foto', $foto);

        $result = $stmt->execute();

        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }

    }

    public function setUltrasonido($idConsulta,$tipo,$valor,$nroReg){
        $pdo =parent:: conexion();
        $stmt = $pdo->prepare("call insertar_ultrasonido(:idconsulta,:tipo,:valor,:nro_reg);");
        $stmt->bindParam(':idconsulta', $idConsulta);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':nro_reg', $nroReg);

        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }

    }

    //Esta funcion se usa para saber que ultrasonidos tiene la consulta e insertar las fotos
    public function getUltrasonidos($idConsulta){
        $ultrasonidos =  array();
        $pdo =parent:: conexion();
        $stmt = $pdo->prepare("SELECT idultrasonido, g.nombre  FROM ultrasonido u
        INNER JOIN generico g on g.codigo = u.tipo and g.tipo = 'ULT'
        WHERE u.idconsulta=:idconsulta_;");
        $stmt->bindParam(':idconsulta_', $idConsulta);
        $resultado = $stmt->execute();
        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $ultrasonidos[] = $resultado;
        }

        return $ultrasonidos;

    }

    //Guarda el identificador de la imagen del ultrasonido
    public function updateUltrasonidoFoto($idUltrasonido,$nombreArchivo){
        $pdo  = parent::conexion();
        $stmt = $pdo->prepare("call update_ultrasonido_foto(:idUltrasonido, :imgUltrasonido);");
        $stmt->bindParam(':idUltrasonido',$idUltrasonido);
        $stmt->bindParam(':imgUltrasonido',$nombreArchivo);
        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }
        return $result;

    }

    public function getUltrasonidoFotos($idConsulta){
        $fotos = array();
        $pdo  = parent::conexion();
        $stmt = $pdo->prepare("call obtener_foto_ultrasonido(:idConsulta);");
        $stmt->bindParam(':idConsulta',$idConsulta);
        $resultado = $stmt->execute();
        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $fotos[] = $resultado;
        }

        return $fotos;

    }

    public function deleteUltrasonidos($idConsulta){
        $pdo = parent:: conexion();
        $stmt = $pdo->prepare("call delete_ultrasonidos(:idconsulta);");
        $stmt->bindParam(':idconsulta', $idConsulta);

        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }

    }

    public function getConsultas(){
        $pdo=parent::conexion();
        $consultas = array();
        $stmt = $pdo->prepare("SELECT * FROM vista_consultas;");
        $result = $stmt->execute();
        while($resultado = $stmt->fetch(PDO::FETCH_ASSOC)){
            $consultas[] = $resultado;
        }

        return $consultas;

        if(!$result){
            print_r($stmt->errorInfo());
        }


    }

    public function getConsultaPorID($idConsulta){
        $pdo=parent::conexion();
        $consulta = array();
        $stmt = $pdo->prepare("SELECT * FROM mostrar_info_consulta WHERE idconsulta= :idConsulta;");
        $stmt->bindParam(':idConsulta',$idConsulta);
        $result = $stmt->execute();

        while($resultado=$stmt->fetch(PDO::FETCH_ASSOC)){
            $consulta[]=$resultado;
        }
            if(!$result){
                print_r($stmt->errorInfo());
                die();
            }else {
                return $consulta[0];

            }

    }

    public function updateConsultaEnc($idPaciente,$idUsuario,$idConsulta){
        $pdo=parent::conexion();
        $stmt = $pdo->prepare("call update_consulta(:idPaciente,:idUsuario,:idConsulta);");
        $stmt->bindParam(':idPaciente',$idPaciente);
        $stmt->bindParam(':idUsuario',$idUsuario);
        $stmt->bindParam(':idConsulta', $idConsulta);
        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }
        return $result;
    }

    //Cambia el estado de una consulta para que no aparezca en el listado
    public function deleteConsulta($idConsulta){
        $pdo=parent::conexion();
        $stmt = $pdo->prepare("call delete_consulta(:idConsulta);");
        $stmt->bindParam(':idConsulta', $idConsulta);
        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
            die();
        }
        return $result;
    }


}