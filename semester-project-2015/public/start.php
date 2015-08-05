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
use \source\common\InternalErrorException;

// register loaders
Autoloader::register();

$securityCtrl = SecurityController::getInstance();
// handle initial call with not logged user
if (!$securityCtrl->isUserLogged()) {
    throw new InternalErrorException("Start not available when user not logged");
}

$pool = PoolController::createFileSystemPool(TemplateController::$POOL_NAMESPACE, array("path" => ROOT_PATH . "/stash"));
$viewCtrl = new ViewController($pool);
echo $viewCtrl->handleRequest();



