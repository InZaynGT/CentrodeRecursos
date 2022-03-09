<?php 
if(isset($_POST['search'])){

$search = $_POST['search'];

require_once 'models/autocompleteModel.php';

//el metodo getpacientes debe retornar un array bien formateado para que no de problemas
$a = new Autocomplete();
$data = $a->getPacientes($search);
unset($a);

//este echo es el que manda el json para la response del ajax
echo json_encode($data);

}