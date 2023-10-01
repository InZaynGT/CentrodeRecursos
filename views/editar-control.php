<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/consultas.css">
<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>
<script src="<?php echo BASE_DIR; ?>js/webcam.js"></script>
<style>
    .select-editable {
        position: relative;
        background-color: white;
        border: solid grey 1px;
        width: 120px;
        height: 18px;
    }

    .select-editable select {
        position: absolute;
        top: 0px;
        left: 0px;
        font-size: 14px;
        border: none;
        width: 120px;
        margin: 0;
    }

    .select-editable input {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 100px;
        padding: 1px;
        font-size: 12px;
        border: none;
    }

    .select-editable select:focus,
    .select-editable input:focus {
        outline: none;
    }
</style>

<script>
    $(document).ready(function() {
        const url = window.location.href
        const idCons = url.match(/[0-9]+/).toString()
        

        $.ajax({
            url: "<?php echo BASE_DIR; ?>editar-consulta",
            type: "POST",
            dataType: "JSON",
            data: {
                cargarMedicamentos: 1,
                idConsulta : idCons
            },
            success: function(data) {

                $.each(data, function(key, datum) {

                    htmlMedicine = '<div class="row">' +
                            '<div id="inputFormMedicine">' +
                            '<div class="form-group col-sm-4">' +
                            '<div class="input-group">' +
                            '<span class="input-group-addon">Medicamento: </span>' +
                            '<input readonly type="text"  class="form-control" value="' + datum.nombre + '"></input>' +
                            '<input type="number" name="idMedicamento[]" value="' + datum.idmedicamento + '" hidden></input>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group col-sm-2">' +
                            '<div class="input-group">' +
                            '<span class="input-group-addon">Dosis: </span>' +
                            '<input readonly type="text" name="dosificacion[]"  class="form-control" value="' + datum.dosificacion + '"></input>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group col-sm-5">' +
                            '<div class="input-group">' +
                            '<span class="input-group-addon">Uso: </span>' +
                            '<input readonly type="text" name="uso[]" class="form-control" value="' + datum.uso+ '"></input>' +
                            '</div>' +
                            '</div>' +
                            // '<button id="removeMedicine" type="button" class="btn btn-danger">X</button>' +
                            '</div>' +
                            '</div>'

                        $('#newMedicine').append(htmlMedicine)

                })

                
            }
        })

        $("#capture").hide();
        $('#off').hide();

        $('#tabs').tab();

        //Minimiza la busqueda de pacientes al editar
        $("#btnMinimizar").trigger('click');

        //funcion ajax que sirve para llenar la busqueda de paciente, la url se manda para el controlador
        $("#buscarPaciente").autocomplete({
            minLength: 4,
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo BASE_DIR; ?>autocomplete",
                    type: "POST",
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function(data) {

                        response(data)
                    }
                })
            }
        });

        $("#nombreMedicamento").autocomplete({
            minLength: 3,
            delay: 500,
            position: {
                my: "right top",
                at: "right bottom"
            },
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo BASE_DIR; ?>autocomplete",
                    type: "POST",
                    dataType: "json",
                    data: {
                        search: request.term,
                        buscarMedicamento: 1
                    },
                    success: function(data) {

                        if (data.length > 0) {
                            $("#empty-medicine").text("").fadeIn(200)
                            response(data)

                        } else {
                            $("#empty-medicine").text("No se encontraron resultados").delay(5000).fadeOut(800)
                        }
                    }
                })
            }
        })

        $('#seleccionarMedicamento').click(function() {
            $("#empty-medicine").fadeIn().text("")
            var codigo = $('#nombreMedicamento').val().split(" ", 1).toString().match(/\d+/g)
            var existe = 0
            if (codigo == null) {
                $("#empty-medicine").text("Escriba el nombre un medicamento para agregar").delay(5000).fadeOut(800)
                return false

            } else {

                $.ajax({
                    url: "<?php echo BASE_DIR; ?>autocomplete",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        agregarMedicamento: codigo[0]
                    },
                    success: function(data) {

                        if (data) {
                            $('input[name^="idMedicamento"]').each(function() {
                                if ($(this).val() == data.idmedicamento) {
                                    existe = 1
                                }

                            })

                            if (existe == 1) {
                                $("#empty-medicine").text("Este medicamento ya fue agregado").delay(5000).fadeOut(800)

                            } else {
                                $("#nombreMedicamento").val("")
                                htmlMedicine = '<div class="row">' +
                                    '<div id="inputFormMedicine">' +
                                    '<div class="form-group col-sm-4">' +
                                    '<div class="input-group">' +
                                    '<span class="input-group-addon">Medicamento: </span>' +
                                    '<input type="text"  class="form-control" value="' + data.nombre + '"></input>' +
                                    '<input type="number" name="idMedicamento[]" value="' + data.idmedicamento + '" hidden></input>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="form-group col-sm-2">' +
                                    '<div class="input-group">' +
                                    '<span class="input-group-addon">Dosis: </span>' +
                                    '<input type="text" name="dosificacion[]" class="form-control" value="' + data.dosificacion + '"></input>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="form-group col-sm-5">' +
                                    '<div class="input-group">' +
                                    '<span class="input-group-addon">Uso: </span>' +
                                    '<input type="text" name="uso[]" class="form-control" value="' + data.uso+ '"></input>' +
                                    '</div>' +
                                    '</div>' +
                                    '<button id="removeMedicine" type="button" class="btn btn-danger">X</button>' +
                                    '</div>' +
                                    '</div>'

                                $('#newMedicine').append(htmlMedicine)

                            }

                        } else {
                            $("#empty-medicine").text("No se encontraron resultados").delay(5000).fadeOut(800)
                        }
                    }
                })

            }


        })

        $().ajaxStart(function() {
            $("#inputPaciente").hide();
        }).ajaxStop(function() {
            $("#inputPaciente").fadeIn("slow");
        });
        $("#inputSeleccionarPaciente").submit(function() {
            $.ajax({
                type: "POST",
                url: $("#inputSeleccionarPaciente").attr('action'),
                data: $("#inputSeleccionarPaciente").serialize(),
                beforeSend: function() {
                    $("#inputPaciente").html('<div class="loading"></div>');
                },
                success: function(data) {
                    $("#inputPaciente").html(data);

                }
            });
            return false;
        });

        //si marca que está embarazada muestra el input
        $(function() {

            $("#embarazo").hide()
            if ($('input[name="chkEmbarazoSi"]').prop('checked')) {
                $('#embarazo').show()

            } else {
                $('#embarazo').hide()

            }

            //cuando se selecciona la opcion Si habilita el campo semanas y deshabilita la opcion no
            $('input[name="chkEmbarazoSi"]').on('click', function() {
                if ($(this).prop('checked')) {
                    $('#embarazo').show();
                    $('input[name="chkEmbarazoNo"]').attr('disabled', 'disabled');
                    

                } else {
                    $('#embarazo').hide();
                    $('input[name="chkEmbarazoNo"]').removeAttr('disabled');
                    
                }
            });

            //Cuando se selecciona la opcion NO bloquea deshabilita la opcion SI
            $('input[name="chkEmbarazoNo"]').on('click', function() {
                if ($(this).prop('checked')) {
                    $('#embarazo').hide();
                    $('input[name="chkEmbarazoSi"]').attr('disabled', 'disabled');
                    
                } else {
                    $('input[name="chkEmbarazoSi"]').removeAttr('disabled');
                }
            });

            $('input[name="chkHisteYes"]').on('click', function() {
                if ($(this).prop('checked')) {
                    $('input[name="chkHisteNo"]').attr('disabled', 'disabled')
                } else {
                    $('input[name="chkHisteNo"]').removeAttr('disabled');

                }
            })

            $('input[name="chkHisteNo"]').on('click', function() {
                if ($(this).prop('checked')) {
                    $('input[name="chkHisteYes"]').attr('disabled', 'disabled')
                } else {
                    $('input[name="chkHisteYes"]').removeAttr('disabled');

                }
            })

        })

        //envia los datos desde el formulario para el controlador via post
        $("frmConsulta").submit(function() {
            $.ajax({
                type: "POST",
                url: $("#frmConsulta").attr('action'),
                data: $("#frmConsulta").serialize(),
                beforeSend: function() {
                    $("#result").html('<div class="loading"></div>');
                },
                success: function(data) {
                    $("#result").html(data);
                }
            })
            return false

        })
        var id = 0
        //funcion para agregar mas ultrasonidos a la lista
        $("#addRow").click(function() {
            id += 1 
            var html = '';
            html += '<div class="row">';
            html += '<div id="inputFormRow">';
            html += '<div class="form-group col-sm-4">';
            html += '<div class="input-group">';
            html += '<span class="input-group-addon">Tipo de ultrasonido </span>';
            html += '<select name="tipoUltrasonido[]" class="form-control m-input" required>';
            html += '<option selected disabled>Seleccionar</option>';
            html += '<?php foreach ($tiposUltrasonido as $tipos) { ?>';
            html += '<option disabled readonly value="<?php echo $tipos['codigo'] ?>" data-id=' + id + ' data-precio="<?php echo $tipos['precio']?>"><?php echo $tipos['nombre'] ?></option>';
            html += '<?php } ?>';
            html += '</select>';
            html += '</div>';
            html += '</div>';
            html += '<div class="form-group col-sm-3">';
            html += '<div class="input-group">';
            html += '<span class="input-group-addon">Valor Q: </span>';
            html += '<input disabled readonly type="number" name="valorUltrasonido[]" min="0" data-id='+ id +' step="0.01" min="0" class="form-control" required></input>';
            html += '</div>';
            html += '</div>';
            html += '<button id="removeRow" type="button" class="btn btn-danger">Eliminar fila</button>';
            html += '</div>';
            html += '</div>';

            $('#newRow').append(html);

            $('select[name="tipoUltrasonido[]"]').change(function() {
                var selectedId = $(this).find(':selected').data('id')
                var selectedPrice = $(this).find(':selected').data('precio')

                $('input[data-id=' + id + ']').val(selectedPrice)
            })

        })

        $("#addLab").click(function() {
            var html = '';
            html += '<div class="row">';
            html += '<div id="inputFormRow">';
            html += '<div class="form-group col-sm-4">';
            html += '<div class="input-group">';
            html += '<span class="input-group-addon">Exámen: </span>';
            html += '<select name="tipoLaboratorio[]" class="form-control m-input" required>';
            html += '<option selected disabled>Seleccionar</option>';
            html += '<?php foreach ($tiposLaboratorio as $tiposLab) { ?>';
            html += '<option value="<?php echo $tiposLab['codigo'] ?>"><?php echo $tiposLab['nombre'] ?></option>';
            html += '<?php } ?>';
            html += '</select>';
            html += '</div>';
            html += '</div>';
            html += '<button id="removeRow" type="button" class="btn btn-danger">X</button>';
            html += '</div>';
            html += '</div>';

            $('#newLab').append(html);
            $("#captureUlt").hide();
            $('#offUlt').hide();
        })

        
        
        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        })

        $(document).on('click', '#removeMedicine', function() {
            $(this).closest('#inputFormMedicine').remove();
        })

    })

    //Abre la camara
    function open_camera() {
        Webcam.reset()
        $('#results').show()

        Webcam.set({
            width: 300,
            height: 250,
            dest_width: 1280,
            dest_height: 720,
            image_format: 'jpeg',
            jpeg_quality: 100
        });

        Webcam.attach('#my_camera')
        var x = document.getElementById("capture");
        if (x.style.display === "none") {
            x.style.display = "inline-block"
            $("#off").show()
            $("#open").hide()
            $('#delete').hide()
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
            x.style.display = "inline-block";

        } else {
            x.style.display = "none";

        }

    }

    //Apaga la cámara y oculta los botones
    function camera_off() {
        Webcam.reset()
        $('#capture').hide()
        $('#off').hide()
        $('#open').show()


    }

    //Eliminar la foto para agregar una nueva
    function delete_photo() {
        $('#imgColp').remove();
        open_camera()

    }

    //abrir la imagen en una nueva ventana
    function newTabImage() {
        var image = new Image();
        image.src = $('#imgColp').attr('src');

        var w = window.open("", '_blank');
        w.document.write(image.outerHTML);
        w.document.close();
    }
