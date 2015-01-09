<?php include "header.php";
$sql = "SELECT date, mm FROM rain ORDER BY date DESC";
if(!isset($_POST['Show_All'])) {$sql .= " LIMIT 0,25";} else {$sql .= " LIMIT 0,5000";}
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<div class="row"><div class="span_2"><h2>Regen per dag</h2><table id="table" align="center"><thead><tr><th scope="col"></th><th scope="col">mm</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td align="right" style="padding-right:10px">'.strftime("%a %e %b",strtotime($row['date'])).'</td>
	<td align="right" style="padding-right:10px">'.$row['mm'].' mm</td>
	</tr>';
}
echo "</tbody></table></div>";

$result->free();
$sql = "SELECT left(date,7) AS date, sum(mm) as mm, count(mm) as days FROM rain where mm > 0 GROUP BY left(date,7) ORDER BY date DESC";
if(!isset($_POST['Show_All'])) {$sql .= " LIMIT 0,25";} else {$sql .= " LIMIT 0,5000";}
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<div class="span_2"><h2>Regen per maand</h2><table id="table_day" align="center"><thead><tr><th></th><th>mm</th><th>dagen</th></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td align="right" style="padding-right:10px">'.strftime("%B %Y",strtotime($row['date'])).'</td>
	<td align="right" style="padding-right:10px">'.round($row['mm'],2).' mm</td>
	<td align="right" style="padding-right:10px">'.$row['days'].'</td>
	</tr>';
}
echo "</tbody></table></div></div>";

$result->free();
include "footer.php";
?>