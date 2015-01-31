<?php
if($authenticated == true && $developermode != 'yes') { 
	$json = file_get_contents($jsonurl.'get-status');
} else if ($developermode == 'yes') {
	print '<div class="error gradient">Developer mode</div>';
	$json = $developerjson;
} else {
	print '<div class="error gradient">Demo mode, no actual data shown.</div>';
	$json = $developerjson;
}
$data = null;
$data = json_decode($json,true);
if($authenticated == true && $debug=='yes') {echo '<div class="error gradient">'.$json.'</div>';}

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
	${'thermometerid'.$thermometer['id']} = $thermometer['id'];
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
$energylinks = $data['response']['energylinks'];

?>