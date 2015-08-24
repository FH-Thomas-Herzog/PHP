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

/**
 * This controller is the db accessor to the user table.
 *
 * Class UserEntityController
 * @package source\db\controller
 */
class UserEntityController extends AbstractEntityController
{
    /**
     * Query which gets all user attributes as they are present on the database.
     * @var string
     */
    private static $SQL_GET_ACTIVE_USER_BY_USERNAME = "SELECT * FROM user WHERE UPPER(username) = UPPER(?) AND deleted_flag = 0";

    /**
     * The query which is used to check if an user already user exists with the given username.
     * @var string
     */
    private static $SQL_CHECK_ACTIVE_USER_BY_USERNAME = "SELECT id FROM user WHERE UPPER(username) = UPPER(?) AND deleted_flag = 0";

    /**
     * Query which is used to check if an user already exists with the given email
     * @var string
     */
    private static $SQL_CHECK_ACTIVE_USER_BY_EMAIL = "SELECT id FROM user WHERE UPPER(email) = UPPER(?) AND deleted_flag = 0";

    /**
     * Query which is used to insert an user on the database. It relies on default values or triggers on user table.
     * @var string
     */
    private static $SQL_INSERT_USER = "INSERT INTO user (firstname, lastname, email, username, password) VALUES (?,?,?,?,?)";

    /**
     * Constructs this instance and delegates to the base class so that common initialization can be done to.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets the user entity by its id
     * @param integer $id the user id
     * @return stdClass the user as represented on the database
     */
    public function getById($id)
    {
        // TODO: Not needed therefore not implemented.
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
        $p1 = (string)$username;

        try {
            $stmt = parent::prepareStatement(self::$SQL_CHECK_ACTIVE_USER_BY_USERNAME);
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
     * Answers the question if an user with the given email already exists.
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
        $p1 = (string)$email;

        try {
            $stmt = parent::prepareStatement(self::$SQL_CHECK_ACTIVE_USER_BY_EMAIL);
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
        $p1 = (string)$username;

        try {
            $stmt = parent::prepareStatement(self::$SQL_GET_ACTIVE_USER_BY_USERNAME);
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

    /**
     * Deletes an user with the given id
     * @param integer $id the user id
     * @return boolean true if successful false otherwise
     */
    public function deleteById($id)
    {
        // TODO: Not needed therefore not implemented
    }

    /**
     * Persists the user defined by the given parameters.
     *
     * @param array $args the array containing the user attributes
     * @throws DbException
     * @throws InternalErrorException
     * @return nothing
     */
    public function persist(array $args)
    {
        if (empty($args)) {
            throw new InternalErrorException("Cannot save user with null or empty given user attributes");
        }

        parent::open();

        $p1 = (string)$args["firstname"];
        $p2 = (string)$args["lastname"];
        $p3 = (string)$args["email"];
        $p4 = (string)$args["username"];


        $stmt = null;
        try {
            $stmt = parent::prepareStatement(self::$SQL_INSERT_USER);
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

    /**
     * Updates the user with the given user attributes
     * @param array $args the array holding the user attributes
     * @return boolean true if successful false otherwise
     */
    public function update(array $args)
    {
        // TODO: Not needed therefore not implemented
    }
}