<?php
$DBhost = "127.0.0.1:3307";
 $DBuser = "root";
 $DBpass = "admin";
 $DBname = "clinica";

try{
  
  $DBcon = new PDO("mysql:host=$DBhost;dbname=$DBname",$DBuser,$DBpass);
  $DBcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
}catch(PDOException $ex){
  
  die($ex->getMessage());
}

?>