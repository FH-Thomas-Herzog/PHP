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
use \source\db\controller\UserController;

// register loaders
Autoloader::register();

$pool = PoolController::createFileSystemPool(TemplateController::$POOL_NAMESPACE, array("path" => ROOT_PATH . "/stash"));
$viewCtrl = new ViewController($pool);
$viewCtrl->handleRequest();



