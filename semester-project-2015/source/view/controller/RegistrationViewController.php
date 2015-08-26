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
 * This view controller is used for the registration view.
 *
 * Class RegistrationRequestController
 * @package source\view\controller
 */
class RegistrationViewController extends AbstractViewController
{

    public static $ACTION_REGISTER = "actionRegister";

    public static $ACTION_TO_LOGIN = "actionToLogin";

    /**
     * Constructs this instance and delegates to the base class so that common initialization can be done.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handles the supported actions.
     *
     * @return RequestControllerResult containing the result of the handled actions
     * @throws InternalErrorException if the action is not supported by this controller
     */
    public function handleAction()
    {
        $result = null;

        switch ($this->actionId) {
            // Action to go to login view
            case self::$ACTION_TO_LOGIN:
                $result = new RequestControllerResult(true, ViewController::$VIEW_LOGIN, array(
                    "success" => false
                ));
                break;
            // Registers the user
            case self::$ACTION_REGISTER:
                $result = $this->handleRegister();
                break;
            // Error on unsupported action
            default:
                throw new InternalErrorException("Action: '" . $this->actionId . "' cannot be handled by: '" . __CLASS__ . "''");
        }

        return $result;
    }

    /**
     * Prepares the to next display view supported by this controller.
     *
     * @param string $nextView the view id to prepare
     * @return array the array holding the arguments for the template render engine
     * @throws InternalErrorException if the view id is not supported by this controller
     */
    public function prepareView($nextView)
    {
        $result = array();

        switch ((string)$nextView) {
            // prepares the registration view
            case ViewController::$VIEW_REGISTRATION:
                $result = array(
                    "actionRegister" => RegistrationViewController::$ACTION_REGISTER,
                    "actionToLogin" => RegistrationViewController::$ACTION_TO_LOGIN,
                    "cacheTemplate" => true,
                    "recreateTemplate" => false
                );
                break;
            // prepares the registration success view
            case ViewController::$PARTIAL_VIEW_REGISTRATION_SUCCESS:
                $result = array(
                    "actionToLogin" => RegistrationViewController::$ACTION_TO_LOGIN,
                    "cacheTemplate" => true,
                    "recreateTemplate" => false
                );
                break;
            // Error on unsupported view
            default:
                throw new InternalErrorException("View: '" . $nextView . " not supported by this controller: '" . __CLASS__ . "'");
        }

        return $result;
    }

    // #########################################################################
    // Private action handle functions
    // #########################################################################
    /**
     * Handles the register action which registers an user on the platform.
     *
     * @return RequestControllerResult the result of this action
     * @throws InternalErrorException
     */
    private function handleRegister()
    {
        $jsonArray = null;
        $nextView = null;
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
            try {
                // If email already used by another active user
                if ($userCtrl->isActiveUserExistingWithEmail($args["email"])) {
                    $jsonArray = array(
                        "error" => true,
                        "message" => "Email already in use",
                        "type" => "warning"
                    );
                } // If username already used by another active user
                else if ($userCtrl->isActiveUserExistingWithUsername($args["username"])) {
                    $jsonArray = array(
                        "error" => true,
                        "message" => "Username already in use",
                        "type" => "warning"
                    );
                } // Here we are ready to save the user
                else {
                    $success = $userCtrl->persist($args);
                    $jsonArray = array(
                        "false" => true
                    );
                    $nextView = ViewController::$PARTIAL_VIEW_REGISTRATION_SUCCESS;
                }
            } catch (DbException $e) {
                $jsonArray = array(
                    "error" => true,
                    "message" => "Sorry an database error occurred." . PHP_EOL . ". If this error keeps showing up, please notify the administrator",
                    "type" => "danger"
                );
            }
        }

        return new RequestControllerResult($success, $nextView, $jsonArray);
    }
}