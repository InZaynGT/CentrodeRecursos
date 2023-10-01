<?php
if($_SERVER['REQUEST_METHOD']=="POST"){
	//Cuando se agrega un producto y se selecciona, se toma el nombre para hacer la busqueda en la tabla
	if (isset($_POST['buscarProd']) && isset($_POST['idProductoBuscar'])) {
		$buscar = trim($_POST['buscarProd']);
		$order   = array("'", '"', '\\', '/');
		$buscar = str_replace($order, '', $buscar);
		$buscarCodigo = explode(" ", $buscar);
		$codProd = (int) filter_var($buscarCodigo[0], FILTER_SANITIZE_NUMBER_INT);
	
		require_once 'models/producto_servicioModel.php';
		$ps = new Producto();
		$productos = $ps->getProductoPorCodigo($codProd);
		unset($ps);
	
		$select = 'checked';
		echo '
					<div id="wrapper"> 
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td width="20px"></td>
								<td align="center"><b>Producto/servicio</b></td>
								<td align="center"><b>Cantidad</b></td>
								<td align="center"><b>Precio</b></td>
							</tr>
							<tr>
									   <td><input type="radio" name="radio_prod" value="' . $productos['codigo'] . '"  data-codigoprod="' . $productos['codigo'] . '"
										  data-nombreprod="' . $productos['nombre'] . '"  ' . $select . '>
										</td>
										 <input type="hidden"  name="codigoProd" id="codprod" value="' . $productos['codigo'] . '" min="1" required>
										 <td>' . $productos['nombre'] . '</td>
										 <td align="right"><input type="number" name="cantidad" id="cantidadp" value="1" min="1" required> </td>
										 <td>
										 <input id="precio" name="precio" type="number" value="' . number_format($productos['precio'], 2, '.', '') . '">
										 </td>
							</tr>';
	
	} else if (isset($_POST['idConsulta']) && isset($_POST['idPaciente'])) {
		if(isset($_POST['producto'])){
			$fecha =  new datetime($_POST['fecha']);
			$fechaFact =  $fecha->format('Y-m-d');
			$idPaciente = $_POST['idPaciente'];
			$idConsulta = $_POST['idConsulta'];
			$documento = $_POST['documento'];
			$formaPago = $_POST['formaPago'];
			$totalPago = $_POST['totalPago'];
			$observaciones = $_POST['observaciones'];
			$monto =$_POST['totalPago'];
		
			$productos = $_POST['producto'];
			$cantidades = $_POST['cantidad'];
			$subtotales = $_POST['subtotal'];
		
			require_once 'models/facturaModel.php';
			$fac =  new Factura();
			$nroFactura = $fac->setfactura($fechaFact, $idPaciente, $idConsulta, $formaPago, $totalPago, $_SESSION['user']['id']);
		
			for ($i = 0; $i < count($productos); $i++) {
				$result = $fac->setDetalleFactura($nroFactura, $productos[$i], $cantidades[$i], $subtotales[$i]);
			}
			
			require_once 'models/documentoModel.php';
			$doc =  new documento();
			$docto = $doc->getCorrelativoDocumento($documento);

			$resultPago = $fac->setPagoFactura($nroFactura, $idPaciente, $formaPago,$documento ,$observaciones, $monto, $docto['correlativo']);

			unset($fac);
		
			if ($result and $resultPago) {
		
				echo '<div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
							<b>OK:</b> Registro almacenado correctamente.
						</div>
						
						<script>
						window.open("'.BASE_DIR.'imprimir-recibo/'.$idItem.'-'.slug($slug).'", "_blank")
						window.location="'.BASE_DIR.'consultas/"
						</script>';
			} else {
		
				echo '<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
					<b>Error:</b> No se pudo actualizar el registro, recarge la p&aacute;gina e int&eacute;ntelo nuevamente.
				   </div>';
			}

		}else {

			echo '<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" onClick="delay()" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
					<b>Error:</b> Debe ingresar un producto en el listado.
				   </div>
				   ';
		//Informa al cliente que no se guardó el pago porque no hay productos o servicios
				echo '<script>
				function delay(){
					window.location="'.BASE_DIR.'cobrar-consulta/'.$idItem.'-'.slug($slug).'"
				}
					</script>';
				   exit;
		}
	

	}

}
 else {
	 //Obtiene los datos del cliente para cobrar
	require_once 'models/consultaModel.php';
	$con = new Consulta();
	$consulta  = $con->getPacienteCobrar($idItem);
	unset($con);

	//Obtiene los tipos de documento para hacer la venta
	require_once 'models/cobroModel.php';
	$cob = new Cobro();
	$documentos = $cob->getDocumentos();
	$formasPago = $cob->getFormasPago();
	unset($cob);

	//obtiene los ultrasonidos asignados a la consulta
	require_once 'models/ultrasonidoModel.php';
	$ult = new Ultrasonido();
	$ultrasonidosConsulta = $ult->getUtrasonidosConsulta($idItem);
	unset($ult);

	require_once 'views/header.php';
	require_once 'views/cobrar-consulta.php';
	require_once 'views/footer.php';
	
}

