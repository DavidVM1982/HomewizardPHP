<?php
// Copy alles hieronder over naar actionscron.php na testen. 

include "data.php";
include "functions.php";
echo '<div class="item wide gradient"><p class="number">2</p><br/>';
echo substr($sensortimestamp1,0,-2).'00';
echo '</div>';

echo '<div class="item wide gradient"><p class="number">3</p><br/>Actie brander';
if($actie_brander=='yes') {
	echo ' actief</b><br/><br/>';
	if($switchstatus6>$thermometerte4+0.3 || $switchstatus7>$thermometerte6+0.3 || $switchstatus8>$thermometerte7+0.3 || $switchstatus14>$thermometerte5+0.3 || $switchstatus15>$thermometerte5+0.3) {
		echo 'Een van de radiatoren heeft warmte nodig<br/>';
		if($switchstatus12=='off') {
			echo "schakel(12, 'on', 'c');sleep(5);schakel(12, 'on', 'd');sleep(5)";
			if(!isset($_POST['showtest'])) schakel(12, 'on', 'c');sleep(5);schakel(12, 'on', 'd');sleep(5);
		}
	}
	if($switchstatus6<$thermometerte4+0 && $switchstatus7<$thermometerte6+0 && $switchstatus8<$thermometerte7+0 && $switchstatus14<$thermometerte5+0 && $switchstatus15<$thermometerte5+0) {
		echo 'Geen enkele radiator heeft warmte nodig<br/>';
		if($switchstatus12=='on') {
			echo "schakel(12, 'off', 'c');sleep(5);schakel(12, 'off', 'd');sleep(5)";
			if(!isset($_POST['showtest'])) schakel(12, 'off', 'c');sleep(5);schakel(12, 'off', 'd');sleep(5);
		}
	}
} else {
	echo ' niet actief<br/>';
}
echo '</div>';

