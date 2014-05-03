-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 03-05-2014 a las 21:54:37
-- Versión del servidor: 5.5.34-0ubuntu0.12.04.1
-- Versión de PHP: 5.3.10-1ubuntu3.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `jlopez_kloderdemo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `flag` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `languages`
--

INSERT INTO `languages` (`id`, `name`, `flag`, `created`, `modified`) VALUES
('en_US', 'English', 'us.png', '2014-03-18 07:14:59', '2014-03-18 07:15:20'),
('es_ES', 'Español', 'es.png', '2014-01-25 06:06:33', '2014-01-25 06:06:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notices`
--

CREATE TABLE IF NOT EXISTS `notices` (
  `id` varchar(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `active` tinyint(1) NOT NULL,
  `class` varchar(100) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plugins`
--

CREATE TABLE IF NOT EXISTS `plugins` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `version` varchar(10) NOT NULL,
  `link` varchar(255) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plugins_repositories`
--

CREATE TABLE IF NOT EXISTS `plugins_repositories` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `public` tinyint(1) NOT NULL,
  `user` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `last_check` datetime NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `plugins_repositories`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` varchar(36) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `thumb_dir` varchar(255) NOT NULL,
  `users_group_id` varchar(36) DEFAULT NULL,
  `language_id` varchar(10) NOT NULL,
  `date_format` varchar(15) NOT NULL,
  `time_format` varchar(15) NOT NULL,
  `first_day` tinyint(4) NOT NULL,
  `last_access` datetime NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `last_name`, `email`, `thumb`, `thumb_dir`, `users_group_id`, `language_id`, `date_format`, `time_format`, `first_day`, `last_access`, `created`, `modified`) VALUES
('53655145-4778-43e4-9a4a-4d36b00903d2', 'admin', '9af4c1a6ff440d3f52d0f588077e464bf4065f09', 'Administrador', '', 'jlopezcur@gmail.com', '', '', 'admin', '', '', '', 0, '0000-00-00 00:00:00', '2014-05-03 20:27:49', '2014-05-03 20:27:49');
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` varchar(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users_groups`
--

INSERT INTO `users_groups` (`id`, `name`, `created`, `modified`) VALUES
('admin', 'Administrators', '2014-02-12 02:52:37', '2014-02-12 02:52:37'),
('users', 'Users', '2014-02-12 02:53:45', '2014-02-12 02:56:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_settings`
--

CREATE TABLE IF NOT EXISTS `users_settings` (
  `id` varchar(36) NOT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` text,
  `group` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users_settings`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
