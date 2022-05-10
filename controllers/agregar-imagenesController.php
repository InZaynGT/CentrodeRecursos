<?php

if ($_POST) {
    if (isset($_POST['idUltrasonido']) && isset($_POST['imageUltrasonido'])) {

        $img = $_POST['imageUltrasonido'];
        $idUltrasonido = $_POST['idUltrasonido'];

        if (empty($img)) {
            $filename[0] = "";
        } else {
            //subir la foto al directorio
            $folderPath = "/xampp/uploads/ultrasonidos/";

            $image_parts = explode(";base64", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);
            $filename = uniqid("", false) . '.png';

            $file = $folderPath . $filename;
            file_put_contents($file, $image_base64);
            $filename = explode('.', $filename);

            require_once 'models/consultaModel.php';
            $cons = new Consulta();
            $status = $cons->updateUltrasonidoFoto($idUltrasonido, $filename[0]);
            unset($cons);

            if ($status) {
                echo '<div class="alert alert-success alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
						<b>OK:</b> Registro almacenado correctamente.
					</div>';
            }
            echo '
        <script>
        window.location.reload();
        </script>';
        }
    } else {
        echo '<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
						<b>Error:</b> Debe completar la información solicitada.
					</div>';
        exit;
    }
} else {
    require_once 'models/consultaModel.php';
    //Para el select de las fotos que se guardan desde el modal 
    $cons = new Consulta();
    $ultrasonidos = $cons->getUltrasonidos($idItem);

    //obtiene las fotos que se han guardado
    $imgsUltrasonido = $cons->getUltrasonidoFotos($idItem);
    unset($cons);

    require_once 'views/header.php';
    require_once 'views/agregar-imagenes.php';
    require_once 'views/footer.php';
}
