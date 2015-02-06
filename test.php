<?php
// Copy alles hieronder over naar actionscron.php na testen. 

include "data.php";
include "functions.php";
echo '<div class="item wide gradient"><p class="number">2</p><br/>';
$subject = "testmail van HomewizardPHP";
$message = "Hello ".$email_notifications.",\r\n\r\nDit is een testmail van HomewizardPHP.";
mail ($email_notifications ,$subject ,$message );


echo '</div>';

if(!isset($_POST['actionscron']) && !isset($_POST['showtest'])) {ob_clean(); $db->close();}
?>
