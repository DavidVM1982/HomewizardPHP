<?php
if(!isset($_POST['actionscron'])) {
include "parameters.php";
	$sql="select variable, value from settings order by variable asc";
	if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
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
$authenticated=true;
 
//Plak hieronder alles van test.php na testen. 

include "data.php";
include "functions.php";

//Brander schakelen.
echo '<div class="item wide gradient" align="left"><p class="number">3</p><br/>Actie brander';
if($actie_brander=='yes') {
	echo ' actief</b><br/><br/>';
	$aantalradiatoren = 0;
	if($switchstatus6>$thermometerte4) {$aantalradiatoren = $aantalradiatoren + 1;}
	if($switchstatus7>$thermometerte6) {$aantalradiatoren = $aantalradiatoren + 1;}
	if($switchstatus8>$thermometerte7) {$aantalradiatoren = $aantalradiatoren + 1;}
	if($switchstatus14>$thermometerte5) {$aantalradiatoren = $aantalradiatoren + 1;}
	if($switchstatus15>$thermometerte5) {$aantalradiatoren = $aantalradiatoren + 1;}
	echo 'Temperatuur Eetplaats is '.$thermometerte5.'°C, radiator staat op '.$switchstatus14.'°C.<br/>';
	echo 'Temperatuur Zithoek is '.$thermometerte5.'°C, radiator staat op '.$switchstatus15.'°C.<br/>';
	echo 'Temperatuur Badkamer is '.$thermometerte4.'°C, radiator staat op '.$switchstatus6.'°C.<br/>';
	echo 'Temperatuur Slaapkamer is '.$thermometerte6.'°C, radiator staat op '.$switchstatus7.'°C.<br/>';
	echo 'Temperatuur Slaapkamer Tobi is '.$thermometerte7.'°C, radiator staat op '.$switchstatus8.'°C.<br/>';
	if($aantalradiatoren==1) {
		echo $aantalradiatoren.' radiator heeft warmte nodig<br/>';
	} else {
		echo $aantalradiatoren.' radiatoren hebben warmte nodig<br/>';
	}
	if($switchstatus12=='off') echo 'De brander brandt niet.<br/>'; else echo 'De brander brandt.<br/>';
	if($aantalradiatoren>0 && $switchstatus12=='off') {
			echo "schakel(12, 'on', 'c');sleep(5);schakel(12, 'on', 'd');sleep(5)";
			if(!isset($_POST['showtest'])) schakel(12, 'on', 'c', $email_notificatie, 'yes');sleep(2);schakel(12, 'on', 'd', $email_notificatie, 'yes');sleep(2);
		
	} else if($aantalradiatoren==0 && $switchstatus12=='on'){
		echo "schakel(12, 'off', 'c');sleep(5);schakel(12, 'off', 'd');sleep(5)";
		if(!isset($_POST['showtest'])) schakel(12, 'off', 'c', $email_notificatie, 'yes');sleep(2);schakel(12, 'off', 'd', $email_notificatie, 'yes');sleep(2);
	}
} else {
	echo ' niet actief<br/>';
}
echo '</div>';

//Timer radiatoren living
echo '<div class="item wide gradient" align="left"><p class="number">3</p><br/>Actie timer radiatoren living';
$tempw = 19;
$tempk = 14;
$voorwarmen = ceil(($tempw-$thermometerte5)*($tempw-$thermometerte1)*60);
if($actie_timer_living=='yes' && $actie_thuis=='yes'){
	echo ' actief</b><br/><br/>';
	if(in_array(date('N', time()), array(1,2,3,4))) {
		echo 'Vandaag is het een werkdag en het is nu '.date('H:i', time()).'.<br/>';
		echo 'We willen warmte van 18:00 tem 22:00.<br/>';
		echo 'We verwarmen '.($tempw-$thermometerte5).' x '.($tempw-$thermometerte1).' x 60 = '.$voorwarmen.' sec vooraf.<br/>';
		if(time()>(strtotime('18:00')-(($tempw-$thermometerte5)*(($tempw-$thermometerte1)*60))) && (time()<(strtotime('22:00')))) {
			echo 'Het is nu tussen 18:00 en 22:00.<br/>';
			if($switchstatus14<$tempw) {
				echo "Radiator eetplaats staat kouder dan ".$tempw."°C<br/>";
				echo "radiator(14, ".$tempw.", 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) {radiator(14, $tempw, 'c', $email_notificatie, 'yes');sleep(2);}
			}
			if($switchstatus15<$tempw) {
				echo "Radiator zithoek staat kouder dan ".$tempw."°C<br/>";
				echo "radiator(15, ".$tempw.", 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) {radiator(15, $tempw, 'c', $email_notificatie, 'yes');sleep(2);}
			}
		}
	} else if (in_array(date('N', time()), array(5,6,7))) {
		echo 'Vandaag is het weekend en het is nu '.date('H:i', time()).'.<br/>';
		echo 'We willen warmte van 7:00 tem 22:00.<br/>';
		echo 'We verwarmen '.($tempw-$thermometerte5).' x '.($tempw-$thermometerte1).' x 60 = '.$voorwarmen.' sec vooraf.<br/>';
		if(time()>(strtotime('7:00')-$voorwarmen) && (time()<(strtotime('23:00')))) {
			echo 'Het is nu tussen '.date('H:i', (strtotime('7:00')-$voorwarmen)).' en 23:00.<br/>';
			if($switchstatus14<$tempw || $switchstatus15<$tempw) {
				echo "Een van de radiatoren staat kouder dan ".$tempw."°C<br/>";
				echo "radiator(14, ".$tempw.", 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) {radiator(14, $tempw, 'c', $email_notificatie, 'yes');sleep(2);}
				echo "radiator(15, ".$tempw.", 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) {radiator(15, $tempw, 'c', $email_notificatie, 'yes');sleep(2);}
			} else {
				echo "Beide radiatoren staan reeds warmer dan ".$tempw."°C.<br/>";
			}
		}
	} else if(time()>(strtotime('8:00')) && (time()<(strtotime('23:00'))) && ($switchstatus14>$tempk || $switchstatus15>$tempk)) {
			echo 'Manueel hoger gezet, niks doen dus.';
	} else {
		if($switchstatus14>$tempk || $switchstatus15>$tempk) {
			echo "Buiten de daguren en van de radiatoren staat warmer dan ".$tempk." °C<br/> Lager zetten indien niet manueel gezet in de laatste 2 uur. ";
			$laatsteschakel = laatsteschakeltijd(14,null, 'm');
			$laatsteschakel2 = laatsteschakeltijd(15,null, 'm');
			if($laatsteschakel2>$laatsteschakel) $laatsteschakel = $laatsteschakel2;
			if($laatsteschakel['timestamp']<(time()-7200))  {
				echo "radiator(14, ".$tempk.", 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) {radiator(14, $tempk, 'c', $email_notificatie, 'yes');sleep(2);}
				echo "radiator(15, ".$tempk.", 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) {radiator(15, $tempk, 'c', $email_notificatie, 'yes');sleep(2);}
			}
		}
	}
} else {
	echo ' niet actief<br/>';
	if($switchstatus14>$tempk || $switchstatus15>$tempk) {
		$laatsteschakel = laatsteschakeltijd(14,null, 'm');
		$laatsteschakel2 = laatsteschakeltijd(15,null, 'm');
		if($laatsteschakel2>$laatsteschakel) $laatsteschakel = $laatsteschakel2;
		if($laatsteschakel['timestamp']<(time()-7200))  {
			echo "radiator(14, ".$tempk.", 'c');sleep(2);<br/>radiator(15, ".$tempk.", 'c');sleep(2)<br/>";
			if(!isset($_POST['showtest'])) {
				radiator(14, $tempk, 'c', $email_notificatie, 'yes');sleep(2);
				radiator(15, $tempk, 'c', $email_notificatie, 'yes');sleep(2);
			}
		}
	}
}
echo '</div>';

//Timer radiator badkamer
echo '<div class="item wide gradient" align="left"><p class="number">3</p><br/>Actie timer radiator badkamer';
$tempw = 22;
$tempk = 15;
if($actie_timer_badkamer=='yes' && $actie_thuis=='yes'){
	echo ' actief</b><br/><br/>';
	if(in_array(date('N', time()), array(1,2,3,4,5))) {
		echo 'Vandaag is het een werkdag<br/>';
		if((time()>(strtotime('6:00')-(($tempw-$thermometerte4)*(($tempw-$thermometerte1)*60)))) && (time()<(strtotime('7:30')))) {
			echo 'Tussen 6 en 7:30, tijd voor warmte<br/>';
			if($switchstatus6<$tempk) {
				echo "radiator(6, ".$tempw.", 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) {
					radiator(6, $tempw, 'c', $email_notificatie, 'yes');sleep(2);
					radiator(6, $tempw, 'd', $email_notificatie, 'yes');sleep(2);
				}
			}
		} 
	} else if(in_array(date('N', time()), array(6,7))) {
		echo 'Vandaag is het weekend<br/>';
		if((time()>(strtotime('7:30')-(($tempw-$thermometerte4)*(($tempw-$thermometerte1)*60)))) && (time()<(strtotime('9:30')))) {
			echo 'Tussen 7:30 en 9:30, tijd voor warmte<br/>';
			if($switchstatus6<$tempw) {
				echo "radiator(6, ".$tempw.", 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) {
					radiator(6, $tempw, 'c', $email_notificatie, 'yes');sleep(2);
					radiator(6, $tempw, 'd', $email_notificatie, 'yes');sleep(2);
				}
			}
		} else {
			echo 'Geen tijd voor warmte<br/>';
			if($switchstatus6==$tempk) echo 'Radiator staat al op koude temperatuur.<br/>';
				if($switchstatus6>$tempk) {
				echo 'Te warm in de badkamer<br/>';
				$laatsteschakel = laatsteschakeltijd(6,null, 'm');
				echo 'Er werd laatst manueel geschakeld om '.date("j M Y H:i:s",$laatsteschakel['timestamp']).'<br/>';
				if($laatsteschakel['timestamp']<(time()-7200)) {
					echo "radiator(6, ".$tempk.", 'c');sleep(2)<br/>";
					if(!isset($_POST['showtest'])) {
						radiator(6, $tempk, 'c', $email_notificatie, 'yes');sleep(2);
						radiator(6, $tempk, 'd', $email_notificatie, 'yes');sleep(2);
					}
				}
			}
		}
	}
} else {
	echo ' niet actief<br/>';
	if($switchstatus6>$tempk) {
		echo 'Radiotor ingesteld op '.$switchstatus6.'°C terwijl de actie niet actief is. Manueel geschakeld in de laatste 2 uur?';
		$laatsteschakel = laatsteschakeltijd(6,null, 'm');
		if($laatsteschakel['timestamp']<(time()-7200)) {
			echo "radiator(6, ".$tempk.", 'c');sleep(2)<br/>";
			if(!isset($_POST['showtest'])) {radiator(6, $tempk, 'c', $email_notificatie, 'yes');sleep(2);radiator(6, $tempk, 'd', $email_notificatie, 'yes');sleep(2);}
		}
	}
}
echo '</div>';

