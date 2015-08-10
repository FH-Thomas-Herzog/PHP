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
use \source\db\controller\UserEntityController;
use \source\view\controller\SecurityController;
use source\view\model\RequestControllerResult;

class RegistrationRequestController extends AbstractRequestController
{

    public static $ACTION_REGISTER = "ACTION_REGISTER";

    public static $ACTION_TO_LOGIN = "ACTION_CANCEL";

    private $viewController;

    public function __construct()
    {
        parent::__construct();
    }

    public function handleRequest()
    {
        parent::handleRequest();

        switch ($this->actionId) {
            // Action to go to login view from registration view
            case self::$ACTION_TO_LOGIN:
                return new RequestControllerResult(true, ViewController::$VIEW_LOGIN, array(
                    "success" => false
                ));
            // Registers the user
            case self::$ACTION_REGISTER:
                return $this->handleRegister();
            default:
                throw new InternalErrorException("Action: '" . $this->actionId . "' cannot be handled by: '" . __CLASS__ . "''");
        }

        // TODO: Login user
    }

    /**
     * Handles the register action which registers an user on the platform.
     *
     * @return RequestControllerResult the result of this action
     * @throws InternalErrorException
     */
    private function handleRegister()
    {
        $userCtrl = new UserEntityController();
        $valid = true;
        $success = false;
        $args = array(
            "firstname" => parent::getParameter("firstname"),
            "lastname" => parent::getParameter("lastname"),
            "email" => parent::getParameter("email"),
            "username" => parent::getParameter("username"),
            "password" => parent::getParameter("password")
        );


        if ($valid) {
            if ($userCtrl->isActiveUserExistingWithUsername($args["username"])) {
                $args["usernameError"] = "Username already in use";
            } else if ($userCtrl->isActiveUserExistingWithEmail($args["email"])) {
                $args["usernameError"] = "Email already in use";
            } else {
                $user = $userCtrl->persist($args);
                if (!isset($user)) {
                    $args["message"] = "Sorry could not save user, please try again";
                } else {
                    $success = true;
                }
            }
        }

        // Keep on current page is save wasn't successful
        if (!$success) {
            return new RequestControllerResult(false, ViewController::$VIEW_REGISTRATION, $args);
        } // Goe to login page is successful
        else {
            $args["message"] = "Login successful";
            return new RequestControllerResult(true, ViewController::$VIEW_REGISTRATION_SUCCESS, $args);
        }
    }
}