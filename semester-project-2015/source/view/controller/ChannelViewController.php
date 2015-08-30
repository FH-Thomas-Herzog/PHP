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

    /**
     * Handles the intended action which must be supported by this controller.
     *
     * @return null|RequestControllerResult the handled controller action result
     * @throws InternalErrorException if the controller does not support this action
     */
    public function handleAction()
    {
        $result = null;
        switch ($this->actionId) {
            case self::$ACTION_SAVE_CHANNEL:
                $result = $this->handleChannelSave();
                break;
            case self::$ACTION_TO_CHANNELS:
                $result = $this->handleToChannelsAction();
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
                $result = $this->handleToSelectedChannelAction();
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

    /**
     * Prepares the controller supported view with the intended given view id.
     *
     * @param string $nextView the intended next view which must be suippported by this controller
     * @return array the array holding the template arguments
     * @throws InternalErrorException if this controller does not support the intended view.
     */
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
                    "actionToChannels" => MainViewController::$ACTION_TO_CHANNELS
                );
                break;
            case ViewController::$PARTIAL_VIEW_CHANNEL:
                $args = $this->prepareChannelView();
                break;
            case ViewController::$PARTIAL_VIEW_CHANNEL_CHAT:
                $args = $this->prepareChannelView();
                break;
            default:
                throw new InternalErrorException("View: '" . $nextView . " not supported by this controller: '" . __CLASS__ . "'");
        }

        return $args;
    }

    // #########################################################################
    // Private prepare view functions
    // #########################################################################
    /**
     * Prepares the channels view.
     *
     * @return array the array holding the twig template arguments
     */
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
                "availableChannels" => $unassigned,
                "recreateTemplate" => true
            );
        } catch (DbException $e) {
            var_dump($e);
        }
    }

    /**
     * Prepares the partialChannel or partialChannelChat views which needs the same twig template arguments.
     *
     * @return array the array holding the twig template arguments
     * @throws InternalErrorException
     */
    private function prepareChannelView()
    {
        $result = array();

        $channelId = (integer)parent::getParameter("channelId");
        $favoriteOnly = (!empty(parent::getParameter("favoriteOnly"))) ? ((boolean)parent::getParameter("favoriteOnly")) : false;

        $channelCtrl = new ChannelEntityController();
        $channelMessageCtrl = new ChannelMessageEntityController();

        try {
            $channel = $channelCtrl->getById($channelId);
            $messages = $channelMessageCtrl->getMessagesForChannel($channelId, $this->securityCtrl->getLoggedUser(), $favoriteOnly);
            $viewMessages = array();
            $dateMessages = array();
            $dateIdx = 0;
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
                    // new date found after first item
                    if (($idx != 0) && (!StringUtil::compare($oldDate, $actualDate))) {
                        $dateMessages[$dateIdx] = $message;
                        $viewMessages[$oldDate] = $dateMessages;
                        $dateMessages = array();
                        $dateIdx = 0;

                    } // still on actual date on not last item
                    else if ($idx < $upperBorder) {
                        $dateMessages[$dateIdx] = $message;
                        $dateIdx++;
                    }

                    // If last item reached
                    if ($idx == $upperBorder) {
                        $dateMessages[$dateIdx] = $message;
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
                "favoriteOnly" => $favoriteOnly,
                "recreateTemplate" => true
            );
        } catch (DbException $e) {
            var_dump($e);
        }

        return $result;
    }

    // #########################################################################
    // Private action handle functions
    // #########################################################################
    /**
     * Handles the to selected channel action which determines between a initial call which will return view 'partialChannels'
     * or an refresh action which will return view 'partialChannelChat'.
     *
     * @return RequestControllerResult the action result
     */
    private function handleToSelectedChannelAction()
    {
        $nextView = null;
        $success = false;
        $refresh = (boolean)parent::getParameter("refresh");
        $channelId = (integer)parent::getParameter("channelId");

        // check if channel exists
        $jsonArray = $this->checkForExistingChannelById($channelId);
        if (!isset($jsonArray)) {
            $nextView = ($refresh) ? ViewController::$PARTIAL_VIEW_CHANNEL_CHAT : ViewController::$PARTIAL_VIEW_CHANNEL;
            $success = true;
            $jsonArray = array(
                "error" => false
            );
        } // handle when refresh
        else if ($refresh) {
            $jsonArray["redirectUrl"] = "/public/start.php?viewId=" . ViewController::$VIEW_MAIN . "&actionId=" . ViewController::$REFRESH_ACTION;
            $jsonArray["additionalMessage"] = "You will be redirected to channels view in 3 seconds";
            $jsonArray["delay"] = 3000;
        } // handle when initial call
        else {
            $nextView = ViewController::$PARTIAL_VIEW_CHANNELS;
        }

        // check for existing channels
        $jsonArrayTmp = $this->checkForExistingChannels();
        if (isset($jsonArrayTmp)) {
            $jsonArray = $jsonArrayTmp;
            $nextView = ViewController::$PARTIAL_VIEW_NEW_CHANNEL;
        }

        return new RequestControllerResult($success, $nextView, $jsonArray);
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

    /**
     * Handles the edit message action.
     *
     * @return RequestControllerResult the action result
     */
    private function handleEditMessage()
    {
        $jsonArray = null;
        $success = null;

        $nextView = null;
        $userId = $this->securityCtrl->geTLoggedUser();
        $messageId = (integer)parent::getParameter("pk");
        $message = (string)parent::getParameter("value");
        $channelId = (string)parent::getParameter("channelId");
        $creationDate = (string)parent::getParameter("creationDate");

        $channelMessageCtrl = new ChannelMessageEntityController();

        try {
            $jsonArray = $this->checkForExistingChannelById($channelId);
            if (!isset($jsonArray)) {
                $success = $channelMessageCtrl->update(array(
                    "userId" => $userId,
                    "messageId" => $messageId,
                    "message" => $message,
                    "channelId" => $channelId,
                    "creationDate" => $creationDate
                ));
                if ($success) {
                    $jsonArray = array(
                        "error" => false
                    );
                } else {
                    $jsonArray = array(
                        "error" => true,
                        "message" => "Could not update message",
                        "additionalMessage" => "Either message does ot exist anymore or a following message has been posted.",
                        "messageType" => "warning",
                    );
                }
                $nextView = ViewController::$PARTIAL_VIEW_CHANNEL_CHAT;
            } else {
                $jsonArray["redirectUrl"] = "/public/start.php?viewId=" . ViewController::$VIEW_MAIN . "&actionId=" . ViewController::$REFRESH_ACTION;
                $jsonArray["additionalMessage"] = "You will be redirected to channels view in 3 seconds";
                $jsonArray["delay"] = 3000;
            }
        } catch (\Exception $e) {
            $jsonArray = array(
                "error" => true,
                "message" => "Could not delete message. If this error keeps showing up, please notify the administrator",
                "additionalMessage" => $e->getMessage(),
                "messageType" => "danger",
            );
        }

        return new RequestControllerResult($success, $nextView, $jsonArray);
    }

    /**
     * Handles the set favorite message action.
     *
     * @return RequestControllerResult the action result
     */
    private function handleSetFavoriteMessage()
    {
        $jsonArray = null;
        $success = false;

        $nextView = null;
        $userId = $this->securityCtrl->geTLoggedUser();
        $messageId = (integer)parent::getParameter("messageId");
        $importantFlag = (integer)parent::getParameter("importantFlag");
        $channelId = (integer)parent::getParameter("channelId");

        $channelMessageUserEntryCtrl = new ChannelMessageUserEntryEntityController();

        try {
            // Check if channel exists
            $jsonArray = $this->checkForExistingChannelById($channelId);
            if (!isset($jsonArray)) {
                $success = $channelMessageUserEntryCtrl->markMessageAsImportant($userId, $messageId, $importantFlag);
                if ($success) {
                    $jsonArray = array(
                        "error" => false
                    );
                } else {
                    $jsonArray = array(
                        "error" => true,
                        "refresh" => true,
                        "message" => "Could not set favorite message",
                        "additionalMessage" => "Message was deleted",
                        "messageType" => "warning"
                    );
                }
                $nextView = ViewController::$PARTIAL_VIEW_CHANNEL_CHAT;
            } else {
                $jsonArray["redirectUrl"] = "/public/start.php?viewId=" . ViewController::$VIEW_MAIN . "&actionId=" . ViewController::$REFRESH_ACTION;
                $jsonArray["additionalMessage"] = "You will be redirected to channels view in 3 seconds";
                $jsonArray["delay"] = 3000;
            }
        } catch (\Exception $e) {
            $jsonArray = array(
                "error" => true,
                "message" => "Could mark message as favorite. If this error keeps showing up, please notify the administrator",
                "additionalMessage" => $e->getMessage(),
                "messageType" => "danger"
            );
        }

        return new RequestControllerResult($success, $nextView, $jsonArray);
    }

    /**
     * Handles the delete action.
     *
     * @return RequestControllerResult the action result
     */
    private function handleDeleteMessage()
    {
        $result = null;
        $jsonArray = null;
        $success = false;

        $nextView = null;
        $userId = $this->securityCtrl->geTLoggedUser();
        $messageId = (integer)parent::getParameter("messageId");
        $channelId = (integer)parent::getParameter("channelId");
        $creationDate = (string)parent::getParameter("creationDate");

        $channelMessageCtrl = new ChannelMessageEntityController();
        try {
            // Check if channel exists
            $jsonArray = $this->checkForExistingChannelById($channelId);
            if (!isset($jsonArray)) {
                $success = $channelMessageCtrl->delete(array(
                    "userId" => $userId,
                    "messageId" => $messageId,
                    "channelId" => $channelId,
                    "creationDate" => $creationDate
                ));
                if (!$success) {
                    $jsonArray = array(
                        "error" => true,
                        "message" => "Could not delete message.",
                        "additionalMessage" => "Message is deleted or there are following messages",
                        "messageType" => "warning"
                    );
                } else {
                    $jsonArray = array(
                        "error" => false
                    );
                }
                $nextView = ViewController::$PARTIAL_VIEW_CHANNEL_CHAT;
            } else {
                $jsonArray["redirectUrl"] = "/public/start.php?viewId=" . ViewController::$VIEW_MAIN . "&actionId=" . ViewController::$REFRESH_ACTION;
                $jsonArray["additionalMessage"] = "You will be redirected to channels view in 3 seconds";
                $jsonArray["delay"] = 3000;
            }
        } catch (DbException $e) {
            $jsonArray = array(
                "message" => "Could not delete message. If this error keeps showing up, please notify the administrator",
                "additionalMessage" => $e->getMessage(),
                "messageType" => "danger"
            );
        }

        return new RequestControllerResult($success, $nextView, $jsonArray);
    }

    /**
     * Handles the post message action.
     *
     * @return RequestControllerResult the action result
     */
    private function handlePostMessage()
    {
        $result = null;
        $jsonArray = null;
        $success = false;
        $nextView = null;
        $userId = $this->securityCtrl->getLoggedUser();
        $channelId = (integer)parent::getParameter("channelId");
        $msg = (string)parent::getParameter("message");

        $channelMessageCtrl = new ChannelMessageEntityController();
        try {
            // Check if channel exists
            $jsonArray = $this->checkForExistingChannelById($channelId);
            if (!isset($jsonArray)) {
                $channelMessageCtrl->persist(array(
                    "userId" => $userId,
                    "channelId" => $channelId,
                    "message" => $msg,
                    "readFlag" => 1,
                    "importantFlag" => 0,
                    "markRead" => 1
                ));
                $jsonArray = array(
                    "error" => false
                );
                $nextView = ViewController::$PARTIAL_VIEW_CHANNEL_CHAT;
            } else {
                $jsonArray["redirectUrl"] = "/public/start.php?viewId=" . ViewController::$VIEW_MAIN . "&actionId=" . ViewController::$REFRESH_ACTION;
                $jsonArray["additionalMessage"] = "You will be redirected to channels view in 3 seconds";
                $jsonArray["delay"] = 3000;
            }
        } catch (DbException $e) {
            $jsonArray = array(
                "message" => "Could not post message. If this error keeps showing up, please notify the administrator",
                "additionalMessage" => $e->getMessage(),
                "messageType" => "danger"
            );
            $success = false;
        }

        return new RequestControllerResult($success, $nextView, $jsonArray);
    }

    /**
     * Handles the set favorite channel action which marks an channel s a user favorite.
     *
     * @return null|RequestControllerResult the action result.
     * @throws \source\db\controller\DbException
     */
    private function handleSetFavoriteChannel()
    {
        $jsonArray = null;
        $success = false;
        $channelId = (integer)parent::getParameter("channelId");
        $nextView = null;

        $channelUserEntryCtrl = new ChannelUserEntryEntityController();

        $result = null;
        try {
            // check if channel exists
            $jsonArray = $this->checkForExistingChannelById($channelId);
            if (!isset($jsonArray)) {
                $success = $channelUserEntryCtrl->update(array(
                    "favoriteFlag" => 1,
                    "userId" => $this->securityCtrl->getLoggedUser(),
                    "channelId" => $channelId
                ));
                if ($success) {
                    $jsonArray = array(
                        "error" => false,
                        "channelId" => $channelId
                    );
                } else {
                    $jsonArray = array(
                        "error" => true,
                        "message" => "Could not set favorite channel",
                        "additionalMessage" => "Assignment does not exist anymore",
                        "messageType" => "warning"
                    );
                }
                $nextView = ViewController::$PARTIAL_VIEW_CHANNELS;
            }
            // Check if channels still exist
            $jsonArrayTmp = $this->checkForExistingChannels();
            if (isset($jsonArrayTmp)) {
                $jsonArray = $jsonArrayTmp;
                $nextView = ViewController::$PARTIAL_VIEW_NEW_CHANNEL;
            }
        } catch (DbException $e) {
            $jsonArray = array(
                "error" => true,
                "message" => "Could not save favorite channel. If this error keeps showing up, please notify the administrator",
                "additionalMessage" => $e->getMessage(),
                "messageType" => "danger"
            );
        }

        return new RequestControllerResult($success, $nextView, $jsonArray);
    }

    /**
     * This function handles the remove assignment action where an channel assignment will be removed from this user.
     *
     * @return RequestControllerResult the handled action result
     * @throws \source\db\controller\DbException
     */
    private function handleRemoveAssignedChannel()
    {
        $jsonArray = null;
        $success = false;
        $channelId = (integer)parent::getParameter("channelId");
        $nextView = null;

        $channelUserEntryCtrl = new ChannelUserEntryEntityController();

        try {
            // check if channel exists
            $jsonArray = $this->checkForExistingChannelById($channelId);
            if (!isset($jsonArray)) {
                $success = (boolean)$channelUserEntryCtrl->deleteById(array(
                    "userId" => (integer)$this->securityCtrl->getLoggedUser(),
                    "channelId" => $channelId
                ));
                if ($success) {
                    $jsonArray = array(
                        "error" => false
                    );
                } else {
                    $jsonArray = array(
                        "error" => true,
                        "message" => "Assignment already removed",
                        "messageType" => "warning"
                    );
                }
                $nextView = ViewController::$PARTIAL_VIEW_CHANNELS;
            }
            // Check if channels still exist
            $jsonArrayTmp = $this->checkForExistingChannels();
            if (isset($jsonArrayTmp)) {
                $jsonArray = $jsonArrayTmp;
                $nextView = ViewController::$PARTIAL_VIEW_NEW_CHANNEL;
            }
        } catch (DbException $e) {
            $jsonArray = array(
                "error" => true,
                "message" => "Could not delete assigned channel. If this error keeps showing up, please notify the administrator",
                "messageType" => "danger",
                "additionalMessage" => $e->getMessage()
            );
        }

        return new RequestControllerResult($success, $nextView, $jsonArray);
    }

    /**
     * Handles the assign channel action which assigns an channel to the current logged user.
     *
     * @return RequestControllerResult the action result
     * @throws \source\db\controller\DbException
     */
    private function handleAssignChannel()
    {
        $jsonArray = null;
        $success = false;
        $channelId = (integer)parent::getParameter("channelId");
        $nextView = null;

        $channelUserEntryCtrl = new ChannelUserEntryEntityController();
        $channelCtrl = new ChannelEntityController();

        try {
            // check if channel exists
            $jsonArray = $this->checkForExistingChannelById($channelId);
            if (!isset($jsonArray)) {
                // channel already assigned to user
                if ($channelUserEntryCtrl->getById(array(
                        "userId" => $this->securityCtrl->geTLoggedUser(),
                        "channelId" => $channelId
                    )) != null
                ) {
                    $jsonArray = array(
                        "error" => true,
                        "message" => "Assignment already exists",
                        "messageType" => "warning"
                    );
                } else {
                    $channelUserEntryCtrl->persist(array(
                        "userId" => (integer)$this->securityCtrl->getLoggedUser(),
                        "channelId" => $channelId,
                        "favorite" => 0
                    ));
                    $success = true;
                    $jsonArray = array(
                        "error" => false,
                        "channelId" => $channelId
                    );
                }
                $nextView = ViewController::$PARTIAL_VIEW_CHANNELS;
            }
            // Check if channels still exist
            $jsonArrayTmp = $this->checkForExistingChannels();
            if (isset($jsonArrayTmp)) {
                $jsonArray = $jsonArrayTmp;
                $nextView = ViewController::$PARTIAL_VIEW_NEW_CHANNEL;
            }
        } catch (\Exception $e) {
            $jsonArray = array(
                "error" => true,
                "message" => "Could not assign you to channel. If this error keeps showing up, please notify the administrator",
                "messageType" => "danger",
                "additionalMessage" => $e->getMessage()
            );
        }

        return new RequestControllerResult($success, $nextView, $jsonArray);
    }

    /**
     * This function handles the save channel action which saves an channel for an user.
     * The newly created channel will implicitly assigned to the creating user.
     *
     * @return RequestControllerResult the handled action result
     * @throws \source\db\controller\DbException
     */
    private function handleChannelSave()
    {
        $jsonArray = null;
        $channelCtrl = new ChannelEntityController();
        $title = parent::getParameter("title");
        $success = false;

        try {
            if ($channelCtrl->isChannelExistingWithTitle($title)) {
                $jsonArray = array(
                    "error" => true,
                    "message" => "A Channel with this title already exists",
                    "messageType" => "warning",
                    "additionalMessage" => "title: '" . $title . "'"
                );
            } else {
                $channelCtrl->persist(array(
                    "title" => $title,
                    "description" => parent::getParameter("description"),
                    "userId" => $this->securityCtrl->getLoggedUser(),
                    "favorite" => parent::getParameter("favorite")
                ));
                $jsonArray = array(
                    "error" => false,
                    "message" => "Channel successfully saved",
                    "additionalMessage" => "Go to channels to get to the channel or create a new one",
                    "messageType" => "info"
                );
                $success = true;
            }
        } catch (\Exception $e) {
            $jsonArray = array(
                "error" => true,
                "message" => "Sorry an database error occurred." . PHP_EOL . ". If this error keeps showing up, please notify the administrator",
                "messageType" => "danger",
                "additionalMessage" => $e->getMessage());
        }

        return new RequestControllerResult($success, ViewController::$PARTIAL_VIEW_NEW_CHANNEL, $jsonArray);
    }

    // #########################################################################
    // Private helper functions
    // #########################################################################
    /**
     * Helper to check if a channel with the given id exists on the database.
     *
     * @param integer $id the channel id
     * @return array|null the json array which is null if channel exists with the given id, otherwise it contains the json result
     */
    private function checkForExistingChannelById($id)
    {
        $channelId = (integer)$id;
        $jsonArray = null;
        try {
            if ((new ChannelEntityController())->getById($channelId) == null) {
                $jsonArray = array(
                    "message" => "Channel does not exist anymore",
                    "messageType" => "warning"
                );
            };
        } catch (\Exception $e) {
            $jsonArray = array(
                "error" => true,
                "message" => "Sorry an database error occurred." . PHP_EOL . ". If this error keeps showing up, please notify the administrator",
                "messageType" => "danger",
                "additionalMessage" => $e->getMessage());
        }

        return $jsonArray;
    }

    /**
     * Checks if at least one channel still exists.
     * @return array|null the json array which is null in case at least one channel still exists,
     * otherwise it contains the json result
     */
    private function checkForExistingChannels()
    {
        $jsonArray = null;
        try {
            if (!(new ChannelEntityController())->checkIfChannelAreExisting()) {
                $jsonArray = array(
                    "error" => true,
                    "message" => "No channels exist anymore",
                    "additionalMessage" => "Please create one",
                    "messageType" => "warning"
                );
            }
        } catch (\Exception $e) {
            $jsonArray = array(
                "error" => true,
                "message" => "Could not delete assigned channel. If this error keeps showing up, please notify the administrator",
                "messageType" => "danger",
                "additionalMessage" => $e->getMessage()
            );
        }

        return $jsonArray;
    }
}