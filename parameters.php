<?php

// Make database connection ('server', 'database', 'password', 'user') 
$db = new mysqli('server', 'database', 'password', 'user');
if($db->connect_errno > 0){ die('Unable to connect to database [' . $db->connect_error . ']');}

// Url to homewizard "http://address:port/password/"
$jsonurl = "http://address:port/password/";

//simple authentication when not at home
$valid_passwords = array ("home" => "wizard");

//IP Address array that bypass authentication
$acceptedip=array("1.2.3.4","5.6.7.8");
//USername and password for external access
$secretusername = '1234';
$secretpassword = '1234';

$authenticated = false;
//if(in_array($_SERVER['REMOTE_ADDR'], $acceptedip)) $authenticated = true;
session_start();
if(isset($_SESSION['authenticated'])) {
	if ($_SESSION['authenticated'] == true) {
		$authenticated = true;
	}
}
if($authenticated==true) error_reporting(E_ALL); ini_set("display_errors", "on"); 
?>