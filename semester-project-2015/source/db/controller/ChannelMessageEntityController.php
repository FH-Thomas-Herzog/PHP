<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 5:41 PM
 */

namespace source\db\controller;


use source\common\DbException;
use source\common\SecurityException;

class ChannelMessageEntityController extends AbstractEntityController
{

    private static $SQL_INSERT_CHANNEL_MESSAGE = "INSERT INTO channel_message (user_id, channel_id, message) VALUES (?,?,?)";

    private static $SQL_DELETE_CHANNEL_MESSAGE = "DELETE FROM channel_message WHERE id = ? ";

    private static $SQL_CHANNEL_MESSAGE_BY_ID = "SELECT * FROM channel_message WHERE id = ? ";

    private static $SQL_SELECT_MESSAGES_FOR_CHANNEL =
        " SELECT DATE(cm.creation_date) AS creation_date_date, TIME_FORMAT(TIME(cm.creation_date), '%H:%m') AS creation_date_time, cm.creation_date, cm.id, cm.message, cm.channel_id, cm.user_id, COALESCE(cme.read_flag, 0) AS read_flag, COALESCE(cme.important_flag, 0) AS important_flag, CASE WHEN (cm.user_id = ?) THEN 1 ELSE 0 END AS owned_flag, u.username  FROM channel_message cm " .
        " LEFT OUTER JOIN channel_message_user_entry cme ON (cme.channel_message_id = cm.id AND cme.user_id = ?) " .
        " INNER JOIN user u on u.id = cm.user_id " .
        " WHERE cm.channel_id = ? " .
        " ORDER BY creation_date_date ASC, creation_date_time ASC, important_flag DESC ";

    private static $SQL_SELECT_IMPORTANT_MESSAGES_FOR_CHANNEL =
        " SELECT DATE(cm.creation_date) AS creation_date_date, TIME_FORMAT(TIME(cm.creation_date), '%H:%m') AS creation_date_time, cm.creation_date, cm.id, cm.message, cm.channel_id, cm.user_id, COALESCE(cme.read_flag, 0) AS read_flag, COALESCE(cme.important_flag, 0) AS important_flag, CASE WHEN (cm.user_id = ?) THEN 1 ELSE 0 END AS owned_flag, u.username  FROM channel_message cm " .
        " INNER JOIN channel_message_user_entry cme ON (cme.channel_message_id = cm.id AND cme.user_id = ?) " .
        " INNER JOIN user u on u.id = cm.user_id " .
        " WHERE cm.channel_id = ? " .
        " AND cme.important_flag = 1 " .
        " ORDER BY creation_date_date DESC, creation_date_time DESC, important_flag DESC ";

    private static $SQL_DELETE_MESSAGE_USER_ENTRIES_FOR_MESSAGE =
        " DELETE FROM channel_message_user_entry " .
        " WHERE channel_message_id = ? ";

    private static $SQL_CHECK_FOR_FOLLOWING_MESSAGES =
        " SELECT DISTINCT channel_id FROM channel_message " .
        " WHERE id != ?" .
        " AND channel_id = ? " .
        " AND creation_date >= ? ";

    public function __construct()
    {
        parent::__construct();
    }

    public function getMessagesForChannel($channelId, $userId, $favoriteOnly = false)
    {
        parent::open();

        $stmt = null;
        $res = array();
        $stmtString = "";
        $p1 = (integer)$userId;
        $p2 = (integer)$channelId;

        try {
            // only favorite ones
            if ((boolean)$favoriteOnly) {
                $stmtString = self::$SQL_SELECT_IMPORTANT_MESSAGES_FOR_CHANNEL;
            } // all messages
            else {
                $stmtString = self::$SQL_SELECT_MESSAGES_FOR_CHANNEL;
            }
            $stmt = parent::prepareStatement($stmtString);
            $stmt->bind_param("iii", $p1, $p1, $p2);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = null;
            while ($data = $result->fetch_object()) {
                $res[] = $data;
            }
        } catch (\Exception $e) {
            throw new DbException("Error on executing query: '" . $stmtString . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            parent::close();
        }

        return $res;
    }

    public function getById($id)
    {
        // TODO: Implement getById() method.
    }

    public function deleteById($id)
    {
        // Not supported here
    }

    public function persist(array $args)
    {

        parent::open();

        $stmt = null;
        $result = null;
        $p1 = (integer)$args["userId"];
        $p2 = (integer)$args["channelId"];
        $p3 = (string)$args["message"];

        try {
            parent::startTx();

            $stmt = parent::prepareStatement(self::$SQL_INSERT_CHANNEL_MESSAGE);
            $stmt->bind_param("iis", $p1, $p2, $p3);
            parent::startTx();
            $stmt->execute();
            $result = $stmt->insert_id;
            parent::commit();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query: '" . self::$SQL_INSERT_CHANNEL_MESSAGE . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            parent::close();
        }

        return $result;
    }

    public function delete(array $args)
    {
        parent::open();

        $result = false;
        $stmtCheckFollowing = null;
        $stmtDeleteEntries = null;
        $stmtDeleteMessage = null;
        $stmtMessageById = null;
        $p1 = (integer)$args["messageId"];
        $p2 = (integer)$args["channelId"];
        $p3 = (string)$args["creationDate"];
        $p4 = (integer)$args["userId"];

        try {
            $stmtCheckFollowing = parent::prepareStatement(self::$SQL_CHECK_FOR_FOLLOWING_MESSAGES);
            $stmtCheckFollowing->bind_param("iis", $p1, $p2, $p3);
            parent::startTx();
            $stmtCheckFollowing->execute();
            // check if post have been followed
            if ($stmtCheckFollowing->get_result()->num_rows == 0) {
                // Check if message owned by user
                $stmtMessageById = parent::prepareStatement(self::$SQL_CHANNEL_MESSAGE_BY_ID);
                $stmtMessageById->bind_param("i", $p1);
                $stmtMessageById->execute();
                $data = $stmtMessageById->get_result()->fetch_object();
                // message found for id
                if (isset($data)) {
                    // message not owned by user
                    if ($data->user_id != $p4) {
                        throw new SecurityException("Message not owned by given user");
                    }
                    $stmtDeleteEntries = parent::prepareStatement(self::$SQL_DELETE_MESSAGE_USER_ENTRIES_FOR_MESSAGE);
                    $stmtDeleteEntries->bind_param("i", $p1);
                    $stmtDeleteEntries->execute();

                    $stmtDeleteMessage = parent::prepareStatement(self::$SQL_DELETE_CHANNEL_MESSAGE);
                    $stmtDeleteMessage->bind_param("i", $p1);
                    $stmtDeleteMessage->execute();

                    parent::commit();
                    $result = true;
                }
            }
        } catch (SecurityException $sec) {
            throw $sec;
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error deleting messages" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmtCheckFollowing)) {
                $stmtCheckFollowing->free_result();
                $stmtCheckFollowing->close();
            }
            if (isset($stmtDeleteEntries)) {
                $stmtDeleteEntries->close();
            }
            if (isset($stmtDeleteMessage)) {
                $stmtDeleteMessage->close();
            }
            if (isset($stmtMessageById)) {
                $stmtMessageById->free_result();
                $stmtMessageById->close();
            }
            parent::close();
        }

        return $result;
    }

    function update(array $args)
    {
        // TODO: Implement update() method.
    }
}