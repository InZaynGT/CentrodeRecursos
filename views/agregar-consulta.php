<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/consultas.css">
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
        $('#tabs').tab();

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
            //source: availableTags
        });

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

            $("#semanas").hide()
            if ($('input[name="chkEmbarazoSi"]').prop('checked')) {
                $('input[name="ag_SemanasEmbarazo"]').show()

            } else {
                $('input[name="ag_SemanasEmbarazo"]').hide()

            }

            //cuando se selecciona la opcion Si habilita el campo semanas y deshabilita la opcion no
            $('input[name="chkEmbarazoSi"]').on('click', function() {
                if ($(this).prop('checked')) {
                    $('input[name="ag_SemanasEmbarazo"]').show();
                    $('input[name="chkEmbarazoNo"]').attr('disabled', 'disabled');
                    $("#semanas").show()

                } else {
                    $('input[name="ag_SemanasEmbarazo"]').hide();
                    $('input[name="chkEmbarazoNo"]').removeAttr('disabled');
                    $("#semanas").hide()

                }
            });

            //Cuando se selecciona la opcion NO bloquea deshabilita la opcion SI
            $('input[name="chkEmbarazoNo"]').on('click', function() {
                if ($(this).prop('checked')) {
                    $('input[name="ag_SemanasEmbarazo"]').hide();
                    $('input[name="chkEmbarazoSi"]').attr('disabled', 'disabled');
                    $("#semanas").hide()
                } else {
                    $('input[name="chkEmbarazoSi"]').removeAttr('disabled');
                    $("#semanas").hide()


                }
            });

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

        //funcion para agregar mas ultrasonidos a la lista
        $("#addRow").click(function() {
            var html = '';
            html += '<div class="row">';
            html += '<div id="inputFormRow">';
            html += '<div class="form-group col-sm-4">';
            html += '<div class="input-group">';
            html += '<span class="input-group-addon">Tipo de ultrasonido </span>';
            html += '<select name="tipoUltrasonido[]" class="form-control m-input" required>';
            html += '<option selected disabled>Seleccionar</option>';
            html += '<?php foreach ($tiposUltrasonido as $tipos) { ?>';
            html += '<option value="<?php echo $tipos['codigo'] ?>"><?php echo $tipos['nombre'] ?></option>';
            html += '<?php } ?>';
            html += '</select>';
            html += '</div>';
            html += '</div>';
            html += '<div class="form-group col-sm-4">';
            html += '<div class="input-group">';
            html += '<span class="input-group-addon">Tomar foto: </span>';
            html += '<input type="text" name="fotoUltrasonido[]" class="form-control" maxlength="2"></input>';
            html += '</div>';
            html += '</div>';
            html += '<button id="removeRow" type="button" class="btn btn-danger">Eliminar fila</button>';
            html += '</div>';
            html += '</div>';

            $('#newRow').append(html);

        })

        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        })
    })
</script>

