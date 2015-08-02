<?php

// set server root path
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/php-semester-project');

// Import common resources used by all classes
require_once(ROOT_PATH . "/source/common/Objects.php");
require_once(ROOT_PATH . "/source/common/Exceptions.php");
require_once(ROOT_PATH . '/source/common/Autoloader.php');

use \source\common\Autoloader;
use source\view\controller\ViewController;

// register loaders
Autoloader::register();

$viewController = new ViewController(ROOT_PATH . "/source/view/templates", array(
    'cache' => ROOT_PATH . '/cache/templates',
    'debug' => true,
    'auto_reload ' => true, // autoload of templates for development puspose only
    'strict_variables' => false, // avoid undefined variables
    'autoescape' => true // escape html
));

echo $viewController->renderView(ViewController::$VIEW_LOGIN, array("actionValue" => ViewController::$VIEW_START));