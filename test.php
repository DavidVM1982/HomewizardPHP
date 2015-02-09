<?php
// Copy alles hieronder over naar actionscron.php na testen. 

include "data.php";
include "functions.php";
echo '<div class="item wide gradient"><p class="number">2</p><br/>';
echo $thermometerte5;
echo '</div>';

if(!isset($_POST['actionscron']) && !isset($_POST['showtest'])) {ob_clean(); $db->close();}
?>
