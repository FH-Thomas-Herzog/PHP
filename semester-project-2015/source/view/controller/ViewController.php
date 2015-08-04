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

    public static $VIEW_START = "start";

    public static $VIEW_REGISTRATION = "registration";

    public static $VIEW_ID = "viewId";

    private $pool;

    private $sesionCtrl;

    private $actionCtrl;

    public function __construct(Pool $pool)
    {
        parent::__construct();
        $this->sesionCtrl = SessionController::getInstance();
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
            case ViewController::$VIEW_LOGIN:
                $result = (new LoginRequestController())->handleRequest();
                break;
            case ViewController::$VIEW_REGISTRATION:
                $result = (new RegistrationRequestController())->handleRequest();
                break;
        }

        // render next view
        $resArgs = (isset($result->args)) ? $result->args : array();
        switch ($result->nextView) {
            case self::$VIEW_LOGIN:
                $args = array(
                    "actionLogin" => LoginRequestController::$ACTION_LOGIN,
                    "actionRegister" => LoginRequestController::$ACTION_REGISTRATION
                );
                return $this->getTemplateController()->renderView($result->nextView, true, true, array_merge($resArgs, $args));
            case self::$VIEW_REGISTRATION:
                $args = array(
                    "viewId" => ViewController::$VIEW_LOGIN,
                    "actionRegister" => RegistrationRequestController::$ACTION_REGISTER,
                    "actionCancel" => RegistrationRequestController::$ACTION_CANCEL
                );
                return $this->getTemplateController()->renderView($result->nextView, true, true, array_merge($resArgs, $args));
        }
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