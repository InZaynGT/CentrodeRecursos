<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>
<script>
$(function(){
	$("#servicios").submit(function() {
			$.ajax({
				type: "POST",
				url: $("#servicios").attr('action'),
				data: $("#servicios").serialize(),
				beforeSend: function () {
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
<?php $min='Servicios'; $may='SERVICIO'; $minS='Servicio'; ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
             <?php echo $min; ?>
            <small>Mantenimiento de  <?php echo $min; ?></small>
        </h1>
		<ol class="breadcrumb">
			<li>
				<a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#compose-modal"><i class="fa fa-file"></i> AGREGAR  <?php echo $may; ?></a>
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
                        <h4 class="modal-title"><i class="fa fa-file-o"></i> Registrar  <?php echo $minS; ?></h4>
                    </div>
                    <form id="servicios" action="" method="post">
                        <div class="modal-body">

                            <div class="form-group col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa  fa-list-alt"></i></span>
                                    <input name="nombreServicio" type="text" class="form-control" placeholder="Nombre de servicio " required>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                    <input type="number" name="precioServicio" min="0" step="0.01" class="form-control" placeholder="Precio" required>
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
					<h3 class="box-title">Listado de  <?php echo $min; ?></h3>
				</div>
				<div class="box-body table-responsive">
						<script>
							$(document).ready(function() {
								$("#tablaServicios").dataTable({
                                    
									dom: 'Bfrtip',
									buttons:
									[
										{ extend: 'excel', footer: true },
										{ extend: 'pdf', footer: true,
											orientation: 'landscape',
											pageSize: 'LETTER' ,
											title: 'Administraci&oacute;n \nListado de  <?php echo $min; ?>'
										},
										{ extend: 'print', footer: true,
											orientation: 'landscape',
											pageSize: 'LETTER' ,
											title: 'Administraci&oacute;n <br/>Listado de  <?php echo $min; ?>'
										}
									]

								});
							});
						</script>
					<table id="tablaServicios" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Código.</th>
								<th>Servicio</th>
								<th>Precio</th>
								<th>Acciones</th>
								
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($servicios as $row){
							?>
								<tr>
									<td><?php echo $row['idservicio']; ?></td>
									<td><?php echo $row['nombre']; ?></td>
									<td>Q. <?php echo number_format($row['precio'], 2,'.',',') ; ?></td>
									<td class="acciones">
										<a title="Editar" href="<?php echo BASE_DIR; ?>editar-servicio/<?php echo slug($row['idservicio']); ?>-<?php echo slug($row['nombre']); ?>"><i class="fa fa-edit"></i></a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th>Código.</th>
								<th>Servicio</th>
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
