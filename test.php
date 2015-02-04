<?php
include "data.php";
include "functions.php";
// TESTFILE. Output wordt getoond in settings > test.
echo '<br/>';
$old = time()-(30*86400);
$old = date("Y-m-d H:i:s", $old);
echo $old;
?>