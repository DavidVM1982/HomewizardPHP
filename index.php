<?php
include "header.php";
if (isset($_POST['schakel'])) {
	if($authenticated == true) {
		if (isset($_POST['dimlevel'])) {
			$responsejson = file_get_contents($jsonurl.'sw/dim/'.$_POST['switch'].'/'.$_POST['dimlevel']);
			$response = json_decode($responsejson, true);
			if($response['status']=='ok') {echo '<div class="row">OK</div>'; } else {echo '<div class="row">response = ';print_r($response);echo '</div><hr>';}
			if($debug=='yes') {echo '<br/>$_POST = ';print_r($_POST);echo "<br/>sw/dim/".$_POST['switch']."/".$_POST['dimlevel']."<hr>";}
		} else if (isset($_POST['somfy'])){
			$responsejson = file_get_contents($jsonurl.'sf/'.$_POST['switch'].'/'.$_POST['somfy']);
			$response = json_decode($responsejson, true);
			if($response['status']=='ok') {echo '<div class="row">OK</div>'; } else {echo '<div class="row">response = ';print_r($response);echo '</div><hr>';}
			if($debug=='yes') {echo '<br/>$_POST = ';print_r($_POST);echo "<br/>sf/".$_POST['switch']."/".$_POST['somfy']."<hr>";}
		} else if (isset($_POST['schakel'])){
			$responsejson = file_get_contents($jsonurl.'sw/'.$_POST['switch'].'/'.$_POST['schakel']);
			$response = json_decode($responsejson, true);
			if($response['status']=='ok') {echo '<div class="row">OK</div>'; } else {echo '<div class="row">response = ';print_r($response);echo '</div><hr>';}
			if($debug=='yes') {echo '<br/>$_POST = ';print_r($_POST);echo "<br/>sw/".$_POST['switch']."/".$_POST['schakel']."<hr>";}
		} 
	} else {echo '<p class="error">Switching blocked when not logged in</p>';}
}
if (isset($_POST['set_temp'])) {
	if($authenticated == true) {
		if(isset($_POST['radiator']) && isset($_POST['set_temp'])) {
			$responsejson = file_get_contents($jsonurl.'sw/'.$_POST['radiator'].'/settarget/'.$_POST['set_temp']);
			$response = json_decode($responsejson, true);
			if($response['status']=='ok') {echo 'OK'; } else {echo 'response = ';print_r($response);echo '<hr>';}
		}
		if($debug=='yes') {echo '<br/>$_POST = ';print_r($_POST);echo "<br>sw/".$_POST['radiator']."/settarget/".$_POST['set_temp']."<hr>";echo 'response = ';print_r($response);echo '<hr>';}
	} else {echo '<p class="error">Switching blocked when not logged in</p>';}
}
if (isset($_POST['schakelscene'])) {
	if($authenticated == true) {
		$responsejson = file_get_contents($jsonurl.'gp/'.$_POST['scene'].'/'.$_POST['schakelscene']);
		$response = json_decode($responsejson, true);
		if($response['status']=='ok') {echo 'OK'; } else {echo 'response = ';print_r($response);echo '<hr>';}
		if($debug=='yes') {echo '<br/>$_POST = ';print_r($_POST);echo "<br/>gp/".$_POST['scene']."/".$_POST['schakelscene']."<hr>";echo 'response = ';print_r($response);echo '<hr>';}
	} else {echo '<p class="error">Switching blocked when not logged in</p>';}
}
$data = null;
if($authenticated == true && $developermode != 'yes') { 
	try {
	  $json = file_get_contents($jsonurl.'get-status');
	  
	} catch (Exception $e) {echo $e->getMessage();}
} else if ($developermode == 'yes') {
	print '<section class="span_3"><p class="error">Developer mode</p></section>';
	$json = $developerjson;
} else {
	print '<section class="span_3"><p class="error">Demo mode, no actual data shown.</p></section>';
	$json = $developerjson;
}
$data = json_decode($json,true);
if($authenticated == true && $debug=='yes') {echo '<div style="width:100%">'.$json.'</div><hr>';}


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
		${'switchstatus'.$switch['id']} = 'off';
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
//$scenes =  $data['response']['scenes'];
$thermometers =  $data['response']['thermometers'];
$rainmeters =  $data['response']['rainmeters'];
$windmeters =  $data['response']['windmeters'];

