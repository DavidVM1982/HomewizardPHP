<?php
include "header.php";
print '<div class="settings">';
if(isset($_POST['logout'])) {session_destroy();$authenticated = false;header("location:settings.php");exit();}

if(isset($_SESSION['authenticated'])) {if ($_SESSION['authenticated'] == true) {$authenticated = true;}}
if(!isset($_SESSION['authenticated'])) {
		$error = null;
		if (!empty($_POST)) {
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
if($authenticated==true) {
	print '<br/><br/>Logged in<br/><br/>';
	print '<form method="post"><input type="submit" name="logout" value="logout" class="abutton"/></form>';
} else {
	print '<br/><br/>Log in:<br/><br/>';
	print '<br/><br/><form method="post"><input type="text" name="username" /><input type="password" name="password" /><input type="submit" value="login" class="abutton"/></form>';
}
print '</div>';
include "footer.php";
?>d'];
			if ($username == $secretusername && $password == $secretpassword) {
			$_SESSION['authenticated'] = true;
			$authenticated = true;
		} else {
			$error = 'Incorrect username or password';
		}
	}
   echo '<p class="error">'.$error.'</p>';
	}
if($authenticated==true) {
	echo '<center><table width="400px" style="text-align:center"><tbody>';
	$sql="select variable, value from settings order by variable asc";
	if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
	while($row = $result->fetch_assoc()){
		echo '<form method="post"><tr><td>'.$row['variable'].'</td><td><input type="hidden" name="variable" id="variable" value="'.$row['variable'].'"><input type="text" name="value" id="value" value="'.$row['value'].'"></td><td><input type="submit" name="upd" value="upd" class="btn btn-default"></td></tr></form>';
	}
	$result->free();
	echo '<form method="post"><tr><td><input type="text" name="variable" id="variable" value=""></td><td><input type="text" name="value" id="value" value=""></td><td><input type="submit" name="add" value="add" class="btn btn-default"></td></tr></form></tbody></table></center>';
	
	print '<form method="post"><input type="submit" name="logout" value="logout" class="abutton"/></form>';
} else {
	print '<br/><br/>Log in:<br/><br/>';
	print '<br/><br/><form method="post"><input type="text" name="username" /><input type="password" name="password" /><input type="submit" value="login" class="abutton"/></form>';
}
$db->close();
print '</div></div>';
include "footer.php";
?>