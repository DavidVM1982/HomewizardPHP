</div>
<div class="footer handje" onclick="window.location='settings.php';">
<br/>
<!-- Please do not remove these lines -->
Get the code at GitHub <a href="https://github.com/Egregius/HomewizardPHP" title="HomewizardPHP"><img src="images/GitHub.png" width="16" height="16"/></a> <a href="https://github.com/Egregius/HomewizardPHP" title="HomewizardPHP">HomewizardPHP</a> <br/><br/>
Created by <a href="http://egregius.be">egregius.be</a><br/><br/><br/>
<script type="text/javascript" language="javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" language="javascript" src="js/isotope.pkgd.min.js"></script>
<script language="javascript">
$( function() {
  var $container = $('.isotope'),
      $items = $('.item');
	$('.isotope').isotope({
    itemSelector: '.item',
	layoutMode: 'masonry',
    sortBy : 'number',
	getSortData: {
    number: '.number parseInt',
    }
  });
  $items.click(function(){
    var $this = $(this);
    $container
      .isotope('updateSortData', $this )
      .isotope();
  });
});
</script>
<script language="javascript"> 
function toggle(showHideDiv, switchTextDiv) {
	var ele = document.getElementById(showHideDiv);
	var text = document.getElementById(switchTextDiv);
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		text.innerHTML = "show";
  	}
	else {
		ele.style.display = "block";
		text.innerHTML = "hide";
	}
} 
</script>
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
echo '<small>Versie '.$versie.'. Opgemaakt in '.$total_time.' seconden op '; echo date("j M Y H:i:s"); 
?>
</small>
</div>

</body>
</html>