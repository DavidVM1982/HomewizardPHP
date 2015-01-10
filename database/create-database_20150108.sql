CREATE DATABASE IF NOT EXISTS `homewizard` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `homewizard`;

CREATE TABLE IF NOT EXISTS `history` (
  `id_sensor` smallint(6) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `rain` (
  `date` char(10) NOT NULL,
  `mm` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sensors` (
  `id_sensor` smallint(6) NOT NULL,
  `volgorde` smallint(6) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `favorite` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `settings` (
  `variable` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(1000) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `statusses` (
  `status` varchar(200) NOT NULL,
  `omschrijving` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `switches` (
  `id_switch` smallint(6) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `favorite` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `volgorde` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `temperature` (
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `te` float NOT NULL,
  `hu` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `temp_day` (
  `date` char(10) NOT NULL,
  `min` float NOT NULL,
  `max` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `wind` (
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `wi` float NOT NULL,
  `gu` float NOT NULL,
  `dir` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `wind_day` (
  `date` char(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `history`
 ADD PRIMARY KEY (`id_sensor`,`time`);

ALTER TABLE `rain`
 ADD PRIMARY KEY (`date`);

ALTER TABLE `sensors`
 ADD PRIMARY KEY (`id_sensor`);

ALTER TABLE `settings`
 ADD PRIMARY KEY (`variable`);

ALTER TABLE `statusses`
 ADD PRIMARY KEY (`status`);

ALTER TABLE `switches`
 ADD PRIMARY KEY (`id_switch`);

ALTER TABLE `temperature`
 ADD PRIMARY KEY (`timestamp`);

ALTER TABLE `temp_day`
 ADD PRIMARY KEY (`date`);

ALTER TABLE `wind`
 ADD PRIMARY KEY (`timestamp`);

ALTER TABLE `wind_day`
 ADD PRIMARY KEY (`date`);

INSERT IGNORE INTO `settings` (`variable`, `value`) VALUES
('acceptedip', '127.0.0.1'),
('acceptedip2', '1.2.3.4'),
('debug', 'no'),
('detailscenes', 'no'),
('jsonurl', 'http://adres:poort/wachtwoord/'),
('secretpassword', '5678'),
('secretusername', '1234');

INSERT IGNORE INTO `statusses` (`status`, `omschrijving`) VALUES
('contactno', 'Gesloten'),
('contactyes', 'Open'),
('doorbellyes', 'Gebeld'),
('motionno', 'motionno'),
('motionyes', 'Beweging'),
('smokeno', 'Getest'),
('smokeyes', 'ROOK!!!');

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
