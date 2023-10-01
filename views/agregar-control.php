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
        $("#capture").hide();
        $('#footerButtons').hide()
        $('#off').hide();

        $('#tabs').tab();
        $('#nuevaConsulta').prop('disabled', true)
        $('#dataNav').hide()

        $("#buscarPaciente").autocomplete({
            minLength: 4,
            delay: 500,
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo BASE_DIR; ?>autocomplete",
                    type: "POST",
                    dataType: "json",
                    data: {
                        search: request.term,
                        buscarPaciente: 1
                    },
                    success: function(data) {

                        if (data.length > 0) {
                            $("#empty-message").text("")
                            response(data)

                        } else {
                            $("#empty-message").text("No se encontraron resultados")

                        }


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
            $("#cargando").show();
        }).ajaxStop(function() {
            $("#cargando").fadeOut("slow");
        });

        $("#inputSeleccionarPaciente").submit(function() {
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: $("#inputSeleccionarPaciente").attr('action'),
                data: $("#inputSeleccionarPaciente").serialize(),
                beforeSend: function() {

                    $("#cargando").html('<div class="loading"></div>');
                    $('#parentHolder tbody').empty()
                },
                success: function(data) {

                    $("#btnMinimizarPaciente").trigger("click")
                    $("#idPaciente").val(data.paciente[0].idpaciente)
                    $("#nombrePaciente").val(data.paciente[0].nombres)
                    $("#cargando").hide()


                    $.each(data.consultas[0], function(key, datum) {

                        var htmlstring =
                            "<tr>" +
                            "<td>" + datum.idconsulta + "</td>" +
                            "<td>" + datum.fechaconsulta + "</td>" +
                            "<td>" + datum.motivoconsulta + "</td>" +
                            "<td><button id='tableButton' class='btn btn-success btn-sm'>Ver</button></td>"
                        "</tr>"
                        $("#parentHolder tbody").append(htmlstring)
                    })

                }
            });
            return false;
        });

        $('#parentHolder').on('click', 'table tr', function() {
            $('#dataNav').show()
            $('#footerButtons').hide()
            var idConsulta = $(this).closest('tr').find('td:first').text()

            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: "<?php echo BASE_DIR; ?>agregar-consulta",
                data: {
                    consulta: idConsulta
                },
                success: function(data) {

                    $('#nuevaConsulta').prop('disabled', false)

                    $('#motivoConsulta').val(data.consulta[0].motivo)
                    $('#historiaEnfermedad').val(data.consulta[0].historiaenfermedad)
                    $('#anteMedicos').val(data.consulta[0].age_medicos)
                    $('#anteQuirurgicos').val(data.consulta[0].age_quirurgicos)
                    $('#anteAlergicos').val(data.consulta[0].age_alergicos)
                    $('#anteTraumaticos').val(data.consulta[0].age_traumaticos)
                    $('#viciosYManias').val(data.consulta[0].age_viciosymanias)
                    $('#embarazos').val(data.consulta[0].agi_embarazos)
                    $('#menarquia').val(data.consulta[0].agi_menarquia)
                    $('#ciclo').val(data.consulta[0].agi_ciclo)
                    $('#duracion').val(data.consulta[0].agi_duracion)
                    $('#dolor').val(data.consulta[0].agi_dolor).trigger('change')
                    $('#ets').val(data.consulta[0].agi_ets).trigger('change')
                    if (data.consulta[0].agi_embarazada == 1) {
                        $('#siEmbarazada').prop('checked', true).trigger('change')
                        $('#noEmbarazada').attr('disabled', 'disabled')
                        $('#embarazo').show();
                    } else {
                        $('#noEmbarazada').prop('checked', true).trigger('change')
                        $('#siEmbarazada').attr('disabled', 'disabled')
                        $('#embarazo').hide();
                    }
                    $('#semanasEmbarazo').val(data.consulta[0].agi_semanasembarazo);
                    $('#fechaParto').val(data.consulta[0].agi_fechaparto)
                    $('#metodoAnti').val(data.consulta[0].agi_metodoanticonceptivo).trigger('change')
                    $('#fur').val(data.consulta[0].agi_fur)
                    if (data.consulta[0].agi_histerectomia == 1) {
                        $('#histerectomiasYes').prop('checked', true)
                        $('#histerectomiasNo').attr('disabled', 'disabled')
                    } else {
                        $('#histerectomiasNo').prop('checked', true)
                        $('#histerectomiasYes').attr('disabled', 'disabled')
                    }
                    $('#partos').val(data.consulta[0].aob_partos)
                    $('#cesareas').val(data.consulta[0].aob_cesareas)
                    $('#abortos').val(data.consulta[0].aob_abortos)
                    $('#hv').val(data.consulta[0].aob_hv)
                    $('#hm').val(data.consulta[0].aob_hm)
                    $('#obitoFetal').val(data.consulta[0].aob_obitofetal)
                    $('#legrados').val(data.consulta[0].aob_legrados)
                    $('#ameu').val(data.consulta[0].aob_ameu)
                    $('#ultimopapanicolaou').val(data.consulta[0].aob_ultimopapanicolaou)
                    $('#cantidadpapanicolaou').val(data.consulta[0].aob_cantidadpapanicolaou)
                    $('#parejassexuales').val(data.consulta[0].aob_parejassexuales)
                    $('#iniciovidasexual').val(data.consulta[0].aob_iniciovidasexual)
                    $('#parejassexualespareja').val(data.consulta[0].aob_parejassexualespareja)

                    $('#pa').val(data.consulta[0].pa)
                    $('#temperatura').val(data.consulta[0].temperatura)
                    $('#pulso').val(data.consulta[0].pulso)
                    $('#spo').val(data.consulta[0].spo)
                    $('#libras').val(data.consulta[0].peso_libras)
                    $('#onzas').val(data.consulta[0].peso_onzas)
                    $('#metros').val(data.consulta[0].estatura_metros)
                    $('#centimetros').val(data.consulta[0].estatura_centimetros)
                    $('#peso').val(data.consulta[0].peso_calidad).change()
                    $('#fr').val(data.consulta[0].fr)
                    $('#pielymucosas').val(data.consulta[0].pielymucosas)
                    $('#cabezaycuello').val(data.consulta[0].cabezaycuello)
                    $('#torax').val(data.consulta[0].torax)
                    $('#pulmones').val(data.consulta[0].pulmones)
                    $('#corazon').val(data.consulta[0].corazon)
                    $('#abdomen').val(data.consulta[0].abdomen)
                    $('#caderaycolumna').val(data.consulta[0].caderaycolumna)
                    $('#extremidades').val(data.consulta[0].extremidades)
                    $('#ginecologico').val(data.consulta[0].ginecologico)
                    $('#impresionclinica').val(data.consulta[0].impresionclinica)

                    $('#referidopor').val(data.consulta[0].referidopor)
                    data.consulta[0].pap_mucosaoriginaria == 1 ? $('#mucosa').prop('checked', true) : $('#mucosa').prop('checked', false)
                    data.consulta[0].pap_ectopia == 1 ? $('#ectopia').prop('checked', true) : $('#ectopia').prop('checked', false)
                    data.consulta[0].pap_zonatransformacion == 1 ? $('#zonaTransf').prop('checked', true) : $('#zonaTransf').prop('checked', false)
                    data.consulta[0].pap_zonatransformacionatipica == 1 ? $('#zonaTransfAt').prop('checked', true) : $('#zonaTransfAt').prop('checked', false)
                    data.consulta[0].pap_epitelioaceticopositivo == 1 ? $('#epitAcetPos').prop('checked', true) : $('#epitAcetPos').prop('checked', false)
                    data.consulta[0].pap_leucoplasia == 1 ? $('#leucoplasia').prop('checked', true) : $('#leucoplasia').prop('checked', false)
                    data.consulta[0].pap_puntuacion == 1 ? $('#puntuacion').prop('checked', true) : $('#puntuacion').prop('checked', false)
                    data.consulta[0].pap_mosaico == 1 ? $('#mosaico').prop('checked', true) : $('#mosaico').prop('checked', false)
                    data.consulta[0].pap_mosaicopuntuacion == 1 ? $('#mosaicoPunt').prop('checked', true) : $('#mosaicoPunt').prop('checked', false)
                    data.consulta[0].pap_atipiasvasculares == 1 ? $('#atipiasVasc').prop('checked', true) : $('#atipiasVasc').prop('checked', false)
                    data.consulta[0].pap_carcinoma == 1 ? $('#carcinoma').prop('checked', true) : $('#carcinoma').prop('checked', false)
                    data.consulta[0].pap_condiloma == 1 ? $('#condiloma').prop('checked', true) : $('#condiloma').prop('checked', false)
                    data.consulta[0].pap_cervitis == 1 ? $('#cervitis').prop('checked', true) : $('#cervitis').prop('checked', false)
                    data.consulta[0].pap_atrofias == 1 ? $('#atrofias').prop('checked', true) : $('#atrofias').prop('checked', false)
                    data.consulta[0].pap_otros == 1 ? $('#otros').prop('checked', true) : $('#otros').prop('checked', false)
                    $('#impresioncolposcopica').val(data.consulta[0].pap_impresioncolposcopica)
                    $('#unionescamocolumnar').val(data.consulta[0].pap_unionescamocolumnar)
                    $('#resHB').val(data.consulta[0].pap_reshb)
                    $('#resultHistBio').val(data.consulta[0].pap_resulthistbiopsia)
                    $('#correlacion').val(data.consulta[0].pap_correlacion)
                    $('#sugerencia').val(data.consulta[0].pap_sugerencia)
                    $('#tratamientoadoptado').val(data.consulta[0].pap_tratamientoadoptado)
                    $('#referidoa').val(data.consulta[0].pap_referidoa)
                    $('#fechareferencia').val(data.consulta[0].pap_fechareferencia)

                    $('#newRow').empty()

                    $.each(data.ultrasonidos[0], function(key, datum) {

                        var htmlString = '<div class="row">' +
                            '<div id="inputFormRow">' +
                            '<div class="form-group col-sm-4">' +
                            '<div class="input-group">' +
                            '<span class="input-group-addon">Tipo de ultrasonido </span>' +
                            '<select name="tipoUltrasonido[]" class="form-control m-input" required>' +
                            '<option value="' + datum.codigo + '">' + datum.nombre + '</option>' +
                            '</select>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group col-sm-3">' +
                            '<div class="input-group">' +
                            '<span class="input-group-addon">Precio: Q</span>' +
                            '<input value="' + datum.valor + '" type="number" name="valorUltrasonido[]" min="0" step="0.01" class="form-control" required></input>' +
                            '</div>' +
                            '</div>' +
                            '<button id="removeRow" type="button" class="btn btn-danger">X</button>' +
                            '</div>' +
                            '</div>'

                        $('#newRow').append(htmlString);

                    })
                    $('#newLab').empty()
                    $.each(data.laboratorios[0], function(key, datum) {

                        var htmlString = '<div class="row">' +
                            '<div id="inputFormRow">' +
                            '<div class="form-group col-sm-4">' +
                            '<div class="input-group">' +
                            '<span class="input-group-addon">Tipo de ultrasonido </span>' +
                            '<select name="tipoLaboratorio[]" class="form-control m-input" required>' +
                            '<option value="' + datum.codigo + '">' + datum.nombre + '</option>' +
                            '</select>' +
                            '</div>' +
                            '</div>' +
                            '<button id="removeRow" type="button" class="btn btn-danger">X</button>' +
                            '</div>' +
                            '</div>'

                        $('#newLab').append(htmlString);

                    })

                    $('#newMedicine').empty()

                    $.each(data.receta[0], function(key, datum) {

                        htmlMedicine = '<div class="row">' +
                            '<div id="inputFormMedicine">' +
                            '<div class="form-group col-sm-4">' +
                            '<div class="input-group">' +
                            '<span class="input-group-addon">Medicamento: </span>' +
                            '<input type="text"  class="form-control" value="' + datum.nombre + '"></input>' +
                            '<input type="number" name="idMedicamento[]" value="' + datum.idmedicamento + '" hidden></input>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group col-sm-2">' +
                            '<div class="input-group">' +
                            '<span class="input-group-addon">Dosis: </span>' +
                            '<input type="text" class="form-control" value="' + datum.dosificacion + '"></input>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group col-sm-5">' +
                            '<div class="input-group">' +
                            '<span class="input-group-addon">Uso: </span>' +
                            '<input type="text" name="uso[]" class="form-control" value="' + datum.uso+ '"></input>' +
                            '</div>' +
                            '</div>' +
                            '<button id="removeMedicine" type="button" class="btn btn-danger">X</button>' +
                            '</div>' +
                            '</div>'

                        $('#newMedicine').append(htmlMedicine)

                    })

                    if (data.imagenColpo != "") {
                        $('#imgColp').attr('src', 'data:image/png;base64,' + data.imagenColpo)
                    }

                }

            })

        })

        $('#nuevaConsulta').click(function() {
            $('#dataNav').show()
            var idConsulta = $("#parentHolder>table>tbody>tr>td:first").text()
            if (idConsulta.length > 0) {

                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "<?php echo BASE_DIR; ?>agregar-consulta",
                    data: {
                        ultimaConsulta: idConsulta
                    },
                    success: function(data) {

                        $(':input').not('#idPaciente').not('#nombrePaciente').not('#fecha').not('#open').not('#off').not('#capture').val('').removeAttr('checked').removeAttr('selected')
                        
                        $('#newRow').empty()
                        $('#newMedicine').empty()
                        $('#imgColp').attr('src', '')
                        $('#footerButtons').show()

                        $('#motivoConsulta').val(data[0].motivo)
                        $('#historiaEnfermedad').val(data[0].historiaenfermedad)
                        $('#anteMedicos').val(data[0].age_medicos)
                        $('#anteQuirurgicos').val(data[0].age_quirurgicos)
                        $('#anteAlergicos').val(data[0].age_alergicos)
                        $('#anteTraumaticos').val(data[0].age_traumaticos)
                        $('#viciosYManias').val(data[0].age_viciosymanias)
                        $('#embarazos').val(data[0].agi_embarazos)
                        $('#menarquia').val(data[0].agi_menarquia)
                        $('#ciclo').val(data[0].agi_ciclo)
                        $('#duracion').val(data[0].agi_duracion)
                        $('#dolor').val(data[0].agi_dolor).trigger('change')
                        $('#ets').val(data[0].agi_ets).trigger('change')
                        if (data[0].agi_embarazada == 1) {
                            $('#siEmbarazada').prop('checked', true)
                            $('#noEmbarazada').attr('disabled', 'disabled')
                            $('#embarazo').show()
                        } else {
                            $('#noEmbarazada').prop('checked', true)
                            $('#siEmbarazada').attr('disabled', 'disabled')
                        }
                        $('#semanasEmbarazo').val(data[0].agi_semanasembarazo);
                        $('#fechaParto').val(data[0].agi_fechaparto)
                        $('#metodoAnti').val(data[0].agi_metodoanticonceptivo).trigger('change')
                        $('#fur').val(data[0].agi_fur)
                        if (data[0].agi_histerectomia == 1) {
                            $('#histerectomiasYes').prop('checked', true)
                            $('#histerectomiasNo').attr('disabled', 'disabled')
                        } else {
                            $('#histerectomiasNo').prop('checked', true)
                            $('#histerectomiasYes').attr('disabled', 'disabled')
                        }

                        $('#partos').val(data[0].aob_partos)
                        $('#cesareas').val(data[0].aob_cesareas)
                        $('#abortos').val(data[0].aob_abortos)
                        $('#hv').val(data[0].aob_hv)
                        $('#hm').val(data[0].aob_hm)
                        $('#obitoFetal').val(data[0].aob_obitofetal)
                        $('#legrados').val(data[0].aob_legrados)
                        $('#ameu').val(data[0].aob_ameu)
                        $('#ultimopapanicolaou').val(data[0].aob_ultimopapanicolaou)
                        $('#cantidadpapanicolaou').val(data[0].aob_cantidadpapanicolaou)
                        $('#parejassexuales').val(data[0].aob_parejassexuales)
                        $('#iniciovidasexual').val(data[0].aob_iniciovidasexual)
                        $('#parejassexualespareja').val(data[0].aob_parejassexualespareja)

                    }

                })

            }else {
                $('#footerButtons').show()
                return false
            }

        })

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
            html += '<option value="<?php echo $tipos['codigo'] ?>" data-id=' + id + ' data-precio="<?php echo $tipos['precio'] ?>"><?php echo $tipos['nombre'] ?></option>';
            html += '<?php } ?>';
            html += '</select>';
            html += '</div>';
            html += '</div>';
            html += '<div class="form-group col-sm-3">';
            html += '<div class="input-group">';
            html += '<span class="input-group-addon">Precio: Q</span>';
            html += '<input type="number" name="valorUltrasonido[]" data-id=' + id + ' min="0" step="0.01" class="form-control" required></input>';
            html += '</div>';
            html += '</div>';
            html += '<button id="removeRow" type="button" class="btn btn-danger">X</button>';
            html += '</div>';
            html += '</div>';

            $('#newRow').append(html);
            $("#captureUlt").hide();
            $('#offUlt').hide();

            //función que sirve para saber cuanto vale el ultrasonido seleccionado
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
            html += '<?php foreach ($laboratorios as $laboratorio) { ?>';
            html += '<option value="<?php echo $laboratorio['codigo'] ?>"><?php echo $laboratorio['nombre'] ?></option>';
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

        
        //Elimina las filas de los ultrasonidos cuando se le da click al botón eliminar
        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        })

        $(document).on('click', '#removeMedicine', function() {
            $(this).closest('#inputFormMedicine').remove();
        })

    })

    //Abre la camara
    function openCamera() {
        Webcam.reset()
        $('#results').show()

        Webcam.set({
            width: 300,
            height: 250,
            //cambiar a 1280x720 con una mejor cámara
            dest_width: 300,
            dest_height: 250,
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
            x.style.display = "inline-block";

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

    function newTabImage() {
        var image = new Image();
        image.src = $('#imgColp').attr('src');

        var w = window.open("", '_blank');
        w.document.write(image.outerHTML);
        w.document.close();
    }
</script>

<?php $min = 'Controles';
$may = 'CONTROLES';
$minS = 'Paciente'; ?>
<aside class="right-side">
    <section class="content-header">
        <h1 style>
            <?php echo $min; ?>
            <small>Controles</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-sm-5">
                <div class="box box-solid box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Seleccione un paciente</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-primary btn-sm" data-widget="collapse" id="btnMinimizarPaciente"><i class="fa fa-chevron-down"></i></button>
                        </div>
                    </div>
                    <div style="display: block;" class="box-body">
                        <form role="form" id="inputSeleccionarPaciente" action="">
                            <div class="box-body">
                                <div class="input-group input-group-sm">
                                    <input class="form-control" id="buscarPaciente" name="buscarPaciente" placeholder="Nombre del paciente" type="text" required data-error-message-value-missing="Please input something">
                                    <span class="input-group-btn">
                                        <button id="seleccionarPaciente" class="btn btn-info btn-flat" type="submit"><i class="fa fa-search"></i> SELECCIONAR</button>
                                    </span>
                                </div>
                                <p id="empty-message" style="color:red"></p>
                            </div>
                            <div id="cargando"></div>
                        </form>
                        <div id="result"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="box box-solid box-primary collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title">Historial de consultas</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-primary btn-sm" data-widget="collapse" id="btnMinimizarHistorial"><i class="fa fa-chevron-down"></i></button>
                        </div>
                    </div>
                    <div style="display:block;" class="box-body">
                        <div class="table-wrapper-scroll-y my-custom-scrollbar">

                            <div id="parentHolder" class="box-body">
                                <table id="parentTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">No.</th>
                                            <th scope="col">Fecha </th>
                                            <th scope="col">Motivo</th>
                                            <th scope="col">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <button id="nuevaConsulta" class="btn btn-warning">Nueva consulta</button>
                    </div>
                </div>
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
                                        <input id="fecha" name="fecha" type="text" readonly value="<?php echo date("d-m-Y"); ?>" class="form-control" placeholder="Fecha">
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
                            <div id="dataNav" class="container">

                                <ul id="tabs" class="nav nav-pills" data-tabs="tabs">
                                    <li class="active"><a href="#antecedentes" data-toggle="tab">Antecedentes</a> </li>
                                    <li><a href="#examen" data-toggle="tab">Exámen Físico</a></li>
                                    <li><a href="#colposcopia" data-toggle="tab">Colposcopía</a></li>
                                    <li><a href="#ultrasonido" data-toggle="tab">Ultrasonido</a></li>
                                    <li><a href="#laboratorios" data-toggle="tab">Laboratorios</a></li>
                                    <li><a href="#receta" data-toggle="tab">Receta</a></li>
                                </ul>
                                <div id="my-tab-content" class="tab-content">
                                    <div class="tab-pane fade in active" id="antecedentes">
                                        <br>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Motivo:</i></span>
                                                <textarea id="motivoConsulta" name="ante_Motivo" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Historia de la enfermedad:</i></span>
                                                <textarea id="historiaEnfermedad" name="ante_Historia" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"></textarea>
                                            </div>
                                        </div>
                                        <h4>Antecedentes generales</h4>
                                        <div class="form-group col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">Médicos:</i></span>
                                                <textarea id="anteMedicos" name="ante_Medicos" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Quirúrgicos:</span>
                                                <textarea id="anteQuirurgicos" name="ante_Quirurgicos" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">Alérgicos:</i></span>
                                                <textarea id="anteAlergicos" name="ante_Alergicos" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Traumáticos:</i></span>
                                                <textarea id="anteTraumaticos" name="ante_Traumaticos" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Vicios y manías:</i></span>
                                                <textarea id="viciosYManias" name="viciosymanias" class="form-control" maxlength="150" rows="2" style="overflow:auto;resize:none"></textarea>
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
                                                                    <input id="embarazos" type="number" name="ag_Embarazos" value="0" class="form-control" maxlength="2"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Menarquía:</th>
                                                            <td>
                                                                <div>
                                                                    <input id="menarquia" type="text" name="ag_Menarquia" class="form-control" maxlength="30"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Ciclo:</th>
                                                            <td>
                                                                <div>
                                                                    <input id="ciclo" type="text" name="ag_Ciclo" class="form-control" maxlength="50"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Duración:</th>
                                                            <td>
                                                                <div>
                                                                    <input id="duracion" type="number" name="ag_Duracion" placeholder="cantidad días" class="form-control" maxlength="150"></input>
                                                                </div>

                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th>Dolor:</th>
                                                            <td>
                                                                <div>
                                                                    <select id="dolor" name="ag_Dolor" class="form-control">
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
                                                                    <select id="ets" name="ag_Ets" class="form-control">
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
                                                                    <input id="siEmbarazada" type="checkbox" name="chkEmbarazoSi"> Sí
                                                                    <input id="noEmbarazada" type="checkbox" name="chkEmbarazoNo"> No
                                                                    <div id="embarazo">
                                                                        <label>Semanas embarazo: </label>
                                                                        <input id="semanasEmbarazo" type="text" placeholder="Cantidad semanas" name="ag_SemanasEmbarazo" class="form-control" maxlength="40"></input>
                                                                        <label>Fecha probable de parto: </label>
                                                                        <input id="fechaParto" type="text" placeholder="Fecha parto" name="ag_FechaParto" class="form-control" onfocus="this.type='date'"></input>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Método Anticonceptivo:</th>
                                                            <td>
                                                                <div>
                                                                    <select id="metodoAnti" name="ag_Metodos" class="form-control">
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
                                                                    <input id="fur" name="ag_Fur" type="text" class="form-control" min="1970-01-01" onfocus="this.type='date'">
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Histerectomías</th>
                                                            <td>
                                                                <div>
                                                                    <input id="histerectomiasYes" name="chkHisteYes" type="checkbox" aria-label="Checkbox for following text input">Sí
                                                                    <input id="histerectomiasNo" name="chkHisteNo" type="checkbox" aria-label="Checkbox for following text input">No
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
                                                                    <input id="partos" type="number" value="0" name="ao_Partos" class="form-control" maxlength="2" min="0"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Cesáreas:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input id="cesareas" type="number" value="0" name="ao_Cesareas" class="form-control" maxlength="2" min="0"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Abortos:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input id="abortos" type="number" value="0" name="ao_Abortos" class="form-control" maxlength="2" min="0"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>HV:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input id="hv" type="number" value="0" name="ao_Hv" class="form-control" maxlength="2" min="0"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>HM:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input id="hm" type="number" value="0" name="ao_Hm" class="form-control" maxlength="2" min="0"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Obito Fetal:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input id="obitoFetal" type="number" value="0" name="ao_ObitoFetal" class="form-control" maxlength="2" min="0"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Legrados:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input id="legrados" type="number" value="0" name="ao_Legrados" class="form-control" maxlength="2" min="0"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>AMEU:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input id="ameu" type="number" value="0" name="ao_ameu" class="form-control" maxlength="2" min="0"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Último papanicolaou:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input id="ultimopapanicolaou" name="ao_ultimoPapanico" type="text" class="form-control" min="1970-01-01" onfocus="this.type='date'">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Cantidad papanicolaou:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input id="cantidadpapanicolaou" type="number" value="0" name="ao_cantidadPapanico" class="form-control" maxlength="2"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Parejas Sexuales:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input id="parejassexuales" type="number" value="0" name="ao_Ps" class="form-control" maxlength="3" min="0"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Inicio Vida Sexual:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input id="iniciovidasexual" type="text" placeholder="Edad" name="ao_Ivs" class="form-control" maxlength="15"></input>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <th>Parejas Sexuales de Pareja:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input id="parejassexualespareja" type="number" value="0" name="ao_Psp" class="form-control" maxlength="3" min="0"></input>
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
                                                                    <input id="pa" type="text" placeholder="0/0" name="ef_pa" class="form-control" maxlength="7"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Temperatura:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input id="temperatura" type="text" placeholder="10°" name="ef_temperatura" class="form-control" maxlength="5" min="0" step="0.01"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Pulso:</th>
                                                            <td>

                                                                <div class="input-group">
                                                                    <input id="pulso" type="text" name="ef_pulso" class="form-control" maxlength="10"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>SPO %:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input id="spo" type="text" name="ef_spo" class="form-control" maxlength="5"></input>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Peso:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <label for="ef_Libras">Libras</label>
                                                                    <input id="libras" type="number" name="ef_libras" step="0.01" class="form-control" value="0" maxlength="3"></input>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <label for="ef_Onzas">Onzas</label>
                                                                    <input id="onzas" type="number" step="0.01" name="ef_onzas" class="form-control" value="0" maxlength="3"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Estatura:</th>
                                                            <td>
                                                                <div class="input-group">
                                                                    <label for="ef_Metros">Metros:</label>
                                                                    <input id="metros" type="number" name="ef_metros" class="form-control" step="0.01" value="0" maxlength="3"></input>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <label for="ef_Centimetros">Centímetros:</label>
                                                                    <input id="centimetros" type="number" name="ef_centimetros" class="form-control" step="0.01" value="0" maxlength="3"></input>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Peso:</th>
                                                            <td>
                                                                <div>
                                                                    <select id="peso" name="ef_peso" class="form-control">
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
                                                                    <input id="fr" type="text" name="ef_fr" class="form-control" maxlength="50"></input>
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
                                                                    <textarea id="pielymucosas" name="ef_pielYMucosas" class="form-control" maxlength="150" rows="3"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>B) Cabeza y cuello:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea id="cabezaycuello" name="ef_cabezayCuello" class="form-control" maxlength="150" rows="3"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>C) Tórax:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea id="torax" name="ef_torax" class="form-control" maxlength="150" rows="3"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>D) Pulmones:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea id="pulmones" name="ef_pulmones" class="form-control" maxlength="150" rows="3"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>E) Corazón:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea id="corazon" name="ef_corazon" class="form-control" maxlength="150" rows="3"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>F) Abdomen:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea id="abdomen" name="ef_abdomen" class="form-control" maxlength="150" rows="3"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>G) Cadera y columna:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea id="caderaycolumna" name="ef_caderayColumna" class="form-control" maxlength="150" rows="3"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>H) Extremidades:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea id="extremidades" name="ef_extremidades" class="form-control" maxlength="150" rows="3"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>I) Ginecológico:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea id="ginecologico" name="ef_ginecologico" class="form-control" maxlength="150" rows="3"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Impresión clínica:</th>
                                                            <td>
                                                                <div>
                                                                    <textarea id="impresionclinica" name="ef_impresionClinica" class="form-control" maxlength="150" rows="4"></textarea>
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
                                                <input id="referidopor" type="text" id="referidoPor" name="referidoPor" class="form-control" maxlength="35"></input>
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
                                                <input class="form-check-input" type="checkbox" id="zonaTransf" name="zonaTransf">
                                                <label class="form-check-label" for="zonaTransf">
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
                                                <input class="form-check-input" type="checkbox" id="epitAcetPos" name="epitAcetPos">
                                                <label class="form-check-label" for="epitAcetPos">
                                                    5. Epitelio acético positivo
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="leucoplasia" name="leucoplasia">
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
                                                <textarea id="impresioncolposcopica" name="impresionCol" class="form-control" maxlength="150" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Unión Escamo Columnar:</i></span>
                                                <textarea id="unionescamocolumnar" name="unionEscaCol" class="form-control" maxlength="150" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">ResHB:</i></span>
                                                <textarea id="resHB" name="resHB" class="form-control" maxlength="150" rows="1"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Result. Hist. Biopsia:</i></span>
                                                <textarea id="resultHistBio" name="resulHistBio" class="form-control" maxlength="150" rows="1"></textarea>
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
                                                <textarea id="sugerencia" name="sugerencia" class="form-control" maxlength="150" rows="1"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Tratamiento adoptado:</i></span>
                                                <textarea id="tratamientoadoptado" name="tratamientoAdopt" class="form-control" maxlength="150" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-7">
                                            <div class="input-group">
                                                <span class="input-group-addon">Referido a:</span>
                                                <input id="referidoa" type="text" name="referidoA" class="form-control" maxlength="45"></input>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">Fecha Referencia:</span>
                                                <input id="fechareferencia" name="fechaReferencia" type="text" class="form-control" min="1970-01-01" onfocus="this.type='date'">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-11">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-camera"> Foto:</i>
                                                    <div id="results" class="foto-div">
                                                        <img id="imgColp" src="" class="img-colposcopia" onclick="newTabImage()" />

                                                        <div id="my_camera"></div>
                                                    </div>
                                                    <input type="button" id="capture" value="Tomar foto" onClick="take_snapshot() ">
                                                    <input type="button" id="off" value="Apagar" onClick="camera_off()">
                                                    <input name="imagen" type="button" id="open" value="Encender cámara" onClick="openCamera()">
                                                    <input type="hidden" name="imageColposcopia" class="image-tag">
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
                                    <div class="tab-pane fade" id="receta">
                                        </br>
                                        <div class="form-group col-sm-8">
                                            <label for="nombreMedicamento">Escriba el nombre o código</label>
                                            <div class="input-group">

                                                <input id="nombreMedicamento" type="text" class="form-control" placeholder="Nombre ó código del medicamento"></input>
                                                <span class="input-group-btn">
                                                    <button type="button" id="seleccionarMedicamento" class="btn btn-info btn-flat"><i class="fa fa-hand-o-down"></i> Agregar</button>
                                                </span>
                                            </div>
                                            <p id="empty-medicine" style="color:red"></p>
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
                                                <div id="newLab"></div>
                                                <button id="addLab" type="button" class="btn btn-info">Agregar fila</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="modal-footer clearfix">
                                <div id="footerButtons" class="text-center">
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