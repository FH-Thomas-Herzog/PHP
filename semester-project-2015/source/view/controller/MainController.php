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

class MainController extends AbstractRequestController
{

    public static $ACTION_TO_DEFAULT = "actionToDefault";

    public static $ACTION_TO_PROFILE = "actionToProfile";

    public static $ACTION_TO_NEW_CHANNEL = "actionToNewChannel";

    public static $ACTION_TO_SELECTED_CHANNEL = "actionSelectChannel";

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
            case self::$ACTION_TO_NEW_CHANNEL:
                return new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_NEW_CHANNEL);
            case self::$ACTION_TO_SELECTED_CHANNEL:
                // TODO: Select the channel and then goe to it
                return new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_CHANNELS);
            case self::$ACTION_TO_DEFAULT:
                return new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_CHANNELS);
            default:
                throw new InternalErrorException("Action with id: '" . $this->actionId . "' not supported by this handler: '" . __CLASS__ . "''");
        }
    }
}