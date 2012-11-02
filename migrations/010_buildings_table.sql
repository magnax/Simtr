CREATE TABLE  `buildings` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`location_id` INT NOT NULL ,
`capacity_person` INT NOT NULL DEFAULT  '3',
`max_weight` INT NOT NULL DEFAULT  '200000'
) ENGINE = INNODB ;

INSERT INTO `locationtypes` (`id`, `name`, `tablename`) VALUES
(2, 'building', 'buildings');

INSERT INTO `locationclasses` (`id`, `name`) VALUES
(3, 'wooden building');

INSERT INTO  `locations` (
`id` ,
`locationtype_id` ,
`class_id` ,
`parent_id` ,
`name`
)
VALUES (
NULL ,  '2',  '3',  '1',  'Warsztat'
);

INSERT INTO  `buildings` (
`id` ,
`location_id` ,
`capacity_person` ,
`max_weight`
)
VALUES (
NULL ,  '3',  '3',  '200000'
);