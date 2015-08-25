<?php
 
/** Configuration Variables **/
 
define ('DEVELOPMENT_ENVIRONMENT',true);

define('DB_PROVIDER', 'mysql');
//define('DB_PROVIDER', 'postgresql');
//define('DB_PROVIDER', 'sqlite');

define('DB_HOST', 'localhost');
define('DB_NAME', 'prueba');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');

define('DEFAULT_CONTROLLER', 'test');

define('BASE_URL','http://zabboard.dev');

$dbProvider = "mysql";

/*
	Add array list with extra libs
*/
$loadLibs = array(
	'../libs/ZabbixApi.class.php',
	'../libs/TestLib.php');

?>