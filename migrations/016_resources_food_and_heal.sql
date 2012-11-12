ALTER TABLE  `resources` ADD  `food` INT NULL DEFAULT NULL COMMENT  'amount for 1 day',
ADD  `heal` INT NULL DEFAULT NULL COMMENT  'amount for 1% health';

ALTER TABLE  `resources` CHANGE  `projecttype_id`  `projecttype_id` INT( 11 ) NULL DEFAULT NULL;

ALTER TABLE  `resources` ADD INDEX (  `food` );

ALTER TABLE  `resources` ADD INDEX (  `heal` );

ALTER TABLE  `characters` ADD  `fed` INT NOT NULL DEFAULT  '100' AFTER  `life`;
ALTER TABLE  `characters` CHANGE  `life`  `life` INT( 11 ) NOT NULL DEFAULT  '1000';