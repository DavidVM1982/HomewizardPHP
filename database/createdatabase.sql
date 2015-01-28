CREATE DATABASE IF NOT EXISTS `homewizard` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `homewizard`;

CREATE TABLE IF NOT EXISTS `energylink` (
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `netto` float NOT NULL,
  `S1` float NOT NULL,
  `S2` float NOT NULL,
  `gas` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `history` (
  `id_sensor` smallint(6) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `rain` (
  `date` char(10) NOT NULL,
  `mm` float NOT NULL,
  `id_sensor` int(11) NOT NULL DEFAULT '0'
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
  `value` varchar(10000) COLLATE utf8_unicode_ci NOT NULL
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

CREATE TABLE IF NOT EXISTS `switchhistory` (
  `id_switch` smallint(6) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `temperature` (
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `te` float NOT NULL,
  `hu` tinyint(4) NOT NULL,
  `id_sensor` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `temp_day` (
  `date` char(10) NOT NULL,
  `min` float NOT NULL,
  `max` float NOT NULL,
  `id_sensor` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `versie` (
`id` int(11) NOT NULL,
  `versie` int(11) NOT NULL,
  `datumupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `wind` (
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `wi` float NOT NULL,
  `gu` float NOT NULL,
  `dir` smallint(6) NOT NULL,
  `id_sensor` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `wind_day` (
  `date` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sensor` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `energylink`
 ADD PRIMARY KEY (`timestamp`);

ALTER TABLE `history`
 ADD PRIMARY KEY (`id_sensor`,`time`);

ALTER TABLE `rain`
 ADD PRIMARY KEY (`date`,`id_sensor`);

ALTER TABLE `sensors`
 ADD PRIMARY KEY (`id_sensor`,`type`);

ALTER TABLE `settings`
 ADD PRIMARY KEY (`variable`);

ALTER TABLE `statusses`
 ADD PRIMARY KEY (`status`);

ALTER TABLE `switches`
 ADD PRIMARY KEY (`id_switch`,`type`);

ALTER TABLE `temperature`
 ADD PRIMARY KEY (`timestamp`,`id_sensor`);

ALTER TABLE `temp_day`
 ADD PRIMARY KEY (`date`,`id_sensor`);

ALTER TABLE `versie`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `wind`
 ADD PRIMARY KEY (`timestamp`,`id_sensor`);

ALTER TABLE `wind_day`
 ADD PRIMARY KEY (`date`,`id_sensor`);


ALTER TABLE `versie`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

INSERT IGNORE INTO `statusses` (`status`, `omschrijving`) VALUES
('contactno', 'Gesloten'),
('contactyes', 'Open'),
('doorbellyes', 'Gebeld'),
('motionno', 'motionno'),
('motionyes', 'Beweging'),
('smokeno', 'Tested'),
('smokeyes', 'ROOK!!!');

INSERT IGNORE INTO `settings` (`variable`, `value`) VALUES
('acceptedip', '127.0.0.1'),
('acceptedip2', '1.2.3.4'),
('debug', 'no'),
('defaultthermometer', '1'),
('detailscenes', 'optional'),
('developerjson', '{"status": "ok", "version": "2.84", "request": {"route": "/get-sensors" }, "response": {"switches" : [{"id":0,"name":"Pluto","type":"switch","status":"off","favorite":"yes"},{"id":1,"name":"Licht Garage","type":"switch","status":"off","favorite":"no"},{"id":2,"name":"Bureel Tobi","type":"switch","status":"off","favorite":"no"},{"id":3,"name":"Lamp Bureel","type":"switch","status":"on","favorite":"no"},{"id":4,"name":"TV","type":"switch","status":"off","favorite":"yes"},{"id":5,"name":"Radio","type":"switch","status":"on","favorite":"yes"},{"id":6,"name":"Badkamer","type":"radiator","tte":10.0,"favorite":"yes"},{"id":7,"name":"Slaapkamer","type":"radiator","tte":8.0,"favorite":"no"},{"id":8,"name":"Slaapkamer Tobi","type":"radiator","tte":8.0,"favorite":"no"},{"id":9,"name":"Diskstation","type":"virtual","status":"off","favorite":"no"},{"id":10,"name":"Eettafel","type":"dimmer","status":"off","dimlevel":0,"favorite":"no"},{"id":11,"name":"Zithoek","type":"dimmer","status":"off","dimlevel":0,"favorite":"no"},{"id":12,"name":"Brander","type":"switch","status":"off","favorite":"no"},{"id":13,"name":"Zonneluifel","type":"somfy","favorite":"no"},{"id":14,"name":"Eetplaats","type":"radiator","tte":15.0,"favorite":"no"},{"id":15,"name":"Zitplaats","type":"radiator","tte":8.0,"favorite":"no"}],"uvmeters":[],"windmeters":[{"id":2,"name":"Windmeter","code":"10321553","model":1,"lowBattery":"yes","version":2.19,"unit":0,"ws":0.1,"dir":"NNW 337","gu":0.0,"wc":5.5,"te":5.5,"ws+":3.2,"ws+t":"14:35","ws-":0.1,"ws-t":"18:26","favorite":"no"}],"rainmeters":[{"id":3,"name":"Regenmeter","code":"4091779","model":1,"lowBattery":"no","version":2.19,"mm":0.0,"3h":0.0,"favorite":"no"}],"thermometers":[{"id":1,"name":"Buiten","code":"13666960","model":1,"lowBattery":"no","version":2.19,"te":6.4,"hu":83,"te+":8.0,"te+t":"14:48","te-":2.2,"te-t":"06:44","hu+":89,"hu+t":"05:39","hu-":69,"hu-t":"14:32","outside":"yes","favorite":"no"},{"id":4,"name":"Badkamer","channel":1,"model":0,"outside":"no","favorite":"no"}],"weatherdisplays":[{"id":0,"name":"Weerstation","code":"11657828","model":1,"version":2.20,"favorite":"no"}], "energymeters": [], "energylinks": [], "heatlinks": [], "hues": [], "scenes": [{"id": 0, "name": "Alles", "favorite": "yes"}], "kakusensors": [{"id":0,"name":"Zolder","status":null,"type":"smoke","favorite":"no","timestamp":"00:00","cameraid":null},{"id":1,"name":"Poort","status":"no","type":"contact","favorite":"no","timestamp":"18:00","cameraid":null},{"id":2,"name":"Garage","status":"no","type":"motion","favorite":"no","timestamp":"18:13","cameraid":null},{"id":3,"name":"Hal boven","status":null,"type":"smoke","favorite":"no","timestamp":"00:00","cameraid":null},{"id":4,"name":"Deurbel","status":null,"type":"doorbell","favorite":"no","timestamp":"00:00","cameraid":null}], "cameras": []}}\r\n\r\n\r\n'),
('developermode', 'no'),
('jsonurl', 'http://adres:port/password/'),
('positie_energylink', '7'),
('positie_radiatoren', '3'),
('positie_regen', '8'),
('positie_scenes', '2'),
('positie_schakelaars', '1'),
('positie_sensoren', '4'),
('positie_somfy', '3'),
('positie_temperatuur', '6'),
('positie_wind', '9'),
('refreshinterval', '30'),
('secretpassword', '5678'),
('secretusername', '1234');

INSERT IGNORE INTO `versie` (`versie`, `datumupdate`) VALUES
(20150128, '2015-01-28 15:13:56');
