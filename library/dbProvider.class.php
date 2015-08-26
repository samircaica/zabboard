<?php

class DbProvider {
	protected $_dbProviderMain;
	protected $_dbProviderMainValue;
	protected $_dbName;
	protected $_tableName;
	protected $testVar;
	protected $connector;

	protected $dbs = array(
		'MYSQL' =>'1',
		'POSTGRESQL' => '2',
		'SQLITE' => '3'
	);

    function __construct() {
    	$this->_dbProviderMain = DB_PROVIDER;
    	$this->_dbProviderMainValue = $this->dbs[strtoupper(DB_PROVIDER)];
    	$this->_dbName = DB_NAME;
    	//echo "Provider en DbProvider: ".$this->_dbProviderMain."<BR>";

    	$this->_tableName = strtolower(get_called_class());
    	//echo $this->_tableName;
    	
    	switch($this->_dbProviderMainValue) {
    		case $this->dbs['MYSQL']:
    			//echo "carga MYSQL<BR>";
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

    function find($q) {
    	return $this->connector->find($this, $this->_tableName, $q);
    }

    function findById($q) {}

    function findAll() {
    	return $this->connector->findAll($this, $this->_tableName);
    }

    function findFirst($order="ASC") {
    	$this->connector->findOne($this, $this->_tableName, $order);
    }

    function findLast($order="DESC") {
    	$this->connector->findOne($this, $this->_tableName, $order);
    }

    function save() {
    	$this->connector->save($this, $this->_tableName);
    }

    function update() {
    	$this->connector->save($this, $this->_tableName);
    }

    function delete() {
    	$this->connector->delete($this, $this->_tableName);
    }

    function __destruct() {
    	$this->connector->close();
    }

}
?>