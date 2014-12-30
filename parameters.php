<?php
// Make database connection ('server', 'database', 'password', 'user') 
$db = new mysqli('server', 'database', 'password', 'user');
if($db->connect_errno > 0){ die('Unable to connect to database [' . $db->connect_error . ']');}

	$sql="select variable, value from settings order by variable asc";
	if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
	$acceptedips = array();
	while($row = $result->fetch_assoc()){
		$$row['variable'] = $row['value'];
		if (strpos($row['variable'], 'acceptedip') === 0) { 
			array_push($acceptedips, $row['value']);
		}
	}
	$result->free();

$authenticated = false;
if(in_array($_SERVER['REMOTE_ADDR'], $acceptedips)) $authenticated = true; 
session_start();
if(isset($_SESSION['authenticated'])) {
	if ($_SESSION['authenticated'] == true) {
		$authenticated = true;
	}
}
if($authenticated==true) error_reporting(E_ALL); ini_set("display_errors", "on"); 
?>