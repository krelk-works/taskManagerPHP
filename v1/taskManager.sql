-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Temps de generació: 21-02-2024 a les 12:50:55
-- Versió del servidor: 8.0.36-0ubuntu0.22.04.1
-- Versió de PHP: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dades: `taskManager`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `tasks`
--

CREATE TABLE `tasks` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(150) NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'Not finished'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Bolcament de dades per a la taula `tasks`
--

INSERT INTO `tasks` (`id`, `name`, `description`, `status`) VALUES
(1, 'Test', 'Esto es un texto de ejemplo para ver si conseguimos acceder a la base de datos', 'Not finished'),
(2, 'Test 2', 'Esto es una pruba numero 2 de inserción de datos a la base de datos', 'Not finished'),
(3, 'Test 3', 'Esta es la tercera prueba de agregado de tareas en la base de datos', 'Not finished'),
(4, 'Hola', 'Mundo', 'Not finished'),
(5, 'Tarea de prueba', 'Ejemplo de descripcion que irá a base de datos', 'Finished'),
(7, 'Prueba de tarea', 'Esta tarea es una prueba', 'Not finished');

--
-- Índexs per a les taules bolcades
--

--
-- Índexs per a la taula `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per les taules bolcades
--

--
-- AUTO_INCREMENT per la taula `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
