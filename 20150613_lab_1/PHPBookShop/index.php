<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 6/13/2015
 * Time: 9:54 AM
 */
require_once('include/bootstrap.php');

// get 'view' parameter from request
$view = isset($_REQUEST['view']) ? $_REQUEST['view'] : 'welcome';
// get 'action' parameter from request
$postAction = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;

if ($postAction != null) {
    Controller::getInstance()->invokePostAction();
}

// '__DIR__' points always to parent directory
// check if requested view exists and require it if yes.
if (file_exists(__DIR__ . '/views/' . $view . '.php')) {
    require(__DIR__ . '/views/' . $view . '.php');
}

SessionCtx::create();

//$cat = new Category(1, 'hello');
//print_r(DataManager::getBooksForCategory(3));
//$cat_list = DataManager::getCategories();

// prints the dump of an array
// var_dump($cat_list);

