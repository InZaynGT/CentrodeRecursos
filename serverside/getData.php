<?php
//database connection info
$dbDetails = array(
    'host' => '127.0.0.1',
    'user' => 'root',
    'pass' => 'root',
    'db' => 'clinica'
);
//db table to use
$table = 'vista_pacientes';

// table´s primary key
$primaryKey = 'idpaciente';

//Array of database columns which should be read and sent back to datatables
//db parameter represents the column name in the database
//dt parameter represents the datatables column identifier

$columns = array(
    array('db' => 'idpaciente','dt' => 0),
    array('db' => 'nombres','dt' => 1),
    array('db' => 'direccion','dt'=> 2),
    array('db' => 'telefono','dt' => 3),
    array('db' => 'fecha_ingreso','dt' => 4),
    array('db' => '','dt' => 5)
    
);

//include sql query processing class
require_once('C:\xampp\htdocs\clinica\serverside\ssp.class.php');

//output data as json format
echo json_encode(
    SSP::simple($_GET, $dbDetails,$table,$primaryKey,$columns)

);
