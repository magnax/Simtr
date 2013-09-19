ALTER TABLE  `projecttypes` ADD  `name_needed` TINYINT NOT NULL DEFAULT  '0';
ALTER TABLE  `projects_raws` ADD UNIQUE  `project_resource_unique` (  `project_id` ,  `resource_id` );