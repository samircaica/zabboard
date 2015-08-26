# zabboard
Zabbix Board

##Installation

1.- Configure Apache with mod_rewrite

2.- Create virtualhost

3.- Edit config/config.php

4..- Create into application/controller a new Controller

```php
<?php
class TestController extends Controller {
	function index() {
		$this->redirect_to("test/main");
	}

	function main() {
		$this->set('title','Main Page');
		$this->render_partial("main_alternative");
		$test = new Test();
		$test->name = "John";
		$test->name = "Doe";
		$test->save();
	}

	function other() {
		$this->set('title','Other Page');
		$this->render("other");
		$test = new Test();
		$ret = $test->findAll();
		foreach($ret as $objTest) {
			echo $objTest->name." ".$objTest->lastname;
		}
	}

	function another() {
		$test = new Test();
		//Search with AND operator and LIKE using % %
		$ret = $test->find(array('name' => '%John%', 'AND lastname' => 'Doe'));
		//Search with OR operator and =
		$ret = $test->find(array('name' => 'John', 'OR lastname' => 'Doe'));
		foreach($ret as $objTest) {
			$objTest->name." ".$objTest->lastname;
		}
	}
}
?>
```

5.- Create Model Test

```php
<?php
class Test extends Model {
	public $id;
	public $name;
	public $lastname;

}
?>
```

6.- Create directory View and inside create index.php view

```php
<?php
	echo "Test view from Test";
	<?=$this->render_partial("test_optional")?>
?>
<BR>
```