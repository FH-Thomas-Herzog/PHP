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

class LoginRequestController extends AbstractRequestController
{
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
            case ActionController::$ACTION_LOGIN:
                return $this->handleLogin();
            default:
                throw new InternalErrorException("Action: '" . $this->actionId . "' cannot be handled by: '" . __CLASS__ . "''");
        }

        // TODO: Login user
    }

    private function handleLogin()
    {
        $username = parent::getParameter("username");
        $password = parent::getParameter("password");

        if (!isset($username) || (!isset($password))) {
            return false;
        }

        $userCtrl = new UserController();
        $user = $userCtrl->getByUsername($username);
        if (!isset($user)) {
            return false;
        } else if (password_verify($password, $user->password)) {
            SecurityController::getInstance()->loginUser($user);
            return true;
        }
        return false;
    }

    private function handleLogout()
    {

    }
}