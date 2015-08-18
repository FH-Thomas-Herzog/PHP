<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/18/2015
 * Time: 11:25 AM
 */

namespace source\db\model;


class MysqlTimestamp extends \DateTime
{
    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return parent::format("");
    }


}