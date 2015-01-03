<?php include "header.php"; 
print '<div class="history"><div class="span_3"><br/><br/>
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
<select name="filter" class="abutton abuttonhistory" onChange="this.form.submit()"><option ';if(isset($_POST['filter'])) { if($_POST['filter']=='all') print 'selected';} print '>All</option>';
$sql = "SELECT name FROM sensors ORDER BY name ASC";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
while($row = $result->fetch_assoc()){
	print '<option ';if(isset($_POST['filter'])) { if($_POST['filter']==$row['name']) print 'selected';} print '>'.$row['name'].'</option>';
}
$result->free();
print '</select></form>';

if(isset($_POST['update'])) include_once('history_to_sql.php');
$sql = "SELECT h.id_sensor, h.time, s.name, t.omschrijving FROM history h LEFT JOIN statusses t ON h.status=t.status LEFT JOIN sensors s ON h.id_sensor=s.id_sensor";
if(isset($_POST['filter'])) {
	$filter = $_POST['filter'];
	if($filter != "All") $sql .= " WHERE s.name like '$filter'";
}
if($authenticated==true) {
	if(isset($_POST['limit'])) { $limit = $_POST['limit']; } else { $limit = 20;}
	$sql .= " ORDER BY h.time DESC LIMIT 0,$limit";
	} else {
		print "<br/><p class='error'>History shows 20 oldest events when not logged in</p>";
		$sql .= " ORDER BY h.time ASC LIMIT 0,20";
	}

if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<table id="table" align="center"><thead><tr><th>Time</th><th>Sensor</th><th>Status</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td width="120px" align="right">'.date('D d/m H:i', strtotime($row['time'])).'&nbsp;</td>
	<td>'.$row['name'].'</td>
	<td>'.$row['omschrijving'].'</td>
	</tr>';
}
echo "</tbody></table></div></div>";
$result->free();
$db->close();
include "footer.php";
?>