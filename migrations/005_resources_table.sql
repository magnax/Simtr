CREATE TABLE `resources` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`is_raw` TINYINT NOT NULL DEFAULT  '0'
) ENGINE = INNODB;

INSERT INTO `resources` (`id`, `name`, `is_raw`) VALUES
(1, 'drewno', 1),
(2, 'piasek', 1),
(3, 'ziemniaki', 1);

CREATE TABLE  `locations_resources` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`location_id` INT NOT NULL ,
`resource_id` INT NOT NULL
) ENGINE = INNODB;

INSERT INTO `locations_resources` (`id`, `location_id`, `resource_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(4, 2, 3);