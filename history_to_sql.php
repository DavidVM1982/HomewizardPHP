<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Homewizard to SQL</title>
</head>
<body>
<?php
include "parameters.php";
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
  echo 'Importing Sensors<br/>';
  $types = array_keys($data['response']);
  foreach ($types as $type) {
    $devices = $data['response'][$type];
    if (count($devices) > 0) { 
      if($type=="kakusensors") {
		    foreach($devices as $device){ 
        		//print_r($device);
				$id_sensor = $device['id'];
				$namedevice = $device['name'];
				$type = $device['type'];
				$sql = "INSERT INTO sensors (`id_sensor`, `name`, `type`) values ($id_sensor, '$namedevice', '$type') ON DUPLICATE KEY UPDATE `name`='$namedevice', `type`= '$type'";
				echo $id_sensor.'-'.$namedevice.': '.$type.'<br/>';
				if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
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
						echo $id_sensor.'-'.$time.': '.$status.'<br/>';
						if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
				    }
				}
				echo '<hr>';
			}
		}
	}
}
}

/* Temperature */
$datas = null;
try {
  $json = file_get_contents($jsonurl.'te/graph/1/day'); 
  $datas = json_decode($json,true);
} catch (Exception $e) { 
  echo $e->getMessage();
}
if (!$datas) {
  echo "No information available...";
} else {
  echo '<hr>Importing Temperature<br/>';
  foreach($datas['response'] as $data){
	  $time = $data['t'];
	  $temp = $data['te'];
	  $hum = $data['hu'];
	  echo $data['t'].' - '.$data['te'].' - '.$data['hu'].'<br/>';
	  $sql = "INSERT IGNORE INTO temperature (`timestamp`, `te`, `hu`) values ('$time', '$temp', '$hum') ";
	  if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
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
  echo '<hr>Importing Rain<br/>';
  foreach($datas['response'] as $data){
	  $datum = date('Y-m-d');
	  $mm = $data['mm'];
	  echo $datum.': '.$mm;
	  $sql = "INSERT INTO rain (`date`, `mm`) values ('$datum', '$mm') ON DUPLICATE KEY UPDATE `mm`='$mm'";
	  if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
  }

}

/* Min Max Temperatures per day */
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
  echo '<hr>Importing Min Max Temperatures per day<br/>';
  foreach($datas['response'] as $data){
	  $datum = date('Y-m-d');
	  $mintemp = $data['te-'];
	  $maxtemp = $data['te+'];
	  echo $datum.': '.$mintemp.' - '.$maxtemp;
	  $sql = "INSERT INTO temp_day (`date`, `min`, `max`) values ('$datum', '$mintemp', '$maxtemp') ON DUPLICATE KEY UPDATE `min`='$mintemp', `max`='$maxtemp'";
	  if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
  }

}

/* Wind */
$datas = null;
try {
  $json = file_get_contents($jsonurl.'wi/graph/2/day'); 
  $datas = json_decode($json,true);
} catch (Exception $e) { 
  echo $e->getMessage();
}
if (!$datas) {
  echo "No information available...";
} else {
  echo '<hr>Importing Wind<br/>';
  foreach($datas['response'] as $data){
	  $time = $data['t'];
	  $windspeed = $data['ws'];
	  $gust = $data['gu'];
	  $direction = $data['dir'];
	  echo $time.' - '.$windspeed.' - '.$gust.' - '.$direction.'<br/>';
	  $sql = "INSERT IGNORE INTO wind (`timestamp`, `wi`, `gu`, `dir`) values ('$time', '$windspeed', '$gust', '$direction') ";
	  if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'] > [' . $db->error . ']');}
  }

}


?>
</body>
</html>  }

}

}
?>
</body>
</html>