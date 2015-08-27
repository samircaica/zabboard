<?php

class PostgController extends Controller {

	function index() {
		$this->set('title','Página de prueba de Postgresql');
		$this->render("prueba");

		$postg = new Postg();

		echo "Find First</BR>";
		$postg->findFirst();
		echo $postg->id." ".$postg->name." ".$postg->lastname." ".$postg->age."<BR>";
		echo "</BR>";
		$ret = $postg->findAll();
		foreach($ret as $objDash) {
			//print_r($objDash);
			echo $objDash->id." ".$objDash->name." ".$objDash->lastname." ".$objDash->age;
			echo "</BR>";
		}
		echo "</BR>";
		$ret = $postg->find(array('name' => '%Samir%', 'ORDER BY' => 'id DESC'));
		echo "</BR>";
		foreach($ret as $objDash) {
			echo $objDash->id." ".$objDash->name." ".$objDash->lastname." ".$objDash->age;
			echo "</BR>";
		}
		echo "</BR>";

	}
}
?>