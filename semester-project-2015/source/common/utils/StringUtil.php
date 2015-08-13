<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/1/2015
 * Time: 7:00 PM
 */

namespace source\common\utils;


use source\common\InternalErrorException;
use source\common\BaseObject;

class StringUtil extends BaseObject
{

    /**
     * Validates if the given mainString starts with the given partialString.
     * @param string $mainString the string to be compared
     * @param string $partialString the string to compare against mainString
     * @param bool|false $caseSensitive true if case sensitivity needs to be enforced
     * @return bool true if the mainString starts with the partialString, false otherwise
     * @throws InternalErrorException if the mainString is null
     */
    public static function startWith($mainString, $partialString, $caseSensitive = false)
    {
        return StringUtil::compare($mainString, $partialString, 0, $caseSensitive);
    }

    /**
     * Validates if the given mainString ends with the given partialString.
     * @param string $mainString the string to be compared
     * @param string $partialString the string to compare against mainString
     * @param bool|false $caseSensitive true if case sensitivity needs to be enforced
     * @return bool true if the mainString ends with the partialString, false otherwise
     * @throws InternalErrorException if the mainString is null
     */
    public static function endsWith($mainString, $partialString, $caseSensitive = false)
    {
        return StringUtil::compare($mainString, $partialString, -strlen($partialString), $caseSensitive);
    }

    /**
     * Compares the two strings from teh given offset.
     * @param $mainString the string to be compared
     * @param $partialString the partial string to compare against mainString
     * @param int $offset the offset to start search from. The absolute value needs to be less or equal to mainString length
     * @param bool|false $caseSensitive true if case sensitivity needs to be enforced
     * @return bool true if the mainString contains the partial string false otherwise
     * @throws InternalErrorException if the mainString is null or the offset overflows the mainString length
     */
    public static function compare($mainString, $partialString, $offset = 0, $caseSensitive = false)
    {
        // Cannot compare null string
        if (!isset($mainString)) {
            throw new InternalErrorException("Main string must not be null");
        }
        if ((strlen($mainString) < strlen($partialString)) || ($partialString == null)) {
            return false;
        }
        // Check for valid offset
        if ((strlen($mainString) < abs($offset))) {
            throw new InternalErrorException("Offset overflows mainString");
        }
        return substr_compare($mainString, $partialString, $offset, strlen($mainString), !$caseSensitive) == 0;
    }
}