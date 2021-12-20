<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/pacientes.css">

<script>
 $(function() {
        $("#editPatient").submit(function() {
            $.ajax({
                type: "POST",
                url: $("#editPacient").attr('action'),
                data: $("#editPacient").serialize(),
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
</script>
<?php $min = 'Pacientes';
$may = 'PACIENTES';
$minS = 'Paciente'; ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            <?php echo $min; ?>
            <small>Mantenimiento de <?php echo $min; ?></small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo BASE_DIR; ?>pacientes" class="btn btn-primary btn-sm"><i class="fa fa-list"></i> LISTADO DE <?php echo $may; ?></a>
            </li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-sm-15">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Editar <?php echo $minS; ?></h3>
                    </div>
                    <form id="editPacient" method="POST">
                        <div class="modal-body">

                            <div class="foto-div" >
                              
                                <?php
                                //obtiene la imagen del servidor con el nombre guardado en la base de datos,
                                //si no existe coloca una generica
                                $imagesDir = "/xampp/uploads/";
                                $content = @file_get_contents($imagesDir.$paciente['foto'].'.png');
                                $content = base64_encode($content);
                                
                                base64_decode($content);

                                ?>
                                <?php if($content != '') { ?>
                                
                                <img class="img-paciente" src ="data:image/png;base64, <?php echo $content?> "/>
                                
                                <?php } else { ?>
                                    <?php $content = file_get_contents($imagesDir.'user.png');
                                     $content = base64_encode($content);
                                     base64_decode($content);
                                    ?>
                                    <img class="img-paciente" src ="data:image/png;base64, <?php echo $content?> "/>
                                    <?php } ?>
                                
                                    <input name="imagen" type="button" value="Cambiar foto" onClick="">
                            </div>

                            <div class="form-group col-md-4">
                            <label for="txtNombres">Nombres</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input name="nombres" type="text" class="form-control" placeholder="Nombres" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="45" value="<?php echo $paciente['nombres']; ?>" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                            <label for="txtApellidos">Apellidos</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input name="apellidos" type="text" class="form-control" placeholder="Apellidos *" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="45" value="<?php echo $paciente['apellidos']; ?>" required>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                            <label for="txtDireccion">Dirección</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-building"></i></span>
                                    <input name="direccion" type="text" class="form-control" placeholder="Dirección" maxlength="75" value="<?php echo $paciente['direccion']; ?>" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                            <label for="txtDireccionTrabajo">Dirección de trabajo</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-suitcase"></i></span>
                                    <input name="direccionTrabajo" type="text" class="form-control" value="<?php echo $paciente['direccion_trabajo']; ?>" maxlength="75">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                            <label for="txtLugarTrabajo">Lugar de trabajo</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-suitcase"></i></span>
                                    <input name="lugarTrabajo" type="text" class="form-control" value="<?php echo $paciente['lugar_trabajo']; ?>" maxlength="75">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                            <label for="txtOcupacion">Ocupación</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-building"></i></span>
                                    <input name="ocupacion" type="text" class="form-control" placeholder="Ocupación" maxlength="20" value="<?php echo $paciente['ocupacion']; ?>" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                            <label for="txtTelefono">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                    <input name="telefono" type="text" class="form-control" placeholder="Teléfono" oninput="this.value =
									this.value.replace(/[^0-9.,+]/g,'').replace(/(\..*)\./g, '$1');" maxlength="15" value="<?php echo $paciente['telefono']; ?>" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                            <label for="txtFechaNacimiento">Fecha de Nacimiento</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input name="fechaNacimiento" type="text" placeholder="Fecha de nacimiento" class="form-control" min="1970-01-01" max="<?php echo date('Y-m-d') ?>" onfocus="this.type='date'" value="<?php echo $paciente['fecha_nacimiento']; ?>" required>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                            <label for="txtDpi">DPI</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-table"></i></span>
                                    <input name="dpi" type="text" class="form-control" oninput="this.value =
									this.value.replace(/[^0-9.]/g,'').replace(/(\..*)\./g, '$1');" maxlength="13" value="<?php echo $paciente['dpi']; ?>">
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                            <label for="txt">Sexo</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-intersex"></i></span>
                                    <select name="genero" class="form-control">
                                        <option value="<?php echo $genero['codigo']; ?>" <?php if ($genero['nombre'] == 'Masculino') echo 'selected' ?>>Masculino</option>
                                        <option value="<?php echo $genero['codigo']; ?>" <?php if ($genero['nombre'] == 'Femenino') echo 'selected' ?>>Femenino</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                            <label for="txtEstadoCivil">Estado Civil</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-mars-stroke-v"></i></span>
                                    <select name="estadoCivil" class="form-control">
                                        <option value="<?php echo $estadoCivil['codigo']; ?>" <?php if ($estadoCivil['nombre'] == 'Soltero(a)') echo 'selected' ?>>Soltero(a)</option>
                                        <option value="<?php echo $estadoCivil['codigo']; ?>" <?php if ($estadoCivil['nombre'] == 'Casado(a)') echo 'selected' ?>>Casado(a)</option>
                                        <option value="<?php echo $estadoCivil['codigo']; ?>" <?php if ($estadoCivil['nombre'] == 'Viudo(a)') echo 'selected' ?>>Viudo(a)</option>
                                        <option value="<?php echo $estadoCivil['codigo']; ?>" <?php if ($estadoCivil['nombre'] == 'Divorciado(a)') echo 'selected' ?>>Divorciado(a)</option>
                                        <option value="<?php echo $estadoCivil['codigo']; ?>" <?php if ($estadoCivil['nombre'] == 'Unido(a)') echo 'selected' ?>>Unido(a)</option>
                                        <option value="<?php echo $estadoCivil['codigo']; ?>" <?php if ($estadoCivil['nombre'] == 'Otro(a)') echo 'selected' ?>>Otro(a)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                            <label for="txtTipoSangre">Escolaridad</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-university"></i></span>
                                    <select name="escolaridad" class="form-control">
                                        <option value="<?php echo $escolaridad['codigo']; ?>" <?php if ($escolaridad['nombre'] == 'Ninguna') echo 'selected' ?>>Ninguna</option>
                                        <option value="<?php echo $escolaridad['codigo']; ?>" <?php if ($escolaridad['nombre'] == 'Primaria') echo 'selected' ?>>Primaria</option>
                                        <option value="<?php echo $escolaridad['codigo']; ?>" <?php if ($escolaridad['nombre'] == 'Básico') echo 'selected' ?>>Básico</option>
                                        <option value="<?php echo $escolaridad['codigo']; ?>" <?php if ($escolaridad['nombre'] == 'Diversificado') echo 'selected' ?>>Diversificado</option>
                                        <option value="<?php echo $escolaridad['codigo']; ?>" <?php if ($escolaridad['nombre'] == 'Carrera técnica') echo 'selected' ?>>Carrera técnica</option>
                                        <option value="<?php echo $escolaridad['codigo']; ?>" <?php if ($escolaridad['nombre'] == 'Universidad') echo 'selected' ?>>Universidad</option>
                                        <option value="<?php echo $escolaridad['codigo']; ?>" <?php if ($escolaridad['nombre'] == 'Especialización') echo 'selected' ?>>Especialización</option>
                                        <option value="<?php echo $escolaridad['codigo']; ?>" <?php if ($escolaridad['nombre'] == 'Otro') echo 'selected' ?>>Otro</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                            <label for="txtTipoSangre">Tipo de sangre</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-university"></i></span>
                                    <select name="tipoSangre" class="form-control">              
                                        <option value="<?php echo $tipoSangre['codigo']; ?>" <?php if ($tipoSangre['nombre'] == 'No sabe') echo 'selected' ?>>No sabe</option>
                                        <option value="<?php echo $tipoSangre['codigo']; ?>" <?php if ($tipoSangre['nombre'] == 'O Positivo') echo 'selected' ?>>O Positivo</option>
                                        <option value="<?php echo $tipoSangre['codigo']; ?>" <?php if ($tipoSangre['nombre'] == 'O Negativo') echo 'selected' ?>>O Negativo</option>
                                        <option value="<?php echo $tipoSangre['codigo']; ?>" <?php if ($tipoSangre['nombre'] == 'A Positivo') echo 'selected' ?>>A Positivo</option>
                                        <option value="<?php echo $tipoSangre['codigo']; ?>" <?php if ($tipoSangre['nombre'] == 'A Negativo') echo 'selected' ?>>A Negativo</option>
                                        <option value="<?php echo $tipoSangre['codigo']; ?>" <?php if ($tipoSangre['nombre'] == 'B Positivo') echo 'selected' ?>>B Positivo</option>
                                        <option value="<?php echo $tipoSangre['codigo']; ?>" <?php if ($tipoSangre['nombre'] == 'B Negativo') echo 'selected' ?>>B Negativo</option>
                                        <option value="<?php echo $tipoSangre['codigo']; ?>" <?php if ($tipoSangre['nombre'] == 'AB Positivo') echo 'selected' ?>>AB Positivo</option>
                                        <option value="<?php echo $tipoSangre['codigo']; ?>" <?php if ($tipoSangre['nombre'] == 'AB Negativo') echo 'selected' ?>>AB Negativo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                            <label for="txtConyugue">Cónyugue </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input name="conyugue" type="text" class="form-control" placeholder="Nombre del cónyugue" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="50" value="<?php echo $paciente['conyugue']; ?>">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lblResponsable">Responsable </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input name="responsable" type="text" class="form-control" placeholder="Responsable" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="75" value="<?php echo $paciente['responsable']; ?>">
                                </div>
                            </div>
                            <h4 class="modal-header"><i class="fa fa-file-o"></i> Antecedentes familiares</h4>
                            <div class="form-group col-md-4">
                                <label for="lblPadre">Padre </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input name="padre" type="text" class="form-control" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="45" value="<?php echo $paciente['padre']; ?>">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="txtMadre">Madre </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input name="madre" type="text" class="form-control" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="45" value="<?php echo $paciente['madre']; ?>">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="txtHermanos">Hermanos </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input name="hermanos" type="text" class="form-control" oninput="this.value =
									this.value.replace(/[^a-zA-Z ]/g,'').replace(/(\..*)\./g, '$1');" maxlength="75" value="<?php echo $paciente['hermanos']; ?>">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="txtObservaciones">Observaciones </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                    <textarea id="txtObservaciones" name="observaciones" class="form-control" maxlength="150" rows="1"><?php echo $paciente['observaciones']; ?></textarea>
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
                <?php
                if (isset($_GET['status']) && $_GET['status'] == 'update') {
                ?>
                    <div class="col-xs-12" style="margin-bottom: 16px;">
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times-circle-o"></i></button>
                            <b>OK:</b> Registro actualizado correctamente.
                        </div>
                    </div>
                <?php    } ?>
            </div>
    </section>
</aside>