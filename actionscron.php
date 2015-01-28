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
try {
	  $json = file_get_contents($jsonurl.'get-status');
	  
	} catch (Exception $e) {echo $e->getMessage();}
	$data = json_decode($json,true);
	if($debug=='yes') print_r($data);
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
	${'thermometertemp'.$thermometer['id']} = $thermometer['te'];
}
$rainmeters =  $data['response']['rainmeters'];
$windmeters =  $data['response']['windmeters'];	

//BEGIN ACTION BRANDER
if($switchstatus12=='off') {
	if(isset($_POST['actionscron'])) echo '<hr>De brander brandt niet.</br>';
	if(isset($_POST['actionscron'])) echo 'Radiator badkamer vraagt'.$switchstatus6.' en de temperatuur is nu '.$thermometertemp4.'<br/>';
	if($switchstatus6>$thermometertemp4) $brander=true;
	if($brander==true) {
		if(isset($_POST['actionscron'])) echo 'We hebben warmte nodig<br/>';
		$responsejson = file_get_contents($jsonurl.'sw/12/on');
		$response = json_decode($responsejson, true);
		if($response['status']=='ok') {echo '<div class="row">OK</div>'; } else {echo '<div class="row">response = ';print_r($response);echo '</div><hr>';}
	} else {
		if(isset($_POST['actionscron'])) echo 'We hebben geen warmte nodig<br/>';
	}
}
if($switchstatus12=='on') {
	$brander=false;
	if(isset($_POST['actionscron'])) echo '<hr>De brander brandt.</br>';
	if(isset($_POST['actionscron'])) echo 'Radiator badkamer vraagt '.$switchstatus6.'°C en de temperatuur is nu '.$thermometertemp4.'°C<br/>';
	if($switchstatus6>$thermometertemp4) $brander=true;
	if($brander==false) {
		if(isset($_POST['actionscron'])) echo 'We hebben geen warmte meer nodig<br/>';
		$responsejson = file_get_contents($jsonurl.'sw/12/off');
		$response = json_decode($responsejson, true);
		if($response['status']=='ok') {echo '<div class="row">OK</div>'; } else {echo '<div class="row">response = ';print_r($response);echo '</div><hr>';}
	} else {
		if(isset($_POST['actionscron'])) echo 'We hebben nog steeds warmte nodig<br/>';
	}
}
//END ACTION BRANDER

//BEGIN ACTION LICHT GARAGE
if($switchstatus1=='off') {
	if(isset($_POST['actionscron'])) echo '<hr>Het licht in de garage is uit.</br>';
}
if($switchstatus1=='on') {
	if(isset($_POST['actionscron'])) echo '<hr>Het licht in de garage is aan.</br>
		De poorts is laatst open geweest om '.$sensortimestamp1.'<br/>
		De laatste beweging is gedetecteerd om '.$sensortimestamp2.'<br/>
		Het is nu '.date("H:i",time()).'<br/>';
	if(strtotime($sensortimestamp1)<(time()-300) && strtotime($sensortimestamp2)<(time()-300)) {
		echo 'Meer dan 5 min geleden, kijken of het licht manueel aangelegd werd. <br/>';
		$sql ="select timestamp from switchhistory WHERE id_switch = $switchid1 AND type like 'on' order by timestamp DESC limit 0,1;";
		if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
		$row = $result->fetch_assoc();
		echo 'Het licht werd laatst manueel geschakeld om '.date("H:i",$row['timestamp']);
		if($row['timestamp']<(time()-60*120)) {
			echo 'Licht is meer dan 2 uur geleden geschakeld, we schakelen het uit.';
			$responsejson = file_get_contents($jsonurl.'sw/1/off');
			$response = json_decode($responsejson, true);
			if($response['status']=='ok') {echo '<div class="row">OK</div>'; } else {echo '<div class="row">response = ';print_r($response);echo '</div><hr>';}
		}
		$result->free();
	} else {
		if(isset($_POST['actionscron'])) echo 'We hebben nog steeds licht nodig<br/>';
	}
}
//END ACTION LICHT GARAGE
?>