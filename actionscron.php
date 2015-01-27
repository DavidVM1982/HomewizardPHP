<?php
include "parameters.php";
setlocale(LC_ALL,'nl_NL.UTF-8');
date_default_timezone_set('Europe/Brussels');
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
$authenticated = false;
if(in_array($_SERVER['REMOTE_ADDR'], $acceptedips)) $authenticated = true; 
if($authenticated==true && $debug=='yes') {error_reporting(E_ALL);ini_set("display_errors", "on");}
if($authenticated==true){
	try {
	  $json = file_get_contents($jsonurl.'get-status');
	  
	} catch (Exception $e) {echo $e->getMessage();}
	$data = json_decode($json,true);
	print_r($data);
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
$scenes =  $data['response']['scenes'];
$thermometers =  $data['response']['thermometers'];
$rainmeters =  $data['response']['rainmeters'];
$windmeters =  $data['response']['windmeters'];	

//BEGIN ACTION BRANDER
if($switchstatus12=='off') {
	echo '<hr>De brander brandt niet.</br>';
	echo 'Radiator badkamer vraagt'.$switchstatus6.' en de temperatuur is nu '.$thermometers['0']['te'].'<br/>';
	if($switchstatus6>$thermometers['0']['te']) $brander=true;
	echo 'Radiator slaapkamer vraagt'.$switchstatus7.' en de temperatuur is nu '.$thermometers['0']['te'].'<br/>';
	if($switchstatus7>$thermometers['0']['te']) $brander=true;
	echo 'Radiator slaapkamer Tobi vraagt'.$switchstatus8.' en de temperatuur is nu '.$thermometers['0']['te'].'<br/>';
	if($switchstatus8>$thermometers['0']['te']) $brander=true;
	if($brander==true) {
		echo 'We hebben warmte nodig<br/>';
		$responsejson = file_get_contents($jsonurl.'sw/12/on');
		$response = json_decode($responsejson, true);
		if($response['status']=='ok') {echo '<div class="row">OK</div>'; } else {echo '<div class="row">response = ';print_r($response);echo '</div><hr>';}
	} else {
		echo 'We hebben geen warmte nodig<br/>';
	}
}
if($switchstatus12=='on') {
	$brander=false;
	echo '<hr>De brander brandt.</br>';
	echo 'Radiator badkamer vraagt '.$switchstatus6.'°C en de temperatuur is nu '.$thermometers['0']['te'].'°C<br/>';
	if($switchstatus6>$thermometers['0']['te']) $brander=true;
	echo 'Radiator slaapkamer vraagt '.$switchstatus7.'°C en de temperatuur is nu '.$thermometers['0']['te'].'°C<br/>';
	if($switchstatus7>$thermometers['0']['te']) $brander=true;
	echo 'Radiator slaapkamer Tobi vraagt '.$switchstatus8.'°C en de temperatuur is nu '.$thermometers['0']['te'].'°C<br/>';
	if($switchstatus8>$thermometers['0']['te']) $brander=true;
	if($brander==false) {
		echo 'We hebben geen warmte meer nodig<br/>';
		$responsejson = file_get_contents($jsonurl.'sw/12/off');
		$response = json_decode($responsejson, true);
		if($response['status']=='ok') {echo '<div class="row">OK</div>'; } else {echo '<div class="row">response = ';print_r($response);echo '</div><hr>';}
	} else {
		echo 'We hebben nog steeds warmte nodig<br/>';
	}
}
//END ACTION BRANDER

}
?>