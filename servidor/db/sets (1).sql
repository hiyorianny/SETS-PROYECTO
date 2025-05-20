-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-03-2025 a las 17:34:45
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
-- Base de datos: `sets`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anuncio`
--

CREATE TABLE `anuncio` (
  `idAnuncio` int(11) NOT NULL,
  `titulo` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `fechaPublicacion` date DEFAULT NULL,
  `horaPublicacion` time DEFAULT NULL,
  `persona` int(11) NOT NULL,
  `apart` varchar(222) NOT NULL,
  `img_anuncio` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apartamento`
--

CREATE TABLE `apartamento` (
  `numApartamento` varchar(111) NOT NULL,
  `pisos` varchar(11) NOT NULL,
  `torre` varchar(112) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `apartamento`
--

INSERT INTO `apartamento` (`numApartamento`, `pisos`, `torre`) VALUES
('1001A', '10A', '1A'),
('1001B', '10B', '2B'),
('1001C', '10C', '3C'),
('1001D', '10D', '4D'),
('1001E', '10E', '5E'),
('1001F', '10F', '6F'),
('1001G', '10G', '7G'),
('1001H', '10H', '8H'),
('1001I', '10I', '9I'),
('1001J', '10J', '10J'),
('1002A', '10A', '1A'),
('1002B', '10B', '2B'),
('1002C', '10C', '3C'),
('1002D', '10D', '4D'),
('1002E', '10E', '5E'),
('1002F', '10F', '6F'),
('1002G', '10G', '7G'),
('1002H', '10H', '8H'),
('1002I', '10I', '9I'),
('1002J', '10J', '10J'),
('1003A', '10A', '1'),
('1003B', '10B', '2'),
('1003C', '10C', '3'),
('1003D', '10D', '4'),
('1003E', '10E', '5'),
('1003F', '10F', '6'),
('1003G', '10G', '7'),
('1003H', '10H', '8'),
('1003I', '10I', '9'),
('1003J', '10J', '10'),
('1004A', '10A', '1'),
('1004B', '10B', '2'),
('1004C', '10C', '3'),
('1004D', '10D', '4'),
('1004E', '10E', '5'),
('1004F', '10F', '6'),
('1004G', '10G', '7'),
('1004H', '10H', '8'),
('1004I', '10I', '9'),
('1004J', '10J', '10'),
('101A', '1A', '1'),
('101B', '1B', '2'),
('101C', '1C', '3'),
('101D', '1D', '4'),
('101E', '1E', '5'),
('101F', '1F', '6'),
('101G', '1G', '7'),
('101H', '1H', '8'),
('101I', '1I', '9'),
('101J', '1J', '10'),
('102A', '1A', '1'),
('102B', '1B', '2'),
('102C', '1C', '3'),
('102D', '1D', '4'),
('102E', '1E', '5'),
('102F', '1F', '6'),
('102G', '1G', '7'),
('102H', '1H', '8'),
('102I', '1I', '9'),
('102J', '1J', '10'),
('103A', '1A', '1'),
('103B', '1B', '2'),
('103C', '1C', '3'),
('103D', '1D', '4'),
('103E', '1E', '5'),
('103F', '1F', '6'),
('103G', '1G', '7'),
('103H', '1H', '8'),
('103I', '1I', '9'),
('103J', '1J', '10'),
('104A', '1A', '1'),
('104B', '1B', '2'),
('104C', '1C', '3'),
('104D', '1D', '4'),
('104E', '1E', '5'),
('104F', '1F', '6'),
('104G', '1G', '7'),
('104H', '1H', '8'),
('104I', '1I', '9'),
('104J', '1J', '10'),
('201A', '2A', '1'),
('201B', '2B', '2'),
('201C', '2C', '3'),
('201D', '2D', '4'),
('201E', '2E', '5'),
('201F', '2F', '6'),
('201G', '2G', '7'),
('201H', '2H', '8'),
('201I', '2I', '9'),
('201J', '2J', '10'),
('202A', '2A', '1'),
('202B', '2B', '2'),
('202C', '2C', '3'),
('202D', '2D', '4'),
('202E', '2E', '5'),
('202F', '2F', '6'),
('202G', '2G', '7'),
('202H', '2H', '8'),
('202I', '2I', '9'),
('202J', '2J', '10'),
('203A', '2A', '1'),
('203B', '2B', '2'),
('203C', '2C', '3'),
('203D', '2D', '4'),
('203E', '2E', '5'),
('203F', '2F', '6'),
('203G', '2G', '7'),
('203H', '2H', '8'),
('203I', '2I', '9'),
('203J', '2J', '10'),
('204A', '2A', '1'),
('204B', '2B', '2'),
('204C', '2C', '3'),
('204D', '2D', '4'),
('204E', '2E', '5'),
('204F', '2F', '6'),
('204G', '2G', '7'),
('204H', '2H', '8'),
('204I', '2I', '9'),
('204J', '2J', '10'),
('301A', '3A', '1'),
('301B', '3B', '2'),
('301C', '3C', '3'),
('301D', '3D', '4'),
('301E', '3E', '5'),
('301F', '3F', '6'),
('301G', '3G', '7'),
('301H', '3H', '8'),
('301I', '3I', '9'),
('301J', '3J', '10'),
('302A', '3A', '1'),
('302B', '3B', '2'),
('302C', '3C', '3'),
('302D', '3D', '4'),
('302E', '3E', '5'),
('302F', '3F', '6'),
('302G', '3G', '7'),
('302H', '3H', '8'),
('302I', '3I', '9'),
('302J', '3J', '10'),
('303A', '3A', '1'),
('303B', '3B', '2'),
('303C', '3C', '3'),
('303D', '3D', '4'),
('303E', '3E', '5'),
('303F', '3F', '6'),
('303G', '3G', '7'),
('303H', '3H', '8'),
('303I', '3I', '9'),
('303J', '3J', '10'),
('304A', '3A', '1'),
('304B', '3B', '2'),
('304C', '3C', '3'),
('304D', '3D', '4'),
('304E', '3E', '5'),
('304F', '3F', '6'),
('304G', '3G', '7'),
('304H', '3H', '8'),
('304I', '3I', '9'),
('304J', '3J', '10'),
('401A', '4A', '1'),
('401B', '4B', '2'),
('401C', '4C', '3'),
('401D', '4D', '4'),
('401E', '4E', '5'),
('401F', '4F', '6'),
('401G', '4G', '7'),
('401H', '4H', '8'),
('401I', '4I', '9'),
('401J', '4J', '10'),
('402A', '4A', '1'),
('402B', '4B', '2'),
('402C', '4C', '3'),
('402D', '4D', '4'),
('402E', '4E', '5'),
('402F', '4F', '6'),
('402G', '4G', '7'),
('402H', '4H', '8'),
('402I', '4I', '9'),
('402J', '4J', '10'),
('403A', '4A', '1'),
('403B', '4B', '2'),
('403C', '4C', '3'),
('403D', '4D', '4'),
('403E', '4E', '5'),
('403F', '4F', '6'),
('403G', '4G', '7'),
('403H', '4H', '8'),
('403I', '4I', '9'),
('403J', '4J', '10'),
('404A', '4A', '1'),
('404B', '4B', '2'),
('404C', '4C', '3'),
('404D', '4D', '4'),
('404E', '4E', '5'),
('404F', '4F', '6'),
('404G', '4G', '7'),
('404H', '4H', '8'),
('404I', '4I', '9'),
('404J', '4J', '10'),
('501A', '5A', '1'),
('501B', '5B', '2'),
('501C', '5C', '3'),
('501D', '5D', '4'),
('501E', '5E', '5'),
('501F', '5F', '6'),
('501G', '5G', '7'),
('501H', '5H', '8'),
('501I', '5I', '9'),
('501J', '5J', '10'),
('502A', '5A', '1'),
('502B', '5B', '2'),
('502C', '5C', '3'),
('502D', '5D', '4'),
('502E', '5E', '5'),
('502F', '5F', '6'),
('502G', '5G', '7'),
('502H', '5H', '8'),
('502I', '5I', '9'),
('502J', '5J', '10'),
('503A', '5A', '1'),
('503B', '5B', '2'),
('503C', '5C', '3'),
('503D', '5D', '4'),
('503E', '5E', '5'),
('503F', '5F', '6'),
('503G', '5G', '7'),
('503H', '5H', '8'),
('503I', '5I', '9'),
('503J', '5J', '10'),
('504A', '5A', '1'),
('504B', '5B', '2'),
('504C', '5C', '3'),
('504D', '5D', '4'),
('504E', '5E', '5'),
('504F', '5F', '6'),
('504G', '5G', '7'),
('504H', '5H', '8'),
('504I', '5I', '9'),
('504J', '5J', '10'),
('601A', '6A', '1'),
('601B', '6B', '2'),
('601C', '6C', '3'),
('601D', '6D', '4'),
('601E', '6E', '5'),
('601F', '6F', '6'),
('601G', '6G', '7'),
('601H', '6H', '8'),
('601I', '6I', '9'),
('601J', '6J', '10'),
('602A', '6A', '1'),
('602B', '6B', '2'),
('602C', '6C', '3'),
('602D', '6D', '4'),
('602E', '6E', '5'),
('602F', '6F', '6'),
('602G', '6G', '7'),
('602H', '6H', '8'),
('602I', '6I', '9'),
('602J', '6J', '10'),
('603A', '6A', '1'),
('603B', '6B', '2'),
('603C', '6C', '3'),
('603D', '6D', '4'),
('603E', '6E', '5'),
('603F', '6F', '6'),
('603G', '6G', '7'),
('603H', '6H', '8'),
('603I', '6I', '9'),
('603J', '6J', '10'),
('604A', '6A', '1'),
('604B', '6B', '2'),
('604C', '6C', '3'),
('604D', '6D', '4'),
('604E', '6E', '5'),
('604F', '6F', '6'),
('604G', '6G', '7'),
('604H', '6H', '8'),
('604I', '6I', '9'),
('604J', '6J', '10'),
('701A', '7A', '1'),
('701B', '7B', '2'),
('701C', '7C', '3'),
('701D', '7D', '4'),
('701E', '7E', '5'),
('701F', '7F', '6'),
('701G', '7G', '7'),
('701H', '7H', '8'),
('701I', '7I', '9'),
('701J', '7J', '10'),
('702A', '7A', '1'),
('702B', '7B', '2'),
('702C', '7C', '3'),
('702D', '7D', '4'),
('702E', '7E', '5'),
('702F', '7F', '6'),
('702G', '7G', '7'),
('702H', '7H', '8'),
('702I', '7I', '9'),
('702J', '7J', '10'),
('703A', '7A', '1'),
('703B', '7B', '2'),
('703C', '7C', '3'),
('703D', '7D', '4'),
('703E', '7E', '5'),
('703F', '7F', '6'),
('703G', '7G', '7'),
('703H', '7H', '8'),
('703I', '7I', '9'),
('703J', '7J', '10'),
('704A', '7A', '1'),
('704B', '7B', '2'),
('704C', '7C', '3'),
('704D', '7D', '4'),
('704E', '7E', '5'),
('704F', '7F', '6'),
('704G', '7G', '7'),
('704H', '7H', '8'),
('704I', '7I', '9'),
('704J', '7J', '10'),
('801A', '8A', '1'),
('801B', '8B', '2'),
('801C', '8C', '3'),
('801D', '8D', '4'),
('801E', '8E', '5'),
('801F', '8F', '6'),
('801G', '8G', '7'),
('801H', '8H', '8'),
('801I', '8I', '9'),
('801J', '8J', '10'),
('802A', '8A', '1'),
('802B', '8B', '2'),
('802C', '8C', '3'),
('802D', '8D', '4'),
('802E', '8E', '5'),
('802F', '8F', '6'),
('802G', '8G', '7'),
('802H', '8H', '8'),
('802I', '8I', '9'),
('802J', '8J', '10'),
('803A', '8A', '1'),
('803B', '8B', '2'),
('803C', '8C', '3'),
('803D', '8D', '4'),
('803E', '8E', '5'),
('803F', '8F', '6'),
('803G', '8G', '7'),
('803H', '8H', '8'),
('803I', '8I', '9'),
('803J', '8J', '10'),
('804A', '8A', '1'),
('804B', '8B', '2'),
('804C', '8C', '3'),
('804D', '8D', '4'),
('804E', '8E', '5'),
('804F', '8F', '6'),
('804G', '8G', '7'),
('804H', '8H', '8'),
('804I', '8I', '9'),
('804J', '8J', '10'),
('901A', '9A', '1'),
('901B', '9B', '2'),
('901C', '9C', '3'),
('901D', '9D', '4'),
('901E', '9E', '5'),
('901F', '9F', '6'),
('901G', '9G', '7'),
('901H', '9H', '8'),
('901I', '9I', '9'),
('901J', '9J', '10'),
('902A', '9A', '1'),
('902B', '9B', '2'),
('902C', '9C', '3'),
('902D', '9D', '4'),
('902E', '9E', '5'),
('902F', '9F', '6'),
('902G', '9G', '7'),
('902H', '9H', '8'),
('902I', '9I', '9'),
('902J', '9J', '10'),
('903A', '9A', '1'),
('903B', '9B', '2'),
('903C', '9C', '3'),
('903D', '9D', '4'),
('903E', '9E', '5'),
('903F', '9F', '6'),
('903G', '9G', '7'),
('903H', '9H', '8'),
('903I', '9I', '9'),
('903J', '9J', '10'),
('904A', '9A', '1'),
('904B', '9B', '2'),
('904C', '9C', '3'),
('904D', '9D', '4'),
('904E', '9E', '5'),
('904F', '9F', '6'),
('904G', '9G', '7'),
('904H', '9H', '8'),
('904I', '9I', '9'),
('904J', '9J', '10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita`
--

CREATE TABLE `cita` (
  `idcita` int(11) NOT NULL,
  `fechacita` date NOT NULL,
  `horacita` time NOT NULL,
  `tipocita` varchar(45) NOT NULL,
  `apa` varchar(113) NOT NULL,
  `respuesta` varchar(100) NOT NULL,
  `estado` enum('pendiente','respondida','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cita`
--

INSERT INTO `cita` (`idcita`, `fechacita`, `horacita`, `tipocita`, `apa`, `respuesta`, `estado`) VALUES
(3, '2025-03-17', '10:00:00', 'Reclamo', '903A', '', 'pendiente'),
(4, '2025-03-22', '16:00:00', 'Duda', '404A', '', 'pendiente'),
(5, '2025-03-30', '11:00:00', 'Reclamo', '404A', '', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactarnos`
--

CREATE TABLE `contactarnos` (
  `idcontactarnos` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contactarnos`
--

INSERT INTO `contactarnos` (`idcontactarnos`, `nombre`, `correo`, `telefono`, `comentario`, `fecha`) VALUES
(1, 'Eddy Merckx', 'eddy.merckx@residencial.com', '321-456-7890', 'No puedo iniciar sesión, dice que el usuario o la clave son incorrectos.', '2024-10-10 19:15:00'),
(2, 'Bernard Hinault', 'bernard.hinault@residencial.com', '321-456-7891', 'Estoy intentando registrarme, pero no recibo el correo de confirmación.', '2024-10-11 20:25:00'),
(3, 'Miguel Induráin', 'miguel.indurain@residencial.com', '321-456-7892', '¿Cómo puedo saber si hay disponibilidad en las zonas comunes antes de registrarme?', '2024-10-12 21:35:00'),
(4, 'Fausto Coppi', 'fausto.coppi@residencial.com', '321-456-7893', 'Tengo problemas para recuperar mi clave, no me llega el correo de recuperación.', '2024-10-13 22:45:00'),
(5, 'Jacques Anquetil', 'jacques.anquetil@residencial.com', '321-456-7894', '¿El sistema tiene una opción para reservar parqueaderos para visitantes?', '2024-10-15 00:00:00'),
(6, 'Lance Armstrong', 'lance.armstrong@residencial.com', '321-456-7895', 'Al intentar registrarme, el sistema me da un error de datos duplicados.', '2024-10-16 01:10:00'),
(7, 'Alberto Contador', 'alberto.contador@residencial.com', '321-456-7896', '¿Puedo usar el sistema para agendar eventos en la zona social?', '2024-10-17 02:20:00'),
(8, 'Chris Froome', 'chris.froome@residencial.com', '321-456-7897', '¿Cuáles son los pasos para registrarse en la plataforma?', '2024-10-18 03:30:00'),
(9, 'Marco Pantani', 'marco.pantani@residencial.com', '321-456-7898', 'No puedo acceder a la página desde mi teléfono móvil, ¿hay soporte para dispositivos móviles?', '2024-10-19 04:40:00'),
(10, 'Gino Bartali', 'gino.bartali@residencial.com', '321-456-7899', 'Quisiera saber si se pueden hacer reservas para la cancha de fútbol antes de iniciar sesión.', '2024-10-20 05:50:00'),
(11, 'gv', 'fvvq@gmail.com', '23434', 'dsfgd', '2024-10-22 16:54:07'),
(22, 'scf', 'saqq@gmail.com', '31232', 'assaa', '2024-10-22 16:55:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_peatonal`
--

CREATE TABLE `ingreso_peatonal` (
  `idIngreso_Peatonal` int(11) NOT NULL,
  `personasIngreso` varchar(45) NOT NULL,
  `horaFecha` datetime NOT NULL,
  `documento` varchar(2009) NOT NULL,
  `tipo_ingreso` enum('vehiculo','visitante') NOT NULL,
  `placa` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingreso_peatonal`
--

INSERT INTO `ingreso_peatonal` (`idIngreso_Peatonal`, `personasIngreso`, `horaFecha`, `documento`, `tipo_ingreso`, `placa`) VALUES
(2, 'Cristiano Ronaldo', '2025-02-22 21:16:00', 'cc.3232323223', 'visitante', ''),
(4, 'lionel messi', '2025-03-26 01:25:00', 'cc.23323232', 'visitante', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `idPagos` int(11) NOT NULL,
  `pagoPor` varchar(100) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `mediopago` enum('Efectivo','Transferencia','Tarjeta','Cheque','Otro') NOT NULL,
  `apart` varchar(112) NOT NULL,
  `fechaPago` date NOT NULL,
  `estado` enum('Pendiente','Pagado','Vencido') NOT NULL DEFAULT 'Pendiente',
  `referenciaPago` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`idPagos`, `pagoPor`, `cantidad`, `mediopago`, `apart`, `fechaPago`, `estado`, `referenciaPago`) VALUES
(4, 'Reserva Zona BBQ', 50.00, 'Tarjeta', '102A', '2025-02-22', 'Pendiente', 'TARJ567890'),
(10, 'LIMITE DE TIEMPO EN PARQUEADERO', 15.00, 'Efectivo', '903A', '2025-03-14', 'Pendiente', 'PARKING2323');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parqueadero`
--

CREATE TABLE `parqueadero` (
  `id_parqueadero` int(11) NOT NULL,
  `numero_parqueadero` int(11) NOT NULL,
  `id_apartamento` varchar(113) DEFAULT NULL,
  `uso` datetime NOT NULL,
  `disponibilidad` varchar(222) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `parqueadero`
--

INSERT INTO `parqueadero` (`id_parqueadero`, `numero_parqueadero`, `id_apartamento`, `uso`, `disponibilidad`) VALUES
(1, 1, '1002B', '2025-02-23 00:25:06', 'SI ESTA DISPONIBLE'),
(2, 2, '202D', '2025-02-23 00:25:27', 'NO ESTA DISPONIBLE'),
(3, 3, '603G', '2025-02-24 07:25:45', 'SI ESTA DISPONIBLE'),
(4, 4, '901J', '2025-02-23 18:32:56', 'NO ESTA DISPONIBLE'),
(5, 5, '302G', '2025-02-26 18:32:56', 'SI ESTA DISPONIBLE'),
(6, 6, '1001F', '2025-02-23 00:33:53', 'SI ESTA DISPONIBLE'),
(7, 7, '904H', '2025-02-23 00:33:53', 'NO ESTA DISPONIBLE'),
(8, 8, '404F', '2025-02-23 00:36:20', 'SI ESTA DISPONIBLE'),
(9, 9, '1002A', '2025-02-23 00:36:20', 'SI ESTA DISPONIBLE'),
(10, 10, '1003A', '2025-02-23 00:37:17', 'NO ESTA DISPONIBLE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE `registro` (
  `id_Registro` int(11) NOT NULL,
  `idRol` int(20) NOT NULL,
  `PrimerNombre` varchar(45) NOT NULL,
  `SegundoNombre` varchar(45) DEFAULT NULL,
  `PrimerApellido` varchar(45) NOT NULL,
  `SegundoApellido` varchar(45) DEFAULT NULL,
  `apartamento` varchar(113) NOT NULL,
  `Correo` varchar(45) NOT NULL,
  `Usuario` varchar(45) NOT NULL,
  `Clave` varchar(255) NOT NULL,
  `Id_tipoDocumento` int(11) NOT NULL,
  `numeroDocumento` int(11) NOT NULL,
  `telefonoUno` int(11) NOT NULL,
  `telefonoDos` int(11) DEFAULT NULL,
  `imagenPerfil` varchar(300) DEFAULT NULL,
  `tipo_propietario` enum('dueño','residente','ambos') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`id_Registro`, `idRol`, `PrimerNombre`, `SegundoNombre`, `PrimerApellido`, `SegundoApellido`, `apartamento`, `Correo`, `Usuario`, `Clave`, `Id_tipoDocumento`, `numeroDocumento`, `telefonoUno`, `telefonoDos`, `imagenPerfil`, `tipo_propietario`) VALUES
(74, 2222, 'JUAN', 'r', 'r', 'r', '101A', 'r@gmail.com', 'RONALDO', '$2y$10$tSw/LKjawgMK7eTeE4n6TuebVIvyz7NW5dB66PiZl4PW0yxv19dAG', 1, 2147483647, 2147483647, 2147483647, 'uploads/deed.jpg', 'residente'),
(77, 3333, 'tt', 't', 't', 'tk', '504A', 'y@gmail.com', 'james', '$2y$10$P.Uh.lKUd0AJaHKMlJxOgekub8rMxPnDqQUIT0WilaExYNK4T.NSW', 1, 2147483647, 2147483647, 2147483647, 'uploads/james-2021-613b068e4395e.jpg', 'residente'),
(79, 1111, 'walter', 'heisenberg', 'white', 'salamanca', '101A', 'ww@gmail.com', 'walter', '$2y$10$BUpRApUYA18u8fOryXZr9OEnV6xUaLpPRtAWC9w4VP/MU2Ls/ISgm', 1, 2147483647, 2147483647, 2147483647, 'uploads/Breaking-Bad.jpg', 'dueño'),
(93, 3333, 'lalo', 'Eduardo ', 'Salamanca ', 'Salamanca ', '102A', 'lalo@gmail.com', 'lalo', '$2b$10$vUuhmh7w7KFP3aZdWbllTug4AK1ee60Ey.Vyn4L0lB6XnJHpUmQOe', 1, 1141115783, 2147483647, 2147483647, NULL, 'residente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `Roldescripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `Roldescripcion`) VALUES
(1111, 'admin'),
(2222, 'Guarda de Seguridad'),
(3333, 'residente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_parqueadero`
--

CREATE TABLE `solicitud_parqueadero` (
  `id_solicitud` int(11) NOT NULL,
  `id_apartamento` varchar(113) NOT NULL,
  `parqueadero_visitante` enum('V1','V2','V3','V4','V5','V6','V7','V8','V9','V10') NOT NULL,
  `nombre_visitante` varchar(100) NOT NULL,
  `placaVehiculo` varchar(45) NOT NULL,
  `colorVehiculo` varchar(45) NOT NULL,
  `tipoVehiculo` varchar(100) NOT NULL,
  `modelo` varchar(90) NOT NULL,
  `marca` varchar(100) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_final` datetime NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud_parqueadero`
--

INSERT INTO `solicitud_parqueadero` (`id_solicitud`, `id_apartamento`, `parqueadero_visitante`, `nombre_visitante`, `placaVehiculo`, `colorVehiculo`, `tipoVehiculo`, `modelo`, `marca`, `fecha_inicio`, `fecha_final`, `estado`) VALUES
(1, '1002E', 'V10', 'Tadej Pogachar', '32ds45', 'Blanco', 'Moto', '2002', 'suzuki', '2025-02-27 10:22:13', '2025-02-20 21:22:13', 'aprobado'),
(3, '202A', 'V1', 'PEDRI', 'SDDWE22', 'ROJO', 'CARRO', 'DSDS34', 'DSDSDS', '2025-02-14 12:55:00', '2025-02-26 12:55:00', 'pendiente'),
(8, '903A', 'V10', 'juan fernando quintero', '3erw34', 'rojo y blanco', 'carro', '34', 'ferrrari', '2025-03-14 09:45:00', '2025-03-17 09:45:00', 'pendiente'),
(9, '903A', 'V3', 'Egan Bernal', 'yt655try', 'negro', 'moto', 'e2024', 'suzuki', '2025-03-14 08:46:00', '2025-03-15 22:46:00', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_zona`
--

CREATE TABLE `solicitud_zona` (
  `ID_Apartamentooss` varchar(100) NOT NULL,
  `ID_zonaComun` int(100) NOT NULL,
  `fechainicio` date NOT NULL,
  `fechafinal` date NOT NULL,
  `Hora_inicio` time NOT NULL,
  `Hora_final` time NOT NULL,
  `estado` enum('ACEPTADA','PENDIENTE','RECHAZADA') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud_zona`
--

INSERT INTO `solicitud_zona` (`ID_Apartamentooss`, `ID_zonaComun`, `fechainicio`, `fechafinal`, `Hora_inicio`, `Hora_final`, `estado`) VALUES
('1001C', 4, '2025-03-25', '2025-02-25', '03:50:57', '20:50:57', 'PENDIENTE'),
('802A', 3, '2025-03-27', '2025-02-25', '05:00:15', '01:51:00', 'ACEPTADA'),
('1003E', 5, '2025-03-18', '2025-02-19', '00:03:46', '09:03:46', 'PENDIENTE'),
('1002E', 1, '2025-03-24', '2025-02-19', '07:04:53', '07:04:53', 'PENDIENTE'),
('1001I', 2, '2025-03-26', '2025-02-28', '02:20:08', '13:20:08', 'PENDIENTE'),
('101A', 1, '2025-03-19', '2025-02-24', '14:35:00', '14:35:00', 'ACEPTADA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodoc`
--

CREATE TABLE `tipodoc` (
  `idtDoc` int(11) NOT NULL,
  `descripcionDoc` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipodoc`
--

INSERT INTO `tipodoc` (`idtDoc`, `descripcionDoc`) VALUES
(1, 'Cedula de Ciudadanía'),
(2, 'Cédula de ciudadanía digital'),
(4, 'Cédulas de Extranjería');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokens`
--

CREATE TABLE `tokens` (
  `id_token` int(11) NOT NULL,
  `id_Registro` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_expiracion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tokens`
--

INSERT INTO `tokens` (`id_token`, `id_Registro`, `token`, `fecha_creacion`, `fecha_expiracion`) VALUES
(52, 74, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6Ijc0IiwiVXN1YXJpbyI6IlJPTkFMRE8iLCJDb3JyZW8iOiJyQGdtYWlsLmNvbSIsImlkUm9sIjoiMjIyMiIsImV4cCI6MTc0MjY4NDYzOX0.uqHZLD-ZmwgkWQydpqAPNmwXZeN7Ga1UuSr7VH-y2kY', '2025-03-22 22:03:59', '2025-03-23 00:07:19'),
(53, 77, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6Ijc3IiwiVXN1YXJpbyI6ImphbWVzIiwiQ29ycmVvIjoiZXFAZ21haWwuY29tIiwiaWRSb2wiOiIzMzMzIiwiZXhwIjoxNzQyNjg4MjA3fQ.fHNoZO7IDbK3G6XVKHS9ImNEkqVAYIvAlULWgW6Sh2g', '2025-03-22 23:03:27', '2025-03-23 01:06:47'),
(55, 79, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6Ijc5IiwiVXN1YXJpbyI6IndhbHRlciIsIkNvcnJlbyI6Ind3QGdtYWlsLmNvbSIsImlkUm9sIjoiMTExMSIsImV4cCI6MTc0MjY5NzAxNX0.3T5jlMWPC2bYODtT32ZAhXQAFzBBVSVyCGYo9Atq-SI', '2025-03-23 01:30:15', '2025-03-23 03:33:35'),
(66, 93, 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6OTMsImlhdCI6MTc0MjkxMDE5MCwiZXhwIjoxNzQyOTk2NTkwfQ.5IY50KOS-JybOHkz-a8M1wzqn7rTacT3YZ3jG-3rkSU', '2025-03-25 13:43:10', '2025-03-26 08:43:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zona_comun`
--

CREATE TABLE `zona_comun` (
  `idZona` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `costo_alquiler` varchar(222) NOT NULL,
  `url_videos` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `zona_comun`
--

INSERT INTO `zona_comun` (`idZona`, `descripcion`, `costo_alquiler`, `url_videos`) VALUES
(1, 'Cancha de Futbol', '120.000', 'img/fut.mp4'),
(2, 'Zona BBQ', '150.000', 'img/coci.mp4'),
(3, 'Salón comunal', '200.000', 'img/event.mp4'),
(4, 'Cancha de Voleyball ', '100.000', 'img/vol.mp4'),
(5, 'Gimnasio', '30.000', 'img/gyd.mp4');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `anuncio`
--
ALTER TABLE `anuncio`
  ADD PRIMARY KEY (`idAnuncio`),
  ADD KEY `fk_anuncio_persona` (`persona`),
  ADD KEY `fk_anuncio_apartamento` (`apart`);

--
-- Indices de la tabla `apartamento`
--
ALTER TABLE `apartamento`
  ADD PRIMARY KEY (`numApartamento`);

--
-- Indices de la tabla `cita`
--
ALTER TABLE `cita`
  ADD PRIMARY KEY (`idcita`),
  ADD KEY `fk_cita_apartamento` (`apa`);

--
-- Indices de la tabla `contactarnos`
--
ALTER TABLE `contactarnos`
  ADD PRIMARY KEY (`idcontactarnos`);

--
-- Indices de la tabla `ingreso_peatonal`
--
ALTER TABLE `ingreso_peatonal`
  ADD PRIMARY KEY (`idIngreso_Peatonal`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`idPagos`),
  ADD KEY `fk_pagos_apa` (`apart`);

--
-- Indices de la tabla `parqueadero`
--
ALTER TABLE `parqueadero`
  ADD PRIMARY KEY (`id_parqueadero`),
  ADD KEY `parqueadero_ibfk_1` (`id_apartamento`);

--
-- Indices de la tabla `registro`
--
ALTER TABLE `registro`
  ADD PRIMARY KEY (`id_Registro`),
  ADD KEY `fk_registro_rol` (`idRol`),
  ADD KEY `fk_registro_tipodoc` (`Id_tipoDocumento`),
  ADD KEY `fk_registro_apartamento` (`apartamento`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `solicitud_parqueadero`
--
ALTER TABLE `solicitud_parqueadero`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `solicitud_parqueadero_ibfk_1` (`id_apartamento`);

--
-- Indices de la tabla `solicitud_zona`
--
ALTER TABLE `solicitud_zona`
  ADD KEY `fkidZona` (`ID_zonaComun`),
  ADD KEY `fkid_Apartamento5` (`ID_Apartamentooss`);

--
-- Indices de la tabla `tipodoc`
--
ALTER TABLE `tipodoc`
  ADD PRIMARY KEY (`idtDoc`);

--
-- Indices de la tabla `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id_token`),
  ADD KEY `id_Registro` (`id_Registro`);

--
-- Indices de la tabla `zona_comun`
--
ALTER TABLE `zona_comun`
  ADD PRIMARY KEY (`idZona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `anuncio`
--
ALTER TABLE `anuncio`
  MODIFY `idAnuncio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `cita`
--
ALTER TABLE `cita`
  MODIFY `idcita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `contactarnos`
--
ALTER TABLE `contactarnos`
  MODIFY `idcontactarnos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `ingreso_peatonal`
--
ALTER TABLE `ingreso_peatonal`
  MODIFY `idIngreso_Peatonal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `idPagos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `parqueadero`
--
ALTER TABLE `parqueadero`
  MODIFY `id_parqueadero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `id_Registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4446;

--
-- AUTO_INCREMENT de la tabla `solicitud_parqueadero`
--
ALTER TABLE `solicitud_parqueadero`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id_token` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `anuncio`
--
ALTER TABLE `anuncio`
  ADD CONSTRAINT `fk_anuncio_apartamento` FOREIGN KEY (`apart`) REFERENCES `apartamento` (`numApartamento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_anuncio_persona` FOREIGN KEY (`persona`) REFERENCES `registro` (`id_Registro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cita`
--
ALTER TABLE `cita`
  ADD CONSTRAINT `fk_cita_apartamento` FOREIGN KEY (`apa`) REFERENCES `apartamento` (`numApartamento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pagos_apa` FOREIGN KEY (`apart`) REFERENCES `apartamento` (`numApartamento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `parqueadero`
--
ALTER TABLE `parqueadero`
  ADD CONSTRAINT `parqueadero_ibfk_1` FOREIGN KEY (`id_apartamento`) REFERENCES `apartamento` (`numApartamento`) ON DELETE CASCADE;

--
-- Filtros para la tabla `registro`
--
ALTER TABLE `registro`
  ADD CONSTRAINT `fk_registro_apartamento` FOREIGN KEY (`apartamento`) REFERENCES `apartamento` (`numApartamento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_registro_rol` FOREIGN KEY (`idRol`) REFERENCES `rol` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_registro_tipodoc` FOREIGN KEY (`Id_tipoDocumento`) REFERENCES `tipodoc` (`idtDoc`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitud_parqueadero`
--
ALTER TABLE `solicitud_parqueadero`
  ADD CONSTRAINT `solicitud_parqueadero_ibfk_1` FOREIGN KEY (`id_apartamento`) REFERENCES `apartamento` (`numApartamento`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solicitud_zona`
--
ALTER TABLE `solicitud_zona`
  ADD CONSTRAINT `fk` FOREIGN KEY (`ID_zonaComun`) REFERENCES `zona_comun` (`idZona`),
  ADD CONSTRAINT `fkidZona` FOREIGN KEY (`ID_zonaComun`) REFERENCES `zona_comun` (`idZona`),
  ADD CONSTRAINT `fkid_Apartamento5` FOREIGN KEY (`ID_Apartamentooss`) REFERENCES `apartamento` (`numApartamento`);

--
-- Filtros para la tabla `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`id_Registro`) REFERENCES `registro` (`id_Registro`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
