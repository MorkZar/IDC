<?php
$conexion = new mysqli("db", "angel", "angelpass", "mi_base");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
    
}
