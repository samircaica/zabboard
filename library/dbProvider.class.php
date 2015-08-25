<?php

class DbProvider {
	protected $_dbProviderMain;
	protected $testVar;

    function __construct() {
    	$this->_dbProviderMain = DB_PROVIDER;
    	echo "Provider en DbProvider: ".$this->_dbProviderMain."<BR>";
    }

    function save() {
    	echo "En Save DbProvider()</BR>";
    	$r = new ReflectionObject($this);
    	foreach ($r->getProperties(ReflectionProperty::IS_PUBLIC) AS $key => $value) {
    		$key = $value->getName();
            $value = $value->getValue($this);

            echo "column: ".$key." value: ".$value."<BR>";
    	}

    }

    function __destruct() {
    	echo "Close Conn en DbProvider</BR>";
    }

}
?>