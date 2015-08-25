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

    function close() {
    	echo "Cerrando coneccion<BR>";
    	$this->conn->close();
    }
}

?>