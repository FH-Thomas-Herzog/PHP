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
use \Stash\Pool;

class ViewController extends AbstractRequestController
{

    public static $VIEW_LOGIN = "login";

    public static $VIEW_START = "start";

    public static $REGISTRATION = "register";

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

        switch ($this->viewId) {
            case ViewController::$VIEW_LOGIN:
                switch ($this->actionId) {
                    case ActionController::$ACTION_LOGIN:
                        if ($this->actionCtrl->handleRequest()) {
                            echo " - Next page would be called here";
                        } else {
                            echo $this->getTemplateController()->renderView(self::$VIEW_LOGIN, true, true, array(
                                ActionController::$ACTION_ID => ActionController::$ACTION_LOGIN,
                                "message" => "Username or password are invalid. Please try again",
                                "messageType" => "danger"
                            ));
                        }
                        break;
                    default:
                        // Handle Invalid action on this view
                        break;
                }
                break;
            // No view id given therefore back to login page
            default:
                $this->sesionCtrl->startSession();
                echo $this->getTemplateController()->renderView(self::$VIEW_LOGIN, true, true, array(
                    ActionController::$ACTION_ID => ActionController::$ACTION_LOGIN
                ));
                return;
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