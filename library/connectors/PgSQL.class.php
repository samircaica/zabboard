<?php

class PgSQL {
	protected $conn;
    protected $database;
    protected $pk = 'id';

    protected $pg_to_php = array(
	    'bit' => 'bit',
	    'boolean' => 'bool',
	    'box' => 'box',
	    'character' => 'bpchar',
	    'char' => 'bpchar',
	    'bytea' => 'bytea',
	    'cidr' => 'cidr',
	    'circle' => 'circle',
	    'date' => 'date',
	    'daterange' => 'daterange',
	    'real' => 'float4',
	    'double precision' => 'float8',
	    'inet' => 'inet',
	    'smallint' => 'int',
	    'smallserial' => 'int',
	    'integer' => 'int',
	    'serial' => 'int',
	    'int4range' => 'int',
	    'bigint' => 'int',
	    'bigserial' => 'int',
	    'int8range' => 'int',
	    'interval' => 'int',
	    'json' => 'json',
	    'lseg' => 'lseg',
	    'macaddr' => 'macaddr',
	    'money' => 'money',
	    'decimal' => 'numeric',
	    'numeric' => 'numeric',
	    'numrange' => 'numrange',
	    'path' => 'path',
	    'point' => 'point',
	    'polygon' => 'polygon',
	    'text' => 'text',
	    'time' => 'time',
	    'time without time zone' => 'time',
	    'timestamp' => 'timestamp',
	    'timestamp without time zone' => 'timestamp',
	    'timestamp with time zone' => 'timestamptz',
	    'time with time zone' => 'timetz',
	    'tsquery' => 'tsquery',
	    'tsrange' => 'tsrange',
	    'tstzrange' => 'tstzrange',
	    'tsvector' => 'tsvector',
	    'uuid' => 'uuid',
	    'bit varying' => 'varbit',
	    'character varying' => 'varchar',
	    'varchar' => 'varchar',
	    'xml' => 'xml'
	);

    function __construct() {
    	/************************
    	Separar la conexion del constructor
    	**************************
    	// Connect to the database using pgsql
    	$this->database = DB_NAME;

		$this->conn = @pg_pconnect("host=".DB_HOST." port=".DB_PORT." user=".DB_USER." password=".DB_PASSWORD."dbname=".$this->database);
		if(!$this->conn) {
        	die('<BR>Message : Unable to connect to the database.<BR>');
    	}
    	*/
    	/*
    	$this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);

    	if($this->conn->connect_error)
  			die(sprintf('Unable to connect to the database. %s', $this->conn->connect_error));
  		*/
  		

  		//$this->conn->select_db($this->database);
  		/*
  		$sql = sprintf("SELECT * FROM `%s`.`%s` ;", $this->database, "tabla1");
        $result = $this->getConnection()->query($sql);
        print_r($result);
        echo "</BR>";
        */
    }

    function connect() {
    	// Connect to the database using pgsql
    	$this->database = DB_NAME;

		$this->conn = @pg_pconnect("host=".DB_HOST." port=".DB_PORT." user=".DB_USER." password=".DB_PASSWORD."dbname=".$this->database);
		if(!$this->conn) {
        	die('<BR>Message : Unable to connect to the database.<BR>');
    	}
    }

