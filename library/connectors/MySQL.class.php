<?php

class MySQL {
	protected $conn;
    protected $database;
    protected $pk = 'id';

    function __construct() {
    	// Connect to the database using mysqli
    	$this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);

    	if ($this->conn->connect_error)
  			die(sprintf('Unable to connect to the database. %s', $this->conn->connect_error));

  		$this->database = DB_NAME;

  		$this->conn->select_db($this->database);

  		$sql = sprintf("SELECT * FROM `%s`.`%s` ;", $this->database, "tabla1");
        $result = $this->getConnection()->query($sql);
        print_r($result);
    }

    public function getConnection () {
        return $this->conn;
    }

    function save($obj, $tableName) {
    	echo "En Save MySQL.class()</BR>";

    	$array = $columNames = $values = array();

    	$reflection = new ReflectionObject($obj);
    	foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) AS $key => $value) {
    		$key = $value->getName();
            $value = $value->getValue($obj);
            $array[$key] = $value;
            echo "- Valores definidos -> column: ".$key." value: ".$value."<BR>";
        }
    	//echo $columNames['id'];
    	if(empty($array['id'])) {
    		unset($array['id']);
    		foreach ($array AS $key => $value) {
    			$columNames[] = sprintf('`%s`', $key);
	            $marks[] = '?';
	            $values[] = &$array[$key];
	            $types[] = $this->setType($value);

    		}
    		//unset($columNames[array_search('id', $columNames)]);
    		echo implode(',', $columNames);
    		//unset($values[0]);
    		echo implode(',', $values);
    		echo implode($types);
    		echo "</BR>";
    		try {
	    		$sql = sprintf("INSERT INTO `%s`.`%s` (%s) VALUES (%s)", $this->database, $tableName, implode(', ', $columNames), implode(', ', $marks));
	    		//$arrayPrepStm = array_merge(array(implode($types)), $values);
	    		//print_r($arrayPrepStm);
	    		$stmt = $this->conn->prepare($sql);

		        if (!$stmt) {
		            throw new Exception($this->conn->error."\n\n".$sql);
		        }
		        call_user_func_array(array($stmt, 'bind_param'), array_merge(array(implode($types)), $values));
		        $stmt->execute();
		        
		        if ($stmt->error) {
	            	throw new Exception($stmt->error."\n\n".$sql);
		        }
		        $obj->id = $stmt->insert_id;
		    } catch(Exception $e) {    
              echo "<BR>Message : " . $e->getMessage();
              $error_message = $e->getMessage();
              //echo "Code : " . $e->getCode();
              echo "<BR>";
        	}
    		
    	} else {
    		echo "Hacer update</BR>";
    	}
    	echo "</BR>";
    }

    private function setType($val) {
    	if (is_int($val)) {
            return 'i';
    	} else if (is_double($val)) {
            return 'd';
    	} else {
    		return 's';
    	}

    }

    function close() {
    	echo "Cerrando coneccion<BR>";
    	$this->conn->close();
    }
}

?>