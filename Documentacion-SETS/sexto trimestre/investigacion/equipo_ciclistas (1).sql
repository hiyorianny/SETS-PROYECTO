-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-05-2025 a las 15:33:00
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
-- Base de datos: `equipo_ciclistas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciclistas`
--

CREATE TABLE `ciclistas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `edad` int(11) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `especialidad` varchar(100) NOT NULL,
  `salario` decimal(10,2) NOT NULL,
  `peso` decimal(5,2) NOT NULL,
  `altura` decimal(5,2) NOT NULL,
  `potencia_maxima` int(11) DEFAULT NULL,
  `vo2_max` decimal(5,2) DEFAULT NULL,
  `fecha_contrato` date NOT NULL,
  `equipo_anterior` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ciclistas`
--

INSERT INTO `ciclistas` (`id`, `nombre`, `apellido`, `edad`, `pais`, `especialidad`, `salario`, `peso`, `altura`, `potencia_maxima`, `vo2_max`, `fecha_contrato`, `equipo_anterior`, `created_at`) VALUES
(3, 'Wout', 'van Aert', 29, 'Bélgica', 'Clasicómano', 2500000.00, 78.00, 1.90, 450, 82.00, '2025-05-07', 'Team Jumbo-Visma', '2025-05-06 14:59:48'),
(4, 'Tadej', 'Pogačar', 25, 'Eslovenia', 'Escalador', 9000000.00, 66.00, 1.76, 420, 88.50, '2024-01-01', 'UAE Team Emirates', '2025-05-06 14:59:48'),
(5, 'Alberto', 'Contador', 41, 'España', 'Escalador', 5000000.00, 62.00, 1.76, 410, 89.00, '2017-01-01', 'Trek-Segafredo', '2025-05-06 14:59:48'),
(6, 'Nairo', 'Quintana', 34, 'Colombia', 'Escalador', 1500000.00, 58.00, 1.66, 390, 87.50, '2023-01-01', 'Arkéa-Samsic', '2025-05-06 14:59:48'),
(7, 'Primož', 'Roglič', 34, 'Eslovenia', 'Contrarrelojista', 4500000.00, 65.00, 1.77, 440, 85.00, '2024-01-01', 'Team Jumbo-Visma', '2025-05-06 14:59:48'),
(8, 'Mathieu', 'van der Poel', 29, 'Países Bajos', 'Clasicómano', 3500000.00, 75.00, 1.84, 460, 83.50, '2023-01-01', 'Alpecin-Deceuninck', '2025-05-06 14:59:48'),
(9, 'Remco', 'Evenepoel', 24, 'Bélgica', 'Contrarrelojista', 5000000.00, 61.00, 1.71, 430, 90.00, '2024-01-01', 'Soudal Quick-Step', '2025-05-06 14:59:48'),
(10, 'Jonas', 'Vingegaard', 27, 'Dinamarca', 'Escalador', 5500000.00, 60.50, 1.75, 415, 89.50, '2024-01-01', 'Team Jumbo-Visma', '2025-05-06 14:59:48'),
(11, 'Julian', 'Alaphilippe', 31, 'Francia', 'Clasicómano', 2800000.00, 62.00, 1.73, 445, 84.00, '2023-01-01', 'Soudal Quick-Step', '2025-05-06 14:59:48'),
(12, 'Egan', 'Bernal', 28, 'Colombia', 'Escalador', 2200000.00, 60.00, 1.75, 405, 86.50, '2024-01-01', 'Ineos Grenadiers', '2025-05-06 14:59:48'),
(14, 'Richard', 'Carapaz', 30, 'Ecuador', 'Escalador', 2000000.00, 62.00, 1.70, 400, 86.00, '2024-01-01', 'EF Education-EasyPost', '2025-05-06 14:59:48');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ciclistas`
--
ALTER TABLE `ciclistas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ciclistas`
--
ALTER TABLE `ciclistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
