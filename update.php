<?php
$laatsteversie = 20150209;
if($authenticated==true) {
	
//BEGIN UPDATE	
$sql="select versie from versie order by id desc limit 0,1";
if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
while($row = $result->fetch_assoc()){$versie = $row['versie'];}
$result->free();
if(isset($_POST['updatedatabasenow'])) {
	if($versie<20150109) {
		$sql="insert into versie (versie) VALUES ('20150109');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150110) {
		$sql="ALTER TABLE `switches` CHANGE `volgorde` `volgorde` SMALLINT(6) DEFAULT null;";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150110');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150111) {
		$sql="ALTER TABLE `temperature` ADD `id_sensor` INT DEFAULT NULL ;";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="ALTER TABLE `temperature` DROP PRIMARY KEY;";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="ALTER TABLE `temperature` ADD PRIMARY KEY (`timestamp`,`id_sensor`);";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="ALTER TABLE `temp_day` ADD `id_sensor` INT DEFAULT NULL ;";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="ALTER TABLE `temp_day` DROP PRIMARY KEY;";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="ALTER TABLE `temp_day` ADD PRIMARY KEY (`date`,`id_sensor`);";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="ALTER TABLE `rain` ADD `id_sensor` INT DEFAULT NULL ;";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="ALTER TABLE `rain` DROP PRIMARY KEY;";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="ALTER TABLE `rain` ADD PRIMARY KEY (`date`,`id_sensor`);";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="ALTER TABLE `wind` ADD `id_sensor` INT DEFAULT NULL ;";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="ALTER TABLE `wind` DROP PRIMARY KEY;";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="ALTER TABLE `wind` ADD PRIMARY KEY (`timestamp`,`id_sensor`);";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="ALTER TABLE `wind_day` ADD `id_sensor` INT DEFAULT NULL ;";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="ALTER TABLE `wind_day` DROP PRIMARY KEY;";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="ALTER TABLE `wind_day` ADD PRIMARY KEY (`date`,`id_sensor`);";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
		$sql="insert into versie (versie) VALUES ('20150111');";
		if(!$result = $db->query($sql)){ echo 'There was an error running the query ['.$sql.'][' . $db->error . ']<hr>';}
	}
	if($versie<20150113) {
		$sql="ALTER TABLE `settings` CHANGE `value` `value` VARCHAR(10000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="INSERT INTO `homewizard`.`settings` (`variable`, `value`) VALUES ('developermode', 'no'), ('developerjson', '{\"status\": \"ok\", \"version\": \"2.81\", \"request\": {\"route\": \"/get-sensors\" }, \"response\": {\"switches\" : [{\"id\":0,\"name\":\"Kerstboom\",\"type\":\"switch\",\"status\":\"off\",\"favorite\":\"no\"},{\"id\":1,\"name\":\"Licht Garage\",\"type\":\"switch\",\"status\":\"off\",\"favorite\":\"no\"},{\"id\":2,\"name\":\"Bureel Tobi\",\"type\":\"switch\",\"status\":\"off\",\"favorite\":\"no\"},{\"id\":3,\"name\":\"Lamp Bureel\",\"type\":\"switch\",\"status\":\"on\",\"favorite\":\"no\"},{\"id\":4,\"name\":\"TV\",\"type\":\"switch\",\"status\":\"off\",\"favorite\":\"yes\"},{\"id\":5,\"name\":\"Radio\",\"type\":\"switch\",\"status\":\"on\",\"favorite\":\"yes\"},{\"id\":6,\"name\":\"Badkamer\",\"type\":\"radiator\",\"tte\":15.0,\"favorite\":\"no\"},{\"id\":7,\"name\":\"Slaapkamer\",\"type\":\"radiator\",\"tte\":15.0,\"favorite\":\"no\"},{\"id\":8,\"name\":\"Slaapkamer Tobi\",\"type\":\"radiator\",\"tte\":15.0,\"favorite\":\"no\"},{\"id\":9,\"name\":\"Diskstation\",\"type\":\"virtual\",\"status\":\"on\",\"favorite\":\"no\"},{\"id\":10,\"name\":\"Living\",\"type\":\"dimmer\",\"status\":\"off\",\"dimlevel\":0,\"favorite\":\"no\"},{\"id\":11,\"name\":\"Salon\",\"type\":\"dimmer\",\"status\":\"off\",\"dimlevel\":0,\"favorite\":\"no\"}],\"uvmeters\":[],\"windmeters\":[{\"id\":2,\"name\":\"Windmeter\",\"code\":\"10321553\",\"model\":1,\"lowBattery\":\"no\",\"version\":2.19,\"unit\":0,\"favorite\":\"no\"}],\"rainmeters\":[{\"id\":3,\"name\":\"Regenmeter\",\"code\":\"4091779\",\"model\":1,\"lowBattery\":\"no\",\"version\":2.19,\"mm\":15.4,\"3h\":0.0,\"favorite\":\"no\"}],\"thermometers\":[{\"id\":1,\"name\":\"Buiten\",\"code\":\"13666960\",\"model\":1,\"lowBattery\":\"no\",\"version\":2.19,\"te\":9.7,\"hu\":83,\"te+\":10.5,\"te+t\":\"01:36\",\"te-\":9.0,\"te-t\":\"09:56\",\"hu+\":90,\"hu+t\":\"07:04\",\"hu-\":82,\"hu-t\":\"01:45\",\"outside\":\"yes\",\"favorite\":\"no\"}],\"weatherdisplays\":[{\"id\":0,\"name\":\"Weerstation\",\"code\":\"11657828\",\"model\":1,\"version\":2.20,\"favorite\":\"no\"}], \"energymeters\": [], \"energylinks\": [], \"heatlinks\": [], \"hues\": [], \"scenes\": [{\"id\": 0, \"name\": \"Alles\", \"favorite\": \"yes\"}], \"kakusensors\": [{\"id\":0,\"name\":\"Zolder\",\"status\":null,\"type\":\"smoke\",\"favorite\":\"no\",\"timestamp\":\"00:00\",\"cameraid\":null},{\"id\":1,\"name\":\"Poort\",\"status\":\"no\",\"type\":\"contact\",\"favorite\":\"no\",\"timestamp\":\"11:25\",\"cameraid\":null},{\"id\":2,\"name\":\"Garage\",\"status\":\"no\",\"type\":\"motion\",\"favorite\":\"no\",\"timestamp\":\"15:27\",\"cameraid\":null},{\"id\":3,\"name\":\"Hal boven\",\"status\":null,\"type\":\"smoke\",\"favorite\":\"no\",\"timestamp\":\"00:00\",\"cameraid\":null},{\"id\":4,\"name\":\"Deurbel\",\"status\":null,\"type\":\"doorbell\",\"favorite\":\"no\",\"timestamp\":\"00:00\",\"cameraid\":null}], \"cameras\": []}}');";
		if(!$result = $db->query($sql)){ echo ('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150113');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150116) {
		$sql="INSERT IGNORE INTO `settings` (`variable`, `value`) VALUES ('positie_radiatoren', '3'),('positie_regen', '8'),('positie_scenes', '2'),('positie_schakelaars', '1'),('positie_sensoren', '4'),('positie_somfy', '1'),('positie_temperatuur', '6'),('positie_wind', '9'),('refreshinterval', '60');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150116');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150126) {
		$sql="INSERT IGNORE INTO settings (`variable`, `value`) VALUES ('positie_energylink', '7');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="CREATE TABLE IF NOT EXISTS `energylink` (`timestamp` timestamp NOT NULL,`netto` float NOT NULL,`S1` float NOT NULL,`S2` float NOT NULL,`gas` float NOT NULL,`verbruik` float NOT NULL,) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="ALTER TABLE energylink ADD PRIMARY KEY (timestamp);";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150126');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150128) { 
		$sql="INSERT IGNORE INTO settings (`variable`, `value`) VALUES ('defaultthermometer', '1');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="ALTER TABLE `sensors` DROP PRIMARY KEY;";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="ALTER TABLE `sensors` ADD PRIMARY KEY (`id_sensor`,`type`);";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="ALTER TABLE `switches` DROP PRIMARY KEY;";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="ALTER TABLE `switches` ADD PRIMARY KEY (`id_switch`,`type`);";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="CREATE TABLE IF NOT EXISTS switchhistory (`id_switch` smallint(6) NOT NULL, `timestamp` int(11) NOT NULL,`type` varchar(50) COLLATE utf8_unicode_ci NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150128');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150129) {
		$sql="INSERT IGNORE INTO `settings` (`variable`, `value`) VALUES ('toon_radiatoren', 'yes'),('toon_regen', 'yes'),('toon_scenes', 'yes'),('toon_schakelaars', 'yes'),('toon_sensoren', 'yes'),('toon_somfy', 'yes'),('toon_temperatuur', 'yes'),('toon_wind', 'yes'),('toon_energylink', 'yes');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150129');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150130) {
		$sql="ALTER TABLE `switchhistory` ADD `who` VARCHAR(20) NOT NULL DEFAULT 'man';";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150130');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150131) {
		$sql="ALTER TABLE `switches` ADD `temp` SMALLINT NULL ;";
		if(!$result = $db->query($sql)){ echo ('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150131');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150201) {
		$sql="INSERT IGNORE INTO `settings` (`variable`, `value`) VALUES ('css_td_newgroup', 'border-top:1px solid black; padding-top:10px;')";
		if(!$result = $db->query($sql)){ echo ('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150201');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150204) {
		$sql="INSERT IGNORE INTO `settings` (`variable`, `value`) VALUES ('temp_correctie0', '0'),('temp_correctie1', '0'),('temp_correctie2', '0'),('temp_correctie3', '0'),('temp_correctie4', '0'),('temp_correctie5', '0'),('temp_correctie6', '0'),('temp_correctie7', '0'),('temp_correctie8', '0'),('temp_correctie9', '0'),('temp_correctie10', '0')";
		if(!$result = $db->query($sql)){ echo ('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150204');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150207) {
		$sql="DELETE FROM settings WHERE variable like 'temp_correctie%'";
		if(!$result = $db->query($sql)){ echo ('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="ALTER TABLE `sensors` ADD `tempk` FLOAT NOT NULL DEFAULT '0' ;";
		if(!$result = $db->query($sql)){ echo ('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="ALTER TABLE `sensors` ADD `tempw` FLOAT NOT NULL DEFAULT '22' ;";
		if(!$result = $db->query($sql)){ echo ('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="ALTER TABLE `sensors` ADD `correctie` FLOAT NOT NULL DEFAULT '0' ;";
		if(!$result = $db->query($sql)){ echo ('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="INSERT IGNORE INTO `settings` (`variable`, `value`) VALUES ('css_body', ''),('css_item', '')";
		if(!$result = $db->query($sql)){ echo ('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150207');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150208) {
		$sql="INSERT IGNORE INTO `settings` (`variable`, `value`) VALUES ('email_from', 'guy@egregius.be'),('email_notificatie', 'guy@egregius.be')";
		if(!$result = $db->query($sql)){ echo ('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150208');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150209) {
		$sql="INSERT IGNORE INTO `settings` (`variable`, `value`) VALUES ('toon_acties', 'yes'),('positie_acties', '9')";
		if(!$result = $db->query($sql)){ echo ('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="ALTER TABLE `settings` ADD `favorite` VARCHAR(20) DEFAULT null;";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150208');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
}





$sql="select versie from versie order by id desc limit 0,1";
if(!$result = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
while($row = $result->fetch_assoc()){$versie = $row['versie'];}

echo '<br/>Huidige versie database: '.$versie.'<br/><br>
Geïnstalleerde versie HomewizardPHP: '.$laatsteversie.'<br/><br/>';
if($versie<$laatsteversie) echo '<form method="post"><input type="hidden" name="updatedatabase" value="Update Database" class="abutton settings gradient"/><input type="submit" name="updatedatabasenow" value="Update Database" class="abutton settings"/></form>';
//EINDE UPDATE
}
?>