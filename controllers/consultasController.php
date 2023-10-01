<?php

require_once 'models/consultaModel.php';
$u = new Consulta();
$idPaciente = $_SESSION['user']['id'];
$array = $u->getConsulta($idPaciente);

require_once 'views/header.php';
require_once 'views/consultas.php';
require_once 'views/footer.php';