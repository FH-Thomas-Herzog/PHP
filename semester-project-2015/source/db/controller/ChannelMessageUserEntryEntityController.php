<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 5:41 PM
 */

namespace source\db\controller;


use source\common\InternalErrorException;
use source\common\DbException;

class ChannelMessageUserEntryEntityController extends AbstractEntityController
{

    public static $SQL_INSERT_CHANNEL_MESSAGE_USER_ENTRY =
        " INSERT INTO channel_message_user_entry (user_id, channel_message_id, read_date, read_flag, important_flag) " .
        " VALUES (?,?,  (CASE WHEN (? = 1) THEN CURRENT_TIMESTAMP(6) ELSE NULL END), ?,?)";

    private static $SQL_UPDATE_USER_ENTRY_IMPORTANT_FLAG =
        " UPDATE channel_message_user_entry SET important_flag = ? " .
        " WHERE user_id = ? " .
        " AND channel_message_id = ? ";

    public function __construct()
    {
        parent::__construct();
    }

    public function getById($id)
    {
        // TODO: Implement getById() method.
    }

    public function deleteById($id)
    {
        throw new InternalErrorException("Deletion of channel_message_user_entry entries not allowed");
    }

    public function markMessageAsImportant($userId, $messageId, $importantFlag) {
        parent::open();

        $stmt = null;
        $result = null;
        $p1 = (integer)$importantFlag;
        $p2 = (integer)$userId;
        $p3 = (integer)$messageId;

        try {
            parent::startTx();

            $stmt = parent::prepareStatement(self::$SQL_UPDATE_USER_ENTRY_IMPORTANT_FLAG);
            $stmt->bind_param("iii", $p1, $p2, $p3);
            parent::startTx();
            $stmt->execute();
            $result = $stmt->affected_rows != 0;
            parent::commit();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query: '" . self::$SQL_UPDATE_USER_ENTRY_IMPORTANT_FLAG . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            parent::close();
        }

        return $result;
    }

    public function persist(array $args)
    {

        parent::open();

        $stmt = null;
        $result = null;
        $p1 = (integer)$args["userId"];
        $p2 = (integer)$args["channelMessageId"];
        $p3 = (!empty($args["markRead"])) ? (integer) $args["markRead"] : 0;
        $p4 = (!empty($args["readFlag"])) ? ((boolean)$args["readFlag"]) : 0;
        $p5 = (!empty($args["importantFlag"])) ? ((boolean)$args["importantFlag"]) : 0;

        try {
            parent::startTx();

            $stmt = parent::prepareStatement(self::$SQL_INSERT_CHANNEL_MESSAGE_USER_ENTRY);
            $stmt->bind_param("iiiii", $p1, $p2, $p3, $p4, $p5);
            parent::startTx();
            $stmt->execute();
            parent::commit();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query: '" . self::$SQL_INSERT_CHANNEL_MESSAGE_USER_ENTRY . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            parent::close();
        }
    }

    function update(array $args)
    {
        // TODO: Implement update() method.
    }
}