//Timer radiatoren living
echo '<div class="item wide gradient"><p class="number">3</p><br/>Actie timer radiatoren living';
$tempw = 20;
$tempk = 17;
if($actie_timer_living=='yes'){
	echo ' actief</b><br/><br/>';
	if(time()>(strtotime('18:00')-(($tempw-$thermometerte5)*(($tempw-$thermometerte1)*10))) && (time()<(strtotime('22:00'))) && ($switchstatus14<$tempw || $switchstatus15<$tempw) && in_array(date('N', time()), array(1,2,3,4))) {
		echo "radiator(14, ".$tempw.", 'c');sleep(2)<br/>";
		if(!isset($_POST['showtest'])) radiator(14, $tempw, 'c');sleep(2);
		echo "radiator(15, ".$tempw.", 'c');sleep(2)<br/>";
		if(!isset($_POST['showtest'])) radiator(15, $tempw, 'c');sleep(2);
	} else if(time()>(strtotime('18:00')-(($tempw-$thermometerte5)*(($tempw-$thermometerte1)*10))) && (time()<(strtotime('23:00'))) && ($switchstatus14<$tempw || $switchstatus15<$tempw) && in_array(date('N', time()), array(5,6,7))) {
		echo "radiator(14, ".$tempw.", 'c');sleep(2)<br/>";
		if(!isset($_POST['showtest'])) radiator(14, $tempw, 'c');sleep(2);
		echo "radiator(15, ".$tempw.", 'c');sleep(2)<br/>";
		if(!isset($_POST['showtest'])) radiator(15, $tempw, 'c');sleep(2);
	} else if(time()>(strtotime('8:00')) && (time()<(strtotime('23:00'))) && ($switchstatus14>$tempk || $switchstatus15>$tempk)) {
	} else {
		if($switchstatus14>$tempk || $switchstatus15>$tempk) {
			echo "Een van de radiatoren staat warmer dan ".$tempk." °C<br/>";
			$laatsteschakel = laatsteschakeltijd(14,null, 'm');
			$laatsteschakel2 = laatsteschakeltijd(15,null, 'm');
			if($laatsteschakel2>$laatsteschakel) $laatsteschakel = $laatsteschakel2;
			if($laatsteschakel['timestamp']<(time()-7200))  {
				echo "radiator(14, ".$tempk.", 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) radiator(14, $tempk, 'c');sleep(2);
				echo "radiator(15, ".$tempk.", 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) radiator(15, $tempk, 'c');sleep(2);
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
			if(!isset($_POST['showtest'])) radiator(14, $tempk, 'c');sleep(2);
			if(!isset($_POST['showtest'])) radiator(15, $tempk, 'c');sleep(2);
		}
	}
}
echo '</div>';

//Timer radiator badkamer
echo '<div class="item wide gradient"><p class="number">3</p><br/>Actie timer radiator badkamer';
$tempw = 23;
$tempk = 17;
if($actie_timer_badkamer=='yes'){
	echo ' actief</b><br/><br/>';
	if((time()>(strtotime('6:00')-(($tempw-$thermometerte4)*(($tempw-$thermometerte1)*10)))) && (time()<(strtotime('7:40'))) && ($switchstatus6<$tempw) && in_array(date('N', time()), array(1,2,3,4,5))) {
		echo "radiator(6, ".$tempw.", 'c');sleep(2)<br/>";
		if(!isset($_POST['showtest'])) radiator(6, $tempw, 'c');sleep(2);
	} else {
		if($switchstatus6>$tempk) {
			$laatsteschakel = laatsteschakeltijd(6,null, 'm');
			if($laatsteschakel['timestamp']<(time()-7200)) {
				echo "radiator(6, ".$tempk.", 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) radiator(6, $tempk, 'c');sleep(2);
			}
		}
	}
} else {
	echo ' niet actief<br/>';
	if($switchstatus6>$tempk) {
		$laatsteschakel = laatsteschakeltijd(6,null, 'm');
		if($laatsteschakel['timestamp']<(time()-7200)) {
			echo "radiator(6, ".$tempk.", 'c');sleep(2)<br/>";
			if(!isset($_POST['showtest'])) radiator(6, $tempk, 'c');sleep(2);
		}
	}
}
echo '</div>';

//Timer radiator slaapkamer
echo '<div class="item wide gradient"><p class="number">3</p><br/>Actie timer radiator slaapkamer ';
if($actie_timer_slaapkamer=='yes'){
	echo ' actief</b><br/><br/>';
	$tempw = 18;
	$tempk = 8;
	if(time()>(strtotime('22:50')-(($tempw-$thermometerte6)*(($tempw-$thermometerte1)*10))) && (time()<(strtotime('23:00'))) && ($switchstatus7<$tempw)) { echo "radiator(7, ".$tempw.", 'c')<br/>";
	} else {
		if($switchstatus7>$tempk) {
			$laatsteschakel = laatsteschakeltijd(7,null, 'm');
			if($laatsteschakel['timestamp']<(time()-7200))  {
				echo "radiator(7, ".$tempk.", 'c');sleep(2)<br/>";
			if(!isset($_POST['showtest'])) radiator(7, $tempk, 'c');sleep(2);
			}
		}
	}
} else {
	echo ' niet actief<br/>';
	if($switchstatus7>$tempk) {
		$laatsteschakel = laatsteschakeltijd(7,null, 'm');
		if($laatsteschakel['timestamp']<(time()-7200)) {
			echo "radiator(7, ".$tempk.", 'c');sleep(2)<br/>";
			if(!isset($_POST['showtest'])) radiator(7, $tempk, 'c');sleep(2);
		}
	}
}
echo '</div>';

//Timer radiator slaapkamer Tobi
echo '<div class="item wide gradient"><p class="number">3</p><br/>Actie timer radiator slaapkamer Tobi';
$tempw = 18;
$tempk = 8;
if($actie_timer_slaapkamertobi=='yes'){
	echo ' actief</b><br/><br/>';
	if(time()>(strtotime('21:20')-(($tempw-$thermometerte7)*(($tempw-$thermometerte1)*10))) && (time()<(strtotime('21:30'))) && ($switchstatus7<$tempw) && ((in_array(date('N', time()), array(5,6)) && date('W', time()) %2 == 0) || (in_array(date('N', time()), array(3,4))))) {
		echo "radiator(8, ".$tempw.", 'c');sleep(2)<br/>";
	} else {
		if($switchstatus8>$tempk) {
			$laatsteschakel = laatsteschakeltijd(8,null, 'm');
			if($laatsteschakel['timestamp']<(time()-7200))  {
				echo "radiator(8, ".$tempk.", 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) radiator(8, $tempk, 'c');sleep(2);
			}
		}
	}
} else {
	echo ' niet actief<br/>';
	if($switchstatus8>$tempk) {
		$laatsteschakel = laatsteschakeltijd(8,null, 'm');
		if($laatsteschakel['timestamp']<(time()-7200))  {
			echo "radiator(8, ".$tempk.", 'c');sleep(2)<br/>";
			if(!isset($_POST['showtest'])) radiator(8, $tempk, 'c');sleep(2);
		}
	}
}
echo '</div>';

//Uitschakelen licht garage
echo '<div class="item wide gradient"><p class="number">3</p><br/>Actie timer uitschakelen licht garage';
if($actie_lichtgarage=='yes') {
	echo ' actief</b><br/><br/>';
	if($switchstatus1=='on') {
		if(strtotime($sensortimestamp1)>time()) {$sensor1tijd = laatstesensortijd($sensorid1,null);$sensortimestamp1 = strtotime($sensor1tijd['time']);} else {$sensortimestamp1 = strtotime($sensortimestamp1);}
		if(strtotime($sensortimestamp2)>time()) {$sensor2tijd = laatstesensortijd($sensorid2,null);$sensortimestamp2 = strtotime($sensor2tijd['time']);} else {$sensortimestamp2 = strtotime($sensortimestamp2);}
		if($sensortimestamp1<(time()-200) && $sensortimestamp2<(time()-200)) {
			$laatsteschakel = laatsteschakeltijd(1,null, 'm');
			if($laatsteschakel['timestamp']<(time()-7200) || $laatsteschakel['type']=='off') {
				echo "schakel(1, 'off', 'c');sleep(2)<br/>";
				if(!isset($_POST['showtest'])) schakel(1, 'off', 'c');sleep(2);
			}
		} 
	}
} else {
	echo ' niet actief<br/>';
}
echo '</div>';


if(!isset($_POST['actionscron']) && !isset($_POST['showtest'])) {ob_clean(); $db->close();}
?>
