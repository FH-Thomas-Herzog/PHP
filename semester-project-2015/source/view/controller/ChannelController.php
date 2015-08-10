<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/10/2015
 * Time: 1:08 PM
 */

namespace source\view\controller;


use source\common\AbstractRequestController;
use \source\db\controller\ChannelEntityController;
use source\common\InternalErrorException;
use source\view\model\RequestControllerResult;

class ChannelController extends AbstractRequestController
{

    public static $ACTION_SAVE_CHANNEL = "actionSaveChannel";

    public static $ACTION_TO_MAIN = "actionToMain";

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
            case self::$ACTION_TO_MAIN:
                return new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_CHANNELS);
            default:
                throw new InternalErrorException("Action with id: '" . $this->actionId . "' not supported by this handler: '" . __CLASS__ . "''");
        }
    }

    private function handleChannelSave()
    {

        $channelCtrl = new ChannelEntityController();
        $args = array(
            "title" => parent::getParameter("title"),
            "description" => parent::getParameter("description"),
            "favorite" => parent::getParameter("favorite")
        );

        if ($channelCtrl->isChannelExistingWithTitle($args["title"])) {
            $args = array(
                "message" => "A Channel with this title already exists",
                "messageType" => "warning",
                "description" => $args["description"]
            );
        } else {
            $args["userId"] = $this->securityCtrl->getLoggedUser();
            $res = $channelCtrl->persist($args);

            if ($res) {
                $args = array(
                    "message" => "Channel successfully saved",
                    "messageType" => "info"
                );
            } else {
                $args = array(
                    "message" => "Channel could not be saved",
                    "messageType" => "danger"
                );
            }
        }

        return new RequestControllerResult(true, ViewController::$PARTIAL_VIEW_NEW_CHANNEL, $args);
    }
}