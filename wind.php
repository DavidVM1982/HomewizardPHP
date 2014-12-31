<?php
include "header.php";
$sql = "SELECT timestamp, wi, gu, dir FROM wind ORDER BY timestamp DESC";
if(!isset($_POST['Show_All'])) {$sql .= " LIMIT 0,25";} else {$sql .= " LIMIT 0,5000";}
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<div class="history"><div class="span_3"><h2>Wind</h2><table width="100%" align="center"><thead><tr><th valign="bottom">Datum</th><th>Windspeed<br/>km/h</th><th>Gust<br/>km/h</th><th>Direction</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td align="right" width="140px">'.date('d/m H:i', strtotime($row['timestamp'])).'</td>
	<td align="right">'.$row['wi'].'</td>
	<td align="right">'.$row['gu'].'</td>
	<td align="right">'.$row['dir'].'</td>
	</tr>';
}
$result->free();
echo "</tbody></table></div></div>";
if(1+2==4) {
$sql = "SELECT date FROM wind_day ORDER BY date DESC";
if(!isset($_POST['Show_All'])) {$sql .= " LIMIT 0,25";} else {$sql .= " LIMIT 0,5000";}
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<table align="center"><thead><tr><th>Datum</th><th>Windspeed</th><th>Gust</th><th>Direction</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td align="right">'.date('M Y', strtotime($row['timestamp'])).'</td>
	<td align="right">'.$row['wi'].' km/u</td>
	<td align="right">'.$row['gu'].' km/u</td>
	<td align="right">'.$row['dir'].' </td>
	</tr>';
}
$result->free();
echo "</tbody></table>";
}
$db->close();
include "footer.php";
?>
