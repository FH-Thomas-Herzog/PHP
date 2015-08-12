<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/10/2015
 * Time: 1:08 PM
 */

namespace source\view\controller;


use source\common\AbstractRequestController;
use source\common\DbException;
use source\common\InternalErrorException;
use source\db\controller\ChannelEntityController;
use source\db\controller\ChannelUserEntryEntityController;
use source\view\model\RequestControllerResult;

class ChannelController extends AbstractRequestController
{

    /* Action specification */
    public static $ACTION_SAVE_CHANNEL = "actionSaveChannel";

    public static $ACTION_TO_CHANNELS = "actionToChannels";

    public static $ACTION_SET_FAVORITE_CHANNEL = "actionSetFavoriteChannel";

    public static $ACTION_ASSIGN_CHANNEL = "actionAssignChannel";

    public static $ACTION_REMOVE_ASSIGNED_CHANNEL = "actionRemoveAssignedChannel";

    public static $ACTION_TO_SELECTED_CHANNEL = "actionToSelectedChannel";

    public function __construct()
    {
        parent::__construct();
    }

    public function handleAction()
    {
        $result = null;
        switch ($this->actionId) {
            case self::$ACTION_SAVE_CHANNEL:
                $result = $this->handleChannelSave();
                break;
            case self::$ACTION_TO_CHANNELS:
                $result = new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_CHANNELS);
                break;
            case self::$ACTION_SET_FAVORITE_CHANNEL:
                $result = $this->handleSetFavoriteChannel();
                break;
            case self::$ACTION_REMOVE_ASSIGNED_CHANNEL:
                $result = $this->handleRemoveAssignedChannel();
                break;
            case self::$ACTION_ASSIGN_CHANNEL:
                $result = $this->handleAssignChannel();
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
            case ViewController::$PARTIAL_VIEW_CHANNELS:
                $args = $this->prepareChannelsView();
                break;
            case ViewController::$PARTIAL_VIEW_NEW_CHANNEL:
                $args = array(
                    "actionSaveChannel" => ChannelController::$ACTION_SAVE_CHANNEL,
                    "actionToMain" => MainController::$ACTION_TO_CHANNELS
                );
                break;
            default:
                throw new InternalErrorException("View: '" . $nextView . " not supported by this controller: '" . __CLASS__ . "'");
        }

        return $args;
    }

    private function prepareChannelsView()
    {
        $channelCtrl = new ChannelEntityController();
        try {
            $assigned = $channelCtrl->getAssignedChannelsWithMsgCount($this->securityCtrl->getLoggedUser());
            $unassigned = $channelCtrl->getUnassignedChannels($this->securityCtrl->getLoggedUser());
            return array(
                "actionSaveFavoriteChannel" => self::$ACTION_SET_FAVORITE_CHANNEL,
                "actionRemoveAssignedChannel" => self::$ACTION_REMOVE_ASSIGNED_CHANNEL,
                "actionToSelectedChannel" => self::$ACTION_TO_SELECTED_CHANNEL,
                "actionAssignChannel" => self::$ACTION_ASSIGN_CHANNEL,
                "assignedChannels" => $assigned,
                "availableChannels" => $unassigned
            );
        } catch (DbException $e) {
            var_dump($e);
        }
    }

    private function handleSetFavoriteChannel()
    {
        $channelUserEntryCtrl = new ChannelUserEntryEntityController();
        $channelId = (integer)parent::getParameter("channelId");

        $result = null;
        try {
            $channelUserEntryCtrl->setFavoriteChannel((integer)$this->securityCtrl->getLoggedUser(), $channelId);
            $result = new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_CHANNELS);
        } catch (DbException $e) {
            $result = new RequestControllerResult(false, ViewController::$PARTIAL_VIEW_CHANNELS, array(
                "message" => "Could not save favorite channel. If this error keeps showing up, please notify the administrator",
                "messageType" => "danger"
            ));
        }

        return $result;
    }

    private function handleRemoveAssignedChannel()
    {
        $channelUserEntryCtrl = new ChannelUserEntryEntityController();

        $result = null;
        try {
            $channelUserEntryCtrl->deleteById(array(
                "userId" => (integer)$this->securityCtrl->getLoggedUser(),
                "channelId" => (integer)parent::getParameter("channelId")
            ));
            $result = new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_CHANNELS);
        } catch (DbException $e) {
            $result = new RequestControllerResult(false, ViewController::$PARTIAL_VIEW_CHANNELS, array(
                "message" => "Could not delete assigned channel. If this error keeps showing up, please notify the administrator",
                "messageType" => "danger"
            ));
        }

        return $result;
    }


    private function handleAssignChannel()
    {
        $channelUserEntryCtrl = new ChannelUserEntryEntityController();

        $result = null;
        try {
            $channelUserEntryCtrl->persist(array(
                "userId" => (integer)$this->securityCtrl->getLoggedUser(),
                "channelId" => (integer)parent::getParameter("channelId"),
                "favorite" => 0
            ));
            $result = new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_CHANNELS);
        } catch (DbException $e) {
            $result = new RequestControllerResult(false, ViewController::$PARTIAL_VIEW_CHANNELS, array(
                "message" => "Could not assign channel. If this error keeps showing up, please notify the administrator",
                "messageType" => "danger"
            ));
        }

        return $result;
    }

    private function handleChannelSave()
    {

        $channelCtrl = new ChannelEntityController();
        $channelUserEntryCtrl = new ChannelUserEntryEntityController();
        $args = array(
            "title" => parent::getParameter("title"),
            "description" => parent::getParameter("description")
        );
        $channelId = null;

        try {
            if ($channelCtrl->isChannelExistingWithTitle($args["title"])) {
                $args = array(
                    "message" => "A Channel with this title already exists",
                    "messageType" => "warning",
                    "description" => $args["description"]
                );
            } else {
                $channelId = $channelCtrl->persist(array(
                    "title" => $args["title"],
                    "description" => $args["description"]
                ));
                $channelUserEntryCtrl->persist(array(
                    "userId" => $this->securityCtrl->getLoggedUser(),
                    "channelId" => $channelId,
                    "favorite" => parent::getParameter("favorite")
                ));
                $args = array(
                    "message" => "Channel successfully saved",
                    "messageType" => "info"
                );
            }
        } catch (DbException $e) {
            try {
                if (isset($channelId)) {
                    $channelCtrl->delete($channelId);
                    $channelUserEntryCtrl->delete(array(
                        "userId" => $this->securityCtrl->getLoggedUser(),
                        "channelId" => $channelId
                    ));
                }
            } catch (DbException $e2) {
            }
            $args["message"] = "Sorry an database error occurred." . PHP_EOL . ". If this error keeps showing up, please notify the administrator";
            $args["messageType"] = "danger";
        }


        return new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_NEW_CHANNEL, $args);
    }
}