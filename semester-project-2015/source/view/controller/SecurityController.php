<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/15/2015
 * Time: 7:22 PM
 */

namespace SCM4\View\Controller;

use SCM4\Common\SingletonObject;
use SCM4\Common\Exception\SecurityException;
use SCM4\View\Model\UserSessionModel;

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
     * @param string $username the username of the user
     * @param string $password the password of the user
     * @throws SecurityException if the login fails
     * @see SecurityErrorCodes for the relevant error codes
     */
    public function loginUser($username, $password)
    {
        ObjectUtil::requireNotNull($username, new SecurityException("Username not allowed to be null", SecurityErrorCodes::$INSUFFICIENT_ARGS));
        ObjectUtil::requireNotNull($password, new SecurityException("Password not allowed to be null", SecurityErrorCodes::$INSUFFICIENT_ARGS));

        // TODO: log user in
        $user = null;

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
     * Answers the question if the used with the given id is still logged in.
     * @param integer $userId the user id to check if still logged in
     * @return bool true if the user is still logged in, false otherwise
     */
    public function isUserLogged($userId = -666)
    {
        if ((!isset($userId)) || (!$this->sessionController->isSessionActive())) {
            return false;
        }
        $userModel = $this->sessionController->getAttribute(SessionController::$USER_MODEL);
        return ((isset($userModel)) && ($userModel->getUserId() === $userId));
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