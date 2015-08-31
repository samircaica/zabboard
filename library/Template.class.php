<?php
class Template {
     
    protected $variables = array();
    protected $_controller;
    protected $_action;
    protected $_id;
    public $params;
    
    function __construct($controller,$action, $id) {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_id = $id;

    }
 
    /** Set Variables **/
 
    function set($name,$value) {
        $this->variables[$name] = $value;
    }
 
    /** Display Template **/
     
    function render($renderHeader = true) {
        extract($this->variables);
        $this->params = $_SESSION['params'];

        if($renderHeader == true) {

        
            try { 
                if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php')) {
                    include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php');
                } else if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . 'header.php')) {
                    include (ROOT . DS . 'application' . DS . 'views' . DS . 'header.php');
                } else {
                    throw new Exception ('View header.php or '. $this->_controller . DS . 'header.php doesn\'t exist');
                }
            } catch(Exception $e) {    
                  echo "Message : " . $e->getMessage();
                  //echo "Code : " . $e->getCode();
                  echo "<BR>";
            }
        }

        try {
            if(file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php')) {
                include(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php');
            } else {
                throw new Exception ('View '.$this->_controller . DS . $this->_action.' doesn\'t exist');
            }
        } catch(Exception $e) {    
              echo "Message : " . $e->getMessage();
              $this->set('error_message',$e->getMessage());
              //echo "Code : " . $e->getCode();
              echo "<BR>";
        }
        
        try {
            if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php')) {
                include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php');
            } else if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . 'footer.php')) {
                include (ROOT . DS . 'application' . DS . 'views' . DS . 'footer.php');
            } else {
                throw new Exception ('View views/footer.php or '. $this->_controller . DS . 'footer.php doesn\'t exist');
            }
        } catch(Exception $e) {    
              echo "<BR>Message : " . $e->getMessage();
              $error_message = $e->getMessage();
              //echo "Code : " . $e->getCode();
              echo "<BR>";
        }
    }

    function renderPartial($text=null) {
        extract($this->variables);
        $this->params = $_SESSION['params'];
        try {
            if(file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS ."_". $text . '.php')) {
                include(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS ."_". $text . '.php');
            } else {
                throw new Exception ('View '.$this->_controller . DS ."_". $text.' doesn\'t exist');
            }
        } catch(Exception $e) {    
              echo "Message : " . $e->getMessage();
              $this->set('error_message',$e->getMessage());
              //echo "Code : " . $e->getCode();
              echo "<BR>";
        }
    }
 
}
?>