<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>
<script src="<?php echo BASE_DIR; ?>js/jquery-1.11.1.js"></script>
<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="confirmarEliminarModal" tabindex="-1" role="dialog"
	aria-labelledby="confirmarEliminarModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="confirmarEliminarModalLabel">Confirmar Eliminación</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				¿Está seguro de que desea eliminar a este paciente?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-danger" id="confirmarEliminar">Eliminar</button>
			</div>
		</div>
	</div>
</div>


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
<?php $min = 'Consultas de '. $array[0]['nombre'];
$may = 'CONSULTAS';
$minS = 'Consultas'; ?>
<aside class="right-side">
	<section class="content-header">
		<h1 style="text-align: center;">
		<div style="float: left;">
			<a href="<?php echo BASE_DIR; ?>consultas" class="btn btn-default btn-sm" style="text-decoration:none;">
				<i class="fa fa-arrow-left"></i> Listado de Pacientes
			</a>
		</div>
			<?php echo $min; ?>
		</h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo BASE_DIR; ?>agregar-consulta" class="btn btn-primary btn-sm"><i
						class="fa fa-file"></i> AGREGAR CONSULTA</a>
			</li>
		</ol>
	</section>
	<section class="content">

		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Listado de
							<?php echo $min; ?>
						</h3>
					</div>
					<div>
						<a class="btn-danger pull-right d-flex" style="margin:1rem; padding:1rem; border-radius:3px"
							href="reportes/reporte_consulta.php" target="_blank" style="text-decoration:none;">PDF</a>
					</div>
					<div class="box-body table-responsive">
						<script>
							$(document).ready(function () {
								var idEliminar; // Variable para almacenar el ID de la consulta a eliminar

								$("#tablaConsultas").dataTable({
									"order": [
										[1, 'desc']
									],
									"responsive": true,
									"pageLength": 50,
									"fnCreatedRow": function (nRow, aData, iDataIndex) {
										// Agregar enlace para eliminar consulta con modal de confirmación
										$('td:eq(3)', nRow).append('<a title="Editar Consulta" href="<?php echo BASE_DIR; ?>editar-consulta/' + aData[0] + '-' + aData[0] + '"><i class="fa fa-edit" style="font-size:30px; margin-right:3px"></i></a>').css('text-align', 'center')
										$('td:eq(3)', nRow).append('<a title="Visualizar Consulta" href="<?php echo BASE_DIR; ?>visualizar-consulta/' + aData[0] + '-' + aData[0] + '"><i class="fa fa-eye" style="color:green; font-size:30px"></i></a>').css('text-align', 'center')
										$('td:eq(3)', nRow).append('<a title="Eliminar Consulta" href="#" data-toggle="modal" data-target="#confirmarEliminarModal" data-id="' + aData[0] + '"><i class="fa fa-trash" style="font-size:30px; margin-right:3px"></i></a>').css('text-align', 'center');
									},
									dom: 'Bfrtip',
									buttons: [{
										extend: 'excel',
										footer: true,
									}]
								});

								// Capturar el ID cuando se hace clic en "Eliminar Consulta"
								$('a[data-target="#confirmarEliminarModal"]').click(function () {
									idEliminar = $(this).data('id');
								});

								// Acción al confirmar la eliminación
								$('#confirmarEliminar').click(function () {
									// Realizar la eliminación utilizando AJAX o como lo estás haciendo actualmente
									$.ajax({
										url: 'eliminar-consulta', // Cambia la URL a tu script de eliminación
										type: 'POST',
										data: { idItem: idEliminar },
										success: function (response) {
											window.location.href = "<?php echo BASE_DIR; ?>consultas";
										},
										error: function () {
											alert('Error al eliminar la consulta.');
										}
									});

									// Cierra el modal
									$('#confirmarEliminarModal').modal('hide');
								});
							});
						</script>


						<table id="tablaConsultas" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Nro. Consulta</th>
									<th>Fecha de Consulta</th>
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
										<td>
											<?php echo $columna['idconsulta']; ?>
										</td>
										<td>
											<?php echo $columna['fechaconsulta']; ?>
										</td>
										<td style="text-align: center;">
											<?php if ($columna['estado'] == '1')
												echo '<span class="label label-info">Activa</span>';
											else if ($columna['estado'] == '2')
												echo '<span class="label label-warning">Terminada</span>';
											else if ($columna['estado'] == '3')
												echo '<span class="label label-success">Pagada</span>';
											else if ($columna['estado'] == '4')
												echo '<span class="label label-danger">Anulada</span>';
											; ?>
										</td>
										<td class="acciones"></td>
									</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<th>Nro. Consulta</th>
									<th>Fecha</th>
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