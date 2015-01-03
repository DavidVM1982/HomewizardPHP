<div class="footer">
<div class="span_1" onclick="window.location='settings.php';">
<br/>
<!-- Please do not remove these lines -->
Get the code at GitHub <a href="https://github.com/Egregius/HomewizardPHP" title="HomewizardPHP"><img src="images/GitHub.png" width="16" height="16"/></a> <a href="https://github.com/Egregius/HomewizardPHP" title="HomewizardPHP">HomewizardPHP</a> <br/><br/> 
&#169; Guy Verschuere <a href="http://egregius.be">egregius.be</a><br/><br/>

<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 3);
echo 'Generated in '.$total_time.' seconds<br/><br/>'; echo date("j M Y H:i:s");
?>
</div></div>
</body>
</html>