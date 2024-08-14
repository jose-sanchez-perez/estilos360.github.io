-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-08-2024 a las 22:06:23
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `estilos_360`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `password` varchar(120) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `token_password` varchar(40) DEFAULT NULL,
  `password_request` tinyint(4) NOT NULL DEFAULT 0,
  `activo` tinyint(4) NOT NULL,
  `fecha_alta` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `usuario`, `password`, `nombre`, `email`, `token_password`, `password_request`, `activo`, `fecha_alta`) VALUES
(1, 'admin', '$2y$10$PsV2HLARjNrFEw.7NJhROe.IyISf2hrwTfLukiSjYkUklAEkw8rRy', 'Administrador', '', NULL, 0, 1, '2024-06-30 17:43:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `activo`) VALUES
(1, 'playeras', 1),
(2, 'pantalones', 1),
(3, 'sudaderas', 1),
(4, 'chamarras', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombres` varchar(80) NOT NULL,
  `apellidos` varchar(80) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `estatus` tinyint(4) NOT NULL,
  `fecha_alta` datetime NOT NULL,
  `fecha_modifica` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombres`, `apellidos`, `email`, `telefono`, `estatus`, `fecha_alta`, `fecha_modifica`, `fecha_baja`) VALUES
(1, 'josa', 'asfs', 'jishd@gmail.com', '2411102832', 1, '2024-08-13 08:35:34', NULL, NULL),
(2, 'josa', 'asfs', 'vane.301291@gmail.com', '2411102832', 1, '2024-08-13 11:16:17', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id` int(11) NOT NULL,
  `id_transaccion` varchar(20) NOT NULL,
  `fecha` datetime NOT NULL,
  `status` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `id_cliente` int(20) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id`, `id_transaccion`, `fecha`, `status`, `email`, `id_cliente`, `total`) VALUES
(1, '66bb93935f515', '2024-08-13 11:10:43', 'completado', 'cliente@example.com', 0, 270.00),
(2, '66bb9482823da', '2024-08-13 11:14:42', 'completado', 'cliente@example.com', 1, 1250.00),
(3, '66bb954b11da2', '2024-08-13 11:18:03', 'completado', 'cliente@example.com', 1, 120.00),
(4, '66bb96e3d9a58', '2024-08-13 11:24:51', 'completado', 'cliente@example.com', 1, 200.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_compra`
--

INSERT INTO `detalle_compra` (`id`, `id_compra`, `id_producto`, `nombre`, `precio`, `cantidad`) VALUES
(1, 1, 6, '\r\nPlayera Con Estampado De Velocímetro, Playera Para Hombre', 150.00, 1),
(2, 1, 4, 'pantalon', 120.00, 1),
(3, 2, 5, 'sudadera', 300.00, 1),
(4, 2, 18, 'Chamarra Rompevientos Casual Y Elegante Para Hombre Con Capucha, Manga Larga Y Cierre De Cremallera', 350.00, 1),
(5, 2, 15, 'Sudadera Para Hombres, Sudadera Con Capucha Casual De Manga Larga', 200.00, 3),
(6, 3, 4, 'pantalon', 120.00, 1),
(7, 4, 9, 'Playera De Cuello Redondo Para Maestras, Playera Casual De Manga Corta Para Primavera Y Verano, Ropa Para Mujer', 200.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellidos` varchar(255) DEFAULT NULL,
  `esta_activo` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `usuario`, `contraseña`, `nombre`, `apellidos`, `esta_activo`) VALUES
(1, 'jose', '$2y$10$tue3MX32nqM7iU0Y9BoSLuqCvRIctZTeIpFhAdY1tCP.zpNVqkBTC', 'josa', 'asfs', 1),
(2, 'jo', '$2y$10$bbzuMotjpAF3slT.7jcIZeKUK/MHouGtuWePQ7lYGbmOHW9yuEc2q', 'josa', 'asfs', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descuento` tinyint(3) NOT NULL DEFAULT 0,
  `categoria` int(11) NOT NULL,
  `activo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `descuento`, `categoria`, `activo`) VALUES
(1, 'playera', 'playera', 300.00, 0, 1, 1),
(2, 'playera', 'playera para mujer', 300.00, 10, 1, 1),
(4, 'pantalon', 'pantalon de mesclilla', 120.00, 0, 2, 1),
(5, 'sudadera', 'sudadera color roja', 300.00, 0, 3, 1),
(6, '\r\nPlayera Con Estampado De Velocímetro, Playera Para Hombre', 'Talla: S \r\nColor: Gris oscuro \r\nMaterial: Poliéster \r\nComposición: 95% Poliéster \r\n', 150.00, 0, 1, 1),
(7, 'Playera Estampada De AEW, Playeras Para Hombre, Playera Casual De Manga Corta Para Verano', 'Talla: L \r\nMaterial: Poliéster \r\nComposición: 100% Poliéster \r\nEstilo: Casual Estilo de cuello: \r\nCuello redondo \r\nTela: Elasticidad ', 200.00, 0, 1, 1),
(8, 'Playera Deportiva Casual Para Mujer - Gráfico Floral, Manga Corta, Playera De Cuello Redondo Con Diseño De Tulipán\r\n', 'Talla: XS \r\nColor: Negro \r\nComposición: 94% Poliéster \r\nEstilo: Casual \r\nEstilo de cuello: Cuello redondo \r\nTela: Elasticidad media ', 200.00, 10, 1, 1),
(9, 'Playera De Cuello Redondo Para Maestras, Playera Casual De Manga Corta Para Primavera Y Verano, Ropa Para Mujer', 'Color: Negro \r\nTalla: M \r\nMaterial: Mezcla de algodón \r\nEstilo: Casual \r\nEstilo de cuello: Cuello redondo Escarpado:', 200.00, 20, 1, 1),
(10, 'Playera Deportiva Básica Para Mujer Con Estampado De GORILA', 'Cuello En V \r\nManga largaopm\r\nColor: Negro \r\nTalla: M \r\nMaterial: Poliéster \r\nComposición: 95% Poliéster \r\na', 250.00, 0, 1, 1),
(11, 'Pantalones Rectos Casuales Para Actividades Al Aire Libre', 'Talla: 28 \r\nColor: Gris claro \r\nMaterial: Nailon \r\nComposición: 88.3% Nailon \r\nEstilo: Casual \r\nTela: Elasticidad media', 250.00, 0, 2, 1),
(12, 'Pantalon Con Bolsillos Múltiples Con Solapa Para Hombres', 'Color: Gris \r\nTalla: 36 \r\nMaterial: Mezcla de algodón \r\nComposición: 97% Algodón \r\nComposición: 3% Elastano ', 500.00, 0, 2, 1),
(13, 'Pantalones De Mezclilla/Jeans/Vaqueros De Diseño Clásico Ajustados ', 'Talla: S \r\nMaterial: Mezcla de algodón \r\nComposición: 65% Algodón \r\nComposición: 35% Poliéster \r\nEstilo: Trabajo', 400.00, 0, 2, 1),
(14, 'Pantalon Vaquero Elástico De Pierna Recta Con Botones Y Estampado Floral Para Mujer\r\n', 'Talla: 5XL \r\nColor: Gris oscuro \r\nMaterial: Mezcla de algodón \r\nEstilo: Vintage \r\nTela: Elasticidad alta \r\nEscarpado: No \r\nEstampado: Floral', 300.00, 0, 2, 1),
(15, 'Sudadera Para Hombres, Sudadera Con Capucha Casual De Manga Larga', 'Talla: L Color: caqui 2 Material: Poliéster Composición: 100% Poliéster Estilo: Casual Estilo de cuello: Capucha Tela: Elasticidad ligera ', 200.00, 10, 3, 1),
(16, 'Sudaderas Con Capucha Para Hombres, Sudadera Con Capucha Casual Para Hombres Con Diseño Gráfico De Fantasma\r\n', 'Color: Gris \r\nTalla: M \r\nMaterial: Poliéster \r\nComposición: 100% Poliéster \r\nEstilo de cuello: Cuello redondo \r\nTela: Elasticidad ligera ', 150.00, 0, 3, 1),
(17, 'Sudadera Con Capucha Estampado Floral, Sudadera Casual Con Cordón Para Invierno Y Otoño, Ropa De Mujer\r\n', 'Talla: S \r\nColor: Color Rosa \r\nMaterial: Poliéster \r\nComposición: 100% Poliéster \r\nEstilo: Casual \r\nEstilo de cuello: Capucha \r\nEscarpado: No \r\nEstampado: Patrones geométricos', 350.00, 0, 3, 1),
(18, 'Chamarra Rompevientos Casual Y Elegante Para Hombre Con Capucha, Manga Larga Y Cierre De Cremallera', 'Talla: L \r\nColor: verde \r\nMaterial: Poliéster \r\nComposición: 100% Poliéster \r\nEstilo: Casual \r\nEstilo de cuello: Capucha \r\nTela: Sin elasticidad', 350.00, 10, 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `password` varchar(120) NOT NULL,
  `activacion` int(11) NOT NULL DEFAULT 0,
  `token` varchar(40) NOT NULL,
  `token_password` varchar(40) DEFAULT NULL,
  `password_request` int(11) NOT NULL DEFAULT 0,
  `id_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `activacion`, `token`, `token_password`, `password_request`, `id_cliente`) VALUES
(1, 'jos', '$2y$10$w067YgZV0FbhumiemSCVZ.6KE2aSGgIFhgoZGbmfzey.YtS0e.NDa', 0, '3bf5c6db69610d4f8975cd4bc0b3f750', NULL, 0, 1),
(2, 'sa', '$2y$10$dWEh43HRRnrXReihss6Afe0EnLlE2btIgtrwgb7uHC5ofg96eT0QO', 0, '1eb00d1673c8cd657256b76c48fe9021', NULL, 0, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
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
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
