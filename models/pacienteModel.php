<?php
require_once 'models/configuracionModel.php';

class Patient extends Configuracion
{

    public function agregarPaciente(
        $nombre,
        $fecha,
        $edad,
        $sexo,
        $estado_civil,
        $direccion,
        $nombre_encargado,
        $telefono,
        $diagnostico,
        $med_admin,
        $medico,
        $telefono_med,
        $examenes_realizados,
        $convulsiona,
        $usa_protesis,
        $desc_protesis,
        $enfermedad_actual,
        $observaciones,
        $usuario_ingresa,
        $usuario_modifica
    ) {

        $pdo = parent::conexion();
        $stmt = $pdo->prepare("INSERT INTO clinica.paciente (idpaciente, nombre, fecha, edad, sexo, estado_civil, direccion, nombre_encargado,
        telefono, diagnostico, med_admin, medico, telefono_med, examenes_realizados, convulsiona, usa_protesis, desc_protesis,
        enfermedad_actual, observaciones, usuario_ingresa, fecha_ingreso, usuario_modifica, fecha_modifica, estado) 
        VALUES (NULL, :nombre, :fecha, :edad, :sexo, :estado_civil, :direccion, :nombre_encargado,
        :telefono, :diagnostico, :med_admin, :medico, :telefono_med, :examenes_realizados, :convulsiona, :usa_protesis, :desc_protesis,
        :enfermedad_actual, :observaciones, :usuario_ingresa, now(), :usuario_modifica, now(), 1)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':estado_civil', $estado_civil);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':nombre_encargado', $nombre_encargado);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':diagnostico', $diagnostico);
        $stmt->bindParam(':med_admin', $med_admin);
        $stmt->bindParam(':medico', $medico);
        $stmt->bindParam(':telefono_med', $telefono_med);
        $stmt->bindParam(':examenes_realizados', $examenes_realizados);
        $stmt->bindParam(':convulsiona', $convulsiona);
        $stmt->bindParam(':usa_protesis', $usa_protesis);
        $stmt->bindParam(':desc_protesis', $desc_protesis);
        $stmt->bindParam(':enfermedad_actual', $enfermedad_actual);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':usuario_ingresa', $usuario_ingresa);
        $stmt->bindParam(':usuario_modifica', $usuario_modifica);
        $insert = $stmt->execute();
        if ($insert == false) {
            print_r($stmt->errorInfo());
        }
        return $insert;

        //Para comprobar si hay un error al insertar
        //if ($insert == false){
        //print_r($stmt->errorInfo());
        // }
    }

    public function getIdPaciente()
    {
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("SELECT idpaciente FROM clinica.paciente ORDER BY idpaciente DESC LIMIT 1");
        $stmt->execute(); // Ejecuta la consulta

        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Obtiene el resultado como un arreglo asociativo
        if ($result === false) {
            print_r($stmt->errorInfo());
            return false; // Retorna falso si no se pudo obtener el resultado
        }

        $lastId = $result['idpaciente']; // Obtiene el último ID de paciente
        return $lastId;
    }


    public function agregarAntecedente(
        $cardiovascular,
        $pulmonares,
        $digestivos,
        $diabetes,
        $renales,
        $quirurgicos,
        $alergicos,
        $transfusiones,
        $medicamentos,
        $otros,
        $alcohol,
        $tabaquismo,
        $drogas,
        $inmunizaciones,
        $otros_2,
        $padre_vive,
        $padre_enferm,
        $madre_vive,
        $madre_enferm,
        $hermanos,
        $cant_hermanos,
        $enf_hermanos,
        $observaciones,
        $id_paciente
    ) {

        $pdo = parent::conexion();
        $stmt = $pdo->prepare("INSERT INTO clinica.antecedentes (idantecendente,cardiovascular, pulmonares, digestivos, diabetes, 
        renales, quirurgicos, alergicos, transfusiones, medicamentos, otros, alcohol, tabaquismo, drogas, inmunizaciones,
        otros_2, padre_vive, padre_enferm, madre_vive, madre_enferm, hermanos, cant_hermanos, enf_hermanos,
        observaciones_ant, id_paciente) 
        VALUES (NULL, :cardiovascular, :pulmonares, :digestivos, :diabetes, :renales, :quirurgicos, :alergicos, 
        :transfusiones, :medicamentos, :otros, :alcohol, :tabaquismo, :drogas, :inmunizaciones, :otros_2, :padre_vive, 
        :padre_enferm, :madre_vive, :madre_enferm, :hermanos, :cant_hermanos, :enf_hermanos, :observaciones, 
        :id_paciente)");
        $stmt->bindParam(':cardiovascular', $cardiovascular);
        $stmt->bindParam(':pulmonares', $pulmonares);
        $stmt->bindParam(':digestivos', $digestivos);
        $stmt->bindParam(':diabetes', $diabetes);
        $stmt->bindParam(':renales', $renales);
        $stmt->bindParam(':quirurgicos', $quirurgicos);
        $stmt->bindParam(':alergicos', $alergicos);
        $stmt->bindParam(':transfusiones', $transfusiones);
        $stmt->bindParam(':medicamentos', $medicamentos);
        $stmt->bindParam(':otros', $otros);
        $stmt->bindParam(':alcohol', $alcohol);
        $stmt->bindParam(':tabaquismo', $tabaquismo);
        $stmt->bindParam(':inmunizaciones', $inmunizaciones);
        $stmt->bindParam(':otros_2', $otros_2);
        $stmt->bindParam(':padre_vive', $padre_vive);
        $stmt->bindParam(':padre_enferm', $padre_enferm);
        $stmt->bindParam(':madre_vive', $madre_vive);
        $stmt->bindParam(':madre_enferm', $madre_enferm);
        $stmt->bindParam(':hermanos', $hermanos);
        $stmt->bindParam(':cant_hermanos', $cant_hermanos);
        $stmt->bindParam(':enf_hermanos', $enf_hermanos);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $stmt->bindParam(':drogas', $drogas);
        $insert = $stmt->execute();
        if ($insert == false) {
            print_r($stmt->errorInfo());
        }
        return $insert;
    }

    public function agregarEvaluaciones($dolor_donde, $dolor_irradiacion, $tipo_dolor, $escala_visual_dolor, $id_paciente)
    {

        $pdo = parent::conexion();
        $stmt = $pdo->prepare("INSERT INTO clinica.evaluaciones_fisioterapeutas (idevaluacion, dolor_donde, dolor_irradiacion,
tipo_dolor, escala_visual_dolor, id_paciente) 
VALUES (NULL, :dolor_donde, :dolor_irradiacion, :tipo_dolor, :escala_visual_dolor, :id_paciente)");
        $stmt->bindParam(':dolor_donde', $dolor_donde);
        $stmt->bindParam(':dolor_irradiacion', $dolor_irradiacion);
        $stmt->bindParam(':tipo_dolor', $tipo_dolor);
        $stmt->bindParam(':escala_visual_dolor', $escala_visual_dolor);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $insert = $stmt->execute();
        if ($insert == false) {
            print_r($stmt->errorInfo());
        }
        return $insert;
    }

    public function agregarTonoMuscular($tipo_tono, $limitacion_artic, $especificacion, $id_paciente)
    {

        $pdo = parent::conexion();
        $stmt = $pdo->prepare("INSERT INTO clinica.tono_muscular (idtono_muscular, tipo_tono,
    limitacion_artic, especificacion, id_paciente) 
    VALUES (NULL, :tipo_tono, :limitacion_artic, :especificacion, :id_paciente)");
        $stmt->bindParam(':tipo_tono', $tipo_tono);
        $stmt->bindParam(':limitacion_artic', $limitacion_artic);
        $stmt->bindParam(':especificacion', $especificacion);
        $stmt->bindParam(':id_paciente', $id_paciente);
        $insert = $stmt->execute();
        if ($insert == false) {
            print_r($stmt->errorInfo());
        }
        return $insert;
    }

    public function agregarEscalaDesarollo(
        $control_cuello,
        $prono_supino,
        $supino_prono,
        $tronco_superior,
        $tronco_inferior,
        $cuatro_puntos,
        $posicion_sedente,
        $posicion_hincado,
        $posicion_semihincado,
        $posicion_bidepestacion,
        $id_paciente
    ) {

        $pdo = parent::conexion();
        $stmt = $pdo->prepare("INSERT INTO clinica.escala_desarrollo (idescala, control_cuello, rotacion_prono_supino,
    rotacion_supino_prono, tronco_superior, tronco_inferior, cuatro_puntos, posicion_sedente, posicion_hincado,
    posicion_semihincado, posicion_bidepestacion, id_paciente) 
    VALUES (NULL, :control_cuello, :rotacion_prono_supino, :rotacion_supino_prono, :tronco_superior,
    :tronco_inferior, :cuatro_puntos, :posicion_sedente, :posicion_hincado, :posicion_semihincado,
    :posicion_bidepestacion, :id_paciente)");

        $stmt->bindParam(':control_cuello', $control_cuello);
        $stmt->bindParam(':rotacion_prono_supino', $prono_supino);
        $stmt->bindParam(':rotacion_supino_prono', $supino_prono);
        $stmt->bindParam(':tronco_superior', $tronco_superior);
        $stmt->bindParam(':tronco_inferior', $tronco_inferior);
        $stmt->bindParam(':cuatro_puntos', $cuatro_puntos);
        $stmt->bindParam(':posicion_sedente', $posicion_sedente);
        $stmt->bindParam(':posicion_hincado', $posicion_hincado);
        $stmt->bindParam(':posicion_semihincado', $posicion_semihincado);
        $stmt->bindParam(':posicion_bidepestacion', $posicion_bidepestacion);
        $stmt->bindParam(':id_paciente', $id_paciente);

        $insert = $stmt->execute();

        if ($insert == false) {
            print_r($stmt->errorInfo());
        }

        return $insert;
    }

    public function agregarAtencion(
        $localizacion,
        $fijacion,
        $seguimiento,
        $alcance,
        $manipulacion,
        $exploracion,
        $id_paciente
    ) {

        $pdo = parent::conexion();
        $stmt = $pdo->prepare("INSERT INTO clinica.atencion (idatencion, localizacion, fijacion, seguimiento,
    alcance, manipulacion, exploracion, id_paciente) 
    VALUES (NULL, :localizacion, :fijacion, :seguimiento, :alcance, :manipulacion, :exploracion, :id_paciente)");

        $stmt->bindParam(':localizacion', $localizacion);
        $stmt->bindParam(':fijacion', $fijacion);
        $stmt->bindParam(':seguimiento', $seguimiento);
        $stmt->bindParam(':alcance', $alcance);
        $stmt->bindParam(':manipulacion', $manipulacion);
        $stmt->bindParam(':exploracion', $exploracion);
        $stmt->bindParam(':id_paciente', $id_paciente);

        $insert = $stmt->execute();

        if ($insert == false) {
            print_r($stmt->errorInfo());
        }

        return $insert;
    }

    public function agregarDestrezasManuales(
        $sostiene_objeto,
        $suelta_objeto,
        $atrapa_objeto,
        $lanza_objeto,
        $realiza_nudo,
        $encaja,
        $id_paciente
    ) {

        $pdo = parent::conexion();
        $stmt = $pdo->prepare("INSERT INTO clinica.destrezas_manuales (iddestrezas, sostiene_objeto, suelta_objeto, atrapa_objeto,
    lanza_objeto, realiza_nudo, encaja, id_paciente) 
    VALUES (NULL, :sostiene_objeto, :suelta_objeto, :atrapa_objeto, :lanza_objeto, :realiza_nudo, :encaja, :id_paciente)");

        $stmt->bindParam(':sostiene_objeto', $sostiene_objeto);
        $stmt->bindParam(':suelta_objeto', $suelta_objeto);
        $stmt->bindParam(':atrapa_objeto', $atrapa_objeto);
        $stmt->bindParam(':lanza_objeto', $lanza_objeto);
        $stmt->bindParam(':realiza_nudo', $realiza_nudo);
        $stmt->bindParam(':encaja', $encaja);
        $stmt->bindParam(':id_paciente', $id_paciente);

        $insert = $stmt->execute();

        if ($insert == false) {
            print_r($stmt->errorInfo());
        }

        return $insert;
    }

    public function agregarActividadDiaria(
        $alimentacion,
        $higiene,
        $vestuario,
        $control_esfinteres,
        $orden_limpieza,
        $ocio_recreacion,
        $observaciones,
        $id_paciente
    ) {

        $pdo = parent::conexion();
        $stmt = $pdo->prepare("INSERT INTO clinica.actividad_diaria(idactividad_diaria, alimentacion, higiene, vestuario,
    control_esfinteres, orden_limpieza, ocio_recreacion, observaciones_act, id_paciente) 
    VALUES (NULL, :alimentacion, :higiene, 
    :vestuario, :control_esfinteres, :orden_limpieza, :ocio_recreacion, :observaciones, :id_paciente)");
        $stmt->bindParam(':alimentacion', $alimentacion);
        $stmt->bindParam(':higiene', $higiene);
        $stmt->bindParam(':vestuario', $vestuario);
        $stmt->bindParam(':control_esfinteres', $control_esfinteres);
        $stmt->bindParam(':orden_limpieza', $orden_limpieza);
        $stmt->bindParam(':ocio_recreacion', $ocio_recreacion);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':id_paciente', $id_paciente);

        $insert = $stmt->execute();

        if ($insert == false) {
            print_r($stmt->errorInfo());
        }

        return $insert;
    }

    public function agregarPostura($observaciones, $id_paciente)
    {

        $pdo = parent::conexion();
        $stmt = $pdo->prepare("INSERT INTO clinica.postura(idpostura, observaciones_post, id_paciente) 
    VALUES (NULL, :observaciones, :id_paciente)");
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':id_paciente', $id_paciente);

        $insert = $stmt->execute();

        if ($insert == false) {
            print_r($stmt->errorInfo());
        }

        return $insert;
    }


    public function agregarMarchaDesplazamiento($realiza_marcha, $base_sustentacion, $coordinacion, $equilibrio, $realiza_apoyo, $silla_ruedas, $utiliza_dispositivo, $cual_dispositivo, $id_paciente)
    {

        $pdo = parent::conexion();
        $stmt = $pdo->prepare("INSERT INTO clinica.marcha_desplazamiento(id_marcha, realiza_marcha, base_sustentacion, coordinacion,
    equilibrio, realiza_apoyo, silla_ruedas, utiliza_dispositivo, cual_dispositivo, id_paciente) 
    VALUES (NULL, :realiza_marcha, :base_sustentacion, :coordinacion, :equilibrio, :realiza_apoyo, :silla_ruedas, :utiliza_dispositivo, :cual_dispositivo, :id_paciente)");

        $stmt->bindParam(':realiza_marcha', $realiza_marcha);
        $stmt->bindParam(':base_sustentacion', $base_sustentacion);
        $stmt->bindParam(':coordinacion', $coordinacion);
        $stmt->bindParam(':equilibrio', $equilibrio);
        $stmt->bindParam(':realiza_apoyo', $realiza_apoyo);
        $stmt->bindParam(':silla_ruedas', $silla_ruedas);
        $stmt->bindParam(':utiliza_dispositivo', $utiliza_dispositivo);
        $stmt->bindParam(':cual_dispositivo', $cual_dispositivo);
        $stmt->bindParam(':id_paciente', $id_paciente);

        $insert = $stmt->execute();

        if ($insert == false) {
            print_r($stmt->errorInfo());
        }

        return $insert;
    }




    public function updatePaciente(
        $idItem,
        $nombres,
        $apellidos,
        $direccion,
        $direccionTrabajo,
        $lugarTrabajo,
        $ocupacion,
        $telefono,
        $fechaNacimiento,
        $dpi,
        $genero,
        $estadoCivil,
        $escolaridad,
        $tipoSangre,
        $conyugue,
        $responsable,
        $religion,
        $padre,
        $madre,
        $hermanos,
        $observaciones,
        $fechaModificacion,
        $usuarioModificacion,
        $imgPaciente
    ) {
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("call update_paciente(:idPaciente,:nombres,:apellidos,:direccion,:direccion_trabajo,:lugar_trabajo,
    :ocupacion,:telefono,:fecha_nacimiento,:dpi,:genero,:estado_civil,:escolaridad,:tipo_sangre,:conyugue,:religion,:padre,:madre,
    :hermanos,:observaciones,:responsable,:fecha_modificacion,:usuario_modificacion,:foto);");
        $stmt->bindParam(':idPaciente', $idItem);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':direccion_trabajo', $direccionTrabajo);
        $stmt->bindParam(':lugar_trabajo', $lugarTrabajo);
        $stmt->bindParam(':ocupacion', $ocupacion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fecha_nacimiento', $fechaNacimiento);
        $stmt->bindParam(':dpi', $dpi);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':estado_civil', $estadoCivil);
        $stmt->bindParam(':escolaridad', $escolaridad);
        $stmt->bindParam(':tipo_sangre', $tipoSangre);
        $stmt->bindParam(':conyugue', $conyugue);
        $stmt->bindParam(':responsable', $responsable);
        $stmt->bindParam(':religion', $religion);
        $stmt->bindParam(':padre', $padre);
        $stmt->bindParam(':madre', $madre);
        $stmt->bindParam(':hermanos', $hermanos);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':responsable', $responsable);
        $stmt->bindParam(':fecha_modificacion', $fechaModificacion);
        $stmt->bindParam(':usuario_modificacion', $usuarioModificacion);
        $update = $stmt->execute();
        if ($update) {
            //CAMBIOS
            require_once 'views/header.php';
            require_once 'views/pacientes.php';
            require_once 'views/footer.php';
            return $update;
        } else {
            //print_r($stmt->errorInfo());
            die();
        }

    }

    //funcion que sirve para llenar los datos del paciente en agregar-consulta
    public function getPacientePorId($idPaciente)
    {
        $pdo = parent::conexion();
        $paciente = array();
        $stmt = $pdo->prepare("select * from paciente where idpaciente= :idpaciente;");
        $stmt->bindParam(":idpaciente", $idPaciente);
        //$get = $stmt->execute() sirve para saber si se ejecutó el statement.
        $stmt->execute();
        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $paciente[] = $resultado;
        }
        if (count($paciente)) {
            return $paciente[0];
        } else {
            return $paciente;
        }

    }

    //funcion que trae los pacientes
    public function getPaciente($idEmpleado)
    {
        $pdo = parent::conexion();
        $cotizaciones = array();
        $stmt = $pdo->prepare("SELECT *
                                FROM paciente
                                WHERE estado = 1 
                                ORDER BY idpaciente DESC
                                LIMIT 0,100");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cotizaciones[] = $resultado;
        }
        return $cotizaciones;
    }


    //funcion para obtener el nombre del paciente
    public function getNombrePacientePorId($idPaciente)
    {
        $pdo = parent::conexion();
        $paciente = array();
        $stmt = $pdo->prepare("SELECT idpaciente, concat(nombres,' ',apellidos) nombres FROM mostrar_info_paciente  WHERE idpaciente= :idpaciente;");
        $stmt->bindParam(":idpaciente", $idPaciente);
        //$get = $stmt->execute() sirve para saber si se ejecutó el statement.
        $stmt->execute();
        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $paciente[] = $resultado;
        }
        if (count($paciente)) {
            return $paciente[0];
        } else {
            return $paciente;
        }

    }

    public function actualizarPaciente(
        $idpaciente,
        $nombre,
        $fecha,
        $edad,
        $sexo,
        $estado_civil,
        $direccion,
        $nombre_encargado,
        $telefono,
        $diagnostico,
        $med_admin,
        $medico,
        $telefono_med,
        $examenes_realizados,
        $convulsiona,
        $usa_protesis,
        $desc_protesis,
        $enfermedad_actual,
        $observaciones,
        $usuario_modifica
    ) {

        $pdo = parent::conexion();
        $stmt = $pdo->prepare("UPDATE clinica.paciente SET
            nombre = :nombre,
            fecha = :fecha,
            edad = :edad,
            sexo = :sexo,
            estado_civil = :estado_civil,
            direccion = :direccion,
            nombre_encargado = :nombre_encargado,
            telefono = :telefono,
            diagnostico = :diagnostico,
            med_admin = :med_admin,
            medico = :medico,
            telefono_med = :telefono_med,
            examenes_realizados = :examenes_realizados,
            convulsiona = :convulsiona,
            usa_protesis = :usa_protesis,
            desc_protesis = :desc_protesis,
            enfermedad_actual = :enfermedad_actual,
            observaciones = :observaciones,
            usuario_modifica = :usuario_modifica,
            fecha_modifica = NOW()
            WHERE idpaciente = :idpaciente");

        $stmt->bindParam(':idpaciente', $idpaciente);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':estado_civil', $estado_civil);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':nombre_encargado', $nombre_encargado);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':diagnostico', $diagnostico);
        $stmt->bindParam(':med_admin', $med_admin);
        $stmt->bindParam(':medico', $medico);
        $stmt->bindParam(':telefono_med', $telefono_med);
        $stmt->bindParam(':examenes_realizados', $examenes_realizados);
        $stmt->bindParam(':convulsiona', $convulsiona);
        $stmt->bindParam(':usa_protesis', $usa_protesis);
        $stmt->bindParam(':desc_protesis', $desc_protesis);
        $stmt->bindParam(':enfermedad_actual', $enfermedad_actual);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':usuario_modifica', $usuario_modifica);

        $update = $stmt->execute();

        if ($update == false) {
            print_r($stmt->errorInfo());
        }

        return $update;
    }

    public function ActualizarAntecedente(
        $cardiovascular,
        $pulmonares,
        $digestivos,
        $diabetes,
        $renales,
        $quirurgicos,
        $alergicos,
        $transfusiones,
        $medicamentos,
        $otros,
        $alcohol,
        $tabaquismo,
        $drogas,
        $inmunizaciones,
        $otros_2,
        $padre_vive,
        $padre_enferm,
        $madre_vive,
        $madre_enferm,
        $hermanos,
        $cant_hermanos,
        $enf_hermanos,
        $observaciones,
        $id_paciente
    ) {
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("UPDATE clinica.antecedentes SET
            cardiovascular = :cardiovascular, pulmonares = :pulmonares, digestivos = :digestivos, 
            diabetes = :diabetes, renales = :renales, quirurgicos = :quirurgicos, alergicos = :alergicos, 
            transfusiones = :transfusiones, medicamentos = :medicamentos, otros = :otros, alcohol = :alcohol, 
            tabaquismo = :tabaquismo, drogas = :drogas, inmunizaciones = :inmunizaciones, otros_2 = :otros_2, 
            padre_vive = :padre_vive, padre_enferm = :padre_enferm, madre_vive = :madre_vive, 
            madre_enferm = :madre_enferm, hermanos = :hermanos, cant_hermanos = :cant_hermanos, 
            enf_hermanos = :enf_hermanos, observaciones_ant = :observaciones
            WHERE id_paciente = :id_paciente"
        );

        $stmt->bindParam(':cardiovascular', $cardiovascular);
        $stmt->bindParam(':pulmonares', $pulmonares);
        $stmt->bindParam(':digestivos', $digestivos);
        $stmt->bindParam(':diabetes', $diabetes);
        $stmt->bindParam(':renales', $renales);
        $stmt->bindParam(':quirurgicos', $quirurgicos);
        $stmt->bindParam(':alergicos', $alergicos);
        $stmt->bindParam(':transfusiones', $transfusiones);
        $stmt->bindParam(':medicamentos', $medicamentos);
        $stmt->bindParam(':otros', $otros);
        $stmt->bindParam(':alcohol', $alcohol);
        $stmt->bindParam(':tabaquismo', $tabaquismo);
        $stmt->bindParam(':drogas', $drogas);
        $stmt->bindParam(':inmunizaciones', $inmunizaciones);
        $stmt->bindParam(':otros_2', $otros_2);
        $stmt->bindParam(':padre_vive', $padre_vive);
        $stmt->bindParam(':padre_enferm', $padre_enferm);
        $stmt->bindParam(':madre_vive', $madre_vive);
        $stmt->bindParam(':madre_enferm', $madre_enferm);
        $stmt->bindParam(':hermanos', $hermanos);
        $stmt->bindParam(':cant_hermanos', $cant_hermanos);
        $stmt->bindParam(':enf_hermanos', $enf_hermanos);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':id_paciente', $id_paciente);

        $update = $stmt->execute();

        if ($update == false) {
            print_r($stmt->errorInfo());
        }

        return $update;
    }

    public function ActualizarEvaluaciones($dolor_donde, $dolor_irradiacion, $tipo_dolor, $escala_visual_dolor, $id_paciente)
    {
        $pdo = parent::conexion();
        $stmt = $pdo->prepare("UPDATE clinica.evaluaciones_fisioterapeutas SET
        dolor_donde = :dolor_donde,
        dolor_irradiacion = :dolor_irradiacion,
        tipo_dolor = :tipo_dolor,
        escala_visual_dolor = :escala_visual_dolor
        WHERE id_paciente = :id_paciente"
        );

        $stmt->bindParam(':dolor_donde', $dolor_donde);
        $stmt->bindParam(':dolor_irradiacion', $dolor_irradiacion);
        $stmt->bindParam(':tipo_dolor', $tipo_dolor);
        $stmt->bindParam(':escala_visual_dolor', $escala_visual_dolor);
        $stmt->bindParam(':id_paciente', $id_paciente);

        $update = $stmt->execute();

        if ($update == false) {
            print_r($stmt->errorInfo());
        }

        return $update;
    }

    public function ActualizarTonoMuscular($tipo_tono, $limitacion_artic, $especificacion, $id_paciente)
{
    $pdo = parent::conexion();
    $stmt = $pdo->prepare("UPDATE clinica.tono_muscular SET
        tipo_tono = :tipo_tono,
        limitacion_artic = :limitacion_artic,
        especificacion = :especificacion
        WHERE id_paciente = :id_paciente"
    );

    $stmt->bindParam(':tipo_tono', $tipo_tono);
    $stmt->bindParam(':limitacion_artic', $limitacion_artic);
    $stmt->bindParam(':especificacion', $especificacion);
    $stmt->bindParam(':id_paciente', $id_paciente);

    $update = $stmt->execute();

    if ($update == false) {
        print_r($stmt->errorInfo());
    }

    return $update;
}

