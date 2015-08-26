<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/1/2015
 * Time: 8:33 PM
 */

namespace source\common;

use source\common\BaseObject;

class Autoloader extends BaseObject
{

    /**
     * Register the spl autloader of the thrid party libraries and of this project
     * @param bool|false $prepend true if the loader shall be prepended
     */
    public static function register($prepend = false)
    {
        require_once(ROOT_PATH . '/source/library/twig/twig/lib/Twig/Autoloader.php');
        require_once(ROOT_PATH . '/source/library/tedivm/stash/autoload.php');
        \Twig_Autoloader::register(true);
        spl_autoload_register(array(__CLASS__, 'load'), true, $prepend);
    }

    /**
     * TRies to load the class
     * @param $classname the classname of the to load class
     */
    public static function load($classname)
    {
        require_once(ROOT_PATH . "/" . str_replace("\\", DIRECTORY_SEPARATOR, $classname) . ".php");
    }
}