//---SCHAKELAARS---
$sql="select id_switch, name, type, favorite, volgorde from switches where type not in ('radiator', 'somfy')";
if (!isset($_POST['showallswitches'])) $sql.=" AND favorite like 'yes'";
$sql.=" order by volgorde asc, favorite desc, name asc";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
if($result->num_rows>0) {
$group = 0;
echo '<div class="isotope"><div class="item gradient"><p class="number">'.$positie_schakelaars.'</p><form id="showallswitches" action="#" method="post"><input type="hidden" name="showallswitches" value="yes"><a href="#" onclick="document.getElementById(\'showallswitches\').submit();" style="text-decoration:none"><h2 >Schakelaars</h2></a></form><table align="center"><tbody>';
while($row = $result->fetch_assoc()){
	$switchon = "";
	$tdstyle = '';
	if($group != $row['volgorde']) $tdstyle = 'style="border-top:1px solid black"';
	$group = $row['volgorde'];
	if($row['type']=='asun') {if(${'switchstatus'.$row['id_switch']}=="1") {$switchon = "off";} else {$switchon = "on";}}
	else {if(${'switchstatus'.$row['id_switch']}=="on") {$switchon = "off";} else {$switchon = "on";}}
	echo '<tr><form method="post" action="#"><td><img id="'.$row['type'].'Icon" src="images/empty.gif" /></td><td align="right" '.$tdstyle.'>'.$row['name'].'</td>
	<td width="100px" '.$tdstyle.' ><input type="hidden" name="switch" value="'.$row['id_switch'].'"/><input type="hidden" name="schakel" value="'.$switchon.'"/>';
	if($row['type']=='dimmer') {
		print '<output for=dimlevel id=dimlevel>'.${'switchstatus'.$row['id_switch']}.'</output>
		<input name="dimlevel" class="input-range" type ="range" min ="0" max="100" step ="1" value ="'.${'switchstatus'.$row['id_switch']}.'" onChange="this.form.submit()"/>
		';
	}
	else if($row['type']=='asun') {
		print '
		<section class="slider">	
			<input type="hidden" value="somfy" />
			<input type="checkbox" value="switch'.$row['id_switch'].'" id="switch'.$row['id_switch'].'" name="switch'.$row['id_switch'].'" '; if(${'switchstatus'.$row['id_switch']}==1) {print 'checked';} print ' onChange="this.form.submit()"/>
			<label for="switch'.$row['id_switch'].'"></label>
		</section>';
	}
	else {
		print '
		<section class="slider">	
			<input type="checkbox" value="switch'.$row['id_switch'].'" id="switch'.$row['id_switch'].'" name="switch'.$row['id_switch'].'" '; if($switchon=="off") {print 'checked';} print ' onChange="this.form.submit()"/>
			<label for="switch'.$row['id_switch'].'"></label>
		</section>';
	}
	print '</td></form></tr>';
}
$result->free();
echo "</tbody></table></div>";
}
/* SCENES */
if(!empty($scenes)) {
echo '<div class="item gradient"><p class="number">'.$positie_scenes.'</p><h2>Scènes</h2>';
foreach($scenes as $scene){
	echo '<table width="100%"><thead><tr><th colspan="2">';
	if($detailscenes=='optional') {print '<a href="#" onclick="toggle_visibility(\'scene'.$scene['id'].'\');" style="text-decoration:none">'.$scene['name'].'</a>';} else {print $scene['name'];}
	print '<img id="'.$row['type'].'Icon" src="images/empty.gif" /></th>
	<th width="50px"><form method="post" action="#"><input type="hidden" name="scene" value="'.$scene['id'].'"/><input type="hidden" name="schakelscene" value="on"/><input type="submit" value="AAN" class="abutton gradient"/></form></th>
	<th width="50px"><form method="post" action="#"><input type="hidden" name="scene" value="'.$scene['id'].'"/><input type="hidden" name="schakelscene" value="off"/><input type="submit" value="UIT" class="abutton gradient"/></form></th>
	</tr></thead>';
	if(($detailscenes=='yes') || ($detailscenes=='optional')) {
		if($detailscenes=='optional') {print '<tbody id="scene'.$scene['id'].'" style="display:none" class="handje">';} else {print '<tbody>';}
		$datascene = null;
		$datascenes = null;
		try {
			$jsonscene = file_get_contents($jsonurl.'gp/get/'.$scene['id']);
			$datascenes = json_decode($jsonscene,true);
			if($debug=='yes') print_r($datascenes);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		if (!$datascenes) {
			echo "No information available...";
		} else {
			foreach($datascenes['response'] as $datascene) {
			print '<tr><td align="right" width="60px">'.$datascene['type'].'&nbsp;&nbsp;</td><td align="left">&nbsp;'.$datascene['name'].'</td><td>'.$datascene['onstatus'].'</td><td>'.$datascene['offstatus'].'</td></tr>';
			}
		}
		echo '</tbody>';
	}
	print '</table>';
}
echo "</div>";
}
/* SOMFY */
$sql="select id_switch, name, volgorde from switches where type like 'somfy' order by volgorde asc, favorite desc, name asc";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
if($result->num_rows>0) {
	$group = 0;
echo '<div class="item gradient"><p class="number">'.$positie_somfy.'</p><h2>Somfy</h2><table align="center"><tbody>';
while($row = $result->fetch_assoc()){
	$tdstyle = '';
	if($group != $row['volgorde']) $tdstyle = 'style="border-top:1px solid black"';
	$group = $row['volgorde'];
	print '<tr><td><img id="somfyIcon" src="images/empty.gif" /></td><td align="right" '.$tdstyle.'>'.$row['name'].'</td>
	<td width="185px" '.$tdstyle.'><form method="post" action="#">
	<input type="hidden" name="switch" value="'.$row['id_switch'].'"/>
	<input type="hidden" name="schakel" value="'.$row['id_switch'].'"/>
	<input type="submit" id="somfydownIcon" name="somfy" value="down" class="abuttonsomfy handje gradient"/>
	<input type="submit" id="somfystopIcon" name="somfy" value="stop" class="abuttonsomfy handje gradient"/>
	<input type="submit" id="somfyupIcon" name="somfy" value="up" class="abuttonsomfy handje gradient"/>
	
	</form></td></tr>';
}
$result->free();
echo "</tbody></table></div>";
}

//---RADIATORS---
$sql="select id_switch, name, volgorde from switches where type like 'radiator' order by volgorde asc, favorite desc, name asc";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
if($result->num_rows>0) {
	$group = 0;
echo '<div class="item gradient"><p class="number">'.$positie_radiatoren.'</p><h2>Radiatoren</h2><table align="center"><tbody>';
while($row = $result->fetch_assoc()){
	$tdstyle = '';
	if($group != $row['volgorde']) $tdstyle = 'style="border-top:1px solid black"';
	$group = $row['volgorde'];
	print '<tr><td><img id="radiatorIcon" src="images/empty.gif" /></td><td align="right" '.$tdstyle.'>'.$row['name'].'</td>
	<td width="115px" '.$tdstyle.'><form method="post" action="#">
	<input type="hidden" name="radiator" value="'.$row['id_switch'].'"/>
	<select name="set_temp"  class="abutton handje gradient" onChange="this.form.submit()" style="margin-top:4px">
		<option '.${'switchstatus'.$row['id_switch']}.') selected>'.${'switchstatus'.$row['id_switch']}.'</option>
		<option>10</option>
		<option>16</option>
		<option>18</option>
		<option>19</option>
		<option>20</option>
		<option>21</option>
		<option>22</option>
	</select>
	</form></td></tr>';
}
$result->free();
echo "</tbody></table></div>";
}

