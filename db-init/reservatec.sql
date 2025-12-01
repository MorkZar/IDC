-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:33065
-- Tiempo de generación: 01-12-2025 a las 22:34:10
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reservatec`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id_admin` int(5) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `ap_paterno` varchar(20) NOT NULL,
  `ap_materno` varchar(20) NOT NULL,
  `correo` varchar(30) NOT NULL,
  `contrasena` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id_admin`, `nombre`, `ap_paterno`, `ap_materno`, `correo`, `contrasena`) VALUES
(1, 'Angel Gabriel', 'Morquecho', 'Pedroza', 'gmorquecho12579@gmail.com', 'angel181103');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `espacios`
--

CREATE TABLE `espacios` (
  `id_espacio` int(1) NOT NULL,
  `nombre_espacio` varchar(20) NOT NULL,
  `descripcion_espacio` varchar(100) NOT NULL,
  `capacidad_espacio` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `espacios`
--

INSERT INTO `espacios` (`id_espacio`, `nombre_espacio`, `descripcion_espacio`, `capacidad_espacio`) VALUES
(1, 'Auditorio', 'Auditorio con butacas para 200 personas, cuenta con ecenario y pantalla para presentacion al frente ', 200),
(2, 'Centro de Computo', 'Laboratorio de computo modernamente equipado.', 40),
(3, 'Aula A1', 'Salon con mesabancos y pizarron.', 45),
(4, 'Cafeteria', 'Cafeteria con mesas sillas y horno microondas.', 25),
(5, 'Lobby', 'Espacio principal del edificio con gran caapcidad, al aire libre.', 300);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mobiliario`
--

