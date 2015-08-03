<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 5:41 PM
 */

namespace source\db\controller;


class UserController extends AbstractEntityController
{
    private static $SQL_BY_USERNAME = "SELECT * FROM user WHERE UPPER(username) = UPPER(?) AND deleted_flag = 0 AND blocked_flag = 0";

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

    public function getByUsername($username)
    {
        $stmt = parent::prepareStatement(self::$SQL_BY_USERNAME);
        $p1 = (string)$username;
        $stmt->bind_param("s", $p1);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_object();
        return $res;
    }

    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
    }

    public function persist($entity)
    {
        // TODO: Implement persist() method.
    }

    public function update($entity)
    {
        // TODO: Implement update() method.
    }

    public function delete($entity)
    {
        // TODO: Implement delete() method.
    }

}