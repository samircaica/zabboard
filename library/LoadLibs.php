<?php

/*
Add extra libraries to the project
*/

//require_once '../libs/ZabbixApi.class.php';

class LoadLibs {
	protected $loadLibs;

	function __construct($arrayLibs) {
		$this->loadLibs = $arrayLibs;
	}

	function loadLibraries() {
		foreach ($this->loadLibs as $lib) {
			try {
				if (file_exists($lib)) {
					require_once $lib;
				} else {
                    throw new Exception ('Library '. $lib .' doesn\'t exist');
                }
            } catch(Exception $e) {    
                  echo "Message : " . $e->getMessage();
                  //echo "Code : " . $e->getCode();
                  echo "<BR>";
            }
		}
	}
}

?>