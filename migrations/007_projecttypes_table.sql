CREATE TABLE  `simtr`.`projecttypes` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) NOT NULL
) ENGINE = INNODB;

INSERT INTO `simtr`.`projecttypes` (`id`, `name`) VALUES (1, 'zbieranie');

ALTER TABLE  `resources` ADD  `projecttype_id` INT NOT NULL ,
ADD  `d` VARCHAR( 255 ) NOT NULL COMMENT  'odmiana - dopełniacz, np. "drewna"';

UPDATE  `resources` SET  `projecttype_id` =  '1',
`d` =  'drewna' WHERE  `resources`.`id` =1;
UPDATE  `resources` SET  `projecttype_id` =  '1',
`d` =  'piasku' WHERE  `resources`.`id` =2;
UPDATE  `resources` SET  `projecttype_id` =  '1',
`d` =  'ziemniaków' WHERE  `resources`.`id` =3;
