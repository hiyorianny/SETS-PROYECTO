-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-04-2025 a las 18:37:24
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

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarAnuncio` (IN `p_titulo` VARCHAR(45), IN `p_descripcion` VARCHAR(45), IN `p_persona` INT(11), IN `p_apart` VARCHAR(222), IN `p_img_anuncio` VARCHAR(70))   BEGIN
    INSERT INTO anuncio (titulo, descripcion, fechaPublicacion, horaPublicacion, persona, apart, img_anuncio)
    VALUES (p_titulo, p_descripcion, CURDATE(), CURTIME(), p_persona, p_apart, p_img_anuncio);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarApartamento` (IN `p_numApartamento` VARCHAR(111), IN `p_pisos` VARCHAR(11), IN `p_torre` VARCHAR(112))   BEGIN
    INSERT INTO apartamento (numApartamento, pisos, torre)
    VALUES (p_numApartamento, p_pisos, p_torre);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarCita` (IN `p_fechacita` DATE, IN `p_horacita` TIME, IN `p_tipocita` VARCHAR(45), IN `p_apa` VARCHAR(113), IN `p_respuesta` VARCHAR(100), IN `p_estado` ENUM('pendiente','respondida',''))   BEGIN
    INSERT INTO cita (fechacita, horacita, tipocita, apa, respuesta, estado)
    VALUES (p_fechacita, p_horacita, p_tipocita, p_apa, p_respuesta, p_estado);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarContacto` (IN `p_nombre` VARCHAR(100), IN `p_correo` VARCHAR(100), IN `p_telefono` VARCHAR(20), IN `p_comentario` TEXT)   BEGIN
    INSERT INTO contactarnos (nombre, correo, telefono, comentario)
    VALUES (p_nombre, p_correo, p_telefono, p_comentario);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarIngresoPeatonal` (IN `p_personasIngreso` VARCHAR(45), IN `p_horaFecha` DATETIME, IN `p_documento` VARCHAR(2009), IN `p_tipo_ingreso` ENUM('vehiculo','visitante'), IN `p_placa` VARCHAR(250))   BEGIN
    INSERT INTO ingreso_peatonal (personasIngreso, horaFecha, documento, tipo_ingreso, placa)
    VALUES (p_personasIngreso, p_horaFecha, p_documento, p_tipo_ingreso, p_placa);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarMensajeChat` (IN `p_id_remitente` INT(11), IN `p_id_destinatario` INT(11), IN `p_contenido` TEXT, IN `p_tipo_chat` ENUM('privado','grupal'), IN `p_grupo_chat` VARCHAR(50))   BEGIN
    INSERT INTO mensajes_chat (id_remitente, id_destinatario, contenido, tipo_chat, grupo_chat)
    VALUES (p_id_remitente, p_id_destinatario, p_contenido, p_tipo_chat, p_grupo_chat);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarPago` (IN `p_pagoPor` VARCHAR(100), IN `p_cantidad` DECIMAL(10,2), IN `p_mediopago` ENUM('Efectivo','Transferencia','Tarjeta','Cheque','Otro'), IN `p_apart` VARCHAR(112), IN `p_fechaPago` DATE, IN `p_referenciaPago` VARCHAR(100))   BEGIN
    INSERT INTO pagos (pagoPor, cantidad, mediopago, apart, fechaPago, estado, referenciaPago)
    VALUES (p_pagoPor, p_cantidad, p_mediopago, p_apart, p_fechaPago, 'Pendiente', p_referenciaPago);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarParqueadero` (IN `p_numero_parqueadero` INT(11), IN `p_id_apartamento` VARCHAR(113), IN `p_disponibilidad` VARCHAR(222))   BEGIN
    INSERT INTO parqueadero (numero_parqueadero, id_apartamento, uso, disponibilidad)
    VALUES (p_numero_parqueadero, p_id_apartamento, NOW(), p_disponibilidad);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarRegistro` (IN `p_idRol` INT(20), IN `p_PrimerNombre` VARCHAR(45), IN `p_SegundoNombre` VARCHAR(45), IN `p_PrimerApellido` VARCHAR(45), IN `p_SegundoApellido` VARCHAR(45), IN `p_apartamento` VARCHAR(113), IN `p_Correo` VARCHAR(45), IN `p_Usuario` VARCHAR(45), IN `p_Clave` VARCHAR(255), IN `p_Id_tipoDocumento` INT(11), IN `p_numeroDocumento` INT(11), IN `p_telefonoUno` INT(11), IN `p_telefonoDos` INT(11), IN `p_imagenPerfil` VARCHAR(300), IN `p_tipo_propietario` ENUM('dueño','residente','ambos','ninguno'))   BEGIN
    INSERT INTO registro (idRol, PrimerNombre, SegundoNombre, PrimerApellido, SegundoApellido, apartamento, Correo, Usuario, Clave, Id_tipoDocumento, numeroDocumento, telefonoUno, telefonoDos, imagenPerfil, tipo_propietario)
    VALUES (p_idRol, p_PrimerNombre, p_SegundoNombre, p_PrimerApellido, p_SegundoApellido, p_apartamento, p_Correo, p_Usuario, p_Clave, p_Id_tipoDocumento, p_numeroDocumento, p_telefonoUno, p_telefonoDos, p_imagenPerfil, p_tipo_propietario);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarRol` (IN `p_Roldescripcion` VARCHAR(100))   BEGIN
    INSERT INTO rol (Roldescripcion)
    VALUES (p_Roldescripcion);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarSolicitudParqueadero` (IN `p_id_apartamento` VARCHAR(113), IN `p_parqueadero_visitante` ENUM('V1','V2','V3','V4','V5','V6','V7','V8','V9','V10'), IN `p_nombre_visitante` VARCHAR(100), IN `p_placaVehiculo` VARCHAR(45), IN `p_colorVehiculo` VARCHAR(45), IN `p_tipoVehiculo` VARCHAR(100), IN `p_modelo` VARCHAR(90), IN `p_marca` VARCHAR(100), IN `p_fecha_inicio` DATETIME, IN `p_fecha_final` DATETIME)   BEGIN
    INSERT INTO solicitud_parqueadero (id_apartamento, parqueadero_visitante, nombre_visitante, placaVehiculo, colorVehiculo, tipoVehiculo, modelo, marca, fecha_inicio, fecha_final, estado)
    VALUES (p_id_apartamento, p_parqueadero_visitante, p_nombre_visitante, p_placaVehiculo, p_colorVehiculo, p_tipoVehiculo, p_modelo, p_marca, p_fecha_inicio, p_fecha_final, 'pendiente');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarSolicitudZona` (IN `p_ID_Apartamentooss` VARCHAR(100), IN `p_ID_zonaComun` INT(100), IN `p_fechainicio` DATE, IN `p_fechafinal` DATE, IN `p_Hora_inicio` TIME, IN `p_Hora_final` TIME)   BEGIN
    INSERT INTO solicitud_zona (ID_Apartamentooss, ID_zonaComun, fechainicio, fechafinal, Hora_inicio, Hora_final, estado)
    VALUES (p_ID_Apartamentooss, p_ID_zonaComun, p_fechainicio, p_fechafinal, p_Hora_inicio, p_Hora_final, 'PENDIENTE');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarTipoDoc` (IN `p_descripcionDoc` VARCHAR(200))   BEGIN
    INSERT INTO tipodoc (descripcionDoc)
    VALUES (p_descripcionDoc);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarToken` (IN `p_id_Registro` INT(11), IN `p_token` VARCHAR(255), IN `p_fecha_expiracion` DATETIME)   BEGIN
    INSERT INTO tokens (id_Registro, token, fecha_expiracion)
    VALUES (p_id_Registro, p_token, p_fecha_expiracion);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AgregarZonaComun` (IN `p_descripcion` VARCHAR(45), IN `p_costo_alquiler` VARCHAR(222), IN `p_url_videos` VARCHAR(200))   BEGIN
    INSERT INTO zona_comun (descripcion, costo_alquiler, url_videos)
    VALUES (p_descripcion, p_costo_alquiler, p_url_videos);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarApartamento` (IN `p_numApartamento` VARCHAR(111))   BEGIN
    SELECT * FROM apartamento WHERE numApartamento = p_numApartamento;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarCita` (IN `p_idcita` INT(11))   BEGIN
    SELECT * FROM cita WHERE idcita = p_idcita;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarContacto` (IN `p_idcontactarnos` INT(11))   BEGIN
    SELECT * FROM contactarnos WHERE idcontactarnos = p_idcontactarnos;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarIngresoPeatonal` (IN `p_idIngreso_Peatonal` INT(11))   BEGIN
    SELECT * FROM ingreso_peatonal WHERE idIngreso_Peatonal = p_idIngreso_Peatonal;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarMensajeChat` (IN `p_id_mensaje` INT(11))   BEGIN
    SELECT * FROM mensajes_chat 
    WHERE id_mensaje = p_id_mensaje 
    AND (eliminado_por_remitente = 0 OR eliminado_por_destinatario = 0);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarPago` (IN `p_idPagos` INT(11))   BEGIN
    SELECT * FROM pagos WHERE idPagos = p_idPagos;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarParqueadero` (IN `p_id_parqueadero` INT(11))   BEGIN
    SELECT * FROM parqueadero WHERE id_parqueadero = p_id_parqueadero;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarRegistro` (IN `p_id_Registro` INT(11))   BEGIN
    SELECT * FROM registro WHERE id_Registro = p_id_Registro;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarRol` (IN `p_id` INT(11))   BEGIN
    SELECT * FROM rol WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarSolicitudParqueadero` (IN `p_id_solicitud` INT(11))   BEGIN
    SELECT * FROM solicitud_parqueadero WHERE id_solicitud = p_id_solicitud;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarSolicitudZona` (IN `p_ID_Apartamentooss` VARCHAR(100), IN `p_ID_zonaComun` INT(100))   BEGIN
    SELECT * FROM solicitud_zona 
    WHERE ID_Apartamentooss = p_ID_Apartamentooss 
    AND ID_zonaComun = p_ID_zonaComun;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarTipoDoc` (IN `p_idtDoc` INT(11))   BEGIN
    SELECT * FROM tipodoc WHERE idtDoc = p_idtDoc;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarToken` (IN `p_token` VARCHAR(255))   BEGIN
    SELECT * FROM tokens WHERE token = p_token AND fecha_expiracion > NOW();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ConsultarZonaComun` (IN `p_idZona` INT(11))   BEGIN
    SELECT * FROM zona_comun WHERE idZona = p_idZona;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarAnuncio` (IN `p_idAnuncio` INT(11), IN `p_titulo` VARCHAR(45), IN `p_descripcion` VARCHAR(45), IN `p_img_anuncio` VARCHAR(70))   BEGIN
    UPDATE anuncio 
    SET titulo = p_titulo, 
        descripcion = p_descripcion,
        img_anuncio = p_img_anuncio
    WHERE idAnuncio = p_idAnuncio;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarCita` (IN `p_idcita` INT(11), IN `p_fechacita` DATE, IN `p_horacita` TIME, IN `p_tipocita` VARCHAR(45), IN `p_respuesta` VARCHAR(100), IN `p_estado` ENUM('pendiente','respondida',''))   BEGIN
    UPDATE cita 
    SET fechacita = p_fechacita,
        horacita = p_horacita,
        tipocita = p_tipocita,
        respuesta = p_respuesta,
        estado = p_estado
    WHERE idcita = p_idcita;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarContacto` (IN `p_idcontactarnos` INT(11), IN `p_comentario` TEXT)   BEGIN
    UPDATE contactarnos 
    SET comentario = p_comentario
    WHERE idcontactarnos = p_idcontactarnos;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarIngresoPeatonal` (IN `p_idIngreso_Peatonal` INT(11), IN `p_documento` VARCHAR(2009), IN `p_placa` VARCHAR(250))   BEGIN
    UPDATE ingreso_peatonal 
    SET documento = p_documento,
        placa = p_placa
    WHERE idIngreso_Peatonal = p_idIngreso_Peatonal;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarMensajeChat` (IN `p_id_mensaje` INT(11), IN `p_contenido` TEXT)   BEGIN
    UPDATE mensajes_chat 
    SET contenido = p_contenido
    WHERE id_mensaje = p_id_mensaje;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarPago` (IN `p_idPagos` INT(11), IN `p_estado` ENUM('Pendiente','Pagado','Vencido'), IN `p_referenciaPago` VARCHAR(100))   BEGIN
    UPDATE pagos 
    SET estado = p_estado,
        referenciaPago = p_referenciaPago
    WHERE idPagos = p_idPagos;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarParqueadero` (IN `p_id_parqueadero` INT(11), IN `p_id_apartamento` VARCHAR(113), IN `p_disponibilidad` VARCHAR(222))   BEGIN
    UPDATE parqueadero 
    SET id_apartamento = p_id_apartamento,
        disponibilidad = p_disponibilidad,
        uso = NOW()
    WHERE id_parqueadero = p_id_parqueadero;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarRegistro` (IN `p_id_Registro` INT(11), IN `p_telefonoUno` INT(11), IN `p_telefonoDos` INT(11), IN `p_imagenPerfil` VARCHAR(300), IN `p_tipo_propietario` ENUM('dueño','residente','ambos','ninguno'))   BEGIN
    UPDATE registro 
    SET telefonoUno = p_telefonoUno,
        telefonoDos = p_telefonoDos,
        imagenPerfil = p_imagenPerfil,
        tipo_propietario = p_tipo_propietario
    WHERE id_Registro = p_id_Registro;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarRol` (IN `p_id` INT(11), IN `p_Roldescripcion` VARCHAR(100))   BEGIN
    UPDATE rol 
    SET Roldescripcion = p_Roldescripcion
    WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarSolicitudParqueadero` (IN `p_id_solicitud` INT(11), IN `p_estado` ENUM('pendiente','aprobado','rechazado'))   BEGIN
    UPDATE solicitud_parqueadero 
    SET estado = p_estado
    WHERE id_solicitud = p_id_solicitud;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarSolicitudZona` (IN `p_ID_Apartamentooss` VARCHAR(100), IN `p_ID_zonaComun` INT(100), IN `p_estado` ENUM('ACEPTADA','PENDIENTE','RECHAZADA'))   BEGIN
    UPDATE solicitud_zona 
    SET estado = p_estado
    WHERE ID_Apartamentooss = p_ID_Apartamentooss 
    AND ID_zonaComun = p_ID_zonaComun;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarTipoDoc` (IN `p_idtDoc` INT(11), IN `p_descripcionDoc` VARCHAR(200))   BEGIN
    UPDATE tipodoc 
    SET descripcionDoc = p_descripcionDoc
    WHERE idtDoc = p_idtDoc;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarZonaComun` (IN `p_idZona` INT(11), IN `p_costo_alquiler` VARCHAR(222), IN `p_url_videos` VARCHAR(200))   BEGIN
    UPDATE zona_comun 
    SET costo_alquiler = p_costo_alquiler,
        url_videos = p_url_videos
    WHERE idZona = p_idZona;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarAnuncio` (IN `p_idAnuncio` INT(11))   BEGIN
    DELETE FROM anuncio WHERE idAnuncio = p_idAnuncio;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarApartamento` (IN `p_numApartamento` VARCHAR(111))   BEGIN
    DELETE FROM apartamento WHERE numApartamento = p_numApartamento;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarCita` (IN `p_idcita` INT(11))   BEGIN
    DELETE FROM cita WHERE idcita = p_idcita;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarContacto` (IN `p_idcontactarnos` INT(11))   BEGIN
    DELETE FROM contactarnos WHERE idcontactarnos = p_idcontactarnos;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarIngresoPeatonal` (IN `p_idIngreso_Peatonal` INT(11))   BEGIN
    DELETE FROM ingreso_peatonal WHERE idIngreso_Peatonal = p_idIngreso_Peatonal;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarMensajeChat` (IN `p_id_mensaje` INT(11), IN `p_es_remitente` BOOLEAN)   BEGIN
    IF p_es_remitente THEN
        UPDATE mensajes_chat SET eliminado_por_remitente = 1 WHERE id_mensaje = p_id_mensaje;
    ELSE
        UPDATE mensajes_chat SET eliminado_por_destinatario = 1 WHERE id_mensaje = p_id_mensaje;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarPago` (IN `p_idPagos` INT(11))   BEGIN
    DELETE FROM pagos WHERE idPagos = p_idPagos;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarParqueadero` (IN `p_id_parqueadero` INT(11))   BEGIN
    DELETE FROM parqueadero WHERE id_parqueadero = p_id_parqueadero;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarRegistro` (IN `p_id_Registro` INT(11))   BEGIN
    DELETE FROM registro WHERE id_Registro = p_id_Registro;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarRol` (IN `p_id` INT(11))   BEGIN
    DELETE FROM rol WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarSolicitudParqueadero` (IN `p_id_solicitud` INT(11))   BEGIN
    DELETE FROM solicitud_parqueadero WHERE id_solicitud = p_id_solicitud;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarSolicitudZona` (IN `p_ID_Apartamentooss` VARCHAR(100), IN `p_ID_zonaComun` INT(100))   BEGIN
    DELETE FROM solicitud_zona 
    WHERE ID_Apartamentooss = p_ID_Apartamentooss 
    AND ID_zonaComun = p_ID_zonaComun;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarTipoDoc` (IN `p_idtDoc` INT(11))   BEGIN
    DELETE FROM tipodoc WHERE idtDoc = p_idtDoc;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarToken` (IN `p_id_token` INT(11))   BEGIN
    DELETE FROM tokens WHERE id_token = p_id_token;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarTokensExpirados` ()   BEGIN
    DELETE FROM tokens WHERE fecha_expiracion <= NOW();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarZonaComun` (IN `p_idZona` INT(11))   BEGIN
    DELETE FROM zona_comun WHERE idZona = p_idZona;
