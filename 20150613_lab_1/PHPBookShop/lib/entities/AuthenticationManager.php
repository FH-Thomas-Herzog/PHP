<?php

/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/4/2015
 * Time: 11:09 AM
 */
class AuthenticationManager extends BaseObject
{
    public static function authenticate($userName, $password)
    {
        $user = DataManager::getUserForUserName($userName);
        if (
            $user != null &&
            $user->getPasswordHash() == hash('sha1',
                $userName . '|' . $password
            )
        ) {
            $_SESSION['user'] = $user->getId();
            return true;
        }
        self::signOut();
        return false;
    }

    public static function signOut()
    {
        unset($_SESSION['user']);
        //unset($_SESSION['cart']);
    }

    public static function getAuthenticatedUser()
    {
        self::isAuthenticated() ? DataManager::getUserForId($_SESSION["user"]) : null;
    }

    public static function isAuthenticated()
    {
        return isset($_SESSION["user"]);
    }
}