<?php 
include "header.php"; 
echo '<div class="twocolumn"><div class="history gradient"><br/><br/>
<form method="post" name="filter" id="filter">
<select name="limit" class="abutton gradient" onChange="this.form.submit()">';
if(isset($_POST['limit'])) print '<option selected>'.$_POST['limit'].'</option>';
echo '<option>20</option>
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
$sql = "SELECT name FROM switches WHERE type not like 'scene'";
if(isset($_POST['filtertype'])) {
	$filtertype = $_POST['filtertype'];
	if($filtertype != "All") $sql .= " AND type like '$filtertype'";
}
$sql .= " ORDER BY name ASC";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
while($row = $result->fetch_assoc()){
	print '<option ';if(isset($_POST['filter'])) { if($_POST['filter']==$row['name']) print 'selected';} print '>'.$row['name'].'</option>';
}
$result->free();
print '</select>
<select name="filtertype" class="abutton abuttonhistory gradient" onChange="this.form.submit()"><option ';if(isset($_POST['filtertype'])) { if($_POST['filtertype']=='all') print 'selected';} print '>All</option>';
$sql = "SELECT type FROM switches WHERE type not like 'scene'";
if(isset($_POST['filter'])) {
	$filter = $_POST['filter'];
	if($filter != "All") $sql .= " AND name like '$filter'";
}
$sql .= " GROUP BY type ORDER BY type ASC";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
while($row = $result->fetch_assoc()){
	print '<option ';if(isset($_POST['filtertype'])) { if($_POST['filtertype']==$row['type']) print 'selected';} print '>'.$row['type'].'</option>';
}
$result->free();
print '</select>
</form>';

$sql = "SELECT h.id_switch, h.timestamp, h.type, h.who, s.name 
FROM switchhistory h 
LEFT JOIN switches s ON h.id_switch=s.id_switch 
WHERE s.type not like 'scene' and h.who not like 'd'";
if(isset($_POST['filter'])) {
	$filter = $_POST['filter'];
	if($filter != "All") $sql .= " AND s.name like '$filter'";
}
if(isset($_POST['filtertype'])) {
	$filtertype = $_POST['filtertype'];
	if($filtertype != "All") $sql .= " AND s.type like '$filtertype'";
}
if($authenticated==true) {
	if(isset($_POST['limit'])) { $limit = $_POST['limit']; } else { $limit = 20;}
	$sql .= " ORDER BY h.timestamp DESC LIMIT 0,$limit";
	} else {
		print "<br/><p class='error'>History shows 20 oldest events when not logged in</p>";
		$sql .= " ORDER BY h.timestamp ASC LIMIT 0,20";
	}

if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<table id="table" align="center"><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td width="120px" align="right">'.strftime("%a %e %b %H:%M",$row['timestamp']).'&nbsp;</td>
	<td>&nbsp;'.$row['name'].'&nbsp;</td>
	<td>&nbsp;'.$row['type'].'</td>
	<td>&nbsp;'.$row['who'].'</td>
	</tr>';
}
echo "</tbody></table></div></div>";
$result->free();
include "footer.php";
?>