<?php

	require_once 'models/facturaModel.php';
	$fact = new Factura();
	$reciboEnc = $fact->getImpresionReciboEnc($idItem);
	$reciboDet = $fact->getImpresionReciboDet($idItem);
	unset($fact);	


	require_once 'views/imprimir-recibo.php';