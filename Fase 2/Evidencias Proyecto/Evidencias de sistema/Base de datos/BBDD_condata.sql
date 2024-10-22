-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-10-2024 a las 19:05:40
-- Versión del servidor: 10.6.16-MariaDB-cll-lve
-- Versión de PHP: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ccm89988_condominio`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`ccm89988`@`localhost` PROCEDURE `proc_obtenerVehiculosDelMesActual` ()   BEGIN
    SELECT fecha, rut, nombre, apellido, torre, departamento,estacionamiento,patente
    FROM vicita
    WHERE YEAR(fecha) = YEAR(NOW()) 
    AND MONTH(fecha) = MONTH(NOW())
    AND estacionamiento IS NOT NULL
    AND patente IS NOT NULL
      order by fecha desc;
END$$

CREATE DEFINER=`ccm89988`@`localhost` PROCEDURE `proc_obtenervehiculosDelPeriodo` ()   BEGIN
    SELECT fecha, rut, nombre, apellido, torre, departamento, patente,estacionamiento
    FROM vicita
    WHERE DATE(fecha) BETWEEN DATE(NOW()) - INTERVAL 1 DAY AND DATE(NOW()) + INTERVAL 1 DAY
    AND estacionamiento IS NOT NULL
    AND patente IS NOT NULL
    order by fecha desc;
END$$

CREATE DEFINER=`ccm89988`@`localhost` PROCEDURE `proc_obtenerVisitasDelMesActual` ()   BEGIN
    SELECT fecha, rut, nombre, apellido, torre, departamento
    FROM vicita
    WHERE YEAR(fecha) = YEAR(NOW()) 
    AND MONTH(fecha) = MONTH(NOW())
    AND estacionamiento IS NULL
    AND patente IS NULL
      order by fecha desc;
END$$

CREATE DEFINER=`ccm89988`@`localhost` PROCEDURE `proc_obtenerVisitasDelPeriodo` ()   BEGIN
    SELECT fecha, rut, nombre, apellido, torre, departamento
    FROM vicita
    WHERE DATE(fecha) BETWEEN DATE(NOW()) - INTERVAL 1 DAY AND DATE(NOW()) + INTERVAL 1 DAY
    AND estacionamiento IS NULL
    AND patente IS NULL
      order by fecha desc;
END$$

CREATE DEFINER=`ccm89988`@`localhost` PROCEDURE `proc_obtener_otro_vehiculo` ()   BEGIN
    SELECT id_otro_vehiculo, nombre_otro_vehiculo, fecha
    FROM otro_vehiculo
    WHERE DATE(fecha) BETWEEN DATE(NOW()) - INTERVAL 1 DAY AND DATE(NOW()) + INTERVAL 1 DAY
      order by fecha desc;
END$$

CREATE DEFINER=`ccm89988`@`localhost` PROCEDURE `proc_obtener_otro_vehiculo_mes` ()   BEGIN
    SELECT id_otro_vehiculo, nombre_otro_vehiculo, fecha
    FROM otro_vehiculo
  WHERE YEAR(fecha) = YEAR(NOW()) 
    AND MONTH(fecha) = MONTH(NOW())    
      order by fecha desc;
END$$

CREATE DEFINER=`ccm89988`@`localhost` PROCEDURE `proc_registro_empresa` (IN `p_empresa` VARCHAR(30))   BEGIN
    INSERT INTO otro_vehiculo ( nombre_otro_vehiculo,fecha)
    VALUES (p_empresa,NOW());
END$$

CREATE DEFINER=`ccm89988`@`localhost` PROCEDURE `proc_registro_vicita` (IN `p_rut` VARCHAR(10), IN `p_nombre` VARCHAR(50), IN `p_apellido` VARCHAR(50), IN `p_torre` VARCHAR(1), IN `p_departamento` VARCHAR(3), IN `p_estacionamiento` VARCHAR(3), IN `p_patente` VARCHAR(11))   BEGIN
    INSERT INTO vicita (rut, nombre, apellido, torre, departamento, estacionamiento, patente, fecha)
    VALUES (p_rut, p_nombre, p_apellido,  p_torre, p_departamento, p_estacionamiento, p_patente, NOW());
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE `category` (
  `id_category` int(11) NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `category`
--

INSERT INTO `category` (`id_category`, `category`) VALUES
(1, 'Equipo Directivo'),
(2, 'Convivencia Escolar'),
(3, 'Profesores Jefes'),
(4, 'Profesores Asignatura'),
(5, 'PIE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citaciones`
--

CREATE TABLE `citaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `derivacion_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_accion` varchar(255) NOT NULL,
  `fecha_citacion` date NOT NULL,
  `hora_citacion` time NOT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `colaborador` int(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `citaciones`
--

INSERT INTO `citaciones` (`id`, `derivacion_id`, `tipo_accion`, `fecha_citacion`, `hora_citacion`, `observaciones`, `created_at`, `updated_at`, `colaborador`, `estado`) VALUES
(20, 3, 'Entrevista Alumno', '2024-10-17', '12:24:00', 'jaja', '2024-10-16 15:24:24', '2024-10-16 15:24:24', 85, 1),
(21, 3, 'Entrevista Apoderado', '2024-10-18', '13:25:00', 'jojo', '2024-10-16 15:24:38', '2024-10-16 15:24:38', 85, 1),
(22, 3, 'Entrevista Alumno', '2024-10-17', '11:28:00', 'lqwkjdakljsdasl', '2024-10-17 14:28:25', '2024-10-17 14:28:25', 85, 1),
(23, 3, 'Tomar Acuerdos', '2024-10-17', '11:34:00', 'toma de acuerdos', '2024-10-17 14:34:18', '2024-10-17 14:34:18', 85, 1),
(24, 5, 'Entrevista Alumno', '2024-10-17', '12:48:00', 'wdiojewn', '2024-10-17 15:48:59', '2024-10-17 15:48:59', 85, 1),
(25, 6, 'Entrevista Alumno', '2024-12-31', '23:59:00', 'CITACION APODERADO', '2024-10-20 20:25:53', '2024-10-20 20:25:53', 88, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `id` int(11) NOT NULL,
  `cod_tipo_ensenanza` int(11) DEFAULT NULL,
  `cod_grado` int(11) DEFAULT NULL,
  `desc_grado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `letra_curso` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `curso`
--

INSERT INTO `curso` (`id`, `cod_tipo_ensenanza`, `cod_grado`, `desc_grado`, `letra_curso`) VALUES
(1, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A'),
(2, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B'),
(3, 10, 5, '2° nivel de Transición (Kinder)', 'A'),
(4, 10, 5, '2° nivel de Transición (Kinder)', 'B'),
(5, 110, 1, '1° básico', 'A'),
(6, 110, 1, '1° básico', 'B'),
(7, 110, 2, '2° básico', 'A'),
(8, 110, 2, '2° básico', 'B'),
(9, 110, 3, '3° básico', 'A'),
(10, 110, 3, '3° básico', 'B'),
(11, 110, 4, '4° básico', 'A'),
(12, 110, 4, '4° básico', 'B'),
(13, 110, 5, '5° básico', 'A'),
(14, 110, 5, '5° básico', 'B'),
(15, 110, 6, '6° básico', 'A'),
(16, 110, 6, '6° básico', 'B'),
(17, 110, 7, '7° básico', 'A'),
(18, 110, 7, '7° básico', 'B'),
(19, 110, 8, '8° básico', 'A'),
(20, 110, 8, '8° básico', 'B'),
(21, 310, 1, '1° medio', 'A'),
(22, 310, 1, '1° medio', 'B'),
(23, 310, 2, '2° medio', 'A'),
(24, 310, 2, '2° medio', 'B'),
(25, 610, 3, '3° medio', 'A'),
(26, 610, 3, '3° medio', 'B'),
(27, 610, 4, '4° medio', 'A'),
(28, 610, 4, '4° medio', 'B');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `derivacions`
--

CREATE TABLE `derivacions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `run` varchar(255) NOT NULL,
  `digito_ver` varchar(255) NOT NULL,
  `nombre_estudiante` varchar(255) NOT NULL,
  `edad` int(11) NOT NULL,
  `curso` varchar(255) NOT NULL,
  `fecha_derivacion` date NOT NULL,
  `adulto_responsable` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `programa_integracion` tinyint(1) NOT NULL DEFAULT 0,
  `programa_retencion` tinyint(1) NOT NULL DEFAULT 0,
  `indicadores_personal` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`indicadores_personal`)),
  `indicadores_familiar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`indicadores_familiar`)),
  `indicadores_socio_comunitario` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`indicadores_socio_comunitario`)),
  `motivo_derivacion` text DEFAULT NULL,
  `acciones_realizadas` text DEFAULT NULL,
  `sugerencias` text DEFAULT NULL,
  `colaborador` varchar(255) DEFAULT NULL,
  `estado_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `derivacions`
--

INSERT INTO `derivacions` (`id`, `run`, `digito_ver`, `nombre_estudiante`, `edad`, `curso`, `fecha_derivacion`, `adulto_responsable`, `telefono`, `programa_integracion`, `programa_retencion`, `indicadores_personal`, `indicadores_familiar`, `indicadores_socio_comunitario`, `motivo_derivacion`, `acciones_realizadas`, `sugerencias`, `colaborador`, `estado_id`, `created_at`, `updated_at`) VALUES
(3, '26961388', '2', 'REYMOND ZEZE VICENTE VEDIA ORTEGA', 5, '1er nivel de Transición (Pre-kinder) A', '2024-10-07', 'Jose Patricio', '9202560900', 1, 0, '[\"Repitencia o pre-deserci\\u00f3n escolar\",\"Presentaci\\u00f3n personal e higiene\",\"Tratamiento farmacol\\u00f3gico\",\"Tratamiento farmacol\\u00f3gico: sdfsdfsdf\"]', '[\"Adultos con baja escolaridad\",\"Problemas en el establecimiento de l\\u00edmites y normas\",\"Abandono afectivo\"]', '[\"Sectores con conductas infractoras\"]', 'Prueba de motivo', 'Prueba de acciones', 'Prueba de sugerencias', '85', 3, '2024-10-07 19:58:49', '2024-10-21 03:13:06'),
(5, '26110555', '1', 'RENATA PASCAL ABARCA TONACCA', 6, '1° básico A', '2024-10-08', 'ewdsad', '979464735', 0, 1, 'null', 'null', 'null', 'adsf', 'fsdfsdf', 'sdfsdf', '85', 1, '2024-10-08 18:00:58', '2024-10-08 18:00:58'),
(6, '100732666', '8', 'JHERALDINET VEDIA ORDOÑEZ', 16, '3° medio B', '2024-10-19', 'Marcelo Gómez', '9 12345678', 1, 0, '[\"Bajo rendimiento acad\\u00e9mico\"]', '[\"Abandono afectivo\"]', '[\"Sectores con conductas infractoras\"]', 'Bajo rendimiento escolar debido a situaciones externas', 'Intervención el día 12-09-2024', 'Solicitar ayuda al SENAME', '88', 1, '2024-10-20 00:28:47', '2024-10-20 00:28:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrevistas`
--

CREATE TABLE `entrevistas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `derivacion_id` int(11) DEFAULT NULL,
  `citacion_id` int(11) DEFAULT NULL,
  `tipo_entrevista` int(11) DEFAULT NULL,
  `nombre_entrevistado` varchar(255) NOT NULL,
  `curso` varchar(255) NOT NULL,
  `entrevistador` varchar(255) NOT NULL,
  `motivo_id` int(11) NOT NULL,
  `desarrollo_entrevista` text NOT NULL,
  `acuerdos` text NOT NULL,
  `firma_entrevistador` varchar(255) DEFAULT NULL,
  `firma_estudiante` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `entrevistas`
--

INSERT INTO `entrevistas` (`id`, `fecha`, `derivacion_id`, `citacion_id`, `tipo_entrevista`, `nombre_entrevistado`, `curso`, `entrevistador`, `motivo_id`, `desarrollo_entrevista`, `acuerdos`, `firma_entrevistador`, `firma_estudiante`, `created_at`, `updated_at`) VALUES
(10, '2024-10-16', 3, 20, 1, 'REYMOND ZEZE VICENTE VEDIA ORTEGA', '1ER NIVEL DE TRANSICIóN (PRE-KINDER) A', 'LORENA REYES', 3, 'OMG', '[{\"acuerdo\":\"si\",\"plazo\":\"2024-10-21\"}]', NULL, NULL, '2024-10-16 15:25:33', '2024-10-16 15:25:33'),
(13, '2024-10-17', 5, 24, 1, 'RENATA PASCAL ABARCA TONACCA', '1° BáSICO A', 'LORENA REYES', 2, 'SKJDHSZJNMZXCHUEWIFJC', '[{\"acuerdo\":\"dododododood\",\"plazo\":\"2024-10-23\"}]', NULL, NULL, '2024-10-17 15:49:14', '2024-10-17 15:49:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expedientes`
--

CREATE TABLE `expedientes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `run` varchar(255) NOT NULL,
  `digito_ver` varchar(255) NOT NULL,
  `curso` varchar(255) NOT NULL,
  `genero` varchar(255) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `adulto_responsable` varchar(50) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `comuna_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `expedientes`
--

INSERT INTO `expedientes` (`id`, `run`, `digito_ver`, `curso`, `genero`, `fecha_nacimiento`, `direccion`, `email`, `telefono`, `adulto_responsable`, `fecha_creacion`, `created_at`, `updated_at`, `region`, `comuna_id`) VALUES
(1, '26961388', '2', '1er nivel de Transición (Pre-kinder)', 'M', '2019-08-05', 'arza 357', 'lorena.almendra.r@gmail.com', '979464735', 'jose pedro', '2024-10-07 19:41:43', NULL, NULL, '0', 0),
(2, '25012086', '9', '3° básico A', 'M', '2015-06-07', '4 PONIENTE VILLA EXÓTICA', 'KARENBUGUENO9@GMAIL.COM', '30130284', NULL, '2024-10-08 02:18:23', NULL, NULL, '0', 0),
(3, '26110555', '1', '1° básico A', 'F', '2018-02-06', NULL, NULL, NULL, NULL, '2024-10-08 02:19:19', NULL, NULL, '0', 0),
(4, '26938683', '5', '1er nivel de Transición (Pre-kinder) B', 'F', '2019-07-23', NULL, NULL, NULL, NULL, '2024-10-08 13:31:03', NULL, NULL, '0', 0),
(5, '27042117', '2', '1er nivel de Transición (Pre-kinder) A', 'F', '2019-10-03', NULL, NULL, NULL, NULL, '2024-10-10 14:00:02', NULL, NULL, '0', 0),
(6, '26966316', '2', '1er nivel de Transición (Pre-kinder) A', 'F', '2019-08-15', NULL, NULL, NULL, NULL, '2024-10-10 14:00:20', NULL, NULL, '0', 0),
(7, '27039278', '4', '1er nivel de Transición (Pre-kinder) A', 'M', '2019-10-02', NULL, NULL, NULL, NULL, '2024-10-10 14:03:24', NULL, NULL, '0', 0),
(8, '26816350', '6', '1er nivel de Transición (Pre-kinder) A', 'M', '2019-05-04', NULL, NULL, NULL, 'pedro pedro', '2024-10-10 14:08:02', NULL, NULL, '0', 0),
(9, '27186931', '2', '1er nivel de Transición (Pre-kinder) A', 'F', '2020-01-06', NULL, NULL, NULL, NULL, '2024-10-10 20:44:11', NULL, NULL, '0', 0),
(10, '25018760', '2', '3° básico A', 'M', '2015-06-21', 'REGIDOL JOSE MANUEL GONZALEZ VIAL 0469', NULL, '49395755', NULL, '2024-10-11 23:00:40', NULL, NULL, '0', 0),
(11, '23145399', '7', '1° medio A', 'M', '2009-10-11', NULL, NULL, NULL, NULL, '2024-10-17 21:28:00', NULL, NULL, '0', 0),
(12, '23283686', '5', '7° básico B', 'M', '2010-03-23', 'COOPERACION 6334', NULL, NULL, NULL, '2024-10-19 01:24:35', NULL, NULL, '0', 0),
(13, '100732666', '8', '3° medio B', 'M', '2007-12-17', NULL, NULL, NULL, NULL, '2024-10-20 00:24:24', NULL, NULL, '0', 0),
(14, '100720104', '0', '1er nivel de Transición (Pre-kinder) A', 'M', '2019-07-08', NULL, NULL, '91093117', NULL, '2024-10-21 03:04:41', NULL, NULL, '0', 0),
(15, '22293427', '3', '4° medio A', 'F', '2006-12-27', 'LA LINEA S/N', NULL, NULL, NULL, '2024-10-21 03:14:45', NULL, NULL, '0', 0),
(16, '22293427', '3', '4° medio A', 'F', '2006-12-27', 'LA LINEA S/N', NULL, NULL, NULL, '2024-10-21 03:14:48', NULL, NULL, '0', 0),
(17, '22293427', '3', '4° medio A', 'F', '2006-12-27', 'LA LINEA S/N', NULL, NULL, NULL, '2024-10-21 03:15:15', NULL, NULL, '0', 0),
(18, '22293427', '3', '4° medio A', 'F', '2006-12-27', 'LA LINEA S/N', NULL, NULL, NULL, '2024-10-21 03:15:24', NULL, NULL, '0', 0),
(19, '25771931', '6', '1° básico A', 'M', '2017-05-22', NULL, NULL, NULL, NULL, '2024-10-21 03:16:44', NULL, NULL, '0', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matricula`
--

CREATE TABLE `matricula` (
  `id` int(11) NOT NULL,
  `ano` int(11) DEFAULT NULL,
  `cod_tipo_ensenanza` int(11) DEFAULT NULL,
  `cod_grado` int(11) DEFAULT NULL,
  `desc_grado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `letra_curso` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `run` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `digito_ver` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `genero` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nombres` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `apellido_paterno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `apellido_materno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `comuna_residencia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_incorporacion_curso` date DEFAULT NULL,
  `fecha_retiro` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `matricula`
--

INSERT INTO `matricula` (`id`, `ano`, `cod_tipo_ensenanza`, `cod_grado`, `desc_grado`, `letra_curso`, `run`, `digito_ver`, `genero`, `nombres`, `apellido_paterno`, `apellido_materno`, `direccion`, `comuna_residencia`, `email`, `telefono`, `fecha_nacimiento`, `fecha_incorporacion_curso`, `fecha_retiro`) VALUES
(1, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '26961388', '2', 'M', 'REYMOND ZEZE VICENTE', 'VEDIA', 'ORTEGA', NULL, 'IQUIQUE', NULL, NULL, '2019-08-05', '2024-03-05', '1900-01-01'),
(2, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27042117', '2', 'F', 'ELEIKA', 'METELLUS', 'JN LOUIS', NULL, 'QUILLOTA', NULL, NULL, '2019-10-03', '2024-03-12', '1900-01-01'),
(3, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '26966316', '2', 'F', 'MÍA ANAHÍS', 'CASTRO', 'HERRERA', NULL, 'SANTIAGO', NULL, NULL, '2019-08-15', '2024-03-01', '1900-01-01'),
(4, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27039278', '4', 'M', 'TIAGO LUCAS', 'PIERRE', 'PIERRE', NULL, 'SANTIAGO', NULL, NULL, '2019-10-02', '2024-03-01', '1900-01-01'),
(5, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '26816350', '6', 'M', 'EDENSLEY', 'BAZIL', 'SAINT JULES', NULL, 'SANTIAGO', NULL, NULL, '2019-05-04', '2024-07-05', '1900-01-01'),
(6, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27186931', '2', 'F', 'RAFAELA IRENE', 'MIRANDA', 'RIQUELME', NULL, 'MELIPILLA', NULL, NULL, '2020-01-06', '2024-03-06', '1900-01-01'),
(7, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '26967433', '4', 'F', 'ISABELLA SOFÍA', 'MOGOLLON', 'VIRGUEZ', NULL, 'MELIPILLA', NULL, NULL, '2019-08-16', '2024-03-01', '2024-03-20'),
(8, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27244508', '7', 'F', 'ALMENDRA JESÚS', 'AGUILERA', 'QUINTANILLA', NULL, 'MELIPILLA', NULL, NULL, '2020-03-15', '2024-03-01', '1900-01-01'),
(9, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27086451', '1', 'F', 'FLORENCIA BELÉN', 'RIQUELME', 'CONEJERA', 'VILLA B. LEYTON CALLE RAFAEL MORANDE 342', 'MELIPILLA', NULL, '98877008', '2019-11-15', '2024-03-01', '1900-01-01'),
(10, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27153470', '1', 'F', 'ELLEN LUCERO', 'BARRAZA', 'GONZÁLEZ', 'VILLA SOL DEL VALLE, CALLE VALLE DEL ACONCAGUA 2192', 'MELIPILLA', NULL, '69008007', '2020-01-08', '2024-03-01', '1900-01-01'),
(11, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27168628', '5', 'F', 'DEBORA', 'MEDINA', 'NERISSE', NULL, 'MELIPILLA', NULL, NULL, '2020-01-19', '2024-03-01', '1900-01-01'),
(12, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27080785', '2', 'M', 'EDGAR BRAYAN MILTON', 'DÍAZ', 'BUSTAMANTE', NULL, 'MELIPILLA', NULL, NULL, '2019-11-08', '2024-03-01', '1900-01-01'),
(13, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '26970957', 'K', 'M', 'THOMAS IGNACIO', 'CARO', 'PÉREZ', 'OBISPO GUILLERMO VERA 2103', 'MELIPILLA', NULL, '95322368', '2019-08-10', '2024-03-01', '1900-01-01'),
(14, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '26949985', '0', 'M', 'JAIME ALEXIS', 'CABRITA', 'MORALES', 'LOMAS DE MANSO, ETAPA 1 BLOCK 815 DPTO. 101', 'MELIPILLA', NULL, '20900157', '2019-08-02', '2024-03-01', '1900-01-01'),
(15, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27237997', '1', 'M', 'JOAQUÍN ALEJANDRO', 'SILVA', 'VALDOVINOS', NULL, 'MELIPILLA', NULL, NULL, '2020-03-09', '2024-07-05', '1900-01-01'),
(16, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27036525', '6', 'M', 'FACUNDO EFRAÍN', 'FARÍAS', 'QUIROZ', NULL, 'MELIPILLA', NULL, NULL, '2019-10-07', '2024-03-01', '1900-01-01'),
(17, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '26790192', '9', 'M', 'MAX ENRIQUE EMANUEL', 'AGUILAR', 'HERNÁNDEZ', 'CONDOMINIO MANSO DE VELASCO, TORRE 1 DPTO. 401', 'MELIPILLA', NULL, '22220842', '2019-04-10', '2024-03-01', '1900-01-01'),
(18, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27108682', '2', 'M', 'JHONWESCKEN', 'FLEURANTIN', 'RAYMOND', NULL, 'MELIPILLA', NULL, NULL, '2019-12-04', '2024-03-01', '2024-03-22'),
(19, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27221429', '8', 'M', 'PATHENSKY ANGELO', 'DORISSAINT', 'PIERRE-MICHEL', NULL, 'MELIPILLA', NULL, NULL, '2020-02-23', '2024-03-01', '1900-01-01'),
(20, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27195885', '4', 'M', 'GIVENS SLEY', 'PIERRE', 'SOUFFRANCE', NULL, 'MELIPILLA', NULL, NULL, '2020-02-08', '2024-03-01', '1900-01-01'),
(21, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27092240', '6', 'M', 'ROOD BOVENS', 'GUILLAUME', 'JEAN', NULL, 'MELIPILLA', NULL, NULL, '2019-10-29', '2024-03-01', '1900-01-01'),
(22, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27087311', '1', 'F', 'NIKOLE CATHALELLA', 'RUBIO', 'TORRES', NULL, 'MELIPILLA', NULL, NULL, '2019-11-14', '2024-03-05', '1900-01-01'),
(23, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '100720104', '0', 'M', 'RUBEN DARIO', 'PEREZ', 'CELIS', NULL, 'MELIPILLA', NULL, '91093117', '2019-07-08', '2024-03-01', '2024-03-19'),
(24, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27067064', '4', 'M', 'CRISTOBAL ANDRES', 'LIEMPI', 'SAAVEDRA', NULL, 'MELIPILLA', NULL, NULL, '2019-11-02', '2024-03-05', '1900-01-01'),
(25, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '27153453', '1', 'M', 'MATHIAS JAVIER', 'CORDERO', 'FREYTEZ', 'VILLA BIECENTENARIO PAJE. 20, 372 AV. 3 PONIENTE', 'MELIPILLA', NULL, '96486129', '2020-01-08', '2024-03-13', '1900-01-01'),
(26, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '100750257', '1', 'M', 'YEYCOD ELIEL', 'LANES', 'FLORES', NULL, 'MELIPILLA', NULL, NULL, '2019-06-29', '2024-03-14', '1900-01-01'),
(27, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '100755937', '9', 'M', 'DAYANE', 'HERRERA', 'NOGALES', NULL, 'MELIPILLA', NULL, NULL, '2019-04-04', '2024-03-28', '1900-01-01'),
(28, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'A', '100758707', '0', 'M', 'YHAIR', 'VEIZAGA', 'RAMOS', NULL, 'MELIPILLA', NULL, NULL, '2019-07-18', '2024-04-01', '1900-01-01'),
(29, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '26938683', '5', 'F', 'CATALINA NAHIARA', 'DELGADILLO', 'ACUÑA', NULL, 'SANTIAGO', NULL, NULL, '2019-07-23', '2024-03-01', '1900-01-01'),
(30, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '26803946', '5', 'F', 'EMILY AYLIN', 'ESPINOZA', 'LILLO', NULL, 'SANTIAGO', NULL, NULL, '2019-04-18', '2024-03-01', '1900-01-01'),
(31, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '26848542', '2', 'M', 'MATEO ALONSO', 'ACEVEDO', 'LOYOLA', NULL, 'SANTIAGO', NULL, NULL, '2019-05-27', '2024-03-01', '1900-01-01'),
(32, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '27150461', '6', 'M', 'DERECK ALEJANDRO', 'SALAZAR', 'RENGIFO', NULL, 'SANTIAGO', NULL, NULL, '2020-01-05', '2024-03-01', '1900-01-01'),
(33, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '26785289', '8', 'F', 'NETHLY', 'CASSEUS', 'PIERE JULES', NULL, 'SANTIAGO', NULL, NULL, '2019-04-03', '2024-03-01', '1900-01-01'),
(34, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '27191431', '8', 'M', 'DARÍO JAVIER', 'GONZÁLEZ', 'VEGA', 'IGANCIO CARRERA PINTO 1387', 'RENCA', NULL, '21919756', '2020-01-31', '2024-03-07', '1900-01-01'),
(35, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '27018875', '3', 'M', 'PEDRO GABRIEL', 'JEREZ', 'SARAVIA', NULL, 'MELIPILLA', NULL, NULL, '2019-09-17', '2024-03-07', '1900-01-01'),
(36, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '27179972', '1', 'F', 'ANAIS ESPERANZA', 'ABARCA', 'SILVA', NULL, 'MELIPILLA', NULL, NULL, '2019-12-02', '2024-03-01', '1900-01-01'),
(37, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '27007332', '8', 'F', 'RENATA VALENTINA', 'MUÑOZ', 'VERA', NULL, 'MELIPILLA', NULL, NULL, '2019-09-03', '2024-03-01', '1900-01-01'),
(38, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '27168330', '8', 'F', 'MIA ISABELLA', 'COLMENARES', 'COLMENARES', NULL, 'MELIPILLA', NULL, NULL, '2020-01-19', '2024-03-01', '1900-01-01'),
(39, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '27037633', '9', 'F', 'ASTRID TRINIDAD', 'PIÑA', 'GARCÍA', NULL, 'MELIPILLA', NULL, NULL, '2019-10-07', '2024-03-01', '1900-01-01'),
(40, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '26793398', '7', 'M', 'JOAQUÍN ANDRÉS', 'CALDERÓN', 'ALVARADO', NULL, 'MELIPILLA', NULL, NULL, '2019-04-16', '2024-03-01', '1900-01-01'),
(41, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '26780734', '5', 'M', 'MARIANO ANDRÉS', 'PAVEZ', 'MENDOZA', 'VILLA CHACRA SAN PEDRO, EL LAZO 769', 'MELIPILLA', NULL, '50454478', '2019-04-05', '2024-03-01', '1900-01-01'),
(42, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '26915886', '7', 'M', 'MATÍAS ALESSANDRO', 'PORRAS', 'CACERES', 'LAGO CHUNGARA 381', 'MELIPILLA', NULL, '72924732', '2019-07-10', '2024-03-01', '1900-01-01'),
(43, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '27083752', '2', 'M', 'DIEGO ANDRÉS', 'NAVARRO', 'RIQUELME', NULL, 'MELIPILLA', NULL, NULL, '2019-11-14', '2024-03-01', '1900-01-01'),
(44, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '26903838', '1', 'M', 'MATEO IGNACIO', 'TRUJILLO', 'AZÚA', NULL, 'MELIPILLA', NULL, NULL, '2019-07-02', '2024-03-01', '1900-01-01'),
(45, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '26790369', '7', 'M', 'JOAQUÍN ANDRES', 'VALDIVIA', 'CARREÑO', NULL, 'MELIPILLA', NULL, NULL, '2019-04-12', '2024-03-01', '1900-01-01'),
(46, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '27118299', '6', 'M', 'MATEO IGNACIO', 'MÁRQUEZ', 'CERDA', NULL, 'MELIPILLA', NULL, NULL, '2019-12-10', '2024-03-01', '1900-01-01'),
(47, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '27194896', '4', 'F', 'ANAYLI', 'THELEMAQUE', 'PROPHETE', NULL, 'MELIPILLA', NULL, NULL, '2020-02-07', '2024-03-01', '1900-01-01'),
(48, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '27194857', '3', 'F', 'MEYLIN', 'THELEMAQUE', 'PROPHETE', NULL, 'MELIPILLA', NULL, NULL, '2020-02-07', '2024-03-01', '1900-01-01'),
(49, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '27159676', '6', 'M', 'VICENTE ELÍAS', 'ROMERO', 'CABRERA', NULL, 'MELIPILLA', NULL, NULL, '2020-01-11', '2024-03-05', '1900-01-01'),
(50, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '27230980', '9', 'M', 'CRISTIAN MATEO', 'QUISPE', 'SANDOVAL', 'POBL. LAGOS 2 CALLE CABURGA 308', 'MELIPILLA', NULL, '65947745', '2020-03-05', '2024-03-01', '1900-01-01'),
(51, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '26940483', '3', 'F', 'MARGARITA PASCAL', 'VIVEROS', 'OLIVEROS', NULL, 'MELIPILLA', NULL, NULL, '2019-07-21', '2024-03-12', '1900-01-01'),
(52, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '26863591', '2', 'F', 'MIRLANGEDIJ SARA', 'PIERRE', 'JULES', NULL, 'MELIPILLA', NULL, NULL, '2019-06-06', '2024-03-12', '1900-01-01'),
(53, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '100750254', '7', 'F', 'KEYLA', 'GALLARDO', 'FLORES', NULL, 'MELIPILLA', NULL, NULL, '2019-09-26', '2024-03-22', '1900-01-01'),
(54, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '26831865', '8', 'F', 'JUN TING', 'YANG', 'FAN', 'CALLE MERCED', 'MELIPILLA', NULL, '95017291', '2019-04-19', '2024-05-06', '1900-01-01'),
(55, 2024, 10, 4, '1er nivel de Transición (Pre-kinder)', 'B', '26968894', '7', 'M', 'LUCAS ALONSO', 'SALINAS', 'ANABALÓN', 'PASAJE LOS MAITENES', 'MELIPILLA', NULL, '98403171', '2019-08-16', '2024-07-17', '1900-01-01'),
(56, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26433622', '8', 'M', 'ALFONSO IGNACIO', 'CABAÑAS', 'CAMUS', NULL, 'SANTIAGO', NULL, NULL, '2018-08-24', '2024-03-01', '1900-01-01'),
(57, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26212095', '3', 'F', 'EMILIA ALEJANDRA', 'HERNÁNDEZ', 'GONZÁLEZ', NULL, 'SANTIAGO', NULL, NULL, '2018-04-11', '2024-03-01', '1900-01-01'),
(58, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26277900', '9', 'M', 'BALTAZAR LEÓN', 'JIMÉNEZ', 'RIQUELME', NULL, 'SANTIAGO', NULL, NULL, '2018-05-16', '2024-03-01', '1900-01-01'),
(59, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26607398', '4', 'F', 'COLOMBA ESTEFANÍA', 'MORALES', 'GONZÁLEZ', NULL, 'SANTIAGO', NULL, NULL, '2018-12-08', '2024-03-01', '1900-01-01'),
(60, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26343330', '0', 'F', 'AMALIA', 'SUPERVIL', 'JOSEPH', NULL, 'SANTIAGO', NULL, NULL, '2018-06-26', '2024-03-01', '2024-03-27'),
(61, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26580593', '0', 'M', 'TOMÁS SANTIAGO', 'CAROCA', 'SANDOVAL', NULL, 'SANTIAGO', NULL, NULL, '2018-11-18', '2024-03-06', '1900-01-01'),
(62, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '100737587', '1', 'M', 'AMAIA', 'MONTOYA', 'DAVID', NULL, 'MELIPILLA', NULL, NULL, '2018-06-18', '2024-03-06', '1900-01-01'),
(63, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26303319', '1', 'F', 'ISIDORA AMPARO', 'CERVANTES', 'TAPIA', NULL, 'MELIPILLA', NULL, NULL, '2018-06-03', '2024-03-06', '1900-01-01'),
(64, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26257488', '1', 'M', 'MATÍAS ALESSANDRO', 'LOYOLA', 'NAVARRO', NULL, 'MELIPILLA', NULL, NULL, '2018-05-05', '2024-03-01', '1900-01-01'),
(65, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26575365', '5', 'F', 'ANTONELLA SIMONÉ', 'PINTO', 'MANZO', 'ANSELMO OSORIO 118 BENJAMIN ULLOA', 'MELIPILLA', NULL, '50586350', '2018-11-16', '2024-03-01', '1900-01-01'),
(66, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26625442', '3', 'M', 'GASPAR ANDRÉS', 'ABURTO', 'DÍAZ', NULL, 'MELIPILLA', NULL, NULL, '2018-12-24', '2024-03-01', '1900-01-01'),
(67, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '27601254', '1', 'M', 'CARLOS EDUARDO', 'AGUIAR', 'ARTEAGA', 'RAUL SILVA HENRIQUEZ 319, VILLA LEYTON', 'MELIPILLA', NULL, '35332076', '2018-05-21', '2024-03-01', '1900-01-01'),
(68, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26464815', '7', 'F', 'SOPHYA MONSERRATH', 'ALDANA', 'MEDINA', 'LORGIO DAÑIO BASTIAS 619, VILLA FLORENCIA 2', 'MELIPILLA', NULL, '87694587', '2018-09-08', '2024-03-01', '1900-01-01'),
(69, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26363613', '9', 'F', 'MONSERRATH GABRIELA', 'ARAYA', 'TORO', NULL, 'MELIPILLA', NULL, NULL, '2018-07-04', '2024-03-01', '1900-01-01'),
(70, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26676937', '7', 'M', 'KALVEEN CHRIST-ADAIN', 'CHERY', 'CHERY', NULL, 'MELIPILLA', NULL, NULL, '2019-01-23', '2024-03-01', '1900-01-01'),
(71, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26290435', '0', 'M', 'DAMIÁN JESÚS', 'ESPINOZA', 'MARAMBIO', NULL, 'MELIPILLA', NULL, NULL, '2018-05-23', '2024-03-01', '1900-01-01'),
(72, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '100655344', 'K', 'M', 'ROBERT ISAAC', 'GONZALEZ', 'LEON', NULL, 'MELIPILLA', NULL, '36625161', '2018-10-27', '2024-03-01', '1900-01-01'),
(73, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26294649', '5', 'M', 'MATEO IGNACIO', 'HUALME', 'DÍAZ', NULL, 'MELIPILLA', NULL, NULL, '2018-05-27', '2024-03-01', '1900-01-01'),
(74, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26205407', '1', 'M', 'LEANDRO MIGUEL', 'HUENCHE', 'CELEDÓN', 'LOS JAZMINES NORTE AYENCUL 2559', 'MELIPILLA', NULL, '99850400', '2018-04-05', '2024-03-01', '1900-01-01'),
(75, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26337737', '0', 'M', 'ISAAC ALONSO', 'JEAN', 'RETAMAL', NULL, 'MELIPILLA', NULL, NULL, '2018-06-19', '2024-03-01', '1900-01-01'),
(76, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26738185', '2', 'F', 'ISIDORA ISABEL', 'JEREZ', 'VALENZUELA', NULL, 'MELIPILLA', NULL, NULL, '2019-03-08', '2024-03-01', '1900-01-01'),
(77, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26361142', 'K', 'M', 'OLIVER ALFONSO', 'LÓPEZ', 'MANCILLA', NULL, 'MELIPILLA', NULL, NULL, '2018-07-05', '2024-03-01', '1900-01-01'),
(78, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26325981', '5', 'M', 'JOAQUÍN IGNACIO', 'MUÑOZ', 'FRÍAS', NULL, 'MELIPILLA', NULL, NULL, '2018-06-18', '2024-03-01', '1900-01-01'),
(79, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26610312', '3', 'M', 'MÁXIMO ANTONIO', 'ROJAS', 'ORTEGA', NULL, 'MELIPILLA', NULL, NULL, '2018-12-09', '2024-03-01', '1900-01-01'),
(80, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26437855', '9', 'F', 'EMILIA PAZ', 'URIBE', 'RUIZ', 'PARQUE NACIONAL PATAGONIA G-5 POBL. VISTA HERMOSA', 'MELIPILLA', NULL, '94557831', '2018-08-26', '2024-03-01', '1900-01-01'),
(81, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26437820', '6', 'F', 'JOSEFA AMPARO', 'URIBE', 'RUIZ', 'PARQUE NACIONAL PATAGONIA G-5 POBL. VISTA HERMOSA', 'MELIPILLA', NULL, '94557831', '2018-08-26', '2024-03-01', '1900-01-01'),
(82, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26611130', '4', 'F', 'EMILY VICTORIA', 'VALERO', 'CALDERÓN', 'PASAJE LASTENIA ALVAREZ Nº 2271', 'MELIPILLA', NULL, NULL, '2018-12-08', '2024-03-01', '1900-01-01'),
(83, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26763043', '7', 'M', 'GAEL MAXIMILIANO', 'VILLENA', 'PLAZA', 'JUAN DE DIOS DIAZ 2279VILLA FLORENCIA I', 'MELIPILLA', NULL, '65251405', '2019-03-19', '2024-03-01', '1900-01-01'),
(84, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26776577', '4', 'F', 'KEYLEEN FRANCISCA IGNACIA', 'GARRIDO', 'URZÚA', NULL, 'MELIPILLA', NULL, NULL, '2019-03-27', '2024-03-01', '1900-01-01'),
(85, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26765182', '5', 'F', 'AMANDA AYLEEN', 'CONCHA', 'VERA', NULL, 'MELIPILLA', NULL, NULL, '2019-03-22', '2024-03-01', '1900-01-01'),
(86, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26765150', '7', 'M', 'GASPAR ALEJANDRO', 'CONCHA', 'VERA', NULL, 'MELIPILLA', NULL, NULL, '2019-03-22', '2024-03-01', '1900-01-01'),
(87, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'A', '26572194', 'K', 'F', 'ANAÍS VALENTINA', 'ORELLANA', 'GARAY', 'CULENCO', 'PEMUCO', NULL, NULL, '2018-11-12', '2024-03-01', '2024-07-17'),
(88, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26747557', '1', 'F', 'JOUNE-LEY SUZIE', 'NOISETTE', 'CINE', NULL, 'SANTIAGO', NULL, NULL, '2019-03-14', '2024-03-01', '1900-01-01'),
(89, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26655399', '4', 'M', 'RAFAEL IGNACIO', 'NÚÑEZ', 'ACUÑA', NULL, 'SANTIAGO', NULL, NULL, '2019-01-12', '2024-03-01', '1900-01-01'),
(90, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26643769', '2', 'F', 'EMILY PASCALE', 'PIERRE LOUIS', 'ARISTILDE', NULL, 'SANTIAGO', NULL, NULL, '2019-01-04', '2024-03-01', '1900-01-01'),
(91, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26738474', '6', 'F', 'MICAELA', 'PRUSS', 'BLAIZE', NULL, 'SANTIAGO', NULL, NULL, '2019-03-08', '2024-03-01', '1900-01-01'),
(92, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26378248', '8', 'F', 'AGUSTINA PAZ', 'RODRÍGUEZ', 'FUENTES', NULL, 'SANTIAGO', NULL, NULL, '2018-07-20', '2024-03-01', '1900-01-01'),
(93, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26732936', '2', 'M', 'MARTÍN ALONSO', 'VAN DEN BELD', 'HERRERA', NULL, 'SANTIAGO', NULL, NULL, '2019-03-05', '2024-03-01', '1900-01-01'),
(94, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26483215', '2', 'F', 'HUDICA YARAH', 'DATHUS', 'LOUIS', NULL, 'SANTIAGO', NULL, NULL, '2018-09-21', '2024-03-01', '1900-01-01'),
(95, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26553444', '9', 'F', 'ESTIVERNIA', 'ESTIVERNE', 'BEAUBRUN', NULL, 'SANTIAGO', NULL, NULL, '2018-11-03', '2024-03-01', '1900-01-01'),
(96, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26613417', '7', 'F', 'MARTINA AIMARA', 'IBARRA', 'SANTIBÁÑEZ', NULL, 'SANTIAGO', NULL, NULL, '2018-12-10', '2024-03-01', '1900-01-01'),
(97, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26350665', '0', 'M', 'JONATHAN REJIDSON', 'JULIEN', 'JEAN-BAPTISTE', NULL, 'SANTIAGO', NULL, NULL, '2018-06-29', '2024-03-01', '1900-01-01'),
(98, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26506998', '3', 'F', 'FANSES', 'LOUIS', 'DESIR', NULL, 'SANTIAGO', NULL, NULL, '2018-10-03', '2024-03-01', '1900-01-01'),
(99, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26453284', '1', 'M', 'PAOLO IGNACIO', 'PAZ', 'AGUILAR', NULL, 'SANTIAGO', NULL, NULL, '2018-09-02', '2024-03-01', '1900-01-01'),
(100, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26625836', '4', 'F', 'ALICIA REINA ZHURI', 'MILORD', 'MILORD', NULL, 'SANTIAGO', NULL, NULL, '2018-12-19', '2024-03-05', '1900-01-01'),
(101, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26696983', 'K', 'F', 'JADE ESMERALDA', 'ALDANA', 'RÍOS', 'VALLE DE LA LUNA 2230 POBL. SOL DEL VALLE', 'MELIPILLA', NULL, '61802295', '2019-02-08', '2024-03-01', '1900-01-01'),
(102, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26455965', '0', 'F', 'COLOMBA ALEXANDRA', 'ASTUDILLO', 'SÁNCHEZ', 'ALFREDO MARIN 2286, POBL FLORENCIA I', 'MELIPILLA', NULL, '95775255', '2018-09-04', '2024-03-01', '1900-01-01'),
(103, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '100560487', '3', 'F', 'KEYSHLA', 'BECERRA', 'ABREGO', NULL, 'MELIPILLA', NULL, '41866447', '2019-03-13', '2024-03-01', '1900-01-01'),
(104, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26435067', '0', 'M', 'PATRICIO EDUARDO ENRIQUE', 'CARVAJAL', 'GATICA', NULL, 'MELIPILLA', NULL, NULL, '2018-08-21', '2024-03-01', '1900-01-01'),
(105, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '100511179', '6', 'M', 'ABDIEL ALFONSO', 'CEDEÑO', 'CRESPO', NULL, 'MELIPILLA', 'cedenobethsy087@gmail.com', '99360187', '2018-05-03', '2024-03-01', '1900-01-01'),
(106, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26673400', 'K', 'M', 'DIVENSLE', 'CENAT', 'JULES', NULL, 'MELIPILLA', NULL, NULL, '2019-01-23', '2024-03-01', '1900-01-01'),
(107, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '100522848', '0', 'F', 'GIANNA ISABELLA', 'NORIEGA', 'DAVOIN', NULL, 'MELIPILLA', 'abrilgianna2111c@gmail.com', '74380365', '2018-05-11', '2024-03-01', '1900-01-01'),
(108, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26686206', '7', 'M', 'JAIRO MATÍAS EHITAN', 'ORTUBIA', 'MORÁN', 'BRUNO ROMANINI 21 POBL. B. VICUÑA MAKENA', 'MELIPILLA', NULL, '87081621', '2019-01-31', '2024-03-01', '1900-01-01'),
(109, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26667957', '2', 'M', 'KEVENS LEYI', 'SENAT', 'FELIX', NULL, 'MELIPILLA', NULL, NULL, '2019-01-19', '2024-03-01', '1900-01-01'),
(110, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26687397', '2', 'F', 'ORIANA VALENTINA', 'SILVA', 'CARDENAS', NULL, 'MELIPILLA', NULL, NULL, '2019-02-01', '2024-03-01', '1900-01-01'),
(111, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26355719', '0', 'F', 'JULIETA IGNACIA', 'TAPIA', 'CÉSPEDES', NULL, 'MELIPILLA', NULL, NULL, '2018-07-05', '2024-03-01', '1900-01-01'),
(112, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26506290', '3', 'F', 'ALMENDRA ANAÍS', 'VARGAS', 'QUINTANILLA', NULL, 'MELIPILLA', NULL, NULL, '2018-09-27', '2024-03-01', '1900-01-01'),
(113, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26506320', '9', 'M', 'JOSÉ FRANCISCO', 'VARGAS', 'QUINTANILLA', NULL, 'MELIPILLA', NULL, NULL, '2018-09-27', '2024-03-01', '1900-01-01'),
(114, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26584826', '5', 'F', 'IGNACIA AMIRA', 'FIGUEROA', 'GUZMÁN', 'POBL. CHACRA MARIN KATHY QUIROGA', 'MELIPILLA', NULL, '93297914', '2018-11-21', '2024-03-01', '1900-01-01'),
(115, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26388025', '0', 'F', 'SOPHÍA ISIDORA', 'HERNÁNDEZ', 'JERIA', NULL, 'MELIPILLA', NULL, NULL, '2018-07-23', '2024-03-01', '1900-01-01'),
(116, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26709828', 'K', 'F', 'CELESTE ALEJANDRA', 'MARAMBIO', 'BUSTAMANTE', 'ADOLFO LARRAÍN 526, POBL. FLORENCIA II', 'MELIPILLA', NULL, '61225392', '2019-02-16', '2024-03-01', '1900-01-01'),
(117, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26739042', '8', 'F', 'JULIANNA PASCAL', 'MUÑOZ', 'SANTIBÁÑEZ', NULL, 'MELIPILLA', NULL, NULL, '2019-03-06', '2024-03-01', '1900-01-01'),
(118, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '100718053', '1', 'M', 'MIGUEL', 'CAYUBA', 'ORTIZ', NULL, 'MELIPILLA', NULL, NULL, '2018-10-08', '2024-03-01', '1900-01-01'),
(119, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26391600', 'K', 'M', 'RENATO ALLEN', 'OSSES', 'LAZO', NULL, 'MELIPILLA', NULL, NULL, '2018-07-29', '2024-03-01', '1900-01-01'),
(120, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26550912', '6', 'M', 'DANILO', 'MERACIN', 'DELISMA', NULL, 'MELIPILLA', NULL, NULL, '2018-11-01', '2024-03-01', '1900-01-01'),
(121, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26253438', '3', 'F', 'ALLISON ALEJANDRA', 'CASTRO', 'BUSTOS', NULL, 'MELIPILLA', NULL, NULL, '2018-05-02', '2024-03-01', '1900-01-01'),
(122, 2024, 10, 5, '2° nivel de Transición (Kinder)', 'B', '26688436', '2', 'F', 'RAYEN ISABELLA', 'LOPEZ', 'LOPEZ', NULL, 'EL MONTE', NULL, NULL, '2019-01-27', '2024-03-01', '1900-01-01'),
(123, 2024, 110, 1, '1° básico', 'A', '26110555', '1', 'F', 'RENATA PASCAL', 'ABARCA', 'TONACCA', NULL, 'SANTIAGO', NULL, NULL, '2018-02-06', '2024-03-01', '1900-01-01'),
(124, 2024, 110, 1, '1° básico', 'A', '25771931', '6', 'M', 'LUCIANO BENJAMÍN', 'BUSTOS', 'QUIJADA', NULL, 'SANTIAGO', NULL, NULL, '2017-05-22', '2024-03-01', '1900-01-01'),
(125, 2024, 110, 1, '1° básico', 'A', '25883180', '2', 'F', 'VALENTINA AYELEN', 'CATRIÁN', 'GONZÁLEZ', NULL, 'SANTIAGO', NULL, NULL, '2017-08-24', '2024-03-01', '1900-01-01'),
(126, 2024, 110, 1, '1° básico', 'A', '25891657', '3', 'F', 'KRISHNA ISABELLA', 'GALAZ', 'HENRÍQUEZ', NULL, 'SANTIAGO', NULL, NULL, '2017-08-31', '2024-03-01', '1900-01-01'),
(127, 2024, 110, 1, '1° básico', 'A', '26064133', '6', 'M', 'HÉCTOR ALEXANDER', 'LAZO', 'CARTES', NULL, 'SANTIAGO', NULL, NULL, '2018-01-04', '2024-03-01', '1900-01-01'),
(128, 2024, 110, 1, '1° básico', 'A', '25846718', '3', 'M', 'MAXIMILIANO NIBALDO', 'TRUJILLO', 'AZÚA', NULL, 'SANTIAGO', NULL, NULL, '2017-07-24', '2024-03-01', '1900-01-01'),
(129, 2024, 110, 1, '1° básico', 'A', '25795945', '7', 'M', 'MARTÍN ALONSO', 'VERDEJO', 'CARVALLO', NULL, 'SANTIAGO', NULL, NULL, '2017-06-10', '2024-03-01', '1900-01-01'),
(130, 2024, 110, 1, '1° básico', 'A', '25901880', '3', 'F', 'JULIETA BELÉN', 'VERGARA', 'TAPIA', NULL, 'SANTIAGO', NULL, NULL, '2017-09-08', '2024-03-01', '1900-01-01'),
(131, 2024, 110, 1, '1° básico', 'A', '25959884', '2', 'F', 'ALICE ISABELLA', 'VILLAR', 'GÓMEZ', NULL, 'SANTIAGO', NULL, NULL, '2017-10-25', '2024-03-01', '1900-01-01'),
(132, 2024, 110, 1, '1° básico', 'A', '26085899', '8', 'M', 'JULIAN ESTEBAN', 'QUIÑENAO', 'YÁÑEZ', NULL, 'SANTIAGO', NULL, NULL, '2018-01-18', '2024-03-01', '1900-01-01'),
(133, 2024, 110, 1, '1° básico', 'A', '25754731', '0', 'M', 'AARON ALEXANDER', 'RAMOS', 'PALACIOS', NULL, 'SANTIAGO', NULL, NULL, '2017-05-03', '2024-03-01', '1900-01-01'),
(134, 2024, 110, 1, '1° básico', 'A', '25926746', '3', 'M', 'JAVIER IGNACIO', 'RIVERA', 'CARRASCO', NULL, 'SANTIAGO', NULL, NULL, '2017-09-30', '2024-03-01', '1900-01-01'),
(135, 2024, 110, 1, '1° básico', 'A', '25812442', '1', 'F', 'CATALINA ABIGAIL', 'LARA', 'OSORIO', NULL, 'SANTIAGO', NULL, NULL, '2017-06-26', '2024-03-01', '1900-01-01'),
(136, 2024, 110, 1, '1° básico', 'A', '26024574', '0', 'M', 'MAXIMILIANO ALEXIS', 'ORTIZ', 'ARENAS', NULL, 'INDEPENDENCIA', NULL, NULL, '2017-12-08', '2024-03-01', '1900-01-01'),
(137, 2024, 110, 1, '1° básico', 'A', '25948562', '2', 'M', 'DUSTYN EREN HANS', 'CHÁVEZ', 'PEREIRA', 'JOSÉ MANUEL BORGOÑO', 'MAIPÚ', 'PEREIRABARBARA950@GMAIL.COM', '72634316', '2017-10-10', '2024-03-01', '2024-08-05'),
(138, 2024, 110, 1, '1° básico', 'A', '25768385', '0', 'F', 'ZAIRA ARIADNE', 'MONTENEGRO', 'PACO', 'LA REFORMA 395', 'MAIPÚ', NULL, NULL, '2017-05-13', '2024-03-05', '1900-01-01'),
(139, 2024, 110, 1, '1° básico', 'A', '26207244', '4', 'F', 'AILEN PAZ', 'GONZÁLEZ', 'VEGA', 'IGNACIO CARRERA PINTO 1387', 'RENCA', NULL, NULL, '2018-03-26', '2024-03-07', '1900-01-01'),
(140, 2024, 110, 1, '1° básico', 'A', '26021745', '3', 'M', 'LUCAS MATÍAS', 'AGUILAR', 'DONOSO', 'POBL. BENJAMÍN ULLOA, CARLOS AVILÉS 140', 'MELIPILLA', NULL, '89348137', '2017-12-04', '2024-03-01', '1900-01-01'),
(141, 2024, 110, 1, '1° básico', 'A', '25780001', '6', 'F', 'CARLA ANTONIA', 'BUSTOS', 'ORTEGA', 'POBL. BENJAMÍN ULLOA, ESPERANZA SOTO 78', 'MELIPILLA', NULL, '42807397', '2017-05-26', '2024-03-01', '1900-01-01'),
(142, 2024, 110, 1, '1° básico', 'A', '25876401', '3', 'M', 'EDUARDO ISAAC', 'CABELLO', 'NÚÑEZ', NULL, 'MELIPILLA', NULL, NULL, '2017-08-16', '2024-03-01', '1900-01-01'),
(143, 2024, 110, 1, '1° básico', 'A', '26117585', '1', 'M', 'DIEGO ALONSO', 'CASTRO', 'ESPINOZA', 'POBL. PADRE DEMETRIO, CALLE JULIO MONTT 290', 'MELIPILLA', NULL, '61114421', '2018-02-06', '2024-03-01', '1900-01-01'),
(144, 2024, 110, 1, '1° básico', 'A', '26005803', '7', 'M', 'LUCIANO IGNACIO', 'GUAICO', 'ORMAZÁBAL', 'VILLA VISTA HERMOSA, PARQUE NACIONAL ALERCE ANDINO BLOCK B-1 DPTO.201', 'MELIPILLA', NULL, '59530567', '2017-11-25', '2024-03-01', '1900-01-01'),
(145, 2024, 110, 1, '1° básico', 'A', '26049511', '9', 'F', 'VALENTINA SOFÍA', 'GUZMÁN', 'RUMINAUS', 'POBLACIÓN LA FORESTA, LOS PEUMOS 150', 'MELIPILLA', NULL, '87958043', '2017-12-27', '2024-03-01', '1900-01-01'),
(146, 2024, 110, 1, '1° básico', 'A', '25967103', '5', 'F', 'MAITE AMARAL', 'HORMAZÁBAL', 'MUÑOZ', 'VILLA EL PORTAL, PSJE. MARÍA SOLEDAD CAMPOS Nº 108', 'MELIPILLA', NULL, '23502518', '2017-11-01', '2024-03-01', '1900-01-01'),
(147, 2024, 110, 1, '1° básico', 'A', '25733449', 'K', 'F', 'ALICIA FERNANDA', 'SOTO', 'CARRASCO', NULL, 'MELIPILLA', NULL, NULL, '2017-04-12', '2024-03-01', '1900-01-01'),
(148, 2024, 110, 1, '1° básico', 'A', '25733494', '5', 'F', 'AMANDA SOFÍA', 'SOTO', 'CARRASCO', NULL, 'MELIPILLA', NULL, NULL, '2017-04-12', '2024-03-01', '1900-01-01'),
(149, 2024, 110, 1, '1° básico', 'A', '25935004', '2', 'F', 'JENIFER EMILIA', 'VÉLIZ', 'SANTIBÁÑEZ', 'PAPA PIO 11 CASA 117 POBL. PABLO LIZAMA', 'MELIPILLA', NULL, '35679420', '2017-10-07', '2024-03-01', '1900-01-01'),
(150, 2024, 110, 1, '1° básico', 'A', '25761578', '2', 'F', 'AGUSTINA IGNACIA', 'VERA', 'VALENZUELA', NULL, 'MELIPILLA', NULL, NULL, '2017-05-11', '2024-03-01', '1900-01-01'),
(151, 2024, 110, 1, '1° básico', 'A', '25914148', '6', 'M', 'DANIEL ÁNGEL JESÚS', 'MARDONES', 'ALMARZA', 'VILLA RENACER, JOSÉ SANTOS ROJAS 456', 'MELIPILLA', NULL, '77727922', '2017-09-19', '2024-03-01', '1900-01-01'),
(152, 2024, 110, 1, '1° básico', 'A', '25927607', '1', 'M', 'EDGAR IGNACIO', 'MARTÍNEZ', 'CONEJERA', NULL, 'MELIPILLA', NULL, NULL, '2017-10-02', '2024-03-01', '1900-01-01'),
(153, 2024, 110, 1, '1° básico', 'A', '25985245', '5', 'M', 'CRISTÓBAL ALONSO', 'PAVEZ', 'MUÑOZ', 'VILLA EL PORTAL, PSJE. MARÍA SOLEDAD CAMPO Nº 107', 'MELIPILLA', NULL, '79281911', '2017-11-13', '2024-03-01', '1900-01-01'),
(154, 2024, 110, 1, '1° básico', 'A', '25943412', '2', 'F', 'DANAE AYLIN', 'QUINTANILLA', 'VEAS', NULL, 'MELIPILLA', NULL, NULL, '2017-10-15', '2024-03-01', '1900-01-01'),
(155, 2024, 110, 1, '1° básico', 'A', '26053682', '6', 'F', 'LEONOR AGUSTINA', 'RIVEROS', 'LOYOLA', 'VILLA LOS ALTOS DE CANTILLANA PSJE. EL ÑIRE 2113', 'MELIPILLA', NULL, '44682819', '2017-12-28', '2024-03-01', '1900-01-01'),
(156, 2024, 110, 1, '1° básico', 'A', '100546807', '4', 'F', 'ANA BELEN', 'SANCHEZ', 'MENDEZ', NULL, 'MELIPILLA', NULL, '79733358', '2017-09-06', '2024-03-01', '1900-01-01'),
(157, 2024, 110, 1, '1° básico', 'A', '25895752', '0', 'F', 'SARA DEL CARMEN', 'SERRANO', 'VILCHES', 'POBL. BICENTENARIO, 3 PONIENTE, PSJE. 2 CASA 373', 'MELIPILLA', NULL, '61172113', '2017-09-01', '2024-03-01', '1900-01-01'),
(158, 2024, 110, 1, '1° básico', 'A', '25982708', '6', 'M', 'IAN MATEO', 'FUENTES', 'PALACIOS', 'GABRIELA MISTRAL 1891', 'MELIPILLA', NULL, NULL, '2017-11-09', '2024-03-01', '1900-01-01'),
(159, 2024, 110, 1, '1° básico', 'A', '25727778', 'K', 'M', 'ALAN IGNACIO', 'RODRÍGUEZ', 'TOBAR', NULL, 'MELIPILLA', NULL, NULL, '2017-04-06', '2024-03-01', '1900-01-01'),
(160, 2024, 110, 1, '1° básico', 'A', '26045665', '2', 'M', 'AGUSTÍN NICOLÁS', 'ATENAS', 'ARMIJO', NULL, 'MELIPILLA', NULL, NULL, '2017-12-20', '2024-03-01', '1900-01-01'),
(161, 2024, 110, 1, '1° básico', 'A', '28258665', '7', 'M', 'LUCIO SALVADOR', 'OLIVO', 'PADILLA', 'PASAJE RAFAEL MORANDE BERNARDO LEYTON', 'MELIPILLA', NULL, '98838641', '2017-12-06', '2024-03-01', '1900-01-01'),
(162, 2024, 110, 1, '1° básico', 'B', '25802146', '0', 'M', 'MAURICIO ISMAEL', 'ARANDA', 'ORTIZ', NULL, 'SANTIAGO', NULL, NULL, '2017-06-12', '2024-03-01', '1900-01-01'),
(163, 2024, 110, 1, '1° básico', 'B', '25780673', '1', 'F', 'ÁMBAR NIYARETH', 'AZÓCAR', 'VALDÉS', NULL, 'SANTIAGO', NULL, NULL, '2017-05-18', '2024-03-01', '1900-01-01'),
(164, 2024, 110, 1, '1° básico', 'B', '26182669', '0', 'M', 'MARTÍN IGNACIO', 'BARRAZA', 'CERDA', NULL, 'SANTIAGO', NULL, NULL, '2018-03-22', '2024-03-01', '1900-01-01'),
(165, 2024, 110, 1, '1° básico', 'B', '25930559', '4', 'M', 'IAN EMMANUEL', 'CASTAÑEDA', 'PAREDES', NULL, 'SANTIAGO', NULL, NULL, '2017-10-02', '2024-03-01', '1900-01-01'),
(166, 2024, 110, 1, '1° básico', 'B', '26017143', '7', 'F', 'ISABELLA MARIANA', 'MORA', 'JORQUERA', 'PINTOR JOSE PEROTTI 377', 'SANTIAGO', NULL, '36612384', '2017-11-30', '2024-03-01', '1900-01-01'),
(167, 2024, 110, 1, '1° básico', 'B', '26157045', '9', 'F', 'ÁMBAR TRINIDAD', 'PIÑA', 'FERRERE', NULL, 'SANTIAGO', NULL, NULL, '2018-03-06', '2024-03-01', '1900-01-01'),
(168, 2024, 110, 1, '1° básico', 'B', '25747462', '3', 'F', 'VICTORIA ISABELLA', 'VERDEJO', 'LILLO', NULL, 'SANTIAGO', NULL, NULL, '2017-04-25', '2024-03-01', '1900-01-01'),
(169, 2024, 110, 1, '1° básico', 'B', '25786002', '7', 'F', 'ANTONIA ESTEFANÍA', 'VIDAL', 'FLORES', NULL, 'SANTIAGO', NULL, NULL, '2017-05-28', '2024-03-01', '1900-01-01'),
(170, 2024, 110, 1, '1° básico', 'B', '26151081', '2', 'M', 'JAVIER IGNACIO', 'GALARCE', 'SALAZAR', NULL, 'SANTIAGO', NULL, NULL, '2018-03-04', '2024-03-01', '1900-01-01'),
(171, 2024, 110, 1, '1° básico', 'B', '25858955', '6', 'F', 'ANTONELA IGNACIA', 'INOSTROZA', 'SÁNCHEZ', NULL, 'SANTIAGO', NULL, NULL, '2017-08-05', '2024-03-01', '1900-01-01'),
(172, 2024, 110, 1, '1° básico', 'B', '25789333', '2', 'M', 'MOISÉS ALEXANDER', 'JEREZ', 'REYES', NULL, 'SANTIAGO', NULL, NULL, '2017-05-31', '2024-03-01', '1900-01-01'),
(173, 2024, 110, 1, '1° básico', 'B', '26197620', 'K', 'M', 'IGNACIO AARON', 'JERIA', 'PERALTA', NULL, 'SANTIAGO', NULL, NULL, '2018-03-26', '2024-03-01', '1900-01-01'),
(174, 2024, 110, 1, '1° básico', 'B', '26145533', '1', 'F', 'VALENTINA ESPERANZA', 'LILLO', 'JARA', NULL, 'SANTIAGO', NULL, NULL, '2018-02-25', '2024-03-01', '1900-01-01'),
(175, 2024, 110, 1, '1° básico', 'B', '26184034', '0', 'M', 'ISSAGE MATEO', 'ORIMA', 'ALCIMA', NULL, 'SANTIAGO', NULL, NULL, '2018-03-21', '2024-03-01', '1900-01-01'),
(176, 2024, 110, 1, '1° básico', 'B', '26163576', '3', 'M', 'CARLOS DAVID', 'ARENA', 'ESCALONA', NULL, 'SANTIAGO', NULL, NULL, '2018-03-08', '2024-03-01', '1900-01-01'),
(177, 2024, 110, 1, '1° básico', 'B', '26005715', '4', 'M', 'PEDRO FELIPE', 'SILVA', 'RUBIO', NULL, 'SANTIAGO', NULL, NULL, '2017-11-20', '2024-03-01', '1900-01-01'),
(178, 2024, 110, 1, '1° básico', 'B', '25957626', '1', 'F', 'ANTONELLA IGNACIA', 'BASUALTO', 'AGUILAR', 'PASAJE 2367 3 PONIENTE POBL. BICENTENARIO', 'MELIPILLA', NULL, '63062416', '2017-10-24', '2024-03-01', '1900-01-01'),
(179, 2024, 110, 1, '1° básico', 'B', '100504912', '8', 'M', 'Angel Mateo', 'Blanco', 'Moya', NULL, 'MELIPILLA', 'JARDIN.CANELUZ@CORMUMEL.CL', NULL, '2018-02-07', '2024-03-01', '1900-01-01'),
(180, 2024, 110, 1, '1° básico', 'B', '26116254', '7', 'M', 'JULIÁN MAXIMILIANO', 'BRITO', 'CÉSPEDES', NULL, 'MELIPILLA', NULL, NULL, '2018-02-10', '2024-03-01', '1900-01-01'),
(181, 2024, 110, 1, '1° básico', 'B', '26132508', 'K', 'M', 'RUBÉN ANTONIO', 'CASTRO', 'PASCAL', 'PAJE. EDUARDO CASTAÑEDA CERDA 81 POBL. BENJAMIN ULLOA', 'MELIPILLA', NULL, '96458523', '2018-02-19', '2024-03-01', '1900-01-01'),
(182, 2024, 110, 1, '1° básico', 'B', '25758529', '8', 'F', 'NAYARA', 'CORONADO', 'QUISPE', 'LOS MAITENES', 'MELIPILLA', NULL, '94568696', '2017-05-05', '2024-03-01', '1900-01-01'),
(183, 2024, 110, 1, '1° básico', 'B', '25871525', 'K', 'F', 'FERNANDA AGUSTINA', 'CORTÉS', 'JELDRES', 'LIBERTAD 2043 TORRE 6 405 CONDOMINIO MANSO DE VELASCO', 'MELIPILLA', NULL, '94856747', '2017-08-10', '2024-03-01', '1900-01-01'),
(184, 2024, 110, 1, '1° básico', 'B', '25810369', '6', 'F', 'VALENTINA ANAÍS', 'MEZA', 'CORNEJO', 'GABIN UGALDE CATALAN 1838 POBL. B. ULLOA', 'MELIPILLA', NULL, NULL, '2017-06-19', '2024-03-01', '1900-01-01'),
(185, 2024, 110, 1, '1° básico', 'B', '28147773', '0', 'F', 'YITZEL ESTHER', 'ORTIZ', 'DUARTE', NULL, 'MELIPILLA', NULL, '22406379', '2018-01-18', '2024-03-01', '1900-01-01'),
(186, 2024, 110, 1, '1° básico', 'B', '100719157', '6', 'M', 'LIAN LUCAS', 'QUESPI', 'ARANIBAR', 'VILLA COLINIAL 2, VALENTIN SILVA', 'MELIPILLA', NULL, '99461432', '2017-10-16', '2024-03-01', '1900-01-01'),
(187, 2024, 110, 1, '1° básico', 'B', '26198231', '5', 'F', 'TRINIDAD MONSERRAT', 'SALAZAR', 'JELDRES', NULL, 'MELIPILLA', NULL, NULL, '2018-03-25', '2024-03-01', '1900-01-01'),
(188, 2024, 110, 1, '1° básico', 'B', '26138375', '6', 'M', 'ALEXIS ALEJANDRO', 'SÁNCHEZ', 'HERNÁNDEZ', 'JOSE PEROTTI 367 POBL. ILUSIONES COMPARTIDAS', 'MELIPILLA', NULL, '74194277', '2018-02-23', '2024-03-01', '1900-01-01'),
(189, 2024, 110, 1, '1° básico', 'B', '25904989', 'K', 'M', 'LEÓN ALONSO', 'TAPIA', 'RAMÍREZ', 'VALENTIL SILVA COLONIAL 342', 'MELIPILLA', NULL, '99950361', '2017-09-09', '2024-03-01', '1900-01-01'),
(190, 2024, 110, 1, '1° básico', 'B', '25780942', '0', 'F', 'MAITE AGUSTINA', 'VERGARA', 'ALMONACID', 'LAS MAGNOLIAS 137 POBL. LA FORESTA', 'MELIPILLA', NULL, '45343820', '2017-05-24', '2024-03-01', '1900-01-01'),
(191, 2024, 110, 1, '1° básico', 'B', '25803065', '6', 'F', 'AGUSTINA IGNACIA', 'ZÚÑIGA', 'BAEZA', 'RAFAEL MORANDE 326, VILLA BERNARDO LEYTON', 'MELIPILLA', NULL, '79394265', '2017-06-15', '2024-03-01', '1900-01-01'),
(192, 2024, 110, 1, '1° básico', 'B', '25800363', '2', 'M', 'ALONSO EZEQUIEL', 'DÍAZ', 'GONZÁLEZ', NULL, 'MELIPILLA', NULL, NULL, '2017-06-13', '2024-03-01', '1900-01-01'),
(193, 2024, 110, 1, '1° básico', 'B', '25767310', '3', 'M', 'MARTÍN ANDRÉS', 'GONZÁLEZ', 'ALLENDES', NULL, 'MELIPILLA', NULL, NULL, '2017-05-12', '2024-03-01', '1900-01-01'),
(194, 2024, 110, 1, '1° básico', 'B', '25790213', '7', 'M', 'TOMÁS IGNACIO', 'HERMOSILLA', 'GARRIDO', NULL, 'MELIPILLA', NULL, NULL, '2017-06-03', '2024-03-01', '1900-01-01'),
(195, 2024, 110, 1, '1° básico', 'B', '26108391', '4', 'M', 'AGUSTÍN ANDRÉS', 'LEÓN', 'SALINAS', 'OBISPO GUILLERMO VERA 2126, POBL. PABLO LIZAMA', 'MELIPILLA', NULL, '61425846', '2018-02-04', '2024-03-01', '1900-01-01'),
(196, 2024, 110, 1, '1° básico', 'B', '26013391', '8', 'F', 'JHENDELYN FRANCHESCA', 'MARDONES', 'SILVA', 'AVENIDA CIRCUNVALACIÓN LOS VALLES', 'MELIPILLA', NULL, '98885194', '2017-11-27', '2024-03-01', '1900-01-01'),
(197, 2024, 110, 1, '1° básico', 'B', '27179749', '4', 'M', 'YAHEL EFREN', 'SEDANO', 'ACUÑA', NULL, 'MELIPILLA', NULL, NULL, '2018-01-22', '2024-03-01', '1900-01-01'),
(198, 2024, 110, 1, '1° básico', 'B', '100490636', '1', 'M', 'SEBASTIAN', 'SASARI', 'CLAROS', NULL, 'MELIPILLA', NULL, '31490115', '2017-11-15', '2024-03-01', '1900-01-01'),
(199, 2024, 110, 1, '1° básico', 'B', '26106163', '5', 'M', 'ALEX DIEGO', 'SEPÚLVEDA', 'VENEGAS', NULL, 'MELIPILLA', NULL, NULL, '2018-02-01', '2024-03-01', '1900-01-01'),
(200, 2024, 110, 1, '1° básico', 'B', '26088626', '6', 'M', 'AGUSTÍN ALEJANDRO', 'MANZO', 'MALDONADO', NULL, 'MELIPILLA', NULL, NULL, '2018-01-23', '2024-03-01', '1900-01-01'),
(201, 2024, 110, 1, '1° básico', 'B', '25869960', '2', 'F', 'DONGAËLLE', 'SAUVEUR', 'SAUVEUR', NULL, 'MELIPILLA', NULL, NULL, '2017-08-11', '2024-03-01', '1900-01-01'),
(202, 2024, 110, 1, '1° básico', 'B', '26119985', '8', 'M', 'MESSIANSKY', 'ESTEMILE', 'PICOT', NULL, 'ALHUÉ', NULL, NULL, '2018-02-07', '2024-03-01', '2024-05-06'),
(203, 2024, 110, 1, '1° básico', 'B', '26139934', '2', 'M', 'EMILIANO NEWEN', 'NAVARRO', 'ROJAS', 'EL LUCHADOR PARCELA 7 LAS MERCEDES', 'MARÍA PINTO', 'MASSIELROJASM.90@GMAIL.COM', '84907037', '2018-02-24', '2024-03-01', '1900-01-01'),
(204, 2024, 110, 2, '2° básico', 'A', '25461850', '0', 'M', 'JOSÉ IGNACIO', 'ALFARO', 'AGUILAR', NULL, 'SANTIAGO', NULL, NULL, '2016-07-29', '2024-03-01', '1900-01-01'),
(205, 2024, 110, 2, '2° básico', 'A', '25438234', '5', 'F', 'ALISON LUCÍA', 'ÁLVAREZ', 'VALENZUELA', NULL, 'SANTIAGO', NULL, NULL, '2016-06-29', '2024-03-01', '1900-01-01'),
(206, 2024, 110, 2, '2° básico', 'A', '25422013', '2', 'M', 'ROBERTO ANTONIO', 'ITURRA', 'GARCÍA', NULL, 'SANTIAGO', NULL, NULL, '2016-06-18', '2024-03-01', '1900-01-01'),
(207, 2024, 110, 2, '2° básico', 'A', '25467527', 'K', 'M', 'DENIS GABRIEL', 'LOYOLA', 'PORRAS', NULL, 'SANTIAGO', NULL, NULL, '2016-08-02', '2024-03-01', '1900-01-01'),
(208, 2024, 110, 2, '2° básico', 'A', '25351796', '4', 'M', 'JOAQUÍN AMARO', 'MOLINA', 'SANTIBÁÑEZ', NULL, 'SANTIAGO', NULL, NULL, '2016-04-16', '2024-03-01', '1900-01-01'),
(209, 2024, 110, 2, '2° básico', 'A', '25609437', '1', 'M', 'MATEO IGNACIO', 'NÚÑEZ', 'BARRÍA', NULL, 'SANTIAGO', NULL, NULL, '2016-12-25', '2024-03-01', '1900-01-01'),
(210, 2024, 110, 2, '2° básico', 'A', '25482474', '7', 'F', 'SARAY ANTONIA', 'NÚÑEZ', 'ORMAZÁBAL', NULL, 'SANTIAGO', NULL, NULL, '2016-08-20', '2024-03-01', '1900-01-01'),
(211, 2024, 110, 2, '2° básico', 'A', '25616454', 'K', 'F', 'RAFAELLA AGUSTINA', 'PIÑA', 'GUTIÉRREZ', NULL, 'SANTIAGO', NULL, NULL, '2017-01-02', '2024-03-01', '1900-01-01'),
(212, 2024, 110, 2, '2° básico', 'A', '25587586', '8', 'F', 'VALENTINA ALMENDRA', 'RIQUELME', 'LOBOS', NULL, 'SANTIAGO', NULL, NULL, '2016-12-01', '2024-03-01', '1900-01-01'),
(213, 2024, 110, 2, '2° básico', 'A', '25713175', '0', 'M', 'GASPAR MILÁN', 'SÁEZ', 'FUENTES', NULL, 'SANTIAGO', NULL, NULL, '2017-03-20', '2024-03-01', '1900-01-01'),
(214, 2024, 110, 2, '2° básico', 'A', '25591514', '2', 'F', 'AMPARO HAYDÉE', 'SALAZAR', 'VARGAS', NULL, 'SANTIAGO', NULL, NULL, '2016-12-06', '2024-03-01', '1900-01-01'),
(215, 2024, 110, 2, '2° básico', 'A', '25437795', '3', 'F', 'SOPHIA AYLEN', 'VILLAR', 'GÓMEZ', NULL, 'SANTIAGO', NULL, NULL, '2016-07-09', '2024-03-01', '1900-01-01'),
(216, 2024, 110, 2, '2° básico', 'A', '25431259', '2', 'F', 'SHARON ELENA', 'CUELLAR', 'CUELLAR', NULL, 'SANTIAGO', NULL, NULL, '2016-06-30', '2024-03-01', '1900-01-01'),
(217, 2024, 110, 2, '2° básico', 'A', '25378536', '5', 'M', 'ANGÉL AARÓN', 'DÍAZ', 'MOYA', NULL, 'SANTIAGO', NULL, NULL, '2016-05-10', '2024-03-01', '1900-01-01'),
(218, 2024, 110, 2, '2° básico', 'A', '25450407', '6', 'F', 'LIZ CATALEYA', 'FRELIJJ', 'VEGA', NULL, 'SANTIAGO', NULL, NULL, '2016-07-24', '2024-03-01', '1900-01-01'),
(219, 2024, 110, 2, '2° básico', 'A', '25567113', '8', 'F', 'ISIDORA ERCIRA', 'HORMAZÁBAL', 'CAYUL', NULL, 'SANTIAGO', NULL, NULL, '2016-11-12', '2024-03-01', '1900-01-01'),
(220, 2024, 110, 2, '2° básico', 'A', '25523670', '9', 'F', 'SOPHIA', 'GEORGES', 'PHILIPPE', NULL, 'SANTIAGO', NULL, NULL, '2016-09-26', '2024-03-01', '1900-01-01'),
(221, 2024, 110, 2, '2° básico', 'A', '25598976', '6', 'F', 'AGUSTINA IGNACIA', 'CARO', 'PÉREZ', NULL, 'SANTIAGO', NULL, NULL, '2016-12-12', '2024-03-05', '1900-01-01'),
(222, 2024, 110, 2, '2° básico', 'A', '25385367', '0', 'F', 'ANTONELLA IGNACIA', 'CRISOSTO', 'HUARACÁN', 'LOS CONQUISTADORES', 'CERRO NAVIA', NULL, '96417296', '2016-05-21', '2024-03-01', '1900-01-01'),
(223, 2024, 110, 2, '2° básico', 'A', '25634912', '4', 'F', 'AGUSTINA ANDREA', 'LABARCA', 'ZAPATA', 'FILOMENA GARATE 740', 'QUILICURA', NULL, NULL, '2017-01-16', '2024-03-01', '1900-01-01'),
(224, 2024, 110, 2, '2° básico', 'A', '25693035', '8', 'M', 'FREDERYCK ABDELA', 'ÁLVAREZ', 'PÉREZ', NULL, 'MELIPILLA', NULL, NULL, '2017-02-24', '2024-03-01', '1900-01-01'),
(225, 2024, 110, 2, '2° básico', 'A', '25458668', '4', 'M', 'AGUSTÍN NICOLÁS', 'BECERRA', 'ARRIAZA', NULL, 'MELIPILLA', NULL, NULL, '2016-07-30', '2024-03-01', '1900-01-01'),
(226, 2024, 110, 2, '2° básico', 'A', '100484415', '3', 'M', 'LUCAS DANILO', 'BLANCO', 'MOYA', NULL, 'MELIPILLA', 'jardin.caneluz@cormumel.cl', NULL, '2016-06-05', '2024-03-01', '1900-01-01'),
(227, 2024, 110, 2, '2° básico', 'A', '25609962', '4', 'F', 'EMMA BELÉN', 'CARRASCO', 'GIGLIO', NULL, 'MELIPILLA', NULL, NULL, '2016-12-16', '2024-03-01', '1900-01-01'),
(228, 2024, 110, 2, '2° básico', 'A', '25381145', '5', 'M', 'EDUARDO GABRIEL', 'CÉSPEDES', 'CORNEJO', NULL, 'MELIPILLA', NULL, NULL, '2016-05-16', '2024-03-01', '1900-01-01'),
(229, 2024, 110, 2, '2° básico', 'A', '25384771', '9', 'M', 'RAZIEL ALFONSO', 'CONTRERAS', 'PEZOA', NULL, 'MELIPILLA', NULL, NULL, '2016-05-22', '2024-03-01', '1900-01-01'),
(230, 2024, 110, 2, '2° básico', 'A', '25364537', '7', 'F', 'MÁRIAM KARINA', 'CORREA', 'MUÑOZ', NULL, 'MELIPILLA', NULL, NULL, '2016-04-25', '2024-03-01', '1900-01-01'),
(231, 2024, 110, 2, '2° básico', 'A', '25435296', '9', 'M', 'AGUSTÍN EMILIO', 'LÓPEZ', 'PLAZA', NULL, 'MELIPILLA', NULL, NULL, '2016-07-08', '2024-03-01', '1900-01-01'),
(232, 2024, 110, 2, '2° básico', 'A', '25462642', '2', 'M', 'ISAÍAS ABRAHAM', 'MARIQUEO', 'ROZAS', NULL, 'MELIPILLA', NULL, NULL, '2016-07-28', '2024-03-01', '1900-01-01'),
(233, 2024, 110, 2, '2° básico', 'A', '25351494', '9', 'F', 'VANESA TRINIDAD', 'PIÑA', 'ESPINOZA', NULL, 'MELIPILLA', NULL, NULL, '2016-04-13', '2024-03-01', '1900-01-01'),
(234, 2024, 110, 2, '2° básico', 'A', '25475109', 'K', 'M', 'LIAN ALEXANDER', 'PORRAS', 'CACERES', NULL, 'MELIPILLA', NULL, NULL, '2016-08-09', '2024-03-01', '1900-01-01'),
(235, 2024, 110, 2, '2° básico', 'A', '25573362', '1', 'M', 'ALFREDO ANDRÉS', 'QUINTEROS', 'TORRES', 'PASAJE ARTURO PACHECO LOS JAZMINES', 'MELIPILLA', NULL, '78809485', '2016-11-20', '2024-03-01', '1900-01-01'),
(236, 2024, 110, 2, '2° básico', 'A', '25428972', '8', 'F', 'IVANNA JESÚS', 'RAMIREZ', 'CAYUQUEO', NULL, 'MELIPILLA', NULL, NULL, '2016-07-01', '2024-03-01', '1900-01-01'),
(237, 2024, 110, 2, '2° básico', 'A', '25720439', '1', 'M', 'RODRIGO ANDRÉS', 'RAMÍREZ', 'ROCO', NULL, 'MELIPILLA', NULL, NULL, '2017-03-24', '2024-03-01', '1900-01-01'),
(238, 2024, 110, 2, '2° básico', 'A', '25673199', '1', 'M', 'ALONSO ANDRÉS', 'SANDOVAL', 'SILVA', NULL, 'MELIPILLA', NULL, NULL, '2017-02-12', '2024-03-01', '1900-01-01'),
(239, 2024, 110, 2, '2° básico', 'A', '25613826', '3', 'M', 'FELIPE ALONSO', 'GARRIDO', 'ABARCA', NULL, 'MELIPILLA', NULL, NULL, '2016-12-27', '2024-03-01', '1900-01-01'),
(240, 2024, 110, 2, '2° básico', 'A', '25614527', '8', 'F', 'ISABELLA ANTONIA', 'GÓNGORA', 'GRANIFO', NULL, 'MELIPILLA', NULL, NULL, '2016-12-29', '2024-03-01', '1900-01-01'),
(241, 2024, 110, 2, '2° básico', 'A', '25552219', '1', 'F', 'JAVIERA VALENTINA', 'HUERTA', 'JORQUERA', NULL, 'MELIPILLA', NULL, NULL, '2016-10-23', '2024-03-01', '1900-01-01'),
(242, 2024, 110, 2, '2° básico', 'A', '25423033', '2', 'F', 'ARIADNE DANAE', 'INOSTROZA', 'QUINTANILLA', NULL, 'MELIPILLA', NULL, NULL, '2016-06-25', '2024-03-01', '1900-01-01'),
(243, 2024, 110, 2, '2° básico', 'A', '25592375', '7', 'F', 'ISIDORA PASCAL', 'CÁCERES', 'URTUBIA', NULL, 'MELIPILLA', NULL, NULL, '2016-12-05', '2024-03-05', '1900-01-01'),
(244, 2024, 110, 2, '2° básico', 'B', '25393399', '2', 'M', 'SALVADOR MATEO', 'OLIVARES', 'CORTEZ', '4 PONIENTE VILLA EXÓTICA', 'CALAMA', 'KARENBUGUENO9@GMAIL.COM', '30130284', '2016-05-07', '2024-03-05', '1900-01-01'),
(245, 2024, 110, 2, '2° básico', 'B', '25492897', '6', 'F', 'ANISSA TRINIDAD', 'BASTÍAS', 'MÁRQUEZ', NULL, 'SANTIAGO', NULL, NULL, '2016-08-28', '2024-03-01', '1900-01-01'),
(246, 2024, 110, 2, '2° básico', 'B', '25412421', '4', 'F', 'MONSERRAT EUGENIA', 'BETANCUR', 'CÁCERES', NULL, 'SANTIAGO', NULL, NULL, '2016-06-15', '2024-03-01', '1900-01-01'),
(247, 2024, 110, 2, '2° básico', 'B', '25621143', '2', 'F', 'PAULA ISABELLA', 'BUSTAMANTE', 'VIDELA', NULL, 'SANTIAGO', NULL, NULL, '2017-01-04', '2024-03-01', '1900-01-01'),
(248, 2024, 110, 2, '2° básico', 'B', '25378503', '9', 'F', 'TRINIDAD BELÉN DEL PILAR', 'CARDOZA', 'CARDOZA', NULL, 'SANTIAGO', NULL, NULL, '2016-05-15', '2024-03-01', '1900-01-01'),
(249, 2024, 110, 2, '2° básico', 'B', '25649333', '0', 'F', 'AMELIA ROSA', 'CARREÑO', 'CARREÑO', NULL, 'SANTIAGO', NULL, NULL, '2017-01-21', '2024-03-01', '1900-01-01'),
(250, 2024, 110, 2, '2° básico', 'B', '25336595', '1', 'F', 'SARITA JAVIERA', 'CONTRERAS', 'URTUBIA', NULL, 'SANTIAGO', NULL, NULL, '2016-04-01', '2024-03-01', '1900-01-01'),
(251, 2024, 110, 2, '2° básico', 'B', '25615602', '4', 'M', 'MATEO ISAAC', 'DE SOUZA', 'IBARRA', NULL, 'SANTIAGO', NULL, NULL, '2016-12-30', '2024-03-01', '1900-01-01'),
(252, 2024, 110, 2, '2° básico', 'B', '25476439', '6', 'M', 'AUGUSTO AARON', 'LÓPEZ', 'CARRASCO', NULL, 'SANTIAGO', NULL, NULL, '2016-08-13', '2024-03-01', '1900-01-01'),
(253, 2024, 110, 2, '2° básico', 'B', '25373540', '6', 'M', 'VICENTE ELIAN', 'OLEA', 'SOTO', NULL, 'SANTIAGO', NULL, NULL, '2016-05-10', '2024-03-01', '1900-01-01'),
(254, 2024, 110, 2, '2° básico', 'B', '25418332', '6', 'M', 'SALVADOR ISAIAS', 'PEÑAILILLO', 'ROZAS', NULL, 'SANTIAGO', NULL, NULL, '2016-06-20', '2024-03-01', '1900-01-01'),
(255, 2024, 110, 2, '2° básico', 'B', '25622909', '9', 'F', 'JOSEFA BELÉN', 'PIZARRO', 'SANTIBÁÑEZ', NULL, 'SANTIAGO', NULL, NULL, '2017-01-09', '2024-03-01', '2024-05-08'),
(256, 2024, 110, 2, '2° básico', 'B', '25453975', '9', 'F', 'FRANCISCA CATALINA', 'RALIL', 'MIRANDA', NULL, 'SANTIAGO', NULL, NULL, '2016-07-26', '2024-03-01', '1900-01-01'),
(257, 2024, 110, 2, '2° básico', 'B', '25590031', '5', 'F', 'MILENA AURORA', 'ROJAS', 'DURÁN', NULL, 'SANTIAGO', NULL, NULL, '2016-11-10', '2024-03-01', '1900-01-01'),
(258, 2024, 110, 2, '2° básico', 'B', '25470404', '0', 'F', 'ÂMARA ANTONIA', 'GODOY', 'RIBEIRO', 'PASAJE VICTOR MALLEA', 'SANTIAGO', NULL, '93136430', '2016-08-04', '2024-03-01', '1900-01-01'),
(259, 2024, 110, 2, '2° básico', 'B', '25355320', '0', 'F', 'DOMINIQUE FERNANDA', 'GONZÁLEZ', 'DONOSO', NULL, 'SANTIAGO', NULL, NULL, '2016-04-09', '2024-03-01', '1900-01-01'),
(260, 2024, 110, 2, '2° básico', 'B', '25458265', '4', 'F', 'MILLA ALEJANDRA', 'GONZÁLEZ', 'QUIROGA', NULL, 'SANTIAGO', NULL, NULL, '2016-07-27', '2024-03-01', '1900-01-01');
INSERT INTO `matricula` (`id`, `ano`, `cod_tipo_ensenanza`, `cod_grado`, `desc_grado`, `letra_curso`, `run`, `digito_ver`, `genero`, `nombres`, `apellido_paterno`, `apellido_materno`, `direccion`, `comuna_residencia`, `email`, `telefono`, `fecha_nacimiento`, `fecha_incorporacion_curso`, `fecha_retiro`) VALUES
(261, 2024, 110, 2, '2° básico', 'B', '25427311', '2', 'M', 'OMAR EDUARDO', 'HUARAMÁN', 'LÓPEZ', NULL, 'SANTIAGO', NULL, NULL, '2016-06-29', '2024-03-01', '1900-01-01'),
(262, 2024, 110, 2, '2° básico', 'B', '25616061', '7', 'F', 'DARIANA KALESSY', 'VASQUEZ', 'CAMPOS', 'TOMA VIOLETA PARRA', 'CERRO NAVIA', 'KARINACAMPOSPEREZ.30@GMAIL.COM', '37863042', '2016-12-30', '2024-03-05', '1900-01-01'),
(263, 2024, 110, 2, '2° básico', 'B', '25537759', '0', 'F', 'AGUSTINA IGNACIA', 'MELÍN', 'CURINAO', 'OIDOR SANCHEZ 8525', 'PUDAHUEL', NULL, '56502365', '2016-10-12', '2024-03-01', '1900-01-01'),
(264, 2024, 110, 2, '2° básico', 'B', '25514046', '9', 'M', 'LUIS CAMILO ALEXANDER', 'ARAYA', 'TORO', NULL, 'MELIPILLA', NULL, NULL, '2016-09-18', '2024-03-01', '1900-01-01'),
(265, 2024, 110, 2, '2° básico', 'B', '25385407', '3', 'M', 'RENATO FRANCISCO', 'INOSTROZA', 'RUIZ', NULL, 'MELIPILLA', NULL, NULL, '2016-05-23', '2024-03-01', '1900-01-01'),
(266, 2024, 110, 2, '2° básico', 'B', '25388918', '7', 'M', 'DIEGO VALENTÍN', 'MOYA', 'MUÑOZ', 'VILLA RENACER ANTONIO LUCKE 2452', 'MELIPILLA', NULL, NULL, '2016-05-27', '2024-03-01', '1900-01-01'),
(267, 2024, 110, 2, '2° básico', 'B', '25522283', 'K', 'F', 'MAITE AGUSTINA', 'MUÑOZ', 'VERA', NULL, 'MELIPILLA', NULL, NULL, '2016-09-25', '2024-03-01', '1900-01-01'),
(268, 2024, 110, 2, '2° básico', 'B', '25701697', '8', 'F', 'CELESTE ELICIA', 'OROZCO', 'MAUREIRA', NULL, 'MELIPILLA', NULL, '31748148', '2017-03-11', '2024-03-01', '1900-01-01'),
(269, 2024, 110, 2, '2° básico', 'B', '25619158', 'K', 'F', 'ANTONELLA MONSERRAT', 'ORTEGA', 'RODRÍGUEZ', NULL, 'MELIPILLA', NULL, NULL, '2017-01-02', '2024-03-01', '1900-01-01'),
(270, 2024, 110, 2, '2° básico', 'B', '25349615', '0', 'M', 'JOSÉ FERNANDO', 'PAILLÁN', 'GUERRERO', NULL, 'MELIPILLA', NULL, NULL, '2016-04-17', '2024-03-01', '1900-01-01'),
(271, 2024, 110, 2, '2° básico', 'B', '26505300', '9', 'F', 'STEPHIE', 'RINCY', NULL, 'MAGNO ESPINOZA 17 AV. 3 PONIENTE', 'MELIPILLA', NULL, '77832456', '2016-06-01', '2024-03-01', '1900-01-01'),
(272, 2024, 110, 2, '2° básico', 'B', '27412373', '7', 'F', 'YELENI ARIADNA', 'RODRIGUEZ', 'SEMPRUN', NULL, 'MELIPILLA', NULL, NULL, '2016-06-21', '2024-03-01', '1900-01-01'),
(273, 2024, 110, 2, '2° básico', 'B', '25519533', '6', 'M', 'TOMÁS ALONSO', 'ESPINOZA', 'PADILLA', NULL, 'MELIPILLA', NULL, NULL, '2016-09-27', '2024-03-01', '1900-01-01'),
(274, 2024, 110, 2, '2° básico', 'B', '25364111', '8', 'F', 'RENATA CAROLINA', 'FRITZ', 'CONTRERAS', NULL, 'MELIPILLA', NULL, NULL, '2016-04-27', '2024-03-01', '1900-01-01'),
(275, 2024, 110, 2, '2° básico', 'B', '25500021', '7', 'F', 'KEYTHEL JOSEFINA IGNACIA', 'GARRIDO', 'URZÚA', NULL, 'MELIPILLA', NULL, NULL, '2016-08-24', '2024-03-01', '1900-01-01'),
(276, 2024, 110, 2, '2° básico', 'B', '25593186', '5', 'F', 'ELIZABETH ANTONELLA', 'GONZÁLEZ', 'PINTO', NULL, 'MELIPILLA', NULL, NULL, '2016-12-09', '2024-03-01', '1900-01-01'),
(277, 2024, 110, 2, '2° básico', 'B', '25561766', '4', 'F', 'NATALIA DEL PILAR', 'HERNÁNDEZ', 'ESPINOZA', 'ESMERALDA, PARCELA 1', 'MELIPILLA', NULL, '84655533', '2016-11-11', '2024-03-01', '1900-01-01'),
(278, 2024, 110, 2, '2° básico', 'B', '25391773', '3', 'M', 'MÁXIMO ANDRÉS', 'HERNÁNDEZ', 'JERIA', 'PASAJE EMILIA SANCHEZ VILLA BENJAMIN ULLOA', 'MELIPILLA', NULL, NULL, '2016-05-31', '2024-03-01', '1900-01-01'),
(279, 2024, 110, 2, '2° básico', 'B', '25449315', '5', 'M', 'CLAUDIO IGNACIO', 'VALDIVIA', 'RAMÍREZ', NULL, 'MELIPILLA', NULL, '89236279', '2016-07-21', '2024-03-01', '1900-01-01'),
(280, 2024, 110, 2, '2° básico', 'B', '25598270', '2', 'M', 'JAIME HERNÁN', 'VARGAS', 'QUINTANILLA', 'CHUNGARA LOS LAGOS', 'MELIPILLA', 'DANIELAQUINTANILLAORELLANA26@GMAIL.COM', '49476960', '2016-12-10', '2024-03-01', '1900-01-01'),
(281, 2024, 110, 2, '2° básico', 'B', '100716391', '2', 'M', 'LUCIANO', 'FERNANDEZ', 'CORSO', NULL, 'MELIPILLA', NULL, NULL, '2017-02-26', '2024-03-01', '1900-01-01'),
(282, 2024, 110, 2, '2° básico', 'B', '100718059', '0', 'M', 'PAUL ESTEBAN', 'CAYUBA', 'ORTIZ', NULL, 'MELIPILLA', NULL, NULL, '2017-03-04', '2024-03-01', '1900-01-01'),
(283, 2024, 110, 2, '2° básico', 'B', '25691604', '5', 'M', 'GASPAR ALEJANDRO', 'BUSTAMANTE', 'GROZNO', NULL, 'MELIPILLA', NULL, NULL, '2017-03-06', '2024-03-01', '1900-01-01'),
(284, 2024, 110, 2, '2° básico', 'B', '24702004', '7', 'M', 'MAXIMILIANO IGNACIO', 'MORALES', 'MORALES', 'CAMINO MACUL PARCELA 3B LOTE7', 'MELIPILLA', NULL, '67124477', '2014-07-26', '2024-03-05', '2024-04-02'),
(285, 2024, 110, 3, '3° básico', 'A', '25012086', '9', 'M', 'ELÍAS VICENTE', 'ULLOA', 'BUGUEÑO', '4 PONIENTE VILLA EXÓTICA', 'CALAMA', 'KARENBUGUENO9@GMAIL.COM', '30130284', '2015-06-07', '2024-03-05', '1900-01-01'),
(286, 2024, 110, 3, '3° básico', 'A', '25169844', '9', 'M', 'AMARO IGNACIO', 'CARVALLO', 'ECHEVERRÍA', NULL, 'SANTIAGO', NULL, NULL, '2015-09-26', '2024-03-01', '1900-01-01'),
(287, 2024, 110, 3, '3° básico', 'A', '25047193', '9', 'F', 'AYELEN NAYARET', 'DÍAZ', 'LIZANA', NULL, 'SANTIAGO', NULL, NULL, '2015-07-21', '2024-03-01', '1900-01-01'),
(288, 2024, 110, 3, '3° básico', 'A', '25032035', '3', 'F', 'ALEXIA FERNANDA', 'DUHAMEL', 'GONZÁLEZ', NULL, 'SANTIAGO', NULL, NULL, '2015-07-02', '2024-03-01', '1900-01-01'),
(289, 2024, 110, 3, '3° básico', 'A', '25173284', '1', 'F', 'SAMANTHA FRANCISCA', 'FARÍAS', 'QUIROZ', NULL, 'SANTIAGO', NULL, NULL, '2015-10-07', '2024-03-01', '1900-01-01'),
(290, 2024, 110, 3, '3° básico', 'A', '25197378', '4', 'M', 'DARYL ALEXANDER', 'GUZMÁN', 'CONTRERAS', NULL, 'SANTIAGO', NULL, NULL, '2015-11-17', '2024-03-01', '2024-04-17'),
(291, 2024, 110, 3, '3° básico', 'A', '25274669', '2', 'F', 'ÁMBAR ALEJANDRA', 'MUÑOZ', 'CORNEJO', 'DINAR ARGELINO 654', 'SANTIAGO', NULL, '46820577', '2016-02-04', '2024-03-01', '1900-01-01'),
(292, 2024, 110, 3, '3° básico', 'A', '25108131', 'K', 'F', 'EMILIA MARISOL', 'PACHECO', 'CALDERÓN', NULL, 'SANTIAGO', NULL, NULL, '2015-08-31', '2024-03-01', '1900-01-01'),
(293, 2024, 110, 3, '3° básico', 'A', '25133220', '7', 'F', 'ANTONELLA EMILIA', 'RIQUELME', 'LOBOS', NULL, 'SANTIAGO', NULL, NULL, '2015-10-13', '2024-03-01', '1900-01-01'),
(294, 2024, 110, 3, '3° básico', 'A', '26752689', '3', 'M', 'PEDRO RAFAEL', 'SEQUERA', 'TEJERA', NULL, 'SANTIAGO', NULL, NULL, '2015-04-17', '2024-03-01', '1900-01-01'),
(295, 2024, 110, 3, '3° básico', 'A', '25029645', '2', 'F', 'ANTONELLA BELÉN', 'PEÑA', 'MUÑOZ', NULL, 'SANTIAGO', NULL, NULL, '2015-07-01', '2024-03-05', '1900-01-01'),
(296, 2024, 110, 3, '3° básico', 'A', '24609455', '1', 'F', 'MARTINA IGNACIA', 'CAMPOS', 'COFRÉ', 'LAS FICEAS 6437 LA HIGUERA', 'LA FLORIDA', NULL, '94096458', '2014-04-27', '2024-03-01', '1900-01-01'),
(297, 2024, 110, 3, '3° básico', 'A', '25276570', '0', 'F', 'ANAÍS ESTEFANÍA', 'ALARCÓN', 'FIGUEROA', NULL, 'MELIPILLA', NULL, NULL, '2016-02-02', '2024-03-01', '1900-01-01'),
(298, 2024, 110, 3, '3° básico', 'A', '100511183', '4', 'M', 'ALESSANDRO ISMAEL', 'CEDEÑO', 'CRESPO', NULL, 'MELIPILLA', NULL, '99360187', '2016-02-25', '2024-03-01', '1900-01-01'),
(299, 2024, 110, 3, '3° básico', 'A', '26395224', '3', 'F', 'CLOE ANNE LAURY', 'DAMAS', NULL, NULL, 'MELIPILLA', NULL, NULL, '2016-01-08', '2024-03-01', '2024-04-16'),
(300, 2024, 110, 3, '3° básico', 'A', '25290844', '7', 'M', 'EMILIANO IGNACIO', 'FARÍAS', 'JIMÉNEZ', NULL, 'MELIPILLA', NULL, NULL, '2016-02-20', '2024-03-01', '1900-01-01'),
(301, 2024, 110, 3, '3° básico', 'A', '25018760', '2', 'M', 'SAMUEL IGNACIO', 'GARRIDO', 'GALLEGUILLOS', 'REGIDOL JOSE MANUEL GONZALEZ VIAL 0469', 'MELIPILLA', NULL, '49395755', '2015-06-21', '2024-03-01', '1900-01-01'),
(302, 2024, 110, 3, '3° básico', 'A', '25329758', '1', 'F', 'BELÉN ANAÍS PAZ', 'GONZÁLEZ', 'JAMET', NULL, 'MELIPILLA', NULL, NULL, '2016-03-18', '2024-03-01', '1900-01-01'),
(303, 2024, 110, 3, '3° básico', 'A', '25324527', '1', 'F', 'ESPERANZA EMILIA', 'JIMÉNEZ', 'RIQUELME', 'AVENIDA TRES PONIENTE PSAJE 16 CASA 374', 'MELIPILLA', NULL, '76871477', '2016-03-16', '2024-03-01', '1900-01-01'),
(304, 2024, 110, 3, '3° básico', 'A', '25174892', '6', 'F', 'LUNA ALIOSKA', 'MARDONES', 'MEZA', NULL, 'MELIPILLA', NULL, NULL, '2015-09-23', '2024-03-01', '1900-01-01'),
(305, 2024, 110, 3, '3° básico', 'A', '24953095', '6', 'F', 'AYELEN YANARA', 'MARIPÍ', 'AGUIRRE', NULL, 'MELIPILLA', NULL, NULL, '2015-04-07', '2024-03-01', '1900-01-01'),
(306, 2024, 110, 3, '3° básico', 'A', '25077577', '6', 'M', 'CHRISTOPHER IGNACIO', 'MATELUNA', 'MESA', 'PASAJE PRAT SECTOR SANTA ELVIRA', 'MELIPILLA', NULL, '96462769', '2015-08-17', '2024-03-01', '1900-01-01'),
(307, 2024, 110, 3, '3° básico', 'A', '25057179', '8', 'F', 'ANTONELLA LETICIA', 'MEDINA', 'FUENTES', NULL, 'MELIPILLA', NULL, NULL, '2015-07-28', '2024-03-01', '1900-01-01'),
(308, 2024, 110, 3, '3° básico', 'A', '25057181', 'K', 'F', 'DOMINIQUE ESTEFANÍA', 'MEDINA', 'FUENTES', NULL, 'MELIPILLA', NULL, NULL, '2015-07-28', '2024-03-01', '1900-01-01'),
(309, 2024, 110, 3, '3° básico', 'A', '100480796', '7', 'F', 'ABRIL ANTONELLA', 'NORIEGA', 'DAVOIN', NULL, 'MELIPILLA', NULL, '58841139', '2015-07-21', '2024-03-01', '1900-01-01'),
(310, 2024, 110, 3, '3° básico', 'A', '25002448', '7', 'M', 'MAURICIO ERNESTO', 'PAVEZ', 'MENDOZA', NULL, 'MELIPILLA', NULL, NULL, '2015-06-03', '2024-03-01', '2024-04-01'),
(311, 2024, 110, 3, '3° básico', 'A', '26378741', '2', 'F', 'MARIA JOSE', 'PINEDA', 'ROSALES', NULL, 'MELIPILLA', NULL, NULL, '2015-12-09', '2024-03-01', '1900-01-01'),
(312, 2024, 110, 3, '3° básico', 'A', '25211302', '9', 'M', 'ISAAC NICOLÁS', 'QUINTEROS', 'TORRES', 'PASAJE ARTURO PACHECO', 'MELIPILLA', NULL, '81537911', '2015-11-25', '2024-03-01', '1900-01-01'),
(313, 2024, 110, 3, '3° básico', 'A', '24951932', '4', 'F', 'ANTONIA ISIDORA MONSERRAT', 'RAMÍREZ', 'ROCO', 'LUIS BARRERMAN ACUÑA', 'MELIPILLA', NULL, '30616452', '2015-04-08', '2024-03-01', '1900-01-01'),
(314, 2024, 110, 3, '3° básico', 'A', '25012909', '2', 'F', 'BRENDA DANIELA', 'SAINT-LOUIS', 'SAINT LOUIS PIERRE', 'MEHUIN', 'MELIPILLA', NULL, NULL, '2015-06-11', '2024-03-01', '1900-01-01'),
(315, 2024, 110, 3, '3° básico', 'A', '25254292', '2', 'M', 'TOMÁS ALEJANDRO', 'SANDOVAL', 'LOYOLA', NULL, 'MELIPILLA', NULL, NULL, '2016-01-14', '2024-03-01', '1900-01-01'),
(316, 2024, 110, 3, '3° básico', 'A', '25002763', 'K', 'F', 'JULIETA', 'SANTIBÁÑEZ', 'HERRERA', NULL, 'MELIPILLA', NULL, NULL, '2015-06-04', '2024-03-01', '1900-01-01'),
(317, 2024, 110, 3, '3° básico', 'A', '25201189', '7', 'M', 'DIEGO ALONSO', 'VERA', 'CASTRO', 'HURTADO 1373', 'MELIPILLA', NULL, '77242076', '2015-11-20', '2024-03-01', '1900-01-01'),
(318, 2024, 110, 3, '3° básico', 'A', '25232631', '6', 'M', 'ETHAN DAVID', 'VIDAL', 'RIQUELME', NULL, 'MELIPILLA', NULL, NULL, '2015-12-22', '2024-03-01', '1900-01-01'),
(319, 2024, 110, 3, '3° básico', 'A', '25006244', '3', 'M', 'RICHARD ANDRÉS', 'VILLEGAS', 'LOBOS', 'MERCED', 'MELIPILLA', NULL, '49987341', '2015-06-02', '2024-03-01', '1900-01-01'),
(320, 2024, 110, 3, '3° básico', 'A', '25276516', '6', 'F', 'ALLISON MONSERRAT', 'ALARCÓN', 'FIGUEROA', NULL, 'MELIPILLA', NULL, NULL, '2016-02-02', '2024-03-01', '1900-01-01'),
(321, 2024, 110, 3, '3° básico', 'A', '24891650', '8', 'M', 'SAMIR ALEXANDER', 'MARTÍNEZ', 'CORREA', 'TEODORA ALLENDES POBL. PABLO LIZAMA', 'MELIPILLA', NULL, '48861588', '2015-02-05', '2024-03-01', '1900-01-01'),
(322, 2024, 110, 3, '3° básico', 'A', '24998952', '5', 'F', 'AMANDA EMILIA', 'ESPINOZA', 'MARAMBIO', NULL, 'MELIPILLA', NULL, NULL, '2015-05-27', '2024-03-01', '1900-01-01'),
(323, 2024, 110, 3, '3° básico', 'A', '25121253', '8', 'F', 'MIA ANDREA ESPERANZA', 'BARRAZA', 'TAPIA', 'EMILIO SANCHEZ SANDOVAL 97 BENJAMIN V. MACKENA', 'MELIPILLA', NULL, NULL, '2015-09-12', '2024-03-01', '1900-01-01'),
(324, 2024, 110, 3, '3° básico', 'A', '100557744', '2', 'M', 'STHANLEY LIONEL', 'BRICEÑO', 'ALVIAREZ', NULL, 'MELIPILLA', NULL, NULL, '2015-05-26', '2024-04-01', '1900-01-01'),
(325, 2024, 110, 3, '3° básico', 'A', '25068658', '7', 'F', 'ISIDORA ANTONIA', 'RIVAS', 'ARANEDA', 'ANDRES BELLO 530 VILLA SANTA REGINA', 'PADRE HURTADO', 'STAR.KENNY.DJM1@GMAIL.COM', '72765044', '2015-08-09', '2024-03-01', '1900-01-01'),
(326, 2024, 110, 3, '3° básico', 'B', '24968470', '8', 'M', 'EDUARDO ALONSO', 'BARRAZA', 'CERDA', NULL, 'SANTIAGO', NULL, NULL, '2015-04-26', '2024-03-01', '1900-01-01'),
(327, 2024, 110, 3, '3° básico', 'B', '25051021', '7', 'M', 'LUCAS MATEO', 'BRAVO', 'FLORES', NULL, 'SANTIAGO', NULL, NULL, '2015-06-28', '2024-03-01', '1900-01-01'),
(328, 2024, 110, 3, '3° básico', 'B', '25337559', '0', 'F', 'RENATA IGNACIA', 'CABELLO', 'MELLA', NULL, 'SANTIAGO', NULL, NULL, '2016-04-01', '2024-03-01', '1900-01-01'),
(329, 2024, 110, 3, '3° básico', 'B', '25197411', 'K', 'F', 'LÍA IGNACIA', 'CASTRO', 'HERRERA', NULL, 'SANTIAGO', NULL, NULL, '2015-11-17', '2024-03-01', '1900-01-01'),
(330, 2024, 110, 3, '3° básico', 'B', '24967189', '4', 'M', 'AGUSTÍN IGNACIO', 'PAREDES', 'IBARRA', NULL, 'SANTIAGO', NULL, NULL, '2015-04-22', '2024-03-01', '1900-01-01'),
(331, 2024, 110, 3, '3° básico', 'B', '24982100', '4', 'M', 'MATEO ALONSO', 'PINTO', 'GUERRA', NULL, 'SANTIAGO', NULL, NULL, '2015-05-08', '2024-03-01', '1900-01-01'),
(332, 2024, 110, 3, '3° básico', 'B', '25258520', '6', 'F', 'RENATA ISIDORA', 'RAMÍREZ', 'SALFATE', NULL, 'SANTIAGO', NULL, NULL, '2016-01-16', '2024-03-01', '1900-01-01'),
(333, 2024, 110, 3, '3° básico', 'B', '25314627', '3', 'F', 'VALENTINA IGNACIA', 'SANHUEZA', 'PARRA', NULL, 'SANTIAGO', NULL, NULL, '2016-03-11', '2024-03-01', '1900-01-01'),
(334, 2024, 110, 3, '3° básico', 'B', '24965893', '6', 'F', 'MARTINA IGNACIA', 'TAPIA', 'ARANDA', NULL, 'SANTIAGO', NULL, NULL, '2015-04-22', '2024-03-01', '1900-01-01'),
(335, 2024, 110, 3, '3° básico', 'B', '25274581', '5', 'F', 'ANABELLA ISIDORA', 'GONZÁLEZ', 'BAEZ', NULL, 'SANTIAGO', NULL, NULL, '2016-02-01', '2024-03-01', '1900-01-01'),
(336, 2024, 110, 3, '3° básico', 'B', '25047695', '7', 'M', 'JOAQUÍN MAXIMILIANO', 'LILLO', 'SANHUEZA', NULL, 'SANTIAGO', NULL, NULL, '2015-07-18', '2024-03-01', '1900-01-01'),
(337, 2024, 110, 3, '3° básico', 'B', '25244531', '5', 'F', 'VAHITIARE ALEXANDRA', 'PALACIOS', 'VERGARA', NULL, 'SANTIAGO', NULL, NULL, '2016-01-03', '2024-03-01', '1900-01-01'),
(338, 2024, 110, 3, '3° básico', 'B', '25754608', 'K', 'F', 'CAMILA VALENTINA', 'SALAZAR', 'RENGIFO', NULL, 'SANTIAGO', NULL, NULL, '2015-04-23', '2024-03-01', '1900-01-01'),
(339, 2024, 110, 3, '3° básico', 'B', '25239547', '4', 'F', 'PASCALLE ANAIS', 'CARVALLO', 'VERGARA', 'REINALDO PRADO N°5384', 'HUECHURABA', NULL, '65283506', '2015-12-28', '2024-03-01', '1900-01-01'),
(340, 2024, 110, 3, '3° básico', 'B', '26431030', 'K', 'F', 'ANN-EMILY ESTHER', 'MILORD', NULL, 'PASAJE 12', 'LA GRANJA', 'ANNELAUREMILORD@GMAIL.COM', '48982414', '2016-02-02', '2024-03-05', '1900-01-01'),
(341, 2024, 110, 3, '3° básico', 'B', '24953434', 'K', 'F', 'EMILIA MILLARAY', 'ABARCA', 'TONACCA', 'PJE POLO SUR 2152', 'MAIPÚ', 'MACARENA.TONACCA@GMAIL.COM', '97370854', '2015-04-08', '2024-03-01', '1900-01-01'),
(342, 2024, 110, 3, '3° básico', 'B', '25180147', '9', 'F', 'MIA ISIDORA', 'ARAYA', 'MORA', NULL, 'MELIPILLA', NULL, NULL, '2015-10-05', '2024-03-01', '1900-01-01'),
(343, 2024, 110, 3, '3° básico', 'B', '25207530', '5', 'M', 'ALBERT ALEXANDER', 'ALCAMÁN', 'HUENTEO', NULL, 'MELIPILLA', NULL, NULL, '2015-11-28', '2024-03-01', '1900-01-01'),
(344, 2024, 110, 3, '3° básico', 'B', '25256087', '4', 'F', 'AMY ANTONELLA', 'BARRERA', 'ROZAS', NULL, 'MELIPILLA', NULL, NULL, '2016-01-12', '2024-03-01', '1900-01-01'),
(345, 2024, 110, 3, '3° básico', 'B', '25327638', 'K', 'F', 'AYLEN ESTEPHANIE', 'CABRERA', 'LÓPEZ', NULL, 'MELIPILLA', NULL, NULL, '2016-03-20', '2024-03-01', '1900-01-01'),
(346, 2024, 110, 3, '3° básico', 'B', '25290547', '2', 'F', 'LIZBETH SARAY', 'CÁCERES', 'LEÓN', NULL, 'MELIPILLA', NULL, NULL, '2016-02-19', '2024-03-01', '1900-01-01'),
(347, 2024, 110, 3, '3° básico', 'B', '25010925', '3', 'F', 'ISSIDORA ALEJANDRA', 'CARREÑO', 'MARTÍNEZ', 'AVENIDA CARLOS AVILES 371', 'MELIPILLA', NULL, '40246011', '2015-06-09', '2024-03-01', '1900-01-01'),
(348, 2024, 110, 3, '3° básico', 'B', '25252731', '1', 'F', 'DOMINIQUE IGNACIA', 'REYES', 'PAREDES', NULL, 'MELIPILLA', NULL, NULL, '2016-01-05', '2024-03-01', '1900-01-01'),
(349, 2024, 110, 3, '3° básico', 'B', '25051257', '0', 'F', 'REBECA LISSETTE', 'SALINAS', 'ESCALANTE', 'ANITA FRESNO 351 POB. BERNARDO LEYTON', 'MELIPILLA', 'SUSSY.LISSETTE29@GMAIL.COM', '61103122', '2015-07-25', '2024-03-01', '1900-01-01'),
(350, 2024, 110, 3, '3° básico', 'B', '24979281', '0', 'M', 'DANTE JOAQUÍN', 'SANTIBÁÑEZ', 'GALLARDO', 'PARCELA 29 LOTE 3 CHOLQUI', 'MELIPILLA', NULL, '76077716', '2015-05-04', '2024-03-01', '1900-01-01'),
(351, 2024, 110, 3, '3° básico', 'B', '25311865', '2', 'F', 'SOFÍA DE LAS MERCEDES', 'SARMIENTO', 'ARISMENDI', 'CULIPRAN,LOS MOLLES ,PARCELA N 140', 'MELIPILLA', NULL, '75516069', '2016-03-09', '2024-03-01', '1900-01-01'),
(352, 2024, 110, 3, '3° básico', 'B', '25323732', '5', 'F', 'EIMMY LIZBETH', 'VIDAL', 'TORRES', NULL, 'MELIPILLA', NULL, NULL, '2016-03-18', '2024-03-01', '1900-01-01'),
(353, 2024, 110, 3, '3° básico', 'B', '24983600', '1', 'M', 'ROBERTO CARLOS', 'CÓRDOVA', 'LOBOS', 'POBLACION CLOTARIO BLEST PAJE. SANTIAGO QUEBRADINO', 'MELIPILLA', NULL, '33947494', '2015-05-12', '2024-03-01', '1900-01-01'),
(354, 2024, 110, 3, '3° básico', 'B', '26754120', '5', 'F', 'REBECCA', 'DESHOMMES', NULL, NULL, 'MELIPILLA', NULL, NULL, '2016-02-27', '2024-03-01', '1900-01-01'),
(355, 2024, 110, 3, '3° básico', 'B', '25286272', '2', 'M', 'JUSTIN IGNACIO', 'FARIAS', 'MUÑOZ', 'PASAJE LAGO RANCO 265 LOS LAGOS 1', 'MELIPILLA', NULL, '3721248', '2016-02-12', '2024-03-01', '1900-01-01'),
(356, 2024, 110, 3, '3° básico', 'B', '25327545', '6', 'F', 'RENATA PAZ', 'GONZÁLEZ', 'ALLENDES', NULL, 'MELIPILLA', NULL, NULL, '2016-03-22', '2024-03-01', '1900-01-01'),
(357, 2024, 110, 3, '3° básico', 'B', '25071757', '1', 'F', 'JESSIE AINHOA', 'GONZALEZ', 'ROJAS', NULL, 'MELIPILLA', NULL, NULL, '2015-08-11', '2024-03-01', '1900-01-01'),
(358, 2024, 110, 3, '3° básico', 'B', '25241615', '3', 'M', 'MARTÍN IGNACIO', 'GUERRA', 'CABELLO', NULL, 'MELIPILLA', NULL, NULL, '2016-01-01', '2024-03-01', '1900-01-01'),
(359, 2024, 110, 3, '3° básico', 'B', '25214374', '2', 'M', 'VICENTE ALEJANDRO', 'HERNÁNDEZ', 'LOBOS', NULL, 'MELIPILLA', NULL, NULL, '2015-12-04', '2024-03-01', '1900-01-01'),
(360, 2024, 110, 3, '3° básico', 'B', '25215664', 'K', 'F', 'SOFÍA MONSERRAT', 'LOYOLA', 'CANALES', NULL, 'MELIPILLA', NULL, NULL, '2015-12-04', '2024-03-01', '1900-01-01'),
(361, 2024, 110, 3, '3° básico', 'B', '28056492', '3', 'M', 'JESUS FERNANDO', 'ZAPATA', 'SEQUERA', 'Gabriela Mistral', 'MELIPILLA', NULL, '35703742', '2015-09-08', '2024-03-01', '1900-01-01'),
(362, 2024, 110, 3, '3° básico', 'B', '25283792', '2', 'F', 'ROSE LISSETTE', 'VARGAS', 'VIDAL', NULL, 'MELIPILLA', NULL, NULL, '2016-02-09', '2024-03-01', '1900-01-01'),
(363, 2024, 110, 3, '3° básico', 'B', '25022629', '2', 'M', 'PAOLO MARCELO', 'SILVA', 'RIQUELME', 'HACIENDA SAN MARTIN PJE. PROF. PATRICIO AGUIRRE 970', 'MELIPILLA', NULL, '77031832', '2015-06-23', '2024-03-05', '1900-01-01'),
(364, 2024, 110, 3, '3° básico', 'B', '100551532', '3', 'M', 'JEREMIAS ADRIAN', 'CELIS', 'TORO', NULL, 'MELIPILLA', NULL, '35571541', '2015-11-27', '2024-03-01', '1900-01-01'),
(365, 2024, 110, 4, '4° básico', 'A', '24738496', '0', 'F', 'MARTINA VERÓNICA', 'AGUILAR', 'UGARTE', 'PINTOR JUAN FCO. GONZALEZ 219 VILLA ILUSIONES COMPARTIDAS', 'ÑUÑOA', NULL, '74436654', '2014-09-10', '2024-03-01', '1900-01-01'),
(366, 2024, 110, 4, '4° básico', 'A', '24644928', '7', 'M', 'LEÓN GASPAR', 'RODRÍGUEZ', 'ELGUETA', 'PILLAN 1158', 'PUDAHUEL', NULL, NULL, '2014-06-06', '2024-03-01', '1900-01-01'),
(367, 2024, 110, 4, '4° básico', 'A', '24806497', '8', 'F', 'DAFNE CATALINA', 'TOLOSA', 'TRONCOSO', 'YUNGAY', 'PUDAHUEL', NULL, NULL, '2014-11-19', '2024-03-01', '1900-01-01'),
(368, 2024, 110, 4, '4° básico', 'A', '24630718', '0', 'M', 'LENIN JESÚS', 'HORMAZÁBAL', 'MUÑOZ', NULL, 'PUDAHUEL', NULL, NULL, '2014-05-25', '2024-03-01', '1900-01-01'),
(369, 2024, 110, 4, '4° básico', 'A', '24759029', '3', 'M', 'JOAQUÍN ANTONIO', 'VÁSQUEZ', 'PÉREZ', NULL, 'QUINTA NORMAL', NULL, NULL, '2014-10-04', '2024-03-01', '1900-01-01'),
(370, 2024, 110, 4, '4° básico', 'A', '24824028', '8', 'M', 'MARTÍN ESTEBAN', 'BADILLA', 'CAMPORA', NULL, 'RECOLETA', NULL, NULL, '2014-12-09', '2024-03-01', '1900-01-01'),
(371, 2024, 110, 4, '4° básico', 'A', '24940658', '9', 'F', 'AIMI AINARA', 'ABARCA', 'RAMÍREZ', 'PASAJE ALEJANDRO GONZALEZ VIAL FLORENCIA I', 'MELIPILLA', NULL, '44607645', '2015-03-25', '2024-03-01', '1900-01-01'),
(372, 2024, 110, 4, '4° básico', 'A', '24789097', '1', 'F', 'LÍA PASKALE', 'AGUILERA', 'QUINTANILLA', 'AV. CIRCUNVALACION ALTOS CANTILLANA', 'MELIPILLA', NULL, '62309058', '2014-10-31', '2024-03-01', '1900-01-01'),
(373, 2024, 110, 4, '4° básico', 'A', '24799874', '8', 'F', 'ZAYRA ANTONELLA', 'ALFARO', 'AGUILAR', NULL, 'MELIPILLA', NULL, NULL, '2014-11-13', '2024-03-01', '1900-01-01'),
(374, 2024, 110, 4, '4° básico', 'A', '24868826', '2', 'M', 'EMILIO ESTEBAN', 'ALLENDE', 'GUTIÉRREZ', 'JUAN FRANCISCO GONZALEZ 38', 'MELIPILLA', NULL, NULL, '2015-01-18', '2024-03-01', '1900-01-01'),
(375, 2024, 110, 4, '4° básico', 'A', '24616141', '0', 'F', 'ANTONIA DANAE', 'ARROYO', 'DURAN', 'MONSEÑOR RENÉ VIO VALDIVIESO POBL. PABLO LIZAMA', 'MELIPILLA', NULL, '58722120', '2014-05-04', '2024-03-01', '1900-01-01'),
(376, 2024, 110, 4, '4° básico', 'A', '24851797', '2', 'M', 'ALLEN IGNACIO', 'BAHAMONDES', 'SÁNCHEZ', 'PASAJE PINTOR JOSE PEROTTI 363 ILUSIONES COMPARTIDAS', 'MELIPILLA', NULL, '98329116', '2015-01-07', '2024-03-01', '1900-01-01'),
(377, 2024, 110, 4, '4° básico', 'A', '24866949', '7', 'M', 'JEAN PIERRE ALEXANDER', 'CARREÑO', 'FREDES', 'FUNDO SANTA LUISA EL MARCO POMAIRE', 'MELIPILLA', NULL, '98933492', '2015-01-16', '2024-03-01', '1900-01-01'),
(378, 2024, 110, 4, '4° básico', 'A', '24812001', '0', 'M', 'DANILO ESTEBAN', 'CID', 'URZÚA', 'OBISPO GUILLERMO VERA POBL. O. LIZAMA', 'MELIPILLA', NULL, '97733222', '2014-11-28', '2024-03-01', '1900-01-01'),
(379, 2024, 110, 4, '4° básico', 'A', '26293481', '0', 'F', 'RAISSA MAIRENA', 'CORONADO', 'QUISPE', 'MAITENES 150 LA FORESTA', 'MELIPILLA', NULL, '45686968', '2015-01-09', '2024-03-01', '1900-01-01'),
(380, 2024, 110, 4, '4° básico', 'A', '26754096', '9', 'F', 'ESTHER', 'DESHOMMES', '.', 'PJE. JUAN FCO. GONZALEZ POBL. SOLIDARIDAD Y ESFUERZO', 'MELIPILLA', NULL, '20379490', '2014-05-30', '2024-03-01', '1900-01-01'),
(381, 2024, 110, 4, '4° básico', 'A', '24820012', 'K', 'M', 'BRUNO ALONSO', 'DÍAZ', 'VELARDE', 'CARLOS AVILES POBL. BENJAMIN ULLOA', 'MELIPILLA', NULL, '98458799', '2014-12-05', '2024-03-01', '1900-01-01'),
(382, 2024, 110, 4, '4° básico', 'A', '24751809', '6', 'M', 'TOMÁS AARON', 'GATICA', 'CALFULAF', 'CALLE BICENTENARIO PSJE 15 CASA 394', 'MELIPILLA', NULL, NULL, '2014-09-24', '2024-03-01', '1900-01-01'),
(383, 2024, 110, 4, '4° básico', 'A', '24631882', '4', 'F', 'ESTEFANÍA CONSTANZA', 'MIRANDA', 'GONZÁLEZ', 'PASAJE ALFREDO MARÍN 2259', 'MELIPILLA', NULL, '85121095', '2014-05-23', '2024-03-01', '1900-01-01'),
(384, 2024, 110, 4, '4° básico', 'A', '24595499', '9', 'F', 'AINHOA VALENTINA', 'OLGUÍN', 'PÉREZ', 'POBL. BENJAMIN ULLOA EDUARDO CASTAÑEDA', 'MELIPILLA', NULL, '56116645', '2014-04-09', '2024-03-01', '1900-01-01'),
(385, 2024, 110, 4, '4° básico', 'A', '24750897', 'K', 'M', 'GASPAR ANDRÉS', 'OPAZO', 'VIDAL', 'VICENTE HUIDOBRO 413 LOS POETAS', 'MELIPILLA', NULL, '75419722', '2014-09-10', '2024-03-01', '1900-01-01'),
(386, 2024, 110, 4, '4° básico', 'A', '24680521', '0', 'M', 'EDISSON IGNACIO', 'PAVEZ', 'MUÑOZ', 'PASAJE MARIA SOLEDAD CAMPOS 107 VILLA EL PORTAL', 'MELIPILLA', NULL, '97928191', '2014-07-10', '2024-03-01', '1900-01-01'),
(387, 2024, 110, 4, '4° básico', 'A', '24905842', '4', 'M', 'CRISTIAN ALEJANDRO ANDRÉS', 'SERRANO', 'FUENZALIDA', 'POBL. CLOTARIO BLEST 2477 SANTIAGO QUEBRADINO RAMOS', 'MELIPILLA', NULL, '98629785', '2015-02-20', '2024-03-01', '1900-01-01'),
(388, 2024, 110, 4, '4° básico', 'A', '24616922', '5', 'M', 'OSCAR JOSÉ', 'SOTO', 'CABRERA', 'PASAJE LAS CAMELIAS 66 POBL. LA FORESTA', 'MELIPILLA', NULL, '95842281', '2014-05-11', '2024-03-01', '1900-01-01'),
(389, 2024, 110, 4, '4° básico', 'A', '24769196', '0', 'F', 'ALINA MILAGROS', 'VÁSQUEZ', 'SANHUEZA', 'CLOTARIO BLEST 224', 'MELIPILLA', NULL, NULL, '2014-10-13', '2024-03-01', '1900-01-01'),
(390, 2024, 110, 4, '4° básico', 'A', '24745356', '3', 'M', 'BASTIÁN EDUARDO', 'GONZÁLEZ', 'ABARCA', 'JUAN BAUTISTA SALINAS 551 POMAIRE', 'MELIPILLA', NULL, '93360226', '2014-09-17', '2024-03-01', '1900-01-01'),
(391, 2024, 110, 4, '4° básico', 'A', '24752770', '2', 'F', 'TATIANA PASCALE', 'GONZÁLEZ', 'GONZÁLEZ', NULL, 'MELIPILLA', NULL, NULL, '2014-09-26', '2024-03-01', '1900-01-01'),
(392, 2024, 110, 4, '4° básico', 'A', '24708942', 'K', 'M', 'VICENTE AGUSTÍN', 'HERRERA', 'VERDEJO', 'PAJE. JUANA BRUNA CLOTARIO BLEST', 'MELIPILLA', NULL, '99882353', '2014-08-10', '2024-03-01', '1900-01-01'),
(393, 2024, 110, 4, '4° básico', 'A', '24687888', '9', 'M', 'FABIÁN ANDRÉS', 'HUENULAO', 'NAVARRO', 'PASAJE EL MAÑIO 2133 ALTOS CANTILLANA', 'MELIPILLA', NULL, '95088300', '2014-07-19', '2024-03-01', '1900-01-01'),
(394, 2024, 110, 4, '4° básico', 'A', '24677442', '0', 'F', 'MARTINA MAILEN', 'JORQUERA', 'ZÚÑIGA', 'VILLA LOS CANELOS 119 EL ROBLE', 'MELIPILLA', NULL, '64370171', '2014-07-08', '2024-03-01', '1900-01-01'),
(395, 2024, 110, 4, '4° básico', 'A', '24677459', '5', 'F', 'VALENTINA MAILEN', 'JORQUERA', 'ZÚÑIGA', 'VILLA LOS CANELOS EL ROBLE 119', 'MELIPILLA', NULL, '95013037', '2014-07-08', '2024-03-01', '1900-01-01'),
(396, 2024, 110, 4, '4° básico', 'A', '24938146', '2', 'M', 'MATÍAS JESÚS', 'LEVIO', 'AGUILERA', NULL, 'MELIPILLA', NULL, NULL, '2015-03-22', '2024-03-01', '1900-01-01'),
(397, 2024, 110, 4, '4° básico', 'A', '24898317', '5', 'M', 'MIGUEL ANGEL ALONSO', 'LIZANA', 'GONZÁLEZ', NULL, 'MELIPILLA', NULL, NULL, '2015-02-14', '2024-03-01', '1900-01-01'),
(398, 2024, 110, 4, '4° básico', 'A', '24689151', '6', 'F', 'MARTINA ANAÍS', 'LOYOLA', 'RIVEROS', NULL, 'MELIPILLA', NULL, NULL, '2014-07-19', '2024-03-01', '1900-01-01'),
(399, 2024, 110, 4, '4° básico', 'A', '100455675', '1', 'M', 'WILSON LEONEL', 'JALDIN', 'VELA', NULL, 'MELIPILLA', 'TANIAVELAALVAREZ.1985@GMAIL.COM', NULL, '2015-02-17', '2024-03-01', '1900-01-01'),
(400, 2024, 110, 4, '4° básico', 'A', '24805932', 'K', 'M', 'FREDY MARTÍN', 'VEGA', 'VEGA', 'PAPA PIO 11', 'MELIPILLA', NULL, '59145320', '2014-11-17', '2024-03-01', '1900-01-01'),
(401, 2024, 110, 4, '4° básico', 'A', '24717537', '7', 'M', 'EMILIO ANTONIO', 'CERÓN', 'GONZÁLEZ', 'PSJ.PINTOR JOSE PEROL Nª11 LOS JAZMINES', 'MELIPILLA', NULL, NULL, '2014-08-20', '2024-03-01', '1900-01-01'),
(402, 2024, 110, 4, '4° básico', 'A', '100560500', '4', 'F', 'MARLEY', 'BECERRA', 'ABREGO', NULL, 'MELIPILLA', NULL, '41866447', '2014-04-19', '2024-03-01', '1900-01-01'),
(403, 2024, 110, 4, '4° básico', 'A', '24576682', '3', 'M', 'IGNACIO FRANCISCO', 'QUIROZ', 'VERA', 'PSJE PÍO X 2063 PABLO LIZAMA', 'MELIPILLA', NULL, '50554173', '2014-03-22', '2024-03-05', '1900-01-01'),
(404, 2024, 110, 4, '4° básico', 'A', '28385820', '0', 'F', 'LISMAR CAMILA', 'COLMENARES', 'COLMENARES', NULL, 'MELIPILLA', NULL, NULL, '2014-10-06', '2024-03-01', '1900-01-01'),
(405, 2024, 110, 4, '4° básico', 'B', '24825219', '7', 'M', 'LEÓN ANDRE', 'TUDESCA', 'BENÍTEZ', 'CARACOLES', 'ANTOFAGASTA', 'CLAUDIABENITES1306@GAMAIL.COM', '72894878', '2014-12-09', '2024-03-01', '1900-01-01'),
(406, 2024, 110, 4, '4° básico', 'B', '24635753', '6', 'F', 'ALEXANDRA ELENA ANAYS', 'ENCALADA', 'ROMERO', NULL, 'ILLAPEL', NULL, NULL, '2014-05-26', '2024-03-01', '1900-01-01'),
(407, 2024, 110, 4, '4° básico', 'B', '24933354', '9', 'F', 'ANAYS MARIOLI', 'CARBALLO', 'PADILLA', NULL, 'SANTIAGO', NULL, NULL, '2015-03-22', '2024-03-01', '1900-01-01'),
(408, 2024, 110, 4, '4° básico', 'B', '24891051', '8', 'F', 'AGUSTINA ANTONIA', 'URRA', 'ACUÑA', NULL, 'SANTIAGO', NULL, NULL, '2015-01-21', '2024-03-01', '1900-01-01'),
(409, 2024, 110, 4, '4° básico', 'B', '24348821', '4', 'M', 'ALEXANDER WLADIMIR EXEQUIEL', 'VEJAR', 'PÉREZ', 'LOS PAPAYOS', 'HUECHURABA', 'ALEXANDER.VEJAR.P@EDUHUECHURABA.CL', '37780935', '2013-08-01', '2024-03-01', '1900-01-01'),
(410, 2024, 110, 4, '4° básico', 'B', '24907444', '6', 'F', 'MAN JUN', 'YANG', 'FAN', 'AVENIDA CENTRAL', 'LO ESPEJO', NULL, '95017291', '2015-02-12', '2024-03-01', '1900-01-01'),
(411, 2024, 110, 4, '4° básico', 'B', '24611409', '9', 'M', 'ALONSO JESÚS', 'ROZAS', 'CASTRO', 'PANGAL', 'MAIPÚ', 'ALONSO.ROZAS@PMEKIS.CL', '82505326', '2014-05-03', '2024-03-01', '1900-01-01'),
(412, 2024, 110, 4, '4° básico', 'B', '24674367', '3', 'M', 'FELIPE IGNACIO', 'ADRIAZOLA', 'VILLAVICENCIO', 'VILLA EL ALAMO 1809 PASAJE LOS SAUCES', 'MELIPILLA', NULL, '91806561', '2014-07-04', '2024-03-01', '1900-01-01'),
(413, 2024, 110, 4, '4° básico', 'B', '24747567', '2', 'F', 'FLORENCIA YIRETH', 'ACEVEDO', 'SAGREDO', NULL, 'MELIPILLA', NULL, NULL, '2014-09-16', '2024-03-01', '1900-01-01'),
(414, 2024, 110, 4, '4° básico', 'B', '100482371', '7', 'M', 'MATIAS MISAEL', 'BLANCO', 'MOYA', NULL, 'MELIPILLA', NULL, '46570970', '2015-03-27', '2024-03-01', '1900-01-01'),
(415, 2024, 110, 4, '4° básico', 'B', '24805953', '2', 'F', 'MONSERRAT ANTONELLA', 'CASTRO', 'CABELLO', 'PASAJE EMILIO SANCHEZ SANDOVAL 9 POBL. B. ULLOA', 'MELIPILLA', NULL, '91660969', '2014-11-17', '2024-03-01', '1900-01-01'),
(416, 2024, 110, 4, '4° básico', 'B', '24674223', '5', 'F', 'AMANDA FRANCISCA', 'CONTRERAS', 'HERRERA', 'PASAJE ARTURO PACHECO 30 POBL. SOLIDARIDAD Y ESFUERZO', 'MELIPILLA', NULL, '68217842', '2014-07-02', '2024-03-01', '1900-01-01'),
(417, 2024, 110, 4, '4° básico', 'B', '24779219', '8', 'F', 'RAFAELLA ELENA', 'CURÍN', 'ZÚÑIGA', 'JOSÉ SANTOS ROJAS 448 VILLA RENACER', 'MELIPILLA', NULL, '94172758', '2014-10-19', '2024-03-01', '1900-01-01'),
(418, 2024, 110, 4, '4° básico', 'B', '24726305', '5', 'M', 'ALONSO ANTONIO', 'DÍAZ', 'CONTRERAS', 'LOMAS DE SAN NATONIO S/N CODIGUA', 'MELIPILLA', NULL, NULL, '2014-08-28', '2024-03-01', '1900-01-01'),
(419, 2024, 110, 4, '4° básico', 'B', '24762209', '8', 'M', 'EDGARD ANDRÉS', 'GUZMÁN', 'CARRASCO', NULL, 'MELIPILLA', NULL, NULL, '2014-10-05', '2024-03-01', '1900-01-01'),
(420, 2024, 110, 4, '4° básico', 'B', '24752793', '1', 'F', 'SOFIA YASMIN ISIDORA', 'HUERTA', 'JORQUERA', 'VOLCÁN CALBUCO H 904 DEPTO 102 VALLES DE MELIPILLA', 'MELIPILLA', NULL, '8890232', '2014-09-25', '2024-03-01', '1900-01-01'),
(421, 2024, 110, 4, '4° básico', 'B', '24672424', '5', 'F', 'AMARAL ISIDORA', 'IBAR', 'FLORES', NULL, 'MELIPILLA', NULL, NULL, '2014-07-01', '2024-03-01', '1900-01-01'),
(422, 2024, 110, 4, '4° básico', 'B', '24794122', '3', 'F', 'AMANDA AGUSTINA', 'JIMÉNEZ', 'RIQUELME', 'AV. 3 PONIENTE PASAJE 16 CASA 374 VILLA LOS JAZMINES', 'MELIPILLA', NULL, '93477074', '2014-11-06', '2024-03-01', '1900-01-01'),
(423, 2024, 110, 4, '4° básico', 'B', '24752342', '1', 'F', 'EMILIA ANTONELLA', 'LOYOLA', 'CANALES', NULL, 'MELIPILLA', NULL, NULL, '2014-09-27', '2024-03-01', '1900-01-01'),
(424, 2024, 110, 4, '4° básico', 'B', '24747702', '0', 'M', 'BRUNO ERNESTO', 'MARTÍNEZ', 'SILVA', 'RAUL SILVA HENRIQUEZ 3014', 'MELIPILLA', NULL, '81840522', '2014-09-23', '2024-03-01', '1900-01-01'),
(425, 2024, 110, 4, '4° básico', 'B', '24846756', '8', 'F', 'IGNACIA SOFÍA', 'MONTENEGRO', 'RAMÍREZ', 'TODOS LOS SANTOS 1657 LOS LAGOS I', 'MELIPILLA', NULL, '83030932', '2015-01-06', '2024-03-01', '1900-01-01'),
(426, 2024, 110, 4, '4° básico', 'B', '24759349', '7', 'M', 'IAN ALEJANDRO', 'RIVERO', 'LOBOS', 'PAJE. EDMUNDO GALINDO CLOTARIO BLEST', 'MELIPILLA', NULL, '37870355', '2014-09-15', '2024-03-01', '1900-01-01'),
(427, 2024, 110, 4, '4° básico', 'B', '24770288', '1', 'F', 'MAITE ANAÍS', 'SÁNCHEZ', 'PETACCI', NULL, 'MELIPILLA', NULL, NULL, '2014-10-13', '2024-03-01', '1900-01-01'),
(428, 2024, 110, 4, '4° básico', 'B', '24600109', 'K', 'F', 'DAPHNE YAELA', 'SEDANO', 'ACUÑA', 'LAGO PUYEHUE BLOCK 515 DPTO B12', 'MELIPILLA', NULL, '96496148', '2014-04-19', '2024-03-01', '1900-01-01'),
(429, 2024, 110, 4, '4° básico', 'B', '24822043', '0', 'M', 'JOAQUÍN IGNACIO', 'SOTO', 'GÁRATE', 'POBL. PABLO LIZAMA PAS. OB. GUILLERMO VERA 2075', 'MELIPILLA', NULL, '95840609', '2014-12-01', '2024-03-01', '1900-01-01'),
(430, 2024, 110, 4, '4° básico', 'B', '26536217', '6', 'F', 'WIDENERLIE MILEY', 'SUPERVIL', NULL, 'LAS ARAUCARIAS 1734 POBL. LA FORESTA', 'MELIPILLA', NULL, '98693456', '2014-09-26', '2024-03-01', '2024-04-01'),
(431, 2024, 110, 4, '4° básico', 'B', '24933957', '1', 'F', 'AMANDA CAROLINA DEL CARMEN', 'URRUTIA', 'SOTO', 'POBL. B. VICUÑA MACKENNA PAJE VICTOR MARIN', 'MELIPILLA', NULL, '41556520', '2015-03-18', '2024-03-01', '1900-01-01'),
(432, 2024, 110, 4, '4° básico', 'B', '24725782', '9', 'M', 'GABRIEL ELÍAS', 'VÁSQUEZ', 'QUIROZ', 'PASAJE RAÚL SILVA HENRIQUEZ 2107', 'MELIPILLA', NULL, '93932853', '2014-08-27', '2024-03-01', '1900-01-01'),
(433, 2024, 110, 4, '4° básico', 'B', '24841793', '5', 'M', 'VICENTE ALEJANDRO', 'VIDAL', 'FLORES', 'ARZA 1669 LOS LAGOS 1', 'MELIPILLA', NULL, '95023795', '2014-12-26', '2024-03-01', '1900-01-01'),
(434, 2024, 110, 4, '4° básico', 'B', '24680276', '9', 'M', 'JOSUE ANDRÉS', 'VILLALOBOS', 'PIÑA', 'MONSEÑOR LUIS BORREMAN 34', 'MELIPILLA', NULL, '99704620', '2014-07-10', '2024-03-01', '1900-01-01'),
(435, 2024, 110, 4, '4° básico', 'B', '24736903', '1', 'F', 'JOSEFINA IGNACIA', 'ZÁRATE', 'ZAVALA', NULL, 'MELIPILLA', NULL, '57806370', '2014-09-09', '2024-03-01', '1900-01-01'),
(436, 2024, 110, 4, '4° básico', 'B', '24660538', '6', 'F', 'IGNACIA ANAÍS DEL PILAR', 'DÍAZ', 'PIZARRO', 'LAS ARAUCARIAS 1722', 'MELIPILLA', NULL, NULL, '2014-06-16', '2024-03-01', '1900-01-01'),
(437, 2024, 110, 4, '4° básico', 'B', '24755958', '2', 'F', 'MONSERRAT CATALINA', 'ESCOBEDO', 'FARÍAS', 'LOMAS DE MANZO', 'MELIPILLA', NULL, '81736069', '2014-09-30', '2024-03-01', '1900-01-01'),
(438, 2024, 110, 4, '4° básico', 'B', '25806968', '4', 'M', 'DIEGO ALEJANDRO', 'FOSSI', 'CISNEROS', NULL, 'MELIPILLA', NULL, NULL, '2015-01-28', '2024-03-01', '1900-01-01'),
(439, 2024, 110, 4, '4° básico', 'B', '24840171', '0', 'M', 'FRANCESCO MAXIMILIANO AMATHIEL', 'GALLEGUILLOS', 'VILLELA', 'VILLA BENJAMIN ULLOA 127 LUIS BORREMANS', 'MELIPILLA', NULL, '97486630', '2014-12-22', '2024-03-01', '1900-01-01'),
(440, 2024, 110, 4, '4° básico', 'B', '24787864', '5', 'F', 'MARÍA CELESTE', 'GOMEZ', 'CABEZAS', NULL, 'MELIPILLA', NULL, NULL, '2014-10-29', '2024-03-01', '1900-01-01'),
(441, 2024, 110, 4, '4° básico', 'B', '24790156', '6', 'F', 'AURORA EMILIA', 'GONZÁLEZ', 'NÚÑEZ', NULL, 'MELIPILLA', NULL, NULL, '2014-11-04', '2024-03-01', '1900-01-01'),
(442, 2024, 110, 4, '4° básico', 'B', '27043845', '8', 'M', 'JEPHTE ALCIMA', 'ORIMA', NULL, NULL, 'MELIPILLA', NULL, '33398641', '2015-03-06', '2024-03-01', '1900-01-01'),
(443, 2024, 110, 4, '4° básico', 'B', '100718057', '4', 'M', 'THIAHO JEREMY', 'CAYUBA', 'ORTIZ', NULL, 'MELIPILLA', NULL, NULL, '2015-02-24', '2024-03-01', '1900-01-01'),
(444, 2024, 110, 4, '4° básico', 'B', '100731883', '5', 'M', 'RONALD YEFERSSON', 'LIZANA', 'ROCHA', NULL, 'MELIPILLA', NULL, NULL, '2014-09-06', '2024-03-05', '1900-01-01'),
(445, 2024, 110, 4, '4° básico', 'B', '100483631', '2', 'M', 'JULIA BERTHA', 'SALVADOR', 'CRUZ', NULL, 'MELIPILLA', NULL, '7901795', '2015-03-10', '2024-03-05', '1900-01-01'),
(446, 2024, 110, 5, '5° básico', 'A', '24150846', '3', 'M', 'IVAN JAVIER', 'SCHMEISSER', 'CASTRO', 'LAS LILAS VILLA LOS CARDENALES', 'SAN ANTONIO', 'GIANNINA.CASTRO.02@GMAIL.COM', '34413295', '2012-12-28', '2024-03-01', '1900-01-01'),
(447, 2024, 110, 5, '5° básico', 'A', '24181999', 'K', 'F', 'THIARE AYLEN', 'OLIVEROS', 'JIMÉNEZ', NULL, 'SAN ANTONIO', NULL, '50574715', '2013-01-30', '2024-03-01', '1900-01-01'),
(448, 2024, 110, 5, '5° básico', 'A', '24279243', '2', 'F', 'ASHLEY ALISON', 'BRIONES', 'SANTELICES', NULL, 'CONCHALÍ', NULL, NULL, '2013-05-15', '2024-03-01', '1900-01-01'),
(449, 2024, 110, 5, '5° básico', 'A', '24499026', '6', 'M', 'MATEO BALTAZAR', 'GUAJARDO', 'VIVANCO', 'PAPA JUAN XXIII 953', 'HUECHURABA', NULL, '95580778', '2013-12-26', '2024-03-01', '1900-01-01'),
(450, 2024, 110, 5, '5° básico', 'A', '24589641', '7', 'F', 'AGUSTINA IGNACIA', 'SANDOVAL', 'CONTRERAS', 'LOS VILOS 6885', 'LA GRANJA', NULL, NULL, '2014-04-06', '2024-03-01', '1900-01-01'),
(451, 2024, 110, 5, '5° básico', 'A', '24542237', '7', 'M', 'AARÓN MAXIMILIANO', 'HERRERA', 'VALENZUELA', 'LOS PLATANOS 1095', 'LO PRADO', 'MARCIA1004@GMAIL.COM', '72416970', '2014-02-19', '2024-03-01', '1900-01-01'),
(452, 2024, 110, 5, '5° básico', 'A', '24482750', '0', 'M', 'FERNANDO ALONSO', 'RIVERA', 'CASTELLANOS', 'EL ACANTILADO', 'PEÑALOLÉN', 'FRIVERA.CASTELLANOS@ESTUDIANTE.REDUCA.CL', '93655144', '2013-12-12', '2024-03-05', '1900-01-01'),
(453, 2024, 110, 5, '5° básico', 'A', '24572262', '1', 'F', 'PASCAL IVÓN', 'YÁÑEZ', 'AEDO', 'ATACAMA 9260', 'PUDAHUEL', NULL, '67021642', '2014-03-20', '2024-03-01', '1900-01-01'),
(454, 2024, 110, 5, '5° básico', 'A', '24434348', '1', 'F', 'VIOLETA AYELEN', 'JARA', 'MATURANA', NULL, 'RECOLETA', NULL, NULL, '2013-09-11', '2024-03-01', '1900-01-01'),
(455, 2024, 110, 5, '5° básico', 'A', '24566813', '9', 'M', 'MAXIMILIANO ALONSO', 'BRAVO', 'GIANONI', NULL, 'RECOLETA', NULL, NULL, '2014-03-15', '2024-03-01', '1900-01-01'),
(456, 2024, 110, 5, '5° básico', 'A', '24318209', '3', 'F', 'MAILY ISIDORA', 'MARDONES', 'VALENZUELA', 'P. TINEO 1995 ALTO CANTILLANA', 'MELIPILLA', NULL, '73642421', '2013-06-23', '2024-03-01', '1900-01-01'),
(457, 2024, 110, 5, '5° básico', 'A', '24282009', '6', 'M', 'EMILIANO JAVIER', 'ACEVEDO', 'MARTÍNEZ', 'LASTENIA ALVAREZ', 'MELIPILLA', 'IM9551613@GMAIL.COM', '66867511', '2013-05-21', '2024-03-01', '1900-01-01'),
(458, 2024, 110, 5, '5° básico', 'A', '24580511', 'K', 'M', 'ISAIAS ANTONIO', 'ARMIJO', 'AGUIRRE', 'PASAJE MAULO 999', 'MELIPILLA', NULL, '71931374', '2014-03-28', '2024-03-01', '1900-01-01'),
(459, 2024, 110, 5, '5° básico', 'A', '24329515', '7', 'M', 'FRANCO ALEXANDER', 'BUSTAMANTE', 'MUÑOZ', 'BENJAMIN VICUÑA', 'MELIPILLA', NULL, NULL, '2013-07-04', '2024-03-01', '1900-01-01'),
(460, 2024, 110, 5, '5° básico', 'A', '24391589', '9', 'F', 'CLAUDIA NICOLE', 'CABRERA', 'SILVA', 'CLOTARIO BLESS', 'MELIPILLA', NULL, NULL, '2013-08-26', '2024-03-01', '1900-01-01'),
(461, 2024, 110, 5, '5° básico', 'A', '24391622', '4', 'M', 'ERICK RODRIGO', 'CABRERA', 'SILVA', 'CLOTARIO BLESS', 'MELIPILLA', NULL, NULL, '2013-08-26', '2024-03-01', '1900-01-01'),
(462, 2024, 110, 5, '5° básico', 'A', '24279794', '9', 'M', 'CRISTIAN EDGARDO', 'CARRASCO', 'ROBLEDO', NULL, 'MELIPILLA', NULL, NULL, '2013-05-16', '2024-03-01', '1900-01-01'),
(463, 2024, 110, 5, '5° básico', 'A', '24474116', '9', 'F', 'SCARLETT ESPERANZA', 'CATALÁN', 'VILCHES', 'PINTOR ROBERTO MATTA 150 VILLA LOS JAZMINES', 'MELIPILLA', NULL, '85984557', '2013-12-04', '2024-03-01', '1900-01-01'),
(464, 2024, 110, 5, '5° básico', 'A', '24508579', '6', 'M', 'MANUEL IGNACIO', 'CHAPARRO', 'RIVAS', 'CARLOS AVILES BLOCK 468', 'MELIPILLA', NULL, '41496801', '2014-01-12', '2024-03-01', '1900-01-01'),
(465, 2024, 110, 5, '5° básico', 'A', '24515291', '4', 'M', 'DIDIÉR NICOLÁS', 'DA CONCEICAO', 'JELDRES', NULL, 'MELIPILLA', NULL, NULL, '2014-01-18', '2024-03-01', '1900-01-01'),
(466, 2024, 110, 5, '5° básico', 'A', '24285135', '8', 'M', 'EYDAN ANDRÉS', 'GONZÁLEZ', 'GUZMÁN', NULL, 'MELIPILLA', NULL, NULL, '2013-05-21', '2024-03-01', '1900-01-01'),
(467, 2024, 110, 5, '5° básico', 'A', '24447904', '9', 'F', 'SAYEN AGUSTINA', 'GONZÁLEZ', 'HUENULLÁN', NULL, 'MELIPILLA', NULL, NULL, '2013-11-10', '2024-03-01', '1900-01-01'),
(468, 2024, 110, 5, '5° básico', 'A', '24517844', '1', 'F', 'FLORENCIA ISABEL', 'OYARZÚN', 'BUSTOS', 'CARLOS AVILÉS 172 POBL. BENJAMÍN ULLOA', 'MELIPILLA', NULL, '97401516', '2014-01-20', '2024-03-01', '1900-01-01'),
(469, 2024, 110, 5, '5° básico', 'A', '24318190', '9', 'F', 'FERNANDA CAROLINA', 'PEÑAILILLO', 'MUÑOZ', 'BERNARDO VALENZUELA 1880', 'MELIPILLA', NULL, '55889554', '2013-06-28', '2024-03-01', '1900-01-01'),
(470, 2024, 110, 5, '5° básico', 'A', '24467080', '6', 'F', 'BLANCA IGNACIA', 'PIÑA', 'GUTIÉRREZ', NULL, 'MELIPILLA', NULL, NULL, '2013-11-29', '2024-03-01', '1900-01-01'),
(471, 2024, 110, 5, '5° básico', 'A', '24468043', '7', 'F', 'JAVIERA FERNANDA IGNACIA', 'RAMIREZ', 'ROCO', 'PASAJE LUIS BORREMANS 182 POBL. B. ULLOA', 'MELIPILLA', NULL, '72050828', '2013-11-28', '2024-03-01', '1900-01-01'),
(472, 2024, 110, 5, '5° básico', 'A', '24616835', '0', 'F', 'EMILIA MONTSERRAT', 'RETAMAL', 'DÍAZ', 'LOMAS DE MNASO 6 BLOCK 920 DPTO 102', 'MELIPILLA', NULL, '84284112', '2014-05-07', '2024-03-01', '1900-01-01'),
(473, 2024, 110, 5, '5° básico', 'A', '24446770', '9', 'M', 'DYLAN MAXIMILIANO ALONSO', 'RUBIO', 'AGUILERA', NULL, 'MELIPILLA', NULL, NULL, '2013-11-07', '2024-03-01', '1900-01-01'),
(474, 2024, 110, 5, '5° básico', 'A', '24369884', '7', 'F', 'JAVIERA ANAÍS', 'VICENCIO', 'TRINCADO', 'ADOLFO LARRAÍN VALDIVIESO 466 FLORENCIA 2', 'MELIPILLA', NULL, '88221615', '2013-08-22', '2024-03-01', '1900-01-01'),
(475, 2024, 110, 5, '5° básico', 'A', '24325414', '0', 'M', 'MÁXIMO IGNACIO', 'VILLAR', 'GÓMEZ', NULL, 'MELIPILLA', NULL, NULL, '2013-07-02', '2024-03-01', '1900-01-01'),
(476, 2024, 110, 5, '5° básico', 'A', '24381393', 'K', 'F', 'CAROLINA SCARLETT', 'GUZMÁN', 'RUMINAUS', 'LOS PEUMOS 150 LA FORESTA', 'MELIPILLA', NULL, '97810013', '2013-09-06', '2024-03-01', '1900-01-01'),
(477, 2024, 110, 5, '5° básico', 'A', '24308788', '0', 'F', 'ELIZABETH DEL CARMEN', 'JERIA', 'RAMOS', 'PASAJE PEDRO ALONSO FERNANDEZ 2124 POBL. PABLO LIZAMA', 'MELIPILLA', NULL, '98586899', '2013-06-20', '2024-03-01', '1900-01-01'),
(478, 2024, 110, 5, '5° básico', 'A', '24506150', '1', 'F', 'MARTINA IGNACIA ANAÍS', 'LÓPEZ', 'SUAZO', 'EL TINEO 2085 VILLA ALTO CANTILLANA', 'MELIPILLA', NULL, '94614851', '2014-01-09', '2024-03-01', '1900-01-01'),
(479, 2024, 110, 5, '5° básico', 'A', '24392574', '6', 'F', 'AYLIN SCARLETH', 'MARTE', 'ASTUDILLO', 'STA. VICTORIA, MALLARAUCO', 'MELIPILLA', NULL, NULL, '2013-09-25', '2024-03-01', '1900-01-01'),
(480, 2024, 110, 5, '5° básico', 'A', '24392624', '6', 'F', 'JAZMÍN AYLEEN', 'MARTE', 'ASTUDILLO', 'STA CLARA', 'MELIPILLA', NULL, NULL, '2013-09-25', '2024-03-01', '1900-01-01'),
(481, 2024, 110, 5, '5° básico', 'A', '24338385', '4', 'F', 'MARIANA POULLET', 'MOYA', 'MUÑOZ', NULL, 'MELIPILLA', NULL, NULL, '2013-07-19', '2024-03-01', '1900-01-01'),
(482, 2024, 110, 5, '5° básico', 'A', '24409720', '0', 'M', 'PABLO ALBERTO', 'NÚÑEZ', 'AVENDAÑO', 'RAÚL SILVA HENRÍQUEZ', 'MELIPILLA', NULL, '76146309', '2013-08-28', '2024-03-01', '1900-01-01'),
(483, 2024, 110, 5, '5° básico', 'A', '24589915', '7', 'M', 'AGUSTÍN ALONSO', 'OLIVOS', 'ATENAS', 'PARCELA 12 LA ALIANZA SANTA JULIA', 'MELIPILLA', NULL, '77335073', '2014-04-04', '2024-03-01', '1900-01-01'),
(484, 2024, 110, 5, '5° básico', 'A', '24474354', '4', 'M', 'BASTIÁN IGNACIO', 'VARGAS', 'VALDÉS', NULL, 'MELIPILLA', NULL, NULL, '2013-12-03', '2024-03-01', '1900-01-01'),
(485, 2024, 110, 5, '5° básico', 'A', '24286381', 'K', 'M', 'TOBÍAS HAKIM ANDRÉS', 'SILVA', 'IÑÍGUEZ', NULL, 'EL MONTE', NULL, NULL, '2013-05-24', '2024-03-01', '1900-01-01'),
(486, 2024, 110, 5, '5° básico', 'B', '24337033', '7', 'M', 'JOCSAN JULIANO', 'ILLANES', 'JARA', NULL, 'CARTAGENA', NULL, NULL, '2013-07-18', '2024-03-01', '1900-01-01'),
(487, 2024, 110, 5, '5° básico', 'B', '24469050', '5', 'F', 'AMALIA ANDREA', 'RAMÍREZ', 'CÁCERES', 'EL MANZANO', 'LAS CABRAS', NULL, '92114721', '2013-11-29', '2024-03-01', '1900-01-01'),
(488, 2024, 110, 5, '5° básico', 'B', '24328634', '4', 'M', 'RODRIGO NESTTA', 'PEÑAILILLO', 'ROZAS', NULL, 'CONCHALÍ', NULL, NULL, '2013-07-11', '2024-03-01', '1900-01-01'),
(489, 2024, 110, 5, '5° básico', 'B', '24504693', '6', 'F', 'MAITE ALEJANDRA', 'SANZANA', 'ARENAS', 'DIEGO SILVA', 'CONCHALÍ', NULL, NULL, '2014-01-10', '2024-03-01', '1900-01-01'),
(490, 2024, 110, 5, '5° básico', 'B', '24286801', '3', 'F', 'VALENTINA ESTEFANÍA', 'OSORIO', 'AHUMADA', 'EL LIRQUE 13204, POBLACIÓN SAN RICARDO', 'LA PINTANA', NULL, '43059486', '2013-05-23', '2024-03-01', '1900-01-01'),
(491, 2024, 110, 5, '5° básico', 'B', '24556857', '6', 'M', 'ALAIN LAUTARO', 'JIMÉNEZ', 'NILSSON', 'SAN ALBERTO', 'LO PRADO', 'CARMENNILSSON_80@HOTMAIL.COM', '69005443', '2014-03-06', '2024-03-01', '1900-01-01'),
(492, 2024, 110, 5, '5° básico', 'B', '24331655', '3', 'M', 'SEBASTIEN JÉRÉMY', 'CARRASCO', 'GONZÁLEZ', 'LUIS DURAN 3364', 'MACUL', NULL, NULL, '2013-07-13', '2024-03-01', '1900-01-01'),
(493, 2024, 110, 5, '5° básico', 'B', '24480843', '3', 'F', 'KATHYA SOFÍA', 'RALIL', 'MIRANDA', 'PASAJE VARINIA 2891', 'MAIPÚ', NULL, '57708685', '2013-12-10', '2024-03-01', '1900-01-01'),
(494, 2024, 110, 5, '5° básico', 'B', '24470649', '5', 'M', 'MAXIMILIANO ALEXIS', 'MARIMÁN', 'MUÑOZ', 'AVDA. VICUÑA MACKENNA 2099', 'MAIPÚ', NULL, '41439592', '2013-12-03', '2024-03-01', '1900-01-01'),
(495, 2024, 110, 5, '5° básico', 'B', '24392506', '1', 'M', 'CRISTÓBAL AGUSTÍN ANDRES', 'LABARCA', 'ZAPATA', 'FILOMENA GARATE 740', 'QUILICURA', NULL, '97904290', '2013-09-12', '2024-03-01', '1900-01-01'),
(496, 2024, 110, 5, '5° básico', 'B', '24560483', '1', 'M', 'AGUSTÍN ANDRÉS', 'AGUIRRE', 'ESTAY', NULL, 'MELIPILLA', NULL, NULL, '2014-03-08', '2024-03-01', '1900-01-01'),
(497, 2024, 110, 5, '5° básico', 'B', '24020425', '8', 'M', 'SEBASTIÁN ANDRÉS', 'AGUIRRE', 'JEREZ', NULL, 'MELIPILLA', NULL, NULL, '2012-07-25', '2024-03-01', '1900-01-01'),
(498, 2024, 110, 5, '5° básico', 'B', '24469308', '3', 'F', 'MONTSERRAT VIOLETA', 'ARAYA', 'FUENZALIDA', 'PASAJE PAPA LEÓN XIII Nº 2075', 'MELIPILLA', NULL, '34850334', '2013-11-28', '2024-03-01', '1900-01-01'),
(499, 2024, 110, 5, '5° básico', 'B', '24365286', '3', 'F', 'IGNACIA MIA', 'ARTILLERÍA', 'GAVILÁN', 'PINTOR ROBERTO MATTA 381 ILUSIONES COMPARTIDAS', 'MELIPILLA', NULL, '76515857', '2013-08-17', '2024-03-01', '1900-01-01'),
(500, 2024, 110, 5, '5° básico', 'B', '24326873', '7', 'F', 'HELEN EMILIA', 'ATENAS', 'VENEGAS', 'PAS.JOSÉ ANTONIO PACHECO 337 VILLA I. COMPARTIDAS', 'MELIPILLA', NULL, '98125857', '2013-07-08', '2024-03-01', '1900-01-01'),
(501, 2024, 110, 5, '5° básico', 'B', '24497603', '4', 'M', 'MISAEL JESÚS', 'BRICEÑO', 'NEYRA', 'PARCELA 29 LOTE 3 CHOLQUI', 'MELIPILLA', NULL, '93934610', '2013-12-28', '2024-03-01', '1900-01-01'),
(502, 2024, 110, 5, '5° básico', 'B', '24577481', '8', 'F', 'KRISTAL DANIELA ALTAIR', 'CÁRDENAS', 'RIVEROS', 'ROBERTO MATTA', 'MELIPILLA', NULL, '88679689', '2014-03-24', '2024-03-01', '1900-01-01'),
(503, 2024, 110, 5, '5° básico', 'B', '24368857', '4', 'M', 'VÍCTOR MANUEL', 'CASTRO', 'ESPINOZA', 'JULIO MONTT 290 POBL. PADRE DEMETRIO BRAVO', 'MELIPILLA', NULL, '73196091', '2013-08-16', '2024-03-01', '1900-01-01'),
(504, 2024, 110, 5, '5° básico', 'B', '26944244', '1', 'M', 'DYLAN JAVIER', 'FERREIRA', 'GONZALEZ', NULL, 'MELIPILLA', NULL, '41584477', '2013-05-27', '2024-03-01', '1900-01-01'),
(505, 2024, 110, 5, '5° básico', 'B', '24580626', '4', 'M', 'ANGEL DAZTAN SIMONS', 'FLORES', 'CERÓN', 'OBISPO GUILLERMO VERA 2120 POBL . B. VICUÑA MAKKENNA', 'MELIPILLA', NULL, '83017767', '2014-03-29', '2024-03-01', '1900-01-01'),
(506, 2024, 110, 5, '5° básico', 'B', '24334712', '2', 'M', 'MAXIMILIANO ANTONIO', 'FLORES', 'GARCÍA', 'LOS CARDENALES 739 POBL. VILLA LOS CARDENALES', 'MELIPILLA', NULL, '96680771', '2013-07-17', '2024-03-01', '1900-01-01'),
(507, 2024, 110, 5, '5° básico', 'B', '24573311', '9', 'F', 'AZALETT TAMAR', 'FUENTES', 'MORENO', NULL, 'MELIPILLA', NULL, NULL, '2014-03-19', '2024-03-01', '1900-01-01'),
(508, 2024, 110, 5, '5° básico', 'B', '24405946', '5', 'F', 'AYNHARA ANTONELLA', 'GALLEGUILLOS', 'VILLELA', 'MONS.LUIS BORREMANS 127 VILLA BENJAMÍN ULLOA', 'MELIPILLA', NULL, '74866304', '2013-10-04', '2024-03-01', '1900-01-01'),
(509, 2024, 110, 5, '5° básico', 'B', '24567378', '7', 'F', 'CARLA VICTORIA', 'GONZÁLEZ', 'PINTO', NULL, 'MELIPILLA', NULL, NULL, '2014-03-13', '2024-03-01', '1900-01-01'),
(510, 2024, 110, 5, '5° básico', 'B', '24486506', '2', 'M', 'AGUSTÍN MAXIMILIANO', 'JULIO', 'AMPUERO', NULL, 'MELIPILLA', NULL, NULL, '2013-12-18', '2024-03-01', '1900-01-01'),
(511, 2024, 110, 5, '5° básico', 'B', '24506460', '8', 'M', 'MATÍAS ANDRÉS', 'ROJAS', 'REYES', 'PASAJE JOSÉ ARTURO PACHECO 184 LOS JAZMINES', 'MELIPILLA', NULL, '35481409', '2013-12-23', '2024-03-01', '1900-01-01'),
(512, 2024, 110, 5, '5° básico', 'B', '24346302', '5', 'M', 'JEAN PAUL', 'SANTIBÁÑEZ', 'FLORES', 'BRUNO ROMANINI 19 POBL B. VICUÑA MACKENNA', 'MELIPILLA', NULL, '97210413', '2013-07-28', '2024-03-01', '1900-01-01'),
(513, 2024, 110, 5, '5° básico', 'B', '24515703', '7', 'F', 'AGUSTINA MONTSERRAT', 'TORRES', 'MOYA', 'POBLA. BENJAMIN VICUÑA ULLOA PJE.EMILIA SANCHEZ 25', 'MELIPILLA', NULL, '61528581', '2013-12-25', '2024-03-01', '1900-01-01'),
(514, 2024, 110, 5, '5° básico', 'B', '24438642', '3', 'M', 'JOHAN SEBASTIÁN', 'VALENZUELA', 'ORIAS', NULL, 'MELIPILLA', NULL, NULL, '2013-10-28', '2024-03-01', '1900-01-01'),
(515, 2024, 110, 5, '5° básico', 'B', '24451451', '0', 'M', 'WILLIAMS ALEXANDER', 'MARDONES', 'SILVA', 'AVENIDA CIRCUNVALACION', 'MELIPILLA', NULL, NULL, '2013-11-12', '2024-03-01', '1900-01-01'),
(516, 2024, 110, 5, '5° básico', 'B', '24558783', 'K', 'F', 'KARLA CONSTANZA', 'MARINAO', 'GÓMEZ', 'LUIS EMILIO RECABARREN 2436 POBL .CLOTARIO BLEST', 'MELIPILLA', NULL, '98562090', '2014-03-09', '2024-03-01', '1900-01-01'),
(517, 2024, 110, 5, '5° básico', 'B', '24504944', '7', 'M', 'DANILO ALONSO', 'MARTÍNEZ', 'ROJAS', 'PAPA PIO XI 2096', 'MELIPILLA', NULL, NULL, '2013-12-31', '2024-03-01', '1900-01-01'),
(518, 2024, 110, 5, '5° básico', 'B', '24542270', '9', 'F', 'MONSERRAT DEL CARMEN', 'MEZA', 'CORNEJO', 'PASAJE EMILIO SÁNCHEZ 49 VILLA BENJAMÍN ULLOA', 'MELIPILLA', NULL, '98552726', '2014-02-18', '2024-03-01', '1900-01-01'),
(519, 2024, 110, 5, '5° básico', 'B', '24400760', '0', 'F', 'MICHELLE ANGELIA', 'MORALES', 'MARTÍNEZ', 'CARLOS AVILÉS DPTO. 32 Nº 405 LOS POETAS', 'MELIPILLA', NULL, '44988808', '2013-10-05', '2024-03-01', '1900-01-01');
INSERT INTO `matricula` (`id`, `ano`, `cod_tipo_ensenanza`, `cod_grado`, `desc_grado`, `letra_curso`, `run`, `digito_ver`, `genero`, `nombres`, `apellido_paterno`, `apellido_materno`, `direccion`, `comuna_residencia`, `email`, `telefono`, `fecha_nacimiento`, `fecha_incorporacion_curso`, `fecha_retiro`) VALUES
(520, 2024, 110, 5, '5° básico', 'B', '24400719', '8', 'F', 'VIOLETTA ANTONELLA', 'MORALES', 'MARTÍNEZ', 'CARLOS AVILES DPTO. 32 Nº 405 LOS POETAS', 'MELIPILLA', NULL, '94498880', '2013-10-05', '2024-03-01', '1900-01-01'),
(521, 2024, 110, 5, '5° básico', 'B', '24513013', '9', 'M', 'ALONSO MATÍAS', 'NARANJO', 'SILVA', 'LOS PEUMOS 121 LA FORESTA', 'MELIPILLA', NULL, '98468302', '2014-01-18', '2024-03-01', '1900-01-01'),
(522, 2024, 110, 5, '5° básico', 'B', '24243730', '6', 'F', 'FRANCISCA IVONNE', 'NÚÑEZ', 'GARCÍA', 'ILUSIONES COMPARTIDAS', 'MELIPILLA', NULL, NULL, '2013-04-10', '2024-03-01', '1900-01-01'),
(523, 2024, 110, 5, '5° básico', 'B', '24483994', '0', 'F', 'MONTSERRAT PASCALE', 'PAZ', 'FARÍAS', 'VIKUÑA MACKENNA 2091 POBL. BENJAMÍN VICUÑA', 'MELIPILLA', NULL, '95810717', '2013-12-04', '2024-03-01', '1900-01-01'),
(524, 2024, 110, 5, '5° básico', 'B', '23628934', '6', 'M', 'BENJAMÍN ENRIQUE', 'NOVOA', 'GUTIÉRREZ', 'BALAKIREFF', 'MELIPILLA', NULL, '32129355', '2011-04-25', '2024-03-01', '1900-01-01'),
(525, 2024, 110, 5, '5° básico', 'B', '24518480', '8', 'F', 'VALENTINA IGNACIA', 'AGUILAR', 'HERNÁNDEZ', NULL, 'MELIPILLA', NULL, NULL, '2014-01-22', '2024-03-05', '1900-01-01'),
(526, 2024, 110, 6, '6° básico', 'A', '24276653', '9', 'F', 'ANTONELLA MONSERRAT', 'ORTIZ', 'DÍAZ', NULL, 'LOS ÁNGELES', NULL, NULL, '2013-05-15', '2024-03-05', '1900-01-01'),
(527, 2024, 110, 6, '6° básico', 'A', '24002755', '0', 'F', 'CONSTANZA ANAÍS', 'TORRES', 'HAX', 'AV. LIBERTADOR BERNARDO O´HIGGINS N° 3457 CASA 9', 'ESTACIÓN CENTRAL', NULL, '64970063', '2012-07-07', '2024-03-01', '1900-01-01'),
(528, 2024, 110, 6, '6° básico', 'A', '24317551', '8', 'M', 'KURT FRANCISCO', 'LEHMANN', 'FIGUEROA', NULL, 'PEÑALOLÉN', NULL, NULL, '2013-06-26', '2024-03-05', '1900-01-01'),
(529, 2024, 110, 6, '6° básico', 'A', '24113002', '9', 'F', 'MAITE ELOÍSA', 'ÁVILA', 'BOBADILLA', 'DINAR ARGELINO 654', 'PUDAHUEL', NULL, '30222343', '2012-11-10', '2024-03-01', '1900-01-01'),
(530, 2024, 110, 6, '6° básico', 'A', '24192890', 'K', 'M', 'BENJAMÍN ENRIQUE', 'MARIQUEO', 'ROZAS', NULL, 'PUDAHUEL', NULL, NULL, '2013-02-11', '2024-03-01', '1900-01-01'),
(531, 2024, 110, 6, '6° básico', 'A', '23820431', '3', 'M', 'LUIS SAMUEL DAVID', 'HERNÁNDEZ', 'MONTENEGRO', 'ALTO CANTILLANA/ NIRRE', 'MELIPILLA', NULL, '86984540', '2011-12-12', '2024-03-01', '1900-01-01'),
(532, 2024, 110, 6, '6° básico', 'A', '100557765', '5', 'M', 'SHANTAL ALESKA', 'BRICEÑO', 'ALVIAREZ', NULL, 'MELIPILLA', NULL, NULL, '2012-04-11', '2024-03-06', '1900-01-01'),
(533, 2024, 110, 6, '6° básico', 'A', '100614165', '6', 'F', 'ZHARICK MAITE', 'ALANES', 'LOPEZ', NULL, 'MELIPILLA', NULL, '55276067', '2012-05-10', '2024-03-01', '1900-01-01'),
(534, 2024, 110, 6, '6° básico', 'A', '24224128', '2', 'F', 'LILIAN VALENTINA', 'ÁVALOS', 'BALLESTEROS', 'RAMON VALDIVIESO CALLE VALDES 1422', 'MELIPILLA', NULL, '73571112', '2013-03-20', '2024-03-01', '1900-01-01'),
(535, 2024, 110, 6, '6° básico', 'A', '23977118', '1', 'M', 'ALONSO ANDRÉS', 'CALDERÓN', 'MUÑOZ', 'VILLA FLORENCIA 2, PSJE ANA JARPA CHAPARRO N 2261', 'MELIPILLA', NULL, '28325083', '2012-06-08', '2024-03-01', '1900-01-01'),
(536, 2024, 110, 6, '6° básico', 'A', '23973976', '8', 'M', 'FRANCISCO IGNACIO', 'CARREÑO', 'CARRASCO', NULL, 'MELIPILLA', NULL, NULL, '2012-05-28', '2024-03-01', '1900-01-01'),
(537, 2024, 110, 6, '6° básico', 'A', '26293491', '8', 'F', 'DAILA ESBELIA', 'CORONADO', 'QUISPE', 'VALDES 1448 - B-13', 'MELIPILLA', NULL, NULL, '2013-03-02', '2024-03-01', '1900-01-01'),
(538, 2024, 110, 6, '6° básico', 'A', '24116177', '3', 'M', 'IAN JOEL', 'DAZA', 'LEÓN', NULL, 'MELIPILLA', NULL, NULL, '2012-11-14', '2024-03-01', '1900-01-01'),
(539, 2024, 110, 6, '6° básico', 'A', '24075549', '1', 'F', 'DAYANA ALEXANDRA', 'DELGADILLO', 'ACUÑA', NULL, 'MELIPILLA', NULL, NULL, '2012-09-25', '2024-03-01', '1900-01-01'),
(540, 2024, 110, 6, '6° básico', 'A', '24244834', '0', 'F', 'NOEMÍ ANTONIA', 'DELGADO', 'BECERRA', 'LOS CARDENALES PASAJE CAUPOLICAN 802', 'MELIPILLA', NULL, '94050050', '2013-04-13', '2024-03-01', '1900-01-01'),
(541, 2024, 110, 6, '6° básico', 'A', '24159192', '1', 'F', 'JULIETA JAEL', 'FARÍAS', 'GALLEGUILLOS', 'CALLE MANUEL MARIN 2241 VILLA FLORENCIA', 'MELIPILLA', NULL, '66225918', '2012-12-03', '2024-03-01', '1900-01-01'),
(542, 2024, 110, 6, '6° básico', 'A', '24033525', '5', 'F', 'MONSERRAT IGNACIA', 'FARÍAS', 'GONZÁLEZ', 'VOLCAN CALBUCO BLOCK D 914 DEPTO. 201 LOS VALLES', 'MELIPILLA', 'JOSELYNGONZALEZ300.JG@GMAIL.COM', NULL, '2012-08-09', '2024-03-01', '1900-01-01'),
(543, 2024, 110, 6, '6° básico', 'A', '24043312', '5', 'F', 'SHELSEE VALLOLETT EMA', 'FUENZALIDA', 'TORO', 'MAGNO ESPINOZA SECTOR CLOTARIO 229', 'MELIPILLA', NULL, '45966675', '2012-08-20', '2024-03-01', '1900-01-01'),
(544, 2024, 110, 6, '6° básico', 'A', '24264442', '5', 'M', 'AGUSTÍN ALONSO', 'GALLARDO', 'FRÍAS', NULL, 'MELIPILLA', NULL, NULL, '2013-04-28', '2024-03-01', '1900-01-01'),
(545, 2024, 110, 6, '6° básico', 'A', '24221584', '2', 'M', 'JOAQUÍN ALEXANDER', 'GONZALEZ', 'AGUIRRE', NULL, 'MELIPILLA', NULL, NULL, '2013-03-09', '2024-03-01', '1900-01-01'),
(546, 2024, 110, 6, '6° básico', 'A', '23920839', '8', 'M', 'MARTÍN ANTONIO', 'GUERRERO', 'CATALÁN', NULL, 'MELIPILLA', NULL, NULL, '2012-04-09', '2024-03-01', '1900-01-01'),
(547, 2024, 110, 6, '6° básico', 'A', '24120411', '1', 'M', 'JORGE IGNACIO', 'HERRADA', 'CERDA', NULL, 'MELIPILLA', NULL, NULL, '2012-11-14', '2024-03-01', '1900-01-01'),
(548, 2024, 110, 6, '6° básico', 'A', '24048170', '7', 'F', 'MAITTE ALEXANDRA', 'HUAIQUIL', 'QUIROZ', 'PAPA PIO XI 2092 POBL. B. VICUÑA', 'MELIPILLA', NULL, '95026577', '2012-08-07', '2024-03-01', '1900-01-01'),
(549, 2024, 110, 6, '6° básico', 'A', '23980104', '8', 'F', 'ANTONELLA IGNACIA', 'MADRID', 'ROJAS', 'ILUSIONES COMPARTIDAS', 'MELIPILLA', NULL, NULL, '2012-06-13', '2024-03-01', '1900-01-01'),
(550, 2024, 110, 6, '6° básico', 'A', '24004933', '3', 'M', 'MATÍAS EDUARDO', 'MARDONES', 'VERA', NULL, 'MELIPILLA', NULL, NULL, '2012-07-06', '2024-03-01', '1900-01-01'),
(551, 2024, 110, 6, '6° básico', 'A', '24034901', '9', 'F', 'ANTONELLA IGNACIA', 'MARTÍNEZ', 'CARRASCO', NULL, 'MELIPILLA', NULL, NULL, '2012-08-11', '2024-03-01', '1900-01-01'),
(552, 2024, 110, 6, '6° básico', 'A', '24049208', '3', 'F', 'AMALIA VALENTINA', 'OJEDA', 'BERMÚDEZ', 'PASAJE PELLIN 2624 LOS JAZMINES I NORTE', 'MELIPILLA', NULL, '97775047', '2012-08-24', '2024-03-01', '1900-01-01'),
(553, 2024, 110, 6, '6° básico', 'A', '24167782', '6', 'F', 'EVOLET ANGELA', 'OÑATE', 'ÁLVAREZ', NULL, 'MELIPILLA', NULL, NULL, '2013-01-12', '2024-03-01', '1900-01-01'),
(554, 2024, 110, 6, '6° básico', 'A', '26378834', '6', 'M', 'JOSE MIGUEL', 'PINEDA', 'ROSALES', 'SECTOR LOS LAGOS TODOS LOS SANTOS CASA 1670', 'MELIPILLA', NULL, '94147782', '2012-12-05', '2024-03-01', '1900-01-01'),
(555, 2024, 110, 6, '6° básico', 'A', '24217509', '3', 'F', 'RENATA ANTONIA', 'PINTO', 'GUERRA', NULL, 'MELIPILLA', NULL, NULL, '2013-03-11', '2024-03-01', '1900-01-01'),
(556, 2024, 110, 6, '6° básico', 'A', '24240264', '2', 'F', 'EMILY MONSERRAT', 'QUINTANA', 'VEAS', 'AV. CIRCUNVALACION 2274', 'MELIPILLA', NULL, '95633867', '2013-04-06', '2024-03-01', '1900-01-01'),
(557, 2024, 110, 6, '6° básico', 'A', '24080276', '7', 'F', 'ISIDORA ANTONIA', 'RAPIMÁN', 'TEJO', NULL, 'MELIPILLA', NULL, NULL, '2012-10-01', '2024-03-01', '2024-06-10'),
(558, 2024, 110, 6, '6° básico', 'A', '26504709', '2', 'M', 'FERNANDO', 'RINCY', NULL, 'VICUÑA MACKENNA I PASCUAL ROMANINI PARCELA 27 LOS JAZMINES', 'MELIPILLA', NULL, '95878211', '2012-11-08', '2024-03-01', '1900-01-01'),
(559, 2024, 110, 6, '6° básico', 'A', '27412397', '4', 'M', 'YEREMI JOSUE', 'RODRIGUEZ', 'SEMPRUN', NULL, 'MELIPILLA', NULL, '72649478', '2013-02-08', '2024-03-01', '1900-01-01'),
(560, 2024, 110, 6, '6° básico', 'A', '23740750', '4', 'F', 'CONSTANZA ELIZABETH', 'ROMERO', 'GUAJARDO', NULL, 'MELIPILLA', NULL, NULL, '2011-09-08', '2024-03-01', '1900-01-01'),
(561, 2024, 110, 6, '6° básico', 'A', '24075636', '6', 'F', 'MAITE ISIDORA', 'TAPIA', 'GÓMEZ', 'ARZA 1791 LOS LAGOS I', 'MELIPILLA', NULL, '58243326', '2012-09-28', '2024-03-01', '1900-01-01'),
(562, 2024, 110, 6, '6° básico', 'A', '24181948', '5', 'F', 'EMILIA ALITZA', 'URRUTIA', 'RUBILAR', 'PASAJE PELLIN 2647', 'MELIPILLA', NULL, '85030118', '2013-01-31', '2024-03-01', '1900-01-01'),
(563, 2024, 110, 6, '6° básico', 'A', '24192449', '1', 'M', 'GABRIEL EDUADRO', 'SOTO', 'ARMIJO', NULL, 'MELIPILLA', NULL, NULL, '2013-02-12', '2024-03-01', '1900-01-01'),
(564, 2024, 110, 6, '6° básico', 'A', '24021257', '9', 'F', 'ALYSON PAOLA', 'MALDONADO', 'TOLEDO', NULL, 'PADRE HURTADO', NULL, NULL, '2012-07-24', '2024-03-01', '1900-01-01'),
(565, 2024, 110, 6, '6° básico', 'B', '24172775', '0', 'M', 'IGNACIO ANDRÉS', 'SEPÚLVEDA', 'SEPÚLVEDA', NULL, 'SANTIAGO', NULL, NULL, '2013-01-21', '2024-03-01', '1900-01-01'),
(566, 2024, 110, 6, '6° básico', 'B', '24116464', '0', 'M', 'JULIÁN ANTONIO', 'CABRERA', 'FERNÁNDEZ', 'ISLA CALBUCO 1792', 'CERRO NAVIA', NULL, '77867858', '2012-11-15', '2024-03-01', '1900-01-01'),
(567, 2024, 110, 6, '6° básico', 'B', '24106198', '1', 'F', 'JUN YAO', 'YANG', 'FAN', 'AVENIDA CENTRAL 7409', 'LO ESPEJO', NULL, '95017291', '2012-11-02', '2024-03-01', '1900-01-01'),
(568, 2024, 110, 6, '6° básico', 'B', '23359198', 'K', 'F', 'CRISTAL ALMENDRA', 'ARAYA', 'SILVA', 'URANO', 'PEDRO AGUIRRE CERDA', NULL, NULL, '2010-06-27', '2024-03-01', '1900-01-01'),
(569, 2024, 110, 6, '6° básico', 'B', '23940526', '6', 'F', 'MONSERRATH ADONAY', 'RIQUELME', 'ANABALÓN', NULL, 'RENCA', 'PALOMARUTP@HOTMAIL.COM', NULL, '2012-04-26', '2024-03-01', '1900-01-01'),
(570, 2024, 110, 6, '6° básico', 'B', '24086260', '3', 'F', 'IGNACIA POLETT CANELA', 'ARENA', 'ESCALONA', 'ORÁN N° 2656', 'SAN MIGUEL', 'NIKOLLORETO19@HOTMAIL.COM', NULL, '2012-10-07', '2024-03-01', '1900-01-01'),
(571, 2024, 110, 6, '6° básico', 'B', '24058128', '0', 'M', 'RAÚL ALAN DIDIER', 'CANALES', 'SOBARZO', NULL, 'SAN BERNARDO', NULL, NULL, '2012-09-05', '2024-03-01', '1900-01-01'),
(572, 2024, 110, 6, '6° básico', 'B', '24071105', '2', 'M', 'ALONSO ISAIAS', 'ALIAGA', 'MAUREIRA', NULL, 'MELIPILLA', NULL, NULL, '2012-09-14', '2024-03-01', '1900-01-01'),
(573, 2024, 110, 6, '6° básico', 'B', '24184763', '2', 'M', 'ALAN BENJAMÍN', 'ÁLVAREZ', 'AZÚA', '3 PONIENTE LOS JAZMINEZ II PASAJE 2 Nº 373', 'MELIPILLA', NULL, '57336683', '2013-02-06', '2024-03-01', '1900-01-01'),
(574, 2024, 110, 6, '6° básico', 'B', '23973190', '2', 'M', 'DIEGO ALEJANDRO', 'ÁLVAREZ', 'VALENZUELA', 'ARZA LOS LAGOS I Nº 1799', 'MELIPILLA', NULL, '94632111', '2012-06-02', '2024-03-01', '1900-01-01'),
(575, 2024, 110, 6, '6° básico', 'B', '28103540', '1', 'M', 'JHOAN JOSE', 'ATACHO', 'TAPIA', NULL, 'MELIPILLA', NULL, NULL, '2012-10-18', '2024-03-01', '1900-01-01'),
(576, 2024, 110, 6, '6° básico', 'B', '24065546', '2', 'F', 'VICTORIA BELÉN', 'ATENAS', 'MIQUE', 'EDMUNDO GALINDO R. 2542', 'MELIPILLA', NULL, '78323342', '2012-09-14', '2024-03-01', '1900-01-01'),
(577, 2024, 110, 6, '6° básico', 'B', '23900816', 'K', 'M', 'VÍCTOR ANTONIO', 'BARRAZA', 'ORTEGA', 'LOMAS DE MANZO 4 BLOCK 837', 'MELIPILLA', NULL, '83278023', '2012-03-05', '2024-03-01', '1900-01-01'),
(578, 2024, 110, 6, '6° básico', 'B', '23991275', '3', 'M', 'BENJAMÍN ALEJANDRO', 'BECERRA', 'ARRIAZA', NULL, 'MELIPILLA', NULL, NULL, '2012-06-26', '2024-03-01', '1900-01-01'),
(579, 2024, 110, 6, '6° básico', 'B', '24046910', '3', 'F', 'CAROLINE TAHIS', 'BRACHO', 'ANDRADE', 'HUILCO ALTO PASAJE LOS OLMOS 2251', 'MELIPILLA', NULL, '88440462', '2012-08-22', '2024-03-01', '1900-01-01'),
(580, 2024, 110, 6, '6° básico', 'B', '27974277', 'K', 'M', 'LUIS FERNANDO', 'CANO', 'CARMONA', 'AV. 3 PONIENTE 498 POBLACION RENACER', 'MELIPILLA', NULL, '89236279', '2013-01-31', '2024-03-01', '1900-01-01'),
(581, 2024, 110, 6, '6° básico', 'B', '24163794', '8', 'F', 'RACHEL ANAÍS', 'CARVAJAL', 'DÍAZ', NULL, 'MELIPILLA', NULL, NULL, '2013-01-14', '2024-03-01', '2024-03-15'),
(582, 2024, 110, 6, '6° básico', 'B', '24042896', '2', 'F', 'CATALINA ANTONIA', 'FARIAS', 'MARCHANT', 'CALLE A. MARIN 2287 VILLA FLORENCIA I', 'MELIPILLA', NULL, '96176453', '2012-08-17', '2024-03-01', '1900-01-01'),
(583, 2024, 110, 6, '6° básico', 'B', '24198973', '9', 'F', 'LORENA ANAÍS', 'FLORES', 'BELTRÁN', 'VOLCAN CALBUCO BLOCK I DPTO 202', 'MELIPILLA', NULL, '95734597', '2013-02-20', '2024-03-01', '1900-01-01'),
(584, 2024, 110, 6, '6° básico', 'B', '24162847', '7', 'M', 'THOMAS ALEXANDER', 'GÓMEZ', 'ESCOBAR', NULL, 'MELIPILLA', NULL, NULL, '2013-01-11', '2024-03-01', '1900-01-01'),
(585, 2024, 110, 6, '6° básico', 'B', '24259857', '1', 'F', 'AGUSTINA FRANCISCA', 'GONZÁLEZ', 'ALLENDES', NULL, 'MELIPILLA', NULL, NULL, '2013-04-23', '2024-03-01', '1900-01-01'),
(586, 2024, 110, 6, '6° básico', 'B', '24087528', '4', 'M', 'ANTONIO ANDRÉS ALEJANDRO', 'GONZÁLEZ', 'JAMET', NULL, 'MELIPILLA', NULL, NULL, '2012-10-02', '2024-03-01', '1900-01-01'),
(587, 2024, 110, 6, '6° básico', 'B', '24276944', '9', 'M', 'THANIEL MAXIMILIANO', 'GUZMAN', 'GUZMÁN', 'LAS ARAUCARIAS 1364 POBL. SANTA LAURA', 'MELIPILLA', NULL, '97134551', '2013-05-11', '2024-03-01', '1900-01-01'),
(588, 2024, 110, 6, '6° básico', 'B', '24145145', '3', 'F', 'PALOMA IGNACIA', 'HERMOSILLA', 'GARRIDO', 'SARGENTO CANDELARIA SAN JOSE', 'MELIPILLA', NULL, '89817174', '2012-12-19', '2024-03-01', '1900-01-01'),
(589, 2024, 110, 6, '6° básico', 'B', '24145183', '6', 'F', 'SOFÍA BELÉN', 'HERMOSILLA', 'GARRIDO', 'SARGENTO CANDELARIA 531 SAN JOSE', 'MELIPILLA', NULL, '89817174', '2012-12-19', '2024-03-01', '1900-01-01'),
(590, 2024, 110, 6, '6° básico', 'B', '23937660', '6', 'M', 'MATÍAS JULIÁN', 'HUERTA', 'JORQUERA', 'PSJE PABLO LIZAMA 352 VILLA COLONIAL II', 'MELIPILLA', NULL, '96660865', '2012-04-28', '2024-03-01', '1900-01-01'),
(591, 2024, 110, 6, '6° básico', 'B', '24307023', '6', 'F', 'SOPHIE ANTONELLA', 'INOSTROZA', 'QUINTANILLA', 'LOMAS DE MANSO BLOCK 892 DPTO 101', 'MELIPILLA', NULL, '86810246', '2013-06-16', '2024-03-01', '1900-01-01'),
(592, 2024, 110, 6, '6° básico', 'B', '23792775', '3', 'M', 'JORGE ARTURO', 'VÁSQUEZ', 'CARREÑO', 'JUAN FRANCISCO GONZÁLEZ 162 LOS JAZMINES', 'MELIPILLA', NULL, '81217919', '2011-10-29', '2024-03-01', '1900-01-01'),
(593, 2024, 110, 6, '6° básico', 'B', '24053059', '7', 'M', 'SEBASTIÁN ALONSO', 'LEVIO', 'AGUILERA', NULL, 'MELIPILLA', NULL, NULL, '2012-09-01', '2024-03-01', '1900-01-01'),
(594, 2024, 110, 6, '6° básico', 'B', '24110845', '7', 'M', 'FRANCISCO SALVADOR', 'MARTÍNEZ', 'SILVA', 'RAUL SILVA HENRIQUEZ 314 B. LEYTON', 'MELIPILLA', NULL, '89532078', '2012-11-08', '2024-03-01', '1900-01-01'),
(595, 2024, 110, 6, '6° básico', 'B', '23969555', '8', 'F', 'ANTONELLA ANAÍS', 'NAVARRO', 'RIQUELME', 'JUAN FCO. GONZALEZ 226', 'MELIPILLA', NULL, '81639802', '2012-06-01', '2024-03-01', '1900-01-01'),
(596, 2024, 110, 6, '6° básico', 'B', '24296040', '8', 'F', 'RENATA ADRIANA', 'NEGRETE', 'ALCAÍNO', 'LOMAS DE MANZO AVENIDA CIRCUNAVALACION BLOCK 818 DEPTO 302', 'MELIPILLA', NULL, '59874299', '2013-06-01', '2024-03-01', '1900-01-01'),
(597, 2024, 110, 6, '6° básico', 'B', '23977235', '8', 'F', 'DARLEEN JACQUELINE', 'PALOMINOS', 'VEGA', NULL, 'MELIPILLA', NULL, NULL, '2012-06-04', '2024-03-01', '1900-01-01'),
(598, 2024, 110, 6, '6° básico', 'B', '24048422', '6', 'F', 'TAMARA ANAÍS', 'QUINTANILLA', 'REYES', NULL, 'MELIPILLA', NULL, NULL, '2012-08-25', '2024-03-01', '1900-01-01'),
(599, 2024, 110, 6, '6° básico', 'B', '24083189', '9', 'F', 'ISABEL ESTRELLA', 'RIQUELME', 'AHUMADA', 'PAPA PIO X 2071 P. PABLO LIZAMA', 'MELIPILLA', NULL, '76522281', '2012-09-29', '2024-03-01', '1900-01-01'),
(600, 2024, 110, 6, '6° básico', 'B', '24102335', '4', 'M', 'BENJAMÍN MATEO', 'SALINAS', 'ESCALANTE', NULL, 'MELIPILLA', NULL, NULL, '2012-10-28', '2024-03-01', '1900-01-01'),
(601, 2024, 110, 6, '6° básico', 'B', '24210026', '3', 'M', 'EDUARDO VICENTE', 'TRUJILLO', 'PÉREZ', NULL, 'MELIPILLA', NULL, NULL, '2013-03-06', '2024-03-01', '1900-01-01'),
(602, 2024, 110, 6, '6° básico', 'B', '23665278', '5', 'M', 'LUIS FELIPE', 'VERA', 'CARO', 'HUILCO BAJO', 'MELIPILLA', NULL, '84952215', '2011-06-08', '2024-03-01', '1900-01-01'),
(603, 2024, 110, 6, '6° básico', 'B', '24074723', '5', 'F', 'EMILY MONSERRAT', 'NÚÑEZ', 'MAUREIRA', 'SAN DAVID, HUECHUN BAJO', 'MELIPILLA', NULL, '95460317', '2012-09-27', '2024-03-05', '1900-01-01'),
(604, 2024, 110, 6, '6° básico', 'B', '24055194', '2', 'M', 'MARTÍN TERRY HENRRY', 'TAPIA', 'GÁLVEZ', NULL, 'MELIPILLA', NULL, NULL, '2012-08-21', '2024-03-12', '1900-01-01'),
(605, 2024, 110, 7, '7° básico', 'A', '23619278', '4', 'M', 'LUIS FELIPE', 'CARVALLO', 'VERGARA', 'REINALDO PRADO 5384', 'HUECHURABA', NULL, '73064254', '2011-04-15', '2024-03-01', '1900-01-01'),
(606, 2024, 110, 7, '7° básico', 'A', '23540915', '1', 'M', 'ANTONY VICTORINO', 'SANGUINETTI', 'VIDAL', 'PORTALES 5494', 'LO PRADO', NULL, NULL, '2011-01-21', '2024-03-01', '1900-01-01'),
(607, 2024, 110, 7, '7° básico', 'A', '23747833', '9', 'M', 'BASTIÁN ALEJANDRO', 'VÁSQUEZ', 'PÉREZ', NULL, 'QUINTA NORMAL', NULL, NULL, '2011-09-19', '2024-03-01', '1900-01-01'),
(608, 2024, 110, 7, '7° básico', 'A', '23884944', '6', 'F', 'ISABELLA ANTONIA', 'GIANINI', 'SERRANO', 'VILLA LOS JAZMINES I / PJE 15', 'MELIPILLA', NULL, '85113548', '2012-02-26', '2024-03-01', '1900-01-01'),
(609, 2024, 110, 7, '7° básico', 'A', '23685718', '2', 'F', 'CARLA MONSERRAT', 'LÓPEZ', 'SUAZO', 'PASAJE EL TINEO 2085 ALTO CANTILLANA', 'MELIPILLA', NULL, '46148515', '2011-06-08', '2024-03-01', '1900-01-01'),
(610, 2024, 110, 7, '7° básico', 'A', '25215828', '6', 'F', 'NATHALIA SOPHIA', 'MEDINA', 'QUINTERO', NULL, 'MELIPILLA', NULL, '30537596', '2011-03-22', '2024-03-01', '1900-01-01'),
(611, 2024, 110, 7, '7° básico', 'A', '23803894', '4', 'F', 'KATHERINE GEORGINA', 'MEZA', 'VEAS', 'PSJE. A. LARRAIN 302 FLORENCIA I', 'MELIPILLA', NULL, '91379380', '2011-11-22', '2024-03-01', '1900-01-01'),
(612, 2024, 110, 7, '7° básico', 'A', '23634908', 'K', 'F', 'ANAÍS MONSERRATH', 'MUÑOZ', 'MARTÍNEZ', 'AV. CARLOS AVILES 371 LOS LAGOS II', 'MELIPILLA', NULL, '40246011', '2011-04-25', '2024-03-01', '1900-01-01'),
(613, 2024, 110, 7, '7° básico', 'A', '23893141', 'K', 'F', 'KRISHNA VAYTHIARE', 'MUÑOZ', 'SANTIBÁÑEZ', 'POBL.BENJAMIN ULLOA / PJE. ROSA AGUERO N°1904', 'MELIPILLA', NULL, '42310515', '2012-03-06', '2024-03-01', '1900-01-01'),
(614, 2024, 110, 7, '7° básico', 'A', '23726340', '5', 'F', 'MAITE ANDREA', 'NEGRETE', 'ALCAÍNO', 'C. DOLORES POZO 113 LOS JAZMINES NORTE', 'MELIPILLA', NULL, NULL, '2011-08-22', '2024-03-01', '1900-01-01'),
(615, 2024, 110, 7, '7° básico', 'A', '23740615', 'K', 'M', 'BENJAMÍN IGNACIO', 'OSSES', 'LAZO', 'ALUMCO 2538, LOS JAZMINES 3', 'MELIPILLA', NULL, '71797719', '2011-09-09', '2024-03-01', '1900-01-01'),
(616, 2024, 110, 7, '7° básico', 'A', '23875170', '5', 'F', 'JOSEFA NICOLE MONTSERRAT', 'PÉREZ', 'BUSTOS', 'CALLE ROBERTO MATTA LOS JAZMINES', 'MELIPILLA', NULL, '99275867', '2012-02-15', '2024-03-01', '1900-01-01'),
(617, 2024, 110, 7, '7° básico', 'A', '26518521', '5', 'F', 'DAYANA', 'PIERRE-LOUIS', NULL, NULL, 'MELIPILLA', NULL, '46186684', '2011-08-03', '2024-03-01', '1900-01-01'),
(618, 2024, 110, 7, '7° básico', 'A', '23684758', '6', 'F', 'SOFÍA ELIZABETH', 'PIÑA', 'ESPINOZA', 'POBL. BENJAMIN VICUÑA / RAUL SILVA H. 2101', 'MELIPILLA', NULL, '68211012', '2011-07-02', '2024-03-01', '1900-01-01'),
(619, 2024, 110, 7, '7° básico', 'A', '23746309', '9', 'M', 'SEBASTIAN ALEXANDER', 'PORRAS', 'CACERES', 'LAGO CHUNGARA 381 LOS LAGOS II', 'MELIPILLA', NULL, '72924732', '2011-09-13', '2024-03-01', '1900-01-01'),
(620, 2024, 110, 7, '7° básico', 'A', '23710289', '4', 'M', 'BRAULIO FRANCISCO', 'RAMOS', 'PALACIOS', 'LOMAS DE MANZO AVDA. CIRC. 1761', 'MELIPILLA', NULL, NULL, '2011-08-01', '2024-03-01', '1900-01-01'),
(621, 2024, 110, 7, '7° básico', 'A', '27143888', '5', 'F', 'FRANSHESKA OCEANIA', 'REYES', 'RODRIGUEZ', NULL, 'MELIPILLA', NULL, '85154942', '2011-08-31', '2024-03-01', '1900-01-01'),
(622, 2024, 110, 7, '7° básico', 'A', '23747094', 'K', 'M', 'GABRIEL VICTORINO', 'RIQUELME', 'TELLO', 'POBL. LOMAS DE MANSO AVDA. CIRC. 1751', 'MELIPILLA', NULL, '64443845', '2011-09-17', '2024-03-01', '1900-01-01'),
(623, 2024, 110, 7, '7° básico', 'A', '23721121', '9', 'F', 'MARTINA ANTONIA', 'CAMPOS', 'VÁSQUEZ', NULL, 'MELIPILLA', NULL, NULL, '2011-08-02', '2024-03-05', '1900-01-01'),
(624, 2024, 110, 7, '7° básico', 'A', '23679651', '5', 'M', 'FELIPE ANDRÉS', 'RODRÍGUEZ', 'GÓMEZ', NULL, 'MELIPILLA', NULL, NULL, '2011-06-27', '2024-03-01', '1900-01-01'),
(625, 2024, 110, 7, '7° básico', 'A', '23815386', '7', 'F', 'EVANGELINE ROSA', 'RUIDÍAZ', 'OLMEDO', 'V. FLORENCIA II ANA JARPA 2265', 'MELIPILLA', NULL, '6197576', '2011-12-09', '2024-03-01', '1900-01-01'),
(626, 2024, 110, 7, '7° básico', 'A', '23883582', '8', 'F', 'GABRIELA ISIDORA DEL CARMEN', 'RUIZ', 'SILVA', 'VILLA FLORENCIA II/ MANUEL MARIN', 'MELIPILLA', NULL, '91555617', '2012-02-23', '2024-03-01', '1900-01-01'),
(627, 2024, 110, 7, '7° básico', 'A', '23759201', '8', 'F', 'DOMINIQUE ALONDRA', 'SANTIBÁÑEZ', 'GALLARDO', 'CHOLQUI PARCELA 29 LOTE 3', 'MELIPILLA', NULL, '76077716', '2011-09-27', '2024-03-01', '1900-01-01'),
(628, 2024, 110, 7, '7° básico', 'A', '23711922', '3', 'M', 'JOSETOMÁS', 'SEPÚLVEDA', 'PACHECO', 'RODRIGO GONZALEZ 517 COLONIAL 1', 'MELIPILLA', NULL, NULL, '2011-08-06', '2024-03-01', '2024-08-05'),
(629, 2024, 110, 7, '7° básico', 'A', '23822003', '3', 'M', 'TOMÁS ANTONIO', 'URRUTIA', 'RUBINA', NULL, 'MELIPILLA', NULL, NULL, '2011-12-15', '2024-03-01', '1900-01-01'),
(630, 2024, 110, 7, '7° básico', 'A', '23744116', '8', 'F', 'MAURA NICOLE', 'VEGA', 'MEZA', NULL, 'MELIPILLA', NULL, NULL, '2011-09-13', '2024-03-01', '1900-01-01'),
(631, 2024, 110, 7, '7° básico', 'A', '23916308', '4', 'M', 'CARLOS ALEXIS', 'VERGARA', 'ALVARADO', 'LOS LAGOS II /AVDA. CARLOS AVILES', 'MELIPILLA', NULL, '76965113', '2012-04-03', '2024-03-01', '1900-01-01'),
(632, 2024, 110, 7, '7° básico', 'A', '100398725', '2', 'M', 'STEVEN SANG WOO', 'ZAVALETA', 'FLORES', 'LAGO PUYEHUE 550 POBL. LA FORESTA', 'MELIPILLA', NULL, '55664981', '2011-12-08', '2024-03-01', '2024-08-14'),
(633, 2024, 110, 7, '7° básico', 'A', '23826197', 'K', 'M', 'JAIME JAVIER', 'VILLALOBOS', 'PIÑA', 'MONSEÑOR LUIS BORREMANS POBL. BENJAMIN VICUÑA', 'MELIPILLA', NULL, '99704620', '2011-12-22', '2024-03-01', '1900-01-01'),
(634, 2024, 110, 7, '7° básico', 'A', '23409294', '4', 'M', 'BRYAN ALEXANDER JESÚS', 'AHUMADA', 'GATICA', 'LOS PEUMOS 138 LA FORESTA', 'MELIPILLA', NULL, '79147678', '2010-08-26', '2024-03-01', '1900-01-01'),
(635, 2024, 110, 7, '7° básico', 'A', '23655866', '5', 'F', 'DAYANARA VALENTINA', 'ARROYO', 'DURAN', NULL, 'MELIPILLA', NULL, NULL, '2011-05-30', '2024-03-01', '1900-01-01'),
(636, 2024, 110, 7, '7° básico', 'A', '23788177', 'K', 'M', 'MAXIMILIANO ZAID', 'ARTILLERÍA', 'GAVILÁN', 'ROBERTO MATTA 381 - ILUSIONES COMPARTIDAS', 'MELIPILLA', NULL, '93478598', '2011-11-03', '2024-03-01', '1900-01-01'),
(637, 2024, 110, 7, '7° básico', 'A', '23640357', '2', 'M', 'ALEJANDRO SEBASTIÁN', 'BUSTOS', 'CHÁVEZ', 'MONSEÑOR LUIS BORREMANS POBL. BENJAMIN VICUÑA', 'MELIPILLA', NULL, '33213617', '2011-05-09', '2024-03-01', '1900-01-01'),
(638, 2024, 110, 7, '7° básico', 'A', '23719016', '5', 'M', 'ALBERTO IGNACIO', 'BUSTOS', 'QUIJADA', 'POBL. CLOTARIO BLEST / AVENIDA 3 PONIENTE', 'MELIPILLA', NULL, '74288960', '2011-08-12', '2024-03-01', '1900-01-01'),
(639, 2024, 110, 7, '7° básico', 'A', '23786765', '3', 'M', 'VICENTE TOMÁS', 'CÁRDENAS', 'CERDA', 'SAN JOSE / LAS TORCAZAS S/N', 'MELIPILLA', NULL, '52850080', '2011-11-04', '2024-03-01', '1900-01-01'),
(640, 2024, 110, 7, '7° básico', 'A', '23570623', '7', 'F', 'ARACELI ANAÍS', 'CASTRO', 'ESPINOZA', 'POBL. PADRE DEMETRIO / JULIO MONTT', 'MELIPILLA', NULL, '63027490', '2011-02-23', '2024-03-01', '1900-01-01'),
(641, 2024, 110, 7, '7° básico', 'A', '23747757', 'K', 'F', 'AIMÉE ESPERANZA SALOMÉ', 'DANCHEFF', 'TOLEDO', 'VILLA MARTITA ROMANIN/ NATALIA', 'MELIPILLA', NULL, '27036978', '2011-09-13', '2024-03-01', '1900-01-01'),
(642, 2024, 110, 7, '7° básico', 'A', '23790930', '5', 'F', 'ANTONIA PAZ', 'DÍAZ', 'DONOSO', 'PUANGUE PARCELA N° 9', 'MELIPILLA', NULL, '83541127', '2011-11-08', '2024-03-01', '1900-01-01'),
(643, 2024, 110, 7, '7° básico', 'A', '23790912', '7', 'F', 'SOFÍA VICTORIA', 'DÍAZ', 'DONOSO', 'PUANGUE / PARCELA N° 9', 'MELIPILLA', NULL, '83541127', '2011-11-08', '2024-03-01', '1900-01-01'),
(644, 2024, 110, 7, '7° básico', 'A', '23810747', '4', 'F', 'SAMANTHA CATALINA', 'DURÁN', 'OVALLE', 'LOS COPIHUES 158 POBL. LA FORESTA', 'MELIPILLA', NULL, '98910833', '2011-12-01', '2024-03-01', '1900-01-01'),
(645, 2024, 110, 7, '7° básico', 'A', '23567993', '0', 'M', 'CRISTÓBAL ALONSO', 'TOLEDO', 'POLANCO', 'PUYEHUE BLOCK 512 DEPARTAMENTO B21', 'MELIPILLA', NULL, '95882032', '2011-02-17', '2024-03-11', '1900-01-01'),
(646, 2024, 110, 7, '7° básico', 'A', '23969200', '1', 'F', 'ZAHIRA MONSERRAT', 'ELGUETA', 'GAONA', 'V. COLONIAL II / PJE. ARMANDO CORTES 323', 'MELIPILLA', NULL, '71845547', '2012-06-01', '2024-03-01', '1900-01-01'),
(647, 2024, 110, 7, '7° básico', 'B', '23929733', '1', 'M', 'MAXIMILIANO ANDRÉS', 'ABARCA', 'FERNÁNDEZ', NULL, 'QUINTERO', NULL, '58725212', '2012-04-17', '2024-03-01', '1900-01-01'),
(648, 2024, 110, 7, '7° básico', 'B', '23660535', '3', 'M', 'AARON ESTEBAN', 'DABNER', 'ORELLANA', 'ALEJANDRO VIAL 7655', 'LA CISTERNA', NULL, '69010280', '2011-06-01', '2024-03-01', '1900-01-01'),
(649, 2024, 110, 7, '7° básico', 'B', '23855599', 'K', 'M', 'ALEXANDER EZEQUÍAS', 'MUÑOZ', 'GAJARDO', 'IGNAO 1225', 'MAIPÚ', NULL, '67014697', '2012-01-28', '2024-03-01', '1900-01-01'),
(650, 2024, 110, 7, '7° básico', 'B', '23283686', '5', 'M', 'ADÁN BENJAMÍN', 'RIQUELME', 'LOBOS', 'COOPERACION 6334', 'PEDRO AGUIRRE CERDA', NULL, NULL, '2010-03-23', '2024-03-01', '1900-01-01'),
(651, 2024, 110, 7, '7° básico', 'B', '23742233', '3', 'F', 'VALENTINA KATIUSCA', 'SEPÚLVEDA', 'ROCO', NULL, 'PUDAHUEL', NULL, NULL, '2011-09-10', '2024-03-01', '1900-01-01'),
(652, 2024, 110, 7, '7° básico', 'B', '23757874', '0', 'M', 'MANUEL ANTONIO', 'BETANCOURT', 'ROZAS', NULL, 'PUDAHUEL', NULL, NULL, '2011-09-28', '2024-03-01', '1900-01-01'),
(653, 2024, 110, 7, '7° básico', 'B', '23683708', '4', 'F', 'ANNAYS MONTSERRAT', 'MESÍAS', 'SANTIBÁÑEZ', 'PAPA JUAN XXIII POBL. PABLO LIZAMA', 'PUENTE ALTO', NULL, '65215999', '2011-07-03', '2024-03-01', '1900-01-01'),
(654, 2024, 110, 7, '7° básico', 'B', '23737193', '3', 'F', 'MAYLING ALEJANDRA', 'PEREZ', 'ROJAS', 'ESTACIÓN QUILACOYA 0855 DPTO. 23', 'PUENTE ALTO', NULL, '95693617', '2011-08-29', '2024-03-01', '1900-01-01'),
(655, 2024, 110, 7, '7° básico', 'B', '23798369', '6', 'F', 'DANAE ISABEL', 'DAZA', 'LEÓN', 'PAJE. LASTENIA ALVAREZ FLORENCIA I', 'MELIPILLA', NULL, '97843715', '2011-11-16', '2024-03-01', '1900-01-01'),
(656, 2024, 110, 7, '7° básico', 'B', '23685282', '2', 'M', 'JEREMY DAVID ISAAC', 'FLORES', 'CERÓN', 'OBISPO GUILLERMO VERA 2120 POBL. B. VICUÑA', 'MELIPILLA', NULL, '95052107', '2011-07-01', '2024-03-01', '1900-01-01'),
(657, 2024, 110, 7, '7° básico', 'B', '23902125', '5', 'M', 'CARLOS MARIO', 'FLORES', 'MIRANDA', 'PAJE. LLANQUIHUE 208 POBL. I. COMPARTIDAS', 'MELIPILLA', NULL, '86803193', '2012-03-16', '2024-03-01', '1900-01-01'),
(658, 2024, 110, 7, '7° básico', 'B', '23736229', '2', 'M', 'MARCELO ANDRÉS', 'GAETE', 'HERNÁNDEZ', 'CALLE 3 PONIENTE 552 RENACER II', 'MELIPILLA', NULL, '98993178', '2011-09-03', '2024-03-01', '1900-01-01'),
(659, 2024, 110, 7, '7° básico', 'B', '23363920', '6', 'M', 'EXEQUIEL ISMAEL', 'GARRIDO', 'CÉSPEDES', 'OBISPO G. VERA 2107 POBL. P. LIZAMA', 'MELIPILLA', NULL, '85079101', '2010-06-25', '2024-03-01', '1900-01-01'),
(660, 2024, 110, 7, '7° básico', 'B', '23633438', '4', 'M', 'LEANDRO SAÚL', 'GUAICO', 'MESA', NULL, 'MELIPILLA', NULL, NULL, '2011-04-30', '2024-03-01', '1900-01-01'),
(661, 2024, 110, 7, '7° básico', 'B', '23734679', '3', 'F', 'JOSEFA AGUSTINA', 'GUERRERO', 'YÁÑEZ', NULL, 'MELIPILLA', NULL, '99826037', '2011-08-29', '2024-03-01', '1900-01-01'),
(662, 2024, 110, 7, '7° básico', 'B', '23882528', '8', 'F', 'MONSERRAT ESTEFANÍA', 'GUZMÁN', 'RUMINAUS', 'POBL. LOS PEUMOS 150 LA FORESTA', 'MELIPILLA', NULL, '97810013', '2012-02-23', '2024-03-01', '1900-01-01'),
(663, 2024, 110, 7, '7° básico', 'B', '23792566', '1', 'F', 'ISABEL STEPHANIE', 'INOSTROZA', 'RUIZ', 'PASAJE 8 VILLA VICENTENARIO 2', 'MELIPILLA', NULL, '45293666', '2011-11-07', '2024-03-01', '1900-01-01'),
(664, 2024, 110, 7, '7° básico', 'B', '23637919', '1', 'M', 'NICOLAS ALONSO', 'MALDONADO', 'NEYRA', 'CHOLQUI PARCELA 29 LOTE 3', 'MELIPILLA', NULL, '97169230', '2011-05-04', '2024-03-01', '1900-01-01'),
(665, 2024, 110, 7, '7° básico', 'B', '23921871', '7', 'M', 'AGUSTIN ALONSO', 'NÚÑEZ', 'AVENDAÑO', 'P. LEYTON RAUL SILVA 302', 'MELIPILLA', NULL, '76146309', '2012-04-02', '2024-03-01', '1900-01-01'),
(666, 2024, 110, 7, '7° básico', 'B', '23940683', '1', 'F', 'RENATA AGUSTINA', 'ORTEGA', 'SEPÚLVEDA', 'AVDA. VICUÑA MACKENNA 2115', 'MELIPILLA', NULL, '86793194', '2012-05-01', '2024-03-01', '1900-01-01'),
(667, 2024, 110, 7, '7° básico', 'B', '23929392', '1', 'M', 'ALEJANDRO EMILIANO', 'OYARZÚN', 'BUSTOS', 'P. BENJAMIN ULLOA CARLOS AVILES 172', 'MELIPILLA', NULL, '74015160', '2012-04-17', '2024-03-01', '1900-01-01'),
(668, 2024, 110, 7, '7° básico', 'B', '23715095', '3', 'M', 'AXEL DAMIANN', 'PÉREZ', 'OLGUÍN', NULL, 'MELIPILLA', NULL, NULL, '2011-08-09', '2024-03-01', '1900-01-01'),
(669, 2024, 110, 7, '7° básico', 'B', '23906202', '4', 'M', 'LEONEL THOMAS', 'PIÑA', 'SÁNCHEZ', NULL, 'MELIPILLA', NULL, NULL, '2012-03-23', '2024-03-01', '1900-01-01'),
(670, 2024, 110, 7, '7° básico', 'B', '23930633', '0', 'M', 'ALONSO ANDRÉS', 'RAMÍREZ', 'RAMÍREZ', 'POBL. LOS LAGOS II PAS. 1769', 'MELIPILLA', NULL, NULL, '2012-04-18', '2024-03-01', '1900-01-01'),
(671, 2024, 110, 7, '7° básico', 'B', '23529021', '9', 'F', 'ANAÍS SOFÍA', 'RIVERO', 'LOBOS', NULL, 'MELIPILLA', NULL, '87774150', '2011-01-07', '2024-03-01', '1900-01-01'),
(672, 2024, 110, 7, '7° básico', 'B', '23713569', '5', 'M', 'VICENTE ALEJANDRO', 'SÁEZ', 'FUENTES', 'ALTOS DE CANTILLANA PSJE EL MAÑÍO', 'MELIPILLA', NULL, NULL, '2011-08-05', '2024-03-01', '1900-01-01'),
(673, 2024, 110, 7, '7° básico', 'B', '23689372', '3', 'M', 'ELISEO ANTONIO', 'SAN MARTÍN', 'ALBARRÁN', 'PASAJE EMILIO SÁNCHEZ SANDOVAL 366 - BENJAMÍN ULLOA', 'MELIPILLA', NULL, '67250211', '2011-07-08', '2024-03-01', '1900-01-01'),
(674, 2024, 110, 7, '7° básico', 'B', '23793331', '1', 'F', 'ALONDRA NOELIA', 'SÁNCHEZ', 'HERNÁNDEZ', 'PASAJE JOSÉ PEROTTI 367', 'MELIPILLA', NULL, '93039679', '2011-11-11', '2024-03-01', '1900-01-01'),
(675, 2024, 110, 7, '7° básico', 'B', '23686958', 'K', 'M', 'EDUARDO DANTE', 'SOTO', 'PULGAR', NULL, 'MELIPILLA', NULL, NULL, '2011-07-04', '2024-03-01', '1900-01-01'),
(676, 2024, 110, 7, '7° básico', 'B', '23687782', '5', 'F', 'SCARLETTE MONTSERRAT', 'VALENZUELA', 'CABRERA', NULL, 'MELIPILLA', NULL, '83093788', '2011-07-08', '2024-03-01', '1900-01-01'),
(677, 2024, 110, 7, '7° básico', 'B', '23665004', '9', 'F', 'RENATA SIMONEY', 'WESTWOOD', 'NÚÑEZ', 'PASCUAL ROMANINI 069 - LOS JAZMINES', 'MELIPILLA', NULL, '81470557', '2011-06-09', '2024-03-01', '1900-01-01'),
(678, 2024, 110, 7, '7° básico', 'B', '23559618', '0', 'M', 'DANTE IGNACIO', 'ACEVEDO', 'MARTÍNEZ', 'LASTENIA ALVAREZ', 'MELIPILLA', 'DANTE.ACEVEDO@MELIPILLAEDUCA.CL', '66867511', '2011-02-12', '2024-03-01', '1900-01-01'),
(679, 2024, 110, 7, '7° básico', 'B', '23836960', '6', 'F', 'VICTORIA ABIGAIL', 'CABELLO', 'NÚÑEZ', 'DOLORES POZO DE ROMANINI 053 - LOS JAZMINES III', 'MELIPILLA', NULL, '97500844', '2012-01-03', '2024-03-01', '1900-01-01'),
(680, 2024, 110, 7, '7° básico', 'B', '23414858', '3', 'M', 'ANDRÉS ERNESTO', 'CARO', 'PIZARRO', NULL, 'MELIPILLA', NULL, NULL, '2010-09-01', '2024-03-01', '1900-01-01'),
(681, 2024, 110, 7, '7° básico', 'B', '23806479', '1', 'M', 'NICOLÁS FRANCISCO ALEJANDRO', 'CASTRO', 'TORRES', 'GABRIELA MISTRAL 1595 DPTO. A-20 LOS POETAS', 'MELIPILLA', NULL, '61167862', '2011-11-14', '2024-03-01', '1900-01-01'),
(682, 2024, 110, 7, '7° básico', 'B', '25622017', '2', 'F', 'ABRIL KAORI', 'CAVERO', 'GUTIERREZ', 'VALDÉS 1448 DPTO. B- 13', 'MELIPILLA', NULL, '82612706', '2012-04-15', '2024-03-01', '1900-01-01'),
(683, 2024, 110, 7, '7° básico', 'B', '23723598', '3', 'M', 'VICENTE ARIEL', 'CÉSPEDES', 'GAVILÁN', NULL, 'MELIPILLA', NULL, '76515857', '2011-08-17', '2024-03-01', '1900-01-01'),
(684, 2024, 110, 7, '7° básico', 'B', '23688541', '0', 'M', 'FERNANDO DAGOBERTO', 'CIFUENTES', 'GODOY', 'SOR TERESA DE CALCUTA 2105', 'MELIPILLA', NULL, '53286792', '2011-07-07', '2024-03-01', '1900-01-01'),
(685, 2024, 110, 7, '7° básico', 'B', '23691479', '8', 'M', 'JOSÉ ALEXIS ALBERTO', 'MARDONES', 'ALMARZA', 'VILLA RENACER PASAJE JOSE SANTOS 453', 'MELIPILLA', NULL, NULL, '2011-07-12', '2024-03-01', '1900-01-01'),
(686, 2024, 110, 7, '7° básico', 'B', '23713082', '0', 'F', 'FRANCISCA XAVIERA', 'VEDIA', 'SANTIBÁÑEZ', 'MERCED 2070', 'MELIPILLA', NULL, '86199698', '2011-08-05', '2024-03-05', '1900-01-01'),
(687, 2024, 110, 7, '7° básico', 'B', '23711325', 'K', 'M', 'LEANDRO JOSÉ', 'VARGAS', 'GÁLVEZ', NULL, 'MELIPILLA', NULL, NULL, '2011-08-05', '2024-03-12', '2024-05-15'),
(688, 2024, 110, 8, '8° básico', 'A', '23483135', '6', 'F', 'KHAMILA ANTONIA', 'VENEGAS', 'MUÑOZ', 'MONSEÑOR JOSE RODRIGUEZ BLOCK 455 DPTO 104', 'CERRILLOS', NULL, '51701252', '2010-11-19', '2024-03-01', '1900-01-01'),
(689, 2024, 110, 8, '8° básico', 'A', '23445310', '6', 'F', 'MIRLENY AYSHA', 'HELLER', 'SERÓN', 'COLU 6054 DPTO. 22', 'ESTACIÓN CENTRAL', NULL, '56261087', '2010-10-08', '2024-03-01', '1900-01-01'),
(690, 2024, 110, 8, '8° básico', 'A', '23501100', 'K', 'M', 'CHRISTOFER DAICHI', 'MOLINA', 'SILVA', NULL, 'LA PINTANA', NULL, NULL, '2010-12-13', '2024-03-01', '1900-01-01'),
(691, 2024, 110, 8, '8° básico', 'A', '23478185', '5', 'F', 'LUNA VIOLETA', 'JIMÉNEZ', 'NILSSON', 'SAN ALBERTO', 'LO PRADO', 'CARMENNILSSON.80@GMAIL.COM', '71492861', '2010-11-11', '2024-03-01', '1900-01-01'),
(692, 2024, 110, 8, '8° básico', 'A', '23511859', '9', 'M', 'GASPAR ALONSO', 'PIZARRO', 'ESPINOZA', 'GUILLERMO GREVER 4388', 'RECOLETA', NULL, '97750372', '2010-12-14', '2024-03-01', '1900-01-01'),
(693, 2024, 110, 8, '8° básico', 'A', '27091917', '0', 'F', 'MELANIE SHOMARA', 'ORELLANA', 'CORPA', NULL, 'SAN JOAQUÍN', NULL, NULL, '2010-12-20', '2024-03-06', '1900-01-01'),
(694, 2024, 110, 8, '8° básico', 'A', '23533335', 'K', 'F', 'SOFÍA CONSTANZA', 'AGUILERA', 'QUINTANILLA', NULL, 'MELIPILLA', NULL, NULL, '2011-01-09', '2024-03-01', '1900-01-01'),
(695, 2024, 110, 8, '8° básico', 'A', '23483099', '6', 'F', 'FLOR ALEJANDRA', 'ÁLVAREZ', 'SILVA', 'LOS JAZMINES III, CALLE DOLORES POZO 101', 'MELIPILLA', NULL, '58547594', '2010-11-18', '2024-03-01', '1900-01-01'),
(696, 2024, 110, 8, '8° básico', 'A', '23569882', 'K', 'M', 'HÉCTOR JOSUE ALONSO', 'CÁCERES', 'GÓMEZ', NULL, 'MELIPILLA', NULL, '61642796', '2011-02-23', '2024-03-01', '1900-01-01'),
(697, 2024, 110, 8, '8° básico', 'A', '25806995', '1', 'M', 'ANGEL GABRIEL', 'FOSSI', 'CISNEROS', 'YULTON', 'MELIPILLA', NULL, '53119454', '2010-01-24', '2024-03-01', '1900-01-01'),
(698, 2024, 110, 8, '8° básico', 'A', '23576940', '9', 'F', 'JAVIERA EMILIA', 'GONZÁLEZ', 'QUIROGA', 'FLORENCIA II, PJE. BERTA GONZÁLEZ 2285', 'MELIPILLA', NULL, '94155210', '2011-02-23', '2024-03-01', '1900-01-01'),
(699, 2024, 110, 8, '8° básico', 'A', '23562298', 'K', 'M', 'JULIÁN ALONSO', 'GUTIÉRREZ', 'CONTRERAS', 'LOMAS DE MANZO 5 BLOCK 881 DPTO 104', 'MELIPILLA', NULL, NULL, '2011-02-16', '2024-03-01', '1900-01-01'),
(700, 2024, 110, 8, '8° básico', 'A', '23562277', '7', 'F', 'MAITE AGUSTINA', 'GUTIÉRREZ', 'CONTRERAS', 'LOMAS DE MANSO 5 CIRCUNVALACIÓN 1751 BOCK. 881 DPTO 104', 'MELIPILLA', NULL, NULL, '2011-02-16', '2024-03-01', '1900-01-01'),
(701, 2024, 110, 8, '8° básico', 'A', '23358639', '0', 'F', 'MARTINA ANTONELLA', 'GUZMÁN', 'CARRASCO', 'LOS JAZMINES, PJE. JOSÉ ARTURO PACHECO 108', 'MELIPILLA', NULL, '56555469', '2010-06-29', '2024-03-01', '1900-01-01'),
(702, 2024, 110, 8, '8° básico', 'A', '23532457', '1', 'M', 'IGNACIO ALONSO', 'JULIO', 'AMPUERO', 'PAJARITOS 1429 BELLA ESPERANZA', 'MELIPILLA', NULL, NULL, '2011-01-13', '2024-03-01', '1900-01-01'),
(703, 2024, 110, 8, '8° básico', 'A', '23605722', '4', 'F', 'CONSTANZA ISIDORA', 'LÓPEZ', 'MANCILLA', 'SOLIDARIDAD, PJE. JOSÉ PERROLLI N° 119', 'MELIPILLA', NULL, '74028106', '2011-03-27', '2024-03-01', '1900-01-01'),
(704, 2024, 110, 8, '8° básico', 'A', '23610927', '5', 'M', 'DYLAN GABRIEL', 'LOYOLA', 'NAVARRO', 'B. VICUÑA. PJE. RAMON NUÑEZ 31', 'MELIPILLA', NULL, '95459986', '2011-03-15', '2024-03-01', '1900-01-01'),
(705, 2024, 110, 8, '8° básico', 'A', '23409003', '8', 'F', 'IGNACIA BELÉN', 'MARAMBIO', 'CÉSPEDES', 'VILLA FLORENCIA I PSAJE LAZARO CARREÑO 667', 'MELIPILLA', 'ESCUELITASANTAINESDELAPAZ@GMAIL.COM', '73163416', '2010-08-23', '2024-03-01', '1900-01-01'),
(706, 2024, 110, 8, '8° básico', 'A', '23382436', '4', 'M', 'VICTORINO MAXIMILIANO', 'OLGUÍN', 'PÉREZ', NULL, 'MELIPILLA', NULL, NULL, '2010-07-26', '2024-03-01', '1900-01-01'),
(707, 2024, 110, 8, '8° básico', 'A', '23606362', '3', 'F', 'CATALINA ANTONELLA', 'ORTEGA', 'LEÓN', NULL, 'MELIPILLA', NULL, '61838725', '2011-03-29', '2024-03-01', '1900-01-01'),
(708, 2024, 110, 8, '8° básico', 'A', '23606346', '1', 'F', 'FERNANDA MONSERRAT', 'ORTEGA', 'LEÓN', NULL, 'MELIPILLA', NULL, '61838725', '2011-03-29', '2024-03-01', '1900-01-01'),
(709, 2024, 110, 8, '8° básico', 'A', '23396841', '2', 'F', 'LISETTE FERNANDA', 'PAVEZ', 'ESPINA', 'ROBERTO MATTA Nº 344 POBL. ILUSIONES COMPARTIDAS', 'MELIPILLA', 'ESCUELASANCARLOS2013@GMAIL.COM', '89087225', '2010-08-13', '2024-03-01', '1900-01-01'),
(710, 2024, 110, 8, '8° básico', 'A', '23663606', '2', 'F', 'MARTINA ISIDORA', 'PINTO', 'GONZÁLEZ', 'JUAN FCO GONZALEZ 313 ILUSIONES COMPARTIDAS', 'MELIPILLA', NULL, '97196357', '2011-06-07', '2024-03-01', '1900-01-01'),
(711, 2024, 110, 8, '8° básico', 'A', '23618395', '5', 'F', 'AURORA ANTONIA', 'POLANCO', 'GONZÁLEZ', 'LOMAS DE MANZO, BLOCK 916-102', 'MELIPILLA', NULL, '77228956', '2011-03-30', '2024-03-01', '1900-01-01'),
(712, 2024, 110, 8, '8° básico', 'A', '23437421', '4', 'F', 'ANTONIA SCARLETT', 'SÁNCHEZ', 'IBÁÑEZ', 'COLONIAL II, PJE. VALENTIN SILVA 315', 'MELIPILLA', NULL, '96586331', '2010-09-29', '2024-03-01', '1900-01-01'),
(713, 2024, 110, 8, '8° básico', 'A', '23520337', '5', 'F', 'JAVIERA CONSTANZA', 'CASTRO', 'PASCAL', 'BENJAMÍN ULLOA, PJE. ED. CASTAÑEDA 81', 'MELIPILLA', NULL, '9648523', '2011-01-06', '2024-03-01', '1900-01-01'),
(714, 2024, 110, 8, '8° básico', 'A', '23609944', 'K', 'F', 'DOMINIQUE MADELAINE', 'CERDA', 'LOYOLA', 'ALTOS DE CANTILLANA, PJE. NIRE 2113', 'MELIPILLA', NULL, NULL, '2011-04-02', '2024-03-01', '1900-01-01'),
(715, 2024, 110, 8, '8° básico', 'A', '23627203', '6', 'F', 'MAITE MARTINA SCARLETH', 'CORDERO', 'MORALES', 'LOMAS DE MANZO II, DP 303, BLOCK 812', 'MELIPILLA', NULL, '8240469', '2011-04-19', '2024-03-01', '1900-01-01'),
(716, 2024, 110, 8, '8° básico', 'A', '23610388', '9', 'M', 'JOAQUÍN IGNACIO', 'CÓRDOVA', 'LOBOS', NULL, 'MELIPILLA', NULL, '77378110', '2011-04-03', '2024-03-01', '1900-01-01'),
(717, 2024, 110, 8, '8° básico', 'A', '23395501', '9', 'M', 'PEDRO JESÚS', 'FARÍAS', 'MUÑOZ', NULL, 'MELIPILLA', NULL, NULL, '2010-08-02', '2024-03-01', '1900-01-01'),
(718, 2024, 110, 8, '8° básico', 'A', '23574385', 'K', 'F', 'YULIETTE ALEXIA', 'FIGUEROA', 'GUZMÁN', NULL, 'MELIPILLA', NULL, NULL, '2011-02-15', '2024-03-01', '1900-01-01'),
(719, 2024, 110, 8, '8° básico', 'A', '23458083', '3', 'F', 'ISIS FLORENCIA', 'VERA', 'REYES', 'VILLA EL ÁLAMO, LOS SAUCES 1737', 'MELIPILLA', NULL, '85254004', '2010-10-22', '2024-03-01', '1900-01-01'),
(720, 2024, 110, 8, '8° básico', 'A', '23407950', '6', 'F', 'MONTSERRAT DENISSE', 'VERDEJO', 'GORMAZ', 'FLORENCIA I, PJE. LASTENIA ÁLVAREZ 2246', 'MELIPILLA', NULL, '90007792', '2010-08-29', '2024-03-01', '1900-01-01'),
(721, 2024, 110, 8, '8° básico', 'A', '23376624', '0', 'M', 'ARNALDO ANTONIO', 'VIDAL', 'RIQUELME', 'RAFAEL MORANDÉ PSJE JUANA ALVAREZ', 'MELIPILLA', NULL, '78853349', '2010-07-21', '2024-03-01', '1900-01-01'),
(722, 2024, 110, 8, '8° básico', 'A', '23555818', '1', 'F', 'JOSEFINA IGNACIA', 'YAÑEZ', 'ORTEGA', NULL, 'MELIPILLA', NULL, NULL, '2011-02-06', '2024-03-01', '1900-01-01'),
(723, 2024, 110, 8, '8° básico', 'A', '23375205', '3', 'F', 'PAZ ISIDORA', 'ZAMORA', 'ARTIGAS', 'LOMAS DE MANSO 6 BLOCK 930 DTO. 304', 'MELIPILLA', NULL, '85509675', '2010-07-15', '2024-03-01', '1900-01-01'),
(724, 2024, 110, 8, '8° básico', 'A', '23608441', '8', 'F', 'TAMARA VICTORIA', 'AHUMADA', 'CIFUENTES', 'LAS ARAUCARIAS 1304 SANTA LAURA', 'MELIPILLA', NULL, NULL, '2011-03-29', '2024-03-05', '1900-01-01'),
(725, 2024, 110, 8, '8° básico', 'A', '23427541', '0', 'F', 'NICOL AILIN', 'LAGOS', 'GARAY', 'CULENCO', 'PEMUCO', NULL, NULL, '2010-09-18', '2024-03-01', '1900-01-01'),
(726, 2024, 110, 8, '8° básico', 'B', '22970315', '3', 'F', 'EMELY ANTONIA', 'CARRASCO', 'GUZMÁN', 'SANTA INÉS', 'LAS CABRAS', 'MOMAN_L_@HOTMAIL.COM', '32965554', '2009-03-06', '2024-03-01', '1900-01-01'),
(727, 2024, 110, 8, '8° básico', 'B', '23429470', '9', 'M', 'CRISTÓBAL SAÚL', 'MOYA', 'MUÑOZ', NULL, 'CHIGUAYANTE', NULL, NULL, '2010-09-15', '2024-03-01', '1900-01-01'),
(728, 2024, 110, 8, '8° básico', 'B', '23544384', '8', 'F', 'MARTINA IGNACIA', 'CÁCERES', 'ORTIZ', NULL, 'EL BOSQUE', NULL, NULL, '2011-01-13', '2024-03-01', '1900-01-01'),
(729, 2024, 110, 8, '8° básico', 'B', '23511183', '7', 'M', 'CARLOS JULIÁN', 'CAMPOS', 'COFRÉ', 'LAS FICAS 6437 LA HIGUERA', 'LA FLORIDA', NULL, '97960360', '2010-12-13', '2024-03-01', '1900-01-01'),
(730, 2024, 110, 8, '8° básico', 'B', '23504685', '7', 'F', 'JOSEFA SOFÍA LUCIANA', 'ARENAS', 'MILLAPÁN', 'LAGO VILLARRICA 6951', 'PEÑALOLÉN', NULL, '90948276', '2010-12-14', '2024-03-01', '1900-01-01'),
(731, 2024, 110, 8, '8° básico', 'B', '23491551', '7', 'M', 'JAVIER DE JESÚS', 'ÁVILA', 'BOBADILLA', NULL, 'PUDAHUEL', NULL, NULL, '2010-11-29', '2024-03-01', '1900-01-01'),
(732, 2024, 110, 8, '8° básico', 'B', '23376944', '4', 'M', 'LUCAS DANIEL', 'MARTÍNEZ', 'ROCO', NULL, 'PUDAHUEL', NULL, NULL, '2010-07-20', '2024-03-01', '1900-01-01'),
(733, 2024, 110, 8, '8° básico', 'B', '23557027', '0', 'F', 'MONSERRAT ANTONIA', 'AYALA', 'NEGRETE', 'SANTA LAURA, POLICARPO TORO 193', 'MELIPILLA', NULL, '96242819', '2011-02-05', '2024-03-01', '1900-01-01'),
(734, 2024, 110, 8, '8° básico', 'B', '23390075', '3', 'M', 'MANUEL JESÚS', 'CANDIA', 'CORREA', 'ROSA AHUERO 1915 BENJAMIN ULLOA', 'MELIPILLA', NULL, NULL, '2010-08-01', '2024-03-01', '1900-01-01'),
(735, 2024, 110, 8, '8° básico', 'B', '27765470', '9', 'F', 'MARIA CAMILA', 'CANO', 'LASSO', NULL, 'MELIPILLA', NULL, '63038326', '2010-07-23', '2024-03-01', '1900-01-01'),
(736, 2024, 110, 8, '8° básico', 'B', '23504217', '7', 'M', 'MATÍAS NIKOLÁS', 'CENTONCIO', 'ORTEGA', 'POBL. CLOTARIO BLEST / AVENIDA 3 PONIENTE', 'MELIPILLA', NULL, '88033005', '2010-12-10', '2024-03-01', '1900-01-01'),
(737, 2024, 110, 8, '8° básico', 'B', '23370047', '9', 'F', 'PAZ LASKAY', 'CUEVAS', 'SERRANO', NULL, 'MELIPILLA', NULL, NULL, '2010-07-07', '2024-03-01', '1900-01-01'),
(738, 2024, 110, 8, '8° básico', 'B', '23478176', '6', 'F', 'MONSERRAT ARIADNA', 'CURILLÁN', 'VENEGAS', 'BOLLENAR', 'MELIPILLA', NULL, NULL, '2010-11-11', '2024-03-01', '1900-01-01'),
(739, 2024, 110, 8, '8° básico', 'B', '23560376', '4', 'F', 'AMANDA JESÚS', 'ESPINOZA', 'PADILLA', NULL, 'MELIPILLA', NULL, NULL, '2011-02-15', '2024-03-01', '1900-01-01'),
(740, 2024, 110, 8, '8° básico', 'B', '23627739', '9', 'F', 'AMANDA ISABELLA', 'FARÍAS', 'GALLEGUILLOS', NULL, 'MELIPILLA', NULL, NULL, '2011-04-19', '2024-03-01', '1900-01-01'),
(741, 2024, 110, 8, '8° básico', 'B', '26556980', '3', 'F', 'NATALIA SOPHIA', 'GUERRERO', 'GUZMAN', NULL, 'MELIPILLA', NULL, '35832629', '2009-12-02', '2024-03-01', '1900-01-01'),
(742, 2024, 110, 8, '8° básico', 'B', '23389514', '8', 'F', 'CATALINA DE LOS ANGELES', 'HERNÁNDEZ', 'ESPINOZA', 'PARCELA 1 ESMERALDA', 'MELIPILLA', NULL, '84655533', '2010-07-26', '2024-03-01', '1900-01-01'),
(743, 2024, 110, 8, '8° básico', 'B', '23367755', '8', 'F', 'EMA MARÍA', 'HERNÁNDEZ', 'LOBOS', NULL, 'MELIPILLA', NULL, NULL, '2010-07-06', '2024-03-01', '1900-01-01'),
(744, 2024, 110, 8, '8° básico', 'B', '23424762', 'K', 'M', 'VICENTE MANUEL', 'IBARRA', 'CÁCERES', NULL, 'MELIPILLA', NULL, NULL, '2010-09-15', '2024-03-01', '1900-01-01'),
(745, 2024, 110, 8, '8° básico', 'B', '23482589', '5', 'F', 'LISSETH SOLANGE', 'IRRAZABAL', 'PEZOA', NULL, 'MELIPILLA', NULL, '85893550', '2010-11-17', '2024-03-01', '1900-01-01'),
(746, 2024, 110, 8, '8° básico', 'B', '23127132', '5', 'F', 'GIULIANA FRANCISCA', 'JORQUERA', 'CORNEJO', NULL, 'MELIPILLA', NULL, NULL, '2009-09-21', '2024-03-01', '1900-01-01'),
(747, 2024, 110, 8, '8° básico', 'B', '23458192', '9', 'M', 'BENJAMÍN ANDRÉS', 'LOYOLA', 'FLORES', 'PASAJE LLANQUIHUE Nº 208 LOS LAGOS I', 'MELIPILLA', 'ESCUELASANCARLOS2013@GMAIL.COM', '81787813', '2010-10-21', '2024-03-01', '1900-01-01'),
(748, 2024, 110, 8, '8° básico', 'B', '23380212', '3', 'M', 'TOMÁS SALVADOR', 'MALLEA', 'MIRANDA', 'OBISPO LIZANA RAMON NUÑEZ 24', 'MELIPILLA', 'ESCUELITASANTAINESDELAPAZ@GMAIL.COM', '75421346', '2010-07-24', '2024-03-01', '1900-01-01'),
(749, 2024, 110, 8, '8° básico', 'B', '23073790', '8', 'F', 'ANAHY BETSABETH', 'MARIMÁN', 'MUÑOZ', 'ROLANDO NUÑEZ N° 28 BENJAMIN VICUÑA', 'MELIPILLA', NULL, NULL, '2009-07-15', '2024-03-01', '1900-01-01'),
(750, 2024, 110, 8, '8° básico', 'B', '23227487', '5', 'F', 'DANIELA ISIDORA', 'MATELUNA', 'VELÁSQUEZ', 'POBL. MEDIALUNA / ANDRÉS BELLO', 'MELIPILLA', NULL, '76301361', '2010-01-18', '2024-03-01', '1900-01-01'),
(751, 2024, 110, 8, '8° básico', 'B', '23468797', '2', 'F', 'MONSERRAT ANAÍS', 'MATURANA', 'QUIROGA', NULL, 'MELIPILLA', NULL, NULL, '2010-11-02', '2024-03-01', '1900-01-01'),
(752, 2024, 110, 8, '8° básico', 'B', '23468779', '4', 'F', 'VALENTINA FERNANDA', 'MATURANA', 'QUIROGA', NULL, 'MELIPILLA', NULL, NULL, '2010-11-02', '2024-03-01', '1900-01-01'),
(753, 2024, 110, 8, '8° básico', 'B', '23428352', '9', 'M', 'PABLO AGUSTÍN', 'MUÑOZ', 'VERA', NULL, 'MELIPILLA', NULL, NULL, '2010-09-18', '2024-03-01', '1900-01-01'),
(754, 2024, 110, 8, '8° básico', 'B', '23396509', 'K', 'F', 'BRENDA MARINA', 'OSORIO', 'NORAMBUENA', 'FLORENCIA II, PJE. RAQUEL AVENDAÑO 2226', 'MELIPILLA', NULL, '96477506', '2010-08-10', '2024-03-01', '1900-01-01'),
(755, 2024, 110, 8, '8° básico', 'B', '23442570', '6', 'F', 'EMILY AILEEN', 'PALMA', 'URETA', 'POBL BELLA ESPERANZA PSJE ANA FUENTES 1781', 'MELIPILLA', NULL, '83278936', '2010-10-05', '2024-03-01', '1900-01-01'),
(756, 2024, 110, 8, '8° básico', 'B', '23397966', 'K', 'F', 'SIMONEY ANTONELLA', 'PINTO', 'GUERRA', 'LOS LAGOS II, LAGO CHAPO 1815', 'MELIPILLA', NULL, '72872448', '2010-08-13', '2024-03-01', '1900-01-01'),
(757, 2024, 110, 8, '8° básico', 'B', '23609557', '6', 'M', 'CRISTÓBAL ANTONIO', 'RIVEROS', 'MONDACA', 'ILUSIONES COMPARTIDAS PSJE. JOSÉ P. 333', 'MELIPILLA', NULL, '90246516', '2011-04-01', '2024-03-01', '1900-01-01'),
(758, 2024, 110, 8, '8° básico', 'B', '23495500', '4', 'F', 'MEILYNG HYUNSU', 'ABARCA', 'RAMÍREZ', 'FLORENCIA 1 PJE. LEJANDRO GONZALEZ 2243', 'MELIPILLA', NULL, '61728883', '2010-12-05', '2024-03-01', '1900-01-01'),
(759, 2024, 110, 8, '8° básico', 'B', '23679307', '9', 'M', 'EDMUNDO JAVIER', 'ARISMENDI', 'ARISMENDI', 'CULIPRAN,LOS MOLLES 140', 'MELIPILLA', NULL, '93657342', '2011-06-23', '2024-03-01', '1900-01-01'),
(760, 2024, 110, 8, '8° básico', 'B', '23352366', '6', 'M', 'MATÍAS ALONSO', 'SOTO', 'CABRERA', NULL, 'MELIPILLA', NULL, '85030167', '2010-06-17', '2024-03-01', '1900-01-01'),
(761, 2024, 110, 8, '8° básico', 'B', '23478266', '5', 'M', 'BENJAMÍN ALFONSO', 'TORRES', 'VIDAL', NULL, 'MELIPILLA', NULL, '50155388', '2010-11-10', '2024-03-01', '1900-01-01'),
(762, 2024, 110, 8, '8° básico', 'B', '23367572', '5', 'F', 'ESPERANZA BEATRIZ', 'VALENZUELA', 'ELGUETA', 'LOS ALTOS DE CANTILLANA 3, LAS PATAGUAS 2054', 'MELIPILLA', NULL, '59481633', '2010-06-16', '2024-03-01', '1900-01-01'),
(763, 2024, 110, 8, '8° básico', 'B', '100440654', '7', 'M', 'MOISES ALEJANDRO', 'VASQUEZ', 'GARCIA', NULL, 'MELIPILLA', NULL, NULL, '2010-03-03', '2024-03-01', '1900-01-01'),
(764, 2024, 110, 8, '8° básico', 'B', '23608031', '5', 'F', 'SARA MARTINA', 'VIDAL', 'DÍAZ', 'POBL. LOS POETAS/ GABRIELA MISTRAL', 'MELIPILLA', NULL, '72861042', '2011-03-30', '2024-03-01', '1900-01-01'),
(765, 2024, 110, 8, '8° básico', 'B', '23400853', '6', 'F', 'ANTONELA ISABEL', 'ZÚÑIGA', 'BAEZA', 'P. LEYTON PJE. RAFAEL MORANDE 326', 'MELIPILLA', NULL, '79394265', '2010-08-17', '2024-03-01', '1900-01-01'),
(766, 2024, 110, 8, '8° básico', 'B', '100716393', '9', 'M', 'MAXIMILIANO', 'FERNANDEZ', 'CORSO', NULL, 'MELIPILLA', NULL, NULL, '2010-10-12', '2024-03-01', '1900-01-01'),
(767, 2024, 310, 1, '1° medio', 'A', '23145399', '7', 'M', 'IGNACIO ANDRÉS', 'HUAIQUIL', 'ARANEDA', NULL, 'COQUIMBO', NULL, NULL, '2009-10-11', '2024-03-01', '1900-01-01'),
(768, 2024, 310, 1, '1° medio', 'A', '22990277', '6', 'M', 'AMIR JESÚS', 'MÉNDEZ', 'ARAVENA', 'BLANCA', 'LA FLORIDA', NULL, '56422691', '2009-04-06', '2024-03-01', '1900-01-01'),
(769, 2024, 310, 1, '1° medio', 'A', '22975923', 'K', 'M', 'MARTÍN SALVADOR', 'RAMÍREZ', 'CAYUQUEO', 'CAMIMO DE LOYOLA 5506', 'LO PRADO', NULL, '50330784', '2009-03-19', '2024-03-01', '1900-01-01'),
(770, 2024, 310, 1, '1° medio', 'A', '23225302', '9', 'M', 'JEAN LUCAS', 'PIZARRO', 'BALMACEDA', 'PASAJE CAPRICORNIO 203', 'PUDAHUEL', NULL, NULL, '2010-01-14', '2024-03-01', '1900-01-01'),
(771, 2024, 310, 1, '1° medio', 'A', '22872415', '7', 'M', 'AGUSTÍN IGNACIO', 'CORDERO', 'VERDUGO', NULL, 'RENCA', NULL, NULL, '2008-11-18', '2024-03-05', '2024-03-18'),
(772, 2024, 310, 1, '1° medio', 'A', '23217571', '0', 'F', 'SCARLET BELÉN', 'MUÑOZ', 'GAJARDO', 'IGNAO', 'SAN BERNARDO', NULL, '50742040', '2010-01-06', '2024-03-01', '1900-01-01'),
(773, 2024, 310, 1, '1° medio', 'A', '23207572', '4', 'F', 'MARTINA BELÉN', 'CANALES', 'SOBARZO', NULL, 'SAN BERNARDO', NULL, NULL, '2009-12-22', '2024-03-01', '1900-01-01');
INSERT INTO `matricula` (`id`, `ano`, `cod_tipo_ensenanza`, `cod_grado`, `desc_grado`, `letra_curso`, `run`, `digito_ver`, `genero`, `nombres`, `apellido_paterno`, `apellido_materno`, `direccion`, `comuna_residencia`, `email`, `telefono`, `fecha_nacimiento`, `fecha_incorporacion_curso`, `fecha_retiro`) VALUES
(774, 2024, 310, 1, '1° medio', 'A', '23253440', '0', 'F', 'ISIDORA ANDREA', 'GAETE', 'HERNÁNDEZ', 'POBL. RENACER II / TRES PONIENTE', 'MELIPILLA', NULL, '75910309', '2010-02-19', '2024-03-01', '1900-01-01'),
(775, 2024, 310, 1, '1° medio', 'A', '22916595', 'K', 'M', 'CHRISTOPHER ANTONIO', 'FARÍAS', 'MUÑOZ', 'RANCO 265', 'MELIPILLA', NULL, NULL, '2008-12-21', '2024-03-01', '1900-01-01'),
(776, 2024, 310, 1, '1° medio', 'A', '23126023', '4', 'F', 'ISIS BIANCA', 'FRELIJJ', 'VEGA', 'POBL. LOS POETAS / CARLOS AVILES BLOCK', 'MELIPILLA', NULL, NULL, '2009-09-13', '2024-03-01', '1900-01-01'),
(777, 2024, 310, 1, '1° medio', 'A', '22905657', '3', 'F', 'ANAÍS ESTRELLA', 'GARRIDO', 'CASTRO', 'POBL.PABLO LIZAMA/ PAPA JUAN XXIII', 'MELIPILLA', NULL, '97229994', '2008-12-17', '2024-03-01', '1900-01-01'),
(778, 2024, 310, 1, '1° medio', 'A', '23091811', '2', 'F', 'ANTONELLA FRANCISCA', 'GONZÁLEZ', 'GONZÁLEZ', 'POBL. LA FORESTA / LOS PEUMOS', 'MELIPILLA', NULL, NULL, '2009-08-04', '2024-03-01', '1900-01-01'),
(779, 2024, 310, 1, '1° medio', 'A', '23218319', '5', 'M', 'VÍCTOR ANTONIO', 'LANDEROS', 'DONOSO', NULL, 'MELIPILLA', NULL, NULL, '2010-01-04', '2024-03-01', '1900-01-01'),
(780, 2024, 310, 1, '1° medio', 'A', '23323153', '3', 'F', 'VANESSA ANDREA', 'OJEDA', 'BERMÚDEZ', 'POBL. LOS JAZMINES NORTE I / PELLIN', 'MELIPILLA', NULL, NULL, '2010-05-17', '2024-03-01', '1900-01-01'),
(781, 2024, 310, 1, '1° medio', 'A', '23066487', '0', 'F', 'GENESIS MONSERRAT', 'QUIROZ', 'CASTRO', 'HURTADO 111', 'MELIPILLA', NULL, NULL, '2009-06-23', '2024-03-01', '1900-01-01'),
(782, 2024, 310, 1, '1° medio', 'A', '23314486', 'K', 'F', 'ANTONELLA MONSERRAT', 'RODRÍGUEZ', 'FUENTES', 'JOSE SANTOS ROJAS Nº 500', 'MELIPILLA', 'ESCUELASANCARLOS2013@GMAIL.COM', '79601602', '2010-05-07', '2024-03-01', '1900-01-01'),
(783, 2024, 310, 1, '1° medio', 'A', '23073959', '5', 'M', 'ALEXANDER JESÚS', 'SALAS', 'FUENZALIDA', 'PJE MEULLIN 1641 LOS LAGOS 3', 'MELIPILLA', NULL, '96513696', '2009-07-15', '2024-03-01', '1900-01-01'),
(784, 2024, 310, 1, '1° medio', 'A', '23320667', '9', 'F', 'MARTINA CONSTANZA ANTONELLA', 'SEPÚLVEDA', 'OYARCE', 'POBL. BENJAMIN ULLOA / MARIO CHUESCAS', 'MELIPILLA', NULL, NULL, '2010-05-12', '2024-03-01', '2024-08-02'),
(785, 2024, 310, 1, '1° medio', 'A', '23140761', '8', 'F', 'FERNANDA GISSELLE', 'SOTO', 'VILLAVICENCIO', NULL, 'MELIPILLA', NULL, NULL, '2009-09-26', '2024-03-01', '1900-01-01'),
(786, 2024, 310, 1, '1° medio', 'A', '23185768', '0', 'M', 'MATEO ENRIQUE', 'ABARCA', 'SILVA', NULL, 'MELIPILLA', NULL, NULL, '2009-10-11', '2024-03-01', '1900-01-01'),
(787, 2024, 310, 1, '1° medio', 'A', '23127725', '0', 'F', 'ALISON DENISSE', 'AHUMADA', 'VARGAS', 'POBL. SOLIDARIDAD Y ESFUERZO / ROBERTO MATTA', 'MELIPILLA', NULL, '99827879', '2009-09-21', '2024-03-01', '1900-01-01'),
(788, 2024, 310, 1, '1° medio', 'A', '23084297', '3', 'M', 'SEBASTIAN CLAUDIO IGNACIO', 'CARO', 'PÉREZ', NULL, 'MELIPILLA', NULL, NULL, '2009-07-29', '2024-03-01', '1900-01-01'),
(789, 2024, 310, 1, '1° medio', 'A', '22821113', '3', 'M', 'AARON DAMIÁN', 'ESPINOZA', 'TAPIA', NULL, 'MELIPILLA', NULL, NULL, '2008-09-12', '2024-03-01', '2024-03-11'),
(790, 2024, 310, 1, '1° medio', 'A', '23072379', '6', 'M', 'CRISTÓBAL ALONSO', 'ABARCA', 'ULLOA', 'POBLACION CHILE CALLE MANUEL RODRÍGUEZ 711', 'MELIPILLA', NULL, NULL, '2009-07-13', '2024-03-01', '1900-01-01'),
(791, 2024, 310, 1, '1° medio', 'A', '22862462', '4', 'M', 'GERALD GUILLERMO', 'GUZMÁN', 'AGUIRRE', 'SAN MANUEL', 'MELIPILLA', NULL, '78023899', '2008-11-05', '2024-03-01', '1900-01-01'),
(792, 2024, 310, 1, '1° medio', 'A', '23021336', '4', 'M', 'MARTÍN ALEJANDRO', 'LOYOLA', 'LOYOLA', NULL, 'MELIPILLA', 'ISABEL27LOKILLA@GMAIL.COM', NULL, '2009-05-14', '2024-03-01', '1900-01-01'),
(793, 2024, 310, 1, '1° medio', 'A', '23000118', '9', 'M', 'BENJAMÍN IGNACIO', 'BAU', 'CRUZ', 'VILLA RENACER PASAJE ROBERTO HERNANDEZ 2441', 'MELIPILLA', NULL, '41702231', '2009-04-12', '2024-03-01', '1900-01-01'),
(794, 2024, 310, 1, '1° medio', 'A', '23251154', '0', 'M', 'GABRIEL MISAEL', 'BUSTOS', 'OLGUÍN', NULL, 'MELIPILLA', NULL, NULL, '2010-02-11', '2024-03-01', '1900-01-01'),
(795, 2024, 310, 1, '1° medio', 'A', '22812695', '0', 'M', 'BENJAMÍN RENATO', 'MESA', 'SANTANDER', 'VILLA COLONIAL 2 AVDA LIBERTAD 2106', 'MELIPILLA', NULL, '42334772', '2008-09-06', '2024-03-01', '1900-01-01'),
(796, 2024, 310, 1, '1° medio', 'A', '22437715', '0', 'M', 'MARTIN ARTURO', 'GALLARDO', 'VERGARA', 'POBLACIÓN 12 DE OCTUBRE.PASAJE ESMERALDA 927', 'MELIPILLA', NULL, '94805875', '2007-07-04', '2024-03-01', '1900-01-01'),
(797, 2024, 310, 1, '1° medio', 'A', '23298327', '2', 'F', 'JADE SCARLETT PASCALE', 'MARDONES', 'ALMARZA', 'VILLA RENACER PASAJE JOSE SANTOS 456', 'MELIPILLA', NULL, '47961814', '2010-04-11', '2024-03-01', '1900-01-01'),
(798, 2024, 310, 1, '1° medio', 'A', '26112159', 'K', 'F', 'SAIKA', 'GEORGES', NULL, NULL, 'MELIPILLA', NULL, NULL, '2010-02-05', '2024-03-01', '1900-01-01'),
(799, 2024, 310, 1, '1° medio', 'A', '23029663', '4', 'F', 'YARITZA KASSANDRA', 'OLIVERA', 'LÓPEZ', 'TODOS LOS SANTOS 1683 LOS LAGOS I', 'MELIPILLA', 'LOPEZPINTOCLAUDIA5@GMAIL.COM', NULL, '2009-05-29', '2024-03-01', '1900-01-01'),
(800, 2024, 310, 1, '1° medio', 'A', '23254784', '7', 'M', 'JULIO ANDRÉS', 'GONZÁLEZ', 'VARGAS', 'SANTA ROSA LO SIERRA S/N SAN PEDRO', 'MELIPILLA', NULL, '40796508', '2010-02-22', '2024-03-01', '1900-01-01'),
(801, 2024, 310, 1, '1° medio', 'A', '23147693', '8', 'M', 'BENJAMÍN ISAAC', 'BUSTOS', 'PALOMINOS', 'LAGO RANCO 231 LOS LAGOS 1', 'MELIPILLA', 'PALOMINOSE14@GMAIL.COM', '73648177', '2009-10-14', '2024-03-01', '1900-01-01'),
(802, 2024, 310, 1, '1° medio', 'A', '24133655', '7', 'M', 'GUILBERT JESUS', 'QUISPE', 'SURUBI', NULL, 'MELIPILLA', NULL, NULL, '2009-06-20', '2024-03-05', '1900-01-01'),
(803, 2024, 310, 1, '1° medio', 'A', '23349582', '4', 'F', 'CONSTANZA PÍA MONSERRAT', 'SERRANO', 'FUENZALIDA', 'POBL. CLOTARIO BLEST /SANTIAGO QUEBRADINO', 'MELIPILLA', 'ESCUELITASANTAINESDELAPAZ@GMAIL.COM', '86297858', '2010-06-10', '2024-03-12', '1900-01-01'),
(804, 2024, 310, 1, '1° medio', 'A', '22760609', '6', 'F', 'DAFNE ANTONIA', 'JIMÉNEZ', 'PÉREZ', 'LOS PATRIOTAS', 'CURACAVÍ', 'ESCUELACUYUNCAVI@GMAIL.COM', '54446699', '2008-04-23', '2024-03-01', '1900-01-01'),
(805, 2024, 310, 1, '1° medio', 'A', '23128445', '1', 'M', 'VICENTE ALONSO', 'CATALAN', 'CATALÁN', 'ALTO LOICA', 'SAN PEDRO', 'INGRIDPAZ25@MAIL.COM', '89766329', '2009-09-18', '2024-03-01', '1900-01-01'),
(806, 2024, 310, 1, '1° medio', 'A', '22956856', '6', 'F', 'ALMENDRA ANAHIS', 'CERDA', 'VILLALOBOS', 'CHIÑPIGUE LOS QUILOS', 'EL MONTE', NULL, '54653111', '2009-02-25', '2024-03-01', '1900-01-01'),
(807, 2024, 310, 1, '1° medio', 'B', '22915577', '6', 'F', 'CARLA GRACIELA ANAÍS', 'PÉREZ', 'JARA', NULL, 'CARTAGENA', 'JARAMARIELA870@GMAIL.COM', NULL, '2009-01-02', '2024-03-01', '1900-01-01'),
(808, 2024, 310, 1, '1° medio', 'B', '23266853', '9', 'M', 'CRISTIAN FELIPE', 'CABRERA', 'FERNÁNDEZ', 'ISLA CALBUCO 1792', 'CERRO NAVIA', NULL, '6691150', '2010-03-08', '2024-03-01', '1900-01-01'),
(809, 2024, 310, 1, '1° medio', 'B', '23170729', '8', 'F', 'TRINIDAD PAZ', 'PÉREZ', 'QUIROZ', 'SAN PEDRO', 'LA FLORIDA', NULL, NULL, '2009-11-13', '2024-03-01', '1900-01-01'),
(810, 2024, 310, 1, '1° medio', 'B', '23110453', '4', 'M', 'ANGEL IGNACIO', 'VALDÉS', 'MUÑOZ', NULL, 'LA FLORIDA', NULL, NULL, '2009-08-11', '2024-03-01', '1900-01-01'),
(811, 2024, 310, 1, '1° medio', 'B', '23159588', '0', 'M', 'VICENTE NICOLÁS', 'RIVAS', 'ARANEDA', 'RAVAL 16637', 'MAIPÚ', NULL, NULL, '2009-10-30', '2024-03-01', '1900-01-01'),
(812, 2024, 310, 1, '1° medio', 'B', '23126194', 'K', 'M', 'DAVID EUGENIO', 'CHANQUEO', 'MENA', 'SAN DANIEL', 'PUDAHUEL', NULL, '45393630', '2009-09-17', '2024-03-01', '1900-01-01'),
(813, 2024, 310, 1, '1° medio', 'B', '23213082', '2', 'M', 'ANGEL ROBERTO', 'JARA', 'MUÑOZ', NULL, 'RECOLETA', NULL, NULL, '2009-12-30', '2024-03-01', '1900-01-01'),
(814, 2024, 310, 1, '1° medio', 'B', '22872415', '7', 'M', 'AGUSTÍN IGNACIO', 'CORDERO', 'VERDUGO', NULL, 'RENCA', NULL, NULL, '2008-11-18', '2024-03-18', '1900-01-01'),
(815, 2024, 310, 1, '1° medio', 'B', '23244273', '5', 'M', 'GENARO ANTONY', 'ARÉVALO', 'PETACCI', 'ALMIRANTE LATORRE 10430', 'SAN RAMÓN', NULL, NULL, '2010-02-06', '2024-03-01', '1900-01-01'),
(816, 2024, 310, 1, '1° medio', 'B', '22903132', '5', 'F', 'CATALINA PAZ', 'GONZALEZ', 'GROZNO', 'PSJE. LAZARO CARREÑO N° 648 FLORENCIA II', 'MELIPILLA', NULL, NULL, '2008-12-20', '2024-03-06', '1900-01-01'),
(817, 2024, 310, 1, '1° medio', 'B', '100692316', '6', 'M', 'YHON JAIRO', 'TRUJILLO', 'PIMENTEL', NULL, 'MELIPILLA', NULL, NULL, '2009-05-16', '2024-03-01', '1900-01-01'),
(818, 2024, 310, 1, '1° medio', 'B', '23137119', '2', 'F', 'MARTINA ISIDORA', 'BLANCO', 'MAUREIRA', 'POBL. RAMON VALDIVIESO / JAVIER ROMERO/ BLOCK', 'MELIPILLA', NULL, '92530390', '2009-10-01', '2024-03-01', '1900-01-01'),
(819, 2024, 310, 1, '1° medio', 'B', '27974276', '1', 'F', 'STEFANIA', 'CANO', 'CARMONA', 'LOS ESPINOS 7 POBL. LA FORESTA', 'MELIPILLA', NULL, '88109219', '2009-06-16', '2024-03-01', '1900-01-01'),
(820, 2024, 310, 1, '1° medio', 'B', '100443065', '0', 'M', 'ALEX', 'CONDORI', 'MAMANI', NULL, 'MELIPILLA', NULL, '62752703', '2008-09-01', '2024-03-01', '1900-01-01'),
(821, 2024, 310, 1, '1° medio', 'B', '22992291', '2', 'M', 'GUSTAVO ALEJANDRO', 'DÍAZ', 'CONTRERAS', 'GUILLERMO VERA 2073 POB PABLO LIZAMA', 'MELIPILLA', NULL, '73834656', '2009-04-10', '2024-03-01', '1900-01-01'),
(822, 2024, 310, 1, '1° medio', 'B', '23213753', '3', 'F', 'ANTONELLA CONSTANZA', 'DUHAMEL', 'GONZÁLEZ', NULL, 'MELIPILLA', NULL, NULL, '2009-12-28', '2024-03-01', '1900-01-01'),
(823, 2024, 310, 1, '1° medio', 'B', '26958512', '9', 'F', 'EILYM DALIANE', 'FERREIRA', 'GONZALEZ', NULL, 'MELIPILLA', NULL, '41584477', '2009-04-20', '2024-03-01', '1900-01-01'),
(824, 2024, 310, 1, '1° medio', 'B', '23221989', '0', 'F', 'SCHLOMITH NAZARETH CONSUELO', 'FLORES', 'CERÓN', 'POBL. BENJAMÍN VICUÑA / OBISPO GUILLERMO VERA', 'MELIPILLA', NULL, '86747590', '2010-01-03', '2024-03-01', '1900-01-01'),
(825, 2024, 310, 1, '1° medio', 'B', '22659572', '4', 'M', 'EDISON MARIO ANDRÉS', 'FLORES', 'MIRANDA', 'PASAJE JOSÉ ARTURO PACHECHO 305', 'MELIPILLA', NULL, '83589771', '2008-03-02', '2024-03-01', '1900-01-01'),
(826, 2024, 310, 1, '1° medio', 'B', '23013814', '1', 'M', 'DANIEL BENJAMÍN', 'GODOY', 'CAMUS', 'BERTA GONZÁLEZ Nº 2077 VILLA FLORENCIA II', 'MELIPILLA', NULL, NULL, '2009-05-05', '2024-03-01', '1900-01-01'),
(827, 2024, 310, 1, '1° medio', 'B', '23036844', '9', 'F', 'SOFÍA PAZ', 'GUTIÉRREZ', 'VERA', 'POBL. FLORENCIA I / ALEJANDRO GONZÁLEZ VIAL', 'MELIPILLA', NULL, '67304527', '2009-06-05', '2024-03-01', '1900-01-01'),
(828, 2024, 310, 1, '1° medio', 'B', '23099337', '8', 'F', 'MARTINA IGNACIA', 'MARTÍNEZ', 'GONZÁLEZ', 'POBL.FLORENCIA I / LASTENIA ALVAREZ', 'MELIPILLA', NULL, '86778606', '2009-08-14', '2024-03-01', '1900-01-01'),
(829, 2024, 310, 1, '1° medio', 'B', '100410351', 'K', 'F', 'YOSELIN', 'MENDEZ', 'SASTE', NULL, 'MELIPILLA', NULL, '33403261', '2010-02-22', '2024-03-01', '1900-01-01'),
(830, 2024, 310, 1, '1° medio', 'B', '23213205', '1', 'F', 'MERY ANNE MAITE', 'PATIÑO', 'HUERTA', 'BELLA ESPARANZA PAJARITO 1461', 'MELIPILLA', NULL, NULL, '2009-12-24', '2024-03-01', '1900-01-01'),
(831, 2024, 310, 1, '1° medio', 'B', '23223950', '6', 'F', 'KARLA BELÉN', 'PEÑA', 'BELTRÁN', 'CALLE MERCED N° 2215 LA FLORENCIA I', 'MELIPILLA', NULL, NULL, '2010-01-13', '2024-03-01', '1900-01-01'),
(832, 2024, 310, 1, '1° medio', 'B', '23123702', 'K', 'F', 'HISARIS MONSERRAT', 'PEÑAILILLO', 'MUÑOZ', 'BERNARDO VALENZUELA 1880 VILLA BENJAMÍN ULLOA', 'MELIPILLA', NULL, '93219341', '2009-09-14', '2024-03-01', '1900-01-01'),
(833, 2024, 310, 1, '1° medio', 'B', '100679948', '1', 'M', 'ANGEL MARIO', 'PEREZ', 'GUERRA', NULL, 'MELIPILLA', NULL, '31034767', '2009-07-19', '2024-03-01', '1900-01-01'),
(834, 2024, 310, 1, '1° medio', 'B', '22918414', '8', 'F', 'MONSERRAT ANTONIA', 'PONCE', 'CONCHA', 'POBL. LA FORESTA / LOS PEUMOS', 'MELIPILLA', NULL, '65065706', '2008-12-22', '2024-03-01', '1900-01-01'),
(835, 2024, 310, 1, '1° medio', 'B', '23085776', '8', 'F', 'VALENTINA PAZ', 'RIVEROS', 'YÁÑEZ', 'POBL. LOS LAGOS II / LAGO CHAPO', 'MELIPILLA', NULL, '90246180', '2009-07-26', '2024-03-01', '1900-01-01'),
(836, 2024, 310, 1, '1° medio', 'B', '22848914', 'K', 'F', 'DANAE CATALINA', 'SANDOVAL', 'RIQUELME', NULL, 'MELIPILLA', NULL, NULL, '2008-10-17', '2024-03-01', '1900-01-01'),
(837, 2024, 310, 1, '1° medio', 'B', '23295934', '7', 'F', 'SOFÍA ANTONIA', 'VERGARA', 'ALMONACID', 'POBL. LAFORESTA / LAS MAGNOLIAS', 'MELIPILLA', NULL, '89195383', '2010-04-05', '2024-03-01', '1900-01-01'),
(838, 2024, 310, 1, '1° medio', 'B', '22972494', '0', 'M', 'ARON ANTONIO', 'ALTAMIRANO', 'TRINCADO', NULL, 'MELIPILLA', NULL, NULL, '2009-03-15', '2024-03-01', '1900-01-01'),
(839, 2024, 310, 1, '1° medio', 'B', '22739598', '2', 'M', 'FABIÁN IGNACIO', 'ARMIJO', 'FRÍAS', 'ROBERTO MATTA 126', 'MELIPILLA', NULL, NULL, '2008-06-05', '2024-03-01', '1900-01-01'),
(840, 2024, 310, 1, '1° medio', 'B', '100523351', '4', 'M', 'HENRRY ALEXANDER', 'CHACIN', 'ALBORNOZ', NULL, 'MELIPILLA', NULL, '91476260', '2009-04-17', '2024-03-01', '1900-01-01'),
(841, 2024, 310, 1, '1° medio', 'B', '23179552', '9', 'M', 'MAURICIO MAXIMILIANO', 'CIRINEO', 'MORALES', NULL, 'MELIPILLA', NULL, NULL, '2009-11-12', '2024-03-01', '1900-01-01'),
(842, 2024, 310, 1, '1° medio', 'B', '23096858', '6', 'M', 'SEBASTIÁN GUILLERMO', 'VIDAL', 'TORRES', 'ALEJANDRO GONZÁLEZ VIAL 2239', 'MELIPILLA', 'SEBASTIAN.VIDAL@MELIPILLAEDUCA.CL', '65112983', '2009-08-14', '2024-03-01', '1900-01-01'),
(843, 2024, 310, 1, '1° medio', 'B', '23321106', '0', 'M', 'IGOR ANDREI', 'KUNAKOV', 'HERRERA', 'VALENTIN SILVA 339 VILLA COLONIAL II', 'MELIPILLA', NULL, '28930670', '2010-04-27', '2024-03-01', '1900-01-01'),
(844, 2024, 310, 1, '1° medio', 'B', '22862025', '4', 'M', 'ALONSO ALEXANDER', 'MADRID', 'MARDONES', NULL, 'MELIPILLA', NULL, NULL, '2008-11-03', '2024-03-01', '2024-04-08'),
(845, 2024, 310, 1, '1° medio', 'B', '23341710', '6', 'F', 'ISIDORA ANTONIA', 'AROS', 'PACHECO', NULL, 'MELIPILLA', NULL, NULL, '2010-06-03', '2024-03-01', '1900-01-01'),
(846, 2024, 310, 1, '1° medio', 'B', '26111125', 'K', 'F', 'NAIKA', 'GEORGES', NULL, NULL, 'MELIPILLA', NULL, NULL, '2010-02-05', '2024-03-01', '1900-01-01'),
(847, 2024, 310, 1, '1° medio', 'B', '22732344', '2', 'F', 'KRISHNA MARIANELA ISABEL', 'ORELLANA', 'GUTIÉRREZ', 'POBL. LA FORESTA / LOS COPIHUES', 'MELIPILLA', NULL, '58300036', '2008-05-27', '2024-03-01', '1900-01-01'),
(848, 2024, 310, 1, '1° medio', 'B', '100576223', '1', 'F', 'NAYERLIS ALEJANDRA', 'VARGAS', 'COLINA', NULL, 'MELIPILLA', NULL, '36224277', '2009-07-03', '2024-03-01', '1900-01-01'),
(849, 2024, 310, 1, '1° medio', 'B', '100717114', '1', 'M', 'ANGEL DAVID', 'PEREZ', 'CELIS', NULL, 'MELIPILLA', NULL, '91093117', '2009-12-09', '2024-07-05', '1900-01-01'),
(850, 2024, 310, 2, '2° medio', 'A', '22921647', '3', 'M', 'JAZTYN RODRIGO', 'ENCALADA', 'ROMERO', NULL, 'ILLAPEL', NULL, NULL, '2009-01-10', '2024-03-01', '1900-01-01'),
(851, 2024, 310, 2, '2° medio', 'A', '22848710', '4', 'M', 'JUAN PABLO', 'ESCALANTE', 'RIVERO', 'VILLA INES DE SUAREZ', 'LAS CABRAS', 'RIVEROMSRCELA923@GMAIL.COM', '77255223', '2008-10-05', '2024-03-01', '1900-01-01'),
(852, 2024, 310, 2, '2° medio', 'A', '26378776', '5', 'M', 'JESUS DAVID', 'PINEDA', 'ROSALES', NULL, 'CONCHALÍ', NULL, NULL, '2008-11-15', '2024-03-01', '1900-01-01'),
(853, 2024, 310, 2, '2° medio', 'A', '22860178', '0', 'F', 'MONSERRAT NAOMI GIDLEY', 'SILVA', 'SANTELICES', 'RIO URUBAMBA 1246', 'CONCHALÍ', NULL, NULL, '2008-10-18', '2024-03-06', '2024-08-13'),
(854, 2024, 310, 2, '2° medio', 'A', '22714588', '9', 'M', 'GASPAR NICOLÁS', 'BRAVO', 'FIGUEROA', NULL, 'LA CISTERNA', NULL, NULL, '2008-05-03', '2024-03-01', '1900-01-01'),
(855, 2024, 310, 2, '2° medio', 'A', '22947223', '2', 'F', 'ANTONELLA AYLINE', 'MALDONADO', 'TOLEDO', 'SAN LUCAS Nº 11.433', 'LA FLORIDA', NULL, '9854178', '2009-02-17', '2024-03-01', '1900-01-01'),
(856, 2024, 310, 2, '2° medio', 'A', '22721991', '2', 'M', 'MAXIMILIANO EDUARDO', 'CAMPOS', 'COFRÉ', 'LAS FICEAS 6437 LA HIGUERA', 'LA FLORIDA', NULL, '62037051', '2008-05-17', '2024-03-01', '1900-01-01'),
(857, 2024, 310, 2, '2° medio', 'A', '22769231', '6', 'F', 'MARTINA ISADORA', 'SALINAS', 'ANABALÓN', 'NECOCHEA', 'LO PRADO', NULL, '50435120', '2008-07-12', '2024-03-01', '1900-01-01'),
(858, 2024, 310, 2, '2° medio', 'A', '22949831', '2', 'F', 'GABRIELA ANTONIA', 'CARMONA', 'BARRÍA', 'VASVONGADAS 4191', 'RECOLETA', NULL, NULL, '2009-02-18', '2024-03-01', '1900-01-01'),
(859, 2024, 310, 2, '2° medio', 'A', '22940475', 'K', 'M', 'ALLEN MISSAEL', 'ÁLVAREZ', 'ELGUETA', NULL, 'MELIPILLA', NULL, NULL, '2009-01-28', '2024-03-01', '1900-01-01'),
(860, 2024, 310, 2, '2° medio', 'A', '22851527', '2', 'M', 'RODRIGO ANDRÉS', 'ARAYA', 'FRÍAS', 'CALLEJÓN MAULÉN', 'MELIPILLA', NULL, NULL, '2008-10-24', '2024-03-01', '1900-01-01'),
(861, 2024, 310, 2, '2° medio', 'A', '22726133', '1', 'M', 'VICENTE ANDRÉS', 'ARCE', 'LOYOLA', 'VILLA BENJAMIN CASA 62', 'MELIPILLA', NULL, NULL, '2008-05-21', '2024-03-01', '1900-01-01'),
(862, 2024, 310, 2, '2° medio', 'A', '22810020', 'K', 'F', 'ARACELY MONSERRAT', 'MARÍN', 'HERNÁNDEZ', 'VILLARRICA 320', 'MELIPILLA', NULL, NULL, '2008-08-28', '2024-03-01', '1900-01-01'),
(863, 2024, 310, 2, '2° medio', 'A', '23033307', '6', 'F', 'MARÍA ROXANA', 'MIRANDA', 'PINTO', 'POBL. FLORENCIA / LASTENIA ALVAREZ', 'MELIPILLA', NULL, '89236279', '2009-05-31', '2024-03-01', '1900-01-01'),
(864, 2024, 310, 2, '2° medio', 'A', '22741797', '8', 'M', 'SEBASTIÁN ANTONIO', 'MONTOYA', 'FABIO', 'LUIS BORREMANS 81. POB BENJAMIN ULLOA', 'MELIPILLA', NULL, '68073481', '2008-06-11', '2024-03-01', '1900-01-01'),
(865, 2024, 310, 2, '2° medio', 'A', '22669874', '4', 'M', 'CLAUDIO ALEXANDER', 'MUÑOZ', 'SANTIBÁÑEZ', 'PROFESORA ROSA AGUERO 1904 POBL. BENJAMIN ULLOA', 'MELIPILLA', NULL, '42310515', '2008-03-14', '2024-03-01', '1900-01-01'),
(866, 2024, 310, 2, '2° medio', 'A', '22523504', 'K', 'F', 'IGNACIA VALENTINA', 'NÚÑEZ', 'CERDA', 'POBL. LA FORESTA / LOS PEUMOS', 'MELIPILLA', NULL, '90105180', '2007-10-09', '2024-03-01', '1900-01-01'),
(867, 2024, 310, 2, '2° medio', 'A', '22803910', '1', 'F', 'JAVIERA ANAÍS', 'OJEDA', 'BERMÚDEZ', 'POBL. LOS JAZMINES I / PELLÍN', 'MELIPILLA', NULL, '77750470', '2008-08-27', '2024-03-01', '1900-01-01'),
(868, 2024, 310, 2, '2° medio', 'A', '22934824', '8', 'F', 'NICOLE ALEJANDRA', 'OROZCO', 'ZÚÑIGA', 'POBL. FLORENCIA II / LORGIO DAÑOBEITÍA', 'MELIPILLA', NULL, '67521315', '2009-01-30', '2024-03-01', '1900-01-01'),
(869, 2024, 310, 2, '2° medio', 'A', '23023231', '8', 'M', 'FELIPE AMARU', 'PAILLAFIL', 'GONZÁLEZ', NULL, 'MELIPILLA', NULL, '96728373', '2009-05-19', '2024-03-01', '1900-01-01'),
(870, 2024, 310, 2, '2° medio', 'A', '22802618', '2', 'F', 'JAVIERA ANTONIA', 'PARRA', 'GIANONI', 'POBL. BENJAMÍN VICUÑA / JUAN PABLO II', 'MELIPILLA', NULL, '94128097', '2008-08-23', '2024-03-01', '1900-01-01'),
(871, 2024, 310, 2, '2° medio', 'A', '22437285', 'K', 'M', 'MIGUEL IVAN', 'PEÑAILILLO', 'ORTEGA', NULL, 'MELIPILLA', NULL, NULL, '2007-07-04', '2024-03-01', '1900-01-01'),
(872, 2024, 310, 2, '2° medio', 'A', '22748766', '6', 'M', 'JEAN PIERRE ENRIQUE', 'PINTO', 'AVENDAÑO', 'CALLE SAN MARTIN 445-D POMAIRE', 'MELIPILLA', 'JEAN.PINTO@MELIPILLAEDUCA.CL', '95937876', '2008-06-18', '2024-03-01', '1900-01-01'),
(873, 2024, 310, 2, '2° medio', 'A', '22795280', '6', 'F', 'DANAE GHISLAINE', 'RODRÍGUEZ', 'GÓMEZ', 'CONDOMINIO BICENTENARIO / PASAJE 15/ LOS JAZMINES I / CASA', 'MELIPILLA', NULL, '85587585', '2008-08-09', '2024-03-01', '1900-01-01'),
(874, 2024, 310, 2, '2° medio', 'A', '22731248', '3', 'M', 'JAVIER ARMANDO', 'SILVA', 'NARANJO', 'LA FORESTA 121', 'MELIPILLA', NULL, '54473721', '2008-05-28', '2024-03-01', '1900-01-01'),
(875, 2024, 310, 2, '2° medio', 'A', '22881919', '0', 'M', 'DIEGO ALEXANDER NICOLÁS', 'SOTO', 'GÁRATE', NULL, 'MELIPILLA', NULL, NULL, '2008-11-24', '2024-03-01', '1900-01-01'),
(876, 2024, 310, 2, '2° medio', 'A', '22814556', '4', 'F', 'ISIDORA ANDREA', 'YÁÑEZ', 'GAMONAL', 'VILLA MARTITA ROMANINI / MATÍAS NÚÑEZ', 'MELIPILLA', NULL, '89865864', '2008-09-05', '2024-03-01', '1900-01-01'),
(877, 2024, 310, 2, '2° medio', 'A', '22895683', 'K', 'M', 'ALAN ANTONIO', 'CIFUENTES', 'MARTÍNEZ', NULL, 'MELIPILLA', NULL, NULL, '2008-12-08', '2024-03-01', '1900-01-01'),
(878, 2024, 310, 2, '2° medio', 'A', '22857079', '6', 'F', 'VALENTINA ALEJANDRA', 'FUENTES', 'GAJARDO', 'LOMAS DE MANSO / LOS JAZMINES B', 'MELIPILLA', NULL, '83734391', '2008-10-24', '2024-03-01', '1900-01-01'),
(879, 2024, 310, 2, '2° medio', 'A', '22983797', '4', 'M', 'JUAN JOSÉ', 'LIZANA', 'CALDERÓN', NULL, 'MELIPILLA', NULL, NULL, '2009-03-30', '2024-03-01', '1900-01-01'),
(880, 2024, 310, 2, '2° medio', 'A', '22819923', '0', 'F', 'CONSTANZA ANTONIA', 'LOYOLA', 'GONZÁLEZ', 'ROBERTO P. MATTA 172 POBL LOS JAZMINES', 'MELIPILLA', NULL, NULL, '2008-09-09', '2024-03-01', '1900-01-01'),
(881, 2024, 310, 2, '2° medio', 'A', '22779392', '9', 'F', 'STEFANI GABRIELA', 'LOYOLA', 'PORRAS', 'POBL.ILUSIONES COMPARTIDAS / ARTURO PACHECO', 'MELIPILLA', NULL, '77987920', '2008-07-27', '2024-03-01', '1900-01-01'),
(882, 2024, 310, 2, '2° medio', 'A', '22558399', '4', 'F', 'AGUSTINA RENATA', 'MOLINA', 'PÉREZ', 'POBL. LOS PRADOS / RICARDO BOURZAT', 'MELIPILLA', NULL, '89415957', '2007-11-13', '2024-03-01', '1900-01-01'),
(883, 2024, 310, 2, '2° medio', 'A', '22919221', '3', 'F', 'MIA PASCALE', 'SANTIBÁÑEZ', 'MONTES', 'ALTOS DE POPETAS', 'MELIPILLA', NULL, '75749613', '2009-01-03', '2024-03-01', '1900-01-01'),
(884, 2024, 310, 2, '2° medio', 'A', '22840032', '7', 'F', 'CATALINA YELENA', 'KUNAKOV', 'HERRERA', 'PEDRO MALDONADO S/N', 'MELIPILLA', NULL, NULL, '2008-09-12', '2024-03-01', '1900-01-01'),
(885, 2024, 310, 2, '2° medio', 'A', '22770478', '0', 'M', 'IAN ALONSO', 'CERÓN', 'GONZÁLEZ', 'PINTOR JOSÉ PEROTTI 11', 'MELIPILLA', NULL, NULL, '2008-07-09', '2024-03-01', '1900-01-01'),
(886, 2024, 310, 2, '2° medio', 'A', '22704295', '8', 'M', 'AXEL ANDRÉS', 'GÓMEZ', 'MIRANDA', NULL, 'MELIPILLA', NULL, NULL, '2008-04-23', '2024-03-06', '1900-01-01'),
(887, 2024, 310, 2, '2° medio', 'A', '22659855', '3', 'F', 'CATALINA IGNACIA', 'SEGURA', 'SANTANA', 'EL ULMO .LOS CANELOS', 'MELIPILLA', NULL, '74675753', '2008-03-03', '2024-03-20', '2024-05-06'),
(888, 2024, 310, 2, '2° medio', 'A', '100717114', '1', 'M', 'ANGEL DAVID', 'PEREZ', 'CELIS', NULL, 'MELIPILLA', NULL, '91093117', '2009-12-09', '2024-06-03', '2024-07-05'),
(889, 2024, 310, 2, '2° medio', 'A', '28385846', '4', 'F', 'LEOMARYS DANIELA', 'COLMENARES', 'COLMENARES', NULL, 'MELIPILLA', NULL, NULL, '2008-08-25', '2024-03-01', '1900-01-01'),
(890, 2024, 310, 2, '2° medio', 'A', '28469545', '3', 'M', 'ANNYAR DANIEL', 'LOPEZ', 'CRESPO', NULL, 'MELIPILLA', NULL, '93611889', '2007-07-24', '2024-03-01', '1900-01-01'),
(891, 2024, 310, 2, '2° medio', 'A', '100736994', '4', 'F', 'RUTH NOHEMI', 'ARANDUA', 'FERNADEZ', NULL, 'TALAGANTE', NULL, NULL, '2008-09-08', '2024-03-15', '1900-01-01'),
(892, 2024, 310, 2, '2° medio', 'B', '22847936', '5', 'F', 'ALMENDRA ANDREA', 'TUDESCA', 'BENÍTEZ', 'CARACOLES', 'ANTOFAGASTA', 'CLAUDIABENITEZ1306@GAMAIL.COM', '72894878', '2008-10-07', '2024-03-01', '1900-01-01'),
(893, 2024, 310, 2, '2° medio', 'B', '22847944', '6', 'M', 'PABLO ANDRE', 'TUDESCA', 'BENÍTEZ', 'CARACOLES', 'ANTOFAGASTA', 'CLAUDIABENITEZ1306@GAMAIL.COM', '72894878', '2008-10-07', '2024-03-01', '1900-01-01'),
(894, 2024, 310, 2, '2° medio', 'B', '22776458', '9', 'M', 'JOSÉ MATÍAS', 'PÉREZ', 'VERGARA', 'PENCAHUE ABAJO 151', 'SAN VICENTE', 'ADRIANA_ARANGUIZ@LIVE.CL', '93620754', '2008-07-05', '2024-03-01', '1900-01-01'),
(895, 2024, 310, 2, '2° medio', 'B', '22958087', '6', 'M', 'NICOLÁS IGNACIO MAXIMILIANO', 'SAN MARTÍN', 'FIGUEROA', 'LAURA RODRIGUEZ 7097', 'PEÑALOLÉN', NULL, '89195437', '2009-02-20', '2024-03-05', '1900-01-01'),
(896, 2024, 310, 2, '2° medio', 'B', '22570026', '5', 'M', 'ISAC JOSÉ', 'CORREA', 'DA CONCEICAO', 'UCAYALIY', 'RECOLETA', NULL, '88865148', '2007-12-03', '2024-03-01', '1900-01-01'),
(897, 2024, 310, 2, '2° medio', 'B', '22563321', '5', 'F', 'MONTSERRAT JAVIERA', 'ROJAS', 'SANTIS', 'TOPOCALMA', 'RENCA', NULL, '50573928', '2007-11-26', '2024-03-12', '1900-01-01'),
(898, 2024, 310, 2, '2° medio', 'B', '22769744', 'K', 'M', 'MARTÍN GULIANO', 'ARÉVALO', 'PETACCI', 'ALMIRANTE LATORRE', 'SAN RAMÓN', NULL, NULL, '2008-07-13', '2024-03-01', '1900-01-01'),
(899, 2024, 310, 2, '2° medio', 'B', '100741768', 'K', 'F', 'HELEN', 'SUAREZ', 'COSSIO', NULL, 'PUENTE ALTO', NULL, '54321774', '2008-04-19', '2024-03-05', '1900-01-01'),
(900, 2024, 310, 2, '2° medio', 'B', '22911161', '2', 'M', 'BENJAMÍN ALONSO', 'GONZALES', 'ARIAS', NULL, 'LAMPA', NULL, NULL, '2009-01-02', '2024-03-01', '1900-01-01'),
(901, 2024, 310, 2, '2° medio', 'B', '28297888', '1', 'F', 'NELIANY GABRIELA', 'ROSALES', 'PEÑA', NULL, 'MELIPILLA', NULL, '67505135', '2008-05-15', '2024-03-07', '1900-01-01'),
(902, 2024, 310, 2, '2° medio', 'B', '22690438', '7', 'F', 'VICTORIA ESPERANZA', 'CANDIA', 'CORREA', NULL, 'MELIPILLA', NULL, NULL, '2008-04-03', '2024-03-01', '1900-01-01'),
(903, 2024, 310, 2, '2° medio', 'B', '22974493', '3', 'M', 'PAULO ALFONSO', 'ACEVEDO', 'LOYOLA', 'CLOTARIO BLEST 234 VILLA CLOTARIO BLEST', 'MELIPILLA', NULL, '86717585', '2009-03-12', '2024-03-01', '2024-03-21'),
(904, 2024, 310, 2, '2° medio', 'B', '22944252', 'K', 'F', 'NATACHA ANTONELA', 'AGUILAR', 'GONZÁLEZ', 'POBL. PABLO LIZAMA / VÍCTOR MARÍN', 'MELIPILLA', NULL, '87803160', '2009-02-11', '2024-03-01', '1900-01-01'),
(905, 2024, 310, 2, '2° medio', 'B', '22875000', 'K', 'F', 'KRISHNA ANTONELLA DANAE', 'BALLESTEROS', 'MORENO', 'POBL. LOS POETAS / GABRIELA MISTRAL/ BLOCK', 'MELIPILLA', NULL, '89425010', '2008-11-19', '2024-03-01', '1900-01-01'),
(906, 2024, 310, 2, '2° medio', 'B', '22781347', '4', 'M', 'CARLOS EDUARDO VICENTE', 'CENTONCIO', 'ORTEGA', 'TENIENTE BARAHONA 13541', 'MELIPILLA', NULL, NULL, '2008-07-03', '2024-03-01', '1900-01-01'),
(907, 2024, 310, 2, '2° medio', 'B', '22821110', '9', 'F', 'FERNANDA BEATRIZ', 'CHACÓN', 'SEREÑO', 'LOMAS DE MANSO / BLOCK', 'MELIPILLA', NULL, '73658447', '2008-09-14', '2024-03-01', '1900-01-01'),
(908, 2024, 310, 2, '2° medio', 'B', '22821149', '4', 'F', 'SOFÍA PASCAL', 'CHACÓN', 'SEREÑO', 'LOMAS DE MANSO 6 / BLOCK', 'MELIPILLA', NULL, '73658447', '2008-09-14', '2024-03-01', '1900-01-01'),
(909, 2024, 310, 2, '2° medio', 'B', '22743994', '7', 'M', 'LUCAS DE JESÚS', 'GONZÁLEZ', 'SANTIBÁÑEZ', 'LOS ALTOS DE CANTILLANA.PASAJE EL MAÑÍO', 'MELIPILLA', NULL, '61647065', '2008-06-09', '2024-03-01', '1900-01-01'),
(910, 2024, 310, 2, '2° medio', 'B', '22634527', '2', 'F', 'DOMINIQUE FRANCOISE', 'GUERRERO', 'YÁÑEZ', 'POBL. RENACER / SAN MARTÍN', 'MELIPILLA', NULL, '99826037', '2008-02-04', '2024-03-01', '1900-01-01'),
(911, 2024, 310, 2, '2° medio', 'B', '22337070', '5', 'F', 'NICOLE ESTEFANÍA', 'HERRERA', 'VERDEJO', 'POBL.CLOTARIO BLEST/ JUAN BRUNA', 'MELIPILLA', NULL, '89460846', '2007-02-22', '2024-03-01', '1900-01-01'),
(912, 2024, 310, 2, '2° medio', 'B', '22892536', '5', 'F', 'GISSELA ANTONELLA', 'KAUER', 'MARTÍNEZ', 'AV. CARLOS AVILÉS', 'MELIPILLA', NULL, '50439827', '2008-12-05', '2024-03-01', '1900-01-01'),
(913, 2024, 310, 2, '2° medio', 'B', '22981263', '7', 'M', 'BENJAMÍN EMILIO', 'LISBOA', 'SÁNCHEZ', 'BENJAMIN ULLOA ESPERANZA SOTO CASA 41', 'MELIPILLA', NULL, NULL, '2009-03-20', '2024-03-01', '1900-01-01'),
(914, 2024, 310, 2, '2° medio', 'B', '22991900', '8', 'F', 'FRANCESCA BELÉN', 'LOYOLA', 'FLORES', 'POBL. BELLA ESPERANZA / CARLOS AVILÉS', 'MELIPILLA', NULL, '81787813', '2009-04-10', '2024-03-01', '1900-01-01'),
(915, 2024, 310, 2, '2° medio', 'B', '22770602', '3', 'M', 'JOAQUÍN FRANCISCO', 'MADRID', 'RUBIO', NULL, 'MELIPILLA', NULL, NULL, '2008-07-14', '2024-03-01', '1900-01-01'),
(916, 2024, 310, 2, '2° medio', 'B', '22648658', '5', 'F', 'GEMMA DAMARIS NICOLE', 'NÚÑEZ', 'SÁNCHEZ', 'LA FLORENCIA 1 SEDE', 'MELIPILLA', NULL, NULL, '2008-02-19', '2024-03-01', '1900-01-01'),
(917, 2024, 310, 2, '2° medio', 'B', '23021752', '1', 'M', 'MARTÍN ALONSO', 'ORELLANA', 'LÓPEZ', NULL, 'MELIPILLA', NULL, NULL, '2009-05-18', '2024-03-01', '1900-01-01'),
(918, 2024, 310, 2, '2° medio', 'B', '22705203', '1', 'M', 'JOSUE ANTONIO', 'ORTÚZAR', 'URTUBIA', NULL, 'MELIPILLA', NULL, NULL, '2008-04-20', '2024-03-01', '1900-01-01'),
(919, 2024, 310, 2, '2° medio', 'B', '22998364', '4', 'F', 'ESTEFANÍA ANTONIA', 'PAZ', 'BAEZA', 'AV. CARLOS AVILÉS / BLOCK', 'MELIPILLA', NULL, '59450100', '2009-04-16', '2024-03-01', '1900-01-01'),
(920, 2024, 310, 2, '2° medio', 'B', '22837382', '6', 'F', 'MARÍA ELENA', 'SAN MARTÍN', 'ALBARRÁN', NULL, 'MELIPILLA', NULL, NULL, '2008-10-02', '2024-03-01', '1900-01-01'),
(921, 2024, 310, 2, '2° medio', 'B', '23010607', 'K', 'F', 'ANGIE ANAIS', 'VALDEBENITO', 'CASTRO', 'POBL. ILUSIONES COMPARTIDAS / PINTOR PACHECO', 'MELIPILLA', NULL, '93707437', '2009-05-01', '2024-03-01', '1900-01-01'),
(922, 2024, 310, 2, '2° medio', 'B', '22993599', '2', 'F', 'JAVIERA ANTONIA', 'VILLEGAS', 'LOBOS', 'POBL. FLORENCIA I / JUAN DE DIOS DÍAZ', 'MELIPILLA', NULL, '96911177', '2009-04-14', '2024-03-01', '1900-01-01'),
(923, 2024, 310, 2, '2° medio', 'B', '100716388', '2', 'F', 'FERNANDA', 'FERNANDEZ', 'CORSO', NULL, 'MELIPILLA', NULL, NULL, '2008-08-09', '2024-03-01', '1900-01-01'),
(924, 2024, 310, 2, '2° medio', 'B', '22909910', '8', 'M', 'JUSTIN ALEXANDER', 'ARMIJO', 'ARAVENA', 'HUILCO ALTO PASAJE. 8 S/N', 'MELIPILLA', NULL, NULL, '2008-12-27', '2024-03-01', '1900-01-01'),
(925, 2024, 310, 2, '2° medio', 'B', '22669619', '9', 'M', 'FELIPE ANDRÉS', 'MALDONADO', 'PEREIRA', NULL, 'MELIPILLA', NULL, NULL, '2008-03-14', '2024-03-01', '1900-01-01'),
(926, 2024, 310, 2, '2° medio', 'B', '22108458', '6', 'M', 'CRISTOPHER GIOVANNI', 'CARDOZA', 'MIRANDA', 'POB. ILUSIONES COMPARTIDAS PJE JOSÉ ARTURO PACHECO', 'MELIPILLA', NULL, '94940699', '2006-04-24', '2024-03-08', '1900-01-01'),
(927, 2024, 310, 2, '2° medio', 'B', '22703079', '8', 'F', 'ANAÍS IGNACIA', 'ACEVEDO', 'VERA', 'PAPA PIO X 2063 POB. PABLO LISANA', 'MELIPILLA', NULL, '72624293', '2008-03-24', '2024-03-05', '1900-01-01'),
(928, 2024, 310, 2, '2° medio', 'B', '22969382', '4', 'F', 'HESIONE ANASTASIA', 'PINO', 'GONZÁLEZ', 'LOMAS DE MANSO / BLOCK', 'MELIPILLA', NULL, '88690979', '2009-03-12', '2024-03-13', '1900-01-01'),
(929, 2024, 310, 2, '2° medio', 'B', '22189172', '4', 'M', 'MATEO EXEQUIEL', 'SEPÚLVEDA', 'GONZÁLEZ', 'POB. CRUZ ROJA PJE CORINA BRAVO 1011', 'MELIPILLA', NULL, '83834011', '2006-08-01', '2024-03-12', '1900-01-01'),
(930, 2024, 310, 2, '2° medio', 'B', '22303695', '3', 'F', 'MADAÍ ELIÚ', 'ANTINAO', 'LLANCAFIL', 'LOMAS DE MANSO / BLOCK', 'MELIPILLA', NULL, '50404823', '2007-01-13', '2024-04-01', '1900-01-01'),
(931, 2024, 310, 2, '2° medio', 'B', '22599961', '9', 'M', 'BYRON MAXIMILIANO', 'VERA', 'TORRES', NULL, 'MELIPILLA', NULL, NULL, '2008-01-04', '2024-04-09', '1900-01-01'),
(932, 2024, 310, 2, '2° medio', 'B', '28427518', '7', 'F', 'WILIANNY SARAI', 'BLANCO', 'CRESPO', NULL, 'MELIPILLA', NULL, '86532986', '2008-10-08', '2024-03-01', '1900-01-01'),
(933, 2024, 310, 2, '2° medio', 'B', '22926474', '5', 'F', 'CONSUELO TRINIDAD', 'ALARCON', 'PINTO', 'LA LINEA S/N', 'ALHUÉ', NULL, '95447454', '2009-01-16', '2024-03-01', '1900-01-01'),
(934, 2024, 310, 2, '2° medio', 'B', '22882709', '6', 'M', 'ELÍAS FERNANDO', 'FIGUEROA', 'RIQUELME', 'QUILAMUTA', 'SAN PEDRO', NULL, '61617702', '2008-11-26', '2024-03-01', '1900-01-01'),
(935, 2024, 610, 3, '3° medio', 'A', '22595758', '4', 'F', 'TRINIDAD ANTONIA', 'GUAJARDO', 'VIVANCO', 'JUAN XXIII 953 28 DE OCTUBRE', 'HUECHURABA', NULL, NULL, '2007-12-30', '2024-03-01', '1900-01-01'),
(936, 2024, 610, 3, '3° medio', 'A', '22286568', '9', 'F', 'NOEMÍ BELÉN', 'UBILLA', 'DEL VALLE', NULL, 'LA PINTANA', NULL, NULL, '2006-12-20', '2024-03-01', '1900-01-01'),
(937, 2024, 610, 3, '3° medio', 'A', '22033083', '4', 'F', 'FRANSUA ESPERANZA', 'SANGUINETTI', 'VIDAL', 'PORTALES 5994', 'LO PRADO', NULL, NULL, '2006-01-01', '2024-03-01', '1900-01-01'),
(938, 2024, 610, 3, '3° medio', 'A', '22366775', '9', 'M', 'MICHAEL ANDRÉS', 'SANGUINETTI', 'VIDAL', 'PORTALES', 'LO PRADO', NULL, NULL, '2007-03-25', '2024-04-15', '1900-01-01'),
(939, 2024, 610, 3, '3° medio', 'A', '22490695', '1', 'M', 'BENJAMÍN ALFONSO', 'PICÓN', 'JORQUERA', 'LAGUNA CAUQUENES 256', 'PUDAHUEL', NULL, NULL, '2007-09-01', '2024-03-01', '1900-01-01'),
(940, 2024, 610, 3, '3° medio', 'A', '22478354', 'K', 'M', 'TOMÁS GABRIEL', 'ABARCA', 'SILVA', 'PARCELA 39 PASANDO EL TRANQUE, HUECHUM', 'MELIPILLA', NULL, '35810586', '2007-08-17', '2024-03-01', '1900-01-01'),
(941, 2024, 610, 3, '3° medio', 'A', '22573421', '6', 'M', 'RUBÉN ALONSO', 'ALVARADO', 'JIMÉNEZ', 'LUIS BORREMANS199 POBLACION BENJAMIN ULLOA', 'MELIPILLA', NULL, NULL, '2007-12-04', '2024-04-01', '1900-01-01'),
(942, 2024, 610, 3, '3° medio', 'A', '22732951', '3', 'F', 'SOFÍA CATALINA', 'BUSTOS', 'ORTEGA', 'POBL. BENJAMIN ULLOA /ESPERANZA SOTO URBINA', 'MELIPILLA', NULL, '89986567', '2008-05-30', '2024-03-01', '1900-01-01'),
(943, 2024, 610, 3, '3° medio', 'A', '22656340', '7', 'F', 'JOSEFA VALENTINA', 'CARRASCO', 'ROBLEDO', 'POBL. LOS CANELOS / EL CEDRO', 'MELIPILLA', NULL, '98739108', '2008-02-27', '2024-03-01', '1900-01-01'),
(944, 2024, 610, 3, '3° medio', 'A', '22659290', '3', 'M', 'BENJAMÍN ALEXIS', 'CIFUENTES', 'LIZAMA', 'POBL. BENJAMIN ULLOA PJE. PAPA JUAN XXIII 151', 'MELIPILLA', NULL, NULL, '2008-02-11', '2024-03-01', '1900-01-01'),
(945, 2024, 610, 3, '3° medio', 'A', '22505021', 'K', 'F', 'MARÍA ANGELINA JOAQUINA', 'DEL PINO', 'ESPINOZA', 'POBL. PADRE DEMETRIO / JULIO MONTT', 'MELIPILLA', NULL, '89545322', '2007-09-12', '2024-03-01', '1900-01-01'),
(946, 2024, 610, 3, '3° medio', 'A', '22704316', '4', 'F', 'DASMARI MONSERRAT', 'ESQUIVEL', 'CALFULAF', NULL, 'MELIPILLA', NULL, '61802749', '2008-04-22', '2024-03-01', '1900-01-01'),
(947, 2024, 610, 3, '3° medio', 'A', '26944256', '5', 'F', 'EMILY DANIELA', 'FERREIRA', 'GONZALEZ', NULL, 'MELIPILLA', NULL, '41584477', '2007-05-17', '2024-03-01', '1900-01-01'),
(948, 2024, 610, 3, '3° medio', 'A', '22712155', '6', 'F', 'VICTORIA ALEXANDRA', 'GÓMEZ', 'SANTIBÁÑEZ', 'POBL. FLORENCIA I / ALFREDO MARÍN', 'MELIPILLA', NULL, '92985309', '2008-04-23', '2024-03-01', '1900-01-01'),
(949, 2024, 610, 3, '3° medio', 'A', '22061682', '7', 'F', 'LILEN EDITH', 'GONZÁLEZ', 'GAETE', 'POBL. LOS JAZMINES NORTE I / PELLIN', 'MELIPILLA', NULL, '50588515', '2006-02-24', '2024-03-01', '1900-01-01'),
(950, 2024, 610, 3, '3° medio', 'A', '22597864', '6', 'F', 'JOSEFA ANDREA', 'SILVA', 'HERRERA', 'LOMAS DE MANSO / AVDA. CIRCUNVALACIÓN 1855 / BLOCK', 'MELIPILLA', NULL, '81349843', '2008-01-04', '2024-03-01', '1900-01-01'),
(951, 2024, 610, 3, '3° medio', 'A', '22583567', '5', 'F', 'DANAE FERNANDA', 'TOLEDO', 'PROAÑO', 'CABURGA 340 LOS LAGOS II', 'MELIPILLA', NULL, NULL, '2007-12-12', '2024-03-01', '1900-01-01'),
(952, 2024, 610, 3, '3° medio', 'A', '22747229', '4', 'F', 'EMILIA ANTONIA', 'GONZÁLEZ', 'PINTO', 'POBL.LOS POETAS / AV.LIBERTAD', 'MELIPILLA', NULL, '55150093', '2008-06-17', '2024-03-01', '1900-01-01'),
(953, 2024, 610, 3, '3° medio', 'A', '22505443', '6', 'F', 'VALENTINA LIZBETH', 'HUENCHE', 'CELEDÓN', 'POBL. LOS JAZMINES NORTE / AYENCUD', 'MELIPILLA', NULL, '98504008', '2007-09-07', '2024-03-01', '1900-01-01'),
(954, 2024, 610, 3, '3° medio', 'A', '21718234', '4', 'M', 'THOMAS FELIPE JAVIER', 'JAQUE', 'REYES', 'LAS ARAUCARIA LA FORESTA', 'MELIPILLA', NULL, '91761228', '2004-11-28', '2024-03-01', '1900-01-01'),
(955, 2024, 610, 3, '3° medio', 'A', '22510313', '5', 'F', 'CATALINA ALEJANDRA', 'MARDONES', 'SILVA', 'POBL. LA FORESTA / LOS COPIHUES', 'MELIPILLA', NULL, '58349565', '2007-09-24', '2024-03-01', '1900-01-01'),
(956, 2024, 610, 3, '3° medio', 'A', '22619132', '1', 'F', 'JAVIERA CONSTANZA', 'MATELUNA', 'VALLADARES', 'POBL. EL ALAMO / EL SAUCE', 'MELIPILLA', NULL, '81605159', '2008-01-23', '2024-03-01', '1900-01-01'),
(957, 2024, 610, 3, '3° medio', 'A', '22592418', 'K', 'F', 'MONSERRAT ANTONELLA', 'NAVARRO', 'OLMEDO', 'CHACRA MARÍN / LOS BELGAS', 'MELIPILLA', NULL, '89886124', '2007-12-26', '2024-03-01', '2024-04-03'),
(958, 2024, 610, 3, '3° medio', 'A', '22578641', '0', 'F', 'GIANINNA NAIARA JOANE', 'OLGUÍN', 'MIRANDA', 'VILLA FLORENCIA II PJE ANA JARPA CHAPARRO 2246', 'MELIPILLA', NULL, NULL, '2007-12-16', '2024-03-01', '1900-01-01'),
(959, 2024, 610, 3, '3° medio', 'A', '27985718', '6', 'M', 'JESUS ADONIS', 'PINEDA', 'TORREALBA', NULL, 'MELIPILLA', NULL, '82628224', '2007-05-25', '2024-03-01', '1900-01-01'),
(960, 2024, 610, 3, '3° medio', 'A', '22479743', '5', 'M', 'GERARDO ANDRÉS', 'ESCÁRATE', 'PÉREZ', 'POB. BENJAMÍN VICUÑA PJE PEDRO ALONSO 2111', 'MELIPILLA', NULL, '68733234', '2007-08-17', '2024-03-01', '2024-04-04'),
(961, 2024, 610, 3, '3° medio', 'A', '22559991', '2', 'M', 'BENJAMÍN PATRICIO', 'VERA', 'JARA', 'FLORENCIA II MARÍA CRUZAT 2286', 'MELIPILLA', NULL, NULL, '2007-11-23', '2024-03-01', '1900-01-01'),
(962, 2024, 610, 3, '3° medio', 'A', '22764616', '0', 'M', 'SAMUEL BENJAMÍN', 'MEJÍAS', 'VÁSQUEZ', 'UNIÓN DE CODIGUA S/N° CB50', 'MELIPILLA', NULL, NULL, '2008-06-29', '2024-03-01', '1900-01-01'),
(963, 2024, 610, 3, '3° medio', 'A', '22554303', '8', 'F', 'JAZMÍN STEFFANNI', 'CALIXTO', 'GODOY', 'CLAUDIO MATTE 282 POB. PADRE HURTADO', 'MELIPILLA', NULL, NULL, '2007-11-14', '2024-03-01', '2024-03-22'),
(964, 2024, 610, 3, '3° medio', 'A', '22696124', '0', 'M', 'ORLANDO ALBERTO', 'MUÑOZ', 'CHACÓN', 'AV. LIBERTAD 1667', 'MELIPILLA', NULL, NULL, '2008-03-30', '2024-03-06', '1900-01-01'),
(965, 2024, 610, 3, '3° medio', 'A', '22734328', '1', 'M', 'NICOLÁS FERNANDO', 'TORRES', 'IBÁÑEZ', 'MAPOCHO 7421', 'MELIPILLA', NULL, NULL, '2008-05-29', '2024-03-18', '1900-01-01'),
(966, 2024, 610, 3, '3° medio', 'A', '22174392', 'K', 'M', 'ELIAS IGNACIO', 'LINARES', 'MUGA', 'EL ÁLAMO 1124', 'MELIPILLA', NULL, NULL, '2006-07-19', '2024-03-22', '1900-01-01'),
(967, 2024, 610, 3, '3° medio', 'A', '100760109', 'K', 'F', 'ANGE-MARA', 'PIERRE', NULL, NULL, 'MELIPILLA', NULL, NULL, '2007-10-23', '2024-04-01', '1900-01-01'),
(968, 2024, 610, 3, '3° medio', 'A', '28411103', '6', 'F', 'WIDELINE', 'GENTILHOMME', NULL, 'LOS ESPINOS , POBL. LA FORESTS', 'MELIPILLA', NULL, '94907919', '2006-09-30', '2024-05-30', '1900-01-01'),
(969, 2024, 610, 3, '3° medio', 'B', '22254110', '7', 'M', 'JUAQUIN MISAEL', 'PÉREZ', 'VERGARA', NULL, 'SAN VICENTE', NULL, NULL, '2006-10-30', '2024-03-01', '2024-03-21'),
(970, 2024, 610, 3, '3° medio', 'B', '22450952', '9', 'M', 'BENJAMÍN ANDRÉS', 'VIDAL', 'TORRES', 'CALLE BLINDADO BLANCO Nº 878', 'CHIGUAYANTE', NULL, '93628522', '2007-07-21', '2024-03-01', '1900-01-01'),
(971, 2024, 610, 3, '3° medio', 'B', '22593813', 'K', 'M', 'MOISÉS BENJAMÍN', 'LATIN', 'HUARACÁN', 'LOS CONQUISTADORES', 'CERRO NAVIA', NULL, '99641729', '2008-01-03', '2024-03-01', '1900-01-01'),
(972, 2024, 610, 3, '3° medio', 'B', '22522801', '9', 'F', 'MARTINA ANTONIA', 'ABARCA', 'TONACCA', 'PASJ. POLO SUR Nº 2150 VILLA SAN CARLOS', 'MAIPÚ', NULL, NULL, '2007-10-09', '2024-03-01', '1900-01-01'),
(973, 2024, 610, 3, '3° medio', 'B', '22565912', '5', 'F', 'CONSTANZA PALOMA', 'MELÍN', 'CURINAO', 'OIDOR SANCHO', 'PUDAHUEL', NULL, '90417940', '2007-11-30', '2024-03-01', '1900-01-01'),
(974, 2024, 610, 3, '3° medio', 'B', '22471965', '5', 'F', 'AMARA ANDREA', 'BADILLA', 'CAMPORA', 'DARWIN FERNANDEZ 691', 'QUILICURA', NULL, NULL, '2007-08-05', '2024-03-01', '1900-01-01'),
(975, 2024, 610, 3, '3° medio', 'B', '22564248', '6', 'M', 'MAURICIO IVAN', 'SEPÚLVEDA', 'PACHECO', 'PASAJE 1 5416 VILLA OSCAR CASTRO', 'RENCA', NULL, NULL, '2007-11-28', '2024-03-01', '1900-01-01'),
(976, 2024, 610, 3, '3° medio', 'B', '22411296', '3', 'F', 'ISIDORA BELÉN', 'MEZA', 'ORTEGA', 'LOMAS DE MANSO / BLOCK', 'MELIPILLA', NULL, '83278023', '2007-06-01', '2024-08-20', '1900-01-01'),
(977, 2024, 610, 3, '3° medio', 'B', '100448471', '8', 'M', 'JHACIEL', 'ALCOSER', 'HUAYLLAS', NULL, 'MELIPILLA', NULL, '79503594', '2007-08-05', '2024-03-01', '1900-01-01'),
(978, 2024, 610, 3, '3° medio', 'B', '22753308', '0', 'M', 'BENJAMÍN ALFREDO', 'ALONSO', 'GONZÁLEZ', 'VILLA MERCED III. LAGO GRIS 1675', 'MELIPILLA', NULL, '74426180', '2008-06-24', '2024-03-01', '1900-01-01'),
(979, 2024, 610, 3, '3° medio', 'B', '22411257', '2', 'F', 'MARÍA IGNACIA ANGELINA', 'ARTIGAS', 'CONTRERAS', 'LAS ARAUCARIAS 1746 POB. LA FORESTA', 'MELIPILLA', NULL, NULL, '2007-06-03', '2024-03-01', '1900-01-01'),
(980, 2024, 610, 3, '3° medio', 'B', '21517273', '2', 'F', 'MARÍA JESÚS', 'BUSTOS', 'CERDA', 'POBL. LA FORESTA / LOS PEUMOS', 'MELIPILLA', NULL, '87803562', '2004-02-24', '2024-03-01', '1900-01-01'),
(981, 2024, 610, 3, '3° medio', 'B', '22629319', '1', 'M', 'MANUEL IGNACIO', 'CÁCERES', 'VALENZUELA', 'FLORENCIA II PJE SARA QUEZADA 2242', 'MELIPILLA', NULL, '83421310', '2008-01-20', '2024-03-01', '1900-01-01'),
(982, 2024, 610, 3, '3° medio', 'B', '22489304', '3', 'F', 'KRISHNA ABATA', 'CARO', 'PIZARRO', 'POBL. PABLO LIZAMA / OBISPO GUILLERMO VERA', 'MELIPILLA', NULL, '90367045', '2007-09-01', '2024-03-01', '1900-01-01'),
(983, 2024, 610, 3, '3° medio', 'B', '100523344', '1', 'F', 'MELINA', 'CARRIZO', 'SANDOVAL', NULL, 'MELIPILLA', 'dilmarcarrizo962@gmail.com', '44161486', '2006-10-28', '2024-03-01', '1900-01-01'),
(984, 2024, 610, 3, '3° medio', 'B', '100443087', '1', 'M', 'CINTHIA', 'CONDORI', 'MAMANI', NULL, 'MELIPILLA', NULL, '79606252', '2006-11-20', '2024-03-01', '1900-01-01'),
(985, 2024, 610, 3, '3° medio', 'B', '22340739', '0', 'F', 'JAVIERA ANTONIA', 'CUEVAS', 'SERRANO', 'POBL. RENACER / GRICELDA ALVAREZ', 'MELIPILLA', NULL, '75248101', '2007-02-27', '2024-03-01', '1900-01-01'),
(986, 2024, 610, 3, '3° medio', 'B', '22548685', '9', 'F', 'DENISSE ALEJANDRA', 'FUENTES', 'MORENO', 'LOMAS DE MANSO / BLOCK', 'MELIPILLA', NULL, '63285824', '2007-11-11', '2024-03-01', '1900-01-01'),
(987, 2024, 610, 3, '3° medio', 'B', '22267306', '2', 'F', 'AMPARO ISABEL', 'LANDAETA', 'GARRIDO', 'FLORENCIA I LASTENIA ALVAREZ 2291', 'MELIPILLA', NULL, NULL, '2006-11-18', '2024-03-01', '1900-01-01'),
(988, 2024, 610, 3, '3° medio', 'B', '22137950', '0', 'M', 'TOMAS IGNACIO', 'NÚÑEZ', 'PIZARRO', NULL, 'MELIPILLA', NULL, NULL, '2006-06-12', '2024-03-01', '1900-01-01'),
(989, 2024, 610, 3, '3° medio', 'B', '22476568', '1', 'M', 'OLIVER ANDRÉS', 'PATIÑO', 'HUERTA', 'PAJARITOS 1461 POB. BELLA ESPERANZA', 'MELIPILLA', NULL, '96005814', '2007-08-14', '2024-03-01', '1900-01-01'),
(990, 2024, 610, 3, '3° medio', 'B', '100648251', '8', 'M', 'JUAN JOSE', 'PESCADOR', 'CARMONA', NULL, 'MELIPILLA', NULL, '74448129', '2007-07-04', '2024-03-01', '1900-01-01'),
(991, 2024, 610, 3, '3° medio', 'B', '22491367', '2', 'F', 'ALONDRA CONSTANZA', 'RODRÍGUEZ', 'FUENTES', NULL, 'MELIPILLA', NULL, NULL, '2007-09-05', '2024-03-01', '1900-01-01'),
(992, 2024, 610, 3, '3° medio', 'B', '22740990', '8', 'F', 'ANAÍS CONSTANZA', 'SANTIBÁÑEZ', 'HERRERA', 'ORTUZAR', 'MELIPILLA', NULL, NULL, '2008-06-12', '2024-03-01', '1900-01-01'),
(993, 2024, 610, 3, '3° medio', 'B', '100646580', 'K', 'F', 'MIRJULY JUDITH', 'SARACHE', 'RAMIREZ', 'LOMAS DEL MANZANO', 'MELIPILLA', 'ramirezmirla85@hotmail.com', '82250474', '2007-08-05', '2024-03-01', '1900-01-01'),
(994, 2024, 610, 3, '3° medio', 'B', '22144651', '8', 'F', 'INGRID SOFIA', 'VELÁSQUEZ', 'CABRERA', 'PUANGUE / VILLA JAZIGI / SITIO', 'MELIPILLA', NULL, '59596952', '2006-06-13', '2024-03-01', '1900-01-01'),
(995, 2024, 610, 3, '3° medio', 'B', '100727904', 'K', 'F', 'MAIDA', 'TARDIO', 'RIVERA', NULL, 'MELIPILLA', NULL, NULL, '2007-05-27', '2024-03-01', '2024-04-15'),
(996, 2024, 610, 3, '3° medio', 'B', '22452419', '6', 'F', 'CATALINA ANDREA', 'JARA', 'OLIVARES', 'POBL. BENJAMÍN VICUÑA / RENÉ YÁÑEZ', 'MELIPILLA', NULL, '66637408', '2007-07-19', '2024-03-01', '1900-01-01'),
(997, 2024, 610, 3, '3° medio', 'B', '100732666', '8', 'M', 'JHERALDINET', 'VEDIA', 'ORDOÑEZ', NULL, 'MELIPILLA', NULL, NULL, '2007-12-17', '2024-03-08', '1900-01-01'),
(998, 2024, 610, 3, '3° medio', 'B', '22261808', '8', 'F', 'EMILY CATRINA', 'SMINK', 'FARÍAS', NULL, 'MELIPILLA', NULL, NULL, '2006-11-15', '2024-03-05', '1900-01-01'),
(999, 2024, 610, 3, '3° medio', 'B', '22674748', '6', 'M', 'GERALD ALEJANDRO', 'BECERRA', 'RUBILAR', NULL, 'MELIPILLA', NULL, '64275443', '2008-02-29', '2024-03-18', '1900-01-01'),
(1000, 2024, 610, 3, '3° medio', 'B', '22573445', '3', 'M', 'WALTER DAVID', 'ALVARADO', 'JIMÉNEZ', 'PASAJE LUS BORREMANS 199 POBLACION BENJAMIN ULLOA', 'MELIPILLA', NULL, NULL, '2007-12-04', '2024-04-01', '1900-01-01'),
(1001, 2024, 610, 3, '3° medio', 'B', '22447542', 'K', 'F', 'CONSTANZA CATALINA', 'SÁNCHEZ', 'LAGOS', 'HURTADO', 'MELIPILLA', NULL, '8314991', '2007-07-13', '2024-05-14', '1900-01-01'),
(1002, 2024, 610, 3, '3° medio', 'B', '22020284', '4', 'M', 'PAHOLO ALEJANDRO', 'ESPINOZA', 'DONOSO', 'PBL. CHILE CHEPICAS 2145', 'ARICA', NULL, '62813882', '2006-01-10', '2024-03-05', '2024-07-05'),
(1003, 2024, 610, 4, '4° medio', 'A', '22336932', '4', 'F', 'MADELIN CAMIL', 'GONZÁLEZ', 'GUZMÁN', 'POBL. CLOTARIO BLEST / EMILIO RECABARREN', 'SAN ANTONIO', NULL, '54747161', '2007-02-11', '2024-03-01', '1900-01-01'),
(1004, 2024, 610, 4, '4° medio', 'A', '22314575', '2', 'F', 'DENISE FABIOLA', 'GUTIÉRREZ', 'GONZÁLEZ', 'RIO DE JANEIRO', 'LO PRADO', NULL, '91797095', '2007-01-25', '2024-03-01', '1900-01-01'),
(1005, 2024, 610, 4, '4° medio', 'A', '22078107', '0', 'F', 'MICAELA DE LA PAZ', 'LÓPEZ', 'NILSSON', 'SAN ALBERTO', 'LO PRADO', NULL, '82189840', '2006-03-21', '2024-03-01', '1900-01-01'),
(1006, 2024, 610, 4, '4° medio', 'A', '22246544', '3', 'F', 'NAOMÍ IGNACIA', 'MARTÍNEZ', 'ROBLES', NULL, 'MAIPÚ', NULL, NULL, '2006-10-24', '2024-03-01', '1900-01-01'),
(1007, 2024, 610, 4, '4° medio', 'A', '22378832', '7', 'F', 'SOFÍA ANTONIA PAOLA', 'CARRASCO', 'ARANEDA', 'EL MEMBRILLAR', 'PUDAHUEL', 'SOFIAANTONIA1@HOTMAIL.COM', '76268859', '2007-04-17', '2024-03-01', '1900-01-01'),
(1008, 2024, 610, 4, '4° medio', 'A', '21969167', 'K', 'F', 'CONSTANZA ESTRELLA', 'ORIAS', 'VALENZUELA', 'AFRODITA 6991 POB. LO VELASQUEZ 1', 'RENCA', NULL, '92641348', '2005-10-28', '2024-03-01', '1900-01-01'),
(1009, 2024, 610, 4, '4° medio', 'A', '22268457', '9', 'F', 'ISIDORA DOMINIQUE', 'BARRAZA', 'VALDÉS', 'POBL. LOS JAZMINES /JOSÉ PEROTTI', 'MELIPILLA', NULL, '85535155', '2006-11-23', '2024-03-01', '1900-01-01'),
(1010, 2024, 610, 4, '4° medio', 'A', '27420815', '5', 'M', 'JIMMY', 'CABRITA', 'MORALES', NULL, 'MELIPILLA', NULL, NULL, '2006-09-18', '2024-03-01', '1900-01-01'),
(1011, 2024, 610, 4, '4° medio', 'A', '22356823', '8', 'F', 'AYLEEN ANTONIA', 'CARVALLO', 'ECHEVERRÍA', 'ALTOS DE CANTILLANA / ÑIRE', 'MELIPILLA', NULL, '93308067', '2007-03-20', '2024-03-01', '1900-01-01'),
(1012, 2024, 610, 4, '4° medio', 'A', '21951453', '0', 'F', 'CONSTANZA BELÉN', 'MARDONES', 'VERA', 'POBL. PADRE DEMETRIO / PEDRO MENDEZ', 'MELIPILLA', NULL, '83990163', '2005-09-03', '2024-03-01', '1900-01-01'),
(1013, 2024, 610, 4, '4° medio', 'A', '22239768', '5', 'F', 'MAITE PATRICIA', 'MUÑOZ', 'GONZÁLEZ', 'JOSE HORMAZABAL 311 VILLA COLONIAL 2', 'MELIPILLA', NULL, NULL, '2006-10-20', '2024-03-01', '1900-01-01'),
(1014, 2024, 610, 4, '4° medio', 'A', '22087141', 'K', 'F', 'ARACELI ANTONELLA', 'NORAMBUENA', 'TRUJILLO', 'LOS CARDENALES LAS ORQUIDEAS Nº1253', 'MELIPILLA', NULL, NULL, '2006-03-31', '2024-03-01', '1900-01-01'),
(1015, 2024, 610, 4, '4° medio', 'A', '22283353', '1', 'M', 'JAVIER IGNACIO', 'NÚÑEZ', 'VILCHES', 'LAS ARAUCARIAS 1764, LA FORESTA', 'MELIPILLA', NULL, '3798072', '2006-12-16', '2024-03-01', '1900-01-01'),
(1016, 2024, 610, 4, '4° medio', 'A', '21782469', '9', 'F', 'MONSERRAT DE LOS ANGELES', 'OÑATE', 'AGUIRRE', 'VICHUQUÉN Nº 1919 POB.LOS LAGOS III', 'MELIPILLA', NULL, NULL, '2005-02-05', '2024-03-01', '1900-01-01'),
(1017, 2024, 610, 4, '4° medio', 'A', '21958219', '6', 'F', 'EMILY IGNACIA', 'SANTIBÁÑEZ', 'MANZO', 'POB. EMOS\Z LA ILUSIÓN 1093', 'MELIPILLA', NULL, '88548580', '2005-10-11', '2024-03-01', '1900-01-01'),
(1018, 2024, 610, 4, '4° medio', 'A', '22298415', '7', 'F', 'YARELA SARAY', 'TAPIA', 'NÚÑEZ', 'PASAJE SANTA TERESA 919', 'MELIPILLA', NULL, '83777264', '2007-01-04', '2024-03-01', '1900-01-01'),
(1019, 2024, 610, 4, '4° medio', 'A', '22345022', '9', 'M', 'GABRIEL ALONSO', 'CASTAÑEDA', 'NÚÑEZ', 'ARTURO PRAT Nº 914', 'MELIPILLA', NULL, NULL, '2007-02-27', '2024-03-01', '1900-01-01'),
(1020, 2024, 610, 4, '4° medio', 'A', '22044669', '7', 'F', 'ANAÍS ANDREA', 'CASTRO', 'DÍAZ', 'CHACRA MARÍN / LOS IRLANDESES', 'MELIPILLA', NULL, '62200369', '2006-02-10', '2024-03-01', '1900-01-01'),
(1021, 2024, 610, 4, '4° medio', 'A', '22055618', '2', 'F', 'ALEXANDRA STEPHANIE', 'CISTERNAS', 'PADILLA', 'POBL. PABLO LIZAMA / PAPA JUAN PABLO II', 'MELIPILLA', NULL, '74445814', '2006-02-10', '2024-03-01', '1900-01-01'),
(1022, 2024, 610, 4, '4° medio', 'A', '22055606', '9', 'F', 'MARIA PAZ', 'CISTERNAS', 'PADILLA', 'POBL. PABLO LIZAMA /', 'MELIPILLA', NULL, '74445814', '2006-02-10', '2024-03-01', '1900-01-01'),
(1023, 2024, 610, 4, '4° medio', 'A', '22398241', '7', 'F', 'ALLISON DANAE', 'DELGADO', 'DÍAZ', 'LOMAS DE MANSO / BLOCK', 'MELIPILLA', NULL, '89067797', '2007-03-17', '2024-03-01', '1900-01-01'),
(1024, 2024, 610, 4, '4° medio', 'A', '22252030', '4', 'F', 'CATALINA ANTONIA', 'FRÍAS', 'ULLOA', 'POBL. LOS LAGOS I / LAGO CHAPO', 'MELIPILLA', NULL, '8325374', '2006-11-01', '2024-03-01', '1900-01-01'),
(1025, 2024, 610, 4, '4° medio', 'A', '21947903', '4', 'F', 'MICHELLE GERALDINE', 'GONZÁLEZ', 'NÚÑEZ', 'ARZA 1021', 'MELIPILLA', NULL, NULL, '2005-09-26', '2024-03-01', '1900-01-01'),
(1026, 2024, 610, 4, '4° medio', 'A', '21622800', '6', 'F', 'AYLEEN ESTEFANÍA', 'INOSTROZA', 'JIMÉNEZ', 'LOMAS DE MANZO III', 'MELIPILLA', NULL, '88527348', '2004-07-18', '2024-03-01', '1900-01-01'),
(1027, 2024, 610, 4, '4° medio', 'A', '26725943', '7', 'F', 'ALBA LUCIA', 'ORDAZ', 'CETRONE', NULL, 'MELIPILLA', NULL, NULL, '2006-05-06', '2024-03-07', '1900-01-01'),
(1028, 2024, 610, 4, '4° medio', 'A', '100748130', '2', 'M', 'ANGEL', 'CARTAGENA', 'TIVY', NULL, 'MELIPILLA', NULL, NULL, '2006-09-07', '2024-03-11', '1900-01-01');
INSERT INTO `matricula` (`id`, `ano`, `cod_tipo_ensenanza`, `cod_grado`, `desc_grado`, `letra_curso`, `run`, `digito_ver`, `genero`, `nombres`, `apellido_paterno`, `apellido_materno`, `direccion`, `comuna_residencia`, `email`, `telefono`, `fecha_nacimiento`, `fecha_incorporacion_curso`, `fecha_retiro`) VALUES
(1029, 2024, 610, 4, '4° medio', 'A', '22293427', '3', 'F', 'RENATA ARACELLI', 'POZO', 'PINTO', 'LA LINEA S/N', 'ALHUÉ', NULL, NULL, '2006-12-27', '2024-03-01', '1900-01-01'),
(1030, 2024, 610, 4, '4° medio', 'A', '22193278', '1', 'F', 'PAULETTE ANAÍS', 'SALINAS', 'TORRES', 'IBACACHE BAJO 91', 'MARÍA PINTO', NULL, NULL, '2006-08-13', '2024-03-01', '1900-01-01'),
(1031, 2024, 610, 4, '4° medio', 'B', '22160060', '6', 'F', 'GEMIMA ANTONIA', 'HENRÍQUEZ', 'LÓPEZ', NULL, 'COPIAPÓ', NULL, NULL, '2006-07-13', '2024-03-20', '1900-01-01'),
(1032, 2024, 610, 4, '4° medio', 'B', '26853623', 'K', 'F', 'DJED NIFLORE', 'JN LOUIS', NULL, 'DIEGO ECHEVBERIA', 'QUILLOTA', NULL, '98427591', '2006-10-27', '2024-03-20', '2024-05-14'),
(1033, 2024, 610, 4, '4° medio', 'B', '22242918', '8', 'F', 'TAIS ANTONIA', 'ÁLVAREZ', 'GUTIÉRREZ', 'VILLA COLONIAL II. VALENTIN SILVA 311', 'MELIPILLA', NULL, '77289652', '2006-10-25', '2024-03-01', '1900-01-01'),
(1034, 2024, 610, 4, '4° medio', 'B', '27559264', '1', 'F', 'RUDDY ANAHIS', 'ANDRADE', 'SUAREZ', NULL, 'MELIPILLA', NULL, '64824936', '2006-09-01', '2024-03-01', '1900-01-01'),
(1035, 2024, 610, 4, '4° medio', 'B', '22195439', '4', 'F', 'CAROLINA ISABEL', 'BASTÍAS', 'LUNA', 'LOS ESPINOS 198', 'MELIPILLA', NULL, NULL, '2006-08-29', '2024-03-01', '1900-01-01'),
(1036, 2024, 610, 4, '4° medio', 'B', '22366556', 'K', 'F', 'PAULA VALENTINA', 'CANCINO', 'CAMPOS', NULL, 'MELIPILLA', NULL, '96512606', '2007-04-04', '2024-03-01', '1900-01-01'),
(1037, 2024, 610, 4, '4° medio', 'B', '21999965', '8', 'M', 'JOSUE JOAQUIN', 'CÉSPEDES', 'MOYA', 'OBISPO GUILLERMO VERA 2109', 'MELIPILLA', NULL, NULL, '2005-12-09', '2024-03-01', '1900-01-01'),
(1038, 2024, 610, 4, '4° medio', 'B', '22130555', '8', 'M', 'JAVIER ALEJANDRO', 'GUAICO', 'MESA', 'EL CARMEN 350', 'MELIPILLA', NULL, NULL, '2006-05-30', '2024-03-01', '1900-01-01'),
(1039, 2024, 610, 4, '4° medio', 'B', '21973144', '2', 'M', 'VÍCTOR MANUEL', 'HILAMANTE', 'PALOMINOS', 'RAMON NUÑES N38 BV MACKENA', 'MELIPILLA', NULL, '84388773', '2005-10-05', '2024-03-01', '1900-01-01'),
(1040, 2024, 610, 4, '4° medio', 'B', '22186665', '7', 'F', 'KARLA VICTORIA', 'LANDEROS', 'DONOSO', 'ALTOS DE CANTILLANA / AV. CIRCUNVALACIÓN', 'MELIPILLA', NULL, '88634254', '2006-08-17', '2024-03-01', '1900-01-01'),
(1041, 2024, 610, 4, '4° medio', 'B', '22137604', '8', 'F', 'CATALINA ALEJANDRA', 'MEZA', 'QUIROZ', 'POBL.BERNARDO LEIGHTON /RAFAEL MORANDÉ', 'MELIPILLA', NULL, '77706588', '2006-05-26', '2024-03-01', '1900-01-01'),
(1042, 2024, 610, 4, '4° medio', 'B', '100640123', '2', 'F', 'YEXIMAR MARIA', 'SEMPRUM', 'COLMENARES', NULL, 'MELIPILLA', NULL, NULL, '2007-03-29', '2024-03-01', '1900-01-01'),
(1043, 2024, 610, 4, '4° medio', 'B', '28084168', '4', 'M', 'NEHEMIAS MOISES', 'SUAREZ', 'SOLIS', NULL, 'MELIPILLA', NULL, '64824936', '2006-06-28', '2024-03-01', '1900-01-01'),
(1044, 2024, 610, 4, '4° medio', 'B', '22260463', 'K', 'F', 'CONSTANZA JAVIERA', 'VALDÉS', 'SOTO', 'POBL. FLORENCIA II / LORGIO DOÑABEITÍA', 'MELIPILLA', NULL, '85748163', '2006-11-13', '2024-03-01', '1900-01-01'),
(1045, 2024, 610, 4, '4° medio', 'B', '22248942', '3', 'M', 'DAVID XAVIER', 'VEDIA', 'SANTIBÁÑEZ', 'PJE.JAVIER ROMERO BLOCK1446 DEPTO. B 33', 'MELIPILLA', NULL, '84979535', '2006-10-30', '2024-03-01', '1900-01-01'),
(1046, 2024, 610, 4, '4° medio', 'B', '27177486', '9', 'M', 'GABRIEL EMILIO', 'VERNA', 'FUENTES', NULL, 'MELIPILLA', NULL, NULL, '2007-03-06', '2024-03-01', '1900-01-01'),
(1047, 2024, 610, 4, '4° medio', 'B', '22337866', '8', 'F', 'AGUSTINA BELÉN', 'WASTAVINO', 'GONZÁLEZ', 'PARDO', 'MELIPILLA', NULL, '8323713', '2007-02-23', '2024-03-01', '1900-01-01'),
(1048, 2024, 610, 4, '4° medio', 'B', '22409662', '3', 'F', 'CONSTANZA MARÍA ELBA', 'MIRANDA', 'OLGUÍN', 'POBL. BENJAMÍN VICUÑA / RAMÓN VALDIVIESO', 'MELIPILLA', NULL, '62989328', '2007-05-31', '2024-03-01', '1900-01-01'),
(1049, 2024, 610, 4, '4° medio', 'B', '22035080', '0', 'M', 'ANGEL GABRIEL', 'MONTENEGRO', 'MARDONES', 'FLORENCIO ASTUDILLO 200, PADRE DEMETRIO BRAVO', 'MELIPILLA', NULL, NULL, '2006-01-30', '2024-03-01', '1900-01-01'),
(1050, 2024, 610, 4, '4° medio', 'B', '22312603', '0', 'F', 'JEAMY ANNE', 'MORALES', 'MARTÍNEZ', 'AV. DOS 29 63', 'MELIPILLA', NULL, NULL, '2007-01-21', '2024-03-01', '1900-01-01'),
(1051, 2024, 610, 4, '4° medio', 'B', '22243971', 'K', 'F', 'TIARE PAZ', 'MORENO', 'VARGAS', 'POBL. ILUSIONES COMPARTIDAS / JUAN FRANCISCO GONZALEZ', 'MELIPILLA', NULL, '54939793', '2006-10-26', '2024-03-01', '1900-01-01'),
(1052, 2024, 610, 4, '4° medio', 'B', '22019217', '2', 'F', 'ISIDORA ANTONIA', 'NÚÑEZ', 'CERDA', 'POBL. LA FORESTA / LOS PEUMOS', 'MELIPILLA', NULL, '90105180', '2006-01-09', '2024-03-01', '1900-01-01'),
(1053, 2024, 610, 4, '4° medio', 'B', '21942125', '7', 'F', 'VAITIARE MONTSERRAT', 'QUINTEROS', 'TORRES', 'PJE. ROLANDO NUÑEZ 6', 'MELIPILLA', NULL, '84611651', '2005-09-25', '2024-03-01', '1900-01-01'),
(1054, 2024, 610, 4, '4° medio', 'B', '22271284', 'K', 'M', 'SEBASTIÁN ALFONSO', 'HUAIQUIL', 'ARANEDA', 'RENÉ VIO VALDIVIESO 18', 'MELIPILLA', NULL, NULL, '2006-11-27', '2024-03-01', '1900-01-01'),
(1055, 2024, 610, 4, '4° medio', 'B', '21904606', '5', 'F', 'CLARA BETZABETH ALEXANDRA', 'SALFATE', 'RAMÍREZ', NULL, 'EL MONTE', NULL, NULL, '2005-08-04', '2024-03-01', '1900-01-01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_09_22_183010_create_expedientes_table', 2),
(8, '2024_09_30_162141_create_derivacions_table', 3),
(9, '2024_10_08_151638_create_entrevistas_table', 4),
(10, '2024_10_14_143551_create_motivos_entrevista_table', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivos_entrevista`
--

CREATE TABLE `motivos_entrevista` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `motivos_entrevista`
--

INSERT INTO `motivos_entrevista` (`id`, `motivo`, `created_at`, `updated_at`) VALUES
(1, 'ACADÉMICA', NULL, NULL),
(2, 'CONDUCTUAL', NULL, NULL),
(3, 'INTERPERSONAL', NULL, NULL),
(4, 'FAMILIAR', NULL, NULL),
(5, 'AFECTIVA-EMOCIONAL', NULL, NULL),
(6, 'ESTUDIANTE NUEVO', NULL, NULL),
(7, 'CARTA DE COMPROMISO', NULL, NULL),
(8, 'RETRASO REITERADO', '2024-10-15 02:40:18', '2024-10-15 02:40:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9mGGlnCHwJ8PDLQLUTjkvPYweM5K4eceV6ku8pzS', NULL, '177.128.113.81', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoic2ltYmNCc3IwN3JsVEticTlHb2tpVVhtNGVGTFRJNVZ5emN3enNsUCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo3NDoiaHR0cHM6Ly9jbXZhcHAuY2wvcHJveWVjdG9fY2Fwc3Rvbl9sYXJhdmVsL3B1YmxpYy9lcXVpcG8tZGlyZWN0aXZvLXByb2ZpbGUiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo1NToiaHR0cHM6Ly9jbXZhcHAuY2wvcHJveWVjdG9fY2Fwc3Rvbl9sYXJhdmVsL3B1YmxpYy9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1726197886),
('m1sMsoGGgOh2bSPG84yGefIVld9T70psand56i8M', 94, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36 OPR/113.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMlJJNUJWY0FrTWw4SklYZncwOVhzajdlZEN3eUZQRDFURXBLMm1UTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9mZXNvcmVzLWplZmVzLXByb2ZpbGUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo5NDt9', 1728418173),
('RXMipnDKQmAiny2Za7zSl08LHJsqt7mCMMpopXdN', 93, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36 OPR/113.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM3o3dVhVRUtPcmVCS2dSNzJqZDRDb21IN1FuRWlWTTZFcVI1VnBTcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjkzO30=', 1728350261),
('Vnlue1OwXWh1jCjWOEXFy4LQ1b0vcphxnCn1mljG', NULL, '177.128.113.81', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTlE3QmY1RGE3akhFRmRad2FNTHl6S3pocmdONHZnS0laOGZPdW9rVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vY212YXBwLmNsL3Byb3llY3RvX2NhcHN0b25fbGFyYXZlbC9wdWJsaWMvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjc0OiJodHRwczovL2NtdmFwcC5jbC9wcm95ZWN0b19jYXBzdG9uX2xhcmF2ZWwvcHVibGljL2VxdWlwby1kaXJlY3Rpdm8tcHJvZmlsZSI7fX0=', 1726197166);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `id_category` int(11) NOT NULL,
  `role` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `lockout_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `id_category`, `role`, `email`, `image`, `password`, `failed_attempts`, `lockout_time`) VALUES
(1, 'Cristian', 'Espinoza', 1, 'COORDINADOR PEDAGÓGICO TÉCNICO PROFESIONAL', 'cristian.espinoza@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/EQUIPO_DIRECTIVO/Espinoza Cristian COORDINADOR PEDAGÓGICO TÉCNICO PROFESIONAL.jpg', '$2y$10$ZQktaadNMiqR61ND/0CcWebXJWdUumsE.la6RyUR4gvbiYm1MAXzK', 0, NULL),
(2, 'Laura', 'Fernández', 1, 'ADMINISTRADORA', 'laura.fernandez@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/EQUIPO_DIRECTIVO/Fernández Laura ADMINISTRADORA.jpg', '$2y$10$6wvWbWrwaszF.5fINTRuSOvD/HR36v8NiEMfDmaoF1Xq0Aj9Po1Fe', 0, NULL),
(3, 'Artemiza', 'Hidalgo', 1, 'COORDINADORA PEDAGÓGICA 2° CICLO', 'artemiza.hidalgo@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/EQUIPO_DIRECTIVO/Hidalgo Artemiza COORDINADORA PEDAGÓGICA 2° CICLO.jpg', '$2y$10$JY1CdMn9BB.R9CWMJgBxYOZA/bve18qG1E2kHrBsY51vqo999impq', 0, NULL),
(4, 'Lorena', 'Herrera', 1, 'COORDINADORA CONVIVENCIA ESCOLAR', 'lorena.herrera@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/EQUIPO_DIRECTIVO/Lorena Herrera COORDINADORA CONVIVENCIA ESCOLAR.jpg', '$2y$10$UMoHLhRqzyZR8W5J1Q156eokDbbv84sddLM07Y/rVDJEyw5W0Fg5q', 0, NULL),
(5, 'Magaly', 'Retamal', 1, 'COORDINADORA PASTORAL', 'magaly.retamal@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/EQUIPO_DIRECTIVO/Retamal Magaly COORDINADORA PASTORAL.png', '$2y$10$U4pTYWTAVjUKnhyKHBeOzubmRX9d61xxrAbxt8mwd3j8tNqsydbA2', 0, NULL),
(6, 'Jessica', 'Pereira', 1, 'DIRECTORA 2° CICLO', 'jessica.pereira@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/EQUIPO_DIRECTIVO/Pereira Jessica DIRECTORA 2° CICLO.jpg', '$2y$10$Ac/lYsC./DTx40v.JNQqBOuxxH2SueTMxmBQPYL0TyArY9CQDE5BG', 0, NULL),
(7, 'Nicole', 'Pinto', 1, 'DIRECTORA COLEGIO', 'nicole.pinto@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/EQUIPO_DIRECTIVO/Pinto Nicole DIRECTORA COLEGIO.jpg', '$2y$12$rK/XEmBxBofKSc.YfvDQ8.J/zQPuEWei/./so0/qeil5FjAFCVAxu', 3, '2024-10-04 16:04:45'),
(8, 'Karen', 'Quiroga', 1, 'DIRECTORA 1° CICLO', 'karen.quiroga@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/EQUIPO_DIRECTIVO/Quiroga Karen DIRECTORA 1° CICLO.jpg', '$2y$10$FrA5m5a.Z5GCYDb4bNHqm.WVWLbcy1koW6Udg/CnXotJeVzSy3QVW', 0, NULL),
(10, 'Mariela', 'Contreras', 1, 'PASTORAL', 'mariela.contreras@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/EQUIPO_DIRECTIVO/Religiosa Contreras Mariela PASTORAL.jpg', '$2y$10$LV.rj.F7OgFaT8f2CQ4IneYnYiTZ.dQDOMY.3lUP3JxnUUogqB6cK', 0, NULL),
(11, 'Yesenia', 'Mendoza', 1, 'COORDINADORA PEDAGÓGICA 1° CICLO', 'yesenia.mendoza@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/EQUIPO_DIRECTIVO/Yesenia Mendoza COORDINADORA PEDAGÓGICA 1° CICLO.jpg', '$2y$10$eJzLIQY0t2CkOOV6homShebUdmuIuAw9Bm5N5Dk69053dzEBK/GuK', 0, NULL),
(12, 'Isabel', 'Rojas', 1, 'COORDINADORA PIE', 'isabel.rojas@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Rojas Isabel.jpg', '$2y$10$RvlKdB8GNLQA9eaxeFtC8uPAzO33F6KndM0QBxYzA8s6lz1O9NdeG', 0, NULL),
(13, 'Lorena', 'Herrera', 2, 'COORDINADORA CONVIVENCIA ESCOLAR', 'lorena.herrera@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/EQUIPO_DIRECTIVO/Lorena Herrera COORDINADORA CONVIVENCIA ESCOLAR.jpg', '$2y$10$wXg9KMl2bg8Et8C5JFgdt.HBLqQlVvmJhhGftTjNDrWDRmVlGz.U.', 0, NULL),
(14, 'Daniela', 'Cabezas', 2, 'TRABAJADORA SOCIAL 2° CICLO', 'daniela.cabezas@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/CONVIVENCIA_ESCOLAR/Daniela Cabezas TRABAJADORA SOCIAL 2° CICLO.jpg', '$2y$10$uCxz4.DqN1Pbtlg3vqmz4OjfDH06IWcNfutGPnA9Owzkk0qR.kMAe', 0, NULL),
(15, 'Caren', 'Díaz', 2, 'ORIENTADORA', 'caren.diaz@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/CONVIVENCIA_ESCOLAR/Díaz Caren ORIENTADORA.jpg', '$2y$10$gLiF9H1rug/qjYebSWhGb.RecI8yuK.MZvoijhEi115R1WZJkDwPm', 0, NULL),
(16, 'Carolina', 'González', 2, 'TRABAJADORA SOCIAL 1° CICLO', 'carolina.gonzalez@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/CONVIVENCIA_ESCOLAR/González Carolina TRABAJADORA SOCIAL 1° CICLO.jpg', '$2y$10$mJprEIbxv7FVPacxoEFOUuDEiD6yq1mDz2Da2Ev2S0Zg2/qzghOGu', 0, NULL),
(17, 'Vannia', 'Lisboa', 2, 'PSICÓLOGA 2° CICLO', 'vannia.lisboa@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/CONVIVENCIA_ESCOLAR/Lisboa Vannia PSICOLOGA 2° CICLO.jpg', '$2y$10$.wR2DRaEGVzlF3UMNKtDkeDg7dd6daU5OMXQXcybKsQe4dOhArLge', 0, NULL),
(18, 'Zunilda', 'Caullán', 3, 'Prekinder-A', 'zunilda.caullan@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Caullán Zunilda PL PK-A.jpg', '$2y$10$45yE8EdGc2BxpkN8D1hAfOFPJNPzw71Qhz460R2ejGpwRQEXWCxA2', 0, NULL),
(19, 'Sylvia', 'Orellana', 3, 'Prekinder-B', 'sylvia.orellana@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Sylvia Orellana PJ PK-B.jpg', '$2y$10$HmGMEZMQr3e2gz.s1.bfkuMo4BKO7zFxA4IUyrgX.nPpPq.fkMvA6', 0, NULL),
(20, 'Cynthia', 'Vásquez', 3, 'Kinder-A', 'cynthia.vasquez@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Vásquez Cynthia PJ K-A.jpg', '$2y$10$/..v7R1aucHG7puKlVuwGeK4qjV38I2Wmc1GoZ2MFzQjMTTcfKLIe', 0, NULL),
(21, 'Rosa', 'Mena', 3, 'Kinder-B', 'rosa.mena@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Mena Rosa PJ K-B.jpg', '$2y$10$p.U.Cj0ESygQaDUl8fM1xOxEVURrPTfjGvf1GXHXdaP1JZHW4Csk.', 0, NULL),
(22, 'Ximena', 'Urbina', 3, '1° básico A', 'ximena.urbina@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Urbina Ximena PJ 1°A.jpg', '$2y$10$Mu.LXgvGyjMXS3tc62eMm.5MGTA8V9CIdYhdV2VI73b7RPSmr4h32', 0, NULL),
(23, 'Dina', 'Ugarte', 3, '1° básico B', 'dina.ugarte@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Ugarte Dina PJ 1!B.jpg', '$2y$10$yHyJ87cfO7bdpZy5Qyau0.5SXUeE5NCmiF/fZjNMMQwhmPLT25ZDS', 0, NULL),
(24, 'Bárbara', 'González', 3, '2° básico A', 'barbara.gonzalez@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Bárbara González PJ2°A.jpg', '$2y$10$92YVC8yFasmMFmZmXhdALuCOKQ3FWbRHqFJWG0UV4YjiSq5I8i1g6', 0, NULL),
(25, 'José', 'Urra', 3, '2° básico B', 'jose.urra@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Urra José PJ 2°B.jpg', '$2y$10$BlJvqspmXFlBEC7sV7hztumpPbuZ7fCWy0WW48uTV7PC7S.dqxkxW', 0, NULL),
(26, 'Romina', 'Velásquez', 3, '3° básico A', 'romina.velasquez@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Velásquez Romina PJ 3°A.jpg', '$2y$10$vIysryqoGhXqomRzELNh2.8pRVR.t7L/xdcAzqWFtdBYiMxb989Ra', 0, NULL),
(27, 'Jaime', 'Catalán', 3, '3° básico B', 'jaime.catalan@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Catalán Jaime PJ 3°B.jpg', '$2y$10$rqganqX7fTEfsRKNrC4yRO9FcS3Imsi893cmvIGmvOfzCqefQ7kmu', 0, NULL),
(28, 'Jonathan', 'Vidal', 3, '4° básico A', 'jonathan.vidal@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Vidal Jonathan PJ 4°A.jpg', '$2y$10$000hZMM/7mHEpYmRscik7.F1Ws6IMsr/oqIJbEXJGsAlazHY0wKF.', 0, NULL),
(29, 'Fernanda', 'Castro', 3, '4° básico B', 'fernanda.castro@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Castro Fernanda PJ 4°B.jpg', '$2y$10$UDKMocfP5GPH4UXOcGkFf.p5bWTixqtN/9wDcW1Uh3sv4alGTpkSW', 0, NULL),
(30, 'Héctor', 'Moreno', 3, '5° básico A', 'hector.moreno@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Moreno Héctor PJ 5°A.jpg', '$2y$10$/QWguqNGbOD/OZia/.szEu.mN2Uzf3c5tcoovg4yXRjttEKpzxdI2', 0, NULL),
(31, 'Sofía', 'Meza', 3, '5° básico B', 'sofia.meza@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Meza Sofía PJ 5°B.jpg', '$2y$10$heHkZ6SEKYVQfdhrKG8osOLNZfaRRUmKLWUBlY3i1pvoUOBJZUXk6', 0, NULL),
(32, 'Paulina', 'Núñez', 3, '6° básico A', 'paulina.nunez@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Paulina Núñez PJ 6°A.jpg', '$2y$10$1tFldA4BTeN9nMfKX/fNn.McdAjlsuV9i13TgrHJL5om8fl94qN1i', 0, NULL),
(33, 'Johannna', 'Rojas', 3, '6° básico B', 'johannna.rojas@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Rojas Johannna PJ 6°B.jpg', '$2y$10$6Tu7clsJ7O1.86PK3oKSSuDbS9YIRjVlM0SiMrZ8k2gbrJq714ZMW', 0, NULL),
(34, 'Priscilla', 'Santibáñez', 3, '7° básico A', 'priscilla.santibanez@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Priscilla Santibáñez PJ 7°A.jpg', '$2y$10$NyADhNr.ZM5bkUug/W6vYuPs826gSbGI9IOLvbQo4ieIqkMx3vvy6', 0, NULL),
(35, 'Cristian', 'Cárdenas', 3, '7° básico B', 'cristian.cardenas@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Cárdenas Cristian PJ 7°B.jpg', '$2y$10$3D1hXCe48x9T0YmstRUM3OWgB0yba58x740iIZKWtnirR/FpVP9/i', 0, NULL),
(36, 'Elías', 'Navarro', 3, '8° básico A', 'elias.navarro@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Navarro Elías 8°A.jpg', '$2y$10$jI5.sQfqSdd6C6XQ3K.R7.8KI9.usBCL.Y62duVQoB3iP12IWEIwq', 0, NULL),
(37, 'Melanie', 'Toledo', 3, '8° básico B', 'melanie.toledo@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Toledo Melanie 8°B.jpg', '$2y$10$JQGK9..hxt5PKaZmr8pBZOfvVCYEasxaa9tChPehh8B7erXbTYjra', 0, NULL),
(38, 'Yislaine', 'Reyes', 3, '1° Medio A', 'yislaine.reyes@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Reyes Yislaine PJ 1°MA.jpg', '$2y$10$8wUST6zEngz9wxHYHvJIVODF7kJg1Eph6TsB1sBI1xwxu7INR5Woq', 0, NULL),
(39, 'Claudia', 'Zavala', 3, '1° Medio B', 'claudia.zavala@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Zavala Claudia 1°MB.jpg', '$2y$10$rcWjgmr9z/Wfc5/OAO0UjuyVRrIM0XuWMINgJ1OSeckmP5Zxhh7dC', 0, NULL),
(40, 'Carolina', 'Cardoza', 3, '2° Medio A', 'carolina.cardoza@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Cardoza Carolina 2°MA.jpg', '$2y$10$ZwAroD8vAmQTJASupUJOkuMghMBgcf8TGYSUMdxQid7hGGSeWFGWa', 0, NULL),
(41, 'Arnaldo', 'Saavedra', 3, '2° Medio B', 'arnaldo.saavedra@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Saavedra Arnaldo 2°MB.jpg', '$2y$10$.Zi28CFIbJOlf4syDRitMOzylISdxqsERwGX8oWkCiRi86YeSScwi', 0, NULL),
(42, 'Moira', 'Monzón', 3, '3° Medio A', 'moira.monzon@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Monzón Moira 3°MA.jpg', '$2y$10$wjncc3QCcnYHfzJz6qdGoOvs4lKoPflkiwKkXmRzs8AEQK9bVw7uK', 0, NULL),
(43, 'Daniela', 'Oyarzún', 3, '3° Medio B', 'daniela.oyarzun@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Oyarzún Daniela 3°MB.jpg', '$2y$10$F08/ZHT/ltXgxy0swTgDNu6gGZ9EsJRhgQ9sNO1/yC5pPduT8dT/q', 0, NULL),
(44, 'Héctor', 'De la Cuadra', 3, '4° Medio A', 'hector.delacuadra@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/De la Cuadra Héctor 4°MA.jpg', '$2y$10$sSyxF8nQ0UBihr6Ir4ui6.AJ/X17FlT6jCPbUh9qhmutfYufZ2ENS', 0, NULL),
(45, 'Jessica', 'Vergara', 3, '4° Medio B', 'jessica.vergara@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_JEFES/Vergara Jessica 4°MB.jpg', '$2y$10$M6yNna7HL7//Si8G65vaPO0i8bOblEEEMcZkWEGjp/r6.59W996mG', 0, NULL),
(46, 'Jared', 'Araya', 4, 'Básica', 'jared.araya@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Araya Jared PROFESORA BÁSICA.jpg', '$2y$10$S.7b4T5suHMJrLBO7sKNoOZCnUFPs8oQyW7kufeJuX.6cZk6x/oI2', 0, NULL),
(47, 'Catalina', 'Artigas', 4, 'Inglés', 'catalina.artigas@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Artigas Catalina PROFESORA INGLES.jpg', '$2y$10$POw2Qgc0FS/lAl9/9LNpV.NoEVQt9G8rfBlGs.1.O.CxZjnFQsyy.', 0, NULL),
(48, 'Bryan', 'Álvarez', 4, 'Música', 'bryan.alvarez@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Bryan Álvarez PROFESOR MÚSICA.jpg', '$2y$10$bkmu1iGTtEfIjmviVIpl/u2nh7mRYuYElhO1g8xs2JCY1AIKJn.Gy', 0, NULL),
(49, 'Frank', 'Durán', 4, 'Historia', 'frank.duran@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Durán Frank PROFESOR HISTORIA.jpg', '$2y$10$yCZI6DRtP7AAkKgM55dGe.rbQHxvqmBqqV35evwPukNzjvZ5ahBQW', 0, NULL),
(50, 'Ana', 'Fariña', 4, 'Lenguaje', 'ana.farina@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Fariña Ana PROFESOR LENGUAJE.jpg', '$2y$10$I./ljKiqkkwgp/Wq37ty0.BmUe/GBGll7Kw1e3wwL5NWZQLmMmLGi', 0, NULL),
(51, 'Danilo', 'González', 4, 'Educación Física', 'danilo.gonzalez@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/González Danilo PROFESOR EDUCACIÓN FÍSICA.jpg', '$2y$10$lEM0I0wxmGT7eH5rU1GvLurqrxBB1ut0JkznkgCv.Z0mQDNhpQr6y', 0, NULL),
(52, 'Alejandra', 'Herrera', 4, 'Lenguaje', 'alejandra.herrera@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Herrera Alejandra PROFESORA LENGUAJE.jpg', '$2y$10$yRitpX6fm0WOnKuBegY5u.nYOheIDWl.k71LX4Lj7vgS5elA78I86', 0, NULL),
(53, 'Verónica', 'Jiménez', 4, 'Artes Visuales', 'veronica.jimenez@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Jiménez Verónica POFESORA ARTES VISUALES.jpg', '$2y$10$.gXEuMQBWVrscV358C6Ui.JCg8rhylMejWf4wN2yD0CN4bo/fOwVC', 0, NULL),
(54, 'Magaly', 'Ancachi', 4, 'Ciencias', 'magaly.ancachi@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Magaly Ancachi PROFESORA CIENCIAS.jpg', '$2y$10$rX8nY55tRGPG4MEaHcNdLOORKwWeTgFOyp31Fs8S1wF6PPvW0YeFK', 0, NULL),
(55, 'Mary-Carmen', 'Mena', 4, 'Lenguaje', 'marycarmen.mena@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Mena Mary-Carmen PROFESORA LENGUAJE.jpg', '$2y$10$aWe20wqMMqL8CzC7FrmzDOQ19sbTydwQfBSJPAsMisj0a1Ak2ZwXS', 0, NULL),
(56, 'Kenya', 'Prieto', 4, 'Matemáticas', 'kenya.prieto@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Prieto Kenya PROFESORA MATEMÁTICAS.jpg', '$2y$10$hN1LEKu2oPBvKUX61YoGa.OJcf2sTM/Zkht024lmlK/gSlERoIV1u', 0, NULL),
(57, 'Rosa', 'Bustamante', 4, 'Artes Visuales', 'rosa.bustamante@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Rosa Bustamante PROFESORA ARTES VISUALES.jpg', '$2y$10$efBDt3QIURVxJvmUghH9oOfcwwtS7InSgjKJSkpLOJ5LKQChKnndG', 0, NULL),
(58, 'Milka', 'Rubina', 4, 'Matemática', 'milka.rubina@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Rubina Milka PROFESORA MATEMÁTICA.jpg', '$2y$10$838V2ZC3DBvFC9gj1HiVseaUq7d8L713shOLLAJzCd2jA7axuT32G', 0, NULL),
(59, 'Gonzalo', 'Sandoval', 4, 'Inglés', 'gonzalo.sandoval@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Sandoval Gonzalo PROFESOR INGLES.jpg', '$2y$10$hbQ/jQHX5edAgLQNPZaS6eHMmx9mjHRb7as8LIAd22NR6bHlehOIW', 0, NULL),
(60, 'Pedro', 'Tapia', 4, 'Matemáticas', 'pedro.tapia@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Tapia Pedro PROFESOR MATEMÁTICAS.jpg', '$2y$10$1ESwvsN4PAubsM2YVElvO.0AfAE2RoMWtMd4B9MYoVt1MYc9/MiFO', 0, NULL),
(61, 'Hernán', 'Álvarez', 4, 'Educación Física', 'hernan.alvarez@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PROFESORES_ASIGNATURA/Álvarez Hernán PROFESOR EDUCACIÓN FÍSICA.jpg', '$2y$10$Lhm/yAzL/tVCga6Oql2KxebegTJaguAADgrGIxidvcC6gF.Gs4Zne', 0, NULL),
(62, 'María Paz', 'Abarca', 5, 'Educadora Diferencial', 'maria.paz.abarca@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/María Paz Abarca.jpg', '$2y$10$IUhdMfTPvj8c./op.yh6hObQD7mK5UPSZUeLU0YTwQwpQWSbKaVdG', 0, NULL),
(63, 'Katherine', 'Albornoz', 5, 'Técnico Diferencial', 'katherine.albornoz@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Albornoz Katherine.jpg', '$2y$10$HiTOyVoWRii.ghw7wPzUn.2JJ3nrT1OmJDhPWl0v4rUj28F79q1aa', 0, NULL),
(64, 'Ruth', 'Alvarado', 5, 'Asistente Aula', 'ruth.alvarado@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Alvarado Ruth.jpg', '$2y$10$htuqm6UulPf6ZO1VUou8suQNOiflQpLbgCgfMaQefGwyq2/RvUl82', 0, NULL),
(65, 'Ávalos', 'Sofía', 5, 'Educadora Diferencial', 'avalos.sofia@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Ávalos Sofía.jpg', '$2y$10$8jWj8XJEH.KXBbn3Bm6zDeP9aY/OQy6xnliFPtN2NTzaRTFrxDLH.', 0, NULL),
(66, 'Cecilia', 'Barrera', 5, 'Psicóloga', 'cecilia.barrera@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Cecilia Barrera.jpg', '$2y$10$fCDkmOxiDYOogYBkKjsAtult0R3ES.CxNDxhyidLSQShPZu15QsnG', 0, NULL),
(67, 'Ayleen', 'Barrientos', 5, 'Educadora Diferencial', 'ayleen.barrientos@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Ayleen Barrientos.jpg', '$2y$10$PRiU3Km1DJcIUjSCfsK4P.YyRJFcxSIpA.SKL1.0vd6Ks0LmAYc8q', 0, NULL),
(68, 'Romina', 'Calderón', 5, 'Asistente Aula', 'romina.calderon@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Romina Calderón.jpg', '$2y$10$cmrpXep3G5CqIBtV.Kl2ROB/NYzZ07VbZlMCfVKjF4l1SVSk5sE1i', 0, NULL),
(69, 'Danae', 'Carreño', 5, 'Educadora Diferencial', 'danae.carreno@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Carreño Danae.jpg', '$2y$10$hqc0gR9zuolH3pwz0lWzAuUp77EsKUmj4UfaybC7DhKDXFx5t1oIq', 0, NULL),
(70, 'Daniela', 'Cañas', 5, 'Educadora Diferencial', 'daniela.canas@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Cañas Daniela.jpg', '$2y$10$dq7YQ6u/3TmJZ16oKUV8AubdJxXZ3aYKENL2aMAPl.5VXCHnMk1JC', 0, NULL),
(71, 'Alicia', 'Escobar', 5, 'Educadora Diferencial', 'alicia.escobar@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Escobar Alicia.jpg', '$2y$10$BVNiCp3lmNjAk22VFZMJ3OOIVz7mXdW5uPdipx8AXs61JwGHWCW6m', 0, NULL),
(72, 'Esperanza', 'Loyola', 5, 'Intérprete de Lengua de Señas Chilena', 'esperanza.loyola@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Loyola Esperanza.jpg', '$2y$10$P.KpT7ElVn/Me1yUAzVK4ucGuNfbcpcVzw2fPMHH39HNUNXwKX4ke', 0, NULL),
(73, 'Paula', 'Maulén', 5, 'Educadora Diferencial', 'paula.maulen@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Paula Maulén.jpg', '$2y$10$H6CvdqZ4km3jnF1S/CUkuewBlyaGKx5PYBZKc9KmOAHo5jrsWR4Xi', 0, NULL),
(74, 'Erick', 'Molina', 5, 'Técnico Diferencial', 'erick.molina@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Molina Erick.jpg', '$2y$10$4jBszDbyOUCnovIeXsHXJuq7SkkhQI6o8TEYBjmZSweiLw3LLqDqu', 0, NULL),
(75, 'Edith', 'Pérez', 5, 'Asistente Aula', 'edith.perez@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Pérez Edith.jpg', '$2y$10$pdyu8xpPJHRPZVQvJ7Oe0.cbjGUwbKZAxdBnyl4OMReT2b2flaEq2', 0, NULL),
(76, 'Alicia', 'Riquelme', 5, 'Psicopedagoga', 'alicia.riquelme@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Riquelme Alicia.jpg', '$2y$10$UpaNUfNxBPv7AhRg1DuBGuJ7dIPOgmc9.Ui5HSnqvkFx2ZRkMPj9m', 0, NULL),
(77, 'Isabel', 'Rojas', 5, 'Coordinadora PIE', 'isabel.rojas@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Rojas Isabel.jpg', '$2y$10$yBt1LBex7mX/i8dxTQ4DauRACq.SkiH0.vA9e7drWnCB9A7IwOwgC', 0, NULL),
(78, 'Marina', 'Sepúlveda', 5, 'Educadora Diferencial', 'marina.sepulveda@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Sepúlveda Marina.jpg', '$2y$10$vOlE5gpTduF60OKwlougtuzQzPC8R3gdzFY8kdRtms0ZzxiML8uha', 0, NULL),
(79, 'Carla', 'Silva', 5, 'Educadora Diferencial', 'carla.silva@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Carla Silva.jpg', '$2y$10$hIoRCgLVbNbKE5xt2IY3n.rwKI/gqwTneQPDwImMg9TV5/uOYNGf.', 0, NULL),
(80, 'Nicole', 'Silva', 5, 'Fonoaudióloga', 'nicole.silva@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Silva Nicole.jpg', '$2y$10$UD/yg3gzmoSNk2ynQwr3Get4oX.MUl4iQJgHnC/xDbgKdOyStemMm', 0, NULL),
(81, 'María Jesús', 'Daille', 5, 'Psicopedagoga', 'maria.jesus.daille@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/María Jesús Daille.jpeg', '$2y$10$wR8d.8WM4kh5cJ8SbKfwLOEz/CETcplsjbQ0mqzy7RYMxHCbM4kmW', 0, NULL),
(82, 'Francisca', 'Valdevenito', 5, 'Terapeuta Ocupacional', 'francisca.valdevenito@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Valdevenito Francisca.jpg', '$2y$10$U5.ga.L1OcLdp38bJl23yOXpzKLBxdQ7wNYLXcACKbizia4zacCO2', 0, NULL),
(83, 'Tatiana', 'Vergara', 5, 'Educadora Diferencial', 'tatiana.vergara@marianistasmelipilla.cl', 'https://cmvapp.cl/Proyecto_CNSMC/image/Funcionarios/PIE/Tatiana Vergara.jpg', '$2y$10$W1QXkuXUUBHJa70CXiyzK.XIf7dWq2aGB2xDyqmBisKS5gmMt.eGC', 0, NULL),
(84, 'Test', 'User', 1, 'Test Role', 'testuser@example.com', 'http://example.com/image.jpg', '$2y$10$LSqNLGdD1Tq/K/auUcScf.s0C2Dnwc7HPcgG0KLdy1zjKy2MYqeYq', 0, NULL),
(85, 'Lorena', 'Reyes', 1, 'admin', 'lorena.almendra.r@gmail.com', 'images/giiE3VkH1EAF0pUmHEvcfIL1rCTzTApxB2GDGKUw.jpg', '$2y$12$jgZYriR9d4aX53RbEf7JIOmodka7B2qshwz41f5ze1iaceO34BVNO', 0, NULL),
(86, 'prueba', 'test2', 1, 'prueba test', 'test@gmail.com', 'http://example.com/image.jpg', '$2y$10$L/DvSwp4hhEIm7tpuCaN.erwXFEmPBJkLjXRuUDAF3Iscq8GVuYTu', 0, NULL),
(88, 'Felipe', 'Quiroz', 1, 'Admin Test', 'felipeaqs88@gmail.com', 'images/p2nEq8Wi2inp1hui69mZn2oXovVUP6vQKyvMnIMZ.jpg', '$2y$12$nf5PrhfBtiFSbkxBSj7lPe16nKoY9jltY7l4X2B9YRrrcTPVyAXnC', 0, NULL),
(93, 'felipe', 'quiroz', 1, 'admin', 'fel.quiroz@duocuc.cl', 'images/1728048450.jpg', '$2y$12$0GH6P0tRfKa7pNZCOEG/4uWLpxZwVnH1k7nc9B86bI5tQ4E0AyhgO', 0, NULL),
(94, 'marcelo', 'gomez', 3, 'segundo basico', 'M.gomezq@duocuc.cl', 'images/1728048484.jpg', '$2y$12$eODTs.vWolRwO8UkebhG5.8MAzT03PAXjMUWsgLWOcy5cYoc28DoG', 0, NULL),
(95, 'Lorena', 'reyes', 3, 'profesor jefe', 'lo.reyes@duocuc.cl', 'images/RSvu8u5uOUHqQ4TLi3OOlQVseMEOXaMc4kX0Ypvb.jpg', '$2y$12$sIR2jEu5CFR/W9.ygiSwmO2UGzYYmBFjESJrJtxEc5EeWUkqw/0F2', 0, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_category`);

--
-- Indices de la tabla `citaciones`
--
ALTER TABLE `citaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `derivacions`
--
ALTER TABLE `derivacions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `entrevistas`
--
ALTER TABLE `entrevistas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `expedientes`
--
ALTER TABLE `expedientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `matricula`
--
ALTER TABLE `matricula`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `motivos_entrevista`
--
ALTER TABLE `motivos_entrevista`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citaciones`
--
ALTER TABLE `citaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `derivacions`
--
ALTER TABLE `derivacions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `entrevistas`
--
ALTER TABLE `entrevistas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `expedientes`
--
ALTER TABLE `expedientes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `matricula`
--
ALTER TABLE `matricula`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1056;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `motivos_entrevista`
--
ALTER TABLE `motivos_entrevista`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
