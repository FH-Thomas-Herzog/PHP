<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/15/2015
 * Time: 7:22 PM
 */

namespace SCM4\View\Controller;

use SCM4\Common\Object;
use SCM4\Common\Exception\SecurityException;

/**
 * This class holds the security error codes for security related exceptions.
 * Class SecurityErrorCodes
 * @package SCM4\View\Controller
 */
class SecurityErrorCodes extends Object
{
    public static $LOGIN_REFUSED = 201;
    public static $NO_ACTIVE_SESSION = 202;
    public static $ALREADY_ACTIVE_SESSION = 203;
    public static $USER_NOT_LOGGED = 204;
    public static $INSUFFICIENT_ARGS = 205;
}

/**
 * This class is implemented as an singleton and handles the security related
 * operations with the users.
 * Class SecurityController
 * @package SCM4\View\Controller
 */
class SecurityController extends Object
{

    private static $instance = null;

    /**
     * Private constructor which prevents instantiation of this class from the outsite.
     */
    private function __construct()
    {
    }

    /**
     * Creates the security controller ones and returns this instance.
     * @return the singleton SecurityController instance
     */
    public static function getInstance()
    {
        if (SecurityController::$instance == null) {
            SecurityController::$instance = new SecurityController();
        }

        return SecurityController::$instance;
    }

    /**
     * Logs the user with given username and password in.
     * @param string $username the username of the user
     * @param string $password the password of the user
     * @throws SecurityException if the login fails
     * @see SecurityErrorCodes for the relevant error codes
     */
    public function loginUser(string $username, string $password)
    {
        ObjectUtil::requireNotNull($username, new SecurityException("Username not allowed to be null", SecurityErrorCodes::$INSUFFICIENT_ARGS));
        ObjectUtil::requireNotNull($password, new SecurityException("Password not allowed to be null", SecurityErrorCodes::$INSUFFICIENT_ARGS));

        // TODO: log user in
    }

    /**
     * Logs the user with the given id out.
     * @param integer $userId the user id
     * @throws SecurityException if no session exists, session not owned by this user
     * @see $this->getSession();
     * @see $this->destroySession();
     */
    public function logoutUser(integer $userId)
    {
        ObjectUtil::requireNotNull($userId, new SecurityException("UserId not allowed to be null", SecurityErrorCodes::$INSUFFICIENT_ARGS));
        // Check if an session exists
        $session = $this->getSession();

        // TODO: Check if session is owned by this user.

        if (!$this->isUserLogged(userId)) {
            throw new SecurityException("User not logged in", SecurityErrorCodes::$USER_NOT_LOGGED);
        }
        // TODO: Log user out

        $this->destroySession();
    }

    /**
     * Answers the question if the used with the given id is still logged in.
     * @param integer $userId the user id to check if still logged in
     * @return bool true if the user is still logged in, false otherwise
     * @throws SecurityException if no session is active, user does not exists and user not logged in.
     * @see $this->getSession();
     */
    public function isUserLogged(integer $userId)
    {
        ObjectUtil::requireNotNull($userId, new SecurityException("UserId not allowed to be null", SecurityErrorCodes::$INSUFFICIENT_ARGS));
        //TODO: Check if user is logged
        return false;
    }

    /**
     * Answers the question if the user with the given id is still valid.
     * A valid user is a user who exists and whoc is able to be logged in.
     * @param $userId the user id
     * @return bool true if the user is valid, false otherwise
     */
    public function isUserValid($userId)
    {
        ObjectUtil::requireNotNull($userId, new SecurityException("UserId not allowed to be null", SecurityErrorCodes::$INSUFFICIENT_ARGS));
        //TODO: Check if user is valid (not deleted and blocked)
        return false;
    }

    /**
     * Answers the question if a session exists.
     * @return bool true if an session exists, false otherwise.
     */
    public function isSessionActive()
    {
        return session_status() == PHP_SESSION_ACTIVE;
    }

    /**
     * Gets the current active session.
     * @return $_SESSION the current active session
     * @throws SecurityException if no active session is associates with the current request.
     */
    public function getSession()
    {
        if ($this->isSessionActive()) {
            return $_SESSION;
        }
        throw new SecurityException("No session is associated with the current request", SecurityErrorCodes::$NO_ACTIVE_SESSION);
    }

    /**
     * STarts a new session.
     * @throws SecurityException if an session is already associated with the current request.
     */
    public function startSession()
    {
        if ($this->isSessionActive()) {
            throw new SecurityException("Session already associated with the current request", SecurityErrorCodes::$ALREADY_ACTIVE_SESSION);
        }
        session_start();
    }

    /**
     * Destroy the session associates with the current request.
     * @throws SecurityException if no session is associated with the current request
     * @see $this->getSession();
     */
    public function destroySession()
    {
        $this->getSession();
        session_destroy();
    }
}