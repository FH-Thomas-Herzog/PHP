<?php

/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 6/13/2015
 * Time: 10:52 AM
 */
class User extends Entity
{

    private $username;
    private $passwordHash;

    /**
     * Constructs a new user entity
     * @param integer $id the user id
     * @param string $username the user username
     * @param string $passwordHash the hashed user password
     */
    function __construct($id, $username, $passwordHash)
    {
        parent::__construct($id);
        $this->username = $username;
        $this->passwordHash = $passwordHash;
    }

    /**
     * Gets the user hashed password
     * @return string the hashed user password
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * Sets the user hashed password.
     * @param string $passwordHash the to set hashed password
     */
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    /**
     * Gets the user username
     * @return string the user's username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the user username
     * @param string $username the to set username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }


}