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
?>
<BR>
```