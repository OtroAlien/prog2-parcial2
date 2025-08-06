-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-08-2025 a las 00:16:00
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
-- Base de datos: `progra2_parcial2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `carrito_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_productos`
--

CREATE TABLE `carrito_productos` (
  `carrito_producto_id` int(10) UNSIGNED NOT NULL,
  `carrito_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `cantidad` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `categoria_id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`categoria_id`, `nombre`) VALUES
(1, 'Skincare'),
(2, 'Maquillaje'),
(3, 'Cabello'),
(4, 'Sin categoría'),
(1014, 'test');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descuentos`
--

CREATE TABLE `descuentos` (
  `descuento_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `valor` int(11) NOT NULL CHECK (`valor` between 0 and 100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `descuentos`
--

INSERT INTO `descuentos` (`descuento_id`, `nombre`, `valor`) VALUES
(2, '', 0),
(3, '', 15),
(5, '', 40),
(8, '', 20),
(14, '', 1),
(15, 'Sin descuento', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `favorito_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `orden_id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `fecha_orden` datetime NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes`
--

INSERT INTO `ordenes` (`orden_id`, `user_id`, `fecha_orden`, `total`, `estado`) VALUES
(1, 1, '2025-06-01 14:30:00', 70000.00, 'completado'),
(2, 2, '2025-06-02 10:15:00', 53000.00, 'pendiente'),
(3, 9, '2025-06-03 18:45:00', 88000.00, 'enviado'),
(4, 10, '2025-06-04 12:00:00', 60000.00, 'completado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_productos`
--

CREATE TABLE `ordenes_productos` (
  `orden_productos_id` int(11) NOT NULL,
  `orden_id` int(11) NOT NULL,
  `producto_id` int(10) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes_productos`
--

INSERT INTO `ordenes_productos` (`orden_productos_id`, `orden_id`, `producto_id`, `cantidad`, `precio`) VALUES
(1, 1, 2, 1, 19000.00),
(2, 1, 5, 2, 24000.00),
(3, 2, 9, 1, 18000.00),
(4, 2, 10, 1, 12000.00),
(5, 2, 11, 1, 22000.00),
(6, 3, 3, 1, 32000.00),
(7, 3, 7, 1, 42000.00),
(8, 3, 4, 1, 14000.00),
(9, 4, 14, 1, 30000.00),
(10, 4, 13, 1, 25000.00),
(11, 4, 6, 1, 5000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(256) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(7,2) UNSIGNED NOT NULL,
  `imagen` varchar(256) NOT NULL,
  `stock` int(11) UNSIGNED NOT NULL,
  `lanzamiento` date NOT NULL,
  `contenido` enum('15','30','60') NOT NULL,
  `waterproof` tinyint(1) UNSIGNED NOT NULL,
  `vegano` tinyint(1) UNSIGNED NOT NULL,
  `productoDestacado` tinyint(1) UNSIGNED NOT NULL,
  `categoria_id` int(10) UNSIGNED NOT NULL,
  `descuento_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`product_id`, `nombre`, `descripcion`, `precio`, `imagen`, `stock`, `lanzamiento`, `contenido`, `waterproof`, `vegano`, `productoDestacado`, `categoria_id`, `descuento_id`) VALUES
(1, 'Snail Bee High Content Essence', 'Esencia altamente concentrada que contiene mucina de caracol y veneno de abeja, conocidos por sus propiedades regenerativas y calmantes. Ayuda a mejorar la textura de la piel, aporta hidratación y calma la piel irritada.', 28000.00, 'productos/skincare-1.jpeg', 51, '2020-05-12', '', 0, 1, 0, 3, 2),
(2, 'Green Tea Fresh Toner ', 'Tónico refrescante formulado con extracto de té verde orgánico de Jeju. Ayuda a equilibrar la piel, controlar la producción de sebo y proporcionar una hidratación ligera. Ideal para pieles grasas y mixtas.', 19000.00, 'productos/skincare-2.jpeg', 80, '2019-08-20', '30', 1, 1, 0, 1, 2),
(3, 'Galactomyces 95 Tone Balancing Essence', 'Esencia a base de fermento de galactomyces al 95%, conocido por sus propiedades iluminadoras y equilibrantes. Ayuda a unificar el tono de la piel, minimizar los poros y mejorar la textura.', 32000.00, 'productos/skincare-3.jpeg', 30, '2021-03-10', '60', 0, 0, 1, 1, 5),
(4, 'Aloe Propolis Soothing Gel', 'Gel calmante formulado con un 80% de extracto de aloe vera y 10% de extracto de propóleo. Proporciona una hidratación profunda, calma la piel irritada y ayuda a mejorar la barrera cutánea.', 21000.00, 'productos/skincare-4.jpeg', 60, '2018-12-05', '60', 1, 1, 0, 1, 2),
(5, 'Rice Mask Wash Off', 'Mascarilla exfoliante a base de arroz que ayuda a eliminar las células muertas de la piel, suavizar la textura y aportar luminosidad. Ideal para pieles opacas y con falta de luminosidad.', 24000.00, 'productos/skincare-5.jpeg', 40, '2017-06-30', '30', 0, 1, 0, 1, 3),
(6, 'Vitamin C Serum', 'Serum facial antioxidante formulado con vitamina C pura al 10%. Ayuda a iluminar la piel, reducir la hiperpigmentación y proteger contra el daño ambiental. Ideal para pieles apagadas y con signos de envejecimiento.', 35000.00, 'productos/skincare-6.jpeg', 25, '2021-10-15', '', 1, 1, 0, 1, 8),
(7, 'Hyaluronic Acid Moisturizer', 'Crema hidratante intensiva con ácido hialurónico para una hidratación profunda y duradera. Ayuda a rellenar la piel, suavizar las líneas finas y restaurar la barrera cutánea. Ideal para pieles deshidratadas y maduras.', 42000.00, 'productos/skincare-7.jpeg', 20, '2022-04-05', '60', 0, 1, 0, 1, 2),
(8, 'Magic Cover Fit Cushion Foundation', 'Base de maquillaje en formato cushion con cobertura perfecta y acabado mate. Formulada con ingredientes hidratantes para mantener la piel suave y con un aspecto fresco durante todo el día.', 35000.00, 'productos/crema-hidratante-2.jpeg', 70, '2021-09-15', '30', 1, 0, 0, 2, 8),
(9, 'Velvet Lip Tint', 'Tinte labial de textura aterciopelada que proporciona un color intenso y duradero. Su fórmula ligera se desliza suavemente sobre los labios, dejándolos con un acabado suave y sedoso.', 18000.00, 'productos/makeup-2.jpeg', 90, '2020-03-20', '', 0, 1, 0, 2, 3),
(10, 'Eyebrow Pencil', 'Lápiz de cejas retráctil que define y rellena las cejas de forma natural. Su fórmula de larga duración es resistente al agua y al sudor, manteniendo las cejas perfectas durante todo el día.', 12000.00, 'productos/makeup-3.jpeg', 80, '2019-07-10', '60', 1, 1, 0, 2, 5),
(11, 'Waterproof Mascara', 'Máscara de pestañas resistente al agua que proporciona volumen, longitud y definición. Su fórmula de secado rápido evita que se corra o se desvanezca, manteniendo las pestañas impecables todo el día.', 22000.00, 'productos/makeup-4.jpeg', 60, '2018-11-05', '30', 0, 0, 0, 2, 2),
(12, 'Highlighter Palette', 'Paleta de iluminadores con tonos variados para resaltar y definir los rasgos faciales. Su fórmula pigmentada y fácil de difuminar proporciona un brillo natural y luminoso a la piel.', 28000.00, 'productos/makeup-5.jpeg', 50, '2022-02-28', '', 1, 1, 0, 2, 3),
(13, 'Matte Liquid Lipstick', 'Labial líquido de acabado mate y larga duración. Formulado con pigmentos intensos que proporcionan un color rico y una cobertura completa. Disponible en una variedad de tonos vibrantes.', 25000.00, 'productos/makeup-6.jpeg', 70, '2023-01-10', '60', 0, 1, 0, 2, 2),
(14, 'Eyeshadow Palette', 'Paleta de sombras de ojos con una gama de tonos mate y brillantes. Su fórmula altamente pigmentada se mezcla fácilmente y proporciona una cobertura intensa y duradera.', 30000.00, 'productos/makeup-7.jpeg', 60, '2023-07-20', '30', 1, 0, 0, 2, 2),
(15, 'Repairing Argan Oil Hair Mask', 'Mascarilla capilar reparadora enriquecida con aceite de argán para restaurar la salud y la vitalidad del cabello. Ayuda a reparar el daño causado por el calor, la coloración y los tratamientos químicos, dejando el cabello suave, sedoso y manejable.', 25000.00, 'productos/hair-1.jpeg', 40, '2020-11-10', '', 0, 0, 0, 3, 3),
(16, 'Curl Defining Cream', 'Crema definidora de rizos que hidrata, suaviza y define los rizos, dejándolos flexibles y con un aspecto natural. Su fórmula ligera no deja residuos pegajosos y controla el encrespamiento, manteniendo los rizos definidos durante todo el día.', 18000.00, 'productos/hair-2.jpeg', 60, '2019-05-25', '60', 1, 1, 0, 3, 8),
(17, 'Hydrating Shampoo', 'Champú hidratante formulado con ingredientes naturales para limpiar suavemente el cabello y el cuero cabelludo, sin eliminar los aceites naturales. Ayuda a restaurar la hidratación y a mejorar la salud del cabello, dejándolo suave, brillante y manejable.', 15000.00, 'productos/hair-8.jpeg', 80, '2018-08-15', '30', 0, 1, 0, 3, 2),
(18, 'Heat Protectant Spray', 'Spray protector térmico que protege el cabello del daño causado por el calor de herramientas de peinado como secadores, planchas y rizadores. Formulado con ingredientes nutritivos que ayudan a fortalecer y proteger el cabello, dejándolo suave y brillante.', 20000.00, 'productos/hair-4.jpeg', 50, '2021-03-30', '', 1, 1, 0, 3, 5),
(19, 'Hair Growth Serum', 'Serum capilar estimulante del crecimiento del cabello que promueve el crecimiento saludable y fortalece los folículos capilares. Formulado con ingredientes activos que nutren el cuero cabelludo y reducen la caída del cabello, dejándolo más grueso y voluminoso.', 30000.00, 'productos/hair-5.jpeg', 30, '2022-06-20', '60', 0, 0, 0, 3, 5),
(20, 'Moisturizing Hair Mask', 'Mascarilla capilar hidratante enriquecida con aceites naturales para restaurar la humedad y la suavidad del cabello. Ayuda a reparar el cabello seco y dañado, dejándolo sedoso, brillante y más manejable.', 28000.00, 'productos/hair-6.jpeg', 35, '2023-02-12', '30', 1, 0, 0, 3, 5),
(64, 'Anti-Frizz Hair Serum', 'Serum capilar anti-frizz que controla el encrespamiento y suaviza el cabello. Formulado con ingredientes hidratantes y protectores para un cabello suave, brillante y sin enredos.', 24000.00, 'productos/hair-7.jpeg', 45, '2023-09-25', '', 0, 1, 0, 3, 2),
(68, 'Anti-Frizz Hair Serum ultra secado rapido', 'Serum capilar anti-frizz que controla el encrespamiento y suaviza el cabello. Formulado con ingredientes hidratantes y protectores para un cabello suave, brillante y sin enredos.', 25000.00, 'productos/hair-7.jpeg', 45, '2023-09-25', '', 0, 1, 0, 4, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_subcategorias`
--

CREATE TABLE `productos_subcategorias` (
  `producto_subcategoria_id` int(10) UNSIGNED NOT NULL,
  `producto_id` int(10) UNSIGNED NOT NULL,
  `subcategoria_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_subcategorias`
--

INSERT INTO `productos_subcategorias` (`producto_subcategoria_id`, `producto_id`, `subcategoria_id`) VALUES
(4, 3, 1),
(5, 4, 5),
(6, 4, 2),
(7, 5, 1),
(8, 6, 1),
(9, 7, 2),
(10, 7, 6),
(11, 8, 7),
(12, 8, 8),
(13, 9, 9),
(14, 9, 10),
(15, 10, 11),
(16, 10, 12),
(17, 12, 13),
(18, 12, 14),
(19, 13, 15),
(21, 14, 17),
(22, 14, 18),
(23, 15, 19),
(24, 15, 20),
(25, 16, 21),
(26, 16, 22),
(27, 17, 1),
(28, 18, 1),
(29, 19, 23),
(30, 19, 24),
(31, 20, 1),
(32, 11, 37),
(33, 1, 37),
(34, 1, 25),
(35, 64, 4),
(36, 64, 5),
(37, 68, 2),
(38, 68, 9),
(39, 2, 37),
(40, 2, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subcategorias`
--

CREATE TABLE `subcategorias` (
  `subcategoria_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subcategorias`
--

INSERT INTO `subcategorias` (`subcategoria_id`, `name`) VALUES
(1, 'Todo tipo'),
(2, 'Piel seca'),
(3, 'Piel grasa'),
(4, 'Piel mixta'),
(5, 'Piel sensible'),
(6, 'Piel madura'),
(7, 'Natural Beige'),
(8, 'Light Beige'),
(9, 'Red Velvet'),
(10, 'Rose Pink'),
(11, 'Dark Brown'),
(12, 'Taupe'),
(13, 'Champagne'),
(14, 'Rose Gold'),
(15, 'Nude'),
(17, 'Warm Neutrals'),
(18, 'Smokey'),
(19, 'Dañado'),
(20, 'Seco'),
(21, 'Rizado'),
(22, 'Ondulado'),
(23, 'Cabello fino'),
(24, 'Cabello teñido'),
(25, 'Cabello decolorado'),
(37, 'Berry');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_address`
--

CREATE TABLE `user_address` (
  `address_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `calle` varchar(256) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `localidad` varchar(100) NOT NULL,
  `codigo_postal` varchar(20) NOT NULL,
  `pais` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `altura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `password_hash` varchar(256) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_address` int(10) UNSIGNED DEFAULT NULL,
  `rol` enum('superadmin','admin','usuario') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`user_id`, `username`, `nombre_completo`, `password_hash`, `email`, `id_address`, `rol`) VALUES
(1, 'jdoe', 'John Doe', 'hashed_password_1', 'jdoe@example.com', NULL, 'usuario'),
(2, 'asmith', 'Alice Smith', 'hashed_password_2', 'asmith@example.com', NULL, 'usuario'),
(3, 'bking', 'Bob King', 'hashed_password_3', 'bking@example.com', NULL, 'admin'),
(7, 'test', '', '$2y$10$rj0s5o9KP2P7hbLxFGMKC.v513rK0IufzC2JFkjVBqy2zwJ5OfdDu', 'test@example.com', NULL, 'admin'),
(8, 'test2', '', '$2y$10$2MepSOovvbRqjgOPKYAMde4V0vDhvVqRebCWAuFOSpwug1PtZWDeO', 'test2@example.com', NULL, 'usuario'),
(9, 'Nombre de usuario', 'Nombre Completo', '$2y$10$P/eED211zBgJ5.n7JDNw2OJnZI5oR1.9HRIJrJWWb8eF3FKG2jJvy', 'email@example.com', NULL, 'usuario'),
(10, 'Registro test', 'test 3', '$2y$10$7q262W9U.t8vNRQk.Bfz2eybJY9QREfgMJ0L3fTvFP76LXW9swbZ.', 'test3@example.com', NULL, 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`carrito_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `carrito_productos`
--
ALTER TABLE `carrito_productos`
  ADD PRIMARY KEY (`carrito_producto_id`),
  ADD KEY `carrito_id` (`carrito_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`categoria_id`);

--
-- Indices de la tabla `descuentos`
--
ALTER TABLE `descuentos`
  ADD PRIMARY KEY (`descuento_id`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`favorito_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD PRIMARY KEY (`orden_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `ordenes_productos`
--
ALTER TABLE `ordenes_productos`
  ADD PRIMARY KEY (`orden_productos_id`),
  ADD KEY `orden_id` (`orden_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `fk_descuento` (`descuento_id`);

--
-- Indices de la tabla `productos_subcategorias`
--
ALTER TABLE `productos_subcategorias`
  ADD PRIMARY KEY (`producto_subcategoria_id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `subcategoria_id` (`subcategoria_id`);

--
-- Indices de la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  ADD PRIMARY KEY (`subcategoria_id`);

--
-- Indices de la tabla `user_address`
--
ALTER TABLE `user_address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `unique_username` (`username`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `carrito_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `carrito_productos`
--
ALTER TABLE `carrito_productos`
  MODIFY `carrito_producto_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `categoria_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1017;

--
-- AUTO_INCREMENT de la tabla `descuentos`
--
ALTER TABLE `descuentos`
  MODIFY `descuento_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  MODIFY `orden_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ordenes_productos`
--
ALTER TABLE `ordenes_productos`
  MODIFY `orden_productos_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `product_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT de la tabla `productos_subcategorias`
--
ALTER TABLE `productos_subcategorias`
  MODIFY `producto_subcategoria_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  MODIFY `subcategoria_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `user_address`
--
ALTER TABLE `user_address`
  MODIFY `address_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`user_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `carrito_productos`
--
ALTER TABLE `carrito_productos`
  ADD CONSTRAINT `carrito_productos_ibfk_1` FOREIGN KEY (`carrito_id`) REFERENCES `carrito` (`carrito_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carrito_productos_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `productos` (`product_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `productos` (`product_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD CONSTRAINT `ordenes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`user_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ordenes_productos`
--
ALTER TABLE `ordenes_productos`
  ADD CONSTRAINT `ordenes_productos_ibfk_1` FOREIGN KEY (`orden_id`) REFERENCES `ordenes` (`orden_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ordenes_productos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`product_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_descuento` FOREIGN KEY (`descuento_id`) REFERENCES `descuentos` (`descuento_id`),
  ADD CONSTRAINT `fk_productos_categorias` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`categoria_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos_subcategorias`
--
ALTER TABLE `productos_subcategorias`
  ADD CONSTRAINT `productos_subcategorias_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productos_subcategorias_ibfk_2` FOREIGN KEY (`subcategoria_id`) REFERENCES `subcategorias` (`subcategoria_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `user_address`
--
ALTER TABLE `user_address`
  ADD CONSTRAINT `user_address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`user_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
