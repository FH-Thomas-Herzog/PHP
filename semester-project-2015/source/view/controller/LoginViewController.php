<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 9:37 AM
 */

namespace source\view\controller;


use source\common\AbstractViewController;
use source\common\DbException;
use source\common\InternalErrorException;
use source\db\controller\UserEntityController;
use source\view\model\RequestControllerResult;

/**
 * This controller is sued for handling the login and logout of an user.
 *
 * Class LoginViewController
 * @package source\view\controller
 */
class LoginViewController extends AbstractViewController
{
    public static $ACTION_REGISTRATION = "actionToRegistration";

    public static $ACTION_LOGIN = "actionLogin";

    /**
     * Constructs this controller instance and delegates to the base class so that common initialization can occur.
     */
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

    /**
     * Prepares the next intended view supported by this controller.
     *
     * @param string $nextView the id of the next intended view
     * @return array the array with the template arguments
     * @throws InternalErrorException if the view id is not supported by this handler
     */
    public function prepareView($nextView)
    {
        $args = array();

        switch ((string)$nextView) {
            case ViewController::$VIEW_LOGIN:
                $args = array(
                    "actionLogin" => LoginViewController::$ACTION_LOGIN,
                    "actionToRegister" => LoginViewController::$ACTION_REGISTRATION,
                    "cacheTemplate" => true,
                    "recreateTemplate" => true
                );
                break;
            default:
                throw new InternalErrorException("View: '" . $nextView . " not supported by this controller: '" . __CLASS__ . "'");
        }

        return $args;
    }

    /**
     * Handles the login of an user.
     *
     * @return RequestControllerResult the controller action result
     */
    private function handleLogin()
    {
        $jsonArray = null;
        $success = false;
        $username = parent::getParameter("username");
        $password = parent::getParameter("password");

        try {
            $userCtrl = new UserEntityController();
            $user = $userCtrl->getActiveUserByUsername($username);
            if (isset($user)) {
                $valid = SecurityController::getInstance()->loginUser($password, $user);
                if (!$valid) {
                    $jsonResult = array(
                        "error" => true,
                        "message" => "Login failed. Check your credentials and try again",
                        "messageType" => "warning"
                    );
                } else {
                    $jsonResult = array(
                        "error" => false,
                        "redirectUrl" => "/public/start.php"
                    );
                }
            } else {
                $jsonResult = array(
                    "error" => true,
                    "message" => "Login failed. Check your credentials and try again",
                    "messageType" => "warning"
                );
                $success = true;
            }
        } catch (DbException $e) {
            $jsonResult = array(
                "error" => true,
                "message" => "Sorry an database error occurred." . PHP_EOL . ". If this error keeps showing up, please notify the administrator",
                "messageType" => "danger"
            );
        }

        return new RequestControllerResult($success, null, $jsonResult);
    }

}