<!DOCTYPE html>
<html>
<head>
	<title>Recibo de Caja</title>
	<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" >
	<style>
		@media print {
			body {-webkit-print-color-adjust:exact;}
		}
		@import url('<?php echo BASE_DIR; ?>css/fonts/ptsans_regular_macroman/stylesheet.css');
		* {
			box-sizing: border-box;
		}
		body {
			margin:0;
			font-family:'pt_sansregular', Arial, Tahoma, Verdana;
			font-size:13px;
		}
		#contenido {
			/*border: solid 3px #000;*/
			width:760px;
			height:990px;
			position: relative;
		}
		h3, h4 {
			text-align:center;
			margin:8px;
		}
		table {
			border-collapse: collapse;
			border-spacing: 0px;
		}
		th, td {
			padding:2px 3px;
		}

		.center {
			text-align:center;
		}
		.right {
			text-align:right;
		}
		footer {
			margin:auto;
		}
		#divfooter{
			position: absolute;
			bottom: 0;
			width: 100%;
		}
		.datos th {
			padding-right:10px;
			text-align:left;
		}
		#logo,
		#texto {
			float:left;
		}
		#logo img {
			max-height:70px;
		}
		#numero {
			float:right;
			width:220px;
		}
		#numero .bordered{
			border:solid 1px #000;
		}
		.clear {
			clear:both;
		}
		#tblmar {
			float:right;
			width:320px;
		}
	</style>
	<script>
		window.print();
	</script>
</head>
<body>
<div id="contenido">
	<div id="logo">

		<table>
			<tr>
				<td><img src="<?php echo BASE_DIR?>/img/clinicalogo.jpg" /></td>
			</tr>
		</table>

	</div>
	<div id="numero">
		<table width="100%">
			<tr>
				<th >N&uacute;mero de Recibo</th>
			</tr>
			<tr>
				<td class="center"><?php echo $reciboEnc['correlativo'] ?></td>
			</tr>
		</table>
	</div>
	<div class="clear"></div>
	<div class="center">
		<h2 style="margin: 0px 5px 0px;"></h2>
	</div>

	<table border="0" class="datos" width="100%">
		<tr>
						<td ><b>FECHA:</b> &nbsp; <?php echo $reciboEnc['fecha'] ?></td>
				<td width="50%"><b>POR:</b> &nbsp;Q. <?php echo $reciboEnc['monto'] ?></td>
		</tr>

		<tr>
			<td ><b>RECIBIMOS DE:</b> &nbsp; <?php echo $reciboEnc['nombres'] ?></td>
			<td ><b>CÓDIGO DE PACIENTE:</b> &nbsp; <?php echo $reciboEnc['idpaciente'] ?>  </td>
		</tr>
		<tr>

			<td colspan="2"><b>LA CANTIDAD DE:</b> &nbsp; 
				<?php
				list($whole, $decimal) = sscanf($reciboEnc['monto'], '%d.%d');
				$f = new NumberFormatter("sp",NumberFormatter::SPELLOUT);
				echo strtoupper(sprintf("%s quetzales con %s centavos", $f->format($whole), $f->format($decimal)));
				 
				?> 
			</td>
		</tr>

	</table>

	<br/>

	<table border="0" width="100%">
		<thead>
			<tr>
				<th class="0" width="10%"></th>
				<th style="background-color:#4682B4; color:white;" width='20%'>Código.</th>
				<th style="background-color:#4682B4; color:white;" width='20%'>Descripción</th>
				<th style="background-color:#4682B4; color:white;" width='20%'>Cantidad</th>
				<th style="background-color:#4682B4; color:white;" width='20%' class="center">Monto</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($reciboDet as $det) {?>
				<tr>
					<td border="0"></td>
						<td width='20%' class='center'><?php echo $det['codigoproducto'] ?></td>
						<td width='20%' class='center'><?php echo $det['nombre'] ?></td>
						<td width='20%' class='center'><?php echo $det['cantidad'] ?></td>
						<td width='20%' class="center">Q. <?php echo $det['precio'] ?></td>
				</tr>
			<?php } ?> 
		</tbody>
		<tfoot>
			<tr>
				<td border="0"></td>
				<th width='20%'> </th>
				<th width='20%'> </th>
				<th width='20%' class="right">TOTAL: </th>
				<th width='20%' class="center">Q.<?php echo $reciboEnc['monto']?></th>

			</tr>
		</tfoot>
	</table>
	<br/>
	<table border="0" width="100%">
		<tr>
			<td >
				<strong>FORMA DE PAGO:</strong> EFECTIVO
            </td>
		</tr>
	</table>

	<table border="0" width="100%">
		<tr>
			<td width="70%">
				<strong>OBSERVACIONES:</strong>
				<p><i><?php echo $reciboEnc['observaciones']?></i></p>
							<p>NOTA: El recibo de caja es el único comprobante de pago que reconoce la institución. </br> </p>
			</td>

						<td width="20%">
							<!-- <p>	&nbsp;&nbsp; <b>Saldo Anterior </b></br> -->
				
			<!-- &nbsp;&nbsp;	<b>Saldo Pendiente </b></p> -->
			</td>

			<td width="20%" class="right">
								<!-- <p>	&nbsp;&nbsp; Q.100.00</br> -->
					
						<!-- &nbsp;&nbsp;	Q.-300.00</p> -->
						</td>
		</tr>
	</table>

	<table border="0" width="100%">
		<tr>
			<td rowspan="3" valign="bottom">
				<div class="center">
					______________________________<br/>
					<small><b>Sello y firma Caja</b></small>
				</div>
			</td>

			<td rowspan="3" valign="bottom">
				<br/>
				<br/>
				<br/>
				<div class="center">
					______________________________<br/>
					<small><b>Nombre y firma paciente</b></small>
				</div>
			</td>
		</tr>

	</table>
</div>
</body>
</html>