//---SENSORS--
echo '<div class="item handje gradient" onclick="window.location=\'history.php\';"><p class="number">'.$positie_sensoren.'</p><h2>Sensoren</h2>';
$sql="select id_sensor, name, type, volgorde from sensors order by volgorde asc, favorite desc, name asc";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
if($result->num_rows>0) {
$group = 0;
echo '<table align="center" width="100%">';
while($row = $result->fetch_assoc()){
        echo '<tr>';
        	$type = $row['type'];
			echo '<td style="color:#F00; font-weight:bold"><img id="'.$type.'Icon" src="images/empty.gif" /></td>';
        	if($type=="contact") $type = "Magneet";
			if($type=="motion") $type = "Beweging";
			if($type=="doorbell") $type = "Deurbel";
			if($type=="smoke") $type = "Rook";
			if(${'sensorstatus'.$row['id_sensor']} == "yes") {echo '<td style="color:#F00; font-weight:bold">'.$row['name'].'</td>';} else {echo '<td>'.$row['name'].'</td>';}
        	if(${'sensorstatus'.$row['id_sensor']} == "yes") {echo '<td style="color:#F00; font-weight:bold">';} else {echo '<td>';}
			if($type=="Magneet" && ${'sensorstatus'.$row['id_sensor']} == "no") { echo 'Gesloten'; }
			else if ($type=="Magneet" && ${'sensorstatus'.$row['id_sensor']} == "yes") { echo 'Open'; }
			else if ($type=="Beweging" && ${'sensorstatus'.$row['id_sensor']} == "yes") { echo 'Beweging'; }
			else if ($type=="Beweging" && ${'sensorstatus'.$row['id_sensor']} == "no") { echo ''; }
			else if ($type=="Deurbel" && ${'sensorstatus'.$row['id_sensor']} == "no") { echo ''; }
			else if ($type=="Deurbel" && ${'sensorstatus'.$row['id_sensor']} == "yes") { echo 'Ring'; }
			else if ($type=="Rook" && ${'sensorstatus'.$row['id_sensor']} == "no") { echo ''; }
			else if ($type=="Rook" && ${'sensorstatus'.$row['id_sensor']} == "yes") { echo 'ROOK!!!'; }
			else echo ${'sensorstatus'.$row['id_sensor']};
			echo '</td>';
			if(${'sensorstatus'.$row['id_sensor']} == "yes") {echo '<td style="color:#F00; font-weight:bold">'.${'sensortimestamp'.$row['id_sensor']}.'</td>';} else {echo '<td>'.${'sensortimestamp'.$row['id_sensor']}.'</td>';}
		echo '</tr>';
}
echo "</table></div>";
}
//--THERMOMETERS--
if(!empty($thermometers)) {
	echo '<div class="item handje gradient" onclick="window.location=\'temp.php\';"><p class="number">'.$positie_temperatuur.'</p><h2>Temperatuur</h2><table width="100%"><tr><th></th><th>temp</th><th>hum</th></tr>';
	foreach($thermometers as $thermometer){
		if($debug=='yes') print_r($thermometer);
		echo '<tr>';
		if(count($thermometers)>1) {echo '<td>'.$thermometer['name'].'</td>';} else { echo '<td></td>';}
		echo '<td>'.$thermometer['te'].' °C</td><td>'.$thermometer['hu'].' %</td></tr>';
	}
	echo "</table></div>";
}
//--RAINMETERS--
if(!empty($rainmeters)) {
	echo '<div class="item handje gradient" onclick="window.location=\'rain.php\';"><p class="number">'.$positie_regen.'</p><h2>Regen</h2><table width="100%"><tr><th></th><th>Vandaag</th><th>Laatste 3u</th></tr>';
	foreach($rainmeters as $rainmeter){
		if($debug=='yes') print_r($rainmeter);
		echo '<tr>';
		if(count($rainmeters)>1) {echo '<td>'.$rainmeter['name'].'</td>';} else { echo '<td></td>';}
		echo '<td>'.$rainmeter['mm'].' mm</td><td>'.$rainmeter['3h'].' mm</td></tr>';
	}
	echo "</table></div>";
}
//--WINDMETERS--
if(!empty($windmeters)) {
	echo '<div class="item handje gradient" onclick="window.location=\'wind.php\';"><p class="number">'.$positie_wind.'</p><h2>Wind</h2><table width="100%"><tr><th></th><th>ws</th><th>gu</th><th>dir</th></tr>';
	foreach($windmeters as $windmeter){
		if($debug=='yes') print_r($windmeter);
		if(isset($windmeter['ws'])) {
			echo '<tr>';
			if(count($windmeters)>1) {echo '<td>'.$windmeter['name'].'</td>';} else { echo '<td></td>';}
			echo '<td>'.$windmeter['ws'].' km/u</td><td>'.$windmeter['gu'].' km/u</td><td>'.$windmeter['dir'].' °</td></tr>';
		}
	}
	echo "</table></div>";
}
?>
<script type="text/javascript">
<!--
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'inherit')
          e.style.display = 'none';
       else
          e.style.display = 'inherit';
    }
//-->
</script>
<?PHP include "footer.php";?>