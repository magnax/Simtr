ALTER TABLE  `itemtypes` ADD  `attack` INT NULL DEFAULT NULL AFTER  `name` ,
ADD INDEX (  `attack` );

ALTER TABLE  `characters` ADD  `life` INT NOT NULL DEFAULT  '1000' AFTER  `spawn_location_id`;

ALTER TABLE  `characters` ADD  `fighting` DECIMAL( 2, 1 ) NOT NULL DEFAULT  '1.0' AFTER  `life`