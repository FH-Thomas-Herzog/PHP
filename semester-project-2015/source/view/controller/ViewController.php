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

    public static $VIEW_START = "start";

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
        $result = $this->handleAction();
        $args = $this->prepareView($result->nextView);
        if (isset($args)) {
            return $this->getTemplateController()->renderView($result->nextView, true, true, array_merge($result->args, $args));
        } else {
            return "";
        }
    }

    public function handleAction()
    {
        $controller = null;

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

        return $controller->handleAction();
    }

    public function prepareView($nextView)
    {
        // render next view
        $controller = null;
        $args = array(
            "viewId" => $nextView
        );

        switch ($nextView) {
            case self::$VIEW_LOGIN:
                $controller = new LoginRequestController();
                break;
            case self::$VIEW_REGISTRATION:
                $controller = new RegistrationRequestController();
                break;
            case self::$VIEW_REGISTRATION_SUCCESS:
                $controller = new RegistrationRequestController();
                break;
            // the main view actions
            case self::$VIEW_START:
                $controller = new MainController();
                break;
            case self::$VIEW_MAIN:
                $controller = new MainController();
                break;
            case self::$PARTIAL_VIEW_NEW_CHANNEL:
                $controller = new ChannelController();
                break;
            case self::$PARTIAL_VIEW_CHANNELS:
                $controller = new ChannelController();
                break;
            default:
                throw new InternalErrorException("Next view: '" . $nextView . "' cannot be handled by '" . __CLASS__ . "'");
        }

        // register the former and next view
        if (!StringUtil::startWith($this->viewId, "partial")) {
            $this->sessionCtrl->setAttribute("formerView", $this->viewId);
        } else {
            $this->sessionCtrl->setAttribute("formerPartialView", $this->viewId);
        }
        if (!StringUtil::startWith($nextView, "partial")) {
            $this->sessionCtrl->setAttribute("currentView", $nextView);
        } else {
            $this->sessionCtrl->setAttribute("currentPartialView", $nextView);
        }

        $args = array_merge($args, $controller->prepareView($nextView));

        if (StringUtil::compare($nextView, self::$VIEW_START)) {
            return null;
        }

        return $args;
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