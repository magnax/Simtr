CREATE TABLE IF NOT EXISTS `buildmenus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Zrzut danych tabeli `buildmenu`
--

INSERT INTO `buildmenus` (`id`, `parent_id`, `name`) VALUES
(1, NULL, 'Budynki'),
(2, NULL, 'Narzędzia'),
(3, NULL, 'Broń');