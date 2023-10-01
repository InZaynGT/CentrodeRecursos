<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>
<script>
	$(function() {
		$("#medicamentos").submit(function() {
			$.ajax({
				type: "POST",
				url: $("#medicamentos").attr('action'),
				data: $("#medicamentos").serialize(),
				beforeSend: function() {
					$("#result").html('<div class="loading"></div>');
				},
				success: function(data) {
					$("#result").html(data);
				}
			});
			return false;
		});
	});
</script>
<?php $min = 'Medicamentos';
$may = 'MEDICAMENTO';
$minS = 'Medicamento'; ?>
<aside class="right-side">
	<section class="content-header">
		<h1>
			<?php echo $min; ?>
			<small>Mantenimiento de <?php echo $min; ?></small>
		</h1>
		<ol class="breadcrumb">
			<li>
				<a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#compose-modal"><i class="fa fa-file"></i> AGREGAR <?php echo $may; ?></a>
			</li>
		</ol>
	</section>
	<section class="content">

		<!-- MODAL -->
		<div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title"><i class="fa fa-file-o"></i> Registrar <?php echo $minS; ?></h4>
					</div>
					<form id="medicamentos" method="post">
						<div class="modal-body">
							<div class="form-group col-xs-5">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa  fa-filter"></i></span>
									<input name="filtroMedicamento" type="text" class="form-control" placeholder="Código para filtro" maxlength="12" required>
								</div>
							</div>

							<div class="form-group col-xs-12">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa  fa-list-alt"></i></span>
									<input name="nombreMedicamento" type="text" class="form-control" placeholder="Nombre del Medicamento " required>
								</div>
							</div>
							<div class="form-group col-xs-12">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
									<input type="text" name="dosificacionMedicamento" class="form-control" placeholder="Dosificación" maxlength="60" required>
								</div>
							</div>
							<div class="form-group col-xs-12">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
									<input type="text" name="usoMedicamento" class="form-control" placeholder="Uso" maxlength="75" required>
								</div>
							</div>
							<div class="form-group col-xs-6">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-money"></i></span>
									<input type="number" name="costo" class="form-control" step="any" placeholder="Costo">
								</div>
							</div>
							<div class="form-group col-xs-6">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-money"></i></span>
									<input type="number" name="precioPMedicamento" class="form-control" step="any" placeholder="precioP" >
								</div>
							</div>
							<div class="form-group col-xs-6">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-money"></i></span>
									<input type="number" name="precioAMedicamento" class="form-control" step="any" placeholder="precioA" >
								</div>
							</div>
							<div class="form-group col-xs-12">
								<div id="result"></div>
							</div>

							<div class="clear"></div>
						</div>
						<div class="modal-footer clearfix">

							<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>

							<button type="submit" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Guardar</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Listado de <?php echo $min; ?></h3>
					</div>
					<div>
						<a class="btn-danger pull-right d-flex" style="margin:1rem; padding:1rem; border-radius:3px" href="reportes/reporte_medicamento.php" target="_blank" style="text-decoration:none;">PDF</a>
					</div>
					<div class="box-body table-responsive">
						<script>
							$(document).ready(function() {
								$("#tablaMedicamentos").dataTable({
									//"serverSide": true,
									"responsive": true,
									//"paging": true,
									//"processing": true,
									//"ajax": "<?php echo BASE_DIR; ?>serverside/getDataMedicamentos.php",
									"pageLength": 50,
									"fnCreatedRow": function(nRow, aData, iDataIndex) {

										$('td:eq(6)', nRow).append('<a title="Editar" href="<?php echo BASE_DIR; ?>editar-medicamento/' + aData[0] + '-' + aData[0] + '"><i class="fa fa-edit" style="font-size:16px; margin-right:2px"></i></a>');
										$('td:eq(6)', nRow).append('<a title="Eliminar" href="<?php echo BASE_DIR; ?>eliminar-medicamento/' + aData[0] + '-' + aData[0] + '"><i class="fa fa-close" style="color:red; font-size:16px"></i></a>');

									},

									dom: 'Bfrtip',
									order: [],
									buttons: [{
										extend: 'excel',
										footer: true
									}, ]

								});
							});
						</script>
						<table id="tablaMedicamentos" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Id.</th>
									<th>Código filtro</th>
									<th>Nombre</th>
									<th>Dosificación</th>
									<th>Uso</th>
									<th>Existencia</th>
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
										<td><?php echo $columna['idmedicamento']; ?></td>
										<td><?php echo $columna['codigofiltro']; ?></td>
										<td><?php echo $columna['nombre']; ?></td>
										<td><?php echo $columna['dosificacion']; ?></td>
										<td><?php echo $columna['uso']; ?></td>
										<td><?php echo $columna['existencia']; ?></td>
										<td class="acciones"></td>
									</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<th>Id.</th>
									<th>Código filtro</th>
									<th>Nombre</th>
									<th>Dosificación</th>
									<th>Uso</th>
									<td>Existencia</td>
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