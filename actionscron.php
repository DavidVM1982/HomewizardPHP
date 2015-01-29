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
$json = file_get_contents($jsonurl.'get-status');
$data = json_decode($json,true);
$switches =  $data['response']['switches'];
foreach($switches as $switch) {
	${'switchid'.$switch['id']} = $switch['id'];
	${'switchtype'.$switch['id']} = $switch['type'];
	if($switch['type']=='radiator') { 
		${'switchstatus'.$switch['id']} = $switch['tte']; 
	} else if($switch['type']=='dimmer') {
		${'switchstatus'.$switch['id']} = $switch['dimlevel'];
	} else if($switch['type']=='asun') {
		${'switchstatus'.$switch['id']} = $switch['mode'];
	} else if($switch['type']=='somfy') {
	} else if($switch['type']=='virtual') {
		if(isset($switch['status'])) {${'switchstatus'.$switch['id']} = $switch['status'];} else {${'switchstatus'.$switch['id']} = 'off';};
	} else {
		${'switchstatus'.$switch['id']} = $switch['status'];
	}
}
$sensors =  $data['response']['kakusensors'];
foreach($sensors as $sensor) {
	${'sensorid'.$sensor['id']} = $sensor['id'];
	${'sensorstatus'.$sensor['id']} = $sensor['status'];
	${'sensortimestamp'.$sensor['id']} = $sensor['timestamp'];
}
$thermometers =  $data['response']['thermometers'];
foreach($thermometers as $thermometer) {
	${'thermometer'.$thermometer['id']} = $thermometer['id'];
	${'thermometerte'.$thermometer['id']} = $thermometer['te'];
	${'thermometerhu'.$thermometer['id']} = $thermometer['hu'];
}
$rainmeters =  $data['response']['rainmeters'];
foreach($rainmeters as $rainmeter) {
	${'rainmeter'.$rainmeter['id']} = $rainmeter['id'];
	${'rainmeter3h'.$rainmeter['id']} = $rainmeter['3h'];
}
$windmeters =  $data['response']['windmeters'];	
foreach($windmeters as $windmeter) {
	${'windmeter'.$windmeter['id']} = $windmeter['id'];
	${'windmeterws'.$windmeter['id']} = $windmeter['ws'];
	${'windmetergu'.$windmeter['id']} = $windmeter['gu'];
}

//BEGIN ACTION BRANDER
if($switchstatus12=='off') {
	if($switchstatus6>$thermometerte4) {
		file_get_contents($jsonurl.'sw/12/on');
	} 
}
if($switchstatus12=='on') {
	if($switchstatus6<$thermometerte4) {
		if(isset($_POST['actionscron'])) echo 'We hebben geen warmte meer nodig<br/>';
		file_get_contents($jsonurl.'sw/12/off');
	}
}
//END ACTION BRANDER

//BEGIN ACTION LICHT GARAGE
if($switchstatus1=='on') {
	if(strtotime($sensortimestamp1)<(time()-300) && strtotime($sensortimestamp2)<(time()-300)) {
		$sql ="select timestamp, type from switchhistory WHERE id_switch = $switchid1 order by timestamp DESC limit 0,1;";
		if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
		$row = $result->fetch_assoc();
		if($row['timestamp']<(time()-7200) || $row['type']=='off') {
			file_get_contents($jsonurl.'sw/1/off');
		}
		$result->free();
	} 
}
//END ACTION LICHT GARAGE
$db->close();
?>