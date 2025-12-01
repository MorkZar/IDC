<?php
// registrarPeticiones.php (versión limpia — prepared statements, transacción y validaciones)
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: inicioSesion1.php");
    exit;
}

require_once 'conexionBD.php'; // debe definir $conexion (mysqli)

// Habilitar exceptions para mysqli (mejora el manejo de errores)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$usuarioCorreo = $_SESSION['usuario'];

// Obtener id_usuario de forma segura
$stmtUser = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
$stmtUser->bind_param("s", $usuarioCorreo);
$stmtUser->execute();
$resUser = $stmtUser->get_result();
if (!$resUser || $resUser->num_rows === 0) {
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

// Validaciones básicas
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
    '02-03','03-17','09-16','05-01','05-05','05-15','11-17',
    // Vacaciones Semana Santa (ejemplo)
    '04-14','04-15','04-16','04-17','04-18','04-21','04-22','04-23','04-24','04-25',
    // Vacaciones Invierno (ejemplo)
    '12-14','12-15','12-16','12-17','12-18','12-19','12-20','12-21','12-22','12-23',
    '12-24','12-25','12-26','12-27','12-28','12-29','12-30','12-31',
    '01-01','01-02','01-03','01-04','01-05','01-06','01-07','01-08','01-09','01-10',
    // ... puedes completar según necesites
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

    $sqlInsertPet = "INSERT INTO peticiones (id_espacio, id_usuario, nombreevento, fecha, hora_inicio, hora_fin, peticion, fecha_peticion, estado_peticion)
                     VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'Procesando')";
    $stmtInsertPet = $conexion->prepare($sqlInsertPet);
    $stmtInsertPet->bind_param("iisssss", $idespacio, $id_usuario, $nombreEvento, $fecha, $horai, $horaf, $detalles);
    $stmtInsertPet->execute();
    $id_peticion = $conexion->insert_id;
    $stmtInsertPet->close();

    // Procesar mobiliario
    $mobiliariosMarcados = $_POST['mobiliario'] ?? []; // array de ids
    $cantidades = $_POST['cantidad'] ?? [];

    $stmtGetDisp = $conexion->prepare("SELECT unidades_disponibles FROM mobiliario WHERE id_mobiliario = ?");
    $stmtInsertSol = $conexion->prepare("INSERT INTO solicitud_mobiliario (id_mobiliario, id_peticion, cantidad) VALUES (?, ?, ?)");

    foreach ($mobiliariosMarcados as $id_mob_raw) {
        $id_mob = (int)$id_mob_raw;
        $cantidad = isset($cantidades[$id_mob]) ? (int)$cantidades[$id_mob] : 0;
        if ($cantidad < 0) $cantidad = 0;

        $stmtGetDisp->bind_param("i", $id_mob);
        $stmtGetDisp->execute();
        $resDisp = $stmtGetDisp->get_result();
        $rowDisp = $resDisp->fetch_assoc();
        $disp = $rowDisp ? (int)$rowDisp['unidades_disponibles'] : 0;

        if ($cantidad > $disp) {
            $conexion->rollback();
            header("Location: peticiones.php?mensaje=disponibilidadInsuficiente");
            exit;
        }

        $stmtInsertSol->bind_param("iii", $id_mob, $id_peticion, $cantidad);
        $stmtInsertSol->execute();
    }

    $stmtGetDisp->close();
    $stmtInsertSol->close();

    $conexion->commit();

    header("Location: peticiones.php?mensaje=success");
    exit;

} catch (Exception $e) {
    if ($conexion->in_transaction) $conexion->rollback();
    // Loggear $e->getMessage() en un fichero real es recomendado
    header("Location: peticiones.php?mensaje=error");
    exit;
}
?>

