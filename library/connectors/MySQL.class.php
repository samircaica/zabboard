<?php

class MySQL {
	protected $conn;
    protected $database;
    protected $pk = 'id';

    protected $mysql_data_type_hash = array(
	    1=>'tinyint',
	    2=>'smallint',
	    3=>'int',
	    4=>'float',
	    5=>'double',
	    7=>'timestamp',
	    8=>'bigint',
	    9=>'mediumint',
	    10=>'date',
	    11=>'time',
	    12=>'datetime',
	    13=>'year',
	    16=>'bit',
	    //252 is currently mapped to all text and blob types (MySQL 5.0.51a)
	    253=>'varchar',
	    254=>'char',
	    246=>'decimal'
	);

    function __construct() {
    	// Connect to the database using mysqli
    	$this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);

    	if($this->conn->connect_error)
  			die(sprintf('Unable to connect to the database. %s', $this->conn->connect_error));

  		$this->database = DB_NAME;

  		$this->conn->select_db($this->database);
  		/*
  		$sql = sprintf("SELECT * FROM `%s`.`%s` ;", $this->database, "tabla1");
        $result = $this->getConnection()->query($sql);
        print_r($result);
        echo "</BR>";
        */
    }

    public function getConnection () {
        return $this->conn;
    }

    function save($obj, $tableName) {
    	//echo "En Save MySQL.class()</BR>";
    	$array = $columNames = $values = array();

    	$reflection = new ReflectionObject($obj);
    	foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) AS $key => $value) {
    		$key = $value->getName();
            $value = $value->getValue($obj);
            $array[$key] = $value;
            //echo "- Valores definidos -> column: ".$key." value: ".$value."<BR>";
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
    		//echo implode(',', $columNames);
    		//unset($values[0]);
    		//echo implode(',', $values);
    		//echo implode($types);
    		//echo "</BR>";
    		try {
	    		$sql = sprintf("INSERT INTO `%s`.`%s` (%s) VALUES (%s)", $this->database, $tableName, implode(', ', $columNames), implode(', ', $marks));
	    		//$arrayPrepStm = array_merge(array(implode($types)), $values);
	    		//print_r($arrayPrepStm);
	    		$stmt = $this->conn->prepare($sql);

		        if(!$stmt) {
		            throw new Exception($this->conn->error."\n\n".$sql);
		        }
		        call_user_func_array(array($stmt, 'bind_param'), array_merge(array(implode($types)), $values));
		        $stmt->execute();
		        
		        if($stmt->error) {
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
    		//echo "Hacer update para id: ".$array['id']."</BR>";
    		$id = (int)$array['id'];
    		$q['id'] = $id;
    		
    		$ret = $this->find($this, $tableName, $q);
    		
    		try {
    			if(!empty($ret)) {
	    			/*
		    		$count = 0;
		    		$q = array();
		    		foreach ($array AS $key => $value) {
		    			$prep = '';
		    			if($count != 0)
		    				$prep = " AND ";
		    			
		    			$q[$prep." ".$key] = $value;
		    			//$q[$prep." ".$key] = 1;
		    			$count++;
		    			//echo $query."<BR>";
		    		}
		    		*/
		    		unset($array['id']);
		    		foreach ($array AS $key => $value) {
		    			$columNames[] = sprintf('`%s` = ?', $key);
			            $values[] = &$array[$key];
			            $types[] = $this->setType($value);

		    		}
		    		try {
		    			$sql = sprintf("UPDATE `%s`.`%s` SET %s WHERE `id` = %s", $this->database, $tableName, implode(', ', $columNames), $id);
		    			$stmt = $this->conn->prepare($sql);

				        if(!$stmt) {
				            throw new Exception($this->conn->error."\n\n".$sql);
				        }
				        call_user_func_array(array($stmt, 'bind_param'), array_merge(array(implode($types)), $values));
				        $stmt->execute();
				        
				        if($stmt->error) {
			            	throw new Exception($stmt->error."\n\n".$sql);
				        }
		    		} catch(Exception $e) {
		    			echo "<BR>Message : " . $e->getMessage();
			            $error_message = $e->getMessage();
			            echo "<BR>";
			        }
	    		} else {
	    			throw new Exception("The data to update does not exist in database.");
	    		}
	    	}catch(Exception $e) {
    			echo "<BR>Message : " . $e->getMessage();
	            $error_message = $e->getMessage();
	            echo "<BR>";
	        }

    	}
    }

    function find($obj, $tableName, $q) {
    	//print_r($q);
    	$query = '';
    	foreach ($q AS $key => $value) {
    		if(is_int($value)) {
    			$val = $value;
    		} else {
    			$val = "'".$value."'";
    		}
    		$array[$key] = $val;
    		$operator = (strpos($value, '%') === false) ? '=' : 'LIKE';
    		$query .= " ".$key." ".$operator." ".$val;
    	}
    	//echo $query;
    	$sql = sprintf("SELECT * FROM %s.%s WHERE %s", $this->database, $tableName, $query);
    	
    	$ret = array();
    	$result = $this->conn->query($sql);

    	$className = get_class($obj);
		$object = new $className();
		
		while($row = $result->fetch_assoc()) {
    		//$std = new stdClass();
    		$std = new $className();
    		//print_r($row);
    		foreach($row as $key => $value) {
    			if($key == 'id') {
    				$value = (int)$value;
    			}
	    		$std->$key = $value;
	    	}
	    	//$ret[] = $this->objectToObject($std, $className);
	    	$ret[] = $std;
    	}
    	return $ret;
    }

    function findAll($obj, $tableName) {
    	$sql = sprintf("SELECT * FROM %s.%s", $this->database, $tableName);

    	$ret = array();
    	$result = $this->conn->query($sql);

    	$className = get_class($obj);
		$object = new $className();

    	while($row = $result->fetch_assoc()) {
    		$std = new $className();
    		//print_r($row);
    		foreach($row as $key => $value) {
	    		$std->$key = $value;
	    	}
	    	$ret[] = $std;
    	}
    	return $ret;
    }

    function findOne($obj, $tableName, $order) {
    	$sql = sprintf("SELECT * FROM %s.%s ORDER BY id %s", $this->database, $tableName, $order);
    	$sql .= ' LIMIT 0,1';

    	$result = $this->conn->query($sql);

    	$row = $result->fetch_assoc();
    	//print_r($row);
    	$count = 0;
    	foreach($row as $key => $value) {
    		$type = $result->fetch_field_direct($count)->type;
    		$dt_str   = $this->mysql_data_type_hash[$type];
    		//echo $dt_str." ";
    		if($dt_str == 'int') {
    			$obj->$key = (int)$value;
	    	} else if($dt_str == 'double') {
	            $obj->$key = (double)$value;
	    	} else {
	    		$obj->$key = $value;
	    	}
    		$count++; 
    	}
    }

    function delete($obj, $tableName) {
    	echo "En delete Mysql</BR>";
    	/*
    	$id = (int)$array['id'];
		$q['id'] = $id;
		
		$ret = $this->find($this, $tableName, $q);
		*/
		try {
			if(!empty($obj->id)) {
				$q = array('id' => $obj->id);
				$ret = $this->find($this, $tableName, $q);
				if(!empty($ret)) {
					$sql = sprintf("DELETE FROM `%s`.`%s` WHERE `id` = ?", $this->database, $tableName);
					$stmt = $this->conn->prepare($sql);

					if(!$stmt) {
						throw new Exception($this->conn->error."\n\n".$sql);
					}

					$stmt->bind_param('i', $obj->id);
					$stmt->execute();
			        if($stmt->error) {
			            throw new \Exception($stmt->error."\n\n".$sql);
			        }
				} else {
					throw new Exception("Object doesn't exist into database.");
				}

			} else {
				throw new Exception("Can not delete an object without id.");
			}

		} catch(Exception $e) {
			echo "<BR>Message : " . $e->getMessage();
            $error_message = $e->getMessage();
            echo "<BR>";
		}
		echo "-----> ".$obj->id;

    }

    private function setType($val) {
    	if(is_int($val)) {
            return 'i';
    	} else if(is_double($val)) {
            return 'd';
    	} else {
    		return 's';
    	}

    }

    function close() {
    	//echo "Cerrando coneccion<BR>";
    	$this->conn->close();
    }
}

?>