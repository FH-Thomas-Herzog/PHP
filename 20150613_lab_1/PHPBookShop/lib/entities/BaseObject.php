<?php

/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 6/13/2015
 * Time: 10:08 AM
 */
/*
 * This class represents the base object of all
     * @param string $name
     * entities.
     */

class BaseObject
{
    /**
     * Magic method 'call'.
     * Magic methods are supposed to be prefixed with '__' (code convention).
     * Magic method which handles invocations of undeclared methods
     * @param string $name
     * @param array $args
     * @throws Exception
     */
    public function __call($name, $args)
    {
        throw new Exception('method ' . $name . ' is not declared');
    }

    /**
     * Magic function for handling set operations on undeclared fields.
     * @param array $value
     * @throws Exception
     */
    public function __set($name, $value)
    {
        throw new Exception('Entity field ' . $name . ' is not declared');
    }

    /**
     * Magic method for handling invocations on not declared static methods.
     * @param string $name
     * @param array $args
     * @throws Exception
     */
    public static function __callStatic($name, $args)
    {
        throw new Exception('static method ' . $name . ' is not declared');
    }
}