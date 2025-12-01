<?php
// registrarPeticiones.php (versión mejorada)
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: inicioSesion1.php");
    exit;
}

require_once 'conexionBD.php'; // $conexion (mysqli)

// Habilitar exceptions para mysqli (mejora el manejo de errores)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$usuarioCorreo = $_SESSION['usuario'];

// Obtener id_usuario de forma segura
$stmtUser = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
$stmtUser->bind_param("s", $usuarioCorreo);
$stmtUser->execute();
$resUser = $stmtUser->get_result();
if (!$resUser || $resUser->num_rows === 0) {
    // No existe el usuario en la DB
    header("Location: peticiones.php?mensaje=errorUsuario");
    exit;
}
$rowU = $resUser->fetch_assoc();
$id_usuario = (int)$rowU['id_usuario'];
$stmtUser->close();

// Recogida y sanitización de campos
$idespacio    = isset($_POST['espacio']) ? (int)$_POST['espacio'] : 0;
$nombreEvento = trim($_POST['nombreEvento'] ?? '');
$fecha        = $_POST['fecha'] ?? '';
$horai        = $_POST['horai'] ?? '';
$horaf        = $_POST['horaf'] ?? '';
$detalles     = trim($_POST['detalles'] ?? '');

// Validaciones básicas (serias) - necesarias antes de insertar
if (!$idespacio || !$nombreEvento || !$fecha || !$horai || !$horaf || !$detalles) {
    header("Location: peticiones.php?mensaje=error");
    exit;
}

// Validar horas (07:00 - 20:00) y orden temporal
$horaInicio = strtotime($horai);
$horaFin    = strtotime($horaf);
$horaMinima = strtotime("07:00");
$horaMaxima = strtotime("20:00");

if ($horaInicio >= $horaFin) {
    header("Location: peticiones.php?mensaje1=back");
    exit;
}
if ($horaInicio < $horaMinima || $horaFin > $horaMaxima) {
    header("Location: peticiones.php?mensaje2=back1");
    exit;
}

// Fecha no pasada y anticipación >= 3 días
$fechaActual = date("Y-m-d");
if ($fecha < $fechaActual) {
    header("Location: peticiones.php?mensaje3=fechaPasada");
    exit;
}
$fechaLimite = date("Y-m-d", strtotime($fechaActual . " +3 days"));
if ($fecha < $fechaLimite) {
    header("Location: peticiones.php?mensaje4=anticipacionInsuficiente");
    exit;
}

// Validar fin de semana
$diaSemana = date('N', strtotime($fecha)); // 1..7
if ($diaSemana == 6 || $diaSemana == 7) {
    header("Location: peticiones.php?mensaje7=finDeSemana");
    exit;
}

// Días no laborables (mm-dd)
$diasNoLaborables = [
    '02-03', // Constitucion Mexicana
    '03-17', // Natalicio de Benito Juarez
    '09-16', // Independencia
    '05-01', // Día del Trabajo
    '05-05', // Batalla de Puebla
    '05-15', //Dia del Maestro
    '09-16', //Dia de la Independencia 
    '11-17', //Dia de la Revolucion
    '04-14', //Vacaciones Semana Santa
    '04-15', //Vacaciones Semana Santa
    '04-16', //Vacaciones Semana Santa
    '04-17', //Vacaciones Semana Santa
    '04-18', //Vacaciones Semana Santa
    '04-21', //Vacaciones Semana Santa
    '04-22', //Vacaciones Semana Santa
    '04-23', //Vacaciones Semana Santa
    '04-24', //Vacaciones Semana Santa
    '04-25', //Vacaciones Semana Santa
    '12-14', //Vacaciones Invierno
    '12-15', //Vacaciones Invierno
    '12-16', //Vacaciones Invierno
    '12-17', //Vacaciones Invierno
    '12-18', //Vacaciones Invierno
    '12-19', //Vacaciones Invierno
    '12-20', //Vacaciones Invierno
    '12-21', //Vacaciones Invierno
    '12-22', //Vacaciones Invierno
    '12-23', //Vacaciones Invierno
    '12-24', //Vacaciones Invierno
    '12-25', //Vacaciones Invierno
    '12-26', //Vacaciones Invierno
    '12-27', //Vacaciones Invierno
    '12-28', //Vacaciones Invierno
    '12-29', //Vacaciones Invierno
    '12-30', //Vacaciones Invierno
    '12-31', //Vacaciones Invierno
    '01-01', //Vacaciones Invierno
    '01-02', //Vacaciones Invierno
    '01-03', //Vacaciones Invierno
    '01-04', //Vacaciones Invierno
    '01-05', //Vacaciones Invierno
    '01-06', //Vacaciones Invierno
    '01-07', //Vacaciones Invierno
    '01-08', //Vacaciones Invierno
    '01-09', //Vacaciones Invierno
    '01-10', //Vacaciones Invierno
    '01-11', //Vacaciones Invierno
    '01-12', //Vacaciones Invierno
    '01-13', //Vacaciones Invierno
    '01-14', //Vacaciones Invierno
    '01-15', //Vacaciones Invierno
    '01-16', //Vacaciones Invierno
    '01-17', //Vacaciones Invierno
    '01-18', //Vacaciones Invierno
    '01-19', //Vacaciones Invierno
    '01-20', //Vacaciones Invierno
    '01-21', //Vacaciones Invierno
    '01-22', //Vacaciones Invierno
    '01-23', //Vacaciones Invierno
    '01-24', //Vacaciones Invierno
    '01-25', //Vacaciones Invierno   
];
$fechaIngresada_md = date("m-d", strtotime($fecha));
if (in_array($fechaIngresada_md, $diasNoLaborables)) {
    header("Location: peticiones.php?mensaje6=fechaNoLaborable");
    exit;
}

