<?php
// CONEXION A BASE DE DATOS
$conexion = mysqli_connect('127.0.0.1:3307', 'root', 'SAMI_zayn2802', 'clinica');
require_once 'dbconfig.php';

// Incluir la biblioteca TCPDF
include('library/tcpdf.php');

// Crear un objeto TCPDF
$pdf = new TCPDF('P', 'mm', 'A4');


$idItem = $_GET['idItem'];

// Sentencia SQL para obtener los datos de las consultas
$sql = "SELECT DISTINCT c.*, a.*, ef.*, tm.*, ed.*, aten.*, dm.*, ad.*, pos.*, md.*
FROM clinica.paciente c
INNER JOIN clinica.antecedentes a ON c.idpaciente = a.id_paciente
INNER JOIN clinica.evaluaciones_fisioterapeutas ef ON c.idpaciente = ef.id_paciente
INNER JOIN clinica.tono_muscular tm ON c.idpaciente = tm.id_paciente
INNER JOIN clinica.escala_desarrollo ed ON c.idpaciente = ed.id_paciente
INNER JOIN clinica.atencion aten ON c.idpaciente = aten.id_paciente
INNER JOIN clinica.destrezas_manuales dm ON c.idpaciente = dm.id_paciente
INNER JOIN clinica.actividad_diaria ad ON c.idpaciente = ad.id_paciente
INNER JOIN clinica.postura pos ON c.idpaciente = pos.id_paciente
INNER JOIN clinica.marcha_desplazamiento md ON c.idpaciente = md.id_paciente
WHERE c.idpaciente = $idItem";

$result = mysqli_query($conexion, $sql);

