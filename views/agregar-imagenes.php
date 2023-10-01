<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>
<script src="<?php echo BASE_DIR; ?>js/webcam.js"></script>


<style>
	@media print {
		a:after {
			content: '';
		}

		a[href]:after {
			content: none !important;
		}


	}
</style>

<script>
	$(document).ready(function() {
		$("#capture").hide();
		$('#off').hide();

		$("#frmNew").submit(function() {
			var formData = new FormData($(this)[0]);
			$.ajax({
				url: $("#frmNew").attr('action'),
				type: 'POST',
				data: formData,
				async: false,
				beforeSend: function() {
					$("#result2").html('<div class="loading"></div>');
				},
				success: function(data) {
					$("#result2").html(data);
				},
				cache: false,
				contentType: false,
				processData: false
			});

			return false;
		});


	});

	//Abre la camara
	function openCamera() {
		Webcam.reset()
		$('#results').show()

		Webcam.set({
			width: 550,
			height: 480,
			//dest_width : 720,
			//dest_height: 480,
			image_format: 'jpeg',
			jpeg_quality: 100
		});

		Webcam.attach('#my_camera')
		var x = document.getElementById("capture");
		if (x.style.display === "none") {
			x.style.display = "inline-block"
			$("#off").show()
			$("#open").hide()
		} else {
			x.style.display = "none";
			$("#off").hide()
		}
	}

	//toma la foto y se asigna a la etiqueta img
	function take_snapshot() {
		Webcam.snap(function(data_uri) {
			$(".image-tag").val(data_uri)
			document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';
		})
		Webcam.reset()
		$('#off').hide()
		$('#open').hide()

		//ocultar el boton tomar foto una vez se toma la foto
		var x = document.getElementById("capture");
		if (x.style.display === "none") {
			x.style.display = "block";

		} else {
			x.style.display = "none";

		}
	}

	function camera_off() {
		Webcam.reset()
		$('#capture').hide()
		$('#off').hide()
		$('#open').show()
		$('#results').hide()

	}

	//abrir la imagen en una nueva ventana
	function newTabImage() {
		var image = new Image();
		image.src = $('#imgUltra').attr('src');

		var w = window.open("", '_blank');
		w.document.write(image.outerHTML);
		w.document.close();
	}
</script>
<?php $min = 'Fotos de Exámenes';
$may = 'FOTOS DE EXÁMENES';
$minS = 'Fotos de exámenes'; ?>
<aside class="right-side">
	<section class="content-header">

		<ol class="breadcrumb">
			<li>
				<a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#compose-modal"><i class="fa fa-camera"></i>AGREGAR IMAGEN</a>
				<a onclick="window.print();" class="btn btn-warning btn-sm"><i class="fa fa-print"></i> IMPRIMIR</a>
				<a href="<?php echo BASE_DIR; ?>consultas" class="btn btn-primary btn-sm"><i class="fa "></i> LISTADO DE CONSULTAS</a>
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
						<h4 class="modal-title"><i class="fa fa-file-o"></i> Agregar nueva foto</h4>
					</div>
					<form id="frmNew" method="POST">
						<div class="modal-body">


							<div class="form-group col-xs-12">
								<div class="input-group ">
									<span class="input-group-addon">Servicio:</span>
									<select name="idUltrasonido" class="form-control m-input" required>
										<option selected disabled>Seleccionar</option>
										<?php foreach ($ultrasonidos as $tipos) { ?>
											<option value="<?php echo $tipos['idproducto_servicio'] ?>-<?php echo $tipos['nombre']?>"></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group col-sm-11">
								<div class="foto-div" id="results">
									<div id="my_camera"></div>
								</div>
							</div>
							<div class="form-group col-sm-11">
								<span class="input-group-addon"><i class="fa fa-camera"> Foto:</i>
									<input type="button" id="capture" value="Tomar foto" onClick="take_snapshot() ">
									<input type="button" id="off" value="Apagar" onClick="camera_off()">
									<input name="imagen" type="button" id="open" value="Encender cámara" onClick="openCamera()">
									<input type="hidden" name="imageUltrasonido" class="image-tag">

							</div>


							<div class="form-group col-xs-12">
								<div id="result2"></div>
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

					</div>
					<div class="box-body table-responsive">
						<h3>

							<small><?php echo $min; ?></small>
						</h3>
						<table id="example1234" class="table table-bordered table-striped">

							<thead>
								<tr>
									<th width='5%'>No.</th>
									<th width='15%'>Número de consulta</th>
									<th width='20%'>Servicio</th>
									<th>Imagen</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								if (!empty($imgsUltrasonido)) {
									foreach ($imgsUltrasonido as $columna) {
										if($columna['tipo']==1){
											$imagesDir = "/xampp/uploads/ultrasonidos/";
										}else{
											$imagesDir = "/xampp/uploads/laboratorios/";

										}
										
										$content = @file_get_contents($imagesDir . $columna['img_ultrasonido'] . '.png');
										$url = $imagesDir . $columna['img_ultrasonido'] . '.png';
										$content = base64_encode($content);

										base64_decode($content);
								?>
										<tr>
											<td><?php echo $i++; ?></td>

											<td><?php echo $columna['idconsulta']; ?></td>
											<td><?php echo $columna['nombre']; ?></td>
											<td width='20%'>
												<center>

													<img id="imgUltra" class="img-paciente" src="data:image/png;base64, <?php echo $content ?> " <?php echo empty($content) ? '' : 'onclick="newTabImage()"' ?> />

												</center>
											</td>
										</tr>
								<?php }
								} ?>
							</tbody>

							<tfoot>
								<tr>
									<th>No.</th>
									<th>Número de consulta</th>
									<th>Servicio</th>
									<th>Imagen</th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</aside>