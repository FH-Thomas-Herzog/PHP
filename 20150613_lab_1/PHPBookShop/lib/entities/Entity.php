<?php

/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 6/13/2015
 * Time: 10:16 AM
 */

/**
 * Interface IdHolderInterface which specifies an id holder.
 */
interface IdHolder
{
    /**
     * Gets the entity id;
     * @return integer $id
     */
    public function getId();

    /*
     * Sets the entity id.
     * @param integer $id
     */
    public function setId($id);
}

/**
 * Class Entity which represents an entity.
 */
class Entity extends BaseObject implements IdHolder
{
    private $id;

    public function __construct($id)
    {
        $this->id = intval($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}