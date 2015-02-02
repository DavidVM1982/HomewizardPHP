<?php
// TESTFILE. Output wordt getoond in settings > test.
echo '<br/>';

echo 'Dag van de week: '.date('N', time()).'<br/>';
echo 'Weeknummer van het jaar: '.date('W', time()).'<br/>';
echo '<hr>';
$number  = date('W', time());
if(date('W', time()) %2 == 0) {
	echo 'Week nummer '.$number.' is een even weeknummer';
} else {
	echo 'Week nummer '.$number.' is een oneven weeknummer';
}
?>