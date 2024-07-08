-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-07-2024 a las 22:41:13
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
-- Base de datos: `restaurant`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `idMesa` int(11) NOT NULL,
  `puntuacion` int(11) NOT NULL,
  `comentario` text DEFAULT NULL,
  `eliminado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`id`, `idPedido`, `idMesa`, `puntuacion`, `comentario`, `eliminado`) VALUES
(4, 4, 29, 10, 'me gusto', 0),
(5, 4, 29, 10, 'me gusto', 0),
(6, 4, 29, 10, 'me gusto', 0),
(7, 4, 29, 10, 'me gusto', 0),
(8, 4, 29, 10, 'me gusto', 0),
(9, 4, 29, 2, 'no me gusto', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `request_user` varchar(40) NOT NULL,
  `http_method` varchar(10) NOT NULL,
  `request_content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `eliminado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `IdSector` int(11) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `estadoMesa` varchar(50) DEFAULT 'DISPONIBLE',
  `veces_solicitada` int(11) NOT NULL DEFAULT 0,
  `eliminado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `IdSector`, `capacidad`, `estadoMesa`, `veces_solicitada`, `eliminado`) VALUES
(29, 1, 2, 'ATENDIDA', 4, 0),
(30, 2, 5, 'ATENDIDA', 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operaciones`
--

CREATE TABLE `operaciones` (
  `id` int(11) NOT NULL,
  `sector` varchar(255) NOT NULL,
  `empleado` varchar(255) NOT NULL,
  `contador` int(11) DEFAULT 0,
  `eliminado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `IdUsuario` int(11) NOT NULL,
  `idMesa` int(11) NOT NULL,
  `precioTotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cobrado` tinyint(1) NOT NULL,
  `momentoCobrado` datetime DEFAULT NULL,
  `momentoPedido` datetime NOT NULL DEFAULT current_timestamp(),
  `RutaImagen` text DEFAULT NULL,
  `eliminado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `IdUsuario`, `idMesa`, `precioTotal`, `cobrado`, `momentoCobrado`, `momentoPedido`, `RutaImagen`, `eliminado`) VALUES
(4, 3, 29, 0.00, 1, '2024-06-30 21:00:46', '2024-06-29 22:16:54', './Fotos/2024/_4.jpg', 0),
(5, 3, 29, 0.00, 0, '0000-00-00 00:00:00', '2024-06-29 22:18:07', NULL, 0),
(6, 3, 29, 0.00, 0, NULL, '2024-06-30 16:19:04', NULL, 0),
(7, 3, 29, 0.00, 0, NULL, '2024-06-30 16:20:31', NULL, 0),
(8, 4, 30, 150.00, 0, NULL, '2024-06-30 17:19:25', NULL, 0),
(9, 4, 30, 0.00, 0, NULL, '2024-07-02 01:27:41', './Fotos/2024/_9.jpg', 0),
(10, 4, 29, 50.00, 0, NULL, '2024-07-02 04:08:31', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_productos`
--

CREATE TABLE `pedido_productos` (
  `id` int(11) NOT NULL,
  `idPedido` int(11) DEFAULT NULL,
  `idProducto` int(11) DEFAULT NULL,
  `tiempoDePreparacion` int(11) NOT NULL,
  `momentoEntregado` datetime DEFAULT NULL,
  `estado` varchar(77) NOT NULL DEFAULT 'PENDIENTE',
  `eliminado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_productos`
--

INSERT INTO `pedido_productos` (`id`, `idPedido`, `idProducto`, `tiempoDePreparacion`, `momentoEntregado`, `estado`, `eliminado`) VALUES
(1, 4, 2, 32, NULL, 'COBRADO', 0),
(2, 4, 2, 0, '2024-06-30 05:40:16', 'COBRADO', 0),
(3, 4, 2, 0, NULL, 'COBRADO', 0),
(4, 4, 2, 0, NULL, 'COBRADO', 0),
(5, 8, 2, 0, NULL, 'PENDIENTE', 0),
(6, 8, 2, 0, NULL, 'PENDIENTE', 0),
(7, 8, 2, 15, NULL, 'EN PREPARACION', 0),
(12, 10, 2, 0, NULL, 'PENDIENTE', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `idTipo` int(11) NOT NULL,
  `veces_solicitado` int(11) NOT NULL DEFAULT 0,
  `eliminado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `idTipo`, `veces_solicitado`, `eliminado`) VALUES
(2, 'perro caliente', 50.00, 1, 3, 0),
(3, 'daikiri', 50.00, 4, 0, 0),
(4, 'milanesa', 50.00, 1, 0, 0),
(5, 'corona', 50.00, 3, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `eliminado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `rol`, `eliminado`) VALUES
(1, 'Socio', 0),
(2, 'Cocinero', 0),
(3, 'Mozo', 0),
(4, 'Cervezero', 0),
(5, 'Bartender', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sectores`
--

CREATE TABLE `sectores` (
  `id` int(11) NOT NULL,
  `sector` varchar(50) NOT NULL,
  `eliminado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sectores`
--

INSERT INTO `sectores` (`id`, `sector`, `eliminado`) VALUES
(1, 'cocina', 0),
(2, 'patio trasero', 0),
(3, 'barra', 0),
(4, 'candybar', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos`
--

CREATE TABLE `tipos` (
  `Id` int(11) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `idsector` int(11) DEFAULT NULL,
  `eliminado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos`
--

INSERT INTO `tipos` (`Id`, `tipo`, `idsector`, `eliminado`) VALUES
(1, 'Comida', 1, 0),
(2, 'Postre', 4, 0),
(3, 'Bebida', 3, 0),
(4, 'Trago', 3, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `rol` int(11) NOT NULL,
  `token` text NOT NULL,
  `creado_en` text NOT NULL,
  `vence_el` text NOT NULL,
  `eliminado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tokens`
--

INSERT INTO `tokens` (`id`, `usuario`, `rol`, `token`, `creado_en`, `vence_el`, `eliminado`) VALUES
(13, 'moza', 3, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MTk3NzcwNDQsImV4cCI6MTcxOTgzNzA0NCwiYXVkIjoiNTIyNjY2Njk5OWYyYWRiNTEyNGM1ODdkYjk1NTdiNzZjMDRlNDhkZiIsImRhdGEiOnsiaWQiOjQsInVzdWFyaW8iOiJtb3phIiwicm9sIjozLCJzZWN0b3IiOjJ9LCJhcHAiOiJDb21hbmRhIn0._Pb3_TkwGXF_GVk4yAV_KgdHXa45_RyJaYysV5T3WdE', '1719777044', '1719837044', 0),
(15, 'cocinero', 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MTk3NzcxNzAsImV4cCI6MTcxOTgzNzE3MCwiYXVkIjoiNTIyNjY2Njk5OWYyYWRiNTEyNGM1ODdkYjk1NTdiNzZjMDRlNDhkZiIsImRhdGEiOnsiaWQiOjYsInVzdWFyaW8iOiJjb2NpbmVybyIsInJvbCI6Miwic2VjdG9yIjoxfSwiYXBwIjoiQ29tYW5kYSJ9.jzE0oZAaUtiGrZRTrFb6i0kNyjW1KFTXu8aS9c5KWVg', '1719777170', '1719837170', 0),
(16, 'socio', 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MTk3Nzc0OTEsImV4cCI6MTcxOTgzNzQ5MSwiYXVkIjoiNTIyNjY2Njk5OWYyYWRiNTEyNGM1ODdkYjk1NTdiNzZjMDRlNDhkZiIsImRhdGEiOnsiaWQiOjUsInVzdWFyaW8iOiJzb2NpbyIsInJvbCI6MSwic2VjdG9yIjo0fSwiYXBwIjoiQ29tYW5kYSJ9.QeNFxhQjiMH07FdFNLHYnfQDEn6LrpbXfs3suV3EmPQ', '1719777491', '1719837491', 0),
(17, 'socio', 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MTk4NzY3MjksImV4cCI6MTcxOTkzNjcyOSwiYXVkIjoiNTIyNjY2Njk5OWYyYWRiNTEyNGM1ODdkYjk1NTdiNzZjMDRlNDhkZiIsImRhdGEiOnsiaWQiOjUsInVzdWFyaW8iOiJzb2NpbyIsInJvbCI6MSwic2VjdG9yIjo0fSwiYXBwIjoiQ29tYW5kYSJ9.FUBuIP6ieLJuecXKyIxn0NX4atlV9LyU9N6KHR8cxXg', '1719876729', '1719936729', 0),
(18, 'moza', 3, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MTk4ODYwNzksImV4cCI6MTcxOTk0NjA3OSwiYXVkIjoiNTIyNjY2Njk5OWYyYWRiNTEyNGM1ODdkYjk1NTdiNzZjMDRlNDhkZiIsImRhdGEiOnsiaWQiOjQsInVzdWFyaW8iOiJtb3phIiwicm9sIjozLCJzZWN0b3IiOjJ9LCJhcHAiOiJDb21hbmRhIn0.hnUU48xE3UH_V7P5ouJNjOABZsrzPnkA6zGU0o2ft8c', '1719886079', '1719946079', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `fechaBaja` datetime DEFAULT NULL,
  `rol` int(255) DEFAULT NULL,
  `sector` int(255) DEFAULT NULL,
  `eliminado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `clave`, `nombre`, `fechaBaja`, `rol`, `sector`, `eliminado`) VALUES
(3, 'kassadin2', 'kassadin1232', 'Franco2', NULL, 2, 1, 0),
(4, 'moza', 'moza', 'Moza', NULL, 3, 2, 0),
(5, 'socio', 'socio', 'Socio', NULL, 1, 4, 0),
(6, 'cocinero', 'cocinero', 'Cocinero', NULL, 2, 1, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idPedido` (`idPedido`),
  ADD KEY `idMesa` (`idMesa`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IdSector` (`IdSector`);

--
-- Indices de la tabla `operaciones`
--
ALTER TABLE `operaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sector` (`sector`,`empleado`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idMesa` (`idMesa`),
  ADD KEY `IdUsuario` (`IdUsuario`);

--
-- Indices de la tabla `pedido_productos`
--
ALTER TABLE `pedido_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idPedido` (`idPedido`),
  ADD KEY `idProducto` (`idProducto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idTipo` (`idTipo`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sectores`
--
ALTER TABLE `sectores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos`
--
ALTER TABLE `tipos`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_tipos_sectores` (`idsector`);

--
-- Indices de la tabla `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol` (`rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol` (`rol`),
  ADD KEY `sector` (`sector`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `operaciones`
--
ALTER TABLE `operaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `pedido_productos`
--
ALTER TABLE `pedido_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `sectores`
--
ALTER TABLE `sectores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipos`
--
ALTER TABLE `tipos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD CONSTRAINT `encuestas_ibfk_1` FOREIGN KEY (`idPedido`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `encuestas_ibfk_2` FOREIGN KEY (`idMesa`) REFERENCES `mesas` (`id`);

--
-- Filtros para la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD CONSTRAINT `mesas_ibfk_1` FOREIGN KEY (`IdSector`) REFERENCES `sectores` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`idMesa`) REFERENCES `mesas` (`id`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido_productos`
--
ALTER TABLE `pedido_productos`
  ADD CONSTRAINT `pedido_productos_ibfk_1` FOREIGN KEY (`idPedido`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedido_productos_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`idTipo`) REFERENCES `tipos` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tipos`
--
ALTER TABLE `tipos`
  ADD CONSTRAINT `fk_tipos_sectores` FOREIGN KEY (`idsector`) REFERENCES `sectores` (`id`);

--
-- Filtros para la tabla `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`sector`) REFERENCES `sectores` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
