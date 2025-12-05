<?php
session_start();

if(!isset($_SESSION['usuario'])){
  header("location: inicioSesion1.php");
  session_destroy();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/peticiones.css">
    <title>Peticiones Pendientes</title>
</head>

<!-- Alerta de éxito -->
<?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == "success") { ?>
<div class="alerta-exito">
    <p>✅ Solicitud enviada, espere respuesta en los próximos días</p>
</div>
<?php } ?>

<!-- Alerta de hora incorrecta -->
<?php if (isset($_GET['mensaje1']) && $_GET['mensaje1'] == "back") { ?>
<div class="alerta-fracaso">
    <p>🚫 La hora de inicio debe ser menor que la hora de finalización.</p>
</div>
<?php } ?>

<!-- Alerta de hora fuera de horario -->
<?php if (isset($_GET['mensaje3']) && $_GET['mensaje3'] == "fechaPasada") { ?>
<div class="alerta-fracaso">
    <p>🚫 La fecha ingresada ya ha pasado.</p>
</div>
<?php } ?>

<!-- Alerta de fecha con anticipacion -->
<?php if (isset($_GET['mensaje4']) && $_GET['mensaje4'] == "anticipacionInsuficiente") { ?>
<div class="alerta-fracaso">
    <p>🚫 Debes reseservar con 3 dias de anticipación.</p>
</div>
<?php } ?>

<!-- Alerta de fecha incorrecta -->
<?php if (isset($_GET['mensaje2']) && $_GET['mensaje2'] == "back1") { ?>
<div class="alerta-fracaso">
    <p>🚫 Los horarios permitidos son de las 7:00am-8:00pm.</p>
</div>
<?php } ?>

<!-- Alerta de fecha incorrecta -->
<?php if (isset($_GET['mensaje5']) && $_GET['mensaje5'] == "ocupado") { ?>
<div class="alerta-fracaso">
    <p>🚫 El espacio no está disponible en la fecha seleccionada. Por favor, elija otra fecha </p>
</div>
<?php } ?>

<!-- Alerta de fin de semana -->
<?php if (isset($_GET['mensaje7']) && $_GET['mensaje7'] == "finDeSemana") { ?>
<div class="alerta-fracaso">
    <p>🚫 No se puede reservar en fines de semana. </p>
</div>
<?php } ?>

<!-- Alerta de fin de semana -->
<?php if (isset($_GET['mensaje6']) && $_GET['mensaje6'] == "fechaNoLaborable") { ?>
<div class="alerta-fracaso">
    <p>🚫 Dia no Laboral. </p>
</div>
<?php } ?>

 <script>
// === Validación en tiempo real de fechas y horas ===

document.addEventListener("DOMContentLoaded", function() {
    const fechaInput = document.getElementById("fecha");
    const horaInicioInput = document.getElementById("horai");
    const horaFinInput = document.getElementById("horaf");

    // Crear contenedores para los mensajes de error dinámicos
    const errorFecha = document.createElement("p");
    const errorHora = document.createElement("p");
    errorFecha.style.color = "red";
    errorHora.style.color = "red";

    fechaInput.insertAdjacentElement("afterend", errorFecha);
    horaFinInput.insertAdjacentElement("afterend", errorHora);

    // Escuchar cambios en fecha y horas
    fechaInput.addEventListener("change", validarFecha);
    horaInicioInput.addEventListener("change", validarHoras);
    horaFinInput.addEventListener("change", validarHoras);

    function validarFecha() {
        const hoy = new Date();
        const fechaSeleccionada = new Date(fechaInput.value);
        const tresDiasDespues = new Date(hoy);
        tresDiasDespues.setDate(hoy.getDate() + 3);

        const diaSemana = fechaSeleccionada.getDay(); // 0=Domingo, 6=Sábado
        const mes = fechaSeleccionada.getMonth() + 1; // Enero=1
        const dia = fechaSeleccionada.getDate();

        errorFecha.textContent = "";

        // 1️⃣ Fecha pasada
        if (fechaSeleccionada < hoy.setHours(0, 0, 0, 0)) {
            errorFecha.textContent = "🚫 La fecha ingresada ya ha pasado.";
            fechaInput.setCustomValidity("Fecha inválida");
            return;
        }

        // 2️⃣ Menos de 3 días de anticipación
        if (fechaSeleccionada < tresDiasDespues) {
            errorFecha.textContent = "🚫 Debes reservar con al menos 3 días de anticipación.";
            fechaInput.setCustomValidity("Anticipación insuficiente");
            return;
        }

        // 3️⃣ Fin de semana
        if (diaSemana === 0 || diaSemana === 6) {
            errorFecha.textContent = "🚫 No se puede reservar en fines de semana.";
            fechaInput.setCustomValidity("Fin de semana no permitido");
            return;
        }

        // 4️⃣ Mes no laboral (junio o julio)
        if (mes === 6 || mes === 7) {
            errorFecha.textContent = "🚫 No se puede reservar durante junio o julio (vacaciones).";
            fechaInput.setCustomValidity("Mes no laboral");
            return;
        }

        // 5️⃣ Días festivos fijos (mes-día)
        const diasNoLaborales = ["02-03","03-17","05-01","05-05","05-15","09-16","11-17"];
        const formatoDiaMes = String(mes).padStart(2, "0") + "-" + String(dia).padStart(2, "0");

        if (diasNoLaborales.includes(formatoDiaMes)) {
            errorFecha.textContent = "🚫 Día no laborable o festivo.";
            fechaInput.setCustomValidity("Día no laborable");
            return;
        }

        // ✅ Si todo está bien
        fechaInput.setCustomValidity("");
        errorFecha.textContent = "";
    }

    function validarHoras() {
        const horaInicio = horaInicioInput.value;
        const horaFin = horaFinInput.value;

        errorHora.textContent = "";

        if (!horaInicio || !horaFin) return;

        // Convertir a minutos para comparar fácilmente
        const [hIni, mIni] = horaInicio.split(":").map(Number);
        const [hFin, mFin] = horaFin.split(":").map(Number);
        const inicioTotal = hIni * 60 + mIni;
        const finTotal = hFin * 60 + mFin;

        // 1️⃣ Hora fin debe ser mayor que hora inicio
        if (finTotal <= inicioTotal) {
            errorHora.textContent = "🚫 La hora de inicio debe ser menor que la hora de finalización.";
            horaFinInput.setCustomValidity("Hora inválida");
            return;
        }

        // 2️⃣ Horarios permitidos: 07:00 - 20:00
        if (hIni < 7 || hFin > 20) {
            errorHora.textContent = "🚫 Los horarios permitidos son de 7:00 AM a 8:00 PM.";
            horaFinInput.setCustomValidity("Fuera de horario permitido");
            return;
        }

        // ✅ Si todo está correcto
        horaFinInput.setCustomValidity("");
        errorHora.textContent = "";
    }
});
</script>



