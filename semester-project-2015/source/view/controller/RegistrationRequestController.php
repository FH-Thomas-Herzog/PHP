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

class RegistrationRequestController extends AbstractRequestController
{

    public static $ACTION_REGISTER = "ACTION_REGISTER";

    public static $ACTION_CANCEL = "ACTION_CANCEL";

    private $viewController;

    public function __construct()
    {
        parent::__construct();
    }

    public function handleRequest()
    {
        parent::handleRequest();

        switch ($this->actionId) {
            case self::$ACTION_CANCEL:
                return new RequestControllerResult(true, ViewController::$VIEW_LOGIN);
            case self::$ACTION_REGISTER:
                return $this->handleRegister();
            default:
                throw new InternalErrorException("Action: '" . $this->actionId . "' cannot be handled by: '" . __CLASS__ . "''");
        }

        // TODO: Login user
    }

    private function handleRegister()
    {
        $args = array();

        $firstname = parent::getParameter("firstname");
        $lastname = parent::getParameter("lastname");
        $username = parent::getParameter("username");
        $password = parent::getParameter("password");

        if (empty($firstname)) {
            $args["firstnameError"] = "Firstname must be given";
        } else {
            $args["firstname"] = $firstname;
        }
        if (empty($lastname)) {
            $args["lastnameError"] = "Lastname must be given";
        } else {
            $args["lastname"] = $lastname;
        }
        if (empty($username)) {
            $args["usernameError"] = "Username must be given";
        } else {
            $args["username"] = $username;
        }
        if (empty($password)) {
            $args["passwordError"] = "Password must be given";
        } else {
            $args["password"] = $password;
        }

        // TODO: Check for already user email, username and valid password
        return new RequestControllerResult(false, ViewController::$VIEW_REGISTRATION, $args);
    }
}