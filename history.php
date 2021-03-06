<?php 
if(isset($_POST['importall'])) include('history_to_sql.php');
include "header.php"; 
print '<div class="twocolumn"><div class="item wide gradient"><br/><br/>
<form method="post" name="filter" id="filter">
<select name="limit" class="abutton gradient" onChange="this.form.submit()">';
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
<select name="filter" class="abutton abuttonhistory gradient" onChange="this.form.submit()"><option ';if(isset($_POST['filter'])) { if($_POST['filter']=='all') print 'selected';} print '>All</option>';
$sql = "SELECT name FROM sensors WHERE type not like 'temp' ORDER BY name ASC";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
while($row = $result->fetch_assoc()){
	print '<option ';if(isset($_POST['filter'])) { if($_POST['filter']==$row['name']) print 'selected';} print '>'.$row['name'].'</option>';
}
$result->free();
print '</select></form>';

if(isset($_POST['update'])) include_once('history_to_sql.php');
$sql = "SELECT h.id_sensor, h.time, s.name, t.omschrijving FROM history h LEFT JOIN statusses t ON h.status=t.status LEFT JOIN sensors s ON h.id_sensor=s.id_sensor WHERE s.type not like 'temp'";
if(isset($_POST['filter'])) {
	$filter = $_POST['filter'];
	if($filter != "All") $sql .= " AND s.name like '$filter'";
}
if($authenticated==true) {
	if(isset($_POST['limit'])) { $limit = $_POST['limit']; } else { $limit = 20;}
	$sql .= " ORDER BY h.time DESC LIMIT 0,$limit";
	} else {
		print "<br/><p class='error'>History shows 20 oldest events when not logged in</p>";
		$sql .= " ORDER BY h.time ASC LIMIT 0,20";
	}

if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<table id="table" align="center"><thead><tr><th>Tijd</th><th>Sensor</th><th>Status</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td width="120px" align="right">'.strftime("%a %e %b %H:%M",strtotime($row['time'])).'&nbsp;</td>
	<td>&nbsp;'.$row['name'].'&nbsp;</td>
	<td>&nbsp;'.$row['omschrijving'].'</td>
	</tr>';
}
echo '</tbody></table><br/><br/><form method="post"><input type="submit" name="importall" value="Historiek updaten" class="abutton settings gradient"/></form></div></div>';
$result->free();
include "footer.php";
?>