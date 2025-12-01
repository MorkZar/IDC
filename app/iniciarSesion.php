<?php

session_start();

include 'conexionBD.php';
$correo = $_POST['correo'];
$pass = $_POST['password'];
//$pass = hash('sha512', $pass);

$validar_login = mysqli_query($conexion, "SELECT * FROM usuarios
WHERE correo='$correo' and contrasena = '$pass'");

if(mysqli_num_rows($validar_login) > 0){
    $_SESSION['usuario'] = $correo;
    header("location: mainpage.php");
    exit;
}else{
    // Redirigir si la inserción fue exitosa
    header("Location: inicioSesion1.php?mensaje=userinvalid");
    exit;
}
?>