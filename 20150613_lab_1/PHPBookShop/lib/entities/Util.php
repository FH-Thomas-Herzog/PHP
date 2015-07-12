<?php

/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/4/2015
 * Time: 8:43 AM
 */
class Util extends BaseObject
{

    public static function escape($string)
    {
        // nl2br -> newLine to Br
        // htmlentities -> escapes invalid characters to html representation
        return nl2br(htmlentities($string));
    }

    public static function action($action, $params = null)
    {
        $page = isset($_REQUEST['page']) && $_REQUEST['page'] ?
            $_REQUEST['page'] :
            $_SERVER['REQUEST_URI'];
        $return = 'index.php?action=' . rawurlencode($action) . '&page=' . rawurlencode($page);
        if (is_array($params)) {
            foreach ($params as $name => $value) {
                $return .= '&' . rawurlencode($name) . '=' . rawurlencode($value);
            }

        }

        return $return;
    }

    public static function redirect($page = null)
    {
        if ($page == null) {
            $page = $_REQUEST['page'];
        }
        header("Location:" . $page);
    }
}