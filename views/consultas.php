<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/consultas.css">

<script>
    $(document).ready(function() {
        $('#tabs').tab();
    })

    $(function() {
        if ($('input[name="checkboxMetodos"]').prop('checked')) {
            $('select[name="metodo"]').fadeIn()
        } else {
            $('select[name="metodo"]').hide()
        }

        //Mostrar los metodos cuando el checkbox es seleccionado
        $('input[name="checkboxMetodos"]').on('click', function() {
            if ($(this).prop('checked')) {
                $('select[name="metodo"]').fadeIn();
            } else {
                $('select[name="metodo"]').hide();

            }

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
            <div class="col-xs-12">
                <div class="box">

                    <form>
                        <div class="container">

                            <ul id="tabs" class="nav nav-pills" data-tabs="tabs">
                                <li class="active"><a href="#antecedentes" data-toggle="tab">Antecedentes</a> </li>
                                <li><a href="#examen" data-toggle="tab">Exámen Físico</a></li>
                                <li><a href="#colposcopia" data-toggle="tab">Colposcopía</a></li>
                                <li><a href="#ultrasonido" data-toggle="tab">Ultrasonido</a></li>
                                <li><a href="#diagnostico" data-toggle="tab">Diagnostico y tratamiento</a></li>
                            </ul>
                            <div id="my-tab-content" class="tab-content">
                                <div class="tab-pane fade in active" id="antecedentes">
                                    <br>
                                    <div class="form-group col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">Motivo:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">Historia de la enfermedad:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <h4>Antecedentes</h4>
                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">Médicos:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">Quirúrgicos:</span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">Alérgicos:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">Traumáticos:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">Vicios y manías:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <h4>Antecedentes ginecológicos</h4>

                                    <div class="form-group col-sm-1">
                                        <label for="txtEmbarazos">Embarazos:</label>
                                        <div class="input-group">
                                            <input type="number" id="txtObservaciones" value="0" name="observaciones" class="form-control" maxlength="2"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="txtPartos">Menarquía: </label>
                                        <div class="input-group">
                                            <input type="text" id="txtObservaciones" name="observaciones" class="form-control" maxlength="150"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="txtPartos">Ciclo: </label>
                                        <div class="input-group">
                                            <input type="text" id="txtObservaciones" name="observaciones" class="form-control" maxlength="150"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="txtPartos">Duración: </label>
                                        <div class="input-group">
                                            <input type="number" id="txtObservaciones" placeholder="días" name="observaciones" class="form-control" maxlength="150"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="txtPartos">Dolor</label>

                                        <input type="checkbox" aria-label="Checkbox for following text input" name="checkboxMetodos">

                                        <select name="metodos" class="form-control">
                                            <option selected disabled>Seleccionar</option>
                                            <option value="1">Leve</option>
                                            <option value="2">Moderada</option>
                                            <option value="3">Abundante</option>
                                            <option value="4">Muy abundante</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="txtPartos">ETS</label>

                                        <input type="checkbox" aria-label="Checkbox for following text input" name="checkboxMetodos">

                                        <select name="metodos" class="form-control">
                                            <option selected disabled>Seleccionar</option>
                                            <option value="1">VPH</option>
                                            <option value="2">HIH</option>
                                            <option value="3">Clamidia</option>
                                            <option value="4">Gardenerella</option>
                                            <option value="5">Herpes genital</option>
                                            <option value="6">Tricomona</option>
                                            <option value="7">Cándida</option>
                                            <option value="8">Otros</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-2">
                                        <label for="txtPartos">¿Está embarazada?</label>
                                        <input type="checkbox" aria-label="Checkbox for following text input" name="checkboxMetodos">
                                        <input type="number" id="txtObservaciones" placeholder="Semanas" name="observaciones" class="form-control" maxlength="150"></input>

                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="txtPartos">¿M.A?</label>

                                        <input type="checkbox" aria-label="Checkbox for following text input" name="checkboxMetodos">

                                        <select name="metodos" class="form-control">
                                            <option selected disabled>Seleccionar</option>
                                            <option value="1">Ninguno</option>
                                            <option value="2">Depropovera</option>
                                            <option value="3">Jadell</option>
                                            <option value="4">T de cobre</option>
                                            <option value="5">Mirena</option>
                                            <option value="6">OTB</option>
                                            <option value="7">Otros</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-7">
                                        <label for="txtPartos">F.U.R: </label>
                                        <div class="input-group">
                                            <input name="ultimoPapanicolau" type="text" class="form-control" min="1970-01-01" onfocus="this.type='date'">
                                        </div>
                                    </div>
                                    <h4> Antecedentes obstetricos </h4>
                                    <div class="form-group col-sm-1">
                                        <label for="txtEmbarazos">Partos:</label>
                                        <div class="input-group">
                                            <input type="number" id="txtObservaciones" value="0" name="observaciones" class="form-control" maxlength="2"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="txtEmbarazos">Cesáreas:</label>
                                        <div class="input-group">
                                            <input type="number" id="txtObservaciones" value="0" name="observaciones" class="form-control" maxlength="2"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="txtEmbarazos">Abortos:</label>
                                        <div class="input-group">
                                            <input type="number" id="txtObservaciones" value="0" name="observaciones" class="form-control" maxlength="2"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="txtEmbarazos">HV:</label>
                                        <div class="input-group">
                                            <input type="number" id="txtObservaciones" value="0" name="observaciones" class="form-control" maxlength="2"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="txtEmbarazos">HV:</label>
                                        <div class="input-group">
                                            <input type="number" id="txtObservaciones" value="0" name="observaciones" class="form-control" maxlength="2"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="txtEmbarazos">HM:</label>
                                        <div class="input-group">
                                            <input type="number" id="txtObservaciones" value="0" name="observaciones" class="form-control" maxlength="2"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="txtEmbarazos">Obito F:</label>
                                        <div class="input-group">
                                            <input type="number" id="txtObservaciones" value="0" name="observaciones" class="form-control" maxlength="2"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="txtPartos">Último papanicolaou: </label>
                                        <div class="input-group">
                                            <input name="ultimoPapanicolau" type="text" class="form-control" min="1970-01-01" onfocus="this.type='date'">
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="txtEmbarazos">Cantidad:</label>
                                        <div class="input-group">
                                            <input type="number" id="txtObservaciones" value="0" name="observaciones" class="form-control" maxlength="2"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="txtEmbarazos">P.S:</label>
                                        <div class="input-group">
                                            <input type="number" value="0" id="txtObservaciones" name="observaciones" class="form-control" maxlength="3"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="txtEmbarazos">I.V.S:</label>
                                        <div class="input-group">
                                            <input type="text" id="txtObservaciones" placeholder="Edad" name="observaciones" class="form-control" maxlength="20"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="txtEmbarazos">P.S.P:</label>
                                        <div class="input-group">
                                            <input type="number" value="0" id="txtObservaciones" name="observaciones" class="form-control" maxlength="3"></input>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="examen">
                                    <br>
                                    <div class="form-group col-sm-1">
                                        <label for="txtPartos">P/A: </label>
                                        <div class="input-group">
                                            <input type="text" id="txtObservaciones" placeholder="0/0" name="observaciones" class="form-control" maxlength="5"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="txtPartos">Temperatura: </label>
                                        <div class="input-group">
                                            <input type="text" id="txtObservaciones" placeholder="10°" name="observaciones" class="form-control" maxlength="2"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="txtPartos">Pulso: </label>
                                        <div class="input-group">
                                            <input type="text" id="txtObservaciones" name="observaciones" class="form-control" maxlength="5"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label for="txtPartos">SPO %: </label>
                                        <div class="input-group">
                                            <input type="text" id="txtObservaciones" name="observaciones" class="form-control" maxlength="5"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="txtPartos">Peso: </label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Libras:</span>
                                            <input type="text" id="txtObservaciones" name="observaciones" class="form-control" maxlength="5"></input>
                                            <span class="input-group-addon">Onzas:</span>
                                            <input type="text" id="txtObservaciones" name="observaciones" class="form-control" maxlength="5"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="txtPartos">Estatura: </label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Metros:</span>
                                            <input type="text" id="txtObservaciones" name="observaciones" class="form-control" maxlength="5"></input>
                                            <span class="input-group-addon">Centímetros:</span>
                                            <input type="text" id="txtObservaciones" name="observaciones" class="form-control" maxlength="5"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="txtPartos">Peso: </label>
                                        <div class="input-group">
                                            <select name="estadoCivil" class="form-control">
                                                <option selected disabled>Peso</option>
                                                <option value="1">Bien</option>
                                                <option value="2">Mal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-7">
                                        <label for="txtPartos">F.R: </label>
                                        <div class="input-group">
                                            <input type="text" id="txtObservaciones" name="observaciones" class="form-control" maxlength="50"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">A) Piel y mucosas:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">B) Cabeza y cuello:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">C) Tórax:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">D) Abdomen:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">E) Ex, cadera y columna:</span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">F) Ex. Ginecológico:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">Impresión clínica:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
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

                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                1. Mucosa originaria
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                2. Ectopía
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                3. Zona de transformación
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                4. Zona de transformación atípica
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                5. Epitelio acético positivo
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                6. Leucoplasia
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                7. Puntuación
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                8. Mosaico
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                9. Mosaico - Puntuación
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                10. Atipias Vasculares
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                11. Carcinoma
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                12. Condiloma
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                13. Cervicitis
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                14. Atrofias
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                15. Otros
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">Impresión colposcopica:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">Unión Escamo Columnar:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">ResHB:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="1"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">Result. Hist. Biopsia:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="1"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">Correlación:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="1"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">Sugerencia:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="1"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">Tratamiento adoptado:</i></span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-7">
                                        <div class="input-group">
                                            <span class="input-group-addon">Referido a:</span>
                                            <input type="text" id="txtObservaciones" name="observaciones" class="form-control" maxlength="5"></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Fecha Referencia:</span>
                                            <input name="fechaReferencia" type="text" class="form-control" min="1970-01-01" onfocus="this.type='date'" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="ultrasonido">
                                </br>
                                <div class="form-group col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Tipo de ultrasonido </span>
                                            <select name="tipoUltrasonido" class="form-control" required>
                                                <option selected disabled>Seleccionar</option>
                                                <option value="1">Pélvico</option>
                                                <option value="2">Endovaginal</option>
                                                <option value="3">Obstétrico</option>
                                                <option value="4">Abdominal</option>
                                                <option value="5">Renal</option>
                                                <option value="6">Prostático</option>
                                                <option value="7">Mamario</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                    <div class="input-group">
                                            <span class="input-group-addon">Valor del ultrasonido Q. </span>
                                            <input type="number" id="txtObservaciones" value="0.00" min="0.00"  name="observaciones" class="form-control" maxlength="2"></input>
                                            
                                        </div>
                                    </div>


                                    


                                </div>
                                <div class="tab-pane fade" id="diagnostico">
                                    <br>
                                    <div class="form-group col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">Plan de tratamiento:</span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon">Plan Educacional:</span>
                                            <textarea id="txtObservaciones" name="observaciones" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Referido a:</span>
                                            <select name="estadoCivil" class="form-control">
                                                <option selected disabled>Seleccionar</option>
                                                <option value="1">Doctor 1</option>
                                                <option value="2">Doctor 2</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">Valor: Q</span>
                                            <input type="number" min="1" placeholder="0.00" step="any" class="form-control" required></input>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Próxima cita:</span>
                                            <input name="ultimoPapanicolau" type="text" class="form-control" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" onfocus="this.type='date'" required>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</aside>