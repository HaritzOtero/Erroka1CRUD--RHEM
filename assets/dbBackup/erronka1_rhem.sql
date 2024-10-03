-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-10-2024 a las 08:49:06
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
(1, 'Zebirsegurtasuna', 'Hackeatu eta tryhackme super guapo'),
(2, 'DAW', 'Desarrollo de aplicaciones web CSS HTML JAVASCRIPT');

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
(13, 'bbb', 'bbb', 'bbb@gmail.com', 'user', '$2y$10$H/xInLm6Ivo0x2GihtMz1.2WClZrLgWU/3GPw92/lteX1Xv0n7jSW');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `kurtsoak`
--
ALTER TABLE `kurtsoak`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
