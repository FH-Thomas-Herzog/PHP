<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 5:41 PM
 */

namespace source\db\controller;


use source\common\DbException;
use source\common\InternalErrorException;

class UserEntityController extends AbstractEntityController
{
    private static $SQL_CHECK_ACTIVE_USER_BY_USERNAME = "SELECT id FROM user WHERE UPPER(username) = UPPER(?) AND deleted_flag = 0 AND blocked_flag = 0";

    private static $SQL_GET_ACTIVE_USER_BY_USERNAME = "SELECT * FROM user WHERE UPPER(username) = UPPER(?) AND deleted_flag = 0 AND blocked_flag = 0";

    private static $SQL_CHECK_ACTIVE_USER_BY_EMAIL = "SELECT id FROM user WHERE UPPER(email) = UPPER(?) AND deleted_flag = 0 AND blocked_flag = 0";

    private static $SQL_INSERT_USER = "INSERT INTO user (firstname, lastname, email, username, password) VALUES (?,?,?,?,?)";

    public function __construct($mysqli = null)
    {
        parent::__construct($mysqli);
    }

    public function getById($id)
    {
        // TODO: Implement getById() method.
    }

    public function getAll()
    {

    }

    /**
     * Answers the question if an user already exists with the given username.
     *
     * @param string $username the username to get user for
     * @return boolean true if a user exists with this username, false otherwise
     * @throws DbException if an error occurs
     */
    public function isActiveUserExistingWithUsername($username)
    {
        parent::open();

        $stmt = null;
        $res = false;

        try {
            $stmt = parent::prepareStatement(self::$SQL_CHECK_ACTIVE_USER_BY_USERNAME);
            $p1 = (string)$username;
            $stmt->bind_param("s", $p1);
            $stmt->execute();
            $stmtRes = $stmt->get_result();
            $res = ($stmtRes->num_rows != 0);
        } catch (\Exception $e) {
            throw new DbException("Error on executing query: '" . self::$SQL_CHECK_ACTIVE_USER_BY_USERNAME . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->free_result();
                $stmt->close();
            }
            parent::close();
        }

        return $res;
    }

    /**
     * Answers the question if a user with the give email already exists.
     *
     * @param string $email the users email
     * @return boolean true if a user already exists with this email, false otherwise
     * @throws DbException if an error occurs
     */
    public function isActiveUserExistingWithEmail($email)
    {
        parent::open();

        $stmt = null;
        $res = false;
        try {
            $stmt = parent::prepareStatement(self::$SQL_CHECK_ACTIVE_USER_BY_EMAIL);
            $p1 = (string)$email;
            $stmt->bind_param("s", $p1);
            $stmt->execute();
            $stmtRes = $stmt->get_result();
            $res = ($stmtRes->num_rows != 0);
        } catch (\Exception $e) {
            throw new DbException("Error on executing query: '" . self::$SQL_CHECK_ACTIVE_USER_BY_EMAIL . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->free_result();
                $stmt->close();
            }
            parent::close();
        }

        return $res;
    }

    /**
     * Gets the active user by his username.
     *
     * @param $username the username to get suer for
     * @return the retrieved user
     * @throws DbException if an error occurs
     */
    public function getActiveUserByUsername($username)
    {
        parent::open();

        $stmt = null;
        $res = null;

        try {
            $stmt = parent::prepareStatement(self::$SQL_GET_ACTIVE_USER_BY_USERNAME);
            $p1 = (string)$username;
            $stmt->bind_param("s", $p1);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_object();
        } catch (\Exception $e) {
            throw new DbException("Error on executing query: '" . self::$SQL_GET_ACTIVE_USER_BY_USERNAME . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->free_result();
                $stmt->close();
            }
            parent::close();
        }

        return $res;
    }


    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
    }

    /**
     * Persists the user defined by given parameters
     * @param array $args the array containing the user attributes
     * @return stdClass the persisted user
     * @throws DbException if an error occurs
     * @throws InternalErrorException if the array is not set
     */
    public function persist(array $args)
    {
        if (empty($args)) {
            throw new InternalErrorException("Cannot save user with null given entity field args");
        }

        parent::open();

        $stmt = null;
        try {
            $stmt = parent::prepareStatement(self::$SQL_INSERT_USER);
            $p1 = (string)$args["firstname"];
            $p2 = (string)$args["lastname"];
            $p3 = (string)$args["email"];
            $p4 = (string)$args["username"];
            $p5 = password_hash((string)$args["password"], PASSWORD_BCRYPT);
            $stmt->bind_param("sssss", $p1, $p2, $p3, $p4, $p5);
            parent::startTx(true);
            $stmt->execute();
            parent::commit();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query: '" . self::$SQL_INSERT_USER . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            parent::close();
        }
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