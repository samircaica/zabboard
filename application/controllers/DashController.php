<?php

class DashController extends Controller {


	function prueba() {
		$dash = new Dash();
		//$dash->id = 1;
		$dash->name = "Samir1";
		$dash->lastname = "Caica1";
		$dash->save();
		echo $dash->id;
		$dash->save();
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
		$this->set('title','Variable definida en function prueba');
		//$this->render_partial("prueba2");
		$this->render("prueba");
		//$this->render_partial("prueba2");
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