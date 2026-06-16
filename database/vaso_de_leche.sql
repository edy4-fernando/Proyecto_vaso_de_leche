-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-06-2026 a las 00:29:40
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
-- Base de datos: `vaso_de_leche`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_logs`
--

CREATE TABLE `actividad_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `accion` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `actividad_logs`
--

INSERT INTO `actividad_logs` (`id`, `user_id`, `accion`, `descripcion`, `ip`, `created_at`, `updated_at`) VALUES
(1, 1, 'ENTREGA_REGISTRADA', 'Entrega registrada para: HALBER DAVID CCAPCHI RIOS (DNI: 73392625)', '127.0.0.1', '2026-06-16 20:19:41', '2026-06-16 20:19:41'),
(2, 1, 'ENTREGA_REGISTRADA', 'Entrega registrada para: HALBER DAVID CCAPCHI RIOS (DNI: 73392625)', '127.0.0.1', '2026-06-16 20:19:41', '2026-06-16 20:19:41'),
(3, 1, 'ENTREGA_REGISTRADA', 'Entrega registrada para: HALBER DAVID CCAPCHI RIOS (DNI: 73392625)', '127.0.0.1', '2026-06-16 20:19:41', '2026-06-16 20:19:41'),
(4, 1, 'ENTREGA_REGISTRADA', 'Entrega registrada para: HALBER DAVID CCAPCHI RIOS (DNI: 73392625)', '127.0.0.1', '2026-06-16 20:19:56', '2026-06-16 20:19:56'),
(5, 1, 'BENEFICIARIO_EDITADO', 'Se modificaron datos de: HALBER DAVID CCAPCHI RIOS (DNI: 73392625)', '127.0.0.1', '2026-06-16 20:20:25', '2026-06-16 20:20:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `beneficiarios`
--

CREATE TABLE `beneficiarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dni` varchar(8) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `tipo_beneficiario` varchar(50) DEFAULT NULL,
  `sector_o_comite` varchar(100) DEFAULT NULL,
  `nombre_apoderado` varchar(150) DEFAULT NULL,
  `dni_apoderado` varchar(8) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `observaciones_medicas` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `beneficiarios`
--

INSERT INTO `beneficiarios` (`id`, `dni`, `nombre`, `apellido`, `fecha_nacimiento`, `direccion`, `telefono`, `tipo_beneficiario`, `sector_o_comite`, `nombre_apoderado`, `dni_apoderado`, `estado`, `observaciones_medicas`, `created_at`, `updated_at`) VALUES
(1, '73392625', 'HALBER DAVID', 'CCAPCHI RIOS', '2005-03-04', 'JR.LAS GARDENIAS I-19', '914071918', 'niño 0-6', 'COMITE 12', NULL, NULL, 1, NULL, '2026-06-15 18:00:19', '2026-06-15 18:11:31'),
(2, '12345678', 'ANA LUCIA', 'QUISPE FLORES', '2020-03-15', 'AV. EL SOL 123 - CUSCO', '984111001', 'niño 0-6', 'COMITÉ 01 - SAN BLAS', 'MARIA FLORES', '45678901', 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(3, '23456789', 'CARLOS', 'MAMANI CONDORI', '2018-07-22', 'JR. HATUNRUMIYOC 456 - CUSCO', '984111002', 'niño 7-13', 'COMITÉ 02 - SANTA ANA', 'ROSA CONDORI', '56789012', 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(4, '34567890', 'LUCIA', 'HUANCA QUISPE', '1985-11-10', 'CALLE SAPHI 789 - CUSCO', '984111003', 'gestante', 'COMITÉ 03 - BELÉN', NULL, NULL, 1, 'Control prenatal activo', '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(5, '45678901', 'PEDRO', 'CCAPCHI VARGAS', '1945-05-20', 'AV. TULLUMAYO 321 - CUSCO', '984111004', 'adulto mayor', 'COMITÉ 01 - SAN BLAS', NULL, NULL, 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(6, '56789012', 'SOFIA', 'TTITO HUALLPA', '2021-09-03', 'JR. MARURI 654 - CUSCO', '984111005', 'niño 0-6', 'COMITÉ 04 - TTIO', 'JUAN HUALLPA', '67890123', 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(7, '67890123', 'ROBERTO', 'APAZA CCORIMANYA', '2016-12-18', 'CALLE RUINAS 987 - CUSCO', '984111006', 'niño 7-13', 'COMITÉ 02 - SANTA ANA', 'ELENA CCORIMANYA', '78901234', 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(8, '78901234', 'ELENA', 'CCORIMANYA ROCA', '1978-04-25', 'AV. LA CULTURA 147 - CUSCO', '984111007', 'lactante', 'COMITÉ 05 - ZARZUELA', NULL, NULL, 1, 'Post parto 2 meses', '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(9, '89012345', 'MIGUEL', 'HUILLCA TORRES', '2019-08-14', 'JR. AYACUCHO 258 - CUSCO', '984111008', 'niño 0-6', 'COMITÉ 03 - BELÉN', 'CARMEN TORRES', '90123456', 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(10, '90123456', 'CARMEN', 'TORRES PUMAYALLI', '1952-01-30', 'CALLE LORETO 369 - CUSCO', '984111009', 'adulto mayor', 'COMITÉ 04 - TTIO', NULL, NULL, 1, 'Hipertensión controlada', '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(11, '01234567', 'JUAN', 'QUISPE MAMANI', '2015-06-08', 'AV. INFANCIA 741 - CUSCO', '984111010', 'niño 7-13', 'COMITÉ 05 - ZARZUELA', 'ANA MAMANI', '12345679', 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(12, '11223344', 'ROSA', 'CONDORI HUANCA', '2022-02-14', 'JR. BELEN 852 - CUSCO', '984111011', 'niño 0-6', 'COMITÉ 01 - SAN BLAS', 'PEDRO HUANCA', '22334455', 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(13, '22334455', 'JORGE', 'PUMAYALLI CRUZ', '1940-09-12', 'CALLE CHOQUECHACA 963 - CUSCO', '984111012', 'adulto mayor', 'COMITÉ 06 - WANCHAQ', NULL, NULL, 1, 'Diabetes tipo 2', '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(14, '33445566', 'MARIA', 'HUALLPA TTITO', '1980-07-19', 'AV. GRAU 174 - CUSCO', '984111013', 'gestante', 'COMITÉ 06 - WANCHAQ', NULL, NULL, 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(15, '44556677', 'LUIS', 'ROCA APAZA', '2017-11-25', 'JR. PUMACURCO 285 - CUSCO', '984111014', 'niño 7-13', 'COMITÉ 07 - SANTIAGO', 'JORGE APAZA', '55667788', 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(16, '55667788', 'PATRICIA', 'VARGAS QUISPE', '2023-05-01', 'CALLE TECSECOCHA 396 - CUSCO', '984111015', 'niño 0-6', 'COMITÉ 07 - SANTIAGO', 'LUIS VARGAS', '66778899', 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(17, '66778899', 'ANTONIO', 'FLORES CCAPCHI', '1948-03-17', 'AV. EJERCITO 507 - CUSCO', '984111016', 'adulto mayor', 'COMITÉ 08 - SAN SEBASTIÁN', NULL, NULL, 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(18, '77889900', 'SANDRA', 'HUANCA APAZA', '1983-10-22', 'JR. MESÓN DE LA ESTRELLA 618', '984111017', 'lactante', 'COMITÉ 08 - SAN SEBASTIÁN', NULL, NULL, 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(19, '88990011', 'DIEGO', 'MAMANI QUISPE', '2020-12-05', 'CALLE PROCURADORES 729 - CUSCO', '984111018', 'niño 0-6', 'COMITÉ 09 - SAN JERÓNIMO', 'SANDRA QUISPE', '99001122', 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(20, '99001122', 'ISABEL', 'CRUZ HUILLCA', '1957-08-28', 'AV. PARDO 840 - CUSCO', '984111019', 'adulto mayor', 'COMITÉ 09 - SAN JERÓNIMO', NULL, NULL, 1, 'Artrosis rodilla', '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(21, '10203040', 'CARLOS JOSE', 'QUISPE TTITO', '2019-04-16', 'JR. NUEVA ALTA 951 - CUSCO', '984111020', 'niño 0-6', 'COMITÉ 10 - POROY', 'ISABEL TTITO', '20304050', 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(22, '20304050', 'VALENTINA', 'CONDORI ROCA', '1990-06-30', 'CALLE SIETE CUARTONES 123', '984111021', 'discapacitado', 'COMITÉ 10 - POROY', NULL, NULL, 1, 'CONADIS N° 001234', '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(23, '30405060', 'MARCO', 'HUALLPA VARGAS', '2014-02-11', 'AV. REGIONAL 234 - CUSCO', '984111022', 'niño 7-13', 'COMITÉ 11 - SAYLLA', 'VALENTINA VARGAS', '40506070', 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(24, '40506070', 'GIULIANA', 'TTITO FLORES', '2022-08-19', 'JR. ALMUDENA 345 - CUSCO', '984111023', 'niño 0-6', 'COMITÉ 11 - SAYLLA', 'MARCO FLORES', '50607080', 1, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(25, '50607080', 'RAFAEL', 'APAZA MAMANI', '1943-11-03', 'CALLE AREQUIPA 456 - CUSCO', '984111024', 'adulto mayor', 'COMITÉ 12 - CCORCA', NULL, NULL, 0, NULL, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(26, '60708090', 'LUCIANA', 'CCORIMANYA CRUZ', '1975-09-14', 'AV. MANCO CAPAC 567 - CUSCO', '984111025', 'tbc', 'COMITÉ 12 - CCORCA', NULL, NULL, 1, 'Tratamiento DOTS activo', '2026-06-16 15:15:19', '2026-06-16 15:15:19');

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
-- Estructura de tabla para la tabla `configuraciones`
--

CREATE TABLE `configuraciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `llave` varchar(100) NOT NULL,
  `valor` text DEFAULT NULL,
  `tipo` varchar(20) NOT NULL DEFAULT 'string',
  `grupo` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuraciones`
--

INSERT INTO `configuraciones` (`id`, `llave`, `valor`, `tipo`, `grupo`, `created_at`, `updated_at`) VALUES
(1, 'nombre_institucion', 'Municipalidad Provincial del Cusco', 'string', 'institucional', NULL, NULL),
(2, 'nombre_programa', 'Vaso de Leche', 'string', 'institucional', NULL, NULL),
(3, 'distrito', 'Cusco', 'string', 'institucional', NULL, NULL),
(4, 'responsable', '', 'string', 'institucional', NULL, NULL),
(5, 'limite_entregas_diarias', '1', 'integer', 'entrega', NULL, NULL),
(6, 'cantidad_default', '1', 'integer', 'entrega', NULL, NULL),
(7, 'stock_minimo_global', '10', 'integer', 'entrega', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entregas`
--

CREATE TABLE `entregas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `beneficiario_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha_entrega` date NOT NULL,
  `hora_entrega` time DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `observaciones_incidencias` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `entregas`
--

INSERT INTO `entregas` (`id`, `beneficiario_id`, `user_id`, `producto_id`, `fecha_entrega`, `hora_entrega`, `cantidad`, `observaciones_incidencias`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, '2026-06-15', '18:11:12', 1, NULL, '2026-06-15 18:11:12', '2026-06-15 18:11:12'),
(62, 1, 1, 2, '2026-06-16', '07:15:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(63, 2, 1, 4, '2026-06-16', '07:22:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(64, 3, 1, 2, '2026-06-16', '07:35:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(65, 4, 1, 3, '2026-06-16', '07:48:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(66, 5, 1, 2, '2026-06-16', '08:02:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(67, 1, 1, 2, '2026-06-15', '07:10:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(68, 2, 1, 4, '2026-06-15', '07:25:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(69, 6, 1, 3, '2026-06-15', '07:40:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(70, 7, 1, 5, '2026-06-15', '07:55:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(71, 8, 1, 2, '2026-06-15', '08:10:00', 1, 'Llegó tarde', '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(72, 1, 1, 2, '2026-06-14', '07:05:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(73, 3, 1, 4, '2026-06-14', '07:18:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(74, 9, 1, 3, '2026-06-14', '07:30:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(75, 10, 1, 2, '2026-06-14', '07:45:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(76, 11, 1, 5, '2026-06-13', '07:12:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(77, 12, 1, 2, '2026-06-13', '07:28:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(78, 13, 1, 4, '2026-06-13', '07:42:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(79, 14, 1, 3, '2026-06-12', '07:08:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(80, 15, 1, 2, '2026-06-12', '07:22:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(81, 16, 1, 5, '2026-06-12', '07:35:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(82, 17, 1, 4, '2026-06-11', '07:15:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(83, 18, 1, 2, '2026-06-11', '07:30:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(84, 19, 1, 3, '2026-06-11', '07:45:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(85, 20, 1, 2, '2026-06-10', '07:10:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(86, 21, 1, 4, '2026-06-10', '07:25:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(87, 1, 1, 2, '2026-06-09', '07:05:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(88, 2, 1, 3, '2026-06-09', '07:18:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(89, 3, 1, 5, '2026-06-08', '07:32:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(90, 4, 1, 2, '2026-06-08', '07:48:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(91, 5, 1, 4, '2026-06-07', '07:15:00', 1, NULL, '2026-06-16 15:18:08', '2026-06-16 15:18:08'),
(92, 1, 1, NULL, '2026-06-16', '20:19:41', 1, NULL, '2026-06-16 20:19:41', '2026-06-16 20:19:41'),
(93, 1, 1, NULL, '2026-06-16', '20:19:41', 1, NULL, '2026-06-16 20:19:41', '2026-06-16 20:19:41'),
(94, 1, 1, NULL, '2026-06-16', '20:19:41', 1, NULL, '2026-06-16 20:19:41', '2026-06-16 20:19:41'),
(95, 1, 1, NULL, '2026-06-16', '20:19:56', 1, NULL, '2026-06-16 20:19:56', '2026-06-16 20:19:56');

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
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo_insumo` varchar(100) DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `numero_lote` varchar(50) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `unidad_medida` varchar(50) DEFAULT NULL,
  `stock_actual` int(11) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `tipo_insumo`, `nombre`, `marca`, `numero_lote`, `fecha_vencimiento`, `unidad_medida`, `stock_actual`, `stock_minimo`, `created_at`, `updated_at`) VALUES
(2, 'lacteo', 'LECHE EN POLVO ENTERA', 'GLORIA', 'LOT-2026-001', '2027-06-30', 'kg', 150, 20, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(3, 'lacteo', 'LECHE EVAPORADA', 'GLORIA', 'LOT-2026-002', '2027-03-15', 'unidad', 200, 30, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(4, 'cereal', 'AVENA INSTANTÁNEA', 'QUAKER', 'LOT-2026-003', '2026-12-31', 'kg', 80, 15, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(5, 'cereal', 'MEZCLA DE CEREALES', 'NUTRIFORT', 'LOT-2026-004', '2026-09-30', 'kg', 45, 20, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(6, 'vitamina', 'SUPLEMENTO VITAMÍNICO', 'CENTRO', 'LOT-2026-005', '2026-08-15', 'unidad', 30, 5, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(7, 'legumbre', 'QUINUA', 'INCA', 'LOT-2026-006', '2027-01-20', 'kg', 60, 10, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(8, 'cereal', 'HARINA DE MAÍZ', 'MOLICENTRO', 'LOT-2026-007', '2026-07-31', 'kg', 8, 15, '2026-06-16 15:15:19', '2026-06-16 15:15:19'),
(9, 'lacteo', 'LECHE UHT', 'LAIVE', 'LOT-2026-008', '2026-08-01', 'unidad', 12, 25, '2026-06-16 15:15:19', '2026-06-16 15:15:19');

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
('QuijZ6ZY2KjvxKFk5ch78UxYtY2a4tFFH4Usfwja', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.124.2 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidGVYaHUzbHdOOGdYbE5mODhZaDhRUGM2anNFYVNTbVE0OVFwazdNbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hc2lzdGVuY2lhIjtzOjU6InJvdXRlIjtzOjE2OiJhc2lzdGVuY2lhLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1781645815),
('WkSCrHryi880upJYvRESUMmYuinFwK6zO4O0QT1d', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibVh3YVRaZWFubWlGTzBNR3NOdTRHUHlXMzlJNVVBYXFDSlZaV1NhUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQvaGlzdG9yaWFsIjtzOjU6InJvdXRlIjtzOjE5OiJkYXNoYm9hcmQuaGlzdG9yaWFsIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1781647108);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `dni` varchar(8) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `rol` enum('maestro','trabajador') NOT NULL DEFAULT 'trabajador',
  `estado_cuenta` tinyint(1) NOT NULL DEFAULT 1,
  `ultimo_ingreso` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `dni`, `email`, `email_verified_at`, `password`, `telefono`, `rol`, `estado_cuenta`, `ultimo_ingreso`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', NULL, 'admin@vasodeleche.gob.pe', NULL, '$2y$12$fUS0UEKz5CBXtA7OOnCXK.p4Y8ssZMe2Af7WAVg2Jjydp0hfKJ9H.', NULL, 'maestro', 1, '2026-06-16 21:57:28', 'BwHRLwfWuYyolikcp2XIuJxNo5EwbvPX3IugqBtq54xI57LPInmU697Sr0n4', '2026-06-14 23:50:13', '2026-06-16 21:57:28'),
(3, 'HALBER DAVID CCAPCHI RIOS', '73392625', 'halberdavid2005@gmail.com', NULL, '$2y$12$QaWToN6ecYmJ0sS/jzfY5O1AFOSj/7wlCiOAC8tSRHr0wg88LTGqi', '914071918', 'trabajador', 1, '2026-06-16 18:16:40', NULL, '2026-06-15 17:58:56', '2026-06-16 18:16:40');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividad_logs`
--
ALTER TABLE `actividad_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `beneficiarios`
--
ALTER TABLE `beneficiarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

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
-- Indices de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `llave` (`llave`);

--
-- Indices de la tabla `entregas`
--
ALTER TABLE `entregas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entregas_beneficiario_id_index` (`beneficiario_id`),
  ADD KEY `entregas_user_id_index` (`user_id`),
  ADD KEY `entregas_producto_id_index` (`producto_id`),
  ADD KEY `entregas_fecha_entrega_index` (`fecha_entrega`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividad_logs`
--
ALTER TABLE `actividad_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `beneficiarios`
--
ALTER TABLE `beneficiarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `entregas`
--
ALTER TABLE `entregas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `entregas`
--
ALTER TABLE `entregas`
  ADD CONSTRAINT `fk_entregas_beneficiario` FOREIGN KEY (`beneficiario_id`) REFERENCES `beneficiarios` (`id`),
  ADD CONSTRAINT `fk_entregas_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_entregas_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
