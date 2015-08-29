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

    private static $SQL_UPDATE_MESSAGE =
        " UPDATE channel_message SET message=?, updated_date = CURRENT_TIMESTAMP(6) " .
        " WHERE id = ? " .
        " AND user_id = ? ";

    private static $SQL_DELETE_CHANNEL_MESSAGE = "DELETE FROM channel_message WHERE id = ? ";

    private static $SQL_CHANNEL_MESSAGE_BY_ID = "SELECT * FROM channel_message WHERE id = ? ";

    private static $SQL_SELECT_MESSAGES_FOR_CHANNEL =
        " SELECT DATE(cm.creation_date) AS creation_date_date, TIME_FORMAT(TIME(cm.creation_date), '%H:%i') AS creation_date_time, cm.creation_date, cm.id, cm.message, cm.channel_id, cm.user_id, COALESCE(cme.read_flag, 0) AS read_flag, COALESCE(cme.important_flag, 0) AS important_flag, CASE WHEN (cm.user_id = ?) THEN 1 ELSE 0 END AS owned_flag, u.username  FROM channel_message cm " .
        " LEFT OUTER JOIN channel_message_user_entry cme ON (cme.channel_message_id = cm.id AND cme.user_id = ?) " .
        " INNER JOIN user u on u.id = cm.user_id " .
        " WHERE cm.channel_id = ? " .
        " ORDER BY creation_date_date ASC, creation_date ASC, important_flag DESC ";

    private static $SQL_SELECT_IMPORTANT_MESSAGES_FOR_CHANNEL =
        " SELECT DATE(cm.creation_date) AS creation_date_date, TIME_FORMAT(TIME(cm.creation_date), '%H:%i') AS creation_date_time, cm.creation_date, cm.id, cm.message, cm.channel_id, cm.user_id, COALESCE(cme.read_flag, 0) AS read_flag, COALESCE(cme.important_flag, 0) AS important_flag, CASE WHEN (cm.user_id = ?) THEN 1 ELSE 0 END AS owned_flag, u.username  FROM channel_message cm " .
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

        $stmtString = null;
        $stmtGetMessages = null;
        $stmtInsertUserEntry = null;
        $res = array();
        $stmtString = "";
        $favorite = (boolean)$favoriteOnly;
        $p1 = (integer)$userId;
        $p2 = (integer)$channelId;

        try {
            // only favorite ones
            if ($favorite) {
                $stmtString = self::$SQL_SELECT_IMPORTANT_MESSAGES_FOR_CHANNEL;
            } // all messages
            else {
                $stmtString = self::$SQL_SELECT_MESSAGES_FOR_CHANNEL;
            }

            // retrieve messages for this channel
            $stmtGetMessages = parent::prepareStatement($stmtString);
            $stmtGetMessages->bind_param("iii", $p1, $p1, $p2);
            $stmtGetMessages->execute();
            $result = $stmtGetMessages->get_result();
            $data = null;
            $userEntryArgs = array();
            while ($data = $result->fetch_object()) {
                $res[] = $data;
                // get all message ids which the given user does not own and has not read yet
                if ((!$favorite) && (!$data->owned_flag) && (!$data->read_flag)) {
                    $userEntryArgs[] = $data->id;
                }
            }

            // create user entries for all messages which haven't been read yet
            if (!empty($userEntryArgs)) {
                parent::startTx();

                $stmtString = ChannelMessageUserEntryEntityController::$SQL_INSERT_CHANNEL_MESSAGE_USER_ENTRY;
                $stmtInsertUserEntry = parent::prepareStatement($stmtString);
                foreach ($userEntryArgs as $id) {
                    $p2 = $id;
                    $p3 = 1;
                    $p4 = 1;
                    $p5 = 0;
                    $stmtInsertUserEntry->bind_param("iiiii", $p1, $p2, $p3, $p4, $p5);
                    $stmtInsertUserEntry->execute();
                }

                parent::commit();
            }

        } catch (\Exception $e) {
            throw new DbException("Error on executing query: '" . $stmtString . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmtGetMessages)) {
                $stmtGetMessages->free_result();
                $stmtGetMessages->close();
            }
            if (isset($stmtCreateUserEntry)) {
                $stmtCreateUserEntry->close();
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

        $stmtInsertMessage = null;
        $stmtInsertUserEntry = null;
        $stmtString = "";
        $p1 = (integer)$args["userId"];
        $p2 = (integer)$args["channelId"];
        $p3 = (string)$args["message"];
        $p4 = (!empty($args["markRead"])) ? (integer)$args["markRead"] : 0;
        $p5 = (!empty($args["readFlag"])) ? ((boolean)$args["readFlag"]) : 0;
        $p6 = (!empty($args["importantFlag"])) ? ((boolean)$args["importantFlag"]) : 0;

        try {
            parent::startTx();
            $stmtString = self::$SQL_INSERT_CHANNEL_MESSAGE;

            // insert message
            $stmtInsertMessage = parent::prepareStatement($stmtString);
            $stmtInsertMessage->bind_param("iis", $p1, $p2, $p3);
            $stmtInsertMessage->execute();

            // insert message user entry
            $p2 = $stmtInsertMessage->insert_id;
            $stmtString = ChannelMessageUserEntryEntityController::$SQL_INSERT_CHANNEL_MESSAGE_USER_ENTRY;
            $stmtInsertUserEntry = parent::prepareStatement($stmtString);
            $stmtInsertUserEntry->bind_param("iiiii", $p1, $p2, $p4, $p5, $p6);
            $stmtInsertUserEntry->execute();

            parent::commit();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query: '" . $stmtString . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmtInsertMessage)) {
                $stmtInsertMessage->close();
            }
            if (isset($stmtInsertUserEntry)) {
                $stmtInsertUserEntry->close();
            }
            parent::close();
        }
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
            $stmtCheckFollowingResult = $stmtCheckFollowing->get_result();
            // check if post have been followed
            if ($stmtCheckFollowingResult->num_rows == 0) {
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
        parent::open();

        $stmt = null;
        $result = false;
        $p1 = (string)$args["message"];
        $p2 = (integer)$args["messageId"];
        $p3 = (integer)$args["userId"];

        try {
            parent::startTx();

            // insert message
            $stmt = parent::prepareStatement(self::$SQL_UPDATE_MESSAGE);
            $stmt->bind_param("sii", $p1, $p2, $p3);
            $stmt->execute();
            $result = ($stmt->affected_rows == 1);

            parent::commit();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query: '" . self::$SQL_UPDATE_MESSAGE . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            parent::close();
        }

        return $result;
    }
}