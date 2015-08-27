<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 5:41 PM
 */

namespace source\db\controller;


use source\common\InternalErrorException;

class ChannelUserEntryEntityController extends AbstractEntityController
{

    public static $SQL_CHANNEL_USER_ENTRY_BY_ID = "SELECT * FROM channel_user_entry WHERE user_id = ? AND channel_id = ?";

    public static $SQL_INSERT_CHANNEL_USER_ENTRY = "INSERT INTO channel_user_entry (user_id, channel_id, favorite_flag) VALUES (?,?,?)";

    public static $SQL_UPDATE_RESET_FAVORITE_CHANNEL = "UPDATE channel_user_entry SET favorite_flag=0 WHERE favorite_flag=1 AND user_id=?";

    private static $SQL_UPDATE_SET_FAVORITE_CHANNEL = "UPDATE channel_user_entry SET favorite_flag=1 WHERE user_id=? AND channel_id=?";

    private static $SQL_DELETE_CHANNEL_USER_ENTRY = "DELETE FROM channel_user_entry WHERE user_id = ? AND channel_id = ?";

    public function __construct()
    {
        parent::__construct();
    }

    public function getById($id)
    {
        parent::open();

        $res = null;
        $stmt = null;
        $p1 = (integer)$id["userId"];
        $p2 = (integer)$id["channelId"];

        try {
            $stmt = parent::prepareStatement(self::$SQL_CHANNEL_USER_ENTRY_BY_ID);
            $stmt->bind_param("ii", $p1, $p2);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_object();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query '" . self::$SQL_CHANNEL_USER_ENTRY_BY_ID . "'" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->free_result();
                $stmt->close();
            }
            parent::close();
        }

        return $res;
    }


    public function setFavoriteChannel($userId, $channelId)
    {

        parent::open();

        $stmtReset = null;

        try {
            $stmtReset = parent::prepareStatement(self::$SQL_UPDATE_RESET_FAVORITE_CHANNEL);
            $p1 = (integer)$userId;
            $stmtReset->bind_param("i", $p1);
            $stmtSet = parent::prepareStatement(self::$SQL_UPDATE_SET_FAVORITE_CHANNEL);
            $p2 = (integer)$channelId;
            $stmtSet->bind_param("ii", $p1, $p2);
            parent::startTx();
            $stmtReset->execute();
            $stmtSet->execute();
            parent::commit();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing " . __CLASS__ . "#setFavoriteChannel(userId, channelId)" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmtReset)) {
                $stmtReset->close();
            }
            if (isset($stmtSet)) {
                $stmtSet->close();
            }
            parent::close();
        }
    }

    public function deleteById($id)
    {
        parent::open();

        $stmt = null;
        $result = false;

        try {
            $stmt = parent::prepareStatement(self::$SQL_DELETE_CHANNEL_USER_ENTRY);
            $p1 = (integer)$id["userId"];
            $p2 = (integer)$id["channelId"];
            $stmt->bind_param("ii", $p1, $p2);
            parent::startTx();
            $stmt->execute();
            $result = ($stmt->affected_rows == 1);
            parent::commit();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query: '" . self::$SQL_DELETE_CHANNEL_USER_ENTRY . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            if (isset($stmtSet)) {
                $stmtSet->close();
            }
            parent::close();
        }

        return $result;
    }

    public function persist(array $args)
    {

        parent::open();

        $stmt = null;

        try {
            $p1 = (integer)$args["userId"];
            $p2 = (integer)$args["channelId"];
            $p3 = (boolean)$args["favorite"];

            parent::startTx();

            // reset existing favorite flag
            if ($p3) {
                $stmt = parent::prepareStatement(self::$SQL_UPDATE_RESET_FAVORITE_CHANNEL);
                $stmt->bind_param("i", $p1);
                $stmt->execute();
            }

            $stmt = parent::prepareStatement(self::$SQL_INSERT_CHANNEL_USER_ENTRY);
            $stmt->bind_param("iii", $p1, $p2, $p3);
            $stmt->execute();
            parent::commit();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query: '" . self::$SQL_INSERT_CHANNEL_USER_ENTRY . "''" . PHP_EOL . "Error: '" . $e->getMessage());
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