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

    private static $SQL_INSERT_CHANNEL_USER_ENTRY = "INSERT INTO channel_user_entry (user_id, channel_id, favorite_flag) VALUES (?,?,?)";

    private static $SQL_UPDATE_RESET_FAVORITE_CHANNEL = "UPDATE channel_user_entry SET favorite_flag=0 WHERE favorite_flag=1 AND user_id=?";

    private static $SQL_UPDATE_SET_FAVORITE_CHANNEL = "UPDATE channel_user_entry SET favorite_flag=1 WHERE channel_id=? AND user_id=?";

    private static $SQL_DELETE_CHANNEL_USER_ENTRY = "DELETE FROM channel_user_entry WHERE channel_id = ? AND user_id = ?";

    public function __construct()
    {
        parent::__construct();
    }

    public function getById($id)
    {
        // TODO: Implement getById() method.
    }

    public function getAll()
    {

    }

    public function resetUserEntryFavoriteFlags($userId)
    {

        parent::open();

        $stmt = null;

        try {
            $stmt = parent::prepareStatement(self::$SQL_UPDATE_RESET_FAVORITE_CHANNEL);
            $p1 = (integer)$userId;
            $stmt->bind_param("ii", $p1);
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

    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
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

    function update($entity)
    {
        // TODO: Implement update() method.
    }

    public
    function delete($id)
    {
        parent::open();

        $stmt = null;
        $res = 0;

        try {
            $stmt = parent::prepareStatement(self::$SQL_DELETE_CHANNEL_USER_ENTRY);
            $p1 = $id["channelId"];
            $p2 = $id["userId"];
            $stmt->bind_param("ii", $p1, $p2);

            parent::startTx();
            $stmt->execute();
            parent::commit();
            $res = $stmt->affected_rows;
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query: '" . self::$SQL_CHECK_ACTIVE_USER_BY_USERNAME . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            parent::close();
        }
        return $res;
    }
}