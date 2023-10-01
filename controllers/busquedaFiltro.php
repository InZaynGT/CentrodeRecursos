<?php


require_once 'models/insumosModel.php';
   $p = new Insumo();
   $insumosList = $p->getExistenciaPorFinca();
   $p = null;
   $tags3= '';

   $tags3=generateAutocompleteArray($insumosList);
   echo $tags3;

function generateAutocompleteArray($insumosList){
  $jsArray = "";

    foreach ($insumosList as $t) {
    $jsArray .= ''.$t['idmedicamento'].'- '.$t['nombre'].' :: '.$t['existencia'].',';
    }
	//Removes the remaining comma so you don't get a blank autocomplete option.
	$jsArray = substr($jsArray, 0, -1);
	return $jsArray;
}

?>