<?php
include "header.php";
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
   echo '<section class="error">'.$error.'</section>';
	}
if($authenticated==true && $debug=='yes') {print '<section class="error"><br/>POST = ';print_r($_POST);print '</section>';}
if($authenticated==true) {
//BEGIN AUTHENTICATED STUFF	
echo '
<form method="post"><input type="submit" name="actions" value="Actions" class="abutton settings gradient"/><input type="submit" name="addaction" value="Add action" class="abutton settings gradient"/></form><br/>
';
if(isset($_POST['addaction'])) {
	echo '<div class="actions gradient">
	<form method="post">
	<label>Naam: </label><input type="text" name="name" size="50"><br/>
	<input type="submit" name="saveaction" value="Save action" class="abutton settings gradient"/></form><br/>
	</div>';
}

if(isset($_POST['saveaction'])) {
	$name=$db->real_escape_string($_POST['name']);
	$sql="insert into actions (naam) VALUES ('$name')";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
}

if(isset($_POST['addsensorin'])) {
	$id_action=$_POST['id'];
	$id_sensor=$_POST['id_sensor'];
	$sql="insert into action_sensors_in (id_action, id_sensor) VALUES ('$id_action', '$id_sensor')";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
}

if(isset($_POST['addswitchin'])) {
	$id_action=$_POST['id'];
	$id_switch=$_POST['id_switch'];
	$sql="insert into action_switches_in (id_action, id_switch) VALUES ('$id_action', '$id_switch')";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
}

if(isset($_POST['removesensorin'])) {
	$id_action=$_POST['id'];
	$id_sensor=$_POST['id_sensor'];
	$sql="delete from action_sensors_in WHERE id_action = '$id_action' AND id_sensor = '$id_sensor'";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
}

if(isset($_POST['removeswitchin'])) {
	$id_action=$_POST['id'];
	$id_switch=$_POST['id_switch'];
	$sql="delete from action_switches_in WHERE id_action = '$id_action' AND id_switch = '$id_switch'";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
}

if(isset($_POST['deleteaction'])) {
	$id=$_POST['id'];
	$sql="delete from actions where id = $id";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
	$sql="delete from action_switches_in where id_action = $id";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
	$sql="delete from action_sensors_in where id_action = $id";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
	$sql="delete from action_switches_uit where id_action = $id";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
	$sql="delete from action_sensors_uit where id_action = $id";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
}

$sql="select id, naam from actions order by naam asc";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
//ga door de acties
while($row = $result->fetch_assoc()){
	echo '<div class="actions gradient"><h2>'.$row['naam'].'</h2>';
	//Toon sensors in
	echo '
	<table width="100%" align="center">
		<thead>
			<tr>
				<th width="50%">IN</th>
				<th width="50%">OUT</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<table align="center">';
						$id_action = $row['id'];
						$sqlsensorsin="select id_sensor, name, type, favorite, volgorde from sensors WHERE id_sensor in (SELECT id_sensor from action_sensors_in where id_action = $id_action) order by volgorde asc, favorite desc, name asc";
						if(!$resultsensorsin = $db->query($sqlsensorsin)){ die('There was an error running the query [' . $db->error . ']');}
						while($rowsensorsin = $resultsensorsin->fetch_assoc()){
						print '<tr>
								<td align="left">'.$rowsensorsin['type'].'</td>
								<td align="left">'.$rowsensorsin['name'].'</td>
								<td>
									<form action="#" method="post">
									<input type="hidden" name="id" value="'.$row['id'].'">
									<input type="hidden" name="id_sensor" value="'.$rowsensorsin['id_sensor'].'">
									<input type="submit" name="removesensorin" value="Remove" class="abutton  gradient"/>
									</form>
								</td>
							</tr>';
						}
					$resultsensorsin->free();
					//Toon schakelaars in
					$id_action = $row['id'];
					$sqlswitchin="select id_switch, name, type, favorite, volgorde from switches WHERE id_switch in (SELECT id_switch from action_switches_in where id_action = $id_action) order by volgorde asc, favorite desc, name asc";
					if(!$resultswitchin = $db->query($sqlswitchin)){ die('There was an error running the query [' . $db->error . ']');}
					while($rowswitchin = $resultswitchin->fetch_assoc()){
						print '<tr>
								<td align="left">'.$rowswitchin['type'].'</td>
								<td align="left">'.$rowswitchin['name'].'</td>
								<td>
									<form action="#" method="post">
									<input type="hidden" name="id" value="'.$row['id'].'">
									<input type="hidden" name="id_switch" value="'.$rowswitchin['id_switch'].'">
									<input type="submit" name="removeswitchin" value="Remove" class="abutton  gradient"/>
									</form>
								</td>
							</tr>';
					}
					$resultswitchin->free();
			echo '</table>';
	//zoek sensors nog niet toegevoegd
	echo '<form action="#" method="post"><input type="hidden" name="id" value="'.$row['id'].'">
	<select name="id_sensor" class="abutton settings gradient" onChange="this.form.submit()"><option selected disabled>Voeg een sensor toe</option> ';
	$sqlsensors="select id_sensor, name, type, favorite, volgorde from sensors WHERE id_sensor not in (SELECT id_sensor from action_sensors_in where id_action = $id_action) order by volgorde asc, favorite desc, name asc";
	if(!$resultsensors = $db->query($sqlsensors)){ die('There was an error running the query [' . $db->error . ']');}
	while($rowsensors = $resultsensors->fetch_assoc()){
		print '<option value="'.$rowsensors['id_sensor'].'">'.$rowsensors['name'].'</option>';
	}
	$resultsensors->free();
	echo '</select>
	<input type="hidden" name="addsensorin" value="Add sensor" class="abutton gradient"/></form>';
	//zoek schakelaars nog niet toegevoegd
	echo '<form action="#" method="post"><input type="hidden" name="id" value="'.$row['id'].'">
	<select name="id_switch" class="abutton settings gradient" onChange="this.form.submit()"><option selected disabled>Voeg een schakelaar toe</option> ';
	$sqlswitches="select id_switch, name, type, favorite, volgorde from switches WHERE id_switch not in (SELECT id_switch from action_switches_in where id_action = $id_action) order by volgorde asc, favorite desc, name asc";
	if(!$resultswitches = $db->query($sqlswitches)){ die('There was an error running the query [' . $db->error . ']');}
	while($rowswitches = $resultswitches->fetch_assoc()){
		print '<option value="'.$rowswitches['id_switch'].'">'.$rowswitches['name'].'</option>';
	}
	$resultsensors->free();
	echo '</select>
	<input type="hidden" name="addswitchin" value="Add switch" class="abutton gradient"/></form>';
	echo '</td><td></td></tbody></table>
	<form action="#" method="post"><input type="hidden" name="id" value="'.$row['id'].'"><input type="submit" name="deleteaction" value="Delete action" class="abutton settings gradient"/></form></div>';
}
$result->free();
echo '</div>';
//END AUTHENTICATED STUFF	
} else {
	echo '<br/><br/>Log in:<br/><br/>';
	echo '<br/><br/>
	<form method="post">
	<label for="username">Username: </label><input type="text" name="username" size="20" /><br/>
	<label for="password">Password: </label><input type="password" name="password" size="20" /><br/>
	<input type="submit" value="login" class="abutton"/><br/>
	</form>';
}
include "footer.php";
?>