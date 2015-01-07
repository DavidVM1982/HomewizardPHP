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
   echo '<p class="error">'.$error.'</p>';
	}
if($debug=='yes') {print '<div class="row"><div class="span_3"><span class="error"><br/>POST = ';print_r($_POST);print '</div></div>';}
$showmenu = true;
$showaddnew = false;
$dosaveaction = false;
$dodeleteaction = false;
$doaddsensor = false;
if(isset($_POST['addaction'])) $showaddnew=true;
if(isset($_POST['saveaction'])) $dosaveaction=true;
if(isset($_POST['deleteaction'])) $dodeleteaction=true;
if(isset($_POST['addsensor'])) $doaddsensor=true;
if($authenticated==true) {
//BEGIN AUTHENTICATED STUFF	
if($showaddnew==true) {
	echo '<div class="row"><div class="span_3">
	<form method="post">
	<label>Name: </label><input type="text" name="name" size="50"><br/>
	<input type="submit" name="saveaction" value="Save action" class="abutton"/></form><br/>
	</div></div>';
}
if($dosaveaction==true) {
	$name=$db->real_escape_string($_POST['name']);
	$sql="insert into actions (name) VALUES ('$name')";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
}
if($doaddsensor==true) {
	$id_action=$_POST['id'];
	$id_sensor=$_POST['id_sensor'];
	$sql="insert into action_sensors (id_action, id_sensor) VALUES ('$id_action', '$id_sensor')";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
}
if($dodeleteaction==true) {
	$id=$_POST['id'];
	$sql="delete from actions where id = $id";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
	$sql="delete from action_switches where id_action = $id";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
	$sql="delete from action_sensors where id_action = $id";
	if(!$result = $db->query($sql)){ die('There was an error running the query '.$sql.'<br/>[' . $db->error . ']');}
}
if($showmenu==true) {
	echo '<div class="row"><div class="span_3">
	<form method="post"><input type="submit" name="addaction" value="Add action" class="abutton"/></form><br/>
	</div></div>';
	$sql="select id, name from actions order by name asc";
	if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
	while($row = $result->fetch_assoc()){
		echo '<div class="row"><div class="span_3"><h2>'.$row['id'].': '.$row['name'].'<h2>
		
		<form action="#" method="post"><input type="hidden" name="id" value="'.$row['id'].'"><select name="id_sensor" class="abutton" > ';
		$id_action = $row['id'];
		$sqlsensors="select id_sensor, name, type, favorite, volgorde from sensors WHERE id_sensor not in (SELECT id_sensor from action_sensors where id_action = $id_action) order by volgorde asc, favorite desc, name asc";
		if(!$resultsensors = $db->query($sqlsensors)){ die('There was an error running the query [' . $db->error . ']');}
		while($rowsensors = $resultsensors->fetch_assoc()){
			print '<option value="'.$rowsensors['id_sensor'].'">'.$rowsensors['name'].'</option>';
		}
		$resultsensors->free();
		echo '</select>
		<input type="submit" name="addsensor" value="Add sensor" class="abutton"/><br/>
		<input type="submit" name="deleteaction" value="Delete action" class="abutton"/></form></div></div>';
	}
	$result->free();
}
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
$db->close();
include "footer.php";
?>