public function ActualizarEscalaDesarrollo(
    $control_cuello, $prono_supino, $supino_prono, $tronco_superior, $tronco_inferior, $cuatro_puntos, 
    $posicion_sedente, $posicion_hincado, $posicion_semihincado, $posicion_bidepestacion, $id_paciente
) {
    $pdo = parent::conexion();
    $stmt = $pdo->prepare("UPDATE clinica.escala_desarrollo SET
        control_cuello = :control_cuello,
        rotacion_prono_supino = :prono_supino,
        rotacion_supino_prono = :supino_prono,
        tronco_superior = :tronco_superior,
        tronco_inferior = :tronco_inferior,
        cuatro_puntos = :cuatro_puntos,
        posicion_sedente = :posicion_sedente,
        posicion_hincado = :posicion_hincado,
        posicion_semihincado = :posicion_semihincado,
        posicion_bidepestacion = :posicion_bidepestacion
        WHERE id_paciente = :id_paciente"
    );

    $stmt->bindParam(':control_cuello', $control_cuello);
    $stmt->bindParam(':prono_supino', $prono_supino);
    $stmt->bindParam(':supino_prono', $supino_prono);
    $stmt->bindParam(':tronco_superior', $tronco_superior);
    $stmt->bindParam(':tronco_inferior', $tronco_inferior);
    $stmt->bindParam(':cuatro_puntos', $cuatro_puntos);
    $stmt->bindParam(':posicion_sedente', $posicion_sedente);
    $stmt->bindParam(':posicion_hincado', $posicion_hincado);
    $stmt->bindParam(':posicion_semihincado', $posicion_semihincado);
    $stmt->bindParam(':posicion_bidepestacion', $posicion_bidepestacion);
    $stmt->bindParam(':id_paciente', $id_paciente);

    $update = $stmt->execute();

    if ($update == false) {
        print_r($stmt->errorInfo());
    }

    return $update;
}

