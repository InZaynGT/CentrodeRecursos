<script>
	$(document).ready(function(){
		$("#editarServicio").submit(function() {
			$.ajax({
				type: "POST",
				url: $("#editarServicio").attr('action'),
				data: $("#editarServicio").serialize(),
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
<?php $min='Servicios'; $may='SERVICIOS'; $minS='Servicio'; ?>
<aside class="right-side">
  <section class="content-header">
        <h1>
            <?php echo $min; ?>
            <small>Mantenimiento de <?php echo $min; ?></small>
        </h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo BASE_DIR; ?>servicios" class="btn btn-primary btn-sm"><i class="fa fa-list"></i> LISTADO DE <?php echo $may; ?></a>
			</li>
		</ol>
  </section>

<section class="content">
	<div class="row">
		<div class="col-sm-7">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Editar <?php echo $minS; ?></h3>
				</div>
				<form id="editarServicio" action="" method="post">
					<div class="modal-body">
						<div class="form-group col-xs-11">
							<div class="form-group col-xs-11">
									<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-tags"></i></span>
											<input name="nombre" type="text" value="<?php echo $servicio['nombre']; ?>" class="form-control" required>
									</div>
							</div>
                            <div class="form-group col-xs-11">
									<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-money"></i></span>
											<input name="precio" type="number" step="0.01" value="<?php echo $servicio['precio'] ?>" class="form-control" required>
									</div>
							</div>
						</div>
						<div class="form-group col-xs-11">
							<div id="result"></div>
						</div>

						<div class="clear"></div>
					</div>
					<div class="modal-footer clearfix">
						<button type="submit" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Guardar</button>
					</div>
				</form>
			</div>
		</div>


		<div class="col-sm-4">
			<div class="box box-solid">
				<div class="box-header">
					<i class="fa fa-file-text-o"></i>
					<h3 class="box-title">Informaci&oacute;n Actual</h3>
				</div>
				<div class="box-body">
					<table class="table table-bordered">
						<tbody>
						<tr>
							<th>C&oacute;digo:</th>
							<td><?php echo $servicio['idproducto_servicio']; ?></td>
						</tr>
						<tr>
							<th>Nombre:</th>
							<td><?php echo $servicio['nombre']; ?></td>
						</tr>
                        <tr>
							<th>Precio:</th>
							<td>Q. <?php echo number_format($servicio['precio'],2,'.',',')  ?></td>
						</tr>
					</tbody>
				</table>
				</div>
			</div>
			<?php
				if (isset($_GET['status']) && $_GET['status'] == 'update'){
			?>
					<div class="col-xs-12" style="margin-bottom: 16px;">
						<div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
							<b>OK:</b> Registro actualizado correctamente.
						</div>
					</div>
			<?php	} ?>
		</div>

	</div>
</section>
