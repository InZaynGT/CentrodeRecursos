<?php
require_once 'models/configuracionModel.php';

class Event extends Configuracion {

	public function addEvent($title,$description,$color,$start,$end, $idUsuario) {
		
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("INSERT INTO events(title,description,color,start_event,end_event, id_usuario) values(:title,:description,:color,:start,:end, :idUsuario);");
		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':description', $description);
		$stmt->bindParam(':color', $color);
		$stmt->bindParam(':start', $start);
		$stmt->bindParam(':end', $end);
		$stmt->bindParam(':idUsuario', $idUsuario);
		$insert = $stmt->execute();
		if($insert==false){
			print_r($stmt->errorInfo());
		}else{
			return $insert;

		}
		
	}

	public function getEvents($idusuario){
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT * FROM events WHERE id_usuario = :idusuario ORDER BY id_event ;");
		$stmt->bindParam(":idusuario", $idusuario);
		$stmt->execute();

		$result = $stmt->fetchAll();
		$data = array();

		if($result){
		foreach($result as $event){
			$data[] = array(
				'id' => $event["id_event"],
				'title' => $event["title"],
				'color' => $event["color"],
				'start' => $event["start_event"],
				'end' => $event["end_event"]
			);
		}}
		return $data;
	}

	public function updateEventDate($id, $start, $end){
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("UPDATE events set start_event = :start, end_event = :end WHERE id_event = :id;");
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":start", $start);
		$stmt->bindParam(":end", $end);
		$update = $stmt->execute();
		if($update==false){
			print_r($stmt->errorInfo());
		}else{
			return $update;

		}

	}

	public function deleteEvent($id){
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("DELETE FROM events where id_event = :id;");
		$stmt->bindParam(":id", $id);
		$delete = $stmt->execute();
		if($delete==false){
			print_r($stmt->errorInfo());
		}else{
			return $delete;

		}

	}

	public function updateTitle($id,$title,$color) {
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("UPDATE events SET title=:title, color=:color WHERE id_event=:id;");
		$stmt->bindParam(':id',$id);
		$stmt->bindParam(':title',$title);
		$stmt->bindParam(':color',$color);
		$update = $stmt->execute();

		if($update==false){
			print_r($stmt->errorInfo());
		}else{
			return $update;

		}

	}


}

