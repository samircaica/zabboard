<?php

class Model extends DbProvider {
	//protected $_dbProvider;
    protected $_model;
 
    function __construct() {
    	parent::__construct();
    	//echo $this->_dbProvider;

        //$this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        //$this->_model = get_class($this);
        //$this->_table = strtolower($this->_model)."s";
    }

    function getProvider() {
    	//$this->_dbProvider = $this->getProvider();
    	echo "getProvider en Model() ".$this->_dbProviderMain."</BR>";
    }
 
    
}
?>