<?php include "header.php"; 
$sql = "SELECT timestamp, te, hu FROM temperature GROUP BY left(timestamp,13) ORDER BY timestamp DESC";
if(!isset($_POST['Show_All'])) {$sql .= " LIMIT 0,30";} else {$sql .= " LIMIT 0,1000";}
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<div class="row"><div class="span_2">
	<h2>Laatste 30 uur</h2>
	<table id="table" align="center"><thead><tr><th>Tijd</th><th>Temp</th><th>Rel Voch</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td>'.strftime("%a %e %b",strtotime($row['timestamp'])).'&nbsp;</td>
	<td class style="text-align: right;">&nbsp;'.$row['te'].' °C&nbsp;</td>
	<td class style="text-align: right;">&nbsp;'.$row['hu'].' %&nbsp;</td>
	</tr>';
}
$result->free();
echo "</tbody></table></div><div class='span_2'>
	<h2>Laatste 30 dagen</h2>";
$sql = "SELECT date, min, max FROM temp_day ORDER BY date DESC";
if(!isset($_POST['Show_All'])) {$sql .= " LIMIT 0,30";} else {$sql .= " LIMIT 0,1000";}
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
$db->close();
echo "</tbody></table></div></div></div>";
include "footer.php";
?>