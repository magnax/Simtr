CREATE TABLE  `specs` (
`id` INT NOT NULL AUTO_INCREMENT ,
`itemtype_id` INT NOT NULL ,
`time` INT NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = INNODB;

INSERT INTO  `specs` (
`id` ,
`itemtype_id` ,
`time`
)
VALUES (
NULL ,  '1',  '3600'
);