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
		/*
		$postg1 = new Postg();
		$postg1->name = "Samir9";
		$postg1->lastname = "Caica9";
		$postg1->age = 762;
		$postg1->save()2
		echo $postg1->id." ".$postg1->name." ".$postg1->lastname." ".$postg1->age."<BR>";
		*/
		echo "<BR>";
		echo $postg->id." ".$postg->name." ".$postg->lastname." ".$postg->age."<BR>";
		$postg->name = "Samir12";
		$postg->lastname = "Caica10";
		$postg->age = 98;
		$postg->save();
		echo $postg->id." ".$postg->name." ".$postg->lastname." ".$postg->age."<BR>";
		echo "<BR>";
	}
}
?>