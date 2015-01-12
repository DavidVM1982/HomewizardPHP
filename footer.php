</div>
<div class="footer handje" onclick="window.location='settings.php';">
<br/>
<!-- Please do not remove these lines -->
Get the code at GitHub <a href="https://github.com/Egregius/HomewizardPHP" title="HomewizardPHP"><img src="images/GitHub.png" width="16" height="16"/></a> <a href="https://github.com/Egregius/HomewizardPHP" title="HomewizardPHP">HomewizardPHP</a> <br/><br/>
Created by <a href="http://egregius.be">egregius.be</a><br/><br/><br/>

<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 3);
$sql="select versie from versie order by id desc limit 0,1";
if(!$result = $db->query($sql)){ die('There was an error running the query ['.$sql.'][' . $db->error . ']');}
while($row = $result->fetch_assoc()){$versie = $row['versie'];}
$result->free();
$db->close();
echo 'Versie '.$versie.'. Opgemaakt in '.$total_time.' seconden op '; echo date("j M Y H:i:s");
?>
</div>
</body>
</html>