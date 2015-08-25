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
	}

	function other() {
		$this->set('title','Other Page');
		$this->render("other");
	}
}
?>
```

5.- Create Model Test

```php
<?php
class Test extends Model {

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