CREATE TABLE IF NOT EXISTS `locationclasses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `locationclasses` (`id`, `name`) VALUES
(1, 'hills'),
(2, 'desert');

ALTER TABLE  `locations` ADD  `class_id` INT NOT NULL COMMENT  'Class of location (hills, desert, etc.)' AFTER  `locationtype_id`;

UPDATE locations SET class_id =1;