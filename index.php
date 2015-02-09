<?php
include "header.php";
include "functions.php";
if($authenticated == true) {
	if (isset($_POST['schakel'])) {
		if (isset($_POST['dimlevel'])) echo dim($_POST['switch'],$_POST['dimlevel'],'m',null,null);
		else if (isset($_POST['somfy'])) echo somfy($_POST['switch'],$_POST['somfy'],'m',null,null);
		else if (isset($_POST['schakel'])) echo schakel($_POST['switch'],$_POST['schakel'],'m',null,null);
	} 
	if(isset($_POST['radiator']) && isset($_POST['set_temp'])) echo radiator($_POST['radiator'],$_POST['set_temp'],'m',null,null);
	if (isset($_POST['schakelscene'])) echo scene($_POST['scene'],$_POST['schakelscene'],'m',null,null);
	if (isset($_POST['updactie'])) {
		$variable = $_POST['variable'];
		if($_POST['updactie']=='off') $value = 'no'; else $value = 'yes';
		$sql="update settings set value = '$value' where variable like '$variable';";
		if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	}
}
include "data.php";
echo '<div class="isotope">';
//---SCHAKELAARS---
if($toon_schakelaars=='yes') {
	echo '<div class="item gradient"><p class="number">'.$positie_schakelaars.'</p>
			<form id="showallswitches" action="#" method="post">
				<input type="hidden" name="showallswitches" value="yes" />
				<a href="#" onclick="document.getElementById(\'showallswitches\').submit();" style="text-decoration:none"><h2 >Schakelaars</h2></a>
			</form>';
	$sql="select id_switch, name, type, favorite, volgorde from switches where type in ('switch', 'dimmer', 'virtual')";
	if (!isset($_POST['showallswitches'])) $sql.=" AND favorite like 'yes'";
	$sql.=" order by volgorde asc, favorite desc, name asc";
	if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	if($result->num_rows>0) {
		$group = 0;
 		echo '
		<table align="center"><tbody>';
		while($row = $result->fetch_assoc()){
			$switchon = "";
			$tdstyle = '';
			if($group != $row['volgorde']) $tdstyle = 'style="'.$css_td_newgroup.'"';
			$group = $row['volgorde'];
			if($row['type']=='asun') {if(${'switchstatus'.$row['id_switch']}=="1") {$switchon = "off";} else {$switchon = "on";}}
			else {if(${'switchstatus'.$row['id_switch']}=="on") {$switchon = "off";} else {$switchon = "on";}}
			echo '<tr>
				<td><img id="'.$row['type'].'Icon" src="images/empty.gif" width="1px" height="1px" /></td>
				<td align="right" '.$tdstyle.'>
					<form action="switchhistory.php" method="post" id="'.$row['name'].'">
						<input type="hidden" name="filter" value="'.$row['name'].'">
						<a href="#" onclick="document.getElementById(\''.$row['name'].'\').submit();" style="text-decoration:none">'.$row['name'].'</a>
					</form>
				</td>
				<td width="115px" '.$tdstyle.' ><form method="post" action="#"><input type="hidden" name="switch" value="'.$row['id_switch'].'"/><input type="hidden" name="schakel" value="'.$switchon.'"/>';
			if($row['type']=='dimmer') {
				print '<select name="dimlevel"  class="abutton handje gradient" onChange="this.form.submit()" style="margin-top:4px">
				<option '.${'switchstatus'.$row['id_switch']}.') selected>'.${'switchstatus'.$row['id_switch']}.'</option>
				<option>0</option>
				<option>10</option>
				<option>20</option>
				<option>30</option>
				<option>40</option>
				<option>50</option>
				<option>60</option>
				<option>70</option>
				<option>80</option>
				<option>90</option>
				<option>100</option>
				</select>';
			} else if($row['type']=='asun') {
				print '
				<section class="slider">	
				<input type="hidden" value="somfy" />
				<input type="checkbox" value="switch'.$row['id_switch'].'" id="switch'.$row['id_switch'].'" name="switch'.$row['id_switch'].'" '; if(${'switchstatus'.$row['id_switch']}==1) {print 'checked';} print ' onChange="this.form.submit()"/>
				<label for="switch'.$row['id_switch'].'"></label>
				</section>';
			} else if($row['type']=='virtual') {
				print '
				<form method="post" action="#"><input type="submit" name="schakel" value="on" class="abutton handje gradient"/><input type="submit" name="schakel" value="off" class="abutton handje gradient"/></form>';
			} else {
				print '
				<section class="slider">	
				<input type="checkbox" value="switch'.$row['id_switch'].'" id="switch'.$row['id_switch'].'" name="switch'.$row['id_switch'].'" '; if($switchon=="off") {print 'checked';} print ' onChange="this.form.submit()"/>
				<label for="switch'.$row['id_switch'].'"></label>
				</section>';
			}
			print '</td></form></tr>';
		}
		echo "</tbody></table>";
	}
	$result->free();
	echo '<br/><br/></div>';
}

