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

    public static $ACTION_SAVE_CHANNEL = "actionSaveChannel";

    public static $ACTION_TO_CHANNELS = "actionToChannels";

    public function __construct()
    {
        parent::__construct();
    }

    public function handleRequest()
    {
        parent::handleRequest();

        switch ($this->actionId) {
            case self::$ACTION_SAVE_CHANNEL:
                return $this->handleChannelSave();
            case self::$ACTION_TO_CHANNELS:
                return new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_CHANNELS);
            default:
                throw new InternalErrorException("Action with id: '" . $this->actionId . "' not supported by this handler: '" . __CLASS__ . "''");
        }
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