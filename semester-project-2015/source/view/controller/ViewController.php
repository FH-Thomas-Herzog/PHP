<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 1:15 PM
 */

namespace source\view\controller;


use source\common\AbstractRequestController;
use source\common\InternalErrorException;
use source\view\model\RequestControllerResult;
use \Stash\Pool;

class ViewController extends AbstractRequestController
{

    public static $VIEW_INITIAL = "login";

    public static $VIEW_LOGIN = "login";

    public static $VIEW_MAIN = "main";

    public static $VIEW_REGISTRATION = "registration";

    public static $VIEW_REGISTRATION_SUCCESS = "registrationSuccess";

    public static $PARTIAL_VIEW_CHANNELS = "channels";

    public static $PARTIAL_VIEW_NEW_CHANNEL = "newChannel";

    public static $PARTIAL_DEFAULT = "defaultMain";

    public static $PARTIAL_VIEW_PROFILE = "profile";

    public static $VIEW_ID = "viewId";

    private $pool;

    private $actionCtrl;

    public function __construct(Pool $pool)
    {
        parent::__construct();
        $this->actionCtrl = new ActionController();
        if (!isset($pool)) {
            throw new InternalErrorException("Pool null but needed");
        }
        $this->pool = $pool;
    }


    public function handleRequest()
    {
        parent::handleRequest();

        $controller = null;
        $result = null;
        $typedResult = null;

        // handle view specific action
        switch ($this->viewId) {
            // the login view actions
            case self::$VIEW_LOGIN:
                $controller = new LoginRequestController();
                break;
            // the registration view actions
            case self::$VIEW_REGISTRATION:
                $controller = new RegistrationRequestController();
                break;
            // the registration success actions
            case self::$VIEW_REGISTRATION_SUCCESS:
                $controller = new RegistrationRequestController();
                break;
            // the new channel view actions
            case self::$PARTIAL_VIEW_NEW_CHANNEL:
                $controller = new ChannelController();
                break;
            // the main view actions
            case self::$VIEW_MAIN:
                $controller = new MainController();
                break;
            // the channels view actions
            case self::$PARTIAL_VIEW_CHANNELS:
                $controller = new ChannelController();
                break;
            // the new channel view actions
            case self::$PARTIAL_VIEW_NEW_CHANNEL:
                $controller = new ChannelController();
                break;
            default:
                throw new InternalErrorException("Unknown view with id: '" . $this->viewId . "' detected'");
        }
        if (isset($controller)) {
            $result = $controller->handleRequest();
        }

        // render next view
        $resArgs = (isset($result) && isset($result->args)) ? $result->args : array();
        $args = array();
        switch ($result->nextView) {
            case self::$VIEW_LOGIN:
                $args = array(
                    "actionLogin" => LoginRequestController::$ACTION_LOGIN,
                    "actionRegister" => LoginRequestController::$ACTION_REGISTRATION
                );
                break;
            case self::$VIEW_REGISTRATION:
                $args = array(
                    "actionRegister" => RegistrationRequestController::$ACTION_REGISTER,
                    "actionToLogin" => RegistrationRequestController::$ACTION_TO_LOGIN
                );
                break;
            case self::$VIEW_REGISTRATION_SUCCESS:
                $args = array(
                    "actionToLogin" => RegistrationRequestController::$ACTION_TO_LOGIN
                );
                break;
            case self::$VIEW_MAIN:
                $args = array(
                    "actionToSelectedChannel" => MainController::$ACTION_TO_SELECTED_CHANNEL,
                    "actionToNewChannel" => MainController::$ACTION_TO_NEW_CHANNEL,
                    "actionToProfile" => MainController::$ACTION_TO_PROFILE,
                    "actionToDefault" => MainController::$ACTION_TO_DEFAULT,
                );
                break;
            case self::$PARTIAL_VIEW_NEW_CHANNEL:
                $args = array(
                    "actionSaveChannel" => ChannelController::$ACTION_SAVE_CHANNEL,
                    "actionToMain" => ChannelController::$ACTION_TO_MAIN
                );
        }
        $args["viewId"] = $result->nextView;

        // register the former and next view
        $this->sessionCtrl->setAttribute("formerView", $this->viewId);
        $this->sessionCtrl->setAttribute("currentView", $result->nextView);

        return $this->getTemplateController()->renderView($result->nextView, true, true, array_merge($resArgs, $args));
    }

    private function getTemplateController()
    {
        $item = $this->pool->getItem("controller/view");
        if ($item->isMiss()) {
            $pool = PoolController::createFileSystemPool(TemplateController::$POOL_NAMESPACE, array("path" => ROOT_PATH . "/cache/templates"));
            $templateCtrl = new TemplateController($pool);
            $item->set($templateCtrl);
        }
        return $item->get();
    }
}