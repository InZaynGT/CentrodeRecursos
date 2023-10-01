<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>

<style type="text/css">
	#container {
		position: relative;
	}
</style>
<style type="text/css">
	.userinfo2 {
		font-family: 'pt_sansregular';
		color: white;
		background: #2E8B57;
		border-radius: 3px;
		border: 1px solid transparent;
		border-color: #556B2F;
		width: 40px;
		height: 40px;
		border-radius: 3px;
		-webkit-box-shadow: inset 0px -2px 0px 0px rgba(0, 0, 0, 0.09);
		-moz-box-shadow: inset 0px -2px 0px 0px rgba(0, 0, 0, 0.09);
		box-shadow: inset 0px -1px 0px 0px rgba(0, 0, 0, 0.09);
	}

	.userinfo:hover {
		background-color: #1462ba;
	}
</style>
<?php $min = 'Controles Prenatales';
$may = 'CONTROLES PRENATALES';
$minS = 'controles prenatales'; ?>
<aside class="right-side">
	<section class="content-header">
		<h1>
			<?php echo $min; ?>
			<small>Mantenimiento de <?php echo $min; ?></small>
		</h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo BASE_DIR; ?>agregar-control" class="btn btn-primary btn-sm"><i class="fa fa-file"></i> AGREGAR CONTROL PRENATAL</a>
			</li>
		</ol>
	</section>
	<section class="content">

		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Listado de <?php echo $min; ?></h3>
					</div>
					<div>
						<a class="btn-danger pull-right d-flex" style="margin:1rem; padding:1rem; border-radius:3px" href="reportes/reporte_consulta_prenatal.php" target="_blank" style="text-decoration:none;">PDF</a>
					</div>
					<div class="box-body table-responsive">
						<script>
							$(document).ready(function() {
								$("#tablaConsultas").dataTable({
									"order": [
										[0, 'desc']
									],
									//"serverSide": true,
									"responsive": true,
									//"paging": true,
									//"processing": true,
									//"ajax": "<?php echo BASE_DIR; ?>serverside/getDataConsultas.php",
									"pageLength": 50,
									"fnCreatedRow": function(nRow, aData, iDataIndex) {
										// $('td:eq(4)', nRow).html('<span class="label label-warning">Activa</span').css('text-align', 'center')
										$('td:eq(5)', nRow).append('<a title="Visualizar" href="<?php echo BASE_DIR; ?>editar-control/' + aData[0] + '-' + aData[0] + '"><i class="fa fa-eye" style="font-size:16px; margin-right:3px"></i></a>').css('text-align', 'center')
										$('td:eq(5)', nRow).append('<a title="Imprimir Receta" href="<?php echo BASE_DIR; ?>imprimir-receta/' + aData[0] + '-' + aData[1] + '"><i class="fa fa-print" style="color:green; font-size:16px; margin-right:3px"></i></a>').css('text-align', 'center')
										// $('td:eq(5)', nRow).append('<a title="Agregar Imágenes" href="<?php echo BASE_DIR; ?>agregar-imagenes/' + aData[0] + '-' + aData[1] + '"><i class="fa fa-camera" style="font-size:16px; margin-right:3px"></i></a>').css('text-align', 'center')
										$('td:eq(5)', nRow).append('<a title="Cobrar" href="<?php echo BASE_DIR; ?>cobrar-consulta/' + aData[0] + '-' + aData[1] + '"><i class="fa fa-money" style="font-size:16px; margin-right:3px"></i></a>').css('text-align', 'center')
										$('td:eq(5)', nRow).append('<a title="Eliminar Consulta" href="<?php echo BASE_DIR; ?>consultaEliminar/' + aData[0] + '-' + aData[2] + '"><i class="fa fa-close" style="color:red; font-size:16px"></i></a>').css('text-align', 'center')
									},

									dom: 'Bfrtip',
									buttons: [{
										extend: 'excel',
										footer: true,
									}, ]

								});
							});
						</script>

						<table id="tablaConsultas" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Nro. Consulta</th>
									<th>Fecha</th>
									<th>Código</th>
									<th>Paciente</th>
									<th>Estado</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								foreach ($array as $columna) {
									// $fecha = new DateTime($columna['fecha']);
								?>
									<tr>
										<td><?php echo $columna['idconsulta']; ?></td>
										<td><?php echo $columna['fechaconsulta']; ?></td>
										<td><?php echo $columna['idpaciente']; ?></td>
										<td><?php echo $columna['nombres']; ?></td>
										<td style="text-align: center;">
											<?php if ($columna{'estado'} == '1') echo '<span class="label label-info">Activa</span>';
											else if ($columna['estado'] == '2') echo '<span class="label label-warning">Terminada</span>';
											else if ($columna['estado'] == '3') echo '<span class="label label-success">Pagada</span>';
											else if ($columna['estado'] == '4') echo '<span class="label label-danger">Anulada</span>';; ?></td>
										<td class="acciones"></td>
									</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<th>Nro. Consulta</th>
									<th>Fecha</th>
									<th>Código</th>
									<th>Paciente</th>
									<th>Estado</th>
									<th>Acciones</th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</aside>