//Timer radiator slaapkamer
echo '<div class="item wide gradient" align="left"><p class="number">3</p><br/>Actie timer radiator slaapkamer ';
if($actie_timer_slaapkamer=='yes' && $actie_thuis=='yes'){
	echo ' actief</b><br/><br/>';
	$tempw = 18;
	$tempk = 5;
	if(time()>(strtotime('22:00')-(($tempw-$thermometerte6)*(($tempw-$thermometerte1)*60))) && (time()<(strtotime('23:00')))) {
		echo 'Tijd voor warmte<br/>';
		if($switchstatus7<$tempw) {
			echo "Radiator 7 verhogen naar ".$tempw." °C.<br/>";
			if(!isset($_POST['showtest'])) radiator(7, $tempw, 'c', $email_notificatie, 'yes');sleep(2);
		} else {
			echo "Radiator al ingesteld op minstens ".$tempw." °C.<br/>";
		}
	} else {
		echo 'Geen tijd voor warmte<br/>';
		if($switchstatus7>$tempk) {
			$laatsteschakel = laatsteschakeltijd(7,null, 'm');
			if($laatsteschakel['timestamp']<(time()-7200))  {
				echo "radiator(7, ".$tempk.", 'c');sleep(2)<br/>";
			if(!isset($_POST['showtest'])) radiator(7, $tempk, 'c', $email_notificatie, 'yes');sleep(2);
			}
		}
	}
} else {
	echo ' niet actief<br/>';
	if($switchstatus7>$tempk) {
		$laatsteschakel = laatsteschakeltijd(7,null, 'm');
		if($laatsteschakel['timestamp']<(time()-7200)) {
			echo "radiator(7, ".$tempk.", 'c')<br/>";
			if(!isset($_POST['showtest'])) radiator(7, $tempk, 'c', $email_notificatie, 'yes');sleep(2);
		}
	}
}
echo '</div>';

