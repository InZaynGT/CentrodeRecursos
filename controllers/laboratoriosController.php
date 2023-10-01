<?php
require_once 'models/laboratorioModel.php';
$i = new Laboratorio();
$idPaciente = $_SESSION['user']['id'];
$array = $i->getLaboratorio($idPaciente);

if ($_SERVER['REQUEST_METHOD']=="POST") {
    if (count($_POST) > 0 && isset($_POST['nombreLaboratorio'])) {
        $nombre = $_POST['nombreLaboratorio'];

        
        $insert =  $i->setLaboratorio($nombre);
        if ($insert) {
            echo '<div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
                        <b>OK:</b> Registro almacenado correctamente.
                    </div>';

                    echo '<script>
							    $(location).attr("href","'.BASE_DIR.'laboratorios");
						    </script>';
        } 
        // else {
        //     echo '<div class="alert alert-danger alert-dismissable">
        //          <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
        //          <b>Error:</b> No se pudo almacenar el registro, recarge la p&aacute;gina e int&eacute;ntelo nuevamente.
        //          </div>';
        // }
    }
    
} else {

    require_once 'views/header.php';
    require_once 'views/laboratorios.php';
    require_once 'views/footer.php';
}
