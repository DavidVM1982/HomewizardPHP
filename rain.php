<?php include "header.php";
$sql = "SELECT date, mm FROM rain ORDER BY date DESC";
if(!isset($_POST['Show_All'])) {$sql .= " LIMIT 0,25";} else {$sql .= " LIMIT 0,5000";}
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<h3>Rain per day</h3><table id="table" align="center"><thead><tr><th scope="col">Date</th><th scope="col">Rain</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td align="right">'.date('D d/m', strtotime($row['date'])).'</td>
	<td align="right">'.$row['mm'].' mm</td>
	</tr>';
}
echo "</tbody></table>";

$result->free();
$sql = "SELECT left(date,7) AS date, sum(mm) as mm FROM rain GROUP BY left(date,7) ORDER BY date DESC";
if(!isset($_POST['Show_All'])) {$sql .= " LIMIT 0,25";} else {$sql .= " LIMIT 0,5000";}
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<h3>Rain per month</h3><table id="table_day" align="center"><thead><tr><th>Date</th><th>Rain</th></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td>'.date('M Y', strtotime($row['date'])).'</td>
	<td align="right">'.round($row['mm'],2).' mm</td>
	</tr>';
}
echo "</tbody></table>";

$result->free();
$db->close();

include "footer.php";
?>}
echo "</tbody></table></div>";

$result->free();
$db->close();

include "footer.php";
?>