<body>


<div class="titulo">
<a href="mainpage.php"><button class="back-btn">&#8592;</button></a>
    <h1>Solicitud de Reservación</h1>
</div>

        <form  action="registrarPeticiones.php" method="POST" class="formulario" id="formulario">
            <table border="1">
                <tr>
                    <td>
                        <label for="solicitante" >Solicitante: 🫏</label>

                        <?php
                        include "conexionBD.php";
                        $usuario = $_SESSION['usuario']; 
                        $query1 = "SELECT nombre, ap_paterno, ap_materno FROM usuarios WHERE correo = '$usuario'"; 
                        $result1 = $conexion->query($query1);

                        if ($result1 && $result1->num_rows > 0) {
                            $row = $result1->fetch_assoc();
                            echo "<p>{$row['nombre']} {$row['ap_paterno']} {$row['ap_materno']}</p>";
                        } else {
                            echo "<p>No se encontró el solicitante</p>";
                        }
                        ?>

                        <label for="espacio">Espacio:</label>
                        <?php
                        include "conexionBD.php";
                        $query = "SELECT id_espacio, nombre_espacio FROM espacios";
                        $result = $conexion->query($query);
                        ?>

                        <div class="selectTipo">
                            <select id="espacio" name="espacio" required oninvalid="validarCampo(this, 'Falta seleccionar espacio')"
                            oninput="this.setCustomValidity('')">
                                <option value="" selected></option>
                                <?php
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['id_espacio'] . "'>" . $row['nombre_espacio'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <label for="nombreEvento">Nombre Evento:</label>
                        <input type="text" id="nombreEvento" name="nombreEvento" required oninvalid="validarCampo(this, 'Falta ingresar nombre del evento')"
                        oninput="this.setCustomValidity('')">

                        <label for="fecha">Fecha:</label>
                        <input type="date" id="fecha" name="fecha" required oninvalid="validarCampo(this, 'Falta seleccionar fecha')"
                        oninput="this.setCustomValidity('')">
                        
                        <label for="horai">Hora Inicio:</label>
                        <input type="time" id="horai" name="horai" required oninvalid="validarCampo(this, 'Falta seleccionar hora')"
                        oninput="this.setCustomValidity('')">

                        <label for="horaf">Hora Final:</label>
                        <input type="time" id="horaf" name="horaf" required oninvalid="validarCampo(this, 'Falta seleccionar hora')"
                        oninput="this.setCustomValidity('')">

                         <label>Mobiliario Disponible</label>
<div class="check-box">
<?php
include "conexionBD.php";
$queryM = "SELECT id_mobiliario, nombre_mobiliario, unidades_disponibles FROM mobiliario";
$resultM = $conexion->query($queryM);

while ($rowM = $resultM->fetch_assoc()) {
    $id = $rowM['id_mobiliario'];
    $nombre = htmlspecialchars($rowM['nombre_mobiliario']);
    $disp = (int)$rowM['unidades_disponibles'];

    echo "<label>
            <input type='checkbox' name='mobiliario[]' value='{$id}'>
            <span class='item-text'>{$nombre} (Disp.: {$disp})</span>
            <!-- Enviamos siempre un campo cantidad[id] (por defecto 0) -->
            <input type='hidden' name='cantidad[{$id}]' value='0'>
            <!-- Input visible para cantidad; el usuario puede poner 0 si trae el suyo -->
            <input type='number' name='cantidad[{$id}]' min='0' max='{$disp}' value='0' style='width:70px; margin-left:8px;'>
          </label>";
}
?>
</div>
                    </td>
                    <td class="detalles-con-fondo">
                        <label for="comentarios">Detalles:</label>
                        <textarea id="detalles" name="detalles" required maxlength="300" oninvalid="validarCampo(this, 'Falta ingresar detalles')"
                        oninput="this.setCustomValidity('')"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <button type="submit">Enviar Solicitud</button>
                    </td>
                </tr>
            </table>
        </form>
</body>

<script>
function validarCampo(input, mensajeVacio, mensajeInvalido) {
    if (input.validity.valueMissing) {
        input.setCustomValidity(mensajeVacio);
    } else if (input.validity.patternMismatch || input.validity.typeMismatch) {
        input.setCustomValidity(mensajeInvalido);
    } else {
        input.setCustomValidity('');
    }
}
</script>
</html>