<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 5:41 PM
 */

namespace source\db\controller;


use source\common\InternalErrorException;

class ChannelMessageUserEntrEntityController extends AbstractEntityController
{

    private static $SQL_INSERT_CHANNEL_MESSAGE_USER_ENTRY = "INSERT INTO channel_message_user_entry (user_id, channel_id, read_flag) VALUES (?,?,?)";

    private static $SQL_SELECT_MESSAGE_READ_FLAGS_FOR_CHANNEL =
        " SELECT DISTINCT cm.id AS channel_message_id, cme.read_flag AS read_flag FROM channel_message_user_entry cme " .
        " INNER JOIN channel_message cm ON cme.channel_message_id = cm.id " .
        " WHERE cm.channel_id = ? " .
        " AND cme.read_flag = 1 ";

    public function __construct()
    {
        parent::__construct();
    }

    public function getMessageReadFlagsForChannel($channelId)
    {
        parent::open();

        $stmt = null;
        $res = array();

        try {
            $stmt = parent::prepareStatement(self::$SQL_SELECT_MESSAGE_READ_FLAGS_FOR_CHANNEL);
            $p1 = (integer)$channelId;
            $stmt->bind_param("i", $p1);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = null;
            while ($data = $result->fetch_object()) {
                $res[$data->channel_message_id] = $data->read_flag;
            }
        } catch (\Exception $e) {
            throw new DbException("Error on executing query: '" . self::$SQL_SELECT_MESSAGE_READ_FLAGS_FOR_CHANNEL . "''" . PHP_EOL . "Error: '" . $e->getMessage());
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
        throw new InternalErrorException("Deletion of channel_message_user_entry entries not allowed");
    }

    public function persist(array $args)
    {

        parent::open();

        $stmt = null;

        try {
            $p1 = (integer)$args["userId"];
            $p2 = (integer)$args["channelId"];
            $p3 = (boolean)$args["readFlag"];

            parent::startTx();

            $stmt = parent::prepareStatement(self::$SQL_INSERT_CHANNEL_MESSAGE_USER_ENTRY);
            $stmt->bind_param("ii1", $p1, $p2, $p3);
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