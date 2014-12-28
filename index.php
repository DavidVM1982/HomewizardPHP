<?php
include "header.php";

if (isset($_POST['schakel'])) {
	if($authenticated == true) {
		echo '<br/>$_POST = ';
		print_r($_POST);
		echo "<br/>sw/".$_POST['switch']."/".$_POST['schakel']."<hr>";
		file_get_contents($jsonurl.'sw/'.$_POST['switch'].'/'.$_POST['schakel']);
	} else {
		echo '<p class="error">Switching blocked for non authenticated users</p>';
	}
}
if (isset($_POST['set_temp'])) {
	if($authenticated == true) {
		echo '<br/>$_POST = ';
		print_r($_POST);echo "<br>sw/".$_POST['radiator']."/settarget/".$_POST['set_temp']."<hr>";
		if(isset($_POST['radiator']) && isset($_POST['set_temp']))file_get_contents($jsonurl.'sw/'.$_POST['radiator'].'/settarget/'.$_POST['set_temp']);
	} else {
		echo 'Switching blocked for non authenticated users';
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

//---SCHAKELAARS---
$switches =  $data['response']['switches'];
if(!empty($switches)) {
echo "<h3>Switches</h3>";
flush();
echo "<dl>";
foreach($switches as $switch){
	if($switch['type']=="switch"){
        $switchon = "";
		if($switch['status']=="on") {$switchon = "off";} else {$switchon = "on";}
		print '
		<dt>'.$switch['name'].'</dt>
		<dd><form method="post">
		<input type="hidden" name="switch" value="'.$switch['id'].'">
		<input type="hidden" name="schakel" value="'.$switchon.'">
		<label><input type="checkbox" class="ios-switch" '; if($switch['status']=="on") {print 'checked';} print ' onChange="this.form.submit()"><div><div></div></div></label>
		</form></dd>';
	}
}
echo "</dl>";
}

//---RADIATORS---
if(!empty($switches)) {
echo "<h3>Radiators</h3>";
flush();
echo "<dl>";
foreach($switches as $switch){
    if($switch['type']=="radiator"){
		print '<dt>'.$switch['name'].'</dt>
		<dd><form method="post">
		<input type="hidden" name="radiator" value="'.$switch['id'].'" id="'.$switch['id'].'" />'; ?>
		<select name='set_temp'>
			<option <?php if($switch['tte']=='10') print 'selected'; ?>>10
			<option <?php if($switch['tte']=='16') print 'selected'; ?>>16
			<option <?php if($switch['tte']=='18') print 'selected'; ?>>18
			<option <?php if($switch['tte']=='19') print 'selected'; ?>>19
			<option <?php if($switch['tte']=='20') print 'selected'; ?>>20
			<option <?php if($switch['tte']=='21') print 'selected'; ?>>21
            <option <?php if($switch['tte']=='22') print 'selected'; ?>>22
		</select>
		<input type="submit" value="Set" />
		</form></dd>
<?php        
	}
}
echo "</dl>";
}
$scenes =  $data['response']['scenes'];
if(!empty($scenes)) {
echo "<h3>Scenes</h3>";
echo "<dl><dt>Id</dt><dt>Naam</dt><dt>Status</dt>";
foreach($scenes as $scene){
	print '<dd>'.$scene['id'].'</dd>';
	print '<dd>'.$scene['name'].'</dd>';
	print '<dd>'.$scene['status'].'</dd>';
}
echo "</dl>";
}


//---SENSORS--
$sensors =  $data['response']['kakusensors'];
if(!empty($sensors)) {
echo "<h3>Sensors</h3>";
flush();

echo "<table width='100%'></tr>";
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
        if($sensor['status'] == "yes") {print '<td style="color:#F00; font-weight:bold">'.$sensor['timestamp'].'</td>';} else {print '<td>'.$sensor['timestamp'].'</td>';}
		print '</tr>';
}
echo "</table>";
}
//--THERMOMETERS--
$thermometers =  $data['response']['thermometers'];
if(!empty($thermometers)) {
echo "<h3>Temperature</h3>";
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
echo "</table>";
}
//--RAINMETERS--
$rainmeters =  $data['response']['rainmeters'];
if(!empty($rainmeters)) {
echo "<h3>Rain</h3>";
flush();
echo "<table width='100%'>";

foreach($rainmeters as $rainmeter){
	print '<tr><th>Naam</th><th>mm</th><th>3h</th></tr>';
	print '<tr>';
	print '<td>'.$rainmeter['name'].'</td>';
	print '<td>'.$rainmeter['mm'].' mm</td>';
	print '<td>'.$rainmeter['3h'].' mm</td></tr>';
}
echo "</table>";
}
//--WINDMETERS--
if(isset($data['response']['windmeters']['0']['ws'])) {
$windmeters =  $data['response']['windmeters'];
echo "<h3>Wind</h3>";
flush();
echo "<table width='100%'>";
foreach($windmeters as $windmeter){
	print '<tr><th>Naam</th><th>ws</th><th>gu</th><th>dir</th><th>wc</th><th>te</th><th>ws-</th><th>ws+</th></tr>';
	print '<tr>';
	print '<td>'.$windmeter['name'].'</td>';
	print '<td>'.$windmeter['ws'].' km/u</td>';
	print '<td>'.$windmeter['gu'].' km/u</td>';
	print '<td>'.$windmeter['dir'].' °</td>';
	print '<td>'.$windmeter['wc'].' °C</td>';
	print '<td>'.$windmeter['te'].' °C</td>';
	print '<td>'.$windmeter['ws-'].' km/u</td>';
	print '<td>'.$windmeter['ws+'].' km/u</td></tr>';
}
echo "</table>";
}
}
include "footer.php";
?>
<script language="javascript">
var range = $('.input-range'),
    value = $('.range-value');
    
value.html(range.attr('value'));

range.on('input', function(){
    value.html(this.value);
}); 
</script>uage="javascript">
var range = $('.input-range'),
    value = $('.range-value');
    
value.html(range.attr('value'));

range.on('input', function(){
    value.html(this.value);
}); 
</script>