<?php

if (!empty($_POST)) {

    if ($_POST > 1) {
    }

} else {

    require_once 'models/consultaModel.php';
    $con = new Consulta();
    $consulta = $con->getPacienteporID($idItem);


    require_once 'views/header.php';
    require_once 'views/visualizar-paciente.php';
    require_once 'views/footer.php';
}
