<?php

class Controller {
     
    protected $variables = array();
    protected $_model;
    protected $_controller;
    protected $_action;
    protected $_template;
    protected $_id;
    protected $_render;
    protected $_partial;
    public $renderHeader;
    public $params;
 
    function __construct($model, $controller, $action, $queryString) {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_model = $model;
        if($queryString) {
            $this->_id = $queryString[0];
        }

        $this->params = $_SESSION['params'];
        
        $this->renderHeader = true;
    }
 
    function set($name, $value) {
        $this->variables[$name] = $value;
    }

    /** Display Template **/
     
    function renderize($renderHeader = true) {
        extract($this->variables);
        $this->params = $_SESSION['params'];

        if($renderHeader == true) {

            /*
                Render header if exist
            */
            try { 
                if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.phtml')) {
                    include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.phtml');
                } else if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . 'header.phtml')) {
                    include (ROOT . DS . 'application' . DS . 'views' . DS . 'header.phtml');
                } else {
                    throw new Exception ('View header.phtml or '. $this->_controller . DS . 'header.phtml doesn\'t exist');
                }
            } catch(Exception $e) {    
                  echo "Message : " . $e->getMessage();
                  //echo "Code : " . $e->getCode();
                  echo "<BR>";
            }
        }

        /*
            Render view if exist
        */
        try {
            if(file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.phtml')) {
                include(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.phtml');
            } else {
                throw new Exception ('View '.$this->_controller . DS . $this->_action.' doesn\'t exist');
            }
        } catch(Exception $e) {    
              echo "Message : " . $e->getMessage();
              $this->set('error_message',$e->getMessage());
              //echo "Code : " . $e->getCode();
              echo "<BR>";
        }
        
        /*
            Render footer if exist
        */
        try {
            if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.phtml')) {
                include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.phtml');
            } else if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . 'footer.phtml')) {
                include (ROOT . DS . 'application' . DS . 'views' . DS . 'footer.phtml');
            } else {
                throw new Exception('View views/footer.phtml or '. $this->_controller . DS . 'footer.phtml doesn\'t exist');
            }
        } catch(Exception $e) {    
              echo "<BR>Message : " . $e->getMessage();
              $error_message = $e->getMessage();
              //echo "Code : " . $e->getCode();
              echo "<BR>";
        }
    }

    function render($text=null){
        $this->_render = $text;
    }

    function render_partial($text=null){
        $this->_partial = $text;
        extract($this->variables);
        try {
            if(file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS ."_". $this->_partial . '.phtml')) {
                include(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS ."_". $this->_partial . '.phtml');
            } else {
                throw new Exception('View '.$this->_controller . DS ."_". $this->_partial.' doesn\'t exist');
            }
        } catch(Exception $e) {    
              echo "Message : " . $e->getMessage();
              $this->set('error_message',$e->getMessage());
              //echo "Code : " . $e->getCode();
              echo "<BR>";
        }
    }

    function renderPartial($text=null) {
        extract($this->variables);
        $this->params = $_SESSION['params'];

        try {
            if(file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS ."_". $text . '.phtml')) {
                include(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS ."_". $text . '.phtml');
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

    function redirect_to($url) {
        $newUrl = BASE_URL."/".$url;
        header("Location: ".$newUrl);
    }

    function __destruct() {
        $_SESSION['params'] = $this->params;

        if(!empty($this->_render)) {
            $this->renderPartial($this->_render);
        } else {
            $this->renderize($this->renderHeader);
        }
        $_SESSION['params'] = $this->params;
    }
         
}
?>