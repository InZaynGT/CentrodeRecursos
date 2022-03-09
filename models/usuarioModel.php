<?php
require_once 'models/configuracionModel.php';

class Usuario extends Configuracion {

	public function getEmpleadoPorID($idEmpleado) {
		$pdo = parent::conexion();
		$empleado = array();
		$stmt = $pdo->prepare("SELECT TOP 1 ID_USUARIO FROM WEB_USUARIO WHERE ID_EMPLEADO = :idEmpleado;");
		$stmt->bindParam(':idEmpleado', $idEmpleado);
		$stmt->execute();

		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$empleado[] = $resultado;
		}

		return $empleado;
	}

	public function getUsuarioPorID($id) {
		$pdo = parent::conexion();
		$usuario = array();

		$stmt = $pdo->prepare("SELECT TOP 1 u.*, e.nombres+ ' '+ e.apellidos AS EMPLEADO
									FROM WEB_USUARIO u
									INNER JOIN WEB_EMPLEADO e ON e.id_empleado = u.id_empleado
									WHERE u.id_usuario = :id;");
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$usuario[] = $resultado;
		}

		if (count($usuario)){
			return $usuario[0];
		}
		else {
			return $usuario;
		}
	}

	public function getUsuario($nickUsuario) {
		$pdo = parent::conexion();
		$usuario = array();
		$stmt = $pdo->prepare("SELECT NICK, ESTADO, FRASE FROM WEB_USUARIO WHERE nick = :nickUsuario LIMIT 1;");
		$stmt->bindParam(':nickUsuario', $nickUsuario);
		$stmt->execute();

		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$usuario[] = $resultado;
		}

		return $usuario;
	}

	public function getUsuarioPass($nickUsuario, $password) {
		$pdo = parent::conexion();
		$usuario = array();
		$password = md5($password);

		$stmt = $pdo->prepare("SELECT ID_USUARIO, NOMBREYAPELLIDO, NICK, PASS, FRASE, ESTADO FROM WEB_USUARIO WHERE NICK = :nickUsuario
								AND PASS = :passwrd LIMIT 1;");
		$stmt->bindParam(':nickUsuario', $nickUsuario);
		$stmt->bindParam(':passwrd', $password);
		$result=$stmt->execute();
		if(!$result){
			die($stmt->errorInfo());
		}

		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$usuario[] = $resultado;
		}

		return $usuario;
	}

	public function getUsuarios() {
		$pdo = parent::conexion();
		$usuarios = array();
		$stmt = $pdo->prepare("SELECT u.ID_USUARIO, u.NICK, u.ESTADO, e.NOMBRES, e.APELLIDOS FROM WEB_USUARIO u
									INNER JOIN WEB_EMPLEADO e ON e.id_empleado = u.id_empleado
									ORDER BY u.ID_USUARIO ASC;");
		$stmt->execute();

		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$usuarios[] = $resultado;
		}

		return $usuarios;
	}

	public function setUsuario($nick, $password, $idEmpleado) {
		$pdo = parent::conexion();

		$stmt = $pdo->prepare("INSERT INTO WEB_USUARIO VALUES (:nick, :password, 'Frase Secreta', 1, :idEmpleado,1,0)");
		$stmt->bindParam(':nick', $nick);
		$password = md5($password);
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':idEmpleado', $idEmpleado);
		$insert = $stmt->execute();

		return $insert;
	}

	public function updateEstadoUsuario($idUsuario, $estado, $ordenes) {
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("UPDATE WEB_USUARIO SET
									estado = :estado, ADMIN_ORDENES = :ordenes
									WHERE id_usuario = :idUsuario");
		$stmt->bindParam(':estado', $estado);
		$stmt->bindParam(':ordenes', $ordenes);
		$stmt->bindParam(':idUsuario', $idUsuario);
		$update = $stmt->execute();
		return $update;
	}

	public function updatePassUsuario($idUsuario, $password) {
		$pdo = parent::conexion();
		$password = md5($password);
		$stmt = $pdo->prepare("UPDATE WEB_USUARIO SET
									pass = :password
									WHERE id_usuario = :idUsuario");
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':idUsuario', $idUsuario);
		$update = $stmt->execute();

		return $update;
	}

	public function updateFraseUsuario($idUsuario, $frase) {
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("UPDATE WEB_USUARIO SET
									frase = :frase
									WHERE id_usuario = :idUsuario");
		$stmt->bindParam(':frase', $frase);
		$stmt->bindParam(':idUsuario', $idUsuario);
		$update = $stmt->execute();

		return $update;
	}
}
?>
