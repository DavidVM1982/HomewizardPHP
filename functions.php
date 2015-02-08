<?php
function schakel($switch, $action, $who, $notify, $failonly) {
	global $jsonurl, $db, $debug;
	$responsejson = file_get_contents($jsonurl.'sw/'.$switch.'/'.$action);
	$response = json_decode($responsejson, true);
	if($response['status']=='ok') {
		if(isset($notify) && !isset($failonly)) notificatie($notify, "Schakelaar ".$switch." werd op ".$action." gezet.", "Hello ".$notify.",\r\n\r\nSchakelaar ".$switch." werd op ".$action." gezet.");
		$timestamp = time();
		if($debug=='yes') $reply = '<div class="error gradient">OK</div>';
		$sql ="insert into switchhistory (`id_switch`,`timestamp`,`type`,`who`) values ($switch, $timestamp, '$action', '$who');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	} else {
		sleep(5);
		$responsejson = file_get_contents($jsonurl.'sw/'.$switch.'/'.$action);
		$response = json_decode($responsejson, true);
		if($response['status']=='ok') {
			if(isset($notify) && !isset($failonly)) notificatie($notify, "Schakelaar ".$switch." werd op ".$action." gezet.", "Hello ".$notify.",\r\n\r\nSchakelaar ".$switch." werd op ".$action." gezet.");
			$timestamp = time();
			if($debug=='yes') $reply = '<div class="error gradient">OK</div>';
			$sql ="insert into switchhistory (`id_switch`,`timestamp`,`type`,`who`) values ($switch, $timestamp, '$action', '$who');";
			if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
		} else {
			if(isset($notify)) notificatie($notify, "Schakelaar ".$switch." op ".$action." zetten mislukt.", "Hello ".$notify.",\r\n\r\nSchakelaar ".$switch." op ".$action." zetten mislukt.");
			$reply = '<div class="error gradient">response = ';$reply .= print_r($response); $reply .= '</div>';
		}
	}
	if($debug=='yes') {$reply .= '<div class="error gradient">$_POST = ';$reply .= print_r($_POST);$reply .=  "<br/>sw/".$switch."/".$action."</div>";}
	if($debug=='yes') return $reply;
}
function dim($switch, $action, $who, $notify, $failonly) {
	global $jsonurl, $db, $debug;
	$responsejson = file_get_contents($jsonurl.'sw/dim/'.$switch.'/'.$action);
	$response = json_decode($responsejson, true);
	if($response['status']=='ok') {
		if(isset($notify) && !isset($failonly)) notificatie($notify, "Dimmer ".$switch." werd ingesteld op ".$action.".", "Hello ".$notify.",\r\n\r\nDimmer ".$switch." werd ingesteld op ".$action.".");
		$timestamp = time();
		if($debug=='yes') $reply = '<div class="error gradient">OK</div>';
		$sql ="insert into switchhistory (`id_switch`,`timestamp`,`type`,`who`) values ($switch, $timestamp, '$action', '$who');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	} else {
		sleep(5);
		$responsejson = file_get_contents($jsonurl.'sw/dim/'.$switch.'/'.$action);
		$response = json_decode($responsejson, true);
		if($response['status']=='ok') {
			if(isset($notify) && !isset($failonly)) notificatie($notify, "Dimmer ".$switch." werd ingesteld op ".$action.".", "Hello ".$notify.",\r\n\r\nDimmer ".$switch." werd ingesteld op ".$action.".");
			$timestamp = time();
			if($debug=='yes') $reply = '<div class="error gradient">OK</div>';
			$sql ="insert into switchhistory (`id_switch`,`timestamp`,`type`,`who`) values ($switch, $timestamp, '$action', '$who');";
			if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
		} else {
			if(isset($notify)) notificatie($notify, "Dimmer ".$switch." instellen op ".$action." mislukt.", "Hello ".$notify.",\r\n\r\nDimmer ".$switch." werd NIET ingesteld op ".$action.".");
			$reply = '<div class="error gradient">response = ';$reply .= print_r($response); $reply .= '</div>';
		}
	}
	if($debug=='yes') {$reply .= '<div class="error gradient">$_POST = ';$reply .= print_r($_POST);$reply .=  "<br/>sw/".$switch."/".$action."</div>";}
	if($debug=='yes') return $reply;
}
function somfy($switch, $action, $who, $notify, $failonly) {
	global $jsonurl, $db, $debug;
	$responsejson = file_get_contents($jsonurl.'sf/'.$switch.'/'.$action);
	$response = json_decode($responsejson, true);
	if($response['status']=='ok') {
		if(isset($notify) && !isset($failonly)) notificatie($notify, "Somfy ".$switch." werd ingesteld op ".$action.".", "Hello ".$notify.",\r\n\r\nSomfy ".$switch." werd ingesteld op ".$action.".");
		$timestamp = time();
		if($debug=='yes') $reply = '<div class="error gradient">OK</div>';
		$sql ="insert into switchhistory (`id_switch`,`timestamp`,`type`,`who`) values ($switch, $timestamp, '$action', '$who');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	} else {
		sleep(5);
		$responsejson = file_get_contents($jsonurl.'sf/'.$switch.'/'.$action);
		$response = json_decode($responsejson, true);
		if($response['status']=='ok') {
			if(isset($notify) && !isset($failonly)) notificatie($notify, "Somfy ".$switch." werd ingesteld op ".$action.".", "Hello ".$notify.",\r\n\r\nSomfy ".$switch." werd ingesteld op ".$action.".");
			$timestamp = time();
			if($debug=='yes') $reply = '<div class="error gradient">OK</div>';
			$sql ="insert into switchhistory (`id_switch`,`timestamp`,`type`,`who`) values ($switch, $timestamp, '$action', '$who');";
			if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
		} else {
			if(isset($notify)) notificatie($notify, "Somfy ".$switch." instellen op ".$action." mislukt.", "Hello ".$notify.",\r\n\r\nSomfy ".$switch." werd NIET ingesteld op ".$action.".");
			$reply = '<div class="error gradient">response = ';$reply .= print_r($response); $reply .= '</div>';
		}
	}
	if($debug=='yes') {$reply .= '<div class="error gradient">$_POST = ';$reply .= print_r($_POST);$reply .=  "<br/>sw/".$switch."/".$action."</div>";}
	if($debug=='yes') return $reply;
}
function radiator($switch, $action, $who, $notify, $failonly) {
	global $jsonurl, $db, $debug;
	$responsejson = file_get_contents($jsonurl.'sw/'.$switch.'/settarget/'.$action);
	$response = json_decode($responsejson, true);
	if($response['status']=='ok') {
		if(isset($notify) && !isset($failonly)) notificatie($notify, "Radiator ".$switch." werd ingesteld op ".$action.".", "Hello ".$notify.",\r\n\r\nRadiator ".$switch." werd ingesteld op ".$action.".");
		$timestamp = time();
		if($debug=='yes') $reply = '<div class="error gradient">OK</div>';
		$sql ="insert into switchhistory (`id_switch`,`timestamp`,`type`,`who`) values ($switch, $timestamp, '$action', '$who');";
		if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	} else {
		sleep(5);
		$responsejson = file_get_contents($jsonurl.'sw/'.$switch.'/settarget/'.$action);
		$response = json_decode($responsejson, true);
		if($response['status']=='ok') {
			if(isset($notify) && !isset($failonly)) notificatie($notify, "Radiator ".$switch." werd ingesteld op ".$action.".", "Hello ".$notify.",\r\n\r\nRadiator ".$switch." werd ingesteld op ".$action.".");
			$timestamp = time();
			if($debug=='yes') $reply = '<div class="error gradient">OK</div>';
			$sql ="insert into switchhistory (`id_switch`,`timestamp`,`type`,`who`) values ($switch, $timestamp, '$action', '$who');";
			if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
		} else {
			if(isset($notify)) notificatie($notify, "Radiator ".$switch." instellen op ".$action." mislukt.", "Hello ".$notify.",\r\n\r\nRadiator ".$switch." werd NIET ingesteld op ".$action.".");
			$reply = '<div class="error gradient">response = ';$reply .= print_r($response); $reply .= '</div>';
		}
	}
	if($debug=='yes') {$reply .= '<div class="error gradient">$_POST = ';$reply .= print_r($_POST);$reply .=  "<br/>sw/".$switch."/".$action."</div>";}
	if($debug=='yes') return $reply;
}
function scene($switch, $action, $who, $notify, $failonly) {
	global $jsonurl, $db, $debug;
	$responsejson = file_get_contents($jsonurl.'gp/'.$switch.'/'.$action);
	$response = json_decode($responsejson, true);
	if($response['status']=='ok') {
		if(isset($notify) && !isset($failonly)) notificatie($notify, "Scene ".$switch." werd ingesteld op ".$action.".", "Hello ".$notify.",\r\n\r\nScene ".$switch." werd ingesteld op ".$action.".");
		if($debug=='yes') $reply = '<div class="error gradient">OK</div>';
	} else {
		sleep(5);
		$responsejson = file_get_contents($jsonurl.'gp/'.$switch.'/'.$action);
		$response = json_decode($responsejson, true);
		if($response['status']=='ok') {
			if(isset($notify) && !isset($failonly)) notificatie($notify, "Scene ".$switch." werd ingesteld op ".$action.".", "Hello ".$notify.",\r\n\r\nScene ".$switch." werd ingesteld op ".$action.".");
			if($debug=='yes') $reply = '<div class="error gradient">OK</div>';
		} else {
			if(isset($notify)) notificatie($notify, "Scene ".$switch." instellen op ".$action." mislukt.", "Hello ".$notify.",\r\n\r\nScene ".$switch." werd NIET ingesteld op ".$action.".");
			$reply = '<div class="error gradient">response = ';$reply .= print_r($response); $reply .= '</div>';
		}
	}
	if($debug=='yes') {$reply .= '<div class="error gradient">$_POST = ';$reply .= print_r($_POST);$reply .=  "<br/>sw/".$switch."/".$action."</div>";}
	if($debug=='yes') return $reply;
}
function laatsteschakeltijd($switch, $action, $who, $notify, $failonly) {
	global $jsonurl, $db, $debug;
	$sql ="select timestamp, type, who from switchhistory WHERE id_switch = $switch";
	if(isset($action)) $sql .= " AND type like '$action'";
	if(isset($who)) $sql .= " AND who like '$who'";
	$sql .= " order by timestamp DESC limit 0,1;";
	if(!$result = $db->query($sql)){ echo ('Error in sql '.$sql.'<br/> [' . $db->error . ']');}
	$row = $result->fetch_assoc();
	return $row;	
}
function laatstesensortijd($sensor, $status, $notify, $failonly) {
	global $jsonurl, $db, $debug;
	$sql ="select time, status from history WHERE id_sensor = $sensor";
	if(isset($status)) $sql .= " AND status like '$status'";
	$sql .= " order by time DESC limit 0,1;";
	if(!$result = $db->query($sql)){ echo ('Error in sql '.$sql.'<br/> [' . $db->error . ']');}
	$row = $result->fetch_assoc();
	return $row;	
}
function notificatie($notify, $onderwerp, $bericht) {
	global $email_from, $email_notificatie;
	if(!isset($notify)) $notify = $email_notificatie;
	$message = 'Hello '.$email_notificatie.",\r\n\r\n";
	$message .= $bericht;
	$message .= "\r\n\r\n\r\nVerzonden door HomeWizardPHP op ".strftime("%A %e %B %Y",time())." om ".strftime("%k:%M",time());
	$headers = 'From: HomeWizardPHP <'.$email_from. ">\r\n";
	$headers .='Reply-To: '.$email_from . "\r\n";
	$headers .='X-Mailer: PHP/' . phpversion();
	mail ($notify ,$onderwerp ,$message, $headers );
}
?>