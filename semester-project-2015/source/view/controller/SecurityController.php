<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/15/2015
 * Time: 7:22 PM
 */

namespace source\view\controller;

use \source\common\SingletonObject;

/**
 * This class is implemented as an singleton and handles the security related
 * operations with the users.
 * Class SecurityController
 * @package SCM4\View\Controller
 */
class SecurityController extends SingletonObject
{
    private static $SESSION_USER_ID = "SESSION_USER_ID";

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
     * @param string $password the input password
     * @param stdClass $user the user from teh database
     * @throws SecurityException if the login fails
     * @return true if the login was successfull
     * @see SecurityErrorCodes for the relevant error codes
     */
    public function loginUser($password, $user)
    {
        if ((isset($password)) && (isset($user)) && (password_verify((string)$password, (string)$user->password))) {
            $this->sessionController->startSession();
            $this->sessionController->setAttribute(self::$SESSION_USER_ID, $user->id);
            return true;
        }
        return false;
    }

    /**
     * Logs the user out by destroying the backed user session.
     *
     * @param int $userId the users id
     * @return bool true if the user has been logged out false otherwise
     */
    public function logoutUser($userId = -666)
    {
        if (!$this->isUserLogged($userId)) {
            return false;
        }
        $this->sessionController->destroySession();
    }

    /**
     * Answers the question if the current active session holds an user.
     * @return bool true if the current active has an valid active user set.
     */
    public function isUserLogged()
    {
        if (!$this->sessionController->isSessionActive()) {
            return false;
        }

        $usrId = $this->getLoggedUser();
        return isset($usrId);
    }

    /**
     * Answers the question if the current active session holds an user.
     * @return bool true if the current active has an valid active user set.
     */
    public function getLoggedUser()
    {
        return $this->sessionController->getAttribute(self::$SESSION_USER_ID);
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