/* SCENES */
if($toon_scenes=='yes') {
	echo '<div class="item gradient"><p class="number">'.$positie_scenes.'</p>
			<form id="showallscenes" action="#" method="post">
				<input type="hidden" name="showallscenes" value="yes" />
				<a href="#" onclick="document.getElementById(\'showallscenes\').submit();" style="text-decoration:none"><h2>Scènes</h2></a>
			</form>';
	$sql="select id_switch, name, type, favorite, volgorde from switches where type in ('scene')";
	if (!isset($_POST['showallscenes'])) $sql.=" AND favorite like 'yes'";
	$sql.=" order by volgorde asc, favorite desc, name asc";
	if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	if($result->num_rows>0) {
		$group = 0;
		while($row = $result->fetch_assoc()){
			echo '<table width="100%"><thead><tr><th colspan="2">';
			if($detailscenes=='optional') {print '<a href="#" onclick="toggle_visibility(\'scene'.$row['id_switch'].'\');" style="text-decoration:none">'.$row['name'].'</a>';} else {print $row['name'];}
			print '</th>
			<th width="50px"><form method="post" action="#"><input type="hidden" name="scene" value="'.$row['id_switch'].'"/><input type="hidden" name="schakelscene" value="on"/><input type="submit" value="AAN" class="abutton gradient"/></form></th>
			<th width="50px"><form method="post" action="#"><input type="hidden" name="scene" value="'.$row['id_switch'].'"/><input type="hidden" name="schakelscene" value="off"/><input type="submit" value="UIT" class="abutton gradient"/></form></th>
			</tr></thead>';
			if(($detailscenes=='yes') || ($detailscenes=='optional')) {
				if($detailscenes=='optional') {
					print '<tbody id="scene'.$row['id_switch'].'" style="display:none" class="handje">';
				} else {
					print '<tbody>';
				}
				$datascene = null;
				$datascenes = null;
				$jsonscene = file_get_contents($jsonurl.'gp/get/'.$row['id_switch']);
				$datascenes = json_decode($jsonscene,true);
				if($debug=='yes') print_r($datascenes);
				if (!$datascenes) {
					echo "No information available...";
				} else {
					foreach($datascenes['response'] as $datascene) {
						print '<tr><td align="right" width="60px">'.$datascene['type'].'&nbsp;&nbsp;</td><td align="left">&nbsp;'.$datascene['name'].'</td><td>'.$datascene['onstatus'].'</td><td>'.$datascene['offstatus'].'</td></tr>';
					}
				}
			}
		}
		echo '</tbody></table>';
	}
	$result->free();
	echo '</div>';
}

