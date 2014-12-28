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
?>