<?php $min = 'Consulta';
$may = 'Consultas';
$minS = 'Paciente'; ?>
<aside class="right-side">
    <section class="content-header">
        <h1 style>
            <?php echo $min; ?>
            <small>Consultas</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-solid box-primary">
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
                            </div>
                            <div id="inputPaciente"></div>
                        </form>
                        <div id="result"></div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="box">

                    <form role="form" id="frmConsulta" method="POST">
                        <div class="col-sm-12">
                            <br>
                            <div class="box box-solid box-info">
                                <div class="form-group col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input name="fecha" type="text" readonly value="<?php echo date("d-m-Y"); ?>" class="form-control" placeholder="Fecha">
                                    </div>
                                </div>

                                <div class="form-group col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input id="nombrePaciente" name="nombrePaciente" type="text" class="form-control" placeholder="Nombre del paciente" onkeydown="return false" style="pointer-events: none;" required>
                                        <input id="idPaciente" name="idPaciente" type="number" hidden>
                                    </div>
                                </div>
                            </div>
                            <div class="container">

                                <ul id="tabs" class="nav nav-pills" data-tabs="tabs">
                                    <li class="active"><a href="#antecedentes" data-toggle="tab">Antecedentes</a> </li>
                                    <li><a href="#examen" data-toggle="tab">Exámen Físico</a></li>
                                    <li><a href="#colposcopia" data-toggle="tab">Colposcopía</a></li>
                                    <li><a href="#ultrasonido" data-toggle="tab">Ultrasonido</a></li>
                                    <!--<li><a href="#diagnostico" data-toggle="tab">Diagnostico y tratamiento</a></li>-->
                                </ul>
                                <div id="my-tab-content" class="tab-content">
                                    <div class="tab-pane fade in active" id="antecedentes">
                                        <br>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Motivo:</i></span>
                                                <textarea name="ante_Motivo" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Historia de la enfermedad:</i></span>
                                                <textarea name="ante_Historia" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"></textarea>
                                            </div>
                                        </div>
                                        <h4>Antecedentes generales</h4>
                                        <div class="form-group col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">Médicos:</i></span>
                                                <textarea name="ante_Medicos" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Quirúrgicos:</span>
                                                <textarea name="ante_Quirurgicos" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">Alérgicos:</i></span>
                                                <textarea name="ante_Alergicos" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Traumáticos:</i></span>
                                                <textarea name="ante_Traumaticos" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Vicios y manías:</i></span>
                                                <textarea name="viciosymanias" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"></textarea>
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
                                                                    <input type="number" name="ag_Embarazos" value="0" class="form-control" maxlength="2"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Menarquía:</th>
                                                            <td>
                                                                <div>
                                                                    <input type="text" name="ag_Menarquia" class="form-control" maxlength="30"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Ciclo:</th>
                                                            <td>
                                                                <div>
                                                                    <input type="text" name="ag_Ciclo" class="form-control" maxlength="50"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Duración:</th>
                                                            <td>
                                                                <div>
                                                                    <input type="number" name="ag_Duracion" placeholder="cantidad días" class="form-control" maxlength="150"></input>
                                                                </div>

                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th>Dolor:</th>
                                                            <td>
                                                                <div>
                                                                    <select name="ag_Dolor" class="form-control">
                                                                        <?php foreach ($cantidadDolor as $cantidad) { ?>
                                                                            <option value="<?php echo $cantidad['codigo']; ?>"> <?php echo $cantidad['nombre']; ?></option>
                                                                        <?php } ?>
                                                                    </select>

                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>ETS:</th>
                                                            <td>
                                                                <div>
                                                                    <select name="ag_Ets" class="form-control">
                                                                        <?php foreach ($ets as $enfermedad) { ?>
                                                                            <option value="<?php echo $enfermedad['codigo']; ?>"><?php echo $enfermedad['nombre']; ?></option>

                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>¿Está embarazada?</th>
                                                            <td>
                                                                <div>
                                                                    <input type="checkbox" aria-label="Checkbox for following text input" name="chkEmbarazoSi"> Sí
                                                                    <input type="checkbox" aria-label="Checkbox for following text input" name="chkEmbarazoNo"> No
                                                                    <input type="number" placeholder="Cantidad semanas" name="ag_SemanasEmbarazo" value="0" class="form-control" maxlength="150"></input>
                                                                    <label id="semanas">Semanas </label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Método Anticonceptivo:</th>
                                                            <td>
                                                                <div>
                                                                    <select name="ag_Metodos" class="form-control">
                                                                        <?php foreach ($metodosAnti as $metodos) { ?>
                                                                            <option value="<?php echo $metodos['codigo'] ?>"><?php echo $metodos['nombre'] ?></option>

                                                                        <?php } ?>

                                                                    </select>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>F.U.R</th>
                                                            <td>
                                                                <div>
                                                                    <input name="ag_Fur" type="text" class="form-control" min="1970-01-01" onfocus="this.type='date'">
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
                                                                    <input type="number" value="0" name="ao_Partos" class="form-control" maxlength="2"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Cesáreas:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="number" value="0" name="ao_Cesareas" class="form-control" maxlength="2"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Abortos:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="number" value="0" name="ao_Abortos" class="form-control" maxlength="2"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>HV:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="number" value="0" name="ao_Hv" class="form-control" maxlength="2"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>HM:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="number" value="0" name="ao_Hm" class="form-control" maxlength="2"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Obito Fetal:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="number" value="0" name="ao_ObitoFetal" class="form-control" maxlength="2"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Último papanicolaou:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input name="ao_ultimoPapanico" type="text" class="form-control" min="1970-01-01" onfocus="this.type='date'">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Cantidad papanicolaou:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="number" value="0" name="ao_cantidadPapanico" class="form-control" maxlength="2"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Parejas Sexuales:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="number" value="0" name="ao_Ps" class="form-control" maxlength="3"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Inicio Vida Sexual:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="text" placeholder="Edad" name="ao_Ivs" class="form-control" maxlength="15"></input>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <th>Parejas Sexuales de Pareja:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="number" value="0" name="ao_Psp" class="form-control" maxlength="3"></input>
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
                                                                    <input type="text" placeholder="0/0" name="ef_pa" class="form-control" maxlength="5"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Temperatura:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="text" placeholder="10°" name="ef_temperatura" class="form-control" maxlength="2"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Pulso:</th>
                                                            <td>

                                                                <div class="input-group">
                                                                    <input type="text" name="ef_Pulso" class="form-control" maxlength="5"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>SPO %:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="text" name="ef_Spo" class="form-control" maxlength="5"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Peso:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <label for="ef_Libras">Libras</label>
                                                                    <input type="number" name="ef_Libras" step="0.01" class="form-control" maxlength="5"></input>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <label for="ef_Onzas">Onzas</label>
                                                                    <input type="number" step="0.01" name="ef_Onzas" class="form-control" maxlength="5"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Estatura:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <label for="ef_Metros">Metros:</label>
                                                                    <input type="number" name="ef_Metros" class="form-control" step="0.01"></input>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <label for="ef_Centimetros">Centímetros:</label>
                                                                    <input type="number" name="ef_Centimetros" class="form-control" step="0.01"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Peso:</th>
                                                            <td>
                                                                <div>
                                                                    <select name="ef_Peso" class="form-control">
                                                                        <?php foreach ($tiposPeso as $peso) { ?>
                                                                            <option value="<?php echo $peso['codigo'] ?>"><?php echo $peso['nombre'] ?> </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>F.R:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="text" name="ef_Fr" class="form-control" maxlength="50"></input>
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
                                                                    <textarea name="ef_PielYMucosas" class="form-control" maxlength="150" rows="3"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>B) Cabeza y cuello:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea name="ef_CabezayCuello" class="form-control" maxlength="150" rows="3"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>C) Tórax:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea name="ef_Torax" class="form-control" maxlength="150" rows="3"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>D) Abdomen:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea name="ef_Abdomen" class="form-control" maxlength="150" rows="3"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>E)Cadera y columna:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea name="ef_CaderayColumna" class="form-control" maxlength="150" rows="3"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>F) Ginecológico:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea name="ef_Ginecologico" class="form-control" maxlength="150" rows="3"></textarea>

                                                                </div>

                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <th>Impresión clínica:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea name="ef_ImpresionClinica" class="form-control" maxlength="150" rows="4"></textarea>
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
                                                <input type="text" id="txtObservaciones" name="observaciones" class="form-control" maxlength="5"></input>
                                            </div>
                                        </div>
                                        <h4>DxPapanicolaou</h4>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">

                                                <label class="form-check-label" for="mucosa">
                                                    <input class="form-check-input" type="checkbox" id="mucosa" name="mucosa">
                                                    1. Mucosa originaria
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="ectopia" name="ectopia">
                                                <label class="form-check-label" for="ectopia">
                                                    2. Ectopía
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="zonaTrasf" name="zonaTrasf">
                                                <label class="form-check-label" for="zonaTrasf">
                                                    3. Zona de transformación
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="zonaTransfAt" name="zonaTransfAt">
                                                <label class="form-check-label" for="zonaTransfAt">
                                                    4. Zona de transformación atípica
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="epitAcetPos" name="epitAcetPos" >
                                                <label class="form-check-label" for="epitAcetPos">
                                                    5. Epitelio acético positivo
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="leucoplasia" value="leucoplasia">
                                                <label class="form-check-label" for="leucoplasia">
                                                    6. Leucoplasia
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="puntuacion" name="puntuacion">
                                                <label class="form-check-label" for="puntuacion">
                                                    7. Puntuación
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="mosaico" name="mosaico">
                                                <label class="form-check-label" for="mosaico">
                                                    8. Mosaico
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="mosaicoPunt" name="mosaicoPunt">
                                                <label class="form-check-label" for="mosaicoPunt">
                                                    9. Mosaico - Puntuación
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="atipiasVasc" name="atipiasVasc">
                                                <label class="form-check-label" for="atipiasVasc">
                                                    10. Atipias Vasculares
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="carcinoma" name="carcinoma">
                                                <label class="form-check-label" for="carcinoma">
                                                    11. Carcinoma
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="condiloma" name="condiloma">
                                                <label class="form-check-label" for="condiloma">
                                                    12. Condiloma
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="cervitis" name="cervitis">
                                                <label class="form-check-label" for="cervitis">
                                                    13. Cervicitis
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="atrofias" name="atrofias">
                                                <label class="form-check-label" for="atrofias">
                                                    14. Atrofias
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="otros" name="otros">
                                                <label class="form-check-label" for="otros">
                                                    15. Otros
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Impresión colposcopica:</i></span>
                                                <textarea  name="impresionCol" class="form-control" maxlength="150" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Unión Escamo Columnar:</i></span>
                                                <textarea  name="unionEscaCol" class="form-control" maxlength="150" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">ResHB:</i></span>
                                                <textarea name="resHB" class="form-control" maxlength="150" rows="1"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Result. Hist. Biopsia:</i></span>
                                                <textarea name="resulHistBio" class="form-control" maxlength="150" rows="1"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Correlación:</i></span>
                                                <textarea id="correlacion" name="correlacion" class="form-control" maxlength="150" rows="1"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Sugerencia:</i></span>
                                                <textarea name="sugerencia" class="form-control" maxlength="150" rows="1"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Tratamiento adoptado:</i></span>
                                                <textarea name="tratamientoAdopt" class="form-control" maxlength="150" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-7">
                                            <div class="input-group">
                                                <span class="input-group-addon">Referido a:</span>
                                                <input type="text" name="referidoA" class="form-control" maxlength="5"></input>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">Fecha Referencia:</span>
                                                <input name="fechaReferencia" type="text" class="form-control" min="1970-01-01" onfocus="this.type='date'">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-7">
                                            <div class="input-group">
                                                <span class="input-group-addon">Tomar foto:</span>
                                                <input type="text" id="txtObservaciones" name="observaciones" class="form-control" maxlength="5"></input>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="ultrasonido">
                                        </br>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div id="newRow"></div>
                                            <button id="addRow" type="button" class="btn btn-info">Agregar fila</button>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <!--<div class="tab-pane fade" id="diagnostico">
                                        <br>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Plan de tratamiento:</span>
                                                <textarea name="planTratamiento" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Plan Educacional:</span>
                                                <textarea name="plaEducacional" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Referido a:</span>
                                                <input type="text" name="referidoa" class="form-control"></input>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon">Valor: Q</span>
                                                <input type="number" name="valor" min="1" placeholder="0.00" step="any" class="form-control"></input>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <div class="input-group">
                                                <span class="input-group-addon">Próxima cita:</span>
                                                <input name="proximaCita" type="text" class="form-control" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" onfocus="this.type='date'">
                                            </div>
                                        </div>
                                    </div>-->
                                </div>

                            </div>
                            <div class="modal-footer clearfix">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> GUARDAR</button>
                                    <button type="button" class="btn btn-danger" onclick="history.back()"><i class="fa fa-times"></i> Cerrar</button>
                                </div>
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</aside>