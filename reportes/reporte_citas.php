<?php
// CONEXION A BASE DE DATOS
$conexion = mysqli_connect('127.0.0.1:3307', 'root', 'SAMI_zayn2802', 'clinica');
require_once 'dbconfig.php';

// Incluir la biblioteca TCPDF
include('library/tcpdf.php');

// Crear un objeto TCPDF
$pdf = new TCPDF('P', 'mm', 'A4');

// Eliminar el encabezado y el pie de página predeterminados
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Agregar una página
$pdf->AddPage();

// Agregar contenido (lista de consultas)

// Logo a la izquierda
$pdf->Image('../img/logo_fundal.png', 15, 15, 40);

$pdf->SetAlpha(0.2);
$pdf->Image('../img/marca_agua.png', 50, 100, 120, 0, 'PNG'); // Cambia 'ruta_de_la_imagen.png' a la ubicación de tu imagen de marca de agua
$pdf->SetAlpha(1);

// Título alineado a la derecha
$pdf->SetFont('Helvetica', '', 20);
$pdf->Cell(0, 40, "CENTRO DE RECURSOS", 0, 1, 'C');

$pdf->SetFont('Helvetica', '', 14);
$pdf->Cell(0, 10, "-FUNDAL, sede Huehuetenango-", 0, 1, 'C');

// Título del informe
$pdf->SetFont('Helvetica', '', 20);
$pdf->Cell(0, 20, "Consolidado de Citas Agendadas", 0, 1, 'C');
$pdf->SetFont('Helvetica', '', 12);
// Plantilla para la tabla
$html = "
    <table style='width: 100%; border-collapse: collapse;'>
        <tr style='background-color: #888; color: #fff;'>
            <th style='border: 1px solid #888; padding: 5px;'>Hora de Inicio</th>
            <th style='border: 1px solid #888; padding: 5px;'>Hora Finalización</th>
            <th style='border: 1px solid #888; padding: 5px;'>Descripción</th>
        </tr>
    ";

// Sentencia SQL para obtener los datos de las consultas
$sql = "SELECT * FROM events WHERE id_usuario = 1 ORDER BY id_event ASC;";

$result = mysqli_query($conexion, $sql);

// Loop para obtener los datos
while ($row = mysqli_fetch_assoc($result)) {
    $html .= "
        <tr>
            <td style='border: 1px solid #888; padding: 5px;'>" . $row['start_event'] . "</td>
            <td style='border: 1px solid #888; padding: 5px;'>" . $row['end_event'] . "</td>
            <td style='border: 1px solid #888; padding: 5px;'>" . $row['title'] . "</td>
        </tr>
    ";
}

// Cierre de la tabla
$html .= "</table>";

// Estilos mejorados para la tabla
$html .= "
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    table tr th {
        background-color: #333;
        color: #fff;
        font-weight: bold;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>
";

// Agregar la tabla al PDF
$pdf->WriteHTML($html);

// Generar el PDF
$pdf->Output();
?>
