-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-10-2025 a las 21:55:13
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
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `enlace` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`id`, `titulo`, `imagen`, `contenido`, `fecha`, `enlace`) VALUES
(1, 'Identidad, memoria y futuro', 'img/img_noti3.jpeg', 'Hoy no solo celebramos la independencia, renovamos el compromiso de construir una patria libre\r\n    desde la educación....', '2025-07-20', 'noticias/noti1.html'),
(2, 'Tradición, Cultura y Juventud en Marcha', 'img/noti0.jpeg', 'Hoy abrimos un espacio para reflexionar sobre el valor de nuestra historia y el poder\r\n                      transformador de la educación como pilar de una nación libre y soberana......', '2025-09-15', 'noticias/noti2.html'),
(3, 'IV OLIMPIADAS MATEMÁTICAS 2025', 'img/img_noti8.jpeg', 'Talento y pasión se encontraron en una competencia que celebró el poder del pensamiento lógico y\r\n                      el talento inato que tiene el colegio po....', '2025-09-19', 'noticias/noti3.html'),
(4, 'Inspiración que transforma', 'img/img.noti6.jpeg', 'Emilio García nos recordó que el éxito se construye con valores, disciplina y educación integral\r\n                      asi comenzando un....', '2025-09-15', 'noticias/noti4.html'),
(5, 'Inspiración que deja huella', 'img/img_noti12.jpeg', 'Julián Cardona compartió su historia de esfuerzo y visión, inspirando a nuestros jóvenes a creer\r\n                      en sus sueños, trabajar con pasión y construir su propio camino hacia el éxito....', '2025-09-15', 'noticias/noti5.html'),
(6, 'FERIA DE LA CIENCIA', 'img/img_noti15.jpeg', 'Nuestros estudiantes nos llevaron en un viaje al pasado, reviviendo a los grandes científicos que\r\n            transformaron el mundo con sus ideas.....', '2025-10-16', 'noticias/noti6.html'),
(7, '!!Manos a la obra!!', 'img/img_sena1.jpeg', 'La visita a la Planta de Chocolate permitió a los jóvenes aplicar conocimientos, descubrir\r\n                      procesos\r\n                      industriales y valorar el impacto del trabajo en equipo y la calidad.\r\n                      ....', '2025-08-21', 'noticias/noticiassena/sena1.html'),
(8, 'Tecnología con propósito', 'img/img_sena2.jpeg', 'Nuestros estudiantes vivieron la tecnología como herramienta de\r\n                      aprendizaje y construcción de futuro con visión crítica y compromiso ciudadano\r\n                      ....', '2023-03-15', 'noticias/noticiassena/sena2.html');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
(1, '¿Qué es este proyecto?', 'periodico, proyecto, periódico, escolar, digital, expresiones', 'Este proyecto es el periódico escolar digital de la Institución Educativa San Luis Gonzaga, llamado EXPRESIONES. Tiene como objetivo informar a la comunidad educativa sobre noticias, eventos, proyectos y logros del colegio.', 1),
(2, '¿Cómo se llama el periódico escolar?', 'nombre, titulo, periódico, expresiones, periodico escolar', 'El periódico escolar se llama EXPRESIONES.', 1),
(3, '¿Qué institución representa el proyecto?', 'institucion, colegio, escuela, san luis gonzaga, sede', 'El proyecto pertenece a la Institución Educativa San Luis Gonzaga, en articulación con el SENA.', 1),
(4, '¿Qué es SENA?', 'sena, tecnica, articulacion, proyecto sena', 'El SENA participa en la formación técnica y tecnológica del proyecto, apoyando los proyectos de innovación y la articulación con la educación.', 1),
(5, '¿Quiénes son los creadores?', 'creadores, autores, equipo, programadores, quienes hicieron el proyecto', 'El proyecto fue realizado por Ashley Eloisa Rivera Maya, Sara Michel Holguín Galeano, Kevin Alejandro Gonzales Cartagena y Valentina Mejía Yepez.', 1),
(6, '¿Qué noticias publican?', 'noticias, publicaciones, que publican, periodico', 'El periódico informa sobre noticias institucionales, eventos, logros estudiantiles, proyectos SENA, actividades culturales y hechos importantes de la comunidad educativa.', 1),
(7, '¿Dónde están las noticias?', 'noticias, pagina noticias, ver noticias, noticias html, verlas', 'Puedes entrar a la sección de noticias desde la página principal o desde la página de noticias del sitio.', 1),
(8, '¿Qué es la historia del colegio?', 'historia, reseña, san luis gonzaga, antecedentes', 'La Institución Educativa San Luis Gonzaga fue fundada en 1904 y se ha consolidado como una de las instituciones más antiguas y reconocidas del occidente antioqueño, con un legado de educación, disciplina y comunidad.', 1),
(9, '¿Qué proyectos existen?', 'proyectos, sena, proyectos sena', 'El periódico destaca proyectos institucionales, comunitarios y de articulación con el SENA, como actividades técnicas, innovación, ciencia, creatividad y formación.', 1),
(10, '¿Cómo puedo contactar con la institución?', 'contacto, comunicar, institucional, telefono, correo', 'Puedes encontrar la información de contacto en la sección de contacto del sitio web y en la página institucional relacionada con la escuela.', 1),
(11, '¿Qué hace Ciro?', 'ciro, asistente virtual, chatbot, ayuda', 'Ciro es el asistente virtual del periódico escolar. Puede ayudarte con información sobre noticias, proyectos, historia, nosotros y contacto del proyecto.', 1),
(12, '¿Quiénes son nosotros?', 'nosotros, quienes somos, equipo, colegio', 'Somos la comunidad educativa de la Institución Educativa San Luis Gonzaga, estudiantes, docentes y aprendices que participan activamente en la construcción del periódico escolar.', 1),
(13, '¿Dónde está la galería?', 'galeria, imagenes, fotos, historia', 'La galería e imágenes del proyecto están relacionadas con la historia, la comunidad educativa y los eventos del periódico escolar; también puedes revisarlas en los apartados visuales del sitio.', 1),
(14, '¿Qué significa EXPRESIONES?', 'expresiones, significado', 'Expresiones representa la voz del periódico escolar: comunicación, creatividad, identidad y participación estudiantil.', 1),
(15, '¿Qué tipo de contenido tiene la página?', 'contenido, tipo de contenido, que ofrece', 'La página ofrece noticias, reseñas institucionales, información de proyectos, datos del colegio, créditos del equipo y contenido informativo de la comunidad escolar.', 1),
(16, '¿Cuál es la fecha del proyecto?', 'fecha, año, cuando se hizo', 'El proyecto fue desarrollado en 2025 con la intención de digitalizar y divulgar la información del periódico escolar de la institución.', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
