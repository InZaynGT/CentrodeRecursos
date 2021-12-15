<?php
//database connection info
$dbDetails = array(
    'host' => '127.0.0.1',
    'user' => 'root',
    'pass' => 'root',
    'db' => 'clinica'
);
//db table to use
$table = 'paciente';

// table´s primary key
$primaryKey = 'idpaciente';

//Array of database columns which should be read and sent back to datatables
//db parameter represents the column name in the database
//dt parameter represents the datatables column identifier

$columns = array(
    array('db' => 'nombres','dt' => 0),
    array('db' => 'direccion','dt'=> 1),
    array('db' => 'telefono','dt' => 2)
    
);

//include sql query processing class
require_once('C:\xampp\htdocs\clinica\scripts\ssp.class.php');

//output data as json format
echo json_encode(
    SSP::simple($_POST, $dbDetails,$table,$primaryKey,$columns)

);