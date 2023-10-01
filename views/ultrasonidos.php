<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>
<script>
	$(function() {
		$("#ultrasonidos").submit(function() {
			$.ajax({
				type: "POST",
				url: $("#ultrasonidos").attr('action'),
				data: $("#ultrasonidos").serialize(),
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
<?php $min = 'Ultrasonidos';
$may = 'ULTRASONIDO';
$minS = 'Ultrasonido'; ?>
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
					<form id="ultrasonidos" action="" method="post">
						<div class="modal-body">

							<div class="form-group col-xs-12">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa  fa-list-alt"></i></span>
									<input name="nombreUltrasonido" type="text" class="form-control" placeholder="Nombre del Ultrasonido " required>
								</div>
							</div>
							<div class="form-group col-xs-12">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-money"></i></span>
									<input type="number" name="precioUltrasonido" min="0" step="0.01" class="form-control" placeholder="Precio" required>
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
						<a class="btn-danger pull-right d-flex" style="margin:1rem; padding:1rem; border-radius:3px" href="reportes/reporte_ultrasonido.php" target="_blank" style="text-decoration:none;">PDF</a>
					</div>
					<div class="box-body table-responsive">
						<script>
							$(document).ready(function() {
								$("#tablaUltrasonidos").dataTable({
									"order": [
										[0, 'desc']
									],
									//"serverSide": true,
									"responsive": true,
									//"paging": true,
									//"processing": true,
									//"ajax": "<?php echo BASE_DIR; ?>serverside/getDataUltrasonidos.php",
									"pageLength": 50,
									"fnCreatedRow": function(nRow, aData, iDataIndex) {

										$('td:eq(3)', nRow).append('<a title="Editar" href="<?php echo BASE_DIR; ?>editar-ultrasonido/' + aData[0] + '-' + aData[0] + '"><i class="fa fa-edit" style="font-size:16px; margin-right:3px"></i></a>').css('text-align', 'center')
										$('td:eq(3)', nRow).append('<a title="Eliminar" href="<?php echo BASE_DIR; ?>eliminarUltrasonido/' + aData[0] + '-' + aData[0] + '"><i class="fa fa-close" style="color:red; font-size:16px"></i></a>').css('text-align', 'center')
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
						<table id="tablaUltrasonidos" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Código.</th>
									<th>Nombre ultrasonido</th>
									<th>Precio</th>
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
										<td><?php echo $columna['idproducto_servicio']; ?></td>
										<td><?php echo $columna['nombre']; ?></td>
										<td><?php echo $columna['precio']; ?></td>
										<td class="acciones">
										</td>
									</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<th>Código.</th>
									<th>Nombre ultrasonido</th>
									<th>Precio</th>
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