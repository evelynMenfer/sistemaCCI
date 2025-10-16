-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 13-10-2025 a las 21:57:13
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbinventariosprueba`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `back_order_list`
--

CREATE TABLE `back_order_list` (
  `id` int(30) NOT NULL,
  `receiving_id` int(30) NOT NULL,
  `po_id` int(30) NOT NULL,
  `bo_code` varchar(50) NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `amount` float NOT NULL,
  `discount_perc` float NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `tax_perc` float NOT NULL DEFAULT 0,
  `tax` float NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = pending, 1 = partially received, 2 =received',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bo_items`
--

CREATE TABLE `bo_items` (
  `bo_id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `quantity` int(30) NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `unit` varchar(50) NOT NULL,
  `total` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company_list`
--

CREATE TABLE `company_list` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `address` varchar(500) DEFAULT '',
  `contact` varchar(255) DEFAULT '',
  `cperson` varchar(255) DEFAULT '',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_update` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL,
  `email` varchar(50) NOT NULL,
  `rfc` varchar(30) NOT NULL,
  `logo` text DEFAULT NULL,
  `identificador` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `company_list`
--

INSERT INTO `company_list` (`id`, `name`, `address`, `contact`, `cperson`, `date_created`, `date_update`, `status`, `email`, `rfc`, `logo`, `identificador`) VALUES
(3, 'INGENIERIA Y SERVICIOS CENIT S.A DE C.V.', 'Guadalupe Victoria # 606, Colonia Presidentes de México. Oaxaca de Juárez, Oaxaca. Código Postal. 68274.', '9512020060', '', '2023-10-11 03:51:50', '2023-10-11 03:51:50', 1, 'ingenieriayservicioscenit13@gmail.com', '', 'cenit.png', 'cenit'),
(4, 'SUCCES', '5A PRIVADA DE VICENTE GUERRERO #112 COLONIA CANDIANI. OAXACA DE JUÁREZ, OAXACA. C.P. 68130', '(01951) 2152725', 'WESERV', '2023-10-11 03:58:47', '2023-10-11 03:58:47', 1, 'succes@gmail.com', '', 'succes.png', 'succes'),
(6, 'PROVEEDORA COMERCIAL HELMES S.A. DE C.V.', 'EMILIO CARRANZA 811 INT 4, REFORMA CH C.P 68050, OAXACA DE JUAREZ, OAX.', '', ' ING. CAROLINA ORTEGA', '2023-10-17 18:50:05', '2023-10-17 18:50:05', 1, 'carolina.ortega.gia.mx', '', 'helmes.png', 'helmes'),
(7, 'Kalahari Distribuidora Comercial', 'OAXACA DE JUAREZ, OAXACA', '', '', '2023-10-17 18:51:49', '2023-10-17 18:51:49', 1, '', '', 'kalahari.png', 'kalahari'),
(8, 'MB Cómputo Soluciones en tecnología', 'Privada 9B Sur #4931, Prados Agua Azul.\r\nPuebla, Puebla', '222 299 8504', '', '2023-10-17 18:53:20', '2023-10-17 18:53:20', 1, '', '', 'mbcomputo.png', 'mbcomputo'),
(9, 'OPERADORA COMERCIAL EL GRAN SURTIDOR DEL SOL NACIENTE SA DE CV', 'Sabinos #900 C ,Reforma, Oaxaca de Juárez.', '', '', '2023-10-17 18:55:24', '2023-10-17 18:55:24', 1, '', 'OCG171215C97', 'solnaciente.png', 'solnaciente'),
(10, 'DESARROLLO IMPLEMENTACION Y SUMINISTRO DE SISTEMAS S.A. DE C.V. ', 'Avenida México 68 No. 312, Colonia Olímpica, Oaxaca de Juárez. C.P. 68020', '951 6260728', 'prueba', '2023-10-17 20:23:06', '2023-10-17 20:23:06', 1, '', '', 'diss.png', 'diss'),
(11, 'FERRETERIA Y SERVICIOS DRAPER S.A. DE C.V.', 'CALLE JAZMINEZ NÚMERO 2 MUNICIPIO DE SAN PEDRO IXTLAHUACA, OAXACA.', '', '', '2023-10-17 20:23:50', '2023-10-17 20:23:50', 1, '', '', 'draper.png', 'draper'),
(14, 'MPF Mecanica estrada de mexico', 'DEL PEDREGAL, 89 B, Cofradía de San Miguel, 54715, Cuautitlán Izcalli, Cuautitlán\r\nIzcalli, Estado de México, México', '+52 1 55 6216 0207', 'Venta en whatsapp', '2024-08-23 09:27:32', '2024-08-23 09:27:32', 1, 'No hay', 'OOPL780303EZ5', 'mpfmecanica.png', 'mpfmecanica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_list`
--

CREATE TABLE `item_list` (
  `id` int(30) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `foto_producto` varchar(255) DEFAULT NULL,
  `supplier_id` int(30) NOT NULL,
  `cost` float NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `company_id` int(30) NOT NULL,
  `stock` decimal(10,2) DEFAULT 0.00,
  `date_purchase` date DEFAULT NULL,
  `product_cost` float NOT NULL,
  `shipping_or_extras` decimal(10,2) DEFAULT 0.00,
  `oc` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `item_list`
--

INSERT INTO `item_list` (`id`, `name`, `description`, `foto_producto`, `supplier_id`, `cost`, `status`, `date_created`, `date_updated`, `company_id`, `stock`, `date_purchase`, `product_cost`, `shipping_or_extras`, `oc`) VALUES
(22, 'A5 de Acriton blanco cubeta de 19 litros', 'A5 de Acriton blanco cubeta de 19 litros', NULL, 5, 2200, 1, '2023-10-16 01:30:23', '2023-10-26 20:13:42', 3, '1.00', '2023-10-16', 2336, '1.00', '1234-prueba'),
(23, 'Escalera tijera doble', 'Escalera tijera doble', NULL, 12, 2800, 1, '2023-10-16 12:48:46', '2024-05-31 14:14:57', 4, '1.00', '2022-04-29', 2550, '59.00', '1'),
(24, 'Bote de agua', 'vdsvsd', NULL, 7, 100, 1, '2023-10-18 01:53:06', '2024-05-31 14:15:00', 7, '1.00', '2023-10-18', 50, '10.00', '1'),
(25, 'Audifonos', '23r345435', NULL, 11, 100, 1, '2023-10-26 19:46:57', '2023-10-26 20:05:51', 9, '18.00', '2023-10-26', 100, '1.00', '1234'),
(26, 'Papel higienico', '32424', NULL, 11, 100, 1, '2023-10-26 20:03:21', '2023-10-26 20:04:45', 9, '1.00', '2023-10-27', 130, '232.00', '3842384'),
(27, 'lapiceros', 'lapiceros descripción', NULL, 6, 15, 1, '2024-05-22 01:12:58', '2024-05-22 01:12:58', 7, '10.00', '2024-05-22', 10, '0.00', '01-lapiceros'),
(28, 'papel', 'hojas blancas', NULL, 12, 80, 1, '2024-05-22 02:09:33', '2024-05-22 02:09:33', 11, '1.00', '2024-05-22', 60, '0.00', '123'),
(29, 'cable', 'cable largo 8m', NULL, 8, 1, 1, '2024-05-23 05:04:35', '2024-05-23 05:04:35', 7, '1.00', '2024-05-24', 1, '1.00', '345'),
(30, 'Tornillo', 'R', NULL, 13, 0, 1, '2024-05-23 14:13:55', '2024-05-31 14:19:27', 3, '1.00', '2024-05-23', 0, '0.00', ''),
(31, '1', 'Valvula gas honeywell vr8304h4503/vr8304h4230 24v60hz', NULL, 14, 6000, 1, '2024-05-23 14:37:30', '2024-05-23 14:41:26', 4, '1.00', '2024-04-03', 1695.61, '500.00', '22357'),
(32, '', 'A5 DE ACRITON BLANCO CUBETA DE 19 LITROS', NULL, 5, 2200, 1, '2024-05-24 14:00:53', '2024-05-31 14:15:09', 4, '1.00', '2022-04-07', 2336, '0.00', '683'),
(33, '', 'ABATE LENGUAS ', NULL, 13, 1, 1, '2024-05-24 14:08:28', '2024-05-31 14:15:12', 4, '1.00', '2022-08-26', 0.3448, '0.00', '17551/17975'),
(34, '', 'ABATE LENGUAS DE MADERA PAQUETE DE 500 PIEZAS ', NULL, 6, 200, 1, '2024-05-24 14:13:34', '2024-05-31 14:15:15', 4, '1.00', '2021-11-23', 73.08, '0.00', '301'),
(39, '', 'Acabadora para impresora xerox altalink b8090 097504811 6bn (maquinaria y equipo)', NULL, 14, 53900, 1, '2024-05-24 14:57:35', '2024-05-31 14:15:36', 6, '1.00', '2022-10-17', 0, '0.00', '7923'),
(40, '', 'Aceite AERZEN delta lube 06 presentacion 5 litros', NULL, 7, 4745, 1, '2024-05-24 15:02:45', '2024-05-31 14:15:41', 6, '1.00', '2020-09-11', 941.433, '0.00', '11869'),
(41, '', 'Aceite Mobil Delvac 1300 super sae 19 litros ', NULL, 8, 1990, 1, '2024-05-24 15:06:46', '2024-05-31 14:15:46', 4, '1.00', '2021-11-19', 1715.34, '6794.00', '14918'),
(42, '', 'Acetona', NULL, 15, 85, 1, '2024-05-24 15:25:10', '2024-05-31 14:15:50', 4, '1.00', '2022-05-18', 55, '323.83', '827'),
(43, '', 'Acido fosforico 35% grabador de esmalte ultra etch jumbo jeringas 30 ml ULTRADENT', NULL, 16, 1275, 1, '2024-05-24 15:31:16', '2024-05-31 14:14:47', 4, '1.00', '2020-11-24', 1102, '0.00', '12930'),
(44, '', 'Acumulador de succion emerson 7/8 A-AS5977', NULL, 17, 2550, 1, '2024-05-24 15:40:54', '2024-05-31 14:14:44', 4, '1.00', '2021-04-05', 2772.4, '0.00', '13674'),
(45, '', 'Acido acetilasilicilico de 100 mg tabletas', NULL, 18, 35, 1, '2024-05-25 10:27:18', '2024-05-31 14:14:39', 0, '1.00', '2022-08-26', 19.5, '0.00', '14918'),
(46, '', 'Agua desionizada marca MIZU de 20 litros', NULL, 20, 600, 1, '2024-05-25 10:55:16', '2024-05-31 14:14:36', 4, '1.00', '2021-11-19', 156.6, '3428.24', '14919'),
(47, '', 'Acumuladores de succión serie A-AS 597, A-AS emerson, de 7/8', NULL, 21, 2050, 1, '2024-05-25 11:03:43', '2024-05-31 18:24:25', 4, '1.00', '2020-12-07', 1900, '0.00', '13030'),
(48, '', 'Adaptador recto de bronce de 1/4 x 5/16 generico', NULL, 22, 290, 1, '2024-05-25 11:12:51', '2024-05-31 18:24:34', 7, '1.00', '2022-09-10', 211.7, '0.00', '17721'),
(49, '', 'Adaptador Minidisplay port a HDMI', NULL, 19, 550, 1, '2024-05-25 11:18:49', '2024-05-31 14:14:19', 10, '1.00', '2020-06-10', 2790, '0.00', '6371'),
(50, '', 'Adhesivo 520 Armaflex color negro 948ml', NULL, 23, 300, 1, '2024-05-25 11:27:26', '2024-05-31 14:15:53', 9, '1.00', '2022-10-10', 150.8, '104.50', '17974'),
(51, '', 'Agitador del despachador de bbidas con base para tubo D15', NULL, 24, 310, 1, '2024-05-25 11:35:08', '2024-05-31 14:15:56', 4, '1.00', '2020-11-10', 220, '255.20', '12826'),
(52, '', 'Aguja Tipo Mariposa', NULL, 25, 35, 1, '2024-05-25 11:46:21', '2024-05-31 14:16:16', 4, '1.00', '2020-09-23', 16.16, '147.20', '12461'),
(53, '', 'Aguja tipor mariposa BD vacutainer', NULL, 26, 35, 1, '2024-05-25 11:50:59', '2024-05-31 14:16:21', 4, '1.00', '2022-09-05', 7.5516, '290.00', '17660'),
(54, '', 'Algodón torundas bolsa de 150 g', NULL, 27, 42, 1, '2024-05-25 11:54:20', '2024-05-31 14:16:25', 4, '1.00', '2021-11-23', 55.17, '0.00', '301'),
(55, '', 'Analizador de motor y de calidad electrica Fluke 438-II', NULL, 29, 255000, 1, '2024-05-25 12:04:02', '2024-05-31 14:16:28', 4, '1.00', '2022-02-23', 188517, '400.00', '550'),
(56, '', 'Anticongelante Valvoline zerex G-93, cubeta 19L', NULL, 30, 1890, 1, '2024-05-25 12:07:40', '2024-05-31 14:16:31', 7, '1.00', '0000-00-00', 1482.88, '0.00', '18342'),
(57, '', 'aplanador de carne', NULL, 31, 350, 1, '2024-05-25 12:10:53', '2024-05-31 14:16:34', 7, '1.00', '2021-12-10', 214.66, '100.00', '367'),
(58, '', 'Armaflex abierto de 7/8 x 1/2', NULL, 32, 129, 1, '2024-05-25 12:17:29', '2024-05-31 18:24:40', 4, '1.00', '2021-07-23', 38.95, '394.40', '14170'),
(59, '', 'Armaflex tubo de  recubrimiento 1 3/4', NULL, 34, 120, 1, '2024-05-25 12:24:36', '2024-05-31 18:24:45', 4, '1.00', '0220-08-10', 61.5, '0.00', '12225'),
(60, '', 'Arrancador 2-6-3.7 AMP trifásico #parte 3021835 Rodotec', NULL, 35, 2800, 1, '2024-05-25 12:29:26', '2024-05-31 14:16:47', 7, '1.00', '2022-02-01', 2044, '0.00', '18193'),
(61, '', 'Asa No contacto #2484 Despachador Bebidas Crathco D-254 2484', NULL, 36, 550, 1, '2024-05-25 12:33:34', '2024-05-31 14:16:50', 7, '1.00', '2022-08-25', 250, '0.00', '17537'),
(62, '', 'Auricular con supresion de ruido, conector USB-A', NULL, 37, 650, 1, '2024-05-25 12:47:46', '2024-05-31 14:16:53', 10, '1.00', '2022-04-29', 428.43, '277.24', '16388'),
(63, '', 'Auricular con supresion de ruido, conector USB-A', NULL, 38, 720, 1, '2024-05-25 12:51:16', '2024-05-31 14:16:57', 10, '1.00', '2020-06-05', 524.83, '0.00', '6368'),
(64, '', 'Azucar estandar', NULL, 39, 23.76, 1, '2024-05-25 12:54:01', '2024-05-31 14:17:00', 4, '1.00', '2020-11-02', 20.47, '0.00', '1511'),
(65, '', 'Balatas de freno delanteras para unidad VW Virtus 2021 No. De Serie 9BWDL5BZ2MP027267', NULL, 40, 570, 1, '2024-05-25 12:58:20', '2024-05-31 14:17:04', 4, '1.00', '2022-08-01', 359.99, '0.00', '1077'),
(66, '', 'balero a13 a 38 a13 a40e13 ford ranger', NULL, 41, 1700, 1, '2024-05-25 13:01:48', '2024-05-31 14:17:07', 4, '1.00', '2022-02-22', 1032.4, '0.00', '7625'),
(67, '', 'Banda 270-H 100', NULL, 42, 460, 1, '2024-05-25 13:06:44', '2024-05-31 14:17:10', 4, '1.00', '2021-03-04', 347.2, '0.00', '13512'),
(68, '', 'Electroestimulador Tens 3000', NULL, 43, 1200, 1, '2024-07-01 11:11:17', '2024-07-01 11:11:17', 4, '0.00', '2023-11-11', 999, '0.00', '21645'),
(69, '', 'Ultrasonido terapéutico portatil us 1000', NULL, 62, 2100, 1, '2024-07-01 11:17:43', '2024-07-01 11:17:43', 4, '0.00', '2023-11-11', 1699, '0.00', '21645'),
(70, '', 'clonixiato de lisina, hioscina 100mg/4 ml caja c/6 ampolletas', NULL, 44, 80, 1, '2024-07-01 11:34:26', '2024-07-01 11:34:26', 4, '0.00', '2024-01-16', 45, '0.00', '21716'),
(71, '', 'guantes de latex 100 pzs talla chica', NULL, 66, 250, 1, '2024-07-01 11:44:13', '2024-07-01 11:44:13', 4, '0.00', '2024-01-23', 80, '0.00', '21758'),
(72, '', 'Pressuere Blade, Spare, Altalink B8090', NULL, 46, 1200, 1, '2024-07-01 11:48:54', '2024-07-01 11:48:54', 4, '0.00', '2024-03-14', 882, '0.00', '22215'),
(73, '', 'Pelicula radiografica carestream dry view 35x43cm', NULL, 47, 6700, 1, '2024-07-01 11:52:34', '2024-07-01 11:52:34', 4, '0.00', '2024-03-15', 4932.32, '0.00', '22220'),
(74, '', 'Bolsa Esterilización 7x200cm p/instrumental calor c/200', NULL, 48, 160, 1, '2024-07-01 11:53:45', '2024-07-01 11:53:45', 4, '0.00', '2024-03-15', 111.21, '0.00', '22220'),
(75, '', 'Bateria de plomo 6V, 4.5 Ah generica', NULL, 49, 350, 1, '2024-07-01 11:55:54', '2024-07-01 11:55:54', 4, '0.00', '2024-03-15', 94.83, '0.00', '22220'),
(76, '', 'Válvula solenoide Jefferson 1/2\" 110 V', NULL, 50, 2500, 1, '2024-07-01 11:57:23', '2025-01-29 18:32:37', 4, '0.00', '2024-03-15', 1811.04, '0.00', '22220'),
(77, '', 'Oxoral Aseptic Flush solucion desinfectante unidad dent', NULL, 51, 1100, 1, '2024-07-01 11:58:47', '2024-07-01 11:58:47', 4, '0.00', '2024-03-15', 665, '0.00', '22220'),
(78, '', 'OROCUP limpiador 2 litros', NULL, 52, 1200, 1, '2024-07-01 12:00:06', '2024-07-01 12:00:06', 4, '0.00', '2024-03-15', 810.34, '0.00', '22220'),
(79, '', 'Agua tridestilada desionizada Zeyco 20 litros', NULL, 53, 630, 1, '2024-07-01 12:03:06', '2024-07-01 12:03:06', 4, '0.00', '2024-03-15', 516.38, '0.00', '22220'),
(80, '', 'Megáfono de hombro marca Steren', NULL, 54, 820, 1, '2024-07-01 12:04:46', '2024-07-01 12:04:46', 4, '0.00', '2024-03-11', 688.8, '0.00', '22185'),
(81, '', 'Placa de hule dieléctrico 1m 1m 1.6 mm', NULL, 55, 390, 1, '2024-07-01 12:06:15', '2024-07-01 12:06:15', 0, '0.00', '2024-03-20', 250, '0.00', '22254'),
(82, '', 'Fuente alimentación para servidores Ucs 200-210 CPB09-031A', NULL, 12, 3400, 1, '2024-07-01 12:08:06', '2024-07-01 12:08:06', 4, '0.00', '2024-03-20', 1742.34, '0.00', '22254'),
(83, '', 'Tubo armaflex abierto de 7/8\" x 3/8\"', NULL, 56, 95, 1, '2024-07-01 12:11:06', '2025-01-29 18:32:57', 4, '0.00', '2024-03-22', 53.2, '0.00', '22284'),
(84, '', 'Tubo armaflex  de 1 5/8¨x 3/4¨autoadherible ', NULL, 33, 145, 1, '2024-07-01 12:16:29', '2024-07-01 12:16:29', 4, '0.00', '2024-05-22', 92.65, '0.00', '22804'),
(85, '', 'Capacitor start 216, 260UF, 250 VAC, 412808417, 50-60Hz para procesador de alimentos Sammic', NULL, 67, 500, 1, '2024-07-01 14:20:26', '2024-07-01 14:20:26', 7, '0.00', '2024-01-03', 90.52, '0.00', '21749'),
(86, '', 'Placa electrónica CA 120V/1 cod: 2059399 para procesador de verduras Sammic modelo S.L. 20720 AZK01TIA SPAIN', NULL, 68, 2120, 1, '2024-07-01 14:21:43', '2024-07-01 14:21:43', 7, '0.00', '2024-01-03', 1480, '0.00', '21749'),
(87, '', 'bascula marca torrey modelo EQM1000/2000', NULL, 69, 19200, 1, '2024-07-01 14:24:21', '2024-07-01 14:24:21', 7, '0.00', '2024-01-23', 14685.8, '0.00', '8390'),
(88, '', 'tobera intercambiable ALCO-800537,#4 para camaras ', NULL, 70, 590, 1, '2024-07-01 14:25:40', '2024-07-01 14:25:40', 7, '0.00', '2024-01-24', 268.78, '0.00', '21763'),
(89, '', 'valvula rotolock 1 1/42\" X5/8\" soldable con pertas de accesorios 1/4 universal para sistemas de refrigeracion  ', NULL, 72, 950, 1, '2024-07-01 14:26:53', '2025-01-29 18:33:07', 7, '0.00', '2024-01-24', 644.86, '0.00', '21763'),
(90, '', 'termostato ALS con rango de -40°C  A 20°C, marca Sainomiya para camaras de congelacion ', NULL, 71, 1950, 1, '2024-07-01 14:28:23', '2024-07-01 14:28:23', 7, '0.00', '2024-01-23', 1100.5, '0.00', '21760'),
(91, '', 'Termostato dde control p/sarteneta mod S 35-48 30 A', NULL, 75, 3200, 1, '2024-07-01 14:32:47', '2024-07-01 14:32:47', 7, '0.00', '2024-02-15', 1650, '0.00', '21939'),
(92, '', 'Kit de encendido piloto intermitente Honeywell: incluye válvula de gas, módulo de encendido y bujia de ignición, cable deignición, arnés de cableado', NULL, 76, 9200, 1, '2024-07-01 14:34:30', '2024-07-01 14:34:30', 7, '0.00', '2024-02-15', 5700, '0.00', '21939'),
(93, '', 'Manguera de gas de 19 mm marca inter', NULL, 77, 1600, 1, '2024-07-01 14:36:35', '2024-07-01 14:36:35', 7, '0.00', '2024-02-15', 114.56, '0.00', '21939'),
(94, '', 'Tromp router marca CELA', NULL, 79, 0, 1, '2024-07-01 14:37:58', '2024-07-01 14:37:58', 7, '0.00', '2024-02-13', 0, '0.00', '8406'),
(95, '', 'Relevador 2PZT 110V No parte 1SVR405601R7000', NULL, 80, 190, 1, '2024-07-01 14:40:00', '2024-07-01 14:40:00', 7, '0.00', '2024-02-27', 54.75, '0.00', '22136'),
(96, '', 'Mini contactor plancha SCHEINER 12A 50/60hz LC1K120M7220/230V', NULL, 90, 890, 1, '2024-07-01 14:42:49', '2024-07-01 14:42:49', 7, '0.00', '2024-02-19', 483.002, '0.00', '22051'),
(97, '', 'Sello Magnético puerta Superior 60404 ropero termico cambro', NULL, 74, 1400, 1, '2024-07-01 14:44:52', '2024-07-01 14:44:52', 7, '0.00', '2024-02-29', 852, '0.00', '22061'),
(98, '', 'Interruptor presion KP35 060-500066 rango 0.2/+7.5 bar', NULL, 91, 1800, 1, '2024-07-01 14:46:40', '2024-07-01 14:46:40', 7, '0.00', '2024-01-04', 318.99, '0.00', '21639'),
(99, '', 'kid de thermofit varias medidas marca generica part N/A', NULL, 81, 210, 1, '2024-07-01 15:00:09', '2024-07-01 15:00:09', 7, '0.00', '2024-05-03', 465.69, '0.00', '22608'),
(100, '', 'Quemador para baño Maria Mod TM 173 con tapa', NULL, 82, 350, 1, '2024-07-01 15:01:53', '2024-07-01 15:01:53', 7, '0.00', '2024-05-03', 1663.79, '0.00', '22608'),
(101, '', 'Ventilador Industrial de pedestal pared 2 en 1, uso rudo', NULL, 84, 2700, 1, '2024-07-01 15:07:17', '2024-07-01 15:07:17', 7, '0.00', '2024-05-31', 1973.28, '0.00', '22858'),
(102, '', 'Manguera cristal 5/7mm -3/16 para equipo meiko', NULL, 85, 70, 1, '2024-07-01 15:08:32', '2024-07-01 15:08:32', 7, '0.00', '2024-05-31', 15, '0.00', '22858'),
(103, '', 'Banda para extractor medidas similares', NULL, 93, 700, 1, '2024-07-01 15:11:44', '2024-07-01 15:11:44', 7, '0.00', '2024-06-11', 440, '0.00', '22937'),
(104, '', 'Switch Carling 15A 250Vac /20A 125Vac 12V Lamp 1620R', NULL, 86, 189, 1, '2024-07-01 15:12:31', '2024-07-01 15:12:31', 7, '0.00', '2024-06-13', 24, '0.00', '22973'),
(105, '', 'Manguera trenzada silicona reforzada 1\" PCI helipuerto', NULL, 73, 1100, 1, '2024-07-01 15:38:59', '2025-01-29 18:33:12', 7, '0.00', '2024-01-30', 540.98, '0.00', '21820'),
(106, '', 'Aceite para máquina de costura', NULL, 87, 318.97, 1, '2024-07-01 16:35:51', '2024-07-01 16:35:51', 7, '0.00', '2024-03-20', 470, '0.00', '22253'),
(107, '', 'Polea ventilador extracción 2MA58 maska inst 1 3/8\"', NULL, 88, 1900, 1, '2024-07-01 16:38:11', '2025-01-29 18:33:22', 7, '0.00', '2024-03-20', 522, '0.00', '22253'),
(108, '', 'Catarina 40b35 para tortilladora rodotec 100', NULL, 92, 450, 1, '2024-07-02 09:18:18', '2024-07-02 09:18:18', 7, '0.00', '2024-03-13', 376.44, '0.00', '22213'),
(109, '', 'thermo fit negro 3/4 de pulgada 19mm rq sig 9082 rq ek 10252', NULL, 89, 25, 1, '2024-07-02 09:25:33', '2024-07-02 09:25:33', 7, '0.00', '2024-05-08', 15.85, '0.00', '22608'),
(110, '', 'Tanque de gas Turner Map/ pro 400 gr, negro modelo CB-1000', NULL, 96, 280, 1, '2024-07-02 13:19:12', '2024-07-02 13:19:12', 11, '0.00', '2024-01-11', 181.03, '0.00', '21680'),
(111, '', 'Grasa líquida HHS 2000 ST de 500 ml, marca Wurth', NULL, 97, 350, 1, '2024-07-02 13:20:13', '2024-07-02 13:20:13', 11, '0.00', '2024-01-11', 277.7, '0.00', '21680'),
(112, '', 'Tubería de pvc hidráulico y accesorios', NULL, 98, 23690, 1, '2024-07-02 13:21:12', '2024-07-02 13:21:12', 11, '0.00', '2024-01-30', 13578.2, '0.00', '1024'),
(113, '', 'Adhesivo epoxico Fester', NULL, 105, 2428, 1, '2024-07-02 13:22:35', '2024-07-02 13:22:35', 11, '0.00', '2024-02-14', 1542, '0.00', '1073'),
(114, '', 'Base hidráulica', NULL, 99, 320, 1, '2024-07-02 13:23:53', '2024-07-02 13:23:53', 11, '0.00', '2024-02-17', 190, '0.00', '1087'),
(115, '', 'Rotomartillo 1/2\" 700 W 2600 RPM 1.9 kg Dewalt', NULL, 100, 3500, 1, '2024-07-02 13:25:43', '2025-01-29 18:33:31', 11, '0.00', '2024-02-20', 2112.07, '0.00', '1098'),
(116, '', 'Separador de muro de 45 cm', NULL, 101, 80, 1, '2024-07-02 13:27:27', '2024-07-02 13:27:27', 11, '0.00', '2024-03-15', 25.8621, '0.00', '1201'),
(117, '', 'Copa diamante turbo 7¨ truper', NULL, 103, 4534.99, 1, '2024-07-02 13:29:35', '2024-07-02 13:29:35', 11, '0.00', '2024-05-06', 3047.41, '0.00', '1390'),
(118, '', 'Cable uso rudo 4x8', NULL, 104, 307, 1, '2024-07-02 13:30:36', '2024-07-02 13:30:36', 11, '0.00', '2024-05-14', 176.12, '0.00', '1409'),
(120, '', 'Empaque perfil estándar espesor 1.90mm marca Coldtek ', NULL, 106, 75, 1, '2024-07-02 13:48:44', '2024-07-02 13:48:44', 11, '0.00', '2024-03-11', 35.58, '0.00', '22187'),
(122, '', 'CUBREBOCAS BLANCO TRICAPA', NULL, 108, 1.5, 1, '2024-07-03 09:25:15', '2024-07-03 09:25:15', 4, '0.00', '2023-02-16', 0.82, '0.00', '19153'),
(123, '', 'VENDA 20 CM (Venda 30 CM)', NULL, 109, 40, 1, '2024-07-03 09:26:29', '2024-07-03 09:26:29', 4, '0.00', '2023-02-16', 27.59, '0.00', '19153'),
(124, '', 'ALCOHOL ETILICO  sin desnaturalizar 96° G.L', NULL, 110, 60, 1, '2024-07-03 09:27:36', '2024-07-03 09:27:36', 4, '0.00', '2023-02-16', 50, '0.00', '19153'),
(125, '', 'Dexametasona con neomicina solucion oftalmico', NULL, 111, 110, 1, '2024-07-03 09:28:38', '2024-07-03 09:28:38', 4, '0.00', '2023-02-16', 17, '0.00', '19153'),
(126, '', 'YODOPOVIDONA ESPUMA ANTISEPTICO (120 ML) (INMEDIATO)', NULL, 113, 20, 1, '2024-07-03 09:31:11', '2024-07-03 09:31:11', 4, '0.00', '2023-02-21', 11.21, '0.00', '19198'),
(127, '', 'Bata quirurgica de cirujano tipo ham azul plumbago talla mediana, reutilizable', NULL, 115, 490, 1, '2024-07-03 09:32:10', '2024-07-03 09:32:10', 4, '0.00', '2023-02-20', 186.65, '0.00', '19246'),
(128, '', 'Calibración de instrumento de medición de luxes NO SE PUDO CALIBRAR', NULL, 116, 4500, 1, '2024-07-03 09:34:12', '2024-07-03 09:34:12', 4, '0.00', '2023-03-06', 2513.39, '0.00', '19308-19826'),
(129, '', 'Pelicula radiografica 14 x17 carestream dryview', NULL, 117, 8200, 1, '2024-07-03 09:35:30', '2024-07-03 09:35:30', 4, '0.00', '2023-04-19', 6139.5, '0.00', '19613'),
(130, '', 'Cinta de empaque transparente 48 x 100', NULL, 118, 31, 1, '2024-07-03 09:36:28', '2024-07-03 09:36:28', 4, '0.00', '2023-04-04', 20.58, '0.00', '2357'),
(131, '', 'Multifuncional inyección tinta a color brother MFC-T920', NULL, 119, 8100, 1, '2024-07-03 09:37:27', '2024-07-03 09:37:27', 4, '0.00', '2023-05-23', 5814, '0.00', '19903'),
(132, '', 'Copul Resina Difer. Tonos marca 3m (A1,A2, A3)', NULL, 120, 55, 1, '2024-07-03 09:38:39', '2024-07-03 09:38:39', 4, '0.00', '2023-06-07', 29.31, '0.00', '20025'),
(133, '', 'Fresa carburo great white wold fisura recta #GW2', NULL, 121, 55, 1, '2024-07-03 09:39:58', '2024-07-03 09:39:58', 4, '0.00', '2023-06-07', 29.31, '0.00', '20025'),
(134, '', 'Fresa carburo ext balon americano great white ultra GW379-023', NULL, 122, 65, 1, '2024-07-03 09:40:44', '2024-07-03 09:40:44', 4, '0.00', '2023-06-07', 21.55, '0.00', '20025'),
(135, '', 'Fuente Alimentación p/servidores Ucs 200-210 CPB09-031A', NULL, 123, 3800, 1, '2024-07-03 09:41:46', '2024-07-03 09:41:46', 4, '0.00', '2023-06-26', 2625, '0.00', '20191'),
(136, '', 'Sensor de presión de silicio no compensado MPX10GP', NULL, 124, 820, 1, '2024-07-03 09:43:36', '2024-07-03 09:43:36', 4, '0.00', '2023-06-26', 636.31, '0.00', '20192'),
(137, '', 'Cinta de seguridad de alta resistencia color negro (uso rudo) de 48mm de grosor', NULL, 125, 70, 1, '2024-07-03 09:45:05', '2024-07-03 09:45:05', 4, '0.00', '2023-07-26', 35, '0.00', '20453'),
(138, '', 'Valvula mezcladora Honeywell mx serie Asse-1017MX128C', NULL, 126, 19900, 1, '2024-07-03 09:46:07', '2024-07-03 09:46:07', 4, '0.00', '2023-08-17', 15973.8, '0.00', '20550'),
(139, '', 'Aguja tipo mariposa BD VACUTAINER', NULL, 127, 2100, 1, '2024-07-03 09:47:07', '2024-07-03 09:47:07', 4, '0.00', '2023-08-10', 1200, '0.00', '20581'),
(140, '', 'Valvula esfera roscable de 3\" de cobre tipo L', NULL, 128, 3850, 1, '2024-07-03 09:48:30', '2025-01-29 18:33:36', 4, '0.00', '2023-08-12', 2601.72, '0.00', '20584'),
(141, '', 'Glutaraldehido Gafidex al 2% ', NULL, 107, 100, 1, '2024-07-03 09:49:18', '2024-07-03 09:49:18', 4, '0.00', '2023-08-10', 69, '0.00', '20577'),
(142, '', 'Agua oxigenada Frasco con 230 ml', NULL, 129, 25, 1, '2024-07-03 09:50:10', '2024-07-03 09:50:10', 4, '0.00', '2023-08-22', 11.21, '0.00', '20644'),
(143, '', 'Botes de gel antibacterial de 1 LT', NULL, 137, 50, 1, '2024-07-03 09:53:36', '2024-07-03 09:53:36', 4, '0.00', '2023-08-22', 45, '0.00', '20644'),
(144, '', 'Tubo de cobre flexible 3/8\" (rollo de 18 mts)', NULL, 130, 980, 1, '2024-07-03 09:55:50', '2025-01-29 18:33:42', 4, '0.00', '2023-10-18', 713.95, '0.00', '21097'),
(145, '', 'Guarda de medicamentos de lamina negra con 2 puertas abatibles y 2 cajones', NULL, 131, 5000, 1, '2024-07-03 09:56:43', '2024-07-03 09:56:43', 4, '0.00', '2023-12-04', 2250, '0.00', '21435'),
(146, '', 'Esfigmomanometro mercurial', NULL, 132, 2800, 1, '2024-07-03 09:57:40', '2024-07-03 09:57:40', 4, '0.00', '2023-12-04', 1037, '0.00', '21435'),
(147, '', 'Banca de espera para consultorio', NULL, 133, 4500, 1, '2024-07-03 09:59:34', '2024-07-03 09:59:34', 4, '0.00', '2023-12-04', 3318.98, '0.00', '21435'),
(148, '', 'Bascula con estadiometro altura y peso', NULL, 134, 5200, 1, '2024-07-03 10:00:28', '2024-07-03 10:00:28', 4, '0.00', '2023-12-04', 3533.62, '0.00', '21435'),
(149, '', 'Camilla rígida naranja', NULL, 136, 2750, 1, '2024-07-03 10:01:24', '2024-07-03 10:01:24', 4, '0.00', '2023-11-29', 2459, '0.00', 'NA'),
(150, '', 'Tarjetas de regalo liverpool', NULL, 135, 1100, 1, '2024-07-03 10:03:55', '2024-07-03 10:03:55', 4, '0.00', '2023-12-08', 1000, '0.00', '21568'),
(151, '', 'Motor evaporador York 220V 19W 0.23A YYK19-4A 154A30000175 para unidad condensadora', NULL, 138, 980, 1, '2024-07-03 15:53:18', '2024-07-03 15:53:18', 7, '0.00', '2023-01-05', 723, '0.00', '18805'),
(152, '', 'Kit master reparacion adaptador carga mastercool #91335', NULL, 140, 2500, 1, '2024-07-03 15:55:57', '2024-07-03 15:55:57', 7, '0.00', '2023-01-10', 1880, '0.00', '18836'),
(153, '', 'Contactor SCHNEIDER ELECTRIC Modelo: LC1K0601B7 24\"', NULL, 141, 2700, 1, '2024-07-03 15:58:15', '2025-01-29 18:33:47', 7, '0.00', '2023-01-23', 431.32, '0.00', '18943'),
(154, '', 'Bobina Danfoss  #part  333716 coil, p/condensadora', NULL, 142, 1400, 1, '2024-07-03 15:59:23', '2024-07-03 15:59:23', 7, '0.00', '2023-02-04', 568.07, '0.00', '18251'),
(155, '', 'Pasa muros para cuerpo presostato para sarteneta', NULL, 143, 55, 1, '2024-07-03 16:00:21', '2024-07-03 16:00:21', 7, '0.00', '2023-01-19', 13.711, '0.00', '18911'),
(156, '', 'Caratula gris o membrana, de amasadora de Pan Zucchelli', NULL, 144, 4800, 1, '2024-07-03 16:01:06', '2024-07-03 16:01:06', 7, '0.00', '2023-03-13', 1575, '0.00', '19364'),
(157, '', 'Rueda giratoria marca TermoTech hule/aluminio 4\"x 1 1/4\" Colson', NULL, 145, 2050, 1, '2024-07-03 16:03:30', '2025-01-29 18:33:55', 7, '0.00', '2023-04-26', 1415.56, '0.00', '19684'),
(158, '', 'Bujía o electrodo ignición con cable marmita volteo MGV80', NULL, 146, 400, 1, '2024-07-03 16:04:30', '2024-07-03 16:04:30', 7, '0.00', '2023-04-19', 87.15, '0.00', '19614'),
(159, '', 'O ring para válvula # part 1012 despachador de bebidas', NULL, 148, 65, 1, '2024-07-03 16:05:28', '2024-07-03 16:05:28', 7, '0.00', '2023-04-19', 30.17, '0.00', '19609'),
(160, '', 'Interruptor de presión apra compresor condor MDR-11/11 EA E304323 LR59232-3', NULL, 149, 890, 1, '2024-07-03 16:06:33', '2024-07-03 16:06:33', 7, '0.00', '2023-04-19', 472.5, '0.00', '19609'),
(161, '', 'Termostado Control Temperatura Emerson #4356200 TS1-E2A emerson 4356200', NULL, 150, 1550, 1, '2024-07-03 16:07:26', '2024-07-03 16:07:26', 7, '0.00', '2023-04-26', 850.86, '0.00', '19686'),
(162, '', 'Banda polinitrilo de 3.35mm para banda transportadora 19 cm de ancho, rango de tensión 120 piw', NULL, 151, 315, 1, '2024-07-03 16:08:28', '2024-07-03 16:08:28', 7, '0.00', '2023-04-25', 203.5, '0.00', '19681'),
(163, '', 'Polea de motor 8066184', NULL, 168, 550, 1, '2024-07-03 16:11:14', '2024-07-03 16:11:14', 7, '0.00', '2023-05-22', 257.75, '0.00', '19886'),
(164, '', 'Banda de motor w10198086', NULL, 153, 660, 1, '2024-07-03 16:12:28', '2024-07-03 16:12:28', 7, '0.00', '2023-05-22', 368.65, '0.00', '19886'),
(165, '', 'Válvula de gas  SV9601M4225 MCA HONEYWELL P/ MARMITA', NULL, 155, 7800, 1, '2024-07-03 16:14:36', '2024-07-03 16:14:36', 7, '0.00', '2023-06-12', 4737.4, '0.00', '20067'),
(166, '', 'Relevador Finder 72.01.8 125.000, 16 A , 250 v p/marmita', NULL, 156, 1500, 1, '2024-07-03 16:15:40', '2024-07-03 16:15:40', 7, '0.00', '2023-07-07', 1000, '0.00', '20301'),
(167, '', 'Calibracion de 1 juego de pesas en acero inoxidable de 1g a 500 g(12 piezas) en clase M1 MARCA PROVIMEX CIENTIFICA MODELO; PVE-12KM1, SERIE;B00018, IDENTIFICADO COMO UPPCOA03', NULL, 158, 2600, 1, '2024-07-03 16:16:50', '2024-07-03 16:16:50', 7, '0.00', '2023-08-28', 1692.39, '0.00', '20695'),
(168, '', 'Aceite sintético Dewalt synthetic oil d55001 ', NULL, 159, 1000, 1, '2024-07-03 16:18:26', '2024-07-03 16:18:26', 7, '0.00', '2023-08-12', 487.64, '0.00', '20587'),
(169, '', ' Polea de motor 8066184 p/secadora Maytag 7MMEDC300DV', NULL, 160, 450, 1, '2024-07-03 16:20:41', '2024-07-03 16:20:41', 7, '0.00', '2023-09-25', 185, '0.00', '20877'),
(170, '', 'Fusible Neosed 63A Classic c/linea c/scc, 3019.0405p (63NZ02,63A/400VCA-250VCD)', NULL, 161, 125, 1, '2024-07-03 16:21:38', '2024-07-03 16:21:38', 7, '0.00', '2023-10-02', 87.65, '0.00', '20917'),
(171, '', 'Colchones Spring Air morgan matrimonial ', NULL, 162, 6200, 1, '2024-07-03 16:22:42', '2024-07-03 16:22:42', 7, '0.00', '2023-10-05', 4223.28, '0.00', '20972'),
(172, '', 'Flecha para extractor de 1\" 3/16', NULL, 163, 1500, 1, '2024-07-03 16:23:39', '2025-01-29 18:34:00', 7, '0.00', '2023-08-24', 600, '0.00', '21133'),
(173, '', 'Relay Nev 1/2 AP 120 V 117U6020  para refrigerador Modelo T-49', NULL, 164, 550, 1, '2024-07-03 16:24:34', '2024-07-03 16:24:34', 7, '0.00', '2023-11-03', 83.69, '0.00', '21213'),
(174, '', 'Bomba de inyección Aquatec 220 PSI modelo 58-FLC-220# parte 5883-Q561-0524', NULL, 165, 5600, 1, '2024-07-03 16:25:41', '2024-07-03 16:25:41', 7, '0.00', '2023-11-06', 4765.03, '0.00', '21216'),
(175, '', 'Sello mecanico jhon crane7410F de 1 1/4\"', NULL, 166, 800, 1, '2024-07-03 16:26:42', '2025-01-29 18:34:10', 7, '0.00', '2023-12-04', 595, '0.00', '21429'),
(176, '', 'Evaporadora para congelación modelo LET200BC', NULL, 167, 80000, 1, '2024-07-03 16:28:02', '2024-07-03 16:28:02', 7, '0.00', '2023-11-27', 68965.5, '0.00', '8316'),
(177, '', 'Botonera ON/OFF de 3 polos de 250V', NULL, 170, 280, 1, '2024-07-04 15:19:33', '2024-07-04 15:19:33', 11, '0.00', '2023-01-19', 131.89, '0.00', '18899'),
(178, '', 'Valvula selenoide EV220A 14B G 12E NC000 G 1/2\" 042U4', NULL, 171, 2600, 1, '2024-07-04 15:20:33', '2025-01-29 18:35:27', 11, '0.00', '2023-01-23', 1041.15, '0.00', '18061'),
(179, '', 'Motor para condensadora model no: 048A11O1642, 0.80 HP Condenser Fans HVAC/R Motor 1 phase, 1075 RPM, 200-230/460V, 48Y Frame, OPAOCondenser Fans motir, marca Marathon.', NULL, 173, 8500, 1, '2024-07-04 15:21:58', '2024-07-04 15:21:58', 11, '0.00', '2023-02-14', 2059.66, '0.00', '19110'),
(180, '', 'Turbina #part 10352036 p/Mini split Trane 4MXW5512A1000A', NULL, 174, 1400, 1, '2024-07-04 15:23:22', '2024-07-04 15:23:22', 11, '0.00', '2023-02-15', 433.29, '0.00', '19116'),
(181, '', 'Kit humidificador, marca nortec, modelo MES2-010/440-660/1', NULL, 175, 38000, 1, '2024-07-04 15:24:52', '2024-07-04 15:24:52', 11, '0.00', '2023-03-07', 24810.2, '0.00', '19297'),
(182, '', 'Aspas HAP-9001096 Motor con uni pres DATA AIR DARC30', NULL, 176, 1600, 1, '2024-07-04 15:25:50', '2024-07-04 15:25:50', 11, '0.00', '2023-03-02', 940, '0.00', '19283'),
(183, '', 'Capacitor mod CBB65 5 Uf +-a 370/440 vac D 50/60 h', NULL, 177, 250, 1, '2024-07-04 15:26:46', '2024-07-04 15:26:46', 11, '0.00', '2023-06-21', 81.9, '0.00', '20152'),
(184, '', 'Filtro Deshidratador marca Sportan Tipo C 415-S', NULL, 78, 450, 1, '2024-07-04 15:27:35', '2024-07-04 15:27:35', 11, '0.00', '2023-07-07', 90.09, '0.00', '20302'),
(185, '', 'Escoba vertical con cerdas tipo sintético amarillo marca vikan', NULL, 204, 850, 1, '2024-07-04 16:27:42', '2024-07-04 16:27:42', 4, '0.00', '2024-01-23', 424.17, '0.00', '4047'),
(186, '', 'Lente de seguridad 3m sf410as SECURE FIT S400 espejado interior/exterior antirayadura', NULL, 202, 115, 1, '2024-07-04 16:28:43', '2024-07-04 16:28:43', 4, '0.00', '2024-03-20', 69.75, '0.00', '4421'),
(187, '', 'Guantes de nitrilo color negro en cubeta con 500 piezas talla mediana marca Uline', NULL, 186, 2300, 1, '2024-07-04 16:29:53', '2024-07-04 16:29:53', 4, '0.00', '2024-01-23', 2090, '0.00', '4051'),
(188, '', 'Casco de protección topgard M454721 MSA dieléctrico blanco', NULL, 201, 820, 1, '2024-07-04 16:31:14', '2024-07-04 16:31:14', 4, '0.00', '2024-01-23', 52.32, '0.00', '4051'),
(189, '', 'Faja con soporte sacrolumbar elastico banda', NULL, 185, 128, 1, '2024-07-04 16:32:10', '2024-07-04 16:32:10', 4, '0.00', '2024-01-23', 84, '0.00', '4051'),
(190, '', 'Guante de nitrilo anticorte nivel 5 anti impacto y anti vibración', NULL, 199, 450, 1, '2024-07-04 16:32:55', '2024-07-04 16:32:55', 4, '0.00', '2024-01-23', 376, '0.00', '4051'),
(191, '', 'Guante de polietileno con palma de nitrilo arenado para carga manual 5#7, 10#8, 10 #9, 10#10', NULL, 200, 65, 1, '2024-07-04 16:33:55', '2024-07-04 16:33:55', 4, '0.00', '2024-01-23', 41.66, '0.00', '4051'),
(192, '', 'Blot Klnud dieléctrico. Botin Blont marco industrial casquillo de policarbonato komposite cuero ganado vacuno lisa color negro suela de PU con patin de hule negro', NULL, 191, 935, 1, '2024-07-04 16:37:07', '2024-07-04 16:37:07', 4, '0.00', '2024-03-11', 729, '0.00', '4362'),
(193, '', 'Bolsa para botiquin de primeros auxilios, dimensiones 45x30x20 cm', NULL, 190, 550, 1, '2024-07-04 16:39:25', '2024-07-04 16:39:25', 4, '0.00', '2024-02-20', 295.89, '0.00', '4243'),
(194, '', 'Aceite para motor a gasolina 5W-30 sintético, mobil super 5000 bote de 4.73 L', NULL, 198, 600, 1, '2024-07-04 16:40:37', '2024-07-04 16:40:37', 4, '0.00', '2024-02-13', 3840, '0.00', '4204'),
(195, '', 'Bateria para camioneta RAM 4000', NULL, 197, 4300, 1, '2024-07-04 16:41:36', '2024-07-04 16:41:36', 4, '0.00', '2024-02-13', 3221.07, '0.00', '4204'),
(196, '', 'Aceite para motor a diesel 10W-40 sintético mobil super 5000. 4.73 L', NULL, 196, 620, 1, '2024-07-04 16:42:26', '2024-07-04 16:42:26', 4, '0.00', '2024-02-13', 452.24, '0.00', '4204'),
(197, '', 'Plafon circular color transparente (reversa) de 4\" # PL-243CR-16-B', NULL, 195, 400, 1, '2024-07-04 16:43:34', '2025-01-29 18:35:33', 4, '0.00', '2024-02-19', 78, '0.00', '4233'),
(198, '', 'Pastilla de detergente Rational color rojo, cubeta', NULL, 178, 2500, 1, '2024-07-04 16:44:31', '2024-07-04 16:44:31', 4, '0.00', '2024-02-26', 1870, '0.00', '4294'),
(199, '', 'Bote Rubbermaid amarillo 44 galones', NULL, 194, 2200, 1, '2024-07-04 16:45:16', '2024-07-04 16:45:16', 4, '0.00', '2024-03-11', 1521, '0.00', '4361'),
(200, '', 'Plato postre blanco polar', NULL, 193, 46, 1, '2024-07-04 16:46:05', '2024-07-04 16:46:05', 4, '0.00', '2024-03-11', 20.69, '0.00', '4361'),
(201, '', 'Bota para soldador marca ROGU con envío a oaxaca', NULL, 192, 713, 1, '2024-07-04 16:46:47', '2024-07-04 16:46:47', 4, '0.00', '2024-04-05', 545, '0.00', '4504'),
(202, '', 'Botella para atoizador capacidad 500 ml', NULL, 205, 8, 1, '2024-07-04 16:49:17', '2024-07-04 16:49:17', 0, '0.00', '2024-04-05', 5.17, '0.00', '4502'),
(203, '', 'Guantes suprannema anticorte marca urrea con recubrimiento de nitrilo en la palma talla grande', NULL, 187, 165, 1, '2024-07-04 16:50:32', '2024-07-08 13:02:47', 4, '0.00', '2024-05-21', 108, '0.00', '4774'),
(204, '', 'Cartuchos gases acidos para respiradores Res-600 y Res-X marca TRUPER', NULL, 184, 210, 1, '2024-07-04 16:51:40', '2024-07-04 16:51:40', 4, '0.00', '2024-05-21', 164.99, '0.00', '4775'),
(205, '', 'Lentes de seguridad VIRTUA CCS sellados 3M Z94.3', NULL, 189, 280, 1, '2024-07-04 16:52:15', '2024-07-04 16:52:15', 4, '0.00', '2024-05-21', 153.93, '0.00', '4775'),
(206, '', 'Manga resistente al corte A3 Kevlar con orifico pulgar GOLDEN EAGLE K 218 color amarillo ', NULL, 188, 85, 1, '2024-07-04 16:52:51', '2024-07-04 16:52:51', 4, '0.00', '2024-05-21', 82.76, '0.00', '4772'),
(207, '', 'Botella para atomizador capacidad 500 ml', NULL, 180, 8, 1, '2024-07-04 16:54:05', '2024-07-04 16:54:05', 4, '0.00', '2024-05-06', 3.2, '0.00', '4664'),
(208, '', 'Tabletas de Rojo fenol para PH', NULL, 179, 320, 1, '2024-07-04 16:55:41', '2024-07-04 16:55:41', 4, '0.00', '2024-06-17', 82.5, '0.00', '4966'),
(209, '', 'Acetona (bidon 20 litros)', NULL, 206, 74, 1, '2024-07-05 09:32:17', '2024-07-05 09:32:17', 4, '0.00', '2024-02-07', 52.5, '0.00', '4151'),
(210, '', 'Shampooo para autos DOGOWASH marca DOGO', NULL, 207, 28, 1, '2024-07-05 09:33:21', '2024-07-05 09:33:21', 4, '0.00', '2024-02-06', 20, '0.00', '4203'),
(211, '', 'Diseño e impresión de señalamiento de precaucion piso mojado 18x32 y Prohibido el paso vinil alta calidad', NULL, 210, 15, 1, '2024-07-05 09:45:03', '2024-07-05 09:45:03', 4, '0.00', '2024-02-12', 10, '0.00', '4180'),
(212, '', 'Velcro color blanco 25mm', NULL, 211, 0, 1, '2024-07-05 09:48:26', '2024-07-05 09:48:26', 4, '0.00', '2024-04-22', 0, '0.00', '4599'),
(213, '', 'Cilindros térmicos', NULL, 208, 44, 1, '2024-07-05 09:49:23', '2024-07-05 09:49:23', 4, '0.00', '2024-06-11', 40, '0.00', '4918'),
(214, '', 'Cinta de empaque transparente 48 mm x 100mts', NULL, 212, 31, 1, '2024-07-05 10:41:19', '2024-07-05 10:41:19', 4, '0.00', '0000-00-00', 21.38, '0.00', '1968'),
(215, '', 'Guante Anticorte Recubierto de Nitrilo espumado negro, guante anticortenivel 5 con certificación, ASTM ANSI A4 además de la norma EN388 2016 con niveles 4544D. Marca Duraflex', NULL, 213, 145, 1, '2024-07-05 10:42:20', '2024-07-05 10:42:20', 4, '0.00', '2024-02-16', 99.14, '0.00', '2123'),
(216, '', 'Bateria LTH 42-500', NULL, 214, 2700, 1, '2024-07-05 10:43:10', '2024-07-05 10:43:10', 4, '0.00', '2023-03-22', 2259.8, '0.00', '2310'),
(217, '', 'Birlos de rueda para VW polo 2020', NULL, 215, 35, 1, '2024-07-05 10:43:53', '2024-07-05 10:43:53', 4, '0.00', '2023-03-22', 21.51, '0.00', '2310'),
(218, '', 'Neumático 185/60 R15 marca winda ', NULL, 216, 1300, 1, '2024-07-05 10:44:30', '2024-07-05 10:44:30', 4, '0.00', '2023-03-22', 930.17, '0.00', '2310'),
(219, '', 'Voltaren emulgel 30g', NULL, 217, 100, 1, '2024-07-05 10:45:17', '2024-07-05 10:45:17', 4, '0.00', '2023-05-30', 70.7, '0.00', '2649'),
(220, '', 'Bolsas 60 x100 (10kg verde y 10 kg azul)', NULL, 218, 80, 1, '2024-07-05 10:46:16', '2024-07-05 10:46:16', 4, '0.00', '2023-05-31', 64, '0.00', '2650/2728'),
(221, '', 'Filtro para desbrozadora Makita EM3400U', NULL, 219, 96, 1, '2024-07-05 10:47:26', '2024-07-05 10:47:26', 4, '0.00', '2023-05-31', 0, '0.00', '2755'),
(222, '', 'Guantes de carnaza color café', NULL, 220, 67, 1, '2024-07-05 10:48:17', '2024-07-05 10:48:17', 4, '0.00', '2023-08-25', 33.07, '0.00', '3135'),
(223, '', 'Cono de hilo de poliester en color beige calibre 125', NULL, 221, 35, 1, '2024-07-05 10:49:20', '2024-07-05 10:49:20', 4, '0.00', '2023-08-04', 25, '0.00', '2999'),
(224, '', 'cinta metrica', NULL, 222, 7, 1, '2024-07-05 10:49:59', '2024-07-05 10:49:59', 4, '0.00', '2023-08-04', 4.99, '0.00', '2999'),
(225, '', 'Cofia plisada color blanco paquete de 100', NULL, 223, 85, 1, '2024-07-05 10:50:38', '2024-07-05 10:50:38', 4, '0.00', '2023-08-29', 55.87, '0.00', '3155'),
(226, '', 'Pelicula autoadherible rollo', NULL, 224, 400, 1, '2024-07-05 10:51:34', '2024-07-05 10:51:34', 4, '0.00', '2023-09-07', 239.5, '0.00', '3199'),
(227, '', 'cofias plisadas color blanco ', NULL, 225, 85, 1, '2024-07-05 10:52:15', '2024-07-05 10:52:15', 4, '0.00', '2023-09-11', 40, '0.00', '3211'),
(228, '', 'Carretes o bobina de metal para maquina de coser recta de 2.1 cm de diametro x 1 de alto con orificios', NULL, 226, 12, 1, '2024-07-05 10:52:57', '2024-07-05 10:52:57', 4, '0.00', '2023-10-02', 4.49, '0.00', '3349'),
(229, '', 'Bota industrial sanitaria premium (PVC)con casquillo de poliamida talla 23', NULL, 227, 550, 1, '2024-07-05 10:54:21', '2024-07-05 10:54:21', 4, '0.00', '2023-10-02', 358, '0.00', '3349'),
(230, '', 'Guantes de neopreno para manejo de químicos talla M', NULL, 228, 85, 1, '2024-07-05 10:55:26', '2024-07-05 10:55:26', 4, '0.00', '2023-10-02', 65.52, '0.00', '3348'),
(231, '', 'Guante para mecánico piel sintético marca MIKEL´S  Unitalla', NULL, 229, 450, 1, '2024-07-05 10:56:20', '2024-07-05 10:56:20', 4, '0.00', '2023-09-19', 289.66, '0.00', '3268'),
(232, '', 'Casco de seguridad con matraca blanco marca infra moselo 1CP210M', NULL, 230, 110, 1, '2024-07-05 10:56:54', '2024-07-05 10:56:54', 4, '0.00', '2023-09-19', 81.89, '0.00', '3268'),
(233, '', 'Tabletas para PH (Tabletas rojo fenol para ph 100 tabletas)', NULL, 231, 320, 1, '2024-07-05 10:57:47', '2024-07-05 10:57:47', 4, '0.00', '2023-10-17', 180, '0.00', '3524'),
(234, '', 'Tabla de corte verde (44x30 cm) ', NULL, 232, 350, 1, '2024-07-05 10:58:30', '2024-07-05 10:58:30', 4, '0.00', '2023-10-12', 229.88, '0.00', '3439'),
(235, '', 'Capacillo no.72, paquete de 500 pz. Marca corrufácil', NULL, 233, 65, 1, '2024-07-05 10:59:11', '2024-07-05 10:59:11', 4, '0.00', '2023-10-12', 50.862, '0.00', '3428'),
(236, '', 'Cubeta 20 litros', NULL, 234, 110, 1, '2024-07-05 10:59:57', '2024-07-05 10:59:57', 4, '0.00', '2023-10-12', 38.57, '0.00', '3428'),
(237, '', 'Chamarra Térmica', NULL, 235, 1400, 1, '2024-07-05 11:01:08', '2024-07-05 11:01:08', 4, '0.00', '2023-11-27', 760, '0.00', '3713'),
(238, '', 'Reconocimientos grabados', NULL, 236, 275, 1, '2024-07-05 11:01:46', '2024-07-05 11:01:46', 4, '0.00', '2023-12-14', 200, '0.00', '3831'),
(239, '', 'Mango ultrahigiénico de polipropileno vikan 1500 mmm amarillo', NULL, 204, 890, 1, '2024-07-08 13:21:20', '2024-07-08 13:21:20', 4, '0.00', '2024-01-23', 407.194, '0.00', ''),
(240, '', 'Equipo medidor de humedad relativa (higrómetro) marca Uplayteck con certificado de calibración', NULL, 43, 1000, 1, '2024-07-08 14:14:40', '2024-07-08 14:14:40', 7, '0.00', '2024-01-03', 767.24, '0.00', ''),
(241, '', 'Bomba original de Disel Thermo king 41-7059', NULL, 43, 3700, 1, '2024-07-08 16:20:27', '2024-07-08 16:20:27', 4, '0.00', '2024-01-18', 2675.86, '0.00', '4026'),
(242, '', 'Bateria 12V LTH arranque en frio 550 amperes modelo H-42-550', NULL, 135, 3200, 1, '2024-07-08 16:21:40', '2024-07-08 16:21:40', 4, '0.00', '2024-01-18', 2456.25, '0.00', '4026'),
(243, '', 'Prefiltros 5 N11', NULL, 91, 17, 1, '2024-07-08 16:29:39', '2024-07-08 16:29:39', 4, '0.00', '2024-01-23', 2.49, '0.00', '4051'),
(244, '', 'Guantes de jardin transpirables con garras, tamaño mediano color negro', NULL, 91, 90, 1, '2024-07-08 16:30:27', '2024-07-08 16:30:27', 4, '0.00', '2024-01-23', 12.8, '0.00', '4051'),
(245, '', 'Filtro para partículas 3M  5N11 N95, protección respirarotira, 100 unidades por caja', NULL, 91, 15, 1, '2024-07-08 16:31:24', '2024-07-08 16:31:24', 4, '0.00', '2024-01-23', 6.4, '0.00', '4051'),
(246, '', 'Guante de nailon para baja temperatura interior de riso acrilico', NULL, 185, 185, 1, '2024-07-08 16:32:17', '2024-07-08 16:32:17', 4, '0.00', '2024-01-23', 98.5, '0.00', '4051'),
(247, '', 'Mangas kevlar de doble capa con orificio para el pulgar', NULL, 185, 85, 1, '2024-07-08 16:32:57', '2024-07-08 16:32:57', 4, '0.00', '2024-01-23', 45, '0.00', '4051'),
(248, '', 'Guantes para altas temperaturas alimentos talla 12\"', NULL, 31, 155, 1, '2024-07-08 16:33:55', '2024-07-08 16:33:55', 4, '0.00', '2024-01-30', 92.3955, '0.00', '4102'),
(249, '', 'Guantes para altas temperaturas alimentos talla 14.5\"', NULL, 31, 155, 1, '2024-07-08 16:34:35', '2024-07-08 16:34:35', 4, '0.00', '2024-01-30', 107.103, '0.00', '4102'),
(250, '', 'Guante de nitrilo anticorte nivel 5de polietileno de alta densidad con carnaza y tejido kevlar en palma', NULL, 199, 170, 1, '2024-07-08 16:38:23', '2024-07-08 16:38:23', 4, '0.00', '2024-01-30', 41.66, '0.00', '4102'),
(251, '', 'Choclo Van Vien negro ORAAKLNUD dieléctrico. Choclo orazio. Chinela mocasin casquillo dekomposite-piel lisa negra', NULL, 191, 930, 1, '2024-07-08 16:39:20', '2024-07-08 16:39:20', 4, '0.00', '2024-03-11', 679, '0.00', '4362'),
(252, '', 'Bolsa deRPBI de 55 cm x 60 cm calibre 200, resistencia 12 kg paquete 100', NULL, 43, 3.5, 1, '2024-07-08 16:40:15', '2024-07-08 16:40:15', 4, '0.00', '2024-01-17', 1.83, '0.00', '4020'),
(253, '', 'bota riverline ergonomic modelo SPSP linea industrial extremo', NULL, 45, 930, 1, '2024-07-08 16:41:59', '2024-07-08 16:41:59', 4, '0.00', '2024-01-25', 779.16, '0.00', '4064'),
(254, '', 'Abrillantor para interiores y exteriores DOGOBRIL MAX', NULL, 207, 55, 1, '2024-07-08 16:43:20', '2024-07-08 16:43:20', 4, '0.00', '2024-02-06', 45, '0.00', '4203'),
(255, '', 'Limpiador para vidrios DOGOVID', NULL, 207, 28, 1, '2024-07-08 16:44:01', '2024-07-08 16:44:01', 4, '0.00', '2024-02-06', 22, '0.00', '4203'),
(256, '', 'Desengrasante biodegradable DOGOMOT', NULL, 207, 25, 1, '2024-07-08 16:44:38', '2024-07-08 16:44:38', 4, '0.00', '2024-02-06', 22, '0.00', '4203'),
(257, '', 'Filtro de aire del motor para polo Volkswaguen Polo serie MEX6B2602LT089295 año 2020', NULL, 12, 250, 1, '2024-07-08 16:48:34', '2024-07-08 16:48:34', 4, '0.00', '2024-02-19', 139, '0.00', '4233'),
(258, '', 'Plafon circular colorambar (intermitente) de 4\" # PL-243CR-08-A', NULL, 195, 400, 1, '2024-07-08 16:49:13', '2024-07-08 16:49:13', 4, '0.00', '2024-02-19', 78, '0.00', '4233'),
(259, '', 'Plafon circular color rojo (freno/posicion) de 4\" # PL-243CR-08-R', NULL, 195, 400, 1, '2024-07-08 16:49:47', '2024-07-08 16:49:47', 4, '0.00', '2024-02-19', 78, '0.00', '4233'),
(260, '', 'Jalador para vidrio 30 cm Ettore', NULL, 43, 410, 1, '2024-07-08 16:50:46', '2024-07-08 16:50:46', 4, '0.00', '2024-03-08', 399, '0.00', '4347'),
(261, '', 'Pastilla para horno Rational color rojo 56.00.210', NULL, 178, 2500, 1, '2024-07-08 16:52:32', '2024-07-08 16:52:32', 4, '0.00', '2024-04-30', 1870, '0.00', '4641'),
(262, '', 'Abrelatas industrial de acero aluminizado de 47 cm, incluye navaja', NULL, 43, 1500, 1, '2024-07-08 16:53:30', '2024-07-08 16:53:30', 4, '0.00', '2024-04-30', 844.22, '0.00', '4641'),
(263, '', 'Bota de seguridad Van Vien Blot Klnud Botin Negro', NULL, 191, 935, 1, '2024-07-08 16:54:12', '2024-07-08 16:54:12', 4, '0.00', '2024-03-12', 729, '0.00', '4378'),
(264, '', 'Choclo Van Vien negro ORAAKLNUD dieléctrico. Choclo orazio. Chinela mocasin casquillo dekomposite-piel lisa negra', NULL, 191, 930, 1, '2024-07-08 16:54:56', '2024-07-08 16:54:56', 4, '0.00', '2024-04-30', 679, '0.00', '4642'),
(265, '', 'Filtro 3M 5N para particulas libres de aceite P95', NULL, 14, 20, 1, '2024-07-08 16:57:43', '2024-07-08 16:57:43', 4, '0.00', '2024-05-21', 9.42, '0.00', '4774'),
(266, 'Cartucho', 'Cartucho para vapores organicos y gases acidos 3M Modelo 6003', NULL, 91, 280, 1, '2024-07-08 16:58:33', '2025-10-12 18:36:14', 4, '0.00', '2025-10-18', 8.79, '0.00', NULL),
(267, '', 'Lentes de seguridad  3m securefit ™  410 AS-antirayadura-espejado', NULL, 77, 155, 1, '2024-07-08 16:59:20', '2024-07-08 16:59:20', 4, '0.00', '2024-05-21', 126.91, '0.00', '4773'),
(268, '', 'Guantes suprannema anticorte marca urrea con recubrimiento de nitrilo en la palma talla grande ', NULL, 187, 165, 1, '2024-07-08 17:00:06', '2024-07-08 17:00:06', 4, '0.00', '2024-05-21', 108, '0.00', '4773'),
(269, '', 'Filtro 3M 5N 11 para particulas libres dr aceite P95', NULL, 14, 20, 1, '2024-07-08 17:00:42', '2024-07-08 17:00:42', 4, '0.00', '2024-05-21', 9.42, '0.00', '4773'),
(270, '', 'Guantes de jardin transpirables con garras, unitalla color verde/negro', NULL, 190, 90, 1, '2024-07-08 17:01:26', '2024-07-08 17:01:26', 4, '0.00', '2024-05-21', 35, '0.00', '4773'),
(271, '', 'Lentes de seguridad  3m securefit ™  410 AS-antirayadura-espejado', NULL, 77, 155, 1, '2024-07-08 17:02:15', '2024-07-08 17:02:15', 4, '0.00', '0000-00-00', 126.91, '0.00', ''),
(272, '', 'USGDM Guante supraneema recubrimiento de nitrilo talla mediana', NULL, 187, 165, 1, '2024-07-08 17:03:36', '2024-07-08 17:03:36', 4, '0.00', '2024-05-21', 108, '0.00', '4775'),
(273, '', 'USGDM Guante supraneema recubrimiento de nitrilo talla grande', NULL, 187, 165, 1, '2024-07-08 17:04:12', '2024-07-08 17:04:12', 4, '0.00', '2024-05-21', 108, '0.00', '4775'),
(274, '', 'USGDM Guante supraneema recubrimiento de nitrilo talla extra grande', NULL, 187, 165, 1, '2024-07-08 17:04:49', '2024-07-08 17:04:49', 4, '0.00', '2024-05-21', 108, '0.00', '4775'),
(275, '', 'Guantes de piel de cabra con dorso de carnaza Truper', NULL, 184, 125, 1, '2024-07-08 17:05:30', '2024-07-08 17:05:30', 4, '0.00', '2024-05-21', 85.34, '0.00', '4775'),
(276, '', 'Barboquejo con barbilla para casco de seguridad industrial CODIGO 12338 CLAVE BARBO-B', NULL, 184, 27, 1, '2024-07-08 17:06:19', '2024-07-08 17:06:19', 4, '0.00', '2024-05-21', 15.52, '0.00', '4775'),
(277, '', 'Mascarilla protectora respiratoria truper tipo concha', NULL, 184, 35, 1, '2024-07-08 17:06:56', '2024-07-08 17:06:56', 4, '0.00', '2024-05-21', 15.52, '0.00', '4775'),
(278, '', 'Truper guantes para mecanico alta sensibilidad CODIGO 10847 CLAVE GU-635q', NULL, 184, 245, 1, '2024-07-08 17:07:46', '2024-07-08 17:07:46', 4, '0.00', '2024-05-21', 162.93, '0.00', '4775'),
(279, '', 'Guante de neopreno para manejo de sustancias quimicas truper talla grande COODIGO: 14271 CLAVE: GU-813', NULL, 184, 83, 1, '2024-07-08 17:08:25', '2024-07-08 17:08:25', 4, '0.00', '2024-05-21', 50.86, '0.00', '4775'),
(280, '', 'Lentes de seguridad grises truper ultralite CODIGO: 15290 CLAVE: LEN LN', NULL, 184, 39, 1, '2024-07-08 17:08:54', '2024-07-08 17:08:54', 4, '0.00', '2024-05-21', 25, '0.00', '4775'),
(281, '', 'Filtro para partículas 3M ™  5N11 N95, protección respirarotira, 100 unidades por caja', NULL, 14, 20, 1, '2024-07-08 17:10:00', '2024-07-08 17:10:00', 4, '0.00', '2024-06-17', 7.54, '0.00', '4969'),
(282, '', 'USGDM Guante supraneema recubrimiento de nitrilo talla chica', NULL, 187, 165, 1, '2024-07-08 17:10:31', '2024-07-08 17:10:31', 4, '0.00', '2024-06-17', 108, '0.00', '4969'),
(283, '', 'Lentes de seguridad 3m sf4 10as secure fit s400 espejado interior/exterior antirayadura (700716500975)', NULL, 77, 155, 1, '2024-07-08 17:11:05', '2024-07-08 17:11:05', 4, '0.00', '2024-06-17', 109.41, '0.00', '4969'),
(284, '', 'Guante supraneema recubrimiento de nitrilo Urre (7M y 8 G)', NULL, 187, 165, 1, '2024-07-08 17:12:44', '2024-07-08 17:12:44', 4, '0.00', '2024-05-21', 108, '0.00', '4772'),
(285, '', 'Filtro para particulas 3 m ™ 5N 1 1 N95 proteccion respiratoria ', NULL, 14, 20, 1, '2024-07-08 17:13:22', '2024-07-08 17:13:22', 4, '0.00', '2024-05-21', 7.54, '0.00', '4772'),
(286, '', 'Guantes anticorte supraneema recubrimiento nitrilo usgdc urrea nivel de corte 5: 18 tallas chicas , 7 tallas medianas', NULL, 187, 165, 1, '2024-07-08 17:13:49', '2024-07-08 17:13:49', 4, '0.00', '2024-05-21', 108, '0.00', '4772'),
(287, '', 'Barbiquejo elastico con menton marca JYRSA', NULL, 43, 25, 1, '2024-07-08 17:14:45', '2024-07-08 17:14:45', 4, '0.00', '2024-05-21', 16.14, '0.00', '4772'),
(288, '', 'Guantes anticorte supraneema recubrimiento nitrilo usgdc urrea nivel de corte 5: 10 tallas medianas , 5 tallas grandes', NULL, 187, 165, 1, '2024-07-08 17:15:11', '2024-07-08 17:15:11', 4, '0.00', '2024-05-21', 108, '0.00', '4772'),
(289, '', 'Guantes anticorte supraneema recubrimiento nitrilo usgdc urrea nivel de corte 5: 15 tallas chicas , 5 tallas medianas', NULL, 187, 165, 1, '2024-07-08 17:15:39', '2024-07-08 17:15:39', 4, '0.00', '2024-05-21', 108, '0.00', '4772'),
(290, '', 'Respirador contra particulas N95 marca 3M o modelos similares', NULL, 43, 25, 1, '2024-07-08 17:16:20', '2024-07-08 17:16:20', 4, '0.00', '2024-05-21', 22.25, '0.00', '4772'),
(291, '', 'Prefiltros 5 N11 Marca 3M', NULL, 14, 20, 1, '2024-07-08 17:17:05', '2024-07-08 17:17:05', 4, '0.00', '2024-05-21', 7.54, '0.00', '4772'),
(292, '', 'Guantes de mecanico unitallas (equios industriales) Marca truper codigo 10847 talla grande ', NULL, 184, 245, 1, '2024-07-08 17:17:56', '2024-07-08 17:17:56', 4, '0.00', '2024-05-21', 162.93, '0.00', '4772'),
(293, '', 'Mangas kevlar de doble capa con orificio para el pulgar', NULL, 185, 85, 1, '2024-07-09 10:50:54', '2024-07-09 10:50:54', 4, '0.00', '2024-05-20', 45.35, '0.00', '4762'),
(294, '', 'Guante de nylon con palma nitrilo arenoso # 8 marca Handy Grip modelo NY 1350S', NULL, 182, 65, 1, '2024-07-09 10:51:39', '2024-07-09 10:51:39', 4, '0.00', '2024-05-20', 23.71, '0.00', '4762'),
(295, '', 'Guante de nylon con palma nitrilo arenoso # 9 marca Handy Grip modelo NY 1350S', NULL, 182, 65, 1, '2024-07-09 10:52:29', '2024-07-09 10:52:29', 4, '0.00', '2024-05-20', 23.71, '0.00', '4762'),
(296, '', 'Guante de nylon con palma nitrilo arenoso # 10 marca Handy Grip modelo NY 1350S', NULL, 182, 65, 1, '2024-07-09 10:53:00', '2024-07-09 10:53:00', 4, '0.00', '2024-05-20', 23.71, '0.00', '4762'),
(297, '', 'Bota industrial de PVC Mod B-PVC-BR # 24', NULL, 181, 285, 1, '2024-07-09 10:53:59', '2024-07-09 10:53:59', 4, '0.00', '2024-05-20', 180, '0.00', '4762'),
(298, '', 'Bota industrial de PVC Mod B-PVC-BR # 25', NULL, 181, 285, 1, '2024-07-09 10:54:37', '2024-07-09 10:54:37', 4, '0.00', '2024-05-20', 180, '0.00', '4762'),
(299, '', 'Bota industrial de PVC Mod B-PVC-BR # 26', NULL, 181, 285, 1, '2024-07-09 10:55:08', '2024-07-09 10:55:08', 4, '0.00', '2024-05-20', 180, '0.00', '4762'),
(300, '', 'Bota industrial de PVC Mod B-PVC-BR # 27', NULL, 181, 285, 1, '2024-07-09 10:55:37', '2024-07-09 10:55:37', 4, '0.00', '2024-05-20', 180, '0.00', '4762'),
(301, '', 'Bota industrial de PVC Mod B-PVC-BR # 28', NULL, 181, 285, 1, '2024-07-09 10:56:08', '2024-07-09 10:56:08', 4, '0.00', '2024-05-20', 180, '0.00', '4762'),
(302, '', 'Bota industrial de PVC Mod B-PVC-BR # 29', NULL, 181, 285, 1, '2024-07-09 10:56:41', '2024-07-09 10:56:41', 4, '0.00', '2024-05-20', 180, '0.00', '4762'),
(303, '', 'Pastilla de detergente Rational color rojo, cubeta', NULL, 178, 2500, 1, '2024-07-09 10:57:46', '2024-07-09 10:57:46', 4, '0.00', '2024-06-17', 1870, '0.00', '4966'),
(304, '', 'percutor de masaje muscular 4000mAh, 11 cabezales y 20 velocidades', NULL, 43, 900, 1, '2024-07-09 11:02:04', '2024-07-09 11:02:04', 4, '0.00', '2024-01-08', 870.25, '0.00', '21645'),
(305, '', 'lámina de 4 electrodos cuadrados de 5*5 cm', NULL, 43, 120, 1, '2024-07-09 11:03:01', '2024-07-09 11:03:01', 4, '0.00', '2024-01-08', 28.8, '0.00', '21645');
INSERT INTO `item_list` (`id`, `name`, `description`, `foto_producto`, `supplier_id`, `cost`, `status`, `date_created`, `date_updated`, `company_id`, `stock`, `date_purchase`, `product_cost`, `shipping_or_extras`, `oc`) VALUES
(306, '', 'bote de gel para ultrasonido de 250 ml', NULL, 43, 130, 1, '2024-07-09 11:03:45', '2024-07-09 11:03:45', 4, '0.00', '2024-01-08', 72.53, '0.00', '21645'),
(307, '', 'Kit de 5 compresas de semillas', NULL, 43, 800, 1, '2024-07-09 11:04:30', '2024-07-09 11:04:30', 4, '0.00', '2024-01-08', 538, '0.00', '21645'),
(308, '', 'compresas frias de 13*25 cm', NULL, 43, 130, 1, '2024-07-09 11:05:39', '2024-07-09 11:05:39', 4, '0.00', '2024-01-08', 99.33, '0.00', '21645'),
(309, '', 'set de 3 bandas de resistencia progresiva', NULL, 43, 290, 1, '2024-07-09 11:06:35', '2024-07-09 11:06:35', 4, '0.00', '2024-01-08', 144.27, '0.00', '21645'),
(310, '', 'par de polainas de 1/2 kg', NULL, 43, 150, 1, '2024-07-09 11:08:03', '2024-07-09 11:08:03', 4, '0.00', '2024-01-08', 110.91, '0.00', '21645'),
(311, '', 'par de polainas de 1 kg', NULL, 43, 175, 1, '2024-07-09 11:08:48', '2024-07-09 11:08:48', 4, '0.00', '2024-01-08', 130.22, '0.00', '21645'),
(312, '', 'pelot de plástico mini para balance fitnes, pilates, yoga de 20 cm', NULL, 43, 110, 1, '2024-07-09 11:09:31', '2024-07-09 11:09:31', 4, '0.00', '2024-01-08', 76.72, '0.00', '21645'),
(313, '', 'pelota de plástico grande 75 cm pilates fisioatleta', NULL, 43, 450, 1, '2024-07-09 11:10:17', '2024-07-09 11:10:17', 4, '0.00', '2024-01-08', 99, '0.00', '21645'),
(314, '', 'pelota de pilates 65 cm', NULL, 43, 310, 1, '2024-07-09 11:10:59', '2024-07-09 11:10:59', 4, '0.00', '2024-01-08', 259, '0.00', '21645'),
(315, '', 'par de mancuernas de 1/2 kg', NULL, 43, 180, 1, '2024-07-09 11:12:00', '2024-07-09 11:12:00', 4, '0.00', '2024-01-08', 65.2, '0.00', '21645'),
(316, '', 'par de mancuernas de 3 lb', NULL, 43, 150, 1, '2024-07-09 11:13:07', '2024-07-09 11:13:07', 4, '0.00', '2024-01-08', 101.72, '0.00', '21645'),
(317, '', 'par de mancuernas de 1 kg', NULL, 43, 150, 1, '2024-07-09 11:14:05', '2024-07-09 11:14:05', 4, '0.00', '2024-01-08', 127.92, '0.00', '21645'),
(318, '', 'aro de plástico', NULL, 210, 140, 1, '2024-07-09 11:16:30', '2024-07-09 11:16:30', 4, '0.00', '2024-01-08', 32, '0.00', '21645'),
(319, '', 'aceite para masaje 1 litro', NULL, 43, 240, 1, '2024-07-09 11:17:16', '2024-07-09 11:17:16', 4, '0.00', '2024-01-08', 188.1, '0.00', '21645'),
(320, '', 'camilla de masaje plegable de 185.5 cm *56 cm* 66 cm', NULL, 43, 2000, 1, '2024-07-09 11:18:04', '2024-07-09 11:18:04', 4, '0.00', '2024-01-08', 1206.03, '0.00', '21645'),
(321, '', 'kit de 12 ventosas neumáticas', NULL, 43, 260, 1, '2024-07-09 11:18:56', '2024-07-09 11:18:56', 4, '0.00', '2024-01-08', 147.52, '0.00', '21645'),
(322, '', 'juego didáctico apilable', NULL, 12, 590, 1, '2024-07-09 11:19:47', '2024-07-09 11:19:47', 4, '0.00', '2024-01-08', 449, '0.00', '21645'),
(323, '', 'kit de 5 ligas de resistencias con agarraderas', NULL, 43, 380, 1, '2024-07-09 11:20:41', '2024-07-09 11:20:41', 4, '0.00', '2024-01-08', 254.11, '0.00', '21645'),
(324, '', 'digoxina 0.250mg c/20 tab', NULL, 44, 120, 1, '2024-07-09 11:28:12', '2024-07-09 11:28:12', 4, '0.00', '2024-01-16', 55, '0.00', '21716'),
(325, '', 'acido ascorbico 2gr efervesente', NULL, 18, 70, 1, '2024-07-09 11:29:20', '2024-07-09 11:29:20', 4, '0.00', '2024-01-16', 43.3, '0.00', '21716'),
(326, '', 'bedoyecta tri 10000 c/jeringa 2ml c/5', NULL, 18, 460, 1, '2024-07-09 11:30:10', '2024-07-09 11:30:10', 4, '0.00', '2024-01-16', 423.29, '0.00', '21716'),
(327, '', 'dexametasona solucion inyectable de 8mg/ml con 1 ampolleta', NULL, 18, 30, 1, '2024-07-09 11:30:47', '2024-07-09 11:30:47', 4, '0.00', '2024-01-16', 18.5, '0.00', '21716'),
(328, '', 'Vendas de guata de 10 cm', NULL, 18, 50, 1, '2024-07-09 11:31:43', '2024-07-09 11:31:43', 4, '0.00', '2024-02-19', 19.3, '0.00', '21982'),
(329, '', 'Vendas elásticas de 5cm', NULL, 18, 20, 1, '2024-07-09 11:32:30', '2024-07-09 11:32:30', 4, '0.00', '2024-02-19', 6.5, '0.00', '21982'),
(330, '', 'Vendas elásticas de 10cm', NULL, 18, 30, 1, '2024-07-09 11:33:14', '2024-07-09 11:33:14', 4, '0.00', '2024-02-19', 13, '0.00', '21982'),
(331, '', 'Vendas elásticas de 15cm', NULL, 18, 40, 1, '2024-07-09 11:33:50', '2024-07-09 11:33:50', 4, '0.00', '2024-02-19', 18.1, '0.00', '21982'),
(332, '', 'Vendas elásticasde 30cm', NULL, 18, 50, 1, '2024-07-09 11:34:28', '2024-07-09 11:34:28', 4, '0.00', '2024-02-19', 30.17, '0.00', '21982'),
(333, '', 'Cartucho de cilindeo Magneta, Versalink C605', NULL, 46, 2200, 1, '2024-07-09 11:35:18', '2024-07-09 11:35:18', 4, '0.00', '2024-03-14', 1690, '0.00', '22215'),
(334, '', 'Fresa quirúrgica alta velocidad 151-Z diam 016 prima dental', NULL, 48, 150, 1, '2024-07-09 11:36:27', '2024-07-09 11:36:27', 4, '0.00', '2024-03-15', 77.59, '0.00', '22220'),
(335, '', 'Termómetro personal de mercurio', NULL, 43, 76, 1, '2024-07-09 11:37:14', '2024-07-09 11:37:14', 4, '0.00', '2024-03-15', 42.88, '0.00', '22220'),
(336, '', 'Válvula selenoide 2 vías 1/4\" NPT 127V', NULL, 50, 1450, 1, '2024-07-09 11:38:03', '2024-07-09 11:38:03', 4, '0.00', '2024-03-15', 1138.76, '0.00', '22220'),
(337, '', 'Papel térmico p/keratometro 56mm', NULL, 43, 60, 1, '2024-07-09 11:38:59', '2024-07-09 11:38:59', 4, '0.00', '2024-03-15', 7.2524, '0.00', '22220'),
(338, '', 'Lubricante para piezas de alta y baja velocidad spray L', NULL, 52, 140, 1, '2024-07-09 11:39:37', '2024-07-09 11:39:37', 4, '0.00', '2024-03-15', 34.48, '0.00', '22220'),
(339, '', 'Cloruro de etilo en aerosol 270 ml', NULL, 43, 150, 1, '2024-07-09 11:40:32', '2024-07-09 11:40:32', 4, '0.00', '2024-03-11', 56.75, '0.00', '22185'),
(340, '', 'Cuerda de rescate de 15 mts', NULL, 43, 750, 1, '2024-07-09 11:41:31', '2024-07-09 11:41:31', 4, '0.00', '2024-03-11', 225.96, '0.00', '22185'),
(341, '', 'Electrolitos orales', NULL, 18, 8, 1, '2024-07-09 11:42:42', '2024-07-09 11:42:42', 4, '0.00', '2024-03-11', 5.42, '0.00', '22185'),
(342, '', 'Cartucho de tambor negro color 560 xerox 013', NULL, 46, 9000, 1, '2024-07-09 11:44:11', '2024-07-09 11:44:11', 4, '0.00', '2024-03-20', 3803, '0.00', '22254'),
(343, '', 'Cartucho de tambor xerox 013R00664', NULL, 46, 5000, 1, '2024-07-09 12:02:12', '2024-07-09 12:02:12', 4, '0.00', '2024-03-28', 3743, '0.00', '22309'),
(344, '', 'Armaflex abierto de 3/4\" x 1/2\"', NULL, 56, 100, 1, '2024-07-09 12:03:11', '2024-07-09 12:03:11', 4, '0.00', '2024-03-22', 51.3, '0.00', '22284'),
(345, '', 'Válvula de gas Honeywell VR8304H4503 / VR8304H4230', NULL, 14, 6000, 1, '2024-07-09 12:06:27', '2024-07-09 12:06:27', 4, '0.00', '2024-04-03', 1695.6, '0.00', '22357'),
(346, '', 'Armaflex abierto de 7/8\" x 1/2\"', NULL, 32, 100, 1, '2024-07-09 12:07:54', '2025-01-29 18:36:03', 4, '0.00', '2024-05-22', 29.87, '0.00', '22804'),
(347, '', 'Tubo armaflex de recubrimiento 1 5/8 x 1/2 ', NULL, 32, 115, 1, '2024-07-09 13:41:46', '2024-07-09 13:41:46', 4, '0.00', '2024-05-22', 45.26, '0.00', '22804'),
(348, '', 'Placa de armaflex de 3/4\"x48\"x36\"aps 34043 ', NULL, 32, 450, 1, '2024-07-09 13:42:35', '2025-01-29 18:36:12', 4, '0.00', '2024-05-22', 253.7, '0.00', '22804'),
(349, '', 'Guante trabajo alta destreza Profelx 720LTR talla M', NULL, 12, 850, 1, '2024-07-09 13:43:12', '2024-07-09 13:43:12', 4, '0.00', '2024-06-12', 726.7, '0.00', '22956'),
(350, '', 'Oxoral Aseptic Flush solucion desinfectante unidad dent', NULL, 51, 1100, 1, '2024-07-09 13:43:52', '2024-07-09 13:43:52', 4, '0.00', '2024-06-12', 665, '0.00', '22960'),
(351, '', 'Equipo medidor de humedad relativa (higrómetro) marca Uplayteck con certificado de calibración', NULL, 43, 1000, 1, '2024-07-09 13:45:34', '2024-07-09 13:45:34', 7, '0.00', '2024-01-03', 767.24, '0.00', '21749'),
(352, '', 'Condensador 60uf 400V Cod: 2000988 para procesador de verduras sammis modelo 20720', NULL, 68, 1100, 1, '2024-07-09 13:46:24', '2024-07-09 13:46:24', 7, '0.00', '2024-01-03', 741.165, '0.00', '21749'),
(353, '', 'Rele de arranque inicial #2004078 marca SAMMIC para procesador de alimentos', NULL, 68, 1050, 1, '2024-07-09 13:47:08', '2024-07-09 13:47:08', 7, '0.00', '2024-01-24', 718.48, '0.00', '21764'),
(354, '', 'valvula rotolock 1 3/4\" PULG X7/8 PLG soldable con pertas de accesorios 1/4 universal para sistemas de refrigeracion  ', NULL, 72, 1200, 1, '2024-07-09 13:47:50', '2024-07-09 13:47:50', 7, '0.00', '2024-01-24', 909.68, '0.00', '21763'),
(355, '', 'tobera intercambiable ALCO-800537,#5  ', NULL, 34, 590, 1, '2024-07-09 13:49:32', '2024-07-09 13:49:32', 7, '0.00', '2024-01-23', 292.55, '0.00', '21760'),
(356, '', 'tobera intercambiable ALCO-800537,#4 ', NULL, 34, 590, 1, '2024-07-09 13:50:14', '2024-07-09 13:50:14', 7, '0.00', '2024-01-23', 268.78, '0.00', '21760'),
(357, '', 'valvula de de expansion ALCO-802421 para gas rerigerante R-22 y R 407C', NULL, 34, 1500, 1, '2024-07-09 13:51:19', '2024-07-09 13:51:19', 7, '0.00', '2024-01-23', 900, '0.00', '21760'),
(358, '', 'valvula de expansion ALCO-802459 para gas refrigerante R-404 C ', NULL, 70, 1700, 1, '2024-07-09 13:52:15', '2024-07-09 13:52:15', 7, '0.00', '2024-01-23', 1158.57, '0.00', '21760'),
(359, '', 'filtro deshidratador para linea refrigrate TD-415S', NULL, 34, 1400, 1, '2024-07-09 13:53:06', '2024-07-09 13:53:06', 7, '0.00', '2024-01-24', 793.08, '0.00', '21772'),
(360, '', 'compresor Emeson mod.ZS21KAE-TF5-818230 V3F60HZparacamara de frutas y verduras', NULL, 34, 22500, 1, '2024-07-09 13:54:00', '2024-07-09 13:54:00', 7, '0.00', '2024-01-29', 15577.3, '0.00', '21793'),
(361, '', 'Fusible tipo americano de cristal para tarjeta de 6 amp 250 V', NULL, 54, 6, 1, '2024-07-09 13:55:16', '2024-07-09 13:55:16', 7, '0.00', '2024-02-27', 4, '0.00', '22136'),
(362, '', 'Porta fusible tipo americano No. Parte 302828 para tortilladora Rodotec RT', NULL, 54, 25, 1, '2024-07-09 13:56:00', '2024-07-09 13:56:00', 7, '0.00', '2024-02-27', 15, '0.00', '22136'),
(363, '', 'Tarjeta maestra báscula Torrey Mod EQM 200/400', NULL, 69, 2900, 1, '2024-07-09 13:56:41', '2024-07-09 13:56:41', 7, '0.00', '2024-02-29', 1961, '0.00', '22061'),
(364, '', 'Bateria 4V 2.5 AH 20 hrs para bascula torrey', NULL, 69, 390, 1, '2024-07-09 13:57:21', '2024-07-09 13:57:21', 7, '0.00', '2024-02-29', 235, '0.00', '22079'),
(365, '', 'Kit de bujía chispero p/tortilladora con cables', NULL, 43, 2900, 1, '2024-07-09 13:58:44', '2024-07-09 13:58:44', 7, '0.00', '2024-03-06', 1465.51, '0.00', '22142'),
(366, '', 'Dispositivo Paro Emergencia Boton # part B41529/341579', NULL, 43, 450, 1, '2024-07-09 13:59:37', '2024-07-09 13:59:37', 7, '0.00', '2024-04-10', 190, '0.00', '22425'),
(367, '', 'DK6N Bloques term DIN conector 10 bds 8-20AWG 50 amp 60', NULL, 12, 500, 1, '2024-07-09 14:00:37', '2024-07-09 14:00:37', 7, '0.00', '2024-04-10', 424.6, '0.00', '22425'),
(368, '', 'Clavija Hubbell HBL9309 para baño maria', NULL, 14, 1350, 1, '2024-07-09 14:01:44', '2024-07-09 14:01:44', 7, '0.00', '2024-04-10', 280.82, '0.00', '22425'),
(369, '', 'Empaque carro calenton FWE mod PTS6060-4040 120V 13', NULL, 14, 380, 1, '2024-07-09 14:02:54', '2024-07-09 14:02:54', 7, '0.00', '2024-04-16', 189, '0.00', '22484'),
(370, '', 'Motor eléctrico bifásico WEG de 1/2 HP, de 1735/1730 RPM, voltaje 127/220V, flecha 5/8\"', NULL, 43, 3200, 1, '2024-07-09 14:05:32', '2024-07-09 14:05:32', 7, '0.00', '2024-02-08', 2068.1, '0.00', '22272'),
(371, '', 'Quemador para baño Maria Mod TM 173 con tapa', NULL, 82, 350, 1, '2024-07-09 14:06:19', '2024-07-09 14:06:19', 7, '0.00', '2024-05-03', 99.14, '0.00', '22608'),
(372, '', 'Termostato tipo als-sagi saginomiya als-c1050l1 rango 10a50 c camaras de refrigeracion ', NULL, 71, 1950, 1, '2024-07-09 14:07:19', '2024-07-09 14:07:19', 7, '0.00', '2024-05-23', 1099.52, '0.00', '22817'),
(373, '', 'Reloj deshielo paragon mod 8145-20b spec a-357-20 control deshielo ', NULL, 70, 2600, 1, '2024-07-09 14:14:04', '2024-07-09 14:14:04', 7, '0.00', '2024-05-23', 1872.22, '0.00', '22816'),
(374, '', 'Boton metalico steren mod au-110 normalmente abierto razantes ', NULL, 81, 115, 1, '2024-07-09 14:14:52', '2024-07-09 14:14:52', 7, '0.00', '2024-05-23', 69.18, '0.00', '22816'),
(375, '', 'Polea de motor 8066184 p/secadora maygtan', NULL, 82, 550, 1, '2024-07-09 14:15:55', '2024-07-09 14:15:55', 7, '0.00', '2024-05-31', 80, '0.00', '22858'),
(376, '', 'Banda de extactor No 44309402 para secadora Unimac', NULL, 83, 1200, 1, '2024-07-09 14:16:46', '2024-07-09 14:16:46', 7, '0.00', '2024-06-11', 677.6, '0.00', '22937'),
(377, '', 'Botonera ON/OFF de 3 polos de 250V', NULL, 43, 280, 1, '2024-07-09 14:17:47', '2024-07-09 14:17:47', 7, '0.00', '2024-06-13', 69.3, '0.00', '22973'),
(378, '', 'Termostato de control S-431-48 ROBERTSHAW', NULL, 75, 1890, 1, '2024-07-09 14:18:25', '2024-07-09 14:18:25', 7, '0.00', '2024-06-13', 1200, '0.00', '22961'),
(379, '', 'Alcohol isopropílico solución 70% 20 L', NULL, 43, 2250, 1, '2024-07-09 14:53:06', '2024-07-09 14:53:06', 11, '0.00', '2024-01-11', 1249, '0.00', '21680'),
(380, '', 'Foam Cleaner 3.75 litros', NULL, 96, 320, 1, '2024-07-09 14:53:46', '2024-07-09 14:53:46', 11, '0.00', '2024-01-11', 206.9, '0.00', '21680'),
(381, '', 'Base hidráulica', NULL, 99, 320, 1, '2024-07-09 14:54:24', '2024-07-09 14:54:24', 11, '0.00', '2024-02-29', 210, '0.00', '1149'),
(382, '', 'Cortacírculos bimetálicos 1-3/8\"', NULL, 100, 135, 1, '2024-07-09 14:55:11', '2024-07-09 14:55:11', 11, '0.00', '2024-02-20', 106.03, '0.00', '1098'),
(383, '', 'Mandril 7/16\" para cortacírculos', NULL, 100, 133, 1, '2024-07-09 14:55:44', '2024-07-09 14:55:44', 11, '0.00', '2024-02-20', 106.03, '0.00', '1098'),
(384, '', 'Fester Grout NM800', NULL, 105, 1430, 1, '2024-07-09 14:56:27', '2024-07-09 14:56:27', 11, '0.00', '2024-02-24', 1033.6, '0.00', '1117'),
(385, '', 'Estuche con 4 dados magnéticos de impacto 3/8\"', NULL, 100, 70, 1, '2024-07-09 14:57:04', '2024-07-09 14:57:04', 11, '0.00', '2024-02-20', 30.17, '0.00', '1099'),
(386, '', 'Silicón Duretan Blanco 310 ml', NULL, 100, 185, 1, '2024-07-09 14:57:42', '2024-07-09 14:57:42', 11, '0.00', '2024-02-20', 133.62, '0.00', '1099'),
(387, '', 'Pistola calefateadora reforzada', NULL, 100, 250, 1, '2024-07-09 14:58:15', '2024-07-09 14:58:15', 11, '0.00', '2024-02-20', 100.86, '0.00', '1099'),
(388, '', 'Pija hex p. broca con accesorios 1', NULL, 100, 263, 1, '2024-07-09 14:59:10', '2024-07-09 14:59:10', 11, '0.00', '2024-02-20', 110.34, '0.00', '1099'),
(389, '', 'Pija hex p. broca con accesorios 14x4', NULL, 100, 4, 1, '2024-07-09 14:59:42', '2024-07-09 14:59:42', 11, '0.00', '2024-02-20', 2.59, '0.00', '1099'),
(390, '', 'Cable flexible 1/4\" de acero 7x19 hilos', NULL, 100, 35.4, 1, '2024-07-09 15:00:32', '2024-07-09 15:00:32', 11, '0.00', '2024-02-20', 21.55, '0.00', '1099'),
(391, '', 'Nudo para cable de 1/4\" tarjeta con 2 piezas', NULL, 100, 28, 1, '2024-07-09 15:01:20', '2024-07-09 15:01:20', 11, '0.00', '2024-02-20', 18.1, '0.00', '1099'),
(392, '', 'Llave combinada mme 13x145mm pretul', NULL, 100, 162, 1, '2024-07-09 15:02:07', '2024-07-09 15:02:07', 11, '0.00', '2024-02-20', 29.31, '0.00', '1099'),
(393, '', 'Redductor 73', NULL, 100, 475, 1, '2024-07-09 15:02:38', '2024-07-09 15:02:38', 11, '0.00', '2024-02-20', 274.54, '0.00', '1099'),
(394, '', 'Brocha dorada 4\"', NULL, 100, 100, 1, '2024-07-09 15:03:08', '2024-07-09 15:03:08', 11, '0.00', '2024-02-20', 72.41, '0.00', '1099'),
(395, '', 'Polvo natural (arena)', NULL, 99, 150, 1, '2024-07-09 15:04:42', '2024-07-09 15:04:42', 11, '0.00', '2024-02-27', 35, '0.00', '1130'),
(396, '', 'Tanque de gas Turner Map/ pro 400 gr, negro modelo CB-1000', NULL, 70, 200, 1, '2024-07-09 15:05:21', '2024-07-09 15:05:21', 11, '0.00', '2024-03-22', 141.44, '0.00', '22287'),
(397, '', 'Refrigerante Genetron R-22 Boya 13.62 KG', NULL, 70, 2800, 1, '2024-07-09 15:05:56', '2024-07-09 15:05:56', 11, '0.00', '2024-03-22', 2510.68, '0.00', '22287'),
(398, '', 'Boya de 10 kg Agente Limpiador Erka Flush-10E', NULL, 70, 3000, 1, '2024-07-09 15:06:37', '2024-07-09 15:06:37', 11, '0.00', '2024-03-22', 1416.62, '0.00', '22287'),
(399, '', 'Acumulador de succión Emerson Mod AS-585-7 Abatidor calor', NULL, 70, 3000, 1, '2024-07-09 15:07:25', '2024-07-09 15:07:25', 11, '0.00', '2024-03-22', 2171.88, '0.00', '22287'),
(400, '', 'Tanque reservorio RR-60-1713 Kraftube', NULL, 34, 3500, 1, '2024-07-09 15:08:06', '2024-07-09 15:08:06', 11, '0.00', '2024-03-22', 1480.88, '0.00', '22287'),
(401, '', 'Filtro deshidratador/secado mod EK-163 S Emerson', NULL, 34, 430, 1, '2024-07-09 15:09:02', '2024-07-09 15:09:02', 11, '0.00', '2024-03-22', 296.83, '0.00', '22287'),
(402, '', 'Compresor CR38K6-TF5-525', NULL, 34, 16500, 1, '2024-07-09 15:14:07', '2024-07-09 15:14:07', 11, '0.00', '2024-03-22', 11993.6, '0.00', '22287'),
(403, '', 'Filtro de acidez Emerson Modelo SFD-13S7W', NULL, 70, 1200, 1, '2024-07-09 15:14:48', '2024-07-09 15:14:48', 11, '0.00', '2024-03-22', 844.18, '0.00', '22287'),
(404, '', 'Perfil empaque puerta refrigerador 3 mm x 9mm 4 mts', NULL, 106, 200, 1, '2024-07-09 15:15:28', '2024-07-09 15:15:28', 11, '0.00', '2024-03-11', 10.78, '0.00', '22187'),
(405, '', 'Fester Grout NM800', NULL, 105, 1450, 1, '2024-07-09 15:16:11', '2024-07-09 15:16:11', 11, '0.00', '2024-04-16', 1033.6, '0.00', '1305'),
(406, '', 'Válvula tipo pivote 1/4\" mod TUSE-4-T', NULL, 34, 25, 1, '2024-07-09 15:18:31', '2024-07-09 15:18:31', 11, '0.00', '2024-04-11', 14.5, '0.00', '22441'),
(407, '', 'Tuerca cónica bronce para fleer 1/2\" conex aire condicionado', NULL, 70, 35, 1, '2024-07-09 15:19:04', '2024-07-09 15:19:04', 11, '0.00', '2024-04-11', 22.5, '0.00', '22441'),
(408, '', 'Separador aceite emerson SAS-3 W55877 conex 7/8\" ODS', NULL, 34, 4400, 1, '2024-07-09 15:19:45', '2024-07-09 15:19:45', 11, '0.00', '2024-04-11', 3287.19, '0.00', '22441'),
(409, '', 'Manguera de servicio de aire acondicionado N/P#VA-360134RYB R134A', NULL, 96, 650, 1, '2024-07-09 15:20:30', '2024-07-09 15:20:30', 11, '0.00', '2024-04-11', 431.034, '0.00', '22441'),
(410, '', 'Cable uso rudo 4x10', NULL, 104, 9800, 1, '2024-07-09 15:21:16', '2024-07-09 15:21:16', 11, '0.00', '2024-05-14', 8174.96, '0.00', '1409'),
(411, '', 'Interruptor termomagnético QO350', NULL, 104, 2300, 1, '2024-07-09 15:21:55', '2024-07-09 15:21:55', 11, '0.00', '2024-05-14', 1645.69, '0.00', '1409'),
(412, '', 'Centro de carga 3 cto sobreponer', NULL, 104, 1000, 1, '2024-07-09 15:22:39', '2024-07-09 15:22:39', 11, '0.00', '2024-05-14', 494.79, '0.00', '1409'),
(413, '', 'Copa diamante turbo 7¨ x 7/8\" truper', NULL, 103, 4200, 1, '2024-07-09 15:23:24', '2024-07-09 15:23:24', 11, '0.00', '2024-05-27', 878.45, '0.00', '1452'),
(414, '', 'Bomba centrifuga 1.5 HP', NULL, 98, 4900, 1, '2024-07-09 15:24:06', '2024-07-09 15:24:06', 11, '0.00', '2024-05-21', 3171.55, '0.00', '1435'),
(415, '', 'Pichancha con resor f 4045r 25 mm', NULL, 98, 400, 1, '2024-07-09 15:24:41', '2024-07-09 15:24:41', 11, '0.00', '2024-05-21', 140.95, '0.00', '1435'),
(416, '', 'Valvula esf pvc cem f-4577 19 mm', NULL, 98, 70, 1, '2024-07-09 15:25:28', '2024-07-09 15:25:28', 11, '0.00', '2024-05-21', 36.21, '0.00', '1435'),
(417, '', 'Adapt hidr cem macho 26 mm', NULL, 98, 10, 1, '2024-07-09 15:26:05', '2024-07-09 15:26:05', 11, '0.00', '2024-05-21', 4.15, '0.00', '1435'),
(418, '', ' Tubo hidr cem ced-40 19 mm tramo ', NULL, 98, 250, 1, '2024-07-09 15:28:02', '2024-07-09 15:28:02', 11, '0.00', '2024-05-21', 82.76, '0.00', '1435'),
(419, '', 'Teehldr cem 19', NULL, 98, 10, 1, '2024-07-09 15:30:43', '2024-07-09 15:30:43', 11, '0.00', '2024-05-21', 4.47, '0.00', '1435'),
(420, '', 'Tca union hldr cem 25 mv', NULL, 98, 80, 1, '2024-07-09 15:31:46', '2024-07-09 15:31:46', 11, '0.00', '2024-05-21', 30.69, '0.00', '1435'),
(421, '', 'Multiconector c/ valvula rotoplas ', NULL, 98, 350, 1, '2024-07-09 15:32:59', '2024-07-09 15:32:59', 11, '0.00', '2024-05-21', 155.35, '0.00', '1435'),
(422, '', 'Abraz s/f hs-6 1/2x7/8 ', NULL, 98, 15, 1, '2024-07-09 15:33:34', '2024-07-09 15:33:34', 11, '0.00', '2024-05-21', 5.6, '0.00', '1435'),
(423, '', 'Manguera 1´industrial (50mts)', NULL, 100, 100, 1, '2024-07-09 15:34:25', '2024-07-09 15:34:25', 11, '0.00', '2024-05-21', 53.45, '0.00', '1435'),
(424, '', 'Manguera 3/4 industrial transparente', NULL, 100, 3800, 1, '2024-07-09 15:35:15', '2024-07-09 15:35:15', 11, '0.00', '2024-05-21', 2456.9, '0.00', '1435'),
(425, '', 'Copa diamante turbo 7¨ x 7/8\" truper', NULL, 103, 4200, 1, '2024-07-09 15:36:29', '2024-07-09 15:36:29', 11, '0.00', '2024-05-28', 1019, '0.00', '1460'),
(426, '', 'Generador Hyundai 6000W con motor 13.1 HP 110V 220V', NULL, 79, 17500, 1, '2024-07-09 15:37:22', '2024-07-09 15:37:22', 11, '0.00', '2024-06-19', 12926.7, '0.00', '1577'),
(427, '', 'Alambre galvanizado calibre 12.5', NULL, 100, 150, 1, '2024-07-09 15:37:56', '2024-07-09 15:37:56', 11, '0.00', '2024-07-05', 60.34, '0.00', '1626'),
(428, '', 'Alambre galvanizado calibre 14.5', NULL, 100, 150, 1, '2024-07-09 15:38:34', '2024-07-09 15:38:34', 11, '0.00', '2024-07-05', 60.34, '0.00', '1626'),
(429, '', 'Cartuchos universales multigases para vapores orgánicos y gases ácidos MOD. 6006 3M(RQ197)', NULL, 43, 200, 1, '2024-07-09 16:06:11', '2024-07-09 16:06:11', 4, '0.00', '0000-00-00', 111.11, '0.00', '1967'),
(430, '', 'Lente dermacare mica clara MOD AL-012-CL', NULL, 201, 30, 1, '2024-07-09 16:06:59', '2024-07-09 16:06:59', 4, '0.00', '0000-00-00', 15.2, '0.00', '1967'),
(431, '', 'Lente dermacare mica gris MOD AL-012-CL', NULL, 201, 35, 1, '2024-07-09 16:07:46', '2024-07-09 16:07:46', 4, '0.00', '0000-00-00', 16.1, '0.00', '1967'),
(432, '', 'Acetona', NULL, 206, 93, 1, '2024-07-09 16:08:17', '2024-07-09 16:08:17', 4, '0.00', '0000-00-00', 52.5, '0.00', '1968'),
(433, '', 'Zapato tipo choclo van vien orazio  oraa klnud negro', NULL, 191, 700, 1, '2024-07-09 16:08:58', '2024-07-09 16:08:58', 4, '0.00', '2024-01-26', 609, '0.00', '1986'),
(434, '', 'Bota de seguridad  van vien Blot KLNUD botin negro', NULL, 191, 770, 1, '2024-07-09 16:09:40', '2024-07-09 16:09:40', 4, '0.00', '2024-01-26', 659, '0.00', '1996'),
(435, '', 'Faja con soporte sacrolumbar elastico de banda estandar con tercer cinto marca jyrsa talla ch', NULL, 185, 162, 1, '2024-07-09 16:10:25', '2024-07-09 16:10:25', 4, '0.00', '2024-02-16', 97.67, '0.00', '2123'),
(436, '', 'Guantes de bajas temperaturas talla chica marca ninja ice cauge nylon color negro con acrilico interior', NULL, 12, 360, 1, '2024-07-09 16:11:08', '2024-07-09 16:11:08', 4, '0.00', '2023-02-16', 328.27, '0.00', '2123'),
(437, '', 'Guantes de fuego resistentes a altas temperaturas para horno', NULL, 12, 349, 1, '2024-07-09 16:12:00', '2024-07-09 16:12:00', 4, '0.00', '2023-02-16', 249, '0.00', '2123'),
(438, '', 'Bota suela antiderrapante (Zapato tipo choclo (van vien Orazio KLNUD negro en tallas #22-4 pares #23-9 pares, #24-8 pares', NULL, 191, 700, 1, '2024-07-09 16:12:47', '2024-07-09 16:12:47', 4, '0.00', '2023-03-30', 609, '0.00', '2111'),
(439, '', 'Bota suela antiderrapante (Zapato tipo choclo (van vien Orazio KLNUD negro en tallas #22-4 pares #23-9 pares, #24-8 pares', NULL, 191, 700, 1, '2024-07-09 16:13:15', '2024-07-09 16:13:15', 4, '0.00', '2023-03-30', 609, '0.00', '2111'),
(440, '', 'Bota de seguridad  van vien Blot KLNUD botin negro tallas #25-9 pares #26-10 pares', NULL, 191, 759, 1, '2024-07-09 16:13:46', '2024-07-09 16:13:46', 4, '0.00', '2023-03-30', 659, '0.00', '2111'),
(441, '', 'Cinta de empaque transparente 48 mm x 100mts', NULL, 118, 31, 1, '2024-07-09 16:14:26', '2024-07-09 16:14:26', 4, '0.00', '2023-04-04', 20.56, '0.00', '2357'),
(442, '', 'VAN VIEN ORAZIO ORAA KLNUD NEGRO #27', NULL, 191, 850, 1, '2024-07-09 16:18:22', '2024-07-09 16:18:22', 4, '0.00', '2023-04-18', 669, '0.00', '2760'),
(443, '', 'VAN VIEN ORAZIO ORAA KLNUD NEGRO #28', NULL, 191, 850, 1, '2024-07-09 16:18:59', '2024-07-09 16:18:59', 4, '0.00', '2023-04-18', 669, '0.00', '2760'),
(444, '', 'VAN VIEN ORAZIO ORAA KLNUD NEGRO #30', NULL, 191, 850, 1, '2024-07-09 16:19:46', '2024-07-09 16:19:46', 4, '0.00', '2023-04-18', 669, '0.00', '2760'),
(445, '', 'Agua oxigenada 230 ml marca curapack', NULL, 18, 20, 1, '2024-07-09 16:22:21', '2024-07-09 16:22:21', 4, '0.00', '2023-05-30', 40.5, '0.00', '2649'),
(446, '', 'Venda elastica 15x5 cm marca curapack', NULL, 18, 35, 1, '2024-07-09 16:23:33', '2024-07-09 16:23:33', 4, '0.00', '2023-05-30', 21.58, '0.00', '2649'),
(447, '', 'Iodopovidona espuma antiséptico 120 ml isodine', NULL, 111, 195, 1, '2024-07-09 16:24:34', '2024-07-09 16:24:34', 4, '0.00', '2023-05-30', 31.04, '0.00', '2649'),
(448, '', 'Vaselina hipoalergénica 100gr', NULL, 18, 60, 1, '2024-07-09 16:25:10', '2024-07-09 16:25:10', 4, '0.00', '2023-05-30', 54, '0.00', '2649'),
(449, '', 'Raspinsons (neomicina, retinol, 28g vitacilina', NULL, 111, 45, 1, '2024-07-09 16:25:47', '2024-07-09 16:25:47', 4, '0.00', '2023-05-30', 29, '0.00', '2649'),
(450, '', 'Agua inyectable (500ml)', NULL, 18, 45, 1, '2024-07-09 16:31:03', '2024-07-09 16:31:03', 4, '0.00', '2023-05-30', 33.53, '0.00', '2649'),
(451, '', 'Electrolitos de sabores ', NULL, 18, 400, 1, '2024-07-09 16:31:44', '2024-07-09 16:31:44', 4, '0.00', '2023-05-30', 5.42, '0.00', '2649'),
(452, '', 'Toallas sanitarias naturella regular con alas 32 pzas', NULL, 18, 75, 1, '2024-07-09 16:32:37', '2024-07-09 16:32:37', 4, '0.00', '2023-05-30', 62.5, '0.00', '2649'),
(453, '', 'Glucometro one touch select plus flex', NULL, 43, 600, 1, '2024-07-09 16:35:13', '2024-07-09 16:35:13', 4, '0.00', '2023-05-30', 529, '0.00', '2649'),
(454, '', 'Tiras (50 tiras) reactivas glocumetro one touch ', NULL, 43, 455, 1, '2024-07-09 16:36:00', '2024-07-09 16:36:00', 4, '0.00', '2023-05-30', 450, '0.00', '2649'),
(455, '', 'Torundas de algodón (bolsa con 150)', NULL, 18, 50, 1, '2024-07-09 16:36:44', '2024-07-09 16:36:44', 4, '0.00', '2023-05-30', 34.6, '0.00', '2649'),
(456, '', 'Pechera para desbrozadora makita modelo EM4351UH', NULL, 43, 700, 1, '2024-07-09 16:39:16', '2024-07-09 16:39:16', 4, '0.00', '2023-06-19', 440.29, '0.00', '2755'),
(457, '', 'Pechera para desbrozadora honda modelo UMK435T', NULL, 12, 500, 1, '2024-07-09 16:39:49', '2024-07-09 16:39:49', 4, '0.00', '2023-06-19', 330, '0.00', '2755'),
(458, '', 'Calibrador rainbird', NULL, 12, 110, 1, '2024-07-09 16:40:35', '2024-07-09 16:40:35', 4, '0.00', '2023-06-19', 80.13, '0.00', '2755'),
(459, '', 'Isodine solucion 120 ml', NULL, 18, 195, 1, '2024-07-09 16:45:12', '2024-07-09 16:45:12', 4, '0.00', '2023-06-01', 31.04, '0.00', '2656'),
(460, '', 'Venda elastica de alta compresión de 10 cm x 5', NULL, 18, 120, 1, '2024-07-09 16:46:29', '2024-07-09 16:46:29', 4, '0.00', '2023-06-01', 28.45, '0.00', '2656'),
(461, '', 'Tela adhesiva de 1.25 cm x 5m', NULL, 18, 50, 1, '2024-07-09 16:48:04', '2024-07-09 16:48:04', 4, '0.00', '2023-06-01', 33.83, '0.00', '2656'),
(462, '', 'Tela adhesiva de 2.5 cm x 5m', NULL, 18, 78, 1, '2024-07-09 16:48:32', '2024-07-09 16:48:32', 4, '0.00', '2023-06-01', 57, '0.00', '2656'),
(463, '', 'Cinta micropore de 1.25 cm x 5m', NULL, 18, 35, 1, '2024-07-09 16:49:09', '2024-07-09 16:49:09', 4, '0.00', '2023-06-01', 29, '0.00', '2656'),
(464, '', 'electrolitos orales de diferentes sabores', NULL, 18, 8, 1, '2024-07-09 16:49:42', '2024-07-09 16:49:42', 4, '0.00', '2023-06-01', 4.75, '0.00', '2656'),
(465, '', 'Acetona', NULL, 206, 85, 1, '2024-07-09 16:50:37', '2024-07-09 16:50:37', 4, '0.00', '2023-07-19', 52.5, '0.00', '2933'),
(466, '', 'Lentes de seguridad secure fit s400 espejado antirayadura marca 3m', NULL, 201, 180, 1, '2024-07-09 16:51:31', '2024-07-09 16:51:31', 4, '0.00', '2023-08-25', 104.22, '0.00', '3135'),
(467, '', 'Careta para soldar marca infra', NULL, 43, 200, 1, '2024-07-09 16:52:14', '2024-07-09 16:52:14', 4, '0.00', '2023-08-25', 150.86, '0.00', '3135'),
(468, '', 'mica para caretas', NULL, 43, 35, 1, '2024-07-09 16:53:02', '2024-07-09 16:53:02', 4, '0.00', '2023-08-25', 15.65, '0.00', '3135'),
(469, '', 'Orejeras marca JYRSA', NULL, 43, 308, 1, '2024-07-09 16:53:43', '2024-07-09 16:53:43', 4, '0.00', '2023-08-25', 171.55, '0.00', '3135'),
(470, '', 'Guantes para soldador azules (guante forrado soldador, talla universal)', NULL, 43, 110, 1, '2024-07-09 16:54:21', '2024-07-09 16:54:21', 4, '0.00', '2023-08-25', 81.9, '0.00', '3135'),
(471, '', 'Lentes transparentes de seguridad modelo AL-012-CL) mica clara', NULL, 201, 36, 1, '2024-07-09 16:54:55', '2024-07-09 16:54:55', 4, '0.00', '2023-08-25', 22.78, '0.00', '3135'),
(472, '', 'Cono de hilo de poliester en color negro calibre 143,184,269,286,150,114', NULL, 221, 35, 1, '2024-07-10 10:16:37', '2024-07-10 10:16:37', 4, '0.00', '2023-08-04', 25, '0.00', '2999'),
(473, '', 'Cofia plisada color blanco paquete de 100', NULL, 223, 85, 1, '2024-07-10 10:18:37', '2024-07-10 10:18:37', 4, '0.00', '2023-08-30', 55.87, '0.00', '3161'),
(474, '', 'Cofia plisada color blanco paquete de 100', NULL, 223, 85, 1, '2024-07-10 10:19:16', '2024-07-10 10:19:16', 4, '0.00', '2023-09-05', 55.87, '0.00', '3187'),
(475, '', 'Botin dielectrico marca van vien talla 23,24,27', NULL, 191, 850, 1, '2024-07-10 10:20:13', '2024-07-10 10:20:13', 4, '0.00', '2023-10-12', 699, '0.00', '3426'),
(476, '', 'Choclo van vien negro talla 24,25,26,27', NULL, 191, 850, 1, '2024-07-10 10:21:03', '2024-07-10 10:21:03', 4, '0.00', '2023-10-12', 669, '0.00', '3425'),
(477, '', 'Bota industrial sanitaria premium (PVC)con casquillo de poliamida talla 26', NULL, 227, 550, 1, '2024-07-10 10:23:03', '2024-07-10 10:23:03', 4, '0.00', '2023-10-02', 358, '0.00', '3349'),
(478, '', 'Bota industrial sanitaria premium (PVC)con casquillo de poliamida talla 25,24', NULL, 227, 550, 1, '2024-07-10 10:24:02', '2024-07-10 10:24:02', 4, '0.00', '2023-10-20', 268, '0.00', '3477'),
(479, '', 'Cucharon de plástico grande 1 kg', NULL, 43, 135, 1, '2024-07-10 10:25:44', '2024-07-10 10:25:44', 4, '0.00', '2023-10-02', 85.3, '0.00', '3348'),
(480, '', 'cucharón de plástico capacidad 300 ml', NULL, 43, 120, 1, '2024-07-10 10:26:20', '2024-07-10 10:26:20', 4, '0.00', '2023-10-02', 26.82, '0.00', '3348'),
(481, '', 'Goggles de seguridad antibaho', NULL, 43, 100, 1, '2024-07-10 10:28:45', '2024-07-10 10:28:45', 4, '0.00', '2023-10-02', 37.25, '0.00', '3348'),
(482, '', 'Guantes de baja temperatura marca NINJA ICE GAUGE NYLON color negro con acrilico interior', NULL, 185, 185, 1, '2024-07-10 10:29:26', '2024-07-10 10:29:26', 4, '0.00', '2023-09-19', 98.52, '0.00', '3268'),
(483, '', 'Guantes de fuego resistentes a altas temperaturas para horno', NULL, 43, 195, 1, '2024-07-10 10:30:03', '2024-07-10 10:30:03', 4, '0.00', '2023-09-19', 137.25, '0.00', '3268'),
(484, '', 'Prefiltros 5N11', NULL, 185, 42, 1, '2024-07-10 10:30:37', '2024-07-10 10:30:37', 4, '0.00', '2023-09-19', 34.3, '0.00', '3268'),
(485, '', 'Overol desechable marca Dupont', NULL, 43, 180, 1, '2024-07-10 10:31:11', '2024-07-10 10:31:11', 4, '0.00', '2023-09-19', 131.12, '0.00', '3268'),
(486, '', 'Mandil ahulado color blanco de longitud corto sin marca', NULL, 43, 100, 1, '2024-07-10 10:31:53', '2024-07-10 10:31:53', 4, '0.00', '2023-09-19', 61.42, '0.00', '3268'),
(487, '', 'Cubremangas de 100 Kevlar color amarillo con orificio para el dedo pulgar', NULL, 185, 80, 1, '2024-07-10 10:32:37', '2024-07-10 10:32:37', 4, '0.00', '2023-09-19', 46.72, '0.00', '3268'),
(488, '', 'Kit de 100 tabletas de Dpd1 para cloro ', NULL, 231, 265, 1, '2024-07-10 10:33:35', '2024-07-10 10:33:35', 4, '0.00', '2023-10-18', 170, '0.00', '3463'),
(489, '', 'Tabla de corte verde (44x30 cm) ', NULL, 43, 350, 1, '2024-07-10 10:34:19', '2024-07-10 10:34:19', 4, '0.00', '2023-10-23', 298, '0.00', '3488'),
(490, '', 'Paño de acetato de polivinilo', NULL, 43, 245, 1, '2024-07-10 10:35:06', '2024-07-10 10:35:06', 4, '0.00', '2023-10-26', 62.79, '0.00', '3533'),
(491, '', 'Guante de palma de nitrilo marca URREA', NULL, 187, 150, 1, '2024-07-10 10:36:01', '2024-07-10 10:36:01', 4, '0.00', '2023-11-16', 108, '0.00', '3643'),
(492, '', 'Faja con soporte sacrolumbar elastico de banda estandar con tercer cinto marca jyrsa talla ch', NULL, 185, 180, 1, '2024-07-10 10:37:05', '2024-07-10 10:37:05', 4, '0.00', '2023-11-16', 135.84, '0.00', '3638'),
(493, '', 'Guantes de nailon para baja temperatura', NULL, 185, 185, 1, '2024-07-10 10:38:06', '2024-07-10 10:38:06', 4, '0.00', '2023-11-27', 113.55, '0.00', '3713'),
(494, '', 'Guante de polietieleno con palma de nitrilo arenado para carga manuales', NULL, 201, 90, 1, '2024-07-10 10:39:09', '2024-07-10 10:39:09', 4, '0.00', '2023-11-27', 73.29, '0.00', '3713'),
(495, '', 'Guante de fuego resistente a altas temperaturas', NULL, 190, 349, 1, '2024-07-10 10:40:18', '2024-07-10 10:40:18', 4, '0.00', '2023-11-27', 169.98, '0.00', '3713'),
(496, '', 'Guante para altas temperaturas alimentos', NULL, 31, 155, 1, '2024-07-10 10:41:00', '2024-07-10 10:41:00', 4, '0.00', '2023-11-27', 134.41, '0.00', '3713'),
(497, '', 'Guante Ansell Alpha tec 58-735 con forro intercept', NULL, 14, 165, 1, '2024-07-10 11:38:38', '2024-07-10 11:38:38', 4, '0.00', '2023-12-02', 58.157, '0.00', '3733'),
(498, '', 'Guante para mecánico piel sintético marca MIKEL´S  Unitalla', NULL, 229, 450, 1, '2024-07-10 11:39:24', '2024-07-10 11:39:24', 4, '0.00', '2023-12-07', 336, '0.00', '3756'),
(499, '', 'Magitel tel en rollo 140 hojas de 28x20cm', NULL, 12, 179, 1, '2024-07-10 11:40:06', '2024-07-10 11:40:06', 4, '0.00', '2023-12-27', 159, '0.00', '3926'),
(500, '', 'Rollo de velcro en color beige de 25 metros de largo 50mm', NULL, 221, 320, 1, '2024-07-10 11:40:45', '2024-07-10 11:40:45', 4, '0.00', '2023-12-27', 193.97, '0.00', '3926'),
(501, '', 'Guantes de latex sin talco con 100 piezas talla G', NULL, 43, 160, 1, '2024-07-10 11:41:21', '2024-07-10 11:41:21', 4, '0.00', '2023-12-14', 116, '0.00', '3822'),
(502, '', '1 Halcon volador, espanta pajaros', NULL, 43, 850, 1, '2024-07-10 11:42:47', '2024-07-10 11:42:47', 4, '0.00', '2023-01-02', 267.5, '0.00', '18772'),
(503, '', '1 Buho ahuyentador de pajaros', NULL, 43, 1650, 1, '2024-07-10 11:43:21', '2024-07-10 11:43:21', 4, '0.00', '2023-01-02', 689, '0.00', '18772'),
(504, '', 'cometa de halon de vela, espantapajaros', NULL, 12, 2700, 1, '2024-07-10 11:44:02', '2024-07-10 11:44:02', 4, '0.00', '2023-01-02', 1415.76, '0.00', '18772'),
(505, '', 'Electrodo p/ electrocardiografo Welsh 4042 / A Phillips', NULL, 43, 1000, 1, '2024-07-10 11:44:39', '2024-07-10 11:44:39', 4, '0.00', '2023-01-19', 169.8, '0.00', '18910'),
(506, '', 'Boton enc./apag. ABBMCB-01 400V/230V/120V verde-rojo (con bloque de contacto y soporte universal)', NULL, 80, 500, 1, '2024-07-10 11:45:21', '2024-07-10 11:45:21', 4, '0.00', '2023-01-18', 165.57, '0.00', '18890'),
(507, '', 'Valvula de alivio de 3/8\" Purgador automatico cromado caleffi', NULL, 43, 650, 1, '2024-07-10 11:45:56', '2024-07-10 11:45:56', 4, '0.00', '2023-01-18', 403, '0.00', '18890'),
(508, '', 'Kavo oxigenal(solucion desinfectante ,(Estericide QX)', NULL, 12, 1000, 1, '2024-07-10 11:46:31', '2024-07-10 11:46:31', 4, '0.00', '2023-02-10', 780, '0.00', '17628'),
(509, '', 'Armaflex abierto 7/8\" X 1/2\"', NULL, 23, 131, 1, '2024-07-10 11:48:07', '2024-07-10 11:48:07', 4, '0.00', '2023-02-13', 34.18, '0.00', '19130'),
(510, '', 'LYSOL spray aerosol 346 g', NULL, 18, 155, 1, '2024-07-10 11:49:18', '2024-07-10 11:49:18', 4, '0.00', '2023-02-16', 109, '0.00', '19153'),
(511, '', 'TOALLAS ALCOHOLADAS con 100 piezas', NULL, 18, 140, 1, '2024-07-10 11:50:18', '2024-07-10 11:50:18', 4, '0.00', '2023-02-16', 69.1, '0.00', '19153'),
(512, '', 'TIRAS REACTIVAS GLUCOSA  50 para performa', NULL, 43, 455, 1, '2024-07-10 11:50:57', '2024-07-10 11:50:57', 4, '0.00', '2023-02-16', 293, '0.00', '19153'),
(513, '', 'JERINGA 5 ML (21 G 20 piezas, 22G 30 piezas)', NULL, 18, 15, 1, '2024-07-10 11:51:38', '2024-07-10 11:51:38', 4, '0.00', '2023-02-16', 3.5, '0.00', '19153'),
(514, '', 'JERINGA 3 ML (21G 10 piezas, 22G 40 piezas)', NULL, 18, 10, 1, '2024-07-10 11:52:14', '2024-07-10 11:52:14', 4, '0.00', '2023-02-16', 3, '0.00', '19153'),
(515, '', 'LANCETAS 50', NULL, 18, 300, 1, '2024-07-10 11:52:48', '2024-07-10 11:52:48', 4, '0.00', '2023-02-16', 189.44, '0.00', '19153'),
(516, '', 'ABATELENGUAS ambiderm', NULL, 18, 1.5, 1, '2024-07-10 11:54:56', '2024-07-10 11:54:56', 4, '0.00', '2023-02-16', 0.533, '0.00', '19153'),
(517, '', 'ALGODÓN torunda 100 pzs', NULL, 18, 50, 1, '2024-07-10 11:55:46', '2024-07-10 11:55:46', 4, '0.00', '2023-02-16', 36, '0.00', '19153'),
(518, '', 'VENDA 7 CM', NULL, 18, 15, 1, '2024-07-10 11:56:19', '2024-07-10 11:56:19', 4, '0.00', '2023-02-16', 8, '0.00', '19153'),
(519, '', 'VENDA 10 CM', NULL, 18, 30, 1, '2024-07-10 11:56:50', '2024-07-10 11:56:50', 4, '0.00', '2023-02-16', 11.5, '0.00', '19153'),
(520, '', 'VENDA 5 CM', NULL, 18, 15, 1, '2024-07-10 11:57:25', '2024-07-10 11:57:25', 4, '0.00', '2023-02-16', 5.9, '0.00', '19153'),
(521, '', 'GASA estéril', NULL, 18, 2, 1, '2024-07-10 11:57:56', '2024-07-10 11:57:56', 4, '0.00', '2023-02-16', 0.9, '0.00', '19153'),
(522, '', 'COTONETES', NULL, 18, 1, 1, '2024-07-10 11:58:30', '2024-07-10 11:58:30', 4, '0.00', '2023-02-16', 0.32, '0.00', '19153'),
(523, '', 'VITAL OXIDE 32 ONZAS', NULL, 12, 1600, 1, '2024-07-10 11:59:10', '2024-07-10 11:59:10', 4, '0.00', '2023-02-16', 1248, '0.00', '19153'),
(524, '', 'GUANTE ESTERIL talla chica', NULL, 43, 4, 1, '2024-07-10 11:59:46', '2024-07-10 11:59:46', 4, '0.00', '2023-02-16', 2.8, '0.00', '19153'),
(525, '', 'Lumboxen gel (naproxeno con lidocaína) 35g', NULL, 18, 100, 1, '2024-07-10 12:00:23', '2024-07-10 12:00:23', 4, '0.00', '2023-02-16', 31.5, '0.00', '19153'),
(526, '', 'Lidocaína 10% solución frasco', NULL, 18, 200, 1, '2024-07-10 12:01:51', '2024-07-10 12:01:51', 4, '0.00', '2023-02-16', 126.01, '0.00', '19153'),
(527, '', 'Ibuprofeno 400 mg 10  cápsulas', NULL, 18, 30, 1, '2024-07-10 12:02:18', '2024-07-10 12:02:18', 4, '0.00', '2023-02-16', 22, '0.00', '19153'),
(528, '', 'Ácido acetil salicílico 100 mg 30 tabletas', NULL, 18, 35, 1, '2024-07-10 14:39:13', '2024-07-10 14:39:13', 4, '0.00', '2023-02-16', 19.5, '0.00', '19153'),
(529, '', 'Melox plus tabeltas maticables', NULL, 18, 100, 1, '2024-07-10 14:40:26', '2024-07-10 14:40:26', 4, '0.00', '2023-02-16', 66.52, '0.00', '19153'),
(530, '', 'Paracetamol tabletas 500 mg 20 tab.', NULL, 18, 35, 1, '2024-07-10 14:41:07', '2024-07-10 14:41:07', 4, '0.00', '2023-02-16', 14, '0.00', '19153'),
(531, '', 'Naproxeno tabletas de 500 mg 20 tab.', NULL, 18, 60, 1, '2024-07-10 14:41:58', '2024-07-10 14:41:58', 4, '0.00', '2023-02-16', 42.5, '0.00', '19153'),
(532, '', 'Ácido acetil salicílico 500 mg 20 tab.', NULL, 18, 35, 1, '2024-07-10 14:42:45', '2024-07-10 14:42:45', 4, '0.00', '2023-02-16', 14.3, '0.00', '19153'),
(533, '', 'Loperamida 2mg, 12 tabletas', NULL, 18, 50, 1, '2024-07-10 14:43:27', '2024-07-10 14:43:27', 4, '0.00', '2023-02-16', 12.84, '0.00', '19153'),
(534, '', 'Metoclopramida 10 mg 20 tabletas', NULL, 18, 30, 1, '2024-07-10 14:44:16', '2024-07-10 14:44:16', 4, '0.00', '2023-02-16', 15.01, '0.00', '19153'),
(535, '', 'Diclofenaco 100 mg 20 tabletas', NULL, 18, 55, 1, '2024-07-10 14:44:49', '2024-07-10 14:44:49', 4, '0.00', '2023-02-16', 30.49, '0.00', '19153'),
(536, '', 'Metamizol 1g/2ml S.I. 3 ampulas', NULL, 18, 50, 1, '2024-07-10 14:45:19', '2024-07-10 14:45:19', 4, '0.00', '2023-02-16', 18.5, '0.00', '19153'),
(537, '', 'Hioscina  10 tabletas', NULL, 18, 55, 1, '2024-07-10 14:45:47', '2024-07-10 14:45:47', 4, '0.00', '2023-02-16', 24, '0.00', '19153'),
(538, '', 'Sedalmerck 500 mg 10 tab.', NULL, 18, 70, 1, '2024-07-10 14:47:02', '2024-07-10 14:47:02', 4, '0.00', '2023-02-16', 28.5, '0.00', '19153'),
(539, '', 'Sensibit NF 12 tabletas', NULL, 18, 55, 1, '2024-07-10 14:47:28', '2024-07-10 14:47:28', 4, '0.00', '2023-02-16', 46.2, '0.00', '19153'),
(540, '', 'Clonixinato de lisina sol. Inyect. Ampula', NULL, 18, 220, 1, '2024-07-10 14:48:04', '2024-07-10 14:48:04', 4, '0.00', '2023-02-16', 195, '0.00', '19153'),
(541, '', 'Salbutamol spray aerosol', NULL, 18, 65, 1, '2024-07-10 14:48:49', '2024-07-10 14:48:49', 4, '0.00', '2023-02-16', 51.5, '0.00', '19153'),
(542, '', 'Loratadina 10 mg 10 tabletas', NULL, 18, 35, 1, '2024-07-10 14:49:29', '2024-07-10 14:49:29', 4, '0.00', '2023-02-16', 18.01, '0.00', '19153'),
(543, '', 'Difenidol 40mg/2ml solución inyectable', NULL, 18, 45, 1, '2024-07-10 14:50:04', '2024-07-10 14:50:04', 4, '0.00', '2023-02-16', 17.4, '0.00', '19153'),
(544, '', 'Benzonatato 100 mg 20 perlas', NULL, 18, 45, 1, '2024-07-10 14:50:37', '2024-07-10 14:50:37', 4, '0.00', '2023-02-16', 43, '0.00', '19153'),
(545, '', 'Cloranfenicol 5mg/ml solución gotas oftálmicas', NULL, 137, 45, 1, '2024-07-10 14:52:04', '2024-07-10 14:52:04', 0, '0.00', '2023-02-16', 25.68, '0.00', '19153'),
(546, '', 'Amantadina, clorfenamina, paracetamol 30 tabletas', NULL, 18, 160, 1, '2024-07-10 14:52:35', '2024-07-10 14:52:35', 4, '0.00', '2023-02-16', 37.76, '0.00', '19153'),
(547, '', 'Soldrin ótico gotas', NULL, 18, 150, 1, '2024-07-10 14:53:08', '2024-07-10 14:53:08', 4, '0.00', '2023-02-16', 116.11, '0.00', '19153'),
(548, '', 'Lisina con hioscina (TABLETAS)', NULL, 18, 150, 1, '2024-07-10 14:53:46', '2024-07-10 14:53:46', 4, '0.00', '2023-02-16', 24.01, '0.00', '19153'),
(549, '', 'Voltaren 1.16% 30g gel', NULL, 18, 100, 1, '2024-07-10 14:54:24', '2024-07-10 14:54:24', 4, '0.00', '2023-02-16', 75.89, '0.00', '19153'),
(550, '', 'Fucicort 20mg/1mg crema 15g', NULL, 18, 300, 1, '2024-07-10 14:54:57', '2024-07-10 14:54:57', 4, '0.00', '2023-02-16', 261.01, '0.00', '19153'),
(551, '', 'Vida suero oral diferentes sabores', NULL, 18, 8, 1, '2024-07-10 14:55:23', '2024-07-10 14:55:23', 4, '0.00', '2023-02-16', 4.5, '0.00', '19153'),
(552, '', 'Equipo medidor de humedad relatica marca UPLAYTECK calibrado con certificado', NULL, 43, 1200, 1, '2024-07-10 14:56:05', '2024-07-10 14:56:05', 4, '0.00', '2023-02-16', 732, '0.00', '19153'),
(553, '', 'Vaselina 60g', NULL, 27, 60, 1, '2024-07-10 14:57:09', '2024-07-10 14:57:09', 4, '0.00', '2023-02-21', 32, '0.00', '19198'),
(554, '', 'Agua inyectable  500 ml', NULL, 109, 45, 1, '2024-07-10 14:57:45', '2024-07-10 14:57:45', 4, '0.00', '2023-02-21', 33.17, '0.00', '19198'),
(555, '', 'Venda Elastica 5cm x5m', NULL, 18, 11, 1, '2024-07-10 14:58:56', '2024-07-10 14:58:56', 4, '0.00', '2023-02-21', 6.5, '0.00', '19198'),
(556, '', 'Cloruro de etilo en Aerosol (270 ml)', NULL, 43, 150, 1, '2024-07-10 15:00:21', '2024-07-10 15:00:21', 4, '0.00', '2023-02-21', 72.92, '0.00', '19198'),
(557, '', 'Venda Elastica Protec 10 x 5 m', NULL, 18, 30, 1, '2024-07-10 15:01:23', '2024-07-10 15:01:23', 4, '0.00', '2023-02-21', 11.5, '0.00', '19198'),
(558, '', 'Tiras reactivas Glocusa Accu- Chek 50', NULL, 43, 450, 1, '2024-07-10 15:02:04', '2024-07-10 15:02:04', 4, '0.00', '2023-02-21', 293, '0.00', '19198'),
(559, '', 'Lancetas marca ACCU CHECK 50', NULL, 43, 280, 1, '2024-07-10 15:02:43', '2024-07-10 15:02:43', 4, '0.00', '2023-02-21', 201, '0.00', '19198'),
(560, '', 'Baumanometro aneroide manual con estetoscopio y maletin modelo 2000', NULL, 43, 400, 1, '2024-07-10 15:03:12', '2024-07-10 15:03:12', 4, '0.00', '2023-02-21', 298, '0.00', '19198'),
(561, '', 'Oximetro digital de dedo hospitalario color azul RoHS', NULL, 43, 450, 1, '2024-07-10 15:04:18', '2024-07-10 15:04:18', 4, '0.00', '2023-02-21', 100, '0.00', '19198'),
(562, '', 'Botiquín  tamaño mediana, 50 (19.68”) LARGO x 29 (11.41”) FONDO x 22 (8.66”) ALTO cm, tela Ripstop, bolsa medica, Medilandia (Rojo)', NULL, 12, 900, 1, '2024-07-10 15:04:47', '2024-07-10 15:04:47', 4, '0.00', '2023-02-21', 710, '0.00', '19198'),
(563, '', 'Bata quirgica cirujano tipo marsupial azul plumbago reutilizable unitalla', NULL, 115, 550, 1, '2024-07-10 15:05:44', '2024-07-10 15:05:44', 4, '0.00', '2023-02-28', 186.65, '0.00', '19246'),
(564, '', 'Cinta armaflex de 2\" de ancho x 30\" de largo y 1/8\" de espesor', NULL, 23, 285, 1, '2024-07-10 15:06:43', '2024-07-10 15:06:43', 4, '0.00', '2023-03-10', 152.76, '0.00', '19354'),
(565, '', 'Luxometro Mca. EXTECH Mod. HD450', NULL, 14, 7000, 1, '2024-07-10 15:07:22', '2024-07-10 15:07:22', 4, '0.00', '2023-03-06', 531.21, '0.00', '19309'),
(566, '', 'Bata quirurgica de cirujano tipo marsupial azul plumbago unitalla reutilizable', NULL, 115, 550, 1, '2024-07-10 15:08:18', '2024-07-10 15:08:18', 4, '0.00', '2023-02-28', 186.65, '0.00', '19246'),
(567, '', 'Bata quirurgica de cirujano tipo ham azul plumbago talla mediana, reutilizable', NULL, 115, 490, 1, '2024-07-10 15:08:53', '2024-07-10 15:08:53', 4, '0.00', '2023-02-28', 186.65, '0.00', '19246'),
(568, '', 'Pelicula radiografica 10 x 12pulgadas carestream dryview', NULL, 47, 4700, 1, '2024-07-10 15:09:56', '2024-07-10 15:09:56', 4, '0.00', '2023-04-19', 3107.8, '0.00', '19613'),
(569, '', 'Mochila maletin botiquin primeros auxilios paramedico', NULL, 12, 900, 1, '2024-07-10 15:10:36', '2024-07-10 15:10:36', 4, '0.00', '2023-04-11', 710, '0.00', '19520'),
(570, '', 'Cámara fotográfica marca Kodak modelo pixpro AZ528', NULL, 12, 5150, 1, '2024-07-10 15:12:13', '2024-07-10 15:12:13', 4, '0.00', '2023-05-23', 4541.66, '0.00', '19903'),
(571, '', 'Puntas jeringa Triple desechable, diferentes colores', NULL, 120, 670, 1, '2024-07-10 16:12:44', '2024-07-10 16:12:44', 4, '0.00', '2023-06-07', 534.48, '0.00', '20025'),
(572, '', 'Cinta testigo para esterilización envapor marca Grupeysa', NULL, 122, 75, 1, '2024-07-10 16:13:18', '2024-07-10 16:13:18', 4, '0.00', '2023-06-07', 53.88, '0.00', '20025'),
(573, '', 'Fresa diamante nat balon 830 grano medio 021 #22', NULL, 120, 28, 1, '2024-07-10 16:14:04', '2024-07-10 16:14:04', 4, '0.00', '2023-06-07', 11.21, '0.00', '20025'),
(574, '', 'Fresa diamante nat balon 830 grano extremo #22', NULL, 120, 28, 1, '2024-07-10 16:14:48', '2024-07-10 16:14:48', 4, '0.00', '2023-06-07', 11.21, '0.00', '20025'),
(575, '', 'Fresa quirurgica carburo bola baja velocidad #155708', NULL, 120, 60, 1, '2024-07-10 16:15:38', '2024-07-10 16:15:38', 4, '0.00', '2023-06-07', 33.52, '0.00', '20025'),
(576, '', 'Fresa quirurgica carburo bola baja velocidad #1557010', NULL, 120, 60, 1, '2024-07-10 16:16:21', '2024-07-10 16:16:21', 4, '0.00', '2023-06-07', 33.52, '0.00', '20025'),
(577, '', 'Fresa quirurgica carburo hp baja velocidad #702', NULL, 120, 60, 1, '2024-07-10 16:17:08', '2024-07-10 16:17:08', 4, '0.00', '2023-06-07', 33.52, '0.00', '20025'),
(578, '', 'Fresa carburo great white wold cono invertido #GW35', NULL, 120, 50, 1, '2024-07-10 16:17:47', '2024-07-10 16:17:47', 4, '0.00', '2023-06-07', 29.31, '0.00', '20025'),
(579, '', 'Fresa quirurgica carburo hp baja velocidad #703', NULL, 120, 60, 1, '2024-07-10 16:18:19', '2024-07-10 16:18:19', 4, '0.00', '2023-06-07', 33.52, '0.00', '20025'),
(580, '', 'Fresa acaba-desbas ext redond #7004', NULL, 121, 30, 1, '2024-07-10 16:19:05', '2024-07-10 16:19:05', 4, '0.00', '2023-06-07', 29.31, '0.00', '20025'),
(581, '', 'Fresa acaba.-desbas ext redond #7009', NULL, 121, 145, 1, '2024-07-10 16:19:36', '2024-07-10 16:19:36', 4, '0.00', '2023-06-07', 21.25, '0.00', '20025'),
(582, '', 'Fresa acaba-desbas comp 12 cu #cft3', NULL, 120, 145, 1, '2024-07-10 16:20:22', '2024-07-10 16:20:22', 4, '0.00', '2023-06-07', 104.31, '0.00', '20025'),
(583, '', 'Fresa diamante natural forma 847 #22-FG84714', NULL, 122, 60, 1, '2024-07-10 16:20:58', '2024-07-10 16:20:58', 4, '0.00', '2023-06-07', 17.24, '0.00', '20025'),
(584, '', 'Fresa diamant natural cilindrica 835 plana g. med#22-FG83514', NULL, 120, 28, 1, '2024-07-10 16:21:30', '2024-07-10 16:21:30', 4, '0.00', '2023-06-07', 11.21, '0.00', '20025'),
(585, '', 'Fresa diamante natural forma 831 #22 FG831SG18', NULL, 122, 35, 1, '2024-07-10 16:22:00', '2024-07-10 16:22:00', 4, '0.00', '2023-06-07', 21.55, '0.00', '20025'),
(586, '', 'Lubricante pára instrumento de alta y baja velocidad de 240 ml marca Lubrimax', NULL, 120, 65, 1, '2024-07-10 16:22:34', '2024-07-10 16:22:34', 4, '0.00', '2023-06-07', 36.21, '0.00', '20025'),
(587, '', 'Tinta/ para ahumar Faros-calavera New Shine Magic Humo 140', NULL, 43, 222, 1, '2024-07-10 16:23:38', '2024-07-10 16:23:38', 4, '0.00', '2023-07-04', 158.5, '0.00', '20246'),
(588, '', 'Cafetera eléctrica Oster BVSTCP-12B-03 12 tazas', NULL, 12, 860, 1, '2024-07-10 16:24:20', '2024-07-10 16:24:20', 4, '0.00', '2023-07-04', 749.25, '0.00', '20248'),
(589, '', 'Tinta de seguridad reactiva con luz negra invisible marca opticz', NULL, 43, 1500, 1, '2024-07-10 16:24:53', '2024-07-10 16:24:53', 4, '0.00', '2023-07-04', 1208, '0.00', '20248'),
(590, '', 'Adhesivo 520 Armaflex color negro CM-1720 N', NULL, 23, 2400, 1, '2024-07-10 16:25:46', '2024-07-10 16:25:46', 4, '0.00', '2023-05-16', 540, '0.00', '19837'),
(591, '', 'Pieza de baja velocidad con contraangulo Mod. NAC-EC', NULL, 120, 2400, 1, '2024-07-10 16:26:23', '2024-07-10 16:26:23', 4, '0.00', '2023-07-18', 1655.17, '0.00', '20387'),
(592, '', 'Contactel de 48 mm de ancho color negro', NULL, 125, 30, 1, '2024-07-10 16:27:27', '2024-07-10 16:27:27', 4, '0.00', '2023-07-26', 25, '0.00', '20453'),
(593, '', 'Contactel de 99 mm de ancho color negro', NULL, 125, 50, 1, '2024-07-10 16:28:07', '2024-07-10 16:28:07', 4, '0.00', '2023-07-26', 10, '0.00', '20453'),
(594, '', 'Suministro de 20 metros de cinta de seguridad', NULL, 125, 1500, 1, '2024-07-10 16:28:37', '2024-07-10 16:28:37', 4, '0.00', '2023-08-01', 1100, '0.00', '20496'),
(595, '', 'Valvula solenoide Jefferson 20 v 1/2\"', NULL, 43, 3100, 1, '2024-07-10 16:30:14', '2024-07-10 16:30:14', 4, '0.00', '2023-08-10', 2327.59, '0.00', '20581'),
(596, '', 'Campos desechables p/braket dental diferentes colores B', NULL, 122, 73, 1, '2024-07-10 16:30:47', '2024-07-10 16:30:47', 4, '0.00', '2023-08-10', 43.1, '0.00', '20577'),
(597, '', 'Campo simple tela azul plumbago algodón de 60x40cm', NULL, 43, 85, 1, '2024-07-10 16:31:28', '2024-07-10 16:31:28', 4, '0.00', '2023-08-10', 84, '0.00', '20577'),
(598, '', 'Isodine espuma de 120 ML', NULL, 129, 195, 1, '2024-07-10 16:32:36', '2024-07-10 16:32:36', 4, '0.00', '2023-08-22', 31.04, '0.00', '20644'),
(599, '', 'caja con 50 cubrebocas azules', NULL, 43, 95, 1, '2024-07-10 16:34:41', '2024-07-10 16:34:41', 4, '0.00', '2023-08-22', 38.79, '0.00', '20644'),
(600, '', 'caja de 100 piezas de gasa esteril de 10x10 cm', NULL, 43, 284, 1, '2024-07-10 16:35:22', '2024-07-10 16:35:22', 4, '0.00', '2023-08-22', 147, '0.00', '20644'),
(601, '', 'Caja de 100 piezas de guantes de latex talla mediana', NULL, 43, 280, 1, '2024-07-10 16:35:56', '2024-07-10 16:35:56', 4, '0.00', '2023-08-22', 163.13, '0.00', '20644');
INSERT INTO `item_list` (`id`, `name`, `description`, `foto_producto`, `supplier_id`, `cost`, `status`, `date_created`, `date_updated`, `company_id`, `stock`, `date_purchase`, `product_cost`, `shipping_or_extras`, `oc`) VALUES
(602, '', 'bote de 30gr de pasta de lassar', NULL, 129, 45, 1, '2024-07-10 16:36:26', '2024-07-10 16:36:26', 4, '0.00', '2023-08-22', 15, '0.00', '20644'),
(603, '', 'tubo de vitacilina de 28gr', NULL, 129, 45, 1, '2024-07-10 16:36:59', '2024-07-10 16:36:59', 4, '0.00', '2023-08-22', 29, '0.00', '20644'),
(604, '', 'Botella portátil para lavado de ojos ', NULL, 137, 480, 1, '2024-07-10 16:38:04', '2024-07-10 16:38:04', 0, '0.00', '2023-10-02', 378.23, '0.00', '20921'),
(605, '', 'Cinta Armaflex de 2\" de ancho x 30\" de largo y 1/8\" de espesor (Insul-tape 1/8\" x2\" x 9.15mts.)', NULL, 23, 285, 1, '2024-07-10 16:38:44', '2024-07-10 16:38:44', 4, '0.00', '2023-10-04', 140.6, '0.00', '20965'),
(606, '', 'carro portacilindro p/tanque de oxígeno ROSCOE MEDICA ITEM E-CART 38-1/2\"-42-1/2\"', NULL, 43, 1300, 1, '2024-07-10 16:39:21', '2024-07-10 16:39:21', 4, '0.00', '2023-10-13', 629, '0.00', '21050'),
(607, '', 'Estetoscopio biauricular', NULL, 43, 200, 1, '2024-07-10 16:40:50', '2024-07-10 16:40:50', 4, '0.00', '2023-12-04', 107.76, '0.00', '21435'),
(608, '', 'Estuche de diagnostico médico', NULL, 43, 2600, 1, '2024-07-10 16:41:23', '2024-07-10 16:41:23', 4, '0.00', '2023-12-04', 1699, '0.00', '21435'),
(609, '', 'Vitrina para medicamentos de 38x80x156cm', NULL, 131, 4800, 1, '2024-07-10 16:41:56', '2024-07-10 16:41:56', 4, '0.00', '2023-12-04', 3000, '0.00', '21435'),
(610, '', 'Botiquin de primeros auxilios de pared de 30x7x21 cm', NULL, 43, 440, 1, '2024-07-10 16:42:34', '2024-07-10 16:42:34', 4, '0.00', '2023-12-04', 197.41, '0.00', '21435'),
(611, '', 'Tarjetas de regalo liverpool', NULL, 135, 1100, 1, '2024-07-10 16:43:13', '2024-07-10 16:43:13', 4, '0.00', '2023-12-08', 1000, '0.00', '1912'),
(612, '', 'Motor AO smith 02435356000 1/8 HP 1075 RPM, 208-230V F42 F62 A50 OEM para unidad condensadora', NULL, 14, 4600, 1, '2024-07-10 16:49:20', '2024-07-10 16:49:20', 7, '0.00', '2023-01-05', 2850, '0.00', '18805'),
(613, '', 'Desinstalar/Reinstalador Univ. De valvula de Obús , NP 91498', NULL, 12, 3700, 1, '2024-07-10 16:54:30', '2024-07-10 16:54:30', 4, '0.00', '2023-01-10', 2146.53, '0.00', '18836'),
(614, '', 'Repuesto lat p/boquilla regulable Hidrolavad Karcher k4', NULL, 12, 170, 1, '2024-07-10 16:55:04', '2024-07-10 16:55:04', 7, '0.00', '2023-01-10', 128, '0.00', '18836'),
(615, '', 'Lanza boquilla vario power Rembow p/ hidrolavad Karcher k4', NULL, 43, 590, 1, '2024-07-10 16:56:02', '2024-07-10 16:56:02', 7, '0.00', '2023-01-10', 529, '0.00', '18836'),
(616, '', 'Valvula carga lata Gas Refrigerante R404 pivote rf', NULL, 106, 70, 1, '2024-07-10 16:56:36', '2024-07-10 16:56:36', 7, '0.00', '2023-01-10', 60.34, '0.00', '18836'),
(617, '', 'Senor de SpO2- reutilizable p/adulto 2m philips M1191B', NULL, 14, 5500, 1, '2024-07-10 16:57:46', '2024-07-10 16:57:46', 7, '0.00', '2023-01-30', 1160.66, '0.00', '18963'),
(618, '', 'Conector din macho 5p bascula Torrey mod eqm', NULL, 43, 550, 1, '2024-07-10 16:58:23', '2024-07-10 16:58:23', 7, '0.00', '2023-02-04', 93.967, '0.00', '18251'),
(619, '', 'Reloj de deshielo paragon mod.8145-20B Spec A-357-20', NULL, 70, 2200, 1, '2024-07-10 16:59:02', '2024-07-10 16:59:02', 7, '0.00', '2023-01-19', 1379.31, '0.00', '18911'),
(620, '', 'Boton metálico steren mod.Au-110 normalmente abierto (RAZANTES)', NULL, 54, 110, 1, '2024-07-10 16:59:44', '2024-07-10 16:59:44', 7, '0.00', '2023-01-19', 85.35, '0.00', '18911'),
(621, '', 'Banda Poly-V, PJ 484 Procesador de alimentos sammic', NULL, 68, 1200, 1, '2024-07-10 17:00:41', '2024-07-10 17:00:41', 7, '0.00', '2023-03-06', 681.5, '0.00', '19304'),
(622, '', 'Conector DIN macho 8p B-21900124 bascula Torrey mod eqm', NULL, 69, 350, 1, '2024-07-10 17:01:18', '2024-07-10 17:01:18', 7, '0.00', '2023-03-31', 126, '0.00', '19478'),
(623, '', 'Conector DIN hembra 8 pines para bascula Torrey EQM-1000 21900127', NULL, 69, 350, 1, '2024-07-10 17:01:48', '2024-07-10 17:01:48', 7, '0.00', '2023-03-31', 107, '0.00', '19478'),
(624, '', 'Conector DIN macho 7 pines bascula Torrey EQM-1000-2000 B-46600969', NULL, 69, 350, 1, '2024-07-10 17:02:23', '2024-07-10 17:02:23', 7, '0.00', '2023-03-31', 202, '0.00', '19478'),
(625, '', 'Conector DIN hembra 7 pines para bascula Torrey EQM-1000 21900127 B-46600970', NULL, 69, 450, 1, '2024-07-10 17:02:59', '2024-07-10 17:02:59', 7, '0.00', '2023-03-31', 355, '0.00', '19478'),
(626, '', 'Bobina Danfoss  #part  333716 coil, p/condensadora', NULL, 142, 1500, 1, '2024-07-11 09:39:21', '2024-07-11 09:39:21', 7, '0.00', '2023-04-28', 592.68, '0.00', '19697'),
(627, '', 'Tarjeta maestra para báscula Torrey modelo FS-500/1000', NULL, 69, 2200, 1, '2024-07-11 09:40:03', '2024-07-11 09:40:03', 7, '0.00', '2023-04-28', 1363.7, '0.00', '19697'),
(628, '', 'Empaque Interfaz puerta superior 60379 Ropero termico cambro', NULL, 11, 3500, 1, '2024-07-11 09:57:03', '2024-07-11 09:57:03', 7, '0.00', '2023-04-18', 726.69, '0.00', '19578'),
(629, '', 'Kit de encendido piloto intermitente honeywell: valvula de as mod VR8304M3558, modulo de encendido mod S8610U3009, bujía de ignición mod 392431, cable de ignición mod 394800-30, arnés de cableado mod 393044, para marmita marca intertecnica modelo MGV-80', NULL, 14, 9500, 1, '2024-07-11 09:58:05', '2024-07-11 09:58:05', 7, '0.00', '2023-04-19', 5344, '0.00', '19614'),
(630, '', 'Quemador para marmita MGV-80', NULL, 130, 550, 1, '2024-07-11 09:58:47', '2024-07-11 09:58:47', 7, '0.00', '2023-04-19', 226.82, '0.00', '19614'),
(631, '', 'Manguera de gas de 19 mm marca inter', NULL, 77, 1600, 1, '2024-07-11 09:59:23', '2024-07-11 09:59:23', 7, '0.00', '2023-04-19', 420.91, '0.00', '19614'),
(632, '', 'switch carling 15A 250 Vac/20A 125Vac 12V lamp 1620R', NULL, 14, 185, 1, '2024-07-11 10:00:48', '2024-07-11 10:00:48', 7, '0.00', '2023-04-19', 159.6, '0.00', '19609'),
(633, '', 'Foco piloto para cafetera de 120/110 VAC', NULL, 54, 150, 1, '2024-07-11 10:02:13', '2024-07-11 10:02:13', 7, '0.00', '2023-04-19', 116.4, '0.00', '19609'),
(634, '', 'Manguera conexiones 1/2\" gas LP amarilla Sarteneta madipsa antiflama 1mt largo', NULL, 77, 900, 1, '2024-07-11 10:03:07', '2024-07-11 10:03:07', 7, '0.00', '2023-04-26', 86.94, '0.00', '19689'),
(635, '', 'Rueda fija poliolepino negro 3\" colson p/carro espiguero', NULL, 145, 205, 1, '2024-07-11 10:04:16', '2024-07-11 10:04:16', 7, '0.00', '2023-05-30', 109.84, '0.00', '19939'),
(636, '', 'Bascula Torrey de recibo marca torrey modelo FS500/1000LB', NULL, 69, 7500, 1, '2024-07-11 10:05:22', '2024-07-11 10:05:22', 7, '0.00', '2023-05-08', 7102, '0.00', '8116'),
(637, '', 'Quemador V para horno de tortilladora marca Rodotec', NULL, 154, 9400, 1, '2024-07-11 10:05:59', '2024-07-11 10:05:59', 7, '0.00', '2023-10-18', 7720.91, '0.00', '18064'),
(638, '', 'Malla de acero al carbon para tortilladora Rodotec RT 100 de 14\" de ancho, rollo de 10m', NULL, 154, 3800, 1, '2024-07-11 10:06:35', '2024-07-11 10:06:35', 7, '0.00', '2023-10-18', 251.72, '0.00', '18064'),
(639, '', 'Chumacera de piso de 1\", 2 agujeros', NULL, 154, 550, 1, '2024-07-11 10:07:12', '2024-07-11 10:07:12', 7, '0.00', '2023-10-18', 112.068, '0.00', '18064'),
(640, '', 'Malla balanceada Delta de 18\" rollo de 10m', NULL, 154, 1950, 1, '2024-07-11 10:08:37', '2024-07-11 10:08:37', 7, '0.00', '2023-10-18', 131.034, '0.00', '18064'),
(641, '', 'Rodillo trasero/ delantero con UHMW', NULL, 154, 9700, 1, '2024-07-11 10:09:14', '2024-07-11 10:09:14', 7, '0.00', '2023-10-18', 7715.52, '0.00', '18064'),
(642, '', 'Boton pulsador rojo', NULL, 80, 305, 1, '2024-07-11 10:09:53', '2024-07-11 10:09:53', 7, '0.00', '2022-10-18', 101.25, '0.00', '18064'),
(643, '', 'Boton pulsador verde  ', NULL, 80, 305, 1, '2024-07-11 10:10:25', '2024-07-11 10:10:25', 7, '0.00', '2022-10-18', 101.25, '0.00', '18064'),
(644, '', 'Resistencia traza un hilo 127v marco puerta camara cong', NULL, 138, 800, 1, '2024-07-11 10:11:21', '2024-07-11 10:11:21', 7, '0.00', '2023-05-04', 100, '0.00', '19734'),
(645, '', 'Polea receptora 60 HZ #parte 2059360 Sammic', NULL, 68, 1150, 1, '2024-07-11 10:12:01', '2024-07-11 10:12:01', 7, '0.00', '2023-06-09', 915.6, '0.00', '20059'),
(646, '', 'Reten #parte 2059404 para procesador de alimentos ', NULL, 68, 190, 1, '2024-07-11 10:12:34', '2024-07-11 10:12:34', 7, '0.00', '2023-06-09', 157.5, '0.00', '20059'),
(647, '', 'Rodamientos eje #parte 2059312 procesador de alimentos sammic', NULL, 68, 1200, 1, '2024-07-11 10:13:12', '2024-07-11 10:13:12', 7, '0.00', '2023-06-09', 961.8, '0.00', '20059'),
(648, '', 'Eje no parte 2059325 para procesador de aliemntos sammic', NULL, 68, 3400, 1, '2024-07-11 10:13:45', '2024-07-11 10:13:45', 7, '0.00', '2023-06-09', 2825.6, '0.00', '20059'),
(649, '', 'Interruptor de presion KP35060-500066', NULL, 91, 1800, 1, '2024-07-11 10:14:26', '2024-07-11 10:14:26', 7, '0.00', '2023-06-12', 509.76, '0.00', '20067'),
(650, '', 'Quemador para marmita MGV-80', NULL, 130, 550, 1, '2024-07-11 10:15:06', '2024-07-11 10:15:06', 7, '0.00', '2023-06-12', 226.82, '0.00', '20067'),
(651, '', 'Cargador Adaptador de corriente ca/ccAblex mod 1282-9-500D para báscula Torrey', NULL, 43, 390, 1, '2024-07-11 10:15:40', '2024-07-11 10:15:40', 7, '0.00', '2023-07-06', 130, '0.00', '20292'),
(652, '', 'Abrelatas industrial de acero inoxidable ', NULL, 12, 1350, 1, '2024-07-11 10:16:13', '2024-07-11 10:16:13', 7, '0.00', '2023-07-06', 999.11, '0.00', '20292'),
(653, '', 'Sprocket o tensor para ajustar cadena de amasadora celo ', NULL, 43, 1750, 1, '2024-07-11 10:16:42', '2024-07-11 10:16:42', 7, '0.00', '2023-06-13', 95, '0.00', '20091'),
(654, '', 'Perilla de control 2R-40498 Baño Maria wells ModHT400AF', NULL, 14, 790, 1, '2024-07-11 10:19:16', '2024-07-11 10:19:16', 7, '0.00', '2023-07-07', 288.63, '0.00', '20303'),
(655, '', 'Interuptor bipolar rojo T 120/55 B6 1.5 MA 200/250V Para plancha Pony', NULL, 43, 160, 1, '2024-07-11 10:19:45', '2024-07-11 10:19:45', 7, '0.00', '2023-07-07', 44.61, '0.00', '20303'),
(656, '', 'Capacitor 430-516uf p/motor de lic 110-125 vac 50/60 Hz', NULL, 43, 230, 1, '2024-07-11 10:20:17', '2024-07-11 10:20:17', 7, '0.00', '2023-07-07', 179.55, '0.00', '20301'),
(657, '', 'Casquillo de acero inoxidable par dispensador despachador agua CJ3220', NULL, 148, 300, 1, '2024-07-11 10:20:53', '2024-07-11 10:20:53', 7, '0.00', '2023-06-26', 200, '0.00', '20201'),
(658, '', 'Agitador del despachador de bebidas Crathco con base par tubo CJ1735', NULL, 148, 450, 1, '2024-07-11 10:21:22', '2024-07-11 10:21:22', 7, '0.00', '2023-06-26', 300, '0.00', '20201'),
(659, '', 'Malla balaceada Delta 18x9M Tortilladora Rodotec RT 100', NULL, 92, 250, 1, '2024-07-11 10:22:10', '2024-07-11 10:22:10', 7, '0.00', '2023-07-19', 131.034, '0.00', '20397'),
(660, '', 'Tuerca con barril de presión de 1/4 p/marmitas cocinas ', NULL, 130, 39, 1, '2024-07-11 10:22:53', '2024-07-11 10:22:53', 7, '0.00', '2023-07-19', 4.33, '0.00', '20397'),
(661, '', 'Calibracion de 1 juego de pesas en acero inoxidable de 1 g a 500 g (12 piezas), en clase 1M, MARCA PROVIMEX, CIENTIFICA, MODELO; PVE-12KM1, SERIE B00017 IDENTIFICADO COMO JPPCOA16', NULL, 158, 2600, 1, '2024-07-11 10:23:39', '2024-07-11 10:23:39', 7, '0.00', '2023-08-28', 1692.39, '0.00', '20695'),
(662, '', 'Bateria 6v 900 MAH Nimh B-21900451 p/bascula Torrey', NULL, 69, 450, 1, '2024-07-11 10:25:20', '2024-07-11 10:25:20', 7, '0.00', '2023-08-02', 268, '0.00', '20507'),
(663, '', 'Juego de grapas Aligator Flexco RS125 inoxidable (2 tiras de 24\") para banda transportadora', NULL, 151, 4500, 1, '2024-07-11 10:25:54', '2024-07-11 10:25:54', 7, '0.00', '2023-08-14', 3075, '0.00', '20594'),
(664, '', 'Engrapadora de 4 pulgadas ', NULL, 151, 12500, 1, '2024-07-11 10:26:40', '2024-07-11 10:26:40', 7, '0.00', '2023-08-25', 10374, '0.00', '20682'),
(665, '', 'Controlador temperatura Dixell Mod XR60CX', NULL, 150, 3000, 1, '2024-07-11 10:27:21', '2024-07-11 10:27:21', 7, '0.00', '2023-08-23', 2120.69, '0.00', '20680'),
(666, '', 'Conector DIN 8P Macho B-21900124 bascula torrey', NULL, 69, 350, 1, '2024-07-11 10:28:12', '2024-07-11 10:28:12', 7, '0.00', '2023-09-05', 126, '0.00', '20756'),
(667, '', 'Termostato baño maria #85917 mod-2T45917', NULL, 14, 3000, 1, '2024-07-11 10:28:48', '2024-07-11 10:28:48', 7, '0.00', '2023-09-05', 2346.53, '0.00', '20756'),
(668, '', 'Bujia o elctrodo ingnicion c/cable marmita volteo MGV80', NULL, 146, 400, 1, '2024-07-11 10:29:19', '2024-07-11 10:29:19', 7, '0.00', '2023-09-05', 87.15, '0.00', '20756'),
(669, '', 'Cafetera eléctrica Oster BVSTDCP-12B-03 12 TAZAS', NULL, 12, 860, 1, '2024-07-11 10:29:51', '2024-07-11 10:29:51', 7, '0.00', '2023-09-12', 779.2, '0.00', '20799'),
(670, '', 'Banda motriz W10198086 P/secadora Maytag 7MMEDC300D', NULL, 160, 600, 1, '2024-07-11 10:38:03', '2024-07-11 10:38:03', 7, '0.00', '2023-09-25', 250, '0.00', '20877'),
(671, '', 'Foco antiestallido #801112 25T10 Tuff-skin', NULL, 14, 1500, 1, '2024-07-11 10:38:38', '2024-07-11 10:38:38', 7, '0.00', '2023-10-10', 304.802, '0.00', '21015'),
(672, '', 'Perilla para estufa San-Son', NULL, 147, 170, 1, '2024-07-11 10:39:38', '2024-07-11 10:39:38', 7, '0.00', '2023-10-10', 90, '0.00', '21015'),
(673, '', ' Overload protector térmico 1/2 genérico', NULL, 82, 350, 1, '2024-07-11 10:40:14', '2024-07-11 10:40:14', 7, '0.00', '2023-11-03', 40, '0.00', '21213'),
(674, '', 'Motor Condensador Fan # 800439 SP B9HS 16 115 volt 60/50 hz 1550 rpm 9w', NULL, 14, 4200, 1, '2024-07-11 10:41:31', '2024-07-11 10:41:31', 7, '0.00', '2023-11-03', 1758.77, '0.00', '21213'),
(675, '', 'Cable alimentación Cord Power #801797 refrigerador True', NULL, 14, 2200, 1, '2024-07-11 10:42:08', '2024-07-11 10:42:08', 7, '0.00', '2023-11-03', 970.19, '0.00', '21213'),
(676, '', 'Compresor EMBRACO ASPERA 1/2 HP 110V R-134A NEK6212Z', NULL, 34, 7500, 1, '2024-07-11 10:42:45', '2024-07-11 10:42:45', 7, '0.00', '2023-11-03', 5008.73, '0.00', '21213'),
(677, '', 'Tarjeta control Valiantec Mod 0218 OMRON para amasadora Zuchelli- Forni', NULL, 144, 9500, 1, '2024-07-11 10:44:18', '2024-07-11 10:44:18', 7, '0.00', '2023-10-25', 9198, '0.00', '21151'),
(678, '', 'Fusible de cartucho de cuerpo de cerámica 4A 250V 5X20mm', NULL, 12, 70, 1, '2024-07-11 10:44:46', '2024-07-11 10:44:46', 7, '0.00', '2023-11-07', 43.072, '0.00', '21230'),
(679, '', 'Cafetera eléctrica Oster BVSTDCP-12B-03 12 TAZAS', NULL, 12, 860, 1, '2024-07-11 10:45:19', '2024-07-11 10:45:19', 7, '0.00', '2023-11-21', 699.3, '0.00', '21350'),
(680, '', 'Sello mecánico J.C. de 1 3/8 para bomba LP M 719', NULL, 91, 2400, 1, '2024-07-11 10:46:33', '2024-07-11 10:46:33', 7, '0.00', '2023-11-14', 280.16, '0.00', '21296'),
(681, '', 'Contactor ABB 40 AMP 3 polos 460 VCA CTR-AF40A3P460', NULL, 80, 3300, 1, '2024-07-11 10:47:25', '2024-07-11 10:47:25', 7, '0.00', '2023-11-14', 1776.9, '0.00', '21296'),
(682, '', 'Manguera de servicio de aire acondicionado N/P#VA-360134RYB R-134A', NULL, 43, 690, 1, '2024-07-11 10:48:08', '2024-07-11 10:48:08', 7, '0.00', '2023-11-14', 500, '0.00', '21296'),
(683, '', 'Termostato tipo ALS-SAGIsaginomiya ALS-C1050L1 rango -10 a 50°C', NULL, 71, 1950, 1, '2024-07-11 10:48:49', '2024-07-11 10:48:49', 7, '0.00', '2023-11-14', 1284, '0.00', '21296'),
(684, '', 'Sello mecánico 1 1/4\" marca VAZEL', NULL, 43, 270, 1, '2024-07-11 10:49:27', '2024-07-11 10:49:27', 7, '0.00', '2023-11-14', 186.79, '0.00', '21296'),
(685, '', 'Ducto flexible de 10\" para aire acondicionado', NULL, 43, 690, 1, '2024-07-11 10:50:03', '2024-07-11 10:50:03', 7, '0.00', '2023-11-14', 542.3, '0.00', '21296'),
(686, '', 'Cerradura para cuarto frio, kason k 58 latch', NULL, 70, 2100, 1, '2024-07-11 10:50:34', '2024-07-11 10:50:34', 7, '0.00', '2023-11-14', 1319, '0.00', '21296'),
(687, '', 'Pump head for Diaphragm Pump 220 PSI 230 V para hidrolavadora Coil Jet \r\nBy pass Poppets and springs for E13639-1 220V', NULL, 165, 1800, 1, '2024-07-11 10:51:17', '2024-08-28 11:50:55', 7, '0.00', '2023-11-06', 1109.7, '0.00', '21216'),
(688, '', 'Valve Housing E136391 220 ', NULL, 165, 1300, 1, '2024-07-11 10:52:26', '2024-07-11 10:52:26', 7, '0.00', '2023-11-06', 807.32, '0.00', '21216'),
(689, '', 'Celda de carga 500 kg mod 1263 código B-46600977', NULL, 69, 3980, 1, '2024-07-11 10:53:08', '2024-07-11 10:53:08', 7, '0.00', '2023-11-16', 2434.4, '0.00', '21326'),
(690, '', 'Ducto flexible aislamiento 8\" diametro para aire acondicionado', NULL, 33, 780, 1, '2024-07-11 10:53:45', '2024-07-11 10:53:45', 7, '0.00', '2023-11-21', 388, '0.00', '21351'),
(691, '', 'Festergrout', NULL, 105, 1400, 1, '2024-07-11 10:56:59', '2024-07-11 10:56:59', 7, '0.00', '2023-12-01', 1036, '0.00', '836'),
(692, '', 'Termostato ALS rango -40°C, Mca Sginomiya o Danfoss camaras de congelación', NULL, 71, 1950, 1, '2024-07-11 10:57:44', '2024-07-11 10:57:44', 7, '0.00', '2023-12-04', 1114.16, '0.00', '21429'),
(693, '', 'Sello mecanico jhon crane 3/4\" NP M00850', NULL, 166, 500, 1, '2024-07-11 10:58:24', '2024-07-11 10:58:24', 7, '0.00', '2023-12-04', 300, '0.00', '21429'),
(694, '', 'Foco piloto C-Verde 250V 2W P/marmita', NULL, 80, 380, 1, '2024-07-11 10:59:17', '2024-07-11 10:59:17', 7, '0.00', '2023-12-05', 166.57, '0.00', '21458'),
(695, '', 'Foco piloto C-Rojo 250V 2W P/marmita', NULL, 80, 380, 1, '2024-07-11 10:59:50', '2024-07-11 10:59:50', 7, '0.00', '2023-12-05', 166.57, '0.00', '21458'),
(696, '', 'Valvula perilla control flama p/estufón Mca Sanson', NULL, 147, 575, 1, '2024-07-11 11:00:25', '2024-07-11 11:00:25', 7, '0.00', '2023-12-05', 410, '0.00', '21458'),
(697, '', 'Retenedor de empaque de puerta inf. #60424, CMBH1826TSC ropero termico cambro', NULL, 11, 1100, 1, '2024-07-11 11:24:24', '2024-07-11 11:24:24', 11, '0.00', '2023-01-11', 851.36, '0.00', '18844'),
(698, '', 'Sello magnetico puerta inf. 60405 ropero term cambro CMB', NULL, 11, 1300, 1, '2024-07-11 11:25:15', '2024-07-11 11:25:15', 11, '0.00', '2023-01-11', 1011.15, '0.00', '18844'),
(699, '', 'Sello magnetico puerta sup. 60404 ropero term cambro CMB', NULL, 11, 1300, 1, '2024-07-11 11:25:55', '2024-07-11 11:25:55', 11, '0.00', '2023-01-11', 1014.69, '0.00', '18844'),
(700, '', 'Retenedor de empaque de puerta sup. #60377, CMBH1826TSC ropero termico cambro', NULL, 11, 1100, 1, '2024-07-11 11:26:25', '2024-07-11 11:26:25', 11, '0.00', '2023-01-11', 816.65, '0.00', '18844'),
(701, '', 'Piloto para marmita MGV-80 mod. Q314ALB', NULL, 14, 1200, 1, '2024-07-11 11:27:06', '2024-07-11 11:27:06', 11, '0.00', '2023-01-19', 865.37, '0.00', '18899'),
(702, '', 'Resistencia traza un hilo 127V marco puerta camara Cong', NULL, 138, 800, 1, '2024-07-11 11:27:34', '2024-07-11 11:27:34', 11, '0.00', '2023-01-19', 298.6, '0.00', '18899'),
(703, '', 'Celda de carga de 300kg mod. M263 S/Ter HI-B-46601126', NULL, 69, 3700, 1, '2024-07-11 11:28:38', '2024-07-11 11:28:38', 11, '0.00', '2023-01-23', 2864, '0.00', '18061'),
(704, '', 'Micro switch (p/banda transportadora) 8LS1 (1449)', NULL, 14, 5000, 1, '2024-07-11 11:30:12', '2024-07-11 11:30:12', 11, '0.00', '2023-01-23', 2825.48, '0.00', '18061'),
(705, '', 'Foco 125V,25W K.J. T170 K.E.I P/Horno microondas', NULL, 43, 1320, 1, '2024-07-11 11:32:09', '2024-07-11 11:32:09', 11, '0.00', '2023-01-23', 220, '0.00', '18936'),
(706, '', 'Placa electronica CA 120V/1 equipo Sammic S.L. 20720 AZ', NULL, 68, 2900, 1, '2024-07-11 11:32:45', '2024-07-11 11:32:45', 11, '0.00', '2023-01-23', 2900, '0.00', '18936'),
(707, '', 'Modulo Ignicion Marmita Volteo Mod MGV-80', NULL, 14, 14000, 1, '2024-07-11 11:33:30', '2024-07-11 11:33:30', 11, '0.00', '2023-01-23', 3500, '0.00', '18936'),
(708, '', 'Switch de límite de alta temperatura, 10H1123-4-210866-L200F-A1227', NULL, 14, 2100, 1, '2024-07-11 11:34:17', '2024-07-11 11:34:17', 11, '0.00', '2023-02-14', 523.25, '0.00', '19110'),
(709, '', 'Extractor de aire para baño silent design 300 S&P 6PLG RQ 514, EXTRACTORES', NULL, 12, 4980, 1, '2024-07-11 11:34:46', '2024-07-11 11:34:46', 11, '0.00', '2023-02-15', 4694, '0.00', '19120'),
(710, '', 'Motor 2HP, 3600 RPM, 1 Fase 115/208-230/460 Vac, 60hz, 56CH, Base tefc y brida, 5/8 in. Eje, cerrado Mca. Lesson', NULL, 173, 32500, 1, '2024-07-11 11:35:52', '2024-07-11 11:35:52', 11, '0.00', '2023-02-02', 27279.9, '0.00', '19029'),
(711, '', 'Control de velocidad del ventilador #159-100-008', NULL, 14, 12500, 1, '2024-07-11 11:36:22', '2024-07-11 11:36:22', 11, '0.00', '2023-02-22', 9076.21, '0.00', '19205'),
(712, '', 'Vávula selenoide EV220A 14B G 12E 042U4022', NULL, 142, 2600, 1, '2024-07-11 11:37:55', '2024-07-11 11:37:55', 11, '0.00', '2023-03-23', 1324.03, '0.00', '19429'),
(713, '', 'Tarjeta de control Valiantec Mod 0218 OMRON ', NULL, 144, 8300, 1, '2024-07-11 11:38:23', '2024-07-11 11:38:23', 11, '0.00', '2023-01-23', 7665, '0.00', '18939'),
(714, '', 'Protector contra acidez emerson SFD27S7VV', NULL, 34, 1300, 1, '2024-07-11 11:38:55', '2024-07-11 11:38:55', 11, '0.00', '2023-04-19', 1077.12, '0.00', '19608'),
(715, '', 'Capacitor Elavsa 15MFDS 370 c C.A 50/60 hz', NULL, 67, 180, 1, '2024-07-11 11:39:27', '2024-07-11 11:39:27', 11, '0.00', '2023-03-02', 99, '0.00', '19283'),
(716, '', 'Motor para condensadora model no: 048A11O1642, 0.80 HP Condenser Fans HVAC/R Motor 1 phase, 1075 RPM, 200-230/460V, 48Y Frame, OPAOCondenser Fans motir, marca Marathon.', NULL, 173, 8500, 1, '2024-07-11 11:40:02', '2024-07-11 11:40:02', 11, '0.00', '2023-03-02', 2059.66, '0.00', '19283'),
(717, '', 'Motor 2HP 3600 RPM, 1 FASE 115/208-230/460 Vac. 60 HZ', NULL, 173, 32500, 1, '2024-07-11 11:41:25', '2024-07-11 11:41:25', 11, '0.00', '2023-06-26', 12349.1, '0.00', '20186'),
(718, '', 'Motor 2HP 3600 RPM, 1 FASE 115/208-230/460 Vac. 60 HZ', NULL, 187, 32500, 1, '2024-07-11 11:42:08', '2024-07-11 11:42:08', 11, '0.00', '2023-06-26', 12349.1, '0.00', '20185'),
(719, '', 'Compresor Copelamd Scroll Mod ZR94KCE-TFD-265', NULL, 14, 57500, 1, '2024-07-11 11:42:51', '2024-07-11 15:07:32', 11, '0.00', '2023-07-11', 26930.1, '0.00', '20325'),
(720, '', 'Válvula expansión Sportan XVE 7 1/2 2112-14412 53VGA 14312D', NULL, 78, 2500, 1, '2024-07-11 11:43:28', '2024-07-11 11:43:28', 11, '0.00', '2023-07-11', 519.55, '0.00', '20325'),
(721, '', 'Limpiador de serpentines Foam cleaner Mca Adesa 20 Lts', NULL, 70, 830, 1, '2024-07-11 11:44:30', '2024-07-11 11:44:30', 11, '0.00', '2023-07-18', 558.48, '0.00', '20388'),
(722, '', 'Limpiador de serpentines verde fuerte mca Adesa 20 Lts', NULL, 34, 1289, 1, '2024-07-11 11:45:09', '2024-07-11 11:45:09', 11, '0.00', '2023-07-18', 930.74, '0.00', '20388'),
(723, '', 'Limpiador de serpentines Foam cleaner no-ácido Mca Ades', NULL, 34, 890, 1, '2024-07-11 11:45:45', '2024-07-11 11:45:45', 11, '0.00', '2023-07-18', 249.875, '0.00', '20388'),
(724, '', 'Limpiador de serpentines Pan Cleaner Mca Adesa 20 Lts', NULL, 34, 1250, 1, '2024-07-11 11:46:17', '2024-07-11 11:46:17', 11, '0.00', '2023-07-18', 552.63, '0.00', '20388'),
(725, '', 'sensor de temperatura NTC 10K OHM de 1/2 NTP, con reducción Bushing 1/4\" o 1/2\" NTP', NULL, 91, 400, 1, '2024-07-11 11:46:48', '2024-07-11 11:46:48', 11, '0.00', '2023-11-09', 49.06, '0.00', '21251'),
(726, '', 'Base hidráulica', NULL, 99, 290, 1, '2024-07-11 11:47:13', '2024-07-11 11:47:13', 11, '0.00', '2023-12-04', 190, '0.00', '839'),
(727, '', 'Cinta Armaflex de \"2x30FTx1/8\"', NULL, 23, 145, 1, '2024-07-15 12:06:11', '2024-07-15 12:06:11', 4, '0.00', '2024-06-21', 290, '0.00', '23035'),
(728, '', 'Guantes de alta temperatura para cocina 13\" POM alegacy', NULL, 183, 375, 1, '2024-07-22 10:48:31', '2024-07-22 10:48:31', 4, '0.00', '2024-06-17', 135, '0.00', '4967'),
(729, '', 'Pastilla de detergente Rational 150 pastillas', NULL, 237, 2700, 1, '2024-07-22 10:54:19', '2024-07-22 10:54:19', 4, '0.00', '2024-06-08', 2392, '0.00', '5098'),
(730, '', 'Tabletas de Rojo fenol para PH', NULL, 238, 320, 1, '2024-07-22 10:55:05', '2024-07-22 10:55:05', 4, '0.00', '2024-06-08', 199, '0.00', '5098'),
(731, '', 'Prefiltro de algodón 3M 5N11, N95 protección respiratoria', NULL, 14, 20, 1, '2024-07-22 10:55:54', '2024-07-22 10:55:54', 4, '0.00', '2024-06-08', 7.54, '0.00', '4968'),
(732, '', 'Mangas kevlar genuine con orificio para el pulgar', NULL, 185, 85, 1, '2024-07-22 10:56:41', '2024-07-22 10:56:41', 4, '0.00', '2024-07-08', 56.53, '0.00', '5100'),
(733, '', 'Prefiltro de algodón 3M 5N11, N95 protección respiratoria', NULL, 14, 20, 1, '2024-07-22 10:57:19', '2024-07-22 10:57:19', 4, '0.00', '2024-07-08', 7.54, '0.00', '5101'),
(734, '', 'Filtro deshidratador/secado Mod. EK-163 S EMERSON  TD-415 S', NULL, 106, 1800, 1, '2024-07-22 11:45:13', '2024-07-22 11:45:13', 7, '0.00', '2024-07-17', 1460, '0.00', '23288'),
(735, '', 'Filtro deshidratador EMERSON sellado 1/4 Soldable ', NULL, 106, 285, 1, '2024-07-22 11:45:54', '2024-07-22 11:45:54', 7, '0.00', '2024-07-17', 245, '0.00', '23288'),
(736, '', 'Separador de aceite Mod:W55824 Conexiones 1/2 ODS FILTRO SUCCION', NULL, 106, 3600, 1, '2024-07-22 11:46:36', '2024-07-22 11:46:36', 7, '0.00', '2024-07-17', 2980, '0.00', '23288'),
(737, '', 'Presostato KP35 060-50066', NULL, 91, 1800, 1, '2024-07-29 14:26:31', '2024-07-29 14:26:31', 7, '0.00', '2024-07-29', 347, '0.00', 'pendiente'),
(738, '', 'Base hidraulica\r\n\r\n', NULL, 99, 320, 1, '2024-07-30 12:06:12', '2024-07-30 12:06:12', 11, '0.00', '0000-00-00', 210, '0.00', ''),
(739, '', '20 piezas caja pvc sin tapa 4x4-3/4\" verde argos, 6 piezas chalupa de empotrar gris, 10 piezas tapa de pvc verde cuadrada-3/4\", 1 rollo cable uso rudo 4x10, 12 precinta de aislar', NULL, 104, 10500, 1, '2024-07-31 12:52:58', '2024-09-11 15:15:54', 11, '0.00', '2024-07-30', 7333.2, '0.00', '1698'),
(740, '', 'Abrelatas industrial de acero aluminio de 47 cm con navaja', NULL, 14, 1100, 1, '2024-08-07 15:06:37', '2024-08-07 15:06:37', 4, '0.00', '2024-06-20', 662.51, '0.00', '5791'),
(741, '', 'Colador doble malla de alambre estaño,malla fina y la otra guresa para reforzar el colado 27 cm', NULL, 239, 300, 1, '2024-08-07 15:08:00', '2024-08-07 15:08:00', 4, '0.00', '2024-06-20', 171.55, '0.00', '5791'),
(742, '', 'Colador doble malla de alambre estaño,malla fina y la otra guresa para reforzar el colado 14 cm', NULL, 239, 250, 1, '2024-08-07 15:08:55', '2024-08-07 15:08:55', 4, '0.00', '2024-06-20', 93.96, '0.00', '5791'),
(743, '', 'Microconmutador  SNAPACTION 12A/250VAC 239269 PonyC14111S02B', NULL, 14, 380, 1, '2024-08-07 16:19:44', '2024-08-07 16:19:44', 7, '0.00', '2024-05-31', 215, '0.00', '22858'),
(744, '', 'Cepillos cerdas de acero con maneral Adicional 10 Mca P', NULL, 240, 500, 1, '2024-08-13 16:32:54', '2025-09-30 21:03:19', 9, '0.00', '2022-12-29', 210, '0.00', '18766'),
(745, '', 'VALVULA TIPO PIVOTE 1/4\" mod TUSE -4-T', NULL, 241, 85, 1, '2024-08-13 16:33:33', '2024-08-13 16:33:33', 9, '0.00', '2022-12-29', 22, '0.00', '19883'),
(746, '', 'Empaque perfil estándar espesor 1.90mm coldtek', NULL, 242, 80, 1, '2024-08-13 16:34:05', '2024-08-13 16:34:05', 9, '0.00', '2022-05-25', 39, '0.00', '19918'),
(747, '', 'Iman perfil empaque puerta refrigerador 3mmx9mm 4mts ', NULL, 242, 300, 1, '2024-08-13 16:34:40', '2024-08-13 16:34:40', 9, '0.00', '2022-05-25', 60, '0.00', '19918'),
(748, '', 'Kit 906 de uniones y juntas HCTK-239 Marca Ite-tools', NULL, 12, 500, 1, '2024-08-13 16:35:12', '2024-08-13 16:35:12', 9, '0.00', '2022-05-25', 299, '0.00', '19918'),
(749, '', 'HCTR -239 Kit de término de cabeado marca Ite-tools', NULL, 12, 500, 1, '2024-08-13 16:35:55', '2024-08-13 16:35:55', 9, '0.00', '2022-05-25', 375, '0.00', '19918'),
(750, '', 'Control Volt Dimmer resistencia 220v 110v 25A ACMC60-25A', NULL, 43, 700, 1, '2024-08-13 16:36:32', '2024-08-13 16:36:32', 9, '0.00', '2022-05-25', 614, '0.00', '19918'),
(751, '', 'Dimmer regulador voltaje AC 2000W Imetronics', NULL, 43, 220, 1, '2024-08-13 16:37:02', '2024-08-13 16:37:02', 9, '0.00', '2022-05-25', 97, '0.00', '19918'),
(752, '', 'Terminal control acceso ZKTECO SPEEDFACE-V5L', NULL, 243, 7500, 1, '2024-08-13 16:38:24', '2024-08-13 16:38:24', 9, '0.00', '2023-06-14', 4775, '0.00', '20108'),
(753, '', 'Motor p/evaporador interlink YSLB-220-6-B004, 5020T 1/4', NULL, 70, 2500, 1, '2024-08-13 16:39:14', '2024-08-13 16:39:14', 9, '0.00', '2023-06-16', 1820, '0.00', '20128'),
(754, '', 'Manguera trenzada silicona para calentador tamaño 3mm, grosor de pared, 25mm diametro interior y 31 mm diametro exterior, 101 psi, presión de trabajo máxima de 50°C a 200°C ', NULL, 14, 29000, 1, '2024-08-13 16:40:09', '2024-08-13 16:40:09', 9, '0.00', '2023-07-03', 17028, '0.00', '20232'),
(755, '', 'Manguera puliuretano flexible tubing azul transp 1/8\" SMC', NULL, 43, 1750, 1, '2024-08-13 16:41:09', '2024-08-13 16:41:09', 9, '0.00', '2023-07-03', 995, '0.00', '20232'),
(756, '', 'cable calefactor cobre prolefin traza electrica AKO-5234', NULL, 245, 290, 1, '2024-08-13 16:41:38', '2024-08-13 16:41:38', 9, '0.00', '2023-05-09', 133, '0.00', '19776'),
(757, '', 'Cable calefactor cobre prolefin traza electrica HC-221-50 50 metros mod HC-221-50', NULL, 14, 22500, 1, '2024-08-13 16:42:19', '2024-08-13 16:42:19', 9, '0.00', '2023-05-09', 3289, '0.00', '19776'),
(758, '', 'Electroválvula ML7984A009 Voltaje 24 volts', NULL, 14, 19500, 1, '2024-08-13 16:42:58', '2024-08-13 16:42:58', 9, '0.00', '2023-07-17', 11698, '0.00', '20374'),
(759, '', 'Medidor de gas LP alta presión Diafragma Honeywell', NULL, 43, 12500, 1, '2024-08-13 16:43:32', '2024-08-13 16:43:32', 9, '0.00', '2023-07-17', 10800, '0.00', '20374'),
(760, '', 'Ducto flexible de 12\" para aire acondicionado (c/5 mts)', NULL, 34, 1200, 1, '2024-08-13 16:44:09', '2024-08-13 16:44:09', 9, '0.00', '2023-07-17', 747, '0.00', '20374'),
(761, '', 'Motor evaporador EBMPAPST MOD S1G305-DA02-07 1590R', NULL, 34, 6900, 1, '2024-08-13 16:44:46', '2024-08-13 16:44:46', 9, '0.00', '2023-07-17', 3807, '0.00', '20374'),
(762, '', 'Bloque terminales 3 polos #1942-PDM3111 Allen Bradley', NULL, 14, 1000, 1, '2024-08-13 16:45:14', '2024-08-13 16:45:14', 9, '0.00', '2023-07-17', 308, '0.00', '20374'),
(763, '', 'Desinstalador/Reinstalador Univ, de válvula de Obús ', NULL, 34, 1680, 1, '2024-08-13 16:45:42', '2024-08-13 16:45:42', 9, '0.00', '2023-07-17', 756, '0.00', '20374'),
(764, '', 'Kit master reparación Adaptador carga Mastercool #9133', NULL, 246, 2750, 1, '2024-08-13 16:46:20', '2024-08-13 16:46:20', 9, '0.00', '2023-07-17', 902, '0.00', '20374'),
(765, '', 'Sello mecánico J.C. de 1-3/8 para bomba LP M 719 aurora picsa de la ptar', NULL, 43, 2500, 1, '2024-08-13 16:46:52', '2024-08-13 16:46:52', 9, '0.00', '2023-07-17', 1725, '0.00', '20374'),
(766, '', 'Fusible americano de cristal p/tarjeta 6 amp 250 v', NULL, 54, 6, 1, '2024-08-13 16:47:37', '2024-08-13 16:47:37', 9, '0.00', '2023-08-28', 3.45, '0.00', '20705'),
(767, '', 'Bateria solex mod SB1240 12V 4AH', NULL, 247, 400, 1, '2024-08-13 16:48:10', '2024-08-13 16:48:10', 9, '0.00', '2023-08-28', 127, '0.00', '20705'),
(768, '', 'tubo alcantarillado s25 de 14\"', NULL, 249, 5319, 1, '2024-08-13 16:48:58', '2024-08-13 16:48:58', 9, '0.00', '2023-09-12', 2937, '0.00', '527'),
(769, '', 'Tubo alcantarillado s25 de 12\"', NULL, 248, 5388, 1, '2024-08-13 16:49:31', '2024-08-13 16:49:31', 9, '0.00', '2023-09-12', 2990, '0.00', '527'),
(770, '', 'Tubo alcantarillado S25 de 16\"', NULL, 248, 8877, 1, '2024-08-13 16:49:59', '2024-08-13 16:49:59', 9, '0.00', '2023-09-12', 5290, '0.00', '526'),
(771, '', 'Tubo alcantarillado S25 de 20\"', NULL, 248, 12399, 1, '2024-08-13 16:50:37', '2024-08-13 16:50:37', 9, '0.00', '2023-09-12', 7999, '0.00', '526'),
(772, '', 'Kit de termiación HotMelt ST, SC y FC, Multimodo y Mono Marca 3M P/N:6355', NULL, 250, 15000, 1, '2024-08-13 16:55:28', '2024-08-13 16:55:28', 6, '0.00', '2023-01-26', 3914, '0.00', '18955'),
(773, '', 'Horno 24 posiciones base ST-7SC/FC/SMA', NULL, 250, 6500, 1, '2024-08-13 16:56:04', '2024-08-13 16:56:04', 6, '0.00', '2023-01-26', 3450, '0.00', '18955'),
(774, '', 'Módulo de red para Switch CATALYST 3k-x 10 G Networ', NULL, 14, 5900, 1, '2024-08-13 16:56:37', '2024-08-13 16:56:37', 6, '0.00', '2023-03-30', 1626, '0.00', '19470'),
(775, '', 'Módulo transceptor fibra Óptica SFP-10G-SR Mca cisco', NULL, 43, 3500, 1, '2024-08-13 16:57:08', '2024-08-13 16:57:08', 6, '0.00', '2023-03-30', 1300, '0.00', '19470'),
(776, '', 'Auricular gris para teléfono IP Cisco serie7800', NULL, 12, 600, 1, '2024-08-13 16:57:41', '2024-08-13 16:57:41', 6, '0.00', '2023-03-30', 359, '0.00', '19470'),
(777, '', 'Adaptador teléfono Grandstream HT812 P/N;961-00050-40A001', NULL, 119, 1500, 1, '2024-08-13 16:58:11', '2024-08-13 16:58:11', 6, '0.00', '2023-03-30', 1007, '0.00', '19470'),
(778, '', 'Espiral para teléfono Cisco color gris', NULL, 14, 380, 1, '2024-08-13 16:58:41', '2024-08-13 16:58:41', 6, '0.00', '2023-03-30', 63, '0.00', '19470'),
(779, '', 'Telefono ip cp-7821 con accesorios (1 cable 9064R28001)mca cisco', NULL, 14, 4600, 1, '2024-08-13 16:59:26', '2024-08-13 16:59:26', 6, '0.00', '2023-03-30', 3626, '0.00', '8078'),
(780, '', 'telefono ip marca cisco modelo CP-7841', NULL, 14, 4000, 1, '2024-08-13 17:00:02', '2024-08-13 17:00:02', 6, '0.00', '2023-03-30', 2656, '0.00', '8078'),
(781, '', 'Telefono Ip Phone 8851 n/p; TCP-8851-KG=', NULL, 14, 7000, 1, '2024-08-13 17:00:38', '2024-08-13 17:00:38', 6, '0.00', '2023-03-30', 6089, '0.00', '8078'),
(782, '', 'Telefono Ip cisco 8865 N/P;CP-8865-KG', NULL, 14, 12500, 1, '2024-08-13 17:01:08', '2024-08-13 17:01:08', 6, '0.00', '2023-03-30', 10123, '0.00', '8078'),
(783, '', 'Brother HL-L2360DW Drum Unit', NULL, 243, 1800, 1, '2024-08-13 17:01:36', '2024-08-13 17:01:36', 6, '0.00', '2023-03-31', 1404, '0.00', '19474'),
(784, '', 'Papel vinil mate de 1.27 mts x 50 mtsw SY PV120SM', NULL, 251, 2900, 1, '2024-08-13 17:02:09', '2024-08-13 17:02:09', 6, '0.00', '2024-04-03', 1489, '0.00', '19485'),
(785, '', 'Mezcladora Behringer EUROSPORT PMP500MP3 8 canales', NULL, 12, 9000, 1, '2024-08-13 17:02:46', '2024-08-13 17:02:46', 6, '0.00', '2023-07-17', 8208, '0.00', '29371'),
(786, '', 'Deposito 5 Gls Acrilico transparente Despachador de bebidas', NULL, 94, 3300, 1, '2024-08-20 15:23:35', '2024-08-20 15:23:35', 7, '0.00', '2024-07-02', 1981, '0.00', '23079'),
(787, '', 'Empaque Interfaz Puerta Sup 60379 Ropero Term Cambro', NULL, 95, 3000, 1, '2024-08-20 15:24:09', '2024-08-20 15:24:09', 7, '0.00', '2024-07-02', 2266, '0.00', '23079'),
(788, '', 'Empaque de Interfaz 43040Cambro mod. CMBH1826L', NULL, 95, 2900, 1, '2024-08-20 15:24:40', '2024-08-20 15:24:40', 7, '0.00', '2024-07-02', 1610, '0.00', '23079'),
(789, '', 'Filtro deshidratador/secado Mod. EK-163 S EMERSON  TD-415 S', NULL, 106, 1800, 1, '2024-08-20 15:25:16', '2024-08-20 15:25:16', 7, '0.00', '2024-07-17', 1460, '0.00', '23288'),
(790, '', 'Filtro deshidratador EMERSON sellado 1/4 Soldable ', NULL, 106, 285, 1, '2024-08-20 15:25:45', '2024-08-20 15:25:45', 7, '0.00', '2024-07-17', 245, '0.00', '23288'),
(791, '', 'Separador de aceite Mod:W55824 Conexiones 1/2 ODS FILTRO SUCCION', NULL, 106, 3600, 1, '2024-08-20 15:26:20', '2024-08-20 15:26:20', 7, '0.00', '2024-07-17', 2980, '0.00', '23288'),
(792, '', 'Banda motriz W10198086 p/secadora Maytag 7MMEDC300D', NULL, 255, 600, 1, '2024-08-20 15:29:39', '2024-08-20 15:29:39', 7, '0.00', '2024-07-16', 250, '0.00', '23266'),
(793, '', 'Juego de grapas alligator flexco RS125 inox de 24\"', NULL, 151, 4500, 1, '2024-08-20 15:30:28', '2024-08-20 15:30:28', 7, '0.00', '2024-07-16', 3075, '0.00', '23266'),
(794, '', 'Termostato Bimetalico 60°C con arandela a 127 v ', NULL, 43, 250, 1, '2024-08-20 15:32:37', '2024-08-20 15:32:37', 7, '0.00', '2024-08-08', 165, '0.00', '23503'),
(795, '', 'Tapa de charola drenaje, parte 2232 mod. D-25-4 Crathco', NULL, 256, 500, 1, '2024-08-20 15:43:50', '2024-08-20 15:43:50', 7, '0.00', '2024-08-08', 337, '0.00', '23503'),
(796, '', 'Bujía o electrodo de Ignición con cable marmita volteo MGV80', NULL, 146, 400, 1, '2024-08-20 15:44:30', '2024-08-20 15:44:30', 7, '0.00', '2024-08-08', 95, '0.00', '23503'),
(797, '', 'Kit de encendido piloto intermitente Honeywell ', NULL, 14, 9200, 1, '2024-08-20 15:45:08', '2024-08-20 15:45:08', 7, '0.00', '2024-08-08', 6600, '0.00', '23503'),
(798, '', 'Presostato KP35 060-50066', NULL, 91, 1800, 1, '2024-08-20 15:45:54', '2024-08-20 15:45:54', 7, '0.00', '2024-08-15', 347, '0.00', '23562'),
(799, '', 'Resistencias electricas CS, 1100 W 230 VCA 59\"', NULL, 257, 2800, 1, '2024-08-20 15:48:01', '2024-08-20 15:48:01', 7, '0.00', '2024-08-08', 1868, '0.00', '23497'),
(800, '', 'Presostato de alta presión encapsulado de alta 350Psi-250Psi', NULL, 245, 950, 1, '2024-08-20 15:48:40', '2024-08-20 15:49:49', 7, '0.00', '2024-08-08', 286, '0.00', '23497'),
(801, '', 'Control de presión P70AA-1C Johnson Controls/ Penn, Abre al bajar la presión, Rango de 20 InHg a 100 Psig,  cierra a 60 Psig. Conector tipo tuerca flare de 1/4', NULL, 164, 2900, 1, '2024-08-20 15:49:23', '2024-08-20 15:49:23', 7, '0.00', '2024-08-08', 2143, '0.00', '23497'),
(802, '', 'Fuelle de lavadora samsung', NULL, 258, 2200, 1, '2024-08-20 16:05:08', '2024-08-20 16:05:08', 4, '0.00', '2024-07-25', 1838, '0.00', '5969'),
(803, '', 'Fuelle de lavadora LG', NULL, 259, 2100, 1, '2024-08-20 16:05:48', '2024-08-20 16:05:48', 4, '0.00', '2024-07-25', 1112, '0.00', '5969'),
(804, '', 'Aro de seguridad samsung', NULL, 12, 550, 1, '2024-08-20 16:06:21', '2024-08-20 16:06:21', 4, '0.00', '2024-07-25', 312, '0.00', '5969'),
(805, '', 'Aro de seguridad LG', NULL, 259, 690, 1, '2024-08-20 16:06:57', '2024-08-20 16:06:57', 4, '0.00', '2024-07-25', 327.59, '0.00', '5969'),
(806, '', 'Renta de tanque y recarga de tanque de nitrogeno 9 metros cubicos', NULL, 230, 3200, 1, '2024-08-20 16:17:14', '2024-08-20 16:17:14', 4, '0.00', '2024-08-16', 2151, '0.00', '23591'),
(807, '', 'Dispositivo Paro Emergencia Boton # part B41529/341579', NULL, 43, 450, 1, '2024-08-20 16:26:49', '2024-08-20 16:26:49', 7, '0.00', '2024-08-16', 150, '0.00', '23592'),
(808, '', 'Presostato Jhonson Controls P70AA-1C rango 20\" Hg', NULL, 70, 2900, 1, '2024-08-20 16:27:26', '2024-08-20 16:27:26', 7, '0.00', '2024-08-16', 1886, '0.00', '23592'),
(809, '', 'Paleta tipo gancho para batidora europan strong SM-200', NULL, 260, 2200, 1, '2024-08-23 09:32:33', '2024-08-23 09:32:33', 7, '0.00', '2024-07-12', 1300, '0.00', '23255'),
(810, '', 'Velcro color beige 50mm', NULL, 221, 12.8, 1, '2024-08-28 14:31:55', '2024-08-28 14:31:55', 4, '0.00', '2024-08-05', 7.75, '0.00', '5304'),
(811, '', 'FESTERGROUT NM 600', NULL, 105, 1460, 1, '2024-08-29 15:43:34', '2024-08-29 15:43:34', 11, '0.00', '2024-08-28', 1088, '0.00', '1792'),
(812, '', 'Fusible renovable 60 A 600 V Mercury', NULL, 262, 270, 1, '2024-09-07 11:22:36', '2024-09-07 11:22:36', 7, '0.00', '2024-08-22', 299, '0.00', '23632'),
(813, '', 'Válvula de gas Honeywell VR8304H4503 / VR8304H4230', NULL, 14, 6000, 1, '2024-09-07 11:27:16', '2024-09-07 11:27:16', 4, '0.00', '2024-04-03', 1695, '0.00', '22357'),
(814, '', 'Hojas de Fexpan de 1.22x1.22', NULL, 137, 1140, 1, '2024-09-07 11:31:15', '2024-09-07 11:31:15', 11, '0.00', '2024-09-03', 800, '0.00', '1810'),
(815, '', 'TOALLA EN ROLLO MARLI BLANCO 180 METROS', NULL, 214, 90, 1, '2024-09-09 13:20:53', '2024-09-09 13:20:53', 4, '0.00', '2024-09-09', 62.6, '0.00', '5499'),
(816, '', 'Carpa maxima de 3.5 x 4mts x 4 mts', NULL, 263, 7500, 1, '2024-09-11 10:04:42', '2024-09-11 10:04:42', 11, '0.00', '2024-09-10', 6000, '0.00', '1830'),
(817, '', 'Bota rogu', NULL, 192, 1050, 1, '2024-09-11 14:51:51', '2024-09-11 14:51:51', 4, '0.00', '2024-07-03', 564, '0.00', 'no hay'),
(818, '', 'Chaflan cepillado de 1\"', NULL, 102, 39, 1, '2024-09-11 14:59:29', '2024-09-11 14:59:29', 11, '0.00', '2024-04-16', 16, '0.00', '1304'),
(819, '', 'JARRA GRADUADA DE USO RUDO DE 2 LITROS POLICARBONATO', NULL, 12, 240, 1, '2024-09-17 13:52:18', '2024-09-17 13:55:24', 4, '0.00', '2024-09-13', 151, '0.00', '5565'),
(820, '', 'TINA DE DRENADO PARA ACEITE 16 LITROS MARCA KNOVA', NULL, 264, 350, 1, '2024-09-17 13:55:11', '2024-09-17 13:55:11', 4, '0.00', '2024-09-13', 290, '0.00', '5565'),
(821, '', 'TINA DE DRENADO PARA ACEITE 10 LITROS', NULL, 265, 350, 1, '2024-09-17 13:57:30', '2024-09-17 13:57:30', 4, '0.00', '2024-09-13', 295, '0.00', '5565'),
(822, '', 'Pumphead Diaphragm Pump 220 PSI 230 V p/hidro Coil Jet', NULL, 165, 3200, 1, '2024-09-18 15:27:37', '2024-09-18 15:27:37', 7, '0.00', '2024-09-03', 2191, '0.00', '23727'),
(824, '', 'ARMAFLEX ABIERTO DE 1 1/8\" X 1.83 MTS X 1 DE ESPESOR', NULL, 23, 270, 1, '2024-11-30 13:44:26', '2024-11-30 13:44:26', 4, '0.00', '2024-11-26', 146.23, '0.00', ''),
(825, '', 'COFIA PAQUETE 100 PZ', NULL, 224, 89, 1, '2024-11-30 13:47:12', '2024-11-30 13:47:12', 4, '0.00', '2024-11-12', 76.9, '0.00', '5900'),
(826, '', 'ARMAZON LENTE DE PRUEBA  METALICO P/LENTILLAS GRADUACION', NULL, 43, 1088, 1, '2024-11-30 13:48:40', '2025-10-11 20:08:31', 4, '0.00', '2025-10-19', 774.82, '0.00', '24319'),
(827, '', 'CINTA PARA DUCTOS DE 2 COLOR NEGRO, MARCA 3M', NULL, 23, 200, 1, '2024-11-30 13:50:13', '2024-11-30 13:50:13', 4, '0.00', '2024-10-24', 90, '0.00', '24233'),
(828, '', 'ARMAFLEX TUBULAR ABIERTO 3\"X 3/4 P /TUBERIA AGUA HELADA', NULL, 23, 270, 1, '2024-11-30 13:53:53', '2024-11-30 13:53:53', 4, '0.00', '2024-10-24', 219.3, '0.00', '24233'),
(829, '', 'ARMAFLEX TUBULAR ABIERTO 2\"X 3/4 P /TUBERIA AGUA HELADA', NULL, 23, 180, 1, '2024-11-30 13:55:50', '2024-11-30 13:55:50', 4, '0.00', '2024-10-24', 130.44, '0.00', '24233'),
(830, '', 'ARMAFLEX TUBULAR ABIERTO 1\"X 3/4 P /TUBERIA AGUA HELADA', NULL, 23, 100, 1, '2024-11-30 13:57:21', '2024-11-30 13:57:21', 4, '0.00', '2024-10-24', 65.22, '0.00', '24233'),
(831, '', 'GAS REFRIGERANTE 404-A DE 10.9KG', NULL, 82, 2600, 1, '2024-11-30 14:37:22', '2024-11-30 14:37:22', 4, '0.00', '2024-10-17', 1706.9, '0.00', '24170'),
(832, '', 'Placa salida a tubo universal, Marca Charofil', NULL, 261, 70, 1, '2024-11-30 14:42:27', '2024-11-30 14:42:27', 4, '0.00', '2024-08-23', 55.71, '0.00', '23643'),
(833, '', 'Unión acoplamiento clema med-peq tornillo tuerca Charof', NULL, 261, 27, 1, '2024-11-30 14:45:49', '2024-11-30 14:45:49', 4, '0.00', '2024-08-23', 18.11, '0.00', '23643'),
(834, '', 'Suspensión central, Marca Charofil', NULL, 261, 44, 1, '2024-11-30 14:54:48', '2024-11-30 14:54:48', 4, '0.00', '2024-08-23', 24.45, '0.00', '23643'),
(835, '', 'Grapa de suspensión, Marca Charofil', NULL, 261, 28, 1, '2024-11-30 14:56:26', '2024-11-30 14:56:26', 4, '0.00', '2024-08-23', 17.62, '0.00', '23643'),
(836, '', 'Curva prefabricada 45° 105 peralte x 200 ancho Charofil:YA NO SE COMERCIALIZA COMO TAL, SÓLO SE PUEDE ARMAR EN SITIO CON LO SIGUIENTE:\r\n- 5 CHAROLAS TIPO MALLA ELECTROSOLDADA 105 MM DE PERALTE X 200 MM DE ANCHO Y 3000 MM DE LONGITUD,\r\nMARCA CHAROFIL, MODELO PERALTE/105 MM - ANCHO/200 MM, CH-105-200EZ\r\n- 40 UNIÓN DE ACOPLAMIENTO EN TRAMOS RECTOS Y DERIVACIONES, CONSTA DE CLEMA, PEQUEÑA Y TORNILLO\r\nCON TUERCA ( KIT 1), MARCA CHAROFIL, MG-51-KIT1EZ', NULL, 261, 458, 1, '2024-11-30 14:59:52', '2024-11-30 14:59:52', 4, '0.00', '2024-08-23', 599.48, '0.00', '23643'),
(837, '', '', NULL, 261, 98, 1, '2024-11-30 15:02:57', '2024-11-30 15:02:57', 4, '0.00', '2024-08-23', 89.11, '0.00', '23643'),
(838, '', 'Abrazadera U 1 1/4 Charofil CH-ABR-U-32-CH-ABR-U-32I304', NULL, 261, 128, 1, '2024-11-30 15:06:12', '2024-11-30 15:06:12', 4, '0.00', '2024-08-23', 114.21, '0.00', '23643'),
(839, '', 'Abrazadera U de 1 Charofil CH-ABR-U-25-CH-ABR-U-25I304L', NULL, 261, 115, 1, '2024-11-30 15:09:35', '2024-11-30 15:09:35', 4, '0.00', '2024-08-23', 98.42, '0.00', '23643'),
(840, '', 'Abrazadera U 3/4 Charofil CH-ABR-U-19-CH-ABR-U-19I304L', NULL, 261, 100, 1, '2024-11-30 15:13:46', '2024-11-30 15:13:46', 4, '0.00', '2024-08-23', 81.46, '0.00', '23643'),
(841, '', 'Abrazadera U 1/2 Charofil CH-ABR-U-12 -CH-ABR-U-12I304L', NULL, 261, 90, 1, '2024-11-30 15:16:02', '2024-11-30 15:16:02', 4, '0.00', '2024-08-23', 65.29, '0.00', '23643'),
(842, '', 'Puesta a tierra, Marca Charofil', NULL, 261, 100, 1, '2024-11-30 15:17:37', '2024-11-30 15:17:37', 4, '0.00', '2024-08-23', 89.62, '0.00', '23643'),
(843, '', 'Tajeta maestra para báscula Torrey  modelo FS 500/1000', NULL, 69, 2200, 1, '2024-11-30 15:36:19', '2024-11-30 15:36:19', 4, '0.00', '2024-08-23', 1252.05, '0.00', ''),
(844, '', 'CARGADOR ADAPTADOR DE CORRIENTE CA/CC ABLEX MOD. 1282', NULL, 43, 500, 1, '2024-11-30 15:48:08', '2024-11-30 15:48:08', 7, '0.00', '2024-10-15', 474.14, '0.00', '24107'),
(845, '', 'CELDA DE CARGA DE 500 KG MOD 1263 GRADO G ENS', NULL, 69, 3300, 1, '2024-11-30 15:50:50', '2024-11-30 15:50:50', 7, '0.00', '2024-10-23', 2434.4, '0.00', '24212'),
(846, '', 'BATERIA 6V 900 MAH NIMH B-21900451 P/BASCULA TORREY ', NULL, 69, 295, 1, '2024-11-30 15:58:36', '2024-11-30 15:58:36', 7, '0.00', '2024-10-23', 227.8, '0.00', '24212'),
(847, '', 'Arnés de torreta EQM ', NULL, 69, 450, 1, '2024-12-09 12:49:02', '2024-12-09 12:49:02', 7, '0.00', '2024-12-12', 260, '0.00', ''),
(848, '', 'Tarjeta maestra para báscula Torrey EQM', NULL, 69, 2200, 1, '2024-12-09 13:31:27', '2024-12-09 13:31:27', 7, '0.00', '2024-11-12', 1252.05, '0.00', ''),
(849, '', 'Teclado para báscula Torrey', NULL, 69, 480, 1, '2024-12-09 13:33:54', '2024-12-09 13:33:54', 7, '0.00', '2024-11-12', 277.1, '0.00', ''),
(850, '', 'BATERIA PARA BASCULA TORREY DE 6VA 4 AMP ', NULL, 43, 335, 1, '2024-12-09 13:39:13', '2024-12-09 13:39:13', 7, '0.00', '2024-11-12', 120.44, '0.00', '24532'),
(851, '', 'BATERIA PARA BASCULA TORREY DE 4VA 4 AMP ', NULL, 69, 332, 1, '2024-12-09 13:40:59', '2024-12-09 13:40:59', 7, '0.00', '2024-11-12', 197.2, '0.00', '24532'),
(852, '', 'ARNES DE MODULA PARA BASCULA TORREY #parte B-46600969', NULL, 69, 300, 1, '2024-12-09 13:45:16', '2024-12-09 13:45:16', 7, '0.00', '2024-11-12', 260.1, '0.00', '24532'),
(853, '', 'ARNES DE TORRETA EQM PARA BASCULA TORREY #parte B-4660970', NULL, 69, 450, 1, '2024-12-09 13:47:36', '2024-12-09 13:47:36', 7, '0.00', '2024-11-12', 147.9, '0.00', '24532'),
(854, '', 'TARJETA MAESTRA DE BASCULA TORREY MODELO FS 500/1000 #parte B-4661243', NULL, 69, 2200, 1, '2024-12-09 13:50:58', '2024-12-09 13:50:58', 7, '0.00', '2024-11-12', 1252.05, '0.00', '24532'),
(855, '', 'CONTROL DE PRESION P70AAIC JOHNSON CONTROLS/PENN BME26', NULL, 69, 2900, 1, '2024-12-09 13:54:15', '2024-12-09 13:54:15', 7, '0.00', '2024-11-12', 286, '0.00', '23497'),
(856, '', 'PRESOSTATO ALTA PRESION HS200W450250 WILSPEC BME26OBA', NULL, 257, 1200, 1, '2024-12-09 14:59:37', '2024-12-09 14:59:37', 7, '0.00', '2024-10-29', 463.36, '0.00', '23497'),
(857, '', 'RESISTENCIA CS 1100W 230 VCA 59 2026210860 BME260BA', NULL, 70, 2800, 1, '2024-12-09 15:01:12', '2024-12-09 15:01:12', 7, '0.00', '2024-10-29', 1868.17, '0.00', '23497'),
(858, '', 'Termostato de control para sarten mod S-35-48 30-A', NULL, 75, 2800, 1, '2024-12-09 15:03:35', '2024-12-09 15:03:35', 7, '0.00', '2024-10-25', 1650, '0.00', '23939'),
(859, '', 'Perilla para estufa San-Son RQ 7299', NULL, 266, 170, 1, '2024-12-09 15:29:42', '2024-12-09 15:29:42', 0, '0.00', '2024-10-23', 90, '0.00', '23939'),
(860, '', 'Interruptor presion KP35 060-500066 rango 0.2/+7.5 bar', NULL, 91, 1800, 1, '2024-12-09 15:35:19', '2024-12-09 15:35:19', 7, '0.00', '2024-09-23', 369.67, '0.00', '23939'),
(862, '', '', NULL, 248, 1, 1, '2025-02-01 00:18:05', '2025-02-01 00:33:21', 3, '1.00', '2025-02-06', 1, '1.00', '1'),
(863, 'sku1', 'tornillos 1200\" ', NULL, 156, 12, 1, '2025-09-27 22:04:46', '2025-10-01 00:32:47', 3, '1.00', '2025-09-27', 12, '1.00', '1'),
(874, 'sku prueba', 'celular', NULL, 187, 16700, 1, '2025-10-10 16:14:00', '2025-10-12 19:38:39', 3, '1.00', '2025-10-11', 15000, '0.00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `po_items`
--

CREATE TABLE `po_items` (
  `po_id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `quantity` int(30) NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `unit` varchar(50) NOT NULL,
  `total` float NOT NULL DEFAULT 0,
  `discount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `po_items`
--

INSERT INTO `po_items` (`po_id`, `item_id`, `quantity`, `price`, `unit`, `total`, `discount`) VALUES
(36, 29, 4, 14, 'mtrs', 56, 0),
(41, 166, 8, 15, 'pza', 120, 0),
(40, 181, 4, 120, 'pza', 240, 50),
(40, 809, 6, 45, 'pza', 189, 30),
(40, 659, 1, 250, 'caja', 250, 0),
(40, 874, 2, 16700, 'pza', 33400, 0),
(40, 246, 6, 185, 'caja', 1110, 0),
(31, 23, 4, 18, 'PZA', 0, 0),
(31, 27, 0, 13, 'paz', 0, 0),
(42, 166, 2, 150, 'pza', 0, 0),
(42, 218, 1, 1500, 'pza', 0, 10),
(42, 640, 1, 1950, 'rollo', 0, 0),
(42, 659, 2, 250, 'rollo', 0, 0),
(42, 688, 1, 1300, 'pza', 0, 50),
(43, 174, 2, 5600, '', 0, 0),
(43, 860, 2, 1800, '', 0, 0),
(43, 688, 2, 1300, '', 0, 0),
(44, 174, 2, 5600, 'pieza', 0, 0),
(44, 649, 2, 1800, 'pieza', 0, 0),
(44, 688, 2, 1300, 'pieza', 0, 0),
(44, 874, 1, 16700, 'pza', 0, 10),
(49, 689, 5, 3980, 'pza', 0, 0),
(49, 874, 1, 16700, 'pza', 0, 10),
(49, 550, 1, 300, 'caja', 0, 0),
(45, 874, 1, 16700, 'pza', 0, 0),
(45, 503, 0, 1650, 'pza', 0, 0),
(50, 752, 11, 7500, '', 0, 0),
(35, 22, 2, 145, 'paz', 0, 0),
(35, 24, 7, 34, 'paz', 0, 0),
(27, 24, 2, 1100, '1', 0, 0),
(27, 874, 7, 16700, 'pza', 0, 0),
(48, 874, 3, 16700, 'pza', 0, 10),
(48, 688, 10, 1300, 'pza', 0, 0),
(51, 836, 1, 458, '', 0, 0),
(46, 196, 5, 620, 'pza', 0, 0),
(46, 659, 1, 250, 'rollo', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_order_list`
--

CREATE TABLE `purchase_order_list` (
  `id` int(30) NOT NULL,
  `po_code` varchar(50) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `amount` float DEFAULT 0,
  `discount_perc` float DEFAULT 0,
  `discount` float DEFAULT 0,
  `tax_perc` float DEFAULT 0,
  `tax` float DEFAULT 0,
  `remarks` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `oc` varchar(30) NOT NULL,
  `date_exp` date NOT NULL,
  `num_factura` int(11) NOT NULL,
  `folio_fiscal` varchar(50) NOT NULL,
  `date_carga_portal` date NOT NULL,
  `date_pago` date NOT NULL,
  `folio_comprobante_pago` varchar(50) NOT NULL,
  `pago_efectivo` date NOT NULL,
  `id_company` int(11) NOT NULL,
  `cliente_cotizacion` text NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `num_cheque` varchar(30) NOT NULL,
  `trabajo` text NOT NULL,
  `cliente_email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `purchase_order_list`
--

INSERT INTO `purchase_order_list` (`id`, `po_code`, `supplier_id`, `amount`, `discount_perc`, `discount`, `tax_perc`, `tax`, `remarks`, `status`, `date_created`, `date_updated`, `oc`, `date_exp`, `num_factura`, `folio_fiscal`, `date_carga_portal`, `date_pago`, `folio_comprobante_pago`, `pago_efectivo`, `id_company`, `cliente_cotizacion`, `metodo_pago`, `num_cheque`, `trabajo`, `cliente_email`) VALUES
(27, 'C0001', NULL, 0, 10, 1, 0, 0, '', 0, '2023-10-19 19:25:59', '2025-10-12 15:10:22', '2324324', '2023-10-19', 235253, '24552', '2023-10-19', '2023-10-19', '245425', '2023-10-19', 10, 'Ing. Antonio Álvarez', '', '', '', ''),
(31, 'C0005', NULL, 0, 0, 1, 0, 0, 'SIN OBERSIVACIONES', 0, '2024-02-29 18:46:54', '2025-10-12 14:59:47', '123456', '2024-02-29', 222222, '1111111', '2024-02-29', '2024-02-29', '111111', '2024-02-29', 4, 'KAREN AGUILAR MENDOZA', 'CONTADO', '123646', 'COMPRA DE SUMINISTROS', ''),
(35, 'C0003', NULL, 0, 0, 1, 0, 0, '', 0, '2024-05-23 03:46:20', '2025-10-13 01:59:44', '3454', '2024-05-23', 324, '324325', '2024-05-30', '2024-05-23', '23535', '2024-05-23', 6, 'MARCOS MENDOZA LARA', 'Contado', '11212', '', ''),
(36, 'C0009', 8, 56, 0, 0, 0, 0, '', 0, '2024-05-23 05:05:28', '2024-05-23 05:05:28', '34', '2024-05-31', 342, '32454', '2024-05-31', '2024-05-23', '353', '2024-05-25', 7, 'Tokio', 'Tranferencia', 'ewrwe', '', ''),
(40, 'C0004', 92, 38707.9, 0, 0, 10, 3518.9, 'obs', 0, '2025-09-30 22:35:39', '2025-10-10 18:26:37', '123', '2025-09-30', 123, '123', '2025-09-30', '2025-09-30', '123', '2025-09-30', 3, 'Moka Mendoza fernandez', 'efectivo', '123', 'prueba', 'prueba@gmail.com'),
(41, 'C0006', 156, 120, 0, 0, 0, 0, '', 0, '2025-10-10 14:11:09', '2025-10-11 23:39:16', '1', '2025-10-18', 1, '1', '2025-10-26', '2025-10-11', '1', '2025-10-18', 7, 'amaya morales', 'efectivo', '1', 'comerciante', 'amaya@gmail.com'),
(42, 'C0007', NULL, 0, 0, 1, 16, 0, 'sin observaciones', 0, '2025-10-10 14:13:19', '2025-10-12 17:32:02', '2', '2025-10-30', 2, '2', '2025-10-25', '2025-10-11', '2', '2025-11-01', 3, 'adriana carreño', 'trabnferencia', '2', 'comerciante', 'adri@gmail.com'),
(43, 'C0008', NULL, 0, 0, 1, 16, 0, 'la disponibilidad de los productos es salvo previa venta', 0, '2025-10-10 19:13:59', '2025-10-12 18:37:37', '123', '2025-10-23', 123, '123', '2025-10-18', '2025-10-25', '123', '2025-10-25', 11, 'evelyn mendoza', 'efectivo', '1', 'desarrollo', 'mendoza@gmail.com'),
(44, 'C0010', NULL, 0, 0, 1, 16, 0, 'La disponibilidad de los productos es salvo previa venta', 1, '2025-10-10 19:17:43', '2025-10-12 20:41:26', '1', '2025-10-18', 1, '1', '2025-10-24', '2025-10-17', '1', '2025-10-30', 3, 'Evelyn Mendoza', 'efectivo', '1', 'desarrollo', 'mednoza@gmail.com'),
(45, 'C0011', NULL, 0, 0, 1, 16, 0, '', 0, '2025-10-10 19:32:20', '2025-10-12 14:58:33', '1', '2025-10-19', 2, '2', '2025-10-31', '2025-10-25', '22', '2025-10-17', 8, 'prueba', 'prueba', '2', 'prueba', 'prueba'),
(46, 'C0012', NULL, 0, 0, 1, 10, 0, 'obs', 0, '2025-10-10 20:56:09', '2025-10-12 18:37:05', '1', '2025-10-17', 2, '1', '2025-10-25', '2025-10-11', '1', '2025-10-23', 3, 'Pinky Lovers', 'mordidas', '1', 'dormir', 'pinky@chillon.com'),
(48, 'COT-8CEAEB', NULL, 0, 50, 1, 16, 0, 'probando cambiar estatus', 2, '2025-10-11 23:53:26', '2025-10-12 20:42:12', '1', '2025-10-12', 1, '2', '2025-10-16', '2025-10-24', '2', '2025-10-23', 3, 'Michael Jordan', 'efectivo', '2', 'jugador', 'mednoza@gmail.com'),
(49, 'COT-DFF630', NULL, 0, 0, 1, 16, 0, '', 0, '2025-10-12 01:08:15', '2025-10-12 20:45:53', '1', '2025-10-12', 1, '1', '2025-10-24', '2025-10-30', '1', '2025-10-29', 3, 'Obama', 'efectivo', '1', 'presidente', 'obama@gmail.com'),
(50, 'COT-751CFF', NULL, 0, 0, 1, 16, 0, '', 0, '2025-10-13 01:47:54', '2025-10-13 01:47:54', '1', '2025-10-13', 1, '1', '2025-10-25', '2025-10-26', '1', '2025-10-23', 9, 'dana paola', 'a elegir por el cliente', '1', 'cantar', 'dana@gmail.com'),
(51, 'COT-00900F', NULL, 0, 0, 1, 16, 0, '', 0, '2025-10-13 02:38:42', '2025-10-13 02:38:42', '2', '2025-10-13', 2, '2', '2025-10-23', '2025-10-24', '2', '2025-10-18', 10, 'kaligaris', 'A CONVENIR CON EL CLIENTE', '2', 'kaligaris', 'kaligaris');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receiving_list`
--

CREATE TABLE `receiving_list` (
  `id` int(30) NOT NULL,
  `form_id` int(30) NOT NULL,
  `from_order` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=PO ,2 = BO',
  `amount` float NOT NULL DEFAULT 0,
  `discount_perc` float NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `tax_perc` float NOT NULL DEFAULT 0,
  `tax` float NOT NULL DEFAULT 0,
  `stock_ids` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `return_list`
--

CREATE TABLE `return_list` (
  `id` int(30) NOT NULL,
  `return_code` varchar(50) NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `stock_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales_list`
--

CREATE TABLE `sales_list` (
  `id` int(30) NOT NULL,
  `sales_code` varchar(50) NOT NULL,
  `client` text DEFAULT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `stock_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `sales_list`
--

INSERT INTO `sales_list` (`id`, `sales_code`, `client`, `amount`, `remarks`, `stock_ids`, `date_created`, `date_updated`) VALUES
(5, 'venta-01', 'Evelyn', 2200, '', '70', '2023-10-16 01:40:32', '2023-10-16 01:42:51'),
(6, 'venta-02', 'Prueba compra', 1000, NULL, '', '2023-10-16 01:44:49', '2023-10-16 01:44:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_list`
--

CREATE TABLE `stock_list` (
  `id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `quantity` int(30) NOT NULL,
  `unit` varchar(250) DEFAULT NULL,
  `price` float NOT NULL DEFAULT 0,
  `total` float NOT NULL DEFAULT current_timestamp(),
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=IN , 2=OUT',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `stock_list`
--

INSERT INTO `stock_list` (`id`, `item_id`, `quantity`, `unit`, `price`, `total`, `type`, `date_created`) VALUES
(70, 22, 1, 'Pza', 2200, 2200, 2, '2023-10-16 01:42:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supplier_list`
--

CREATE TABLE `supplier_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `address` varchar(500) DEFAULT '',
  `cperson` varchar(255) DEFAULT '',
  `contact` varchar(255) DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `supplier_list`
--

INSERT INTO `supplier_list` (`id`, `name`, `address`, `cperson`, `contact`, `status`, `date_created`, `date_updated`) VALUES
(1, 'Proveedor Genérico', '', '', '', 1, '2025-10-10 20:28:36', '2025-10-10 20:28:36'),
(5, 'GRUPO IMPER', 'Oaxaca', 'Proveedor Grupo Imper', '9513981261', 1, '2023-10-11 00:59:26', '2023-10-11 00:59:26'),
(6, 'Tony', 'Papeleria centro', 'Tony', '9511981237', 1, '2023-10-11 01:55:42', '2023-10-11 01:55:42'),
(7, 'Aerzen mexico sa de cv', 'Oaxaca', 'Aerzen contacto', '8761911432', 1, '2023-10-11 01:57:02', '2023-10-11 01:57:02'),
(8, 'Tracsa', 'Tracsa Oaxaca', 'Tracsa contacto', '9611235426', 1, '2023-10-11 02:19:17', '2023-10-11 02:19:17'),
(11, 'DEI Equipment', 'DEI Equipment direccional', 'DEI Equipment contacto', '9513981261', 1, '2023-10-15 00:43:34', '2023-10-15 00:43:34'),
(12, 'amazon', 'web', 'web', '12345667', 1, '2023-10-16 12:46:30', '2023-10-16 12:46:30'),
(13, 'Sin proveedor', '', '', '', 1, '2024-05-23 14:13:15', '2024-05-23 14:13:15'),
(14, 'Ebay', '', 'Hill country liquidators', '', 1, '2024-05-23 14:33:32', '2024-05-23 14:33:40'),
(15, 'Annuar Bazan Tarango', '', '', '', 1, '2024-05-24 15:11:19', '2024-05-24 15:11:19'),
(16, 'odonttology', '', '', '', 1, '2024-05-24 15:28:36', '2024-05-24 15:28:36'),
(17, 'Iequialsa', '', '', '', 1, '2024-05-24 15:39:08', '2024-05-24 15:39:08'),
(18, 'farmacia guadalajara', 'sucursal oaxaca', 'mostrador', '', 1, '2024-05-25 09:48:35', '2024-05-25 09:48:35'),
(19, 'mb computo', '', '', '', 1, '2024-05-25 10:50:44', '2024-05-25 10:50:44'),
(20, 'mizu técnica', '', '', '', 1, '2024-05-25 10:53:46', '2024-05-25 10:53:46'),
(21, 'Suat Mexíco ', '', '', '', 1, '2024-05-25 11:01:42', '2024-05-25 11:01:42'),
(22, 'Sur timaco', 'CARRETERA INTERNACIONAL 1809A Santa Lucia Del Camino Oaxaca', '', '9515139277', 1, '2024-05-25 11:09:07', '2024-05-25 11:09:07'),
(23, 'soluciones termicas y acustica, sa de cv', 'Nte 94 4534, Nueva Tenochtitlan, Gustavo A. Madero, 07890 Ciudad de México, CDMX', '', '55 7990 6920', 1, '2024-05-25 11:26:09', '2024-05-25 11:26:09'),
(24, 'intrade corporation s.a de c.v', ' C. Rafael de La Peña 603, Del Nte., 64500 Monterrey, N.L.', '', '81 8331 1424', 1, '2024-05-25 11:31:40', '2024-05-25 11:36:50'),
(25, 'Dikysa', 'C. Tehuacán Sur N°. 161, La Paz, 72160 Heroica Puebla de Zaragoza, Pue.', '', '222 230 5151', 1, '2024-05-25 11:44:21', '2024-05-25 11:44:21'),
(26, 'Sumeba', '. Bosque de Jacarandas 101, Portales de la Arboleda, 37100 León de los Aldama, Gto.', '', '477 251 6828', 1, '2024-05-25 11:48:11', '2024-05-25 11:48:11'),
(27, 'Farmacia del ahorro ', 'Oaxaca de juarez oax', '', '', 1, '2024-05-25 11:52:43', '2024-05-25 11:52:43'),
(28, 'Equitor', '', '', '', 1, '2024-05-25 11:58:55', '2024-05-25 11:58:55'),
(29, 'Marci hardware', '', '', '', 1, '2024-05-25 12:02:12', '2024-05-25 12:02:12'),
(30, 'Grupo parda', 'Díaz Ordaz, 97130 Mérida, Yuc.', '', '9992927443', 1, '2024-05-25 12:06:17', '2024-05-25 12:06:17'),
(31, 'Zona chef', 'av, Morelos Nte 3940, La Soledad, 58118 Morelia, Mich.', 'Venta en linea', ' 443 314 1897', 1, '2024-05-25 12:09:27', '2024-07-04 14:32:22'),
(32, 'Aisladora Térmica Acústica del Centro SA de CV', '', '', '', 1, '2024-05-25 12:14:48', '2024-05-25 12:14:48'),
(33, 'ingenieria y control térmico industrial/Aisladora Térmica Acústica del Centro SA de CV', '', '', '', 1, '2024-05-25 12:18:59', '2024-05-25 12:18:59'),
(34, 'Reacsa puebla', 'Calz Zavaleta 53-int. 1, Santiago Momoxpan, 72170 Heroica Puebla de Zaragoza, Pue.', '', '222 688 3087', 1, '2024-05-25 12:23:05', '2024-05-25 12:23:05'),
(35, 'Industrial electrico de juarez', 'Blvd. Francisco Villarreal Torres #2053\r\nCol. Partido Senecú, C.P. 32459\r\nCd. Juárez, Chihuahua, México', '', '', 1, '2024-05-25 12:27:52', '2024-05-25 12:27:52'),
(36, 'viva refacciones', '', '', '', 1, '2024-05-25 12:31:48', '2024-05-25 12:31:48'),
(37, 'Digitalife', 'Garibaldi 2014 Ladron de guevara 44600  Guadalajara Jalisco', '', '3336169511', 1, '2024-05-25 12:42:13', '2024-05-25 12:42:13'),
(38, 'Office Depot', 'Avenida Universidad 535', '', '951 506 0260', 1, '2024-05-25 12:49:45', '2024-05-25 12:49:45'),
(39, 'comercial Guvier', ' 70805 Centro, C. 5 de Mayo 101, Centro, 70800 Miahuatlán de Porfirio Díaz, Oax.', '', '951 572 0495', 1, '2024-05-25 12:52:38', '2024-05-25 12:52:38'),
(40, 'ATJ autopartes', 'Av. Insurgentes 84, San Cristóbal Centro, 55000 Ecatepec de Morelos, Méx.', '', '55 4145 3715', 1, '2024-05-25 12:56:58', '2024-05-25 12:56:58'),
(41, 'refaccionaria volcanes', 'C. Zempoaltepetl 206 C, Volcanes, 68023 Oaxaca de Juárez, Oax.', '', ' 951 235 4490', 1, '2024-05-25 13:00:33', '2024-05-25 13:00:33'),
(42, 'Coronado', '', '', '', 1, '2024-05-25 13:05:17', '2024-05-25 13:05:17'),
(43, 'Mercado libre', '', 'Empresa', '', 1, '2024-05-30 13:55:11', '2024-05-30 13:55:11'),
(44, 'Farmacia sanorim', 'Calle Marsella 83, Col Americana, Lafayette, 44160 Guadalajara, Jal.', '', '+52 1 33 1697 8192', 1, '2024-05-30 14:09:46', '2024-05-30 14:09:46'),
(45, 'Riverline', 'Av. Chapultepec 631. Col. Moderna Guadalajara Jalisco', 'Oscar Neri', '52 477 392 0726', 1, '2024-05-30 14:11:47', '2024-07-04 14:31:05'),
(46, 'Docupuebla', 'Pabellón Reforma, Av. Reforma 3518-F, Ampliación Aquiles Serdán, Aquiles Serdán, 72140 Heroica Puebla de Zaragoza, Pue.', 'Adriana', '222 231 4915', 1, '2024-05-30 14:12:33', '2024-05-30 14:12:33'),
(47, 'Carlos Nafarrete', 'Av. Las Américas #601 Col. Ladrón de Guevara 44600 Guadalajara, Jal.', 'Ventas Internet', '33 3508 5724', 1, '2024-05-30 14:14:44', '2024-05-30 14:14:44'),
(48, 'DentalMex', 'Manta #672, Col. Lindavista, Gustavo A. Madero, CDMX', 'Ventas Internet', '55-8858-9260', 1, '2024-05-30 14:16:14', '2024-05-30 14:25:34'),
(49, 'Intercompras', 'https://intercompras.com/?gad_source=1&gclid=CjwKCAjwp4m0BhBAEiwAsdc4aJnSVLum3VOm1yLwuqlZ2i5gsHisePojOCUeUDCAATqoW4d7TiRApRoCx-cQAvD_BwE', 'venta en linea', '+525584219962', 1, '2024-07-01 09:52:45', '2024-07-01 09:52:45'),
(50, 'isi cafu', '1a Fco I Madero 5, Los Reyes Culhuacan, Iztapalapa, 09840 Ciudad de México, CDMX', 'Hector adrian moya', '5572136159', 1, '2024-07-01 09:55:08', '2024-07-01 09:55:08'),
(51, 'Sabrident ', '18 RUE MOHAMED FELAH KOUBA Garidi, 16006, Argelia\r\n', 'Sandra leti', '5513412812', 1, '2024-07-01 09:57:05', '2024-07-01 09:57:05'),
(52, 'Todo dental', ' 14 de Octubre 24, Santo Domingo Barrio Bajo, 68200 Villa de Etla, Oax.', 'Venta en linea', '8115961304', 1, '2024-07-01 09:58:47', '2024-07-01 09:58:47'),
(53, 'Zeyco', 'Camino a santa ana tepetitlan 2230 zapopan Jalisco', 'Carla mungía', '3818924179', 1, '2024-07-01 10:02:48', '2024-07-01 10:02:48'),
(54, 'Steren', 'Galeana 311-B, Zona Lunes Feb 09, Centro, 68000 Oaxaca de Juárez, Oax.', 'Sucursal Oaxaca', '9515164952', 1, '2024-07-01 10:06:21', '2024-07-01 10:06:21'),
(55, 'Proveedora de hules y cañuelas', 'Calle san jose de la escalera 07630 Gustavo A Madero CDMX Mexico', 'Mercado libre', '55 5392 7652', 1, '2024-07-01 10:14:44', '2024-07-01 10:14:44'),
(56, 'Aislamientos térmicos y acusticos', 'Norte 80 no 4313 Nueva tenochtitlan Gustavo A Madero 07899 CDMX', 'Carla', '5546132700', 1, '2024-07-01 10:17:39', '2024-07-01 10:17:39'),
(57, 'Corporativo DFW', 'mercado libre', 'venta en linea', 'no aplica', 1, '2024-07-01 10:33:49', '2024-07-01 10:33:49'),
(58, 'ALCLICK', 'Mercado libre', 'Ventas Internet', 'no aplica', 1, '2024-07-01 10:38:39', '2024-07-01 10:38:39'),
(59, 'SSR sport', 'Calle Esteban Loera #210, entre Dionisio Rodriguez y Pedro Maria Anaya, al lado de tienda de plasticos.', 'Venta en linea', '33 2162 2466', 1, '2024-07-01 10:42:34', '2024-07-01 10:42:34'),
(60, 'Gadget World', 'Manuel Doblado 122A Guadalajara Jalisco', 'Venta en linea', '3326051402', 1, '2024-07-01 10:46:29', '2024-07-01 10:46:29'),
(61, 'Melissa and doug', 'amazon', 'Venta en linea', '01 800 1230478', 1, '2024-07-01 10:51:49', '2024-07-01 10:51:49'),
(62, 'MD productos especiales', 'Mariscal Rommel 240, Providencia, Azcapotzalco, 02440 Ciudad de México, CDMX', 'Venta en linea', '5568458999', 1, '2024-07-01 10:54:55', '2024-07-01 10:54:55'),
(63, 'Avila home and Garden', 'Av Nezahualcóyotl, Ext. 20, Int. 6, Cumbres de Conin, 76246 Santiago de Querétaro, Qro.', 'Venta en linea', '446 121 7438', 1, '2024-07-01 10:56:20', '2024-07-01 10:56:20'),
(64, 'Walmart', ' Carr. Internacional 2002, Agencia Municipal Sta Maria Ixcotel, 71228 Santa Lucía del Camino, Oax.', 'Venta en linea', '951 517 8736', 1, '2024-07-01 10:58:54', '2024-07-01 10:58:54'),
(65, 'Madame dubarry ', 'mercado libre', 'Venta en linea', 'no aplica', 1, '2024-07-01 11:07:50', '2024-07-01 11:07:50'),
(66, 'comprador oaxaca', 'amazon\r\n', 'Venta en linea', 'no aplica', 1, '2024-07-01 11:41:22', '2024-07-01 11:41:22'),
(67, 'Ferreteria osito', 'Calle 59 No.214 por Calle 120 y, Calle 120, Yucalpetén, 97238 Mérida, Yuc.', 'Venta en linea', '999 945 0722', 1, '2024-07-01 12:34:22', '2024-07-01 12:34:22'),
(68, 'Sammic', 'Av Adolfo Ruiz Cortines Ote 2700\r\nCol Pro Vivienda La Esperanza\r\n67110 - Guadalupe\r\nNuevo León', 'Venta en linea', '+52 81 25 25 53 60', 1, '2024-07-01 12:38:39', '2024-07-01 12:38:39'),
(69, 'SUNEGO', ' LIBRAMIENTO A IZUCAR DE MATAMOROS 105 LOC 1,2,3 SAN JUAN TEJALUCA, 74360 Atlixco, Pue.', 'Venta en linea', '244 110 8160', 1, '2024-07-01 12:41:46', '2024-07-01 12:41:46'),
(70, 'Reacsa', 'Carretera a Nogales 5777-A, Col. Bosques Vallarta, C.P.45222 Zapopan, Jalisco.', 'Venta en linea', '+52 (33) 3134 4000', 1, '2024-07-01 12:43:21', '2024-07-01 12:43:21'),
(71, 'X SOLUCIONES INTEGRADAS DE INGENIERÍA SA de CV', 'Calzada de los jinetes 121, Edificio B, Despacho 6. Col. Las Arboledas, Tlalnepantla de Baz, CP 52950., 52950 Cdad. López Mateos, Méx.', 'Venta en linea', '55 5370 9692', 1, '2024-07-01 12:45:44', '2024-07-01 12:45:44'),
(72, 'partes totales', 'Anaxagoras 504 numero exterior 3 col.Navarte CDMX', 'Venta en linea', ' 5536777461', 1, '2024-07-01 12:50:51', '2024-07-01 12:50:51'),
(73, 'Creative motoring', '7339 E Acoma Dr Ste 4, Scottsdale, AZ 85260, Estados Unidos', 'Ventas Internet', '+1 800-289-4045', 1, '2024-07-01 12:54:15', '2024-07-01 12:54:15'),
(74, 'commercialkitchendirect', 'buscar en ebay', 'Venta en linea', 'no aplica', 1, '2024-07-01 13:05:28', '2024-07-01 13:05:28'),
(75, 'Termostatos y accesorios MV', 'Guadalupe Victoria 38, Minas Palacio, 53696 Naucalpan de Juárez, Méx.', 'Venta en linea', '55 5463 0760', 1, '2024-07-01 13:08:45', '2024-07-01 13:08:45'),
(76, 'Mockwild', 'buscar en ebay', 'Venta en linea', 'no aplica', 1, '2024-07-01 13:10:28', '2024-07-01 13:10:28'),
(77, 'Casa myers', 'Calle Gamma 11376,complejo industrial chihuahua,México', 'Venta en linea', '(64) 5820013', 1, '2024-07-01 13:14:09', '2024-07-01 13:14:09'),
(78, 'Surtidor dimaco', 'en linea', 'Venta en linea', '+52 5157186', 1, '2024-07-01 13:17:50', '2024-07-01 13:17:50'),
(79, 'Raiker oaxaca', 'Blvrd José Vasconcelos 1225, Reforma, 68050 Oaxaca de Juárez, Oax.', 'Venta en linea', '951 513 9710', 1, '2024-07-01 13:19:46', '2024-07-01 13:19:46'),
(80, 'ITC (Ingenieria total y control)', ' Calle 14 Poniente Colonia Tierra y Libertad 2309, Villa San Alejandro, 72090 Heroica Puebla de Zaragoza, Pue.', 'Venta en linea', '222 336 3425', 1, '2024-07-01 13:20:32', '2024-07-02 15:48:18'),
(81, 'Tecneu ', 'Calle, San Angel 37-A, Santa Rosa, 45693 Las Pintas, Jal.', 'Venta en linea', '(+52) 3315110996.', 1, '2024-07-01 13:22:57', '2024-07-01 13:22:57'),
(82, 'suemi susuki partes', 'Calle Periferico S/n t, 68090 ', 'Venta en linea', '951-514-5724', 1, '2024-07-01 13:28:21', '2024-07-01 13:28:21'),
(83, 'Coronado partes', 'Prol. de Calzada Madero 1031, Guadalupe Victoria, 68030 Oaxaca de Juárez, Oax.', 'Venta en linea', '951 512 5155', 1, '2024-07-01 13:35:15', '2024-07-01 13:35:15'),
(84, 'beckon', 'en linea', 'Venta en linea', '449 999 4449', 1, '2024-07-01 13:46:31', '2024-07-01 13:46:31'),
(85, 'Ferretera del centro ', 'carranza #225, San Francisco del Rincón, Mexico', 'Venta en linea', '476 743 2520', 1, '2024-07-01 13:48:18', '2024-07-01 13:48:18'),
(86, 'IUSA', 'en linea', 'Venta en linea', ' 800 900 4872 CDMX y A. Metropolitana: 55 5118 1500', 1, '2024-07-01 13:49:54', '2024-07-01 13:49:54'),
(87, 'Vitraquim', ' Av. Sur 8 93, Agrícola Oriental, Iztacalco, 08500 Ciudad de México, CDMX', 'Venta en linea', ' 55 2235 0038', 1, '2024-07-01 13:53:31', '2024-07-01 13:53:31'),
(88, 'MAPOSA', 'Calle 13 norte,3404,Santa maria,Puebla,72080', 'Venta en linea', ' 222 2 32 39 06 | 222 793 50 94', 1, '2024-07-01 14:04:04', '2024-07-01 14:04:04'),
(89, 'Amaterasu iluminacion led', 'en linea', 'Venta en linea', ' 5530769545', 1, '2024-07-01 14:05:58', '2024-07-01 14:05:58'),
(90, 'tre', 'en linea', 'Venta en linea', 'no aplica', 1, '2024-07-01 14:41:36', '2024-07-01 14:41:36'),
(91, 'aliexpress', 'en linea', 'Venta en linea', 'no aplica', 1, '2024-07-01 14:45:27', '2024-07-01 14:45:27'),
(92, 'Rodotec', 'Juan Salvador Agraz #73\r\npiso 8, Col. Santa Fe\r\nDelegación Cuajimalpa\r\nDistrito Federal, C.P. 05348', 'Venta en linea', '+52 55 9177-0400', 1, '2024-07-01 14:53:11', '2024-07-01 14:53:11'),
(93, 'LA TAPATIA', 'EN LINEA', 'Venta en linea', 'no aplica', 1, '2024-07-01 15:10:55', '2024-07-01 15:10:55'),
(94, 'Koffeexpress', ' koffeeexpress_express@yahoo.com', 'Venta en linea', '732-643-9133 ', 1, '2024-07-02 11:09:05', '2024-07-02 11:09:05'),
(95, 'DEI design,Inc', 'http://www.deiequipment.com ', 'Venta en linea', 'Toll-free ordering & Customer Service 866-482-8919 From outside the US: +1-954-237-6722', 1, '2024-07-02 11:12:14', '2024-07-02 11:12:14'),
(96, 'Central de refriclimas', 'Frente al I.M.S.S, Carretera Transistmica, Super Carretera Transísmica Km. 2, Hidalgo Poniente, 70610 Salina Cruz, Oax.', 'Venta en linea', ' 971 265 6973', 1, '2024-07-02 12:58:06', '2024-07-02 12:58:06'),
(97, 'Wurth', 'Carr. Temixco - Emiliano Zapata Lote 17, Bodega 1 Desarrollo Industrial Emiliano Zapata (D.I.E.Z.), Col. Palo Escrito C.P. 62760 Emiliano Zapata, Morelos', 'Venta en linea', '7773287190 o 5514406115', 1, '2024-07-02 12:59:34', '2024-07-02 12:59:34'),
(98, 'TUDOGAR', 'En linea', 'Venta en linea', '22 2109 4500', 1, '2024-07-02 13:03:03', '2024-07-02 13:03:03'),
(99, 'Asfalto y grava', 'https://www.corporativoag.com.mx/productos/', 'Venta en linea', '(442) 192 9660 y (442) 192 9666', 1, '2024-07-02 13:05:56', '2024-07-02 13:05:56'),
(100, 'Ferretera Castillo', 'https://www.construrama.com/materialescastillo', 'Venta en linea', '9811280113 | 9811711313', 1, '2024-07-02 13:08:32', '2024-07-02 13:08:32'),
(101, 'Clevis fragua', 'https://clevis.com.mx/', 'Venta en linea', '55 5719 2446/55 5587 8074', 1, '2024-07-02 13:10:06', '2024-07-02 13:10:06'),
(102, 'Hommedepot', 'Diagonal de Av. Universidad, Avenida Universidad 140, Exhacienda Candiani, 68130 Oaxaca de Juárez, Oax.', 'Mostrador O en linea', '800 004 6633', 1, '2024-07-02 13:11:27', '2024-07-02 13:11:27'),
(103, 'GRINFE', 'https://www2.grinfe.com/', 'Venta en linea', '229 925 0412', 1, '2024-07-02 13:14:44', '2024-07-02 13:14:44'),
(104, 'Fofel', 'http://www.fofel.com.mx/', 'Venta en linea', '229 935 4281', 1, '2024-07-02 13:16:41', '2024-07-02 13:16:41'),
(105, 'Tavera', 'Av.cuahutemoc 3747\r\nCol.Veracruz Centro cp 91700 veracruz,VERACRUZ.', 'Juan cano mendez', '(229) 920-8369', 1, '2024-07-02 13:21:34', '2024-07-02 14:06:35'),
(106, 'CR store Oaxaca', ' Nte. 5 111 bis, FERROCARRIL, Victor Bravo Ahuja Sur, 71228 Santa Lucía del Camino, Oax.', 'Venta en linea', '951 206 1790', 1, '2024-07-02 13:47:51', '2024-07-02 13:47:51'),
(107, 'Importaciones Dentales Roentgen SA de CV', 'Camino San Juan de Aragón 856-D, Col. Casas Alemán, Del. G.A.M. Ciudad de México, C. P. 07580', 'Venta en linea', 'Tels. (55) 5748-4995, 5737-0075 y 5767-9365', 1, '2024-07-02 15:49:51', '2024-07-02 15:49:51'),
(108, 'Proveedora e integradora de bioinstrumental', 'Av 606 115, San Juan de Aragón IV Secc, Gustavo A. Madero, 07979 Ciudad de México, CDMX', 'Venta en linea', '55 3149 7405', 1, '2024-07-02 15:51:47', '2024-07-02 15:51:47'),
(109, 'SALUCOM S.A. DE CV.', 'en linea', 'Venta en linea', '(55)5355-9595', 1, '2024-07-02 15:59:41', '2024-07-02 15:59:41'),
(110, 'Ninextra', 'Mariano Abasolo # 11, Urbana Ixhuatepec, 55349 Ecatepec, Estado de México', 'contacto@alcoholerabalmex.com', '552 724 3164', 1, '2024-07-02 16:00:54', '2024-07-02 16:00:54'),
(111, 'Farmacias similares', 'https://www.farmaciasdesimilares.com/#!/', 'Venta en linea o mostrador', '800 911 6666', 1, '2024-07-02 16:03:20', '2024-07-02 16:03:20'),
(112, 'Farmacias del ahorro', 'https://www.fahorro.com/', 'Venta en linea o mostrador', 'depende de sucursal', 1, '2024-07-02 16:06:13', '2024-07-02 16:06:13'),
(113, 'Soriana', 'https://www.soriana.com\r\no\r\nAvenida Universidad 500 ', 'Venta en linea o mostrador', '55 5062 8019 o Marcar *78737', 1, '2024-07-02 16:27:42', '2024-07-02 16:27:42'),
(114, 'Addicor technologys', 'https://addicortechnologies.com/', 'Venta en linea', ' 1800-121-6659 /+91-9873983059', 1, '2024-07-02 16:30:27', '2024-07-02 16:30:27'),
(115, 'Cuisine QRO', ' Calle Ignacio Zaragoza 152 b, Centro, 76058 Santiago de Querétaro, Qro.', 'Venta en linea', '442 476 8850', 1, '2024-07-02 16:31:48', '2024-07-02 16:31:48'),
(116, 'twilight', 'Alfonso Reyes 2612, Piso 7, Of. 704 , Ed. Connexity, Col.\r\nDel Paseo Residencial, Monterrey, Nuevo León , México.', 'Venta en linea', '(81) 8115-1400 / (81) 8173-4300 / 800 087 43 75  ', 1, '2024-07-02 16:33:40', '2024-07-02 16:33:40'),
(117, 'Nafarrete Equipo medico', 'https://www.nafarrate.com', '', '800 NAFANET (623 2638)', 1, '2024-07-02 16:35:11', '2024-07-02 16:35:11'),
(118, 'Papeleria Lucky Puebla', 'Av. 8 Ote. No. 13-Local 1B, Centro histórico de Puebla, 72000 Heroica Puebla de Zaragoza, Pue.', 'Venta en linea o mostrador', ' 222 214 5768', 1, '2024-07-02 16:36:25', '2024-07-02 16:36:25'),
(119, 'PC digital', ' Plaza Agua Azul, 11 Sur No. 2306 Local 22, Esquina con 25 Pte., Col. Chula Vista., C.P. 72420., Puebla, Puebla', 'Venta en linea o mostrador', '(222) 945 560/ (222) 945 5602', 1, '2024-07-02 16:38:16', '2024-07-02 16:38:16'),
(120, 'Zedent', 'Ixcoatla 1908, San Bernardino Tlaxcalancingo, Puebla, México', 'Venta en linea ', '222.945.4225  ​  ​  222.111.3459  ​  ​  222.136.5761', 1, '2024-07-02 16:40:04', '2024-07-02 16:40:04'),
(121, 'Dental Q', ' 04480 CTM V, Culhuacan, 04480 Ciudad de México, CDMX', 'Venta en linea o mostrador', '55 9196 0765', 1, '2024-07-02 16:40:50', '2024-07-02 16:40:50'),
(122, 'Donaji', 'Donaji', 'mostrador', 'no aplica', 1, '2024-07-02 16:41:45', '2024-07-02 16:41:45'),
(123, 'ServerXpress', 'Enrique Rébsamen 440, Narvarte Poniente, Benito Juárez, 03020 Ciudad de México, CDMX', 'Venta en linea o mostrador', '55 9307 4480', 1, '2024-07-02 16:43:12', '2024-07-02 16:43:12'),
(124, 'NEWARK', 'https://mexico.newark.com/?&CMP=KNC-GMX-BRAND-ES-ONLY-CONTROL-S43&mckv=sz2BzCfec_dc|pcrid|569194172800|plid||kword|newark|match|b|slid||product||pgrid|152734864489|ptaid|kwd-10065921|&gad_source=1&gclid=CjwKCAjwyo60BhBiEiwAHmVLJZSku_VaFdEBoDSlZLu6s4zlEnuAc33F6VyY_ZaMvahD1AdC6L3DnRoCdoYQAvD_BwE', 'Venta en linea', ' 01.800.463.9275', 1, '2024-07-02 16:44:20', '2024-07-02 16:44:20'),
(125, 'Merceria Novedades', 'Prol. de Colón 1404A, FERROCARRIL, Agencia de Policia de Cinco Señores, 68120 Oaxaca de Juárez, Oax.', 'Venta en mostrador', '951 511 0816', 1, '2024-07-02 16:47:42', '2024-07-02 16:47:42'),
(126, 'Supplyhouse', 'https://www.supplyhouse.com', 'Venta en linea', '888-757-4774', 1, '2024-07-02 16:49:21', '2024-07-02 16:49:21'),
(127, 'Distribuidora galma', 'Senda Eterna 640, Milenio III, 76060 Santiago de Querétaro, Qro.', 'https://distribuidoragalma.com/pages/contactanos', '442 802 5199', 1, '2024-07-02 16:50:26', '2024-07-02 16:50:26'),
(128, 'Ferretubos', 'https://ferretubos.com.mx', 'Venta en linea o mostrador', '951 656 22 21', 1, '2024-07-02 16:51:58', '2024-07-02 16:51:58'),
(129, 'Medicamentos diaz gonzales', 'Jordan Lomo los Frailes 79\r\n35018 Tamaraceite,españa', 'Venta en linea', 'online', 1, '2024-07-02 16:54:53', '2024-07-02 16:54:53'),
(130, 'Tubos y conexiones', 'https://tubosyconexiones.mx', 'Venta en linea o mostrador', '951-282-2002', 1, '2024-07-02 16:58:15', '2024-07-02 16:58:15'),
(131, 'BestNey Mubles medicos', ' Centeno 600, Granjas México, Iztacalco, 08400 Ciudad de México, CDMX', 'Venta en linea o mostrador', '5572630847', 1, '2024-07-02 16:59:30', '2024-07-02 16:59:30'),
(132, 'Medical rental', 'Dr. Eduardo Aguirre Pequeño 1205, Mitras Centro, 64460 Monterrey, N.L.', 'Venta en linea', '81 8348 3806', 1, '2024-07-02 17:00:34', '2024-07-02 17:00:34'),
(133, 'Poltrone Muebles', 'https://www.poltrone.mx', 'Venta en linea', '55 42 703937', 1, '2024-07-03 09:19:42', '2024-07-03 09:19:42'),
(134, 'Ferrepat', 'Calle 3 Sur # 313. C.P. 75700, Col. Centro. Tehuacán Puebla, México. ', 'Venta en linea o mostrador', 'Tel: 238 39 216 46 Whatsapp: 23 8100 1418', 1, '2024-07-03 09:21:04', '2024-07-03 09:21:04'),
(135, 'Liverpool', 'Avenida Ing, Jorge L. Tamayo Castellanos 500, Agencia de Policia de Candiani, Oaxaca de Juárez', 'Venta en linea o mostrador', ' 951 501 7000', 1, '2024-07-03 09:22:20', '2024-07-03 09:22:20'),
(136, 'Torretas y más', 'Av. Miguel Alemán 38, 52975 El Potrero, Méx.', 'Venta en linea o mostrador', '55 7042 1303', 1, '2024-07-03 09:23:07', '2024-07-03 09:23:07'),
(137, 'Succes', 'WI-FI', 'Venta en linea', 'no aplica', 1, '2024-07-03 09:52:34', '2024-07-03 09:52:34'),
(138, 'Climas monterrey', 'Av. Cristóbal Colón 1014, Centro, 64000 Monterrey, N.L.', 'Venta en linea o mostrador', '(81) 8288-7500/01 800 288-7500', 1, '2024-07-03 10:12:20', '2024-07-03 10:12:20'),
(139, 'Frio potencia y herramientas', 'Oriente 10 #718 Entre Sur 15 y 17, Centro, 94300 Orizaba, Ver.', 'Venta en linea o mostrador', '272 724 4235', 1, '2024-07-03 10:13:18', '2024-07-03 10:13:18'),
(140, 'TessTool ', '2554 State Street\r\nHamden, Ct. 06517\r\n', 'Venta en linea o mostrador', 'TEXT US: 203 494 4415/CALL US: 203 248 7553', 1, '2024-07-03 10:15:20', '2024-07-03 10:15:20'),
(141, 'Transfer Multisort Elektronik', 'ul. Rozalii 1\r\n93-351 Łódź, Polonia', 'Venta en linea o mostrador', '+48 42 645 54 44', 1, '2024-07-03 10:18:15', '2024-07-03 10:18:15'),
(142, 'Solcae S.A. de C.V.', 'Av. Carlos Hank González #50\r\nCol. Valle de Anáhuac, Mz 29\r\nLocal 32 y 33, CP 55210,\r\nEcatepec de Morelos,\r\nEstado De México.', 'Venta en linea o mostrador', 'Teléfono Oficina:  55 92982651/52  WhatsApp:  55 6292 1820', 1, '2024-07-03 10:20:07', '2024-07-03 10:20:07'),
(143, 'Baoblaze', 'Buscar en amazon', 'Venta en linea', 'no aplica', 1, '2024-07-03 10:22:07', '2024-07-03 10:22:07'),
(144, 'Euromex', 'Carretera de Barcelona 88, Entresuelo\r\nEsc. B - Local 9\r\n08302 Mataró, España', 'Venta en linea', 'Teléfono+34 (0) 937 415 609/E-mailinfo@euromex.com', 1, '2024-07-03 10:24:10', '2024-07-03 10:24:10'),
(145, 'Colson Caster', '3700 Airport Road\r\nJonesboro, AR 72401', 'Venta en linea', '+1 800-253-0868', 1, '2024-07-03 10:25:38', '2024-07-03 10:25:38'),
(146, 'Moragas', 'Online', 'Venta en linea', '3321549447', 1, '2024-07-03 10:26:57', '2024-07-03 10:26:57'),
(147, 'Sanson parillas', 'Protón 11, Parque Industrial Naucalpan, Naucalpan, Edo. de México C.P. 53370.', 'Eduardo Delgadillo Olivares', '5566089164', 1, '2024-07-03 10:30:23', '2024-07-03 10:30:23'),
(148, 'Vica', 'Av. Gustavo Baz 2160, Edificio n° 7, Bodega n°2\r\nColonia La Loma, Tlalnepantla de Baz\r\nC.P. 54060', 'Venta en linea o mostrador', '55 9000 0744', 1, '2024-07-03 10:31:16', '2024-07-03 10:31:16'),
(149, 'Grainger', 'Boulevard Hermanos Serdán #760,, San Rafael Oriente, Puebla, Puebla, PU, 72020', 'Venta en linea', '800 800 8080', 1, '2024-07-03 10:32:42', '2024-07-03 10:32:42'),
(150, 'Perssa', 'SM308 M02 LT45 Blvd Luis Donaldo y Entrada Bo, Alfredo V Bonfil, Cancun Q.R. CP 77560', 'Venta en linea o mostrador', 'Teléfono: 01 (998) 313 7794', 1, '2024-07-03 10:33:57', '2024-07-03 10:33:57'),
(151, 'Hegamex', 'Carretera Atotonilco - La Barca 151, Milpillas Crucero, Milpillas - Margaritas, Milpillas, 47775 Atotonilco el Alto, Jal.', 'Venta en linea o mostrador', '391 917 1277', 1, '2024-07-03 10:35:29', '2024-07-03 10:35:29'),
(152, 'Refaciones del hogar', 'CALLE GERMÁN EVERS, 1708, CENTRO, MAZATLAN, SIN, C.P. 82000', 'Venta en linea o mostrador', '669-985-0794/669-326-8925', 1, '2024-07-03 10:37:18', '2024-07-03 10:37:18'),
(153, 'Denek Refacciones', 'Online', 'Venta en linea ', 'Cotizaciones: 81 2870 7083 Facturación: 81 8337 7999 Envíos: 81 8337 7999 Otro Motivo: 81 8337 7949', 1, '2024-07-03 10:38:23', '2024-07-03 10:38:23'),
(154, 'Imator refacciones tortillera rodotec', 'Mérida', 'Venta en linea o mostrador', '999 208 5092', 1, '2024-07-03 10:42:31', '2024-07-03 10:42:31'),
(155, 'Dominium', 'Paganini No. 267 Col. Vallejo, México, D.F. C.P. 07870', 'Venta en linea', '+55 3096 6666', 1, '2024-07-03 10:47:04', '2024-07-03 10:47:04'),
(156, 'Acomee', 'Av. Dr. Salvador Nava Martínez No. 704-B\r\n\r\nCol. Nuevo Paseo.\r\n\r\nSan Luis Potosí, SLP.\r\n\r\nCP. 78328', 'Venta en linea', 'Teléfonos de contacto: (444) 5850878, 5850879, 8415452, 8415949, 8415970, 8415971.', 1, '2024-07-03 10:50:10', '2024-07-03 10:50:10'),
(157, 'Pahusa', 'Anillo Périferico Poniente Km. 31.5, Tablaje 20723, Col. Chenkú, C.P 97219, Mérida, Yucatán.', 'Venta en linea', '(999) 940  65 20 ', 1, '2024-07-03 10:51:31', '2024-07-03 10:51:31'),
(158, 'Suministros de Metrología', 'Prolongación Antonio Diaz Varela No. 123, Col. Industrial, Chiautempan/ Tlaxcala. C.P. 90802, México', 'Venta en linea o mostrador', ' Teléfono (246) 144 0420 y (246) 464 6887', 1, '2024-07-03 10:53:16', '2024-07-03 10:53:16'),
(159, 'Chupaprecios', 'Calle Centro Comercial 17206\r\n\r\nCol, Otay Constituyentes\r\n\r\nC.P 22457 Tijuana, BC.', 'Venta en linea o mostrador', 'Atención Telefónica: (+52) 664 311 9682', 1, '2024-07-03 10:54:31', '2024-07-03 10:54:31'),
(160, 'Refaccionaria luna', 'Huzares 117, Zona Lunes Feb 09, Centro, 68000 Oaxaca de Juárez, Oax.', 'Venta mostrador', '951 124 6575', 1, '2024-07-03 10:57:55', '2024-07-03 10:57:55'),
(161, 'Lumi Material Electrico', 'Avenida 14 Poniente esquina con 23 Norte, Col. Lázaro Cárdenas Puebla, Puebla CP. 72140', 'Venta en linea o mostrador', 'Tel. (01222) 246 5577 Tel. (01222) 242 5518 Cel. 2224 21 5132', 1, '2024-07-03 10:59:19', '2024-07-03 10:59:19'),
(162, 'Bomssa', 'Calle 27 #200 Entre calle 4A y calle 4 Colonia Santa Maria Chi, Mérida, Yucatán, México.', 'Venta en linea o mostrador', '+52 9992 61 81 12', 1, '2024-07-03 11:00:17', '2024-07-03 11:00:17'),
(163, 'Taller de torno Eloy', 'Prolongación de Rayón # 204 Colonia 5 Señores, Oaxaca de Juárez, Mexico', 'Venta en linea o mostrador', 'Whatsapp:9512432191', 1, '2024-07-03 11:04:39', '2024-07-03 11:04:39'),
(164, 'Coresa', 'Calle Artículo 123 No. 116 Esquina con Calle de Humboldt, Cuauhtémoc CP 06040', 'Venta en linea o mostrador', '800 700 5050/(55) 5025.9090', 1, '2024-07-03 15:44:49', '2024-07-03 15:44:49'),
(165, 'Celim clean center', 'Calle Manzanillo No. 68Col. Roma Sur, Cuauhtémoc, Ciudad de México, CDMX', 'Venta en linea o mostrador', '333 636 3218', 1, '2024-07-03 15:48:55', '2024-07-03 15:48:55'),
(166, 'Lurosa Seals', 'Calz. Vallejo 1110, Hab Prado Vallejo, 54170 Tlalnepantla, Méx.', 'Venta mostrador', 'No hay', 1, '2024-07-03 15:51:21', '2024-07-03 15:51:21'),
(167, 'Grupo Mereti', '19 Poniente No. 1101, Col. Barrio de Santiago, C.P. 72410, Puebla', 'hola@grupomereti.com', 'Tel. 222 2378 122 / 5532-3605', 1, '2024-07-03 15:52:18', '2024-07-03 15:52:18'),
(168, 'Refacciones del hogar', 'Col. López Mateos', 'Venta en linea o mostrador', 'Tel: 669-983-3605', 1, '2024-07-03 16:10:24', '2024-07-03 16:10:24'),
(169, 'SISTELEC', 'Carretera Federal Córdoba-Veracruz Km. 23.5\r\nCuitláhuac, Veracruz C.P. 94910', 'Venta en linea o mostrador', 'Teléfono: 01 (278) 732-0753/01 (278) 732-0258/01 (278) 732-5917', 1, '2024-07-03 16:50:18', '2024-07-03 16:50:18'),
(170, 'Casman LK', 'v. Bacardi, Tres Picos, 54763 Cuautitlán Izcalli, Méx.', 'Venta en linea o mostrador', ' 56 1044 9527', 1, '2024-07-03 16:55:39', '2024-07-03 16:55:39'),
(171, 'DICISA', ' C. 28 149, García Ginerés, 97070 Mérida, Yuc.', 'Venta en linea o mostrador', ' 999 920 0891 ', 1, '2024-07-03 16:56:31', '2024-07-03 16:56:31'),
(172, 'RagaNet', 'Av. Camino Nuevo a Huixquilucan 54, Las Canteras, 52770 Naucalpan de Juárez, Méx.', 'Venta en linea o mostrador', '55 9137 4956', 1, '2024-07-03 16:57:22', '2024-07-03 16:57:22'),
(173, 'MROsupply', 'MRO Supply, Inc. 2915 E Washington Blvd. Los Angeles,CA. 90023', 'Venta en linea', '+1 888 671 2883 customerservice@mrosupply.com +1 323 263 4131 International Fax: +1-323-908-6064', 1, '2024-07-03 17:00:26', '2024-07-03 17:00:26'),
(174, 'TRANE', 'Online', 'Venta en linea', '800 841 3730', 1, '2024-07-03 17:01:20', '2024-07-03 17:01:20'),
(175, 'Industrial Store', 'Online', 'Venta en linea', 'Toll free : 1-866-494-4610', 1, '2024-07-03 17:02:25', '2024-07-03 17:02:25'),
(176, 'Link Parts', 'Calzada San Pedro 105, Interior 101 Del Valle, C.P. 66220 – San Pedro Garza García, N.L., México', 'Venta en linea o mostrador', 'Tel. 33 1695 3550', 1, '2024-07-03 17:03:45', '2024-07-03 17:03:45'),
(177, 'CT Store', 'C. Azucenas 113, Reforma, 68050 Oaxaca de Juárez, Oax.', 'Venta en linea o mostrador', '951 518 4551', 1, '2024-07-03 17:05:25', '2024-07-03 17:05:25'),
(178, 'Innovacion Foodservice México', 'Pedro Moreno 1775 Local 1\r\nCol. Americana, Lafayette\r\nC.P. 44160 Guadalajara, Jalisco', 'Venta en linea o mostrador', '33 3407 2407/contacto@innova-fs.com', 1, '2024-07-04 11:18:58', '2024-07-04 11:18:58'),
(179, 'Corporación ECO Industrial y Comercial', 'Plan de San Luis 101-801\r\nEl Coecillo CP. 37260\r\nLeón, Guanajuato, Mx.', 'Venta en linea o mostrador', 'Tel. 442 298 3363 Cel. 56 1027 0696', 1, '2024-07-04 11:20:44', '2024-07-04 11:20:44'),
(180, 'Grupo Luin', 'González Ortega No 410-local 1, Zona Feb 10 2015, Centro, 68080 Oaxaca de Juárez, Oax.', 'Ventas Internet o mostrador', ' 951 688 5632', 1, '2024-07-04 11:23:09', '2024-07-04 11:23:09'),
(181, 'LICA S.A. DE C.V.', 'Calle El Calvario, San Salvador, El Salvador', 'https://lica.mx/', '+503 2501 9400', 1, '2024-07-04 11:24:39', '2024-07-04 11:24:39'),
(182, 'Industriales de piel', 'https://www.industrialesdepiel.com', 'Venta en linea', 'Teléfonos: (477)792-12-64 /(477)727-04-75 /(477)754-59-74', 1, '2024-07-04 11:28:23', '2024-07-04 11:28:23'),
(183, 'Parts Town', 'Parts Town\r\n1200 Greenbriar Dr.\r\nAddison, IL 60101', 'Venta en linea', '630.403.9159 / international@partstown.com', 1, '2024-07-04 11:29:44', '2024-07-04 11:29:44'),
(184, 'Ferre Fix', 'Av. Reforma Sur #201, PUEBLA (PUE, PU, PL)\r\n\r\n75700', 'https://www.fixferreterias.com/storelocator', ' 55 5350 9472', 1, '2024-07-04 12:35:54', '2024-07-04 12:35:54'),
(185, 'SEGURI.com', ' : Priv. Parque México 101. Parque Industrial México. Apodaca, NL, Mexico. C.P. 66633', 'Venta en linea o mostrador', 'Oficina: (81) 7770-0636', 1, '2024-07-04 12:37:30', '2024-07-04 12:37:30'),
(186, 'ULINE', 'Uline Shipping Supplies,\r\nS. de R.L. de C.V.\r\nCarr. Miguel Alemán KM 21, #6\r\nPrologis Park Apodaca\r\nApodaca, N.L. C.P. 66627', 'Venta en linea o mostrador', '800-295-5510', 1, '2024-07-04 12:38:20', '2024-07-04 12:38:20'),
(187, ' Ferreteria El Oso', 'Online', 'Venta en linea', 'Atención en General:  464 647 0049  464 126 4685 /Ventas a Empresas: ventasb2b@ferreteriaeloso.mx /whatsapp:4642054992', 1, '2024-07-04 12:41:43', '2024-07-04 12:41:43'),
(188, 'SERSA', '\r\nAvenida Península Ibérica #4090, Colonia Villa Española, Guadalupe, Nuevo León', 'Venta en linea o mostrador', '+528180481500', 1, '2024-07-04 14:03:33', '2024-07-04 14:03:33'),
(189, 'Digikey', 'Online', 'Venta en linea', '800-351-0126', 1, '2024-07-04 14:05:14', '2024-07-04 14:05:14'),
(190, 'Temu', 'Online', 'Venta en linea', 'No hay', 1, '2024-07-04 14:07:38', '2024-07-04 14:07:38'),
(191, 'VANVIEN', 'Calzada San Esteban 24, El Parque, 53390, Naucalpan de Juárez, México', 'Venta en linea', 'T: +55 5358 0988 T: +52 (55) 5358 0354 W: +52 (55) 7672 2447', 1, '2024-07-04 14:13:41', '2024-07-04 14:13:41'),
(192, 'Grupo ROGU', 'Pelicano 1471, Morelos, 44919 Guadalajara, Jal.', 'Venta en linea o mostrador', '33 8421 5252', 1, '2024-07-04 14:15:48', '2024-07-04 14:15:48'),
(193, 'Piquio Mart', 'Av. 10 Pte. 907, Centro histórico de Puebla, 72000 Heroica Puebla de Zaragoza, Pue.', 'Venta en linea o mostrador', '222 196 6570', 1, '2024-07-04 14:20:03', '2024-07-04 14:20:03'),
(194, 'Grupo Zuma', 'Av. Paseo Constituyentes 1001-A, Residencial del Valle, CP 76190 Santiago de Querétaro, Qro.', 'Venta en linea o mostrador', 'Telefono:442 303 6523 /WhatsApp:442 810 1852', 1, '2024-07-04 14:21:53', '2024-07-04 14:22:17'),
(195, 'Concept Car(Mercado libre)', 'Mercado libre', 'Venta en linea', 'No hay', 1, '2024-07-04 14:25:48', '2024-07-04 14:25:48'),
(196, 'IMLUB GAJ solutions(Mercado libre)', 'Calle Oyamel #13, Santa Gertrudis, Hidalgo\r\n\r\nXoloc MZ15 L41, C.P. 42115,\r\nPitahayas Huixmí, Hidalgo.', 'Buscar en su Pagina propia o Mercado Libre', '771 234 8740', 1, '2024-07-04 14:27:52', '2024-07-04 14:27:52'),
(197, 'Runsa Autopartes', 'Av. Pacífico 181, Los Reyes, Coyoacán, 04330 Ciudad de México, CDMX', 'Venta en linea o mostrador', 'Teléfonos: 55 5484 5700, 55 5484 5701', 1, '2024-07-04 14:29:10', '2024-07-04 14:29:10'),
(198, 'LEXOY', 'Dirección : Boulevard Miguel Aleman 1215-B, Hercilia, Ciudad Miguel Alemán, Tamaulipas, Mexico. 88306.', 'Venta en linea', 'Telefono: +52-(897)-116-9178', 1, '2024-07-04 14:30:23', '2024-07-04 14:30:23'),
(199, 'Codissa', 'Alexander Fleming Mza 14 lote 8,\r\nCol. Granja San Cristobal, Coacalco, Edo Mex. C.P. 55726', 'Venta en linea o mostrador', 'Teléfono: 55 2956 7221  Whatsapp:  55 2956 7221', 1, '2024-07-04 14:31:52', '2024-07-04 14:31:52'),
(200, 'MarQuez Industriales de piel y seguridad ', 'Mercado Republica Local Int. 4\r\nLeón, Guanajuato, Mexico.', 'Venta en linea o mostrador', 'Telefono. ‭477 714 0372‬‬ Whatsapp. 477 760 1518', 1, '2024-07-04 14:35:36', '2024-07-04 14:35:36'),
(201, 'Safety Store', 'Río San Lorenzo 503 Col. Del Valle, SPGG, NL.', 'Venta en linea o mostrador', 'Whatsapp:(81) 1577 0557 /Telefono:(81) 2085 8122', 1, '2024-07-04 14:37:43', '2024-07-04 14:37:43'),
(202, 'R.S Huges', 'Vicente Guerrero No 317-D\r\nCol. Francisco I. Madero\r\nSan Mateo Atenco, EM 52106\r\nMéxico', 'Venta en linea o mostrador', 'Tel +52 (728) 688-1614 /+52 (722) 673-6178', 1, '2024-07-04 14:38:56', '2024-07-04 14:38:56'),
(203, 'Thermo King', 'Carretera Federal 45, Tramo Silao-León km 158+860, Fraccionamiento Granjas Campestres San Antonio, Silao Guanajuato', 'Venta en linea', '(472) 690 5555 ', 1, '2024-07-04 14:41:51', '2024-07-04 14:41:51'),
(204, 'WebstaurantStore', 'ONLINE', 'Venta en linea', 'No hay', 1, '2024-07-04 14:43:16', '2024-07-04 14:43:16'),
(205, 'Alianza de Antequera', '5 de Mayo 808, Barrio de Jalatlaco, 68080 Oaxaca de Juárez, Oax.', 'Venta en linea o mostrador', '951 516 5132', 1, '2024-07-04 16:48:38', '2024-07-04 16:48:38'),
(206, 'Anuar Bazan', 'Whatsapp', 'Venta en linea', '7774590042', 1, '2024-07-05 09:23:08', '2024-07-05 09:23:08'),
(207, 'Articulos de Limpieza Klael', 'Etla,Oaxaca', 'Venta por telefono', '9512347830', 1, '2024-07-05 09:26:08', '2024-07-05 09:44:10'),
(208, 'Fantasias Eliseo', 'Miahuatlan', 'Venta en mostrador', '', 1, '2024-07-05 09:27:33', '2024-07-05 09:27:33'),
(209, 'Tus Ideas Impresas', 'Oaxaca', 'Venta en telefono', '9511963580', 1, '2024-07-05 09:31:12', '2024-07-05 09:31:12'),
(210, 'Oaxaca', 'oaxaca', 'Mostrador', 'No hay', 1, '2024-07-05 09:43:53', '2024-07-05 09:43:53'),
(211, 'Merceria Centro', 'Oaxaca', 'Venta por telefono', 'No lo tenemos ', 1, '2024-07-05 09:47:45', '2024-07-05 09:47:45'),
(212, 'SOS bonita', 'Plaza Santa Mónica, Camino a Santa Mónica #8 (conocida también como:Avenida Benito Juárez).\r\nCol. Las Margaritas, Tlanepantla, Estado de México.\r\nC.P. 54050', 'Venta en linea', ' Teléfono de la boutique: 55 6729 4691 WhatsApp: 55 6055 5139', 1, '2024-07-05 09:55:23', '2024-07-05 09:55:23'),
(213, 'Ramsa', 'https://gruporamsa.com/contacto.html', 'Venta en linea', ' 55.4460.4407', 1, '2024-07-05 10:04:15', '2024-07-05 10:04:15'),
(214, 'SAMS OAXACA', 'Avenida Universidad 601, Exhacienda Candiani, 68130 Oaxaca de Juárez, Oax.', 'Venta en linea', ' 951 506 0106', 1, '2024-07-05 10:06:49', '2024-07-05 10:06:49'),
(215, 'Fuentes Automtriz', 'Av. Cañada 2-12, Lomas de la Estancia, Iztapalapa, 09640 Ciudad de México, CDMX', 'Venta en linea o mostrador', ' 55 1551 4934', 1, '2024-07-05 10:07:33', '2024-07-05 10:07:33'),
(216, 'Car Master', 'CARRETERA NAC. MEXICO-TAMPICO KM. 214\r\nHUEJUTLA DE REYES, Hidalgo', 'Venta en linea o mostrador', ' (789)896 64 33', 1, '2024-07-05 10:09:27', '2024-07-05 10:09:27'),
(217, 'Chedraui', ' Heroica Escuela Naval Militar 917, Reforma Centro, 68050 Oaxaca de Juárez, Oax.', 'Venta en linea o mostrador', ' 800 925 1111', 1, '2024-07-05 10:12:01', '2024-07-05 10:12:01'),
(218, 'MIK multiplasticos', 'Av. Paseo de la Reforma 215, Col. Lomas de Chapultepec lll sección, Miguel Hidalgo CDMX, C.P.11000', 'Venta en linea', '​55 5393 6825 o 55 5393 9755 ', 1, '2024-07-05 10:13:19', '2024-07-05 10:13:19'),
(219, 'AVM Tools', ' Diag. Defensores de la República 318, Lázaro Cárdenas Oriente, 72100 Heroica Puebla de Zaragoza, Pue.', 'Venta en linea', '222 947 5248', 1, '2024-07-05 10:14:03', '2024-07-05 10:14:03'),
(220, 'SITECSA', 'Orion 3953 1-C, Colonia La Calma , Zapopan, Jalisco . Código postal 45070', 'Venta en linea o mostrador', '+52 1 (33) 3631-1980', 1, '2024-07-05 10:15:05', '2024-07-05 10:15:05'),
(221, 'GANON ', 'Tiendas\r\nVolver a lista de tiendas\r\nMEDRANO\r\nJose Maria La Fragua 247-B\r\nLa Loma, Medráno\r\n44800, JAL', 'Venta en linea', '33-3653-2158', 1, '2024-07-05 10:20:28', '2024-07-05 10:20:28'),
(222, 'Parisina', ' C. de Carlos María Bustamante 112, OAX_RE_BENITO JUAREZ, Centro, 68000 Oaxaca de Juárez, Oax.', 'Venta en linea o mostrador', 'No hay', 1, '2024-07-05 10:24:44', '2024-07-05 10:24:44'),
(223, 'SYB Oaxaca', 'Miguel Hidalgo 1301, Centro, 68000 Oaxaca de Juárez, Oax.', 'Venta en linea o mostrador', '951 514 9151', 1, '2024-07-05 10:25:25', '2024-07-05 10:25:25'),
(224, 'Inix Comercial', 'Eucaliptos, 68050 Oaxaca de Juárez, Oax.', 'Venta en linea o mostrador', '55) 54 89 00 54', 1, '2024-07-05 10:26:55', '2024-07-05 10:26:55'),
(225, 'Comarca', 'Revolución 531, Burócratas del Estado, 64380 Monterrey, N.L.', 'Venta en linea o mostrador', 'Tel: 81.8311.3289', 1, '2024-07-05 10:27:56', '2024-07-05 10:28:12'),
(226, 'Modatelas ', ' Calle de Armenta y López 319, OAX_RE_BENITO JUAREZ, Centro, 68000 Oaxaca de Juárez, Oax.', 'Venta en linea o mostrador', '951 514 3466', 1, '2024-07-05 10:29:04', '2024-07-05 10:29:04'),
(227, 'PROCLIFF', 'Manuel Doblado 702, Centro, 36400 Cortazar, Gto', 'Venta en linea', '476 743 1800', 1, '2024-07-05 10:29:58', '2024-07-05 10:29:58'),
(228, 'FERREX', 'Blvd. Arandas esq. Paseo de las Plazas No.11 Local 1\r\n\r\n', 'Venta en linea o mostrador', 'Tel 4622865731 , WhatsApp 4622865731', 1, '2024-07-05 10:30:51', '2024-07-05 10:30:51'),
(229, 'Centro tornillero del Sureste', 'CARR INTERNACIONAL 2213-A LAS FLORES, OAXACA, OAXACA 71228', 'Venta en linea o mostrador', ' 951-144-7267/951-144-8821/951-513-7055', 1, '2024-07-05 10:32:09', '2024-07-05 10:32:09'),
(230, 'INFRA Oaxaca', ' Av. Símbolos Patrios 15, Universidad, Exhacienda Candiani, 71233 Santa Cruz Xoxocotlán, Oax.', 'Venta en linea o mostrador', '951 516 3780', 1, '2024-07-05 10:33:14', '2024-07-05 10:33:14'),
(231, 'Lefix y Asociados', '28 de Diciembre 87, Coapa, Emiliano Zapata, Coyoacán, 04815 Ciudad de México, CDMX', 'Venta en linea o mostrador', '56843301', 1, '2024-07-05 10:34:07', '2024-07-05 10:34:07'),
(232, 'Vencort', 'Av. Plutarco Elías Calles 816 A Col. San Pedro Iztacalco, MX-CMX, CP 08220, CDMX.', 'Venta en linea o mostrador', 'Teléfono: 55 5590 6028', 1, '2024-07-05 10:34:52', '2024-07-05 10:34:52'),
(233, 'CUCO Panaderos y Reposteros', '\r\nCALLE CONSTITUYENTES 136 A GUADALUPE VICTORIA, OAXACA DE JUAREZ, OAXACA 68030', 'Venta en linea o mostrador', ' 951-549-0040', 1, '2024-07-05 10:37:03', '2024-07-05 10:37:03'),
(234, 'Ecodeli', 'Calz. Heroes de Chapultepec 304, RUTA INDEPENDENCIA, Centro, 68000 Oaxaca de Juárez, Oax.\r\n', 'Venta en linea o mostrador', '951 513 8734', 1, '2024-07-05 10:37:45', '2024-07-05 10:37:45'),
(235, 'TERMICGUAE ', 'Calle bosques de cedros Mz 8 Lt8-interior 9B, La Piedad, 54720 Cuautitlán Izcalli, Méx.', 'Venta en linea o mostrador', '55 1204 1316', 1, '2024-07-05 10:39:04', '2024-07-05 10:39:04'),
(236, 'Medallas Aurum', 'C. Ignacio Herrera y Cairo 1348, Santa Teresita, 44600 Guadalajara, Jal.', 'Venta en linea', ' 33 3614 4400', 1, '2024-07-05 10:40:10', '2024-07-05 10:40:10'),
(237, 'Hidro-cocinas', 'Sierra de Pinos 401, Bosques del Prado Sur, 20130 Aguascalientes, Ags.', 'Venta en linea', ' 449 238 8676', 1, '2024-07-22 10:50:19', '2024-07-22 10:50:19'),
(238, 'BHI grupo corporativo internacional', 'CALLE EZFUERZO NACIONAL NO. 11\r\n53370 Naucalpan de Juarez, Mexico', 'Venta en linea', 'No hay', 1, '2024-07-22 10:53:16', '2024-07-22 10:53:16'),
(239, 'GRUPO FINANCIERO PAAL', '16 Poniente Norte 138 Loc A y B Fracc Las, Arboledas, 29030 Tuxtla Gutiérrez, Chis.', 'Venta en linea', '961 121 3404', 1, '2024-08-07 15:05:38', '2024-08-07 15:05:38'),
(240, ' ECOMAQMX -Ecomaq Mexico SA de CV-', 'Priv. Emiliano Zapata 5997, Bugambilias, Bugambilias 3ra Secc, 72580 Heroica Puebla de Zaragoza, Pue.', 'Venta en venta', '222 219 3524', 1, '2024-08-13 16:17:34', '2025-09-30 19:55:41'),
(241, 'Ferreteria aguilar', 'C. Benito Juárez 1, Centro, 71510 Ejido del Centro, Oax.\r\n', 'Venta en linea o mostrador', ' 951 571 0076', 1, '2024-08-13 16:18:51', '2024-08-13 16:18:51'),
(242, 'ALGER-Refacciones, Reparaciones, y Venta De Electrodomésticos en Puebla ', ' Av. 10 Ote. 7, Centro histórico de Puebla, 72000 Heroica Puebla de Zaragoza, Pue.', 'Venta en linea o mostrador', '222 232 4287', 1, '2024-08-13 16:19:59', '2024-08-13 16:19:59'),
(243, 'Cyberpuerta', 'Álamo Business Park, Av. Patria: S/N, Parque Industrial El Álamo, Las Juntas, 44490 Guadalajara, Jal.', 'Venta en linea o mostrador', '33 4737 1360', 1, '2024-08-13 16:20:56', '2024-08-13 16:20:56'),
(244, 'FRIGOTEK', '\r\n Av. Tulum Poniente Mza 8. Lt. 6 entre Kukulkán Sur y Saturno Sur 77760', 'Venta en linea o mostrador', ' 9842183264', 1, '2024-08-13 16:22:43', '2024-08-13 16:22:43'),
(245, 'Equipos de refrigeración y refacciones cuitláhuac', 'Cerrada Poniente. 13-A. #4442\r\nCol. Héroe de Nacozári • Alcaldía. Gustavo A Madero.\r\nCDMX. CP. 07780.', 'Venta en linea o mostrador', '(55) 5355-1912 (55) 5355-1910 (55) 5355-1331 (55) 5355-3399', 1, '2024-08-13 16:25:00', '2024-08-13 16:25:00'),
(246, 'TOOLPAN', '1220 Maple Ave #605, Los Angeles, CA 90015, Estados Unidos', 'Venta en linea o mostrador', ' +1 888-784-1615', 1, '2024-08-13 16:26:13', '2024-08-13 16:26:13'),
(247, 'MAGOCAD', 'Av. Vicente Guerrero, No. 62, Col. Buenavista, Alcaldía Cuauhtémoc, C.P. 06350, CDMX.', 'Venta en linea o mostrador', '(55) 5703 – 0900 ', 1, '2024-08-13 16:27:46', '2024-08-13 16:27:46'),
(248, ' FERREMORSE Y EQUIPOS SA DE CV', 'Av. Ignacio Allende 2671, Zona Centro, 91700 Veracruz, Ver.', 'Venta en linea o mostrador', '229 931 1997', 1, '2024-08-13 16:28:22', '2024-08-13 16:28:22'),
(249, 'Arvi del golfo', 'Cedros 17, CD INDUSTRIAL, Bruno Pagliai, 91697 Veracruz, Ver.', 'Venta en linea o mostrador', '229 114 8420', 1, '2024-08-13 16:29:17', '2024-08-13 16:29:17'),
(250, 'Fibremex', 'Parque Tecnológico Innovación Querétaro Lateral de la carretera Estatal 431, km.2+200, Int.28, 76246 Santiago de Querétaro, Qro.', 'Venta en linea o mostrador', '442 220 8046', 1, '2024-08-13 16:53:52', '2024-08-13 16:53:52'),
(251, 'INKMEX S.A. DE C.V.', 'C. Lago Chapala 54, Anáhuac I Secc., Anáhuac I Secc, Miguel Hidalgo, 11320 Ciudad de México, CDMX', 'Venta en linea o mostrador', 'No hay', 1, '2024-08-13 16:54:38', '2024-08-13 16:54:38'),
(252, 'ITASA', 'MONTERREY\r\nJuan de La Barrera 928\r\nObrera, 64010\r\nMonterrey, Nuevo León, México.', 'Venta en linea o mostrador', ' 01 81 8354 6610', 1, '2024-08-13 17:04:03', '2024-08-13 17:04:03'),
(253, 'NETWORKS TIGER', 'NetworkTigers, Inc.\r\n1029 S. Claremont St.\r\nSan Mateo, CA 94402', 'Venta en linea o mostrador', 'No hay', 1, '2024-08-13 17:05:00', '2024-08-13 17:05:00'),
(254, 'Rafma Distribuciones SA de CV', 'Convento de Sta. Cruz 10, Las Margaritasampliacion, 54050 Tlalnepantla, Méx.', 'Venta en linea o mostrador', '55 1659 1278', 1, '2024-08-13 17:05:37', '2024-08-13 17:05:37'),
(255, 'Ferreteria la luna', ' Antropólogos 1B, Apatlaco, Iztapalapa, 09430 Ciudad de México, CDMX', 'Venta en linea', 'No hay', 1, '2024-08-20 15:28:56', '2024-08-20 15:28:56'),
(256, 'Vica refacciones', 'whatsapp', 'Venta en whatsapp', '+52 1 81 1421 9212', 1, '2024-08-20 15:40:32', '2024-08-20 15:40:32'),
(257, 'wilspec', '4801 S Council Rd, Oklahoma City, OK 73179, Estados Unidos', 'Venta en linea', 'no aplica', 1, '2024-08-20 15:47:19', '2024-08-20 15:47:19'),
(258, 'RED HOGAR', 'https://www.redhogar.com.mx/', 'Venta en linea', '3336986003', 1, '2024-08-20 16:02:49', '2024-08-20 16:02:49'),
(259, 'TOTAL HOGAR', 'https://www.totalhogar.com.mx/', 'Venta en linea', '524141871978', 1, '2024-08-20 16:04:22', '2024-08-20 16:04:22'),
(260, 'MPF Mecánica Estrada De México. ', 'DEL PEDREGAL, 89 B, Cofradía de San Miguel, 54715, Cuautitlán Izcalli, Cuautitlán\r\nIzcalli, Estado de México, Méxic', 'Venta en whatsapp', '+52 1 55 6216 0207', 1, '2024-08-23 09:30:47', '2024-08-23 09:30:47'),
(261, 'CDC GROUP', 'C. Versalles 63 Juárez, Cuauhtémoc, 06600 Ciudad de México, CDMX', 'dguevara@cdcmx.com', '55 5705 2760', 1, '2024-08-23 10:05:13', '2024-08-23 10:05:13'),
(262, 'EUROELECTRICA', 'Av. Año de Juárez 253, Col. Granjas San Antonio, Iztapalapa, CDMX', 'Venta en linea', ' (55) 54 45 23 00', 1, '2024-09-07 11:21:53', '2024-09-07 11:21:53'),
(263, 'ING. Amado', 'NO APLICA', 'La señora berenice tiene el número', 'no aplica', 1, '2024-09-11 10:03:56', '2024-09-11 10:03:56'),
(264, 'FERRECASTE', 'Priv. de Av. Universidad #123-1a, Universidad, Trinidad de las Huertas, 68120 Oaxaca de Juárez, Oax', 'Venta en linea o mostrador', ' 951 144 7731', 1, '2024-09-17 13:54:00', '2024-09-17 13:54:00'),
(265, 'AUTOZONE', ' Eduardo Mata 2200, Universidad, Trinidad de las Huertas, 68120 Oaxaca de Juárez, Oax.', 'Venta en linea o mostrador', ' 951 144 8944', 1, '2024-09-17 13:56:50', '2024-09-17 13:56:50'),
(266, 'Wise kitchen', '', '', '', 1, '2024-12-09 15:09:35', '2024-12-09 15:09:35'),
(267, 'DESARROLLADORA Y OPERADORA DE INFRAESTRUCTURA DE OAXACA SAPI DE DV', '', '', '', 1, '2024-12-09 15:38:34', '2024-12-09 15:38:34'),
(269, 'moka', 'moka', 'pinky', '1234123412', 1, '2025-09-30 19:05:34', '2025-09-30 19:05:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Control de Cotizaciones e Insumos'),
(6, 'short_name', 'Sistema CCI'),
(11, 'logo', 'uploads/logo-1688747776.png'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover-1688752047.png'),
(15, 'content', 'Array');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`) VALUES
(1, 'Administrador', NULL, '', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'uploads/avatar-1.png?v=1688747814', NULL, 1, '2021-01-20 14:02:37', '2023-10-11 14:14:04'),
(10, 'Berenice ', NULL, 'Cervantes', 'BereniceC', 'f07cea5a270c83089b29e8831f7e6148', 'uploads/avatar-10.png?v=1697006899', NULL, 1, '2021-11-03 14:21:28', '2025-09-30 22:18:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_meta`
--

CREATE TABLE `user_meta` (
  `user_id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `back_order_list`
--
ALTER TABLE `back_order_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `po_id` (`po_id`),
  ADD KEY `receiving_id` (`receiving_id`);

--
-- Indices de la tabla `bo_items`
--
ALTER TABLE `bo_items`
  ADD KEY `item_id` (`item_id`),
  ADD KEY `bo_id` (`bo_id`);

--
-- Indices de la tabla `company_list`
--
ALTER TABLE `company_list`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `item_list`
--
ALTER TABLE `item_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indices de la tabla `po_items`
--
ALTER TABLE `po_items`
  ADD KEY `po_id` (`po_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `purchase_order_list`
--
ALTER TABLE `purchase_order_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indices de la tabla `receiving_list`
--
ALTER TABLE `receiving_list`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `return_list`
--
ALTER TABLE `return_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indices de la tabla `sales_list`
--
ALTER TABLE `sales_list`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `stock_list`
--
ALTER TABLE `stock_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `supplier_list`
--
ALTER TABLE `supplier_list`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user_meta`
--
ALTER TABLE `user_meta`
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `back_order_list`
--
ALTER TABLE `back_order_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `company_list`
--
ALTER TABLE `company_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `item_list`
--
ALTER TABLE `item_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=878;

--
-- AUTO_INCREMENT de la tabla `purchase_order_list`
--
ALTER TABLE `purchase_order_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `receiving_list`
--
ALTER TABLE `receiving_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `return_list`
--
ALTER TABLE `return_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sales_list`
--
ALTER TABLE `sales_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `stock_list`
--
ALTER TABLE `stock_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT de la tabla `supplier_list`
--
ALTER TABLE `supplier_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=272;

--
-- AUTO_INCREMENT de la tabla `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `back_order_list`
--
ALTER TABLE `back_order_list`
  ADD CONSTRAINT `back_order_list_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `back_order_list_ibfk_2` FOREIGN KEY (`po_id`) REFERENCES `purchase_order_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `back_order_list_ibfk_3` FOREIGN KEY (`receiving_id`) REFERENCES `receiving_list` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `bo_items`
--
ALTER TABLE `bo_items`
  ADD CONSTRAINT `bo_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bo_items_ibfk_2` FOREIGN KEY (`bo_id`) REFERENCES `back_order_list` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `item_list`
--
ALTER TABLE `item_list`
  ADD CONSTRAINT `item_list_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier_list` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `po_items`
--
ALTER TABLE `po_items`
  ADD CONSTRAINT `po_items_ibfk_1` FOREIGN KEY (`po_id`) REFERENCES `purchase_order_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `po_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item_list` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `purchase_order_list`
--
ALTER TABLE `purchase_order_list`
  ADD CONSTRAINT `purchase_order_list_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier_list` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `return_list`
--
ALTER TABLE `return_list`
  ADD CONSTRAINT `return_list_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier_list` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `stock_list`
--
ALTER TABLE `stock_list`
  ADD CONSTRAINT `stock_list_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item_list` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
