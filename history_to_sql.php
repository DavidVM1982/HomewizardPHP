<?php
include_once "parameters.php";
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
//session_start();
if(isset($_SESSION['authenticated'])) {
	if ($_SESSION['authenticated'] == true) {
		$authenticated = true;
	}
}
if($authenticated==true && $debug=='yes') {
	error_reporting(E_ALL); 
	ini_set("display_errors", "on");
}
/* Sensors */
$data = null;
try {
  $json = file_get_contents($jsonurl.'get-sensors'); 
  $data = json_decode($json,true);
} catch (Exception $e) { 
  echo $e->getMessage();
}
if (!$data) {
  echo "No information available...";
} else {
	$thermometers =  $data['response']['thermometers'];
	$rainmeters =  $data['response']['rainmeters'];
	$windmeters =  $data['response']['windmeters'];
	$energylinks =  $data['response']['energylinks'];
	$types = array_keys($data['response']);
	foreach ($types as $type) {
		$devices = $data['response'][$type];
		if (count($devices) > 0) { 
			if($type=="kakusensors") {
				foreach($devices as $device){ 
					//print_r($device);
					$id_sensor = $device['id'];
					$namedevice = $device['name'];
					$favorite = 'yes';
					$type = $device['type'];
					if(isset($_POST['updateswitches'])) {
						$sql = "INSERT INTO sensors (`id_sensor`, `name`, `type`, `favorite`) values ($id_sensor, '$namedevice', '$type', '$favorite') ON DUPLICATE KEY UPDATE `name`='$namedevice', `type`= '$type'";
						echo $id_sensor.'-'.$namedevice.': '.$type.'<br/>';
						if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
					}
					$datahistory = null;
					try {
						$jsonhistory = file_get_contents($jsonurl.'kks/get/'.$id_sensor.'/log');  
						$datahistory = json_decode($jsonhistory,true); 
					} catch (Exception $e) {  
						echo $e->getMessage();
					}
					if (!$datahistory) {
						echo "No information available...";
					} else {
				    	$deviceshistory = $datahistory['response']; 
						foreach($deviceshistory as $devicehistory){  
			       			$time = $devicehistory['t'];
							$status = $device['type'].$devicehistory['status'];
							$sql = "INSERT IGNORE INTO history (`id_sensor`, `time`, `status`) values ($id_sensor, '$time', '$status')";
							if(isset($_POST['updateswitches'])) echo $id_sensor.'-'.$time.': '.$status.'<br/>';
							if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
				    	}
					}
					if(isset($_POST['updateswitches'])) echo '<hr>';
				}
			}
			if(isset($_POST['updateswitches'])) {
				if($type=="switches") {
					foreach($devices as $device){ 
						//print_r($device);
						$id_switch = $device['id'];
						$namedevice = $device['name'];
						$favorite = 'yes';
						$type = $device['type'];
						$sql = "INSERT INTO switches (`id_switch`, `name`, `type`, `favorite`) values ($id_switch, '$namedevice', '$type', '$favorite') ON DUPLICATE KEY UPDATE `name`='$namedevice', `type`= '$type'";
						echo $id_switch.'-'.$namedevice.': '.$type.'<br/>';
						if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
						echo '<hr>';
					}
				}
				if($type=="scenes") {
					foreach($devices as $device){ 
						//print_r($device);
						$id_switch = $device['id'];
						$namedevice = $device['name'];
						$favorite = 'yes';
						$type = 'scene';
						$sql = "INSERT INTO switches (`id_switch`, `name`, `type`, `favorite`) values ($id_switch, '$namedevice', '$type', '$favorite') ON DUPLICATE KEY UPDATE `name`='$namedevice', `type`= '$type'";
						echo $id_switch.'-'.$namedevice.': '.$type.'<br/>';
						if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
						echo '<hr>';
					}
				}
				if($type=="thermometers") {
					foreach($devices as $device){ 
						print_r($device);
						$id_sensor = $device['id'];
						$namedevice = $device['name'];
						$favorite = 'yes';
						$type = 'temp';
						$sql = "INSERT INTO sensors (`id_sensor`, `name`, `type`, `favorite`) values ($id_sensor, '$namedevice', '$type', '$favorite') ON DUPLICATE KEY UPDATE `name`='$namedevice', `type`= '$type'";
						echo $id_switch.'-'.$namedevice.': '.$type.'<br/>';
						if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
						echo '<hr>';
					}
				}
			}
		}
	}
}

