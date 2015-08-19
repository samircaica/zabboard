<?=$message?>

<?php

if($authorized == true) {
	foreach($hostGroups as $hostGroup) {
		echo $hostGroup->name."<br>";
		echo $hostGroup->groupid."<br>";
	}
	?>
	<a href="infraestructura">Infraestructura</a>
	<a href="servicios">Servicios</a>
	<?php
}
if($authorized == false) {
	?>
	<a href="/">Volver</a>
	<?php
}
?>