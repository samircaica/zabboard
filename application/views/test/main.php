<?php
if($this->params->message != '') {
?>
Mensaje: <?=$this->params->message?>
<?php
}
?>
<?php

if($this->params->authorized == true) {
	foreach($hostGroups as $hostGroup) {
		echo $hostGroup->name."<br>";
		echo $hostGroup->groupid."<br>";
	}
	?>
	<a href="infraestructura">Infraestructura</a>
	<a href="servicios">Servicios</a>
	<?php
}
if($this->params->authorized == false) {
	?>
	<a href="/">Volver</a>
	<?php
}
?>