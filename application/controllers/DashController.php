<?php

class DashController extends Controller {


	function prueba() {
		//$this->params = new stdClass();
		$this->params->nombre = "Samir";
		/*
		$var1 = new stdClass();
		$var1->valor = "Asd";
		$_SESSION['favcolor'] = "String";
		echo $_SESSION['favcolor'];
		echo $var1->valor;
		$_SESSION['favcolor'] = $var1;
		*/
		$this->render_partial("prueba");
	}

	function prueba2() {
		//echo $_SESSION['favcolor'];
		/*
		$var2 = $_SESSION['favcolor'];
		echo $var2->valor;
		*/
		echo $this->params->nombre;
		
		$this->render_partial("prueba2");
	}
}
?>