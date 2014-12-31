<?php
include "header.php";

if (isset($_POST['schakel'])) {
	if($authenticated == true) {
		if($debug=='yes') {echo '<br/>$_POST = ';print_r($_POST);echo "<br/>sw/".$_POST['switch']."/".$_POST['schakel']."<hr>";}
		file_get_contents($jsonurl.'sw/'.$_POST['switch'].'/'.$_POST['schakel']);
	} else {
		echo '<p class="error">Switching blocked when not logged in</p>';
	}
}
if (isset($_POST['set_temp'])) {
	if($authenticated == true) {
		if($debug=='yes') {echo '<br/>$_POST = ';print_r($_POST);echo "<br>sw/".$_POST['radiator']."/settarget/".$_POST['set_temp']."<hr>";}
		if(isset($_POST['radiator']) && isset($_POST['set_temp']))file_get_contents($jsonurl.'sw/'.$_POST['radiator'].'/settarget/'.$_POST['set_temp']);
	} else {
		echo 'Switching blocked when not logged in';
	}
}
if (isset($_POST['schakelscene'])) {
	if($authenticated == true) {
		if($debug=='yes') {echo '<br/>$_POST = ';print_r($_POST);echo "<br/>gp/".$_POST['scene']."/".$_POST['schakelscene']."<hr>";}
		file_get_contents($jsonurl.'gp/'.$_POST['scene'].'/'.$_POST['schakelscene']);
	} else {
		echo '<p class="error">Switching blocked when not logged in</p>';
	}
}
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
  
flush();	
if($authenticated == false) print '<div class="row"><div class="span_3"><p class="error">Some information is not available when not logged in</p></div></div>';
//---SCHAKELAARS---
$switches =  $data['response']['switches'];
if(!empty($switches)) {
echo "<div class='row'><div class='span_1'><h2>Switches</h2>";
flush();
echo "<table align='center'><tbody>";
foreach($switches as $switch){
	if($switch['type']=="switch"){
        $switchon = "";
		if($switch['status']=="on") {$switchon = "off";} else {$switchon = "on";}
		print '
		<tr><td align="right">'.$switch['name'].'</td>
		<td width="70px"><form method="post" action="#">
		<input type="hidden" name="switch" value="'.$switch['id'].'"/>
		<input type="hidden" name="schakel" value="'.$switchon.'"/>
		<label><input type="checkbox" class="ios-switch" '; if($switch['status']=="on") {print 'checked';} print ' onChange="this.form.submit()"/><div><div></div></div></label>
		</form></td></tr>';
	}
}
echo "</tbody></table></div>";
}

