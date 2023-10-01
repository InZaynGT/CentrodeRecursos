<?php
//database connection info
$dbDetails = array(
    'host' => 'localhost',
    'user' => 'root',
    'pass' => 'root',
    'db' => 'clinica'
);
//db table to use
$table = 'vista_medicamentos';

// table´s primary key
$primaryKey = 'idmedicamento';

//Array of database columns which should be read and sent back to datatables
//db parameter represents the column name in the database
//dt parameter represents the datatables column identifier

$columns = array(
    array('db' => 'idmedicamento','dt' => 0),
    array('db' => 'codigofiltro','dt' => 1),
    array('db' => 'nombre','dt' => 2),
    array('db' => 'dosificacion','dt'=> 3),
    array('db' => 'uso','dt'=> 4),
    array('db' => '','dt' => 5 )
     
);

//include sql query processing class
//require_once('C:\xampp\htdocs\clinica\serverside\ssp.class.php');

require_once('ssp.class.php');

//output data as json format
echo json_encode(
    SSP::simple($_GET, $dbDetails,$table,$primaryKey,$columns)

);