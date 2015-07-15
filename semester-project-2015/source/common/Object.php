<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/15/2015
 * Time: 6:42 PM
 */

namespace SCM4\Common;

/**
 * This class is the base class for all other implemented classes.
 * It provides magic methods for handling illegal access to an inherit class
 * and provides a default string representation for all inherit class types.
 * Class Object
 * @package SCM4\Common
 */
abstract class Object
{
    /**
     * Magic function called when undefined function gets called.
     * @param $name the function name
     * @param $args the call arguments
     * @throws InternalErrorException to populate this error
     */
    public function __call($name, $args)
    {
        throw new InternalErrorException("Undefined function '" . $name . "(" . $args . ")'called", 2);
    }

    /**
     * Magic function called when undefined static function gets called.
     * @param $name the static function name
     * @param $args the static call arguments
     * @throws InternalErrorException to populate this error
     */
    public static function __callStatic($name, $args)
    {
        throw new InternalErrorException("Undefined static function '" . $name . "(" . $args . ")' called");
    }

    /**
     * Magic function called when undefined setter function gets called.
     * @param $name the setter function name
     * @param $value the setter call argument
     * @throws InternalErrorException to populate this error
     */
    public function __set($name, $value)
    {
        throw new InternalErrorException("Undefined setter '" . $name . "(" . $value . ")'called", 2);
    }

    /**
     * Magic function called when undefined getter function gets called.
     * @param $name the getter function name
     * @throws InternalErrorException to populate this error
     */
    public function __get($name)
    {
        throw new InternalErrorException("Undefined getter '" . $name . "()'called", 2);
    }

    /**
     * Default implementation for string representation of the actual class type.
     * @return string the default string representation
     */
    public function __toString()
    {
        return "No string representation defined (" . get_class($this) . ")";
    }
}