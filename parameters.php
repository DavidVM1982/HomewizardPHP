<?php
// Make database connection ('server', 'user', 'password', 'database') 
$db = new mysqli('server', 'user', 'password', 'database');
if($db->connect_errno > 0){ die('Unable to connect to database [' . $db->connect_error . ']');}
?>
