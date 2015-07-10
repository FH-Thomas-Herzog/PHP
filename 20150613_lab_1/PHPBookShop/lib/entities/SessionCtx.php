<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/4/2015
 * Time: 10:06 AM
 */

class SessionCtx extends BaseObject {

    // static within request not whole application lifecycle
    // php gets reinterpreted each access therefore no static context possible aat all.
    private static $exists = false;

    public static function create() {
        if(!self::$exists) {
            self:: $exists = session_start();
        }
        return self::$exists;
    }
}