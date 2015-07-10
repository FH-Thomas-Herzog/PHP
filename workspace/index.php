<?php
require_once('inc/bootstrap.php');

$categories_list = DataManager::getCategories();
print_r(DataManager::getBooksForCategory(3));

//print '<textarea style="width: 100%; height: 200px;">';
//var_dump($categories_list);
//print '</textarea>';