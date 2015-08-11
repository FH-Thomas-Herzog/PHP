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
use source\common\utils\StringUtil;
use source\db\controller\ChannelEntityController;
use \Stash\Pool;

class ViewController extends AbstractRequestController
{

    public static $VIEW_INITIAL = "login";

    public static $VIEW_LOGIN = "login";

    public static $VIEW_MAIN = "main";

    public static $VIEW_REGISTRATION = "registration";

    public static $VIEW_REGISTRATION_SUCCESS = "registrationSuccess";

    public static $PARTIAL_VIEW_CHANNELS = "partialChannels";

    public static $PARTIAL_VIEW_NEW_CHANNEL = "partialNewChannel";

    public static $VIEW_ID = "viewId";

    public static $REFRESH_ACTION = "refreshAction";

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
                if (self::$VIEW_LOGIN === $this->viewId) {
                    header('Location: start.php');
                    return "";
                } else {
                    $args = array(
                        "actionToNewChannel" => MainController::$ACTION_TO_NEW_CHANNEL,
                        "actionToChannels" => MainController::$ACTION_TO_CHANNELS,
                        "actionLogout" => MainController::$ACTION_LOGOUT
                    );
                }
                break;
            case self::$PARTIAL_VIEW_NEW_CHANNEL:
                $args = array(
                    "actionSaveChannel" => ChannelController::$ACTION_SAVE_CHANNEL,
                    "actionToMain" => MainController::$ACTION_TO_CHANNELS
                );
                break;
            case self::$PARTIAL_VIEW_CHANNELS:
                $args = array(
                    "actionToSelectedChannel" => ChannelController::$ACTION_SAVE_CHANNEL
                );
                $channels = $this->prepareChannelsView($args);
                break;
        }
        $args["viewId"] = $result->nextView;

        // register the former and next view
        if (!StringUtil::startWith($this->viewId, "partial")) {
            $this->sessionCtrl->setAttribute("formerView", $this->viewId);
        } else {
            $this->sessionCtrl->setAttribute("formerPartialView", $this->viewId);
        }
        if (!StringUtil::startWith($result->nextView, "partial")) {
            $this->sessionCtrl->setAttribute("currentView", $result->nextView);
        } else {
            $this->sessionCtrl->setAttribute("currentPartialView", $result->nextView);
        }

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

    private function prepareChannelsView(array &$args = array())
    {
        $channelCtrl = new ChannelEntityController();
        if (!isset($args)) {
            $args = array();
        }
        try {
            $assigned = $channelCtrl->getAssignedChannelsWithMsgCount($this->securityCtrl->getLoggedUser());
            $unassigned = $channelCtrl->getUnassignedChannels($this->securityCtrl->getLoggedUser());
            $args["assignedChannels"] = $assigned;
            $args["availableChannels"] = $unassigned;
        } catch (DbException $e) {
            var_dump($e);
        }
    }
}