/* SOMFY */
if($toon_somfy=='yes') {
	echo '<div class="item gradient"><p class="number">'.$positie_somfy.'</p>
			<form id="showallsomfy" action="#" method="post">
				<input type="hidden" name="showallsomfy" value="yes" />
				<a href="#" onclick="document.getElementById(\'showallsomfy\').submit();" style="text-decoration:none"><h2>Somfy</h2></a>
			</form>';
	$sql="select id_switch, name, volgorde from switches where type like 'somfy'";
	if (!isset($_POST['showallsomfy'])) $sql.=" AND favorite like 'yes'";
	$sql.=" order by volgorde asc, favorite desc, name asc";
	if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	if($result->num_rows>0) {
		$group = 0;
		echo '<table align="center"><tbody>';
		while($row = $result->fetch_assoc()){
			$tdstyle = '';
			if($group != $row['volgorde']) $tdstyle = 'style="'.$css_td_newgroup.'"';
			$group = $row['volgorde'];
			print '<tr>
			<td><img id="somfyIcon" src="images/empty.gif" width="1px" height="1px" /></td>
			<td align="right" '.$tdstyle.'>
				<form action="switchhistory.php" method="post" id="'.$row['name'].'">
					<input type="hidden" name="filter" value="'.$row['name'].'">
					<a href="#" onclick="document.getElementById(\''.$row['name'].'\').submit();" style="text-decoration:none">'.$row['name'].'</a>
				</form></td>
			<td width="185px" '.$tdstyle.'><form method="post" action="#">
			<input type="hidden" name="switch" value="'.$row['id_switch'].'"/>
			<input type="hidden" name="schakel" value="'.$row['id_switch'].'"/>
			<input type="submit" id="somfydownIcon" name="somfy" value="down" class="abuttonsomfy handje gradient"/>
			<input type="submit" id="somfystopIcon" name="somfy" value="stop" class="abuttonsomfy handje gradient"/>
			<input type="submit" id="somfyupIcon" name="somfy" value="up" class="abuttonsomfy handje gradient"/>
			</form></td></tr>';
		}
		echo "</tbody></table>";
	}
	$result->free();
	echo '</div>';
}

//---RADIATORS---
if($toon_radiatoren=='yes') {
	echo '<div class="item gradient"><p class="number">'.$positie_radiatoren.'</p>
			<form id="showallradiators" action="#" method="post">
				<input type="hidden" name="showallradiators" value="yes"/>
				<a href="#" onclick="document.getElementById(\'showallradiators\').submit();" style="text-decoration:none"><h2>Radiatoren</h2></a>
			</form>';
	$sql="select id_switch, name, temp, volgorde from switches where type like 'radiator'";
	if (!isset($_POST['showallradiators'])) $sql.=" AND favorite like 'yes'";
	$sql.=" order by volgorde asc, favorite desc, name asc";
	if(!$result = $db->query($sql)){ echo ('There was an error running the query [' . $db->error . ']');}
	if($result->num_rows>0) {
		$group = 0;
		echo '<table align="center"><tbody>';
		while($row = $result->fetch_assoc()){
			$tdstyle = '';
			if($group != $row['volgorde']) $tdstyle = 'style="'.$css_td_newgroup.'"';
			$group = $row['volgorde'];
			print '<tr>
			<td><img id="radiatorIcon" src="images/empty.gif" width="1px" height="1px" /></td>
			<td align="right" '.$tdstyle.'>
				<form action="switchhistory.php" method="post" id="'.$row['name'].'">
					<input type="hidden" name="filter" value="'.$row['name'].'">
					<a href="#" onclick="document.getElementById(\''.$row['name'].'\').submit();" style="text-decoration:none">'.$row['name'].'</a>
				</form></td>
			<td width="60px" '.$tdstyle.'>
				<form method="post" action="#">
					<input type="hidden" name="radiator" value="'.$row['id_switch'].'"/>
					<select name="set_temp"  class="abutton handje gradient" onChange="this.form.submit()" style="margin-top:4px">
						<option '.${'switchstatus'.$row['id_switch']}.') selected>'.${'switchstatus'.$row['id_switch']}.'</option>
						<option>8</option>
						<option>10</option>
						<option>12</option>
						<option>14</option>
						<option>16</option>
						<option>18</option>
						<option>19</option>
						<option>20</option>
						<option>20.5</option>
						<option>21</option>
						<option>21.5</option>
						<option>22</option>
						<option>22.5</option>
						<option>23</option>
						<option>23.5</option>
						<option>24</option>
					</select>
				</form>
			</td>
			<td width="60px" '.$tdstyle.'>';
			if(!empty($row['temp']) || $row['temp']==0) {
				if(${'thermometerte'.$row['temp']}>${'switchstatus'.$row['id_switch']}+1) echo '<font color="#880000">';
				else if(${'thermometerte'.$row['temp']}<${'switchstatus'.$row['id_switch']}-1) echo '<font color="#000088">';
				else echo '<font color="#008800">';
				echo ${'thermometerte'.$row['temp']}.'°C';
			}
			echo '</font></td>
			</tr>';
		}
		echo '</tbody></table>';
	}
	$result->free();
	echo '<br/><br/></div>';
}