//Timer radiator slaapkamer Tobi
echo '<div class="item wide gradient" align="left"><p class="number">3</p><br/>Actie timer radiator slaapkamer Tobi';
$tempw = 18;
$tempk = 5;
if($actie_timer_slaapkamertobi=='yes' && $actie_thuis=='yes'){
	echo ' actief</b><br/><br/>';
	if(date('W', time()) %2 == 0) {
		echo 'Het is een even weeknummer.<br/>';
		if(in_array(date('N', time()), array(3,4,5,6))) {
			echo 'Het is wo, do, vr of za.<br/>';
			if(time()>(strtotime('20:30')-(($tempw-$thermometerte7)*(($tempw-$thermometerte1)*60))) && (time()<(strtotime('21:30')))) {
				echo 'Bijna slaaptijd.<br/>';
				if($switchstatus8<$tempw) {
					echo "radiator(8, ".$tempw.", 'c');sleep(2)<br/>";
					if(!isset($_POST['showtest'])) {radiator(8, $tempw, 'c', $email_notificatie, 'yes');sleep(2);}
				}
			}
		}
	} else {
		echo 'Het is een onevenen weeknummer.<br/>';
		if(in_array(date('N', time()), array(3,4))) {
			echo 'Het is wo of do.<br/>';
			if(time()>(strtotime('21:20')-(($tempw-$thermometerte7)*(($tempw-$thermometerte1)*60))) && (time()<(strtotime('21:30')))) {
				echo 'Bijna slaaptijd.<br/>';
				if($switchstatus8<$tempw) {
					echo "radiator(8, ".$tempw.", 'c');sleep(2)<br/>";
					if(!isset($_POST['showtest'])) {radiator(8, $tempw, 'c', $email_notificatie, 'yes');sleep(2);}
				}
			}
		}
	} 
} else {
	echo ' niet actief<br/>';
	if($switchstatus8>$tempk) {
		$laatsteschakel = laatsteschakeltijd(8,null, 'm');
		if($laatsteschakel['timestamp']<(time()-7200))  {
			echo "radiator(8, ".$tempk.", 'c');sleep(2)<br/>";
			if(!isset($_POST['showtest'])) radiator(8, $tempk, 'c', $email_notificatie, 'yes');sleep(2);
		}
	}
}
echo '</div>';

