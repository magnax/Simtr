ALTER TABLE `resources` ADD `gather_base` INT NULL;
UPDATE  `resources` SET  `gather_base` =  '300' WHERE  `resources`.`id` =1;
UPDATE  `resources` SET  `gather_base` =  '1100' WHERE  `resources`.`id` =2;
UPDATE  `resources` SET  `gather_base` =  '700' WHERE  `resources`.`id` =3;