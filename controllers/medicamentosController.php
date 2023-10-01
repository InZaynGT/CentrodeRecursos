<?php
    require_once 'models/medicamentoModel.php';
    $i = new Medicamento();
    $idPaciente = $_SESSION['user']['id'];
    $array = $i->getMedicamento($idPaciente);

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $filtro = $_POST['filtroMedicamento'];
    $nombreMedicamento = $_POST['nombreMedicamento'];
    $dosificacion = $_POST['dosificacionMedicamento'];
    $uso = $_POST['usoMedicamento'];
    $costo = $_POST['costo'];
    $precioP = $_POST['precioPMedicamento'];
    $precioA = $_POST['precioAMedicamento'];
    

    $insert =  $i->insertMedicamento($filtro, $nombreMedicamento, $dosificacion, $uso, $costo, $precioP, $precioA);
    unset($i);
    if ($insert == 1062) {
        echo '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
            <b>OK:</b> Registro almacenado correctamente prueba 1301.
            </div>';

        echo '<script>
                    window.location="' . BASE_DIR . 'medicamentos/"
                </script>';
    }
} else {

    require_once 'views/header.php';
    require_once 'views/medicamentos.php';
    require_once 'views/footer.php';
}
