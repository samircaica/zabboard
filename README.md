# zabboard
Zabbix Board

#Installation

1.- Configure Apache with mod_rewrite

2.- Create virtualhost

3.- Edit config/config.php

4..- Create into application/controller a new Controller

<code>
	class TestController extends Controller {
		function index() {

		}
	}
</code>

5.- Create Model Test

<code>
	class Test extends Model {

	}
</code>

6.- Create directory View and inside create index.php view

<code>
	<?php
		echo "Test view from Test";
	?>
	<BR>
</code>