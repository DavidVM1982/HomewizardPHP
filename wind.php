<?php
include "header.php";
if(isset($_POST['limit'])) { $limit = $_POST['limit']; } else { $limit = 20;}
$sql = "SELECT timestamp, wi, gu, dir FROM wind ORDER BY timestamp DESC LIMIT 0,$limit";
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
<div class="isotope"><div class="item temprain"><h2>Wind</h2><table width="100%" align="center"><thead><tr><th valign="bottom">Datum</th><th>Windspeed<br/>km/h</th><th>Gust<br/>km/h</th><th>Direction</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td align="right" width="140px">'.date('d/m H:i', strtotime($row['timestamp'])).'</td>
	<td align="right">'.$row['wi'].'</td>
	<td align="right">'.$row['gu'].'</td>
	<td align="right">'.$row['dir'].'</td>
	</tr>';
}
$result->free();
echo '</tbody></table></div>';
$sql = "SELECT timestamp, max(wi) as maxwi, max(gu) maxgu FROM wind GROUP BY left(timestamp,10) ORDER BY timestamp DESC LIMIT 0,$limit";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<div class="item temprain"><h2>Max wind per dag</h2><table width="100%" align="center"><thead><tr><th valign="bottom">Datum</th><th>Windspeed<br/>km/h</th><th>Gust<br/>km/h</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td align="right">'.strftime("%a %e %b",strtotime($row['timestamp'])).'</td>
	<td align="right">'.round($row['maxwi'],1).'</td>
	<td align="right">'.round($row['maxgu'],1).'</td>
	</tr>';
}
$result->free();
echo '</tbody></table></div>';
$sql = "SELECT timestamp, max(wi) as maxwi, max(gu) maxgu FROM wind GROUP BY left(timestamp,7) ORDER BY timestamp DESC LIMIT 0,$limit";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<div class="item temprain"><h2>Max wind per maand</h2><table width="100%" align="center"><thead><tr><th valign="bottom">Datum</th><th>Windspeed<br/>km/h</th><th>Gust<br/>km/h</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td align="right">'.strftime("%B %Y",strtotime($row['timestamp'])).'</td>
	<td align="right">'.round($row['maxwi'],1).'</td>
	<td align="right">'.round($row['maxgu'],1).'</td>
	</tr>';
}
$result->free();
echo "</tbody></table></div>";
include "footer.php";
?>
