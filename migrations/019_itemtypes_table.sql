-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 11 Paź 2013, 08:37
-- Wersja serwera: 5.5.32
-- Wersja PHP: 5.3.10-1ubuntu3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Baza danych: `simtr`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `itemtypes`
--

CREATE TABLE IF NOT EXISTS `itemtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projecttype_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `attack` int(11) DEFAULT NULL,
  `shield` int(11) DEFAULT NULL,
  `visible` tinyint(4) NOT NULL DEFAULT '1',
  `rot` int(11) DEFAULT NULL,
  `rot_use` int(11) DEFAULT NULL,
  `repair` int(11) DEFAULT NULL,
  `points` int(11) NOT NULL,
  `weight` int(11) DEFAULT NULL,
  `kind` char(1) NOT NULL DEFAULT 'M' COMMENT 'typ odmiany męski/żeński/nijaki (M,K,N)',
  PRIMARY KEY (`id`),
  KEY `attack` (`attack`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Zrzut danych tabeli `itemtypes`
--

INSERT INTO `itemtypes` (`id`, `projecttype_id`, `name`, `attack`, `shield`, `visible`, `rot`, `rot_use`, `repair`, `points`, `weight`, `kind`) VALUES
(-1, 0, '(brak)', NULL, NULL, 1, NULL, NULL, NULL, 0, NULL, 'M'),
(0, 0, 'goła pięść', 10, NULL, 1, NULL, NULL, NULL, 0, NULL, 'M'),
(1, 2, 'kij', 20, 0, 1, 0, 0, 0, 100, 300, 'M'),
(2, 2, 'stalowa szabla', 400, NULL, 1, NULL, NULL, NULL, 550, NULL, 'K'),
(3, 3, 'drewniana chata', NULL, NULL, 1, NULL, NULL, NULL, 10000, NULL, 'K'),
(4, 2, 'kościany bagh-nagh', 45, NULL, 1, NULL, NULL, NULL, 300, NULL, 'M'),
(5, 4, 'zamek level 1', NULL, NULL, 1, NULL, NULL, NULL, 0, NULL, 'M'),
(6, 4, 'zamek level 2', NULL, NULL, 1, NULL, NULL, NULL, 0, NULL, 'M'),
(7, 4, 'zamek level 3', NULL, NULL, 1, NULL, NULL, NULL, 0, NULL, 'M'),
(8, 4, 'zamek level 4', NULL, NULL, 1, NULL, NULL, NULL, 0, NULL, 'M'),
(9, 5, 'droga level 1', 0, 0, 0, 0, 0, 0, 0, 0, 'K'),
(10, 5, 'droga level 2', 0, 0, 0, 0, 0, 0, 0, 0, 'K'),
(11, 5, 'droga level 3', 0, 0, 0, 0, 0, 0, 0, 0, 'K'),
(12, 5, 'droga level 4', 0, 0, 0, 0, 0, 0, 0, 0, 'K'),
(13, 2, 'Łopata', 0, 0, 1, 20, 60, 120, 1500, 560, 'K'),
(14, 2, 'taczka', 0, 0, 1, 20, 40, 80, 2000, 800, 'K'),
(15, 2, 'dymarka', 0, 0, 0, 0, 0, 0, 0, 5000, 'K');
