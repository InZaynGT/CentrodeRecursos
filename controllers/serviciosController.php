<?php
require_once 'models/servicioModel.php';
$i = new Servicio();
$idPaciente = $_SESSION['user']['id'];
$array = $i->getServicio($idPaciente);

if ($_SERVER['REQUEST_METHOD']=="POST") {
    if (count($_POST) > 0 && isset($_POST['nombreServicio']) && isset($_POST['precioServicio'])) {
        $nombre = $_POST['nombreServicio'];
        $precio = $_POST['precioServicio'];

        $insert =  $i->setServicio($nombre, $precio);
        if ($insert) {
            

            echo '<div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
                        <b>OK:</b> Registro almacenado correctamente.
                    </div>';

                    echo '
								<script>
									$(location).attr("href","'.BASE_DIR.'servicios");
								</script>
							';
        } 
        // else {
            
        //     echo '<div class="alert alert-danger alert-dismissable">
        //          <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
        //          <b>Error:</b> No se pudo almacenar el registro, ya que ya se encuentra almacenado.
        //          </div>';
        // }
    }
}
 else {

    require_once 'views/header.php';
    require_once 'views/servicios.php';
    require_once 'views/footer.php';
}
