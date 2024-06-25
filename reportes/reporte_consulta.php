<?php
//LOCAL
$conexion = mysqli_connect('localhost:3307', 'root', 'SAMI_zayn2802', 'clinica');
//PRODUCCION
//$conexion = mysqli_connect('localhost:3306', 'cmiranda', 'MySecurePass123!', 'clinica');
require_once 'dbconfig.php';

include ('library/tcpdf.php');
// Crear un objeto TCPDF
$pdf = new TCPDF('P', 'mm', 'A4');
// Eliminar el encabezado y el pie de página predeterminados
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// Agregar una página
$pdf->AddPage();
// Logo a la izquierda
$pdf->Image('../img/logo_fundal.png', 15, 15, 40);
// Agregar una marca de agua (imagen)
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
$pdf->Cell(0, 20, "Listado de Últimas Consultas realizadas", 0, 1, 'C');
$pdf->SetFont('Helvetica', '', 12);
// Plantilla para la tabla
$html = "
    <table style='width: 100%; border-collapse: collapse;'>
        <tr style='background-color: #888; color: #fff;'>
            <th style='border: 1px solid #888; padding: 5px;'>Nro. Consulta</th>
            <th style='border: 1px solid #888; padding: 5px;'>Fecha de Consulta</th>
            <th style='border: 1px solid #888; padding: 5px;'>Código Interno de Paciente</th>
            <th style='border: 1px solid #888; padding: 5px;'>Nombre de Paciente</th>
        </tr>
    ";

// Sentencia SQL para obtener los datos de las consultas
$sql = "SELECT c.*, p.nombre AS nombre 
        FROM clinica.consulta c 
        INNER JOIN clinica.paciente p ON p.idpaciente = c.idpaciente 
        WHERE c.estado = 1
        ORDER BY fechaconsulta DESC";
$result = mysqli_query($conexion, $sql);
//MANDAMOS A TRAER LOS DATOS A LA BDD
$stmt = $DBcon->prepare($sql);
$stmt->execute();

//DECLARAMOS EL ARRAY DONDE LAS VAMOS A GUARDAR
$userData = array();

//INICIAMOS EL CICLO PARA INTRODUCIRLAS DENTRO DEL ARRAY userData
while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    $userData[] = $row;

//HACEMOS EL ENCODE PARA PASARLO DE PHP A JSON (esto para que tenga el formato que tengamos cuando modifiquemos)
$userData2 = json_encode($userData);
//loop the data

//HACEMOS EL DECODE PARA REGRESARLO A PHP, YA CON EL FORMATO ADECUADO PARA PODER HACER EL LOOP E INSERTARLO AL PDF
$data2 = json_decode($userData2);

// Loop para obtener los datos
// while ($row = mysqli_fetch_assoc($result)) {
//     $html .= "
//         <tr>
//             <td style='border: 1px solid #888; padding: 5px;'>" . $row['idconsulta'] . "</td>
//             <td style='border: 1px solid #888; padding: 5px;'>" . $row['fechaconsulta'] . "</td>
//             <td style='border: 1px solid #888; padding: 5px;'>" . $row['idpaciente'] . "</td>
//             <td style='border: 1px solid #888; padding: 5px;'>" . $row['nombre'] . "</td>
//         </tr>
//     ";
// }

//ITERAMOS EL PHP PARA PODER INSERTARLO EN CADA REGISTRO DE LA TABLA
if($data2> 0){
foreach ($data2 as $student) {
    $html .= "
			<tr>
				<td style='border: 1px solid #888; padding: 5px;'>" . $student->idconsulta . "</td>
				<td style='border: 1px solid #888; padding: 5px;'>" . $student->fechaconsulta . "</td>
				<td style='border: 1px solid #888; padding: 5px;'>" . $student->idpaciente . "</td>
				<td style='border: 1px solid #888; padding: 5px;'>" . $student->nombre . "</td>
			</tr>
			";
}

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
