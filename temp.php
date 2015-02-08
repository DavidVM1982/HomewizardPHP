<?php include "header.php"; 
$data = null;
try {
  $json = file_get_contents($jsonurl.'telist'); 
  $data = json_decode($json,true);
} catch (Exception $e) { 
  echo $e->getMessage();
}
if (!$data) {
  echo "No information available...";
} else {
	
echo '<div class="onecolumn">';
$thermometers =  $data['response'];
if(!empty($thermometers)) {
	foreach($thermometers as $thermometer){
		${'thermometerid'.$thermometer['id']} = $thermometer['id'];
		${'thermometernaam'.$thermometer['id']} = $thermometer['name'];
	}
}
if(isset($_POST['limit'])) { $limit = $_POST['limit']; } else { $limit = 20;}
if(isset($_POST['sensor'])) { $sensor = $_POST['sensor']; $sensornaam = ${'thermometernaam'.$_POST['sensor']};} else { $sensor = 1;}
if(isset($_POST['filter'])) { 
	$filter = $_POST['filter'];
	$sql = "SELECT id_sensor, name FROM sensors WHERE name like '$filter' AND type like 'temp'";
	if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	$row = $result->fetch_assoc();
	$sensor = $row['id_sensor'];
	$sensornaam = $row['name'];
	$result->free();
}

echo '</div><div class="threecolumn"><br/>
<form method="post" name="filter" id="filter">';
echo '<select name="limit" class="abutton settings gradient" onChange="this.form.submit()" style="max-width:90px;">';
echo '<option selected>'.$limit.'</option>';
echo '<option>20</option>
<option>50</option>
<option>100</option>
<option>500</option>
<option>1000</option>
<option>5000</option>
<option>10000</option>
<option>50000</option>
<option>100000</option>
</select>';
if(!empty($thermometers) && $authenticated==true) {
	echo '<select name="sensor" class="abutton settings gradient" onChange="this.form.submit()" style="max-width:200px;">';
	if($sensornaam) {
		echo '<option value="'.$sensor.'" selected>'.$sensor.' - '.$sensornaam.'</option>';
	} else {
		echo '<option value="'.${'thermometerid'.$defaultthermometer}.'" selected>'.${'thermometerid'.$defaultthermometer}.' - '.${'thermometernaam'.$defaultthermometer}.'</option>';
	}
	foreach($thermometers as $thermometer){
		echo '<option value="'.$thermometer['id'].'">'.$thermometer['id'].' - '.$thermometer['name'].'</option>';
	}
	echo '
	</select>
	';
}
echo '
</form>
<div class="isotope"><div class="item temprain gradient">
	<h2>Laatste '.$limit.' uur</h2>
	<table id="table" align="center"><thead><tr><th>Tijd</th><th>Temp</th><th>Rel Voch</th></tr></thead><tbody>';



$sql = "SELECT timestamp, te, hu FROM temperature WHERE id_sensor = $sensor ORDER BY timestamp DESC LIMIT 0,$limit";
if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}

while($row = $result->fetch_assoc()){
	echo '<tr>
	<td>'.strftime("%a %e %b %H:%M",strtotime($row['timestamp'])).'&nbsp;</td>
	<td class style="text-align: right;">&nbsp;'.number_format($row['te'],1).' °C&nbsp;</td>
	<td class style="text-align: right;">&nbsp;'.$row['hu'].' %&nbsp;</td>
	</tr>';
}
$result->free();
echo "</tbody></table></div><div class='item temprain gradient'>
	<h2>Laatste ".$limit." dagen</h2>";

$sql = "SELECT date, min, max FROM temp_day WHERE id_sensor = $sensor ORDER BY date DESC LIMIT 0,$limit";
if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
echo '<table id="table_day" align="center"><thead><tr><th>Datum</th><th>Min</th><th>Max</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td class style="text-align: right;">'.strftime("%a %e %b",strtotime($row['date'])).'&nbsp;</td>
	<td class style="text-align: right;">&nbsp;'.number_format($row['min'],1).' °C&nbsp;</td>
	<td class style="text-align: right;">&nbsp;'.number_format($row['max'],1).' °C&nbsp;</td>
	</tr>';
}
$result->free();
echo "</tbody></table></div><div class='item temprain gradient'>
	<h2>Laatste ".$limit." maanden</h2>";

$sql = "SELECT left(date,7) AS date, min(min) as min, max(max) as max FROM temp_day WHERE id_sensor = $sensor GROUP BY left(date,7) ORDER BY date DESC LIMIT 0,$limit";
if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
echo '<table id="table_day" align="center"><thead><tr><th>Datum</th><th>Min</th><th>Max</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td class style="text-align: right;">'.strftime("%B %Y",strtotime($row['date'])).'&nbsp;</td>
	<td class style="text-align: right;">&nbsp;'.number_format($row['min'],1).' °C&nbsp;</td>
	<td class style="text-align: right;">&nbsp;'.number_format($row['max'],1).' °C&nbsp;</td>
	</tr>';
}
}
$result->free();
echo "</tbody></table></div></div></div>";
include "footer.php";
?>