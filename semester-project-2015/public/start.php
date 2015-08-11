<?php
if (!isset($_SESSION)) {
    session_start();
}

// set server root path
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/php-semester-project');

// Import common resources used by all classes
require_once(ROOT_PATH . "/source/common/Objects.php");
require_once(ROOT_PATH . '/source/common/AbstractRequestController.php');
require_once(ROOT_PATH . "/source/common/Exceptions.php");
require_once(ROOT_PATH . '/source/common/Autoloader.php');

use \source\common\Autoloader;
use \Stash\Driver\FileSystem;
use \source\view\controller\TemplateController;
use \source\view\controller\ViewController;
use \source\view\controller\PoolController;
use \source\view\controller\SecurityController;
use \source\view\controller\LoginRequestController;
use \source\view\controller\SessionController;

// register loaders
Autoloader::register();

$securityCtrl = SecurityController::getInstance();
$sessionCtrl = SessionController::getInstance();

// handle initial call with not logged user
if (!$securityCtrl->isUserLogged()) {
    header('Location: index.php');
    return "";
} else {
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        if (!isset($_GET['viewId'])) {
            $_GET['viewId'] = ViewController::$VIEW_MAIN;
            $_GET['actionId'] = ViewController::$REFRESH_ACTION;
        }
    } else if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (!isset($_POST['viewId'])) {
            $_POST['viewId'] = ViewController::$VIEW_MAIN;
            $_POST['actionId'] = ViewController::$REFRESH_ACTION;
        }
    }
    $pool = PoolController::createFileSystemPool(TemplateController::$POOL_NAMESPACE, array("path" => ROOT_PATH . "/stash"));
    $viewCtrl = new ViewController($pool);
    echo $viewCtrl->handleRequest();
}




