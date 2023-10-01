<?php

require_once 'models/recetaModel.php';
$r =  new Receta();
$recetaEnc = $r->getRecetaEncabezado($idItem);
$recetaDet = $r->getRecetaDetalle($idItem);
unset($r);

require_once 'views/imprimir-receta.php';