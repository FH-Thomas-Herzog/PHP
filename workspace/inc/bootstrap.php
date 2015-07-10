<?php

error_reporting(E_ALL); ini_set('display_errors', 'On');

spl_autoload_register(function($class) {
	require_once 'lib/' . $class . '.php';
});

require_once 'lib/data/DataManager_mock.php';
