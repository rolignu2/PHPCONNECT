<?php

 include 'Config.php';
 include 'Database.php';
 
 $db = new Database();
 
 /**
  * 
  * TESTEO DE INSERT
  * **/
 
/* $table = "user_login";
 
 $params = array(
     "username" => "pepito" , 
     "email" => "pepe",
     "password" => "password",
     "estado" => 1,
     "tipo" => 1
     );
 
echo $db->Insert($table, $params); */ 
 
 //TEST INSERT OK
 
 
/*$params = array(
     "username" => "pepitorecargado" , 
     "email" => "pepe",
     "password" => "password",
     "estado" => 1,
     "tipo" => 1
     );

$table = "user_login";

echo $db->Update($table, $params, " iduser like 39");*/
 
 //TEST UPDATE SET
 