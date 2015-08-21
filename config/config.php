<?php
 
/** Configuration Variables **/
 
define ('DEVELOPMENT_ENVIRONMENT',true);
 
define('DB_NAME', 'yourdatabasename');
define('DB_USER', 'yourusername');
define('DB_PASSWORD', 'yourpassword');
define('DB_HOST', 'localhost');

define('DEFAULT_CONTROLLER', 'test');

define('BASE_URL','http://zabboard.dev');

/*
	Add array list with extra libs
*/
$loadLibs = array(
	'../libs/ZabbixApi.class.php',
	'../libs/TestLib.php');

?>