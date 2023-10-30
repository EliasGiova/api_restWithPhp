<?php

$host="localhost";
$user="root";
$pass="root";
$bd="api";

$conexion= new mysqli($host,$user,$pass,$bd);

if($conexion->connect_error){
    die ("Conexion no establecida: ".$conexion->connect_error);
}
?>