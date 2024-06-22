<?php
$DBhost = "127.0.0.1:3306";
$DBuser = "root";
$DBpass = "";
$DBname = "clinica";

try {

  $DBcon = new PDO("mysql:host=$DBhost;dbname=$DBname", $DBuser, $DBpass);
  $DBcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $ex) {

  die($ex->getMessage());
}

?>