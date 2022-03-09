<?php
if (!empty($_POST)) {
    if (count($_POST) == 1  && isset($_POST['buscarPaciente'])) {

        //se obtiene la string que hayan seleccionado luego se saca el codigo del paciente y se busca la info
        $buscar = $_POST['buscarPaciente'];
        $order   = array('-');
        $buscar = str_replace($order, '', $buscar);
        $buscarCodCliente  = explode(" ", $buscar);
        require_once 'models/pacienteModel.php';
        //funcion para obtener los datos del paciente
        $p = new Patient();
        $paciente = $p->getPacientePorId($buscarCodCliente[0]);
        unset($p);

        //Desde aqui se mandan los datos a la vista agregar-consulta
        echo '
            <script>
             $("#idPaciente").val("'; echo $paciente['idpaciente']; echo '");
            $("#nombrePaciente").val("'; echo $paciente['nombres'] .' '.$paciente['apellidos']; echo '");
            </script>
            ';

        //Minimiza el arrow-down de seleccionar paciente
        echo '
        <script>
        $("#btnMinimizar").trigger( "click" );
        </script>';
    }
    else{
        if($_POST > 1 && isset($_POST['fecha']) && isset($_POST['idPaciente']) && isset($_POST['nombrePaciente'])){
            
           
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
           $fur = new DateTime($_POST['ag_Fur']) ;
           $partos = $_POST['ao_Partos'];
           $cesareas = $_POST['ao_Cesareas'];
           $abortos = $_POST['ao_Abortos'];
           $hv= $_POST['ao_Hv'];
           $hm = $_POST['ao_Hm'];
           $obitoFetal = $_POST['ao_ObitoFetal'];
           $ultimoPapanicolaou = new DateTime($_POST['ao_ultimoPapanico']);
           $cantidadPapanicolaou = $_POST['ao_cantidadPapanico'];
           $parejassexuales = $_POST['ao_Ps'];
           $inicioVidaSexual = $_POST['ao_Ivs'];
           $parejasSexualesPareja = $_POST['ao_Psp']; 


           require_once 'models/consultaModel.php';
           $c = new Consulta();
           //Guarda el encabezado de la consulta
           $idConsulta = $c->setConsultaEnc($idPaciente,$fechaConsulta->format('Y-m-d'),$idUsuario);
           //Guarda los antecedentes de la consulta
           $c->setAntecedentes($idConsulta,$motivo,$historiaEnfermedad,$antecedentesMedicos,$antecedentesQuirurgicos,$antecedentesAlergicos,
            $antecedentesTraumaticos,$viciosYManias,$embarazos,$menarquia,$ciclo,$duracion,$dolor,$ets,$esEmbarazada,$semanasEmbarazo,$metodoAnticonceptivo,
            $fur->format('Y-m-d'),$partos,$cesareas,$abortos,$hv,$hm,$obitoFetal,$ultimoPapanicolaou->format('Y-m-d'),$cantidadPapanicolaou,$parejassexuales,$inicioVidaSexual,
            $parejasSexualesPareja);
            unset($c);
        }
        else {
            exit;
        }

           
    }
} else {

    //se traen los campos de la tabla genericos para llenar los option en agregar-consulta
    require_once 'models/consultaModel.php';

    $d = new Consulta();
    $cantidadDolor = $d->getDolor();
    $ets = $d->getEts();
    $metodosAnti = $d->getMetodosAnticonceptivos();
    $tiposUltrasonido = $d->getTiposUltrasonido();
    $tiposPeso = $d->getTiposPeso();
    unset($d);





    require_once 'models/pacienteModel.php';
    require_once 'views/header.php';
    require_once 'views/agregar-consulta.php';
    require_once 'views/footer.php';
}
