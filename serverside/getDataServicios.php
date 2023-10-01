<?php
//database connection info
$dbDetails = array(
    'host' => 'localhost',
    'user' => 'root',
    'pass' => 'root',
    'db' => 'clinica'
);
//db table to use
$table = 'vista_servicios';

// table´s primary key
$primaryKey = 'idproducto_servicio';

//Array of database columns which should be read and sent back to datatables
//db parameter represents the column name in the database
//dt parameter represents the datatables column identifier

$columns = array(
    array('db' => 'idproducto_servicio','dt' => 0),
    array('db' => 'nombre','dt' => 1),
    array('db' => 'precio','dt' => 2),
    array('db' => '', 'dt' => 3)
     
);

//include sql query processing class
//require_once('C:\xampp\htdocs\clinica\serverside\ssp.class.php');

require_once('ssp.class.php');

//output data as json format
echo json_encode(
    SSP::simple($_GET, $dbDetails,$table,$primaryKey,$columns)

);