public function ActualizarAtencion(
    $localizacion, $fijacion, $seguimiento, $alcance, $manipulacion, $exploracion, $id_paciente
) {
    $pdo = parent::conexion();
    $stmt = $pdo->prepare("UPDATE clinica.atencion SET
        localizacion = :localizacion,
        fijacion = :fijacion,
        seguimiento = :seguimiento,
        alcance = :alcance,
        manipulacion = :manipulacion,
        exploracion = :exploracion
        WHERE id_paciente = :id_paciente"
    );

    $stmt->bindParam(':localizacion', $localizacion);
    $stmt->bindParam(':fijacion', $fijacion);
    $stmt->bindParam(':seguimiento', $seguimiento);
    $stmt->bindParam(':alcance', $alcance);
    $stmt->bindParam(':manipulacion', $manipulacion);
    $stmt->bindParam(':exploracion', $exploracion);
    $stmt->bindParam(':id_paciente', $id_paciente);

    $update = $stmt->execute();

    if ($update == false) {
        print_r($stmt->errorInfo());
    }

    return $update;
}

public function ActualizarDestrezasManuales(
    $sostiene_objeto, $suelta_objeto, $atrapa_objeto, $lanza_objeto, $realiza_nudo, $encaja, $id_paciente
) {
    $pdo = parent::conexion();
    $stmt = $pdo->prepare("UPDATE clinica.destrezas_manuales SET
        sostiene_objeto = :sostiene_objeto,
        suelta_objeto = :suelta_objeto,
        atrapa_objeto = :atrapa_objeto,
        lanza_objeto = :lanza_objeto,
        realiza_nudo = :realiza_nudo,
        encaja = :encaja
        WHERE id_paciente = :id_paciente"
    );

    $stmt->bindParam(':sostiene_objeto', $sostiene_objeto);
    $stmt->bindParam(':suelta_objeto', $suelta_objeto);
    $stmt->bindParam(':atrapa_objeto', $atrapa_objeto);
    $stmt->bindParam(':lanza_objeto', $lanza_objeto);
    $stmt->bindParam(':realiza_nudo', $realiza_nudo);
    $stmt->bindParam(':encaja', $encaja);
    $stmt->bindParam(':id_paciente', $id_paciente);

    $update = $stmt->execute();

    if ($update == false) {
        print_r($stmt->errorInfo());
    }

    return $update;
}

