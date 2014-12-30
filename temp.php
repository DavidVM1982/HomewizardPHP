<?php include "header.php"; 
$sql = "SELECT timestamp, te, hu FROM temperature GROUP BY left(timestamp,13) ORDER BY timestamp DESC";
if(!isset($_POST['Show_All'])) {$sql .= " LIMIT 0,25";} else {$sql .= " LIMIT 0,1000";}
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<div style="text-align:center"><table id="table" align="center"><thead><tr><th>Time</th><th>Temp</th><th>Hum</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td>'.date('d/m H:i', strtotime($row['timestamp'])).'</td>
	<td class style="text-align: right;">'.$row['te'].' °C&nbsp;</td>
	<td class style="text-align: right;">'.$row['hu'].' %&nbsp;</td>
	</tr>';
}
$result->free();
echo "</tbody></table>";
$sql = "SELECT date, min, max FROM temp_day ORDER BY date DESC";
if(!isset($_POST['Show_All'])) {$sql .= " LIMIT 0,25";} else {$sql .= " LIMIT 0,1000";}
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<table id="table_day" align="center"><thead><tr><th>Datum</th><th>Min</th><th>Max</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td class style="text-align: right;" >'.date('D d/m', strtotime($row['date'])).'</td>
	<td class style="text-align: right;" >'.$row['min'].' °C&nbsp;</td>
	<td class style="text-align: right;" >'.$row['max'].' °C&nbsp;</td>
	</tr>';
}
$result->free();
$db->close();
echo "</tbody></table></div>";
include "footer.php";
?>r>';
}
$result->free();
$db->close();
echo "</tbody></table></div></div></div>";
include "footer.php";
?>