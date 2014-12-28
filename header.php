<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="expires" content="-1" />
<meta http-equiv="expires" content="Tue, 01 Jan 2014 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>HomewizardPHP</title>
<link href="css/index.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0px" topmargin="0" rightmargin="0">
<div class="content">
<div class="header">
</div>
<?php include "parameters.php"; 
$actual_page = substr("$_SERVER[REQUEST_URI]", -8);
if (!isset($_POST['schakel']) && !isset($_POST['set_temp']) && $actual_page=="ndex.php") {print '<a href="javascript:history.go(0)" class="abutton">Home</a>';} else {print '<a href="index.php" class="abutton">Home</a>';}
if ($actual_page=="tory.php") {print '<a href="javascript:history.go(0)" class="abutton">History</a>';} else {print '<a href="history.php" class="abutton">History</a>';}
if ($actual_page=="ings.php") {print '<a href="javascript:history.go(0)" class="abutton">Settings</a>';} else {print '<a href="settings.php" class="abutton">Settings</a>';}
?>;} else {print '<a href="settings.php" class="abutton">Settings</a>';}
?>