</script>

<?php $min = 'Control prenatal';
$may = 'Controles Prenatales';
$minS = 'Paciente'; ?>
<aside class="right-side">
    <section class="content-header">
        <h1 style>
            <?php echo $min; ?>
            <small>Control Prenatal</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo BASE_DIR; ?>consultas" class="btn btn-primary btn-sm"><i class="fa fa-list"></i> LISTADO DE CONTROLES PRENATALES</a>
            </li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <!-- <div class="box box-solid box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Seleccione un paciente</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-primary btn-sm" data-widget="collapse" id="btnMinimizar"><i class="fa fa-chevron-down"></i></button>
                        </div>
                    </div>
                    <div style="display: block;" class="box-body">
                        <form role="form" id="inputSeleccionarPaciente" action="">
                            <div class="box-body">
                                <div class="input-group input-group-sm">
                                    <input class="form-control" id="buscarPaciente" name="buscarPaciente" placeholder="Nombre del paciente" type="text" required data-errormessage-value-missing="Please input something">
                                    <span class="input-group-btn">
                                        <button class="btn btn-info btn-flat" type="submit"><i class="fa fa-search"></i> SELECCIONAR</button>
                                    </span>
                                </div>
                                <p id="empty-message" style="color:red"></p>
                            </div>
                            <div id="inputPaciente"></div>
                        </form>
                        <div id="result"></div>
                    </div>
                </div> -->
            </div>

            <div class="col-xs-12">
                <div class="box">

                    <form role="form" id="frmConsulta" method="POST">
                        <div class="box box-solid">
                            <br>
                            <div class="box box-solid box-info">
                                <div class="form-group col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input name="fecha" type="text" readonly value="<?php echo $consulta['fechaconsulta']; ?>" class="form-control" placeholder="Fecha">
                                    </div>
                                </div>

                                <div class="form-group col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input id="nombrePaciente" name="nombrePaciente" type="text" class="form-control" placeholder="Nombre del paciente" value="<?php echo $paciente['nombres'] ?>" onkeydown="return false" style="pointer-events: none;" required>
                                        <input id="idPaciente" name="idPaciente" type="number" value="<?php echo $paciente['idpaciente'] ?>" hidden>
                                    </div>
                                </div>
                            </div>
                            <div class="container">

                                <ul id="tabs" class="nav nav-pills" data-tabs="tabs">
                                    <li class="active"><a href="#antecedentes" data-toggle="tab">Antecedentes</a> </li>
                                    <li><a href="#examen" data-toggle="tab">Exámen Físico</a></li>
                                    <li><a href="#colposcopia" data-toggle="tab">Colposcopía</a></li>
                                    <li><a href="#ultrasonido" data-toggle="tab">Ultrasonidos</a></li>
                                    <li><a href="#laboratorios" data-toggle="tab">Laboratorios</a></li>
                                    <li><a href="#receta" data-toggle="tab">Receta</a></li>
                                </ul>
                                <div id="my-tab-content" class="tab-content">
                                    <div class="tab-pane fade in active" id="antecedentes">
                                        <br>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Motivo:</i></span>
                                                <textarea readonly name="ante_Motivo" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"><?php echo $consulta['motivo'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Historia de la enfermedad:</i></span>
                                                <textarea readonly name="ante_Historia" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"><?php echo $consulta['historiaenfermedad'] ?></textarea>
                                            </div>
                                        </div>
                                        <h4>Antecedentes generales</h4>
                                        <div class="form-group col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">Médicos:</i></span>
                                                <textarea readonly name="ante_Medicos" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"><?php echo $consulta['age_medicos'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Quirúrgicos:</span>
                                                <textarea readonly name="ante_Quirurgicos" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"><?php echo $consulta['age_quirurgicos'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">Alérgicos:</i></span>
                                                <textarea readonly name="ante_Alergicos" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"><?php echo $consulta['age_alergicos'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Traumáticos:</i></span>
                                                <textarea readonly name="ante_Traumaticos" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"><?php echo $consulta['age_traumaticos'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Vicios y manías:</i></span>
                                                <textarea readonly name="viciosymanias" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"><?php echo $consulta['age_viciosymanias'] ?></textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-5">
                                            <h4>Antecedentes ginecológicos</h4>
                                            <div class="box box-primary padd">
                                                <table class="table table-condensed">
                                                    <tbody>
                                                        <tr>
                                                            <th>Embarazos: </th>
                                                            <td>
                                                                <div>
                                                                    <input readonly disabled type="number" name="ag_Embarazos" value="<?php echo $consulta['agi_embarazos'] ?>" class="form-control" maxlength="2"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Menarquía:</th>
                                                            <td>
                                                                <div>
                                                                    <input readonly disabled type="text" name="ag_Menarquia" class="form-control" maxlength="30" value="<?php echo $consulta['agi_menarquia'] ?>"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Ciclo:</th>
                                                            <td>
                                                                <div>
                                                                    <input readonly disabled type="text" name="ag_Ciclo" class="form-control" maxlength="50" value="<?php echo $consulta['agi_ciclo'] ?>"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Duración:</th>
                                                            <td>
                                                                <div>
                                                                    <input readonly disabled type="number" name="ag_Duracion" placeholder="cantidad días" class="form-control" maxlength="150" value="<?php echo $consulta['agi_duracion'] ?>"></input>
                                                                </div>

                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th>Dolor:</th>
                                                            <td>
                                                                <div>
                                                                    <select readonly disabled name="ag_Dolor" class="form-control">
                                                                        <?php foreach ($dolor as $dolor) { ?>
                                                                            <option value="<?php echo $dolor['codigo']; ?>" <?php if ($dolor['codigo'] == $consulta['agi_dolor']) echo 'selected' ?>><?php echo $dolor['nombre'] ?></option>
                                                                        <?php } ?>

                                                                    </select>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>ETS:</th>
                                                            <td>
                                                                <div>
                                                                    <select readonly disabled name="ag_Ets" class="form-control">
                                                                        <?php foreach ($ets as $enfermedad) { ?>
                                                                            <option value="<?php echo $enfermedad['codigo']; ?>" <?php if ($enfermedad['codigo'] == $consulta['agi_ets']) echo 'selected' ?>><?php echo $enfermedad['nombre']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>¿Está embarazada?</th>
                                                            <td>
                                                                <div>
                                                                    <?php if ($consulta['agi_embarazada'] == 1) { ?>
                                                                        <input readonly disabled type="checkbox" aria-label="Checkbox for following text input" name="chkEmbarazoSi" <?php echo 'checked="checked"'; ?>> Sí
                                                                        <input readonly disabled type="checkbox" aria-label="Checkbox for following text input" name="chkEmbarazoNo"> No
                                                                        <div id="embarazo">
                                                                        <label>Semanas embarazo: </label>
                                                                        <input readonly disabled type="text" placeholder="Cantidad semanas" name="ag_SemanasEmbarazo" value="<?php echo $consulta['agi_semanasembarazo']; ?>" class="form-control" maxlength="40"></input>
                                                                        <label>Fecha probable de parto: </label>
                                                                        <input readonly disabled id="fechaParto" type="text" min="2022-01-01" placeholder="Cantidad semanas" name="ag_FechaParto" class="form-control"  onfocus="this.type='date'" value="<?php echo $consulta['agi_fechaparto'] ?>">
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <input readonly disabled type="checkbox" aria-label="Checkbox for following text input" name="chkEmbarazoSi"> Sí
                                                                        <input readonly disabled type="checkbox" aria-label="Checkbox for following text input" name="chkEmbarazoNo" <?php echo 'checked="checked"'; ?>> No
                                                                        <div id="embarazo">
                                                                        <label>Semanas embarazo: </label>
                                                                        <input readonly disabled type="text" placeholder="Cantidad semanas" name="ag_SemanasEmbarazo" value="<?php echo $consulta['agi_semanasembarazo']; ?>" class="form-control" maxlength="40"></input>
                                                                        <label>Fecha probable de parto: </label>
                                                                        <input readonly disabled id="fechaParto" type="text" min="2022-01-01" placeholder="Cantidad semanas" name="ag_FechaParto" class="form-control"  onfocus="this.type='date'" value="<?php echo $consulta['agi_fechaparto'] ?>">
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Método Anticonceptivo:</th>
                                                            <td>
                                                                <div>
                                                                    <select readonly disabled name="ag_Metodos" class="form-control">
                                                                        <?php foreach ($metodosAnticonceptivos as $metodos) { ?>
                                                                            <option value="<?php echo $metodos['codigo'] ?>" <?php if ($consulta['agi_metodoanticonceptivo'] == $metodos['codigo']) echo 'selected' ?>><?php echo $metodos['nombre'] ?></option>
                                                                        <?php } ?>

                                                                    </select>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>F.U.R</th>
                                                            <td>
                                                                <div>
                                                                    <input readonly disabled name="ag_Fur" type="text" class="form-control" min="1970-01-01" onfocus="this.type='date'" value="<?php echo $consulta['agi_fur'] ?>">
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Histerectomías</th>
                                                            <td>
                                                                <div>
                                                                    <?php if($consulta['agi_histerectomias'] == 1) { ?>
                                                                        <input readonly disabled id="histerectomiasYes" name="chkHisteYes" type="checkbox" aria-label="Checkbox for following text input" <?php echo 'checked="checked"'; ?>>Sí
                                                                        <input readonly disabled id="histerectomiasNo" name="chkHisteNo" type="checkbox" aria-label="Checkbox for following text input">No

                                                                    <?php }else { ?>
                                                                        <input readonly disabled id="histerectomiasYes" name="chkHisteYes" type="checkbox" aria-label="Checkbox for following text input" >Sí
                                                                        <input readonly disabled id="histerectomiasNo" name="chkHisteNo" type="checkbox" aria-label="Checkbox for following text input" <?php echo 'checked="checked"'; ?>>No
                                                                   <?php } ?>
                                                                    
                                                                </div>

                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-sm-5">
                                            <h4> Antecedentes obstetricos </h4>
                                            <div class="box box-primary padd">
                                                <table class="table table-condensed">
                                                    <tbody>
                                                        <tr>
                                                            <th>Partos:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled type="number" name="ao_Partos" class="form-control" maxlength="2" value="<?php echo $consulta['aob_partos'] ?>"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Cesáreas:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled type="number" name="ao_Cesareas" class="form-control" maxlength="2" value="<?php echo $consulta['aob_cesareas'] ?>"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Abortos:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled type="number" name="ao_Abortos" class="form-control" maxlength="2" value="<?php echo $consulta['aob_abortos'] ?>"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>HV:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled type="number" name="ao_Hv" class="form-control" maxlength="2" value="<?php echo $consulta['aob_hv'] ?>"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>HM:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled type="number" name="ao_Hm" class="form-control" maxlength="2" value="<?php echo $consulta['aob_hm'] ?>"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Obito Fetal:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled type="number" name="ao_ObitoFetal" class="form-control" maxlength="2" value="<?php echo $consulta['aob_obitofetal'] ?>"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Legrados:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled id="legrados" type="number" value="0" name="ao_Legrados" class="form-control" maxlength="2" min="0"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>AMEU:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled id="ameu" type="number" value="0" name="ao_ameu" class="form-control" maxlength="2" min="0"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Último papanicolaou:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled name="ao_ultimoPapanico" type="text" class="form-control" min="1970-01-01" onfocus="this.type='date'" value="<?php echo $consulta['aob_ultimopapanicolaou'] ?>">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Cantidad papanicolaou:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled type="number" name="ao_cantidadPapanico" class="form-control" maxlength="2" value="<?php echo $consulta['aob_cantidadpapanicolaou'] ?>"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Parejas Sexuales:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled type="number" name="ao_Ps" class="form-control" maxlength="3" value="<?php echo $consulta['aob_parejassexuales'] ?>"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Inicio Vida Sexual:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled type="text" placeholder="Edad" name="ao_Ivs" class="form-control" maxlength="15" value="<?php echo $consulta['aob_iniciovidasexual'] ?>"></input>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <th>Parejas Sexuales de Pareja:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled type="number" name="ao_Psp" class="form-control" maxlength="3" value="<?php echo $consulta['aob_parejassexualespareja'] ?>"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="examen">
                                        <div class="col-sm-5">
                                            <br>
                                            <div class="box box-primary padd">
                                                <table class="table table-condensed">
                                                    <tbody>
                                                        <tr>
                                                            <th>P/A:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled type="text" placeholder="0/0" name="ef_pa" class="form-control" maxlength="7" value="<?php echo $consulta['pa'] ?>"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Temperatura:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled type="text" placeholder="10°" name="ef_temperatura" class="form-control" maxlength="10" min="0" step="0.01" value="<?php echo $consulta['temperatura'] ?>"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Pulso:</th>
                                                            <td>

                                                                <div class="input-group">
                                                                    <input readonly disabled type="text" name="ef_pulso" class="form-control" maxlength="10" value="<?php echo $consulta['pulso'] ?>"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>SPO %:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled type="text" name="ef_spo" class="form-control" maxlength="5" value="<?php echo $consulta['spo'] ?>"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Peso:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <label for="ef_Libras">Libras</label>
                                                                    <input readonly disabled type="number" name="ef_libras" step="0.01" class="form-control" maxlength="3" value="<?php echo $consulta['peso_libras'] ?>"></input>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <label for="ef_Onzas">Onzas</label>
                                                                    <input readonly disabled type="number" step="0.01" name="ef_onzas" class="form-control" maxlength="3" value="<?php echo $consulta['peso_onzas'] ?>"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Estatura:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <label for="ef_Metros">Metros:</label>
                                                                    <input readonly disabled type="number" name="ef_metros" class="form-control" step="0.01" maxlength="3" value="<?php echo $consulta['estatura_metros'] ?>"></input>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <label for="ef_Centimetros">Centímetros:</label>
                                                                    <input readonly disabled type="number" name="ef_centimetros" class="form-control" step="0.01" maxlength="3" value="<?php echo $consulta['estatura_centimetros'] ?>"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Peso:</th>
                                                            <td>
                                                                <div>
                                                                    <select readonly disabled name="ef_peso" class="form-control">
                                                                        <?php foreach ($tiposPeso as $peso) { ?>
                                                                            <option value="<?php echo $peso['codigo'] ?>" <?php if ($consulta['peso_calidad'] == $peso['codigo']) echo 'selected' ?>><?php echo $peso['nombre'] ?> </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>F.R:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input readonly disabled type="text" name="ef_fr" class="form-control" maxlength="50" value="<?php echo $consulta['fr'] ?>"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <br>
                                            <div class="box box-primary padd">
                                                <table class="table table-condensed">
                                                    <tbody>
                                                        <tr>
                                                            <th>A) Piel y mucosas:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea readonly disabled name="ef_pielYMucosas" class="form-control" maxlength="150" rows="3" style="overflow:auto;resize:none"><?php echo $consulta['pielymucosas'] ?></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>B) Cabeza y cuello:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea readonly disabled name="ef_cabezayCuello" class="form-control" maxlength="150" rows="3" style="overflow:auto;resize:none"><?php echo $consulta['cabezaycuello'] ?></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>C) Tórax:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea readonly disabled name="ef_torax" class="form-control" maxlength="150" rows="3" style="overflow:auto;resize:none"><?php echo $consulta['torax'] ?></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>D) Pulmones:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea readonly disabled id="pulmones" name="ef_pulmones" class="form-control" maxlength="150" rows="3" style="overflow:auto;resize:none"><?php echo $consulta['pulmones'] ?></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>E) Corazón:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea readonly disabled id="corazon" name="ef_corazon" class="form-control" maxlength="150" rows="3" style="overflow:auto;resize:none"><?php echo $consulta['corazon'] ?></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>F) Abdomen:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea readonly disabled name="ef_abdomen" class="form-control" maxlength="150" rows="3" style="overflow:auto;resize:none"><?php echo $consulta['abdomen'] ?></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>G) Cadera y columna:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea readonly disabled name="ef_caderayColumna" class="form-control" maxlength="150" rows="3" style="overflow:auto;resize:none"><?php echo $consulta['caderaycolumna'] ?></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>H) Extremidades:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea readonly disabled id="extremidades" name="ef_extremidades" class="form-control" style="overflow:auto;resize:none" maxlength="150" rows="3"><?php echo $consulta['extremidades'] ?></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>I) Ginecológico:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea readonly disabled  name="ef_ginecologico" class="form-control" maxlength="150" rows="3" style="overflow:auto;resize:none"><?php echo $consulta['ginecologico'] ?></textarea>

                                                                </div>

                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <th>Impresión clínica:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea readonly disabled name="ef_impresionClinica" class="form-control" maxlength="150" rows="4" style="overflow:auto;resize:none"><?php echo $consulta['impresionclinica'] ?></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="colposcopia">
                                        <br>
                                        <div class="form-group col-sm-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Referido por:</i></span>
                                                <input type="text" id="referidoPor" name="referidoPor" class="form-control" maxlength="35" value="<?php echo $consulta['referidopor'] ?>"></input>
                                            </div>
                                        </div>
                                        <h4>DxPapanicolaou</h4>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">

                                                <label class="form-check-label" for="mucosa">
                                                    <input readonly disabled class="form-check-input" type="checkbox" id="mucosa" name="mucosa" <?php echo $consulta['pap_mucosaoriginaria'] == 1 ? 'checked="checked"' : '' ?>>
                                                    1. Mucosa originaria
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input readonly disabled class="form-check-input" type="checkbox" id="ectopia" name="ectopia" <?php echo $consulta['pap_ectopia'] == 1 ? 'checked="checked"' : '' ?>>
                                                <label class="form-check-label" for="ectopia">
                                                    2. Ectopía
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input readonly disabled class="form-check-input" type="checkbox" id="zonaTransf" name="zonaTransf" <?php echo $consulta['pap_zonatransformacion'] == 1 ? 'checked="checked"' : '' ?>>
                                                <label class="form-check-label" for="zonaTransf">
                                                    3. Zona de transformación
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input readonly disabled class="form-check-input" type="checkbox" id="zonaTransfAt" name="zonaTransfAt" <?php echo $consulta['pap_zonatransformacionatipica'] == 1 ? 'checked="checked"' : '' ?>>
                                                <label class="form-check-label" for="zonaTransfAt">
                                                    4. Zona de transformación atípica
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input readonly disabled class="form-check-input" type="checkbox" id="epitAcetPos" name="epitAcetPos" <?php echo $consulta['pap_epitelioaceticopositivo'] == 1 ? 'checked="checked"' : '' ?>>
                                                <label class="form-check-label" for="epitAcetPos">
                                                    5. Epitelio acético positivo
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input readonly disabled class="form-check-input" type="checkbox" id="leucoplasia" name="leucoplasia" <?php echo $consulta['pap_leucoplasia'] == 1 ? 'checked="checked"' : '' ?>>
                                                <label class="form-check-label" for="leucoplasia">
                                                    6. Leucoplasia
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input readonly disabled class="form-check-input" type="checkbox" id="puntuacion" name="puntuacion" <?php echo $consulta['pap_puntuacion'] == 1 ? 'checked="checked"' : '' ?>>
                                                <label class="form-check-label" for="puntuacion">
                                                    7. Puntuación
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input readonly disabled class="form-check-input" type="checkbox" id="mosaico" name="mosaico" <?php echo $consulta['pap_mosaico'] == 1 ? 'checked="checked"' : '' ?>>
                                                <label class="form-check-label" for="mosaico">
                                                    8. Mosaico
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input readonly disabled class="form-check-input" type="checkbox" id="mosaicoPunt" name="mosaicoPunt" <?php echo $consulta['pap_mosaicopuntuacion'] == 1 ? 'checked="checked"' : '' ?>>
                                                <label class="form-check-label" for="mosaicoPunt">
                                                    9. Mosaico - Puntuación
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input readonly disabled class="form-check-input" type="checkbox" id="atipiasVasc" name="atipiasVasc" <?php echo $consulta['pap_atipiasvasculares'] == 1 ? 'checked="checked"' : '' ?>>
                                                <label class="form-check-label" for="atipiasVasc">
                                                    10. Atipias Vasculares
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input readonly disabled class="form-check-input" type="checkbox" id="carcinoma" name="carcinoma" <?php echo $consulta['pap_carcinoma'] == 1 ? 'checked="checked"' : '' ?>>
                                                <label class="form-check-label" for="carcinoma">
                                                    11. Carcinoma
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input readonly disabled class="form-check-input" type="checkbox" id="condiloma" name="condiloma" <?php echo $consulta['pap_condiloma'] == 1 ? 'checked="checked"' : '' ?>>
                                                <label class="form-check-label" for="condiloma">
                                                    12. Condilomaloca
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input readonly disabled class="form-check-input" type="checkbox" id="cervitis" name="cervitis" <?php echo $consulta['pap_cervitis'] == 1 ? 'checked="checked"' : '' ?>>
                                                <label class="form-check-label" for="cervitis">
                                                    13. Cervicitis
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input readonly disabled class="form-check-input" type="checkbox" id="atrofias" name="atrofias" <?php echo $consulta['pap_atrofias'] == 1 ? 'checked="checked"' : '' ?>>
                                                <label class="form-check-label" for="atrofias">
                                                    14. Atrofias
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input readonly disabled class="form-check-input" type="checkbox" id="otros" name="otros" <?php echo $consulta['pap_otros'] == 1 ? 'checked="checked"' : '' ?>>
                                                <label class="form-check-label" for="otros">
                                                    15. Otros
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Impresión colposcopica:</i></span>
                                                <textarea readonly disabled name="impresionCol" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none">  <?php echo $consulta['pap_impresioncolposcopica'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Unión Escamo Columnar:</i></span>
                                                <textarea readonly disabled name="unionEscaCol" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"><?php echo $consulta['pap_unionescamocolumnar'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">ResHB:</i></span>
                                                <textarea readonly disabled name="resHB" class="form-control" maxlength="150" rows="1" style="overflow:auto;resize:none"><?php echo $consulta['pap_reshb'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Result. Hist. Biopsia:</i></span>
                                                <textarea readonly disabled name="resulHistBio" class="form-control" maxlength="150" rows="1" style="overflow:auto;resize:none"><?php echo $consulta['pap_resulthistbiopsia'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Correlación:</i></span>
                                                <textarea readonly disabled id="correlacion" name="correlacion" class="form-control" maxlength="150" rows="1" style="overflow:auto;resize:none"><?php echo $consulta['pap_correlacion'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Sugerencia:</i></span>
                                                <textarea readonly disabled name="sugerencia" class="form-control" maxlength="150" rows="1" style="overflow:auto;resize:none"><?php echo $consulta['pap_sugerencia'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Tratamiento adoptado:</i></span>
                                                <textarea readonly disabled name="tratamientoAdopt" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"><?php echo $consulta['pap_tratamientoadoptado'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-7">
                                            <div class="input-group">
                                                <span class="input-group-addon">Referido a:</span>
                                                <input readonly disabled type="text" name="referidoA" class="form-control" maxlength="45" style="overflow:auto;resize:none" value="<?php echo $consulta['pap_referidoa'] ?>"></input>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">Fecha Referencia:</span>
                                                <input readonly disabled name="fechaReferencia" type="text" class="form-control" min="1970-01-01" onfocus="this.type='date'" value="<?php echo $consulta['pap_fechareferencia'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-11">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-camera"> Foto:</i>
                                                    <div class="foto-div">

                                                        <?php
                                                        //obtiene la imagen del servidor con el nombre guardado en la base de datos,
                                                        //si no existe coloca una generica
                                                        $imagesDir = "/xampp/uploads/colposcopia/";
                                                        $content = @file_get_contents($imagesDir . $consulta['pap_img'] . '.png');
                                                        $content = base64_encode($content);

                                                        base64_decode($content);

                                                        ?>
                                                        <?php if ($content != '') { ?>

                                                            <img id="imgColp" class="img-colposcopia" src="data:image/png;base64, <?php echo $content ?> " onclick="newTabImage()" />

                                                        <?php } else { ?>
                                                            <img class="img-colposcopia" src="data:image/png;base64, <?php echo $content ?>" style="width:300; height:200;" />

                                                        <?php } ?>

                                                        <div class="foto-div" id="results">
                                                            <div id="my_camera"></div>
                                                        </div>
                                                        <input type="button" id="capture" value="Tomar foto" onClick="take_snapshot() ">
                                                        <input type="button" id="off" value="Apagar" onClick="camera_off()">
                                                        <!-- <input name="imagen" type="button" id="open" value="Encender cámara" onClick="open_camera()"> -->
                                                        <input type="hidden" name="imageColposcopia" class="image-tag">
                                                        <!-- <input type="button" id="delete" name="eliminarFoto" value="Cambiar foto" onClick="delete_photo()"> -->
                                                    </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="ultrasonido">
                                        </br>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <?php foreach ($ultrasonidosConsulta as $ultrasonidos) { ?>
                                                    <div class="row">
                                                        <div id="inputFormRow">
                                                            <div class="form-group col-sm-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">Tipo de ultrasonido </span>
                                                                    <select name="tipoUltrasonido[]" class="form-control m-input" required>

                                                                        <option selected disabled>Seleccionar</option>
                                                                        <?php foreach ($tiposUltrasonido as $tipos) { ?>
                                                                            <option value="<?php echo $tipos['codigo'] ?>" <?php if ($tipos['codigo'] == $ultrasonidos['codigo']) echo 'selected' ?>><?php echo $tipos['nombre'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-sm-3">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">Valor: Q</span>
                                                                    <input type="number" name="valorUltrasonido[]" min="0" step="0.01" value="<?php echo $ultrasonidos['valor'] ?>" class="form-control" required></input>
                                                                </div>
                                                            </div>
                                                            <button id="removeRow" type="button" class="btn btn-danger">Eliminar fila</button>
                                                        </div>
                                                    </div>


                                                <?php } ?>
                                                <div id="newRow"></div>
                                                <!-- <button id="addRow" type="button" class="btn btn-info">Agregar fila</button> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="receta">
                                        </br>
                                        <div class="form-group col-sm-8">
                                            <!-- <label for="nombreMedicamento">Escriba el nombre o código</label>
                                            <div class="input-group">

                                                <input id="nombreMedicamento" type="text" class="form-control" placeholder="Nombre ó código del medicamento"></input>
                                                <span class="input-group-btn">
                                                    <button type="button" id="seleccionarMedicamento" class="btn btn-info btn-flat"><i class="fa fa-hand-o-down"></i> Agregar</button>
                                                </span>
                                            </div>
                                            <p id="empty-medicine" style="color:red"></p> -->
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div id="newMedicine"></div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="laboratorios" >
                                    </br>
                                        <div class="row">
                                        <div class="col-lg-12">
                                                <?php foreach ($laboratorios as $laboratorio) { ?>
                                                    <div class="row">
                                                        <div id="inputFormRow">
                                                            <div class="form-group col-sm-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">Exámen </span>
                                                                    <select name="tipoLaboratorio[]" class="form-control m-input" required>

                                                                        <option selected disabled>Seleccionar</option>
                                                                        <?php foreach ($tiposLaboratorio as $tiposL) { ?>
                                                                            <option value="<?php echo $tiposL['codigo'] ?>" <?php if ($tiposL['codigo'] == $laboratorio['codigo']) echo 'selected' ?>><?php echo $tiposL['nombre'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <button id="removeRow" type="button" class="btn btn-danger">X</button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div id="newLab"></div>
                                                <!-- <button id="addLab" type="button" class="btn btn-info">Agregar fila</button> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer clearfix">
                                <!-- <div class="text-center">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> GUARDAR</button>
                                    <button type="button" class="btn btn-danger" onclick="history.back()"><i class="fa fa-times"></i> Cerrar</button>
                                </div> -->
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</aside>