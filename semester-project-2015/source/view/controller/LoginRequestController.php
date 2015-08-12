<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 9:37 AM
 */

namespace source\view\controller;


use source\common\AbstractRequestController;
use source\common\DbException;
use source\common\InternalErrorException;
use source\db\controller\UserEntityController;
use source\view\model\RequestControllerResult;

class LoginRequestController extends AbstractRequestController
{
    public static $ACTION_REGISTRATION = "ACTION_TO_REGISTRATION";

    public static $ACTION_LOGIN = "ACTION_LOGIN";

    public function __construct()
    {
        parent::__construct();
    }

    public function handleAction()
    {
        $result = null;

        switch ($this->actionId) {
            // goes to registration page
            case self::$ACTION_REGISTRATION:
                $result = new RequestControllerResult(true, ViewController::$VIEW_REGISTRATION);
                break;
            // logs user in and goes to start
            case self::$ACTION_LOGIN:
                $result = $this->handleLogin();
                break;// handle unknown action
            case ViewController::$REFRESH_ACTION:
                $result = new RequestControllerResult(true, ViewController::$VIEW_LOGIN);
                break;
            default:
                throw new InternalErrorException("Action: '" . $this->actionId . "' cannot be handled by: '" . __CLASS__ . "''");
        }

        return $result;
    }

    public function prepareView($nextView)
    {
        $args = array();

        switch ((string)$nextView) {
            case ViewController::$VIEW_LOGIN:
                $args = array(
                    "actionLogin" => LoginRequestController::$ACTION_LOGIN,
                    "actionRegister" => LoginRequestController::$ACTION_REGISTRATION
                );
                break;
            default:
                throw new InternalErrorException("View: '" . $nextView . " not supported by this controller: '" . __CLASS__ . "'");
        }

        return $args;
    }

    private function handleLogin()
    {
        $username = parent::getParameter("username");
        $password = parent::getParameter("password");

        if (!isset($username) || (!isset($password))) {
            return new RequestControllerResult(false, ViewController::$VIEW_LOGIN);
        }

        try {
            $userCtrl = new UserEntityController();
            $user = $userCtrl->getActiveUserByUsername($username);
            if (isset($user)) {
                $valid = SecurityController::getInstance()->loginUser($password, $user);
                if (!$valid) {
                    return new RequestControllerResult(false, ViewController::$VIEW_LOGIN, array(
                        "message" => "Username or password wrong. Please try again",
                        "messageType" => "warning"

                    ));
                }
                return new RequestControllerResult($valid, ViewController::$VIEW_START);
            } else {
                return new RequestControllerResult(false, ViewController::$VIEW_LOGIN, array(
                    "message" => "Username or password wrong. Please try again",
                    "messageType" => "warning"

                ));
            }
        } catch (DbException $e) {
            return new RequestControllerResult(false, ViewController::$VIEW_LOGIN, array(
                "message" => "Sorry an database error occurred." . PHP_EOL . ". If this error keeps showing up, please notify the administrator",
                "messageType" => "danger"
            ));
        }
    }

}