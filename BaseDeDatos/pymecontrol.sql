-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-06-2025 a las 15:43:18
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
-- Base de datos: `pymecontrol`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` varchar(9) NOT NULL,
  `nombre_cliente` varchar(100) NOT NULL,
  `email_cliente` varchar(100) NOT NULL,
  `telefono_cliente` varchar(20) NOT NULL,
  `id_empresa_cliente` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre_cliente`, `email_cliente`, `telefono_cliente`, `id_empresa_cliente`) VALUES
('67890123F', 'Sergio Ruiz', 'sergio.ruiz@yahoo.es', '644567890', 'A11223344'),
('78901234G', 'Iván Delgado', 'ivan.delgado@icloud.com', '611234567', 'A11223344');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_factura`
--

CREATE TABLE `detalles_factura` (
  `id_detalle` int(11) NOT NULL,
  `cantidad_detalle` int(11) NOT NULL,
  `precio_por_unidad_detalle` decimal(10,2) NOT NULL,
  `id_factura_detalle` int(11) NOT NULL,
  `id_producto_detalle` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_factura`
--

INSERT INTO `detalles_factura` (`id_detalle`, `cantidad_detalle`, `precio_por_unidad_detalle`, `id_factura_detalle`, `id_producto_detalle`) VALUES
(28, 1, 89.50, 25, 13),
(30, 20, 89.50, 27, 13),
(31, 20, 24.90, 28, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` varchar(9) NOT NULL,
  `nombre_empleado` varchar(100) NOT NULL,
  `email_empleado` varchar(100) NOT NULL,
  `contraseña_empleado` varchar(255) NOT NULL,
  `rol` varchar(10) NOT NULL DEFAULT 'empleado',
  `id_empresa_empleado` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `nombre_empleado`, `email_empleado`, `contraseña_empleado`, `rol`, `id_empresa_empleado`) VALUES
('34567890C', 'Lucía Moreno', 'lucia_moreno@informaticatotal.es', '1234', 'empleado', 'A11223344'),
('45678901D', 'David Fernández', 'david_fernandez@informaticatotal.es', '1234', 'empleado', 'A11223344');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id_empresa` varchar(9) NOT NULL,
  `nombre_empresa` varchar(150) NOT NULL,
  `email_empresa` varchar(100) NOT NULL,
  `contraseña_empresa` varchar(255) NOT NULL,
  `telefono_empresa` varchar(20) NOT NULL,
  `direccion_empresa` varchar(200) NOT NULL,
  `rol` varchar(10) NOT NULL DEFAULT 'empresa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id_empresa`, `nombre_empresa`, `email_empresa`, `contraseña_empresa`, `telefono_empresa`, `direccion_empresa`, `rol`) VALUES
('A11223344', 'Informatica Total S.L.', 'info@informaticatotal.es', '1234', '912345678', 'Calle Tecnología 42, 28001 Madrid', 'empresa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id_factura` int(11) NOT NULL,
  `fecha_factura` date NOT NULL DEFAULT current_timestamp(),
  `total_factura` decimal(10,2) NOT NULL,
  `id_cliente_factura` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id_factura`, `fecha_factura`, `total_factura`, `id_cliente_factura`) VALUES
(25, '2025-06-09', 89.50, '78901234G'),
(27, '2025-06-12', 1790.00, '67890123F'),
(28, '2025-06-12', 498.00, '78901234G');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `precio_producto` decimal(10,2) NOT NULL,
  `stock_producto` int(11) NOT NULL,
  `id_empresa_producto` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `precio_producto`, `stock_producto`, `id_empresa_producto`) VALUES
(13, 'Teclado mecánico Logitech', 89.50, 29, 'A11223344'),
(14, 'Ratón inalámbrico HP', 24.90, 130, 'A11223344');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `email_cliente` (`email_cliente`,`telefono_cliente`),
  ADD KEY `id_empresa_cliente` (`id_empresa_cliente`);

--
-- Indices de la tabla `detalles_factura`
--
ALTER TABLE `detalles_factura`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_factura_detalle` (`id_factura_detalle`),
  ADD KEY `id_producto_detalle` (`id_producto_detalle`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`),
  ADD UNIQUE KEY `email_empleado` (`email_empleado`),
  ADD KEY `id_empresa_empleado` (`id_empresa_empleado`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id_empresa`),
  ADD UNIQUE KEY `email_empresa` (`email_empresa`),
  ADD UNIQUE KEY `telefono_empresa` (`telefono_empresa`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_cliente_factura` (`id_cliente_factura`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_empresa_producto` (`id_empresa_producto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalles_factura`
--
ALTER TABLE `detalles_factura`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `id_empresa_cliente` FOREIGN KEY (`id_empresa_cliente`) REFERENCES `empresas` (`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_factura`
--
ALTER TABLE `detalles_factura`
  ADD CONSTRAINT `id_factura_detalle` FOREIGN KEY (`id_factura_detalle`) REFERENCES `facturas` (`id_factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_producto_detalle` FOREIGN KEY (`id_producto_detalle`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `id_empresa_empleado` FOREIGN KEY (`id_empresa_empleado`) REFERENCES `empresas` (`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `id_cliente_factura` FOREIGN KEY (`id_cliente_factura`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `id_empresa_producto` FOREIGN KEY (`id_empresa_producto`) REFERENCES `empresas` (`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
