<?php include "header.php"; 
if(isset($_POST['limit'])) { $limit = $_POST['limit']; } else { $limit = 20;}
$sql = "SELECT timestamp, te, hu FROM temperature GROUP BY left(timestamp,13) ORDER BY timestamp DESC LIMIT 0,$limit";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<div class="threecolumn">
<form method="post" name="filter" id="filter">
<select name="limit" class="abutton settings" onChange="this.form.submit()">';
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
<div class="isotope"><div class="item temprain">
	<h2>Laatste '.$limit.' uur</h2>
	<table id="table" align="center"><thead><tr><th>Tijd</th><th>Temp</th><th>Rel Voch</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td>'.strftime("%a %e %b %H:%M",strtotime($row['timestamp'])).'&nbsp;</td>
	<td class style="text-align: right;">&nbsp;'.$row['te'].' °C&nbsp;</td>
	<td class style="text-align: right;">&nbsp;'.$row['hu'].' %&nbsp;</td>
	</tr>';
}
$result->free();
echo "</tbody></table></div><div class='item temprain'>
	<h2>Laatste ".$limit." dagen</h2>";

$sql = "SELECT date, min, max FROM temp_day ORDER BY date DESC LIMIT 0,$limit";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<table id="table_day" align="center"><thead><tr><th>Datum</th><th>Min</th><th>Max</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td class style="text-align: right;">'.strftime("%a %e %b",strtotime($row['date'])).'&nbsp;</td>
	<td class style="text-align: right;">&nbsp;'.$row['min'].' °C&nbsp;</td>
	<td class style="text-align: right;">&nbsp;'.$row['max'].' °C&nbsp;</td>
	</tr>';
}
$result->free();
echo "</tbody></table></div><div class='item temprain'>
	<h2>Laatste ".$limit." maanden</h2>";

$sql = "SELECT left(date,7) AS date, min(min) as min, max(max) as max FROM temp_day GROUP BY left(date,7) ORDER BY date DESC LIMIT 0,$limit";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<table id="table_day" align="center"><thead><tr><th>Datum</th><th>Min</th><th>Max</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td class style="text-align: right;">'.strftime("%B %Y",strtotime($row['date'])).'&nbsp;</td>
	<td class style="text-align: right;">&nbsp;'.$row['min'].' °C&nbsp;</td>
	<td class style="text-align: right;">&nbsp;'.$row['max'].' °C&nbsp;</td>
	</tr>';
}
$result->free();
echo "</tbody></table></div></div></div>";
include "footer.php";
?>