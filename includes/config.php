<?php 
ob_start();
session_start();

$db_host="localhost";
$db_user="root";
$db_password="ayten12345";
$db_name="shopping_practice";

try{
    $con = new PDO("mysql:dbname=$db_name; host=$db_host", $db_user, $db_password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(PDOException $e){
    echo $e->getMessage();
}


?>