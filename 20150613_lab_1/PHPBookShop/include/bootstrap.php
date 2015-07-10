<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 6/13/2015
 * Time: 9:54 AM
 */

// Print the info of the php installation
// phpinfo();

// headers need to bet in the first place
//header('Location:http://orf.at'); // redirect to orf.at

// enables all error logging
error_reporting(E_ALL);
// enables display errors via ini_set. error_reporting could be set the same way.
ini_set('display_errors', 'ON');
// define the root path for referencing the resources
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/bookshop');

// register all class which are referenced from the source
spl_autoload_register(function ($class) {
    require_once(ROOT_PATH . '/lib/entities/' . $class . '.php');
});

SessionCtx::create();

// no wildcard inclusion supported therefore all files must be included manually
require_once(ROOT_PATH . '/lib/data-manager/DataManager_mock.php');





// php tags gets automatically closed on EOF