END$$

DELIMITER ;

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
  `apart` varchar(222) DEFAULT NULL,
  `img_anuncio` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `anuncio`
--

INSERT INTO `anuncio` (`idAnuncio`, `titulo`, `descripcion`, `fechaPublicacion`, `horaPublicacion`, `persona`, `apart`, `img_anuncio`) VALUES
(20, 'corte de luz', 'entre la 1 y 2 am el día 24/marxo', '2025-04-07', '14:35:00', 136, '101A', 'img/alarma.png'),
(21, 'SEGURIDAD', 'CUIDADO AL SALIR DEL CONJUNTO ', '2025-04-14', '13:59:00', 137, NULL, 'img/alerta.png'),
(22, 'actualizaciones', 'se actualizarán algunos datos', '2025-04-07', '01:04:00', 138, '303D', 'img/alerta.png');

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
(5, '2025-03-30', '11:00:00', 'Reclamo', '404A', '', 'pendiente'),
(6, '2025-04-17', '14:00:00', 'Reclamo', '101A', '', 'pendiente'),
(7, '2025-04-22', '13:00:00', 'Duda', '101A', '', 'pendiente');

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
(23, 'EGAN BERNAL', 'EGAN@GMAIL.COM', '344545454545', 'NO ME DEJO REGISTRARME', '2025-04-09 13:08:06');

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
(4, 'lionel messi', '2025-03-26 01:25:00', 'cc.23323232', 'visitante', ''),
(5, 'taded pogachar', '2025-04-16 23:57:00', 'CC.23323223', 'vehiculo', 'fe343443'),
(6, 'ESTEVAN CHAVES', '2025-04-29 23:57:00', 'CC.23322323', 'visitante', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_chat`
--

CREATE TABLE `mensajes_chat` (
  `id_mensaje` int(11) NOT NULL,
  `id_remitente` int(11) NOT NULL,
  `id_destinatario` int(11) DEFAULT NULL,
  `contenido` text NOT NULL,
  `fecha_envio` datetime NOT NULL DEFAULT current_timestamp(),
  `tipo_chat` enum('privado','grupal') NOT NULL,
  `grupo_chat` varchar(50) DEFAULT NULL,
  `eliminado_por_remitente` tinyint(1) DEFAULT 0,
  `eliminado_por_destinatario` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes_chat`
--

INSERT INTO `mensajes_chat` (`id_mensaje`, `id_remitente`, `id_destinatario`, `contenido`, `fecha_envio`, `tipo_chat`, `grupo_chat`, `eliminado_por_remitente`, `eliminado_por_destinatario`) VALUES
(46, 136, NULL, 'hola', '2025-04-05 23:50:35', 'grupal', 'comunal', 0, 0),
(47, 137, 136, 'hola', '2025-04-06 00:00:24', 'privado', NULL, 0, 0),
(48, 136, 137, 'hola', '2025-04-06 00:16:56', 'privado', NULL, 0, 0),
(49, 138, NULL, 'hols', '2025-04-06 00:28:16', 'grupal', 'comunal', 0, 0),
(50, 138, 137, 'er', '2025-04-06 00:28:34', 'privado', NULL, 1, 0);

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
(10, 'LIMITE DE TIEMPO EN PARQUEADERO', 15.00, 'Efectivo', '903A', '2025-03-14', 'Pendiente', 'PARKING2323'),
(11, 'gym', 50.00, 'Transferencia', '101A', '2025-04-28', 'Pagado', 'GYMN455');

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
  `apartamento` varchar(113) DEFAULT NULL,
  `Correo` varchar(45) NOT NULL,
  `Usuario` varchar(45) NOT NULL,
  `Clave` varchar(255) NOT NULL,
  `Id_tipoDocumento` int(11) NOT NULL,
  `numeroDocumento` int(11) NOT NULL,
  `telefonoUno` int(11) NOT NULL,
  `telefonoDos` int(11) DEFAULT NULL,
  `imagenPerfil` varchar(300) DEFAULT NULL,
  `tipo_propietario` enum('dueño','residente','ambos','ninguno') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`id_Registro`, `idRol`, `PrimerNombre`, `SegundoNombre`, `PrimerApellido`, `SegundoApellido`, `apartamento`, `Correo`, `Usuario`, `Clave`, `Id_tipoDocumento`, `numeroDocumento`, `telefonoUno`, `telefonoDos`, `imagenPerfil`, `tipo_propietario`) VALUES
