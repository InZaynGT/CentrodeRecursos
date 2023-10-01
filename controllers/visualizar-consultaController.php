<?php

if (!empty($_POST)) {

    if ($_POST > 1) {
    }

} else {

    require_once 'models/consultaModel.php';
    $con = new Consulta();
    $consulta = $con->getConsultaPorID($idItem);


    require_once 'views/header.php';
    require_once 'views/visualizar-consulta.php';
    require_once 'views/footer.php';
}
