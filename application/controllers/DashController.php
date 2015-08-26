<?php

class DashController extends Controller {


	function prueba() {
		$dash = new Dash();
		//$dash->id = 1;
		//$dash->name = "Samir1";
		//$dash->lastname = "Caica1";
		//$dash->save();
		//echo $dash->id;
		//$dash->save();
		$dash->findFirst();
		echo "Find First</BR>";
		echo $dash->id." ".$dash->name." ".$dash->lastname." ".$dash->age;
		echo "</BR>";
		$dash->name = 'Samir';
		$dash->age = 36;
		//$dash->id = 30;
		$dash->save();
		echo $dash->id." ".$dash->name." ".$dash->lastname." ".$dash->age;
		echo "</BR>";
		$dash->findLast();
		echo $dash->name;
		echo "</BR>";
		//$ret = array();
		$ret = $dash->findAll();
		//print_r($ret);
		echo "</BR>";
		foreach($ret as $objDash) {
			//print_r($objDash);
			echo $objDash->name;
			echo "</BR>";
		}
		echo "</BR>";
		$ret = $dash->find(array('name' => '%Samir%', 'AND id' => '1'));
		//$ret = $dash->find(array('name' => 'Samir'));
		//$ret = $dash->find(array('name' => '%Samir%'));
		foreach($ret as $objDash) {
			//print_r($objDash);
			echo $objDash->name." ".$objDash->lastname;
			echo "</BR>";
		}
		echo "</BR>";
		/*
		$ret = $dash->find(array('name' => 'Samir', 'AND column' => '%valor%', 'OR column2' => 'valor2'));
		foreach($ret as $objDash) {
			//print_r($objDash);
			echo $objDash->name;
			echo "</BR>";
		}
		echo "</BR>";
		*/
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