(136, 3333, 'Nairo', 'Alexander', 'Quintana', 'Rojas', '101A', 'nairo.q@gmail.com', 'nairo', '$2y$10$F0EhaGS7w8edZk4FYPllHued12XZWpc1qZNETw3UxFijr2TmDRL/q', 1, 2147483647, 2147483647, 2147483647, 'img/alarma.png', 'residente'),
(137, 2222, 'Primoz', '', 'Roglic', '', NULL, 'primos@gmail.com', 'primoz', '$2y$10$Z2Oh1369PDn/veGJx0fStuiFaLH5TJNduUPuOHX9Rqs685VSvlXWG', 1, 1212122112, 2147483647, 2147483647, 'uploads/YR3HFCGBQ5ETFBLFFH6FT63KSM.jpg', NULL),
(138, 1111, 'lalo', 'eduardo', 'salamanca', 'salamanca', '303D', 'apirazanesquivel@gmail.com', 'lalo', '$2y$10$Xtzq7UxfX36ZWstBridc5.BQDl/w4XK.suWSWwZuM2F8ZSRrOu6ie', 1, 1221212121, 2147483647, 2147483647, 'uploads/Better-Call-Saul-Lalo-Salamanca-Jacket-Christmas-Sale.jpg', 'dueño'),
(139, 2222, 'Egan ', 'Arley', 'Bernal ', 'Rojas', '202A', 'ineos@gmail.com', 'egan ', '$2b$10$c6JZQPqZchIPjU.UYK6HKeQKTeMUS2teJRrVnHHIjYn7sJRE4gpGe', 1, 2147483647, 325566855, 665589423, NULL, 'residente');

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
(1, '1002E', 'V10', 'Tadej Pogachar', '32ds45', 'Blanco', 'Moto', '2002', 'suzuki', '2025-02-27 10:22:13', '2025-02-20 21:22:13', 'pendiente'),
(3, '202A', 'V1', 'PEDRI', 'SDDWE22', 'ROJO', 'CARRO', 'DSDS34', 'DSDSDS', '2025-02-14 12:55:00', '2025-02-26 12:55:00', 'aprobado'),
(8, '903A', 'V10', 'juan fernando quintero', '3erw34', 'rojo y blanco', 'carro', '34', 'ferrrari', '2025-03-14 09:45:00', '2025-03-17 09:45:00', 'pendiente'),
(9, '903A', 'V3', 'Egan Bernal', 'yt655try', 'negro', 'moto', 'e2024', 'suzuki', '2025-03-14 08:46:00', '2025-03-15 22:46:00', 'pendiente'),
(10, '101A', 'V2', 'LIONEL MESSI', 'SDDS3223', 'AZUL', 'carro', 'sv3443', 'mazda', '2025-04-16 12:42:00', '2025-04-20 23:42:00', 'aprobado'),
(11, '101A', 'V3', 'CRISTIANO RONALDO JR', 'CR7656565', 'MORADO', 'moto', 'suzuki3443', 'suzuki', '2025-04-13 23:43:00', '2025-04-14 23:43:00', 'aprobado');

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
('1001C', 4, '2025-04-25', '2025-02-25', '03:50:57', '20:50:57', 'PENDIENTE'),
('802A', 3, '2025-04-27', '2025-02-25', '05:00:15', '01:51:00', 'ACEPTADA'),
('1003E', 5, '2025-04-18', '2025-02-19', '00:03:46', '09:03:46', 'ACEPTADA'),
('1002E', 1, '2025-04-24', '2025-02-19', '07:04:53', '07:04:53', 'PENDIENTE'),
('1001I', 2, '2025-04-27', '2025-04-28', '02:20:08', '13:20:08', 'PENDIENTE'),
('101A', 1, '2025-04-19', '2025-02-24', '14:35:00', '14:35:00', 'PENDIENTE'),
('101A', 5, '2025-04-06', '2025-04-25', '13:45:00', '17:51:00', 'PENDIENTE'),
('101A', 4, '2025-04-10', '2025-04-10', '05:46:00', '20:46:00', 'PENDIENTE'),
('101A', 3, '2025-04-27', '2025-04-27', '14:47:00', '12:47:00', 'PENDIENTE'),
('101A', 2, '2025-04-26', '2025-04-27', '16:48:00', '12:48:00', 'PENDIENTE');

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
(102, 136, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEzNiIsIlVzdWFyaW8iOiJuYWlybyIsIkNvcnJlbyI6Im5haXJvLnFAZ21haWwuY29tIiwiaWRSb2wiOiIzMzMzIiwiZXhwIjoxNzQzOTE3NjQ3fQ.Y3Rt9y3zQ_JWxixfD5tFk4skt9FBZf_dZ3A9xwZpOmo', '2025-04-06 04:34:07', '2025-04-06 07:34:07'),
(103, 137, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEzNyIsIlVzdWFyaW8iOiJwcmltb3oiLCJDb3JyZW8iOiJwcmltb3NAZ21haWwuY29tIiwiaWRSb2wiOiIyMjIyIiwiZXhwIjoxNzQzOTE5MDE1fQ.LhtYleP52U9dpFnBmyTj6wNWlsGpoc7rsYlnKZj3pS0', '2025-04-06 04:56:55', '2025-04-06 07:56:55'),
(104, 138, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEzOCIsIlVzdWFyaW8iOiJsYWxvIiwiQ29ycmVvIjoiYXBpcmF6YW5lc3F1aXZlbEBnbWFpbC5jb20iLCJpZFJvbCI6IjExMTEiLCJleHAiOjE3NDM5MTk0MTl9.bHn2NIqNj4-06Y1xbBzGg3oSlXRF85GuYGLNiZvMsp8', '2025-04-06 05:03:39', '2025-04-06 08:03:39'),
(105, 139, 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MTM5LCJpYXQiOjE3NDQxMTM2MTAsImV4cCI6MTc0NDIwMDAxMH0.Rp15rrFtjdnadLWYVRqExbSnhJyGtMksvNLu0ejtq0M', '2025-04-08 12:00:10', '2025-04-09 07:00:10');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_anuncios_detalle`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_anuncios_detalle` (
`ID Anuncio` int(11)
,`Título` varchar(45)
,`Descripción` varchar(45)
,`Fecha Publicación` date
,`Hora Publicación` time
,`Apartamento` varchar(222)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_anuncios_recientes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_anuncios_recientes` (
`ID Anuncio` int(11)
,`Título` varchar(45)
,`Fecha` date
,`Apartamento` varchar(222)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_apartamentos_completo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_apartamentos_completo` (
`Número Apartamento` varchar(111)
,`Piso` varchar(11)
,`Torre` varchar(112)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_apartamentos_por_torre`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_apartamentos_por_torre` (
`Torre` varchar(112)
,`Total Apartamentos` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_citas_pendientes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_citas_pendientes` (
`ID Cita` int(11)
,`Fecha` date
,`Hora` time
,`Tipo Cita` varchar(45)
,`Apartamento` varchar(113)
,`Estado` enum('pendiente','respondida','')
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_citas_por_tipo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_citas_por_tipo` (
`Tipo Cita` varchar(45)
,`Total Citas` bigint(21)
,`Primera Cita` date
,`Última Cita` date
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_contactos_por_mes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_contactos_por_mes` (
`Mes` int(2)
,`Total Contactos` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_contactos_recientes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_contactos_recientes` (
`ID Contacto` int(11)
,`Nombre` varchar(100)
,`Correo` varchar(100)
,`Teléfono` varchar(20)
,`Fecha Contacto` timestamp
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_conteo_usuarios_por_rol`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_conteo_usuarios_por_rol` (
`Rol` varchar(100)
,`Total Usuarios` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_documentos_usuarios`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_documentos_usuarios` (
`Tipo Documento` varchar(200)
,`Total Usuarios` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_ingresos_hoy`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_ingresos_hoy` (
`ID Ingreso` int(11)
,`Persona` varchar(45)
,`Fecha/Hora` datetime
,`Tipo Ingreso` enum('vehiculo','visitante')
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_ingresos_por_tipo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_ingresos_por_tipo` (
`Tipo Ingreso` enum('vehiculo','visitante')
,`Total Ingresos` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_mensajes_privados`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_mensajes_privados` (
`ID Mensaje` int(11)
,`Remitente` int(11)
,`Destinatario` int(11)
,`Fecha` date
,`Hora` time
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_pagos_pendientes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_pagos_pendientes` (
`ID Pago` int(11)
,`Concepto` varchar(100)
,`Monto` decimal(10,2)
,`Apartamento` varchar(112)
,`Estado` enum('Pendiente','Pagado','Vencido')
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_pagos_por_medio`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_pagos_por_medio` (
`Medio de Pago` enum('Efectivo','Transferencia','Tarjeta','Cheque','Otro')
,`Total Pagos` bigint(21)
,`Monto Total` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_parqueaderos_disponibles`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_parqueaderos_disponibles` (
`ID Parqueadero` int(11)
,`Número` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_parqueaderos_ocupados`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_parqueaderos_ocupados` (
`ID Parqueadero` int(11)
,`Número` int(11)
,`Apartamento` varchar(113)
,`Estado` varchar(222)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_solicitudes_aceptadas_zona`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_solicitudes_aceptadas_zona` (
`Apartamento` varchar(100)
,`Zona Común` int(100)
,`Fecha Reserva` date
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_solicitudes_parqueadero`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_solicitudes_parqueadero` (
`ID Solicitud` int(11)
,`Apartamento` varchar(113)
,`Visitante` varchar(100)
,`Placa` varchar(45)
,`Estado` enum('pendiente','aprobado','rechazado')
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_solicitudes_pendientes_parqueadero`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_solicitudes_pendientes_parqueadero` (
`ID Solicitud` int(11)
,`Visitante` varchar(100)
,`Fecha Inicio` datetime
,`Fecha Fin` datetime
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_solicitudes_zona`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_solicitudes_zona` (
`Apartamento` varchar(100)
,`Zona Común` int(100)
,`Fecha Inicio` date
,`Estado` enum('ACEPTADA','PENDIENTE','RECHAZADA')
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_ultimos_mensajes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_ultimos_mensajes` (
`ID Usuario` int(11)
,`Total Mensajes` bigint(21)
,`Último Mensaje` datetime
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_usuarios_activos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_usuarios_activos` (
`ID Usuario` int(11)
,`Nombre Completo` varchar(91)
,`Apartamento` varchar(113)
,`Correo` varchar(45)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_usuarios_por_tipo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_usuarios_por_tipo` (
`Tipo Propietario` enum('dueño','residente','ambos','ninguno')
,`Total Usuarios` bigint(21)
);

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
-- Indices de la tabla `mensajes_chat`
--
ALTER TABLE `mensajes_chat`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `id_remitente` (`id_remitente`),
  ADD KEY `id_destinatario` (`id_destinatario`);

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
  MODIFY `idAnuncio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `cita`
--
ALTER TABLE `cita`
  MODIFY `idcita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `contactarnos`
--
ALTER TABLE `contactarnos`
  MODIFY `idcontactarnos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `ingreso_peatonal`
--
ALTER TABLE `ingreso_peatonal`
  MODIFY `idIngreso_Peatonal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `mensajes_chat`
--
ALTER TABLE `mensajes_chat`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `idPagos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `parqueadero`
--
ALTER TABLE `parqueadero`
  MODIFY `id_parqueadero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `id_Registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4446;

--
-- AUTO_INCREMENT de la tabla `solicitud_parqueadero`
--
ALTER TABLE `solicitud_parqueadero`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id_token` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_anuncios_detalle`
--
DROP TABLE IF EXISTS `vista_anuncios_detalle`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_anuncios_detalle`  AS SELECT `anuncio`.`idAnuncio` AS `ID Anuncio`, `anuncio`.`titulo` AS `Título`, `anuncio`.`descripcion` AS `Descripción`, `anuncio`.`fechaPublicacion` AS `Fecha Publicación`, `anuncio`.`horaPublicacion` AS `Hora Publicación`, `anuncio`.`apart` AS `Apartamento` FROM `anuncio` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_anuncios_recientes`
--
DROP TABLE IF EXISTS `vista_anuncios_recientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_anuncios_recientes`  AS SELECT `anuncio`.`idAnuncio` AS `ID Anuncio`, `anuncio`.`titulo` AS `Título`, `anuncio`.`fechaPublicacion` AS `Fecha`, `anuncio`.`apart` AS `Apartamento` FROM `anuncio` WHERE `anuncio`.`fechaPublicacion` >= curdate() - interval 7 day ORDER BY `anuncio`.`fechaPublicacion` DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_apartamentos_completo`
--
DROP TABLE IF EXISTS `vista_apartamentos_completo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_apartamentos_completo`  AS SELECT `apartamento`.`numApartamento` AS `Número Apartamento`, `apartamento`.`pisos` AS `Piso`, `apartamento`.`torre` AS `Torre` FROM `apartamento` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_apartamentos_por_torre`
--
DROP TABLE IF EXISTS `vista_apartamentos_por_torre`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_apartamentos_por_torre`  AS SELECT `apartamento`.`torre` AS `Torre`, count(0) AS `Total Apartamentos` FROM `apartamento` GROUP BY `apartamento`.`torre` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_citas_pendientes`
--
DROP TABLE IF EXISTS `vista_citas_pendientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_citas_pendientes`  AS SELECT `cita`.`idcita` AS `ID Cita`, `cita`.`fechacita` AS `Fecha`, `cita`.`horacita` AS `Hora`, `cita`.`tipocita` AS `Tipo Cita`, `cita`.`apa` AS `Apartamento`, `cita`.`estado` AS `Estado` FROM `cita` WHERE `cita`.`estado` = 'pendiente' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_citas_por_tipo`
--
DROP TABLE IF EXISTS `vista_citas_por_tipo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_citas_por_tipo`  AS SELECT `cita`.`tipocita` AS `Tipo Cita`, count(0) AS `Total Citas`, min(`cita`.`fechacita`) AS `Primera Cita`, max(`cita`.`fechacita`) AS `Última Cita` FROM `cita` GROUP BY `cita`.`tipocita` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_contactos_por_mes`
--
DROP TABLE IF EXISTS `vista_contactos_por_mes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_contactos_por_mes`  AS SELECT month(`contactarnos`.`fecha`) AS `Mes`, count(0) AS `Total Contactos` FROM `contactarnos` GROUP BY month(`contactarnos`.`fecha`) ORDER BY month(`contactarnos`.`fecha`) ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_contactos_recientes`
--
DROP TABLE IF EXISTS `vista_contactos_recientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_contactos_recientes`  AS SELECT `contactarnos`.`idcontactarnos` AS `ID Contacto`, `contactarnos`.`nombre` AS `Nombre`, `contactarnos`.`correo` AS `Correo`, `contactarnos`.`telefono` AS `Teléfono`, `contactarnos`.`fecha` AS `Fecha Contacto` FROM `contactarnos` WHERE `contactarnos`.`fecha` >= curdate() - interval 30 day ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_conteo_usuarios_por_rol`
--
DROP TABLE IF EXISTS `vista_conteo_usuarios_por_rol`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_conteo_usuarios_por_rol`  AS SELECT `r`.`Roldescripcion` AS `Rol`, count(`u`.`id_Registro`) AS `Total Usuarios` FROM (`rol` `r` left join `registro` `u` on(`r`.`id` = `u`.`idRol`)) GROUP BY `r`.`Roldescripcion` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_documentos_usuarios`
--
DROP TABLE IF EXISTS `vista_documentos_usuarios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_documentos_usuarios`  AS SELECT `t`.`descripcionDoc` AS `Tipo Documento`, count(`r`.`id_Registro`) AS `Total Usuarios` FROM (`tipodoc` `t` left join `registro` `r` on(`t`.`idtDoc` = `r`.`Id_tipoDocumento`)) GROUP BY `t`.`descripcionDoc` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_ingresos_hoy`
--
DROP TABLE IF EXISTS `vista_ingresos_hoy`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_ingresos_hoy`  AS SELECT `ingreso_peatonal`.`idIngreso_Peatonal` AS `ID Ingreso`, `ingreso_peatonal`.`personasIngreso` AS `Persona`, `ingreso_peatonal`.`horaFecha` AS `Fecha/Hora`, `ingreso_peatonal`.`tipo_ingreso` AS `Tipo Ingreso` FROM `ingreso_peatonal` WHERE cast(`ingreso_peatonal`.`horaFecha` as date) = curdate() ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_ingresos_por_tipo`
--
DROP TABLE IF EXISTS `vista_ingresos_por_tipo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_ingresos_por_tipo`  AS SELECT `ingreso_peatonal`.`tipo_ingreso` AS `Tipo Ingreso`, count(0) AS `Total Ingresos` FROM `ingreso_peatonal` GROUP BY `ingreso_peatonal`.`tipo_ingreso` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_mensajes_privados`
--
DROP TABLE IF EXISTS `vista_mensajes_privados`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_mensajes_privados`  AS SELECT `mensajes_chat`.`id_mensaje` AS `ID Mensaje`, `mensajes_chat`.`id_remitente` AS `Remitente`, `mensajes_chat`.`id_destinatario` AS `Destinatario`, cast(`mensajes_chat`.`fecha_envio` as date) AS `Fecha`, cast(`mensajes_chat`.`fecha_envio` as time) AS `Hora` FROM `mensajes_chat` WHERE `mensajes_chat`.`tipo_chat` = 'privado' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_pagos_pendientes`
--
DROP TABLE IF EXISTS `vista_pagos_pendientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_pagos_pendientes`  AS SELECT `pagos`.`idPagos` AS `ID Pago`, `pagos`.`pagoPor` AS `Concepto`, `pagos`.`cantidad` AS `Monto`, `pagos`.`apart` AS `Apartamento`, `pagos`.`estado` AS `Estado` FROM `pagos` WHERE `pagos`.`estado` = 'Pendiente' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_pagos_por_medio`
--
DROP TABLE IF EXISTS `vista_pagos_por_medio`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_pagos_por_medio`  AS SELECT `pagos`.`mediopago` AS `Medio de Pago`, count(0) AS `Total Pagos`, sum(`pagos`.`cantidad`) AS `Monto Total` FROM `pagos` WHERE `pagos`.`estado` = 'Pagado' GROUP BY `pagos`.`mediopago` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_parqueaderos_disponibles`
--
DROP TABLE IF EXISTS `vista_parqueaderos_disponibles`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_parqueaderos_disponibles`  AS SELECT `parqueadero`.`id_parqueadero` AS `ID Parqueadero`, `parqueadero`.`numero_parqueadero` AS `Número` FROM `parqueadero` WHERE `parqueadero`.`disponibilidad` = 'SI ESTA DISPONIBLE' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_parqueaderos_ocupados`
--
DROP TABLE IF EXISTS `vista_parqueaderos_ocupados`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_parqueaderos_ocupados`  AS SELECT `parqueadero`.`id_parqueadero` AS `ID Parqueadero`, `parqueadero`.`numero_parqueadero` AS `Número`, `parqueadero`.`id_apartamento` AS `Apartamento`, `parqueadero`.`disponibilidad` AS `Estado` FROM `parqueadero` WHERE `parqueadero`.`disponibilidad` = 'NO ESTA DISPONIBLE' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_solicitudes_aceptadas_zona`
--
DROP TABLE IF EXISTS `vista_solicitudes_aceptadas_zona`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_solicitudes_aceptadas_zona`  AS SELECT `solicitud_zona`.`ID_Apartamentooss` AS `Apartamento`, `solicitud_zona`.`ID_zonaComun` AS `Zona Común`, `solicitud_zona`.`fechainicio` AS `Fecha Reserva` FROM `solicitud_zona` WHERE `solicitud_zona`.`estado` = 'ACEPTADA' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_solicitudes_parqueadero`
--
DROP TABLE IF EXISTS `vista_solicitudes_parqueadero`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_solicitudes_parqueadero`  AS SELECT `solicitud_parqueadero`.`id_solicitud` AS `ID Solicitud`, `solicitud_parqueadero`.`id_apartamento` AS `Apartamento`, `solicitud_parqueadero`.`nombre_visitante` AS `Visitante`, `solicitud_parqueadero`.`placaVehiculo` AS `Placa`, `solicitud_parqueadero`.`estado` AS `Estado` FROM `solicitud_parqueadero` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_solicitudes_pendientes_parqueadero`
--
DROP TABLE IF EXISTS `vista_solicitudes_pendientes_parqueadero`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_solicitudes_pendientes_parqueadero`  AS SELECT `solicitud_parqueadero`.`id_solicitud` AS `ID Solicitud`, `solicitud_parqueadero`.`nombre_visitante` AS `Visitante`, `solicitud_parqueadero`.`fecha_inicio` AS `Fecha Inicio`, `solicitud_parqueadero`.`fecha_final` AS `Fecha Fin` FROM `solicitud_parqueadero` WHERE `solicitud_parqueadero`.`estado` = 'pendiente' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_solicitudes_zona`
--
DROP TABLE IF EXISTS `vista_solicitudes_zona`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_solicitudes_zona`  AS SELECT `solicitud_zona`.`ID_Apartamentooss` AS `Apartamento`, `solicitud_zona`.`ID_zonaComun` AS `Zona Común`, `solicitud_zona`.`fechainicio` AS `Fecha Inicio`, `solicitud_zona`.`estado` AS `Estado` FROM `solicitud_zona` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_ultimos_mensajes`
--
DROP TABLE IF EXISTS `vista_ultimos_mensajes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_ultimos_mensajes`  AS SELECT `mensajes_chat`.`id_remitente` AS `ID Usuario`, count(0) AS `Total Mensajes`, max(`mensajes_chat`.`fecha_envio`) AS `Último Mensaje` FROM `mensajes_chat` GROUP BY `mensajes_chat`.`id_remitente` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_usuarios_activos`
--
DROP TABLE IF EXISTS `vista_usuarios_activos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_usuarios_activos`  AS SELECT `registro`.`id_Registro` AS `ID Usuario`, concat(`registro`.`PrimerNombre`,' ',`registro`.`PrimerApellido`) AS `Nombre Completo`, `registro`.`apartamento` AS `Apartamento`, `registro`.`Correo` AS `Correo` FROM `registro` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_usuarios_por_tipo`
--
DROP TABLE IF EXISTS `vista_usuarios_por_tipo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_usuarios_por_tipo`  AS SELECT `registro`.`tipo_propietario` AS `Tipo Propietario`, count(0) AS `Total Usuarios` FROM `registro` GROUP BY `registro`.`tipo_propietario` ;

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
-- Filtros para la tabla `mensajes_chat`
--
ALTER TABLE `mensajes_chat`
  ADD CONSTRAINT `mensajes_chat_ibfk_1` FOREIGN KEY (`id_remitente`) REFERENCES `registro` (`id_Registro`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mensajes_chat_ibfk_2` FOREIGN KEY (`id_destinatario`) REFERENCES `registro` (`id_Registro`) ON DELETE CASCADE ON UPDATE CASCADE;

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
