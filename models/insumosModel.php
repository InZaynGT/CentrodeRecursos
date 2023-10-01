<?php
require_once 'models/configuracionModel.php';

class Insumo extends Configuracion {

	public function getInsumos() {
		$insumos = array();
		$pdo = parent::conexion();
		$sql = "SELECT *
                FROM medicamento
				WHERE activo = 1
                ORDER BY idmedicamento ASC;";
		$query = $pdo->query($sql);
		while( $resultado = $query->fetch(PDO::FETCH_ASSOC) ){
		  $insumos[] = $resultado;
		}
		return $insumos;
	}

	public function getMovProds($id) {
		$insumo = array();
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT fecha,nro_docto,Descr_trans,cantidad,cant_disponible, movimiento, correlativo
								FROM movprods 
								WHERE Cod_Prod = :id 
								ORDER BY nro_reg DESC;");
								$stmt->bindParam(':id', $id);
		$stmt->execute();

		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$insumo[] = $resultado;
		}
		if (count($insumo)){
			return $insumo;
		}
		else {
			return 0;
		}
	}
	public function getInsumosList() {
		$insumos = array();
		$pdo = parent::conexion();
		$sql = "SELECT i.*, m.DESCRIPCION AS MARCA_DESC
								FROM insumo i
								LEFT JOIN marca m on m.ID_MARCA = i.MARCA
								ORDER BY ID_INSUMO ASC;";
		$query = $pdo->query($sql);
		while( $resultado = $query->fetch(PDO::FETCH_ASSOC) ){
			$insumos[] = $resultado;
		}
		return $insumos;
	}

	public function getInsumosPivot($pivot) {
		$array = array();
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT i.ID_INSUMO, i.DESCRIPCION, cp.PORCENTAJE_GANANCIA, i.PRECIO_VENTA, i.UBICACION,
			".$pivot." m.DESCRIPCION AS MARCA
		, (SELECT sum(fi.EXISTENCIA)) as TOTAL
					FROM insumo i
					INNER JOIN insumo_finca fi
					  ON i.ID_INSUMO = fi.ID_INSUMO
					INNER JOIN marca m
					  ON i.MARCA = m.ID_MARCA
						inner JOIN categoria_producto cp on cp.ID_CATEGORIA = i.ID_DEPARTAMENTO
					INNER JOIN bodega b
					  ON fi.ID_BODEGA = b.ID_BODEGA
					GROUP BY i.ID_INSUMO;");
    $stmt->execute();
		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$array[] = $resultado;
		}
		if (count($array)){
			return $array;
		}
		else {
			return 0;
		}
	}

	public function getInsumosPivotDet($pivot, $id) {
		$array = array();
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT i.ID_INSUMO, i.DESCRIPCION, cp.PORCENTAJE_GANANCIA, i.PRECIO,
			".$pivot." m.DESCRIPCION AS MARCA
		, (SELECT sum(fi.EXISTENCIA)) as TOTAL
					FROM insumo i
					INNER JOIN insumo_finca fi
					  ON i.ID_INSUMO = fi.ID_INSUMO
					INNER JOIN marca m
					  ON i.MARCA = m.ID_MARCA
						inner JOIN categoria_producto cp on cp.ID_CATEGORIA = i.ID_DEPARTAMENTO
					INNER JOIN bodega b
					  ON fi.ID_BODEGA = b.ID_BODEGA
						WHERE b.ID_SUCURSAL = 	".$id."
					GROUP BY i.ID_INSUMO;");
    $stmt->execute();
		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$array[] = $resultado;
		}
		if (count($array)){
			return $array;
		}
		else {
			return 0;
		}
	}



	public function getInsumo($id) {
		$insumo = array();
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT  *
				FROM medicamento
                WHERE idmedicamento = :id LIMIT 1;");
                $stmt->bindParam(':id', $id);
		$stmt->execute();

		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$insumo[] = $resultado;
		}

		if (count($insumo)){
			return $insumo[0];
		}
		else {
			return $insumo;
		}
	}

	public function getInsumoKardex($id) {
		$insumo = array();
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT m.FECHA, m.MOVIMIENTO, mi.ID_INSUMO, mi.CANTIDAD, m.NOTA, mi.EXISTENCIA
										, i.DESCRIPCION AS INSUMO, b.DESCRIPCION AS BODEGA, e.NOMBRES, e.APELLIDOS, c.NOMBRES AS NOMBRES_CLI, c.APELLIDOS AS APELLIDOS_CLI, mi.MOV_TRASLADO
											FROM movimiento_insumo_det mi
		                                    INNER JOIN movimiento_insumo m on m.ID_MOVIMIENTO_INSUMO = mi.ID_MOVIMIENTO_INSUMO
											INNER JOIN insumo i ON i.ID_INSUMO=mi.ID_INSUMO
											INNER JOIN bodega b ON b.ID_BODEGA=mi.BODEGA
											INNER JOIN empleado e ON e.ID_EMPLEADO=m.OPERADOR

											LEFT JOIN cliente c ON c.ID_CLIENTE = m.ID_CLIENTE
											where mi.id_insumo= :id AND mi.ESTADO=0
											order by m.FECHA DESC
									;");
								$stmt->bindParam(':id', $id);
		$stmt->execute();

		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$insumo[] = $resultado;
		}

		if (count($insumo)){
			return $insumo;
		}
		else {
			return 0;
		}
	}


	public function getInsumosTratamiento($id) {
		$insumo = array();
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT it.*, i.precio as COSTO_PROMEDIO, i.DESCRIPCION AS DESC_INSUMO, m.DESCRIPCION AS MARCA_INSUMO
											FROM insumo_tratamiento it
											INNER JOIN insumo i on i.ID_INSUMO = it.ID_INSUMO
											INNER JOIN tratamiento t ON t.ID_TRATAMIENTO = it.ID_TRATAMIENTO
											LEFT JOIN marca m ON m.ID_MARCA = i.MARCA
											WHERE it.ID_TRATAMIENTO = :id AND it.estado=0
									;");
								$stmt->bindParam(':id', $id);
		$stmt->execute();
		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$insumo[] = $resultado;
		}
		if (count($insumo)){
			return $insumo;
		}
		else {
			return $insumo;
		}
	}

	public function verificaInsumoTratamiento($tratamiento, $insumoid) {
		$insumo = array();
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT it.*
											FROM insumo_tratamiento it
											WHERE it.ID_TRATAMIENTO = :tratamiento and it.ID_INSUMO = :insumo AND it.ESTADO=0
									;");
								$stmt->bindParam(':tratamiento', $tratamiento);
								$stmt->bindParam(':insumo', $insumoid);
		$stmt->execute();
		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$insumo[] = $resultado;
		}
		if (count($insumo)){
			return $insumo;
		}
		else {
			return $insumo;
		}
	}
	public function eliminarInsumoTratamiento($idItem,$recibida_por) {
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("UPDATE insumo_tratamiento SET	estado = 1,  MODIFICO= :recibida_por
									WHERE ID_INSUMO_TRATAMIENTO = :idItem");
		$stmt->bindParam(':idItem', $idItem);
		$stmt->bindParam(':recibida_por', $recibida_por);
		$update = $stmt->execute();

		return $update;
	}
	// public function getInsumoKardex($id) {
	// 	$insumo = array();
	// 	$pdo = parent::conexion();
	// 	$stmt = $pdo->prepare("SELECT mi.FECHA, mi.MOVIMIENTO, mi.ID_INSUMO, mi.CANTIDAD, mi.NOTA
	// 							, i.DESCRIPCION AS INSUMO, b.DESCRIPCION AS BODEGA, e.NOMBRES, e.APELLIDOS, c.NOMBRES AS NOMBRES_CLI, c.APELLIDOS AS APELLIDOS_CLI
	// 								FROM movimiento_insumo mi
	// 								INNER JOIN insumo i ON i.ID_INSUMO=mi.ID_INSUMO
	// 								INNER JOIN bodega b ON b.ID_BODEGA=mi.ID_BODEGA
	// 								INNER JOIN empleado e ON e.ID_EMPLEADO=mi.OPERADOR
	// 								LEFT JOIN cliente c ON c.ID_CLIENTE = mi.ID_CLIENTE
	// 								where mi.id_insumo= :id AND mi.ESTADO=0
	// 								order by fecha DESC
	// 								;");
	// 							$stmt->bindParam(':id', $id);
	// 	$stmt->execute();
	//
	// 	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
	// 		$insumo[] = $resultado;
	// 	}
	//
	// 	if (count($insumo)){
	// 		return $insumo;
	// 	}
	// 	else {
	// 		return 0;
	// 	}
	// }

	public function getInsumoCargaDescarga($id) {
		$insumo = array();
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT idmedicamento, nombre, costo, precioP, precioA, existencia
							   FROM clinica.medicamento 
							   WHERE activo = 1 
							   and idmedicamento = :id LIMIT 1;");
							   $stmt->bindParam(':id', $id);
		$stmt->execute();

		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$insumo[] = $resultado;
		}
		if (count($insumo)){
			return $insumo;
		}
		else {
			return $insumo;
		}
	}

	public function getVentaInsumos($fi,$ff) {
		$insumo = array();
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("SELECT b.DESCRIPCION as BODEGA, c.NOMBRES, c.APELLIDOS,  mi.* , i.DESCRIPCION as INSUMO,
													e.NOMBRES AS NOMBRES_EMPLEADO, e.APELLIDOS AS APELLIDOS_EMPLEADO, e2.NOMBRES AS NOMBRES_MODIFICO, e2.APELLIDOS AS APELLIDOS_MODIFICO
									FROM movimiento_insumo mi
									INNER JOIN bodega b ON b.ID_BODEGA = mi.ID_BODEGA

									INNER JOIN insumo i ON i.ID_INSUMO = mi.ID_INSUMO
									INNER JOIN empleado e ON e.ID_EMPLEADO = mi.OPERADOR
									INNER JOIN empleado e2 ON e2.ID_EMPLEADO = mi.OPERADOR
									LEFT JOIN cliente c ON c.ID_CLIENTE= mi.ID_CLIENTE
									WHERE MOVIMIENTO=3
									AND FECHA BETWEEN :fi and :ff;");
								$stmt->bindParam(':fi', $fi);
									$stmt->bindParam(':ff', $ff);
		$stmt->execute();

		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$insumo[] = $resultado;
		}
		if (count($insumo)){
			return $insumo;
		}
		else {
			return $insumo;
		}
	}

	public function eliminarVentaInsumo($id, $idE) {
		$pdo = parent::conexion();
		$empleado = array();
		$stmt = $pdo->prepare("UPDATE movimiento_insumo set estado = 1, MODIFICO= :idE where ID_MOVIMIENTO_INSUMO = :id;");
		$stmt->bindParam(':id', $id);
		$stmt->bindParam(':idE', $idE);
		$stmt->execute();
		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$empleado[] = $resultado;
		}
		if (count($empleado)){
			return $empleado[0];
		}
		else {
			return $empleado;
		}
	}


		public function setInsumo($descripcion, $marca, $depto,$presentacion) {
			$pdo = parent::conexion();
			$sql = "INSERT INTO insumo VALUES (NULL, '".$descripcion."', '".$marca."',  0, NULL, 0, '".$depto."', '".$presentacion."', NULL, 0); ";
			$pdo->exec($sql);
			return $pdo->lastInsertId();
		}

		public function setInsumoTratamiento($idItem, $insumo, $cantidad) {
			$pdo = parent::conexion();
			$sql = "INSERT INTO insumo_tratamiento VALUES (NULL, '".$idItem."', '".$insumo."', '".$cantidad."',NULL, 0); ";
			$pdo->exec($sql);
			return $pdo->lastInsertId();
		}


	public function updateInsumo($idInsumo, $descripcion, $marca, $depto, $presentacion, $precioV) {
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("UPDATE insumo SET descripcion = :descripcion,
									marca = :marca, ID_DEPARTAMENTO  = :depto, PRESENTACION = :presentacion, PRECIO_VENTA = :precioV
                  					WHERE id_insumo = :idInsumo");
		$stmt->bindParam(':descripcion', $descripcion);
		$stmt->bindParam(':marca', $marca);
    $stmt->bindParam(':idInsumo', $idInsumo);
		$stmt->bindParam(':depto', $depto);
		$stmt->bindParam(':presentacion', $presentacion);
		$stmt->bindParam(':precioV', $precioV);
		$update = $stmt->execute();
		return $update;
	}
	// public function updateCostoPromedio($idInsumo, $precio) {
	// 	$pdo = parent::conexion();
	// 	$stmt = $pdo->prepare("UPDATE insumo SET PRECIO = :precio
  //                 WHERE id_insumo = :id");
	// 	$stmt->bindParam(':id', $idInsumo);
	// 	$stmt->bindParam(':precio', $precio);
	// 	$update = $stmt->execute();
	// 	return $update;
	// }

	public function updateCostoPromedio($idInsumo, $precio,$precioV) {
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("UPDATE insumo SET PRECIO = :precio, PRECIO_VENTA = :precioV
									WHERE id_insumo = :id");
		$stmt->bindParam(':id', $idInsumo);
		$stmt->bindParam(':precio', $precio);
		$stmt->bindParam(':precioV', $precioV);
		$update = $stmt->execute();
		return $update;
	}

	public function getInsumosAsignados() {
		$array = array();
		$pdo = parent::conexion();
		$sql = "SELECT ht.*, e.NOMBRES, e.APELLIDOS, i.DESCRIPCION AS INSUMO, l.DESCRIPCION AS LOTE, e2.NOMBRES as NOMBRE_ENTREGO,
								e2.APELLIDOS as APELLIDO_ENTREGO, f.NOMBRE as FINCA, b.DESCRIPCION AS BODEGA
						FROM insumo_asignar ht
						INNER JOIN empleado e ON e.ID_EMPLEADO = ht.ID_TRABAJADOR
						INNER JOIN bodega b ON b.ID_BODEGA = ht.ID_BODEGA
						INNER JOIN empleado e2 ON e2.ID_EMPLEADO = ht.ENTREGO
						INNER JOIN insumo i ON i.ID_INSUMO = ht.id_insumo
						LEFT JOIN lote l ON l.ID_LOTE = ht.ID_LOTE
						LEFT JOIN finca f ON f.ID_FINCA= l.ID_FINCA
						WHERE ht.ESTADO=0 AND ht.ID_COSECHA = ".$_SESSION['cosecha']['ID_TEMPORADA']."
						ORDER BY ID_INSUMO_ASIGNAR DESC;";
		$query = $pdo->query($sql);
		while( $resultado = $query->fetch(PDO::FETCH_ASSOC) ){
			$array[] = $resultado;
		}
		return $array;
	}

	public function getInsumosAsignadosTrabajador() {
		$array = array();
		$pdo = parent::conexion();
		$sql = "SELECT ht.*, e.NOMBRES, e.APELLIDOS, i.DESCRIPCION AS INSUMO, e2.NOMBRES as NOMBRE_ENTREGO,
								e2.APELLIDOS as APELLIDO_ENTREGO,  b.DESCRIPCION AS BODEGA, e3.NOMBRES as NOMBRE_RECIBIO,
														e3.APELLIDOS as APELLIDO_RECIBIO
						FROM insumo_trabajador ht
						INNER JOIN empleado e ON e.ID_EMPLEADO = ht.ID_TRABAJADOR
						INNER JOIN bodega b ON b.ID_BODEGA = ht.ID_BODEGA
						INNER JOIN empleado e2 ON e2.ID_EMPLEADO = ht.ENTREGO
						LEFT JOIN empleado e3 ON e3.ID_EMPLEADO = ht.RECIBIO
						INNER JOIN insumo i ON i.ID_INSUMO = ht.id_insumo


						WHERE  ht.ID_COSECHA = ".$_SESSION['cosecha']['ID_TEMPORADA']."
						ORDER BY ID_INSUMO_TRABAJADOR DESC LIMIT 300;";
		$query = $pdo->query($sql);
		while( $resultado = $query->fetch(PDO::FETCH_ASSOC) ){
			$array[] = $resultado;
		}
		return $array;
	}


	public function getInsumoAsignado($id) {
		$pdo = parent::conexion();
		$array = array();
		$stmt = $pdo->prepare("SELECT ht.*, e.NOMBRES, e.APELLIDOS, i.DESCRIPCION AS INSUMO, l.DESCRIPCION AS LOTE, e2.NOMBRES as NOMBRE_ENTREGO,
								e2.APELLIDOS as APELLIDO_ENTREGO, f.NOMBRE as FINCA, b.DESCRIPCION AS BODEGA
						FROM insumo_asignar ht
						INNER JOIN empleado e ON e.ID_EMPLEADO = ht.ID_TRABAJADOR
						INNER JOIN bodega b ON b.ID_BODEGA = ht.ID_BODEGA
						INNER JOIN empleado e2 ON e2.ID_EMPLEADO = ht.ENTREGO
						INNER JOIN insumo i ON i.ID_INSUMO = ht.id_insumo
						LEFT JOIN lote l ON l.ID_LOTE = ht.ID_LOTE
						LEFT JOIN finca f ON f.ID_FINCA= l.ID_FINCA
						 WHERE ht.ID_INSUMO_ASIGNAR = :id");
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$array[] = $resultado;
		}
		if (count($array)){
			return $array[0];
		}
		else {
			return 0;
		}
	}


	public function getInsumoFact($id) {
		$pdo = parent::conexion();
		$array = array();
		$stmt = $pdo->prepare("	SELECT cc.*,  0 as DESCUENTO, mi.ID_CLIENTE, mi.FECHA, c.NOMBRES, c.APELLIDOS, cc.DOCUMENTO AS TRATAMIENTO,
								(select GROUP_CONCAT(i.DESCRIPCION SEPARATOR '+ ') AS TRATAMIENTO
								from cuenta_cliente cc2
								inner join movimiento_insumo mi on mi.CORRELATIVO=cc2.DOCUMENTO
								inner join movimiento_insumo_det mid on mid.ID_MOVIMIENTO_INSUMO= mi.ID_MOVIMIENTO_INSUMO
								inner join insumo i on i.ID_INSUMO = mid.ID_INSUMO
								where cc2.ID_CUENTA_CLIENTE=cc.ID_CUENTA_CLIENTE and cc.ID_MOVIMIENTO=3) AS TRATAMIENTO2,
								c.SALDO_ANTICIPOS, 'B' AS BOS
						FROM cuenta_cliente cc
						INNER JOIN movimiento_insumo mi on mi.ID_MOVIMIENTO_INSUMO = cc.CUENTA
						INNER JOIN cliente c on c.ID_CLIENTE = mi.ID_CLIENTE
						WHERE cc.ESTADO=0 AND  cc.ID_MOVIMIENTO=3 AND cc.DOCUMENTO = :id");
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$array[] = $resultado;
		}
		if (count($array)){
			return $array[0];
		}
		else {
			return 0;
		}
	}




	public function getInsumoAsignadoTrabajador($id) {
		$pdo = parent::conexion();
		$array = array();
		$stmt = $pdo->prepare("SELECT * from insumo_trabajador
						 WHERE ID_INSUMO_TRABAJADOR = :id");
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$array[] = $resultado;
		}
		if (count($array)){
			return $array[0];
		}
		else {
			return 0;
		}
	}

	public function setAsignaInsumo($trabajador, $insumo, $lote,$finca, $cantidad, $nota, $fecha, $entregado_por, $cosecha){
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("INSERT INTO insumo_asignar VALUES (NULL, :insumo, :trabajador, :lote, :finca, :cantidad, :fecha, 0, :nota, :entregado_por, null, NULL, :cosecha)");
										$stmt->bindParam(':trabajador', $trabajador);
										$stmt->bindParam(':insumo', $insumo);
										$stmt->bindParam(':lote', $lote);
										$stmt->bindParam(':finca', $finca);
										$stmt->bindParam(':fecha', $fecha);
										$stmt->bindParam(':cantidad', $cantidad);
										$stmt->bindParam(':nota', $nota);
										$stmt->bindParam(':entregado_por', $entregado_por);
										$stmt->bindParam(':cosecha', $cosecha);
										$insert = $stmt->execute();
										return $insert;
	}

	public function setAsignaInsumoTrabajador($trabajador, $insumo, $precio,$finca, $cantidad, $nota, $fecha, $entregado_por, $cosecha, $idplanilla, $idfinca){
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("INSERT INTO insumo_trabajador VALUES (NULL, :insumo, :trabajador, :precio, :finca, :cantidad, :fecha, 0, :nota, :entregado_por, NULL, NULL, :cosecha, :idplanilla, NULL, :idfinca)");
										$stmt->bindParam(':trabajador', $trabajador);
										$stmt->bindParam(':insumo', $insumo);
										$stmt->bindParam(':precio', $precio);
										$stmt->bindParam(':finca', $finca);
										$stmt->bindParam(':fecha', $fecha);
										$stmt->bindParam(':cantidad', $cantidad);
										$stmt->bindParam(':nota', $nota);
										$stmt->bindParam(':entregado_por', $entregado_por);
										$stmt->bindParam(':cosecha', $cosecha);
										$stmt->bindParam(':idplanilla', $idplanilla);
										$stmt->bindParam(':idfinca', $idfinca);
										$insert = $stmt->execute();
										return $insert;
	}

	// public function setMovimiento($bodega, $fecha_f, $movimiento, $usuario,$nota, $idCliente, $idProveedor, $comprobante, $fp, $cuenta, $documento, $correlativo,$bodegaD,$total, $idcaja, $cuentaBancaria, $facturaVenta,$serieFel,$numeroFel,$uuidFel,$fechaFel,$iddoc,$numeroactual){
	// $pdo = parent::conexion();
	// $stmt = $pdo->prepare("INSERT INTO movprods VALUES (NULL, :bodega, :fecha, :movimiento, :nota, :entregado_por, :cliente, 0, NULL, NULL, :proveedor, :comprobante, :fp, :cuenta, :documento, :correlativo, :bodegaD, :total, :caja, :cuentaBancaria, :facturaVenta, NULL, now(),NULL,  :serieFel, :numeroFel, :uuidFel, NULL, :fechaFel, :iddoc, :numeroactual)");
	// 									$stmt->bindParam(':bodega', $bodega);
	// 									$stmt->bindParam(':fecha', $fecha_f);
	// 									$stmt->bindParam(':movimiento', $movimiento);
	// 									$stmt->bindParam(':nota', $nota);
	// 									$stmt->bindParam(':entregado_por', $usuario);
	// 									$stmt->bindParam(':cliente', $idCliente);
	// 									$stmt->bindParam(':proveedor', $idProveedor);
	// 									$stmt->bindParam(':comprobante', $comprobante);
	// 									$stmt->bindParam(':fp', $fp);
	// 									$stmt->bindParam(':cuenta', $cuenta);
	// 									$stmt->bindParam(':documento', $documento);
	// 									$stmt->bindParam(':correlativo', $correlativo);
	// 									$stmt->bindParam(':bodegaD', $bodegaD);
	// 									$stmt->bindParam(':total', $total);
	// 									$stmt->bindParam(':caja', $idcaja);
	// 									$stmt->bindParam(':cuentaBancaria', $cuentaBancaria);
	// 									$stmt->bindParam(':facturaVenta', $facturaVenta);
	// 									$stmt->bindParam(':serieFel', $serieFel);
	// 									$stmt->bindParam(':numeroFel', $numeroFel);
	// 									$stmt->bindParam(':uuidFel', $uuidFel);
	// 									$stmt->bindParam(':fechaFel', $fechaFel);
	// 									$stmt->bindParam(':iddoc', $iddoc);
	// 									$stmt->bindParam(':numeroactual', $numeroactual);
	// 									$insert = $stmt->execute();
	// 									return $pdo->lastInsertId();
	// }

	public function setMovimiento($codprod, $descr_trans, $cantidad,$cant_disponible, $precio_u, $nro_Docto, $movimiento, $correlativo){
		$pdo = parent::conexion();
		$stmt = $pdo->prepare("INSERT INTO movprods VALUES (NULL, :codprod, now(), :descr_trans,:cantidad,:cant_disponible,:precio_u,:nro_docto,now(),:movimiento,:correlativo)");
											$stmt->bindParam(':codprod', $codprod);
											$stmt->bindParam(':descr_trans', $descr_trans);
											$stmt->bindParam(':cantidad', $cantidad);
											$stmt->bindParam(':cant_disponible', $cant_disponible);
											$stmt->bindParam(':precio_u', $precio_u);
											$stmt->bindParam(':nro_docto', $nro_Docto);
											$stmt->bindParam(':movimiento', $movimiento);
											$stmt->bindParam(':correlativo', $correlativo);
											$insert = $stmt->execute();
											return $pdo->lastInsertId();
		}
	
	

	public function setMovimientoInsumos($movimiento, $insumo,$cantidad,$precio, $existencia, $bodega,$costo,$tipoTraslado){
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("INSERT INTO movimiento_insumo_det VALUES (NULL, :movimiento, :insumo, :cantidad, :precio, :existencia, :bodega, 0, :costo, :tipoTraslado)");
										$stmt->bindParam(':movimiento', $movimiento);
										$stmt->bindParam(':insumo', $insumo);
										$stmt->bindParam(':cantidad', $cantidad);
										$stmt->bindParam(':precio', $precio);
										$stmt->bindParam(':existencia', $existencia);
										$stmt->bindParam(':bodega', $bodega);
										$stmt->bindParam(':costo', $costo);
										$stmt->bindParam(':tipoTraslado', $tipoTraslado);
										$insert = $stmt->execute();
										return $insert;
	}

	public function getInsumoPorNombre($id) {
		$pdo = parent::conexion();
		$array = array();
		$stmt = $pdo->prepare("SELECT *
									FROM insumo WHERE ID_INSUMO = :id");
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$array[] = $resultado;
		}
		if (count($array)){
			return $array;
		}
		else {
			return 0;
		}
	}

	public function getCorrelativo($id) {
		$pdo = parent::conexion();
		$array = array();
		$stmt = $pdo->prepare("SELECT CORRELATIVO
									FROM movprods
									WHERE MOVIMIENTO = :id
									ORDER BY nro_reg DESC LIMIT 1");
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
			$array[] = $resultado;
		}
		if (count($array)){
			return $array[0]['CORRELATIVO'];
		}
		else {
			return 0;
		}
	}


public function getExistencia($id) {
	$pdo = parent::conexion();
	$array = array();
	$stmt = $pdo->prepare("SELECT EXISTENCIA FROM medicamento WHERE idmedicamento = :id");
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
		$array[] = $resultado;
	}
	if (count($array)){
		return $array[0]['EXISTENCIA'];
	}
	else {
		return 0;
	}
}




public function getExistenciaPorFinca() {
	$pdo = parent::conexion();
	$array = array();
	$stmt = $pdo->prepare("SELECT idmedicamento, nombre, precioP, costo, existencia 
						   FROM clinica.medicamento 
						   WHERE activo = 1");
	$stmt->execute();
	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
		$array[] = $resultado;
	}
	if (count($array)){
		return $array;
	}
	else {
		return 0;
	}
}


public function eliminarInsumoAsignado($idItem,$recibida_por) {
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("UPDATE insumo_asignar SET	estado = 1, fecha_devolucion= now(), recibio= :recibida_por
								WHERE ID_INSUMO_ASIGNAR = :idItem");
	$stmt->bindParam(':idItem', $idItem);
	$stmt->bindParam(':recibida_por', $recibida_por);
	$update = $stmt->execute();

	return $update;
}

public function eliminarInsumoAsignadoTrabajador($idItem,$recibida_por) {
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("UPDATE insumo_trabajador SET	estado = 1, fecha_devolucion= now(), recibio= :recibida_por
								WHERE ID_INSUMO_TRABAJADOR = :idItem");
	$stmt->bindParam(':idItem', $idItem);
	$stmt->bindParam(':recibida_por', $recibida_por);
	$update = $stmt->execute();
	return $update;
}

public function setDevolucion($idItem,$cantidad) {
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("UPDATE insumo_asignar SET cantidad = cantidad - :cantidad
								WHERE ID_INSUMO_ASIGNAR = :idItem");
	$stmt->bindParam(':idItem', $idItem);
	$stmt->bindParam(':cantidad', $cantidad);
	$update = $stmt->execute();

	return $update;
}

public function updateExistenciaDevolucion($idItem,$cantidad,$bodega) {
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("UPDATE insumo_finca SET EXISTENCIA = EXISTENCIA +  (:cantidad)
								WHERE ID_INSUMO = :idItem AND ID_BODEGA = :bodega;");
	$stmt->bindParam(':idItem', $idItem);
	$stmt->bindParam(':cantidad', $cantidad);
	$stmt->bindParam(':bodega', $bodega);
	$update = $stmt->execute();

	return $update;
}

public function updateExistencia($nExistencia, $idMedicamento) {
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("UPDATE medicamento SET existencia = :nExistencia
								WHERE idmedicamento = :idMedicamento;");
	$stmt->bindParam(':nExistencia', $nExistencia);
	$stmt->bindParam(':idMedicamento', $idMedicamento);
	$update = $stmt->execute();

	return $update;
}

public function updateExistenciaMenos($idItem,$cantidad,$bodega) {
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("UPDATE insumo_finca SET EXISTENCIA = EXISTENCIA -  (:cantidad)
								WHERE ID_INSUMO = :idItem AND ID_BODEGA = :bodega;");
	$stmt->bindParam(':idItem', $idItem);
	$stmt->bindParam(':cantidad', $cantidad);
	$stmt->bindParam(':bodega', $bodega);
	$update = $stmt->execute();

	return $update;
}


public function updatePrecioInsumo($idItem,$precio) {
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("UPDATE insumo SET	precio = :precio
								WHERE ID_INSUMO = :idItem");
	$stmt->bindParam(':idItem', $idItem);
	$stmt->bindParam(':precio', $precio);
	$update = $stmt->execute();

	return $update;
}

public function setTraslado($bodega, $bodegaD, $fecha_f,  $usuario,$nota) {
	$pdo = parent::conexion();
	$sql = "INSERT INTO insumo_traslado VALUES (NULL, '".$bodega."', '".$bodegaD."', '".$fecha_f."', '".$usuario."', '".$nota."'); ";
	$pdo->exec($sql);
	return $pdo->lastInsertId();
}

public function setDetalleTraslado($idMovimiento, $insumo,$cantidad,$precio, $bodega, $bodegaD ){
$pdo = parent::conexion();
$stmt = $pdo->prepare("INSERT INTO insumo_traslado_det VALUES (NULL, :movimiento, :insumo, :cantidad, :precio, :bodega, :bodegaD)");
									$stmt->bindParam(':movimiento', $idMovimiento);
									$stmt->bindParam(':insumo', $insumo);
									$stmt->bindParam(':cantidad', $cantidad);
									$stmt->bindParam(':precio', $precio);
									$stmt->bindParam(':bodega', $bodega);
									$stmt->bindParam(':bodegaD', $bodegaD);
									$insert = $stmt->execute();
									return $insert;
}

public function getTraslado($id) {
	$insumo = array();
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("SELECT mid.*, mi.FECHA, mi.NOTA, mi.OPERADOR, e.NOMBRES, e.APELLIDOS, mi.BODEGA_DESTINO, b.DESCRIPCION as ORIGEN,
		 										b2.DESCRIPCION AS DESTINO, mi.CORRELATIVO, i.DESCRIPCION as INSUMO, i.PRESENTACION
										FROM movimiento_insumo_det mid
										INNER JOIN movimiento_insumo mi on mi.ID_MOVIMIENTO_INSUMO = mid.ID_MOVIMIENTO_INSUMO
										INNER JOIN empleado e on e.ID_EMPLEADO = mi.OPERADOR
										INNER JOIN bodega b on b.ID_BODEGA= mi.ID_BODEGA
										INNER JOIN bodega b2 on b2.ID_BODEGA= mi.BODEGA_DESTINO
										INNER JOIN insumo i on i.ID_INSUMO = mid.ID_INSUMO
										WHERE mid.ID_MOVIMIENTO_INSUMO = :id and mid.MOV_TRASLADO= 2 ;");
							$stmt->bindParam(':id', $id);
	$stmt->execute();

	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
		$insumo[] = $resultado;
	}

	if (count($insumo)){
		return $insumo;
	}
	else {
		return $insumo;
	}
}

public function getDetalleOperacion($id) {
	$insumo = array();
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("SELECT mid.*, b.DESCRIPCION as BODEGA, mi.FECHA, mi.MOVIMIENTO, mi.COMPROBANTE, mi.CORRELATIVO, mi.BODEGA_DESTINO, mi.TOTAL, mi.ID_CLIENTE, mi.ID_PROVEEDOR, c.NOMBRES, c.APELLIDOS, p.DESCRIPCION as PROVEEDOR, i.DESCRIPCION as PRODUCTO
										FROM movimiento_insumo_det mid
										INNER JOIN movimiento_insumo mi on mi.ID_MOVIMIENTO_INSUMO = mid.ID_MOVIMIENTO_INSUMO
										INNER JOIN bodega b ON b.ID_BODEGA = mi.ID_BODEGA
										LEFT join cliente c on c.ID_CLIENTE = mi.ID_CLIENTE
										INNER JOIN insumo i on i.ID_INSUMO = mid.ID_INSUMO
										LEFT JOIN casa p on p.ID_CASA = mi.ID_PROVEEDOR
										WHERE mid.ID_MOVIMIENTO_INSUMO = :id and mid.ESTADO=0 ;");
							$stmt->bindParam(':id', $id);
	$stmt->execute();

	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
		$insumo[] = $resultado;
	}

	if (count($insumo)){
		return $insumo;
	}
	else {
		return $insumo;
	}
}

public function getDetalleVenta($id) {
	$insumo = array();
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("SELECT mid.*, b.DESCRIPCION as BODEGA, mi.*, c.NOMBRES,
		c.APELLIDOS, p.DESCRIPCION as PROVEEDOR, i.DESCRIPCION as PRODUCTO
										FROM movimiento_insumo_det mid
										INNER JOIN movimiento_insumo mi on mi.ID_MOVIMIENTO_INSUMO = mid.ID_MOVIMIENTO_INSUMO
										INNER JOIN bodega b ON b.ID_BODEGA = mi.ID_BODEGA
										LEFT join cliente c on c.ID_CLIENTE = mi.ID_CLIENTE
										INNER JOIN insumo i on i.ID_INSUMO = mid.ID_INSUMO
										LEFT JOIN casa p on p.ID_CASA = mi.ID_PROVEEDOR
										WHERE mi.MOVIMIENTO =3 AND mi.CORRELATIVO = :id ;");
							$stmt->bindParam(':id', $id);
	$stmt->execute();

	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
		$insumo[] = $resultado;
	}

	if (count($insumo)){
		return $insumo;
	}
	else {
		return $insumo;
	}
}

public function updateVentaFel($corr,$serieFel,$numeroFel,$uuidFel,$fechaFel,$docNuevo, $correlativoNuevo) {
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("UPDATE movimiento_insumo SET
								UUID = :uuidFel, NUMEROFEL = :numeroFel, FECHA_CERTIFICACION = :fechaFel, SERIEFEL = :serieFel, ID_DOCUMENTO = :docNuevo, CORRELATIVO_DOCUMENTO = :correlativo
								WHERE MOVIMIENTO = 3 and CORRELATIVO = :corr");
	$stmt->bindParam(':serieFel', $serieFel);
	$stmt->bindParam(':numeroFel', $numeroFel);
	$stmt->bindParam(':uuidFel', $uuidFel);
	$stmt->bindParam(':fechaFel', $fechaFel);
	$stmt->bindParam(':corr', $corr);
	$stmt->bindParam(':docNuevo', $docNuevo);
	$stmt->bindParam(':correlativo', $correlativoNuevo);
	$update = $stmt->execute();
	return $update;
}

public function anulaVenta($corr) {
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("UPDATE movimiento_insumo SET
								ESTADO = 1
								WHERE MOVIMIENTO = 3 and CORRELATIVO = :corr");
	$stmt->bindParam(':corr', $corr);
	$update = $stmt->execute();
	return $update;
}

public function getListadoTraslados($fi,$ff) {
	$insumo = array();
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("SELECT mi.*, e.NOMBRES, e.APELLIDOS, b.DESCRIPCION as ORIGEN,
		 										b2.DESCRIPCION AS DESTINO
										FROM movimiento_insumo mi
										INNER JOIN empleado e on e.ID_EMPLEADO = mi.OPERADOR
										INNER JOIN bodega b on b.ID_BODEGA= mi.ID_BODEGA
										INNER JOIN bodega b2 on b2.ID_BODEGA= mi.BODEGA_DESTINO
										WHERE mi.FECHA BETWEEN :fi and :ff
										ORDER BY mi.ID_MOVIMIENTO_INSUMO DESC;");
							$stmt->bindParam(':fi', $fi);
							$stmt->bindParam(':ff', $ff);
	$stmt->execute();

	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
		$insumo[] = $resultado;
	}

	if (count($insumo)){
		return $insumo;
	}
	else {
		return $insumo;
	}
}



public function getListadoOperaciones($fi,$ff,$condicion) {

	$insumo = array();
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("SELECT mi.*, c.NOMBRES, c.APELLIDOS, cs.DESCRIPCION, mid.*, i.DESCRIPCION AS PRODUCTO, b.DESCRIPCION AS BODEGA_DESC
										FROM movimiento_insumo mi
										LEFT JOIN cliente c on c.ID_CLIENTE = mi.ID_CLIENTE
										LEFT JOIN casa cs on cs.ID_CASA = mi.ID_PROVEEDOR
										INNER JOIN movimiento_insumo_det mid on mid.ID_MOVIMIENTO_INSUMO = mi.ID_MOVIMIENTO_INSUMO
										INNER JOIN insumo i on i.ID_INSUMO = mid.ID_INSUMO
										INNER JOIN bodega b ON b.ID_BODEGA = mid.BODEGA
										WHERE mi.MOVIMIENTO <6 and mi.fecha BETWEEN :fi and :ff AND mi.ESTADO=0
										".$condicion."
										ORDER BY mi.FECHA, mi.ID_MOVIMIENTO_INSUMO DESC ;");
							$stmt->bindParam(':fi', $fi);
							$stmt->bindParam(':ff', $ff);
	$stmt->execute();

	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
		$insumo[] = $resultado;
	}

	if (count($insumo)){
		return $insumo;
	}
	else {
		return $insumo;
	}
}


public function getListadoOperacionesR($fi,$ff,$condicion) {

	$insumo = array();
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("SELECT mi.*, c.NOMBRES, c.APELLIDOS, cs.DESCRIPCION
										FROM movimiento_insumo mi
										LEFT JOIN cliente c on c.ID_CLIENTE = mi.ID_CLIENTE
										LEFT JOIN casa cs on cs.ID_CASA = mi.ID_PROVEEDOR
										WHERE mi.MOVIMIENTO <5 and mi.fecha BETWEEN :fi and :ff AND mi.ESTADO=0
										".$condicion."
										ORDER BY mi.FECHA, mi.ID_MOVIMIENTO_INSUMO DESC ;");
							$stmt->bindParam(':fi', $fi);
							$stmt->bindParam(':ff', $ff);
	$stmt->execute();

	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
		$insumo[] = $resultado;
	}

	if (count($insumo)){
		return $insumo;
	}
	else {
		return $insumo;
	}
}

public function getOperacion($fi,$ff,$condicion) {
	$insumo = array();
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("SELECT mi.*, c.NOMBRES, c.APELLIDOS, cs.DESCRIPCION, i.DESCRIPCION as PRODUCTO
										FROM movimiento_insumo mi
										INNER JOIN movimiento_insumo_det mid on mid.ID_MOVIMIENTO_INSUMO= mi.ID_MOVIMIENTO_INSUMO
										LEFT JOIN cliente c on c.ID_CLIENTE = mi.ID_CLIENTE
										LEFT JOIN casa cs on cs.ID_CASA = mi.ID_PROVEEDOR
										INNER JOIN insumo i on i.ID_INSUMO=mid.ID_INSUMO
										WHERE mi.MOVIMIENTO <5
										".$condicion."
										ORDER BY mi.ID_MOVIMIENTO_INSUMO DESC ;");
							$stmt->bindParam(':id', $id);
	$stmt->execute();

	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
		$insumo[] = $resultado;
	}

	if (count($insumo)){
		return $insumo;
	}
	else {
		return $insumo;
	}
}

public function getVenta($id) {
	$insumo = array();
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("SELECT mi.*, c.NOMBRES, c.APELLIDOS, cs.DESCRIPCION, fp.DESCRIPCION AS FORMAPAGO
										FROM movimiento_insumo mi
										LEFT JOIN cliente c on c.ID_CLIENTE = mi.ID_CLIENTE
										LEFT JOIN casa cs on cs.ID_CASA = mi.ID_PROVEEDOR
                                        INNER JOIN forma_pago fp on fp.ID_FORMA_PAGO= mi.FORMA_PAGO
										WHERE mi.MOVIMIENTO =3 and mi.CORRELATIVO = :id
									 ;");
							$stmt->bindParam(':id', $id);
	$stmt->execute();
	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
		$insumo[] = $resultado;
	}

	if (count($insumo)){
		return $insumo;
	}
	else {
		return $insumo;
	}
}




public function getVentasPorCajaDet($id) {
	$insumo = array();
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("SELECT mi.*, c.NOMBRES, c.APELLIDOS, fp.DESCRIPCION, i.DESCRIPCION as PRODUCTO, mid.CANTIDAD * mid.PRECIO as TOTAL_INSUMO, mid.CANTIDAD, mid.PRECIO
							FROM movimiento_insumo_det mid
                            INNER JOIN movimiento_insumo mi on mi.ID_MOVIMIENTO_INSUMO = mid.ID_MOVIMIENTO_INSUMO
							left JOIN cliente c on c.ID_CLIENTE = mi.ID_CLIENTE
							INNER JOIN forma_pago fp on fp.ID_FORMA_PAGO=mi.FORMA_PAGO
                            INNER JOIN insumo i on i.ID_INSUMO= mid.ID_INSUMO
							WHERE mi.ID_CAJA = :id and mi.MOVIMIENTO=3 AND FORMA_PAGO <>5 and mi.ESTADO=0
						");
							$stmt->bindParam(':id', $id);
	$stmt->execute();

	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
		$insumo[] = $resultado;
	}

	if (count($insumo)){
		return $insumo;
	}
	else {
		return $insumo;
	}
}

public function getVentasPorCajaDetCredito($id) {
	$insumo = array();
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("SELECT mi.*, c.NOMBRES, c.APELLIDOS, fp.DESCRIPCION, i.DESCRIPCION as PRODUCTO, mid.CANTIDAD * mid.PRECIO as TOTAL_INSUMO, mid.CANTIDAD, mid.PRECIO
							FROM movimiento_insumo_det mid
                            INNER JOIN movimiento_insumo mi on mi.ID_MOVIMIENTO_INSUMO = mid.ID_MOVIMIENTO_INSUMO
							left JOIN cliente c on c.ID_CLIENTE = mi.ID_CLIENTE
							INNER JOIN forma_pago fp on fp.ID_FORMA_PAGO=mi.FORMA_PAGO
                            INNER JOIN insumo i on i.ID_INSUMO= mid.ID_INSUMO
							WHERE mi.ID_CAJA = :id and mi.MOVIMIENTO=3 AND FORMA_PAGO =5 and mi.ESTADO=0
						");
							$stmt->bindParam(':id', $id);
	$stmt->execute();

	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
		$insumo[] = $resultado;
	}

	if (count($insumo)){
		return $insumo;
	}
	else {
		return $insumo;
	}
}
// SELECT mi.*, c.NOMBRES, c.APELLIDOS, fp.DESCRIPCION
// 						FROM movimiento_insumo mi
// 						INNER JOIN cliente c on c.ID_CLIENTE = mi.ID_CLIENTE
// 						INNER JOIN forma_pago fp on fp.ID_FORMA_PAGO=mi.FORMA_PAGO
// 						WHERE mi.ID_CAJA = :id;


public function getVentasPorCajaDetCierre($id) {
	$insumo = array();
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("SELECT mi.*, c.NOMBRES, c.APELLIDOS, fp.DESCRIPCION
							FROM movimiento_insumo mi
							LEFT JOIN cliente c on c.ID_CLIENTE = mi.ID_CLIENTE
							INNER JOIN forma_pago fp on fp.ID_FORMA_PAGO=mi.FORMA_PAGO
							WHERE mi.ID_CAJA = :id;");
							$stmt->bindParam(':id', $id);
	$stmt->execute();

	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
		$insumo[] = $resultado;
	}

	if (count($insumo)){
		return $insumo;
	}
	else {
		return $insumo;
	}
}

public function getVentasPorCaja($id) {
	$insumo = array();
	$pdo = parent::conexion();
	$stmt = $pdo->prepare("SELECT mi.*, c.NOMBRES, c.APELLIDOS, sum(mi.TOTAL) as TOTAL, fp.DESCRIPCION
							FROM movimiento_insumo mi
							LEFT JOIN cliente c on c.ID_CLIENTE = mi.ID_CLIENTE
							INNER JOIN forma_pago fp on fp.ID_FORMA_PAGO=mi.FORMA_PAGO
							WHERE mi.ID_CAJA = :id AND MOVIMIENTO=3 AND FORMA_PAGO <>5 AND mi.ESTADO=0
							GROUP BY mi.FORMA_PAGO ;");
							$stmt->bindParam(':id', $id);
	$stmt->execute();

	while( $resultado = $stmt->fetch(PDO::FETCH_ASSOC) ){
		$insumo[] = $resultado;
	}

	if (count($insumo)){
		return $insumo;
	}
	else {
		return $insumo;
	}
}


}
?>
