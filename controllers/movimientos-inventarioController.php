<?php
////////////////////////   BUSCAR INSUMOS   //////////////////////////////

//Lo que valida aquí aquí es si existe la variable buscarDetalle y la del id para el proveedor
if (isset($_POST['buscarDetalle']) && isset($_POST['idproveedorBus'])) {
    $buscar = trim($_POST['buscarDetalle']);
    $order   = array("'", '"', '\\', '/');
    $buscar = str_replace($order, '', $buscar);

    //Aquí valida si lo que estamos enviando a buscar está vacío o no
    if ($buscar == '') {
        echo $buscar;
        sleep(1);
        echo '<div class="error"><i class="fa fa-close"></i> Rellene el campo.</div>';
    } else {
        // echo '	<script>
        // 	document.getElementById("btnMinimizar").click();
        // 	</script> ';

        //Aquí lo que hacemos es empalar el nombre que acabamos de encontrar y buscar su código
        $buscarCodI = explode("-", $buscar);
        require_once 'models/insumosModel.php';

        //Instanciamos la clase Insumo
        $b = new Insumo();

        //Encontramos el ID del valor que acabamos de mandar a buscar
        $detalle = $b->getMovProds($buscarCodI[0]);
        $b = null;

        //Evaluamos si el detalle está vacío o no
        if (empty($detalle)) {
            sleep(1);
            echo '<div ><i class="fa fa-warning"></i> No se encontraron movimientos.</div>';
        } else {
            echo '
				<div id="wrapper" >
				<table class="table table-bordered" >
                    <tbody>
                        <tr>
                            <h3 align="center">' . $buscarCodI[1] . '</h3>
                        </tr>
						<tr>
							<td width="30px" align="center">
                                <b>Fecha</b>
                            </td>
							<td width="50px"  align="center">
                                <b>Nro. Comprobante</b>
                            </td>
							<td width="300px"align="center">
                                <b>Descripción del movimiento</b>
                            </td>
                            <td align="center">
                                <b>Notas</b>
                            </td>
							<td width="30px" align="center">
                                <b>Cantidad</b>
                            </td>
                            <td width="30px" align="center">
                                <b>Cant. Disponible</b>
                            </td>
						</tr>
							';
            for ($i = 0; $i < count($detalle); $i++) {
                $select = ($i == 0) ? 'checked' : '';
                echo ' <tr>
							<td>' . $detalle[$i]['fecha'] . '</td> 
                            <td align="center">' . $detalle[$i]['nro_docto'] . '</td>
                            <td>';
                if ($detalle[$i]['movimiento'] == 4) {
                    echo 'Compra de mercadería No. ';
                } else if ($detalle[$i]['movimiento'] == 3) {
                    echo 'Venta de mercadería No. ';
                } else if ($detalle[$i]['movimiento'] == 1) {
                    echo 'Entrada de Mercadería No. ';
                } else if ($detalle[$i]['movimiento'] == 2) {
                    echo 'Salida de Mercadería No. ';
                }
                echo '' . $detalle[$i]['correlativo'] . '</td>
                            <td>' . $detalle[$i]['Descr_trans'] . '</td> 
                            <td align="center">' . $detalle[$i]['cantidad'] . '</td> 
                            <td align="center">' . $detalle[$i]['cant_disponible'] . '</td>  
                    ';
            }
            echo '   </tbody>
				    </table>
						</div>

					';

            echo '<script>
				//	$("#myModal").modal("hide");
				//	$("#btnSelProd").trigger("click");
					</script>
					';
        }
    }
}

