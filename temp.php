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
if($authenticated==true) {
if(!empty($thermometers)) {
	echo '<div class="gradient"><table width="100%">';
	foreach($thermometers as $thermometer){
		if($authenticated == true && $debug=='yes') print_r($thermometer);
		echo '<tr>';
		if(count($thermometers)>1) {echo '<td>'.$thermometer['name'].'</td>';} else { echo '<td></td>';}
		echo '<td>'.$thermometer['te'].' °C</td><td>'.$thermometer['hu'].' %</td></tr>';
	}
	echo "</table></div>";
}
}

echo '</div><div class="threecolumn"><br/>
<form method="post" name="filter" id="filter">';
if(!empty($thermometers) && $authenticated==true) {
	echo '<select name="sensor" class="abutton settings gradient" onChange="this.form.submit()">';
	if(isset($_POST['sensor'])) {
		print '<option value="'.$_POST['sensor'].'" selected>'.$_POST['sensor'].' - '.${'thermometernaam'.$_POST['sensor']}.'</option>';
	} else {
		print '<option value="'.${'thermometerid'.$defaultthermometer}.'" selected>'.${'thermometerid'.$defaultthermometer}.' - '.${'thermometernaam'.$defaultthermometer}.'</option>';
	}
	foreach($thermometers as $thermometer){
		print '<option value="'.$thermometer['id'].'">'.$thermometer['id'].' - '.$thermometer['name'].'</option>';
	}
	echo '
	</select>
	<br/>';
}
echo '<select name="limit" class="abutton settings gradient" onChange="this.form.submit()">';

if(isset($_POST['limit'])) print '<option selected>'.$_POST['limit'].'</option>';
print '<option>20</option>
<option>50</option>
<option>100</option>
<option>500</option>
<option>1000</option>
<option>5000</option>
<option>10000</option>
<option>50000</option>
<option>100000</option>
</select>
</form>
<div class="isotope"><div class="item temprain gradient">
	<h2>Laatste '.$limit.' uur</h2>
	<table id="table" align="center"><thead><tr><th>Tijd</th><th>Temp</th><th>Rel Voch</th></tr></thead><tbody>';

if(isset($_POST['limit'])) { $limit = $_POST['limit']; } else { $limit = 20;}
if(isset($_POST['sensor'])) { $sensor = $_POST['sensor']; } else { $sensor = 1;}
$sql = "SELECT timestamp, te, hu FROM temperature WHERE id_sensor = $sensor GROUP BY left(timestamp,13) ORDER BY timestamp DESC LIMIT 0,$limit";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}

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
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
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
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
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