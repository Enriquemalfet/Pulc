-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 19-04-2019 a las 19:31:09
-- Versión del servidor: 5.7.21
-- Versión de PHP: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pulc`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dispositivos`
--

DROP TABLE IF EXISTS `dispositivos`;
CREATE TABLE IF NOT EXISTS `dispositivos` (
  `idDispositivos` int(11) NOT NULL,
  `Status` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDispositivos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `dispositivos`
--

INSERT INTO `dispositivos` (`idDispositivos`, `Status`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 0),
(5, 1),
(6, 0),
(7, 1),
(8, 1),
(9, 0),
(10, 1),
(11, 0),
(12, 0),
(13, 1),
(14, 1),
(15, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hist_preg`
--

DROP TABLE IF EXISTS `hist_preg`;
CREATE TABLE IF NOT EXISTS `hist_preg` (
  `idHist_Preg` int(11) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `N_Preg` int(11) DEFAULT NULL,
  PRIMARY KEY (`idHist_Preg`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportero`
--

DROP TABLE IF EXISTS `reportero`;
CREATE TABLE IF NOT EXISTS `reportero` (
  `idReportero` int(11) NOT NULL,
  `Nombre` varchar(45) DEFAULT NULL,
  `A_Paterno` varchar(45) CHARACTER SET armscii8 DEFAULT NULL,
  `A_Materno` varchar(45) DEFAULT NULL,
  `Medio` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idReportero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `reportero`
--

INSERT INTO `reportero` (`idReportero`, `Nombre`, `A_Paterno`, `A_Materno`, `Medio`) VALUES
(1, 'Enrique', 'Perez', 'Garcia', 'La jornada'),
(2, 'Jess', 'Lamonte', 'Maceda', 'El sol de México'),
(3, 'Ricardo', 'Pastrana', 'Hérnandez', 'El heraldo'),
(4, 'Erika', 'Flores', 'Martinez', 'Síntesis'),
(5, 'Carina', 'Remigo', 'Rosas', 'El sol Puebla');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `usuario` varchar(12) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `clave` varchar(12) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `correo` varchar(50) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion_clave` timestamp NULL DEFAULT NULL,
  `fecha_intento_acceso` timestamp NULL DEFAULT NULL,
  `n_intentos_acceso` int(11) DEFAULT '0',
  `fecha_suspension` timestamp NULL DEFAULT NULL,
  `almacen` varchar(15) NOT NULL,
  PRIMARY KEY (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario`, `nombre`, `clave`, `status`, `correo`, `telefono`, `fecha_creacion`, `fecha_actualizacion_clave`, `fecha_intento_acceso`, `n_intentos_acceso`, `fecha_suspension`, `almacen`) VALUES
('eperez', 'Enrique Pérez', 'Cambiame02', 1, NULL, NULL, '2014-10-23 18:46:25', '2019-04-06 01:34:04', '2019-04-06 01:33:52', 0, NULL, ''),
('sburbano', 'Santiago Burbano', 'Cambiar.2017', 1, 'santiago.burbano@gruposese.com', NULL, '2016-10-27 00:00:00', '2017-03-15 23:35:11', '2017-03-15 23:34:56', 0, NULL, '1'),
('uspino', 'Pino General', 'Cambiame02', 1, 'enrique.perez@gruposese.com', '2226822954', '2016-04-15 16:13:13', '2019-04-06 01:34:56', '2019-04-18 20:56:02', 0, NULL, '1');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
