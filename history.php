<?php include "header.php"; ?>
<div class="history">
<form method="post" name="filter" id="filter">
<select name="filter">
<option <?php if(isset($_POST['filter'])) { if($_POST['filter']=='All') print 'selected';} ?>>All
<option <?php if(isset($_POST['filter'])) { if($_POST['filter']=='Contact') print 'selected';} ?>>Contact
<option <?php if(isset($_POST['filter'])) { if($_POST['filter']=='Motion') print 'selected';} ?>>Motion
<option <?php if(isset($_POST['filter'])) { if($_POST['filter']=='Doorbell') print 'selected';} ?>>Doorbell
<option <?php if(isset($_POST['filter'])) { if($_POST['filter']=='Smoke') print 'selected';} ?>>Smoke
</select>
<input type="submit" class="abutton" value="filter" />
</form>
<?php
if(isset($_POST['update'])) include_once('history_to_sql.php');
$sql = "SELECT h.id_sensor, h.time, s.name, t.omschrijving FROM history h LEFT JOIN statusses t ON h.status=t.status LEFT JOIN sensors s ON h.id_sensor=s.id_sensor";
if(isset($_POST['filter'])) {
	$filter = $_POST['filter'];
	if($filter != "All") $sql .= " WHERE s.type like '$filter'";
}
if($authenticated==true) {
	$sql .= " ORDER BY h.time DESC LIMIT 0,100";
	} else {
		print "<br/>History shows 20 oldest events<br/>to non authenticated users<br/>The owner of the installation<br/>views 100 most recent";
		$sql .= " ORDER BY h.time ASC LIMIT 0,20";
	}

if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<table id="table" align="center"><thead><tr><th>Time</th><th>Sensor</th><th>Status</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td width="120px">'.date('d/m H:i', strtotime($row['time'])).'</td>
	<td>'.$row['name'].'</td>
	<td>'.$row['omschrijving'].'</td>
	</tr>';
}
echo "</tbody></table></div>";
$result->free();
$db->close();
include "footer.php";
?>