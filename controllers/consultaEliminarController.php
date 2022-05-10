<?php 

require_once 'models/consultaModel.php';

$cons = new Consulta();
$status  =  $cons->deleteConsulta($idItem);
unset($cons);

if($status){
    echo '<script>
    window.location.href = "'.BASE_DIR.'consultas";
    </script>';

}else {
    echo '<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
    <b>Error:</b> No se pudo actualizar el registro, recarge la p&aacute;gina e int&eacute;ntelo nuevamente.
    </div>';


}