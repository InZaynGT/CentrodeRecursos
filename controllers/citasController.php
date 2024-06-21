<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isset($_POST['title']) && isset($_POST['color']) && isset($_POST['start']) && isset($_POST['end'])) {
        $title=$_POST['title'];
        $description="";
		$start=$_POST['start'];
		$end=$_POST['end'];
        $color=$_POST['color'];
		$idUsuario = $_SESSION['user']['id'];
	
	    require_once 'models/eventModel.php';
						$e = new Event();
						if ($end > $start){
							$insert = $e->addEvent($title,$description,$color,$start,$end, $idUsuario);
							if ($insert == true) {
								sleep(1);
								header('Location: citas');
							}else{
								echo 'Ha ocurrido un Error';
							}
						}
						else{
							echo '<script>alert("La cita no pudo ser ingresada porque la fecha final era mayor a la fecha de inicio.");</script>';
							echo '<meta http-equiv="refresh" content="1;url=http://localhost/clinica/citas">';

						}
						

	}
	if(isset($_POST['update']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['id'])){
		$start = $_POST['start'];
		$end = $_POST['end'];
		$id= $_POST['id'];

		require_once 'models/eventModel.php';
		$e = new Event();
		$update = $e->updateEventDate($id, $start, $end);
	}
	
	if(isset($_POST['delete']) && isset($_POST['id'])){
		$id = $_POST['id'];

		require_once 'models/eventModel.php';
		$e = new Event();
		$delete = $e->deleteEvent($id);
		if($delete==true){
			header('Location: citas');
		}

	}else if(isset($_POST['title']) && isset($_POST['color']) && isset($_POST['id'])){
		$id= $_POST['id'];
		$title = $_POST['title'];
		$color= $_POST['color'];

		require_once 'models/eventModel.php';
		$e = new Event();
		$updateTitle = $e->updateTitle($id,$title,$color);
		if($updateTitle == true){
			header('Location: citas');
		}


	}
	
	// else {
	// 	echo 'No se puede realizar ninguna acci&oacute;n';
	// }
}
else {
	$idUsuario = $_SESSION['user']['id'];
	require_once 'models/eventModel.php';
	$load = new Event();
	$events = $load->getEvents($idUsuario);
	$load = null;

	$eventsList = json_encode($events);

	require_once 'views/header.php';
	require_once 'views/citas.php';
	require_once 'views/footer.php';
}
?>