<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/15/2015
 * Time: 6:47 PM
 */

namespace SCM4\Common\Exception;

/**
 * This exception is the base exception of all used exceptions.
 * This class implements the '__toString()' function and provides a custom
 * string representation of the inherit exceptions.
 * Class BaseException
 * @package SCM4\Common\Exception
 */
abstract class BaseException extends \Exception
{
    /**
     * Custom representation of this message and its previous messages
     * @return string the string containing all information of this message and it previous exceptions
     */
    public function __toString()
    {
        return parent::getMessage() . " [" . parent::getCode() . "] " . PHP_EOL . (parent::getPrevious() != null) ? parent::__toString() : "";
    }

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
        throw new InternalErrorException('static method ' . $name . ' is not declared');
    }

    /**
     * Magic function called when undefined setter function gets called.
     * @param $name the setter function name
     * @param $args the setter call arguments
     * @throws InternalErrorException to populate this error
     */
    public function __set($name, $args)
    {
        throw new InternalErrorException("Undefined setter '" . $name . "(" . $args . ")'called", 2);
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
}

/**
 * This class represents an internal error which is supposed to be an
 * application critical error.
 * Class InternalErrorException
 * @package SCM4\Common\Exception
 */
final  class InternalErrorException extends BaseException
{
    public function __construct($message = "Unspecified Internal Error occurred", $code = 666, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * This class represents the CoreException which is supposed to be the root
 * exception of all non critical exceptions.
 * Class CoreException
 * @package SCM4\Common\Exception
 */
class CoreException extends BaseException
{
    public function __construct($message = "Unspecified Core Exception occurred", $code = 100, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * This class represents an security exception which shall be used to indicate
 * an security related error.
 * Class SecurityException
 * @package SCM4\Common\Exception
 */
class SecurityException extends BaseException
{

    public function __construct($message = "Unspecified SecurityException occurred", $code = 200, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * This class indicates that an entity could not be found.
 * It does not specify why the entity wasn't found.
 * Class EntityNotFoundException
 * @package SCM4\Common\Exception
 */
class EntityNotFoundException extends BaseException
{

    public function __construct($message = "Entity could not be found", $code = 300, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

