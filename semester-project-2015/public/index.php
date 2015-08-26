<?php
/**
 * This php file is the main access point for not logged users. (login and registration).
 * No direct access to resources are allowed therefore this php file was introduced to controll acces
 * to the available resources
 */

// Start and session if not already set.
// This is just an http session and does not mean that a user has a valid user session
if (!isset($_SESSION)) {
    session_start();
}

define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
// Import common resources which do not follow psr-4 namespace specification
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

// register the autoloaders
Autoloader::register();

// Security and session controller unique instances over an http request
$securityCtrl = SecurityController::getInstance();
$sessionCtrl = SessionController::getInstance();
$clearCache = false;

// handle initial call with not logged user
// TODO: redirect via start.php causes an submit of start.php form and not login form on the first
// submit after redirect. Need to fix this
if (!$securityCtrl->isUserLogged()) {
    // in case of an get request
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $clearCache = (!empty($_GET['clearCache'])) ? true : false;
        if (!isset($_GET['viewId'])) {
            $_GET['viewId'] = ViewController::$VIEW_LOGIN;
            $_GET['actionId'] = ViewController::$REFRESH_ACTION;
        }
    } // in case of an post request
    else if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $clearCache = (!empty($_POST['clearCache'])) ? true : false;
        if (!isset($_POST['viewId'])) {
            $_POST['viewId'] = ViewController::$VIEW_LOGIN;
            $_POST['actionId'] = ViewController::$REFRESH_ACTION;
        }
    }

    // instantiate pool and connect to existing cache (could be empty)
    $pool = PoolController::createFileSystemPool(TemplateController::$POOL_NAMESPACE, array("path" => ROOT_PATH . "/stash"));
    // clear cache if intended
    if($clearCache) {
        $pool->flush();
    }
    // The view controller which controls access to the views and view bound actions
    $viewCtrl = new ViewController($pool);
    // handles the request by delegating to the proper controller depending on the current view.
    echo $viewCtrl->handleRequest();
} // redirect logged user to main page
else {
    header('Location: start.php');
    return "";
}




