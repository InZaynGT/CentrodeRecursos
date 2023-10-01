<?php

if (!empty($_POST)) {

    if ($_POST > 1) {

        $idConsulta = $idItem;
        $fechaConsulta = new DateTime($_POST['fecha']);
        $fechaConsultaStr = $fechaConsulta->format('Y-m-d H:i:s');
        $observaciones = $_POST['observaciones'];
        $usuarioModifica = $_SESSION['user']['id'];

        require_once 'models/consultaModel.php';
        $c = new Consulta();

        $c->updateConsulta($fechaConsultaStr, $observaciones, $usuarioModifica, $idConsulta);
        echo '<script>alert("Consulta actualizada exitosamente.");</script>';
        echo '<meta http-equiv="refresh" content="1;url=http://localhost/clinica/consultas">';
    }

} else {

    require_once 'models/consultaModel.php';
    $con = new Consulta();
    $consulta = $con->getConsultaPorID($idItem);


    require_once 'views/header.php';
    require_once 'views/editar-consulta.php';
    require_once 'views/footer.php';
}
