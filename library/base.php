<?php
 
/** Check if environment is development and display errors **/
 
function setReporting() {
if (DEVELOPMENT_ENVIRONMENT == true) {
    error_reporting(E_ALL);
    ini_set('display_errors','On');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors','Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', ROOT.DS.'logs'.DS.'error.log');
}
}
 
/** Check for Magic Quotes and remove them **/
 
function stripSlashesDeep($value) {
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return $value;
}
 
function removeMagicQuotes() {
if ( get_magic_quotes_gpc() ) {
    $_GET    = stripSlashesDeep($_GET   );
    $_POST   = stripSlashesDeep($_POST  );
    $_COOKIE = stripSlashesDeep($_COOKIE);
}
}
 
/** Check register globals and remove them **/
 
function unregisterGlobals() {
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}
 
/** Main Call Function **/
 
function callHook() {
    global $url;
 
    $urlArray = array();

    if(empty($url)) {
        $url = DEFAULT_CONTROLLER;
    }
    if (!(substr($url, -1) == '/')) {
        $url = $url."/";
    }

    $urlArray = explode("/",$url);
 
    if (!empty($urlArray)) {
        $controller = $urlArray[0];
        array_shift($urlArray);
        $action = $urlArray[0];
        array_shift($urlArray);
        $queryString = $urlArray;
        
    }
    
    try {
        if(file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . ucwords($controller) . 'Controller.php')) {
            $controllerName = $controller;
            $controller = ucwords($controller);

            if(file_exists(ROOT . DS . 'application' . DS . 'models' . DS . ucwords($controller) . '.php')) {
                $model = rtrim($controller, 's');
            } else {
                throw new Exception ('Model file "'. ucwords($controller) .'" doesn\'t exist, please add.');
            }

            $controller .= 'Controller';

            if(empty($action)) {
                $action = "index";
            }

            $dispatch = new $controller($model, $controllerName, $action, $queryString);
            

        } else {
            throw new Exception ('Controller file "'. ucwords($controller) .'Controller" doesn\'t exist, please add.');
        }
    } catch(Exception $e) {
        echo "Message : " . $e->getMessage();
        //echo "Code : " . $e->getCode();
        echo "<BR>";
    }

 
    if ((int)method_exists($controller, $action)) {
        call_user_func_array(array($dispatch,$action),$queryString);
    } else {
        /* Error Generation Code Here */
    }
}
 
/** Autoload any classes that are required **/
 
function __autoload($className) {
    if (file_exists(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php')) {
        require_once(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php');
    } else if (file_exists(ROOT . DS . 'libs' . DS . strtolower($className) . '.class.php')) {
        require_once(ROOT . DS . 'libs' . DS . strtolower($className) . '.class.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . ucwords($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . ucwords($className) . '.php');
    } else if(file_exists(ROOT . DS . 'application' . DS . 'models' . DS . ucwords($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'models' . DS . ucwords($className) . '.php');
    } else if (file_exists(ROOT . DS . 'lib' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'models' . DS . ucwords($className) . '.php');
    } else {
        /* Error Generation Code Here */
    }
}
 
setReporting();
removeMagicQuotes();
//unregisterGlobals();
callHook();

?>