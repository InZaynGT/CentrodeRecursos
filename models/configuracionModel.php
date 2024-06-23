<?php
abstract class Configuracion {

	protected $pdo;

    protected function conexion(){
		$timezone = date_default_timezone_get();
		$this->pdo = new PDO('mysql:host='.DB_SERVER.';port=3306;dbname='.DB_NAME.';charset=utf8',DB_USER,DB_PASSWD);
		$this->pdo->exec("SET time_zone = '-06:00'");
		//Activar para hacer las pruebas de errores mientra se programa
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
		return $this->pdo;
	}
}
?>
