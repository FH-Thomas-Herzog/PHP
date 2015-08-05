<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 5:41 PM
 */

namespace source\db\controller;


use source\common\InternalErrorException;

class UserController extends AbstractEntityController
{
    private static $SQL_ACTIVE_USER_BY_USERNAME = "SELECT * FROM user WHERE UPPER(username) = UPPER(?) AND deleted_flag = 0 AND blocked_flag = 0";

    private static $SQL_ACTIVE_USER_BY_EMAIL = "SELECT * FROM user WHERE UPPER(email) = UPPER(?) AND deleted_flag = 0 AND blocked_flag = 0";

    private static $SQL_INSERT_USER = "INSERT INTO user (firstname, lastname, email, username, password) VALUES (?,?,?,?,?)";

    public function __construct()
    {
        parent::__construct();
    }

    public function getById($id)
    {
        // TODO: Implement getById() method.
    }

    public function getAll()
    {

    }

    public function getActiveUserByUsername($username)
    {
        $stmt = parent::prepareStatement(self::$SQL_ACTIVE_USER_BY_USERNAME);
        $p1 = (string)$username;
        $stmt->bind_param("s", $p1);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_object();
        return $res;
    }

    public function getActiveUserByEmail($email)
    {
        $stmt = parent::prepareStatement(self::$SQL_ACTIVE_USER_BY_EMAIL);
        $p1 = (string)$email;
        $stmt->bind_param("s", $p1);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_object();
        return $res;
    }

    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
    }

    public function persist(array $args)
    {
        if (empty($args)) {
            throw new InternalErrorException("Cannot save user with null given entity field args");
        }
        $stmt = parent::prepareStatement(self::$SQL_INSERT_USER);
        $p1 = (string)$args["firstname"];
        $p2 = (string)$args["lastname"];
        $p3 = (string)$args["email"];
        $p4 = (string)$args["username"];
        $p5 = password_hash((string)$args["password"], PASSWORD_BCRYPT);
        $stmt->bind_param("sssss", $p1, $p2, $p3, $p4, $p5);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }


    public
    function update($entity)
    {
        // TODO: Implement update() method.
    }

    public
    function delete($entity)
    {
        // TODO: Implement delete() method.
    }

}