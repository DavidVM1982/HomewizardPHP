<?php
// Make database connection ('server', 'database', 'password', 'user') 
$db = new mysqli('localhost', 'homewizard', 'home!wizard', 'homewizard');
if($db->connect_errno > 0){ echo('Unable to connect to database [' . $db->connect_error . ']');}
?>