//Uitschakelen licht garage
echo '<div class="item wide gradient" align="left"><p class="number">3</p><br/>Actie timer uitschakelen licht garage';
if($actie_lichtgarage=='yes') {
	echo ' actief</b><br/><br/>';
	if($switchstatus1=='on') {
		if(strtotime($sensortimestamp1)>time()) {$sensor1tijd = laatstesensortijd($sensorid1,null);$sensortimestamp1 = strtotime($sensor1tijd['time']);} else {$sensortimestamp1 = strtotime($sensortimestamp1);}
		if(strtotime($sensortimestamp2)>time()) {$sensor2tijd = laatstesensortijd($sensorid2,null);$sensortimestamp2 = strtotime($sensor2tijd['time']);} else {$sensortimestamp2 = strtotime($sensortimestamp2);}
		if($sensortimestamp1<(time()-200) && $sensortimestamp2<(time()-200)) {
			$laatsteschakel = laatsteschakeltijd(1,null, 'm');
			if($laatsteschakel['timestamp']<(time()-7200) || $laatsteschakel['type']=='off') {
				echo "schakel(1, 'off', 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) schakel(1, 'off', 'c', $email_notificatie, 'yes');sleep(2);
			}
		} 
	}
} else {
	echo ' niet actief<br/>';
}
echo '</div>';

