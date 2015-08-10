<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/10/2015
 * Time: 1:08 PM
 */

namespace source\view\controller;


use source\common\AbstractRequestController;
use source\common\BaseObject;
use source\common\InternalErrorException;
use source\view\model\RequestControllerResult;

class ProfileController extends AbstractRequestController
{

    public static $ACTION_TO_PROFILE = "actionToProfile";

    public function __construct()
    {
        parent::__construct();
    }

    public function handleRequest()
    {
        parent::handleRequest();

        switch ($this->actionId) {
            case self::$ACTION_TO_PROFILE:
                return new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_PROFILE);
                break;
            default:
                throw new InternalErrorException("Action with id: '" . $this->actionId . "' not supported by this handler: '" . __CLASS__ . "''");
        }
    }
}