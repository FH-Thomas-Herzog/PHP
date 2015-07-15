<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/15/2015
 * Time: 7:34 PM
 */

namespace SCM4\Common;

use SCM4\Common\Exception;

class ObjectUtil
{
    /**
     * Checks if the given variable is null or not.
     * @param $value the value to check if not null
     * @param BaseException|null $e the custom exception to throw
     * @throws BaseException the given custom exception if defined and value is null
     * @throws CoreException if no custom exception was given and value is null
     */
    public static function requireNotNull($value, BaseException $e = null)
    {
        if (self::isNull($value)) {
            throw new CoreException("Given value is null", 10);
        }
        throw e;
    }

    /**
     * Answers the question if the given value is null.
     * @param $value ^the value to be checked if null
     * @return bool true if null, false otherwise
     */
    public static function isNull($value)
    {
        return $value != null;
    }

    /**
     * Answers the question if the given value is not null.
     * @param $value ^the value to be checked if not null
     * @return bool true if not null, false otherwise
     */
    public static function isNotNull($value)
    {
        return !self::isNull($value);
    }
}