<?php include "parameters.php"; 
$sql="select variable, value from settings order by variable asc";
	if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
	$acceptedips = array();
	while($row = $result->fetch_assoc()){
		if (strpos($row['variable'], 'acceptedip') === 0) { 
			array_push($acceptedips, $row['value']);
		} else {
			$$row['variable'] = $row['value'];
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
if($authenticated==true && $debug=='yes') {
	error_reporting(E_ALL); 
	ini_set("display_errors", "on");
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="expires" content="-1" />
<meta http-equiv="expires" content="Tue, 01 Jan 2014 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>HomewizardPHP</title>
<link href="css/index.css" rel="stylesheet" type="text/css" />
</head>
<body>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
<!-- <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> -->
<div class="row">

<?php 
$actual_page = "ndex.php";
if(isset($_SERVER["REQUEST_URI"])) $actual_page = substr('$_SERVER["REQUEST_URI"]', -8);
print '<div class="abutton-group">';
if (!isset($_POST['schakel']) && !isset($_POST['set_temp']) && $actual_page=="ndex.php") {print '
<a href="javascript:history.go(0)" class="abutton">Home</a>';} else {print '
<a href="index.php" class="abutton">Home</a>';}
if ($actual_page=="tory.php") {print '
<a href="javascript:history.go(0)" class="abutton">History</a>';} else {print '
<a href="history.php" class="abutton">History</a>';}
print '
<a href="temp.php" class="abutton">Temperature</a>
<a href="rain.php" class="abutton">Rain</a>
<a href="wind.php" class="abutton">Wind</a>';
if ($actual_page=="ings.php") {print '
<a href="javascript:history.go(0)" class="abutton">Settings</a>';} else {print '
<a href="settings.php" class="abutton">Settings</a>';}
print '</div>';
?>
</div>
