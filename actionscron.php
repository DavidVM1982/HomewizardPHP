<?php
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
include "functions.php";
include "data.php";

//BEGIN ACTION BRANDER
if($switchstatus12=='off') {
	if($switchstatus6>$thermometerte4) {
		schakel(12, 'on', 'c');
	} 
}
if($switchstatus12=='on') {
	if($switchstatus6<$thermometerte4) {
		schakel(12, 'off', 'c');
	}
}
sleep(1);
//END ACTION BRANDER

//BEGIN ACTION LICHT GARAGE
if($switchstatus1=='on') {
	if(strtotime($sensortimestamp1)<(time()-300) && strtotime($sensortimestamp2)<(time()-300)) {
		$sql ="select timestamp, type from switchhistory WHERE id_switch = $switchid1 order by timestamp DESC limit 0,1;";
		if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
		$row = $result->fetch_assoc();
		if($row['timestamp']<(time()-7200) || $row['type']=='off') {
			schakel(1, 'off', 'c');
		}
		$result->free();
	} 
}
sleep(1);
//END ACTION LICHT GARAGE
$db->close();
?>