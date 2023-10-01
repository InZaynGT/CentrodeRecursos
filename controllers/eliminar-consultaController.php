<?php
// eliminar-consulta.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idItem'])) {
    $idItem = $_POST['idItem'];
    echo 'Valor de idItem: ' . $idItem;
    // Realiza la eliminación de la consulta utilizando tu lógica actual
    require_once 'models/consultaModel.php';
    $cons = new Consulta();
    $status = $cons->anularConsulta($idItem);
    
    if ($status == true) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'invalid_request';
}

?>
