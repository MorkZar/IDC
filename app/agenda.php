<?php
session_start();

if(!isset($_SESSION['usuario'])){
  header("location: inicioSesion1.php");
  session_destroy();
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva TEC</title>
    <link rel="stylesheet" href="css/agenda.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js'></script>
   
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <a href="mainpage.php"><button class="back-btn">&#8592;</button></a>
            <h2>Espacio</h2>
            <ul>
                <li><input type="radio" name="space" value="1" disabled> <span class="auditorio">Auditorio <img src="imagenes/auditorio-ico.png" alt=""></span></li>
                <li><input type="radio" name="space" value="2" disabled> <span class="centro">Centro de Cómputo <img src="imagenes/pantalla-de-computadora.png" alt=""></span></li>
                <li><input type="radio" name="space" value="3" disabled> <span class="aula">Aula A1 <img src="imagenes/aula.png" alt=""></span></li>
                <li><input type="radio" name="space" value="4" disabled> <span class="cafeteria">Cafetería <img src="imagenes/mesa-de-cafe.png" alt=""></span></li>
                <li><input type="radio" name="space" value="5" disabled> <span class="lobby">Lobby <img src="imagenes/lobby-ico.png" alt=""></span></li>
            </ul>
        </aside>
        <main class="calendar-container">
            <div id="calendar"></div>
        </main>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',

                events: 'obtenerEventos.php',

                eventClick: function(info) {
    // Concatenar toda la información del evento en un solo mensaje
    var mensaje = 'Evento: ' + info.event.title + '\n' +
                  'Fecha de inicio: ' + info.event.start + '\n' +
                  'Fecha de fin: ' + info.event.end + '\n' +
                  'Descripción: ' + info.event.extendedProps.descripcion;

    // Mostrar la información del evento en un solo alert
    alert(mensaje);
  }
            });
            calendar.render();
        });
    </script>
</body>
</html>