/* Temperature */
if(!empty($thermometers)) {
	foreach($thermometers as $thermometer) {
		$datas = null;
		try {
			$json = file_get_contents($jsonurl.'te/graph/'.$thermometer['id'].'/day'); 
			$datas = json_decode($json,true);
		} catch (Exception $e) { 
			echo $e->getMessage();
		}
		if (!$datas) {
			echo "No information available...";
		} else {
			if(isset($_POST['updateswitches'])) echo '<hr>Importing Temperature<br/>';
			$lasttime = '123';
			foreach($datas['response'] as $data){
				$id_sensor=$thermometer['id'];
				$time = substr($data['t'],0,-2).'00';
				if($time!=$lasttime) {
					$temp = str_replace(',', '.', str_replace('.', '', $data['te']));
					$hum = $data['hu'];
					if(isset($_POST['updateswitches'])) echo $time.' - '.$temp.' - '.$hum.'<br/>';
					//$sql = "INSERT INTO temperature (`timestamp`, `te`, `hu`, `id_sensor`) values ('$time', '$temp', '$hum', '$id_sensor') ON DUPLICATE KEY UPDATE `te`='$temp', `hu`='$hum'";
					$sql = "INSERT IGNORE INTO temperature (`timestamp`, `te`, `hu`, `id_sensor`) values ('$time', '$temp', '$hum', '$id_sensor')";
					if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
					$lasttime = $time;
				}
			}
		}
	}
}

/* Min Max Temperatures per day */
if(!empty($thermometers)) {
	$datas = null;
	try {
		$json = file_get_contents($jsonurl.'telist'); 
		$datas = json_decode($json,true);
	} catch (Exception $e) { 
		echo $e->getMessage();
	}
	if (!$datas) {
		echo "No information available...";
	} else {
		if(isset($_POST['updateswitches'])) echo '<hr>Importing Min Max Temperatures per day<br/>';
		foreach($datas['response'] as $data){
			$id_sensor=$data['id'];
			$datum = date('Y-m-d');
			$mintemp = str_replace(',', '.', str_replace('.', '', $data['te-']));
			$maxtemp = str_replace(',', '.', str_replace('.', '', $data['te+']));
			if(isset($_POST['updateswitches'])) echo $datum.': '.$mintemp.' - '.$maxtemp;
			$sql = "INSERT INTO temp_day (`date`, `min`, `max`, `id_sensor`) values ('$datum', '$mintemp', '$maxtemp', '$id_sensor') ON DUPLICATE KEY UPDATE `min`='$mintemp', `max`='$maxtemp'";
			if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
		}
	}
}

/* Rain */
$datas = null;
try {
  $json = file_get_contents($jsonurl.'ralist'); 
  $datas = json_decode($json,true);
} catch (Exception $e) { 
  echo $e->getMessage();
}
if (!$datas) {
  echo "No information available...";
} else {
  if(isset($_POST['updateswitches'])) echo '<hr>Importing Rain<br/>';
  foreach($datas['response'] as $data){
	  $datum = date('Y-m-d');
	  $mm = str_replace(',', '.', str_replace('.', '', $data['mm']));
	  if(isset($_POST['updateswitches'])) echo $datum.': '.$mm;
	  $sql = "INSERT INTO rain (`date`, `mm`, `id_sensor`) values ('$datum', '$mm', '$id_sensor') ON DUPLICATE KEY UPDATE `mm`='$mm'";
	  if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
  }

}
	
/* Wind */
if(!empty($windmeters)) {
	foreach($windmeters as $windmeter){
		$datas = null;
		try {
			$json = file_get_contents($jsonurl.'wi/graph/'.$windmeter['id'].'/day'); 
			$datas = json_decode($json,true);
		} catch (Exception $e) { 
			echo $e->getMessage();
		}
		if (!$datas) {
			echo "No information available...";
		} else {
			if(isset($_POST['updateswitches'])) echo '<hr>Importing Wind<br/>';
			foreach($datas['response'] as $data){
				$id_sensor=$windmeter['id'];
				$time = $data['t'];
				$windspeed = str_replace(',', '.', str_replace('.', '', $data['ws']));
				$gust = str_replace(',', '.', str_replace('.', '', $data['gu']));
				$direction = $data['dir'];
				if(isset($_POST['updateswitches'])) echo $time.' - '.$windspeed.' - '.$gust.' - '.$direction.'<br/>';
				$sql = "INSERT IGNORE INTO wind (`timestamp`, `wi`, `gu`, `dir`, `id_sensor`) values ('$time', '$windspeed', '$gust', '$direction', '$id_sensor') ";
				if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
			}
		}
	}
}

/* energylink */
if(!empty($energylinks)) {
	foreach($energylinks as $energylink) {
		$datas = null;
		try {
			$json = file_get_contents($jsonurl.'el/graph/0/day');
			$datas = json_decode($json,true);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	if (!$datas) {
		echo "No information available...";
	} else {
		echo ' Importing energylink';
			foreach($datas['response'] as $data){
			$time = $data['t'];
			$netto = $data['a'];
			$verbruik = $data['u'];
			$water = $data['s1'];
			$zon = $data['s2'];
			$gas = $data['g']*100;
			echo $time.' - '.$netto.' - '.$water.' - '.$zon.' - '.$gas.'<br/>';
			$sql = "INSERT IGNORE INTO energylink (timestamp, netto, verbruik, S1 , S2 , gas) values ('$time', '$netto', '$verbruik', '$water' , '$zon' , '$gas') ";
			if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
			}
		}
	}
}
if(!isset($_POST['updateswitches'])) ob_clean();
if(isset($_POST['importall'])) ob_clean();

?>
</body>
</html>