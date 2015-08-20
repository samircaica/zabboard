<?php
/*
if(!isset($_SESSION)){
	//echo "no session index2";
	session_start();
	//$_SESSION['params'] = new stdClass();
}
*/
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
 
$url = $_GET['url'];
 
//require_once (ROOT . DS . 'library' . DS . 'bootstrap.php');
require_once (ROOT . DS . 'config' . DS . 'config.php');
require_once (ROOT . DS . 'library' . DS . 'base.php');

?>