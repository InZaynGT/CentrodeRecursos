<?php 
	//FORMATO PARA GENERAR EL PDF DE LOS SERVICIOS DISPONIBLES DENTRO DEL SISTEMA.


	//CONEXION A BASE DE DATOS
	$conexion=mysqli_connect('127.0.0.1:3307','root','admin','clinica');
    require_once 'dbconfig.php';


//include library
include('library/tcpdf.php');

//make TCPDF object
$pdf = new TCPDF('P','mm','A4');

//remove default header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//add page
$pdf->AddPage();

//add content (student list)
//title
$pdf->SetFont('Helvetica','',20);
$pdf->Cell(190,10,"CENTRO DE RECURSOS, FUNDAL HUEHUETENANGO",0,1,'C');

$pdf->SetFont('Helvetica','',14);
$pdf->Cell(190,5,"Listado de Pacientes Inscritos",0,1,'C');

$pdf->SetFont('Helvetica','',10);

//PLANTILLA QUE USAREMOS PARA EL HTML, AQUÍ EDITAMOS EL ENCABEZADO DE LA TABLA
$html = "
	<table>
		<tr>
			<th>Codigo</th>
			<th>Nombre</th>
            <th>Fecha y Hora de Registro</th>
			<th>Nombre de Encargado</th>
            <th>Teléfono</th>
            <th>Diagnostico</th>
		</tr>
		";

		//SENTENCIA SQL DE LOS DATOS QUE VAMOS A TRAER
		$sql="SELECT *
		FROM paciente
		WHERE estado = 1 
		ORDER BY idpaciente DESC
		LIMIT 0,100";
		$result=mysqli_query($conexion,$sql);
		//MANDAMOS A TRAER LOS DATOS A LA BDD
        $stmt = $DBcon->prepare($sql);
        $stmt->execute();
        
		//DECLARAMOS EL ARRAY DONDE LAS VAMOS A GUARDAR
        $userData = array();

		//INICIAMOS EL CICLO PARA INTRODUCIRLAS DENTRO DEL ARRAY userData
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)) $userData[] = $row;

		//HACEMOS EL ENCODE PARA PASARLO DE PHP A JSON (esto para que tenga el formato que tengamos cuando modifiquemos)
        $userData2 = json_encode($userData);
		//loop the data

		//HACEMOS EL DECODE PARA REGRESARLO A PHP, YA CON EL FORMATO ADECUADO PARA PODER HACER EL LOOP E INSERTARLO AL PDF
        $data2 = json_decode($userData2);


		//ITERAMOS EL PHP PARA PODER INSERTARLO EN CADA REGISTRO DE LA TABLA
foreach($data2 as $student){	
	$html .= "
			<tr>
				<td>". $student->idpaciente ."</td>
				<td>". $student->nombre ."</td>
				<td>". $student->fecha_ingreso ."</td>
				<td>". $student->nombre_encargado ."</td>
				<td>". $student->telefono ."</td>
				<td>". $student->diagnostico ."</td>
			</tr>
			";
}		

//ESTILOS DE LA TABLA
$html .= "
	</table>
	<style>
	table {
		border-collapse:collapse;
	}
	th,td {
		border:1px solid #888;
	}
	table tr th {
		background-color:#888;
		color:#fff;
		font-weight:bold;
	}
	</style>
";
//WriteHTMLCell
$pdf->WriteHTMLCell(192,0,9,'',$html,0);	

//output
$pdf->Output();
	 ?>