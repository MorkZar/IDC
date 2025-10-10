<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "reservatec";

$conexion = new mysqli($server,$user,$pass,$db);

if ($conexion->connect_errno) {
    echo "Error de conexión: " . $conexion->connect_error;
} else {
    echo "Conexión exitosa!";
}

$conexion->close();

