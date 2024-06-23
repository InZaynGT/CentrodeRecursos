<?php
	define('APP_NAME', 'Centro de Recursos');

	// LOCAL
	// define('DB_SERVER', 'localhost');
	// define('PORT', '3306');
	// define('DB_NAME', 'clinica');
	// define('DB_USER', 'root');
	// define('DB_PASSWD', '');
	// define('BASE_DIR', 'http://localhost/centroderecursos/');

	//PRODUCCION
	define('PORT', '3306');
	define('DB_SERVER', 'localhost');
	define('DB_NAME', 'clinica');
	define('DB_USER', 'cmiranda');
	define('DB_PASSWD', 'MySecurePass123!');
	define('BASE_DIR', 'https://centroderecursos.intecod.com/');
	

	function slug($string){
		$slug = trim($string);
		$order   = array("'", '"', '\\', '/', 'á', 'Á', 'É', 'é', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú', 'ñ', 'Ñ', '.', ',', '$', '%', '&', '(', '=', ')'
							, '!', '°', '|', '#', '[', '+', ']', ':', ';', ']', '{', '}', '?', '<', '>', '_', '@', '¡', '?');
		$slug = str_replace($order, '', $slug);
		$slug = str_replace(' ', '-', $slug);

		return strtolower($slug);
	}

     /*   function permiso($requerido, $requerido2='') {
		if ($requerido == $_SESSION['user']['puesto'] || $_SESSION['user']['puesto'] == 4) {
			// echo 'SI TIENE PERMISO';
		}
		else if ($requerido2 != '' && ($requerido2 == $_SESSION['user']['puesto'] || $_SESSION['user']['puesto'] == 4)) {
			// echo 'SI TIENE PERMISO';
		}
		else {
			require_once 'views/404.php';
			exit;
		}
	}*/
     function mostrarFiltroFecha($idTabla, $numeroColumna, $verFiltroFecha = true, $tituloReporte = '', $columnas = array(), $anchoColumna = 6) {
		require_once 'views/filtro-fecha.php';
	}

	function mostrarFiltroFecha2($idTabla, $numeroColumna, $verFiltroFecha = true, $tituloReporte = '', $columnas = array(), $anchoColumna = 6) {
		require_once 'views/filtro-fecha2.php';
	}

	function mostrarFiltroFecha3($idTabla, $numeroColumna, $verFiltroFecha = true, $tituloReporte = '', $columnas = array(), $anchoColumna = 6) {
		require_once 'views/filtro-fecha3.php';
	}

?>
