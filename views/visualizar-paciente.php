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
    $(document).ready(function () {
        $("#capture").hide();
        $('#footerButtons').show()
        $('#off').hide();

        $('#tabs').tab();
        $('#nuevaConsulta').prop('disabled', true)
        $('#dataNav').show()

        $("#buscarPaciente").autocomplete({
            minLength: 4,
            delay: 500,
            source: function (request, response) {
                $.ajax({
                    url: "<?php echo BASE_DIR; ?>autocomplete",
                    type: "POST",
                    dataType: "json",
                    data: {
                        search: request.term,
                        buscarPaciente: 1
                    },
                    success: function (data) {

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
            source: function (request, response) {
                $.ajax({
                    url: "<?php echo BASE_DIR; ?>autocomplete",
                    type: "POST",
                    dataType: "json",
                    data: {
                        search: request.term,
                        buscarMedicamento: 1
                    },
                    success: function (data) {

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

        $('#seleccionarMedicamento').click(function () {
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
                    success: function (data) {

                        if (data) {
                            $('input[name^="idMedicamento"]').each(function () {
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
                                    '<input type="text" name="uso[]" class="form-control" value="' + data.uso + '"></input>' +
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

        $().ajaxStart(function () {
            $("#cargando").show();
        }).ajaxStop(function () {
            $("#cargando").fadeOut("slow");
        });

        $("#inputSeleccionarPaciente").submit(function () {
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: $("#inputSeleccionarPaciente").attr('action'),
                data: $("#inputSeleccionarPaciente").serialize(),
                beforeSend: function () {

                    $("#cargando").html('<div class="loading"></div>');
                    $('#parentHolder tbody').empty()
                },
                success: function (data) {

                    $("#btnMinimizarPaciente").trigger("click")
                    $("#idPaciente").val(data.paciente[0].idpaciente)
                    $("#nombrePaciente").val(data.paciente[0].nombres)
                    $("#cargando").hide()


                    $.each(data.consultas[0], function (key, datum) {

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

        $('#parentHolder').on('click', 'table tr', function () {
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
                success: function (data) {

                    $('#nuevaConsulta').prop('disabled', false)

                    //CAMBIOS
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

                    $.each(data.ultrasonidos[0], function (key, datum) {

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
                    $.each(data.laboratorios[0], function (key, datum) {

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

                    $.each(data.receta[0], function (key, datum) {

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
                            '<input type="text" name="uso[]" class="form-control" value="' + datum.uso + '"></input>' +
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

        $('#nuevaConsulta').click(function () {

            $('#dataNav').show()
            var idConsulta = $("#parentHolder>table>tbody>tr>td:first").text()
            //CAMBIO
            if (idConsulta.length > 0) {

                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "<?php echo BASE_DIR; ?>agregar-consulta",
                    data: {
                        ultimaConsulta: idConsulta
                    },
                    success: function (data) {

                        $(':input').not('#idPaciente').not('#nombrePaciente').not('#fecha').not('#open').not('#off').not('#capture').val('').removeAttr('checked').removeAttr('selected')

                        $('#newRow').empty()
                        $('#newMedicine').empty()
                        $('#imgColp').attr('src', '')
                        $('#footerButtons').show()

                        //CAMBIO
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

                        //CAMBIOS
                        $('#seleccionarPaciente2').hide()
                    }

                })

                //Aquí esta el 1er error
            } else {

                $('#footerButtons').show()
                $('#seleccionarPaciente2').hide()
            }
        })



        //si marca que está embarazada muestra el input
        $(function () {

            $("#embarazo").hide()
            if ($('input[name="chkEmbarazoSi"]').prop('checked')) {
                $('#embarazo').show()

            } else {
                $('#embarazo').hide()

            }

            //cuando se selecciona la opcion Si habilita el campo semanas y deshabilita la opcion no
            $('input[name="chkEmbarazoSi"]').on('click', function () {
                if ($(this).prop('checked')) {
                    $('#embarazo').show();
                    $('input[name="chkEmbarazoNo"]').attr('disabled', 'disabled');


                } else {
                    $('#embarazo').hide();
                    $('input[name="chkEmbarazoNo"]').removeAttr('disabled');

                }
            });

            //Cuando se selecciona la opcion NO bloquea deshabilita la opcion SI
            $('input[name="chkEmbarazoNo"]').on('click', function () {
                if ($(this).prop('checked')) {
                    $('#embarazo').hide();
                    $('input[name="chkEmbarazoSi"]').attr('disabled', 'disabled');

                } else {
                    $('input[name="chkEmbarazoSi"]').removeAttr('disabled');
                }
            });

            $('input[name="chkHisteYes"]').on('click', function () {
                if ($(this).prop('checked')) {
                    $('input[name="chkHisteNo"]').attr('disabled', 'disabled')
                } else {
                    $('input[name="chkHisteNo"]').removeAttr('disabled');

                }
            })

            $('input[name="chkHisteNo"]').on('click', function () {
                if ($(this).prop('checked')) {
                    $('input[name="chkHisteYes"]').attr('disabled', 'disabled')
                } else {
                    $('input[name="chkHisteYes"]').removeAttr('disabled');

                }
            })

        })

        //envia los datos desde el formulario para el controlador via post
        $("frmConsulta").submit(function () {
            $.ajax({
                type: "POST",
                url: $("#frmConsulta").attr('action'),
                data: $("#frmConsulta").serialize(),
                beforeSend: function () {
                    $("#result").html('<div class="loading"></div>');
                },
                success: function (data) {
                    $("#result").html(data);
                }
            })
            return false

        })

        var id = 0
        //funcion para agregar mas ultrasonidos a la lista
        $("#addRow").click(function () {
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
            $('select[name="tipoUltrasonido[]"]').change(function () {
                var selectedId = $(this).find(':selected').data('id')
                var selectedPrice = $(this).find(':selected').data('precio')

                $('input[data-id=' + id + ']').val(selectedPrice)
            })
        })

        $("#addLab").click(function () {
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
        $(document).on('click', '#removeRow', function () {
            $(this).closest('#inputFormRow').remove();
        })

        $(document).on('click', '#removeMedicine', function () {
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

    function handleFileSelect(event) {
        var files = event.target.files; // Obtener los archivos seleccionados
        var file = files[0]; // Tomar el primer archivo seleccionado (asumiendo que solo se permite seleccionar una imagen)

        if (file) {
            var reader = new FileReader(); // Crear un lector de archivos

            reader.onload = function (e) {
                var imageSrc = e.target.result; // Obtener la URL de la imagen cargada
                // Aquí puedes hacer lo que desees con la imagen, como mostrarla en una vista previa o enviarla al servidor para su procesamiento
                // Por ejemplo, puedes mostrar la imagen en un elemento <img> con el id "preview"
                document.getElementById('preview').src = imageSrc;
            };

            reader.readAsDataURL(file); // Leer el contenido del archivo como una URL de datos
        }
    }

    //toma la foto y se asigna a la etiqueta img
    function take_snapshot() {
        Webcam.snap(function (data_uri) {
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

<?php $min = 'Consulta';
$may = 'Consultas';
$minS = 'Paciente'; ?>
<aside class="right-side">
    <section class="content-header">
        <h1 style>
            HISTORIA CLÍNICA
            <small>FISIOTERAPIA EXTERNA</small>
        </h1>
    </section>
    <section class="container pb-5">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <form role="form" id="frmConsulta" method="POST">
                        <div class="box box-solid">
                            <br>
                            <div id="dataNav" class="container">
                                <ul id="tabs" class="nav nav-pills" data-tabs="tabs">
                                    <li class="active"><a href="#infoPersonal" data-toggle="tab">Información
                                            Personal</a> </li>
                                    <li><a href="#antecedentes" data-toggle="tab">Antecedentes</a></li>
                                    <li><a href="#evaluaciones_fisioterapeuticas" data-toggle="tab">Evaluaciones
                                            Fisioterapeuticas</a></li>
                                    <li><a href="#tono_muscular" data-toggle="tab">Tono Muscular</a></li>
                                    <li><a href="#escala_desarrollo" data-toggle="tab">Escala de Desarrollo</a></li>
                                    <li><a href="#atencion" data-toggle="tab">Atencion</a></li>
                                    <li><a href="#destrezas_manuales" data-toggle="tab">Destrezas Manuales</a></li>
                                    <li><a href="#actividades_diaria" data-toggle="tab">Actividades de la Vida
                                            Diaria</a></li>
                                    <li><a href="#postura" data-toggle="tab">Postura</a></li>
                                    <li><a href="#marcha_desplaza" data-toggle="tab">Marcha Y/O Desplazamiento</a></li>
                                </ul>
                                <div id="my-tab-content" class="tab-content">
                                    <div class="tab-pane fade in active" id="infoPersonal">
                                        <br>
                                        <div class="form-group col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Nombre del usuario:</i></span>
                                                <input id="nom_paciente" type="text" name="nom_paciente"
                                                    class="form-control" maxlength="100" required
                                                    value="<?php echo $consulta[0]['nombre']; ?>" disabled></input>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Fecha de Nacimiento:</i></span>
                                                <input id="fechadeNacimiento" name="fechadeNacimiento" type="text"
                                                    class="form-control" min="1970-01-01" onfocus="this.type='date'"
                                                    value="<?php echo $consulta[0]['fecha']; ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon">Edad:</i></span>
                                                <input id="edad" name="edad" type="number"
                                                    class="form-control""
                                                    value="<?php echo $consulta[0]['edad']; ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Sexo:</span>
                                                    <select name="sexo" id="sexo" class="form-control" disabled required>
                                                        <option disabled value="">Sexo</option>
                                                        <option value="1" <?php echo ($consulta[0]['sexo'] == 1) ? 'selected' : ''; ?>>Masculino</option>
                                                        <option value="2" <?php echo ($consulta[0]['sexo'] == 2) ? 'selected' : ''; ?>>Femenino</option>
                                                    </select>
                                                </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-mars-stroke-v"></i></span>
                                                <select name="estadoCivil" disabled id="estadoCivil" class="form-control" required>
                                                    <option disabled value="">Estado civil</option>
                                                    <option value="1" <?php echo ($consulta[0]['estado_civil'] == 1) ? 'selected' : ''; ?>>Soltero(a)</option>
                                                    <option value="2" <?php echo ($consulta[0]['estado_civil'] == 2) ? 'selected' : ''; ?>>Casado(a)</option>
                                                    <option value="3" <?php echo ($consulta[0]['estado_civil'] == 3) ? 'selected' : ''; ?>>Viudo(a)</option>
                                                    <option value="4" <?php echo ($consulta[0]['estado_civil'] == 4) ? 'selected' : ''; ?>>Divorciado(a)</option>
                                                    <option value="5" <?php echo ($consulta[0]['estado_civil'] == 5) ? 'selected' : ''; ?>>Unido(a)</option>
                                                    <option value="6" <?php echo ($consulta[0]['estado_civil'] == 6) ? 'selected' : ''; ?>>Otro</option>
                                                </select>
                                            </div>
                                        </div>
                                            <div class="form-group col-md-8">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Dirección:</span>
                                                    <textarea id="direccion" name="direccion"
                                                        class="form-control" maxlength="150" rows="2"
                                                        style="overflow:auto;resize:none" disabled>
                                                        <?php echo $consulta[0]['direccion']; ?>
                                                    </textarea>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-11">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Nombre del encargado:</i></span>
                                                    <input id="nom_encargado" type="text" name="nom_encargado"
                                                        class="form-control" maxlength="30" required
                                                        value="<?php echo $consulta[0]['nombre_encargado']; ?>" disabled></input>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-11">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Número de teléfono:</i></span>
                                                    <input id="telefono_encargado" type="text" name="telefono_encargado"
                                                        class="form-control" maxlength="30" required
                                                        value="<?php echo $consulta[0]['telefono']; ?>" disabled></input>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-11">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Diagnóstico:</i></span>
                                                    <input id="diagnostico" type="text" name="diagnostico"
                                                        class="form-control" maxlength="30" required
                                                        value="<?php echo $consulta[0]['diagnostico']; ?>" disabled></input>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-11">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Medicamentos Administrados
                                                        Actualmente:</span>
                                                    <textarea id="medicamentos_Admin" name="medicamentos_Admin"
                                                        class="form-control" maxlength="150" rows="2"
                                                        style="overflow:auto;resize:none"
                                                        value="<?php echo $consulta[0]['med_admin']; ?>" disabled></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <h4>CUADRO CLÍNICO</h4>
                                                <div class="box box-primary padd">
                                                    <table class="table table-condensed">
                                                        <tbody>
                                                            <tr>
                                                                <th>Médico Tratante: </th>
                                                                <td>
                                                                    <div>
                                                                        <input id="medico_Tratante" type="text"
                                                                            name="medico_Tratante" class="form-control"
                                                                            maxlength="150"
                                                                            value="<?php echo $consulta[0]['medico']; ?>" disabled></input>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Teléfono de Médico:</th>
                                                                <td>
                                                                    <div>
                                                                        <input id="telefono_medico" type="text"
                                                                            name="telefono_medico" class="form-control"
                                                                            maxlength="30"
                                                                            value="<?php echo $consulta[0]['telefono_med']; ?>" disabled></input>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Exámenes de gabinete realizados:</th>
                                                                <td>
                                                                    <div>
                                                                        <input id="ciclo" type="text" name="ciclo"
                                                                            class="form-control"
                                                                            maxlength="200"
                                                                            value="<?php echo $consulta[0]['examenes_realizados']; ?>" disabled></input>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Convulsiona</th>
                                                                <td>
                                                                    <div>
                                                                        <input disabled class="form-check-input" type="checkbox" id="convulsiona" name="convulsiona" value="1" <?php echo ($consulta[0]['convulsiona'] == 1) ? 'checked' : ''; ?>>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Usa algún tipo de prótesis?</th>
                                                                <td>
                                                                    <div>
                                                                        <input disabled class="form-check-input" type="checkbox" id="usa_protesis" name="usa_protesis" <?php echo ($consulta[0]['usa_protesis'] == 1) ? 'checked' : ''; ?>>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th>Cuál prótesis?:</th>
                                                                <td>
                                                                    <div>
                                                                        <input id="cual_protesis" type="text"
                                                                            name="cual_protesis" class="form-control"
                                                                            maxlength="200"
                                                                            value="<?php echo $consulta[0]['desc_protesis']; ?>" disabled></input>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Historia Enfermedad Actual</th>
                                                                <td>
                                                                    <div>
                                                                        <input id="historia_enfermedad" type="text"
                                                                            name="historia_enfermedad" class="form-control"
                                                                            maxlength="200"
                                                                            value="<?php echo $consulta[0]['enfermedad_actual']; ?>" disabled></input>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Observaciones</th>
                                                                <td>
                                                                    <div>
                                                                        <input id="observaciones" type="text"
                                                                            name="observaciones" class="form-control"
                                                                            maxlength="200"
                                                                            value="<?php echo $consulta[0]['observaciones']; ?>" disabled></input>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="antecedentes">
                                            <div class="form-group col-md-11">
                                                <h4>Personales Patológicos</h4>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Cardiovascular:</i></span>
                                                    <input id="cardiovascular" type=" text" name="cardiovascular"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['cardiovascular']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Pulmonares:</i></span>
                                                    <input id="pulmonares" type="text" name="pulmonares"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['pulmonares']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Digestivos:</i></span>
                                                    <input id="digestivos" type="text" name="digestivos"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['digestivos']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Diabetes:</i></span>
                                                    <input id="diabetes" type="text" name="diabetes"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['diabetes']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Renales:</i></span>
                                                    <input id="renales" type="text" name="renales"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['renales']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Quirurgicos:</i></span>
                                                    <input id="quirurgicos" type="text" name="quirurgicos"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['quirurgicos']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Alergicos:</i></span>
                                                    <input id="alergicos" type="text" name="alergicos"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['alergicos']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Transfuciones:</i></span>
                                                    <input id="transfuciones" type="text" name="transfuciones"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['transfusiones']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Medicamentos:</i></span>
                                                    <input id="medicamentos" type="text" name="medicamentos"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['medicamentos']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Otros:</i></span>
                                                    <input id="otros_antecedentes" type="text" name="otros_antecedentes"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['otros']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <h4>Personales No Patológicos</h4>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Alcohol:</i></span>
                                                    <input id="alcohol" type="text" name="alcohol"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['alcohol']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Tabaquismo:</i></span>
                                                    <input id="tabaquismo" type="text" name="tabaquismo"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['tabaquismo']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Drogas:</i></span>
                                                    <input id="drogas" type="text" name="drogas"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['drogas']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Inmunizaciones:</i></span>
                                                    <input id="inmunizaciones" type="text" name="inmunizaciones"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['inmunizaciones']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Otros:</i></span>
                                                    <input id="otros_inmuniz" type="text" name="otros_inmuniz"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['otros_2']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <h5>Padre:</h5>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Vivo:</i></span>
                                                    <input class="form-check-input" type="checkbox" id="padre_vivo" name="padre_vivo" value="1"
                                                    <?php if ($consulta[0]['padre_vive'] == 1) echo 'checked'; ?> disabled>
                                                </div>

                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Enfermedades que padece:</i></span>
                                                    <input id="enfermedad_padre" type="text" name="enfermedad_padre"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['padre_enferm']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <h5>Madre:</h5>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Vivo:</i></span>
                                                    <input class="form-check-input" type="checkbox" id="madre_vivo" name="madre_vivo" value="1"
                                                    <?php if ($consulta[0]['madre_vive'] == 1) echo 'checked'; ?> disabled>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Enfermedades que padece:</i></span>
                                                    <input id="enfermedad_madre" type="text" name="enfermedad_madre"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['madre_enferm']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <h5>Hermanos:</h5>
                                                <div class="input-group">
                                                    <span class="input-group-addon">¿Cuántos hermanos tiene?:</i></span>
                                                    <input id="num_hermanos" type="number" name="num_hermanos"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['hermanos']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Hermanos vivos:</i></span>
                                                    <input id="herman_vive" type="number" name="herman_vive"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['cant_hermanos']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Enfermedades que padecen:</i></span>
                                                    <input id="enfermedad_herm" type="text" name="enfermedad_herm"
                                                        class="form-control" maxlength="30"
                                                        value="<?php echo $consulta[0]['enf_hermanos']; ?>" disabled></input>
                                                </div>
                                                <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Observaciones</i></span>
                                                    <input id="observaciones" type="text" name="observaciones"
                                                        class="form-control" maxlength="100"
                                                        value="<?php echo $consulta[0]['observaciones_ant']; ?>" disabled></input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="evaluaciones_fisioterapeuticas">
                                            <div class="form-group col-md-11">
                                                <h4>EVALUACIONES FISIOTERAPEUTICAS</h4>
                                                <span class="input-group-addon">¿Dónde se localiza el dolor?:</span>
                                                <textarea id="localiza_dolor" name="localiza_dolor"
                                                    class="form-control" maxlength="150" rows="2" text=""
                                                    style="overflow:auto;resize:none" disabled><?php echo $consulta[0]['dolor_donde']; ?></textarea>
                                                <span class="input-group-addon">Irradiación del dolor:</span>
                                                <textarea id="irradia_dolor" name="irradia_dolor"
                                                    class="form-control" maxlength="150" rows="2" text=""
                                                    style="overflow:auto;resize:none" disabled><?php echo $consulta[0]['dolor_irradiacion']; ?></textarea>
                                                <span class="input-group-addon">Tipo de dolor:</span>
                                                <textarea id="tipo_dolor" name="tipo_dolor" class="form-control"
                                                    maxlength="150" rows="2" text=""
                                                    style="overflow:auto;resize:none" disabled><?php echo $consulta[0]['tipo_dolor']; ?></textarea>
                                                <span class="input-group-addon">Escala de dolor:</span>
                                                <br>
                                                <div style="position: relative;">
                                                <input type="range" min="0" max="10" disabled value="<?php echo $consulta[0]['escala_visual_dolor']; ?>" step="1" style="width: 100%; overflow: auto; resize: none" name="escaladolor" >
                                                    <div style="display: flex; justify-content: space-between;">
                                                        <span>0</span>
                                                        <span>1</span>
                                                        <span>2</span>
                                                        <span>3</span>
                                                        <span>4</span>
                                                        <span>5</span>
                                                        <span>6</span>
                                                        <span>7</span>
                                                        <span>8</span>
                                                        <span>9</span>
                                                        <span>10</span>
                                                    </div>
                                                </div>

                                                <style>
                                                    div[style^="display: flex"] span {
                                                        text-align: center;
                                                    }
                                                </style>

                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tono_muscular">
                                            </br>
                                            <div class="container mt-5">
                                                <table class="table" method="POST">
                                                    <tr>
                                                        <td><label class="form-check-label"><input disabled type="radio" class="form-check-input" name="tono_muscular" value="1" <?php if ($consulta[0]['tipo_tono'] == 1) echo 'checked'; ?>>Espástico</label></td>
                                                        <td><label class="form-check-label"><input disabled type="radio" class="form-check-input" name="tono_muscular" value="2" <?php if ($consulta[0]['tipo_tono'] == 2) echo 'checked'; ?>>Hipotónico</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td><label class="form-check-label"><input disabled type="radio" class="form-check-input" name="tono_muscular" value="3" <?php if ($consulta[0]['tipo_tono'] == 3) echo 'checked'; ?>>Atetósico</label></td>
                                                        <td><label class="form-check-label"><input disabled type="radio" class="form-check-input" name="tono_muscular" value="4" <?php if ($consulta[0]['tipo_tono'] == 4) echo 'checked'; ?>>Fluctuante</label></td>
                                                    </tr>
                                                </table>
                                                <div class="form-inline">
                                                    <label class="mr-2" for="limit_amplit">Presenta limitación en amplitudes articulares:</label>
                                                    <input disabled class="form-check-input" type="checkbox" id="limit_amplit" name="limit_amplit" <?php if ($consulta[0]['limitacion_artic'] == 1) echo 'checked disabled'; ?>>
                                                </div>
                                                <div class="input-group col-md-11">
                                                    <label for="especifica_tono"
                                                        class="col-form-label">Especifique:</label>
                                                    <textarea id="especifica_tono" name="especifica_tono"
                                                        class="form-control" maxlength="150" rows="2" disabled
                                                        style="width:100%; resize:none;"><?php echo $consulta[0]['especificacion']; ?></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="escala_desarrollo">
                                            </br>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Escala</th>
                                                        <th>Si</th>
                                                        <th>No</th>
                                                        <th>Inicia</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Control de cuello</td>
                                                        <td><input disabled type="radio" name="control_cuello" value="1" <?php if ($consulta[0]['control_cuello'] == 1) echo 'checked'; ?>></td>
                                                        <td><input disabled type="radio" name="control_cuello" value="2" <?php if ($consulta[0]['control_cuello'] == 2) echo 'checked'; ?>></td>
                                                        <td><input disabled type="radio" name="control_cuello" value="3" <?php if ($consulta[0]['control_cuello'] == 3) echo 'checked'; ?>></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Rotación de prono a supino</td>
                                                        <td><input type="radio" name="prono_supino" value=1 disabled <?php if ($consulta[0]['rotacion_prono_supino'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="prono_supino" value=2 disabled <?php if ($consulta[0]['rotacion_prono_supino'] == 2) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="prono_supino" value=3 disabled <?php if ($consulta[0]['rotacion_prono_supino'] == 3) echo 'checked'; ?>></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Rotación de supino a prono</td>
                                                        <td><input type="radio" name="supino_prono" value=1 disabled <?php if ($consulta[0]['rotacion_supino_prono'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="supino_prono" value=2 disabled <?php if ($consulta[0]['rotacion_supino_prono'] == 2) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="supino_prono" value=3 disabled <?php if ($consulta[0]['rotacion_supino_prono'] == 3) echo 'checked'; ?>></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Control de tronco superior</td>
                                                        <td><input type="radio" name="tronco_superior" value=1 disabled <?php if ($consulta[0]['tronco_superior'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="tronco_superior" value=2 disabled <?php if ($consulta[0]['tronco_superior'] == 2) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="tronco_superior" value=3 disabled <?php if ($consulta[0]['tronco_superior'] == 3) echo 'checked'; ?>></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Control de tronco inferior</td>
                                                        <td><input type="radio" name="tronco_inferior" value=1 disabled <?php if ($consulta[0]['tronco_inferior'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="tronco_inferior" value=2 disabled <?php if ($consulta[0]['tronco_inferior'] == 2) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="tronco_inferior" value=3 disabled <?php if ($consulta[0]['tronco_inferior'] == 3) echo 'checked'; ?>></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Posición de cuatro puntos</td>
                                                        <td><input type="radio" name="cuatro_puntos" value=1 disabled <?php if ($consulta[0]['cuatro_puntos'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="cuatro_puntos" value=2 disabled <?php if ($consulta[0]['cuatro_puntos'] == 2) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="cuatro_puntos" value=3 disabled <?php if ($consulta[0]['cuatro_puntos'] == 3) echo 'checked'; ?>></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Adopta posición sedente</td>
                                                        <td><input type="radio" name="pos_sedente" value=1 disabled <?php if ($consulta[0]['posicion_sedente'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="pos_sedente" value=2 disabled <?php if ($consulta[0]['posicion_sedente'] == 2) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="pos_sedente" value=3 disabled <?php if ($consulta[0]['posicion_sedente'] == 3) echo 'checked'; ?>></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Adopta posición hincado</td>
                                                        <td><input type="radio" name="pos_hincado" value=1 disabled <?php if ($consulta[0]['posicion_hincado'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="pos_hincado" value=2 disabled <?php if ($consulta[0]['posicion_hincado'] == 2) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="pos_hincado" value=3 disabled <?php if ($consulta[0]['posicion_hincado'] == 3) echo 'checked'; ?>></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Adopta posición semi-hincado</td>
                                                        <td><input type="radio" name="pos_semihincado" value=1 disabled <?php if ($consulta[0]['posicion_semihincado'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="pos_semihincado" value=2 disabled <?php if ($consulta[0]['posicion_semihincado'] == 2) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="pos_semihincado" value=3 disabled <?php if ($consulta[0]['posicion_semihincado'] == 3) echo 'checked'; ?>></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Adopta posición de bidepestación</td>
                                                        <td><input type="radio" name="pos_bidepestacion" value=1 disabled <?php if ($consulta[0]['posicion_bidepestacion'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="pos_bidepestacion" value=2 disabled <?php if ($consulta[0]['posicion_bidepestacion'] == 2) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="pos_bidepestacion" value=3 disabled <?php if ($consulta[0]['posicion_bidepestacion'] == 3) echo 'checked'; ?>></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="atencion">
                                            </br>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Descripción</th>
                                                        <th>Existe</th>
                                                        <th>No existe</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>Localización</td>
                                                    <td><input disabled type="radio" name="localizacion" value="1" <?php if ($consulta[0]['localizacion'] == 1) echo 'checked'; ?>></td>
                                                    <td><input disabled type="radio" name="localizacion" value="0" <?php if ($consulta[0]['localizacion'] == 0) echo 'checked'; ?>></td>
                                                </tr>
                                                <tr>
                                                    <td>Fijación</td>
                                                    <td><input disabled type="radio" name="fijacion" value="1" <?php if ($consulta[0]['fijacion'] == 1) echo 'checked'; ?>></td>
                                                    <td><input disabled type="radio" name="fijacion" value="0" <?php if ($consulta[0]['fijacion'] == 0) echo 'checked'; ?>></td>
                                                </tr>
                                                <tr>
                                                    <td>Seguimiento</td>
                                                    <td><input disabled type="radio" name="seguimiento" value="1" <?php if ($consulta[0]['seguimiento'] == 1) echo 'checked'; ?>></td>
                                                    <td><input disabled type="radio" name="seguimiento" value="0" <?php if ($consulta[0]['seguimiento'] == 0) echo 'checked'; ?>></td>
                                                </tr>
                                                <tr>
                                                    <td>Alcance</td>
                                                    <td><input disabled type="radio" name="alcance" value="1" <?php if ($consulta[0]['alcance'] == 1) echo 'checked'; ?>></td>
                                                    <td><input disabled type="radio" name="alcance" value="0" <?php if ($consulta[0]['alcance'] == 0) echo 'checked'; ?>></td>
                                                </tr>
                                                <tr>
                                                    <td>Manipulación</td>
                                                    <td><input disabled type="radio" name="manipulacion" value="1" <?php if ($consulta[0]['manipulacion'] == 1) echo 'checked'; ?>></td>
                                                    <td><input disabled type="radio" name="manipulacion" value="0" <?php if ($consulta[0]['manipulacion'] == 0) echo 'checked'; ?>></td>
                                                </tr>
                                                <tr>
                                                    <td>Exploración</td>
                                                    <td><input disabled type="radio" name="exploracion" value="1" <?php if ($consulta[0]['exploracion'] == 1) echo 'checked'; ?>></td>
                                                    <td><input disabled type="radio" name="exploracion" value="0" <?php if ($consulta[0]['exploracion'] == 0) echo 'checked'; ?>></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="destrezas_manuales">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Actividad</th>
                                                        <th>Si</th>
                                                        <th>No</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Sostiene un objeto</td>
                                                        <td><input type="radio" name="sostiene_objeto" value=1 disabled <?php if ($consulta[0]['sostiene_objeto'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="sostiene_objeto" value=0 disabled <?php if ($consulta[0]['sostiene_objeto'] == 0) echo 'checked'; ?>></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Suelta un objeto</td>
                                                        <td><input type="radio" name="suelta_objeto" value=1 disabled <?php if ($consulta[0]['suelta_objeto'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="suelta_objeto" value=0 disabled <?php if ($consulta[0]['suelta_objeto'] == 0) echo 'checked'; ?>></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Atrapa un objeto</td>
                                                        <td><input type="radio" name="atrapa_objeto" value=1 disabled <?php if ($consulta[0]['atrapa_objeto'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="atrapa_objeto" value=0 disabled <?php if ($consulta[0]['atrapa_objeto'] == 0) echo 'checked'; ?>></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Lanza un objeto</td>
                                                        <td><input type="radio" name="lanza_objeto" value=1 disabled <?php if ($consulta[0]['lanza_objeto'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="lanza_objeto" value=0 disabled <?php if ($consulta[0]['lanza_objeto'] == 0) echo 'checked'; ?>></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Realiza un nudo</td>
                                                        <td><input type="radio" name="realiza_nudo" value=1 disabled <?php if ($consulta[0]['realiza_nudo'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="realiza_nudo" value=0 disabled <?php if ($consulta[0]['realiza_nudo'] == 0) echo 'checked'; ?>></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Encaja</td>
                                                        <td><input type="radio" name="encaja" value=1 disabled <?php if ($consulta[0]['encaja'] == 1) echo 'checked'; ?>></td>
                                                        <td><input type="radio" name="encaja" value=0 disabled <?php if ($consulta[0]['encaja'] == 0) echo 'checked'; ?>></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="actividades_diaria">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Actividad</th>
                                                        <th>I</th>
                                                        <th>SI</th>
                                                        <th>D</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>Alimentación</td>
                                                    <td><input type="radio" name="alimentacion" value="1" <?php if ($consulta[0]['alimentacion'] == 1) echo 'checked'; ?> disabled></td>
                                                    <td><input type="radio" name="alimentacion" value="2" <?php if ($consulta[0]['alimentacion'] == 2) echo 'checked'; ?> disabled></td>
                                                    <td><input type="radio" name="alimentacion" value="3" <?php if ($consulta[0]['alimentacion'] == 3) echo 'checked'; ?> disabled></td>
                                                </tr>
                                                <tr>
                                                    <td>Higiene</td>
                                                    <td><input type="radio" name="higiene" value="1" <?php if ($consulta[0]['higiene'] == 1) echo 'checked'; ?> disabled></td>
                                                    <td><input type="radio" name="higiene" value="2" <?php if ($consulta[0]['higiene'] == 2) echo 'checked'; ?> disabled></td>
                                                    <td><input type="radio" name="higiene" value="3" <?php if ($consulta[0]['higiene'] == 3) echo 'checked'; ?> disabled></td>
                                                </tr>
                                                <tr>
                                                    <td>Vestuario</td>
                                                    <td><input type="radio" name="vestuario" value="1" <?php if ($consulta[0]['vestuario'] == 1) echo 'checked'; ?> disabled></td>
                                                    <td><input type="radio" name="vestuario" value="2" <?php if ($consulta[0]['vestuario'] == 2) echo 'checked'; ?> disabled></td>
                                                    <td><input type="radio" name="vestuario" value="3" <?php if ($consulta[0]['vestuario'] == 3) echo 'checked'; ?> disabled></td>
                                                </tr>
                                                <tr>
                                                    <td>Control de esfínteres</td>
                                                    <td><input type="radio" name="control_esfinteres" value="1" <?php if ($consulta[0]['control_esfinteres'] == 1) echo 'checked'; ?> disabled></td>
                                                    <td><input type="radio" name="control_esfinteres" value="2" <?php if ($consulta[0]['control_esfinteres'] == 2) echo 'checked'; ?> disabled></td>
                                                    <td><input type="radio" name="control_esfinteres" value="3" <?php if ($consulta[0]['control_esfinteres'] == 3) echo 'checked'; ?> disabled></td>
                                                </tr>
                                                <tr>
                                                    <td>Orden y Limpieza</td>
                                                    <td><input type="radio" name="orden_limpieza" value="1" <?php if ($consulta[0]['orden_limpieza'] == 1) echo 'checked'; ?> disabled></td>
                                                    <td><input type="radio" name="orden_limpieza" value="2" <?php if ($consulta[0]['orden_limpieza'] == 2) echo 'checked'; ?> disabled></td>
                                                    <td><input type="radio" name="orden_limpieza" value="3" <?php if ($consulta[0]['orden_limpieza'] == 3) echo 'checked'; ?> disabled></td>
                                                </tr>
                                                <tr>
                                                    <td>Ocio y recreación</td>
                                                    <td><input type="radio" name="ocio_recreacion" value="1" <?php if ($consulta[0]['ocio_recreacion'] == 1) echo 'checked'; ?> disabled></td>
                                                    <td><input type="radio" name="ocio_recreacion" value="2" <?php if ($consulta[0]['ocio_recreacion'] == 2) echo 'checked'; ?> disabled></td>
                                                    <td><input type="radio" name="ocio_recreacion" value="3" <?php if ($consulta[0]['ocio_recreacion'] == 3) echo 'checked'; ?> disabled></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div class="form-group col-md-12">
                                                <div class="input-group col-md-11">
                                                    <label for="observaciones_actividades"
                                                        class="col-form-label">Observaciones:</label>
                                                    <textarea disabled id="observaciones_actividades" name="observaciones_actividades"
                                                        class="form-control" rows="2"
                                                        style="width:100%; resize:none;"><?php echo $consulta[0]['observaciones_act']; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="postura">
                                            <div class="form-group col-md-11">
                                                <div class="input-group col-md-11">
                                                    <label for="observaciones_postura"
                                                        class="col-form-label">Observaciones:</label>
                                                    <textarea disabled id="observaciones_postura" name="observaciones_postura"
                                                        class="form-control" rows="2"
                                                        style="width:100%; resize:none;"><?php echo $consulta[0]['observaciones_post']; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="marcha_desplaza">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <td>Realiza la marcha</td>
                                                    <td><label><input disabled type="radio" name="marcha" value="1" <?php if ($consulta[0]['realiza_marcha'] == 1) echo 'checked'; ?>> Si</label></td>
                                                    <td><label><input disabled type="radio" name="marcha" value="0" <?php if ($consulta[0]['realiza_marcha'] == 0) echo 'checked'; ?>> No</label></td>
                                                </tr>
                                                <tr>
                                                    <td>Base de sustentación</td>
                                                    <td><label><input disabled type="radio" name="sustentacion" value="1" <?php if ($consulta[0]['base_sustentacion'] == 1) echo 'checked'; ?>> Amplia</label></td>
                                                    <td><label><input disabled type="radio" name="sustentacion" value="2" <?php if ($consulta[0]['base_sustentacion'] == 2) echo 'checked'; ?>> Disminuida</label></td>
                                                    <td><label><input disabled type="radio" name="sustentacion" value="3" <?php if ($consulta[0]['base_sustentacion'] == 3) echo 'checked'; ?>> Proporcionada</label></td>
                                                </tr>
                                                <tr>
                                                    <td>Usa silla de ruedas</td>
                                                    <td><label><input disabled type="radio" name="silla_ruedas" value="1" <?php if ($consulta[0]['silla_ruedas'] == 1) echo 'checked'; ?>> Si</label></td>
                                                    <td><label><input disabled type="radio" name="silla_ruedas" value="0" <?php if ($consulta[0]['silla_ruedas'] == 0) echo 'checked'; ?>> No</label></td>
                                                </tr>
                                                <tr>
                                                    <td>Realiza con apoyo</td>
                                                    <td><label><input disabled type="radio" name="apoyo" value="1" <?php if ($consulta[0]['realiza_apoyo'] == 1) echo 'checked'; ?>> Si</label></td>
                                                    <td><label><input disabled type="radio" name="apoyo" value="0" <?php if ($consulta[0]['realiza_apoyo'] == 0) echo 'checked'; ?>> No</label></td>
                                                </tr>
                                                <tr>
                                                    <td>Equilibrio</td>
                                                    <td><label><input disabled type="radio" name="equilibrio" value="1" <?php if ($consulta[0]['equilibrio'] == 1) echo 'checked'; ?>> Bueno</label></td>
                                                    <td><label><input disabled type="radio" name="equilibrio" value="2" <?php if ($consulta[0]['equilibrio'] == 2) echo 'checked'; ?>> Regular</label></td>
                                                    <td><label><input disabled type="radio" name="equilibrio" value="3" <?php if ($consulta[0]['equilibrio'] == 3) echo 'checked'; ?>> Malo</label></td>
                                                </tr>
                                                <tr>
                                                    <td>Coordinación</td>
                                                    <td><label><input disabled type="radio" name="coordinacion" value="1" <?php if ($consulta[0]['coordinacion'] == 1) echo 'checked'; ?>> Bueno</label></td>
                                                    <td><label><input disabled type="radio" name="coordinacion" value="2" <?php if ($consulta[0]['coordinacion'] == 2) echo 'checked'; ?>> Regular</label></td>
                                                    <td><label><input disabled type="radio" name="coordinacion" value="3" <?php if ($consulta[0]['coordinacion'] == 3) echo 'checked'; ?>> Malo</label></td>
                                                </tr>
                                                <tr>
                                                    <td>Utiliza dispositivo:</td>
                                                    <td><label><input disabled type="radio" name="dispositivo" value="1" <?php if ($consulta[0]['utiliza_dispositivo'] == 1) echo 'checked'; ?>> Si</label></td>
                                                    <td><label><input disabled type="radio" name="dispositivo" value="0" <?php if ($consulta[0]['utiliza_dispositivo'] == 0) echo 'checked'; ?>> No</label></td>
                                                </tr>

                                                </tbody>
                                            </table>
                                            <div class="input-group col-md-11">
                                                <label for="observaciones_postura"
                                                    class="col-form-label">Cual dispositivo?:</label>
                                                <textarea disabled id="cual_dispositivo" name="cual_dispositivo"
                                                    class="form-control" rows="2"
                                                    style="width:100%; resize:none;"><?php echo $consulta[0]['cual_dispositivo']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                               <div class="modal-footer clearfix">
                                    <!-- <div id="footerButtons" class="text-center">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                            GUARDAR</button>
                                        <button type="button" class="btn btn-danger" onclick="history.back()"><i
                                                class="fa fa-times"></i> Cerrar</button>
                                    </div> -->
                                </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</aside>