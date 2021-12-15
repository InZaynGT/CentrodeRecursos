<?php
session_start();
date_default_timezone_set('America/Guatemala');
require_once 'config/settings.php';

$vista = (isset($_GET['cf_vst']) ? $_GET['cf_vst'] : 'inicio');
$idItem = (isset($_GET['subcf_vst']) ? $_GET['subcf_vst'] : 0);
$slug = (isset($_GET['slug']) ? $_GET['slug'] : '');
$tituloPagina = "Administraci&oacute;n :: Altek Systems";

if (is_file('controllers/'.$vista.'Controller.php')) {
	if (isset($_SESSION['user']['username'])) {	
	   require_once 'controllers/'.$vista.'Controller.php';
	}
	else {
		require_once 'controllers/lgn_cf_0738Controller.php';
	}
}
else {
	require_once 'views/404.php';
}
?>
