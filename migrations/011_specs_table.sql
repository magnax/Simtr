CREATE TABLE IF NOT EXISTS `specs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemtype_id` int(11) NOT NULL,
  `buildmenu_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `buildmenu_id` (`buildmenu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Zrzut danych tabeli `specs`
--

INSERT INTO `specs` (`id`, `itemtype_id`, `buildmenu_id`, `time`) VALUES
(1, 1, 2, 3600),
(2, 3, 1, 86400);