//---SENSORS--
if($toon_sensoren=='yes') {
	echo '<div class="item gradient"><p class="number">'.$positie_sensoren.'</p>
			<form id="showallsensors" action="#" method="post">
				<input type="hidden" name="showallsensors" value="yes"/>
				<a href="#" onclick="document.getElementById(\'showallsensors\').submit();" style="text-decoration:none"><h2>Sensoren</h2></a>
			</form>';
	$sql="select id_sensor, name, type, volgorde from sensors WHERE type in ('smoke','contact','doorbell','motion')";
	if (!isset($_POST['showallsensors'])) $sql.=" AND favorite like 'yes'";
	$sql.=" order by volgorde asc, favorite desc, name asc";
	if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	if($result->num_rows>0) {
		$group = 0;
		echo '<div ><table align="center" width="100%">';
		while($row = $result->fetch_assoc()){
       		echo '<tr>';
       		$type = $row['type'];
			echo '<td style="color:#F00; font-weight:bold"><img id="'.$type.'Icon" src="images/empty.gif" width="1px" height="1px" /></td>';
       		if($type=="contact") $type = "Magneet";
			if($type=="motion") $type = "Beweging";
			if($type=="doorbell") $type = "Deurbel";
			if($type=="smoke") $type = "Rook";
			if(${'sensorstatus'.$row['id_sensor']} == "yes") {
				echo '<td style="color:#F00; font-weight:bold">
						<form action="history.php" method="post" id="'.$row['name'].'">
						<input type="hidden" name="filter" value="'.$row['name'].'">
						<a href="#" onclick="document.getElementById(\''.$row['name'].'\').submit();" style="text-decoration:none">'.$row['name'].'</a>
						</form>
					</td>';
					} else {
						echo '<td><form action="history.php" method="post" id="'.$row['name'].'">
						<input type="hidden" name="filter" value="'.$row['name'].'">
						<a href="#" onclick="document.getElementById(\''.$row['name'].'\').submit();" style="text-decoration:none">'.$row['name'].'</a>
					</form></td>';
					}
       		if(${'sensorstatus'.$row['id_sensor']} == "yes") {echo '<td style="color:#A00; font-weight:bold">';} else {echo '<td>';}
			if($type=="Magneet" && ${'sensorstatus'.$row['id_sensor']} == "no") { echo 'Gesloten'; }
			else if ($type=="Magneet" && ${'sensorstatus'.$row['id_sensor']} == "yes") { echo 'Open'; }
			else if ($type=="Beweging" && ${'sensorstatus'.$row['id_sensor']} == "yes") { echo 'Beweging'; }
			else if ($type=="Beweging" && ${'sensorstatus'.$row['id_sensor']} == "no") { echo ''; }
			else if ($type=="Deurbel" && ${'sensorstatus'.$row['id_sensor']} == "no") { echo ''; }
			else if ($type=="Deurbel" && ${'sensorstatus'.$row['id_sensor']} == "yes") { echo 'Gebeld'; }
			else if ($type=="Rook" && ${'sensorstatus'.$row['id_sensor']} == "no") { echo ''; }
			else if ($type=="Rook" && ${'sensorstatus'.$row['id_sensor']} == "yes") { echo 'ROOK!!!'; }
			else echo ${'sensorstatus'.$row['id_sensor']};
			echo '</td>';
			if(${'sensorstatus'.$row['id_sensor']} == "yes") {echo '<td style="color:#A00; font-weight:bold">'.${'sensortimestamp'.$row['id_sensor']}.'</td>';} else {echo '<td>'.${'sensortimestamp'.$row['id_sensor']}.'</td>';}
			echo '</tr>';
		}
		echo "</table></div>";
	}
	$result->free();
	echo '</div>';
}

