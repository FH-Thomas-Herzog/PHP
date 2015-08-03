<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/15/2015
 * Time: 8:46 PM
 */

namespace source\view\controller;

use source\common\CoreException;
use source\common\InternalErrorException;
use source\common\SingletonObject;
use source\common\utils\ObjectUtil;

/**
 * This class represents the session controller which handles the access to session
 * attributes and values.
 * Class SessionController
 * @package SCM4\View\Controller
 */
class SessionController extends SingletonObject
{

    /**
     * The key for the user model_propel which represents the logged user
     * @var string
     */
    public static $USER_MODEL = "USER_MODEL";

    /**
     * The key for the session start date/time.
     * @var string
     */
    public static $SESSION_START = "SESSION_START";

    /**
     * The key for the last access time.
     * Represents the date/time of the last occurred request.
     * @var string
     */
    public static $LAST_ACCESS_TIME = "LAST_ACCESS_TIME";

    /**
     * The key for the session timeout.
     * Represents session timeout in minutes.
     * @var string
     */
    public static $SESSION_TIMEOUT = "SESSION_TIMEOUT";

    private static $instance = null;

    /**
     * Constructs an SessionController with the given sessionTimeOut.
     * @param integer $sessionTimeOut the session timeout >= 0 in minutes (0 = infinite, default = 30 minutes)
     * @throws InternalErrorException if the sessionTimeOut < 0
     */
    private function __construct()
    {
    }

    /**
     * Creates and returns an singleton instance of an SessionController. (request scoped)
     * @return SessionController the singleton instance
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new SessionController();
        }
        return self::$instance;
    }

    /**
     * Gets the user set on the current associated session.
     * @return User|null the User if set on the current active session, null otherwise
     * @throws \SCM4\Common\CoreException if no session associated with the current request.
     */
    public function getUser()
    {
        return $this->getAttribute(self::$USER_MODEL);
    }

    /**
     * Sets the last access date/time on the current associated session.
     * @throws CoreException if tno session is active
     */
    public function setLastAccess()
    {
        $_SESSION[self::$LAST_ACCESS_TIME] = new \DateTime();
    }

    /**
     * Gets an attribute from the current associated session object.
     * @param string $name the attribute name
     * @return any|null the attribute value or null if attribute not set
     * @throws \SCM4\Common\CoreException if no session is associated with the current request
     */
    public function getAttribute($name)
    {
        ObjectUtil::requireSet($name, new CoreException("Cannot get value for null attribute name"));
        if (!$this->isSessionActive()) {
            new CoreException("Cannot get attribute from non existing session");
        }

        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        return null;
    }

    /**
     * Sets the value for the given attribute on the current associated session.
     * @param string $name the attribute name
     * @param any|$value the attribute value to be set. if null then attribute is unset
     * @throws \SCM4\Common\CoreException if attribute name is null or no active session present
     */
    public function setAttribute($name, $value = null)
    {
        ObjectUtil::requireSet($name, new CoreException("Cannot set value on null attribute name"));
        if (!$this->isSessionActive()) {
            new CoreException("Cannot get attribute from non existing session");
        }

        if (isset($value)) {
            $_SESSION[$name] = $value;
        } else {
            unset($_SESSION[$name]);
        }
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
     * Starts an http session with the given timeout.
     * Will destroy an associated session if exists.
     * @param int $sessionTimeout the $sessionTimeout the session timeout in minutes, default is 30
     * @throws InternalErrorException
     */
    public function startSession($sessionTimeout = 30)
    {
        if ($this->isSessionActive()) {
            $this->destroySession();
        }
        if (!session_start()) {
            throw new InternalErrorException("Session creation failed");
        }
        if ((integer)$sessionTimeout < 0) {
            throw new InternalErrorException("A session timeout smaller than 0 not allowed");
        }

        $this->setAttribute(self::$SESSION_START, new \DateTime());
        $this->setAttribute(self::$SESSION_TIMEOUT, (integer)$sessionTimeout);
        $this->setLastAccess();
    }

    /**
     * Destroy the session associates with the current request.
     * @throws SecurityException if no session is associated with the current request
     */
    public function destroySession()
    {
        if ($this->isSessionActive()) {
            session_unset();
            session_destroy();
        }
    }
}