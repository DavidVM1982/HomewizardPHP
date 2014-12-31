<?php
// Make database connection ('server', 'database', 'password', 'user') 
$db = new mysqli('server', 'database', 'password', 'user');
if($db->connect_errno > 0){ die('Unable to connect to database [' . $db->connect_error . ']');}
?>
