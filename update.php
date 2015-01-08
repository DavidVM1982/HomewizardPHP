<?php
$laatsteversie = 20150108;
include "header.php";
print '<div class="row"><div class="span_3">';
if($authenticated==true) {
	
//BEGIN UPDATE	
$sql="select versie from versie order by id desc limit 0,1";
if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'][' . $db->error . ']');}
while($row = $result->fetch_assoc()){$versie = $row['versie'];}
$result->free();
echo '<br/>Huidige versie database: '.$versie.'<br/><br>
Geïnstalleerde versie HomewizardPHP: '.$laatsteversie.'<br/><br/>';
//EINDE UPDATE
}
print '</div></div>';
include "footer.php";
?>