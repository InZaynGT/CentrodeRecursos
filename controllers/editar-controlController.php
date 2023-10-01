<?php

if (!empty($_POST)) {

    if ($_POST > 1 && isset($_POST['fecha']) && isset($_POST['idPaciente']) && isset($_POST['nombrePaciente'])) {

        $idConsulta = $idItem;
        $idPaciente = $_POST['idPaciente'];
        $fechaConsulta = new DateTime($_POST['fecha']);
        $idUsuario = $_SESSION['user']['id'];
        $motivo = $_POST['ante_Motivo'];
        $historiaEnfermedad = $_POST['ante_Historia'];
        $antecedentesMedicos = $_POST['ante_Medicos'];
        $antecedentesQuirurgicos = $_POST['ante_Quirurgicos'];
        $antecedentesAlergicos = $_POST['ante_Alergicos'];
        $antecedentesTraumaticos = $_POST['ante_Traumaticos'];
        $viciosYManias = $_POST['viciosymanias'];
        $embarazos = $_POST['ag_Embarazos'];
        $menarquia = $_POST['ag_Menarquia'];
        $ciclo = $_POST['ag_Ciclo'];
        $duracion = $_POST['ag_Duracion'];
        $dolor = $_POST['ag_Dolor'];
        $ets = $_POST['ag_Ets'];
        $esEmbarazada = isset($_POST['chkEmbarazoSi']) ? True : False;
        $semanasEmbarazo = $_POST['ag_SemanasEmbarazo'];
        $fechaParto = empty($_POST['ag_FechaParto']) ? '' : new DateTime($_POST['ag_FechaParto']);
        $fechaPartoInsert = empty($fechaParto) ? "" : $fechaParto->format('Y-m-d');
        $metodoAnticonceptivo = $_POST['ag_Metodos'];
        $fechFur = str_replace('/', '-', $_POST['ag_Fur']);
        $fur1 = empty($fechFur) ? "" : new DateTime($fechFur);
        $fur= empty($fur1) ? "": $fur1->format('Y-m-d');
        $histerectomias = isset($_POST['chkHisteYes']) ? True : False;

        //Antecedentes obstetricos
        $partos = $_POST['ao_Partos'];
        $cesareas = $_POST['ao_Cesareas'];
        $abortos = $_POST['ao_Abortos'];
        $hv = $_POST['ao_Hv'];
        $hm = $_POST['ao_Hm'];
        $obitoFetal = $_POST['ao_ObitoFetal'];
        $legrados = $_POST['ao_Legrados'];
        $ameu = $_POST['ao_ameu'];
        $fechUP = str_replace('/', '-', $_POST['ao_ultimoPapanico']);
        $ultimoPapanicolaou1 = empty($fechUP) ? "": new DateTime($fechUP);
        $ultimoPapanicolaou = empty($ultimoPapanicolaou1) ? "" : $ultimoPapanicolaou1->format('Y-m-d');
        $cantidadPapanicolaou = $_POST['ao_cantidadPapanico'];
        $parejassexuales = $_POST['ao_Ps'];
        $inicioVidaSexual = $_POST['ao_Ivs'];
        $parejasSexualesPareja = $_POST['ao_Psp'];

        //Examen fisico
        $pA = $_POST['ef_pa'];
        $temperatura = $_POST['ef_temperatura'];
        $pulso = $_POST['ef_pulso'];
        $spo = $_POST['ef_spo'];
        $libras = $_POST['ef_libras'];
        $onzas = $_POST['ef_onzas'];
        $metros = $_POST['ef_metros'];
        $centimetros = $_POST['ef_centimetros'];
        $peso = $_POST['ef_peso'];
        $fr = $_POST['ef_fr'];
        $pielYMucosas = $_POST['ef_pielYMucosas'];
        $cabezaYCuello = $_POST['ef_cabezayCuello'];
        $torax = $_POST['ef_torax'];
        $pulmones = $_POST['ef_pulmones'];
        $corazon = $_POST['ef_corazon'];
        $abdomen = $_POST['ef_abdomen'];
        $caderaYColumna = $_POST['ef_caderayColumna'];
        $extremidades = $_POST['ef_extremidades'];
        $ginecologico = $_POST['ef_ginecologico'];
        $impresionClinica = $_POST['ef_impresionClinica'];

        //colposcopia
        $referidoPor = $_POST['referidoPor'];
        $mucosaOriginaria = isset($_POST['mucosa']) ? True : False;
        $ectopia = isset($_POST['ectopia']) ? True : False;
        $zonaTransformacion = isset($_POST['zonaTransf']) ? True : False;
        $zonaTransformacionAtipica = isset($_POST['zonaTransfAt']) ? True : False;
        $epitelioAceticoPos = isset($_POST['epitAcetPos']) ? True : False;
        $leucoplasia = isset($_POST['leucoplasia']) ? True : False;
        $puntuacion = isset($_POST['puntuacion']) ? True : False;
        $mosaico =  isset($_POST['mosaico']) ? True : False;
        $mosaicoPuntua = isset($_POST['mosaicoPunt']) ? True : False;
        $atipiasVasc = isset($_POST['atipiasVasc']) ? True : False;
        $carcinoma = isset($_POST['carcinoma']) ? True : False;
        $condiloma = isset($_POST['condiloma']) ? True : False;
        $cervitis = isset($_POST['cervitis']) ? True : False;
        $atrofias = isset($_POST['atrofias']) ? True : False;
        $otros = isset($_POST['otros']) ? True : False;
        $impresionColpos = $_POST['impresionCol'];
        $unionEscamoColumnar = $_POST['unionEscaCol'];
        $resHB = $_POST['resHB'];
        $resHisBiopsia = $_POST['resulHistBio'];
        $correlacion = $_POST['correlacion'];
        $sugerencia = $_POST['sugerencia'];
        $tratamientoAdoptado = $_POST['tratamientoAdopt'];
        $referidoA = $_POST['referidoA'];
        $fref = str_replace('/', '-', $_POST['fechaReferencia']);
        $fechaReferencia1 = empty($fref) ? "" : new DateTime($fref);
        $fechaReferencia = empty($fechaReferencia1) ? "" : $fechaReferencia1->format('Y-m-d');
        $img = isset($_POST['imageColposcopia']) ? $_POST['imageColposcopia'] : NULL;

        if (empty($img)) {
            $filename[0] = "";
        } else {
            //subir la foto al directorio
            $folderPath = "/xampp/uploads/colposcopia/";

            $image_parts = explode(";base64", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);
            $filename = uniqid("", false) . '.png';

            $file = $folderPath . $filename;
            file_put_contents($file, $image_base64);
            $filename = explode('.', $filename);
        }

        //información de ultrasonido.
        $tiposUltrasonido = isset($_POST['tipoUltrasonido']) ?  $_POST['tipoUltrasonido'] : NULL;
        $valorUltrasonido = isset($_POST['valorUltrasonido']) ? $_POST['valorUltrasonido'] : NULL;

        //información de la receta
        $idMedicamento = isset($_POST['idMedicamento']) ? $_POST['idMedicamento'] : NULL;
        $dosificacion = isset($_POST['dosificacion']) ? $_POST['dosificacion']: NULL;
        $uso = isset($_POST['uso']) ? $_POST['uso']: NULL;

        $tiposLaboratorio = isset($_POST['tipoLaboratorio']) ? $_POST['tipoLaboratorio'] : NULL;

        require_once 'models/consultaModel.php';
        $c = new Consulta();
        //Actualiza el encabezado de la consulta
        //idItem es el valor que va en la URL, identificador de la consulta
        $c->updateConsultaEnc($idPaciente, $idItem, $idUsuario);

        //Guarda los antecedentes de la consulta
        $c->updateAntecedentes(
            $idConsulta,
            $motivo,
            $historiaEnfermedad,
            $antecedentesMedicos,
            $antecedentesQuirurgicos,
            $antecedentesAlergicos,
            $antecedentesTraumaticos,
            $viciosYManias,
            $embarazos,
            $menarquia,
            $ciclo,
            $duracion,
            $dolor,
            $ets,
            $esEmbarazada,
            $semanasEmbarazo,
            $fechaPartoInsert,
            $metodoAnticonceptivo,
            $fur,
            $histerectomias,
            $partos,
            $cesareas,
            $abortos,
            $hv,
            $hm,
            $obitoFetal,
            $legrados,
            $ameu,
            $ultimoPapanicolaou,
            $cantidadPapanicolaou,
            $parejassexuales,
            $inicioVidaSexual,
            $parejasSexualesPareja
        );

        //Guarda los datos del examen fisico de la consulta
        $c->updateExamenFisico(
            $idConsulta,
            $pA,
            $temperatura,
            $pulso,
            $spo,
            $libras,
            $onzas,
            $metros,
            $centimetros,
            $peso,
            $fr,
            $pielYMucosas,
            $cabezaYCuello,
            $torax,
            $pulmones,
            $corazon,
            $abdomen,
            $caderaYColumna,
            $extremidades,
            $ginecologico,
            $impresionClinica
        );

        //Guarda los datos de la colposcopia de la consulta
        $c->updateColposcopia(
            $idConsulta,
            $referidoPor,
            $mucosaOriginaria,
            $ectopia,
            $zonaTransformacion,
            $zonaTransformacionAtipica,
            $epitelioAceticoPos,
            $leucoplasia,
            $puntuacion,
            $mosaico,
            $mosaicoPuntua,
            $atipiasVasc,
            $carcinoma,
            $condiloma,
            $cervitis,
            $atrofias,
            $otros,
            $impresionColpos,
            $unionEscamoColumnar,
            $resHB,
            $resHisBiopsia,
            $correlacion,
            $sugerencia,
            $tratamientoAdoptado,
            $referidoA,
            $fechaReferencia,
            $filename[0]
        );

        if(!empty($idMedicamento)){
            require_once 'models/recetaModel.php';
            $r = new Receta();
            
            $r->deleteMedicamentos($idConsulta);

            for($i = 0; $i <= count($idMedicamento) - 1; $i++){
                $idMed = $idMedicamento[$i];
                $dosis = $dosificacion[$i];
                $us = $uso[$i];
                
                $r->setReceta($idConsulta, $idPaciente, $idMed,$dosis,$us);
            }
            unset($r);
           
        }
        if(!empty($tiposLaboratorio)){
            require_once 'models/laboratorioModel.php';
            $lab = new Laboratorio();

            $lab->deleteLaboratorios($idConsulta);

            for($i = 0; $i <= count($tiposLaboratorio) - 1; $i++){
                $idLab = $tiposLaboratorio[$i];
                
                $lab->setLaboratorioConsulta($idConsulta, $idLab,$idPaciente,$i);
            }
            unset($lab);


        }

        if (!empty($tiposUltrasonido)) {

            //Se eliminan los ultradonidos para volver a insertarlos
            $c->deleteUltrasonidos($idConsulta);

            //Guarda el/los ultrasonidos de la consulta
            for ($i = 0; $i <= count($tiposUltrasonido) - 1; $i++) {
                $idUltrasonido = $tiposUltrasonido[$i];
                $valorUlt = $valorUltrasonido[$i];

                $c->setUltrasonido($idUltrasonido, $idConsulta, $valorUlt, $i);
            }

            unset($c);

            echo '
				<script>
					window.location.href = "' . BASE_DIR . 'editar-control/' . $idItem . '-' . $idPaciente . '";
				</script>
			';
        } else {
            
            if (!empty($tiposUltrasonido && $valorUltrasonido)) {
                $c->setUltrasonido($tiposUltrasonido, $idConsulta, $valorUltrasonido, 0);
                unset($c);
            } else {
                echo '
				<script>
                window.location.href = "' . BASE_DIR . 'editar-control/' . $idItem . '-' . $idPaciente . '";
				</script>';
            }
            echo '
				<script>
                window.location.href = "' . BASE_DIR . 'editar-control/' . $idItem . '-' . $idPaciente . '";
				</script>';
        }
    }
    
    else if (isset($_POST['cargarMedicamentos'])) {
        $idConsulta = $_POST['idConsulta'];
        require_once 'models/recetaModel.php';
        $r = new Receta();
        $medicamentos =  $r->getRecetaDetalle($idConsulta);
        unset($r);

        echo json_encode($medicamentos);
    }

} else {

    require_once 'models/consultaModel.php';
    $con = new Consulta();
    $consulta = $con->getConsultaPorID($idItem);

    $ultrasonidosConsulta = $con->getUtrasonidosConsulta($idItem);

    $idPaciente = $consulta['idpaciente'];
    require_once 'models/pacienteModel.php';
    $conn = new Patient();
    $paciente = $conn->getNombrePacientePorId($idPaciente);
    unset($conn);

    $idDolor = $consulta['agi_dolor'];
    $dolor = $con->getDolor();

    $idEts = $consulta['agi_ets'];
    $ets = $con->getEts();
    $tiposUltrasonido = $con->getTiposUltrasonido();

    $idMetodoA = $consulta['agi_metodoanticonceptivo'];
    $metodosAnticonceptivos = $con->getMetodosAnticonceptivos();

    $idPeso = $consulta['peso_calidad'];
    $tiposPeso = $con->getTiposPeso();
    unset($con);

    require_once 'models/laboratorioModel.php';
    $lab = new Laboratorio();
    $laboratorios = $lab->getLaboratoriosConsulta($idItem);
    $tiposLaboratorio = $lab->getTiposLaboratorio();
    unset($lab);

    require_once 'views/header.php';
    require_once 'views/editar-control.php';
    require_once 'views/footer.php';
}
