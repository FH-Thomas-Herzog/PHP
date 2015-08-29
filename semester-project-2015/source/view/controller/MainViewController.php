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
use source\db\controller\ChannelEntityController;
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
                $result = $this->handleToNewChannel();
                break;
            case self::$ACTION_TO_CHANNELS:
                $result = $this->handleToChannelsAction();
                break;
            case ViewController::$REFRESH_ACTION:
                $result = new RequestControllerResult(true, ViewController::$VIEW_MAIN);
                break;
            case self::$ACTION_LOGOUT:
                $this->securityCtrl->logoutUser();
                $result = new RequestControllerResult(true, ViewController::$VIEW_LOGIN, array(
                    "error" => false,
                    "redirectUrl" => "/public/index.php"
                ));
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

    /**
     * Handles the action to new channel which checks for an existing channels.
     *
     * @throws \source\common\DbException
     */
    private function handleToNewChannel()
    {
        $jsonArray = null;
        if (!((new ChannelEntityController())->checkIfChannelAreExisting())) {
            $jsonArray = array(
                "error" => false,
                "message" => "There are no channels present.",
                "messageType" => "warning",
                "additionalMessage" => "Please create one"
            );
        }

        return new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_NEW_CHANNEL, $jsonArray);
    }

    /**
     * Handles the to channels action. It is checked if there are channels present.
     * If not then next view is set to new channels view.
     *
     * @return RequestControllerResult the action handle result
     */
    private function handleToChannelsAction()
    {
        $success = false;
        $jsonArray = null;
        $nextView = null;

        try {
            if (!((new ChannelEntityController())->checkIfChannelAreExisting())) {
                $nextView = ViewController::$PARTIAL_VIEW_NEW_CHANNEL;
                $jsonArray = array(
                    "error" => false,
                    "message" => "There are no channels present.",
                    "messageType" => "warning",
                    "additionalMessage" => "Please create one"
                );
            } else {
                $nextView = ViewController::$PARTIAL_VIEW_CHANNELS;
                $jsonArray = array(
                    "error" => false
                );
            }
            $success = true;
        } catch (\Exception $e) {
            $jsonArray = array(
                "error" => true,
                "message" => "Sorry an database error occurred." . PHP_EOL . ". If this error keeps showing up, please notify the administrator",
                "messageType" => "danger",
                "additionalMessage" => $e->getMessage()
            );
        }

        return new RequestControllerResult($success, $nextView, $jsonArray);
    }
}