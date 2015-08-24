<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/10/2015
 * Time: 1:08 PM
 */

namespace source\view\controller;


use source\common\AbstractViewController;
use source\common\DbException;
use source\common\InternalErrorException;
use source\common\utils\StringUtil;
use source\db\controller\ChannelEntityController;
use source\db\controller\ChannelMessageEntityController;
use source\db\controller\ChannelMessageUserEntryEntityController;
use source\db\controller\ChannelUserEntryEntityController;
use source\view\model\RequestControllerResult;
use source\view\model\SimpleJsonResult;

class ChannelViewController extends AbstractViewController
{

    /* Action specification */
    public static $ACTION_SAVE_CHANNEL = "actionSaveChannel";

    public static $ACTION_TO_CHANNELS = "actionToChannels";

    public static $ACTION_SET_FAVORITE_CHANNEL = "actionSetFavoriteChannel";

    public static $ACTION_ASSIGN_CHANNEL = "actionAssignChannel";

    public static $ACTION_REMOVE_ASSIGNED_CHANNEL = "actionRemoveAssignedChannel";

    public static $ACTION_TO_SELECTED_CHANNEL = "actionToSelectedChannel";

    public static $ACTION_POST_MESSAGE = "actionPostMessage";

    public static $ACTION_DELETE_MESSAGE = "actionDeleteMessage";

    public static $ACTION_EDIT_MESSAGE = "actionEditMessage";

