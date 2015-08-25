<?php

class DbProvider {
	protected $_dbProviderMain;
	protected $testVar;
	protected $connector;

	protected $dbs = array(
		'MYSQL' =>'1',
		'POSTGRESQL' => '2',
		'SQLITE' => '3'
	);

    function __construct() {
    	$this->_dbProviderMain = $this->dbs[strtoupper(DB_PROVIDER)];
    	//echo "Provider en DbProvider: ".$this->_dbProviderMain."<BR>";
    	
    	switch($this->_dbProviderMain) {
    		case $this->dbs['MYSQL']:
    			echo "carga MYSQL<BR>";
                $this->loadMySQL();
                break;

            case $this->dbs['POSTGRESQL']:
                $this->loadMySQL();
                break;

            case $this->dbs['SQLITE']:
                $this->loadMySQL();
                break;
    	}


    }

    function loadMySQL() {
    	//echo "Load connector Mysql<BR>";
    	$this->connector = new MySQL();
    }

    function find($q) {}

    function findAll() {}

    function findFirst() {}

    function findLast() {}

    function save() {
    	echo "En Save DbProvider()</BR>";
    	$r = new ReflectionObject($this);
    	foreach ($r->getProperties(ReflectionProperty::IS_PUBLIC) AS $key => $value) {
    		$key = $value->getName();
            $value = $value->getValue($this);

            echo "- Valores definidos -> column: ".$key." value: ".$value."<BR>";
    	}

    }

    function update() {}

    function delete() {}

    function __destruct() {
    	//echo "Close Conn en DbProvider</BR>";
    	$this->connector->close();
    }

}
?>