/////////////////////  GUARDAR   /////////////////////////////////////////////////////
else if (isset($_POST['movimiento'])) {
    // if ($_POST['idItem'] == 0){

    // 	echo '<script>alert("Debe seleccionar una bodega"); location.reload();</script>';

    // 	exit;
    // }
    if (isset($_POST['movimiento']) == 0) {
        sleep(1);
        echo '<div class="error"><i class="fa fa-close"></i> Debe seleccionar el tipo de movimiento</div>';
        exit;
    } else if (isset($_POST['movimiento']) && isset($_POST['fecha']) && isset($_POST['nota'])) {
        $movimiento = $_POST['movimiento'];
        $fecha = $_POST['fecha'];
        $fecha = date_create($fecha);
        // $bodega = $_POST['idItem'];
        $usuario = $_SESSION['user']['username'];
        // $usuario = $_SESSION['user']['id_empleado'];
        $nota = $_POST['nota'];
        $formaPagoC = $_POST['formaPagoC'];
        $formaPagoV = $_POST['formaPagoV'];
        // $cuenta = $_POST['cuenta'];
        // $cuentaBancaria = $_POST['cuentaBancaria'];
        // $documento = $_POST['documento'];
        $comprobante = $_POST['comprobante'];
        // $facturaVenta = $_POST['facturaVenta'];
        // $mensualidades = $_POST['mensualidades'];
        $idCaja = NULL;
        // $docfel = $_POST['docfel'];

        ///////////
        // Cliente
        $cliente = $_POST['cliente'];
        if ($movimiento == 3 && $cliente == '') {
            echo '<div class="alert alert-danger alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
							<b>Error:</b> Debe ingresar un cliente.
						</div>';
            exit;
        }
        if ($cliente != '') {
            $buscar = trim($cliente);
            $order   = array("'", '"', '\\', '/');
            $buscar = str_replace($order, '', $buscar);
            $buscarCodC = explode("-", $buscar);
            require_once 'models/pacienteModel.php';
            $p = new Patient();
            $idCliente = $p->getClientePorNombreAsignar($buscarCodC[0]); // <-aca va el post
            $p = null;
            if ($idCliente == 0) {
                echo '<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
						<b>Error:</b> El cliente seleccionado no existe.
					</div>';
                exit;
            }
            $idC = $idCliente['idpaciente'];
        } else {
            $idC = NULL;
        }
        // Proveedor
        $proveedor = $_POST['proveedor'];
        if ($proveedor != '') {
            $buscar = trim($proveedor);
            $order   = array("'", '"', '\\', '/');
            $buscar = str_replace($order, '', $buscar);
            $buscarCodP = explode("-", $buscar);
            echo $buscarCodP[0];
            require_once 'models/casaModel.php';
            $p = new Casa();
            $idProveedor = $p->getCasaPorNombre($buscarCodP[0]); // <-aca va el post
            if ($idProveedor == 0) {
                echo '<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
					<b>Error:</b> El proveedor seleccionado no existe. 
				</div>';
                exit;
            }
            $idP = $idProveedor[0];
        } else {
            $idP = NULL;
        }
        /////////

        if (!isset($_POST['producto'])) {
            sleep(1);
            echo '<div class="error"><i class="fa fa-close"></i> No se han agregado productos.</div>';
        } else {
            require_once 'models/insumosModel.php';
            $p = new Insumo();
            $correlativo = $p->getCorrelativo($movimiento); // <-aca va el post
            $p = null;
            $nCorrelativo = $correlativo + 1;
            $bodegaD = NULL;

            $insumos = $_POST['producto'];
            $cantidades = $_POST['cantidad'];
            $precios = $_POST['precio'];
            $totalMovimiento = $_POST['totalMovimiento'];
            $preciosV = $_POST['precioV'];

            $fecha_f = date_format($fecha, 'Y-m-d');

            $serieFel = NULL;
            $numeroFel = NULL;
            $uuidFel = NULL;
            $fechaFel = NULL;

            $c = new Insumo();
            //itera los insumos dentro de la tabla
            for ($i = 0; $i < count($insumos); $i++) {
                $p = new Insumo();
                //se obtiene la existencia de cada insumo
                $existencia = $c->getExistencia($insumos[$i]);
                $p = null;
                $p = new Insumo();
                //se obtiene el insumo
                $detProducto = $c->getInsumo($insumos[$i]);
                $p = null;
                $movTraslado = 0;

                //si el movimiento es una COMPRA
                if ($movimiento == 4) {

                    //Se detalla el precio por la existencia
                    $actual = $detProducto['precioP'] * $existencia;
                    //se detalla la cantidad de cada fila de producto
                    $nuevo = $cantidades[$i] * $precios[$i];

                    //SE DETERMINA CUAL SERA EL NUEVO COSTO EN BASE AL PROMEDIO
                    // $nuevoPromedio = ($actual + $nuevo) / ($existencia + $cantidades[$i]);

                    //SE DETERMINA CUAL ES EL NUEVO COSTO PROMEDIO
                    // $p = new Insumo();
                    // $p->updateCostoPromedio($insumos[$i], $nuevoPromedio, $preciosV[$i]);
                    // $p = null;

                    $nExistencia = $existencia - $cantidades[$i];
                }

                //si el movimiento es una VENTA o SALIDA
                if ($movimiento == 3 || $movimiento == 2) {
                    $tipoMov = 1;
                    $nExistencia = $existencia - $cantidades[$i];
                    $c->updateExistencia($nExistencia, $insumos[$i]);
                }
                //si el movimiento es una COMPRA o ENTRADA
                if ($movimiento == 1 || $movimiento == 4) {
                    $nExistencia = $existencia + $cantidades[$i];
                    $c->updateExistencia($nExistencia, $insumos[$i]);
                }

                //VALIDACION PARA INSERTAR EN MOVPRODS, SI ES VENTA O SALIDA, SE MANDA COMO NEGATIVO.
                if ($movimiento == 2 || $movimiento == 3) {
                    $cantidad = $cantidades[$i] * -1;
                }
                //SI NO, COMO CANTIDAD POSITIVA
                else if ($movimiento == 1 || $movimiento == 4) {
                    $cantidad = $cantidades[$i];
                }

                $codprod = $insumos[$i];
                $precio_u = $precios[$i];
                $fecha_f = $fecha;
                $descr_trans = $nota;
                $cant_disponible = $c->getExistencia($insumos[$i]);
                $fecha_operacion = $fecha;
                $movimientoMov = $movimiento;
                $correlativo = $c->getCorrelativo($movimiento);
                $nCorrelativo = $correlativo + 1;
                $idMovimiento = $c->setMovimiento($codprod, $descr_trans, $cantidad, $cant_disponible, $precio_u, $comprobante, $movimientoMov, $nCorrelativo);
                $cp = null;
            }
            $c = null;
            echo '<script> $("#BotonGuardar").prop("disabled", true); alert("Movimiento realizado satisfactoriamente!");  $(location).attr("href","' . BASE_DIR . 'insumosCargaDescarga/");</script>';
        }
    }
} else if (count($_POST) > 0) {
    sleep(1);
    echo '<div class="error"><i class="fa fa-close"></i> No se puede realizar ninguna acci&oacute;n.</div>';
} else {
    require_once 'models/pacienteModel.php';
    require_once 'models/casaModel.php';
    require_once 'models/insumosModel.php';

    $c = new Patient();
    $clientes = $c->getCliente();
    $c = null;

    $c = new Casa();
    $proveedores = $c->getCasas();
    $c = null;

    $c = new Insumo();
    $insumos = $c->getInsumos();
    $c = null;

    $tags2 = '';
    foreach ($clientes as $tc) {
        $tags2 .= '"' . $tc['idpaciente'] . '- ' . $tc['nombres'] . ' ' . $tc['apellidos'] . '",';
    }

    $tags3 = '';
    foreach ($proveedores as $p) {
        $tags3 .= '"' . $p['idproveedor'] . '- ' . $p['nombre'] . '",';
    }

    $tags4 = '';
    foreach ($insumos as $i) {
        $tags4 .= '"' . $i['idmedicamento'] . '- ' . $i['nombre'] . '",';
    }

    require_once 'views/header.php';
    require_once 'views/movimientos-inventario.php';
    require_once 'views/footer.php';
}
