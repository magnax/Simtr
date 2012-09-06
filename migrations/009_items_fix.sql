ALTER TABLE  `itemtypes` ADD  `attack` INT NULL DEFAULT NULL AFTER  `name` ,
ADD INDEX (  `attack` );
