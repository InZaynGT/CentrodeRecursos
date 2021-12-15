<?php
	// define('DB_SERVER', 'USUARIOPC\SQLEXPRESS');
	// define('DB_NAME', 'logistik');
 // define('DB_SERVER', 'USUARIOPC\SQLEXPRESS');
	//define('DB_NAME', 'Logistik');
//	define('DB_USER', '');
	//define('DB_PASSWD', '');
	//define('BASE_DIR', 'http://usuariopc/rapiescapes/');

	define('DB_SERVER', 'localhost');
	define('DB_NAME', 'clinica');
	define('DB_USER', 'root');
	define('DB_PASSWD', 'root');
	define('BASE_DIR', 'https://localhost/clinica/');
	

	function slug($string){
		$slug = trim($string);
		$order   = array("'", '"', '\\', '/', 'á', 'Á', 'É', 'é', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú', 'ñ', 'Ñ', '.', ',', '$', '%', '&', '(', '=', ')'
							, '!', '°', '|', '#', '[', '+', ']', ':', ';', ']', '{', '}', '?', '<', '>', '_', '@', '¡', '?');
		$slug = str_replace($order, '', $slug);
		$slug = str_replace(' ', '-', $slug);

		return strtolower($slug);
	}

        function permiso($requerido, $requerido2='') {
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
	}
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
