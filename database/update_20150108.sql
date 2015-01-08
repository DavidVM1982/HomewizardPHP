UPDATE `homewizard`.`statusses` SET `omschrijving` = 'Gesloten' WHERE `statusses`.`status` = 'contactno';
UPDATE `homewizard`.`statusses` SET `omschrijving` = 'ROOK!!!' WHERE `statusses`.`status` = 'smokeyes';
CREATE TABLE IF NOT EXISTS `versie` (
`id` int(11) NOT NULL,
  `versie` int(11) NOT NULL,
  `datumupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `versie`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `versie`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

INSERT INTO `versie` (`id`, `versie`) VALUES
(1, 20150108);