    public static $ACTION_SET_IMPORTANT_MESSAGE = "actionSetImportantMessage";

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
            case self::$ACTION_TO_SELECTED_CHANNEL:
                $result = new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_CHANNEL);
                break;
            case self::$ACTION_POST_MESSAGE:
                $result = $this->handlePostMessage();
                break;
            case self::$ACTION_DELETE_MESSAGE:
                $result = $this->handleDeleteMessage();
                break;
            case self::$ACTION_SET_IMPORTANT_MESSAGE:
                $result = $this->handleSetFavoriteMessage();
                break;
            case self::$ACTION_EDIT_MESSAGE:
                $result = $this->handleEditMessage();
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
                    "actionSaveChannel" => ChannelViewController::$ACTION_SAVE_CHANNEL,
                    "actionToMain" => MainViewController::$ACTION_TO_CHANNELS
                );
                break;
            case ViewController::$PARTIAL_VIEW_CHANNEL:
                $args = $this->prepareChannelView();
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

    private function prepareChannelView()
    {
        $result = array();

        $channelId = (integer)parent::getParameter("channelId");
        $favoriteOnly = (!empty(parent::getParameter("favoriteOnly"))) ? ((boolean)parent::getParameter("favoriteOnly")) : false;
        $channelCtrl = new ChannelEntityController();
        $channelMessageCtrl = new ChannelMessageEntityController();
        $channelMessageUserEntryCtrl = new ChannelMessageUserEntryEntityController();
        try {
            $channel = $channelCtrl->getById($channelId);
            $messages = $channelMessageCtrl->getMessagesForChannel($channelId, $this->securityCtrl->getLoggedUser(), $favoriteOnly);
            $viewMessages = array();
            $dateMessages = array();
            $timeMessages = array();
            $timeIdx = 0;
            $oldTime = "";
            $actualTime = "";
            $oldDate = "";
            $actualDate = "";
            $upperBorder = (count($messages) - 1);
            // only if messages are present
            if ($upperBorder >= 0) {
                // build map of date => array of messages on this date
                for ($idx = 0; $idx <= $upperBorder; $idx++) {
                    $message = $messages[$idx];
                    // save old set date
                    $oldDate = $actualDate;
                    // get new date
                    $actualDate = $message->creation_date_date;
                    // save old time
                    $oldTime = $actualTime;
                    // get actual time
                    $actualTime = $message->creation_date_time;
                    // new date found after first item
                    if (($idx != 0) && (!StringUtil::compare($oldDate, $actualDate))) {
                        $dateMessages[$actualTime] = $timeMessages;
                        $viewMessages[$oldDate] = $dateMessages;
                        $timeMessages = array();
                        $dateMessages = array();
                        $timeIdx = 0;
                        $timeMessages[$timeIdx] = $message;
                        $timeIdx++;

                    } // still on actual date
                    else {
                        if (($idx != 0) && (!StringUtil::compare($oldTime, $actualTime))) {
                            $dateMessages[$oldTime] = $timeMessages;
                            $timeIdx = 0;
                            $timeMessages[$timeIdx] = $message;
                            $timeIdx++;
                        } else {
                            $timeMessages[$timeIdx] = $message;
                            $timeIdx++;
                        }
                    }

                    // If last item reached
                    if ($idx == $upperBorder) {
                        $dateMessages[$actualTime] = $timeMessages;
                        $viewMessages[$actualDate] = $dateMessages;
                    }
                }
            }

            $result = array(
                "actionPostMessage" => self::$ACTION_POST_MESSAGE,
                "actionDeleteMessage" => self::$ACTION_DELETE_MESSAGE,
                "actionEditMessage" => self::$ACTION_EDIT_MESSAGE,
                "actionToSelectedChannel" => self::$ACTION_TO_SELECTED_CHANNEL,
                "actionSetImportantMessage" => self::$ACTION_SET_IMPORTANT_MESSAGE,
                "channel" => $channel,
                "messages" => $viewMessages,
                "favoriteOnly" => $favoriteOnly
            );
        } catch (DbException $e) {
            var_dump($e);
        }

        return $result;
    }

    private function handleEditMessage()
    {

        $result = new RequestControllerResult(false);

        $userId = $this->securityCtrl->geTLoggedUser();
        $messageId = (integer)parent::getParameter("pk");
        $message = (string)parent::getParameter("value");
        $channelMessageCtrl = new ChannelMessageEntityController();

        try {
            $updated = $channelMessageCtrl->update(array(
                "userId" => $userId,
                "messageId" => $messageId,
                "message" => $message
            ));
            if (!$updated) {
                http_response_code(500);
            } else {
                $result->args = array(
                    "successful" => true
                );
            }
        } catch (DbException $e) {
            $result->args = array(
                "error" => true,
                "refresh" => true
            );
        }

        return $result;
    }

    private function handleSetFavoriteMessage()
    {
        $result = new RequestControllerResult(false);

        $userId = $this->securityCtrl->geTLoggedUser();
        $messageId = (integer)parent::getParameter("messageId");
        $importantFlag = (integer)parent::getParameter("importantFlag");
        $channelMessageUserEntryCtrl = new ChannelMessageUserEntryEntityController();

        try {
            $updated = $channelMessageUserEntryCtrl->markMessageAsImportant($userId, $messageId, $importantFlag);
            if (!$updated) {
                $result->args = array(
                    "refresh" => true
                );
            } else {
                $result->args = array(
                    "successful" => true
                );
            }
        } catch (DbException $e) {
            $result->args = array(
                "error" => true,
                "refresh" => true
            );
        }

        return $result;
    }

    private function handleDeleteMessage()
    {
        $result = null;

        $userId = $this->securityCtrl->geTLoggedUser();
        $messageId = (integer)parent::getParameter("messageId");
        $channelId = (integer)parent::getParameter("channelId");
        $creationDate = (string)parent::getParameter("creationDate");

        $channelMessageCtrl = new ChannelMessageEntityController();
        try {
            $deleted = $channelMessageCtrl->delete(array(
                "userId" => $userId,
                "messageId" => $messageId,
                "channelId" => $channelId,
                "creationDate" => $creationDate
            ));
            if (!$deleted) {
                $result = new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_CHANNEL, array(
                    "message" => "Could not delete message because answers have already been posted",
                    "messageType" => "warning"
                ));
            } else {
                $result = new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_CHANNEL);
            }
        } catch (DbException $e) {
            $result = new RequestControllerResult(false, ViewController::$PARTIAL_VIEW_CHANNELS, array(
                "message" => "Could not delete message. If this error keeps showing up, please notify the administrator",
                "messageType" => "danger"
            ));
        }

        return $result;
    }

    private function handlePostMessage()
    {
        $result = null;

        $userId = $this->securityCtrl->getLoggedUser();
        $channelId = (integer)parent::getParameter("channelId");
        $msg = (string)parent::getParameter("message");

        $channelMessageCtrl = new ChannelMessageEntityController();
        try {
            $channelMessageCtrl->persist(array(
                "userId" => $userId,
                "channelId" => $channelId,
                "message" => $msg,
                "readFlag" => 1,
                "importantFlag" => 0,
                "markRead" => 1
            ));
            $result = new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_CHANNEL);
        } catch (DbException $e) {
            $result = new RequestControllerResult(false, ViewController::$PARTIAL_VIEW_CHANNEL, array(
                "message" => "Could not post message. If this error keeps showing up, please notify the administrator",
                "messageType" => "danger"
            ));
        }

        return $result;
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