<?php
if(!isset($_POST['actionscron'])) {
include "parameters.php";
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
}
$authenticated=true;
include "data.php";
include "functions.php";

//BEGIN ACTION BRANDER
echo 'Brander: '.$switchstatus12.'<br/>';
if($switchstatus12=='off' && $switchstatus6>$thermometerte4) {schakel(12, 'on', 'c');sleep(1);}
if($switchstatus12=='on' && $switchstatus6<$thermometerte4) {schakel(12, 'off', 'c');sleep(1);}

//BEGIN ACTION LICHT GARAGE
if($switchstatus1=='on') {
	if(strtotime($sensortimestamp1)>time()) {$sensor1tijd = laatstesensortijd($sensorid1,null);$sensortimestamp1 = strtotime($sensor1tijd['time']);} else {$sensortimestamp1 = strtotime($sensortimestamp1);}
	if(strtotime($sensortimestamp2)>time()) {$sensor2tijd = laatstesensortijd($sensorid2,null);$sensortimestamp2 = strtotime($sensor2tijd['time']);} else {$sensortimestamp2 = strtotime($sensortimestamp2);}
	if($sensortimestamp1<(time()-120) && $sensortimestamp2<(time()-120)) {
		$laatsteschakel = laatsteschakeltijd(1,null, 'm');
		if($laatsteschakel['timestamp']<(time()-7200) || $laatsteschakel['type']=='off') {schakel(1, 'off', 'c');sleep(1);}
	} 
} 

if(!isset($_POST['actionscron'])) $db->close();
?>