//--THERMOMETERS--
if($toon_temperatuur=='yes') {
	echo '<div class="item gradient"><p class="number">'.$positie_temperatuur.'</p>
			<form id="showalltemps" action="#" method="post">
				<input type="hidden" name="showalltemps" value="yes"/>
				<a href="#" onclick="document.getElementById(\'showalltemps\').submit();" style="text-decoration:none"><h2>Temperatuur</h2></a>
			</form>';
	$sql="select id_sensor, name, volgorde, tempk, tempw from sensors WHERE type in ('temp')";
	if (!isset($_POST['showalltemps'])) $sql.=" AND favorite like 'yes'";
	$sql.=" order by volgorde asc, favorite desc, name asc";
	if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	if($result->num_rows>0) {	
		echo '<div><table width="100%"><tr><th></th><th>temp</th><th>hum</th></tr>';
		while($row = $result->fetch_assoc()){
			echo '<tr>';
			if($result->num_rows>1) {echo '<td><form action="temp.php" method="post" id="temp'.$row['name'].'">
						<input type="hidden" name="filter" value="'.$row['name'].'">
						<a href="#" onclick="document.getElementById(\'temp'.$row['name'].'\').submit();" style="text-decoration:none">'.$row['name'].'</a>
					</form></td>';} else { echo '<td></td>';}
			if(${'thermometerte'.$row['id_sensor']} < $row['tempk']) {
				echo '<td style="color:#00A;">';
			} else if(${'thermometerte'.$row['id_sensor']} > $row['tempw']) {
				echo '<td style="color:#A00;">';
			} else {
				echo '<td>';
			}
			echo ${'thermometerte'.$row['id_sensor']}.' °C</td>';
			echo '<td>'.${'thermometerhu'.$row['id_sensor']}.' %</td></tr>';
		}
		echo "</table></div>";
	}
	$result->free();
	echo '</div>';
}

//--RAINMETERS--
if($toon_regen=='yes') {
	if(!empty($rainmeters)) {
		echo '<div class="item handje gradient" onclick="window.location=\'rain.php\';"><p class="number">'.$positie_regen.'</p><h2>Regen</h2><table width="100%"><tr><th></th><th>Vandaag</th><th>Laatste 3u</th></tr>';
		foreach($rainmeters as $rainmeter){
			if($authenticated == true && $debug=='yes') print_r($rainmeter);
			echo '<tr>';
			if(count($rainmeters)>1) {echo '<td>'.$rainmeter['name'].'</td>';} else { echo '<td></td>';}
			echo '<td>'.$rainmeter['mm'].' mm</td><td>'.$rainmeter['3h'].' mm</td></tr>';
		}
		echo "</table></div>";
	}
}

//--WINDMETERS--
if($toon_wind=='yes') {
	if(!empty($windmeters)) {
		echo '<div class="item handje gradient" onclick="window.location=\'wind.php\';"><p class="number">'.$positie_wind.'</p><h2>Wind</h2><table width="100%"><tr><th></th><th>ws</th><th>gu</th><th>dir</th></tr>';
		foreach($windmeters as $windmeter){
			if($authenticated == true && $debug=='yes') print_r($windmeter);
			if(isset($windmeter['ws'])) {
				echo '<tr>';
				if(count($windmeters)>1) {echo '<td>'.$windmeter['name'].'</td>';} else { echo '<td></td>';}
				echo '<td>'.$windmeter['ws'].' km/u</td><td>'.$windmeter['gu'].' km/u</td><td>'.$windmeter['dir'].' °</td></tr>';
			}
		}
		echo "</table></div>";
	}
}

//--ENERGYLINKS--
if($toon_energylink=='yes') {
	if(!empty($energylinks)) {
		echo '<div class="item handje gradient"><p class="number">'.$positie_energylink.'</p><h2>Energylink</h2><table width="100%">';
		foreach($energylinks as $energylink){
			if($authenticated == true && $debug=='yes') print_r($energylink);
				echo '<tr><td>S1 PO</td><td>'.$energylink['s1']['po'].'</td></tr>';
				echo '<tr><td>S1 dagtotaal</td><td>'.$energylink['s1']['dayTotal'].'</td></tr>';
				echo '<tr><td>S1 PO+</td><td>'.$energylink['s1']['po+'].'</td></tr>';
				echo '<tr><td>S1 PO+t</td><td>'.$energylink['s1']['po+t'].'</td></tr>';
				echo '<tr><td>S2 PO</td><td>'.$energylink['s2']['po'].'</td></tr>';
				echo '<tr><td>S2 dagtotaal</td><td>'.$energylink['s2']['dayTotal'].'</td></tr>';
				echo '<tr><td>S2 PO+</td><td>'.$energylink['s2']['po+'].'</td></tr>';
				echo '<tr><td>S2 PO+t</td><td>'.$energylink['s2']['po+t'].'</td></tr>';
				echo '<tr><td>aggregate PO</td><td>'.$energylink['aggregate']['po'].'</td></tr>';
				echo '<tr><td>aggregate dagtotaal</td><td>'.$energylink['aggregate']['dayTotal'].'</td></tr>';
				echo '<tr><td>aggregate PO+</td><td>'.$energylink['aggregate']['po+'].'</td></tr>';
				echo '<tr><td>aggregate PO+t</td><td>'.$energylink['aggregate']['po+t'].'</td></tr>';
				echo '<tr><td>used PO</td><td>'.$energylink['used']['po'].'</td></tr>';
				echo '<tr><td>used dagtotaal</td><td>'.$energylink['used']['dayTotal'].'</td></tr>';
				echo '<tr><td>used PO+</td><td>'.$energylink['used']['po+'].'</td></tr>';
				echo '<tr><td>used PO+t</td><td>'.$energylink['used']['po+t'].'</td></tr>';
				echo '<tr><td>gas uur</td><td>'.$energylink['gas']['lastHour'].'</td></tr>';
				echo '<tr><td>gas dag</td><td>'.$energylink['gas']['dayTotal'].'</td></tr>';
		}
		echo "</table></div>";
	}
}

//---ACTIES---
if($toon_acties=='yes') {
	echo '<div class="item gradient"><p class="number">'.$positie_acties.'</p>
			<form id="showallacties" action="#" method="post">
				<input type="hidden" name="showallacties" value="yes" />
				<a href="#" onclick="document.getElementById(\'showallacties\').submit();" style="text-decoration:none"><h2 >Acties</h2></a>
			</form>';
	$sql="select variable, value from settings where variable like 'actie_%'";
	$sql.=" order by variable";
	if (!isset($_POST['showallacties'])) $sql.=" LIMIT 0,0";
	if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	if($result->num_rows>0) {
		$group = 0;
 		echo '
		<table align="center"><tbody>';
		while($row = $result->fetch_assoc()){
			$switchon = "";
			$tdstyle = '';
			//if($group != $row['volgorde']) $tdstyle = 'style="'.$css_td_newgroup.'"';
			//$group = $row['volgorde'];
			if($row['value']=="yes") {$switchon = "off";} else {$switchon = "on";}
			echo '<tr>
				<td align="right" '.$tdstyle.'>'.ucwords(str_replace('_', ' ', ltrim($row['variable'],'actie'))).'</td>
				<td width="115px" '.$tdstyle.' ><form method="post" action="#"><input type="hidden" name="updactie" value="'.$switchon.'"/><input type="hidden" name="variable" value="'.$row['variable'].'"/>
				<section class="slider">	
				<input type="checkbox" value="switch'.$row['variable'].'" id="switch'.$row['variable'].'" name="switch'.$row['variable'].'" '; if($switchon=="off") {print 'checked';} print ' onChange="this.form.submit()"/>
				<label for="switch'.$row['variable'].'"></label>
				</section>
				</td></form></tr>';
		}
		echo "</tbody></table>";
	}
	$result->free();
	echo '<br/><br/></div>';
}
?>
<script type="text/javascript">
<!--
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'inherit')
          e.style.display = 'none';
       else
          e.style.display = 'inherit';
    }
//-->
</script>
<?PHP include "footer.php";?>