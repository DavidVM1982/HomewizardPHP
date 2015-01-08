CREATE TABLE IF NOT EXISTS `switches` (
  `id_switch` smallint(6) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `favorite` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `volgorde` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `switches`
 ADD PRIMARY KEY (`id_switch`);

ALTER TABLE `sensors` 
 CHANGE `order` `volgorde` SMALLINT NULL DEFAULT NULL;
 
ALTER TABLE `sensors` 
 ADD `favorite` VARCHAR(3) NOT NULL ;




