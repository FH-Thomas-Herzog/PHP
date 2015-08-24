<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/10/2015
 * Time: 1:08 PM
 */

namespace source\view\controller;


use source\common\AbstractViewController;
use source\common\InternalErrorException;
use source\view\model\RequestControllerResult;

class MainViewController extends AbstractViewController
{

    public static $ACTION_TO_CHANNELS = "actionToChannels";

    public static $ACTION_TO_PROFILE = "actionToProfile";

    public static $ACTION_TO_NEW_CHANNEL = "actionToNewChannel";

    public static $ACTION_LOGOUT = "ACTION_LOGOUT";

    public function __construct()
    {
        parent::__construct();
    }

    public function handleAction()
    {
        $result = null;

        switch ($this->actionId) {
            case self::$ACTION_TO_NEW_CHANNEL:
                $result = new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_NEW_CHANNEL);
                break;
            case self::$ACTION_TO_CHANNELS:
                $result = new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_CHANNELS);
                break;
            case ViewController::$REFRESH_ACTION:
                $result = new RequestControllerResult(true, ViewController::$VIEW_MAIN);
                break;
            case self::$ACTION_LOGOUT:
                $this->securityCtrl->logoutUser();
                $result = new RequestControllerResult(true, ViewController::$VIEW_LOGIN);
                break;
            default:
                throw new InternalErrorException("Action with id: '" . $this->actionId . "' not supported by this handler: '" . __CLASS__ . "''");
        }

        return $result;
    }

    public function prepareView($nextView)
    {
        $args = array();
        switch ((string)$nextView) {
            case ViewController::$VIEW_START:
                header('Location: start.php');
                break;
            case ViewController::$VIEW_MAIN:
                $args = array(
                    "actionToNewChannel" => MainViewController::$ACTION_TO_NEW_CHANNEL,
                    "actionToChannels" => MainViewController::$ACTION_TO_CHANNELS,
                    "actionLogout" => MainViewController::$ACTION_LOGOUT
                );
                break;
            default:
                throw new InternalErrorException("View: '" . $nextView . " not supported by this controller: '" . __CLASS__ . "'");
        }

        return $args;
    }

}