// Meses no laborables (junio/julio)
$mes = date("m", strtotime($fecha));
if ($mes === "06" || $mes === "07") {
    header("Location: peticiones.php?mensaje6=fechaNoLaborable");
    exit;
}

// Verificar solapamiento con peticiones ACEPTADAS
// Notar: tu condición original es válida para chequear intervalos (overlap)
$sqlCheckOverlap = "
    SELECT 1 FROM peticiones
    WHERE id_espacio = ?
      AND fecha = ?
      AND estado_peticion = 'Aceptada'
      AND (hora_inicio < ? AND hora_fin > ?)
    LIMIT 1
";
$stmtOverlap = $conexion->prepare($sqlCheckOverlap);
$stmtOverlap->bind_param("isss", $idespacio, $fecha, $horaf, $horai);
$stmtOverlap->execute();
$resOverlap = $stmtOverlap->get_result();
if ($resOverlap && $resOverlap->num_rows > 0) {
    header("Location: peticiones.php?mensaje5=ocupado");
    exit;
}
$stmtOverlap->close();

// === TRANSACCIÓN: insertar petición y luego solicitud_mobiliario ===
try {
    $conexion->begin_transaction();

    // Inserción en peticiones
    // ATENCIÓN: usa el nombre de columna que tengas en tu DB. Aquí usamos "nombreevento" 
    // porque es tu query original; si tu columna se llama "nombre_evento", cámbialo.
    $sqlInsertPet = "INSERT INTO peticiones (id_espacio, id_usuario, nombreevento, fecha, hora_inicio, hora_fin, peticion, fecha_peticion, estado_peticion)
                     VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'Procesando')";
    $stmtInsertPet = $conexion->prepare($sqlInsertPet);
    $stmtInsertPet->bind_param("iisssss", $idespacio, $id_usuario, $nombreEvento, $fecha, $horai, $horaf, $detalles);
    $stmtInsertPet->execute();
    $id_peticion = $conexion->insert_id;
    $stmtInsertPet->close();

    // Procesar mobiliario: solo los que vienen en mobiliario[] (checkbox marcados)
    $mobiliariosMarcados = $_POST['mobiliario'] ?? []; // array de ids (strings)
    $cantidades = $_POST['cantidad'] ?? []; // asociativo: id => cantidad

    // Prepared statements: obtener disponibilidad e insertar solicitud_mobiliario
    $stmtGetDisp = $conexion->prepare("SELECT unidades_disponibles FROM mobiliario WHERE id_mobiliario = ?");
    $stmtInsertSol = $conexion->prepare("INSERT INTO solicitud_mobiliario (id_mobiliario, id_peticion, cantidad) VALUES (?, ?, ?)");

    foreach ($mobiliariosMarcados as $id_mob_raw) {
        $id_mob = (int)$id_mob_raw;
        // si no viene cantidad, tomamos 0 (por hidden)
        $cantidad = isset($cantidades[$id_mob]) ? (int)$cantidades[$id_mob] : 0;
        if ($cantidad < 0) $cantidad = 0; // sanitización

        // Verificar disponibilidad (opcional pero recomendado)
        $stmtGetDisp->bind_param("i", $id_mob);
        $stmtGetDisp->execute();
        $resDisp = $stmtGetDisp->get_result();
        $rowDisp = $resDisp->fetch_assoc();
        $disp = $rowDisp ? (int)$rowDisp['unidades_disponibles'] : 0;

        if ($cantidad > $disp) {
            // Si pides más de lo disponible, hacemos rollback y redirigimos con mensaje
            $conexion->rollback();
            header("Location: peticiones.php?mensaje=disponibilidadInsuficiente");
            exit;
        }

        // Insertar en solicitud_mobiliario (permitimos cantidad = 0 para registrar que traen propio)
        $stmtInsertSol->bind_param("iii", $id_mob, $id_peticion, $cantidad);
        $stmtInsertSol->execute();
    }

    $stmtGetDisp->close();
    $stmtInsertSol->close();

    // Todo OK: commit
    $conexion->commit();

    header("Location: peticiones.php?mensaje=success");
    exit;

} catch (Exception $e) {
    // Si ocurre cualquier error, revertimos
    if ($conexion->in_transaction) $conexion->rollback();
    // Loguea $e->getMessage() en un log real; aquí devolvemos un mensaje controlado.
    header("Location: peticiones.php?mensaje=error");
    exit;
}