public function ActualizarActividadDiaria(
    $alimentacion, $higiene, $vestuario, $control_esfinteres, $orden_limpieza, $ocio_recreacion, $observaciones, $id_paciente
) {
    $pdo = parent::conexion();
    $stmt = $pdo->prepare("UPDATE clinica.actividad_diaria SET
        alimentacion = :alimentacion,
        higiene = :higiene,
        vestuario = :vestuario,
        control_esfinteres = :control_esfinteres,
        orden_limpieza = :orden_limpieza,
        ocio_recreacion = :ocio_recreacion,
        observaciones_act = :observaciones
        WHERE id_paciente = :id_paciente"
    );

    $stmt->bindParam(':alimentacion', $alimentacion);
    $stmt->bindParam(':higiene', $higiene);
    $stmt->bindParam(':vestuario', $vestuario);
    $stmt->bindParam(':control_esfinteres', $control_esfinteres);
    $stmt->bindParam(':orden_limpieza', $orden_limpieza);
    $stmt->bindParam(':ocio_recreacion', $ocio_recreacion);
    $stmt->bindParam(':observaciones', $observaciones);
    $stmt->bindParam(':id_paciente', $id_paciente);

    $update = $stmt->execute();

    if ($update == false) {
        print_r($stmt->errorInfo());
    }

    return $update;
}

