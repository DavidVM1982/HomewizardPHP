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
include "functions.php";
include "data.php";
echo '<hr>';
//BEGIN ACTION BRANDER
if($switchstatus12=='off' && $switchstatus6>$thermometerte4) {schakel(12, 'on', 'c'); sleep(1);}
if($switchstatus12=='on' && $switchstatus6<$thermometerte4) {schakel(12, 'off', 'c'); sleep(1);}
//END ACTION BRANDER

//BEGIN ACTION LICHT GARAGE
if($switchstatus1=='on') {
	$sensor1tijd = laatstesensortijd($sensorid1,null);
	$sensor2tijd = laatstesensortijd($sensorid2,null);
	if(strtotime($sensor1tijd['time'])<(time()-120) && strtotime($sensor2tijd['time'])<(time()-120)) {
		$laatsteschakel = laatsteschakeltijd(1,null, 'm');
		if($laatsteschakel['timestamp']<(time()-7200) || $laatsteschakel['type']=='off') {schakel(1, 'off', 'c');}
		sleep(1);
	} 
}
//END ACTION LICHT GARAGE

if(!isset($_POST['actionscron'])) $db->close();
?>