// Verificar si la consulta fue exitosa
if ($result) {
    // Fetch the row from the result set
    $row = mysqli_fetch_assoc($result);

    // Eliminar el encabezado y el pie de página predeterminados
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Agregar una página
    $pdf->AddPage();

    // Agregar contenido (lista de consultas)

    // Logo a la izquierda
    $pdf->Image('../img/logo_fundal.png', 15, 10, 30);

    $pdf->SetAlpha(0.2);
    //$pdf->Image('../img/marca_agua.png', 50, 100, 120, 0, 'PNG'); MARCA DE AGUA
    $pdf->SetAlpha(1);

    // Título alineado a la derecha
    $pdf->SetFont('Helvetica', '', 20);
    $pdf->Cell(0, 10, "HISTORIA CLÍNICA", 0, 1, 'C');
    $pdf->Cell(0, 20, "FISIOTERAPIA EXTERNA", 0, 1, 'C');

    $pdf->SetFont('Helvetica', '', 12);
    $pdf->SetX(150);
    // Plantilla para la tabla
    $fechaFormateada = date('d/m/Y', strtotime($row['fecha_ingreso']));
    $fechadeNacimiento = date('d/m/Y', strtotime($row['fecha']));
    $sexoTexto = ($row['sexo'] == 1) ? 'Masculino' : 'Femenino';
    $estadoCivilNumero = $row['estado_civil'];
    switch ($estadoCivilNumero) {
        case 1:
            $estadoCivilTexto = 'Soltero (a)';
            break;
        case 2:
            $estadoCivilTexto = 'Casado (a)';
            break;
        case 3:
            $estadoCivilTexto = 'Viudo (a)';
            break;
        case 4:
            $estadoCivilTexto = 'Divorciado (a)';
            break;
        case 5:
            $estadoCivilTexto = 'Unido (a)';
            break;
        case 6:
            $estadoCivilTexto = 'Otro (a)';
            break;
        default:
            $estadoCivilTexto = 'Desconocido';
    }
    $convulsiona = ($row['convulsiona'] == 1) ? 'Sí' : 'No';
    $usa_protesis = ($row['usa_protesis'] == 1) ? 'Sí' : 'No';
    $padre_vive = ($row['padre_vive'] == 1) ? 'Sí' : 'No';
    $madre_vive = ($row['madre_vive'] == 1) ? 'Sí' : 'No';


    $html = "
    <label style='margin-bottom: 0;'>Fecha: $fechaFormateada</label>
    ";

    // Agregar la primera parte de la tabla al PDF
    $pdf->WriteHTML($html);

    // Establecer un nuevo valor para SetX antes de agregar el segundo bloque
    $pdf->SetX(50);

    $html = "
    <br> 
    <label style='margin-bottom: 0;'><strong>Nombre del Usuario:</strong> {$row['nombre']}</label>
    <br>
    <br>
    <label style='margin-bottom: 0;'><strong>Fecha de Nacimiento:</strong> $fechadeNacimiento</label>
    <label style='margin-bottom: 0;'>   <strong>Edad:</strong> {$row['edad']} años</label>
    <label style='margin-bottom: 0;'>   <strong>Sexo:</strong> $sexoTexto</label>
    <br>
    <br>
    <label style='margin-bottom: 0;'><strong>Estado Civil:</strong> $estadoCivilTexto</label>
    <label style='margin-bottom: 0;'>   <strong>Direccion:</strong> {$row['direccion']}</label>
    <br>
    <br>
    <label style='margin-bottom: 0;'><strong>Nombre del Encargado:</strong> {$row['nombre_encargado']}</label>
    <br>
    <br>
    <label style='margin-bottom: 0;'><strong>Diagnóstico:</strong> {$row['diagnostico']}</label>
    <br>
    <br>
    <label style='margin-bottom: 0;'><strong>Medicamentos Administrados Actualmente:</strong> 
    {$row['med_admin']}</label>
    <br>
    <br>
    <label style='margin-bottom: 0;'><strong>CUADRO CLÍNICO:</strong></label>
    <br>
    <label style='margin-bottom: 0;'><strong>Médico Tratante:</strong> {$row['medico']}</label>
    <label style='margin-bottom: 0;'>   <strong>Teléfono:</strong> {$row['telefono_med']}</label>
    <hr style='border-top: 1px solid #000; margin: 20%;'>
    <br>
    <br>
    <label style='margin-top: 10px;'><strong>Exámenes de gabinete realizados:</strong> {$row['examenes_realizados']}</label>
    <br>
    <br>
    <label style='margin-top: 10px;'><strong>¿Convulsiona?:</strong> $convulsiona</label>
    <br>
    <label style='margin-top: 10px;'><strong>¿Usa algún tipo de prótesis?:</strong> $usa_protesis</label>
    <br>
    <label style='margin-top: 10px;'><strong>¿Cuál prótesis?:</strong> {$row['desc_protesis']}</label>


    
    ";


    // Agregar la segunda parte de0 la tabla al PDF
    $pdf->WriteHTML($html);
    // Saltar a otra página
    $pdf->AddPage();

    // Logo a la izquierda
    $pdf->Image('../img/logo_fundal.png', 15, 10, 30);

    // Establecer el contenido de la tabla
    $html2 = "
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <table style='width: 100%; border-collapse: collapse;'>
        <tr style='background-color: #888; color: #fff; text-align: center;'>
            <th style='border: 1px solid #888; padding: 5px;'>HISTORIA DE LA ENFERMEDAD ACTUAL</th>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 5px;'>" . $row['enfermedad_actual'] . "</td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <label style='margin-top: 10px;'><strong>OBSERVACIONES:</strong>
    {$row['observaciones']}</label>

    <style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th, td {
        border: 1px solid #888;
        padding: 8px;
    }
    table tr th {
        border: 1px solid #888;
        color: #000;
        font-weight: bold;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>
";

    // Agregar la tabla al PDF
    $pdf->writeHTML($html2);

    $pdf->AddPage();

    // Logo a la izquierda
    $pdf->Image('../img/logo_fundal.png', 15, 10, 30);

    $html3 = "
    <br>
    <br>
    <br>
    <br>
    <br>
    <h2 style='text-align: center; margin: auto;'>ANTECEDENTES</h2>
    <table style='width: 100%; border-collapse: collapse; margin-top: 20px;'>
        <tr class='center-text'>
            <th class='center-text-title' style='border: 1px solid #888; padding: 8px;'><strong>Personales Patológicos</strong></th>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Cardiovascular:</strong> {$row['cardiovascular']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Pulmonares:</strong> {$row['pulmonares']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Digestivos:</strong> {$row['digestivos']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Diabetes:</strong> {$row['diabetes']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Renales:</strong> {$row['renales']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Quirúrgicos:</strong> {$row['quirurgicos']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Alergicos:</strong> {$row['alergicos']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Transfusiones:</strong> {$row['transfusiones']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Medicamentos:</strong> {$row['medicamentos']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Otros:</strong> {$row['otros']}</td>
        </tr>
        <tr class='center-text'>
            <th class='center-text-title' style='border: 1px solid #888; padding: 8px;'><strong>Personales No Patológicos</strong></th>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Alcohol:</strong> {$row['alcohol']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Tabaquismo:</strong> {$row['tabaquismo']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Inmunizaciones:</strong> {$row['inmunizaciones']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Otros:</strong> {$row['otros_2']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>¿Padre vive?:</strong> $padre_vive</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Enfermedades que padece:</strong> {$row['padre_enferm']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>¿Madre vive?:</strong> $madre_vive</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Enfermedades que padece:</strong> {$row['madre_enferm']}</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>¿Cuántos hermanos tiene?:</strong> {$row['hermanos']} hermano(s)</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>¿Cuántos viven?:</strong> {$row['cant_hermanos']} hermano(s)</td>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'><strong>Enfermedades que padecen:</strong> {$row['enf_hermanos']}</td>
        </tr>
    </table>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th{ 
            text-align: center;
        }
        table tr th {
            border: 1px solid #888;
            color: #000;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr, td{
            border: 1px solid #888;
            padding: 8px;
        }
        
    </style>
    ";


    // Agregar la tabla al PDF
    $pdf->writeHTML($html3);

    $pdf->AddPage();

    // Logo a la izquierda
    $pdf->Image('../img/logo_fundal.png', 15, 10, 30);
    $escalaVisualDolor = $row['escala_visual_dolor'];
    $maxEscala = 10;
    $visualizacion = str_repeat('_ ', $maxEscala);
    $visualizacion[$escalaVisualDolor * 2] = 'X';

    $html4 = "
    <br>
    <br>
    <br>
    <br>
    <br>
    <h2 style='text-align: center; margin: auto;'>EVALUACIONES FISIOTERAPÉUTICAS</h2>
    <table style='width: 100%; border-collapse: collapse; margin-top: 20px;'>
        <tr class='center-text'>
            <th class='center-text-title' style='border: 1px solid #888; padding: 8px;'><strong>¿Dónde se localiza el dolor?</strong></th>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'>{$row['dolor_donde']}</td>
        </tr>
        <tr class='center-text'>
            <th class='center-text-title' style='border: 1px solid #888; padding: 8px;'><strong>Irradiación del dolor:</strong></th>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'>{$row['dolor_irradiacion']}</td>
        </tr>
        <tr class='center-text'>
            <th class='center-text-title' style='border: 1px solid #888; padding: 8px;'><strong>Tipo de dolor:</strong></th>
        </tr>
        <tr>
            <td style='border: 1px solid #888; padding: 8px;'>{$row['tipo_dolor']}</td>
        </tr>        
    </table>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th{ 
            text-align: center;
        }
        table tr th {
            border: 1px solid #888;
            color: #000;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr, td{
            border: 1px solid #888;
            padding: 8px;
        }
        
    </style>
    ";

    // Agregar la tabla al PDF
    $pdf->writeHTML($html4, true, false, false, false, '');

    // Definir la tabla
    $pdf->SetFont('helvetica', '', 12);
    $pdf->SetFillColor(200, 220, 255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0, 0, 0);

    // Columnas de la tabla
    $columnWidth = 17.2;
    $columnHeight = 5;

    $pdf->Cell($columnWidth * 11, $columnHeight, 'ESCALA VISUAL DE DOLOR', 1, 1, 'C', 1);

    // Fila 2
    $pdf->Cell($columnWidth * 3, $columnHeight * 3, 'Leve', 1, 0, 'C', 1);
    $pdf->Cell($columnWidth * 5, $columnHeight * 3, 'Moderado', 1, 0, 'C', 1);
    $pdf->Cell($columnWidth * 3, $columnHeight * 3, 'Intenso', 1, 1, 'C', 1);

    // Fila 3
    for ($i = 0; $i <= 10; $i++) {
        $pdf->Cell($columnWidth, $columnHeight, $i, 1, 0, 'C', 1);
    }
    $pdf->Ln();
    $pdf->SetFillColor(255, 255, 255);
    // Fila 4 (marcar X)
    for ($i = 0; $i <= 10; $i++) {
        if ($row['escala_visual_dolor'] == $i) {
            $pdf->Cell($columnWidth, $columnHeight * 3, 'X', 1, 0, 'C', 1);
        } else {
            $pdf->Cell($columnWidth, $columnHeight * 3, '', 1, 0, 'C', 1);
        }

    }

    $pdf->AddPage();
    $pdf->Image('../img/logo_fundal.png', 15, 10, 30);
    $tipo_tono = $row['tipo_tono'];

    $html5 = "
    <br>
    <br>
    <br>
    <br>
    <br>
    <h2 style='text-align: center; margin: auto;'>TONO MUSCULAR</h2>
    ";


    $pdf->writeHTML($html5, true, false, false, false, '');

    $pdf->Cell(40, 8, 'Espástico', 1, 0, 'C');
    $pdf->Cell(40, 8, ($tipo_tono == 1) ? 'X' : '', 1, 0, 'C');
    $pdf->Cell(40, 8, 'Hipotónico', 1, 0, 'C');
    $pdf->Cell(40, 8, ($tipo_tono == 2) ? 'X' : '', 1, 1, 'C'); // Nuevo renglón

    $pdf->Cell(40, 8, 'Atetósico', 1, 0, 'C');
    $pdf->Cell(40, 8, ($tipo_tono == 3) ? 'X' : '', 1, 0, 'C');
    $pdf->Cell(40, 8, 'Fluctuante', 1, 0, 'C');
    $pdf->Cell(40, 8, ($tipo_tono == 4) ? 'X' : '', 1, 1, 'C'); // Nuevo renglón

    // Añadir un salto de línea
    $pdf->Ln(1);

    // Pregunta sobre limitación en amplitudes articulares
    // $pdf->Cell(0, 10, 'Presenta limitación en amplitudes articulares: ' . ($row['limitacion_artic'] == 1 ? 'Sí' : 'No'), 0, 1);

    $pdf->Ln(1);
    // Agregar "Especifique:"
    $pdf->SetFont('helvetica', 'B', 12); // Establecer la fuente en negrita
    $pdf->Cell(0, 10, 'Especifique: ', 0, 1);
    $pdf->SetFont('helvetica', '', 12); // Restaurar la fuente a normal

    // Mostrar el texto de $row['especificacion']
    // $pdf->MultiCell(0, 10, $row['especificacion'], 0, 1);

    $html5_5 = "
    <br>
    <h2 style='text-align: center; margin: auto;'>ESCALA DE DESARROLLO</h2>
    ";

    $pdf->writeHTML($html5_5, true, false, false, false, '');

    // Crear otra tabla
    $pdf->Cell(80, 6, 'Escala', 1, 0, 'C');
    $pdf->Cell(30, 6, 'Si', 1, 0, 'C');
    $pdf->Cell(30, 6, 'No', 1, 0, 'C');
    $pdf->Cell(30, 6, 'Inicia', 1, 0, 'C');
    $pdf->Ln(); // Salto de línea

    // Datos para las 11 filas
$escalas = array(
    'Control de Cuello',
    'Rotación de prono a supino',
    'Rotación de supino a prono',
    'Control de tronco superior',
    'Control de tronco inferior',
    'Posición de cuatro puntos',
    'Adopta posición sedente',
    'Adopta posición hincado',
    'Adopta posición de semi-hincado',
    'Adopta posición de bidepestación'
);

$valores = array(
    $row['control_cuello'],
    $row['rotacion_prono_supino'],
    $row['rotacion_supino_prono'],
    $row['tronco_superior'],
    $row['tronco_inferior'],
    $row['cuatro_puntos'],
    $row['posicion_sedente'],
    $row['posicion_hincado'],
    $row['posicion_semihincado'],
    $row['posicion_bidepestacion']
);

// Agregar filas manualmente con títulos en la primera columna
for ($i = 0; $i < count($escalas); $i++) {
    $pdf->Cell(80, 6, $escalas[$i], 1);
    $pdf->Cell(30, 6, ($valores[$i] == 1) ? 'X' : '', 1, 0, 'C');
    $pdf->Cell(30, 6, ($valores[$i] == 2) ? 'X' : '', 1, 0, 'C');
    $pdf->Cell(30, 6, ($valores[$i] == 3) ? 'X' : '', 1, 0, 'C');
    $pdf->Ln(); // Salto de línea
}

$html5_6 = "
<br>
<h2 style='text-align: center; margin: auto;'>ATENCION</h2>
";

$pdf->writeHTML($html5_6, true, false, false, false, '');
$pdf->Cell(0, 10, 'Mediante estímulo visual, auditivo o táctil', 0, 1);
// Crear otra tabla
$pdf->Cell(80, 6, '', 1, 0, 'C');
$pdf->Cell(30, 6, 'Existe', 1, 0, 'C');
$pdf->Cell(30, 6, 'No existe', 1, 0, 'C');
$pdf->Ln(); // Salto de línea

// Datos para las 11 filas
$escalas = array(
'Localizacion',
'Fijación',
'Seguimiento',
'Alcance',
'Manipulación',
'Exploración'
);

$valores = array(
$row['localizacion'],
$row['fijacion'],
$row['seguimiento'],
$row['alcance'],
$row['manipulacion'],
$row['exploracion']
);

// Agregar filas manualmente con títulos en la primera columna
for ($i = 0; $i < count($escalas); $i++) {
$pdf->Cell(80, 6, $escalas[$i], 1);
$pdf->Cell(30, 6, ($valores[$i] == 1) ? 'X' : '', 1, 0, 'C');
$pdf->Cell(30, 6, ($valores[$i] == 0) ? 'X' : '', 1, 0, 'C');
$pdf->Ln(); // Salto de línea
}

$pdf->AddPage();
$pdf->Image('../img/logo_fundal.png', 15, 10, 30);
$pdf->Image('../img/postura2.png', 15, 180, 100);
$pdf->Image('../img/silueta.jpg', 130, 182, 50);
$sostiene_objeto = $row['sostiene_objeto'];
$suelta_objeto = $row['suelta_objeto'];
$atrapa_objeto = $row['atrapa_objeto'];
$lanza_objeto = $row['lanza_objeto'];
$realiza_nudo = $row['realiza_nudo'];
$encaja = $row['encaja'];

$html6 = "
<br>
<br>
<br>
<br>
<br>
<h2 style='text-align: center; margin: auto;'>DESTREZAS MANUALES</h2>
";


$pdf->writeHTML($html6, true, false, false, false, '');

$pdf->Cell(40, 8, '¿Sostiene objeto?', 0, 0, 'C');
$pdf->Cell(40, 8, ($sostiene_objeto == 1) ? 'Sí' : 'No', 0, 0, 'C');
$pdf->Cell(40, 8, '¿Suelta objeto?', 0, 0, 'C');
$pdf->Cell(40, 8, ($suelta_objeto == 1) ? 'Sí' : 'No', 0, 1, 'C'); // Nuevo renglón

$pdf->Cell(40, 8, '¿Atrapa objeto?', 0, 0, 'C');
$pdf->Cell(40, 8, ($atrapa_objeto == 1) ? 'Sí' : 'No', 0, 0, 'C');
$pdf->Cell(40, 8, '¿Lanza objeto?', 0, 0, 'C');
$pdf->Cell(40, 8, ($lanza_objeto == 1) ? 'Sí' : 'No', 0, 1, 'C'); // Nuevo renglón

$pdf->Cell(40, 8, '¿Realiza nudo?', 0, 0, 'C');
$pdf->Cell(40, 8, ($realiza_nudo == 1) ? 'Sí' : 'No', 0, 0, 'C');
$pdf->Cell(40, 8, '¿Encaja?', 0, 0, 'C');
$pdf->Cell(40, 8, ($encaja == 1) ? 'Sí' : 'No', 0, 1, 'C'); // Nuevo renglón

$html6_8 = "
<br>
<h2 style='text-align: center; margin: auto;'>ACTIVIDADES DE LA VIDA DIARIA (AVD)</h2>
";

$pdf->writeHTML($html6_8, true, false, false, false, '');
$pdf->Cell(0, 10, 'Entrevista a padre/madre/encargado', 0, 1);

    // Crear otra tabla
    $pdf->Cell(80, 6, 'Actividad', 1, 0, 'C');
    $pdf->Cell(30, 6, 'I', 1, 0, 'C');
    $pdf->Cell(30, 6, 'SI', 1, 0, 'C');
    $pdf->Cell(30, 6, 'D', 1, 0, 'C');
    $pdf->Ln(); // Salto de línea

    // Datos para las 11 filas
$escalas = array(
    'Alimentación',
    'Higiene',
    'Vestuario',
    'Control de esfínteres',
    'Orden y limpieza',
    'Ocio y recreación'
);

$valores = array(
    $row['alimentacion'],
    $row['higiene'],
    $row['vestuario'],
    $row['control_esfinteres'],
    $row['orden_limpieza'],
    $row['ocio_recreacion']
);

// Agregar filas manualmente con títulos en la primera columna
for ($i = 0; $i < count($escalas); $i++) {
    $pdf->Cell(80, 6, $escalas[$i], 1);
    $pdf->Cell(30, 6, ($valores[$i] == 1) ? 'X' : '', 1, 0, 'C');
    $pdf->Cell(30, 6, ($valores[$i] == 2) ? 'X' : '', 1, 0, 'C');
    $pdf->Cell(30, 6, ($valores[$i] == 3) ? 'X' : '', 1, 0, 'C');
    $pdf->Ln(); // Salto de línea
}

$pdf->Cell(0, 10, 'Observaciones:', 0, 1);
$pdf->MultiCell(0, 5, $row['observaciones_act'], 0, 1);

$html6_9 = "
<br>
<h2 style='text-align: center; margin: auto;'>POSTURA</h2>
";

$pdf->writeHTML($html6_9, true, false, false, false, '');
$pdf->SetY(252);
$pdf->Cell(0, 10, 'OBSERVACIONES:', 0, 1);
$pdf->MultiCell(0, 5, $row['observaciones_post'], 0, 1);

$pdf->AddPage();
$pdf->Image('../img/logo_fundal.png', 15, 10, 30);

$html7_10 = "
<br>
<br>
<br>
<br>
<br>
<br>
<h2 style='text-align: center; margin: auto;'>MARCHA Y/O DESPLAZAMIENTO</h2>
";

$pdf->writeHTML($html7_10, true, false, false, false, '');

$pdf->Cell(0, 10, 'Evaluación que indicará el proceso de la marcha.', 0, 1);
// Crear otra tabla
$pdf->Cell(80, 6, 'Evaluación', 1, 0, 'C');
$pdf->Cell(30, 6, 'Resultado', 1, 0, 'C');
$pdf->Ln(); // Salto de línea

// Datos para las 11 filas
$escalas = array(
'Realiza la marcha:',
'Base de sustentación:',
'Usa silla de ruedas:',
'Lo realiza con apoyo:',
'Equilibrio:',
'Coordinación:',
'¿Utiliza dispositivo?:'
);

$valores = array(
    ($row['realiza_marcha'] == 1) ? 'Si' : 'No',
    ($row['base_sustentacion'] == 1) ? 'Amplia' : (($row['base_sustentacion'] == 2) ? 'Disminuida' : (($row['base_sustentacion'] == 3) ? 'Proporcionada' : 'Desconocido')),
    ($row['coordinacion'] == 1) ? 'Si' : 'No',
    ($row['equilibrio'] == 1) ? 'Bueno' : (($row['equilibrio'] == 2) ? 'Regular' : (($row['equilibrio'] == 3) ? 'Malo' : 'Desconocido')),
    ($row['realiza_apoyo'] == 1) ? 'Si' : 'No',
    ($row['silla_ruedas'] == 1) ? 'Si' : 'No',
    ($row['utiliza_dispositivo'] == 1) ? 'Si' : 'No'
);


// Agregar filas manualmente con títulos en la primera columna
for ($i = 0; $i < count($escalas); $i++) {
    $pdf->Cell(80, 6, $escalas[$i], 1);
    $pdf->Cell(30, 6, $valores[$i], 1, 0, 'C');
    $pdf->Ln(); // Salto de línea
}

$pdf->Cell(0, 10, '¿Cuál? :', 0, 1);
$pdf->MultiCell(0, 5, $row['cual_dispositivo'], 0, 1);



    // Generar el PDF
    $pdf->Output();


} else {
    // Handle the case where the query fails
    echo "Error in the SQL query: " . mysqli_error($conexion);
}
?>