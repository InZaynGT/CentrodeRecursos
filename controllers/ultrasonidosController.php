<?php
require_once 'models/ultrasonidoModel.php';
$i = new Ultrasonido();
$idPaciente = $_SESSION['user']['id'];
$array = $i->getUltrasonido($idPaciente);

if ($_SERVER['REQUEST_METHOD']=="POST") {
    if (count($_POST) > 0 && isset($_POST['nombreUltrasonido']) && isset($_POST['precioUltrasonido'])) {
        $nombre = $_POST['nombreUltrasonido'];
        $precio = $_POST['precioUltrasonido'];


        $insert =  $i->setUltrasonidoYGenerico($nombre, $precio);
        if ($insert) {
            echo '<div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
                        <b>OK:</b> Registro almacenado correctamente.
                    </div>';

                    echo '<script>
							    $(location).attr("href","'.BASE_DIR.'ultrasonidos");
						    </script>';
        } 
        // else {
        //      echo '<div class="alert alert-danger alert-dismissable">
        //           <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
        //           <b>Error:</b> No se pudo almacenar el registro, recarge la p&aacute;gina e int&eacute;ntelo nuevamente.
        //           </div>';
        //  }
    }
    
} else {


    require_once 'views/header.php';
    require_once 'views/ultrasonidos.php';
    require_once 'views/footer.php';
}
