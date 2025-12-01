<?php
include 'conexionBD.php';

if (isset($_POST['id'], $_POST['estado'])) {
    $id = intval($_POST['id']);
    $estado = $_POST['estado'];

    // Si se quiere aceptar la petición
    if ($estado === 'Aceptada') {
        // Obtener los datos de la petición actual
        $consulta = $conexion->prepare("SELECT fecha, hora_inicio, hora_fin, id_espacio FROM peticiones WHERE id_peticion = ?");
        if (!$consulta) {
            die("Error en prepare 1: " . $conexion->error);
        }

        $consulta->bind_param('i', $id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        if ($resultado->num_rows === 0) {
            echo "Petición no encontrada.";
            exit;
        }

        $datos = $resultado->fetch_assoc();
        $fecha = $datos['fecha'];
        $inicio = $datos['hora_inicio'];
        $fin = $datos['hora_fin'];
        $espacio = $datos['id_espacio'];

        // Verificar conflictos con otras peticiones aceptadas
        $verificar = $conexion->prepare("
            SELECT * FROM peticiones 
            WHERE id_espacio = ? 
              AND fecha = ? 
              AND estado_peticion = 'Aceptada'
              AND (
                (hora_inicio < ? AND hora_fin > ?) OR
                (hora_inicio >= ? AND hora_inicio < ?)
              )
        ");

        if (!$verificar) {
            die("Error en prepare 2: " . $conexion->error);
        }

        // explicación:
        // hora_inicio < fin_actual AND hora_fin > inicio_actual --> se solapan
        $verificar->bind_param('isssss', $espacio, $fecha, $fin, $inicio, $inicio, $fin);
        $verificar->execute();
        $conflictos = $verificar->get_result();

        if ($conflictos->num_rows > 0) {
            echo "⚠️ Ya existe un evento aceptado en ese espacio, fecha y horario.";
            exit;
        }
    }

    // No hay conflicto, o es un rechazo
    $stmt = $conexion->prepare("UPDATE peticiones SET estado_peticion = ? WHERE id_peticion = ?");
    if (!$stmt) {
        die("Error en prepare 3: " . $conexion->error);
    }

    $stmt->bind_param('si', $estado, $id);

    if ($stmt->execute()) {
        echo "Estado actualizado a $estado.";
    } else {
        echo "❌ Error al actualizar: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "❌ Datos no válidos.";
}
?>
