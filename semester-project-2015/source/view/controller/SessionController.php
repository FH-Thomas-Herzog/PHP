<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/15/2015
 * Time: 8:46 PM
 */

namespace SCM4\View\Controller;

use SCM4\Common\Exception\CoreException;
use SCM4\Common\Exception\InternalErrorException;
use SCM4\Common\ObjectUtil;

/**
 * This class represents the session controller which handles the access to session
 * attributes and values.
 * Class SessionController
 * @package SCM4\View\Controller
 */
class SessionController
{

    /**
     * The key for the user model which represents the logged user
     * @var string
     */
    private static $USER_MODEL = "USER_MODEL";

    /**
     * The key for the session start date/time.
     * @var string
     */
    private static $SESSION_START = "SESSION_START";

    /**
     * The key for the last access time.
     * Represents the date/time of the last occurred request.
     * @var string
     */
    private static $LAST_ACCESS_TIME = "LAST_ACCESS_TIME";

    /**
     * Timeout in minutes who long a session is allowed to exist before it gets invalidated.
     * @var
     */
    private $sessionTimeout;

    /**
     * Constructs an SessionController with the given sessionTimeOut.
     * @param integer $sessionTimeOut the session timeout >= 0 in minutes (0 = infinite, default = 30 minutes)
     * @throws InternalErrorException if the sessionTimeOut < 0
     */
    public function __construct(integer $sessionTimeOut = 30)
    {
        parent::__construct();
        if ($sessionTimeOut < 0) {
            throw new InternalErrorException("A session timeout smaller than 0 is not allowed");
        }
        self::$sessionTimeOut = $sessionTimeOut;
    }

    /**
     * Gets the user set on the current associated session.
     * @return User|null the User if set on the current active session, null otherwise
     * @throws \SCM4\Common\CoreException if no session associated with the current request.
     */
    public function getUser()
    {
        return self::getAttribute(self::$USER_MODEL);
    }

    /**
     * Sets the start date/time on the current associated session.
     * @throws CoreException if tno session is active
     */
    public function setSessionStart()
    {
        self::getSession()[self::$SESSION_START] = new \DateTime();
    }

    /**
     * Sets the last access date/time on the current associated session.
     * @throws CoreException if tno session is active
     */
    public function setLastAccess()
    {
        self::getSession()[self::$LAST_ACCESS_TIME] = new \DateTime();
    }

    /**
     * Gets an attribute from the current associated session object.
     * @param string $name the attribute name
     * @return any|null the attribute value or null if attribute not set
     * @throws \SCM4\Common\CoreException if no session is associated with the current request
     */
    public function getAttribute(string $name)
    {
        ObjectUtil::requireNotNull($name);
        $session = self::getSession();
        ObjectUtil::requireNotNull($session);

        if (isset($session[$name])) {
            return $session[$name];
        }
        return null;
    }

    /**
     * Sets the value for the given attribute on the current associated session.
     * @param string $name the attribute name
     * @param $value the attribute value
     * @throws \SCM4\Common\CoreException if attribute name is null or no active session present
     */
    public function setAttribute(string $name, $value)
    {
        ObjectUtil::requireNotNull($name);
        $session = self::getSession();
        ObjectUtil::requireNotNull($session);

        $session[$name] = $value;
    }

    /**
     * Gets the current associated session if one exists.
     * @return $_SESSION if an session is associated with this request, null otherwise
     */
    private function getSession()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            return $_SESSION;
        }
        return null;
    }
}