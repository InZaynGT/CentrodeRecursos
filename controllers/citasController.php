<?php
permiso(1);
if (count($_POST) > 0) {
	if (isset($_POST['title']) && isset($_POST['color']) && isset($_POST['start']) && isset($_POST['end'])) {
        $title=$_POST['title'];
        $description="";
		$start=$_POST['start'];
		$end=$_POST['end'];
        $color=$_POST['color'];

	
	    require_once 'models/eventModel.php';
						$e = new Event();
						$insert = $e->addEvent($title,$description,$color,$start,$end);
						if ($insert == true) {
							// sleep(1);
							header('Location: citas');
						}else{
							echo 'Ha ocurrido un Error';
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
	
	else {
		echo 'No se puede realizar ninguna acci&oacute;n';
	}
}
else {
	require_once 'models/eventModel.php';
	$load = new Event();
	$events = $load->getEvents();
	$load = null;

	$eventsList = json_encode($events);

	require_once 'views/header.php';
	require_once 'views/citas.php';
	require_once 'views/footer.php';
}
?>