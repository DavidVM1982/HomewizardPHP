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
<link href="css/index.css" rel="stylesheet" type="text/css" />
<title>HomewizardPHP</title>
<?php 
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
include_once "parameters.php"; 
setlocale(LC_ALL,'nl_NL.UTF-8');
setlocale(LC_ALL, 'nld_nld');
date_default_timezone_set('Europe/Brussels');
$sql="select variable, value from settings order by variable asc";
	if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	$acceptedips = array();
	while($row = $result->fetch_assoc()){
		if (strpos($row['variable'], 'acceptedip') === 0) array_push($acceptedips, $row['value']);
		else $$row['variable'] = $row['value'];
	}
	$result->free();
$authenticated = false;
if(in_array($_SERVER['REMOTE_ADDR'], $acceptedips)) $authenticated = true; 
session_start();
if(isset($_SESSION['authenticated'])) {if ($_SESSION['authenticated'] == true) {$authenticated = true;}}
if($authenticated==true && $debug=='yes') {error_reporting(E_ALL);ini_set("display_errors", "on");} 
$actual_page = "ndex.php";
if(isset($_SERVER['PHP_SELF'])) $actual_page = substr($_SERVER['PHP_SELF'], -9);
if ($actual_page=="index.php") {
	print '<meta http-equiv="refresh" content="'.$refreshinterval.'" />';
}
echo '
<style>
body {'.$css_body.';}
.item {'.$css_item.';}
</style>';
?>

</head>
<body>
<div class="header"><a href="index.php" class="abutton home gradient" style="padding:10px 0px;">Home</a></div>
