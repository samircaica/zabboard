<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));


require_once (ROOT . DS . 'library' . DS . 'LoadLibs.php');
require_once(ROOT . DS . 'config' . DS . 'config.php');

$libs = new LoadLibs($loadLibs);
$libs->loadLibraries();

session_start();

if(!isset($_SESSION['params'])) {
    $_SESSION['params'] = new stdClass();
}

$url = $_GET['url'];

require_once (ROOT . DS . 'library' . DS . 'Base.php');

?>