CREATE TABLE `mobiliario` (
  `id_mobiliario` int(11) NOT NULL,
  `nombre_mobiliario` varchar(20) NOT NULL,
  `descripcion_mobiliario` varchar(50) NOT NULL,
  `unidades_disponibles` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `mobiliario`
--

INSERT INTO `mobiliario` (`id_mobiliario`, `nombre_mobiliario`, `descripcion_mobiliario`, `unidades_disponibles`) VALUES
(1, 'Proyector', 'proyector con entrada HDMI/VGA, resolución estánda', 5),
(2, 'Microfono', 'Micrófono inalámbrico de mano', 6),
(3, 'Mesa', 'Mesa rectangular de uso múltiple', 10),
(4, 'silla', 'Silla plegable para eventos y aulas', 50),
(5, 'Bocina', 'Bocina portátil con amplificador integrado', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peticiones`
--

CREATE TABLE `peticiones` (
  `id_peticion` int(5) NOT NULL,
  `id_espacio` int(1) NOT NULL,
  `id_usuario` int(1) NOT NULL,
  `nombreEvento` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `peticion` text NOT NULL,
  `fecha_peticion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado_peticion` enum('Aceptada','Rechazada','Procesando','') NOT NULL DEFAULT 'Procesando'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `peticiones`
--

INSERT INTO `peticiones` (`id_peticion`, `id_espacio`, `id_usuario`, `nombreEvento`, `fecha`, `hora_inicio`, `hora_fin`, `peticion`, `fecha_peticion`, `estado_peticion`) VALUES
(1, 2, 1, 'Curso Excel Avanzado', '2025-04-30', '13:08:00', '18:14:00', 'Curso impartido por el ingeniero Edgar Alatorre de excel avanzado, sin conocimiento previo.', '2025-04-11 05:07:32', 'Rechazada'),
(2, 1, 1, 'Conferencia PLC', '2025-05-22', '07:00:00', '15:00:00', 'Conferencia sobre PLC y su importancia en la industria.', '2025-04-11 06:48:29', 'Rechazada'),
(3, 5, 1, 'Expo Servicio Social', '2025-08-29', '10:30:00', '18:30:00', 'Feria del servicio social', '2025-04-11 06:49:53', 'Aceptada'),
(4, 2, 1, 'Certificacion Matlab', '2025-04-29', '15:00:00', '17:00:00', 'Curso Intensivo de MATLAB.', '2025-04-11 06:59:31', 'Rechazada'),
(5, 4, 1, 'Entrega de refrigerios', '2025-05-08', '16:30:00', '18:00:00', 'Entrega de refrigerio para alumnos del curso de programacion.', '2025-04-11 07:31:17', 'Rechazada'),
(6, 2, 2, 'Demostracion de practica MATLAB.', '2025-04-29', '08:45:00', '10:45:00', 'Demostracion de practica MATLAB.', '2025-04-11 07:46:28', 'Rechazada'),
(7, 1, 1, 'SITEC', '2025-04-30', '12:46:00', '16:47:00', 'evento', '2025-04-11 18:47:07', 'Rechazada'),
(8, 1, 1, 'evento', '2025-04-30', '12:55:00', '13:53:00', 'evento', '2025-04-11 18:53:17', 'Procesando'),
(10, 5, 1, 'Torneo Smash Bros', '2025-05-30', '10:30:00', '12:30:00', 'Torneo de smash bros organizado por el CSC', '2025-05-26 18:15:25', 'Aceptada'),
(11, 5, 1, 'Semana de la Ciencia y la Tecnologia', '2025-06-05', '10:00:00', '13:00:00', 'Evento dedicado a exposiciones, talleres y conferencias sobre avances científicos, investigación e innovación tecnológica.', '2025-05-28 16:53:59', 'Rechazada'),
(12, 2, 1, 'Hackaton', '2025-06-09', '08:00:00', '12:00:00', 'Competencia intensiva de desarrollo de software o soluciones digitales para resolver retos propuestos por la industria o comunidad.', '2025-05-28 16:54:50', 'Aceptada'),
(13, 5, 1, 'Expo Emprende', '2025-06-18', '15:00:00', '19:30:00', 'Feria donde estudiantes exhiben sus ideas de negocio, startups o productos desarrollados en sus materias de emprendimiento o incubadoras.', '2025-05-28 16:59:02', 'Rechazada'),
(14, 5, 23, 'Feria Servicio Social', '2025-06-05', '08:00:00', '14:00:00', 'Exposicion de diferentes organizacion donde se podra realzar el sevicio.', '2025-05-28 17:02:35', 'Procesando'),
(15, 2, 23, 'Conferencia Networking', '2025-06-10', '13:00:00', '14:30:00', 'Conferencia de Networking impartida por el experto Eduardo Valdicvia.', '2025-05-28 17:04:32', 'Aceptada'),
(16, 2, 23, 'Convive con La IA', '2025-06-09', '11:00:00', '18:30:00', 'curso introductorio al taller convive con la IA impartida por el dr.Figueroa', '2025-05-28 17:06:46', 'Procesando'),
(17, 1, 1, 'aaaaaa', '2025-10-23', '15:30:00', '15:31:00', 'dddd', '2025-10-13 21:28:02', 'Procesando'),
(18, 2, 1, 'ExpoTec', '2025-12-02', '08:10:00', '15:30:00', 'Evento para que los alumnos conozcan lo que estan pidiendo las empresas como requisitos que debe tener un egresado.', '2025-11-27 21:07:47', 'Aceptada'),
(19, 3, 1, 'Conferencia', '2025-12-04', '15:11:00', '19:15:00', 'Webinar Vive de lo que sabes.', '2025-11-27 21:09:50', 'Aceptada'),
(20, 5, 1, 'ExpoTecnologico2', '2025-12-10', '09:00:00', '13:00:00', 'Expotec', '2025-11-27 21:16:00', ''),
(21, 3, 1, 'Torneo Kahoot', '2025-12-12', '10:20:00', '12:20:00', 'Kahoot', '2025-11-27 21:16:48', ''),
(22, 4, 1, 'Convivencia ExAlumnos', '2025-12-09', '15:38:00', '19:50:00', 'Convivencia Tranquila', '2025-11-27 21:38:29', 'Aceptada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_mobiliario`
--

CREATE TABLE `solicitud_mobiliario` (
  `id_mobiliario` int(11) NOT NULL,
  `id_peticion` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 0 CHECK (`cantidad` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `solicitud_mobiliario`
--

INSERT INTO `solicitud_mobiliario` (`id_mobiliario`, `id_peticion`, `cantidad`) VALUES
(1, 20, 5),
(2, 18, 1),
(3, 22, 1),
(4, 22, 10),
(5, 18, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipousuario`
--

CREATE TABLE `tipousuario` (
  `id_tipousuario` int(1) NOT NULL,
  `tipo_usuario` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipousuario`
--

INSERT INTO `tipousuario` (`id_tipousuario`, `tipo_usuario`) VALUES
(1, 'Alumno'),
(2, 'Docente'),
(3, 'Empresa'),
(4, 'Institucion Externa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(5) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `ap_paterno` varchar(20) NOT NULL,
  `ap_materno` varchar(20) NOT NULL,
  `id_tipousuario` int(1) NOT NULL,
  `nombre_organizacion` varchar(50) DEFAULT NULL,
  `correo` varchar(30) NOT NULL,
  `contrasena` varchar(20) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `ap_paterno`, `ap_materno`, `id_tipousuario`, `nombre_organizacion`, `correo`, `contrasena`, `fecha_creacion`) VALUES
(1, 'Angel Gabriel', 'Morquecho', 'Pedroza', 3, 'Magna', 'gmorquecho12579@gmail.com', 'angel181103', '2025-04-07 18:17:14'),
(2, 'Cristian', 'Morquecho', 'Pedroza', 3, 'TCC', 'alemorquecho1234@gmail.com', 'cristian123', '2025-04-11 07:43:14'),
(22, 'Paola', 'Morquecho', 'Pedroza', 1, '', 'paolamorquecho13579@gmail.com', 'paola123', '2025-04-11 17:42:50'),
(23, 'David Alonso', 'Guerrero', 'Estrada', 1, '', 'gmorquecho13579@gmail.com', 'david123', '2025-04-11 18:58:16'),
(24, 'Angel Gabriel', 'Morquecho', 'Pedroza', 1, '', 'gmorquecho12570@gmail.com', 'anhel1122', '2025-04-11 18:59:05');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `espacios`
--
ALTER TABLE `espacios`
  ADD PRIMARY KEY (`id_espacio`);

--
-- Indices de la tabla `mobiliario`
--
ALTER TABLE `mobiliario`
  ADD PRIMARY KEY (`id_mobiliario`);

--
-- Indices de la tabla `peticiones`
--
ALTER TABLE `peticiones`
  ADD PRIMARY KEY (`id_peticion`),
  ADD KEY `espacios_idespacio` (`id_espacio`),
  ADD KEY `usuarios_idusuario` (`id_usuario`);

--
-- Indices de la tabla `solicitud_mobiliario`
--
ALTER TABLE `solicitud_mobiliario`
  ADD PRIMARY KEY (`id_mobiliario`,`id_peticion`),
  ADD KEY `id_peticion` (`id_peticion`);

--
-- Indices de la tabla `tipousuario`
--
ALTER TABLE `tipousuario`
  ADD PRIMARY KEY (`id_tipousuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `tipousuario_idtipousuario` (`id_tipousuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id_admin` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mobiliario`
--
ALTER TABLE `mobiliario`
  MODIFY `id_mobiliario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `peticiones`
--
ALTER TABLE `peticiones`
  MODIFY `id_peticion` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `peticiones`
--
ALTER TABLE `peticiones`
  ADD CONSTRAINT `espacios_idespacio` FOREIGN KEY (`id_espacio`) REFERENCES `espacios` (`id_espacio`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuarios_idusuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `solicitud_mobiliario`
--
ALTER TABLE `solicitud_mobiliario`
  ADD CONSTRAINT `solicitud_mobiliario_ibfk_1` FOREIGN KEY (`id_mobiliario`) REFERENCES `mobiliario` (`id_mobiliario`),
  ADD CONSTRAINT `solicitud_mobiliario_ibfk_2` FOREIGN KEY (`id_peticion`) REFERENCES `peticiones` (`id_peticion`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `tipousuario_idtipousuario` FOREIGN KEY (`id_tipousuario`) REFERENCES `tipousuario` (`id_tipousuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
