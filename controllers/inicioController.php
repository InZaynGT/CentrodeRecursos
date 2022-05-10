<?php	

	require_once 'models/dashboardModel.php';
	$dsh = new DASHBOARD();
	$totalPacientes = $dsh->getPacientesCount();
	$totalConsultas = $dsh->getConsultasCount();
	$totalCitas = $dsh ->getCitasCount();
	unset($dsh);
	
	require_once 'views/header.php';
	require_once 'views/inicio.php';
	require_once 'views/footer.php';
?>