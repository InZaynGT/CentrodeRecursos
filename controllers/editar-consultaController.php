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
            $metodoAnticonceptivo = $_POST['ag_Metodos'];
            $fechFur = str_replace('/','-',$_POST['ag_Fur']);
            $fur = new DateTime($fechFur);
            $partos = $_POST['ao_Partos'];
            $cesareas = $_POST['ao_Cesareas'];
            $abortos = $_POST['ao_Abortos'];
            $hv = $_POST['ao_Hv'];
            $hm = $_POST['ao_Hm'];
            $obitoFetal = $_POST['ao_ObitoFetal'];
            $fechUP = str_replace('/','-',$_POST['ao_ultimoPapanico']);
            $ultimoPapanicolaou = new DateTime($fechUP);
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
            $abdomen = $_POST['ef_abdomen'];
            $caderaYColumna = $_POST['ef_caderayColumna'];
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
            $fref = str_replace('/','-',$_POST['fechaReferencia']);
            $fechaReferencia = new DateTime($fref);
            $img = isset($_POST['imageColposcopia']) ? $_POST['imageColposcopia']: NULL ;

            if(empty($img)){
                $filename[0]="";
    
            }
            else{
                //subir la foto al directorio
            $folderPath = "/xampp/uploads/colposcopia/";
    
            $image_parts = explode(";base64",$img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
    
            $image_base64 = base64_decode($image_parts[1]);
            $filename = uniqid("",false).'.png';
    
            $file = $folderPath.$filename;
            file_put_contents($file,$image_base64);
            $filename = explode('.',$filename);
                
            }

            //información de ultrasonido.
            $tiposUltrasonido = isset($_POST['tipoUltrasonido']) ?  $_POST['tipoUltrasonido'] : NULL;
            $valorUltrasonido = isset($_POST['valorUltrasonido']) ? $_POST['valorUltrasonido'] : NULL;

            require_once 'models/consultaModel.php';
            $c = new Consulta();
            //Actualiza el encabezado de la consulta
            //idItem es el valor que va en la URL, identificador de la consulta
            $c->updateConsultaEnc($idPaciente, $idItem,$idUsuario);

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
                $metodoAnticonceptivo,
                $fur->format('Y-m-d'),
                $partos,
                $cesareas,
                $abortos,
                $hv,
                $hm,
                $obitoFetal,
                $ultimoPapanicolaou->format('Y-m-d'),
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
                $abdomen,
                $caderaYColumna,
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
                $fechaReferencia->format('Y-m-d'),
                $filename[0]
            );

            if (!empty($tiposUltrasonido)) {

                //Se eliminan los ultradonidos para volver a insertarlos
                $c->deleteUltrasonidos($idConsulta);

                //Guarda el/los ultrasonidos de la consulta
                for ($i = 0; $i <= count($tiposUltrasonido) - 1; $i++) {
                    $tipoUlt = $tiposUltrasonido[$i];
                    $valorUlt = $valorUltrasonido[$i];

                    $c->setUltrasonido($idConsulta, $tipoUlt,$valorUlt, $i);
                }

                unset($c);

                echo '
				<script>
					window.location.href = "'.BASE_DIR.'editar-consulta/'.$idItem.'-'.$idPaciente.'";
				</script>
			';

            }else {
                if (!empty($tiposUltrasonido && $valorUltrasonido) ){
                $c->setUltrasonido($idConsulta, $tiposUltrasonido,$valorUltrasonido, 0);
                unset($c);
                }else{
                    exit;

                }

                echo '
				<script>
                window.location.href = "'.BASE_DIR.'editar-consulta/'.$idItem.'-'.$idPaciente.'";
				</script>';
            }
        } else {
            exit;
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



require_once 'views/header.php';
require_once 'views/editar-consulta.php';
require_once 'views/footer.php';
}