<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/15/2015
 * Time: 7:34 PM
 */

namespace source\common\utils;

use\source\common\BaseObject;
use \source\common\CoreException;

class ObjectUtil extends BaseObject
{
    /**
     * Checks if the given variable is null or not.
     * @param $value the value to check if not null
     * @param BaseException|null $e the custom exception to throw
     * @throws BaseException the given custom exception if defined and value is null
     * @throws CoreException if no custom exception was given and value is null
     */
    public static function requireSet($value = null, $e = null)
    {
        if (!isset($value)) {
            if (isset($e)) {
                throw $e;
            } else {
                throw new CoreException("Given value is null", 10);
            }
        }
    }
}