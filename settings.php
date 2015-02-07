<?php
include "header.php";
print '<div class="threecolumn"><div class="isotope">';	
$showparameters = true;
$showupdatesensors  = false;
$showeditswitches = false;
$showeditsensors = false;
$showopmaak = false;
$showacties = false;
$cleandatabase = false;
if(isset($_POST['updatesensors'])) {$showupdatesensors = true; $showparameters = false;}
if(isset($_POST['editswitches'])) {$showeditswitches = true; $showparameters = false;}
if(isset($_POST['editsensors'])) {$showeditsensors = true; $showparameters = false;}
if(isset($_POST['showopmaak'])) {$showopmaak = true; $showparameters = false;}
if(isset($_POST['showacties'])) {$showacties = true; $showparameters = false;}
if(isset($_POST['cleandatabase'])) {$cleandatabase = true; $showparameters = false;}

if(isset($_POST['logout'])) {session_destroy();$authenticated = false;header("location:settings.php");exit();}
if($authenticated==true) {
if(isset($_POST['deleteswitch'])) { 
	$id_switch=($_POST['id_switch']);
	$sql="delete from switches where id_switch = $id_switch";
	if(!$result = $db->query($sql)){ die('<div class="item wide gradient"><p class="number">2</p><br/>There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
	$showeditswitches=true;
	$showparameters = false;
}
if(isset($_POST['deletesensor'])) { 
	$id_sensor=($_POST['id_sensor']);
	$sql="delete from sensors where id_sensor = $id_sensor";
	if(!$result = $db->query($sql)){ die('<div class="item wide gradient"><p class="number">2</p><br/>There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
	$showeditsensors=true;
	$showparameters = false;
}
if(isset($_POST['editswitch'])) { 
	$id_switch=($_POST['id_switch']);
	$volgorde=($_POST['volgorde']);
	$type=($_POST['soort']);
	$favorite=($_POST['favorite']);
	$sql="update switches set volgorde = '$volgorde', favorite = '$favorite' where id_switch = $id_switch AND type like '$type'";
	if(!$result = $db->query($sql)){ die('<div class="item wide gradient"><p class="number">2</p><br/>There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
	$showeditswitches=true;
	$showparameters = false;
}
if(isset($_POST['editsensor'])) { 
	$id_sensor=($_POST['id_sensor']);
	$volgorde=($_POST['volgorde']);
	$type=($_POST['soort']);
	$favorite=($_POST['favorite']);
	if(isset($_POST['tempk'])) $tempk=($_POST['tempk']); else $tempk = 0;
	if(isset($_POST['tempw'])) $tempw=($_POST['tempw']); else $tempw = 0;
	if(isset($_POST['correctie'])) $correctie=($_POST['correctie']); else $correctie = 0;
	$sql="update sensors set volgorde = '$volgorde', favorite = '$favorite', tempk = '$tempk', tempw = '$tempw', correctie = '$correctie' where id_sensor = $id_sensor AND type like '$type'";
	if(!$result = $db->query($sql)){ die('<div class="item wide gradient"><p class="number">2</p><br/>There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
	$showeditsensors=true;
	$showparameters = false;
}
if(isset($_POST['upd'])) { 
	$variable=$db->real_escape_string($_POST['variable']);
	$value=$db->real_escape_string($_POST['value']);
	if(!isset($_POST['value'])) $value = 'no';
	echo '<div class="item wide gradient"><p class="number">2</p><br/>update settings set value = '.$value.' where variable like '.$variable.'</div>';
	$sql="update settings set value = '$value' where variable like '$variable'";
	if(!$result = $db->query($sql)){ die('<div class="item wide gradient"><p class="number">2</p><br/>There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
}
if(isset($_POST['add'])) { 
	$variable=$db->real_escape_string($_POST['variable']);
	$value=$db->real_escape_string($_POST['value']);
	$sql="insert into settings (variable, value) VALUES ('$variable', '$value')";
	if(!$result = $db->query($sql)){ die('<div class="item wide gradient"><p class="number">2</p><br/>There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
}
if(isset($_POST['cleansensorhistory'])) { 
	$id_sensor=($_POST['id_sensor']);
	$old = time()-($_POST['daystokeep']*86400);
	$old = date("Y-m-d H:i:s", $old);
	$sql="delete from history where id_sensor = $id_sensor and time < '$old'";
	if(!$result = $db->query($sql)){ die('<div class="item wide gradient"><p class="number">2</p><br/>There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
	echo '<div class="item wide gradient"><p class="number">7</p><br>'.$db->affected_rows.' Records verwijderd voor sensor '.$id_sensor.', tot datum '.$old.'</div>';
}
if(isset($_POST['cleanswitchhistory'])) { 
	$id_switch=($_POST['id_switch']);
	$old = time()-($_POST['daystokeep']*86400);
	$sql="delete from switchhistory where id_switch = $id_switch and timestamp < '$old'";
	if(!$result = $db->query($sql)){ die('<div class="item wide gradient"><p class="number">2</p><br/>There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
	echo '<div class="item wide gradient"><p class="number">7</p><br>'.$db->affected_rows.' Records verwijderd voor schakelaar '.$id_switch.', tot datum '.date("Y-m-d H:i:s", $old).'</div>';
}
if(isset($_POST['cleanhistoryfromremovedswitches'])) { 
	$sql="DELETE FROM `switchhistory` WHERE id_switch not in (select id_switch from switches where type not like 'scene')";
	if(!$result = $db->query($sql)){ die('<div class="item wide gradient"><p class="number">2</p><br/>There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
	echo '<div class="item wide gradient"><p class="number">7</p><br>'.$db->affected_rows.' Records verwijderd voor gewiste schakelaars</div>';
}
if(isset($_POST['cleanhistoryfromremovedsensors'])) { 
	$sql="DELETE FROM `history` WHERE id_sensor not in (select id_sensor from sensors where type not like 'temp')";
	if(!$result = $db->query($sql)){ die('<div class="item wide gradient"><p class="number">2</p><br/>There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
	echo '<div class="item wide gradient"><p class="number">7</p><br>'.$db->affected_rows.' Records verwijderd voor gewiste sensoren</div>';
}
if(isset($_POST['updateswitches'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">9</p>';
	include "history_to_sql.php";
	echo '</div>';
}
if(isset($_POST['actionscron'])) { 
	$showparameters=false;
	include "actionscron.php";
}
if(isset($_POST['showtest'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">2</p><br/><font color="#A00"> Output van test.php file. Handig om actions voor te bereiden</font></div>';
	include "test.php";
}
if(isset($_POST['updatedatabase'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">9</p>';
	include "update.php";
	echo '</div>';
}
if(isset($_POST['jsongetsensors'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">9</p><textarea rows="50" cols="68">';
	$json = file_get_contents($jsonurl.'get-sensors');
	echo $json.'</textarea></div>';
}
if(isset($_POST['jsongetstatus'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">9</p><textarea rows="50" cols="68">';
	$json = file_get_contents($jsonurl.'get-status');
	echo $json.'</textarea></div>';
}
if(isset($_POST['jsongetsuntimes'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">9</p><textarea rows="50" cols="68">';
	$json = file_get_contents($jsonurl.'suntimes/today');
	echo $json.'</textarea></div>';
}
if(isset($_POST['jsongettimers'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">9</p><textarea rows="50" cols="68">';
	$json = file_get_contents($jsonurl.'timers');
	echo $json.'</textarea></div>';
}
if(isset($_POST['jsongetnotifications'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">9</p><textarea rows="50" cols="68">';
	$json = file_get_contents($jsonurl.'notifications');
	echo $json.'</textarea></div>';
}
if(isset($_POST['jsongetswlist'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">9</p><textarea rows="50" cols="68">';
	$json = file_get_contents($jsonurl.'swlist');
	echo $json.'</textarea></div>';
}
}
if(isset($_SESSION['authenticated'])) {if ($_SESSION['authenticated'] == true) {$authenticated = true;}}
if(!isset($_SESSION['authenticated'])) {
		$error = null;
		if (!empty($_POST['username'])) {
			$username = empty($_POST['username']) ? null : $_POST['username'];
			$password = empty($_POST['password']) ? null : $_POST['password'];
			if ($username == $secretusername && $password == $secretpassword) {
			$_SESSION['authenticated'] = true;
			$authenticated = true;
		} else {
			$error = 'Incorrect username or password';
		}
	}
   if(isset($error)) echo '<div class="item wide gradient"><p class="number">2</p><br/>'.$error.'</div>';
	}
if($authenticated==true) {
//BEGIN AUTHENTICATED STUFF	

	echo '<div class="item gradient"><p class="number">1</p>
	<form method="post"><input type="submit" name="parameters" value="Parameters" class="abutton settings gradient"/></form>
	<form method="post"><input type="submit" name="showopmaak" value="Opmaak" class="abutton settings gradient"/></form>
	<form method="post"><input type="submit" name="showacties" value="Acties" class="abutton settings gradient"/></form>
	<form method="post"><input type="submit" name="editsensors" value="Bewerk sensoren" class="abutton settings gradient"/></form>
	<form method="post"><input type="submit" name="editswitches" value="Bewerk schakelaars" class="abutton settings gradient"/></form>
	<form method="post"><input type="submit" name="updateswitches" value="Import schakelaars, historiek" class="abutton settings gradient"/></form>
	<form method="post"><input type="submit" name="updatedatabase" value="Update Database" class="abutton settings gradient"/></form>
	<form method="post"><input type="submit" name="jsongetsensors" value="JSON get-sensors" class="abutton settings gradient"/></form>
	<form method="post"><input type="submit" name="jsongetstatus" value="JSON get-status" class="abutton settings gradient"/></form>
	<form method="post"><input type="submit" name="jsongetswlist" value="JSON swlist" class="abutton settings gradient"/></form>
	<form method="post"><input type="submit" name="jsongetsuntimes" value="JSON suntimes" class="abutton settings gradient"/></form>
	<form method="post"><input type="submit" name="jsongettimers" value="JSON timers" class="abutton settings gradient"/></form>
	<form method="post"><input type="submit" name="jsongetnotifications" value="JSON notifications" class="abutton settings gradient"/></form><br/>
	<form method="post"><input type="submit" name="cleandatabase" value="Clean Database" class="abutton settings gradient"/></form><br/>
	<form method="post"><input type="submit" name="showtest" value="Test" class="abutton settings gradient"/></form><br/>
	<form method="post"><input type="submit" name="actionscron" value="Actions cron" class="abutton settings gradient"/></form><br/><br/><br/>
	<br/><br/><br/><br/><br/><br/><form method="post"><input type="submit" name="logout" value="Uitloggen" class="abutton settings gradient"/></form><br/>
	Kijk op de <a href="https://github.com/Egregius/HomewizardPHP/wiki/Settings-en-parameters" target="_blank">wiki</a> voor uitleg.<br/></div>
	';

if($showparameters==true) {
	echo '<div class="item wide gradient"><center><table width="400px" style="text-align:center"><tbody>';
	$sql="select variable, value from settings where variable not like 'css_%' and variable not like 'positie_%' and variable not like 'toon_%' and variable not like 'actie_%' order by variable asc";
	if(!$result = $db->query($sql)){ die('<div class="item wide gradient">There was an error running the query [' . $db->error . ']</div>');}
	while($row = $result->fetch_assoc()){
		echo '<form method="post" >
		<tr>
			<td align="left">'.$row['variable'].'</td>
			<td><input type="hidden" name="variable" id="variable" value="'.$row['variable'].'"/>';
		if(in_array($row['variable'], array('developerjson'))) { echo '<textarea name="value" id="value" cols="32" rows="5">'.$row['value'].'</textarea>';} 
		else if(in_array($row['variable'], array('debug','developermode','toon_radiatoren','toon_regen','toon_scenes','toon_schakelaars','toon_sensoren','toon_somfy','toon_temperatuur','toon_wind','toon_energylink'))) {
			if($row['value']=="yes") {echo '<input type="hidden" name="value" id="value" value="no"/>';} else {echo '<input type="hidden" name="value" id="value" value="yes"/>';}
		echo '
		<section class="slider">	
			<input type="hidden" name="upd" value="update">
			<input type="checkbox" value="'.$row['value'].'" id="'.$row['variable'].'" name="'.$row['variable'].'" '; if($row['value']=="yes") {print 'checked';} print ' onChange="this.form.submit()"/>
			<label for="'.$row['variable'].'"></label>
		</section>
			' ;}
		else if(in_array($row['variable'], array('detailscenes'))) {
		echo '<input type="hidden" name="upd" value="update">
		<select name="value" class="abutton handje gradient settings" onChange="this.form.submit()" >	
			<option '.$row['value'].' selected>'.$row['value'].'</option>
			<option>yes</option>
			<option>optional</option>
			<option>no</option>
		</select>
			' ;}	
		else {echo '<input type="text" name="value" id="value" value="'.$row['value'].'" size="40px"/>' ;}
		
		echo '</td><td><input type="hidden" name="parameters" value="Parameters" />';
		if(!in_array($row['variable'], array('debug','developermode','detailscenes','toon_radiatoren','toon_regen','toon_scenes','toon_schakelaars','toon_sensoren','toon_somfy','toon_temperatuur','toon_wind','toon_energylink'))) echo '<input type="submit" name="upd" value="update" class="abutton gradient">';
		echo '</td></tr></form>';
	}
	$result->free();
	echo '<form method="post"><tr><td><input type="text" name="variable" id="variable" value=""/></td><td><input type="text" name="value" id="value" value=""/></td><td><input type="submit" name="add" value="add" class="abutton gradient"/></td></tr></form></tbody></table></center>';
}
if($showopmaak==true) {
	echo '<div class="item wide gradient"><center><table width="400px" style="text-align:center"><tbody>';
	$sql="select variable, value from settings where variable like 'css_%' OR variable like 'positie_%' OR variable like 'toon_%' order by variable asc";
	if(!$result = $db->query($sql)){ die('<div class="item wide gradient">There was an error running the query [' . $db->error . ']</div>');}
	while($row = $result->fetch_assoc()){
		echo '<form method="post" ><input type="hidden" name="showopmaak" value="Opmaak"/>
		<tr>
			<td align="left">'.$row['variable'].'</td>
			<td><input type="hidden" name="variable" id="variable" value="'.$row['variable'].'"/>';
		if((strpos($row['variable'], 'css') === 0)) { echo '<textarea name="value" id="value" cols="32" rows="5">'.$row['value'].'</textarea>';} 
		else if(in_array($row['variable'], array('debug','developermode','toon_radiatoren','toon_regen','toon_scenes','toon_schakelaars','toon_sensoren','toon_somfy','toon_temperatuur','toon_wind','toon_energylink'))) {
			if($row['value']=="yes") {echo '<input type="hidden" name="value" id="value" value="no"/>';} else {echo '<input type="hidden" name="value" id="value" value="yes"/>';}
		echo '
		<section class="slider">	
			<input type="hidden" name="upd" value="update">
			<input type="checkbox" value="'.$row['value'].'" id="'.$row['variable'].'" name="'.$row['variable'].'" '; if($row['value']=="yes") {print 'checked';} print ' onChange="this.form.submit()"/>
			<label for="'.$row['variable'].'"></label>
		</section>
			' ;}
		else if(in_array($row['variable'], array('detailscenes'))) {
		echo '<input type="hidden" name="upd" value="update">
		<select name="value" class="abutton handje gradient settings" onChange="this.form.submit()" >	
			<option '.$row['value'].' selected>'.$row['value'].'</option>
			<option>yes</option>
			<option>optional</option>
			<option>no</option>
		</select>
			' ;}	
		else {echo '<input type="text" name="value" id="value" value="'.$row['value'].'" size="40px"/>' ;}
		
		echo '</td><td>';
		if(!in_array($row['variable'], array('debug','developermode','detailscenes','toon_radiatoren','toon_regen','toon_scenes','toon_schakelaars','toon_sensoren','toon_somfy','toon_temperatuur','toon_wind','toon_energylink'))) echo '<input type="submit" name="upd" value="update" class="abutton gradient">';
		echo '</td></tr></form>';
	}
	$result->free();
	echo '<form method="post"><tr><td><input type="hidden" name="showopmaak" value="Opmaak"/><input type="text" name="variable" id="variable" value=""/></td><td><input type="text" name="value" id="value" value=""/></td><td><input type="submit" name="add" value="add" class="abutton gradient"/></td></tr></form></tbody></table></center>';
}
if($showacties==true) {
	echo '<div class="item wide gradient"><p class="number">9</p><center><table width="400px" style="text-align:center"><tbody>';
	$sql="select variable, value from settings where variable like 'actie_%' order by variable asc";
	if(!$result = $db->query($sql)){ die('<div class="error gradient">There was an error running the query [' . $db->error . ']</div>');}
	while($row = $result->fetch_assoc()){
		echo '<form method="post" ><input type="hidden" name="showacties" value="Acties"/>
		<tr>
			<td align="left">'.$row['variable'].'</td>
			<td><input type="hidden" name="variable" id="variable" value="'.$row['variable'].'"/>';
		if($row['value']=="yes") {echo '<input type="hidden" name="value" id="value" value="no"/>';} else {echo '<input type="hidden" name="value" id="value" value="yes"/>';}
		echo '
		<section class="slider">	
			<input type="hidden" name="upd" value="update">
			<input type="checkbox" value="'.$row['value'].'" id="'.$row['variable'].'" name="'.$row['variable'].'" '; if($row['value']=="yes") {print 'checked';} print ' onChange="this.form.submit()"/>
			<label for="'.$row['variable'].'"></label>
		</section>
		</td></tr></form>';
	}
	$result->free();
	echo '<form method="post"><tr><td align="left"><input type="hidden" name="showacties" value="Acties"/><input type="text" name="variable" id="variable" value="actie_" size="40"/></td><td><input type="submit" name="add" value="add" class="abutton gradient"/></td></tr></form></tbody></table></center>';
}
if($showeditswitches==true) {
	echo '<div class="item wide gradient"><center><table width="500px" style="text-align:center"><thead><tr><th>id</th><th>Name</th><th>type</th><th>favorite</th><th>order</th></thead><tbody>';
	$sql="select id_switch, name, type, favorite, volgorde from switches order by type asc, volgorde asc, name asc";
	if(!$result = $db->query($sql)){ die('<div class="error gradient">There was an error running the query [' . $db->error . ']</div>');}
	while($row = $result->fetch_assoc()){
		echo '
			<tr>
				<td>'.$row['id_switch'].'</td>
				<td>'.$row['name'].'</td>
				<td>'.$row['type'].'</td>
				<td>';
				
		echo '
		<section class="slider"><form method="post">';
		if($row['favorite']=="yes") {echo '<input type="hidden" name="favorite" id="favorite" value="no"/>';} else {echo '<input type="hidden" name="favorite" id="favorite" value="yes"/>';}
		echo '
			<input type="hidden" name="editswitch" value="update">
			<input type="hidden" name="id_switch" id="id_switch" value="'.$row['id_switch'].'"/>
			<input type="hidden" name="soort" id="soort" value="'.$row['type'].'"/>
			<input type="hidden" name="volgorde" id="volgorde" value="'.$row['volgorde'].'" size="5"/>
			<input type="checkbox" value="'.$row['favorite'].'" id="'.$row['type'].$row['id_switch'].'" name="'.$row['id_switch'].'" '; if($row['favorite']=="yes") {print 'checked';} print ' onChange="this.form.submit()"/>
			<label for="'.$row['type'].$row['id_switch'].'"></label>
		</form></section>
		</td>
				<td><form method="post">
				<input type="hidden" name="id_switch" id="id_switch" value="'.$row['id_switch'].'"/>
				<input type="hidden" name="soort" id="soort" value="'.$row['type'].'"/>
				<input type="text" name="volgorde" id="volgorde" value="'.$row['volgorde'].'" size="5"/>
				<input type="hidden" name="favorite" id="favorite" value="'.$row['favorite'].'" /></td>
				<td><input type="submit" name="editswitch" value="Update" class="abutton gradient"><input type="submit" name="deleteswitch" value="Wissen" class="abutton gradient"></td>
			</form></tr>';
	}
	$result->free();
	echo '</tbody></table></center>';
}
if($showeditsensors==true) {
	echo '<div class="item wide gradient"><center><table width="500px" style="text-align:center"><thead><tr><th>id</th><th>Name</th><th>type</th><th>favorite</th><th>order</th></thead><tbody>';
	$sql="select id_sensor, name, type, favorite, volgorde from sensors where type not like 'temp' order by volgorde asc, name asc";
	if(!$result = $db->query($sql)){ die('<div class="error gradient">There was an error running the query [' . $db->error . ']</div>');}
	while($row = $result->fetch_assoc()){
		echo '
			<tr>
				<td>'.$row['id_sensor'].'</td>
				<td>'.$row['name'].'</td>
				<td>'.$row['type'].'</td>
				<td>';
				
		echo '
		<section class="slider"><form method="post">';
		if($row['favorite']=="yes") {echo '<input type="hidden" name="favorite" id="favorite" value="no"/>';} else {echo '<input type="hidden" name="favorite" id="favorite" value="yes"/>';}
		echo '
			<input type="hidden" name="editsensor" value="update">
			<input type="hidden" name="id_sensor" id="id_sensor" value="'.$row['id_sensor'].'"/>
			<input type="hidden" name="soort" id="soort" value="'.$row['type'].'"/>
			<input type="hidden" name="volgorde" id="volgorde" value="'.$row['volgorde'].'" size="5"/>
			<input type="checkbox" value="'.$row['favorite'].'" id="'.$row['type'].$row['id_sensor'].'" name="'.$row['id_sensor'].'" '; if($row['favorite']=="yes") {print 'checked';} print ' onChange="this.form.submit()"/>
			<label for="'.$row['type'].$row['id_sensor'].'"></label>
		</form></section>
		</td>
				<td><form method="post">
				<input type="hidden" name="editsensor" value="update">
				<input type="hidden" name="id_sensor" id="id_sensor" value="'.$row['id_sensor'].'"/>
				<input type="hidden" name="soort" id="soort" value="'.$row['type'].'"/>
				<input type="text" name="volgorde" id="volgorde" value="'.$row['volgorde'].'" size="5"/>
				<input type="hidden" name="favorite" id="favorite" value="'.$row['favorite'].'" /></td>
				<td><input type="submit" name="editsensor" value="Update" class="abutton gradient">
				<input type="submit" name="deletesensor" value="Wissen" class="abutton gradient"></td>
			</tr></form>';
	}
	$result->free();
	echo '</tbody></table></center></div>';
	echo '<div class="item wide gradient"><center><table width="500px" style="text-align:center"><thead><tr><th>id</th><th>Name</th><th>type</th><th>favorite</th><th>opties</th></thead><tbody>';
	$sql="select id_sensor, name, type, favorite, volgorde, tempk, tempw, correctie from sensors where type like 'temp' order by volgorde asc, name asc";
	if(!$result = $db->query($sql)){ die('<div class="error gradient">There was an error running the query [' . $db->error . ']</div>');}
	while($row = $result->fetch_assoc()){
		echo '
			<tr>
				<td style="border-bottom:1px solid black">'.$row['id_sensor'].'</td>
				<td style="border-bottom:1px solid black">'.$row['name'].'</td>
				<td style="border-bottom:1px solid black">'.$row['type'].'</td>
				<td style="border-bottom:1px solid black">';
				
		echo '
		<section class="slider"><form method="post">';
		if($row['favorite']=="yes") {echo '<input type="hidden" name="favorite" id="favorite" value="no"/>';} else {echo '<input type="hidden" name="favorite" id="favorite" value="yes"/>';}
		echo '
			<input type="hidden" name="editsensor" value="update">
			<input type="hidden" name="id_sensor" id="id_sensor" value="'.$row['id_sensor'].'"/>
			<input type="hidden" name="soort" id="soort" value="'.$row['type'].'"/>
			<input type="hidden" name="volgorde" id="volgorde" value="'.$row['volgorde'].'" size="5"/>
			<input type="checkbox" value="'.$row['favorite'].'" id="'.$row['type'].$row['id_sensor'].'" name="'.$row['id_sensor'].'" '; if($row['favorite']=="yes") {print 'checked';} print ' onChange="this.form.submit()"/>
			<label for="'.$row['type'].$row['id_sensor'].'"></label>
		</form></section>
		</td>
				<td align="right" style="border-bottom:1px solid black"><form method="post">
				<input type="hidden" name="editsensor" value="update">
				<input type="hidden" name="id_sensor" id="id_sensor" value="'.$row['id_sensor'].'"/>
				<input type="hidden" name="soort" id="soort" value="'.$row['type'].'"/>
				<label for="volgorde">Volgorde</label><input type="text" name="volgorde" id="volgorde" value="'.$row['volgorde'].'" size="5"/><br/>
				<label for="tempk">tempk</label><input type="text" name="tempk" id="tempk" value="'.$row['tempk'].'" size="5"/><br/>
				<label for="tempw">tempw</label><input type="text" name="tempw" id="tempw" value="'.$row['tempw'].'" size="5"/><br/>
				<label for="correctie">correctie</label><input type="text" name="correctie" id="correctie" value="'.$row['correctie'].'" size="5"/>
				<input type="hidden" name="favorite" id="favorite" value="'.$row['favorite'].'" />
			</td>
			<td  style="border-bottom:1px solid black"><input type="submit" name="editsensortemp" value="Update" class="abutton gradient">
				<input type="submit" name="deletesensor" value="Wissen" class="abutton gradient"></td>
			</tr></form>';
	}
	$result->free();
	echo '</tbody></table></center></div>';
}
if($cleandatabase==true) {
	echo '<div class="item wide gradient"><br/>
			<form method="post"><input type="hidden" name="cleandatabase" value="cleandatabase"/>
				<input type="submit" name="cleanhistoryfromremovedswitches" value="Verwijder historiek van gewiste schakelaars" class="abutton settings gradient"/>
			</form>
			<form method="post"><input type="hidden" name="cleandatabase" value="cleandatabase"/>
				<input type="submit" name="cleanhistoryfromremovedsensors" value="Verwijder historiek van gewiste sensoren" class="abutton settings gradient"/>
			</form>
		</div>';
	echo '<div class="item wide gradient"><center>
	<br/><big><b>Verwijder oude historieken van sensoren.</b></big><br/><br/>
	<table width="500px" style="text-align:center"><thead><tr><th>id</th><th>Naam</th><th>Aantal Records</th><th>Dagen te behouden</th></thead><tbody>';
	$sql="select id_sensor, name, type volgorde from sensors where type not like 'temp' order by volgorde asc, name asc";
	if(!$result = $db->query($sql)){ die('<div class="error gradient">There was an error running the query [' . $db->error . ']</div>');}
	while($row = $result->fetch_assoc()){
		$id_sensor = $row['id_sensor'];
		$sqlcount = "select count(id_sensor) as aantal from history where id_sensor = $id_sensor;";
		if(!$resultcount = $db->query($sqlcount)){ die('<div class="error gradient">There was an error running the query [' . $db->error . ']</div>');}
		$rowcount = $resultcount->fetch_assoc();
		echo '
			<tr>
				<td>'.$row['id_sensor'].'</td>
				<td>'.$row['name'].'</td>
				<td>'.$rowcount['aantal'].'</td>
				<td>
					<form method="post">
						<input type="hidden" name="cleandatabase" value="cleandatabase"/>
						<input type="hidden" name="id_sensor" value="'.$row['id_sensor'].'" />
						<input type="text" name="daystokeep" value="365" size="5"/>
						<input type="submit" name="cleansensorhistory" value="Clean" class="abutton gradient"/>
					</form>
				</td>
			</tr>';
				
		
	}
	$resultcount->free();
	$result->free();
	echo '</tbody></table></center></div>';
	echo '<div class="item wide gradient"><center>
	<br/><big><b>Verwijder oude historieken van schakelaars.</b></big><br/><br/>
	<table width="500px" style="text-align:center"><thead><tr><th>id</th><th>Naam</th><th>Aantal Records</th><th>Dagen te behouden</th></thead><tbody>';
	$sql="select id_switch, name, type from switches where type not like 'scene' order by volgorde asc, name asc";
	if(!$result = $db->query($sql)){ die('<div class="error gradient">There was an error running the query [' . $db->error . ']</div>');}
	while($row = $result->fetch_assoc()){
		$id_switch = $row['id_switch'];
		$sqlcount = "select count(id_switch) as aantal from switchhistory where id_switch = $id_switch;";
		if(!$resultcount = $db->query($sqlcount)){ die('<div class="error gradient">There was an error running the query [' . $db->error . ']</div>');}
		$rowcount = $resultcount->fetch_assoc();
		echo '
			<tr>
				<td>'.$row['id_switch'].'</td>
				<td>'.$row['name'].'</td>
				<td>'.$rowcount['aantal'].'</td>
				<td>
					<form method="post">
						<input type="hidden" name="cleandatabase" value="cleandatabase"/>
						<input type="hidden" name="id_switch" value="'.$row['id_switch'].'" />
						<input type="text" name="daystokeep" value="365" size="5"/>
						<input type="submit" name="cleanswitchhistory" value="Clean" class="abutton gradient"/>
					</form>
				</td>
			</tr>';
				
		
	}
	$resultcount->free();
	$result->free();
	echo '</tbody></table></center></div>';
	
}
//END AUTHENTICATED STUFF	

} else {
	print '<div class="error gradient">Log in:<br/><br/>';
	print '<br/><br/>
	<form method="post">
	<label for="username">Username: </label><input type="text" name="username" size="20" /><br/>
	<label for="password">Password: </label><input type="password" name="password" size="20" /><br/>
	<input type="submit" value="login" class="abutton settings gradient"/><br/>
	</form></div>';
}
print '</div></div>';
include "footer.php";
?>
