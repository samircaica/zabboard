<?php

class Controller {
     
    protected $variables = array();
    protected $_model;
    protected $_controller;
    protected $_action;
    protected $_template;
    protected $_id;
    protected $_partial;
    public $renderHeader;
    public $params;
 
    function __construct($model, $controller, $action, $queryString) {
        if(!isset($_SESSION)) {
            session_start();
        }
        //print_r($queryString);
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_model = $model;
        if($queryString) {
            $this->_id = $queryString[0];
        }

        $this->params = new stdClass();
        $this->params = $_SESSION['params'];
        
        try {
            $this->$model = new $model;
        } catch(Exception $e) {    
              echo "Message : " . $e->getMessage();
              //echo "Code : " . $e->getCode();
              echo "<BR>";
        }

        $this->renderHeader = true;
        //$this->_template = new Template($controller, $action, $this->_id);
 
    }
 
    function set($name, $value) {
        //$this->_template->set($name, $value);
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

    function render_partial($text=null){
        $this->_partial = $text;
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

    function __destruct() {
        //echo $this->renderHeader;
        if(!empty($this->_partial)) {
            $this->renderPartial($this->_partial);
        } else {
            //$this->_template->render($this->renderHeader);
            $this->render($this->renderHeader);
        }
        
        $_SESSION['params'] = $this->params;
    }
         
}
?>