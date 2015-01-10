<?php
include "header.php";
if(isset($_POST['limit'])) { $limit = $_POST['limit']; } else { $limit = 20;}
$sql = "SELECT timestamp, wi, gu, dir FROM wind ORDER BY timestamp DESC LIMIT 0,$limit";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<div class="onecolumn">
<form method="post" name="filter" id="filter">
<select name="limit" class="abutton" onChange="this.form.submit()">';
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
if(1+2==4) {
$sql = "SELECT date FROM wind_day ORDER BY date DESC LIMIT 0,$limit";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<div class="item temprain"><table align="center"><thead><tr><th>Datum</th><th>Windspeed</th><th>Gust</th><th>Direction</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td align="right">'.date('M Y', strtotime($row['timestamp'])).'</td>
	<td align="right">'.$row['wi'].' km/u</td>
	<td align="right">'.$row['gu'].' km/u</td>
	<td align="right">'.$row['dir'].' </td>
	</tr>';
}
$result->free();
echo "</tbody></table></div>";
}
echo '</div>';
include "footer.php";
?>
