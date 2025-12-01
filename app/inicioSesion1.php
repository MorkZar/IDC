<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.1/normalize.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style3.css">
  <title>Login</title>
</head>
<body>
<div class="container-all">

  <div class="container-form">
  <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == "success") { ?>
  <div class="alert alert-success" role="alert">
  <span class="text-danger" style="color: crimson;">Cuenta creada exitosamente. ¡Inicia sesión ahora!</span>
  </div>
<?php } ?>

  <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == "userinvalid") { ?>
  <div class="alert alert-success" role="alert">
  <span class="text-danger" style="color: crimson">Usuario no Registrado</span>
  </div>
<?php } ?>

    <img src="imagenes/logo ReservaTec (1).png" alt="" class="logo">
    <h1 class="title">Inicio de Sesión</h1>

    <form action="iniciarSesion.php" method="POST" class="formulario" id="formulario">

    <!-- Grupo: Correo Electronico -->
			<div class="formulario__grupo" id="grupo__correo">
				<label for="correo" class="formulario__label">Correo Electrónico</label>
				<div class="formulario__grupo-input">
					<input type="text" class="formulario__input" name="correo" id="correo" placeholder="correo@correo.com" maxlength ="30 " required>
					<i class="formulario__validacion-estado fas fa-times-circle"></i>
				</div>
				<p class="formulario__input-error">Fromato de correo incorrecto correo@ejemplo.com.</p>
			</div>

            <label for=""></label>

    <!-- Grupo: Contraseña -->
			<div class="formulario__grupo" id="grupo__password">
				<label for="password" class="formulario__label">Contraseña</label>
				<div class="formulario__grupo-input">
					<input type="password" class="formulario__input" name="password" id="password" minlength="8" maxlength="20" required>
					<i class="formulario__validacion-estado fas fa-times-circle"></i>
				</div>
				<p class="formulario__input-error">La contraseña tiene que ser de 8 a 20 dígitos.</p>
			</div>

            <label for=""></label>

    <button name = iniciarSesion class="buttonin" type="submit">Iniciar Sesión</button>

    </form>

    <script src="js/formulario.js"></script>
	<script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>


    <span class="text-footer">¿Aún no te has registrado? <a href="registrarse1.php">Registrate</a></span>

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