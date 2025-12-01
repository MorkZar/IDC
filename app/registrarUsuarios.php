<?php
include 'conexionBD.php';

$nombre = $_POST['nombre'];
$ap_paterno = $_POST['ap_paterno'];
$ap_materno = $_POST['ap_materno'];
$tipo_usuario = $_POST['tipo'];

// Convertimos el tipo de usuario a su respectivo ID
$tipos_validos = [
    "alumno" => 1,
    "docente" => 2,
    "empresa" => 3,
    "institucionExterna" => 4
];

$id_tipousuario = isset($tipos_validos[$tipo_usuario]) ? $tipos_validos[$tipo_usuario] : NULL;


$nombre_organizacion = $_POST['nombreAdicional'];
$correo = $_POST['correo'];
$pass = $_POST['password'];
//$pass = hash('sha512', $pass);

// Iniciar la transacción
mysqli_begin_transaction($conexion);

try{
// Verificar si el correo ya existe en la base de datos
$check_email_query = "SELECT * FROM usuarios WHERE correo = '$correo'";
$result = mysqli_query($conexion, $check_email_query);

session_start(); // Iniciar sesión

if (mysqli_num_rows($result) > 0) {
    $_SESSION['error_message'] = "El correo ya está registrado. Intenta con otro.";
    header("Location: registrarse1.php"); // Redirige de vuelta al formulario
    exit;
}

$query = "INSERT INTO usuarios (nombre, ap_paterno, ap_materno, id_tipousuario, nombre_organizacion, correo, contrasena)
VALUES ('$nombre', '$ap_paterno', '$ap_materno', '$tipo_usuario', '$nombre_organizacion', '$correo', '$pass')";

if (!mysqli_query($conexion, $query)) {
    throw new Exception("Error al insertar el usuario.");
}

// Confirmar la transacción
mysqli_commit($conexion);

// Redirigir si la inserción fue exitosa
header("Location: inicioSesion1.php?mensaje=success");
exit;
} catch (Exception $e) {
// Revertir los cambios si ocurre un error
mysqli_rollback($conexion);

// Mostrar el error
echo'
<script>
alert("Error: ' . $e->getMessage() . '");
window.history.back();
</script>
';
}

// Cerrar la conexión
mysqli_close($conexion);
?>