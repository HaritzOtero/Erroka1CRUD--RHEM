-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-10-2024 a las 10:14:01
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
-- Base de datos: `erronka1_rhem`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kurtsoak`
--

CREATE TABLE `kurtsoak` (
  `id` int(11) NOT NULL,
  `izena` varchar(30) NOT NULL,
  `deskripzioa` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `kurtsoak`
--

INSERT INTO `kurtsoak` (`id`, `izena`, `deskripzioa`) VALUES
(1, 'Zibersegurtasuna', 'Hackeatu eta tryhackme super guapo'),
(2, 'DAW', 'Desarrollo de aplicaciones web CSS HTML JAVASCRIPT'),
(3, 'DAM', 'Hainbat entornoendako aplikazioen garapena');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariokurtsoak`
--

CREATE TABLE `usuariokurtsoak` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `kurtso_id` int(11) NOT NULL,
  `matrikulazio_data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuariokurtsoak`
--

INSERT INTO `usuariokurtsoak` (`id`, `usuario_id`, `kurtso_id`, `matrikulazio_data`) VALUES
(1, 12, 2, '2024-10-03 09:12:50'),
(2, 15, 1, '2024-10-03 09:18:24'),
(3, 16, 1, '2024-10-03 09:21:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `izena` varchar(30) NOT NULL,
  `abizena` varchar(30) NOT NULL,
  `email` varchar(60) NOT NULL,
  `mota` varchar(10) NOT NULL,
  `pasahitza` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `izena`, `abizena`, `email`, `mota`, `pasahitza`) VALUES
(9, 'Admin', 'Admin', 'admin@uni.eus', 'admin', '$2y$10$8mm4M.5yC8ImUBj09Uff8.ZSh9aXPCYvtfEEdNY/1C4wBh5RAcLL.'),
(12, 'Rauw', 'Alejandro', 'rauw@gmail.com', 'user', '$2y$10$Bu3ct.0YWrSN6oPqsLARc.mrWxVM7SJCxSajlZNuw6v8cRGQdHcPa'),
(15, 'Haritz', 'Otero', 'haritzotero@gmail.com', 'user', '$2y$10$lwMCPHUJQ6GWiNb53Xwvl.JivH.icwvVCNw6BVKXMO58EfV.gRSPm'),
(16, 'bbb', 'bbb', 'bbb@gmail.com', 'user', '$2y$10$I7aNYKYQpCPtP39aynKYAuEIVvMfy8hmTgJHWcpHOxhQl/eahxmEa');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `kurtsoak`
--
ALTER TABLE `kurtsoak`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuariokurtsoak`
--
ALTER TABLE `usuariokurtsoak`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`),
  ADD KEY `kurtso_id` (`kurtso_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `kurtsoak`
--
ALTER TABLE `kurtsoak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuariokurtsoak`
--
ALTER TABLE `usuariokurtsoak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuariokurtsoak`
--
ALTER TABLE `usuariokurtsoak`
  ADD CONSTRAINT `usuariokurtsoak_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuariokurtsoak_ibfk_2` FOREIGN KEY (`kurtso_id`) REFERENCES `kurtsoak` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
