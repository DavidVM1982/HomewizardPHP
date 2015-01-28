<?php include "header.php";
if(isset($_POST['limit'])) { $limit = $_POST['limit']; } else { $limit = 20;}
$sql = "SELECT date, mm FROM rain ORDER BY date DESC LIMIT 0,$limit";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<div class="threecolumn">
<form method="post" name="filter" id="filter">
<select name="limit" class="abutton settings gradient" onChange="this.form.submit()">';
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
<div class="isotope"><div class="item temprain gradient"><h2>Regen per dag</h2><table id="table" align="center"><thead><tr><th scope="col"></th><th scope="col">mm</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td align="right" style="padding-right:10px">'.strftime("%a %e %b",strtotime($row['date'])).'</td>
	<td align="right" style="padding-right:10px">'.$row['mm'].' mm</td>
	</tr>';
}
echo "</tbody></table></div>";

$result->free();
$sql = "SELECT left(date,7) AS date, sum(mm) as mm, count(mm) as days FROM rain where mm > 0 GROUP BY left(date,7) ORDER BY date DESC LIMIT 0,$limit";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<div class="item temprain gradient"><h2>Regen per maand</h2><table id="table_day" align="center"><thead><tr><th></th><th>mm</th><th>dagen</th></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td align="right" style="padding-right:10px">'.strftime("%B %Y",strtotime($row['date'])).'</td>
	<td align="right" style="padding-right:10px">'.round($row['mm'],2).' mm</td>
	<td align="right" style="padding-right:10px">'.$row['days'].'</td>
	</tr>';
}
echo "</tbody></table></div>";

$result->free();
$sql = "SELECT left(date,7) AS date, sum(mm) as mm, count(mm) as days FROM rain where mm > 0 GROUP BY left(date,4) ORDER BY date DESC LIMIT 0,$limit";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<div class="item temprain gradient"><h2>Regen per jaar</h2><table id="table_day" align="center"><thead><tr><th></th><th>mm</th><th>dagen</th></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td align="right" style="padding-right:10px">'.strftime("%Y",strtotime($row['date'])).'</td>
	<td align="right" style="padding-right:10px">'.round($row['mm'],2).' mm</td>
	<td align="right" style="padding-right:10px">'.$row['days'].'</td>
	</tr>';
}
echo "</tbody></table></div></div></div>";

$result->free();
include "footer.php";
?>