<?php
abstract class Configuracion {

	protected $pdo;

    protected function conexion(){
		$timezone = date_default_timezone_get();
  // $dbh = new PDO("sqlsrv:Server=localhost,1433;Database=mydb", $user , $pass);
  //  	$this->pdo = new PDO("sqlsrv:Server=localhost;Database=mydb", DB_USER, DB_PASSWD);
		$this->pdo = new PDO('mysql:host=127.0.0.1;port=3307;dbname=clinica;charset=utf8','root','root');
		$this->pdo->exec("SET time_zone = '{$timezone}'");
		return $this->pdo;
	}
}
?>
