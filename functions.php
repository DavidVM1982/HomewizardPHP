<?php
function schakel($switch, $action, $who) {
	global $jsonurl, $db, $debug;
	$responsejson = file_get_contents($jsonurl.'sw/'.$switch.'/'.$action);
	$response = json_decode($responsejson, true);
	if($response['status']=='ok') {
		$timestamp = time();
		$reply = '<div class="error gradient">OK</div>';
		$sql ="insert into switchhistory (`id_switch`,`timestamp`,`type`,`who`) values ($switch, $timestamp, '$action', '$who');";
		if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
	} else {
		$reply = '<div class="error gradient">response = ';$reply .= print_r($response); $reply .= '</div>';
	}
	if($debug=='yes') {$reply .= '<div class="error gradient">$_POST = ';$reply .= print_r($_POST);$reply .=  "<br/>sw/".$switch."/".$action."</div>";}
	return $reply;
}
function dim($switch, $action, $who) {
	global $jsonurl, $db, $debug;
	$responsejson = file_get_contents($jsonurl.'sw/dim/'.$switch.'/'.$action);
	$response = json_decode($responsejson, true);
	if($response['status']=='ok') {
		$timestamp = time();
		$reply = '<div class="error gradient">OK</div>';
		$sql ="insert into switchhistory (`id_switch`,`timestamp`,`type`,`who`) values ($switch, $timestamp, '$action', '$who');";
		if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
	} else {
		$reply = '<div class="error gradient">response = ';$reply .= print_r($response); $reply .= '</div>';
	}
	if($debug=='yes') {$reply .= '<div class="error gradient">$_POST = ';$reply .= print_r($_POST);$reply .=  "<br/>sw/".$switch."/".$action."</div>";}
	return $reply;
}
function somfy($switch, $action, $who) {
	global $jsonurl, $db, $debug;
	$responsejson = file_get_contents($jsonurl.'sf/'.$switch.'/'.$action);
	$response = json_decode($responsejson, true);
	if($response['status']=='ok') {
		$timestamp = time();
		$reply = '<div class="error gradient">OK</div>';
		$sql ="insert into switchhistory (`id_switch`,`timestamp`,`type`,`who`) values ($switch, $timestamp, '$action', '$who');";
		if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
	} else {
		$reply = '<div class="error gradient">response = ';$reply .= print_r($response); $reply .= '</div>';
	}
	if($debug=='yes') {$reply .= '<div class="error gradient">$_POST = ';$reply .= print_r($_POST);$reply .=  "<br/>sw/".$switch."/".$action."</div>";}
	return $reply;
}
function radiator($switch, $action, $who) {
	global $jsonurl, $db, $debug;
	$responsejson = file_get_contents($jsonurl.'sw/'.$switch.'/settarget/'.$action);
	$response = json_decode($responsejson, true);
	if($response['status']=='ok') {
		$timestamp = time();
		$reply = '<div class="error gradient">OK</div>';
		$sql ="insert into switchhistory (`id_switch`,`timestamp`,`type`,`who`) values ($switch, $timestamp, '$action', '$who');";
		if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
	} else {
		$reply = '<div class="error gradient">response = ';$reply .= print_r($response); $reply .= '</div>';
	}
	if($debug=='yes') {$reply .= '<div class="error gradient">$_POST = ';$reply .= print_r($_POST);$reply .=  "<br/>sw/".$switch."/".$action."</div>";}
	return $reply;
}
function scene($switch, $action, $who) {
	global $jsonurl, $db, $debug;
	$responsejson = file_get_contents($jsonurl.'gp/'.$switch.'/'.$action);
	$response = json_decode($responsejson, true);
	if($response['status']=='ok') {
		$reply = '<div class="error gradient">OK</div>';
	} else {
		$reply = '<div class="error gradient">response = ';$reply .= print_r($response); $reply .= '</div>';
	}
	if($debug=='yes') {$reply .= '<div class="error gradient">$_POST = ';$reply .= print_r($_POST);$reply .=  "<br/>sw/".$switch."/".$action."</div>";}
	return $reply;
}
?>