<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 6/13/2015
 * Time: 10:21 AM
 */

/**
 * Class CategoryThe book category entity.
 */
class Category extends Entity
{
    private $name;

    function __construct($id, $name)
    {
        parent::__construct($id);
        $this->name = $name;
    }


    public function getName()
    {
        return $this->name;
    }

    public function  setName($name)
    {
        $this->name = $name;
    }
}