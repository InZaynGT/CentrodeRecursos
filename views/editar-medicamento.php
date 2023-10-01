<script>
	$(document).ready(function(){

        $.ajax({
            type:"POST",
            dataType: "JSON",
            url: "<?php echo BASE_DIR; ?>editar-medicamento",
            data : {
                idMedicamento : <?php echo $idItem ?>
            },
            success : function(data) {

                $('#codigoFiltro').val(data.codigofiltro)
                $('#nombreMedicamento').val(data.nombre)
                $('#dosificacion').val(data.dosificacion)

                var htmlString = 
                "<tr>"+
                "<th>C&oacute;digo filtro:</th>"+
                "<td>"+data.codigofiltro+" </td>"+
                "</tr>"+
                "<tr>"+
                "<th>Nombre medicamento:</th>"+
                "<td>"+data.nombre+" </td>"+
                "</tr>"+
                "<tr>"+
                "<th>C&oacute;digo filtro:</th>"+
                "<td>"+data.dosificacion+" </td>"+
                "</tr>"

                $('#infoTable tbody').append(htmlString)
                
            }

        })


		$("#editarMedicamento").submit(function() {
			$.ajax({
				type: "POST",
				url: $("#editarMedicamento").attr('action'),
				url: $("#editarMedicamento").attr('action'),
				data: $("#editarMedicamento").serialize(),
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
<?php $min='Medicamentos'; $may='MEDICAMENTOS'; $minS='Medicamento'; ?>
<aside class="right-side">
  <section class="content-header">
        <h1>
            <?php echo $min; ?>
            <small>Mantenimiento de <?php echo $min; ?></small>
        </h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo BASE_DIR; ?>medicamentos" class="btn btn-primary btn-sm"><i class="fa fa-list"></i> LISTADO DE <?php echo $may; ?></a>
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
				<form id="editarMedicamento" method="post">
					<div class="modal-body">
						<div class="form-group col-xs-11">
							<div class="form-group col-xs-11">
									<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-filter"></i></span>
											<input id="codigoFiltro" name="codigoFiltro" type="text" placeholder="Código para filtro" class="form-control" maxlength="12" required>
									</div>
							</div>
                            <div class="form-group col-xs-11">
									<div class="input-group">
											<span class="input-group-addon"><i class="fa  fa-list-alt"></i></span>
											<input id="nombreMedicamento" name="nombreMedicamento" type="text" placeholder="Nombre de medicamento"  class="form-control" maxlength="45" required>
									</div>
							</div>
                            <div class="form-group col-xs-11">
									<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
											<input id="dosificacion" name="dosificacion" type="text" placeholder="Dosificación"  class="form-control" maxlength="60" required>
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
					<table id="infoTable" class="table table-bordered">
						<tbody>
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
