<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 5:41 PM
 */

namespace source\db\controller;


use source\common\InternalErrorException;

class ChannelMessageEntityController extends AbstractEntityController
{

    private static $SQL_INSERT_CHANNEL_MESSAGE = "INSERT INTO channel_message (user_id, channel_id, message) VALUES (?,?,?)";

    private static $SQL_DELETE_CHANNEL_MESSAGE = "DELETE FROM channel_message WHERE id = ?";

    private static $SQL_SELECT_MESSAGES_FOR_CHANNEL =
        " SELECT DATE(cm.creation_date) AS creation_date_date, TIME_FORMAT(TIME(cm.creation_date), '%H:%m') AS creation_date_time, cm.id, cm.message, cm.channel_id, cm.user_id, COALESCE(cme.read_flag, 0) AS read_flag, COALESCE(cme.important_flag, 0) AS important_flag, CASE WHEN (cm.user_id = ?) THEN 1 ELSE 0 END AS owned_flag, u.username  FROM channel_message cm " .
        " LEFT OUTER JOIN channel_message_user_entry cme ON (cme.channel_message_id = cm.id AND cme.user_id = ?) " .
        " INNER JOIN user u on u.id = cm.user_id " .
        " WHERE cm.channel_id = ? " .
        " ORDER BY creation_date_date DESC, creation_date_time DESC, important_flag DESC ";

    public function __construct()
    {
        parent::__construct();
    }

    public function getMessagesForChannel($channelId, $userId)
    {
        parent::open();

        $stmt = null;
        $res = array();

        try {
            $stmt = parent::prepareStatement(self::$SQL_SELECT_MESSAGES_FOR_CHANNEL);
            $p1 = (integer)$userId;
            $p2 = (integer)$channelId;
            $stmt->bind_param("iii", $p1, $p1, $p2);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = null;
            while ($data = $result->fetch_object()) {
                $res[] = $data;
            }
        } catch (\Exception $e) {
            throw new DbException("Error on executing query: '" . self::$SQL_SELECT_MESSAGES_FOR_CHANNEL . "''" . PHP_EOL . "Error: '" . $e->getMessage());
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
        parent::open();

        $stmt = null;

        try {
            $stmt = parent::prepareStatement(self::$SQL_DELETE_CHANNEL_MESSAGE);
            $p1 = (integer)$id;
            $stmt->bind_param("i", $p1);
            parent::startTx();
            $stmt->execute();
            parent::commit();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query: '" . self::$SQL_DELETE_CHANNEL_MESSAGE . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            parent::close();
        }
    }

    public function persist(array $args)
    {

        parent::open();

        $stmt = null;

        try {
            $p1 = (integer)$args["userId"];
            $p2 = (integer)$args["channelId"];
            $p3 = (string)$args["message"];

            parent::startTx();

            $stmt = parent::prepareStatement(self::$SQL_INSERT_CHANNEL_MESSAGE);
            $stmt->bind_param("iis", $p1, $p2, $p3);
            parent::startTx();
            $stmt->execute();
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
    }

    function update(array $args)
    {
        // TODO: Implement update() method.
    }
}