//Schakel Pluto
echo '<div class="item wide gradient" align="left"><p class="number">3</p><br/>Actie timer Pluto';
if($actie_timer_pluto=='yes'){
	echo ' actief</b><br/><br/>';
	if((time()>(strtotime('11:00'))) && (time()<(strtotime('23:00')))) {
		echo 'Tijd voor Pluto.<br/>';
		if($switchstatus0=='off') {
			echo "schakel(0, 'on', 'c');sleep(2)<br/>";
			if(!isset($_POST['showtest'])) schakel(0, 'on', 'c', $email_notificatie, 'yes');sleep(2);
		} else if ($switchstatus0=='on') {
			echo "Pluto is al actief.<br/>";
		}
	} else {
		echo 'Geen tijd voor Pluto<br/>';
		if($switchstatus0=='on') {
			$laatsteschakel = laatsteschakeltijd(0,null, 'm');
			if($laatsteschakel['timestamp']<(time()-7200))  {
				echo "schakel(0, 'off', 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) schakel(0, 'off', 'c', $email_notificatie, 'yes');sleep(2);
			}
		} else if ($switchstatus0=='off') {
			echo "Pluto is al uitgeschakeld.<br/>";
		}
	}
} else {
	echo ' niet actief<br/>';
	if($switchstatus8>$tempk) {
		$laatsteschakel = laatsteschakeltijd(8,null, 'm');
		if($laatsteschakel['timestamp']<(time()-7200))  {
			echo "schakel(0, 'off', 'c');sleep(2)<br/>";
			if(!isset($_POST['showtest'])) schakel(0, 'off', 'c', $email_notificatie, 'yes');sleep(2);
		}
	}
}
echo '</div>';

//Thuis
echo '<div class="item wide gradient" align="left"><p class="number">3</p><br/>Actie thuis';
if($actie_thuis=='yes'){
	echo ' niet actief</b><br/><br/>';
} else {
	echo ' actief<br/>';
	echo 'We zijn thuis<br/>';
	if($sensorstatus0=='yes') notificatie($email_notificatie ,'ROOK gedetecteerd op zolder' ,'ROOK gedetecteerd op zolder' );
	if($sensorstatus1=='yes') notificatie($email_notificatie ,'Poort is geopend' ,'Poort is geopend' );
	if($sensorstatus2=='yes') notificatie($email_notificatie ,'Beweging gedetecteerd in garage' ,'Beweging gedetecteerd in garage' );
	if($sensorstatus3=='yes') notificatie($email_notificatie ,'ROOK gedetecteerd in de hall' ,'ROOK gedetecteerd in de hall' );
	if($sensorstatus4=='yes') notificatie($email_notificatie ,'Bel voordeur ingedrukt' ,'Bel voordeur ingedrukt' );
}
echo '</div>';

//Verstuur lege batterij waarschuwing
echo '<div class="item wide gradient" align="left"><p class="number">3</p><br/>Actie batterij waarschuwing';
if($actie_batterij=='yes'){
	echo ' actief</b><br/><br/>';
	//Verstuur alleen om 16:43 elke 3 dagen. 
	if(date('H:i',time())=="16:43" && date('z', time()) %3 == 0) {
		echo 'Tijd om de batterijen op te vragen.<br/>';
		$json = file_get_contents($jsonurl.'get-sensors');
		$data = null;
		$data = json_decode($json,true);
		$thermometers =  $data['response']['thermometers'];
			foreach ($thermometers as $thermometer) {
				if($thermometer['lowBattery']=='yes') notificatie($email_notificatie, "Batterijleeg van ".$thermometer['name']."", "Batterijleeg van ".$thermometer['name']."");
			}
		$windmeters =  $data['response']['windmeters'];	
			foreach ($windmeters as $windmeter) {
				if($windmeter['lowBattery']=='yes') notificatie($email_notificatie, "Batterijleeg van ".$windmeter['name']."", "Batterijleeg van ".$windmeter['name']."");
			}
		$rainmeters =  $data['response']['rainmeters'];
			foreach ($rainmeters as $rainmeter) {
				if($rainmeter['lowBattery']=='yes') notificatie($email_notificatie, "Batterijleeg van ".$rainmeter['name']."", "Batterijleeg van ".$rainmeter['name']."");
			}
		
	} else {
		echo 'Niet nu<br/>';
	}
} else {
	echo ' niet actief<br/>';
	
}
echo '</div>';


if(!isset($_POST['actionscron']) && !isset($_POST['showtest'])) {ob_clean(); $db->close();}

?>