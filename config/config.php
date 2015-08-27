<?php
 
/** Configuration Variables **/
 
define ('DEVELOPMENT_ENVIRONMENT',true);

//define('DB_PROVIDER', 'mysql');
//define('DB_PORT', '3306');
define('DB_PROVIDER', 'postgresql');
define('DB_PORT', '5432');
//define('DB_PROVIDER', 'sqlite');

define('DB_HOST', 'localhost');
define('DB_NAME', 'samircaica');
define('DB_USER', 'samircaica');
define('DB_PASSWORD', '');

define('DEFAULT_CONTROLLER', 'test');

define('BASE_URL','http://zabboard.dev');

/*
	Add array list with extra libs
*/
$loadLibs = array(
	'../libs/ZabbixApi.class.php',
	'../libs/TestLib.php');

?>