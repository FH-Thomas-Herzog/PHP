<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 9:18 AM
 */

namespace source\view\controller;

use source\common\InternalErrorException;
use source\common\AbstractRequestController;

class ActionController extends AbstractRequestController
{

    public static $ACTION_LOGIN = "ACTION_LOGIN";

    public static $ACTION_LOGOUT = "ACTION_LOGOUT";

    public static $POOL_NAMESPACE = "controller/action";

    public static $ACTION_ID = "actionId";

    private $sesionCtrl;

    public function __construct()
    {
        parent::__construct();
        $this->sesionCtrl = SessionController::getInstance();
    }

    public function handleRequest()
    {
        parent::handleRequest();

        $controller = null;

        try {
            switch ($this->actionId) {
                case ActionController::$ACTION_LOGIN:
                    return false;
                default:
                    break;
            }
        }catch (\Exception $ex) {
            // Handle error
            return false;
        }
    }
}