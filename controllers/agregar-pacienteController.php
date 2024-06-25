<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (count($_POST) == 1  && isset($_POST['buscarPaciente'])) {

        //se obtiene la string que hayan seleccionado luego se saca el codigo del paciente y se busca la info
        $buscar = $_POST['buscarPaciente'];
        $order   = array('-');
        $buscar = str_replace($order, '', $buscar);
        $buscarCodCliente  = explode(" ", $buscar);

        require_once 'models/pacienteModel.php';
        //funcion para obtener los datos del paciente
        $p = new Patient();
        $paciente = $p->getNombrePacientePorId($buscarCodCliente[0]);
        unset($p);

        //funcion para obtener el listado de consultas del paciente
        require_once 'models/consultaModel.php';
        $c = new Consulta();
        $consultas = $c->getConsultasListado($buscarCodCliente[0]);
        unset($c);

        //se manda un json con los datos del paciente y la lista de consultas para mostrar la información en la vista
        $data = array();
        $data['paciente'] = array($paciente);
        $data['consultas'] = array($consultas);

        echo json_encode($data);
    } else if (count($_POST) == 1 && isset($_POST['consulta'])) {
        //con el id de la consulta del historial se busca toda la información de la consulta
        $idConsulta = $_POST['consulta'];

        require_once 'models/consultaModel.php';
        $con = new Consulta();
        $consulta = $con->getConsultaPorID($idConsulta);
        unset($con);

        require_once 'models/ultrasonidoModel.php';
        $ult = new Ultrasonido();
        $ultrasonidos = $ult->getUtrasonidosConsulta($idConsulta);
        unset($utl);

        require_once 'models/recetaModel.php';
        $rec = new Receta();
        $medicamentos = $rec->getRecetaDetalle($idConsulta);
        unset($rec);

        require_once 'models/laboratorioModel.php';
        $lab = new Laboratorio();
        $laboratoriosConsulta = $lab->getLaboratoriosConsulta($idConsulta);
        unset($lab);

        //Con el nombre de la imagen que viene en el array consulta se busca la imagen en el servidor.
        $imagesDir = "/xampp/uploads/colposcopia/";
        $content = @file_get_contents($imagesDir . $consulta['pap_img'] . '.png');
        $content = base64_encode($content);
        
        $data = array();
        $data['consulta'] = array($consulta);
        $data['ultrasonidos'] = array($ultrasonidos);
        $data['laboratorios'] = array($laboratoriosConsulta);
        $data['receta'] = array($medicamentos);
        $data['imagenColpo'] = $content;

        echo json_encode($data);

    } else if (count($_POST) == 1 && isset($_POST['ultimaConsulta'])) {
        //con el id de la consulta del historial se busca toda la información de la consulta
        $idConsulta = $_POST['ultimaConsulta'];

        require_once 'models/consultaModel.php';
        $con = new Consulta();

        $consulta = $con->getAntecedentesConsulta($idConsulta);
        unset($con);

        echo json_encode($consulta);
    } else {
        if ($_POST > 1) {
            //Información Personal
            $nombre = $_POST['nom_paciente'];
            $fecha= new DateTime($_POST['fechadeNacimiento']);
            $fechaFormateada = $fecha->format('Y-m-d H:i:s');
            $idUsuario = $_SESSION['user']['id'];
            $edad = isset($_POST['edad']) && is_numeric($_POST['edad']) ? intval($_POST['edad']) : 0;
            $sexo = $_POST['sexo'];
            $estado_civil= $_POST['estadoCivil'];
            $direccion = $_POST['direccion'];
            $nombre_encargado = $_POST['nom_encargado'];
            $telefono_encargado = $_POST['telefono_encargado'];
            $diagnostico = $_POST['diagnostico'];
            $medicamentos_admin = $_POST['medicamentos_Admin'];
            $medico_Tratante = $_POST['medico_Tratante'];
            $telefono_medico = $_POST['telefono_medico'];
            $examenes_realizados = $_POST['ciclo'];
            $convulsiona = isset($_POST['convulsiona']) ? 1 : 0;
            $usa_protesis = isset($_POST['usa_protesis']) ? 1 : 0;
            $cual_protesis = $_POST['cual_protesis'];
            $historia_enfermedad = $_POST['historia_enfermedad'];
            $observaciones = $_POST['observaciones'];
            require_once 'models/pacienteModel.php';

            //Antecedentes
            $cardiovascular = $_POST['cardiovascular'];
            $pulmonares = $_POST['pulmonares'];
            $digestivos = $_POST['digestivos'];
            $diabetes = $_POST['diabetes'];
            $renales = $_POST['renales'];
            $quirurgicos = $_POST['quirurgicos'];
            $alergicos = $_POST['alergicos'];
            $transfuciones = $_POST['transfuciones'];
            $medicamentos = $_POST['medicamentos'];
            $otros = $_POST['otros_antecedentes'];
            $alcohol = $_POST['alcohol'];
            $tabaquismo = $_POST['tabaquismo'];
            $drogas = $_POST['drogas'];
            $inmunizaciones = $_POST['inmunizaciones'];
            $otros_inmuniz = $_POST['otros_inmuniz'];
            $padre_vivo = isset($_POST['padre_vivo']) ? 1 : 0;
            $padre_enfermedad = $_POST['enfermedad_padre'];
            $madre_vivo = isset($_POST['madre_vivo']) ? 1 : 0;
            $madre_enfermedad = $_POST['enfermedad_madre'];
            $num_hermanos = isset($_POST['num_hermanos']) ? intval($_POST['num_hermanos']) : 0;
            $herman_vive = isset($_POST['herman_vive']) ? intval($_POST['herman_vive']) : 0;
            $enfermedad_herm = $_POST['enfermedad_herm'];
            $observaciones_antecedentes = $_POST['observaciones'];

            // Evaluaciones Fisioterapéuticas
            $dolor_donde = $_POST['localiza_dolor'];
            $dolor_irradiacion = $_POST['irradia_dolor'];
            $tipo_dolor = $_POST['tipo_dolor'];
            $escala_visual_dolor = $_POST['escaladolor'];

            // Tono Muscular
            if (isset($_POST['tono_muscular'])) {$tipo_tono = $_POST['tono_muscular'];} else {$tipo_tono = 0;}
            if (isset($_POST['limit_amplit'])) {$limitacion_artic = 1;} else {$limitacion_artic = 0;}
            $especificacion = $_POST['especifica_tono'];

            // Escala de Desarollo
            if (isset($_POST['control_cuello'])) {$control_cuello = $_POST['control_cuello'];} else {$control_cuello = 0;}
            if (isset($_POST['prono_supino'])) {$rotacion_prono_supino = $_POST['prono_supino'];} else {$rotacion_prono_supino = 0;}
            if (isset($_POST['supino_prono'])) {$rotacion_supino_prono = $_POST['supino_prono'];} else {$rotacion_supino_prono = 0;}
            if (isset($_POST['tronco_superior'])) {$tronco_superior = $_POST['tronco_superior'];} else {$tronco_superior = 0;}
            if (isset($_POST['tronco_inferior'])) {
                $tronco_inferior = $_POST['tronco_inferior']; // Checkbox marcado, asignamos el valor 1
            } else {
                $tronco_inferior = 0; // Checkbox no marcado, asignamos el valor 0
            }
            if (isset($_POST['cuatro_puntos'])) {
                $cuatro_puntos = $_POST['cuatro_puntos']; // Checkbox marcado, asignamos el valor 1
            } else {
                $cuatro_puntos = 0; // Checkbox no marcado, asignamos el valor 0
            }
            if (isset($_POST['pos_sedente'])) {
                $posicion_sedente = $_POST['pos_sedente']; // Checkbox marcado, asignamos el valor 1
            } else {
                $posicion_sedente = 0; // Checkbox no marcado, asignamos el valor 0
            }
            if (isset($_POST['pos_hincado'])) {
                $posicion_hincado = $_POST['pos_hincado']; // Checkbox marcado, asignamos el valor 1
            } else {
                $posicion_hincado = 0; // Checkbox no marcado, asignamos el valor 0
            }
            if (isset($_POST['pos_semihincado'])) {
                $posicion_semihincado = $_POST['pos_semihincado']; // Checkbox marcado, asignamos el valor 1
            } else {
                $posicion_semihincado = 0; // Checkbox no marcado, asignamos el valor 0
            }
            if (isset($_POST['pos_bidepestacion'])) {
                $posicion_bidepestacion = $_POST['pos_bidepestacion']; // Checkbox marcado, asignamos el valor 1
            } else {
                $posicion_bidepestacion = 0; // Checkbox no marcado, asignamos el valor 0
            }

            // Atencion
            if (isset($_POST['localizacion'])) {
                $localizacion = $_POST['localizacion']; // Checkbox marcado, asignamos el valor 1
            } else {
                $localizacion = 0; // Checkbox no marcado, asignamos el valor 0
            }
            if (isset($_POST['fijacion'])) {
                $fijacion = $_POST['fijacion']; // Checkbox marcado, asignamos el valor 1
            } else {
                $fijacion = 0; // Checkbox no marcado, asignamos el valor 0
            }
            if (isset($_POST['seguimiento'])) {
                $seguimiento = $_POST['seguimiento']; // Checkbox marcado, asignamos el valor 1
            } else {
                $seguimiento = 0; // Checkbox no marcado, asignamos el valor 0
            }
            if (isset($_POST['alcance'])) {
                $alcance = $_POST['alcance']; // Checkbox marcado, asignamos el valor 1
            } else {
                $alcance = 0; // Checkbox no marcado, asignamos el valor 0
            }
            if (isset($_POST['manipulacion'])) {
                $manipulacion = $_POST['manipulacion']; // Checkbox marcado, asignamos el valor 1
            } else {
                $manipulacion = 0; // Checkbox no marcado, asignamos el valor 0
            }
            if (isset($_POST['exploracion'])) {
                $exploracion = $_POST['exploracion']; // Checkbox marcado, asignamos el valor 1
            } else {
                $exploracion = 0; // Checkbox no marcado, asignamos el valor 0
            }

            // Destrezas Manuales
            if (isset($_POST['sostiene_objeto'])) {$sostiene_objeto = $_POST['sostiene_objeto'];} else {$sostiene_objeto = 0;}
            if (isset($_POST['suelta_objeto'])) {$suelta_objeto = $_POST['suelta_objeto'];} else {$suelta_objeto = 0;}
            if (isset($_POST['atrapa_objeto'])) {$atrapa_objeto = $_POST['atrapa_objeto'];} else {$atrapa_objeto = 0;}
            if (isset($_POST['lanza_objeto'])) {$lanza_objeto = $_POST['lanza_objeto'];} else {$lanza_objeto = 0;}
            if (isset($_POST['realiza_nudo'])) {$realiza_nudo = $_POST['realiza_nudo'];} else {$realiza_nudo = 0; }
            if (isset($_POST['encaja'])) {$encaja = $_POST['encaja']; } else {$encaja = 0; }

            //Actividades de la Vida Diaria

            if (isset($_POST['alimentacion'])) {$alimentacion = $_POST['alimentacion']; } else {$alimentacion = 0; }
            if (isset($_POST['higiene'])) {$higiene = $_POST['higiene']; } else {$higiene = 0; }
            if (isset($_POST['vestuario'])) {$vestuario = $_POST['vestuario']; } else {$vestuario = 0; }
            if (isset($_POST['control_esfinteres'])) {$control_esfinteres = $_POST['control_esfinteres']; } else {$control_esfinteres = 0; }
            if (isset($_POST['orden_limpieza'])) {$orden_limpieza = $_POST['orden_limpieza']; } else {$orden_limpieza = 0; }
            if (isset($_POST['ocio_recreacion'])) {$ocio_recreacion = $_POST['ocio_recreacion']; } else {$ocio_recreacion = 0; }
            $observaciones_actividades = $_POST['observaciones_actividades']; 
            
            //Postura
            $observaciones_postura = $_POST['observaciones_postura']; 

            //Marcha Y/O Desplazamiento
            if (isset($_POST['marcha'])) {$marcha = $_POST['marcha']; } else {$marcha = 0; }
            if (isset($_POST['sustentacion'])) {$sustentacion = $_POST['sustentacion']; } else {$sustentacion = 0; }
            if (isset($_POST['silla_ruedas'])) {$silla_ruedas = $_POST['silla_ruedas']; } else {$silla_ruedas = 0; }
            if (isset($_POST['apoyo'])) {$apoyo = $_POST['apoyo']; } else {$apoyo = 0; }
            if (isset($_POST['equilibrio'])) {$equilibrio = $_POST['equilibrio']; } else {$equilibrio = 0; }
            if (isset($_POST['coordinacion'])) {$coordinacion = $_POST['coordinacion']; } else {$coordinacion = 0; }
            if (isset($_POST['dispositivo'])) {$dispositivo = $_POST['dispositivo']; } else {$dispositivo = 0; }
            $cual_dispositivo = $_POST['cual_dispositivo']; 

            $c = new Patient();
            $c->agregarPaciente($nombre, $fechaFormateada,$edad,$sexo, $estado_civil,$direccion,$nombre_encargado,
            $telefono_encargado,$diagnostico,$medicamentos_admin,$medico_Tratante,$telefono_medico,$examenes_realizados,
            $convulsiona,$usa_protesis,$cual_protesis, $historia_enfermedad,$observaciones,$idUsuario,$idUsuario);
            
            $idPaciente = $c->getIdPaciente();

            $c->agregarAntecedente($cardiovascular,$pulmonares,$digestivos,$diabetes, $renales,
            $quirurgicos, $alergicos, $transfuciones, $medicamentos, $otros, $alcohol, $tabaquismo, $drogas, $inmunizaciones,
            $otros_inmuniz, $padre_vivo, $padre_enfermedad, $madre_vivo, $madre_enfermedad, $num_hermanos, $herman_vive,
            $enfermedad_herm, $observaciones_antecedentes, $idPaciente);

            $c->agregarEvaluaciones($dolor_donde, $dolor_irradiacion, $tipo_dolor, $escala_visual_dolor, 
            $idPaciente);
            
            $c->agregarTonoMuscular($tipo_tono, $limitacion_artic, $especificacion, $idPaciente);

            $c->agregarEscalaDesarollo($control_cuello, $rotacion_prono_supino, $rotacion_supino_prono, $tronco_superior,
            $tronco_inferior, $cuatro_puntos, $posicion_sedente, $posicion_hincado, $posicion_semihincado, $posicion_bidepestacion,
            $idPaciente);

            $c->agregarAtencion($localizacion, $fijacion, $seguimiento, $alcance, $manipulacion, $exploracion, $idPaciente);

            $c->agregarDestrezasManuales($sostiene_objeto, $suelta_objeto, $atrapa_objeto, $lanza_objeto, 
            $realiza_nudo, $encaja, $idPaciente);

            $c->agregarActividadDiaria($alimentacion, $higiene, $vestuario, $control_esfinteres, $orden_limpieza,
            $ocio_recreacion, $observaciones_actividades, $idPaciente);

            $c->agregarPostura($observaciones_postura,$idPaciente);

            $c->agregarMarchaDesplazamiento($marcha, $sustentacion, $coordinacion, $equilibrio, $apoyo, $silla_ruedas, $dispositivo,
            $cual_dispositivo, $idPaciente);

            echo '<script>alert("Consulta ingresada exitosamente.");
            window.location.href = "'. BASE_DIR .'pacientes";</script>';
            exit;

        } else {
            exit;
        }
    }
} else {

    require_once 'views/header.php';
    require_once 'views/agregar-paciente.php';
    require_once 'views/footer.php';
}
