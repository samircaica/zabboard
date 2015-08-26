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
    	echo $this->_tableName;
    	
    	switch($this->_dbProviderMainValue) {
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

    function findById($q) {}

    function findAll() {
    	/*
    	$ret = array();
    	$ret[] = $this->connector->findAll($this, $this->_tableName);
    	return $ret;
    	*/
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
    	echo "En Save DbProvider()</BR>";
    	/*
    	$array = $columNames = $values = array();

    	$reflection = new ReflectionObject($this);
    	foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) AS $key => $value) {
    		$key = $value->getName();
            $value = $value->getValue($this);
            $array[$key] = $value;
            echo "- Valores definidos -> column: ".$key." value: ".$value."<BR>";
        }
    	//echo $columNames['id'];
    	if(empty($array['id'])) {
    		unset($array['id']);
    		foreach ($array AS $key => $value) {
    			$columNames[] = sprintf('`%s`', $key);
	            $marks[] = '?';
	            $values[] = $value;
	            $types[] = $this->setType($value);

    		}
    		//unset($columNames[array_search('id', $columNames)]);
    		echo implode(',', $columNames);
    		//unset($values[0]);
    		echo implode(',', $values);
    		echo implode($types);
    		echo "</BR>";
    		$sql = sprintf("INSERT INTO `%s`.`%s` (%s) VALUES (%s)", $this->_dbName, $this->_tableName, implode(', ', $columNames), implode(', ', $marks));
    		$arrayPrepStm = array_merge(array(implode($types)), $values);
    		print_r($arrayPrepStm);
    		$stmt = self::getConnection()->prepare($sql);
	        if (!$stmt) {
	            throw new Exception(self::getConnection()->error."\n\n".$sql);
	        }
	        call_user_func_array(array($stmt, 'bind_param'), array_merge(array(implode($types)), $values));
	        $stmt->execute();
    		echo $sql."<BR>";
    	} else {
    		echo "Hacer update</BR>";
    	}
    	*/
    	echo "</BR>";
    }

    function update() {}

    function delete() {}

    function __destruct() {
    	//echo "Close Conn en DbProvider</BR>";
    	$this->connector->close();
    }

}
?>