-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-09-2026 a las 23:06:50
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
-- Base de datos: `proyectoperiodico`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_principal`
--

CREATE TABLE `menu_principal` (
  `id` int(11) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `titulo` varchar(120) NOT NULL,
  `texto` text NOT NULL,
  `enlace` varchar(255) NOT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `menu_principal`
--

INSERT INTO `menu_principal` (`id`, `slug`, `titulo`, `texto`, `enlace`, `orden`, `activo`) VALUES
(1, 'proyectos', 'Proyectos', 'Descubre los proyectos que impulsan la innovaci¾n y el talento de nuestra comunidad.', '#proyectos', 1, 1),
(2, 'noticias', 'Noticias', 'Mantente informado con las novedades, eventos y logros del colegio.', 'html/noticias.html', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--
-- Error leyendo la estructura de la tabla proyectoperiodico.noticias: #1932 - Table &#039;proyectoperiodico.noticias&#039; doesn&#039;t exist in engine
-- Error leyendo datos de la tabla proyectoperiodico.noticias: #1064 - Algo está equivocado en su sintax cerca &#039;FROM `proyectoperiodico`.`noticias`&#039; en la linea 1

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias_admin`
--

CREATE TABLE `noticias_admin` (
  `id` int(11) NOT NULL,
  `titulo` varchar(220) NOT NULL,
  `resumen` text NOT NULL,
  `contenido` longtext NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  `fecha` date NOT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `noticias_admin`
--

INSERT INTO `noticias_admin` (`id`, `titulo`, `resumen`, `contenido`, `imagen`, `enlace`, `fecha`, `orden`, `activo`, `creado_en`) VALUES
(1, 'Identidad, memoria y futuro', 'Reflexi¾n sobre la historia y la identidad de la comunidad educativa.', 'Contenido principal del artÝculo sobre identidad, memoria y futuro.', 'img/img_noti3.jpeg', 'html/noticias.html', '2025-07-20', 1, 1, '2026-09-11 20:17:06'),
(2, 'TecnologÝa con prop¾sito', 'Los estudiantes vivieron la tecnologÝa como herramienta para construir futuro.', 'Contenido principal del artÝculo sobre tecnologÝa con prop¾sito.', 'img/img_sena2.jpeg', 'html/noticias.html', '2025-08-21', 2, 1, '2026-09-11 20:17:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL,
  `pregunta` varchar(255) NOT NULL,
  `palabras_clave` text NOT NULL,
  `respuesta` text NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id`, `pregunta`, `palabras_clave`, `respuesta`, `activa`) VALUES
(1, '┐QuÚ es este proyecto?', 'periodico, proyecto, peri¾dico, escolar, digital, expresiones', 'Este proyecto es el peri¾dico escolar digital de la Instituci¾n Educativa San Luis Gonzaga, llamado EXPRESIONES. Tiene como objetivo informar a la comunidad educativa sobre noticias, eventos, proyectos y logros del colegio.', 1),
(2, '┐C¾mo se llama el peri¾dico escolar?', 'nombre, titulo, peri¾dico, expresiones, periodico escolar', 'El peri¾dico escolar se llama EXPRESIONES.', 1),
(3, '┐QuÚ instituci¾n representa el proyecto?', 'institucion, colegio, escuela, san luis gonzaga, sede', 'El proyecto pertenece a la Instituci¾n Educativa San Luis Gonzaga, en articulaci¾n con el SENA.', 1),
(4, '┐QuÚ es SENA?', 'sena, tecnica, articulacion, proyecto sena', 'El SENA participa en la formaci¾n tÚcnica y tecnol¾gica del proyecto, apoyando los proyectos de innovaci¾n y la articulaci¾n con la educaci¾n.', 1),
(5, '┐QuiÚnes son los creadores?', 'creadores, autores, equipo, programadores, quienes hicieron el proyecto', 'El proyecto fue realizado por Ashley Eloisa Rivera Maya, Sara Michel HolguÝn Galeano, Kevin Alejandro Gonzales Cartagena y Valentina MejÝa Yepez.', 1),
(6, '┐QuÚ noticias publican?', 'noticias, publicaciones, que publican, periodico', 'El peri¾dico informa sobre noticias institucionales, eventos, logros estudiantiles, proyectos SENA, actividades culturales y hechos importantes de la comunidad educativa.', 1),
(7, '┐D¾nde estßn las noticias?', 'noticias, pagina noticias, ver noticias, noticias html, verlas', 'Puedes entrar a la secci¾n de noticias desde la pßgina principal o desde la pßgina de noticias del sitio.', 1),
(8, '┐QuÚ es la historia del colegio?', 'historia, rese±a, san luis gonzaga, antecedentes', 'La Instituci¾n Educativa San Luis Gonzaga fue fundada en 1904 y se ha consolidado como una de las instituciones mßs antiguas y reconocidas del occidente antioque±o, con un legado de educaci¾n, disciplina y comunidad.', 1),
(9, '┐QuÚ proyectos existen?', 'proyectos, sena, proyectos sena', 'El peri¾dico destaca proyectos institucionales, comunitarios y de articulaci¾n con el SENA, como actividades tÚcnicas, innovaci¾n, ciencia, creatividad y formaci¾n.', 1),
(10, '┐C¾mo puedo contactar con la instituci¾n?', 'contacto, comunicar, institucional, telefono, correo', 'Puedes encontrar la informaci¾n de contacto en la secci¾n de contacto del sitio web y en la pßgina institucional relacionada con la escuela.', 1),
(11, '┐QuÚ hace Ciro?', 'ciro, asistente virtual, chatbot, ayuda', 'Ciro es el asistente virtual del peri¾dico escolar. Puede ayudarte con informaci¾n sobre noticias, proyectos, historia, nosotros y contacto del proyecto.', 1),
(12, '┐QuiÚnes son nosotros?', 'nosotros, quienes somos, equipo, colegio', 'Somos la comunidad educativa de la Instituci¾n Educativa San Luis Gonzaga, estudiantes, docentes y aprendices que participan activamente en la construcci¾n del peri¾dico escolar.', 1),
(13, '┐D¾nde estß la galerÝa?', 'galeria, imagenes, fotos, historia', 'La galerÝa e imßgenes del proyecto estßn relacionadas con la historia, la comunidad educativa y los eventos del peri¾dico escolar; tambiÚn puedes revisarlas en los apartados visuales del sitio.', 1),
(14, '┐QuÚ significa EXPRESIONES?', 'expresiones, significado', 'Expresiones representa la voz del peri¾dico escolar: comunicaci¾n, creatividad, identidad y participaci¾n estudiantil.', 1),
(15, '┐QuÚ tipo de contenido tiene la pßgina?', 'contenido, tipo de contenido, que ofrece', 'La pßgina ofrece noticias, rese±as institucionales, informaci¾n de proyectos, datos del colegio, crÚditos del equipo y contenido informativo de la comunidad escolar.', 1),
(16, '┐Cußl es la fecha del proyecto?', 'fecha, a±o, cuando se hizo', 'El proyecto fue desarrollado en 2025 con la intenci¾n de digitalizar y divulgar la informaci¾n del peri¾dico escolar de la instituci¾n.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(180) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id`, `titulo`, `descripcion`, `imagen`, `enlace`, `orden`, `activo`, `creado_en`) VALUES
(1, 'Proyectos SENA', 'Innovaci¾n y talento de los aprendices en formaci¾n tÚcnica y tecnol¾gica.', 'img/img_noti3.jpeg', '#', 1, 1, '2026-09-11 20:17:06'),
(2, 'Feria de la ciencia', 'Espacios de creatividad, investigaci¾n y experimentaci¾n para la comunidad escolar.', 'img/img_noti15.jpeg', '#', 2, 1, '2026-09-11 20:17:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_admin`
--

CREATE TABLE `usuarios_admin` (
  `id` int(11) NOT NULL,
  `usuario` varchar(80) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_admin`
--

INSERT INTO `usuarios_admin` (`id`, `usuario`, `nombre`, `password_hash`, `activo`, `creado_en`) VALUES
(1, 'admin', 'Administrador', '$2y$10$J/d3Vx28XkXP9XNQ9x8YXenDjkbgnB5eZfo5SgmF5MNHjO94nIwiG', 1, '2026-09-11 20:17:06');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `menu_principal`
--
ALTER TABLE `menu_principal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `noticias_admin`
--
ALTER TABLE `noticias_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios_admin`
--
ALTER TABLE `usuarios_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `menu_principal`
--
ALTER TABLE `menu_principal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `noticias_admin`
--
ALTER TABLE `noticias_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios_admin`
--
ALTER TABLE `usuarios_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
