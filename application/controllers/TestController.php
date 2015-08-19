<?php
//session_start();
require_once '../libs/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;

class TestController extends Controller {

	private $title = 'Test Page';
	private $api = array();
	private $authorized = false;
	
	
	function index() {
		$this->set('title',$this->title);
		//$this->renderHeader = false;
		$this->params->authorized = $this->authorized;
		$this->set("authorized", $this->authorized);
	}

	function login() {
		$this->set('title', "Add page");
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$usuario = $_POST['usuario'];
			$password = $_POST['password'];
			$this->set('message', "");

			try {
				//$api = new ZabbixApi('http://192.168.1.43:3080/zabbix/api_jsonrpc.php', $usuario, $password);
				
				/*
				$this->api = new ZabbixApi();
				$this->api->setApiUrl('http://192.168.1.43:3080/zabbix/api_jsonrpc.php');
				$this->api->userLogin(array('user' => $usuario, 'password' => $password));
				$this->set("dump", print_r($this->api));
				*/
				$this->params->Api = new ZabbixApi();
				$this->params->Api->setApiUrl('http://192.168.1.43:3080/zabbix/api_jsonrpc.php');
				$this->params->Api->userLogin(array('user' => $usuario, 'password' => $password));

				$hostGroups = $this->params->Api->hostgroupGet(array('real_hosts' => true));

				$this->set("hostGroups", $hostGroups);
    			echo "<BR>";
    			echo "----";
    			echo "<BR>";
    			//print_r($this->_params['Api']);
    			//$this->params->someProperty = 'hello';

				//store in session
				$authorized = true;

			} catch(Exception $e) {
				// Exception in ZabbixApi catched
    			//echo $e->getMessage();
    			$this->set('message', $e->getMessage());
    			$authorized = false;
			}
			$this->set("authorized", $authorized);
			$this->params->authorized = $authorized;

		}
		//$_SESSION['params'] = $this->params;
	}

	function logout() {
		try {
			//$this->params = $_SESSION['params'];

			//add something else, which will be stored in the session
			//$this->params->anotherPropery = 'Something';
			//echo $this->params->someProperty;
			//echo $this->params->anotherPropery;
			$this->set('title', "Logout");

			$this->params->Api->userLogout('', '');
			$this->params->authorized = false;
			$this->authorized = $this->params->authorized;
			$this->set("authorized", $this->authorized);
			//var_dump($this->params->Api);
		} catch(Exception $e) {
			echo $e->getMessage();
		}
		
	}

	function infraestructura() {
		$this->set('title', "Infraestructura");
		$this->authorized = $this->params->authorized;
		$this->set("authorized", $this->authorized);

		try {
			$groups = $this->params->Api->hostgroupGet(array('output' => 'extend'), '');
			$group_list = array();
			foreach($groups as $group) {
			    array_push($group_list, $group->groupid);
			}
			$hosts = $this->params->Api->hostGet(array('groupids' => $group_list, 'output' => array('hostid', 'host', 'name', 'description')), '');
			//$hosts = $this->params->Api->hostGet(array('groupids' => $group_list, 'output' => 'extend'), '');
	 
			$host_lists = array();
			foreach($hosts as $host) {
				echo $host->host;
			    $host_lists[$host->hostid] = $host->host;
			    //$host_lists[$host->hostid] = $host->name;
			    echo "<BR>";

			    
			}
			//$this->set("groups", $groups);
			$this->set("hosts", $hosts);
			$this->set("host_lists", $host_lists);
			$this->params->hosts = $hosts;
			$this->params->host_lists = $host_lists;
		}catch(Exception $e) {
			echo $e->getMessage();
		}
	}

	function triggers() {
		$this->set('title', "Infraestructura");
		$authorized = $this->params->authorized;
		$this->set("authorized", $authorized);

		echo $this->_id;
		$triggers = $this->params->Api->triggerGet(
	    array(
	        'output' => 'extend',
	        // Retorna somente os triggers dos hosts da lista.
	        'hostids' => $this->_id,
	        // Exibir somente triggers que são considerados problemas. 
	        'filter' => array('value' => 1),
	        // Ordenar do mais recente para o mais antigo e por prioridade.
	        'sortfield' => array('lastchange', 'priority'),
	        // Ordenar do mais recente para o mais antigo. 
	        'sortorder' => 'DESC',
	        // Ignorar triggres com problema que são dependentes de outros triggers.
	        'skipDependent' => true,
	        // Expandir macros na descrição do trigger.
	        'expandDescription' => true,
	        // Retornar apenas triggers que têm eventos não reconhecidos.
	        'withUnacknowledgedEvents' => true,
	        // Retornar apenas triggers habilitados que pertencem a hosts monitorados e conter apenas os itens habilitados.
	        'monitored' => true
	    ),
	    ''
	);
		$this->set("triggers", $triggers);
		$this->set("this", $this);
		$this->render_partial("asd");
		//print_r($triggers);
	}

	function details() {
		try {
			$this->set('title', "Detalle");
			$authorized = $this->params->authorized;
			$this->set("authorized", $authorized);

			$graphs = $this->params->Api->graphGet(array(
	        	'output' => 'extend',
	        	'hostids' => $this->_id,
	        	'sortfield' => array('name')
	    	));

	    	if(!empty($_GET['period'])) {
	    		$period = $_GET['period'];
	    		$this->set('period', $period);
	    	} else {
	    		$this->set('period', '43200');
	    	}
	    	//print_r($graphs);
			$this->set("graphs", $graphs);
			$this->set("id", $this->_id);
			//$this->prueba();
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}

	private function prueba() {
		$this->set('title', "Prueba");
		$this->render_partial("asd2");
	}

	function items() {
		try {
			$this->set('title', "Items");
			$authorized = $this->params->authorized;
			$this->set("authorized", $authorized);

			$items = $this->params->Api->itemGet(array(
	        	'output' => 'extend',
	        	'hostids' => $this->_id
	    	));

	    	//print_r($items);
			$this->set("items", $items);
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}

	function applications() {
		header("Refresh:10");
		try {
			$this->set('title', "Applications");
			$authorized = $this->params->authorized;
			$this->set("authorized", $authorized);

			$applications = $this->params->Api->applicationGet(array(
	        	'output' => 'extend',
	        	'hostids' => $this->_id,
	        	"sortfield" => "name"
	    	));

	    	$app_lists = array();
			foreach($applications as $application) {
				//$app_lists[$application->name] = $application->applicationid;
				$items = $this->params->Api->itemGet(array(
		        	'output' => 'extend',
		        	'hostids' => $this->_id,
		        	'applicationids' => $application->applicationid
		    	));
		    	foreach($items as $item) {
					if (preg_match('(\$1|\$2|\$3|\$4)',$item->name)) {
						preg_match_all("/\\[(.*?)\\]/", $item->key_, $matches);
						//echo $matches[1][0];
						//echo "<BR>";
						$array = explode(',', $matches[1][0]);
						//print_r($array);
						//echo "<BR>";
					}
				    if (preg_match('/\$1/',$item->name)) {
				    	$item->name = str_replace("$1", $array[0], $item->name);
				    }
				    if (preg_match('/\$2/',$item->name)) {
				    	$item->name = str_replace("$2", $array[1], $item->name);
				    }
				    if (preg_match('(\$3)',$item->name)) {
				    	$item->name = str_replace("$3", $array[2], $item->name);
				    }
				    if (preg_match('(\$4)',$item->name)) {
				    	$item->name = str_replace("$4", $array[3], $item->name);
				    }
				}
		    	$app_lists[$application->name] = $items;
			}

	    	//print_r($items);
			$this->set("app_lists", $app_lists);
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}

	function procesa() {

		echo "Procesa";
	}

}

?>