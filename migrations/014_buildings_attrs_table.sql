// 
//

CREATE TABLE  `buildings_attrs` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`locationclass_id` INT NOT NULL ,
`capacity_person` INT NOT NULL ,
`max_weight` INT NOT NULL
) ENGINE = INNODB;

INSERT INTO  `buildings_attrs` (
`id` ,
`locationclass_id` ,
`capacity_person` ,
`max_weight`
)
VALUES (
NULL ,  '3',  '3',  '250000'
);