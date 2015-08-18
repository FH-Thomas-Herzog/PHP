<?php
/**
 * This php file handles all of the request to resources for already logged users.
 * No direct access to available resources is allowed, therefore this php file was introduced.
 */
// Start an session if session not already set.
if (!isset($_SESSION)) {
    session_start();
}

// set server root path where the resources reside
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/php-semester-project');

// Import resources which do not follow psr-4 namespace specification
// and therefore cannot be autoloaded
require_once(ROOT_PATH . "/source/common/Objects.php");
require_once(ROOT_PATH . "/source/common/Exceptions.php");
require_once(ROOT_PATH . '/source/common/Autoloader.php');

// used resources in this php file
use \source\common\Autoloader;
use \Stash\Driver\FileSystem;
use \source\view\controller\TemplateController;
use \source\view\controller\ViewController;
use \source\view\controller\PoolController;
use \source\view\controller\SecurityController;
use \source\view\controller\SessionController;

// register loaders
Autoloader::register();

// singelton security and session controller unique over a single http request
$securityCtrl = SecurityController::getInstance();
$sessionCtrl = SessionController::getInstance();

// redirect not logged user back to login page
if (!$securityCtrl->isUserLogged()) {
    header('Location: index.php');
    return "";
}
// in case of redirect or refresh triggered by the logged user
else {
    // in case of get request
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        if (!isset($_GET['viewId'])) {
            $_GET['viewId'] = ViewController::$VIEW_MAIN;
            $_GET['actionId'] = ViewController::$REFRESH_ACTION;
        }
    }
    // in case of post request
    else if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (!isset($_POST['viewId'])) {
            $_POST['viewId'] = ViewController::$VIEW_MAIN;
            $_POST['actionId'] = ViewController::$REFRESH_ACTION;
        }
    }

    // instantiate pool and connect to cache (maybe empty)
    $pool = PoolController::createFileSystemPool(TemplateController::$POOL_NAMESPACE, array("path" => ROOT_PATH . "/stash"));

    // the view controller which controls the access to views and view actions
    $viewCtrl = new ViewController($pool);
    // handles the current request
    echo $viewCtrl->handleRequest();
}




