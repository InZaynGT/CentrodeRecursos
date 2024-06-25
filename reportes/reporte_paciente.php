<?php
//LOCAL
$conexion = mysqli_connect('localhost:3307', 'root', 'SAMI_zayn2802', 'clinica');
//PRODUCCION
//$conexion = mysqli_connect('localhost:3306', 'cmiranda', 'MySecurePass123!', 'clinica');

require_once 'dbconfig.php';
//include library
include ('library/tcpdf.php');

//make TCPDF object
$pdf = new TCPDF('P', 'mm', 'A4');

//remove default header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//add page
$pdf->AddPage();

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
$pdf->Cell(0, 20, "Listado de Últimos Pacientes Registrados", 0, 1, 'C');
$pdf->SetFont('Helvetica', '', 12);

//PLANTILLA QUE USAREMOS PARA EL HTML, AQUÍ EDITAMOS EL ENCABEZADO DE LA TABLA
$html = "
    <table style='width: 100%; border-collapse: collapse;'>
        <tr style='background-color: #888; color: #fff;'>
            <th style='border: 1px solid #888; padding: 5px;'>Nro. Paciente</th>
            <th style='border: 1px solid #888; padding: 5px;'>Nombre de Paciente</th>
            <th style='border: 1px solid #888; padding: 5px;'>Fecha de Ingreso</th>
            <th style='border: 1px solid #888; padding: 5px;'>Nombre de Encargado</th>
        </tr>
    ";

//SENTENCIA SQL DE LOS DATOS QUE VAMOS A TRAER
$sql = "SELECT *
		FROM paciente
		WHERE estado = 1 
		ORDER BY idpaciente DESC
		LIMIT 0,100";

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

if($data2> 0){

//ITERAMOS EL PHP PARA PODER INSERTARLO EN CADA REGISTRO DE LA TABLA
foreach ($data2 as $student) {
    $html .= "
			<tr>
				<td>" . $student->idpaciente . "</td>
				<td>" . $student->nombre . "</td>
				<td>" . $student->fecha_ingreso . "</td>
				<td>" . $student->nombre_encargado . "</td>
				<td>" . $student->telefono . "</td>
				<td>" . $student->diagnostico . "</td>
			</tr>
			";
}
}

//ESTILOS DE LA TABLA
// Estilos para la tabla
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
//WriteHTMLCell
$pdf->WriteHTMLCell(192, 0, 9, '', $html, 0);

//output
$pdf->Output();
?>