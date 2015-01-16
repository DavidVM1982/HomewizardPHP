CREATE DATABASE IF NOT EXISTS `homewizard` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `homewizard`;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

ALTER TABLE `history`
 ADD PRIMARY KEY (`id_sensor`,`time`);

ALTER TABLE `rain`
 ADD PRIMARY KEY (`date`,`id_sensor`);

ALTER TABLE `sensors`
 ADD PRIMARY KEY (`id_sensor`);

ALTER TABLE `settings`
 ADD PRIMARY KEY (`variable`);

ALTER TABLE `statusses`
 ADD PRIMARY KEY (`status`);

ALTER TABLE `switches`
 ADD PRIMARY KEY (`id_switch`);

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
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
('detailscenes', 'no'),
('developerjson', '{"status": "ok", "version": "2.81", "request": {"route": "/get-sensors" }, "response": {"switches" : [{"id":0,"name":"Kerstboom","type":"switch","status":"off","favorite":"no"},{"id":1,"name":"Licht Garage","type":"switch","status":"on","favorite":"no"},{"id":2,"name":"Bureel Tobi","type":"switch","status":"off","favorite":"no"},{"id":3,"name":"Lamp Bureel","type":"switch","status":"off","favorite":"no"},{"id":4,"name":"TV","type":"switch","status":"off","favorite":"yes"},{"id":5,"name":"Radio","type":"switch","status":"on","favorite":"yes"},{"id":6,"name":"Badkamer","type":"radiator","tte":15.0,"favorite":"no"},{"id":7,"name":"Slaapkamer","type":"radiator","tte":15.0,"favorite":"no"},{"id":8,"name":"Slaapkamer Tobi","type":"radiator","tte":15.0,"favorite":"no"},{"id":9,"name":"Diskstation","type":"virtual","status":"on","favorite":"no"},{"id":10,"name":"Living","type":"dimmer","status":"off","dimlevel":0,"favorite":"no"},{"id":11,"name":"Salon","type":"dimmer","status":"off","dimlevel":0,"favorite":"no"}],"uvmeters":[],"windmeters":[{"id":2,"name":"Windmeter","code":"10321553","model":1,"lowBattery":"no","version":2.19,"unit":0,"ws":3.0,"dir":"ESE 112","gu":4.3,"wc":8.4,"te":8.4,"ws+":7.0,"ws+t":"00:40","ws-":0.1,"ws-t":"10:12","favorite":"no"}],"rainmeters":[{"id":3,"name":"Regenmeter","code":"4091779","model":1,"lowBattery":"no","version":2.19,"mm":15.4,"3h":0.0,"favorite":"no"}],"thermometers":[{"id":1,"name":"Buiten","code":"13666960","model":1,"lowBattery":"no","version":2.19,"te":9.5,"hu":81,"te+":10.5,"te+t":"01:36","te-":9.0,"te-t":"09:56","hu+":90,"hu+t":"07:04","hu-":81,"hu-t":"16:07","outside":"yes","favorite":"no"}],"weatherdisplays":[{"id":0,"name":"Weerstation","code":"11657828","model":1,"version":2.20,"favorite":"no"}], "energymeters": [], "energylinks": [], "heatlinks": [], "hues": [], "scenes": [{"id": 0, "name": "Alles", "favorite": "yes"}], "kakusensors": [{"id":0,"name":"Zolder","status":null,"type":"smoke","favorite":"no","timestamp":"00:00","cameraid":null},{"id":1,"name":"Poort","status":"no","type":"contact","favorite":"no","timestamp":"11:25","cameraid":null},{"id":2,"name":"Garage","status":"no","type":"motion","favorite":"no","timestamp":"15:27","cameraid":null},{"id":3,"name":"Hal boven","status":null,"type":"smoke","favorite":"no","timestamp":"00:00","cameraid":null},{"id":4,"name":"Deurbel","status":null,"type":"doorbell","favorite":"no","timestamp":"00:00","cameraid":null}], "cameras": []}}'),
('developermode', 'no'),
('jsonurl', 'http://adres:port/wachtwoord/'),
('positie_radiatoren', '3'),
('positie_regen', '8'),
('positie_scenes', '2'),
('positie_schakelaars', '1'),
('positie_sensoren', '4'),
('positie_somfy', '1'),
('positie_temperatuur', '6'),
('positie_wind', '9'),
('refreshinterval', '60'),
('secretpassword', '5678'),
('secretusername', '1234');

INSERT INTO `versie` (`versie`) VALUES (20150116)
