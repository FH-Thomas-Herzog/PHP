<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 9:37 AM
 */

namespace source\view\controller;


use \source\common\AbstractRequestController;
use \source\common\InternalErrorException;
use \source\db\controller\UserController;
use \source\view\controller\SecurityController;
use source\view\model\RequestControllerResult;

class LoginRequestController extends AbstractRequestController
{
    public static $ACTION_TO_LOGIN = "ACTION_TO_LOGIN";

    public static $ACTION_REGISTRATION = "ACTION_TO_REGISTRATION";

    public static $ACTION_LOGIN = "ACTION_LOGIN";

    public static $ACTION_LOGOUT = "ACTION_LOGOUT";

    private $viewController;

    public function __construct()
    {
        parent::__construct();
    }

    public function handleRequest()
    {
        parent::handleRequest();

        switch ($this->actionId) {
            // goes to login page
            case self::$ACTION_TO_LOGIN:
                return new RequestControllerResult(true, ViewController::$VIEW_LOGIN);
            // goes to registration page
            case self::$ACTION_REGISTRATION:
                return new RequestControllerResult(true, ViewController::$VIEW_REGISTRATION);
            // logs user in and goes to start
            case self::$ACTION_LOGIN:
                return $this->handleLogin();
            // handle unknown action
            default:
                throw new InternalErrorException("Action: '" . $this->actionId . "' cannot be handled by: '" . __CLASS__ . "''");
        }
    }

    private function handleLogin()
    {
        $username = parent::getParameter("username");
        $password = parent::getParameter("password");

        if (!isset($username) || (!isset($password))) {
            return new RequestControllerResult();
        }

        $userCtrl = new UserController();
        $user = $userCtrl->getActiveUserByUsername($username);
        if (isset($user)) {
            $valid = SecurityController::getInstance()->loginUser($password, $user);
            return new RequestControllerResult($valid, ViewController::$VIEW_START, null);
        }
        return new RequestControllerResult();
    }

    private function handleLogout()
    {

    }
}