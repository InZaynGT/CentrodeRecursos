<?php

	require_once 'models/consultaModel.php';
	$consulta = new Consulta();

	$consultas =  $consulta->getConsultas();
	unset($consulta);


    require_once 'views/header.php';
	require_once 'views/consultas.php';
	require_once 'views/footer.php';