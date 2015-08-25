<?php

class Dash extends Model {
	public $id;
	public $name;
	public $lastname;

	function __construct() {
		parent::__construct();
		//echo "Constructor Dash<BR>";
		//echo "provider1: ".$this->_dbProvider."<BR>";
		//echo "provider2: ".DB_PROVIDER."<BR>";
	}

	function test() {
		echo "testttttt<BR>";
	}
}
?>