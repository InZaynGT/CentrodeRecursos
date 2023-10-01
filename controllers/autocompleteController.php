<?php
if (isset($_POST['search']) && (isset($_POST['buscarPaciente']))) {

    $search = $_POST['search'];

    require_once 'models/autocompleteModel.php';

    //el metodo getpacientes debe retornar un array bien formateado para que no de problemas
    $a = new Autocomplete();
    $data = $a->getPacientes($search);
    unset($a);

    //este echo es el que manda el json para la respuesta de ajax
    echo json_encode($data);
    
} else if (isset($_POST['search']) && isset($_POST['buscarProducto'])) {
    $search = $_POST['search'];

    require_once 'models/autocompleteModel.php';

    //el metodo getpacientes debe retornar un array bien formateado para que no de problemas
    $a = new Autocomplete();
    $data = $a->getProductos($search);
    unset($a);

    //este echo es el que manda el json para la respuesta de ajax
    echo json_encode($data);

}else if (isset($_POST['search']) && isset($_POST['buscarMedicamento'])){

    $search = $_POST['search'];

    require_once 'models/autocompleteModel.php';

    $a = new Autocomplete();
    $data = $a->getMedicamentos($search);
    unset($a);

    echo json_encode($data);

} else if(isset($_POST['agregarMedicamento'])){

    $codigo = $_POST['agregarMedicamento'];

    require_once 'models/autocompleteModel.php';
    
    $a = new Autocomplete();
    $data = $a->getAgregarMedicamentos($codigo);
    unset($a);

    echo json_encode($data);


}
