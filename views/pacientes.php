<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>
<script src="<?php echo BASE_DIR; ?>js/webcam.js"></script>



<script type="text/javascript">
    $(function() {
        $("#addPacient").submit(function() {
            $.ajax({
                type: "POST",
                url: $("#addPacient").attr('action'),
                data: $("#addPacient").serialize(),
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


    function openCamera() {
        Webcam.set({
            width: 200,
            height: 150,
            dest_width: 400,
            dest_height : 300,
            image_format: 'jpeg',
            jpeg_quality: 100
        });

        Webcam.attach('#my_camera')
        var x = document.getElementById("capture");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }

    }

    function take_snapshot() {
        Webcam.snap(function(data_uri) {
            $(".image-tag").val(data_uri)
            document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';

        })
        Webcam.reset()

        //ocultar el boton tomar foto una vez se toma la foto
        var x = document.getElementById("capture");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }


    }

//
$(document).ready(function() {
    $("#capture").hide();
})
</script>

<?php $min = 'Pacientes';
$may = 'PACIENTE';
$minS = 'Paciente'; ?>
<aside class="right-side">
    <section class="content-header">
        <h1 style>
            <?php echo $min; ?>
            <small>Mantenimiento de Pacientes</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#compose-modal"><i class="fa fa-file-o"></i> AGREGAR PACIENTE</a>
            </li>
        </ol>
    </section>
    <section class="content">

        <!-- MODAL -->
        <div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" style="width:70%">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-file-o"></i> Datos generales</h4>
                    </div>
                    <form id="addPacient" method="POST">
                        <div class="modal-body">

                            <div id="results">
                                <div id="my_camera"></div>
                            </div>
                            <div class="form-group">
                                <span class="input-group-addon"><i class="fa fa-camera"></i>
                                    <input type=button id="capture" value="Tomar foto" onClick="take_snapshot()">
                                    <input name="imagen" type="button" id="open" value="Encender cámara" onClick="openCamera()">
                                    <input type="hidden" name="image" class="image-tag">
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input name="nombres" type="text" class="form-control" placeholder="Nombres *" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="45" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input name="apellidos" type="text" class="form-control" placeholder="Apellidos *" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="45" required>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-building"></i></span>
                                    <input name="direccion" type="text" class="form-control" placeholder="Dirección" maxlength="75" >
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-suitcase"></i></span>
                                    <input name="direccionTrabajo" type="text" class="form-control" placeholder="Dirección de Trabajo" maxlength="75">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-suitcase"></i></span>
                                    <input name="lugarTrabajo" type="text" class="form-control" placeholder="Lugar de Trabajo" maxlength="75">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-building"></i></span>
                                    <input name="ocupacion" type="text" class="form-control" placeholder="Ocupación" maxlength="20" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                    <input name="telefono" type="text" class="form-control" placeholder="Teléfono" oninput="this.value =
									this.value.replace(/[^0-9.,+]/g,'').replace(/(\..*)\./g, '$1');" maxlength="15" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input name="fechaNacimiento" type="text" placeholder="Fecha de nacimiento" class="form-control" onfocus="this.type='date'" required>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-table"></i></span>
                                    <input name="dpi" type="text" class="form-control" placeholder="DPI" oninput="this.value =
									this.value.replace(/[^0-9.]/g,'').replace(/(\..*)\./g, '$1');" maxlength="13">
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-intersex"></i></span>
                                    <select name="genero" class="form-control" required>
                                        <option selected disabled value="">Sexo</option>
                                        <option value="1">Masculino</option>
                                        <option value="2">Femenino</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-mars-stroke-v"></i></span>
                                    <select name="estadoCivil" class="form-control" required>
                                        <option selected disabled value="">Estado civil</option>
                                        <option value="1">Soltero(a)</option>
                                        <option value="2">Casado(a)</option>
                                        <option value="3">Viudo(a)</option>
                                        <option value="4">Divorciado(a)</option>
                                        <option value="5">Unido(a)</option>
                                        <option value="6">Otro</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-university"></i></span>
                                    <select name="escolaridad" class="form-control" required>
                                        <option selected disabled value="">Escolaridad</option>
                                        <option value="1">Ninguna</option>
                                        <option value="2">Primaria</option>
                                        <option value="3">Básico</option>
                                        <option value="4">Diversificado</option>
                                        <option value="5">Carrera técnica</option>
                                        <option value="6">Universidad</option>
                                        <option value="7">Especialización</option>
                                        <option value="8">Otro</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-list-alt"></i></span>
                                    <select name="tipoSangre" class="form-control" required>
                                        <option selected disabled value="">Tipo de sangre</option>
                                        <option value="1">No sabe</option>
                                        <option value="2">O Positivo</option>
                                        <option value="3">O Negativo</option>
                                        <option value="4">A Positivo</option>
                                        <option value="5">A Negativo</option>
                                        <option value="6">B Positivo</option>
                                        <option value="7">B Negativo</option>
                                        <option value="8">AB Positivo</option>
                                        <option value="9">AB Negativo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input name="conyugue" type="text" class="form-control" placeholder="Nombre del cónyugue" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="50">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input name="responsable" type="text" class="form-control" placeholder="Responsable" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="75">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-exclamation"></i></span>
                                    <input name="religion" type="text" class="form-control" placeholder="Religión" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="40">
                                </div>
                            </div>
                            <div class="modal-header col-md-12">
                        <h4 class="modal-title"><i class="fa fa-file-o"></i> Antecedentes familiares</h4>
                    </div>
                            
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input name="padre" type="text" class="form-control" placeholder="Padre" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="45">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input name="madre" type="text" class="form-control" placeholder="Madre" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="45">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input name="hermanos" type="text" class="form-control" placeholder="Hermanos" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="75">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="txtObservaciones">* Observaciones </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                    <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="1" ></textarea>
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
                    <div class="box-body table-responsive">
                        <script>
                            $(document).ready(function() {
                                $("#tablaPacientes").DataTable({
                                    "serverSide": true,
                                    "responsive": true,
                                    "paging": true,
                                    "processing": true,
                                    "ajax": "<?php echo BASE_DIR; ?>serverside/getData.php",
                                    "pageLength": 50,
                                    "fnCreatedRow": function(nRow,aData,iDataIndex){

                                        $('td:eq(6)', nRow).html('<a title="Editar" href="<?php echo BASE_DIR; ?>editar-pacientes/'+aData[0]+'-'+aData[1]+'"><i class="fa fa-edit"></i></a>');

                                    },
                                    dom: 'Blfrtip',
                                    order: [],
                                    buttons: [{
                                            extend: 'excel',
                                            footer: true
                                        },
                                        {
                                            extend: 'pdf',
                                            footer: true,
                                            orientation: 'landscape',
                                            pageSize: 'LETTER',
                                            title: 'Administraci&oacute;n \nListado de  <?php echo $min; ?>'
                                        },
                                        {
                                            extend: 'print',
                                            footer: true,
                                            orientation: 'landscape',
                                            pageSize: 'LETTER',
                                            title: 'Administraci&oacute;n <br/>Listado de  <?php echo $min; ?>'
                                        }
                                    ]

                                });
                            });
                        </script>
                        <table id="tablaPacientes" class="table table-bordered table-striped table-bordered table-condensed">
                            <thead>
                                <tr>
                                <th>Codigo</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Fecha Ingreso</th>
                                    <th>Acciones</th>
                                    
                            </thead>
                            <tbody>
                                
                            </tbody>
                            
                            <tfoot>
                                <tr>
                                <th>Codigo</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Fecha Ingreso</th>
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