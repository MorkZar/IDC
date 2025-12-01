<?php
session_start();
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-primary d-flex align-items-center" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:" width="24" height="24">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            <div>' . $_SESSION['error_message'] . '</div>
        </div>';
    unset($_SESSION['error_message']); // Eliminar el mensaje después de mostrarlo
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.1/normalize.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style3.css">
  <title>Registro</title>
</head>
<body>
<div class="container-all">

<div class="container-form">
    <img src="imagenes/logo ReservaTec (1).png" alt="" class="logo">
    <h1 class="title">Registro!</h1>

    <form action="registrarUsuarios.php" method="POST" class="formulario" id="formulario">

			<!-- Grupo: Nombre -->
			<div class="formulario__grupo" id="grupo__nombre">
				<label for="nombre" class="formulario__label">Nombre</label>
				<div class="formulario__grupo-input">
					<input type="text" class="formulario__input" name="nombre" id="nombre" maxlength ="30" required>
					<i class="formulario__validacion-estado fas fa-times-circle"></i>
				</div>
				<p class="formulario__input-error">Solo se permiten letras y espacio en blanco.</p>
			</div>


            <!-- Grupo: Apellidos -->

            <div class="formulario__grupo" id="grupo__ap_paterno">
				<label for="ap_paterno" class="formulario__label">Apellido Paterno</label>
				<div class="formulario__grupo-input">
					<input type="text" class="formulario__input" name="ap_paterno" id="ap_paterno" maxlength ="30" required>  
					<i class="formulario__validacion-estado fas fa-times-circle"></i>
				</div>
				<p class="formulario__input-error">Solo se permiten letras y espacio en blanco.</p>
			</div>

			

			<div class="formulario__grupo" id="grupo__ap_materno">
				<label for="ap_materno" class="formulario__label">Apellido Materno</label>
				<div class="formulario__grupo-input">
					<input type="text" class="formulario__input" name="ap_materno" id="ap_materno" maxlength ="30" required>
					<i class="formulario__validacion-estado fas fa-times-circle"></i>
				</div>
				<p class="formulario__input-error">Solo se permiten letras y espacio en blanco.</p>
			</div>

			<!-- Tipo Usuario -->

            <label for="tipo"><b>Tipo Usuario</b></label>
<?php
include "conexionBD.php";

$query = "SELECT id_tipousuario, tipo_usuario FROM tipousuario";
$result = $conexion->query($query);
?>

<div class="selectTipo">
<select id="tipo" name="tipo" required>
    

    <?php
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['id_tipousuario'] . "'>" . $row['tipo_usuario'] . "</option>";
    }
    ?>
</select>
  </div>

  <div id="campoAdicional" style="display: none;">
	<div class="formulario__grupo" id="grupo__nombreAdicional">
		<label for="nombreAdicional" class="formulario__label">Específique el Nombre de la Organización:</label>
		<div class="formulario__grupo-input">
			<input type="text" class="formulario__input" name="nombreAdicional" id="nombreAdicional" maxlength="30">
			<i class="formulario__validacion-estado fas fa-times-circle"></i>
		</div>
		<p class="formulario__input-error">Solo se permiten letras, números y espacios.</p>
	</div>
</div>

		
    <!-- Grupo: Correo Electronico -->
			<div class="formulario__grupo" id="grupo__correo">
				<label for="correo" class="formulario__label">Correo Electrónico</label>
				<div class="formulario__grupo-input">
					<input type="text" class="formulario__input" name="correo" id="correo" placeholder="correo@correo.com" maxlength ="30" required>
					<i class="formulario__validacion-estado fas fa-times-circle"></i>
				</div>
				<p class="formulario__input-error">Fromato de correo incorrecto correo@ejemplo.com.</p>
			</div>

       

    <!-- Grupo: Contraseña -->
			<div class="formulario__grupo" id="grupo__password">
				<label for="password" class="formulario__label">Contraseña</label>
				<div class="formulario__grupo-input">
					<input type="password" class="formulario__input" name="password" id="password" minlength="8" maxlength="20" required>
					<i class="formulario__validacion-estado fas fa-times-circle"></i>
				</div>
				<p class="formulario__input-error">La contraseña tiene que ser de 8 a 20 dígitos.</p>
			</div>

    <button class="buttonin" type="submit">Registrarse</button>

    </form>

    <script src="js/formularioNuevoUsuario.js"></script>
	<script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>


  <span class="text-footer">Ya tengo una cuenta  <a href="inicioSesion1.php"> Iniciar Sesión</a></span>

  </div>

    <div class="container-image">
  <div class="capa"></div>
  </div>

</div>
</body>
<footer class="footer">
  <p class="information">2025 | Todos los derechos reservados </p>
</footer>
</html>