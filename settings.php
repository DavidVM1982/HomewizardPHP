<?php
include "header.php";
print '<div class="threecolumn"><div class="isotope">';	
$showparameters = true;
$showupdatesensors  = false;
$showeditswitches = false;
$showeditsensors = false;
if(isset($_POST['updatesensors'])) {$showupdatesensors = true; $showparameters = false;}
if(isset($_POST['editswitches'])) {$showeditswitches = true; $showparameters = false;}
if(isset($_POST['editsensors'])) {$showeditsensors = true; $showparameters = false;}

if(isset($_POST['logout'])) {session_destroy();$authenticated = false;header("location:settings.php");exit();}
if($authenticated==true) {
if(isset($_POST['deleteswitch'])) { 
	$id_switch=($_POST['id_switch']);
	$sql="delete from switches where id_switch = $id_switch";
	if(!$result = $db->query($sql)){ die('<div class="error gradient">There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
	$showeditswitches=true;
	$showparameters = false;
}
if(isset($_POST['deletesensor'])) { 
	$id_sensor=($_POST['id_sensor']);
	$sql="delete from sensors where id_sensor = $id_sensor";
	if(!$result = $db->query($sql)){ die('<div class="error gradient">There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
	$showeditsensors=true;
	$showparameters = false;
}
if(isset($_POST['editswitch'])) { 
	$id_switch=($_POST['id_switch']);
	$volgorde=($_POST['volgorde']);
	$type=($_POST['soort']);
	$favorite=($_POST['favorite']);
	$sql="update switches set volgorde = '$volgorde', favorite = '$favorite' where id_switch = $id_switch AND type like '$type'";
	if(!$result = $db->query($sql)){ die('<div class="error gradient">There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
	$showeditswitches=true;
	$showparameters = false;
}
if(isset($_POST['editsensor'])) { 
	$id_sensor=($_POST['id_sensor']);
	$volgorde=($_POST['volgorde']);
	$type=($_POST['soort']);
	$favorite=($_POST['favorite']);
	$sql="update sensors set volgorde = '$volgorde', favorite = '$favorite' where id_sensor = $id_sensor AND type like '$type'";
	if(!$result = $db->query($sql)){ die('<div class="error gradient">There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
	$showeditsensors=true;
	$showparameters = false;
}
if(isset($_POST['upd'])) { 
	$variable=$db->real_escape_string($_POST['variable']);
	$value=$db->real_escape_string($_POST['value']);
	if(!isset($_POST['value'])) $value = 'no';
	echo 'update settings set value = '.$value.' where variable like '.$variable.'';
	$sql="update settings set value = '$value' where variable like '$variable'";
	if(!$result = $db->query($sql)){ die('<div class="error gradient">There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
}
if(isset($_POST['add'])) { 
	$variable=$db->real_escape_string($_POST['variable']);
	$value=$db->real_escape_string($_POST['value']);
	$sql="insert into settings (variable, value) VALUES ('$variable', '$value')";
	if(!$result = $db->query($sql)){ die('<div class="error gradient">There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
}
if(isset($_POST['updateswitches'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">2</p>';
	include "history_to_sql.php";
	echo '</div>';
}
if(isset($_POST['actionscron'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">2</p>';
	include "actionscron.php";
	echo '</div>';
}
if(isset($_POST['updatedatabase'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">2</p>';
	include "update.php";
	echo '</div>';
}
if(isset($_POST['jsongetsensors'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">2</p><textarea rows="50" cols="68">';
	$json = file_get_contents($jsonurl.'get-sensors');
	echo $json.'</textarea></div>';
}
if(isset($_POST['jsongetstatus'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">2</p><textarea rows="50" cols="68">';
	$json = file_get_contents($jsonurl.'get-status');
	echo $json.'</textarea></div>';
}
if(isset($_POST['jsongetsuntimes'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">2</p><textarea rows="50" cols="68">';
	$json = file_get_contents($jsonurl.'suntimes/today');
	echo $json.'</textarea></div>';
}
if(isset($_POST['jsongettimers'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">2</p><textarea rows="50" cols="68">';
	$json = file_get_contents($jsonurl.'timers');
	echo $json.'</textarea></div>';
}
if(isset($_POST['jsongetnotifications'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">2</p><textarea rows="50" cols="68">';
	$json = file_get_contents($jsonurl.'notifications');
	echo $json.'</textarea></div>';
}
if(isset($_POST['jsongetswlist'])) { 
	$showparameters=false;
	echo '<div class="item wide gradient"><p class="number">2</p><textarea rows="50" cols="68">';
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
   if(isset($error)) echo '<div class="error gradient">'.$error.'</div>';
	}
if($authenticated==true) {
//BEGIN AUTHENTICATED STUFF	

	echo '<div class="item gradient"><p class="number">1</p>
	<form method="post"><input type="submit" name="parameters" value="Parameters" class="abutton settings gradient"/></form>
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
	<form method="post"><input type="submit" name="actionscron" value="Actions cron" class="abutton settings gradient"/></form><br/>
	<br/><br/><br/><br/><br/><br/><form method="post"><input type="submit" name="logout" value="Uitloggen" class="abutton settings gradient"/></form><br/>
	Kijk op de <a href="https://github.com/Egregius/HomewizardPHP/wiki/Settings-en-parameters" target="_blank">wiki</a> voor uitleg.<br/></div>
	';

if($showeditsensors==true || $showeditswitches==true || $showparameters==true || $showupdatesensors==true) echo '<div class="item wide gradient">';
if($showparameters==true) {
	echo '<center><table width="400px" style="text-align:center"><tbody>';
	$sql="select variable, value from settings order by variable asc";
	if(!$result = $db->query($sql)){ die('<div class="error gradient">There was an error running the query [' . $db->error . ']</div>');}
	while($row = $result->fetch_assoc()){
		echo '<form method="post" >
		<tr>
			<td align="left">'.$row['variable'].'</td>
			<td><input type="hidden" name="variable" id="variable" value="'.$row['variable'].'"/>';
		if(in_array($row['variable'], array('developerjson'))) { echo '<textarea name="value" id="value" cols="32" rows="5">'.$row['value'].'</textarea>';} 
		else if(in_array($row['variable'], array('debug','developermode'))) {
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
		if(!in_array($row['variable'], array('debug','developermode','detailscenes'))) echo '<input type="submit" name="upd" value="update" class="abutton gradient">';
		echo '</td></tr></form>';
	}
	$result->free();
	echo '<form method="post"><tr><td><input type="text" name="variable" id="variable" value=""/></td><td><input type="text" name="value" id="value" value=""/></td><td><input type="submit" name="add" value="add" class="abutton gradient"/></td></tr></form></tbody></table></center>';
}
if($showeditswitches==true) {
	echo '<center><table width="500px" style="text-align:center"><thead><tr><th>id</th><th>Name</th><th>type</th><th>favorite</th><th>order</th></thead><tbody>';
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
	echo '<center><table width="500px" style="text-align:center"><thead><tr><th>id</th><th>Name</th><th>type</th><th>favorite</th><th>order</th></thead><tbody>';
	$sql="select id_sensor, name, type, favorite, volgorde from sensors order by volgorde asc, name asc";
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
	echo '</tbody></table></center>';
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
