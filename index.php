<?php
include "header.php";

if (isset($_POST['schakel'])) {
	if($authenticated == true) {
		if (isset($_POST['dimlevel'])) {
			$responsejson = file_get_contents($jsonurl.'sw/dim/'.$_POST['switch'].'/'.$_POST['dimlevel']);
			$response = json_decode($responsejson, true);
			if($response['status']=='ok') {echo '<div class="row">OK</div>'; } else {echo 'response = ';print_r($response);echo '<hr>';}
			if($debug=='yes') {echo '<br/>$_POST = ';print_r($_POST);echo "<br/>sw/dim/".$_POST['switch']."/".$_POST['dimlevel']."<hr>";}
		} else if (isset($_POST['somfy'])){
			$responsejson = file_get_contents($jsonurl.'sf/'.$_POST['switch'].'/'.$_POST['somfy']);
			$response = json_decode($responsejson, true);
			if($response['status']=='ok') {echo '<div class="row">OK</div>'; } else {print_r($response);}
			if($debug=='yes') {echo '<br/>$_POST = ';print_r($_POST);echo "<br/>sf/".$_POST['switch']."/".$_POST['somfy']."<hr>";}
		} else if (isset($_POST['schakel'])){
			$responsejson = file_get_contents($jsonurl.'sw/'.$_POST['switch'].'/'.$_POST['schakel']);
			$response = json_decode($responsejson, true);
			if($response['status']=='ok') {echo '<div class="row">OK</div>'; } else {print_r($response);}
			if($debug=='yes') {echo '<br/>$_POST = ';print_r($_POST);echo "<br/>sw/".$_POST['switch']."/".$_POST['schakel']."<hr>";}
		} 
	} else {
		echo '<p class="error">Switching blocked when not logged in</p>';
	}
}
if (isset($_POST['set_temp'])) {
	if($authenticated == true) {
		if(isset($_POST['radiator']) && isset($_POST['set_temp'])) {
			$responsejson = file_get_contents($jsonurl.'sw/'.$_POST['radiator'].'/settarget/'.$_POST['set_temp']);
			$response = json_decode($responsejson, true);
			if($response['status']=='ok') {echo 'OK'; } else {echo 'response = ';print_r($response);echo '<hr>';}
		}
		if($debug=='yes') {echo '<br/>$_POST = ';print_r($_POST);echo "<br>sw/".$_POST['radiator']."/settarget/".$_POST['set_temp']."<hr>";echo 'response = ';print_r($response);echo '<hr>';}
	} else {
		echo 'Switching blocked when not logged in';
	}
}
if (isset($_POST['schakelscene'])) {
	if($authenticated == true) {
		$responsejson = file_get_contents($jsonurl.'gp/'.$_POST['scene'].'/'.$_POST['schakelscene']);
		$response = json_decode($responsejson, true);
		if($response['status']=='ok') {echo 'OK'; } else {echo 'response = ';print_r($response);echo '<hr>';}
		if($debug=='yes') {echo '<br/>$_POST = ';print_r($_POST);echo "<br/>gp/".$_POST['scene']."/".$_POST['schakelscene']."<hr>";echo 'response = ';print_r($response);echo '<hr>';}
		
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
if($authenticated == false) { 
print '<section class="span_3"><p class="error">Some information is not available when not logged in</p></section>';
} else {
	if($debug=='yes') print_r($data);
}

//---SCHAKELAARS---
$switches =  $data['response']['switches'];
if(!empty($switches)) {
$sql="select id_switch, name, type, volgorde from switches where type not in ('radiator', 'somfy') order by volgorde asc, favorite desc, name asc";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
$group = 0;
?>
<section class='span_1'><section onclick="window.location='index.php'"><h2>Schakelaars</h2></section>
<?php
flush();
echo '<table align="center"><tbody>';
while($row = $result->fetch_assoc()){
	$switchon = "";
	$tdstyle = '';
	if($group != $row['volgorde']) $tdstyle = 'style="border-top:1px solid black"';
	$group = $row['volgorde'];
	if($row['type']=='asun') {if($data['response']['switches'][$row['id_switch']]['mode']=="1") {$switchon = "off";} else {$switchon = "on";}}
	else {if($data['response']['switches'][$row['id_switch']]['status']=="on") {$switchon = "off";} else {$switchon = "on";}}
	print '
	<tr ><form method="post" action="#"><td><img id="'.$row['type'].'Icon" src="images/empty.gif" /></td><td align="right" '.$tdstyle.'>'.$row['name'].'</td>
	<td width="70px" '.$tdstyle.' ><input type="hidden" name="switch" value="'.$row['id_switch'].'"/><input type="hidden" name="schakel" value="'.$switchon.'"/>';
	if($row['type']=='dimmer') {
		print '<select name="dimlevel"  class="abutton" onChange="this.form.submit()" style="margin-top:4px; width:80px; ">
		<option '.$data['response']['switches'][$row['id_switch']]['dimlevel'].') selected>'.$data['response']['switches'][$row['id_switch']]['dimlevel'].'</option>
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
			<input type="checkbox" value="switch'.$row['id_switch'].'" id="switch'.$row['id_switch'].'" name="switch'.$row['id_switch'].'" '; if($data['response']['switches'][$row['id_switch']]['mode']==1) {print 'checked';} print ' onChange="this.form.submit()"/>
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
echo "</tbody></table></section>";
}
/* SCENES */
$scenes =  $data['response']['scenes'];
if(!empty($scenes)) {
?>
<section class='span_1'><section onclick='window.location="index.php"'><h2>Scènes</h2></section>
<?php
foreach($scenes as $scene){
	echo '<table width="100%"><thead><tr><th colspan="2">';
	if($detailscenes=='optional') {print '<a href="#" onclick="toggle_visibility(\'scene'.$scene['id'].'\');" style="text-decoration:none">'.$scene['name'].'</a>';} else {print $scene['name'];}
	print '<img id="'.$row['type'].'Icon" src="images/empty.gif" /></th>
	<th width="50px"><form method="post" action="#"><input type="hidden" name="scene" value="'.$scene['id'].'"/><input type="hidden" name="schakelscene" value="on"/><input type="submit" value="AAN" class="abutton"/></form></th>
	<th width="50px"><form method="post" action="#"><input type="hidden" name="scene" value="'.$scene['id'].'"/><input type="hidden" name="schakelscene" value="off"/><input type="submit" value="UIT" class="abutton"/></form></th>
	</tr></thead>';
	if(($detailscenes=='yes') || ($detailscenes=='optional')) {
		if($detailscenes=='optional') {print '<tbody id="scene'.$scene['id'].'" style="display:none">';} else {print '<tbody>';}
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
echo "</section>";
}
/* SOMFY */
$sql="select id_switch, volgorde from switches where type like 'somfy' order by volgorde asc, favorite desc, name asc";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
if($result->num_rows>0) {
	$group = 0;
?>
<section class='span_1'><section onclick="window.location='index.php'"><h2>Somfy</h2></section>
<?php
flush();
echo '<table align="center"><tbody>';
while($row = $result->fetch_assoc()){
	$tdstyle = '';
	if($group != $row['volgorde']) $tdstyle = 'style="border-top:1px solid black"';
	$group = $row['volgorde'];
	print '<tr><td><img id="somfyIcon" src="images/empty.gif" /></td><td align="right" '.$tdstyle.'>'.$data['response']['switches'][$row['id_switch']]['name'].'</td>
	<td width="185px" '.$tdstyle.'><form method="post" action="#">
	<input type="hidden" name="switch" value="'.$row['id_switch'].'"/>
	<input type="hidden" name="schakel" value="'.$row['id_switch'].'"/>
	<input type="submit" id="somfydownIcon" name="somfy" value="down" class="abuttonsomfy"/>
	<input type="submit" id="somfystopIcon" name="somfy" value="stop" class="abuttonsomfy"/>
	<input type="submit" id="somfyupIcon" name="somfy" value="up" class="abuttonsomfy"/>
	
	</form></td></tr>';
}
$result->free();
echo "</tbody></table></section>";
}

//---RADIATORS---
$sql="select id_switch, volgorde from switches where type like 'radiator' order by volgorde asc, favorite desc, name asc";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
if($result->num_rows>0) {
	$group = 0;
?>
<section class='span_1'><section onclick="window.location='index.php'"><h2>Radiatoren</h2></section>
<?php
flush();
echo '<table align="center"><tbody>';
while($row = $result->fetch_assoc()){
	$tdstyle = '';
	if($group != $row['volgorde']) $tdstyle = 'style="border-top:1px solid black"';
	$group = $row['volgorde'];
	print '<tr><td><img id="radiatorIcon" src="images/empty.gif" /></td><td align="right" '.$tdstyle.'>'.$data['response']['switches'][$row['id_switch']]['name'].'</td>
	<td width="115px" '.$tdstyle.'><form method="post" action="#">
	<input type="hidden" name="radiator" value="'.$row['id_switch'].'"/>
	<select name="set_temp"  class="abutton" onChange="this.form.submit()" style="margin-top:4px">
		<option '.$data['response']['switches'][$row['id_switch']]['tte'].') selected>'.$data['response']['switches'][$row['id_switch']]['tte'].'</option>
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
echo "</tbody></table></section>";
}

//---SENSORS--
$sensors =  $data['response']['kakusensors'];
if(!empty($sensors)) {
?>
<section class='span_1' onclick="window.location='history.php';"><h2>Sensoren</h2>
<?php
flush();
$sql="select id_sensor, type, volgorde from sensors order by volgorde asc, favorite desc, name asc";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
$group = 0;
echo '<table align="center" width="100%">';
while($row = $result->fetch_assoc()){
        print '<tr>';
        if($authenticated==true) {
			$name = $data['response']['kakusensors'][$row['id_sensor']]['name'];
			$status = $data['response']['kakusensors'][$row['id_sensor']]['status'];
			$type = $data['response']['kakusensors'][$row['id_sensor']]['type'];
			$time = $data['response']['kakusensors'][$row['id_sensor']]['timestamp'];
			echo '<td style="color:#F00; font-weight:bold"><img id="'.$type.'Icon" src="images/empty.gif" /></td>';
        	if($type=="contact") $type = "Magneet";
			if($type=="motion") $type = "Beweging";
			if($type=="doorbell") $type = "Deurbel";
			if($type=="smoke") $type = "Rook";
			if($status == "yes") {print '<td style="color:#F00; font-weight:bold">'.$name.'</td>';} else {print '<td>'.$name.'</td>';}
        	if($status == "yes") {print '<td style="color:#F00; font-weight:bold">';} else {print '<td>';}
			if($type=="Magneet" && $status == "no") { print 'Gesloten'; }
			else if ($type=="Magneet" && $status == "yes") { print 'Open'; }
			else if ($type=="Beweging" && $status == "yes") { print 'Beweging'; }
			else if ($type=="Beweging" && $status == "no") { print ''; }
			else if ($type=="Deurbel" && $status == "no") { print ''; }
			else if ($type=="Deurbel" && $status == "yes") { print 'Ring'; }
			else if ($type=="Rook" && $status == "no") { print ''; }
			else if ($type=="Rook" && $status == "yes") { print 'ROOK!!!'; }
			else print $status;
			print '</td>';
        
			if($status == "yes") {print '<td style="color:#F00; font-weight:bold">'.$time.'</td>';} else {print '<td>'.$time.'</td>';}
		} else {
			print '<td>'.$name.'</td><td>'.$type.'</td><td>status</td><td>time</td>';
		}
		print '</tr>';
}
echo "</table></section>";
}
//--THERMOMETERS--
$thermometers =  $data['response']['thermometers'];
if(!empty($thermometers)) {
?>
<section class="span_1" onclick="window.location='temp.php';"><h2>Temperatuur</h2>
<?php
flush();
echo "<table width='100%'>";

foreach($thermometers as $thermometer){
	print '<tr><th></th><th>temp<br/>°C</th><th>hum<br/>%</th><th>min<br/>°C</th><th>te-t<br/>&nbsp;</th><th>max<br/>°C</th><th>te+t<br/>&nbsp;</th></tr>';
	print '<tr>';
	print '<td>'.$thermometer['name'].'</td>';
	print '<td>'.$thermometer['te'].'</td>';
	print '<td>'.$thermometer['hu'].'</td>';
	print '<td>'.$thermometer['te-'].'</td>';
	print '<td>'.$thermometer['te-t'].'</td>';
	print '<td>'.$thermometer['te+'].'</td>';
	print '<td>'.$thermometer['te+t'].'</td></tr>';
}
echo "</table></section>";
}
//--RAINMETERS--
$rainmeters =  $data['response']['rainmeters'];
if(!empty($rainmeters)) {
?>
<section class='span_1' onclick="window.location='rain.php';"><h2>Regen</h2>
<?php 
flush();
echo "<table width='100%'>";

foreach($rainmeters as $rainmeter){
	print '<tr><th></th><th>mm</th><th>3h</th></tr>';
	print '<tr>';
	print '<td>'.$rainmeter['name'].'</td>';
	print '<td>'.$rainmeter['mm'].' mm</td>';
	print '<td>'.$rainmeter['3h'].' mm</td></tr>';
}
echo "</table></section>";
}
//--WINDMETERS--
if(isset($data['response']['windmeters']['0']['ws'])) {
$windmeters =  $data['response']['windmeters'];
?>
<section class='span_1' onclick="window.location='wind.php';"><h2>Wind</h2>
<?php
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
echo "</table></section>";
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
<tbody style="visibility:collapse">
<?php
include "footer.php";?>