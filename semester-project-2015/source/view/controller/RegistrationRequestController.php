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
        $valid = true;
        $success = false;

        $firstname = parent::getParameter("firstname");
        $lastname = parent::getParameter("lastname");
        $username = parent::getParameter("username");
        $email = parent::getParameter("email");
        $password = parent::getParameter("password");

        if (empty($firstname)) {
            $args["firstnameError"] = "Firstname must be given";
            $valid = false;
        } else {
            $args["firstname"] = $firstname;
        }
        if (empty($lastname)) {
            $args["lastnameError"] = "Lastname must be given";
            $valid = false;
        } else {
            $args["lastname"] = $lastname;
        }
        if (empty($username)) {
            $args["usernameError"] = "Username must be given";
            $valid = false;
        } else {
            $args["username"] = $username;
        }
        if (empty($email)) {
            $args["emailError"] = "Email must be given";
            $valid = false;
        } else {
            $args["email"] = $email;
        }
        if (empty($password)) {
            $args["passwordError"] = "Password must be given";
            $valid = false;
        } else {
            $args["password"] = $password;
        }

        $userCtrl = new UserController();
        if ($valid) {
            // check for already used username
            $user = $userCtrl->getActiveUserByUsername($username);
            if (isset($user)) {
                $args["usernameError"] = "Username already in use";
            } else {
                $user = $userCtrl->getActiveUserByEmail($email);
                if (isset($user)) {
                    $args["usernameError"] = "Email already in use";
                } else {
                    $user = $userCtrl->persist(array(
                        "firstname" => $firstname,
                        "lastname" => $lastname,
                        "email" => $lastname,
                        "username" => $username,
                        "password" => $password
                    ));
                    if (!isset($user)) {
                        $args["message"] = "Sorry could not save user, please try again";
                    } else {
                        $success = true;
                    }
                }
            }
        }

        // Keep on current page is save wasn't successful
        if ($success) {
            return new RequestControllerResult(false, ViewController::$VIEW_LOGIN, $args);
        } // Goe to login page is successful
        else {
            return new RequestControllerResult(true, ViewController::$VIEW_REGISTRATION, $args);
        }
    }
}