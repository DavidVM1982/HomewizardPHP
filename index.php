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
try {
  $json = file_get_contents($jsonurl.'get-sensors');
  $data = json_decode($json,true);
} catch (Exception $e) {echo $e->getMessage();}
if (!$data) {echo "No information available...";} else {
if($authenticated == false) { 
print '<section class="span_3"><p class="error">Some information is not available when not logged in</p></section>';
} else {
	if($debug=='yes') {echo '<div class="row">';print_r($data);echo '</div><hr>';}
}
$switches =  $data['response']['switches'];
foreach($switches as $switch) {
	${'switchid'.$switch['id']} = $switch['id'];
	${'switchname'.$switch['id']} = $switch['name'];
	${'switchtype'.$switch['id']} = $switch['type'];
	if($switch['type']=='radiator') { 
		${'switchstatus'.$switch['id']} = $switch['tte']; 
	} else if($switch['type']=='dimmer') {
		${'switchstatus'.$switch['id']} = $switch['dimlevel'];
	} else if($switch['type']=='asun') {
		${'switchstatus'.$switch['id']} = $switch['mode'];
	} else if($switch['type']=='somfy') {
	} else {
		${'switchstatus'.$switch['id']} = $switch['status'];
	}
}
$sensors =  $data['response']['kakusensors'];
foreach($sensors as $sensor) {
	${'sensorid'.$sensor['id']} = $sensor['id'];
	${'sensorname'.$sensor['id']} = $sensor['name'];
	${'sensorstatus'.$sensor['id']} = $sensor['status'];
	${'sensortype'.$sensor['id']} = $sensor['type'];
	${'sensortimestamp'.$sensor['id']} = $sensor['timestamp'];
}
$scenes =  $data['response']['scenes'];
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
echo '<div class="isotope"><div class="item"><form id="showallswitches" action="#" method="post"><input type="hidden" name="showallswitches" value="yes"><a href="#" onclick="document.getElementById(\'showallswitches\').submit();" style="text-decoration:none"><h2 >Schakelaars</h2></a></form><table align="center"><tbody>';
while($row = $result->fetch_assoc()){
	$switchon = "";
	$tdstyle = '';
	if($group != $row['volgorde']) $tdstyle = 'style="border-top:1px solid black"';
	$group = $row['volgorde'];
	if($row['type']=='asun') {if(${'switchstatus'.$row['id_switch']}=="1") {$switchon = "off";} else {$switchon = "on";}}
	else {if(${'switchstatus'.$row['id_switch']}=="on") {$switchon = "off";} else {$switchon = "on";}}
	echo '<tr><form method="post" action="#"><td><img id="'.$row['type'].'Icon" src="images/empty.gif" /></td><td align="right" '.$tdstyle.'>'.$row['name'].'</td>
	<td width="70px" '.$tdstyle.' ><input type="hidden" name="switch" value="'.$row['id_switch'].'"/><input type="hidden" name="schakel" value="'.$switchon.'"/>';
	if($row['type']=='dimmer') {
		print '<select name="dimlevel"  class="abutton handje" onChange="this.form.submit()" style="margin-top:4px; width:80px; ">
		<option '.${'switchstatus'.$row['id_switch']}.') selected>'.${'switchstatus'.$row['id_switch']}.'</option>
		<option>0</option>
		<option>10</option>
		<option>20</option>
		<option>30</option>
		<option>40</option>
		<option>50</option>
		<option>60</option>
		<option>70</option>
		<option>80</option>
		<option>90</option>
		<option>100</option>
	</select>';
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
echo '<div class="item"><h2>Scènes</h2>';
foreach($scenes as $scene){
	echo '<table width="100%"><thead><tr><th colspan="2">';
	if($detailscenes=='optional') {print '<a href="#" onclick="toggle_visibility(\'scene'.$scene['id'].'\');" style="text-decoration:none">'.$scene['name'].'</a>';} else {print $scene['name'];}
	print '<img id="'.$row['type'].'Icon" src="images/empty.gif" /></th>
	<th width="50px"><form method="post" action="#"><input type="hidden" name="scene" value="'.$scene['id'].'"/><input type="hidden" name="schakelscene" value="on"/><input type="submit" value="AAN" class="abutton"/></form></th>
	<th width="50px"><form method="post" action="#"><input type="hidden" name="scene" value="'.$scene['id'].'"/><input type="hidden" name="schakelscene" value="off"/><input type="submit" value="UIT" class="abutton"/></form></th>
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
echo '<div class="item"><h2>Somfy</h2><table align="center"><tbody>';
while($row = $result->fetch_assoc()){
	$tdstyle = '';
	if($group != $row['volgorde']) $tdstyle = 'style="border-top:1px solid black"';
	$group = $row['volgorde'];
	print '<tr><td><img id="somfyIcon" src="images/empty.gif" /></td><td align="right" '.$tdstyle.'>'.$row['name'].'</td>
	<td width="185px" '.$tdstyle.'><form method="post" action="#">
	<input type="hidden" name="switch" value="'.$row['id_switch'].'"/>
	<input type="hidden" name="schakel" value="'.$row['id_switch'].'"/>
	<input type="submit" id="somfydownIcon" name="somfy" value="down" class="abuttonsomfy handje"/>
	<input type="submit" id="somfystopIcon" name="somfy" value="stop" class="abuttonsomfy handje"/>
	<input type="submit" id="somfyupIcon" name="somfy" value="up" class="abuttonsomfy handje"/>
	
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
echo '<div class="item"><h2>Radiatoren</h2><table align="center"><tbody>';
while($row = $result->fetch_assoc()){
	$tdstyle = '';
	if($group != $row['volgorde']) $tdstyle = 'style="border-top:1px solid black"';
	$group = $row['volgorde'];
	print '<tr><td><img id="radiatorIcon" src="images/empty.gif" /></td><td align="right" '.$tdstyle.'>'.$row['name'].'</td>
	<td width="115px" '.$tdstyle.'><form method="post" action="#">
	<input type="hidden" name="radiator" value="'.$row['id_switch'].'"/>
	<select name="set_temp"  class="abutton handje" onChange="this.form.submit()" style="margin-top:4px">
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
echo '<div class="item handje" onclick="window.location=\'history.php\';"><h2>Sensoren</h2>';
$sql="select id_sensor, name, type, volgorde from sensors order by volgorde asc, favorite desc, name asc";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
if($result->num_rows>0) {
$group = 0;
echo '<table align="center" width="100%">';
while($row = $result->fetch_assoc()){
        echo '<tr>';
        if($authenticated==true) {
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
		} else {
			echo '<td>'.$row['name'].'</td><td>'.$type.'</td><td>status</td><td>time</td>';
		}
		echo '</tr>';
}
echo "</table></div>";
}
//--THERMOMETERS--
if(!empty($thermometers)) {
	echo '<div class="item handje" onclick="window.location=\'temp.php\';"><h2>Temperatuur</h2><table width="100%">';
	foreach($thermometers as $thermometer){
		if($debug=='yes') print_r($thermometer);
		echo '<tr><th></th><th>temp<br/>°C</th><th>hum<br/>%</th><th>min<br/>°C</th><th>te-t<br/>&nbsp;</th><th>max<br/>°C</th><th>te+t<br/>&nbsp;</th></tr>
		<tr><td>'.$thermometer['name'].'</td><td>'.$thermometer['te'].'</td><td>'.$thermometer['hu'].'</td><td>'.$thermometer['te-'].'</td><td>'.$thermometer['te-t'].'</td><td>'.$thermometer['te+'].'</td><td>'.$thermometer['te+t'].'</td></tr>';
	}
	echo "</table></div>";
}
//--RAINMETERS--
if(!empty($rainmeters)) {
	echo '<div class="item handje" onclick="window.location=\'rain.php\';"><h2>Regen</h2><table width="100%">';
	foreach($rainmeters as $rainmeter){
		if($debug=='yes') print_r($rainmeter);
		echo '<tr><th></th><th>mm</th><th>3h</th></tr>
		<tr><td>'.$rainmeter['name'].'</td><td>'.$rainmeter['mm'].' mm</td><td>'.$rainmeter['3h'].' mm</td></tr>';
	}
	echo "</table></div>";
}
//--WINDMETERS--
//if(isset($data['response']['windmeters']['0']['ws'])) {
if(!empty($windmeters)) {
	echo '<div class="item handje" onclick="window.location=\'wind.php\';"><h2>Wind</h2><table width="100%">';
	foreach($windmeters as $windmeter){
		if($debug=='yes') print_r($windmeter);
		if(isset($windmeter['ws'])) print '<tr><th>Naam</th><th>ws</th><th>gu</th><th>dir</th><th>ws+</th></tr><tr><td>'.$windmeter['name'].'</td><td>'.$windmeter['ws'].' km/u</td><td>'.$windmeter['gu'].' km/u</td><td>'.$windmeter['dir'].' °</td><td>'.$windmeter['ws+'].' km/u</td></tr>';
	}
	echo "</table></div>";
}
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
<?php
include "footer.php";?>