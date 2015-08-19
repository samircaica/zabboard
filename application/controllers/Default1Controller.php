<?php

class Default1Controller extends Controller {

	private $title = 'Default Index';

	function index() {
		$this->set('title',$this->title);
	}

	function add() {
		$this->set('title', "Add page");
		$this->set('todo', $_POST['todo']);
	}
}

?>