    function save($obj, $tableName) {
    	$array = $columNames = $values = array();

    	$reflection = new ReflectionObject($obj);
    	foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) AS $key => $value) {
    		$key = $value->getName();
            $value = $value->getValue($obj);
            $array[$key] = $value;
        }
    	
    	if(empty($array['id'])) {
    		unset($array['id']);
    		$count = 1;
    		foreach ($array AS $key => $value) {
    			$columNames[] = sprintf('%s', $key);
    			$marks[] = '$'.$count;
    			$values[] = &$array[$key];
		    	$count++;
    		}

    		try {
	    		$sql = sprintf("INSERT INTO %s (%s) VALUES (%s) RETURNING id",  $tableName, implode(', ', $columNames), implode(', ', $marks));
	    		//print_r(array($values));

	    		$stmt = pg_prepare($this->conn, '', $sql);
	    		
		        if(!$stmt) {
		            throw new Exception(pg_last_error($this->conn)."\n\n".$sql);
		        }
		        
		        $result = pg_execute($this->conn, '', $values);
		        $id = pg_fetch_row($result);
		        
		        if(pg_result_error($result)) {
	            	throw new Exception(pg_last_error($this->conn)."\n\n".$sql);
		        }

		        $obj->id = $id[0];		        
		        pg_free_result($result);

		    } catch(Exception $e) {    
              echo "<BR>Message : " . $e->getMessage();
              $error_message = $e->getMessage();
              //echo "Code : " . $e->getCode();
              echo "<BR>";
        	}
    	} else {
    		$id = (int)$array['id'];
    		$q['id'] = $id;
    		
    		$ret = $this->find($this, $tableName, $q);

    		try {
    			if(!empty($ret)) {
    				unset($array['id']);
		    		$count = 1;
		    		foreach ($array AS $key => $value) {
		    			$columNames[] = sprintf('%s = $'.$count, $key);
			            $marks[] = '$'.$count;
			            $values[] = &$array[$key];
				    	$count++;
		    		}
		    		try {
		    			$sql = sprintf("UPDATE %s SET %s WHERE id = %s", $tableName, implode(', ', $columNames), $id);
		    			$stmt = pg_prepare($this->conn, '', $sql);
	    		
				        if(!$stmt) {
				            throw new Exception(pg_last_error($this->conn)."\n\n".$sql);
				        }

				        $result = pg_execute($this->conn, '', $values);
		        
				        if(pg_result_error($result)) {
			            	throw new Exception(pg_last_error($this->conn)."\n\n".$sql);
				        }

		    		} catch(Exception $e) {
		    			echo "<BR>Message : " . $e->getMessage();
			            $error_message = $e->getMessage();
			            echo "<BR>";
			        }
    			} else {
    				throw new Exception("The data to update does not exist in database.");
    			}
    		} catch(Exception $e) {
    			echo "<BR>Message : " . $e->getMessage();
	            $error_message = $e->getMessage();
	            echo "<BR>";
	        }

    	}
    }

    function find($obj, $tableName, $q) {
    	$query = '';
    	$colTypes = $this->getColumTypes($tableName);

    	foreach ($q AS $key => $value) {
    		if(is_int($value)) {
    			$val = $value;
    		} else {
    			$val = "'".$value."'";
    		}
    		if($key == "ORDER BY") {
    			$query .= $key." ".$value; 
    		} else {
	    		$array[$key] = $val;
	    		$operator = (strpos($value, '%') === false) ? '=' : 'LIKE';
	    		$query .= " ".$key." ".$operator." ".$val;
	    	}
    	}

    	$sql = sprintf("SELECT * FROM %s WHERE %s", $tableName, $query);
    	$ret = array();

    	$result = pg_query($this->conn, $sql);

    	$className = get_class($obj);
		$object = new $className();
		$ret = array();

    	while($row = pg_fetch_assoc($result)) {
    		$std = new $className();
    		foreach($row as $key => $value) {
    			if($this->pg_to_php[$colTypes[$key]] == 'int') {
    				$value = (int)$value;
				}
    			$std->$key = $value;
    		}
    		$ret[] = $std;
    	}
    	pg_free_result($result);

    	return $ret;
    }

    function findAll($obj, $tableName, $order) {
    	$colTypes = $this->getColumTypes($tableName);
    	$sql = sprintf("SELECT * FROM %s ORDER BY id %s", $tableName, $order);

    	$result = pg_query($this->conn, $sql);

    	$className = get_class($obj);
		$object = new $className();
		$ret = array();

    	while($row = pg_fetch_assoc($result)) {
    		$std = new $className();
    		foreach($row as $key => $value) {
    			if($this->pg_to_php[$colTypes[$key]] == 'int') {
    				$value = (int)$value;
				}
    			$std->$key = $value;
    		}
    		$ret[] = $std;
    	}
    	pg_free_result($result);

    	return $ret;
    }

    function findOne($obj, $tableName, $order) {
    	$colTypes = $this->getColumTypes($tableName);

    	$sql = sprintf("SELECT * FROM %s ORDER BY id %s", $tableName, $order);
    	$sql .= ' LIMIT 1';

    	$result = pg_query($this->conn, $sql);

    	$row = pg_fetch_assoc($result);
    	if(!empty($row)) {
    		foreach($row as $key => $value) {
	    		//echo $type = pg_field_type($result, 0);
	    		if($this->pg_to_php[$colTypes[$key]] == 'int') {
	    			$value = (int)$value;
				}
				$obj->$key = $value;
	    	}
	    }
    	pg_free_result($result);
    }

    private function getColumTypes($tableName) {
    	$sql = sprintf("select column_name, data_type from information_schema.columns where table_name = '%s'", $tableName);
    	
    	$result = pg_query($this->conn, $sql);
    	$colTypes = array();
    	for ($row = 0; $row < pg_numrows($result); $row++) {
    		$values = pg_fetch_row($result, $row);
    		$colTypes[$values[0]] = $values[1];
    	}
    	pg_free_result($result);
    	return $colTypes;
    }

    function close() {
    	echo "Cerrando coneccion<BR>";
    	pg_close($this->conn);
    }
}

?>