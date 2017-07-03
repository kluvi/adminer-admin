-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `adminer_help`;
CREATE TABLE `adminer_help` (
  `table` varchar(255) NOT NULL,
  `text` longtext NOT NULL,
  PRIMARY KEY (`table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `adminer_help` (`table`, `text`) VALUES
('examples',	'This help text comes from table adminer_help');

DROP TABLE IF EXISTS `adminer_users`;
CREATE TABLE `adminer_users` (
  `login` varchar(255) NOT NULL COMMENT '{"name": "Login"}',
  `password` varchar(255) NOT NULL COMMENT '{"name": "Password", "type": "password"}',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='{"name": "Admins"}';

INSERT INTO `adminer_users` (`login`, `password`, `updated_at`, `created_at`) VALUES
('test',	'$2y$11$Bx6CZUyytajsSDXnWhIMfuO/jw0OoYMyohmAJ2Occ54rGCO37qyHG',	'2017-07-03 17:46:34',	'2017-06-26 23:08:48');

DELIMITER ;;

CREATE TRIGGER `users_bi` BEFORE INSERT ON `adminer_users` FOR EACH ROW
SET NEW.created_at = NOW(), NEW.updated_at = NOW();;

CREATE TRIGGER `users_bu` BEFORE UPDATE ON `adminer_users` FOR EACH ROW
SET NEW.updated_at=NOW();;

DELIMITER ;

DROP TABLE IF EXISTS `examples`;
CREATE TABLE `examples` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL COMMENT '{"name": "Image field", "type": "image"}',
  `readonly` varchar(255) NOT NULL COMMENT '{"name": "Read-only field", "type": "readonly"}',
  `multi` text NOT NULL COMMENT '{"name": "Multi-input field", "type": "multi-input"}',
  `foreign` int(11) DEFAULT NULL COMMENT '{"name": "Foreign key field"}',
  PRIMARY KEY (`id`),
  KEY `foreign` (`foreign`),
  CONSTRAINT `examples_ibfk_1` FOREIGN KEY (`foreign`) REFERENCES `examples2` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='{"name": "Examples"}';

INSERT INTO `examples` (`id`, `image`, `readonly`, `multi`, `foreign`) VALUES
(10,	'examples/image/10/sticker,375x360.png',	'this field is not editable',	'[\"test 1\",\"test 2\"]',	1);

DROP TABLE IF EXISTS `examples2`;
CREATE TABLE `examples2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `examples2` (`id`, `name`) VALUES
(1,	'Test 1'),
(2,	'Test 2');

-- 2017-07-03 15:57:27