public function ActualizarPostura($observaciones, $id_paciente)
{
    $pdo = parent::conexion();
    $stmt = $pdo->prepare("UPDATE clinica.postura SET
        observaciones_post = :observaciones
        WHERE id_paciente = :id_paciente"
    );

    $stmt->bindParam(':observaciones', $observaciones);
    $stmt->bindParam(':id_paciente', $id_paciente);

    $update = $stmt->execute();

    if ($update == false) {
        print_r($stmt->errorInfo());
    }

    return $update;
}

public function ActualizarMarchaDesplazamiento(
    $realiza_marcha, $base_sustentacion, $coordinacion, $equilibrio, $realiza_apoyo, $silla_ruedas, $utiliza_dispositivo, 
    $cual_dispositivo, $id_paciente
) {
    $pdo = parent::conexion();
    $stmt = $pdo->prepare("UPDATE clinica.marcha_desplazamiento SET
        realiza_marcha = :realiza_marcha,
        base_sustentacion = :base_sustentacion,
        coordinacion = :coordinacion,
        equilibrio = :equilibrio,
        realiza_apoyo = :realiza_apoyo,
        silla_ruedas = :silla_ruedas,
        utiliza_dispositivo = :utiliza_dispositivo,
        cual_dispositivo = :cual_dispositivo
        WHERE id_paciente = :id_paciente"
    );

    $stmt->bindParam(':realiza_marcha', $realiza_marcha);
    $stmt->bindParam(':base_sustentacion', $base_sustentacion);
    $stmt->bindParam(':coordinacion', $coordinacion);
    $stmt->bindParam(':equilibrio', $equilibrio);
    $stmt->bindParam(':realiza_apoyo', $realiza_apoyo);
    $stmt->bindParam(':silla_ruedas', $silla_ruedas);
    $stmt->bindParam(':utiliza_dispositivo', $utiliza_dispositivo);
    $stmt->bindParam(':cual_dispositivo', $cual_dispositivo);
    $stmt->bindParam(':id_paciente', $id_paciente);

    $update = $stmt->execute();

    if ($update == false) {
        print_r($stmt->errorInfo());
    }

    return $update;
}

public function anularPaciente($idConsulta){
    $pdo = parent::conexion();
    try {
        $stmt = $pdo->prepare("UPDATE clinica.paciente SET estado = 0 WHERE idpaciente = :idconsulta");
        $stmt->bindParam(':idconsulta', $idConsulta);
        
        $update = $stmt->execute();
        
        if ($update == false) {
            // Error en la actualización
            return false;
        }
        
        // Éxito en la actualización
        return true;
    } catch (PDOException $e) {
        // Captura la excepción y devuelve false en caso de error
        return false;
    }
}

}