<?php
$idItem = $_POST['idItem'];
// Realiza la eliminación de la consulta utilizando tu lógica actual
require_once 'models/pacienteModel.php';
$consulta = new Patient();
$status = $consulta->anularPaciente($idItem);

if ($status == true) {
    echo 'success';
} else {
    echo 'error';
}

?>