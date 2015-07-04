<?php

Admin::menu()->url('/')
	->label('Start page')->icon('fa-dashboard')
	->uses('Ankh\Http\Controllers\HomeController@getAdmin');

