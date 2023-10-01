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

            $nombreCompleto = trim($_POST['nombreCompleto']);
            $order   = array("'", '"', '\\', '/');
            $nombreCompleto2 = str_replace($order, '', $nombreCompleto);
            $nombreCompleto3 = explode("-", $nombreCompleto2);
            $idPaciente = $nombreCompleto3[0];
            $fechaConsulta = new DateTime($_POST['fecha']);
            $fechaConsultaStr = $fechaConsulta->format('Y-m-d');
            $idUsuario = $_SESSION['user']['id'];
            $observaciones = $_POST['observaciones'];
            
            require_once 'models/consultaModel.php';
            $c = new Consulta;
            $c->setConsulta($idPaciente, $fechaConsultaStr, $idUsuario, $observaciones);
            echo '<script>alert("Consulta ingresada exitosamente.");</script>';
            echo '<meta http-equiv="refresh" content="1;url=http://localhost/clinica/consultas">';
            exit; // Importante para detener la ejecución del script PHP aquí
        } else {
            exit;
        }
    }
} else {

    //se traen los campos de la tabla genericos para llenar los option en agregar-consulta
    require_once 'models/consultaModel.php';

    $d = new Consulta();
    $clientes = $d->getCliente();

    $tags2 = '';
	foreach ($clientes as $tc) {
		$tags2 .= '"' . $tc['idpaciente'] . '- ' . $tc['nombre'] . '",';
	}

    require_once 'views/header.php';
    require_once 'views/agregar-consulta.php';
    require_once 'views/footer.php';
}
