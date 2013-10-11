-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 11 Paź 2013, 08:33
-- Wersja serwera: 5.5.32
-- Wersja PHP: 5.3.10-1ubuntu3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Baza danych: `simtr`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `machines`
--

CREATE TABLE IF NOT EXISTS `machines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemtype_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='maszyny (urządzenia) w lokacjach' AUTO_INCREMENT=2 ;

--
-- Zrzut danych tabeli `machines`
--

INSERT INTO `machines` (`id`, `itemtype_id`, `location_id`) VALUES
(1, 15, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `roadtypes`
--

CREATE TABLE IF NOT EXISTS `roadtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemtype_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `roadtypes`
--

INSERT INTO `roadtypes` (`id`, `itemtype_id`, `level`, `name`) VALUES
(1, 9, 1, 'droga piaszczysta'),
(2, 10, 2, 'droga brukowana'),
(3, 11, 3, 'szosa'),
(4, 12, 4, 'autostrada');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `skills`
--

CREATE TABLE IF NOT EXISTS `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Skills dictionary' AUTO_INCREMENT=16 ;

--
-- Zrzut danych tabeli `skills`
--

INSERT INTO `skills` (`id`, `name`) VALUES
(1, 'Oczyszczanie/Wytapianie'),
(2, 'Gotowanie'),
(3, 'Wiertnictwo/Górnictwo'),
(4, 'Leśnictwo'),
(5, 'Uprawa/zbieractwo'),
(6, 'Polowanie'),
(7, 'Budowa maszyn'),
(8, 'Budowa pojazdów'),
(9, 'Produkcja broni'),
(10, 'Produkcja narzędzi'),
(11, 'Walka'),
(12, 'Krawiectwo'),
(13, 'Stolarstwo'),
(14, 'Kopanie'),
(15, 'Budowa domów');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `tools`
--

CREATE TABLE IF NOT EXISTS `tools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemtype_id` int(11) NOT NULL,
  `req_itemtype_id` int(11) NOT NULL,
  `optional` tinyint(4) NOT NULL DEFAULT '0',
  `speed` float NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='narzędzia używane przy projektach' AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `tools`
--

INSERT INTO `tools` (`id`, `itemtype_id`, `req_itemtype_id`, `optional`, `speed`) VALUES
(2, 9, 13, 0, 1),
(3, 9, 14, 1, 1.5),
(4, 9, 1, 1, 1.1);
