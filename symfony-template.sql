-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-12-2022 a las 22:25:31
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `symfony-template`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20221025224733', '2022-10-26 00:47:44', 51),
('DoctrineMigrations\\Version20221104232209', '2022-11-05 00:22:28', 213),
('DoctrineMigrations\\Version20221105140906', '2022-11-05 15:09:10', 163),
('DoctrineMigrations\\Version20221113111805', '2022-11-13 12:18:08', 72),
('DoctrineMigrations\\Version20221113182239', '2022-11-13 19:22:48', 360),
('DoctrineMigrations\\Version20221113190832', '2022-11-13 20:08:35', 31),
('DoctrineMigrations\\Version20221121233305', '2022-11-22 00:33:13', 174),
('DoctrineMigrations\\Version20221209130159', '2022-12-09 14:02:04', 314),
('DoctrineMigrations\\Version20221209130535', '2022-12-09 14:05:38', 82),
('DoctrineMigrations\\Version20221209143407', '2022-12-09 15:34:10', 33),
('DoctrineMigrations\\Version20221209161931', '2022-12-09 17:19:34', 63);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `new_user_notification`
--

DROP TABLE IF EXISTS `new_user_notification`;
CREATE TABLE `new_user_notification` (
  `id` int(11) NOT NULL,
  `new_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `watched` tinyint(1) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notification_user`
--

DROP TABLE IF EXISTS `notification_user`;
CREATE TABLE `notification_user` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `name`, `firstname`, `lastname`, `image`, `creation_date`, `is_active`) VALUES
(1, 'diegodenavas@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$0jZdIRhwQrykK3UIqr18vudrCdF/NL3Xm5sTbG39.CQzkKFXB8yiW', 'Diego', 'García', 'Ortiz', 'cat.webp', '2022-10-26 01:01:03', 1),
(6, 'ivan@example.com', '[\"ROLE_ADMIN\"]', '$2y$13$nFoHvaqV.Cb8OtsqdZNmD.9YJ7dkvkVSKJ/4jUjUw7kPzmCpdT49a', 'Iván', 'García', NULL, 'train-172581671-1000-6366580ab283f731976605.jpg', '2022-11-05 13:33:14', 1),
(12, 'juan@example.com', '[\"ROLE_USER\"]', '$2y$13$2bn3UnlC/o1wf36SlVwvdeLZoPl/y5vfYIyhCO6h6IrnxkClEMLPa', 'Juan', 'Pérez', NULL, 'design-patterns-636684756f242273021865.png', '2022-11-05 16:42:45', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_blocked_notification`
--

DROP TABLE IF EXISTS `user_blocked_notification`;
CREATE TABLE `user_blocked_notification` (
  `id` int(11) NOT NULL,
  `user_blocked_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indices de la tabla `new_user_notification`
--
ALTER TABLE `new_user_notification`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_53BAD8087C2D807B` (`new_user_id`);

--
-- Indices de la tabla `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notification_user`
--
ALTER TABLE `notification_user`
  ADD PRIMARY KEY (`notification_id`,`user_id`),
  ADD KEY `IDX_35AF9D73EF1A9D84` (`notification_id`),
  ADD KEY `IDX_35AF9D73A76ED395` (`user_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Indices de la tabla `user_blocked_notification`
--
ALTER TABLE `user_blocked_notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_83992B0B869897DA` (`user_blocked_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `new_user_notification`
--
ALTER TABLE `new_user_notification`
  ADD CONSTRAINT `FK_53BAD8087C2D807B` FOREIGN KEY (`new_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_53BAD808BF396750` FOREIGN KEY (`id`) REFERENCES `notification` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notification_user`
--
ALTER TABLE `notification_user`
  ADD CONSTRAINT `FK_35AF9D73A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_35AF9D73EF1A9D84` FOREIGN KEY (`notification_id`) REFERENCES `notification` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_blocked_notification`
--
ALTER TABLE `user_blocked_notification`
  ADD CONSTRAINT `FK_83992B0B869897DA` FOREIGN KEY (`user_blocked_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_83992B0BBF396750` FOREIGN KEY (`id`) REFERENCES `notification` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
