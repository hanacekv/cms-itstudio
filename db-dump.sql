-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `text` text NOT NULL,
  `image_path` varchar(200) DEFAULT NULL,
  `visibility` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(80) NOT NULL,
  `role` varchar(15) NOT NULL DEFAULT 'registered',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1,	'admin',	'$2y$10$wF7LyUTkxnN/P5aQAGGR5e59DZ0LLx.gJ1iH.FDTPruJkgBxBO4Ry',	'admin'),
(14,	'uzivatel',	'$2y$10$eKBrhFig9h7xvktPgHbw9uS2YEB.isRc5QttNLf0WaqLlVGOjdXPi',	'registered');

-- 2016-08-09 10:30:09
