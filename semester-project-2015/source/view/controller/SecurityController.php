<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/15/2015
 * Time: 7:22 PM
 */

namespace source\view\controller;

use \source\common\SingletonObject;
use \source\common\SecurityException;
use \source\view\model\UserSessionModel;

/**
 * This class holds the security error codes for security related exceptions.
 * Class SecurityErrorCodes
 * @package SCM4\View\Controller
 */
class SecurityErrorCodes
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
class SecurityController extends SingletonObject
{
    private $sessionController;

    private static $instance = null;

    /**
     * Private constructor which prevents instantiation of this class from the outsite.
     */
    private function __construct()
    {
        $this->sessionController = SessionController::getInstance();
    }

    /**
     * Creates the security controller ones and returns this instance.
     * @return the singleton SecurityController instance
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new SecurityController();
        }

        return self::$instance;
    }

    /**
     * Logs the user with given username and password in.
     *
     * @param stdClass user the suer to be logged in
     * @throws SecurityException if the login fails
     * @see SecurityErrorCodes for the relevant error codes
     */
    public function loginUser($user)
    {
        $this->sessionController->startSession();
        $this->sessionController->setAttribute(SessionController::$USER_MODEL, new UserSessionModel($user));
    }

    /**
     * Logs the user with the given id out.
     * @param integer $userId the user id
     * @throws SecurityException if no session exists, session not owned by this user
     * @see $this->getSession();
     * @see $this->destroySession();
     */
    public function logoutUser($userId = -666)
    {
        if (!$this->isUserLogged($userId)) {
            throw new SecurityException("User not logged in", SecurityErrorCodes::$USER_NOT_LOGGED);
        }
        $this->sessionController->destroySession();
    }

    /**
     * Answers the question if the current active session holds an user.
     * @return bool true if the current active has an valid active user set.
     */
    public function isUserLogged()
    {
        if ((!isset($userId)) || (!$this->sessionController->isSessionActive())) {
            return false;
        }
        $userModel = $this->sessionController->getAttribute(SessionController::$USER_MODEL);
        // TODO: Check db for this user
        return isset($userModel);
    }

    /**
     * Answers the question if the user with the given id is still valid.
     * A valid user is a user who exists and whoc is able to be logged in.
     * @param $userId the user id
     * @return bool true if the user is valid, false otherwise
     */
    public function isUserValid($userId = -666)
    {
        if (!isset($userId)) {
            return false;
        }
        return false;
    }
}