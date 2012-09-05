CREATE TABLE  `itemtypes` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
`points` INT NOT NULL
) ENGINE = INNODB;

INSERT INTO `itemtypes` (`id`, `name`, `points`) VALUES
(1, 'kij', 100),
(2, 'stalowa szabla', 550);

CREATE TABLE  `items` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`itemtype_id` INT NOT NULL ,
`points` INT NOT NULL
) ENGINE = INNODB;

INSERT INTO `items` (`id`, `itemtype_id`, `points`) VALUES
(1, 1, 90),
(2, 2, 400);

