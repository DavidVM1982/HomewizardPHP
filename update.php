<?php
$laatsteversie = 20150111;
include "header.php";
print '<div class="row"><div class="span_3">';
if($authenticated==true) {
	
//BEGIN UPDATE	
$sql="select versie from versie order by id desc limit 0,1";
if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'][' . $db->error . ']');}
while($row = $result->fetch_assoc()){$versie = $row['versie'];}
$result->free();
if(isset($_POST['updatedatabasenow'])) {
	if($versie<20150109) {
		$sql="insert into versie (versie) VALUES ('20150109');";
		if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150110) {
		$sql="ALTER TABLE `switches` CHANGE `volgorde` `volgorde` SMALLINT(6) DEFAULT null;";
		if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150110');";
		if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
	if($versie<20150111) {
		$sql="ALTER TABLE `temperature` ADD `id_sensor` INT DEFAULT NULL ;ALTER TABLE `temperature` DROP PRIMARY KEY; ALTER TABLE `temperature` ADD PRIMARY KEY (`timestamp`,`id_sensor`);";
		if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="ALTER TABLE `temp_day` ADD `id_sensor` INT DEFAULT NULL ;ALTER TABLE `temp_day` DROP PRIMARY KEY; ALTER TABLE `temp_day` ADD PRIMARY KEY (`date`,`id_sensor`);";
		if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="ALTER TABLE `rain` ADD `id_sensor` INT DEFAULT NULL ;ALTER TABLE `rain` DROP PRIMARY KEY; ALTER TABLE `rain` ADD PRIMARY KEY (`date`,`id_sensor`);";
		if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="ALTER TABLE `wind` ADD `id_sensor` INT DEFAULT NULL ;ALTER TABLE `wind` DROP PRIMARY KEY; ALTER TABLE `wind` ADD PRIMARY KEY (`timestamp`,`id_sensor`);";
		if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="ALTER TABLE `wind_day` ADD `id_sensor` INT DEFAULT NULL ;ALTER TABLE `wind_day` DROP PRIMARY KEY; ALTER TABLE `wind_day` ADD PRIMARY KEY (`date`,`id_sensor`);";
		if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'][' . $db->error . ']');}
		$sql="insert into versie (versie) VALUES ('20150111');";
		if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'][' . $db->error . ']');}
	}
}

$sql="select versie from versie order by id desc limit 0,1";
if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'][' . $db->error . ']');}
while($row = $result->fetch_assoc()){$versie = $row['versie'];}

echo '<br/>Huidige versie database: '.$versie.'<br/><br>
Geïnstalleerde versie HomewizardPHP: '.$laatsteversie.'<br/><br/>';
if($versie<$laatsteversie) echo '<form method="post"><input type="submit" name="updatedatabasenow" value="Update Database" class="abutton settings"/></form>';
//EINDE UPDATE
}
print '</div></div>';
include "footer.php";
?>