//---RADIATORS---
if(!empty($switches)) {
echo "<div class='span_1'><h2>Radiators</h2>";
flush();
echo "<table align='center'><tbody>";
foreach($switches as $switch){
    if($switch['type']=="radiator"){
		print '<tr><td align="right">'.$switch['name'].'</td>
		<td width="115px"><form method="post" action="#">
		<input type="hidden" name="radiator" value="'.$switch['id'].'"/>'; ?>
		<select name='set_temp'  class='abutton'>
			<option <?php if($switch['tte']=='10') print 'selected'; ?>>10</option>
			<option <?php if($switch['tte']=='16') print 'selected'; ?>>16</option>
			<option <?php if($switch['tte']=='18') print 'selected'; ?>>18</option>
			<option <?php if($switch['tte']=='19') print 'selected'; ?>>19</option>
			<option <?php if($switch['tte']=='20') print 'selected'; ?>>20</option>
			<option <?php if($switch['tte']=='21') print 'selected'; ?>>21</option>
            <option <?php if($switch['tte']=='22') print 'selected'; ?>>22</option>
		</select>
		<input type="submit" value="Set" class="abutton"/>
		</form></td></tr>
<?php        
	}
}
echo "</tbody></table></div>";
}
$scenes =  $data['response']['scenes'];
echo "<div class='span_1'><h2>Scenes</h2>";
foreach($scenes as $scene){
	echo '<table width="100%"><thead><tr><th colspan="2">'.$scene['name'].'</th>
	<th width="50px"><form method="post" action="#"><input type="hidden" name="scene" value="'.$scene['id'].'"/><input type="hidden" name="schakelscene" value="on"/><input type="submit" value="ON" class="abutton"/></form></th>
	<th width="50px"><form method="post" action="#"><input type="hidden" name="scene" value="'.$scene['id'].'"/><input type="hidden" name="schakelscene" value="off"/><input type="submit" value="OFF" class="abutton"/></form></th>
	</tr></thead><tbody>';
	$datascene = null;
	$datascenes = null;
	try {
		$jsonscene = file_get_contents($jsonurl.'gp/get/'.$scene['id']);
		$datascenes = json_decode($jsonscene,true);
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	if (!$datascenes) {
		echo "No information available...";
	} else {
		foreach($datascenes['response'] as $datascene) {
		print '<tr><td>'.$datascene['type'].'</td><td>'.$datascene['name'].'</td><td>'.$datascene['onstatus'].'</td><td>'.$datascene['offstatus'].'</td></tr>';
		}
	}
	echo '</tbody></table>';
}
echo "</div></div><div class='row'>";


//---SENSORS--
$sensors =  $data['response']['kakusensors'];
if(!empty($sensors)) {
echo "<div class='span_1'><h2>Sensors</h2>";
flush();

echo '<table align="center" width="100%">';
foreach($sensors as $sensor){
        print '<tr>';
        if($sensor['status'] == "yes") {print '<td style="color:#F00; font-weight:bold">'.$sensor['name'].'</td>';} else {print '<td>'.$sensor['name'].'</td>';}
        if($sensor['status'] == "yes") {print '<td style="color:#F00; font-weight:bold">'.$sensor['type'].'</td>';} else {print '<td>'.$sensor['type'].'</td>';}
        if($sensor['status'] == "yes") {print '<td style="color:#F00; font-weight:bold">';} else {print '<td>';}
		if($sensor['type']=="contact" && $sensor['status'] == "no") { print 'Closed'; }
		else if ($sensor['type']=="contact" && $sensor['status'] == "yes") { print 'Open'; }
		else if ($sensor['type']=="motion" && $sensor['status'] == "yes") { print 'Movement'; }
		else if ($sensor['type']=="motion" && $sensor['status'] == "no") { print ''; }
		else if ($sensor['type']=="doorbell" && $sensor['status'] == "no") { print ''; }
		else if ($sensor['type']=="doorbell" && $sensor['status'] == "yes") { print 'Ring'; }
		else if ($sensor['type']=="smoke" && $sensor['status'] == "no") { print ''; }
		else if ($sensor['type']=="smoke" && $sensor['status'] == "yes") { print 'SMOKE!'; }
		else print $sensor['status'];
		print '</td>';
        if($authenticated==true) {
			if($sensor['status'] == "yes") {print '<td style="color:#F00; font-weight:bold">'.$sensor['timestamp'].'</td>';} else {print '<td>'.$sensor['timestamp'].'</td>';}
		} else {
			print '<td></td>';
		}
		print '</tr>';
}
echo "</table></div>";
}
//--THERMOMETERS--
$thermometers =  $data['response']['thermometers'];
if(!empty($thermometers)) {
echo "<div class='span_1'><h2>Temperature</h2>";
flush();
echo "<table width='100%'>";

foreach($thermometers as $thermometer){
	print '<tr><th>Naam</th><th>temp<br/>°C</th><th>hum<br/>%</th><th>min<br/>°C</th><th>te-t</th><th>max<br/>°C</th><th>te+t</th></tr>';
	print '<tr>';
	print '<td>'.$thermometer['name'].'</td>';
	print '<td>'.$thermometer['te'].'</td>';
	print '<td>'.$thermometer['hu'].'</td>';
	print '<td>'.$thermometer['te-'].'</td>';
	print '<td>'.$thermometer['te-t'].'</td>';
	print '<td>'.$thermometer['te+'].'</td>';
	print '<td>'.$thermometer['te+t'].'</td></tr>';
}
echo "</table></div>";
}
//--RAINMETERS--
$rainmeters =  $data['response']['rainmeters'];
if(!empty($rainmeters)) {
echo "<div class='span_1'><h2>Rain</h2>";
flush();
echo "<table width='100%'>";

foreach($rainmeters as $rainmeter){
	print '<tr><th></th><th>mm</th><th>3h</th></tr>';
	print '<tr>';
	print '<td>'.$rainmeter['name'].'</td>';
	print '<td>'.$rainmeter['mm'].' mm</td>';
	print '<td>'.$rainmeter['3h'].' mm</td></tr>';
}
echo "</table></div>";
}
//--WINDMETERS--
if(isset($data['response']['windmeters']['0']['ws'])) {
$windmeters =  $data['response']['windmeters'];
echo "<div class='span_1'><h2>Wind</h2>";
flush();
echo "<table width='100%'>";
foreach($windmeters as $windmeter){
	print '<tr><th>Naam</th><th>ws</th><th>gu</th><th>dir</th><th>ws+</th></tr>';
	print '<tr>';
	print '<td>'.$windmeter['name'].'</td>';
	print '<td>'.$windmeter['ws'].' km/u</td>';
	print '<td>'.$windmeter['gu'].' km/u</td>';
	print '<td>'.$windmeter['dir'].' °</td>';
	print '<td>'.$windmeter['ws+'].' km/u</td></tr>';
}
echo "</table></div></div>";
}
}
include "footer.php";?>