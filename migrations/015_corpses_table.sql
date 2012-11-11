CREATE TABLE  `corpses` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`character_id` INT NOT NULL ,
`location_id` INT NOT NULL ,
`weight` INT NOT NULL ,
`created` INT NOT NULL
) ENGINE = INNODB;

/*
 * character's location ID can now be NULL to point that this character is dead
 */
ALTER TABLE  `characters` CHANGE  `location_id`  `location_id` INT( 11 ) NULL;